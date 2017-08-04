<table id="CustomerPersonalProtocolIndex_addWindow_carsDatagrid"></table>
<!-- toolbar start -->
<div id="CustomerPersonalProtocolIndex_addWindow_carsDatagridToolbar">
    <form id="CustomerPersonalProtocolIndex_addWindow_baseInfoForm" class="easyui-form" method="post">
        <div
            class="easyui-panel"
            title="协议基本信息"
            iconCls='icon-script-edit'
            border="false"
            style="width:100%;"
        >
			<input type="hidden" name="ctp_customer_type" value="PERSONAL" />
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
						<input id="CustomerPersonalProtocolIndex_addWindow_chooseCustomer" name="ctp_pCustomer_id"  style="width:160px" />
                        <a href="javascript:CustomerPersonalProtocolIndex_addWindow.addCustomer()" class="easyui-linkbutton" data-options="iconCls:'icon-add'" title="新增客户"></a>
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">试用车数量</div>
                    <div class="ulforform-resizeable-input">
                        <input
                            class="easyui-numberbox"
                            style="width:160px;"
                            name="ctp_car_nums" 
							id="CustomerPersonalProtocolIndex_addWindow_ctp_car_nums"
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
            iconCls='icon-save'
            border="false"
            style="width:100%;"
        >
            <div style="padding:4px;">
                <a href="javascript:CustomerPersonalProtocolIndex_addWindow.addCar()" class="easyui-linkbutton" data-options="iconCls:'icon-add'">添加</a>
                <a href="javascript:CustomerPersonalProtocolIndex_addWindow.editCar()" class="easyui-linkbutton" data-options="iconCls:'icon-edit'">修改</a>
                <a href="javascript:CustomerPersonalProtocolIndex_addWindow.removeCar()" class="easyui-linkbutton" data-options="iconCls:'icon-remove'">删除</a>
				<!-- <a href="javascript:CustomerPersonalProtocolIndex_addWindow.endEdit()" class="easyui-linkbutton" data-options="iconCls:'icon-save'">完成编辑</a> -->
			</div>
        </div>
    </form>
