<?php
/**
 * @Desc:	微信推广活动租车记录 模型
 * @date:	2016-03-12
 */
namespace backend\models;
use yii;
class VipPromotionLet extends \common\models\VipPromotionLet{

    public function rules(){
        $rules = [
			[['contract_no', 'operator','mark'],'trim'],
            [['contract_no', 'operator','mark'],'filter','filter'=>'htmlspecialchars'],
            ['amount','default','value'=>0]
        ];
		return array_merge($rules,parent::rules());
    }


    //特别注意下这里的关联写法，不管是查租车人还是邀请人都是关联同一张注册信息表的。
	//关联注册信息表，查租车人信息。（加入了Yii2 AR的别名）
    public function getRenterInfo(){
        return $this->hasOne(VipPromotionSign::className(), ['id' => 'renter_id'])
                    ->from(VipPromotionSign::tableName().' renter'); // from设置别名
    }
    //关联注册信息表，查邀请人信息。（加入了Yii2 AR的别名）
    public function getInviterInfo(){
        return $this->hasOne(VipPromotionSign::className(), ['invite_code_mine' => 'inviter_invite_code'])
            ->andOnCondition("inviter.invite_code_mine != ''")  // 增加关联条件
            ->from(VipPromotionSign::tableName().' inviter');   // from设置别名
    }

    // 关联【人员表】
    public function getAdmin() {
        return $this->hasOne(Admin::className(), ['id' => 'creator_id']);
    }


    /*
     * 奖金计算规则（按月进行）
     * @$rentNum：本月租车数量
     */
    public static function getRewardPerMonth($rentNum){
        if($rentNum == 1){
            $reward = 200;
            $calcMethod = "租车{$rentNum}部，奖金定额{$reward}元；";
        }elseif($rentNum == 2){
            $reward = 500;
            $calcMethod = "租车{$rentNum}部，奖金定额{$reward}元；";
        }elseif($rentNum == 3){
            $reward = 700;
            $calcMethod = "租车{$rentNum}部，奖金定额{$reward}元；";
        }elseif($rentNum >= 4 && $rentNum <= 10){
            $reward = 500 * ($rentNum - 2);
            $calcMethod = "租车{$rentNum}部，奖金500x".($rentNum - 2)."={$reward}元；";
        }elseif($rentNum > 10){
            $reward = 450 * $rentNum;
            $calcMethod = "租车{$rentNum}部，奖金450x{$rentNum}={$reward}元；";
        }else{
            $reward = 0;
            $calcMethod = "租车0部，奖金0元";
        }
        return [
            'reward'=>$reward,
            'calcMethod'=>$calcMethod
        ];
    }


    /*
     * 计算某人的奖金总数量
     * @$groupedRentNum：数组，表示按月统计的租车数量。
     */
    public static function getReward($groupedRentNum){
        $data = [
            'reward'=>0.00,
            'calcMethod'=>'',
            'month_reward'=>[]
        ];
        if(is_array($groupedRentNum) && !empty($groupedRentNum)){
            foreach($groupedRentNum as $key=>$val){
                $res = self::getRewardPerMonth($val);
                $data['reward'] += $res['reward'];
                $data['calcMethod'] .= "【".$key."】".$res['calcMethod'];
                $data['month_reward'][$key] = $res['reward'];
            }
        }
        return $data;
    }


