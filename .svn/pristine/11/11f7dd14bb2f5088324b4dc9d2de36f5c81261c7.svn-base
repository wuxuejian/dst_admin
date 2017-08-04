<?php
/**
 * 充电管理控制器
 */
namespace backend\modules\interfaces\controllers;
use yii;
use yii\db\Query;
use backend\models\Vip;
use backend\models\VipChargeRecord;
use backend\models\ChargeSpots;
use backend\models\ChargeFrontmachine;
use backend\models\ConfigCategory;
use backend\models\VipMoneyChange;
use backend\classes\FrontMachine;
class ChargeController extends BaseController{

    /**
     * 获取充电桩信息
     * charge_pole-info
     */
    public function actionPoleInfo(){
        $datas = [];
        $datas['error'] = 1;
        $datas['msg'] = '';
        //接收参数
        $poleId = isset($_REQUEST['pole_id']) ? $_REQUEST['pole_id'] : 0;
        $measuringPointNumber = isset($_REQUEST['mpn']) ? $_REQUEST['mpn'] : 0;
        //如果有枪号则以枪号来查询电桩id。枪号格式：逻辑地址（单枪）或 逻辑地址+A/B（双枪）
        $gunCode = isset($_REQUEST['gun_code']) ? $_REQUEST['gun_code'] : '';
        if($gunCode){
            $lastChar = substr($gunCode,-1);
            if($lastChar == 'A' || $lastChar == 'B'){
                $logicAddr = substr($gunCode,0,strlen($gunCode) - 1);
            }else{
                $logicAddr = $gunCode;
            }
            $poleInfo = ChargeSpots::find()
                ->select(['id','charge_type'])
                ->where([
                    'logic_addr'=>$logicAddr,
                    'is_del'=>0
                ])->asArray()->one();
            if(!$poleInfo){
                $datas['msg'] = '枪号输入错误！';
                return json_encode($datas);
            }
            $poleId = $poleInfo['id'];
            $measuringPointNumber = 0;
            //获取测量点号
            switch($poleInfo['charge_type']){
                case 'DC': //单直流
                    $measuringPointNumber = 8; break;
                case 'AC': //单交流
                    $measuringPointNumber = 2; break;
                case 'AC_DC': //交直流
                    $arr = ['A'=>8,'B'=>2];
                    $measuringPointNumber = $arr[$lastChar]; break;
                case 'DC_DC': //双直流
                    $arr = ['A'=>8,'B'=>4];
                    $measuringPointNumber = $arr[$lastChar]; break;
                case 'AC_AC': //双交流
                    $arr = ['A'=>2,'B'=>1];
                    $measuringPointNumber = $arr[$lastChar]; break;
            }
        }
        //接收参数完成
        if(!$poleId || !$measuringPointNumber){
            $datas['msg'] = '缺少必要参数！';
            return json_encode($datas);
        }
        $poleInfo = ChargeSpots::find()
            ->select([
                'pole_id'=>'{{%charge_spots}}.`id`',
                '{{%charge_spots}}.`charge_type`',
                '{{%charge_spots}}.`status`',
                '{{%charge_spots}}.`code_from_compony`',
                '{{%charge_spots}}.`connection_type`',
                '{{%charge_spots}}.`rated_output_voltage`',
                '{{%charge_spots}}.`rated_output_current`',
                '{{%charge_spots}}.`rated_output_power`',
                '{{%charge_spots}}.`fm_id`',
                '{{%charge_spots}}.`logic_addr`',
                '{{%charge_spots}}.`charge_pattern`',
                '{{%charge_station}}.`cs_name`'
            ])->where([
                '{{%charge_spots}}.`id`'=>$poleId,
                '{{%charge_spots}}.`is_del`'=>0
            ])->joinWith('chargeStation',false)
            ->asArray()->one();
        if(!$poleInfo){
            $datas['msg'] = '没有查询到电桩信息！';
            return json_encode($poleInfo);
        }
        $poleInfo['gun_info'] = ['name'=>'','code'=>''];
        $poleInfo['fit_carmodel'] = '请确保充电车辆与充电桩的接口标准相匹配';
        $measuringPoint = 0;//请求启动的枪的测量点（枪号）
        switch ($poleInfo['charge_type']) {
            case 'DC': //单直流
                if($measuringPointNumber != 8){
                    $datas['msg'] = '无法获取电桩信息，枪号错误！';
                    return json_encode($datas);
                }
                $poleInfo['gun_info']['code'] = $poleInfo['logic_addr'];
                $measuringPoint = 3;
                break;
            case 'AC': //单交流
                if($measuringPointNumber != 2){
                    $datas['msg'] = '无法获取电桩信息，枪号错误！';
                    return json_encode($datas);
                }
                $poleInfo['gun_info']['code'] = $poleInfo['logic_addr'];
                $measuringPoint = 1;
                break;
            case 'AC_DC': //交直流
                switch ($measuringPointNumber) {
                    case 8:
                        $poleInfo['gun_info']['name'] = 'A枪';
                        $poleInfo['gun_info']['code'] = $poleInfo['logic_addr'].'A';
                        $measuringPoint = 3;
                        break;
                    case 2:
                        $poleInfo['gun_info']['name'] = 'B枪';
                        $poleInfo['gun_info']['code'] = $poleInfo['logic_addr'].'B';
                        $measuringPoint = 1;
                        break;
                    default:
                        $datas['msg'] = '无法获取电桩信息，枪号错误！';
                        return json_encode($datas);
                }
                break;
            case 'DC_DC': //双直流
                switch ($measuringPointNumber) {
                    case 8:
                        $poleInfo['gun_info']['name'] = 'A枪';
                        $poleInfo['gun_info']['code'] = $poleInfo['logic_addr'].'A';
                        $measuringPoint = 3;
                        break;
                    case 4:
                        $poleInfo['gun_info']['name'] = 'B枪';
                        $poleInfo['gun_info']['code'] = $poleInfo['logic_addr'].'B';
                        $measuringPoint = 2;
                        break;
                    default:
                        $datas['msg'] = '无法获取电桩信息，枪号错误！';
                        return json_encode($datas);
                }
                break;
            case 'AC_AC': //双交流
                switch ($measuringPointNumber) {
                    case 2:
                        $poleInfo['gun_info']['name'] = 'A枪';
                        $poleInfo['gun_info']['code'] = $poleInfo['logic_addr'].'A';
                        $measuringPoint = 1;
                        break;
                    case 1:
                        $poleInfo['gun_info']['name'] = 'B枪';
                        $poleInfo['gun_info']['code'] = $poleInfo['logic_addr'].'B';
                        $measuringPoint = 0;
                        break;
                    default:
                        $datas['msg'] = '无法获取电桩信息，枪号错误！';
                        return json_encode($datas);
                }
                break;
            default:
                $datas['msg'] = '无法获取电桩信息，电桩没有配置类型！';
                return json_encode($datas);
        }
        //获取配置项目
        $configItem = [
            'connection_type','charge_type','status','charge_pattern'
        ];
        $config = (new ConfigCategory)->getCategoryConfig($configItem,'value');
        //获取前置机上电桩状态
        //链接前置机数据库
        $connectRes = ChargeFrontmachine::connect($poleInfo['fm_id']);
        if(!$connectRes[0]){
            $datas['msg'] = $connectRes[1];
            return json_encode($datas);
        }
        $fmPoleStatus = (new \yii\db\Query())
            ->select([
                'charge_status.`STATUS`',
            ])->from('charge_status')
            ->leftJoin('charge_pole','charge_status.`DEV_ID` = charge_pole.`DEV_ID`')
            ->where([
                'charge_pole.`DEV_ADDR`'=>$poleInfo['logic_addr'],
                'charge_status.`INNER_ID`'=> $measuringPoint,
            ])->one($connectRes[1]);
            //->createCommand()->sql;
        $poleInfo['gun_status'] = 4;//默认离线
        if($fmPoleStatus){
            $poleInfo['gun_status'] = $fmPoleStatus['STATUS'];//默认离线
        }
        $poleInfo['connection_type_text'] = $config['connection_type'][$poleInfo['connection_type']]['text'];
        $poleInfo['charge_type_text'] = $config['charge_type'][$poleInfo['charge_type']]['text'];
        $poleInfo['charge_pattern_text'] = $config['charge_pattern'][$poleInfo['charge_pattern']]['text'];
        if(isset($config['status'][$poleInfo['gun_status']])){
            $poleInfo['gun_status_text'] = $config['status'][$poleInfo['gun_status']]['text'];
        }else{
            $poleInfo['gun_status_text'] = '';
        }
        $datas['error'] = 0;
        $datas['data'] = $poleInfo;
        $datas['data']['mpn'] = $measuringPointNumber;
        //echo '<pre>';
        return json_encode($datas);
    }
	
