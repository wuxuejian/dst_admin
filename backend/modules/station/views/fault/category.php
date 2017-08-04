<table id="easyui-datagrid-station-fault-category"></table> 
<div id="easyui-datagrid-station-fault-category-toolbar"> 
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
<div id="easyui-dialog-station-fault-category-add"></div>
<div id="easyui-dialog-station-fault-category-edit"></div>
<div id="easyui-dialog-station-fault-category-info"></div>
<div id="easyui-dialog-station-fault-category-events"></div>
<!-- 窗口 -->

<script>
	var StationFalutCategory = new Object();
    	// 初始化
        StationFalutCategory.init=function () {
            //--初始化表格
            $('#easyui-datagrid-station-fault-category').treegrid({
                idField: 'id',
                treeField: 'category',
                method: 'post',
                url: "<?= yii::$app->urlManager->createUrl(['station/fault/category']); ?>",
                toolbar: "#easyui-datagrid-station-fault-category-toolbar",
                fit: true,
                border: false,
                pagination: false,
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
                    {field: 'category', title: '类别名称', width: 230, halign: 'center', sortable: true},
                    {field: 'code', title: '编码', width: 150, halign: 'center', sortable: true},
                ]]
            });
        }


      //初始化添加窗口
    	$('#easyui-dialog-station-fault-category-add').dialog({
        	title: '新增分类',   
            width: '380px',   
            height: '190px',   
            closed: true,   
            cache: true,   
            modal: true,
            resizable:true,
            maximizable: true,
            buttons: [{
    			text:'确定',
    			iconCls:'icon-ok',
    			handler:function(){
                    var form = $('#StationFalutCategory_addWin_form');
                    if(!form.form('validate')) return false;
    				var data = form.serialize();
    				$.ajax({
    					type: 'post',
    					url: "<?php echo yii::$app->urlManager->createUrl(['station/fault/add-category']); ?>",
    					data: data,
    					dataType: 'json',
    					success: function(data){
    						if(data.status){
    							$.messager.alert('添加成功',data.info,'info');
    							$('#easyui-dialog-station-fault-category-add').dialog('close');
    							$('#easyui-datagrid-station-fault-category').treegrid('reload');
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
    				$('#easyui-dialog-station-fault-category-add').dialog('close');
    			}
    		}],
            onClose: function(){
                $(this).dialog('clear');
            }
        });

    	//初始化编辑窗口
    	$('#easyui-dialog-station-fault-category-edit').dialog({
        	title: '修改分类',   
            width: '380px',   
            height: '190px',   
            closed: true,   
            cache: true,   
            modal: true,
            resizable:true,
            maximizable: true,
            buttons: [{
    			text:'确定',
    			iconCls:'icon-ok',
    			handler:function(){
                    var form = $('#StationFalutCategory_addWin_form');
                    if(!form.form('validate')) return false;
    				var data = form.serialize();
    				$.ajax({
    					type: 'post',
    					url: "<?php echo yii::$app->urlManager->createUrl(['station/fault/edit-category']); ?>",
    					data: data,
    					dataType: 'json',
    					success: function(data){
    						if(data.status){
    							$.messager.alert('修改成功',data.info,'info');
    							$('#easyui-dialog-station-fault-category-edit').dialog('close');
    							$('#easyui-datagrid-station-fault-category').treegrid('reload');
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
    				$('#easyui-dialog-station-fault-category-edit').dialog('close');
    			}
    		}],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
        
      	//执行
        StationFalutCategory .init();
      

        
        //查询表单构建结束
      	//增加
        StationFalutCategory .add = function(){
             var selectRow = $('#easyui-datagrid-station-fault-category').treegrid('getSelected');
             var id = 0;
             if(selectRow) id = selectRow.id;
            $('#easyui-dialog-station-fault-category-add').dialog('open');
            $('#easyui-dialog-station-fault-category-add').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['station/fault/add-category']); ?>&id="+id);
        }
      	//编辑
        StationFalutCategory .edit = function(){
        	var selectRow = $('#easyui-datagrid-station-fault-category').treegrid('getSelected');
        	if(!selectRow){
                $.messager.alert('错误','请选择要操作的记录','error');   
                return false;
            }
            var id = selectRow.id;
            $('#easyui-dialog-station-fault-category-edit').dialog('open');
            $('#easyui-dialog-station-fault-category-edit').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['station/fault/edit-category']); ?>&id="+id);
        }

</script>
