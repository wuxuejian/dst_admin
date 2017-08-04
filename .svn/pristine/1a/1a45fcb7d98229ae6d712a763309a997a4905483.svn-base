<?php
/**
 * 充值统计报表 控制器
 */
namespace backend\modules\charge\controllers;
use backend\models\VipRechargeRecord;
use yii;
use yii\data\Pagination;
use backend\controllers\BaseController;
use backend\models\ConfigCategory;
use backend\models\ReportRechargeStatisticsApp;
use backend\models\ReportRechargeStatisticsCard;
use backend\models\ChargeCard;
use backend\models\ChargeCardRechargeRecord;
use backend\models\Vip;
use common\models\Excel;

class RechargeStatisticsController extends BaseController
{
    
    public function actionIndex(){
        //获取最近12月（包括本月）的充值充次情况
        $twelveMonthsData = $this->getRechargeMoneyTimesOfLatestTwelveMonths();
        //获取各类电卡（包括App）的发卡数量、充值金额、充值人次、当前结余概况
        $overviewData = $this->getOverviewOfDifferentCards();
        $buttons = $this->getCurrentActionBtn();
        return $this->render('index',[
            'twelveMonthsData'=>$twelveMonthsData,
            'overviewData'=>$overviewData,
            'buttons'=>$buttons
        ]);
    }

    /**
     * 获取充值充次统计列表
     */
    public function actionGetList(){
        $data = [
            ['colSubTitle'=>'昨日'],
            ['colSubTitle'=>'上周'],
            ['colSubTitle'=>'上月'],
            ['colSubTitle'=>'历史累计']
        ];
        //---（1）获取各时段的“APP”和“电卡”充值充次情况------
        $typeArr = ['app','card'];
        //定义统计时段
        $timeArr = [
            'yesterday'=>date('Y-m-d',strtotime('-1 day')),
            'lastWeek'=>[
                date("Y-m-d",mktime(0,0,0,date("m"),date("d")-date("w")+1-7,date("Y"))),
                date("Y-m-d",mktime(23,59,59,date("m"),date("d")-date("w")+7-7,date("Y")))
            ],
            'lastMonth'=>date('Y-m',strtotime('-1 month'))
        ];
        foreach($typeArr as $type){
            $rechargeMoneyTimes = $this->getRechargeMoneyTimesByPeriod($timeArr,$type);
            $moneyTimesArr = array_values($rechargeMoneyTimes);
            $i = 0;
            foreach($data as &$row){
                $money = 0.00; $times = 0;
                if($moneyTimesArr[$i]){
                    list($money,$times) = explode('|',$moneyTimesArr[$i]);
                }
                if($type == 'app'){
                    $row['recharge_money_app'] = number_format($money,2,'.','');
                    $row['recharge_times_app'] = $times;
                }elseif($type == 'card'){
                    $row['recharge_money_card'] = number_format($money,2,'.','');
                    $row['recharge_times_card'] = $times;
                }
                $i++;
            }
        }
        //---（2）各行合计：“APP”+“电卡”充值充次情况------
        foreach($data as &$row){
            $money = $row['recharge_money_app'] + $row['recharge_money_card'];
            $row['recharge_money_total'] = number_format($money,2,'.','');
            $times = $row['recharge_times_app'] + $row['recharge_times_card'];
            $row['recharge_times_total'] = $times;
        }
        return json_encode(['rows'=>$data,'total'=>count($data)]);
    }


