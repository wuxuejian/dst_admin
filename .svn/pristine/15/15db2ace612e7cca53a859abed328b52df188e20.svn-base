<?php
/**
 * @Desc:	充电卡管理 控制器
 * @author: chengwk
 * @date:	2016-01-22
 */
namespace backend\modules\card\controllers;
use backend\controllers\BaseController;
use yii;
use yii\data\Pagination;
use backend\models\ConfigCategory;
use backend\models\ChargeCard;
use backend\models\ChargeCardRechargeRecord;
use backend\models\ChargeFrontmachine;
use backend\models\Vip;
use backend\classes\UserLog;//日志类
use common\models\Excel;

class ChargeCardController extends BaseController
{
    /**
     * 访问“充电卡管理”视图
     */
    public function actionIndex()
    {
		$configItems = ['cc_type','cc_status'];
        $data['config'] = (new ConfigCategory())->getCategoryConfig($configItems,'value'); 
		$data['buttons'] = $this->getCurrentActionBtn();
        return $this->render('index',$data);
    }
    
    /**
     * 获取“充电卡”列表
     */
    public function actionGetList()
    {
        $returnArr = [];
        $returnArr['rows'] = [];
        $returnArr['total'] = 0;
        $query = ChargeCard::find()
            ->select([
                '{{%charge_card}}.*',
               'cc_holder_code'=> '{{%vip}}.code',
               'cc_holder_mobile'=> '{{%vip}}.mobile',
               'cc_holder_name'=> '{{%vip}}.client',
               'cc_creator'=> '{{%admin}}.username'
            ])
            ->joinWith('vip',false,'LEFT JOIN')
            ->joinWith('admin',false,'LEFT JOIN')
            ->where(['cc_is_del'=>0]);
        //查询条件
        $query->andFilterWhere(['LIKE','cc_code',yii::$app->request->get('cc_code')]);
        $query->andFilterWhere(['=','cc_type',yii::$app->request->get('cc_type')]);
        $query->andFilterWhere(['=','cc_status',yii::$app->request->get('cc_status')]);
        $query->andFilterWhere(['LIKE','{{%vip}}.client',yii::$app->request->get('cc_holder_client')]);
        $query->andFilterWhere(['LIKE','{{%vip}}.mobile',yii::$app->request->get('cc_holder_mobile')]);
        $query->andFilterWhere(['>=','cc_start_date',yii::$app->request->get('cc_start_date_start')]);
        $query->andFilterWhere(['<=','cc_start_date',yii::$app->request->get('cc_start_date_end')]);
        $total = $query->count();
        $query2 = clone $query;
        //分页
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
		//排序
		if(yii::$app->request->get('sort')){
			$field = yii::$app->request->get('sort');		//field
			$direction = yii::$app->request->get('order');  //asc or desc
			$orderStr = $field.' '.$direction;
		}else{
			$orderStr = 'cc_id DESC';
		}	
        $data = $query->offset($pages->offset)->limit($pages->limit)->orderBy($orderStr)->asArray()->all();
        if($data){
            //如果有记录则查询所有卡的最新充电记录用于比对卡余额
            $sql = '';
            foreach($data as $v){
                $v['cc_code'] = addslashes($v['cc_code']);
                $sql .= '(SELECT DEAL_END_DATE,START_CARD_NO,REMAIN_AFTER_DEAL FROM `charge_record` where START_CARD_NO = "'.$v['cc_code'].'" and DEAL_TYPE in (1,2) ORDER BY DEAL_END_DATE desc limit 1) union ';
            }
            $sql = rtrim($sql,'union ');
            //链接前置机数据库
            $fmConnection = ChargeFrontmachine::connect();
            if(!$fmConnection[0]){
                return json_encode($returnArr);
            }
            $chargeRecord = $fmConnection[1]
                ->createCommand($sql)
                ->queryAll();
            if($chargeRecord){
                foreach($chargeRecord as $k=>$v){
                    unset($chargeRecord[$k]);
                    $chargeRecord[$v['START_CARD_NO']] = $v;
                }
                foreach($data as &$cardItem){
                    $updateTime = 0;
                    $chargeTime = 0;
                    if($cardItem['cm_update_datetime']){
                        $updateTime = strtotime($cardItem['cm_update_datetime']);
                    }
                    if(isset($chargeRecord[$cardItem['cc_code']])){
                        $chargeTime = strtotime($chargeRecord[$cardItem['cc_code']]['DEAL_END_DATE']);
                    }
                    if($chargeTime > $updateTime){
                        $cardItem['cc_current_money'] = $chargeRecord[$cardItem['cc_code']]['REMAIN_AFTER_DEAL'];
                    }
                }
            }
        }
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        // 表格底部增加合计行
        $mySum = $query2->select(['cc_current_money'=>'SUM(cc_current_money)'])->asArray()->one();
        $returnArr['footer'] = [[
            'cc_code'=>'合计：',
            'cc_current_money'=>$mySum['cc_current_money']
        ]];
        return json_encode($returnArr);
    }

