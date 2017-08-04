<?php
/**
 * Created by PhpStorm.
 * User: lingcheng
 * Date: 2015/7/24
 * Time: 14:31
 */
use yii\helpers\Url;
use yii\captcha\Captcha;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>找回密码</title>
    <link rel="stylesheet" type="text/css" href="css/company/retrieve.css" />
</head>
<script language="javascript" src="./js/jquery.min.js?"></script>
<script language="javascript" src="/js/company/retrieve.js?"></script>
<script>


    // 验证码图片绑定单击事件
    $(function(){
        $('#captchaimg').bind('click',function(){
            $(this).attr('src','<?php echo Url::to(['/company/retrieve/captcha'])?>'+'&random='+Math.random());
        });
    });

    var curCount = 60;
    function getCode(){
        if (curCount == 0) {
            window.clearInterval(getCode);//停止计时器
            $("#yzm").removeAttr("disabled");//启用按钮
            $("#yzm").val("重新发送验证码");
        }
        else {
            if(curCount==60){
               //发送请求获取验证码

                var mobile = $('#mobile').val();
                $.ajax({
                    type:"POST",
                    url:'<?=\yii\helpers\Url::to(['/company/retrieve/send-code'])?>',
                    data:{mobile:mobile},
                    success:function(data){
                        eval('data='+data);
                        if(data.success){
                            $("#message").html('验证码为'+data.message);
                        }else{
                            $("#message").html(data.message);
                        }
                    },
                    error: function(){
                        //请求出错处理
                    }
                });

            }
            $("#yzm").attr("disabled","true");//关闭按钮
            setTimeout(function(){
                curCount--;
                $("#yzm").val("请在" + curCount + "秒内输入");
                getCode();
            },1000)
        }
    }
    Retrieve.operateUrls = <?=json_encode($datas['urls'])?>;


