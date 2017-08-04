<table id="CarTrialCarIndex_datagrid"></table> 
<div id="CarTrialCarIndex_datagridToolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="CarTrialCarIndex_searchForm">
                <ul class="search-main">
                    <li>
                        <div class="item-name">车牌号</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="plate_number" style="width:150px;"  />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">车架号（vin）</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="vehicle_dentification_number" style="width:150px;"  />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">车辆状态</div>
                        <div class="item-input">
                            <select style="width:150px;" class="easyui-combobox" name="car_status"  data-options="panelHeight:'auto',editable:false">
                                <option value="">不限</option>
                                <?php foreach($config['trial_car_status'] as $val){ ?>
                                    <option value="<?php echo $val['value']; ?>"><?php echo $val['text']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">车辆品牌</div>
                        <div class="item-input">
                            <select style="width:150px;" class="easyui-combobox" name="car_brand"  data-options="panelHeight:'auto',editable:false">
                                <option value="">不限</option>
                                <?php foreach($config['car_brand'] as $val){ ?>
                                <option value="<?php echo $val['value']; ?>"><?php echo $val['text']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </li>
					
                    <li class="search-button">
                        <a id="btn" href="javascript:CarTrialCarIndex.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                    </li>
                </ul>
            </form>
        </div>
    </div>

    <div class="easyui-panel" title="试用车辆列表" style="padding:8px 4px;" data-options="
        iconCls: 'icon-table-list',
        border: false
    ">
	<?php 
		if(!empty($buttons)){
			foreach($buttons as $val){ 			
				echo '<a href="javascript:' . $val['on_click'] . '" class="easyui-linkbutton" data-options="iconCls:\'' . $val['icon'] . '\'">' . $val['text'] . '</a> ';
			} 
		}
	?>
	</div>
</div>
<!-- 窗口 -->
<div id="CarTrialCarIndex_addWindow"></div>
<div id="CarTrialCarIndex_scanWindow"></div>
<div id="CarTrialCarIndex_editWindow"></div>
<div id="CarTrialCarIndex_attachmentWindow"></div>
<div id="CarTrialCarIndex_faultWindow"></div>
<div id="CarTrialCarIndex_drivingLicenseWindow"></div>
<div id="CarTrialCarIndex_roadTransportCertificateWindow"></div>
<div id="CarTrialCarIndex_secondMaintenanceRecordWindow"></div>
<div id="CarTrialCarIndex_trafficCompulsoryInsuranceWindow"></div>
<div id="CarTrialCarIndex_businessInsuranceWindow"></div>
<!-- 窗口 -->
<script>
	// 请求的URL。
	// 注意：访问的路由与"正式车辆基本信息"菜单页面里的一模一样，只是个别额外传递了参数(isTrialCar=1)用以区分是否为试用车辆！！！
	var CarTrialCarIndex_URL_getList = "<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/get-list']); ?>&isTrialCar=1";
	var CarTrialCarIndex_URL_add = "<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/add']); ?>&isTrialCar=1";
	var CarTrialCarIndex_URL_edit = "<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/edit']); ?>&isTrialCar=1";
	var CarTrialCarIndex_URL_scan = "<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/scan']); ?>&isTrialCar=1";
	var CarTrialCarIndex_URL_remove = "<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/remove']); ?>";
	var CarTrialCarIndex_URL_drivingLicense = "<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/driving-license']); ?>";
	var CarTrialCarIndex_URL_roadTransportCertificate = "<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/road-transport-certificate']); ?>";
	var CarTrialCarIndex_URL_businessInsurance = "<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/business-insurance']); ?>";
	var CarTrialCarIndex_URL_secondMaintenanceRecord = "<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/second-maintenance-record']); ?>";
	var CarTrialCarIndex_URL_trafficCompulsoryInsurance = "<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/traffic-compulsory-insurance']); ?>";
	var CarTrialCarIndex_URL_exportChoose = "<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/export-choose']);?>";
	var CarTrialCarIndex_URL_exportWidthCondition = "<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/export-width-condition']);?>&isTrialCar=1";
	var CarTrialCarIndex_URL_attachment = "<?php echo yii::$app->urlManager->createUrl(['car/attachment/index-single']); ?>";
	var CarTrialCarIndex_URL_fault = "<?php echo yii::$app->urlManager->createUrl(['car/fault/index']); ?>";
	
	var CarTrialCarIndex = {
		// 初始化页面
		init: function(){
			// 初始化-试用车辆表格
			$('#CarTrialCarIndex_datagrid').datagrid({  
				method: 'get', 
				url: CarTrialCarIndex_URL_getList,   
				fit: true,
				border: false,
				toolbar: "#CarTrialCarIndex_datagridToolbar",
				pagination: true,
				loadMsg: '数据加载中...',
				striped: true,
				checkOnSelect: true,
				rownumbers: true,
				singleSelect: false,
				pageSize: 20,
				frozenColumns: [[
					{field: 'ck',checkbox: true}, 
					{field: 'id',title: 'id',align: 'center',hidden: true},   
					{field: 'plate_number',title: '车牌号',width: 80,align: 'center',sortable: true},
				]],
				columns: [[
					{field: 'vehicle_dentification_number',title: '车架号',width: 140,align: 'center',sortable: true},
					{field: 'engine_number',title: '发动机号',width: 140,align: 'center',sortable: true},
					{
						field: 'car_status',title: '车辆状态',width: 80,align: 'center',
						sortable: true,
						formatter: function(value){
							var status = <?php echo json_encode($config['trial_car_status']); ?>;
							try{
								return status[value].text;
							}catch(e){
								return '';
							}
						}
					},
					{
						field: 'car_brand',title: '车辆品牌',width: 100,align: 'center',
						sortable: true,
						formatter: function(value){
							var car_brand = <?php echo json_encode($config['car_brand']); ?>;
							try{
								return car_brand[value].text;
							}catch(e){
								return '';
							}
						}
					},
					{
						field: 'car_type',title: '车辆类型',width: 100,align: 'center',
						sortable: true,
						formatter: function(value){
							var car_type = <?php echo json_encode($config['car_type']); ?>;
							try{
								return car_type[value].text;
							}catch(e){
								return '';
							}
						}
					},
					{
						field: 'car_color',title: '车身颜色',width: 80,align: 'center',
						sortable: true,
						formatter: function(value){
							var car_color = <?php echo json_encode($config['car_color']); ?>;
							try{
								return car_color[value].text;
							}catch(e){
								return '';
							}
						}
					},
					{field: 'note',title: '备注',width: 200,halign: 'center'},
                    {field: 'is_trial',title: '试用车辆?',width: 70,align: 'center',
                        formatter: function(value){
                            try{
                                return '<input type="checkbox" class="isTrial" disabled="true" ' + (value == 1 ? 'checked="checked"' : '') + ' />';
                            }catch(e){
                                return value;
                            }
                        }
                    },
					{
						field: 'add_time',title: '入库时间',width: 140,align: 'center',
						sortable: true,
						formatter: function(value){
							if(!isNaN(value) && value >0){
								return formatDateToString(value);
							}
						}
					},
					{field: 'username',title: '操作人员',width: 90,align: 'center',sortable: true}
				]],
				onDblClickRow: function(rowIndex,rowData){
					CarTrialCarIndex.edit(rowData.id);
				}
			});	
			
			//初始化-添加窗口
			$('#CarTrialCarIndex_addWindow').dialog({
				title: '添加试用车辆信息',
				width: '980px',   
				height: '500px',   
				closed: true,   
				cache: true,   
				modal: true,
				buttons: [{
					text:'确定',
					iconCls:'icon-ok',
					handler:function(){
                        var form = $('#easyui-form-car-baseinfo-add');
						if(!form.form('validate')) return false;
						var data = form.serialize();
						$.ajax({
							type: 'post',
							url: CarTrialCarIndex_URL_add,
							data: data,
							dataType: 'json',
							success: function(data){
								if(data.status){
									$.messager.alert('添加成功',data.info,'info');
									$('#CarTrialCarIndex_addWindow').dialog('close');
									$('#CarTrialCarIndex_datagrid').datagrid('reload');
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
						$('#CarTrialCarIndex_addWindow').dialog('close');
					}
				}],
				onClose: function(){
					$(this).dialog('clear');
				}       
			});
			
			//初始化-查看窗口
			$('#CarTrialCarIndex_scanWindow').window({
				title: '查看试用车辆信息',
				width: 1000,   
				height: 600,   
				closed: true,   
				cache: true,   
				modal: true,
				collapsible: false,
				minimizable: false, 
				maximizable: true,
				onClose: function(){
					$(this).window('clear');
				}       
			});
			
			//初始化-修改窗口
			$('#CarTrialCarIndex_editWindow').dialog({
				title: '修改试用车辆信息',
				width: '980px',   
				height: '500px',   
				closed: true,   
				cache: true,   
				modal: true,
				buttons: [{
					text:'确定',
					iconCls:'icon-ok',
					handler:function(){
                        var form = $('#easyui-form-car-baseinfo-edit');
						if(!form.form('validate')) return false;
						var data = form.serialize();
						$.ajax({
							type: 'post',
							url: CarTrialCarIndex_URL_edit,
							data: data,
							dataType: 'json',
							success: function(data){
								if(data.status){
									$.messager.alert('修改成功',data.info,'info');
									$('#CarTrialCarIndex_editWindow').dialog('close');
									$('#CarTrialCarIndex_datagrid').datagrid('reload');
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
						$('#CarTrialCarIndex_editWindow').dialog('close');
					}
				}],
				onClose: function(){
					$(this).dialog('clear');
				}       
			});
			//初始化-附件管理窗口
			$('#CarTrialCarIndex_attachmentWindow').window({
				title: '车辆附件管理',
				width: 800,   
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
			//初始化-故障管理窗口
			$('#CarTrialCarIndex_faultWindow').window({
				title: '车辆故障管理',
				width: 1000,   
				height: 600,   
				modal: true,
				closed: true,
				collapsible: false,
				minimizable: false,
				maximizable: false,
				onClose: function(){
					$(this).window('clear');
				}                      
			});
			//初始化-行驶证管理窗口
			$('#CarTrialCarIndex_drivingLicenseWindow').dialog({
				title: '车辆行驶证',   
				width: '640px',   
				height: '270px',   
				closed: true,   
				cache: true,   
				modal: true,
				buttons: [{
					text:'确定',
					iconCls:'icon-ok',
					handler:function(){
						var form = $('#easyui-form-car-baseinfo-driving-license');
						if(!form.form('validate')) return false;
						var data = form.serialize();
						$.ajax({
							type: 'post',
							url: CarTrialCarIndex_URL_drivingLicense,
							data: data,
							dataType: 'json',
							success: function(data){
								if(data.status){
									$.messager.alert('操作成功',data.info,'info');
									$('#CarTrialCarIndex_drivingLicenseWindow').dialog('close');
								}else{
									$.messager.alert('操作失败',data.info,'error');
								}
							}
						});
					}
				},{
					text:'取消',
					iconCls:'icon-cancel',
					handler:function(){
						$('#CarTrialCarIndex_drivingLicenseWindow').dialog('close');
					}
				}],
				onClose: function(){
					$(this).dialog('clear');
				}                       
			});
			//初始化-道路运输证窗口
			$('#CarTrialCarIndex_roadTransportCertificateWindow').dialog({
				title: '车辆道路运输证',   
				width: '630px',   
				height: '270px',   
				closed: true,   
				cache: true,   
				modal: true,
				buttons: [{
					text:'确定',
					iconCls:'icon-ok',
					handler:function(){
						var form = $('#easyui-form-car-baseinfo-road-transport-certificate');
						if(!form.form('validate')) return false;
						var data = form.serialize();
						$.ajax({
							type: 'post',
							url: CarTrialCarIndex_URL_roadTransportCertificate,
							data: data,
							dataType: 'json',
							success: function(data){
								if(data.status){
									$.messager.alert('操作成功',data.info,'info');
									$('#CarTrialCarIndex_roadTransportCertificateWindow').dialog('close');
								}else{
									$.messager.alert('操作失败',data.info,'error');
								}
							}
						});
					}
				},{
					text:'取消',
					iconCls:'icon-cancel',
					handler:function(){
						$('#CarTrialCarIndex_roadTransportCertificateWindow').dialog('close');
					}
				}],
				onClose: function(){
					$(this).dialog('clear');
				}                       
			});
			//初始化-车辆二级维护记录管理窗口
			$('#CarTrialCarIndex_secondMaintenanceRecordWindow').window({
				title: '车辆二级维护记录管理',
				width: 1000,   
				height: 600,   
				modal: true,
				closed: true,
				collapsible: false,
				minimizable: false,
				maximizable: false,
				onClose: function(){
					$(this).window('clear');
				}                       
			});
			//初始化-车辆交强险管理窗口
			$('#CarTrialCarIndex_trafficCompulsoryInsuranceWindow').window({
				title: '车辆交通强制险管理',
				width: 1000,   
				height: 600,   
				modal: true,
				closed: true,
				collapsible: false,
				minimizable: false,
				maximizable: false,
				onClose: function(){
					$(this).window('clear');
				}                       
			});
			//初始化-车辆商业险管理窗口
			$('#CarTrialCarIndex_businessInsuranceWindow').window({
				title: '车辆商业保险管理',
				width: 1000,   
				height: 600,   
				modal: true,
				closed: true,
				collapsible: false,
				minimizable: false,
				maximizable: false,
				onClose: function(){
					$(this).window('clear');
				}                       
			});
		},
		//获取当前所选择的记录
		getCurrentSelected: function(multiline){
			var datagrid = $('#CarTrialCarIndex_datagrid');
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
		//添加
		add: function(){
			$('#CarTrialCarIndex_addWindow')
				.dialog('open')
				.dialog('refresh',CarTrialCarIndex_URL_add);
		},
		//查看
		scan: function(){
			var selectRow = this.getCurrentSelected();
			if(!selectRow){
				return false;
			}
			var id = selectRow.id;
			$('#CarTrialCarIndex_scanWindow')
				.window('open')
				.window('refresh',CarTrialCarIndex_URL_scan + "&id=" + id);
		},
		//修改
		edit: function(id){
			if(!id){
				var selectRow = this.getCurrentSelected();
				if(!selectRow){
					return false;
				}
				id = selectRow.id;
			}
			$('#CarTrialCarIndex_editWindow')
				.dialog('open')
				.dialog('refresh',CarTrialCarIndex_URL_edit + '&id=' + id);
		},
		//删除
		remove: function(){
			var selectRow = this.getCurrentSelected();
			if(!selectRow) return false;
			var id = selectRow.id;
			$.messager.confirm('确定删除','您确定要删除该汽车数据？',function(r){
				if(r){
					$.ajax({
						type: 'get',
						url: CarTrialCarIndex_URL_remove,
						data: {id: id},
						dataType: 'json',
						success: function(data){
							if(data){
								$.messager.alert('删除成功',data.info,'info');   
								$('#CarTrialCarIndex_datagrid').datagrid('reload');
							}else{
								$.messager.alert('删除失败',data.info,'error');   
							}
						}
					});
				}
			});
		},
		//附件管理
		attachment: function(){
			var selectRow = this.getCurrentSelected();
			if(!selectRow) return false;
			var id = selectRow.id;
			$('#CarTrialCarIndex_attachmentWindow')
				.window('open')
				.window('refresh', CarTrialCarIndex_URL_attachment + '&carId=' + id);
		},
		//故障管理
		faultMange: function(){
			var selectRow = this.getCurrentSelected();
			if(!selectRow) return false;
			var id = selectRow.id;
			$('#CarTrialCarIndex_faultWindow')
				.window('open')
				.window('refresh', CarTrialCarIndex_URL_fault + '&carId=' + id);
		},
		//行驶证管理
		drivingLicense: function(){
			var selectRow = this.getCurrentSelected();
			if(!selectRow){
				return false;
			}
			var id = selectRow.id;
			$('#CarTrialCarIndex_drivingLicenseWindow')
				.dialog('open')
				.dialog('refresh',CarTrialCarIndex_URL_drivingLicense + "&carId=" + id);
		},
		//道路运输证管理
		roadTransportCertificate: function(){
			var selectRow = this.getCurrentSelected();
			if(!selectRow){
				return false;
			}
			var id = selectRow.id;
			$('#CarTrialCarIndex_roadTransportCertificateWindow')
				.dialog('open')
				.dialog('refresh',CarTrialCarIndex_URL_roadTransportCertificate + "&carId=" + id);
		},
		//二级维护记录管理
		secondMaintenanceRecord: function(){
			var selectRow = this.getCurrentSelected();
			if(!selectRow){
				return false;
			}
			var id = selectRow.id;
			$('#CarTrialCarIndex_secondMaintenanceRecordWindow')
				.window('open')
				.window('refresh',CarTrialCarIndex_URL_secondMaintenanceRecord + "&carId=" + id);
		},
		//交强险管理
		trafficCompulsoryInsurance: function(){
			var selectRow = this.getCurrentSelected();
			if(!selectRow){
				return false;
			}
			var id = selectRow.id;
			$('#CarTrialCarIndex_trafficCompulsoryInsuranceWindow')
				.window('open')
				.window('refresh',CarTrialCarIndex_URL_trafficCompulsoryInsurance + "&carId=" + id);
		},
		//商业险管理
		businessInsurance: function(){
			var selectRow = this.getCurrentSelected();
			if(!selectRow){
				return false;
			}
			var id = selectRow.id;
			$('#CarTrialCarIndex_businessInsuranceWindow')
				.window('open')
				.window('refresh',CarTrialCarIndex_URL_businessInsurance + "&carId=" + id);
		},
		//导出选择的车辆
		exportChooseCar: function(){
			var selectRows = this.getCurrentSelected(true);
			if(!selectRows){
				return false;
			}
			var id = '';
			for(var i in selectRows){
				id += selectRows[i].id+',';
			}
			var url = CarTrialCarIndex_URL_exportChoose + "&id=" + id;
			window.open(url);
		},
		//按条件导出
		exportWidthCondition: function(){
			var url = CarTrialCarIndex_URL_exportWidthCondition;
			var form = $('#CarTrialCarIndex_searchForm');
			var data = {};
			var searchCondition = form.serializeArray();
			for(var i in searchCondition){
				data[searchCondition[i]['name']] = searchCondition[i]['value'];
			}
			for(var i in data){
				url += '&'+i+'='+data[i];
			}
			window.open(url);
		},
		//查询
		search: function(){
			var form = $('#CarTrialCarIndex_searchForm');
			var data = {};
			var searchCondition = form.serializeArray();
			for(var i in searchCondition){
				data[searchCondition[i]['name']] = searchCondition[i]['value'];
			}
			$('#CarTrialCarIndex_datagrid').datagrid('load',data);
		}
	}
	
	// 执行初始化函数
	CarTrialCarIndex.init();
	
</script>