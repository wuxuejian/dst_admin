<?php
/**
 * 会员充电预约(电桩)控制器
 */
namespace backend\modules\interfaces\controllers;
use backend\models\ConfigCategory;
use backend\models\Vip;
use backend\models\ChargeAppointment;
use backend\models\ChargeSpots;

use yii;

class AppointmentController extends BaseController{
    

	/**
	 *	新增/修改充电预约单
	 */
	public function actionAddEditAppointment(){
		$datas = [];
		$_mobile    = isset($_REQUEST['mobile']) ? trim($_REQUEST['mobile']) : '';		 	    // 预约手机号
		// $_contype  	= isset($_REQUEST['contype']) ? trim($_REQUEST['contype']) : '';	    // 充电连接方式
		$_chargerid = isset($_REQUEST['chargerid']) ? intval($_REQUEST['chargerid']) : 0;       // 预约的电桩ID
		$_date 		= isset($_REQUEST['date']) ? trim($_REQUEST['date']) : '';  				// 预约日期
		$_starttime = isset($_REQUEST['starttime']) ? trim($_REQUEST['starttime']) : '';        // 开始时间
		$_endtime  	= isset($_REQUEST['endtime']) ? trim($_REQUEST['endtime']) : '';	  	    // 截止时间
		$_mark  	= isset($_REQUEST['mark']) ? trim($_REQUEST['mark']) : '';	  	  		    // 备注
		$_caid		= isset($_REQUEST['caid']) ? intval($_REQUEST['caid']) : 0;				    // 预约单ID
		// $_ver  	    = isset($_REQUEST['ver']) ? trim($_REQUEST['ver']) : '';		 		    // App版本号

        $vip_id = Vip::getVipIdByPhoneNumber($_mobile);

		if(!$_chargerid){
			$datas['error'] = 1;
			$datas['msg'] = '请选择要预约的电桩！';
			$datas['errline'] = __LINE__;
			echo json_encode($datas); exit;
		}

		if(!$_date || !$_starttime || !$_endtime){
			$datas['error'] = 1;
			$datas['msg'] = '预约时间填写不完整！';
			$datas['errline'] = __LINE__;
			echo json_encode($datas); exit;
		}

		if($_starttime >= $_endtime){
			$datas['error'] = 1;
			$datas['msg'] = '结束时间要大于开始时间！';
			$datas['errline'] = __LINE__;
			echo json_encode($datas); exit;
		}

		if(!$_caid){ // 新增预约
			$model = new ChargeAppointment();
			$model->code = 'CA'.date('YmdHis').mt_rand(100,999);
			$model->vip_id = $vip_id;
			$model->chargerid = $_chargerid;
			$model->appointed_date = $_date;
			$model->time_start = $_starttime;
			$model->time_end = $_endtime;
			$model->systime = time();
			$model->mark = $_mark;
			if($model->save(false)){
				$datas['error'] = 0;
				$datas['msg'] = '新增预约单成功！';
				$datas['data'] = $model->getAttributes();
			}else{
				$datas['error'] = 1;
				$datas['msg'] = '新增预约单保存时出错！';
				$datas['errline'] = __LINE__;
			}
		}else{	// 修改预约
			$model = ChargeAppointment::findOne($_caid);
			$model->vip_id = $vip_id;
			$model->chargerid = $_chargerid;
			$model->appointed_date = $_date;
			$model->time_start = $_starttime;
			$model->time_end = $_endtime;
			$model->mark = $_mark;
			if($model->save(false)){
				$datas['error'] = 0;
				$datas['msg'] = '修改预约单成功！';
				$datas['data'] = $model->getAttributes();
			}else{
				$datas['error'] = 1;
				$datas['msg'] = '修改预约单保存时出错！';
				$datas['errline'] = __LINE__;
			}
		}
		echo json_encode($datas); exit;
	}

	/**
	 *	获取充电预约单
	 */
	public function actionGetAppointment(){
		$datas = [];
		$_mobile = isset($_REQUEST['mobile']) ? trim($_REQUEST['mobile']) : '';		// 手机号
		// $_ver  = isset($_REQUEST['ver']) ? trim($_REQUEST['ver']) : '';		// App版本号

        $vip_id = Vip::getVipIdByPhoneNumber($_mobile);

        $query = ChargeAppointment::find()
			->select([
                '{{%charge_appointment}}.*',
                '{{%vip}}.mobile','code_from_compony','connection_type','install_site'
            ])
			->joinWith('vip',false)
			->joinWith('charger',false)
			->where(['vip_id'=>$vip_id,'{{%charge_appointment}}.is_del'=>0]);
        $total = $query->count();
        //分页
        $size = isset($_REQUEST['rows']) && $_REQUEST['rows'] <= 50 ? intval($_REQUEST['rows']) : 10;
        $page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
        $appointments = $query->offset(($page-1)*$size)->limit($size)->asArray()->all();
        if(empty($appointments)){
			$datas['error'] = 1;
			$datas['msg'] = '未找到任何预约单！';
			$datas['errline'] = __LINE__;
			echo json_encode($datas); exit;
		}else{
			//将各combox配置项一并取得对应的文本描述
			$configItems = ['connection_type'];
			$configs = (new ConfigCategory())->getCategoryConfig($configItems,'value');
			foreach($appointments as $k=>$row) {
				foreach($configItems as $conf) {
					if(isset($row[$conf])) {
						$appointments[$k][$conf.'_txt'] = '';
						if($row[$conf]) {
							$_val = $row[$conf];
							$_txt = $configs[$conf][$_val]['text'];
							$appointments[$k][$conf.'_txt'] = $_txt;
						}
					}
				}
			}
			$datas['error'] = 0;
			$datas['msg'] = "获取预约单成功！";
			$datas['data'] = $appointments;
			$datas['total'] = $total;
		}
		echo json_encode($datas); exit;
	}

	/**
	 *	删除充电预约单
	 */
	public function actionRemoveAppointment(){
		$datas = [];
		$_caid 	 = isset($_REQUEST['caid']) ? intval($_REQUEST['caid']) : 0;			// 预约单ID
		$_mobile = isset($_REQUEST['mobile']) ? trim($_REQUEST['mobile']) : '';	        // 手机号
		// $_ver  	 = isset($_REQUEST['ver']) ? trim($_REQUEST['ver']) : '';		 		// App版本号

        $vip_id = Vip::getVipIdByPhoneNumber($_mobile);
		if($_caid){
			$count = ChargeAppointment::updateAll(['is_del'=>1],['id'=>$_caid]);
			if($count){
				$datas['error'] = 0;
				$datas['msg'] = "删除预约单成功！";
			}else{
				$datas['error'] = 1;
				$datas['msg'] = '删除预约单时出错！';
				$datas['errline'] = __LINE__;
			}
		}
		echo json_encode($datas); exit;
	}	
	
}