</script>
<body class="retrieve">
<div class="jd-divmain">
    <div class="jd-dp"><span>找回密码</span></div>
    <div class="jd-blank"></div>

    <?
    if($s==1){

        ?>
        <ul class="jd-ulinfo">
            <li class="jd_liback1">1.填写手机号码 </li>
            <li class="jd_liback2s">2.验证身份</li>
            <li class="jd_liback2s">3.设置新密码</li>
            <li class="jd_liback3s">4.完成</li>
        </ul>
        <div class="jd-blank"></div>
    <div class="jd-divinfo">
        <p>请输入您注册时使用的手机号码，我们将给您发送手机验证码短信。</p>
        <form method="post" name="form1" id="form1" action="">
        <table border="0" class="jd-table">
            <tr>
                <td align="right">手机号<span class="scolor">*</span></td>
                <td align="left">
                    <input type="text" class="jd-input" name="mobile" id="mobile"  onfocus="Retrieve.clear()"/>
                </td>
            </tr>
            <tr>
                <td align="right">验证码<span class="scolor">*</span></td>
                <td align="left"><input type="text" class="jd-input" name="code" id="code" onfocus="Retrieve.clear()"/>
                    <?php echo Captcha::widget([
                        'name'=>'captchaimg',
                        'captchaAction'=>'retrieve/captcha',
                        'imageOptions'=>[
                            'id'=>'captchaimg',
                            'title'=>'换一张图片',
                            'alt'=>'换一张图片',
                            'style'=>'cursor:pointer;margin-left:25px;'
                        ],
                        'template'=>'{image}'
                    ]);?>
                </td>
            </tr>
            <tr>
                <td></td>
                <td align="left"><span class="scolor" id = "message"><?=$message?></span></td>
            </tr>
            <tr>
                <td></td>
                <td align="left">
                    <input type="button" value="下一步" class="jd-submit" onclick="Retrieve.getCode()"/>
                </td>
            </tr>
        </table>
        </form>
    </div>

    <?}elseif($s==2){

        ?>
        <ul class="jd-ulinfo">
            <li class="jd_liback1s">1.填写手机号码 </li>
            <li class="jd_liback2">2.验证身份</li>
            <li class="jd_liback2s">3.设置新密码</li>
            <li class="jd_liback3s">4.完成</li>
        </ul>
        <div class="jd-blank"></div>
        <div class="jd-divinfo">
            <p>&nbsp;</p>
            <form method="post" name="form2" id="form2" action="<?=\yii\helpers\Url::to(['/company/retrieve/set-pass'])?>">
            <table border="0" class="jd-table" id = "table1">
                <tr>
                    <td align="right">请选择验证身份方式<span class="scolor">*</span></td>
                    <td align="left">
                        <select name="validate_mode" id="validate_mode" class="validate_mode" onChange="Retrieve.validateModeSelect(this.value)">
                            <option value="mobile">手机号码</option>
                            <option value="email">电子邮箱</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td align="right">用户名<span class="scolor">*</span></td>
                    <td align="left"><strong class="jd-show"><?=$username?></strong>
                        <input type="hidden" id="username"  name="username"  value="<?=$username?>">
                    </td>
                </tr>
                <tr id="validate_mode_mobile_tr">
                    <td align="right">手机<span class="scolor">*</span></td>
                    <td align="left"><strong  class="jd-show"><?=$mobile?></strong>
                        <input type="hidden" id="mobile"  name="mobile"  value="<?=$mobile2?>">
                    </td>
                </tr>
                <tr id="validate_mode_email_tr" class="validate_mode_hidden">
                    <td align="right">电子邮箱<span class="scolor">*</span></td>
                    <td align="left"><strong  class="jd-show"><?=$email?></strong>
                        <input type="hidden" id="validate_mode_email"  name="validate_mode_email"  value="<?=$email2?>">
                    </td>
                </tr>
                <tr id="validate_mode_code_tr">
                    <td align="right">验证码<span class="scolor">*</span></td>
                    <td align="left"><input type="text" class="jd-input" name="Vcode" id="Vcode" onfocus="Retrieve.clear()" />
                        <input type="button" id="yzm"  name="yzm"  value="免费获取验证码" class="jd-button" onclick="getCode()"/>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left"><span class="scolor" id = "message"><?=$message?></span></td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left">
                        <input id = "next" type="button" value="下一步" class="jd-submit" onclick="Retrieve.setCode()" />
                        <input  type="button" id="send"  name="send"  value="发送邮件" class="validate_mode_hidden" onclick="Retrieve.SendSms()"/>
                    </td>
                </tr>
            </table>
                <table  class="jd-table2" id = "table2">
                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="2" align="left">
                            <p style="font-size: 16px;color:#71b247;">验证邮件已发送成功！</p>
                            <p style="color:red;">(请立即完成验证，邮箱验证不通过则修改邮箱失败）</p>
                            <p>验证邮件2小时内有效，请尽快登录您的邮箱点击验证链接完成验证。</p>

                        </td>

                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                </table>
            </form>
        </div>
    <?}elseif($s==3){?>
        <ul class="jd-ulinfo">
            <li class="jd_liback1s">1.填写手机号码 </li>
            <li class="jd_liback2s">2.验证身份</li>
            <li class="jd_liback2">3.设置新密码</li>
            <li class="jd_liback3s">4.完成</li>
        </ul>
        <div class="jd-blank"></div>
        <div class="jd-divinfo">
            <p>&nbsp;</p>
            <form method="post" name="form3" id="form3" action="">
            <table border="0" class="jd-table">
                <tr>
                    <td align="right">新密码<span class="scolor">*</span></td>
                    <td align="left"><input type="password" class="jd-input" name="p1" id="p1" onfocus="Retrieve.clear()"/></td>
                </tr>
                <tr>
                    <td align="right">重复密码<span class="scolor">*</span></td>
                    <td align="left"><input type="password" class="jd-input" name="p2" id="p2" onfocus="Retrieve.clear()" onchange="Retrieve.compare()"/></td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left"><span class="scolor" id = "message"><?=$message?></span></td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left">
                        <input type="hidden" name="id" id="id" value="<?=$id?>"/>
                        <input type="button" value="下一步" class="jd-submit"  onclick="Retrieve.updatePass()"/>
                    </td>
                </tr>
            </table>
            </form>
        </div>
    <?}elseif($s==4){?>
        <ul class="jd-ulinfo">
            <li class="jd_liback1s">1.填写手机号码 </li>
            <li class="jd_liback2s">2.验证身份</li>
            <li class="jd_liback2s">3.设置新密码</li>
            <li class="jd_liback3">4.完成</li>
        </ul>
        <div class="jd-blank"></div>
        <div class="jd-divinfo">
            <p>&nbsp;</p>
            <p><a href="<?=\yii\helpers\Url::to(['/company/login/index'])?>">修改成功，请重新登陆!</a></p>
        </div>
    <?}?>
</div>
</body>
</html>
