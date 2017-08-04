<table id="easyui-datagrid-drbac-user-index"></table> 
<div id="easyui-datagrid-drbac-user-index-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-drbac-user-index">
                <ul class="search-main">
                    <li>
                        <div class="item-name">用户名</div>
                        <div class="item-input">
                            <input name="username" style="width:100%;" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">姓名</div>
                        <div class="item-input">
                            <input name="name" style="width:100%;" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">运营公司</div>
                        <div class="item-input">
                            <input name="operating_company" style="width:100%;" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">所属部门</div>
                        <div class="item-input">
                            <input name="department_name" style="width:100%;" />
                        </div>
                    </li>
                    <li class="search-button">
                        <button class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button onclick="DrbacUserIndex.resetForm();return false;" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
                    </li>
					 <li>
                        <div class="item-name">MAC地址</div>
                        <div class="item-input">
                            <input name="mac" style="width:100%;" />
                        </div>
                    </li>
                </ul>
            </form>
        </div>
    </div>
    <div class="easyui-panel" title="用户列表" style="width:100%" data-options="
        iconCls: 'icon-tip',
        border: false
    ">
        <?php if($buttons){ ?>
        <div style="padding:3px 2px;">
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
<div id="easyui-dialog-drbac-user-index-add"></div>
<div id="easyui-dialog-drbac-user-index-edit"></div>
<div id="easyui-dialog-drbac-user-index-mac-list"></div>
<div id="easyui-dialog-drbac-user-index-reset-password"></div>
<div id="easyui-dialog-drbac-user-index-role"></div>
<div id="easyui-dialog-drbac-user-index-import"></div>
<!-- 窗口 -->
<script>
	var DrbacUserIndex = new Object();
	// var DrbacUserMac = new Object();
	DrbacUserIndex.init = function(){
        var searchForm = $('#search-form-drbac-user-index');//查询表单
        var easyuiDatagrid = $('#easyui-datagrid-drbac-user-index');
		//获取列表数据
		easyuiDatagrid.datagrid({  
			method: 'get', 
		    url:"<?php echo yii::$app->urlManager->createUrl(['drbac/user/get-user-list']); ?>",   
			fit: true,
			border: false,
			toolbar: "#easyui-datagrid-drbac-user-index-toolbar",
			pagination: true,
			loadMsg: '数据加载中...',
			striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
            frozenColumns: [[
                {field: 'ck',checkbox: true},
                {field: 'id',title: 'id',hidden: true},
                {field: 'username',title: '账号',width: 100,halign: 'center',sortable: true}
            ]],
		    columns:[[
		        {field: 'name',title: '姓名',width: 70,halign: 'center',align: 'left',sortable: true},
                {field: 'operating_company',title: '运营公司',width: 170,halign: 'center',sortable: true},
                {field: 'department_name',title: '部门名称',width: 70,align: 'center',sortable: true},
                {
                    field: 'sex',title: '性别',width: 50,
                    align: 'center',sortable: true,
                    formatter: function(value,row,index){
                        switch(value){
                            case '0':
                                return '女';
                            case '1':
                                return '男';
                        }
                    }
                },
                {field: 'email',title: '邮箱',width: 110,align: 'left',halign: 'center',sortable: true},
                {field: 'telephone',title: '电话',width: 80,align: 'center',sortable: true},
                {field: 'qq',title: 'QQ',width: 100,align: 'left',halign: 'center',sortable: true},
                {
                    field: 'is_locked',title: '锁定',width: 60,align: 'center',sortable: true,
                    formatter: function(value,row,index){
                        switch(value){
                            case '0':
                                return '正常';
                            case '1':
                                return '<span style="color:red">锁定</span>';
                        }
                    }
                },
                {
                    field: 'active_time',title: '是否在线',width: 70,align: 'center',
                    sortable: true,
                    formatter: function(value,row,index){
                        var nowTimeStamp = Date.parse(new Date()) / 1000;
                        if((parseInt(value) + 90) >= nowTimeStamp){
                            return '<span style="color:green">在线</span>';
                        }else{
                            return '否';
                        }
                    }
                },
                {
                    field: 'ltime',title: '登陆时间',width: 130,align: 'center',
                    sortable: true,
                    formatter: function(value,row,index){
                        if(value != 0){
                            return formatDateToString(value,true);
                        }
                        
                    }
                },
                {field: 'lip',title: '登陆IP',width: 100,align: 'center',sortable: true},
		    ]]   
		});
        //查询表单自动化
        searchForm.submit(function(){
            var data = {};
            var searchCondition = $(this).serializeArray();
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = $.trim(searchCondition[i]['value']);
            }
            easyuiDatagrid.datagrid('load',data);
            return false;
        });
        searchForm.find('input[name=username]').textbox({
            onChange: function(){
                searchForm.submit();
            }
        });
		searchForm.find('input[name=mac]').textbox({
            onChange: function(){
                searchForm.submit();
            }
        });
        searchForm.find('input[name=name]').textbox({
            onChange: function(){
                searchForm.submit();
            }
        });
        searchForm.find('input[name=operating_company]').textbox({
            onChange: function(){
                searchForm.submit();
            }
        });
        searchForm.find('input[name=department_name]').textbox({
            onChange: function(){
                searchForm.submit();
            }
        });
        //查询表单自动化结束
		//初始化添加窗口
		$('#easyui-dialog-drbac-user-index-add').dialog({
        	title: '添加新用户', 
            width: 700,
            height: 600,
            cache: true,   
            modal: true,
            closed: true,
            buttons: [{
				text:'确定',
				iconCls:'icon-ok',
				handler:function(){
                    var form = $('#easyui-form-drbac-user-add-user');
                    if(!form.form('validate')){
                        return false;
                    }
					var data = form.serialize();
					$.ajax({
						type: 'post',
						url: "<?php echo yii::$app->urlManager->createUrl(['drbac/user/add-user']); ?>",
						data: data,
						dataType: 'json',
						success: function(data){
							if(data.status){
								$.messager.alert('添加成功',data.info,'info');
								$('#easyui-dialog-drbac-user-index-add').dialog('close');
								$('#easyui-datagrid-drbac-user-index').datagrid('reload');
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
					$('#easyui-dialog-drbac-user-index-add').dialog('close');
				}
			}]
        });
        //初始化修改窗口
		$('#easyui-dialog-drbac-user-index-edit').dialog({
        	title: '修改用户信息',   
            width: 700,
            height: 600,
            closed: true,   
            cache: true,   
            modal: true,
            buttons: [{
				text:'确定',
				iconCls:'icon-ok',
				handler:function(){
                    var form = $('#easyui-form-drbac-user-edit-user');
                    if(!form.form('validate')){
                        return false;
                    }
					var data = form.serialize();
					$.ajax({
						type: 'post',
						url: "<?php echo yii::$app->urlManager->createUrl(['drbac/user/edit-user']); ?>",
						data: data,
						dataType: 'json',
						success: function(data){
							if(data.status){
								$.messager.alert('修改成功',data.info,'info');
								$('#easyui-dialog-drbac-user-index-edit').dialog('close');
								$('#easyui-datagrid-drbac-user-index').datagrid('reload');
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
					$('#easyui-dialog-drbac-user-index-edit').dialog('close');
				}
			}]
        });
		 //初始化mac管理窗口
        $('#easyui-dialog-drbac-user-index-mac-list').dialog({
            title: '用户MAC地址管理',   
            width: '740px',   
            height: '400px',   
            closed: true,   
            cache: true,   
            modal: true,
            buttons: [],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
		//初始化导入mac窗口
        $('#easyui-dialog-drbac-user-index-import').dialog({
            title: '导入用户MAC地址信息文件',   
            width: '415px',   
            height: '200px',   
            closed: true,   
            cache: true,   
            modal: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
					console.log("1");
                    DrbacUserIndex.import2();
                }
            },{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui-dialog-drbac-user-index-import').dialog('close');
                }
            }],
            onClose: function(){
                $(this).dialog('clear');
            } 
        });
        //初始化修改用户密码窗口
        $('#easyui-dialog-drbac-user-index-reset-password').dialog({
            title: '修改用户密码', 
            width: 650,
            height: 140,
            cache: true,   
            modal: true,
            closed: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var form = $('#easyui-form-drbac-user-reset-password');
                    if(!form.form('validate')){
                        return false;
                    }
                    var data = form.serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['drbac/user/reset-password']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('修改成功',data.info,'info');
                                $('#easyui-dialog-drbac-user-index-reset-password').dialog('close');
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
                    $('#easyui-dialog-drbac-user-index-reset-password').dialog('close');
                }
            }]
        });
        //初始化角色分配窗口
        $('#easyui-dialog-drbac-user-index-role').dialog({
            title: '角色分配', 
            width: 600,
            height: 400,
            cache: true,   
            modal: true,
            closed: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var form = $('#easyui-form-drbac-user-role-distribution');
                    if(!form.form('validate')){
                        return false;
                    }
                    var data = form.serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['drbac/user/role-distribution']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('操作成功',data.info,'info');
                                $('#easyui-dialog-drbac-user-index-role').dialog('close');
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
                    $('#easyui-dialog-drbac-user-index-role').dialog('close');
                }
            }]
        });
	}
	DrbacUserIndex.init();
	//获取选择的记录
	DrbacUserIndex.getSelected = function(){
		var datagrid = $('#easyui-datagrid-drbac-user-index');
		var selectRow = datagrid.datagrid('getSelected');
		if(!selectRow){
			$.messager.alert('错误','请选择要操作的记录','error');   
			return false;
		}
		return selectRow.id;
	}
	//添加方法
	DrbacUserIndex.add = function(){
		$('#easyui-dialog-drbac-user-index-add').dialog('open');
		$('#easyui-dialog-drbac-user-index-add').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['drbac/user/add-user']); ?>");
	}
	//修改
	DrbacUserIndex.edit = function(id){
		if(!id){
			id = this.getSelected();
		}
		if(!id){
			return;
		}
		$('#easyui-dialog-drbac-user-index-edit').dialog('open');
		$('#easyui-dialog-drbac-user-index-edit').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['drbac/user/edit-user']); ?>&id="+id);
	}//用户mac地址管理
	DrbacUserIndex.macList = function(id){
		if(!id){
			id = this.getSelected();
		}
		if(!id){
			return;
		}
		$('#easyui-dialog-drbac-user-index-mac-list').dialog('open');
		$('#easyui-dialog-drbac-user-index-mac-list').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['drbac/user/mac-list']); ?>&id="+id);
	}
    //删除
    DrbacUserIndex.remove = function(){
        var id = this.getSelected();
        if(!id) return false;
        $.messager.confirm('删除确认','您确定要删除该用户？',function(r){
            if(r){
                $.ajax({
                    type: 'get',
                    url: "<?php echo yii::$app->urlManager->createUrl(['drbac/user/remove-user']); ?>",
                    data: {"id": id},
                    dataType: 'json',
                    success: function(data){
                        if(data.status){
                            $('#easyui-datagrid-drbac-user-index').datagrid('reload');
                            $.messager.alert('删除成功',data.info,'info');
                        }else{
                            $.messager.alert('删除失败',data.info,'error');
                        }
                    }
                });
            }
        });
    }
    //锁定账号
    DrbacUserIndex.lock = function(){
        var id = this.getSelected();
        if(!id) return false;
        $.ajax({
            type: 'get',
            url: "<?php echo yii::$app->urlManager->createUrl(['drbac/user/lock-user']); ?>",
            data: {"id": id},
            dataType: 'json',
            success: function(data){
                if(data.status){
                    $('#easyui-datagrid-drbac-user-index').datagrid('reload');
                    $.messager.alert('操作成功',data.info,'info');
                }else{
                    $.messager.alert('操作失败',data.info,'error');
                }
            }
        });
    }
	 //导入 import
    DrbacUserIndex.macImport = function(){
        $('#easyui-dialog-drbac-user-index-import').dialog('open');
        $('#easyui-dialog-drbac-user-index-import').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['drbac/user/mac-import']); ?>");
    }
    //修改密码
    DrbacUserIndex.resetPassword = function(){
        var id = this.getSelected();
        if(!id) return false;
        $('#easyui-dialog-drbac-user-index-reset-password').dialog('open');
        $('#easyui-dialog-drbac-user-index-reset-password').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['drbac/user/reset-password']); ?>&id="+id);
    }
    //角色分配
    DrbacUserIndex.role = function(){
        var id = this.getSelected();
        if(!id) return false;
        $('#easyui-dialog-drbac-user-index-role').dialog('open');
        $('#easyui-dialog-drbac-user-index-role').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['drbac/user/role-distribution']); ?>&adminId="+id);
    }
	//清空查询表单
	DrbacUserIndex.resetForm = function(){
		var easyuiForm = $('#search-form-drbac-user-index');
		easyuiForm.form('reset');
        easyuiForm.submit();
	}
</script>