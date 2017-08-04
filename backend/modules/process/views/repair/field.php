<table id="easyui-datagrid-process-repair-field"></table> 
<div id="easyui-datagrid-process-repair-field-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-process-repair-field">
                <ul class="search-main">
                    <li>
                        <div class="item-name">车牌号</div>
                        <div class="item-input">
                            <input name="car_no" class="easyui-textbox" style="width:100%;" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">工单号</div>
                        <div class="item-input">
                            <input name="order_no" style="width:100%;" class="easyui-textbox" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">登记时间</div>
                        <div class="item-input" style="width:220px">
                            <input class="easyui-datebox" type="text" name="start_wq_time" style="width:100px;"
                                   data-options=""
                                />
                            -
                            <input class="easyui-datebox" type="text" name="end_wq_time" style="width:100px;"
                                   data-options=""
                                />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">工单类型</div>
                        <div class="item-input">
                            <input name="type" style="width:100%;" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">工单状态</div>
                        <div class="item-input">
                            <input name="status" style="width:100%;" />
                        </div>
                    </li>
                    <li>
                   		<div class="item-name">车辆品牌</div>
                    	<div class="item-input">
                    		<input class="easyui-combotree" name="brand_id"
                           data-options="
                                width:160,
                                url: '<?php echo yii::$app->urlManager->createUrl(['car/combotree/get-car-brands']); ?>',
                                editable: false,
                                panelHeight:'auto',
                                lines:false
                           "
                         />
                   		</div>
                    </li>
                    <li class="search-button">
                        <button class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button onclick="ProcessRepairField.resetForm();" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
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
        <a href="javascript:<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon']; ?>'"><?= $val['text']; ?></a>
        <?php } ?>
    </div>
    <?php } ?>
