<script type="text/javascript" src="<?= yii::getAlias('@web'); ?>/jquery-easyui-1.4.3/plugins/jquery.datagrid_detailview.js"></script>
<table id="easyui-datagrid-process-car-transfer3"></table> 
<div id="easyui-datagrid-process-car-transfer3-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-process-car-transfer3">
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
                        <button onclick="ProcessCarTransfer3.resetForm();return false;" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
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
<div id="easyui-dialog-process-car-transfer3-car-submit"></div>
<div id="easyui-dialog-process-car-transfer3-edit"></div>
<div id="easyui-dialog-process-car-transfer3-details"></div>
<!-- 窗口 -->
<script>
    var ProcessCarTransfer3 = new Object();
    ProcessCarTransfer3.init = function(){
        var easyuiDatagrid = $('#easyui-datagrid-process-car-transfer3');
        //获取列表数据
        easyuiDatagrid.datagrid({
            method: 'get', 
            url:"<?php echo yii::$app->urlManager->createUrl(['process/car-transfer/get-list3']); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-process-car-transfer3-toolbar",
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
				    field: 'attachment_url',title: 'PDF下载',align: 'center',sortable:true,
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
					url:'<?php echo yii::$app->urlManager->createUrl(['process/car-transfer/get-details-data3']); ?>&transfer_id='+row.id,
					fitColumns:true,
					singleSelect:true,
					loadMsg:'',
					height:'auto',
					columns:[[
						{field:'car_brand_name',title:'车辆品牌',width:100},
						{field:'car_model_name',title:'车型',width:100},
						{field:'plate_number',title:'车牌',width:100},
						{field:'vehicle_dentification_number',title:'车架号',width:100},
						{field: 'start_time',title: '发车日期',sortable: true,
							formatter: function(value){
								if(!isNaN(value) && value >0){
									return formatDateToString(value);
								}
							}
						},
						{field: 'end_time',title: '实际到车时间',
							formatter: function(value){
								if(!isNaN(value) && value >0){
									return formatDateToString(value);
								}
							}
						},
						{field: 'credentials_status',title: '证件是否齐全',
							formatter: function(value){
								if(value==1){
									return '是';
								}else if(value==2){
									return '否';
								}
							}
						},
						{field: 'abnormal_note',title: '车辆异常情况',width:100},
						{field: 'transport_company',title: '承运商',width:100},
						{field: 'transport_tel',title: '承运人电话',width:100},
						{field: 'transport_money',title: '运费',width:100},
						{
							field:'is_confirm',title:'确认状态',width:100,
							formatter:function(value){
								if(value==1){
									return '已确认';
								}else {
									return '未确认';
								}
							}
						},
						{
							field:'oper',title:'操作',width:100,
							formatter:function(value, row, index){
                               var tdContext = '<a href="javascript:void(0)" onclick="ProcessCarTransfer3.carSubmit('+(row.id)+')">确认提交</a> ';  
							   return tdContext;
							}
						}
					]],
					onResize:function(){
						$('#easyui-datagrid-process-car-transfer3').datagrid('fixDetailRowHeight',index);
					},
					onLoadSuccess:function(){
						setTimeout(function(){
							$('#easyui-datagrid-process-car-transfer3').datagrid('fixDetailRowHeight',index);
						},0);
					}
				});
				$('#easyui-datagrid-process-car-transfer3').datagrid('fixDetailRowHeight',index);
			}
        });
        //构建查询表单
        var searchForm = $('#search-form-process-car-transfer3');
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
        
		//初始化确认提交窗口
        $('#easyui-dialog-process-car-transfer3-car-submit').dialog({
            title: '确认提交',
            iconCls:'icon-add', 
            width: '800px',   
            height: '400px',   
            closed: true,   
            cache: true,   
            modal: true,
            maximizable: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    //回调添加页面submitForm方法
                    ProcessCarTransfer3CarSubmit.submitForm();
                }
            },{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui-dialog-process-car-transfer3-car-submit').dialog('close');
                }
            }],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
    }
    ProcessCarTransfer3.init();
    //获取选择的记录
    ProcessCarTransfer3.getSelected = function(){
        var datagrid = $('#easyui-datagrid-process-car-transfer3');
        var selectRow = datagrid.datagrid('getSelected');
        if(!selectRow){
            $.messager.alert('错误','请选择要操作的记录','error');   
            return false;
        }
        return selectRow;
    }
   
	//确认提交
	ProcessCarTransfer3.carSubmit = function(id){
        $('#easyui-dialog-process-car-transfer3-car-submit').dialog('open');
        $('#easyui-dialog-process-car-transfer3-car-submit').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/car-transfer/car-submit']); ?>&id="+id);
	}

  	//按条件导出车辆列表
    ProcessCarTransfer3.export = function(){
        var url = "<?php echo yii::$app->urlManager->createUrl(['car/car-back/export']);?>";
        var form = $('#search-form-process-car-transfer3');
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
    ProcessCarTransfer3.resetForm = function(){
        var easyuiForm = $('#search-form-process-car-transfer3');
        easyuiForm.form('reset');
        easyuiForm.submit();
    }
</script>