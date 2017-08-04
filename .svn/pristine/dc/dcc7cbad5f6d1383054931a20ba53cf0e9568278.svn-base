<table id="easyui-datagrid-process-config-steps"></table> 
<div id="easyui-datagrid-process-config-steps-toolbar">
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
<div id="easyui-dialog-process-config-steps-add"></div>
<div id="easyui-dialog-process-config-steps-edit"></div>
<!-- 窗口 -->
<script>
    var ProcessConfigSteps= new Object();
    ProcessConfigSteps.init = function(){
        //获取列表数据process-config
        $('#easyui-datagrid-process-config-steps').datagrid({  
            method: 'POST', 
            url:"<?php echo yii::$app->urlManager->createUrl(['process/config/steps']); ?>&template_id=<?php echo $template_id;?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-process-config-steps-toolbar",
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
                 {field: 'role_name',title: '角色名称',width: 120,align: 'center',sortable: true},
                 {field: 'is_approval_action',title: '动作',width: 120,align: 'center',sortable: true},
                 {field: 'is_cancel',title: '终止流程',width: 120,align: 'center',sortable: true},
                 {field: 'count_down',title: '截止倒计时(天)',width: 120,align: 'center',sortable: true},
                 {field: 'sort',title: '顺序',width: 120,align: 'center',sortable: true},
                 {field: 'last_update_time',title: '最后修改时间',align: 'center',sortable: true},
                 {field: 'create_time',title: '创建时间',align: 'center',sortable: true,},       
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
	$('#easyui-dialog-process-config-steps-add').dialog({
    	title: '添加步骤',   
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
                var form = $('#easyui-form-process-config-add-step');
                if(!form.form('validate')) return false;
				var data = form.serialize();
				$.ajax({
					type: 'post',
					url: "<?php echo yii::$app->urlManager->createUrl(['process/config/add-step']); ?>",
					data: data,
					dataType: 'json',
					success: function(data){
						if(data.status){
							$.messager.alert('添加成功',data.info,'info');
							$('#easyui-dialog-process-config-steps-add').dialog('close');
							$('#easyui-datagrid-process-config-steps').datagrid('reload');
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
				$('#easyui-dialog-process-config-steps-add').dialog('close');
			}
		}],
        onClose: function(){
            $(this).dialog('clear');
        }
    });


	//初始化修改窗口
	$('#easyui-dialog-process-config-steps-edit').dialog({
    	title: '修改步骤',   
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
                var form = $('#easyui-form-process-config-edit-step');
                if(!form.form('validate')) return false;
				var data = form.serialize();
				$.ajax({
					type: 'post',
					url: "<?php echo yii::$app->urlManager->createUrl(['process/config/edit-step']); ?>",
					data: data,
					dataType: 'json',
					success: function(data){
						if(data.status){
							$.messager.alert('添加成功',data.info,'info');
							$('#easyui-dialog-process-config-steps-edit').dialog('close');
							$('#easyui-datagrid-process-config-steps').datagrid('reload');
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
				$('#easyui-dialog-process-config-steps-edit').dialog('close');
			}
		}],
        onClose: function(){
            $(this).dialog('clear');
        }
    });  
  	//执行
    ProcessConfigSteps.init();
  //获取选择的记录
    ProcessConfigSteps.getSelected = function(){
        var datagrid = $('#easyui-datagrid-process-config-steps');
        var selectRow = datagrid.datagrid('getSelected');
        if(!selectRow){
            $.messager.alert('错误','请选择要操作的记录','error');   
            return false;
        }
        return selectRow;
    }
  	//增加流程步骤事件
    ProcessConfigSteps.add = function(){
        $('#easyui-dialog-process-config-steps-add').dialog('open');
        $('#easyui-dialog-process-config-steps-add').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/config/add-step']); ?>&template_id=<?php echo $template_id;?>");
    }
  	//修改流程步骤事件
    ProcessConfigSteps.edit = function(){
    	var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var id = selectRow.id;
        $('#easyui-dialog-process-config-steps-edit').dialog('open');
        $('#easyui-dialog-process-config-steps-edit').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/config/edit-step']); ?>&id="+id);
    }
 	 //删除步骤
	ProcessConfigSteps.remove = function(){
		var selectRow = this.getSelected();
        if(!selectRow) return false;
        var id = selectRow.id;
        if(selectRow.sort <=0)
        {
        	$.messager.alert('删除失败','开始步骤不能删除！','error'); 
        	return false;
        }
		$.messager.confirm('确定删除','您确定要删除该步骤？',function(r){
			if(r){
				$.ajax({
					type: 'post',
					url: '<?php echo yii::$app->urlManager->createUrl(['process/config/delete-step']); ?>',
					data: {id: id},
					dataType: 'json',
					success: function(data){
						if(data.status){
							$.messager.alert('删除成功',data.info,'info');   
							$('#easyui-datagrid-process-config-steps').datagrid('reload');
						}else{
							$.messager.alert('删除失败',data.info,'error');   
						}
					}
				});
			}
		});
	}
</script>