    /**
     *  开始充电
     *  charge_start-charge
     */
    public function actionStartCharge(){
        $returnArr = [
            'error'=>1,
            'msg'=>'',
        ];
        //接收参数开始
        $mobile = isset($_REQUEST['mobile'])  ? trim($_REQUEST['mobile']) : '';
        $poleId = isset($_REQUEST['pole_id']) ? $_REQUEST['pole_id'] : 0;
        $mpn = isset($_REQUEST['mpn']) ? $_REQUEST['mpn'] : 0;
        //接收参数结束
        //检测参数是否完整
        if(!$poleId || !$mpn){
            $returnArr['msg'] = '缺少必要参数！';
            return json_encode($returnArr);
        }
        //检测用户是否有没有支付的充电记录如果有则完成支付
        $nowChargeRecord = VipChargeRecord::find()
            ->select(['{{%vip_charge_record}}.`id`'])
            ->joinWith('vip',false)
            ->where([
                '{{%vip}}.`mobile`'=>$mobile,
                '{{%vip_charge_record}}.`start_status`'=>'success',
                '{{%vip_charge_record}}.`end_status`'=>'noaction',
                '{{%vip_charge_record}}.`pay_status`'=>'wait_pay',
            ])->one();
        if($nowChargeRecord){
            $returnArr['msg'] = '您有未结束的充电记录，请先结束！';
            return json_encode($returnArr);
        }
        //查询电桩信息
        $poleInfo = ChargeSpots::find()
            ->select(['id','fm_id','logic_addr','charge_type'])
            ->where(['id'=>intval($poleId),'is_del'=>0])
            ->asArray()->one();
        if(!$poleInfo){
            $returnArr['msg'] = '电桩不存在！';
            return json_encode($returnArr);
        }
        //检测测量点号是否正确
        switch ($poleInfo['charge_type']) {
            case 'DC': //单直流
                if($mpn != 8){
                    $returnArr['msg'] = '测量点号错误，无法启动！';
                    return json_encode($returnArr);
                }
                break;
            case 'AC': //单交流
                if($mpn != 2){
                    $returnArr['msg'] = '测量点号错误，无法启动！';
                    return json_encode($returnArr);
                }
                break;
            case 'AC_DC': //交直流
                if($mpn != 8 && $mpn != 2){
                    $returnArr['msg'] = '测量点号错误，无法启动！';
                    return json_encode($returnArr);
                }
                break;
            case 'DC_DC': //双直流
                if($mpn != 8 && $mpn != 4){
                    $returnArr['msg'] = '测量点号错误，无法启动！';
                    return json_encode($returnArr);
                }
                break;
            case 'AC_AC': //双交流
                if($mpn != 2 && $mpn != 1){
                    $returnArr['msg'] = '测量点号错误，无法启动！';
                    return json_encode($returnArr);
                }
                break;
            default:
                $returnArr['msg'] = '电桩没有配置类型，无法启动！';
                return json_encode($returnArr);
        }
        //查询前置机信息
        $frontMachineInfo = ChargeFrontmachine::find()
            ->select(['addr','port'])
            ->where(['id'=>$poleInfo['fm_id']])
            ->asArray()->one();
        if(!$frontMachineInfo){
            $returnArr['msg'] = '无法查询到前置机！';
            return json_encode($returnArr);
        }
        //查询会员信息
        $vipInfo = Vip::find()
            ->select(['id','money_acount'])
            ->where(['mobile'=>$mobile])
            ->asArray()->one();
        if(!$vipInfo){
            $returnArr['msg'] = '会员不存在！';
            return json_encode($returnArr);
        }
        if($vipInfo['money_acount'] <= 0){
            $returnArr['msg'] = '余额不足，请先充值！';
            return json_encode($returnArr);
        }
        //添加充电记录
        $vcrModel = new VipChargeRecord;
        $vcrModel->number = str_replace('.','',uniqid('vcr',true));
        $vcrModel->vip_id = $vipInfo['id'];
        $vcrModel->pole_id = $poleInfo['id'];
        $vcrModel->measuring_point = $mpn;
        $vcrModel->write_datetime = date('Y-m-d H:i:s');
        $vcrModel->start_status = 'timeout';
        $vcrModel->pay_status = 'wait_pay';
        $vcrModel->last_gun_status = 5;
        $vcrModel->fm_id = $poleInfo['fm_id'];
        if(!$vcrModel->save(false)){
            $returnArr['msg'] = '新建充电订单失败！';
            return json_encode($returnArr);
        }
        //启动电桩
		$fm = new \backend\classes\FrontMachine($frontMachineInfo['addr'].':'.$frontMachineInfo['port']);
        $cardNo = '999'.str_pad($vipInfo['id'],13,'0',STR_PAD_LEFT);
        //启动电枪使用电枪的测量点号
        $actionResult = $fm->start($poleInfo['logic_addr'],$cardNo,$mpn,$vipInfo['money_acount']);
        //解析返回数据
        if(isset($actionResult['actionTarget'])){
            $returnArr['actionTarget'] = $actionResult['actionTarget'];
        }
        if($actionResult['result'] == 'success'){
            $returnArr['error'] = 0;
            $returnArr['msg'] = '操作成功！';
            $returnArr['data']['vcr_id'] = $vcrModel->id;//充电记录id
            $returnArr['data']['write_datetime'] = $vcrModel->write_datetime;
            VipChargeRecord::updateAll(['start_status'=>'success'],['id'=>$vcrModel->id]);
        }else{
            $returnArr['error'] = 1;
            $returnArr['msg'] = '操作失败，'.$actionResult['reason'].'！';
            //更新充电记录启动状态
            VipChargeRecord::updateAll(['start_status'=>'fail','start_fail_reason'=>$actionResult['reason']],['id'=>$vcrModel->id]);
        }
        return json_encode($returnArr);
    }

