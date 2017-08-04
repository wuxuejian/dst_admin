<table id="easyuidatagrid_car_drive_statistics_drive_record"></table>
<div id="easyuidatagrid_car_drive_statistics_drive_record_toolbar">
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
    var CarDriveStatisticsDriveRecord = {
        params: {
            url: {
                driveRecordGetList: "<?= yii::$app->urlManager->createUrl([
                    'car/drive-statistics/drive-record-get-list',
                    'statusChangeIds'=>$statusChangeIds,
                    'startDate'=>$startDate,
                    'endDate'=>$endDate
                ]); ?>"
            }
        },
        init: function(){
            $('#easyuidatagrid_car_drive_statistics_drive_record').datagrid({
                method: 'get', 
                url: this.params.url.driveRecordGetList,  
                fit: true,
                border: false,
                toolbar: "#easyuidatagrid_car_drive_statistics_drive_record_toolbar",
                loadMsg: '数据加载中...',
                striped: true,
                checkOnSelect: true,
                rownumbers: true,
                singleSelect: true,
                pagination: true,
                pageSize: 20,
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
                    {field: 'use_time',title: '使用时间',width: 80,align: 'center'},
                    {field: 'start_longitude_latitude',title: '起始经纬度',width: 140,align: 'left',halign:'center'},
                    {field: 'end_longitude_latitude',title: '结束经纬度',width: 140,align: 'left',halign:'center'},
                    {field: 'position',title: '起始位置',width: 200,align: 'left',halign:'center'},
                    {field: 'end_position',title: '结束位置',width: 200,align: 'left',halign:'center'},
                    {field: 'null',title: '',width: 10,align: 'center'}
                ]]
            });
        }
    };
    CarDriveStatisticsDriveRecord.init();
</script>