<?php
namespace Protocols;
/**
 * 地上铁-航天无线通信协议
 */
class DstCarHangTianWuXian
{
    /**
     * 检查包的完整性
     * 如果能够得到包长，则返回包的在buffer中的长度，否则返回0继续等待数据
     * 如果协议有问题，则可以返回false，当前客户端连接会因此断开
     * @param string $buffer
     * @return int
     */
    public static function input($buffer)
    {
        $packType = [
            'startMark'=>'a2',//数据包起始符（2字节##）
            'commandSingle'=>'C1',//命令标识（1字节）
            'commandAnswer'=>'C1',//命令应答标识（1字节）
            'carVIN'=>'a17',//车辆vin码或充电站编码+充电桩编码(17字节)
            'dataEncryptionWay'=>'C1',//数据加密方式（1字节8位）
            'dataLength'=>'n1',//数据长度（2字节16位0-65534）
            'data'=>'',//数据内容等待接收到数据后确定
            'checkCode'=>'a1',//校验码（1字节8位）
        ];
        //数据包头上27个字符/字节
        if(strlen($buffer) < 24){
            //数据包头不完整继续等待数据
            return 0;
        }
        //头数据接收完成判断当前数据有多长
        $format = '';//用于解析本包长度
        foreach($packType as $key=>$val){
            if($key == 'data'){
                continue;
            }
            $format .= $val.$key.'/';
        }
        $format = rtrim($format,'/');
        try{
            $data = unpack($format,$buffer);
            print_r($data);
        }catch(\Exception $e){
            //出现异常踢掉当前连接用户
            //return false;
        }
        $packType['data'] = 'a'.intval($data['dataLength']);
        //返回本包长
        return 25 + $data['dataLength'];
    }

    /**
     * 打包，当向客户端发送数据的时候会自动调用
     * @param string $buffer
     * @return string
     */
    public static function encode($buffer)
    {
        $packType = [
            'startMark'=>'a2',//数据包起始符（2字节##）
            'commandSingle'=>'C1',//命令标识（1字节）
            'commandAnswer'=>'C1',//命令应答标识（1字节）
            'carVIN'=>'a17',//车辆vin码或充电站编码+充电桩编码(17字节)
            'dataEncryptionWay'=>'C1',//数据加密方式（1字节8位）
            'dataLength'=>'n1',//数据长度（2字节16位0-65534）
            'data'=>'',//数据内容等待接收到数据后确定
            'checkCode'=>'a1',//校验码（1字节8位）
        ];
        $dataToPackage = [
            'startMark'=>'##',//数据包起始符（2字节##）
            'commandSingle'=>$buffer['commandSingle'],//命令标识（1字节）
            'commandAnswer'=>$buffer['commandAnswer'],//命令应答标识（1字节）
            'carVIN'=>$buffer['carVIN'],//车辆vin码或充电站编码+充电桩编码(17字节)
            'dataEncryptionWay'=>0x01,//数据加密方式（1字节8位）
            'dataLength'=>strlen($buffer['data']),//数据长度（2字节16位0-65534）
            'data'=>$buffer['data'],//数据内容等待接收到数据后确定
            'checkCode'=>'x',//校验码（1字节8位）
        ];
        $packType['data'] = 'a'.strlen($buffer['data']);
        $format = '';
        foreach($packType as $key=>$val){
            $format .= $val.$key.'/';
        }
        $format = rtrim($format,'/');
        $data = self::packStruct($format,$dataToPackage);
        //var_dump(bin2hex($data));
//         print_r(bin2hex($data));
        return $data;
    }

    /**
     * 解包，当接收到的数据字节数等于input返回的值（大于0的值）自动调用
     * 并传递给onMessage回调函数的$data参数
     * @param string $buffer
     * @return string
     */
    public static function decode($buffer)
    {
        $packType = [
            'startMark'=>'a2',//数据包起始符（2字节##）
            'commandSingle'=>'C',//命令标识（1字节）
            'commandAnswer'=>'C',//命令应答标识（1字节）
            'carVIN'=>'a17',//车辆vin码或充电站编码+充电桩编码(17字节)
            'dataEncryptionWay'=>'C',//数据加密方式（1字节8位）
            'dataLength'=>'n1',//数据长度（2字节16位0-65534）
            'data'=>'',//数据内容等待接收到数据后确定
            'checkCode'=>'a1',//校验码（1字节8位）
        ];
        $format = '';//用于解析本包长度
        foreach($packType as $key=>$val){
            if($key == 'data'){
                continue;
            }
            $format .= $val.$key.'/';
        }
        $format = rtrim($format,'/');
        $data = unpack($format,$buffer);
        if(isset($data['dataLength'])){
            $packType['data'] = 'a'.intval($data['dataLength']);
        }else{
            //无法解析数据长度踢掉当前连接用户
            return [];
        }
        $format = '';
        foreach($packType as $key=>$val){
            $format .= $val.$key.'/';
        }
        $format = rtrim($format,'/');
        $message = unpack($format,$buffer);
        $message['data_hex'] = bin2hex($buffer);
        //unset($message['startMark']);
        return $message;
    }

    protected static function packStruct($format, $struct){
        $arr = explode('/', $format);
        $pattern = '/([aAhHcCsSnviIlLNVqQJPfdxXZ@][0-9]+)([\S][a-zA-Z_0-9]*)/';
        foreach($arr as $i=>$str){
            $matches = array();
            preg_match($pattern ,$str, $matches);
            $info['fmt'][$i] = $matches[1];
            $info['key'][$i] = $matches[2];
        }
        $count = count($info['key']);
        $content = '';  
        for($i=0; $i<$count; $i++){
            $fmt = $info['fmt'][$i];
            $key = $info['key'][$i];
            $content .= pack($fmt, $struct[$key]);
        }
        return $content;
    }
}