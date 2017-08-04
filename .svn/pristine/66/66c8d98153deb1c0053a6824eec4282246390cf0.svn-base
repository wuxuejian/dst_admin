<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\base\View;

/* 
 * @name 前台消息列表显示视图
 * @author yangping
 * @beizhu tanbenjiang edit 2015-6-18 
 * @var $this yii\web\View
 */ 
?>

<!--引入引入kindeditor编辑器相关文件-->
<link rel="stylesheet" href="Kindeditor4.1.10/themes/default/default.css" />
<script charset="utf-8" src="Kindeditor4.1.10/kindeditor.js"></script>
<script charset="utf-8" src="Kindeditor4.1.10/lang/zh_CN.js"></script>

<div id="layout-communityrank" class="easyui-layout" style="width:100%;height:100%;">

	<div class="easyui-panel" title="消息查询" data-options="region:'north',collapsible:true,border:false" style="width:100%; height:80px;">
		<form name="message_index_search" id="member_manage_index_search" method="post" style="margin-top:20px;margin-left:50px;">
		    
		    关键词：<input id="key_word" name="key_word" type="text" class="easyui-textbox"  style="width:150px;" />  &nbsp;&nbsp;&nbsp;        
		            
		             选择查看类型：<select name="search_level" id="search_level" class="easyui-combobox" data-options="editable:false"   style="width:120px;">
		             	<option value="4">未阅读的消息</option>
		             	<option value="5">已阅读的消息</option>
		             </select>
		<input type="hidden" name="frontend_company_message_moduleid" id="frontend_company_message_moduleid" />
		    <a href="#" class="easyui-linkbutton" iconCls="icon-search" style="height:25px" onclick="Message.searchMessageInfo('#message_list','#message_index_search')"> 搜 索 </a>
	 	</form>
	</div>
	
	<!-- 消息列表 -->
	<div class="easyui-panel" data-options="region:'center'">
	   <div class="easyui-tabs" id="frontend_company_message_tabs" data-options="border:false,fit:true,onSelect:Message.loadData" style="width:100%; height:100%;">
	   <?php foreach($infoType as $key=>$value){?>
	     <div title="<?php echo $value?>" module="<?php echo $key?>">
		<table id="frontend_company_message_list_<?php echo $key?>" class="easyui-datagrid" style="width:100%;height:auto" 
        	data-options="rownumbers:true,singleSelect:true,remoteSort:true,border:false,fit:true,
            url:'<?=\Yii::$app->urlManager->createUrl(['company/message/messagelist','infoType'=>$key])?>', onLoadSuccess:TW_ENUM.parseGridAlias,
            pagination:true,pageSize:20,pageList:[20,50,100],toolbar:'#frontend_company_message_toolbar_<?php echo $key?>',
            rowStyler:function(index,row){}
            ">
	        <thead>  
	            <th data-options="field:'sid',sortable:true,checkbox:true,align:'center'" width="40">id</th>
	            <th data-options="field:'itemid',align:'center'" width="60">ID</th>
				<th data-options="field:'title',align:'left'" width="250">消息主题</th> 
	            <th data-options="field:'status',align:'center',formatter:function(val,row,index){
	            	if(val == 0){
	            		return '未读，未回复';
	            	}else if(val == 1){
	            		return '<font color=red>已读</font>，未回复';
	            	}else if(val == 2){
					    return '<font color=red>已读</font>，<font color=red>已回复</font>';
					}else{
					    return '';
					}
	            }" width="150">状态</th> 
				<th data-options="field:'typeid',align:'center',codeAlias:'message_type'" width="120">消息类型</th>
				<th data-options="field:'add_time',align:'center',formatter:function(val,row,index){
	            	if(val){
	            		return Message.getTime(val);
	            	}
	            }" width="150">发送时间</th> 
				<?php if($key == 0){?> <!--仅收到的消息显示发件人-->
	            <th data-options="field:'user_name',align:'center'" width="150">发件人</th> 
	            <th data-options="field:'from',align:'center',formatter:function(val,row,index){
	            	if(val == 0){
	            		return '系统';
	            	}else if(val == 1 ){
	            		return '商城会员';
	            	}else if(val == 2 ){
	            	
	            		return '商户';
	            	}else{
	            		return '管理员';
	            	}
	            	
	            }" width="120">发件人类型</th> 
				<?php }?>
				<?php if($key == 1){?> <!--发送的消息显示收件人-->
	            <th data-options="field:'company_name',align:'center'" width="150">收件人</th>
	            <th data-options="field:'to',align:'center',formatter:function(val,row,index){
	            	if(val == 0){
	            		return '系统';
	            	}else if(val == 1 ){
	            		return '商城会员';
	            	}else if(val == 2 ){
	            	
	            		return '商户';
	            	}
	            	
	            }" width="120">收件人类型</th> 
				<?php }?>
				
			   <!--暂时隐藏2个field-->
	           <th data-options="field:'is_reply',align:'center',hidden:true,formatter:function(val,row,index){
		          	if(val == 0){
		          		return '未回复';
		          	}else{
		          		return '已回复';
		          	}
	          }" width="80">回复</th>
			  <th data-options="field:'is_read',align:'center',hidden:true,formatter:function(val,row,index){
	            	if(val == 1){
	            		return '已读';
	            	}else{
	            		return '未读';
	            	}
	            }" width="150">状态</th> 
	        </thead>
    	</table>
		<!--按钮部分-->
    	<div id="frontend_company_message_toolbar_<?php echo $key?>">
			    	<?php foreach ($buttons as $b){ ?> 
			    		<a href="#" class="easyui-linkbutton" iconCls="<?=$b['class']?>" onClick="<?=$b['click']?>"><?=$b['text']?> </a>
			    	<?php }?>
		</div>
	    <!--按钮部分结束/-->
		 </div><!--收到的消息/-->
		 <?php }?>
	   </div><!--tabs/-->
	</div>
	

