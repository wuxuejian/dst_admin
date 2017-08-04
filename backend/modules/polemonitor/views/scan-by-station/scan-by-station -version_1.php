<table id="PolemonitorIndexScanByStation_datagrid"></table> 
<div id="PolemonitorIndexScanByStation_datagridToolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="PolemonitorIndexScanByStation_searchForm">
                <ul class="search-main">
                    <li>
                        <div class="item-name">选择充电站</div>
                        <div class="item-input">
                            <select
                                id="PolemonitorIndexScanByStation_chargeStationID"
                                name="chargeStationId"
                                style="width:150px;"
                            ></select>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">电桩编号</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="code_from_compony" style="width:150px;"  />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">电桩逻辑地址</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="logic_addr" style="width:150px;"  />
                        </div>
                    </li>
                    <li class="search-button">
                        <a href="javascript:PolemonitorIndexScanByStation.reset()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</a>
                    </li>
                    <li class="search-button">
                        <a href="javascript:PolemonitorIndexScanByStation.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                    </li>
                </ul>
            </form>
        </div>
    </div>
    <?php if($buttons){ ?>
    <div class="easyui-panel" title="电桩列表" style="padding:3px 2px;" data-options="
        iconCls: 'icon-table-list',
        border: false
    ">
        <?php foreach($buttons as $val){ ?>
        <a href="javascript:<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon']; ?>'"><?= $val['text']; ?></a>
        <?php } ?>
    </div>
    <?php } ?>
</div>

<!--弹窗-->
<div id="PolemonitorIndexScanByStation_monitorChargePoleWin"></div>
<div id="PolemonitorIndexScanByStation_monitorBatteryWin"></div>
<div id="PolemonitorIndexScanByStation_monitorTotalPowerWin"></div>
<div id="PolemonitorIndexScanByStation_monitorMeterWin"></div>
<div id="PolemonitorIndexScanByStation_monitorChargeWin"></div>

