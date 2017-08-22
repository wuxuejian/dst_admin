<table id="easyui_datagrid_carmonitorgb_history_index"></table> 
<div id="easyui_datagrid_carmonitorgb_history_index_toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search_form_carmonitorgb_history_index">
                <ul class="search-main">
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
                        <button onclick="return CarmonitorgbHistoryIndex.reset()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
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
    var CarmonitorgbHistoryIndex = {
        params: {
            url: {
                getListData: "<?= yii::$app->urlManager->createUrl(['carmonitorgb/history/get-list-data']); ?>",
                export: "<?= yii::$app->urlManager->createUrl(['carmonitorgb/history/export']); ?>"
            }
        },
        init: function(){
            //获取列表数据
            $('#easyui_datagrid_carmonitorgb_history_index').datagrid({  
                method: 'get', 
                url:this.params.url.getListData,   
                fit: true,
                border: false,
                toolbar: "#easyui_datagrid_carmonitorgb_history_index_toolbar",
                pagination: true,
                loadMsg: '数据加载中...',
                striped: true,
                checkOnSelect: true,
                rownumbers: true,
                singleSelect: true,
                frozenColumns: [[
                    {field: 'ck',checkbox: true},    
                    {field: 'carVin',title: '车架号',width: 130,align: 'center'},
                    {field: 'deviceNo',title: '设备号',width: 130,align: 'center'}
                ]],
                columns:[[
                    {
                        field: 'collectionDatetime',title: '采集时间',width: 130,align: 'center',
                        sortable: true,
                        formatter: function(value){
                            if(value && value > 0){
                                return formatDateToString(value,true);
                            }
                        }
                    },
                    {
                        field: 'updateDatetime',title: '更新时间',width: 130,align: 'center',
                        sortable: true,
                        formatter: function(value){
                            if(value && value > 0){
                                return formatDateToString(value,true);
                            }
                        }
                    },
                    {field: 'totalDrivingMileage',title: '里程',width: 80,align: 'center'},
                    {field: 'positionEffective',title: '定位有效',width: 80,align: 'center'},
                    {field: 'latitudeType',title: '纬度类型',width: 80,align: 'center'},
                    {field: 'longitudeType',title: '经度类型',width: 80,align: 'center'},
                    {field: 'longitudeValue',title: '经度值',width: 80,align: 'center'},
                    {field: 'latitudeValue',title: '纬度值',width: 80,align: 'center'},
                    {field: 'speed',title: '速度',width: 80,align: 'center'},
                    {field: 'gear',title: '档位',width: 100,align: 'center'},
                    {field: 'moterControllerTemperature',title: '电机控制器温度',width: 100,align: 'center'},
                    {field: 'moterSpeed',title: '电机转速',width: 80,align: 'center'},
                    {field: 'moterTorque',title: '电机转矩',width: 80,align: 'center'},
                    {field: 'moterTemperature',title: '电机温度',width: 80,align: 'center'},
                    {field: 'moterVoltage',title: '电机电压',width: 80,align: 'center'},
                    {field: 'moterCurrent',title: '电机电流',width: 80,align: 'center'},
                    {field: 'carStatus',title: '车辆状态',width: 80,align: 'center',formatter:function(value){
                        switch(value){
                            case 1:
                                return '启动';
                            case 2:
                                return '熄火';
                            case 3:
                                return '其它';
                            case 254:
                                return '异常';
                            case 255:
                                return '无效';
                            case 255:
                                return value;
                        }
                    }},
                    {field: 'carChargeStatus',title: '充电状态',width: 80,align: 'center',formatter:function(value){
                        switch(value){
                            case 0:
                                return '默认';
                            case 1:
                                return '放电';
                            case 2:
                                return '充电';
                            case 3:
                                return '其它';
                            default:
                            	return value;
                        }
                    }},
                    {field: 'batteryPackageTotalVoltage',title: '电池总电压',width: 80,align: 'center'},
                    {field: 'batteryPackageCurrent',title: '电池包电流',width: 90,align: 'center'},
                    {field: 'soc',title: 'SOC',width: 80,align: 'center'},
                    {field: 'batteryPackageHvSerialNum',title: '电压最高包号',width: 80,align: 'center'},
                    {field: 'batterySingleHvSerialNum',title: '电压最高电池号',width: 100,align: 'center'},
                    {field: 'batterySingleHvValue',title: '电压最高值',width: 80,align: 'center'},
                    {field: 'batteryPackageLvSerialNum',title: '电压最低包号',width: 80,align: 'center'},
                    {field: 'batterySingleLvSerialNum',title: '电压最低电池号',width: 100,align: 'center'},
                    {field: 'batterySingleLvValue',title: '电压最低值',width: 80,align: 'center'},
                    {field: 'batteryPackageHtSerialNum',title: '高温电池包号',width: 80,align: 'center'},
                    {field: 'batterySingleHtSerialNum',title: '高温电池号',width: 80,align: 'center'},
                    {field: 'batterySingleHtValue',title: '温度最高值',width: 80,align: 'center'},
                    {field: 'batteryPackageLtSerialNum',title: '低温电池包号',width: 80,align: 'center'},
                    {field: 'batterySingleLtSerialNum',title: '低温探针号',width: 80,align: 'center'},
                    {field: 'batterySingleLtValue',title: '温度值最低',width: 80,align: 'center'},
                    {field: 'batteryPackageResistanceValue',title: '绝缘电阻',width: 80,align: 'center'},
                    {field: 'acceleratorPedalTravel',title: '加速踏板行程',width: 80,align: 'center'},
                    {field: 'brakePedal',title: '制动踏板状态',width: 80,align: 'center'},
                    {field: 'battteryVoltageData',title: '可充电储能装置电池电压',width: 160,formatter:function(value){
                    	if(value == null){
                    		return "";
                    	}else {
                    		return JSON.stringify(value); 
                    	}
                    }},
                    {field: 'battteryTemperatureData',title: '可充电储能装置电池温度',width: 160,formatter:function(value){
                    	if(value == null){
                    		return "";
                    	}else {
                    		return JSON.stringify(value); 
                    	}
                    }},
					{field: 'relayStatus',title: '继电器状态',width: 50,align: 'center'},
                    {field: 'dataHex',title: '上报数据',width: 300}
                ]]
            });
            //查询表单
            $('#search_form_carmonitorgb_history_index').submit(function(){
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
                $('#easyui_datagrid_carmonitorgb_history_index').datagrid('load',data);
                return false;
            });
        },
        getSelected: function(all){
            var datagrid = $('#easyui_datagrid_carmonitorgb_history_index');
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
            $('#search_form_carmonitorgb_history_index').submit();
        },
        reset: function(){
            var searchForm = $('#search_form_carmonitorgb_history_index');
            searchForm.form('reset');
            searchForm.submit();
        },
        export: function(){
            var searchForm = $('#search_form_carmonitorgb_history_index');
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
    CarmonitorgbHistoryIndex.init();
</script>