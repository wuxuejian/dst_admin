<table id="datagrid-vip-charge-record-index"></table> 
<div id="datagrid-vip-charge-record-index-toolbar">
    <div class="easyui-panel" title="检索区域" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-from-vip-charge-record-index">
                <ul class="search-main">
                    <li>
                        <div class="item-name">订单编号</div>
                        <div class="item-input">
                            <input name="number" style="width:100%;"  />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">电卡编号</div>
                        <div class="item-input">
                            <input name="card_no" style="width:100%;"  />
                        </div>               
                    </li>
                    <li>
                        <div class="item-name">会员手机</div>
                        <div class="item-input">
                            <input name="mobile" style="width:100%;"  />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">电桩逻辑地址</div>
                        <div class="item-input">
                            <input name="logic_addr" style="width:100%;"  />
                        </div>               
                    </li>
                    <li>
                        <div class="item-name">启动状态</div>
                        <div class="item-input">
                            <input name="start_status" style="width:100%;">
                        </div>               
                    </li>
                    <li>
                        <div class="item-name">停止状态</div>
                        <div class="item-input">
                            <input name="end_status" style="width:100%;">
                        </div>               
                    </li>
                    <li class="search-button">
                        <button class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button onclick="VipChargeRecordIndex.reset();return false;" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
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
                <button onclick="<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon'] ;?>'"><?= $val['text'] ;?></button>
            <?php } ?>
        </div>
    <?php } ?>

</div>
<div id="easyui_dialog_vip_charge_record_index_exceptiondo"></div>
<div id="easyui_window_vip_charge_record_index_detail"></div>
<script>
    var VipChargeRecordIndex = new Object();
    VipChargeRecordIndex.init = function(){
        var easyuiDatagrid = $('#datagrid-vip-charge-record-index');
        var searchForm = $('#search-from-vip-charge-record-index');
        //获取列表数据
        easyuiDatagrid.datagrid({  
            method: 'get', 
            url: "<?php echo yii::$app->urlManager->createUrl(['vip/charge-record/get-list']); ?>",   
            fit: true,
            border: false,
            toolbar: "#datagrid-vip-charge-record-index-toolbar",
            pagination: true,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
            pageSize: 20,
            showFooter: true,
            frozenColumns: [[
                {field: 'ck',checkbox: true}, 
                {field: 'id',title: 'ID',align:'center',hidden:true},   
                {field: 'number',title: '订单编号',width: 170,align:'center',sortable:true}
            ]],
            columns:[[
                    {field: 'card_no',title: '电卡编号',width: 120,align:'center',sortable:true},
                    {field: 'vip_mobile',title: '会员手机',width: 90,align:'center',sortable:true},
                    {field: 'logic_addr',title: '电桩逻辑地址',width: 90,align:'center',sortable:true},
                    {field: 'measuring_point',title: '测量点号',width: 60,align:'center'},
                    {field: 'write_datetime',title: '请求时间',width: 130,align:'center',sortable:true},
                    {field: 'start_status',title: '启动状态',width: 70,
                        align:'center',sortable: true,
                        formatter: function(value){
                            switch(value){
                                case 'success':
                                    return '<span style="color:green">成功</span>';
                                case 'fail':
                                    return '<b style="color:red">失败</b>';
                                case 'timeout':
                                    return '<span style="color:#FFCC01">超时</span>';
                            }
                        }
                    },
                    {field: 'start_fail_reason',title: '启动失败原因',align:'left',width: 150},
                    {field: 'end_datetime',title: '停止时间',width: 130,align:'center',sortable: true},
                    {field: 'end_status',title: '停止状态',width: 70,
                        align:'center',sortable: true,
                        formatter: function(value){
                            switch(value){
                                case 'success':
                                    return '<span style="color:green">成功</span>';
                                case 'fail':
                                    return '<b style="color:red">失败</b>';
                                case 'timeout':
                                    return '<span style="color:#FFCC01">超时</span>';
                                case 'noaction':
                                    return '未操作';
                            }
                        }
                    },
                    {field: 'money',title: '消费金额(元)',width:90,halign:'center',align:'right',sortable: true},
                    {field: 'count_datetime',title: '结算时间',width: 130,halign:'center',align:'right',sortable: true}
/*
                    {field: 'c_amount',title: '消费金额',width: 80,align:'right',sortable: true},
                    {field: 'pay_status',title: '支付状态',width: 70,
                        align:'center',sortable: true,
                        formatter: function(value){
                            switch(value){
                                case 'wait_pay':
                                    return '等待支付';
                                case 'success':
                                    return '支付成功';
                            }
                        }
                    },
                    {field: 'fm_charge_no',title: '交易流水号',width: 80,align:'center',sortable: true}
*/
                ]]
        });
        //查询表单自动化处理
        searchForm.submit(function(){
            var data = {};
            var searchCondition = $(this).serializeArray();
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = searchCondition[i]['value'];
            }
            easyuiDatagrid.datagrid('load',data);
            return false;
        });
        searchForm.find('input[name=number]').textbox({
            onChange: function(){
                searchForm.submit();
            }
        });
        searchForm.find('input[name=card_no]').textbox({
            onChange: function(){
                searchForm.submit();
            }
        });
        searchForm.find('input[name=mobile]').textbox({
            onChange: function(){
                searchForm.submit();
            }
        });
        searchForm.find('input[name=logic_addr]').textbox({
            onChange: function(){
                searchForm.submit();
            }
        });
