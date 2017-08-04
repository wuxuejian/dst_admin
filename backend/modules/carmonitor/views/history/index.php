<table id="easyui_datagrid_carmonitor_history_index"></table> 
<div id="easyui_datagrid_carmonitor_history_index_toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form class="easyui-form" id="search_form_carmonitor_history_index">
                <ul class="search-main">
                	<li>
                        <div class="item-name">车牌号</div>
                        <div class="item-input">
                            <input
                                class="easyui-textbox"
                                name="plate_number"
                                style="width:100%;"
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
                            />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">开始时间</div>
                        <div class="item-input">
                            <input
                                class="easyui-datetimebox"
                                name="start_date"
                                style="width:100%;"
                                value="<?= date('Y-m-d'); ?>"
                            />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">结束时间</div>
                        <div class="item-input">
                            <input
                                class="easyui-datetimebox"
                                name="end_date"
                                style="width:100%;"
                            />
                        </div>
                    </li>
                    <li class="search-button">
                        <button class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button onclick="return CarmonitorHistoryIndex.reset()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
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
<script>
    var CarmonitorHistoryIndex = {
        params: {
            url: {
                getListData: "<?= yii::$app->urlManager->createUrl(['carmonitor/history/get-list-data']); ?>",
                export: "<?= yii::$app->urlManager->createUrl(['carmonitor/history/export']); ?>"
            }
        },
        init: function(){
            //获取列表数据
            $('#easyui_datagrid_carmonitor_history_index').datagrid({  
                method: 'get', 
                url:this.params.url.getListData,   
                fit: true,
                border: false,
                toolbar: "#easyui_datagrid_carmonitor_history_index_toolbar",
                pagination: true,
                loadMsg: '数据加载中...',
                striped: true,
                checkOnSelect: true,
                rownumbers: true,
                singleSelect: true,
                frozenColumns: [[
                    {field: 'ck',checkbox: true},    
                    {field: 'car_vin',title: '车架号',width: 120,align: 'center'}
                ]],
                columns:[[
                    {field: 'plate_number',title: '车牌号',width: 70,align: 'center'},
                    {
                        field: 'collection_datetime',title: '采集时间',width: 130,align: 'center',
                        sortable: true,
                        formatter: function(value){
                            if(value && value > 0){
                                return formatDateToString(value,true);
                            }
                        }
                    },
                    {
                        field: 'update_datetime',title: '更新时间',width: 130,align: 'center',
                        sortable: true,
                        formatter: function(value){
                            if(value && value > 0){
                                return formatDateToString(value,true);
                            }
                        }
                    },
                    {
                        field: 'ignition_datetime',title: '点火时间',width: 130,align: 'center',
                        formatter: function(value){
                            if(value && value > 0){
                                return formatDateToString(value,true);
                            }
                        }
                    },
                    {
                        field: 'flameout_datetime',title: '熄火时间',width: 130,align: 'center',
                        formatter: function(value){
                            if(value && value > 0){
                                return formatDateToString(value,true);
                            }
                        }
                    },
                    {field: 'total_driving_mileage',title: '里程',width: 80,align: 'center'},
                    {field: 'position_effective',title: '定位有效',width: 80,align: 'center'},
                    {field: 'latitude_type',title: '纬度类型',width: 80,align: 'center'},
                    {field: 'longitude_type',title: '经度类型',width: 80,align: 'center'},
                    {field: 'longitude_value',title: '经度值',width: 80,align: 'center'},
                    {field: 'latitude_value',title: '纬度型',width: 80,align: 'center'},
                    {field: 'speed',title: '速度',width: 80,align: 'center'},
                    {field: 'direction',title: '方向',width: 80,align: 'center'},
                    {field: 'gear',title: '档位',width: 100,align: 'center'},
                    {field: 'accelerator_pedal',title: '加速踏板行程值',width: 100,align: 'center'},
                    {field: 'brake_pedal_distance',title: '制动踏板行程值',width: 100,align: 'center'},
                    {field: 'moter_controller_temperature',title: '电机控制器温度',width: 100,align: 'center'},
                    {field: 'moter_speed',title: '电机转速',width: 80,align: 'center'},
                    {field: 'moter_temperature',title: '电机温度',width: 80,align: 'center'},
                    {field: 'moter_voltage',title: '电机电压',width: 80,align: 'center'},
                    {field: 'moter_current',title: '电机电流',width: 80,align: 'center'},
                    {field: 'moter_generatrix_current',title: '电机母线电流',width: 80,align: 'center'},
                    {field: 'air_condition_temperature',title: '空调预设温度',width: 80,align: 'center'},
                    {field: 'brake_pedal_status',title: '制动踏板状态',width: 80,align: 'center'},
                    {field: 'power_system_ready',title: '动力系统就绪',width: 80,align: 'center'},
                    {field: 'emergency_electric_request',title: '紧急正电请求',width: 80,align: 'center'},
                    {field: 'car_current_status',title: '车辆状态',width: 80,align: 'center',formatter:function(value){
                        switch(value){
                            case 0:
                                return '';
                            case 1:
                                return '';
                            case 2:
                                return '';
                        }
                    }},
                    {field: 'battery_package_voltage',title: '电池包电压数据',width: 100,align: 'center'},
                    {field: 'battery_package_total_voltage',title: '电池总电压',width: 80,align: 'center'},
                    {field: 'battery_package_temperature',title: '电池包温度数据',width: 100,align: 'center'},
                    {field: 'battery_package_current',title: '电池包电流',width: 90,align: 'center'},
                    {field: 'battery_package_soc',title: 'SOC',width: 80,align: 'center'},
                    {field: 'battery_package_power',title: '剩余能量',width: 80,align: 'center'},
                    {field: 'battery_package_hv_serial_num',title: '电压最高包号',width: 80,align: 'center'},
                    {field: 'battery_single_hv_serial_num',title: '电压最高电池号',width: 100,align: 'center'},
                    {field: 'battery_single_hv_value',title: '电压最高值',width: 80,align: 'center'},
                    {field: 'battery_package_lv_serial_num',title: '电压最低包号',width: 80,align: 'center'},
                    {field: 'battery_single_lv_serial_num',title: '电压最低电池号',width: 100,align: 'center'},
                    {field: 'battery_single_lv_value',title: '电压最低值',width: 80,align: 'center'},
                    {field: 'battery_package_ht_serial_num',title: '高温电池包号',width: 80,align: 'center'},
                    {field: 'battery_single_ht_serial_num',title: '高温电池号',width: 80,align: 'center'},
                    {field: 'battery_single_ht_value',title: '温度最高值',width: 80,align: 'center'},
                    {field: 'battery_package_lt_serial_num',title: '低温电池包号',width: 80,align: 'center'},
                    {field: 'battery_single_lt_serial_num',title: '低温探针号',width: 80,align: 'center'},
                    {field: 'battery_single_lt_value',title: '最低温度值',width: 80,align: 'center'},
                    {field: 'battery_package_resistance_value',title: '绝缘电阻值',width: 80,align: 'center'},
                    {field: 'battery_package_equilibria_active',title: '电池均衡活动',width: 80,align: 'center'},
                    {field: 'battery_package_fuel_consumption',title: '液体燃料消耗',width: 80,align: 'center'},
                    {field: 'data_hex',title: '上报数据',width: 300}
                ]]
            });
            //查询表单
            $('#search_form_carmonitor_history_index').submit(function(){
                if(!$(this).form('validate')){
                    return false;
                }
                var start_date = $(this).find('input[textboxname=start_date]').datebox('getValue');
                var end_date = $(this).find('input[textboxname=end_date]').datebox('getValue');
                if(start_date && end_date){
                    if(start_date.substr(5,2) != end_date.substr(5,2)){
                        $.messager.alert('操作失败','无法统计跨月份的监控数据！','error');
                        return false;
                    }
                }
                var data = {};
                var searchCondition = $(this).serializeArray();
                for(var i in searchCondition){
                    data[searchCondition[i]['name']] = searchCondition[i]['value'];
                }
                $('#easyui_datagrid_carmonitor_history_index').datagrid('load',data);
                return false;
            });
        },
        getSelected: function(all){
            var datagrid = $('#easyui_datagrid_carmonitor_history_index');
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
        search: function(){
            $('#search_form_carmonitor_history_index').submit();
        },
        reset: function(){
            var searchForm = $('#search_form_carmonitor_history_index');
            searchForm.form('reset');
            searchForm.submit();
        },
        export: function(){
            var searchForm = $('#search_form_carmonitor_history_index');
            if(!searchForm.form('validate')){
                return false;
            }
            var start_date = searchForm.find('input[textboxname=start_date]').datebox('getValue');
            var end_date = searchForm.find('input[textboxname=end_date]').datebox('getValue');
            if(start_date && end_date){
                if(start_date.substr(5,2) != end_date.substr(5,2)){
                    $.messager.alert('操作失败','无法导出跨月份的监控数据！','error');
                    return false;
                }
            }
            window.open(this.params.url.export+'&'+searchForm.serialize());
        }
    };
    CarmonitorHistoryIndex.init();
</script>