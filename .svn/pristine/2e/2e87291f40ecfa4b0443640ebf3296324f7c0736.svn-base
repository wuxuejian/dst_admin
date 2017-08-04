<table id="easyui-datagrid-process-config-index"></table> 
<div id="easyui-datagrid-process-config-index-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-process-config-index">
                <ul class="search-main">
                    <li>
                        <div class="item-name">名称</div>
                        <div class="item-input">
                            <input name="name" style="width:100%;" />
                        </div>
                    </li>
                    <li class="search-button">
                        <button class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button onclick="ProcessConfigIndex.resetForm();" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
                    </li>
                </ul>
            </form>
        </div>
    </div>
    <?php if($buttons){ ?>
    <div class="easyui-panel" title="数据列表" style="padding:3px 2px;width:100%;" data-options="
        iconCls: 'icon-table-list',
        border: false
    ">
        <?php foreach($buttons as $val){ ?>
        <a href="javascript:<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon']; ?>'"><?= $val['text']; ?></a>
        <?php } ?>
    </div>
    <?php } ?>
</div>
<!-- 窗口 -->
<div id="easyui-dialog-process-config-index-add"></div>
<div id="easyui-dialog-process-config-index-edit"></div>
<div id="easyui-dialog-process-config-index-steps"></div>
<div id="easyui-dialog-process-config-index-events"></div>
<!-- 窗口 -->
<script>
    var ProcessConfigIndex = new Object();
    ProcessConfigIndex.init = function(){
        //获取列表数据process-config
        $('#easyui-datagrid-process-config-index').datagrid({  
            method: 'POST', 
            url:"<?php echo yii::$app->urlManager->createUrl(['process/config/index']); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-process-config-index-toolbar",
            pagination: true,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
            showFooter: true,
			pageSize: 20,
            frozenColumns: [[
                {field: 'ck',checkbox: true}, 
                {field: 'id',title: 'id',hidden: true}
            ]],
            columns: [[
                 {field: 'name',title: '流程名称',width: 120,align: 'center',sortable: true},
                 {field: 'by_business',title: '对应业务',width: 120,align: 'center',sortable: true},
                 {field: 'last_update_time',title: '最后修改时间',width: 120,align: 'center',sortable: true},
                 {field: 'create_time',title: '创建时间',width: 120,align: 'center', sortable: true,},    
            ]],
            onLoadSuccess: function (data){
                $(this).datagrid('doCellTip',{
                    position : 'bottom',
                    maxWidth : '300px',
                    onlyShowInterrupt : true,
                    specialShowFields : [     
                        {field : 'action',showField : 'action'}
                    ],
                    tipStyler : {            
                        'backgroundColor' : '#E4F0FC',
                        borderColor : '#87A9D0',
                        boxShadow : '1px 1px 3px #292929'
                    }
                });
            }
        });
    }
	//初始化添加窗口
	$('#easyui-dialog-process-config-index-add').dialog({
    	title: '添加流程',   
        width: '450px',   
        height: '180px',   
        closed: true,   
        cache: true,   
        modal: true,
        resizable:true,
        maximizable: true,
        buttons: [{
			text:'确定',
			iconCls:'icon-ok',
			handler:function(){
                var form = $('#easyui-form-process-config-add');
                if(!form.form('validate')) return false;
				var data = form.serialize();
				$.ajax({
					type: 'post',
					url: "<?php echo yii::$app->urlManager->createUrl(['process/config/add']); ?>",
					data: data,
					dataType: 'json',
					success: function(data){
						if(data.status){
							$.messager.alert('添加成功',data.info,'info');
							$('#easyui-dialog-process-config-index-add').dialog('close');
							$('#easyui-datagrid-process-config-index').datagrid('reload');
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
				$('#easyui-dialog-process-config-index-add').dialog('close');
			}
		}],
        onClose: function(){
            $(this).dialog('clear');
        }
    });

	//初始化编辑窗口
	$('#easyui-dialog-process-config-index-edit').dialog({
    	title: '编辑流程',   
        width: '450px',   
        height: '180px',   
        closed: true,   
        cache: true,   
        modal: true,
        resizable:true,
        maximizable: true,
        buttons: [{
			text:'确定',
			iconCls:'icon-ok',
			handler:function(){
                var form = $('#easyui-form-process-config-edit');
                if(!form.form('validate')) return false;
				var data = form.serialize();
				$.ajax({
					type: 'post',
					url: "<?php echo yii::$app->urlManager->createUrl(['process/config/edit']); ?>",
					data: data,
					dataType: 'json',
					success: function(data){
						if(data.status){
							$.messager.alert('编辑成功',data.info,'info');
							$('#easyui-dialog-process-config-index-edit').dialog('close');
							$('#easyui-datagrid-process-config-index').datagrid('reload');
						}else{
							$.messager.alert('编辑失败',data.info,'error');
						}
					}
				});
			}
		},{
			text:'取消',
			iconCls:'icon-cancel',
			handler:function(){
				$('#easyui-dialog-process-config-index-edit').dialog('close');
			}
		}],
        onClose: function(){
            $(this).dialog('clear');
        }
    });

  	//初始流程步骤管理窗口
    $('#easyui-dialog-process-config-index-steps').window({
        title: '流程步骤管理',
    	width: 900,   
        height: 500,   
        modal: true,
        closed: true,
        collapsible: false,
        minimizable: false,
        maximizable: false,
        onClose: function(){
            $(this).window('clear');
        }                    
    });

  	//初始事件管理窗口
    $('#easyui-dialog-process-config-index-events').window({
        title: '事件管理',
    	width: 700,   
        height: 500,   
        modal: true,
        closed: true,
        collapsible: false,
        minimizable: false,
        maximizable: false,
        onClose: function(){
            $(this).window('clear');
        }                    
    });

    
  	//执行
    ProcessConfigIndex.init();
    //查询表单构建
    var searchForm = $('#search-form-process-config-index');
    searchForm.submit(function(){
        var data = {};
        var searchCondition = $(this).serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        $('#easyui-datagrid-process-config-index').datagrid('load',data);
        return false;
    });
    searchForm.find('input[name=name]').textbox({
        onChange: function(){
            searchForm.submit();
        }
    });
    //查询表单构建结束
    //获取选择的记录
    ProcessConfigIndex.getSelected = function(){
        var datagrid = $('#easyui-datagrid-process-config-index');
        var selectRow = datagrid.datagrid('getSelected');
        if(!selectRow){
            $.messager.alert('错误','请选择要操作的记录','error');   
            return false;
        }
        return selectRow;
    }
  	//增加流程事件
    ProcessConfigIndex.add = function(){
        $('#easyui-dialog-process-config-index-add').dialog('open');
        $('#easyui-dialog-process-config-index-add').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/config/add']); ?>");
    }
  	//编辑流程事件
    ProcessConfigIndex.edit = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var id = selectRow.id;
        $('#easyui-dialog-process-config-index-edit').dialog('open');
        $('#easyui-dialog-process-config-index-edit').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/config/edit']); ?>&id="+id);
    }
    //删除步骤
	ProcessConfigIndex.remove = function(){
		var selectRow = this.getSelected();
        if(!selectRow) return false;
        var id = selectRow.id;
		$.messager.confirm('确定删除','您确定要删除该流程？',function(r){
			if(r){
				$.ajax({
					type: 'post',
					url: '<?php echo yii::$app->urlManager->createUrl(['process/config/delete']); ?>&id='+id,
					data: {id: id},
					dataType: 'json',
					success: function(data){
						if(data.status){
							$.messager.alert('删除成功',data.info,'info');   
							$('#easyui-datagrid-process-config-index').datagrid('reload');
						}else{
							$.messager.alert('删除失败',data.info,'error');   
						}
					}
				});
			}
		});
	}
  	//流程步骤
    ProcessConfigIndex.steps = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var id = selectRow.id;
        $('#easyui-dialog-process-config-index-steps').dialog('open');
        $('#easyui-dialog-process-config-index-steps').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/config/steps']); ?>&id="+id);
    }
    //重置查询表单
    ProcessConfigIndex.resetForm = function(){
        var easyuiForm = $('#search-form-process-config-index');
        easyuiForm.form('reset');
    }

 	//事件管理
    ProcessConfigIndex.events = function(){
        $('#easyui-dialog-process-config-index-events').dialog('open');
        $('#easyui-dialog-process-config-index-events').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/config/events']); ?>");
    }
    
</script>