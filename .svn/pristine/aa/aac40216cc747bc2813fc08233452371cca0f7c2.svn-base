<div style="padding:10px 20px;">
    <div style="padding:10px 0px 20px 0px;">
        以下是根据车辆<?php echo isset($car_vin) ? '【'.$car_vin.'】' : ''; ?>最近一次提交的电池数据分析得出的结论，仅供参考：
    </div>
    <table id="carmonitorBatteryMaintainIndex_verifyCorrectWin_datagrid"></table>
    <div style="padding:10px 0px 20px 0px;color:red;" id="carmonitorBatteryMaintainIndex_waitingDetectNewTip">
        正在进行最新衰减检测，请稍等...
    </div>
</div>

<script>
    $('#carmonitorBatteryMaintainIndex_verifyCorrectWin_datagrid').datagrid({
        fit: true,
        border: false,
        pagination: false,
        scrollbarSize:0,
        columns: [[
            {field: 'soc_deviation_status', title: 'SOC偏移', width: 60, align: 'center', sortable: true,
                formatter: function (value, row, index) {
                    switch (value) {
                        case 'ABNORMAL':
                            return '<span style="color:red;">异常</span>';
                        case 'NORMAL':
                            return '正常';
                        case 'INVALID':
                            return '<span style="color:#ddd;">无效</span>';
                        default:
                            return value;
                    }
                }
            },
            {field: 'soc_deviation_val', title: '偏移量', width: 60, align: 'center', sortable: true},
            {field: 'soc_deviation_res', title: '判定结果', width: 200, halign: 'center', sortable: true},
            {field: 'capacitance_attenuation_status', title: '电池容量衰减', width: 90, align: 'center', sortable: true,
                formatter: function (value, row, index) {
                    switch (value) {
                        case 'ABNORMAL':
                            return '<span style="color:red;">异常</span>';
                        case 'NORMAL':
                            return '正常';
                        case 'INVALID':
                            return '<span style="color:#ccc;">无效</span>';
                        default:
                            return value;
                    }
                }
            },
            {field: 'voltage_deviation_val', title: '压差偏移量', width: 80, align: 'center', sortable: true},
            {field: 'capacitance_attenuation_res', title: '判定结果', width: 240, halign: 'center', sortable: true},
            {field: 'capacitance_deviation_status', title: '电池容量偏差', width: 90, align: 'center', sortable: true,
                formatter: function (value, row, index) {
                    switch (value) {
                        case 'ABNORMAL':
                            return '<span style="color:red;">异常</span>';
                        case 'NORMAL':
                            return '正常';
                        case 'INVALID':
                            return '<span style="color:#ddd;">无效</span>';
                        default:
                            return value;
                    }
                }
            },
            {field: 'capacitance_deviation_res', title: '判定结果', width: 110, halign: 'center', sortable: true}
        ]]
    });

    //进行最新电池衰减检测
    $.ajax({
        "type": 'get',
        "url": '<?php echo yii::$app->urlManager->createUrl(['carmonitor/battery-maintain/verify-correct']); ?>',
        "data": {
            'isDetectNew': 1,
            'car_vin': '<?php echo $car_vin; ?>'
        },
        "dataType": 'json',
        "success": function(rData){
            if(rData.status){
                $('#carmonitorBatteryMaintainIndex_verifyCorrectWin_datagrid').datagrid('appendRow',{
                    'soc_deviation_status': rData.verifyInfo.soc_deviation_status,
                    'soc_deviation_val': rData.verifyInfo.soc_deviation_val,
                    'soc_deviation_res': rData.verifyInfo.soc_deviation_res,
                    'capacitance_attenuation_status': rData.verifyInfo.capacitance_attenuation_status,
                    'voltage_deviation_val': rData.verifyInfo.voltage_deviation_val,
                    'capacitance_attenuation_res': rData.verifyInfo.capacitance_attenuation_res,
                    'capacitance_deviation_status': rData.verifyInfo.capacitance_deviation_status,
                    'capacitance_deviation_res': rData.verifyInfo.capacitance_deviation_res
                });
                $('#carmonitorBatteryMaintainIndex_waitingDetectNewTip').hide();
                $('#carmonitorBatteryMaintainIndex_datagrid').datagrid('reload');
            }else{
                $('#carmonitorBatteryMaintainIndex_waitingDetectNewTip').html(rData.info);
            }
        }
    });

</script>
