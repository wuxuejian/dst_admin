<?php
/**
 * 会员建议控制器
 */
namespace backend\modules\interfaces\controllers;
use yii;
use backend\models\ConfigCategory;
use backend\models\Vip;
use backend\models\VipSuggestion;

class SuggestionController extends BaseController{
    
	/**
	 *	新增会员建议
	 */
	public function actionAddSuggestion(){
		$datas = [];
		$_mobile    = isset($_REQUEST['mobile']) ? trim($_REQUEST['mobile']) : '';		 	// 手机号
		$_title		= isset($_REQUEST['title']) ? trim($_REQUEST['title']) : '';   			// 建议标题
		$_content  	= isset($_REQUEST['content']) ? trim($_REQUEST['content']) : '';	  	// 建议内容

		$vip_id = Vip::getVipIdByPhoneNumber($_mobile);

		if(!$_content){
			$datas['error'] = 1;
			$datas['msg'] = '建议内容不能为空！';
			echo json_encode($datas); exit;
		}

		$model = new VipSuggestion();
		$model->vs_code = 'VS'.date('YmdHis').mt_rand(100,999);
		$model->vs_title = $_title;
		$model->vs_content = $_content;
		$model->vs_time = date('Y-m-d H:i:s');
		$model->vs_vip_id = $vip_id;
		$model->vs_systime = time();
		if(!$model->save()){
			$datas['error'] = 1;
			$datas['msg'] = '新增建议保存时出错！';
		}else{
			$datas['error'] = 0;
			$datas['msg'] = '提交成功，谢谢您的反馈！';
			$datas['data'] = $model->getAttributes();
		}
		echo json_encode($datas); exit;
	}
	
	
	/**
	 *	获取会员建议
	 */
	public function actionGetSuggestion(){
		$datas = [];
		$_mobile    = isset($_REQUEST['mobile']) ? trim($_REQUEST['mobile']) : '';		 	// 手机号

		$vip_id = Vip::getVipIdByPhoneNumber($_mobile);

        $query = VipSuggestion::find()
        	->select(['*'])
        	->where([
				'`vs_is_del`'=>0,
				'`vs_vip_id`'=>$vip_id,
			]);
        $total = $query->count();
        //分页
        $size = isset($_REQUEST['rows']) && $_REQUEST['rows'] <= 50 ? intval($_REQUEST['rows']) : 10;
        $page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
        $suggestions = $query
            ->orderBy('`vs_respond_time` desc,`vs_id` desc')
            ->offset(($page-1)*$size)
            ->limit($size)
            ->asArray()->all();
		if(empty($suggestions)){
			$datas['error'] = 1;
            if($page == 1){
                $datas['msg'] = '没有找到任何建议！';
            }else{
                $datas['msg'] = '没有更多的意见建议了！';
            }
		}else{
            foreach($suggestions as &$suggestionsItem){
                if($suggestionsItem['vs_responder_id']){
                    $suggestionsItem['vs_responder'] = '官方客服';
                }else{
                    $suggestionsItem['vs_responder'] = '';
                }
            }
			$datas['error'] = 0;
			$datas['msg'] = '获取建议成功！';
			$datas['data'] = $suggestions;
			$datas['total'] = $total;
		}
		echo json_encode($datas); exit;
	}
	
	
	/**
	 *	删除会员建议
	 */
	public function actionRemoveSuggestion(){
		$datas = [];
        $_mobile = isset($_REQUEST['mobile']) ? trim($_REQUEST['mobile']) : '';	    // 手机号
		$_sid    = isset($_REQUEST['sid']) ? intval($_REQUEST['sid']) : 0;			// 建议ID

		$vip_id = Vip::getVipIdByPhoneNumber($_mobile);
		
        $count = VipSuggestion::updateAll(['vs_is_del'=>1],['vs_id'=>$_sid,'vs_vip_id'=>$vip_id]);
        if($count){
            $datas['error'] = 0;
            $datas['msg'] = "删除建议成功！";
        }else{
            $datas['error'] = 1;
            $datas['msg'] = '删除建议时出错！';
        }
		echo json_encode($datas); exit;
	}
	
}