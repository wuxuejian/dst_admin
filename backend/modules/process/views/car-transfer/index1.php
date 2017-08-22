<table id="easyui-datagrid-process-car-transfer1"></table> 
<div id="easyui-datagrid-process-car-transfer1-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-process-car-transfer1">
                <ul class="search-main">
                	<li>
                        <div class="item-name">钉钉审批号</div>
                        <div class="item-input">
                            <input name="dd_number" style="width:200px">
                        </div>
                    </li>
					<li>
                        <div class="item-name">调拨发起日期</div>
                        <div class="item-input">
                            <input class="easyui-datebox" type="text" name="start_add_time" style="width:93px;"
                                   data-options="
                                        onChange:function(){
                                            $('#search-form-process-car-transfer1').submit();
                                        }
                                   "
                                />
                            -
                            <input class="easyui-datebox" type="text" name="end_add_time" style="width:93px;"
                                   data-options="
                                        onChange:function(){
                                            $('#search-form-process-car-transfer1').submit();
                                        }
                                   "
                                />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">调拨状态</div>
                        <div class="item-input">
                            <input name="status" style="width:200px">
                        </div>
                    </li>
                    
                    <li class="search-button">
                        <button class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button onclick="ProcessCarTransfer1.resetForm();return false;" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
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
                <button onclick="<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon']; ?>'"><?= $val['text']; ?></button>
            <?php } ?>
        </div>
    <?php } ?>
