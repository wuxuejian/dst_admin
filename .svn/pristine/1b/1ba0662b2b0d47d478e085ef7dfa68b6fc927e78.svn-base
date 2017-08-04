<?php
namespace backend\modules\parts\controllers;
use backend\controllers\BaseController;
use backend\models\PurchaseOrderMain;
use backend\models\PurchaseExpress;

use backend\models\Car;
use backend\models\ConfigCategory;
use backend\models\CarFault;
use backend\models\CarDrivingLicense;
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

class PartsInfoController extends BaseController
{
    public function actionIndex()
    {
        $buttons = $this->getCurrentActionBtn();
        $configItems = ['part_type','part_kind'];
        $config = (new ConfigCategory)->getCategoryConfig($configItems,'value');
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
        return $this->render('index',['buttons'=>$buttons,'searchFormOptions'=>$searchFormOptions]);
    }

    public function actionAdd()
    {
        if(yii::$app->request->isPost){
            $car_brand = yii::$app->request->post('car_brand');
            $parts_type = yii::$app->request->post('parts_type');
            $parts_kind = yii::$app->request->post('parts_kind');
            $parts_name = yii::$app->request->post('parts_name');
            $parts_brand = yii::$app->request->post('parts_brand');
            $vender_code = yii::$app->request->post('vender_code');
            $dst_code = yii::$app->request->post('dst_code');
            $unit = yii::$app->request->post('unit');
            $main_engine_price = yii::$app->request->post('main_engine_price');
            if($car_brand==' ' or
                $parts_type==' ' or
                $parts_kind==' ' or
                $parts_name==' ' or
                $parts_brand==' ' or
                $vender_code==' ' or
                $dst_code==' ' or
                $unit==' ' or
                $main_engine_price==' '
            ){
                $msg['status'] = 2;
                $msg['info'] = '您还有数据未填写!';
                echo json_encode($msg);die;
            }
            if(preg_match('/[\x7f-\xff]/', $vender_code)){
                $returnArr['status'] = 2;
                $returnArr['info'] = '厂家编码不能输入中文!';
                return json_encode($returnArr);
            }
            if(preg_match('/[\x7f-\xff]/', $dst_code)){
                $returnArr['status'] = 2;
                $returnArr['info'] = '我方编码不能输入中文!';
                return json_encode($returnArr);
            }
            //保证我方编码的唯一性
            $query = (new \yii\db\Query())
                ->select('parts_id,dst_code')
                ->from('cs_parts_info')
                ->where(['is_del'=>0,'dst_code'=>$dst_code]);
            $dst_code_data = $query->one();
            if($dst_code_data){
                $returnArr['status'] = 2;
                $returnArr['info'] = '我方编码已经存在，请不要重复输入!';
                return json_encode($returnArr);
            }
            $result = Yii::$app->db->createCommand()->insert('cs_parts_info', [
                'car_brand'         =>trim($car_brand),
                'parts_type'        =>trim($parts_type),
                'parts_kind'        =>trim($parts_kind),
                'parts_name'        =>trim($parts_name),
                'parts_brand'       =>trim($parts_brand),
                'vender_code'       =>trim($vender_code),
                'dst_code'          =>trim($dst_code),
                'unit'              =>trim($unit),
                'main_engine_price' =>trim($main_engine_price)
            ])->execute();
            if($result){
                $msg['status'] = 1;
                $msg['info'] = '添加成功!';
                echo json_encode($msg);die;
            }else{
                $msg['status'] = 0;
                $msg['info'] = '添加失败!';
                echo json_encode($msg);die;
            }
        }
        //获取车辆品牌
        $query = (new \yii\db\Query())
            ->select('id,name')
            ->from('cs_car_brand')
            ->where(['is_del'=>0]);
        $car_brand_data = $query->all();
        //配件类别
        $query = (new \yii\db\Query())
            ->select('a.parts_name,a.id')
            ->where(['a.is_del'=>0,'a.parents_id'=>0])
            ->from('cs_parts_kind as a');
        $type_name = $query->all();
        //单位
        $unit = '个,片,套,只,组,条,瓶,桶,升,毫升';
        $unit_data = explode(',',$unit);
        foreach ($unit_data as $val){
            $searchFormOptions['unit'][] = ['value'=>$val,'text'=>$val];
        }
        return $this->render('add',['car_brand'=>$car_brand_data,'searchFormOptions'=>$searchFormOptions,'type_name'=>$type_name]);
    }

