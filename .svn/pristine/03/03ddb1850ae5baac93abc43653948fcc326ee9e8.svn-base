<?php 
require_once dirname(__FILE__).'/WeChatCallBack.php';
require_once dirname(__FILE__).'/../common/Common.php';
class HaoFeng extends WeChatCallBack{
	protected function getWX(){
		/*try{
			$DB = DbFactory :: getInstance();
			$selectsql = "SELECT * FROM wx_user WHERE weixin ='".$this->_fromUserName."' AND status = ".BIND;
			$result = $DB->getRow($selectsql);
			if(empty($result['weixin'])){
				$sql = "SELECT * FROM wx_user WHERE weixin ='".$this->_fromUserName."'";
				$ret = $DB->getRow($sql);
				if(empty($ret['weixin'])){
					$insertsql = "INSERT INTO `wx_user` ( `id` , `weixin` , `status` , `wx_number` , `wx_pwd` ) VALUES (NULL , '".$this->_fromUserName."',".UN_BIND.", NULL , NULL )";
					$DB->update($insertsql);
				}
			}
			return $result;
		}catch (Exception $e){
			interface_log(ERROR, EC_DB_OP_EXCEPTION, "operate wx_user error! msg:" . $e->getMessage());
			return false;
		}*/
	}
	protected function dealQueryClick($clickKey,$responseText){
		/*$wx_data = $this->getWX();
		if(empty($wx_data['wx_number'])){
			$hint = sprintf(LOGIN_LINK,'您还没有登陆,登陆请猛击','PRINT_LOGIN&weixin='.$this->_fromUserName,'');
			return $hint;
		}
		$arr = array(
			'wx_number'=>$wx_data['wx_number'],
			'wx_pwd'=>$wx_data['wx_pwd'],
			'wx_key'=>$clickKey
		);
		interface_log(DEBUG,0,"request:".json_encode($arr));
		$ret_data = getDataFrom(WX_DEAL, json_encode($arr));
		interface_log(DEBUG,0,'response:'.$ret_data);
		switch($ret_data){
			case WRONG_PWD:{
				$hint = sprintf(LOGIN_LINK,'密码被更改，请重新登陆','PRINT_LOGIN&weixin='.$this->_fromUserName,'');
				return $hint;
				break;
			}
			default:break;
		}
		$ret = json_decode($ret_data,true);
		$reg1 = "/普通客户/i";
		$reg2 = "/客户/i";
		if(preg_match($reg1,$ret['cust_level'])){
			$ret['cust_level'] = '';
		}else{
			if(preg_match($reg2,$ret['cust_level'])){
				
			}else{
				$ret['cust_level'] = $ret['cust_level'].'客户';
			}
		}
		if($clickKey == 'PRINT_EXCHANGE' ){
			if(isset($ret['gift'])){
				if($ret['gift'] == NO_EXCHANGE) $responseText .= "\r\n没有兑换活动";
				if($ret['gift'] == NO_GIFT) $responseText .= "\r\n没有礼品可以兑换";
			}else{
				$j = (int)((count($ret)-3)/2);
				$kScore = 0;
				while($j){
					$responseText .="\r\n可以兑换  %s";
					$j--;
					$kScore++;
					if( $j ==0) {
						$responseText .="\r\n\r\n".sprintf(WX_GIFT_LIST,$ret['vipnum']);
						break;
					}
					
				}
			}
		}
		if($clickKey == 'PRINT_TRANSACTION' ){
			//if($ret['items']>=1) $responseText .='，时间为%s';
			for($k=1;$k<=$ret['items'];$k++){
				$responseText .="\r\n单号：%s";
				if($ret['money'.$k]) {
					$responseText .="\r\n金额：%s元\r\n";
					$responseText .= sprintf(ORDER_DETAIL,$ret['ordernum'.$k]);
					$responseText .="\r\n";
				}
			}
		}
		$hint = vsprintf($responseText, $ret);
		return $hint;*/
	}
	
