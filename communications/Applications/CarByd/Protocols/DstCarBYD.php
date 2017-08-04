<?php
namespace Protocols;
/**
 * 地上铁-比亚迪汽车通信协议
 */
class DstCarBYD
{

    //数据解析格式
    protected static $packType = [
        'startSingleMark'=>'C1',//固定起始标识符（1字节8位）
        'startMark'=>'a2',//数据包起始符（2字节##）
        'commandSingle'=>'C1',//命令标识（1字节）
        'commandAnswer'=>'C1',//命令应答标识（1字节）
        'serialNumber'=>'n1',//命令流水号（2字节16位）
        'carVIN'=>'a17',//车辆vin码或充电站编码+充电桩编码(17字节)
        'dataEncryptionWay'=>'C1',//数据加密方式（1字节8位）
        'dataLength'=>'n1',//数据长度（2字节16位0-65534）
        'data'=>'',//数据内容等待接收到数据后确定
        'checkCode'=>'a1',//校验码（1字节8位）
        //'endSingleMark'=>'C1'//固定结束标识符（1字节8位）
    ];
    /**
     * 检查包的完整性
     * 如果能够得到包长，则返回包的在buffer中的长度，否则返回0继续等待数据
     * 如果协议有问题，则可以返回false，当前客户端连接会因此断开
     * @param string $buffer
     * @return int
     */
    public static function input($buffer)
    {
        //数据包头上27个字符/字节
        if(strlen($buffer) < 27){
            //数据包头不完整继续等待数据
            return 0;
        }
        //头数据接收完成判断当前数据有多长
        $format = '';//用于解析本包长度
        foreach(self::$packType as $key=>$val){
            if($key == 'data'){
                continue;
            }
            $format .= $val.$key.'/';
        }
        $format = rtrim($format,'/');
        try{
            $data = unpack($format,$buffer);
        }catch(\Exception $e){
            //出现异常踢掉当前连接用户
            return false;
        }
        if(isset($data['dataLength'])){
            self::$packType['data'] = 'a'.intval($data['dataLength']);
        }else{
            //无法解析数据长度踢掉当前连接用户
            return false;
        }
        //返回本包长
        return 28 + $data['dataLength'];
    }

    /**
     * 打包，当向客户端发送数据的时候会自动调用
     * @param string $buffer
     * @return string
     */
    public static function encode($buffer)
    {
        $dataToPackage = [
            'startSingleMark'=>0x7e,//固定起始标识符（1字节8位）
            'startMark'=>'##',//数据包起始符（2字节##）
            'commandSingle'=>$buffer['commandSingle'],//命令标识（1字节）
            'commandAnswer'=>$buffer['commandAnswer'],//命令应答标识（1字节）
            'serialNumber'=>$buffer['serialNumber'],//命令流水号（2字节16位）
            'carVIN'=>$buffer['carVIN'],//车辆vin码或充电站编码+充电桩编码(17字节)
            'dataEncryptionWay'=>0x00,//数据加密方式（1字节8位）
            'dataLength'=>strlen($buffer['data']),//数据长度（2字节16位0-65534）
            'data'=>$buffer['data'],//数据内容等待接收到数据后确定
            'checkCode'=>'x',//校验码（1字节8位）
            //'endSingleMark'=>'C1'//固定结束标识符（1字节8位）
        ];
        self::$packType['data'] = 'a'.strlen($buffer['data']);
        $format = '';
        foreach(self::$packType as $key=>$val){
            $format .= $val.$key.'/';
        }
        $format = rtrim($format,'/');
        return self::packStruct($format,$dataToPackage);
    }

    /**
     * 解包，当接收到的数据字节数等于input返回的值（大于0的值）自动调用
     * 并传递给onMessage回调函数的$data参数
     * @param string $buffer
     * @return string
     */
    public static function decode($buffer)
    {
        $format = '';
        foreach(self::$packType as $key=>$val){
            $format .= $val.$key.'/';
        }
        $format = rtrim($format,'/');
        try{
            $message = unpack($format,$buffer);
        }catch(\Exception $e){
            return [];
        }
        unset($message['startSingleMark']);
        unset($message['startMark']);
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