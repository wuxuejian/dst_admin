<?php
/**
 * @Desc: 微信推广活动->【后台管理系统】->【租车信息管理】 控制器
 * @date:	2016-03-11
 */
namespace backend\modules\promotion\controllers;
use backend\controllers\BaseController;
use yii;
use yii\data\Pagination;
use backend\models\VipPromotionLet;
use backend\models\VipPromotionSign;
use backend\classes\UserLog;
use common\models\Excel;

class LetInfoController extends BaseController{

    /*
     * 访问“租车信息管理”视图
     */
    public function actionIndex(){
        $buttons = $this->getCurrentActionBtn();
        return $this->render('index',[
            'buttons'=>$buttons,
        ]);
	}

    /**
     * 获取活动租车信息列表
     */
    public function actionGetList(){
        $query = VipPromotionLet::find()
            ->select([
                '{{%vip_promotion_let}}.*',
                'renter'=>'renter.client',
                'renter_mobile'=>'renter.mobile',
                'renter_sign_date'=>'renter.systime',
                'inviter'=>'inviter.client',
                'inviter_mobile'=>'inviter.mobile',
            ])
            ->joinWith('renterInfo',false)
            ->joinWith('inviterInfo',false);
        //查询条件开始
        $query->andFilterWhere(['like','renter.client',yii::$app->request->get('renter')]);
        $query->andFilterWhere(['like','renter.mobile',yii::$app->request->get('renter_mobile')]);
        $query->andFilterWhere(['like','inviter.client',yii::$app->request->get('inviter')]);
        $query->andFilterWhere(['like','inviter.mobile',yii::$app->request->get('inviter_mobile')]);
        $query->andFilterWhere(['like','contract_no',yii::$app->request->get('contract_no')]);
        $signDateStart = yii::$app->request->get('sign_date_start');
        if($signDateStart){
            $query->andFilterWhere(['>=','sign_date',$signDateStart]);
        }
        $signDateEnd = yii::$app->request->get('sign_date_end');
        if($signDateEnd){
            $query->andFilterWhere(['<=','sign_date',$signDateEnd.' 23:59:59']);
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
                case 'inviter':
                    $orderBy = "inviter.client "; break;
                case 'inviter_mobile':
                    $orderBy = "inviter.client "; break;
                case 'renter_sign_date':
                    $orderBy = "renter.systime "; break;
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
     * 新增租车信息
     */
    public function actionAdd(){
        if(yii::$app->request->isPost){
            $model = new VipPromotionLet;
            $formData = yii::$app->request->post();
            $model->load($formData,'');
            $model->is_settle = $formData['inviter_invite_code'] != ''  ? 'NO' : ''; //若有邀请人则默认状态是未结算。
            $returnArr = [];
            if($model->validate()){
                $model->create_time = date('Y-m-d H:i:s');
                $model->creator_id = $_SESSION['backend']['adminInfo']['id'];
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '新增租车信息成功！';
                    // 添加日志
                    $logStr = "微信活动管理-新增租车信息（合同编号：" . ($model->contract_no) . "）";
                    UserLog::log($logStr, 'sys');
                    //新增后，若有邀请人则需要短信通知邀请人
                    $returnArr['sendMsgData'] = '';
                    if($model->inviter_invite_code){ //前提要有邀请人
                        //查邀请人手机号
                        $inviter = VipPromotionSign::find()
                            ->select(['id','mobile'])
                            ->where(['invite_code_mine'=>$model->inviter_invite_code])
                            ->asArray()->one();
                        //查租车人名称
                        $renter = VipPromotionSign::find()
                            ->select(['client'])
                            ->where(['id'=>$model->renter_id])
                            ->asArray()->one();
                        //查邀请人未结算奖金
                        $res = VipPromotionLet::getStatisticsByInviterId($inviter['id']);
                        if($inviter && $inviter['mobile'] && $renter && $renter['client'] && $res && $res['total_reward_unsettled']>0){
                            $returnArr['sendMsgData'] = [
                                'type'=>'letNotice',
                                'mobile'=>$inviter['mobile'],
                                'friendName'=>$renter['client'],
                                'rentNum'=>$model->amount,
                                'unsettledReward'=>$res['total_reward_unsettled'],
                            ];
                        }else{
                            $returnArr['info'] .= "<br>通知邀请人短信发送失败（要发送的数据不正确）！";
                        }
                    }else{
                        //无邀请人则不作任何处理...
                    }
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '新增租车信息失败！';
                }
            }else{
                $returnArr['status'] = false;
                $error = $model->getErrors();
                if($error){
                    $arr = array_column($error,0);
                    $errorStr = join('',$arr);
                    $returnArr['info'] = $errorStr;
                }else{
                    $returnArr['info'] = '未知错误';
                }
            }
            return json_encode($returnArr);
        }else{
            return $this->render('addWin');
        }
    }

    /*
     * 修改租车信息
     */
    public function actionEdit(){
        if(yii::$app->request->isPost){
            $formData = yii::$app->request->post();
            $model = VipPromotionLet::findOne($formData['id']);
            if(!$model){
                return json_encode(['status'=>false,'info'=>'找不到对应记录！']);
            }
            $model->load($formData,'');
            $returnArr = [];
            if($model->validate()){
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '修改租车信息成功！';
                    // 添加日志
                    $logStr = "微信活动管理-修改租车信息（合同编号：" . ($model->contract_no) . "）";
                    UserLog::log($logStr, 'sys');
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '修改租车信息失败！';
                }
            }else{
                $returnArr['status'] = false;
                $error = $model->getErrors();
                if($error){
                    $arr = array_column($error,0);
                    $errorStr = join('',$arr);
                    $returnArr['info'] = $errorStr;
                }else{
                    $returnArr['info'] = '未知错误';
                }
            }
            return json_encode($returnArr);
        }else{
            $id = yii::$app->request->get('id') or die('Param(id) is required');
            $letInfo = VipPromotionLet::find()->where(['id'=>$id])->asArray()->one();
            //查出租车人手机
            $signInfo = VipPromotionSign::find()->select(['mobile'])->where(['id'=>$letInfo['renter_id']])->asArray()->one();
            $letInfo['renter_mobile'] = $signInfo['mobile'];
            return $this->render('editWin',['letInfo'=>$letInfo]);
        }
    }