</div>
<!-- 窗口 -->
<div id="easyui-dialog-process-repair-field-reg"></div>
<div id="easyui-dialog-process-repair-field-info"></div>
<div id="easyui-dialog-process-repair-field-field-info"></div>
<!-- 窗口 -->
<script>
    var ProcessRepairField = new Object();
    ProcessRepairField.init = function(){
        //获取列表数据process-repair
        $('#easyui-datagrid-process-repair-field').datagrid({  
            method: 'POST', 
            url:"<?php echo yii::$app->urlManager->createUrl(['process/repair/field']); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-process-repair-field-toolbar",
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
                 {field: 'car_no',title: '车牌号',width: 120,align: 'center',sortable: true},
                 {field: 'order_no',title: '工单号',width: 120,align: 'center',sortable: true},
                 {field: 'type',title: '工单类型',width: 120,align: 'center',sortable: true},
                 {field: 'desc',title: '工单内容简述',width: 120,align: 'center', sortable: true,}, 
                 {field: 'status',title: '工单状态',width: 120,align: 'center',sortable: true,
                	 formatter: function (value, row, index) {
							if(value == 3)
							{
								return '<span style="background-color:#FF4040;color:#fff;padding:2px 5px;">未登记<font>';
							}else{
								return '<span style="background-color:#05CD69;color:#fff;padding:2px 5px;">已登记<font>';
							}
                      }
                 },
                 {field: 'assign_name',title: '指派对象',width: 120,align: 'center', sortable: true,},
                 {field: 'record_user',title: '受理人',width: 120,align: 'center', sortable: true,},
                 {field: 'is_go_scene',title: '是否进场维修',width: 120,align: 'center',sortable: true},
                 {field: 'maintain_scene',title: '维修站',width: 120,align: 'center', sortable: true,},  
                 {field: 'is_replace_car',title: '是否替换车辆',width: 120,align: 'center',sortable: true},
                 {field: 'replace_car',title: '替换车',width: 120,align: 'center', sortable: true,}, 
                 
                 {field: 'field_time',title: '记录时间',width: 120,align: 'center', sortable: true,}, 
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

	//初始外勤服务记录登记窗口
	$('#easyui-dialog-process-repair-field-reg').dialog({
    	title: '外勤服务记录',   
        width: '750px',   
        height: '650px',   
        closed: true,   
        cache: true,   
        modal: true,
        resizable:true,
        maximizable: true,
        buttons: [{
			text:'确定',
			iconCls:'icon-ok',
			handler:function(){
                var form = $('#easyui-form-process-repair-field-from');
                if(!form.form('validate')) return false;

                if ($("input[name='car_no_img']").val() == ""){
					$.messager.alert('登记失败','请上传车牌照片','error');
					return false;
				}
				if ($("input[name='dashboard_img']").val() == ""){
					$.messager.alert('登记失败','请上传车辆仪表照片','error');
					return false;
				}
				if ($("input[name='fault_scene_img']").val() == ""){
					$.messager.alert('登记失败','请上传故障现场照片','error');
					return false;
				}
                
				var data = form.serialize();
				$.ajax({
					type: 'post',
					url: "<?php echo yii::$app->urlManager->createUrl(['process/repair/field-reg']); ?>",
					data: data,
					dataType: 'json',
					success: function(data){
						if(data.status){
							$.messager.alert('登记成功',data.info,'info');
							$('#easyui-dialog-process-repair-field-reg').dialog('close');
							$('#easyui-datagrid-process-repair-field').datagrid('reload');
						}else{
							$.messager.alert('登记失败',data.info,'error');
						}
					}
				});
			}
		},{
			text:'取消',
			iconCls:'icon-cancel',
			handler:function(){
				$('#easyui-dialog-process-repair-field-reg').dialog('close');
			}
		}],
        onClose: function(){
            $(this).dialog('clear');
        }
    });

  	//初始查看工单详情
    $('#easyui-dialog-process-repair-field-info').window({
        title: '工单详情',
    	width: 550,   
        height: 500,   
        modal: true,
        closed: true,
        collapsible: false,
        minimizable: false,
        maximizable: false,
        onClose: function(){
            $(this).window('clear');
        }                    
    });
  //初始外勤登记记录详情
    $('#easyui-dialog-process-repair-field-field-info').window({
        title: '外勤服务详情',
    	width: 700,   
        height: 550,   
        modal: true,
        closed: true,
        collapsible: false,
        minimizable: false,
        maximizable: false,
        onClose: function(){
            $(this).window('clear');
        }                    
    });


    
  	//执行
    ProcessRepairField.init();
    //查询表单构建
    var searchForm = $('#search-form-process-repair-field');
    searchForm.submit(function(){
        var data = {};
        var searchCondition = $(this).serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        $('#easyui-datagrid-process-repair-field').datagrid('load',data);
        return false;
    });
    searchForm.find('input[name=type]').combobox({
        valueField:'value',
        textField:'text',
        editable: false,
        panelHeight:'auto',
        data: [{"value": '',"text": '不限'},{"value": 1,"text": '车辆报修'},{"value": 2,"text": '出险理赔'}],
        onSelect: function(){
            searchForm.submit();
        }
    });
    searchForm.find('input[name=status]').combobox({
        valueField:'value',
        textField:'text',
        editable: false,
        panelHeight:'auto',
        data: [
               {"value": '',"text": '不限'},
               {"value": 3,"text": '未登记'},
               {"value": 5,"text": '已登记'},
               ],
        onSelect: function(){
            searchForm.submit();
        }
    });
    //查询表单构建结束
    //获取选择的记录
    ProcessRepairField.getSelected = function(){
        var datagrid = $('#easyui-datagrid-process-repair-field');
        var selectRow = datagrid.datagrid('getSelected');
        if(!selectRow){
            $.messager.alert('错误','请选择要操作的记录','error');   
            return false;
        }
        return selectRow;
    }
  	//外勤登记
    ProcessRepairField.field_reg = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var id = selectRow.id;
        if(selectRow.status >3)
        {
        	$.messager.alert('操作失败','工单已经登记过了！','error');   
        	return false;  
        }
        $('#easyui-dialog-process-repair-field-reg').dialog('open');
        $('#easyui-dialog-process-repair-field-reg').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/repair/field-reg']); ?>&id="+id);
    }
  	//查看外勤登记详情
    ProcessRepairField.field_info = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var id = selectRow.id;
        $('#easyui-dialog-process-repair-field-field-info').dialog('open');
        $('#easyui-dialog-process-repair-field-field-info').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/repair/field-info']); ?>&id="+id);
    }
  	//查看工单
    ProcessRepairField.info = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var order_no = selectRow.order_no;
        $('#easyui-dialog-process-repair-field-info').dialog('open');
        $('#easyui-dialog-process-repair-field-info').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/repair/info']); ?>&order_no="+order_no);
    }
  //导出
    ProcessRepairField.export_excel = function(){
        var form = $('#search-form-process-repair-field');
        window.open("<?= yii::$app->urlManager->createUrl(['process/repair/export']); ?>&category=4&"+form.serialize());
    }
    //重置查询表单
    ProcessRepairField.resetForm = function(){
        var easyuiForm = $('#search-form-process-repair-field');
        easyuiForm.form('reset');
    }
    
</script>