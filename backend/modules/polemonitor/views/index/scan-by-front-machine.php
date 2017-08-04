<table id="PolemonitorIndexScanByFrontMachine_datagrid"></table> 
<div id="PolemonitorIndexScanByFrontMachine_datagridToolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="PolemonitorIndexScanByFrontMachine_searchForm">
                <ul class="search-main">
                    <li>
                        <div class="item-name">选择前置机</div>
                        <div class="item-input">
                            <select
                                id="PolemonitorIndexScanByFrontMachine_frontMachineID"
                                class="easyui-combogrid"
                                name="front_machine_id"
                                style="width:100%;"
                                data-options="
                                    panelWidth: 420,
                                    panelHeight: 200,
                                    delay: 800,
                                    mode:'remote',
                                    idField: 'id',
                                    textField: 'addr',
                                    value:<?= $defaultFrontMachineId; ?>,
                                    url: '<?= yii::$app->urlManager->createUrl(['polemonitor/combogrid/get-front-machine-list']); ?>',
                                    method: 'get',
                                    scrollbarSize:0,
                                    pagination: true,
                                    pageSize: 10,
                                    pageList: [10,20,30],
                                    fitColumns: true,
                                    rownumbers: true,
                                    columns: [[
                                        {field:'id',title:'ID',align:'center',width:30,hidden:true},
                                        {field:'addr',title:'地址',align:'center',width:100},
                                        {field:'port',title:'端口',align:'center',width:40}
                                    ]],
                                    onHidePanel:function(){
                                        var _combogrid = $(this);
                                        var value = _combogrid.combogrid('getValue');
                                        var textbox = _combogrid.combogrid('textbox');
                                        var text = textbox.val();
                                        var rows = _combogrid.combogrid('grid').datagrid('getSelections');
                                        if(text && rows.length < 1 && value == text){
                                            $.messager.show(
                                                {
                                                    title: '无效值',
                                                    msg:'【' + text + '】不是有效值！请重新检索并选择一个前置机！'
                                                }
                                            );
                                            _combogrid.combogrid('clear');
                                        }else{
                                            PolemonitorIndexScanByFrontMachine.search();
                                        }
                                    }
                                "
                            ></select>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">电桩编号</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="code_from_compony" style="width:100%;"
                                data-options="
                                    onChange:function(){
                                        PolemonitorIndexScanByFrontMachine.search();
                                    }
                                "
                                />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">电桩逻辑地址</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="logic_addr" style="width:100%;"
                                data-options="
                                    onChange:function(){
                                        PolemonitorIndexScanByFrontMachine.search();
                                    }
                                "
                                />
                        </div>
                    </li>
                    <li class="search-button">
                        <a href="javascript:void(0)" onclick="PolemonitorIndexScanByFrontMachine.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                        <a href="javascript:void(0)" onclick="PolemonitorIndexScanByFrontMachine.reset()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</a>
                    </li>
                </ul>
            </form>
        </div>
    </div>
    <?php if($buttons){ ?>
    <div class="easyui-panel" title="电桩列表" style="padding:3px 2px;width:100%;" data-options="
        iconCls: 'icon-table-list',
        border: false
    ">
        <?php foreach($buttons as $val){ ?>
        <a href="javascript:void(0)" onclick="<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon']; ?>'"><?= $val['text']; ?></a>
        <?php } ?>
    </div>
    <?php } ?>
</div>

<!--弹窗-->
<div id="PolemonitorIndexScanByFrontMachine_monitorChargePoleWin"></div>
<div id="PolemonitorIndexScanByFrontMachine_monitorBatteryWin"></div>
<div id="PolemonitorIndexScanByFrontMachine_monitorTotalPowerWin"></div>
<div id="PolemonitorIndexScanByFrontMachine_monitorMeterWin"></div>
<div id="PolemonitorIndexScanByFrontMachine_monitorChargeWin"></div>

