<table id="easyui-datagrid-drbac-mca-manage-action"></table> 
<div id="easyui-datagrid-drbac-mca-manage-action-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-drbac-mca-manage-action">
                <ul class="search-main">
                    <li>
                        <div class="item-name">中文名称</div>
                        <div class="item-input">
                            <input class="easyui-textbox" name="name" style="width:150px;"></input>
                        </div>
                    </li>
                    <li class="search-button">
                        <a id="btn" href="javascript:DrbacMcaManageAction.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                    </li>
                </ul>
            </form>
        </div>
    </div>
    <div class="easyui-panel" title="方法列表" style="padding:8px 0;width:100%" data-options="
        iconCls: 'icon-tip',
        border: false
    ">
        <a href="javascript:DrbacMcaManageAction.add()" class="easyui-linkbutton" data-options="iconCls:'icon-add'">添加</a>
        <a href="javascript:DrbacMcaManageAction.edit()" class="easyui-linkbutton" data-options="iconCls:'icon-edit'">修改</a>
        <a href="javascript:DrbacMcaManageAction.btnManage()" class="easyui-linkbutton" data-options="iconCls:'icon-edit'">页面按钮管理</a>
    </div>
</div>
<!-- 窗口 -->
<div id="easyui-dialog-drbac-mca-manage-action-add"></div>
<div id="easyui-dialog-drbac-mca-manage-action-edit"></div>
<div id="easyui-window-drbac-mca-manage-action-btn-manage"></div>
<!-- 窗口 -->
<script>
	var DrbacMcaManageAction = new Object();
	DrbacMcaManageAction.init = function(){
		//获取列表数据
		$('#easyui-datagrid-drbac-mca-manage-action').datagrid({  
			method: 'get', 
		    url:'<?php echo yii::$app->urlManager->createUrl(['drbac/mca/get-action-list','id'=>$id]); ?>',   
			fit: true,
			border: false,
			toolbar: "#easyui-datagrid-drbac-mca-manage-action-toolbar",
			pagination: true,
			loadMsg: '数据加载中...',
			striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
            frozenColumns: [[
                {field: 'ck',checkbox: true}, 
                {field: 'id',title: 'id'},   
                {field: 'list_order',title: '排序号'},   
                {field: 'name',title: '中文名称',width: 100}
            ]],
		    columns:[[
                {field: 'module_code',title: '模块',width: 100}, 
                {field: 'controller_code',title: '控制器',width: 100},  
	        	{field: 'action_code',title: '方法',width: 100},   
		        {
			        field: 'is_menu',title: '作为菜单',width: 80,align: 'center',
			        formatter: function(value,row,index){
				        if(value == 1){
							return '<span style="color:green">是</span>';
					    }else{
							return '否';
						}
				    }
				},
		        {field: 'note',title: '备注',width: 400,align: 'left'}
		    ]]   
		});
		//初始化添加窗口
		$('#easyui-dialog-drbac-mca-manage-action-add').dialog({
        	title: '添加新方法', 
            width: 600,
            height: 280,
            cache: true,   
            modal: true,
            closed: true,
            buttons: [{
				text:'确定',
				iconCls:'icon-ok',
				handler:function(){
					var data = $('#easyui-form-drbac-mca-add-action').serialize();
					$.ajax({
						type: 'post',
						url: "<?php echo yii::$app->urlManager->createUrl(['drbac/mca/add-action']); ?>",
						data: data,
						dataType: 'json',
						success: function(data){
							if(data.status){
								$.messager.alert('添加成功',data.info,'info');
								$('#easyui-dialog-drbac-mca-manage-action-add').dialog('close');
								$('#easyui-datagrid-drbac-mca-manage-action').datagrid('reload');
							}else{
								$.messager.alert('添加失败',data.info,'error');
							}
						}
					});
				}
			},{
				text:'取消',
				iconCls:'icon-cancel',
				handler:function(){
					$('#easyui-dialog-drbac-mca-manage-action-add').dialog('close');
				}
			}]
        });
        //初始化修改窗口
		$('#easyui-dialog-drbac-mca-manage-action-edit').dialog({
        	title: '修改方法信息',   
            width: 600,   
            height: 280,   
            closed: true,   
            cache: true,   
            modal: true,
            buttons: [{
				text:'确定',
				iconCls:'icon-ok',
				handler:function(){
					var data = $('#easyui-form-drbac-mca-edit-action').serialize();
					$.ajax({
						type: 'post',
						url: '<?php echo yii::$app->urlManager->createUrl(['drbac/mca/edit-action']); ?>',
						data: data,
						dataType: 'json',
						success: function(data){
							if(data.status){
								$.messager.alert('修改成功',data.info,'info');
								$('#easyui-dialog-drbac-mca-manage-action-edit').dialog('close');
								$('#easyui-datagrid-drbac-mca-manage-action').datagrid('reload');
							}else{
								$.messager.alert('修改失败',data.info,'error');
							}
						}
					});
				}
			},{
				text:'取消',
				iconCls:'icon-cancel',
				handler:function(){
					$('#easyui-dialog-drbac-mca-manage-action-edit').dialog('close');
				}
			}]
        });
        //初始化页面按钮管理窗口
		$('#easyui-window-drbac-mca-manage-action-btn-manage').window({
			title: '页面按钮管理',
            width: '70%',   
            height: '70%',   
            closed: true,   
            cache: true,   
            modal: true,
            collapsible: false,
            minimizable: false, 
            maximizable: true  
		});
	};
	DrbacMcaManageAction.init();
	//获取选择的记录
	DrbacMcaManageAction.getSelected = function(){
		var datagrid = $('#easyui-datagrid-drbac-mca-manage-action');
		var selectRow = datagrid.datagrid('getSelected');
		if(!selectRow){
			$.messager.alert('错误','请选择要操作的记录','error');   
			return false;
		}
		return selectRow.id;
	}
	//添加方法
	DrbacMcaManageAction.add = function(){
		$('#easyui-dialog-drbac-mca-manage-action-add').dialog('open');
		$('#easyui-dialog-drbac-mca-manage-action-add').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['drbac/mca/add-action','controllerId'=>$id]); ?>");
	}
	//修改
	DrbacMcaManageAction.edit = function(){
        var id = this.getSelected();
        if(!id){
			return false;
        }
        $('#easyui-dialog-drbac-mca-manage-action-edit').dialog('open');
		$('#easyui-dialog-drbac-mca-manage-action-edit').dialog('refresh','<?php echo yii::$app->urlManager->createUrl(['drbac/mca/edit-action']); ?>&id='+id);
	}
	//管理页面按钮
	DrbacMcaManageAction.btnManage = function(){
		var id = this.getSelected();
        if(!id){
			return false;
        }
        var window = $('#easyui-window-drbac-mca-manage-action-btn-manage');
        window.window('open');
        window.window('refresh','<?php echo yii::$app->urlManager->createUrl(['drbac/mca/manage-action-btn']); ?>&actionId='+id);
	};
	//查询
	DrbacMcaManageAction.search = function(){
		var form = $('#search-form-drbac-mca-manage-action');
		var data = {};
		var searchCondition = form.serializeArray();
		for(var i in searchCondition){
			data[searchCondition[i]['name']] = searchCondition[i]['value'];
		}
		$('#easyui-datagrid-drbac-mca-manage-action').datagrid('load',data);
	}
</script>