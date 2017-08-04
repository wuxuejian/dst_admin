<html> 
<head> 
<title>图文系统</title>
<link href="css/login1.css" mce_href="css/login1.css" rel="stylesheet" type="text/css" />
    <? $indeUurl = \Yii::$app->urlManager->createUrl('company/login/');?>
<script language="javascript" type="text/javascript"> 
var i = 2;
var intervalid; 
intervalid = setInterval("fun()", 1000); 
function fun() { 
if (i == 0) { 
window.location.href = "<?=$indeUurl?>";
clearInterval(intervalid); 
} 
document.getElementById("mes").innerHTML = i; 
i--; 
} 
</script> 
</head> 
<body> 
<div id="errorfrm"> 
<h3>未登录用户</h3>
<div id="error"> 
<img src="images/company/error.jpg" width="40" height="40" alt="" />
    无权访问系统。请重新登录...！
 将在 <span id="mes">2</span> 秒钟后返回首页！
</div> 

</div> 
</body> 
</html> 
