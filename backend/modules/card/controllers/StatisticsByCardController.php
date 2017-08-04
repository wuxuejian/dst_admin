<?php
/**
 * @Desc:   电卡充值-按卡号统计 控制器
 * @date:   2016-03-22
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

class StatisticsByCardController extends BaseController{
    /**
     * 访问【按卡号统计】菜单视图
     */
    public function actionIndex()
    {
        $configItems = ['cc_type','cc_status'];
        $data['config'] = (new ConfigCategory())->getCategoryConfig($configItems,'value');
        $data['buttons'] = $this->getCurrentActionBtn();
        return $this->render('index',$data);
    }

    /**
     * 获取按卡号统计充值记录列表
     */
    public function actionGetList()
    {
        $query = ChargeCard::find()
            ->select([
                'cc_id','cc_code','cc_type','cc_status','cc_current_money',
                'recharge_num'=>'COUNT(ccrr_card_id)',
                'recharge_money'=>'SUM(ccrr_recharge_money)',
                'incentive_money'=>'SUM(ccrr_incentive_money)',
                'holder_code'=>'{{%vip.code}}',
                'holder_name'=>'{{%vip.client}}',
                'holder_mobile'=>'{{%vip.mobile}}'
            ])
            ->joinWith('chargeCardRechargeRecord', false, 'LEFT JOIN')
            ->joinWith('vip', false, 'LEFT JOIN')
            ->where('cc_is_del=0 AND (ccrr_is_del=0 OR ccrr_is_del IS NULL)');
        //查询条件
        $query->andFilterWhere(['like', 'cc_code', yii::$app->request->get('cc_code')]);
        $query->andFilterWhere(['=', 'cc_type', yii::$app->request->get('cc_type')]);
        $query->andFilterWhere(['=', 'cc_status', yii::$app->request->get('cc_status')]);
        $query->andFilterWhere(['like', '{{%vip.code}}', yii::$app->request->get('holder_code')]);
        $query->andFilterWhere(['like', '{{%vip.client}}', yii::$app->request->get('holder_name')]);
        $query->andFilterWhere(['like', '{{%vip.mobile}}', yii::$app->request->get('holder_mobile')]);
        $query2 = clone $query; // 底部合计用
        $total = $query->groupBy('ccrr_card_id')->count();
        //分页
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount' => $total, 'pageSize' => $pageSize]);
        //排序
        if (yii::$app->request->get('sort')) {
            $field = yii::$app->request->get('sort');        //field
            $direction = yii::$app->request->get('order');  //asc or desc
            $orderStr = $field . ' ' . $direction;
        } else {
            $orderStr = 'cc_id DESC';
        }
        $data = $query->offset($pages->offset)->limit($pages->limit)->orderBy($orderStr)->asArray()->all();
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        // 表格底部增加合计行
        $bottomSum = $query2->select([
            'cc_ids'=>'GROUP_CONCAT(DISTINCT cc_id)',
            'recharge_num'=>'COUNT(ccrr_card_id)',
            'recharge_money'=>'SUM(ccrr_recharge_money)',
            'incentive_money'=>'SUM(ccrr_incentive_money)',
        ])->asArray()->one();
        $bottomSum2 = ChargeCard::find()
            ->select(['cc_current_money'=>'SUM(cc_current_money)'])
            ->where(['cc_id'=>explode(',',$bottomSum['cc_ids'])])
            ->asArray()->one();
        $returnArr['footer'] = [[
            'cc_code'=>'合计：',
            'cc_current_money'=>$bottomSum2['cc_current_money'],
            'recharge_num'=>$bottomSum['recharge_num'],
            'recharge_money'=>$bottomSum['recharge_money'],
            'incentive_money'=>$bottomSum['incentive_money']
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
            ['content'=>'电卡编号','font-weight'=>true,'width'=>'15'],
            ['content'=>'电卡类型','font-weight'=>true,'width'=>'15'],
            ['content'=>'电卡状态','font-weight'=>true,'width'=>'15'],
            ['content'=>'充值次数(次)','font-weight'=>true,'width'=>'15'],
            ['content'=>'充值金额(元)','font-weight'=>true,'width'=>'15'],
            ['content'=>'奖励金额(元)','font-weight'=>true,'width'=>'15'],
            ['content'=>'当前余额(元)','font-weight'=>true,'width'=>'15'],
            ['content'=>'会员编号','font-weight'=>true,'width'=>'25'],
            ['content'=>'会员手机','font-weight'=>true,'width'=>'15'],
            ['content'=>'会员名称','font-weight'=>true,'width'=>'15']
        ];

        // 要查的字段，与导出的excel表头对应
        $selectArr = [
            'cc_code',
            'cc_type',
            'cc_status',
            'recharge_num'=>'COUNT(ccrr_card_id)',
            'recharge_money'=>'SUM(ccrr_recharge_money)',
            'incentive_money'=>'SUM(ccrr_incentive_money)',
            'cc_current_money',
            '{{%vip.code}}',
            '{{%vip.mobile}}',
            '{{%vip.client}}'
        ];

        $query = ChargeCard::find()
            ->select($selectArr)
            ->joinWith('chargeCardRechargeRecord', false, 'LEFT JOIN')
            ->joinWith('vip', false, 'LEFT JOIN')
            ->where('cc_is_del=0 AND (ccrr_is_del=0 OR ccrr_is_del IS NULL)')
            ->groupBy('ccrr_card_id');
        //查询条件
        $query->andFilterWhere(['like', 'cc_code', yii::$app->request->get('cc_code')]);
        $query->andFilterWhere(['=', 'cc_type', yii::$app->request->get('cc_type')]);
        $query->andFilterWhere(['=', 'cc_status', yii::$app->request->get('cc_status')]);
        $query->andFilterWhere(['like', '{{%vip.code}}', yii::$app->request->get('holder_code')]);
        $query->andFilterWhere(['like', '{{%vip.client}}', yii::$app->request->get('holder_name')]);
        $query->andFilterWhere(['like', '{{%vip.mobile}}', yii::$app->request->get('holder_mobile')]);
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
        //---向excel添加表头---------
        $excel->addLineToExcel($excHeaders);
        //---向excel添加具体数据------
        $configItems = ['cc_type','cc_status'];
        $configs = (new ConfigCategory())->getCategoryConfig($configItems,'value');
        foreach($data as $item){
            $lineData = [];
            $item['cc_code'] = ' '.$item['cc_code'];
            $item['recharge_money'] = $item['recharge_money'] > 0 ? $item['recharge_money'] : 0;
            $item['recharge_money'] = $item['recharge_money'] > 0 ? $item['recharge_money'] : 0;
            $item['incentive_money'] = $item['incentive_money'] > 0 ? $item['incentive_money'] : 0;
            // 各combox配置项以txt代替val
            $item['cc_type'] = $configs['cc_type'][$item['cc_type']]['text'];
            $item['cc_status'] = $configs['cc_status'][$item['cc_status']]['text'];
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
        header("Content-Disposition: attachment; filename=".iconv('utf-8','gbk','按卡号统计充值记录导出.xls'));
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }




}