<table id="easyui-datagrid-process-extract-site"></table> 
<div id="easyui-datagrid-process-extract-site-toolbar">
   
   
   	  
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
<div id="easyui-dialog-process-extract-site-add"></div>
<div id="easyui-dialog-process-extract-site-edit"></div>
<!-- 窗口 -->
<script>
    var ProcessExtractSiteIndex= new Object();
    ProcessExtractSiteIndex.init = function(){
        //获取列表数据process-config
        $('#easyui-datagrid-process-extract-site').datagrid({  
            method: 'POST', 
            url:"<?php echo yii::$app->urlManager->createUrl(['process/extract-car-site/site']); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-process-extract-site-toolbar",
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
                 {field: 'name',title: '提车地点',width: 150,align: 'center',sortable: true},
                 {field: 'company_name',title: '运营公司',width: 350,align: 'center',sortable: true},    
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
	$('#easyui-dialog-process-extract-site-add').dialog({
    	title: '添加提车地点',   
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
                var form = $('#easyui-form-process-extract-site-add');
                if(!form.form('validate')) return false;
				var data = form.serialize();
				$.ajax({
					type: 'post',
					url: "<?php echo yii::$app->urlManager->createUrl(['process/extract-car-site/add-site']); ?>",
					data: data,
					dataType: 'json',
					success: function(data){
						if(data.status){
							$.messager.alert('添加成功',data.info,'info');
							$('#easyui-dialog-process-extract-site-add').dialog('close');
							$('#easyui-datagrid-process-extract-site').datagrid('reload');
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
				$('#easyui-dialog-process-extract-site-add').dialog('close');
			}
		}],
        onClose: function(){
            $(this).dialog('clear');
        }
    });


	//初始化修改窗口
	$('#easyui-dialog-process-extract-site-edit').dialog({
    	title: '修改提车地点',   
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
                var form = $('#easyui-form-process-extract-site-edit');
                if(!form.form('validate')) return false;
				var data = form.serialize();
				$.ajax({
					type: 'post',
					url: "<?php echo yii::$app->urlManager->createUrl(['process/extract-car-site/edit-site']); ?>",
					data: data,
					dataType: 'json',
					success: function(data){
						if(data.status){
							$.messager.alert('添加成功',data.info,'info');
							$('#easyui-dialog-process-extract-site-edit').dialog('close');
							$('#easyui-datagrid-process-extract-site').datagrid('reload');
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
				$('#easyui-dialog-process-extract-site-edit').dialog('close');
			}
		}],
        onClose: function(){
            $(this).dialog('clear');
        }
    });  
  	//执行
    ProcessExtractSiteIndex.init();
  //获取选择的记录
    ProcessExtractSiteIndex.getSelected = function(){
        var datagrid = $('#easyui-datagrid-process-extract-site');
        var selectRow = datagrid.datagrid('getSelected');
        if(!selectRow){
            $.messager.alert('错误','请选择要操作的记录','error');   
            return false;
        }
        return selectRow;
    }
  	//增加
    ProcessExtractSiteIndex.add = function(){

        $('#easyui-dialog-process-extract-site-add').dialog('open');
        $('#easyui-dialog-process-extract-site-add').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/extract-car-site/add-site']); ?>");
    }
  	//修改
    ProcessExtractSiteIndex.edit = function(){
    	var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var id = selectRow.id;
        $('#easyui-dialog-process-extract-site-edit').dialog('open');
        $('#easyui-dialog-process-extract-site-edit').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/extract-car-site/edit-site']); ?>&id="+id);
    }
	  //删除提车地点
	ProcessExtractSiteIndex.del = function(){		
		var selectRow = this.getSelected();
        if(!selectRow) return false;
        var id = selectRow.id;
		$.messager.confirm('确定删除','您确定要删除该提车地点？',function(r){
			if(r){
				$.ajax({
					type: 'post',
					url: '<?php echo yii::$app->urlManager->createUrl(['process/extract-car-site/del-site']); ?>&id='+id,
					data: {id: id},
					dataType: 'json',
					success: function(data){
						if(data.status){
							$.messager.alert('删除成功',data.info,'info');   
							$('#easyui-datagrid-process-extract-site').datagrid('reload');
						}else{
							$.messager.alert('删除失败',data.info,'error');   
						}
					}
				});
			}
		});
	}
</script>