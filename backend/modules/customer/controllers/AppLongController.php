<?php
/**
 * App长租订单申请管理控制器
 * time: 2014/10/14 11:35
 * @author wangmin
 */
namespace backend\modules\customer\controllers;
use backend\controllers\BaseController;
use backend\models\CustomerCompany;
use backend\models\Vip;
use common\models\Excel;
use yii;
use yii\data\Pagination;
use backend\models\ConfigCategory;

class AppLongController extends BaseController
{
    public function actionIndex()
    {
        $buttons = $this->getCurrentActionBtn();
        return $this->render('index',[
            'buttons'=>$buttons
        ]);
    }

    /**
     * 获取申请列表
     */
    public function actionGetList()
    {
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;  		
		$query = (new \yii\db\Query())->select([
    			'app_long_rent_apply.*',   			
    			'zc_region.region_name',   			
    			'a.name as manager_name',   			
    			'b.name as sale_name',   			
    			'cs_vip.category'   			
    			])->from('app_long_rent_apply')  
				->leftJoin('cs_vip','cs_vip.mobile = app_long_rent_apply.apply_customer')        		
				->leftJoin('zc_region','zc_region.region_id = app_long_rent_apply.city_id and zc_region.region_type=2')        		
				->leftJoin('cs_admin as a','a.id = app_long_rent_apply.manager_id')        		
				->leftJoin('cs_admin as b','b.id = app_long_rent_apply.sale_id')        		
    			->where(['app_long_rent_apply.is_del'=>0]);           
        //查询条件开始
        $query->andFilterWhere([
            'like',
            'apply_customer',
            yii::$app->request->get('apply_customer')
        ]);
        $query->andFilterWhere([
            '=',
            'cs_vip.category',
            yii::$app->request->get('category')
        ]); 
		$query->andFilterWhere([
            'like',
            'app_long_rent_apply.contact_name',
            yii::$app->request->get('contact_name')
        ]);
		$query->andFilterWhere([
            'like',
            'app_long_rent_apply.contact_mobile',
            yii::$app->request->get('contact_mobile')
        ]);
        //检测是否要根据当前登录人员所属运营公司来显示列表数据-20160328
        // $isLimitedArr = CustomerCompany::isLimitedToShowByAdminOperatingCompany();
        // if(is_array($isLimitedArr) && isset($isLimitedArr['status']) && $isLimitedArr['status']){
            // $query->andWhere("{{%customer_company}}.`operating_company_id` in ({$isLimitedArr['adminInfo_operatingCompanyId']})");
        // }
        //查询条件结束
        //排序开始
        // $sortColumn = yii::$app->request->get('sort');
        $sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
        $orderBy = '';
        // if($sortColumn){
            // switch($sortColumn){
                // case 'operating_company':
                    // $orderBy = "{{%operating_company}}.name ";
                    // break;
                // default:
                    // $orderBy = "{{%customer_company}}.$sortColumn ";
            // }
        // }else{
           $orderBy = 'app_long_rent_apply.`apply_id` ';
        // }
        $orderBy .= $sortType;
        //排序结束
        $total = $query->count();
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)->limit($pages->limit)
                ->orderBy($orderBy)
				// ->asArray()
				->all();
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        echo json_encode($returnArr);
    }

    /**
     * 添加企业客户
     */
    public function actionAdd()
    {
        //data submit start
        if(yii::$app->request->isPost){
            $model = new CustomerCompany;
            $model->load(yii::$app->request->post(),'');
            $model->number = date('YmdHis').str_pad(mt_rand(0,100),3,0);
            $model->password = md5(substr(md5(yii::$app->request->post('password')),0,30));
            $model->operating_company_id = $_SESSION['backend']['adminInfo']['operating_company_id'];
            $returnArr = [];
            $returnArr['status'] = true;
            $returnArr['info'] = '';
            
            if($model->validate()){
            	$company_name = trim(yii::$app->request->post('company_name'));
            	if(CustomerCompany::find()
		            ->select('count(*)')
		            ->where(['company_name'=>$company_name])
		            ->count()>0){
            		$returnArr['status'] = false;
            		$returnArr['info'] = '企业客户已存在';
            		exit(json_encode($returnArr));
            	}
            	 
            	 
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '企业客户添加成功！';
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '数据保存失败！';
                }
            }else{
                $returnArr['status'] = false;
                $errors = $model->getErrors();
                if($errors){
                    foreach($errors as $val){
                        $returnArr['info'] .= $val[0];
                    }
                }else{
                    $returnArr['info'] = '未知错误！';
                }
            }
            echo json_encode($returnArr);
            return null;
        }
        //data submit end
        return $this->render('add');
    }
	
	//查看详情
    public function actionScan(){
    	$id = yii::$app->request->get('id') or die('param id is required');
    	
    	$connection = yii::$app->db;
    	$data = $connection->createCommand('
		select 
			apply_no, 
			order_time, 
			zc_region.region_name as city, 
			es_take_car_time, 
			company_name, 
			contact_name, 
			contact_mobile, 
			contact_email, 
			car_models, 
			a.name as manager_name, 
			b.name as sale_name, 
			call_back_status, 
			call_back_man_note, 
			call_back_sale_note 
			
		from app_long_rent_apply 
		left join zc_region on zc_region.region_id=app_long_rent_apply.city_id
		left join cs_admin as a on a.id=app_long_rent_apply.manager_id
		left join cs_admin as b on b.id=app_long_rent_apply.sale_id
		
		where apply_id='.$id
		
		)->queryOne();
		if ($data) {
			$data['car_models'] = json_decode($data['car_models']);
			if ($data['car_models']){
				foreach ($data['car_models'] as $key => $value) {
					$car_type_id = $value->car_type_id;
					if ($car_type_id) {
						//查询车型模板数据
						$car_model_category_id = 62;		
						$car_type = $connection->createCommand("
						select 
							cs_car_brand.name as brand_name,
							i.text as car_model_name
						from cs_car_type 
						left join cs_car_brand on cs_car_brand.id=cs_car_type.brand_id
						left join cs_config_item as i on i.`value`=cs_car_type.car_model and i.belongs_id=$car_model_category_id				
						where cs_car_type.id=".$car_type_id
						)->queryOne();
						if ($car_type) {
							$data['car_models'][$key]->car_type_id = $car_type['brand_name']." ".$car_type['car_model_name'];							
						}
					}
				}
			}
		}
		// var_dump($data);exit;
		
		
		
    	return $this->render('scan',[
    			'data'=>$data
    			]);
    }
    /**
     * 需求指派
     */
    public function actionSetSales()
    {
        //data submit start
        if(yii::$app->request->isPost){
            $id = yii::$app->request->post('id') or die('param id is required');
            // $model = CustomerCompany::findOne(['id'=>$id]);
            // $model or die('record not found');
            //检查当前登录用户和要操作的企业客户的所属运营公司是否匹配-20160328
            // $checkArr = CustomerCompany::checkOperatingCompanyIsMatch($id);
            // if(is_array($checkArr) && isset($checkArr['status']) && !$checkArr['status']){
                // return json_encode(['status'=>false,'info'=>$checkArr['info']]);
            // }
           			
			$db = \Yii::$app->db;
			$result = $db->createCommand()->update('app_long_rent_apply',
					[
					'sale_id'		=> yii::$app->request->post('sale_id'),
					'sales_mobile'		=> yii::$app->request->post('sales_mobile')
					// 'update_time'  =>  $update_time,
				    ],'apply_id=:id',[':id'=>$id])->execute();
			if($result){
                $returnArr['status'] = true;
                $returnArr['info'] = '需求指派成功！';											
			}else{
                $returnArr['status'] = false;
                $returnArr['info'] = '需求指派失败！';				
			}
            echo json_encode($returnArr);
            return null;
        }
        //data submit end
        $id = yii::$app->request->get('id') or die('param id is required');
       
		$sales = (new \yii\db\Query())->select([
    			'cs_admin.*'		
    			])->from('cs_admin')  				
    			->where(['cs_admin.is_del'=>0])
				->all();
        $sales or die('record not found');
        return $this->render('set-sales',[
            'sales'=>$sales,
			'id'=>$id
        ]);
    }
