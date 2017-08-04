<table id="easyui-datagrid-process-repair-archive-maintain"></table> 
<div id="easyui-datagrid-process-repair-archive-maintain-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-process-repair-archive-maintain">
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
                        <div class="item-name">完结时间</div>
                        <div class="item-input" style="width:220px">
                            <input class="easyui-datebox" type="text" name="start_archive_time" style="width:100px;"
                                   data-options=""
                                />
                            -
                            <input class="easyui-datebox" type="text" name="end_archive_time" style="width:100px;"
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
                        <button onclick="ProcessRepairArchiveMaintain.resetForm();" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
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

 <div id="easyui-dialog-process-repair-maintain-archive-confirm">

</div>
<script>
    var ProcessRepairArchiveMaintain = new Object();
    ProcessRepairArchiveMaintain.init = function(){
        //获取列表数据process-repair
        $('#easyui-datagrid-process-repair-archive-maintain').datagrid({  
            method: 'POST', 
            url:"<?php echo yii::$app->urlManager->createUrl(['process/repair/maintain-archive']); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-process-repair-archive-maintain-toolbar",
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
                 {field: 'plate_number',title: '车牌号',width: 120,align: 'center',sortable: true},
                 {field: 'order_no',title: '工单号',width: 120,align: 'center',sortable: true},
                 {field: 'type',title: '工单类型',width: 120,align: 'center',sortable: true},
                 {field: 'scene_desc',title: '工单内容简述',width: 210,align: 'left',sortable: true},
                 {field: 'status',title: '工单状态',width: 120,align: 'center',sortable: true,
                	 formatter: function (value, row, index) {
							if(value==8)
							{
								return '<span style="background-color:#FF4040;color:#fff;padding:2px 5px;">未归档<font>';
							}else{
								return '<span style="background-color:#05CD69;color:#fff;padding:2px 5px;">已完结<font>';
							}
                      }
                 },
                 {field: 'maintain_way',title: '维修方式',width: 120,align: 'center',sortable: true},
                 {field: 'countdown',title: '48小时响应',width: 120,align: 'center',
                	 formatter: function (value, row, index) {
							if(value == '超时')
							{
								return '<span style="background-color:#FF4040;color:#fff;padding:2px 5px;">超时<font>';
							}else if(value == '及时'){
								return '<span style="background-color:#05CD69;color:#fff;padding:2px 5px;">及时<font>';
							}
                	}
                },
                 {field: 'time',title: '工单创建时间',width: 120,align: 'center',sortable: true},
                /* {field: 'assign_time',title: '工单指派时间',width: 120,align: 'center', sortable: true,},  
                 {field: 'confirm_time',title: '工单确认时间',width: 120,align: 'center',sortable: true},*/
                 {field: 'archive_time',title: '工单完结时间',width: 120,align: 'center', sortable: true,}, 
                 {field: 'archive_name',title: '归档人',width: 120,align: 'center', sortable: true,},
               /*  {field: 'is_attendance',title: '是否出外勤',width: 120,align: 'center', sortable: true,},*/
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



    $("#easyui-dialog-process-repair-maintain-archive-confirm").dialog({
    	title: '确认归档',   
        width: '620px',   
        height: '650px',   
        closed: true,   
        cache: true,   
        modal: true,
        resizable:true,
        maximizable: true,
        buttons: [{
			text:'确认',
			iconCls:'icon-ok',
			handler:function(){
                //var form = $('#easyui-form-process-repair-maintain-edit-from');
              //  if(!form.form('validate')) return false;
				//var data = form.serialize();
				$.ajax({
					type: 'post',
					url: "<?php echo yii::$app->urlManager->createUrl(['process/repair/archive-confirm']); ?>",
				//	data: data,
					dataType: 'json',
					success: function(data){
						if(data.status){
							$.messager.alert('归档成功',data.info,'info');
							$('#easyui-dialog-process-repair-maintain-archive-confirm').dialog('close');
							$('#easyui-datagrid-process-repair-archive-maintain').datagrid('reload');
						}else{
							$.messager.alert('归档失败',data.info,'error');
						}
					}
				});
			}
		},{
			text:'驳回',
			iconCls:'icon-cancel',
			handler:function(){
				//$('#easyui-dialog-process-repair-maintain-archive-confirm').dialog('close');
				$.ajax({
					type: 'post',
					url: "<?php echo yii::$app->urlManager->createUrl(['process/repair/no-archive-confirm']); ?>",
				//	data: data,
					dataType: 'json',
					success: function(data){
						if(data.status){
							$.messager.alert('操作成功',data.info,'info');
							$('#easyui-dialog-process-repair-maintain-archive-confirm').dialog('close');
							$('#easyui-datagrid-process-repair-archive-maintain').datagrid('reload');
						}else{
							$.messager.alert('操作失败',data.info,'error');
						}
					}
				});
			}
		}],
        onClose: function(){
            $(this).dialog('clear');
        }
    });
    
  	//执行
    ProcessRepairArchiveMaintain.init();
    //查询表单构建
    var searchForm = $('#search-form-process-repair-archive-maintain');
    searchForm.submit(function(){
        var data = {};
        var searchCondition = $(this).serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        $('#easyui-datagrid-process-repair-archive-maintain').datagrid('load',data);
        return false;
    });
    searchForm.find('input[name=type]').combobox({
        valueField:'value',
        textField:'text',
        editable: false,
        panelHeight:'auto',
        data: [{"value": '',"text": '不限'},{"value": 1,"text": '车辆报修'},{"value": 2,"text": '出险理赔'},{"value": 3,"text": '自报车辆维修'}],
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
               {"value": 8,"text": '未归档'},
               {"value": 7,"text": '已完结'},
               ],
        onSelect: function(){
            searchForm.submit();
        }
    });
    //查询表单构建结束
    //获取选择的记录
    ProcessRepairArchiveMaintain.getSelected = function(){
        var datagrid = $('#easyui-datagrid-process-repair-archive-maintain');
        var selectRow = datagrid.datagrid('getSelected');
        if(!selectRow){
            $.messager.alert('错误','请选择要操作的记录','error');   
            return false;
        }
        return selectRow;
    }


    //归档
    ProcessRepairArchiveMaintain.confirm = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        if(selectRow.status ==7)
		{
			 $.messager.alert('错误','该工单维修结果已归档!','error');   
			 return false;
		}
        var id = selectRow.id;
        var src = "<?php echo yii::$app->urlManager->createUrl(['process/repair/archive-info']); ?>&id="+id;
        var iframe= '<iframe scrolling="auto" id="openXXXIframe" frameborder="0"  src="'+src+'" style="width:100%;height:100%;"></iframe>';
       	$("#easyui-dialog-process-repair-maintain-archive-confirm").append(iframe);
        $('#easyui-dialog-process-repair-maintain-archive-confirm').dialog('open');

		

        
    }


    

  	//查看工单
    ProcessRepairArchiveMaintain.info = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var order_no = selectRow.order_no;
        var id = selectRow.id;
        $('#easyui-dialog-process-repair-archive-maintain-info').dialog('open');
        $('#easyui-dialog-process-repair-archive-maintain-info').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/repair/info']); ?>&order_no="+order_no+"&id="+id);
    }
  //导出列表
    ProcessRepairArchiveMaintain.export_excel = function(){
        var form = $('#search-form-process-repair-archive-maintain');
        window.open("<?= yii::$app->urlManager->createUrl(['process/repair/export']); ?>&export_type=3&category=6&"+form.serialize());
    }
  //导出工单列表
    ProcessRepairArchiveMaintain.archive_export = function(){
    	var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var order_no = selectRow.order_no;
        var id = selectRow.id;
        window.open("<?= yii::$app->urlManager->createUrl(['process/repair/archive-export']); ?>&order_no="+order_no+"&id="+id);
    }
  //查看工单
    ProcessRepairArchiveMaintain.archive_info = function(){
    	var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var order_no = selectRow.order_no;
        var id = selectRow.id;
        window.open("<?= yii::$app->urlManager->createUrl(['process/repair/archive-info']); ?>&order_no="+order_no+"&id="+id);
    }
    //重置查询表单
    ProcessRepairArchiveMaintain.resetForm = function(){
        var easyuiForm = $('#search-form-process-repair-archive-maintain');
        easyuiForm.form('reset');
    }
    
</script>