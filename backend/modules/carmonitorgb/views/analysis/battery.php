<div
    class="easyui-layout"
    data-options="fit:true,border:false"
>  
    <div data-options="region:'north',border:false" style="">
        <div class="data-search-form">
            <form id="search-form-carmonitorgb-analysis-battery">
                <input type="hidden" name="car_vin" value="<?php echo $carVin; ?>" />
                <ul class="search-main">
                    <li>
                        <div class="item-name">开始时间</div>
                        <div class="item-input">
                            <input
                                name="start_datetime"
                                value="<?= date('Y-m-d H'); ?>:00:00"
                            />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">结束时间</div>
                        <div class="item-input">
                            <input name="end_datetime" />
                        </div>
                    </li>
                    <li class="search-button">
                        <button class="easyui-linkbutton" data-options="iconCls:'icon-search'">分析</button>
                        <button onclick="CarmonitorgbAnalysisBattery.resetSearchForm();return false;" class="easyui-linkbutton" data-options="iconCls:'icon-search'">重置</button>
                    </li>
                </ul>
            </form>
            <div style="color:red;text-align:right;line-height:24px;">注意：无法统计跨月份数据！</div>
        </div>
    </div>  
    <div data-options="region:'center',title:'数据分析',border:false">
        <?php
        if($buttons){
            echo '<div style="padding:10px;">';
            foreach($buttons as $val){
                echo '<button onclick="',$val['on_click'],'" class="easyui-linkbutton" data-options="iconCls:\'',$val['icon'],'\'">',$val['text'],'</button>';
            }
            echo '</div>';
        }
        ?>
        <div id="echart-carmonitorgb-analysis-battery-tem" style="width:98%;height:400px;margin:0 auto;"></div>
        <div id="echart-carmonitorgb-analysis-battery-vol" style="width:98%;height:400px;margin:0 auto;"></div>
    </div>  
</div>
<script>
    var CarmonitorgbAnalysisBattery = new Object();
    CarmonitorgbAnalysisBattery.init = function(){
        //查询表单自动化处理
        var easyuiForm = $('#search-form-carmonitorgb-analysis-battery');
        easyuiForm.submit(function(){
            $.ajax({
                "type": "post",
                "url": "<?= yii::$app->urlManager->createUrl(['carmonitorgb/analysis/battery']); ?>",
                "data": $(this).serialize(),
                "dataType": "json",
                "success": function(rData){
                    if(rData.status){
                        CarmonitorgbAnalysisBattery.drawTemData(rData.temData);
                        CarmonitorgbAnalysisBattery.drawVolData(rData.volData);
                    }else{
                        $.messager.alert('错误',rData.msg,'error');
                    }
                }
            });
            return false;
        });
        easyuiForm.find('input[name=start_datetime]').datetimebox({
            editable: false,
            onChange: function(){
                easyuiForm.submit();
            }
        });
        easyuiForm.find('input[name=end_datetime]').datetimebox({
            editable: false,
            onChange: function(){
                easyuiForm.submit();
            }
        })
        //查询表单自动化处理结束
        //easyuiForm.submit();
    }
    //绘制电池温度数据
    CarmonitorgbAnalysisBattery.drawTemData = function(tData){
        if(!tData){
            return false;
        }
        var chat = echarts.init(document.getElementById('echart-carmonitorgb-analysis-battery-tem'));
        var totalPackage = tData.totalPackage;//总包数
        var totalProbe = tData.totalProbe;//总探针数
        var packageData = {};//各包的温度数据
        var legendData = [];
        var seriesData = [];
        var xAxisData = [];
        //对数据进行分组
        for(var i in tData.data){
            packageData[i] = [];
            for(j in tData.data[i]){
                packageData[i].push(tData.data[i][j].tem_val);
				if(i == 0){
					xAxisData.push(tData.data[i][j].collection_datetime);
				}
            }
        }
        for(var i in tData.data){
            legendData.push('电池包'+(parseInt(i) + 1));
            seriesData.push({
                "name": '电池包'+(parseInt(i) + 1),
                "type": 'line',
                "data": packageData[i],
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
        var option = {
            title : {
                text: '电池包温度数据',
                subtext: '电池包总数： '+totalPackage+'  总探针数：'+totalProbe
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
    //绘制电池电压数据
    CarmonitorgbAnalysisBattery.drawVolData = function(vData){
        if(!vData){
            return false;
        }
        var chat = echarts.init(document.getElementById('echart-carmonitorgb-analysis-battery-vol'));
        var totalPackage = vData.totalPackage;//总包数
        var totalSingleBattery = vData.totalSingleBattery;//电池总数
        var packageData = {};//各包的温度数据
        var legendData = [];
        var seriesData = [];
        var xAxisData = [];
        for(var i in vData.data){
            packageData[i] = [];
            for(j in vData.data[i]){
                packageData[i].push(vData.data[i][j].vol_val);
				if(i == 0){
					xAxisData.push(vData.data[i][j].collection_datetime);
				}
            }
        }
        for(var i in vData.data){
            legendData.push('电池包'+(parseInt(i)+1));
            seriesData.push({
                "name": '电池包'+(parseInt(i)+1),
                "type": 'line',
                "data": packageData[i],
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
        var option = {
            title : {
                text: '电池包电压数据',
                subtext: '电池包总数： '+totalPackage+'  电池数：'+totalSingleBattery
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
                        formatter: '{value} V'
                    }
                }
            ],
            series : seriesData
        };
        chat.setOption(option,true);
    }
    //导出电池历史电池数据
    CarmonitorgbAnalysisBattery.exportBatData = function(){
        window.open("<?= yii::$app->urlManager->createUrl(['carmonitorgb/analysis/export-bat-data']); ?>");
    }
    //重置查询表单
    CarmonitorgbAnalysisBattery.resetSearchForm = function(){
        var easyuiForm = $('#search-form-carmonitorgb-analysis-battery');
        easyuiForm.form('reset');
        easyuiForm.submit();
    }
    CarmonitorgbAnalysisBattery.init();
</script>