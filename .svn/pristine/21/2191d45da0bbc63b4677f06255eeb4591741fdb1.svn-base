<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>地上铁后台登录</title>
		<link rel="stylesheet" href="<?= yii::getAlias('@web'); ?>/css/login1.css">
		
		<script>
			//判断是否是IE浏览器，包括Edge浏览器
			function IEVersion(){
				var userAgent = navigator.userAgent; //取得浏览器的userAgent字符串
				var isOpera = userAgent.indexOf("Opera") > -1; //判断是否Opera浏览器	undefined
				var isIE = (!!window.ActiveXObject || "ActiveXObject" in window)//判断是否IE浏览器
				var isEdge = userAgent.indexOf("Windows NT 6.1; Trident/7.0;") > -1 && !isIE; //判断是否IE的Edge浏览器
				if(isIE){
				     var reIE = new RegExp("MSIE (\\d+\\.\\d+);");
				     reIE.test(userAgent);
				     var fIEVersion = parseFloat(RegExp["$1"]);
				     if(fIEVersion == 7)
				     { alert('IE版本过低请使用IE10以上版本');return "IE7";}
				     else if(fIEVersion == 8)
				     { alert('IE版本过低请使用IE10以上版本');return "IE8";}
				     else if(fIEVersion == 9)
				     { alert('IE版本过低请使用IE10以上版本');return "IE9";}
				     else if(fIEVersion == 10)
				     { return "IE10";}
				     else if(fIEVersion == 11)
				     { return "IE11";}
				 	 else if(!isEdge)
				 	 { return "Edge";}
				     else
				     { alert('IE版本过低请使用IE10以上版本');return "0"}//IE版本过低
				}else{
				    alert('您使用的不是IE内核浏览器请使用IE内核浏览器');return "-1";//非IE
				}
			}
			IEVersion();
			$(function(){
				
				function getMacBeforSubmit(){
					/*var userAgent = navigator.userAgent; //取得浏览器的userAgent字符串		
					if (!!window.ActiveXObject || "ActiveXObject" in window) {
						var dstActiveX = document.getElementById("dstActiveX");
						if (dstActiveX.object==null) {
							(function (){
				  				var r=confirm("是否安装地上铁插件？安装完毕后重新打开浏览器才可生效");
								if (r==true){
									window.open("DstSetup.msi");
								}else{
								}
							}())
						}
						var mac = dstActiveX.GetIp();
						console.log(mac);
						$('#mac').val(mac);
					}*/
					var dstActiveX = document.getElementById("dstActiveX");
					if (dstActiveX.object != null) {
						var mac = dstActiveX.GetIp();
						console.log(mac);
						$('#mac').val(mac);
					}else {
						var r=confirm("是否安装地上铁插件？安装完毕后重新打开浏览器才可生效");
						if (r==true){
							window.open("DstSetup.msi");
						}else{
						}
					}
					//if (userAgent.indexOf("compatible") > -1 && userAgent.indexOf("MSIE") > -1 && !isOpera) {
					//	return "IE";
					//}; //判断是否IE浏览器
					
					
					
					//alert(dstActiveX.object);
					
					
				}
				getMacBeforSubmit();

				
				
				$('#admin-verifycode-image').click(function(){
					$('#admin-verifycode-image').attr('src','<?php echo yii::$app->urlManager->createUrl(['/site/captcha']); ?>&v='+Math.random());
				});
				$('#loginForm').submit(function(){
		            $('#submit-btn').val('验证中...');
					
										
					
		            $('.erro').html('');
					var str = $('#loginForm').serialize();
					$.ajax({
						type: 'post',
						url: "<?php echo yii::$app->urlManager->createUrl('/site/login'); ?>",
						data: str,
						dataType: 'json',
						success: function(data){
							if(data.status){
								window.location.href="<?php echo yii::$app->urlManager->createUrl('/index/index'); ?>";
							}else{
		                        $('#submit-btn').val('登陆');
		                        $('#admin-verifycode-image').attr('src','<?php echo yii::$app->urlManager->createUrl(['/site/captcha']); ?>&v='+Math.random());
								$('.erro').html(data.info);
							}
						}
						
					});
					return false;
				});
				$('#reset-btn').click(function(event) {
					$('.erro').html('');
				});
			});
		    function refreshVerifyCode(){
		        $('#admin-verifycode-image').attr('src','<?php echo yii::$app->urlManager->createUrl(['/site/captcha']); ?>&v='+Math.random());
		    }
		</script>
	<?php
	    //use yii\bootstrap\ActiveForm;
	    use yii\captcha\Captcha;
	    //$form = ActiveForm::begin(['id'=>'loginForm']);
	?>
	</head>
	<body>
		<div class='cantainer clear'>
			<div class='login-top-bg'></div>
			<div class='header-wp'>
				<div class='header'>
					<a class='login-logo' href="#"></a>
					<span class='login-watchword'>新能源运力服务平台</span>
				</div>
			</div>
			<div class='login-box'>
				<div class='login-bg-text'>
					<p>城市绿色物流运力</p>
					<p class='fontsize24'>URBAN GREEN LOGISTICS CAPACITY</p>
				</div>
				<div class='login-windows'>
					<h5>欢迎登录</h5>
					<form id="loginForm">
					<input type="hidden" class="mac" id="mac" name="mac" value=""/>
					<div><input type="text" name="username" placeholder="帐号"></div>
					<div><input type="password" name="password" placeholder="密码"></div>
					<div><input type="text" name="verifyCode" placeholder="验证码"><span class='checkimg' style="width:60px; margin-right:1px;" onclick="javascript:refreshVerifyCode()"><img id="admin-verifycode-image"  src='<?php echo yii::$app->urlManager->createUrl(['/site/captcha']); ?>' style="width:60px"/></span></div>
					<!--  <div><input class='remen'type="checkbox" /><span>记住密码</span></div>-->
					<button class='login-btn'>登录</button>
					</form>
					<div class="erro" style="position:absolute; top:250px; left: 30px; color: red;"></div>
				</div>
				
			</div>
		</div>
		<div class='footer-cantainer'>
			<div class='footer'>
				<div class='yewei'>
					<p>CopyRight © 2012-2016 dstcar.com,All Rights Reserved.  版权所有  地上铁租车（深圳）有限公司</p>
					<p>粤ICP备：15109279号</p>
				</div>
			</div>
		</div>
	</body>
	<div style="display:none">
		<object id="dstActiveX" classid="clsid:EE533B73-1210-433B-AA01-94BFC3B503D4"></object>
	</div>
</html>