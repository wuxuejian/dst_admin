<?php
/**
 * 车辆异常控制
 */
namespace backend\modules\carmonitor\controllers;
use backend\controllers\BaseController;
use backend\models\CarMoniExceptionCondition;
use backend\models\CarMoniExceptionConditionItem;
use backend\models\ConfigCategory;
use backend\models\CarAnomalyShotmessageRule;
use yii;
use yii\data\Pagination;
class ExceptionControlController extends BaseController
{
    /**
     * 车辆
     * carmonitor/exception-control/list
     */
    public function actionList()
    {
        $buttons = $this->getCurrentActionBtn();
        $configItems = ['battery_type'];
        $config = (new ConfigCategory())->getCategoryConfig($configItems,'value');
        return $this->render('list',[
            'buttons'=>$buttons,
            'config'=>$config
        ]);
    }

    /**
     * 查看异常告警参数明细
     * carmonitor/exception-control/get-list-data
     */
    public function actionGetListData()
    {
        $query = CarMoniExceptionCondition::find()
            ->select([
                '{{%car_moni_exception_condition}}.`id`',
                '{{%car_moni_exception_condition}}.`battery_type`',
                '{{%car_moni_exception_condition}}.`add_datetime`',
                '{{%admin}}.`username`',
            ])->joinWith('admin',false);
        $query->andFilterWhere(['{{%car_moni_exception_condition}}.battery_type'=>yii::$app->request->get('battery_type')]);
        $query->andFilterWhere(['>=','{{%car_moni_exception_condition}}.add_datetime',yii::$app->request->get('add_datetime_start')]);
        $query->andFilterWhere(['<=','{{%car_moni_exception_condition}}.add_datetime',yii::$app->request->get('add_datetime_end')]);
        $query->andFilterWhere(['{{%admin}}.username'=>yii::$app->request->get('username')]);
        //排序开始
        $sortColumn = yii::$app->request->get('sort');
        $sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
        $orderBy = '';
        if($sortColumn){
            switch ($sortColumn) {
                case 'username':
                    $orderBy = '{{%admin}}.`'.$sortColumn.'` ';
                    break;
                default:
                    $orderBy = '{{%car_moni_exception_condition}}.`'.$sortColumn.'` ';
                    break;
            }
            
        }else{
           $orderBy = '{{%car_moni_exception_condition}}.`id` ';
        }
        $orderBy .= $sortType;
        //排序结束
        $total = $query->count();
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)->limit($pages->limit)
            ->orderBy($orderBy)->asArray()->all();  
        $returnArr = [];
        $returnArr['rows'] = $data; 
        $returnArr['total'] = $total;
        return json_encode($returnArr);
    }

    /**
     * 新增报警标准
     * carmonitor/exception-control/add
     */
    public function actionAdd()
    {
        if(yii::$app->request->isPost){
            $returnArr = [
                'error'=>1,
                'msg'=>''
            ];
            //检测改电池类型是否已经添加报警标准
            $batteryType = yii::$app->request->post('battery_type');
            if(empty($batteryType)){
                $returnArr['msg'] = '电池类型不能为空！';
                return json_encode($returnArr);
            }
            $hasData = CarMoniExceptionCondition::find()->select(['id'])->where(['battery_type'=>$batteryType])->one();
            if($hasData){
                $returnArr['msg'] = '该类型电池已经添加了报警标准，无法重复添加！';
                return json_encode($returnArr);
            }
            $transaction = yii::$app->db->beginTransaction();
            $carMoniExceptionConditionModel = new CarMoniExceptionCondition;
            $carMoniExceptionConditionModel->battery_type =  $batteryType;
            $carMoniExceptionConditionModel->add_uid = $_SESSION['backend']['adminInfo']['id'];
            $carMoniExceptionConditionModel->add_datetime = date('Y-m-d H:i:s');
            $res1 = $carMoniExceptionConditionModel->save(true);
            //保存具体条件项
            $saveItemHasError = false;
            foreach($_POST['alert_type']  as $key=>$val){
                $carMECIModel = new CarMoniExceptionConditionItem;
                $carMECIModel->battery_type = $batteryType;
                $carMECIModel->alert_type = $_POST['alert_type'][$key];
                $carMECIModel->max_min = $_POST['max_min'][$key];
                $carMECIModel->set_value = $_POST['set_value'][$key] ? $_POST['set_value'][$key] : 0;
                $carMECIModel->alert_level = $_POST['alert_level'][$key] ? intval($_POST['alert_level'][$key]) : 1;
                $carMECIModel->alert_dispose = $_POST['alert_dispose'][$key];
                $carMECIModel->alert_content = $_POST['alert_content'][$key];
                $carMECIModel->interval_time = $_POST['interval_time'][$key] ? intval($_POST['interval_time'][$key]) : 0;
                $carMECIModel->in_use = $_POST['in_use'][$key];
                if(!$carMECIModel->save(true)){
                    $saveItemHasError = true;
                    break;
                }
            }
            if($res1 && !$saveItemHasError){
                $returnArr['error'] = 0;
                $returnArr['msg'] = '新增报警标准成功！';
                $transaction->commit();//提交事务
            }else{
                $returnArr['error'] = 0;
                $returnArr['msg'] = '新增报警标准失败！';
                $transaction->rollback();//回滚事务
            }
            return json_encode($returnArr);
        }else{
            $configItems = ['battery_type'];
            $config = (new ConfigCategory())->getCategoryConfig($configItems,'value');
            return $this->render('add',[
                'config'=>$config
            ]);
        }
    }
    /**
     * 修改报警标准
     * carmonitor/exception-control/edit
     */
    public function actionEdit()
    {
        if(yii::$app->request->isPost){
            $returnArr = [
                'error'=>0,
                'msg'=>'修改成功！'
            ];
            foreach($_POST['id'] as $key=>$val){
                $model = CarMoniExceptionConditionItem::findOne(['id'=>$val]);
                $model->alert_type = $_POST['alert_type'][$key];
                $model->max_min = $_POST['max_min'][$key];
                $model->set_value = $_POST['set_value'][$key];
                $model->alert_level = $_POST['alert_level'][$key];
                $model->alert_dispose = $_POST['alert_dispose'][$key];
                $model->alert_content = $_POST['alert_content'][$key];
                $model->interval_time = $_POST['interval_time'][$key];
                $model->in_use = $_POST['in_use'][$key];
                $model->save();
            }
            return json_encode($returnArr);
        }else{
            $id = yii::$app->request->get('id');
            if(!$id){
                return '参数错误!';
            }
            $conditionInfo = CarMoniExceptionCondition::find()
                ->select(['battery_type'])
                ->where(['id'=>$id])->asArray()->one();
            if(!$conditionInfo){
                return '参数错误!';
            }
            $conditionItem = CarMoniExceptionConditionItem::find()
                ->where(['battery_type'=>$conditionInfo['battery_type']])
                ->asArray()->all();
            $conditionItemDeal = [];
            foreach($conditionItem as $val){
                $key = $val['alert_type'].'_'.$val['max_min'];
                if(!isset($conditionItemDeal[$key])){
                    $conditionItemDeal[$key] = [];
                }
                $conditionItemDeal[$key][] = $val;
            }
            return $this->render('edit',[
                'conditionItemDeal'=>$conditionItemDeal
            ]);
        }
    }
    /**
     * 删除报警标准
     * carmonitor/exception-control/remove
     */
    public function actionRemove()
    {
        $returnArr = [
            'error'=>1,
            'msg'=>'删除失败！',
        ];
        $id = yii::$app->request->get('id');
        $id = yii::$app->request->get('id');
        if(!$id){
            return json_encode($returnArr);
        }
        $conditionInfo = CarMoniExceptionCondition::find()
            ->select(['battery_type'])
            ->where(['id'=>$id])->asArray()->one();
        if(!$conditionInfo){
            return json_encode($returnArr);
        }
        if(CarMoniExceptionCondition::deleteAll(['id'=>$id])){
            CarMoniExceptionConditionItem::deleteAll(['battery_type'=>$conditionInfo['battery_type']]);
            $returnArr['error'] = 0;
            $returnArr['msg'] = '删除成功！';
        }
        return json_encode($returnArr);
    }

    /**
     * 查看报警标准明细
     * carmonitor/exception-control/detail
     */
    public function actionDetail()
    {
        $id = yii::$app->request->get('id');
        if(!$id){
            return '参数错误!';
        }
        $conditionInfo = CarMoniExceptionCondition::find()
            ->select(['battery_type'])
            ->where(['id'=>$id])->asArray()->one();
        if(!$conditionInfo){
            return '参数错误!';
        }
        $conditionItem = CarMoniExceptionConditionItem::find()
            ->where(['battery_type'=>$conditionInfo['battery_type']])
            ->asArray()->all();
        $conditionItemDeal = [];
        foreach($conditionItem as $val){
            $key = $val['alert_type'].'_'.$val['max_min'];
            if(!isset($conditionItemDeal[$key])){
                $conditionItemDeal[$key] = [];
            }
            $conditionItemDeal[$key][] = $val;
        }
        return $this->render('detail',[
            'conditionItemDeal'=>$conditionItemDeal
        ]);
    }

    /**
     * 设置报警短信推送规则
     * carmonitor/exception-control/alert-shot-message-rule
     */
    public function actionAlertShotMessageRule()
    {
        if(yii::$app->request->isPost){
            $returnArr = [
                'error'=>0,
                'msg'=>''
            ];
            $model = CarAnomalyShotmessageRule::findOne(['id'=>1]);
            if(!$model){
                $model = new CarAnomalyShotmessageRule;
            }
            $model->load(yii::$app->request->post(),'');
            $wdMobile = explode('|',yii::$app->request->post('wd_mobile'));
            $hdMobile = explode('|',yii::$app->request->post('hd_mobile'));
            if($wdMobile){
                $model->wd_mobile = '';
                foreach($wdMobile as $val){
                    $val = trim($val);
                    if(preg_match('/^1[3456789]\d{9}$/',$val)){
                        $model->wd_mobile .= $val.'|';
                    }
                }
                $model->wd_mobile = rtrim($model->wd_mobile,'|');
            }
            if($hdMobile){
                $model->hd_mobile = '';
                foreach($hdMobile as $val){
                    $val = trim($val);
                    if(preg_match('/^1[3456789]\d{9}$/',$val)){
                        $model->hd_mobile .= $val.'|';
                    }
                }
                $model->hd_mobile = rtrim($model->hd_mobile,'|');
            }
            if($model->save()){
                $returnArr['error'] = 0;
                $returnArr['msg'] = '设置成功！';
            }else{
                $returnArr['error'] = 1;
                $returnArr['msg'] = '设置失败！';
            }
            return json_encode($returnArr);
        }
        $model = CarAnomalyShotmessageRule::findOne(['id'=>1]);
        $data = [];
        if($model){
            $data = $model->getOldAttributes();
        }
        //var_dump($data);
        return $this->render('alert_shot_message_rule',[
            'data'=>$data,
        ]);
    }
}