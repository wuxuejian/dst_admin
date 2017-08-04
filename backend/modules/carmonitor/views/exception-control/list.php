<table id="easyui_datagrid_carmonitor_exception_control_list"></table> 
<div id="easyui_datagrid_carmonitor_exception_control_list_toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form class="easyui-form" id="search_form_carmonitor_exception_control_list">
                <ul class="search-main">
                    <li>
                        <div class="item-name">电池型号</div>
                        <div class="item-input">
                            <select
                                class="easyui-combobox"
                                name="battery_type"
                                style="width:100%;"
                                data-options="{editable:false,panelHeight:'auto',onChange: function(){
                                    CarmonitorExceptionControlList.search();
                                }}"
                            >
                                <option value="">不限</option>
                                <?php foreach($config['battery_type'] as $val){ ?>
                                <option value="<?php echo $val['value']; ?>"><?php echo $val['text']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">添加时间</div>
                        <div class="item-input-datebox">
                            <input
                                class="easyui-datebox"
                                name="add_datetime_start"
                                style="width:92px;"
                                data-options="{editable:false,onChange: function(){
                                    CarmonitorExceptionControlList.search();
                                }}"
                            /> - <input
                                class="easyui-datebox"
                                name="add_datetime_end"
                                style="width:92px;"
                                data-options="{editable:false,onChange: function(){
                                    CarmonitorExceptionControlList.search();
                                }}"
                            />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">操作人员</div>
                        <div class="item-input">
                            <input
                                class="easyui-textbox"
                                name="username"
                                style="width:100%;"
                                data-options="{onChange: function(){
                                    CarmonitorExceptionControlList.search();
                                }}"
                            />
                        </div>
                    </li>
                    <li class="search-button">
                        <button class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button onclick="return CarmonitorExceptionControlList.reset()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
                    </li>
                </ul>
            </form>
        </div>
    </div>

    <?php if(!empty($buttons)){ ?>
        <div class="easyui-panel" title="数据列表" style="padding:3px 2px;width:100%;" data-options="
        iconCls: 'icon-table-list',
        border: false
    ">
            <?php foreach($buttons as $val){ ?>
                <button onclick="<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon']; ?>'"><?= $val['text']; ?></button>
            <?php } ?>
        </div>
    <?php } ?>

