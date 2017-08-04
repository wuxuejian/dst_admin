<?php
    if($realtimeData){
?>
<div style="padding: 5px 0 10px 0">
    <?php foreach($buttons as $val){ ?>
    <a href="javascript:<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon']; ?>'"><?= $val['text']; ?></a>
    <?php } ?>
</div>
<table id="easyui-datagrid-car-overview-monitor-info"></table>
<div id="easyui-window-car-overview-monitor-info-detail"></div>
<div id="easyui-window-car-overview-monitor-info-analysis-battery"></div>
<div id="easyui-window-car-overview-monitor-info-realtime-position"></div>
<div id="easyui-window-car-overview-monitor-info-car-track"></div>
<script>
    var CarOverviewMonitorInfo = {
        init: function(){
            $('#easyui-datagrid-car-overview-monitor-info').datagrid({  
                border: false,
                columns:[[
                    {field: 'car_vin',title: '车架号',width: 120,align: 'left',sortable: true},
                    {field: 'data_source',title: '数据来源',width: 80,align: 'left',sortable: true},
                    {
                        field: 'collection_datetime',title: '数据采集时间',width: 120,align: 'center',
                        sortable: true,
                        formatter: function(value){
                            return formatDateToString(value,true);
                        }
                    },
                    {
                        field: 'update_datetime',title: '记录更新时间',width: 120,align: 'left',
                        sortable: true,
                        formatter: function(value){
                            return formatDateToString(value,true);
                        }
                    },
                    {field: 'total_driving_mileage',title: '累计行驶里程(km)',width: 120,align: 'left',sortable: true},
                    {
                        field: 'position_effective',title: '定位有效',width: 80,
                        align: 'left',sortable: true,
                        formatter: function(value){
                            if(value == 0){
                                return '有效';
                            }
                            return '无效';
                        }
                    },
                    {
                        field: 'latitude_type',title: '南北纬',width: 80,align: 'left',
                        sortable: true,
                        formatter: function(value){
                            if(value == 0){
                                return '北纬';
                            }
                            return '南纬';
                        }
                    },
                    {
                        field: 'longitude_type',title: '东西经',width: 80,align: 'left',
                        sortable: true,
                        formatter: function(value){
                            if(value == 0){
                                return '东经';
                            }
                            return '西经';
                        }
                    },
                    {field: 'latitude_value',title: '纬度值',width: 90,align: 'left',sortable: true},
                    {field: 'longitude_value',title: '经度值',width: 90,align: 'left',sortable: true},
                    {field: 'speed',title: '车速(km/h)',width: 100,align: 'left',sortable: true},
                    {field: 'direction',title: '方向',width: 80,align: 'left',sortable: true},
                    {field: 'battery_package_soc',title: '电池电量(%)',width: 100,align: 'left',sortable: true},
                    {
                        field: 'car_current_status',title: '车辆行驶状态',width: 100,align: 'left',
                        sortable: true,
                        formatter: function(value){
                            switch(value){
                                case '0':
                                    return '停止';
                                case '1':
                                    return '行驶';
                                case '2':
                                    return '充电';    
                            }
                            return '错误';
                        }
                    }
                ]]
            });
            $('#easyui-datagrid-car-overview-monitor-info').datagrid('appendRow',<?= json_encode($realtimeData); ?>);
            //初始化查看窗口
            $('#easyui-window-car-overview-monitor-info-detail').window({
                title: '车辆实时数据总览',
                iconCls: 'icon-search',
                width: 1200,   
                height: 600,
                closed: true,   
                cache: true,   
                modal: true,
                draggable: false,
                resizable: false,
                collapsible: false,
                minimizable: false, 
                maximizable: true,
                onClose: function(){
                    clearInterval(CarmonitorRealtimeDetail.timer);//关闭计时器
                    delete CarmonitorRealtimeDetail;//销毁变量
                    $(this).window('clear');
                }       
            });
            //查看车辆电池数据分析
            $('#easyui-window-car-overview-monitor-info-analysis-battery').window({
                title: '车辆电池数据分析',
                iconCls: 'icon-search',
                width: 1200,   
                height: 600,   
                closed: true,   
                cache: true,   
                modal: true,
                collapsible: false,
                minimizable: false, 
                maximizable: true,
                onClose: function(){
                    $(this).window('clear')
                }       
            }); 
            //车辆实时定位
            $('#easyui-window-car-overview-monitor-info-realtime-position').window({
                title: '车辆实时定位',
                width: 1200,
                height: 600,
                closed: true,  
                cache: true,  
                modal: true,
                collapsible: false,
                minimizable: false,
                maximizable: true,
                content: '<iframe id="iframe-car-overview-monitor-info-realtime-position" style="width:100%;height:100%;" frameborder="none"></iframe>',
                onClose: function(){
                    //关闭计时器
                    var iframe = document.getElementById('iframe-car-overview-monitor-info-realtime-position');
                    iframe.contentWindow.clearTimer();
                    //$(this).window('clear');
                }  
            });
            //车辆运行轨迹
            $('#easyui-window-car-overview-monitor-info-car-track').window({
                title: '车辆运行轨迹',
                width: 1200,
                height: 600,
                closed: true,  
                cache: true,  
                modal: true,
                collapsible: false,
                minimizable: false,
                maximizable: true,
                onClose: function(){
                    $(this).window('clear');
                }  
            });
        },
        //查看详细
        detail: function(){
            var easyuiWindow = $('#easyui-window-car-overview-monitor-info-detail');
            easyuiWindow.window('open');
            easyuiWindow.window('refresh',"<?= yii::$app->urlManager->createUrl(['carmonitor/realtime/detail','car_vin'=>$realtimeData['car_vin']]); ?>");
        },
        //电池数据分析
        analysisBattery: function(){
            var easyuiWindow = $('#easyui-window-car-overview-monitor-info-analysis-battery');
            easyuiWindow.window('open');
            easyuiWindow.window('refresh',"<?= yii::$app->urlManager->createUrl(['carmonitor/analysis/battery','car_vin'=>$realtimeData['car_vin']]); ?>");
        },
        //车辆实时定位
        realtimePosition: function(){
            var easyuiWindow = $('#easyui-window-car-overview-monitor-info-realtime-position');
            easyuiWindow.window('open');
            var iframe = document.getElementById('iframe-car-overview-monitor-info-realtime-position');
            $(iframe.contentWindow.document.body).html('');
            $(iframe).attr('src',"<?php echo yii::$app->urlManager->createUrl(['carmonitor/realtime/realtime-position','car_vin'=>$realtimeData['car_vin']]); ?>");
        },
        //查看车辆行驶轨迹
        carTrack: function(){
            var easyuiWindow = $('#easyui-window-car-overview-monitor-info-car-track');
            easyuiWindow.window('open');
            easyuiWindow.window('refresh',"<?php echo yii::$app->urlManager->createUrl(['carmonitor/realtime/car-track','car_vin'=>$realtimeData['car_vin']]); ?>");
        }
    };
    CarOverviewMonitorInfo.init();
</script>
<?php
    }else{
?>
<div style="color:red">车辆无监控数据！</div>
<?php
    }
?>