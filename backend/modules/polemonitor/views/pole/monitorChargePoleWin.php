<table id="PolemonitorIndexScanByFrontMachine_monitorChargePoleWin_datagrid"></table>

<div id="PolemonitorIndexScanByFrontMachine_monitorChargePoleWin_charts"
     style="width:1150px;height:300px;margin:5px auto;"
></div>

<script>
    // 初始数据
    var monitorChargePoleWin_initDatas = <?php echo json_encode($initDatas); ?>;

    // 拼接好列表查询数据时所需的查询参数：当前前置机id 和 当前充电设备id
    var monitorChargePoleWin_queryStr = '&fmId=' + monitorChargePoleWin_initDatas.fmId +
                                        '&devId=' + monitorChargePoleWin_initDatas.devId;

    var PolemonitorIndexScanByFrontMachine_monitorChargePoleWin = {
		init: function(){
			//获取列表数据
			$('#PolemonitorIndexScanByFrontMachine_monitorChargePoleWin_datagrid').datagrid({
				method: 'get', 
				url:"<?php echo yii::$app->urlManager->createUrl(['polemonitor/pole/monitor-charge-pole-get-list']); ?>" + monitorChargePoleWin_queryStr,
				fit: false,height:'220',width:'100%',
				border: false,
				toolbar: "#PolemonitorIndexScanByFrontMachine_monitorChargePoleWin_datagridToolbar",
				pagination: true,
				loadMsg: '数据加载中...',
				striped: true,
				checkOnSelect: true,
				rownumbers: true,
				singleSelect: true,
				frozenColumns: [[
					{field: 'ck',checkbox: true}, 
					{field: 'DEV_ID',title: '设备ID',align: 'center',hidden: false},
					{field: 'DEV_NAME',title: '设备名称',width: 90,align: 'center',sortable: true},
                    {field: 'CHARGE_TYPE',title: '电桩类别',width: 80,align: 'center',sortable: true},
                    {field: 'INNER_ID',title: '电枪(测量点号)',width: 90,align: 'center',sortable: true}
                ]],
				columns:[[
                    {field: 'COSUM_AMOUNT',title: '消费金额',width: 80,halign:'center',align: 'right',sortable: true},
                    {field: 'CHARGE_AMOUNT',title: '充电电量',width: 80,halign:'center',align: 'right',sortable: true},
                    {field: 'SOC',title: '电池SOC',width: 80,halign:'center',align: 'right',sortable: true},
                    {field: 'CHG_RATE',title: '转化效率',width: 80,halign:'center',align: 'right',sortable: true},
                    {field: 'RATED_VOLTAGE',title: '额定电压',width: 80,halign:'center',align: 'right',sortable: true},
					{field: 'RATED_CURRENT',title: '额定电流',width: 80,halign:'center',align: 'right',sortable: true},
					{field: 'MAX_POWER',title: '最大功率',width: 80,halign:'center',align: 'right',sortable: true},
					{field: 'STATUS',title: '当前状态',width: 80,align: 'center',sortable: true},
					{field: 'STU_TIME_TAG',title: '状态改变时间',width: 140,align: 'center',sortable: true},
					{field: 'DC_U',title: '直流电压',width: 80,halign:'center',align: 'right',sortable: true},
					{field: 'DC_I',title: '直流电流',width: 80,halign:'center',align: 'right',sortable: true},
					{field: 'Ua',title: '交流侧UA',width: 80,halign:'center',align: 'right',sortable: true},
					{field: 'Ub',title: '交流侧UB',width: 80,halign:'center',align: 'right',sortable: true},
					{field: 'Uc',title: '交流侧UC',width: 80,halign:'center',align: 'right',sortable: true},
					{field: 'Ia',title: '交流侧IA',width: 80,halign:'center',align: 'right',sortable: true},
					{field: 'Ib',title: '交流侧IB',width: 80,halign:'center',align: 'right',sortable: true},
					{field: 'Ic',title: '交流侧IC',width: 80,halign:'center',align: 'right',sortable: true}
				]],
                onLoadSuccess: function(data){ // 加载成功就绘图表，否则报错。
                    if(data.errInfo){
                        $.messager.show({
                            title:'获取电桩监控数据失败',
                            msg: '<span style="color:red;">' + data.errInfo + '</span>'
                        });
                    }else{
                        PolemonitorIndexScanByFrontMachine_monitorChargePoleWin.drawCharts();
                    }
                }
			});
		},
        // 绘制图表
        drawCharts: function(){
            drawCharts_1();
            //===第一种图表形式========================================
            function drawCharts_1(){
                var monitorChargePoleWin_charts = echarts.init(document.getElementById('PolemonitorIndexScanByFrontMachine_monitorChargePoleWin_charts'));
                var option = {
                    title: {
                        text: '电桩电枪使用情况分析',
                        subtext : '仅按表格当前页数据绘制'
                    },
                    tooltip : {
                        trigger: 'axis',
                        axisPointer : {            // 坐标轴指示器，坐标轴触发有效
                            type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                        }
                    },
                    grid: {
                        left: '3%',
                        right: '4%',
                        bottom: '3%',
                        containLabel: true
                    },
                    toolbox: {
                        show: true,
                        orient: 'horizontal',      // 布局方式，默认为水平布局，可选为：'horizontal' ¦ 'vertical'
                        showTitle: true,
                        feature: {
                            myTool: { //自定义图标
                                show: true,
                                title: '切换图表', color: 'black',
                                icon: 'image://./images/redraw.png',
                                onclick: function () {
                                    drawCharts_2(); // 切换绘制图表方式
                                }
                            },
                            magicType : {show: true, type: ['line', 'bar']},
                            restore : {show: true}
                            //saveAsImage : {show: true}
                        }
                    },
                    legend: {
                        data:['电枪1','电枪2']
                    },
                    xAxis : [
                        {
                            type : 'category',
                            data : ['消费金额','充电电量','电池SOC']
                        }
                    ],
                    yAxis : [
                        {
                            type : 'value'
                        }
                    ],
                    series : [
                        {
                            name:'电枪1',
                            type:'bar',
                            itemStyle : { normal: {label : {show: true, position: 'top'}}},
                            data:[160, 232,342]
                        },
                        {
                            name:'电枪2',
                            type:'bar',
                            itemStyle : { normal: {label : {show: true, position: 'top'}}},
                            data:[220, 420,454]
                        }
                    ]
                };
                // 以表格数据重绘图表
                var rows = $('#PolemonitorIndexScanByFrontMachine_monitorChargePoleWin_datagrid').datagrid('getRows');
                var data_legend = [];
                var data_series = [];
                for (var i=0;i<rows.length;i++) {
                    var INNER_ID = rows[i].INNER_ID;
                    var name = '电枪' + INNER_ID + '';
                    data_legend.push(name);
                    data_series.push({
                        name: name,
                        type: 'bar',
                        itemStyle : { normal: {label : {show: true, position: 'top'}}},
                        data:[
                            rows[i].COSUM_AMOUNT != null ? rows[i].COSUM_AMOUNT : '0.0000',
                            rows[i].CHARGE_AMOUNT != null ? rows[i].CHARGE_AMOUNT : '0.0000',
                            rows[i].SOC != null ? rows[i].SOC : '0.0000'
                        ]
                    });
                }
                option.legend.data = data_legend;
                option.series = data_series;
                monitorChargePoleWin_charts.setOption(option);
            }
            //===第二种图表形式========================================
            function drawCharts_2() {
                var monitorChargePoleWin_charts = echarts.init(document.getElementById('PolemonitorIndexScanByFrontMachine_monitorChargePoleWin_charts'));
                var option = {
                    title: {
                        text: '电桩电枪使用情况分析',
                        subtext : '仅按表格当前页数据绘制'
                    },
                    tooltip : {
                        trigger: 'axis',
                        axisPointer : {            // 坐标轴指示器，坐标轴触发有效
                            type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                        }
                    },
                    grid: {
                        left: '3%',
                        right: '4%',
                        bottom: '3%',
                        containLabel: true
                    },
                    toolbox: {
                        show: true,
                        orient: 'horizontal',      // 布局方式，默认为水平布局，可选为：'horizontal' ¦ 'vertical'
                        showTitle: true,
                        feature: {
                            myTool: { //自定义图标
                                show: true,
                                title: '切换图表', color: 'black',
                                icon: 'image://./images/redraw.png',
                                onclick: function () {
                                    drawCharts_1(); // 切换绘制图表方式
                                }
                            },
                            magicType : {show: true, type: ['line', 'bar']},
                            restore : {show: true}
                            //saveAsImage : {show: true}
                        }
                    },
                    legend: {
                        data: ['消费金额', '充电电量', '电池SOC']
                    },
                    xAxis: [
                        {
                            type: 'category',
                            data: ['电枪1', '电枪2']
                        }
                    ],
                    yAxis: [
                        {
                            type: 'value'
                        }
                    ],
                    series: [
                        {
                            name: '消费金额',
                            type: 'bar',
                            itemStyle: {normal: {label: {show: true, position: 'top'}}},
                            data: [160, 232]
                        },
                        {
                            name: '充电电量',
                            type: 'bar',
                            itemStyle: {normal: {label: {show: true, position: 'top'}}},
                            data: [220, 420]
                        },
                        {
                            name: '电池SOC',
                            type: 'bar',
                            itemStyle: {normal: {label: {show: true, position: 'top'}}},
                            data: [250, 332]
                        }
                    ]
                };
                // 以表格数据重绘图表
                var rows = $('#PolemonitorIndexScanByFrontMachine_monitorChargePoleWin_datagrid').datagrid('getRows');
                var data_xAxis = [];
                var data_1 = [];
                var data_2 = [];
                var data_3 = [];
                for (var i = 0; i < rows.length; i++) {
                    var INNER_ID = rows[i].INNER_ID;
                    data_xAxis.push('电枪' + INNER_ID);
                    data_1.push(rows[i].COSUM_AMOUNT != null ? rows[i].COSUM_AMOUNT : '0.0000');
                    data_2.push(rows[i].CHARGE_AMOUNT != null ? rows[i].CHARGE_AMOUNT : '0.0000');
                    data_3.push(rows[i].SOC != null ? rows[i].SOC : '0.0000');
                }
                option.xAxis[0].data = data_xAxis;
                option.series[0].data = data_1;
                option.series[1].data = data_2;
                option.series[2].data = data_3;
                monitorChargePoleWin_charts.setOption(option);
            }
        }
	}

	// 执行初始化函数
	PolemonitorIndexScanByFrontMachine_monitorChargePoleWin.init();

</script>