    public function actionEdit()
    {
        if(yii::$app->request->isPost){
            $data = $_POST;
            if( $data['car_brand'] == '' or
                $data['parts_type'] == '' or
                $data['parts_kind'] == '' or
                $data['parts_name'] == '' or
                $data['parts_brand'] == '' or
                $data['vender_code'] == '' or
                $data['dst_code'] == '' or
                $data['unit'] == '' or
                $data['main_engine_price'] == ''){
                    $msg['status'] = 2;
                    $msg['info'] = '您还有数据未填写!';
                    echo json_encode($msg);die;
            }
            $result = Yii::$app->db->createCommand()->update('cs_parts_info', [
                'car_brand' => trim($data['car_brand']),
                'parts_type' => trim($data['parts_type']),
                'parts_kind' => trim($data['parts_kind']),
                'parts_name' => trim($data['parts_name']),
                'parts_brand' => trim($data['parts_brand']),
                'vender_code' => trim($data['vender_code']),
                'dst_code' => trim($data['dst_code']),
                'unit' => trim($data['unit']),
                'main_engine_price' => trim($data['main_engine_price']),
                'last_time' => date('Y-m-d H:i:s',time()),
            ],"parts_id=$data[parts_id]")->execute();
            if($result){
                $msg['status'] = 1;
                $msg['info'] = '修改成功!';
                echo json_encode($msg);die;
            }else{
                $msg['status'] = 0;
                $msg['info'] = '修改失败!';
                echo json_encode($msg);die;
            }
        }
        $parts_id = yii::$app->request->get('parts');
        $query = (new \yii\db\Query())
            ->select('a.*,b.id as parent_id,b.parts_name as parent_name,c.id as son_id,c.parts_name as son_name')
            ->from('cs_parts_info as a ')
            ->where(['a.parts_id'=>$parts_id,'a.is_del'=>0,'b.is_del'=>0])
            ->leftjoin('cs_parts_kind as b','a.parts_type = b.id')
            ->leftjoin('cs_parts_kind as c','a.parts_kind = c.id');
        $parts_id_data = $query->one();
        //获取车辆品牌
        $query = (new \yii\db\Query())
            ->select('id,name')
            ->from('cs_car_brand')
            ->where(['is_del'=>0]);
        $car_brand_data = $query->all();
        //配件类别
        $query = (new \yii\db\Query())
            ->select('a.parts_name,a.id')
            ->where(['a.is_del'=>0,'a.parents_id'=>0])
            ->from('cs_parts_kind as a');
        $type_name = $query->all();
        //车辆类型
        $searchFormOptions = [];
        //单位
        $unit = '个,片,套,只,组,条,瓶,桶,升,毫升';
        $unit_data = explode(',',$unit);
        foreach ($unit_data as $val){
            $searchFormOptions['unit'][] = ['value'=>$val,'text'=>$val];
        }
        foreach ($car_brand_data as $val){
            $searchFormOptions['car_brand'][] = ['value'=>$val['id'],'text'=>$val['name']];
        }
        foreach ($type_name as $val){
            $searchFormOptions['type_name'][] = ['value'=>$val['id'],'text'=>$val['parts_name']];
        }
        return $this->render('edit',['searchFormOptions'=>$searchFormOptions,'data'=>$parts_id_data,'type_name'=>$type_name]);
    }

