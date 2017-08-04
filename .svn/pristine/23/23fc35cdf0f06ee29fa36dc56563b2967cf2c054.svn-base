<table id="easyui_datagrid_charge_charge_record_charge_record"></table> 
<div id="easyui_datagrid_charge_charge_record_charge_record_toolbar">
    <div class="easyui-panel" title="检索区域" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">
        <div class="data-search-form">
            <form id="search_from_datagrid_charge_charge_record_charge_record">
                <ul class="search-main">
                    <li>
                        <div class="item-name">交易流水号</div>
                        <div class="item-input">
                            <input
                                class="easyui-textbox"
                                type="text"
                                name="DEAL_NO"
                                style="width:100%;"
                                data-options="
                                    onChange:function(){
                                        VipChargeRecordChargeRecord.search();
                                    }
                                "
                            />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">电卡编号</div>
                        <div class="item-input">
                            <input
                                class="easyui-textbox"
                                type="text"
                                name="START_CARD_NO"
                                style="width:100%;"
                                data-options="
                                    onChange:function(){
                                        VipChargeRecordChargeRecord.search();
                                    }
                                "
                            />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">状态</div>
                        <div class="item-input">
                            <select
                                class="easyui-combobox"
                                name="DEAL_TYPE"
                                style="width:100%;"
                                data-options="
                                    panelHeight:'auto',
                                    editable:false,
                                    onChange:function(){
                                        VipChargeRecordChargeRecord.search();
                                    }
                                "
                            >
                                <option value="0" selected="true">正在充电</option>
                                <option value="1">结束充电</option>
                            </select>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">充电时间</div>
                        <div class="item-input">
                            <input
                                class="easyui-datebox"
                                type="text"
                                name="DEAL_START_DATE_start"
                                style="width:91px;"
                                data-options="
                                    onChange:function(){
                                        VipChargeRecordChargeRecord.search();
                                    }
                                "
                            />
                            -
                            <input
                                class="easyui-datebox"
                                type="text"
                                name="DEAL_START_DATE_end"
                                style="width:91px;"
                                data-options="
                                    onChange:function(){
                                        VipChargeRecordChargeRecord.search();
                                    }
                                "
                            />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">充电站</div>
                        <div class="item-input">
                            <select
                                id="easyui_combogrid_charge_charge_record_charge_record_cs_id"
                                name="cs_id"
                                style="width:100%;"
                            ></select>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">充电桩</div>
                        <div class="item-input">
                            <input
                                class="easyui-textbox"
                                type="text"
                                name="DEV_ADDR"
                                style="width:100%;"
                                data-options="
                                    onChange:function(){
                                        VipChargeRecordChargeRecord.search();
                                    }
                                "
                            />
                        </div>
                    </li>
                    <li class="search-button">
                        <button class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button onclick="VipChargeRecordChargeRecord.reset();return false;" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
                    </li>
                </ul>
            </form>
        </div>
    </div>
    <?php if(!empty($buttons)){ ?>
        <div class="easyui-panel" title="记录列表" style="padding:3px 2px;width:100%;" data-options="
        iconCls: 'icon-table-list',
        border: false
    ">
            <?php foreach($buttons as $val){ ?>
                <button onclick="javascript:<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon'] ;?>'"><?= $val['text'] ;?></button>
            <?php } ?>
        </div>
    <?php } ?>
</div>

<div id="VipChargeRecordChargeRecord_handleExceptionWin"></div>

