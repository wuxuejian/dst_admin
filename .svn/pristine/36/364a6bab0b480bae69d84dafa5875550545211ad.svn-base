<table id="easyui-datagrid-carmonitor-realtime-index"></table> 
<div id="easyui-datagrid-carmonitor-realtime-index-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-carmonitor-realtime-index">
                <ul class="search-main">
                    <li>
                        <div class="item-name">车辆品牌</div>
                        <div class="item-input">
                            <select
                                class="easyui-combobox"
                                name="data_source"
                                style="width:100%;"
                                data-options="{editable: false,panelHeight:'auto',onChange: function(){
                                    CarmonitorRealtimeIndex.search();
                                }}"
                            >
                                <option value="">不限</option>
                                <option value="北汽">北汽</option>
                                <option value="东风">东风</option>
                                <option value="比亚迪">比亚迪</option>
                            </select>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">车牌号</div>
                        <div class="item-input">
                            <input
                                class="easyui-textbox"
                                type="text"
                                name="plate_number"
                                style="width:100%;"
                                data-options="{onChange: function(){
                                    CarmonitorRealtimeIndex.search();
                                }}"
                            />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">车架号</div>
                        <div class="item-input">
                            <input
                                class="easyui-textbox"
                                name="car_vin"
                                style="width:100%;"
                                data-options="{onChange: function(){
                                    CarmonitorRealtimeIndex.search();
                                }}" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">车辆状态</div>
                        <div class="item-input">
                            <select
                                class="easyui-combobox"
                                name="car_current_status"
                                style="width:100%;"
                                data-options="{editable: false,panelHeight:'auto',onChange: function(){
                                    CarmonitorRealtimeIndex.search();
                                }}"
                            >
                                <option value="">不限</option>
                                <option value="stop">停止</option>
                                <option value="driving">行驶</option>
                                <option value="charging">充电</option>
                                <option value="offline">离线</option>
                            </select>
                        </div>
                    </li>
					<!--<li>
                        <div class="item-name">一级状态</div>
                        <div class="item-input">
                            <input style="width:200px;" name="car_status" />
                        </div>
                    </li>

                    <li>
                        <div class="item-name">二级状态</div>
                        <div class="item-input">
                            <input style="width:200px;" name="car_status2" />
                        </div>
                    </li>-->
                    <li>
                        <div class="item-name">承租客户</div>
                        <div class="item-input">
                            <select
                                id="easyui-combogrid-carmonitor-realtime-index-customerid"
                                name="customer_id"
                                style="width:100%;"
                            ></select>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">车辆类型</div>
                        <div class="item-input">
                            <select
                                class="easyui-combobox"
                                type="text"
                                name="car_type"
                                style="width:100%;"
                                data-options="{editable: false,panelHeight: 'auto',onChange: function(){
                                    CarmonitorRealtimeIndex.search();
                                }}"
                            >
                                <option value="">不限</option>
                                <?php foreach($config['car_type'] as $val){ ?>
                                <option value="<?= $val['value']; ?>"><?= $val['text']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </li>
                    <li class="search-button">
                        <button class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button onclick="CarmonitorRealtimeIndex.reset()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
                    </li>
                </ul>
            </form>
        </div>
    </div>
    <?php if($buttons){ ?>
    <div class="easyui-panel" title="数据列表" style="padding:3px 2px;width:100%;" data-options="
        iconCls: 'icon-table-list',
        border: false
    ">
        <?php foreach($buttons as $val){ ?>
        <button onclick="<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon']; ?>'"><?= $val['text']; ?></button>
        <?php } ?>
    </div>
    <?php } ?>
</div>
<!-- 窗口 -->
<div id="easyui-window-carmonitor-realtime-index-detail"></div>
<div id="easyui-window-carmonitor-realtime-index-analysis-battery"></div>
<div id="easyui-dialog-carmonitor-realtime-index-realtime-position"></div>
<div id="easyui-dialog-carmonitor-realtime-index-car-track"></div>
<div id="easyui-dialog-carmonitor-realtime-index-car-distribution"></div>
<!-- 窗口 -->
<script>
    var CarmonitorRealtimeIndex = new Object();
    CarmonitorRealtimeIndex.params = {
        url: {
            detail: "<?= yii::$app->urlManager->createUrl(['carmonitor/realtime/detail']); ?>",
            analysis_battery: "<?= yii::$app->urlManager->createUrl(['carmonitor/analysis/battery']); ?>",
            realtimePosition: "<?= yii::$app->urlManager->createUrl(['carmonitor/realtime/realtime-position']); ?>",
            carTrack: "<?= yii::$app->urlManager->createUrl(['carmonitor/realtime/car-track']); ?>"
        }
    };
    CarmonitorRealtimeIndex.init = function(){
        var easyuiDatagrid = $('#easyui-datagrid-carmonitor-realtime-index');
        var searchForm = $('#search-form-carmonitor-realtime-index');
        //获取列表数据
        $('#easyui-datagrid-carmonitor-realtime-index').datagrid({  
            method: 'get', 
            url:"<?php echo yii::$app->urlManager->createUrl(['carmonitor/realtime/get-list']); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-carmonitor-realtime-index-toolbar",
            pagination: true,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
            frozenColumns: [[
                {field: 'ck',checkbox: true}, 
                {field: 'id',title: 'id',hidden: true},
                {field: 'plate_number',title: '车牌号',width: 70,align: 'center'}
            ]],
            columns:[[
                {field: 'car_vin',title: '车架号',width: 120,align: 'center',sortable: true},
                {field: 'data_source',title: '数据来源',width: 60,align: 'center'},
                {
                    field: 'car_type',title: '车辆类型',width: 90,align: 'center',
                    formatter: function(value){
                        var carType = <?= json_encode($config['car_type']); ?>;
                        if(carType[value]){
                            return carType[value].text;
                        }
                    }
                },
                {
                    field: 'collection_datetime',title: '数据采集时间',width: 130,align: 'center',
                    sortable: true,
                    formatter: function(value){
                        return formatDateToString(value,true);
                    }
                },
                {
                    field: 'update_datetime',title: '记录更新时间',width: 130,align: 'center',
                    sortable: true,
                    formatter: function(value){
                        return formatDateToString(value,true);
                    }
                },
                {
                    field: 'car_current_status',title: '车辆状态',width: 80,align: 'center',
                    sortable: true,
                    formatter: function(value){
                        value = parseInt(value);
                        switch(value){
                            case 0:
                                return '<span style="background-color:#05CD69;color:#fff;padding:2px 5px;">停止</span>';
                            case 1:
                                return '<span style="background-color:#05CD69;color:#fff;padding:2px 5px;">行驶</span>';
                            case 2:
                                return '<span style="background-color:#FFCC01;color:#fff;padding:2px 5px;">充电</span>';
                            case 3:
                                return '<span style="background-color:#E7E7E7;color:#fff;padding:2px 5px;">离线</span>';
                        }
                    }
                },
                {field: 'total_driving_mileage',title: '累计行驶里程(km)',width: 100,align: 'center',sortable: true,formatter: function(value){
                        if(value == 429496736){
                            return '无效';
                        }
                        return value;
                    }
                },
                {
                    field: 'position_effective',title: '定位有效',width: 60,align: 'center',sortable: true,
                    formatter: function(value){
                        if(value == 0){
                            return '有效';
                        }
                        return '无效';
                    }
                },
                <?php /*{
                    field: 'latitude_type',title: '南北纬',width: 60,align: 'center',
                    sortable: true,
                    formatter: function(value){
                        if(value == 0){
                            return '北纬';
                        }
                        return '南纬';
                    }
                },
                {
                    field: 'longitude_type',title: '东西经',width: 60,align: 'center',
                    sortable: true,
                    formatter: function(value){
                        if(value == 0){
                            return '东经';
                        }
                        return '西经';
                    }
                },
                {field: 'longitude_value',title: '经度值',width: 80,halign: 'center',align: 'right',sortable: true},
                {field: 'latitude_value',title: '纬度值',width: 80,halign: 'center',align: 'right',sortable: true},*/ ?>
                {field: 'speed',title: '车速(km/h)',width: 80,align: 'center',sortable: true},
                {field: 'direction',title: '方向',width: 60,align: 'center',sortable: true},
                {field: 'battery_package_soc',title: '电池电量(%)',width: 80,align: 'center',sortable: true,formatter: function(value){
                        if(value == 102){
                            return '无效';
                        }
                        return value;
                    }
                }
            ]]
        });
        searchForm.submit(function(){
            var data = {};
            var searchCondition = $(this).serializeArray();
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = searchCondition[i]['value'];
            }
            easyuiDatagrid.datagrid('load',data);
            return false;
        });
		searchForm.find('input[name=car_status]').combobox({
            valueField:'value',
            textField:'text',
            data: <?= json_encode($searchFormOptions['car_status']); ?>,
            editable: false,
            panelHeight:'auto',
            onSelect: function(){
                searchForm.submit();
            }
        });
        searchForm.find('input[name=car_status2]').combobox({
            valueField:'value',
            textField:'text',
            data: <?= json_encode($searchFormOptions['car_status2']); ?>,
            editable: false,
            panelHeight:'auto',
            onSelect: function(){
                searchForm.submit();
            }
        });
        //初始化查看窗口
        $('#easyui-window-carmonitor-realtime-index-detail').window({
            title: '车辆综合数据监控',
            iconCls: 'icon-search',
            width: 1200,   
            height: 600,
            closed: true,   
            cache: true,   
            modal: true,
            //draggable: false,
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
        $('#easyui-window-carmonitor-realtime-index-analysis-battery').window({
            title: '车辆电池数据分析',
            iconCls: 'icon-search',
            width: 1000,   
            height: 500,   
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
        $('#easyui-dialog-carmonitor-realtime-index-realtime-position').window({
            title: '车辆实时定位',
            width: 1200,
            height: 600,
            closed: true,  
            cache: true,  
            modal: true,
            collapsible: false,
            minimizable: false,
            maximizable: true,
            content: '<iframe id="iframe-carmonitor-realtime-index-realtime-position" style="width:100%;height:100%;" frameborder="none"></iframe>',
            onClose: function(){
                var iframe = document.getElementById('iframe-carmonitor-realtime-index-realtime-position');
                    iframe.contentWindow.clearTimer();
                    //$(this).window('clear');
            }  
        });
        //车辆运行轨迹
        $('#easyui-dialog-carmonitor-realtime-index-car-track').window({
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
        //车辆分布
        $('#easyui-dialog-carmonitor-realtime-index-car-distribution').window({
            title: '车辆分布',
            iconCls: 'icon-search',
            width: 1200,   
            height: 600,
            closed: true,   
            cache: true,   
            modal: true,
            //draggable: false,
            resizable: false,
            collapsible: false,
            minimizable: false, 
            maximizable: true,
            onClose: function(){
                delete CarmonitorRealtimeDetail;//销毁变量
                $(this).window('clear');
            }       
        });
        //获取当前正在租车或试用的客户
        $('#easyui-combogrid-carmonitor-realtime-index-customerid').combogrid({ 
            pagination: true,
            pageSize: 10,
            pageList: [10,20,30],
            fitColumns: true,
            rownumbers: true,
            delay: 800,
            panelWidth:450,
            delay: 500,
            mode: 'remote',
            method: 'get',
            url: "<?= yii::$app->urlManager->createUrl(['carmonitor/realtime/get-leting-customer']); ?>",
            idField: 'id',
            textField: 'company_name',
            onSelect: function(){
                CarmonitorRealtimeIndex.search();
            },
            columns: [[
                {field:'number',title:'客户号',width:150,sortable:true},
                {field:'company_name',title:'客户公司名称',width:400,sortable:true}
            ]]
        });  
    }
    CarmonitorRealtimeIndex.init();
    //获取选择的记录
    //参数all = true标示是否要返回所有被选择的记录
    CarmonitorRealtimeIndex.getSelected = function(all){
        var datagrid = $('#easyui-datagrid-carmonitor-realtime-index');
        if(all){
            var selectRows = datagrid.datagrid('getSelections');
            if(selectRows.length <= 0){
                $.messager.alert('错误','请选择要操作的记录','error');   
                return false;
            }
            return selectRows;
        }else{
            var selectRow = datagrid.datagrid('getSelected');
            if(!selectRow){
                $.messager.alert('错误','请选择要操作的记录','error');   
                return false;
            }
            return selectRow;
        }
        
    }
    //查看详细
    CarmonitorRealtimeIndex.detail = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var easyuiWindow = $('#easyui-window-carmonitor-realtime-index-detail');
        easyuiWindow
            .dialog('open')
            .dialog('refresh',this.params.url.detail+'&car_vin='+selectRow.car_vin);
    }
    //电池数据分析
    CarmonitorRealtimeIndex.analysisBattery = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var easyuiWindow = $('#easyui-window-carmonitor-realtime-index-analysis-battery');
        easyuiWindow.window('open');
        easyuiWindow.window('refresh',this.params.url.analysis_battery+"&car_vin="+selectRow.car_vin);
    }
    //车辆实时定位
    CarmonitorRealtimeIndex.realtimePosition = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        /*if(selectRow.car_current_status == 3){
            $.messager.alert('操作失败','离线车辆无法查看实时定位！','error');
            return false;
        }*/
        var easyuiWindow = $('#easyui-dialog-carmonitor-realtime-index-realtime-position');
        easyuiWindow.window('open');
        var iframe = document.getElementById('iframe-carmonitor-realtime-index-realtime-position');
        $(iframe.contentWindow.document.body).html('');
        $(iframe).attr('src',this.params.url.realtimePosition+"&car_vin="+selectRow.car_vin);
    }
    //查看车辆行驶轨迹
    CarmonitorRealtimeIndex.carTrack = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var easyuiWindow = $('#easyui-dialog-carmonitor-realtime-index-car-track');
        easyuiWindow.window('open');
        easyuiWindow.window('refresh',this.params.url.carTrack+"&car_vin="+selectRow.car_vin);
    }
    //车辆分布图查看
    CarmonitorRealtimeIndex.carDistribution = function(){
        var easyuiWindow = $('#easyui-dialog-carmonitor-realtime-index-car-distribution');
        easyuiWindow.window('open');
        easyuiWindow.window('refresh',"<?php echo yii::$app->urlManager->createUrl(['carmonitor/realtime/car-distribution']); ?>");
    }
    CarmonitorRealtimeIndex.MapTest = function(){
        window.open('http://www.dstcar.com/map/');
    }
    //查询1
    CarmonitorRealtimeIndex.search = function(){
        $('#search-form-carmonitor-realtime-index').submit();
    }
  	//按条件导出车辆列表
    CarmonitorRealtimeIndex.exportWidthCondition = function(){
        var url = "<?php echo yii::$app->urlManager->createUrl(['carmonitor/realtime/export-width-condition']);?>";
        var form = $('#search-form-carmonitor-realtime-index');
        var data = {};
        var searchCondition = form.serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        for(var i in data){
            url += '&'+i+'='+data[i];
        }
        window.open(url);
    }
    //重置
    CarmonitorRealtimeIndex.reset = function(){
        var searchForm = $('#search-form-carmonitor-realtime-index');
        searchForm.form('reset');
        searchForm.submit();
    }
</script>