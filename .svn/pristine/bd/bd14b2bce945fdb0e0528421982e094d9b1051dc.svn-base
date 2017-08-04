<table id="easyui-datagrid-car-maintain-record"></table> 
<div id="easyui-datagrid-car-maintain-record-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-car-maintain-record">
                <ul class="search-main">
                    <li class="item-name">
                        <div class="item-name">归属客户</div>
                        <div class="item-input">
                            <input
                                id="easyui-form-car-maintain-recordCombogrid"
                                name="customer"
                                style="width:180px;"
                                />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">车牌/车架/发动机</div>
                        <div class="item-input">
                            <input name="plate_number" style="width:200px;" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">上次保养类型</div>
                        <div class="item-input">
                            <input name="type" style="width:200px">
                        </div>
                    </li>
                    <li>
                        <div class="item-name">上次保养时间</div>
                        <div class="item-input">
                            <input class="easyui-datebox" type="text" name="start_add_time" style="width:93px;"
                                   data-options="
                                        onChange:function(){
                                            $('#search-form-car-maintain-record').submit();
                                        }
                                   "
                                />
                            -
                            <input class="easyui-datebox" type="text" name="end_add_time" style="width:93px;"
                                   data-options="
                                        onChange:function(){
                                            $('#search-form-car-maintain-record').submit();
                                        }
                                   "
                                />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">上次保养厂</div>
                        <div class="item-input">
                            <input name="maintenance_shop" style="width:200px;" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">下次保养类型</div>
                        <div class="item-input">
                            <input name="next_type" style="width:200px">
                        </div>
                    </li>
					<li>
                        <div class="item-name">车辆类型</div>
                        <div class="item-input">
                            <input style="width:200px;" name="car_type" />
                        </div>
                    </li>
					
                    
                    <li class="search-button" >
                        <button class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button onclick="MaintainRecord.resetForm();return false;" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
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
<div id="easyui-window-car-maintain-record"></div>
<div id="easyui-dialog-car-maintain-record-add"></div>
<div id="easyui-dialog-car-maintain-record-edit"></div>
<div id="easyui-dialog-car-maintain-record-scan"></div>
<!-- 窗口 -->
<script>
    var CarMaintainRecordIndex = new Object();
    CarMaintainRecordIndex.init = function(){
        var easyuiDatagrid = $('#easyui-datagrid-car-maintain-record');
        //获取列表数据
        easyuiDatagrid.datagrid({  
            method: 'get', 
            url:"<?php echo yii::$app->urlManager->createUrl(['car/maintain-record/get-list']); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-car-maintain-record-toolbar",
            pagination: true,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: false,
            frozenColumns: [[
                {field: 'ck',checkbox: true}, 
                {field: 'id',title: 'id',hidden: true}
            ]],
            columns:[
                [
                    {
                        field: 'plate_number',title: '车牌号',rowspan:2
                    },
                    {
                        field: 'vehicle_dentification_number',title: '车架号',rowspan:2
                    },
                    {
                        field: 'engine_number',title: '发动机号',rowspan:2
                    },
                    {
                        field: 'customer_name',title: '归属客户',rowspan:2
                    },
                    {title: '上次保养信息',colspan:5}, // 跨几列
                    {title: '下次保养信息',colspan:4}
                ],
               [
                {
                    field: 'maintain_time',title: '上次保养时间',width: 100
                },
                {
                    field: 'maintain_driving_mileage',title: '上次保养里程',width: 100
                },
                {
                    field: 'maintain_maintenance_shop',title: '上次保养厂',width: 100
                },
                {
                    field: 'maintain_type',title: '上次保养类型',
                    formatter: function(value,row,index){ //企业/个人客户名称
                        if(row.maintain_type == 1){
                            return 'A保';
                        }else if(row.maintain_type == 2){
                            return 'B保';
                        }else{
                            return '';
                        }
                    }
                },
                {
                    field: 'maintain_amount',title: '上次保养费用',width: 100
                },
                {
                    field: 'total_driving_mileage',title: '当前总里程',width: 100
                },
                {
                    field: 'tt1',title: '下次保养里程',
                    formatter: function(value,row,index){ //企业/个人客户名称
                        if(row.maintain_driving_mileage){
                            return Math.ceil(row.maintain_driving_mileage/10000)*10000;
                        }else{
                            return '';
                        }
                    }
                },
                {
                    field: 'tt2',title: '剩余里程',
                    formatter: function(value,row,index){ //企业/个人客户名称
                        if(row.maintain_driving_mileage){
                            if((Math.ceil(row.maintain_driving_mileage/10000)*10000-row.total_driving_mileage).toFixed(2)<0){
                            	return "<font color=red>"+(Math.ceil(row.maintain_driving_mileage/10000)*10000-row.total_driving_mileage).toFixed(2)+"</font>";
                            }else {
                            	return (Math.ceil(row.maintain_driving_mileage/10000)*10000-row.total_driving_mileage).toFixed(2);
                            }
                        }else{
                            return '';
                        }
                    }
                },
                {
                    field: 'number',title: '下次保养类型',
                    formatter: function(value,row,index){ //企业/个人客户名称
                        if(row.maintain_type == 1){
                            return 'B保';
                        }else if(row.maintain_type == 2){
                            return 'A保';
                        }else{
                            return '';
                        }
                    }
                }
            ]],
            onDblClickRow: function(rowIndex,rowData){
                MaintainRecord.edit(rowData.id);
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
        var searchForm = $('#search-form-car-maintain-record');
        searchForm.submit(function(){
            var data = {};
            var searchCondition = $(this).serializeArray();
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = searchCondition[i]['value'];
            }
            easyuiDatagrid.datagrid('load',data);
            return false;
        });
        searchForm.find('input[name=plate_number]').textbox({
            onChange: function(){
                searchForm.submit();
            }
        });
        searchForm.find('input[name=type]').combobox({
            valueField:'value',
            textField:'text',
            data: [{"value":"","text":"不限"},{"value":"1","text":"A保"},{"value":"2","text":"B保"}],
            editable: false,
            onChange: function(){
                searchForm.submit();
            }
        });
        searchForm.find('input[name=next_type]').combobox({
            valueField:'value',
            textField:'text',
            data: [{"value":"","text":"不限"},{"value":"1","text":"A保<br/>全车全面保养、高压安全检查"},{"value":"2","text":"B保<br/>主要项目检查、保养、高压安全检查"}],
            editable: false,
            onChange: function(){
                searchForm.submit();
            }
        });
		searchForm.find('input[name=car_type]').combobox({
        	valueField:'value',
            textField:'text',
            data: <?= json_encode($searchFormOptions['car_type']); ?>,
            editable: false,
            panelHeight:'auto',
            onSelect: function(){
                searchForm.submit();
            }
        });
        searchForm.find('input[name=maintenance_shop]').textbox({
            onChange: function(){
                searchForm.submit();
            }
        });
        //构建查询表单结束
        
      //初始化查看窗口
		$('#easyui-dialog-car-maintain-record-scan').window({
			title: '查看详情',
            width: '83%',   
            height: '83%',   
            closed: true,   
            cache: true,   
            modal: true,
            collapsible: false,
            minimizable: false, 
            maximizable: false,
            onClose: function(){
                $(this).window('clear');
            }       
		});
        //初始化归属客户
        $('#easyui-form-car-maintain-recordCombogrid').combogrid({
            panelWidth: 450,
            panelHeight: 200,
            missingMessage: '请输入检索后从下拉列表里选择一项！',
            onHidePanel:function(){
                var _combogrid = $(this);
                var value = _combogrid.combogrid('getValue');
                var text = _combogrid.combogrid('textbox').val();
                var row = _combogrid.combogrid('grid').datagrid('getSelected');
                if(!row){ //没有选择表格行但输入有检索字符串时，提示并清除检索字符串
                    if(text && value == text){
                        $.messager.show(
                            {
                                title: '无效值',
                                msg:'【' + text + '】不是有效值！请重新输入检索后，从下拉列表里选择一项！'
                            }
                        );
                        _combogrid.combogrid('clear');
                    }
                }
            },
            delay: 800,
            mode:'remote',
            idField: 'value',
            textField: 'text',
            url: '<?= yii::$app->urlManager->createUrl(['car/insurance/get-customers']); ?>',
            method: 'get',
            scrollbarSize:0,
            pagination: false,
            pageSize: 10,
            pageList: [10,20,30],
            fitColumns: true,
            rownumbers: true,
            onSelect: function(){
                searchForm.submit();
            },
            columns: [[
                {field:'value',title:'归属客户key',width:40,align:'center',hidden:true},
                {field:'text',title:'归属客户',width:150,align:'center'}
            ]]
        });
        //初始化车辆保养记录窗口
        $('#easyui-window-car-maintain-record').window({
            width: 800,   
            height: 460,   
            modal: true,
            closed: true,
            collapsible: false,
            minimizable: false,
            maximizable: false,
            onClose: function(){
                $(this).window('clear');
            }                      
        });
        //初始化添加窗口
        $('#easyui-dialog-car-maintain-record-add').dialog({
            title: '添加保养',   
            width: '380px',   
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
                    var form = $('#easyui-form-car-maintain-record-add');
                    if(!form.form('validate')) return false;
                    var data = form.serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['car/maintain-record/add']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('添加成功',data.info,'info');
                                $('#easyui-dialog-car-maintain-record-add').dialog('close');
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
                    $('#easyui-dialog-car-maintain-record-add').dialog('close');
                }
            }],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
        //初始化修改窗口
        $('#easyui-dialog-car-maintain-record-edit').dialog({
            title: '修改保养',   
            width: '380px',   
            height: '260px',   
            closed: true,   
            cache: true,   
            modal: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var form = $('#easyui-form-car-maintain-record-edit');
                    if(!form.form('validate')) return false;
                    var data = form.serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['car/maintain-record/edit']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('修改成功',data.info,'info');
                                $('#easyui-dialog-car-maintain-record-edit').dialog('close');
                                $('#easyui-datagrid-car-maintain-record').datagrid('reload');
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
                    $('#easyui-dialog-car-maintain-record-edit').dialog('close');
                }
            }],
            onClose: function(){
                $(this).dialog('clear');
            } 
        });
    }
    CarMaintainRecordIndex.init();
    //获取选择的记录
    CarMaintainRecordIndex.getSelected = function(){
        var datagrid = $('#easyui-datagrid-car-maintain-record');
        var selectRow = datagrid.datagrid('getSelected');
        if(!selectRow){
            $.messager.alert('错误','请选择要操作的记录','error');   
            return false;
        }
        return selectRow;
    }
    //添加
    CarMaintainRecordIndex.add = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var id = selectRow.id;

        $('#easyui-dialog-car-maintain-record-add').dialog('open');
        $('#easyui-dialog-car-maintain-record-add').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/maintain-record/add']); ?>&carId="+id);
    }
  	//查看
	CarMaintainRecordIndex.scan = function(){
		var selectRow = this.getSelected();
		if(!selectRow){
			return false;
		}
        var id = selectRow.id;
        $('#easyui-dialog-car-maintain-record-scan').window('open');
		$('#easyui-dialog-car-maintain-record-scan').window('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/maintain-record/scan']); ?>&id="+id);
	}
    //修改
    CarMaintainRecordIndex.edit = function(id){
        if(!id){
            var selectRow = this.getSelected();
            if(!selectRow){
                return false;
            }
            id = selectRow.id;
        }
        $('#easyui-dialog-car-maintain-record-edit').dialog('open');
        $('#easyui-dialog-car-maintain-record-edit').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/maintain-record/edit']); ?>&carId="+id);
    }
    //车辆保养记录
    CarMaintainRecordIndex.main = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var id = selectRow.id;
        $('#easyui-window-car-maintain-record').window('open');
        $('#easyui-window-car-maintain-record').window('setTitle',selectRow.plate_number+'保养记录');
        $('#easyui-window-car-maintain-record').window('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/maintain-record/main']); ?>&carId="+id);
    }
  	//按条件导出车辆列表
    CarMaintainRecordIndex.export = function(){
      //检查是否导出指定车辆
		var selectRows = $('#easyui-datagrid-car-maintain-record').datagrid('getSelections');
        if(selectRows == ""){
            var url = "<?php echo yii::$app->urlManager->createUrl(['car/maintain-record/export-width-condition']);?>";
			var form = $('#search-form-car-insurance-claim-log');
			var data = {};
			var searchCondition = form.serializeArray();
			for(var i in searchCondition){
				data[searchCondition[i]['name']] = searchCondition[i]['value'];
			}
			for(var i in data){
				url += '&'+i+'='+data[i];
			}
			window.open(url);
        }else {
			var id = '';
			for(var i in selectRows){
				id += selectRows[i].id+',';
			}
			window.open("<?php echo yii::$app->urlManager->createUrl(['car/maintain-record/export-choose']);?>&id="+id);
		}
    }
    //重置查询表单
    CarMaintainRecordIndex.resetForm = function(){
        var easyuiForm = $('#search-form-car-maintain-record');
        easyuiForm.form('reset');
        easyuiForm.submit();
    }
</script>