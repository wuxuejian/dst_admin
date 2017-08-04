<?php
namespace backend\modules\parts\controllers;
use backend\controllers\BaseController;
use backend\models\PurchaseOrderMain;
use backend\models\PurchaseExpress;

use backend\models\Car;
use backend\models\ConfigCategory;
use backend\models\CarFault;
use backend\models\CarDrivingLicense;
use backend\models\CarRoadTransportCertificate;
use backend\models\CarSecondMaintenance;
use backend\models\CarInsuranceCompulsory;
use backend\models\CarInsuranceBusiness;
use backend\models\CarBrand;
use backend\models\Owner;
use backend\models\OperatingCompany;
use backend\models\Battery;
use backend\models\Motor;
use backend\models\MotorMonitor;
use backend\classes\UserLog;
use common\models\Excel;
use common\models\File;
use yii;
use yii\base\Object;
use yii\data\Pagination;
use backend\models\Admin;
use common\classes\Category;
use backend\models\CarType;

class PartsStockController extends BaseController
{
    public function actionIndex()
    { $buttons = $this->getCurrentActionBtn();
		$configItems = ['car_type','part_type','part_kind'];
        $config = (new ConfigCategory)->getCategoryConfig($configItems,'value');
        //车辆类型
        $searchFormOptions = [];
        if($config['car_type'])
        {
        	$searchFormOptions['car_type'] = [];
        	$searchFormOptions['car_type'][] = ['value'=>'','text'=>'不限'];
        	foreach($config['car_type'] as $val){
        		$searchFormOptions['car_type'][] = ['value'=>$val['value'],'text'=>$val['text']];
        	}
        }
       //配件类别
        $query = (new \yii\db\Query())
            ->select('id,parts_name,parents_id')
            ->from('cs_parts_kind')
            ->where(['is_del'=>'0','parents_id'=>'0']);
        $parts_type = $query->all();
        $searchFormOptions['parts_type'][] = ['id'=>'0','name'=>'不限','selected'=>'selected'];
        foreach ($parts_type as $k=>$v){

            $searchFormOptions['parts_type'][] = ['id'=>$v['id'],'name'=>$v['parts_name']];
        }
        
       //大区
        $daqu_query = (new \yii\db\Query())
            ->select('id,region_name')
            ->from('cs_op_region')
            ->where(['is_del'=>0]);
        $data_daqu = $daqu_query->all();
        $searchFormOptions['region'][] = ['id'=>'0','name'=>'不限','selected'=>'selected'];
        foreach ($data_daqu as $k=>$v){

            $searchFormOptions['region'][] = ['id'=>$v['id'],'name'=>$v['region_name']];
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
        //仓储地点
        $warehouse_address=(new \yii\db\Query())->from('oa_extract_car_site')->where('is_del=0')->all();
        if($warehouse_address){
        	$searchFormOptions['warehouse_address'][] = ['value'=>'0','text'=>'不限','selected'=>'selected'];
        	foreach($warehouse_address as $val){
        		
        		$searchFormOptions['warehouse_address'][] = ['value'=>$val['id'],'text'=>$val['name']];
        	}
        }
        //配件名称
        $parts_name=Yii::$app->db->createCommand("select distinct parts_name from cs_parts_info")->queryAll();
        if($parts_name){
            $searchFormOptions['parts_name'][] = ['value'=>'0','text'=>'不限','selected'=>'selected'];
            foreach($parts_name as $val){
                
                $searchFormOptions['parts_name'][] = ['value'=>$val['parts_name'],'text'=>$val['parts_name']];
            }
        }

	
		return $this->render('index',[
            'buttons'=>$buttons,
            'config'=>$config,
            'searchFormOptions'=>$searchFormOptions,
        ]);
	}
	public function actionGetList()
	{
		 $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
         $query = (new \yii\db\Query())
            ->select([
                'a.*',
                'b.*',
                'c.name as company_name',
                'd.name as warehouse_address',
                'f.name as brand_name',
                'i.parts_name as parents_name',
                'j.parts_name as son_name',
                'k.region_name'

            ])->from('cs_parts_storage a')
    			->leftJoin('cs_parts_info b', 'b.parts_id=a.parts_info_id')
                ->leftJoin('cs_operating_company c', 'a.operating_company_id=c.id')
    			->leftJoin('oa_extract_car_site d', 'a.storage_id=d.id')
                //->leftJoin('cs_parts_info e', 'e.parts_id=b.parts_info_id')
                ->leftJoin('cs_car_brand f', 'b.car_brand=f.id')
               ->leftJoin('cs_parts_kind i', 'i.id = b.parts_type')
                ->leftJoin('cs_parts_kind j', 'j.id = b.parts_kind')
                ->leftJoin('cs_op_region k', 'k.id = a.region')
                ->where(['a.is_del'=>0])
				;
           //查询条件开始
            if(yii::$app->request->get('region')&& yii::$app->request->get('region')!='不限'){
                $query->andFilterWhere(['=','a.`region`',yii::$app->request->get('region')]);
            }
            if(yii::$app->request->get('operating_company_id')){
                $query->andFilterWhere(['=','a.`operating_company_id`',yii::$app->request->get('operating_company_id')]);
            }
            if(yii::$app->request->get('warehouse_address')&& yii::$app->request->get('warehouse_address')!='不限'){
                $query->andFilterWhere(['=','a.`storage_id`',yii::$app->request->get('warehouse_address')]);
            }
             if(yii::$app->request->get('brand_id')){
                $query->andFilterWhere(['=','b.`car_brand`',yii::$app->request->get('brand_id')]);
            }
            if(yii::$app->request->get('vender_code')){
                $query->andFilterWhere(['=','b.`vender_code`',yii::$app->request->get('vender_code')]);
            }
            if(yii::$app->request->get('dst_code')){
                $query->andFilterWhere(['=','b.`dst_code`',yii::$app->request->get('dst_code')]);
            }
            if(yii::$app->request->get('parts_name') && yii::$app->request->get('parts_name')!=0 ){
                $query->andFilterWhere(['=','b.`parts_name`',yii::$app->request->get('parts_name')]);
            }
            if(yii::$app->request->get('parts_type') ){
                $query->andFilterWhere(['=','b.`parts_type`',yii::$app->request->get('parts_type')]);
            }
            if(yii::$app->request->get('parts_kind') ){
                $query->andFilterWhere(['=','b.`parts_kind`',yii::$app->request->get('parts_kind')]);
            }
            if(yii::$app->request->get('parts_brand') ){
                $query->andFilterWhere(['like','b.`parts_brand`',yii::$app->request->get('parts_brand')]);
            }
           
            
            //查询条件结束
            //排序开始
           /* $sortColumn = yii::$app->request->get('sort');
            $sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
            $orderBy = '';
            if($sortColumn){
                switch($sortColumn){
                    case 'operating_company':
                        $orderBy = "{{%operating_company}}.name ";
                        break;
                    default:
                        $orderBy = "{{%customer_personal}}.$sortColumn ";
                }
            }else{
                $orderBy = '{{%customer_personal}}.`id` ';
            }
            $orderBy .= $sortType;*/
            //排序结束
            $total = $query->count();
            $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
            $data = $query
                    ->offset($pages->offset)->limit($pages->limit)
                    ->all();
                     foreach($data as $k=> $v){
                        if($v['region']==1){
                            $data[$k]['region']='华南';
                        }elseif($v['region']==2){
                            $data[$k]['region']='华北';
                        }elseif($v['region']==3){
                            $data[$k]['region']='华中';
                        }elseif($v['region']==4){
                            $data[$k]['region']='华东';
                        }elseif($v['region']==5){
                            $data[$k]['region']='西南';
                        }
                         $data[$k]['under_in_warehouse_time']=date('Y-m-d ',$v['under_in_warehouse_time']);
                          $data[$k]['on_registrant_date']=date('Y-m-d ',$v['on_registrant_date']);
                 }
            $returnArr = [];
            $returnArr['rows'] = $data;
            $returnArr['total'] = $total;
            echo json_encode($returnArr);

	}

    //运营公司和仓储地点联动
    public function actionGetAddress()
    {
        $operating_company_id = intval(yii::$app->request->get('operating_company_id')); 
        $connection = yii::$app->db;
        $data = $connection->createCommand(
                "select id,name from oa_extract_car_site where operating_company_id={$operating_company_id}"
        )->queryAll();
        return json_encode($data);
    }
}