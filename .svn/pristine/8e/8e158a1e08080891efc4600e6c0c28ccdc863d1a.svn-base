<table id="easyui-datagrid-process-repair-maintain"></table> 
<div id="easyui-datagrid-process-repair-maintain-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-process-repair-maintain">
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
                            <input class="easyui-datebox" type="text" name="start_wx_time" style="width:100px;"
                                   data-options=""
                                />
                            -
                            <input class="easyui-datebox" type="text" name="end_wx_time" style="width:100px;"
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
                        <div class="item-name">故障发生时间</div>
                        <div class="item-input" style="width:220px">
                            <input class="easyui-datebox" type="text" name="start_gz_time" style="width:100px;"
                                   data-options=""
                                />
                            -
                            <input class="easyui-datebox" type="text" name="end_gz_time" style="width:100px;"
                                   data-options=""
                                />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">受理人</div>
                        <div class="item-input">
                            <input  name="accept_name" style="width:100%;" class="easyui-textbox" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">承租客户</div>
                        <div class="item-input">
							<input  name="customer_name" style="width:100%;" class="easyui-textbox" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">故障分类</div>
                        <div class="item-input">
                            <input class="easyui-combotree" name="pid" style="width:100%;" 
                           data-options="
                                width:160,
                                url: '<?php echo yii::$app->urlManager->createUrl(['station/fault/get-categorys']); ?>&isShowRoot=1&mark=1',
                                editable: false,
                                panelHeight:'auto',
                                lines:false
                           "
                         />
                        </div>
                    </li>
                    <li class="search-button">
                        <button class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button onclick="ProcessRepairMaintain.resetForm();" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
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
<div id="easyui-dialog-process-repair-maintain-add"></div>
<div id="easyui-dialog-process-repair-maintain-edit"></div>
<div id="easyui-dialog-process-repair-maintain-info"></div>
<div id="easyui-dialog-process-repair-maintain-reg"></div>
<div id="easyui-dialog-process-repair-maintain-maintain-info"></div>
<div id="easyui-dialog-process-repair-maintain-fault"></div>

