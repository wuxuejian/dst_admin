<div class="easyui-panel" title="选择充电站" style="padding:10px 35px;width:100%"
     data-options="iconCls: 'icon-search',border: false">
    <select
        id="PolemonitorIndexScanByStation_polesTab_chargeStationID"
        name="stationId"
        style="width:235px;"
        ></select>
</div>

<!-- tabs begin -->
<div class="easyui-tabs" data-options="border:false,tabWidth:130" >
    <!--tab页签1-->
    <div title="充电桩">
        <table id="PolemonitorIndexScanByStation_polesTab_datagrid"></table>
        <div id="PolemonitorIndexScanByStation_polesTab_datagridToolbar">
            <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
                iconCls: 'icon-search',
                border: false
            ">
                <div class="data-search-form">
                    <form id="PolemonitorIndexScanByStation_polesTab_searchForm">
                        <ul class="search-main">
                            <li>
                                <div class="item-name">电桩编号</div>
                                <div class="item-input">
                                    <input class="easyui-textbox" type="text" name="code_from_compony" style="width:150px;"  />
                                </div>
                            </li>
                            <li>
                                <div class="item-name">逻辑地址</div>
                                <div class="item-input">
                                    <input class="easyui-textbox" type="text" name="logic_addr" style="width:150px;"  />
                                </div>
                            </li>
                            <li class="search-button">
                                <a href="javascript:PolemonitorIndexScanByStation.search('polesTab')" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                                <a href="javascript:PolemonitorIndexScanByStation.reset('polesTab')" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</a>
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
    </div>

    <!--tab页签2-->
    <div title="充电记录">
        <table id="PolemonitorIndexScanByStation_chargeRecordsTab_datagrid"></table>
        <div id="PolemonitorIndexScanByStation_chargeRecordsTab_datagridToolbar">
            <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
                iconCls: 'icon-search',
                border: false
            ">
                <div class="data-search-form">
                    <form id="PolemonitorIndexScanByStation_chargeRecordsTab_searchForm">
                        <ul class="search-main">
                            <li>
                                <div class="item-name">电桩编号</div>
                                <div class="item-input">
                                    <input class="easyui-textbox" type="text" name="code_from_compony" style="width:150px;"  />
                                </div>
                            </li>
                            <li>
                                <div class="item-name">逻辑地址</div>
                                <div class="item-input">
                                    <input class="easyui-textbox" type="text" name="logic_addr" style="width:150px;"  />
                                </div>
                            </li>
                            <li class="search-button">
                                <a href="javascript:PolemonitorIndexScanByStation.search('chargeRecordsTab')" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                                <a href="javascript:PolemonitorIndexScanByStation.reset('chargeRecordsTab')" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</a>
                            </li>
                        </ul>
                    </form>
                </div>
            </div>
            <?php if($buttons){ ?>
                <div class="easyui-panel" title="充电记录" style="padding:3px 2px;" data-options="
                iconCls: 'icon-table-list',
                border: false
            ">
                    <?php foreach($buttons as $val){ ?>
                        <a href="javascript:<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon']; ?>'"><?= $val['text']; ?></a>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<!-- tabs end -->

<!--弹窗-->
<div id="PolemonitorIndexScanByStation_polesTab_monitorChargePoleWin"></div>
<div id="PolemonitorIndexScanByStation_polesTab_monitorBatteryWin"></div>
<div id="PolemonitorIndexScanByStation_polesTab_monitorTotalPowerWin"></div>
<div id="PolemonitorIndexScanByStation_polesTab_monitorMeterWin"></div>
<div id="PolemonitorIndexScanByStation_polesTab_monitorChargeWin"></div>

<script>
    // 配置项
    var PolemonitorIndexScanByStation_polesTab_configs = <?php echo json_encode($configs); ?>;

    // 请求的URL
    var PolemonitorIndexScanByStation_polesTab_URL_getLIist = "<?php echo yii::$app->urlManager->createUrl(['polemonitor/scan-by-station/get-list']); ?>";
    var PolemonitorIndexScanByStation_polesTab_URL_monitorChargePole = "<?php echo yii::$app->urlManager->createUrl(['polemonitor/pole/monitor-charge-pole']); ?>";
    var PolemonitorIndexScanByStation_polesTab_URL_monitorBattery = "<?php echo yii::$app->urlManager->createUrl(['polemonitor/battery/monitor-battery']); ?>";
    var PolemonitorIndexScanByStation_polesTab_URL_monitorTotalPower = "<?php echo yii::$app->urlManager->createUrl(['polemonitor/power/monitor-total-power']); ?>";
    var PolemonitorIndexScanByStation_polesTab_URL_monitorMeter = "<?php echo yii::$app->urlManager->createUrl(['polemonitor/meter/monitor-meter']); ?>";
    var PolemonitorIndexScanByStation_polesTab_URL_monitorCharge = "<?php echo yii::$app->urlManager->createUrl(['polemonitor/charge/monitor-charge']); ?>";
    var PolemonitorIndexScanByStation_chargeRecordsTab_URL_getChargeRecords = "<?php echo yii::$app->urlManager->createUrl(['polemonitor/charge-record/get-charge-records']); ?>";

    var PolemonitorIndexScanByStation = {
        init: function () {
            // 初始化【选择充电站】combogrid
            $('#PolemonitorIndexScanByStation_polesTab_chargeStationID').combogrid({
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
                rownumbers: true,
                columns: [[
                    {field:'cs_id',title:'电站ID',align:'center',width:30,hidden:true},
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
                    }else{ // 重新加载表格
                        //var data = {'stationId':value};
                        //$('#PolemonitorIndexScanByStation_polesTab_datagrid').datagrid('load', data);
                        //$('#PolemonitorIndexScanByStation_chargeRecordsTab_datagrid').datagrid('load', data);
                        // 必须重写表格URL以传递新stationId（不能用上面的，否则页面中表格查询或刷新时无法获取新stationId）
                        $('#PolemonitorIndexScanByStation_polesTab_datagrid').datagrid({
                            url:PolemonitorIndexScanByStation_polesTab_URL_getLIist + '&stationId=' + value
                        });
                        $('#PolemonitorIndexScanByStation_chargeRecordsTab_datagrid').datagrid({
                            url:PolemonitorIndexScanByStation_chargeRecordsTab_URL_getChargeRecords + '&stationId=' + value
                        });
                    }
                }
            });
            // 充电桩页签-初始化【电桩列表】
            $('#PolemonitorIndexScanByStation_polesTab_datagrid').datagrid({
                method: 'get',
                url: PolemonitorIndexScanByStation_polesTab_URL_getLIist + '&stationId=' + <?php echo $pageInitStationId; ?>,
                //fit: true,
                height:350,
                border: false,
                toolbar: "#PolemonitorIndexScanByStation_polesTab_datagridToolbar",
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
                            return eval('PolemonitorIndexScanByStation_polesTab_configs[103].dictItem[' + value + ']');
                        }
                    },
                    {field: 'CHARGE_TYPE', title: '电桩类型', width: 110, align: 'center', sortable: true,
                        formatter:function(value,row,index){
                            return eval('PolemonitorIndexScanByStation_polesTab_configs[102].dictItem[' + value + ']');
                        }
                    },
                    {field: 'SPEAR_COUNT', title: '充电枪数', width: 60,align:'center', sortable: true,
                        formatter:function(value,row,index){
                            return eval('PolemonitorIndexScanByStation_polesTab_configs[125].dictItem[' + value + ']');
                        }
                    },
                    {field: 'PRTL_TYPE', title: '协议类型', width: 90, align: 'center', sortable: true,
                        formatter:function(value,row,index){
                            return eval('PolemonitorIndexScanByStation_polesTab_configs[109].dictItem[' + value + ']');
                        }
                    },
                    {field: 'CHN_ID', title: '通道ID', width: 90, align: 'center', sortable: true,
                        formatter:function(value,row,index){
                            return eval('PolemonitorIndexScanByStation_polesTab_configs[110].dictItem[' + value + ']');
                        }
                    },
                    {field: 'HEART_PERIOD', title: '心跳周期(分钟)', width: 100, align: 'center', sortable: true},
                    {field: 'RATED_VOLTAGE', title: '充电机额定电压(V)', width: 110, halign: 'center',align:'right', sortable: true},
                    {field: 'RATED_CURRENT', title: '充电机额定电流(A)', width: 110, halign: 'center',align:'right', sortable: true},
                    {field: 'MAX_POWER', title: '充电机最大充电功率(KW)', width: 140, halign: 'center',align:'right', sortable: true},
                    {field: 'CHARGING_POWER', title: '电桩充电功率(KW)', width: 100, halign: 'center',align:'right', sortable: true,
                        formatter:function(value,row,index){
                            return eval('PolemonitorIndexScanByStation_polesTab_configs[115].dictItem[' + value + ']');
                        }
                    },
                    /*
                    {field: 'FACTORY', title: '电桩厂家', width: 150, align: 'center', sortable: true,
                        formatter:function(value,row,index){
                            return eval('PolemonitorIndexScanByStation_polesTab_configs[117].dictItem[' + value + ']');
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
            // 充电桩页签-初始化【电桩监控】窗口
            $('#PolemonitorIndexScanByStation_polesTab_monitorChargePoleWin').window({
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
            // 充电桩页签-初始化【电池监控】窗口
            $('#PolemonitorIndexScanByStation_polesTab_monitorBatteryWin').window({
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
            // 充电桩页签-初始化【总电能示值监控】窗口
            $('#PolemonitorIndexScanByStation_polesTab_monitorTotalPowerWin').window({
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
            // 充电桩页签-初始化【电表监控】窗口
            $('#PolemonitorIndexScanByStation_polesTab_monitorMeterWin').window({
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
            // 充电桩页签-初始化【充电计量计费监控】窗口
            $('#PolemonitorIndexScanByStation_polesTab_monitorChargeWin').window({
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

            // 充电记录页签-初始化【充电记录列表】
            $('#PolemonitorIndexScanByStation_chargeRecordsTab_datagrid').datagrid({
                method: 'get',
                url: PolemonitorIndexScanByStation_chargeRecordsTab_URL_getChargeRecords + '&stationId=' + <?php echo $pageInitStationId; ?>,
                //fit: true,
                height:350,
                border: false,
                toolbar: "#PolemonitorIndexScanByStation_chargeRecordsTab_datagridToolbar",
                pagination: true,
                loadMsg: '数据加载中...',
                striped: true,
                checkOnSelect: true,
                rownumbers: true,
                singleSelect: false,
                pageSize:20,
                frozenColumns: [[
                    {field: 'ck', checkbox: true},
                    {field: 'DEV_ID', title: '设备ID', width: 50,align: 'center', hidden: false},
                    {field: 'DEAL_NO', title: '交易流水号', width: 80,align: 'center',sortable: true},
                ]],
                columns: [[
                    {field: 'TIME_TAG', title: '数据时间', width: 140, align: 'center', sortable: true},
                    {field: 'DEAL_TYPE', title: '交易类型', width: 60, align: 'center', sortable: true},
                    {field: 'AREA_CODE', title: '地区代码', width: 60, align: 'center', sortable: true},
                    {field: 'START_CARD_NO', title: '开始卡号', width: 120, align: 'center', sortable: true},
                    {field: 'END_CARD_NO', title: '结束卡号', width: 120, align: 'center', sortable: true},
                    {field: 'START_CARD_TYPE', title: '开始卡型', width: 60, align: 'center', sortable: true},
                    {field: 'END_CARD_TYPE', title: '结束卡型', width: 60, align: 'center', sortable: true},

                    {field: 'START_DEAL_DL', title: '开始交易电量行度(度)', width: 150, halign: 'center',align:'right', sortable: true},
                    {field: 'START_DEAL_R1_DL', title: '开始交易费率1电量行度(度)', width: 150, halign: 'center',align:'right', sortable: true},
                    {field: 'START_DEAL_R2_DL', title: '开始交易费率2电量行度(度)', width: 150, halign: 'center',align:'right', sortable: true},
                    {field: 'START_DEAL_R3_DL', title: '开始交易费率3电量行度(度)', width: 150, halign: 'center',align:'right', sortable: true},
                    {field: 'START_DEAL_R4_DL', title: '开始交易费率4电量行度(度)', width: 150, halign: 'center',align:'right', sortable: true},

                    {field: 'END_DEAL_DL', title: '结束交易电量行度(度)', width: 150, halign: 'center',align:'right', sortable: true},
                    {field: 'END_DEAL_R1_DL', title: '结束交易费率1电量行度(度)', width: 150, halign: 'center',align:'right', sortable: true},
                    {field: 'END_DEAL_R2_DL', title: '结束交易费率2电量行度(度)', width: 150, halign: 'center',align:'right', sortable: true},
                    {field: 'END_DEAL_R3_DL', title: '结束交易费率3电量行度(度)', width: 150, halign: 'center',align:'right', sortable: true},
                    {field: 'END_DEAL_R4_DL', title: '结束交易费率4电量行度(度)', width: 150, halign: 'center',align:'right', sortable: true},

                    {field: 'DEAL_R1_PRICE', title: '交易费率1电价(元)', width: 130, halign: 'center',align:'right', sortable: true},
                    {field: 'DEAL_R2_PRICE', title: '交易费率2电价(元)', width: 130, halign: 'center',align:'right', sortable: true},
                    {field: 'DEAL_R3_PRICE', title: '交易费率3电价(元)', width: 130, halign: 'center',align:'right', sortable: true},
                    {field: 'DEAL_R4_PRICE', title: '交易费率4电价(元)', width: 130, halign: 'center',align:'right', sortable: true},

                    {field: 'STOP_FEE_PRICE', title: '停车费单价(元/小时)', width: 130, halign: 'center',align:'right', sortable: true},

                    {field: 'DEAL_START_DATE', title: '交易开始时间', width: 140, align: 'center', sortable: true},
                    {field: 'DEAL_END_DATE', title: '交易结束时间', width: 140, align: 'center', sortable: true},

                    {field: 'STOP_FEE', title: '停车费(元)', width: 100, halign: 'center',align:'right', sortable: true},
                    {field: 'REMAIN_BEFORE_DEAL', title: '交易前余额(元)', width: 100, halign: 'center',align:'right', sortable: true},
                    {field: 'REMAIN_AFTER_DEAL', title: '交易后余额(元)', width: 100, halign: 'center',align:'right', sortable: true},

                    {field: 'CAR_DEAL_COUNTER', title: '卡交易计数器', width: 100, align: 'center', sortable: true},
                    {field: 'TRM_NO', title: '终端号', width: 60, align: 'center', sortable: true},
                    {field: 'CARD_VER_NO', title: '卡版本号', width: 60, align: 'center', sortable: true},
                    {field: 'POS_NO', title: 'POS机号', width: 60, align: 'center', sortable: true},
                    {field: 'CARD_STATUS', title: '卡状态码', width: 60, align: 'center', sortable: true},
                    {field: 'WRITE_TIME', title: '写库时间', width: 140, align: 'center', sortable: true},
                    {field: 'CAR_NO', title: '车号', width: 60, align: 'center', sortable: true},
                    {field: 'INNER_ID', title: '数据测量点', width: 60, align: 'center', sortable: true}
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
        },
        //获取选择的记录。参数all = true标示是否要返回所有被选择的记录
        getCurrentSelected: function (datagrid,all) {
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
            var currentChargeStation = $('#PolemonitorIndexScanByStation_polesTab_chargeStationID').combogrid('grid').datagrid('getSelected');
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
            var datagrid = $('#PolemonitorIndexScanByStation_polesTab_datagrid');
            var selectedRow = this.getCurrentSelected(datagrid);
            if (!selectedRow) {
                return false;
            }
            // 传递当前前置机id和设备id去获取最新监控数据
            var devId = selectedRow.DEV_ID;
            var fmId = this.getCurrentFrontMachineId();
            var _url = PolemonitorIndexScanByStation_polesTab_URL_monitorChargePole + '&fmId=' + fmId + '&devId=' + devId;
            $('#PolemonitorIndexScanByStation_polesTab_monitorChargePoleWin')
                .window('open')
                .window('refresh',_url);
        },
        // 电池监控
        monitorBattery: function(){
            var datagrid = $('#PolemonitorIndexScanByStation_polesTab_datagrid');
            var selectedRow = this.getCurrentSelected(datagrid);
            if (!selectedRow) {
                return false;
            }
            // 传递当前前置机id和设备id去获取最新监控数据
            var devId = selectedRow.DEV_ID;
            var fmId = this.getCurrentFrontMachineId();
            var _url = PolemonitorIndexScanByStation_polesTab_URL_monitorBattery + '&fmId=' + fmId + '&devId=' + devId;
            $('#PolemonitorIndexScanByStation_polesTab_monitorBatteryWin')
                .window('open')
                .window('refresh',_url);
        },
        // 总电能示值监控
        monitorTotalPower: function(){
            var datagrid = $('#PolemonitorIndexScanByStation_polesTab_datagrid');
            var selectedRow = this.getCurrentSelected(datagrid);
            if (!selectedRow) {
                return false;
            }
            // 传递当前前置机id和设备id去获取最新监控数据
            var devId = selectedRow.DEV_ID;
            var fmId = this.getCurrentFrontMachineId();
            var _url = PolemonitorIndexScanByStation_polesTab_URL_monitorTotalPower + '&fmId=' + fmId + '&devId=' + devId;
            $('#PolemonitorIndexScanByStation_polesTab_monitorTotalPowerWin')
                .window('open')
                .window('refresh',_url);
        },
        // 电表监控
        monitorMeter: function(){
            var datagrid = $('#PolemonitorIndexScanByStation_polesTab_datagrid');
            var selectedRow = this.getCurrentSelected(datagrid);
            if (!selectedRow) {
                return false;
            }
            // 传递当前前置机id和设备id去获取最新监控数据
            var devId = selectedRow.DEV_ID;
            var fmId = this.getCurrentFrontMachineId();
            var _url = PolemonitorIndexScanByStation_polesTab_URL_monitorMeter + '&fmId=' + fmId + '&devId=' + devId;
            $('#PolemonitorIndexScanByStation_polesTab_monitorMeterWin')
                .window('open')
                .window('refresh',_url);
        },
        // 充电计量计费监控
        monitorCharge: function(){
            var datagrid = $('#PolemonitorIndexScanByStation_polesTab_datagrid');
            var selectedRow = this.getCurrentSelected(datagrid);
            if (!selectedRow) {
                return false;
            }
            // 传递当前前置机id和设备id去获取最新监控数据
            var devId = selectedRow.DEV_ID;
            var fmId = this.getCurrentFrontMachineId();
            var _url = PolemonitorIndexScanByStation_polesTab_URL_monitorCharge + '&fmId=' + fmId + '&devId=' + devId;
            $('#PolemonitorIndexScanByStation_polesTab_monitorChargeWin')
                .window('open')
                .window('refresh',_url);
        },
        // 查询
        search: function (whichForm) {
            if(whichForm == 'polesTab'){
                var form = $('#PolemonitorIndexScanByStation_polesTab_searchForm');
            }else if(whichForm == 'chargeRecordsTab'){
                var form = $('#PolemonitorIndexScanByStation_chargeRecordsTab_searchForm');
            }
            var data = {};
            var searchCondition = form.serializeArray();
            for (var i in searchCondition) {
                data[searchCondition[i]['name']] = searchCondition[i]['value'];
            }
            if(whichForm == 'polesTab'){
                var datagrid = $('#PolemonitorIndexScanByStation_polesTab_datagrid');
            }else if(whichForm == 'chargeRecordsTab'){
                var datagrid = $('#PolemonitorIndexScanByStation_chargeRecordsTab_datagrid');
            }
            datagrid.datagrid('load', data);
        },
        // 重置
        reset: function (whichForm) {
            if(whichForm == 'polesTab'){
                var form = $('#PolemonitorIndexScanByStation_polesTab_searchForm');
            }else if(whichForm == 'chargeRecordsTab'){
                var form = $('#PolemonitorIndexScanByStation_chargeRecordsTab_searchForm');
            }
            form.form('reset');
        }
    }

    // 执行初始化函数
    PolemonitorIndexScanByStation.init();

</script>