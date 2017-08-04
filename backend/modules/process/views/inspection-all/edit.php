<table id="easyui-datagrid-process-inspection-all-edit"></table>
<!-- toolbar start -->
<div id="process-inspection-all-edit-toolbar"> 
    <form id="easyui-form-process-inspection-all-index-edit" class="easyui-form" method="post">
        <input type="hidden" name="id" />
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
        <div class="easyui-panel" title="登记车辆" style="padding:3px 2px;width:100%;" iconCls='icon-save' border="false">
                <a href="javascript:ProcessInspectionAllEdit.addCar()" class="easyui-linkbutton" data-options="iconCls:'icon-add'">添加</a>
                <a href="javascript:ProcessInspectionAllEdit.editCar()" class="easyui-linkbutton" data-options="iconCls:'icon-add'">批量修改</a>
                <a href="javascript:ProcessInspectionAllEdit.saveEdit()" class="easyui-linkbutton" data-options="iconCls:'icon-remove'">保存添加/修改</a>
        </div>
    </form>
</div>
<!-- toolbar end -->
<form style="display:none;" id="process-inspection-all-edit-submit-data"></form>
<script>
    var ProcessInspectionAllEdit = new Object();
    ProcessInspectionAllEdit.init = function(){
        var car_data = <?=json_encode($car)?>;
        var inspection_result_data = <?=json_encode(array(array('id'=>'1','inspection_result'=>'合格'),array('id'=>'2','inspection_result'=>'不合格')))?>;
        var is_put_data = <?=json_encode(array(array('id'=>'1','is_put'=>'已提车'),array('id'=>'2','is_put'=>'未提车')))?>;
        //初始化datagrid
        $('#easyui-datagrid-process-inspection-all-edit').datagrid({
            method: 'get', 
            url:"<?php echo yii::$app->urlManager->createUrl(['process/inspection-all/get-car-list','inspection_id'=>$inspection_id]); ?>",  
            toolbar: "#process-inspection-all-edit-toolbar",
            border: false,
            fit: true,
            pagination: true,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: false,
            showFooter: true,
            frozenColumns: [[
                {field: 'ck',checkbox: true}, 
                {field: 'id',title: 'id',hidden: true},   
                {
                    field: 'vehicle_dentification_number',title: '车架号',width: '15%',sortable: true,
                    editor:{
                        type:'combobox',
                        options:{
                            valueField:'vehicle_dentification_number',
                            textField:'vehicle_dentification_number',
                            data: car_data,
                            required: true
                        }
                    }
                }
            ]],
            columns:[[
				{
				    
				    field: 'inspection_result',title:'检验结果',width: '15%',
				    editor:{
				    	type:'combobox',
				        options:{
				            valueField:'id',
				            textField:'inspection_result',
				            data: inspection_result_data,
				            required: true
				        }
				    },
					formatter:function(value,row,index){
						for (var i = 0; i < inspection_result_data.length; i++) {
							if (inspection_result_data[i].id == value) {
								return inspection_result_data[i].inspection_result;
							}
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
				            data: is_put_data,
				            required: true
				        }
				    },
					formatter:function(value,row,index){
						for (var i = 0; i < is_put_data.length; i++) {
							if (is_put_data[i].id == value) {
								return is_put_data[i].is_put;
							}
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

        //表单赋值
        var oldData = <?php echo json_encode($inspection_all); ?>;
        setTimeout(function(){
        	$('#easyui-form-process-inspection-all-index-edit').form('load',oldData);
        },1000);

    }
    ProcessInspectionAllEdit.init();
    //获取选择记录
    ProcessInspectionAllEdit.getSelected = function(multiple){
        var datagrid = $('#easyui-datagrid-process-inspection-all-edit');
        if(multiple){
            selectRows = datagrid.datagrid('getSelections');
            if(selectRows.length <= 0){
                $.messager.alert('错误','请选择要操作的记录！','error');   
                return false;
            }
            return selectRows;
        }else{
            var selectRow = datagrid.datagrid('getSelected');
            if(!selectRow){
                $.messager.alert('错误','请选择要操作的记录！','error');   
                return false;
            }
            return selectRow;
        }  
    }
    //登记车辆
    ProcessInspectionAllEdit.addCar = function(){
        var datagrid = $('#easyui-datagrid-process-inspection-all-edit');
        datagrid.datagrid('appendRow',{       
            id: '0',
            vehicle_dentification_number: '',
            inspection_result: '',
            is_put: '',
            note: ''
        });
        var rows = datagrid.datagrid('getRows');
        var lastRowNum = rows.length - 1;
        var lastRow = rows[lastRowNum];
        var rowIndex = datagrid.datagrid('getRowIndex',lastRow);
        datagrid.datagrid('beginEdit',rowIndex);
        datagrid.datagrid('selectRow',rowIndex);
    }
    //修改登记车辆
    ProcessInspectionAllEdit.editCar = function(){
        var selectRows = this.getSelected(true);
        if(!selectRows) return false;
        var datagrid = $('#easyui-datagrid-process-inspection-all-edit');
        for(var i in selectRows){
            var rowIndex = datagrid.datagrid('getRowIndex',selectRows[i]);
            datagrid.datagrid('beginEdit',rowIndex);
        }
    }
    //保存修改
    ProcessInspectionAllEdit.saveEdit = function()
    {
        var selectRows = this.getSelected(true);
        if(!selectRows) return false;
        var datagrid = $('#easyui-datagrid-process-inspection-all-edit');
        
        for(var i in selectRows){
            datagrid.datagrid('endEdit',datagrid.datagrid('getRowIndex',selectRows[i]));
        }
        var selectRows = this.getSelected(true);
        var html = '<input type="text" name="inspection_id" value="<?php echo $inspection_all["id"]; ?>" />';
        for(var i in selectRows){
            if(selectRows[i].vehicle_dentification_number){
                html += '<input type="text" name="id[]" value="'+selectRows[i].id+'" />';
                html += '<input type="text" name="vehicle_dentification_number[]" value="'+selectRows[i].vehicle_dentification_number+'" />';
                html += '<input type="text" name="is_put[]" value="'+selectRows[i].is_put+'" />';
                html += '<input type="text" name="inspection_result[]" value="'+selectRows[i].inspection_result+'" />';
                html += '<input type="text" name="car_note[]" value="'+selectRows[i].car_note+'" />';
            }
        }
        var form = $('#process-inspection-all-edit-submit-data');
        form.html(html);
        var data = form.serialize();
        $.ajax({
            type: 'post',
            url: "<?php echo yii::$app->urlManager->createUrl(['process/inspection-all/add-edit-car']); ?>",
            data: data,
            dataType: 'json',
            success: function(data){
                if(data.status){
                    $.messager.alert('操作成功',data.info,'info');
                    $('#easyui-datagrid-process-inspection-all-edit').datagrid('reload');
                }else{
                    $.messager.alert('操作失败',data.info,'error');
                }
            }
        });
    }
</script>