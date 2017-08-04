<table id="easyui-datagrid-process-inspection-index"></table> 
<div id="easyui-datagrid-process-inspection-index-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-process-inspection-index">
                <ul class="search-main">
                	<li>
                        <div class="item-name">车辆品牌</div>
                        <div class="item-input">
                            <input style="width:200px;" name="car_brand_id" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">产品型号</div>
                        <div class="item-input">
                            <input style="width:200px;" name="car_model" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">审批状态</div>
                        <div class="item-input">
                            <input style="width:200px;" name="approve_status" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">审批结果</div>
                        <div class="item-input">
                            <input style="width:200px;" name="approve_result" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">验车时间</div>
                        <div class="item-input" style="width:320px">
                            <input class="easyui-datebox" type="text" name="start_validate_car_time" style="width:100px;"
                                   data-options=""
                                />
                            -
                            <input class="easyui-datebox" type="text" name="end_validate_car_time" style="width:100px;"
                                   data-options=""
                                />
                        </div>
                    </li>
                    <li class="search-button">
                        <a onclick="javascript:ProcessInspectionIndex.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                        <a onclick="javascript:ProcessInspectionIndex.reset()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</a>
                    </li>
                </ul>
            </form>
        </div>
    </div>
    <?php if(!empty($buttons)){ ?>
        <div class="easyui-panel" title="数据列表" style="padding:3px 2px;width:100%;" data-options="
        iconCls: 'icon-table-list',
        border: false
    ">
        <?php foreach($buttons as $val){ ?>
            <a onclick="<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon']; ?>'"><?= $val['text']; ?></a>
        <?php } ?>
        </div>
    <?php } ?>
