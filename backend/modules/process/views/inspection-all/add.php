<table id="easyui-datagrid-process-inspection-all-add"></table>
<!-- toolbar start -->
<div id="process-inspection-all-add-toolbar">
    <form id="easyui-form-process-inspection-all-index-add" class="easyui-form" method="post">
        <div
            class="easyui-panel"
            title="全检结果登记"    
            iconCls='icon-save'
            border="false"
            style="width:100%;"
        >
            <ul class="ulforform-resizeable">
            	<li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">计划提车数量</div>
                    <div class="ulforform-resizeable-input">
                        <input
                            class="easyui-textbox"
                            style="width:160px;"
                            name="car_num"
                            required="true"
                            missingMessage="请输入计划提车数量！"
                        />
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">实际提车数量</div>
                    <div class="ulforform-resizeable-input">
                        <input
                            class="easyui-textbox"
                            style="width:160px;"
                            name="real_car_num"
                            required="true"
                            missingMessage="请输入实际提车数量！"
                        />
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">车辆品牌</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-combotree" name="car_brand_id"
                           data-options="
                                width:160,
                                url: '<?php echo yii::$app->urlManager->createUrl(['car/combotree/get-car-brands']); ?>',
                                editable: false,
                                panelHeight:'auto',
                                lines:false,
                                required:true
                           "
                        />
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                	<div class="ulforform-resizeable-title">产品型号</div>
					<div class="ulforform-resizeable-input">
						<select
	                        class="easyui-combobox"
	                        style="width:160px;"
	                        name="car_model"
							editable="false"
							data-options="panelHeight:'auto',required:true"
	                    >
	                    	<option value=""></option>
	                        <?php foreach($config['car_model_name'] as $val){ ?>
	                        <option value="<?php echo $val['value']; ?>"><?php echo $val['value']; ?></option>
	                        <?php } ?>
	                    </select>
					</div>
                 </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">验车负责人</div>
                    <div class="ulforform-resizeable-input">
                        <input
                            class="easyui-textbox"
                            style="width:160px;"
                            name="inspection_director_name"
                            required="true"
                            missingMessage="请输入验车负责人！"
                        />
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">验车日期</div>
                    <div class="ulforform-resizeable-input">
                    	<input
	                        class="easyui-datebox"
	                        style="width:100px;"
	                        name="validate_car_time"
	                        validType="date"
	                        required="true"
	                    />
                    </div>
                </li>
                <li class="ulforform-resizeable-group-single">
                    <div class="ulforform-resizeable-title">备注</div>
                    <div class="ulforform-resizeable-input">
                        <input
                        class="easyui-textbox"
                        name='note'
                        data-options="multiline:true"
                        style="height:60px;width:800px;"
                        required="true"
                        prompt=""
                        validType="length[500]"
                        />
                    </div>
                </li>
            </ul>
        </div>
        <div style="border-top:1px solid #95B8E7;"></div>
        <div
            class="easyui-panel"
            title="登记车辆"    
            iconCls='icon-save'
            border="false"
            style="width:100%;"
        >
            <div style="padding:4px;">
                <a href="javascript:ProcessInspectionAllAdd.addCar()" class="easyui-linkbutton" data-options="iconCls:'icon-add'">添加</a>
                <a href="javascript:ProcessInspectionAllAdd.removeCar()" class="easyui-linkbutton" data-options="iconCls:'icon-remove'">删除</a>
            </div>
        </div>
        <div style="display:none" id="process-inspection-all-add-car-item"></div>
    </form>
