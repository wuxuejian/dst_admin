<table id="easyui-datagrid-process-config-events"></table> 
<div id="easyui-datagrid-process-config-events-toolbar">
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
<div id="easyui-dialog-process-config-events-add"></div>
<div id="easyui-dialog-process-config-events-edit"></div>
<!-- 窗口 -->
<script>
    var ProcessConfigEvents= new Object();
    ProcessConfigEvents.init = function(){
        //获取列表数据process-config
        $('#easyui-datagrid-process-config-events').datagrid({  
            method: 'POST', 
            url:"<?php echo yii::$app->urlManager->createUrl(['process/config/events']); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-process-config-events-toolbar",
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
                 {field: 'name',title: '名称',width: 120,align: 'center',sortable: true},
                 {field: 'action',title: 'URL路由',width: 150,align: 'center',sortable: true},
                 {field: 'js_object',title: 'JS对象',width: 120,align: 'center',sortable: true},
                 {field: 'js_function',title: 'JS方法',width: 120,align: 'center',sortable: true},
                 {field: 'type',title: '类型',width: 120,align: 'center',sortable: true,
                     formatter: function(value){
	                     if(value == 1){
	                         return '指定事件';
	                     }else{
	                         return '对应业务';
	                     }
                 	}
                 },     
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
	$('#easyui-dialog-process-config-events-add').dialog({
    	title: '添加事件',   
        width: '450px',   
        height: '480px',   
        closed: true,   
        cache: true,   
        modal: true,
        resizable:true,
        maximizable: true,
        buttons: [{
			text:'确定',
			iconCls:'icon-ok',
			handler:function(){
                var form = $('#easyui-form-process-config-add-event');
                if(!form.form('validate')) return false;
				var data = form.serialize();
				$.ajax({
					type: 'post',
					url: "<?php echo yii::$app->urlManager->createUrl(['process/config/add-event']); ?>",
					data: data,
					dataType: 'json',
					success: function(data){
						if(data.status){
							$.messager.alert('添加成功',data.info,'info');
							$('#easyui-dialog-process-config-events-add').dialog('close');
							$('#easyui-datagrid-process-config-events').datagrid('reload');
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
				$('#easyui-dialog-process-config-events-add').dialog('close');
			}
		}],
        onClose: function(){
            $(this).dialog('clear');
        }
    });


	//初始化修改窗口
	$('#easyui-dialog-process-config-events-edit').dialog({
    	title: '修改事件',   
        width: '450px',   
        height: '480px',   
        closed: true,   
        cache: true,   
        modal: true,
        resizable:true,
        maximizable: true,
        buttons: [{
			text:'确定',
			iconCls:'icon-ok',
			handler:function(){
                var form = $('#easyui-form-process-config-edit-event');
                if(!form.form('validate')) return false;
				var data = form.serialize();
				$.ajax({
					type: 'post',
					url: "<?php echo yii::$app->urlManager->createUrl(['process/config/edit-event']); ?>",
					data: data,
					dataType: 'json',
					success: function(data){
						if(data.status){
							$.messager.alert('添加成功',data.info,'info');
							$('#easyui-dialog-process-config-events-edit').dialog('close');
							$('#easyui-datagrid-process-config-events').datagrid('reload');
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
				$('#easyui-dialog-process-config-events-edit').dialog('close');
			}
		}],
        onClose: function(){
            $(this).dialog('clear');
        }
    });  
  	//执行
    ProcessConfigEvents.init();
  //获取选择的记录
    ProcessConfigEvents.getSelected = function(){
        var datagrid = $('#easyui-datagrid-process-config-events');
        var selectRow = datagrid.datagrid('getSelected');
        if(!selectRow){
            $.messager.alert('错误','请选择要操作的记录','error');   
            return false;
        }
        return selectRow;
    }
  	//增加事件
    ProcessConfigEvents.add = function(){
        $('#easyui-dialog-process-config-events-add').dialog('open');
        $('#easyui-dialog-process-config-events-add').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/config/add-event']); ?>");
    }
  	//修改事件
    ProcessConfigEvents.edit = function(){
    	var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var id = selectRow.id;
        $('#easyui-dialog-process-config-events-edit').dialog('open');
        $('#easyui-dialog-process-config-events-edit').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/config/edit-event']); ?>&id="+id);
    }
 	 //删除步骤
	ProcessConfigEvents.remove = function(){
		var selectRow = this.getSelected();
        if(!selectRow) return false;
        var id = selectRow.id;
		$.messager.confirm('确定删除','您确定要删除该步骤？',function(r){
			if(r){
				$.ajax({
					type: 'post',
					url: '<?php echo yii::$app->urlManager->createUrl(['process/config/delete-event']); ?>',
					data: {id: id},
					dataType: 'json',
					success: function(data){
						if(data.status){
							$.messager.alert('删除成功',data.info,'info');   
							$('#easyui-datagrid-process-config-events').datagrid('reload');
						}else{
							$.messager.alert('删除失败',data.info,'error');   
						}
					}
				});
			}
		});
	}
</script>