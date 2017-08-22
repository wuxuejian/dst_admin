<?php
/**
 * 保险记录控制器
 * time    2016/08/24 11:37
 * @author pengyl
 */
namespace backend\modules\car\controllers;
use backend\models\CarBrand;

use backend\models\CarInsuranceOther;

use backend\models\CarDrivingLicense;

use backend\models\CarInsuranceCompulsory;
use backend\models\Admin;
use backend\models\OperatingCompany;
use backend\models\ConfigItem;
use backend\models\Owner;
use backend\controllers\BaseController;
use backend\models\Car;
use backend\models\CarInsuranceBusiness;
use backend\models\ConfigCategory;
use common\models\Excel;
use common\models\File;
use yii;
use yii\data\Pagination;
class InsuranceLogController extends BaseController
{
    public function actionIndex()
    {
    	$buttons = $this->getCurrentActionBtn();
    	$config = (new ConfigCategory)->getCategoryConfig(['INSURANCE_COMPANY','car_model_name','car_status'],'value');
    	$insurerCompany = [
    	['value'=>'','text'=>'不限']
    	];
    	if($config['INSURANCE_COMPANY']){
    		foreach($config['INSURANCE_COMPANY'] as $val){
    			$insurerCompany[] = ['value'=>$val['value'],'text'=>$val['text']];
    		}
    	}
    	//查询表单select选项
    	$searchFormOptions = [];
    	if($config['car_model_name']){
    		$searchFormOptions['car_model_name'] = [];
    		$searchFormOptions['car_model_name'][] = ['value'=>'','text'=>'不限'];
    		foreach($config['car_model_name'] as $val){
    			$isexist = false;
    			foreach ($searchFormOptions['car_model_name'] as $obj){	//去重
    				if($obj['value'] == $val['text']){
    					$isexist = true;
    					break;
    				}
    			}
    			if(!$isexist){
    				$searchFormOptions['car_model_name'][] = ['value'=>$val['text'],'text'=>$val['text']];
    			}
    		}
    	}
    	
    	return $this->render('index',[
    			'buttons'=>$buttons,
    			'config'=>$config,
    			'insurerCompany'=>$insurerCompany,
    			'searchFormOptions'=>$searchFormOptions,
    			]);
    }
    
   
    /**
     * 获取列表
     */
    public function actionGetList()
    {
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        
        //交强险、商业险、其它险sql
        $db = new \yii\db\Query();
        $query = $db->select(
        		'a.id,
        		a.type,
                a.type _type,
        		a.car_id,
        		a.start_date,
        		a.end_date,
        		a.end_date _end_date,
        		a.car_id insurance_text,
        		a.insurer_company,
        		a.money_amount,
        		a.note,
        		a.add_datetime,
        		a.add_aid,
				a.number,
        		b.plate_number,
        		b.vehicle_dentification_number,
        		b.car_model,
        		c.username,
                b.car_status,
                b.operating_company_id,
                b.owner_id,
                d.number numberp,
                a.use_nature use_nature_a,
                d.use_nature use_nature_d,
                d.money_amount money_amount_p,
                e.car_model_name,

                '
        )
        ->from('cs_car_insurance_compulsory a')
		->leftJoin('{{%car}} b', 'a.car_id = b.id')
		->leftJoin('{{%admin}} c', 'a.add_aid = c.id')
        ->leftJoin('{{%car_type}} e', 'b.car_type_id = e.id')
        ->leftJoin('(select * from (select * from cs_car_insurance_compulsory_form where is_del=0 order by add_datetime desc limit 1) temp3) as d','d.insurance_id = a.id')
		->where('a.is_del=0');
        $query1 = (new \yii\db\Query())->select(
        		'a.id,
        		a.insurance_text type,
                a.type _type,
        		a.car_id,
        		a.start_date,
                a.end_date,
        		a.end_date _end_date,
        		a.insurance_text,
        		a.insurer_company,
        		a.money_amount,
        		a.note,
        		a.add_datetime,
        		a.add_aid,
				a.number,
        		b.plate_number,
        		b.vehicle_dentification_number,
        		b.car_model,
        		c.username,
                b.car_status,
                b.operating_company_id,
                b.owner_id,
                d.number numberp,
                a.use_nature use_nature_a,
                d.use_nature use_nature_d,
                d.money_amount money_amount_p,
                e.car_model_name,
                '
                //

        )
        ->from('cs_car_insurance_business a')
        ->leftJoin('{{%car}} b', 'a.car_id = b.id')
		->leftJoin('{{%admin}} c', 'a.add_aid = c.id')
        ->leftJoin('{{%car_type}} e', 'b.car_type_id = e.id')
        ->leftJoin('(select * from (select * from cs_car_insurance_business_form where is_del=0 order by add_datetime desc limit 1) temp3) as d','d.insurance_id = a.id')
        ->where('a.is_del=0');
        $query2 = (new \yii\db\Query())->select(
        		'a.id,
        		a.insurance_text type,
                a.type _type,
        		a.car_id,
        		a.start_date,
                a.end_date,
        		a.end_date _end_date,
        		a.insurance_text,
        		a.insurer_company,
        		a.money_amount,
        		a.note,
        		a.add_datetime,
        		a.add_aid,
				a.number,
        		b.plate_number,
        		b.vehicle_dentification_number,
        		b.car_model,
        		c.username,
                b.car_status,
                b.operating_company_id,
                b.owner_id,
                d.number numberp,
                a.use_nature use_nature_a,
                d.use_nature use_nature_d,
                d.money_amount money_amount_p,
                e.car_model_name,
                '
                //
        )
        ->from('cs_car_insurance_other a')
        ->leftJoin('{{%car}} b', 'a.car_id = b.id')
		->leftJoin('{{%admin}} c', 'a.add_aid = c.id')
        ->leftJoin('{{%car_type}} e', 'b.car_type_id = e.id')
        ->leftJoin('(select * from (select * from cs_car_insurance_business_form where is_del=0 order by add_datetime desc limit 1) temp3) as d','d.insurance_id = a.id')
        ->where('a.is_del=0');
        //搜索条件
        $car_no = yii::$app->request->get('plate_number');	//车牌号
//        $vehicle_dentification_number = yii::$app->request->get('vehicle_dentification_number');	//车架号
        $car_model = yii::$app->request->get('car_model');	//车型
        $start_date = yii::$app->request->get('start_date');	//保险开始日期
        $end_date = yii::$app->request->get('end_date');	//保险结束日期
        $insurer_company = yii::$app->request->get('insurer_company');	//保险公司
        $insurance_type = yii::$app->request->get('insurance_type');	//保险类型
        $start_money_amount = yii::$app->request->get('start_money_amount');	//起始保费
        $end_money_amount = yii::$app->request->get('end_money_amount');	//结束保费
        $number = yii::$app->request->get('number');	//结束保费
        if($car_no)
        {
        	$query->andFilterWhere([
        			'or',
        			['like','b.plate_number',$car_no],
					['like','b.vehicle_dentification_number',$car_no],
        			['like','b.engine_number',$car_no]
        		]);
        	$query1->andFilterWhere([
        			'or',
        			['like','b.plate_number',$car_no],
        			['like','b.vehicle_dentification_number',$car_no],
        			['like','b.engine_number',$car_no]
        			]);
        	$query2->andFilterWhere([
        			'or',
        			['like','b.plate_number',$car_no],
        			['like','b.vehicle_dentification_number',$car_no],
        			['like','b.engine_number',$car_no]
        			]);
        }
        $oper_user = yii::$app->request->get('oper_user');  //操作用户
        
        if($oper_user){
            $admin = Admin::find()->where(['username'=>$oper_user])->asArray()->one();
            $add_aid = @$admin['id'];
            if($add_aid){
                $query->andFilterWhere(['=','a.add_aid',$add_aid]);
                $query1->andFilterWhere(['=','a.add_aid',$add_aid]);
                $query2->andFilterWhere(['=','a.add_aid',$add_aid]);
            }
        }
		if($car_model){
			$car_model_query = ConfigItem::find()->select('value')
					->andWhere(['is_del'=>0,'belongs_id'=>62,'text'=>$car_model]);
			$car_models = $car_model_query->asArray()->all();
			$car_models_s = array();
			foreach($car_models as $item){
				array_push($car_models_s, $item['value']);
			}
			$query->andFilterWhere(['in','b.car_model',$car_models_s]);
			$query1->andFilterWhere(['in','b.car_model',$car_models_s]);
			$query2->andFilterWhere(['in','b.car_model',$car_models_s]);
		}
		if($start_date){
			$query->andFilterWhere(['>=','a.start_date',strtotime($start_date)]);
			$query1->andFilterWhere(['>=','a.start_date',strtotime($start_date)]);
			$query2->andFilterWhere(['>=','a.start_date',strtotime($start_date)]);
		}
		if($end_date){
			$query->andFilterWhere(['<=','a.end_date',strtotime($end_date)]);
			$query1->andFilterWhere(['<=','a.end_date',strtotime($end_date)]);
			$query2->andFilterWhere(['<=','a.end_date',strtotime($end_date)]);
		}
		if($insurer_company){
			$query->andFilterWhere(['=','a.insurer_company',$insurer_company]);
			$query1->andFilterWhere(['=','a.insurer_company',$insurer_company]);
			$query2->andFilterWhere(['=','a.insurer_company',$insurer_company]);
		}
		if($insurance_type){
			if($insurance_type == 1){	//交强险
				$query1->andFilterWhere(['=','a.car_id',0]);
				$query2->andFilterWhere(['=','a.car_id',0]);
			}else if($insurance_type == 2){	//商业险
				$query->andFilterWhere(['=','a.car_id',0]);
				$query2->andFilterWhere(['=','a.car_id',0]);
			}else{	//其它险
				$query->andFilterWhere(['=','a.car_id',0]);
				$query1->andFilterWhere(['=','a.car_id',0]);
			}
		}
		if($start_money_amount){
			$query->andFilterWhere(['>=','a.money_amount',$start_money_amount]);
			$query1->andFilterWhere(['>=','a.money_amount',$start_money_amount]);
			$query2->andFilterWhere(['>=','a.money_amount',$start_money_amount]);
		}
		if($end_money_amount){
			$query->andFilterWhere(['<=','a.money_amount',$end_money_amount]);
			$query1->andFilterWhere(['<=','a.money_amount',$end_money_amount]);
			$query2->andFilterWhere(['<=','a.money_amount',$end_money_amount]);
		}
		if($number){
			$query->andFilterWhere(['like','a.number',$number]);
			$query1->andFilterWhere(['like','a.number',$number]);
			$query2->andFilterWhere(['like','a.number',$number]);
		}
		
		$query3 = (new \yii\db\Query())->from(['data'=>$query->union($query1)->union($query2)]);
        ////查询条件结束
        $sortColumn = yii::$app->request->get('sort');
        $sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
        $orderBy = '';
        if($sortColumn){
            switch ($sortColumn) {
                case 'xxx':
                    $orderBy = '{{%car_insurance_compulsory}}.`id` ';
                    break;
                default:
                    $orderBy = 'data.`'.$sortColumn.'` ';
                    break;
            }
        }else{
            $orderBy = 'data.`add_datetime` ';
        }
        $orderBy .= $sortType;
        $total = $query3->count();
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $query3 = $query3->offset($pages->offset)->limit($pages->limit)->orderBy($orderBy);
//         echo $query3->createCommand()->getRawSql();exit;
        $data = $query3->all();



        //车辆运营公司
        $oCompany = OperatingCompany::getOperatingCompany();  
        
        //加载归属客户    
        $connection = yii::$app->db;
        $configItems = ['car_status'];
        $config = (new ConfigCategory)->getCategoryConfig($configItems,'value');
        foreach ($data as $index=>$row){
            
            if(isset($oCompany[$row['operating_company_id']]) && $oCompany[$row['operating_company_id']]){
                $data[$index]['operating_company_id'] = $oCompany[$row['operating_company_id']]['name'];
            } 
            
            if (isset($row['owner_id'])) {
                $query_owner = Owner::find()->select(['owner_name'=>'name']);
                $query_owner->andFilterWhere(['`id`'=>$row['owner_id']]);
                $owner = $query_owner->asArray()->one();
                if($owner){
                    $data[$index]['owner_name'] = $owner['owner_name'];
                }
            }           
            //var_dump($row);
            $query = $connection->createCommand(
                "select cCustomer_id,pCustomer_id,cs_customer_company.company_name,cs_customer_personal.id_name
                 from cs_car_let_record
                left join cs_customer_company on cs_car_let_record.cCustomer_id=cs_customer_company.id 
                left join cs_customer_personal on cs_car_let_record.pCustomer_id=cs_customer_personal.id
                 where (back_time>".time()." or back_time=0) and car_id=".$row['id']
            );
            $customer = $query->queryOne();
            if($customer){
                if($customer['company_name']){
                    $data[$index]['customer_name'] = $customer['company_name'];
                }else if($customer['id_name']){
                    $data[$index]['customer_name'] = $customer['id_name'];
                }
            }else {
                $data[$index]['customer_name'] = @$config['car_status'][$row['car_status']]['text'];
            }
        }
        //echo '<pre>';
        //var_dump($data);exit;
        $returnArr = [];
        $returnArr['rows'] = $data; 
        $returnArr['total'] = $total;
        return json_encode($returnArr);
    }
    
