<table id="easyui-datagrid-customer-company-sms-notify-index"></table> 
<div id="easyui-datagrid-customer-company-sms-notify-index-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-customer-company-sms-notify-index">
                <ul class="search-main">
                    <li>
                        <div class="item-name">客户名称</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="company_name" style="width:100%;" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">客户号</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="company_number" style="width:100%;" />
                        </div>
                    </li>
                    <li>
                    	<div class="item-name">发送状态</div>
                        <div class="item-input">
                            <input style="width:100px;" name="is_send" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">发送日期</div>
                        <div class="item-input">
							<input class="easyui-datebox" type="text" name="start_send_time" style="width:90px;"
                                   data-options="
                                    onChange:function(){
                                        CustomerCompanySmsNotifyIndex.search();
                                    }
                                "
                            />
                            -
							<input class="easyui-datebox" type="text" name="end_send_time" style="width:90px;"
                                   data-options="
                                    onChange:function(){
                                        CustomerCompanySmsNotifyIndex.search();
                                    }
                                "
                            />
                        </div>               
                    </li>
                    <li class="search-button">
                        <a onclick="javascript:CustomerCompanySmsNotifyIndex.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                        <a onclick="javascript:CustomerCompanySmsNotifyIndex.reset()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</a>
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
            <a onclick="<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon']; ?>'"><?= $val['text']; ?></a>
        <?php } ?>
        </div>
    <?php } ?>