    /*
     * 按各时段获取“APP”或“电卡”的充值充次情况
     * @$timeArr: 各统计时段
     * @$type: 区分“APP”或“电卡”
     */
    protected function getRechargeMoneyTimesByPeriod($timeArr,$type){
        $returnArr = [
            'yesterday'=>'',
            'lastWeek'=>'',
            'lastMonth'=>'',
            'historyTotal'=>''
        ];
        foreach($timeArr as $periodType=>$timeValue){
            switch($periodType){
                case 'yesterday':
                    if($type == 'app'){
                        $query = ReportRechargeStatisticsApp::find();
                    }elseif($type == 'card'){
                        $query = ReportRechargeStatisticsCard::find();
                    }
                    $data = $query->select(['m'=>'day_' . substr($timeValue,-2)])
                        ->where(['year_month'=>substr($timeValue,0,7)])
                        ->asArray()->one();
                    if($data){
                        $returnArr[$periodType] = $data['m'];
                    }
                    break;
                case 'lastWeek':
                    $dayStart = $timeValue[0];
                    $dayEnd   = $timeValue[1];
                    $days = ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31'];
                    //判断上周的开始日期和结束日期是否属于同一个月份？
                    $monthStart = substr($dayStart,0,7);
                    $monthEnd   = substr($dayEnd,0,7);
                    if($monthStart == $monthEnd){
                        $weekStartIndex = array_search( substr($dayStart,-2), $days );
                        $weekDays = array_slice($days, $weekStartIndex, 7);
                        $tmpArr = [];
                        foreach($weekDays as $day){
                            $tmpArr[] = "`day_{$day}`";
                        }
                        $str = join(',',$tmpArr);
                        if($type == 'app'){
                            $query = ReportRechargeStatisticsApp::find();
                        }elseif($type == 'card'){
                            $query = ReportRechargeStatisticsCard::find();
                        }
                        $data = $query->select($str)
                            ->where(['year_month'=>$monthStart])
                            ->asArray()->one();
                        if($data){
                            $money = 0; $times = 0;
                            foreach($data as $item){
                                if($item){
                                    list($m,$t) = explode('|',$item);
                                    $money += $m;
                                    $times += $t;
                                }
                            }
                            if($money && $times){
                                $returnArr[$periodType] = number_format($money,2,'.','').'|'.$times;
                            }
                        }
                    }else{
                        $weekStartIndex = array_search( substr($dayStart,-2), $days );
                        $weekDays_part1 = array_slice($days, $weekStartIndex); //不论大小月份都将截取到末尾31日
                        $tmpArr = [];
                        foreach($weekDays_part1 as $day){
                            $tmpArr[$monthStart][] = "`day_{$day}`";
                        }
                        $weekEndIndex = array_search( substr($dayEnd,-2), $days );
                        $weekDays_part2 = array_slice( $days, 0, ($weekEndIndex + 1) );
                        foreach($weekDays_part2 as $day){
                            $tmpArr[$monthEnd][] = "`day_{$day}`";
                        }
                        $money = 0; $times = 0;
                        foreach($tmpArr as $key=>$val){
                            $str = join(',',$val);
                            if($type == 'app'){
                                $query = ReportRechargeStatisticsApp::find();
                            }elseif($type == 'card'){
                                $query = ReportRechargeStatisticsCard::find();
                            }
                            $data = $query->select($str)
                                ->where(['year_month'=>$key])
                                ->asArray()->one();
                            if($data){
                                foreach($data as $item){
                                    if($item){
                                        list($m,$t) = explode('|',$item);
                                        $money += $m;
                                        $times += $t;
                                    }
                                }
                            }
                        }
                        if($money && $times){
                            $returnArr[$periodType] = number_format($money,2,'.','').'|'.$times;
                        }
                    }
                    break;
                case 'lastMonth':
                    if($type == 'app'){
                        $query = ReportRechargeStatisticsApp::find();
                    }elseif($type == 'card'){
                        $query = ReportRechargeStatisticsCard::find();
                    }
                    $data = $query->select(['m'=>'month_total'])
                        ->where(['year_month'=>$timeValue])
                        ->asArray()->one();
                    if($data){
                        $returnArr[$periodType] = $data['m'];
                    }
                    break;
            }
        }
        //历史累计
        if($type == 'app'){
            $query = ReportRechargeStatisticsApp::find();
        }elseif($type == 'card'){
            $query = ReportRechargeStatisticsCard::find();
        }
        $data = $query->select(['month_total'])->asArray()->all();
        if($data){
            $money = 0; $times = 0;
            foreach($data as $item){
                foreach($item as $val){
                    if($val){
                        list($m,$t) = explode('|',$val);
                        $money += $m;
                        $times += $t;
                    }
                }
            }
            $returnArr['historyTotal'] = number_format($money,2,'.','').'|'.$times;
        }
        return $returnArr;
    }


