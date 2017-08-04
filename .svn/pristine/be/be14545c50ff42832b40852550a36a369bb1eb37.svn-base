<table id="ThreeElectricSystemMotorMonitor_datagrid"></table>
<div id="ThreeElectricSystemMotorMonitor_datagridToolbar">
    <div class="easyui-panel" title="检索区域" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">
        <div class="data-search-form">
            <form id="ThreeElectricSystemMotorMonitor_searchFrom">
                <ul class="search-main">
                    <li>
                        <div class="item-name">电机控制器型号</div>
                        <div class="item-input">
                            <input class="easyui-textbox"  name="motor_monitor_model" style="width:100%;"
                                   data-options="
                                    onChange:function(){
                                        ThreeElectricSystemMotorMonitor.search();
                                    }
                                "
                                />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">适用电机</div>
                        <div class="item-input">
                            <select class="easyui-combobox" name="apply_motor_type" style="width:100%;"
                                    data-options="
                                        panelHeight:'auto',
                                        editable:false,
                                        onChange:function(){
                                            ThreeElectricSystemMotorMonitor.search();
                                        }
                                  "
                                >
                                <option value="" selected="selected">--不限--</option>
                                <?php foreach($config['apply_motor_type'] as $val){ ?>
                                    <option value="<?= $val['value'] ?>"><?= $val['text'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">冷却方式</div>
                        <div class="item-input">
                            <select class="easyui-combobox" name="cooling_type" style="width:100%;"
                                    data-options="
                                        panelHeight:'auto',
                                        editable:false,
                                        onChange:function(){
                                            ThreeElectricSystemMotorMonitor.search();
                                        }
                                  "
                                >
                                <option value="" selected="selected">--不限--</option>
                                <?php foreach($config['cooling_type'] as $val){ ?>
                                    <option value="<?= $val['value'] ?>"><?= $val['text'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">生产厂家</div>
                        <div class="item-input">
                            <input class="easyui-textbox" name="motor_monitor_maker" style="width:100%;"
                                   data-options="
                                        onChange:function(){
                                            ThreeElectricSystemMotorMonitor.search();
                                        }
                                  "
                                />
                        </div>
                    </li>
                    <li class="search-button">
                        <a href="javascript:void(0)" onclick="ThreeElectricSystemMotorMonitor.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                        <a href="javascript:void(0)" onclick="ThreeElectricSystemMotorMonitor.reset()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</a>
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
<div id="ThreeElectricSystemMotorMonitor_addWin"></div>
<div id="ThreeElectricSystemMotorMonitor_editWin"></div>
<!-- 窗口 end -->

<script>
    var ThreeElectricSystemMotorMonitor = {
        params:{
            'CONFIG': <?php echo json_encode($config); ?>,
            'URL': {
                'getList': '<?php echo yii::$app->urlManager->createUrl(['car/three-electric-system/motor-monitor-get-list']); ?>',
                'add': '<?php echo yii::$app->urlManager->createUrl(['car/three-electric-system/motor-monitor-add']); ?>',
                'edit': '<?php echo yii::$app->urlManager->createUrl(['car/three-electric-system/motor-monitor-edit']); ?>',
                'remove': '<?php echo yii::$app->urlManager->createUrl(['car/three-electric-system/motor-monitor-remove']); ?>',
                'exportGridData': '<?php echo yii::$app->urlManager->createUrl(['car/three-electric-system/motor-monitor-export']); ?>'
            }
        },
        //初始化
        init: function() {
            //列表
            $('#ThreeElectricSystemMotorMonitor_datagrid').datagrid({
                method: 'get',
                url: ThreeElectricSystemMotorMonitor.params.URL.getList,
                fit: true,
                border: false,
                toolbar: "#ThreeElectricSystemMotorMonitor_datagridToolbar",
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
                    {field: 'motor_monitor_model', title: '电机控制器型号', width: 100, align: 'center', sortable: true}
                ]],
                columns: [[
                    {field: 'apply_motor_type', title: '适用电机', width: 80, align: 'center', sortable: true,
                        formatter: function (value, row, index) {
                            try {
                                var str = 'ThreeElectricSystemMotorMonitor.params.CONFIG.apply_motor_type.' + value + '.text';
                                return eval(str);
                            } catch (e) {
                                return value;
                            }
                        }
                    },
                    {field: 'input_voltage_range', title: '输入电压范围(VDC)', width: 120, halign: 'center',align: 'right', sortable: true},
                    {field: 'rated_input_voltage', title: '额定输入电压(VDC)', width: 120, halign: 'center',align: 'right', sortable: true},
                    {field: 'rated_capacity', title: '额定容量(kVA)', width: 90, halign: 'center',align: 'right', sortable: true},
                    {field: 'peak_capacity', title: '峰值容量(kVA)', width: 90, halign: 'center',align: 'right', sortable: true},
                    {field: 'rated_input_current', title: '额定输入电流(A)', width: 100, halign: 'center',align: 'right', sortable: true},
                    {field: 'rated_output_current', title: '额定输出电流(A)', width: 100, halign: 'center',align: 'right', sortable: true},
                    {field: 'peak_output_current', title: '峰值输出电流(A)', width: 100, halign: 'center',align: 'right', sortable: true},
                    {field: 'peak_current_duration', title: '峰值电流持续时间(min)', width: 130, halign: 'center',align: 'right', sortable: true},
                    {field: 'output_frequency_range', title: '输出频率范围(Hz)', width: 120, halign: 'center',align: 'right', sortable: true},
                    {field: 'max_effciency', title: '控制器最大效率(%)', width: 120, halign: 'center',align: 'right', sortable: true},
                    {field: 'protection_level', title: '防护等级', width: 90, align: 'center', sortable: true},
                    {field: 'working_temp', title: '工作环境温度(℃)', width: 110, halign: 'center',align: 'right', sortable: true},

                    {field: 'cooling_type', title: '冷却方式', width: 80, align: 'center', sortable: true,
                        formatter: function (value, row, index) {
                            try {
                                var str = 'ThreeElectricSystemMotorMonitor.params.CONFIG.cooling_type.' + value + '.text';
                                return eval(str);
                            } catch (e) {
                                return value;
                            }
                        }
                    },
                    {field: 'motor_monitor_maker', title: '生产厂家', width: 120, align: 'center', sortable: true},
                    {field: 'create_time', title: '创建时间', align: 'center', width: 130, sortable: true},
                    {field: 'creator', title: '创建人员', width: 100, halign: 'center', sortable: true}
                ]]
            });
            //【新增】窗口
            $('#ThreeElectricSystemMotorMonitor_addWin').dialog({
                title: '新增电机控制器',
                width: 750,
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
                        var form = $('#ThreeElectricSystemMotorMonitor_addWin_form');
                        if(!form.form('validate')){
                            return false;
                        }
                        $.ajax({
                            "type": 'post',
                            "url": ThreeElectricSystemMotorMonitor.params.URL.add,
                            "data": form.serialize(),
                            "dataType": 'json',
                            "success": function(rData){
                                if(rData.status){
                                    $.messager.show({
                                        title: '操作成功',
                                        msg: rData.info
                                    });
                                    $('#ThreeElectricSystemMotorMonitor_addWin').dialog('close');
                                    $('#ThreeElectricSystemMotorMonitor_datagrid').datagrid('reload');
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
                        $('#ThreeElectricSystemMotorMonitor_addWin').dialog('close');
                    }
                }]
            });
            //【修改】窗口
            $('#ThreeElectricSystemMotorMonitor_editWin').dialog({
                title: '修改电机控制器',
                width: 750,
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
                        var form = $('#ThreeElectricSystemMotorMonitor_editWin_form');
                        if(!form.form('validate')){
                            return false;
                        }
                        $.ajax({
                            "type": 'post',
                            "url": ThreeElectricSystemMotorMonitor.params.URL.edit,
                            "data": form.serialize(),
                            "dataType": 'json',
                            "success": function(rData){
                                if(rData.status){
                                    $.messager.show({
                                        title: '操作成功',
                                        msg: rData.info
                                    });
                                    $('#ThreeElectricSystemMotorMonitor_editWin').dialog('close');
                                    $('#ThreeElectricSystemMotorMonitor_datagrid').datagrid('reload');
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
                        $('#ThreeElectricSystemMotorMonitor_editWin').dialog('close');
                    }
                }]
            });
        },
        //获取当前所选择的记录
        getCurrentSelected: function(multiline){
            var datagrid = $('#ThreeElectricSystemMotorMonitor_datagrid');
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
        //新增
        add: function(){
            $('#ThreeElectricSystemMotorMonitor_addWin')
                .dialog('open')
                .dialog('refresh',ThreeElectricSystemMotorMonitor.params.URL.add);
        },
        //修改
        edit: function(){
            var id = (this.getCurrentSelected()).id;
            if (!id) return;
            $('#ThreeElectricSystemMotorMonitor_editWin')
                .dialog('open')
                .dialog('refresh',ThreeElectricSystemMotorMonitor.params.URL.edit + '&id=' + id);
        },
        //删除
        remove: function(){
            var id = (this.getCurrentSelected()).id;
            if (!id) return;
            $.messager.confirm('确定删除', '您确定要删除该电机控制器吗？', function (r) {
                if (r) {
                    $.ajax({
                        type: 'get',
                        url: ThreeElectricSystemMotorMonitor.params.URL.remove,
                        data: {id: id},
                        dataType: 'json',
                        success: function (data) {
                            if (data) {
                                $.messager.show({
                                    title: '操作成功',
                                    msg: data.info
                                });
                                $('#ThreeElectricSystemMotorMonitor_datagrid').datagrid('reload');
                            } else {
                                $.messager.alert('操作失败', data.info, 'error');
                            }
                        }
                    });
                }
            });
        },
        //查询
        search: function(){
            var form = $('#ThreeElectricSystemMotorMonitor_searchFrom');
            var data = {};
            var searchCondition = form.serializeArray();
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = $.trim(searchCondition[i]['value']);
            }
            $('#ThreeElectricSystemMotorMonitor_datagrid').datagrid('load',data);
        },
        //重置
        reset: function(){
            $('#ThreeElectricSystemMotorMonitor_searchFrom').form('reset');
            ThreeElectricSystemMotorMonitor.search();
        },
        //导出Excel
        exportGridData: function(){
            var form = $('#ThreeElectricSystemMotorMonitor_searchFrom');
            var searchConditionStr = form.serialize();
            window.open(ThreeElectricSystemMotorMonitor.params.URL.exportGridData + '&' + searchConditionStr);
        }
    }

    // 执行初始化函数
    ThreeElectricSystemMotorMonitor.init();

</script>