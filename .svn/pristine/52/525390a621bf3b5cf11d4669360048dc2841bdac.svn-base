<div id="echart-carmonitor-detect-detail-dashboard" style="width:1100px;height:340px;margin:0 auto;"></div>
<div style="border:1px dashed #ddd;border-radius:4px;margin:10px;">
    <div style="padding:10px;font-size:18px;color:#333;font-weight:bold;">车辆基本数据</div>
    <form id="easyui-from-carmonitor-detect-detail-baseinfo" style="overflow:hidden;">
        <?php
            $useColumns = ['data_source','collection_datetime','update_datetime',
                'car_vin','total_driving_mileage','position_effective','latitude_type',
                'longitude_type','longitude_value','latitude_value','speed','direction',
                'battery_package_soc','car_current_status'
            ];
            foreach($data as $key=>$val){
                if(in_array($key,$useColumns)){
        ?>
                    <ul style="list-style:none;float:left;width:135px;margin:0;padding:4px 10px;overflow:hidden;">
                        <li style="font-weight:bold;color:#666;line-height:18px;"><?= $attributeLabels[$key]; ?></li>
                        <li><input type="text" style="border:none;background:none;outline:none;color:#777;" name="<?= $key; ?>" value="" readonly="true"></li>
                    </ul>
        <?php
                }
            }
        ?>
        <div style="clear:both;"></div>
    </form>
</div>
<div id="echart-carmonitor-detect-detail-battery-t" style="width:1150px;height:300px;margin:0 auto;"></div>
<div id="echart-carmonitor-detect-detail-battery-v" style="width:1150px;height:300px;margin:0 auto;"></div>
<!-- 电机数据开始 -->
<div style="border:1px dashed #ddd;border-radius:4px;margin:10px;">
    <div style="padding:10px;font-size:18px;color:#333;font-weight:bold;">驱动电机数据</div>
    <form id="easyui-from-carmonitor-detect-detail-moter" style="overflow:hidden;">
        <?php
            $useColumns = [
                'moter_controller_temperature','moter_speed','moter_temperature',
                'moter_voltage','moter_current','moter_generatrix_current'
            ];
            foreach($data as $key=>$val){
                if(in_array($key,$useColumns)){
        ?>
                    <ul style="list-style:none;float:left;width:135px;margin:0;padding:4px 10px;overflow:hidden;">
                        <li style="font-weight:bold;color:#666;line-height:18px;"><?= $attributeLabels[$key]; ?></li>
                        <li><input type="text" style="border:none;background:none;outline:none;color:#777;" name="<?= $key; ?>" value="" readonly="true"></li>
                    </ul>
        <?php
                }
            }
        ?>
        <div style="clear:both;"></div>
    </form>
</div>
<!-- 电机数据结束 -->
<!-- 极值数据开始 -->
<div style="border:1px dashed #ddd;border-radius:4px;margin:10px;">
    <div style="padding:10px;font-size:18px;color:#333;font-weight:bold;">极值数据</div>
    <form id="easyui-from-carmonitor-detect-detail-extremum" style="overflow:hidden;">
        <?php
            $useColumns = [
                'battery_package_total_voltage','battery_package_current','battery_package_power',
                'battery_package_hv_serial_num','battery_single_hv_serial_num','battery_single_hv_value',
                'battery_package_lv_serial_num','battery_single_lv_serial_num','battery_single_lv_value',
                'battery_package_ht_serial_num','battery_single_ht_serial_num','battery_single_ht_value',
                'battery_package_lt_serial_num','battery_single_lt_serial_num','battery_single_lt_value'
            ];
            foreach($data as $key=>$val){
                if(in_array($key,$useColumns)){
        ?>
                    <ul style="list-style:none;float:left;width:135px;margin:0;padding:4px 10px;overflow:hidden;">
                        <li style="font-weight:bold;color:#666;line-height:18px;"><?= $attributeLabels[$key]; ?></li>
                        <li><input type="text" style="border:none;background:none;outline:none;color:#777;" name="<?= $key; ?>" value="" readonly="true"></li>
                    </ul>
        <?php
                }
            }
        ?>
        <div style="clear:both;"></div>
    </form>