    /**
     * 获取当前正在充电的车辆的基本信息
     * act: charge_car-status
     */
    public function actionCarStatus(){
        $returnArr = [
            'error'=>1,
            'msg'=>'',
            'data'=>[
                'vcr_id'=>'',//充电记录id
                //'VOLT_MEAS'=>'',
                //'CURR_MEAS'=>'',
                'start_time'=>'',//开始充电时间
                'soc'=>0,//当前电量
                //'MAX_SINGLE_VOLT'=>'',//最大电压
                //'WRITE_TIME'=>'',
                'estimate_time'=>'',//预计完成时间
                'already_time'=>'',//已经使用的时间
                'charge_complete'=>0,//充电是否已经自动完成
                'code_from_compony'=>'',//充电桩编号
                'logic_addr'=>'',//电桩逻辑地址
                'charge_type'=>'',//充电桩类型
                'charge_type_text'=>'',
                'charge_pattern'=>'',//充电桩充电模式
                'charge_pattern_text'=>'',//充电桩充电模式
                'connection_type'=>'',//充电桩连接标准
                'connection_type_text'=>'',
                'cs_name'=>'',//充电站名称
                'gun_name'=>'',//当前枪名称
                //'gun_code'=>'',//当前枪编号
                'gun_type'=>'',//电枪类型
                'gun_type_text'=>'',//电枪类型
                'charge_electricity'=>0,//已充电量
            ]
        ];
        //接收参数开始
        $vcrId = isset($_REQUEST['vcr_id']) ? $_REQUEST['vcr_id'] : 0;
        $mobile = isset($_REQUEST['mobile']) ? $_REQUEST['mobile'] : 0;
        if(!$vcrId || !$mobile){
            $returnArr['msg'] = '缺少必要参数！';
            return json_encode($returnArr);
        }
        //接收参数结束
        //查询会员信息
        $vipInfo = Vip::find()
            ->select(['id'])
            ->where(['mobile'=>$mobile])
            ->asArray()->one();
        if(!$vipInfo){
            $returnArr['msg'] = '必要参数错误！';
            return json_encode($returnArr);
        }
        //查询充值订单数据
        $vcrInfo = VipChargeRecord::find()
            ->select([
                'id',
                'pole_id',
                'measuring_point',
                'last_gun_status',
                'write_datetime',
                'fm_start_id',
            ])->where([
                'vip_id'=>$vipInfo['id'],
                'id'=>$vcrId,
                'start_status'=>'success',
                'pay_status'=>'wait_pay'
            ])->asArray()->one();
        //echo '<pre>';
        //var_dump($vcrInfo);
        if(!$vcrInfo){
            $returnArr['msg'] = '无法查询到您的充电订单记录！';
            return json_encode($returnArr);
        }
        //查询电桩信息
        $poleInfo = ChargeSpots::find()
            ->select([
                '{{%charge_spots}}.`id`',
                '{{%charge_spots}}.`code_from_compony`',
                '{{%charge_spots}}.`charge_type`',
                '{{%charge_spots}}.`charge_pattern`',
                '{{%charge_spots}}.`connection_type`',
                '{{%charge_spots}}.`logic_addr`',
                '{{%charge_spots}}.`fm_id`',
                '{{%charge_station}}.`cs_name`',
            ])->joinWith('chargeStation',false)
            ->where(['{{%charge_spots}}.`id`'=>$vcrInfo['pole_id']])
            ->asArray()->one();
        if(!$poleInfo){
            $returnArr['msg'] = '无法查询到电桩信息！';
            return $returnArr;
        }
        //获取配置项
        $configItem = ['charge_type','charge_pattern','connection_type'];
        $config = (new ConfigCategory)->getCategoryConfig($configItem,'value');
        unset($configItem);
        //添加返回参数
        $returnArr['data']['vcr_id'] = $vcrInfo['id'];
        $returnArr['data']['start_time'] = $vcrInfo['write_datetime'];
        //计算默认的已经充电的时间
        $useTime = time() - strtotime($vcrInfo['write_datetime']);
        $alreadyTimeH = intval($useTime / 3600);
        $alreadyTimeM = intval(($useTime - ($alreadyTimeH * 3600)) / 60);
        $alreadyTimeS = ($useTime - ($alreadyTimeH * 3600)) % 60;
        $returnArr['data']['already_time'] = $alreadyTimeH.'时 '.$alreadyTimeM.'分 '.$alreadyTimeS.'秒';
        $returnArr['data']['code_from_compony'] = $poleInfo['code_from_compony'];
        $returnArr['data']['logic_addr'] = $poleInfo['logic_addr'];
        $returnArr['data']['charge_type'] = $poleInfo['charge_type'];
        if(isset($config['charge_type'][$poleInfo['charge_type']])){
            $returnArr['data']['charge_type_text'] = $config['charge_type'][$poleInfo['charge_type']]['text'];
        }else{
            $returnArr['data']['charge_type_text'] = '';
        }
        $returnArr['data']['charge_pattern'] = $poleInfo['charge_pattern'];
        if(isset($config['charge_pattern'][$poleInfo['charge_pattern']])){
            $returnArr['data']['charge_pattern_text'] = $config['charge_pattern'][$poleInfo['charge_pattern']]['text'];
        }else{
            $returnArr['data']['charge_pattern_text'] = '';
        }
        $returnArr['data']['connection_type'] = $poleInfo['connection_type'];
        if(isset($config['connection_type'][$poleInfo['connection_type']])){
            $returnArr['data']['connection_type_text'] = $config['connection_type'][$poleInfo['connection_type']]['text'];
        }else{
            $returnArr['data']['connection_type_text'] = '';
        }
        $returnArr['data']['cs_name'] = $poleInfo['cs_name'];
        $returnArr['data']['gun_code'] = $poleInfo['code_from_compony'].$vcrInfo['measuring_point'];
        unset($config);
        //链接前置机数据库
        $connectRes = ChargeFrontmachine::connect($poleInfo['fm_id']);
        if(!$connectRes[0]){
            $returnArr['msg'] = $connectRes[1];
            return json_encode($returnArr);
        }
        //查询电桩在前置机数据库中的id
        $fmPoleInfo = (new \yii\db\Query())
            ->select(['DEV_ID'])
            ->from('charge_pole')
            ->where(['DEV_ADDR'=>$poleInfo['logic_addr']])->one($connectRes[1]);
        if(!$fmPoleInfo){
            $returnArr['msg'] = '前置机数据库没有查询到电桩数据！';
            return json_encode($returnArr);
        }
        //判断当前枪的测量点与枪类型
        $measuringPoint = 0;
        switch ($poleInfo['charge_type']) {
            case 'DC': //单直流
                $measuringPoint = 3;
                $returnArr['data']['gun_type'] = 'DC';
                $returnArr['data']['gun_type_text'] = '直流';
                break;
            case 'AC': //单交流
                $measuringPoint = 1;
                $returnArr['data']['gun_type'] = 'AC';
                $returnArr['data']['gun_type_text'] = '交流';
                break;
            case 'AC_DC': //交直流
                switch ($vcrInfo['measuring_point']) {
                    case 8:
                        $measuringPoint = 3;
                        $returnArr['data']['gun_name'] = 'A';
                        $returnArr['data']['gun_type'] = 'DC';
                        $returnArr['data']['gun_type_text'] = '直流';
                        break;
                    case 2:
                        $measuringPoint = 1;
                        $returnArr['data']['gun_name'] = 'B';
                        $returnArr['data']['gun_type'] = 'AC';
                        $returnArr['data']['gun_type_text'] = '交流';
                        break;
                }
                break;
            case 'DC_DC': //双直流
                switch ($vcrInfo['measuring_point']) {
                    case 8:
                        $measuringPoint = 3;
                        $returnArr['data']['gun_name'] = 'A';
                        break;
                    case 4:
                        $measuringPoint = 2;
                        $returnArr['data']['gun_name'] = 'B';
                        break;
                }
                $returnArr['data']['gun_type'] = 'DC';
                $returnArr['data']['gun_type_text'] = '直流';
                break;
            case 'AC_AC': //双交流
                switch ($vcrInfo['measuring_point']) {
                    case 2:
                        $measuringPoint = 1;
                        $returnArr['data']['gun_name'] = 'A';
                        break;
                    case 1:
                        $measuringPoint = 0;
                        $returnArr['data']['gun_name'] = 'B';
                        break;
                }
                $returnArr['data']['gun_type'] = 'AC';
                $returnArr['data']['gun_type_text'] = '交流';
                break;
            default:
                $returnArr['msg'] = '未知的电桩类型！';
                return $returnArr;
        }
        //获取当前枪口状态
        $gunStatus = (new \yii\db\Query())
            ->select([
                '`STATUS`',
            ])->from('charge_status')
            ->where([
                '`DEV_ID`'=>$fmPoleInfo['DEV_ID'],
                '`INNER_ID`'=> $measuringPoint,
            ])->one($connectRes[1]);
        //var_dump($gunStatus);
        //var_dump($vcrInfo);
        if($gunStatus && isset($gunStatus['STATUS']) && $gunStatus['STATUS'] == 0 && $vcrInfo['last_gun_status'] != 0){
            //第一次检测到枪口状态变成充电中
            VipChargeRecord::updateAll(['last_gun_status'=>0],['id'=>$vcrInfo['id']]);
        }
        if($gunStatus && isset($gunStatus['STATUS']) && $gunStatus['STATUS'] == 1 && $vcrInfo['last_gun_status'] == 0 ){
            //枪口状态从充电中变成其它状态
            $returnArr['error'] = 0;
            $returnArr['data']['charge_complete'] = 1;
            return json_encode($returnArr);
        }
        //如果是双直流可能有AB轮充情况出现（5秒后检测）
        if($poleInfo['charge_type'] == 'DC_DC' && (time() - strtotime($vcrInfo['write_datetime']) > 5) ){
            //查询当前枪口是否已经启动
            $gunStatus = (new \yii\db\Query())
                ->select([
                    '`STATUS`',
                    '`INNER_ID`',
                ])->from('charge_status')
                ->where([
                    '`DEV_ID`'=>$fmPoleInfo['DEV_ID'],
                    //'`INNER_ID`'=> $measuringPoint,
                ])->indexBy('INNER_ID')->all($connectRes[1]);
            $otherMeasuringPoint = 5 - $measuringPoint;
            if(
                $gunStatus
                &&
                isset($gunStatus[$measuringPoint])
                &&
                $gunStatus[$measuringPoint]['STATUS'] == 1
                &&
                isset($gunStatus[$otherMeasuringPoint])
                &&
                $gunStatus[$otherMeasuringPoint]['STATUS'] == 0
            ){
                //双直流提示等待另一枪结束
                $returnArr['error'] = 2;
                $returnArr['msg'] = '充电任务将在另一个充电枪结束充电后开始';
                $returnArr['data']['start_time'] = '充电还没有开始';
                $returnArr['data']['already_time'] = '充电还没有开始';
                return json_encode($returnArr);
            }
        }
        //如果枪是交流则获取当前已充电度
        if($returnArr['data']['gun_type'] == 'AC'){
            $moniData = (new \yii\db\Query())
                ->select(['CHARGE_AMOUNT'])
                ->from('charging')
                ->where('DEV_ID = :DEV_ID AND INNER_ID = :INNER_ID AND WRITE_TIME > :WRITE_TIME',[
                    'DEV_ID'=>$fmPoleInfo['DEV_ID'],
                    'INNER_ID'=>$measuringPoint,
                    'WRITE_TIME'=>$vcrInfo['write_datetime'],
                ])->orderby('`WRITE_TIME` desc')->one($connectRes[1]);
            if($moniData){
                $returnArr['data']['charge_electricity'] = sprintf('%.2f',$moniData['CHARGE_AMOUNT']);
            }
        }
        //app启动成功5秒后查询本记录的开始充电记录
        if(!$vcrInfo['fm_start_id'] && (time() - strtotime($vcrInfo['write_datetime']) > 5)){
            $startCardNo = '999'.str_pad($vipInfo['id'],13,'0',STR_PAD_LEFT);
            $startRecord = (new \yii\db\Query())
                ->select(['ID','DEV_ID','DEAL_NO'])
                ->from('charge_record')
                ->where('`DEV_ID` = :DEV_ID and `INNER_ID` = :INNER_ID and WRITE_TIME >= :WRITE_TIME AND `DEAL_TYPE` = 0 AND START_CARD_NO = :START_CARD_NO',[
                    'DEV_ID'=>$fmPoleInfo['DEV_ID'],
                    'INNER_ID'=>$measuringPoint,
                    'WRITE_TIME'=>$vcrInfo['write_datetime'],
                    'START_CARD_NO'=>$startCardNo,
                ])->orderby('`WRITE_TIME` asc')->one($connectRes[1]);
            if(!$startRecord){
                $returnArr['error'] = 3;
                $returnArr['msg'] = '没有查询到开始充电记录，请检查电枪是否插好！';
                return json_encode($returnArr);
            }
            VipChargeRecord::updateAll([
                'fm_start_id'=>$startRecord['ID'],
                'fm_charge_no'=>$startRecord['DEAL_NO']
            ],[
                'id'=>$vcrInfo['id']
            ]);
        }
        //第一条电池数据监控记录
        $firstBatteryRecord = (new \yii\db\Query())
            ->select(['SOC','WRITE_TIME'])
            ->from('battery_infos')
            ->where('`DEV_ID` = :DEV_ID and `INNER_ID` = :INNER_ID and WRITE_TIME >= :WRITE_TIME',[
                'DEV_ID'=>$fmPoleInfo['DEV_ID'],
                'INNER_ID'=>$measuringPoint,
                'WRITE_TIME'=>$vcrInfo['write_datetime'],
            ])->orderby('`WRITE_TIME` asc')->one($connectRes[1]);
        //最新电池数据监控记录
        $lastBatteryRecord = (new \yii\db\Query())
            ->select(['SOC','WRITE_TIME'])
            ->from('battery_infos')
            ->where('`DEV_ID` = :DEV_ID and `INNER_ID` = :INNER_ID and WRITE_TIME >= :WRITE_TIME',[
                'DEV_ID'=>$fmPoleInfo['DEV_ID'],
                'INNER_ID'=>$measuringPoint,
                'WRITE_TIME'=>$vcrInfo['write_datetime'],
            ])->orderby('`WRITE_TIME` desc')->one($connectRes[1]);
        //充电时间预估
        if($firstBatteryRecord && $lastBatteryRecord){
            //获取当前充电电量
            $returnArr['data']['soc'] = intval($lastBatteryRecord['SOC']);
            if(
                $returnArr['data']['soc'] == 100
                || 
                (
                    $returnArr['data']['soc'] == 99
                    && isset($gunStatus)
                    && isset($gunStatus[$measuringPoint])
                    && $gunStatus[$measuringPoint]['STATUS'] == 1
                )
            ){
                //充电完成
                $returnArr['error'] = 0;
                $returnArr['data']['charge_complete'] = 1;
                return json_encode($returnArr);
            }
            //计算预计结束时间
            $useTime = strtotime($lastBatteryRecord['WRITE_TIME']) - strtotime($firstBatteryRecord['WRITE_TIME']);
            $chargeSOC = $lastBatteryRecord['SOC'] - $firstBatteryRecord['SOC'];
            $secPreSoc = $chargeSOC > 0 ? $useTime / $chargeSOC : 0;
            $returnArr['data']['estimate_time'] = intval($secPreSoc * (100 - $lastBatteryRecord['SOC']));
            if($returnArr['data']['estimate_time'] > 0){
                $returnArr['data']['estimate_time'] = date('Y-m-d H:i:s',time()+$returnArr['data']['estimate_time']);
            }else{
                $returnArr['data']['estimate_time'] = '';
            }
            //开始充电时间
            $returnArr['data']['start_time'] = $firstBatteryRecord['WRITE_TIME'];
            //已经充电的时间
            $alreadyTimeH = intval($useTime / 3600);
            $alreadyTimeM = intval(($useTime - ($alreadyTimeH * 3600)) / 60);
            $alreadyTimeS = ($useTime - ($alreadyTimeH * 3600)) % 60;
            $returnArr['data']['already_time'] = $alreadyTimeH.'时 '.$alreadyTimeM.'分 '.$alreadyTimeS.'秒';
        }
        $returnArr['error'] = 0;
        return json_encode($returnArr);
    }
	
