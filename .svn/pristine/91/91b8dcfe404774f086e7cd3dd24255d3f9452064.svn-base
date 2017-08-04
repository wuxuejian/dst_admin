<table id="carmonitorBatteryMaintainIndex_datagrid"></table>
<div id="carmonitorBatteryMaintainIndex_datagridToolbar">
    <div class="easyui-panel" title="检索区域" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">
        <div class="data-search-form">
            <form id="carmonitorBatteryMaintainIndex_searchFrom">
                <ul class="search-main">
                    <li>
                        <div class="item-name">车牌号</div>
                        <div class="item-input">
                            <input class="easyui-textbox"  name="plate_number" style="width:100%;"
                                   data-options="
                                    onChange:function(){
                                        carmonitorBatteryMaintainIndex.search();
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
                                            carmonitorBatteryMaintainIndex.search();
                                        }
                                  "
                                />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">车辆品牌</div>
                        <div class="item-input">
                            <input id="carmonitorBatteryMaintainIndex_searchForm_chooseBrand" name="brand_id"  style="width:100%;"  />
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
                                            carmonitorBatteryMaintainIndex.search();
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
                                            carmonitorBatteryMaintainIndex.search();
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
                        <div class="item-name">通知用户</div>
                        <div class="item-input">
                            <select class="easyui-combobox" name="contact_name"  style="width:100%;"
                                    data-options="
                                        panelHeight:'auto',
                                        editable:false,
                                        onChange:function(){
                                            carmonitorBatteryMaintainIndex.search();
                                        }
                                    ">
                                <option value="" selected="selected">--不限--</option>
                                <option value="YES">已通知</option>
                                <option value="NO">未通知</option>
                            </select>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">通知日期</div>
                        <div class="item-input">
                            <input class="easyui-datebox" name="notice_time_start" style="width:93px;"
                                   data-options="
                                        onChange:function(){
                                            carmonitorBatteryMaintainIndex.search();
                                        }
                                   "
                                />
                            -
                            <input class="easyui-datebox" name="notice_time_end" style="width:93px;"
                                   data-options="
                                        onChange:function(){
                                            carmonitorBatteryMaintainIndex.search();
                                        }
                                   "
                                />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">执行修正</div>
                        <div class="item-input">
                            <select class="easyui-combobox" name="is_corrected"  style="width:100%;"
                                    data-options="
                                        panelHeight:'auto',
                                        editable:false,
                                        onChange:function(){
                                            carmonitorBatteryMaintainIndex.search();
                                        }
                                    ">
                                <option value="" selected="selected">--不限--</option>
                                <option value="YES">已执行</option>
                                <option value="NO">未执行</option>
                            </select>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">验证结果</div>
                        <div class="item-input">
                            <select class="easyui-combobox" name="verify_res"  style="width:100%;"
                                    data-options="
                                        panelHeight:'auto',
                                        editable:false,
                                        onChange:function(){
                                            carmonitorBatteryMaintainIndex.search();
                                        }
                                    ">
                                <option value="" selected="selected">--不限--</option>
                                <option value="ABNORMAL">异常</option>
                                <option value="NORMAL">正常</option>
                            </select>
                        </div>
                    </li>
                    <li class="search-button">
                        <a href="javascript:void(0)" onclick="carmonitorBatteryMaintainIndex.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                        <a href="javascript:void(0)" onclick="carmonitorBatteryMaintainIndex.reset()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</a>
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
<div id="carmonitorBatteryMaintainIndex_noticeCorrectWin"></div>
<div id="carmonitorBatteryMaintainIndex_verifyCorrectWin"></div>
<!-- 窗口 end -->

