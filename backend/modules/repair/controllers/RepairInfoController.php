<?php
namespace backend\modules\repair\controllers;
use backend\controllers\BaseController;
use backend\models\PurchaseOrderMain;
use backend\models\PurchaseExpress;
use backend\classes\MyUploadFile;
use common\classes\Resizeimage;
use backend\models\Car;
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
use yii\web\UploadedFile;
use yii\base\Object;
use yii\data\Pagination;
use backend\models\Admin;
use common\classes\Category;
use backend\models\CarType;
class RepairInfoController extends BaseController
{
    public function actionIndex()
    {
        $buttons = $this->getCurrentActionBtn();
        //车牌号
        $query = (new \yii\db\Query())
            ->select('id,plate_number')
            ->from('cs_car')
            ->where(['is_del'=>0]);
        $searchFormOptions['car_id'] = $query->all();
        //售后修理厂
        $query = (new \yii\db\Query())
            ->select('id,site_name')
            ->from('oa_service_site');
        $searchFormOptions['repair_company'] = $query->all();
        return $this->render('index',['buttons'=>$buttons,'formoption'=>$searchFormOptions]);
    }
    //添加维修单
    public function actionAdd()
    {
        date_default_timezone_set('PRC');
        $connection = yii::$app->db;
        $add_time = strtotime(date('Y-m-d H:i:s'));
        //var_dump($add_time);exit;
        $add_aid  = $_SESSION['backend']['adminInfo']['id'];
        $repair_company=0;
        if(yii::$app->request->isPost){
            $car_no = yii::$app->request->post('car_no');//车牌号
            //校验是否存在维修中的记录
            //校验是否存在维修中的记录
            $check_status=(new \yii\db\Query())
                ->select('check_status')
                ->from('cs_repair')
                ->where(['car_id'=>$car_no])
                ->orderBy('create_time DESC')
                ->one();
                if($check_status){
                    if($check_status['check_status']!=7){
                        $returnArr['status'] = false;
                        $returnArr['info'] = '该车存在维修中的维修单!';
                        return  json_encode($returnArr);
                    }
                }
            $order_no = yii::$app->request->post('order_no');//工单号
            $repair_person = yii::$app->request->post('repair_person');//送修人
            if(!trim($repair_person)){
                $returnArr['status'] = false;
                $returnArr['info'] = '送修人未填!';
                return json_encode($returnArr);
            }
            $repair_person_tel = yii::$app->request->post('repair_person_tel');//送修人电话
            if(!preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#', $repair_person_tel) && !preg_match('|^\d{8}$|',$repair_person_tel)){
                $returnArr['status'] = false;
                $returnArr['info'] = '送修人电话格式不对!';
                return json_encode($returnArr);
            }
            $fuwu_person = yii::$app->request->post('fuwu_person');//服务人
            if(!trim($fuwu_person)){
                $returnArr['status'] = false;
                $returnArr['info'] = '服务顾问未填!';
                return json_encode($returnArr);
            }
            $fuwu_person_tel = yii::$app->request->post('fuwu_person_tel');//服务人电话
            if(!preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#', $fuwu_person_tel) && !preg_match('|^\d{8}$|',$fuwu_person_tel)){
                $returnArr['status'] = false;
                $returnArr['info'] = '服务顾问电话格式不对!';
                return json_encode($returnArr);
            }
            $in_time = yii::$app->request->post('in_time');//进厂时间
            $expect_time = yii::$app->request->post('expect_time');//出厂时间
            $into_mile = yii::$app->request->post('into_mile');//进厂里程
            if(!trim($fuwu_person)){
                $returnArr['status'] = false;
                $returnArr['info'] = '服务顾问未填!';
                return json_encode($returnArr);
            }
            $soc = yii::$app->request->post('soc');//电量
            if(!trim($soc)){
                $returnArr['status'] = false;
                $returnArr['info'] = '电量未填!';
                return json_encode($returnArr);
            }
            $error_note = yii::$app->request->post('error_note');//故障描述
            if(!trim($error_note)){
                $returnArr['status'] = false;
                $returnArr['info'] = '故障描述未填!';
                return json_encode($returnArr);
            }
            $note = yii::$app->request->post('note');//备注
            $photoa = yii::$app->request->post('photoa');//车辆仪表盘
            if(!$photoa){
                $returnArr['status'] = false;
                $returnArr['info'] = '车辆仪表盘照片有误!';
                return json_encode($returnArr);
            }
            $photoc = yii::$app->request->post('photoc');//故障位置
            if(!$photoc){
                $returnArr['status'] = false;
                $returnArr['info'] = '故障位置照片有误!';
                return json_encode($returnArr);
            }
            $is_save_task = yii::$app->request->post('is_save_task');//是否保存工时
            $is_save_part = yii::$app->request->post('is_save_part');//是否保存配件
             if(!$is_save_task){
                $returnArr['status'] = false;
                $returnArr['info'] = '工时信息未保存!';
                return json_encode($returnArr);
            }
            if(!$is_save_part){
                $returnArr['status'] = false;
                $returnArr['info'] = '配件信息未保存!';
                return json_encode($returnArr);
            }
            $part_info = yii::$app->request->post('part_info');//配件信息
            if(!$part_info){
                $returnArr['status'] = false;
                $returnArr['info'] = '配件信息有误!';
                return json_encode($returnArr);
            }
            //$part_info =  preg_replace('/[\x00-\x1F\x80-\x9F]/u', '', trim($part_info));
            $part_info=json_decode($part_info);
            $task_info = yii::$app->request->post('task_info');//工时信息
            if(!$task_info){
                $returnArr['status'] = false;
                $returnArr['info'] = '工时信息有误!';
                return json_encode($returnArr);
            }
            $repair_price = yii::$app->request->post('repair_price');//维修报价
            $sale_factory = yii::$app->request->post('sale_factory');//维修厂类型
            if($sale_factory=='外部维修厂'){
                $sale_factory=1;
            }else{
                $sale_factory=0;
            }
            $into_factory = yii::$app->request->post('into_factory');//是否拖车进厂
            if($into_factory==-1){
                $returnArr['status'] = false;
                $returnArr['info'] = '未选择是否拖车进厂!';
                return json_encode($returnArr);
            }
            $db = \Yii::$app->db;
            $transaction = $db->beginTransaction(); //开启事物
                if(!$order_no && !$repair_company){
                    //登记编号，格式：GZ+日期+3位数（即该登记是系统当天登记的第几个，第一个是001，第二个是002…）
                   $todayNo = (new \yii\db\Query())->select('order_no')->from('oa_car_maintain')
                ->where('time >=:start_time AND time <=:end_time and order_no like "%WX%"',[':start_time'=>strtotime(date('Y-m-d')),':end_time'=>strtotime(date('Y-m-d'))+86400])
                ->orderBy('time DESC')->one();
                    
                    if($todayNo){
                        if(preg_match('/^WX(\d+)/i',$todayNo['order_no'],$data))
                        {
                            $currentNo = $data[1]+1;
                            $order_no = 'WX'.$currentNo;
                        }else{
                            $currentNo = str_pad(1,6,0,STR_PAD_LEFT);
                            $order_no = 'WX' . date('Ymd') . $currentNo;
                        }
                        
                    }else{
                        $currentNo = str_pad(1,6,0,STR_PAD_LEFT);
                        $order_no = 'WX' . date('Ymd') . $currentNo;
                    }
                    //根据车牌号拿到车辆ID
                    $dat=(new \yii\db\Query())
                        ->select('id')
                        ->from('cs_car')
                        ->where(['is_del'=>'0','plate_number'=>$car_no])
                        ->one();

                    //插入到oa_car_maintain
                        $ocs = !empty($_SESSION['backend']['adminInfo']['operating_company_ids']) ? $_SESSION['backend']['adminInfo']['operating_company_ids']: $_SESSION['backend']['adminInfo']['operating_company_id'];
                    $result_1= $connection->createCommand()->insert('oa_car_maintain', [
                                    'type'=>1,
                                    'car_no'=>$car_no,
                                    'order_no'=>$order_no,
                                    'status'=>5,
                                    'create_time'=>time(),
                                    'car_id'=>$dat['id'],
                                    'operating_company_id'=>$ocs,
                                    'into_factory_time'=>strtotime($in_time),
                                    'time'=>time()
                                ])->execute();
                }elseif(!$order_no && $repair_company){
                    $returnArr['status'] = false;
                    $returnArr['info'] = '无法找到对应的客服工单号，无法登记，请与维修专员联系确定客服工单号!';
                    return json_encode($returnArr);
                }
            //插入到cs_repair表
            $reg_record = $connection->createCommand()->insert('cs_repair', [
                        'car_id' => $car_no,
                        'order_number' => $order_no,
                        //'car_model' => $car_model,
                        'sale_factory' => $sale_factory,
                        //'use_nature' => $use_nature,
                        'repair_price' => $repair_price,
                        'create_time' => date('Y-m-d H:i:s',time()),
                        'send_human' => $repair_person,
                        'send_phone' => $repair_person_tel,
                        'service_human' => $fuwu_person,
                        'service_phone' => $fuwu_person_tel,
                        'into_factory' => $into_factory,

                        'into_time' => $in_time,
                        'expect_time' => $expect_time,
                        'into_mile' => $into_mile,
                        'soc' => $soc,
                        'error_note' => $error_note,
                        'info_note' => $note,
                        'repair_img_o' => $photoa,
                        //'wheel_amount' => $wheel_amount,
                        'repair_img_t' => $photoc,
                        'task_info' => $task_info,

                        'last_time' => date('Y-m-d H:i:s',time())
                        ])->execute();
                    $repair_id=$connection->getLastInsertID();

            //插入到oa_field_record
            /*$reg_record_1 = $connection->createCommand()->insert('oa_field_record', [
                            'repair_id' => $repair_id['id'],
                            'time' => time()
                            ])->execute();*/
            //插入到cs_repair_part
                            /*echo "<pre>";
                            var_dump($part_info);
                            die;*/
            foreach($part_info as $key=>$val){
                 $reg_record_2 = $connection->createCommand()->insert('cs_repair_part', [
                        'part_number' => $val[1],
                        'part_fee' => $val[2],
                        'part_name' => $val[4],
                        'part_no' => $val[0],
                        'repair_id' => $repair_id,
                        'part_unit' =>$val[3],
                        'before_repair_time'=>$val[5],
                        'before_repair_li'=>$val[6],
                        'last_part_time'=>date('Y-m-d H:i:s',time())
                        ])->execute();
            }
            $transaction->commit();

            if($reg_record  && $reg_record_2){
                $returnArr['status'] = true;
                $returnArr['info'] = '添加成功!';
            } else {
                $transaction->rollback();
                $returnArr['status'] = false;
                $returnArr['info'] = '添加失败!';   
            }
            return json_encode($returnArr);
        }
        $cars = (new \yii\db\Query())->select('plate_number')->from('cs_car')->where('is_del = 0 ')->all();
        if(!$repair_company){
            $type['repair_company']='内部维修厂';
        }else{
            $type['repair_company']='外部维修厂';
        }
        return $this->render('add',['cars'=>$cars,'type'=>$type]);
    }
    //获取工单号
    public function actionGetOrder()
    {
        //维修厂类型0内部1外部
        $repair_company=0;
        $car_no = isset($_REQUEST['car_no']) ? trim($_REQUEST['car_no']) : '';
        //校验是否存在维修中的记录
        $check_status=(new \yii\db\Query())
            ->select('check_status')
            ->from('cs_repair')
            ->where(['car_id'=>$car_no])
            ->orderBy('create_time DESC')
            ->one();
            if($check_status){
                if($check_status['check_status']!=7){
                    $returnArr['status'] = false;
                    $returnArr['info'] = '该车存在维修中的维修单!';
                    return  json_encode($returnArr);
                }
            }
                

        $query = (new \yii\db\Query())
            ->select('order_no')
            ->from('oa_car_maintain')
            ->where(['status'=>'5','car_no'=>$car_no]);
            //->orderBy('order_no DESC');
        if($repair_company){
            $query->andFilterWhere(['like','order_no','BX']);
        }else{
            $query->andFilterWhere(['like','order_no','WX']);
        }
        $dat=$query->all();
        if($dat){
                foreach($dat as $key=>$val){
                    $query = (new \yii\db\Query())
                    ->select('id')
                    ->from('cs_repair')
                    ->where(['bill_status'=>'0','car_id'=>$car_no]);
                   // ->orderBy('order_no DESC');
                    $query->andFilterWhere(['like','order_number',$val['order_no']]);
                    $total = $query->count();
                    if($total>0){
                        $val['order_no']=$val['order_no']."-".$total;
                        $data[] = ['value'=>$val['order_no'],'text'=>$val['order_no']];
                    }else{
                       $data[] = ['value'=>$val['order_no'],'text'=>$val['order_no']];  
                    }
                }        
            
        }else{
            $data[] = ['value'=>'','text'=>'系统自动生成'];

        }
            $returnArr['status'] = true;
            $returnArr['info'] = $data;
            return  json_encode($returnArr);
            
    }
    //获取其他关联信息
    public function actionGetInfo()
    {
        date_default_timezone_set('PRC');
         $car_no = isset($_REQUEST['car_no']) ? trim($_REQUEST['car_no']) : '';
        return json_encode($this->getinfos($car_no));
         
    }
    public function getinfos($car_no)
    {
        $dat['data_1']  = (new \yii\db\Query())
                ->select('b.car_model_name,a.vehicle_dentification_number,c.name')
                ->from('cs_car a ')
                ->join('LEFT JOIN','cs_car_type b','b.id=a.car_type_id')
                ->join('LEFT JOIN','cs_owner c','c.id=a.owner_id')
                ->where("a.plate_number='".$car_no."'")
                ->one();
                $time_now=date('Y-m-d H:i:s',time());
        $dat['data_2']=(new \yii\db\Query())
                ->select('a.add_time a ,a.driving_mileage b')
                ->from('cs_maintain_record a')
                ->join('LEFT JOIN','cs_car b','b.id=a.car_id')
                ->where("b.plate_number='".$car_no."'")
                ->orderBy('a.add_time DESC')
                ->one();
                //$dat['data_2']->andwhere('create_time<)
                if(!$dat['data_2']){
                    $dat['data_2']=array();
                }
        $cards = array_merge($dat['data_1'],$dat['data_2']);
        return $cards;

    }
    //取的维修单工时信息
    public function actionGetTask()
    {
        $repair_id = isset($_REQUEST['id']) ? trim($_REQUEST['id']) : '';
        $data = (new \yii\db\Query())
            ->select('task_info')
            ->from('cs_repair')
            ->where('id='.$repair_id)
            ->one();
           $task_info=json_decode($data['task_info']);
           $task_info_new=array();
           foreach($task_info as $k3=>$v3){
            $task_info_new[$k3]['task_type']=$v3[0];
            $task_info_new[$k3]['task_name']=$v3[1];
            $task_info_new[$k3]['task_fee']=$v3[2];
            $task_info_new[$k3]['task_note']=$v3[3];    
            }
            return json_encode($task_info_new);
    }
    //取的维修单的配件信息
    public function actionGetPart()
    {
        $repair_id = isset($_REQUEST['id']) ? trim($_REQUEST['id']) : '';
        $data = (new \yii\db\Query())
            ->select('*')
            ->from('cs_repair_part')
            ->where('repair_id='.$repair_id." and is_del=0")
            ->all();
            foreach($data as $key=>$val){
                $data[$key]['part_total']=$val['part_fee']*$val['part_number'];
            }
        return json_encode($data);


    }
    //编辑维修单
    public function actionEdit()
    {
        $repair_id = isset($_REQUEST['id']) ? trim($_REQUEST['id']) : '';
        date_default_timezone_set('PRC');
        $connection = yii::$app->db;
        $data = (new \yii\db\Query())
            ->select('*')
            ->from('cs_repair')
            ->where('id='.$repair_id)
            ->one();
            /*$task_info=json_decode($data['task_info'])*/
            $task_info=json_decode($data['task_info']);
            $data['task_fee']=0;
            foreach($task_info as $key=>$val){
                $data['task_fee']+=$val[2];
            }
            $data['part_fee']=$data['repair_price']-$data['task_fee'];
            if($data['sale_factory']==1){
                $data['sale_factory']='外部维修厂';
            }else{
                $data['sale_factory']='内部维修厂';
            }
            $other_info=$this->getinfos($data['car_id']);
            $data=array_merge($data,$other_info);
            /*echo "<pre>";
            var_dump($data);
            die;*/
        if(yii::$app->request->isPost){
            /*echo "<pre>";
            var_dump($_POST);
            die;*/
            $repair_person = yii::$app->request->post('repair_person');//送修人
            if(!trim($repair_person)){
                $returnArr['status'] = false;
                $returnArr['info'] = '送修人未填!';
                return json_encode($returnArr);
            }
            $repair_person_tel = yii::$app->request->post('repair_person_tel');//送修人电话
            if(!preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#', $repair_person_tel) && !preg_match('|^\d{8}$|',$repair_person_tel)){
                $returnArr['status'] = false;
                $returnArr['info'] = '送修人电话格式不对!';
                return json_encode($returnArr);
            }
            $fuwu_person = yii::$app->request->post('fuwu_person');//服务人
            if(!trim($fuwu_person)){
                $returnArr['status'] = false;
                $returnArr['info'] = '服务顾问未填!';
                return json_encode($returnArr);
            }
            $fuwu_person_tel = yii::$app->request->post('fuwu_person_tel');//服务人电话
            if(!preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#', $fuwu_person_tel) && !preg_match('|^\d{8}$|',$fuwu_person_tel)){
                $returnArr['status'] = false;
                $returnArr['info'] = '服务顾问电话格式不对!';
                return json_encode($returnArr);
            }
            $in_time = yii::$app->request->post('in_time');//进厂时间
            $expect_time = yii::$app->request->post('expect_time');//出厂时间
            $into_mile = yii::$app->request->post('into_mile');//进厂里程
            if(!trim($fuwu_person)){
                $returnArr['status'] = false;
                $returnArr['info'] = '服务顾问未填!';
                return json_encode($returnArr);
            }
            $soc = yii::$app->request->post('soc');//电量
            if(!trim($soc)){
                $returnArr['status'] = false;
                $returnArr['info'] = '电量未填!';
                return json_encode($returnArr);
            }
            $error_note = yii::$app->request->post('error_note');//故障描述
            if(!trim($error_note)){
                $returnArr['status'] = false;
                $returnArr['info'] = '故障描述未填!';
                return json_encode($returnArr);
            }
            $note = yii::$app->request->post('note');//备注
            $photoa = yii::$app->request->post('repair_img_o');//车辆仪表盘
           /* if(!$photoa){
                $returnArr['status'] = false;
                $returnArr['info'] = '车辆仪表盘照片有误!';
                return json_encode($returnArr);
            }*/
            $photoc = yii::$app->request->post('repair_img_t');//故障位置
            $is_save_task = yii::$app->request->post('is_save_task');//是否保存工时
            $is_save_part = yii::$app->request->post('is_save_part');//是否保存配件
             if(!$is_save_task){
                $returnArr['status'] = false;
                $returnArr['info'] = '工时信息未保存!';
                return json_encode($returnArr);
            }
            if(!$is_save_part){
                $returnArr['status'] = false;
                $returnArr['info'] = '配件信息未保存!';
                return json_encode($returnArr);
            }
            $part_info = yii::$app->request->post('part_info');//配件信息
            if(!$part_info){
                $returnArr['status'] = false;
                $returnArr['info'] = '配件信息有误!';
                return json_encode($returnArr);
            }
            //$part_info =  preg_replace('/[\x00-\x1F\x80-\x9F]/u', '', trim($part_info));
            /*echo "<pre>";
                        var_dump($part_info);
                        die;*/
            $part_info=json_decode($part_info);
            $task_info = yii::$app->request->post('task_info');//工时信息
            if(!$task_info){
                $returnArr['status'] = false;
                $returnArr['info'] = '工时信息有误!';
                return json_encode($returnArr);
            }
            $repair_price = yii::$app->request->post('repair_price');//维修报价
            $sale_factory = yii::$app->request->post('sale_factory');//维修厂类型
            if($sale_factory=='外部维修厂'){
                $sale_factory=1;
            }else{
                $sale_factory=0;
            }
            $into_factory = yii::$app->request->post('into_factory');//是否拖车进厂
            if($into_factory==-1){
                $returnArr['status'] = false;
                $returnArr['info'] = '未选择是否拖车进厂!';
                return json_encode($returnArr);
            }
            $db = \Yii::$app->db;
            $transaction = $db->beginTransaction(); //开启事物
            //更新cs_repair表
            try {
                        $reg_record = $connection->createCommand()->update('cs_repair', [
                                    //'use_nature' => $use_nature,
                                    'repair_price' => $repair_price,
                                    'send_human' => $repair_person,
                                    'send_phone' => $repair_person_tel,
                                    'service_human' => $fuwu_person,
                                    'service_phone' => $fuwu_person_tel,
                                    'into_factory' => $into_factory,

                                    'into_time' => $in_time,
                                    'expect_time' => $expect_time,
                                    'into_mile' => $into_mile,
                                    'soc' => $soc,
                                    'error_note' => $error_note,
                                    'info_note' => $note,
                                    'task_info' => $task_info,
                                    'last_time' => date('Y-m-d H:i:s',time())
                                    ],
                                        'id=:id',
                                        array(':id'=>$repair_id))->execute();
                        //判断图片是否修改
                        if($photoa){
                            //echo 1111;die;
                            $reg_record_1 = $connection->createCommand()->update('cs_repair', [
                                    //'use_nature' => $use_nature,
                                    'repair_img_o' => $photoa
                                    ],
                                        'id=:id',
                                        array(':id'=>$repair_id))->execute();
                        }
                         if($photoc){
                            $reg_record_2 = $connection->createCommand()->update('cs_repair', [
                                    //'use_nature' => $use_nature,
                                    'repair_img_t' => $photoc
                                    ],
                                        'id=:id',
                                        array(':id'=>$repair_id))->execute();
                        }
                         $part_info_before = (new \yii\db\Query())
                                            ->select('*')
                                            ->from('cs_repair_part')
                                            ->where('repair_id='.$repair_id.' and is_del=0')
                                            ->all(); 
                        /*echo "<pre>";
                        var_dump($part_info_before);
                        echo "---";
                        var_dump($part_info);
                        die; */             
                        //判断配件信息是否修改
                        foreach($part_info as $key=>$value){
                            foreach($part_info_before as $k=>$v){
                                //新数据和老数据的配件编码相同，update，unsert()老数据
                                if($part_info[$key][0]==$part_info_before[$k]['part_no']){
                                 $reg_record_3=   $connection->createCommand()->update('cs_repair_part', [
                                    //'use_nature' => $use_nature,
                                    'part_number' => $value[1],
                                    'part_fee' => $value[2],
                                    'part_unit' => $value[3],
                                    'part_name' => $value[4],
                                    'last_part_time' => date('Y-m-d H:i:s',time())
                                    ],
                                        'repair_id=:repair_id  AND is_del =0 AND part_no=:part_no',
                                        array(':repair_id'=>$repair_id,'part_no'=>$v['part_no']))->execute();
                                    unset($part_info_before[$k]);
                                    unset($part_info[$key]);   
                                }
                                break;
                            }
                        }
                        //删除老数据剩余的数据
                        /*echo "<pre>";
                        var_dump($part_info_before);
                        die;*/
                        if($part_info_before){
                                foreach($part_info_before as $k=>$v){
                                    $reg_record_5=   $connection->createCommand()->update('cs_repair_part', [
                                            //'use_nature' => $use_nature,
                                            'is_del' => 1
                                            ],
                                                'repair_id=:repair_id   AND part_no=:part_no',
                                                array(':repair_id'=>$repair_id,'part_no'=>$v['part_no']))->execute();
                                }
                        }
                        //新数组剩余数据插入操作
                        if($part_info){
                            foreach($part_info as $k=>$value){
                                $reg_record_4 =$connection->createCommand()->insert('cs_repair_part', [
                                    'part_number' => $value[1],
                                    'part_fee' => $value[2],
                                    'part_name' => $value[4],
                                    'part_no' => $value[0],
                                    'repair_id' => $repair_id,
                                    'part_unit' =>$value[3],
                                    'before_repair_time'=>$value[5],
                                    'before_repair_li'=>$value[6],
                                    'last_part_time'=>date('Y-m-d H:i:s',time())
                                    ])->execute();
                            }
                        }
                            $transaction->commit();
                            $returnArr['status'] = true;
                            $returnArr['info'] = '修改成功!';
                } catch (Exception $e) {
                    $transaction->rollback();
                    $returnArr['status'] = false;
                    $returnArr['info'] = '修改失败!';   
                    }

                        return json_encode($returnArr);
        }
        
        return $this->render('edit',['repair_id'=>$repair_id,'data'=>$data]);
    }
    //审核维修单
    public function actionCheck()
    {
       
        date_default_timezone_set('PRC');
        $connection = yii::$app->db;
        if(yii::$app->request->isPost){
            $id=yii::$app->request->post('id');
            $check_status=yii::$app->request->get('status');
            //通过
            if($check_status){
                $result=   $connection->createCommand()->update('cs_repair', [
                                            //'use_nature' => $use_nature,
                                            'project_human'=> $_SESSION['backend']['adminInfo']['username'],
                                            'check_status' => 3,
                                            'last_time'    =>date('Y-m-d H:i:s',time())
                                            ],
                                                'id=:id',
                                                array(':id'=>$id))->execute();
                $returnArr['status'] = true;
                $returnArr['info'] = '已同意!';
            }

            return json_encode($returnArr);
        }
         $repair_id = isset($_REQUEST['id']) ? trim($_REQUEST['id']) : '';
         $data = (new \yii\db\Query())
            ->select('*')
            ->from('cs_repair')
            ->where('id='.$repair_id)
            ->one();
            /*$task_info=json_decode($data['task_info'])*/
            $task_info=json_decode($data['task_info']);
            $data['task_fee']=0;
            foreach($task_info as $key=>$val){
                $data['task_fee']+=$val[2];
            }
            $data['part_fee']=$data['repair_price']-$data['task_fee'];
            if($data['sale_factory']==1){
                $data['sale_factory']='外部维修厂';
            }else{
                $data['sale_factory']='内部维修厂';
            }
            $other_info=$this->getinfos($data['car_id']);
            $data=array_merge($data,$other_info);
        
        return $this->render('check',['data'=>$data]);
    }
       //审核完工结算
    public function actionCheckMoney()
    {
       
        date_default_timezone_set('PRC');
        $connection = yii::$app->db;
        
            /*echo "<pre>";
            var_dump($data);
            die;*/
        if(yii::$app->request->isPost){
            $id=yii::$app->request->post('id');
            $check_status=yii::$app->request->get('status');
            //通过
            if($check_status){
                $result=   $connection->createCommand()->update('cs_repair', [
                                            //'use_nature' => $use_nature,
                                            'account_human'=> $_SESSION['backend']['adminInfo']['username'],
                                            'check_status' => 5,
                                            'last_time'    =>date('Y-m-d H:i:s',time())
                                            ],
                                                'id=:id',
                                                array(':id'=>$id))->execute();
                $returnArr['status'] = true;
                $returnArr['info'] = '已同意!';
            }
                return json_encode($returnArr);
        }
         $repair_id = isset($_REQUEST['id']) ? trim($_REQUEST['id']) : '';
         $data = (new \yii\db\Query())
            ->select('*')
            ->from('cs_repair')
            ->where('id='.$repair_id)
            ->one();
            /*$task_info=json_decode($data['task_info'])*/
            $task_info=json_decode($data['task_info']);
            $data['task_fee']=0;
            foreach($task_info as $key=>$val){
                $data['task_fee']+=$val[2];
            }
            $data['part_fee']=$data['repair_price']-$data['task_fee'];
            if($data['sale_factory']==1){
                $data['sale_factory']='外部维修厂';
            }else{
                $data['sale_factory']='内部维修厂';
            }
            $other_info=$this->getinfos($data['car_id']);
            $data=array_merge($data,$other_info);
        
        return $this->render('check-money',['data'=>$data]);
    }
    //驳回
    public function actionBohui()
    {
        date_default_timezone_set('PRC');
        $connection = yii::$app->db;
        if(yii::$app->request->isPost){
            $note=trim(yii::$app->request->post('a'));
            $note=explode("=", $note);
            if(!trim($note[1])){
                $returnArr['status'] = false;
                $returnArr['info'] = '请填写驳回备注!';
                return  json_encode($returnArr);
            }
            if(yii::$app->request->post('b')){
                $info=yii::$app->request->post('b');
            }else{
                $info=yii::$app->request->post('c');
            }
            
            $info=explode('&',$info);
            $ids=explode('=', $info[2]);
            $check_1=explode('=', $info[3]);
            $id=$ids[1];;
            $check_1=$check_1[1];
            //通过
            if($check_1){
                $result=   $connection->createCommand()->update('cs_repair', [
                                            //'use_nature' => $use_nature,
                                            'project_human'=> $_SESSION['backend']['adminInfo']['username'],
                                            'check_status' => 2,
                                            'repair_note'  => $note[1],
                                            'last_time'    =>date('Y-m-d H:i:s',time())
                                            ],
                                                'id=:id',
                                                array(':id'=>$id))->execute();
                $returnArr['status'] = true;
                $returnArr['info'] = '已驳回!';
            }else{
                $result=   $connection->createCommand()->update('cs_repair', [
                                            //'use_nature' => $use_nature,
                                            'account_human'=> $_SESSION['backend']['adminInfo']['username'],
                                            'check_status' => 4,
                                            'finish_note'  => $note[1],
                                            'last_time'    =>date('Y-m-d H:i:s',time())
                                            ],
                                                'id=:id',
                                                array(':id'=>$id))->execute();
                $returnArr['status'] = true;
                $returnArr['info'] = '已驳回!';
            }

            return json_encode($returnArr);
        }
        return $this->render('bohui');

    }
     //完工结算
    public function actionFinish()
    {
        $repair_id = isset($_REQUEST['id']) ? trim($_REQUEST['id']) : '';
        date_default_timezone_set('PRC');
        $connection = yii::$app->db;
        $data = (new \yii\db\Query())
            ->select('*')
            ->from('cs_repair')
            ->where('id='.$repair_id)
            ->one();
            /*$task_info=json_decode($data['task_info'])*/
            $task_info=json_decode($data['task_info']);
            $data['task_fee']=0;
            foreach($task_info as $key=>$val){
                $data['task_fee']+=$val[2];
            }
            $data['part_fee']=$data['repair_price']-$data['task_fee'];
            if($data['sale_factory']==1){
                $data['sale_factory']='外部维修厂';
            }else{
                $data['sale_factory']='内部维修厂';
            }
            $other_info=$this->getinfos($data['car_id']);
            $data=array_merge($data,$other_info);
            /*echo "<pre>";
            var_dump($data);
            die;*/
        if(yii::$app->request->isPost){
            /*echo "<pre>";
            var_dump($_POST);
            die;*/
            $is_save_task = yii::$app->request->post('is_save_task');//是否保存工时
            $is_save_part = yii::$app->request->post('is_save_part');//是否保存配件
             if(!$is_save_task){
                $returnArr['status'] = false;
                $returnArr['info'] = '工时信息未保存!';
                return json_encode($returnArr);
            }
            if(!$is_save_part){
                $returnArr['status'] = false;
                $returnArr['info'] = '配件信息未保存!';
                return json_encode($returnArr);
            }
            $part_info = yii::$app->request->post('part_info');//配件信息
            $repair_price = yii::$app->request->post('repair_price');//维修报价
          
            
            $part_info=json_decode($part_info);
            $task_info = yii::$app->request->post('task_info');//工时信息
            $db = \Yii::$app->db;
            $transaction = $db->beginTransaction(); //开启事物
            //更新cs_repair表
            try {
                        $reg_record = $connection->createCommand()->update('cs_repair', [
                                    //'use_nature' => $use_nature,
                                    'repair_price' => $repair_price,
                                    'task_info' => $task_info,
                                    'last_time' => date('Y-m-d H:i:s',time())
                                    ],
                                        'id=:id',
                                        array(':id'=>$repair_id))->execute();
                        foreach($part_info as $key=>$value){
                            
                                //新数据和老数据的配件编码相同，update，unsert()老数据
                                 $reg_record_3=   $connection->createCommand()->update('cs_repair_part', [
                                    //'use_nature' => $use_nature,
                                    'part_number' => $value[1],
                                    'part_fee' => $value[2],
                                    'last_part_time' => date('Y-m-d H:i:s',time())
                                    ],
                                        'repair_id=:repair_id  AND is_del =0 AND part_no=:part_no',
                                        array(':repair_id'=>$repair_id,'part_no'=>$value[0]))->execute();  
                               
                        }
                            $transaction->commit();
                            $returnArr['status'] = true;
                            $returnArr['info'] = '修改成功!';
                } catch (Exception $e) {
                    $transaction->rollback();
                    $returnArr['status'] = false;
                    $returnArr['info'] = '修改失败!';   
                    }

                        return json_encode($returnArr);
        }
        
        return $this->render('finish',['repair_id'=>$repair_id,'data'=>$data]);
    }