<script>
    // 配置项
    var PolemonitorIndexScanByStation_configs = <?php echo json_encode($configs); ?>;

    // 请求的URL
    var PolemonitorIndexScanByStation_URL_getLIist = "<?php echo yii::$app->urlManager->createUrl(['polemonitor/scan-by-station/get-list']); ?>";
    var PolemonitorIndexScanByStation_URL_monitorChargePole = "<?php echo yii::$app->urlManager->createUrl(['polemonitor/pole/monitor-charge-pole']); ?>";
    var PolemonitorIndexScanByStation_URL_monitorBattery = "<?php echo yii::$app->urlManager->createUrl(['polemonitor/battery/monitor-battery']); ?>";
    var PolemonitorIndexScanByStation_URL_monitorTotalPower = "<?php echo yii::$app->urlManager->createUrl(['polemonitor/power/monitor-total-power']); ?>";
    var PolemonitorIndexScanByStation_URL_monitorMeter = "<?php echo yii::$app->urlManager->createUrl(['polemonitor/meter/monitor-meter']); ?>";
    var PolemonitorIndexScanByStation_URL_monitorCharge = "<?php echo yii::$app->urlManager->createUrl(['polemonitor/charge/monitor-charge']); ?>";

    var PolemonitorIndexScanByStation = {
        init: function () {
            //
            $('#PolemonitorIndexScanByStation_chargeStationID').combogrid({
                panelWidth: 420,
                panelHeight: 200,
                delay: 800,
                mode:'remote',
                idField: 'cs_id',
                textField: 'cs_name',
                value:<?= $pageInitStationId; ?>,
                url: '<?= yii::$app->urlManager->createUrl(['polemonitor/scan-by-station/get-charge-stations']); ?>',
                method: 'get',
                scrollbarSize:0,
                pagination: true,
                pageSize: 10,
                pageList: [10,20,30],
                fitColumns: true,
                columns: [[
                    {field:'cs_id',title:'电站ID',align:'center',width:30},
                    {field:'cs_code',title:'电站编号',align:'center',width:90},
                    {field:'cs_name',title:'电站名称',halign:'center',width:200}
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
                                msg:'【' + text + '】不是有效值！请重新检索并选择一个电站！'
                            }
                        );
                        _combogrid.combogrid('clear');
                    }
                }
            });
            //获取列表数据
            $('#PolemonitorIndexScanByStation_datagrid').datagrid({
                method: 'get',
                url: PolemonitorIndexScanByStation_URL_getLIist,
                fit: true,
                border: false,
                toolbar: "#PolemonitorIndexScanByStation_datagridToolbar",
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
                    {field: 'code_from_compony', title: '电桩编号', align: 'center', width: 90, sortable: true},
                    {field: 'fm_id', title: '所属前置机ID', width: 100,align: 'center',hidden: true},
                    {field: 'logic_addr', title: '电桩逻辑地址', width: 90, align: 'center', sortable: true},
                ]],
                columns: [[
                    {field: 'DEV_ID', title: '设备ID', width: 50, align: 'center', sortable: true},
                    {field: 'DEV_NAME', title: '设备名称', width: 80, align: 'center', sortable: true},
                    {field: 'DEV_ADDR', title: '设备地址', width: 70, align: 'center', sortable: true},
                    {field: 'DEV_TYPE', title: '设备类型', width: 70, align: 'center', sortable: true,
                        formatter:function(value,row,index){
                            return eval('PolemonitorIndexScanByStation_configs[103].dictItem[' + value + ']');
                        }
                    },
                    {field: 'CHARGE_TYPE', title: '电桩类型', width: 110, align: 'center', sortable: true,
                        formatter:function(value,row,index){
                            return eval('PolemonitorIndexScanByStation_configs[102].dictItem[' + value + ']');
                        }
                    },
                    {field: 'SPEAR_COUNT', title: '充电枪数', width: 60,align:'center', sortable: true,
                        formatter:function(value,row,index){
                            return eval('PolemonitorIndexScanByStation_configs[125].dictItem[' + value + ']');
                        }
                    },
                    {field: 'PRTL_TYPE', title: '协议类型', width: 90, align: 'center', sortable: true,
                        formatter:function(value,row,index){
                            return eval('PolemonitorIndexScanByStation_configs[109].dictItem[' + value + ']');
                        }
                    },
                    {field: 'CHN_ID', title: '通道ID', width: 90, align: 'center', sortable: true,
                        formatter:function(value,row,index){
                            return eval('PolemonitorIndexScanByStation_configs[110].dictItem[' + value + ']');
                        }
                    },
                    {field: 'HEART_PERIOD', title: '心跳周期(分钟)', width: 100, align: 'center', sortable: true},
                    {field: 'RATED_VOLTAGE', title: '充电机额定电压(V)', width: 110, halign: 'center',align:'right', sortable: true},
                    {field: 'RATED_CURRENT', title: '充电机额定电流(A)', width: 110, halign: 'center',align:'right', sortable: true},
                    {field: 'MAX_POWER', title: '充电机最大充电功率(KW)', width: 140, halign: 'center',align:'right', sortable: true},
                    {field: 'CHARGING_POWER', title: '电桩充电功率(KW)', width: 100, halign: 'center',align:'right', sortable: true,
                        formatter:function(value,row,index){
                            return eval('PolemonitorIndexScanByStation_configs[115].dictItem[' + value + ']');
                        }
                    },
                    {field: 'FACTORY', title: '电桩厂家', width: 150, align: 'center', sortable: true,
                        formatter:function(value,row,index){
                            return eval('PolemonitorIndexScanByStation_configs[117].dictItem[' + value + ']');
                        }
                    },
                    {field: 'SN', title: '电桩出厂编号', width: 100, align: 'center', sortable: true},
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
            $('#PolemonitorIndexScanByStation_monitorChargePoleWin').window({
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
            $('#PolemonitorIndexScanByStation_monitorBatteryWin').window({
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
            $('#PolemonitorIndexScanByStation_monitorTotalPowerWin').window({
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
            $('#PolemonitorIndexScanByStation_monitorMeterWin').window({
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
            $('#PolemonitorIndexScanByStation_monitorChargeWin').window({
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
            var datagrid = $('#PolemonitorIndexScanByStation_datagrid');
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
        // 获取当前的所选择的电站的所属前置机ID
        getCurrentFrontMachineId: function(){
            var currentChargeStation = $('#PolemonitorIndexScanByStation_chargeStationID').combogrid('grid').datagrid('getSelected');
            var fmId = currentChargeStation.cs_fm_id;
            if (parseInt(fmId) < 1) {
                $.messager.show({
                    title: '错误',
                    msg: '请先选择一个要查看的充电站！'
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
            var fmId = this.getCurrentFrontMachineId();
            var _url = PolemonitorIndexScanByStation_URL_monitorChargePole + '&fmId=' + fmId + '&devId=' + devId;
            $('#PolemonitorIndexScanByStation_monitorChargePoleWin')
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
            var fmId = this.getCurrentFrontMachineId();
            var _url = PolemonitorIndexScanByStation_URL_monitorBattery + '&fmId=' + fmId + '&devId=' + devId;
            $('#PolemonitorIndexScanByStation_monitorBatteryWin')
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
            var fmId = this.getCurrentFrontMachineId();
            var _url = PolemonitorIndexScanByStation_URL_monitorTotalPower + '&fmId=' + fmId + '&devId=' + devId;
            $('#PolemonitorIndexScanByStation_monitorTotalPowerWin')
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
            var fmId = this.getCurrentFrontMachineId();
            var _url = PolemonitorIndexScanByStation_URL_monitorMeter + '&fmId=' + fmId + '&devId=' + devId;
            $('#PolemonitorIndexScanByStation_monitorMeterWin')
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
            var fmId = this.getCurrentFrontMachineId();
            var _url = PolemonitorIndexScanByStation_URL_monitorCharge + '&fmId=' + fmId + '&devId=' + devId;
            $('#PolemonitorIndexScanByStation_monitorChargeWin')
                .window('open')
                .window('refresh',_url);
        },
        // 查询
        search: function () {
            var form = $('#PolemonitorIndexScanByStation_searchForm');
            var data = {};
            var searchCondition = form.serializeArray();
            for (var i in searchCondition) {
                data[searchCondition[i]['name']] = searchCondition[i]['value'];
            }
            $('#PolemonitorIndexScanByStation_datagrid').datagrid('load', data);
        },
        // 重置
        reset: function () {
            var form = $('#PolemonitorIndexScanByStation_searchForm');
            form.form('reset');
        }
    }

    // 执行初始化函数
    PolemonitorIndexScanByStation.init();

</script>