</div>
<!-- toolbar end -->
<!-- 窗口 -->
<div id="CustomerPersonalProtocolIndex_addWindow_addCustomerWindow"></div>
<!-- 窗口 -->
<script>
	// 相关数据
	var ctpAdd_initDatas_Personal = <?php echo json_encode($initDatas); ?>;

	// 请求的URL
	var CustomerPersonalProtocolIndex_addWindow_URL_ChooseCustomer = "<?php echo yii::$app->urlManager->createUrl(['customer/combogrid/get-personal-customer-list']); ?>";
	var CustomerPersonalProtocolIndex_addWindow_URL_addCustomer = "<?php echo yii::$app->urlManager->createUrl(['customer/personal/add']); ?>";

	// 当前被编辑的行索引
	var ctpAdd_editingRowIndex_Personal = -1;

    var CustomerPersonalProtocolIndex_addWindow = {
		// 初始化页面
		init: function(){
			// 初始化datagrid
			$('#CustomerPersonalProtocolIndex_addWindow_carsDatagrid').datagrid({
				toolbar: '#CustomerPersonalProtocolIndex_addWindow_carsDatagridToolbar',
				fit: true,
				border: false,
				singleSelect: true,
				rownumbers: true,
				pagination: true,
				pageSize: 20,
				columns:[[
					{field: 'ck',checkbox: true},
					{field: 'ctpd_id',title:'明细ID',width: '50px',hidden:true},
                    {field: 'plate_number',title: '车牌号',width: '100px',align:'center',sortable:true,
                        editor:{
                            type:'combobox',
                            options:{
                                valueField:'plate_number',
                                textField:'plate_number',
                                data: ctpAdd_initDatas_Personal.cars,
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
				]],
				onClickRow:function(rowIndex,rowData){
					if(rowIndex != ctpAdd_editingRowIndex_Personal){ // 若本次点击的行不是当前正被编辑的行
						var _datagrid = $(this);
						// 判断当前正被编辑的行数据是否验证合法
						if(_datagrid.datagrid('validateRow',ctpAdd_editingRowIndex_Personal)){
							_datagrid.datagrid('endEdit',ctpAdd_editingRowIndex_Personal);	// 结束当前正被编辑的行编辑状态
							_datagrid.datagrid('beginEdit',rowIndex); 			// 打开本次点击行成编辑状态
							_datagrid.datagrid('selectRow',rowIndex);			// 选中本次点击行
							ctpAdd_editingRowIndex_Personal =  rowIndex; 					// 改变全局变量值
						}else{ // 若验证不合法，则还选中编辑当前行
							_datagrid.datagrid('selectRow',ctpAdd_editingRowIndex_Personal);
							_datagrid.datagrid('beginEdit',rowIndex);
						}
					}
				},
				onDblClickRow:function(rowIndex, rowData){
					if(rowIndex != ctpAdd_editingRowIndex_Personal){ // 若本次双击的行不是当前正被编辑的行
						var _datagrid = $(this);
						// 判断当前正被编辑的行数据是否验证合法
						if(_datagrid.datagrid('validateRow',ctpAdd_editingRowIndex_Personal)){
							_datagrid.datagrid('endEdit',ctpAdd_editingRowIndex_Personal);	// 结束当前正被编辑的行编辑状态
							_datagrid.datagrid('beginEdit',rowIndex); 			// 打开本次点击行成编辑状态
							_datagrid.datagrid('selectRow',rowIndex);			// 选中本次点击行
							ctpAdd_editingRowIndex_Personal =  rowIndex; 					// 改变全局变量值
                        }else{ // 若验证不合法，则还选中编辑当前行
                            _datagrid.datagrid('selectRow',ctpAdd_editingRowIndex_Personal);
                            _datagrid.datagrid('beginEdit',rowIndex);
						}
					}
				}
/*                ,keyHandler: {
                    up: function(){
                        console.log('up');
                    },
                    down: function(obj){
                        console.log('down');
                    }
                }*/
			});

			// 初始化选择试用客户combogrid
			$('#CustomerPersonalProtocolIndex_addWindow_chooseCustomer').combogrid({
				panelWidth: 500,
				panelHeight: 200,
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
				url: CustomerPersonalProtocolIndex_addWindow_URL_ChooseCustomer,
				method: 'get',
				scrollbarSize:0,
				pagination: true,
				pageSize: 10,
				pageList: [10,20,30],
				fitColumns: true,
				columns: [[
					{field:'customer_id',title:'ID',width:20,align:'center'},
					{field:'customer_name',title:'个人客户名称',width:100,halign:'center'},
					{field:'customer_address',title:'客户地址',width:150,halign:'center'}
				]]
			});

			// 初始化添加新客户窗口
			$('#CustomerPersonalProtocolIndex_addWindow_addCustomerWindow').dialog({
				title: '添加个人客户',
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
							url: CustomerPersonalProtocolIndex_addWindow_URL_addCustomer,
							data: data,
							dataType: 'json',
							success: function(data){
								if(data.status){
									$.messager.show({
                                        title: '添加成功',
                                        msg: data.info
                                    });
									$('#CustomerPersonalProtocolIndex_addWindow_addCustomerWindow').dialog('close');
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
						$('#CustomerPersonalProtocolIndex_addWindow_addCustomerWindow').dialog('close');
					}
				}],
				onClose:function(){  // 关闭窗口时清除可避免id重复问题
					$(this).dialog('clear');
				}
			});
		},
		// 添加客户
		addCustomer: function(){
			$('#CustomerPersonalProtocolIndex_addWindow_addCustomerWindow')
				.dialog('open')
				.dialog('refresh',CustomerPersonalProtocolIndex_addWindow_URL_addCustomer);
		},
		// 获取当前所选的记录
		getCurrentSelected: function(){
			var selectedRow = $('#CustomerPersonalProtocolIndex_addWindow_carsDatagrid').datagrid('getSelected');
			if(!selectedRow){
				$.messager.show({
					title: '提示',
					msg: '请选择要操作的记录！'
				});
				return false;
			}
			return selectedRow;
		},
		// 添加车辆
		addCar: function(){
			var _datagrid = $('#CustomerPersonalProtocolIndex_addWindow_carsDatagrid');
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

			ctpAdd_editingRowIndex_Personal = total;   	// 全局变量设为当前行
			this.sumCarTotalNums();  	// 统计车辆数量

		},
		// 修改车辆
		editCar: function(){
			var selectedRow = this.getCurrentSelected();
			if(!selectedRow) return false;
			var _datagrid = $('#CustomerPersonalProtocolIndex_addWindow_carsDatagrid');
			var selectedRowIndex = _datagrid.datagrid('getRowIndex',selectedRow);
			_datagrid.datagrid('beginEdit',selectedRowIndex);
		},
		// 删除车辆
		removeCar: function(){
			var selectedRow = this.getCurrentSelected();
			if(!selectedRow) return false;
			var _datagrid = $('#CustomerPersonalProtocolIndex_addWindow_carsDatagrid');
			var selectedRowIndex = _datagrid.datagrid('getRowIndex',selectedRow);
			_datagrid.datagrid('deleteRow',selectedRowIndex);
			this.sumCarTotalNums();  	// 统计车辆数量
		},
		// 完成编辑
		endEdit: function(){
			var _datagrid = $('#CustomerPersonalProtocolIndex_addWindow_carsDatagrid');
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
			var _datagrid = $('#CustomerPersonalProtocolIndex_addWindow_carsDatagrid');
			var total = _datagrid.datagrid('getData').total;
			var _form = $('#CustomerPersonalProtocolIndex_addWindow_baseInfoForm');
			$('#CustomerPersonalProtocolIndex_addWindow_ctp_car_nums').numberbox('setValue',total);
		}
	}

	// 执行初始化函数
	CustomerPersonalProtocolIndex_addWindow.init();
	
</script>