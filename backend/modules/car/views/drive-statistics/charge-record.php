<table id="easyuidatagrid_car_drive_statistics_charge_record"></table>
<div id="easyuidatagrid_car_drive_statistics_charge_record_toolbar">
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
    var CarDriveStatisticsChargeRecord = {
        params: {
            url: {
                ChargeRecordGetList: "<?= yii::$app->urlManager->createUrl([
                    'car/drive-statistics/charge-record-get-list',
                    'validIdStr'=>$validIdStr,
                    'startDate'=>$startDate,
                    'endDate'=>$endDate
                ]); ?>"
            }
        },
        init: function(){
            $('#easyuidatagrid_car_drive_statistics_charge_record').datagrid({
                method: 'get',
                url: this.params.url.ChargeRecordGetList,
                fit: true,
                border: false,
                toolbar: "#easyuidatagrid_car_drive_statistics_charge_record_toolbar",
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
                    {field: 'start_soc',title: '开始SOC(%)',width: 90,halign: 'center',align: 'right'},
                    {field: 'end_soc',title: '结束SOC(%)',width: 90,halign: 'center',align: 'right'},
                    {field: 'charge_soc',title: '<span style="color:#FF8000;">充电SOC(%)</span>',width: 90,halign: 'center',align: 'right'}
                ]]
            });
        }
    };
    CarDriveStatisticsChargeRecord.init();
</script>