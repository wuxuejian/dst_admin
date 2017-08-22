<table id="easyui-datagrid-purchase-order-index"></table> 
<div id="easyui-datagrid-purchase-order-index-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-purchase-order-index">
                <ul class="search-main">
					<li>
                        <div class="item-name">订单编号</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="order_number" style="width:100%;"  />
                        </div>
                    </li>   
					<li>
                        <div class="item-name">登记日期</div>
                        <div class="item-input">
                            <input class="easyui-datebox" type="text" name="start_date" style="width:93px;"
                                   data-options="
                                        onChange:function(){
                                            $('#search-form-purchase-order-index').submit();
                                        }
                                   "
                                />
                            -
                            <input class="easyui-datebox" type="text" name="end_date" style="width:93px;"
                                   data-options="
                                        onChange:function(){
                                            $('#search-form-purchase-order-index').submit();
                                        }
                                   "
                                />
                        </div>
                    </li>						
                    <li class="search-button">
                        <button type="submit" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button type="submit" onclick="PurchaseOrderIndex.resetForm()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
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
<div id="easyui-dialog-purchase-order-index-add"></div>
<div id="easyui-purchase-order-index-start"></div>
<div id="easyui-purchase-order-index-start2"></div>
<div id="easyui-purchase-order-index-arrive"></div>
<div id="easyui-purchase-order-index-info"></div><!-- 查看详情 -->
<!-- 窗口 -->
<script>
	var PurchaseOrderIndex = new Object();
	PurchaseOrderIndex.init = function(){
		$('#easyui-datagrid-purchase-order-index').datagrid({  
			method: 'get', 
		    url:"<?php echo yii::$app->urlManager->createUrl(['purchase/purchase-order/get-list']); ?>",  
            idField: 'id',
            treeField: 'contract_number', 
			fit: true,
			border: false,
			toolbar: "#easyui-datagrid-purchase-order-index-toolbar",
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
				{field: 'distributor_name',title: '经销商名称',sortable: true,width: 150,halign: 'center'},
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
				{
					field: 'estimated_delivery_time',title: '预计发货时',
					align: 'center',sortable: true,
					formatter: function(value){
						if(!isNaN(value) && value > 0){
							return formatDateToString(value);
						} else {
							return "-";
						}
					}
				},
				{
					field: 'operating_company_name',title: '接受方',
					align: 'center',sortable: true
				},
				{
					field: 'owner_name',title: '所有人',
					align: 'center',sortable: true
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
				}
            ]],
            onDblClickRow: function(rowIndex,rowData){
                PurchaseOrderIndex.edit(rowData.id);
            }
		});

		//构建查询表单
        var searchForm = $('#search-form-purchase-order-index');
        /**查询表单提交事件**/
        searchForm.submit(function(){
            var data = {};
            var searchCondition = $(this).serializeArray();
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = searchCondition[i]['value'];
            }
            $('#easyui-datagrid-purchase-order-index').datagrid('load',data);
            return false;
        });
		searchForm.find('input[name=order_number]').textbox({
            onChange: function(){
                searchForm.submit();
            }
        });
        //构建查询表单结束
	
	}
	PurchaseOrderIndex.init();

	//获取选择的记录
    //参数all = true标示是否要返回所有被选择的记录
	PurchaseOrderIndex.getSelected = function(all){
		var datagrid = $('#easyui-datagrid-purchase-order-index');
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
     //初始化添加窗口
        $('#easyui-dialog-purchase-order-index-add').dialog({
            title: '采购订单登记',   
            width: '1100px',   
            height: '320px',   
            closed: true,   
            cache: true,   
            modal: true,
            resizable:true,
            maximizable: true,
            buttons: [],
            onClose: function(){
                $(this).dialog('clear');
            }
        });

    //添加方法
    PurchaseOrderIndex.add = function(){
        $('#easyui-dialog-purchase-order-index-add').dialog('open');
        $('#easyui-dialog-purchase-order-index-add').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['purchase/purchase-order/add']); ?>");
    }

    //初始化发车状态窗口
        $('#easyui-purchase-order-index-start').dialog({
            title: '发车状态登记',   
            width: '750px',   
            height: '500px',   
            closed: true,   
            cache: true,   
            modal: true,
            resizable:true,
            maximizable: true,
            buttons: [{
                text:'提交',
                iconCls:'icon-ok',
                handler:function(){
                    var form = $('#easyui-form-start');
                    if(!form.form('validate')) return false;
                    var data = form.serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['purchase/purchase-order/start']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                //$.messager.alert('添加成功',data.info,'info');
                                $('#easyui-purchase-order-index-start').dialog('close');
                                $('#easyui-datagrid-purchase-order-index').datagrid('reload');

								//
								 $('#easyui-purchase-order-index-arrive')
									.dialog('open')
									.dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['purchase/purchase-express/arrive']); ?>&id="+data.id);

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
                    $('#easyui-purchase-order-index-start').dialog('close');
                }
            }],
            onClose: function(){
                $(this).dialog('clear');
            }
        });

    //初始化发车状态窗口
    PurchaseOrderIndex.start = function(id){
		if(!id){
			var selectRow = this.getSelected();
            if(!selectRow){
                return false;
            }
            id = selectRow.id;
		}
		
        var flag = id.indexOf("Ex_");
        if(flag == 0){ 
             $('#easyui-purchase-order-index-start2')
            .dialog('open')
            .dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['purchase/purchase-order/start']); ?>&id="+id);        
           //$.messager.alert('错误','请选择采购订单，不要选择运单进行操作！','error');          
          //return ;
        }else if(flag == -1){
          $('#easyui-purchase-order-index-start')
            .dialog('open')
            .dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['purchase/purchase-order/start']); ?>&id="+id);
        } 
		
		
		
        //$('#easyui-purchase-order-index-start').dialog('open');
        //$('#easyui-purchase-order-index-start').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['purchase/purchase-order/start']); ?>");
    }

    //初始化  发车发车状态查看
        $('#easyui-purchase-order-index-start2').dialog({
            title: '发车状态查看',   
            width: '650px',   
            height: '400px',   
            closed: true,   
            cache: true,   
            modal: true,
            resizable:true,
            maximizable: true,
            buttons: [{
                text:'提交',
                iconCls:'icon-ok',
                handler:function(){
                    var form = $('#easyui-form-start2');
                    if(!form.form('validate')) return false;
                    var data = form.serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['purchase/purchase-order/start']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('添加成功',data.info,'info');
                                $('#easyui-purchase-order-index-start2').dialog('close');
                                $('#easyui-datagrid-purchase-order-index').datagrid('reload');
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
                    $('#easyui-purchase-order-index-start2').dialog('close');
                }
            }],
            onClose: function(){
                $(this).dialog('clear');
            }
        });

    //初始化查看详情窗口
    $('#easyui-purchase-order-index-info').window({
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
    PurchaseOrderIndex.info = function(){
        var selectRow = this.getSelected();
        console.log(selectRow);
        if(!selectRow){
            return false;
        }
        var id = selectRow.id;
        $('#easyui-purchase-order-index-info').window('open');
        $('#easyui-purchase-order-index-info').window('refresh',"<?php echo yii::$app->urlManager->createUrl(['purchase/purchase-order/info']); ?>&id="+id);
    }
     //到车登记窗口
    $('#easyui-purchase-order-index-arrive').dialog({
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
                            //$('#easyui-datagrid-purchase-express-index').datagrid('reload');
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
                $('#easyui-purchase-order-index-arrive').dialog('close');
            }
        }],
        onClose: function(){
            $(this).dialog('clear');
        }
    });
	
	//重置查询表单
    PurchaseOrderIndex.resetForm = function(){
        var easyuiForm = $('#search-form-purchase-order-index');
        easyuiForm.form('reset');
    }
</script>