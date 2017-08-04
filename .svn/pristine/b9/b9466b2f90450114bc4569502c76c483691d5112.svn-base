<?php
/**
 * 流程配置类
 * @author Administrator
 *
 */
namespace backend\modules\station\controllers;
use backend\controllers\BaseController;
use yii;
use yii\data\Pagination;
use backend\models\RbacRole;
use backend\models\CarBrand;
use common\classes\Category;
class ServiceController extends BaseController
{
	public function actionIndex()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$db = new \yii\db\Query();
			$query = $db->select('oa_service_site.*,cs_car_brand.name as brand_name')->from('oa_service_site');
			$pageSize = isset($_POST['rows']) && $_POST['rows'] <= 50 ? intval($_POST['rows']) : 10;
			//站点名称
 			$site_name = yii::$app->request->post('site_name');
			/*if($site_name)
			{
				$query->andWhere('site_name=:site_name',[':site_name'=>$site_name]);
				//$query->andWhere(['like','car_no',$car_no]);
			}*/
			if($site_name){
            	$query->andFilterWhere(['like','{{oa_service_site}}.`site_name`',$site_name]);
        	}
			//类型
			$type = yii::$app->request->post('type');
			if($type)
			{
				$query->andWhere('type=:type',[':type'=>$type]);
			}
			//修理厂类别
			$type2 = yii::$app->request->post('type2');
			if($type2)
			{
				$query->andWhere('type2=:type2',[':type2'=>$type2]);
			}
			//合作方式
			$team_type = yii::$app->request->post('team_type');
			if($team_type)
			{
				$query->andWhere('team_type=:team_type',[':team_type'=>$team_type]);
			}
			//var_dump($type2);exit;
			//所属厂商
			//ServiceSite::find()
			$arr_brand_name= yii::$app->request->post('arr_brand_name');
			//var_dump($arr_brand_name);exit;

			/*if($arr_brand_name)
			{
				$query->andWhere('brand_id=:brand_id',[':brand_id'=>$arr_brand_name]);
			}*/
			if($arr_brand_name){
            $query->andFilterWhere(['like','{{oa_service_site}}.`brand_id`',$arr_brand_name]);
        	}
			//var_dump($arr_brand_name);exit;
			//省
			$province_id = yii::$app->request->post('province_id');
			if($province_id)
			{
				$query->andWhere('province_id=:province_id',[':province_id'=>$province_id]);
			}
			//市
			$city_id = yii::$app->request->post('city_id');
			if($city_id)
			{
				$query->andWhere('city_id=:city_id',[':city_id'=>$city_id]);
			}
			//地区/县
			$county_id = yii::$app->request->post('county_id');
			if($county_id)
			{
				$query->andWhere('county_id=:county_id',[':county_id'=>$county_id]);
			}
			
