<table id="ThreeElectricSystemBattery_datagrid"></table>
<div id="ThreeElectricSystemBattery_datagridToolbar">
    <div class="easyui-panel" title="检索区域" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">
        <div class="data-search-form">
            <form id="ThreeElectricSystemBattery_searchFrom">
                <ul class="search-main">
                    <li>
                        <div class="item-name">电池型号</div>
                        <div class="item-input">
                            <input class="easyui-textbox"  name="battery_model" style="width:100%;"
                                   data-options="
                                    onChange:function(){
                                        ThreeElectricSystemBattery.search();
                                    }
                                "
                                />
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
                                            ThreeElectricSystemBattery.search();
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
                        <div class="item-name">充电接口类型</div>
                        <div class="item-input">
                            <select class="easyui-combobox" name="connection_type" style="width:100%;"
                                    data-options="
                                        panelHeight:'auto',
                                        editable:false,
                                        onChange:function(){
                                            ThreeElectricSystemBattery.search();
                                        }
                                  "
                                >
                                <option value="" selected="selected">--不限--</option>
                                <?php foreach($config['connection_type'] as $val){ ?>
                                    <option value="<?= $val['value'] ?>"><?= $val['text'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">生产厂家</div>
                        <div class="item-input">
                            <input class="easyui-textbox" name="battery_maker" style="width:100%;"
                                   data-options="
                                        onChange:function(){
                                            ThreeElectricSystemBattery.search();
                                        }
                                  "
                                />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">电池规格</div>
                        <div class="item-input">
                            <select class="easyui-combobox" name="battery_spec" style="width:100%;"
                                    data-options="
                                        panelHeight:'auto',
                                        editable:false,
                                        onChange:function(){
                                            ThreeElectricSystemBattery.search();
                                        }
                                  "
                                >
                                <option value="" selected="selected">--不限--</option>
                                <?php foreach($config['battery_spec'] as $val){ ?>
                                    <option value="<?= $val['value'] ?>"><?= $val['text'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </li>
                    <li class="search-button">
                        <a href="javascript:void(0)" onclick="ThreeElectricSystemBattery.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                        <a href="javascript:void(0)" onclick="ThreeElectricSystemBattery.reset()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</a>
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
<div id="ThreeElectricSystemBattery_addWin"></div>
<div id="ThreeElectricSystemBattery_editWin"></div>
<!-- 窗口 end -->

<script>
    var ThreeElectricSystemBattery = {
        params:{
            'CONFIG': <?php echo json_encode($config); ?>,
            'URL': {
                'getList': '<?php echo yii::$app->urlManager->createUrl(['car/three-electric-system/battery-get-list']); ?>',
                'add': '<?php echo yii::$app->urlManager->createUrl(['car/three-electric-system/battery-add']); ?>',
                'edit': '<?php echo yii::$app->urlManager->createUrl(['car/three-electric-system/battery-edit']); ?>',
                'remove': '<?php echo yii::$app->urlManager->createUrl(['car/three-electric-system/battery-remove']); ?>',
                'exportGridData': '<?php echo yii::$app->urlManager->createUrl(['car/three-electric-system/battery-export']); ?>'
            }
        },
        //初始化
        init: function() {
            //列表
            $('#ThreeElectricSystemBattery_datagrid').datagrid({
                method: 'get',
                url: ThreeElectricSystemBattery.params.URL.getList,
                fit: true,
                border: false,
                toolbar: "#ThreeElectricSystemBattery_datagridToolbar",
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
                    {field: 'battery_model', title: '电池型号', width: 80, align: 'center', sortable: true}
                ]],
                columns: [[
                    {field: 'battery_type', title: '电池类型', width: 100, align: 'center', sortable: true,
                        formatter: function (value, row, index) {
                            try {
                                var str = 'ThreeElectricSystemBattery.params.CONFIG.battery_type.' + value + '.text';
                                return eval(str);
                            } catch (e) {
                                return value;
                            }
                        }
                    },
                    {field: 'system_voltage', title: '电池系统额定电压(V)', width: 130, halign: 'center',align: 'right', sortable: true},
                    {field: 'system_capacity', title: '电池系统额定容量(Ah)', width: 130, halign: 'center',align: 'right', sortable: true},
                    {field: 'system_power', title: '电池系统额定电能(kWh)', width: 130, halign: 'center',align: 'right', sortable: true},
                    {field: 'system_nums', title: '电池系统电池串联数量', width: 130, halign: 'center',align: 'right', sortable: true},

                    {field: 'single_voltage', title: '单体电池额定电压(V)', width: 130, halign: 'center',align: 'right', sortable: true},
                    {field: 'single_capacity', title: '单体电池额定容量(Ah)', width: 130, halign: 'center',align: 'right', sortable: true},
                    {field: 'module_capacity', title: '电池模块容量(kWh)', width: 130, halign: 'center',align: 'right', sortable: true},
                    {field: 'module_nums', title: '电池模块数量', width: 100, halign: 'center',align: 'right', sortable: true},
                    {field: 'connection_type', title: '充电接口类型', width: 100, align: 'center', sortable: true,
                        formatter: function (value, row, index) {
                            try {
                                var str = 'ThreeElectricSystemBattery.params.CONFIG.connection_type.' + value + '.text';
                                return eval(str);
                            } catch (e) {
                                return value;
                            }
                        }
                    },
                    {field: 'battery_spec', title: '电池规格', width: 100, align: 'center', sortable: true,
                        formatter: function (value, row, index) {
                            try {
                                var str = 'ThreeElectricSystemBattery.params.CONFIG.battery_spec.' + value + '.text';
                                return eval(str);
                            } catch (e) {
                                return value;
                            }
                        }
                    },
                    {field: 'battery_maker', title: '生产厂家', width: 120, halign: 'center', sortable: true},
                    {field: 'create_time', title: '创建时间', align: 'center', width: 130, sortable: true},
                    {field: 'creator', title: '创建人员', width: 100, halign: 'center', sortable: true}
                ]]
            });
            //【新增】窗口
            $('#ThreeElectricSystemBattery_addWin').dialog({
                title: '新增电池',
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
                        var form = $('#ThreeElectricSystemBattery_addWin_form');
                        if(!form.form('validate')){
                            return false;
                        }
                        $.ajax({
                            "type": 'post',
                            "url": ThreeElectricSystemBattery.params.URL.add,
                            "data": form.serialize(),
                            "dataType": 'json',
                            "success": function(rData){
                                if(rData.status){
                                    $.messager.show({
                                        title: '操作成功',
                                        msg: rData.info
                                    });
                                    $('#ThreeElectricSystemBattery_addWin').dialog('close');
                                    $('#ThreeElectricSystemBattery_datagrid').datagrid('reload');
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
                        $('#ThreeElectricSystemBattery_addWin').dialog('close');
                    }
                }]
            });
            //【修改】窗口
            $('#ThreeElectricSystemBattery_editWin').dialog({
                title: '修改电池',
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
                        var form = $('#ThreeElectricSystemBattery_editWin_form');
                        if(!form.form('validate')){
                            return false;
                        }
                        $.ajax({
                            "type": 'post',
                            "url": ThreeElectricSystemBattery.params.URL.edit,
                            "data": form.serialize(),
                            "dataType": 'json',
                            "success": function(rData){
                                if(rData.status){
                                    $.messager.show({
                                        title: '操作成功',
                                        msg: rData.info
                                    });
                                    $('#ThreeElectricSystemBattery_editWin').dialog('close');
                                    $('#ThreeElectricSystemBattery_datagrid').datagrid('reload');
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
                        $('#ThreeElectricSystemBattery_editWin').dialog('close');
                    }
                }]
            });
        },
        //获取当前所选择的记录
        getCurrentSelected: function(multiline){
            var datagrid = $('#ThreeElectricSystemBattery_datagrid');
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
            $('#ThreeElectricSystemBattery_addWin')
                .dialog('open')
                .dialog('refresh',ThreeElectricSystemBattery.params.URL.add);
        },
        //修改
        edit: function(){
            var id = (this.getCurrentSelected()).id;
            if (!id) return;
            $('#ThreeElectricSystemBattery_editWin')
                .dialog('open')
                .dialog('refresh',ThreeElectricSystemBattery.params.URL.edit + '&id=' + id);
        },
        //删除
        remove: function(){
            var id = (this.getCurrentSelected()).id;
            if (!id) return;
            $.messager.confirm('确定删除', '您确定要删除该电池吗？', function (r) {
                if (r) {
                    $.ajax({
                        type: 'get',
                        url: ThreeElectricSystemBattery.params.URL.remove,
                        data: {id: id},
                        dataType: 'json',
                        success: function (data) {
                            if (data) {
                                $.messager.show({
                                    title: '操作成功',
                                    msg: data.info
                                });
                                $('#ThreeElectricSystemBattery_datagrid').datagrid('reload');
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
            var form = $('#ThreeElectricSystemBattery_searchFrom');
            var data = {};
            var searchCondition = form.serializeArray();
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = $.trim(searchCondition[i]['value']);
            }
            $('#ThreeElectricSystemBattery_datagrid').datagrid('load',data);
        },
        //重置
        reset: function(){
            $('#ThreeElectricSystemBattery_searchFrom').form('reset');
            ThreeElectricSystemBattery.search();
        },
        //导出Excel
        exportGridData: function(){
            var form = $('#ThreeElectricSystemBattery_searchFrom');
            var searchConditionStr = form.serialize();
            window.open(ThreeElectricSystemBattery.params.URL.exportGridData + '&' + searchConditionStr);
        }
    }

    // 执行初始化函数
    ThreeElectricSystemBattery.init();

</script>