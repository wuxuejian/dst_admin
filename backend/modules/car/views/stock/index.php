<table id="easyui-datagrid-car-stock-index"></table> 
<div id="easyui-datagrid-car-stock-index-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-car-stock-index">
                <ul class="search-main">
                    <li>
                        <div class="item-name">车牌号</div>
                        <div class="item-input">
                            <input name="plate_number" style="width:200px;" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">车辆类型</div>
                        <div class="item-input">
                            <input style="width:200px;" name="car_type" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">替换状态</div>
                        <div class="item-input">
                            <input style="width:200px;" name="car_status" />
                        </div>
                    </li>
					<li>
                        <div class="item-name">车辆运营公司</div>
                        <div class="item-input">
                            <input style="width:200px;" name="operating_company_id" />
                        </div>
                    </li>
                    <li class="search-button">
                        <button type="submit" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button type="submit" onclick="CarStockIndex.resetForm()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
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
                <button onclick="<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon']; ?>'"><?= $val['text']; ?></button>
            <?php } ?>
        </div>
    <?php } ?>
</div>
<!-- 窗口 -->
<div id="easyui-dialog-car-stock-index-add-dst"></div>
<div id="easyui-dialog-car-stock-index-add-backup"></div>
<div id="easyui-dialog-car-stock-index-replace"></div>

