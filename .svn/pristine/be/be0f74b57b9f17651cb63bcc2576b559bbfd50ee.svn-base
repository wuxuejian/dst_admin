<table id="easyui-datagrid-system-config-item-index"></table>
<?php if($button){ ?>
<div id="easyui-datagrid-system-config-item-index-toolbar" style="padding:6px;">
	<?php foreach($button as $val){ ?>
	<a onclick="<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon']; ?>'"><?= $val['text']; ?></a>
	<?php } ?>
</div> 
<?php } ?>
<div id="easyui-dialog-system-config-item-index-add"></div>
<div id="easyui-dialog-system-config-item-index-edit"></div>
<script>
	var SystemConfigItemIndex = new Object();
	SystemConfigItemIndex.belongsId = <?php echo $belongsId; ?>;
	SystemConfigItemIndex.init = function(){
    	//初始化配置项目的datagrid
    	$('#easyui-datagrid-system-config-item-index').datagrid({
        	method: 'get', 
    	    url:'<?php echo yii::$app->urlManager->createUrl(['system/config/get-item-list','belongsId'=>$belongsId]); ?>',
    	    border: false,
    	    fit :true,
            fitColumns: false,
            singleSelect: true,
            loadMsg: '数据加载中...',
            pagination: true,   
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            toolbar: '#easyui-datagrid-system-config-item-index-toolbar',       
    	    columns:[[
				{field: 'ck',checkbox: true}, 
    	        {field:'id',title:'id',width: 100},   
    	        {field:'text',title:'文本内容',width: 200},   
    	        {field:'value',title:'对应值',width: 200},
    	        {field:'list_order',title:'排序号'},
    	        {field:'note',title:'备注',width: 300}   
    	    ]]   
    	}); 
    	//添加双击事件
    	$('#easyui-datagrid-system-config-item-index').datagrid({
			'onDblClickRow': function(rowData){
				SystemConfigItemIndex.edit(rowData.id);
			}
		});
    	//初始化添加窗口
    	$('#easyui-dialog-system-config-item-index-add').dialog({   
		    title: '添加配置项',   
		    width: 600,   
		    height: 240,   
		    closed: true,   
		    cache: true,   
		    modal: true,
		    buttons: [{
				text: '确定',
				iconCls: 'icon-ok',
				handler: function(){
					$.ajax({
						type: 'post',
						url: '<?php echo yii::$app->urlManager->createUrl(['system/config/item-add','belongsId'=>$belongsId]); ?>',
						data: $('#easyui-form-system-config-item-add').serialize(),
						dataType: 'json',
						success: function(data){
							if(data.status){
								$.messager.alert('添加成功',data.info,'info');
								$('#easyui-dialog-system-config-item-index-add').dialog('close');
								$('#easyui-datagrid-system-config-item-index').datagrid('reload');
							}else{
								$.messager.alert('添加失败',data.info,'error');
							}
						}
					});
				}
			},{
				text: '取消',
				iconCls: 'icon-cancel',
				handler: function(){
					$('#easyui-dialog-system-config-item-index-add').dialog('close');
				}
			}]
		    
		});   
    	//初始化修改窗口
    	$('#easyui-dialog-system-config-item-index-edit').dialog({   
		    title: '修改配置项',   
		    width: 600,   
		    height: 240,   
		    closed: true,   
		    cache: true,   
		    modal: true,
		    buttons: [{
				text: '确定',
				iconCls: 'icon-ok',
				handler: function(){
					$.ajax({
						type: 'post',
						url: '<?php echo yii::$app->urlManager->createUrl(['system/config/item-edit']); ?>',
						data: $('#easyui-form-system-config-item-edit').serialize(),
						dataType: 'json',
						success: function(data){
							if(data.status){
								$.messager.alert('修改成功',data.info,'info');
								$('#easyui-dialog-system-config-item-index-edit').dialog('close');
								$('#easyui-datagrid-system-config-item-index').datagrid('reload');
							}else{
								$.messager.alert('修改失败',data.info,'error');
							}
						}
					});
				}
			},{
				text: '取消',
				iconCls: 'icon-cancel',
				handler: function(){
					$('#easyui-dialog-system-config-item-index-edit').dialog('close');
				}
			}]
		    
		});   
	} 
	SystemConfigItemIndex.init();
	//获取选中记录
	SystemConfigItemIndex.getSelected = function(){
		var datagrid = $('#easyui-datagrid-system-config-item-index');
		var selectRow = datagrid.datagrid('getSelected');
		if(!selectRow){
			$.messager.alert('错误','请选择要操作的记录','error');   
			return false;
		}
		return selectRow.id;
	}
	//添加
	SystemConfigItemIndex.add = function(){
		$('#easyui-dialog-system-config-item-index-add').dialog('open');
		$('#easyui-dialog-system-config-item-index-add').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['system/config/item-add','belongsId'=>$belongsId]); ?>");
	}
	//修改
	SystemConfigItemIndex.edit = function(id){
		if(!id){
			id = this.getSelected();
		}
		if(!id){
			return false;
		}
		$('#easyui-dialog-system-config-item-index-edit').dialog('open');
		$('#easyui-dialog-system-config-item-index-edit').dialog('refresh','<?php echo yii::$app->urlManager->createUrl(['system/config/item-edit']); ?>&id='+id);
	}
	//删除
	SystemConfigItemIndex.remove = function(){
		var id = this.getSelected();
		if(!id){
			return false;
		}
		$.messager.confirm('删除确定','您确定要删除该配置项？',function(r){
			if(r){
				$.ajax({
					type: 'get',
					url: '<?php echo yii::$app->urlManager->createUrl(['system/config/item-remove']) ?>',
					data: {'id':id},
					dataType: 'json',
					success:function(data){
						if(data.status){
							$.messager.alert('删除成功',data.info,'info');
							$('#easyui-datagrid-system-config-item-index').datagrid('reload');
						}else{
							$.messager.alert('删除失败',data.info,'error');
						}
					}
				});
			}
		});
	}
	//锁定
	SystemConfigItemIndex.lock = function(){
		var id = this.getSelected();
		if(!id){
			return false;
		}
		$.ajax({
			type: 'get',
			url: "<?php echo yii::$app->urlManager->createUrl(['system/config/item-lock']) ?>",
			data: {'id':id},
			dataType: 'json',
			success:function(data){
				if(data.status){
					$.messager.alert('操作成功',data.info,'info');
					$('#easyui-datagrid-system-config-item-index').datagrid('reload');
				}else{
					$.messager.alert('操作失败',data.info,'error');
				}
			}
		});
	}
</script>