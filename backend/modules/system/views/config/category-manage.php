<table id="easyui-treegrid-system-config-con-cate-manage"></table>
<div id="easyui-treegrid-system-config-con-cate-manage-toolbar" style="padding:6px;">
    <?php foreach($button as $val){ ?>
    <a onclick="<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon']; ?>'"><?= $val['text']; ?></a>
	<?php } ?>
</div>
<div id="easyui-dialog-system-config-category-manage-add"></div>
<div id="easyui-dialog-system-config-category-manage-edit"></div>
<script>
	var systemConfigCategoryManage = new Object();
	systemConfigCategoryManage.init = function(){
		//获取数据
		$('#easyui-treegrid-system-config-con-cate-manage').treegrid({
			idField: 'id',
        	treeField: 'title',
			url:'<?php echo yii::$app->urlManager->createUrl(['system/config/get-category-list']);  ?>',   
			border: false,
			fit: true,
			toolbar: '#easyui-treegrid-system-config-con-cate-manage-toolbar',
			singleSelect: true,
            loadMsg: '数据加载中...',
            pagination: false,   
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
		    columns:[[
		    	{field: 'ck',checkbox: true},
		        {field: 'id',title: 'id',width: 100,hidden: true},   
		        {field: 'title',title: '标题',width: 300},   
		        {field: 'key',title: '键名',width: 300,align: 'left'},
		        {field: 'is_lock',title: '锁定',width: 100,align: 'center',formatter: function(value){
		        	if(value == 0){
		        		return '否';
		        	}else{
		        		return '<span style="color:red">锁定</span>';
		        	}
		        }},
		        {field: 'list_order',title: '排序号',width: 100,align: 'left'}   
		    ]]  
		});
		//双击事件
		$('#easyui-treegrid-system-config-con-cate-manage').treegrid({
			'onDblClickRow': function(rowData){
				systemConfigCategoryManage.edit(rowData.id);
			}
		});
		//初始化添加窗口
		$('#easyui-dialog-system-config-category-manage-add').dialog({   
		    title: '添加配置类别',   
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
						url: '<?php echo yii::$app->urlManager->createUrl(['system/config/category-add']); ?>',
						data: $('#easyui-form-system-config-category-add').serialize(),
						dataType: 'json',
						success: function(data){
							if(data.status){
								$.messager.alert('添加成功',data.info,'info');
								$('#easyui-dialog-system-config-category-manage-add').dialog('close');
								$('#easyui-treegrid-system-config-con-cate-manage').treegrid('reload');
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
					$('#easyui-dialog-system-config-category-manage-add').dialog('close');
				}
			}]
		    
		});   
		//初始化修改窗口
		$('#easyui-dialog-system-config-category-manage-edit').dialog({   
		    title: '修改配置类别',   
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
						url: '<?php echo yii::$app->urlManager->createUrl(['system/config/category-edit']); ?>',
						data: $('#easyui-form-system-config-category-edit').serialize(),
						dataType: 'json',
						success: function(data){
							if(data.status){
								$.messager.alert('修改成功',data.info,'info');
								$('#easyui-dialog-system-config-category-manage-edit').dialog('close');
								$('#easyui-treegrid-system-config-con-cate-manage').treegrid('reload');
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
					$('#easyui-dialog-system-config-category-manage-edit').dialog('close');
				}
			}]
		    
		});   
	}
	systemConfigCategoryManage.init();
	//刷新treegrid
	systemConfigCategoryManage.reload = function(){
		$('#easyui-treegrid-system-config-con-cate-manage').treegrid('reload');
	}
	//添加
	systemConfigCategoryManage.add = function(){
		$('#easyui-dialog-system-config-category-manage-add').dialog('open');
		$('#easyui-dialog-system-config-category-manage-add').dialog('refresh','<?php echo yii::$app->urlManager->createUrl(['system/config/category-add']); ?>');
	}
	//选择条目
	systemConfigCategoryManage.getSelected = function(){
    	var treegrid = $('#easyui-treegrid-system-config-con-cate-manage');
		var selectRow = treegrid.treegrid('getSelected');
		if(!selectRow){
			$.messager.alert('错误','请选择要操作的记录','error');   
			return false;
		}
		return selectRow.id;
    };
	systemConfigCategoryManage.edit = function(id){
		if(!id){
			id = this.getSelected();
		}
		if(!id){
			return false;
		}
		$('#easyui-dialog-system-config-category-manage-edit').dialog('open');
		$('#easyui-dialog-system-config-category-manage-edit').dialog('refresh','<?php echo yii::$app->urlManager->createUrl(['system/config/category-edit']); ?>&id='+id);
	}
	//删除
	systemConfigCategoryManage.remove = function(){
		var id = this.getSelected();
		if(!id){
			return false;
		}
		$.messager.confirm('删除确定','您确定要删除该配置分类？',function(r){
			if(r){
    			$.ajax({
    				type: 'get',
    				url: '<?php echo yii::$app->urlManager->createUrl(['system/config/category-remove']); ?>&id='+id,
    				dataType: 'json',
    				success: function(data){
    					if(data.status){
    						$.messager.alert('删除成功',data.info,'info');
    						$('#easyui-treegrid-system-config-con-cate-manage').treegrid('reload');
    					}else{
    						$.messager.alert('删除失败',data.info,'error');
    					}
    				}
    			});
			}
		});
	}
	//锁定分类
	systemConfigCategoryManage.lock = function(){
		var id = this.getSelected();
		if(!id){
			return false;
		}
		$.ajax({
			type: 'get',
			url: "<?php echo yii::$app->urlManager->createUrl(['system/config/category-lock']); ?>&id="+id,
			dataType: 'json',
			success: function(data){
				if(data.status){
					$.messager.alert('操作成功',data.info,'info');
					$('#easyui-treegrid-system-config-con-cate-manage').treegrid('reload');
				}else{
					$.messager.alert('操作失败',data.info,'error');
				}
			}
		});
	}
</script>