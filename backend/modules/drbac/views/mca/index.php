<table id="easyui-datagrid-drbac-mca-index"></table> 
<div id="easyui-datagrid-drbac-mca-index-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-drbac-mca-index">
                <ul class="search-main">
                    <li>
                        <div class="item-name">中文名称</div>
                        <div class="item-input">
                            <input class="easyui-textbox" name="name" style="width:150px;"></input>
                        </div>
                    </li>
                    <li class="search-button">
                        <a onclick="DrbacMcaIndex.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                    </li>
                </ul>
            </form>
        </div>
    </div>
    <div class="easyui-panel" title="模块列表" style="padding:8px 4px;" data-options="
        iconCls: 'icon-tip',
        border: false
    ">
        <a onclick="DrbacMcaIndex.add()" class="easyui-linkbutton" data-options="iconCls:'icon-add'">添加</a>
        <a onclick="DrbacMcaIndex.edit()" class="easyui-linkbutton" data-options="iconCls:'icon-edit'">修改</a>
        <a onclick="DrbacMcaIndex.manage()" class="easyui-linkbutton" data-options="iconCls:'icon-remove'">管理</a>
    </div>
</div>
<!-- 窗口 -->
<div id="easyui-dialog-drbac-mca-index-add"></div>
<div id="easyui-dialog-drbac-mca-index-edit"></div>
<div id="easyui-window-drbac-mca-index-manage"></div>
<!-- 窗口 -->
<script>
	var DrbacMcaIndex = new Object();
	DrbacMcaIndex.init = function(){
		//获取列表数据
		$('#easyui-datagrid-drbac-mca-index').datagrid({  
			method: 'get', 
		    url:'<?php echo yii::$app->urlManager->createUrl(['drbac/mca/get-module-list']); ?>',   
			fit: true,
			border: false,
			toolbar: "#easyui-datagrid-drbac-mca-index-toolbar",
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
                {field: 'name',title: '中文名称',width: 160}
            ]],
		    columns:[[
	        	{field: 'module_code',title: '模块代码',width: 100},   
		        {
			        field: 'is_menu',title: '作为菜单',width: 100,align: 'center',
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
		$('#easyui-dialog-drbac-mca-index-add').dialog({
        	title: '添加新模块', 
            width: 600,
            height: 280,
            cache: true,   
            modal: true,
            closed: true,
            buttons: [{
				text:'确定',
				iconCls:'icon-ok',
				handler:function(){
					var data = $('#easyui-form-drbac-mca-add-module').serialize();
					$.ajax({
						type: 'post',
						url: '<?php echo yii::$app->urlManager->createUrl(['drbac/mca/add-module']); ?>',
						data: data,
						dataType: 'json',
						success: function(data){
							if(data.status){
								$.messager.alert('添加成功',data.info,'info');
								$('#easyui-dialog-drbac-mca-index-add').dialog('close');
								$('#easyui-datagrid-drbac-mca-index').datagrid('reload');
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
					$('#easyui-dialog-drbac-mca-index-add').dialog('close');
				}
			}]
        });
        //初始化修改窗口
		$('#easyui-dialog-drbac-mca-index-edit').dialog({
        	title: '修改模块信息',   
            width: 600,   
            height: 280,   
            closed: true,   
            cache: true,   
            modal: true,
            buttons: [{
				text:'确定',
				iconCls:'icon-ok',
				handler:function(){
					var data = $('#easyui-form-drbac-mca-edit-module').serialize();
					$.ajax({
						type: 'post',
						url: '<?php echo yii::$app->urlManager->createUrl(['drbac/mca/edit-module']); ?>',
						data: data,
						dataType: 'json',
						success: function(data){
							if(data.status){
								$.messager.alert('修改成功',data.info,'info');
								$('#easyui-dialog-drbac-mca-index-edit').dialog('close');
								$('#easyui-datagrid-drbac-mca-index').datagrid('reload');
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
					$('#easyui-dialog-drbac-mca-index-edit').dialog('close');
				}
			}]
        });
		//初始化管理窗口
		$('#easyui-window-drbac-mca-index-manage').window({
			title: '模块管理',
            width: '80%',   
            height: '80%',   
            closed: true,   
            cache: true,   
            modal: true,
            collapsible: false,
            minimizable: false, 
            maximizable: true            
		});
		//绑定记录双击事件
        $('#easyui-datagrid-drbac-mca-index').datagrid({
        	onDblClickRow: function(rowIndex,rowData){
        		DrbacMcaIndex.edit(rowData.id);
           }
        });
	}
	DrbacMcaIndex.init();
	//获取选择的记录
	DrbacMcaIndex.getSelected = function(){
		var datagrid = $('#easyui-datagrid-drbac-mca-index');
		var selectRow = datagrid.datagrid('getSelected');
		if(!selectRow){
			$.messager.alert('错误','请选择要操作的记录','error');   
			return false;
		}
		return selectRow.id;
	}
	//添加方法
	DrbacMcaIndex.add = function(){
		$('#easyui-dialog-drbac-mca-index-add').dialog('open');
		$('#easyui-dialog-drbac-mca-index-add').dialog('refresh','<?php echo yii::$app->urlManager->createUrl(['drbac/mca/add-module']); ?>');
	}
	//修改
	DrbacMcaIndex.edit = function(id){
		if(!id){
			id = this.getSelected();
		}
		if(!id){
			return;
		}
		$('#easyui-dialog-drbac-mca-index-edit').dialog('open');
		$('#easyui-dialog-drbac-mca-index-edit').dialog('refresh','<?php echo yii::$app->urlManager->createUrl(['drbac/mca/edit-module']); ?>&id='+id);
	}
	//管理
	DrbacMcaIndex.manage = function(){
		var id = this.getSelected();
		if(!id){
			return false;
		}
		var window = $('#easyui-window-drbac-mca-index-manage')
		window.window('open');
		window.window('refresh','<?php echo yii::$app->urlManager->createUrl(['drbac/mca/manage-module']); ?>&id='+id);
	}
	//查询
	DrbacMcaIndex.search = function(){
		var form = $('#search-form-drbac-mca-index');
		var data = {};
		var searchCondition = form.serializeArray();
		for(var i in searchCondition){
			data[searchCondition[i]['name']] = searchCondition[i]['value'];
		}
		$('#easyui-datagrid-drbac-mca-index').datagrid('load',data);
	}
</script>