<table id="PolemonitorIndexScanByFrontMachine_monitorTotalPowerWin_datagrid"></table>

<div id="PolemonitorIndexScanByFrontMachine_monitorTotalPowerWin_charts"
     style="width:1150px;height:300px;margin:5px auto;"
></div>

<script>
    // 初始数据
    var monitorTotalPowerWin_initDatas = <?php echo json_encode($initDatas); ?>;

    // 拼接好列表查询数据时所需的查询参数：当前前置机id 和 当前充电设备id
    var monitorTotalPowerWin_queryStr = '&fmId=' + monitorTotalPowerWin_initDatas.fmId +
                                        '&devId=' + monitorTotalPowerWin_initDatas.devId;

    var PolemonitorIndexScanByFrontMachine_monitorTotalPowerWin = {
		init: function(){
			//获取列表数据
			$('#PolemonitorIndexScanByFrontMachine_monitorTotalPowerWin_datagrid').datagrid({
				method: 'get', 
				url:"<?php echo yii::$app->urlManager->createUrl(['polemonitor/power/monitor-total-power-get-list']); ?>" + monitorTotalPowerWin_queryStr,
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
					{field: 'ZXYGZ',title: '正向有功总电能',width: 120,halign:'center',align: 'right',sortable: true},
					{field: 'ZXYG1',title: '费率1正向有功总电能',width: 150,halign:'center',align: 'right',sortable: true},
					{field: 'ZXYG2',title: '费率2正向有功总电能',width: 150,halign:'center',align: 'right',sortable: true},
					{field: 'ZXYG3',title: '费率3正向有功总电能',width: 150,halign:'center',align: 'right',sortable: true},
                    {field: 'ZXYG4',title: '费率4正向有功总电能',width: 150,align: 'center',sortable: true},
					{field: 'WRITE_TIME',title: '写库时间',width: 150,align: 'center',sortable: true}
				]],
                onLoadSuccess: function(data){ // 加载成功就绘图表，否则报错。
                    if(data.errInfo){
                        $.messager.show({
                            title:'获取电能监控数据失败',
                            msg: '<span style="color:red;">' + data.errInfo + '</span>'
                        });
                    }else{
                        PolemonitorIndexScanByFrontMachine_monitorTotalPowerWin.drawCharts();
                    }
                }
			});
		},
        // 绘制图表
        drawCharts: function(){
            var monitorTotalPowerWin_charts = echarts.init(document.getElementById('PolemonitorIndexScanByFrontMachine_monitorTotalPowerWin_charts'));
            var option = {
                title: {
                    text: '正向有功总电能',
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
                    trigger: 'axis',
                    axisPointer : {            // 坐标轴指示器，坐标轴触发有效
                        type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                    }
                },
                legend: {
                    data:['费率1','费率2','费率3','费率4']
                },
                grid: {
                    'x':70,'y':70,'x2':40,'y2':40,
                    containLabel: true
                },
                xAxis : [
                    {
                        type : 'category',
                        name: '时间',
                        data : ['周一','周二','周三','周四','周五','周六','周日']
                    }
                ],
                yAxis : [
                    {
                        type : 'value',
                        name: '正向有功总电能'
                    }
                ],
                series : [
                    {
                        name:'费率1',
                        type:'bar',
                        stack: '总量',
                        itemStyle : { normal: {label : {show: true, position: 'inside'}}},
                        data:[320, 332, 301, 334, 390, 330, 320]
                    },
                    {
                        name:'费率2',
                        type:'bar',
                        stack: '总量',
                        itemStyle : { normal: {label : {show: true, position: 'inside'}}},
                        data:[120, 132, 101, 134, 90, 230, 210]
                    },
                    {
                        name:'费率3',
                        type:'bar',
                        stack: '总量',
                        itemStyle : { normal: {label : {show: true, position: 'inside'}}},
                        data:[220, 182, 191, 234, 290, 330, 310]
                    },
                    {
                        name:'费率4',
                        type:'bar',
                        stack: '总量',
                        itemStyle : { normal: {label : {show: true, position: 'inside'}}},
                        data:[150, 232, 201, 154, 190, 330, 410]
                    }
                ]
            };
            // 以表格数据重绘图表
            var rows = $('#PolemonitorIndexScanByFrontMachine_monitorTotalPowerWin_datagrid').datagrid('getRows');
            var data_xAxis = [];
            var data_rate1 = []; var data_rate2 = []; var data_rate3 = []; var data_rate4 = [];
            for (var i=rows.length-1;i>=0;i--) {
                var TIME_TAG = rows[i].TIME_TAG;
                data_xAxis.push(TIME_TAG.slice(0,10)+'\n\r'+TIME_TAG.slice(10));
                data_rate1.push(rows[i].ZXYG1 != null ? rows[i].ZXYG1 : '0.0000');
                data_rate2.push(rows[i].ZXYG2 != null ? rows[i].ZXYG2 : '0.0000');
                data_rate3.push(rows[i].ZXYG3 != null ? rows[i].ZXYG3 : '0.0000');
                data_rate4.push(rows[i].ZXYG4 != null ? rows[i].ZXYG4 : '0.0000');
            }
            option.xAxis[0].data = data_xAxis;
            option.series[0].data = data_rate1;
            option.series[1].data = data_rate2;
            option.series[2].data = data_rate3;
            option.series[3].data = data_rate4;
            monitorTotalPowerWin_charts.setOption(option);
        }
    }
	// 执行初始化函数
	PolemonitorIndexScanByFrontMachine_monitorTotalPowerWin.init();
</script>