<table id="carmonitorDetectionIndex_datagrid"></table>
<div id="carmonitorDetectionIndex_datagridToolbar">
    <div class="easyui-panel" title="检索区域" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">
        <div class="data-search-form">
            <form id="carmonitorDetectionIndex_searchFrom">
                <ul class="search-main">
                    <li>
                        <div class="item-name">车牌号</div>
                        <div class="item-input">
                            <input class="easyui-textbox"  name="plate_number" style="width:100%;"
                                   data-options="
                                    onChange:function(){
                                        carmonitorDetectionIndex.search();
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
                                            carmonitorDetectionIndex.search();
                                        }
                                  "
                                />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">车辆品牌</div>
                        <div class="item-input">
                            <select class="easyui-combobox" name="car_brand" style="width:100%;"
                                   data-options="
                                        panelHeight:'auto',
                                        editable:false,
                                        onChange:function(){
                                            carmonitorDetectionIndex.search();
                                        }
                                  "
                                >
                            <option value="" selected="selected">--不限--</option>
                            <?php foreach($config['car_brand'] as $val){ ?>
                                <option value="<?= $val['value'] ?>"><?= $val['text'] ?></option>
                            <?php } ?>
                            </select>
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
                                            carmonitorDetectionIndex.search();
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
                                            carmonitorDetectionIndex.search();
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
                                            carmonitorDetectionIndex.search();
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
                                            carmonitorDetectionIndex.search();
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
                                            carmonitorDetectionIndex.search();
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
                                            carmonitorDetectionIndex.search();
                                        }
                                   "
                                />
                            -
                            <input class="easyui-datebox" name="detect_time_end" style="width:93px;"
                                   data-options="
                                        onChange:function(){
                                            carmonitorDetectionIndex.search();
                                        }
                                   "
                                />
                        </div>
                    </li>
                    <li class="search-button">
                        <a href="javascript:void(0)" onclick="carmonitorDetectionIndex.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                        <a href="javascript:void(0)" onclick="carmonitorDetectionIndex.reset()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</a>
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
<div id="carmonitorDetectionIndex_setParamsWin"></div>
<div id="carmonitorDetectionIndex_detectWin"></div>
<div id="carmonitorDetectionIndex_scanOriginalDataWin"></div>
<!-- 窗口 end -->

