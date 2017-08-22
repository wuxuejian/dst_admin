<script type="text/javascript" src="<?= yii::getAlias('@web'); ?>/jquery-easyui-1.4.3/plugins/jquery.datagrid_detailview.js"></script>
<table id="easyui-datagrid-process-car-transfer2"></table> 
<div id="easyui-datagrid-process-car-transfer2-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-process-car-transfer2">
                <input id="is_db" type="hidden" name="is_db" value="">
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
                        <button onclick="ProcessCarTransfer2.resetForm();return false;" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
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
<div id="easyui-dialog-process-car-transfer2-add"></div>
<div id="easyui-dialog-process-car-transfer2-edit"></div>
<div id="easyui-dialog-process-car-transfer2-details"></div>
<!-- 窗口 -->
<script>
    var ProcessCarTransfer2 = new Object();
    ProcessCarTransfer2.init = function(){
        var easyuiDatagrid = $('#easyui-datagrid-process-car-transfer2');
        //获取列表数据
        easyuiDatagrid.datagrid({
            method: 'get', 
            url:"<?php echo yii::$app->urlManager->createUrl(['process/car-transfer/get-list2']); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-process-car-transfer2-toolbar",
            pagination: true,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
			fitColumns: true,
            frozenColumns: [[
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
				    field: 'attachment_url',width: 180,title: 'PDF下载',align: 'center',
					formatter: function(value){
						return '<a href="'+value+'" target="_b">下载</a>';
					}
				},
                {
                    field: 'status',title: '流程状态',width: 100,align: 'center',sortable:true,
                    formatter: function(value,row,index){
                        var states = {"":"","1":'<span style="background-color:#FF4040;color:#fff;padding:2px 5px;">1.需求已发起<font>',"2":'<span style="background-color:#FF4040;color:#fff;padding:2px 5px;">2.需求已满足<font>',"3":'<span style="background-color:#05CD69;color:#fff;padding:2px 5px;">3.调拨到车已确认<font>'};
                        return states[value];
                    }
                }
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
            },
			view: detailview,
			detailFormatter: function(rowIndex, rowData){
				return '<div style="padding:2px"><table class="ddv"></table></div>';
			},
			onExpandRow: function(index,row){
				var ddv = $(this).datagrid('getRowDetail',index).find('table.ddv');
				ddv.datagrid({
					url:'<?php echo yii::$app->urlManager->createUrl(['process/car-transfer/get-list2-data']); ?>&transfer_id='+row.id,
					fitColumns:true,
					singleSelect:true,
					loadMsg:'',
					height:'auto',
					columns:[[
						{field:'car_brand_name',title:'车辆品牌',width:100},
						{field:'car_model_name',title:'车型',width:100},
						{field:'number',title:'需求台数',width:30},
						{field:'details_number',title:'已添加台数',width:30},
						{field:'pre_operating_company_name',title:'调入前所属运营公司',width:100},
						{field:'after_operating_company_name',title:'调入后所属运营公司',width:100},
						{
							field:'is_owner_change',title:'是否变更车辆所有人',width:60,
							formatter: function(value){
								if(value==1){
									return '是';
								}else if(value==2){
									return '否';
								}else {
									return '';
								}
							}
						},
						{field:'after_owner_name',title:'调入后车辆所有人',width:100},
						{field:'note',title:'备注',width:100},
						{
							field:'oper',title:'操作',width:100,
							formatter:function(value, row, index){
                               var tdContext = '<a href="javascript:void(0)" onclick="ProcessCarTransfer2.carDetails('+(row.id)+')">车辆明细管理</a> ';  
							   return tdContext;
							}
						}
					]],
					onResize:function(){
						$('#easyui-datagrid-process-car-transfer2').datagrid('fixDetailRowHeight',index);
					},
					onLoadSuccess:function(){
						setTimeout(function(){
							$('#easyui-datagrid-process-car-transfer2').datagrid('fixDetailRowHeight',index);
						},0);
					}
				});
				$('#easyui-datagrid-process-car-transfer2').datagrid('fixDetailRowHeight',index);
			}
        });
        //构建查询表单
        var searchForm = $('#search-form-process-car-transfer2');
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
        $('#easyui-dialog-process-car-transfer2-add').dialog({
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
                    ProcessCarTransfer2Add.submitForm();
                }
            },{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui-dialog-process-car-transfer2-add').dialog('close');
                }
            }],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
		//初始化修改需求发起窗口
        $('#easyui-dialog-process-car-transfer2-edit').dialog({
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
                    ProcessCarTransfer2Edit.submitForm();
                }
            },{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui-dialog-process-car-transfer2-edit').dialog('close');
                }
            }],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
		//初始化需求车辆管理
        $('#easyui-dialog-process-car-transfer2-details').dialog({
            title: '&nbsp;需求车辆明细管理', 
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
    ProcessCarTransfer2.init();
    //获取选择的记录
    ProcessCarTransfer2.getSelected = function(){
        var datagrid = $('#easyui-datagrid-process-car-transfer2');
        var selectRow = datagrid.datagrid('getSelected');
        if(!selectRow){
            $.messager.alert('错误','请选择要操作的记录','error');   
            return false;
        }
        return selectRow;
    }
    //新增需求发起
    ProcessCarTransfer2.add = function(){
        $('#easyui-dialog-process-car-transfer2-add').dialog('open');
        $('#easyui-dialog-process-car-transfer2-add').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/car-transfer/add2']); ?>");
    }
	//修改需求发起
    ProcessCarTransfer2.edit = function(id){
		if(!id){
            var selectRow = this.getSelected();
            if(!selectRow)  return false;
            id = selectRow.id;
        }
        $('#easyui-dialog-process-car-transfer2-edit').dialog('open');
        $('#easyui-dialog-process-car-transfer2-edit').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/car-transfer/edit2']); ?>&id="+id);
    }
	//需求车辆明细管理 
	ProcessCarTransfer2.carDetails = function(id){
        $('#easyui-dialog-process-car-transfer2-details').dialog('open');
        $('#easyui-dialog-process-car-transfer2-details').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/car-transfer/car-details']); ?>&transfer_list_id="+id);
	}
	//进入下一个状态
    ProcessCarTransfer2.toNextStatus = function(id){
		var selectRow = this.getSelected();
        if(!selectRow) return false;
        var id = selectRow.id;
        $.messager.confirm('提交确定','提交后不能修改需求车辆，您确定要提交该条数据？',function(r){
            if(r){
                $.ajax({
                    type: 'get',
                    url: "<?php echo yii::$app->urlManager->createUrl(['process/car-transfer/to-next-status']); ?>",
                    data: {id: id,next_status:2},
                    dataType: 'json',
                    success: function(data){
                        if(data){
                            $.messager.alert('删除成功',data.info,'info');   
                            $('#easyui-datagrid-process-car-transfer2').datagrid('reload');
                        }else{
                            $.messager.alert('删除失败',data.info,'error');   
                        }
                    }
                });
            }
        });
    }
    //删除
    ProcessCarTransfer2.remove = function(){
        var selectRow = this.getSelected();
        if(!selectRow) return false;
        var id = selectRow.id;
        $.messager.confirm('确定删除','删除后不可恢复，您确定要删除该条流程吗？',function(r){
            if(r){
                $.ajax({
                    type: 'get',
                    url: "<?php echo yii::$app->urlManager->createUrl(['process/car-transfer/remove2']); ?>",
                    data: {id: id},
                    dataType: 'json',
                    success: function(data){
                        if(data){
                            $.messager.alert('删除成功',data.info,'info');   
                            $('#easyui-datagrid-process-car-transfer2').datagrid('reload');
                        }else{
                            $.messager.alert('删除失败',data.info,'error');   
                        }
                    }
                });
            }
        });
    }
  	//按条件导出车辆列表
    ProcessCarTransfer2.export = function(){
        var url = "<?php echo yii::$app->urlManager->createUrl(['car/car-back/export']);?>";
        var form = $('#search-form-process-car-transfer2');
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
    ProcessCarTransfer2.resetForm = function(){
        var easyuiForm = $('#search-form-process-car-transfer2');
        easyuiForm.form('reset');
        easyuiForm.submit();
    }
</script>