    /*
     * 查看租车信息详情
     */
    public function actionScanLetDetails(){
        $id = yii::$app->request->get('id') or die('Param(id) is required');
        $letInfo = VipPromotionLet::find()
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
            ->where(['{{%vip_promotion_let}}.id'=>$id])
            ->asArray()->one();
        return $this->render('scanLetDetailsWin',['letInfo'=>$letInfo]);
    }



    /**
     * 导出Excel
     */
    public function actionExportGridData(){
        // 构建导出的excel表头
        $excHeaders = [
            [
                ['content'=>'租车人','font-weight'=>true,'width'=>'15'],
                ['content'=>'手机号','font-weight'=>true,'width'=>'15'],
                ['content'=>'租车数量','font-weight'=>true,'width'=>'10'],
                ['content'=>'合同编号','font-weight'=>true,'width'=>'25'],
                ['content'=>'合同签订日期','font-weight'=>true,'width'=>'20'],
                ['content'=>'合同受理人','font-weight'=>true,'width'=>'15'],
                ['content'=>'租车时间','font-weight'=>true,'width'=>'20'],
                ['content'=>'邀请人','font-weight'=>true,'width'=>'15'],
                ['content'=>'邀请人手机号','font-weight'=>true,'width'=>'15'],
                ['content'=>'备注','font-weight'=>true,'width'=>'30']
            ]
        ];

        // 要查的字段，与导出的excel表头对应
        $selectArr = [
            'renter'=>'renter.client',
            'renter_mobile'=>'renter.mobile',
            '{{%vip_promotion_let}}.amount',
            '{{%vip_promotion_let}}.contract_no',
            '{{%vip_promotion_let}}.sign_date',
            '{{%vip_promotion_let}}.operator',
            '{{%vip_promotion_let}}.create_time',
            'inviter'=>'inviter.client',
            'inviter_mobile'=>'inviter.mobile',
            '{{%vip_promotion_let}}.mark',
        ];

        $query = VipPromotionLet::find()
            ->select($selectArr)
            ->joinWith('renterInfo',false)
            ->joinWith('inviterInfo',false);
        //查询条件开始
        $query->andFilterWhere(['like','renter.client',yii::$app->request->get('renter')]);
        $query->andFilterWhere(['like','renter.mobile',yii::$app->request->get('renter_mobile')]);
        $query->andFilterWhere(['like','inviter.client',yii::$app->request->get('inviter')]);
        $query->andFilterWhere(['like','inviter.mobile',yii::$app->request->get('inviter_mobile')]);
        $query->andFilterWhere(['like','contract_no',yii::$app->request->get('contract_no')]);
        $signDateStart = yii::$app->request->get('sign_date_start');
        if($signDateStart){
            $query->andFilterWhere(['>=','sign_date',$signDateStart]);
        }
        $signDateEnd = yii::$app->request->get('sign_date_end');
        if($signDateEnd){
            $query->andFilterWhere(['<=','sign_date',$signDateEnd.' 23:59:59']);
        }
        //查询条件结束
        $data = $query->asArray()->all();
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
        //---向excel添加表头-------------
        foreach($excHeaders as $lineData){
            $excel->addLineToExcel($lineData);
        }
        //---向excel添加具体数据----------
        foreach($data as $item){
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