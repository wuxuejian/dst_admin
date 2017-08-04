<table id="easyui-datagrid-process-repair-index"></table> 
<div id="easyui-datagrid-process-repair-index-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-process-repair-index">
                <ul class="search-main">
                    <li>
                        <div class="item-name">车牌号</div>
                        <div class="item-input">
                            <input name="car_no" class="easyui-textbox" style="width:100%;" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">报修人</div>
                        <div class="item-input">
                            <input name="repair_name" class="easyui-textbox" style="width:100%;" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">来电号码</div>
                        <div class="item-input">
                            <input name="tel" style="width:100%;" class="easyui-textbox" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">工单号</div>
                        <div class="item-input">
                            <input name="order_no" style="width:100%;" class="easyui-textbox" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">来电时间</div>
                        <div class="item-input" style="width:220px">
                            <input class="easyui-datebox" type="text" name="start_tel_time" style="width:100px;"
                                   data-options=""
                                />
                            -
                            <input class="easyui-datebox" type="text" name="end_tel_time" style="width:100px;"
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
                        <div class="item-name">车辆运营公司</div>
                        <div class="item-input">
                            <input name="operating_company_id" style="width:100%;" />
                        </div>
                    </li>
                    
                    

                    <li class="search-button">
                        <button class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button onclick="ProcessRepairIndex.resetForm();" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
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
<div id="easyui-dialog-process-repair-index-add"></div>
<div id="easyui-dialog-process-repair-index-edit"></div>
<div id="easyui-dialog-process-repair-index-info"></div>
<!-- 窗口 -->
<script>
    var ProcessRepairIndex = new Object();
    ProcessRepairIndex.init = function(){
        //获取列表数据process-repair
        $('#easyui-datagrid-process-repair-index').datagrid({  
            method: 'POST', 
            url:"<?php echo yii::$app->urlManager->createUrl(['process/repair/index']); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-process-repair-index-toolbar",
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
                		 switch (value){
	 						case '1':
	 							value = '<span style="background-color:#FF4040;color:#fff;padding:2px 5px;">工单已提交,等待指派<font>';
	 							break;
	 						case '2':
	 							value = '<span style="background-color:#FF4040;color:#fff;padding:2px 5px;">工单已指派,等待确认<font>';
	 							break;
	 						case '3':
	 							value = '<span style="background-color:#FF4040;color:#fff;padding:2px 5px;">工单已确认,出外勤中<font>';
	 							break;
	 						case '4':
	 							value = '<span style="background-color:#FF4040;color:#fff;padding:2px 5px;">工单已确认,等待归档<font>';
	 							break;
	 						case '5':
	 							value = '<span style="background-color:#FF4040;color:#fff;padding:2px 5px;">车辆维修中<font>';
	 							break;
	 						case '6':
	 							value = '<span style="background-color:#FF4040;color:#fff;padding:2px 5px;">故障已修复,等待归档<font>';
	 							break;
	 						case '7':
	 							value = '<span style="background-color:#05CD69;color:#fff;padding:2px 5px;">已完结<font>';
	 							break;
 							}

							return value;
                     }
                 },
                 {field: 'repair_name',title: '报修人',width: 120,align: 'center',sortable: true},
                 {field: 'tel',title: '来电号码',width: 120,align: 'center', sortable: true,},
                 {field: 'urgency',title: '紧急程度',width: 120,align: 'center',sortable: true},
                 {field: 'accept_name',title: '受理人',width: 120,align: 'center',sortable: true},
                 {field: 'time',title: '创建时间',width: 120,align: 'center',sortable: true},   
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
	//初始化添加窗口
	$('#easyui-dialog-process-repair-index-add').dialog({
    	title: '新增',   
        width: '750px',   
        height: '480px',   
        closed: true,   
        cache: true,   
        modal: true,
        resizable:true,
        maximizable: true,
        buttons: [{
			text:'确定',
			iconCls:'icon-ok',
			handler:function(){
                var form = $('#easyui-form-process-repair-add');
                if(!form.form('validate')) return false;
				var data = form.serialize();
				$.ajax({
					type: 'post',
					url: "<?php echo yii::$app->urlManager->createUrl(['process/repair/add']); ?>",
					data: data,
					dataType: 'json',
					success: function(data){
						if(data.status){
							$.messager.alert('添加成功',data.info,'info');
							$('#easyui-dialog-process-repair-index-add').dialog('close');
							$('#easyui-datagrid-process-repair-index').datagrid('reload');
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
				$('#easyui-dialog-process-repair-index-add').dialog('close');
			}
		}],
        onClose: function(){
            $(this).dialog('clear');
        }
    });

	//初始化编辑窗口
	$('#easyui-dialog-process-repair-index-edit').dialog({
    	title: '编辑',   
        width: '750px',   
        height: '480px',   
        closed: true,   
        cache: true,   
        modal: true,
        resizable:true,
        maximizable: true,
        buttons: [{
			text:'确定',
			iconCls:'icon-ok',
			handler:function(){
                var form = $('#easyui-form-process-repair-edit');
                if(!form.form('validate')) return false;
				var data = form.serialize();
				$.ajax({
					type: 'post',
					url: "<?php echo yii::$app->urlManager->createUrl(['process/repair/edit']); ?>",
					data: data,
					dataType: 'json',
					success: function(data){
						if(data.status){
							$.messager.alert('编辑成功',data.info,'info');
							$('#easyui-dialog-process-repair-index-edit').dialog('close');
							$('#easyui-datagrid-process-repair-index').datagrid('reload');
						}else{
							$.messager.alert('编辑失败',data.info,'error');
						}
					}
				});
			}
		},{
			text:'取消',
			iconCls:'icon-cancel',
			handler:function(){
				$('#easyui-dialog-process-repair-index-edit').dialog('close');
			}
		}],
        onClose: function(){
            $(this).dialog('clear');
        }
    });

  	//初始流程步骤管理窗口
    $('#easyui-dialog-process-repair-index-info').window({
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


    
  	//执行
    ProcessRepairIndex.init();
    //查询表单构建
    var searchForm = $('#search-form-process-repair-index');
    searchForm.submit(function(){
        var data = {};
        var searchCondition = $(this).serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        $('#easyui-datagrid-process-repair-index').datagrid('load',data);
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
               {"value": 1,"text": '已提交'},
               {"value": 2,"text": '已指派'},
               {"value": 3,"text": '已确认'},
               {"value": 5,"text": '维修中'},
               {"value": 6,"text": '已修复'},
               {"value": 7,"text": '已完结'},
               ],
        onSelect: function(){
            searchForm.submit();
        }
    });
    searchForm.find('input[name=operating_company_id]').combobox({
            valueField:'value',
            textField:'text',
            data: <?= json_encode($searchFormOptions['operating_company_id']); ?>,
            editable: false,
            panelHeight:'auto',
            onSelect: function(){
                searchForm.submit();
            }
        });
    
    //查询表单构建结束
    //获取选择的记录
    ProcessRepairIndex.getSelected = function(){
        var datagrid = $('#easyui-datagrid-process-repair-index');
        var selectRow = datagrid.datagrid('getSelected');
        if(!selectRow){
            $.messager.alert('错误','请选择要操作的记录','error');   
            return false;
        }
        return selectRow;
    }
  	//新增客户报修
    ProcessRepairIndex.add = function(){
        $('#easyui-dialog-process-repair-index-add').dialog('open');
        $('#easyui-dialog-process-repair-index-add').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/repair/add']); ?>");
    }
  	//编辑
    ProcessRepairIndex.edit = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var id = selectRow.id;
        if(selectRow.status >1)
        {
        	$.messager.alert('操作失败','工单已被指派，无法修改','error');   
        	return false;  
        }
        $('#easyui-dialog-process-repair-index-edit').dialog('open');
        $('#easyui-dialog-process-repair-index-edit').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/repair/edit']); ?>&id="+id);
    }
    //删除
	ProcessRepairIndex.remove = function(){
		var selectRow = this.getSelected();
        if(!selectRow) return false;
        var id = selectRow.id;
        if(selectRow.status >1)
        {
        	$.messager.alert('操作失败','工单已被指派，无法删除','error');   
        	return false;  
        }
		$.messager.confirm('确定删除','您确定要删除该工单？',function(r){
			if(r){
				$.ajax({
					type: 'post',
					url: '<?php echo yii::$app->urlManager->createUrl(['process/repair/delete']); ?>&id='+id,
					data: {id: id},
					dataType: 'json',
					success: function(data){
						if(data.status){
							$.messager.alert('删除成功',data.info,'info');   
							$('#easyui-datagrid-process-repair-index').datagrid('reload');
						}else{
							$.messager.alert('删除失败',data.info,'error');   
						}
					}
				});
			}
		});
	}
  	//查看工单
    ProcessRepairIndex.info = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var order_no = selectRow.order_no;
        $('#easyui-dialog-process-repair-index-info').dialog('open');
        $('#easyui-dialog-process-repair-index-info').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/repair/info']); ?>&order_no="+order_no);
    }
  //导出
    ProcessRepairIndex.export_excel = function(){
        var form = $('#search-form-process-repair-index');
        window.open("<?= yii::$app->urlManager->createUrl(['process/repair/export']); ?>&category=1&"+form.serialize());
    }
    //重置查询表单
    ProcessRepairIndex.resetForm = function(){
        var easyuiForm = $('#search-form-process-repair-index');
        easyuiForm.form('reset');
    }
    
</script>