</div>
<div id="easyui_dialog_carmonitor_exception_control_list_add"></div>
<div id="easyui_dialog_carmonitor_exception_control_list_edit"></div>
<div id="easyui_window_carmonitor_exception_control_list_detail"></div>
<div id="easyui_window_carmonitor_exception_control_list_alert_shot_message_rule"></div>
<script>
    var CarmonitorExceptionControlList = {
        params: {
            url: {
                getListData: "<?= yii::$app->urlManager->createUrl(['carmonitor/exception-control/get-list-data']); ?>",
                add: "<?= yii::$app->urlManager->createUrl(['carmonitor/exception-control/add']); ?>",
                edit: "<?= yii::$app->urlManager->createUrl(['carmonitor/exception-control/edit']); ?>",
                remove: "<?= yii::$app->urlManager->createUrl(['carmonitor/exception-control/remove']); ?>",
                detail: "<?= yii::$app->urlManager->createUrl(['carmonitor/exception-control/detail']); ?>",
                alertShotMessageRule: "<?= yii::$app->urlManager->createUrl(['carmonitor/exception-control/alert-shot-message-rule']); ?>"
            },
            config: <?php echo json_encode($config); ?>,
            windows: {
                alertShotMessageRule: $('#easyui_window_carmonitor_exception_control_list_alert_shot_message_rule')
            }
        },
        init: function(){
            //获取列表数据
            $('#easyui_datagrid_carmonitor_exception_control_list').datagrid({  
                method: 'get', 
                url:this.params.url.getListData,   
                fit: true,
                border: false,
                toolbar: "#easyui_datagrid_carmonitor_exception_control_list_toolbar",
                pagination: true,
                loadMsg: '数据加载中...',
                striped: true,
                checkOnSelect: true,
                rownumbers: true,
                singleSelect: true,
                frozenColumns: [[
                    {field: 'ck',checkbox: true},
                    {field: 'id',hidden: true},
                    {field: 'battery_type',title: '电池型号',width: 160,align: 'left',halign: 'center',sortable: true,formatter: function(value){
                        if(CarmonitorExceptionControlList.params.config.battery_type[value]){
                            return CarmonitorExceptionControlList.params.config.battery_type[value].text;
                        }
                    }}
                ]],
                columns:[[
                    {field: 'add_datetime',title: '添加时间',width: 130,align: 'center',
                        sortable: true},
                    {field: 'username',title: '操作人员',width: 100,align: 'left',halign: 'center',sortable: true}
                ]]
            });
            //查询表单
            $('#search_form_carmonitor_exception_control_list').submit(function(){
                var data = {};
                var searchCondition = $(this).serializeArray();
                for(var i in searchCondition){
                    data[searchCondition[i]['name']] = searchCondition[i]['value'];
                }
                $('#easyui_datagrid_carmonitor_exception_control_list').datagrid('load',data);
                return false;
            });
            //初始化添加窗口
            $('#easyui_dialog_carmonitor_exception_control_list_add').dialog({
                title: '新增报警标准',
                width: 1000,
                height: 500,
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
                        var easyuiForm = $('#easyui_form_carmonitor_exception_control_add');
                        if(!easyuiForm.form('validate')){
                            $.messager.show({
                                title:'数据验证失败',
                                msg:'请确认新增报警标准中数据填写无误！',
                                timeout:5000,
                                showType:'slide'
                            });
                            return false;
                        }
                        $.ajax({
                            type: 'post',
                            url: CarmonitorExceptionControlList.params.url.add,
                            data: easyuiForm.serialize(),
                            dataType: 'json',
                            success: function(rData){
                                if(rData.error){
                                    $.messager.alert('操作失败',rData.msg,'error');
                                }else{
                                    $.messager.alert('操作成功',rData.msg,'info');
                                    $('#easyui_dialog_carmonitor_exception_control_list_add').dialog('close');
                                    $('#easyui_datagrid_carmonitor_exception_control_list').datagrid('reload');
                                }
                            }
                        });
                    }    
                }, {
                    text: '取消',
                    iconCls: 'icon-cancel',
                    handler: function () {
                        $('#easyui_dialog_carmonitor_exception_control_list_add').dialog('close');
                    }
                }]
            });
            //初始化修改窗口
            $('#easyui_dialog_carmonitor_exception_control_list_edit').dialog({
                title: '修改报警标准',
                width: 1000,
                height: 500,
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
                        var easyuiForm = $('#easyui_form_carmonitor_exception_control_edit');
                        if(!easyuiForm.form('validate')){
                            $.messager.show({
                                title:'数据验证失败',
                                msg:'请确认修改报警标准中数据填写无误！',
                                timeout:5000,
                                showType:'slide'
                            });
                            return false;
                        }
                        $.ajax({
                            type: 'post',
                            url: CarmonitorExceptionControlList.params.url.edit,
                            data: easyuiForm.serialize(),
                            dataType: 'json',
                            success: function(rData){
                                if(rData.error){
                                    $.messager.alert('操作失败',rData.msg,'error');
                                }else{
                                    $.messager.alert('操作成功',rData.msg,'info');
                                    $('#easyui_dialog_carmonitor_exception_control_list_edit').dialog('close');
                                }
                            }
                        });
                    }    
                }, {
                    text: '取消',
                    iconCls: 'icon-cancel',
                    handler: function () {
                        $('#easyui_dialog_carmonitor_exception_control_list_edit').dialog('close');
                    }
                }]
            });
            //初始化查看详细
            $('#easyui_window_carmonitor_exception_control_list_detail').window({
                title: '报警标准明细',
                width: 1000,
                height: 500,
                closed: true,
                cache: true,
                modal: true,
                maximizable: true,
                collapsible: false,
                minimizable: false,
                resizable: false,
                onClose: function () {
                    $(this).dialog('clear');
                }
            });
            //初始化设置短信推送规则窗口
            this.params.windows.alertShotMessageRule.dialog({
                title: '设置报警信息推送规则',
                width: 384,
                height: 390,
                closed: true,
                cache: true,
                modal: true,
                maximizable: false,
                resizable: false,
                onClose: function () {
                    $(this).dialog('clear');
                },
                buttons: [{
                    text: '确定',
                    iconCls: 'icon-ok',
                    handler: function () {
                        var easyuiForm = $('#easyui_form_carmonitor_exception_control_shot_message_rule');
                        if(!easyuiForm.form('validate')){
                            $.messager.show({
                                title:'数据验证失败',
                                msg:'请确认数据填写无误！',
                                timeout:5000,
                                showType:'slide'
                            });
                            return false;
                        }
                        $.ajax({
                            type: 'post',
                            url: CarmonitorExceptionControlList.params.url.alertShotMessageRule,
                            data: easyuiForm.serialize(),
                            dataType: 'json',
                            success: function(rData){
                                if(rData.error){
                                    $.messager.alert('操作失败',rData.msg,'error');
                                }else{
                                    $.messager.alert('操作成功',rData.msg,'info');
                                    CarmonitorExceptionControlList.params.windows.alertShotMessageRule.dialog('refresh',CarmonitorExceptionControlList.params.url.alertShotMessageRule);
                                }
                            }
                        });
                    }    
                }, {
                    text: '取消',
                    iconCls: 'icon-cancel',
                    handler: function () {
                        CarmonitorExceptionControlList.params.windows.alertShotMessageRule.dialog('close');
                    }
                }]
            });
        },
        getSelected: function(all){
            var datagrid = $('#easyui_datagrid_carmonitor_exception_control_list');
            if(all){
                var selectRows = datagrid.datagrid('getSelections');
                if(selectRows.length <= 0){
                    $.messager.alert('错误','请选择要操作的记录','error');   
                    return false;
                }
                return selectRows;
            }else{
                var selectRow = datagrid.datagrid('getSelected');
                if(!selectRow){
                    $.messager.alert('错误','请选择要操作的记录','error');   
                    return false;
                }
                return selectRow;
            }
        },
        add: function(){
            $('#easyui_dialog_carmonitor_exception_control_list_add')
                .dialog('open')
                .dialog('refresh',this.params.url.add);
        },
        edit: function(){
            var selectedRow = this.getSelected();
            if(!selectedRow){
                return false;
            }
            $('#easyui_dialog_carmonitor_exception_control_list_edit')
                .dialog('open')
                .dialog('refresh',this.params.url.edit+'&id='+selectedRow.id);
        },
        remove: function(){
            var selectedRow = this.getSelected();
            if(!selectedRow){
                return false;
            }
            $.messager.confirm('删除确认','确定要删除该电池类型的报警标准？',function(r){
                if(r){
                    $.ajax({
                        type: 'get',
                        url: CarmonitorExceptionControlList.params.url.remove,
                        data: {id: selectedRow.id},
                        dataType: 'json',
                        success: function(rData){
                            if(rData.error){
                                $.messager.alert('操作失败',rData.msg,'error');
                            }else{
                                $.messager.alert('操作成功',rData.msg,'info');
                                $('#easyui_datagrid_carmonitor_exception_control_list').datagrid('reload');
                            }
                        }
                    });
                }
            });
        },
        detail: function(){
            var selectedRow = this.getSelected();
            if(!selectedRow){
                return false;
            }
            $('#easyui_window_carmonitor_exception_control_list_detail')
                .window('open')
                .window('refresh',this.params.url.detail+'&id='+selectedRow.id);
        },
        alertShotMessageRule: function(){
            this.params.windows.alertShotMessageRule
                .dialog('open')
                .dialog('refresh',this.params.url.alertShotMessageRule);
        },
        search: function(){
            $('#search_form_carmonitor_exception_control_list').submit();
        },
        reset: function(){
            var searchForm = $('#search_form_carmonitor_exception_control_list');
            searchForm.form('reset');
            searchForm.submit();
        }
    };
    CarmonitorExceptionControlList.init();
</script>