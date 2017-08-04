<table id="carmonitorBatteryDetectionIndex_setParamsWin_datagrid"></table>
<div id="carmonitorBatteryDetectionIndex_setParamsWin_datagridToolbar">
    <?php if(isset($buttons) && !empty($buttons)){ ?>
        <div class="easyui-panel" title="" style="padding:3px 2px;width:100%;" data-options="
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
<div id="carmonitorBatteryDetectionIndex_setParamsWin_addCriteriaWin"></div>
<div id="carmonitorBatteryDetectionIndex_setParamsWin_editCriteriaWin"></div>
<div id="carmonitorBatteryDetectionIndex_setParamsWin_scanCriteriaDetailsWin"></div>
<!-- 窗口 end -->

<script>
    var carmonitorBatteryDetectionIndex_setParamsWin = {
        params:{
            'CONFIG': <?php echo json_encode($config); ?>,
            'URL': {
                'getCriteriaList': '<?php echo yii::$app->urlManager->createUrl(['carmonitor/battery-detection/get-criteria-list']); ?>',
                'addCriteria': '<?php echo yii::$app->urlManager->createUrl(['carmonitor/battery-detection/add-criteria']); ?>',
                'editCriteria': '<?php echo yii::$app->urlManager->createUrl(['carmonitor/battery-detection/edit-criteria']); ?>',
                'removeCriteria': '<?php echo yii::$app->urlManager->createUrl(['carmonitor/battery-detection/remove-criteria']); ?>',
                'scanCriteriaDetails': '<?php echo yii::$app->urlManager->createUrl(['carmonitor/battery-detection/scan-criteria-details']); ?>'
            }
        },
        //初始化
        init: function() {
            //列表
            $('#carmonitorBatteryDetectionIndex_setParamsWin_datagrid').datagrid({
                method: 'get',
                url: carmonitorBatteryDetectionIndex_setParamsWin.params.URL.getCriteriaList,
                fit: true,
                border: false,
                toolbar: "#carmonitorBatteryDetectionIndex_setParamsWin_datagridToolbar",
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
                    {field: 'battery_type', title: '电池类型', width: 120, align: 'center', sortable: true,
                        formatter: function (value, row, index) {
                            try {
                                var str = 'carmonitorBatteryDetectionIndex_setParamsWin.params.CONFIG.battery_type.' + value + '.text';
                                return eval(str);
                            } catch (e) {
                                return value;
                            }
                        }
                    }
                ]],
                columns: [[
                    {field: 'create_time', title: '操作时间', align: 'center', width: 130, sortable: true},
                    {field: 'creator', title: '操作人员', align: 'center', width: 130, sortable: true}
                ]]
            });
            //【新增判定标准】窗口
            $('#carmonitorBatteryDetectionIndex_setParamsWin_addCriteriaWin').dialog({
                title: '新增判定标准',
                width: 1200,
                height: 520,
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
                        var form = $('#carmonitorBatteryDetectionIndex_setParamsWin_addCriteriaWin_form');
                        if(!form.form('validate')){
                            return false;
                        }
                        $.ajax({
                            "type": 'post',
                            "url": carmonitorBatteryDetectionIndex_setParamsWin.params.URL.addCriteria,
                            "data": form.serialize(),
                            "dataType": 'json',
                            "success": function(rData){
                                if(rData.status){
                                    $.messager.show({
                                        title: '操作成功',
                                        msg: rData.info
                                    });
                                    $('#carmonitorBatteryDetectionIndex_setParamsWin_addCriteriaWin').dialog('close');
                                    $('#carmonitorBatteryDetectionIndex_setParamsWin_datagrid').datagrid('reload');
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
                        $('#carmonitorBatteryDetectionIndex_setParamsWin_addCriteriaWin').dialog('close');
                    }
                }]
            });
            //【修改判定标准】窗口
            $('#carmonitorBatteryDetectionIndex_setParamsWin_editCriteriaWin').dialog({
                title: '修改判定标准',
                width: 1200,
                height: 520,
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
                        var form = $('#carmonitorBatteryDetectionIndex_setParamsWin_editCriteriaWin_form');
                        if(!form.form('validate')){
                            return false;
                        }
                        $.ajax({
                            "type": 'post',
                            "url": carmonitorBatteryDetectionIndex_setParamsWin.params.URL.editCriteria,
                            "data": form.serialize(),
                            "dataType": 'json',
                            "success": function(rData){
                                if(rData.status){
                                    $.messager.show({
                                        title: '操作成功',
                                        msg: rData.info
                                    });
                                    $('#carmonitorBatteryDetectionIndex_setParamsWin_editCriteriaWin').dialog('close');
                                    $('#carmonitorBatteryDetectionIndex_setParamsWin_datagrid').datagrid('reload');
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
                        $('#carmonitorBatteryDetectionIndex_setParamsWin_editCriteriaWin').dialog('close');
                    }
                }]
            });
            //【查看标准详细】窗口
            $('#carmonitorBatteryDetectionIndex_setParamsWin_scanCriteriaDetailsWin').window({
                title: '查看标准详细',
                width: 1100,
                height: 520,
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
            var datagrid = $('#carmonitorBatteryDetectionIndex_setParamsWin_datagrid');
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
                        msg: '该功能不能批量操作！<br/>如果您选择了多条记录，则默认操作的是第一条记录！'
                    });
                }
                return selectRows[0];
            }
        },
        //新增标准
        addCriteria: function(){
            $('#carmonitorBatteryDetectionIndex_setParamsWin_addCriteriaWin')
                .dialog('open')
                .dialog('refresh',carmonitorBatteryDetectionIndex_setParamsWin.params.URL.addCriteria);
        },
        //修改标准
        editCriteria: function(){
            var id = (this.getCurrentSelected()).id;
            if (!id) return;
            $('#carmonitorBatteryDetectionIndex_setParamsWin_editCriteriaWin')
                .dialog('open')
                .dialog('refresh',carmonitorBatteryDetectionIndex_setParamsWin.params.URL.editCriteria + '&id=' + id);
        },
        //删除标准
        removeCriteria: function(){
            var id = (this.getCurrentSelected()).id;
            if (!id) return;
            $.messager.confirm('确定删除', '您确定要删除该标准吗？', function (r) {
                if (r) {
                    $.ajax({
                        type: 'get',
                        url: carmonitorBatteryDetectionIndex_setParamsWin.params.URL.removeCriteria,
                        data: {id: id},
                        dataType: 'json',
                        success: function (data) {
                            if (data) {
                                $.messager.show({
                                    title: '操作成功',
                                    msg: data.info
                                });
                                $('#carmonitorBatteryDetectionIndex_setParamsWin_datagrid').datagrid('reload');
                            } else {
                                $.messager.alert('操作失败', data.info, 'error');
                            }
                        }
                    });
                }
            });
        },
        //查看详细
        scanCriteriaDetails: function(){
            var id = (this.getCurrentSelected()).id;
            if (!id) return;
            $('#carmonitorBatteryDetectionIndex_setParamsWin_scanCriteriaDetailsWin')
                .window('open')
                .window('refresh',carmonitorBatteryDetectionIndex_setParamsWin.params.URL.scanCriteriaDetails + '&id=' + id);
        }
    }

    // 执行初始化函数
    carmonitorBatteryDetectionIndex_setParamsWin.init();

</script>