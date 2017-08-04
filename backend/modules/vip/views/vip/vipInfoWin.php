<script type="text/javascript" src="<?= yii::getAlias('@web'); ?>/jquery-easyui-1.4.3/plugins/jquery.edatagrid.js"></script>

<div style="padding:5px 3px;">
	<!-- 基本信息 begin -->
    <div class="easyui-panel" title="(1)基本资料" data-options="collapsible:true,collapsed:false,border:false">
        <form id="vipInfoWin_baseInfo" method="post">
        	<table cellpadding="5" style="width:100%;">
        	    <tr>
        			<td>会员编号：</td>
        			<td>
        			    <input class="easyui-textbox" name="code" style="width:153px;" disabled="true" value="系统将会自动生成"  />    
        			</td>
        			<td>会员名称：</td>
        			<td>
        			    <input class="easyui-textbox" name="client" style="width:153px" data-options="
							required:true,
							missingMessage:'会员名称不能为空！'
						"  />  
        			</td>
					<td>性别：</td>
        			<td>
        			    <select class="easyui-combobox" name="sex" style="width:153px;" data-options="panelHeight:'auto'">
                            <option value="1" selected>男</option>
                            <option value="0">女</option>
        			    </select>
        			</td>
        		</tr>
        	    <tr>
        			<td>手机号：</td>
        			<td>
        			    <input class="easyui-textbox" name="mobile" style="width:153px;" data-options="
							validType:'match[/^1[34578][0-9]{9}$/]',
							invalidMessage:'手机号格式错误！',
							required:true,
							missingMessage:'手机号不能为空！'
						"  />  
        			</td>
        			<td>邮箱：</td>
        			<td>
        			    <input class="easyui-textbox" name="email" style="width:153px;" data-options="
							validType:'email',
							invalidMessage:'邮箱格式错误！'
						"  />  
        			</td>
        			<td></td>
        			<td></td>
				</tr>
				<tr>
					<td>备注：</td>
					<td colspan="5">
						<input class="easyui-textbox" name="mark" style="width:490px;height:40px;" 
						data-options="multiline:true"
                        validType="length[150]"  /> 
					</td>
				</tr>
				<tr hidden>
					<td>会员ID：</td>
					<td>
						 <input class="easyui-textbox" name="id" style="width:153px;"  /> 
					</td>
        			<td></td>
        			<td></td>
        			<td></td>
        			<td></td>
				</tr>
			</table>
        </form>
    </div>  
    <!-- 基本信息 end -->
	
	<div style="height:10px;clear:both;"></div>
	
	<!-- 车辆列表 begin -->
	<div style="height:250px;" class="easyui-panel" title="(2)车辆信息" data-options="collapsible:true,collapsed:false,border:true">
		<table id="vipInfoWin_datagrid"></table>
		<div id="vipInfoWin_datagrid_toolbar">
			<div class="easyui-panel" style="padding:3px 2px;" data-options="border: false">
				<button onclick="vipInfoWin.add();" class="easyui-linkbutton" data-options="iconCls:'icon-add'">新增</button>
				<button onclick="vipInfoWin.remove();" class="easyui-linkbutton" data-options="iconCls:'icon-remove'">删除</button>
				<button onclick="vipInfoWin.cancel();" class="easyui-linkbutton" data-options="iconCls:'icon-undo'">取消编辑</button>
				<button onclick="vipInfoWin.save();" class="easyui-linkbutton" data-options="iconCls:'icon-save'">保存编辑</button>
			</div>
		</div>
	</div>
	<!-- 车辆列表 end -->
	
</div>
<script>
	var myData = <?php echo json_encode($myData); ?>;
	switch(myData.action){
		case 'edit':
			var oldData = myData.vipInfo;
			$('#vipInfoWin_baseInfo').form('load',oldData);
			break;
		default: break;
	}
	
	var connection_type = <?= json_encode($config['connection_type']); ?>;
	var connection_type_arr = new Array();
	for(var i in connection_type){
		var item = {'value':connection_type[i].value,'text':connection_type[i].text};
		connection_type_arr.push(item);
	}
	
	var vipInfoWin = new Object();
	$('#vipInfoWin_datagrid').edatagrid({ 
		method: 'get', 
		url:'<?php echo yii::$app->urlManager->createUrl(['vip/vehicle/get-vehicle-by-vip-id','vipId'=>$vipId]); ?>',    
		fit: true,
		border: false,
		toolbar: '#vipInfoWin_datagrid_toolbar',
		pagination: true,
		loadMsg: '数据加载中...',
		striped: true,
		checkOnSelect: true,
		rownumbers: true,
		singleSelect: true,
		columns:[[
			{field: 'ck',checkbox: true}, 
			{field: 'id',title: '车辆ID',width:40,align:'center',hidden:true},   
			{field: 'vip_id',title: '会员ID',width:40,align:'center',hidden:true},   
			{field: 'vehicle',title: '车牌号',width: 100,align:'center',sortable:true,editor:{
				type:'textbox',
				options:{
					validType:'match[/^[\u4e00-\u9fa5][A-Z][A-Z0-9]{5}$/]',
					invalidMessage:'车牌号格式错误！',
					required:true
				}
			}},   
			{field: 'vhc_model',title: '车型',width: 150,align:'center',sortable:true,editor:{type:'textbox',options:{required:true}}},
			{field: 'vhc_con_type',title: '充电连接方式',width: 150,align:'center',sortable:true,editor:{
				type: 'combobox', 
				options: { 
					data: connection_type_arr, 
					valueField: "value", 
					textField: "text",
					panelHeight: 'auto',
					editable: false,
					required:true
				}},
				formatter:function(value,row,index){
					for (var i = 0; i < connection_type_arr.length; i++) {
						if (connection_type_arr[i].value == value) {
							return connection_type_arr[i].text;
						}
					}
				}
			},		
			{field: 'mark',title: '备注',width: 350,align:'center',editor:'text'}
		]]
	});	
	
	vipInfoWin.add = function(){
		$('#vipInfoWin_datagrid').edatagrid('addRow');
	}
	vipInfoWin.remove = function(){ 
		var _datagrid = $('#vipInfoWin_datagrid');
		var selectRow = _datagrid.edatagrid('getSelected');
		if(!selectRow){
			$.messager.alert('提示','请先选择要操作的记录','info');   
			return false;
		}
		var index = _datagrid.edatagrid('getRowIndex',selectRow);
		_datagrid.edatagrid('deleteRow',index);
	}
	vipInfoWin.cancel = function(){
		$('#vipInfoWin_datagrid').edatagrid('cancelRow');
	}
	vipInfoWin.save = function(){
        $('#vipInfoWin_datagrid').edatagrid('saveRow');
    }
</script>