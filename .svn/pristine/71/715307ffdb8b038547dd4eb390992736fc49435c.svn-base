<?php
/**
 * 获取附近电站电桩 控制器（此控制器不继承BaseController去验证是否登录，因为游客也能使用此功能）
 */
namespace backend\modules\interfaces\controllers;
use yii;
use yii\web\Controller;
use backend\models\ConfigCategory;
use backend\models\ChargeStation;
use backend\models\ChargeSpots;
use backend\models\Vip;
use backend\models\Vehicle;
use backend\models\VipFavorite;
use backend\models\ChargeFrontmachine;
use backend\classes\FrontMachineDbConnection; // 前置机连接类

class NearbyController extends Controller{
    public $layout = false;
    public $enableCsrfValidation = false;

    /**
     * 前置机链接（因为此控制器没有继承BaseController，这里要声明此函数）
     */
    protected function connectFrontMachineDbByFmId($fmId=0,$isFmIdFromCharger=false)
    {
        $fm = ChargeFrontmachine::find()
            ->select(['db_host' => 'addr','db_port','db_username','db_password','db_name'])
            ->where(['id' => $fmId, 'is_del' => 0])
            ->asArray()->one();
        if ($fm) {
            $dbHost = $fm['db_host'];
            $dbPort = $fm['db_port'];
            $dbUsername = $fm['db_username'];
            $dbPassword = $fm['db_password'];
            $dbName = $fm['db_name'];
            if ($dbHost && $dbPort && $dbUsername && $dbPassword && $dbName) {
                $fmConnection = (new FrontMachineDbConnection())->connectFrontMachineDb($dbHost, $dbUsername, $dbPassword,$dbPort,$dbName);
                return $fmConnection;
            } else {
                return '该前置机的数据库连接信息填写不完整！';
            }
        } else {
            if ($isFmIdFromCharger) {
                return '根据该电桩查找不到对应的前置机，可能该电桩基本信息的“所属前置机”字段填写有误！';
            }
        }
    }


