<?php
/**
 * mqtt 协议
 */
class MQTT{
    public function __construct($connection) {
        
    }

    /**
     * 获取头部
     */
    protected static function getHeader($binFixedHeader,$msbLength = 0,$lsbLength = 0) {
        $buffer = '';
        //获取固定头部
        $buffer .= pack('C',bindec($binFixedHeader));
        //获取可变头部
        $buffer .= pack('C',$msbLength);
        $buffer .= pack('C',$lsbLength);
        $buffer .= pack('a6','MQIsdp'); //协议名秒称
        $buffer .= pack('C',3);         //版本号
        $buffer .= pack('C',bindec('11001111')); //链接标志
        $buffer .= pack('C',0);
        $buffer .= pack('C',10);                 //保活计时器
        return $buffer;
    }

    /**
     * 消息解包
     * @return mixed 当return全等于false必需强制重连
     */
    public static function decode($buffer) {
        $unpackData = unpack('Ccommand',$buffer);
        $buffer = substr($buffer,1);
        $command = str_pad(decbin($unpackData['command']),8,'0',STR_PAD_LEFT);
        //var_dump($command);
        switch (substr($command,0,4)) {
            case '1101':
                //ping
                echo 'ping return',"\n";
                return true;
            case '0010':
                return self::decodeConnect($buffer);
            case '0100':
                echo 'message sure',"\n";
                self::decodePuback($buffer);
                return true;

        }
        return true;
    }
    /**
     * 连接确定
     */
    public static function decodeConnect($buffer) {
        $unpackData = unpack('CrLength/Cnouse/CreturnCode',$buffer);
        if($unpackData['returnCode'] == 0){
            echo 'connect useable!',"\n";
            return true;
        }
        return false;
    }

    /**
     * 发布确定
     */
    protected static function decodePuback($buffer) {

    }

    /**
     * 建立链接
     */
    public static function connect() {
        //固定头部
        $fixedHeader = pack('C',bindec('00010000'));
        //可变头部
        $variablHeader = '';
        //协议名称
        $protocolName = 'MQIsdp';
        $variablHeader .= pack('C',0);
        $variablHeader .= pack('C',strlen($protocolName));
        $variablHeader .= pack('a'.strlen($protocolName),$protocolName);
        $variablHeader .= pack('C',3);    //版本号
        $variablHeader .= pack('C',bindec('00001000')); //链接标志
        $variablHeader .= pack('C',0);
        $variablHeader .= pack('C',10);   //保活计时器
        $payload = '';
        //链接ID
        $connectId = str_replace('.','0',uniqid('',true));
        $payload .= pack('C',0);
        $payload .= pack('C',strlen($connectId));
        $payload .= pack('a'.strlen($connectId),$connectId);
        //计算包长度
        $length = strlen($variablHeader) + strlen($payload);
        $rLength = pack('C',$length);
        //var_dump(bin2hex($fixedHeader.$rLength.$variablHeader.$payload));
        return $fixedHeader.$rLength.$variablHeader.$payload;
    }

    /**
     * 销毁链接
     */
    public static function disconnect() {
        //固定头部
        $fixedHeader = pack('C',bindec('11100000'));
        //计算包长度
        return $fixedHeader.pack('C',0);
    }

    /**
     * ping消息
     */
    public static function ping() {
        $fixedHeader = pack('C',bindec('11000000'));
        $fixedHeader .= pack('C',0);
        return $fixedHeader;
    }

    /**
     * 消息发布
     */
    protected static function publish($messageId,$payloadContent) {
        //固定头部
        $fixedHeader = pack('C',bindec('00110010'));
        //可变头部
        $variablHeader = '';
        //主题名称
        $topic = 'httx/input/gpsmsg/test';
        $variablHeader .= pack('C',0);
        $variablHeader .= pack('C',strlen($topic));
        $variablHeader .= pack('a'.strlen($topic),$topic);
        $variablHeader .= pack('C',0);
        $variablHeader .= pack('C',$messageId);
        //有效载荷
        $payload = '';
        $payload .= pack('C',0);
        $payload .= pack('C',strlen($payloadContent));
        $payload .= pack('a'.strlen($payloadContent),$payloadContent);
        var_dump(strlen($payloadContent));
        //计算长度
        $length = strlen($variablHeader) + strlen($payload);
        if($length < 256){
            $rLength = pack('C',$length);
        }else{
            $rLength = pack('n',$length);
        }
        return $fixedHeader.$rLength.$variablHeader.$payload;
    }

    /**
     * 获取车辆位置数据
     */
    public static function getPositionData($messageId,$params){
        $message = [
            2157,
            'gpsmsg',
            2,
            $params['mobile'],
            '20160618132921',
            (float) sprintf("%.6f", $params['longitude_value']),
            (float) sprintf("%.6f", $params['latitude_value']),
            intval($params['speed']),
            intval($params['direction']),
            [0,1,0,0,0,0,0],
            [0.0,0,0.0,0,0.0,0,0.0,0,0.0,0],
            true
        ];
        var_dump($message);
        $sendMessage = gzencode(msgpack_pack($message));
        var_dump(bin2hex(pack('a'.strlen($sendMessage),$sendMessage)));
        return self::publish($messageId,$sendMessage);
    }


}