    //打印
    public function actionPrint()
    {
        $print = isset($_REQUEST['print']) ? trim($_REQUEST['print']) : '';
        $query = (new \yii\db\Query())
            ->select('a.*,a.expect_time as expect_time_feng,b.*,b.id as match_type_id,c.*,d.id,d.car_type_id,d.vehicle_dentification_number,d.owner_id,d.plate_number,e.id,e.car_model_name,f.*,g.name as company_name,h.driving_mileage,h.add_time,i.*,j.*')
            ->from('cs_repair as a')
            ->leftjoin('oa_car_maintain as b','a.order_number = b.order_no')
            ->leftjoin('oa_repair as c','a.order_number = c.order_no')
            ->leftjoin('cs_car as d','a.car_id = d.plate_number')
            ->leftjoin('cs_car_type as e','d.car_type_id = e.id')
            ->leftjoin('oa_field_record as f','c.id = f.repair_id')
            ->leftjoin('cs_owner as g','d.owner_id = g.id')
            ->leftjoin('cs_maintain_record as h','d.id = h.car_id')
            ->leftjoin('cs_maintain_type as i','h.type = i.id')
            ->leftjoin('oa_car_maintain_fault as j','b.id = j.maintain_id')
            ->where(['a.is_del'=>0,'a.id'=>$print])
            ->orderBy('add_time DESC');
        $detail_data = $query->one();
        $detail_data['task_info'] = json_decode($detail_data['task_info']);
        $query = (new \yii\db\Query())
            ->select('id,tier_pid')
            ->from('oa_car_maintain_fault')
            ->where(['maintain_id'=>$detail_data['match_type_id']]);
        $fault_type = $query->one();
        $fault_type = ltrim($fault_type['tier_pid'],",");
        $fault_type = rtrim($fault_type,",");
        $fault_type =  explode(",",$fault_type);
        $name_and_name = '';
        foreach ($fault_type  as $k=>$v){
            $query = (new \yii\db\Query())
                ->select('category')
                ->from('oa_fault_category')
                ->where(['id'=>$v]);
            $three_name = $query->one();
            $name_and_name .= '-'.$three_name['category'];
        }
        $name_and_name = ltrim($name_and_name,'-');
        //工单类型
        if($detail_data[order_type] == 1){
            $detail_data[order_type] = '我方报修';
        }else{
            $detail_data[order_type] = '客户报修';
        }
        //工单来源
        if($detail_data[source] == 1){
            $detail_data[source] = '400电话';
        }else{
            $detail_data[source] = '未知来源';
        }
        $detail_data['tel_time'] = date('Y-m-d H:i:s',$detail_data['tel_time']);
        //紧急程度
        if($detail_data['urgency'] == 1){
            $detail_data['urgency'] = '一般紧急';
        }elseif ($detail_data['urgency'] == 2){
            $detail_data['urgency'] = '比较紧急';
        }else{
            $detail_data['urgency'] = '非常紧急';
        }
        $detail_data['fault_start_time'] = date('Y-m-d H:i:s',$detail_data['fault_start_time']);
        $detail_data['tel_time'] = date('Y-m-d H:i:s',$detail_data['tel_time']);
        if($detail_data['is_attendance'] == 1){
            $detail_data['is_attendance'] = '是';
        }else{
            $detail_data['is_attendance'] = '否';
        }
        if($detail_data['is_use_car'] == 1){
            $detail_data['is_use_car'] = '是';
        }else{
            $detail_data['is_use_car'] = '否';
        }
        //抵达时间
        $detail_data['arrive_time'] = date('Y-m-d H:i:s',$detail_data['arrive_time']);
        if($detail_data['is_go_scene'] == 1){
            $detail_data['is_go_scene'] = '是';
        }else{
            $detail_data['is_go_scene'] = '否';
        }
        if($detail_data['replace_car']){
            $detail_data['is_need_replace_car'] = '是';
        }else{
            $detail_data['is_need_replace_car'] = '否';
        }
        //替换开始时间
        $detail_data['replace_start_time'] = empty($detail_data['replace_start_time']) ? '未填写' : date('Y-m-d H:i:s',$detail_data['replace_start_time']);
        $detail_data['replace_end_time'] = empty($detail_data['replace_end_time']) ? '未填写' : date('Y-m-d H:i:s',$detail_data['replace_end_time']);
        $detail_data['sale_factory'] = $detail_data['sale_factory'] == 1 ? '外部维修厂' : '内部维修厂';
        $detail_data['into_factory'] = $detail_data['into_factory'] == 1 ? '是' : '否';
        $use_time = strtotime($detail_data['expect_time_feng']) - strtotime($detail_data['into_time']);
        $detail_data['use_time'] = intval($use_time/3600);
        //维修配件
        $query = (new \yii\db\Query())
            ->select('*')
            ->from('cs_repair_part')
            ->where(['is_del'=>0,'repair_id'=>$print]);
        $repair_part = $query->all();
        //配件数量价格综合
        if($repair_part){
            foreach ($repair_part as $k=>$v){
                $part_price = bcmul($v['part_number'],$v['part_fee'],2);
                $all_part_price = bcadd($all_part_price,$part_price,2);
                $msg3 .= '<tr>
            <td width="93" valign="center" colspan="1"  style="width:93px;">
                <font size="2">'.$v[part_name].'</font>
            </td>
            <td width="93" valign="center" colspan="1"  style="width:93px;">
                <font size="2">'.$v[part_fee].'</font>
            </td>    
            <td width="93" valign="center" colspan="1"  style="width:93px;">
                <font size="2">'.$v[part_number].'</font>
            </td>
            <td width="93" valign="center" colspan="1"  style="width:93px;">
                <font size="2">'.$v[part_unit].'</font>
            </td>
            <td width="93" valign="center" colspan="1"  style="width:93px;">
                <font size="2">'.$part_price.'</font>
            </td>
            <td width="93" valign="center" colspan="3"  style="width:93px;">
                <font size="2">'.$v[before_repair_time].'</font>
            </td>    
            <td width="93" valign="center" colspan="3"  style="width:93px;">
                <font size="2">'.$v[before_repair_li].'</font>
            </td>
            <td width="93" valign="center" colspan="2"  style="width:93px;">
                <font size="2">配件质保期</font>
            </td>
        </tr>';
            };
        }
        if($detail_data['task_info']){
            foreach ($detail_data['task_info'] as $k=>$v){
                $msg2 .= '<tr>
                <td width="93" valign="center" colspan="2"  style="width:93px;">
                    <font size="2">'.$v[0].'</font>
                </td>
                <td width="93" valign="center" colspan="2"  style="width:93px;">
                    <font size="2">'.$v[1].'</font>
                </td>
                <td width="93" valign="center" colspan="2"  style="width:93px;">
                    <font size="2">'.$v[2].'</font>
                </td>
                <td width="93" valign="center" colspan="7"  style="width:93px;">
                    <font size="2">'.$v[3].'</font>
                </td>
            </tr>';
                $time_price = bcadd($time_price,$v['2'],2);
            }
            $total_price = bcadd($all_part_price,$time_price,2);
        }
//        var_dump($msg2);die;
        header("Content-type:text/html;charset=utf-8");
        $msg = '<table border="1" cellpadding="3" cellspacing="0">
        <tr>
            <td width="90" style="text-align:center;" valign="center" colspan="13">
                <strong>车辆'.$detail_data[plate_number].'维修完工结算单</strong>
            </td>
        </tr>
        <tr>
            <td width="93" valign="center" colspan="13"  style="width:93px;">
                <strong><font size="2">工单号:'.$detail_data[order_number].'</font></strong>
            </td>
        </tr>
        <tr>
            <td width="93" valign="center" colspan="13" style="width:93px;">
                <strong><font size="2">接单信息</font></strong>
            </td>
        </tr>
        <tr>
            <td width="93" valign="center" colspan="6"  style="width:93px;">
                <font size="2">工单类型：'.$detail_data[order_type].'</font>
            </td>
            <td width="93" valign="center" colspan="7"  style="width:93px;">
                <font size="2">工单来源：'.$detail_data[source].'</font>
            </td>
        </tr>
        <tr>
            <td width="93" valign="center" colspan="3"  style="width:93px;">
                <font size="2">报修人姓名：'.$detail_data[repair_name].'</font>
            </td>
            <td width="93" valign="center" colspan="3"  style="width:93px;">
                <font size="2">来电号码：'.$detail_data[tel].'</font>
            </td>
            <td width="93" valign="center" colspan="7"  style="width:93px;">
                <font size="2">来电时间：'.$detail_data[tel_time].'</font>
            </td>
        </tr>
        <tr>
            <td width="93" valign="center" colspan="13"  style="width:93px;">
                <font size="2">客户公司名称：'.$detail_data[order_number].'</font>
            </td>
        </tr>
        <tr>
            <td width="93" valign="center" colspan="3"  style="width:93px;">
                <font size="2">紧急程度：'.$detail_data[urgency].'</font>
            </td>
            <td width="93" valign="center" colspan="3"  style="width:93px;">
                <font size="2">车牌号：'.$detail_data[car_no].'</font>
            </td>
            <td width="93" valign="center" colspan="7"  style="width:93px;">
                <font size="2">车型：'.$detail_data[car_model_name].'</font>
            </td>
        </tr>
        <tr>
            <td width="93" valign="center" colspan="6"  style="width:93px;">
                <font size="2">故障发生时间：'.$detail_data[fault_start_time].'</font>
            </td>
            <td width="93" valign="center" colspan="7"  style="width:93px;">
                <font size="2">故障地点：'.$detail_data[address].'</font>
            </td>
        </tr>
        <tr>
            <td width="93" valign="center" colspan="13"  style="width:93px;">
                <font size="2">工单内容简述：'.$detail_data[desc].'</font>
            </td>
        </tr>
        <tr>
            <td width="93" valign="center" colspan="13"  style="width:93px;">
                <font size="2">来电内容记录：'.$detail_data[tel_content].'</font>
            </td>
        </tr>
        <tr>
            <td width="93" valign="center" colspan="13"  style="width:93px;">
                <font size="2">所需服务：'.$detail_data[need_serve].'</font>
            </td>
        </tr>
        <tr>
            <td width="93" valign="center" colspan="13"  style="width:93px;">
                <strong><font size="2">派单信息</font></strong>
            </td>
        </tr>
        <tr>
            <td width="93" valign="center" colspan="3"  style="width:93px;">
                <font size="2">派单对象：'.$detail_data[assign_name].'</font>
            </td>
            <td width="93" valign="center" colspan="3"  style="width:93px;">
                <font size="2">需要外勤：'.$detail_data[is_attendance].'</font>
            </td>
            <td width="93" valign="center" colspan="7"  style="width:93px;">
                <font size="2">携带设备：'.$detail_data[carry].'</font>
            </td>
        </tr>
        <tr>
            <td width="93" valign="center" colspan="6"  style="width:93px;">
                <font size="2">需申请用车：'.$detail_data[is_use_car].'</font>
            </td>
            <td width="93" valign="center" colspan="7"  style="width:93px;">
                <font size="2">外勤用车车牌号：'.$detail_data[use_car_no].'</font>
            </td>
        </tr>
        <tr>
            <td width="93" valign="center" colspan="13"  style="width:93px;">
                <strong><font size="2">外勤服务信息</font></strong>
            </td>
        </tr>
        <tr>
            <td width="93" valign="center" colspan="13"  style="width:93px;">
                <font size="2">抵达现场时间：'.$detail_data[arrive_time].'</font>
            </td>
        </tr>
        <tr>
            <td width="93" valign="center" colspan="13"  style="width:93px;">
                <font size="2">现场故障描述：'.$detail_data[scene_desc].'</font>
            </td>
        </tr>
        <tr>
            <td width="93" valign="center" colspan="13"  style="width:93px;">
                <font size="2">现场处理结果：'.$detail_data[scene_result].'</font>
            </td>
        </tr>
        <tr>
            <td width="93" valign="center" colspan="6"  style="width:93px;">
                <font size="2">是否进厂维修：'.$detail_data[is_go_scene].'</font>
            </td>
            <td width="93" valign="center" colspan="7"  style="width:93px;">
                <font size="2">维修场站：'.$detail_data[maintain_scene].'</font>
            </td>
        </tr>
        <tr>
            <td width="93" valign="center" colspan="6"  style="width:93px;">
                <font size="2">是否替换车辆：'.$detail_data[is_need_replace_car].'</font>
            </td>
            <td width="93" valign="center" colspan="7"  style="width:93px;">
                <font size="2">替换车：'.$detail_data[replace_car].'</font>
            </td>
        </tr>
        <tr>
            <td width="93" valign="center" colspan="3"  style="width:93px;">
                <font size="2">替换开始时间：'.$detail_data[replace_start_time].'</font>
            </td>
            <td width="93" valign="center" colspan="5"  style="width:93px;">
                <font size="2">预计归还时间：'.$detail_data[replace_end_time].'</font>
            </td>    
            <td width="93" valign="center" colspan="5"  style="width:93px;">
                <strong><font size="2">外勤服务：'.$detail_data[accept_name].'</font></strong>
            </td>
        </tr> 
        <tr>
            <td width="93" valign="center" colspan="13"  style="width:93px;">
                <strong><font size="2">维修结算</font></strong>
            </td>
        </tr>
        <tr>
            <td width="93" valign="center" colspan="3"  style="width:93px;">
                <font size="2">维修厂：'.$detail_data[sale_factory].'</font>
            </td>
            <td width="93" valign="center" colspan="5"  style="width:93px;">
                <font size="2">车牌号：'.$detail_data[car_id].'</font>
            </td>    
            <td width="93" valign="center" colspan="5"  style="width:93px;">
                <font size="2">车架号：'.$detail_data[vehicle_dentification_number].'</font>
            </td>
        </tr>
        <tr>
            <td width="93" valign="center" colspan="3"  style="width:93px;">
                <font size="2">机动车所有人：'.$detail_data[company_name].'</font>
            </td>
            <td width="93" valign="center" colspan="5"  style="width:93px;">
                <font size="2">送修人：'.$detail_data[send_human].'</font>
            </td>    
            <td width="93" valign="center" colspan="5"  style="width:93px;">
                <font size="2">送修人电话：'.$detail_data[send_phone].'</font>
            </td>
        </tr>
        <tr>
            <td width="93" valign="center" colspan="3"  style="width:93px;">
                <font size="2">进厂时间：'.$detail_data[into_time].'</font>
            </td>
            <td width="93" valign="center" colspan="5"  style="width:93px;">
                <font size="2">出厂时间：'.$detail_data[expect_time_feng].'</font>
            </td>    
            <td width="93" valign="center" colspan="5"  style="width:93px;">
                <font size="2">进厂里程：'.$detail_data[into_mile].'</font>
            </td>
        </tr>
        <tr>
            <td width="93" valign="center" colspan="3"  style="width:93px;">
                <font size="2">上次保养里程：'.$detail_data[driving_mileage].'</font>
            </td>
            <td width="93" valign="center" colspan="5"  style="width:93px;">
                <font size="2">上次保养时间：'.$detail_data[add_time].'</font>
            </td>    
            <td width="93" valign="center" colspan="5"  style="width:93px;">
                <font size="2">SOC：'.$detail_data[soc].'</font>
            </td>
        </tr>
        <tr>
            <td width="93" valign="center" colspan="3"  style="width:93px;">
                <font size="2">服务顾问：'.$detail_data[service_human].'</font>
            </td>
            <td width="93" valign="center" colspan="5"  style="width:93px;">
                <font size="2">服务顾问电话：'.$detail_data[service_phone].'</font>
            </td>    
            <td width="93" valign="center" colspan="5"  style="width:93px;">
                <font size="2">拖车进厂：'.$detail_data[into_factory].'</font>
            </td>
        </tr>
        <tr>
            <td width="93" valign="center" colspan="13"  style="width:93px;">
                <font size="2">进厂时长：'.$detail_data[use_time].'小时</font>
            </td>
        </tr>
         <tr>
            <td width="93" valign="center" colspan="13"  style="width:93px;">
                <strong><font size="2">保养标准</font></strong>
            </td>
        </tr>
        <tr>
            <td width="93" valign="center" colspan="2"  style="width:93px;">
                <font size="2">车型名称</font>
            </td>
            <td width="93" valign="center" colspan="2"  style="width:93px;">
                <font size="2">保养类型</font>
            </td>    
            <td width="93" valign="center" colspan="9"  style="width:93px;">
                <font size="2">描述</font>
            </td>
        </tr>
        <tr>
            <td width="93" valign="center" colspan="2"  style="width:93px;">
                <font size="2">'.$detail_data[car_model_name].'</font>
            </td>
            <td width="93" valign="center" colspan="2"  style="width:93px;">
                <font size="2">'.$detail_data[maintain_type].'</font>
            </td>    
            <td width="93" valign="center" colspan="9"  style="width:93px;">
                <font size="2">'.$detail_data[maintain_des].'</font>
            </td>
        </tr>
        <tr>
            <td width="93" valign="center" colspan="2"  style="width:93px;">
                <strong><font size="2">故障分类</font></strong>
            </td>
            <td width="93" valign="center" colspan="8"  style="width:93px;">
                <strong><font size="2">故障名称</font></strong>
            </td>
            <td width="93" valign="center" colspan="3"  style="width:93px;">
                <strong><font size="2">故障代码</font></strong>
            </td>
        </tr>
        <tr>
            <td width="93" valign="center" colspan="2"  style="width:93px;">
                <strong><font size="2">'.$name_and_name.'</font></strong>
            </td>
            <td width="93" valign="center" colspan="8"  style="width:93px;">
                <strong><font size="2">'.$detail_data[category].'</font></strong>
            </td>
            <td width="93" valign="center" colspan="3"  style="width:93px;">
                <strong><font size="2">'.$detail_data[total_code].'</font></strong>
            </td>
        </tr>
        <tr>
            <td width="93" valign="center" colspan="13"  style="width:93px;">
                <strong><font size="2">工时信息</font></strong>
            </td>
        </tr>
        <tr>
            <td width="93" valign="center" colspan="2"  style="width:93px;">
                <font size="2">维修类型</font>
            </td>
            <td width="93" valign="center" colspan="2"  style="width:93px;">
                <font size="2">维修项目名称</font>
            </td>    
            <td width="93" valign="center" colspan="2"  style="width:93px;">
                <font size="2">工时费金额</font>
            </td>
            <td width="93" valign="center" colspan="7"  style="width:93px;">
                <font size="2">备注</font>
            </td>
        </tr>';
        $msg .= $msg2;
        $msg .='<tr>
            <td width="93" valign="center" colspan="13"  style="width:93px;">
                <font size="2">合计：'.$time_price.'</font>
            </td>
        </tr>
        <tr>
            <td width="93" valign="center" colspan="13"  style="width:93px;">
                <strong><font size="2">配件信息</font></strong>
            </td>
        </tr>
        <tr>
            <td width="93" valign="center" colspan="1"  style="width:93px;">
                <font size="2">配件名称</font>
            </td>
            <td width="93" valign="center" colspan="1"  style="width:93px;">
                <font size="2">单价</font>
            </td>    
            <td width="93" valign="center" colspan="1"  style="width:93px;">
                <font size="2">数量</font>
            </td>
            <td width="93" valign="center" colspan="1"  style="width:93px;">
                <font size="2">单位</font>
            </td>
            <td width="93" valign="center" colspan="1"  style="width:93px;">
                <font size="2">配件金额</font>
            </td>
            <td width="93" valign="center" colspan="3"  style="width:93px;">
                <font size="2">上次维修时间</font>
            </td>    
            <td width="93" valign="center" colspan="3"  style="width:93px;">
                <font size="2">上次维修里程</font>
            </td>
            <td width="93" valign="center" colspan="2"  style="width:93px;">
                <font size="2">配件质保期</font>
            </td>
        </tr>';
        $msg .= $msg3;
        $msg .='<tr>
            <td width="93" valign="center" colspan="13"  style="width:93px;">
                <strong><font size="2">合计：'.$all_part_price.'</font></strong>
            </td>
        </tr>
        <tr>
            <td width="93" valign="center" colspan="13"  style="width:93px;">
                <strong><font size="2">总金额：'.$total_price.'</font></strong>
            </td>
        </tr>
</table>';
        echo $msg;
        echo '<script type="text/javascript">'."\n";
        echo 'window.print();'."\n";
        echo '</script>'."\n";die;
    }