    /*
     * 获取奖金总排名榜 （注意：奖金只会派发给成功邀请朋友注册并租车的邀请人）
     * @$isJustGetTop：判断是否只需要获取奖金排名前x名。
     * @$topNum: 前x名。
     */
    public static function getRewardTotalRank($isJustGetTop=false,$topNum=0){
        $results = VipPromotionLet::find()
            ->select([
                'rent_num'=>'SUM({{%vip_promotion_let}}.amount) ',
                'details_rentYearMonth'=>'GROUP_CONCAT(LEFT(`cs_vip_promotion_let`.create_time,7))', //租车详情-租车年月
                'details_rentNum'=>'GROUP_CONCAT({{%vip_promotion_let}}.amount)',                    //租车详情-租车数量（与上顺序对应）
                //'renter'=>'GROUP_CONCAT(`renter`.`client`)',
                //'renter_mobile'=>'GROUP_CONCAT(`renter`.`mobile`)',
                'inviter_id'=>'inviter.id',
                'inviter'=>'inviter.client',
                'inviter_mobile'=>'inviter.mobile'
            ])
            ->joinWith('renterInfo',false)
            ->joinWith('inviterInfo',false)
            ->where("{{%vip_promotion_let}}.inviter_invite_code != ''") //只统计被邀请注册的
            ->groupBy('inviter_mobile') //按邀请人分组
            ->orderBy('rent_num DESC')
            ->asArray()->all();
        //print_r($results);exit;

        $tmpData = [];
        $rewardArr = [];
        $data = [];
        if(!empty($results)){
            foreach($results as $row){
                //计算奖金。（千万注意：奖金要求一月一计算，所以这里必须将租车数量按月进行分组统计）
                $rentYearMonth = explode(',',$row['details_rentYearMonth']);
                $rentNum = explode(',',$row['details_rentNum']);
                $groupedRentNum = [];
                foreach($rentYearMonth as $key=>$val){
                    if(isset($groupedRentNum[$val])){
                        $groupedRentNum[$val] += $rentNum[$key];
                    }else{
                        $groupedRentNum[$val] = $rentNum[$key]; //水平取出租车数量
                    }
                }
                $rewardData = self::getReward($groupedRentNum); // 计算奖金
                $reward = $rewardData['reward'];
                $rewardArr[] =  $reward;
                $tmpData[] = [
                    'inviter_id'=>$row['inviter_id'],
                    'inviter'=>$row['inviter'],
                    'inviter_mobile'=>$row['inviter_mobile'],
                    'rent_num'=>$row['rent_num'],
                    'rent_details'=>$groupedRentNum,
                    'reward'=>$reward,
                    'calcMethod'=>$rewardData['calcMethod']
                ];
            }

            //将奖金降序排列
            $rewardArr = array_unique($rewardArr);
            rsort($rewardArr);
            if($isJustGetTop){ //若只需要取奖金排名前x名。
                $rewardArr = array_slice($rewardArr, 0, $topNum);
            }
            //按奖金分组显示排名
            foreach($rewardArr as $reward){
                foreach($tmpData as $item){
                    if($item['reward'] == $reward){
                        $data[$reward][] = $item;
                    }
                }
            }
        }
        return $data;
    }


    /*
     * 获取某微信用户所邀请朋友的租车记录
     * @$open_id：微信用户open_id
     * @$isSettle：标记租车的奖金是否已结算给相应邀请人（YES/NO）
     */
    public static function getFriendsLetInfo($open_id,$isSettle=''){
        $data = [];
        if($open_id){
            $query = VipPromotionLet::find()
                ->select([
                    '{{%vip_promotion_let}}.id',
                    '{{%vip_promotion_let}}.renter_id',
                    '{{%vip_promotion_let}}.amount',
                    '{{%vip_promotion_let}}.contract_no',
                    '{{%vip_promotion_let}}.create_time',
                    '{{%vip_promotion_let}}.is_settle',
                    'renter'=>'renter.client',
                    'renter_mobile'=>'renter.mobile',
                    'inviter'=>'inviter.client',
                    'inviter_mobile'=>'inviter.mobile'
                ])
                ->joinWith('renterInfo',false)
                ->joinWith('inviterInfo',false)
                ->where(['inviter.open_id'=>$open_id]);
            if($isSettle){
                $query->andWhere(['{{%vip_promotion_let}}.is_settle'=>$isSettle]);
            }
            $data = $query->asArray()->all();
        }
        return $data;
    }


