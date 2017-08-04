<table id="carmonitorBatteryAlertIndex_datagrid"></table>
<div id="carmonitorBatteryAlertIndex_datagridToolbar">
    <div class="easyui-panel" title="检索区域" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">
        <div class="data-search-form">
            <form id="carmonitorBatteryAlertIndex_searchFrom">
                <ul class="search-main">
                    <li>
                        <div class="item-name">车牌号</div>
                        <div class="item-input">
                            <input class="easyui-textbox"  name="plate_number" style="width:100%;"
                                   data-options="
                                    onChange:function(){
                                        carmonitorBatteryAlertIndex.search();
                                    }
                                "
                                />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">车架号</div>
                        <div class="item-input">
                            <input class="easyui-textbox" name="car_vin" style="width:100%;"
                                   data-options="
                                        onChange:function(){
                                            carmonitorBatteryAlertIndex.search();
                                        }
                                  "
                                />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">车辆品牌</div>
                        <div class="item-input">
                            <input id="carmonitorBatteryAlertIndex_searchForm_chooseBrand" name="brand_id"  style="width:100%;"  />

                        </div>
                    </li>
                    <li>
                        <div class="item-name">车辆类型</div>
                        <div class="item-input">
                            <select class="easyui-combobox" name="car_type" style="width:100%;"
                                   data-options="
                                        panelHeight:'auto',
                                        editable:false,
                                        onChange:function(){
                                            carmonitorBatteryAlertIndex.search();
                                        }
                                  "
                                >
                            <option value="" selected="selected">--不限--</option>
                            <?php foreach($config['car_type'] as $val){ ?>
                                <option value="<?= $val['value'] ?>"><?= $val['text'] ?></option>
                            <?php } ?>
                            </select>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">电池类型</div>
                        <div class="item-input">
                            <select class="easyui-combobox" name="battery_type" style="width:100%;"
                                    data-options="
                                        panelHeight:'auto',
                                        editable:false,
                                        onChange:function(){
                                            carmonitorBatteryAlertIndex.search();
                                        }
                                  "
                                >
                                <option value="" selected="selected">--不限--</option>
                                <?php foreach($config['battery_type'] as $val){ ?>
                                    <option value="<?= $val['value'] ?>"><?= $val['text'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">处理状态</div>
                        <div class="item-input">
                            <select class="easyui-combobox" name="process_status"  style="width:100%;"
                                    data-options="
                                        panelHeight:'auto',
                                        editable:false,
                                        onChange:function(){
                                            carmonitorBatteryAlertIndex.search();
                                        }
                                    ">
                                <option value="" selected="selected">--不限--</option>
                                <option value="PROCESSED">已处理</option>
                                <option value="UNPROCESSED">未处理</option>
                                <option value="WAITFOLLOW">待跟进</option>
                            </select>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">处理时间</div>
                        <div class="item-input">
                            <input class="easyui-datebox" name="process_time_start" style="width:93px;"
                                   data-options="
                                        onChange:function(){
                                            carmonitorBatteryAlertIndex.search();
                                        }
                                   "
                                />
                            -
                            <input class="easyui-datebox" name="process_time_end" style="width:93px;"
                                   data-options="
                                        onChange:function(){
                                            carmonitorBatteryAlertIndex.search();
                                        }
                                   "
                                />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">复检结果</div>
                        <div class="item-input">
                            <select class="easyui-combobox" name="recheck_res"  style="width:100%;"
                                    data-options="
                                        panelHeight:'auto',
                                        editable:false,
                                        onChange:function(){
                                            carmonitorBatteryAlertIndex.search();
                                        }
                                    ">
                                <option value="" selected="selected">--不限--</option>
                                <option value="ABNORMAL">异常</option>
                                <option value="NORMAL">正常</option>
                            </select>
                        </div>
                    </li>
                    <li class="search-button">
                        <a href="javascript:void(0)" onclick="carmonitorBatteryAlertIndex.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                        <a href="javascript:void(0)" onclick="carmonitorBatteryAlertIndex.reset()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</a>
                    </li>
                </ul>
            </form>
        </div>
    </div>

    <?php if(isset($buttons) && !empty($buttons)){ ?>
        <div class="easyui-panel" title="数据列表" style="padding:3px 2px;width:100%;" data-options="
            iconCls: 'icon-table-list',
            border: false
        ">
            <?php foreach($buttons as $val){ ?>
                <a href="javascript:void(0)" onclick="<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon'] ;?>'"><?= $val['text'] ;?></a>
            <?php } ?>
        </div>
    <?php } ?>

</div>

<!-- 窗口 begin -->
<div id="carmonitorBatteryAlertIndex_processWin"></div>
<div id="carmonitorBatteryAlertIndex_recheckWin"></div>
<!-- 窗口 end -->

<script>
    var carmonitorBatteryAlertIndex = {
        params:{
            'CONFIG': <?php echo json_encode($config); ?>,
            'URL': {
                'getList': '<?php echo yii::$app->urlManager->createUrl(['carmonitor/battery-alert/get-list']); ?>',
                'process': '<?php echo yii::$app->urlManager->createUrl(['carmonitor/battery-alert/process']); ?>',
                'recheck': '<?php echo yii::$app->urlManager->createUrl(['carmonitor/battery-alert/recheck']); ?>',
                'exportGridData': '<?php echo yii::$app->urlManager->createUrl(['carmonitor/battery-alert/export-grid-data']); ?>'
            }
        },
        //初始化
        init: function() {
            //列表
            $('#carmonitorBatteryAlertIndex_datagrid').datagrid({
                method: 'get',
                url: carmonitorBatteryAlertIndex.params.URL.getList,
                fit: true,
                border: false,
                toolbar: "#carmonitorBatteryAlertIndex_datagridToolbar",
                pagination: true,
                loadMsg: '数据加载中...',
                striped: true,
                checkOnSelect: true,
                rownumbers: true,
                singleSelect: false,
                pageSize: 20,
                frozenColumns: [[
                    {field: 'ck', checkbox: true},
                    {field: 'id', title: 'ID', width: 40, align: 'center', hidden: true},
                    {field: 'plate_number', title: '车牌号', width: 70, align: 'center', sortable: true}
                ]],
                columns: [[
                    {field: 'car_vin', title: '车架号', width: 120, align: 'center', sortable: true},
                    {field: 'brand_id', title: '车辆品牌', width: 70, align: 'center', sortable: true},
                    {field: 'car_type', title: '车辆类型', width: 90, align: 'center', sortable: true,
                        formatter: function (value, row, index) {
                            try {
                                var str = 'carmonitorBatteryAlertIndex.params.CONFIG.car_type.' + value + '.text';
                                return eval(str);
                            } catch (e) {
                                return value;
                            }
                        }
                    },
                    {field: 'battery_type', title: '电池类型', width: 100, align: 'center', sortable: true,
                        formatter: function (value, row, index) {
                            try {
                                var str = 'carmonitorBatteryAlertIndex.params.CONFIG.battery_type.' + value + '.text';
                                return eval(str);
                            } catch (e) {
                                return value;
                            }
                        }
                    },
                    {field: 'soc_deviation_status', title: 'SOC偏移', width: 60, align: 'center', sortable: true,
                        formatter: function (value, row, index) {
                            switch (value) {
                                case 'ABNORMAL':
                                    return '<span style="color:red;">异常</span>';
                                case 'NORMAL':
                                    return '正常';
                                case 'INVALID':
                                    return '<span style="color:#ddd;">无效</span>';
                                default:
                                    return value;
                            }
                        }
                    },
                    {field: 'soc_deviation_val', title: '偏移量', width: 60, align: 'center', sortable: true},
                    {field: 'capacitance_attenuation_status', title: '电池容量衰减', width: 90, align: 'center', sortable: true,
                        formatter: function (value, row, index) {
                            switch (value) {
                                case 'ABNORMAL':
                                    return '<span style="color:red;">异常</span>';
                                case 'NORMAL':
                                    return '正常';
                                case 'INVALID':
                                    return '<span style="color:#ccc;">无效</span>';
                                default:
                                    return value;
                            }
                        }
                    },
                    {field: 'voltage_deviation_val', title: '压差偏移量', width: 80, align: 'center', sortable: true},
                    {field: 'capacitance_deviation_status', title: '电池容量偏差', width: 90, align: 'center', sortable: true,
                        formatter: function (value, row, index) {
                            switch (value) {
                                case 'ABNORMAL':
                                    return '<span style="color:red;">异常</span>';
                                case 'NORMAL':
                                    return '正常';
                                case 'INVALID':
                                    return '<span style="color:#ddd;">无效</span>';
                                default:
                                    return value;
                            }
                        }
                    },
                    {field: 'verify_time', title: '验证时间', align: 'center', width: 130, sortable: true},
                    {field: 'process_time', title: '处理时间', align: 'center', width: 130, sortable: true,
                        formatter: function(value){
                            if(value){
                                return value;
                            }else{
                                return '—';
                            }
                        }
                    },
                    {field: 'process_status', title: '处理状态', align: 'center', width: 90, sortable: true,
                        formatter: function(value){
                            switch (value) {
                                case 'PROCESSED':
                                    return '已处理';
                                case 'UNPROCESSED':
                                    return '未处理';
                                case 'WAITFOLLOW':
                                    return '待跟进';
                                default:
                                    return '—';
                            }
                        }
                    },
                    {field: 'recheck_res', title: '复检结果', align: 'center', width: 90, sortable: true,
                        formatter: function(value){
                            switch (value) {
                                case 'ABNORMAL':
                                    return '<span style="color:red;">异常</span>';
                                case 'NORMAL':
                                    return '正常';
                                default:
                                    return '—';
                            }
                        }
                    }
                ]]
            });

            // 初始化【车辆品牌】combotree
            $('#carmonitorBatteryAlertIndex_searchForm_chooseBrand').combotree({
                url: "<?php echo yii::$app->urlManager->createUrl(['car/combotree/get-car-brands']); ?>&isShowNotLimitOption=1",
                panelHeight: 'auto',
                valueField: 'id',
                textField: 'text',
                editable: false,
                onChange:function(){
                    carmonitorBatteryAlertIndex.search();
                }
            });

            //【标记为已处理】窗口
            $('#carmonitorBatteryAlertIndex_processWin').dialog({
                title: '标记为已处理',
                width: 700,
                height: 270,
                closed: true,
                cache: true,
                modal: true,
                collapsible: false,
                minimizable: false,
                maximizable: false,
                onClose: function(){
                    $(this).window('clear');
                },
                buttons: [{
                    text: '确定',
                    iconCls: 'icon-ok',
                    handler: function () {
                        var form = $('#carmonitorBatteryAlertIndex_processWin_form');
                        if(!form.form('validate')){
                            return false;
                        }
                        $.ajax({
                            "type": 'post',
                            "url": carmonitorBatteryAlertIndex.params.URL.process,
                            "data": form.serialize(),
                            "dataType": 'json',
                            "success": function(rData){
                                if(rData.status){
                                    $.messager.show({
                                        title: '操作成功',
                                        msg: rData.info
                                    });
                                    $('#carmonitorBatteryAlertIndex_processWin').dialog('close');
                                    $('#carmonitorBatteryAlertIndex_datagrid').datagrid('reload');
                                }else{
                                    $.messager.show({
                                        title: '操作失败',
                                        msg: rData.info
                                    });
                                }
                            }
                        });
                    }
                }, {
                    text: '取消',
                    iconCls: 'icon-cancel',
                    handler: function () {
                        $('#carmonitorBatteryAlertIndex_processWin').dialog('close');
                    }
                }]
            });
            //【复检】窗口
            $('#carmonitorBatteryAlertIndex_recheckWin').window({
                title: '复检',
                width: 1000,
                height: 300,
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
        },
        //获取当前所选择的记录
        getCurrentSelected: function(multiline){
            var datagrid = $('#carmonitorBatteryAlertIndex_datagrid');
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
        //处理
        process: function(){
            var car_vin =  (this.getCurrentSelected()).car_vin;
            if(!car_vin) return false;
            $('#carmonitorBatteryAlertIndex_processWin')
                .dialog('open')
                .dialog('refresh',carmonitorBatteryAlertIndex.params.URL.process + '&car_vin=' + car_vin);
        },
        //复检
        recheck: function(){
            var car_vin =  (this.getCurrentSelected()).car_vin;
            if(!car_vin) return false;
            $('#carmonitorBatteryAlertIndex_recheckWin')
                .window('open')
                .window('refresh',carmonitorBatteryAlertIndex.params.URL.recheck + '&car_vin=' + car_vin);
        },
        //查询
        search: function(){
            var form = $('#carmonitorBatteryAlertIndex_searchFrom');
            var data = {};
            var searchCondition = form.serializeArray();
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = $.trim(searchCondition[i]['value']);
            }
            $('#carmonitorBatteryAlertIndex_datagrid').datagrid('load',data);
        },
        //重置
        reset: function(){
            $('#carmonitorBatteryAlertIndex_searchFrom').form('reset');
            carmonitorBatteryAlertIndex.search();
        },
        //导出Excel
        exportGridData: function(){
            var form = $('#carmonitorBatteryAlertIndex_searchFrom');
            var searchConditionStr = form.serialize();
            window.open(carmonitorBatteryAlertIndex.params.URL.exportGridData + '&' + searchConditionStr);
        }
    }

    // 执行初始化函数
    carmonitorBatteryAlertIndex.init();

</script>