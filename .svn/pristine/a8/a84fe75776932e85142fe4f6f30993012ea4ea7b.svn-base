<?php
namespace backend\classes;
/**
 * 前置机通讯类
 */
class FrontMachine{
    protected $socket;   //socket对象
    protected $register; //寄存器号
    
    /**
     * @param $frontAddr    前置机地址和端口号
     * @param $registerNum  寄存器号(暂时无用)
     */
    public function __construct($frontAddr,$registerNum = ''){
        list($addr,$port) = explode(':',$frontAddr);
        $this->register = $registerNum;
        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        //发送超时
        socket_set_option($this->socket,SOL_SOCKET,SO_SNDTIMEO,array("sec"=>3,"usec"=>0 ));
        //接收超时
        socket_set_option($this->socket,SOL_SOCKET,SO_RCVTIMEO,array("sec"=>20,"usec"=>0 ));
        //var_dump($addr,$port);
        //die;
        socket_connect($this->socket, $addr, $port);
    }
    
    /**
     * 打包
     * @param array $data 要发送的数据
     */
    protected static function encode($data){
        $formatArr = [
            'startChar'=>'C',//起始字符（68H）
            'RTUA'=>'a4',//充电桩逻辑地址（RTUA）
            'MSTA_SEQ'=>'a2',//主站地址与命令序号（MSTA&SEQ）
            'startCharRepeat'=>'C',//起始字符（68H）
            'controllerCode'=>'C',//控制码C（0EH）
            'dataLength'=>'v',//数据长度L(0AH)
            'measureDot'=>'C',//测量点号（TN）
            'accessLevel'=>'C',//权限等级（AUT）（注：为高级权限11H）
            'pwd'=>'a3',//密码（PW）（注：3字节BCD码）
            'data'=>'a'.strlen($data['data']),//数据项DATA
            'checkCode'=>'C',//校验（CS）
            'endChar'=>'C',//帧尾（16H）
        ];
        //$measureDotMap = [1,2,4,8];
        $dataToPackage = [
            'startChar' => 0x68,//起始字符（68H）
            'RTUA'      => pack('C',hexdec(substr($data['RTUA'],0,2))).pack('C',hexdec(substr($data['RTUA'],2,2))).pack('C',hexdec(substr($data['RTUA'],6,2))).pack('C',hexdec(substr($data['RTUA'],4,2))),//充电桩逻辑地址（RTUA）
            'MSTA_SEQ'  => pack('C',0x03).pack('C',0x00),//主站地址与命令序号（MSTA&SEQ）
            'startCharRepeat' => 0x68,//起始字符（68H）
            'controllerCode'  => 0x0E,//控制码C（0EH）
            'dataLength'      => strlen($data['data']) + 5,//数据长度L(0AH)
            //'measureDot'      => $measureDotMap[$data['measureDot']],//测量点号（TN）
            'measureDot'      => $data['measureDot'],//测量点号（TN）
            'accessLevel'     => 0x11,//权限等级（AUT）（注：为高级权限11H）
            'pwd'       => pack('C',0x11).pack('C',0x11).pack('C',0x11),//密码（PW）（注：3字节BCD码）
            'data'      => $data['data'],//数据项DATA
            'checkCode' => 0,//校验（CS）计算所得
            'endChar'   => 0x16,//帧尾（16H）
        ];
        //计算校验码开始
        $content = '';
        foreach ($formatArr as $key => $value) {
            if($key == 'checkCode'){
                break;
            }else{
                $content .= pack($value,$dataToPackage[$key]);
            }
        }
        $codeSum = 0;
        for($i = 0;$i < strlen($content);$i++){
            $codeSum += hexdec(bin2hex($content[$i]));
        }
        $dataToPackage['checkCode'] = $codeSum % 256;
        //计算校验码结束
        $content = '';
        foreach ($formatArr as $key => $value) {
            $content .= pack($value,$dataToPackage[$key]);
        }
        return $content;
    }

    /**
     * 读取socket内容并解析
     */
    protected function read(){
        try{
            $readData = socket_read($this->socket,8192);
        }catch(\Exception $e){
            return false;
        }
        //不解析所有返回数据以下为返回数据格式
        /*$formatArr = [
            'startChar'=>'C',//起始字符（68H）
            'RTUA'=>'a4',//充电桩逻辑地址（RTUA）
            'MSTA_SEQ'=>'a2',//主站地址与命令序号（MSTA&SEQ）
            'startCharRepeat'=>'C',//起始字符（68H）
            'controllerCode'=>'C',//控制码C（0EH）
            'dataLength'=>'v',//数据长度L(0AH)
            'data'=>'',//数据项DATA
            'checkCode'=>'C',//校验（CS）
            'endChar'=>'C',//帧尾（16H）
        ];*/
        //解析数据长度
        //var_dump(bin2hex($readData));
        if(!$readData){
            return false;
        }
        $unpackData = unpack('vdataLength',substr($readData,9,2));
        return substr($readData,11,$unpackData['dataLength']);
    }