    /*
     * 获取某一个邀请人的邀请注册人数、朋友租车总数、奖金等统计信息
     * @$inviter_id：邀请人id
     */
    public static function getStatisticsByInviterId($inviter_id){
        //根据租车记录查改邀请人
        $letData =  VipPromotionLet::find()
            ->select([
                'rent_num'=>'SUM({{%vip_promotion_let}}.amount) ',
                'details_rentYearMonth'=>'GROUP_CONCAT(LEFT(`cs_vip_promotion_let`.create_time,7))', //租车详情-租车年月
                'details_rentNum'=>'GROUP_CONCAT({{%vip_promotion_let}}.amount)',                    //租车详情-租车数量（与上顺序对应）
                'inviter_id'=>'inviter.id',
                'inviter'=>'inviter.client',
                'inviter_mobile'=>'inviter.mobile',
                'inviter_invite_code'=>'inviter.invite_code_mine'
            ])
            ->joinWith('inviterInfo',false)
            ->where(['inviter.id'=>$inviter_id])
            ->asArray()->one();
        //print_r($letData);exit;
        $data = [];
        if(!empty($letData)){
            //计算奖金。（千万注意：奖金要求一月一计算，所以这里必须将租车数量按月进行分组统计）
            $rentYearMonth = explode(',',$letData['details_rentYearMonth']);
            $rentNum = explode(',',$letData['details_rentNum']);
            $groupedRentNum = [];
            foreach($rentYearMonth as $key=>$val){
                if(isset($groupedRentNum[$val])){
                    $groupedRentNum[$val] += $rentNum[$key];
                }else{
                    $groupedRentNum[$val] = $rentNum[$key]; //水平取出租车数量
                }
            }
            $rewardData = VipPromotionLet::getReward($groupedRentNum); // 计算奖金
            $reward = $rewardData['reward'];
            $data = [
                'inviter_id'=>$letData['inviter_id'],
                'inviter'=>$letData['inviter'],
                'inviter_mobile'=>$letData['inviter_mobile'],
                'inviter_invite_code'=>$letData['inviter_invite_code'],
                'total_rent_num'=>$letData['rent_num'],
                'total_reward'=>number_format($reward,2,".","")
            ];
            //===查已结算、待结算金额=======================================
            $res = VipPromotionSettle::find()
                ->select(['totalSettled'=>'SUM(settled_money)'])
                ->where(['inviter_id'=>$inviter_id])
                ->asArray()->one();
            $data['total_reward_settled'] =  number_format($res['totalSettled'],2,".","");
            $unsettled = $data['total_reward'] - $res['totalSettled'];
            $data['total_reward_unsettled'] = number_format($unsettled,2,".","");
            //print_r($data);exit;

            unset($results);
            //===查邀请注册总人数============================================
            //自连接查询
            $sql = "
                  SELECT
                        inviter.id,inviter.client AS inviter,inviter.invite_code_mine,
                        COUNT(receiver.invite_code_used) AS usedNum,
                        GROUP_CONCAT(receiver.id) AS receiver_id
                    FROM `cs_vip_promotion_sign` inviter
                    LEFT JOIN `cs_vip_promotion_sign` receiver ON inviter.invite_code_mine = receiver.invite_code_used
                    WHERE  receiver.code != '' AND  receiver.invite_code_mine != '' AND  receiver.invite_code_used != '' AND receiver.is_del = 0 AND inviter.id={$inviter_id}
                    GROUP BY(receiver.invite_code_used)
                    ORDER BY usedNum DESC
                ";
            $res =  VipPromotionSign::findBySql($sql)->asArray()->one();
            $data['total_invite_num'] = $res['usedNum'];
            //===查待结算的租车记录==================================================
            $res = self::getUnsettledLetsByInviterId($inviter_id);
            $data['unsettled_letIds'] = $res['unsettled_letIds'];
        }
        return $data;
    }


    /*
     * 获取某个邀请人的待结算的租车记录
     * @$inviter_id：邀请人id
     */
    public static function getUnsettledLetsByInviterId($inviter_id){
        $data =  VipPromotionLet::find()
            ->select([
                'unsettled_letIds'=>"GROUP_CONCAT({{%vip_promotion_let}}.id)",
                'unsettled_letContractNos'=>"GROUP_CONCAT(contract_no)"
            ])
            ->joinWith('inviterInfo',false)
            ->where(['inviter.id'=>$inviter_id,'is_settle'=>'NO'])
            ->asArray()->one();
        return $data;
    }


    /*
     * 根据租车记录ID获取租车合同编号
     * @$letIds：数组，租车记录id
     */
    public static function getLetContractNosByLetIds($letIds){
        $letContractNos = '';
        if(is_array($letIds) && !empty($letIds)){
            $data =  VipPromotionLet::find()
                ->select([
                    'letContractNos'=>"GROUP_CONCAT(contract_no)"
                ])
                ->where(['id'=>$letIds])
                ->asArray()->one();
            $letContractNos = $data['letContractNos'];
        }
        return $letContractNos;
    }



}