	public function process(){
		if($this->_msgType == 'text'){
			$hint = '开发中...';
			return $this->makeHint($hint);
		}
		if($this->_msgType == 'event'){
			if($this->_postObject->Event == 'subscribe'){
					/*$hint = '感谢您关注地上铁！';
				    return $this->makeHint($hint);*/
						$ret = array();
						$ret[0]['title'] = '二月二，龙抬头：你邀好友，我送现金！';
						$ret[0]['picurl'] ='https://mmbiz.qlogo.cn/mmbiz/TdwlqbDrpfteoJIB8EOKe5HfWz1f9ATwScN1O5wZhXTSM6nUsSnfZFvGWfFBZxO71jCWmaEgatz7OUfx6TTSSg/0?wx_fmt=jpeg';
						$ret[0]['url'] ='http://mp.weixin.qq.com/s?__biz=MzA3OTk5Mjc4MQ==&mid=405906434&idx=1&sn=83665c585b05379905e824fb4000c4c6#rd';
					   
					    $ret[1]['title'] = '地上铁租车春季返利【活动日程安排】';
						$ret[1]['picurl'] ='https://mmbiz.qlogo.cn/mmbiz/TdwlqbDrpfteoJIB8EOKe5HfWz1f9ATwUda7n19qblpqeAwLicwlAz5E1ISrPd2ibvemiaqVFtSl64QPfKSPuRELw/0?wx_fmt=jpeg';
						$ret[1]['url'] ='http://mp.weixin.qq.com/s?__biz=MzA3OTk5Mjc4MQ==&mid=405906434&idx=2&sn=8a64afea8d8ed15ad3d5785bee45e3b2#rd';
					    
						$ret[2]['title'] = '地上铁租车春季返利【租车优惠】 ';
						$ret[2]['picurl'] ='https://mmbiz.qlogo.cn/mmbiz/TdwlqbDrpfteoJIB8EOKe5HfWz1f9ATwX6wvcwKIENJxpSr2JTkJUdewXiawr6SdloJZsKQb6QqraGpHHeaQHBQ/0?wx_fmt=jpeg';
						$ret[2]['url'] ='http://mp.weixin.qq.com/s?__biz=MzA3OTk5Mjc4MQ==&mid=405906434&idx=3&sn=0126e95fb9cc7bfdf881af6eb4166db5#rd';
						foreach ($ret as $key => $value) {
						 	$title["$key"]="{$value['title']}";
						 	$picurl["$key"]=$value['picurl'];
						    $url["$key"]=$value['url'];
						} 
						return $this->hintNews($title,$describe='',$picurl,$url);break;
					
			}
			if($this->_postObject->Event == 'CLICK'){
				$eventKey = $this->_postObject->EventKey;
				switch($eventKey){
					case 'service' : {//客服
						$text ='您好！咨询活动信息或者租车优惠，请致电4008604558';
						return $this->makeHint($text);
                         
						break;
					}
					case 'ranking' : {//查看排名
					    $myOpenId = $this->_fromUserName;
						$url = sprintf(RANKINGURL,$myOpenId);
						$output=file_get_contents($url);
						return $this->makeHint($output);
						break;
					}
					case 'reward' : {//查看奖金
					    $myOpenId = $this->_fromUserName;
						$url = sprintf(REWARDURL,$myOpenId);
						$output=file_get_contents($url);
						return $this->makeHint($output);
						break;
					}
					
					
					
					
					
					
					
					
					
					
					
					case 'PRINT_LOGIN' : {
						/*interface_log(DEBUG,0,'response:'.$eventKey);
						//$hint = sprintf(LOGIN_LINK,'会员登陆','PRINT_LOGIN&weixin='.$this->_fromUserName,'');*/
						return $this->makeHint('开发中...');
                         
						break;
					}
					
					case 'PRINT_LOGOUT' : {
						/*$arr = $this->getWX();
						if(empty($arr)){ 
							$hint = '您还没有登陆过';
						}else{
							try{
								$DB = DbFactory :: getInstance();
								$updatesql = "UPDATE wx_user SET status = ".UN_BIND." WHERE id = ".$arr[id];
								$DB->update($updatesql);
							}catch (Exception $e){
								interface_log(ERROR, EC_DB_OP_EXCEPTION, "operate wx_user error! msg:" . $e->getMessage());
								return false;
							}
							$hint = '注销成功';
						}*/
						return $this->makeHint('开发中...');break;
					}
					case 'PRINT_PWD' : {
						$hint = sprintf(MODIFY_PWD,'密码修改','PRINT_PWD&weixin='.$this->_fromUserName,'');
						return $this->makeHint($hint);break;
					}
					case 'PRINT_LINK' : {
						$arr = array(
							'wx_key'=>'PRINT_LINK'
						);
						interface_log(DEBUG,0,"request:".json_encode($arr));
						$ret_data = getDataFrom(WX_NO_DEAL, json_encode($arr));
						interface_log(DEBUG,0,'response:'.$ret_data);
						if($ret_data == NO_LINK){
							return $this->makeHint('联系方式尚未设置');break;
						}
						$ret = json_decode($ret_data,true);
						$title = '各分店信息';
						$describe = '';
						foreach ($ret as $key => $value) {
							if(!empty($describe)) $describe .="\r\n";
						 	$describe .="{$value['shop_name']}：\r\n联系电话：{$value['phone']}\r\n地址：{$value['address']}";
						} 
//						$prcurl = '';
//						$url = LINK_SHOW;
//						return $this->hintNews($title,$describe,$prcurl,$url);break;
						$describe .="\r\n\r\n".LINK_SHOW;
						return $this->makeHint($title."\r\n\r\n".$describe);break;
					}
					case 'PRINT_TRANSACTION' :{
						$hint = $this->dealQueryClick('PRINT_TRANSACTION',WX_TADYORDER);
						interface_log(DEBUG,0,'chat response text:'.$hint);
						return $this->makeHint($hint);break;
						break;
					}
					case 'PRINT_ORDER' : {
						$hint = $this->dealQueryClick('PRINT_ORDER',WX_ORDER_LIST);
						interface_log(DEBUG,0,'chat response text:'.$hint);
						return $this->makeHint($hint);break;
						break;
					}
					case 'PRINT_BALANCE' : {
						$hint = $this->dealQueryClick('PRINT_BALANCE',WX_BALANCE);
						interface_log(DEBUG,0,'chat response text:'.$hint);
						return $this->makeHint($hint);break;
					}
					case 'PRINT_EXPENCE' : {
						$hint = $this->dealQueryClick('PRINT_EXPENCE',WX_EXPENCE);
						interface_log(DEBUG,0,'chat response text:'.$hint);
						return $this->makeHint($hint);break;
					}
					case 'PRINT_SCORE' : {
						$hint = $this->dealQueryClick('PRINT_SCORE',WX_SCORE);
						interface_log(DEBUG,0,'chat response text:'.$hint);
						return $this->makeHint($hint);break;
					}
					case 'PRINT_ALL' : {
						$hint = $this->dealQueryClick('PRINT_ALL',WX_ALL);
						interface_log(DEBUG,0,'chat response text:'.$hint);
						return $this->makeHint($hint);break;
					}
					case 'PRINT_EXCHANGE' : {
						$hint = $this->dealQueryClick('PRINT_EXCHANGE',WX_EXCHANGE);
						interface_log(DEBUG,0,'chat response text:'.$hint);
						return $this->makeHint($hint);break;
					}
					case 'PRINT_FAVORABLE' : {
						$arr = array(
							'wx_key'=>'PRINT_FAVORABLE'
						);
						interface_log(DEBUG,0,"url:".WX_NO_DEAL."  request:".json_encode($arr));
						$ret_data = getDataFrom(WX_NO_DEAL, json_encode($arr));
						interface_log(DEBUG,0,'response:'.$ret_data);
						if($ret_data == NO_FAVORABLE){
							return $this->makeHint('没有优惠活动');break;
						}
						$ret = json_decode($ret_data,true);
						//$title[0] = '优惠活动';
						foreach ($ret as $key => $value) {
						 /*	$title["$key"]="{$value['content']}\r\n截止时间：{$value['end_time']}";
						 	if($value['url'] == 'client_recom') $url["$key"]=CLIENT_RECOM;
						 	else $url["$key"]='';*/
						 	$title["$key"]="{$value['title']}";
						 	$picurl["$key"]=sprintf(PRODUCT_IMAGE,$value['tupian']);
						    $url["$key"]=sprintf(HUDONG_LIST,$value['id']);
						} 
						return $this->hintNews($title,$describe='',$picurl,$url);break;
					}
					case 'PRINT_MALL' :{
						$arr = array(
							'wx_key'=>'PRINT_MALL'
						);
						interface_log(DEBUG,0,"request:".json_encode($arr));
						$ret_data = getDataFrom(WX_NO_DEAL, json_encode($arr));
						interface_log(DEBUG,0,'response:'.$ret_data);
						if($ret_data == NO_PRODUCT){
							return $this->makeHint('暂时没有商品哦');break;
						}
						$ret = json_decode($ret_data,true);
						//$title[0] = '微商城';
						//$url[0] = PRODUCT_SHOW;
						$num_pro = (int)count($ret['product'])/3;
						for ($i = 1;$i<=$num_pro;$i++) {
							$title[$i]=$ret['product']['product_name'.$i];
						 	$picUrl[$i]=sprintf(PRODUCT_IMAGE, $ret['product']['image_name'.$i]);
						 	$url[$i]=sprintf(PRODUCT_ORDER, $ret['product']['id'.$i]);
						} 
						$num = count($title);
						$title[$num] = '查看所有商品';
						$url[$num] = PRODUCT_SHOW;
						$title[$num+1] = '查看最近订单';
						$url[$num+1] = NEW_ORDER;
						return $this->hintNews($title,'',$picUrl,$url);break;
					}
					case 'PRINT_CARD' : {//办卡须知
						$arr = array(
							'wx_key'=>'PRINT_CARD'
						);
						interface_log(DEBUG,0,"url:".WX_NO_DEAL."  request:".json_encode($arr));
						$ret_data = getDataFrom(WX_NO_DEAL, json_encode($arr));
						interface_log(DEBUG,0,'response:'.$ret_data);
						if($ret_data == NO_CARD){
							return $this->makeHint('没有办卡内容');break;
						}
						$ret = json_decode($ret_data,true);
						foreach ($ret as $key => $value) {
						 	$title["$key"]="{$value['title']}";
						 	$picurl["$key"]=sprintf(PRODUCT_IMAGE,$value['picture']);
						    $url["$key"]=sprintf(CARD_DETAIL,$value['id']);
						} 
						return $this->hintNews($title,$describe='',$picurl,$url);break;
					}
					case 'PRINT_SATISFY': {
						$wx_data = $this->getWX();
							$arr = array(
								'wx_number'=>$wx_data['wx_number'],
								'wx_pwd'=>$wx_data['wx_pwd'],
								'wx_key'=>'PRINT_SATISFY'
							);
						$ret_data = getDataFrom(WX_DEAL, json_encode($arr));
						$ret = json_decode($ret_data,true);
						$data = sprintf(SATISFY_DIAO,$ret['custVip']);
						$hint = "尊敬的客户请点击\r\n".$data."\r\n进入填写";
						return $this->makeHint($hint);break;
					}case 'PRINT_DIAL': {
						$wx_data = $this->getWX();
						if(empty($wx_data['wx_number'])){
							$hint = sprintf(LOGIN_LINK,'您还没有登陆,登陆请猛击','PRINT_LOGIN&weixin='.$this->_fromUserName,'');
						}else {
							$arr = array(
								'wx_number'=>$wx_data['wx_number'],
								'wx_pwd'=>$wx_data['wx_pwd'],
								'wx_key'=>'PRINT_DIAL'
							);
							interface_log(DEBUG,0,"request:".json_encode($arr));
							$ret_data = getDataFrom(WX_DEAL, json_encode($arr));
							interface_log(DEBUG,0,'response:'.$ret_data);

							
							if($ret_data == WRONG_PWD){
								$hint = sprintf(LOGIN_LINK,'密码被更改，请重新登陆','PRINT_LOGIN&weixin='.$this->_fromUserName,'');
							}else{
								$ret = json_decode($ret_data,true);
								if($ret['stime']=='invalid'){
									return $this->makeHint('转盘活动不在有效期之内！');break;
								}else{
										if($PERSONAL_LOGIN){
											$hint = sprintf(START_DIAL,$ret['custId']);
											}else{
											$hint = sprintf(START_DIAL,$ret['custId']);
												}
								}
							}
						}
						return $this->makeHint($hint);break;
					}
					default : return $this->makeHint(HINT_NOT_IMPLEMEMT);break;
				}
				return $this->makeHint(FIRST_SUBSCRIBE);
			}
			if($this->_postObject->Event == 'unsubscribe'){
				try{
					$DB = DbFactory :: getInstance();
					$selectsql = "SELECT * FROM wx_user WHERE weixin ='".$this->_fromUserName."'";
					$result = $DB->getOne($selectsql);
					$updatesql = "UPDATE wx_user SET status = ".UN_SUBSCRIBE." WHERE weixin ='".$this->_fromUserName."'";
					$DB->update($updatesql);
				}catch (Exception $e) {
					interface_log(ERROR, EC_DB_OP_EXCEPTION, "operate wx_user error! msg:" . $e->getMessage());
					return false;
				}
				return $this->makeHint('取消关注');
			}
		}
		return $this->makeHint(HINT_NOT_IMPLEMEMT);
	}
}
?>