<!-- 窗口 -->
<script>
    var ProcessRepairMaintain = new Object();
    ProcessRepairMaintain.init = function(){
        //获取列表数据process-repair
        $('#easyui-datagrid-process-repair-maintain').datagrid({  
            method: 'POST', 
            url:"<?php echo yii::$app->urlManager->createUrl(['process/repair/maintain']); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-process-repair-maintain-toolbar",
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
                 {field: 'scene_desc',title: '工单故障内容描述',width: 210,align: 'left',sortable: true},
                 {field: 'status',title: '工单状态',width: 120,align: 'center',sortable: true,
                	 formatter: function (value, row, index) {
							if(value == 5)
							{
								return '<span style="background-color:#FF4040;color:#fff;padding:2px 5px;">维修中<font>';
							}else if(value == 6){
								return '<span style="background-color:#ffe48d;color:#fff;padding:2px 5px;">已修复<font>';
							}else{
								return '<span style="background-color:#05CD69;color:#fff;padding:2px 5px;">已结案<font>';
							}
                      }
                 },

                 {field: 'accept_name',title: '受理人',width: 120,align: 'center', sortable: true,},
                 {field: 'fault_start_time',title: '故障发生时间',width: 120,align: 'center', sortable: true,},
                 {field: 'maintain_way',title: '维修方式',width: 120,align: 'center',sortable: true},
                 {field: 'maintain_scene',title: '维修站',width: 120,align: 'center', sortable: true,},  
                 {field: 'countdown',title: '48小时倒计时',width: 120,align: 'center', 
                	 formatter: function (value, row, index) {
							if(value == '超时')
							{
								return '<span style="background-color:#FF4040;color:#fff;padding:2px 5px;">超时<font>';
							}else if(value == '及时'){
								return '<span style="background-color:#05CD69;color:#fff;padding:2px 5px;">及时<font>';
							}else{
								return value;
							}
                   }
                 },
              /*   {field: 'is_replace_car',title: '是否替换车辆',width: 120,align: 'center',sortable: true},
                 {field: 'replace_car',title: '替换车',width: 120,align: 'center', sortable: true,}, */
                 {field: 'fault_why',title: '故障原因简述',width: 120,align: 'center', sortable: true,},
                 {field: 'maintain_method',title: '故障处理方法',width: 120,align: 'center', sortable: true,},
                 {field: 'total_code',title: '故障编号',width: 120,align: 'center', sortable: true,},
                 {field: 'leave_factory_time',title: '维修结束时间',width: 120,align: 'center', sortable: true,},
                 {field: 'time',title: '记录时间',width: 120,align: 'center', sortable: true,}, 
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

	//初始进场维修登记 add
	$('#easyui-dialog-process-repair-maintain-add').dialog({
    	title: '车辆维修登记',   
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
                var form = $('#easyui-form-process-repair-maintain-add-from');
                if(!form.form('validate')) return false;
                var button = this;
                $(button).linkbutton('disable');
                /*
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
				if ($("input[name='fault_location_img']").val() == ""){
					$.messager.alert('登记失败','请上传故障位置照片','error');
					return false;
				}
				if ($("input[name='maintain_jieche_img']").val() == ""){
					$.messager.alert('登记失败','请上传维修接车单照片照片','error');
					return false;
				}	*/
   
				var data = form.serialize();
				$.ajax({
					type: 'post',
					url: "<?php echo yii::$app->urlManager->createUrl(['process/repair/maintain-add']); ?>",
					data: data,
					dataType: 'json',
					success: function(data){
                        $(button).linkbutton('enable');
						if(data.status){
							$.messager.alert('登记成功',data.info,'info');
							$('#easyui-dialog-process-repair-maintain-add').dialog('close');
							$('#easyui-datagrid-process-repair-maintain').datagrid('reload');
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
				$('#easyui-dialog-process-repair-maintain-add').dialog('close');
			}
		}],
        onClose: function(){
            $(this).dialog('clear');
        }
    });


	//修改
	$('#easyui-dialog-process-repair-maintain-edit').dialog({
    	title: '修改车辆维修登记',   
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
                var form = $('#easyui-form-process-repair-maintain-edit-from');
                if(!form.form('validate')) return false;
                /*
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
				if ($("input[name='fault_location_img']").val() == ""){
					$.messager.alert('登记失败','请上传故障位置照片','error');
					return false;
				}
				if ($("input[name='maintain_jieche_img']").val() == ""){
					$.messager.alert('登记失败','请上传维修接车单照片照片','error');
					return false;
				}*/

				var data = form.serialize();
				$.ajax({
					type: 'post',
					url: "<?php echo yii::$app->urlManager->createUrl(['process/repair/maintain-edit']); ?>",
					data: data,
					dataType: 'json',
					success: function(data){
						if(data.status){
							$.messager.alert('修改成功',data.info,'info');
							$('#easyui-dialog-process-repair-maintain-edit').dialog('close');
							$('#easyui-datagrid-process-repair-maintain').datagrid('reload');
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
				$('#easyui-dialog-process-repair-maintain-edit').dialog('close');
			}
		}],
        onClose: function(){
            $(this).dialog('clear');
        }
    });

	//维修结果登记
	$('#easyui-dialog-process-repair-maintain-reg').dialog({
    	title: '维修结果登记',   
        width: '750px',   
        height: '400px',   
        closed: true,   
        cache: true,   
        modal: true,
        resizable:true,
        maximizable: true,
        buttons: [{
			text:'确定',
			iconCls:'icon-ok',
			handler:function(){
                var form = $('#easyui-form-process-repair-maintain-reg-from');
                if(!form.form('validate')) return false;

                if ($("input[name='maintain_way']").val() == '进厂维修')
                {
                	if ($("input[name='leave_jieche_img']").val() == ""){
    					$.messager.alert('登记失败','请上传出厂接车单照片','error');
    					return false;
    				}
                }
                

                
				var data = form.serialize();
				$.ajax({
					type: 'post',
					url: "<?php echo yii::$app->urlManager->createUrl(['process/repair/maintain-reg']); ?>",
					data: data,
					dataType: 'json',
					success: function(data){
						if(data.status){
							$.messager.alert('登记结果成功',data.info,'info');
							$('#easyui-dialog-process-repair-maintain-reg').dialog('close');
							$('#easyui-datagrid-process-repair-maintain').datagrid('reload');
						}else{
							$.messager.alert('登记结果失败',data.info,'error');
						}
					}
				});
			}
		},{
			text:'取消',
			iconCls:'icon-cancel',
			handler:function(){
				$('#easyui-dialog-process-repair-maintain-reg').dialog('close');
			}
		}],
        onClose: function(){
            $(this).dialog('clear');
        }
    });

	
  	//初始查看工单详情
    $('#easyui-dialog-process-repair-maintain-info').window({
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
  //初始维修结果详情详情
    $('#easyui-dialog-process-repair-maintain-maintain-info').window({
        title: '查看维修详情',
    	width: 770,   
        height: 650,   
        modal: true,
        closed: true,
        collapsible: false,
        minimizable: false,
        maximizable: false,
        onClose: function(){
            $(this).window('clear');
        }                    
    });
    

    

    $('#easyui-dialog-process-repair-maintain-fault').dialog({
    	title: '登记车辆故障原因',   
        width: '830px',   
        height: '550px',   
        closed: true,   
        cache: true,   
        modal: true,
        resizable:true,
        maximizable: true,
        buttons: [{
			text:'确定',
			iconCls:'icon-ok',
			handler:function(){
                //var form = $('#easyui-form-process-repair-maintain-reg-from');
                //if(!form.form('validate')) return false;
				//var data = form.serialize();
				 var datagrid = $('#easyui-datagrid-process-repair-maintain');
       			 var selectRow = datagrid.datagrid('getSelected');
				var id = selectRow.id;
				$.ajax({
					type: 'post',
					url: "<?php echo yii::$app->urlManager->createUrl(['process/repair/fault-archive']); ?>",
					data: {id:id},
					dataType: 'json',
					success: function(data){
						if(data.status){
							$.messager.alert('故障原因归档成功',data.info,'info');
							$('#easyui-dialog-process-repair-maintain-fault').dialog('close');
							$('#easyui-datagrid-process-repair-maintain').datagrid('reload');
						}else{
							$.messager.alert('故障原因归档失败',data.info,'error');
						}
					}
				});
			}
		},{
			text:'取消',
			iconCls:'icon-cancel',
			handler:function(){
				$('#easyui-dialog-process-repair-maintain-fault').dialog('close');
			}
		}],
        onClose: function(){
            $(this).dialog('clear');
        }
    });

    
  	//执行
    ProcessRepairMaintain.init();
    //查询表单构建
    var searchForm = $('#search-form-process-repair-maintain');
    searchForm.submit(function(){
        var data = {};
        var searchCondition = $(this).serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        $('#easyui-datagrid-process-repair-maintain').datagrid('load',data);
        return false;
    });
    searchForm.find('input[name=type]').combobox({
        valueField:'value',
        textField:'text',
        editable: false,
        panelHeight:'auto',
        data: [{"value": '',"text": '不限'},{"value": 1,"text": '客户报修'},{"value": 2,"text": '保险事故'},{"value": 3,"text": '我方维修'}],
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
               {"value": 5,"text": '维修中'},
               {"value": 6,"text": '已修复'},
               {"value": 8,"text": '已结案'},
               ],
        onSelect: function(){
            searchForm.submit();
        }
    }); 
	
   
    //查询表单构建结束
    //获取选择的记录
    ProcessRepairMaintain.getSelected = function(){
        var datagrid = $('#easyui-datagrid-process-repair-maintain');
        var selectRow = datagrid.datagrid('getSelected');
        if(!selectRow){
            $.messager.alert('错误','请选择要操作的记录','error');   
            return false;
        }
        return selectRow;
    }
  	//车辆维修登记
    ProcessRepairMaintain.add = function(){
        $('#easyui-dialog-process-repair-maintain-add').dialog('open');
        $('#easyui-dialog-process-repair-maintain-add').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/repair/maintain-add']); ?>");
    }
  	//修改车辆维修登记
    ProcessRepairMaintain.edit = function(){
    	var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var id = selectRow.id;
		if(selectRow.status >5)
		{
			 $.messager.alert('错误','该工单维修结果已登记!','error');   
			 return false;
		}
        
        $('#easyui-dialog-process-repair-maintain-edit').dialog('open');
        $('#easyui-dialog-process-repair-maintain-edit').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/repair/maintain-edit']); ?>&id="+id);
    }
    
 	 //维修结果登记
    ProcessRepairMaintain.maintain_reg = function(){
    	var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        if(selectRow.status >5)
		{
			 $.messager.alert('错误','该故障已修复，无法修改故障信息!','error');   
			 return false;
		}
        var order_no = selectRow.order_no;
        var car_no = encodeURI(selectRow.plate_number);
        var maintain_way = encodeURI(selectRow.maintain_way);
        $('#easyui-dialog-process-repair-maintain-reg').dialog('open');
        $('#easyui-dialog-process-repair-maintain-reg').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/repair/maintain-reg']); ?>&order_no="+order_no+"&car_no="+car_no+"&maintain_way="+maintain_way);
    }
  	//查看维修结果详情
    ProcessRepairMaintain.maintain_info = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var order_no = selectRow.order_no;
        $('#easyui-dialog-process-repair-maintain-maintain-info').dialog('open');
        $('#easyui-dialog-process-repair-maintain-maintain-info').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/repair/maintain-info']); ?>&order_no="+order_no);
    }
  	//查看工单
    ProcessRepairMaintain.info = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var order_no = selectRow.order_no;
        var id = selectRow.id;
        $('#easyui-dialog-process-repair-maintain-info').dialog('open');
        $('#easyui-dialog-process-repair-maintain-info').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/repair/info']); ?>&order_no="+order_no+"&id="+id);
    }
    //导出
    ProcessRepairMaintain.export_excel = function(){
        var form = $('#search-form-process-repair-maintain');

        window.open("<?= yii::$app->urlManager->createUrl(['process/repair/export']); ?>&category=5&"+form.serialize());
    }
   

    //故障原因归类
    ProcessRepairMaintain.fault = function(){
    	var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        if(selectRow.status <6)
		{
			 $.messager.alert('错误','该车辆故障尚未修复，不能登记故障原因。','error');   
			 return false;
		}
        if(selectRow.status >6)
		{
			 $.messager.alert('错误','该故障已完结!','error');   
			 return false;
		}
        var id = selectRow.id;
        $('#easyui-dialog-process-repair-maintain-fault').dialog('open');
        $('#easyui-dialog-process-repair-maintain-fault').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/repair/fault']); ?>&id="+id);
    }



  //删除
	ProcessRepairMaintain.maintain_del = function(){
		var selectRow = this.getSelected();
        if(!selectRow) return false;

        if(selectRow.status >=6)
		{
			 $.messager.alert('错误','该故障已修复，不能删除!','error');   
			 return false;
		}
        var id = selectRow.id;
		$.messager.confirm('确定删除','您确定要删除该登记？',function(r){
			if(r){
				$.ajax({
					type: 'post',
					url: '<?php echo yii::$app->urlManager->createUrl(['process/repair/maintain-del']); ?>&id='+id,
					data: {id: id},
					dataType: 'json',
					success: function(data){
						if(data.status){
							$.messager.alert('删除成功',data.info,'info');   
							$('#easyui-datagrid-process-repair-maintain').datagrid('reload');
						}else{
							$.messager.alert('删除失败',data.info,'error');   
						}
					}
				});
			}
		});
	}
    
    //重置查询表单
    ProcessRepairMaintain.resetForm = function(){
        var easyuiForm = $('#search-form-process-repair-maintain');
        easyuiForm.form('reset');
    }
    
</script>