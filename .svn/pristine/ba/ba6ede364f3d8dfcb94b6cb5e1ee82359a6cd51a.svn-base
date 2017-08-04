<table id="easyui-datagrid-car-monitor-index"></table> 
<div id="easyui-datagrid-car-monitor-index-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-car-monitor-index">
                <ul class="search-main">
                    <li>
                        <div class="item-name">车牌号</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="plate_number" style="width:150px;"></input>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">车架号（vin）</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="vehicle_dentification_number" style="width:150px;"></input>
                        </div>
                    </li>
                    <li class="search-button">
                        <a id="btn" href="javascript:CarMonitorIndex.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                    </li>
                </ul>
            </form>
        </div>
    </div>
    <?php if($buttons){ ?>
    <div class="easyui-panel" title="数据列表" style="padding:8px 4px;" data-options="
        iconCls: 'icon-tip',
        border: false
    ">
        <?php foreach($buttons as $val){ ?>
        <a href="javascript:<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon']; ?>'"><?= $val['text']; ?></a>
        <?php } ?>
    </div>
    <?php } ?>
</div>
<!-- 窗口 -->
<div id="easyui-dialog-car-monitor-index-detail"></div>
<div id="easyui-window-car-monitor-index-scan"></div>
<div id="easyui-dialog-car-monitor-index-edit"></div>
<div id="easyui-window-car-monitor-index-attachment"></div>
<div id="easyui-window-car-monitor-index-fault"></div>
<div id="easyui-dialog-car-monitor-index-driving-license"></div>
<!-- 窗口 -->
<script>
    var CarMonitorIndex = new Object();
    CarMonitorIndex.init = function(){
        //获取列表数据
        $('#easyui-datagrid-car-monitor-index').datagrid({  
            method: 'get', 
            url:"<?php echo yii::$app->urlManager->createUrl(['car/monitor/get-list']); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-car-monitor-index-toolbar",
            pagination: true,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: false,
            frozenColumns: [[
                {field: 'ck',checkbox: true}, 
                {field: 'id',title: 'id',hidden: true},   
                {field: 'plate_number',title: '车牌号',width: 100,sortable: true},   
            ]],
            columns:[[
                {field: 'car_vin',title: '车架号',width: 200,align: 'left',sortable: true},
                {field: 'data_source',title: '数据来源',width: 100,align: 'left',sortable: true},
                {
                    field: 'collection_datetime',title: '数据采集时间',width: 120,align: 'center',
                    sortable: true,
                    formatter: function(value){
                        return formatDateToString(value,true);
                    }
                },
                {
                    field: 'update_datetime',title: '记录更新时间',width: 120,align: 'left',
                    sortable: true,
                    formatter: function(value){
                        return formatDateToString(value,true);
                    }
                },
                {field: 'total_driving_mileage',title: '累计行驶里程(km)',width: 120,align: 'left',sortable: true},
                {
                    field: 'position_effective',title: '定位有效',width: 200,
                    align: 'left',sortable: true,
                    formatter: function(value){
                        if(value == 0){
                            return '有效';
                        }
                        return '无效';
                    }
                },
                {
                    field: 'latitude_type',title: '南北纬',width: 100,align: 'left',
                    sortable: true,
                    formatter: function(value){
                        if(value == 0){
                            return '北纬';
                        }
                        return '南纬';
                    }
                },
                {
                    field: 'longitude_type',title: '东西经',width: 200,align: 'left',
                    sortable: true,
                    formatter: function(value){
                        if(value == 0){
                            return '东经';
                        }
                        return '西经';
                    }
                },
                {field: 'latitude_value',title: '纬度值',width: 100,align: 'left',sortable: true},
                {field: 'longitude_value',title: '经度值',width: 100,align: 'left',sortable: true},
                {field: 'speed',title: '车速(km/h)',width: 100,align: 'left',sortable: true},
                {field: 'direction',title: '方向',width: 100,align: 'left',sortable: true},
                {field: 'battery_package_soc',title: '电池电量(%)',width: 100,align: 'left',sortable: true},
                {
                    field: 'car_current_status',title: '车辆行驶状态',width: 100,align: 'left',
                    sortable: true,
                    formatter: function(value){
                        switch(value){
                            case '0':
                                return '停止';
                            case '1':
                                return '行驶';
                            case '2':
                                return '充电';    
                        }
                        return '错误';
                    }
                }
            ]]
        });
        //初始化添加窗口
        $('#easyui-dialog-car-monitor-index-add').dialog({
            title: '添加车辆信息',   
            width: '980px',   
            height: '500px',   
            closed: true,   
            cache: true,   
            modal: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var form = $('#easyui-form-car-baseinfo-add');
                    if(!form.form('validate')) return false;
                    var data = form.serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/add']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('添加成功',data.info,'info');
                                $('#easyui-dialog-car-monitor-index-add').dialog('close');
                                $('#easyui-datagrid-car-monitor-index').datagrid('reload');
                            }else{
                                $.messager.alert('添加失败',data.info,'error');
                            }
                        }
                    });
                }
            },{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui-dialog-car-monitor-index-add').dialog('close');
                }
            }]
        });
        //初始化查看窗口
        $('#easyui-dialog-car-monitor-index-detail').window({
            title: '查看实时数据详细信息',
            width: 1000,   
            height: 600,   
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
        
        //初始化附件管理窗口
        $('#easyui-window-car-monitor-index-attachment').window({
            title: '车辆附件管理',
            width: 800,   
            height: 500,   
            modal: true,
            closed: true,
            collapsible: false,
            minimizable: false,
            maximizable: false,
            onClose: function(){
                $(this).window('clear')
            }                    
        });
    }
    CarMonitorIndex.init();
    //获取选择的记录
    //参数all = true标示是否要返回所有被选择的记录
    CarMonitorIndex.getSelected = function(all){
        var datagrid = $('#easyui-datagrid-car-monitor-index');
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
    CarMonitorIndex.detail = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        $('#easyui-dialog-car-monitor-index-detail').dialog('open');
        $('#easyui-dialog-car-monitor-index-detail').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/monitor/detail']); ?>&id="+selectRow.id);
    }
    //删除
    CarMonitorIndex.remove = function(){
        var selectRow = this.getSelected();
        if(!selectRow) return false;
        var id = selectRow.id;
        $.messager.confirm('确定删除','您确定要删除该汽车数据？',function(r){
            if(r){
                $.ajax({
                    type: 'get',
                    url: '<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/remove']); ?>',
                    data: {id: id},
                    dataType: 'json',
                    success: function(data){
                        if(data){
                            $.messager.alert('删除成功',data.info,'info');   
                            $('#easyui-datagrid-car-monitor-index').datagrid('reload');
                        }else{
                            $.messager.alert('删除失败',data.info,'error');   
                        }
                    }
                });
            }
        });
    }
    //附件管理
    CarMonitorIndex.attachment = function(){
        var selectRow = this.getSelected();
        if(!selectRow) return false;
        var id = selectRow.id;
        $('#easyui-window-car-monitor-index-attachment').window('open');
        $('#easyui-window-car-monitor-index-attachment').window('refresh','<?php echo yii::$app->urlManager->createUrl(['car/attachment/index-single']); ?>&carId='+id);
    }
    //故障管理
    CarMonitorIndex.faultMange = function(){
        var selectRow = this.getSelected();
        if(!selectRow) return false;
        var id = selectRow.id;
        $('#easyui-window-car-monitor-index-fault').window('open');
        $('#easyui-window-car-monitor-index-fault').window('refresh','<?php echo yii::$app->urlManager->createUrl(['car/fault/index']); ?>&carId='+id);
    }
    //行驶证管理
    CarMonitorIndex.drivingLicense = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var id = selectRow.id;
        $('#easyui-dialog-car-monitor-index-driving-license').dialog('open');
        $('#easyui-dialog-car-monitor-index-driving-license').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/driving-license']); ?>&carId="+id);
    }
    //道路运输证管理
    CarMonitorIndex.roadTransportCertificate = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var id = selectRow.id;
        $('#easyui-dialog-car-monitor-index-road-transport-certificate').dialog('open');
        $('#easyui-dialog-car-monitor-index-road-transport-certificate').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/road-transport-certificate']); ?>&carId="+id);
    }
    //二级维护记录管理
    CarMonitorIndex.secondMaintenanceRecord = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var id = selectRow.id;
        $('#easyui-window-car-baseinfo-second-maintenance-record').window('open');
        $('#easyui-window-car-baseinfo-second-maintenance-record').window('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/second-maintenance-record']); ?>&carId="+id);
    }
    //交强险管理
    CarMonitorIndex.trafficCompulsoryInsurance = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var id = selectRow.id;
        $('#easyui-window-car-baseinfo-traffic-compulsory-insurance').window('open');
        $('#easyui-window-car-baseinfo-traffic-compulsory-insurance').window('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/traffic-compulsory-insurance']); ?>&carId="+id);
    }
    //商业险管理
    CarMonitorIndex.businessInsurance = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var id = selectRow.id;
        $('#easyui-window-car-baseinfo-business-insurance').window('open');
        $('#easyui-window-car-baseinfo-business-insurance').window('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/business-insurance']); ?>&carId="+id);
    }
    //导出所选择车辆的信息
    CarMonitorIndex.exportChooseCar = function(){
        var selectRows = this.getSelected(true);
        if(!selectRows){
            return false;
        }
        var id = '';
        for(var i in selectRows){
            id += selectRows[i].id+',';
        }
        window.open("<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/export-choose']);?>&id="+id);
    }
    //按条件导出车辆列表
    CarMonitorIndex.exportWidthCondition = function(){
        var url = "<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/export-width-condition']);?>";
        var form = $('#search-form-car-monitor-index');
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
    //查询
    CarMonitorIndex.search = function(){
        var form = $('#search-form-car-monitor-index');
        var data = {};
        var searchCondition = form.serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        $('#easyui-datagrid-car-monitor-index').datagrid('load',data);
    }
</script>