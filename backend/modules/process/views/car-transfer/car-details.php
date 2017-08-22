<script src="js/jquery.ajaxSubmit.js"></script>
<table id="easyui-datagrid-process-car-transfer2-details"></table>
<!-- toolbar start -->
<div id="process-car-transfer1-add-toolbar">
	<div
		class="easyui-panel"
		title="基本信息"    
		iconCls='icon-save'
		border="false"
		style="width:100%;"
	>
		<table cellpadding="8" cellspacing="0">
		<tr>
			<td><div style="width:140px;text-align:right;">钉钉审批号：</div></td>
			<td>
				<?=$carTransferListInfo['dd_number']?>
			</td>
			<td><div style="width:140px;text-align:right;">发起日期：</div></td>
			<td>
				<?=date('Y-m-d',$carTransferListInfo['add_time'])?>
			</td>
			<td><div style="width:140px;text-align:right;">需求提报人：</div></td>
			<td>
				<?=$carTransferListInfo['originator']?>
			</td>
		</tr>
		<tr>
			<td><div style="width:140px;text-align:right;">车辆品牌：</div></td>
			<td>
				<?=$carTransferListInfo['car_brand_name']?>
			</td>
			<td><div style="width:140px;text-align:right;">车型：</div></td>
			<td>
				<?=$carTransferListInfo['car_model_name']?>
			</td>
			<td><div style="width:140px;text-align:right;">需求台数：</div></td>
			<td>
				<?=$carTransferListInfo['number']?>
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
			<a href="javascript:ProcessCarTransfer2Details.add()" class="easyui-linkbutton" data-options="iconCls:'icon-add'">添加</a>
			<a href="javascript:ProcessCarTransfer2Details.edit()" class="easyui-linkbutton" data-options="iconCls:'icon-add'">修改</a>
			<a href="javascript:ProcessCarTransfer2Details.remove()" class="easyui-linkbutton" data-options="iconCls:'icon-remove'">删除</a>
		</div>
	</div>
</div>
<!-- toolbar end -->
<!-- 窗口 -->
<div id="easyui-dialog-process-car-transfer2-details-add"></div>
<div id="easyui-dialog-process-car-transfer2-details-edit"></div>
<!-- 窗口 -->
<script>
    var ProcessCarTransfer2Details = new Object();
	ProcessCarTransfer2Details.init = function(){
        //初始化datagrid
        $('#easyui-datagrid-process-car-transfer2-details').datagrid({
            method: 'get', 
            url:"<?php echo yii::$app->urlManager->createUrl(['process/car-transfer/get-details-data']).'&transfer_list_id='.$transfer_list_id; ?>",  
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
                {field: 'plate_number',title: '车牌',sortable: true},
				{field: 'vehicle_dentification_number',title: '车架号',sortable: true}
            ]],
            columns:[[
                {field: 'start_time',title: '发车日期',sortable: true,
                    formatter: function(value){
                        if(!isNaN(value) && value >0){
                            return formatDateToString(value);
                        }
                    }
				},
				{field: 'transport_company',title: '承运商'},
				{field: 'transport_tel',title: '承运人电话'},
				{field: 'transport_money',title: '运费'}
            ]]
        });

		//初始化新增车辆调拨清单窗口
        $('#easyui-dialog-process-car-transfer2-details-add').dialog({
            title: '新增需求车辆',
            iconCls:'icon-add', 
            width: '700px',   
            height: '300px',   
            closed: true,   
            cache: true,   
            modal: true,
            maximizable: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    //回调添加页面submitForm方法
					ProcessCarTransfer2DetailsAdd.submitForm();
                }
            },{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui-dialog-process-car-transfer2-details-add').dialog('close');
                }
            }],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
		//初始化修改车辆调拨清单窗口
        $('#easyui-dialog-process-car-transfer2-details-edit').dialog({
            title: '修改需求车辆',
            iconCls:'icon-add', 
            width: '700px',   
            height: '300px',   
            closed: true,   
            cache: true,   
            modal: true,
            maximizable: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    //回调添加页面submitForm方法
					ProcessCarTransfer2DetailsEdit.submitForm();
                }
            },{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui-dialog-process-car-transfer2-details-edit').dialog('close');
                }
            }],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
    }
    ProcessCarTransfer2Details.init();
	ProcessCarTransfer2Details.getSelected = function(all){
		var datagrid = $('#easyui-datagrid-process-car-transfer2-details');
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
    ProcessCarTransfer2Details.add = function(){
        $('#easyui-dialog-process-car-transfer2-details-add').dialog('open');
        $('#easyui-dialog-process-car-transfer2-details-add').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/car-transfer/details-add']); ?>&transfer_list_id="+<?=$carTransferListInfo['id']?>);
    }
	//修改车辆调拨清单
    ProcessCarTransfer2Details.edit = function(id){
		if(!id){
            var selectRow = this.getSelected();
            if(!selectRow)  return false;
            id = selectRow.id;
        }
        $('#easyui-dialog-process-car-transfer2-details-edit').dialog('open');
        $('#easyui-dialog-process-car-transfer2-details-edit').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/car-transfer/details-edit']); ?>&id="+id);
    }
	
	//删除
	ProcessCarTransfer2Details.remove = function(){
		var selectRow = this.getSelected();
        if(!selectRow) return false;
        var id = selectRow.id;
		$.messager.confirm('确定删除','您确定要删除该数据？',function(r){
			if(r){
				$.ajax({
					type: 'get',
					url: '<?php echo yii::$app->urlManager->createUrl(['process/car-transfer/details-remove']); ?>',
					data: {id: id},
					dataType: 'json',
					success: function(data){
						if(data.status){
							$.messager.alert('删除成功',data.info,'info');
							$('#easyui-datagrid-process-car-transfer2-details').datagrid('reload');
						}else{
							$.messager.alert('删除失败',data.info,'error');   
						}
					}
				});
			}
		});
	}
</script>