    /*
     * 获取最近12个月（包括本月）的充值充次情况
     */
    protected function getRechargeMoneyTimesOfLatestTwelveMonths(){
        $twelveMonths = [];
        $i = 11;
        while($i >= 0){
            $twelveMonths[] = date('Y-m',strtotime("-{$i} months"));
            $i--;
        }
        $returnArr = [];
        foreach($twelveMonths as $ym){
            $returnArr[] = ['year_month'=>$ym,'rechargeMoney'=>0,'rechargeTimes'=>0];
        }
        //---查询最近12个月“APP”和“电卡”充值充次数据-----------
        $typeArr = ['app','card'];
        foreach ($typeArr as $type) {
            if($type == 'app'){
                $query = ReportRechargeStatisticsApp::find();
            }elseif($type == 'card'){
                $query = ReportRechargeStatisticsCard::find();
            }
            $data = $query->select(['year_month','month_total'])
                ->where(['`year_month`'=>$twelveMonths])
                ->orderBy('year_month ASC')
                ->asArray()->all();
            if($data){
                foreach($returnArr as &$item){
                    foreach($data as $row){
                        if($row['year_month'] == $item['year_month'] && $row['month_total']){
                            list($m,$t) = explode('|',$row['month_total']);
                            $item['rechargeMoney'] += number_format($m,2,'.','');
                            $item['rechargeTimes'] += $t;
                            break;
                        }
                    }
                }
                unset($data);
            }
        }
        return $returnArr;
    }


