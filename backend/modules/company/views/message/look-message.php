<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* 
 * @name 前台消息查看视图 
 * @author yangping
 * @beizhu tanbenjiang edit 2015-6-18
 * @var $this yii\web\View
 */ 
?>

<style type="text/css">
#company_message_lookmessage .touxiang {
            width: 65px;
            text-align: center;
            margin-top: 3px;
            float: left;
        }

#company_message_lookmessage .con {
            width: 570px;
            margin-left: 15px;
            float: left;
        }

        /* 图片圆角 */
#company_message_lookmessage img {
            -moz-border-radius: 6px; /* Gecko browsers */
            -webkit-border-radius: 6px; /* Webkit browsers */
            border-radius: 6px; /* W3C syntax */
            border: 3px solid #ddd;
        }
#company_message_lookmessage .martop3 {
        	margin-top:3px;
        }
#company_message_lookmessage .con .content {
        	line-height:20px;
        }

        /**************箭头开始的地方**************/
#company_message_lookmessage div.container {
            display: block;
            min-height: 50px;
            width: 520px;
            color: #999;
            position: relative;
            background-color: transparent;
        }

#company_message_lookmessage s{
            position: absolute;
            display: block;
            height: 0;
            width: 0;
            font-size: 0;
            line-height: 0;
            border-style: dashed dashed solid dashed;
            border-width: 10px;
        }

#company_message_lookmessage i{
            position: absolute;
            display: block;
            height: 0;
            width: 0;
            font-size: 0;
            line-height: 0;
            border-style: dashed dashed solid dashed;
            border-width: 10px;
        }
        
        /* 左边箭头 */
#company_message_lookmessage .left_i s {
            top: 20px;
            left: -20px;
            border-color: transparent #F3F2F0 transparent transparent;
        }

#company_message_lookmessage .left_i i {
            top: -10px;
            left: -9px;
            border-color: transparent #FFFEF4 transparent transparent;
        }
        /* 左边箭头 */

        /* 右边箭头 */
#company_message_lookmessage .right_i s {
            top: 20px;
            right: -62px;
            border-color: transparent transparent transparent #CDE0F4;
        }

#company_message_lookmessage .right_i i {
            top: -10px;
            right: -9px;
            border-color: transparent transparent transparent #E3EEF9;
        }
        /* 右边箭头 */

#company_message_lookmessage .content {
            border: 1px solid #F3F2F0;
            -moz-border-radius: 3px; /* Gecko browsers */
            -webkit-border-radius: 3px; /* Webkit browsers */
            background-color: #FFFEF4;
            width: 100%;
            padding: 15px 20px;
        }

        /* 最新回复下的回复背景颜色 */
#company_message_lookmessage .right_i .content {
            background-color: #E3EEF9;
            border: 1px solid #CDE0F4;
        }
        /* 最新回复下的回复背景颜色 */
        /***************箭头结束的地方*************/
</style>

<div title="发送消息" id="company_message_lookmessage" style="width:800px;height:auto;overflow:hidden;padding:10px; padding-left:6px; margin:0 auto; border:1px solid #95b8e7; border-top:none">
	 <div class="big_div"  style="margin-left:65px;margin-top:30px;">
	 	<?php foreach ($allreplylist as $allreplyk => $allreply){ ?>
	 		<?php if($allreply['direction'] == 'left'){ ?>
	 			<div class="left_i">
		            <div class="touxiang">
		                <div><img src="<?php echo Yii::$app->session->get('sUserAvatar')?>" class="t_img" width="62" height="62" style="width:62px;height:62px;" /></div>
		                <div class="fontsize11 colorccc martop3">
		                	<?=$allreply['from_name'] ?>  <br />
		                	<?=$allreply['time'] ?>
		                </div>
		            </div>
		            <div class="con">
		                <div class="container">
		                    <div class="content" >
		                    		标题：<?=$allreply['title'] ?><br />
		                        	内容：<?=$allreply['content'] ?>
		                    </div>
		                    <s><i></i></s>
		                </div>
		            </div>
		        </div>
		        <div style="clear: both;"></div>
	 		
	 		<?php }else{ ?>
	 			<div class="right_i" style="margin-top:10px;">
		            <div class="grid_10 alpha floatleft con">
		                <div class="container">
		                    <div class="content">
		                        	标题：<?=$allreply['title'] ?><br />
		                        	内容：<?=$allreply['content'] ?>
		                    </div>
		                    <s><i></i></s>
		                </div>
		            </div>
		            <div class="touxiang">
		                <div><img src="<?=Yii::$app->session->get('back_url_path').$allreply['from_name_avatar'] ?>" class="t_img" width="62" height="62" style="width:62px;height:62px;" /></div>
		                <div class="fontsize11 colorccc martop3">
		                	<?=$allreply['from_name'] ?>  <br />
		                	<?=$allreply['time'] ?>
		                </div>
		            </div>
		        </div>
		        <div style="clear: both;"></div>
	 		<?php } ?>
	 	
	 	<?php } ?>
        
    </div>

</div>

