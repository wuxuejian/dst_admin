<?php
/**
 * @Desc: 微信推广活动->【后台管理系统】->【提现申请审核】 控制器
 * @date:	2016-03-11
 */
namespace backend\modules\promotion\controllers;
use backend\controllers\BaseController;
use yii;
use yii\data\Pagination;
use backend\models\VipPromotionLet;
use backend\models\VipPromotionSign;
use backend\models\VipPromotionApplyCash;
use backend\models\VipPromotionSettle;
use backend\classes\UserLog;
use common\models\Excel;

class ApplyAuditController extends BaseController{

    /*
     * 访问“提现申请审核”视图
     */
    public function actionIndex(){
        $buttons = $this->getCurrentActionBtn();
        return $this->render('index',[
            'buttons'=>$buttons,
        ]);
	}

    /**
     * 获取活动提现申请列表
     */
    public function actionGetList(){
        $query = VipPromotionApplyCash::find()
            ->select([
                '{{%vip_promotion_apply_cash}}.*',
                'applicant'=>'applicant.client',
                'applicant_mobile'=>'applicant.mobile',
            ])
            ->joinWith('applicantInfo',false);
        //查询条件开始
        $query->andFilterWhere(['like','applicant.client',yii::$app->request->get('applicant')]);
        $query->andFilterWhere(['like','applicant.mobile',yii::$app->request->get('applicant_mobile')]);
        $query->andFilterWhere(['=','settle_status',yii::$app->request->get('settle_status')]);
        $applyDateStart = yii::$app->request->get('apply_date_start');
        if($applyDateStart){
            $query->andFilterWhere(['>=','apply_date',$applyDateStart]);
        }
        $applyDateEnd = yii::$app->request->get('apply_date_end');
        if($applyDateEnd){
            $query->andFilterWhere(['<=','apply_date',$applyDateEnd.' 23:59:59']);
        }
        $total = $query->count();
        //排序
        $sortColumn = yii::$app->request->get('sort');
        $sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
        $orderBy = "";
        if($sortColumn){
            switch($sortColumn){
                case 'applicant':
                    $orderBy = "applicant.client "; break;
                case 'applicant_mobile':
                    $orderBy = "applicant.mobile "; break;
                default:
                    $orderBy = "{{%vip_promotion_apply_cash}}.$sortColumn "; break;
            }
        }else{
            $orderBy = "{{%vip_promotion_apply_cash}}.id ";
        }
        $orderBy .= $sortType;
        //分页
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)->limit($pages->limit)->orderBy($orderBy)->asArray()->all();

        //根据租车记录ID获取租车合同编号
        foreach($data as &$row){
            $row['apply_letIds'] = VipPromotionLet::getLetContractNosByLetIds(explode(',',$row['apply_letIds']));
            $row['real_settle_letIds'] = VipPromotionLet::getLetContractNosByLetIds(explode(',',$row['real_settle_letIds']));
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
        $apply_id = yii::$app->request->get('apply_id') or die('未传递参数：apply_id');
        return $this->render('scanFriendLetDetailsWin',['apply_id'=>$apply_id]);
    }

    /*
     * 获取朋友租车详情列表
     */
    public function actionGetFriendLetList(){
        $apply_id = yii::$app->request->get('apply_id') or die('未传递参数：apply_id');
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
            ->where(['inviter.id'=>$apply_id]);
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
            $model->applyCash_id = $formData['applyCash_id'];
            if($model->save(true)){
                //同步修改申请记录的状态为“已结算”，并保持实际结算金额和实际结算租车记录id
                VipPromotionApplyCash::updateAll(
                    [
                        'settle_status'=>'SETTLED',
                        'real_settle_money'=>$model->settled_money,
                        'real_settle_letIds'=>$model->settled_letId
                    ],
                    ['id'=>$model->applyCash_id]
                );
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
            $id = yii::$app->request->get('id') or die('未传递参数：id');
            $applyInfo = VipPromotionApplyCash::find()
                ->select(['applyCash_id'=>'id','apply_id','unsettled_reward','apply_letIds'])
                ->where(['id'=>$id])
                ->asArray()->one();
            //获取某个邀请人的邀请注册人数、朋友租车总数、奖金等统计信息
            $data = VipPromotionLet::getStatisticsByInviterId($applyInfo['apply_id']);
            return $this->render('settleWin',['statistics'=>array_merge($data,$applyInfo)]);
        }
    }