</div>
<!-- 窗口 -->
<div id="easyui-dialog-customer-company-sms-notify-index-edit"></div>
<!-- 窗口 -->
<script>
    var CustomerCompanySmsNotifyIndex = new Object();
    CustomerCompanySmsNotifyIndex.init = function(){
        //获取列表数据
        $('#easyui-datagrid-customer-company-sms-notify-index').datagrid({  
            method: 'get', 
            url:"<?php echo yii::$app->urlManager->createUrl(['customer/company-sms-notify/get-list']); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-customer-company-sms-notify-index-toolbar",
            pagination: true,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
            showFooter: true,
            frozenColumns: [[
                {field: 'ck',checkbox: true}, 
                {field: 'id',title: 'id',hidden: true},   
                {field: 'company_number',title: '客户号',align:'center',width: 120,sortable: true},
                {field: 'company_name',title: '客户名称',width: 200,align:'center',sortable: true}
            ]],
            columns:[[
				{field: 'car_num',title: '租车数量',width: 60,align:'center',sortable: true},
				{field: 'amount',title: '本月应缴租金',width: 100,align:'center',sortable: true},
				{field: 'delivery_time',title: '交租时间',width: 100,align:'center',sortable: true},
				{field: 'keeper_name',title: '管理人姓名',width: 120,align:'center',sortable: true},
				{field: 'keeper_mobile',title: '管理人手机',width: 100,align:'center',sortable: true},
				{field: 'is_del',title: '是否发送',width: 60,align:'center',sortable: true,
                    formatter: function(value){
                        return value==0?'是':'否';
                    }
                },
				{field: 'is_send',title: '发送状态',width: 60,align:'center',sortable: true,
                	formatter: function(value){
                        return value==1?'已发送':'未发送';
                    }
    			},
				{field: 'send_time',title: '发送时间',width: 120,align:'center',sortable: true,
    				formatter: function(value){
    					if(value){
                            return formatDateToString(value,true)
                        }
                    }
    			},
				{field: 'oper_user',title: '操作人',width: 100,align:'center',sortable: true}
            ]],
            //双击
            onDblClickRow: function(rowIndex,rowData){
                //CustomerCompanySmsNotifyIndex.edit(rowData.id);
            },
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

      	//构建查询表单
        var searchForm = $('#search-form-customer-company-sms-notify-index');
        searchForm.find('input[name=company_name]').textbox({
            onChange: function(){
            	CustomerCompanySmsNotifyIndex.search();
            }
        });
        searchForm.find('input[name=company_number]').textbox({
            onChange: function(){
            	CustomerCompanySmsNotifyIndex.search();
            }
        });
        searchForm.find('input[name=is_send]').combobox({
            valueField:'value',
            textField:'text',
            editable: false,
            panelHeight:'auto',
            data: [{"value": '',"text": '不限'},{"value": 1,"text": '已发送'},{"value": "0","text": '未发送'}],
            onSelect: function(){
            	CustomerCompanySmsNotifyIndex.search();
            }
        });
      	//初始化修改窗口
        $('#easyui-dialog-customer-company-sms-notify-index-edit').dialog({
            title: '编辑',   
            width: '500px',   
            height: '300px',   
            closed: true,   
            cache: true,   
            modal: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var form = $('#easyui-form-customer-company-sms-notify-edit');
                    if(!form.form('validate')){
                        return false;
                    }
                    var data = form.serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['customer/company-sms-notify/edit']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('修改成功',data.info,'info');
                                $('#easyui-dialog-customer-company-sms-notify-index-edit').dialog('close');
                                $('#easyui-datagrid-customer-company-sms-notify-index').datagrid('reload');
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
                    $('#easyui-dialog-customer-company-sms-notify-index-edit').dialog('close');
                }
            }] 
        });
    }
    //获取选择的记录
    CustomerCompanySmsNotifyIndex.getSelected = function(){
        var datagrid = $('#easyui-datagrid-customer-company-sms-notify-index');
        var selectRow = datagrid.datagrid('getSelected');
        if(!selectRow){
            $.messager.alert('错误','请选择要操作的记录','error');   
            return false;
        }
        return selectRow;
    }
    //查询
    CustomerCompanySmsNotifyIndex.search = function(){
        var form = $('#search-form-customer-company-sms-notify-index');
        var data = {};
        var searchCondition = form.serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        $('#easyui-datagrid-customer-company-sms-notify-index').datagrid('load',data);
    }
  	//修改
    CustomerCompanySmsNotifyIndex.edit = function(id){
        if(!id){
            var selectRow = this.getSelected();
            if(!selectRow){
                return false;
            }
            id = selectRow.id;
        }
        if(!id){
            return false;
        }
        $('#easyui-dialog-customer-company-sms-notify-index-edit').dialog('open');
        $('#easyui-dialog-customer-company-sms-notify-index-edit').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['customer/company-sms-notify/edit']); ?>&id="+id);
    }
 	 //条件导出
    CustomerCompanySmsNotifyIndex.exportWidthCondition = function(){
        var form = $('#search-form-customer-company-sms-notify-index');
        window.open("<?php echo yii::$app->urlManager->createUrl(['customer/company-sms-notify/export-width-condition']); ?>&"+form.serialize());
    }	
    //发送通知
    CustomerCompanySmsNotifyIndex.send = function(id){
    	$.messager.confirm('确定发送','&nbsp;&nbsp;系统将自动给列表中所有被选择的客户发送短信通知，短信内容为：<br/><br/>&nbsp;&nbsp;尊敬的客户：您好！感谢您选择地上铁租车。贵司共租赁我司x台车辆，本月共计需支付租金x元。请于本月x日前支付租金。逾期未支付客户，我司将根据合同规定收取相应滞纳金，谢谢配合！<br/><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;请确认是否群发短信通知？',function(r){
            if(r){
            	$.ajax({
                    type: 'post',
                    url: "<?php echo yii::$app->urlManager->createUrl(['customer/company-sms-notify/send-notify']); ?>",
                    dataType: 'json',
                    success: function(data){
                        if(data.status){
                        	$.messager.alert('提示','正在发送，请耐心等待！','info');   
                        }else{
                        	$.messager.alert('提示','发送失败！','info');
                        }
                    }
                });
            }
        });
    }
    //重置
    CustomerCompanySmsNotifyIndex.reset = function(){
        $('#search-form-customer-company-sms-notify-index').form('reset');
    }
    //执行
    CustomerCompanySmsNotifyIndex.init();
</script>