<?php
/**
 * @Desc:	充电桩管理 控制器
 * @author: chengwk
 * @date:	2015-10-12
 */
namespace backend\modules\charge\controllers;
use backend\controllers\BaseController;
use yii;
use yii\data\Pagination;
use backend\models\ConfigCategory;
use backend\models\ChargeSpots;
use backend\models\ChargeStation;
use backend\models\ChargeFrontmachine;
use backend\classes\UserLog;//日志类
use common\models\Excel;

class ChargeSpotsController extends BaseController
{
    /**
     * 访问‘电桩列表’视图
     */
    public function actionIndex()
    {	
		$configItems = ['charge_type','connection_type','install_type','manufacturer','status','model','charge_pattern'];
        $data['config'] = (new ConfigCategory())->getCategoryConfig($configItems,'value'); 
		$data['buttons'] = $this->getCurrentActionBtn();
        $stations = ChargeStation::find()
            ->select(['cs_id','cs_code','cs_name'])
            ->where(['cs_is_del'=>0])
            ->orderBy('cs_name')
            ->asArray()->all();
        array_unshift($stations,['cs_id'=>'','cs_code'=>'','cs_name'=>'--不限--']);
        $data['chargeStation'] = $stations;
        return $this->render('index',$data);
    }
    
    /**
     * 获取电桩列表
     */
    public function actionGetList()
    {
        $query = ChargeSpots::find()
            ->select([
                '{{%charge_spots}}.*',
                'station_name'=> '{{%charge_station}}.cs_name'
            ])
            ->joinWith('chargeStation',false,'LEFT JOIN')
            ->where(['{{%charge_spots}}.is_del'=>0]);
        //查询条件
        $query->andFilterWhere(['like','code_from_compony',yii::$app->request->get('code_from_compony')]);
        $query->andFilterWhere(['=','charge_pattern',yii::$app->request->get('charge_pattern')]);
        $query->andFilterWhere(['=','charge_type',yii::$app->request->get('charge_type')]);
        $query->andFilterWhere(['=','station_id',yii::$app->request->get('station_id')]);
        $query->andFilterWhere(['=','manufacturer',yii::$app->request->get('manufacturer')]);
        $query->andFilterWhere(['=','connection_type',yii::$app->request->get('connection_type')]);
        $query->andFilterWhere(['=','logic_addr',yii::$app->request->get('logic_addr')]);
        $query->andFilterWhere(['=','install_type',yii::$app->request->get('install_type')]);
        $query->andFilterWhere(['like','install_site',yii::$app->request->get('install_site')]);
        $query->andFilterWhere(['>=','`install_date`',yii::$app->request->get('install_date_start')]);
        $query->andFilterWhere(['<=','`install_date`',yii::$app->request->get('install_date_end')]);
        $total = $query->count();
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
		//排序
		if(yii::$app->request->get('sort')){
			$field = yii::$app->request->get('sort');		//field
			$direction = yii::$app->request->get('order');  //asc or desc
			$orderStr = $field.' '.$direction;
		}else{
			$orderStr = 'id desc';
		}	
        $data = $query->offset($pages->offset)->limit($pages->limit)->orderBy($orderStr)->asArray()->all();

        //===【电桩状态的处理】===================================================================
        // 注意：这段代码在3处使用了，记得同步修改：charge模块ChargeSpotsController和ChargeStationController中。
        //---1.默认所有电桩真实状态都是【离线】-------------------------
        // 注意：电桩类型决定电枪数量，而有几个电枪就有几个状态。
        // 前置机上配置的电桩状态：0-充电，1-待机，2-故障，3-禁用，4-离线
        foreach($data as $k=>$item){
            $chargeType = $item['charge_type'];
            if($chargeType == 'DC' || $chargeType == 'AC'){
                $data[$k]['status'] = '4';
            }elseif($chargeType == 'DC_DC' || $chargeType == 'AC_AC' || $chargeType == 'AC_DC'){
                $data[$k]['status'] = '4,4';
            }
        }
        //---2.连接前置机数据库查询各电桩真实状态 begin -----------------
        // 将电桩逻辑地址按前置机id分组组装成数组备用
        $tmpArr = [];
        foreach ($data as $item) {
            if ($item['fm_id'] > 0 && $item['logic_addr']) {
                $tmpArr[$item['fm_id']][] = $item['logic_addr'];
            }
        }
        foreach ($tmpArr as $fmId=>$logicAddr_arr) {
            $fmConnection = $this->connectFrontMachineDbByFmId($fmId);
            if (is_object($fmConnection)) {
                $rows = (new \yii\db\Query())
                    ->select([
                        'DEV_ADDR',
                        'gun_status'=>"GROUP_CONCAT(charge_status.INNER_ID,'-',charge_status.STATUS)"
                    ])
                    ->from('charge_pole')
                    ->leftJoin('charge_status', 'charge_status.DEV_ID = charge_pole.DEV_ID')
                    ->where(['DEV_ADDR' => $logicAddr_arr])
                    ->groupBy('DEV_ADDR')
                    ->indexBy('DEV_ADDR')
                    ->all($fmConnection);
                //print_r($rows);exit;
                // 合并数据
                if (!empty($rows)) {
                    foreach ($data as &$ControllerGLitem) {
                        if (isset($rows[$ControllerGLitem['logic_addr']])) {
                            $gunStatus_str = $rows[$ControllerGLitem['logic_addr']]['gun_status']; // 每一个枪号及对应状态的格式：“枪号-状态”
                            if($gunStatus_str){
                                $gunStatus_arr = explode(',',$gunStatus_str);
                                $gunStatus = [];
                                foreach($gunStatus_arr as $item){
                                    $tmp = explode('-',$item);
                                    $gunStatus[$tmp[0]] = $tmp[1];
                                }
                                // 按键名降序排序并保持索引关系。使得前端就能按序以A-B顺序显示对应枪状态。
                                krsort ($gunStatus);
                                $ControllerGLitem['status'] = implode(',',array_values($gunStatus));
                            }
                        }
                    }
                }
            }
        }
        //---连接前置机数据库查询各电桩真实状态 end -------------------------

        //print_r($data);exit;
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        echo json_encode($returnArr);
    }