    /**
     *  结束充电
     *  charge_end-charge
     */
    public function actionEndCharge(){
        set_time_limit(300);
        $datas = [
            "error"=>1,
            'msg'=>'',
        ];
        //return json_encode($datas);
        //接收参数开始
        $mobile = isset($_REQUEST['mobile'])  ? trim($_REQUEST['mobile']) : '';
        $vcrId = isset($_REQUEST['vcr_id'])  ? trim($_REQUEST['vcr_id']) : '';
        //检测参数是否完整
        if(!$mobile || !$vcrId){
            $datas['msg'] = '缺少必要参数！';
            return json_encode($datas);
        }
        //接收参数结束
        //查询会员信息
        $vipInfo = Vip::find()
            ->select(['id'])
            ->where(['mobile'=>$mobile])
            ->asArray()->one();
        //查询充电订单数据
        $vcrInfo = VipChargeRecord::find()
            ->select([
                'id','pole_id','measuring_point','write_datetime',
                'fm_start_id',
            ])->where(['vip_id'=>$vipInfo['id'],'id'=>$vcrId])
            ->asArray()->one();
        if(!$vcrInfo){
            $datas['msg'] = '未找到对应的充电记录！';
            return json_encode($datas);
        }
        //查询电桩信息
        $poleInfo = ChargeSpots::find()
            ->select(['id','fm_id','logic_addr','charge_type'])
            ->where(['id'=>$vcrInfo['pole_id']])
            ->asArray()->one();
        //获取前置机信息
        $frontMachineInfo = ChargeFrontmachine::find()
            ->select(['addr','port'])
            ->where(['id'=>$poleInfo['fm_id']])
            ->asArray()->one();
        //停止充电
        $fm = new \backend\classes\FrontMachine($frontMachineInfo['addr'].':'.$frontMachineInfo['port']);
        $cardNo = '999'.str_pad($vipInfo['id'],13,'0',STR_PAD_LEFT);
		$actionResult = $fm->stop($poleInfo['logic_addr'],$cardNo,$vcrInfo['measuring_point']);
        //解析返回数据
        if($actionResult['result'] == 'success'){
            $datas['msg'] = '操作成功！';
            //$datas['data']['c_amount'] = $actionResult['c_amount'];//充电记录id
            //更新充电记录启动状态
            VipChargeRecord::updateAll(['end_status'=>'success','end_datetime'=>date('Y-m-d H:i:s')],['id'=>$vcrInfo['id']]);
        }else{
            $datas['msg'] = '操作失败['.$actionResult['reason'].']！';
            //更新充电记录启动状态
            VipChargeRecord::updateAll(['end_status'=>'fail','end_datetime'=>date('Y-m-d H:i:s')],['id'=>$vcrInfo['id']]);
        }
        //链接前置机结束本次充电
        if(!$vcrInfo['fm_start_id']){
            $datas['msg'] = '无法获取充电开始记录，请确认充电是否已经开始！';
            return json_encode($datas);
        }
        $connectRes = ChargeFrontmachine::connect($poleInfo['fm_id']);
        if(!$connectRes[0]){
            $datas['msg'] = $connectRes[1];
            return json_encode($datas);
        }
        //查询前置机开始充电记录
        $startRecord = (new \yii\db\Query())
            ->select(['ID','DEV_ID','DEAL_NO','START_CARD_NO','INNER_ID'])
            ->from('charge_record')
            ->where(['ID'=>$vcrInfo['fm_start_id']])->one($connectRes[1]);
        $tryTimes = 2;
        do{
            //查询前置机结束充电记录
            $endRecord = (new \yii\db\Query())
                ->select([
                    'ID','REMAIN_BEFORE_DEAL',
                    'REMAIN_AFTER_DEAL','DEAL_NO'
                ])->from('charge_record')
                ->where('ID > :ID AND DEV_ID = :DEV_ID AND DEAL_NO = :DEAL_NO AND END_CARD_NO = :END_CARD_NO AND INNER_ID = :INNER_ID AND (`DEAL_TYPE` = 1 OR `DEAL_TYPE` = 2)',[
                    ':ID'=>$startRecord['ID'],
                    ':DEV_ID'=>$startRecord['DEV_ID'],
                    ':DEAL_NO'=>$startRecord['DEAL_NO'],
                    ':END_CARD_NO'=>$startRecord['START_CARD_NO'],
                    ':INNER_ID'=>$startRecord['INNER_ID'],
                ])->one($connectRes[1]);
            if($endRecord){
                break;
            }
            $tryTimes --;
            //sleep(2);
        }while($tryTimes > 0);
        if(!$endRecord){
            $datas['msg'] = '电桩通讯故障结算失败，通信恢复后将自行结算电费！';
            return json_encode($datas);
        }
        $money = sprintf('%.2f',$endRecord['REMAIN_BEFORE_DEAL'] - $endRecord['REMAIN_AFTER_DEAL']);
        $payCharge = (new VipChargeRecord)->payCharge($vcrInfo['id'],0,$money,$poleInfo['fm_id'],$startRecord['ID'],$endRecord['ID'],$endRecord['DEAL_NO']);
        if(!$payCharge){
            $datas['msg'] = '结算失败，系统稍后将自动结算！';
            return json_encode($datas);
        }
        $datas['error'] = 0;
        $datas['msg'] = '操作成功！';
        $datas['data']['fm_id'] = $poleInfo['fm_id'];
        $datas['data']['fm_end_id'] = $endRecord['ID'];
        return json_encode($datas); 
    }