    public function actionDel()
    {
        $parts_id = yii::$app->request->post('id');
        $result = Yii::$app->db->createCommand()->update('cs_parts_info', [
               'is_del' => 1,
            'last_time' => date('Y-m-d H:i:s',time()),
        ],"parts_id=$parts_id")->execute();
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

    /**
     * 新增或修改配件基本信息
     */
    function addOrUpdate($connection,$obj,$brand_arr,$part_type,$part_kind)
    {
//        var_dump($obj);die;
        $obj[6] = trim($obj[6]);
        //通过查询我方编码，来判断是新增数据还是更新数据
        $query = (new \yii\db\Query())
            ->select('a.dst_code,a.parts_id')
            ->where(['a.is_del'=>0,'a.dst_code'=>$obj[6]])
            ->from('cs_parts_info as a');
        $dst_code = $query->one();
        $saveData = [
            'car_brand' => $brand_arr[trim($obj[0])],
            'parts_type' => $part_type[trim($obj[1])],
            'parts_kind' => $part_kind[trim($obj[2])],
            'parts_name' => trim($obj[3]),
            'parts_brand' => trim($obj[4]),
            'vender_code' => trim($obj[5]),
            'dst_code' => trim($obj[6]),
            'unit' => trim($obj[7]),
            'main_engine_price' => trim($obj[8])
        ];
        if($dst_code['parts_id']){
            $db = \Yii::$app->db;
            $transaction = $db->beginTransaction();
            try{
                $transaction->commit();
                $r = $connection->createCommand()->update('cs_parts_info', $saveData, "dst_code = $dst_code[dst_code]")->execute();
            }catch (yii\db\Exception $e){
                $transaction->rollback();
            }
            return 'update';
        }else {
            $db = \Yii::$app->db;
            $transaction = $db->beginTransaction();
            try{
                $transaction->commit();
                $query = $connection->createCommand()->insert('cs_parts_info',$saveData);
                $query->execute();
            }catch (yii\db\Exception $e){
                $transaction->rollback();
            }
            return 'add';
        }
    }
    /**
     * 检测导入数据合法性
     * @param unknown_type $connection	数据库连接
     * @param unknown_type $obj	数据
     * @param unknown_type $brand_arr	品牌
     * @param unknown_type $part_type	配件类别
     * @param unknown_type $part_kind	配件种类
     */
    public function checkImportData($connection,$obj,$brand_arr,$part_type,$part_kind)
    {
        /*
         * 0车辆品牌,1配件类别,2配件种类,
         */
        $err_info = array();
        $obj[0] = trim($obj[0]);
        $obj[1] = trim($obj[1]);
        $obj[2] = trim($obj[2]);
        if(!@$brand_arr[$obj[0]]){
            array_push($err_info, "车辆品牌：{$obj[0]}，不存在！");
        }
        if(!@$part_type[$obj[1]]){
            array_push($err_info, "配件类别：{$obj[1]}，不存在！");
        }
        if(!@$part_kind[$obj[2]]){
            array_push($err_info, "配件种类：{$obj[2]}，不存在！");
        }
        return $err_info;
    }
    //读取csv文件
    public function input_csv($handle) {
        $out = array ();
        $n = 0;
        while ($data = fgetcsv($handle, 10000)) {
            $num = count($data);
            for ($i = 0; $i < $num; $i++) {
                $out[$n][$i] = mb_convert_encoding($data[$i], "UTF-8", "GBK");
            }
            $n++;

        }
        return $out;
    }

    public function actionMuchImport()
    {
        if(yii::$app->request->isPost){
            $list = array();
            //1.解析csv
            $filename = $_FILES['append']['tmp_name'];
            if (empty ($filename)) {
                echo '文件不存在';exit;
            }
            $handle = fopen($filename, 'r');
            $result = $this->input_csv($handle);
            //解析csv end...
            $connection = yii::$app->db;
            //2.初始化配置数据(自己写的)
            //配件类别和配件种类
            $query = (new \yii\db\Query())
                ->select('id,parts_name,parents_id')
                ->from('cs_parts_kind')
                ->where(['is_del'=>0]);
            $alldata = $query->all();
            foreach($alldata as $val){
                if($val['parents_id'] == 0){
                    $part_type[$val['parts_name']] = $val['id'];
                }else{
                    $part_kind[$val['parts_name']] = $val['id'];
                }
            }
            //车辆品牌
            $brand_arr	= [];
            $sql = "select id,name from cs_car_brand where is_del=0";
            $car_brand_data = $connection->createCommand($sql)->queryAll();
            foreach ($car_brand_data as $row){
                $brand_arr[$row['name']] = $row['id'];
            }
            //初始化配置数据end
            //3.检查数据合法性
            foreach ($result as $index=>$row) {
                if($index==0){
                    continue;
                }
                $err_info = $this->checkImportData($connection,$row,$brand_arr,$part_type,$part_kind);
                if ($err_info) {
                    array_unshift($err_info, "检查第{$index}条数据失败<br/>");
                    $returnArr['status'] = false;
                    $returnArr['info'] = $err_info;
                    return json_encode($returnArr);
                }
            }//end
            //4.新增或修改数据
            $add_num = 0;
            $update_num = 0;
            foreach ($result as $index=>$row) {
                if($index==0){
                    continue;
                }
                $r = $this->addOrUpdate($connection,$row,$brand_arr,$part_type,$part_kind);
                if($r == 'update'){
                    $update_num++;
                }else {
                    $add_num++;
                }
            }
            $returnArr = [];
            $returnArr['status'] = true;
            $returnArr['info'] = "文件导入成功！新增：{$add_num}，修改{$update_num}条";
            return json_encode($returnArr);
        }
        return $this->render('import');
    }

    public function actionGetList()
    {
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        //var_dump($pageSize);exit;
        $query = (new \yii\db\Query())
            ->select('c.parts_name as parents_name,c.is_del,d.is_del,d.parts_name as son_name,b.name,a.parts_id,a.car_brand,a.parts_name,a.parts_brand,a.vender_code,a.dst_code,a.unit,a.main_engine_price')
            ->from('cs_parts_info as a')
            ->leftjoin('cs_car_brand as b','a.car_brand = b.id')
            ->leftJoin('cs_parts_kind as c','a.parts_type = c.id')
            ->leftJoin('cs_parts_kind as d','a.parts_kind = d.id')
            ->where(['a.is_del'=>'0','c.is_del'=>'0','d.is_del'=>'0']);
        $configItems = ['part_type','part_kind'];
        $config = (new ConfigCategory)->getCategoryConfig($configItems,'value');
        if (yii::$app->request->isGet){
            $dat = $_GET;
            if($dat['car_brand'] != ''){
                $query->andFilterWhere(['=','car_brand',trim($dat['car_brand'])]);
            }
            if($dat['parts_type'] != ''){
                $query->andFilterWhere(['=','parts_type',trim($dat['parts_type'])]);
            }
            if($dat['parts_kind'] != ''){
                $query->andFilterWhere(['=','parts_kind',trim($dat['parts_kind'])]);
            }
            if($dat['parts_name'] != ''){
                $query->andFilterWhere(['like','a.parts_name',trim($dat['parts_name'])]);
            }
            if($dat['parts_brand'] != ''){
                $query->andFilterWhere(['like','a.parts_brand',trim($dat['parts_brand'])]);
            }
            if($dat['vender_code'] != ''){
                $query->andFilterWhere(['like','vender_code',trim($dat['vender_code'])]);
            }
            if($dat['dst_code'] != ''){
                $query->andFilterWhere(['like','dst_code',trim($dat['dst_code'])]);
            }
        }
        $total = $query->count();
        //var_dump($total);exit;
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);

        $data = $query->offset($pages->offset)->limit($pages->limit)->all();

        $carBrand = CarBrand::getCarBrands();
        //echo '<pre>';
        //var_dump($data);exit;
        foreach($data as &$dataItem){
            if(isset($carBrand[$dataItem['car_brand']]) && $carBrand[$dataItem['car_brand']]){
                $dataItem['car_brand'] = $carBrand[$dataItem['car_brand']]['name'];
            }
        }
        //数据转换
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
        if($dat){
            foreach($dat as $val){
                $data[] = ['value'=>$val['id'],'text'=>$val['parts_name']];
            }
        }else{
            $data[] = ['value'=>' ','text'=>' '];

        }
        return json_encode($data);
    }
}
