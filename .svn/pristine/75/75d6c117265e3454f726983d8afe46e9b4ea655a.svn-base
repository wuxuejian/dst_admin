<table id="CustomerCarTrialProtocolIndex_datagrid"></table> 
<div id="CustomerCarTrialProtocolIndex_datagridToolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="CustomerCarTrialProtocolIndex_searchForm">
                <ul class="search-main">
                    <li>
                        <div class="item-name">协议编号</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="ctp_number" style="width:100%;"  />
                        </div>
                    </li>          
					<li>
						<div class="item-name">试用客户</div>
						<div class="item-input">
							<input class="easyui-textbox" type="text" name="customer_name" style="width:100%;"  />
						</div>
					</li>	
					<!--		
					<li>
						<div class="item-name">试用日期</div>
						<div class="item-input-datebox">
							<input class="easyui-datebox" type="text" name="ctp_start_date" style="width:90px;"  /> -
							<input class="easyui-datebox" type="text" name="ctp_end_date" style="width:90px;"  />
						</div>               
					</li>
					-->
                    <li class="search-button">
                        <a onclick="CustomerCarTrialProtocolIndex.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                        <a onclick="CustomerCarTrialProtocolIndex.reset()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</a>
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
                <a onclick="<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon']; ?>'"><?= $val['text']; ?></a>
            <?php } ?>
        </div>
    <?php } ?>
</div>
<!-- 窗口 -->
<div id="CustomerCarTrialProtocolIndex_addWindow"></div>
<div id="CustomerCarTrialProtocolIndex_editWindow"></div>
<div id="CustomerCarTrialProtocolIndex_manageCarsWindow"></div>
<!-- 窗口 -->