</div>
<!-- toolbar end -->
<!-- 窗口 -->
<div id="process-inspection-all-add-customer"></div>
<!-- 窗口 -->
<script>
    var ProcessInspectionAllAdd = new Object();
    ProcessInspectionAllAdd.init = function(){
        //初始化datagrid
        $('#easyui-datagrid-process-inspection-all-add').datagrid({
            fit: true,
            border: false,
            singleSelect: true,
            rownumbers: true,
            toolbar: '#process-inspection-all-add-toolbar',
            columns:[[
                {field: 'ck',checkbox: true},
                {
                    
                    field: 'vehicle_dentification_number',title:'车架号',width: '15%',
                    editor:{
                        type:'combobox',
                        options:{
                            valueField:'vehicle_dentification_number',
                            textField:'vehicle_dentification_number',
                            data: <?php echo json_encode($car); ?>,
                            required: true
                        }
                    }
                }, 
				{
                    
                    field: 'inspection_result',title:'检验结果',width: '15%',
                    editor:{
                    	type:'combobox',
                        options:{
                            valueField:'id',
                            textField:'inspection_result',
                            data: <?php echo json_encode(array(array('id'=>'1','inspection_result'=>'合格'),array('id'=>'2','inspection_result'=>'不合格'))); ?>,
                            required: true
                        }
                    }
                }, 
				{
                    
                    field: 'is_put',title:'提车',width: '15%',
                    editor:{
                    	type:'combobox',
                        options:{
                            valueField:'id',
                            textField:'is_put',
                            data: <?php echo json_encode(array(array('id'=>'1','is_put'=>'已提车'),array('id'=>'2','is_put'=>'未提车'))); ?>,
                            required: true
                        }
                    }
                }, 
                {
                    field:'car_note',title:'备注',width: '85%',align:'left',
                    editor:{
                        type:'textbox',
                        options:{
                            validType: 'length[255]',
                            prompt: '如车辆检验不合格，请注明原因及处理方法'
                        }
                    }
                }   
            ]],
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
    }
    ProcessInspectionAllAdd.init();
    //获取选中记录
    ProcessInspectionAllAdd.getSelectedRow = function(){
        var datagrid = $('#easyui-datagrid-process-inspection-all-add');
        var selectedRow = datagrid.datagrid('getSelected');
        if(!selectedRow){
            $.messager.alert('错误','请选择要操作的记录！','error');
            return false;
        }
        return selectedRow;
    }
    //添加车辆
    ProcessInspectionAllAdd.addCar = function(){
        var datagrid = $('#easyui-datagrid-process-inspection-all-add');
        var data = datagrid.datagrid('getData');
        var rowsNum = data.total;
        datagrid.datagrid('appendRow',{
            'vehicle_dentification_number': '',
            'inspection_result': '',
            'is_put': '',
            'note': ''
        });
        datagrid.datagrid('beginEdit',rowsNum);
    }
    //删除车辆
    ProcessInspectionAllAdd.removeCar = function(){
        var selectedRow = this.getSelectedRow();
        if(!selectedRow) return false;
        var datagrid = $('#easyui-datagrid-process-inspection-all-add');
        var rowIndex = datagrid.datagrid('getRowIndex',selectedRow);
        datagrid.datagrid('deleteRow',rowIndex);
    }
    //提交表单
    ProcessInspectionAllAdd.submitForm = function(){
        var form = $('#easyui-form-process-inspection-all-index-add');
        if(!form.form('validate')){
            return false;
        }
        var datagrid = $('#easyui-datagrid-process-inspection-all-add');
        var carData = datagrid.datagrid('getData');
        var carRowNum = carData.total;
        var carHtml = '';
        for(var i=0; i<carRowNum;i++){
            datagrid.datagrid('endEdit',i);
            if(carData.rows[i].vehicle_dentification_number != ''){
                carHtml += '<input type="text" name="vehicle_dentification_number[]" value="'+carData.rows[i].vehicle_dentification_number+'" />';
                carHtml += '<input type="text" name="is_put[]" value="'+carData.rows[i].is_put+'" />';
                carHtml += '<input type="text" name="inspection_result[]" value="'+carData.rows[i].inspection_result+'" />';
                carHtml += '<input type="text" name="car_note[]" value="'+carData.rows[i].car_note+'" />';
            }
        }
        $('#process-inspection-all-add-car-item').html(carHtml);
        var data = form.serialize();
        $.ajax({
            type: 'post',
            url: "<?php echo yii::$app->urlManager->createUrl(['process/inspection-all/add']); ?>",
            data: data,
            dataType: 'json',
            success: function(data){
                if(data.status){
                    $.messager.alert('新建成功',data.info,'info');
                    $('#easyui-datagrid-process-inspection-all-index-add').dialog('close');
                    $('#easyui-datagrid-process-inspection-all-index').datagrid('reload');
                }else{
                    $.messager.alert('新建失败',data.info,'error');
                }
            }
        });
    }
</script>