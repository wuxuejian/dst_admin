<table id="easyui-datagrid-drbac-user-index-mac-list"></table>
<div id="easyui-datagrid-drbac-user-index-mac-list-toolbar">
    <div class="easyui-panel" title="数据列表" style="padding:8px 4px;" data-options="
        iconCls: 'icon-tip',
        border: false
    ">
    <?php if($buttons){ ?>
   
        <?php foreach($buttons as $val){ ?>
        <a href="javascript:<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon']; ?>'"><?= $val['text']; ?></a>
        <?php } ?>
  
    <?php } ?>
	  </div>
</div>
<!-- 窗口 -->
<div id="easyui-dialog-drbac-user-index-mac-add"></div>
<div id="easyui-dialog-drbac-user-index-mac-edit"></div>
<!-- 窗口 -->
<script>
    var DrbacUserMac = new Object();
    DrbacUserMac.init = function(){
		console.log("hi");
        var easyuiDatagrid = $('#easyui-datagrid-drbac-user-index-mac-list');
        //获取列表数据
        easyuiDatagrid.datagrid({  
            method: 'get', 
            url:"<?php echo yii::$app->urlManager->createUrl(['drbac/user/get-mac-list','id'=>$uid]); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-drbac-user-index-mac-list-toolbar",
            pagination: true,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
            frozenColumns: [[
                {field: 'ck',checkbox: true}, 
                {field: 'id',title: 'id',hidden: true}
            ]],
            columns:[[
				{
				    field: 'mac',title: 'MAC地址',width:200				 
				},                 
                {
                    field: 'add_time',title: '添加时间',width: 150
                },               
                {field: 'note',title: '备注',width: 230,align: 'left',sortable: false},               
                //{field: 'name',title: '操作人员',width:100,align: 'center',sortable: true}
            ]],
            onDblClickRow: function(rowIndex,rowData){
                DrbacUserMac.edit(rowData.id);
            },
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
       
        //初始化添加窗口
        $('#easyui-dialog-drbac-user-index-mac-add').dialog({
            title: '添加MAC记录',   
            width: '415px',   
            height: '250px',   
            closed: true,   
            cache: true,   
            modal: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                	var form = $('#easyui-form-drbac-user-index-mac-add');
                    if(!form.form('validate')) return false;
                    var data = form.serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['drbac/user/mac-add','uid'=>$uid]); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('添加成功',data.info,'info');
                                $('#easyui-dialog-drbac-user-index-mac-add').dialog('close');
								$('#easyui-datagrid-drbac-user-index-mac-list').datagrid('reload');
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
                    $('#easyui-dialog-drbac-user-index-mac-add').dialog('close');
                }
            }],
            onClose: function(){
                $(this).dialog('clear');
            } 
        });
        //初始化修改窗口
        $('#easyui-dialog-drbac-user-index-mac-edit').dialog({
            title: '修改mac记录',   
            width: '415px',   
            height: '250px',   
            closed: true,   
            cache: true,   
            modal: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                	var form = $('#easyui-form-drbac-user-index-mac-edit');
                    if(!form.form('validate')){
                        return false;
                    }
					var data = form.serialize();
					$.ajax({
						type: 'post',
						url: "<?php echo yii::$app->urlManager->createUrl(['drbac/user/mac-edit']); ?>",
						data: data,
						dataType: 'json',
						success: function(data){
							if(data.status){
								$.messager.alert('修改成功',data.info,'info');
								$('#easyui-dialog-drbac-user-index-mac-edit').dialog('close');
								$('#easyui-datagrid-drbac-user-index-mac-list').datagrid('reload');
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
                    $('#easyui-dialog-drbac-user-index-mac-edit').dialog('close');
                }
            }],
            onClose: function(){
                $(this).dialog('clear');
            } 
        });
    }
    DrbacUserMac.init();
    //获取选择的记录
    DrbacUserMac.getSelected = function(){
        var datagrid = $('#easyui-datagrid-drbac-user-index-mac-list');
        var selectRow = datagrid.datagrid('getSelected');
        if(!selectRow){
            $.messager.alert('错误','请选择要操作的记录','error');   
            return false;
        }
        return selectRow;
    }
    //添加
    DrbacUserMac.add = function(){
        $('#easyui-dialog-drbac-user-index-mac-add').dialog('open');
        $('#easyui-dialog-drbac-user-index-mac-add').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['drbac/user/mac-add','uid'=>$uid]); ?>");
    }
    //修改
    DrbacUserMac.edit = function(id){
		console.log("233");
        if(!id){
            var selectRow = this.getSelected();
            if(!selectRow){
                return false;
            }
            id = selectRow.id;
        }
		console.log(id);
        $('#easyui-dialog-drbac-user-index-mac-edit').dialog('open');
        $('#easyui-dialog-drbac-user-index-mac-edit').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['drbac/user/mac-edit']); ?>&id="+id);
    }
    //删除
    DrbacUserMac.remove = function(){
        var selectRow = this.getSelected();
        if(!selectRow) return false;
        var id = selectRow.id;
        $.messager.confirm('确定删除','您确定要删除该条MAC地址记录？',function(r){
            if(r){
                $.ajax({
                    type: 'get',
                    url: "<?php echo yii::$app->urlManager->createUrl(['drbac/user/mac-remove']); ?>",
                    data: {id: id},
                    dataType: 'json',
                    success: function(data){
                        if(data){
                            $.messager.alert('删除成功',data.info,'info');   
                            $('#easyui-datagrid-drbac-user-index-mac-list').datagrid('reload');
                        }else{
                            $.messager.alert('删除失败',data.info,'error');   
                        }
                    }
                });
            }
        });
    }
  
</script>