<table id="easyui-datagrid-purchase-express-index"></table> 
<div id="easyui-datagrid-purchase-express-index-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-purchase-express-index">
                <ul class="search-main">
					<li>
                        <div class="item-name">订单编号</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="order_number" style="width:100%;"  />
                        </div>
                    </li>   
					<li>
                        <div class="item-name">到车状态</div>
                        <div class="item-input">
							<input style="width:200px;" name="purchase_arrive_status" />
                        </div>
                    </li>
					<li>
                        <div class="item-name">上牌状态</div>
                        <div class="item-input">
							<input style="width:200px;" name="purchase_on_card_status" />
                        </div>
                    </li>
					<li>
                        <div class="item-name">入库状态</div>
                        <div class="item-input">
							<input style="width:200px;" name="purchase_storage_status" />
                        </div>
                    </li>
					<li>
                        <div class="item-name">登记日期</div>
                        <div class="item-input">
                            <input class="easyui-datebox" type="text" name="start_date" style="width:93px;"
                                   data-options="
                                        onChange:function(){
                                            $('#search-form-purchase-express-index').submit();
                                        }
                                   "
                                />
                            -
                            <input class="easyui-datebox" type="text" name="end_date" style="width:93px;"
                                   data-options="
                                        onChange:function(){
                                            $('#search-form-purchase-express-index').submit();
                                        }
                                   "
                                />
                        </div>
                    </li>						
                    <li class="search-button">
                        <button type="submit" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button type="submit" onclick="PurchaseExpressIndex.resetForm()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
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
<div id="easyui-purchase-express-index-arrive"></div>
<div id="easyui-purchase-express-index-on-card"></div>
<div id="easyui-purchase-express-index-info"></div><!-- 查看详情 -->
<!-- 窗口 -->
<script>
	var PurchaseExpressIndex = new Object();
	PurchaseExpressIndex.init = function(){
		$('#easyui-datagrid-purchase-express-index').datagrid({  
			method: 'get', 
		    url:"<?php echo yii::$app->urlManager->createUrl(['purchase/purchase-express/get-list']); ?>",  
            idField: 'id',
            treeField: 'contract_number', 
			fit: true,
			border: false,
			toolbar: "#easyui-datagrid-purchase-express-index-toolbar",
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
				{field: 'contract_number',title: '合同编号',width: 150,sortable: true,halign: 'center'}
			]],
		    columns: [[          
				{field: 'order_number',title: '订单编号',width: 150,sortable: true,halign: 'center'},
				{field: 'distributor_name',title: '经销商名称',width: 150,sortable: true,halign: 'center'},
				{
					field: 'sign_time',title: '合同签订日期',
					align: 'center',sortable: true,
					formatter: function(value){
						if(!isNaN(value) && value > 0){
							return formatDateToString(value);
						} else {
							return "-";
						}
					}
				},
				{field: 'express_number',title: '物流单号',sortable: true,width: 150,halign: 'center'},
				{field: 'true_delivery_time',title: '实际发车时间',sortable: true,width: 150,halign: 'center',
					formatter: function(value){
						if(!isNaN(value) && value > 0){
							return formatDateToString(value);
						} else {
							return "-";
						}
					}
				},
				{field: 'arrive_num',title: '到车登记',width: 100,halign: 'center',
					formatter: function(value, row){
						if(row.start_num == row.arrive_num){
							return "<span style='color:green'>已到车</span>";
						}else {
							return "<span style='color:red'>"+row.arrive_num+"/"+row.start_num+"</span>";
						}
					}
				},
				{field: 'on_card_num',title: '上牌登记',width: 100,halign: 'center',
					formatter: function(value, row){
						if(row.start_num == row.on_card_num){
							return "<span style='color:green'>已上牌</span>";
						}else {
							return "<span style='color:red'>"+row.on_card_num+"/"+row.start_num+"</span>";
						}
					}
				},
				{field: 'storage_num',title: '入库状态',width: 100,halign: 'center',
					formatter: function(value, row){
						if(row.start_num == row.storage_num){
							return "<span style='color:green'>已入库</span>";
						}else {
							return "<span style='color:red'>"+row.storage_num+"/"+row.start_num+"</span>";
						}
					}
				},							
				{
					field: 'add_time',title: '登记时间',
					align: 'center',sortable: true,
					formatter: function(value){
						if(!isNaN(value) && value > 0){
							return formatDateToString(value);
						} else {
							return "";
						}
					}
				},
				{field: 'operating_company_name',title: '接受方',sortable: true,halign: 'center'},
				{field: 'owner_name',title: '所有人',sortable: true,halign: 'center'}
            ]],
            onDblClickRow: function(rowIndex,rowData){
                PurchaseExpressIndex.edit(rowData.id);
            }
		});

		//构建查询表单
        var searchForm = $('#search-form-purchase-express-index');
        /**查询表单提交事件**/
        searchForm.submit(function(){
            var data = {};
            var searchCondition = $(this).serializeArray();
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = searchCondition[i]['value'];
            }
            $('#easyui-datagrid-purchase-express-index').datagrid('load',data);
            return false;
        });
		searchForm.find('input[name=order_number]').textbox({
            onChange: function(){
                searchForm.submit();
            }
        });
		searchForm.find('input[name=purchase_arrive_status]').combobox({
			valueField:'value',
			textField:'text',
			data: [{"value": '',"text": '不限'},{"value": '1',"text": '已到车'},{"value": '2',"text": '未到车'}],
			editable: false,
			panelHeight:'auto',
			onSelect: function(){
				searchForm.submit();
			}
		});	
		searchForm.find('input[name=purchase_on_card_status]').combobox({
			valueField:'value',
			textField:'text',
			data: [{"value": '',"text": '不限'},{"value": '1',"text": '已上牌'},{"value": '2',"text": '未上牌'}],
			editable: false,
			panelHeight:'auto',
			onSelect: function(){
				searchForm.submit();
			}
		});	
		searchForm.find('input[name=purchase_storage_status]').combobox({
			valueField:'value',
			textField:'text',
			data: [{"value": '',"text": '不限'},{"value": '1',"text": '已入库'},{"value": '2',"text": '未入库'}],
			editable: false,
			panelHeight:'auto',
			onSelect: function(){
				searchForm.submit();
			}
		});			
        searchForm.find('input[name=purchase_person]').textbox({
            onChange: function(){
                searchForm.submit();
            }
        });
        searchForm.find('input[name=area_vehicle_person]').textbox({
            onChange: function(){
                searchForm.submit();
            }
        });
        //构建查询表单结束
	
	}
	PurchaseExpressIndex.init();

	//获取选择的记录
    //参数all = true标示是否要返回所有被选择的记录
	PurchaseExpressIndex.getSelected = function(all){
		var datagrid = $('#easyui-datagrid-purchase-express-index');
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

     //到车登记窗口
    $('#easyui-purchase-express-index-arrive').dialog({
        title: '到车登记',
        width: '700px',
        height: '600px',
        closed: true,
        cache: true,
        modal: true,
        resizable:true,
        maximizable: true,
        buttons: [{
            text:'保存',
            iconCls:'icon-ok',
            handler:function(){
                var form = $('#purchase-express-arrive-form');
                if(!form.form('validate')) return false;
                var data = form.serialize();
                $.ajax({
                    type: 'post',
                    url: "<?php echo yii::$app->urlManager->createUrl(['purchase/purchase-express/arrive']); ?>",
                    data: data,
                    dataType: 'json',
                    success: function(data){
                        if(data.status){
                            $.messager.alert('添加成功',data.info,'info');
                            $('#easyui-purchase-express-index-arrive').dialog('close');
                            $('#easyui-datagrid-purchase-express-index').datagrid('reload');
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
                $('#easyui-purchase-express-index-arrive').dialog('close');
            }
        }],
        onClose: function(){
            $(this).dialog('clear');
        }
    });
    PurchaseExpressIndex.arrive = function(id){
        if(!id){
            var selectRow = this.getSelected();
            if(!selectRow){
                return false;
            }
            id = selectRow.id;
        }
        $('#easyui-purchase-express-index-arrive')
            .dialog('open')
            .dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['purchase/purchase-express/arrive']); ?>&id="+id);
    }

    //初始化查看详情窗口
    $('#easyui-purchase-express-index-info').window({
            title: '查看详情',
            width: '55%',   
            height: '60%',   
            closed: true,   
            cache: true,   
            modal: true,
            collapsible: false,
            minimizable: false, 
            maximizable: false,
            onClose: function(){
                $(this).window('clear');
            }       
        });
    //查看
    PurchaseExpressIndex.info = function(){
        var selectRow = this.getSelected();
    console.log(selectRow);
        if(!selectRow){
            return false;
        }
        var id = selectRow.id;
       // alert(id);
        $('#easyui-purchase-express-index-info').window('open');
        $('#easyui-purchase-express-index-info').window('refresh',"<?php echo yii::$app->urlManager->createUrl(['purchase/purchase-express/info']); ?>&id="+id);
    }

	 //上牌登记窗口
    $('#easyui-purchase-express-index-on-card').dialog({
        title: '上牌登记',
        width: '700px',
        height: '600px',
        closed: true,
        cache: true,
        modal: true,
        resizable:true,
        maximizable: true,
        buttons: [{
            text:'保存',
            iconCls:'icon-ok',
            handler:function(){
                var form = $('#purchase-express-on-card-form');
                if(!form.form('validate')) return false;
                var data = form.serialize();
                $.ajax({
                    type: 'post',
                    url: "<?php echo yii::$app->urlManager->createUrl(['purchase/purchase-express/on-card']); ?>",
                    data: data,
                    dataType: 'json',
                    success: function(data){
                        if(data.status){
                            $.messager.alert('保存成功',data.info,'info');
                            $('#easyui-purchase-express-index-on-card').dialog('close');
                            $('#easyui-datagrid-purchase-express-index').datagrid('reload');
                        }else{
                            $.messager.alert('保存失败',data.info,'error');
                        }
                    }
                });
            }
        },
		{
            text:'提交',
            iconCls:'icon-ok',
            handler:function(){
                var form = $('#purchase-express-on-card-form');
				form.find('input[name="is_submit"]').val(1);
                if(!form.form('validate')) return false;
                var formdata = form.serialize();


				$.ajax({
                    type: 'post',
                    url: "<?php echo yii::$app->urlManager->createUrl(['purchase/purchase-express/get-storage-msg']); ?>",
                    data: formdata,
                    dataType: 'json',
                    success: function(data){
                        if(data.status){
							$.messager.confirm('Confirm',data.info,function(r){
								if (r){
									$.ajax({
										type: 'post',
										url: "<?php echo yii::$app->urlManager->createUrl(['purchase/purchase-express/on-card']); ?>",
										data: formdata,
										dataType: 'json',
										success: function(data){
											if(data.status){
												$.messager.alert('提交成功',data.info,'info');
												$('#easyui-purchase-express-index-on-card').dialog('close');
												$('#easyui-datagrid-purchase-express-index').datagrid('reload');
											}else{
												$.messager.alert('提交失败',data.info,'error');
											}
										}
									});
								}
							});
                        }else{
                            $.messager.alert('提交失败',data.info,'error');
                        }
                    }
                });
            }
        }
		,{
            text:'取消',
            iconCls:'icon-cancel',
            handler:function(){
                $('#easyui-purchase-express-index-on-card').dialog('close');
            }
        }],
        onClose: function(){
            $(this).dialog('clear');
        }
    });
    PurchaseExpressIndex.onCard = function(){
        var selectRow = this.getSelected();
		if(!selectRow){
			return false;
		}
		id = selectRow.id;

        $('#easyui-purchase-express-index-on-card')
            .dialog('open')
            .dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['purchase/purchase-express/on-card']); ?>&id="+id);
    }
	
	//重置查询表单
    PurchaseExpressIndex.resetForm = function(){
        var easyuiForm = $('#search-form-purchase-express-index');
        easyuiForm.form('reset');
    }
</script>