<table id="chargeRechargeStatisticsIndex_datagrid"></table>
<div id="chargeRechargeStatisticsIndex_datagridToolbar">
    <div class="easyui-panel" title="充电费充值统计概况" style="padding:0px;width:100%;"
         data-options="iconCls: 'icon-chart-curve', border: false">
    </div>
</div>

<!-- 趋势图 -->
<div id="chargeRechargeStatisticsIndex_chart_line"
     style="width:1050px;height:230px;margin:15px auto;border:0px solid gray;">
</div>

<!-- 饼图 -->
<div style="height:230px;margin:15px auto;border:0px solid gray;">
    <div id="chargeRechargeStatisticsIndex_chart_pie1" style="float:left;width:520px;height:220px;border:0px solid gray;"></div>
    <div id="chargeRechargeStatisticsIndex_chart_pie2" style="float:left;width:520px;height:220px;border:0px solid gray;"></div>
    <div id="chargeRechargeStatisticsIndex_chart_pie3" style="float:left;width:520px;height:220px;border:0px solid gray;"></div>
    <div id="chargeRechargeStatisticsIndex_chart_pie4" style="float:left;width:520px;height:220px;border:0px solid gray;"></div>
</div>

<div style="height:10px;clear:both;"></div>

<!--按电卡类型统计充值列表-->
<table id="chargeRechargeStatisticsIndex_datagridByCardType"></table>
<div id="chargeRechargeStatisticsIndex_datagridByCardTypeToolbar">
    <div class="easyui-panel" title="检索区域" style="padding:0px;width:100%;"
         data-options="iconCls: 'icon-search', border: false">
        <div class="data-search-form">
            <form id="chargeRechargeStatisticsIndex_searchForm">
                <ul class="search-main">
                    <li>
                        <div class="item-name">选择时间段</div>
                        <div class="item-input">
                            <select
                                class="easyui-combobox" name="choose_period" style="width:100%;"
                                data-options="
                                    panelHeight:'auto',
                                    editable:false,
                                    onChange:function(){
                                        chargeRechargeStatisticsIndex.search();
                                    }
                                "
                                >
                                <option value="" selected="selected">--请选择--</option>
                                <option value="today">今天</option>
                                <option value="yesterday">昨天</option>
                                <option value="thisWeek">本周</option>
                                <option value="lastWeek">上一周</option>
                                <option value="thisMonth">本月</option>
                                <option value="lastMonth">上一月</option>
                                <option value="thisYear">本年度</option>
                                <option value="lastYear">上一年度</option>
                            </select>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">查询时间</div>
                        <div class="item-input">
                            <input
                                class="easyui-datebox"
                                type="text"
                                name="searchTime_start"
                                style="width:93px;"
                                data-options="
                                    onChange:function(){
                                        chargeRechargeStatisticsIndex.search();
                                    }
                                "
                                />
                            -
                            <input
                                class="easyui-datebox"
                                type="text"
                                name="searchTime_end"
                                style="width:93px;"
                                data-options="
                                    onChange:function(){
                                        chargeRechargeStatisticsIndex.search();
                                    }
                                "
                                />
                        </div>
                    </li>
                    <li class="search-button">
                        <a href="javascript:void(0)" onclick="chargeRechargeStatisticsIndex.search();" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                        <a href="javascript:void(0)" onclick="chargeRechargeStatisticsIndex.reset();" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</a>
                    </li>
                </ul>
            </form>
        </div>
    </div>
    <div class="easyui-panel" title="按电卡类型统计充值" style="padding:2px 3px;width:100%;"
         data-options="iconCls: 'icon-table-list', border: false">
        <?php
            if($buttons){
                foreach($buttons as $val){
        ?>
                    <a href="javascript:void(0)" onclick="<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon'] ;?>'"><?= $val['text'] ;?></a>
        <?php
               }
            }
        ?>
    </div>
</div>

