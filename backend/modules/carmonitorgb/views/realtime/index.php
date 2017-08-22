<table id="easyui-datagrid-carmonitorgb-realtime-index"></table> 
<div id="easyui-datagrid-carmonitorgb-realtime-index-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-carmonitorgb-realtime-index">
                <ul class="search-main">
                    <li>
                        <div class="item-name">数据来源</div>
                        <div class="item-input">
                            <select
                                class="easyui-combobox"
                                name="company_no"
                                style="width:100%;"
                                data-options="{editable: false,panelHeight:'auto',onChange: function(){
                                    CarmonitorgbRealtimeIndex.search();
                                }}"
                            >
                                <option value="">不限</option>
                                <option value="1">福嘉太</option>
                                <option value="2">G7</option>
								<option value="3">北汽(地标)</option>
								<option value="4">东风(地标)</option>
                            </select>
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
                                    CarmonitorgbRealtimeIndex.search();
                                }}" />
                        </div>
                    </li>
                    <li class="search-button">
                        <button class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button onclick="CarmonitorgbRealtimeIndex.reset()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
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
<div id="easyui-window-carmonitorgb-realtime-index-detail"></div>
<div id="easyui-window-carmonitorgb-realtime-index-analysis-battery"></div>
<div id="easyui-dialog-carmonitorgb-realtime-index-realtime-position"></div>
<div id="easyui-dialog-carmonitorgb-realtime-index-car-track"></div>
<div id="easyui-dialog-carmonitorgb-realtime-index-car-distribution"></div>
<!-- 窗口 -->
<script>
    var CarmonitorgbRealtimeIndex = new Object();
    CarmonitorgbRealtimeIndex.params = {
        url: {
            detail: "<?= yii::$app->urlManager->createUrl(['carmonitorgb/realtime/detail']); ?>",
            analysis_battery: "<?= yii::$app->urlManager->createUrl(['carmonitorgb/analysis/battery']); ?>",
            realtimePosition: "<?= yii::$app->urlManager->createUrl(['carmonitorgb/realtime/realtime-position']); ?>",
            carTrack: "<?= yii::$app->urlManager->createUrl(['carmonitorgb/realtime/car-track']); ?>"
        }
    };
    CarmonitorgbRealtimeIndex.init = function(){
        var easyuiDatagrid = $('#easyui-datagrid-carmonitorgb-realtime-index');
        var searchForm = $('#search-form-carmonitorgb-realtime-index');
        //获取列表数据
        $('#easyui-datagrid-carmonitorgb-realtime-index').datagrid({  
            method: 'get', 
            url:"<?php echo yii::$app->urlManager->createUrl(['carmonitorgb/realtime/get-list']); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-carmonitorgb-realtime-index-toolbar",
            pagination: true,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
            frozenColumns: [[
                {field: 'ck',checkbox: true}, 
                {field: 'id',title: 'id',hidden: true},
				{field: 'carVin',title: '车架号',width: 140,align: 'center',sortable: true},
                {field: 'deviceNo',title: '设备号',width: 140,align: 'center',sortable: true}
            ]],
            columns:[[
                {field: 'companyNo',title: '数据来源',width: 60,align: 'center'},
                {
                    field: 'collectionDatetime',title: '数据采集时间',width: 130,align: 'center',
                    sortable: true,
                    formatter: function(value){
                        return formatDateToString(value,true);
                    }
                },
                {
                    field: 'updateDatetime',title: '记录更新时间',width: 130,align: 'center',
                    sortable: true,
                    formatter: function(value){
                        return formatDateToString(value,true);
                    }
                },
                {
                    field: 'carStatus',title: '车辆状态',width: 80,align: 'center',
                    sortable: true,
                    formatter: function(value){
                    	switch(value){
	                        case 1:
	                            return '启动';
	                        case 2:
	                            return '熄火';
	                        case 3:
	                            return '其它';
	                        case 254:
	                            return '异常';
	                        case 255:
	                            return '无效';
	                        case 255:
	                            return value;
	                    }
                    }
                },
				{
                    field: 'carChargeStatus',title: '充电状态',width: 80,align: 'center',
                    sortable: true,
                    formatter: function(value){
                    	switch(value){
	                        case 0:
	                            return '默认';
	                        case 1:
	                            return '放电';
	                        case 2:
	                            return '充电';
	                        case 3:
	                            return '其它';
	                        default:
	                        	return value;
	                    }
                    }
                },
                {field: 'totalDrivingMileage',title: '累计行驶里程(km)',width: 100,align: 'center',sortable: true,formatter: function(value){
                        return value;
                    }
                },
                {field: 'longitudeValue',title: '经度值',width: 80,halign: 'center',align: 'right',sortable: true},
                {field: 'latitudeValue',title: '纬度值',width: 80,halign: 'center',align: 'right',sortable: true},
                {field: 'speed',title: '车速(km/h)',width: 80,align: 'center',sortable: true},
                {field: 'soc',title: '电池电量(%)',width: 80,align: 'center',sortable: true,formatter: function(value){
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
        //初始化查看窗口
        $('#easyui-window-carmonitorgb-realtime-index-detail').window({
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
                clearInterval(CarmonitorgbRealtimeDetail.timer);//关闭计时器
                delete CarmonitorgbRealtimeDetail;//销毁变量
                $(this).window('clear');
            }       
        });
        //查看车辆电池数据分析
        $('#easyui-window-carmonitorgb-realtime-index-analysis-battery').window({
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
        $('#easyui-dialog-carmonitorgb-realtime-index-realtime-position').window({
            title: '车辆实时定位',
            width: 1200,
            height: 600,
            closed: true,  
            cache: true,  
            modal: true,
            collapsible: false,
            minimizable: false,
            maximizable: true,
            content: '<iframe id="iframe-carmonitorgb-realtime-index-realtime-position" style="width:100%;height:100%;" frameborder="none"></iframe>',
            onClose: function(){
                var iframe = document.getElementById('iframe-carmonitorgb-realtime-index-realtime-position');
                    iframe.contentWindow.clearTimer();
                    //$(this).window('clear');
            }  
        });
        //车辆运行轨迹
        $('#easyui-dialog-carmonitorgb-realtime-index-car-track').window({
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
        $('#easyui-dialog-carmonitorgb-realtime-index-car-distribution').window({
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
                delete CarmonitorgbRealtimeDetail;//销毁变量
                $(this).window('clear');
            }       
        });
        //获取当前正在租车或试用的客户
        $('#easyui-combogrid-carmonitorgb-realtime-index-customerid').combogrid({ 
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
            url: "<?= yii::$app->urlManager->createUrl(['carmonitorgb/realtime/get-leting-customer']); ?>",
            idField: 'id',
            textField: 'company_name',
            onSelect: function(){
                CarmonitorgbRealtimeIndex.search();
            },
            columns: [[
                {field:'number',title:'客户号',width:150,sortable:true},
                {field:'company_name',title:'客户公司名称',width:400,sortable:true}
            ]]
        });  
    }
    CarmonitorgbRealtimeIndex.init();
    //获取选择的记录
    //参数all = true标示是否要返回所有被选择的记录
    CarmonitorgbRealtimeIndex.getSelected = function(all){
        var datagrid = $('#easyui-datagrid-carmonitorgb-realtime-index');
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
    CarmonitorgbRealtimeIndex.detail = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var easyuiWindow = $('#easyui-window-carmonitorgb-realtime-index-detail');
        easyuiWindow
            .dialog('open')
            .dialog('refresh',this.params.url.detail+'&car_vin='+selectRow.carVin);
    }
    //电池数据分析
    CarmonitorgbRealtimeIndex.analysisBattery = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var easyuiWindow = $('#easyui-window-carmonitorgb-realtime-index-analysis-battery');
        easyuiWindow.window('open');
        easyuiWindow.window('refresh',this.params.url.analysis_battery+"&car_vin="+selectRow.carVin);
    }
    //车辆实时定位
    CarmonitorgbRealtimeIndex.realtimePosition = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        /*if(selectRow.car_current_status == 3){
            $.messager.alert('操作失败','离线车辆无法查看实时定位！','error');
            return false;
        }*/
        var easyuiWindow = $('#easyui-dialog-carmonitorgb-realtime-index-realtime-position');
        easyuiWindow.window('open');
        var iframe = document.getElementById('iframe-carmonitorgb-realtime-index-realtime-position');
        $(iframe.contentWindow.document.body).html('');
        $(iframe).attr('src',this.params.url.realtimePosition+"&car_vin="+selectRow.carVin);
    }
    //查看车辆行驶轨迹
    CarmonitorgbRealtimeIndex.carTrack = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var easyuiWindow = $('#easyui-dialog-carmonitorgb-realtime-index-car-track');
        easyuiWindow.window('open');
        easyuiWindow.window('refresh',this.params.url.carTrack+"&car_vin="+selectRow.carVin);
    }
    //车辆分布图查看
    CarmonitorgbRealtimeIndex.carDistribution = function(){
        var easyuiWindow = $('#easyui-dialog-carmonitorgb-realtime-index-car-distribution');
        easyuiWindow.window('open');
        easyuiWindow.window('refresh',"<?php echo yii::$app->urlManager->createUrl(['carmonitorgb/realtime/car-distribution']); ?>");
    }
    CarmonitorgbRealtimeIndex.MapTest = function(){
        window.open('http://www.dstcar.com/map/');
    }
    //查询1
    CarmonitorgbRealtimeIndex.search = function(){
        $('#search-form-carmonitorgb-realtime-index').submit();
    }
  	//按条件导出车辆列表
    CarmonitorgbRealtimeIndex.exportWidthCondition = function(){
        var url = "<?php echo yii::$app->urlManager->createUrl(['carmonitorgb/realtime/export-width-condition']);?>";
        var form = $('#search-form-carmonitorgb-realtime-index');
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
    CarmonitorgbRealtimeIndex.reset = function(){
        var searchForm = $('#search-form-carmonitorgb-realtime-index');
        searchForm.form('reset');
        searchForm.submit();
    }
</script>