    /**
     * 获取APP启动记录
     * charge_start-stop-record
     */
    /*public function actionStartStopRecord(){

    }*/
	
	
    /**
     *  获取充电记录
     *  charge_get-charge-record
     */
    public function actionGetChargeRecord(){
        $returnArr = [
            'error'=>1,
            'msg'=>'',
            'data'=>[],
        ];
        $_mobile  = isset($_REQUEST['mobile'])  ? trim($_REQUEST['mobile']) : '';
        $vip_id = Vip::getVipIdByPhoneNumber($_mobile);
        if(!$vip_id){
            $returnArr['msg'] = '会员不存在！';
            return json_encode($returnArr);
        }
        $connectRes = ChargeFrontmachine::connect();
        if(!$connectRes[0]){
            $returnArr['msg'] = $connectRes[1];
            return json_encode($returnArr);
        }
        $cardNo = '999'.str_pad($vip_id,13,'0',STR_PAD_LEFT);
        $query = (new \yii\db\Query())
            ->select([
                'ID','charge_record.DEV_ID','DEAL_NO','DEAL_START_DATE','START_CARD_NO'
            		,'charge_pole.CS_ID'
            ])->from('charge_record')
            ->join('LEFT JOIN','charge_pole','charge_pole.DEV_ID = charge_record.DEV_ID')
            ->where(['START_CARD_NO'=>$cardNo,'DEAL_TYPE'=>0]);
        $total = $query->count('*',$connectRes[1]);
        //分页
        $size = isset($_REQUEST['rows']) && $_REQUEST['rows'] <= 50 ? intval($_REQUEST['rows']) : 10;
        $page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
        $startRecord = $query->offset(($page-1)*$size)->limit($size)->orderby('ID desc')->all($connectRes[1]);
        if(!$startRecord){
            $returnArr['error'] = 1;
            if($page == 1){
                $returnArr['msg'] = '没有找到任何充电记录！';
            }else{
                $returnArr['msg'] = '没有更多数据了！';
            }
            return json_encode($returnArr);
        }
        unset($query);
        $endRecordColumn = [
            'ID','DEAL_TYPE','DEV_ID','DEAL_NO',
            'DEAL_START_DATE','DEAL_END_DATE','REMAIN_BEFORE_DEAL',
            'REMAIN_AFTER_DEAL'
        ];
        foreach($startRecord as $val){
            if(!isset($query)){
                $query = (new \yii\db\Query())
                    ->select($endRecordColumn)
                    ->from('charge_record')
                    ->where("`ID` > {$val['ID']} AND `DEAL_TYPE` IN (1,2) AND `DEV_ID` = {$val['DEV_ID']} AND `DEAL_NO` = '{$val['DEAL_NO']}' AND START_CARD_NO = '{$val['START_CARD_NO']}'");
            }else{
                $queryTmp = (new \yii\db\Query())
                    ->select($endRecordColumn)
                    ->from('charge_record')
                    ->where("`ID` > {$val['ID']} AND `DEAL_TYPE` IN (1,2) AND `DEV_ID` = {$val['DEV_ID']} AND `DEAL_NO` = '{$val['DEAL_NO']}' AND START_CARD_NO = '{$val['START_CARD_NO']}'");
                $query->union($queryTmp);
            }
        }
        $endRecord = $query->all($connectRes[1]);
        unset($query);
        unset($queryTmp);
        if($endRecord){
            foreach($endRecord as $key=>$val){
                $keyIndex = $val['DEV_ID'].'-'.$val['DEAL_NO'];
                $endRecord[$keyIndex] = $val;
                unset($endRecord[$key]);
            }
        }
        foreach($startRecord as $key=>$val){
            $startRecord[$key]['fm_id'] = 1;
            $startRecord[$key]['fm_end_id'] = 0;
            $startRecord[$key]['c_amount'] = '';
            $keyIndex = $val['DEV_ID'].'-'.$val['DEAL_NO'];
            if(isset($endRecord[$keyIndex])){
            	$startRecord[$key]['DEAL_END_DATE'] = $endRecord[$keyIndex]['DEAL_END_DATE'];
                $startRecord[$key]['fm_end_id'] = $endRecord[$keyIndex]['ID'];
                $startRecord[$key]['c_amount'] = $endRecord[$keyIndex]['REMAIN_BEFORE_DEAL'] - $endRecord[$keyIndex]['REMAIN_AFTER_DEAL'];
                $startRecord[$key]['c_amount'] = sprintf('%.2f',$startRecord[$key]['c_amount']);
               	if($endRecord[$keyIndex]['DEAL_TYPE'] == 1){
                    $startRecord[$key]['status'] = 'success';
                    $startRecord[$key]['status_text'] = '正常结束';
                }else{
                    $startRecord[$key]['status'] = 'exception';
                    $startRecord[$key]['status_text'] = '异常结束';
                }
                
                //查询出充电站名称
                $charge_station = (new \yii\db\Query())->select('CS_NAME')->from('charge_station')->where('CS_ID=:CS_ID',[':CS_ID'=>$val['CS_ID']])->one($connectRes[1]);
                $startRecord[$key]['cs_name'] = $charge_station['CS_NAME'];
            }else{
                //正在充电
                //$startRecord[$key]['status'] = 'charging';
                //$startRecord[$key]['status_text'] = '正在充电';
                unset($startRecord[$key]);
            }
            
           

        }
        
       
        
        $startRecord = array_values($startRecord);
        $returnArr['error'] = 0;
        $returnArr['data'] = $startRecord;
        $returnArr['total'] = $total;
        return json_encode($returnArr);
    }


