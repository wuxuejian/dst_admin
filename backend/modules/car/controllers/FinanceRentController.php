<?php
/**
 * 
 * @author 
 */
namespace backend\modules\car\controllers;
use backend\controllers\BaseController;
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
use backend\models\CarStock;
use yii\db\Query;
use backend\models\CarOfficeRegister;
use backend\models\FinanceCompany;
use backend\models\FinanceCar;


class FinanceRentController extends BaseController
{
	public function actionIndex()
	{	
		//echo '456';exit;


		$buttons = $this->getCurrentActionBtn();
		return $this->render('index',[
            'buttons'=>$buttons,
            
        ]);
	}

	//列表显示
	public function actionGetList()
	{	
		
		//echo  '123';exit;
		$query = FinanceCompany::find()
            ->select(['{{%finance_company}}.*',
            		  '{{%admin}}.name as add_person',		
            		 // 'count(`finance_id`)',
					  ])
			->leftJoin('{{%admin}}', '{{%admin}}.`id` = {{%finance_company}}.`add_aid` ')
			//->leftJoin('{{%finance_car}}', '{{%finance_car}}.`finance_id` = {{%finance_company}}.`id` ')  
          //  ->andWhere(['{{%purchase_order_main}}.`is_del`'=>0])
            ;
        $company_name = yii::$app->request->post('company_name');
        // var_dump($company_name);exit;
       	$query->andFilterWhere(['like','{{%finance_company}}.`company_name`',$company_name]);
        $total = $query->count();
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)->limit($pages->limit)
		->asArray()->all();

