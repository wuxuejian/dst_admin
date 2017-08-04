<table id="easyui-datagrid-station-repair-indicator-light"></table> 
<div id="easyui-datagrid-station-repair-indicator-light-toolbar"> 
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
<div id="easyui-dialog-station-repair-indicator-light-add"></div>
<div id="easyui-dialog-station-repair-indicator-light-edit"></div>
<div id="easyui-dialog-station-repair-indicator-light-info"></div>
<div id="easyui-dialog-station-repair-indicator-light-events"></div>
<!-- 窗口 -->

<script>
	var ProcessRepairIndicatorLight = new Object();
    	// 初始化
        ProcessRepairIndicatorLight.init=function () {
            //--初始化表格
            $('#easyui-datagrid-station-repair-indicator-light').datagrid({
                idField: 'id',
                treeField: 'category',
                method: 'post',
                url: "<?= yii::$app->urlManager->createUrl(['process/repair/indicator-light']); ?>",
                toolbar: "#easyui-datagrid-station-repair-indicator-light-toolbar",
                fit: true,
                border: false,
                pagination: true,
                loadMsg: '数据加载中...',
                striped: true,
                checkOnSelect: true,
                rownumbers: true,
                singleSelect: true,
                pageSize: 50,
                frozenColumns: [[
                    {field: 'ck', checkbox: true},
                    {field: 'id',title: 'ID', hidden: true},
                ]],
                columns: [[
                    {field: 'name', title: '名称', width: 230, halign: 'center', sortable: true},
                    {field: 'image_url', title: '故障指示灯', width: 150, halign: 'center', 
                	 formatter: function (value, row, index) {
							return '<img src="'+value+'" width="60px" height="60px"></img>';
                     }},
                ]]
            });
        }


      //初始化添加窗口
    	$('#easyui-dialog-station-repair-indicator-light-add').dialog({
        	title: '新增故障指示灯',   
            width: '450px',   
            height: '250px',   
            closed: true,   
            cache: true,   
            modal: true,
            resizable:true,
            maximizable: true,
            buttons: [{
    			text:'确定',
    			iconCls:'icon-ok',
    			handler:function(){
                    var form = $('#easyui-dialog-station-repair-indicator-light-add-form');
                    if(!form.form('validate')) return false;
    				var data = form.serialize();
    				$.ajax({
    					type: 'post',
    					url: "<?php echo yii::$app->urlManager->createUrl(['process/repair/indicator-light-add']); ?>",
    					data: data,
    					dataType: 'json',
    					success: function(data){
    						if(data.status){
    							$.messager.alert('添加成功',data.info,'info');
    							$('#easyui-dialog-station-repair-indicator-light-add').dialog('close');
    							$('#easyui-datagrid-station-repair-indicator-light').datagrid('reload');
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
    				$('#easyui-dialog-station-repair-indicator-light-add').dialog('close');
    			}
    		}],
            onClose: function(){
                $(this).dialog('clear');
            }
        });

    	//初始化编辑窗口
    	$('#easyui-dialog-station-repair-indicator-light-edit').dialog({
        	title: '修改故障指示灯',   
            width: '450px',   
            height: '250px',   
            closed: true,   
            cache: true,   
            modal: true,
            resizable:true,
            maximizable: true,
            buttons: [{
    			text:'确定',
    			iconCls:'icon-ok',
    			handler:function(){
                    var form = $('#easyui-dialog-station-repair-indicator-light-edit-form');
                    if(!form.form('validate')) return false;
    				var data = form.serialize();
    				$.ajax({
    					type: 'post',
    					url: "<?php echo yii::$app->urlManager->createUrl(['process/repair/indicator-light-edit']); ?>",
    					data: data,
    					dataType: 'json',
    					success: function(data){
    						if(data.status){
    							$.messager.alert('修改成功',data.info,'info');
    							$('#easyui-dialog-station-repair-indicator-light-edit').dialog('close');
    							$('#easyui-datagrid-station-repair-indicator-light').datagrid('reload');
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
    				$('#easyui-dialog-station-repair-indicator-light-edit').dialog('close');
    			}
    		}],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
        
      	//执行
        ProcessRepairIndicatorLight .init();
      

      //获取选择的记录
        ProcessRepairIndicatorLight.getSelected = function(){
            var datagrid = $('#easyui-datagrid-station-repair-indicator-light');
            var selectRow = datagrid.datagrid('getSelected');
            if(!selectRow){
                $.messager.alert('错误','请选择要操作的记录','error');   
                return false;
            }
            return selectRow;
        }
        
        //查询表单构建结束
      	//增加
        ProcessRepairIndicatorLight.add = function(){
            $('#easyui-dialog-station-repair-indicator-light-add').dialog('open');
            $('#easyui-dialog-station-repair-indicator-light-add').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/repair/indicator-light-add']); ?>");
        }
      	//编辑
        ProcessRepairIndicatorLight.edit = function(){
        	 var selectRow = this.getSelected();
             if(!selectRow){
                 return false;
             }
            var id = selectRow.id;
            $('#easyui-dialog-station-repair-indicator-light-edit').dialog('open');
            $('#easyui-dialog-station-repair-indicator-light-edit').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/repair/indicator-light-edit']); ?>&id="+id);
        }

      //删除
    	ProcessRepairIndicatorLight .del = function(){
    		var selectRow = this.getSelected();
            if(!selectRow) return false;
            var id = selectRow.id;
    		$.messager.confirm('确定删除','您确定要删除该故障指示灯？',function(r){
    			if(r){
    				$.ajax({
    					type: 'post',
    					url: '<?php echo yii::$app->urlManager->createUrl(['process/repair/indicator-light-del']); ?>',
    					data: {id: id},
    					dataType: 'json',
    					success: function(data){
    						if(data.status){
    							$.messager.alert('删除成功',data.info,'info');   
    							$('#easyui-datagrid-station-repair-indicator-light').datagrid('reload');
    						}else{
    							$.messager.alert('删除失败',data.info,'error');   
    						}
    					}
    				});
    			}
    		});
    	}

</script>