    /**
	 *	获取附近电站
     *  注意：地图界面上会筛选充电连接方式，但它是筛选电桩的而不是直接筛选电站。下面做法是根据筛选出的电桩查出电站范围并作为后面的电站过滤条件！
	 */
	public function actionGetStations(){
        $datas = [];
        $_curLng  = isset($_REQUEST['curlng']) ? trim($_REQUEST['curlng']) : '';	// 当前你所在位置的经度（如深圳114.066112,22.548515）
        $_curLat  = isset($_REQUEST['curlat']) ? trim($_REQUEST['curlat']) : '';  	// 当前你所在位置的纬度
        $_radius  = isset($_REQUEST['radius']) ? floatval($_REQUEST['radius']) : 10;// 要查找多少千米范围内的电站
        $_mobile  = isset($_REQUEST['mobile']) ? trim($_REQUEST['mobile']) : '';	// 会员手机号
        $_ver  	  = isset($_REQUEST['ver']) ? trim($_REQUEST['ver']) : '';		 	// App版本号
        //  下面各参数仅在app用户在地图界面上作自主筛选时才传递：
        $_contype = isset($_REQUEST['contype']) ? trim($_REQUEST['contype']) : '';	 // 自主筛选电桩充电连接方式

        if(!$_curLng || !$_curLat) {
            $datas['error'] = 1;
            $datas['msg'] = "没有获取到你当前的坐标位置！";
            echo json_encode($datas); exit;
        }

        // 若有手机号则是会员登录，查出该会员及车辆信息。
        if($_mobile) {
            $vip = Vip::find()
                ->joinWith('vehicle')
                ->where([
                    'and',
                    '{{%vip}}.is_del=0',
                    ['or',
                        '{{%vehicle}}.is_del=0',
                        '{{%vehicle}}.vehicle IS NULL'
                    ],
                    ['mobile'=>$_mobile]
                ])
                ->asArray()
                ->one();
        }
        $vip_id = isset($vip) && count($vip)>0 ? $vip['id'] : 0;

        // 过滤充电连接方式。
        // 注意：这里充电连接方式是筛选电桩的而不是直接筛选电站。下面做法是根据筛选出的电桩查出电站范围并作为后面的电站过滤条件！
        $connectionType = 'ALL'; // 表示当前查哪种连接方式,默认所有类型（app上说需要）
        if($_contype){
            // (1)优先考虑是否app用户正在作自主筛选。（不管是会员还是游客都优先考虑自主筛选）
            if($_contype == 'ALL'){
                //---自主筛选--看所有充电连接类型的电桩，即不限制电站
            }elseif($_contype == 'MY'){
                //---自主筛选--仅看拥有符合我的车辆的充电连接类型的电桩的电站
                if($vip_id) {
                    $vhc_con_types = [];
                    if(!empty($vip['vehicle'])){
                        //若有车辆，查出车辆的充电连接类型
                        foreach($vip['vehicle'] as $item) {
                            if($item['vhc_con_type']){
                                $vhc_con_types[] = $item['vhc_con_type'];
                            }
                        }
                        $vhc_con_types = array_unique($vhc_con_types);
                        //查出拥有此类充电连接类型的电桩的都有哪些电站，后面所查的电站都必须属于此范围中！！！
                        if(!empty($vhc_con_types)) {
                            $res = ChargeSpots::find()
                                ->select(['station_id'])
                                ->where(['connection_type'=>$vhc_con_types,'is_del'=>0])
                                ->groupBy('station_id')
                                ->asArray()->all();
                            if(!empty($res)){
                                $filterStationIds = array_column($res,'station_id');
                                $connectionType = 'MY';
                            }
                        }
                    }else{
                        //若没有车辆，则默认还是查所有充电连接类型的电桩，即不限制电站
                    }
                }
            }else{
                //---自主筛选--看其他具体某一种充电连接类型的电桩，查都有哪些电站，后面所查的电站都必须属于此范围中！！！
                $res = ChargeSpots::find()
                    ->select(['station_id'])
                    ->where(['connection_type'=>$_contype,'is_del'=>0])
                    ->groupBy('station_id')
                    ->asArray()->all();
                if(!empty($res)){
                    $filterStationIds = array_column($res,'station_id');
                    $connectionType = $_contype;
                }

            }
        }else{
            // (2)若app用户没有在作自主筛选。
            // 若是会员，则查出该会员车辆的充电连接类型，然后再查出拥有此类电桩的都有哪些电站！！！
            if($vip_id) {
                $vhc_con_types = [];
                if(!empty($vip['vehicle'])){
                    //若有车辆，查出车辆的充电连接类型
                    foreach($vip['vehicle'] as $item) {
                        if($item['vhc_con_type']){
                            $vhc_con_types[] = $item['vhc_con_type'];
                        }
                    }
                    $vhc_con_types = array_unique($vhc_con_types);
                    //查出拥有此类充电连接类型的电桩的都有哪些电站，后面所查的电站都必须属于此范围中！！！
                    if(!empty($vhc_con_types)) {
                        $res = ChargeSpots::find()
                            ->select(['station_id'])
                            ->where(['connection_type'=>$vhc_con_types,'is_del'=>0])
                            ->groupBy('station_id')
                            ->asArray()->all();
                        if(!empty($res)){
                            $filterStationIds = array_column($res,'station_id');
                            $connectionType = 'MY';
                        }
                    }
                }else{
                    //若没有车辆，则默认还是查所有充电连接类型的电桩，即不限制电站
                }
            }else{
                // 若是游客，默认查所有充电连接类型的电桩，即不限制电站
            }
        }



        //===【1】先查找出符合的电站======================================================
        // 过滤条件：经纬度不为空、未被删除、对外开放、状态‘正常’、不是‘客户自用’类型；
        $sql = "SELECT cs_id,cs_code,cs_name,cs_type,cs_lng,cs_lat,cs_address,
				ROUND(
					(6371 * ACOS(
							COS( RADIANS({$_curLat}) ) * COS( RADIANS(cs_lat) )
							*
							COS( RADIANS({$_curLng}) - RADIANS(cs_lng) )
							+
							SIN( RADIANS({$_curLat}) ) * SIN( RADIANS(cs_lat))
						)
					),4
				) AS distance
			FROM `cs_charge_station`
			WHERE cs_lng != '' AND cs_lat != '' AND cs_is_del = 0 AND cs_is_open = 1 AND cs_status = 'NORMAL' AND cs_type != 'CUSTOMER_SELF_USE'
		";

        // 若是会员且有车则还要再过滤电站
        if(isset($filterStationIds) && !empty($filterStationIds)){
            $sql .= " AND cs_id IN('" . implode("','",$filterStationIds) . "')";
        }

        $mixNum = 5;      // 默认最少查几个电站
        $maxNum = 20;     // 默认最多查几个电站
        $maxRadius = 5000;   // 默认最大查几公里范围
        do{
            $sql_1 = $sql . " HAVING distance <= {$_radius} ORDER BY distance ASC LIMIT {$maxNum} ";
            $stations = ChargeStation::findBySql($sql_1)->asArray()->all();
            if(isset($isLastQuery) && $isLastQuery){
                break; // 确定是最后一次查询后退出循环。
            }
            $_radius += 100; // 递增查找范围
            if($_radius >= $maxRadius){ // 若达到最大查找范围限制则以最大范围作最后一次查询。
                $_radius = $maxRadius;
                $isLastQuery = true;
            }
        }while(count($stations) < $mixNum && $_radius <= $maxRadius);

        if(empty($stations)){
            $datas['error'] = 1;
            $datas['msg'] = "对不起，{$_radius}公里范围内没有找到符合的电站！";
            echo json_encode($datas); exit;
        }

        // 返回查找出的最远的那个电站的距离（app使用）
        $maxDistance = $stations[count($stations)-1]['distance'];
        // 将电站各combox配置项一并取得对应的文本描述
        $configItems = ['cs_type'];
        $configs = (new ConfigCategory())->getCategoryConfig($configItems,'value');
        foreach($stations as $k=>$row){
            foreach($configItems as $item){
                if(isset($row[$item])){
                    $stations[$k][$item.'_txt'] = '';
                    if($row[$item]) {
                        $_val = $row[$item];
                        $_txt = $configs[$item][$_val]['text'];
                        $stations[$k][$item.'_txt'] = $_txt;
                    }
                }
            }
        }
        //print_r($stations);exit;

        $datas['error'] = 0;
        $datas['msg'] = "获取附近电站成功！";
        $datas['realRadius'] = $_radius;
        $datas['maxDistance'] = $maxDistance;
        $datas['connectionType'] = $connectionType;
        $datas['total'] = count($stations);
        $datas['data'] = $stations;
		echo json_encode($datas); exit;
	}


