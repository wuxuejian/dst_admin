<?php
/**
 * @Desc: 微信推广活动->微信公众号->查看排名 控制器
 * @date:	2016-03-05
 */
namespace backend\modules\promotion\controllers;
use yii;
use yii\data\Pagination;
use backend\models\VipPromotionSign;
use backend\models\VipPromotionLet;

class CheckRankController extends BaseController{

    /*
     * 查看排名
     */
	public function actionIndex(){
        $open_id = yii::$app->request->get('openid');
        //$open_id = 'ozfWUtz7yiBcxF06TsFAmBp3D29I'; //测试
        $questionStr = "\r\n\r\n如有疑问，请致电400-860-4558";
        if(!$open_id){
            return '对不起，没有识别到您的微信身份！请确认是使用微信客户端打开链接！'.$questionStr;
        }
        $curUser = VipPromotionSign::find()
            ->select(['invite_code_mine'])
            ->where("code != '' AND invite_code_mine != '' AND is_del = 0")
            ->andWhere(['open_id'=>$open_id])
            ->asArray()->one();
        if(!$curUser){
            return '对不起，没有找到您的信息，请确定是否已注册！'.$questionStr;
        }

        //===【1】查当前用户总共邀请多少位朋友成功注册及邀请排名==================================================
        $results = VipPromotionSign::find()
            ->select(['invite_code_used', 'usedNum' => 'COUNT(invite_code_used)'])
            ->where("code != '' AND invite_code_mine != '' AND invite_code_used != '' AND is_del = 0")
            ->groupBy('invite_code_used')
            ->orderBy('usedNum DESC')
            ->asArray()->all();
        //print_r($results);exit;
        $usedNumArr = array_unique(array_column($results, 'usedNum'));
        $usedNumArr = array_flip($usedNumArr);
        foreach ($results as $k => $row) {
            if (strtolower($row['invite_code_used']) == strtolower($curUser['invite_code_mine'])) {
                $inviteNum = $row['usedNum'];
                $inviteRankNo = $usedNumArr[$inviteNum] + 1;
                break;
            }
        }
        if(!isset($inviteNum)){
            return "您好！到目前为止，您还没有成功邀请到任何朋友参与活动！请继续加油哦！".$questionStr;
        }

        //===【2】查当前用户所邀请朋友的租车记录及奖金==============================================================
        $checkTotalRankStr = "\r\n\r\n"."<a href=\"".(VipPromotionSign::$_host)."/index.php?r=promotion/check-rank/reward-total-rank\">查看排名榜</a>";
        $data = VipPromotionLet::getFriendsLetInfo($open_id);
        if(!$data || empty($data)){
            $info = "您好！到目前为止，您总共成功邀请了 {$inviteNum} 位朋友参与活动，邀请排名是第 {$inviteRankNo} 名；但还没有人成功的从地上铁租车，所以您暂时还未获得奖金哦！请继续加油哦！";
            return $info.$questionStr.$checkTotalRankStr;
        }
        $renterNum = count(array_unique(array_column($data, 'renter_id')));  // 租赁总人数
        $rentNum = array_sum(array_column($data, 'amount'));                 // 租车总数量
        //计算奖金。（千万注意：奖金要求一月一计算，所以这里必须将租车数量按月进行分组统计）
        $groupedRentNum = [];
        foreach($data as $row){
            $yearMonth = substr($row['create_time'],0,7);
            if(isset($groupedRentNum[$yearMonth])){
                $groupedRentNum[$yearMonth] += $row['amount'];
            }else{
                $groupedRentNum[$yearMonth] = $row['amount'];
            }
        }
        $rewardData = VipPromotionLet::getReward($groupedRentNum);           // 计算奖金
        $reward = $rewardData['reward'];
        $calcMethod = $rewardData['calcMethod'];
        $rewardTotalRank = VipPromotionLet::getRewardTotalRank();            // 奖金排名
        $rewardArr = array_keys($rewardTotalRank);
        $rewardRank = array_search($reward,$rewardArr) + 1;
        $info = "您好！到目前为止，您总共成功邀请了 {$inviteNum} 位朋友参与活动，邀请排名是第 {$inviteRankNo} 名；其中 {$renterNum} 位朋友成功的从地上铁租了 {$rentNum} 台车，您总共获得的奖金是：{$reward} 元，奖金排名是第 {$rewardRank} 名！请继续加油哦！";
        //$calcMethodStr = "\r\n\r\n"."您的奖金计算方式：{$calcMethod}";
        return $info.$questionStr.$checkTotalRankStr;
	}


    /*
     * 访问“奖金总排名榜”视图
     */
    public function actionRewardTotalRank(){
        //总参与人数
        $totalPerson = VipPromotionSign::find()
            ->where("code != '' AND invite_code_mine != '' AND is_del = 0")
            ->count();
        //获取奖金前x名排名榜
        $topNum = 10;
        $data = VipPromotionLet::getRewardTotalRank(true,$topNum);
        //遍历处理下姓名和手机号
        foreach($data as &$_CItem){
            foreach($_CItem as &$_CItemItem){
                $_CItemItem['inviter'] = substr_replace($_CItemItem['inviter'],'**',3);
                $_CItemItem['inviter_mobile'] = substr_replace($_CItemItem['inviter_mobile'],'****',3,4);
            }
        }
        return $this->render('rewardTotalRank',[
            'totalPerson'=>$totalPerson,
            'topNum'=>$topNum,
            'rewardTotalRank'=>$data
        ]);
    }




}