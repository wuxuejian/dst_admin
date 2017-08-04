<?php
/**
 * 提车地点
 * @author Administrator
 *
 */
namespace backend\modules\process\controllers;
use backend\controllers\BaseController;
use yii;
use yii\data\Pagination;
use backend\models\RbacRole;
use backend\models\ChargeStation;
use backend\models\Admin;
class ExtractCarSiteController extends BaseController
{
	public function actionIndex()
	{
		//echo 'mm';exit;
		/*if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$db = new \yii\db\Query();
			$query = $db->select('
					oa_extract_car_site.id,										
					oa_extract_car_site.name as site_name,
					cs_operating_company.name as company_name					
					')->from('oa_extract_car_site')->where('parent_id=0 and oa_extract_car_site.is_del=0');
			$query->join('LEFT JOIN','cs_operating_company','oa_extract_car_site.operating_company_id=cs_operating_company.id');
			$pageSize = isset($_POST['rows']) && $_POST['rows'] <= 50 ? intval($_POST['rows']) : 10;
			//排序字段
			$sort = yii::$app->request->post('sort');
			$order = yii::$app->request->post('order');  //asc|desc
			if($sort && $sort != 'name_and_tel')
			{
				$query->orderBy("{$sort} {$order}");
			}		
			$total = $query->count();
			$pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize,'page'=>$_POST['page']-1]);
			$result = $query->offset($pages->offset)->limit($pages->limit)->all();
			
			//遍历场站
			foreach ($result as $key=>$val)
			{
				$query = (new \yii\db\Query())->select('cs_admin.name,oa_extract_car_site.tel')->from('oa_extract_car_site')
				->where('oa_extract_car_site.is_del=0 and oa_extract_car_site.parent_id=:id',[':id'=>$val['id']]);
				$query->join('LEFT JOIN','cs_admin','cs_admin.id=oa_extract_car_site.user_id');
				$person_res = $query->all();
				foreach($person_res as $person) {
					if (!isset($result[$key]['name_and_tel'])) {
						$result[$key]['name_and_tel'] = "";
					}
					$result[$key]['name_and_tel'] .= $person['name']." ".$person['tel']."、";					
				}							
			}
			
			$returnArr = [];
						
			$returnArr['rows'] = $result;
			$returnArr['total'] = $total;
			return json_encode($returnArr);
		}
		$buttons = $this->getCurrentActionBtn();
		
		return $this->render('index',['buttons'=>$buttons]);*/
		$name = yii::$app->request->post('name');
		$province_id = yii::$app->request->post('province_id');
		$city_id = yii::$app->request->post('city_id');
		$county_id = yii::$app->request->post('county_id');
		$operating_company_id = yii::$app->request->post('operating_company_id');
		$use_name = yii::$app->request->post('use_name');
		$is_sta = yii::$app->request->post('is_sta');
		//var_dump($is_sta);exit;
	
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$db = new \yii\db\Query();
			$query = $db->select('
					oa_extract_car_site.*,										
					cs_admin.name as user_name,
					cs_operating_company.name as company_name,
					cs_charge_station.cs_name as sta_name					
					')->from('oa_extract_car_site')->where('parent_id=0 and oa_extract_car_site.is_del=0');
			$query->join('LEFT JOIN','cs_operating_company','oa_extract_car_site.operating_company_id=cs_operating_company.id');
			$query->join('LEFT JOIN','cs_admin','oa_extract_car_site.user_id=cs_admin.id');
			$query->join('LEFT JOIN','cs_charge_station','oa_extract_car_site.sta_id=cs_charge_station.cs_id');

			$pageSize = isset($_POST['rows']) && $_POST['rows'] <= 50 ? intval($_POST['rows']) : 10;
			
			//var_dump($name);exit;
			if($name){
            //echo '123';exit;
            $query->andFilterWhere([
                    'like',
                    '{{oa_extract_car_site}}.name',
                    $name
                    ]);
       		}

       		if($province_id)
			{
				//$query->andWhere('province_id=:province_id',[':province_id'=>$province_id]);
				 $query->andFilterWhere([
                    '=',
                    '{{oa_extract_car_site}}.province_id',
                    $province_id
                    ]);
			}
			//市
			
			if($city_id)
			{
				//$query->andWhere('city_id=:city_id',[':city_id'=>$city_id]);
				$query->andFilterWhere([
                    '=',
                    '{{oa_extract_car_site}}.city_id',
                    $city_id
                    ]);
			}
			//地区/县
			
			if($county_id)
			{
				//$query->andWhere('county_id=:county_id',[':county_id'=>$county_id]);
				$query->andFilterWhere([
                    '=',
                    '{{oa_extract_car_site}}.county_id',
                    $county_id
                    ]);
			}

			if($operating_company_id)
			{
				//$query->andWhere('county_id=:county_id',[':county_id'=>$county_id]);
				$query->andFilterWhere([
                    '=',
                    '{{oa_extract_car_site}}.operating_company_id',
                    $operating_company_id
                    ]);
			}
			//var_dump($use_name);exit;
			if($use_name)
			{
				//echo '123';exit;
				
				$query->andFilterWhere([
                    'like',
                    '{{cs_admin}}.name',
                    $use_name
                    ]);
			}
			if($is_sta==1 || $is_sta==0)
			{
				//echo '123';exit;
				$query->andFilterWhere([
                    '=',
                    '{{oa_extract_car_site}}.is_sta',
                    $is_sta
                    ]);
			}

			//排序字段
			$sort = yii::$app->request->post('sort');
			$order = yii::$app->request->post('order');  //asc|desc
			if($sort && $sort != 'name_and_tel')
			{
				$query->orderBy("{$sort} {$order}");
			}		
			$total = $query->count();
			$pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize,'page'=>$_POST['page']-1]);
			$result = $query->offset($pages->offset)->limit($pages->limit)->all();
			
