<link rel="stylesheet" href="<?= yii::getAlias('@web'); ?>/css/login.css">
<script>
	$(function(){
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
    use yii\bootstrap\ActiveForm;
    use yii\captcha\Captcha;
    $form = ActiveForm::begin(['id'=>'loginForm']);
?>
<div class="topDiv"></div>
<div class="warper">
<div class="title"></div>

<div class="main">
    <ul>
        <li><label>用户名</label><input type="text" name="username" value="" placeholder=""></li>
        <li><label>密码</label><input type="password" name="password" value="" placeholder=""></li>
        <li><label>验证码</label><input type="text" name="verifyCode" value="" placeholder=""></li>
        <li class="img"><img id="admin-verifycode-image" src="<?php echo yii::$app->urlManager->createUrl(['/site/captcha']); ?>"><a href="javascript:refreshVerifyCode()">看不清，刷新验证码</a></li>
        <li><input id="submit-btn" type="submit" name="" value="登录"><input type="reset" name="" value="重置" id="reset-btn"></li>
    </ul>
    <div class="erro"></div>
</div>

</div>
<?php $form->end(); ?>