<?php
/**
 * 车辆预警控制器
 * time    2015/10/17 11:48
 * @author wangmin
 */
namespace backend\modules\car\controllers;
use backend\controllers\BaseController;
use backend\models\Car;
use backend\models\CarInsuranceCompulsory;
use backend\models\CarInsuranceBusiness;
use backend\models\CarRoadTransportCertificate;
use backend\models\CarSecondMaintenance;
use backend\models\CarDrivingLicense;
use backend\models\ConfigCategory;
use yii;
use yii\data\Pagination;
use common\models\Excel;
use backend\models\OperatingCompany;;
use backend\models\Owner;
class AlertController extends BaseController
{
    /**
     * 交通强制险预警
     */
    public function actionInsuranceCompulsory()
    {
	
        $config = (new ConfigCategory)->getCategoryConfig(['INSURANCE_COMPANY','car_status'],'value');
        $serachConditionIC = [
            ['value'=>'','text'=>'不限']
        ];
        $insuranceCompanyName = [];
        if(isset($config['INSURANCE_COMPANY'])){
            foreach($config['INSURANCE_COMPANY'] as $k=>$v){
                array_push($serachConditionIC,['value'=>$v['value'],'text'=>$v['text']]);
                $insuranceCompanyName[$k] = ['text'=>$v['text']];
            }
        }
				
		//查询表单select选项
        $searchFormOptions = [];
        if($config['car_status']){
            $searchFormOptions['car_status'] = [];
            $searchFormOptions['car_status'][] = ['value'=>'','text'=>'不限'];
            foreach($config['car_status'] as $val){
                $searchFormOptions['car_status'][] = ['value'=>$val['value'],'text'=>$val['text']];
            }
        }
		
		//车辆运营公司
        $oc = (new \yii\db\Query())->from('cs_operating_company')->where('is_del=0')->all();
        if($oc)
        {
        	$searchFormOptions['operating_company_id'] = [];
        	$searchFormOptions['operating_company_id'][] = ['value'=>'','text'=>'不限'];
        	
        	$adminInfo_operatingCompanyIds = @$_SESSION['backend']['adminInfo']['operating_company_ids'];
        	$super = $_SESSION['backend']['adminInfo']['super'];
        	$username = $_SESSION['backend']['adminInfo']['username'];
        	//除了开发人员和超级管理员不受管控外，其他人必须检测
			if($super || $username == 'administrator'){
				foreach($oc as $val){
					$searchFormOptions['operating_company_id'][] = ['value'=>$val['id'],'text'=>$val['name']];
        		}
			}else if($adminInfo_operatingCompanyIds){
        		$adminInfo_operatingCompanyIds = explode(',',$adminInfo_operatingCompanyIds);
        		foreach($oc as $val){
        			if(in_array($val['id'],$adminInfo_operatingCompanyIds)){
        				$searchFormOptions['operating_company_id'][] = ['value'=>$val['id'],'text'=>$val['name']];
        			}
        		}
        	}
        }		
		
		
        $buttons = $this->getCurrentActionBtn();
        return $this->render('insurance-compulsory',[
            'serachConditionIC'=>$serachConditionIC,
            'insuranceCompanyName'=>$insuranceCompanyName,
            'config'=>$config,
			'searchFormOptions'=>$searchFormOptions,
            'buttons'=>$buttons
        ]);
    }

    /**
     * 获取交通强制险预警列表
     */
    public function actionIcGetList()
    {
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        //查出每辆车的最新交强险记录
        // $ids = CarInsuranceCompulsory::find()
                // ->select(['max_id'=>"max(`id`)"])
                // ->groupBy('car_id')->indexBy('max_id')->asArray()->all();
				
		  $query = CarInsuranceCompulsory::find();
		$query->from(CarInsuranceCompulsory::tableName() . ' as a');
		$query->select(['max_id'=>"id"]);
		$ids = $query->where('end_date =
						(SELECT max(end_date)
						FROM cs_car_insurance_compulsory AS b
						WHERE b.car_id=a.car_id
						)')
               ->indexBy('max_id')->asArray()->all();		
        $ids = array_keys($ids);
        $query = CarInsuranceCompulsory::find()
                ->select([
                    '{{%car_insurance_compulsory}}.*',
                    '{{%car}}.`plate_number`',
                    '{{%car}}.`car_status`',
					'{{%car}}.`operating_company_id`',
					'{{%car}}.`owner_id`',
                    '{{%admin}}.`username`'
                ])
                ->joinWith('car',false,'LEFT JOIN')
                ->joinWith('admin',false,'LEFT JOIN')
                ->where([
                    CarInsuranceCompulsory::tableName().'.`is_del`'=>0,
                ])->andWhere(['in',CarInsuranceCompulsory::tableName().'.`id`',$ids]);
        $query->andFilterWhere([
        		'=',
        		Car::tableName().'.`is_del`',
        		0
        		]);
        //查询条件
		$query->andFilterWhere(['=','{{%car}}.`operating_company_id`',yii::$app->request->get('operating_company_id')]);
		if(yii::$app->request->get('owner_id')){
        	$owner_id = yii::$app->request->get('owner_id');
        	$query->andFilterWhere(['{{%car}}.`owner_id`'=>$owner_id]);
        }
        $query->andFilterWhere([
            'like',
            Car::tableName().'.`plate_number`',
            yii::$app->request->get('plate_number')
        ]);
        $query->andFilterWhere([
            '=',
            CarInsuranceCompulsory::tableName().'.`insurer_company`',
            yii::$app->request->get('insurer_company')
        ]);
        $scanType = yii::$app->request->get('scan_type');
        if($scanType == 'seven'){
            $query->andFilterWhere([
                '<=',
                CarInsuranceCompulsory::tableName().'.`end_date`',
                time()+604800
            ]);
        }
        //检测是否要根据当前登录人员所属运营公司来显示列表数据-20160325
        $isLimitedArr = Car::isLimitedToShowByAdminOperatingCompany();
        if(is_array($isLimitedArr) && isset($isLimitedArr['status']) && $isLimitedArr['status']){
            $query->andWhere("{{%car}}.`operating_company_id` in ({$isLimitedArr['adminInfo_operatingCompanyId']})");
        }
        //查询条件结束
        //排序开始
        $sortColumn = yii::$app->request->get('sort');
        $sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
        $orderBy = '';
        if($sortColumn){
            switch ($sortColumn) {
                case 'plate_number':
                    $orderBy = Car::tableName().'.`plate_number` ';
                    break;
                case 'car_status':
                    $orderBy = Car::tableName().'.`car_status` ';
                    break;
                case '_end_date':
                    $orderBy = CarInsuranceCompulsory::tableName().'.`end_date` ';
                    break;
                case 'username':
                    $orderBy = '{{%admin}}.`username` ';
                    break;
                default:
                    $orderBy = CarInsuranceCompulsory::tableName().'.`'.$sortColumn.'` ';
                    break;
            }
        }else{
           $orderBy = CarInsuranceCompulsory::tableName().'.`id` ';
        }
        $orderBy .= $sortType;
        //排序结束
        $total = $query->count();
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)->limit($pages->limit)
                ->orderBy($orderBy)->asArray()->all();
				
		//车辆运营公司
        $oCompany = OperatingCompany::getOperatingCompany();        		
        if($data){
            foreach($data as &$dataItem){
                $dataItem['_end_date'] = $dataItem['end_date'];
				if(isset($oCompany[$dataItem['operating_company_id']]) && $oCompany[$dataItem['operating_company_id']]){
					$dataItem['operating_company_id'] = $oCompany[$dataItem['operating_company_id']]['name'];
				} 
				
				if (isset($dataItem['owner_id'])) {
					$query = Owner::find()->select(['owner_name'=>'name']);
					$query->andFilterWhere(['`id`'=>$dataItem['owner_id']]);
					$rows = $query->asArray()->one();
					if($rows){
						$dataItem['owner_name'] = $rows['owner_name'];
					}
				}
				
				
            }
        }
		
		
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        echo json_encode($returnArr);
    }


