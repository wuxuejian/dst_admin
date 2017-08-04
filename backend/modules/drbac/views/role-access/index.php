<table id="easyui-datagrid-drbac-role-access-index"></table> 
<div id="easyui-datagrid-drbac-role-access-index-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-drbac-role-access-index">
                <ul class="search-main">
                    <li>
                        <div class="item-name">角色名称</div>
                        <div class="item-input">
                            <input class="easyui-textbox" name="name" style="width:150px;"></input>
                        </div>
                    </li>
                    <li class="search-button">
                        <a onclick="DrbacRoleAccessIndex.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                    </li>
                </ul>
            </form>
        </div>
    </div>
    <div class="easyui-panel" title="角色列表" data-options="
        iconCls: 'icon-tip',
        border: false
    ">
        <?php if($buttons){ ?>
        <div style="padding:8px 4px;">
            <?php foreach($buttons as $val){ ?>
            <a
                onclick="<?= $val['on_click']; ?>"
                class="easyui-linkbutton"
                data-options="iconCls:'<?= $val['icon'] ;?>'"
            ><?= $val['text'] ;?></a>
            <?php } ?>
        </div>
        <?php } ?>
    </div>
</div>
<!-- 窗口 -->
<div id="easyui-dialog-drbac-role-access-index-add"></div>
<div id="easyui-dialog-drbac-role-access-index-edit"></div>
<div id="easyui-dialog-drbac-role-access-index-member-manage"></div>
<div id="easyui-dialog-drbac-role-access-index-access-manage"></div>
<!-- 窗口 -->
<script>
	var DrbacRoleAccessIndex = new Object();
	DrbacRoleAccessIndex.init = function(){
		//获取列表数据
		$('#easyui-datagrid-drbac-role-access-index').datagrid({  
			method: 'get', 
		    url:'<?php echo yii::$app->urlManager->createUrl(['drbac/role-access/get-role-list']); ?>',   
			fit: true,
			border: false,
			toolbar: "#easyui-datagrid-drbac-role-access-index-toolbar",
			pagination: true,
			loadMsg: '数据加载中...',
			striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
            frozenColumns: [[
                {field: 'ck',checkbox: true}, 
                {field: 'id',title: 'id',hidden: true},    
                {field: 'name',title: '角色名称',width: 160,sortable: true}
            ]],
		    columns:[[
		        {field: 'note',title: '备注',width: 400,align: 'left'}
		    ]]   
		});
		//初始化添加窗口
		$('#easyui-dialog-drbac-role-access-index-add').dialog({
        	title: '添加新角色', 
            width: 600,
            height: 280,
            cache: true,   
            modal: true,
            closed: true,
            buttons: [{
				text:'确定',
				iconCls:'icon-ok',
				handler:function(){
					var data = $('#easyui-form-drbac-role-access-add-role').serialize();
					$.ajax({
						type: 'post',
						url: '<?php echo yii::$app->urlManager->createUrl(['drbac/role-access/add-role']); ?>',
						data: data,
						dataType: 'json',
						success: function(data){
							if(data.status){
								$.messager.alert('添加成功',data.info,'info');
								$('#easyui-dialog-drbac-role-access-index-add').dialog('close');
								$('#easyui-datagrid-drbac-role-access-index').datagrid('reload');
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
					$('#easyui-dialog-drbac-role-access-index-add').dialog('close');
				}
			}]
        });
        //初始化修改窗口
		$('#easyui-dialog-drbac-role-access-index-edit').dialog({
        	title: '修改角色信息',   
            width: 600,   
            height: 280,   
            closed: true,   
            cache: true,   
            modal: true,
            buttons: [{
				text:'确定',
				iconCls:'icon-ok',
				handler:function(){
					var data = $('#easyui-form-drbac-role-access-edit-role').serialize();
					$.ajax({
						type: 'post',
						url: '<?php echo yii::$app->urlManager->createUrl(['drbac/role-access/edit-role']); ?>',
						data: data,
						dataType: 'json',
						success: function(data){
							if(data.status){
								$.messager.alert('修改成功',data.info,'info');
								$('#easyui-dialog-drbac-role-access-index-edit').dialog('close');
								$('#easyui-datagrid-drbac-role-access-index').datagrid('reload');
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
					$('#easyui-dialog-drbac-role-access-index-edit').dialog('close');
				}
			}]
        });
		//初始化成员管理窗口
		$('#easyui-dialog-drbac-role-access-index-member-manage').dialog({
			title: '角色成员管理',
            width: '60%',   
            height: '60%',   
            closed: true,   
            cache: true,   
            modal: true,
            collapsible: false,
            minimizable: false, 
            maximizable: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var data = $('#easyui-form-drbac-role-access-memeber-manage').serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['drbac/role-access/member-manage']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('操作成功',data.info,'info');
                                $('#easyui-dialog-drbac-role-access-index-member-manage').dialog('close');
                            }else{
                                $.messager.alert('操作失败',data.info,'error');
                            }
                        }
                    });
                }
            },{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui-dialog-drbac-role-access-index-member-manage').dialog('close');
                }
            }]         
		});
        //初始化权限管理窗口
        $('#easyui-dialog-drbac-role-access-index-access-manage').dialog({
            title: '角色权限管理',
            width: '80%',   
            height: '80%',   
            closed: true,   
            cache: true,   
            modal: true,
            collapsible: false,
            minimizable: false, 
            maximizable: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var data = $('#easyui-form-role-access-access-manage').serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['drbac/role-access/access-manage']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('操作成功',data.info,'info');
                                $('#easyui-dialog-drbac-role-access-index-access-manage').dialog('close');
                            }else{
                                $.messager.alert('操作失败',data.info,'error');
                            }
                        }
                    });
                }
            },{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui-dialog-drbac-role-access-index-access-manage').dialog('close');
                }
            }]         
        });
	}
	DrbacRoleAccessIndex.init();
	//获取选择的记录
	DrbacRoleAccessIndex.getSelected = function(){
		var datagrid = $('#easyui-datagrid-drbac-role-access-index');
		var selectRow = datagrid.datagrid('getSelected');
		if(!selectRow){
			$.messager.alert('错误','请选择要操作的记录','error');   
			return false;
		}
		return selectRow.id;
	}
	//添加方法
	DrbacRoleAccessIndex.add = function(){
		$('#easyui-dialog-drbac-role-access-index-add').dialog('open');
		$('#easyui-dialog-drbac-role-access-index-add').dialog('refresh','<?php echo yii::$app->urlManager->createUrl(['drbac/role-access/add-role']); ?>');
	}
	//修改
	DrbacRoleAccessIndex.edit = function(id){
		if(!id){
			id = this.getSelected();
		}
		if(!id){
			return;
		}
        if(id == 1){
            $.messager.alert('操作失败','无法修改系统角色"超级管理员"！','error');
            return false;
        }
		$('#easyui-dialog-drbac-role-access-index-edit').dialog('open');
		$('#easyui-dialog-drbac-role-access-index-edit').dialog('refresh','<?php echo yii::$app->urlManager->createUrl(['drbac/role-access/edit-role']); ?>&id='+id);
	}
    //删除
    DrbacRoleAccessIndex.remove = function(){
        var id = this.getSelected();
        if(!id){
            return;
        }
        if(id == 1){
            $.messager.alert('操作失败','无法删除系统角色"超级管理员"！','error');
            return false;
        }
        $.messager.confirm('删除确定','您确定要删除该角色？',function(r){
            if(r){
                $.ajax({
                    type: 'get',
                    url: "<?= yii::$app->urlManager->createUrl(['drbac/role-access/remove-role']); ?>",
                    dataType: 'json',
                    data: {"id": id},
                    success: function(data){
                        if(data.status){
                            $.messager.alert('操作成功',data.info,'info');
                            $('#easyui-datagrid-drbac-role-access-index').datagrid('reload');
                        }else{
                            $.messager.alert('操作失败',data.info,'error');
                        }
                    }
                });
            }
        });
    }
	//成员管理
	DrbacRoleAccessIndex.memberManage = function(){
		var id = this.getSelected();
		if(!id){
			return false;
		}
		var dialog = $('#easyui-dialog-drbac-role-access-index-member-manage')
		dialog.dialog('open');
		dialog.dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['drbac/role-access/member-manage']); ?>&roleId="+id);
	}
    //权限管理
    DrbacRoleAccessIndex.accessManage = function(){
        var id = this.getSelected();
        if(!id){
            return false;
        }
        if(id == 1 && <?php echo $isTrueSuperman ? 'false' : 'true' ;?>){
            $.messager.alert('操作失败','无法修改系统角色"超级管理员"的权限！','error');
            return false;
        }
        var dialog = $('#easyui-dialog-drbac-role-access-index-access-manage')
        dialog.dialog('open');
        dialog.dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['drbac/role-access/access-manage']); ?>&roleId="+id);
    }
	//查询
	DrbacRoleAccessIndex.search = function(){
		var form = $('#search-form-drbac-role-access-index');
		var data = {};
		var searchCondition = form.serializeArray();
		for(var i in searchCondition){
			data[searchCondition[i]['name']] = searchCondition[i]['value'];
		}
		$('#easyui-datagrid-drbac-role-access-index').datagrid('load',data);
	}
</script>