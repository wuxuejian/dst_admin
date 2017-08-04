<?php
/**
 * 808协议数据转发类
 */
use \Protocols\ShenZhenShunFeng;
//use \GatewayWorker\Lib\Db;
class Transmit{
    /**
     * 数据打包
     * @param int    $msgId        消息id
     * @param string $mobile       终端手机号
     * @param int    $serialNumber 消息流水号
     * @param string $messageData  消息内容[必须是二进制数据]
     */
    protected static function packData($msgId,$mobile,$serialNumber,$messageData) {
        $messageHeader = self::getMessageHeader($msgId,strlen($messageData),$mobile,$serialNumber);
        //计算校验码
        $checkContentArr = str_split(bin2hex($messageHeader . $messageData),2);
        //var_dump($checkContentArr);
        $checkCode = hexdec($checkContentArr[0]) ^ hexdec($checkContentArr[1]);
        foreach($checkContentArr as $key=>$val){
            switch ($key) {
                case 0:
                case 1:
                    break;
                default:
                    $checkCode ^= hexdec($val);
                    break;
            }
        }
        unset($checkContentArr);
        //var_dump($checkCode);
        $checkCode = pack('C',$checkCode);
        //var_dump(bin2hex($checkCode));
        //转义并组装消息
        $message = pack('C',0x7e);
        $escapeArr = str_split(bin2hex($messageHeader . $messageData . $checkCode),2);
        foreach($escapeArr as $val){
            switch ($val) {
                case '7e':
                    $message .= hex2bin('7d').hex2bin('02');
                    break;
                case '7d':
                    $message .= hex2bin('7d').hex2bin('01');
                    break;
                default:
                    $message .= hex2bin($val);
                    break;
            }
        }
        $message .= pack('C',0x7e);
        unset($escapeArr);
        return $message;
    }

    /**
     * 获取消息头
     * @param int     $msgId        消息id
     * @param int     $dataLength   数据长度
     * @param string  $mobile       11位电话号码
     * @param int     $serialNumber 消息序号
     */
    protected static function getMessageHeader($msgId,$dataLength,$mobile,$serialNumber) {
        $header = pack('n',$msgId);
        $header .= self::getMessageAttr($dataLength);
        if(strlen($mobile) > 12){
            $mobile = substr($mobile,-12);
        }else{
            $mobile = str_pad($mobile,12,'0',STR_PAD_LEFT);
        }
        $mobile = str_split($mobile,2);
        foreach($mobile as $val){
            $header .= hex2bin($val);
        }
        $header .= pack('n',$serialNumber);
        //分包消息包封顶请在这里实现
        //分包消息包封顶请在这里实现
        return $header;
    }

    /**
     * 获取消息体属性
     * @param int     $dataLength       数据长度最大长度[0,1023]
     * @param boolean $hasDividePackage 是否有分包
     */
    protected static function getMessageAttr($dataLength,$hasDividePackage = false) {
        if($hasDividePackage){
            $binStr = '001';
        }else{
            $binStr = '000';
        }
        $binStr .= '000';//数据加密方式不加密
        $binStr .= str_pad(decbin(intval($dataLength)),10,'0',STR_PAD_LEFT);
        return pack('n',bindec($binStr));
    }

    //服务方法结束
    //功能方法开始
    /**
     * ping 终端心跳
     * 消息ID：0x0002
     */
    public static function ping($serialNumber,$mobile){
        return self::packData(0x0002,$mobile,$serialNumber,''); //完整手机号89860115811029790550
    }

    /**
     * 获取车辆位置数据
     * 消息ID：0x0200
     */
    public static function getPositionData($serialNumber,$params){
        //位置信息汇报消息体组成：位置基本信息和位置附加信息
        //位置基本信息
        $messageData  = pack('N',bindec('00000000000000000000000000000000'));     //报警标志，DWORD
        $messageData .= pack('N',bindec('00000000000001000000000000000011'));     //状态，DWORD
        $messageData .= pack('N',intval($params['latitude_value'] * pow(10,6)));  //纬度，DWORD，乘以10的6次方，精确到百万分之一度
        $messageData .= pack('N',intval($params['longitude_value'] * pow(10,6))); //经度，DWORD，乘以10的6次方，精确到百万分之一度
        $messageData .= pack('n',0);                                      //高程，WORD，海拔高度，单位为米（m）
        $messageData .= pack('n',intval($params['speed'] * 10));          //速度，WORD，1/10km/h 这什么意思？？？
        $messageData .= pack('n',$params['direction']);                   //方向，WORD
        $time = date('ymdHis',$params['collection_datetime']);            //时间，BCD[6]，YY-MM-DD-hh-mm-ss
        $timeArr = str_split($time,2);
        foreach($timeArr as $val){
            $messageData .= hex2bin($val);
        }
        //位置附加信息项
        //$messageData .= pack('C', 1);        //附加信息ID，1-255
        //$messageData .= pack('C', 0);        //附加信息长度
        //$messageData .= pack('', '');      //附加信息
        //var_dump(bin2hex($messageData));
        return self::packData(0x0200,$params['mobile'],$serialNumber,$messageData);
    }


    /**
     * 终端注册
     * 消息ID：0x0100
     */
/*    public function terminalReg($serialNumber,$carInfo) {
        //var_dump($carInfo);
        $messageData  = pack('n',$carInfo['province_id']);        //省域ID
        $messageData .= pack('n',$carInfo['city_id']);            //市县域ID
        $messageData .= pack('a5',$carInfo['manufacturer_id']);   //制造商ID
        $messageData .= pack('a20',$carInfo['terminer_model']);   //终端型号
        $messageData .= pack('a7',$carInfo['terminer_id']);       //终端ID
        $messageData .= pack('C',0);                              //车牌颜色
        $messageData .= pack('a'.strlen($carInfo['car_vin']),$carInfo['car_vin']); //车架号
        return self::packData(0x0100,$carInfo['mobile'],$serialNumber,$messageData);
    }*/


    /**
     * 注册后保存“鉴权码”到数据库表
     */
/*    public function saveAuthCode($car_vin,$authCode){
        if($car_vin && $authCode){
            $count = Db::instance('db')
                ->update('cs_tcp_transmit_car_sz_sf')
                ->cols(['auth_code'=>$authCode])
                ->where("car_vin = '$car_vin'")
                ->query();
            if($count){
                return true;
            }
        }
        return false;
    }*/


    /**
     * 终端鉴权
     * 消息ID：0x0102
     */
/*    public function terminalAuth($serialNumber,$authCode) {
        $messageData = pack('a20',$authCode);  //鉴权码
        return self::packData(0x0102,'13888888888',$serialNumber,$messageData);
    }*/


    /**
     * 终端注销
     * 消息ID：0x0003
     */
/*    public function terminalLogout() {

    }*/

}