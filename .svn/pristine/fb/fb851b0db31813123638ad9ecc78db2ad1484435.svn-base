<table id="ThreeElectricSystemMotor_datagrid"></table>
<div id="ThreeElectricSystemMotor_datagridToolbar">
    <div class="easyui-panel" title="检索区域" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">
        <div class="data-search-form">
            <form id="ThreeElectricSystemMotor_searchFrom">
                <ul class="search-main">
                    <li>
                        <div class="item-name">电机型号</div>
                        <div class="item-input">
                            <input class="easyui-textbox"  name="motor_model" style="width:100%;"
                                   data-options="
                                    onChange:function(){
                                        ThreeElectricSystemMotor.search();
                                    }
                                "
                                />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">编码器</div>
                        <div class="item-input">
                            <select class="easyui-combobox" name="encoder" style="width:100%;"
                                    data-options="
                                        panelHeight:'auto',
                                        editable:false,
                                        onChange:function(){
                                            ThreeElectricSystemMotor.search();
                                        }
                                  "
                                >
                                <option value="" selected="selected">--不限--</option>
                                <?php foreach($config['encoder'] as $val){ ?>
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
                                            ThreeElectricSystemMotor.search();
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
                            <input class="easyui-textbox" name="motor_maker" style="width:100%;"
                                   data-options="
                                        onChange:function(){
                                            ThreeElectricSystemMotor.search();
                                        }
                                  "
                                />
                        </div>
                    </li>
                    <li class="search-button">
                        <a href="javascript:void(0)" onclick="ThreeElectricSystemMotor.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                        <a href="javascript:void(0)" onclick="ThreeElectricSystemMotor.reset()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</a>
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
<div id="ThreeElectricSystemMotor_addWin"></div>
<div id="ThreeElectricSystemMotor_editWin"></div>
<!-- 窗口 end -->

<script>
    var ThreeElectricSystemMotor = {
        params:{
            'CONFIG': <?php echo json_encode($config); ?>,
            'URL': {
                'getList': '<?php echo yii::$app->urlManager->createUrl(['car/three-electric-system/motor-get-list']); ?>',
                'add': '<?php echo yii::$app->urlManager->createUrl(['car/three-electric-system/motor-add']); ?>',
                'edit': '<?php echo yii::$app->urlManager->createUrl(['car/three-electric-system/motor-edit']); ?>',
                'remove': '<?php echo yii::$app->urlManager->createUrl(['car/three-electric-system/motor-remove']); ?>',
                'exportGridData': '<?php echo yii::$app->urlManager->createUrl(['car/three-electric-system/motor-export']); ?>'
            }
        },
        //初始化
        init: function() {
            //列表
            $('#ThreeElectricSystemMotor_datagrid').datagrid({
                method: 'get',
                url: ThreeElectricSystemMotor.params.URL.getList,
                fit: true,
                border: false,
                toolbar: "#ThreeElectricSystemMotor_datagridToolbar",
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
                    {field: 'motor_model', title: '电机型号', width: 80, align: 'center', sortable: true}
                ]],
                columns: [[
                    {field: 'encoder', title: '编码器', width: 80, align: 'center', sortable: true,
                        formatter: function (value, row, index) {
                            try {
                                var str = 'ThreeElectricSystemMotor.params.CONFIG.encoder.' + value + '.text';
                                return eval(str);
                            } catch (e) {
                                return value;
                            }
                        }
                    },
                    {field: 'rated_power', title: '额定功率(kW)', width: 90, halign: 'center',align: 'right', sortable: true},
                    {field: 'rated_speed', title: '额定转速(rpm)', width: 90, halign: 'center',align: 'right', sortable: true},
                    {field: 'rated_frequency', title: '额定频率(Hz)', width: 90, halign: 'center',align: 'right', sortable: true},
                    {field: 'rated_current', title: '额定电流(A)', width: 90, halign: 'center',align: 'right', sortable: true},
                    {field: 'rated_torque', title: '额定转矩(Nm)', width: 90, halign: 'center',align: 'right', sortable: true},
                    {field: 'rated_voltage', title: '额定电压(V)', width: 90, halign: 'center',align: 'right', sortable: true},

                    {field: 'peak_power', title: '峰值功率(kW)', width: 90, halign: 'center',align: 'right', sortable: true},
                    {field: 'peak_speed', title: '峰值转速(rpm)', width: 90, halign: 'center',align: 'right', sortable: true},
                    {field: 'peak_frequency', title: '峰值频率(Hz)', width: 90, halign: 'center',align: 'right', sortable: true},
                    {field: 'peak_current', title: '峰值电流(A)', width: 90, halign: 'center',align: 'right', sortable: true},
                    {field: 'peak_torque', title: '峰值转矩(Nm)', width: 90, halign: 'center',align: 'right', sortable: true},
                    {field: 'polar_logarithm', title: '极对数', width: 90, halign: 'center',align: 'right', sortable: true},

                    {field: 'cooling_type', title: '冷却方式', width: 80, align: 'center', sortable: true,
                        formatter: function (value, row, index) {
                            try {
                                var str = 'ThreeElectricSystemMotor.params.CONFIG.cooling_type.' + value + '.text';
                                return eval(str);
                            } catch (e) {
                                return value;
                            }
                        }
                    },
                    {field: 'motor_maker', title: '生产厂家', width: 120, align: 'center', sortable: true},
                    {field: 'create_time', title: '创建时间', align: 'center', width: 130, sortable: true},
                    {field: 'creator', title: '创建人员', width: 100, halign: 'center', sortable: true}
                ]]
            });
            //【新增】窗口
            $('#ThreeElectricSystemMotor_addWin').dialog({
                title: '新增电机',
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
                        var form = $('#ThreeElectricSystemMotor_addWin_form');
                        if(!form.form('validate')){
                            return false;
                        }
                        $.ajax({
                            "type": 'post',
                            "url": ThreeElectricSystemMotor.params.URL.add,
                            "data": form.serialize(),
                            "dataType": 'json',
                            "success": function(rData){
                                if(rData.status){
                                    $.messager.show({
                                        title: '操作成功',
                                        msg: rData.info
                                    });
                                    $('#ThreeElectricSystemMotor_addWin').dialog('close');
                                    $('#ThreeElectricSystemMotor_datagrid').datagrid('reload');
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
                        $('#ThreeElectricSystemMotor_addWin').dialog('close');
                    }
                }]
            });
            //【修改】窗口
            $('#ThreeElectricSystemMotor_editWin').dialog({
                title: '修改电机',
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
                        var form = $('#ThreeElectricSystemMotor_editWin_form');
                        if(!form.form('validate')){
                            return false;
                        }
                        $.ajax({
                            "type": 'post',
                            "url": ThreeElectricSystemMotor.params.URL.edit,
                            "data": form.serialize(),
                            "dataType": 'json',
                            "success": function(rData){
                                if(rData.status){
                                    $.messager.show({
                                        title: '操作成功',
                                        msg: rData.info
                                    });
                                    $('#ThreeElectricSystemMotor_editWin').dialog('close');
                                    $('#ThreeElectricSystemMotor_datagrid').datagrid('reload');
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
                        $('#ThreeElectricSystemMotor_editWin').dialog('close');
                    }
                }]
            });
        },
        //获取当前所选择的记录
        getCurrentSelected: function(multiline){
            var datagrid = $('#ThreeElectricSystemMotor_datagrid');
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
            $('#ThreeElectricSystemMotor_addWin')
                .dialog('open')
                .dialog('refresh',ThreeElectricSystemMotor.params.URL.add);
        },
        //修改
        edit: function(){
            var id = (this.getCurrentSelected()).id;
            if (!id) return;
            $('#ThreeElectricSystemMotor_editWin')
                .dialog('open')
                .dialog('refresh',ThreeElectricSystemMotor.params.URL.edit + '&id=' + id);
        },
        //删除
        remove: function(){
            var id = (this.getCurrentSelected()).id;
            if (!id) return;
            $.messager.confirm('确定删除', '您确定要删除该电机吗？', function (r) {
                if (r) {
                    $.ajax({
                        type: 'get',
                        url: ThreeElectricSystemMotor.params.URL.remove,
                        data: {id: id},
                        dataType: 'json',
                        success: function (data) {
                            if (data) {
                                $.messager.show({
                                    title: '操作成功',
                                    msg: data.info
                                });
                                $('#ThreeElectricSystemMotor_datagrid').datagrid('reload');
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
            var form = $('#ThreeElectricSystemMotor_searchFrom');
            var data = {};
            var searchCondition = form.serializeArray();
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = $.trim(searchCondition[i]['value']);
            }
            $('#ThreeElectricSystemMotor_datagrid').datagrid('load',data);
        },
        //重置
        reset: function(){
            $('#ThreeElectricSystemMotor_searchFrom').form('reset');
            ThreeElectricSystemMotor.search();
        },
        //导出Excel
        exportGridData: function(){
            var form = $('#ThreeElectricSystemMotor_searchFrom');
            var searchConditionStr = form.serialize();
            window.open(ThreeElectricSystemMotor.params.URL.exportGridData + '&' + searchConditionStr);
        }
    }

    // 执行初始化函数
    ThreeElectricSystemMotor.init();

</script>