    /*
     * 获取各类电卡（包括App）的发卡数量、充值金额、充值人次、当前结余概况
     * 注：卡类有APP用户、普通用户卡、站点管理员卡、协议充值卡、客户自用卡。
     */
    protected function getOverviewOfDifferentCards(){
        $returnArr = [
            'saleAmount'   =>['APP_USER'=>0, 'COMMON'=>0, 'STATION_MANAGER'=>0, 'PROTOCOL'=>0, 'CUSTOMER_SELF'=>0],
            'rechargeMoney'=>['APP_USER'=>0, 'COMMON'=>0, 'STATION_MANAGER'=>0, 'PROTOCOL'=>0, 'CUSTOMER_SELF'=>0],
            'rechargeTimes'=>['APP_USER'=>0, 'COMMON'=>0, 'STATION_MANAGER'=>0, 'PROTOCOL'=>0, 'CUSTOMER_SELF'=>0],
            'currentMoney' =>['APP_USER'=>0, 'COMMON'=>0, 'STATION_MANAGER'=>0, 'PROTOCOL'=>0, 'CUSTOMER_SELF'=>0]
        ];
        //---APP用户------------------------
        //查APP用户数量、当前结余
        $rec = Vip::find()
            ->select(['total'=>'COUNT(id)','total2'=>'SUM(money_acount)'])
            ->where('`is_del` = 0 AND `code` != ""')
            ->andWhere(['<=','systime',strtotime(date('Y-m-d 23:59:59',strtotime('-1 day')))]) //只统计到昨天为止
            ->asArray()->one();
        if($rec){
            $returnArr['saleAmount']['APP_USER'] = $rec['total'];
            $returnArr['currentMoney']['APP_USER'] = $rec['total2'];
            unset($rec);
        }
        //查APP用户充值金额、充值人次
        $rec = VipRechargeRecord::find()
            ->select(['total'=>'SUM(total_fee)','total2'=>'COUNT(id)'])
            ->where(['trade_status'=>'success'])
            ->andWhere(['<=','gmt_payment_datetime',strtotime(date('Y-m-d 23:59:59',strtotime('-1 day')))]) //只统计到昨天为止
            ->asArray()->one();
        if($rec){
            $returnArr['rechargeMoney']['APP_USER'] = $rec['total'];
            $returnArr['rechargeTimes']['APP_USER'] = $rec['total2'];
            unset($rec);
        }
        //---其他各类电卡------------------------
        //查其他各类电卡发卡数量、当前结余
        $records = ChargeCard::find()
            ->select(['cc_type','total'=>'COUNT(cc_type)','total2'=>'SUM(cc_current_money)'])
            ->where(['cc_is_del'=>0,'cc_status'=>'ACTIVATED'])
            ->andWhere(['<=','cc_create_time',date('Y-m-d 23:59:59',strtotime('-1 day'))]) //只统计到昨天为止
            ->groupBy('cc_type')
            ->asArray()->all();
        if($records){
            foreach($records as $row){
                if(isset($returnArr['saleAmount'][$row['cc_type']])){
                    $returnArr['saleAmount'][$row['cc_type']] = $row['total'];
                }
                if(isset($returnArr['currentMoney'][$row['cc_type']])){
                    $returnArr['currentMoney'][$row['cc_type']] = $row['total2'];
                }
            }
            unset($records);
        }
        //查其他各类电卡充值金额、充值人次
        $records = ChargeCardRechargeRecord::find()
            ->select(['cc_type','total'=>'SUM(ccrr_recharge_money)','total2'=>'COUNT(ccrr_id)'])
            ->joinWith('chargeCard',false)
            ->where(['ccrr_is_del'=>0,'write_status'=>'success'])
            ->andWhere(['<=','ccrr_create_time',date('Y-m-d 23:59:59',strtotime('-1 day'))]) //只统计到昨天为止
            ->groupBy('cc_type')
            ->asArray()->all();
        if($records){
            foreach($records as $row){
                if(isset($returnArr['rechargeMoney'][$row['cc_type']])){
                    $returnArr['rechargeMoney'][$row['cc_type']] = $row['total'];
                }
                if(isset($returnArr['rechargeTimes'][$row['cc_type']])){
                    $returnArr['rechargeTimes'][$row['cc_type']] = $row['total2'];
                }
            }
            unset($records);
        }
        return $returnArr;
    }


