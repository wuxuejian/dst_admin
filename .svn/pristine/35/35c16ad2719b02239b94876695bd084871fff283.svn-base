<table id="carmonitorBatteryDetectionIndex_datagrid"></table>
<div id="carmonitorBatteryDetectionIndex_datagridToolbar">
    <div class="easyui-panel" title="检索区域" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">
        <div class="data-search-form">
            <form id="carmonitorBatteryDetectionIndex_searchFrom">
                <ul class="search-main">
                    <li>
                        <div class="item-name">车牌号</div>
                        <div class="item-input">
                            <input class="easyui-textbox"  name="plate_number" style="width:100%;"
                                   data-options="
                                    onChange:function(){
                                        carmonitorBatteryDetectionIndex.search();
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
                                            carmonitorBatteryDetectionIndex.search();
                                        }
                                  "
                                />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">车辆品牌</div>
                        <div class="item-input">
                            <input id="carmonitorBatteryDetectionIndex_searchForm_chooseBrand" name="brand_id"  style="width:100%;"  />
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
                                            carmonitorBatteryDetectionIndex.search();
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
                                            carmonitorBatteryDetectionIndex.search();
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
                        <div class="item-name">SOC偏移</div>
                        <div class="item-input">
                            <select class="easyui-combobox" name="soc_deviation_status"  style="width:100%;"
                                    data-options="
                                        panelHeight:'auto',
                                        editable:false,
                                        onChange:function(){
                                            carmonitorBatteryDetectionIndex.search();
                                        }
                                    ">
                                <option value="" selected="selected">--不限--</option>
                                <option value="ABNORMAL">异常</option>
                                <option value="NORMAL">正常</option>
                                <option value="INVALID">无效</option>
                            </select>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">电池容量衰减</div>
                        <div class="item-input">
                            <select class="easyui-combobox" name="capacitance_attenuation_status"  style="width:100%;"
                                    data-options="
                                        panelHeight:'auto',
                                        editable:false,
                                        onChange:function(){
                                            carmonitorBatteryDetectionIndex.search();
                                        }
                                    ">
                                <option value="" selected="selected">--不限--</option>
                                <option value="ABNORMAL">异常</option>
                                <option value="NORMAL">正常</option>
                                <option value="INVALID">无效</option>
                            </select>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">电池容量偏差</div>
                        <div class="item-input">
                            <select class="easyui-combobox" name="capacitance_deviation_status"  style="width:100%;"
                                    data-options="
                                        panelHeight:'auto',
                                        editable:false,
                                        onChange:function(){
                                            carmonitorBatteryDetectionIndex.search();
                                        }
                                    ">
                                <option value="" selected="selected">--不限--</option>
                                <option value="ABNORMAL">异常</option>
                                <option value="NORMAL">正常</option>
                                <option value="INVALID">无效</option>
                            </select>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">检测日期</div>
                        <div class="item-input">
                            <input class="easyui-datebox" name="detect_time_start" style="width:93px;"
                                   data-options="
                                        onChange:function(){
                                            carmonitorBatteryDetectionIndex.search();
                                        }
                                   "
                                />
                            -
                            <input class="easyui-datebox" name="detect_time_end" style="width:93px;"
                                   data-options="
                                        onChange:function(){
                                            carmonitorBatteryDetectionIndex.search();
                                        }
                                   "
                                />
                        </div>
                    </li>
                    <li class="search-button">
                        <a href="javascript:void(0)" onclick="carmonitorBatteryDetectionIndex.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                        <a href="javascript:void(0)" onclick="carmonitorBatteryDetectionIndex.reset()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</a>
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
<div id="carmonitorBatteryDetectionIndex_setParamsWin"></div>
<div id="carmonitorBatteryDetectionIndex_detectWin"></div>
<div id="carmonitorBatteryDetectionIndex_scanOriginalDataWin"></div>
<!-- 窗口 end -->