			//排序字段
			$sort = yii::$app->request->post('sort');
			$order = yii::$app->request->post('order');  //asc|desc
			if($sort)
			{
				$query->orderBy("{$sort} {$order}");
			}else{
				$query->orderBy("time DESC");
			}
			$total = $query->count();
			$query->join('LEFT JOIN','cs_car_brand','cs_car_brand.id =oa_service_site.brand_id');
			$pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize,'page'=>$_POST['page']-1 ]);
			$result = $query->offset($pages->offset)->limit($pages->limit)->all();
			//echo '<pre>';
			//var_dump($result);exit;
			if($result){
				foreach ($result as $key=>$val)
				{
					switch ($val['type']){
						case 1:
							$result[$key]['type'] = '厂家特约服务站';
							break;
						case 2:
							$result[$key]['type'] = '我方合作服务站';
							break;
						case 3:
							$result[$key]['type'] = '厂家4S店/修理厂';
							break;
						case 4:
							$result[$key]['type'] = '其他类型';
							break;
					}
			
					switch ($val['level']){
						case 0:
							$result[$key]['level'] = '';
							break;
						case 1:
							$result[$key]['level'] = '一级';
							break;
						case 2:
							$result[$key]['level'] = '二级';
							break;
						case 3:
							$result[$key]['level'] = '三级';
							break;
					}


					//echo '<pre>';
					//var_dump($val);exit;
					/*foreach($result[$key]['brand_id'] as $key2 => $value2){
						echo '<pre>';
						var_dump($value2);exit;
					}*/
					$arr=explode(",",$val['brand_id']);
					//echo '<pre>';
					//var_dump($arr);exit;
					$arr_brand_name = '';
					foreach($arr as $key2 => $value2){
						//echo '<pre>';
						//$arr_brand_name .= $value2;
						$arr_brand = CarBrand::find()
        				->select(['name'])
        				->andWhere(['`id`'=>$value2])->asArray()->one();

						$arr_brand_name .= $arr_brand['name'].' ';

					}
					//echo 'm2';
					//echo '<pre>';
					//var_dump($arr_brand_name);exit;
					$result[$key]['arr_brand_name'] = $arr_brand_name;

					
					//echo 'm2';
					//echo '<pre>';
					//var_dump($result[$key]['arr_brand_name']);exit;
					
					switch ($val['type2']){
						case 1:
							$result[$key]['type2'] = '4S店';
							break;
						case 2:
							$result[$key]['type2'] = '二类';
							break;
						case 3:
							$result[$key]['type2'] = '三类';
							break;
						case 4:
							$result[$key]['type2'] = '其他';
							break;
					}

					switch ($val['team_type']){
						case 1:
							$result[$key]['team_type'] = '有合作协议';
							break;
						case 2:
							$result[$key]['team_type'] = '无合作协议';
							break;
					}



					//地址
					$connection = yii::$app->db3;
					$provinces = $connection->createCommand(
							"select * from zc_region where region_type=1 and region_id={$val['province_id']}"
					)->queryOne();
					$citys = $connection->createCommand(
							"select * from zc_region where region_type=2 and region_id={$val['city_id']}"
							)->queryOne();
					$countys = $connection->createCommand(
							"select * from zc_region where region_type=3 and region_id={$val['county_id']}"
							)->queryOne();
					$result[$key]['addr'] = $provinces['region_name'].$citys['region_name'].$countys['region_name'].$val['address'];
					
					//提供服务
					$provide_services = json_decode($val['provide_services'],true);

					$str = '';
					if($provide_services){
					foreach ($provide_services as $provide_service)
					{
						$str .= $provide_service.' ';
					} 
					//var_dump($str);exit;
					$result[$key]['provide_services'] = $str;
					}
				}


			}
			$returnArr = [];
			$returnArr['rows'] = $result;
			$returnArr['total'] = $total;
			return json_encode($returnArr);
		}
		$buttons = $this->getCurrentActionBtn();
		
		$connection = yii::$app->db3;
		$provinces = $connection->createCommand("select * from zc_region where region_type=1")->queryAll();
		return $this->render('index',['buttons'=>$buttons,'provinces'=>$provinces]);
	}
	
	
	public function actionAdd()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$site_name 			= yii::$app->request->post('site_name');
			//$site_short_name 	= yii::$app->request->post('site_short_name');
			//$type 				= yii::$app->request->post('type');
			//$level 				= $type ==1 ? yii::$app->request->post('level') : 0;
			$type2 				= yii::$app->request->post('type2');//修理厂类别
			$team_type 			= yii::$app->request->post('team_type');//合作方式

			//授权品牌
			$brand_id   		= [];
			$brand_id 			= yii::$app->request->post('brand_id');
			//$brand_id			= json_encode($brand_id);
			$str = '';
			if(isset($brand_id)){
				foreach($brand_id as $key => $value){
				//var_dump($value);exit;
				$str .=$value.',';
				}
				$newstr = substr($str,0,strlen($str)-1);	
			}
			
			//var_dump($newstr);exit;
			//座机号码
			$landline   		= [];
			$landline 			= yii::$app->request->post('landline');
			$str2 = '';
			foreach($landline as $key2 => $value2){
				//var_dump($value2);exit;
				$str2 .=$value2.'-';
			}
			//var_dump($str2);
			if($landline[2] != ''){
				//echo '---'.'<br>';
				$newstr2 = substr($str2,0,strlen($str2)-1);
				//echo 'm1';
				//var_dump($newstr2);exit;

			}
			$newstr2 = substr($str2,0,strlen($str2)-2);
			//var_dump($landline);
			//echo 'm2';
			//var_dump($newstr2);exit;



			$province_id 		= yii::$app->request->post('province_id');
			$city_id 			= yii::$app->request->post('city_id');
			$county_id 			= yii::$app->request->post('county_id');
			$address 			= yii::$app->request->post('address');
			$main_duty_name 	= yii::$app->request->post('main_duty_name');
			$main_duty_tel 		= yii::$app->request->post('main_duty_tel');
			$other_duty_name 	= yii::$app->request->post('other_duty_name');
			$other_duty_tel 	= yii::$app->request->post('other_duty_tel');
			$remark 			= yii::$app->request->post('remark');
			
			//提供服务
			$provide_services   = [];
			$provide_services	= yii::$app->request->post('provide_services');
			$provide_services	= json_encode($provide_services);

			/*echo '<pre>';
			var_dump($type2).'<br>';

			var_dump($team_type).'<br>';
			var_dump($brand_id).'<br>';
			var_dump($landline).'<br>';
			var_dump($provide_services);
			exit;
*/

			$db = \Yii::$app->db;
			$result = $db->createCommand()->insert('oa_service_site',
					[
					'site_name'			=> $site_name,
					//'site_short_name'	=> $site_short_name,
					//'type'				=> $type,
					//'level'				=> $level,
					'type2'             => $type2,
					'team_type'         => $team_type,
					'brand_id'			=> $newstr,
					'landline'			=> $newstr2,
					'province_id'		=> $province_id,
					'city_id'			=> $city_id,
					'county_id'			=> $county_id,
					'address'			=> $address,
					'main_duty_name'	=> $main_duty_name,
					'main_duty_tel'		=> $main_duty_tel,
					'other_duty_name'	=> $other_duty_name,
					'other_duty_tel'	=> $other_duty_tel,
					'provide_services'	=> $provide_services,
					'remark'			=> $remark,
					'time'				=> time(),
					]
				)->execute();
			if($result)
			{
				$returnArr['status'] = true;
				$returnArr['info'] = '新增成功！';
			}else{
				$returnArr['status'] = false;
				$returnArr['info'] = '新增失败！';
			}
			
			
			return json_encode($returnArr);
			
		}
		$connection = yii::$app->db3;
		$provinces = $connection->createCommand(
				"select * from zc_region where region_type=1"
		)->queryAll();
		//品牌
		$query1 = CarBrand::find()
            ->select(['id','pid','text'=>'name'])
            ->andWhere(['`is_del`'=>0]);
        $rows = $query1->asArray()->all();
        $nodes = [];
        if(!empty($rows)){
            $nodes = Category::unlimitedForLayer($rows,'pid');
        }
         //$isShowRoot = intval(yii::$app->request->get('isShowRoot'));
        //if(!$isShowRoot){
            $data = [['id'=>0,'text'=>'顶级','iconCls'=>'icon-filter','children'=>$nodes]];
        //}
         $data1 = array();
         //var_dump($data1);exit;
        foreach($data[0]['children'] as $key => $value3){
        	//echo '<pre>';
        	//var_dump($data[0]['children']);exit;
        	$obj = array();	

        	if(count($value3['children']) != 0){
        		//var_dump(count($value3['children']));exit;
        		//var_dump($value3['children'][0]['text']);exit;
        		
        		$obj['id'] = $value3['children'][0]['id'];
        		$obj['text'] = $value3['children'][0]['text'];
        		$data1[] = $obj;
        		//echo '<pre>';
        		//echo 'm1';
        		//var_dump($value3['children'][0]['text']);exit;

        	} else {
        		//var_dump($value3['text']);exit;
        		//echo 'm1';
        		//$data1[] = $value3['text'];
        		$obj['id'] = $value3['id'];
        		$obj['text'] = $value3['text'];
        		$data1[] = $obj;
        		//var_dump($data1);

        	}
        }
        /*echo '<pre>';
        var_dump($data1);exit;
        $data2 = array();
        foreach($data1 as $value4){
        	$data2[] = CarBrand::find()
        	->select(['id','name'])
        	->andWhere(['`name`'=>$value4])->asArray()->all();
        }*/
        //echo '<pre>';
       //var_dump($data1);exit;
        //$rows = $query1->asArray()->all();



		return $this->render('add',['provinces'=>$provinces,'data1'=>$data1]);
	}
	
	
	
	public function actionEdit()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$id 				= yii::$app->request->post('id');
			$site_name 			= yii::$app->request->post('site_name');
			//$site_short_name 	= yii::$app->request->post('site_short_name');
			//$type 				= yii::$app->request->post('type');
			//$level 				= $type ==1 ? yii::$app->request->post('level') : 0;
			$type2 				= yii::$app->request->post('type2');//修理厂类别
			$team_type 			= yii::$app->request->post('team_type');//合作方式
			//授权品牌
			$brand_id   		= [];
			$brand_id 			= yii::$app->request->post('brand_id');
			$str = '';
			if(isset($brand_id)){
				foreach($brand_id as $key => $value){
				//var_dump($value);exit;
				$str .=$value.',';
				}
				$newstr = substr($str,0,strlen($str)-1);	
			}


			//$landline 			= yii::$app->request->post('landline');
			//座机号码
			
			$landline1			= yii::$app->request->post('landline1');
			$landline2 			= yii::$app->request->post('landline2');
			$landline3 			= yii::$app->request->post('landline3');
			$newstr2            = $landline1.'-'.$landline2.'-'.$landline3;
			if($landline3 == '') {
				$newstr2 = substr($newstr2,0,strlen($newstr2)-1);
			}
			//var_dump($newstr2);exit;



			$province_id 		= yii::$app->request->post('province_id');
			$city_id 			= yii::$app->request->post('city_id');
			$county_id 			= yii::$app->request->post('county_id');
			$address 			= yii::$app->request->post('address');
			$main_duty_name 	= yii::$app->request->post('main_duty_name');
			$main_duty_tel 		= yii::$app->request->post('main_duty_tel');
			$other_duty_name 	= yii::$app->request->post('other_duty_name');
			$other_duty_tel 	= yii::$app->request->post('other_duty_tel');
			$remark 			= yii::$app->request->post('remark');
	//var_dump($brand_id);exit;
			//提供服务
			$provide_services   = [];
			$provide_services	= yii::$app->request->post('provide_services');
			$provide_services	= json_encode($provide_services);
	
			$db = \Yii::$app->db;
			$result = $db->createCommand()->update('oa_service_site',
					['site_name'		=> $site_name,
					//'site_short_name'	=> $site_short_name,
					//'type'				=> $type,
					//'level'				=> $level,
					'type2'				=> $type2,
					'team_type'			=> $team_type,
					'brand_id'			=> $newstr,
					'landline'			=> $newstr2,
					'province_id'		=> $province_id,
					'city_id'			=> $city_id,
					'county_id'			=> $county_id,
					'address'			=> $address,
					'main_duty_name'	=> $main_duty_name,
					'main_duty_tel'		=> $main_duty_tel,
					'other_duty_name'	=> $other_duty_name,
					'other_duty_tel'	=> $other_duty_tel,
					'provide_services'	=> $provide_services,
					'remark'			=> $remark,
					'time'				=> time(),
					],'id=:id',[':id'=>$id]
			)->execute();
			if($result)
			{
				$returnArr['status'] = true;
				$returnArr['info'] = '修改成功！';
			}else{
				$returnArr['status'] = false;
				$returnArr['info'] = '修改失败！';
			}
	
	
			return json_encode($returnArr);
	
		}

		$id = yii::$app->request->get('id');
		$result = (new \yii\db\Query())->from('oa_service_site')->where('id=:id',[':id'=>$id])->one();
		//foreach($result as $key1 => $val1) {
		//授权品牌	
		$arr=explode(",",$result['brand_id']);
		//var_dump($result['brand_id']);exit;
		//echo '<pre>';
		//var_dump($arr);exit;
		$arr_brand_name = [];
		foreach($arr as $key3 => $value3){
			//echo '<pre>';
			//$arr_brand_name .= $value2;
			$arr_brand = CarBrand::find()
			->select(['name'])
			->andWhere(['`id`'=>$value3])->asArray()->one();

			$arr_brand_name[] = trim($arr_brand['name']);
		}

		$result['arr_brand_name'] = $arr_brand_name;
		//************************座机号码*****************************
		$result['landline'] = explode("-",$result['landline']);
		$result['landline1'] = $result['landline'][0];
		$result['landline2'] = $result['landline'][1];
		$result['landline3'] = $result['landline'][2];
		//echo '<pre>';
		//var_dump($result['landline']);exit;

		$connection = yii::$app->db3;
		$provinces = $connection->createCommand(
				"select * from zc_region where region_type=1"
		)->queryAll();
		$citys = $connection->createCommand(
				"select * from zc_region where region_type=2 and parent_id={$result['province_id']}"
				)->queryAll();
		$countys = $connection->createCommand(
				"select * from zc_region where region_type=3 and parent_id={$result['city_id']}"
				)->queryAll();
		
		$result['provide_services'] = json_decode($result['provide_services'],true);
		//echo '<pre>';
		//var_dump($result);exit;	
		
		/*if(in_array('上汽大通', $result['arr_brand_name'])){
			echo '12345';exit;
		}*/
		return $this->render('edit',['provinces'=>$provinces,'citys'=>$citys,'countys'=>$countys,'result'=>$result]);
	}
	
	public function actionDel()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$id = yii::$app->request->post('id');
			$db = \Yii::$app->db;
			$result = $db->createCommand()->delete('oa_service_site','id=:id',[':id'=>$id])->execute();
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
	
	public function actionInfo()
	{
		$id = yii::$app->request->get('id');
		
		$result = (new \yii\db\Query())->from('oa_service_site')->where('id=:id',[':id'=>$id])->one();
		
		/*switch ($result['type']){
			case 1:
				$result['type'] = '厂家特约服务站';
				break;
			case 2:
				$result['type'] = '我方合作服务站';
				break;
			case 3:
				$result['type'] = '厂家4S店/修理厂';
				break;
			case 4:
				$result['type'] = '其他类型';
				break;
		}*/
		
		/*switch ($result['level']){
			case 0:
				$result['level'] = '';
				break;
			case 1:
				$result['level'] = '一级';
				break;
			case 2:
				$result['level'] = '二级';
				break;
			case 3:
				$result['level'] = '三级';
				break;
		}*/
		switch ($result['type2']){
			case 1:
				$result['type2'] = '4S店';
				break;
			case 2:
				$result['type2'] = '二类';
				break;
			case 3:
				$result['type2'] = '三类';
				break;
			case 4:
				$result['type2'] = '其他';
				break;
					}

		switch ($result['team_type']){
			case 1:
				$result['team_type'] = '有合作协议';
				break;
			case 2:
				$result['team_type'] = '无合作协议';
				break;
		}

		$arr=explode(",",$result['brand_id']);
		//echo '<pre>';
		//var_dump($arr);exit;
		$arr_brand_name = '';
		foreach($arr as $key2 => $value2){
			//echo '<pre>';
			//$arr_brand_name .= $value2;
			$arr_brand = CarBrand::find()
			->select(['name'])
			->andWhere(['`id`'=>$value2])->asArray()->one();

			$arr_brand_name .= $arr_brand['name'].' ';

		}
		//echo 'm2';
		//echo '<pre>';
		//var_dump($arr_brand_name);exit;
		$result['arr_brand_name'] = $arr_brand_name;


		//地址
		$connection = yii::$app->db3;
		$provinces = $connection->createCommand(
				"select * from zc_region where region_type=1 and region_id={$result['province_id']}"
				)->queryOne();
		$citys = $connection->createCommand(
				"select * from zc_region where region_type=2 and region_id={$result['city_id']}"
				)->queryOne();
		$countys = $connection->createCommand(
				"select * from zc_region where region_type=3 and region_id={$result['county_id']}"
				)->queryOne();
		$result['addr'] = $provinces['region_name'].$citys['region_name'].$countys['region_name'].$result['address'];
		
		//提供服务
		$provide_services = json_decode($result['provide_services'],true);
		$str = '';
		foreach ($provide_services as $provide_service)
		{
			$str .= $provide_service.' ';
		}
		$result['provide_services'] = $str;
		
		return $this->render('info',['result'=>$result]);
	}
	
	
	public function actionExport()
	{
		
		$query = (new \yii\db\Query())->select('oa_service_site.*,cs_car_brand.name as brand_name')->from('oa_service_site');
		
		//站点名称
 		$site_name = yii::$app->request->post('site_name');
		if($site_name)
		{
			$query->andWhere('site_name=:site_name',[':site_name'=>$site_name]);
		}
		//类型
		$type = yii::$app->request->post('type');
		if($type)
		{
			$query->andWhere('type=:type',[':type'=>$type]);
		}
		//所属厂商
		$brand_id= yii::$app->request->post('brand_id');
		if($brand_id)
		{
			$query->andWhere('brand_id=:brand_id',[':brand_id'=>$$brand_id]);
		}
		//省
		$province_id = yii::$app->request->post('province_id');
		if($province_id)
		{
			$query->andWhere('province_id=:province_id',[':province_id'=>$province_id]);
		}
		//市
		$city_id = yii::$app->request->post('city_id');
		if($city_id)
		{
			$query->andWhere('city_id=:city_id',[':city_id'=>$city_id]);
		}
		//地区/县
		$county_id = yii::$app->request->post('county_id');
		if($county_id)
		{
			$query->andWhere('county_id=:county_id',[':county_id'=>$county_id]);
		}
		
		$result = $query->join('LEFT JOIN','cs_car_brand','cs_car_brand.id =oa_service_site.brand_id')->all();
		if($result){
			foreach ($result as $key=>$val)
			{
				switch ($val['type']){
					case 1:
						$result[$key]['type'] = '厂家特约服务站';
						break;
					case 2:
						$result[$key]['type'] = '我方合作服务站';
						break;
					case 3:
						$result[$key]['type'] = '厂家4S店/修理厂';
						break;
					case 4:
						$result[$key]['type'] = '其他类型';
						break;
				}
		
				switch ($val['level']){
					case 0:
						$result[$key]['level'] = '';
						break;
					case 1:
						$result[$key]['level'] = '一级';
						break;
					case 2:
						$result[$key]['level'] = '二级';
						break;
					case 3:
						$result[$key]['level'] = '三级';
						break;
				}

				switch ($val['type2']){
						case 1:
							$result[$key]['type2'] = '4S店';
							break;
						case 2:
							$result[$key]['type2'] = '二类';
							break;
						case 3:
							$result[$key]['type2'] = '三类';
							break;
						case 4:
							$result[$key]['type2'] = '其他';
							break;
					}
				$arr=explode(",",$val['brand_id']);
				//echo '<pre>';
				//var_dump($arr);exit;
				$arr_brand_name = '';
				foreach($arr as $key2 => $value2){
					//echo '<pre>';
					//$arr_brand_name .= $value2;
					$arr_brand = CarBrand::find()
    				->select(['name'])
    				->andWhere(['`id`'=>$value2])->asArray()->one();

					$arr_brand_name .= $arr_brand['name'].' ';

				}
				//echo 'm2';
				//echo '<pre>';
				//var_dump($arr_brand_name);exit;
				$result[$key]['arr_brand_name'] = $arr_brand_name;

				switch ($val['team_type']){
					case 1:
						$result[$key]['team_type'] = '有合作协议';
						break;
					case 2:
						$result[$key]['team_type'] = '无合作协议';
						break;
				}


				//地址
				$connection = yii::$app->db3;
				$provinces = $connection->createCommand(
						"select * from zc_region where region_type=1 and region_id={$val['province_id']}"
						)->queryOne();
				$citys = $connection->createCommand(
						"select * from zc_region where region_type=2 and region_id={$val['city_id']}"
						)->queryOne();
				$countys = $connection->createCommand(
						"select * from zc_region where region_type=3 and region_id={$val['county_id']}"
						)->queryOne();
				$result[$key]['addr'] = $provinces['region_name'].$citys['region_name'].$countys['region_name'].$val['address'];
		
				//提供服务
				$provide_services = json_decode($val['provide_services'],true);
				$str = '';
				foreach ($provide_services as $provide_service)
				{
					$str .= $provide_service.' ';
				}
				$result[$key]['provide_services'] = $str;
			}
		}
		
		
		$filename = '售后修理厂管理.csv'; //设置文件名
		$str = "修理厂名称,修理厂分类,授权品牌,合作方式,负责人,手机,提供服务类型,备注\n";
		foreach ($result as $row){
			$addr 				= str_replace('，', ',', $row['addr']);
			$provide_services 	= str_replace('，', ',', $row['provide_services']);
			$remark 			= str_replace('，', ',', $row['remark']);
			$str .="{$row['site_name']},{$row['type2']},{$row['arr_brand_name']},{$row['team_type']},{$row['main_duty_name']},{$row['main_duty_tel']},{$provide_services},{$remark}"."\n";
		}
		$str = mb_convert_encoding($str, "GBK", "UTF-8");
		$this->export_csv($filename,$str); //导出
	}
	
	function export_csv($filename,$data)
	{
		$filename = mb_convert_encoding($filename, "GBK","UTF-8");
		//		header("Content-type: text/html; charset=utf-8");
		header("Content-type:text/csv;charset=GBK");
		header("Content-Disposition:attachment;filename=".$filename);
		header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
		header('Expires:0');
		header('Pragma:public');
		//	header("Content-Length:".strlen($data));
		echo $data;
	}
}