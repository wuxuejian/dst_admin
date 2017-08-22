<div id="echart-carmonitorgb-realtime-detail-dashboard" style="width:1100px;height:340px;margin:0 auto;"></div>
<div style="border:1px dashed #ddd;border-radius:4px;margin:10px;">
    <div style="padding:10px;font-size:18px;color:#333;font-weight:bold;">车辆基本数据</div>
    <form id="easyui-from-carmonitorgb-realtime-detail-baseinfo" style="overflow:hidden;">
        <?php
			$useColumns = ['companyNo'=>'数据来源','collectionDatetime'=>'采集时间','updateDatetime'=>'更新时间',
				'carVin'=>'车架号','totalDrivingMileage'=>'累计行驶里程','longitudeValue'=>'经度值','latitudeValue'=>'纬度值',
				'carStatus'=>'车辆状态','carChargeStatus'=>'充电状态','soc'=>'SOC'
            ];

            foreach($useColumns as $key=>$val){
        ?>
				<ul style="list-style:none;float:left;width:135px;margin:0;padding:4px 10px;overflow:hidden;">
					<li style="font-weight:bold;color:#666;line-height:18px;"><?= $val; ?></li>
					<li><input type="text" style="border:none;background:none;outline:none;color:#777;" name="<?= $key; ?>" value="" readonly="true"></li>
				</ul>
        <?php
            }
        ?>
        <div style="clear:both;"></div>
    </form>
</div>
<div id="echart-carmonitorgb-realtime-detail-battery-t" style="width:1150px;height:300px;margin:0 auto;"></div>
<div id="echart-carmonitorgb-realtime-detail-battery-v" style="width:1150px;height:300px;margin:0 auto;"></div>
<!-- 电机数据开始 -->
<div style="border:1px dashed #ddd;border-radius:4px;margin:10px;">
    <div style="padding:10px;font-size:18px;color:#333;font-weight:bold;">驱动电机数据</div>
    <form id="easyui-from-carmonitorgb-realtime-detail-moter" style="overflow:hidden;">
        <?php
			$useColumns = [
                'moterControllerTemperature'=>'电机控制器温度','moterSpeed'=>'电机转速','moterTemperature'=>'电机温度',
                'moterVoltage'=>'电机电压','moterCurrent'=>'电机电流'
            ];
            foreach($useColumns as $key=>$val){
        ?>
				<ul style="list-style:none;float:left;width:135px;margin:0;padding:4px 10px;overflow:hidden;">
					<li style="font-weight:bold;color:#666;line-height:18px;"><?= $val; ?></li>
					<li><input type="text" style="border:none;background:none;outline:none;color:#777;" name="<?= $key; ?>" value="" readonly="true"></li>
				</ul>
        <?php
            }
        ?>
        <div style="clear:both;"></div>
    </form>
</div>
<!-- 电机数据结束 -->
<!-- 极值数据开始 -->
<div style="border:1px dashed #ddd;border-radius:4px;margin:10px;">
    <div style="padding:10px;font-size:18px;color:#333;font-weight:bold;">极值数据</div>
    <form id="easyui-from-carmonitorgb-realtime-detail-extremum" style="overflow:hidden;">
        <?php
			$useColumns = [
                'batteryPackageTotalVoltage'=>'总电压','batteryPackageCurrent'=>'总电流',
				'batteryPackageHvSerialNum'=>'高压电池包号','batterySingleHvSerialNum'=>'高压电池号','batterySingleHvValue'=>'电压最高值',
				'batteryPackageLvSerialNum'=>'低压电池包号','batterySingleLvSerialNum'=>'低压电池号','batterySingleLvValue'=>'电压最低值',
				'batteryPackageHtSerialNum'=>'高温电池包号','batterySingleHtSerialNum'=>'最高温度探针序号','batterySingleHtValue'=>'最高温度值',
				'batteryPackageLtSerialNum'=>'低温电池包号','batterySingleLtSerialNum'=>'最低温度探针序号','batterySingleLtValue'=>'最低温度值'
            ];
            foreach($useColumns as $key=>$val){
        ?>
				<ul style="list-style:none;float:left;width:135px;margin:0;padding:4px 10px;overflow:hidden;">
					<li style="font-weight:bold;color:#666;line-height:18px;"><?= $val; ?></li>
					<li><input type="text" style="border:none;background:none;outline:none;color:#777;" name="<?= $key; ?>" value="" readonly="true"></li>
				</ul>
        <?php
            }
        ?>
        <div style="clear:both;"></div>
    </form>