    /**
     * 启动充电
     * @param  string $RTUA 充电桩逻辑地址
     * @param  string $card 卡号
     * @param  fload  $money卡内余额
     * @param  int    $gun  枪号  
     * @return array
     */
    public function start($RTUA,$card,$gun,$money){
        $data['RTUA'] = $RTUA;
        $data['data'] = '';
        $data['data'] .= pack('C',0x40).pack('C',0x88);//寄存器
        $data['data'] .= pack('C',2);//启动状态
        $data['data'] .= self::packCardNumber($card);//卡号
        $data['data'] .= pack('C',intval($gun));//枪号
        $data['data'] .= self::packMoney($money);//卡内余额
        $data['measureDot'] = $gun;
        $content = self::encode($data);
        if(!@socket_write($this->socket, $content,strlen($content))){
            //数据发送失败
            return ['status'=>false,'info'=>'数据发送失败！'];
        }
        //读取返回数据
        $receiveData = $this->read();
        $actionResult = [];
        if($receiveData === false){
            //前置机无响应
            $actionResult['result'] = 'error';
            $actionResult['reason'] = '充电桩通讯故障，请稍后再试或使用其他电桩！';
            return $actionResult;
        }else{
            $receiveData = bin2hex($receiveData);
        }
        $actionResult['measuringPoint'] = intval(substr($receiveData,0,2));
        if(substr($receiveData,2,2) == 0){
            $actionResult['actionTarget'] = '操作桩';
        }else{
            $actionResult['actionTarget'] = '预约';
        }
        if(substr($receiveData,4,2) == 0){
            $actionResult['result'] = 'success';
        }else{
            $actionResult['result'] = 'error';
        }
        if($actionResult['result'] == 'success'){
            return $actionResult;
        }else{
            $reason = intval(substr($receiveData,6,2));
            switch ($reason) {
                case 0:
                    $actionResult['reason'] = '枪没有插好';
                    break;
                case 1:
                    $actionResult['reason'] = '请确认充电桩正常运行';
                    break;
                default:
                    $actionResult['reason'] = '其他原因';
                    break;
            }
            return $actionResult;
        }
    }

    /**
     * 结束充电
     * @param  string $RTUA 充电桩逻辑地址
     * @param  string $card 卡号
     * @param  fload  $money卡内余额
     * @param  int    $gun  枪号  
     * @return array
     */
    public function stop($RTUA,$card,$gun){
        $data['RTUA'] = $RTUA;
        $data['data'] = '';
        $data['data'] .= pack('C',0x40).pack('C',0x88);//寄存器
        $data['data'] .= pack('C',3);//结束状态
        $data['data'] .= self::packCardNumber($card);//卡号
        $data['data'] .= pack('C',intval($gun));//枪号
        $data['measureDot'] = $gun;
        $content = self::encode($data);
        if(!@socket_write($this->socket, $content,strlen($content))){
            //数据发送失败
            return ['status'=>false,'info'=>'数据发送失败！'];
        }
        //读取返回数据
        $receiveData = $this->read();
        $actionResult = [];
        if($receiveData == false){
            $actionResult['result'] = 'error';
            $actionResult['reason'] = '前置机无响应！';
            return $actionResult;
        }else{
            $receiveData = bin2hex($receiveData);
            //var_dump($receiveData);
        }
        $actionResult['measuringPoint'] = intval(substr($receiveData,0,2));
        if(substr($receiveData,2,2) == 0){
            $actionResult['actionTarget'] = '操作桩';
        }else{
            $actionResult['actionTarget'] = '预约';
        }
        if(substr($receiveData,4,2) == 0){
            $actionResult['result'] = 'success';
        }else{
            $actionResult['result'] = 'error';
        }
        $actionResult['c_amount'] = substr($receiveData,6,8) / 100;
        return $actionResult;
    }

    /**
     * 对传输过来的卡号进行打包
     */
    protected static function packCardNumber($card){
        $card = str_split(str_pad($card,16,'0',STR_PAD_LEFT),2);
        $card = array_reverse($card);
        $packData = '';
        foreach($card as $val){
            $packData .= pack('C',hexdec($val));
        }
        return $packData;
    }

    /**
     * 对传输过来的金额进行打包
     */
    protected static function packMoney($money){
        $money = intval($money * 100);
        $money = str_split(str_pad($money,8,'0',STR_PAD_LEFT),2);
        $packData = '';
        $money = array_reverse($money);
        foreach($money as $val){
            $packData .= pack('C',hexdec($val));
        }
        return $packData;
    }

    public function __destruct(){
        if($this->socket){
            socket_close($this->socket);
        }
    }
}

//测试
//$frontMachine = new FrontMachine('120.76.41.26:9094');
//var_dump($frontMachine->start('82050503',9,1,98685.64));
//var_dump($frontMachine->stop('82050503',6,1));