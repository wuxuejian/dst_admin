<?php
/**
 * 充电统计报表 控制器
 */
namespace backend\modules\charge\controllers;
use yii;
use yii\data\Pagination;
use backend\controllers\BaseController;
use backend\models\ConfigCategory;
use backend\models\ReportChargeStatisticsApp;
use backend\models\ReportChargeStatisticsCard;
use backend\models\ChargeStation;
use backend\models\ChargeSpots;
use backend\models\ReportPoleChargeStatisticsApp;
use backend\models\ReportPoleChargeStatisticsCard;
use common\models\Excel;

class ChargeStatisticsController extends BaseController
{
    
    public function actionIndex(){
        //获取最近12月（包括本月）的充电量/充值金额/充值人次情况
        $twelveMonthsData = $this->getChargeKwhMoneyTimesOfLatestTwelveMonths();
        $config = (new ConfigCategory())->getCategoryConfig(['cs_type','charge_type'],'value');
        $buttons = $this->getCurrentActionBtn();
        return $this->render('index',[
            'twelveMonthsData'=>$twelveMonthsData,
            'config'=>$config,
            'buttons'=>$buttons
        ]);
    }

    /**
     * 获取充电量/充值金额/充值人次统计列表
     */
    public function actionGetList(){
        $data = [
            ['colSubTitle'=>'昨日'],
            ['colSubTitle'=>'上周'],
            ['colSubTitle'=>'上月'],
            ['colSubTitle'=>'历史累计']
        ];
        //---（1）获取各时段的“APP”和“电卡”充电量/充值金额/充值人次情况------
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
            $kwhMoneyTimes = $this->getChargeKwhMoneyTimesByPeriod($timeArr,$type);
            $kwhMoneyTimesArr = array_values($kwhMoneyTimes);
            $i = 0;
            foreach($data as &$row){
                $kwh = 0.00; $money = 0.00; $times = 0;
                if($kwhMoneyTimesArr[$i]){
                    list($kwh,$money,$times) = explode('|',$kwhMoneyTimesArr[$i]);
                }
                if($type == 'app'){
                    $row['charge_kwh_app']   = number_format($kwh,2,'.','');
                    $row['charge_money_app'] = number_format($money,2,'.','');
                    $row['charge_times_app'] = $times;
                }elseif($type == 'card'){
                    $row['charge_kwh_card']   = number_format($kwh,2,'.','');
                    $row['charge_money_card'] = number_format($money,2,'.','');
                    $row['charge_times_card'] = $times;
                }
                $i++;
            }
        }
        //---（2）各行合计：“APP”+“电卡”充电量/充值金额/充值人次情况------
        foreach($data as &$row){
            $kwh = $row['charge_kwh_app'] + $row['charge_kwh_card'];
            $row['charge_kwh_total'] = number_format($kwh,2,'.','');
            $money = $row['charge_money_app'] + $row['charge_money_card'];
            $row['charge_money_total'] = number_format($money,2,'.','');
            $times = $row['charge_times_app'] + $row['charge_times_card'];
            $row['charge_times_total'] = $times;
        }
        return json_encode(['rows'=>$data,'total'=>count($data)]);
    }


    /*
     * 按各时段获取“APP”或“电卡”的充电量/充值金额/充值人次情况
     * @$timeArr: 各统计时段
     * @$type: 区分“APP”或“电卡”
     */
    protected function getChargeKwhMoneyTimesByPeriod($timeArr,$type){
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
                        $query = ReportChargeStatisticsApp::find();
                    }elseif($type == 'card'){
                        $query = ReportChargeStatisticsCard::find();
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
                            $query = ReportChargeStatisticsApp::find();
                        }elseif($type == 'card'){
                            $query = ReportChargeStatisticsCard::find();
                        }
                        $data = $query->select($str)
                            ->where(['year_month'=>$monthStart])
                            ->asArray()->one();
                        if($data){
                            $kwh = 0; $money = 0; $times = 0;
                            foreach($data as $item){
                                if($item){
                                    list($k,$m,$t) = explode('|',$item);
                                    $kwh   += $k;
                                    $money += $m;
                                    $times += $t;
                                }
                            }
                            if($money && $times){
                                $returnArr[$periodType] = number_format($kwh,2,'.','') . '|' . number_format($money,2,'.','') . '|' . $times;
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
                        $kwh = 0; $money = 0; $times = 0;
                        foreach($tmpArr as $key=>$val){
                            $str = join(',',$val);
                            if($type == 'app'){
                                $query = ReportChargeStatisticsApp::find();
                            }elseif($type == 'card'){
                                $query = ReportChargeStatisticsCard::find();
                            }
                            $data = $query->select($str)
                                ->where(['year_month'=>$key])
                                ->asArray()->one();
                            if($data){
                                foreach($data as $item){
                                    if($item){
                                        list($k,$m,$t) = explode('|',$item);
                                        $kwh   += $k;
                                        $money += $m;
                                        $times += $t;
                                    }
                                }
                            }
                        }
                        if($money && $times){
                            $returnArr[$periodType] = number_format($kwh,2,'.','') . '|' . number_format($money,2,'.','') . '|' . $times;
                        }
                    }
                    break;
                case 'lastMonth':
                    if($type == 'app'){
                        $query = ReportChargeStatisticsApp::find();
                    }elseif($type == 'card'){
                        $query = ReportChargeStatisticsCard::find();
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
            $query = ReportChargeStatisticsApp::find();
        }elseif($type == 'card'){
            $query = ReportChargeStatisticsCard::find();
        }
        $data = $query->select(['month_total'])->asArray()->all();
        if($data){
            $kwh = 0; $money = 0; $times = 0;
            foreach($data as $item){
                foreach($item as $val){
                    if($val){
                        list($k,$m,$t) = explode('|',$val);
                        $kwh   += $k;
                        $money += $m;
                        $times += $t;
                    }
                }
            }
            $returnArr['historyTotal'] = number_format($kwh,2,'.','') . '|' . number_format($money,2,'.','') . '|' . $times;
        }
        return $returnArr;
    }


    /*
     * 获取最近12个月（包括本月）的充电量/充值金额/充值人次情况
     */
    protected function getChargeKwhMoneyTimesOfLatestTwelveMonths(){
        $twelveMonths = [];
        $i = 11;
        while($i >= 0){
            $twelveMonths[] = date('Y-m',strtotime("-{$i} months"));
            $i--;
        }
        $returnArr = [];
        foreach($twelveMonths as $ym){
            $returnArr[] = ['year_month'=>$ym,'chargeKwh'=>0,'chargeMoney'=>0,'chargeTimes'=>0];
        }
        //---查询最近12个月“APP”和“电卡”充电量/充值金额/充值人次数据-----------
        $typeArr = ['app','card'];
        foreach ($typeArr as $type) {
            if($type == 'app'){
                $query = ReportChargeStatisticsApp::find();
            }elseif($type == 'card'){
                $query = ReportChargeStatisticsCard::find();
            }
            $data = $query->select(['year_month','month_total'])
                ->where(['`year_month`'=>$twelveMonths])
                ->orderBy('year_month ASC')
                ->asArray()->all();
            if($data){
                foreach($returnArr as &$item){
                    foreach($data as $row){
                        if($row['year_month'] == $item['year_month'] && $row['month_total']){
                            list($k,$m,$t) = explode('|',$row['month_total']);
                            $item['chargeKwh']   += number_format($k,2,'.','');
                            $item['chargeMoney'] += number_format($m,2,'.','');
                            $item['chargeTimes'] += $t;
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
     * 获取充电站类型统计概况列表
     * 注：电站类型有自营、客户自用、联营、合作。
     */
    public function  actionGetListByStationType(){
        $tmpArr = [
            'SELF_OPERATION'    => ['stationType'=>'自营',   'stationNum'=>0,'poleNum'=>0,'DC_poleNum'=>0,'AC_poleNum'=>0],
            'CUSTOMER_SELF_USE' => ['stationType'=>'客户自用','stationNum'=>0,'poleNum'=>0,'DC_poleNum'=>0,'AC_poleNum'=>0],
            'JOINT_OPERATION'   => ['stationType'=>'联营',   'stationNum'=>0,'poleNum'=>0,'DC_poleNum'=>0,'AC_poleNum'=>0],
            'COOPERATION'       => ['stationType'=>'合作',   'stationNum'=>0,'poleNum'=>0,'DC_poleNum'=>0,'AC_poleNum'=>0]
        ];
        //---查各类型电站数量--------------
        $records = ChargeStation::find()
            ->select(['cs_type','total'=>'COUNT(cs_type)','csIds'=>'GROUP_CONCAT(`cs_id`)'])
            ->where(['cs_is_del'=>0])
            ->andWhere("cs_status != 'STOPPED'")
            ->andWhere(['<=','cs_create_time',date('Y-m-d 23:59:59',strtotime('-1 day'))]) //只统计到昨天为止
            ->groupBy('cs_type')
            ->asArray()->all();
        if($records){
            foreach($records as $row){
                if(isset($tmpArr[$row['cs_type']])){
                    if(isset($tmpArr[$row['cs_type']]['stationNum'])){
                        $tmpArr[$row['cs_type']]['stationNum'] = $row['total'];
                    }
                    //---查该类型所有电站所拥有的电桩总数、直流桩总数、交流桩总数--------
                    $stationIds = explode(',', $row['csIds']);
                    $chargeTypes = ChargeSpots::find()
                        ->select(['charge_type','num'=>'COUNT(charge_type)'])
                        ->where(['station_id'=>$stationIds, 'is_del'=>0])
                        ->groupBy('charge_type')
                        ->indexBy('charge_type')
                        ->asArray()->all();
                    if($chargeTypes){
                        $poleNum = 0; $DC_poleNum = 0; $AC_poleNum = 0;
                        foreach ($chargeTypes as $type) {
                            $poleNum += $type['num'];
                            //注意：这里统计直流桩总数包含单直流、双直流、交直流；统计交流桩总数包含单交流、双交流、交直流。
                            if(in_array($type['charge_type'],['DC','DC_DC','AC_DC'])){
                                $DC_poleNum += $type['num'];
                            }
                            if(in_array($type['charge_type'],['AC','AC_AC','AC_DC'])){
                                $AC_poleNum += $type['num'];
                            }
                        }
                        if(isset($tmpArr[$row['cs_type']]['poleNum'])){
                            $tmpArr[$row['cs_type']]['poleNum'] = $poleNum;
                        }
                        if(isset($tmpArr[$row['cs_type']]['DC_poleNum'])){
                            $tmpArr[$row['cs_type']]['DC_poleNum'] = $DC_poleNum;
                        }
                        if(isset($tmpArr[$row['cs_type']]['AC_poleNum'])){
                            $tmpArr[$row['cs_type']]['AC_poleNum'] = $AC_poleNum;
                        }
                    }
                }
            }
        }
        //---（3）底部合计行 -----------------------------------------
        $sumStationNum = 0; $sumPoleNum = 0; $sumDC_poleNum = 0; $sumAC_poleNum = 0;
        foreach($tmpArr as $item){
            $sumStationNum += $item['stationNum'];
            $sumPoleNum    += $item['poleNum'];
            $sumDC_poleNum    += $item['DC_poleNum'];
            $sumAC_poleNum    += $item['AC_poleNum'];
        }
        $tmpArr['sumTotal'] = ['stationType'=>'合计','stationNum'=>$sumStationNum,'poleNum'=>$sumPoleNum,'DC_poleNum'=>$sumDC_poleNum,'AC_poleNum'=>$sumAC_poleNum];
        return json_encode([
            'rows' => array_values($tmpArr),
            'total'=> count($tmpArr)
        ]);
    }


    /*
     * 获取按充电桩统计充电列表
     */
    public function  actionGetListByPole(){
        //---（1）查出所有符合查询条件的充电桩-----------------
        $query = ChargeSpots::find()
            ->select([
                '{{%charge_spots}}.id',
                '{{%charge_spots}}.code_from_compony',
                '{{%charge_spots}}.charge_type',
                '{{%charge_spots}}.logic_addr',
                '{{%charge_station}}.cs_name',
                '{{%charge_station}}.cs_type'
            ])
            ->joinWith('chargeStation',false,'LEFT JOIN')
            ->where(['{{%charge_spots}}.is_del'=>0,'{{%charge_station}}.cs_is_del'=>0])
            ->andWhere("{{%charge_station}}.cs_status != 'STOPPED'");
        $query->andFilterWhere(['=','cs_type',yii::$app->request->get('cs_type')]);
        $query->andFilterWhere(['=','station_id',yii::$app->request->get('station_id')]);
        $query->andFilterWhere(['=','charge_type',yii::$app->request->get('charge_type')]);
        if(yii::$app->request->get('charge_pole')){
            $query->andWhere([
                'or',
                ['like','code_from_compony',yii::$app->request->get('charge_pole')],
                ['like','logic_addr',yii::$app->request->get('charge_pole')]
            ]);
        }
        $total = $query->count();
        $totalData = $query->asArray()->all();
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        //排序
        if(yii::$app->request->get('sort')){
            $field = yii::$app->request->get('sort');		//field
            $direction = yii::$app->request->get('order');  //asc or desc
            $orderStr = $field.' '.$direction;
        }else{
            $orderStr = '{{%charge_spots}}.id DESC';
        }
        $data = $query->offset($pages->offset)->limit($pages->limit)->orderBy($orderStr)->asArray()->all();

        //---（2）根据上面查询出的所有充电桩的逻辑地址，查询出对应的APP和IC电卡充电量/充值金额/充值人次统计数据---------------
        $totalDataArr = [];
        $defaultLineArr = [
            'charge_kwh_app'    => number_format(0,2,'.',''),
            'charge_kwh_card'   => number_format(0,2,'.',''),
            'charge_kwh_total'   => number_format(0,2,'.',''),
            'charge_money_app'  => number_format(0,2,'.',''),
            'charge_money_card' => number_format(0,2,'.',''),
            'charge_money_total'   => number_format(0,2,'.',''),
            'charge_times_app'  => 0,
            'charge_times_card' => 0,
            'charge_times_total' => 0
        ];
        $footerSumTotal = $defaultLineArr; //底部合计行
        if($totalData){
            $logicAddr = array_filter( array_column($totalData, 'logic_addr') );
            $typeArr = ['app','card'];
            //获取要查询的时间段，若没有指定则默认会统计到昨天为止
            $searchTime_start = yii::$app->request->get('searchTime_start','');
            $searchTime_end   = yii::$app->request->get('searchTime_end','');
            if($searchTime_start || $searchTime_end){
                if($searchTime_start && $searchTime_end){
                    if($searchTime_start > $searchTime_end){ //若时间段不合法，直接返回
                        return json_encode(['total'=>0,'rows'=>[]]);
                    }
                }else{
                    if($searchTime_start){
                        $searchTime_start = $searchTime_start.' 00:00:00';
                    }
                    if($searchTime_end){
                        $searchTime_end   = $searchTime_end.' 23:59:59';
                    }
                }
            }elseif(yii::$app->request->get('choose_period')){
                $choose_period = yii::$app->request->get('choose_period');
                $arr = $this->getTimeBucket($choose_period);
                if($arr){
                    $searchTime_start = $arr['timeFrom'];
                    $searchTime_end   = $arr['timeTo'];
                }
            }else{
                $searchTime_end   = date('Y-m-d 23:59:59',strtotime('-1 day')); //只统计到昨天为止
            }
            $timeArr = [$searchTime_start,$searchTime_end];
            foreach($typeArr as $type){
                $kwhMoneyTimes = $this->getPoleChargeKwhMoneyTimesByPeriod($timeArr,$type,$logicAddr);
                if($kwhMoneyTimes){
                    foreach($kwhMoneyTimes as $key=>$item){
                        if($key != 'footerSumTotal'){
                            if(!isset($totalDataArr[$key])){
                                $totalDataArr[$key] = $defaultLineArr;
                            }
                            $totalDataArr[$key]['charge_kwh_'.$type]   = number_format($item['kwh'],2,'.','');
                            $totalDataArr[$key]['charge_money_'.$type] = number_format($item['money'],2,'.','');
                            $totalDataArr[$key]['charge_times_'.$type] = $item['times'];
                        }
                    }
                    $footerSumTotal['charge_kwh_'.$type]   = number_format($kwhMoneyTimes['footerSumTotal']['kwh'],2,'.','');
                    $footerSumTotal['charge_money_'.$type] = number_format($kwhMoneyTimes['footerSumTotal']['money'],2,'.','');
                    $footerSumTotal['charge_times_'.$type] = $kwhMoneyTimes['footerSumTotal']['times'];
                }
            }
        }
        $totalDataArr['footerSumTotal'] = $footerSumTotal;
        //合计每一行的“APP”+“电卡”的充电量/充值金额/充值人次情况
        foreach($totalDataArr as &$row){
            $row['charge_kwh_total'] = number_format(($row['charge_kwh_app'] + $row['charge_kwh_card']),2,'.','');
            $row['charge_money_total'] = number_format(($row['charge_money_app'] + $row['charge_money_card']),2,'.','');
            $row['charge_times_total'] = $row['charge_times_app'] + $row['charge_times_card'];
        }
        //合并在当前页显示的充电桩后面
        foreach($data as $k=>$dataItem){
            if(isset($totalDataArr[$dataItem['logic_addr']])){
                $data[$k] = array_merge($dataItem, $totalDataArr[$dataItem['logic_addr']]);
            }else{
                $data[$k] = array_merge($dataItem, $defaultLineArr);
            }
        }
        $stationArr = array_count_values( array_filter(array_column($totalData,'cs_name')) );
        $showFooter = array_merge(['code_from_compony'=>'合计：','logic_addr'=>count($totalData).'个电桩','cs_name'=>count($stationArr).'个电站'], $totalDataArr['footerSumTotal']);
        return json_encode([
            'rows' => $data,
            'total'=> $total,
            'footer'=>[$showFooter]
        ]);
    }

    /*
     * 获取指定充电桩在查询时间内的“APP”或“电卡”的充电量/充值金额/充值人次统计
     * @$timeArr: 要查询的时间段
     * @$type: 区分“APP”或“电卡”
     * @$logicAddr: 要查询的充电桩逻辑地址数组
     */
    protected function getPoleChargeKwhMoneyTimesByPeriod($timeArr,$type,$logicAddr){
        $dayStart = $timeArr[0]; $dayEnd = $timeArr[1];
        if($type == 'app'){
            $query = ReportPoleChargeStatisticsApp::find();
        }elseif($type == 'card'){
            $query = ReportPoleChargeStatisticsCard::find();
        }
        //按时间段查询数据
        if(!$dayStart && !$dayEnd)
        {
            //---（1）若开始日期和结束日期都没有，则默认查电桩的所有统计记录------------
            $data = $query->select(['logic_addr','month_total'])
                ->where(['logic_addr'=>$logicAddr])
                ->asArray()->all();
        }
        elseif($dayStart && !$dayEnd)
        {
            //---（2）若有开始日期，但没有结束日期---------------------------------
            $monthStart   = substr($dayStart,0,7);
            //a.查出“该开始年月之后”的所有年月的统计记录
            $data = $query->select(['logic_addr','month_total'])
                ->where(['logic_addr'=>$logicAddr])
                ->andWhere(['>','year_month',$monthStart])
                ->asArray()->all();
            //print_r($data);exit;
            //b.查出“该开始年月的后部分几日”的数据，再按上面a形式累加处理，最后合并数组，以便下面统一处理
            $days = ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31'];
            $startDayIndex = array_search( substr($dayStart,8,2), $days );
            $startMonthDays = array_slice($days,$startDayIndex);
            $tmpArr = [];
            foreach($startMonthDays as $day){
                $tmpArr[] = "`day_{$day}`";
            }
            $str = join(',',$tmpArr);
            $data2 = $query->select("`logic_addr`,{$str}")
                ->where(['logic_addr'=>$logicAddr])
                ->andWhere(['=','year_month',$monthStart])
                ->asArray()->all();
            foreach($data2 as $data2Key=>$data2Item){
                $totalKwh = 0; $totalMoney = 0; $totalTimes = 0;
                foreach($data2Item as $key=>$val){
                    if($key != 'logic_addr' && $val){
                        list($k, $m, $t) = explode('|', $val);
                        $totalKwh   += $k;
                        $totalMoney += $m;
                        $totalTimes += $t;
                    }
                }
                $data2[$data2Key] = [
                    'logic_addr'  => $data2Item['logic_addr'],
                    'month_total'=> number_format($totalKwh,2,'.','') . '|' . number_format($totalMoney,2,'.','') . '|' . $totalTimes
                ];
            }
            $data = array_merge($data2,$data);
            unset($data2);
        }
        elseif(!$dayStart && $dayEnd)
        {
            //---（3）若没有开始日期，但有结束日期---------------------------------
            $monthEnd   = substr($dayEnd,0,7);
            //a.查出“该结束年月之前”的所有年月的统计记录
            $data = $query->select(['logic_addr','month_total'])
                ->where(['logic_addr'=>$logicAddr])
                ->andWhere(['<','year_month',$monthEnd])
                ->asArray()->all();
            //print_r($data);exit;
            //b.查出“该结束年月的前部分几日”的数据，再按上面a形式累加处理，最后合并数组，以便下面统一处理
            $days = ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31'];
            $endDayIndex = array_search( substr($dayEnd,8,2), $days );
            $endMonthDays = array_slice($days, 0, $endDayIndex + 1);
            $tmpArr = [];
            foreach($endMonthDays as $day){
                $tmpArr[] = "`day_{$day}`";
            }
            $str = join(',',$tmpArr);
            $data2 = $query->select("`logic_addr`,{$str}")
                ->where(['logic_addr'=>$logicAddr])
                ->andWhere(['=','year_month',$monthEnd])
                ->asArray()->all();
            foreach($data2 as $data2Key=>$data2Item){
                $totalKwh = 0; $totalMoney = 0; $totalTimes = 0;
                foreach($data2Item as $key=>$val){
                    if($key != 'logic_addr' && $val){
                        list($k, $m, $t) = explode('|', $val);
                        $totalKwh   += $k;
                        $totalMoney += $m;
                        $totalTimes += $t;
                    }
                }
                $data2[$data2Key] = [
                    'logic_addr'  => $data2Item['logic_addr'],
                    'month_total'=> number_format($totalKwh,2,'.','') . '|' . number_format($totalMoney,2,'.','') . '|' . $totalTimes
                ];
            }
            $data = array_merge($data, $data2);
            unset($data2);
        }
        else
        {
            //---（4）若有开始日期，也有结束日期--------------------------
            $monthStart = substr($dayStart,0,7);
            $monthEnd   = substr($dayEnd,0,7);
            //判断开始年月和结束年月是否属于同一年月份？
            if($monthStart != $monthEnd)
            {
                //a.查出“该开始年月和结束年月之间”的所有年月的统计记录
                $data = $query->select(['logic_addr','month_total'])
                    ->where(['logic_addr'=>$logicAddr])
                    ->andWhere(['>','year_month',$monthStart])
                    ->andWhere(['<','year_month',$monthEnd])
                    ->asArray()->all();
                //print_r($data);exit;
                $days = ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31'];
                //b.查出“该开始年月的后部分几日”的数据，再按上面a形式累加处理，最后合并数组，以便下面统一处理
                $startDayIndex = array_search( substr($dayStart,8,2), $days );
                $startMonthDays = array_slice($days,$startDayIndex);
                $tmpArr = [];
                foreach($startMonthDays as $day){
                    $tmpArr[] = "`day_{$day}`";
                }
                $str = join(',',$tmpArr);
                $data2 = $query->select("`logic_addr`,{$str}")
                    ->where(['logic_addr'=>$logicAddr])
                    ->andWhere(['=','year_month',$monthStart])
                    ->asArray()->all();
                foreach($data2 as $data2Key=>$data2Item){
                    $totalKwh = 0; $totalMoney = 0; $totalTimes = 0;
                    foreach($data2Item as $key=>$val){
                        if($key != 'logic_addr' && $val){
                            list($k, $m, $t) = explode('|', $val);
                            $totalKwh   += $k;
                            $totalMoney += $m;
                            $totalTimes += $t;
                        }
                    }
                    $data2[$data2Key] = [
                        'logic_addr'  => $data2Item['logic_addr'],
                        'month_total'=> number_format($totalKwh,2,'.','') . '|' . number_format($totalMoney,2,'.','') . '|' . $totalTimes
                    ];
                }
                $data = array_merge($data2,$data);
                unset($data2);
                //c.查出“该结束年月的前部分几日”的数据，再按上面a形式累加处理，最后合并数组，以便下面统一处理
                $endDayIndex = array_search( substr($dayEnd,8,2), $days );
                $endMonthDays = array_slice($days, 0, $endDayIndex + 1);
                $tmpArr = [];
                foreach($endMonthDays as $day){
                    $tmpArr[] = "`day_{$day}`";
                }
                $str = join(',',$tmpArr);
                $data2 = $query->select("`logic_addr`,{$str}")
                    ->where(['logic_addr'=>$logicAddr])
                    ->andWhere(['=','year_month',$monthEnd])
                    ->asArray()->all();
                foreach($data2 as $data2Key=>$data2Item){
                    $totalKwh = 0; $totalMoney = 0; $totalTimes = 0;
                    foreach($data2Item as $key=>$val){
                        if($key != 'logic_addr' && $val){
                            list($k, $m, $t) = explode('|', $val);
                            $totalKwh   += $k;
                            $totalMoney += $m;
                            $totalTimes += $t;
                        }
                    }
                    $data2[$data2Key] = [
                        'logic_addr'  => $data2Item['logic_addr'],
                        'month_total'=> number_format($totalKwh,2,'.','') . '|' . number_format($totalMoney,2,'.','') . '|' . $totalTimes
                    ];
                }
                $data = array_merge($data, $data2);
                unset($data2);
            }
            else
            {
                $days = ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31'];
                //查出“该年月选定区间部分的几日”的数据，再按上面a形式累加处理，最后合并数组，以便下面统一处理
                $startDayIndex = array_search( substr($dayStart,8,2), $days );
                $endDayIndex   = array_search( substr($dayEnd,8,2), $days );
                $betweenMonthDays = array_slice($days,$startDayIndex,($endDayIndex - $startDayIndex + 1));
                $tmpArr = [];
                foreach($betweenMonthDays as $day){
                    $tmpArr[] = "`day_{$day}`";
                }
                $str = join(',',$tmpArr);
                $data = $query->select("`logic_addr`,{$str}")
                    ->where(['logic_addr'=>$logicAddr])
                    ->andWhere(['=','year_month',$monthStart])
                    ->asArray()->all();
                foreach($data as $dataKey=>$dataItem){
                    $totalKwh = 0; $totalMoney = 0; $totalTimes = 0;
                    foreach($dataItem as $key=>$val){
                        if($key != 'logic_addr' && $val){
                            list($k, $m, $t) = explode('|', $val);
                            $totalKwh   += $k;
                            $totalMoney += $m;
                            $totalTimes += $t;
                        }
                    }
                    $data[$dataKey] = [
                        'logic_addr'  => $dataItem['logic_addr'],
                        'month_total'=> number_format($totalKwh,2,'.','') . '|' . number_format($totalMoney,2,'.','') . '|' . $totalTimes
                    ];
                }
            }
        }
        //最后合计处理数据
        $returnArr = [];
        if($data){
            $totalPoleKwh = 0; $totalPoleMoney = 0; $totalPoleTimes = 0;
            foreach($data as $item){
                if(!$item['month_total']){
                    continue;
                }
                list($k, $m, $t) = explode('|', $item['month_total']);
                $totalPoleKwh   += $k;
                $totalPoleMoney += $m;
                $totalPoleTimes += $t;
                if(isset($returnArr[$item['logic_addr']])){
                    $returnArr[$item['logic_addr']]['kwh']   += $k;
                    $returnArr[$item['logic_addr']]['money'] += $m;
                    $returnArr[$item['logic_addr']]['times'] += $t;
                }else{
                    $returnArr[$item['logic_addr']]['kwh']   = $k;
                    $returnArr[$item['logic_addr']]['money'] = $m;
                    $returnArr[$item['logic_addr']]['times'] = $t;
                }
            }
            $returnArr['footerSumTotal']['kwh']   = number_format($totalPoleKwh,2,'.','');
            $returnArr['footerSumTotal']['money'] = number_format($totalPoleMoney,2,'.','');
            $returnArr['footerSumTotal']['times'] = $totalPoleTimes;
        }
        return $returnArr;
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
            'title'=>'ChargeStatistics',
            'subject'=>'ChargeStatistics',
            'description'=>'ChargeStatistics',
            'keywords'=>'ChargeStatistics',
            'category'=>'ChargeStatistics'
        ]);
        //---向excel添加构建的excel表头（这里有2行表头）-----------------
        $excHeaders = [
            [
                ['content'=>'电桩编号','font-weight'=>true,'width'=>'10','rowspan'=>2,'valign'=>'center'],
                ['content'=>'逻辑地址','font-weight'=>true,'width'=>'10','rowspan'=>2,'valign'=>'center'],
                ['content'=>'电桩类型','font-weight'=>true,'width'=>'20','rowspan'=>2,'valign'=>'center'],
                ['content'=>'所属充电站','font-weight'=>true,'width'=>'25','rowspan'=>2,'valign'=>'center'],
                ['content'=>'充电站类型','font-weight'=>true,'width'=>'15','rowspan'=>2,'valign'=>'center'],
                ['content'=>'充电量（度）','font-weight'=>true,'colspan'=>3,'align'=>'center'], // 跨多列
                ['content'=>'充电收入（元）','font-weight'=>true,'colspan'=>3,'align'=>'center'],
                ['content'=>'充电人次（次）','font-weight'=>true,'colspan'=>3,'align'=>'center']
            ],
            [
                [],[],[],[], [],
                ['content'=>'APP','font-weight'=>true,'width'=>'15'],
                ['content'=>'电卡','font-weight'=>true,'width'=>'15'],
                ['content'=>'合计','font-weight'=>true,'width'=>'15'],
                ['content'=>'APP','font-weight'=>true,'width'=>'15'],
                ['content'=>'电卡','font-weight'=>true,'width'=>'15'],
                ['content'=>'合计','font-weight'=>true,'width'=>'15'],
                ['content'=>'APP','font-weight'=>true,'width'=>'15'],
                ['content'=>'电卡','font-weight'=>true,'width'=>'15'],
                ['content'=>'合计','font-weight'=>true,'width'=>'15']
            ]
        ];
        foreach($excHeaders as $lineData){
            $excel->addLineToExcel($lineData);
        }

        // 获取数据。
        //---（1）查出所有符合查询条件的充电桩-----------------
        $query = ChargeSpots::find()
            ->select([
                '{{%charge_spots}}.code_from_compony',
                '{{%charge_spots}}.logic_addr',
                '{{%charge_spots}}.charge_type',
                '{{%charge_station}}.cs_name',
                '{{%charge_station}}.cs_type'
            ])
            ->joinWith('chargeStation',false,'LEFT JOIN')
            ->where(['{{%charge_spots}}.is_del'=>0,'{{%charge_station}}.cs_is_del'=>0])
            ->andWhere("{{%charge_station}}.cs_status != 'STOPPED'");
        $query->andFilterWhere(['=','cs_type',yii::$app->request->get('cs_type')]);
        $query->andFilterWhere(['=','station_id',yii::$app->request->get('station_id')]);
        $query->andFilterWhere(['=','charge_type',yii::$app->request->get('charge_type')]);
        if(yii::$app->request->get('charge_pole')){
            $query->andWhere([
                'or',
                ['like','code_from_compony',yii::$app->request->get('charge_pole')],
                ['like','logic_addr',yii::$app->request->get('charge_pole')]
            ]);
        }
        $data = $query->orderBy('{{%charge_spots}}.id DESC')->asArray()->all();
        //---（2）根据上面查询出的所有充电桩的逻辑地址，查询出对应的APP和IC电卡充电量/充值金额/充值人次统计数据---------------
        $totalDataArr = [];
        $defaultLineArr = [
            'charge_kwh_app'    => number_format(0,2,'.',''),
            'charge_kwh_card'   => number_format(0,2,'.',''),
            'charge_kwh_total'   => number_format(0,2,'.',''),
            'charge_money_app'  => number_format(0,2,'.',''),
            'charge_money_card' => number_format(0,2,'.',''),
            'charge_money_total'   => number_format(0,2,'.',''),
            'charge_times_app'  => 0,
            'charge_times_card' => 0,
            'charge_times_total' => 0
        ];
        $footerSumTotal = $defaultLineArr; //底部合计行
        if($data){
            $logicAddr = array_filter( array_column($data, 'logic_addr') );
            $typeArr = ['app','card'];
            //获取要查询的时间段，若没有指定则默认会统计到昨天为止
            $searchTime_start = yii::$app->request->get('searchTime_start','');
            $searchTime_end   = yii::$app->request->get('searchTime_end','');
            if($searchTime_start || $searchTime_end){
                if($searchTime_start && $searchTime_end){
                    if($searchTime_start > $searchTime_end){ //若时间段不合法，直接返回
                        return json_encode(['total'=>0,'rows'=>[]]);
                    }
                }else{
                    if($searchTime_start){
                        $searchTime_start = $searchTime_start.' 00:00:00';
                    }
                    if($searchTime_end){
                        $searchTime_end   = $searchTime_end.' 23:59:59';
                    }
                }
            }elseif(yii::$app->request->get('choose_period')){
                $choose_period = yii::$app->request->get('choose_period');
                $arr = $this->getTimeBucket($choose_period);
                if($arr){
                    $searchTime_start = $arr['timeFrom'];
                    $searchTime_end   = $arr['timeTo'];
                }
            }else{
                $searchTime_end   = date('Y-m-d 23:59:59',strtotime('-1 day')); //只统计到昨天为止
            }
            $timeArr = [$searchTime_start,$searchTime_end];
            foreach($typeArr as $type){
                $kwhMoneyTimes = $this->getPoleChargeKwhMoneyTimesByPeriod($timeArr,$type,$logicAddr);
                if($kwhMoneyTimes){
                    foreach($kwhMoneyTimes as $key=>$item){
                        if($key != 'footerSumTotal'){
                            if(!isset($totalDataArr[$key])){
                                $totalDataArr[$key] = $defaultLineArr;
                            }
                            $totalDataArr[$key]['charge_kwh_'.$type]   = number_format($item['kwh'],2,'.','');
                            $totalDataArr[$key]['charge_money_'.$type] = number_format($item['money'],2,'.','');
                            $totalDataArr[$key]['charge_times_'.$type] = $item['times'];
                        }
                    }
                    $footerSumTotal['charge_kwh_'.$type]   = number_format($kwhMoneyTimes['footerSumTotal']['kwh'],2,'.','');
                    $footerSumTotal['charge_money_'.$type] = number_format($kwhMoneyTimes['footerSumTotal']['money'],2,'.','');
                    $footerSumTotal['charge_times_'.$type] = $kwhMoneyTimes['footerSumTotal']['times'];
                }
            }
            $totalDataArr['footerSumTotal'] = $footerSumTotal;
            //合计每一行的“APP”+“电卡”的充电量/充值金额/充值人次情况
            foreach($totalDataArr as &$row){
                $row['charge_kwh_total'] = number_format(($row['charge_kwh_app'] + $row['charge_kwh_card']),2,'.','');
                $row['charge_money_total'] = number_format(($row['charge_money_app'] + $row['charge_money_card']),2,'.','');
                $row['charge_times_total'] = $row['charge_times_app'] + $row['charge_times_card'];
            }
            //合并在当前页显示的充电桩后面
            foreach($data as $k=>$dataItem){
                if(isset($totalDataArr[$dataItem['logic_addr']])){
                    $data[$k] = array_merge($dataItem, $totalDataArr[$dataItem['logic_addr']]);
                }else{
                    $data[$k] = array_merge($dataItem, $defaultLineArr);
                }
            }
            $stationArr = array_count_values( array_filter(array_column($data,'cs_name')) );
            $showFooter = array_merge(['code_from_compony'=>'合计：','logic_addr'=>count($data).'个电桩','charge_type'=>'','cs_name'=>count($stationArr).'个电站','cs_type'=>''], $totalDataArr['footerSumTotal']);
            $data[] = $showFooter;
            if($data){
                $configItems = ['charge_type','cs_type'];
                $configs = (new ConfigCategory())->getCategoryConfig($configItems,'value');
                //---向excel添加具体数据----------
                foreach($data as $item){
                    $lineData = [];
                    // 各combox配置项以txt代替val
                    foreach($configItems as $conf) {
                        if(isset($item[$conf]) && $item[$conf]) {
                            $item[$conf] = $configs[$conf][$item[$conf]]['text'];
                        }
                    }
                    foreach($item as $k=>$v) {
                        $lineData[] = ['content'=>$v];
                    }
                    $excel->addLineToExcel($lineData);
                }
                unset($data);
            }
        }

        $objPHPExcel = $excel->getPHPExcel();
        header("Content-type: application/octet-stream");
        header("Accept-Ranges: bytes");
        //header("Accept-Length:".$fileSize);
        header("Content-Disposition: attachment; filename=".iconv('utf-8','gbk','按电卡类型统计充电列表导出.xls'));
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }


}