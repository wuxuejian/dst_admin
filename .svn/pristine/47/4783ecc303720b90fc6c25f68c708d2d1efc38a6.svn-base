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

class PartsInstockController extends BaseController
{
    public function actionIndex()
    {
        $buttons = $this->getCurrentActionBtn();
//        $configItems = ['part_type','part_kind'];
//        $config = (new ConfigCategory)->getCategoryConfig($configItems,'value');
        //车辆类型
        $searchFormOptions = [];
        //配件名称
        $query = (new \yii\db\Query())
            ->select('parts_id,parts_name')
            ->from('cs_parts_info')
            ->distinct('parts_name');
        $parts_name = $query->all();
        if($parts_name){
//            $searchFormOptions['parts_name'][] = ['value'=>'0','text'=>'不限','selected'=>'selected'];
            foreach($parts_name as $val){
                $searchFormOptions['parts_name'][] = ['value'=>$val['parts_name'],'text'=>$val['parts_name']];
            }
        }
        //配件类别
        $query = (new \yii\db\Query())
            ->select('id,parts_name,parents_id')
            ->from('cs_parts_kind')
            ->where(['is_del'=>'0','parents_id'=>'0']);
        $searchFormOptions['parts_type'] = $query->all();
        //大区
        $daqu_query = (new \yii\db\Query())
            ->select('id,region_name')
            ->from('cs_op_region')
            ->where(['is_del'=>0]);
        $data_daqu = $daqu_query->all();
        foreach ($data_daqu as $k=>$v){
            $daqu[] = ['id'=>$v['id'],'name'=>$v['region_name']];
        }
        return $this->render('index',['buttons'=>$buttons,'searchFormOptions'=>$searchFormOptions,'daqu'=>$daqu]);
    }