<!-- 以下为添加消息窗口  -->
	<div id="win-message-add" class="easyui-dialog" title="添加消息 " style="width:700px;height:300px"
         data-options="iconCls:'icon-edit',modal:true,closed:true,closable:true,inline:true,
         buttons: [{
					text:'确定',
					iconCls:'icon-ok',
					handler:Message.saveMessage
					
					
				},{
					text:'取消',
					handler:Message.cencelMessage
				}]
         ">
    <form id="form-message-add1111" name="form1" method="post" action="" style="margin-top:20px;margin-left:40px;">
       <table>
       	  <tr>
          	<td>接收者：</td>
          	<td>
          		<select name="search_level" id="search_level" class="easyui-combobox" data-options="editable:false,onChange:function(newValue, oldValue){
          			$.get(Message.operateUrls['getUser'],{level_id:newValue},function(data){
						$('#to_user').combobox('clear').combobox('loadData',data);
					},'json');
          			
          		}"  style="width:120px;">
          			<option value="1">管理员</option>
          			<option value="2">会员</option>
          		</select>
          		<select name="to_user1" id="to_user1" class="easyui-combobox" data-options="editable:false"  style="width:120px;">
          			<?php //foreach($companylist as $list){ ?>
          			<!-- <option value="<?//=$list['id'] ?>"><?//=$list['company_name'] ?></option> -->
          			<?php //} ?>
          		</select>
          	</td>
          </tr>
          <tr>
          	<td>消息主题：</td>
          	<td><input id="title1" name="title1" type="text" class="easyui-textbox"  /></td>
          </tr>
          <tr>
          	<td>消息内容：</td>
          	<td> <textarea rows="5" cols="50" id="content1" name="content1"></textarea></td>
          </tr>
       </table>
    </form>
    </div>
<!-- 添加消息窗口结束 -->
   
    
    <!-- 查看消息 -->
    <div id="win-message-view" class="easyui-dialog" title="消息预览" style="width:700px;height:300px"
         data-options="iconCls:'icon-edit',modal:true,closed:true,closable:true,inline:true,
         buttons: [{
					text:'关闭',
					handler:Message.cencelView
				}]
         ">
          <form id="form-message-view" name="form2" method="post" action="" style="margin-top:20px;margin-left:40px;">
          	发送者：<span id="liuy_user"></span> &nbsp;&nbsp;&nbsp;&nbsp;
          	接收者：<span id="jies_user"></span> &nbsp;&nbsp;&nbsp;&nbsp;
          	发送日期：<span id="send_data"></span>&nbsp;<br /><br />
          	标题：<span id="liuy_title"></span><br /><br />
          	<div style="float:left;">内容：</div>
          	<div style="float:left;"><span id="liuy_content"></span></div>
          	<div style="clear:both;"></div>
          	<br />
          	<span style="color:gray;">------------------------------------以下为回复消息------------------------------------</span>
          	<br /><br />
          	<div id="reply_div">
          		回复标题：<span id="reply_title_view"></span><br /><br />
          		<div style="float:left;">回复内容：</div>
          		<div style="float:left;"><span id="reply_content_view"></span></div>
          	</div>
          </form>    
	</div>
	
	
	<!-- 回复消息窗口 -->
	<div id="win-message-reply-view" class="easyui-dialog" title="消息回复" style="width:650px;height:400px"
		data-options="iconCls:'icon-edit',modal:true,closed:true,closable:true,inline:true,
         buttons: [{
					text:'保存',
					handler:Message.saveReplyView
				},{
					text:'关闭',
					handler:Message.cencelReplyView
				}]
         ">
		 <form id="form-message-reply-view" name="reply-form" method="post" action="" style="margin-top:20px;margin-left:40px;">
          	发送者：<span id="reply_liuy_user"></span> &nbsp;&nbsp;&nbsp;&nbsp;
          	接收者：<span id="reply_jies_user"></span> &nbsp;&nbsp;&nbsp;&nbsp;
          	发送日期：<span id="reply_send_data"></span>&nbsp;<br /><br />
          	标题：<span id="reply_liuy_title"></span>&nbsp;<br /><br />
          	内容：<span id="reply_liuy_content"></span>&nbsp;<br /><br />
          	<span style="color:gray;">------------------------------------以下为回复消息------------------------------------</span>
          	
          	<br /><br />
          	回复标题：<input id="reply_title" name="reply_title" type="text" class="easyui-textbox"  /><br /><br />
          	<div style="float:left;">回复内容：</div>
          	<div style="float:left;"><textarea rows="5" cols="62" id="reply_content"></textarea></div>
          	<div style="clear:both;"></div>
          	<br />
          	<input type="checkbox" id="email_ck" name="email_ck" />邮件通知
          	<span id="tishi" style="display: none;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;提示: 此条留言已有回复, 如果继续回复将更新原来回复的内容!</span>
          	<span id="reply_type">0</span>
          </form>
	</div>
	<!-- 回复消息窗口结束/ -->

</div><!--layout/-->
<script type="text/javascript" src="js/company/message.js"></script>
<script type="text/javascript">
	Message.operateUrls = <?= json_encode($urls)?>;
	Message.init(); 
</script>