<table id="easyui_datagrid_car_drive_statistics_charge_record_index"></table> 
<div id="easyui_datagrid_car_drive_statistics_charge_record_index_toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">
        <div class="data-search-form">
            <form id="search_form_car_drive_statistics_charge_record_index">
                <ul class="search-main">
                    <li>
                        <div class="item-name">车牌号</div>
                        <div class="item-input">
                            <input
                                class="easyui-textbox"
                                name="plate_number"
                                style="width:200px;"
                            />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">车架号</div>
                        <div class="item-input">
                            <input
                                class="easyui-textbox"
                                name="car_vin"
                                style="width:200px;"
                            />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">查询类型</div>
                        <div class="item-input">
                            <select
                                class="easyui-combobox"
                                name="search_type"
                                style="width:100%;"
                                data-options="{editable:false,panelHeight:'auto'}"
                            >
                                <option value="">--不限--</option>
                                <option value="this_day">当天</option>
                                <option value="this_week">本周</option>
                                <option value="this_month">本月</option>
                            </select>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">统计日期</div>
                        <div class="item-input">
                            <input class="easyui-datebox" type="text" name="start_date" style="width:90px;" />
                            -
                            <input class="easyui-datebox" type="text" name="end_date" style="width:90px;" />
                        </div>
                    </li>
                    <li class="search-button">
                        <a href="javascript:void(0)" onclick="CarDriveStatisticsChargeRecordIndex.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                        <a href="javascript:void(0)" onclick="CarDriveStatisticsChargeRecordIndex.reset()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</a>
                    </li>
                </ul>
            </form>
        </div>
    </div>
    <?php if($buttons){ ?>
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
    var CarDriveStatisticsChargeRecordIndex = {
        params: {
            url: {
                chargeRecordSearch:"<?= yii::$app->urlManager->createUrl(['car/drive-statistics/charge-record-search']); ?>",
                chargeRecordGetList:"<?= yii::$app->urlManager->createUrl(['car/drive-statistics/charge-record-get-list']); ?>",
                chargeRecordExport:"<?= yii::$app->urlManager->createUrl(['car/drive-statistics/charge-record-export']); ?>"
            },
            searchConditions:{} //将用于保存表单查询后返回的数据
        },
        init: function(){
            var easyuiDatagrid = $('#easyui_datagrid_car_drive_statistics_charge_record_index');
            easyuiDatagrid.datagrid({  
				method: 'post', //因为查询后重载表格要传递大量的id所以这里要用post方法
                url: this.params.url.chargeRecordGetList,
                fit: true,
                border: false,
                toolbar: "#easyui_datagrid_car_drive_statistics_charge_record_index_toolbar",
                pagination: true,
                pageSize: 20,
                loadMsg: '数据加载中...',
                striped: true,
                checkOnSelect: true,
                rownumbers: true,
                singleSelect: true,
                frozenColumns: [[
                        {field: 'ck',checkbox: true},
                        {field: 'id',title: 'id',hidden: true},
                        {field: 'plate_number',title: '车牌号',width: 80,align: 'center'}
                    ]],
                columns: [[
                    {field: 'car_vin',title: '车架号',width: 120,align: 'center'},
                    {field: 'start_time',title: '开始充电时间',width: 130,align: 'center',
                        formatter: function(value){
                            return formatDateToString(value,true);
                        }
                    },
                    {field: 'end_time',title: '结束充电时间',width: 130,align: 'center',
                        formatter: function(value){
                            return formatDateToString(value,true);
                        }
                    },
                    {field: 'charge_time',title: '<span style="color:#FF8000;">充电时长</span>',width: 100,halign: 'center',align: 'right',
                        formatter: function(value){
                            var sec = parseInt(value);
                            var h = Math.floor(sec/3600);
                            var diff = sec - h*3600;
                            var m = Math.floor(diff/60);
                            var diff2 = diff - m*60;
                            var str = '';
                            if(h){
                                str += h + '时';
                            }
                            if(m){
                                str += m + '分';
                            }
                            if(diff2){
                                str += diff2 + '秒';
                            }
                            return str;
                        }
                    },
                    {field: 'start_soc',title: '开始SOC(%)',width: 90,halign: 'center',align: 'right',
                        formatter: function(value){
                            return Number(value).toFixed(1);
                        }
                    },
                    {field: 'end_soc',title: '结束SOC(%)',width: 90,halign: 'center',align: 'right',
                        formatter: function(value){
                            return Number(value).toFixed(1);
                        }
                    },
                    {field: 'charge_soc',title: '<span style="color:#FF8000;">充电SOC(%)</span>',width: 90,halign: 'center',align: 'right',
                        formatter: function(value){
                            return Number(value).toFixed(1);
                        }
                    }
                ]]
            });
            //构建查询表单
            var searchForm = $('#search_form_car_drive_statistics_charge_record_index');
            /**查询表单提交事件**/
            searchForm.submit(function(){
                var data = {};
                var searchCondition = $(this).serializeArray();
                for(var i in searchCondition){
                    data[searchCondition[i]['name']] = $.trim(searchCondition[i]['value']);
                }
                //根据用户所选查询条件查询车辆充电的起止记录id
                $.ajax({
                    type: 'get',
                    url: CarDriveStatisticsChargeRecordIndex.params.url.chargeRecordSearch,
                    data: data,
                    dataType: 'json',
                    success: function(rData){
                        if(rData.status){
                            var data = {
                                car_vin:rData.car_vin,
                                validIdStr:rData.validIdStr,
                                startDate:rData.startDate,
                                endDate:rData.endDate
                            };
                            easyuiDatagrid.datagrid('load',data); //重新加载表格数据
                            CarDriveStatisticsChargeRecordIndex.params.searchConditions = data; //保存表单查询数据以便导出时使用
                        }else{
                            $.messager.alert('错误',rData.info,'error');
                        }
                    }
                });
                return false;
            });
        },
        //查询
        search: function(){
            $('#search_form_car_drive_statistics_charge_record_index').submit();
        },
        //重置
        reset: function(){
            var easyuiForm = $('#search_form_car_drive_statistics_charge_record_index');
            easyuiForm.form('reset');
        },
        //导出
        chargeRecordExport: function(){
            var url = CarDriveStatisticsChargeRecordIndex.params.url.chargeRecordExport;
            var conditions = CarDriveStatisticsChargeRecordIndex.params.searchConditions;
            var conditionStr = '';
            for(var i in conditions){
                conditionStr += i + '=' + conditions[i] + '&';
            }
            window.open(url+'&'+conditionStr);
        }
    };
    //执行初始化
    CarDriveStatisticsChargeRecordIndex.init();
</script>