/**
     * 回访登记
     */
    public function actionCallBackRegister()
    {
        //data submit start
        if(yii::$app->request->isPost){
            $id = yii::$app->request->post('id') or die('param id is required');
                       			
			$db = \Yii::$app->db;
			$call_back_man_note =  yii::$app->request->post('call_back_man_note');
			$call_back_sale_note =  yii::$app->request->post('call_back_sale_note');
			if (
				(isset($call_back_man_note) && $call_back_man_note != '') ||
				(isset($call_back_sale_note) && $call_back_sale_note != '')
				)
			{
				$call_back_status = 1;
			} else {
				$returnArr['status'] = false;
                $returnArr['info'] = '回访登记失败！请输入登记内容';		
				echo json_encode($returnArr);
				return null;
				//$call_back_status = 0;
			}
			
			$result = $db->createCommand()->update('app_long_rent_apply',
					[
					'call_back_man_note'		=> $call_back_man_note,
					'call_back_status'		=> $call_back_status,
					'call_back_sale_note'		=> $call_back_sale_note
					// 'update_time'  =>  $update_time,
				    ],'apply_id=:id',[':id'=>$id])->execute();
			if($result){
                $returnArr['status'] = true;
                $returnArr['info'] = '回访登记成功！';											
			}else{
                $returnArr['status'] = false;
                $returnArr['info'] = '回访登记失败！';				
			}
            echo json_encode($returnArr);
            return null;
        }
        //data submit end
        $id = yii::$app->request->get('id') or die('param id is required');
       
		// $sales = (new \yii\db\Query())->select([
    			// 'cs_admin.*'		
    			// ])->from('cs_admin')  				
    			// ->where(['cs_admin.is_del'=>0])
				// ->all();
        // $sales or die('record not found');
        return $this->render('call-back',[
            // 'sales'=>$sales,
			'id'=>$id
        ]);
    }


}