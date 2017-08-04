<table id="easyui-datagrid-drbac-mca-manage-controller"></table> 
<div id="easyui-datagrid-drbac-mca-manage-controller-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-drbac-mca-manage-controller">
                <ul class="search-main">
                    <li>
                        <div class="item-name">中文名称</div>
                        <div class="item-input">
                            <input class="easyui-textbox" name="name" style="width:150px;"></input>
                        </div>
                    </li>
                    <li class="search-button">
                        <a id="btn" href="javascript:DrbacMcaManageController.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                    </li>
                </ul>
            </form>
        </div>
    </div>
    <div class="easyui-panel" title="模块列表" style="padding:8px 0;width:100%" data-options="
        iconCls: 'icon-tip',
        border: false
    ">
        <a href="javascript:DrbacMcaManageController.add()" class="easyui-linkbutton" data-options="iconCls:'icon-add'">添加</a>
        <a href="javascript:DrbacMcaManageController.edit()" class="easyui-linkbutton" data-options="iconCls:'icon-edit'">修改</a>
        <a href="javascript:DrbacMcaManageController.manage()" class="easyui-linkbutton" data-options="iconCls:'icon-edit'">方法管理</a>
    </div>
</div>
<!-- 窗口 -->
<div id="easyui-dialog-drbac-mca-manage-controller-add"></div>
<div id="easyui-dialog-drbac-mca-manage-controller-edit"></div>
<!-- 窗口 -->
<script>
	var DrbacMcaManageController = new Object();
	DrbacMcaManageController.init = function(){
		//获取列表数据
		$('#easyui-datagrid-drbac-mca-manage-controller').datagrid({  
			method: 'get', 
		    url:"<?php echo yii::$app->urlManager->createUrl(['drbac/mca/get-controller-list','id'=>$id]); ?>",   
			fit: true,
			border: false,
			toolbar: "#easyui-datagrid-drbac-mca-manage-controller-toolbar",
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
                {field: 'module_code',title: '模块代码',width: 100},
	        	{field: 'controller_code',title: '控制器代码',width: 100},
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
		$('#easyui-dialog-drbac-mca-manage-controller-add').dialog({
        	title: '添加新控制器', 
            width: 600,
            height: 280,
            cache: true,   
            modal: true,
            closed: true,
            buttons: [{
				text:'确定',
				iconCls:'icon-ok',
				handler:function(){
					var data = $('#easyui-form-drbac-mca-add-controller').serialize();
					$.ajax({
						type: 'post',
						url: "<?php echo yii::$app->urlManager->createUrl(['drbac/mca/add-controller']); ?>",
						data: data,
						dataType: 'json',
						success: function(data){
							if(data.status){
								$.messager.alert('添加成功',data.info,'info');
								$('#easyui-dialog-drbac-mca-manage-controller-add').dialog('close');
								$('#easyui-datagrid-drbac-mca-manage-controller').datagrid('reload');
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
					$('#easyui-dialog-drbac-mca-manage-controller-add').dialog('close');
				}
			}]
        });
        //初始化修改窗口
		$('#easyui-dialog-drbac-mca-manage-controller-edit').dialog({
        	title: '修改控制器信息',   
            width: 600,   
            height: 280,   
            closed: true,   
            cache: true,   
            modal: true,
            buttons: [{
				text:'确定',
				iconCls:'icon-ok',
				handler:function(){
					var data = $('#easyui-form-drbac-mca-edit-controller').serialize();
					$.ajax({
						type: 'post',
						url: '<?php echo yii::$app->urlManager->createUrl(['drbac/mca/edit-controller']); ?>',
						data: data,
						dataType: 'json',
						success: function(data){
							if(data.status){
								$.messager.alert('修改成功',data.info,'info');
								$('#easyui-dialog-drbac-mca-manage-controller-edit').dialog('close');
								$('#easyui-datagrid-drbac-mca-manage-controller').datagrid('reload');
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
					$('#easyui-dialog-drbac-mca-manage-controller-edit').dialog('close');
				}
			}]
        });
	};
	DrbacMcaManageController.init();
	//获取选择的记录
	DrbacMcaManageController.getSelected = function(){
		var datagrid = $('#easyui-datagrid-drbac-mca-manage-controller');
		var selectRow = datagrid.datagrid('getSelected');
		if(!selectRow){
			$.messager.alert('错误','请选择要操作的记录','error');   
			return false;
		}
		return selectRow.id;
	}
	//添加控制器
	DrbacMcaManageController.add = function(){
		$('#easyui-dialog-drbac-mca-manage-controller-add').dialog('open');
		$('#easyui-dialog-drbac-mca-manage-controller-add').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['drbac/mca/add-controller','moduleId'=>$id]); ?>");
	}
	//修改
	DrbacMcaManageController.edit = function(){
        var id = this.getSelected();
        if(!id){
			return false;
        }
        $('#easyui-dialog-drbac-mca-manage-controller-edit').dialog('open');
		$('#easyui-dialog-drbac-mca-manage-controller-edit').dialog('refresh','<?php echo yii::$app->urlManager->createUrl(['drbac/mca/edit-controller']); ?>&id='+id);
	}
	//管理
	DrbacMcaManageController.manage = function(){
		var id = this.getSelected();
        if(!id){
			return false;
        }
        $('#easyui-panel-manage-module-action').panel('refresh','<?php echo yii::$app->urlManager->createUrl(['drbac/mca/manage-action']); ?>&id='+id);
	};
	//查询
	DrbacMcaManageController.search = function(){
		var form = $('#search-form-drbac-mca-manage-controller');
		var data = {};
		var searchCondition = form.serializeArray();
		for(var i in searchCondition){
			data[searchCondition[i]['name']] = searchCondition[i]['value'];
		}
		$('#easyui-datagrid-drbac-mca-manage-controller').datagrid('load',data);
	}
</script>