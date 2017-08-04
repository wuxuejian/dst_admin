<?php
namespace backend\modules\interfaces\controllers;
use backend\models\ChargeStation;
use backend\models\ConfigCategory;
use backend\models\ChargeFrontmachine;
use backend\models\ChargeSpots;
use backend\models\VipFavorite;
use yii;
use yii\web\Controller;
use yii\data\Pagination;
class ChargeStationController extends Controller{
    public $layout = false;
    public $enableCsrfValidation = false;

    /**
     * 根据两点间的经纬度计算距离
     * @param float $lat 纬度值
     * @param float $lng 经度值
     */
    protected static function getDistance($lat1, $lng1, $lat2, $lng2) {
        $earthRadius = 6367;
        //approximate radius of earth in meters
        /* Convert these degrees to radians to work with the formula */
        $lat1 = ($lat1 * pi() ) / 180;
        $lng1 = ($lng1 * pi() ) / 180;
        $lat2 = ($lat2 * pi() ) / 180;
        $lng2 = ($lng2 * pi() ) / 180;
        /* Using the Haversine formula  http://en.wikipedia.org/wiki/Haversine_formula  calculate the distance */
        $calcLongitude = $lng2 - $lng1;
        $calcLatitude = $lat2 - $lat1;
        $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
        $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
        $calculatedDistance = $earthRadius * $stepTwo;
        return sprintf('%.2f',$calculatedDistance);
    } 
    /**
     * 根据用户输入内容返回可用的关键词
     * charge-station_search-keyword
     */
    public function actionSearchKeyword(){
        $datas = [
            'error'=>1,
            'msg'=>'',
            'data'=>[],
        ];
        $keyword = isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] : '';
        if(empty($keyword)){
            $datas['msg'] = '请输入关键词！';
            return json_encode($datas);
        }
        $keyword = str_replace(['%','_'],'',$keyword);
        $datas['error'] = 0;
        $searchResult = ChargeStation::find()
            ->select(['cs_id','cs_code','cs_name','cs_address','cs_type'])
            ->where('(`cs_code` like :keyword or `cs_name` like :keyword or `cs_address` like :keyword) and `cs_is_del` = 0 AND `cs_lng` != "" AND `cs_lat` != "" AND `cs_is_del` = 0 AND `cs_is_open` = 1 AND `cs_status` = "NORMAL" AND `cs_type` != "CUSTOMER_SELF_USE"',[
                'keyword'=>"%{$keyword}%"
            ])->limit(5)->asArray()->all();
        //var_dump($searchResult);
        if($searchResult){
            foreach($searchResult as $val){
                if(stristr($val['cs_code'],$keyword)){
                    $datas['data'][] = [
                        'cs_id'=>$val['cs_id'],
                        'text'=>$val['cs_code'],
                        'cs_type'=>$val['cs_type']
                    ];
                    continue;
                }elseif(stristr($val['cs_name'],$keyword)){
                    $datas['data'][] = [
                        'cs_id'=>$val['cs_id'],
                        'text'=>$val['cs_name'],
                        'cs_type'=>$val['cs_type']
                    ];
                    continue;
                }else{
                    $datas['data'][] = [
                        'cs_id'=>$val['cs_id'],
                        'text'=>$val['cs_address'],
                        'cs_type'=>$val['cs_type']
                    ];
                    continue;
                }
            }
        }
        return json_encode($datas);
    }

    /**
     * 根据关键词查询电站
     * charge-station_search-with-keyword
     */
    public function actionSearchWithKeyword(){
        $datas = [
            'error'=>1,
            'msg'=>'',
            'data'=>[],
        ];
        //接收参数
        //关键字
        $keyword = isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] : '';
        if(empty($keyword)){
            $datas['msg'] = '请输入关键词！';
            return json_encode($datas);
        }
        $keyword = str_replace(['%','_'],'',$keyword);
        $keyword = addslashes($keyword);
        //经纬度
        $curLng  = isset($_REQUEST['curlng']) ? floatval($_REQUEST['curlng']) : '';
        $curLat  = isset($_REQUEST['curlat']) ? floatval($_REQUEST['curlat']) : '';
        if(!$curLng || !$curLat) {
            $datas['msg'] = "没有获取到你当前的坐标位置！";
            return json_encode($datas);
        }
        $datas['data']['keyword'] = $keyword;
        $datas['data']['radius'] = '';
        $datas['data']['contype'] = '';
        $datas['data']['charge_pattern'] = '';
        $datas['data']['pole_status'] = '';
        //参数接收完成
        $stations = ChargeStation::find()
            ->select([
                '`cs_id`,`cs_code`,`cs_name`,`cs_lng`,
            	`spots_fast_num`,`spots_slow_num`,`spots_connection_type`,
                `cs_lat`,`cs_address`,`cs_type`'])
            ->where('(`cs_code` like :keyword or `cs_name` like :keyword or `cs_address` like :keyword) and `cs_is_del` = 0 AND `cs_lng` != "" AND `cs_lat` != "" AND `cs_is_del` = 0 AND `cs_is_open` = 1 AND `cs_status` = "NORMAL" AND `cs_type` != "CUSTOMER_SELF_USE"',[
                'keyword'=>"%{$keyword}%"
            ])->asArray()->all();
        if(!$stations){
            $datas['msg'] = '没有查找到电桩，请切换关键词再试！';
            return json_encode($datas);
        }
        //链接前置机数据库
        $fmConnection = ChargeFrontmachine::connect(1);
        if(!$fmConnection[0]){
            $datas['msg'] = $fmConnection['1'];
            return json_encode($datas);
        }
        $datas['error'] = 0;
        //查询符合条件的电站的所有电桩
        $poleInfo = ChargeSpots::find()
            ->select(['station_id','connection_type','logic_addr','charge_pattern'])
            ->where(['station_id'=>array_column($stations,'cs_id')])
            ->asArray()->all();
        //查询前置机上空闲的电桩
        $fmFreePoleInfo = (new \yii\db\Query())
            ->select([
                'charge_status.`STATUS`',
                'charge_pole.`DEV_ADDR`'
            ])->from('charge_status')
            ->leftJoin('charge_pole','charge_status.`DEV_ID` = charge_pole.`DEV_ID`')
            ->where([
                'charge_status.`STATUS`' => 1,
                'charge_pole.`DEV_ADDR`' => array_column($poleInfo,'logic_addr'),
            ])->groupBy('DEV_ADDR')->indexBy('DEV_ADDR')->all($fmConnection[1]);
            //->createCommand()->sql;
        $poleInfoIndexBySid = [];//对电桩信息按电站分组
        $freePoleInfoBySid = [];
        foreach($poleInfo as $v){
            if(!isset($poleInfoIndexBySid[$v['station_id']])){
                $poleInfoIndexBySid[$v['station_id']] = [];
            }
            if(!in_array($v['connection_type'],$poleInfoIndexBySid[$v['station_id']])){
                $poleInfoIndexBySid[$v['station_id']][] = $v['connection_type'];
            }
            if(!isset($freePoleInfoBySid[$v['station_id']])){
                $freePoleInfoBySid[$v['station_id']] = ['slow'=>0,'fast'=>0];
            }
            if(isset($fmFreePoleInfo) && isset($fmFreePoleInfo[$v['logic_addr']])){
                if($v['charge_pattern'] == 'SLOW_CHARGE'){
                    $freePoleInfoBySid[$v['station_id']]['slow'] += 1 ;
                }else{
                    $freePoleInfoBySid[$v['station_id']]['fast'] += 1;
                }
            }
        }
        foreach($stations as &$_CSItem){
            //计算两点距离
            $_CSItem['distance'] = self::getDistance($curLat,$curLng,$_CSItem['cs_lat'],$_CSItem['cs_lng']);
            //电站接口类型
            if(isset($poleInfoIndexBySid[$_CSItem['cs_id']])){
                $_CSItem['connection_type'] = $poleInfoIndexBySid[$_CSItem['cs_id']];
            }else{
                $_CSItem['connection_type']  = [];
            }
            //电站在线电枪
            if(isset($freePoleInfoBySid[$_CSItem['cs_id']])){
                $_CSItem['free_pole_num'] = $freePoleInfoBySid[$_CSItem['cs_id']];
            }else{
                $_CSItem['free_pole_num']  = ['fast'=>0,'slow'=>0];
            }
        }
        $datas['data']['list'] = $stations;
        return json_encode($datas);
    }

    /**
     * 电站查询条件页面选项
     * charge-station_search-condition
     */
    public function actionSearchCondition(){
        $config = (new ConfigCategory)->getCategoryConfig(['connection_type','charge_pattern']);
        $connectionType = array_values($config['connection_type']);
        array_unshift($connectionType,['value'=>'','text'=>'不限']);
        $chargePattern =  array_values($config['charge_pattern']);
        array_unshift($chargePattern,['value'=>'','text'=>'不限']);
        $returnArr = [
            'error'=>0,
            'msg'=>'',
            'data'=>[[
                    'text'=>'接口类型',
                    'key'=>'contype',
                    'item'=>$connectionType
                ],[
                    'text'=>'充电桩状态',
                    'key'=>'pole_status',
                    'item'=>[
                        ['value'=>'','text'=>'不限'],
                        ['value'=>1,'text'=>'可用']
                    ]
                ],[
                    'text'=>'充电模式',
                    'key'=>'charge_pattern',
                    'item'=>$chargePattern,
                ],
        ]];
        return json_encode($returnArr);
    }

    /**
     * 根据条件查询筛选电站
     * charge-station_search-width-condition
     */
    public function actionSearchWidthCondition(){
        $datas = [
            'error'=>1,
            'msg'=>'',
            'data'=>'',
        ];
        //****获取所有提交参数****
        //当前你所在位置
        $curLng  = isset($_REQUEST['curlng']) ? floatval($_REQUEST['curlng']) : '';
        $curLat  = isset($_REQUEST['curlat']) ? floatval($_REQUEST['curlat']) : '';
        if(!$curLng || !$curLat) {
            $datas['msg'] = "没有获取到你当前的坐标位置！";
            return json_encode($datas);
        }
        //会员手机号
        $mobile  = isset($_REQUEST['mobile']) ? trim($_REQUEST['mobile']) : '';
        //检索范围
        $radius = isset($_REQUEST['radius']) ? floatval($_REQUEST['radius']) : 10;
        //自主筛选电桩充电连接方式
        $contype = isset($_REQUEST['contype']) ? trim($_REQUEST['contype']) : '';
        //充电类型[快充/慢充]
        $chargePattern = isset($_REQUEST['charge_pattern']) ? trim($_REQUEST['charge_pattern']) : '';
        //充电桩状态
        $poleStatus = isset($_REQUEST['pole_status']) ? trim($_REQUEST['pole_status']) : '';
        //****参数获取完成****
        //向app返回当前所使用的查询条件
        $datas['data']['radius'] = $radius;
        $datas['data']['contype'] = $contype;
        $datas['data']['charge_pattern'] = $chargePattern;
        $datas['data']['pole_status'] = $poleStatus;
        //链接前置机数据库
        $fmConnection = ChargeFrontmachine::connect(1);
        if(!$fmConnection[0]){
            $datas['msg'] = $fmConnection[1];
            return json_encode($datas);
        }
        //如果选择了按充电桩状态查询先到前置查询符合状态条件的电桩
        if($poleStatus){
            $fmPoleInfo = (new \yii\db\Query())
                ->select([
                    'charge_status.`DEV_ID`',
                    'charge_pole.`DEV_ADDR`'
                ])->from('charge_status')
                ->leftJoin('charge_pole','charge_status.`DEV_ID` = charge_pole.`DEV_ID`')
                ->where(['charge_status.`STATUS`'=>$poleStatus])
                ->groupBy('DEV_ADDR')->all($fmConnection[1]);
            if(!$fmPoleInfo){
                $datas['msg'] = '没有符合条件的电站！';
                return json_encode($datas);
            }
            $poleAddr = array_unique(array_column($fmPoleInfo,'DEV_ADDR'));
        }
        //以下三个条件先要验证是否有该类型的电桩
        if($contype || $chargePattern || $poleStatus){
            $poleInfo = ChargeSpots::find()
                ->select(['distinct `station_id`'])
                ->andFilterWhere(['connection_type'=>$contype])
                ->andFilterWhere(['charge_pattern'=>$chargePattern]);
            if(isset($poleAddr)){
                $poleInfo->andFilterWhere(['logic_addr'=>$poleAddr]);
            }
            $poleInfo = $poleInfo->andWhere(['is_del'=>0])->asArray()->all();
            if(!$poleInfo){
                $datas['msg'] = '没有符合条件的电站！';
                return json_encode($datas);
            }
            $stationIds = array_column($poleInfo,'station_id');
        }
        //查询电站
        $sql = "SELECT `cs_id`,`cs_code`,`cs_name`,`cs_lng`,`cs_lat`,
            `cs_address`,
                ROUND(
                    (6371 * ACOS(
                            COS( RADIANS({$curLat}) ) * COS( RADIANS(cs_lat) )
                            *
                            COS( RADIANS({$curLng}) - RADIANS(cs_lng) )
                            +
                            SIN( RADIANS({$curLat}) ) * SIN( RADIANS(cs_lat))
                        )
                    ),4
                ) AS distance,`cs_type`,`spots_fast_num`,`spots_slow_num`,`spots_connection_type`
            FROM `cs_charge_station`
            WHERE `cs_lng` != '' AND `cs_lat` != '' AND `cs_is_del` = 0
            AND `cs_is_open` = 1 AND `cs_status` = 'NORMAL'
            AND `cs_type` != 'CUSTOMER_SELF_USE'";
        if(isset($stationIds)){
            $sql .= ' and `cs_id` in('.join(',',$stationIds).')';
        }
        $sql .= " HAVING `distance` <= {$radius}";
        $stations = ChargeStation::findBySql($sql)->asArray()->all();
        if($stations){
            $datas['error'] = 0;
            //查询符合条件的电站的所有电桩
            $query = ChargeSpots::find()
                ->select(['station_id','connection_type','logic_addr','charge_pattern'])
                ->where(['station_id'=>array_column($stations,'cs_id')]);
            //电桩数量不对修改   by 2017/3/17
           $poleInfo= $query->andWhere(['is_del'=>0])->asArray()->all();
            //查询前置机上空闲的电桩
            $fmFreePoleInfo = (new \yii\db\Query())
                ->select([
                    'charge_status.`STATUS`',
                    'charge_pole.`DEV_ADDR`'
                ])->from('charge_status')
                ->leftJoin('charge_pole','charge_status.`DEV_ID` = charge_pole.`DEV_ID`')
                ->where([
                    'charge_status.`STATUS`' => 1,
                    'charge_pole.`DEV_ADDR`' => array_column($poleInfo,'logic_addr'),
                ])->groupBy('DEV_ADDR')->indexBy('DEV_ADDR')->all($fmConnection[1]);
                //->createCommand()->sql;
            $poleInfoIndexBySid = [];//对电桩信息按电站分组
            $freePoleInfoBySid = [];
            foreach($poleInfo as $v){
                if(!isset($poleInfoIndexBySid[$v['station_id']])){
                    $poleInfoIndexBySid[$v['station_id']] = [];
                }
                if(!in_array($v['connection_type'],$poleInfoIndexBySid[$v['station_id']])){
                    $poleInfoIndexBySid[$v['station_id']][] = $v['connection_type'];
                }
                if(!isset($freePoleInfoBySid[$v['station_id']])){
                    $freePoleInfoBySid[$v['station_id']] = ['slow'=>0,'fast'=>0];
                }
                if(isset($fmFreePoleInfo) && isset($fmFreePoleInfo[$v['logic_addr']])){
                    if($v['charge_pattern'] == 'SLOW_CHARGE'){
                        $freePoleInfoBySid[$v['station_id']]['slow'] += 1 ;
                    }else{
                        $freePoleInfoBySid[$v['station_id']]['fast'] += 1;
                    }
                }
            }
            foreach($stations as &$_CSItem){
                if(isset($poleInfoIndexBySid[$_CSItem['cs_id']])){
                    $_CSItem['connection_type'] = $poleInfoIndexBySid[$_CSItem['cs_id']];
                }else{
                    $_CSItem['connection_type']  = [];
                }
                if(isset($freePoleInfoBySid[$_CSItem['cs_id']])){
                    $_CSItem['free_pole_num'] = $freePoleInfoBySid[$_CSItem['cs_id']];
                }else{
                    $_CSItem['free_pole_num']  = ['fast'=>0,'slow'=>0];
                }
                //距离保留两位小数
                $_CSItem['distance'] = sprintf('%.2f',$_CSItem['distance']);
            }
            $datas['data']['list'] = $stations;
        }else{
            $datas['msg'] = '没有符合条件的电站！';
        }
        return json_encode($datas);
    }

    /**
     * 按列表显示电站
     * charge-station_station-list
     */
    public function actionStationList(){
        $returnArr = [
            'error'=>1,
            'msg'=>'',
            'data'=>'',
        ];
        //****获取所有提交参数****
        //当前你所在位置
        $curLng  = isset($_REQUEST['curlng']) ? floatval($_REQUEST['curlng']) : '';
        $curLat  = isset($_REQUEST['curlat']) ? floatval($_REQUEST['curlat']) : '';
        //所属城市ID
        $city_id = isset($_REQUEST['city_id']) ? intval($_REQUEST['city_id']) : '';
        $area_id = isset($_REQUEST['area_id']) ? intval($_REQUEST['area_id']) : '';
        if(!$curLng || !$curLat) {
            $returnArr['msg'] = "没有获取到你当前的坐标位置！";
            return json_encode($returnArr);
        }
        $page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
        $_GET['page'] = $page;
        //会员手机号
        $mobile  = isset($_REQUEST['mobile']) ? trim($_REQUEST['mobile']) : '';
        //自主筛选电桩充电连接方式
        $contype = isset($_REQUEST['contype']) ? trim($_REQUEST['contype']) : '';
        //充电类型[快充/慢充]
        $chargePattern = isset($_REQUEST['charge_pattern']) ? trim($_REQUEST['charge_pattern']) : '';
        //充电桩状态
        $poleStatus = isset($_REQUEST['pole_status']) ? trim($_REQUEST['pole_status']) : '';
        //****参数获取完成****
        //向app返回当前所使用的查询条件
        $returnArr['data']['contype'] = $contype;
        $returnArr['data']['charge_pattern'] = $chargePattern;
        $returnArr['data']['pole_status'] = $poleStatus;
        //链接前置机数据库
        $fmConnection = ChargeFrontmachine::connect(1);
        if(!$fmConnection[0]){
            $returnArr['msg'] = $fmConnection['1'];
            return json_encode($returnArr);
        }
        //如果选择了按充电桩状态查询先到前置查询符合状态条件的电桩
        if($poleStatus){
            $fmPoleInfo = (new \yii\db\Query())
                ->select([
                    'charge_status.`DEV_ID`',
                    'charge_pole.`DEV_ADDR`'
                ])->from('charge_status')
                ->leftJoin('charge_pole','charge_status.`DEV_ID` = charge_pole.`DEV_ID`')
                ->where(['charge_status.`STATUS`'=>$poleStatus])
                ->groupBy('DEV_ADDR')->all($fmConnection[1]);
            if(!$fmPoleInfo){
                $returnArr['msg'] = '没有符合条件的电站！';
                return json_encode($returnArr);
            }
            $poleAddr = array_unique(array_column($fmPoleInfo,'DEV_ADDR'));
        }
        //以下三个条件先要验证是否有该类型的电桩
        if($contype || $chargePattern || $poleStatus){
            $poleInfo = ChargeSpots::find()
                ->select(['distinct `station_id`'])
                ->andFilterWhere(['connection_type'=>$contype])
                ->andFilterWhere(['charge_pattern'=>$chargePattern]);
            if(isset($poleAddr)){
                $poleInfo->andFilterWhere(['logic_addr'=>$poleAddr]);
            }
            $poleInfo = $poleInfo->andWhere(['is_del'=>0])->asArray()->all();
            if(!$poleInfo){
                $returnArr['msg'] = '没有符合条件的电站！';
                return json_encode($returnArr);
            }
            $stationIds = array_column($poleInfo,'station_id');
        }
        //查询电站
        /*$sql = "SELECT `cs_id`,`cs_code`,`cs_name`,`cs_lng`,`cs_lat`,
            `cs_address`,
                ROUND(
                    (6371 * ACOS(
                            COS( RADIANS({$curLat}) ) * COS( RADIANS(cs_lat) )
                            *
                            COS( RADIANS({$curLng}) - RADIANS(cs_lng) )
                            +
                            SIN( RADIANS({$curLat}) ) * SIN( RADIANS(cs_lat))
                        )
                    ),4
                ) AS distance
            FROM `cs_charge_station`";*/
        $where = "`cs_lng` != '' AND `cs_lat` != '' AND `cs_is_del` = 0 AND `cs_is_open` = 1 AND `cs_status` = 'NORMAL' AND `cs_type` != 'CUSTOMER_SELF_USE'";
        if(isset($stationIds)){
            $where .= ' and `cs_id` in('.join(',',$stationIds).')';
        }
        //按城市查询
        if($city_id){
        	$where .= ' and `city_id` ='.$city_id;
        }
        if($area_id){
        	$where .= ' and `area_id` ='.$area_id;
        }
        
        $query = ChargeStation::find()->select([
                'cs_id','cs_code','cs_name','cs_lng','cs_lat','cs_address','cs_type','spots_fast_num','spots_slow_num','spots_connection_type',
                'distance'=>"ROUND( (6371 * ACOS( COS( RADIANS({$curLat}) ) * COS( RADIANS(cs_lat) ) * COS( RADIANS({$curLng}) - RADIANS(cs_lng) ) + SIN( RADIANS({$curLat}) ) * SIN( RADIANS(cs_lat)) ) ),4 )"
            ])->where($where);
        $total = $query->count();
        $returnArr['data']['total'] = $total;
        //分页
        $pageSize = isset($_POST['rows']) && $_POST['rows'] <= 50 ? intval($_POST['rows']) : 10;
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $stations = $query->offset($pages->offset)->limit($pages->limit)
            ->orderBy('distance')->asArray()->all();
        
        if($stations){
            $returnArr['error'] = 0;
            //查询符合条件的电站的所有电桩
            $poleInfo = ChargeSpots::find()
                ->select(['station_id','connection_type','logic_addr','charge_pattern'])
                ->where(['station_id'=>array_column($stations,'cs_id')]);
            //debug  筛选出没有删除标识的电桩  by 2017/3/7
            $poleInfo = $poleInfo->andWhere(['is_del'=>0])->asArray()->all();
            //查询前置机上空闲的电桩
            $fmFreePoleInfo = (new \yii\db\Query())
                ->select([
                    'charge_status.`STATUS`',
                    'charge_pole.`DEV_ADDR`'
                ])->from('charge_status')
                ->leftJoin('charge_pole','charge_status.`DEV_ID` = charge_pole.`DEV_ID`')
                ->where([
                    'charge_status.`STATUS`' => 1,
                    'charge_pole.`DEV_ADDR`' => array_column($poleInfo,'logic_addr'),
                ])->groupBy('DEV_ADDR')->indexBy('DEV_ADDR')->all($fmConnection[1]);
                //->createCommand()->sql;
            $poleInfoIndexBySid = [];//对电桩信息按电站分组
            $freePoleInfoBySid = [];
            foreach($poleInfo as $v){
                if(!isset($poleInfoIndexBySid[$v['station_id']])){
                    $poleInfoIndexBySid[$v['station_id']] = [];
                }
                if(!in_array($v['connection_type'],$poleInfoIndexBySid[$v['station_id']])){
                    $poleInfoIndexBySid[$v['station_id']][] = $v['connection_type'];
                }
                if(!isset($freePoleInfoBySid[$v['station_id']])){
                    $freePoleInfoBySid[$v['station_id']] = ['slow'=>0,'fast'=>0];
                }
                if(isset($fmFreePoleInfo) && isset($fmFreePoleInfo[$v['logic_addr']])){
                    if($v['charge_pattern'] == 'SLOW_CHARGE'){
                        $freePoleInfoBySid[$v['station_id']]['slow'] += 1 ;
                    }else{
                        $freePoleInfoBySid[$v['station_id']]['fast'] += 1;
                    }
                }
            }
            foreach($stations as &$_CSItem){
                if(isset($poleInfoIndexBySid[$_CSItem['cs_id']])){
                    $_CSItem['connection_type'] = $poleInfoIndexBySid[$_CSItem['cs_id']];
                }else{
                    $_CSItem['connection_type']  = [];
                }
                if(isset($freePoleInfoBySid[$_CSItem['cs_id']])){
                    $_CSItem['free_pole_num'] = $freePoleInfoBySid[$_CSItem['cs_id']];
                }else{
                    $_CSItem['free_pole_num']  = ['fast'=>0,'slow'=>0];
                }
                //距离保留两位小数
                $_CSItem['distance'] = sprintf('%.2f',$_CSItem['distance']);
            }
            $returnArr['data']['list'] = $stations;
        }else{
            if($page == 1){
                $returnArr['msg'] = '没有符合条件的电站！';
            }else{
                $returnArr['msg'] = '无更多数据！';
            }
        }
        return json_encode($returnArr);
    }

    /**
     * 获取指定电站的详细信息
     * charge-station_get-detail
     */
    public function actionGetDetail(){
        $datas = [
            'error'=>1,
            'msg'=>'',
        ];
        //参数接收开始
        $csId = isset($_REQUEST['cs_id']) ? $_REQUEST['cs_id'] : 0;
        if(!$csId){
            $datas['msg'] = '请选择您要查询的电站！';
            return json_encode($datas);
        }
        $mobile = isset($_REQUEST['mobile']) ? $_REQUEST['mobile'] : '';
        //参数接收完成
        $stationInfo = ChargeStation::find()
            ->select([
                'cs_id','cs_code','cs_name','cs_address','cs_lng','cs_lat',
            	'cs_type','spots_fast_num','spots_slow_num','spots_connection_type',
                'cs_pic_path','cs_service_telephone',
                'cs_building_user','app_tips',
                'cs_opentime','cs_powerrate','cs_servicefee','cs_parkingfee'
            ])->where([
                'cs_id'=>$csId,
                'cs_is_del'=>0,
                'cs_status'=>'NORMAL',
            ])->andWhere(['!=','cs_type','CUSTOMER_SELF_USE'])
            ->asArray()->one();
        if(!$stationInfo){
            $datas['msg'] = '没有该电站数据！';
            return json_encode($datas);
        }
        if($stationInfo['cs_pic_path']){
            $stationInfo['cs_pic_path'] = explode(';',$stationInfo['cs_pic_path']);
        }
        if(!$stationInfo['cs_pic_path']){
            $stationInfo['cs_pic_path'] = [];
        }else{
            foreach($stationInfo['cs_pic_path'] as &$_c_detail_pic_item){
                $_c_detail_pic_item = 'http://'.$_SERVER['SERVER_ADDR'].'/'.$_c_detail_pic_item;
            }
        }
        //开放时间
        $cs_opentime = unserialize($stationInfo['cs_opentime']);
        unset($stationInfo['cs_opentime']); //删掉原数据避免app解析出问题
        $stationInfo['open_time'] = [
            ['text'=>'工作日','content'=>$cs_opentime['workday_s'].' - '.$cs_opentime['workday_e']],
            ['text'=>'节假日','content'=>$cs_opentime['holiday_s'].' - '.$cs_opentime['holiday_e']],
        ];
        //费用
        $cs_powerrate = unserialize($stationInfo['cs_powerrate']);
        $cs_parkingfee = unserialize($stationInfo['cs_parkingfee']);
        unset($stationInfo['cs_powerrate']);
        unset($stationInfo['cs_parkingfee']);
        $stationInfo['rate'] = [
            'charge_rate'=>[
                ['time'=>$cs_powerrate['peacetime_s'].' - '.$cs_powerrate['peacetime_e'],'rate'=>$cs_powerrate['peacetime_price'].'元/度'],
                ['time'=>$cs_powerrate['peaktime_s'].' - '.$cs_powerrate['peaktime_e'],'rate'=>$cs_powerrate['peaktime_price'].'元/度'],
                ['time'=>$cs_powerrate['valleytime_s'].' - '.$cs_powerrate['valleytime_e'],'rate'=>$cs_powerrate['valleytime_price'].'元/度'],
            ],
            'server_rate'=>$stationInfo['cs_servicefee'].'元/度',
            'port_rate'=>[
                [
                    'text'=>'慢充桩车位',
                    'free_hour'=>$cs_parkingfee['slowpole_freetime'],
                    'list'=>[
                        ['time'=>$cs_parkingfee['slowpole_period1_s'].' - '.$cs_parkingfee['slowpole_period1_e'],'rate'=>$cs_parkingfee['slowpole_period1_price'].'元/小时'],
                    ]
                ],
                [
                    'text'=>'快充桩车位',
                    'free_hour'=>$cs_parkingfee['fastpole_freetime'],
                    'list'=>[
                        ['time'=>$cs_parkingfee['fastpole_period1_s'].' - '.$cs_parkingfee['fastpole_period1_e'],'rate'=>$cs_parkingfee['fastpole_period1_price'].'元/小时'],
                    ]
                ]
            ],
        ];
        $stationInfo['pole_total'] = 0;
        $stationInfo['free_pole_num'] = ['fast'=>0,'slow'=>0];
        //查询本电站下的电桩
        $poleInfo = ChargeSpots::find()
            ->select([
                'id','code_from_compony','charge_type',
                'charge_pattern','charge_gun_nums',
                'logic_addr'
            ])
            ->where(['station_id'=>$csId,'is_del'=>0])
            ->orderBy('charge_pattern ASC')
            ->asArray()->all();
        if($poleInfo){
            //电桩总数
            $stationInfo['pole_total'] = count($poleInfo);
            //查询前置机上电桩当前状态
            //链接前置机数据库
            $fmConnection = ChargeFrontmachine::connect(1);
            if(!$fmConnection[0]){
                $datas['msg'] = $fmConnection['1'];
                return json_encode($datas);
            }
            //查询前置机上空闲的电桩
            $fmPoleStatus = (new \yii\db\Query())
                ->select([
                    'charge_status.`STATUS`',
                    'charge_status.`INNER_ID`',
                    'charge_pole.`DEV_ADDR`'
                ])->from('charge_status')
                ->leftJoin('charge_pole','charge_status.`DEV_ID` = charge_pole.`DEV_ID`')
                ->where([
                    'charge_pole.`DEV_ADDR`' => array_column($poleInfo,'logic_addr'),
                ])->all($fmConnection[1]);
            if($fmPoleStatus){
                foreach($fmPoleStatus as $k=>$v){
                    $fmPoleStatus[$v['DEV_ADDR']][$v['INNER_ID']] = $v['STATUS'];
                    unset($fmPoleStatus[$k]);
                }
            }else{
                $fmPoleStatus = [];
            }
            //查询前置机电桩信息
            $fmPoleInfo = (new \yii\db\Query())
                ->select(['DEV_NAME','DEV_ADDR'])
                ->from('charge_pole')
                ->where([
                    '`DEV_ADDR`' => array_column($poleInfo,'logic_addr'),
                ])->indexBy('DEV_ADDR')->all($fmConnection[1]);
            //var_dump($fmPoleStatus);
            //获取配置项
            $config = (new ConfigCategory)->getCategoryConfig(['charge_pattern','charge_type','status'],'value');
            //组装电桩列表
            foreach($poleInfo as $v){
                $chargeTypeText = isset($config['charge_type'][$v['charge_type']]) ? $config['charge_type'][$v['charge_type']]['text'] : '' ;
                $chargePatternText = isset($config['charge_pattern'][$v['charge_pattern']]) ? $config['charge_pattern'][$v['charge_pattern']]['text'] : '' ;
                $devName = $fmPoleInfo && isset($fmPoleInfo[$v['logic_addr']]) ? str_replace('充电','',$fmPoleInfo[$v['logic_addr']]['DEV_NAME']) : '';
                $singlePoleData = [
                    'id'=>$v['id'],
                    'code_from_compony'=>$v['code_from_compony'],
                    'DEV_NAME'=>$devName,
                    'charge_type'=>$v['charge_type'],
                    'charge_type_text'=>$chargeTypeText,
                    'charge_pattern'=>$v['charge_pattern'],
                    'charge_pattern_text'=>$chargePatternText,
                    'gun_list'=>[],
                ];
                //判断各枪状态
                //只有要枪空闲就认为该桩空闲
                $freePole = false;//本桩是否有空闲桩初始化为false
                switch ($v['charge_type']) {
                    case 'DC':
                        //单直流
                        if(isset($fmPoleStatus[$v['logic_addr']]) && isset($fmPoleStatus[$v['logic_addr']][3])){
                            //前置机有该枪数据
                            $statusCode = $fmPoleStatus[$v['logic_addr']][3];
                            $statusText = $config['status'][$statusCode]['text'];
                        }else{
                            //前置机无该枪数据
                            $statusCode = 4;
                            $statusText = '离线';
                        }
                        //改变空闲状态变量值
                        if($statusCode == 1){
                            $freePole = true;
                        }
                        //添加本枪信息
                        $singlePoleData['gun_list'][] = [
                            'gun_name'=>'',
                            'status_code'=>$statusCode,
                            'status_text'=>$statusText,
                            'mpn'=>8
                        ];
                        break;
                    case 'AC':
                        //单交流
                        if(isset($fmPoleStatus[$v['logic_addr']]) && isset($fmPoleStatus[$v['logic_addr']][1])){
                            //前置机有该枪数据
                            $statusCode = $fmPoleStatus[$v['logic_addr']][1];
                            $statusText = $config['status'][$statusCode]['text'];
                        }else{
                            //前置机无该枪数据
                            $statusCode = 4;
                            $statusText = '离线';
                        }
                        //改变空闲状态变量值
                        if($statusCode == 1){
                            $freePole = true;
                        }
                        //添加本枪信息
                        $singlePoleData['gun_list'][] = [
                            'gun_name'=>'',
                            'status_code'=>$statusCode,
                            'status_text'=>$statusText,
                            'mpn'=>2
                        ];
                        break;
                    case 'AC_DC':
                        //交直流
                        //A枪
                        if(isset($fmPoleStatus[$v['logic_addr']]) && isset($fmPoleStatus[$v['logic_addr']][3])){
                            //前置机有该枪数据
                            $statusCode = $fmPoleStatus[$v['logic_addr']][3];
                            $statusText = $config['status'][$statusCode]['text'];
                        }else{
                            //前置机无该枪数据
                            $statusCode = 4;
                            $statusText = '离线';
                        }
                        //改变空闲状态变量值
                        if($statusCode == 1){
                            $freePole = true;
                        }
                        //添加本枪信息
                        $singlePoleData['gun_list'][] = [
                            'gun_name'=>'A枪',
                            'status_code'=>$statusCode,
                            'status_text'=>$statusText,
                            'mpn'=>8
                        ];
                        //B枪
                        if(isset($fmPoleStatus[$v['logic_addr']]) && isset($fmPoleStatus[$v['logic_addr']][1])){
                            //前置机有该枪数据
                            $statusCode = $fmPoleStatus[$v['logic_addr']][1];
                            $statusText = $config['status'][$statusCode]['text'];
                        }else{
                            //前置机无该枪数据
                            $statusCode = 4;
                            $statusText = '离线';
                        }
                        //改变空闲状态变量值
                        if($statusCode == 1){
                            $freePole = true;
                        }
                        //添加本枪信息
                        $singlePoleData['gun_list'][] = [
                            'gun_name'=>'B枪',
                            'status_code'=>$statusCode,
                            'status_text'=>$statusText,
                            'mpn'=>2
                        ];
                        break;
                    case 'DC_DC':
                        //双直流
                        //A枪
                        if(isset($fmPoleStatus[$v['logic_addr']]) && isset($fmPoleStatus[$v['logic_addr']][3])){
                            //前置机有该枪数据
                            $statusCode = $fmPoleStatus[$v['logic_addr']][3];
                            $statusText = $config['status'][$statusCode]['text'];
                        }else{
                            //前置机无该枪数据
                            $statusCode = 4;
                            $statusText = '离线';
                        }
                        //改变空闲状态变量值
                        if($statusCode == 1){
                            $freePole = true;
                        }
                        //添加本枪信息
                        $singlePoleData['gun_list'][] = [
                            'gun_name'=>'A枪',
                            'status_code'=>$statusCode,
                            'status_text'=>$statusText,
                            'mpn'=>8
                        ];
                        //B枪
                        if(isset($fmPoleStatus[$v['logic_addr']]) && isset($fmPoleStatus[$v['logic_addr']][2])){
                            //前置机有该枪数据
                            $statusCode = $fmPoleStatus[$v['logic_addr']][2];
                            $statusText = $config['status'][$statusCode]['text'];
                        }else{
                            //前置机无该枪数据
                            $statusCode = 4;
                            $statusText = '离线';
                        }
                        //改变空闲状态变量值
                        if($statusCode == 1){
                            $freePole = true;
                        }
                        //添加本枪信息
                        $singlePoleData['gun_list'][] = [
                            'gun_name'=>'B枪',
                            'status_code'=>$statusCode,
                            'status_text'=>$statusText,
                            'mpn'=>4
                        ];
                        break;
                    case 'AC_AC':
                        //双交流
                        //A枪
                        if(isset($fmPoleStatus[$v['logic_addr']]) && isset($fmPoleStatus[$v['logic_addr']][1])){
                            //前置机有该枪数据
                            $statusCode = $fmPoleStatus[$v['logic_addr']][1];
                            $statusText = $config['status'][$statusCode]['text'];
                        }else{
                            //前置机无该枪数据
                            $statusCode = 4;
                            $statusText = '离线';
                        }
                        //改变空闲状态变量值
                        if($statusCode == 1){
                            $freePole = true;
                        }
                        //添加本枪信息
                        $singlePoleData['gun_list'][] = [
                            'gun_name'=>'A枪',
                            'status_code'=>$statusCode,
                            'status_text'=>$statusText,
                            'mpn'=>2
                        ];
                        //B枪
                        if(isset($fmPoleStatus[$v['logic_addr']]) && isset($fmPoleStatus[$v['logic_addr']][0])){
                            //前置机有该枪数据
                            $statusCode = $fmPoleStatus[$v['logic_addr']][0];
                            $statusText = $config['status'][$statusCode]['text'];
                        }else{
                            //前置机无该枪数据
                            $statusCode = 4;
                            $statusText = '离线';
                        }
                        //改变空闲状态变量值
                        if($statusCode == 1){
                            $freePole = true;
                        }
                        //添加本枪信息
                        $singlePoleData['gun_list'][] = [
                            'gun_name'=>'B枪',
                            'status_code'=>$statusCode,
                            'status_text'=>$statusText,
                            'mpn'=>1
                        ];
                        break;
                }
                $stationInfo['pole_list'][] = $singlePoleData;
                //判断本枪是否空闲
                if($freePole){
                    switch ($v['charge_pattern']) {
                        case 'FAST_CHARGE':
                            //快
                            $stationInfo['free_pole_num']['fast'] ++;
                            break;
                        default:
                            //慢
                            $stationInfo['free_pole_num']['slow'] ++;
                            break;
                    }
                }
            }
        }
        //echo '<pre>';
        //检测电站是否被当前用户收藏
        $stationInfo['is_favorite'] = 0;
        $stationInfo['favorite_id'] = 0;
        if($mobile){
            $favoriteInfo = VipFavorite::find()
                ->select(['{{%vip_favorite}}.`id`'])
                ->joinWith('vip',false)
                ->where([
                    '{{%vip_favorite}}.`is_del`'=>0,
                    '{{%vip_favorite}}.`chargerid`'=>$stationInfo['cs_id'],
                    '{{%vip}}.`mobile`'=>$mobile,
                ])->asArray()->one();
            if($favoriteInfo){
                $stationInfo['is_favorite'] = 1;
                $stationInfo['favorite_id'] = $favoriteInfo['id'];
            }
        }

        //--按“快充桩在前慢充桩在后，且桩号由小到大”顺序显示--20160411
        // 关键是将数据根据 harge_pattern 升序排列，根据 DEV_NAME 升序排列，把 $data 作为最后一个参数，以通用键排序
        $stationInfo['pole_list'] = @$stationInfo['pole_list']?$stationInfo['pole_list']:array();
        $charge_pattern = array();
        $DEV_NAME = array();
        foreach($stationInfo['pole_list'] as $key=>$item){
            $charge_pattern[$key] = $item['charge_pattern'];
            $DEV_NAME[$key] = $item['DEV_NAME'];
        }
        
        array_multisort($charge_pattern, SORT_ASC, $DEV_NAME,SORT_NUMERIC, SORT_ASC, $stationInfo['pole_list']);

        //--当从收藏页面点击进入电站详情页面时-begin--20160414
        //计算您与该电站的距离
        $stationInfo['distance'] = 0.00;
        $curLng  = isset($_REQUEST['curlng']) ? floatval($_REQUEST['curlng']) : '';
        $curLat  = isset($_REQUEST['curlat']) ? floatval($_REQUEST['curlat']) : '';
        if ($curLng && $curLat) {
            $stationInfo['distance'] = self::getDistance($curLat,$curLng,$stationInfo['cs_lat'],$stationInfo['cs_lng']);
        }
        //查该电站的接口类型
        $stationInfo['connection_type']  = [];
        $poleInfo = ChargeSpots::find()
            ->select(['connection_type'])
            ->where(['station_id'=>$csId])
            ->groupBy('connection_type')
            ->asArray()->all();
        if ($poleInfo) {
            $stationInfo['connection_type'] = array_column($poleInfo,'connection_type');
        }
        //--当从收藏页面点击进入电站详情页面时-end--20160414

        $datas['error'] = 0;
        $datas['data'] = $stationInfo;
        return json_encode($datas);
    }
}