<script>
    var carmonitorDetectionIndex = {
        params:{
            'CONFIG': <?php echo json_encode($config); ?>,
            'URL': {
                'getList': '<?php echo yii::$app->urlManager->createUrl(['carmonitor/detection/get-list']); ?>',
                'setParams': '<?php echo yii::$app->urlManager->createUrl(['carmonitor/detection/set-params']); ?>',
                'detect': '<?php echo yii::$app->urlManager->createUrl(['carmonitor/detection/detect']); ?>',
                'scanOriginalData': '<?php echo yii::$app->urlManager->createUrl(['carmonitor/detection/scan-original-data']); ?>',
                'exportGridData': '<?php echo yii::$app->urlManager->createUrl(['carmonitor/detection/export-grid-data']); ?>'
            }
        },
        //初始化
        init: function() {
            //列表
            $('#carmonitorDetectionIndex_datagrid').datagrid({
                method: 'get',
                url: carmonitorDetectionIndex.params.URL.getList,
                fit: true,
                border: false,
                toolbar: "#carmonitorDetectionIndex_datagridToolbar",
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
                    {field: 'car_brand', title: '车辆品牌', width: 70, align: 'center', sortable: true,
                        formatter: function (value, row, index) {
                            try {
                                var str = 'carmonitorDetectionIndex.params.CONFIG.car_brand.' + value + '.text';
                                return eval(str);
                            } catch (e) {
                                return value;
                            }
                        }
                    },
                    {field: 'car_type', title: '车辆类型', width: 90, align: 'center', sortable: true,
                        formatter: function (value, row, index) {
                            try {
                                var str = 'carmonitorDetectionIndex.params.CONFIG.car_type.' + value + '.text';
                                return eval(str);
                            } catch (e) {
                                return value;
                            }
                        }
                    },
                    {field: 'battery_type', title: '电池类型', width: 100, align: 'center', sortable: true,
                        formatter: function (value, row, index) {
                            try {
                                var str = 'carmonitorDetectionIndex.params.CONFIG.battery_type.' + value + '.text';
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
                    {field: 'soc_deviation_res', title: '判定结果', width: 200, halign: 'center', sortable: true},
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
                    {field: 'capacitance_attenuation_res', title: '判定结果', width: 240, halign: 'center', sortable: true},
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
                    {field: 'capacitance_deviation_res', title: '判定结果', width: 110, halign: 'center', sortable: true},
                    {field: 'detect_time', title: '检测时间', align: 'center', width: 130, sortable: true}
                ]]
            });
            //【设置参数】窗口
            $('#carmonitorDetectionIndex_setParamsWin').window({
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
            $('#carmonitorDetectionIndex_detectWin').dialog({
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
                        var form = $('#carmonitorDetectionIndex_detectWin_form');
                        if(!form.form('validate')){
                            return false;
                        }
                        $('#detectFailTip').html('');
                        $.ajax({
                            "type": 'post',
                            "url": carmonitorDetectionIndex.params.URL.detect,
                            "data": form.serialize(),
                            "dataType": 'json',
                            "success": function(rData){
                                if(rData.status){
                                    $.messager.show({
                                        title: '操作成功',
                                        msg: rData.info
                                    });
                                    $('#carmonitorDetectionIndex_detectWin').dialog('close');
                                    $('#carmonitorDetectionIndex_datagrid').datagrid('reload');
                                }else{
                                    if(typeof(rData.info) == 'string'){
                                        $.messager.show({
                                            title: '操作失败',
                                            msg: rData.info
                                        });
                                    }else{
                                        var tip = '检测执行完毕，但发现以下问题：';
                                        tip += '<br>--------------------<br>';
                                        for(var i in rData.info){
                                            var item = rData.info[i];
                                            tip += item.failInfo + '<br>' + item.failCar.join(' ');
                                            tip += '<br>--------------------<br>';
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
                        $('#carmonitorDetectionIndex_detectWin').dialog('close');
                    }
                }]
            });
            //【查看原始数据】窗口
            $('#carmonitorDetectionIndex_scanOriginalDataWin').window({
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
            var datagrid = $('#carmonitorDetectionIndex_datagrid');
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
            $('#carmonitorDetectionIndex_setParamsWin')
                .window('open')
                .window('refresh',carmonitorDetectionIndex.params.URL.setParams);
        },
        //执行检测
        detect: function(){
            var selectRows = $('#carmonitorDetectionIndex_datagrid').datagrid('getSelections');
            var car_vin = '';
            if(selectRows){
                var arr = [];
                $.each(selectRows,function(i,rec){
                    arr.push(rec.car_vin);
                });
                car_vin = arr.join(' ');
            }
            $('#carmonitorDetectionIndex_detectWin')
                .dialog('open')
                .dialog('refresh',carmonitorDetectionIndex.params.URL.detect + '&car_vin=' + car_vin);
        },
        //查看原始数据
        scanOriginalData: function(){
            var selectedRow = this.getCurrentSelected();
            if(!selectedRow){
                return false;
            }
            var car_vin = selectedRow.car_vin;
            $('#carmonitorDetectionIndex_scanOriginalDataWin')
                .window('open')
                .window('refresh',carmonitorDetectionIndex.params.URL.scanOriginalData + '&car_vin=' + car_vin);
        },
        //查询
        search: function(){
            var form = $('#carmonitorDetectionIndex_searchFrom');
            var data = {};
            var searchCondition = form.serializeArray();
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = $.trim(searchCondition[i]['value']);
            }
            $('#carmonitorDetectionIndex_datagrid').datagrid('load',data);
        },
        //重置
        reset: function(){
            $('#carmonitorDetectionIndex_searchFrom').form('reset');
            carmonitorDetectionIndex.search();
        },
        //导出Excel
        exportGridData: function(){
            var form = $('#carmonitorDetectionIndex_searchFrom');
            var searchConditionStr = form.serialize();
            window.open(carmonitorDetectionIndex.params.URL.exportGridData + '&' + searchConditionStr);
        }
    }

    // 执行初始化函数
    carmonitorDetectionIndex.init();

</script>