		//echo '<pre>';
		//var_dump($data);exit;
		foreach ($data as $key => $value) {
			//echo '<pre>';
			//var_dump($value);exit;
			$num = FinanceCar::find()->select(['finance_id'])->where(['finance_id'=>$value['id']])->count();
			//var_dump($num);exit;
			$data[$key]['num'] = $num;
		}

        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        return json_encode($returnArr);	

    	
	}

	//添加融资租赁公司
	public function actionAdd()
	{	
        //echo '123';exit;
        $connection = yii::$app->db;
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
				//$number = yii::$app->request->post('number');//公司编号，系统自动生成
				$number = date('YmdHis').str_pad(mt_rand(0,100),3,0);//
				//$password = yii::$app->request->post('password');//密码
				$password = md5(substr(md5(yii::$app->request->post('password')),0,30));

				$company_name = yii::$app->request->post('company_name');//公司名称
				$director_name = yii::$app->request->post('director_name');//负责人姓名
				$director_mobile = yii::$app->request->post('director_mobile');//负责人手机
				$director_post = yii::$app->request->post('director_post');//负责人职务
				//echo yii::$app->request->post('password');
				//print_r(yii::$app->request->post());
				//exit;
				//var_dump(md5(substr(md5(yii::$app->request->post('password')),0,30)));exit;

				$time = strtotime(date('Y-m-d H:i:s',time()));//添加时间
				$add_aid =  $_SESSION['backend']['adminInfo']['id'];
				//var_dump($add_aid);
				//var_dump($time);exit;

				$result = $connection->createCommand()->insert('cs_finance_company',[
					'number'=>$number,
					'password'=>$password,
					'company_name'=>$company_name,
					'director_name'=>$director_name,
					'director_mobile'=>$director_mobile,
					'director_post'=>$director_post,
					'add_time'=>$time,
					'add_aid'=>$add_aid,

				])->execute();

				if($result){
					$returnArr['status'] = true;
		    		$returnArr['info'] = '添加成功!';
				} else {
					$returnArr['status'] = false;
			    	$returnArr['info'] = '添加失败!';	
				}
				return json_encode($returnArr);
		}
		return $this->render('add',[]);
		
	}

	//
	public function actionRelCar(){
		//echo '456';exit;
        $id = yii::$app->request->get('id') or die('param carId is required');
        //var_dump($id);exit;
        $buttons  = [
            ['text'=>'添加车辆','on_click'=>'CarFinanceRentRelCar.add()','icon'=>'icon-add'],
            ['text'=>'移除','on_click'=>'CarFinanceRentRelCar.remove()','icon'=>'icon-edit'],
            //['text'=>'导出 excel','on_click'=>'CountDriverRelIndex.exportWidthCondition()','icon'=>'icon-add']
        ];
        
        return $this->render('rel-car',[
                'id'=>$id,
                'buttons'=>$buttons
                ]);
    }
    

    public function actionAddRelCar(){
    	//echo '179';exit;
    	 $connection = yii::$app->db;
    	
			//var_dump($finance_id);exit;
		if(yii::$app->request->isPost) {
			 $id = yii::$app->request->post('id');//关联融资租赁公司汽车
			    //$finance_id = 360;
				//var_dump($id);exit;
				$plate_number = yii::$app->request->post('plate_number');//批量接受车牌号
				//$car_id = yii::$app->request->post('car_id');//
				$add_aid =  $_SESSION['backend']['adminInfo']['id'];//操作人
				$time = strtotime(date('Y-m-d H:i:s',time()));//添加时间

				$arr = [];
				$arr = explode("\n", trim($plate_number));
				
				//var_dump(strlen($arr[0]));exit;
				//var_dump($arr);exit;

				//判断数值里是否有重复的数
				/*$unique_arr = array_unique ($arr); 
			    // 获取重复数据的数组 
			    $repeat_arr = array_diff_assoc ($arr, $unique_arr); 
			    var_dump(array_unique($repeat_arr));exit;
			    return $repeat_arr;*/
			    foreach ($arr as $key => $val) {
			    	# code...
			    	$arr_rr[] = trim($val);
			    }
			    if (count($arr_rr) != count(array_unique($arr_rr))) {   
   					//echo '该数数据有重复值';  
   					$returnArr['info'] = '该数据有重复值';
					return json_encode($returnArr);
				}
				
//echo '456123';exit;
				foreach ($arr as $key1 => $value1) {
					//按车牌号查询
					//var_dump($arr);exit;
					if(strlen($value1) < 13) {
						//echo 'chepai1';exit;
						$car_r = Car::find()->select(['id'])->where(['plate_number'=>trim($value1)])->asArray()->one();
						$financecar = FinanceCar::find()->select(['car_id','finance_id'])->where(['car_id'=>$car_r['id']])->asArray()->one();//查询出车牌id
						$financecompany = FinanceCompany::find()->select(['company_name'])->where(['id'=>$financecar['finance_id']])->asArray()->one();
						//var_dump($financecompany);exit;
						//判断车辆已存在
						if($financecar) {
							$plate_number_r1 = Car::find()->select(['plate_number'])->where(['id'=>$financecar['car_id']])->asArray()->one();
							$plate_number1 = $plate_number_r1['plate_number'];
							//echo '11111';exit;
							//var_dump($plate_number_r1);exit;
							$returnArr['info'] = '车牌号 '."$plate_number1".' 已关联到'.$financecompany['company_name'];
							return json_encode($returnArr);
						}
						//车牌不存在
						$car_r = Car::find()->select(['id'])->where(['plate_number'=>trim($value1)])->asArray()->one();
						if(!isset($car_r)){
							$returnArr['info'] = '车牌号 '."$value1".' 输入错误,请更正!';
							return json_encode($returnArr);
						}
					}

					//按车架号查询
					if(strlen($value1) == 13 || strlen($value1) > 13) {
						//echo 'chejia2';exit;
						$car_j = Car::find()->select(['id'])->where(['vehicle_dentification_number'=>trim($value1)])->asArray()->one();//根据车架号查询id
						
						$financecar = FinanceCar::find()->select(['car_id','finance_id'])->where(['car_id'=>$car_j['id']])->asArray()->one();//查询出车牌id
						
						$financecompany = FinanceCompany::find()->select(['company_name'])->where(['id'=>$financecar['finance_id']])->asArray()->one();
						//var_dump($car_j);exit;
						//var_dump($financecompany);exit;
						//判断车辆已存在
						if($financecar) {
							$plate_number_r1 = Car::find()->select(['vehicle_dentification_number'])->where(['id'=>$financecar['car_id']])->asArray()->one();
							$plate_number1 = $plate_number_r1['vehicle_dentification_number'];
							//echo '11111';exit;
							//var_dump($plate_number_r1);exit;
							$returnArr['info'] = '车架号 '."$plate_number1".' 已关联到'.$financecompany['company_name'].'公司!';
							return json_encode($returnArr);
						}
						//车牌不存在
						$car_j = Car::find()->select(['id'])->where(['vehicle_dentification_number'=>trim($value1)])->asArray()->one();
						if(!isset($car_j)){
							$returnArr['info'] = '车牌号 '."$value1".' 输入错误,请更正!';
							return json_encode($returnArr);
						}
					}
					
				
					//var_dump($car_r);exit;



				}

				//echo '123456789';exit;
				$the_arr = [];//定义一个空数组
				foreach ($arr as $key => $value) {
				
					//$arr_c[] = trim($value);
					$car_r = Car::find()->select(['id'])->where(['plate_number'=>trim($value),'is_del'=>0])->asArray()->one();//查询出车牌id
					$car_j = Car::find()->select(['id'])->where(['vehicle_dentification_number'=>trim($value),'is_del'=>0])->asArray()->one();

					//var_dump($financecar);exit;
					if($car_r) {
						$result = $connection->createCommand()->insert('cs_finance_car',[
						'finance_id'=>$id,
						'car_id'=>$car_r['id'],
						'add_time'=>$time,
						'add_aid'=>$add_aid,
						])->execute();
					}
					if($car_j) {
						$result = $connection->createCommand()->insert('cs_finance_car',[
						'finance_id'=>$id,
						'car_id'=>$car_j['id'],
						'add_time'=>$time,
						'add_aid'=>$add_aid,
						])->execute();
					}

					
				}
				
				//echo '<pre>';
				//var_dump($the_arr);exit;
				if($result){
					$returnArr['status'] = true;
		    		$returnArr['info'] = '添加成功!';
				} else {
					$returnArr['status'] = false;
			    	$returnArr['info'] = '添加失败!';	
				}
				return json_encode($returnArr);
		}
		 $id = yii::$app->request->get('id');//关联融资租赁公司汽车


    	return $this->render('add-rel-car',['id'=>$id]);
    }


     public function actionGetRelList(){
     	 $id = yii::$app->request->get('id');//关联融资租赁公司汽车
     	 //var_dump($id);exit;
     	$query = FinanceCar::find()
            ->select(['{{%finance_car}}.*',
            		  '{{%admin}}.name as add_name',
            		  '{{%car}}.plate_number',
            		  '{{%car_brand}}.name as car_brand',	



					  ])
			->leftJoin('{{%admin}}', '{{%admin}}.`id` = {{%finance_car}}.`add_aid` ')
			->leftJoin('{{%car}}', '{{%car}}.`id` = {{%finance_car}}.`car_id` ')
			->leftJoin('{{%car_brand}}', '{{%car_brand}}.`id` = {{%car}}.`brand_id` ')
              
            ->andWhere(['{{%finance_car}}.`finance_id`'=>$id,'{{%finance_car}}.`is_del`'=>0 ])
            //'{{%finance_car}}.`is_del`'=>0 
            ;
       	$plate_number = yii::$app->request->get('plate_number');
       	$car_brand = yii::$app->request->get('car_brand');
        //var_dump($plate_number);exit;
       	$query->andFilterWhere(['like','{{%car}}.`plate_number`',$plate_number]);
       	$query->andFilterWhere(['like','{{%car_brand}}.`name`',$car_brand]);

        $total = $query->count();
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)->limit($pages->limit)
		->asArray()->all();
		//echo '<pre>';
		//var_dump($data);exit;

        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        return json_encode($returnArr);	
     }

     public function actionEdit() {
     	//$number = date('YmdHis').str_pad(mt_rand(0,100),3,0);//
     	$connection = \Yii::$app->db;
		if(yii::$app->request->isPost) {

			$id = yii::$app->request->post('id');
			//var_dump($id);exit;
			$password = md5(substr(md5(yii::$app->request->post('password')),0,30));

			$company_name = yii::$app->request->post('company_name');//公司名称
			$director_name = yii::$app->request->post('director_name');//负责人姓名
			$director_mobile = yii::$app->request->post('director_mobile');//负责人手机
			$director_post = yii::$app->request->post('director_post');//负责人职务

			$time = strtotime(date('Y-m-d H:i:s',time()));//添加时间
			$add_aid =  $_SESSION['backend']['adminInfo']['id'];

			
			$result = $connection->createCommand()->update('cs_finance_company',
						[
						'password'=>$password,
						'company_name'=>$company_name,
						'director_name'=>$director_name,
						'director_mobile'=>$director_mobile,
						'director_post'=>$director_post,
						'add_time'=>$time,
						'add_aid'=>$add_aid,
						
						],'id=:id',[':id'=>$id]
					)->execute();
			if($result){
					$returnArr['status'] = true;
		    		$returnArr['info'] = '修改成功!';
			} else {
				$returnArr['status'] = false;
		    	$returnArr['info'] = '修改失败!';	
			}
			return json_encode($returnArr);

		}
		
		$id = yii::$app->request->get('id');
		 $finance_c = FinanceCompany::find()->where(['id'=>$id])->asArray()->one();
		 //var_dump($finance_c);exit;
		//var_dump($id);exit;
     	return $this->render('edit',['id'=>$id,'finance_c'=>$finance_c]);
     }

     //删除关联车辆
     public function actionRemove() {
		$id = yii::$app->request->get('id');
		//var_dump($id);exit;
		$connection = \Yii::$app->db;
		//$result = $connection->createCommand()->update('cs_finance_car',['is_del'=>1],'id=:id',[':id'=>$id])->execute();
		$sql = "delete from cs_finance_car where id = {$id}";
		$result = $connection->createCommand($sql)->execute();
		if($result)
		{
			$returnArr['status'] = true;
			$returnArr['info'] = '删除成功！';
		}else{
			$returnArr['status'] = false;
			$returnArr['info'] = '删除失败！';
		}
	
		return json_encode($returnArr);
		
	}
     
    //删除融资租赁公司
    public function actionDel() {
		$id = yii::$app->request->get('id');
		//var_dump($id);exit;
		$connection = \Yii::$app->db;
		//$result = $connection->createCommand()->update('cs_finance_car',['is_del'=>1],'id=:id',[':id'=>$id])->execute();
		$sql = "delete from cs_finance_company where id = {$id}";
		$result = $connection->createCommand($sql)->execute();
		if($result)
		{
			$returnArr['status'] = true;
			$returnArr['info'] = '删除成功！';
		}else{
			$returnArr['status'] = false;
			$returnArr['info'] = '删除失败！';
		}
	
		return json_encode($returnArr);
		
	}
     
}