    /**
     *	获取某电站下的电桩
     */
    public function actionGetPoles()
    {
        $datas = [];
        $_stationId = isset($_REQUEST['stationId']) ? intval($_REQUEST['stationId']) : 0;   // 某电站ID
        $_mobile  = isset($_REQUEST['mobile']) ? trim($_REQUEST['mobile']) : '';	        // 会员手机号
        $_ver = isset($_REQUEST['ver']) ? trim($_REQUEST['ver']) : '';                      // App版本号

        if(!$_stationId){
            $datas['error'] = 1;
            $datas['msg'] = "没有传递电站ID，无法获取电桩详情！";
            echo json_encode($datas); exit;
        }

        $data = ChargeSpots::find()
            ->select(['id','code_from_compony','connection_type','charge_type','model','fm_id','logic_addr','station_id'])
            ->where(['is_del'=>0,'station_id'=>$_stationId])
            ->asArray()->all();
        if(empty($data)){
            $datas['error'] = 1;
            $datas['msg'] = "对不起，该电站内还没有任何电桩！";
            echo json_encode($datas); exit;
        }
        //===【电桩状态的处理】===================================================================
        // 注意：这段代码在3处使用了，记得同步修改：charge模块ChargeSpotsController和ChargeStationController中、interfaces模块的NearbyController中。
        //---1.默认所有电桩真实状态都是【离线】-------------------------
        // 注意：电桩类型决定电枪数量，而有几个电枪就有几个状态。
        // 前置机上配置的电桩状态：0-充电，1-待机，2-故障，3-禁用，4-离线
        foreach($data as $k=>$item){
            $chargeType = $item['charge_type'];
            if($chargeType == 'DC' || $chargeType == 'AC'){
                $data[$k]['status'] = '4';
            }elseif($chargeType == 'DC_DC' || $chargeType == 'AC_AC' || $chargeType == 'AC_DC'){
                $data[$k]['status'] = '4,4';
            }
        }
        //---2.连接前置机数据库查询各电桩真实状态 begin -----------------
        $fmIds = array_unique(array_column($data, 'fm_id'));
        // 将电桩逻辑地址按前置机id分组组装成数组备用
        $tmpArr = [];
        foreach ($fmIds as $fmId) {
            if ($fmId > 0) {
                foreach ($data as $item) {
                    if ($item['fm_id'] == $fmId && $item['logic_addr']) {
                        $tmpArr[$fmId][] = $item['logic_addr'];
                    }
                }
            }
        }
        foreach ($tmpArr as $fmId=>$logicAddr_arr) {
            $fmConnection = $this->connectFrontMachineDbByFmId($fmId);
            if (is_object($fmConnection)) {
                $rows = (new \yii\db\Query())
                    ->select([
                        'DEV_ADDR',
                        'gun_status'=>"GROUP_CONCAT(charge_status.INNER_ID,'-',charge_status.STATUS)"
                    ])
                    ->from('charge_pole')
                    ->leftJoin('charge_status', 'charge_status.DEV_ID = charge_pole.DEV_ID')
                    ->where(['DEV_ADDR' => $logicAddr_arr])
                    ->groupBy('DEV_ADDR')   //->createCommand()->sql; echo $rows;exit;
                    ->indexBy('DEV_ADDR')
                    ->all($fmConnection);
                // 合并数据
                if (!empty($rows)) {
                    foreach ($data as &$ControllerGLitem) {
                        if (isset($rows[$ControllerGLitem['logic_addr']])) {
                            $gunStatus_str = $rows[$ControllerGLitem['logic_addr']]['gun_status']; // 每一个枪号及对应状态的格式：“枪号-状态”
                            if($gunStatus_str){
                                $gunStatus_arr = explode(',',$gunStatus_str);
                                $gunStatus = [];
                                foreach($gunStatus_arr as $item){
                                    $tmp = explode('-',$item);
                                    $gunStatus[$tmp[0]] = $tmp[1];
                                }
                                // 降序排序并保持索引关系。这么做是因为比对测量点规范：枪号大的为A枪，小的为B枪，这样前端就能规范地以A-B顺序显示对应状态。
                                arsort($gunStatus);
                                $ControllerGLitem['status'] = implode(',',array_values($gunStatus));
                            }
                        }
                    }
                }
            }
        }
        //---连接前置机数据库查询各电桩真实状态 end -------------------------
        //print_r($data);exit;

        // 将电桩各combox配置项一并取得对应的文本描述
        $configItems = ['connection_type','status','charge_type','model'];
        $configs = (new ConfigCategory())->getCategoryConfig($configItems,'value');
        foreach($data as $k=>$row){
            foreach($configItems as $item){
                if(isset($row[$item])){
                    $data[$k][$item.'_txt'] = '';
                    if($row[$item]) {
                        $_val = $row[$item];
                        if($item != 'status'){
                            $_txt = $configs[$item][$_val]['text'];
                        }else{
                            //注意：电桩状态要特殊处理，区分单枪双枪且双枪电桩获取的电桩状态是两个值。
                            $_valArr = explode(',',$_val);
                            if(count($_valArr) == 1){
                                $_txt = [
                                    [
                                        'gunName'=>'单枪',
                                        'gunStatus'=>$_valArr[0],
                                        'gunStatusTxt'=>$configs[$item][$_valArr[0]]['text']
                                    ]
                                ];
                            }else{
                                $_txt = [
                                    [
                                        'gunName'=>'A枪',
                                        'gunStatus'=>$_valArr[0],
                                        'gunStatusTxt'=>$configs[$item][$_valArr[0]]['text']
                                    ],[
                                        'gunName'=>'B枪',
                                        'gunStatus'=>$_valArr[1],
                                        'gunStatusTxt'=>$configs[$item][$_valArr[0]]['text']
                                    ]
                                ];
                            }
                        }
                        $data[$k][$item.'_txt'] = $_txt;
                    }
                }
            }
        }
        //print_r($data);exit;

        // 若有手机号则是会员登录，查出该会员ID。
        if($_mobile) {
            $vip = Vip::find()->select(['id'])->where(['mobile'=>$_mobile,'is_del'=>0])->asArray()->one();
        }
        $vip_id = isset($vip) && count($vip)>0 ? $vip['id'] : 0;
        // 返回各电桩收藏状态（app上说需要判断各电桩是否已被当前用户收藏）
        if($vip_id){
            $favorites = VipFavorite::find()->select(['id','chargerid'])->where(['vip_id'=>$vip_id,'is_del'=>0])->indexBy('chargerid')->asArray()->all();
            if($favorites){
                foreach($data as $k=>$charger){
                    if(isset($favorites[$charger['id']])){
                        $data[$k]['isAddFavorite'] = 1;
                        $data[$k]['fid'] = $favorites[$charger['id']]['id'];
                    }else{
                        $data[$k]['isAddFavorite'] = 0;
                        $data[$k]['fid'] = 0;
                    }
                }
            }else{
                foreach($data as $k=>$charger){
                    $data[$k]['isAddFavorite'] = 0;
                    $data[$k]['fid'] = 0;
                }
            }
        }else{
            foreach($data as $k=>$charger){
                $data[$k]['isAddFavorite'] = 0;
                $data[$k]['fid'] = 0;
            }
        }

        //print_r($data);exit;
        $datas['error'] = 0;
        $datas['msg'] = "获取电桩详情成功！";
        $datas['total'] = count($data);
        $datas['data'] = $data;
        echo json_encode($datas); exit;
    }



}