<script>
    var PolemonitorIndexScanByFrontMachine = {
        //配置项、请求的URL
        param:{
            CONFIG: <?php echo json_encode($configs); ?>,
            URL: {
                getList: "<?php echo yii::$app->urlManager->createUrl(['polemonitor/index/get-list']); ?>",
                monitorChargePole: "<?php echo yii::$app->urlManager->createUrl(['polemonitor/pole/monitor-charge-pole']); ?>",
                monitorBattery: "<?php echo yii::$app->urlManager->createUrl(['polemonitor/battery/monitor-battery']); ?>",
                monitorTotalPower: "<?php echo yii::$app->urlManager->createUrl(['polemonitor/power/monitor-total-power']); ?>",
                monitorMeter: "<?php echo yii::$app->urlManager->createUrl(['polemonitor/meter/monitor-meter']); ?>",
                monitorCharge: "<?php echo yii::$app->urlManager->createUrl(['polemonitor/charge/monitor-charge']); ?>"
            }
        },
        init: function () {
            //获取列表数据
            $('#PolemonitorIndexScanByFrontMachine_datagrid').datagrid({
                method: 'get',
                url: PolemonitorIndexScanByFrontMachine.param.URL.getList,
                fit: true,
                border: false,
                toolbar: "#PolemonitorIndexScanByFrontMachine_datagridToolbar",
                pagination: true,
                loadMsg: '数据加载中...',
                striped: true,
                checkOnSelect: true,
                rownumbers: true,
                singleSelect: true,
                pageSize:20,
                frozenColumns: [[
                    {field: 'ck', checkbox: true},
                    {field: 'id', title: '电桩ID', width: 50, hidden: true},
                    {field: 'code_from_compony', title: '电桩编号', align: 'center', width: 80, sortable: true},
                    {field: 'fm_id', title: '所属前置机ID', width: 100,align: 'center',hidden: true},
                    {field: 'logic_addr', title: '逻辑地址', width: 80, align: 'center', sortable: true},
                ]],
                columns: [[
                    {field: 'DEV_ID', title: '设备ID', width: 50, align: 'center', sortable: true},
                    {field: 'DEV_NAME', title: '设备名称', width: 80, align: 'center', sortable: true},
                    {field: 'DEV_ADDR', title: '设备地址', width: 70, align: 'center', sortable: true},
                    {field: 'DEV_TYPE', title: '设备类型', width: 70, align: 'center', sortable: true,
                        formatter:function(value,row,index){
                            return eval('PolemonitorIndexScanByFrontMachine.param.CONFIG[103].dictItem[' + value + ']');
                        }
                    },
                    {field: 'CHARGE_TYPE', title: '电桩类型', width: 110, align: 'center', sortable: true,
                        formatter:function(value,row,index){
                            return eval('PolemonitorIndexScanByFrontMachine.param.CONFIG[102].dictItem[' + value + ']');
                        }
                    },
                    {field: 'SPEAR_COUNT', title: '充电枪数', width: 60,align:'center', sortable: true,
                        formatter:function(value,row,index){
                            return eval('PolemonitorIndexScanByFrontMachine.param.CONFIG[125].dictItem[' + value + ']');
                        }
                    },
                    {field: 'PRTL_TYPE', title: '协议类型', width: 90, align: 'center', sortable: true,
                        formatter:function(value,row,index){
                            return eval('PolemonitorIndexScanByFrontMachine.param.CONFIG[109].dictItem[' + value + ']');
                        }
                    },
                    {field: 'CHN_ID', title: '通道ID', width: 90, align: 'center', sortable: true,
                        formatter:function(value,row,index){
                            return eval('PolemonitorIndexScanByFrontMachine.param.CONFIG[110].dictItem[' + value + ']');
                        }
                    },
                    {field: 'HEART_PERIOD', title: '心跳周期(分钟)', width: 100, halign: 'center',align:'right', sortable: true},
                    {field: 'RATED_VOLTAGE', title: '额定电压(V)', width: 100, halign: 'center',align:'right', sortable: true},
                    {field: 'RATED_CURRENT', title: '额定电流(A)', width: 100, halign: 'center',align:'right', sortable: true},
                    {field: 'MAX_POWER', title: '最大充电功率(KW)', width: 120, halign: 'center',align:'right', sortable: true},
                    {field: 'CHARGING_POWER', title: '电桩充电功率(KW)', width: 100, halign: 'center',align:'right', sortable: true,
                        formatter:function(value,row,index){
                            return eval('PolemonitorIndexScanByFrontMachine.param.CONFIG[115].dictItem[' + value + ']');
                        }
                    },
                    /*{field: 'FACTORY', title: '电桩厂家', width: 150, align: 'center', sortable: true,
                        formatter:function(value,row,index){
                            return eval('PolemonitorIndexScanByFrontMachine.param.CONFIG[117].dictItem[' + value + ']');
                        }
                    },
                    {field: 'SN', title: '电桩出厂编号', width: 100, align: 'center', sortable: true},
                    */
                ]],
                onLoadSuccess: function(data){
                    if(data.errInfo){
                        $.messager.show({
                            title:'获取数据失败',
                            msg: '<span style="color:red;">' + data.errInfo + '</span>'
                        });
                    }
                }
            });
            // 初始化【电桩监控】窗口
            $('#PolemonitorIndexScanByFrontMachine_monitorChargePoleWin').window({
                title: '电桩监控数据',
                iconCls: 'icon-chart-curve',
                width: 1200,
                height: 580,
                closed: true,
                cache: true,
                modal: true,
                collapsible: false,
                minimizable: false,
                maximizable: true,
                onClose: function () {
                    $(this).window('clear');
                }
            });
            // 初始化【电池监控】窗口
            $('#PolemonitorIndexScanByFrontMachine_monitorBatteryWin').window({
                title: '电池监控数据',
                iconCls: 'icon-chart-curve',
                width: 1200,
                height: 580,
                closed: true,
                cache: true,
                modal: true,
                collapsible: false,
                minimizable: false,
                maximizable: true,
                onClose: function () {
                    $(this).window('clear');
                }
            });
            // 初始化【总电能示值监控】窗口
            $('#PolemonitorIndexScanByFrontMachine_monitorTotalPowerWin').window({
                title: '总电能示值监控数据',
                iconCls: 'icon-chart-curve',
                width: 1200,
                height: 580,
                closed: true,
                cache: true,
                modal: true,
                collapsible: false,
                minimizable: false,
                maximizable: true,
                onClose: function () {
                    $(this).window('clear');
                }
            });
            // 初始化【电表监控】窗口
            $('#PolemonitorIndexScanByFrontMachine_monitorMeterWin').window({
                title: '电表监控数据',
                iconCls: 'icon-chart-curve',
                width: 1200,
                height: 580,
                closed: true,
                cache: true,
                modal: true,
                collapsible: false,
                minimizable: false,
                maximizable: true,
                onClose: function () {
                    $(this).window('clear');
                }
            });
            // 初始化【充电计量计费监控】窗口
            $('#PolemonitorIndexScanByFrontMachine_monitorChargeWin').window({
                title: '充电计量计费监控数据',
                iconCls: 'icon-chart-curve',
                width: 1200,
                height: 580,
                closed: true,
                cache: true,
                modal: true,
                collapsible: false,
                minimizable: false,
                maximizable: true,
                onClose: function () {
                    $(this).window('clear');
                }
            });
        },
        //获取选择的记录。参数all = true标示是否要返回所有被选择的记录
        getCurrentSelected: function (all) {
            var datagrid = $('#PolemonitorIndexScanByFrontMachine_datagrid');
            var selectRows = datagrid.datagrid('getSelections');
            if (selectRows.length <= 0) {
                $.messager.show({
                    title: '请选择',
                    msg: '请选择要操作的记录！'
                });
                return false;
            }
            if (all) {
                return selectRows;
            } else {
                if (selectRows.length > 1) {
                    $.messager.show({
                        title: '提醒',
                        msg: '该功能不能批量操作！<br/>如果你选择了多条记录，则默认操作第一条记录！'
                    });
                }
                return selectRows[0];
            }
        },
        // 获取当前的所选择的前置机的ID
        getCurrentFrontMachineID: function(){
            var fmId = $('#PolemonitorIndexScanByFrontMachine_frontMachineID').combogrid('getValue');
            if (parseInt(fmId) < 1) {
                $.messager.show({
                    title: '前置机错误',
                    msg: '请先选择好前置机！'
                });
                return false;
            }
            return fmId;
        },
        // 电桩监控
        monitorChargePole: function(){
            var selectedRow = this.getCurrentSelected();
            if (!selectedRow) {
                return false;
            }
            // 传递当前前置机id和设备id去获取最新监控数据
            var devId = selectedRow.DEV_ID;
            var fmId = this.getCurrentFrontMachineID();
            var _url = PolemonitorIndexScanByFrontMachine.param.URL.monitorChargePole + '&fmId=' + fmId + '&devId=' + devId;
            $('#PolemonitorIndexScanByFrontMachine_monitorChargePoleWin')
                .window('open')
                .window('refresh',_url);
        },
        // 电池监控
        monitorBattery: function(){
            var selectedRow = this.getCurrentSelected();
            if (!selectedRow) {
                return false;
            }
            // 传递当前前置机id和设备id去获取最新监控数据
            var devId = selectedRow.DEV_ID;
            var fmId = this.getCurrentFrontMachineID();
            var _url = PolemonitorIndexScanByFrontMachine.param.URL.monitorBattery + '&fmId=' + fmId + '&devId=' + devId;
            $('#PolemonitorIndexScanByFrontMachine_monitorBatteryWin')
                .window('open')
                .window('refresh',_url);
        },
        // 总电能示值监控
        monitorTotalPower: function(){
            var selectedRow = this.getCurrentSelected();
            if (!selectedRow) {
                return false;
            }
            // 传递当前前置机id和设备id去获取最新监控数据
            var devId = selectedRow.DEV_ID;
            var fmId = this.getCurrentFrontMachineID();
            var _url = PolemonitorIndexScanByFrontMachine.param.URL.monitorTotalPower + '&fmId=' + fmId + '&devId=' + devId;
            $('#PolemonitorIndexScanByFrontMachine_monitorTotalPowerWin')
                .window('open')
                .window('refresh',_url);
        },
        // 电表监控
        monitorMeter: function(){
            var selectedRow = this.getCurrentSelected();
            if (!selectedRow) {
                return false;
            }
            // 传递当前前置机id和设备id去获取最新监控数据
            var devId = selectedRow.DEV_ID;
            var fmId = this.getCurrentFrontMachineID();
            var _url = PolemonitorIndexScanByFrontMachine.param.URL.monitorMeter + '&fmId=' + fmId + '&devId=' + devId;
            $('#PolemonitorIndexScanByFrontMachine_monitorMeterWin')
                .window('open')
                .window('refresh',_url);
        },
        // 充电计量计费监控
        monitorCharge: function(){
            var selectedRow = this.getCurrentSelected();
            if (!selectedRow) {
                return false;
            }
            // 传递当前前置机id和设备id去获取最新监控数据
            var devId = selectedRow.DEV_ID;
            var fmId = this.getCurrentFrontMachineID();
            var _url = PolemonitorIndexScanByFrontMachine.param.URL.monitorCharge + '&fmId=' + fmId + '&devId=' + devId;
            $('#PolemonitorIndexScanByFrontMachine_monitorChargeWin')
                .window('open')
                .window('refresh',_url);
        },
        // 查询
        search: function () {
            var form = $('#PolemonitorIndexScanByFrontMachine_searchForm');
            var data = {};
            var searchCondition = form.serializeArray();
            for (var i in searchCondition) {
                data[searchCondition[i]['name']] = $.trim(searchCondition[i]['value']);
            }
            $('#PolemonitorIndexScanByFrontMachine_datagrid').datagrid('load', data);
        },
        // 重置
        reset: function () {
            var form = $('#PolemonitorIndexScanByFrontMachine_searchForm');
            form.form('reset');
            PolemonitorIndexScanByFrontMachine.search();
        }
    }

    // 执行初始化函数
    PolemonitorIndexScanByFrontMachine.init();

</script>