    /**
     * 导出【交通强制险预警】列表
     */
    public function actionExportIcList()
    {
        // 构建导出的excel表头
        $excHeaders = [
            [
                ['content'=>'车牌号','font-weight'=>true,'width'=>'15'],
                ['content'=>'车辆状态','font-weight'=>true,'width'=>'10'],
                ['content'=>'保险公司','font-weight'=>true,'width'=>'45'],
                ['content'=>'保险金额','font-weight'=>true,'width'=>'10'],
                ['content'=>'开始时间','font-weight'=>true,'width'=>'15'],
                ['content'=>'结束时间','font-weight'=>true,'width'=>'15'],
                ['content'=>'倒计时','font-weight'=>true,'width'=>'10'],
                ['content'=>'车辆运营公司','font-weight'=>true,'width'=>'10'],
                ['content'=>'机动车辆所有人','font-weight'=>true,'width'=>'10'],
                ['content'=>'上次操作时间','font-weight'=>true,'width'=>'20'],
                ['content'=>'上次操作人员','font-weight'=>true,'width'=>'15']
            ]
        ];

        // 要查的字段，与导出的excel表头对应
        $selectArr = [
            '{{%car}}.plate_number',
            '{{%car}}.car_status',
            '{{%car_insurance_compulsory}}.insurer_company',
            '{{%car_insurance_compulsory}}.money_amount',
            '{{%car_insurance_compulsory}}.start_date',
            '{{%car_insurance_compulsory}}.end_date',
            '_end_date'=>'{{%car_insurance_compulsory}}.end_date', //“倒计时”占位
            '{{%car}}.operating_company_id', 
            '{{%car}}.owner_id', 
            '{{%car_insurance_compulsory}}.add_datetime',
            '{{%admin}}.username'
        ];

        //查出每辆车的最新交强险记录
        // $ids = CarInsuranceCompulsory::find()
            // ->select(['max_id'=>"max(`id`)"])
            // ->groupBy('car_id')->indexBy('max_id')->asArray()->all();
		  $query = CarInsuranceCompulsory::find();
		$query->from(CarInsuranceCompulsory::tableName() . ' as a');
		$query->select(['max_id'=>"id"]);
		$ids = $query->where('end_date =
						(SELECT max(end_date)
						FROM cs_car_insurance_compulsory AS b
						WHERE b.car_id=a.car_id
						)')
               ->indexBy('max_id')->asArray()->all();	
        $ids = array_keys($ids);
        $query = CarInsuranceCompulsory::find()
            ->select($selectArr)
            ->joinWith('car',false,'LEFT JOIN')
            ->joinWith('admin',false,'LEFT JOIN')
            ->where([CarInsuranceCompulsory::tableName().'.`is_del`'=>0])
            ->andWhere(['in',CarInsuranceCompulsory::tableName().'.`id`',$ids]);
        $query->andFilterWhere([
            '=',
            Car::tableName().'.`is_del`',
            0
        ]);
        //查询条件
		$query->andFilterWhere(['=','{{%car}}.`operating_company_id`',yii::$app->request->get('operating_company_id')]);
		if(yii::$app->request->get('owner_id')){
        	$owner_id = yii::$app->request->get('owner_id');
        	$query->andFilterWhere(['{{%car}}.`owner_id`'=>$owner_id]);
        }
        $query->andFilterWhere([
            'like',
            Car::tableName().'.`plate_number`',
            yii::$app->request->get('plate_number')
        ]);
        $query->andFilterWhere([
            '=',
            CarInsuranceCompulsory::tableName().'.`insurer_company`',
            yii::$app->request->get('insurer_company')
        ]);
        $scanType = yii::$app->request->get('scan_type');
        if($scanType == 'seven'){
            $query->andFilterWhere([
                '<=',
                CarInsuranceCompulsory::tableName().'.`end_date`',
                time()+604800
            ]);
        }
        //检测是否要根据当前登录人员所属运营公司来显示列表数据-20160325
        $isLimitedArr = Car::isLimitedToShowByAdminOperatingCompany();
        if(is_array($isLimitedArr) && isset($isLimitedArr['status']) && $isLimitedArr['status']){
            $query->andWhere("{{%car}}.`operating_company_id` in ({$isLimitedArr['adminInfo_operatingCompanyId']})");
        }
		
        //查询条件结束
        //$data = $query->asArray()->all();
        //print_r($data);exit;
		//车辆运营公司
        $oCompany = OperatingCompany::getOperatingCompany();        		
       		
        $excel = new Excel();
        $excel->setHeader([
            'creator'=>'皓峰通讯',
            'lastModifiedBy'=>'hao feng tong xun',
            'title'=>'CarInsuranceCompulsory',
            'subject'=>'CarInsuranceCompulsory',
            'description'=>'CarInsuranceCompulsory',
            'keywords'=>'CarInsuranceCompulsory',
            'category'=>'CarInsuranceCompulsory'
        ]);
        //---向excel添加表头-----------------
        foreach($excHeaders as $lineData){
            $excel->addLineToExcel($lineData);
        }
        //---向excel添加具体数据--------------
        $configItems = ['car_status','INSURANCE_COMPANY'];
        $configs = (new ConfigCategory())->getCategoryConfig($configItems,'value');
        //分批次查询数据
        $total = $query->count();
        $pageSize = 200;
        $pageNum = ceil($total/$pageSize);
        for($i=1; $i<=$pageNum; $i++){
            $offset = ($i-1) * $pageSize;
            $data = $query
                ->offset($offset)->limit($pageSize)
                ->orderBy('{{%car_insurance_compulsory}}.id ASC')
                ->asArray()->all();
            foreach($data as $item){				 
				if (isset($item['operating_company_id'])) {
					if(isset($oCompany[$item['operating_company_id']]) && $oCompany[$item['operating_company_id']]){
						$item['operating_company_id'] = $oCompany[$item['operating_company_id']]['name'];
					} 
				}
				if (isset($item['owner_id'])) {
					$query_owner = Owner::find()->select(['owner_name'=>'name']);
					$query_owner->andFilterWhere(['`id`'=>$item['owner_id']]);
					$rows = $query_owner->asArray()->one();
					if($rows){
						$item['owner_id'] = $rows['owner_name'];
					}
				}		
				
                $item['start_date'] = $item['start_date'] ? date('Y-m-d',$item['start_date']) : '';
                $item['end_date'] = $item['end_date'] ? date('Y-m-d',$item['end_date']) : '';
                if($item['_end_date']){
                    if($item['_end_date'] < time()){
                        $item['_end_date'] = '已过期';
                    }else{
                        $diff = $item['_end_date'] - strtotime(date('Y-m-d',time())); //年月日
                        $days = floor($diff/(3600*24)) + 1; //+1包含今日在内
                        $item['_end_date'] = $days.'天';
                    }
                }else{
                    $item['_end_date'] = '';
                }
                $item['add_datetime'] = $item['add_datetime'] ? date('Y-m-d H:i:s',$item['add_datetime']) : '';
                $lineData = [];
                // 各combox配置项以txt代替val
                foreach($configItems as $conf) {
                    if(isset($item[$conf]) && $item[$conf]) {
                        $item[$conf] = $configs[$conf][$item[$conf]]['text'];
                    }elseif($conf == 'INSURANCE_COMPANY'){
                        $realKey = 'insurer_company';
                        if(isset($item[$realKey]) && $item[$realKey]) {
                            $item[$realKey] = $configs[$conf][$item[$realKey]]['text'];
                        }
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
        }
		set_time_limit(0);
        $objPHPExcel = $excel->getPHPExcel();
        header("Content-type: application/octet-stream");
        header("Accept-Ranges: bytes");
        //header("Accept-Length:".$fileSize);
        header("Content-Disposition: attachment; filename=".iconv('utf-8','gbk','交强险预警导出.xls'));
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }



    /**
     * 商业险预警
     */
    public function actionBusinessCompulsory()
    {
        $config = (new ConfigCategory)->getCategoryConfig(['INSURANCE_COMPANY','car_status'],'value');
        $serachConditionIC = [
            ['value'=>'','text'=>'不限']
        ];
        $insurerCompany = [];
        if(isset($config['INSURANCE_COMPANY'])){
            foreach($config['INSURANCE_COMPANY'] as $k=>$v){
                array_push($serachConditionIC,['value'=>$v['value'],'text'=>$v['text']]);
                $insurerCompany[$k] = ['text'=>$v['text']];
            }
        }
		//查询表单select选项
        $searchFormOptions = [];
        if($config['car_status']){
            $searchFormOptions['car_status'] = [];
            $searchFormOptions['car_status'][] = ['value'=>'','text'=>'不限'];
            foreach($config['car_status'] as $val){
                $searchFormOptions['car_status'][] = ['value'=>$val['value'],'text'=>$val['text']];
            }
        }
		
		//车辆运营公司
        $oc = (new \yii\db\Query())->from('cs_operating_company')->where('is_del=0')->all();
        if($oc)
        {
        	$searchFormOptions['operating_company_id'] = [];
        	$searchFormOptions['operating_company_id'][] = ['value'=>'','text'=>'不限'];
        	
        	$adminInfo_operatingCompanyIds = @$_SESSION['backend']['adminInfo']['operating_company_ids'];
        	$super = $_SESSION['backend']['adminInfo']['super'];
        	$username = $_SESSION['backend']['adminInfo']['username'];
        	//除了开发人员和超级管理员不受管控外，其他人必须检测
			if($super || $username == 'administrator'){
				foreach($oc as $val){
					$searchFormOptions['operating_company_id'][] = ['value'=>$val['id'],'text'=>$val['name']];
        		}
			}else if($adminInfo_operatingCompanyIds){
        		$adminInfo_operatingCompanyIds = explode(',',$adminInfo_operatingCompanyIds);
        		foreach($oc as $val){
        			if(in_array($val['id'],$adminInfo_operatingCompanyIds)){
        				$searchFormOptions['operating_company_id'][] = ['value'=>$val['id'],'text'=>$val['name']];
        			}
        		}
        	}
        }		
		
		
		
		
		
        $buttons = $this->getCurrentActionBtn();
        return $this->render('business-compulsory',[
            'serachConditionIC'=>$serachConditionIC,
            'insurerCompany'=>$insurerCompany,
            'config'=>$config,
			'searchFormOptions'=>$searchFormOptions,
            'buttons'=>$buttons
        ]);
    }

    /**
     * 获取商业险预警列表
     */
    public function actionBcGetList()
    {
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        //查出每辆车的最新交强险记录
        $query = CarInsuranceBusiness::find();
		$query->from(CarInsuranceBusiness::tableName() . ' as a');
		$query->select(['max_id'=>"id"]);
		$ids = $query->where('end_date =
						(SELECT max(end_date)
						FROM cs_car_insurance_business AS b
						WHERE b.car_id=a.car_id
						)')
               ->indexBy('max_id')->asArray()->all();
				// $ids = CarInsuranceBusiness::find()
                // ->select(['max_id'=>"max(`id`)"])
                // ->groupBy('car_id')->indexBy('max_id')->asArray()->all();
		//var_dump($ids);exit;
			// $connection  = Yii::$app->db;
			// $sql     = "SELECT id as max_id
						// FROM cs_car_insurance_business AS a
						// WHERE end_date =
						// (SELECT max(end_date)
						// FROM cs_car_insurance_business AS b
						// WHERE b.car_id=a.car_id
						// )";
			// $sth     = $connection->createCommand($sql)->queryAll();
			
			// var_dump($sth);exit;
        $ids = array_keys($ids);
        $query = CarInsuranceBusiness::find()
                ->select([
                    '{{%car_insurance_business}}.*',
                    '{{%car}}.`plate_number`',
                    '{{%car}}.`car_status`',
					'{{%car}}.`operating_company_id`',
					'{{%car}}.`owner_id`',
                    '{{%admin}}.`username`'
                ])
                ->joinWith('car',false,'LEFT JOIN')
                ->joinWith('admin',false,'LEFT JOIN')
                ->where([
                    CarInsuranceBusiness::tableName().'.`is_del`'=>0,
                ])->andWhere(['in',CarInsuranceBusiness::tableName().'.`id`',$ids]);
        $query->andFilterWhere([
        		'=',
        		Car::tableName().'.`is_del`',
        		0
        		]);
        //查询条件
		$query->andFilterWhere(['=','{{%car}}.`operating_company_id`',yii::$app->request->get('operating_company_id')]);
		if(yii::$app->request->get('owner_id')){
        	$owner_id = yii::$app->request->get('owner_id');
        	$query->andFilterWhere(['{{%car}}.`owner_id`'=>$owner_id]);
        }
		
        $query->andFilterWhere([
            'like',
            Car::tableName().'.`plate_number`',
            yii::$app->request->get('plate_number')
        ]);
        $query->andFilterWhere([
            '=',
            CarInsuranceBusiness::tableName().'.`insurer_company`',
            yii::$app->request->get('insurer_company')
        ]);
        $scanType = yii::$app->request->get('scan_type');
        if($scanType == 'seven'){
            $query->andFilterWhere([
                '<=',
                CarInsuranceBusiness::tableName().'.`end_date`',
                time()+604800
            ]);
        }
        //检测是否要根据当前登录人员所属运营公司来显示列表数据-20160325
        $isLimitedArr = Car::isLimitedToShowByAdminOperatingCompany();
        if(is_array($isLimitedArr) && isset($isLimitedArr['status']) && $isLimitedArr['status']){
            $query->andWhere("{{%car}}.`operating_company_id` in ({$isLimitedArr['adminInfo_operatingCompanyId']})");
        }
        //查询条件结束
        //排序开始
        $sortColumn = yii::$app->request->get('sort');
        $sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
        $orderBy = '';
        if($sortColumn){
            switch ($sortColumn) {
                case 'plate_number':
                    $orderBy = Car::tableName().'.`plate_number` ';
                    break;
                case 'car_status':
                    $orderBy = Car::tableName().'.`car_status` ';
                    break;
                case '_end_date':
                    $orderBy = CarInsuranceBusiness::tableName().'.`end_date` ';
                    break;
                case 'username':
                    $orderBy = '{{%admin}}.`username` ';
                    break;
                default:
                    $orderBy = CarInsuranceBusiness::tableName().'.`'.$sortColumn.'` ';
                    break;
            }
        }else{
           $orderBy = CarInsuranceBusiness::tableName().'.`id` ';
        }
        $orderBy .= $sortType;
        $total = $query->count();
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)->limit($pages->limit)
                ->orderBy($orderBy)->asArray()->all();
				
				
		//车辆运营公司
        $oCompany = OperatingCompany::getOperatingCompany();        		
        if($data){
            foreach($data as &$dataItem){
                $dataItem['_end_date'] = $dataItem['end_date'];
				if(isset($oCompany[$dataItem['operating_company_id']]) && $oCompany[$dataItem['operating_company_id']]){
					$dataItem['operating_company_id'] = $oCompany[$dataItem['operating_company_id']]['name'];
				} 
				
				if (isset($dataItem['owner_id'])) {
					$query = Owner::find()->select(['owner_name'=>'name']);
					$query->andFilterWhere(['`id`'=>$dataItem['owner_id']]);
					$rows = $query->asArray()->one();
					if($rows){
						$dataItem['owner_name'] = $rows['owner_name'];
					}
				}
				
				
            }
        }				
       
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        echo json_encode($returnArr);
    }


    /**
     * 导出【商业险预警】列表
     */
    public function actionExportBcList()
    {
        // 构建导出的excel表头
        $excHeaders = [
            [
                ['content'=>'车牌号','font-weight'=>true,'width'=>'15'],
                ['content'=>'车辆状态','font-weight'=>true,'width'=>'10'],
                ['content'=>'保险公司','font-weight'=>true,'width'=>'45'],
                ['content'=>'保险金额','font-weight'=>true,'width'=>'10'],
                ['content'=>'开始时间','font-weight'=>true,'width'=>'15'],
                ['content'=>'结束时间','font-weight'=>true,'width'=>'15'],
                ['content'=>'倒计时','font-weight'=>true,'width'=>'10'],
				['content'=>'车辆运营公司','font-weight'=>true,'width'=>'10'],
                ['content'=>'机动车辆所有人','font-weight'=>true,'width'=>'10'],
                ['content'=>'上次操作时间','font-weight'=>true,'width'=>'20'],
                ['content'=>'上次操作人员','font-weight'=>true,'width'=>'15']
            ]
        ];

        // 要查的字段，与导出的excel表头对应
        $selectArr = [
            '{{%car}}.plate_number',
            '{{%car}}.car_status',
            '{{%car_insurance_business}}.insurer_company',
            '{{%car_insurance_business}}.money_amount',
            '{{%car_insurance_business}}.start_date',
            '{{%car_insurance_business}}.end_date',
            '_end_date'=>'{{%car_insurance_business}}.end_date', //“倒计时”占位
			'{{%car}}.operating_company_id', 
            '{{%car}}.owner_id', 
            '{{%car_insurance_business}}.add_datetime',
            '{{%admin}}.username'
        ];

        //查出每辆车的最新交强险记录
        // $ids = CarInsuranceBusiness::find()
            // ->select(['max_id'=>"max(`id`)"])
            // ->groupBy('car_id')->indexBy('max_id')->asArray()->all();
			
		  $query = CarInsuranceBusiness::find();
		$query->from(CarInsuranceBusiness::tableName() . ' as a');
		$query->select(['max_id'=>"id"]);
		$ids = $query->where('end_date =
						(SELECT max(end_date)
						FROM cs_car_insurance_business AS b
						WHERE b.car_id=a.car_id
						)')
               ->indexBy('max_id')->asArray()->all();	
        $ids = array_keys($ids);
        $query = CarInsuranceBusiness::find()
            ->select($selectArr)
            ->joinWith('car',false,'LEFT JOIN')
            ->joinWith('admin',false,'LEFT JOIN')
            ->where([CarInsuranceBusiness::tableName().'.`is_del`'=>0])
            ->andWhere(['in',CarInsuranceBusiness::tableName().'.`id`',$ids]);
        //查询条件
		$query->andFilterWhere(['=','{{%car}}.`operating_company_id`',yii::$app->request->get('operating_company_id')]);
		if(yii::$app->request->get('owner_id')){
        	$owner_id = yii::$app->request->get('owner_id');
        	$query->andFilterWhere(['{{%car}}.`owner_id`'=>$owner_id]);
        }
        $query->andFilterWhere([
            'like',
            Car::tableName().'.`plate_number`',
            yii::$app->request->get('plate_number')
        ]);
        $query->andFilterWhere([
            '=',
            CarInsuranceBusiness::tableName().'.`insurer_company`',
            yii::$app->request->get('insurer_company')
        ]);
        $scanType = yii::$app->request->get('scan_type');
        if($scanType == 'seven'){
            $query->andFilterWhere([
                '<=',
                CarInsuranceBusiness::tableName().'.`end_date`',
                time()+604800
            ]);
        }
        //检测是否要根据当前登录人员所属运营公司来显示列表数据-20160325
        $isLimitedArr = Car::isLimitedToShowByAdminOperatingCompany();
        if(is_array($isLimitedArr) && isset($isLimitedArr['status']) && $isLimitedArr['status']){
            $query->andWhere("{{%car}}.`operating_company_id` in ({$isLimitedArr['adminInfo_operatingCompanyId']})");
        }
        //查询条件结束
        //$data = $query->asArray()->all();
        //print_r($data);exit;
		//车辆运营公司
        $oCompany = OperatingCompany::getOperatingCompany();        		
       		
		set_time_limit(0);
        $excel = new Excel();
        $excel->setHeader([
            'creator'=>'皓峰通讯',
            'lastModifiedBy'=>'hao feng tong xun',
            'title'=>'CarInsuranceBusiness',
            'subject'=>'CarInsuranceBusiness',
            'description'=>'CarInsuranceBusiness',
            'keywords'=>'CarInsuranceBusiness',
            'category'=>'CarInsuranceBusiness'
        ]);
        //---向excel添加表头-----------------
        foreach($excHeaders as $lineData){
            $excel->addLineToExcel($lineData);
        }
        //---向excel添加具体数据--------------
        $configItems = ['car_status','INSURANCE_COMPANY'];
        $configs = (new ConfigCategory())->getCategoryConfig($configItems,'value');
        //分批次查询数据
        $total = $query->count();
        $pageSize = 200;
        $pageNum = ceil($total/$pageSize);
        for($i=1; $i<=$pageNum; $i++){
            $offset = ($i-1) * $pageSize;
            $data = $query
                ->offset($offset)->limit($pageSize)
                ->orderBy('{{%car_insurance_business}}.end_date ASC')
                ->asArray()->all();
            foreach($data as $item){
				if (isset($item['operating_company_id'])) {
					if(isset($oCompany[$item['operating_company_id']]) && $oCompany[$item['operating_company_id']]){
						$item['operating_company_id'] = $oCompany[$item['operating_company_id']]['name'];
					} 
				}
				if (isset($item['owner_id'])) {
					$query_owner = Owner::find()->select(['owner_name'=>'name']);
					$query_owner->andFilterWhere(['`id`'=>$item['owner_id']]);
					$rows = $query_owner->asArray()->one();
					if($rows){
						$item['owner_id'] = $rows['owner_name'];
					}
				}		
                $item['start_date'] = $item['start_date'] ? date('Y-m-d',$item['start_date']) : '';
                $item['end_date'] = $item['end_date'] ? date('Y-m-d',$item['end_date']) : '';
                if($item['_end_date']){
                    if($item['_end_date'] < time()){
                        $item['_end_date'] = '已过期';
                    }else{
                        $diff = $item['_end_date'] - strtotime(date('Y-m-d',time())); //年月日
                        $days = floor($diff/(3600*24)) + 1; //+1包含今日在内
                        $item['_end_date'] = $days.'天';
                    }
                }else{
                    $item['_end_date'] = '';
                }
                $item['add_datetime'] = $item['add_datetime'] ? date('Y-m-d H:i:s',$item['add_datetime']) : '';
                $lineData = [];
                // 各combox配置项以txt代替val
                foreach($configItems as $conf) {
                    if(isset($item[$conf]) && $item[$conf]) {
                        $item[$conf] = $configs[$conf][$item[$conf]]['text'];
                    }elseif($conf == 'INSURANCE_COMPANY'){
                        $realKey = 'insurer_company';
                        if(isset($item[$realKey]) && $item[$realKey]) {
                            $item[$realKey] = $configs[$conf][$item[$realKey]]['text'];
                        }
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
        }

        $objPHPExcel = $excel->getPHPExcel();
        header("Content-type: application/octet-stream");
        header("Accept-Ranges: bytes");
        //header("Accept-Length:".$fileSize);
        header("Content-Disposition: attachment; filename=".iconv('utf-8','gbk','商业险预警导出.xls'));
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }



    /**
     * 道路运输证预警
     */
    public function actionRoudTranCer()
    {
        $config = (new ConfigCategory)->getCategoryConfig(['TC_ISSUED_BY','car_status'],'value');
        $buttons = $this->getCurrentActionBtn();
        return $this->render('roud-tran-cer',[
            'config'=>$config,
            'buttons'=>$buttons
        ]);
    }

    /**
     * 道路运输证预警列表
     */
    public function actionRtcGetList()
    {
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $query = CarRoadTransportCertificate::find()
                ->select([
                    CarRoadTransportCertificate::tableName().'.*',
                    Car::tableName().'.`plate_number`',
                    Car::tableName().'.`car_status`',
                    '{{%admin}}.`username`'
                ])
                ->joinWith('car',false,'LEFT JOIN')
                ->joinWith('admin',false,'LEFT JOIN');
        $query->andFilterWhere([
        		'=',
        		Car::tableName().'.`is_del`',
        		0
        		]);
        //查询条件
        $query->andFilterWhere([
            'like',
            Car::tableName().'.`plate_number`',
            yii::$app->request->get('plate_number')
        ]);
        $scanType = yii::$app->request->get('scan_type');
        if($scanType == 'thirty'){
            $query->andFilterWhere([
                '<=',
                CarRoadTransportCertificate::tableName().'.`next_annual_verification_date`',
                time()+2592000
            ]);
        }
        //检测是否要根据当前登录人员所属运营公司来显示列表数据-20160325
        $isLimitedArr = Car::isLimitedToShowByAdminOperatingCompany();
        if(is_array($isLimitedArr) && isset($isLimitedArr['status']) && $isLimitedArr['status']){
            $query->andWhere("{{%car}}.`operating_company_id` in ({$isLimitedArr['adminInfo_operatingCompanyId']})");
        }
        //查询条件结束
        //排序开始
        $sortColumn = yii::$app->request->get('sort');
        $sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
        $orderBy = '';
        if($sortColumn){
            switch ($sortColumn) {
                case 'plate_number':
                    $orderBy = Car::tableName().'.`plate_number` ';
                    break;
                case 'car_status':
                    $orderBy = Car::tableName().'.`car_status` ';
                    break;
                case 'username':
                    $orderBy = '{{%admin}}.`username` ';
                    break;
                default:
                    $orderBy = '{{%car_road_transport_certificate}}.`'.$sortColumn.'` ';
                    break;
            }
        }else{
           $orderBy = '{{%car_road_transport_certificate}}.`id` ';
        }
        $orderBy .= $sortType;
        $total = $query->count();
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)->limit($pages->limit)
                ->orderBy($orderBy)->asArray()->all();
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        echo json_encode($returnArr);
    }

    /**
     * 导出【道路运输证预警】列表
     */
    public function actionExportRtcList()
    {
        // 构建导出的excel表头
        $excHeaders = [
            [
                ['content'=>'车牌号','font-weight'=>true,'width'=>'15'],
                ['content'=>'车辆状态','font-weight'=>true,'width'=>'10'],
                ['content'=>'吨（座）位','font-weight'=>true,'width'=>'10'],
                ['content'=>'核发机关','font-weight'=>true,'width'=>'25'],
                ['content'=>'省','font-weight'=>true,'width'=>'5'],
                ['content'=>'市','font-weight'=>true,'width'=>'5'],
                ['content'=>'运输证号','font-weight'=>true,'width'=>'15'],
                ['content'=>'发证日期','font-weight'=>true,'width'=>'15'],
                ['content'=>'上次审核时间','font-weight'=>true,'width'=>'15'],
                ['content'=>'下次审核时间','font-weight'=>true,'width'=>'15'],
                ['content'=>'倒计时','font-weight'=>true,'width'=>'10'],
                ['content'=>'上次操作时间','font-weight'=>true,'width'=>'20'],
                ['content'=>'上次操作人员','font-weight'=>true,'width'=>'15']
            ]
        ];

        // 要查的字段，与导出的excel表头对应
        $selectArr = [
            '{{%car}}.plate_number',
            '{{%car}}.car_status',
            '{{%car_road_transport_certificate}}.ton_or_seat',
            '{{%car_road_transport_certificate}}.issuing_organ',
            '{{%car_road_transport_certificate}}.rtc_province',
            '{{%car_road_transport_certificate}}.rtc_city',
            '{{%car_road_transport_certificate}}.rtc_number',
            '{{%car_road_transport_certificate}}.issuing_date',
            '{{%car_road_transport_certificate}}.last_annual_verification_date',
            '{{%car_road_transport_certificate}}.next_annual_verification_date',
            'remaining_time'=>'{{%car_road_transport_certificate}}.next_annual_verification_date', //“倒计时”占位
            '{{%car_road_transport_certificate}}.add_datetime',
            '{{%admin}}.username'
        ];

        //查出每辆车的最新交强险记录
        $ids = CarInsuranceBusiness::find()
            ->select(['max_id'=>"max(`id`)"])
            ->groupBy('car_id')->indexBy('max_id')->asArray()->all();
        $ids = array_keys($ids);
        $query = CarRoadTransportCertificate::find()
            ->select($selectArr)
            ->joinWith('car',false,'LEFT JOIN')
            ->joinWith('admin',false,'LEFT JOIN');
        $query->andFilterWhere([
        		'=',
        		Car::tableName().'.`is_del`',
        		0
        		]);
        //查询条件
        $query->andFilterWhere([
            'like',
            Car::tableName().'.`plate_number`',
            yii::$app->request->get('plate_number')
        ]);
        $scanType = yii::$app->request->get('scan_type');
        if($scanType == 'thirty'){
            $query->andFilterWhere([
                '<=',
                CarRoadTransportCertificate::tableName().'.`next_annual_verification_date`',
                time()+2592000
            ]);
        }
        //检测是否要根据当前登录人员所属运营公司来显示列表数据-20160325
        $isLimitedArr = Car::isLimitedToShowByAdminOperatingCompany();
        if(is_array($isLimitedArr) && isset($isLimitedArr['status']) && $isLimitedArr['status']){
            $query->andWhere("{{%car}}.`operating_company_id` in ({$isLimitedArr['adminInfo_operatingCompanyId']})");
        }
        //查询条件结束
        //$data = $query->asArray()->all();
        //print_r($data);exit;

        $excel = new Excel();
        $excel->setHeader([
            'creator'=>'皓峰通讯',
            'lastModifiedBy'=>'hao feng tong xun',
            'title'=>'CarRoadTransportCertificate',
            'subject'=>'CarRoadTransportCertificate',
            'description'=>'CarRoadTransportCertificate',
            'keywords'=>'CarRoadTransportCertificate',
            'category'=>'CarRoadTransportCertificate'
        ]);
        //---向excel添加表头-----------------
        foreach($excHeaders as $lineData){
            $excel->addLineToExcel($lineData);
        }
        //---向excel添加具体数据--------------
        $configItems = ['car_status','TC_ISSUED_BY'];
        $configs = (new ConfigCategory())->getCategoryConfig($configItems,'value');
        //分批次查询数据
        $total = $query->count();
        $pageSize = 200;
        $pageNum = ceil($total/$pageSize);
        for($i=1; $i<=$pageNum; $i++){
            $offset = ($i-1) * $pageSize;
            $data = $query
                ->offset($offset)->limit($pageSize)
                ->orderBy('{{%car_road_transport_certificate}}.next_annual_verification_date ASC')
                ->asArray()->all();
            foreach($data as $item){
                $item['ton_or_seat'] = ' '.$item['ton_or_seat'];
                $item['issuing_date'] = $item['issuing_date'] ? date('Y-m-d',$item['issuing_date']) : '';
                $item['last_annual_verification_date'] = $item['last_annual_verification_date'] ? date('Y-m-d',$item['last_annual_verification_date']) : '';
                $item['next_annual_verification_date'] = $item['next_annual_verification_date'] ? date('Y-m-d',$item['next_annual_verification_date']) : '';
                if($item['remaining_time']){
                    if($item['remaining_time'] < time()){
                        $item['remaining_time'] = '已过期';
                    }else{
                        $diff = $item['remaining_time'] - strtotime(date('Y-m-d',time())); //年月日
                        $days = floor($diff/(3600*24)) + 1; //+1包含今日在内
                        $item['remaining_time'] = $days.'天';
                    }
                }else{
                    $item['remaining_time'] = '';
                }
                $item['add_datetime'] = $item['add_datetime'] ? date('Y-m-d H:i:s',$item['add_datetime']) : '';
                $lineData = [];
                // 各combox配置项以txt代替val
                foreach($configItems as $conf) {
                    if(isset($item[$conf]) && $item[$conf]) {

                        //var_dump($item[$conf]);exit;
                        if($item[$conf] != 'REPLACE'){
                            $item[$conf] = $configs[$conf][$item[$conf]]['text'];
                        }
                        //echo '-----------------------------------';
                        //echo '<pre>';
                        //var_dump($configs[$conf][$item[$conf]]);exit;
                        

                    }elseif($conf == 'TC_ISSUED_BY'){
                        $realKey = 'issuing_organ';
                        if(isset($item[$realKey]) && $item[$realKey]) {
                            //echo '<pre>';
                            //var_dump($configs[$conf][$item[$realKey]]['text']);exit;
                            //echo '-----------------------------------';
                           //var_dump($item[$realKey]);exit;
                            if(isset($configs[$conf][$item[$realKey]])){
                                $item[$realKey] = $configs[$conf][$item[$realKey]]['text'];
                            }
                            

                        }
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
        }

        $objPHPExcel = $excel->getPHPExcel();
        header("Content-type: application/octet-stream");
        header("Accept-Ranges: bytes");
        //header("Accept-Length:".$fileSize);
        header("Content-Disposition: attachment; filename=".iconv('utf-8','gbk','道路运输证预警导出.xls'));
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }


    /**
     * 二级维护记录预警
     */
    public function actionSecMain()
    {
        $config = (new ConfigCategory)->getCategoryConfig(['car_status'],'value');
        $buttons = $this->getCurrentActionBtn();
        return $this->render('sec-main',[
            'config'=>$config,
            'buttons'=>$buttons
        ]);
    }

    /**
     * 二级维护记录预警列表
     */
    public function actionSmGetList()
    {
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        //查出每辆车的最新交强险记录
        $ids = CarSecondMaintenance::find()
                ->select(['max_id'=>"max(`id`)"])
                ->groupBy('car_id')->indexBy('max_id')->asArray()->all();
        $ids = array_keys($ids);
        $query = CarSecondMaintenance::find()
                ->select([
                    CarSecondMaintenance::tableName().'.*',
                    Car::tableName().'.`plate_number`',
                    Car::tableName().'.`car_status`',
                    '{{%admin}}.`username`',
                ])
                ->joinWith('car',false,'LEFT JOIN')
                ->joinWith('admin',false,'LEFT JOIN')
                ->where([
                    CarSecondMaintenance::tableName().'.`is_del`'=>0,
                ])->andWhere(['in',CarSecondMaintenance::tableName().'.`id`',$ids]);
        //查询条件
        $query->andFilterWhere([
            'like',
            Car::tableName().'.`plate_number`',
            yii::$app->request->get('plate_number')
        ]);
        $query->andFilterWhere([
            'like',
            CarSecondMaintenance::tableName().'.`number`',
            yii::$app->request->get('number')
        ]);
        $scanType = yii::$app->request->get('scan_type');
        if($scanType == 'thirty'){
            $query->andFilterWhere([
                '<=',
                CarSecondMaintenance::tableName().'.`next_date`',
                time()+2592000
            ]);
        }
        //检测是否要根据当前登录人员所属运营公司来显示列表数据-20160325
        $isLimitedArr = Car::isLimitedToShowByAdminOperatingCompany();
        if(is_array($isLimitedArr) && isset($isLimitedArr['status']) && $isLimitedArr['status']){
            $query->andWhere("{{%car}}.`operating_company_id` in ({$isLimitedArr['adminInfo_operatingCompanyId']})");
        }
        //查询条件结束
        //排序开始
        $sortColumn = yii::$app->request->get('sort');
        $sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
        $orderBy = '';
        if($sortColumn){
            switch ($sortColumn) {
                case 'plate_number':
                    $orderBy = Car::tableName().'.`plate_number` ';
                    break;
                case 'car_status':
                    $orderBy = Car::tableName().'.`car_status` ';
                    break;
                case '_next_date':
                    $orderBy = CarSecondMaintenance::tableName().'.`next_date` ';
                    break;
                case 'username':
                    $orderBy = '{{%admin}}.`username` ';
                    break;
                default:
                    $orderBy = CarSecondMaintenance::tableName().'.`'.$sortColumn.'` ';
                    break;
            }
        }else{
           $orderBy = CarSecondMaintenance::tableName().'.`id` ';
        }
        $orderBy .= $sortType;
        //排序结束
        $total = $query->count();
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)->limit($pages->limit)
                ->orderBy($orderBy)->asArray()->all();
        if($data){
            foreach($data as &$SMListVal){
                $SMListVal['_next_date'] = $SMListVal['next_date'];
            }
        }
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        echo json_encode($returnArr);
    }

    /**
     * 导出【二级维护记录预警】列表
     */
    public function actionExportSmList()
    {
        // 构建导出的excel表头
        $excHeaders = [
            [
                ['content'=>'车牌号','font-weight'=>true,'width'=>'15'],
                ['content'=>'车辆状态','font-weight'=>true,'width'=>'10'],
                ['content'=>'编号','font-weight'=>true,'width'=>'15'],
                ['content'=>'本次维护时间','font-weight'=>true,'width'=>'15'],
                ['content'=>'下次维护时间','font-weight'=>true,'width'=>'15'],
                ['content'=>'倒计时','font-weight'=>true,'width'=>'10'],
                ['content'=>'上次操作时间','font-weight'=>true,'width'=>'20'],
                ['content'=>'上次操作人员','font-weight'=>true,'width'=>'15']
            ]
        ];

        // 要查的字段，与导出的excel表头对应
        $selectArr = [
            '{{%car}}.plate_number',
            '{{%car}}.car_status',
            '{{%car_second_maintenance}}.number',
            '{{%car_second_maintenance}}.current_date',
            '{{%car_second_maintenance}}.next_date',
            'remaining_time'=>'{{%car_second_maintenance}}.next_date', //“倒计时”占位
            '{{%car_second_maintenance}}.add_datetime',
            '{{%admin}}.username'
        ];

        //查出每辆车的最新交强险记录
        $ids = CarSecondMaintenance::find()
            ->select(['max_id'=>"max(`id`)"])
            ->groupBy('car_id')->indexBy('max_id')->asArray()->all();
        $ids = array_keys($ids);
        $query = CarSecondMaintenance::find()
            ->select($selectArr)
            ->joinWith('car',false,'LEFT JOIN')
            ->joinWith('admin',false,'LEFT JOIN')
            ->where([
                CarSecondMaintenance::tableName().'.`is_del`'=>0,
            ])->andWhere(['in',CarSecondMaintenance::tableName().'.`id`',$ids]);
        //查询条件
        $query->andFilterWhere([
            'like',
            Car::tableName().'.`plate_number`',
            yii::$app->request->get('plate_number')
        ]);
        $query->andFilterWhere([
            'like',
            CarSecondMaintenance::tableName().'.`number`',
            yii::$app->request->get('number')
        ]);
        $scanType = yii::$app->request->get('scan_type');
        if($scanType == 'thirty'){
            $query->andFilterWhere([
                '<=',
                CarSecondMaintenance::tableName().'.`next_date`',
                time()+2592000
            ]);
        }
        //检测是否要根据当前登录人员所属运营公司来显示列表数据-20160325
        $isLimitedArr = Car::isLimitedToShowByAdminOperatingCompany();
        if(is_array($isLimitedArr) && isset($isLimitedArr['status']) && $isLimitedArr['status']){
            $query->andWhere("{{%car}}.`operating_company_id` in ({$isLimitedArr['adminInfo_operatingCompanyId']})");
        }
        //查询条件结束
        //$data = $query->asArray()->all();
        //print_r($data);exit;

        $excel = new Excel();
        $excel->setHeader([
            'creator'=>'皓峰通讯',
            'lastModifiedBy'=>'hao feng tong xun',
            'title'=>'CarSecondMaintenance',
            'subject'=>'CarSecondMaintenance',
            'description'=>'CarSecondMaintenance',
            'keywords'=>'CarSecondMaintenance',
            'category'=>'CarSecondMaintenance'
        ]);
        //---向excel添加表头-----------------
        foreach($excHeaders as $lineData){
            $excel->addLineToExcel($lineData);
        }
        //---向excel添加具体数据--------------
        $configItems = ['car_status'];
        $configs = (new ConfigCategory())->getCategoryConfig($configItems,'value');
        //分批次查询数据
        $total = $query->count();
        $pageSize = 200;
        $pageNum = ceil($total/$pageSize);
        for($i=1; $i<=$pageNum; $i++){
            $offset = ($i-1) * $pageSize;
            $data = $query
                ->offset($offset)->limit($pageSize)
                ->orderBy('{{%car_second_maintenance}}.next_date ASC')
                ->asArray()->all();
            foreach($data as $item){
                $item['current_date'] = $item['current_date'] ? date('Y-m-d',$item['current_date']) : '';
                $item['next_date'] = $item['next_date'] ? date('Y-m-d',$item['next_date']) : '';
                if($item['remaining_time']){
                    if($item['remaining_time'] < time()){
                        $item['remaining_time'] = '已过期';
                    }else{
                        $diff = $item['remaining_time'] - strtotime(date('Y-m-d',time())); //年月日
                        $days = floor($diff/(3600*24)) + 1; //+1包含今日在内
                        $item['remaining_time'] = $days.'天';
                    }
                }else{
                    $item['remaining_time'] = '';
                }
                $item['add_datetime'] = $item['add_datetime'] ? date('Y-m-d H:i:s',$item['add_datetime']) : '';
                $lineData = [];
                // 各combox配置项以txt代替val
                foreach($configItems as $conf) {
                    if(isset($item[$conf]) && $item[$conf]) {
                        $item[$conf] = $configs[$conf][$item[$conf]]['text'];
                    }elseif($conf == 'TC_ISSUED_BY'){
                        $realKey = 'issuing_organ';
                        if(isset($item[$realKey]) && $item[$realKey]) {
                            $item[$realKey] = $configs[$conf][$item[$realKey]]['text'];
                        }
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
        }

        $objPHPExcel = $excel->getPHPExcel();
        header("Content-type: application/octet-stream");
        header("Accept-Ranges: bytes");
        //header("Accept-Length:".$fileSize);
        header("Content-Disposition: attachment; filename=".iconv('utf-8','gbk','二级维护记录预警导出.xls'));
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }



    /**
     * 行驶证年审预警
     */
    public function actionDrivLicense()
    {
        $config = (new ConfigCategory)->getCategoryConfig(['car_status','DL_REG_ADDR'],'value');
        $buttons = $this->getCurrentActionBtn();
        return $this->render('driv-license',[
            'config'=>$config,
            'buttons'=>$buttons
        ]);
    }

    /**
     * 行驶证年审预警列表
     */
    public function actionDlGetList()
    {
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $query = CarDrivingLicense::find()
                ->select([
                    '{{%car_driving_license}}.*',
                    '{{%car}}.`plate_number`',
                    '{{%car}}.`car_status`',
                	'car_brand_name'=>'{{%car_brand}}.`name`',
                	'car_model_name'=>'{{%config_item}}.`text`',
                    '{{%admin}}.`username`',
                ])
                ->joinWith('car',false,'LEFT JOIN')
                ->joinWith('admin',false,'LEFT JOIN')
                ->leftJoin('{{%car_brand}}', '{{%car}}.`brand_id` = {{%car_brand}}.`id`')
                ->leftJoin('{{%config_item}}', '{{%car}}.`car_model` = {{%config_item}}.`value`');
//         exit($query->createCommand()->getRawSql());
        $query->andFilterWhere([
        		'=',
        		Car::tableName().'.`is_del`',
        		0
        		]);
        //查询条件
        $query->andFilterWhere([
            'like',
            Car::tableName().'.`plate_number`',
            yii::$app->request->get('plate_number')
        ]);
        $scanType = yii::$app->request->get('scan_type');
        if($scanType == 'thirty'){
            $query->andFilterWhere([
                '<=',
                '{{%car_driving_license}}.`next_valid_date`',
                time()+2592000
            ]);
        }
        //查品牌，查父品牌时也会查出子品牌
        if(yii::$app->request->get('brand_id')){
        	$brand_id = yii::$app->request->get('brand_id');
        	$query->andFilterWhere([
        			'or',
        			['{{%car_brand}}.`id`'=>$brand_id],
        			['{{%car_brand}}.`pid`'=>$brand_id]
        			]);
        }
        //检测是否要根据当前登录人员所属运营公司来显示列表数据-20160325
        $isLimitedArr = Car::isLimitedToShowByAdminOperatingCompany();
        if(is_array($isLimitedArr) && isset($isLimitedArr['status']) && $isLimitedArr['status']){
            $query->andWhere("{{%car}}.`operating_company_id` in ({$isLimitedArr['adminInfo_operatingCompanyId']})");
        }
        //查询条件结束
        //排序开始
        $sortColumn = yii::$app->request->get('sort');
        $sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
        $orderBy = '';
        if($sortColumn){
            switch ($sortColumn) {
                case 'plate_number':
                    $orderBy = '{{%car}}.`plate_number` ';
                    break;
                case 'car_status':
                    $orderBy = '{{%car}}.`car_status` ';
                    break;
                case 'username':
                    $orderBy = '{{%admin}}.`username` ';
                    break;
                default:
                    $orderBy = '{{%car_driving_license}}.`'.$sortColumn.'` ';
                    break;
            }
        }else{
           $orderBy = '{{%car_driving_license}}.`id` ';
        }
        $orderBy .= $sortType;
        //排序结束
        $total = $query->count();
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)->limit($pages->limit)
                ->orderBy($orderBy)->asArray()->all();
        foreach ($data as $key=>$val)
        {
        	$date1 = date_create(date('Y-m-d',time()));
        	$date2 = date_create(date('Y-m-d',$val['valid_to_date']));
        	$diff = date_diff($date1,$date2);
        	
        	$countdown = $diff->format("%a");
        	//判断是否是正数
        	$data[$key]['next_valid_date'] = $diff->invert == 0 ? "<span style='color:red'>{$countdown}天</span>":"<span style='color:red'>过期</span>";
        }
        
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        echo json_encode($returnArr);
    }


    /**
     * 导出【行驶证年审预警】列表
     */
    public function actionExportDlList()
    {
    	set_time_limit(0);
        // 构建导出的excel表头
        $excHeaders = [
            [
                ['content'=>'车牌号','font-weight'=>true,'width'=>'15'],
                ['content'=>'车辆品牌','font-weight'=>true,'width'=>'15'],
                ['content'=>'车型名称','font-weight'=>true,'width'=>'15'],
                ['content'=>'车辆状态','font-weight'=>true,'width'=>'10'],
                ['content'=>'登记地址','font-weight'=>true,'width'=>'35'],
                ['content'=>'注册日期','font-weight'=>true,'width'=>'15'],
                ['content'=>'发证日期','font-weight'=>true,'width'=>'15'],
                ['content'=>'档案编号','font-weight'=>true,'width'=>'15'],
                ['content'=>'整备质量(kg)','font-weight'=>true,'width'=>'15'],
                ['content'=>'强制报废日期','font-weight'=>true,'width'=>'15'],
                ['content'=>'检验有效期','font-weight'=>true,'width'=>'10'],
                ['content'=>'倒计时','font-weight'=>true,'width'=>'10'],
                ['content'=>'上次操作时间','font-weight'=>true,'width'=>'20'],
                ['content'=>'上次操作人员','font-weight'=>true,'width'=>'15']
            ]
        ];

        // 要查的字段，与导出的excel表头对应
        $selectArr = [
            '{{%car}}.plate_number',
            'car_brand_name'=>'{{%car_brand}}.`name`',
            'car_model_name'=>'{{%config_item}}.`text`',
            '{{%car}}.car_status',
            '{{%car_driving_license}}.addr',
            '{{%car_driving_license}}.register_date',
            '{{%car_driving_license}}.issue_date',
            '{{%car_driving_license}}.archives_number',
            '{{%car_driving_license}}.total_mass',
            '{{%car_driving_license}}.force_scrap_date',
            '{{%car_driving_license}}.valid_to_date',
            'remaining_time'=>'{{%car_driving_license}}.next_valid_date', //“倒计时”占位
            '{{%car_driving_license}}.add_datetime',
            '{{%admin}}.username'
        ];

        $query = CarDrivingLicense::find()
            ->select($selectArr)
            ->joinWith('car',false,'LEFT JOIN')
            ->joinWith('admin',false,'LEFT JOIN')
            ->leftJoin('{{%car_brand}}', '{{%car}}.`brand_id` = {{%car_brand}}.`id`')
            ->leftJoin('{{%config_item}}', '{{%car}}.`car_model` = {{%config_item}}.`value`');
        $query->andFilterWhere([
        		'=',
        		Car::tableName().'.`is_del`',
        		0
        		]);
        //查询条件
        $query->andFilterWhere([
            'like',
            Car::tableName().'.`plate_number`',
            yii::$app->request->get('plate_number')
        ]);
        $scanType = yii::$app->request->get('scan_type');
        if($scanType == 'thirty'){
            $query->andFilterWhere([
                '<=',
                '{{%car_driving_license}}.`next_valid_date`',
                time()+2592000
            ]);
        }
        //查品牌，查父品牌时也会查出子品牌
        if(yii::$app->request->get('brand_id')){
        	$brand_id = yii::$app->request->get('brand_id');
        	$query->andFilterWhere([
        			'or',
        			['{{%car_brand}}.`id`'=>$brand_id],
        			['{{%car_brand}}.`pid`'=>$brand_id]
        			]);
        }
        //检测是否要根据当前登录人员所属运营公司来显示列表数据-20160325
        $isLimitedArr = Car::isLimitedToShowByAdminOperatingCompany();
        if(is_array($isLimitedArr) && isset($isLimitedArr['status']) && $isLimitedArr['status']){
            $query->andWhere("{{%car}}.`operating_company_id` in ({$isLimitedArr['adminInfo_operatingCompanyId']})");
        }
        //查询条件结束
        //$data = $query->asArray()->all();
        //print_r($data);exit;

        $excel = new Excel();
        $excel->setHeader([
            'creator'=>'皓峰通讯',
            'lastModifiedBy'=>'hao feng tong xun',
            'title'=>'CarDrivingLicense',
            'subject'=>'CarDrivingLicense',
            'description'=>'CarDrivingLicense',
            'keywords'=>'CarDrivingLicense',
            'category'=>'CarDrivingLicense'
        ]);
        //---向excel添加表头-----------------
        foreach($excHeaders as $lineData){
            $excel->addLineToExcel($lineData);
        }
        //---向excel添加具体数据--------------
        $configItems = ['car_status','DL_REG_ADDR'];
        $configs = (new ConfigCategory())->getCategoryConfig($configItems,'value');
        //分批次查询数据
        $total = $query->count();
        $pageSize = 200;
        $pageNum = ceil($total/$pageSize);
        for($i=1; $i<=$pageNum; $i++){
            $offset = ($i-1) * $pageSize;
            $data = $query
                ->offset($offset)->limit($pageSize)
                ->orderBy('{{%car_driving_license}}.next_valid_date ASC')
                ->asArray()->all();
            foreach($data as $item){
                $item['archives_number'] = ' '.$item['archives_number'];
                $item['register_date'] = $item['register_date'] ? date('Y-m-d',$item['register_date']) : '';
                $item['issue_date'] = $item['issue_date'] ? date('Y-m-d',$item['issue_date']) : '';
                $item['force_scrap_date'] = $item['force_scrap_date'] ? date('Y-m-d',$item['force_scrap_date']) : '';
                if($item['remaining_time']){
                    if($item['remaining_time'] < time()){
                        $item['remaining_time'] = '已过期';
                    }else{
                        $diff = $item['remaining_time'] - strtotime(date('Y-m-d',time())); //年月日
                        $days = floor($diff/(3600*24)) + 1; //+1包含今日在内
                        $item['remaining_time'] = $days.'天';
                    }
                }else{
                    $item['remaining_time'] = '';
                }
                $item['add_datetime'] = $item['add_datetime'] ? date('Y-m-d H:i:s',$item['add_datetime']) : '';
                $item['valid_to_date'] = $item['valid_to_date'] ? date('Y-m-d H:i:s',$item['valid_to_date']) : '';
                
                $item['car_brand_name'] = $item['car_brand_name'];
                $item['car_model_name'] = $item['car_model_name'];
                $lineData = [];
                // 各combox配置项以txt代替val
                foreach($configItems as $conf) {
                    if(isset($item[$conf]) && $item[$conf]) {
                        $item[$conf] = @$configs[$conf][$item[$conf]]['text'];
                    }elseif($conf == 'DL_REG_ADDR'){
                        $realKey = 'addr';
                        if(isset($item[$realKey]) && $item[$realKey]) {
                            $item[$realKey] = $configs[$conf][$item[$realKey]]['text'];
                        }
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
        }

        $objPHPExcel = $excel->getPHPExcel();
        header("Content-type: application/octet-stream");
        header("Accept-Ranges: bytes");
        //header("Accept-Length:".$fileSize);
        header("Content-Disposition: attachment; filename=".iconv('utf-8','gbk','行驶证年审预警.xls'));
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }


}