    /**
     * 查看充电记录详情
     * action: charge_detail
     */
    public function actionDetail(){
        $returnArr = [
            'error'=>1,
            'msg'=>'',
            'data'=>[],
        ];
        $mobile = isset($_REQUEST['mobile']) ? trim($_REQUEST['mobile']) : '';// 手机号
        $fmId = isset($_REQUEST['fm_id']) ? trim($_REQUEST['fm_id']) : '';
        $fmEndId = isset($_REQUEST['fm_end_id']) ? trim($_REQUEST['fm_end_id']) : '';
        //检测参数是否完整
        if(!$mobile || !$fmId || !$fmEndId){
            $returnArr['msg'] = '订单异常，请等待系统结算！';
            return json_encode($returnArr);
        }
        //查询会员信息
        $vipInfo = Vip::find()
            ->select(['id'])
            ->where(['mobile'=>$mobile])
            ->asArray()->one();
        if(!$vipInfo){
            $returnArr['msg'] = '会员不存在！';
            return json_encode($returnArr);
        }
        $connectRes = ChargeFrontmachine::connect($fmId);
        if(!$connectRes[0]){
            $returnArr['msg'] = $connectRes[1];
            return json_encode($returnArr);
        }
        $cardNo = '999'.str_pad($vipInfo['id'],13,'0',STR_PAD_LEFT);
        $endRecord = (new \yii\db\Query())
            ->select([
                'DEAL_NO','DEAL_START_DATE','DEAL_END_DATE',
                'START_DEAL_DL','END_DEAL_DL',
                'STOP_FEE_PRICE','STOP_FEE',
                'REMAIN_BEFORE_DEAL','REMAIN_AFTER_DEAL'
            	,'charge_pole.DEV_ADDR','charge_pole.CS_ID'
            ])->from('charge_record')
            ->join('LEFT JOIN','charge_pole','charge_pole.DEV_ID = charge_record.DEV_ID')
            ->where(['START_CARD_NO'=>$cardNo,'ID'=>$fmEndId])->one($connectRes[1]);
        $connectRes[1]->close();
        if(!$endRecord){
            $returnArr['msg'] = '记录不存在！';
            return json_encode($returnArr);
        }
        //查询出充电站名称
        $charge_station = (new \yii\db\Query())->select('CS_NAME')->from('charge_station')->where('CS_ID=:CS_ID',[':CS_ID'=>$endRecord['CS_ID']])->one($connectRes[1]);
        
        $returnArr['data'] = $endRecord;
        $returnArr['data']['c_amount'] = sprintf('%.2f',$endRecord['REMAIN_BEFORE_DEAL'] - $endRecord['REMAIN_AFTER_DEAL']);
        $returnArr['data']['c_deal_dl'] = sprintf('%.2f',$endRecord['END_DEAL_DL'] - $endRecord['START_DEAL_DL']);//用电量
        $returnArr['data']['service_rate'] = '0.00';//服务费
        $returnArr['data']['cs_name'] = !empty($charge_station) ? $charge_station['CS_NAME']:'';//充电站名称
        $returnArr['error'] = 0;
        return json_encode($returnArr);
    }
	
}