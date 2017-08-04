<?php
// 测试二进制存和读取储结构体(struct)
function packStruct($format, $struct){
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

//返回数据解包
function _unpack($data){
    $formatArr = [
        'startSingleMark'=>'C1',//固定起始标识符（1字节8位）
        'startMark'=>'a2',//数据包起始符（2字节##）
        'commandSingle'=>'C1',//命令标识（1字节）
        'commandAnswer'=>'C1',//命令应答标识（1字节）
        'serialNumber'=>'n1',//命令流水号（2字节16位）
        'carVIN'=>'a17',//车辆vin码（17字节）
        'dataEncryptionWay'=>'C1',//数据加密方式（1字节8位）
        'dataLength'=>'n1',//数据长度（2字节16位0-65534）
        'data'=>'',//数据内容
        'checkCode'=>'a1',//校码码（1字节8位）
        //'endSingleMark'=>'C1'//固定结束标识符（1字节8位）
    ];
    $format = '';
    foreach($formatArr as $key=>$val){
        if($key == 'data'){
            continue;
        }
        $format .= "{$val}{$key}/";
    }
    $format = rtrim($format,'/');
    $dataHeader = unpack($format,$data);
    $formatArr['data'] = 'a'.$dataHeader['dataLength'];
    $format = '';
    foreach($formatArr as $key=>$val){
        $format .= "{$val}{$key}/";
    }
    $format = rtrim($format,'/');
    return unpack($format,$data);
}

echo '<pre>';