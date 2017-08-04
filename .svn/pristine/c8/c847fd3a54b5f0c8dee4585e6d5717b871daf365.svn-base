<table id="CustomerCarTrialProtocolIndex_editWindow_carsDatagrid"></table>
<!-- toolbar start -->
<div id="CustomerCarTrialProtocolIndex_editWindow_carsDatagridToolbar">
    <form id="CustomerCarTrialProtocolIndex_editWindow_baseInfoForm" class="easyui-form" method="post">
	    <input type="hidden" name="ctp_id" />
		<input type="hidden" name="ctp_customer_type" value="COMPANY" />
        <div
            class="easyui-panel"
            title="协议基本信息"    
            iconCls='icon-script-edit'
            border="false"
            style="width:100%;"
        >
            <ul class="ulforform-resizeable">
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">协议编号</div>
                    <div class="ulforform-resizeable-input">
                        <input
                            class="easyui-textbox"
                            style="width:160px;"
                            name="ctp_number"
                            required="true"
                            missingMessage="请填写协议编号！"
                        />
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">试用客户</div>
                    <div class="ulforform-resizeable-input">
						<input id="CustomerCarTrialProtocolIndex_editWindow_chooseCustomer" name="ctp_cCustomer_id"  style="width:160px" />
                        <a href="javascript:CustomerCarTrialProtocolIndex_editWindow.addCustomer()" class="easyui-linkbutton" data-options="iconCls:'icon-add'" title="新增客户"></a>
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">试用车数量</div>
                    <div class="ulforform-resizeable-input">
                        <input
                            class="easyui-numberbox"
                            style="width:160px;"
                            name="ctp_car_nums" 
							id="CustomerCarTrialProtocolIndex_editWindow_ctp_car_nums"
                            editable="false"
                            value="0"
                        >
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">签订日期</div>
                    <div class="ulforform-resizeable-input">
                        <input
                            class="easyui-datebox"
                            style="width:160px;"
                            name="ctp_sign_date"
                            required="true"
                            missingMessage="请选择协议签订日期！"
                            value="<?php echo date('Y-m-d'); ?>"
                            validType="date"
                        >
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">开始时间</div>
                    <div class="ulforform-resizeable-input">
                        <input
                            class="easyui-datebox"
                            style="width:160px;"
                            name="ctp_start_date"
                            required="true"
                            missingMessage="请选择开始时间！"
                            validType="date"
                        >
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">结束时间</div>
                    <div class="ulforform-resizeable-input">
                        <input
                            class="easyui-datebox"
                            style="width:160px;"
                            name="ctp_end_date"
                            required="true"
                            missingMessage="请选择结束时间！"
                            validType="date"
                        >
                    </div>
                </li>
                <li class="ulforform-resizeable-group-single">
                    <div class="ulforform-resizeable-title">备注</div>
                    <div class="ulforform-resizeable-input">
                        <input 
                            class="easyui-textbox"
                            name="ctp_note"
                            data-options="multiline:true"
                            style="height:40px;width:765px;"
                        />
                    </div>
                </li>
            </ul>
        </div>
        <div style="border-top:1px solid #95B8E7;"></div>
        <div
            class="easyui-panel"
            title="试用车辆明细"    
            iconCls='icon-car'
            border="false"
            style="width:100%;"
        >
            <div style="padding:4px;">
                <a href="javascript:CustomerCarTrialProtocolIndex_editWindow.addCar()" class="easyui-linkbutton" data-options="iconCls:'icon-add'">添加</a>
                <a href="javascript:CustomerCarTrialProtocolIndex_editWindow.editCar()" class="easyui-linkbutton" data-options="iconCls:'icon-edit'">修改</a>
                <a href="javascript:CustomerCarTrialProtocolIndex_editWindow.removeCar()" class="easyui-linkbutton" data-options="iconCls:'icon-remove'">删除</a>
				<!-- <a href="javascript:CustomerCarTrialProtocolIndex_editWindow.endEdit()" class="easyui-linkbutton" data-options="iconCls:'icon-save'">完成编辑</a> -->
                <a href="javascript:CustomerCarTrialProtocolIndex_editWindow.saveCars()" class="easyui-linkbutton" data-options="iconCls:'icon-save'">确定保存车辆</a>
                <a href="javascript:CustomerCarTrialProtocolIndex_editWindow.backCar()" class="easyui-linkbutton" data-options="iconCls:'icon-car_rarrow'">归还车辆</a>
            </div>
        </div>
    </form>
