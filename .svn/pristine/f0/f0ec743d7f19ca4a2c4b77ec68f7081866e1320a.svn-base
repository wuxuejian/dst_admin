<table id="easyui_datagrid_car_drive_statistics_index"></table> 
<div id="easyui_datagrid_car_drive_statistics_index_toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">
        <div class="data-search-form">
            <form id="search_form_car_drive_statistics_index">
                <ul class="search-main">
                    <li>
                        <div class="item-name">车牌号</div>
                        <div class="item-input">
                            <input
                                class="easyui-textbox"
                                name="plate_number"
                                style="width:200px;"
                                data-options="{onChange:function(){
                                    CarDriveStatisticsIndex.search();
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
                                style="width:200px;"
                                data-options="{onChange:function(){
                                    CarDriveStatisticsIndex.search();
                                }}"
                            />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">统计日期</div>
                        <div class="item-input">
                            <input
                                class="easyui-datebox"
                                name="date"
                                style="width:200px;"
                                value="<?= date('Y-m-d',strtotime('-1 day')); ?>"
                                data-options="{editable:false,onChange:function(){
                                    CarDriveStatisticsIndex.search();
                                }}"
                            />
                        </div>
                    </li>
                    <li class="search-button">
                        <button type="submit" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button type="submit" onclick="CarDriveStatisticsIndex.reset()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
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
<div id="easyui_window_car_drive_statistics_index_detail"></div>
<!-- 窗口 -->
<script>
    var CarDriveStatisticsIndex = {
        params: {
            url: {
                getListData:"<?= yii::$app->urlManager->createUrl(['car/drive-statistics/get-list-data']); ?>",
                detail: "<?= yii::$app->urlManager->createUrl(['car/drive-statistics/detail']); ?>"
            }
        },
        init: function(){
            var easyuiDatagrid = $('#easyui_datagrid_car_drive_statistics_index');
            easyuiDatagrid.datagrid({  
            method: 'get', 
            url: this.params.url.getListData,   
            fit: true,
            border: false,
            toolbar: "#easyui_datagrid_car_drive_statistics_index_toolbar",
            pagination: true,
            pageSize: 20,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
            frozenColumns: [[
                {field: 'ck',checkbox: true}, 
                {field: 'id',title: 'id',hidden: true},
                {field: 'plate_number',title: '车牌号',width: 70,sortable: true,align: 'center'}
            ]],
            columns: [[
                {field: 'car_vin',title: '车架号',width: 130,align: 'center',sortable: true},
                {field: 'start_datetime',title: '初次采集时间',width: 130,align: 'center'},
                {field: 'end_datetime',title: '最后采集时间',width: 130,align: 'center'},
                {field: 'start_soc',title: '初始电量(%)',width: 130,align: 'center'},
                {field: 'end_soc',title: '结束电量(%)',width: 130,align: 'center'},
                {field: 'start_mileage',title: '初始里程(km)',width: 130,align: 'center'},
                {field: 'end_mileage',title: '结束里程(km)',width: 130,align: 'center'}
            ]]
        }); 
        //构建查询表单
        var searchForm = $('#search_form_car_drive_statistics_index');
        /**查询表单提交事件**/
        searchForm.submit(function(){
            var data = {};
            var searchCondition = $(this).serializeArray();
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = $.trim(searchCondition[i]['value']);
            }
            easyuiDatagrid.datagrid('load',data);
            return false;
        });
        //初始化【日行驶明细】窗口
        $('#easyui_window_car_drive_statistics_index_detail').window({
            title: '日行驶明细',
            width: 1000,   
            height: 500,   
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
    },
    getSelected: function(all){
        var datagrid = $('#easyui_datagrid_car_drive_statistics_index');
        if(all){
            var selectRows = datagrid.datagrid('getSelections');
            if(selectRows.length <= 0){
                $.messager.show({title:'操作失败',msg:'请选择要操作的记录！'});
                return false;
            }
            return selectRows;
        }else{
            var selectRow = datagrid.datagrid('getSelected');
            if(!selectRow){
                $.messager.show({title:'操作失败',msg:'请选择要操作的记录！'});   
                return false;
            }
            return selectRow;
        }
    },
    search: function(){
        $('#search_form_car_drive_statistics_index').submit();
    },
    reset: function(){
        var easyuiForm = $('#search_form_car_drive_statistics_index');
        easyuiForm.form('reset');
        easyuiForm.submit();
    },
    detail: function(){
        var selectRow = this.getSelected(false);
        if(!selectRow){
            return false;
        }
        var date = $('#search_form_car_drive_statistics_index').find('input[textboxname=date]').datebox('getValue');
        $('#easyui_window_car_drive_statistics_index_detail')
            .window('open')
            .window('refresh',this.params.url.detail+'&car_vin='+selectRow.car_vin+'&date='+date);
    }
};
    //重置查询表单
    CarDriveStatisticsIndex.init();
</script>