    //作废
    public function actionAbandon()
    {
        $repair_id = isset($_REQUEST['id']) ? trim($_REQUEST['id']) : '';
        $now_time = date('Y-m-d H:i:s',time());
        $result = Yii::$app->db->createCommand()->update('cs_parts_info', [
            'bill_status' => 0,
            'last_time' => $now_time,
        ],"id=$repair_id")->execute();
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

    public function actionDel()
    {
        $repair_id = isset($_REQUEST['id']) ? trim($_REQUEST['id']) : '';
        $now_time = date('Y-m-d H:i:s',time());
        $result = Yii::$app->db->createCommand()->update('cs_parts_info', [
            'is_del' => 1,
            'last_time' => $now_time,
        ],"id=$repair_id")->execute();
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

    //付款凭证
    public function actionPayMoney()
    {
//        if(yii::$app->request->isGet){
//            $id = isset($_REQUEST['id']) ? trim($_REQUEST['id']) : '';
//            if(!$id){
//                $msg['status'] = 0;
//                $msg['info'] = '维修方案不存在!';
//                echo json_encode($msg);die;
//            }
//            $query = (new \yii\db\Query())
//                ->select('id,money_note,money_img')
//                ->from('cs_repair')
//                ->where(['is_del'=>0,'id'=>$id]);
//            $data = $query->one();
//        }
        if(yii::$app->request->isPost){
            $go_back = yii::$app->request->post('go_back');
            $go_back = isset($go_back) ? trim($go_back) : '';
            //驳回操作
            if($go_back){
                $go_back = ltrim($go_back,',');
                $go_back = explode(',',$go_back);
                $db = \Yii::$app->db;
                foreach ($go_back as $k=>$v){
                    $transaction = $db->beginTransaction();
                    try {
                        $result = Yii::$app->db->createCommand()->update('cs_repair', [
                            'check_status' => 6,
                            'last_time' => date('Y-m-d H:i:s',time()),
                        ],"id=$v")->execute();
                        $transaction->commit();
                    } catch (Exception $e) {
                        $transaction->rollback();
                    }
                }
                if($result){
                    $msg['status'] = 1;
                    $msg['info'] = '驳回成功!';
                    echo json_encode($msg);die;
                }else{
                    $msg['status'] = 0;
                    $msg['info'] = '驳回失败!';
                    echo json_encode($msg);die;
                }
            }
            //付款成功操作
            $money_note = trim(yii::$app->request->post('money_note'));
            $money_img = trim(yii::$app->request->post('money_img'));
            $order_id = trim(yii::$app->request->post('order_id'));
            if($money_note !=' ' && $money_img && $order_id){
                $order_id = ltrim($order_id,',');
                $order_id = explode(',',$order_id);
                $db = \Yii::$app->db;
                foreach ($order_id as $k=>$v){
                    $transaction = $db->beginTransaction();
                    try {
                    $result = Yii::$app->db->createCommand()->update('cs_repair', [
                        'check_status' => 7,
                        'money_note' => $money_note,
                        'money_img' => $money_img,
                        'money_human' => $_SESSION['backend']['adminInfo']['username'],
                        'last_time' => date('Y-m-d H:i:s',time()),
                        'finish_time' => date('Y-m-d H:i:s',time()),
                    ],"id=$v")->execute();
                        $transaction->commit();
                    } catch (Exception $e) {
                        $transaction->rollback();
                    }
                }
                if($result){
                    $msg['status'] = 1;
                    $msg['info'] = '付款成功!';
                    echo json_encode($msg);die;
                }else{
                    $msg['status'] = 0;
                    $msg['info'] = '付款失败!';
                    echo json_encode($msg);die;
                }
            }
            $msg['status'] = 0;
            $msg['info'] = '备注或者凭证未填写!';
            echo json_encode($msg);die;
        }
        return $this->render('money');
    }

    //查看方案详情
    public function actionSee()
    {
        $id = isset($_REQUEST['id']) ? trim($_REQUEST['id']) : '';
        $query = (new \yii\db\Query())
            ->select('a.*,b.plate_number,b.car_type_id,b.vehicle_dentification_number,c.car_model_name,d.name as owner_name,e.add_time,e.driving_mileage,f.*')
            ->from('cs_repair as a')
            ->leftJoin('cs_car as b','a.car_id = b.plate_number')
            ->leftJoin('cs_car_type as c','b.car_type_id = c.id')
            ->leftJoin('cs_owner as d','b.owner_id = d.id')
            ->leftJoin('cs_maintain_record as e','b.id = e.car_id')
            ->leftJoin('cs_maintain_type as f','e.type = f.id')
            ->where(['a.is_del'=>0,'a.id'=>$id]);
        $detail_data = $query->one();
        $detail_data['task_info'] = json_decode($detail_data['task_info']);
        $query = (new \yii\db\Query())
            ->select('*')
            ->from('cs_repair_part')
            ->where(['is_del'=>0,'repair_id'=>$id]);
        $repair_part = $query->all();
        //配件数量价格综合
        foreach ($repair_part as $k=>$v){
            $repair_part[$k]['now_price'] = bcmul($v['part_number'],$v['part_fee'],2);
        }
        return $this->render('see',['detail_data'=>$detail_data,'repair_part'=>$repair_part]);
    }
   

    public function actionGetList()
    {
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $query = (new \yii\db\Query())
            ->select('a.*,b.maintain_scene,c.site_name')
            ->from('cs_repair as a')
            ->leftJoin('oa_car_maintain as b','a.order_number = b.order_no')
            ->leftJoin('oa_service_site as c','a.sale_factory = c.id')
            ->where(['a.is_del'=>'0']);
        if (yii::$app->request->isGet){
            $dat = $_GET;
            if($dat['car_id'] != ''){
                $query->andFilterWhere(['like','a.car_id',addslashes(trim($dat['car_id']))]);
            }
            if($dat['order_number'] != ''){
                $query->andFilterWhere(['like','order_number',addslashes(trim($dat['order_number']))]);
            }
            if($dat['order_type'] != ''){
                $order_type = addslashes(trim($dat['order_type']));
                if($order_type == 1){
                    $order_type = 'WX';
                }elseif ($order_type == 2){
                    $order_type = 'BX';
                }else{
                    $order_type = ' ';
                }
                $query->andFilterWhere(['like','order_number',$order_type]);
            }
            if($dat['sale_factory'] != ''){
                $query->andFilterWhere(['like','b.maintain_scene',addslashes(trim($dat['sale_factory']))]);
            }
            if($dat['check_status'] != ''){
                $query->andFilterWhere(['=','a.check_status',addslashes(trim($dat['check_status']))]);
            }
            if($dat['bill_status'] != ''){
                $query->andFilterWhere(['=','bill_status',addslashes(trim($dat['bill_status']))]);
            }
        }
        $total = $query->count();
//        var_dump($total);exit;
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)->limit($pages->limit)->all();
//        echo '<pre>';
//        var_dump($data);exit;
        //数据转换
        $allData=array();
        $allData['rows'] = $data;
        $allData['total'] = $total;
        return json_encode($allData);
    }

    //取配件信息
    public function actionPartInfo()
    {
        $part_no=yii::$app->request->post('part_no');
        $car_id=yii::$app->request->post('car_no');
        //$i=yii::$app->request->post('i');
        if(!$part_no or !$car_id){
            $data=array('error'=>1,'msg'=>'缺少参数');
            return json_encode($data);
        }
        $repair_ids=(new \yii\db\Query())
                ->select('repair_id')
                ->from('cs_repair_part')
                ->where("part_no=$part_no")
                ->all();
                if(!$repair_ids){
                    $data=array('into_time'=>'','into_mile'=>'');
                    return json_encode($data);
                }
                $repair_id='';
                foreach($repair_ids as $key=>$value){
                    foreach($value as $ke=>$val){
                        
                        $repair_id=$repair_id.$val.',';
                    }
                }
                $repair_id=rtrim($repair_id, ',');
        $data=Yii::$app->db->createCommand("select into_mile,into_time from cs_repair where id in (".$repair_id.") and car_id='".$car_id." ' and create_time< '".date('Y-m-d H:i:s',time())."' order by into_time desc ")->queryOne();
        if(!$data){
             $data=array('into_time'=>'','into_mile'=>'');
        }

        return json_encode($data);




    }

       /**
     * 单照片上传窗口
     */
    public function actionUploadWindow()
    {
        $columnName = yii::$app->request->get('columnName'); //判断上传哪种图片
        $isEdit = intval(yii::$app->request->get('isEdit')); //判断是否为修改图片上传
        $view = $isEdit > 0 ? 'upload-window-edit' : 'upload-window';
        return $this->render($view,[
                'columnName'=>$columnName
                ]);
    }

    /**
     * 上传验车单缩略图
     */
    public function actionUploadThumb()
    {
        $columnName = yii::$app->request->post('columnName');
        $isEdit = intval(yii::$app->request->get('isEdit'));
        $upload = UploadedFile::getInstanceByName($columnName);
        $fileExt = $upload->getExtension();
        $allowExt = ['jpg','png','jpeg','gif'];
        $returnArr = [];
        $returnArr['status'] = false;
        $returnArr['info'] = '';
        $returnArr['columnName'] = $columnName;
        if(!in_array($fileExt,$allowExt)){
            $returnArr['info'] = '文件格式错误！';
            $oStr = $isEdit > 0 ? 'CountDriverEdit' : 'RepairAddUpload';
            return '<script>window.parent.'.$oStr.'.uploadComplete('.json_encode($returnArr).');</script>';
        }
        $fileName = uniqid().'.'.$fileExt;
        $storePath = 'uploads/image/repair/';
        if(!is_dir($storePath)){
            mkdir($storePath, 0777, true);
        }
        $storePath .= $fileName;
        if($upload->saveAs($storePath)){
            $returnArr['status'] = true;
            $returnArr['info'] = $fileName;
            $returnArr['storePath'] = $storePath;
        }else{
            $returnArr['info'] = $upload->error;
        }
        $oStr = $isEdit > 0 ? 'CountDriverEdit' : 'RepairAddUpload';
        return '<script>window.parent.'.$oStr.'.uploadComplete('.json_encode($returnArr).');</script>';
    }

    /**
     * 单照片上传窗口_feng
     */
    public function actionUploadWindowFeng()
    {
        $columnName = yii::$app->request->get('columnName'); //判断上传哪种图片
        $isEdit = intval(yii::$app->request->get('isEdit')); //判断是否为修改图片上传
        $view = $isEdit > 0 ? 'upload-window-edit' : 'upload-window-feng';
        return $this->render($view,[
            'columnName'=>$columnName
        ]);
    }

    /**
     * 上传验车单缩略图_feng
     */
    public function actionUploadThumbFeng()
    {
        $columnName = yii::$app->request->post('columnName');
        $isEdit = intval(yii::$app->request->get('isEdit'));
        $upload = UploadedFile::getInstanceByName($columnName);
        $fileExt = $upload->getExtension();
        $allowExt = ['jpg','png','jpeg','gif'];
        $returnArr = [];
        $returnArr['status'] = false;
        $returnArr['info'] = '';
        $returnArr['columnName'] = $columnName;
        if(!in_array($fileExt,$allowExt)){
            $returnArr['info'] = '文件格式错误！';
            $oStr = $isEdit > 0 ? 'CountDriverEdit' : 'PayMoney';
            return '<script>window.parent.'.$oStr.'.uploadComplete('.json_encode($returnArr).');</script>';
        }
        $fileName = uniqid().'.'.$fileExt;
        $storePath = 'uploads/image/repair/';
        if(!is_dir($storePath)){
            mkdir($storePath, 0777, true);
        }
        $storePath .= $fileName;
        if($upload->saveAs($storePath)){
            $returnArr['status'] = true;
            $returnArr['info'] = $fileName;
            $returnArr['storePath'] = $storePath;
        }else{
            $returnArr['info'] = $upload->error;
        }
        $oStr = $isEdit > 0 ? 'CountDriverEdit' : 'PayMoney';
        return '<script>window.parent.'.$oStr.'.uploadComplete('.json_encode($returnArr).');</script>';
    }
}