    public function actionAdd()
    {
        if(yii::$app->request->isPost){
            $data = $_POST;
            if(!$data['parts_id']){
                $msg['status'] = 2;
                $msg['info'] = '出现异常，请重新选择配件!';
                echo json_encode($msg);die;
            }
            $original_from = isset($_REQUEST['original_from']) ? trim($_REQUEST['original_from']) : '';
            $factory = isset($_REQUEST['factory']) ? trim($_REQUEST['factory']) : '';
            if( addslashes($data['s_province_add'])=='' or
                addslashes($data['s_city_add'])=='' or
                addslashes($data['s_county_add'])=='' or
                addslashes($data['shop_price'])=='' or
                addslashes($data['in_number'])=='' or
                addslashes($data['expiration_date'])=='' or
                addslashes($data['warranty_date'])=='' or
                addslashes($data['match_car'])=='' or
                addslashes($original_from)=='' or
                addslashes($factory)=='' or
                addslashes(strtotime($data['under_in_warehouse_time']))==''
            ){
                $msg['status'] = 2;
                $msg['info'] = '您还有数据未填写!';
                echo json_encode($msg);die;
            }
            if(preg_match('/[\x7f-\xff]/', $data['shop_price'])){
                $returnArr['status'] = 2;
                $returnArr['info'] = '采购单价不能输入中文!';
                return json_encode($returnArr);
            }
            if($data['in_number'] <= 0){
                $msg['status'] = 2;
                $msg['info'] = '入库数量应大于0!';
                echo json_encode($msg);die;
            }
            $data['out_price'] = bcmul($data['shop_price'],1.3,2);
                $result = Yii::$app->db->createCommand()->insert('cs_parts_in', [
                    'warehouse_address'            =>trim($data['s_county_add']),
                    'shop_price'                   =>trim($data['shop_price']),
                    'out_price'                    =>trim($data['out_price']),
                    'in_number'                    =>trim($data['in_number']),
                    'standard'                     =>trim($data['standard']),
                    'parts_model'                  =>trim($data['parts_model']),
                    'param'                        =>trim($data['param']),
                    'expiration_date'              =>trim($data['expiration_date']),
                    'warranty_date'                =>trim($data['warranty_date']),
                    'match_car'                    =>trim($data['match_car']),
                    'original_from'                =>trim($original_from),
                    'original_from_company'        =>trim($data['original_from_company']),
                    'original_from_code'           =>trim($data['original_from_code']),
                    'factory'                      =>trim($factory),
                    'product_company'              =>trim($data['product_company']),
                    'product_company_code'         =>trim($data['product_company_code']),
                    'under_in_warehouse_time'      =>trim(strtotime($data['under_in_warehouse_time'])),
                    'on_registrant'                =>trim($_SESSION['backend']['adminInfo']['id']),
                    'on_registrant_date'           =>time(),
                    'info_parts_id'                =>trim($data['parts_id']),
                    'is_del'                       =>0,
                    'storage_quantity'             =>trim($data['in_number']),
                    'region'                       =>trim($data['s_province_add']),
                    'operating_company_id'         =>trim($data['s_city_add'])
                ])->execute();
                $query = (new \yii\db\Query())
                    ->select('id,parts_info_id,storage_id,storage_quantity')
                    ->from('cs_parts_storage');
                $query->andFilterWhere(['=', 'parts_info_id',$data['parts_id']]);
                $query->andFilterWhere(['=', 'storage_id',$data['s_county_add']]);
                $query->andFilterWhere(['=', 'is_del','0']);
                $dat = $query->one();
                if($dat){
                    $res = Yii::$app->db->createCommand()->update('cs_parts_storage', [
                        'storage_quantity' => trim($data['in_number'])+$dat['storage_quantity'],
                    ],"id=$dat[id]")->execute();
                }else{
                    $res = Yii::$app->db->createCommand()->insert('cs_parts_storage', [
                        'parts_info_id'                =>trim($data['parts_id']),
                        'storage_id'                   =>trim($data['s_county_add']),
                        'storage_quantity'             =>trim($data['in_number']),
                        'region'                       =>trim($data['s_province_add']),
                        'operating_company_id'         =>trim($data['s_city_add']),
                        'is_del'                       =>0
                    ])->execute();
                }
            if($res){
                $msg['status'] = 1;
                $msg['info'] = '添加成功!';
                echo json_encode($msg);die;
            }else{
                $msg['status'] = 0;
                $msg['info'] = '添加失败!';
                echo json_encode($msg);die;
            }
        }
        $query = (new \yii\db\Query())
            ->select('id,name')
            ->from('cs_operating_company');
        $company = $query->all();
        //大区
        $daqu=[['name'=>"华南"],['name'=>"华北"],['name'=>"华东"],['name'=>"西南"],['name'=>"华中"]];
        $query = (new \yii\db\Query())
            ->select('id,name')
            ->from('oa_extract_car_site');
        //仓库编码
        $warehouse_address = $query->all();
        //车辆类型
        $searchFormOptions = [];
        //配件类别
        $query = (new \yii\db\Query())
            ->select('id,parts_name,parents_id')
            ->from('cs_parts_kind')
            ->where(['is_del'=>'0','parents_id'=>'0']);
        $searchFormOptions['parts_type'] = $query->all();
        //配件名称
        $query = (new \yii\db\Query())
            ->select('parts_id,parts_name')
            ->from('cs_parts_info')
            ->distinct('parts_name');
        $parts_name = $query->all();
        if($parts_name){
//            $searchFormOptions['parts_name'][] = ['value'=>'0','text'=>'不限','selected'=>'selected'];
            foreach($parts_name as $val){
                $searchFormOptions['parts_name'][] = ['value'=>$val['parts_name'],'text'=>$val['parts_name']];
            }
        }
        return $this->render('add',['daqu'=>$daqu,'company'=>$company,'warehouse_address'=>$warehouse_address,'searchFormOptions'=>$searchFormOptions]);
    }

