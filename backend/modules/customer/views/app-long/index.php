<table id="easyui-datagrid-app-long-index"></table> 
<div id="easyui-datagrid-app-long-index-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-app-long-index">
                <ul class="search-main">
                    <li>
                        <div class="item-name">App账号</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="apply_customer" style="width:100%;" prompt="输入要查找的手机号"
                                   data-options="
                                        onChange:function(){
                                            AppLongIndex.search();
                                        }
                                   "
                                />
                        </div>
                    </li>
					 <li>
                        <div class="item-name">会员类型</div>
                        <div class="item-input">
                             <select class="easyui-combobox" name="category" style="width:173px;" editable="true">
							 <option value="">所有</option>  												
								<option value="2">企业会员</option>  
								<option value="1">个人会员</option>  
								<option value="0">未认证</option>  							               
							</select> 
                        </div>
                    </li>
					 <li>
                        <div class="item-name">申请用户名</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="contact_name" style="width:100%;" prompt=""
                                   data-options="
                                        onChange:function(){
                                            AppLongIndex.search();
                                        }
                                   "
                                />
                        </div>
                    </li> 
					<li>
                        <div class="item-name">联系手机</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="contact_mobile" style="width:100%;" prompt=""
                                   data-options="
                                        onChange:function(){
                                            AppLongIndex.search();
                                        }
                                   "
                                />
                        </div>
                    </li>
					
					
                    <li class="search-button">
                        <a href="javascript:void(0)" onclick="AppLongIndex.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                        <a href="javascript:void(0)" onclick="AppLongIndex.resetForm()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</a>
                    </li>
                </ul>
            </form>
        </div>
    </div>
    <?php if(!empty($buttons)){ ?>
    <div class="easyui-panel" title="数据列表" style="padding:3px 2px;width:100%;" data-options="
        iconCls: 'icon-table-list',
        border: false
    ">
        <?php foreach($buttons as $val){ ?>
        <a   href="javascript:void(0)"
             onclick="<?= $val['on_click']; ?>"
             class="easyui-linkbutton"
             data-options="iconCls:'<?= $val['icon'] ;?>'"
        ><?= $val['text'] ;?></a>
        <?php } ?>
    </div>
    <?php } ?>
</div>
<!-- 窗口 -->
<div id="easyui-dialog-app-long-index-callback"></div>
<div id="easyui-dialog-app-long-index-scan"></div>
<div id="easyui-dialog-app-long-index-edit"></div>