    //详情
    public function actionScan(){
        $connection = yii::$app->db;
    	$id = yii::$app->request->get('id') or die('param id is required');
    	$type = yii::$app->request->get('type') or die('param type is required');
        //var_dump($id);var_dump($type);//exit;
    	if($type==1){	//交强险
    		$data = CarInsuranceCompulsory::find()
                    ->select([
                        'cs_car_insurance_compulsory.*',
                        'cs_car.car_status',
                        'cs_owner.name as owner_name',
                        'cs_operating_company.name as oper_name',
                        'cs_car_type.car_model_name as car_model_name2',
                    ])
                    ->where(['cs_car_insurance_compulsory.id'=>$id])
                    ->leftJoin('cs_car','cs_car.id = cs_car_insurance_compulsory.car_id')
                    ->leftJoin('cs_owner','cs_owner.id = cs_car.owner_id')
                    ->leftJoin('cs_operating_company','cs_operating_company.id = cs_car.operating_company_id')
                    ->leftJoin('cs_car_type', 'cs_car.car_type_id = cs_car_type.id')
                    ->asArray()
                    ->one();
            //var_dump($data);exit;
            //加载归属客户
                $configItems = ['car_status','INSURANCE_COMPANY'];
                $config = (new ConfigCategory)->getCategoryConfig($configItems,'value');
                $query = $connection->createCommand(
                        "select cCustomer_id,pCustomer_id,cs_customer_company.company_name,cs_customer_personal.id_name
                        from cs_car_let_record
                        left join cs_customer_company on cs_car_let_record.cCustomer_id=cs_customer_company.id
                        left join cs_customer_personal on cs_car_let_record.pCustomer_id=cs_customer_personal.id
                        where (back_time>".time()." or back_time=0) and car_id=".$data['car_id']
                );
                $customer = $query->queryOne();
                //var_dump($customer);exit;
                if($customer){
                    if($customer['company_name']){
                        $data['customer_name'] = $customer['company_name'];
                    }else if($customer['id_name']){
                        $data['customer_name'] = $customer['id_name'];
                    }
                }else {
                    $data['customer_name'] = $config['car_status'][$data['car_status']]['text'];
                }

            $insurance_compulsory = $connection->createCommand(
                'select id,money_amount,insurer_company,start_date,end_date,note 
                from cs_car_insurance_compulsory 
                where car_id='.$data['car_id'].' order by end_date desc limit 1'
                )->queryOne();
            if($insurance_compulsory){
                $data['insurance_compulsory'] = $insurance_compulsory;
                $data['insurance_compulsory']['insurer_company_name'] = @$config['INSURANCE_COMPANY'][$insurance_compulsory['insurer_company']]['text'];
            }else {
                $data['insurance_compulsory'] = '';
                $data['insurance_compulsory']['insurer_company_name'] = '';
            }

            //$car_m = $car_mod['car_model'];
            //$data[$key]['car_model'] = $config['car_model_name'][$car_m]['text'];
           $data['car_status_name'] =  $config['car_status'][$data['car_status']]['text'];
            //echo '<pre>';
            //var_dump($aa);exit;
            $data2 = CarInsuranceCompulsory::find()//关联批单数据
                ->select([
                    'cs_car_insurance_compulsory_form.*',
                    //'cs_car_insurance_compulsory.id as id_'

                    ])
                ->where(['cs_car_insurance_compulsory.id'=>$id,'cs_car_insurance_compulsory_form.is_del'=>0])
                ->leftJoin('cs_car_insurance_compulsory_form','cs_car_insurance_compulsory_form.insurance_id = cs_car_insurance_compulsory.id')
                ->asArray()->all();
    	}else if($type==2){	//商业险
    		$data = CarInsuranceBusiness::find()
                ->select([
                        'cs_car_insurance_business.*',
                        'cs_car.car_status',
                        'cs_owner.name as owner_name',
                        'cs_operating_company.name as oper_name',
                        'cs_car_type.car_model_name as car_model_name2',
                    ])
                ->where(['cs_car_insurance_business.id'=>$id])
                ->leftJoin('cs_car','cs_car.id = cs_car_insurance_business.car_id')
                ->leftJoin('cs_owner','cs_owner.id = cs_car.owner_id')
                ->leftJoin('cs_operating_company','cs_operating_company.id = cs_car.operating_company_id')
                ->leftJoin('cs_car_type', 'cs_car.car_type_id = cs_car_type.id')
                ->asArray()
                ->one();

             //加载归属客户
                $configItems = ['car_status','INSURANCE_COMPANY'];
                $config = (new ConfigCategory)->getCategoryConfig($configItems,'value');
                $query = $connection->createCommand(
                        "select cCustomer_id,pCustomer_id,cs_customer_company.company_name,cs_customer_personal.id_name
                        from cs_car_let_record
                        left join cs_customer_company on cs_car_let_record.cCustomer_id=cs_customer_company.id
                        left join cs_customer_personal on cs_car_let_record.pCustomer_id=cs_customer_personal.id
                        where (back_time>".time()." or back_time=0) and car_id=".$data['car_id']
                );
                $customer = $query->queryOne();
                //var_dump($customer);exit;
                if($customer){
                    if($customer['company_name']){
                        $data['customer_name'] = $customer['company_name'];
                    }else if($customer['id_name']){
                        $data['customer_name'] = $customer['id_name'];
                    }
                }else {
                    $data['customer_name'] = $config['car_status'][$data['car_status']]['text'];
                }
        $insurance_business = $connection->createCommand(
        'select id,money_amount,insurer_company,start_date,end_date,note,insurance_text 
        from cs_car_insurance_business 
        where car_id='.$data['car_id'].' order by end_date desc limit 1'
        )->queryOne();
        if($insurance_business){
            $data['insurance_business'] = $insurance_business;
            $data['insurance_business']['insurer_company_name'] = $config['INSURANCE_COMPANY'][$insurance_business['insurer_company']]['text'];
        }else {
            $data['insurance_business'] = '';
            $data['insurance_business']['insurer_company_name'] = '';
        }
          

            //$car_m = $car_mod['car_model'];
            //$data[$key]['car_model'] = $config['car_model_name'][$car_m]['text'];
           $data['car_status_name'] =  $config['car_status'][$data['car_status']]['text'];


            //(new \yii\db\query())->from('cs_car_insurance_business_form')
            $data2 = CarInsuranceBusiness::find()//关联批单数据
                ->select([
                    'cs_car_insurance_business_form.*',
                    //'cs_car_insurance_compulsory.id as id_'
                    ])
                ->where(['cs_car_insurance_business.id'=>$id,'cs_car_insurance_business_form.is_del'=>0])
                ->leftJoin('cs_car_insurance_business_form','cs_car_insurance_business_form.insurance_id = cs_car_insurance_business.id')
                ->asArray()->all();
    	}else if($type==3){	//其它险
    		$data = CarInsuranceOther::find()
                 ->select([
                        'cs_car_insurance_other.*',
                        'cs_car.car_status',
                        'cs_owner.name as owner_name',
                        'cs_operating_company.name as oper_name',
                        'cs_car_type.car_model_name as car_model_name2',
                    ])
                 //->where(['cs_car_insurance_compulsory.id'=>$id])
                    ->leftJoin('cs_car','cs_car.id = cs_car_insurance_other.car_id')
                    ->leftJoin('cs_owner','cs_owner.id = cs_car.owner_id')
                    ->leftJoin('cs_operating_company','cs_operating_company.id = cs_car.operating_company_id')
                    ->leftJoin('cs_car_type', 'cs_car.car_type_id = cs_car_type.id')
                ->where(['cs_car_insurance_other.id'=>$id])
                ->asArray()
                ->one();

            $configItems = ['car_status','INSURANCE_COMPANY'];
                $config = (new ConfigCategory)->getCategoryConfig($configItems,'value');
                $query = $connection->createCommand(
                        "select cCustomer_id,pCustomer_id,cs_customer_company.company_name,cs_customer_personal.id_name
                        from cs_car_let_record
                        left join cs_customer_company on cs_car_let_record.cCustomer_id=cs_customer_company.id
                        left join cs_customer_personal on cs_car_let_record.pCustomer_id=cs_customer_personal.id
                        where (back_time>".time()." or back_time=0) and car_id=".$data['car_id']
                );
                $customer = $query->queryOne();
                //var_dump($customer);exit;
                if($customer){
                    if($customer['company_name']){
                        $data['customer_name'] = $customer['company_name'];
                    }else if($customer['id_name']){
                        $data['customer_name'] = $customer['id_name'];
                    }
                }else {
                    $data['customer_name'] = $config['car_status'][$data['car_status']]['text'];
                }

            $insurance_other = $connection->createCommand(
                'select id,money_amount,insurer_company,start_date,end_date,note 
                from cs_car_insurance_other 
                where car_id='.$data['car_id'].' order by end_date desc limit 1'
                )->queryOne();
            if($insurance_other){
                $data['insurance_other'] = $insurance_other;
                $data['insurance_other']['insurer_company_name'] = @$config['INSURANCE_COMPANY'][$insurance_other['insurer_company']]['text'];
            }else {
                $data['insurance_other'] = '';
                $data['insurance_other']['insurer_company_name'] = '';
            }

            //$car_m = $car_mod['car_model'];
            //$data[$key]['car_model'] = $config['car_model_name'][$car_m]['text'];
           $data['car_status_name'] =  $config['car_status'][$data['car_status']]['text'];


    	}
        //echo '<pre>';
        //var_dump($data);
        //var_dump($data2);exit;
    	$car = Car::findOne($data['car_id']);
    	$data['plate_number'] = $car['plate_number'];
    	//查询车辆品牌
    	$data['brand_name'] = '';
    	if($car['brand_id']){
    		$carBrand = CarBrand::find()
    		->select(['name'])
    		->where(['id'=>$car['brand_id']])
    		->limit(1)->asArray()->one();
    		if(!empty($carBrand)){
    			$data['brand_name'] = $carBrand['name'];
    		}
    		unset($carBrand);
    	}
    	//查询车辆型号、保险公司
    	$data['car_model_name'] = '';
    	$data['insurer_company_name'] = '';
    	if($car['car_model']){
	    	$configItems = ['car_model_name','INSURANCE_COMPANY'];
	    	$config = (new ConfigCategory())->getCategoryConfig($configItems,'value');
	    	if(isset($config['car_model_name'][$car['car_model']])){
	    		$data['car_model_name'] = $config['car_model_name'][$car['car_model']]['text'];
	    	}
	    	if(isset($config['INSURANCE_COMPANY'][$data['insurer_company']])){
	    		$data['insurer_company_name'] = $config['INSURANCE_COMPANY'][$data['insurer_company']]['text'];
	    	}
    	}
    	//倒计时
    	$data['_end_date'] = $data['end_date'];
    	if($data['_end_date']){
    		if($data['_end_date']+86400 < time()){
    			$data['_end_date'] = '已过期';
    		}else{
    			$diff = $data['_end_date'] - strtotime(date('Y-m-d',time())); //年月日
    			$days = floor($diff/(3600*24)) + 1; //+1包含今日在内
    			$data['_end_date'] = $days.'天';
    		}
    	}else{
    		$data['_end_date'] = '';
    	}
        //echo '<pre>';
        //var_dump($data);
        //var_dump($data2);
        //exit;
    	return $this->render('scan',[
    			'data'=>$data,
                'data2'=>$data2,
    			]);
    }
    public function actionTest(){
        $url = yii::$app->request->get('url') or die('param id is requried');
        //echo '123';exit;
       // $filename = dirname(getcwd()).'/web/'.iconv("UTF-8","gb2312", $url);
        $filename = dirname(getcwd()).'/web/'.$url;
        $image = file_get_contents($filename,true);
        header('Content-type: image/jpg');
        echo $image;

    }
    /**
     * 下载附近
     */
    public function actionDownload()
    {
    	
        //echo yii::$app->request->baseUrl;exit;
    	$id = yii::$app->request->get('id') or die('param id is requried');
        $insurance_id = yii::$app->request->get('insurance_id');
        
    	$type = yii::$app->request->get('type') or die('param type is required');
       
    	if($type==1){	
        //交强险
    		$model = CarInsuranceCompulsory::findOne(['id'=>$id]);
    	}else if($type==2){	//商业险
    		$model = CarInsuranceBusiness::findOne(['id'=>$id]);
    	}else if($type==3){	//其它险
    		$model = CarInsuranceOther::findOne(['id'=>$id]);
    	}
    	
    	if(@$model->append_urls && @json_decode($model->append_urls)){
    		$append_urls = json_decode($model->append_urls);
    		foreach ($append_urls as $append_url){
    			//$file[] = dirname(getcwd()).'/web/'.iconv("UTF-8","gb2312", $append_url);
                $file[] = dirname(getcwd()).'/web/'.$append_url;
    		}
    		header("Content-type: text/html; charset=gbk");
    		$zipFile = dirname(getcwd()).'/runtime/'.time().'.zip';
            //var_dump($zipFile);exit;
    		File::filesToZip($file,$zipFile);
    		File::fileDownload($zipFile);
    		//     		foreach($file as $val){
    		//     			@unlink($val);
    		//     		}
    		@unlink($zipFile);
    	}else {
    		echo '无';
    	}
    }