<script>
    var VipChargeRecordChargeRecord = {
        // 请求的URl
        param: {
            "urlDatagrid": "<?php echo yii::$app->urlManager->createUrl(['vip/charge-record/get-charge-list']); ?>",
            //"urlExport": "<?php echo yii::$app->urlManager->createUrl(['vip/charge-record/export']); ?>",
            "urlHandleException": "<?php echo yii::$app->urlManager->createUrl(['vip/charge-record/handle-exception']); ?>",
            "easyuiDatagrid": $('#easyui_datagrid_charge_charge_record_charge_record'),
            "searchForm": $('#search_from_datagrid_charge_charge_record_charge_record')
        },
        // 初始化函数
        init: function(){
            this.param.easyuiDatagrid.datagrid({
                method: 'get',
                url: VipChargeRecordChargeRecord.param.urlDatagrid,
                queryParams: {end_status: 1},
                toolbar: "#easyui_datagrid_charge_charge_record_charge_record_toolbar",
                fit:true,
                border: false,
                pagination: true,
                loadMsg: '数据加载中...',
                striped: true,
                checkOnSelect: true,
                rownumbers: true,
                singleSelect: true,
                pageSize: 20,
                frozenColumns: [[
                    {field: 'ck', checkbox: true},
                    {field: 'ID', hidden: true},
                    {field: 'DEAL_NO', title: '交易流水号', width: 80,align: 'center'}
                ]],
                columns: [[
                    {field: 'START_CARD_NO', title: '电卡编号', width: 120, align: 'center'},
                    {field: 'DEAL_TYPE', title: '状态', width: 80, align: 'center',
                        formatter:function(v){
                            switch(parseInt(v)){
                                case 0:
                                    return '<span style="background-color:#FFCC01;color:#fff;padding:2px 5px;">正在充电</span>';
                                case 1:
                                    return '<span style="background-color:#05CD69;color:#fff;padding:2px 5px;">结束正常</span>';
                                case 2:
                                    return '<span style="background-color:#FF4040;color:#fff;padding:2px 5px;">结束异常</span>';
                            }
                        }
                    },
                    {field: 'count_status', title: '结算状态', width: 80, align: 'center',
                        formatter:function(v){
                            switch(parseInt(v)){
                                case 1:
                                    return '<span style="background-color:#05CD69;color:#fff;padding:2px 5px;">已结算</span>';
                                default:
                                    return '<span style="background-color:#FF4040;color:#fff;padding:2px 5px;">未结算</span>';
                            }
                        }
                    },
                    {field: 'cs_name', title: '充电站', width: 160, halign: 'center',align:'left'},
                    {field: 'DEV_ADDR', title: '充电桩', width: 80, align:'center'},
                    {field: 'START_DEAL_DL', title: '开始电量(度)', width: 90, halign: 'center',align:'right'},
                    {field: 'END_DEAL_DL', title: '结束电量(度)', width: 90, halign: 'center',align:'right'},
                    {field: 'c_dl', title: '<span style="color:#FF8000;">消费电量(度)</span>', width: 90, halign: 'center',align:'right'},
                    {field: 'REMAIN_BEFORE_DEAL', title: '交易前余额(元)', width: 100, halign: 'center',align:'right'},
                    {field: 'REMAIN_AFTER_DEAL', title: '交易后余额(元)', width: 100, halign: 'center',align:'right'},
                    {field: 'c_amount', title: '<span style="color:#FF8000;">消费金额(元)</span>', width: 100, halign: 'center',align:'right'},
                    {field: 'DEAL_START_DATE', title: '开始时间', width: 130, align: 'center'},
                    {field: 'DEAL_END_DATE', title: '结束时间', width: 130, align: 'center'},
                    {field: 'CAR_NO', title: '车号', width: 50, align: 'center'},
                    {field: 'INNER_ID', title: '测量点', width: 50, align: 'center'},
                    {field: 'gun_name', title: '电枪', width: 50, align: 'center'},
                    {field: 'TIME_TAG', title: '记录时间', width: 130, align: 'center'}
                ]]
            });

            //表单提交
            this.param.searchForm.submit(function(){
                var data = {};
                var searchCondition = $(this).serializeArray();
                for(var i in searchCondition){
                    data[searchCondition[i]['name']] = $.trim(searchCondition[i]['value']);
                }
                VipChargeRecordChargeRecord.param.easyuiDatagrid.datagrid('load',data);
                return false;
            });

            //获取充电站combogrid
            $('#easyui_combogrid_charge_charge_record_charge_record_cs_id').combogrid({   
                pagination: true,
                pageSize: 10,
                pageList: [10,20,30],
                fitColumns: true,
                rownumbers: true,
                delay: 800,
                panelWidth:450,  
                mode: 'remote',
                url: "<?= yii::$app->urlManager->createUrl(['system/combogrid/charge-station']); ?>",
                method: 'get',
                idField: 'cs_id',
                textField: 'cs_name',
                columns: [[
                    {field:'cs_code',title:'电站编号',width:120,sortable:true},
                    {field:'cs_name',title:'电站名称',width:300,sortable:true}
                ]],
                onChange: function(){
                    VipChargeRecordChargeRecord.search();
                }
            });

            //异常处理窗口
            $('#VipChargeRecordChargeRecord_handleExceptionWin').dialog({
                title: '异常处理',
                width: 800,
                height: 500,
                closed: true,
                cache: true,
                modal: true,
                collapsible: false,
                minimizable: false,
                maximizable: true,
                onClose: function(){
                    $(this).window('clear');
                },
                buttons: [{
                    text: '确定',
                    iconCls: 'icon-ok',
                    handler: function () {
                        var form = $('#VipChargeRecordChargeRecord_handleExceptionWin_form');
                        if(!form.form('validate')){
                            return false;
                        }

                        $.messager.confirm('请确认','请仔细检查表单数据是否填写正确！<br>您确定要继续保存表单吗？',function(r){
                            if(r){
                                $.ajax({
                                    "type": 'post',
                                    "url": VipChargeRecordChargeRecord.param.urlHandleException,
                                    "data": form.serialize(),
                                    "dataType": 'json',
                                    "success": function(rData){
                                        if(rData.status){
                                            $.messager.show({
                                                title: '操作成功',
                                                msg: rData.info
                                            });
                                            $('#VipChargeRecordChargeRecord_handleExceptionWin').dialog('close');
                                            VipChargeRecordChargeRecord.param.easyuiDatagrid.datagrid('reload');
                                        }else{
                                            $.messager.show({
                                                title: '错误',
                                                msg: rData.info
                                            });
                                        }
                                    }
                                });
                            }
                        });
                    }
                }, {
                    text: '取消',
                    iconCls: 'icon-cancel',
                    handler: function () {
                        $('#VipChargeRecordChargeRecord_handleExceptionWin').dialog('close');
                    }
                }]
            });
        },
        // 获取当前所选择的记录
        getCurrentSelected: function(multiline){
            var selectRows = this.param.easyuiDatagrid.datagrid('getSelections');
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
        // 异常处理
        handleException: function(){
            var selectRow = this.getCurrentSelected();
            if(!selectRow){
                return false;
            }
            if(selectRow.DEAL_TYPE != 0){
                $.messager.show({
                    title: '请重新选择',
                    msg: '只能操作状态为【正在充电】的记录！'
                });
                return false;
            }
            var ID = selectRow.ID;
            $('#VipChargeRecordChargeRecord_handleExceptionWin')
                .dialog('open')
                .dialog('refresh',this.param.urlHandleException + '&ID=' + ID);
        },
        // 查询
        search: function(){
            this.param.searchForm.submit();
        },
        // 重置
        reset: function(){
            this.param.searchForm.form('reset');
            this.param.searchForm.submit();
        },
        // 导出Excel
        exportGridData: function(){
            var searchConditionStr = this.param.searchForm.serialize();
            window.open(VipChargeRecordChargeRecord.param.urlExport + "&" + searchConditionStr);
        }

    }

    // 执行初始化函数
    VipChargeRecordChargeRecord.init();

</script>