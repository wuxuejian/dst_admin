<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* 
 * @name 前台通知查看视图 
 * @author tanbenjiang
 * @date 2015-6-24
 * @var $this yii\web\View
 */ 
?>
<style>
 .frontend_notification_lookmessage{width:100%; height:100%;}
 .frontend_notification_lookmessage_title{width:100%; height:50px; float:left; text-align:center; line-height:50px; font-family:'Microsoft yahei'; font-size:14px; font-weight:bold;}
 .frontend_notification_lookmessage_blank{width:100%; height:20px; float:left; border-bottom:1px dotted #666666; text-align:right;}
 .frontend_notification_lookmessage_content{width:100%; height:auto; float:left; margin-top:10px; text-align:left; line-height:24px;}
</style>

<div class="frontend_notification_lookmessage">
   <div class="frontend_notification_lookmessage_title">【<?php echo $datas['typename']?>】<?php echo $datas['title']?></div>
   <div class="frontend_notification_lookmessage_blank">日期：<?php echo $datas['add_time']?></div>
   <div class="frontend_notification_lookmessage_content"><?php echo $datas['content']?></div>
</div>