    public function actionDownload2()
    {
        $type = yii::$app->request->get('type');
        $id = yii::$app->request->get('id');//通过insurance_id关联到保险的id
        $ii = yii::$app->request->get('ii');
        
        if($type==1){//交强险  
            $data = (new \yii\db\query())->from('cs_car_insurance_compulsory_form')->where(['insurance_id'=>$id])->all();
        }else if($type==2){ //商业险
            $data = (new \yii\db\query())->from('cs_car_insurance_business_form')->where(['insurance_id'=>$id])->all();   
        }else if($type==3){ //其它险
            $model = CarInsuranceOther::findOne(['id'=>$id]);
        }
        
//var_dump($data);exit;
        foreach(@$data as $k => $v) {
 
            if($k == $ii) {
            if(@$v['append_urls'] && @json_decode($v['append_urls'])){
            $append_urls = json_decode($v['append_urls']);
            foreach ($append_urls as $append_url){
                $file[] = dirname(getcwd()).'/web/'.$append_url;
                $arr = explode('/',$file[0]);
                $arr[3] = 'tci';
                foreach ($arr as $k1 => $v1) {
                    $file1[0] .= '/'.$v1;
                }
            }
            header("Content-type: text/html; charset=gbk");
            $zipFile = dirname(getcwd()).'/runtime/'.time().'.zip';
            //var_dump($zipFile);exit;
            File::filesToZip($file,$zipFile);
            File::fileDownload($zipFile);
    //var_dump(File::fileDownload($zipFile));exit;
            //          foreach($file as $val){
            //              @unlink($val);
            //          }
            @unlink($zipFile);
            }else {
                //echo '11111';exit;
                echo '无';
            }

        }
        }

    }
    
