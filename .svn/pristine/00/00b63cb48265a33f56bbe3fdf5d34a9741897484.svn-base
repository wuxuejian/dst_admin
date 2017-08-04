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
use backend\models\PartsOut;

class PartsOutstockController extends BaseController
{
    public function actionIndex()
    {
       $buttons = $this->getCurrentActionBtn();
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

     /**
     * 获取出库记录列表
     */
    public function actionGetList()
    {
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $query = (new \yii\db\Query())
            ->select([
                'a.*',
                'b.*',
                'c.plate_number as plate_number',
                'd.*',
                'e.name as company_name',
                'f.name as brand_name',
                'g.name as warehouse_address',
                //'h.region',
                //'i.text as parts_type',
                //'j.text as parts_kind',
                'h.region_name',
                'k.parts_name as parents_name',
                'm.parts_name as son_name'
            ])->from('cs_parts_out a')
    			->leftJoin('cs_parts_in b', 'a.instock_id = b.insert_id')
    			->leftJoin('cs_car c', 'a.car_id = c.id')
    			->leftJoin('cs_parts_info d', 'd.parts_id = b.info_parts_id')
    			->leftJoin('cs_car_brand f', 'd.car_brand = f.id')
    			->leftJoin('oa_extract_car_site g', 'b.warehouse_address = g.id')
                //->leftJoin('cs_parts_storage h', 'h.storage_id = b.warehouse_address')
                ->leftJoin('cs_operating_company e', 'b.operating_company_id = e.id')
                //->leftJoin('cs_config_item i', 'i.id = d.parts_type')
                //->leftJoin('cs_config_item j', 'j.id = d.parts_kind')
                ->leftJoin('cs_op_region h', 'h.id = b.region')
                ->leftJoin('cs_parts_kind k', 'd.parts_type = k.id')
                ->leftJoin('cs_parts_kind m', 'd.parts_kind = m.id')
                //->distinct()
    			->where(['a.is_del'=>0]);
        //查询条件开始
        if(yii::$app->request->get('region')&& yii::$app->request->get('region')!=0){
        	$query->andFilterWhere(['=','b.`region`',yii::$app->request->get('region')]);
        }
        if(yii::$app->request->get('operating_company_id')){
        	$query->andFilterWhere(['=','b.`operating_company_id`',yii::$app->request->get('operating_company_id')]);
        }
        if(yii::$app->request->get('warehouse_address')){
            $query->andFilterWhere(['=','b.`warehouse_address`',yii::$app->request->get('warehouse_address')]);
        }
         if(yii::$app->request->get('brand_id')){
            $query->andFilterWhere(['=','d.`car_brand`',yii::$app->request->get('brand_id')]);
        }
        if(yii::$app->request->get('vender_code')){
            $query->andFilterWhere(['=','d.`vender_code`',yii::$app->request->get('vender_code')]);
        }
        if(yii::$app->request->get('dst_code')){
            $query->andFilterWhere(['=','d.`dst_code`',yii::$app->request->get('dst_code')]);
        }
        if(yii::$app->request->get('start_out_time')){
            $start_out_time=strtotime( yii::$app->request->get('start_out_time'));
            $query->andFilterWhere([
                    '>=',
                    'a.`out_time`',
                    $start_out_time
                    ]);
        }
        if(yii::$app->request->get('end_out_time')){
            $end_out_time=strtotime( yii::$app->request->get('end_out_time'));
            $query->andFilterWhere([
                    '<=',
                    'a.`out_time`',
                    $end_out_time
                    ]);
        }
        if(yii::$app->request->get('use_person')){
            $query->andFilterWhere(['like','a.`use_person`',yii::$app->request->get('use_person')]);
        }
         if(yii::$app->request->get('parts_name') && yii::$app->request->get('parts_name')!='不限' ){
            $query->andFilterWhere(['=','d.`parts_name`',yii::$app->request->get('parts_name')]);
        }
        if(yii::$app->request->get('parts_type') ){
            $query->andFilterWhere(['=','d.`parts_type`',yii::$app->request->get('parts_type')]);
        }
        if(yii::$app->request->get('parts_kind') ){
            $query->andFilterWhere(['=','d.`parts_kind`',yii::$app->request->get('parts_kind')]);
        }
        if(yii::$app->request->get('parts_brand') ){
            $query->andFilterWhere(['like','d.`parts_brand`',yii::$app->request->get('parts_brand')]);
        }

        //查询条件结束
        $total = $query->count();
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query
                ->offset($pages->offset)->limit($pages->limit)
                ->orderBy(['out_time'=>SORT_DESC])->all();
                foreach($data as $k=>$v){
                    $data[$k]['out_time']=date('Y-m-d ',$v['out_time']);
                }
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        echo json_encode($returnArr);
    }
    public function actionOutstock()
    { 

    	$buttons = $this->getCurrentActionBtn();
        $configItems = ['car_type','part_type','part_kind'];
        $config = (new ConfigCategory)->getCategoryConfig($configItems,'value');
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
        //配件名称
        $parts_name=Yii::$app->db->createCommand("select distinct parts_name from cs_parts_info")->queryAll();
        if($parts_name){
            $searchFormOptions['parts_name'][] = ['value'=>'0','text'=>'不限','selected'=>'selected'];
            foreach($parts_name as $val){
                
                $searchFormOptions['parts_name'][] = ['value'=>$val['parts_name'],'text'=>$val['parts_name']];
            }
        }
    	return $this->render('outstock',['buttons'=>$buttons,'config'=>$config,
            'searchFormOptions'=>$searchFormOptions,]);
    }


    /**
     * 删除记录
     */
  /*  public function actionDel()
    {
        $id = yii::$app->request->get('id') or die('param id is required');
        //检查当前登录用户和要操作的企业客户的所属运营公司是否匹配-20160328
        if(PartsOut::updateAll(['is_del'=>1],['out_id'=>$id])){
            $returnArr['status'] = true;
            $returnArr['info'] = '记录删除成功！';
        }else{
            $returnArr['status'] = false;
            $returnArr['info'] = '记录删除失败！';
        }
        echo json_encode($returnArr);
    }*/
    //显示配件信息	
    public function actionGetOutstockList()
    {
    	$pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $query = (new \yii\db\Query())
            ->select([
                'a.*',
                'd.*',
                'e.name as company_name',
                'f.name as brand_name',
                'g.name as warehouse_address',
                'h.region',
                'i.parts_name as parents_name',
                'j.parts_name as son_name',
                'k.region_name'
            ])->from('cs_parts_in a')
    			->leftJoin('cs_parts_info d', 'd.parts_id = a.info_parts_id')
    			->leftJoin('cs_car_brand f', 'd.car_brand = f.id')
    			->leftJoin('oa_extract_car_site g', 'a.warehouse_address = g.id')
                ->leftJoin('cs_parts_storage h', 'h.storage_id = a.warehouse_address')
                ->leftJoin('cs_operating_company e', 'h.operating_company_id = e.id')
                ->leftJoin('cs_parts_kind i', 'i.id = d.parts_type')
                ->leftJoin('cs_parts_kind j', 'j.id = d.parts_kind')
                ->leftJoin('cs_op_region k', 'k.id = a.region')
    			 ->where(['a.is_del'=>0])
    			 //->andWhere(['a.storage_quantity>0'])
    			 ->andFilterWhere(['>','a.storage_quantity',0]);

			if(yii::$app->request->get('brand_id')){
            $query->andFilterWhere(['=','d.`car_brand`',yii::$app->request->get('brand_id')]);
            }
             if(yii::$app->request->get('parts_name') && yii::$app->request->get('parts_name')!='不限' ){
            $query->andFilterWhere(['=','d.`parts_name`',yii::$app->request->get('parts_name')]);
            }
            if(yii::$app->request->get('parts_type') ){
                $query->andFilterWhere(['=','d.`parts_type`',yii::$app->request->get('parts_type')]);
            }
            if(yii::$app->request->get('parts_kind') ){
                $query->andFilterWhere(['=','d.`parts_kind`',yii::$app->request->get('parts_kind')]);
            }
            if(yii::$app->request->get('parts_brand') ){
                $query->andFilterWhere(['like','d.`parts_brand`',yii::$app->request->get('parts_brand')]);
            }
            if(yii::$app->request->get('vender_code')){
            $query->andFilterWhere(['=','d.`vender_code`',yii::$app->request->get('vender_code')]);
            }
            if(yii::$app->request->get('dst_code')){
                $query->andFilterWhere(['=','d.`dst_code`',yii::$app->request->get('dst_code')]);
            }

			$total = $query->count();
        	$pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
            $data = $query
            ->offset($pages->offset)->limit($pages->limit)
            ->orderBy(['under_in_warehouse_time'=>SORT_ASC])->all();

            foreach($data as $k=> $v){
                         $data[$k]['under_in_warehouse_time']=date('Y-m-d ',$v['under_in_warehouse_time']);
                 }
                $returnArr = [];
                $returnArr['rows'] = $data;
                $returnArr['total'] = $total;
                echo json_encode($returnArr);
    }
    //添加要出库的配件
    public function actionAddStock()
    {
    	 $id = yii::$app->request->get('id') or die('param id is required');
    	 $data=Yii::$app->db->createCommand("SELECT a.*,d.*,e.name as 
    	 		company_name,f.name as brand_name,
    	 		g.name as warehouse_address_name,h.region,i.text as parts_type,j.text as parts_kind
    	 							FROM cs_parts_in a
    	 									LEFT JOIN cs_parts_info d
    	 									ON d.parts_id = a.info_parts_id
    	 									LEFT JOIN cs_car_brand f
    	 									ON d.car_brand = f.id
    	 									LEFT JOIN oa_extract_car_site g
    	 									ON a.warehouse_address = g.id
                                            LEFT JOIN cs_parts_storage h
                                            ON h.storage_id = a.warehouse_address
                                            LEFT JOIN cs_operating_company e
                                            ON h.operating_company_id = e.id
                                            LEFT JOIN cs_config_item i
                                            ON i.id=d.parts_type
                                            LEFT JOIN cs_config_item j
                                            ON j.id=d.parts_kind
    	 									WHERE a.insert_id=".$id
    	 	     )->queryOne();
    	           if($data['region']==1){
             			$data['region']='华南';
             		}elseif($data['region']==2){
             			$data['region']='华北';
             		}elseif($data['region']==3){
             			$data['region']='华中';
             		}elseif($data['region']==4){
             			$data['region']='华东';
             		}elseif($data['region']==5){
             			$data['region']='华西';
             		}
                    $data['under_in_warehouse_time']=date('Y-m-d ',$data['under_in_warehouse_time']);
    	           return $this->render('addstock',['data'=>$data]);
    }
    //出库保存
    public function actionSaveStock()
    {
    	
    	$data=yii::$app->request->post();
    	if($data['car_id']==''){
			$returnArr['status'] = false;
            $returnArr['info'] = '车牌号不能为空!';
            return json_encode($returnArr);
    	}
    	if($data['out_reason']==''){
			$returnArr['status'] = false;
            $returnArr['info'] = '出库原因不能为空!';
            return json_encode($returnArr);
    	}
    	if($data['use_person']==''){
			$returnArr['status'] = false;
            $returnArr['info'] = '用车人不能为空!';
            return json_encode($returnArr);
    	}
    	if($data['out_time']==''){
			$returnArr['status'] = false;
            $returnArr['info'] = '出库时间不能为空!';
            return json_encode($returnArr);
    	}
    	if($data['out_number']=='' || $data['out_number']==0){
			$returnArr['status'] = false;
            $returnArr['info'] = '数量不能为空!';
            return json_encode($returnArr);
    	}
        if(!preg_match("/^\d*$/",$data['out_number'])){
            $returnArr['status'] = false;
            $returnArr['info'] = '数量只能为数字!';
            return json_encode($returnArr);
        }
    	if($data['out_number']>$data['storage_quantity']){
    		$returnArr['status'] = false;
            $returnArr['info'] = '出库数量不能大于库存!';
            return json_encode($returnArr);
    	}
        $data['out_time']=strtotime( $data['out_time']);
    	$result1=Yii::$app->db->createCommand()->insert('cs_parts_out', [
                        'instock_id' => $data['insert_id'],
                        'car_id' => $data['car_id'],
                        'out_reason'=>$data['out_reason'],
                        'use_person'=>$data['use_person'],
                        'out_time'=>$data['out_time'],
                        'out_number'=>$data['out_number']
                        ])->execute();
    	$number_now=$data['storage_quantity']-$data['out_number'];
    	$result2=Yii::$app->db->createCommand()->update('cs_parts_in',['storage_quantity'=>$number_now],"insert_id=".$data['insert_id'])->execute();
    	$result3=Yii::$app->db->createCommand("update cs_parts_storage set storage_quantity=storage_quantity-".$data['out_number']." where parts_info_id=".$data['info_parts_id']." and storage_id=".$data['warehouse_address'])->execute();
    	if($result1 && $result2 && $result3){
    		$returnArr['status'] = true;
            $returnArr['info'] = '出库成功!';
    	}
    	return json_encode($returnArr);
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