<script>
    var chargeRechargeStatisticsIndex = {
        param: {
            URL:{
                "getList": "<?php echo yii::$app->urlManager->createUrl(['charge/recharge-statistics/get-list']); ?>",
                "getListByCardType": "<?php echo yii::$app->urlManager->createUrl(['charge/recharge-statistics/get-list-by-card-type']); ?>",
                "exportGridData": "<?php echo yii::$app->urlManager->createUrl(['charge/recharge-statistics/export-grid-data']); ?>"
            }
        },
        // 初始化表格
        init: function(){
            $('#chargeRechargeStatisticsIndex_datagrid').datagrid({
                method: 'get',
                url: chargeRechargeStatisticsIndex.param.URL.getList,
                toolbar: "#chargeRechargeStatisticsIndex_datagridToolbar",
                fit:false,
                border: false,
                pagination: false,
                loadMsg: '数据加载中...',
                striped: true,
                checkOnSelect: true,
                rownumbers: true,
                singleSelect: false,
                pageSize: 20,
                columns: [
                    [
                        {title:'<span style="font-size:16px;font-weight:bold;">充电费充值统计概况</span>',colspan:7} //跨7列
                    ],
                    [
                        {field: 'colSubTitle', title: '', width: 130, align: 'center',rowspan:2}, //跨2行
                        {field: 'recharge_money', title: '充值金额（元）', align: 'center',colspan:3},
                        {field: 'recharge_times', title: '充值人次（次）', align: 'center',colspan:3}
                    ],
                    [
                        {field: 'recharge_money_app', title: 'APP', width: 150, halign: 'center',align:'right'},
                        {field: 'recharge_money_card', title: '电卡', width: 150, halign: 'center',align:'right'},
                        {field: 'recharge_money_total', title: '合计', width: 150, halign: 'center',align:'right'},
                        {field: 'recharge_times_app', title: 'APP', width: 150, halign: 'center',align:'right'},
                        {field: 'recharge_times_card', title: '电卡', width: 150, halign: 'center',align:'right'},
                        {field: 'recharge_times_total', title: '合计', width: 150, halign: 'center',align:'right'}
                    ]
                ]
            });
            $('#chargeRechargeStatisticsIndex_datagridByCardType').datagrid({
                method: 'get',
                url: chargeRechargeStatisticsIndex.param.URL.getListByCardType,
                toolbar: "#chargeRechargeStatisticsIndex_datagridByCardTypeToolbar",
                fit:false,
                border: false,
                pagination: false,
                loadMsg: '数据加载中...',
                striped: true,
                checkOnSelect: true,
                rownumbers: true,
                singleSelect: false,
                pageSize: 20,
                columns: [[
                    {field: 'cardType', title: '电卡类型', width: 150, align: 'center'},
                    {field: 'rechargeMoney', title: '充值金额（元）', width: 180, halign: 'center', align: 'right'},
                    {field: 'rechargeTimes', title: '充值人次（次）', width: 180, halign: 'center', align: 'right'}
                ]]
            });
        },
        // 绘制趋势图
        drawChartLine: function(){
            var chart = echarts.init(document.getElementById('chargeRechargeStatisticsIndex_chart_line'));
            var option = {
                title : {
                    text : '最近12个月充值统计趋势图',
                    subtext : ''
                },
                toolbox: {
                    show : true,
                    feature : {
                        magicType : {show: true, type: ['line', 'bar']},
                        restore : {show: true}
                    }
                },
                tooltip : {
                    trigger: 'axis'
                },
                grid: {
                    'y':40,'y2':30 //左上角和右下角垂直距离
                },
                legend: {
                    data:['充值金额','充值人次']
                },
                xAxis : [
                    {
                        type : 'category',
                        name: '年月',
                        boundaryGap : false,
                        data : ['1月','2月','3月','4月','5月','6月','7月','8月','9月','10月','11月','12月']
                    }
                ],
                yAxis : [
                    {
                        type : 'value'
                    }
                ],
                series : [
                    {
                        name:'充值金额',
                        type:'line',
                        itemStyle : { normal: {label : {show: true, position: 'top',textStyle:{color:'#FF7F50'}}}},
                        data:[320, 332, 301, 334, 290, 330, 320, 301, 334, 290, 330, 320]
                    },
                    {
                        name:'充值人次',
                        type:'line',
                        itemStyle : { normal: {label : {show: true, position: 'top',textStyle:{color:'#87CEFA'}}}},
                        data:[120, 132, 101, 134, 110, 230, 210, 101, 134, 110, 230, 210]
                    }
                ]
            };
            // 以最近12个月的数据重绘图表
            var rows = <? echo json_encode($twelveMonthsData); ?>;
            var data_xAxis = [];
            var data_1 = []; var data_2 = [];
            for (var i=0; i<rows.length; i++) {
                data_xAxis.push(rows[i].year_month);
                data_1.push(rows[i].rechargeMoney);
                data_2.push(rows[i].rechargeTimes);
            }
            option.xAxis[0].data = data_xAxis;
            option.series[0].data = data_1;
            option.series[1].data = data_2;
            chart.setOption(option);
        },
        // 绘制饼图
        drawChartPie: function(){
            var overviewData = <? echo json_encode($overviewData); ?>;
            for(var key in overviewData){
                switch(key){
                    case 'saleAmount':
                        var titleTxt  = '发卡数量';
                        var dataItem  = overviewData.saleAmount;
                        var elementId = 'chargeRechargeStatisticsIndex_chart_pie1';
                        break;
                    case 'rechargeMoney':
                        var titleTxt  = '充值金额';
                        var dataItem  = overviewData.rechargeMoney;
                        var elementId = 'chargeRechargeStatisticsIndex_chart_pie2';
                        break;
                    case 'currentMoney':
                        var titleTxt  = '当前结余';
                        var dataItem  = overviewData.currentMoney;
                        var elementId = 'chargeRechargeStatisticsIndex_chart_pie3';
                        break;
                    case 'rechargeTimes':
                        var titleTxt  = '充值人次';
                        var dataItem  = overviewData.rechargeTimes;
                        var elementId = 'chargeRechargeStatisticsIndex_chart_pie4';
                        break;
                }
                // 要加载的数据
                var loadData = [];
                for(var i in dataItem){
                    var item = { value: dataItem[i] };
                    switch(i){
                        case 'APP_USER':
                            item.name = 'APP用户'; break;
                        case 'COMMON':
                            item.name = '普通用户卡'; break;
                        case 'STATION_MANAGER':
                            item.name = '站点管理员卡'; break;
                        case 'PROTOCOL':
                            item.name = '协议充值卡'; break;
                        case 'CUSTOMER_SELF':
                            item.name = '客户自用卡'; break;
                    }
                    loadData.push(item);
                }
                var chart = echarts.init(document.getElementById(elementId));
                option = {
                    title : {
                        text: titleTxt,
                        x:'center'
                    },
                    tooltip : {
                        trigger: 'item',
                        formatter: "{a} <br/>{b} : {c} ({d}%)"
                    },
                    toolbox: {
                        show : false,
                        feature : {
                            magicType : {
                                show: true,
                                type: ['pie', 'funnel'],
                                option: {
                                    funnel: {
                                        x: '25%',
                                        width: '50%',
                                        funnelAlign: 'left',
                                        max: 1548
                                    }
                                }
                            },
                            restore : {show: true}
                        }
                    },
                    calculable : false,
                    legend: {
                        //orient : 'vertical', x : 'right',
                        y: 'bottom',
                        data:['APP用户','普通用户卡','站点管理员卡','协议充值卡','客户自用卡']
                    },
                    series : [
                        {
                            name: titleTxt,
                            type: 'pie',
                            radius : '45%',
                            center: ['50%', '53%'],
                            itemStyle : {
                                normal : {
                                    label : {
                                        position : 'outer',
                                        formatter : function (params) {
                                            if(parseFloat(params.value)){
                                                return params.value + ' | ' + params.percent + '%'
                                            }else{
                                                return '0 | 0%';
                                            }
                                        }
                                    },
                                    labelLine : {
                                        show : true
                                    }
                                },
                                emphasis : {
                                    label : {
                                        show : true,
                                        formatter : "{b}\n{d}%"
                                    }
                                }
                            },
                            data: loadData
                        }
                    ]
                };
                chart.setOption(option);
            }
        },
        // 查询
        search: function(){
            var form = $('#chargeRechargeStatisticsIndex_searchForm');
            var data = {};
            var searchCondition = form.serializeArray();
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = $.trim(searchCondition[i]['value']);
            }
            $('#chargeRechargeStatisticsIndex_datagridByCardType').datagrid('load',data);
        },
        // 重置
        reset: function(){
            $('#chargeRechargeStatisticsIndex_searchForm').form('reset');
            chargeRechargeStatisticsIndex.search();
        },
        // 导出Excel
        exportGridData: function(){
            var gridData = $('#chargeRechargeStatisticsIndex_datagridByCardType').datagrid('getData'); //这里仅一页就直接转对象为josn字符串传递
            window.open(chargeRechargeStatisticsIndex.param.URL.exportGridData + "&dataStr=" + JSON.stringify(gridData.rows));
        }
    }

    // 执行初始化
    chargeRechargeStatisticsIndex.init();
    // 绘制趋势图
    chargeRechargeStatisticsIndex.drawChartLine();
    // 绘制饼图
    chargeRechargeStatisticsIndex.drawChartPie();

</script>