</div>
<!-- 极值数据结束 -->
<!-- 其它数据开始 -->
<div style="border:1px dashed #ddd;border-radius:4px;margin:10px;">
    <div style="padding:10px;font-size:18px;color:#333;font-weight:bold;">其它数据</div>
    <form id="easyui-from-carmonitor-detect-detail-otherdata" style="overflow:hidden;">
        <?php
            $useColumns = [
                'ignition_datetime','flameout_datetime','accelerator_pedal',
                'brake_pedal_distance','air_condition_temperature','brake_pedal_status',
                'power_system_ready','emergency_electric_request','battery_package_resistance_value',
                'battery_package_equilibria_active','battery_package_fuel_consumption'
            ];
            foreach($data as $key=>$val){
                if(in_array($key,$useColumns)){
        ?>
                    <ul style="list-style:none;float:left;width:135px;margin:0;padding:4px 10px;overflow:hidden;">
                        <li style="font-weight:bold;color:#666;line-height:18px;"><?= $attributeLabels[$key]; ?></li>
                        <li><input type="text" style="border:none;background:none;outline:none;color:#777;" name="<?= $key; ?>" value="" readonly="true"></li>
                    </ul>
        <?php
                }
            }
        ?>
        <div style="clear:both;"></div>
    </form>
</div>
<!-- 极值数据结束 -->
<script>
    var CarmonitorDetectDetail = new Object();
    CarmonitorDetectDetail.chartObject = {
        "dashboardChart": "",
        "batteryTemChart": "",
        "batteryVolChart": "",
    };
    CarmonitorDetectDetail.init = function(){
        try{
            this.drawDashboard(<?= json_encode($data); ?>);
            this.drawBatteryTemperature(<?= json_encode($data); ?>);
            this.drawBatteryVoltage(<?= json_encode($data); ?>);
            CarmonitorDetectDetail.loadData(<?= json_encode($data); ?>);//装载初始化数据
        }catch(e){
            return;
        }
        //this.getData();//定时获取数据 （电池衰减检测时只获取显示检测时所依赖的那一帧数据，所有关闭定时获取数据）
    }
    //绘制仪表数据
    CarmonitorDetectDetail.drawDashboard = function(d){
        //图表对象是否已经被缓存
        if(this.chartObject.dashboardChart){
            var dashboardChart = this.chartObject.dashboardChart;
        }else{
            var dashboardChart = echarts.init(document.getElementById('echart-carmonitor-detect-detail-dashboard'));
            this.chartObject.dashboardChart = dashboardChart;
        }
        var option = {
            tooltip : {
                formatter: "{a} <br/>{c} {b}"
            },
            toolbox: {
                show : true,
                feature : {
                    //mark : {show: true},
                    //restore : {show: true},
                    //saveAsImage : {show: true}
                }
            },
            series : [
                {
                    name:'速度',
                    type:'gauge',
                    z: 3,
                    min:0,
                    max:220,
                    splitNumber:11,
                    axisLine: {            // 坐标轴线
                        lineStyle: {       // 属性lineStyle控制线条样式
                            width: 10
                        }
                    },
                    axisTick: {            // 坐标轴小标记
                        length :15,        // 属性length控制线长
                        lineStyle: {       // 属性lineStyle控制线条样式
                            color: 'auto'
                        }
                    },
                    splitLine: {           // 分隔线
                        length :20,         // 属性length控制线长
                        lineStyle: {       // 属性lineStyle（详见lineStyle）控制线条样式
                            color: 'auto'
                        }
                    },
                    title : {
                        textStyle: {       // 其余属性默认使用全局文本样式，详见TEXTSTYLE
                            fontWeight: 'bolder',
                            fontSize: 20,
                            fontStyle: 'italic'
                        }
                    },
                    detail : {
                        textStyle: {       // 其余属性默认使用全局文本样式，详见TEXTSTYLE
                            fontWeight: 'bolder'
                        }
                    },
                    data:[{value: 0, name: 'km/h'}]
                },
                {
                    name:'电机转速',
                    type:'gauge',
                    center : ['25%', '55%'],    // 默认全局居中
                    radius : '50%',
                    min:0,
                    max:7,
                    endAngle:45,
                    splitNumber:7,
                    axisLine: {            // 坐标轴线
                        lineStyle: {       // 属性lineStyle控制线条样式
                            width: 8
                        }
                    },
                    axisTick: {            // 坐标轴小标记
                        length :12,        // 属性length控制线长
                        lineStyle: {       // 属性lineStyle控制线条样式
                            color: 'auto'
                        }
                    },
                    splitLine: {           // 分隔线
                        length :20,         // 属性length控制线长
                        lineStyle: {       // 属性lineStyle（详见lineStyle）控制线条样式
                            color: 'auto'
                        }
                    },
                    pointer: {
                        width:5
                    },
                    title : {
                        offsetCenter: [0, '-30%'],       // x, y，单位px
                    },
                    detail : {
                        textStyle: {       // 其余属性默认使用全局文本样式，详见TEXTSTYLE
                            fontWeight: 'bolder'
                        }
                    },
                    data:[{value: 0, name: 'x1000 r/min'}]
                },
                {
                    name:'电池soc(%)',
                    type:'gauge',
                    center : ['75%', '50%'],    // 默认全局居中
                    radius : '50%',
                    min:0,
                    max:100,
                    startAngle:135,
                    endAngle:45,
                    splitNumber:2,
                    axisLine: {            // 坐标轴线
                        lineStyle: {       // 属性lineStyle控制线条样式
                            color: [[0.2, '#ff4500'],[0.8, '#48b'],[1, '#228b22']], 
                            width: 8
                        }
                    },
                    axisTick: {            // 坐标轴小标记
                        splitNumber:5,
                        length :10,        // 属性length控制线长
                        lineStyle: {       // 属性lineStyle控制线条样式
                            color: 'auto'
                        }
                    },
                    axisLabel: {
                        formatter:function(v){
                            switch (v + '') {
                                case '0' : return 'E';
                                case '50' : return 'soc';
                                case '100' : return 'F';
                            }
                        }
                    },
                    splitLine: {           // 分隔线
                        length :15,         // 属性length控制线长
                        lineStyle: {       // 属性lineStyle（详见lineStyle）控制线条样式
                            color: 'auto'
                        }
                    },
                    pointer: {
                        width:2
                    },
                    title : {
                        show: false
                    },
                    detail : {
                        show: false
                    },
                    data:[{value: 0, name: '%'}]
                }
            ]
        };
        var speed = parseInt(d.speed * 100) / 100;
        var moter_speed = parseInt(d.moter_speed / 10) / 100;
        var soc = parseInt(d.battery_package_soc * 100) / 100;
        option.series[0].data[0].value = speed;
        option.series[1].data[0].value = moter_speed;
        option.series[2].data[0].value = soc;
        dashboardChart.setOption(option,true);
    }
    //绘制电池温度数据
    CarmonitorDetectDetail.drawBatteryTemperature = function(d){
        //图表对象是否已经被缓存
        if(this.chartObject.batteryTemChart){
            var chat = this.chartObject.batteryTemChart;
        }else{
            var chat = echarts.init(document.getElementById('echart-carmonitor-detect-detail-battery-t'));
            this.chartObject.batteryTemChart = chat;
        }
        temData = JSON.parse(d.battery_package_temperature);
        var legendData = [];
        var seriesData = [];
        for(var i=0;i<temData.totalPackage;i++){
            legendData.push('电池包'+(i+1));
            seriesData.push({
                "name": '温度值',
                "type": 'line',
                "data": temData.temperatureList[i].probeTemperature,
                "markPoint" : {
                    "data" : [
                        {"type" : 'max', "name": '最大值'},
                        {"type" : 'min', "name": '最小值'}
                    ]
                },
                "markLine" : {
                    "data" : [
                        {"type" : 'average', "name": '平均值'}
                    ]
                }
            });
        }
        var xAxisData = [];
        for(var i=0;i<temData.temperatureList[0].totalProbe;i++){
            xAxisData.push('探针'+(i+1));
        }
        var option = {
            title : {
                text: '电池包温度数据',
                subtext: '电池包总数： '+temData.totalPackage+' 总探针数：'+temData.totalProbe
            },
            tooltip : {
                trigger: 'axis'
            },
            legend: {
                data: legendData
            },
            toolbox: {
                show : false,
                feature : {
                    mark : {show: true},
                    dataView : {show: true, readOnly: false},
                    magicType : {show: true, type: ['line', 'bar']},
                    restore : {show: true},
                    saveAsImage : {show: true}
                }
            },
            calculable : true,
            xAxis : [
                {
                    type : 'category',
                    boundaryGap : false,
                    data : xAxisData
                }
            ],
            yAxis : [
                {
                    type : 'value',
                    axisLabel : {
                        formatter: '{value} °C'
                    }
                }
            ],
            series : seriesData
        };
        chat.setOption(option,true);
    }
    //绘制电压数据
    CarmonitorDetectDetail.drawBatteryVoltage = function(d){
        //图表对象是否已经被缓存
        if(this.chartObject.batteryVolChart){
            var chat = this.chartObject.batteryVolChart;
        }else{
            var chat = echarts.init(document.getElementById('echart-carmonitor-detect-detail-battery-v'));
            this.chartObject.batteryVolChart = chat;
        }
        volData = JSON.parse(d.battery_package_voltage);
        var legendData = [];
        var seriesData = [];
        for(var i=0;i<volData.totalPackage;i++){
            legendData.push('电池包'+(i+1));
            seriesData.push({
                "name": '电压值',
                "type":'line',
                "data": volData.batteryPackage[i].battteryVoltage,
                "markPoint" : {
                    "data" : [
                        {"type" : 'max', "name": '最大值'},
                        {"type" : 'min', "name": '最小值'}
                    ]
                },
                "markLine" : {
                    "data" : [
                        {"type" : 'average', "name": '平均值'}
                    ]
                }
            });
        }
        var xAxisData = [];
        for(var i=0;i<volData.batteryPackage[0].totalBattery;i++){
            xAxisData.push('电池'+(i+1));
        }
        var option = {
            title : {
                text: '电池包电压数据',
                subtext: '电池包总数：'+volData.totalPackage+' 电池总数：'+volData.totalSingleBattery
            },
            tooltip : {
                trigger: 'axis'
            },
            legend: {
                "data": legendData
            },
            toolbox: {
                show : false,
                feature : {
                    mark : {show: true},
                    dataView : {show: true, readOnly: false},
                    magicType : {show: true, type: ['line', 'bar']},
                    restore : {show: true},
                    saveAsImage : {show: true}
                }
            },
            calculable : true,
            xAxis : [
                {
                    type : 'category',
                    boundaryGap : false,
                    data : xAxisData
                }
            ],
            yAxis : [
                {
                    type : 'value',
                    axisLabel : {
                        formatter: '{value} V'
                    }
                }
            ],
            series : seriesData
        };
        chat.setOption(option,true);
    }

    /*
    //定时获取数据
    CarmonitorDetectDetail.timer = 0;
    CarmonitorDetectDetail.getData = function(){
        if(this.timer){
            clearInterval(this.timer);
        }
        this.timer = setInterval(function(){
            $.ajax({
                    "type": "post",
                    "url": "<?= yii::$app->urlManager->createUrl(['carmonitor/detect/detail']); ?>",
                    "data": {"car_vin": "<?= $carVin;?>"},
                    "dataType": "json",
                    "success": function(d){
                        try{
                            CarmonitorDetectDetail.drawDashboard(d);
                            CarmonitorDetectDetail.drawBatteryTemperature(d);
                            CarmonitorDetectDetail.drawBatteryVoltage(d);
                            CarmonitorDetectDetail.loadData(d);
                        }catch(e){
                            return;
                        }
                    }
                });
        },8000);
    }
    */

    //装载显示数据
    CarmonitorDetectDetail.loadData = function(d){
        var data = d;
        //数据格式化
        data.collection_datetime = formatDateToString(data.collection_datetime,true);
        data.update_datetime = formatDateToString(data.update_datetime,true);
        data.ignition_datetime = formatDateToString(data.ignition_datetime,true);
        data.flameout_datetime = formatDateToString(data.flameout_datetime,true);
        data.total_driving_mileage += ' km';
        data.position_effective = data.position_effective ? '无效' : '有效' ;
        data.latitude_type = data.latitude_type ? '南纬' : '北纬' ;
        data.longitude_type = data.longitude_type ? '西经' : '东经' ;
        data.speed += ' km/h';
        data.accelerator_pedal += ' %';
        data.brake_pedal_distance += '%';
        data.moter_controller_temperature += ' ℃';
        data.moter_speed += ' r/min';
        data.moter_temperature += ' ℃';
        data.moter_voltage += ' V';
        data.moter_current += ' A';
        data.moter_generatrix_current += ' A';
        data.air_condition_temperature += ' ℃';
        data.brake_pedal_status = data.brake_pedal_status == 1 ? '启用' : '不启用' ;
        data.power_system_ready = data.brake_pedal_status ? '就绪' : '未就绪' ;
        data.emergency_electric_request = data.brake_pedal_status ? '异常' : '正常' ;
        switch(data.car_current_status){
            case 0:
                data.car_current_status = '停止';
                break;
            case 1:
                data.car_current_status = '行驶';
                break;
            case 2:
                data.car_current_status = '充电';
                break;
        }
        data.battery_package_total_voltage += ' V';
        data.battery_package_current += ' A';
        data.battery_package_soc += ' %';
        data.battery_package_power += ' kw.h';
        data.battery_single_hv_value += ' V';
        data.battery_single_lv_value += ' V';
        data.battery_single_ht_value += ' ℃';
        data.battery_single_lt_value += ' ℃';
        data.battery_package_resistance_value += ' KΩ';
        data.battery_package_fuel_consumption += ' 毫升/100km';
        $('#easyui-from-carmonitor-detect-detail-baseinfo').form('load',data);
        $('#easyui-from-carmonitor-detect-detail-moter').form('load',data);
        $('#easyui-from-carmonitor-detect-detail-extremum').form('load',data);
        $('#easyui-from-carmonitor-detect-detail-otherdata').form('load',data);
    }
    CarmonitorDetectDetail.init();
</script>