			//遍历场站
			foreach ($result as $key=>$val)
			{
				$query = (new \yii\db\Query())->select('cs_admin.name,oa_extract_car_site.tel')->from('oa_extract_car_site')
				->where('oa_extract_car_site.is_del=0 and oa_extract_car_site.parent_id=:id',[':id'=>$val['id']]);
				$query->join('LEFT JOIN','cs_admin','cs_admin.id=oa_extract_car_site.user_id');
				$person_res = $query->all();
				foreach($person_res as $person) {
					if (!isset($result[$key]['name_and_tel'])) {
						$result[$key]['name_and_tel'] = "";
					}

					$result[$key]['name_and_tel'] .= $person['name']." ".$person['tel']."、";	

					//var_dump($result[$key]['name_and_tel']);exit;

				}
				$arra = [];
				$arra = explode('、',$result[$key]['name_and_tel'],2);
				$result[$key]['name_and_tel'] = $arra[0];
				$result[$key]['name2_and_tel'] = $arra[1];
				
				if($val['sta_id']==0 || !isset($val['sta_id'])) {
					$result[$key]['sta_rel'] = '无';
				} else {
					$result[$key]['sta_rel'] = '有';
				}

			}
			//地址
			/*$connection = yii::$app->db3;
			$provinces = $connection->createCommand(
					"select * from zc_region where region_type=1 and region_id={$val['province_id']}"
			)->queryOne();
			$citys = $connection->createCommand(
					"select * from zc_region where region_type=2 and region_id={$val['city_id']}"
					)->queryOne();
			$countys = $connection->createCommand(
					"select * from zc_region where region_type=3 and region_id={$val['county_id']}"
					)->queryOne();
			$result[$key]['addr'] = $provinces['region_name'].$citys['region_name'].$countys['region_name'].$val['address'];*/
			//echo '<pre>';
			
			$returnArr = [];
						
