<?php
/**
 * 交流侧瞬时量 控制器
 * time 2015-12-31 14:29
 * @author chengwk
 */
namespace backend\modules\polemonitor\controllers;
use backend\controllers\BaseController;
use backend\models\ChargeSpotsAlert;
use backend\models\ChargeSpotsAlertItem;
use backend\models\ChargeSpotsAlertShotmessageRule;
use backend\models\ChargeFrontmachine;
use backend\models\ChargeSpotsAlertDeal;
use common\models\Excel;
use yii;
use yii\data\Pagination;

class AlertController extends BaseController
{
    /**
     * 电桩异常报警
     * polemonitor/alert/index
     */
    public function actionIndex()
    {
        $buttons = $this->getCurrentActionBtn();
        return $this->render('index',[
            'buttons'=>$buttons,
        ]);
    }

    /**
     * 获取告警列表
     * polemonitor/alert/get-list
     */
    public function actionGetList()
    {
        $returnArr = [
            'rows'=>[],
            'total'=>0,
        ];
        $query = ChargeSpotsAlert::find()
            ->select([
                '{{%charge_spots_alert}}.*',
                '{{%charge_station}}.`cs_name`',
            ])->joinWith('chargeStation',false);
        //查询条件开始
        $searchCondition = [];
        $query->andFilterWhere(['like','{{%charge_station}}.`cs_name`',yii::$app->request->get('cs_name')]);
        $query->andFilterWhere(['like','{{%charge_spots_alert}}.`dev_addr`',yii::$app->request->get('dev_addr')]);
        $searchCondition['happen_datetime_start'] = yii::$app->request->get('happen_datetime_start');
        $searchCondition['happen_datetime_end'] = yii::$app->request->get('happen_datetime_end');
        if($searchCondition['happen_datetime_start']){
            $query->andWhere(['>=','{{%charge_spots_alert}}.`happen_datetime`',$searchCondition['happen_datetime_start'].' 00:00:00']);
        }
        if($searchCondition['happen_datetime_end']){
            $query->andWhere(['<=','{{%charge_spots_alert}}.`happen_datetime`',$searchCondition['happen_datetime_end'].' 23:59:59']);
        }
        $query->andFilterWhere(['>=','{{%charge_spots_alert}}.alert_level',yii::$app->request->get('alert_level_start')]);
        $query->andFilterWhere(['<=','{{%charge_spots_alert}}.alert_level',yii::$app->request->get('alert_level_end')]);
        $query->andFilterWhere(['{{%charge_spots_alert}}.status'=>yii::$app->request->get('status')]);
        $searchCondition['deal_datetime_start'] = yii::$app->request->get('deal_datetime_start');
        $searchCondition['deal_datetime_end'] = yii::$app->request->get('deal_datetime_end');
        if($searchCondition['deal_datetime_start']){
            $query->andFilterWhere(['>=','{{%charge_spots_alert}}.deal_datetime',$searchCondition['deal_datetime_start'].' 00:00:00']);
        }
        if($searchCondition['deal_datetime_end']){
            $query->andFilterWhere(['<=','{{%charge_spots_alert}}.deal_datetime',$searchCondition['deal_datetime_end'].' 23:59:59']);
        }
        //查询条件结束
        //排序开始
        $sortColumn = yii::$app->request->get('sort');
        $sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
        $orderBy = '';
        if($sortColumn){
            switch ($sortColumn) {
                case 'cs_name':
                    $orderBy = '{{%charge_station}}.`'.$sortColumn.'` ';
                    break;
                default:
                    $orderBy = '{{%charge_spots_alert}}.`'.$sortColumn.'` ';
                    break;
            }
            
        }else{
           $orderBy = '{{%charge_spots_alert}}.`id` ';
        }
        $orderBy .= $sortType;
        //排序结束
        $total = $query->count();
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)->limit($pages->limit)
            ->orderBy($orderBy)->asArray()->all();
        if($data){
            $dealRecord = ChargeSpotsAlertDeal::find()
                ->select([
                    'id'=>'max(id)',
                ])->where([
                    '`is_del`'=>0,
                    '`csa_id`'=>array_column($data,'id'),
                ])->groupBy('csa_id')->asArray()->all();
            $dealRecord = ChargeSpotsAlertDeal::find()
                ->select([
                    '{{%charge_spots_alert_deal}}.`csa_id`',
                    '{{%charge_spots_alert_deal}}.`status`',
                    '{{%charge_spots_alert_deal}}.`deal_way`',
                    '{{%admin}}.`username`'
                ])->joinWith('admin',false)
                ->where(['{{%charge_spots_alert_deal}}.`id`'=>array_column($dealRecord,'id')])
                ->indexBy('csa_id')->asArray()->all();
            foreach($data as $key=>$val){
                if(isset($dealRecord[$val['id']])){
                    $data[$key] = array_merge($data[$key],$dealRecord[$val['id']]);
                }
            }
        }
        $returnArr['rows'] = $data; 
        $returnArr['total'] = $total;
        return json_encode($returnArr);
    }

    /**
     * 设置报警参数
     * polemonitor/alert/set-param
     */
    public function actionSetParam()
    {
        if(yii::$app->request->isPost){
            $returnArr = [
                'error'=>0,
                'msg'=>'报警参数设置成功！',
            ];
            foreach(yii::$app->request->post('id') as $key=>$val){
                $model = ChargeSpotsAlertItem::findOne(['id'=>$val]);
                if($model){
                    $model->alert_level = yii::$app->request->post('alert_level')[$key];
                    $model->alert_dispose = yii::$app->request->post('alert_dispose')[$key];
                    $model->alert_content = yii::$app->request->post('alert_content')[$key];
                    $model->alert_content = htmlspecialchars($model->alert_content);
                    $model->in_use = yii::$app->request->post('in_use')[$key];
                    $model->save(true);
                }
            }
            return json_encode($returnArr);
        }
        $alertProject = ChargeSpotsAlertItem::find()->asArray()->all();
        return $this->render('set-param',[
            'alertProject'=>$alertProject
        ]);
    }

    /**
     * 设置短信报警机制
     * polemonitor/alert/shotmessage-rule
     */
    public function actionShotmessageRule()
    {
        if(yii::$app->request->isPost){
            $returnArr = [
                'error'=>0,
                'msg'=>'',
            ];
            $model = ChargeSpotsAlertShotmessageRule::findOne(1);
            $model->load(yii::$app->request->post(),'');
            $model->wd_mobile = str_replace('|',',',$model->wd_mobile);
            $model->hd_mobile = str_replace('|',',',$model->hd_mobile);
            if($model->save()){
                $returnArr['msg'] = '设置成功！';
            }else{
                $returnArr['error'] = 1;
                $returnArr['msg'] = '设置失败！';
            }
            return json_encode($returnArr);
        }
        $data = ChargeSpotsAlertShotmessageRule::findOne(1);
        if($data){
            $data = $data->getOldAttributes();
        }else{
            $data = [];
        }
        return $this->render('shotmessage-rule',[
            'data'=>$data,
        ]);
    }

    /**
     * 异常告警处理
     * polemonitor/alert/deal
     */
    public function actionDeal()
    {
        //post请求开始
        if(yii::$app->request->isPost){
            $returnArr = [
                'error'=>0,
                'msg'=>'操作成功！',
            ];
            $csaId = yii::$app->request->get('csa_id');
            if(empty($csaId)){
                $returnArr['error'] = 1;
                $returnArr['msg'] = '参数错误！';
                return json_encode($returnArr);
            }
            //删除处理项目
            if(empty(yii::$app->request->post())){
                ChargeSpotsAlertDeal::updateAll([
                    'is_del'=>1
                ],[
                    'csa_id'=>$csaId
                ]);   
            }else{
                $saveId = array_unique(yii::$app->request->post('id'));
                //var_dump($saveId);
                ChargeSpotsAlertDeal::updateAll([
                    'is_del'=>1
                ],[
                    'and',
                    ['=','csa_id',$csaId],
                    ['not in','id',$saveId]
                ]);
            }
            //修改或更新
            if(empty(yii::$app->request->post())){
                return json_encode($returnArr);
            }
            foreach(yii::$app->request->post('id') as $key=>$val){
                switch (yii::$app->request->post('status')[$key]) {
                    case '已受理':
                        $status = 'acceptance';
                        break;
                    case '处理中':
                        $status = 'processing';
                        break;
                    default:
                        $status = 'end';
                        break;
                }
                $dealDate = yii::$app->request->post('deal_date')[$key];
                if(empty($dealDate)){
                    continue;
                }
                if($val){
                    $model = ChargeSpotsAlertDeal::findOne(['id'=>$val]);
                    if(!$model){
                        continue;
                    }
                    $model->status = $status;
                    $model->deal_way = yii::$app->request->post('deal_way')[$key];
                    $model->deal_date = $dealDate;
                }else{
                    $model = new ChargeSpotsAlertDeal;
                    $model->csa_id = $csaId;
                    $model->status = $status;
                    $model->deal_way = yii::$app->request->post('deal_way')[$key];
                    $model->deal_date = $dealDate;
                    $model->reg_aid = $_SESSION['backend']['adminInfo']['id'];
                }
                $model->save();
                
            }
            //更新异常报警状态为最新记录状态
            $lastRecord = ChargeSpotsAlertDeal::find()
                ->select(['status','deal_date'])
                ->where(['csa_id'=>$csaId,'is_del'=>0])
                ->orderBy('id desc')->limit(1)->asArray()->one();
            if($lastRecord){
                ChargeSpotsAlert::updateAll([
                    'status'=>$lastRecord['status'],
                    'deal_date'=>$lastRecord['deal_date']
                ],['id'=>$csaId]);
            }else{
                ChargeSpotsAlert::updateAll([
                    'status'=>'new',
                    'deal_date'=>''
                ],['id'=>$csaId]);
            }
            return json_encode($returnArr);
        }
        //post请求结束
        $csaId = yii::$app->request->get('id');
        if(empty($csaId)){
            throw new \yii\web\HttpException(404, 'The requested could not be found.');
        }
        //查询电桩信息
        $alertRecord = ChargeSpotsAlert::find()
            ->select([
                '{{%charge_spots_alert}}.`id`',
                '{{%charge_spots_alert}}.`dev_addr`',
                '{{%charge_spots_alert}}.`pole_status`',
                '{{%charge_station}}.`cs_name`',
            ])->joinWith('chargeStation',false)
            ->andWhere(['{{%charge_spots_alert}}.`id`'=>$csaId])
            ->asArray()->limit(1)->one();
        if(empty($alertRecord)){
            throw new \yii\web\HttpException(404, 'The requested could not be found.');
        }
        switch ($alertRecord['pole_status']) {
            case 0:
                $alertRecord['pole_status'] = '充电';
                break;
            case 1:
                $alertRecord['pole_status'] = '待机';
                break;
            case 2:
                $alertRecord['pole_status'] = '故障';
                break;
            case 3:
                $alertRecord['pole_status'] = '禁用';
                break;
            default:
                $alertRecord['pole_status'] = '离线';
                break;
        }
        return $this->render('deal',[
            'csaId'=>$csaId,
            'alertRecord'=>$alertRecord
        ]);
    }

    /**
     * 获取电桩异常告警处理列表
     * polemonitor/alert/get-deal-record
     */
    public function actionGetDealRecord()
    {
        $returnArr = [
            'rows'=>[],
            //'total'=>0
        ];
        $returnArr['rows'] = ChargeSpotsAlertDeal::find()
            ->where([
                'is_del'=>0,
                'csa_id'=>yii::$app->request->get('csa_id')
            ])->asArray()->all();
        if($returnArr['rows']){
            foreach($returnArr['rows'] as &$alertDealItem){
                switch ($alertDealItem['status']) {
                    case 'acceptance':
                        $alertDealItem['status'] = '已受理';
                        break;
                    case 'processing':
                        $alertDealItem['status'] = '处理中';
                        break;
                    default:
                        $alertDealItem['status'] = '已完结';
                        break;
                }
            }
        }
        return json_encode($returnArr);
    }

    /**
     * 按条件导出
     * polemonitor/alert/export-with-condition
     */
    public function actionExportWithCondition()
    {
        $query = ChargeSpotsAlert::find()
            ->select([
                '{{%charge_spots_alert}}.*',
                '{{%charge_station}}.`cs_name`',
            ])->joinWith('chargeStation',false);
        //查询条件开始
        $searchCondition = [];
        $query->andFilterWhere(['like','{{%charge_station}}.`cs_name`',yii::$app->request->get('cs_name')]);
        $query->andFilterWhere(['like','{{%charge_spots_alert}}.`dev_addr`',yii::$app->request->get('dev_addr')]);
        $searchCondition['happen_datetime_start'] = yii::$app->request->get('happen_datetime_start');
        $searchCondition['happen_datetime_end'] = yii::$app->request->get('happen_datetime_end');
        if($searchCondition['happen_datetime_start']){
            $query->andWhere(['>=','{{%charge_spots_alert}}.`happen_datetime`',$searchCondition['happen_datetime_start'].' 00:00:00']);
        }
        if($searchCondition['happen_datetime_end']){
            $query->andWhere(['<=','{{%charge_spots_alert}}.`happen_datetime`',$searchCondition['happen_datetime_end'].' 23:59:59']);
        }
        $query->andFilterWhere(['>=','{{%charge_spots_alert}}.alert_level',yii::$app->request->get('alert_level_start')]);
        $query->andFilterWhere(['<=','{{%charge_spots_alert}}.alert_level',yii::$app->request->get('alert_level_end')]);
        $query->andFilterWhere(['{{%charge_spots_alert}}.status'=>yii::$app->request->get('status')]);
        $searchCondition['deal_datetime_start'] = yii::$app->request->get('deal_datetime_start');
        $searchCondition['deal_datetime_end'] = yii::$app->request->get('deal_datetime_end');
        if($searchCondition['deal_datetime_start']){
            $query->andFilterWhere(['>=','{{%charge_spots_alert}}.deal_datetime',$searchCondition['deal_datetime_start'].' 00:00:00']);
        }
        if($searchCondition['deal_datetime_end']){
            $query->andFilterWhere(['<=','{{%charge_spots_alert}}.deal_datetime',$searchCondition['deal_datetime_end'].' 23:59:59']);
        }
        //查询条件结束
        //排序开始
        $sortColumn = yii::$app->request->get('sort');
        $sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
        $orderBy = '';
        if($sortColumn){
            switch ($sortColumn) {
                case 'cs_name':
                    $orderBy = '{{%charge_station}}.`'.$sortColumn.'` ';
                    break;
                default:
                    $orderBy = '{{%charge_spots_alert}}.`'.$sortColumn.'` ';
                    break;
            }
            
        }else{
           $orderBy = '{{%charge_spots_alert}}.`id` ';
        }
        $orderBy .= $sortType;
        //排序结束
        /*$total = $query->count();
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);*/
        $data = $query/*->offset($pages->offset)->limit($pages->limit)*/
            ->orderBy($orderBy)->asArray()->all();
        if($data){
            $dealRecord = ChargeSpotsAlertDeal::find()
                ->select([
                    'id'=>'max(id)',
                ])->where([
                    '`is_del`'=>0,
                    '`csa_id`'=>array_column($data,'id'),
                ])->groupBy('csa_id')->asArray()->all();
            $dealRecord = ChargeSpotsAlertDeal::find()
                ->select([
                    '{{%charge_spots_alert_deal}}.`csa_id`',
                    '{{%charge_spots_alert_deal}}.`status`',
                    '{{%charge_spots_alert_deal}}.`deal_way`',
                    '{{%admin}}.`username`'
                ])->joinWith('admin',false)
                ->where(['{{%charge_spots_alert_deal}}.`id`'=>array_column($dealRecord,'id')])
                ->indexBy('csa_id')->asArray()->all();
            //excel column
            $excelColumn = [
                'cs_name'=>'充电站',
                'dev_addr'=>'充电桩',
                'alert_name'=>'报警项目',
                'alert_level'=>'报警级别',
                'event_code'=>'报警编码',
                'alert_dispose'=>'报警处理方式',
                'alert_content'=>'报警内容',
                'event_desc'=>'故障描述',
                'happen_datetime'=>'报警时间',
                'times'=>'报警次数',
                'status'=>'报警处理状态',
                'deal_date'=>'处理时间',
                'deal_way'=>'处理方法',
                'username'=>'操作人员'
            ];
            $excelObj = new Excel();
            $excelObj->setHeader([
                'creator'=>'皓峰通讯',
                'lastModifiedBy'=>'hao feng tong xun'
            ]);
            $lineData = [];
            foreach($excelColumn as $val){
                $lineData[] = ['content'=>$val,'width'=>15];
            }
            $excelObj->addLineToExcel($lineData);
            foreach($data as $val){
                if(isset($dealRecord[$val['id']])){
                    $val = array_merge($val,$dealRecord[$val['id']]);
                }
                switch($val['alert_dispose']){
                    case 0:
                        $val['alert_dispose'] = '不报警';
                        break;
                    case 1:
                        $val['alert_dispose'] = '后台报警';
                        break;
                    case 2:
                        $val['alert_dispose'] = '后台报警，短信报警';
                        break;
                }
                switch($val['status']){
                    case 'new':
                        $val['status'] = '未处理';
                        break;
                    case 'no_need':
                        $val['status'] = '无需处理';
                        break;
                    case 'acceptance':
                        $val['status'] = '已受理';
                        break;
                    case 'processing':
                        $val['status'] = '处理中';
                        break;
                    case 'end':
                        $val['status'] = '已完结';
                        break;
                }
                $lineData = [];
                foreach ($excelColumn as $k => $v) {
                    if(isset($val[$k])){
                        $lineData[] = ['content'=>$val[$k]];
                    }else{
                        $lineData[] = ['content'=>''];
                    }
                    
                }
                $excelObj->addLineToExcel($lineData);
            }
            //下载
            $objPHPExcel = $excelObj->getPHPExcel();
            header("Content-type: application/octet-stream"); 
            header("Accept-Ranges: bytes"); 
            //header("Accept-Length:".$fileSize); 
            header("Content-Disposition: attachment; filename=".iconv('utf-8','gbk','电桩异常报警').'.xls'); 
            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
        }
        return '<script>alert("无数据导出！")</script>';
    }

    /**
     * 后台报警
     * polemonitor/alert/pole-back-alert
     */
    public function actionPoleBackAlert()
    {
        if(yii::$app->request->isPost){
            $startId = yii::$app->request->post('start_id');
            $startId = $startId ? intval($startId) : 0;
            $query = ChargeSpotsAlert::find()
                ->select([
                    '{{%charge_spots_alert}}.`id`',
                    '{{%charge_spots_alert}}.`dev_addr`',
                    '{{%charge_spots_alert}}.`pole_status`',
                    '{{%charge_spots_alert}}.`alert_name`',
                    '{{%charge_spots_alert}}.`alert_content`',
                    '{{%charge_station}}.`cs_name`'
                ])->joinWith('chargeStation',false)
                ->andWhere(['>','{{%charge_spots_alert}}.`alert_dispose`',0])
                ->andWhere(['>','{{%charge_spots_alert}}.`id`',$startId]);
            $data = $query->orderBy('{{%charge_spots_alert}}.`id` asc')->asArray()->all();
            return json_encode($data);
        }
        //返回该值表示用户有权限查看后台告警
        $returnArr = [
            'error'=>0,
            'max_id'=>0,
        ];
        $maxId = ChargeSpotsAlert::find()
            ->select(['max_id'=>'max(id)'])->asArray()->limit(1)->one();
        if($maxId){
            $returnArr['max_id'] = $maxId['max_id'];
        }
        return json_encode($returnArr);
    }
}