<!-- 窗口 -->
<script>
    var AppLongIndex = new Object();
    AppLongIndex.init = function(){
        //获取列表数据
        $('#easyui-datagrid-app-long-index').datagrid({  
            method: 'get', 
            url: "<?= yii::$app->urlManager->createUrl(['customer/app-long/get-list']); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-app-long-index-toolbar",
            pagination: true,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
			pageSize: 20,
            frozenColumns: [[
                {field: 'ck',checkbox: true}, 
                {field: 'apply_id',title: 'apply_id',hidden: true},   
                {field: 'apply_no',title: '需求单号',width: 120,align: 'center',sortable: true}
            ]],
            columns:[[
                {field: 'order_time',title: '申请时间',width: 140,halign: 'center',sortable: true},               
                {field: 'contact_name',title: '客户姓名',width: 80,align: 'center',sortable: true},
                {field: 'contact_mobile',title: '联系人手机号',width: 100,align: 'center'},
                {field: 'apply_customer',title: 'App账号',width: 120,align: 'center',sortable: true},
                {field: 'category',title: '会员类型',width: 80,align: 'center',
					formatter: function(value){                		
                    	if (value == 1) {
							return "个人";
						} else if (value == 2) {
							return "企业";
						} else {
							return "未认证";
						}
                    }
				
				},
                {field: 'total_use_car_number',title: '用车总数',width: 80,align: 'center',sortable: true},
                {field: 'total_use_car_time',title: '用车时长',width: 80,align: 'center'},
                {field: 'region_name',title: '取车城市',width: 80,align: 'center',sortable: true},
                {field: 'es_take_car_time',title: '取车时间',width: 140,align: 'center'},
                {field: 'sale_name',title: '服务专员',width: 70,halign: 'center',align:'center'},
                {field: 'manager_name',title: '业务主管',width: 70,halign: 'center',align: 'center',sortable: true
				}
                
            ]],
            onDblClickRow: function(rowIndex,rowData){
                // AppLongIndex.edit(rowData.id);
            },
            onLoadSuccess: function (data) {
                //单元格内容悬浮提示，doCellTip()是在入口文件index.php中拓展的。
                // $(this).datagrid('doCellTip', {
                    // position: 'bottom',
                    // maxWidth: '200px',
                    // onlyShowInterrupt: true, //false时所有单元格都显示提示；true时配合specialShowFields自定义要提示的列
                    // specialShowFields: [     //需要提示的列
                       
                    // ],
                    // tipStyler: {
                        // backgroundColor: '#E4F0FC',
                        // borderColor: '#87A9D0',
                        // boxShadow: '1px 1px 3px #292929'
                    // }
                // });
            }
        });
        //初始化需求指派窗口
        $('#easyui-dialog-app-long-index-edit').dialog({
            title: '需求指派',   
            width: '500px',   
            height: '300px',   
            closed: true,   
            cache: true,   
            modal: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var form = $('#easyui-form-app-long-edit');
                    if(!form.form('validate')){
                        return false;
                    }
                    var data = form.serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['customer/app-long/set-sales']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('指派成功',data.info,'info');
                                $('#easyui-dialog-app-long-index-edit').dialog('close');
                                $('#easyui-datagrid-app-long-index').datagrid('reload');
                            }else{
                                $.messager.alert('指派失败',data.info,'error');
                            }
                        }
                    });
                }
            },{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui-dialog-app-long-index-edit').dialog('close');
                }
            }] 
        });
        // 初始化回访登记窗口
        $('#easyui-dialog-app-long-index-callback').dialog({
            title:'回访登记',
			// iconCls:'icon-group-key',
            height:400,width:600,
            modal:true,
            closed:true,
            onClose: function(){
                $(this).dialog('clear');
            },
            buttons:[{
                text:'确定',
                iconCls:'icon-ok',
                handler: function(){
                    var form = $('#easyui-form-app-long-callback');
                    if(!form.form('validate')){
                        return false;
                    }
                    var data = form.serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['customer/app-long/call-back-register']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('回访登记成功',data.info,'info');
                                $('#easyui-dialog-app-long-index-callback').dialog('close');
                                $('#easyui-datagrid-app-long-index').datagrid('reload');
                            }else{
                                $.messager.alert('回访登记失败',data.info,'error');
                            }
                        }
                    });
                }
            },{
                text:'取消',
                iconCls:'icon-cancel',
                handler: function(){
                    $('#easyui-dialog-app-long-index-callback').dialog('close');
                }
            }]
        });
		$('#easyui-dialog-app-long-index-scan').dialog({
			title: '需求详情',
            width: '780px',   
            height: '500px',   
            closed: true,   
            modal: true,
            collapsible: false,
            minimizable: false, 
            maximizable: false,
            onClose: function(){
                $(this).window('clear');
            }       
		});
      
    }
    AppLongIndex.init();
    //获取选择的记录
    AppLongIndex.getSelected = function(){
        var datagrid = $('#easyui-datagrid-app-long-index');
        var selectRow = datagrid.datagrid('getSelected');
        if(!selectRow){
            $.messager.alert('错误','请选择要操作的记录','error');   
            return false;
        }
        return selectRow;
    }
    
    //需求指派
    AppLongIndex.setSales = function(id){
		
        if(!id){
            var selectRow = this.getSelected();
            if(!selectRow){
                return false;
            }
            id = selectRow.apply_id;
        }
        if(!id){
            return false;
        }
		console.log(id);
        $('#easyui-dialog-app-long-index-edit').dialog('open');
        $('#easyui-dialog-app-long-index-edit').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['customer/app-long/set-sales']); ?>&id="+id);
    }
	//回访登记
    AppLongIndex.callBack = function(id){
		
        if(!id){
            var selectRow = this.getSelected();
            if(!selectRow){
                return false;
            }
            id = selectRow.apply_id;
        }
        if(!id){
            return false;
        }
		console.log(id);
        $('#easyui-dialog-app-long-index-callback').dialog('open');
        $('#easyui-dialog-app-long-index-callback').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['customer/app-long/call-back-register']); ?>&id="+id);
    }
	//需求详情
    AppLongIndex.scan = function(id){		
        if(!id){
            var selectRow = this.getSelected();
            if(!selectRow){
                return false;
            }
            id = selectRow.apply_id;
        }
        if(!id){
            return false;
        }
		console.log(id);
        $('#easyui-dialog-app-long-index-scan').dialog('open');
        $('#easyui-dialog-app-long-index-scan').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['customer/app-long/scan']); ?>&id="+id);
    }
   	    	
    //查询
    AppLongIndex.search = function(){
        var form = $('#search-form-app-long-index');
        var data = {};
        var searchCondition = form.serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = $.trim(searchCondition[i]['value']);
        }
        $('#easyui-datagrid-app-long-index').datagrid('load',data);
    }
    
	//重置
    // AppLongIndex.reset = function(){
        // $('#search-form-app-long-index').form('reset');
		// AppLongIndex.search();
    // }
	 //重置查询表单
    AppLongIndex.resetForm = function(){
        var easyuiForm = $('#search-form-app-long-index');
        easyuiForm.form('reset');
    }
</script>