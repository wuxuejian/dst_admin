<table id="easyui-datagrid-process-car-rent"></table> 
<form  id="easyui-form-process-car-tiche-from" class="easyui-form" method="post">
<input type="text" name="id" value="<?php echo $result['id'];?>" /><!-- id -->
<input type="text" name="step_id" value="<?php echo $result['step_id'];?>" /><!-- step_id -->
<input type="text" name="template_id" value="<?php echo $result['template_id'];?>" /><!-- template_id -->
</form>

<div id="easyui-datagrid-process-car-rent-toolbar">

    <div class="easyui-panel" title="数据列表" style="padding:3px 2px;width:100%;" data-options="
        iconCls: 'icon-table-list',
        border: false
    ">
        <a href="javascript:ProcessCarRent.add();" class="easyui-linkbutton" data-options="iconCls:'icon-add'"><?php echo '登记'; ?></a>
    </div>
</div>
<!-- 窗口 -->
<div id="easyui-dialog-process-car-rent-add"></div>
<!-- 窗口 -->
<script>
    var ProcessCarRent = new Object();
    ProcessCarRent.init = function(){
        //获取列表数据process-config
        $('#easyui-datagrid-process-car-rent').datagrid({  
            method: 'POST', 
            url:"<?php echo yii::$app->urlManager->createUrl(['process/car/get-list']); ?>&id=<?php echo $result['id'] ?>&is_jiaoche=1&is_delivery=1",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-process-car-rent-toolbar",
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
                 {field: 'car_type',title: '品牌型号',width: 120,align: 'center',sortable: true},
                 {field: 'first_phase',title: '首期（天）',width: 120,align: 'center',sortable: true},
                 {field: 'first_phase_fee',title: '首期服务费（元）',width: 120,align: 'center',sortable: true},
                 {field: 'money_fee',title: '服务费（元/月）',width: 120,align: 'center',sortable: true},
                 {field: 'time_limit',title: '期限(月)',width: 120,align: 'center',sortable: true},
                 //{field: 'margin',title: '保证金',width: 120,align: 'center',sortable: true},
                 {field: 'last_stage_rent',title: '尾期租金',width: 120,align: 'center',sortable: true},
                 {field: 'start_time',title: '开始用车日期',width: 120,align: 'center',sortable: true},
                 {field: 'end_time',title: '车辆归还日期',width: 120,align: 'center',sortable: true},        
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
        });
    }

    
	//初始化登记窗口
	$('#easyui-dialog-process-car-rent-add').dialog({
    	title: '登记租金信息',   
        width: '500px',   
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
                var form = $('#easyui-form-process-car-rent-add-from');
                if(!form.form('validate')) return false;
				//var data = form.serialize();
				var data = new FormData($('#easyui-form-process-car-rent-add-from')[0]);  
				$.ajax({
					type: 'post',
					url: "<?php echo yii::$app->urlManager->createUrl(['process/car/add-rent']); ?>",
					data: data,
					dataType: 'json',
					cache: false,  
			        processData: false,  
			        contentType: false, 
					success: function(data){
						if(data.status){
							$.messager.alert('登记成功',data.info,'info');
							$('#easyui-dialog-process-car-rent-add').dialog('close');
							$('#easyui-datagrid-process-car-rent').datagrid('reload');
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
				$('#easyui-dialog-process-car-rent-add').dialog('close');
			}
		}],
        onClose: function(){
            $(this).dialog('clear');
        }
    });


  	//执行
    ProcessCarRent.init();
    //获取选择的记录
    ProcessCarRent.getSelected = function(){
        var datagrid = $('#easyui-datagrid-process-car-rent');
        var selectRow = datagrid.datagrid('getSelected');
        if(!selectRow){
            $.messager.alert('错误','请选择要操作的记录','error');   
            return false;
        }
        return selectRow;
    }

  //登记车辆租金
    ProcessCarRent.add = function(){
    	var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var id = selectRow.id;
		 $('#easyui-dialog-process-car-rent-add').dialog('open');
	     $('#easyui-dialog-process-car-rent-add').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/car/add-rent']); ?>&id="+id);
    }

</script>