    public function actionEdit()
    {
        $insert_id = isset($_REQUEST['insert_id']) ? trim($_REQUEST['insert_id']) : '';
        $parts_id = isset($_REQUEST['parts_id']) ? trim($_REQUEST['parts_id']) : '';
        if(yii::$app->request->isPost){
            $parts_in_id = isset($_REQUEST['parts_in_id']) ? trim($_REQUEST['parts_in_id']) : '';
            $data = $_POST;
            if(!$data['parts_id']){
                $msg['status'] = 2;
                $msg['info'] = '出现异常，请重新选择配件!';
                echo json_encode($msg);die;
            }
            if( addslashes($data['shop_price'])=='' or
                addslashes($data['in_number'])=='' or
                addslashes($data['expiration_date'])=='' or
                addslashes($data['warranty_date'])=='' or
                addslashes($data['match_car'])=='' or
                addslashes($data['original_from'])=='' or
                addslashes($data['factory'])=='' or
                addslashes(strtotime($data['under_in_warehouse_time']))==''
            ){
                $msg['status'] = 2;
                $msg['info'] = '您还有数据未填写!';
                echo json_encode($msg);die;
            }
            if(preg_match('/[\x7f-\xff]/', $data['shop_price'])){
                $returnArr['status'] = 2;
                $returnArr['info'] = '采购单价不能输入中文!';
                return json_encode($returnArr);
            }
            $data['out_price'] = bcmul($data['shop_price'],1.3,2);
            //入库信息  核对入库数量
            $query = (new \yii\db\Query())
                ->select('a.*')
                ->from('cs_parts_in as a')
                ->where(['a.is_del'=>'0','insert_id'=>$parts_in_id]);
            $in_data = $query->one();
            if($data['in_number']<0){
                $msg['status'] = 2;
                $msg['info'] = '请输入大于0的数据!';
                echo json_encode($msg);die;
            }
            if(trim($data['in_number'])>$in_data['in_number']){
                $msg['status'] = 2;
                $msg['info'] = '你输入的数量大于库存了，请确定一下数量!';
                echo json_encode($msg);die;
            }
            $new_number = bcsub($in_data['in_number'],$data['in_number'],2);
            $result=Yii::$app->db->createCommand("update cs_parts_storage set storage_quantity = storage_quantity - ". $new_number ." 
                                                       where parts_info_id=".$data['parts_id']." and storage_id=".$in_data['warehouse_address'])->execute();
            $res=Yii::$app->db->createCommand("update cs_parts_in set in_number = ". $data['in_number'] .",storage_quantity = ". $data['in_number'] .",
                                                shop_price = ". $data['shop_price'] .",out_price = ". $data['out_price'] .",standard = '". $data['standard'] ."',
                                                parts_model = ' ". $data['parts_model'] ."',param = ' ". $data['param'] ."',expiration_date = ". $data['expiration_date'] .",
                                                warranty_date = ". $data['warranty_date'] .",match_car = '". $data['match_car'] ."',
                                                original_from = ' ". $data['original_from'] ."',original_from_company = ' ". $data['original_from_company'] ."',
                                                original_from_code = ' ". $data['original_from_code'] ."',factory = ' ". $data['factory'] ."',
                                                product_company = ' ". $data['product_company'] ."',product_company_code = ' ". $data['product_company_code'] ."',
                                                under_in_warehouse_time = ". strtotime($data['under_in_warehouse_time']) ."
                                                where info_parts_id=".$data['parts_id']." and warehouse_address=".$in_data['warehouse_address'])->execute();
            if($res){
                $msg['status'] = 1;
                $msg['info'] = '修改成功!';
                echo json_encode($msg);die;
            }else{
                $msg['status'] = 0;
                $msg['info'] = '修改失败!';
                echo json_encode($msg);die;
            }
        }
        //配件信息
        $query = (new \yii\db\Query())
            ->select('a.*,b.name as parts_car_brand,c.parts_name as parent_name,d.parts_name as son_name')
            ->from('cs_parts_info as a')
            ->where(['a.is_del'=>'0','parts_id'=>$parts_id])
            ->leftJoin('cs_car_brand b', 'a.car_brand = b.id')
            ->leftJoin('cs_parts_kind c', 'a.parts_type = c.id')
            ->leftJoin('cs_parts_kind d', 'a.parts_kind = d.id');
        $parts_info = $query->one();
        //入库信息
        $query = (new \yii\db\Query())
            ->select('a.*,b.id,b.name as site_name,c.id,c.name as company_name,')
            ->from('cs_parts_in as a')
            ->where(['a.is_del'=>'0','insert_id'=>$insert_id])
            ->leftJoin('oa_extract_car_site b', 'a.warehouse_address = b.id')
            ->leftJoin('cs_operating_company c', 'a.operating_company_id = c.id');
        $parts_in = $query->one();
        $parts_in['under_in_warehouse_time'] = date('Y-m-d H:i:s',$parts_in['under_in_warehouse_time']);
        return $this->render('edit',['parts_info'=>$parts_info,'parts_in'=>$parts_in]);
    }

    public function actionDel()
    {
        $insert_id = isset($_REQUEST['insert_id']) ? trim($_REQUEST['insert_id']) : '';
        $parts_id = isset($_REQUEST['parts_id']) ? trim($_REQUEST['parts_id']) : '';
        $query = (new \yii\db\Query())
            ->select('out_id,instock_id,use_person')
            ->from('cs_parts_out')
            ->where(['is_del'=>'0','instock_id'=>$insert_id]);
        $outData = $query->all();
        if($outData){
            $msg['status'] = 0;
            $msg['info'] = '删除失败,该配件已经出库!';
            echo json_encode($msg);die;
        }
        $query = (new \yii\db\Query())
            ->select('region,operating_company_id,warehouse_address,in_number,info_parts_id')
            ->from('cs_parts_in')
            ->where(['is_del'=>'0','insert_id'=>$insert_id]);
        $inData = $query->one();
        $db = \Yii::$app->db;
        $transaction = $db->beginTransaction();
        try{
            $result=Yii::$app->db->createCommand("update cs_parts_in set is_del = ". 1 ." where insert_id=".$insert_id)->execute();
            $res=Yii::$app->db->createCommand("update cs_parts_storage set storage_quantity = storage_quantity - ". $inData['in_number'] ." 
                                                       where parts_info_id=".$inData['info_parts_id']." and storage_id=".$inData['warehouse_address'])->execute();
            $transaction->commit();
        }catch (yii\db\Exception $e){
            $xinxi = $e->getMessage();
            $transaction->rollback();
        }
        if($result){
            $msg['status'] = 1;
            $msg['info'] = '删除成功!';
            echo json_encode($msg);die;
        }else{
            $msg['status'] = 0;
            $msg['info'] = '删除失败!';
            echo json_encode($msg);die;
        }
    }

    public function actionAddPart()
    {
        //大区
        $daqu_query = (new \yii\db\Query())
            ->select('id,region_name')
            ->from('cs_op_region')
            ->where(['is_del'=>0]);
        $data_daqu = $daqu_query->all();
        foreach ($data_daqu as $k=>$v){
            $daqu[] = ['id'=>$v['id'],'name'=>$v['region_name']];
        }
        //配件来源
        $original_from=[['id'=>1,'name'=>"厂家索赔"],['id'=>2,'name'=>"客户自采"],['id'=>3,'name'=>"维修入库"],['id'=>4,'name'=>"采购入库"]];
        //配件信息
        $id = isset($_REQUEST['parts_id']) ? trim($_REQUEST['parts_id']) : '';
        $query = (new \yii\db\Query())
            ->select('a.parts_id,a.car_brand,a.parts_type,a.parts_kind,a.parts_name,a.parts_brand,a.vender_code,a.dst_code,a.unit,a.main_engine_price,b.name as car_name,c.parts_name as parent_name,d.parts_name as son_name')
            ->from('cs_parts_info as a')
            ->where(['a.is_del'=>'0','a.parts_id'=>$id])
            ->leftJoin('cs_car_brand as b', 'a.car_brand = b.id')
            ->leftJoin('cs_parts_kind as c', 'a.parts_type = c.id')
            ->leftJoin('cs_parts_kind as d', 'a.parts_kind = d.id');
        $dat = $query->one();
        return $this->render('add-part',['daqu'=>$daqu,'dat'=>$dat,'original_from'=>$original_from]);
    }

    public function actionGetList()
    {
        //var_dump($_SESSION['backend']['adminInfo']['id']);exit;
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;

        $query = (new \yii\db\Query())
            ->select('i.region_name,g.parts_name as parents_name,h.parts_name as son_name,c.*,a.parts_id,a.car_brand,a.parts_type,a.parts_kind,a.parts_name,a.parts_brand,a.vender_code,a.dst_code,a.unit,a.main_engine_price,d.name as on_registrant')
            ->from('cs_parts_in as c')
            ->leftJoin('cs_parts_info as a','a.parts_id = c.info_parts_id')
            ->leftJoin('cs_admin d', 'c.on_registrant = d.id')
            ->leftJoin('cs_car_brand f', 'a.car_brand = f.id')
            ->leftJoin('cs_parts_kind g', 'a.parts_type = g.id')
            ->leftJoin('cs_parts_kind h', 'a.parts_kind = h.id')
            ->leftJoin('cs_op_region i', 'c.region = i.id')
            ->where(['a.is_del'=>0,'c.is_del'=>0]);
        //大区
        $s_province = yii::$app->request->get('s_province');
        if($s_province == '请选择'){
            $s_province = '';
        }
        //大区
        if ($s_province){
            $query->andFilterWhere(['=','c.`region`',$s_province]);
        }
        //运营公司
        $s_city = yii::$app->request->get('s_city');
        if ($s_city){
                $query->andFilterWhere(['=','c.`operating_company_id`',$s_city]);
        }
        //仓储地点
        $s_county = yii::$app->request->get('s_county');
        if ($s_county){
                $query->andFilterWhere(['=','c.`warehouse_address`',$s_county]);
        }
        $parts_type = yii::$app->request->get('parts_type');
        if ($parts_type){
            $query->andFilterWhere(['=','parts_type',trim($parts_type)]);
        }
        $brand_id = yii::$app->request->get('brand_id');
        if($brand_id){
            $query->andFilterWhere(['=','a.`car_brand`',trim($brand_id)]);
        }
        $parts_kind = yii::$app->request->get('parts_kind');
        if($parts_kind){
            $query->andFilterWhere(['=','a.`parts_kind`',trim($parts_kind)]);
        }
        $parts_name = yii::$app->request->get('parts_name');
        if($parts_name){
            $query->andFilterWhere(['like','a.`parts_name`',trim($parts_name)]);
        }
        $parts_brand = yii::$app->request->get('parts_brand');
        if($parts_brand){
            $query->andFilterWhere(['=','a.`parts_brand`',trim($parts_brand)]);
        }
        $vender_code = yii::$app->request->get('vender_code');
        if($vender_code){
            $query->andFilterWhere(['=','a.`vender_code`',trim($vender_code)]);
        }
        $dst_code = yii::$app->request->get('dst_code');
        if($dst_code){
            $query->andFilterWhere(['=','a.`dst_code`',trim($dst_code)]);
        }
        $start_add_time = trim(yii::$app->request->get('start_add_time'));
        if($start_add_time)
        {
            $query->andWhere('c.under_in_warehouse_time >=:start_add_time',[':start_add_time'=>strtotime($start_add_time)]);
        }
        $end_add_time = trim(yii::$app->request->get('end_add_time'));
        if($end_add_time)
        {
            $query->andWhere('c.under_in_warehouse_time <=:end_add_time',[':end_add_time'=>strtotime($end_add_time)]);
        }
    
        $total = $query->count();
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)->limit($pages->limit)->all();
        foreach($data as $k=>$v) {
            $data[$k]['under_in_warehouse_time'] = date('Y-m-d',$v['under_in_warehouse_time']);
            $data[$k]['on_registrant_date'] = date('Y-m-d H:i:s',$v['on_registrant_date']);
        }
        //echo '<pre>';
        //var_dump($data);exit;
        //车辆运营公司
        $oCompany = OperatingCompany::getOperatingCompany();
        //车辆品牌
        $carBrand = CarBrand::getCarBrands();
        //echo '<pre>';
        //var_dump($data);exit;
        foreach($data as &$dataItem){
            if(isset($oCompany[$dataItem['operating_company_id']]) && $oCompany[$dataItem['operating_company_id']]){
                $dataItem['operating_company_id'] = $oCompany[$dataItem['operating_company_id']]['name'];
            }
            if(isset($carBrand[$dataItem['car_brand']]) && $carBrand[$dataItem['car_brand']]){
                $dataItem['car_brand'] = $carBrand[$dataItem['car_brand']]['name'];
            }
       
            $warehouse_name = (new \yii\db\Query())->select('name')->from('oa_extract_car_site')->where(['id'=>$dataItem['warehouse_address'],'is_del'=>0])->one();
            $dataItem['warehouse_address'] = $warehouse_name['name'];

            /*$configItems = ['part_type'];
            $configCategoryModel = new ConfigCategory();
            $config = $configCategoryModel->getCategoryConfig($configItems,'value');
            $dataItem['parts_type_name']= $config['part_type'][$dataItem['parts_type']]['text'];
            echo '<pre>';
            var_dump($config['part_type']);
            var_dump($dataItem['parts_type']);exit;*/
            $part_type_name = (new \yii\db\Query())->select('text')->from('cs_config_item')->where(['id'=>$dataItem['parts_type'],'is_del'=>0])->one();
            $dataItem['parts_type'] = $part_type_name['text'];
            $parts_kind_name = (new \yii\db\Query())->select('text')->from('cs_config_item')->where(['id'=>$dataItem['parts_kind'],'is_del'=>0])->one();
            $dataItem['parts_kind'] = $parts_kind_name['text'];
            //var_dump($dataItem);exit;

        }

        /* echo '<pre>';
         var_dump($data);exit;*/

        $allData=array();
        $allData['rows'] = $data;
        $allData['total'] = $total;
        return json_encode($allData);
    }

    //获取配件种类,下拉
    public function actionGetKind()
    {
        $id = isset($_REQUEST['id']) ? trim($_REQUEST['id']) : '';
        //配件类别
        $query = (new \yii\db\Query())
            ->select('id,parts_name,parents_id')
            ->from('cs_parts_kind')
            ->where(['is_del'=>'0','parents_id'=>$id]);
        $dat = $query->all();
        if($id == ''){
            $data[] = ['value'=>' ','text'=>' '];
            return json_encode($data);
        }
        if($dat){
            foreach($dat as $val){
                $data[] = ['value'=>$val['id'],'text'=>$val['parts_name']];
            }
        }else{
            $data[] = ['value'=>' ','text'=>' '];

        }
        return json_encode($data);
    }
    //获取运营公司
    public function actionGetCompany()
    {
        $id = isset($_REQUEST['id']) ? trim($_REQUEST['id']) : '';
        //运营公司
        $query = (new \yii\db\Query())
            ->select('id,name')
            ->from('cs_operating_company')
            ->where(['is_del'=>'0','area'=>$id]);
        $dat = $query->all();
        if($dat){
            foreach($dat as $val){
                $data[] = ['value'=>$val['id'],'text'=>$val['name']];
            }
        }else{
            $data[] = ['value'=>' ','text'=>' '];

        }
        return json_encode($data);
    }
    //获取仓储地点
    public function actionGetSite()
    {
        $id = isset($_REQUEST['id']) ? trim($_REQUEST['id']) : '';
        //仓储地点
        $query = (new \yii\db\Query())
            ->select('id,name')
            ->from('oa_extract_car_site')
            ->where(['is_del'=>'0','operating_company_id'=>$id]);
        $dat = $query->all();
        if($dat){
            foreach($dat as $val){
                $data[] = ['value'=>$val['id'],'text'=>$val['name']];
            }
        }else{
            $data[] = ['value'=>' ','text'=>' '];
        }
        return json_encode($data);
    }
}