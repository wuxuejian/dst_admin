<?php
namespace backend\modules\parts\controllers;
use backend\controllers\BaseController;

use backend\models\Car;
use backend\modules\parts\models\PartsInfoModel;
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
        return $this->render('index',['buttons'=>$buttons]);
    }

    public function actionAdd()
    {
        if(yii::$app->request->isPost){
            $factory_code = yii::$app->request->post('factory_code');
            $parts_name = yii::$app->request->post('parts_name');
            $unit = yii::$app->request->post('unit');
            $size = yii::$app->request->post('size');
            $three_date = yii::$app->request->post('three_date');
            $work_date = yii::$app->request->post('work_date');
            $shop_price = yii::$app->request->post('shop_price');
            $out_price = yii::$app->request->post('out_price');
            $che_type = yii::$app->request->post('che_type');

            if(!$unit){
                $msg['status'] = 0;
                $msg['info'] = '单位未填写!';
                echo json_encode($msg);die;
            }
            if(count($che_type) <= 0 ){
                $msg['status'] = 0;
                $msg['info'] = '车型未填写!';
                echo json_encode($msg);die;
            }
            //车型去重
            $che_type = array_unique($che_type);
            $parts_code = '';
            $model = new PartsInfoModel;
            $db = \Yii::$app->db;
            foreach ($che_type as $k=>$v){
                $transaction = $db->beginTransaction();
                try {
                    $parts_code .= $v.'-'.$factory_code;
                    $data = $model ->add($factory_code,$parts_code,$parts_name,$unit,$size,$three_date,$work_date,$shop_price,$out_price,$v);
                    $transaction->commit();
                } catch (Exception $e) {
                    $transaction->rollback();
                }
            }
            if($data){
                $msg['status'] = 1;
                $msg['info'] = '添加成功!';
                echo json_encode($msg);die;
            }else{
                $msg['status'] = 0;
                $msg['info'] = '添加失败!';
                echo json_encode($msg);die;
            }
        }
        return $this->render('add');
    }

    public function actionEdit()
    {
        $model = new PartsInfoModel;
        if(yii::$app->request->isPost){
            $id = yii::$app->request->post('id');
            $factory_code = yii::$app->request->post('factory_code');
            $parts_name = yii::$app->request->post('parts_name');
            $unit = yii::$app->request->post('unit');
            $size = yii::$app->request->post('size');
            $three_date = yii::$app->request->post('three_date');
            $work_date = yii::$app->request->post('work_date');
            $shop_price = yii::$app->request->post('shop_price');
            $out_price = yii::$app->request->post('out_price');
            $che_type = yii::$app->request->post('che_type');

            if(!$unit){
                $msg['status'] = 0;
                $msg['info'] = '单位未填写!';
                echo json_encode($msg);die;
            }
            if(count($che_type) <= 0 ){
                $msg['status'] = 0;
                $msg['info'] = '车型未填写!';
                echo json_encode($msg);die;
            }
            if(count($che_type) >1){
                $msg['status'] = 0;
                $msg['info'] = '车型只能填写一个!';
                echo json_encode($msg);die;
            }
            $parts_code = '';
            $db = \Yii::$app->db;
            foreach ($che_type as $k=>$v){
                $transaction = $db->beginTransaction();
                try {
                    $parts_code .= $v.'-'.$factory_code;
                    $result = PartsInfoModel::edit_data($factory_code,$parts_code,$parts_name,$unit,$size,$three_date,$work_date,$shop_price,$out_price,$v,$id);
                    $transaction->commit();
                } catch (Exception $e) {
                    $transaction->rollback();
                }
            }
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
        $data = $model->edit($parts_id);
//        echo '<pre>';
//        var_dump($parts_id);die;
        return $this->render('edit',['data'=>$data]);
    }

    public function actionSee()
    {
        $parts_id = yii::$app->request->get('parts');
        $model = new PartsInfoModel;
        $data = $model->edit($parts_id);
        return $this->render('see',['data'=>$data]);
    }

    public function actionDel()
    {
        $parts_id = yii::$app->request->post('id');
        $result = PartsInfoModel::del($parts_id);
        if($result){
            $msg['status'] = 1;
            $msg['info'] = '作废成功!';
            echo json_encode($msg);die;
        }else{
            $msg['status'] = 0;
            $msg['info'] = '作废失败!';
            echo json_encode($msg);die;
        }
    }

    /**
     * 新增或修改配件基本信息
     */
    function addOrUpdate($connection,$obj)
    {
//        var_dump($obj);die;
        //通过查询我方编码，来判断是新增数据还是更新数据
        //根据配件编码来判断是新增数据还是更新数据
        $parts_code = $obj[8].'-'.$obj[0];
        $query = (new \yii\db\Query())
            ->select('a.id,a.parts_code')
            ->where(['a.parts_code'=>$parts_code])
            ->from('cs_parts_info_new as a');
        $parts_code_data = $query->one();
        $saveData = [
            'factory_code' => addslashes(trim($obj[0])),
            'parts_code' => $parts_code,
            'parts_name' => addslashes(trim($obj[1])),
            'unit' => addslashes(trim($obj[2])),
            'size' => addslashes(trim($obj[3])),
            'three_date' => addslashes(trim($obj[4])),
            'work_date' => addslashes(trim($obj[5])),
            'shop_price' => addslashes(trim($obj[6])),
            'out_price' => addslashes(trim($obj[7])),
            'car_type' => addslashes(trim($obj[8])),
            'status' => 0,
            'create_man' => $_SESSION['backend']['adminInfo']['username'],
            'create_time' => date('Y-m-d H:i:s',time()),
        ];
        if($parts_code_data['parts_code']){
            $db = \Yii::$app->db;
            $transaction = $db->beginTransaction();
            try{
                $transaction->commit();
                $r = $connection->createCommand()->update('cs_parts_info_new', $saveData, "parts_code = $parts_code_data[parts_code]")->execute();
            }catch (yii\db\Exception $e){
                $transaction->rollback();
            }
            return 'update';
        }else {
            $db = \Yii::$app->db;
            $transaction = $db->beginTransaction();
            try{
                $transaction->commit();
                $query = $connection->createCommand()->insert('cs_parts_info_new',$saveData);
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
    public function checkImportData($connection,$obj)
    {
        /*
         * 0原厂编码（整数+字母）,1三包期（正整数）,2质保期（正整数）,3.采购指导价,4.出库指导价,
         */
        $err_info = array();
        $obj[0] = trim($obj[0]);
        $obj[4] = trim($obj[4]);
        $obj[5] = trim($obj[5]);
        $obj[6] = trim($obj[6]);
        $obj[7] = trim($obj[7]);
//        if(!@$brand_arr[$obj[0]]){
//            array_push($err_info, "车辆品牌：{$obj[0]}，不存在！");
//        }
//        if(!@$part_type[$obj[1]]){
//            array_push($err_info, "配件类别：{$obj[1]}，不存在！");
//        }
//        if(!@$part_kind[$obj[2]]){
//            array_push($err_info, "配件种类：{$obj[2]}，不存在！");
//        }
        if(!preg_match("/^[0-9a-zA-Z]*$/",$obj[0])){
            array_push($err_info, "原厂编码：{$obj[0]}，只能是数字加字母！");
        }
        if(!preg_match("/^[0-9]*$/",$obj[4])){
            array_push($err_info, "三包期：{$obj[4]}，只能是整数！");
        }
        if(!preg_match("/^[0-9]*$/",$obj[5])){
            array_push($err_info, "质保期：{$obj[5]}，只能是整数！");
        }
        if(!preg_match("/^[0-9.]*$/",$obj[6])){
            array_push($err_info, "采购指导价：{$obj[6]}，只能是数字！");
        }
        if(!preg_match("/^[0-9.]*$/",$obj[7])){
            array_push($err_info, "出库指导价：{$obj[7]}，只能是数字！");
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
            //初始化配置数据end
            //3.检查数据合法性
            foreach ($result as $index=>$row) {
                if($index==0){
                    continue;
                }
                $err_info = $this->checkImportData($connection,$row);
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
                $r = $this->addOrUpdate($connection,$row);
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
        //获取状态
        $status = isset($_REQUEST['status']) ? trim($_REQUEST['status']) : 0;
        if (yii::$app->request->isGet){
            $dat = $_GET;
        }
        $model = new PartsInfoModel;
        $data =  $model ->search($pageSize,$dat,$status);
        return $data;
    }

}