<script>
    var carmonitorBatteryMaintainIndex = {
        params:{
            'CONFIG': <?php echo json_encode($config); ?>,
            'URL': {
                'getList': '<?php echo yii::$app->urlManager->createUrl(['carmonitor/battery-maintain/get-list']); ?>',
                'noticeCorrect': '<?php echo yii::$app->urlManager->createUrl(['carmonitor/battery-maintain/notice-correct']); ?>',
                'verifyCorrect': '<?php echo yii::$app->urlManager->createUrl(['carmonitor/battery-maintain/verify-correct']); ?>',
                'exportGridData': '<?php echo yii::$app->urlManager->createUrl(['carmonitor/battery-maintain/export-grid-data']); ?>'
            }
        },
        //初始化
        init: function() {
            //列表
            $('#carmonitorBatteryMaintainIndex_datagrid').datagrid({
                method: 'get',
                url: carmonitorBatteryMaintainIndex.params.URL.getList,
                fit: true,
                border: false,
                toolbar: "#carmonitorBatteryMaintainIndex_datagridToolbar",
                pagination: true,
                loadMsg: '数据加载中...',
                striped: true,
                checkOnSelect: true,
                rownumbers: true,
                singleSelect: true,
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
                                var str = 'carmonitorBatteryMaintainIndex.params.CONFIG.car_type.' + value + '.text';
                                return eval(str);
                            } catch (e) {
                                return value;
                            }
                        }
                    },
                    {field: 'battery_type', title: '电池类型', width: 100, align: 'center', sortable: true,
                        formatter: function (value, row, index) {
                            try {
                                var str = 'carmonitorBatteryMaintainIndex.params.CONFIG.battery_type.' + value + '.text';
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
                    {field: 'detect_time', title: '检测时间', align: 'center', width: 130, sortable: true},
                    {field: 'contact_name', title: '通知用户', align: 'center', width: 70, sortable: true,
                        formatter: function(value){
                            return value ? '是' : '否';
                        }
                    },
                    {field: 'notice_time', title: '通知时间', align: 'center', width: 80, sortable: true,
                        formatter: function(value){
                            if(value){
                                return value;
                            }else{
                                return '—';
                            }
                        }
                    },
                    {field: 'countdown', title: '倒计时', align: 'center', width: 60, sortable: true,
                        formatter: function(value){
                            if(value){
                                return value;
                            }else{
                                return '—';
                            }
                        }
                    },
                    {field: 'is_corrected', title: '执行慢充修正', align: 'center', width: 90, sortable: true,
                        formatter: function(value){
                            return value==1 ? '是' : '否';
                        }
                    },
                    {field: 'verify_res', title: '验证修正结果', align: 'center', width: 90, sortable: true,
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
            $('#carmonitorBatteryMaintainIndex_searchForm_chooseBrand').combotree({
                url: "<?php echo yii::$app->urlManager->createUrl(['car/combotree/get-car-brands']); ?>&isShowNotLimitOption=1",
                panelHeight: 'auto',
                valueField: 'id',
                textField: 'text',
                editable: false,
                onChange:function(){
                    carmonitorBatteryMaintainIndex.search();
                }
            });

            //【通知用户修正】窗口
            $('#carmonitorBatteryMaintainIndex_noticeCorrectWin').window({
                title: '通知用户修正',
                width: 900,
                height: 480,
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
            //【验证修正结果】窗口
            $('#carmonitorBatteryMaintainIndex_verifyCorrectWin').window({
                title: '验证修正结果',
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
            var datagrid = $('#carmonitorBatteryMaintainIndex_datagrid');
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
        //通知用户修正
        noticeCorrect: function(){
            var car_vin =  (this.getCurrentSelected()).car_vin;
            if(!car_vin) return false;
            $('#carmonitorBatteryMaintainIndex_noticeCorrectWin')
                .window('open')
                .window('refresh',carmonitorBatteryMaintainIndex.params.URL.noticeCorrect + '&car_vin=' +car_vin);
        },
        //验证修正结果
        verifyCorrect: function(){
            var car_vin =  (this.getCurrentSelected()).car_vin;
            if(!car_vin) return false;
            var is_corrected =  (this.getCurrentSelected()).is_corrected;
            if(!parseInt(is_corrected)){
                $.messager.show({
                    title: '未慢充修正SOC',
                    msg: '车辆可能尚未进行慢充修正SOC，请及时通知车辆用户执行！'
                });
                return false;
            }
            $('#carmonitorBatteryMaintainIndex_verifyCorrectWin')
                .window('open')
                .window('refresh',carmonitorBatteryMaintainIndex.params.URL.verifyCorrect + '&car_vin=' +car_vin);
        },
        //导出Excel
        exportGridData: function(){
            var form = $('#carmonitorBatteryMaintainIndex_searchFrom');
            var searchConditionStr = form.serialize();
            window.open(carmonitorBatteryMaintainIndex.params.URL.exportGridData + '&' + searchConditionStr);
        },
        //查询
        search: function(){
            var form = $('#carmonitorBatteryMaintainIndex_searchFrom');
            var data = {};
            var searchCondition = form.serializeArray();
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = $.trim(searchCondition[i]['value']);
            }
            $('#carmonitorBatteryMaintainIndex_datagrid').datagrid('load',data);
        },
        //重置
        reset: function(){
            $('#carmonitorBatteryMaintainIndex_searchFrom').form('reset');
            carmonitorBatteryMaintainIndex.search();
        }
    }

    // 执行初始化函数
    carmonitorBatteryMaintainIndex.init();

</script>