    /**
     * 新增电桩
     */
    public function actionAdd()
    {
        if(yii::$app->request->isPost){
            $model = new ChargeSpots();
            $formData = yii::$app->request->post();
            $model->load($formData,'');
            $returnArr = [];
            if($model->validate()){
                $model->systime = time();
                $model->sysuser = $_SESSION['backend']['adminInfo']['username'];
                //防止电桩表结构变动，只能将电站表中位置、经纬度等字段也同步保存到电桩表
                $stationId  = $formData['station_id'];
                $stationInfo = ChargeStation::find()
                    ->select(['cs_address','cs_lng','cs_lat','cs_fm_id'])
                    ->where(['cs_id'=>$stationId])
                    ->asArray()->one();
                $model->install_site = $stationInfo['cs_address'];
                $model->lng = $stationInfo['cs_lng'];
                $model->lat = $stationInfo['cs_lat'];
                $model->fm_id = $stationInfo['cs_fm_id'];
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '新增电桩成功！';
                    // 添加日志
                    $logStr = "充电桩列表-新增充电桩【" . ($model->code_from_compony) . "】";
                    UserLog::log($logStr, 'sys');
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '新增电桩失败！';
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
			$configItems = ['charge_type','connection_type','install_type','manufacturer','status','model','charge_pattern'];
			$configCategoryModel = new ConfigCategory();
			$config = $configCategoryModel->getCategoryConfig($configItems);
			return $this->render('addEditWin',[
				'config'=>$config,
				'myData'=>['action'=>'add']
			]);
		}
    }

    /**
     * 修改电桩
     */
    public function actionEdit()
    {
        if(yii::$app->request->isPost){
            $id = intval(yii::$app->request->post('id')) or die("Not pass 'id'.");
            $model = ChargeSpots::findOne(['id'=>$id]) or die('Not find corresponding data.');
            $formData = yii::$app->request->post();
            $model->load($formData,'');
            $returnArr = []; 
            if($model->validate()){
                //防止电桩表结构变动，只能将电站表中位置、经纬度等字段也同步保存到电桩表
                $stationId  = $formData['station_id'];
                $stationInfo = ChargeStation::find()
                    ->select(['cs_address','cs_lng','cs_lat','cs_fm_id'])
                    ->where(['cs_id'=>$stationId])
                    ->asArray()->one();
                $model->install_site = $stationInfo['cs_address'];
                $model->lng = $stationInfo['cs_lng'];
                $model->lat = $stationInfo['cs_lat'];
                $model->fm_id = $stationInfo['cs_fm_id'];
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '修改电桩成功！';
                    // 添加日志
                    $logStr = "充电桩列表-修改充电桩【" . ($model->code_from_compony) . "】";
                    UserLog::log($logStr, 'sys');
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '修改电桩失败！';
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
			$id = intval(yii::$app->request->get('id')) or die("Not pass 'id'.");
			//获取combo配置数据
			$configItems = ['charge_type','connection_type','install_type','manufacturer','status','model','charge_pattern'];
			$configCategoryModel = new ConfigCategory();
			$config = $configCategoryModel->getCategoryConfig($configItems);
			$chargeSpotsInfo = ChargeSpots::find()->where(['id'=>$id])->asArray()->one() or die('读取数据失败！');
			return $this->render('addEditWin',[
				'config'=>$config,
				'myData'=>[
					'action'=>'edit',
					'chargeSpotsInfo'=>$chargeSpotsInfo
				]
			]);
		}
    }
    
    /**
     * 删除电桩
     */
    public function actionRemove()
    {
        $id = intval(yii::$app->request->get('id')) or die("Not pass 'id'.");
        $returnArr = [];
        if(ChargeSpots::updateAll(['is_del'=>1],['id'=>$id])){
            $returnArr['status'] = true;
            $returnArr['info'] = '电桩信息删除成功！';
            UserLog::log("充电桩列表-删除充电桩（{$id}）", 'sys');
        }else{
            $returnArr['status'] = false;
            $returnArr['info'] = '电桩信息删除失败！';
        }
        echo json_encode($returnArr);
    }

    /**
     * 查看某电桩详情
     */
    public function actionScanDetails()
    {
        $id = intval(yii::$app->request->get('id')) or die("Not pass 'id'.");
        //获取combo配置数据
        $configItems = ['charge_type','connection_type','install_type','manufacturer','status','model','charge_pattern'];
        $configCategoryModel = new ConfigCategory();
        $config = $configCategoryModel->getCategoryConfig($configItems,'value');
        $chargerInfo = ChargeSpots::find()
            ->select([
                '{{%charge_spots}}.*',
                'station_name'=> '{{%charge_station}}.cs_name'
            ])
            ->joinWith('chargeStation',false,'LEFT JOIN')
            ->where(['id'=>$id])
            ->asArray()->one() or die('读取数据失败！');
        //获取测量点号（二维码使用）
        $measuringPoint = ChargeSpots::getMeasuringPoint($chargerInfo['charge_type']);
        return $this->render('scanDetailsWin',[
            'config'=>$config,
            'chargerInfo'=>$chargerInfo,
            'measuringPoint'=>$measuringPoint,
        ]);
    }

    /**
     * 查看某电桩充电记录
     */
    public function actionScanChargeRecords()
    {
        $id = intval(yii::$app->request->get('id')) or die("Not pass 'id'.");
        return $this->render('scanChargeRecordsWin',[
            'id'=>$id
        ]);
    }

    /**
     * 获取某电桩充电记录
     */
    public function actionGetChargeRecords()
    {
        $returnArr = [
            'rows'=>[],
            'total'=>0,
        ];
        $id = intval(yii::$app->request->get('id')) or die("Not pass 'id'.");
        $pole = ChargeSpots::find()->select(['id','fm_id','logic_addr'])->where(['id'=>$id])->asArray()->one();
        $dealType = yii::$app->request->get('DEAL_TYPE');
        $connectRes = ChargeFrontmachine::connect($pole['fm_id']);
        if(!$connectRes[0]){
            return json_encode($returnArr);
        }
        //排序
        $column = yii::$app->request->get('sort');
        $sort = yii::$app->request->get('order','desc');
        if($column){
            switch($column){
                case 'DEV_ADDR':
                    $orderStr = 'charge_pole.DEV_ADDR';
                    break;
                default:
                    $orderStr = 'charge_record.' . $column;
            }
            $orderStr .= ' ' . $sort;
        }else{
            $orderStr = 'charge_record.ID DESC';
        }
        //分页
        $size = isset($_REQUEST['rows']) && $_REQUEST['rows'] <= 50 ? intval($_REQUEST['rows']) : 10;
        $page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
        switch ($dealType) {
            case 1:
            case 2:
                //结束
                $query = (new \yii\db\Query())
                    ->from('charge_record')
                    ->select(['charge_record.*','charge_pole.DEV_ADDR'])
                    ->leftJoin('charge_pole','charge_pole.DEV_ID = charge_record.DEV_ID')
                    ->andWhere(['charge_record.DEAL_TYPE'=>[1,2]])
					->andWhere(['charge_pole.DEV_ADDR'=>$pole['logic_addr']])		
                    ->andFilterWhere(['DEAL_NO'=>yii::$app->request->get('DEAL_NO')])
                    ->andFilterWhere(['like','START_CARD_NO',yii::$app->request->get('START_CARD_NO')]);
                if(yii::$app->request->get('DEAL_START_DATE_start')){ //充电时间
                    $query->andWhere(['>=','DEAL_START_DATE',yii::$app->request->get('DEAL_START_DATE_start').' 00:00:00']);
                }
                if(yii::$app->request->get('DEAL_START_DATE_end')){
                    $query->andWhere(['<=','DEAL_START_DATE',yii::$app->request->get('DEAL_START_DATE_end').' 23:59:59']);
                }
                if(yii::$app->request->get('TIME_TAG_start')){ //记录时间
                    $query->andWhere(['>=','TIME_TAG',yii::$app->request->get('TIME_TAG_start').' 00:00:00']);
                }
                if(yii::$app->request->get('TIME_TAG_end')){
                    $query->andWhere(['<=','TIME_TAG',yii::$app->request->get('TIME_TAG_end').' 23:59:59']);
                }
                if(yii::$app->request->get('cs_id')){
                    $conditionChargePole = ChargeSpots::find()
                        ->select(['logic_addr'])
                        ->where(['is_del'=>0,'station_id'=>yii::$app->request->get('cs_id')])
                        ->asArray()->all();
                    if($conditionChargePole){
                        $conditionChargePole = array_column($conditionChargePole,'logic_addr');
                    }else{
                        $conditionChargePole = [];
                    }
                    $query->andWhere(['charge_pole.DEV_ADDR'=>$conditionChargePole]);
                }
                $total = $query->count('*',$connectRes[1]);
                $rows = $query->offset(($page-1)*$size)->limit($size)
                    ->orderby($orderStr)->all($connectRes[1]);
                break;
            default:
                //正在充电
                $subQuery = (new \yii\db\Query())->select([
                        'ID',
                        'ins'=>'concat(charge_record.`DEV_ID`,"-",charge_record.`DEAL_NO`,"-",charge_record.`START_CARD_NO`)',
                    ])->from('charge_record')
                    ->leftJoin('charge_pole','charge_pole.DEV_ID = charge_record.DEV_ID')
                    ->andWhere(['>','TIME_TAG',date('Y-m-d H:i:s',strtotime('-1 day'))])
					->andWhere(['charge_pole.DEV_ADDR'=>$pole['logic_addr']])	
                    ->andFilterWhere(['DEAL_NO'=>yii::$app->request->get('DEAL_NO')])
                    ->andFilterWhere(['like','START_CARD_NO',yii::$app->request->get('START_CARD_NO')]);
                if(yii::$app->request->get('DEAL_START_DATE_start')){ //充电时间
                    $subQuery->andWhere(['>=','DEAL_START_DATE',yii::$app->request->get('DEAL_START_DATE_start').' 00:00:00']);
                }
                if(yii::$app->request->get('DEAL_START_DATE_end')){
                    $subQuery->andWhere(['<=','DEAL_START_DATE',yii::$app->request->get('DEAL_START_DATE_end').' 23:59:59']);
                }
                if(yii::$app->request->get('TIME_TAG_start')){ //记录时间
                    $subQuery->andWhere(['>=','TIME_TAG',yii::$app->request->get('TIME_TAG_start').' 00:00:00']);
                }
                if(yii::$app->request->get('TIME_TAG_end')){
                    $subQuery->andWhere(['<=','TIME_TAG',yii::$app->request->get('TIME_TAG_end').' 23:59:59']);
                }
                $chargingRecord = (new \yii\db\Query())->select([
                        'ID'=>'min(ID)',
                        'ins',
                        'num'=>'count(ins)',
                    ])->from(['subTable'=>$subQuery])
                    ->groupBy('ins')
                    ->having('num < 2')
                    ->all($connectRes[1]);
                if(!$chargingRecord){
                    return json_encode($returnArr);
                }
                unset($subQuery);
                $chargingIds = array_column($chargingRecord,'ID');
                $query = (new \yii\db\Query())
                    ->from('charge_record')
                    ->select(['charge_record.*','charge_pole.DEV_ADDR'])
                    ->leftJoin('charge_pole','charge_pole.DEV_ID = charge_record.DEV_ID')
                    ->andWhere(['charge_record.ID'=>$chargingIds])
                    ->andWhere(['charge_record.DEAL_TYPE'=>0]);
                $total = $query->count('*',$connectRes[1]);
                $rows = $query->offset(($page-1)*$size)->limit($size)
                    ->orderby($orderStr)->all($connectRes[1]);
                break;
        }
        if($rows){
			//查询本页数据电站信息
            foreach($rows as $key=>$val){
                if($dealType != 0){
                    $rows[$key]['c_amount'] = sprintf('%.2f',$val['REMAIN_BEFORE_DEAL'] - $val['REMAIN_AFTER_DEAL']);
                    $rows[$key]['c_dl'] = sprintf('%.2f',$val['END_DEAL_DL'] - $val['START_DEAL_DL']);
                }
			}
        }
        $returnArr['total'] = $total;
        $returnArr['rows'] = $rows;
        return json_encode($returnArr);
    }

    /**
     * 导出Excel
     */
    public function actionExportGridData()
    {
		// 构建导出的excel表头
		$excHeaders = [
            [
                ['content'=>'电桩编号','font-weight'=>true,'width'=>'15'],
                ['content'=>'充电模式','font-weight'=>true,'width'=>'10'],
                ['content'=>'电桩类型','font-weight'=>true,'width'=>'15'],
                ['content'=>'电枪个数','font-weight'=>true,'width'=>'10'],
                ['content'=>'所属充电站','font-weight'=>true,'width'=>'25'],
                ['content'=>'安装地点','font-weight'=>true,'width'=>'30'],
                ['content'=>'安装方式','font-weight'=>true,'width'=>'10'],
                ['content'=>'安装日期','font-weight'=>true,'width'=>'10'],
                ['content'=>'连接方式','font-weight'=>true,'width'=>'10'],
                ['content'=>'生产厂家','font-weight'=>true,'width'=>'10'],
                ['content'=>'出厂编号','font-weight'=>true,'width'=>'20'],
                ['content'=>'电桩型号','font-weight'=>true,'width'=>'10'],
                ['content'=>'逻辑地址','font-weight'=>true,'width'=>'10'],
                ['content'=>'登记人员','font-weight'=>true,'width'=>'15']
            ]
		];
        // 要查的字段，与导出的excel表头对应
        $query = ChargeSpots::find()
            ->select([
                '{{%charge_spots}}.code_from_compony',
                '{{%charge_spots}}.charge_pattern',
                '{{%charge_spots}}.charge_type',
                '{{%charge_spots}}.charge_gun_nums',
                '{{%charge_station}}.cs_name',
                '{{%charge_spots}}.install_site',
                '{{%charge_spots}}.install_type',
                '{{%charge_spots}}.install_date',
                '{{%charge_spots}}.connection_type',
                '{{%charge_spots}}.manufacturer',
                '{{%charge_spots}}.code_from_factory',
                '{{%charge_spots}}.model',
                '{{%charge_spots}}.logic_addr',
                '{{%charge_spots}}.sysuser'
            ])
            ->joinWith('chargeStation',false,'LEFT JOIN')
            ->where(['{{%charge_spots}}.is_del'=>0]);
        //查询条件开始
        $query->andFilterWhere(['like','code_from_compony',yii::$app->request->get('code_from_compony')]);
        $query->andFilterWhere(['=','charge_pattern',yii::$app->request->get('charge_pattern')]);
        $query->andFilterWhere(['=','charge_type',yii::$app->request->get('charge_type')]);
        $query->andFilterWhere(['=','station_id',yii::$app->request->get('station_id')]);
        $query->andFilterWhere(['=','manufacturer',yii::$app->request->get('manufacturer')]);
        $query->andFilterWhere(['=','connection_type',yii::$app->request->get('connection_type')]);
        $query->andFilterWhere(['=','logic_addr',yii::$app->request->get('logic_addr')]);
        $query->andFilterWhere(['=','install_type',yii::$app->request->get('install_type')]);
        $query->andFilterWhere(['like','install_site',yii::$app->request->get('install_site')]);
        $query->andFilterWhere(['>=','`install_date`',yii::$app->request->get('install_date_start')]);
        $query->andFilterWhere(['<=','`install_date`',yii::$app->request->get('install_date_end')]);
		//查询条件结束
		$data = $query->asArray()->all();
		//print_r($data);exit;
        
		$excel = new Excel();
        $excel->setHeader([
            'creator'=>'皓峰通讯',
            'lastModifiedBy'=>'hao feng tong xun',
            'title'=>'charge_spots',
            'subject'=>'charge_spots',
            'description'=>'charge_spots',
            'keywords'=>'charge_spots',
            'category'=>'charge_spots'
        ]);
		//---向excel添加表头----------
		foreach($excHeaders as $lineData){
			$excel->addLineToExcel($lineData);
		}
		//---向excel添加具体数据-------
		$configItems = ['charge_type','connection_type','install_type','manufacturer','status','model','charge_pattern'];
        $configs = (new ConfigCategory())->getCategoryConfig($configItems,'value'); 
        foreach($data as $item){
            $lineData = [];
			// 各combox配置项以txt代替val
			foreach($configItems as $conf) {
				if(isset($item[$conf]) && $item[$conf]) {
					$item[$conf] = $configs[$conf][$item[$conf]]['text'];
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
        header("Content-Disposition: attachment; filename=".iconv('utf-8','gbk','充电桩列表导出.xls'));
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');	
    }
	
    
	/**
	 * 在百度地图上显示所有电桩
	 */
	public function actionShowOnMap(){
        $config = (new ConfigCategory)->getCategoryConfig(['bmap_api_addr']);
        $_config = [];
        foreach($config as $key=>$val){
            $_config[$key] = array_values($val)[0]['value'];
        }
        $query = ChargeSpots::find()
            ->select(['code_from_compony','connection_type','install_type','install_site','lng','lat'])
            ->joinWith('chargeStation',false,'LEFT JOIN')
            ->where(['{{%charge_spots}}.is_del'=>0]);
        //查询条件
        $query->andFilterWhere(['like','code_from_compony',yii::$app->request->get('code_from_compony')]);
        $query->andFilterWhere(['=','charge_pattern',yii::$app->request->get('charge_pattern')]);
        $query->andFilterWhere(['=','charge_type',yii::$app->request->get('charge_type')]);
        $query->andFilterWhere(['=','station_id',yii::$app->request->get('station_id')]);
        $query->andFilterWhere(['=','manufacturer',yii::$app->request->get('manufacturer')]);
        $query->andFilterWhere(['=','connection_type',yii::$app->request->get('connection_type')]);
        $query->andFilterWhere(['=','logic_addr',yii::$app->request->get('logic_addr')]);
        $query->andFilterWhere(['=','install_type',yii::$app->request->get('install_type')]);
        $query->andFilterWhere(['like','install_site',yii::$app->request->get('install_site')]);
        $query->andFilterWhere(['>=','`install_date`',yii::$app->request->get('install_date_start')]);
        $query->andFilterWhere(['<=','`install_date`',yii::$app->request->get('install_date_end')]);
		$data = $query->asArray()->all();
		$configItems = ['connection_type','install_type'];
        $configs = (new ConfigCategory())->getCategoryConfig($configItems,'value');
		//将各combox配置项一并取得对应的文本描述
		foreach($data as $k=>$row) {
			foreach($configItems as $conf) {
				if(isset($row[$conf])) {
					$data[$k][$conf.'_txt'] = '';
					if($row[$conf]) {
						$_val = $row[$conf];
						$_txt = $configs[$conf][$_val]['text'];
						$data[$k][$conf.'_txt'] = $_txt;
					}
				}
			}
		}
        return $this->render('showOnMapWin',[
            'listData'=>$data,
            'config'=>$_config
        ]);
	}


    /**
     * 显示电桩二维码
     */
    public function actionQrCode()
    {
        $id = intval(yii::$app->request->get('id')) or die("Param(id) is required.");
        $chargerInfo = ChargeSpots::find()
            ->select(['id','code_from_compony','charge_type'])
            ->where(['id'=>$id])
            ->asArray()
            ->one();
        $configItems = ['charge_type'];
        $config = (new ConfigCategory())->getCategoryConfig($configItems,'value');
        $chargerInfo['charge_type_txt'] = $config['charge_type'][$chargerInfo['charge_type']]['text'];
        //获取测量点号
        $measuringPoint = ChargeSpots::getMeasuringPoint($chargerInfo['charge_type']);
        return $this->render('qrCodeWin',[
            'chargerInfo'=>$chargerInfo,
            'measuringPoint'=>$measuringPoint,
        ]);
    }

    /**
     * 生成电桩二维码
     */
    public function actionCreateQr(){
        $qrdata = yii::$app->request->get('qrdata') or die("Param(qrdata) is required.");
        if(!$qrdata){
            return;
        }
        include_once('../../extension/phpqrcode/qrlib.php');
        echo \QRcode::png($qrdata, false, 'L', 10, 2);
    }


    /**
     * 访问‘充电计量计费监控’窗口视图
     */
    public function actionMonitorCharge(){
        $id = isset($_GET['id']) ? $_GET['id'] : 0; // 电桩id
        return $this->render('monitorChargeWin', ['id' => $id]);
    }

    /**
     * 在‘充电计量计费监控’窗口里获取列表
     */
    public function actionMonitorChargeGetList()
    {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0; // 电桩id
        if (!$id) {
            return json_encode(['rows'=>[],'total'=>0,'errInfo'=>'没有传递电桩id！']);
        }
        $spot = ChargeSpots::find()->select(['id','charge_type','fm_id','logic_addr'])->where(['id'=>$id])->asArray()->one();
        //===连接前置机数据库并查询出相关数据==============================================
        $connectArr = ChargeFrontmachine::connect($spot['fm_id']);
        if (!$connectArr[0]) {
            return json_encode(['rows'=>[],'total'=>0,'errInfo'=>$connectArr[1]]);
        }
        $fmConnection = $connectArr[1];
        // 只查与该设备相匹配的最新监控数据
        $query = (new \yii\db\Query())
            ->select(['charging.*'])
            ->from('charging')
            ->leftJoin('charge_pole', 'charging.DEV_ID = charge_pole.DEV_ID')
            ->where(['charge_pole.DEV_ADDR'=>$spot['logic_addr']]);
        if(yii::$app->request->get('TIME_TAG_start')){
            $query->andWhere(['>=','TIME_TAG',yii::$app->request->get('TIME_TAG_start')]);
        }
        if(yii::$app->request->get('TIME_TAG_end')){
            $query->andWhere(['<=','TIME_TAG',yii::$app->request->get('TIME_TAG_end')]);
        }
        $query->andFilterWhere(['=','INNER_ID',yii::$app->request->get('INNER_ID')]);
        $total = $query->count('charging.DEV_ID', $fmConnection);
        // 分页
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount' => $total, 'pageSize' => $pageSize]);
        //排序
        if(yii::$app->request->get('sort')){
            $field = yii::$app->request->get('sort');		//field
            $direction = yii::$app->request->get('order');  //asc or desc
            switch($field){
                case 'gunName':
                    $orderStr = 'charging.INNER_ID '.$direction; break;
                default:
                    $orderStr = 'charging.'.$field.' '.$direction;
            }
        }else{
            $orderStr = 'charging.TIME_TAG desc';
        }
        $rows = $query->orderBy($orderStr)->offset($pages->offset)->limit($pages->limit)->all($fmConnection);
        //获取INNER_ID和电枪名称的对应关系
        $gunName = ChargeSpots::getGunName($spot['charge_type']);
        foreach($rows as &$row){
            if(isset($gunName[$row['INNER_ID']]) && $gunName[$row['INNER_ID']]){
                $row['gunName'] = $gunName[$row['INNER_ID']];
            }
        }
        $returnArr = ['rows'=>$rows,'total'=>$total];
        return json_encode($returnArr);
    }





}