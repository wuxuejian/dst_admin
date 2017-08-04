<?php
/**
 * @Desc:	充电卡管理 控制器
 * @author: chengwk
 * @date:	2016-01-22
 */
namespace backend\modules\charge\controllers;
use backend\controllers\BaseController;
use yii;
use yii\data\Pagination;
use backend\models\ConfigCategory;
use backend\models\ChargeCard;
use backend\models\ChargeCardRechargeRecord;
use backend\models\ChargeCardConsumeRecord;
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
        $query->andFilterWhere(['LIKE','{{%vip}}.code',yii::$app->request->get('cc_holder_code')]);
        $query->andFilterWhere(['LIKE','{{%vip}}.mobile',yii::$app->request->get('cc_holder_mobile')]);
        $query->andFilterWhere(['>=','cc_start_date',yii::$app->request->get('cc_start_date_start')]);
        $query->andFilterWhere(['<=','cc_start_date',yii::$app->request->get('cc_start_date_end')]);
        $total = $query->count();
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
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        echo json_encode($returnArr);
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
    public function actionAdd()
    {
        if(yii::$app->request->isPost){
            $model = new ChargeCard();
            $formData = yii::$app->request->post();
            $model->load($formData,'');
            $returnArr = [];
            if($model->validate()){
                $model->cc_current_money = $formData['cc_initial_money']; //新卡的当前余额等于初始额度
                $model->cc_create_time = date('Y-m-d H:i:s');
                $model->cc_creator_id = $_SESSION['backend']['adminInfo']['id'];
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '新增充电卡成功！';
                    // 添加日志
                    $logStr = "充电卡管理-新增充电卡【" . ($model->cc_code) . "】";
                    UserLog::log($logStr, 'sys');
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '新增充电卡失败！';
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
			//获取combo配置数据
            $configItems = ['cc_type','cc_status'];
            $config = (new ConfigCategory())->getCategoryConfig($configItems,'value');
            return $this->render('addEditWin',[
				'config'=>$config,
				'initData'=>[
                    'action'=>'add',
                ]
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
            return $this->render('addEditWin',[
				'config'=>$config,
				'initData'=>[
					'action'=>'edit',
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
     * 电卡充值
     */
    public function actionRecharge()
    {
        if(yii::$app->request->isPost){
            $model = new ChargeCardRechargeRecord();
            $formData = yii::$app->request->post();
            //print_r($formData);exit;
            $model->load($formData,'');
            $returnArr = [];
            if($model->validate()){
                $model->ccrr_code = uniqid('DKCZ');
                $model->ccrr_create_time = date('Y-m-d H:i:s');
                $model->ccrr_creator_id = $_SESSION['backend']['adminInfo']['id'];
                // 查出电卡当前余额等信息
                $cardInfo = ChargeCard::find()
                    ->select(['cc_code','cc_current_money'])
                    ->where(['cc_id'=>$model->ccrr_card_id])
                    ->asArray()->one();
                $model->ccrr_before_money = $cardInfo['cc_current_money'];
                $model->ccrr_after_money = $cardInfo['cc_current_money'] + ($model->ccrr_recharge_money) + ($model->ccrr_incentive_money);

                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '新建充值记录【' . ($model->ccrr_code) . '】';
                    // 添加日志
                    $logStr = "充电卡管理-充值：新建充值记录【" . ($model->ccrr_code) . "】" ;
                    UserLog::log($logStr, 'sys');
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '新建充值记录失败！';
                }

/*              //=====事务begin 涉及到金钱，操作必须同步进行(相关表要改成InnoDB)
                $transaction = yii::$app->db->beginTransaction();
                $isSavedNewRecord = $model->save(false);
                $isUpdatedMoney = ChargeCard::updateAll(['cc_current_money' => $model->ccrr_after_money], ['cc_id' => $model->ccrr_card_id]); //更新当前余额
                if ($isSavedNewRecord && $isUpdatedMoney) {
                    $transaction->commit();  //提交事务
                    $returnArr['status'] = true;
                    $returnArr['info'] = '新建充值记录【' . ($model->ccrr_code) . '】';
                    // 添加日志
                    $logStr = "充电卡管理-充值：新建充值记录【" . ($model->ccrr_code) . "】" ;
                    UserLog::log($logStr, 'sys');
                } else {
                    $transaction->rollback(); //回滚事务
                    $returnArr['status'] = false;
                    $returnArr['info'] = '新建充值记录失败！';
                }
                //=====事务end==============================================================
*/
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
        } else {
            $cc_id = intval(yii::$app->request->get('cc_id')) or die("Not pass 'cc_id'.");
            $ChargeCardInfo = ChargeCard::find()
                ->select([
                    'cc_id','cc_code','cc_initial_money','cc_current_money',
                    'cc_holder_code'=> '{{%vip}}.code'
                ])
                ->joinWith('vip',false,'LEFT JOIN')
                ->where(['cc_id'=>$cc_id])
                ->asArray()->one() or die('读取数据失败！');
            return $this->render('rechargeWin',[
                'ChargeCardInfo'=>$ChargeCardInfo
            ]);
        }
    }


    /**
     * 查看某电卡详情
     */
    public function actionScanCardDetails()
    {
        $cc_id = intval(yii::$app->request->get('cc_id')) or die("Not pass 'cc_id'.");
        //获取combo配置数据
        $configItems = ['cc_type','cc_status'];
        $config = (new ConfigCategory())->getCategoryConfig($configItems,'value');
        //查电卡信息
        $cardInfo = ChargeCard::find()
            ->select([
                '{{%charge_card}}.*',
                'cc_holder_code'=> '{{%vip}}.code'
            ])
            ->joinWith('vip',false,'LEFT JOIN')
            ->where(['cc_id'=>$cc_id])
            ->asArray()->one() or die('读取数据失败！');
        //查充值次数
        $rechargeRecords = ChargeCardRechargeRecord::find()
            ->select(['recharge_num'=>'COUNT(ccrr_id)'])
            ->where(['ccrr_card_id'=>$cc_id,'ccrr_is_del'=>0])
            ->asArray()->one();
        $cardInfo['recharge_num'] = $rechargeRecords['recharge_num'];
        //查消费次数
/*      $rechargeRecords = ChargeCardRechargeRecord::find()
        ->select(['recharge_num'=>'COUNT(ccrr_id)'])
        ->where(['ccrr_card_id'=>$cc_id,'ccrr_is_del'=>0])
        ->asArray()->one();
        $cardInfo['consume_num'] = $rechargeRecords['consume_num'];
*/
        $cardInfo['consume_num'] = 0;
        return $this->render('scanCardDetailsWin',[
            'config'=>$config,
            'cardInfo'=>$cardInfo
        ]);
    }


    /**
     * 获取某电卡的【充值记录】列表
     */
    public function actionGetRechargeRecords()
    {
        $returnArr = ['rows'=>[],'total'=>0];
        $cc_id = intval(yii::$app->request->get('cc_id')) or die("Not pass 'cc_id'.");
        if($cc_id) {
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
            $query->andFilterWhere(['<=', 'ccrr_create_time', yii::$app->request->get('ccrr_create_time_end')]);
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
                $orderStr = 'ccrr_id DESC';
            }
            $data = $query->offset($pages->offset)->limit($pages->limit)->orderBy($orderStr)->asArray()->all();
            $returnArr = [];
            $returnArr['rows'] = $data;
            $returnArr['total'] = $total;
        }
        echo json_encode($returnArr);exit;
    }


    /**
     * 获取某电卡的【消费记录】列表
     */
    public function actionGetConsumeRecords()
    {
        $returnArr = ['rows'=>[],'total'=>0];
        $cc_id = intval(yii::$app->request->get('cc_id')) or die("Not pass 'cc_id'.");
        if($cc_id) {
            //---1.查出电卡编号---------------
            $chargeCard = ChargeCard::find()
                ->select(['cc_id','cc_code'])
                ->where(['cc_id'=>$cc_id,'cc_is_del' => 0])
                ->asArray()->one();
            $cardNo = $chargeCard['cc_code'];
            //---2.连接前置机数据库------------
            $fmConnection = $this->connectFrontMachineDbByFmId();
            if (is_object($fmConnection)) {
                // 根据卡号查出充电记录
                $query = (new \yii\db\Query())
                    ->select(['charge_record.*'])
                    ->from('charge_record')
                    ->where(['END_CARD_NO' => $cardNo]);
                    //->groupBy('DEAL_NO');
                //echo $query->createCommand()->sql;exit;
                // 查询条件
                $query->andFilterWhere(['LIKE','DEAL_NO',yii::$app->request->get('DEAL_NO')]);
                $query->andFilterWhere(['>=','TIME_TAG',yii::$app->request->get('TIME_TAG_start')]);
                $query->andFilterWhere(['<=','TIME_TAG',yii::$app->request->get('TIME_TAG_end')]);
                $total = $query->count('DEAL_NO', $fmConnection);
                // 分页
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
                $records = $query->orderBy($orderStr)->offset($pages->offset)->limit($pages->limit)->all($fmConnection);
                $returnArr['rows'] = $records;
                $returnArr['total'] = $total;
            } else {
                $errInfo = $fmConnection;
            }
            if (isset($errInfo)) {
                $returnArr['errInfo'] = $errInfo;
            }
        }
        echo json_encode($returnArr);exit;
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
                ['content'=>'初始额度(元)','font-weight'=>true,'width'=>'15'],
                ['content'=>'当前额度(元)','font-weight'=>true,'width'=>'15'],
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
            '{{%charge_card}}.cc_initial_money',
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
        header("Content-Disposition: attachment; filename=".iconv('utf-8','gbk','充电站列表_'.date('YmdHis').'.xls'));
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }
	

}