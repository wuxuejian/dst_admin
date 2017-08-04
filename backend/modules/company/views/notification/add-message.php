<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* 
 * @name 前台添加消息视图 
 * @author yangping
 * @beizhu tanbenjiang edit 2015-6-18
 * @var $this yii\web\View
 */ 
?>

<script type="text/javascript">
 $(function(){
    var company_message_addmessage_editor;
    window.company_message_addmessage_editor = KindEditor.create('#content',{
					resizeType:1,						
		            urlType:'domain', // 带有域名的绝对路径
	});
 });
</script>

<style>
.company_message_addmessage_form{width:100%; height:auto; margin:0 auto;}
</style>

<div title="发送消息" style="width:800px;height:100%;padding:10px; padding-left:6px; margin:0 auto; border:1px solid #95b8e7; border-top:none">
  <div class="company_message_addmessage_form">
<form id="form-message-add" name="form1" method="post" action="" style="margin-top:30px;">
       <table width="100%">
       	  <tr>
          	<td width="150">接收者：</td>
          	<td>
          		<select name="search_level" id="search_level" class="easyui-combobox" data-options="editable:false,onChange:function(newValue, oldValue){
          			$.get(Message.operateUrls['getUser'],{level_id:newValue},function(data){
						$('#to_user').combobox('clear').combobox('loadData',data);
					},'json');
          			
          		}"  style="width:180px;">
          			<option value="1">管理员</option>
          			<option value="2">商户</option>
          		</select>
          		<select name="to_user" id="to_user" class="easyui-combobox" data-options="editable:false"  style="width:180px;">
          			<?php //foreach($companylist as $list){ ?>
          			<!-- <option value="<?//=$list['id'] ?>"><?//=$list['company_name'] ?></option> -->
          			<?php //} ?>
          		</select>
          	</td>
          </tr>
         
          <tr>
          	<td>消息主题：</td>
          	<td><input id="title" name="title" type="text" class="easyui-textbox" data-options="required:true"  style="width:180px;"/> <span id="company_message_addmessage_tip"></span></td>
          </tr>
          <tr>
          	<td>消息内容：</td>
          	<td><textarea id="content" name="content" style="width:700px; height:300px;"></textarea></td>
          </tr>
          <tr>
          	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
          	<td><input type="checkbox" id="ck" name="ck" />邮件通知</td>
          </tr>
       </table>
       
       <!--提交按钮开始-->
<div style=" padding:10px; height:40px; margin-top:10px; margin-left:150px;">
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" style="width:80px;" onclick="Message.saveMessage()" >提交</a>&nbsp;&nbsp;
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" style="width:80px;"onclick="Message.resetSaveMessage()">重置</a>
	
</div>
<!--提交按钮结束/-->
    </form>
  </div>
</div>

<script type="text/javascript" src="js/company/message.js"> </script>
<script type="text/javascript">
	Message.operateUrls = <?= json_encode($urls)?>;
	Message.init(); 
</script>