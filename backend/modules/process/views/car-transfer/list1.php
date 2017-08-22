<script src="js/jquery.ajaxSubmit.js"></script>
<table id="easyui-datagrid-process-car-transfer1-list"></table>
<!-- toolbar start -->
<div id="process-car-transfer1-add-toolbar">
	<div
		class="easyui-panel"
		title="需求发起基本信息"    
		iconCls='icon-save'
		border="false"
		style="width:100%;"
	>
		<table cellpadding="8" cellspacing="0">
		<tr>
			<td><div style="width:140px;text-align:right;">钉钉审批号：</div></td>
			<td>
				<?=$carTransferInfo['dd_number']?>
			</td>
			<td><div style="width:140px;text-align:right;">发起日期：</div></td>
			<td>
				<?=date('Y-m-d',$carTransferInfo['add_time'])?>
			</td>
			<td><div style="width:140px;text-align:right;">需求提报人：</div></td>
			<td>
				<?=$carTransferInfo['originator']?>
			</td>
		</tr>
		<tr>
			<td><div style="width:140px;text-align:right;">提报人所属运营公司：</div></td>
			<td>
				<?=$carTransferInfo['originator_operating_company_name']?>
			</td>
		</tr>
		</table>
	</div>
	<div
            class="easyui-panel"
            title="需求车辆"    
            iconCls='icon-save'
            border="false"
            style="width:100%;"
        >
		<div style="padding:4px;">
			<a href="javascript:ProcessCarTransfer1List.add()" class="easyui-linkbutton" data-options="iconCls:'icon-add'">添加</a>
			<a href="javascript:ProcessCarTransfer1List.edit()" class="easyui-linkbutton" data-options="iconCls:'icon-add'">修改</a>
			<a href="javascript:ProcessCarTransfer1List.remove()" class="easyui-linkbutton" data-options="iconCls:'icon-remove'">删除</a>
		</div>
	</div>
</div>
<!-- toolbar end -->
<!-- 窗口 -->
<div id="easyui-dialog-process-car-transfer1-list-add"></div>
<div id="easyui-dialog-process-car-transfer1-list-edit"></div>
<!-- 窗口 -->
<script>
    var ProcessCarTransfer1List = new Object();
	ProcessCarTransfer1List.init = function(){
        //初始化datagrid
        $('#easyui-datagrid-process-car-transfer1-list').datagrid({
            method: 'get', 
            url:"<?php echo yii::$app->urlManager->createUrl(['process/car-transfer/get-list1-data']).'&transfer_id='.$transfer_id; ?>",  
            toolbar: "#process-car-transfer1-add-toolbar",
            border: false,
            fit: true,
            pagination: true,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
            showFooter: true,
            frozenColumns: [[
                {field: 'ck',checkbox: true}, 
                {field: 'id',title: 'id',hidden: true},   
                {field: 'car_brand_name',title: '车辆品牌',sortable: true},
				{field: 'car_model_name',title: '车型',sortable: true}
            ]],
            columns:[[
                {field: 'number',title: '需求台数',sortable: true},
				{field: 'pre_operating_company_name',title: '调入前所属运营公司'},
				{field: 'after_operating_company_name',title: '调入后所属运营公司'},
				{field: 'is_owner_change',title: '是否变更车辆所有人',
					formatter: function(value){
                        if(value==1){
							return '是';
						}else if(value==2){
							return '否';
						}else {
							return '';
						}
                    }
				},
				{field: 'after_owner_name',title: '调入后车辆所有人'},
				{field: 'note',title: '备注',width: 120}
            ]]
        });

		//初始化新增车辆调拨清单窗口
        $('#easyui-dialog-process-car-transfer1-list-add').dialog({
            title: '新增需求车辆',
            iconCls:'icon-add', 
            width: '700px',   
            height: '480px',   
            closed: true,   
            cache: true,   
            modal: true,
            maximizable: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    //回调添加页面submitForm方法
					ProcessCarTransfer1ListAdd.submitForm();
                }
            },{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui-dialog-process-car-transfer1-list-add').dialog('close');
                }
            }],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
		//初始化修改车辆调拨清单窗口
        $('#easyui-dialog-process-car-transfer1-list-edit').dialog({
            title: '修改需求车辆',
            iconCls:'icon-add', 
            width: '700px',   
            height: '480px',   
            closed: true,   
            cache: true,   
            modal: true,
            maximizable: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    //回调添加页面submitForm方法
					ProcessCarTransfer1ListEdit.submitForm();
                }
            },{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui-dialog-process-car-transfer1-list-edit').dialog('close');
                }
            }],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
    }
    ProcessCarTransfer1List.init();
	ProcessCarTransfer1List.getSelected = function(all){
		var datagrid = $('#easyui-datagrid-process-car-transfer1-list');
        if(all){
            var selectRows = datagrid.datagrid('getSelections');
            if(selectRows.length <= 0){
                $.messager.alert('错误','请选择要操作的记录','error');   
                return false;
            }
            return selectRows;
        }else{
            var selectRow = datagrid.datagrid('getSelected');
            if(!selectRow){
                $.messager.alert('错误','请选择要操作的记录','error');   
                return false;
            }
            return selectRow;
        }
		
	}
	//新增车辆调拨清单
    ProcessCarTransfer1List.add = function(){
        $('#easyui-dialog-process-car-transfer1-list-add').dialog('open');
        $('#easyui-dialog-process-car-transfer1-list-add').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/car-transfer/list1-add']); ?>&transfer_id="+<?=$carTransferInfo['id']?>);
    }
	//修改车辆调拨清单
    ProcessCarTransfer1List.edit = function(id){
		if(!id){
            var selectRow = this.getSelected();
            if(!selectRow)  return false;
            id = selectRow.id;
        }
        $('#easyui-dialog-process-car-transfer1-list-edit').dialog('open');
        $('#easyui-dialog-process-car-transfer1-list-edit').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/car-transfer/list1-edit']); ?>&id="+id);
    }
	
	//删除
	ProcessCarTransfer1List.remove = function(){
		var selectRow = this.getSelected();
        if(!selectRow) return false;
        var id = selectRow.id;
		$.messager.confirm('确定删除','您确定要删除该数据？',function(r){
			if(r){
				$.ajax({
					type: 'get',
					url: '<?php echo yii::$app->urlManager->createUrl(['process/car-transfer/list1-remove']); ?>',
					data: {id: id},
					dataType: 'json',
					success: function(data){
						if(data.status){
							$.messager.alert('删除成功',data.info,'info');
							$('#easyui-datagrid-process-car-transfer1-list').datagrid('reload');
						}else{
							$.messager.alert('删除失败',data.info,'error');   
						}
					}
				});
			}
		});
	}
</script>