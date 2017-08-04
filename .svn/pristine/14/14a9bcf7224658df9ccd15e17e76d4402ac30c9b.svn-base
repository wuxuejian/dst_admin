<table id="easyui-datagrid-drbac-mca-manage-action-btn"></table> 
<div id="easyui-datagrid-drbac-mca-manage-action-btn-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-drbac-mca-manage-action-btn">
                <ul class="search-main">
                    <li>
                        <div class="item-name">文本内容</div>
                        <div class="item-input">
                            <input class="easyui-textbox" name="text" style="width:150px;"></input>
                        </div>
                    </li>
                    <li class="search-button">
                        <a id="btn" href="javascript:DrbacMcaManageActionBtn.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                    </li>
                </ul>
            </form>
        </div>
    </div>
    <div class="easyui-panel" title="按钮列表" style="padding:8px 0;width:100%" data-options="
        iconCls: 'icon-tip',
        border: false
    ">
        <a href="javascript:DrbacMcaManageActionBtn.add()" class="easyui-linkbutton" data-options="iconCls:'icon-add'">添加</a>
        <a href="javascript:DrbacMcaManageActionBtn.edit()" class="easyui-linkbutton" data-options="iconCls:'icon-edit'">修改</a>
        <a href="javascript:DrbacMcaManageActionBtn.remove()" class="easyui-linkbutton" data-options="iconCls:'icon-remove'">删除</a>
    </div>
</div>
<!-- 窗口 -->
<div id="easyui-dialog-drbac-mca-manage-action-btn-add"></div>
<div id="easyui-dialog-drbac-mca-manage-action-btn-edit"></div>
<!-- 窗口 -->
<script>
	var DrbacMcaManageActionBtn = new Object();
	DrbacMcaManageActionBtn.init = function(){
		//获取列表数据
		$('#easyui-datagrid-drbac-mca-manage-action-btn').datagrid({  
			method: 'get', 
		    url:'<?php echo yii::$app->urlManager->createUrl(['drbac/mca/get-action-btn-list','actionId'=>$actionId]); ?>',   
			fit: true,
			border: false,
			toolbar: "#easyui-datagrid-drbac-mca-manage-action-btn-toolbar",
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
                {field: 'text',title: '文本内容',width: 100}
            ]],
		    columns:[[
                {field: 'icon',title: '图标样式',width: 100}, 
                {field: 'on_click',title: '点击执行脚本',width: 200},
                {field: 'target_mca_code',title: '目标mca',width: 200},
		        {field: 'note',title: '备注',width: 400,align: 'left'}
		    ]]   
		});
		//初始化添加窗口
		$('#easyui-dialog-drbac-mca-manage-action-btn-add').dialog({
        	title: '添加新按钮', 
            width: 600,
            height: 280,
            cache: true,   
            modal: true,
            closed: true,
            buttons: [{
				text:'确定',
				iconCls:'icon-ok',
				handler:function(){
                    var form = $('#easyui-form-drbac-mca-add-action-btn');
                    if(!form.form('validate')){
                        return false;
                    }
					var data = form.serialize();
					$.ajax({
						type: 'post',
						url: '<?php echo yii::$app->urlManager->createUrl(['drbac/mca/add-action-btn']); ?>',
						data: data,
						dataType: 'json',
						success: function(data){
							if(data.status){
								$.messager.alert('添加成功',data.info,'info');
								$('#easyui-dialog-drbac-mca-manage-action-btn-add').dialog('close');
								$('#easyui-datagrid-drbac-mca-manage-action-btn').datagrid('reload');
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
					$('#easyui-dialog-drbac-mca-manage-action-btn-add').dialog('close');
				}
			}]
        });
        //初始化修改窗口
		$('#easyui-dialog-drbac-mca-manage-action-btn-edit').dialog({
        	title: '修改按钮信息',   
            width: 600,   
            height: 280,   
            closed: true,   
            cache: true,   
            modal: true,
            buttons: [{
				text:'确定',
				iconCls:'icon-ok',
				handler:function(){
					var data = $('#easyui-form-drbac-mca-edit-action-btn').serialize();
					$.ajax({
						type: 'post',
						url: '<?php echo yii::$app->urlManager->createUrl(['drbac/mca/edit-action-btn']); ?>',
						data: data,
						dataType: 'json',
						success: function(data){
							if(data.status){
								$.messager.alert('修改成功',data.info,'info');
								$('#easyui-dialog-drbac-mca-manage-action-btn-edit').dialog('close');
								$('#easyui-datagrid-drbac-mca-manage-action-btn').datagrid('reload');
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
					$('#easyui-dialog-drbac-mca-manage-action-btn-edit').dialog('close');
				}
			}]
        });
	};
	DrbacMcaManageActionBtn.init();
	//获取选择的记录
	DrbacMcaManageActionBtn.getSelected = function(){
		var datagrid = $('#easyui-datagrid-drbac-mca-manage-action-btn');
		var selectRow = datagrid.datagrid('getSelected');
		if(!selectRow){
			$.messager.alert('错误','请选择要操作的记录','error');   
			return false;
		}
		return selectRow.id;
	}
	//添加方法
	DrbacMcaManageActionBtn.add = function(){
		$('#easyui-dialog-drbac-mca-manage-action-btn-add').dialog('open');
		$('#easyui-dialog-drbac-mca-manage-action-btn-add').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['drbac/mca/add-action-btn','actionId'=>$actionId]); ?>");
	}
	//修改
	DrbacMcaManageActionBtn.edit = function(){
        var id = this.getSelected();
        if(!id){
			return false;
        }
        $('#easyui-dialog-drbac-mca-manage-action-btn-edit').dialog('open');
		$('#easyui-dialog-drbac-mca-manage-action-btn-edit').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['drbac/mca/edit-action-btn']); ?>&id="+id);
	}
	//删除
	DrbacMcaManageActionBtn.remove = function(){
		var id = this.getSelected();
        if(!id){
			return false;
        }
        $.messager.confirm('删除确定','您确定要删除该按钮？',function(r){
			if(r){
				$.ajax({
					'type': 'get',
					'url': '<?php echo yii::$app->urlManager->createUrl(['drbac/mca/remove-action-btn']); ?>',
                    'data': {"id": id},
					'dataType': 'json',
					'success': function(data){
						if(data.status){
							$.messager.alert('删除成功',data.info,'info');
							$('#easyui-datagrid-drbac-mca-manage-action-btn').datagrid('reload');
						}else{
							$.messager.alert('删除失败',data.info,'error');
						}
					}
		        });
			}
        });
	}
	//查询
	DrbacMcaManageActionBtn.search = function(){
		var form = $('#search-form-drbac-mca-manage-action-btn');
		var data = {};
		var searchCondition = form.serializeArray();
		for(var i in searchCondition){
			data[searchCondition[i]['name']] = searchCondition[i]['value'];
		}
		$('#easyui-datagrid-drbac-mca-manage-action-btn').datagrid('load',data);
	}
</script>