    /**
     * 导出Excel
     */
    public function actionExportGridData(){
        // 构建导出的excel表头
        $excHeaders = [
            [
                ['content'=>'申请人','font-weight'=>true,'width'=>'15'],
                ['content'=>'手机号','font-weight'=>true,'width'=>'15'],
                ['content'=>'申请日期','font-weight'=>true,'width'=>'15'],
                ['content'=>'转账方式','font-weight'=>true,'width'=>'15'],
                ['content'=>'银行名称','font-weight'=>true,'width'=>'15'],
                ['content'=>'银行卡号','font-weight'=>true,'width'=>'20'],
                ['content'=>'支付宝账号','font-weight'=>true,'width'=>'20'],
                ['content'=>'结算状态','font-weight'=>true,'width'=>'15'],
                ['content'=>'申请结算金额(元)','font-weight'=>true,'width'=>'20'],
                ['content'=>'申请结算合同','font-weight'=>true,'width'=>'35'],
                ['content'=>'实际结算金额(元)','font-weight'=>true,'width'=>'20'],
                ['content'=>'实际结算合同','font-weight'=>true,'width'=>'35']
            ]
        ];

        // 要查的字段，与导出的excel表头对应
        $selectArr = [
            'applicant'=>'applicant.client',
            'applicant_mobile'=>'applicant.mobile',
            '{{%vip_promotion_apply_cash}}.apply_date',
            '{{%vip_promotion_apply_cash}}.pay_type',
            '{{%vip_promotion_apply_cash}}.bank_name',
            '{{%vip_promotion_apply_cash}}.bank_card',
            '{{%vip_promotion_apply_cash}}.alipay_account',
            '{{%vip_promotion_apply_cash}}.settle_status',
            '{{%vip_promotion_apply_cash}}.unsettled_reward',
            '{{%vip_promotion_apply_cash}}.apply_letIds',
            '{{%vip_promotion_apply_cash}}.real_settle_money',
            '{{%vip_promotion_apply_cash}}.real_settle_letIds',
        ];

        $query = VipPromotionApplyCash::find()
            ->select($selectArr)
            ->joinWith('applicantInfo',false);
        //查询条件开始
        $query->andFilterWhere(['like','applicant.client',yii::$app->request->get('applicant')]);
        $query->andFilterWhere(['like','applicant.mobile',yii::$app->request->get('applicant_mobile')]);
        $query->andFilterWhere(['=','settle_status',yii::$app->request->get('settle_status')]);
        $applyDateStart = yii::$app->request->get('apply_date_start');
        if($applyDateStart){
            $query->andFilterWhere(['>=','apply_date',$applyDateStart]);
        }
        $applyDateEnd = yii::$app->request->get('apply_date_end');
        if($applyDateEnd){
            $query->andFilterWhere(['<=','apply_date',$applyDateEnd.' 23:59:59']);
        }
        //查询条件结束
        $data = $query->asArray()->all();
        //print_r($data);exit;
        //根据租车记录ID获取租车合同编号
        foreach($data as &$row){
            $row['apply_letIds'] = VipPromotionLet::getLetContractNosByLetIds(explode(',',$row['apply_letIds']));
            $row['real_settle_letIds'] = VipPromotionLet::getLetContractNosByLetIds(explode(',',$row['real_settle_letIds']));
        }

        $excel = new Excel();
        $excel->setHeader([
            'creator'=>'皓峰通讯',
            'lastModifiedBy'=>'hao feng tong xun',
            'title'=>'cs_vip_promotion_apply_cash',
            'subject'=>'cs_vip_promotion_apply_cash',
            'description'=>'cs_vip_promotion_apply_cash',
            'keywords'=>'cs_vip_promotion_apply_cash',
            'category'=>'cs_vip_promotion_apply_cash'
        ]);

        //---向excel添加表头----------
        foreach($excHeaders as $lineData){
            $excel->addLineToExcel($lineData);
        }
        //---向excel添加具体数据-------
        foreach($data as $item){
            $item['pay_type'] = $item['pay_type']=='bank' ? '银行' : ($item['pay_type']=='alipay' ? '支付宝' : $item['pay_type']);
            $item['settle_status'] = $item['settle_status']=='SETTLED' ? '已结算' : ($item['settle_status']=='UNSETTLED' ? '未结算' : $item['settle_status']);
            $lineData = [];
            foreach($item as $k=>$v) {
                if(!is_array($v)){
                    $lineData[] = ['content'=>$v];
                }
            }
            $excel->addLineToExcel($lineData);
        }
        unset($data);

        $objPHPExcel = $excel->getPHPExcel();
        header("Content-type: application/octet-stream");
        header("Accept-Ranges: bytes");
        //header("Accept-Length:".$fileSize);
        header("Content-Disposition: attachment; filename=".iconv('utf-8','gbk','微信推广活动租车信息导出.xls'));
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }




}