    /*
     * 获取按电卡类型统计充值列表
     * 注：卡类有APP用户、普通用户卡、站点管理员卡、协议充值卡、客户自用卡。
     */
    public function  actionGetListByCardType(){
        $tmpArr = [
            'APP_USER'       => ['cardType'=>'APP用户','rechargeMoney'=>0,'rechargeTimes'=>0],
            'COMMON'         => ['cardType'=>'普通用户卡','rechargeMoney'=>0,'rechargeTimes'=>0],
            'STATION_MANAGER'=> ['cardType'=>'站点管理员卡','rechargeMoney'=>0,'rechargeTimes'=>0],
            'PROTOCOL'       => ['cardType'=>'协议充值卡','rechargeMoney'=>0,'rechargeTimes'=>0],
            'CUSTOMER_SELF'  => ['cardType'=>'客户自用卡','rechargeMoney'=>0,'rechargeTimes'=>0]
        ];
        //开始查询数据
        //获取要查询的时间段，若没有指定则默认会统计到昨天为止
        $searchTime_start = ''; $searchTime_end = '';
        if(yii::$app->request->get('searchTime_start') || yii::$app->request->get('searchTime_end')){
            if(yii::$app->request->get('searchTime_start')){
                $searchTime_start = yii::$app->request->get('searchTime_start').' 00:00:00';
            }
            if(yii::$app->request->get('searchTime_end')){
                $searchTime_end   = yii::$app->request->get('searchTime_end').' 23:59:59';
            }
        }elseif(yii::$app->request->get('choose_period')){
            $choose_period = yii::$app->request->get('choose_period');
            $arr = $this->getTimeBucket($choose_period);
            if($arr){
                $searchTime_start = $arr['timeFrom'];
                $searchTime_end   = $arr['timeTo'];
            }
        }
        //---（1）查APP用户充值金额、充值人次--------------------------------
        $query = VipRechargeRecord::find()
            ->select(['total'=>'SUM(total_fee)','total2'=>'COUNT(id)'])
            ->where(['trade_status'=>'success']);
        if($searchTime_start || $searchTime_end){
            if($searchTime_start){
                $query->andWhere(['>=','gmt_payment_datetime',strtotime($searchTime_start)]);
            }
            if($searchTime_end){
                $query->andWhere(['<=','gmt_payment_datetime',strtotime($searchTime_end)]);
            }
        }else{
            $query->andWhere(['<=','gmt_payment_datetime',strtotime(date('Y-m-d 23:59:59',strtotime('-1 day')))]);
        }
        $rec = $query->asArray()->one();
        if($rec){
            $tmpArr['APP_USER']['rechargeMoney'] = number_format($rec['total'],2,'.','');
            $tmpArr['APP_USER']['rechargeTimes'] = $rec['total2'];
            unset($rec);
        }
        //---（2）查其他各类电卡充值金额、充值人次------------------------------
        $query2 = ChargeCardRechargeRecord::find()
            ->select(['cc_type','total'=>'SUM(ccrr_recharge_money)','total2'=>'COUNT(ccrr_id)'])
            ->joinWith('chargeCard',false)
            ->where(['ccrr_is_del'=>0,'write_status'=>'success'])
            ->groupBy('cc_type');
        if($searchTime_start || $searchTime_end){
            if($searchTime_start){
                $query2->andWhere(['>=','ccrr_create_time',$searchTime_start]);
            }
            if($searchTime_end){
                $query2->andWhere(['<=','ccrr_create_time',$searchTime_end]);
            }
        }else{
            $query2->andWhere(['<=','ccrr_create_time',date('Y-m-d 23:59:59',strtotime('-1 day'))]);
        }
        $records = $query2->asArray()->all();
        if($records){
            foreach($records as $row){
                if(isset($tmpArr[$row['cc_type']]['rechargeMoney'])){
                    $tmpArr[$row['cc_type']]['rechargeMoney'] = number_format($row['total'],2,'.','');
                    $tmpArr[$row['cc_type']]['rechargeTimes'] = $row['total2'];
                }
            }
            unset($records);
        }
        //---（3）底部合计行 -----------------------------------------
        $sumRechargeMoney = 0; $sumRechargeTimes = 0;
        foreach($tmpArr as $item){
            $sumRechargeMoney += $item['rechargeMoney'];
            $sumRechargeTimes += $item['rechargeTimes'];
        }
        $tmpArr['sumTotal'] = ['cardType'=>'合计','rechargeMoney'=>number_format($sumRechargeMoney,2,'.',''),'rechargeTimes'=>$sumRechargeTimes];
        return json_encode([
            'rows' => array_values($tmpArr),
            'total'=> count($tmpArr)
        ]);
    }