    /**
     * 按条件导出车辆列表
     */
    public function actionExportWidthCondition()
    {
    	set_time_limit(0);
    	//交强险、商业险、其它险sql
    	$db = new \yii\db\Query();
    	$query = $db->select(
    			'a.id,
    			a.type,
    			a.car_id,
    			a.start_date,
    			a.end_date,
    			a.end_date _end_date,
    			a.car_id insurance_text,
    			a.insurer_company,
    			a.money_amount,
    			a.note,
    			a.add_datetime,
    			a.add_aid,
    			a.number,
    			b.plate_number,
    			b.vehicle_dentification_number,
    			b.car_model,
    			b.brand_id,
    			c.username'
    	)
    	->from('cs_car_insurance_compulsory a')
    	->leftJoin('{{%car}} b', 'a.car_id = b.id')
    	->leftJoin('{{%admin}} c', 'a.add_aid = c.id')
    	->where('a.is_del=0');
    	$query1 = (new \yii\db\Query())->select(
    			'a.id,
    			a.type,
    			a.car_id,
    			a.start_date,
    			a.end_date,
    			a.end_date _end_date,
    			a.insurance_text,
    			a.insurer_company,
    			a.money_amount,
    			a.note,
    			a.add_datetime,
    			a.add_aid,
    			a.number,
    			b.plate_number,
    			b.vehicle_dentification_number,
    			b.car_model,
    			b.brand_id,
    			c.username'
    	)
    	->from('cs_car_insurance_business a')
    	->leftJoin('{{%car}} b', 'a.car_id = b.id')
    	->leftJoin('{{%admin}} c', 'a.add_aid = c.id')
    	->where('a.is_del=0');
    	$query2 = (new \yii\db\Query())->select(
    			'a.id,
    			a.type,
    			a.car_id,
    			a.start_date,
    			a.end_date,
    			a.end_date _end_date,
    			a.insurance_text,
    			a.insurer_company,
    			a.money_amount,
    			a.note,
    			a.add_datetime,
    			a.add_aid,
    			a.number,
    			b.plate_number,
    			b.vehicle_dentification_number,
    			b.car_model,
    			b.brand_id,
    			c.username'
    	)
    	->from('cs_car_insurance_other a')
    	->leftJoin('{{%car}} b', 'a.car_id = b.id')
    	->leftJoin('{{%admin}} c', 'a.add_aid = c.id')
    	->where('a.is_del=0');
    	//搜索条件
    	$car_no = yii::$app->request->get('plate_number');	//车牌号
    	$vehicle_dentification_number = yii::$app->request->get('vehicle_dentification_number');	//车架号
    	$car_model = yii::$app->request->get('car_model');	//车型
    	$start_date = yii::$app->request->get('start_date');	//保险开始日期
    	$end_date = yii::$app->request->get('end_date');	//保险结束日期
    	$insurer_company = yii::$app->request->get('insurer_company');	//保险公司
    	$insurance_type = yii::$app->request->get('insurance_type');	//保险类型
    	$start_money_amount = yii::$app->request->get('start_money_amount');	//起始保费
    	$end_money_amount = yii::$app->request->get('end_money_amount');	//结束保费
    	$number = yii::$app->request->get('number');
    	if($car_no)
    	{
    		$query->andFilterWhere([
    				'or',
    				['like','b.plate_number',$car_no],
    				['like','b.vehicle_dentification_number',$car_no],
    				['like','b.engine_number',$car_no]
    				]);
    		$query1->andFilterWhere([
    				'or',
    				['like','b.plate_number',$car_no],
    				['like','b.vehicle_dentification_number',$car_no],
    				['like','b.engine_number',$car_no]
    				]);
    		$query2->andFilterWhere([
    				'or',
    				['like','b.plate_number',$car_no],
    				['like','b.vehicle_dentification_number',$car_no],
    				['like','b.engine_number',$car_no]
    				]);
    	}
    	if($car_model){
    		$car_model_query = ConfigItem::find()->select('value')
    		->andWhere(['is_del'=>0,'belongs_id'=>62,'text'=>$car_model]);
    		$car_models = $car_model_query->asArray()->all();
    		$car_models_s = array();
    		foreach($car_models as $item){
    			array_push($car_models_s, $item['value']);
    		}
    		$query->andFilterWhere(['in','b.car_model',$car_models_s]);
    		$query1->andFilterWhere(['in','b.car_model',$car_models_s]);
    		$query2->andFilterWhere(['in','b.car_model',$car_models_s]);
    	}
    	if($start_date){
    		$query->andFilterWhere(['>=','a.start_date',strtotime($start_date)]);
    		$query1->andFilterWhere(['>=','a.start_date',strtotime($start_date)]);
    		$query2->andFilterWhere(['>=','a.start_date',strtotime($start_date)]);
    	}
    	if($end_date){
    		$query->andFilterWhere(['<=','a.end_date',strtotime($end_date)]);
    		$query1->andFilterWhere(['<=','a.end_date',strtotime($end_date)]);
    		$query2->andFilterWhere(['<=','a.end_date',strtotime($end_date)]);
    	}
    	if($insurer_company){
    		$query->andFilterWhere(['=','a.insurer_company',$insurer_company]);
    		$query1->andFilterWhere(['=','a.insurer_company',$insurer_company]);
    		$query2->andFilterWhere(['=','a.insurer_company',$insurer_company]);
    	}
    	if($insurance_type){
    		if($insurance_type == 1){	//交强险
    			$query1->andFilterWhere(['=','a.car_id',0]);
    			$query2->andFilterWhere(['=','a.car_id',0]);
    		}else if($insurance_type == 2){	//商业险
    			$query->andFilterWhere(['=','a.car_id',0]);
    			$query2->andFilterWhere(['=','a.car_id',0]);
    		}else{	//其它险
    			$query->andFilterWhere(['=','a.car_id',0]);
    			$query1->andFilterWhere(['=','a.car_id',0]);
    		}
    	}
    	if($start_money_amount){
    		$query->andFilterWhere(['>=','a.money_amount',$start_money_amount]);
    		$query1->andFilterWhere(['>=','a.money_amount',$start_money_amount]);
    		$query2->andFilterWhere(['>=','a.money_amount',$start_money_amount]);
    	}
    	if($end_money_amount){
    		$query->andFilterWhere(['<=','a.money_amount',$end_money_amount]);
    		$query1->andFilterWhere(['<=','a.money_amount',$end_money_amount]);
    		$query2->andFilterWhere(['<=','a.money_amount',$end_money_amount]);
    	}
    	if($number){
    		$query->andFilterWhere(['like','a.number',$number]);
    		$query1->andFilterWhere(['like','a.number',$number]);
    		$query2->andFilterWhere(['like','a.number',$number]);
    	}
    	
    	$query3 = (new \yii\db\Query())->from(['data'=>$query->union($query1)->union($query2)]);
    	////查询条件结束
    	$sortColumn = yii::$app->request->get('sort');
    	$sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
    	$orderBy = '';
    	if($sortColumn){
    		switch ($sortColumn) {
    			case 'xxx':
    				$orderBy = '{{%car_insurance_compulsory}}.`id` ';
    				break;
    			default:
    				$orderBy = 'data.`'.$sortColumn.'` ';
    			break;
    		}
    	}else{
    		$orderBy = 'data.`add_datetime` ';
    	}
    	$orderBy .= $sortType;
    	$query3 = $query3->orderBy($orderBy);
    	$data = $query3->all();
//     	print_r($data);
//     	exit;
    	
    	$filename = '购买保险记录.csv'; //设置文件名
    	$str = "保单号,车牌号,品牌,车型名称,开始时间,结束时间,倒计时,类型,保险公司,保费,备注,上次修改时间,操作人员,险种\n";
    	$car_type_arr = array(1=>'自用车',2=>'备用车');
    	$car_status_arr = array(1=>'已替换',2=>'未替换');
    	foreach ($data as $row){
    		$plate_number = $row['plate_number'];
    		$start_date = date('Y-m-d',$row['start_date']);
    		$end_date = date('Y-m-d',$row['end_date']);
    		$_end_date = $row['_end_date'];
    		if($_end_date){
    			if($_end_date+86400 < time()){
    				$_end_date = '已过期';
    			}else{
    				$diff = $_end_date - strtotime(date('Y-m-d',time())); //年月日
    				$days = floor($diff/(3600*24)) + 1; //+1包含今日在内
    				$_end_date = $days.'天';
    			}
    		}else{
    			$_end_date = '';
    		}
    		$types = array('','交强险','商业险','其它险');
    		$type = @$types[$row['type']];
    		$config = (new ConfigCategory)->getCategoryConfig(['INSURANCE_COMPANY','car_model_name'],'value');
    		$insurer_company = @$config['INSURANCE_COMPANY'][$row['insurer_company']]['text'];
    		$money_amount = $row['money_amount'];
    		$note = $row['note'];
    		$add_datetime = date('Y-m-d',$row['add_datetime']);
    		$username = $row['username'];
    		$insurance_text = '';
    		if($row['insurance_text'] && ($row['type']==2 || $row['type']==3)){
    			$insurance_objs = json_decode($row['insurance_text']);
    			foreach ($insurance_objs as $row1){
    				if($row1[0]){
    					$insurance_text .= "{$row1[0]}({$row1[1]}元)，";
    				}
    			}
    		}
    		$car_model = '';
    		if(@$config['car_model_name'][$row['car_model']]){
    			$car_model = @$config['car_model_name'][$row['car_model']]['text'];
    		}
    		$brand_name = '';
    		if($row['brand_id']){
    			$carBrand = CarBrand::find()
    			->select(['name'])
    			->where(['id'=>$row['brand_id']])
    			->limit(1)->asArray()->one();
    			if(!empty($carBrand)){
    				$brand_name = $carBrand['name'];
    			}
    			unset($carBrand);
    		}
    		$str .= "{$row['number']},{$plate_number},{$brand_name},{$car_model},{$start_date},{$end_date},{$_end_date},{$type},{$insurer_company},{$money_amount},{$note},{$add_datetime},{$username},{$insurance_text}"."\n";
    	}
    	$str = mb_convert_encoding($str, "GBK", "UTF-8");
    	$this->export_csv($filename,$str); //导出
    }
    
    function export_csv($filename,$data)
    {
    	//		header("Content-type: text/html; charset=utf-8");
    	header("Content-type:text/csv;charset=GBK");
    	header("Content-Disposition:attachment;filename=".$filename);
    	header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
    	header('Expires:0');
    	header('Pragma:public');
    	echo $data;
    }
}