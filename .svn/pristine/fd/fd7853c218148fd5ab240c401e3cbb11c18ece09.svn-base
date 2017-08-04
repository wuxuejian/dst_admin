<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\base\View;

/* 
 * @name 前台系统通知列表显示视图
 * @author tanbenjiang
 * @date 2015-6-23
 * @var $this yii\web\View
 */ 
?>

<div id="layout-communityrank" class="easyui-layout" style="width:100%;height:100%;">
	<div class="easyui-panel" title="消息查询" data-options="region:'north',collapsible:true,border:false" style="width:100%; height:80px;">
		<form name="message_index_search" id="member_manage_index_search" method="post" style="margin-top:20px;margin-left:50px;">
		    关键词：<input id="key_word" name="key_word" type="text" class="easyui-textbox"  style="width:150px;" />  &nbsp;&nbsp;&nbsp;        
		            
		             选择查看类型：<select name="search_level" id="search_level" class="easyui-combobox" data-options="editable:false"   style="width:120px;">
		             	<option value="1">所有消息</option>
		             	<option value="2">所有收到的消息</option>
		             	<option value="3">所有发送的消息</option>
		             	<option value="4">未阅读的消息</option>
		             	<option value="5">已阅读的消息</option>
		             </select>
			        <input type="hidden" name="moduleid" id="moduleid" value="" />		 
		    <a href="#" class="easyui-linkbutton" iconCls="icon-search" style="height:25px" onclick="Message.searchMessageInfo('#message_list','#message_index_search')"> 搜 索 </a>
	 	</form>
	</div>
	
	
	<!-- 消息列表 -->
	<div class="easyui-panel" data-options="region:'center'">
	  <div class="easyui-tabs" id="frontend_notification_tabs" data-options="border:false,fit:true,onSelect:Notification.loadData" style="width:100%; height:auto">
	  <?php foreach($notification_type as $key=>$type){?>
	    <div title="<?php echo $type['code_value']?>" module="<?php echo $type['code_key']?>" style="width:100%; height:100%;">
		<table id="frontend_notification_list_<?php echo $type['code_key']?>" class="easyui-datagrid" style="width:100%;height:auto" 
        	data-options="rownumbers:true,singleSelect:true,remoteSort:true,border:false, fit:true,
            url:'<?=\Yii::$app->urlManager->createUrl(['company/notification/messagelist','type_id'=>$type['code_key']])?>',
            pagination:true,pageSize:20,pageList:[20,50,100],toolbar:'#frontend_notification_message_toolbar_<?php echo $type['code_key']?>',
            rowStyler:function(index,row){}">
	        <thead>  
	            <th data-options="field:'sid',sortable:true,checkbox:true,align:'center'" width="40">id</th>
	            <th data-options="field:'itemid',align:'center'" width="60">ID</th>
				<th data-options="field:'title',align:'left'" width="300">消息主题</th> 
	            <th data-options="field:'is_read',align:'center',formatter:function(val,row,index){
	            	if(val == 0){
	            		return '未读';
	            	}else if(val == 1){
	            		return '<font color=red>已读</font>';
	            	}
	            }" width="150">状态</th> 
				<th data-options="field:'sub_typeid',align:'center',formatter:function(val,row,index){
				     return '<?php echo $type['code_value']?>';
				   }" width="120">通知类型</th>
				<th data-options="field:'add_time',align:'center',formatter:function(val,row,index){
	            	if(val){
	            		return Notification.getTime(val);
	            	}
	            }" width="150">发送时间</th> 
	            <th data-options="field:'user_name',align:'center',formatter:function(val,row,index){
				   if(val=='' || val == null){
				     return '系统';
				   }else{
				     return val;
				   }
				}" width="150">发件人</th> 
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
	        </thead>
    	</table>
    	
		<!--按钮部分-->
    	<div id="frontend_notification_message_toolbar_<?php echo $type['code_key']?>">
			    	<?php foreach ($buttons as $b){ ?> 
			    		<a href="#" class="easyui-linkbutton" iconCls="<?=$b['class']?>" onClick="<?=$b['click']?>"><?=$b['text']?> </a>
			    	<?php }?>
		</div>
	    <!--按钮部分结束/-->
		
		</div><!--tabs_1/-->	
		<?php }?>
	  </div><!--tabs/-->
	</div>
   
<!--查看通知windows开始-->
  <div id="frontend_notification_list_window" class="easyui-window" title=" 查看详情" data-options="iconCls:'icon-tip',modal:true,collapsible:false,minimizable:false,maximizable:false,closed:true" style="width:500px;height:400px;padding:10px;">
  </div>
<!--查看通知windows结束/-->

</div><!--layout/-->
<script type="text/javascript" src="js/company/notification.js"></script>
<script type="text/javascript">
	Notification.operateUrls = <?= json_encode($urls)?>;
	Notification.init(); 
</script>