<?php
/**
 * 车辆故障处理进度 控制器
 * @author chengwk
 * @date 2016-02-17
 */
namespace backend\modules\car\controllers;
use backend\controllers\BaseController;
use backend\models\ConfigCategory;
use backend\models\Car;
use backend\models\CarFault;
use backend\models\CarFaultDisposeProgress;
use backend\models\CarLetRecord;
use backend\models\CarTrialProtocolDetails;
use yii;
use yii\data\Pagination;
use backend\classes\UserLog;

class FaultDisposeProgressController extends BaseController
{
    
    /**
     * 获取某故障的所有维修进度
     */
    public function actionGetProgressList()
    {
        $faultId = yii::$app->request->get('faultId') or die('Param(faultId) not found!');
        $query = CarFaultDisposeProgress::find()
            ->select([
                '{{%car_fault_dispose_progress}}.*',
                '{{%admin}}.username'
            ])
            ->joinWith('admin')
            ->where(['fault_id'=>$faultId])
            ->andWhere(['{{%car_fault_dispose_progress}}.is_del'=>0]);
        $total = $query->count();
        $pageSize = isset($_GET['rows']) && $_GET['rows']<=50 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)->limit($pages->limit)->orderBy('id desc')->asArray()->all();
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        echo json_encode($returnArr);
    }

    /**
     * 新增进度
     */
    public function actionAdd()
    {
        if(yii::$app->request->isPost){
            $formData = yii::$app->request->post();
            //检查当前登录用户和要操作的车辆的所属运营公司是否匹配-20160325
            $fault = CarFault::find()->select(['car_id'])->where(['id'=>$formData['fault_id']])->asArray()->one();
            $checkArr = Car::checkOperatingCompanyIsMatch($fault['car_id']);
            if(is_array($checkArr) && isset($checkArr['status']) && !$checkArr['status']){
                return json_encode(['status'=>false,'info'=>$checkArr['info']]);
            }
            $model = new CarFaultDisposeProgress();
            $model->load($formData,'');
            $returnArr = [];
            if($model->validate()){
                $model->create_time = date('Y-m-d H:i:s');
                $model->creator_id = $_SESSION['backend']['adminInfo']['id'];
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '新增进度成功！';
                    // 同步更新故障表里的故障状态！
                    $CarFaultModel = CarFault::findOne($model->fault_id);
                    $CarFaultModel->fault_status = $model->fault_status;
                    $CarFaultModel->save();
                    // 同步更新车辆状态！
//                     Car::changeCarStatus($CarFaultModel->car_id);
                    // 添加日志
                    $logStr = "为故障【" . ($CarFaultModel->number) . "】新增了维修进度！";
                    UserLog::log($logStr, 'sys');
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '新增进度失败！';
                }
            }else{
                $returnArr['status'] = false;
                $error = $model->getErrors();
                if($error){
                    $arr = array_column($error,0);
                    $errorStr = join('',$arr);
                    $returnArr['info'] = $errorStr;
                }else{
                    $returnArr['info'] = '未知错误';
                }
            }
            return json_encode($returnArr);
        }else{
            $faultId = intval(yii::$app->request->get('faultId')) or die("Not pass 'faultId'.");
            //获取combo配置数据
            $configItems = ['fault_status'];
            $config = (new ConfigCategory())->getCategoryConfig($configItems,'value');
            return $this->render('addEditWin',[
                'config'=>$config,
                'faultId'=>$faultId,
                'initData'=>[
                    'action'=>'add'
                ]
            ]);
        }
    }

    //修改进度
    public function actionEdit()
    {
        if(yii::$app->request->isPost){
            $formData = yii::$app->request->post();
            //print_r($formData);exit;
            //检查当前登录用户和要操作的车辆的所属运营公司是否匹配-20160325
            $fault = CarFault::find()->select(['car_id'])->where(['id'=>$formData['fault_id']])->asArray()->one();
            $checkArr = Car::checkOperatingCompanyIsMatch($fault['car_id']);
            if(is_array($checkArr) && isset($checkArr['status']) && !$checkArr['status']){
                return json_encode(['status'=>false,'info'=>$checkArr['info']]);
            }
            $progress_id = intval(yii::$app->request->post('id')) or die("Not pass 'id'.");
            $model = CarFaultDisposeProgress::findOne(['id'=>$progress_id]) or die('Not find corresponding record.');
            $model->load($formData,'');
            $returnArr = [];
            if($model->validate()){
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '修改进度成功！';
                    // 同步更新故障表里的故障状态！
                    $CarFaultModel = CarFault::findOne($model->fault_id);
                    $CarFaultModel->fault_status = $model->fault_status;
                    $CarFaultModel->save();
                    // 同步更新车辆状态！
//                     Car::changeCarStatus($CarFaultModel->car_id);
                    // 添加日志
                    $logStr = "为故障【" . ($CarFaultModel->number) . "】修改了维修进度！";
                    UserLog::log($logStr, 'sys');
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '修改进度失败！';
                }
            }else{
                $returnArr['status'] = false;
                $error = $model->getErrors();
                if($error){
                    $arr = array_column($error,0);
                    $errorStr = join('',$arr);
                    $returnArr['info'] = $errorStr;
                }else{
                    $returnArr['info'] = '未知错误';
                }
            }
            return json_encode($returnArr);
        }else{
            $faultId = intval(yii::$app->request->get('faultId')) or die("Not pass 'faultId'.");
            $progress_id = intval(yii::$app->request->get('progress_id')) or die("Not pass 'progress_id'.");
            $progressInfo = CarFaultDisposeProgress::find()->where(['id'=>$progress_id,'is_del'=>0])->asArray()->one();
            //获取combo配置数据
            $configItems = ['fault_status'];
            $config = (new ConfigCategory())->getCategoryConfig($configItems,'value');
            return $this->render('addEditWin',[
                'config'=>$config,
                'faultId'=>$faultId,
                'initData'=>[
                    'action'=>'edit',
                    'progressInfo'=>$progressInfo
                ]
            ]);
        }
    }

    /**
     * 删除进度
     */
    public function actionRemove()
    {
        $progress_id = intval(yii::$app->request->get('progress_id')) or die("Not pass 'progress_id'.");
        $model = CarFaultDisposeProgress::findOne($progress_id);
        //检查当前登录用户和要操作的车辆的所属运营公司是否匹配-20160325
        $fault = CarFault::find()->select(['car_id'])->where(['id'=>$model->fault_id])->asArray()->one();
        $checkArr = Car::checkOperatingCompanyIsMatch($fault['car_id']);
        if(is_array($checkArr) && isset($checkArr['status']) && !$checkArr['status']){
            return json_encode(['status'=>false,'info'=>$checkArr['info']]);
        }
        $model->is_del = 1;
        if($model->save()){
            $returnArr['status'] = true;
            $returnArr['info'] = '删除进度成功！';
        }else{
            $returnArr['status'] = false;
            $returnArr['info'] = '删除进度失败！';
        }
        echo json_encode($returnArr);
    }



}