<!-- 窗口 -->
<script>
	var CarStockIndex = new Object();
	CarStockIndex.init = function(){
		$('#easyui-datagrid-car-stock-index').datagrid({  
			method: 'get', 
		    url:"<?php echo yii::$app->urlManager->createUrl(['car/stock/get-list']); ?>",   
			fit: true,
			border: false,
			toolbar: "#easyui-datagrid-car-stock-index-toolbar",
			pagination: true,
			loadMsg: '数据加载中...',
			striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
			pageSize: 20,
            frozenColumns: [[
				{field: 'ck',checkbox: true}, 
				{field: 'id',title: 'id',hidden: true},   
				{field: 'plate_number',title: '车牌号',width: 70,sortable: true,align: 'center'}
			]],
		    columns: [[
				{
				    field: 'car_type',title: '车辆类型',width: 70,align: 'center',
				    sortable: true,
				    formatter: function(value){
				        if(value == 1){
				            return '自用车';
				        }else if(value == 2){
				        	return '备用车';
				        }
				    }
				},
				{
				    field: 'car_status',title: '替换状态',width: 70,align: 'center',
				    sortable: true,
				    formatter: function(value){
				        if(value == 1){
				            return '已替换';
				        }else if(value == 2){
				        	return '未替换';
				        }
				    }
				},
				{field: 'department_name',title: '用车部门',width: 80,align: 'center',sortable: true},
				{field: 'company_name',title: '客户',width: 180,align: 'center',sortable: true},
				{field: 'username',title: '操作人',width: 70,align: 'center',sortable: true},
				{field: 'add_time',title: '操作时间',width: 140,align: 'center',sortable: true}
            ]],
            onDblClickRow: function(rowIndex,rowData){
                CarStockIndex.edit(rowData.id);
            },
            onLoadSuccess: function (data) {
                //单元格内容悬浮提示，doCellTip()是在入口文件index.php中拓展的。
                $(this).datagrid('doCellTip', {
                    position: 'bottom',
                    maxWidth: '300px',
                    onlyShowInterrupt: true, //false时所有单元格都显示提示；true时配合specialShowFields自定义要提示的列
                    specialShowFields: [     //需要提示的列
                        //{field: 'sketch', showField: 'sketch'}
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
        var searchForm = $('#search-form-car-stock-index');
        /**查询表单提交事件**/
        searchForm.submit(function(){
            var data = {};
            var searchCondition = $(this).serializeArray();
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = searchCondition[i]['value'];
            }
            $('#easyui-datagrid-car-stock-index').datagrid('load',data);
            return false;
        });
        searchForm.find('input[name=plate_number]').textbox({
            onChange: function(){
                searchForm.submit();
            }
        });
        searchForm.find('input[name=transact_dl]').combobox({
            valueField:'value',
            textField:'text',
            editable: false,
            panelHeight:'auto',
            data: [{"value": '',"text": '不限'},{"value": 1,"text": '已办理'},{"value": 2,"text": '未办理'}],
            onSelect: function(){
                searchForm.submit();
            }
        });
        searchForm.find('input[name=car_type]').combobox({
            valueField:'value',
            textField:'text',
            editable: false,
            panelHeight:'auto',
            data: [{"value": '',"text": '不限'},{"value": 1,"text": '自用'},{"value": 2,"text": '备用'}],
            onSelect: function(){
                searchForm.submit();
            }
        });
        searchForm.find('input[name=car_status]').combobox({
            valueField:'value',
            textField:'text',
            editable: false,
            panelHeight:'auto',
            data: [{"value": '',"text": '不限'},{"value": 1,"text": '已替换'},{"value": 2,"text": '未替换'}],
            onSelect: function(){
                searchForm.submit();
            }
        });
		searchForm.find('input[name=operating_company_id]').combobox({
        	valueField:'value',
            textField:'text',
            data: <?= json_encode($searchFormOptions['operating_company_id']); ?>,
            editable: false,
            panelHeight:'auto',
            onSelect: function(){
                searchForm.submit();
            }
        });
        //构建查询表单结束
		//初始化添加窗口
		$('#easyui-dialog-car-stock-index-add-dst').dialog({
        	title: '添加自用车',   
            width: '700px',   
            height: '200px',   
            closed: true,   
            cache: true,   
            modal: true,
            resizable:true,
            maximizable: true,
            buttons: [{
				text:'确定',
				iconCls:'icon-ok',
				handler:function(){
                    var form = $('#easyui-form-car-stock-add');
                    if(!form.form('validate')) return false;
					var data = form.serialize();
					$.ajax({
						type: 'post',
						url: "<?php echo yii::$app->urlManager->createUrl(['car/stock/add']); ?>",
						data: data,
						dataType: 'json',
						success: function(data){
							if(data.status){
								$.messager.alert('添加成功',data.info,'info');
								$('#easyui-dialog-car-stock-index-add-dst').dialog('close');
								$('#easyui-datagrid-car-stock-index').datagrid('reload');
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
					$('#easyui-dialog-car-stock-index-add-dst').dialog('close');
				}
			}],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
		$('#easyui-dialog-car-stock-index-add-backup').dialog({
        	title: '添加备用车',   
            width: '500px',   
            height: '200px',   
            closed: true,   
            cache: true,   
            modal: true,
            resizable:true,
            maximizable: true,
            buttons: [{
				text:'确定',
				iconCls:'icon-ok',
				handler:function(){
                    var form = $('#easyui-form-car-stock-add');
                    if(!form.form('validate')) return false;
					var data = form.serialize();
					$.ajax({
						type: 'post',
						url: "<?php echo yii::$app->urlManager->createUrl(['car/stock/add']); ?>",
						data: data,
						dataType: 'json',
						success: function(data){
							if(data.status){
								$.messager.alert('添加成功',data.info,'info');
								$('#easyui-dialog-car-stock-index-add-backup').dialog('close');
								$('#easyui-datagrid-car-stock-index').datagrid('reload');
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
					$('#easyui-dialog-car-stock-index-add-backup').dialog('close');
				}
			}],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
        //初始化替换窗口
		$('#easyui-dialog-car-stock-index-replace').dialog({
        	title: '替换车辆',   
            width: '680px',   
            height: '300px',   
            closed: true,   
            cache: true,   
            modal: true,
            resizable:true,
            maximizable: true,
            buttons: [{
				text:'确定',
				iconCls:'icon-ok',
				handler:function(){
                    var form = $('#easyui-form-car-stock-replace');
                    if(!form.form('validate')) return false;
                    var data = form.serialize();
					$.ajax({
						type: 'post',
						url: "<?php echo yii::$app->urlManager->createUrl(['car/stock/replace']); ?>",
						data: data,
						dataType: 'json',
						success: function(data){
							if(data.status){
								$.messager.alert('修改成功',data.info,'info');
								$('#easyui-dialog-car-stock-index-replace').dialog('close');
								$('#easyui-datagrid-car-stock-index').datagrid('reload');
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
					$('#easyui-dialog-car-stock-index-replace').dialog('close');
				}
            }],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
	}
	CarStockIndex.init();

	
	//获取选择的记录
    //参数all = true标示是否要返回所有被选择的记录
	CarStockIndex.getSelected = function(all){
		var datagrid = $('#easyui-datagrid-car-stock-index');
        if(all){
            var selectRows = datagrid.datagrid('getSelections');
            if(selectRows.length <= 0){
                $.messager.alert('错误','请选择要操作的记录','error');   
                return false;
            }
            return selectRows;
        }else{
            var selectRow = datagrid.datagrid('getSelected');
            if(!selectRow){
                $.messager.alert('错误','请选择要操作的记录','error');   
                return false;
            }
            return selectRow;
        }
		
	}
	//添加方法
	CarStockIndex.addDst = function(){
		$('#easyui-dialog-car-stock-index-add-dst').dialog('open');
		$('#easyui-dialog-car-stock-index-add-dst').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/stock/add']); ?>&car_type=1");
	}
	CarStockIndex.addBackup = function(){
		$('#easyui-dialog-car-stock-index-add-backup').dialog('open');
		$('#easyui-dialog-car-stock-index-add-backup').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/stock/add']); ?>&car_type=2");
	}
	//替换
	CarStockIndex.replace = function(id){
		if(!id){
			var selectRow = this.getSelected();
            if(!selectRow){
                return false;
            }
            id = selectRow.id;
		}
		$('#easyui-dialog-car-stock-index-replace').dialog('open');
		$('#easyui-dialog-car-stock-index-replace').dialog('refresh','<?php echo yii::$app->urlManager->createUrl(['car/stock/replace']); ?>&id='+id);
	}
	//删除
	CarStockIndex.remove = function(){
		var selectRow = this.getSelected();
        if(!selectRow) return false;
        var id = selectRow.id;
		$.messager.confirm('确定删除','您确定要删除该车辆吗？',function(r){
			if(r){
				$.ajax({
					type: 'get',
					url: '<?php echo yii::$app->urlManager->createUrl(['car/stock/remove']); ?>',
					data: {id: id},
					dataType: 'json',
					success: function(data){
						if(data.status){
							$.messager.alert('删除成功',data.info,'info');
							$('#easyui-datagrid-car-stock-index').datagrid('reload');
						}else{
							$.messager.alert('删除失败',data.info,'error');   
						}
					}
				});
			}
		});
	}
	//归还
	CarStockIndex.giveBack = function(){
		var selectRow = this.getSelected();
        if(!selectRow) return false;
        var id = selectRow.id;
		$.messager.confirm('确定归还','您确定要归还该车辆吗？',function(r){
			if(r){
				$.ajax({
					type: 'get',
					url: '<?php echo yii::$app->urlManager->createUrl(['car/stock/give-back']); ?>',
					data: {id: id},
					dataType: 'json',
					success: function(data){
						if(data.status){
							$.messager.alert('归还成功',data.info,'info');
							$('#easyui-datagrid-car-stock-index').datagrid('reload');
						}else{
							$.messager.alert('归还失败',data.info,'error');   
						}
					}
				});
			}
		});
	}
    //按条件导出车辆列表
    CarStockIndex.exportWidthCondition = function(){
        var url = "<?php echo yii::$app->urlManager->createUrl(['car/stock/export-width-condition']);?>";
        var form = $('#search-form-car-stock-index');
        var data = {};
        var searchCondition = form.serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        for(var i in data){
            url += '&'+i+'='+data[i];
        }
        window.open(url);
    }
    //重置查询表单
    CarStockIndex.resetForm = function(){
        var easyuiForm = $('#search-form-car-stock-index');
        easyuiForm.form('reset');
    }
</script>