<table id="ChargeSpotsIndex_monitorChargeWin_datagrid"></table>
<div id="ChargeSpotsIndex_monitorChargeWin_datagridToolbar">
    <div class="easyui-panel" title="" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">
        <div class="data-search-form">
            <form id="ChargeSpotsIndex_monitorChargeWin_searchForm">
                <ul class="search-main">
                    <li>
                        <div class="item-name">数据时间</div>
                        <div class="item-input" style="width:320px;">
                            <input class="easyui-datetimebox" type="text" name="TIME_TAG_start" style="width:145px;"
                                   data-options="
                                    onChange:function(){
                                        ChargeSpotsIndex_monitorChargeWin.search();
                                    }
                               "
                                />
                            -
                            <input class="easyui-datetimebox" type="text" name="TIME_TAG_end" style="width:145px;"
                                   data-options="
                                    onChange:function(){
                                        ChargeSpotsIndex_monitorChargeWin.search();
                                    }
                               "
                                />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">测量点</div>
                        <div class="item-input">
                            <select class="easyui-combobox" type="text" name="INNER_ID" style="width:100%;"
                                    data-options="
                                    panelHeight:'auto',
                                    editable:false,
                                    onChange:function(){
                                        ChargeSpotsIndex_monitorChargeWin.search();
                                    }
                                ">
                                <option value="">--不限--</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                            </select>
                        </div>
                    </li>
                    <li class="search-button">
                        <a href="javascript:void(0)" onclick="ChargeSpotsIndex_monitorChargeWin.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                        <a href="javascript:void(0)" onclick="ChargeSpotsIndex_monitorChargeWin.reset()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</a>
                    </li>
                </ul>
            </form>
        </div>
    </div>
</div>

<div id="ChargeSpotsIndex_monitorChargeWin_charts"
     style="width:1150px;height:270px;margin:5px auto;"
></div>

<script>
    var ChargeSpotsIndex_monitorChargeWin = {
        'URL':{
            monitorChargeGetList: "<?php echo yii::$app->urlManager->createUrl(['charge/charge-spots/monitor-charge-get-list','id'=>$id]); ?>"
        },
		init: function(){
			//获取列表数据
			$('#ChargeSpotsIndex_monitorChargeWin_datagrid').datagrid({
                toolbar:'#ChargeSpotsIndex_monitorChargeWin_datagridToolbar',
                method: 'get',
				url: this.URL.monitorChargeGetList,
				fit: false,height:'260',width:'100%',
				border: false,
				pagination: true,
				loadMsg: '数据加载中...',
				striped: true,
				checkOnSelect: true,
				rownumbers: true,
				singleSelect: true,
                pageSize:20,
				frozenColumns: [[
					{field: 'ck',checkbox: true}, 
					{field: 'DEV_ID',title: '设备ID',align: 'center',hidden: false},
					{field: 'TIME_TAG',title: '数据时间',width: 150,align: 'center',sortable: true},
				]],
				columns:[[
                    {field: 'INNER_ID',title: '测量点',width: 80,align: 'center',sortable: true},
                    {field: 'gunName',title: '电枪',width: 80,align: 'center',sortable: true},
					{field: 'CHARGE_AMOUNT',title: '充电电量',width: 100,halign:'center',align: 'right',sortable: true},
					{field: 'COSUM_AMOUNT',title: '消费金额',width: 100,halign:'center',align: 'right',sortable: true},
					{field: 'SOC',title: '电池SOC',width: 100,halign:'center',align: 'right',sortable: true},
					{field: 'CAR_NO',title: '车号',width: 100,halign:'center',align: 'right',sortable: true},
					{field: 'WRITE_TIME',title: '写库时间',width: 150,align: 'center',sortable: true}
				]],
                onLoadSuccess: function(data){ // 加载成功就绘图表，否则报错。
                    if(data.errInfo){
                        $.messager.show({
                            title:'获取充电计量计费监控数据失败',
                            msg: '<span style="color:red;">' + data.errInfo + '</span>'
                        });
                    }else{
                        ChargeSpotsIndex_monitorChargeWin.drawCharts();
                    }
                }
			});
		},
        // 绘制图表
        drawCharts: function(){
            var monitorChargeWin_charts = echarts.init(document.getElementById('ChargeSpotsIndex_monitorChargeWin_charts'));
            var option = {
                title : {
                    text : '充电计量计费分析',
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
                    data:['充电电量','消费金额','电池SOC']
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
                        name:'充电电量',
                        type:'line',
                        itemStyle : { normal: {label : {show: true, position: 'top',textStyle:{color:'#FF7F50'}}}},
                        data:[320, 332, 301, 334, 390, 330, 320]
                    },
                    {
                        name:'消费金额',
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
            var rows = $('#ChargeSpotsIndex_monitorChargeWin_datagrid').datagrid('getRows');
            var data_xAxis = [];
            var data_1 = []; var data_2 = []; var data_3 = [];
            for (var i=rows.length-1;i>=0;i--) {
                var TIME_TAG = rows[i].TIME_TAG;
                data_xAxis.push(TIME_TAG.slice(0,10)+'\n\r'+TIME_TAG.slice(10));
                data_1.push(rows[i].CHARGE_AMOUNT != null ? rows[i].CHARGE_AMOUNT : '0.0000');
                data_2.push(rows[i].COSUM_AMOUNT != null ? rows[i].COSUM_AMOUNT : '0.0000');
                data_3.push(rows[i].SOC != null ? rows[i].SOC : '0.0000');
            }
            option.xAxis[0].data = data_xAxis;
            option.series[0].data = data_1;
            option.series[1].data = data_2;
            option.series[2].data = data_3;
            monitorChargeWin_charts.setOption(option);
        },
        // 查询
        search: function () {
            var form = $('#ChargeSpotsIndex_monitorChargeWin_searchForm');
            var data = {};
            var searchCondition = form.serializeArray();
            for (var i in searchCondition) {
                data[searchCondition[i]['name']] = $.trim(searchCondition[i]['value']);
            }
            $('#ChargeSpotsIndex_monitorChargeWin_datagrid').datagrid('load', data);
        },
        // 重置
        reset: function () {
            $('#ChargeSpotsIndex_monitorChargeWin_searchForm').form('reset');
            ChargeSpotsIndex_monitorChargeWin.search();
        }
    }

	// 执行初始化函数
	ChargeSpotsIndex_monitorChargeWin.init();

</script>