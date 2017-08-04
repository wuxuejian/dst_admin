<?php
/**
 * 会员分享(电桩)控制器
 */
namespace backend\modules\interfaces\controllers;
use yii;
use backend\models\ConfigCategory;
use backend\models\Vip;
use backend\models\VipShare;
use backend\models\ChargeSpots;

class ShareController extends BaseController{
    
	/**
	 *	新增/修改分享记录
     *  注意：此分享指的是第三方分享新电桩供地上铁审核再收录进系统以供用户搜索使用。
	 */
	public function actionAddEditShare(){
		$datas = [];
		$_mobile     = isset($_REQUEST['mobile']) ? trim($_REQUEST['mobile']) : '';         // 手机号
        $_chargerStr = isset($_REQUEST['charger']) ? trim($_REQUEST['charger']) : '';       // 被分享的电桩数据，json字符串
        $_sid        = isset($_REQUEST['sid']) ? intval($_REQUEST['sid']) : 0;		 	    // 分享记录ID
		// $_ver  	     = isset($_REQUEST['ver']) ? trim($_REQUEST['ver']) : '';		        // App版本号

        // 注意：上面接收到的电桩数据格式为：
        // $_chargerStr = '[{"code_from_factory":"123456","connection_type":"GB","model":"BYD_T5"}]';
        // 下面用json_decode()解码后是只包含了一个元素的数组，但该元素是个stdClass类型对象，只能以"->"形式访问单个属性值！！！
        $tmpArr = json_decode($_chargerStr);
        $_chargerObj = $tmpArr[0]; // 是stdClass类型对象

        // 检查某些必填项
        $requiredFields = ['code_from_factory','connection_type','model','charge_type','manufacturer'];
        foreach($requiredFields as $item){
           if(property_exists($_chargerObj,$item)){
               if($_chargerObj->$item == '') {
                   $datas['error'] = 1;
                   $datas['msg'] = '请将电桩资料填写完整！';
                   $datas['errline'] = __LINE__;
                   echo json_encode($datas); exit;
               }
           }
        }

		$vip_id = Vip::getVipIdByPhoneNumber($_mobile);

		if(!$_sid){ // 新增
		    //先以电桩厂家编号来查重复
            $codeFromFactory = $_chargerObj->code_from_factory;
			$row = VipShare::find()->where(['code_from_factory'=>$codeFromFactory,'is_del'=>0])->asArray()->one();
			if($row){
				$datas['error'] = 1;
				$datas['msg'] = '该电桩已存在于分享库中，不能重复分享！';
				$datas['errline'] = __LINE__;
				echo json_encode($datas); exit;
			}
			$model = new VipShare();
			$model->vip_id = $vip_id;
			foreach($_chargerObj as $key=>$val){
				$model->$key = $val;
			}
			$model->share_time = date('Y-m-d H:i:s');
			$model->systime = time();
			if(!$model->save(false)){
				$datas['error'] = 1;
				$datas['msg'] = '保存新分享记录时出错！';
				$datas['errline'] = __LINE__;
			}else{
				$datas['error'] = 0;
				$datas['msg'] = '新增分享成功！';
				$datas['data'] = $model->getAttributes();
			}
		}else{ // 修改
			$model = VipShare::findOne($_sid);
            if($model->approve_status == 2){ // 0未审核，1审核未通过，2审核已通过
                $datas['error'] = 1;
                $datas['msg'] = '该分享已被审核通过，不能再修改！';
                $datas['errline'] = __LINE__;
                echo json_encode($datas); exit;
            }
			foreach($_chargerObj as $key=>$val){
				$model->$key = $val;
			}
            $model->share_time = date('Y-m-d H:i:s'); // 更新分享时间
            $model->approve_status = 1; // 更新审核状态为"未审核"，但上次审核不通过的原因不要清空！
			if($model->save(false)){
				$datas['error'] = 1;
				$datas['msg'] = '修改失败：保存分享记录时出错！';
				$datas['errline'] = __LINE__;
			}else{
				$datas['error'] = 0;
				$datas['msg'] = '修改成功！';
				$datas['data'] = $model->getAttributes();
			}
		}
		echo json_encode($datas); exit;
	}

	/**
	 *	获取分享记录
	 */
	public function actionGetShare(){
		$datas = [];
		$_mobile = isset($_REQUEST['mobile']) ? trim($_REQUEST['mobile']) : '';		// 手机号
		// $_ver  	 = isset($_REQUEST['ver']) ? trim($_REQUEST['ver']) : '';		    // App版本号

		$vip_id = Vip::getVipIdByPhoneNumber($_mobile);

        $query = VipShare::find()
			->where(['is_del'=>0,'vip_id'=>$vip_id])
			->orderBy('approve_status ASC,share_time DESC');
        $total = $query->count();
        //分页
        $size = isset($_REQUEST['rows']) && $_REQUEST['rows'] <= 50 ? intval($_REQUEST['rows']) : 10;
        $page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
        $results = $query->offset(($page-1)*$size)->limit($size)->asArray()->all();
		if(empty($results)){
			$datas['error'] = 1;
			$datas['msg'] = '未找到任何分享记录！';
			$datas['errline'] = __LINE__;
		}else{
			// 将各combox配置项一并取得对应的文本描述
			$configItems = ['connection_type','charge_type','model','manufacturer','install_type'];
			$configs = (new ConfigCategory())->getCategoryConfig($configItems,'value');
			foreach($results as $k=>$row) {
				foreach($configItems as $conf) {
					if(isset($row[$conf])) {
						$results[$k][$conf.'_txt'] = '';
						if($row[$conf]) {
							$_val = $row[$conf];
							$_txt = $configs[$conf][$_val]['text'];
							$results[$k][$conf.'_txt'] = $_txt;
						}
					}
				}
			}
			$datas['error'] = 0;
			$datas['msg'] = "获取我的分享记录成功！";
			$datas['data'] = $results;
			$datas['total'] = $total;
		}
		echo json_encode($datas); exit;
	}

	/**
	 *	删除分享记录
     *  share_remove-share
	 */
	public function actionRemoveShare(){
		$datas = [];
		$_sid 	 = isset($_REQUEST['sid']) ? intval($_REQUEST['sid']) : 0;		// 分享记录ID
		$_mobile = isset($_REQUEST['mobile']) ? trim($_REQUEST['mobile']) : '';	// 手机号
		// $_ver  	 = isset($_REQUEST['ver']) ? trim($_REQUEST['ver']) : '';		// App版本号

        $vip_id = Vip::getVipIdByPhoneNumber($_mobile);

		$model = VipShare::findOne($_sid);
		if(!$model){
			$datas['error'] = 1;
			$datas['msg'] = '找不到指定的记录！';
			$datas['errline'] = __LINE__;
			echo json_encode($datas); exit;
		}
		if($model->approve_status == 2){
			$datas['error'] = 1;
			$datas['msg'] = '该电桩已被系统审核收录，不能删除！';
			$datas['errline'] = __LINE__;
			echo json_encode($datas); exit;
		}

		$count = VipShare::updateAll(['is_del'=>1],['id'=>$_sid]);
		if($count){
			$datas['error'] = 0;
			$datas['msg'] = "删除成功！";
		}else{
			$datas['error'] = 1;
			$datas['msg'] = '删除分享记录时出错！';
			$datas['errline'] = __LINE__;
		}
		echo json_encode($datas); exit;
	}

	
	
}