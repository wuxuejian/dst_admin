<?php
@session_start();

$conf = include('../config.php');
if ($_GET['action']=='logout'){
    $_SESSION['tool_logined'] = 0;
}

if ($_POST['submit'] && !$_SESSION['tool_logined']){
    if ($_POST['name'] == $conf['auth_name'] && md5($_POST['pass'])==$conf['auth_pass']){
        $_SESSION['tool_logined'] = 1;
    }else{
        $_SESSION['tool_logined'] = 0;
    }
}
if (!$_SESSION['tool_logined']){
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=7" />
<title>登录</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<form name="form1" method="post" action="">
<table align="center">
    <tr><td align="center">用户名:</td><td><input type="text" name="name" style="width:150px"></td></tr>
    <tr><td align="center">验证码:</td><td><input type="password" name="pass" style="width:150px"></td></tr>
    <tr><td colspan="2" align="center"><input type="submit" name="submit" value="登 录"></td></tr>
    <?php if ($_POST['submit']){?>
    <tr><td colspan="2" align="center"><div style="color:red">认证失败，请重新登录...</div></td></tr>
    <?php }?>
</table>
</form>
</body>
<?php
    exit;
}
?>

<?php
$wwwroot = $_SERVER['DOCUMENT_ROOT'];
$action  = $_GET['act'];
if (!$action){
    $action = 'update';
}

$output1 = shell_exec("sudo -u www chmod 0770 {$wwwroot}");
if (trim($output1) == ''){
    $output1 = "chmod ok.";
}

$workDir = substr($wwwroot, 0, strpos($wwwroot, 'backend'));
switch($action){
    case 'clean':
        $cmds[] = "sudo -u www {$wwwroot}/tool/update/svn_clean.sh {$workDir} 2>&1";
        break;
    default:
        $cmds[] = "sudo -u www {$wwwroot}/tool/update/svn_up.sh {$workDir} 2>&1";
} 

foreach($cmds as $cmd){
    echo $cmd.'<br>';
    $output2 .= shell_exec($cmd);
}

$output = $output1. "\r\n". $output2;
?>
<br>
<div style="color:red">
<li>有可能更新时间比较长，请耐心等候几分钟</li>
<li>如果执行结果显示锁定需要执行svn cleanup,则请加页面参数act=clean,即 tool/update/index.php?act=clean</li>
</div>
<br>
<?php ob_flush(); ?>
<textarea style="width: 800px; height:400px;">
<?php
echo $output;
?>
</textarea>
