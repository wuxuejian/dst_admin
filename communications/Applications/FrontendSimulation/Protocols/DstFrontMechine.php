<?php
namespace Protocols;
/**
 * 地上铁-模拟电桩协议
 */
class DstFrontMechine
{

    //数据解析格式
    /*protected static $packType = [
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
    ];*/
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
        if(strlen($buffer) < 11){
            //数据包头不完整继续等待数据
            return 0;
        }
        //头数据接收完成判断当前数据有多长
        $unpackData = unpack('ndataLength',substr($buffer,9,2));
        //返回本包长
        return 18 + $unpackData['dataLength'];
    }

    /**
     * 打包，当向客户端发送数据的时候会自动调用
     * @param string $buffer
     * @return string
     */
    public static function encode($buffer)
    {
        var_dump($buffer);
        $content = '';
        $content .= pack('C',$buffer['actionTarget']);
        $content .= pack('C',$buffer['result']);
        switch ($buffer['action']) {
            case 2:
                //回复充电
                $content .= pack('C',$buffer['reason']);
                break;
            case 3:
                //回复结束充电
                $content .= pack('f',$buffer['money']);
                break;
       }
       return $content;
    }

    /**
     * 解包，当接收到的数据字节数等于input返回的值（大于0的值）自动调用
     * 并传递给onMessage回调函数的$data参数
     * @param string $buffer
     * @return string
     */
    public static function decode($buffer)
    {
        $unpackData = unpack('ndataLength',substr($buffer,9,2));
        $formatArr = [
            'startChar'=>'C',//起始字符（68H）
            'RTUA'=>'a4',//充电桩逻辑地址（RTUA）
            'MSTA_SEQ'=>'a2',//主站地址与命令序号（MSTA&SEQ）
            'startCharRepeat'=>'C',//起始字符（68H）
            'controllerCode'=>'C',//控制码C（0EH）
            'dataLength'=>'n',//数据长度L(0AH)
            'measureDot'=>'C',//测量点号（TN）
            'accessLevel'=>'C',//权限等级（AUT）（注：为高级权限11H）
            'pwd'=>'a3',//密码（PW）（注：3字节BCD码）
            'data'=>'a'.$unpackData['dataLength'],//数据项DATA
            'checkCode'=>'C',//校验（CS）
            'endChar'=>'C',//帧尾（16H）
        ];
        $format = '';
        foreach($formatArr as $key=>$val){
            $format .= $val.$key.'/';
        }
        $format = rtrim($format,'/');
        $unpackData = unpack($format,$buffer);
        return $unpackData['data'];
    }
}