</div>
<!-- 窗口 -->
<div id="easyui-datagrid-process-inspection-index-add"></div>
<div id="easyui-datagrid-process-inspection-index-edit"></div>
<div id="easyui-datagrid-process-inspection-index-approve"></div>
<div id="easyui-datagrid-process-inspection-index-detail"></div>
<!-- 窗口 -->
<script>
    // 配置数据
    var ProcessInspectionIndex_CONFIG = <?php echo json_encode($config); ?>;

    var ProcessInspectionIndex = new Object();
    ProcessInspectionIndex.init = function(){
        //获取列表数据
        $('#easyui-datagrid-process-inspection-index').datagrid({  
            method: 'get', 
            url:"<?php echo yii::$app->urlManager->createUrl(['process/inspection/get-list']); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-process-inspection-index-toolbar",
            pagination: true,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
            showFooter: true,
            frozenColumns: [[
                {field: 'ck',checkbox: true}, 
                {field: 'id',title: '检验批次编号',halign:'center',width: 120,sortable: true}
            ]],
            columns:[[
			    {field: 'approve_status',title: '审批状态',width: 60,align:'center',sortable: true,
                    formatter: function (value, row, index) {
                    	if(value == 1){
                    		return '<span style="background-color:#D3D3D3;color:#fff;padding:2px 5px;">待审批</span>';
 				        }else if(value == 2){
 				        	return '<span style="background-color:#FFCC01;color:#fff;padding:2px 5px;">已审批</span>';
 				        }else if(value == 3){
 				        	return '<span style="background-color:#05CD69;color:#fff;padding:2px 5px;">已确认</span>';
 				        }
                    }
                },
                {field: 'approve_result',title: '审批结果',width: 60,align:'center',sortable: true,
                    formatter: function (value, row, index) {
                    	if(value == 1){
                    		return '<span style="background-color:#05CD69;color:#fff;padding:2px 5px;">合格</span>';
 				        }else if(value == 2){
 				        	return '<span style="background-color:#FF4040;color:#fff;padding:2px 5px;">不合格</span>';
 				        }else {
 	 				        return '-';
 				        }
                    }
                },
                {field: 'approve_note',title: '审批意见',width: 150,align:'center',sortable: true,
                    formatter: function (value, row, index) {
                    	return value;
                    }
                },
                {field: 'car_brand',title: '车辆品牌',width: 100,align:'center',
                    formatter: function (value, row, index) {
                    	return value;
                    }
                },
                {field: 'car_model',title: '产品型号',width: 110,align:'center',sortable: true,
                    formatter: function (value, row, index) {
                    	return value;
                    }
                },
                {field: 'put_car_num',title: '计划提车数量',width: 80,align:'center',sortable: true},
                {field: 'inspection_num',title: '抽检数量',width: 60,align:'center',sortable: true},
                {field: 'inspection_director_name',title: '抽检负责人',width: 100,align:'center',sortable: true},
                {field: 'validate_car_time',title: '验车时间',width: 80,align:'center',sortable: true,
                	formatter: function (value, row, index) {
                    	return value.substring(0,10);
                    }
                },
                {field: 'add_time',title: '登记时间',width: 120,align:'center',sortable: true},
                {field: 'oper_user',title: '登记人',width: 90,align:'center',sortable: true}
            ]],
            //双击
            onDblClickRow: function(rowIndex,rowData){
                //ProcessInspectionIndex.edit(rowData.id);
            },
            onLoadSuccess: function (data) {
                //单元格内容悬浮提示，doCellTip()是在入口文件index.php中拓展的。
                $(this).datagrid('doCellTip', {
                    position: 'bottom',
                    maxWidth: '200px',
                    onlyShowInterrupt: true, //false时所有单元格都显示提示；true时配合specialShowFields自定义要提示的列
                    specialShowFields: [     //需要提示的列
                        //{field: 'company_name', showField: 'company_name'}
                    ],
                    tipStyler: {
                        backgroundColor: '#E4F0FC',
                        borderColor: '#87A9D0',
                        boxShadow: '1px 1px 3px #292929'
                    }
                });
            }
        });

      	//构建查询表单
        var searchForm = $('#search-form-process-inspection-index');
        /**查询表单提交事件**/
        searchForm.submit(function(){
            var data = {};
            var searchCondition = $(this).serializeArray();
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = searchCondition[i]['value'];
            }
            $('#easyui-datagrid-process-inspection-index').datagrid('load',data);
            return false;
        });
        searchForm.find('input[name=car_brand_id]').combotree({
            url: "<?php echo yii::$app->urlManager->createUrl(['car/combotree/get-car-brands']); ?>",
            editable: false,
            panelHeight:'auto',
            lines:false,
            onChange: function(o){
                searchForm.submit();
            }
        });
        searchForm.find('input[name=car_model]').combobox({
            valueField:'value',
            textField:'text',
            data: <?= json_encode($searchFormOptions['car_model_name']); ?>,
            editable: false,
            panelHeight:'auto',
            onSelect: function(){
                searchForm.submit();
            }
        });
        searchForm.find('input[name=approve_status]').combobox({
            valueField:'value',
            textField:'text',
            editable: false,
            panelHeight:'auto',
            data: [{"value": '',"text": '不限'},{"value": 1,"text": '待审批'},{"value": 2,"text": '已审批'},{"value": 3,"text": '已确认'}],
            onSelect: function(){
                searchForm.submit();
            }
        });
        searchForm.find('input[name=approve_result]').combobox({
            valueField:'value',
            textField:'text',
            editable: false,
            panelHeight:'auto',
            data: [{"value": '',"text": '不限'},{"value": 1,"text": '合格'},{"value": 2,"text": '不合格'}],
            onSelect: function(){
                searchForm.submit();
            }
        });
        
        //初始化新增合同窗口
        $('#easyui-datagrid-process-inspection-index-add').dialog({
            title: '&nbsp;抽检结果登记',
            iconCls:'icon-add', 
            width: '80%',   
            height: '90%',   
            closed: true,   
            cache: true,   
            modal: true,
            maximizable: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    //回调添加页面submitForm方法
                    ProcessInspectionAdd.submitForm();
                }
            },{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui-datagrid-process-inspection-index-add').dialog('close');
                }
            }],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
        //初始化修改登记窗口
        $('#easyui-datagrid-process-inspection-index-edit').dialog({
            title: '&nbsp;修改抽检结果登记', 
            iconCls:'icon-edit',
            width: '80%',   
            height: '90%',   
            closed: true,   
            cache: true,   
            modal: true,
            maximizable: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var form = $('#easyui-form-process-inspection-index-edit');
                    if(!form.form('validate')){
                        return false;
                    }
                    var data = form.serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['process/inspection/edit']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('修改成功',data.info,'info');
                                $('#easyui-datagrid-process-inspection-index-edit').dialog('close');
                                $('#easyui-datagrid-process-inspection-index').datagrid('reload');
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
                    $('#easyui-datagrid-process-inspection-index-edit').dialog('close');
                }
            }],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
      //初始化查看窗口
        $('#easyui-datagrid-process-inspection-index-detail').dialog({
            title: '&nbsp;查看车辆抽检结果', 
            iconCls:'icon-search',
            width: '750px',   
            height: '500px',   
            closed: true,   
            cache: true,   
            modal: true,
            maximizable: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                	$('#easyui-datagrid-process-inspection-index-detail').dialog('close');
                }
            }],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
        
      //初始化审批窗口
        $('#easyui-datagrid-process-inspection-index-approve').dialog({
            title: '&nbsp;车辆抽检结果审批', 
            iconCls:'icon-edit',
            width: '550px',   
            height: '200px',   
            closed: true,   
            cache: true,   
            modal: true,
            maximizable: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var form = $('#easyui-form-process-inspection-index-approve');
                    if(!form.form('validate')){
                        return false;
                    }
                    var data = form.serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['process/inspection/approve']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('审批成功',data.info,'info');
                                $('#easyui-datagrid-process-inspection-index-approve').dialog('close');
                                $('#easyui-datagrid-process-inspection-index').datagrid('reload');
                            }else{
                                $.messager.alert('审批失败',data.info,'error');
                            }
                        }
                    });
                }
            },{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui-datagrid-process-inspection-index-approve').dialog('close');
                }
            }],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
    }
    //获取选择的记录
    ProcessInspectionIndex.getSelected = function(){
        var datagrid = $('#easyui-datagrid-process-inspection-index');
        var selectRow = datagrid.datagrid('getSelected');
        if(!selectRow){
            $.messager.alert('错误','请选择要操作的记录','error');   
            return false;
        }
        return selectRow;
    }
  //确认
	ProcessInspectionIndex.confirm = function(){
		var selectRow = this.getSelected();
        if(!selectRow) return false;
        if(selectRow.approve_status != 2){
        	$.messager.alert('操作失败','该项目未审批或者已确认，无法进行确认。','error');   
        	return false;
        }
        var id = selectRow.id;
		$.messager.confirm('车辆抽检审批结果确认','请确认你已了解该批次新提车辆的抽检结果？',function(r){
			if(r){
				$.ajax({
					type: 'get',
					url: '<?php echo yii::$app->urlManager->createUrl(['process/inspection/confirm']); ?>',
					data: {id: id},
					dataType: 'json',
					success: function(data){
						if(data.status){
							$.messager.alert('操作成功',data.info,'info');
							$('#easyui-datagrid-process-inspection-index').datagrid('reload');
						}else{
							$.messager.alert('操作失败',data.info,'error');   
						}
					}
				});
			}
		});
	}
	//查看
    ProcessInspectionIndex.detail = function(id){
    	if(!id){
            var selectRow = this.getSelected();
            if(!selectRow)  return false;
            id = selectRow.id;
        }
    	$('#easyui-datagrid-process-inspection-index-detail').dialog('open');
        $('#easyui-datagrid-process-inspection-index-detail').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/inspection/detail']); ?>&id="+id);
    }
    //审批
    ProcessInspectionIndex.approve = function(id){
    	
            var selectRow = this.getSelected();
            if(!selectRow)  return false;
            id = selectRow.id;
            if(selectRow.approve_status != 1){
            	$.messager.alert('操作失败','该项目已完成审批或者确认，无法进行更改。','error');   
            	return false;
            }
        
    	$('#easyui-datagrid-process-inspection-index-approve').dialog('open');
        $('#easyui-datagrid-process-inspection-index-approve').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/inspection/approve']); ?>&id="+id);
    }
    //新建登记
    ProcessInspectionIndex.add = function(){
        $('#easyui-datagrid-process-inspection-index-add').dialog('open');
        $('#easyui-datagrid-process-inspection-index-add').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/inspection/add']); ?>");
    }
    //修改登记
    ProcessInspectionIndex.edit = function(id){
            var selectRow = this.getSelected();
            if(!selectRow)  return false;
            id = selectRow.id;
            if(selectRow.approve_status != 1){
            	$.messager.alert('操作失败','该项目已完成审批或者确认，无法进行更改。','error');   
            	return false;
            }
        
        $('#easyui-datagrid-process-inspection-index-edit').dialog('open');
        $('#easyui-datagrid-process-inspection-index-edit').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/inspection/edit']); ?>&id="+id);
    }
    //导出
    ProcessInspectionIndex.exportWidthCondition = function(){
        var form = $('#search-form-process-inspection-index');
        window.open("<?= yii::$app->urlManager->createUrl(['process/inspection/export-width-condition']); ?>&"+form.serialize());
    }
    
    //查询
    ProcessInspectionIndex.search = function(){
        var form = $('#search-form-process-inspection-index');
        var data = {};
        var searchCondition = form.serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        $('#easyui-datagrid-process-inspection-index').datagrid('load',data);
    }
    //重置
    ProcessInspectionIndex.reset = function(){
        $('#search-form-process-inspection-index').form('reset');
    }
    //执行
    ProcessInspectionIndex.init();
</script>