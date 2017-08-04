<table id="CustomerPersonalProtocolIndex_manageCarsWindow_datagrid"></table> 
<div id="CustomerPersonalProtocolIndex_manageCarsWindow_datagridToolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="CustomerPersonalProtocolIndex_manageCarsWindow_searchForm">
                <ul class="search-main">
                    <li>
                        <div class="item-name">车牌号</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="plate_number" style="width:150px;" />
                        </div>
                    </li>
                    <li class="search-button">
                        <a href="javascript:CustomerPersonalProtocolIndex_manageCarsWindow.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                    </li>
                </ul>
            </form>
        </div>
    </div>
	
    <div class="easyui-panel" title="试用车辆" style="width:100%;" data-options="
        iconCls: 'icon-car',
        border: false
    ">
        <div style="padding:4px;">
			<a href="javascript:CustomerPersonalProtocolIndex_manageCarsWindow.addCar()" class="easyui-linkbutton" data-options="iconCls:'icon-add'">添加</a>
			<a href="javascript:CustomerPersonalProtocolIndex_manageCarsWindow.editCar()" class="easyui-linkbutton" data-options="iconCls:'icon-edit'">修改</a>
			<a href="javascript:CustomerPersonalProtocolIndex_manageCarsWindow.removeCar()" class="easyui-linkbutton" data-options="iconCls:'icon-remove'">删除</a>
			<a href="javascript:CustomerPersonalProtocolIndex_manageCarsWindow.saveCars()" class="easyui-linkbutton" data-options="iconCls:'icon-save'">确定保存车辆</a>
            <a href="javascript:CustomerPersonalProtocolIndex_manageCarsWindow.backCar()" class="easyui-linkbutton" data-options="iconCls:'icon-car_rarrow'">归还车辆</a>
        </div>
    </div>
	
</div>
<script>
	// 相关数据
	var ctpManageCar_initDatas_Personal = <?php echo json_encode($initDatas); ?>;  
	var ctpManageCar_ctp_id_Personal = ctpManageCar_initDatas_Personal.ctp_id;  

	// 请求的URL
	var CustomerPersonalProtocolIndex_manageCarsWindow_URL_getProtocolDetails = "<?php echo yii::$app->urlManager->createUrl(['customer/personal-protocol/get-protocol-details']); ?>";
	var CustomerPersonalProtocolIndex_manageCarsWindow_URL_backCar = "<?php echo yii::$app->urlManager->createUrl(['customer/personal-protocol/back-car']); ?>";
	var CustomerPersonalProtocolIndex_manageCarsWindow_URL_saveCars = "<?php echo yii::$app->urlManager->createUrl(['customer/personal-protocol/save-cars']); ?>";

    var CustomerPersonalProtocolIndex_manageCarsWindow = {
		init: function(){
			//初始化列表
			$('#CustomerPersonalProtocolIndex_manageCarsWindow_datagrid').datagrid({  
				method: 'get',
				url: CustomerPersonalProtocolIndex_manageCarsWindow_URL_getProtocolDetails + '&inManageWin=1&ctp_id=' + ctpManageCar_ctp_id_Personal,
				toolbar: '#CustomerPersonalProtocolIndex_manageCarsWindow_datagridToolbar',
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
								data: ctpManageCar_initDatas_Personal.cars,
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
		},
        //获取当前所选择的记录
        getCurrentSelected: function(multiline){
            var datagrid = $('#CustomerPersonalProtocolIndex_manageCarsWindow_datagrid');
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
			var _datagrid = $('#CustomerPersonalProtocolIndex_manageCarsWindow_datagrid');
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
			
			ctpEdit_editingRowIndex = total; // 全局变量设为当前行
		},
		// 修改车辆
		editCar: function(){
			var selectRows = this.getCurrentSelected(true);
			if(!selectRows) return false;
			var _datagrid = $('#CustomerPersonalProtocolIndex_manageCarsWindow_datagrid');
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
            var _datagrid = $('#CustomerPersonalProtocolIndex_manageCarsWindow_datagrid');
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
		},
		// 确定保存车辆
		saveCars: function(){
			var _datagrid = $('#CustomerPersonalProtocolIndex_manageCarsWindow_datagrid'); 
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
                ctp_id: ctpManageCar_ctp_id_Personal,
				changedRows: changedRows
			};	
			$.ajax({
				type: 'post',
				url: CustomerPersonalProtocolIndex_manageCarsWindow_URL_saveCars,
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
                        $('#CustomerPersonalProtocolIndex_manageCarsWindow_datagrid').datagrid('reload').datagrid('clearSelections');
						$('#CustomerPersonalProtocolIndex_datagrid').datagrid('reload');
					}else{
						$.messager.alert('出错',data.info,'error');
                        $('#CustomerPersonalProtocolIndex_manageCarsWindow_datagrid').datagrid('reload').datagrid('clearSelections');
					}
				}
			});
		},
		//归还车辆(可批量)
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
						url: CustomerPersonalProtocolIndex_manageCarsWindow_URL_backCar,
						data: {"idStr": idStr},
						dataType: 'json',
						success: function(data){
							if(data.status){
								$.messager.show({
									title: '归还成功',
									msg: data.info
								});
								$('#CustomerPersonalProtocolIndex_manageCarsWindow_datagrid') .datagrid('reload');
							}else{
								$.messager.alert('归还失败',data.info,'error');
                                $('#CustomerPersonalProtocolIndex_manageCarsWindow_datagrid') .datagrid('reload');
							}
						}
					});
				}
			});
		},
		//查询
		search: function(){
			var form = $('#CustomerPersonalProtocolIndex_manageCarsWindow_searchForm');
			var data = {};
			var searchCondition = form.serializeArray();
			for(var i in searchCondition){
				data[searchCondition[i]['name']] = searchCondition[i]['value'];
			}
			$('#CustomerPersonalProtocolIndex_manageCarsWindow_datagrid').datagrid('load',data);
		}
	}
	
    //执行初始化
    CustomerPersonalProtocolIndex_manageCarsWindow.init();
	
</script>