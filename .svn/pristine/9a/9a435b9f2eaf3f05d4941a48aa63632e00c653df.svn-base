<table id="easyui_datagrid_cpolemonitor_alert_index"></table> 
<div id="easyui_datagrid_cpolemonitor_alert_index_toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form class="easyui-form" id="search_form_polemonitor_alert_index">
                <ul class="search-main">
                    <li>
                        <div class="item-name">充电站</div>
                        <div class="item-input">
                            <input
                                class="easyui-textbox"
                                name="cs_name"
                                style="width:100%;"
                                data-options="oncharge: function(){
                                    PolemonitorAlertIndex.search();
                                }" 
                            />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">电桩逻辑地址</div>
                        <div class="item-input">
                            <input
                                class="easyui-textbox"
                                name="dev_addr"
                                style="width:100%;"
                                data-options="oncharge: function(){
                                    PolemonitorAlertIndex.search();
                                }" 
                            />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">报警时间</div>
                        <div class="item-input">
                            <input
                                class="easyui-datebox"
                                name="happen_datetime_start"
                                style="width:92px;"
                                data-options="{editable:false,onChange: function(){
                                    PolemonitorAlertIndex.search();
                                }}"
                            /> - <input
                                class="easyui-datebox"
                                name="happen_datetime_end"
                                style="width:92px;"
                                data-options="{editable:false,onChange: function(){
                                    PolemonitorAlertIndex.search();
                                }}"
                            />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">报警等级</div>
                        <div class="item-input">
                            <select
                                class="easyui-combobox"
                                name="alert_level_start"
                                style="width:92px;"
                                data-options="{editable:false,onChange: function(){
                                    PolemonitorAlertIndex.search();
                                }}"
                            >
                                <option value="">不限</option>
                                <option value="5">5</option>
                                <option value="4">4</option>
                                <option value="3">3</option>
                                <option value="2">2</option>
                                <option value="1">1</option>
                            </select> - <select
                                class="easyui-combobox"
                                name="alert_level_end"
                                style="width:92px;"
                                data-options="{editable:false,onChange: function(){
                                    PolemonitorAlertIndex.search();
                                }}"
                            >
                                <option value="">不限</option>
                                <option value="5">5</option>
                                <option value="4">4</option>
                                <option value="3">3</option>
                                <option value="2">2</option>
                                <option value="1">1</option>
                            </select>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">处理状态</div>
                        <div class="item-input-datebox">
                            <select
                                class="easyui-combobox"
                                name="status"
                                style="width:100%;"
                                data-options="{editable:false,panelHeight:'auto',onChange: function(){
                                    PolemonitorAlertIndex.search();
                                }}"
                            >
                                <option value="">不限</option>
                                <option value="new">未处理</option>
                                <option value="no_need">无需处理</option>
                                <option value="acceptance">已受理</option>
                                <option value="processing">处理中</option>
                                <option value="end">已完结</option>
                            </select>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">处理时间</div>
                        <div class="item-input-datebox">
                            <input
                                class="easyui-datebox"
                                name="deal_datetime_start"
                                style="width:92px;"
                                data-options="{editable:false,onChange: function(){
                                    PolemonitorAlertIndex.search();
                                }}"
                            /> - <input
                                class="easyui-datebox"
                                name="deal_datetime_end"
                                style="width:92px;"
                                data-options="{editable:false,onChange: function(){
                                    PolemonitorAlertIndex.search();
                                }}"
                            />
                        </div>
                    </li>
                    <li class="search-button">
                        <button class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button onclick="return PolemonitorAlertIndex.reset()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
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
<div id="easyui_dialog_polemonitor_alert_index_set_param"></div>
<div id="easyui_dialog_polemonitor_alert_index_shotmessage_rule"></div>
<div id="easyui_dialog_polemonitor_alert_index_deal"></div>
<script>
    var PolemonitorAlertIndex = {
        params: {
            url: {
                getListData: "<?= yii::$app->urlManager->createUrl(['polemonitor/alert/get-list']); ?>",
                setParam: "<?= yii::$app->urlManager->createUrl(['polemonitor/alert/set-param']); ?>",
                shotmessageRule: "<?= yii::$app->urlManager->createUrl(['polemonitor/alert/shotmessage-rule']); ?>",
                deal: "<?= yii::$app->urlManager->createUrl(['polemonitor/alert/deal']); ?>",
                exportWithCondition: "<?= yii::$app->urlManager->createUrl(['polemonitor/alert/export-with-condition']); ?>"
            },
            forms: {
                search: $('#search_form_polemonitor_alert_index')
            },
            windows: {
                setParam: $('#easyui_dialog_polemonitor_alert_index_set_param'),
                shotmessageRule: $('#easyui_dialog_polemonitor_alert_index_shotmessage_rule'),
                deal: $('#easyui_dialog_polemonitor_alert_index_deal')

            },
            datagrid: $('#easyui_datagrid_cpolemonitor_alert_index'),
        },
        init: function(){
            //获取列表数据
            this.params.datagrid.datagrid({  
                method: 'get', 
                url:this.params.url.getListData,   
                fit: true,
                border: false,
                toolbar: "#easyui_datagrid_cpolemonitor_alert_index_toolbar",
                pagination: true,
                loadMsg: '数据加载中...',
                striped: true,
                checkOnSelect: true,
                rownumbers: true,
                singleSelect: true,
                frozenColumns: [[
                    {field: 'ck',checkbox: true},
                    {field: 'id',hidden: true},
                    {field: 'cs_name',title: "充电站",align: 'left',halign: 'center',sortable: true,width: 160},
                ]],
                columns:[[
                    {field: 'dev_addr',title: "充电桩",align: 'center',sortable: true,width: 80},
                    {field: 'alert_name',title: '报警项目',width: 120,align: 'center',sortable: true},
                    {field: 'alert_level',title: '报警级别',width: 80,align: 'center',sortable: true,formatter: function(value){
                        if(value == 1){
                            return '<span style="background-color:#FF4040;color:#fff;padding:2px 5px;">'+value+'</span>';
                        }else if(value > 1 && value <= 4){
                            return '<span style="background-color:#FFCC01;color:#fff;padding:2px 5px;">'+value+'</span>';
                        }else{
                            return '<span style="background-color:#05CD69;color:#fff;padding:2px 5px;">'+value+'</span>';
                        }
                    }},
                    {field: 'event_code',title: "报警编码",align: 'center',sortable: true,width: 80},
                    {field: 'alert_dispose',title: '报警处理方式',width: 120,align: 'center',formatter: function(value){
                        value = parseInt(value);
                        switch(value){
                            case 0:
                                return '不报警';
                            case 1:
                                return '后台报警';
                            case 2:
                                return '后台报警，短信报警';
                        }
                    }},
                    {field: 'alert_content',title: '报警内容',width: 200,align: 'left',halign: 'center'},
                    {field: 'event_desc',title: '故障描述',width: 140,align: 'left',halign: 'center'},
                    {field: 'happen_datetime',title: '报警时间',width: 135,align: 'center',sortable: true},
                    {field: 'times',title: '报警次数',width: 80,align: 'center',sortable: true},
                    {field: 'status',title: '报警处理状态',width: 100,align: 'center',sortable: true,formatter: function(value){
                        switch(value){
                            case 'new':
                                return '<span style="background-color:#FF4040;color:#fff;padding:2px 5px;">未处理</span>';
                            case 'no_need':
                                return '<span style="background-color:#FFCC01;color:#fff;padding:2px 5px;">无需处理</span>';
                            case 'acceptance':
                                return '<span style="background-color:#FFCC01;color:#fff;padding:2px 5px;">已受理</span>';
                            case 'processing':
                                return '<span style="background-color:#FFCC01;color:#fff;padding:2px 5px;">处理中</span>';
                            case 'end':
                                return '<span style="background-color:#05CD69;color:#fff;padding:2px 5px;">已完结</span>';
                        }
                    }},
                    {field: 'deal_date',title: '处理时间',width: 80,align: 'center',sortable: true},
                    {field: 'deal_way',title: '处理方法',width: 200,align: 'left',halign: 'center'},
                    {field: 'username',title: '操作人员',width: 100,align: 'left',halign: 'center'}
                ]]
            });
            //查询表单
            this.params.forms.search.submit(function(){
                var data = {};
                var searchCondition = $(this).serializeArray();
                for(var i in searchCondition){
                    data[searchCondition[i]['name']] = searchCondition[i]['value'];
                }
                PolemonitorAlertIndex.params.datagrid.datagrid('load',data);
                return false;
            });
            //初始化异常警告窗口
            this.params.windows.setParam.dialog({
                title: '报警参数设置',
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
                        var easyuiForm = $('#easyui_form_polemonitor_alert_set_param');
                        if(!easyuiForm.form('validate')){
                            return;
                        }
                        $.ajax({
                            type: 'post',
                            url: PolemonitorAlertIndex.params.url.setParam,
                            data: easyuiForm.serialize(),
                            dataType: 'json',
                            success: function(rData){
                                if(rData.error){
                                    $.messager.alert('操作失败',rData.msg,'error');
                                }else{
                                    $.messager.alert('操作成功',rData.msg,'info');
                                    PolemonitorAlertIndex.params.windows.setParam.dialog('close');
                                }
                            }
                        });
                    }    
                }, {
                    text: '取消',
                    iconCls: 'icon-cancel',
                    handler: function () {
                        PolemonitorAlertIndex.params.windows.setParam.dialog('close');
                    }
                }]
            });
            //初始化设置短信推送规则窗口
            this.params.windows.shotmessageRule.dialog({
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
                        var easyuiForm = $('#easyui_form_polemonitor_alert_shotmessage_rule');
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
                            url: PolemonitorAlertIndex.params.url.shotmessageRule,
                            data: easyuiForm.serialize(),
                            dataType: 'json',
                            success: function(rData){
                                if(rData.error){
                                    $.messager.alert('操作失败',rData.msg,'error');
                                }else{
                                    $.messager.alert('操作成功',rData.msg,'info');
                                    PolemonitorAlertIndex.params.windows.shotmessageRule.dialog('refresh',PolemonitorAlertIndex.params.url.shotmessageRule);
                                }
                            }
                        });
                    }    
                }, {
                    text: '取消',
                    iconCls: 'icon-cancel',
                    handler: function () {
                        PolemonitorAlertIndex.params.windows.shotmessageRule.dialog('close');
                    }
                }]
            });
            //初始化异常警告处理窗口
            this.params.windows.deal.dialog({
                title: '电桩告警处理',
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
                        PolemonitorAlertDeal.saveData();
                    }    
                }, {
                    text: '取消',
                    iconCls: 'icon-cancel',
                    handler: function () {
                        PolemonitorAlertIndex.params.windows.deal.dialog('close');
                    }
                }]
            });
        },
        getSelected: function(all){
            var datagrid = this.params.datagrid;
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
        setParam: function(){
            this.params.windows.setParam
                .dialog('open')
                .dialog('refresh',this.params.url.setParam);
        },
        shotmessageRule: function(){
            this.params.windows.shotmessageRule
                .dialog('open')
                .dialog('refresh',this.params.url.shotmessageRule);
        },
        deal: function(){
            var selectedRow = this.getSelected();
            if(!selectedRow){
                return false;
            }
            this.params.windows.deal
                .dialog('open')
                .dialog('refresh',this.params.url.deal+'&id='+selectedRow.id);
        },
        exportWithCondition: function(){
            var searchForm = this.params.forms.search;
            window.open(this.params.url.exportWithCondition+'&'+searchForm.serialize());
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
                        url: PolemonitorAlertIndex.params.url.remove,
                        data: {id: selectedRow.id},
                        dataType: 'json',
                        success: function(rData){
                            if(rData.error){
                                $.messager.alert('操作失败',rData.msg,'error');
                            }else{
                                $.messager.alert('操作成功',rData.msg,'info');
                                $('#easyui_datagrid_cpolemonitor_alert_index').datagrid('reload');
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
        search: function(){
            $('#search_form_polemonitor_alert_index').submit();
        },
        reset: function(){
            var searchForm = $('#search_form_polemonitor_alert_index');
            searchForm.form('reset');
            searchForm.submit();
        }
    };
    PolemonitorAlertIndex.init();
</script>