</div>
<!-- 极值数据结束 -->
<!-- 其它数据开始 -->
<div style="border:1px dashed #ddd;border-radius:4px;margin:10px;">
    <div style="padding:10px;font-size:18px;color:#333;font-weight:bold;">其它数据</div>
    <form id="easyui-from-carmonitorgb-realtime-detail-otherdata" style="overflow:hidden;">
        <?php
			$useColumns = [
                'acceleratorPedalTravel'=>'加速踏板行程','brakePedal'=>'制动踏板状态',
				'batteryPackageResistanceValue'=>'绝缘电阻'
            ];
            foreach($useColumns as $key=>$val){
        ?>
				<ul style="list-style:none;float:left;width:135px;margin:0;padding:4px 10px;overflow:hidden;">
					<li style="font-weight:bold;color:#666;line-height:18px;"><?= $val; ?></li>
					<li><input type="text" style="border:none;background:none;outline:none;color:#777;" name="<?= $key; ?>" value="" readonly="true"></li>
				</ul>
        <?php
            }
        ?>
        <div style="clear:both;"></div>
    </form>
</div>
<!-- 极值数据结束 -->
<script>
    var CarmonitorgbRealtimeDetail = new Object();
    CarmonitorgbRealtimeDetail.chartObject = {
        "dashboardChart": "",
        "batteryTemChart": "",
        "batteryVolChart": "",
    };
    CarmonitorgbRealtimeDetail.init = function(){
        try{
            this.drawDashboard(<?= json_encode($data); ?>);
            this.drawBatteryTemperature(<?= json_encode($data); ?>);
            this.drawBatteryVoltage(<?= json_encode($data); ?>);
            CarmonitorgbRealtimeDetail.loadData(<?= json_encode($data); ?>);//装载初始化数据
			
        }catch(e){
            return;
        }
        this.getData();//定时获取数据
    }
    //绘制仪表数据
    CarmonitorgbRealtimeDetail.drawDashboard = function(d){
        //图表对象是否已经被缓存
        if(this.chartObject.dashboardChart){
            var dashboardChart = this.chartObject.dashboardChart;
        }else{
            var dashboardChart = echarts.init(document.getElementById('echart-carmonitorgb-realtime-detail-dashboard'));
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
        var speed = d.speed;
        var moterSpeed = d.moterSpeed;
        var soc = parseInt(d.soc * 100) / 100;
        option.series[0].data[0].value = speed;
        option.series[1].data[0].value = moterSpeed;
        option.series[2].data[0].value = soc;
        dashboardChart.setOption(option,true);
    }
	//绘制电池温度数据
    CarmonitorgbRealtimeDetail.drawBatteryTemperature = function(d){
        //图表对象是否已经被缓存
        if(this.chartObject.batteryTemChart){
            var chat = this.chartObject.batteryTemChart;
        }else{
            var chat = echarts.init(document.getElementById('echart-carmonitorgb-realtime-detail-battery-t'));
            this.chartObject.batteryTemChart = chat;
        }
        var legendData = [];
        var seriesData = [];
		var totalPackageNum = d.battteryTemperatureData.length;
		var totalProbeNum = 0;
		var maxProbeNum = 0;

        for(var i=0;i<totalPackageNum;i++){
			maxProbeNum = d.battteryTemperatureData[i].totalProbe>maxProbeNum?d.battteryTemperatureData[i].totalProbe:maxProbeNum;
			totalProbeNum += d.battteryTemperatureData[i].totalProbe;
            legendData.push('电池包'+(i+1));
            seriesData.push({
                "name": '电池包'+(i+1),
                "type": 'line',
                "data": d.battteryTemperatureData[i].battteryTemperatureList,
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
        for(var i=0;i<maxProbeNum;i++){
            xAxisData.push('探针'+(i+1));
        }
        var option = {
            title : {
                text: '电池包温度数据',
                subtext: '电池包总数： '+totalPackageNum+' 总探针数：'+totalProbeNum
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
    CarmonitorgbRealtimeDetail.drawBatteryVoltage = function(d){
        //图表对象是否已经被缓存
        if(this.chartObject.batteryVolChart){
            var chat = this.chartObject.batteryVolChart;
        }else{
            var chat = echarts.init(document.getElementById('echart-carmonitorgb-realtime-detail-battery-v'));
            this.chartObject.batteryVolChart = chat;
        }
		var legendData = [];
        var seriesData = [];
		var totalPackageNum = d.battteryVoltageData.length;

		var totalBatteryNum = 0;
		var maxBatteryNum = 0;

        for(var i=0;i<totalPackageNum;i++){
			if(d.battteryVoltageData[i].totalBattery==0){
				continue;
			}
			maxBatteryNum = d.battteryVoltageData[i].totalBattery>maxBatteryNum?d.battteryVoltageData[i].totalBattery:maxBatteryNum;
			totalBatteryNum += d.battteryVoltageData[i].totalBattery;
            legendData.push('电池包'+(i+1));
            seriesData.push({
				"name": '电池包'+(i+1),
                "type":'line',
                "data": d.battteryVoltageData[i].battteryVoltageList,
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
        for(var i=0;i<maxBatteryNum;i++){
            xAxisData.push('电池'+(i+1));
        }
        var option = {
            title : {
                text: '电池包电压数据',
                subtext: '电池包总数：'+totalPackageNum+' 电池总数：'+totalBatteryNum
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
    //定时获取数据
    CarmonitorgbRealtimeDetail.timer = 0;
    CarmonitorgbRealtimeDetail.getData = function(){
        if(this.timer){
            clearInterval(this.timer);
        }
        this.timer = setInterval(function(){
            $.ajax({
                    "type": "post",
                    "url": "<?= yii::$app->urlManager->createUrl(['carmonitorgb/realtime/detail']); ?>",
                    "data": {"car_vin": "<?= $carVin;?>"},
                    "dataType": "json",
                    "success": function(d){
                        try{
                            CarmonitorgbRealtimeDetail.drawDashboard(d);
                            CarmonitorgbRealtimeDetail.drawBatteryTemperature(d);
                            CarmonitorgbRealtimeDetail.drawBatteryVoltage(d);
                            CarmonitorgbRealtimeDetail.loadData(d);
                        }catch(e){
                            return;
                        }
                    }
                });
        },8000);
    }
    //装载显示数据
    CarmonitorgbRealtimeDetail.loadData = function(d){
        var data = d;
        //数据格式化
        data.collectionDatetime = formatDateToString(data.collectionDatetime,true);
        data.updateDatetime = formatDateToString(data.updateDatetime,true);
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
        data.soc += ' %';
        data.battery_package_power += ' kw.h';
        data.battery_single_hv_value += ' V';
        data.battery_single_lv_value += ' V';
        data.battery_single_ht_value += ' ℃';
        data.battery_single_lt_value += ' ℃';
        data.battery_package_resistance_value += ' KΩ';
        data.battery_package_fuel_consumption += ' 毫升/100km';
        $('#easyui-from-carmonitorgb-realtime-detail-baseinfo').form('load',data);
        $('#easyui-from-carmonitorgb-realtime-detail-moter').form('load',data);
        $('#easyui-from-carmonitorgb-realtime-detail-extremum').form('load',data);
        $('#easyui-from-carmonitorgb-realtime-detail-otherdata').form('load',data);
    }
    CarmonitorgbRealtimeDetail.init();
</script>