<script>
    // 配置数据
    var CustomerCarTrialProtocolIndex_CONFIG = <?php echo json_encode($config); ?>;

	// 声明了全局变量保存请求的URL
	var CustomerCarTrialProtocolIndex_URL_getList = "<?php echo yii::$app->urlManager->createUrl(['customer/car-trial-protocol/get-list']);?>";
	var CustomerCarTrialProtocolIndex_URL_add = "<?php echo yii::$app->urlManager->createUrl(['customer/car-trial-protocol/add']);?>";
	var CustomerCarTrialProtocolIndex_URL_edit = "<?php echo yii::$app->urlManager->createUrl(['customer/car-trial-protocol/edit']);?>";
	var CustomerCarTrialProtocolIndex_URL_remove = "<?php echo yii::$app->urlManager->createUrl(['customer/car-trial-protocol/remove']);?>";
	var CustomerCarTrialProtocolIndex_URL_manageCars = "<?php echo yii::$app->urlManager->createUrl(['customer/car-trial-protocol/manage-cars']);?>";
	var CustomerCarTrialProtocolIndex_URL_exportWidthCondition = "<?php echo yii::$app->urlManager->createUrl(['customer/car-trial-protocol/export-width-condition']);?>";

	var CustomerCarTrialProtocolIndex = {
		// 页面初始化
		init: function(){
			// 初始化表格
			$('#CustomerCarTrialProtocolIndex_datagrid').datagrid({  
				method: 'get', 
				url: CustomerCarTrialProtocolIndex_URL_getList,   
				fit: true,
				border: false,
				toolbar: '#CustomerCarTrialProtocolIndex_datagridToolbar',
				pagination: true,
				loadMsg: '数据加载中...',
				striped: true,
				checkOnSelect: true,
				rownumbers: true,
				singleSelect: false,
				pageSize: 20,
				frozenColumns: [[
					{field: 'ck',checkbox: true}, 
					{field: 'ctp_id',title: '协议ID',hidden: true},  
					{field: 'ctp_number',title: '协议编号',width: 120,halign: 'center',sortable: true},
					{field: 'ctp_customer_id',title: '试用客户ID',width: 80,align: 'center',hidden:true},
					{field: 'customer_name',title: '试用客户',width: 180,halign: 'center',sortable: true},
				]],
				columns: [[
					{field: 'ctp_customer_type',title: '客户类型',width: 60,align:'center',sortable: true,
						formatter: function (value, row, index) {
							try {
								var str = 'CustomerCarTrialProtocolIndex_CONFIG.customer_type.' + value + '.text';
								return eval(str);
							} catch (e) {
								return value;
							}
						}
					},
					{field: 'ctp_sign_date',title: '签订日期',width: 80,align: 'center',sortable: true},
					{field: 'ctp_start_date',title: '开始时间',width: 80,align: 'center',sortable: true},
					{field: 'ctp_end_date',title: '结束时间',width: 80,align: 'center',sortable: true},
					{field: 'ctp_car_nums',title: '车辆数量',width: 60,align: 'center',sortable: true},
					{field: 'ctp_systime',title: '登记时间',width: 130,align: 'center',sortable: true,
						formatter: function(value,row,index){
							return formatDateToString(value,true);
						}
					},
					{field: 'ctp_sysuser',title: '登记人员',width: 100,align: 'center',sortable: true},
					{field: 'ctp_note',title: '备注',width: 200,halign: 'center',sortable: true},
					{field: 'operating_company',title: '所属运营公司',width: 170,halign: 'center',sortable: true},
                    {field: 'ctp_last_modify_datetime',title: '上次操作时间',width: 130,align: 'center',sortable: true,
                        formatter: function(value,row,index){
                            return formatDateToString(value,true);
                        }
                    },
                    {field: 'username',title: '操作账号',width: 100,halign: 'center',sortable: true},
				]],
				onDblClickRow: function(rowIndex,rowData){
					CustomerCarTrialProtocolIndex.edit(rowData.ctp_id);
				}
			});	
			
			// 初始化新建协议窗口
			$('#CustomerCarTrialProtocolIndex_addWindow').dialog({
				title: '新建试用协议',   
				width: '980px',   
				height: '500px',   
				closed: true,   
				cache: true,   
				modal: true,
				resizable:true,
                maximizable: true,
				buttons: [{
					text:'确定',
					iconCls:'icon-ok',
					handler:function(){ 
						var _form = $('#CustomerCarTrialProtocolIndex_addWindow_baseInfoForm');	
						if(!_form.form('validate')){
							$.messager.show({
								title: '表单验证不合法',
								msg: '基本信息表单填写不完整或填写错误！'
							});
							return false;
						}
						var _grid = $('#CustomerCarTrialProtocolIndex_addWindow_carsDatagrid'); 
						var _gridData = _grid.datagrid('getData'); 	
						if(_gridData.total > 0){ 
							for(var i=0;i<_gridData.total;i++){  
								if(!_grid.datagrid('validateRow',i)){ // 行验证
									$.messager.show({
										title: '表格验证不合法',
										msg: '试用车辆列表【第'+(i+1)+'行】校验不合法！'
									});
									return false;
								}else{ 
									_grid.datagrid('endEdit',i); // 结束行编辑
								}
								var submitData = {
									formData: _form.serialize(),
									gridData: _gridData.rows
								};									
								
							}
						}else{
							var submitData = {
							formData: _form.serialize(),
							gridData: 0
							};
						}
						$.ajax({
							type: 'post',
							url: CustomerCarTrialProtocolIndex_URL_add,
							data: submitData,
							dataType: 'json',
							success: function(data){
								if(data.status){
									$.messager.show({
										title: '新建协议成功',
                                        msg: data.info,
                                        width: 280,
                                        height:'25%'
									});
									$('#CustomerCarTrialProtocolIndex_addWindow').dialog('close'); 
									$('#CustomerCarTrialProtocolIndex_datagrid').datagrid('reload');
								}else{
									$.messager.alert('新建协议出错',data.info,'error');
								}
							}
						});
					}
				},{
					text:'取消',
					iconCls:'icon-cancel',
					handler:function(){
						$('#CustomerCarTrialProtocolIndex_addWindow').dialog('close');
					}
				}],
				onClose: function(){ // 关闭窗口时清空以避免id重复！
					$(this).dialog('clear');
				}  
			});
			// 初始化修改协议窗口
			$('#CustomerCarTrialProtocolIndex_editWindow').dialog({
				title: '修改试用协议',   
				width: '980px',   
				height: '500px',   
				closed: true,   
				cache: true,   
				modal: true,
				resizable:true,
                maximizable: true,
				buttons: [{
					text:'确定',
					iconCls:'icon-ok',
					handler:function(){ 
						var _form = $('#CustomerCarTrialProtocolIndex_editWindow_baseInfoForm');	
						if(!_form.form('validate')){
							$.messager.show({
								title: '表单验证不合法',
								msg: '基本信息表单填写不完整或填写错误！'
							});
							return false;
						}
						var _grid = $('#CustomerCarTrialProtocolIndex_editWindow_carsDatagrid'); 
						var _gridData = _grid.datagrid('getData'); 	
						if(_gridData.total > 0){ 
							for(var i=0;i<_gridData.total;i++){  
								if(!_grid.datagrid('validateRow',i)){ // 行验证
									$.messager.show({
										title: '表格验证不合法',
										msg: '试用车辆列表【第'+(i+1)+'行】校验不合法！'
									});
									return false;
								}else{ 
									_grid.datagrid('endEdit',i); // 结束行编辑
								}
							}
						}
						else{
							
							$.messager.show({
								title: '表格验证不合法',
								msg: '试用车辆列表不能为空！'
							});  
							return false;
						}				
						var changedRows = _grid.datagrid('getChanges'); // 获取所有改变的行！！！
						var submitData = {
							formData: _form.serialize(),
							changedRows: changedRows
						};	
						$.ajax({
							type: 'post',
							url: CustomerCarTrialProtocolIndex_URL_edit,
							data: submitData,
							dataType: 'json',
							success: function(data){
								if(data.status){
                                    $.messager.show({
                                        title: '修改协议成功',
                                        msg: data.info,
                                        width: 280,
                                        height:'25%'
                                    });
									// $('#CustomerCarTrialProtocolIndex_editWindow_carsDatagrid').datagrid('reload');
                                    // $('#CustomerCarTrialProtocolIndex_editWindow_baseInfoForm').form('load',data.protocol);
                                    $('#CustomerCarTrialProtocolIndex_editWindow').dialog('close');
									$('#CustomerCarTrialProtocolIndex_datagrid').datagrid('reload');
								}else{
									$.messager.alert('修改协议出错',data.info,'error');
                                    $('#CustomerCarTrialProtocolIndex_editWindow_carsDatagrid').datagrid('reload');
								}
							}
						});
					}
				},{
					text:'取消',
					iconCls:'icon-cancel',
					handler:function(){
						$('#CustomerCarTrialProtocolIndex_editWindow').dialog('close');
					}
				}],
				onClose: function(){ // 关闭窗口时清空以避免id重复！
					$(this).dialog('clear');
				}  
			});
			
			// 初始化试用车辆管理窗口
			$('#CustomerCarTrialProtocolIndex_manageCarsWindow').window({
				title: '试用车辆管理',   
				width: '980px',   
				height: '500px',   
				closed: true,   
				cache: true,   
				modal: true,
				resizable:true,			
				onClose: function(){ // 关闭窗口时清空以避免id重复！
					$(this).window('clear');
				}  
			});
		},
        //获取当前所选择的记录
        getCurrentSelected: function(multiline){
            var datagrid = $('#CustomerCarTrialProtocolIndex_datagrid');
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
		// 添加
		add: function(){ 
			$('#CustomerCarTrialProtocolIndex_addWindow')
				.dialog('open')
				.dialog('refresh',CustomerCarTrialProtocolIndex_URL_add);
		},
		// 修改
		edit: function(id){
			if(!id){
				var selectRow = this.getCurrentSelected();
				if(!selectRow){
					return false;
				}
				var id = selectRow.ctp_id;
			}
			$('#CustomerCarTrialProtocolIndex_editWindow')
				.dialog('open')
				.dialog('refresh',CustomerCarTrialProtocolIndex_URL_edit + '&id=' + id);
		},
		// 删除
		remove: function(){
			var selectRow = this.getCurrentSelected();
			if(!selectRow) return false;
			var id = selectRow.ctp_id;
			$.messager.confirm('确定删除','你确定要删除所选行吗？',function(r){
				if(r){
					$.ajax({
						type: 'get',
						url: CustomerCarTrialProtocolIndex_URL_remove,
						data: {id: id},
						dataType: 'json',
						success: function(data){
							if(data.status){
								$.messager.show({
									title: '删除成功',
									msg: data.info
								});   
								$('#CustomerCarTrialProtocolIndex_datagrid').datagrid('reload');
							}else{
								$.messager.alert('删除失败',data.info,'error');   
							}
						}
					});
				}
			});
		},
        // 试用车辆管理
        manageCars: function(){
            var selectRow = this.getCurrentSelected();
            if(!selectRow) return false;
            var id = selectRow.ctp_id;
			$('#CustomerCarTrialProtocolIndex_manageCarsWindow')
				.dialog('open')
				.dialog('refresh',CustomerCarTrialProtocolIndex_URL_manageCars + '&id=' + id);
        },
		// 按条件导出
		exportWidthCondition: function(){
			var searchConditionStr = $("CustomerCarTrialProtocolIndex_searchForm").serialize(); // 查询字符串格式
			var url = CustomerCarTrialProtocolIndex_URL_exportWidthCondition + "&" + searchConditionStr;
			window.open(url);
		},	
		// 查询
		search: function(){
			var data = {};
			var searchCondition = $("#CustomerCarTrialProtocolIndex_searchForm").serializeArray();
			for(var i in searchCondition){
				data[searchCondition[i]['name']] = searchCondition[i]['value'];
			}console.log(data)
			$('#CustomerCarTrialProtocolIndex_datagrid').datagrid('load',data);
		},
		// 重置
        reset: function(){
			$("#CustomerCarTrialProtocolIndex_searchForm").form('reset');
		}
	}
	
	// 执行初始化函数
	CustomerCarTrialProtocolIndex.init();
	
</script>