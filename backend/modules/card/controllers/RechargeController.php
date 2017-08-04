<?php
/**
 * @Desc:   充电卡充值管理控制器
 * @author: wangmin
 * @date:   2016-02-19
 */
namespace backend\modules\card\controllers;
use backend\controllers\BaseController;
use yii;
use yii\data\Pagination;
use backend\models\ConfigCategory;
use common\models\Excel;
use backend\models\ChargeCard;
use backend\models\ChargeCardRechargeRecord;
use backend\models\ChargeFrontmachine;

class RechargeController extends BaseController{

    /**
     * 充电卡充值
     */
    public function actionAdd(){
        if(yii::$app->request->isPost){
            $returnArr = [
                'status'=>false,
                'info'=>'',
            ];
            //查询卡号id
            $cardNo = yii::$app->request->post('cc_code');
            if(!$cardNo){
                $returnArr['info'] = '无法获取到卡号请重新读卡！';
                echo json_encode($returnArr);
                return;
            }
            $cardInfo = ChargeCard::find()
                ->select(['cc_id','recharge_times'])
                ->where(['cc_code'=>$cardNo])
                ->asArray()->one();
            if(!$cardInfo){
                $returnArr['info'] = '系统无该卡数据！';
                echo json_encode($returnArr);
                return;
            }
            $model = new ChargeCardRechargeRecord;
            $model->load(yii::$app->request->post(),'');
            $model->ccrr_code = 'ccr'.uniqid();
            $model->ccrr_card_id = $cardInfo['cc_id'];
            $model->ccrr_create_time = date('Y-m-d H:i:s');
            $model->ccrr_creator_id = $_SESSION['backend']['adminInfo']['id'];
            $model->ccrr_after_money = $model->ccrr_before_money
                + $model->ccrr_recharge_money
                + $model->ccrr_incentive_money;
            $model->write_status = 'fail';
            if($model->save(true)){
                //返回操作状态
                $returnArr['status'] = true;
                $returnArr['info'] = '充值操作成功！';
                $returnArr['rechargeId'] = $model->ccrr_id;
                $returnArr['rechargeMoney'] = $model->ccrr_recharge_money + $model->ccrr_incentive_money;
                $returnArr['rechargeTimes'] = $cardInfo['recharge_times'] + 1;
            }else{
                $errors = $model->getErrors();
                if($errors){
                    $returnArr['info'] = join('',array_column($errors,0));
                }else{
                    $returnArr['info'] = '未知错误！';
                }
            }
            return json_encode($returnArr);
        }else{
            return $this->render('add');
        }
        
    }

    /**
     * 写卡成功回调
     */
    public function actionWriteSuccess(){
        $rechargeId = yii::$app->request->get('rechargeId');
        $rechargeInfo = ChargeCardRechargeRecord::find()
            ->select(['ccrr_id','ccrr_card_id','ccrr_after_money'])
            ->where(['ccrr_id'=>$rechargeId])
            ->asArray()->one();
        if($rechargeInfo){
            ChargeCardRechargeRecord::updateAll([
                    'write_status'=>'success'
                ],[
                    'ccrr_id'=>$rechargeInfo['ccrr_id']
            ]);
            //修改余额与充值次数
            ChargeCard::updateAll([
                    'cc_current_money'=>$rechargeInfo['ccrr_after_money'],
                    'cm_update_datetime'=>date('Y-m-d H:i:s'),
                ],[
                    'cc_id'=>$rechargeInfo['ccrr_card_id'],
            ]);
            //更新次数
            ChargeCard::updateAllCounters([
                'recharge_times'=>1
                ],[
                'cc_id'=>$rechargeInfo['ccrr_card_id'],
            ]);
        }
    }



    /**
     * 访问【IC电卡充值记录】菜单视图
     */
    public function actionIcRechargeRecords()
    {
        $configItems = ['cc_type'];
        $data['config'] = (new ConfigCategory())->getCategoryConfig($configItems,'value');
        $data['buttons'] = $this->getCurrentActionBtn();
        return $this->render('icRechargeRecords',$data);
    }

    /**
     * 获取【IC电卡充值记录】菜单视图里的充值记录列表
     */
    public function actionGetIcRechargeRecords()
    {
        $query = ChargeCardRechargeRecord::find()
            ->select([
                '{{%charge_card_recharge_record}}.*',
                'ccrr_creator' => '{{%admin}}.username',
                '{{%charge_card}}.cc_code',
                '{{%charge_card}}.cc_type',
            ])
            ->joinWith('admin', false, 'LEFT JOIN')
            ->joinWith('chargeCard', false, 'LEFT JOIN')
            ->where(['ccrr_is_del' => 0]);
        //查询条件
        $query->andFilterWhere(['LIKE', 'ccrr_code', yii::$app->request->get('ccrr_code')]);
        $query->andFilterWhere(['LIKE', 'cc_code', yii::$app->request->get('cc_code')]);
        $query->andFilterWhere(['=', 'cc_type', yii::$app->request->get('cc_type')]);
        $query->andFilterWhere(['>=', 'ccrr_create_time', yii::$app->request->get('ccrr_create_time_start')]);
        $endTime = yii::$app->request->get('ccrr_create_time_end');
        if($endTime){
            $query->andFilterWhere(['<=', 'ccrr_create_time', $endTime.' 23:59:59']);
        }
        $query->andFilterWhere(['write_status'=>yii::$app->request->get('write_status')]);
        $total = $query->count();
        $query2 = clone $query; // 底部合计用
        //分页
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount' => $total, 'pageSize' => $pageSize]);
        //排序
        if (yii::$app->request->get('sort')) {
            $field = yii::$app->request->get('sort');        //field
            $direction = yii::$app->request->get('order');  //asc or desc
            $orderStr = $field . ' ' . $direction;
        } else {
            $orderStr = 'ccrr_id DESC';
        }
        $data = $query->offset($pages->offset)->limit($pages->limit)->orderBy($orderStr)->asArray()->all();
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        // 表格底部增加合计行
        $mySum = $query2
            ->select([
                'ccrr_recharge_money'=>'SUM(ccrr_recharge_money)',
                'ccrr_incentive_money'=>'SUM(ccrr_incentive_money)',
                'ccrr_before_money'=>'SUM(ccrr_before_money)',
                'ccrr_after_money'=>'SUM(ccrr_after_money)'
            ])
            ->asArray()->one();
        $returnArr['footer'] = [[
            'ccrr_code'=>'合计：',
            'ccrr_recharge_money'=>$mySum['ccrr_recharge_money'],
            'ccrr_incentive_money'=>$mySum['ccrr_incentive_money'],
            'ccrr_before_money'=>$mySum['ccrr_before_money'],
            'ccrr_after_money'=>$mySum['ccrr_after_money']
        ]];
        return json_encode($returnArr);
    }