			$returnArr['rows'] = $result;
			$returnArr['total'] = $total;
			return json_encode($returnArr);
		}
		$buttons = $this->getCurrentActionBtn();
		$connection = yii::$app->db3;
		$provinces = $connection->createCommand("select * from zc_region where region_type=1")->queryAll();
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
		
		return $this->render('index',['buttons'=>$buttons,'provinces'=>$provinces,'searchFormOptions'=>$searchFormOptions]);
	}
	
	//获取所有场站
	public function actionGetList(){
		//echo 'mm2';exit;
		$connection = yii::$app->db;
		$sql = 'select * from oa_extract_car_site where parent_id=0 and is_del=0';
		$data = $connection->createCommand($sql)->queryAll();
		echo json_encode($data);
	}
	
	public function actionAdd()
	{
		//echo 'm1';exit;
		/*if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			
			$parent_id = yii::$app->request->post('parent_id');
			$arr_user_id = yii::$app->request->post('user_id');
			$arr_tel = yii::$app->request->post('tel');

			$the_model = array();
			foreach ($arr_user_id as $key => $user_id) {
				
				$the_model[] = array($user_id,$parent_id,$arr_tel[$key]);
				
			}

			$db = \Yii::$app->db;
			$result = $db->createCommand()->batchInsert(
					'oa_extract_car_site',
					['user_id','parent_id','tel'],
					$the_model
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
		$sites = (new \yii\db\Query())->from('oa_extract_car_site')->where('is_del=0 AND parent_id=0')->all();
		$users = (new \yii\db\Query())->from('cs_admin')->where('is_del=0')->all();
		return $this->render('add',['sites'=>$sites,'users'=>$users]);*/
		$connection = yii::$app->db;
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			//$arr = [];
			
				$name = yii::$app->request->post('name');//场站名称
				$operating_company_id = yii::$app->request->post('operating_company_id');//运营公司
				$province_id = yii::$app->request->post('province_id');//省份
				$city_id = yii::$app->request->post('city_id');//城市
				$county_id = yii::$app->request->post('county_id');//区/县
				$address = yii::$app->request->post('address');//详细地址
				$sta_id = yii::$app->request->post('sta_name');//关联充电站

				$arr_user_id = yii::$app->request->post('user_id');//场站负责人
				$arr_tel = yii::$app->request->post('tel');//电话号码
				$sta_photo = yii::$app->request->post('sta_img');//图片
				//$sta_name = yii::$app->request->post('sta_name');//是否关联充电站
				if($sta_id!=0 && isset($sta_id)) {
					$sta_name = 1;//是否关联充电站
				} 
				if($sta_id==0 || !isset($sta_id)) {
					$sta_name = 0;//是否关联充电站
				} 
				//var_dump($arr_user_id);
				//var_dump($arr_tel);exit;
				

				$result1 = $connection->createCommand()->insert('oa_extract_car_site',[
					'name'=>$name,
					'operating_company_id'=>$operating_company_id,
					'province_id'=>$province_id,
					'city_id'=>$city_id,
					'county_id'=>$county_id,
					'address'=>$address,
					'sta_id'=>$sta_id,
					//'user_id'=>$arr_user_id[0],//主负责人
					//'tel'=>$arr_tel[0],
					'sta_photo'=>$sta_photo,
					'is_sta'=>$sta_name,

				])->execute();
				
				$new_id = yii::$app->db->getLastInsertID();
				
				//获取主负责人id，插入其他负责人
				$new_r = (new \yii\db\Query())->from('oa_extract_car_site')->select(['id'])->where(['id'=>$new_id])->one();

				$parent_id = $new_r['id'];
			
				$the_model = array();
				foreach ($arr_user_id as $key => $user_id) {
					$the_model[] = array($user_id,$parent_id,$arr_tel[$key]);
				}
				//echo '<pre>';
				//var_dump($the_model);exit;
				$result2 = $connection->createCommand()->batchInsert('oa_extract_car_site',
					
					['user_id','parent_id','tel'],
					$the_model
					
				)->execute();
			

				if($result1 && $result2){
					$returnArr['status'] = true;
		    		$returnArr['info'] = '添加成功!';
				} else {
					$returnArr['status'] = false;
			    	$returnArr['info'] = '添加失败!';	
				}
				return json_encode($returnArr);
			

		}
		$sites = (new \yii\db\Query())->from('oa_extract_car_site')->where('is_del=0 AND parent_id=0')->all();
		$users = (new \yii\db\Query())->from('cs_admin')->where('is_del=0')->all();
		//身份
		$connection = yii::$app->db3;
		$provinces = $connection->createCommand(
				"select * from zc_region where region_type=1"
		)->queryAll();
		//充电站
		$sta_s = (new \yii\db\Query())->from('cs_charge_station')->select(['cs_id','cs_name'])->where('cs_is_del=0')->all();
		//echo '<pre>';
		//var_dump($sta_s);exit;

		return $this->render('add',['sites'=>$sites,'users'=>$users,'provinces'=>$provinces,'sta_s'=>$sta_s]);
	}
	
	public function actionEdit()
	{
		
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$id = yii::$app->request->post('id');			
			$del_ids = yii::$app->request->post('del_ids');			
			$parent_id = yii::$app->request->post('parent_id');
			
			$arr_person_id = yii::$app->request->post('person_id');//接受的是负责人，来自result
			$arr_user_id = yii::$app->request->post('user_id');//
			$arr_tel = yii::$app->request->post('tel');
			$arr_user2_id = yii::$app->request->post('user2_id');
			$arr_tel2 = yii::$app->request->post('tel2');

			$name = yii::$app->request->post('name');//站点
			$operating_company_id = yii::$app->request->post('operating_company_id');//所属运营公司ID
			$province_id = yii::$app->request->post('province_id');//省份
			$city_id = yii::$app->request->post('city_id');//城市
			$county_id = yii::$app->request->post('county_id');//区/县
			$address = yii::$app->request->post('address');//详细地址
			$sta_photo = yii::$app->request->post('sta_photo');//场站照片
			$sta_id = yii::$app->request->post('sta_rel');//关联充电站id
			//sta_rel
			//$sta_rel = yii::$app->request->post('sta_rel');//是否关联充电站
			if($sta_id!=0 && isset($sta_id)) {
					$sta_rel = 1;
				}
			if($sta_id==0 || !isset($sta_id)) {
					$sta_rel = 0;
				}
						
			$db = \Yii::$app->db;
			try {
				if (isset($del_ids) && $del_ids != ""){				
					//删除负责人
					$result = $db->createCommand()->update('oa_extract_car_site',
														  ['is_del' => 1],
														  "id IN ($del_ids)")->execute();
				}
				foreach ($arr_person_id as $key => $person_id) {
					$result = $db->createCommand()->update('oa_extract_car_site',
						['user_id'		=> $arr_user_id[$key],
						'parent_id'	=> $id,//'parent_id'	=> $parent_id,
						'tel'		=> $arr_tel[$key],
						],'id=:id',[':id'=>$person_id]
					)->execute();

					//父数据
					$result2 = $db->createCommand()->update('oa_extract_car_site',
						[
						'name'=>$name,
						'operating_company_id'=>$operating_company_id,
						'province_id'=>$province_id,
						'city_id'=>$city_id,
						'county_id'=>$county_id,
						'address'=>$address,
						'sta_photo'=>$sta_photo,
						'sta_id'=>$sta_id,
						'is_sta'=>$sta_rel,

						],'id=:id',[':id'=>$id]
					)->execute();
				}

				if($arr_user2_id) {//修改时添加其他负责人
					foreach ($arr_user2_id as $key2 => $value2) {
						$result3 = $db->createCommand()->insert('oa_extract_car_site',
							['user_id' => $value2,'tel' => $arr_tel2[$key2],'parent_id' => $id]
							)->execute();
					}
				}
				$returnArr['status'] = true;
				$returnArr['info'] = '修改成功！';
			} catch (Exception $e) {
				$returnArr['status'] = false;
				$returnArr['info'] = '修改失败！';
			}

			return json_encode($returnArr);
		}
		$id = yii::$app->request->get('id');
		$the_site = (new \yii\db\Query())->from('oa_extract_car_site')->where('is_del=0 and id=:id',[':id'=>$id])->one();//本场站
		//echo '<pre>';
		//var_dump($the_site);exit;
		$result = (new \yii\db\Query())->from('oa_extract_car_site')->where('is_del=0 and parent_id=:id',[':id'=>$id])->all();//本场站负责人结果集
		$sites = (new \yii\db\Query())->from('oa_extract_car_site')->where('is_del=0 AND parent_id=0')->all();//所有场站选项
		$users = (new \yii\db\Query())->from('cs_admin')->where('is_del=0')->all();//所有负责人选项

		$the_site['sta_name'] = (new \yii\db\Query())->from('cs_charge_station')->select(['cs_name'])->where(['cs_id'=>$the_site['sta_id']])->one();

		$sta_s = (new \yii\db\Query())->from('cs_charge_station')->select(['cs_id','cs_name'])->where('cs_is_del=0')->all();//查询所有充电站
	//echo '<pre>';
