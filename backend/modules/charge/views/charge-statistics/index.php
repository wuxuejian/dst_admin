<table id="chargeChargeStatisticsIndex_datagrid"></table>
<div id="chargeChargeStatisticsIndex_datagridToolbar">
    <div class="easyui-panel" title="充电站充电统计概况" style="padding:0px;width:100%;"
         data-options="iconCls: 'icon-chart-bar', border: false">
    </div>
</div>

<!-- 趋势图 -->
<div id="chargeChargeStatisticsIndex_chart_line" style="width:1050px;height:230px;margin:15px auto;border:0px solid gray;"></div>

<!-- 充电站类型统计概况和充电桩数量占比 -->
<div class="easyui-panel" title="充电站基本概况|充电桩数量占比" style="height:180px;" data-options="iconCls: 'icon-chart-pie', border: false">
    <div style="float:left;width:55%;border:0px solid red;" >
        <table id="chargeChargeStatisticsIndex_datagridByStationType"></table>
    </div>
    <div id="chargeChargeStatisticsIndex_chart_pie" style="float:left;width:44%;height:150px;border:0px solid gray;"></div>
</div>

<div style="height:3px;clear:both;"></div>

<!--按充电桩统计充电列表-->
<table id="chargeChargeStatisticsIndex_datagridByPole"></table>
<div id="chargeChargeStatisticsIndex_datagridByPoleToolbar">
    <div class="easyui-panel" title="检索区域" style="padding:0px;width:100%;"
         data-options="iconCls: 'icon-search', border: false">
        <div class="data-search-form">
            <form id="chargeChargeStatisticsIndex_searchForm">
                <ul class="search-main">
                    <li>
                        <div class="item-name">充电站类型</div>
                        <div class="item-input">
                            <select
                                class="easyui-combobox" name="cs_type" style="width:100%;"
                                data-options="
                                    panelHeight:'auto',
                                    editable:false,
                                    onChange:function(){
                                        chargeChargeStatisticsIndex.search();
                                    }
                                "
                                >
                                <option value="" selected="selected">--不限--</option>
                                <?php foreach($config['cs_type'] as $val){ ?>
                                    <option value="<?php echo $val['value']; ?>"><?php echo $val['text']; ?></option>
                                <?php }?>
                            </select>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">充电站</div>
                        <div class="item-input">
                            <input id="chargeChargeStatisticsIndex_chooseStation" name="station_id" style="width:100%;"  />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">选择时间段</div>
                        <div class="item-input">
                            <select
                                class="easyui-combobox" name="choose_period" style="width:100%;"
                                data-options="
                                    panelHeight:'auto',
                                    editable:false,
                                    onChange:function(){
                                        chargeChargeStatisticsIndex.search();
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
                        <div class="item-name">充电桩类型</div>
                        <div class="item-input">
                            <select
                                class="easyui-combobox" name="charge_type" style="width:100%;"
                                data-options="
                                    panelHeight:'auto',
                                    editable:false,
                                    onChange:function(){
                                        chargeChargeStatisticsIndex.search();
                                    }
                                "
                                >
                                <option value="" selected="selected">--不限--</option>
                                <?php foreach($config['charge_type'] as $val){ ?>
                                    <option value="<?php echo $val['value']; ?>"><?php echo $val['text']; ?></option>
                                <?php }?>
                            </select>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">充电桩</div>
                        <div class="item-input">
                            <input class="easyui-textbox" name="charge_pole" style="width:100%;" prompt="电桩编号/逻辑地址"
                                   data-options="
                                        onChange:function(){
                                            chargeChargeStatisticsIndex.search();
                                        }
                                   "/>
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
                                        chargeChargeStatisticsIndex.search();
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
                                        chargeChargeStatisticsIndex.search();
                                    }
                                "
                                />
                        </div>
                    </li>
                    <li class="search-button">
                        <a href="javascript:void(0)" onclick="chargeChargeStatisticsIndex.search();" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                        <a href="javascript:void(0)" onclick="chargeChargeStatisticsIndex.reset();" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</a>
                    </li>
                </ul>
            </form>
        </div>
    </div>
    <div class="easyui-panel" title="按充电桩统计充电" style="padding:2px 3px;width:100%;"
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
    var chargeChargeStatisticsIndex = {
        param: {
            CONFIG: <?php echo json_encode($config); ?>,
            URL:{
                "getList": "<?php echo yii::$app->urlManager->createUrl(['charge/charge-statistics/get-list']); ?>",
                "getListByStationType": "<?php echo yii::$app->urlManager->createUrl(['charge/charge-statistics/get-list-by-station-type']); ?>",
                "getListByPole": "<?php echo yii::$app->urlManager->createUrl(['charge/charge-statistics/get-list-by-pole']); ?>",
                "exportGridData": "<?php echo yii::$app->urlManager->createUrl(['charge/charge-statistics/export-grid-data']); ?>"
            }
        },
        // 初始化
        init: function(){
            //初始化各表格
            $('#chargeChargeStatisticsIndex_datagrid').datagrid({
                method: 'get',
                url: chargeChargeStatisticsIndex.param.URL.getList,
                toolbar: "#chargeChargeStatisticsIndex_datagridToolbar",
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
                        {title:'<span style="font-size:16px;font-weight:bold;">充电站充电统计概况</span>',colspan:10} //跨7列
                    ],
                    [
                        {field: 'colSubTitle', title: '', width: 130, align: 'center',rowspan:2}, //跨2行
                        {field: 'charge_kwh', title: '充电量（度）', align: 'center',colspan:3},
                        {field: 'charge_money', title: '充电收入（元）', align: 'center',colspan:3},
                        {field: 'charge_times', title: '充电人次（次）', align: 'center',colspan:3}
                    ],
                    [
                        {field: 'charge_kwh_app', title: 'APP', width: 100, halign: 'center',align:'right'},
                        {field: 'charge_kwh_card', title: '电卡', width: 100, halign: 'center',align:'right'},
                        {field: 'charge_kwh_total', title: '合计', width: 100, halign: 'center',align:'right'},
                        {field: 'charge_money_app', title: 'APP', width: 100, halign: 'center',align:'right'},
                        {field: 'charge_money_card', title: '电卡', width: 100, halign: 'center',align:'right'},
                        {field: 'charge_money_total', title: '合计', width: 100, halign: 'center',align:'right'},
                        {field: 'charge_times_app', title: 'APP', width: 100, halign: 'center',align:'right'},
                        {field: 'charge_times_card', title: '电卡', width: 100, halign: 'center',align:'right'},
                        {field: 'charge_times_total', title: '合计', width: 100, halign: 'center',align:'right'}
                    ]
                ]
            });
            $('#chargeChargeStatisticsIndex_datagridByStationType').datagrid({
                method: 'get',
                url: chargeChargeStatisticsIndex.param.URL.getListByStationType,
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
                    {field: 'stationType', title: '充电站类型', width: 113, align: 'center'},
                    {field: 'stationNum', title: '充电站数量（个）', width: 110, halign: 'center',align:'right'},
                    {field: 'poleNum', title: '充电桩数量（个）', width: 110, halign: 'center',align:'right'},
                    {field: 'DC_poleNum', title: '直流桩数量（个）', width: 110, halign: 'center',align:'right'},
                    {field: 'AC_poleNum', title: '交流桩数量（个）', width: 110, halign: 'center',align:'right'}
                ]],
                onLoadSuccess: function(data){ // 加载成功后绘制饼图
                    var loadData = [];
                    var rows = data.rows;
                    for(var i in rows){
                        if(rows[i].stationType != '合计'){
                            loadData.push({name:rows[i].stationType, value:rows[i].poleNum});
                        }
                    }
                    chargeChargeStatisticsIndex.drawChartPie(loadData);
                }
            });
            $('#chargeChargeStatisticsIndex_datagridByPole').datagrid({
                height: '460',
                method: 'get',
                url: chargeChargeStatisticsIndex.param.URL.getListByPole,
                toolbar:'#chargeChargeStatisticsIndex_datagridByPoleToolbar',
                fit: false,
                border: false,
                pagination: true,
                loadMsg: '数据加载中...',
                striped: true,
                checkOnSelect: true,
                rownumbers: true,
                singleSelect: false,
                pageSize: 20,
                showFooter: true,
                frozenColumns: [[
                    {field: 'ck', checkbox: true},
                    {field: 'id', title: '电桩ID', width: 40, align: 'center', hidden: true},
                    {field: 'code_from_compony', title: '电桩编号', width: 70, align: 'center', sortable: true},
                    {field: 'logic_addr', title: '逻辑地址', width: 70, align: 'center', sortable: true}
                ]],
                columns: [
                    [
                        {field: 'charge_type', title: '电桩类型', width: 100, halign: 'center', rowspan:2, sortable: true,
                            formatter: function (value, row, index) {
                                try {
                                    var str = 'chargeChargeStatisticsIndex.param.CONFIG.charge_type.' + value + '.text';
                                    return eval(str);
                                } catch (e) {
                                    return '';
                                }
                            }
                        },
                        {field: 'cs_name', title: '所属充电站', width: 160, halign: 'center', rowspan:2, sortable: true},
                        {field: 'cs_type', title: '充电站类型', width: 80, align:'center', rowspan:2, sortable: true,
                            formatter: function (value, row, index) {
                                try {
                                    var str = 'chargeChargeStatisticsIndex.param.CONFIG.cs_type.' + value + '.text';
                                    return eval(str);
                                } catch (e) {
                                    return '';
                                }
                            }
                        },
                        {field:'', title:'充电量（度）', colspan:3},
                        {field:'', title:'充电收入（元）', colspan:3},
                        {field:'', title:'充电人次（次）', colspan:3}
                    ],
                    [
                        {field: 'charge_kwh_app', title: 'APP', width: 90, halign: 'center',align:'right'},
                        {field: 'charge_kwh_card', title: '电卡', width: 90, halign: 'center',align:'right'},
                        {field: 'charge_kwh_total', title: '合计', width: 90, halign: 'center',align:'right'},
                        {field: 'charge_money_app', title: 'APP', width: 90, halign: 'center',align:'right'},
                        {field: 'charge_money_card', title: '电卡', width: 90, halign: 'center',align:'right'},
                        {field: 'charge_money_total', title: '合计', width: 90, halign: 'center',align:'right'},
                        {field: 'charge_times_app', title: 'APP', width: 90, halign: 'center',align:'right'},
                        {field: 'charge_times_card', title: '电卡', width: 90, halign: 'center',align:'right'},
                        {field: 'charge_times_total', title: '合计', width: 90, halign: 'center',align:'right'}
                    ]
                ]
            });

            // 初始化【选择充电站】combogrid
            $('#chargeChargeStatisticsIndex_chooseStation').combogrid({
                panelWidth: 500,
                panelHeight: 200,
                missingMessage: '请从下拉列表里选择电站！',
                onHidePanel:function(){
                    var _combogrid = $(this);
                    var value = _combogrid.combogrid('getValue');
                    var textbox = _combogrid.combogrid('textbox');
                    var text = textbox.val();
                    var rows = _combogrid.combogrid('grid').datagrid('getSelections');
                    if(text){
                        if(rows.length < 1 && value == text){
                            $.messager.show({
                                title: '无效电站',
                                msg:'【' + text + '】不是有效电站！请重新检索并选择一个电站！'
                            });
                            _combogrid.combogrid('clear');
                        }else{
                            chargeChargeStatisticsIndex.search();
                        }
                    }
                },
                delay: 800,
                mode:'remote',
                idField: 'cs_id',
                textField: 'cs_name',
                url: "<?php echo yii::$app->urlManager->createUrl(['charge/combogrid/get-station']); ?>",
                method: 'get',
                scrollbarSize:0,
                rownumbers: true,
                pagination: true,
                pageSize: 10,
                pageList: [10,20,30],
                fitColumns: true,
                columns: [[
                    {field:'cs_id',title:'电站ID',width:35,align:'center',hidden:true},
                    {field:'cs_code',title:'电站编号',width:80,align:'center'},
                    {field:'cs_name',title:'电站名称',width:130,halign:'center'},
                    {field:'cs_address',title:'电站位置',width:200,halign:'center'}
                ]]
            });
        },
        // 绘制趋势图
        drawChartLine: function(){
            var chart = echarts.init(document.getElementById('chargeChargeStatisticsIndex_chart_line'));
            var option = {
                title : {
                    text : '最近12个月充电统计趋势图',
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
                    data:['充电量','充电收入','充电人次']
                },
                xAxis : [
                    {
                        type : 'category',
                        name: '年月',
                        boundaryGap : true,
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
                        name:'充电量',
                        type:'line',
                        itemStyle : { normal: {label : {show: true, position: 'top',textStyle:{color:'#FF7F50'}}}},
                        data:[220, 162, 201, 234, 190, 210, 190, 231, 234, 190, 230, 220]
                    },
                    {
                        name:'充电收入',
                        type:'line',
                        itemStyle : { normal: {label : {show: true, position: 'top',textStyle:{color:'#87CEFA'}}}},
                        data:[320, 332, 301, 334, 290, 330, 320, 301, 334, 290, 330, 320]
                    },
                    {
                        name:'充电人次',
                        type:'line',
                        itemStyle : { normal: {label : {show: true, position: 'top',textStyle:{color:'#DA70D6'}}}},
                        data:[120, 132, 101, 134, 110, 230, 210, 101, 134, 110, 230, 210]
                    }
                ]
            };
            // 以最近12个月的数据重绘图表
            var rows = <?php echo json_encode($twelveMonthsData); ?>;
            var data_xAxis = [];
            var data_1 = []; var data_2 = []; var data_3 = [];
            for (var i=0; i<rows.length; i++) {
                data_xAxis.push(rows[i].year_month);
                data_1.push(rows[i].chargeKwh);
                data_2.push(rows[i].chargeMoney);
                data_3.push(rows[i].chargeTimes);
            }
            option.xAxis[0].data = data_xAxis;
            option.series[0].data = data_1;
            option.series[1].data = data_2;
            option.series[2].data = data_3;
            chart.setOption(option);
        },
        // 绘制饼图
        drawChartPie: function(loadData){
            var chart = echarts.init(document.getElementById('chargeChargeStatisticsIndex_chart_pie'));
            option = {
                // title : {
                //     text: '各类充电站充电桩数量占比',
                //     x:'100'
                // },
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
                    orient : 'vertical', x : 'right',
                    //y: 'bottom',
                    data:['自营','客户自用','联营','合作']
                },
                series : [
                    {
                        name: '各类充电站充电桩数量占比',
                        type: 'pie',
                        radius : '80%',
                        center: ['43%', '58%'],
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
        },
        // 查询
        search: function(){
            var form = $('#chargeChargeStatisticsIndex_searchForm');
            var data = {};
            var searchCondition = form.serializeArray();
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = $.trim(searchCondition[i]['value']);
            }
            $('#chargeChargeStatisticsIndex_datagridByPole').datagrid('load',data);
        },
        // 重置
        reset: function(){
            $('#chargeChargeStatisticsIndex_searchForm').form('reset');
            chargeChargeStatisticsIndex.search();
        },
        // 导出Excel
        exportGridData: function(){
            var searchConditionStr = $('#chargeChargeStatisticsIndex_searchForm').serialize();
            window.open(chargeChargeStatisticsIndex.param.URL.exportGridData + "&" + searchConditionStr);
        }
    }

    // 执行初始化
    chargeChargeStatisticsIndex.init();
    // 绘制趋势图
    chargeChargeStatisticsIndex.drawChartLine();

</script>