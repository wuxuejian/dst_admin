<?php
namespace backend\modules\parts\controllers;
use backend\controllers\BaseController;
use backend\models\PurchaseOrderMain;
use backend\models\PurchaseExpress;

use backend\models\Car;
use backend\modules\parts\models\PartsInstockModel;
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
        $searchFormOptions['company'] = PartsInstockModel::company();
        $searchFormOptions['check_man'] = PartsInstockModel::duty_man();
        return $this->render('index',['buttons'=>$buttons,'searchFormOptions'=>$searchFormOptions]);
    }

    public function actionAdd()
    {
        if(yii::$app->request->isPost){
            $data = $_POST;
            if(count($data['parts_id']) <=0){
                $msg['status'] = 2;
                $msg['info'] = '未添加配件!';
                echo json_encode($msg);die;
            }
            //去重
            $repeat = array_unique($data['parts_id']);
            if(count($data['parts_id']) != count($repeat)){
                $msg['status'] = 2;
                $msg['info'] = '不能添加重复的配件!';
                echo json_encode($msg);die;
            }
            if(count($data['price_in']) >0){
                foreach ($data['price_in'] as $val){
                    if(floatval($val) <=0){
                        $msg['status'] = 2;
                        $msg['info'] = '采购价不能为负数,不能为0!';
                        echo json_encode($msg);die;
                    }
                    if(preg_match('/[\x7f-\xff]/', $val)){
                        $returnArr['status'] = 2;
                        $returnArr['info'] = '采购单价不能输入中文!';
                        return json_encode($returnArr);
                    }
                }
            }
            if(count($data['price_num']) >0){
                foreach ($data['price_num'] as $val){
                    if(floatval($val) <=0){
                        $msg['status'] = 2;
                        $msg['info'] = '数量不能为负数,不能为0!';
                        echo json_encode($msg);die;
                    }
                    if(preg_match('/[\x7f-\xff]/', $val)){
                        $returnArr['status'] = 2;
                        $returnArr['info'] = '数量不能输入中文!';
                        return json_encode($returnArr);
                    }
                }
            }
//            $data['out_price'] = bcmul($data['shop_price'],1.3,2);
            $result_data = PartsInstockModel::add($data);
            if($result_data){
                $msg['status'] = 1;
                $msg['info'] = '添加成功!';
                echo json_encode($msg);die;
            }else{
                $msg['status'] = 0;
                $msg['info'] = '添加失败!';
                echo json_encode($msg);die;
            }
        }

        $warehouse_address = PartsInstockModel::warehouse();
        foreach($warehouse_address as $val){
            $searchFormOptions['warehouse_address'][] = ['value'=>$val['id'],'text'=>$val['name']];
        }
        $human =  PartsInstockModel::duty_man();
        foreach ($human as $val){
            $searchFormOptions['check_man'][] = ['value'=>$val['user_id'],'text'=>$val['human_name']];
        }
        $provide_man =  PartsInstockModel::provide_man();
        foreach ($provide_man as $val){
            $searchFormOptions['provide_name'][] = ['value'=>$val['id'],'text'=>$val['provide_name']];
        }
        return $this->render('add-new',['searchFormOptions'=>$searchFormOptions]);
    }

    public function actionAddNew()
    {
        return $this->render('add');
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

    public function actionSee()
    {
        $id = isset($_REQUEST['id']) ? trim($_REQUEST['id']) : '';
        if($id){
            $data = PartsInstockModel::see($id);
            if($data){
                $parts_data =PartsInstockModel::getParts($data['parts_id']);
            }
        }
        return $this->render('see',['dat'=>$data,'parts'=>$parts_data]);
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

    public function actionGetList()
    {
        //var_dump($_SESSION['backend']['adminInfo']['id']);exit;
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $get_data = $_GET;
        $data = PartsInstockModel::search($get_data,$pageSize);
        //车辆运营公司
        $oCompany = OperatingCompany::getOperatingCompany();
        return $data;
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