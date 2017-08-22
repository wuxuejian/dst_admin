<?php
namespace backend\classes;
use backend\classes\Chexingyi;
// +----------------------------------------------------------------------
// | JuhePHP [ NO ZUO NO DIE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2010-2015 http://juhe.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: Juhedata <info@juhe.cn-->
// +----------------------------------------------------------------------
 
//----------------------------------
// 聚合数据全国违章接口调用类
//----------------------------------
class wz{
    private $appkey = '7d81796873facdb75505e1fc39348942'; //申请的全国违章查询APPKEY
 
    private $cityUrl = 'http://v.juhe.cn/wz/citys';
 
    private $wzUrl = 'http://v.juhe.cn/wz/query';
    private $statusUrl = 'http://v.juhe.cn/wz/status';
//    private $wzUrl = 'http://pengyunlin.w148.mc-test.com/dstzc/zc/test_query';
//	private $wzUrl = 'http://localhost/dstzc/zc/test_query';
 
    public function __construct($appkey=''){
    	if(@$appkey){
    		$this->appkey = $appkey;
    	}
    }
 
    /**
     * 获取违章支持的城市列表
     * @return array
     */
    public function getCitys($province=false){
        $params = 'key='.$this->appkey."&format=2";
        $content = $this->juhecurl($this->cityUrl,$params);
        return $this->_returnArray($content);
    }
    
    /**
     * 
     * 接口剩余请求次数查询
     */
    public function status(){
    	$params = array(
            'key' => $this->appkey
        );
        $content = $this->juhecurl($this->statusUrl,$params,1);
        return $this->_returnArray($content);
    }
 
    /**
     * 查询车辆违章
     * @param  string $city     [城市代码]
     * @param  string $carno    [车牌号]
     * @param  string $engineno [发动机号]
     * @param  string $classno  [车架号]
     * @return  array 返回违章信息
     */
    public function query($city,$carno,$engineno='',$classno='',$car_type=''){
		
		//新的查询规则
		$wz_query = new Chexingyi();
		$wz_list = $wz_query->query($carno);
		// var_dump($wz_list);		
		
		$data = array('resultcode'=>0,'result'=> array('hphm'=>$carno,'lists'=>array()) );		
		//对结果进行格式化，兼容旧接口的返回模式。
		if ($wz_list['ErrorCode']== 0) {
			$data['resultcode'] = 200;
			if (!empty($wz_list['Records'])) {
				foreach ($wz_list['Records'] as $key => $wz) {
					$obj = new \stdClass;
					$obj->date = $wz['Time'];
					$obj->area = $wz['Location'];
					$obj->act = $wz['Reason'];
					$obj->code = $wz['Code'];
					$obj->fen = $wz['Degree'];
					$obj->money = $wz['count'];
					$obj->handled = $wz['status'];				
					$data['result']['lists'][] = $obj;				
				}	
			}				
		}
		// var_dump($result);
		// echo "hihi";exit;
    	$hpzl= '02';
    	if($car_type=='HUOCHE' || $car_type=='ZXXSHC'){
    		$hpzl = '01';
    	}
		$data['result']['hpzl'] = $hpzl;
    	return $data;
        // $params = array(
            // 'key' => $this->appkey,
            // 'city'  => $city,
            // 'hphm' => $carno,
        	// 'hpzl' => $hpzl,
            // 'engineno'=> $engineno,
            // 'classno'   => $classno
        // );
        // $content = $this->juhecurl($this->wzUrl,$params,1);
        // return $this->_returnArray($content);
    }
 
    /**
     * 将JSON内容转为数据，并返回
     * @param string $content [内容]
     * @return array
     */
    public function _returnArray($content){
        return json_decode($content,true);
    }
 
    /**
     * 请求接口返回内容
     * @param  string $url [请求的URL地址]
     * @param  string $params [请求的参数]
     * @param  int $ipost [是否采用POST形式]
     * @return  string
     */
    public function juhecurl($url,$params=false,$ispost=0){
        $httpInfo = array();
        $ch = curl_init();
 
        curl_setopt( $ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1 );
        curl_setopt( $ch, CURLOPT_USERAGENT , 'JuheData' );
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 60 );
        curl_setopt( $ch, CURLOPT_TIMEOUT , 60);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
        if( $ispost )
        {
            curl_setopt( $ch , CURLOPT_POST , true );
            curl_setopt( $ch , CURLOPT_POSTFIELDS , $params );
            curl_setopt( $ch , CURLOPT_URL , $url );
        }
        else
        {
            if($params){
                curl_setopt( $ch , CURLOPT_URL , $url.'?'.$params );
            }else{
                curl_setopt( $ch , CURLOPT_URL , $url);
            }
        }
        $response = curl_exec( $ch );
        if ($response === FALSE) {
            //echo "cURL Error: " . curl_error($ch);
            return false;
        }
        $httpCode = curl_getinfo( $ch , CURLINFO_HTTP_CODE );
        $httpInfo = array_merge( $httpInfo , curl_getinfo( $ch ) );
        curl_close( $ch );
        return $response;
    }
}