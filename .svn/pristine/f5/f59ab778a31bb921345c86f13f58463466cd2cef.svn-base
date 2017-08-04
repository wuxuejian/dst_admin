<?php
/**
 * 会员车辆管理控制器
 */
namespace backend\modules\interfaces\controllers;
use yii;
use backend\models\ConfigCategory;
use backend\models\Vip;
use backend\models\Vehicle;

class VehicleController extends BaseController{
    
	/**
	 *	新增/修改会员车辆
	 */
	public function actionAddEditVehicle(){
		$datas = [];
		$_mobile = isset($_REQUEST['mobile']) ? trim($_REQUEST['mobile']) : '';		    // 手机号
		$_vehicle = isset($_REQUEST['vehicle']) ? trim($_REQUEST['vehicle']) : '';	    // 车牌号
		$_model = isset($_REQUEST['model']) ? trim($_REQUEST['model']) : '';			// 车型
		$_vhccontype = isset($_REQUEST['contype']) ? trim($_REQUEST['contype']) : '';	// 充电连接方式
		$_vhcid = isset($_REQUEST['vhcid']) ? intval($_REQUEST['vhcid']) : '';			// 车辆ID（仅修改时使用）

        $vip_id = Vip::getVipIdByPhoneNumber($_mobile);

		if(!$_vehicle || !$_vhccontype){
			$datas['error'] = 1;
			$datas['msg'] = '车辆信息填写不完整！';
			$datas['errline'] = __LINE__;
            return json_encode($datas);
		}

		if(!$_vhcid ){ // 新增车辆
			$model = new Vehicle();
			$model->vip_id = $vip_id;
			$model->vehicle = $_vehicle;
			$model->vhc_model = $_model;
			$model->vhc_con_type = $_vhccontype;
			if($model->save(true)){
				$datas['error'] = 0;
				$datas['msg'] = '新增车辆成功！';
				$datas['data'] = $model->getAttributes();
			}else{
				$datas['error'] = 1;
                $error = $model->getErrors();
                if($error){
                    $datas['msg'] = join('',array_column($error,0));
                }else{
                    $datas['msg'] = '新增车辆保存时出错！';
                }
				$datas['errline'] = __LINE__;
			}
		}else{ // 修改车辆
			$model = Vehicle::findOne($_vhcid);
			$model->vip_id = $vip_id;
			$model->vehicle = $_vehicle;
			$model->vhc_model = $_model;
			$model->vhc_con_type = $_vhccontype;
			if($model->save(true)){
				$datas['error'] = 0;
				$datas['msg'] = '修改车辆成功！';
				$datas['data'] = $model->getAttributes();
			}else{
				$datas['error'] = 1;
                $error = $model->getErrors();
                if($error){
                    $datas['msg'] = join('',array_column($error,0));
                }else{
                    $datas['msg'] = '修改车辆保存时出错！';
                }
				$datas['errline'] = __LINE__;
			}
		}
        return json_encode($datas);
	}

	/**
	 *	获取会员车辆
	 */
	public function actionGetVehicle(){
		$datas = [];
		$_mobile = isset($_REQUEST['mobile']) ? trim($_REQUEST['mobile']) : '';	// 手机号

        $vip_id = Vip::getVipIdByPhoneNumber($_mobile);
		$vehiclesAR = Vehicle::find()->where(['vip_id'=>$vip_id,'is_del'=>0]);
		$total = $vehiclesAR->count();
		$vehicles = $vehiclesAR->asArray()->all();
		if (empty($vehicles)){
			$datas['error'] = 1;
			$datas['msg'] = '未找到任何车辆！';
			$datas['errline'] = __LINE__;
		}else{
			// 将各combox配置项一并取得对应的文本描述
			$configItems = ['connection_type'];
			$configs = (new ConfigCategory())->getCategoryConfig($configItems,'value');
			foreach ($vehicles as $k=>$row) {
				foreach ($configItems as $conf) {
					if ($conf == 'connection_type'){
						if(isset($row['vhc_con_type'])) {
							$vehicles[$k]['vhc_con_type_txt'] = '';
							if($row['vhc_con_type']) {
								$_val = $row['vhc_con_type'];
								$_txt = $configs[$conf][$_val]['text'];
								$vehicles[$k]['vhc_con_type_txt'] = $_txt;
							}
						}
					} else {
						if (isset($row[$conf])) {
							$vehicles[$k][$conf.'_txt'] = '';
							if ($row[$conf]) {
								$_val = $row[$conf];
								$_txt = $configs[$conf][$_val]['text'];
								$vehicles[$k][$conf.'_txt'] = $_txt;
							}
						}
					}
				}
			}
			$datas['error'] = 0;
			$datas['msg'] = '获取车辆成功！';
			$datas['data'] = $vehicles;
			$datas['total'] = $total;
		}
        return json_encode($datas);
	}

	/**
	 *	删除会员车辆
	 */
	public function actionRemoveVehicle(){
		$datas = [];
		$_mobile = isset($_REQUEST['mobile']) ? trim($_REQUEST['mobile']) : '';		// 手机号
        $_vhcid = isset($_REQUEST['vhcid']) ? intval($_REQUEST['vhcid']) : 0;		// 车辆ID

        $vip_id = Vip::getVipIdByPhoneNumber($_mobile);
		if($_vhcid){
			$count = Vehicle::updateAll(['is_del'=>1],['id'=>$_vhcid,'vip_id'=>$vip_id]);
			if($count){
				$datas['error'] = 0;
				$datas['msg'] = "删除车辆成功！";
			}else{
				$datas['error'] = 1;
				$datas['msg'] = '删除车辆时出错！';
				$datas['errline'] = __LINE__;
			}
		}
		return json_encode($datas);
	}
	
	
}