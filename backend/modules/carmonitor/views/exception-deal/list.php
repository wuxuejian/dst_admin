<table id="easyui_datagrid_carmonitor_exception_deal_list"></table> 
<div id="easyui_datagrid_carmonitor_exception_deal_list_toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form class="easyui-form" id="search_form_carmonitor_exception_deal_list">
                <ul class="search-main">
                    <li>
                        <div class="item-name">车牌号</div>
                        <div class="item-input">
                            <input
                                class="easyui-textbox"
                                name="plate_number"
                                style="width:100%;"
                                data-options="oncharge: function(){
                                    CarmonitorExceptionDealList.search();
                                }" 
                            />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">车架号</div>
                        <div class="item-input">
                            <input
                                class="easyui-textbox"
                                name="car_vin"
                                style="width:100%;"
                                data-options="oncharge: function(){
                                    CarmonitorExceptionDealList.search();
                                }" 
                            />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">电池型号</div>
                        <div class="item-input">
                            <select
                                class="easyui-combobox"
                                name="battery_type"
                                style="width:100%;"
                                data-options="{editable:false,panelHeight:'auto',onChange: function(){
                                    CarmonitorExceptionDealList.search();
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
                        <div class="item-name">异常时间</div>
                        <div class="item-input-datebox">
                            <input
                                class="easyui-datebox"
                                name="alert_datetime_start"
                                style="width:92px;"
                                data-options="{editable:false,onChange: function(){
                                    CarmonitorExceptionDealList.search();
                                }}"
                            /> - <input
                                class="easyui-datebox"
                                name="alert_datetime_end"
                                style="width:92px;"
                                data-options="{editable:false,onChange: function(){
                                    CarmonitorExceptionDealList.search();
                                }}"
                            />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">告警等级</div>
                        <div class="item-input-datebox">
                            <select
                                class="easyui-combobox"
                                name="alert_level_start"
                                style="width:92px;"
                                data-options="{panelHeight:'auto',editable:false,onChange: function(){
                                    CarmonitorExceptionDealList.search();
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
                                data-options="{panelHeight:'auto',editable:false,onChange: function(){
                                    CarmonitorExceptionDealList.search();
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
                        <div class="item-input">
                            <select
                                class="easyui-combobox"
                                name="status"
                                style="width:100%;"
                                data-options="{editable:false,panelHeight:'auto',onChange: function(){
                                    CarmonitorExceptionDealList.search();
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
                                    CarmonitorExceptionDealList.search();
                                }}"
                            /> - <input
                                class="easyui-datebox"
                                name="deal_datetime_end"
                                style="width:92px;"
                                data-options="{editable:false,onChange: function(){
                                    CarmonitorExceptionDealList.search();
                                }}"
                            />
                        </div>
                    </li>
                    <li class="search-button">
                        <button class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button onclick="return CarmonitorExceptionDealList.reset()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
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
<div id="easyui_dialog_carmonitor_exception_deal_list_alert_deal"></div>
<script>
    var CarmonitorExceptionDealList = {
        params: {
            url: {
                getListData: "<?= yii::$app->urlManager->createUrl(['carmonitor/exception-deal/get-list-data']); ?>",
                alertDeal: "<?= yii::$app->urlManager->createUrl(['carmonitor/exception-deal/alert-deal']); ?>",
                exportWithCondition: "<?= yii::$app->urlManager->createUrl(['carmonitor/exception-deal/export-with-condition']); ?>"
            },
            forms: {
                search: $('#search_form_carmonitor_exception_deal_list')
            },
            windows: {
                alertDeal: $('#easyui_dialog_carmonitor_exception_deal_list_alert_deal')
            },
            datagrid: $('#easyui_datagrid_carmonitor_exception_deal_list'),
            alertType: <?php echo json_encode($alertType); ?>,
            config: <?php echo json_encode($config); ?>
        },
        init: function(){
            //获取列表数据
            this.params.datagrid.datagrid({  
                method: 'get', 
                url:this.params.url.getListData,   
                fit: true,
                border: false,
                toolbar: "#easyui_datagrid_carmonitor_exception_deal_list_toolbar",
                pagination: true,
                loadMsg: '数据加载中...',
                striped: true,
                checkOnSelect: true,
                rownumbers: true,
                singleSelect: true,
                frozenColumns: [[
                    {field: 'ck',checkbox: true},
                    {field: 'id',hidden: true},
                    {field: 'plate_number',title: "车牌号",align: 'center',sortable: true,width: 70},
                ]],
                columns:[[
                    {field: 'car_vin',title: "车架号",align: 'center',sortable: true,width: 120},
                    {field: 'battery_type',title: '电池型号',width: 80,align: 'center',sortable: true,formatter: function(value){
                        if(!value){
                            return '';
                        }
                        if(CarmonitorExceptionDealList.params.config.battery_type[value]){
                            return CarmonitorExceptionDealList.params.config.battery_type[value].text;
                        }
                    }},
                    {field: 'alert_type',title: '报警项目',width: 120,align: 'center',sortable: true,formatter: function(value){
                        if(CarmonitorExceptionDealList.params.alertType[value]){
                            return CarmonitorExceptionDealList.params.alertType[value];
                        }
                    }},
                    {field: 'alert_level',title: '报警级别',width: 80,align: 'center',sortable: true,formatter: function(value){
                        if(value == 1){
                            return '<span style="background-color:#FF4040;color:#fff;padding:2px 5px;">'+value+'</span>';
                        }else if(value > 1 && value <= 4){
                            return '<span style="background-color:#FFCC01;color:#fff;padding:2px 5px;">'+value+'</span>';
                        }else{
                            return '<span style="background-color:#05CD69;color:#fff;padding:2px 5px;">'+value+'</span>';
                        }
                    }},
                    {field: 'alert_dispose',title: '报警处理方式',width: 100,align: 'center',formatter: function(value){
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
                    {field: 'alert_value',title: '告警值',width: 80,align: 'center'},
                    {field: 'alert_datetime',title: '报警时间',width: 135,align: 'center',sortable: true},
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
                CarmonitorExceptionDealList.params.datagrid.datagrid('load',data);
                return false;
            });
            //初始化异常警告处理窗口
            this.params.windows.alertDeal.dialog({
                title: '车辆告警处理',
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
                        CarmonitorExceptionAlertDeal.saveData();
                    }    
                }, {
                    text: '取消',
                    iconCls: 'icon-cancel',
                    handler: function () {
                        CarmonitorExceptionDealList.params.windows.alertDeal.dialog('close');
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
        alertDeal: function(){
            var selectedRow = this.getSelected();
            if(!selectedRow){
                return false;
            }
            this.params.windows.alertDeal
                .dialog('open')
                .dialog('refresh',this.params.url.alertDeal+'&id='+selectedRow.id+'&car_vin='+selectedRow.car_vin);
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
                        url: CarmonitorExceptionDealList.params.url.remove,
                        data: {id: selectedRow.id},
                        dataType: 'json',
                        success: function(rData){
                            if(rData.error){
                                $.messager.alert('操作失败',rData.msg,'error');
                            }else{
                                $.messager.alert('操作成功',rData.msg,'info');
                                $('#easyui_datagrid_carmonitor_exception_deal_list').datagrid('reload');
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
            $('#search_form_carmonitor_exception_deal_list').submit();
        },
        reset: function(){
            var searchForm = $('#search_form_carmonitor_exception_deal_list');
            searchForm.form('reset');
            searchForm.submit();
        }
    };
    CarmonitorExceptionDealList.init();
</script>