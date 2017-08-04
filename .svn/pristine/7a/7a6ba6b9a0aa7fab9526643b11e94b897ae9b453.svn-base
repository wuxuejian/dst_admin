<table id="easyui_datagrid_car_drive_statistics_drive_record_index"></table> 
<div id="easyui_datagrid_car_drive_statistics_drive_record_index_toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">
        <div class="data-search-form">
            <form id="search_form_car_drive_statistics_drive_record_index">
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
                        <a href="javascript:void(0)" onclick="CarDriveStatisticsDriveRecordIndex.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                        <a href="javascript:void(0)" onclick="CarDriveStatisticsDriveRecordIndex.reset()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</a>
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
    var CarDriveStatisticsDriveRecordIndex = {
        params: {
            url: {
                driveRecordSearch:"<?= yii::$app->urlManager->createUrl(['car/drive-statistics/drive-record-search']); ?>",
                driveRecordGetList:"<?= yii::$app->urlManager->createUrl(['car/drive-statistics/drive-record-get-list']); ?>",
                driveRecordExport:"<?= yii::$app->urlManager->createUrl(['car/drive-statistics/drive-record-export']); ?>"
            },
            searchConditions:{} //将用于保存表单查询后返回的数据
        },
        init: function(){
            var easyuiDatagrid = $('#easyui_datagrid_car_drive_statistics_drive_record_index');
            easyuiDatagrid.datagrid({  
            method: 'post', //因为查询后重载表格要传递大量的id所以这里要用post方法
            url: this.params.url.driveRecordGetList,
            fit: true,
            border: false,
            toolbar: "#easyui_datagrid_car_drive_statistics_drive_record_index_toolbar",
            pagination: true,
            pageSize: 20,
            showFooter: true,
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
                    {field: 'car_current_status',title: '车辆状态',width: 60,align: 'center',formatter: function(value){
                            switch(value){
                                case 0:
                                    return '停止';
                                    break;
                                case 1:
                                    return '行驶';
                                    break;
                            }
                    }},
                    {field: 'collection_datetime',title: '采集时间',width: 130,align: 'center'},
                    {field: 'total_driving_mileage',title: '当前里程(km)',width: 85,halign: 'center',align: 'right'},
                    {field: 'battery_package_soc',title: '当前SOC(%)',width: 80,halign: 'center',align: 'right'},
                    {field: 'drive_mileage',title: '行驶里程(km)',width: 85,halign: 'center',align: 'right'},
                    {field: 'use_soc',title: '消耗SOC(%)',width: 80,halign: 'center',align: 'right'},
                    {field: 'use_time',title: '使用时间',width: 80,halign: 'center',align: 'right'},
                    {field: 'start_longitude_latitude',title: '起始经纬度',width: 140,align: 'left',halign:'center'},
                    {field: 'end_longitude_latitude',title: '结束经纬度',width: 140,align: 'left',halign:'center'},
                    {field: 'position',title: '起始位置',width: 200,align: 'left',halign:'center'},
                    {field: 'end_position',title: '结束位置',width: 200,align: 'left',halign:'center'},
                    {field: 'null',title: '',width: 10,align: 'center'}
                ]]
            }); 
            //构建查询表单
            var searchForm = $('#search_form_car_drive_statistics_drive_record_index');
            /**查询表单提交事件**/
            searchForm.submit(function(){
                var data = {};
                var searchCondition = $(this).serializeArray();
                for(var i in searchCondition){
                    data[searchCondition[i]['name']] = $.trim(searchCondition[i]['value']);
                }
                //根据用户所选查询条件查询车辆状态改变的记录的id
                $.ajax({
                    type: 'get',
                    url: CarDriveStatisticsDriveRecordIndex.params.url.driveRecordSearch,
                    data: data,
                    dataType: 'json',
                    success: function(rData){
                        if(rData.status){
                            var data = {
                                car_vin:rData.car_vin,
                                statusChangeIds:rData.statusChangeIds,
                                startDate:rData.startDate,
                                endDate:rData.endDate
                            };
                            easyuiDatagrid.datagrid('load',data); //重新加载表格数据
                            CarDriveStatisticsDriveRecordIndex.params.searchConditions = data; //保存表单查询数据以便导出时使用
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
            $('#search_form_car_drive_statistics_drive_record_index').submit();
        },
        //重置
        reset: function(){
            var easyuiForm = $('#search_form_car_drive_statistics_drive_record_index');
            easyuiForm.form('reset');
        },
        //导出
        driveRecordExport: function(){
            var url = CarDriveStatisticsDriveRecordIndex.params.url.driveRecordExport;
            var conditions = CarDriveStatisticsDriveRecordIndex.params.searchConditions;
            var conditionStr = '';
            for(var i in conditions){
                conditionStr += i + '=' + conditions[i] + '&';
            }
            window.open(url+'&'+conditionStr);
        }
    };
    //执行初始化
    CarDriveStatisticsDriveRecordIndex.init();
</script>