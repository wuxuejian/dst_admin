<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="content-type">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <title>我要注册-下一步</title>
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
			input[type=text]{
				width:90%;
				padding-left:5px;
				outline:none;
				border:1px solid #ccc;
				border-radius:5px;
				height:30px;
			}
			
			.nextFormSubmitBtn{
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

			
			.address{
				width:92%;
				height:auto;
				float:left;
			}
			.address .address_options{
				width: 8%;
				min-width:40px;
				height:30px;
				line-height: 30px;
				border:1px solid #ccc;
				float:left;
				text-align:center;
				margin-right:5px;
				margin-bottom:5px;
				border-radius:5px;
				background-color:#fff;
				cursor:pointer;
			}
			.address .currend{
				background-color:#70d49f;
				border:1px solid #70d49f;
				color:#fff;
			}
			.address .address_options input[type=radio]{
				display:none;
			}
			
			.address .otherDistrictInput{
				width:92%;
				height:30px;
				float:left;
				display:none;
			}

			
			#nextFormErrTip{
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
    </head>
    <body>
        <div class="container">
			<form class="next_form">
                <input type="hidden" name="id" value="<?php echo $id; ?>" />
				<table cellspacing="0" cellpadding="5" width="100%" border="0">
					<tr>
						<td colspan="3">填写一下信息，可以帮助我们更好的为您提供服务，您也可以直接跳过:</td>
					</tr>
					<tr>
						<td width="50"><label>公司</label></td>
						<td colspan="2"><input type="text" name="company" value=""></td>
					</tr>
					<tr>
						<td><label>职业</label></td>
						<td><input type="text" name="profession" value=""></td>
					</tr>
					<tr>
						<td valign="top"><label>区域</label></td>
						<td colspan="2">
							<div class="address">
								<div class="address_options currend">
									罗湖
									<input type="radio" name="district" value="罗湖" checked="checked">
								</div>
								<div class="address_options">
									福田
									<input type="radio" name="district" value="福田">
								</div>
								<div class="address_options">
									南山
									<input type="radio" name="district" value="南山">
								</div>
								<div class="address_options">
									宝安
									<input type="radio" name="district" value="宝安">
								</div>
								<div class="address_options">
									龙岗
									<input type="radio" name="district" value="龙岗">
								</div>
								<div class="address_options">
									盐田
									<input type="radio" name="district" value="盐田">
								</div>
								<div class="address_options">
									坪山
									<input type="radio" name="district" value="坪山">
								</div>
								<div class="address_options">
									龙华
									<input type="radio" name="district" value="龙华">
								</div>
								<div class="address_options other_input">
									其他
									<input type="radio" name="district" value="其他">
								</div>
								<div class="otherDistrictInput">
									<input type="text" name="otherDistrict" value="" placeholder="请输入您所在的区域">
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="3" align="center">
                            <a href="javascript:;" class="nextFormSubmitBtn" id="nextFormSubmitBtn">提交</a>
						</td>
					</tr>
				</table>
			</form>
            <div id="nextFormErrTip"></div>
        </div>
    </body>
    <script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
	<script>
		$(function(){
			
			/*选择地址选项*/
			function otherInput(){
				$(".address_options").on('click', function() {
					if ($(this).hasClass('other_input')) {
						$(".otherDistrictInput").find('input').val('').end().show();
						$(this).addClass('currend').siblings(".address_options").removeClass('currend');
					}else{
						$(".otherDistrictInput").find('input').val('').end().hide();
						$(this).addClass('currend').siblings(".address_options").removeClass('currend');
						$(this).find("input").prop("checked",true);
					}
				});
			};
			otherInput();
			
			
			//提交注册
			$('#nextFormSubmitBtn').on('click',function(){
				if($('[name=otherDistrict]',$('.next_form')).is(':visible') && $('[name=otherDistrict]',$('form')).val() == ''){
                    $('#nextFormErrTip').show().text('请填写您选择的其他区域！').fadeOut(2000);
                    return false;
				}
				$.ajax({
					type: 'post',
					url:'<?php echo yii::$app->urlManager->createUrl(['promotion/sign/next-form-submit']); ?>',
					data: $('.next_form').serialize(),
					dataType: 'json',
					success: function(rdata){
						if(rdata.status){
							location.href = '<?php echo yii::$app->urlManager->createUrl(['promotion/sign/share']); ?>&myInviteCode='+rdata.data.invite_code_mine;
						}else{
                            $('#nextFormErrTip').show().text(rdata.info).fadeOut(2000);
						}
					}
				});
			});
		});
	</script>
</html>