<script>
    var carmonitorBatteryDetectionIndex = {
        params:{
            'CONFIG': <?php echo json_encode($config); ?>,
            'URL': {
                'getList': '<?php echo yii::$app->urlManager->createUrl(['carmonitor/battery-detection/get-list']); ?>',
                'setParams': '<?php echo yii::$app->urlManager->createUrl(['carmonitor/battery-detection/set-params']); ?>',
                'detect': '<?php echo yii::$app->urlManager->createUrl(['carmonitor/battery-detection/detect']); ?>',
                'scanOriginalData': '<?php echo yii::$app->urlManager->createUrl(['carmonitor/battery-detection/scan-original-data']); ?>',
                'exportGridData': '<?php echo yii::$app->urlManager->createUrl(['carmonitor/battery-detection/export-grid-data']); ?>'
            }
        },
        //初始化
        init: function() {
            //列表
            $('#carmonitorBatteryDetectionIndex_datagrid').datagrid({
                method: 'get',
                url: carmonitorBatteryDetectionIndex.params.URL.getList,
                fit: true,
                border: false,
                toolbar: "#carmonitorBatteryDetectionIndex_datagridToolbar",
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
                                var str = 'carmonitorBatteryDetectionIndex.params.CONFIG.car_type.' + value + '.text';
                                return eval(str);
                            } catch (e) {
                                return value;
                            }
                        }
                    },
                    {field: 'battery_type', title: '电池类型', width: 100, align: 'center', sortable: true,
                        formatter: function (value, row, index) {
                            try {
                                var str = 'carmonitorBatteryDetectionIndex.params.CONFIG.battery_type.' + value + '.text';
                                return eval(str);
                            } catch (e) {
                                return value;
                            }
                        }
                    },
                    {field: 'soc_deviation_status', title: '<span style="color:#FF8000;">SOC偏移</span>', width: 60, align: 'center', sortable: true,
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
                    {field: 'soc_deviation_val', title: '偏移量', width: 60, align: 'center', sortable: true},
                    {field: 'soc_deviation_res', title: '判定结果', width: 200, halign: 'center', sortable: true},
                    {field: 'capacitance_attenuation_status', title: '<span style="color:#FF8000;">电池容量衰减</span>', width: 90, align: 'center', sortable: true,
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
                    {field: 'capacitance_attenuation_res', title: '判定结果', width: 240, halign: 'center', sortable: true},
                    {field: 'capacitance_deviation_status', title: '<span style="color:#FF8000;">电池容量偏差</span>', width: 90, align: 'center', sortable: true,
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
                    {field: 'capacitance_deviation_res', title: '判定结果', width: 150, halign: 'center', sortable: true},
                    {field: 'detect_time', title: '检测时间', align: 'center', width: 130, sortable: true}
                ]]
            });

            // 初始化【车辆品牌】combotree
            $('#carmonitorBatteryDetectionIndex_searchForm_chooseBrand').combotree({
                url: "<?php echo yii::$app->urlManager->createUrl(['car/combotree/get-car-brands']); ?>&isShowNotLimitOption=1",
                panelHeight: 'auto',
                valueField: 'id',
                textField: 'text',
                editable: false,
                onChange:function(){
                    carmonitorBatteryDetectionIndex.search();
                }
            });

            //【设置参数】窗口
            $('#carmonitorBatteryDetectionIndex_setParamsWin').window({
                title: '设置SOC偏移判定参数',
                width: 700,
                height: 400,
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
            //【执行检测】窗口
            $('#carmonitorBatteryDetectionIndex_detectWin').dialog({
                title: '检测任务设置',
                width: 850,
                height: 480,
                closed: true,
                cache: true,
                modal: true,
                maximizable: true,
                resizable: true,
                onClose: function () {
                    $(this).dialog('clear');
                },
                buttons: [{
                    text: '确定',
                    iconCls: 'icon-ok',
                    handler: function () {
                        var form = $('#carmonitorBatteryDetectionIndex_detectWin_form');
                        if(!form.form('validate')){
                            return false;
                        }
                        $('#detectFailTip').html('正在进行衰减检测，请稍等...');
                        $.ajax({
                            "type": 'post',
                            "url": carmonitorBatteryDetectionIndex.params.URL.detect,
                            "data": form.serialize(),
                            "dataType": 'json',
                            "success": function(rData){
                                if(rData.status){
                                    $.messager.show({
                                        title: '操作成功',
                                        msg: rData.info
                                    });
                                    $('#carmonitorBatteryDetectionIndex_detectWin').dialog('close');
                                    $('#carmonitorBatteryDetectionIndex_datagrid').datagrid('reload');
                                }else{
                                    if(typeof(rData.info) == 'string'){
                                        $.messager.show({
                                            title: '操作失败',
                                            msg: rData.info
                                        });
                                        $('#detectFailTip').html('');
                                    }else{
                                        var tip = '检测执行完毕，请仔细检查以下问题车辆：';
                                        tip += '<br>--------------------<br>';
                                        for(var i in rData.info){
                                            var failItem = rData.info[i];
                                            if(failItem.hasOwnProperty('failInfo')){
                                                tip += failItem.failInfo + '<br>' + failItem.failCar.join(' ');
                                                tip += '<br>--------------------<br>';
                                            }else{
                                                for(var k in failItem){
                                                    tip += failItem[k].failInfo + '<br>' + failItem[k].failCar.join(' ');
                                                    tip += '<br>--------------------<br>';
                                                }
                                            }
                                        }
                                        $('#detectFailTip').html(tip);
                                    }
                                }
                            }
                        });
                    }
                }, {
                    text: '取消',
                    iconCls: 'icon-cancel',
                    handler: function () {
                        $('#carmonitorBatteryDetectionIndex_detectWin').dialog('close');
                    }
                }]
            });
            //【查看原始数据】窗口
            $('#carmonitorBatteryDetectionIndex_scanOriginalDataWin').window({
                title: '查看原始数据',
                width: 1200,
                height: 600,
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
        },
        //获取当前所选择的记录
        getCurrentSelected: function(multiline){
            var datagrid = $('#carmonitorBatteryDetectionIndex_datagrid');
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
        //设置参数
        setParams: function(){
            $('#carmonitorBatteryDetectionIndex_setParamsWin')
                .window('open')
                .window('refresh',carmonitorBatteryDetectionIndex.params.URL.setParams);
        },
        //执行检测
        detect: function(){
            var selectRows = $('#carmonitorBatteryDetectionIndex_datagrid').datagrid('getSelections');
            var car_vin = '';
            if(selectRows){
                var arr = [];
                $.each(selectRows,function(i,rec){
                    arr.push(rec.car_vin);
                });
                car_vin = arr.join(' ');
            }
            $('#carmonitorBatteryDetectionIndex_detectWin')
                .dialog('open')
                .dialog('refresh',carmonitorBatteryDetectionIndex.params.URL.detect + '&car_vin=' + car_vin);
        },
        //查看原始数据
        scanOriginalData: function(){
            var selectedRow = this.getCurrentSelected();
            if(!selectedRow){
                return false;
            }
            var car_vin = selectedRow.car_vin;
            $('#carmonitorBatteryDetectionIndex_scanOriginalDataWin')
                .window('open')
                .window('refresh',carmonitorBatteryDetectionIndex.params.URL.scanOriginalData + '&car_vin=' + car_vin);
        },
        //查询
        search: function(){
            var form = $('#carmonitorBatteryDetectionIndex_searchFrom');
            var data = {};
            var searchCondition = form.serializeArray();
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = $.trim(searchCondition[i]['value']);
            }
            $('#carmonitorBatteryDetectionIndex_datagrid').datagrid('load',data);
        },
        //重置
        reset: function(){
            $('#carmonitorBatteryDetectionIndex_searchFrom').form('reset');
            carmonitorBatteryDetectionIndex.search();
        },
        //导出Excel
        exportGridData: function(){
            var form = $('#carmonitorBatteryDetectionIndex_searchFrom');
            var searchConditionStr = form.serialize();
            window.open(carmonitorBatteryDetectionIndex.params.URL.exportGridData + '&' + searchConditionStr);
        }
    }

    // 执行初始化函数
    carmonitorBatteryDetectionIndex.init();

</script>