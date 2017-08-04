<?php
/**
 * 配置控制器
 */
namespace backend\modules\interfaces\controllers;
use yii;
//use yii\web\Controller;
use backend\models\CustomerCompany;
use backend\models\Vip;
use backend\models\CarLetRecord;
use backend\models\TcpCarRealtimeData;
use backend\models\ConfigCategory;
class CompanyCustomerController extends BaseController{
    public $hasRelation = false;
    public $customerId = 0;
    public function init(){
        parent::init();
        //检测该账号是否已经
        $mobile = $_REQUEST['mobile'];
        $vip_id = Vip::getVipIdByPhoneNumber($mobile);
        $customerInfo = CustomerCompany::find()
            ->select(['id'])
            ->where(['vip_id'=>$vip_id])
            ->asArray()->one();
        if(!$customerInfo){
            $datas = [];
            $datas['error'] = 1;
            $datas['msg'] = '无关联客户！';
            echo json_encode($datas);
            return false;
        }else{
            $this->hasRelation = true;
            $this->customerId = $customerInfo['id'];
        }
        return true;
    }
    /**
     * 获取关联客户信息
     */
    public function actionCustomerInfo(){
        if(!$this->hasRelation){
            return false;
        }
        $datas = [];
        $datas['error'] = 0;
        $datas['msg'] = '';
        $customerInfo = CustomerCompany::find()
            ->select(['id','number','company_name','company_addr','company_brief'])
            ->where(['id'=>$this->customerId])
            ->asArray()->one();
        $datas['data'] = $customerInfo;
        echo json_encode($datas);
    }
    /**
     * 获取关联客户当前出租的车辆
     */
    public function actionLetingCar(){
        if(!$this->hasRelation){
            return false;
        }
        $datas = [];
        $datas['error'] = 1;
        $datas['msg'] = '';
        $query = CarLetRecord::find()
            ->select([
                '{{%car_let_record}}.`id`',
                '{{%car_let_record}}.`month_rent`',
                '{{%car_let_record}}.`let_time`',
                '{{%car}}.`plate_number`',
                '{{%car}}.`vehicle_dentification_number`',
                '{{%car}}.`car_brand`',
            ])
            ->joinWith('car',false)
            ->where([
                '{{%car_let_record}}.`customer_id`'=>$this->customerId,
                '{{%car_let_record}}.`back_time`'=>0,
            ]);
        $total = $query->count();
        //分页
        $size = isset($_REQUEST['rows']) && $_REQUEST['rows'] <= 50 ? intval($_REQUEST['rows']) : 10;
        $page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
        $records = $query->offset(($page-1)*$size)->limit($size)->asArray()->all();
        if(empty($records)){
            $datas['msg'] = '未找到出租车辆！';
            echo json_encode($datas);
            return;
        }
        //获取车辆品牌配置
        $config = (new ConfigCategory)->getCategoryConfig(['car_brand'],'value');
        foreach($records as &$cCLItem){
            if(isset($config['car_brand'][$cCLItem['car_brand']])){
                $cCLItem['car_brand_text'] = $config['car_brand'][$cCLItem['car_brand']]['text'];
            }else{
                $cCLItem['car_brand_text'] = '';
            }
        }
        $datas['error'] = 0;
        $datas['msg'] = '';
        $datas['data'] = $records;
        $datas['total'] = $total;
        echo json_encode($datas);
    }

    /**
     * 获取当前车辆的实时简要信息
     */
    public function actionRealtimeDataBrief(){
        if(!$this->hasRelation){
            return false;
        }
        $datas = [];
        $datas['error'] = 1;
        $datas['msg'] = '';
        if(!isset($_REQUEST['vin']) || !$_REQUEST['vin']){
            $datas['msg'] = '缺少必要参数！';
            echo json_encode($datas);
            return;
        }
        $vin = $_REQUEST['vin'];
        $realtimeData = TcpCarRealtimeData::find()
            ->select([
                'data_source','collection_datetime','update_datetime',
                'total_driving_mileage','longitude_value','latitude_value',
                'speed','direction','battery_package_soc',
            ])
            ->where(['car_vin'=>$vin])->asArray()->one();
        if(!$realtimeData){
            $datas['msg'] = '无该车辆数据！';
            echo json_encode($datas);
            return;
        }
        $datas['error'] = 0;
        $datas['data'] = $realtimeData;
        echo json_encode($datas);
    }

    /**
     * 获取当前车辆的实时详细信息
     */
    public function actionRealtimeDataDetail(){
        if(!$this->hasRelation){
            return false;
        }
        $datas = [];
        $datas['error'] = 1;
        $datas['msg'] = '';
        if(!isset($_REQUEST['vin']) || !$_REQUEST['vin']){
            $datas['msg'] = '缺少必要参数！';
            echo json_encode($datas);
            return;
        }
        $vin = $_REQUEST['vin'];
        $realtimeData = TcpCarRealtimeData::find()
            ->select([
                'data_source','collection_datetime','update_datetime',
                'total_driving_mileage','longitude_value','latitude_value',
                'speed','direction','moter_controller_temperature',
                'moter_speed','moter_temperature','moter_voltage','moter_current',
                'moter_generatrix_current','car_current_status','battery_package_soc',
            ])
            ->where(['car_vin'=>$vin])->asArray()->one();
        if(!$realtimeData){
            $datas['msg'] = '无该车辆数据！';
            echo json_encode($datas);
            return;
        }
        switch ($realtimeData['car_current_status']) {
            case 0:
                $realtimeData['car_current_status'] = '停止';
                break;
            case 1:
                $realtimeData['car_current_status'] = '行驶';
                break;
            case 2:
                $realtimeData['car_current_status'] = '充电';
                break;
            default:
                $realtimeData['car_current_status'] = '未知';
                break;
        }
        $datas['error'] = 0;
        $datas['data'] = $realtimeData;
        echo json_encode($datas);
    }
}