</div>
<!-- 窗口 -->
<div id="easyui-dialog-process-car-transfer1-add"></div>
<div id="easyui-dialog-process-car-transfer1-edit"></div>
<div id="easyui-dialog-process-car-transfer1-list"></div>
<div id="easyui-dialog-process-car-transfer1-scan"></div>
<!-- 窗口 -->
<script>
    var ProcessCarTransfer1 = new Object();
    ProcessCarTransfer1.init = function(){
        var easyuiDatagrid = $('#easyui-datagrid-process-car-transfer1');
        //获取列表数据
        easyuiDatagrid.datagrid({  
            method: 'get', 
            url:"<?php echo yii::$app->urlManager->createUrl(['process/car-transfer/get-list1']); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-process-car-transfer1-toolbar",
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
				    field: 'dd_number',title: '钉钉审批号',width: 100,align: 'center',sortable:true
				},
				{
                    field: 'add_time',title: '发起日期',width: 100,align: 'center',sortable:true,
                    formatter: function(value){
                        if(!isNaN(value) && value >0){
                            return formatDateToString(value,false);
                        }
                    }
                },
                {
				    field: 'originator',title: '需求提报人',width: 100,align: 'center',sortable:true
				},
				{
				    field: 'originator_operating_company_name',title: '提报人所属运营公司',width: 180,align: 'center',sortable:true
				},
				{
				    field: 'attachment_url',title: 'PDF下载',align: 'center',sortable:true,
					formatter: function(value){
						return '<a href="'+value+'" target="_b">下载</a>';
					}
				},
                {
                    field: 'status',title: '流程状态',width: 120,align: 'center',sortable:true,
                    formatter: function(value,row,index){
                        var states = {"":"","1":'<span style="background-color:#FF4040;color:#fff;padding:2px 5px;">1.需求已发起<font>',"2":'<span style="background-color:#FF4040;color:#fff;padding:2px 5px;">2.需求已满足<font>',"3":'<span style="background-color:#05CD69;color:#fff;padding:2px 5px;">3.调拨到车已确认<font>'};
                        return states[value];
                    }
                },
				{
				    field: 'car_number',title: '已提交需求台数',width: 120,align: 'center',sortable:true
				}
				,
				{
				    field: 'car_ok_number',title: '确认到车台数',width: 120,align: 'center',sortable:true
				}
				
            ]],
            onDblClickRow: function(rowIndex,rowData){
                ProcessCarTransfer1.edit(rowData.id);
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
        var searchForm = $('#search-form-process-car-transfer1');
        searchForm.submit(function(){
            var data = {};
            var searchCondition = $(this).serializeArray();
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = searchCondition[i]['value'];
            }
            easyuiDatagrid.datagrid('load',data);
            return false;
        });
		searchForm.find('input[name=dd_number]').textbox({
            onChange: function(){
                searchForm.submit();
            }
        });
        searchForm.find('input[name=status]').combobox({
            valueField:'value',
            textField:'text',
            data: [{"value":"","text":"不限"},{"value":"1","text":"1.需求已发起"},{"value":"2","text":"2.需求已满足"},{"value":"3","text":"3.调拨到车已确认"}],
            editable: false,
            onChange: function(){
                searchForm.submit();
            }
        });
        //构建查询表单结束
        
		//初始化新增需求发起窗口
        $('#easyui-dialog-process-car-transfer1-add').dialog({
            title: '新增需求发起',
            iconCls:'icon-add', 
            width: '600px',   
            height: '300px',   
            closed: true,   
            cache: true,   
            modal: true,
            maximizable: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    //回调添加页面submitForm方法
                    ProcessCarTransfer1Add.submitForm();
                }
            },{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui-dialog-process-car-transfer1-add').dialog('close');
                }
            }],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
		//初始化修改需求发起窗口
        $('#easyui-dialog-process-car-transfer1-edit').dialog({
            title: '修改需求发起',
            iconCls:'icon-add', 
            width: '600px',   
            height: '300px',   
            closed: true,   
            cache: true,   
            modal: true,
            maximizable: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    //回调添加页面submitForm方法
                    ProcessCarTransfer1Edit.submitForm();
                }
            },{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui-dialog-process-car-transfer1-edit').dialog('close');
                }
            }],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
		//初始化需求车辆管理
        $('#easyui-dialog-process-car-transfer1-list').dialog({
            title: '&nbsp;需求车辆管理', 
            iconCls:'icon-edit',
            width: '80%',   
            height: '90%',
            closed: true,   
            cache: true,   
            modal: true,
            maximizable: true,
            buttons: [],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
    }
    ProcessCarTransfer1.init();
    //获取选择的记录
    ProcessCarTransfer1.getSelected = function(){
        var datagrid = $('#easyui-datagrid-process-car-transfer1');
        var selectRow = datagrid.datagrid('getSelected');
        if(!selectRow){
            $.messager.alert('错误','请选择要操作的记录','error');   
            return false;
        }
        return selectRow;
    }
    //新增需求发起
    ProcessCarTransfer1.add = function(){
        $('#easyui-dialog-process-car-transfer1-add').dialog('open');
        $('#easyui-dialog-process-car-transfer1-add').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/car-transfer/add1']); ?>");
    }
	//修改需求发起
    ProcessCarTransfer1.edit = function(id){
		if(!id){
            var selectRow = this.getSelected();
            if(!selectRow)  return false;
            id = selectRow.id;
        }
        $('#easyui-dialog-process-car-transfer1-edit').dialog('open');
        $('#easyui-dialog-process-car-transfer1-edit').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/car-transfer/edit1']); ?>&id="+id);
    }
	//需求车辆管理 
	ProcessCarTransfer1.list = function(id){
		if(!id){
            var selectRow = this.getSelected();
            if(!selectRow)  return false;
            id = selectRow.id;
        }
        $('#easyui-dialog-process-car-transfer1-list').dialog('open');
        $('#easyui-dialog-process-car-transfer1-list').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/car-transfer/list1']); ?>&id="+id);
	}
	//进入下一个状态
    ProcessCarTransfer1.toNextStatus = function(id){
		var selectRow = this.getSelected();
        if(!selectRow) return false;
        var id = selectRow.id;
        $.messager.confirm('提交确定','提交后不能修改需求车辆，您确定要提交该条数据？',function(r){
            if(r){
                $.ajax({
                    type: 'get',
                    url: "<?php echo yii::$app->urlManager->createUrl(['process/car-transfer/to-next-status']); ?>",
                    data: {id: id,next_status:1},
                    dataType: 'json',
                    success: function(data){
                        if(data){
                            $.messager.alert('删除成功',data.info,'info');   
                            $('#easyui-datagrid-process-car-transfer1').datagrid('reload');
                        }else{
                            $.messager.alert('删除失败',data.info,'error');   
                        }
                    }
                });
            }
        });
    }
    //删除
    ProcessCarTransfer1.remove = function(){
        var selectRow = this.getSelected();
        if(!selectRow) return false;
        var id = selectRow.id;
        $.messager.confirm('确定删除','删除后不可恢复，您确定要删除该条流程吗？',function(r){
            if(r){
                $.ajax({
                    type: 'get',
                    url: "<?php echo yii::$app->urlManager->createUrl(['process/car-transfer/remove1']); ?>",
                    data: {id: id},
                    dataType: 'json',
                    success: function(data){
                        if(data){
                            $.messager.alert('删除成功',data.info,'info');   
                            $('#easyui-datagrid-process-car-transfer1').datagrid('reload');
                        }else{
                            $.messager.alert('删除失败',data.info,'error');   
                        }
                    }
                });
            }
        });
    }
  	//按条件导出车辆列表
    ProcessCarTransfer1.export = function(){
        var url = "<?php echo yii::$app->urlManager->createUrl(['car/car-back/export']);?>";
        var form = $('#search-form-process-car-transfer1');
        var data = {};
        var searchCondition = form.serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        for(var i in data){
            url += '&'+i+'='+data[i];
        }
        window.open(url);
    }
    //重置查询表单
    ProcessCarTransfer1.resetForm = function(){
        var easyuiForm = $('#search-form-process-car-transfer1');
        easyuiForm.form('reset');
        easyuiForm.submit();
    }
</script>