<?php
/**
 * @Desc: 微信推广活动->微信公众号->申请提现 控制器
 * @date:	2016-03-15
 */
namespace backend\modules\promotion\controllers;
use yii;
use yii\data\Pagination;
use backend\models\VipPromotionSign;
use backend\models\VipPromotionLet;
use backend\models\VipPromotionApplyCash;
use backend\models\VipPromotionSettle;


class ApplyCashController extends BaseController{

    /*
     * 访问“申请提现”视图
     */
	public function actionIndex(){
		$appid = VipPromotionSign::$_appid;
		$redirect_uri = urlencode ( 'http://yqzc.dstzc.com/index.php?r=promotion/apply-cash/apply');
		//静默
		$url ="https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_base&state=1#wechat_redirect";
		header("Location:".$url);
	}
	
    public function actionApply(){
        $data = [];
        $appid = VipPromotionSign::$_appid;
        $secret = VipPromotionSign::$_secret;
        $code = Yii::$app->request->get('code');
        if($code){
            $userInfo = VipPromotionSign::getWxUserInfo($appid, $secret, $code);
            $data['open_id'] = $userInfo['openid'];
            //===统计朋友租车情况、计算奖金=========================
            $totalAmount = 0;
            $total_reward =0.00; $settled_reward = 0.00; $unsettled_reward = 0.00;
            $res = VipPromotionLet::getFriendsLetInfo($data['open_id']);
            if($res && !empty($res)){
                //租车总数
                $totalAmount = array_sum(array_column($res,'amount'));
                //计算奖金。（千万注意：奖金要求一月一计算，所以这里必须将租车数量按月进行分组统计）
                $groupedRentNum = [];
                $noSettle_letIds = [];
                foreach($res as $row){
                    $yearMonth = substr($row['create_time'],0,7);
                    if(isset($groupedRentNum[$yearMonth])){
                        $groupedRentNum[$yearMonth] += $row['amount'];
                    }else{
                        $groupedRentNum[$yearMonth] = $row['amount'];
                    }
                    //取出未结算的租车记录
                    if($row['is_settle'] == 'NO'){
                        $noSettle_letIds[] = $row['id'];
                    }
                }
                $rewardData = VipPromotionLet::getReward($groupedRentNum);
                $total_reward = number_format($rewardData['reward'],2,'.','');
                //===根据结算记录计算已提现金额==================
                $signInfo = VipPromotionSign::find()->select(['id'])->where(['open_id'=>$data['open_id']])->asArray()->one();
                $rows = VipPromotionSettle::find()
                    ->select(['settled_money','settled_letId'])
                    ->where(['inviter_id'=>$signInfo['id']])
                    ->asArray()->all();
                if($rows){
                    $totalSettled = array_sum(array_column($rows,'settled_money'));
                    $settled_reward = number_format($totalSettled,2,'.','');
                }else{
                    $settled_reward = number_format(0.00,2,'.','');
                }
                //===计算未提现金额=============================
                $diff = $total_reward - $settled_reward;
                $unsettled_reward = number_format($diff,2,'.','');
            }
            $res[] = ['renter'=>'租车合计','amount'=>"{$totalAmount} 部",'create_time'=>''];
            $res[] = ['renter'=>'奖金合计','amount'=>"{$total_reward} 元",'create_time'=>''];
            $res[] = ['renter'=>'已提现','amount'=>"{$settled_reward} 元",'create_time'=>''];
            $res[] = ['renter'=>'未提现','amount'=>"{$unsettled_reward} 元",'create_time'=>''];
            $data['friendsLetInfo'] = $res;
            $data['rewardDetails'] = [
                'totalAmount'=>$totalAmount,
                'total_reward'=>$total_reward,
                'settled_reward'=>$settled_reward,
                'unsettled_reward'=>$unsettled_reward,
                'noSettle_letIds'=>isset($noSettle_letIds) ? implode(',',$noSettle_letIds) : '',
            ];
        }else{
            $data['open_id'] = '';
            $res = [];
            $res[] = ['renter'=>'租车合计','amount'=>"0.00 部",'create_time'=>''];
            $res[] = ['renter'=>'奖金合计','amount'=>"0.00 元",'create_time'=>''];
            $res[] = ['renter'=>'已提现','amount'=>"0.00 元",'create_time'=>''];
            $res[] = ['renter'=>'未提现','amount'=>"0.00 元",'create_time'=>''];
            $data['friendsLetInfo'] = $res;
            $data['rewardDetails'] = [
                'totalAmount'=>0,
                'total_reward'=>0.00,
                'settled_reward'=>0.00,
                'unsettled_reward'=>0.00,
                'noSettle_letIds'=>'',
            ];
        }
		//print_r($data);exit;
		return $this->render('index',$data);
	}

    /*
     * 提交保存提现申请
     */
    public function actionSubmit(){
        $formData = yii::$app->request->post();
        if(!$formData['open_id']){
            return json_encode(['status' => false, 'info' => '请使用微信客户端！']);
        }
        $model = new VipPromotionApplyCash();
        $model->load($formData,'');
        //根据open_id查对应用户注册id
        $signInfo = VipPromotionSign::find()
            ->select(['id'])
            ->where("code != '' AND invite_code_mine != '' AND is_del = 0")
            ->andWhere(['open_id'=>$formData['open_id']])
            ->asArray()->one();
        $model->apply_id = $signInfo['id'];
        $model->apply_date = date('Y-m-d');
        $model->create_time = date('Y-m-d H:i:s');
        $model->settle_status = 'UNSETTLED';
        if ($model->save(true)) {
            $data['status'] = true;
            $data['info'] = '提交申请成功！';
            return json_encode($data);
        } else {
            return json_encode(['status' => false, 'info' => '保存时出错！']);
        }
    }


}