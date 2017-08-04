<table id="easyui-datagrid-process-repair-confirm"></table> 
<div id="easyui-datagrid-process-repair-confirm-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-process-repair-confirm">
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
                        <div class="item-name">指派对象</div>
                        <div class="item-input">
                            <input name="assign_name" class="easyui-textbox" style="width:100%;" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">确认时间</div>
                        <div class="item-input" style="width:220px">
                            <input class="easyui-datebox" type="text" name="start_confirm_time" style="width:100px;"
                                   data-options=""
                                />
                            -
                            <input class="easyui-datebox" type="text" name="end_confirm_time" style="width:100px;"
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
                    <li class="search-button">
                        <button class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button onclick="ProcessRepairConfirm.resetForm();" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
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
<div id="easyui-dialog-process-repair-confirm-affirm"></div>
<div id="easyui-dialog-process-repair-confirm-info"></div>
<div id="easyui-dialog-process-repair-confirm-affirm-info"></div>
<!-- 窗口 -->
<script>
    var ProcessRepairConfirm = new Object();
    ProcessRepairConfirm.init = function(){
        //获取列表数据process-repair
        $('#easyui-datagrid-process-repair-confirm').datagrid({  
            method: 'POST', 
            url:"<?php echo yii::$app->urlManager->createUrl(['process/repair/confirm']); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-process-repair-confirm-toolbar",
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
							if(value == 2)
							{
								return '<span style="background-color:#FF4040;color:#fff;padding:2px 5px;">未确认<font>';
							}else{
								return '<span style="background-color:#05CD69;color:#fff;padding:2px 5px;">已确认<font>';
							}
                      }
                 },
                 {field: 'assign_name',title: '指派对象',width: 120,align: 'center',sortable: true},
                 {field: 'assign_time',title: '指派时间',width: 120,align: 'center', sortable: true,},  
                 {field: 'confirm_time',title: '确认时间',width: 120,align: 'center', sortable: true,}, 
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

	//初始工单确认窗口
	$('#easyui-dialog-process-repair-confirm-affirm').dialog({
    	title: '确认工单',   
        width: '750px',   
        height: '350px',   
        closed: true,   
        cache: true,   
        modal: true,
        resizable:true,
        maximizable: true,
        buttons: [{
			text:'确定',
			iconCls:'icon-ok',
			handler:function(){
                var form = $('#easyui-form-process-repair-confirmed');
                if(!form.form('validate')) return false;
				var data = form.serialize();
				$.ajax({
					type: 'post',
					url: "<?php echo yii::$app->urlManager->createUrl(['process/repair/affirm']); ?>",
					data: data,
					dataType: 'json',
					success: function(data){
						if(data.status){
							$.messager.alert('确认成功',data.info,'info');
							$('#easyui-dialog-process-repair-confirm-affirm').dialog('close');
							$('#easyui-datagrid-process-repair-confirm').datagrid('reload');
						}else{
							$.messager.alert('确认失败',data.info,'error');
						}
					}
				});
			}
		},{
			text:'取消',
			iconCls:'icon-cancel',
			handler:function(){
				$('#easyui-dialog-process-repair-confirm-affirm').dialog('close');
			}
		}],
        onClose: function(){
            $(this).dialog('clear');
        }
    });

  	//初始查看工单详情
    $('#easyui-dialog-process-repair-confirm-info').window({
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
  //初始查看确认信息详情
    $('#easyui-dialog-process-repair-confirm-affirm-info').window({
        title: '工单确认信息',
    	width: 580,   
        height: 300,   
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
    ProcessRepairConfirm.init();
    //查询表单构建
    var searchForm = $('#search-form-process-repair-confirm');
    searchForm.submit(function(){
        var data = {};
        var searchCondition = $(this).serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        $('#easyui-datagrid-process-repair-confirm').datagrid('load',data);
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
               {"value": 2,"text": '未确认'},
               {"value": 3,"text": '已确认'},
               ],
        onSelect: function(){
            searchForm.submit();
        }
    });
    //查询表单构建结束
    //获取选择的记录
    ProcessRepairConfirm.getSelected = function(){
        var datagrid = $('#easyui-datagrid-process-repair-confirm');
        var selectRow = datagrid.datagrid('getSelected');
        if(!selectRow){
            $.messager.alert('错误','请选择要操作的记录','error');   
            return false;
        }
        return selectRow;
    }
  	//工单确认
    ProcessRepairConfirm.affirm = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var id = selectRow.id;
        if(selectRow.status >2)
        {
        	$.messager.alert('操作失败','该工单已被确认，不能重复确认。','error');   
        	return false;  
        }
        $('#easyui-dialog-process-repair-confirm-affirm').dialog('open');
        $('#easyui-dialog-process-repair-confirm-affirm').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/repair/affirm']); ?>&id="+id);
    }
  	//查看确认信息
    ProcessRepairConfirm.affirm_info = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var id = selectRow.id;
        $('#easyui-dialog-process-repair-confirm-affirm-info').dialog('open');
        $('#easyui-dialog-process-repair-confirm-affirm-info').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/repair/affirm-info']); ?>&id="+id);
    }
  	//查看工单
    ProcessRepairConfirm.info = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var order_no = selectRow.order_no;
        $('#easyui-dialog-process-repair-confirm-info').dialog('open');
        $('#easyui-dialog-process-repair-confirm-info').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/repair/info']); ?>&order_no="+order_no);
    }
  //导出
    ProcessRepairConfirm.export_excel = function(){
        var form = $('#search-form-process-repair-confirm');
        window.open("<?= yii::$app->urlManager->createUrl(['process/repair/export']); ?>&category=3&"+form.serialize());
    }
    //重置查询表单
    ProcessRepairConfirm.resetForm = function(){
        var easyuiForm = $('#search-form-process-repair-confirm');
        easyuiForm.form('reset');
    }
    
</script>