</div>
<!-- toolbar end -->
<!-- 窗口 -->
<div id="CustomerCarTrialProtocolIndex_editWindow_addCustomerWindow"></div>
<!-- 窗口 -->
<script>
	// 相关数据
	var ctpEdit_initDatas = <?php echo json_encode($initDatas); ?>;  
	var ctpEdit_ctp_id = ctpEdit_initDatas.carTrialProtocol.ctp_id;  
	
	// 请求的URL
	var CustomerCarTrialProtocolIndex_editWindow_URL_getProtocolDetails = "<?php echo yii::$app->urlManager->createUrl(['customer/car-trial-protocol/get-protocol-details']); ?>";
	var CustomerCarTrialProtocolIndex_editWindow_URL_ChooseCustomer = "<?php echo yii::$app->urlManager->createUrl(['customer/combogrid/get-company-customer-list']); ?>";
	var CustomerCarTrialProtocolIndex_editWindow_URL_addCustomer = "<?php echo yii::$app->urlManager->createUrl(['customer/company/add']); ?>";
    var CustomerCarTrialProtocolIndex_editWindow_URL_backCar = "<?php echo yii::$app->urlManager->createUrl(['customer/car-trial-protocol/back-car']); ?>";
    var CustomerCarTrialProtocolIndex_editWindow_URL_saveCars = "<?php echo yii::$app->urlManager->createUrl(['customer/car-trial-protocol/save-cars']); ?>";

    var CustomerCarTrialProtocolIndex_editWindow = {
		// 初始化页面
		init: function(){
			// 初始化datagrid
			$('#CustomerCarTrialProtocolIndex_editWindow_carsDatagrid').datagrid({
				method: 'get',
				url: CustomerCarTrialProtocolIndex_editWindow_URL_getProtocolDetails + '&ctp_id=' + ctpEdit_ctp_id, 
				toolbar: '#CustomerCarTrialProtocolIndex_editWindow_carsDatagridToolbar',
				fit: true,
				border: false,
				singleSelect: false,
				rownumbers: true,
				pagination: true,
				pageSize: 50,
				columns:[[
					{field: 'ck',checkbox: true},
					{field: 'ctpd_id',title:'明细ID',width: '50px',hidden:true},
					{field: 'plate_number',title: '车牌号',width: '100px',align:'center',sortable:true,
						editor:{
							type:'combobox',
							options:{
								valueField:'plate_number',
								textField:'plate_number',
								data: ctpEdit_initDatas.cars,
								required: true,
								panelHeight:'auto'
							}
						}
					},   
					{field:'ctpd_deliver_date',title:'交车时间',width: '130px',align:'center',sortable:true,
						editor:{
							type:'datebox',
							options:{
								validType: 'date',
								required: true
							}
						}
					},   
					{field:'ctpd_back_date',title:'还车时间',width: '130px',align:'center',sortable:true},   
					{field:'ctpd_note',title:'备注',width: '300px',halign:'center',
						editor:{
							type:'textbox',
							options:{
								validType: 'length[255]'
							}
						}
					}   
				]]
			});
			
			// 初始化选择试用客户combogrid
			$('#CustomerCarTrialProtocolIndex_editWindow_chooseCustomer').combogrid({
				panelWidth: 500,				
				panelHeight: 210,
				required: true,
				missingMessage: '请从下拉列表里选择客户！',
				onHidePanel:function(){
					var _combogrid = $(this);
					var value = _combogrid.combogrid('getValue');
					var textbox = _combogrid.combogrid('textbox');
					var text = textbox.val();
					var rows = _combogrid.combogrid('grid').datagrid('getSelections');
					if(text && rows.length < 1 && value == text){										
						$.messager.show(
							{
								title: '无效客户',
								msg:'【' + text + '】不是有效客户！请重新检索并选择一个客户！'
							}
						);
						_combogrid.combogrid('clear');
					}
				},
				delay: 800,
				mode:'remote',
				idField: 'customer_id',
				textField: 'customer_name',
				url: CustomerCarTrialProtocolIndex_editWindow_URL_ChooseCustomer,
				method: 'get',
				scrollbarSize:0, 
				pagination: true,
				pageSize: 10,
				pageList: [10,20,30],
				fitColumns: true,
				columns: [[
					{field:'customer_id',title:'ID',width:20,align:'center'},
					{field:'customer_name',title:'企业客户名称',width:150,halign:'center'},
					{field:'customer_address',title:'客户地址',width:150,halign:'center'}
				]]
			});
			
			// 初始化添加新客户窗口
			$('#CustomerCarTrialProtocolIndex_editWindow_addCustomerWindow').dialog({
				title: '添加企业客户',   
				width: '900px',   
				height: '500px',   
				closed: true,   
				cache: true,   
				modal: true,
				buttons: [{
					text:'确定',
					iconCls:'icon-ok',
					handler:function(){
						var form = $('#easyui-form-customer-company-add');
						if(!form.form('validate')){
							return false;
						}
						var data = form.serialize();
						$.ajax({
							type: 'post',
							url: CustomerCarTrialProtocolIndex_editWindow_URL_addCustomer,
							data: data,
							dataType: 'json',
							success: function(data){
								if(data.status){
									$.messager.show({
                                        title: '添加成功',
                                        msg: data.info
                                    });
									$('#CustomerCarTrialProtocolIndex_editWindow_addCustomerWindow').dialog('close');
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
						$('#CustomerCarTrialProtocolIndex_editWindow_addCustomerWindow').dialog('close');
					}
				}],
				onClose:function(){  // 关闭窗口时清除可避免id重复问题
					$(this).dialog('clear');
				}
			});		
		}, 
		// 添加客户
		addCustomer: function(){
			$('#CustomerCarTrialProtocolIndex_editWindow_addCustomerWindow')
				.dialog('open')
				.dialog('refresh',CustomerCarTrialProtocolIndex_editWindow_URL_addCustomer);
		},
        //获取当前所选择的记录
        getCurrentSelected: function(multiline){
            var datagrid = $('#CustomerCarTrialProtocolIndex_editWindow_carsDatagrid');
            var selectRows = datagrid.datagrid('getSelections');
            if(selectRows.length <= 0){
                $.messager.show({
                    title: '请选择',
                    msg: '请先选择要操作的记录！'
                });
                return false;
            }
            if(multiline){
                return selectRows;
            }else{
                if(selectRows.length > 1){
                    $.messager.show({
                        title: '提醒',
                        msg: '该功能不能批量操作！<br/>如果你选择了多条记录，则默认操作的是第一条记录！'
                    });
                }
                return selectRows[0];
            }
        },
		// 添加车辆
		addCar: function(){
			var _datagrid = $('#CustomerCarTrialProtocolIndex_editWindow_carsDatagrid');
			var gridData = _datagrid.datagrid('getData'); 
			var total = gridData.total;
			if(total > 0){
				var selectedRow = _datagrid.datagrid('getSelected');				
				if(selectedRow){
					var selectedRowIndex = _datagrid.datagrid('getRowIndex',selectedRow);
					if(_datagrid.datagrid('validateRow',selectedRowIndex)){
						_datagrid.datagrid('endEdit',selectedRowIndex);
					}else{
						$.messager.show({
							title: '验证不合法',
							msg: '请先填写好当前行再新增！'
						});
						return false; // 提前退出
					}	
				}	
			}
			_datagrid.datagrid('appendRow',{
				'ctpd_id': '',
				'ctpd_car_id': '',
				'ctpd_deliver_date': parseDateObj(new Date()),
				'ctpd_note': ''
			});
			_datagrid.datagrid('clearSelections',total)	// 清除所有的选择
					.datagrid('selectRow',total) 		// 选中此行
					.datagrid('beginEdit',total) 		// 打开此行编辑状态
					.datagrid('scrollTo',total); 		// 滚动视图到此行	
			
			// this.sumCarTotalNums();  	// 统计车辆数量
			
		},
		// 修改车辆
		editCar: function(){
			var selectRows = this.getCurrentSelected(true);
			if(!selectRows) return false;
			var _datagrid = $('#CustomerCarTrialProtocolIndex_editWindow_carsDatagrid');
            for(var i in selectRows){
                var rowIndex = _datagrid.datagrid('getRowIndex',selectRows[i]);
                _datagrid.datagrid('beginEdit',rowIndex);
                if(selectRows[i].ctpd_id){  //原记录的车牌号不允许修改
                    var editor = _datagrid.datagrid('getEditor',{"index": rowIndex,"field": "plate_number"});
                    editor.target.combobox('disable');
                }
            }
		},
		// 删除车辆
		removeCar: function(){
            var selectedRows = this.getCurrentSelected(true);
            if(!selectedRows) return false;
            var _datagrid = $('#CustomerCarTrialProtocolIndex_editWindow_carsDatagrid');
            var forbidRemoveRows = []; // 统计不允许删除的原纪录
            for(var i in selectedRows){
                var rowIndex = _datagrid.datagrid('getRowIndex',selectedRows[i]);
                if(selectedRows[i].ctpd_id){
                    forbidRemoveRows.push((rowIndex+1));
                }else{
                    _datagrid.datagrid('deleteRow',rowIndex);
                }
            }
            if(forbidRemoveRows.length){
                $.messager.show({
                    title: '限制删除',
                    msg: '你所选择的第' + forbidRemoveRows.join('、') + '行<br/>为原车辆纪录，不允许删除！',
                    width: 280,
                    autoHeight: true
                });
                return false;
            }
            // this.sumCarTotalNums();  	// 统计车辆数量
		},
		// 完成编辑
		endEdit: function(){
			var _datagrid = $('#CustomerCarTrialProtocolIndex_editWindow_carsDatagrid');
			var gridData = _datagrid.datagrid('getData'); 
			if(gridData.total > 0){ 
				for(var i=0;i<gridData.total;i++){  
					if(!_datagrid.datagrid('validateRow',i)){ // 行验证
						$.messager.show({
							title: '验证不合法',
							msg: '试用车辆列表【第'+(i+1)+'行】校验不合法！'
						});
						return false;
					}else{ 
						_datagrid.datagrid('endEdit',i); // 结束行编辑
					}
				}
			}						
		},
		// 统计车辆数量
		sumCarTotalNums: function(){
			var _datagrid = $('#CustomerCarTrialProtocolIndex_editWindow_carsDatagrid');
			var total = _datagrid.datagrid('getData').total; 
			var _form = $('#CustomerCarTrialProtocolIndex_editWindow_baseInfoForm');
			$('#CustomerCarTrialProtocolIndex_editWindow_ctp_car_nums').numberbox('setValue',total);
		},
        // 确定保存车辆
        saveCars: function(){
            var _datagrid = $('#CustomerCarTrialProtocolIndex_editWindow_carsDatagrid');
            var _gridData = _datagrid.datagrid('getData');
            if(_gridData.total > 0){
                for(var i=0;i<_gridData.total;i++){
                    if(!_datagrid.datagrid('validateRow',i)){ // 行验证
                        $.messager.show({
                            title: '表格验证不合法',
                            msg: '试用车辆列表【第'+(i+1)+'行】校验不合法！'
                        });
                        return false;
                    }else{
                        _datagrid.datagrid('endEdit',i); // 结束行编辑
                    }
                }
            }else{
                $.messager.show({
                    title: '表格验证不合法',
                    msg: '试用车辆列表不能为空！'
                });
                return false;
            }
            var changedRows = _datagrid.datagrid('getChanges'); // 获取所有改变的行！！！
            if(changedRows.length < 1){
                $.messager.show({
                    title: '无需保存',
                    msg: '试用车辆列表未发生改变，不用保存！'
                });
                return false;
            }
            var submitData = {
                ctp_id: ctpEdit_ctp_id,
                changedRows: changedRows
            };
            $.ajax({
                type: 'post',
                url: CustomerCarTrialProtocolIndex_editWindow_URL_saveCars,
                data: submitData,
                dataType: 'json',
                success: function(data){
                    if(data.status){
                        $.messager.show({
                            title: '保存车辆成功',
                            msg: data.info,
                            width: 280,
                            height: '25%'
                        });
                        $('#CustomerCarTrialProtocolIndex_editWindow_baseInfoForm').form('load',data.protocol); // 表单重新赋值
                        $('#CustomerCarTrialProtocolIndex_editWindow_carsDatagrid').datagrid('reload').datagrid('clearSelections');
                        $('#CustomerCarTrialProtocolIndex_datagrid').datagrid('reload');
                    }else{
                        $.messager.alert('出错',data.info,'error');
                        $('#CustomerCarTrialProtocolIndex_editWindow_carsDatagrid').datagrid('reload').datagrid('clearSelections');
                    }
                }
            });
        },
        // 归还车辆(可批量)
        backCar: function(){
            var selectRows = this.getCurrentSelected(true);
            if(!selectRows) return false;
            var idStr = '';
            for(var i in selectRows){
                if(!selectRows[i].ctpd_back_date) {
                    idStr += selectRows[i].ctpd_id + ',';
                }
            }
            if(!idStr){
                $.messager.show({
                    title: '已经归还',
                    msg: '你所选择的车辆都已经归还，不能重复归还！'
                });
                return false;
            }
            $.messager.confirm('确认还车','您确定要归还所选车辆？',function(r){
                if(r){
                    $.ajax({
                        type: 'post',
                        url: CustomerCarTrialProtocolIndex_editWindow_URL_backCar,
                        data: {"idStr": idStr},
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.show({
                                    title: '归还成功',
                                    msg: data.info
                                });
                                $('#CustomerCarTrialProtocolIndex_editWindow_carsDatagrid').datagrid('reload');
                            }else{
                                $.messager.alert('归还失败',data.info,'error');
                                $('#CustomerCarTrialProtocolIndex_editWindow_carsDatagrid').datagrid('reload');
                            }
                        }
                    });
                }
            });
        }
    }
	
	// 执行初始化函数
	CustomerCarTrialProtocolIndex_editWindow.init();
		
	// 加载旧表单数据
	$('#CustomerCarTrialProtocolIndex_editWindow_baseInfoForm').form('load',ctpEdit_initDatas.carTrialProtocol);
	// 查旧客户以赋值显示text,因为combogrid远程查询第一页不一定存在该客户而显示为id
	var oldCustomer = {customerId: ctpEdit_initDatas.carTrialProtocol.ctp_cCustomer_id};  
	$('#CustomerCarTrialProtocolIndex_editWindow_chooseCustomer').combogrid('grid').datagrid('load',oldCustomer);

</script>