    /**
     * 导出Excel
     */
    public function actionExportGridData()
    {
        // 构建导出的excel表头
        $excHeaders = [
            ['content'=>'充值单号','font-weight'=>true,'width'=>'20'],
            ['content'=>'电卡编号','font-weight'=>true,'width'=>'15'],
            ['content'=>'电卡类型','font-weight'=>true,'width'=>'15'],
            ['content'=>'充值金额(元)','font-weight'=>true,'width'=>'15'],
            ['content'=>'奖励金额(元)','font-weight'=>true,'width'=>'15'],
            ['content'=>'充值前余额(元)','font-weight'=>true,'width'=>'15'],
            ['content'=>'充值后余额(元)','font-weight'=>true,'width'=>'15'],
            ['content'=>'充值时间','font-weight'=>true,'width'=>'20'],
            ['content'=>'写卡状态','font-weight'=>true,'width'=>'20'],
            ['content'=>'操作人员','font-weight'=>true,'width'=>'15'],
            ['content'=>'备注','font-weight'=>true,'width'=>'30']
        ];

        // 要查的字段，与导出的excel表头对应
        $selectArr = [
            'ccrr_code',
            'cc_code',
            'cc_type',
            'ccrr_recharge_money',
            'ccrr_incentive_money',
            'ccrr_before_money',
            'ccrr_after_money',
            'ccrr_create_time',
            'write_status',
            '{{%admin}}.username',
            'ccrr_mark'
        ];

        $query = ChargeCardRechargeRecord::find()
            ->select($selectArr)
            ->joinWith('admin', false, 'LEFT JOIN')
            ->joinWith('chargeCard', false, 'LEFT JOIN')
            ->where(['ccrr_is_del' => 0]);
        //查询条件开始
        $query->andFilterWhere(['LIKE', 'ccrr_code', yii::$app->request->get('ccrr_code')]);
        $query->andFilterWhere(['LIKE', 'cc_code', yii::$app->request->get('cc_code')]);
        $query->andFilterWhere(['=', 'cc_type', yii::$app->request->get('cc_type')]);
        $query->andFilterWhere(['>=', 'ccrr_create_time', yii::$app->request->get('ccrr_create_time_start')]);
        $endTime = yii::$app->request->get('ccrr_create_time_end');
        if($endTime){
            $query->andFilterWhere(['<=', 'ccrr_create_time', $endTime.' 23:59:59']);
        }
        $query->andFilterWhere(['write_status'=>yii::$app->request->get('write_status')]);
        //查询条件结束
        $data = $query->asArray()->all();
        //print_r($data);exit;

        $excel = new Excel();
        $excel->setHeader([
            'creator'=>'皓峰通讯',
            'lastModifiedBy'=>'hao feng tong xun',
            'title'=>'charge_card_recharge_record',
            'subject'=>'charge_card_recharge_record',
            'description'=>'charge_card_recharge_record',
            'keywords'=>'charge_card_recharge_record',
            'category'=>'charge_card_recharge_record'
        ]);

        //---向excel添加表头-------------------------------------
        $excel->addLineToExcel($excHeaders);

        //---向excel添加具体数据----------------------------------
        $configItems = ['cc_type'];
        $configs = (new ConfigCategory())->getCategoryConfig($configItems,'value');
        foreach($data as $item){
            $lineData = [];
            // 各combox配置项以txt代替val
            $item['cc_type'] = $configs['cc_type'][$item['cc_type']]['text'];
            $item['write_status'] = $item['write_status'] == 'success' ? '成功' : '失败';
            $item['cc_code'] = ' '.$item['cc_code'];
            foreach($item as $k=>$v) {
                $lineData[] = ['content'=>$v];
            }
            $excel->addLineToExcel($lineData);
        }
        unset($data);
        $objPHPExcel = $excel->getPHPExcel();
        header("Content-type: application/octet-stream");
        header("Accept-Ranges: bytes");
        //header("Accept-Length:".$fileSize);
        header("Content-Disposition: attachment; filename=".iconv('utf-8','gbk','IC电卡充值记录.xls'));
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }




}