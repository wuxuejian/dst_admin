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

class ChargerController extends Controller{
    public $layout = false;
    public $enableCsrfValidation = false;
    /**
     *	获取附近电桩
     *  charger_get-chargers
     */
    public function actionGetChargers(){
        $datas = [
            'error'=>1,
            'msg'=>'',
        ];
        //****获取所有提交参数****
        //当前你所在位置
        $curLng  = isset($_REQUEST['curlng']) ? trim($_REQUEST['curlng']) : '';
        $curLat  = isset($_REQUEST['curlat']) ? trim($_REQUEST['curlat']) : '';
        if(!$curLng || !$curLat) {
            $datas['msg'] = "没有获取到你当前的坐标位置！";
            return json_encode($datas);
        }  	
        //检索范围
        if(isset($_REQUEST['radius']) && !empty($_REQUEST['radius'])){
            $radius = floatval($_REQUEST['radius']);
        }
        //会员手机号
        $mobile  = isset($_REQUEST['mobile']) ? trim($_REQUEST['mobile']) : '';	
        //自主筛选电桩充电连接方式
        $contype = isset($_REQUEST['contype']) ? trim($_REQUEST['contype']) : '';
        //充电类型[快充/慢充]
        $chargePattern = isset($_REQUEST['charge_pattern']) ? trim($_REQUEST['charge_pattern']) : '';
        //充电桩状态
        $poleStatus = isset($_REQUEST['pole_status']) ? trim($_REQUEST['pole_status']) : '';
        //****参数获取完成****
        //如果选择了按充电桩状态查询先到前置查询符合状态条件的电桩
        if($poleStatus){
            $fmConnection = ChargeFrontmachine::connect(1);
            if(!$fmConnection[0]){
                $datas['msg'] = $fmConnection['1'];
                return json_encode($datas);
            }
            $fmPoleInfo = (new \yii\db\Query())
                ->select([
                    'charge_status.`DEV_ID`',
                    'charge_pole.`DEV_ADDR`'
                ])->from('charge_status')
                ->leftJoin('charge_station','charge_status.`DEV_ID` = charge_pole.`DEV_ID`')
                ->where(['charge_status.`STATUS`'=>$poleStatus])
                ->groupBy('DEV_ADDR')
                ->all($fmConnection);
            if(!$fmPoleInfo){
                $datas['msg'] = '没有符合条件的电站！';
                return json_encode($datas);
            }
        }
        var_dump(100);
        die;
        //===【1】先查找出符合的电站======================================================
        // 过滤条件：经纬度不为空、未被删除、对外开放、状态‘正常’、不是‘客户自用’类型
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
			WHERE `cs_lng` != '' AND `cs_lat` != '' AND `cs_is_del` = 0 AND `cs_is_open` = 1 AND `cs_status` = 'NORMAL' AND `cs_type` != 'CUSTOMER_SELF_USE'
		";
        //
        if(isset($radius)){
            //提交了距离严格按照距离查询
            //$sql .= 
        }else{
            //没有提交距离
            $radius = 10;//默然检索当前位置10公里以内的电站
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
        }
        

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

        //===【2】查找出电站后,再去查出这些电站下的电桩数据=======================================
        // 注意：上面查找电站时不用区分游客和会员，但是查电桩时要区分！
        // 若有手机号则是会员登录，查出该会员及车辆信息（备用）
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
        $filterConType = " 1 ";
        $connectionType = 'ALL'; // 表示当前查哪种连接方式,默认所有类型（app上说需要）
        if($_contype){
            // (1)优先考虑是否app用户正在作自主筛选。（不管是会员还是游客都优先考虑自主筛选）
            $connectionType = $_contype;
            if($_contype == 'ALL'){
                //---自主筛选--看所有类型的电桩
            }elseif($_contype == 'MY'){
                //---自主筛选--仅看符合我的车辆的类型的电桩
                if($vip_id) {
                    $vhc_con_types = [];
                    if(!empty($vip['vehicle'])){
                        //若有车辆，则只查匹配类型的电桩
                        foreach($vip['vehicle'] as $item) {
                            if($item['vhc_con_type']){
                                $vhc_con_types[] = $item['vhc_con_type'];
                            }
                        }
                        $vhc_con_types = array_unique($vhc_con_types);
                        if(!empty($vhc_con_types)) {
                            $str = implode("','",$vhc_con_types);
                            $filterConType .= " AND connection_type IN('{$str}') ";
                        }
                    }else{
                        //若没有车辆，则默认还是查所有类型的电桩
                        $connectionType = 'ALL';
                    }
                }
            }else{
                //---自主筛选--看其他具体某一种类型的电桩
                $filterConType .= " AND connection_type = '{$_contype}' ";
            }
        }else{
            // (2)若app用户没有在作自主筛选。
            // 若是会员，则查出适用于该会员车辆的电桩。
            if($vip_id) {
                $connectionType = 'MY';
                if(!empty($vip)) {
                    $vhc_con_types = [];
                    if(!empty($vip['vehicle'])){
                        //若有车辆，则只查匹配类型的电桩
                        foreach($vip['vehicle'] as $item) {
                            if($item['vhc_con_type']){
                                $vhc_con_types[] = $item['vhc_con_type'];
                            }
                        }
                        $vhc_con_types = array_unique($vhc_con_types);
                        if(!empty($vhc_con_types)) {
                            $str = implode("','",$vhc_con_types);
                            $filterConType .= " AND connection_type IN('{$str}') ";
                        }
                    }else{
                        //若没有车辆，则默认还是查所有类型的电桩
                        $connectionType = 'ALL';
                    }
                }
            }else{
                // 若是游客，默认查所有类型
            }
        }

        $stationIds = array_column($stations,'cs_id');
        $stationIdStr = "'" . implode("','",$stationIds) ."'";
        $sql = "
            SELECT id,code_from_compony,connection_type,charge_type,model,fm_id,logic_addr,station_id
            FROM cs_charge_spots
            WHERE station_id IN ({$stationIdStr}) AND is_del=0 AND {$filterConType}
        ";
        $data = ChargeSpots::findBySql($sql)->asArray()->all();

        if(empty($data)){
            $datas['error'] = 1;
            $datas['msg'] = "对不起，{$_radius}公里范围内没有找到符合的电桩！";
            echo json_encode($datas); exit;
        }
        //===【从前置机上获取真实电桩状态】===================================================================
        // 注意：这段代码在3处使用了，记得同步修改：charge模块ChargeSpotsController和ChargeStationController中、interfaces模块的ChargerController中。
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
                        'gun_status'=>"GROUP_CONCAT(cp_moni_data.INNER_ID,'-',cp_moni_data.STATUS)"
                    ])
                    ->from('charge_pole')
                    ->leftJoin('cp_moni_data', 'cp_moni_data.DEV_ID = charge_pole.DEV_ID')
                    ->where(['DEV_ADDR' => $logicAddr_arr])
                    ->groupBy('DEV_ADDR')   //->createCommand()->sql;
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
        //===【3】将查出的电桩数据按电站ID分组，再与电站组装并只返回有符合电桩的电站=================================
        $groupedChargers = [];
        foreach($data as $charger){
            $groupedChargers[$charger['station_id']][] = $charger;
        }
        $validStations = [];
        foreach($stations as $station){
            if(isset($groupedChargers[$station['cs_id']])){
                $station['chargers'] = $groupedChargers[$station['cs_id']];
                $validStations[] = $station;
            }
        }
        //print_r($validStations);exit;

        $datas['error'] = 0;
        $datas['msg'] = "获取附近电站及电桩成功！";
        $datas['realRadius'] = $_radius;
        $datas['maxDistance'] = $maxDistance;
        $datas['connectionType'] = $connectionType;
        $datas['total'] = count($stations);
        $datas['data'] = $validStations;
        echo json_encode($datas); exit;
    }



}