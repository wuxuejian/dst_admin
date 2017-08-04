<?php
/**
 * 会员收藏(电桩)控制器
 */
namespace backend\modules\interfaces\controllers;
use yii;
use backend\models\ConfigCategory;
use backend\models\Vip;
use backend\models\VipFavorite;
use backend\models\ChargeSpots;
use backend\models\ChargeStation;

class FavoriteController extends BaseController{
    
	/**
	 *	新增收藏记录
	 */
	public function actionAddFavorite(){
		$datas = [];
		$_mobile    = isset($_REQUEST['mobile']) ? trim($_REQUEST['mobile']) : '';		 	// 手机号
		$_chargerid = isset($_REQUEST['chargerid']) ? intval($_REQUEST['chargerid']) : 0;   // 收藏的电站ID

        $vip_id = Vip::getVipIdByPhoneNumber($_mobile);

		if(!$_chargerid){
			$datas['error'] = 1;
			$datas['msg'] = '未选择要收藏的充电站！';
			$datas['errline'] = __LINE__;
            return json_encode($datas);
		}

		$favorite = VipFavorite::find()->where(['vip_id'=>$vip_id,'chargerid'=>$_chargerid,'is_del'=>0])->asArray()->one();
		if($favorite){
			$datas['error'] = 1;
			$datas['msg'] = '您已收藏该充电站，无法重复收藏！';
			$datas['errline'] = __LINE__;
            return json_encode($datas);
		}
        $chargeStation = ChargeStation::find()
            ->select('cs_id')->where(['cs_id'=>$_chargerid])->one();
        if(!$chargeStation){
            $datas['error'] = 1;
            $datas['msg'] = '您要收藏的充电站不存在！';
            return json_encode($datas);
        }
		$model = new VipFavorite();
		$model->vip_id = $vip_id;
		$model->chargerid = $_chargerid;
		$model->systime = time();
		if($model->save(false)){
			$datas['error'] = 0;
			$datas['msg'] = '新增收藏记录成功！';
			$datas['data'] = $model->getAttributes();
		}else{
			$datas['error'] = 1;
			$datas['msg'] = '新增收藏记录保存时出错！';
			$datas['errline'] = __LINE__;
		}
        return json_encode($datas);
	}

	/**
	 *	获取收藏记录
	 */
	public function actionGetFavorite(){
		$datas = [];
		$_mobile = isset($_REQUEST['mobile']) ? trim($_REQUEST['mobile']) : '';		// 手机号

        $vip_id = Vip::getVipIdByPhoneNumber($_mobile);

        $query = VipFavorite::find()
            ->select([
                '{{%vip_favorite}}.`id`',
                '{{%vip_favorite}}.`systime`',
                '{{%charge_station}}.`cs_id`',
                '{{%charge_station}}.`cs_code`',
                '{{%charge_station}}.`cs_name`',
                '{{%charge_station}}.`cs_lng`',
                '{{%charge_station}}.`cs_lat`',
                '{{%charge_station}}.`cs_address`',
            	'{{%charge_station}}.`cs_type`'
            ])->joinWith('chargeStation',false)
            ->where([
                '{{%vip_favorite}}.`vip_id`'=>$vip_id,
                '{{%vip_favorite}}.`is_del`'=>0
            ]);
        $total = $query->count();
        //分页
        $size = isset($_REQUEST['rows']) && $_REQUEST['rows'] <= 50 ? intval($_REQUEST['rows']) : 10;
        $page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
        $favorites = $query->offset(($page-1)*$size)->limit($size)->asArray()->all();
        if($favorites){
            $datas['error'] = 0;
            $datas['msg'] = "获取收藏记录成功！";
            $datas['data'] = $favorites;
            $datas['total'] = $total;
        }else{
            $datas['error'] = 1;
            if($page == 1){
                $datas['msg'] = '未找到任何收藏记录！';
            }else{
                $datas['msg'] = '没有更多收藏了！';
            }
            $datas['errline'] = __LINE__;
        }
		return json_encode($datas);
	}

	/**
	 *	删除收藏记录
	 */
	public function actionRemoveFavorite(){
		$datas = [];
		$_mobile = isset($_REQUEST['mobile']) ? trim($_REQUEST['mobile']) : '';	    // 手机号
        $_fid 	 = isset($_REQUEST['fid']) ? intval($_REQUEST['fid']) : 0;		    // 收藏记录ID

        $vip_id = Vip::getVipIdByPhoneNumber($_mobile);

        $count = VipFavorite::updateAll(['is_del'=>1],['vip_id'=>$vip_id,'id'=>$_fid]);
        if($count){
            $datas['error'] = 0;
            $datas['msg'] = "删除收藏记录成功！";
        }else{
            $datas['error'] = 1;
            $datas['msg'] = '删除收藏记录时出错！';
            $datas['errline'] = __LINE__;
        }
		return json_encode($datas);
	}
	
	
	
}