    /**
     * 功能: 获取开始到结束的时间段
     * 参数: 今天,昨天,本周,上一周,本月,上一月,年本度,上一年
     */
    public static function getTimeBucket($time){
        $arr = [];
        switch($time){
            case 'today':
                $arr['timeFrom'] = date('Y-m-d H:i:s',mktime(0,0,0,date("m"),date("d"),date("Y")));
                $arr['timeTo']   = date('Y-m-d H:i:s',mktime(23,59,59,date("m"),date("d"),date("Y")));
                break;
            case 'yesterday':
                $arr['timeFrom'] = date("Y-m-d H:i:s",mktime(0,0,0,date("m"),date("d")-1,date("Y")));
                $arr['timeTo']   = date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("d")-1,date("Y")));
                break;
            case 'thisWeek':
                $arr['timeFrom'] = date("Y-m-d H:i:s",mktime(0,0,0,date("m"),date("d")-date("w")+1,date("Y")));
                //$arr['timeTo']   = date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("d")-date("w")+7,date("Y")));
                $arr['timeTo']   = date('Y-m-d 23:59:59',strtotime('-1 day')); //这里改为只统计到昨天为止
                break;
            case 'lastWeek':
                $arr['timeFrom'] = date("Y-m-d H:i:s",mktime(0,0,0,date("m"),date("d")-date("w")+1-7,date("Y")));
                $arr['timeTo']   = date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("d")-date("w")+7-7,date("Y")));
                break;
            case 'thisMonth':
                $arr['timeFrom'] = date("Y-m-d H:i:s",mktime(0,0,0,date("m"),1,date("Y")));
                //$arr['timeTo']   = date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("t"),date("Y")));
                $arr['timeTo']   = date('Y-m-d 23:59:59',strtotime('-1 day')); //这里改为只统计到昨天为止
                break;
            case 'lastMonth':
                $arr['timeFrom'] = date("Y-m-d H:i:s",mktime(0,0,0,date("m")-1,1,date("Y")));
                $arr['timeTo']   = date("Y-m-d H:i:s",mktime(23,59,59,date("m") ,0,date("Y")));
                break;
            case 'thisYear':
                $arr['timeFrom'] = date('Y-m-d H:i:s', mktime(0,0,0,1,1,date('Y')));
                //$arr['timeTo']   = date('Y-m-d H:i:s', mktime(23,59,59,12,31,date('Y')));
                $arr['timeTo']   = date('Y-m-d 23:59:59',strtotime('-1 day')); //这里改为只统计到昨天为止
                break;
            case 'lastYear':
                $arr['timeFrom'] = date('Y-m-d H:i:s', mktime(0,0,0,1,1,date('Y')-1));
                $arr['timeTo']   = date('Y-m-d H:i:s', mktime(23,59,59,12,31,date('Y')-1));
                break;
        }
        return $arr;
    }

    /*
     * 导出Excel
     */
    public function actionExportGridData(){
        $excel = new Excel();
        $excel->setHeader([
            'creator'=>'皓峰通讯',
            'lastModifiedBy'=>'hao feng tong xun',
            'title'=>'charge_card',
            'subject'=>'charge_card',
            'description'=>'charge_card',
            'keywords'=>'charge_card',
            'category'=>'charge_card'
        ]);
        //---向excel添加构建的excel表头-----------------
        $excHeaders = [
            ['content'=>'电卡类型','font-weight'=>true,'width'=>'15'],
            ['content'=>'充值金额（元）','font-weight'=>true,'width'=>'25'],
            ['content'=>'充值人次（次）','font-weight'=>true,'width'=>'25']
        ];
        $excel->addLineToExcel($excHeaders);

        // 获取数据。注意这里传递的是json字符串格式
        $data = yii::$app->request->get('dataStr');
        $data = json_decode($data,true);
        if($data){
            //---向excel添加具体数据----------
            foreach($data as $item){
                $lineData = [];
                foreach($item as $k=>$v) {
                    $lineData[] = ['content'=>$v];
                }
                $excel->addLineToExcel($lineData);
            }
            unset($data);
        }

        $objPHPExcel = $excel->getPHPExcel();
        header("Content-type: application/octet-stream");
        header("Accept-Ranges: bytes");
        //header("Accept-Length:".$fileSize);
        header("Content-Disposition: attachment; filename=".iconv('utf-8','gbk','按电卡类型统计充值列表导出.xls'));
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }


}