<table id="PolemonitorIndexScanByFrontMachine_monitorBatteryWin_datagrid"></table>

<div id="PolemonitorIndexScanByFrontMachine_monitorBatteryWin_charts"
     style="width:1150px;height:300px;margin:5px auto;border:0px solid gray;"
    ></div>

<script>
    // 初始数据
    var monitorBatteryWin_initDatas = <?php echo json_encode($initDatas); ?>;

    // 拼接好列表查询数据时所需的查询参数：当前前置机id 和 当前充电设备id
    var monitorBatteryWin_queryStr = '&fmId=' + monitorBatteryWin_initDatas.fmId +
                                     '&devId=' + monitorBatteryWin_initDatas.devId;

    var PolemonitorIndexScanByFrontMachine_monitorBatteryWin = {
		init: function(){
			//获取列表数据
			$('#PolemonitorIndexScanByFrontMachine_monitorBatteryWin_datagrid').datagrid({
				method: 'get', 
				url:"<?php echo yii::$app->urlManager->createUrl(['polemonitor/battery/monitor-battery-get-list']); ?>" + monitorBatteryWin_queryStr,
				fit: false,height:'220',width:'100%',
				border: false,
				pagination: true,
				loadMsg: '数据加载中...',
				striped: true,
				checkOnSelect: true,
				rownumbers: true,
				singleSelect: true,
				frozenColumns: [[
					{field: 'ck',checkbox: true}, 
					{field: 'DEV_ID',title: '设备ID',align: 'center',hidden: false},
					{field: 'TIME_TAG',title: '数据时间',width: 150,align: 'center',sortable: true},
				]],
				columns:[[
					{field: 'INNER_ID',title: '充电机在充电设备内部编号',width: 100,align: 'center',sortable: true},
					{field: 'CAR_NO',title: '车号',width: 80,align: 'center',sortable: true},
                    {field: 'CURR_MEAS',title: '电流测量值',width: 80,halign:'center',align: 'right',sortable: true},
                    {field: 'VOLT_MEAS',title: '电压测量值',width: 80,halign:'center',align: 'right',sortable: true},
                    {field: 'SOC',title: '电池组SOC',width: 80,halign:'center',align: 'right',sortable: true},
                    {field: 'MAX_CHARGE_VOLT',title: '最大允许充电电压',width: 120,halign:'center',align: 'right',sortable: true},
					{field: 'MAX_CHARGE_CURR',title: '最大允许充电电流',width: 120,halign:'center',align: 'right',sortable: true},
					{field: 'MAX_CHARGE_TEMP',title: '最大允许充电温度',width: 120,halign:'center',align: 'right',sortable: true},
					{field: 'MAX_SINGLE_VOLT',title: '最高单体电压',width: 80,halign:'center',align: 'right',sortable: true},
                    {field: 'MAX_SINGEL_VOLT_NO',title: '最高单体电压电池编号',width: 80,align: 'center',sortable: true},
                    {field: 'WRITE_TIME',title: '写库时间',width: 150,align: 'center',sortable: true},
                    {field: 'BATTERY_GRP_NO',title: '电池组编号',width: 80,align: 'center',sortable: true},
                    {field: 'MANU_DATE',title: '电池组生产日期',width: 150,align: 'center',sortable: true},
                    {field: 'BATTERY_TYPE',title: '电池类型',width: 80,halign:'center',align: 'right',sortable: true},
					{field: 'RATED_CURRENT',title: '额定电流',width: 80,halign:'center',align: 'right',sortable: true},
					{field: 'RATED_VOLTAGE',title: '额定电压',width: 80,halign:'center',align: 'right',sortable: true},
					{field: 'MAX_PB_TEMP',title: '最高动力电池温度',width: 120,halign:'center',align: 'right',sortable: true},
					{field: 'MAX_PB_MEAS',title: '最高动力电池测量点',width: 120,halign:'center',align: 'right',sortable: true},
					{field: 'MIN_PB_TEMP',title: '最低动力电池温度',width: 120,halign:'center',align: 'right',sortable: true},
					{field: 'MIN_PB_MEAS',title: '最低动力电池测量点',width: 120,halign:'center',align: 'right',sortable: true}
				]],
                onLoadSuccess: function(data){ // 加载成功就绘图表，否则报错。
                    if (data.errInfo) {
                        $.messager.show({
                            title:'获取电池监控数据失败',
                            msg: '<span style="color:red;">' + data.errInfo + '</span>'
                        });
                    }else{
                        PolemonitorIndexScanByFrontMachine_monitorBatteryWin.drawCharts();
                    }
                }
			});
		},
        // 绘制图表
        drawCharts: function(){
            var monitorBatteryWin_charts = echarts.init(document.getElementById('PolemonitorIndexScanByFrontMachine_monitorBatteryWin_charts'));
            var option = {
                title : {
                    text : '电流、电压和电池SOC测量值分析',
                    subtext : '仅按表格当前页数据绘制'
                },
                toolbox: {
                    show : true,
                    feature : {
                        magicType : {show: true, type: ['line', 'bar']},
                        restore : {show: true}
                        //saveAsImage : {show: true}
                    }
                },
                tooltip : {
                    trigger: 'axis'
                },
                grid: {
                    'y':70,'y2':40
                },
                legend: {
                    data:['电流','电压','电池SOC']
                },
                xAxis : [
                    {
                        type : 'category',
                        name: '时间',
                        boundaryGap : false,
                        data : ['周一','周二','周三','周四','周五','周六','周日']
                    }
                ],
                yAxis : [
                    {
                        type : 'value'
                    }
                ],
                series : [
                    {
                        name:'电流',
                        type:'line',
                        itemStyle : { normal: {label : {show: true, position: 'top',textStyle:{color:'#FF7F50'}}}},
                        data:[320, 332, 301, 334, 390, 330, 320]
                    },
                    {
                        name:'电压',
                        type:'line',
                        itemStyle : { normal: {label : {show: true, position: 'top',textStyle:{color:'#87CEFA'}}}},
                        data:[120, 132, 101, 134, 90, 230, 210]
                    },
                    {
                        name:'电池SOC',
                        type:'line',
                        itemStyle : { normal: {label : {show: true, position: 'top',textStyle:{color:'#DA70D6'}}}},
                        data:[220, 182, 191, 234, 290, 330, 310]
                    }
                ]
            };
            // 以表格数据重绘图表
            var rows = $('#PolemonitorIndexScanByFrontMachine_monitorBatteryWin_datagrid').datagrid('getRows');
            var data_xAxis = [];
            var data_1 = []; var data_2 = []; var data_3 = [];
            for (var i=rows.length-1;i>=0;i--) {
                var TIME_TAG = rows[i].TIME_TAG;
                data_xAxis.push(TIME_TAG.slice(0,10)+'\n\r'+TIME_TAG.slice(10));
                data_1.push(rows[i].CURR_MEAS != null ? rows[i].CURR_MEAS : '0.0000');
                data_2.push(rows[i].VOLT_MEAS != null ? rows[i].VOLT_MEAS : '0.0000');
                data_3.push(rows[i].SOC != null ? rows[i].SOC : '0.0000');
            }
            option.xAxis[0].data = data_xAxis;
            option.series[0].data = data_1;
            option.series[1].data = data_2;
            option.series[2].data = data_3;
            monitorBatteryWin_charts.setOption(option);
        }
	}
	// 执行初始化函数
	PolemonitorIndexScanByFrontMachine_monitorBatteryWin.init();
</script>