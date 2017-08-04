<?php
namespace backend\modules\repair\controllers;
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

    public function actionAdd()
    {
        date_default_timezone_set('PRC');
        $connection = yii::$app->db;
        $add_time = strtotime(date('Y-m-d H:i:s'));
        //var_dump($add_time);exit;
        $add_aid  = $_SESSION['backend']['adminInfo']['id'];


        if(yii::$app->request->isPost){
            echo "<pre>";
            var_dump($_POST);
            die;
            $brand_id = yii::$app->request->post('brand_id');//车辆品牌
            $car_type = yii::$app->request->post('car_type');//车辆类型
            $car_model = yii::$app->request->post('car_model');//车辆型号
            //车辆名称
            $manufacturer_name = yii::$app->request->post('manufacturer_name');//车辆制造厂
            //$use_nature = yii::$app->request->post('use_nature');//车辆使用性质
            $outside_long = yii::$app->request->post('outside_long');//长度
            $outside_width = yii::$app->request->post('outside_width');//宽度
            $outside_height = yii::$app->request->post('outside_height');//高度
            //判断长宽高是否填写
            if($outside_long == '') {
                $outside_long = 0;
            }
            if($outside_width == '') {
                $outside_width = 0;
            }
            if($outside_height == '') {
                $outside_height = 0;
            }
            //判断轴距是否录入
            $shaft_distance = yii::$app->request->post('shaft_distance');//轴距
            if($shaft_distance == '') {
                $shaft_distance = 0;
            }
            $wheel_distance_f = yii::$app->request->post('wheel_distance_f');//前轮距
            $wheel_distance_b = yii::$app->request->post('wheel_distance_b');//后轮距
            if($wheel_distance_f == '') {
                $wheel_distance_f = 0;
            }
            if($wheel_distance_b == '') {
                $wheel_distance_b = 0;
            }
            $cubage = yii::$app->request->post('cubage');//容积
            if($cubage == '') {
                $cubage = 0;
            }
            $approach_angle = yii::$app->request->post('approach_angle');//接近角
            if($approach_angle == '') {
                $approach_angle = 0;
            }
            $departure_angle = yii::$app->request->post('departure_angle');//离去角
            if($departure_angle == '') {
                $departure_angle = 0;
            }
            $total_mass = yii::$app->request->post('total_mass');//总质量
            if($total_mass == '') {
                $total_mass = 0;
            }
            $empty_mass = yii::$app->request->post('empty_mass');//整备质量
            if($empty_mass == '') {
                $empty_mass = 0;
            }
            $check_mass = yii::$app->request->post('check_mass');//额定载重质量
            if($check_mass == '') {
                $check_mass = 0;
            }
            $cab_passenger = yii::$app->request->post('cab_passenger');//驾驶室乘客数量
            $wheel_specifications = yii::$app->request->post('wheel_specifications');//轮胎型号
            /*$wheel_amount = yii::$app->request->post('wheel_amount');//轮胎数量
            if($wheel_amount == '') {
                $wheel_amount = 0;
            }*/
            $engine_model = yii::$app->request->post('engine_model');//发动机型号
            $fuel_type = yii::$app->request->post('fuel_type');//燃料形式
            $displacement = yii::$app->request->post('displacement');//排量
            if($displacement == '') {
                $displacement = 0;
            }
            $endurance_mileage = yii::$app->request->post('endurance_mileage');//工部续航里程
            if($endurance_mileage == '') {
                $endurance_mileage = 0;
            }
            $rated_power = yii::$app->request->post('rated_power');//驱动电机额定功率
            if($rated_power == '') {
                $rated_power = 0;
            }
            $peak_power = yii::$app->request->post('peak_power');//驱动电机峰值功率
            if($peak_power == '') {
                $peak_power = 0;
            }
            $power_battery_capacity = yii::$app->request->post('power_battery_capacity');//动力电池容量kW
            if($power_battery_capacity == '') {
                $power_battery_capacity = 0;
            }
            $power_battery_manufacturer = yii::$app->request->post('power_battery_manufacturer');//动力电池生产厂家
            $drive_motor_manufacturer = yii::$app->request->post('drive_motor_manufacturer');//驱动电机生产厂家
            $max_speed = yii::$app->request->post('max_speed');//最高车速
            if($max_speed == '') {
                $max_speed = 0;
            }
            $fast_charging_time = yii::$app->request->post('fast_charging_time');//充电时间 快
            if($fast_charging_time == '') {
                $fast_charging_time = 0.0;
            }
            $slow_charging_time = yii::$app->request->post('slow_charging_time');//充电时间 慢
            if($slow_charging_time == '') {
                $slow_charging_time = 0.0;
            }
            $charging_type = yii::$app->request->post('charging_type');//充电方式
            //图片
            $car_front_img = yii::$app->request->post('car_front_img');//车头图片
            $car_left_img = yii::$app->request->post('car_left_img');//左侧车身图片
            $car_right_img = yii::$app->request->post('car_right_img');//右侧车身图片
            $car_tail_img = yii::$app->request->post('car_tail_img');//车尾图片
            $car_control_img = yii::$app->request->post('car_control_img');//中控图片
            $car_full_img = yii::$app->request->post('car_full_img');//全车图片

            $inside_long = yii::$app->request->post('inside_long');
            if($inside_long == '') {
                $inside_long = 0;
            }
            $inside_width = yii::$app->request->post('inside_width');
            if($inside_width == '') {
                $inside_width = 0;
            }
            $inside_height = yii::$app->request->post('inside_height');
            if($inside_height == '') {
                $inside_height = 0;
            }
            $car_model_name_ = yii::$app->request->post('car_model_name_');//车型名称
            //var_dump($car_model_name_);exit;
            $reg_record = $connection->createCommand()->insert('cs_car_type', [
                        'brand_id' => $brand_id,
                        'car_type' => $car_type,
                        'car_model' => $car_model,
                        'manufacturer_name' => $manufacturer_name,
                        //'use_nature' => $use_nature,
                        'outside_long' => $outside_long,
                        'outside_width' => $outside_width,
                        'outside_height' => $outside_height,
                        'shaft_distance' => $shaft_distance,
                        'wheel_distance_f' => $wheel_distance_f,
                        'wheel_distance_b' => $wheel_distance_b,
                        'cubage' => $cubage,

                        'approach_angle' => $approach_angle,
                        'departure_angle' => $departure_angle,
                        'total_mass' => $total_mass,
                        'empty_mass' => $empty_mass,
                        'check_mass' => $check_mass,
                        'cab_passenger' => $cab_passenger,
                        'wheel_specifications' => $wheel_specifications,
                        //'wheel_amount' => $wheel_amount,
                        'engine_model' => $engine_model,
                        'fuel_type' => $fuel_type,

                        'displacement' => $displacement,
                        'endurance_mileage' => $endurance_mileage,
                        'rated_power' => $rated_power,
                        'peak_power' => $peak_power,
                        'power_battery_capacity' => $power_battery_capacity,
                        'power_battery_manufacturer' => $power_battery_manufacturer,
                        'drive_motor_manufacturer' => $drive_motor_manufacturer,
                        'max_speed' => $max_speed,
                        'fast_charging_time' => $fast_charging_time,
                        'slow_charging_time' => $slow_charging_time,
                        'charging_type' => $charging_type,
                        'add_time'=>$add_time,
                        'add_aid'=>$add_aid,

                        'car_front_img'=>$car_front_img,
                        'car_left_img'=>$car_left_img,
                        'car_right_img'=>$car_right_img,
                        'car_tail_img'=>$car_tail_img,
                        'car_control_img'=>$car_control_img,
                        'car_full_img'=>$car_full_img,

                        'inside_long'=>$inside_long,
                        'inside_width'=>$inside_width,
                        'inside_height'=>$inside_height,
                        'car_model_name'=>$car_model_name_

                        ])->execute();
            if($reg_record){
                $reg_record_id = Yii::$app->db->getLastInsertID();
                $this->synData($reg_record_id);//同步新增数据到菜鸟
                $returnArr['status'] = true;
                $returnArr['info'] = '添加成功!';
            } else {
                $returnArr['status'] = false;
                $returnArr['info'] = '添加失败!';   
            }
            return json_encode($returnArr);
        }
        
        //获取配置数据
        $configItems = [
            'car_type','use_nature','car_color','battery_type','gain_year',
            'car_model_name',
            'import_domestic','fuel_type','turn_type','gain_way','modified_car_type'
        ];
        $configCategoryModel = new ConfigCategory();
        $config = $configCategoryModel->getCategoryConfig($configItems,'value');
        //echo '<pre>';
        //var_dump($config['fuel_type']);
        //var_dump($config['use_nature']);
       // exit;
        $cars = (new \yii\db\Query())->select('plate_number')->from('cs_car')->where('is_del = 0 ')->all();
        return $this->render('add',['config'=>$config,'cars'=>$cars]);
    }
    //获取工单号
    public function actionGetOrder()
    {
        //维修厂类型0内部1外部
        $repair_company=0;
        $car_no = isset($_REQUEST['car_no']) ? trim($_REQUEST['car_no']) : '';
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
        /*echo "<pre>";
        var_dump($data);
        die;*/
        return json_encode($data);
    }

    public function actionEdit()
    {
        return $this->render('edit');
    }

    public function actionGetList()
    {
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $query = (new \yii\db\Query())
            ->select('a.*,b.plate_number,c.site_name,d.order_no')
            ->from('cs_repair as a')
            ->leftjoin('cs_car as b','a.car_id = b.id')
            ->leftJoin('oa_service_site as c','a.sale_factory = c.id')
            ->leftJoin('oa_car_maintain as d','a.order_number = d.id')
            ->where(['a.is_del'=>'0','b.is_del'=>'0']);
        if (yii::$app->request->isGet){
            $dat = $_GET;
            if($dat['car_id'] != ''){
                $query->andFilterWhere(['=','car_id',trim($dat['car_id'])]);
            }
            if($dat['order_number'] != ''){
                $query->andFilterWhere(['like','order_number',trim($dat['order_number'])]);
            }
            if($dat['order_type'] != ''){
                $query->andFilterWhere(['=','order_type',trim($dat['order_type'])]);
            }
            if($dat['sale_factory'] != ''){
                $query->andFilterWhere(['=','a.sale_factory',trim($dat['sale_factory'])]);
            }
            if($dat['check_status'] != ''){
                $query->andFilterWhere(['=','a.check_status',trim($dat['check_status'])]);
            }
            if($dat['bill_status'] != ''){
                $query->andFilterWhere(['=','bill_status',trim($dat['bill_status'])]);
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
        $repair_ids=(new \yii\db\Query())
                ->select('repair_id')
                ->from('cs_repair_part')
                ->where("part_no=$part_no")
                ->all();
                $repair_id='';
                foreach($repair_ids as $key=>$value){
                    foreach($value as $ke=>$val){
                        
                        $repair_id=$repair_id.$val.',';
                    }
                }
                $repair_id=rtrim($repair_id, ',');
            
        $data=Yii::$app->db->createCommand("select into_mile,into_time from cs_repair where id in (".$repair_id.") and car_id='".$car_id." ' order by into_time desc ")->queryOne();

        return json_encode($data);




    }
}