    /**
     * 获取会员 （新增/修改窗口的combogrid）
     */
    public function actionGetVip()
    {
        $queryStr = isset($_REQUEST['q']) ? trim($_REQUEST['q']) : ''; // 检索过滤字符串
        $vipId = isset($_REQUEST['vipId']) ? intval($_REQUEST['vipId']) : 0; //修改时赋值用
        $query = Vip::find()
            ->select([
                'id AS vip_id',
                'code AS vip_code',
                'mobile AS vip_mobile',
                'client AS vip_name'
            ])
            ->where(['is_del'=>0]);

        if($vipId){
            // 修改时查询赋值
            $total = $query->andWhere(['id'=>$vipId])->count();
        }elseif($queryStr){
            // 检索过滤时
            $total = $query->andWhere(['or',['like', 'code', $queryStr],['like', 'client', $queryStr],['like', 'mobile', $queryStr]])
                ->count();
        }else{
            // 默认查询
            $total = $query->count();
        }
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount' => $total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        return json_encode($returnArr);
    }

    /**
     * 新增
     */
    public function actionAdd(){
        if(yii::$app->request->isPost){
            $returnArr = [
                'status'=>false,
                'info'=>'',
                'cardInfo'=>[],
                'ccrrInfo'=>[],
            ];
            //检测改卡是否已经存在
            $cardInfo = ChargeCard::find()
                ->select(['cc_id','cc_is_del'])
                ->where(['cc_code'=>yii::$app->request->post('cc_code')])
                ->asArray()->one();
                //已存在并在正常使用
                if($cardInfo['cc_id'] > 0 && $cardInfo['cc_is_del'] == 0){
                $returnArr['info'] = '卡号已经存在！';
                return json_encode($returnArr);
            }elseif($cardInfo['cc_id'] > 0 && $cardInfo['cc_is_del'] == 1){
                //已存在但被标记为已删除，则重新开启并更新该记录
                $model = ChargeCard::findOne(['cc_code'=>yii::$app->request->post('cc_code')]);
                $model->cc_is_del = 0;
                $model->load(yii::$app->request->post(),'');
                $returnArr = [];
                if($model->validate()){
                    if($model->save(false)){
                        $returnArr['status'] = true;
                        $returnArr['info'] = '充电卡已重新启用！';
                        $returnArr['cardInfo'] = [
                            'cc_id'=>$model->cc_id,
                            'cc_code'=>$model->cc_code,
                        ];
                        //如果有初始额度则添加充值记录
                        $ccInitialMoney = yii::$app->request->post('cc_initial_money');//初始金额
                        $ccInitialMoney = sprintf('%.2f',$ccInitialMoney);
                        if($ccInitialMoney > 0){
                            $ccrrModel = new ChargeCardRechargeRecord;
                            $ccrrModel->ccrr_code = 'ccr'.uniqid();
                            $ccrrModel->ccrr_card_id = $model->cc_id;
                            $ccrrModel->ccrr_before_money = 0;
                            $ccrrModel->ccrr_recharge_money = $ccInitialMoney;
                            $ccrrModel->ccrr_incentive_money = 0;
                            $ccrrModel->ccrr_after_money = $ccInitialMoney;
                            $ccrrModel->ccrr_mark = '初始额度';
                            $ccrrModel->ccrr_create_time = date('Y-m-d H:i:s');
                            $ccrrModel->ccrr_creator_id = $_SESSION['backend']['adminInfo']['id'];
                            $ccrrModel->write_status = 'fail';
                            if($ccrrModel->save(false)){
                                //添加初始额度充值订单成功
                                $returnArr['ccrrInfo'] = [
                                    'ccrr_id'=>$ccrrModel->ccrr_id,
                                    'ccrr_recharge_money'=>$ccrrModel->ccrr_recharge_money
                                ];
                            }
                        }
                        // 添加日志
                        $logStr = "充电卡管理-重新启用充电卡【" . ($model->cc_code) . "】";
                        UserLog::log($logStr, 'sys');
                    }else{
                        $returnArr['status'] = false;
                        $returnArr['info'] = '重新启用充电卡失败！';
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
            }
            //新增一条电卡记录
            $model = new ChargeCard();
            $model->load(yii::$app->request->post(),'');
            if($model->validate()){
                $model->cc_create_time = date('Y-m-d H:i:s');
                $model->cc_creator_id = $_SESSION['backend']['adminInfo']['id'];
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '新增充电卡成功！';
                    $returnArr['cardInfo'] = [
                        'cc_id'=>$model->cc_id,
                        'cc_code'=>$model->cc_code,
                    ];
                    //如果有初始额度则添加充值记录
                    $ccInitialMoney = yii::$app->request->post('cc_initial_money');//初始金额
                    $ccInitialMoney = sprintf('%.2f',$ccInitialMoney);
                    if($ccInitialMoney > 0){
                        $ccrrModel = new ChargeCardRechargeRecord;
                        $ccrrModel->ccrr_code = 'ccr'.uniqid();
                        $ccrrModel->ccrr_card_id = $model->cc_id;
                        $ccrrModel->ccrr_before_money = 0;
                        $ccrrModel->ccrr_recharge_money = $ccInitialMoney;
                        $ccrrModel->ccrr_incentive_money = 0;
                        $ccrrModel->ccrr_after_money = $ccInitialMoney;
                        $ccrrModel->ccrr_mark = '初始额度';
                        $ccrrModel->ccrr_create_time = date('Y-m-d H:i:s');
                        $ccrrModel->ccrr_creator_id = $_SESSION['backend']['adminInfo']['id'];
                        $ccrrModel->write_status = 'fail';
                        if($ccrrModel->save(false)){
                            //添加初始额度充值订单成功
                            $returnArr['ccrrInfo'] = [
                                'ccrr_id'=>$ccrrModel->ccrr_id,
                                'ccrr_recharge_money'=>$ccrrModel->ccrr_recharge_money
                            ];
                        }
                    }
                    // 添加日志
                    $logStr = "充电卡管理-新增充电卡【" . ($model->cc_code) . "】";
                    UserLog::log($logStr, 'sys');
                }else{
                    $returnArr['info'] = '新增充电卡失败！';
                }
            }else{
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
			//获取combo配置数据
            $configItems = ['cc_type','cc_status'];
            $config = (new ConfigCategory())->getCategoryConfig($configItems,'value');
            return $this->render('add',[
				'config'=>$config,
			]);
		}
    }

    /**
     * 修改
     */
    public function actionEdit()
    {
        if(yii::$app->request->isPost){
            $cc_id = intval(yii::$app->request->post('cc_id')) or die("Not pass 'cc_id'.");
            $model = ChargeCard::findOne(['cc_id'=>$cc_id]) or die('Not find corresponding record.');
            $formData = yii::$app->request->post();
            unset($formData['cc_code']);//不更新cc_code字段
            //print_r($formData);exit;
            $model->load($formData,'');
            $returnArr = []; 
            if($model->validate()){
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '修改充电卡成功！';
                    // 添加日志
                    $logStr = "充电卡管理-修改充电卡【" . ($model->cc_code) . "】";
                    UserLog::log($logStr, 'sys');
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '修改充电卡失败！';
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
            $cc_id = intval(yii::$app->request->get('cc_id')) or die("Not pass 'cc_id'.");
            //获取combo配置数据
            $configItems = ['cc_type','cc_status'];
            $config = (new ConfigCategory())->getCategoryConfig($configItems,'value');
            $ChargeCardInfo = ChargeCard::find()->where(['cc_id'=>$cc_id])->asArray()->one() or die('读取数据失败！');
            return $this->render('edit',[
				'config'=>$config,
				'initData'=>[
					'ChargeCardInfo'=>$ChargeCardInfo
				]
			]);
		}
    }
    
    /**
     * 删除
     */
    public function actionRemove()
    {
        $cc_id = intval(yii::$app->request->get('cc_id')) or die("Not pass 'cc_id'.");
        $returnArr = [];
        if(ChargeCard::updateAll(['cc_is_del'=>1],['cc_id'=>$cc_id])){
            $returnArr['status'] = true;
            $returnArr['info'] = '删除充电卡成功！';
        }else{
            $returnArr['status'] = false;
            $returnArr['info'] = '删除充电卡失败！';
        }
        echo json_encode($returnArr);
    }

    /**
     * 查看某电卡详情
     */
    public function actionScanCardDetails()
    {
        $cc_id = intval(yii::$app->request->get('cc_id')) or die("Not pass 'cc_id'.");
        $buttons = $this->getCurrentActionBtn();
        return $this->render('scanCardDetailsWin',[
            'buttons'=>$buttons,
            'cc_id'=>$cc_id,
        ]);
    }

    /**
     * 查看电卡基本信息
     * card/charge-card/base-info
     */
    public function actionBaseInfo(){
        //get参数
        $cc_id = yii::$app->request->get('cc_id');
        //需要获取的配置项
        $configItems = ['cc_type','cc_status'];
        //数据库获取的配置项值
        $config = [];
        //卡数据
        $cardInfo = false;
        //获取充电卡基本信息
        $cardInfo = ChargeCard::find()
            ->select([
                '{{%charge_card}}.*',
                'cc_holder_code'=> '{{%vip}}.code'
            ])
            ->joinWith('vip',false)
            ->where(['cc_id'=>$cc_id])
            ->asArray()->one();
        if(!$cardInfo){
            return '无该卡数据！';
        }
        //获取配置
        $config = (new ConfigCategory())->getCategoryConfig($configItems,'value');
        $cardInfo['cc_type'] = $config['cc_type'][$cardInfo['cc_type']]['text'];
        $cardInfo['cc_status'] = $config['cc_status'][$cardInfo['cc_status']]['text'];
        //统计该卡消费次数
        //---【2】查消费次数---------------------------------
        //连接前置机数据库，根据卡号查出IC卡充电记录
        $connectArr = ChargeFrontmachine::connect();
        if (!$connectArr[0]) {
            return $connectArr[1];
        }
        $fmConnection = $connectArr[1];
        //查DEAL_TYPE=0（开始充电）
        $total = (new \yii\db\Query())
            ->from('charge_record')
            ->where([
                'END_CARD_NO'=>$cardInfo['cc_code'],
                'DEAL_TYPE'=>0
            ])->count('*', $fmConnection);
        $cardInfo['consume_num'] = $total;
        //查询最近一次充电记录
        $lastChargeTime = 0;//最近的充电记录时间缀
        $lastUpdateTime = 0;//最近的充值记录时间缀
        if($cardInfo['cm_update_datetime']){
            $lastUpdateTime = strtotime($cardInfo['cm_update_datetime']);
        }
        $lastCharge = (new \yii\db\Query())
            ->select(['REMAIN_AFTER_DEAL','DEAL_END_DATE'])
            ->from('charge_record')
            ->where([
                'END_CARD_NO'=>$cardInfo['cc_code'],
                'DEAL_TYPE'=>[1,2]
            ])->orderBy('`DEAL_END_DATE` desc')->one($fmConnection);
        if($lastCharge){
            $lastChargeTime = strtotime($lastCharge['DEAL_END_DATE']);
            $cardInfo['lastChargeDateTime'] = $lastCharge['DEAL_END_DATE'];
        }else{
            $cardInfo['lastChargeDateTime'] = '无充电记录';
        }
        //如果有最新的消费记录则以消费记录为准否则以本地数据库记录为准
        if($lastChargeTime > $lastUpdateTime){
            $cardInfo['cc_current_money'] = $lastCharge['REMAIN_AFTER_DEAL'];
        }
        return $this->render('base-info',[
            'cardInfo'=>$cardInfo,
        ]);
    }

    /**
     * 获取电卡充值记录
     * card/charge-card/recharge-record
     */
    public function actionRechargeRecord(){
        //get参数
        $cc_id = yii::$app->request->get('cc_id');
        if(!$cc_id){
            return '参数错误！';
        }
        return $this->render('recharge-record',[
            'cc_id'=>$cc_id,
        ]);
    }

    /**
     * 获取某电卡的【充值记录】列表
     * card/charge-card/get-recharge-records
     */
    public function actionGetRechargeRecords()
    {
        $returnArr = ['rows'=>[],'total'=>0];
        $cc_id = yii::$app->request->get('cc_id');
        if(!$cc_id){
            return json_encode($returnArr);
        }
        $query = ChargeCardRechargeRecord::find()
            ->select([
                '{{%charge_card_recharge_record}}.*',
                'ccrr_creator' => '{{%admin}}.username'
            ])
            ->joinWith('admin', false, 'LEFT JOIN')
            ->where(['ccrr_card_id'=>$cc_id,'ccrr_is_del' => 0]);
        //查询条件
        $query->andFilterWhere(['LIKE', 'ccrr_code', yii::$app->request->get('ccrr_code')]);
        $query->andFilterWhere(['>=', 'ccrr_create_time', yii::$app->request->get('ccrr_create_time_start')]);
        $endTime = yii::$app->request->get('ccrr_create_time_end');
        if($endTime){
            $query->andWhere(['<=', 'ccrr_create_time', $endTime.' 23:59:59']);
        }
        $query->andFilterWhere(['write_status'=>yii::$app->request->get('write_status')]);
        $total = $query->count();
        //分页
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount' => $total, 'pageSize' => $pageSize]);
        //排序
        if (yii::$app->request->get('sort')) {
            $field = yii::$app->request->get('sort');        //field
            $direction = yii::$app->request->get('order');  //asc or desc
            $orderStr = $field . ' ' . $direction;
        } else {
            $orderStr = '`ccrr_id` DESC';
        }
        $data = $query->offset($pages->offset)
            ->limit($pages->limit)->orderBy($orderStr)
            ->asArray()->all();
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        return json_encode($returnArr);
    }

    /**
     * 获取电卡充电记录
     * card/charge-card/charge-record
     */
    public function actionChargeRecord(){
        //get参数
        $cc_id = yii::$app->request->get('cc_id');
        if(!$cc_id){
            return '参数错误！';
        }
        return $this->render('charge-record',[
            'cc_id'=>$cc_id,
        ]);
    }

    /**
     * 获取某电卡的【消费记录】列表
     */
    public function actionGetConsumeRecords()
    {
        $cc_id = intval(yii::$app->request->get('cc_id')) or die("Not pass 'cc_id'."); 
        //===【1】.查出电卡编号=====================
        $chargeCard = ChargeCard::find()
            ->select(['cc_id','cc_code'])
            ->where(['cc_id'=>$cc_id,'cc_is_del' => 0])
            ->asArray()->one();
        $cardNo = $chargeCard['cc_code'];
        //===【2】.连接前置机数据库==================
        //连接前置机数据库，根据卡号查出IC卡充电记录
        $connectArr = ChargeFrontmachine::connect();
        if (!$connectArr[0]) {
            return json_encode(['rows'=>[],'total'=>0,'errInfo'=>$connectArr[1]]);
        }
        $fmConnection = $connectArr[1];
        //先查DEAL_TYPE=0（开始充电）
        $query = (new \yii\db\Query())
            ->select([
                'DEAL_NO','START_CARD_NO','START_DEAL_DL',
                'REMAIN_BEFORE_DEAL','DEAL_START_DATE','CAR_NO',
                'INNER_ID','TIME_TAG'
            ])->from('charge_record')
            ->where([
                'and',
				['END_CARD_NO'=>$cardNo],
                ['DEAL_TYPE'=>0]
            ]);
        //查询条件
        $query->andFilterWhere(['LIKE','DEAL_NO',yii::$app->request->get('DEAL_NO')]);
        $query->andFilterWhere(['LIKE','START_CARD_NO',yii::$app->request->get('START_CARD_NO')]);
        $query->andFilterWhere(['>=','DEAL_START_DATE',yii::$app->request->get('DEAL_START_DATE_start')]); //开始充电时间
        if(yii::$app->request->get('DEAL_START_DATE_end')){ //结束充电时间
            $query->andFilterWhere(['<=','DEAL_START_DATE',yii::$app->request->get('DEAL_START_DATE_end').' 23:59:59']);
        }
        //查总数
        $total = $query->count('DEAL_NO', $fmConnection);
        //分页
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount' => $total, 'pageSize' => $pageSize]);
        //排序
        if(yii::$app->request->get('sort')){
            $field = yii::$app->request->get('sort');		//field
            $direction = yii::$app->request->get('order');  //asc or desc
            $orderStr = $field.' '.$direction;
        }else{
            $orderStr = 'TIME_TAG desc';
        }
        $query->orderBy($orderStr)->offset($pages->offset)->limit($pages->limit);
        //echo $query->createCommand()->sql;exit;
        $res = $query->all($fmConnection);
        if($res){
            //遍历查对应的充电结束记录
            foreach($res as &$_CCRItem){
                $endRecord = (new \yii\db\Query())
                    ->select(['END_DEAL_DL','REMAIN_AFTER_DEAL','DEAL_END_DATE','DEAL_TYPE'])
                    ->from('charge_record')
                    ->where('`DEAL_NO` = "'.$_CCRItem['DEAL_NO'].'" AND `END_CARD_NO` = "'.$_CCRItem['START_CARD_NO'].'" AND (`DEAL_TYPE` = 1 or  `DEAL_TYPE` = 2) AND `INNER_ID` = '.$_CCRItem['INNER_ID'])
                    ->one($fmConnection);
                if($endRecord){
                    if($endRecord['DEAL_TYPE'] == 1){
                        $_CCRItem['end_status'] = 1; //表示'结束正常'
                    }elseif($endRecord['DEAL_TYPE'] == 2){
                        $_CCRItem['end_status'] = 2; //表示'结束异常'
                    }
                    $_CCRItem = array_merge($_CCRItem,$endRecord);
                    //计算消费电量和消费金额
                    $_CCRItem['consume_DL'] = number_format($_CCRItem['END_DEAL_DL'] - $_CCRItem['START_DEAL_DL'],2);
                    $_CCRItem['consume_money'] = number_format($_CCRItem['REMAIN_BEFORE_DEAL'] - $_CCRItem['REMAIN_AFTER_DEAL'],2);
                }else{
                    $_CCRItem['end_status'] = 0; //表示'正在充电'
                }
            }
        }
        $returnArr = [];
        $returnArr['rows'] = $res;
        $returnArr['total'] = $total;
        return json_encode($returnArr);
    }


    /**
     * 电卡列表导出Excel
     */
    public function actionExportGridData()
    {
        // 构建导出的excel表头
        $excHeaders = [
            [
                ['content'=>'电卡编号','font-weight'=>true,'width'=>'15'],
                ['content'=>'电卡类型','font-weight'=>true,'width'=>'15'],
                ['content'=>'电卡状态','font-weight'=>true,'width'=>'15'],
                ['content'=>'当前余额(元)','font-weight'=>true,'width'=>'15'],
                ['content'=>'会员编号','font-weight'=>true,'width'=>'25'],
                ['content'=>'会员手机','font-weight'=>true,'width'=>'15'],
                ['content'=>'会员名称','font-weight'=>true,'width'=>'25'],
                ['content'=>'制卡日期','font-weight'=>true,'width'=>'15'],
                ['content'=>'有效日期','font-weight'=>true,'width'=>'15'],
                ['content'=>'备注','font-weight'=>true,'width'=>'15'],
                ['content'=>'创建时间','font-weight'=>true,'width'=>'20'],
                ['content'=>'创建人','font-weight'=>true,'width'=>'15']
            ]
        ];

        // 要查的字段，与导出的excel表头对应
        $selectArr = [
            '{{%charge_card}}.cc_code',
            '{{%charge_card}}.cc_type',
            '{{%charge_card}}.cc_status',
            '{{%charge_card}}.cc_current_money',
            '{{%vip}}.code',
            '{{%vip}}.mobile',
            '{{%vip}}.client',
            '{{%charge_card}}.cc_start_date',
            '{{%charge_card}}.cc_end_date',
            '{{%charge_card}}.cc_mark',
            '{{%charge_card}}.cc_create_time',
            '{{%admin}}.username'
        ];

        $query = ChargeCard::find()
            ->select($selectArr)
            ->joinWith('vip',false,'LEFT JOIN')
            ->joinWith('admin',false,'LEFT JOIN')
            ->where(['cc_is_del'=>0]);
        //查询条件
        $query->andFilterWhere(['LIKE','cc_code',yii::$app->request->get('cc_code')]);
        $query->andFilterWhere(['=','cc_type',yii::$app->request->get('cc_type')]);
        $query->andFilterWhere(['=','cc_status',yii::$app->request->get('cc_status')]);
        $query->andFilterWhere(['LIKE','{{%vip}}.code',yii::$app->request->get('cc_holder_code')]);
        $query->andFilterWhere(['LIKE','{{%vip}}.mobile',yii::$app->request->get('cc_holder_mobile')]);
        //查询条件结束
        $data = $query->asArray()->all();
        //print_r($data);exit;

        $excel = new Excel();
        $excel->setHeader([
            'creator'=>'皓峰通讯',
            'lastModifiedBy'=>'hao feng tong xun',
            'title'=>'charge_card',
            'subject'=>'charge_card',
            'description'=>'charge_card',
            'keywords'=>'charge_card',
            'category'=>'charge_card'
        ]);

        //---向excel添加表头-------------------------------------
        foreach($excHeaders as $lineData){
            $excel->addLineToExcel($lineData);
        }

        //---向excel添加具体数据----------------------------------
        $configItems = ['cc_type','cc_status'];
        $configs = (new ConfigCategory())->getCategoryConfig($configItems,'value');
        foreach($data as $item){
            $lineData = [];
            $item['cc_code'] = ' '.$item['cc_code'];
            // 各combox配置项以txt代替val
            foreach($configItems as $conf) {
                if($item[$conf]) {
                    $v = $item[$conf];
                    $item[$conf] = $configs[$conf][$v]['text'];
                }
            }
            foreach($item as $k=>$v) {
                if(!is_array($v)){
                    $lineData[] = ['content'=>$v];
                }
            }
            $excel->addLineToExcel($lineData);
        }
        unset($data);

        $objPHPExcel = $excel->getPHPExcel();
        header("Content-type: application/octet-stream");
        header("Accept-Ranges: bytes");
        //header("Accept-Length:".$fileSize);
        header("Content-Disposition: attachment; filename=".iconv('utf-8','gbk','充电卡列表_'.date('YmdHis').'.xls'));
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }

    /**
     * 获取充电卡基本信息
     */
    public function actionGetInfo(){
        $returnArr = [
            'status'=>false,
            'info'=>'',
        ];
        $cardNo = yii::$app->request->get('cardNo');
        if(!$cardNo){
            $returnArr['info'] = '卡号错误！';
            echo json_encode($returnArr);
            return;
        }
        $cardInfo = ChargeCard::find()
            ->select([
                '{{%charge_card}}.`cc_type`',
                '{{%vip}}.`code`',
                '{{%vip}}.`client`',
                '{{%vip}}.`mobile`',
            ])->joinWith('vip',false)
            ->where([
                '{{%charge_card}}.`cc_code`'=>$cardNo,
                '{{%charge_card}}.`cc_is_del`'=>0,
            ])->asArray()->one();
        if(!$cardInfo){
            $returnArr['info'] = '系统无卡片数据！';
            return json_encode($returnArr);
        }
        //
        $configItems = ['cc_type'];
        $config = (new ConfigCategory())->getCategoryConfig($configItems,'value');
        $cardInfo['cc_type'] = $config['cc_type'][$cardInfo['cc_type']]['text'];
        $returnArr['status'] = true;
        $returnArr['datas'] = $cardInfo;
        return json_encode($returnArr);
    }

    /**
     * 读卡
     * card/charge-card/read
     */
    public function actionRead(){
        return $this->render('read');
    }
}