/*
        searchForm.find('input[name=fm_charge_no]').textbox({
            onChange: function(){
                searchForm.submit();
            }
        });
*/
        searchForm.find('input[name=start_status]').combobox({
            valueField:'value',
            textField:'text',
            data: [{"value": '',"text": '不限'},{"value": 'success',"text": '成功'},{"value": 'fail',"text": '失败'},{"value": 'timeout',"text": '超时'}],
            editable: false,
            panelHeight:'auto',
            onSelect: function(){
                searchForm.submit();
            }
        });
        searchForm.find('input[name=end_status]').combobox({
            valueField:'value',
            textField:'text',
            data: [{"value": '',"text": '不限'},{"value": 'success',"text": '成功'},{"value": 'fail',"text": '失败'},{"value": 'timeout',"text": '超时'},{"value": 'noaction',"text": '未操作'}],
            editable: false,
            panelHeight:'auto',
            onSelect: function(){
                searchForm.submit();
            }
        });
/*
        searchForm.find('input[name=pay_status]').combobox({
            valueField:'value',
            textField:'text',
            data: [{"value": '',"text": '不限'},{"value": 'wait_pay',"text": '等待支付'},{"value": 'success',"text": '支付成功'}],
            editable: false,
            panelHeight:'auto',
            onSelect: function(){
                searchForm.submit();
            }
        });
*/
        //查询表彰自动化处理结束
        //订单异常处理窗口
        $('#easyui_dialog_vip_charge_record_index_exceptiondo').dialog({
            title: '订单异常处理',
            width: 800,
            height: 400,
            closed: true,
            cache: true,
            modal: true,
            maximizable: true,
            resizable: false,
            onClose: function () {
                $(this).dialog('clear');
            },
            buttons: [{
                text: '确定',
                iconCls: 'icon-ok',
                handler: function () {
                    var easyuiForm = $('#easyui_form_vip_charge_record_exception_do');
                    if(!easyuiForm.form('validate')){
                        return false;
                    }
                    $.ajax({
                        "type": 'post',
                        "url": "<?= yii::$app->urlManager->createUrl(['vip/charge-record/exception-do']); ?>",
                        "data": easyuiForm.serialize(),
                        "dataType": 'json',
                        "success": function(rData){
                            if(rData.error == 0){
                                $.messager.alert('操作成功',rData.msg,'info');
                                $('#easyui_dialog_vip_charge_record_index_exceptiondo').dialog('close');
                                easyuiDatagrid.datagrid('reload');
                            }else{
                                $.messager.alert('错误',rData.msg,'error');
                            }
                        }
                    });
                }
            }, {
                text: '取消',
                iconCls: 'icon-cancel',
                handler: function () {
                    $('#easyui_dialog_vip_charge_record_index_exceptiondo').dialog('close');
                }
            }]
        });
        //初始化订单详细窗口
        $('#easyui_window_vip_charge_record_index_detail').window({
            title: '充值订单明细',
            width: 900,
            height: 500,
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
    }
    //获取选择的记录
    VipChargeRecordIndex.getSelected = function(){
        var datagrid = $('#datagrid-vip-charge-record-index');
        var selectRow = datagrid.datagrid('getSelected');
        if(!selectRow){
            $.messager.show({
                title: '选择操作对象',
                msg: '请先选择要操作的记录！',
                timeout: 5000,
                showType: 'slide'
            });   
            return false;
        }
        return selectRow;
    }
    //查询
    VipChargeRecordIndex.search = function(){
        var form = $('#search-from-vip-charge-record-index');
        var data = {};
        var searchCondition = form.serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        $('#datagrid-vip-charge-record-index').datagrid('load',data);
    }
    //重置
    VipChargeRecordIndex.reset = function(){
        var searchForm = $('#search-from-vip-charge-record-index');
        searchForm.form('reset');
        searchForm.submit();
    }

    //导出
    VipChargeRecordIndex.exportGridData = function(){
        var form = $('#search-from-vip-charge-record-index');
        var searchConditionStr = form.serialize();
        window.open("<?php echo yii::$app->urlManager->createUrl(['vip/charge-record/export-grid-data']); ?>" + "&" + searchConditionStr);
    }
    //异常订单处理
    VipChargeRecordIndex.exceptionDo = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return;
        }
        if(selectRow.start_status != 'success' || selectRow.pay_status != 'wait_pay'){
            $.messager.show({
                title: '无法执行操作',
                msg: '只有[启动状态]为“成功”，并且[支付状态]为“等待支付”的订单才能执行本操作！',
                timeout: 5000,
                showType: 'slide'
            });
            return false;
        }
        var eaysuiDialog = $('#easyui_dialog_vip_charge_record_index_exceptiondo');
        eaysuiDialog.dialog('open')
            .dialog('refresh',"<?= yii::$app->urlManager->createUrl(['vip/charge-record/exception-do']); ?>&id="+selectRow.id);
    }
    //订单详细
    VipChargeRecordIndex.detail = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return;
        }
        var eaysuiWindow = $('#easyui_window_vip_charge_record_index_detail');
        eaysuiWindow.window('open')
            .window('refresh',"<?= yii::$app->urlManager->createUrl(['vip/charge-record/detail']); ?>&id="+selectRow.id);
    }
    VipChargeRecordIndex.init();
    
</script>