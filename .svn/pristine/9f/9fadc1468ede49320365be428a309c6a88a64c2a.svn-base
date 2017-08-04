<?php
/**
 * @Desc: 微信推广活动->【后台管理系统】->【未结算用户管理】 控制器
 * @date:	2016-03-18
 */
namespace backend\modules\promotion\controllers;
use backend\controllers\BaseController;
use common\models\VipPromotionSettle;
use yii;
use yii\data\Pagination;
use backend\models\VipPromotionLet;
use backend\models\VipPromotionSign;
use backend\models\VipPromotionApplyCash;
use backend\classes\UserLog;
use common\models\Excel;

class UnsettledManageController extends BaseController{

    /*
     * 访问“未结算用户管理”视图
     */
    public function actionIndex(){
        $buttons = $this->getCurrentActionBtn();
        return $this->render('index',[
            'buttons'=>$buttons,
        ]);
	}

    /**
     * 获取活动未结算用户列表
     */
    public function actionGetList(){
        //根据租车记录逆向查出邀请人
        $query = VipPromotionLet::find()
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
            ->where("{{%vip_promotion_let}}.inviter_invite_code != ''"); //只统计被邀请注册的
        //查询条件开始
        $query->andFilterWhere(['like','inviter.client',yii::$app->request->get('inviter')]);
        $query->andFilterWhere(['like','inviter.mobile',yii::$app->request->get('inviter_mobile')]);
        $query->andFilterWhere(['like','inviter.invite_code_mine',yii::$app->request->get('inviter_invite_code')]);
        //查询条件结束
        $query->groupBy('inviter_mobile'); //按邀请人分组
        $total = $query->count();
        //排序
        $sortColumn = yii::$app->request->get('sort');
        $sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
        $orderBy = "";
        if($sortColumn){
            switch($sortColumn){
                case 'inviter':
                    $orderBy = "inviter.client "; break;
                case 'inviter_mobile':
                    $orderBy = "inviter.client "; break;
                case 'inviter_invite_code':
                    $orderBy = "inviter.invite_code_mine "; break;
                case 'total_rent_num':
                    $orderBy = "rent_num "; break;
                default:
                    $orderBy = $sortColumn." "; break;
            }
        }else{
            $orderBy = " inviter.id ";
        }
        $orderBy .= $sortType;
        //分页
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $results = $query->offset($pages->offset)->limit($pages->limit)->orderBy($orderBy)->asArray()->all();
        //print_r($results);exit;

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
                $rewardData = VipPromotionLet::getReward($groupedRentNum);           // 计算奖金
                $reward = $rewardData['reward'];
                //$calcMethod = $rewardData['calcMethod'];
                $data[] = [
                    'inviter_id'=>$row['inviter_id'],
                    'inviter'=>$row['inviter'],
                    'inviter_mobile'=>$row['inviter_mobile'],
                    'inviter_invite_code'=>$row['inviter_invite_code'],
                    'total_rent_num'=>$row['rent_num'],
                    'total_reward'=>number_format($reward,2,".","")
                ];
            }
            //===查各邀请人的已结算、待结算金额=======================================
            $results = VipPromotionSettle::find()
                ->select([
                    'inviter_id',
                    'totalSettled'=>'SUM(settled_money)'])
                ->groupBy('inviter_id')
                ->indexBy('inviter_id')
                ->asArray()->all();
            foreach($data as &$_CControllerRow){
                if(isset($results[$_CControllerRow['inviter_id']])){
                    $settled = $results[$_CControllerRow['inviter_id']]['totalSettled'];
                    $_CControllerRow['total_reward_settled'] =  number_format($settled,2,".","");
                    $unsettled = $_CControllerRow['total_reward'] - $settled;
                    $_CControllerRow['total_reward_unsettled'] = number_format($unsettled,2,".","");
                }else{
                    $_CControllerRow['total_reward_settled'] =  number_format(0.00,2,".","");
                    $_CControllerRow['total_reward_unsettled'] = $_CControllerRow['total_reward'];
                }
            }
            unset($results);
            //===查各邀请人邀请注册总人数============================================
            $inviterIds = array_column($data, 'inviter_id');
            $inviterIdStr = "'".implode("','",$inviterIds)."'";
            //自连接查询
            $sql = "
              SELECT
                    inviter.id,inviter.client AS inviter,inviter.invite_code_mine,
                    COUNT(receiver.invite_code_used) AS usedNum,
                    GROUP_CONCAT(receiver.id) AS receiver_id
                FROM `cs_vip_promotion_sign` inviter
                LEFT JOIN `cs_vip_promotion_sign` receiver ON inviter.invite_code_mine = receiver.invite_code_used
                WHERE  receiver.code != '' AND  receiver.invite_code_mine != '' AND  receiver.invite_code_used != '' AND receiver.is_del = 0 AND inviter.id IN({$inviterIdStr})
                GROUP BY(receiver.invite_code_used)
                ORDER BY usedNum DESC
            ";
            $results =  VipPromotionSign::findBySql($sql)->asArray()->all();
            foreach($data as &$_CControllerRow){
                foreach($results as $item){
                    if($_CControllerRow['inviter_id'] == $item['id']){
                        $_CControllerRow['total_invite_num'] = $item['usedNum'];
                    }
                }
            }
            unset($results);
        }

        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        return json_encode($returnArr);
    }


    /*
     * 访问“查看朋友租车详情”视图
     */
    public function actionScanFriendLetDetails(){
        $inviter_id = yii::$app->request->get('inviter_id') or die('未传递参数：inviter_id');
        return $this->render('scanFriendLetDetailsWin',['inviter_id'=>$inviter_id]);
    }

    /*
     * 获取朋友租车详情列表
     */
    public function actionGetFriendLetList(){
        $inviter_id = yii::$app->request->get('inviter_id') or die('未传递参数：inviter_id');
        $query = VipPromotionLet::find()
            ->select([
                '{{%vip_promotion_let}}.*',
                'renter'=>'renter.client',
                'renter_mobile'=>'renter.mobile',
                'renter_invite_code'=>'renter.invite_code_mine',
                'renter_sign_date'=>'LEFT(FROM_UNIXTIME(renter.systime),10)',
                'inviter'=>'inviter.client',
                'inviter_mobile'=>'inviter.mobile'
            ])
            ->joinWith('renterInfo',false)
            ->joinWith('inviterInfo',false)
            ->where(['inviter.id'=>$inviter_id]);
        //查询条件开始
        $query->andFilterWhere(['like','renter.client',yii::$app->request->get('renter')]);
        $query->andFilterWhere(['like','renter.mobile',yii::$app->request->get('renter_mobile')]);
        $query->andFilterWhere(['like','contract_no',yii::$app->request->get('contract_no')]);
        $createTimeStart = yii::$app->request->get('create_time_start');
        if($createTimeStart){
            $query->andFilterWhere(['>=','create_time',$createTimeStart]);
        }
        $createTimeEnd = yii::$app->request->get('create_time_end');
        if($createTimeEnd){
            $query->andFilterWhere(['<=','create_time',$createTimeEnd.' 23:59:59']);
        }
        $total = $query->count();
        //排序
        $sortColumn = yii::$app->request->get('sort');
        $sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
        $orderBy = "";
        if($sortColumn){
            switch($sortColumn){
                case 'renter':
                    $orderBy = "renter.client "; break;
                case 'renter_mobile':
                    $orderBy = "renter.mobile "; break;
                default:
                    $orderBy = "{{%vip_promotion_let}}.$sortColumn "; break;
            }
        }else{
            $orderBy = "{{%vip_promotion_let}}.id ";
        }
        $orderBy .= $sortType;
        //分页
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)->limit($pages->limit)->orderBy($orderBy)->asArray()->all();
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        return json_encode($returnArr);
    }


    /*
     * 结算奖金
     */
    public function actionSettle(){
        if(yii::$app->request->isPost){
            $formData = yii::$app->request->post();
            $model = new VipPromotionSettle();
            $model->inviter_id = $formData['inviter_id'];
            $model->settled_money = $formData['settled_money'];
            $model->settled_letId = $formData['unsettled_letIds'];
            $model->create_time = date('Y-m-d H:i:s');
            $model->creator_id = $_SESSION['backend']['adminInfo']['id'];
            if($model->save(true)){
                //同步修改租车记录的状态为“已结算”
                VipPromotionLet::updateAll(
                    ['is_settle'=>'YES'],
                    ['id'=>explode(',',$model->settled_letId)]
                );
                return json_encode(['status'=>true,'info'=>'提现申请结算成功！']);
            }else{
                return json_encode(['status'=>false,'info'=>'提现申请结算失败！']);
            }
        }else{
            $inviter_id = yii::$app->request->get('inviter_id') or die('未传递参数：inviter_id');
            //获取某个邀请人的邀请注册人数、朋友租车总数、奖金等统计信息
            $data = VipPromotionLet::getStatisticsByInviterId($inviter_id);
            return $this->render('settleWin',['statistics'=>$data]);
        }
    }
    

    /**
     * 导出Excel
     */
    public function actionExportGridData(){
        // 构建导出的excel表头
        $excHeaders = [
            [
                ['content'=>'邀请人','font-weight'=>true,'width'=>'15'],
                ['content'=>'手机号','font-weight'=>true,'width'=>'15'],
                ['content'=>'邀请码','font-weight'=>true,'width'=>'15'],
                ['content'=>'邀请注册总数(人)','font-weight'=>true,'width'=>'20'],
                ['content'=>'朋友租车总数(部)','font-weight'=>true,'width'=>'20'],
                ['content'=>'奖金总额(元)','font-weight'=>true,'width'=>'15'],
                ['content'=>'已结算(元)','font-weight'=>true,'width'=>'15'],
                ['content'=>'待结算(元)','font-weight'=>true,'width'=>'15']
            ]
        ];

        //根据租车记录逆向查出邀请人
        $query = VipPromotionLet::find()
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
            ->where("{{%vip_promotion_let}}.inviter_invite_code != ''"); //只统计被邀请注册的
        //查询条件开始
        $query->andFilterWhere(['like','inviter.client',yii::$app->request->get('inviter')]);
        $query->andFilterWhere(['like','inviter.mobile',yii::$app->request->get('inviter_mobile')]);
        $query->andFilterWhere(['like','inviter.invite_code_mine',yii::$app->request->get('inviter_invite_code')]);
        //查询条件结束
        $query->groupBy('inviter_mobile'); //按邀请人分组
        $results = $query->asArray()->all();
        //print_r($results);exit;
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
                $rewardData = VipPromotionLet::getReward($groupedRentNum);           // 计算奖金
                $reward = $rewardData['reward'];
                //$calcMethod = $rewardData['calcMethod'];
                $data[] = [
                    'inviter_id'=>$row['inviter_id'],
                    'inviter'=>$row['inviter'],
                    'inviter_mobile'=>$row['inviter_mobile'],
                    'inviter_invite_code'=>$row['inviter_invite_code'],
                    'total_rent_num'=>$row['rent_num'],
                    'total_reward'=>number_format($reward,2,".","")
                ];
            }
            //===查各邀请人的已结算、待结算金额=======================================
            $results = VipPromotionSettle::find()
                ->select([
                    'inviter_id',
                    'totalSettled'=>'SUM(settled_money)'])
                ->groupBy('inviter_id')
                ->indexBy('inviter_id')
                ->asArray()->all();
            foreach($data as &$_CControllerRow){
                if(isset($results[$_CControllerRow['inviter_id']])){
                    $settled = $results[$_CControllerRow['inviter_id']]['totalSettled'];
                    $_CControllerRow['total_reward_settled'] =  number_format($settled,2,".","");
                    $unsettled = $_CControllerRow['total_reward'] - $settled;
                    $_CControllerRow['total_reward_unsettled'] = number_format($unsettled,2,".","");
                }else{
                    $_CControllerRow['total_reward_settled'] =  number_format(0.00,2,".","");
                    $_CControllerRow['total_reward_unsettled'] = $_CControllerRow['total_reward'];
                }
            }
            unset($results);
            //===查各邀请人邀请注册总人数============================================
            $inviterIds = array_column($data, 'inviter_id');
            $inviterIdStr = "'".implode("','",$inviterIds)."'";
            //自连接查询
            $sql = "
              SELECT
                    inviter.id,inviter.client AS inviter,inviter.invite_code_mine,
                    COUNT(receiver.invite_code_used) AS usedNum,
                    GROUP_CONCAT(receiver.id) AS receiver_id
                FROM `cs_vip_promotion_sign` inviter
                LEFT JOIN `cs_vip_promotion_sign` receiver ON inviter.invite_code_mine = receiver.invite_code_used
                WHERE  receiver.code != '' AND  receiver.invite_code_mine != '' AND  receiver.invite_code_used != '' AND receiver.is_del = 0 AND inviter.id IN({$inviterIdStr})
                GROUP BY(receiver.invite_code_used)
                ORDER BY usedNum DESC
            ";
            $results =  VipPromotionSign::findBySql($sql)->asArray()->all();
            foreach($data as &$_CControllerRow){
                foreach($results as $item){
                    if($_CControllerRow['inviter_id'] == $item['id']){
                        $_CControllerRow['total_invite_num'] = $item['usedNum'];
                    }
                }
            }
            unset($results);
        }
        //print_r($data);exit;

        $excel = new Excel();
        $excel->setHeader([
            'creator'=>'皓峰通讯',
            'lastModifiedBy'=>'hao feng tong xun',
            'title'=>'vip_promotion_let',
            'subject'=>'vip_promotion_let',
            'description'=>'vip_promotion_let',
            'keywords'=>'vip_promotion_let',
            'category'=>'vip_promotion_let'
        ]);
        //---向excel添加表头--------
        foreach($excHeaders as $lineData){
            $excel->addLineToExcel($lineData);
        }
        //---向excel添加具体数据-----
        foreach($data as $item){
            //邀请人id不要；将邀请总人数插入到邀请码之后
            unset($item['inviter_id']);
            $total_invite_num = $item['total_invite_num'];
            unset($item['total_invite_num']);
            $lineData = [];
            foreach($item as $k=>$v) {
                if(!is_array($v)){
                    $lineData[] = ['content'=>$v];
                    if($k == 'inviter_invite_code'){
                        $lineData[] = ['content'=>$total_invite_num];
                    }
                }
            }
            $excel->addLineToExcel($lineData);
        }
        unset($data);

        $objPHPExcel = $excel->getPHPExcel();
        header("Content-type: application/octet-stream");
        header("Accept-Ranges: bytes");
        //header("Accept-Length:".$fileSize);
        header("Content-Disposition: attachment; filename=".iconv('utf-8','gbk','微信推广活动-奖金结算管理-导出.xls'));
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }




}