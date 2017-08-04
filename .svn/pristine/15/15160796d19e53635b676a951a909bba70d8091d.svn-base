<table id="easyui-datagrid-car-maintain-record-type"></table> 
<div id="easyui-datagrid-car-maintain-record-type-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-car-maintain-record-type">
                <ul class="search-main">
                             
                  
                    <!-- <li>
                        <div class="item-name">保养厂</div>
                        <div class="item-input">
                            <input name="maintenance_shop" style="width:200px;" />
                        </div>
                    </li>-->
                    
                    <li class="search-button" >
                        <button class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button onclick="CarMaintainRecordType.resetForm();return false;" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
                    </li>
                </ul>
            </form>
        </div>
    </div>
    <?php if($buttons){ ?>
    <div class="easyui-panel" title="数据列表" style="padding:8px 4px;" data-options="
        iconCls: 'icon-tip',
        border: false
    ">
        <?php foreach($buttons as $val){ ?>
        <a href="javascript:<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon']; ?>'"><?= $val['text']; ?></a>
        <?php } ?>
    </div>
    <?php } ?>
</div>
<!-- 窗口 -->
<div id="easyui-dialog-car-maintain-record-type-add"></div>
<div id="easyui-dialog-car-maintain-record-type-edit"></div>
<div id="easyui-dialog-car-maintain-record-type-scan"></div>
<!-- 窗口 -->
<script>
    var CarMaintainRecordType = new Object();
    CarMaintainRecordType.init = function(){
        var easyuiDatagrid = $('#easyui-datagrid-car-maintain-record-type');
        //获取列表数据
        easyuiDatagrid.datagrid({  
            method: 'get', 
            url:"<?php echo yii::$app->urlManager->createUrl(['car/maintain-record/get-maintain-type-list']); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-car-maintain-record-type-toolbar",
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
            columns:[
               [ 
			    {
                    field: 'text',title: '车型名称',width: 100
                },

                {
                    field: 'maintain_type',title: '保养类型',width: 300
                },
                {
                    field: 'maintain_des',title: '描述',width: 1000
                }
            ]],
            onDblClickRow: function(rowIndex,rowData){
                CarMaintainRecordType.edit(rowData.id);
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
        //构建查询表单
        var searchForm = $('#search-form-car-maintain-record-type');
        searchForm.submit(function(){
            var data = {};
            var searchCondition = $(this).serializeArray();
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = searchCondition[i]['value'];
            }
            easyuiDatagrid.datagrid('load',data);
            return false;
        });
        // searchForm.find('input[name=type]').combobox({
            // valueField:'value',
            // textField:'text',
            // data: [{"value":"","text":"不限"},{"value":"1","text":"A保"},{"value":"2","text":"B保"}],
            // editable: false,
            // onChange: function(){
                // searchForm.submit();
            // }
        // });
      
        //构建查询表单结束
        
      //初始化查看窗口
        // $('#easyui-dialog-car-maintain-record-main-scan').window({
            // title: '查看详情',
            // width: '83%',   
            // height: '83%',   
            // closed: true,   
            // cache: true,   
            // modal: true,
            // collapsible: false,
            // minimizable: false, 
            // maximizable: false,
            // onClose: function(){
                // $(this).window('clear');
            // }       
        // });
             
        //初始化添加窗口
        $('#easyui-dialog-car-maintain-record-type-add').dialog({
            title: '添加保养类型',   
            width: '580px',   
            height: '260px',   
            closed: true,   
            cache: true,   
            modal: true,
            resizable:true,
            maximizable: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var form = $('#easyui-form-car-maintain-record-type-add');
                    if(!form.form('validate')) return false;
                    var data = form.serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['car/maintain-record/maintain-add']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('添加成功',data.info,'info');
                                $('#easyui-dialog-car-maintain-record-type-add').dialog('close');
                                $('#easyui-datagrid-car-maintain-record-type').datagrid('reload');
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
                    $('#easyui-dialog-car-maintain-record-type-add').dialog('close');
                }
            }],
            onClose: function(){
                $(this).dialog('clear');
            }
        });

        //初始化修改窗口
        $('#easyui-dialog-car-maintain-record-type-edit').dialog({
            title: '修改保养类型',   
            width: '580px',   
            height: '260px',   
            closed: true,   
            cache: true,   
            modal: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var form = $('#easyui-form-car-maintain-type-edit');
                    if(!form.form('validate')) return false;
                    var data = form.serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['car/maintain-record/maintain-edit']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('修改成功',data.info,'info');
                                $('#easyui-dialog-car-maintain-record-type-edit').dialog('close');
                                $('#easyui-datagrid-car-maintain-record-type').datagrid('reload');
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
                    $('#easyui-dialog-car-maintain-record-type-edit').dialog('close');
                }
            }],
            onClose: function(){
                $(this).dialog('clear');
            } 
        });
    }
    CarMaintainRecordType.init();
    //获取选择的记录
    CarMaintainRecordType.getSelected = function(){
		console.log("in2");
        var datagrid = $('#easyui-datagrid-car-maintain-record-type');
		
        var selectRow = datagrid.datagrid('getSelected');
		console.log(selectRow);
        if(!selectRow){
            $.messager.alert('错误','请选择要操作的记录','error');   
            return false;
        }
        return selectRow;
    }
   
   //添加
    CarMaintainRecordType.add = function(){
		//console.log("hi");
        $('#easyui-dialog-car-maintain-record-type-add').dialog('open');
        $('#easyui-dialog-car-maintain-record-type-add').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/maintain-record/maintain-add']); ?>");
    }
  //删除
	CarMaintainRecordType.remove = function(){
		var selectRow = this.getSelected();
        if(!selectRow) return false;
        var id = selectRow.id;
		$.messager.confirm('确定删除','您确定要删除该数据？',function(r){
			if(r){
				$.ajax({
					type: 'get',
					url: '<?php echo yii::$app->urlManager->createUrl(['car/maintain-record/maintain-remove']); ?>',
					data: {id: id},
					dataType: 'json',
					success: function(data){
						if(data.status){
							$.messager.alert('删除成功',data.info,'info');   
							$('#easyui-datagrid-car-maintain-record-type').datagrid('reload');
						}else{
							$.messager.alert('删除失败',data.info,'error');   
						}
					}
				});
			}
		});
	}
    	
    //修改
    CarMaintainRecordType.edit = function(id){
		console.log("e");
        if(!id){
			console.log("in233");
            var selectRow = this.getSelected();
            if(!selectRow){
                return false;
            }
            id = selectRow.id;
			console.log(selectRow.id);
        }
		//console.log(id);
        $('#easyui-dialog-car-maintain-record-type-edit').dialog('open');
        $('#easyui-dialog-car-maintain-record-type-edit').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/maintain-record/maintain-edit']); ?>&id="+id);
    }
    
    //重置查询表单
    CarMaintainRecordType.resetForm = function(){
        var easyuiForm = $('#search-form-car-maintain-record-type');
        easyuiForm.form('reset');
        easyuiForm.submit();
    }
</script>