//var_dump($the_site);exit;
		$connection = yii::$app->db3;
		$provinces = $connection->createCommand(
				"select * from zc_region where region_type=1"
		)->queryAll();
		$citys = $connection->createCommand(
				//"select * from zc_region where region_type=2 and parent_id={$the_site['province_id']}"
				"select * from zc_region where region_type=2 "
				)->queryAll();
		$countys = $connection->createCommand(
				//"select * from zc_region where region_type=3 and parent_id={$the_site['city_id']}"
				"select * from zc_region where region_type=3 "
				)->queryAll();

		//echo '<pre>';
		//var_dump($result);exit;
		return $this->render('edit',['result'=>$result,'sites'=>$sites,'users'=>$users,'the_site'=>$the_site,'sta_s'=>$sta_s,'provinces'=>$provinces,'citys'=>$citys,'countys'=>$countys]);
	}
	
	public function actionDel()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$id = yii::$app->request->post('id');
			$db = \Yii::$app->db;
			$result = $db->createCommand()->update('oa_extract_car_site',['is_del'=>1],'id=:id',[':id'=>$id])->execute();
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
	
	public function actionSite()
	{
		//echo 'm2';exit;
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			//echo 'm44';exit;
			$db = new \yii\db\Query();
			$query = $db->select('oa_extract_car_site.*,cs_operating_company.name as company_name')->from('oa_extract_car_site')->where('parent_id=0 and oa_extract_car_site.is_del=0');
			$pageSize = isset($_POST['rows']) && $_POST['rows'] <= 50 ? intval($_POST['rows']) : 10;
			//排序字段
			$sort = yii::$app->request->post('sort');
			$order = yii::$app->request->post('order');  //asc|desc
			if($sort)
			{
				$query->orderBy("{$sort} {$order}");
			}
			$query->join('LEFT JOIN','cs_operating_company','oa_extract_car_site.operating_company_id=cs_operating_company.id');
			$total = $query->count();
			$pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize,'page'=>$_POST['page']-1]);
			$result = $query->offset($pages->offset)->limit($pages->limit)->all();
			$returnArr = [];
			$returnArr['rows'] = $result;
			$returnArr['total'] = $total;
			return json_encode($returnArr);
		}
		$buttons = $this->getCurrentActionBtn();
		return $this->render('site',['buttons'=>$buttons]);
		//return $this->render('site');
	}
	
	public function actionAddSite()
	{
		//echo 'm33';exit;
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			//echo '456';exit;
			$name = yii::$app->request->post('name');
			$operating_company_id = yii::$app->request->post('operating_company_id');
			
			$db = \Yii::$app->db;
			$result = $db->createCommand()->insert('oa_extract_car_site',
					['name'		            => $name,
					'operating_company_id'	=> $operating_company_id,
					])->execute();
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
		$operating_company = (new \yii\db\Query())->from('cs_operating_company')->where('is_del=0')->all();
		return $this->render('add-site',['operating_company'=>$operating_company]);
	}
	
	public function actionEditSite()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$id = yii::$app->request->post('id');
			$name = yii::$app->request->post('name');
			$operating_company_id = yii::$app->request->post('operating_company_id');
			
			$db = \Yii::$app->db;
			
			try {
				$result = $db->createCommand()->update('oa_extract_car_site',
						['name'		            => $name,
						'operating_company_id'	=> $operating_company_id,
						],'id=:id',[':id'=>$id]
				)->execute();
				
				$returnArr['status'] = true;
				$returnArr['info'] = '修改成功！';
			} catch (Exception $e) {
				$returnArr['status'] = false;
				$returnArr['info'] = '修改失败！';
			}
			return json_encode($returnArr);
		}
		
		$id = yii::$app->request->get('id');
		$result = (new \yii\db\Query())->from('oa_extract_car_site')->where('id=:id',[':id'=>$id])->one();
		
		$operating_company = (new \yii\db\Query())->from('cs_operating_company')->where('is_del=0')->all();
		return $this->render('edit-site',['operating_company'=>$operating_company,'result'=>$result]);
	}
	
	public function actionDelSite()
	{
		
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$id = yii::$app->request->post('id');
			$db = \Yii::$app->db;
			$result = $db->createCommand()->update('oa_extract_car_site',['is_del'=>1],'id=:id',[':id'=>$id])->execute();
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

	//通过选择负责人，查询出手机号码
	public function actionCheck()
	{
		//echo '4569';exit;
		$id = yii::$app->request->post('id');
        $tel_user = Admin::find()
            ->select([
               
                'cs_admin.telephone',
                ])
            ->where(['cs_admin.is_del'=>0,'cs_admin.id'=>$id])
            ->asArray()
            ->one();
      //var_dump($tel_user);exit;
        return json_encode($tel_user);
	}
}