<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="content-type">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <title>我要注册</title>
		<style type="text/css">
			.*{
				padding:0;
				margin:0;
			}
			img{
				border:0;
			}
			body{
				background:#fff;
				color:#555;
				font-size:14px;
				font-family: microsoft yahei, Arial, Helvetica, sans-serif;
			}
			.container{
				margin:0 auto;
				width: 95%;
				padding:5px;
			}
			tr{
				height:30px;
			}
			input:focus{
				box-shadow:0 0 8px #ddd;
			}
			input{
				outline:none;
			}
			
			.gender{
				height:30px; 
				min-width:100px;
                width: 100%;
			}
			.gender .sex{
				float:left;
				height:30px;
				line-height:30px;
				min-width:40px;
				border:1px solid #ccc;
				margin-right:8px;
				border-radius:5px;
				text-align:center;
                width: 36%;
			}
			.gender .changed{
				background-color:#70d49f;
				color:#fff;
				border:1px solid #70d49f;
			}
			.gender .sex input[type=radio]{
				display:none;
			}
			
			input[type=text]{
				width:90%;
				padding-left:5px;
				outline:none;
				border:1px solid #ccc;
				border-radius:5px;
				height:30px;
			}
			
			.next_btn{
				background-color:#70d49f;
				border:1px solid #70d49f;
				color:#fff;
				padding:8px 40px;
				margin-top:10px;
				border-radius:5px;
				font-size:16px;
				letter-spacing:2px;
				display: block;
                text-decoration: none;
                width: 30%;
                min-width: 86px;
                box-shadow: 0px 4px 4px #ddd;
                margin: :0 auto;
			}

			.vcode_btn{
                display: block;
				background-color:#FF9900;
				border:1px solid #FF9900;
				color:#fff;
				border-radius:5px;
                height:30px;
                line-height: 30px;
                box-shadow: 0px 4px 4px #ddd;
                text-align: center;
                min-width:70px;
                width:78%;
                padding-left: 2px;
                text-decoration: none;
			}
			
			.topTitle{
				font-size:16px;
				margin:10px 0px 20px 0px;
			}
			
			.bottomTip{
				margin:10px auto;
			}
			.bottomTip_title{
                margin-bottom: 8px;
			}
			.bottomTip_item{
				margin-bottom: 8px;
                color: #B3B3B3;
			}

			#indexFormErrTip{
				background: #000;
				filter: alpha(opacity=50);
				opacity: 0.5;  
				display: none;
				color:#fff;
				position: absolute;
				top: 50%;
				left: 50%;
				width: 250px;
				height: 30px;
				margin-left:-125px;
				margin-top:-15px;
				line-height: 30px;
				text-align:center;
				border-radius:5px;
			}
        </style>
        <script>
            //倒计时
            var counter = 80;
            var timer;
            function countdown(){
                var btn = document.getElementById('vcodeBtn');
                btn.innerHTML='剩余'+counter+'秒';
                counter--;
                if(counter >= 0){
                    timer = setTimeout("countdown()",1000);
                }else{
                    clearTimeout(timer);
                    btn.innerHTML='获取验证码';
                    counter = 80;
                }
            }
        </script>
    </head>
	
    <body>
        <div class="container">
			<form class="index_form">
                <input type="hidden" name="open_id" value="<?php echo isset($open_id) ? $open_id : ''; ?>" >
				<div class="topTitle">
					<div >注册参与地上铁春季返利活动，邀请朋友租车可以获得非常可观的现金奖励哦！</div>
				</div>
				<table cellspacing="0" cellpadding="5" width="100%" border="0">
					<tr>
						<td colspan="3">为确保您能顺利参加活动，请您如实填写信息:</td>
					</tr>
					<tr>
						<td width="50"><label>姓名</label></td>
						<td><input type="text" name="name" value="" placeholder="必填"></td>
						<td width="40%">
							<div class="gender">
								<div class="sex changed">
									先生
									<input type="radio" name="sex" value="1" checked="checked">
								</div>
								<div class="sex">
									女士
									<input type="radio" name="sex" value="0">
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td><label>手机</label></td>
						<td><input type="text" name="mobile" value="" placeholder="必填"></td>
						<td><a class="vcode_btn" id="vcodeBtn">获取验证码</a></td>
					</tr>
					<tr>
						<td><label>验证码</label></td>
						<td colspan="2"><input type="text" name="vcode" value=""></td>
					</tr>
					<tr>
						<td><label>邀请码</label></td>
						<td colspan="2">
							<input type="text" name="invite_code" value=""  placeholder="如果没有，可以不填">
						</td>
					</tr>
					<tr>
						<td colspan="3" align="center">
                            <a href="javascript:;" class="next_btn" id="nextBtn">下一步</a>
						</td>
					</tr>
				<table>
                
                <div class="bottomTip">
                    <div class="bottomTip_title">什么是邀请码？</div>
                    <div class="bottomTip_item">邀请码在地上铁租车春季返利活动中的作用：</div>
                    <div class="bottomTip_item">1、成功报名注册后，您可以获得一个专属的邀请码；</div>
                    <div class="bottomTip_item">2、将邀请码发送给朋友；</div>
                    <div class="bottomTip_item">3、朋友注册参与活动时，填入您的邀请码；</div>
                    <div class="bottomTip_item">4、朋友在地上铁完成租车，您即可以获得现金奖励。</div>
                    <div class="bottomTip_item">所以，赶紧完成注册，然后把邀请码发给想租车的朋友吧！丰厚奖金等你拿！</div>
                </div>
			</form>
            <div id="indexFormErrTip"></div>
        </div>
    </body>
    <script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
	<script>
		$(function(){

            //若是点击别人的分享链接进入的注册页面则能以别人的邀请码赋值
            var invite_code = '<?php echo $invite_code; ?>';
            if(invite_code){
                $('[name=invite_code]',$('.index_form')).val(invite_code);
            }
			
			/*选择性别*/
            $(".gender").find('.sex').on('click',function(event) {
                $(this).addClass('changed').siblings('.sex').removeClass('changed');
                $(this).find('input').prop("checked",true);
            });

            //获取短信验证码
            $('#vcodeBtn').on('click',function(){
                var _mobile = $('[name=mobile]',$('.index_form')).val();
                _mobile = _mobile.replace(/(^\s*)|(\s*$)/g,'');
                if(_mobile == ''){
                    $('#indexFormErrTip').show().text('手机必填！').fadeOut(2000);
                    return false;
                }
                var pattern = /^1\d{10}$/i;
                if(!pattern.test(_mobile)){
                    $('#indexFormErrTip').show().text('手机格式错误！').fadeOut(2000);
                    return false;
                }
                $.ajax({
                    type: 'post',
                    url:'<?php echo yii::$app->urlManager->createUrl(['promotion/ali-shotmessage/create-user']); ?>',
                    data: {'mobile':_mobile,'type':'reg'},
                    dataType: 'json',
                    success: function(rdata){
                        if(!rdata.error){
							countdown(); //倒计时	 
                        }else{
                            $('#indexFormErrTip').show().text(rdata.msg).fadeOut(2000);
                        }
                    }
                });
            });

            //下一步按钮
			$('#nextBtn').on('click',function(){
				var errTip = '';
                var _name = $('[name=name]',$('.index_form')).val();
                var _mobile = $('[name=mobile]',$('.index_form')).val();
                var _vcode = $('[name=vcode]',$('.index_form')).val();
                _name = _name.replace(/(^\s*)|(\s*$)/g,'');
                _mobile = _mobile.replace(/(^\s*)|(\s*$)/g,'');
                _vcode = _vcode.replace(/(^\s*)|(\s*$)/g,'');
				if(_name == ''){
                    errTip += '姓名必填！';
				}
				if(_mobile == ''){
                    errTip += '手机必填！';
				}
				if(_vcode == ''){
                    errTip += '验证码必填！';
				}
                if(errTip != ''){
                    $('#indexFormErrTip').show().text(errTip).fadeOut(2000);
                    return false;
                }
                var pattern = /^1\d{10}$/i;
                if(!pattern.test(_mobile)){
                    $('#indexFormErrTip').show().text('手机格式错误！').fadeOut(2000);
                    return false;
                }

				$.ajax({
					type: 'post',
					url:'<?php echo yii::$app->urlManager->createUrl(['promotion/sign/index-form-submit']); ?>',
					data: $('.index_form').serialize(),
					dataType: 'json',
					success: function(rdata){
						if(rdata.status){
							location.href = '<?php echo yii::$app->urlManager->createUrl(['promotion/sign/next']); ?>&id='+rdata.data.id;
						}else{
                            $('#indexFormErrTip').show().text(rdata.info).fadeOut(2000);
						}
					}
				});
			});
		});
	</script>
</html>
