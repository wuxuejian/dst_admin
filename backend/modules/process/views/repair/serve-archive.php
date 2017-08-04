<table id="easyui-datagrid-process-repair-archive-serve"></table> 
<div id="easyui-datagrid-process-repair-archive-serve-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-process-repair-archive-serve">
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
                     <li>
                        <div class="item-name">指派对象</div>
                        <div class="item-input">
                            <input name="assign_name" class="easyui-textbox" style="width:100%;" />
                        </div>
                    </li>
                    <li class="search-button">
                        <button class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button onclick="ProcessRepairArchiveServe.resetForm();" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
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

<div id="easyui-dialog-process-repair-serve-archive-confirm"></div>
<script>
    var ProcessRepairArchiveServe = new Object();
    ProcessRepairArchiveServe.init = function(){
        //获取列表数据process-repair
        $('#easyui-datagrid-process-repair-archive-serve').datagrid({  
            method: 'POST', 
            url:"<?php echo yii::$app->urlManager->createUrl(['process/repair/serve-archive']); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-process-repair-archive-serve-toolbar",
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
                 {field: 'desc',title: '工单内容简述',width: 120,align: 'center',sortable: true},
                 {field: 'status',title: '工单状态',width: 120,align: 'center',sortable: true,
                	 formatter: function (value, row, index) {
							if(value <= 5)
							{
								return '<span style="background-color:#FF4040;color:#fff;padding:2px 5px;">未归档<font>';
							}else{
								return '<span style="background-color:#05CD69;color:#fff;padding:2px 5px;">已完结<font>';
							}
                      }
                 },
                 {field: 'assign_name',title: '指派对象',width: 120,align: 'center', sortable: true,},
                 {field: 'archive_name',title: '归档人',width: 120,align: 'center', sortable: true,},
                 {field: 'time',title: '工单创建时间',width: 120,align: 'center',sortable: true},
                 {field: 'assign_time',title: '工单指派时间',width: 120,align: 'center', sortable: true,},  
                 {field: 'confirm_time',title: '工单确认时间',width: 120,align: 'center',sortable: true},
                 {field: 'archive_time',title: '工单完结时间',width: 120,align: 'center', sortable: true,}, 
                 
                 {field: 'is_attendance',title: '是否出外勤',width: 120,align: 'center', sortable: true,},
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


	$("#easyui-dialog-process-repair-serve-archive-confirm").dialog({
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
							$('#easyui-dialog-process-repair-serve-archive-confirm').dialog('close');
							$('#easyui-datagrid-process-repair-archive-serve').datagrid('reload');
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
				//$('#easyui-dialog-process-repair-serve-archive-confirm').dialog('close');
				$.ajax({
					type: 'post',
					url: "<?php echo yii::$app->urlManager->createUrl(['process/repair/no-archive-confirm']); ?>",
				//	data: data,
					dataType: 'json',
					success: function(data){
						if(data.status){
							$.messager.alert('操作成功',data.info,'info');
							$('#easyui-dialog-process-repair-serve-archive-confirm').dialog('close');
							$('#easyui-datagrid-process-repair-archive-serve').datagrid('reload');
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
    ProcessRepairArchiveServe.init();
    //查询表单构建
    var searchForm = $('#search-form-process-repair-archive-serve');
    searchForm.submit(function(){
        var data = {};
        var searchCondition = $(this).serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        $('#easyui-datagrid-process-repair-archive-serve').datagrid('load',data);
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
               {"value": 5,"text": '未归档'},
               {"value": 7,"text": '已完结'},
               ],
        onSelect: function(){
            searchForm.submit();
        }
    });
    //查询表单构建结束
    //获取选择的记录
    ProcessRepairArchiveServe.getSelected = function(){
        var datagrid = $('#easyui-datagrid-process-repair-archive-serve');
        var selectRow = datagrid.datagrid('getSelected');
        if(!selectRow){
            $.messager.alert('错误','请选择要操作的记录','error');   
            return false;
        }
        return selectRow;
    }
       
 	 //归档
    ProcessRepairArchiveServe.confirm = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        if(selectRow.status ==7)
		{
			 $.messager.alert('错误','该工单维修结果已归档!','error');   
			 return false;
		}
        var order_no = selectRow.order_no;
        var src = "<?php echo yii::$app->urlManager->createUrl(['process/repair/archive-info']); ?>&order_no="+order_no;
        var iframe= '<iframe scrolling="auto" id="openXXXIframe" frameborder="0"  src="'+src+'" style="width:100%;height:100%;"></iframe>';
        $("#easyui-dialog-process-repair-serve-archive-confirm").append(iframe);
        $('#easyui-dialog-process-repair-serve-archive-confirm').dialog('open');
      

    }




    
  	//查看工单（归档总记录）
    ProcessRepairArchiveServe.archive_info = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var order_no = selectRow.order_no;
        $('#easyui-dialog-process-repair-archive-serve-archive-info').dialog('open');
        $('#easyui-dialog-process-repair-archive-serve-archive-info').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/repair/actionInfo']); ?>&order_no="+order_no);
    }
  	//查看工单
    ProcessRepairArchiveServe.info = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var order_no = selectRow.order_no;
        $('#easyui-dialog-process-repair-archive-serve-info').dialog('open');
        $('#easyui-dialog-process-repair-archive-serve-info').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/repair/info']); ?>&order_no="+order_no);
    }
  //导出列表
    ProcessRepairArchiveServe.export_excel = function(){
        var form = $('#search-form-process-repair-archive-serve');
        window.open("<?= yii::$app->urlManager->createUrl(['process/repair/export']); ?>&category=6&"+form.serialize());
    }
  //导出工单列表
    ProcessRepairArchiveServe.archive_export = function(){
    	var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var order_no = selectRow.order_no;

        
        //var form = $('#search-form-process-repair-archive-serve');
   		//window.open("<?//= yii::$app->urlManager->createUrl(['process/repair/archive-export']); ?>&"+form.serialize());
        window.open("<?= yii::$app->urlManager->createUrl(['process/repair/archive-export']); ?>&order_no="+order_no);
    }
  //查看工单
    ProcessRepairArchiveServe.archive_info = function(){
    	var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var order_no = selectRow.order_no;
        window.open("<?= yii::$app->urlManager->createUrl(['process/repair/archive-info']); ?>&order_no="+order_no);
    }
    //重置查询表单
    ProcessRepairArchiveServe.resetForm = function(){
        var easyuiForm = $('#search-form-process-repair-archive-serve');
        easyuiForm.form('reset');
    }
    
</script>