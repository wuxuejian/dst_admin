<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>云印刷用户服务系统</title>
        <link rel="stylesheet" type="text/css" href="css/company/login.css" />
	</head>
    <script language="javascript">
        operateUrls = <?=json_encode($datas['urls'])?>;
    </script>
    <script type="text/javascript" src="js/jquery.min.js"> </script>
    <script type="text/javascript" src="js/company/login.js"> </script>
	<body>
		<div class="main">
			<div class="center-pocid"><img src="images/company/fontss.png" /><span class="fontspan">供应商</span></div>
			<div>
			<div class="main-divimgs"><img src="images/company/back_left.png" class="img-left"/></div>
			<div class="main-divimgs"><img src="images/company/back_cen.png" width="100%" height="56px" class="img-cent"/></div>
			<div class="main-divimgs"><img src="images/company/back_right.png" class="img-right"/></div>
</div>
			<div class="headers">
				<div class="center-main">
					<span class="ico-logo"></span>
					<span class="font-sty">供应商管理</span>
				</div>
			</div>
			<div class="center-main">
				<div class="center-right">
					<h1 class="h1">用户登陆	</h1> 
                    <p class="mainlogin mainlogin1">
                        <input type="text" placeholder="请输入商户名称" class="divinput1_input" name="bossname" id="bossname" value="<?=$_SESSION['bHfbname']?>">
                    </p>
					<p class="mainlogin mainlogin2">
                        <input type="text" placeholder="请输入用户名" class="divinput1_input" name="username" id="username"  value="<?=$_SESSION['bHfuname']?>">
                    </p>
                    <p class="mainlogin">
                        <input type="password" placeholder="********" class="divinput1_input" name="password" id="password">
                    </p>
                    <p>
                        <span id="tishi">&nbsp;</span>
                    </p>
					
					<p class="mainlogin1 back-logininputs" style="margin-top:0px">
                        <input type="button" name="button" value="登陆" onclick="login()">
                    </p>
					<p>
						<small><a href="<?=\yii\helpers\Url::to(['/company/retrieve/index'])?>" style="color: #4BB2D8;text-decoration: none;">找回密码？</a></small>
					</p>
				</div>
			</div>
		</div>
	</body>
</html>
