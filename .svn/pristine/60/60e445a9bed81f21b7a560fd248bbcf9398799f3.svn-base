<div class="easyui-panel" title="车辆行驶证信息" border="false" collapsible="true" style="height:600px;">  
	<table id="easyui_datagrid_car_overview_status_change_log"></table>
</div>
<div id="easyui_datagrid_car_overview_status_change_log_toolbar">
    <div class="data-search-form">
        <form id="search_from_car_overview_status_change_log">
            <ul class="search-main">
                <li>
                    <div class="item-name">变更时间</div>
                    <div class="item-input">
                        <input name="add_time_start" style="width:91px;"  />
                        -
                        <input name="add_time_end" style="width:91px;"  />
                    </div>
                </li>
                <li class="search-button">
                    <button class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                    <button onclick="CarOverviewStatusChangeLog.resetForm();return false;" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
                </li>
            </ul>
        </form>
    </div>
</div>
<script type="text/javascript">
    var CarOverviewStatusChangeLog = {
        init: function(){
            var easyuiDatagrid = $('#easyui_datagrid_car_overview_status_change_log');
            var searchForm = $('#search_from_car_overview_status_change_log');
            easyuiDatagrid.datagrid({
                method: 'get',
                url: "<?= yii::$app->urlManager->createUrl(['car/overview/get-status-change-logs','car_id'=>$car_id]); ?>",
                toolbar: '#easyui_datagrid_car_overview_status_change_log_toolbar',
                fit:true,
                border: false,
                pagination: true,
                loadMsg: '数据加载中...',
                striped: true,
                checkOnSelect: true,
                rownumbers: true,
                singleSelect: false,
                pageSize: 20,
                columns:[[
                    {field: 'id', title: 'ID', width: 40, align: 'center', hidden: true},
                    {field: 'add_time', title: '变更时间', width: 150, align: 'center', sortable: true},
                    {
                        field:'pre_status',title:'变更前一级状态',width:90,halign:'center',align:'center',sortable:true,
                        formatter: function(value){
                            var status = <?php echo json_encode($config['car_status']); ?>;
                            try{
                                return status[value].text;
                            }catch(e){
                                return value;
                            }
                        }
                    },
					{
                        field:'after_status',title:'变更后一级状态',width:90,halign:'center',align:'center',sortable:true,
                        formatter: function(value){
                            var status = <?php echo json_encode($config['car_status']); ?>;
                            try{
                                return status[value].text;
                            }catch(e){
                                return value;
                            }
                        }
                    },
                    {field:'code_url',title:'代码URL',width:100,halign:'center',align:'center',sortable:true},
                    {field:'note',title:'变更记录',width:100,halign:'center',align:'center',sortable:true},
					{field:'oper_name',title:'操作人',width:100,halign:'center',align:'center',sortable:true}
                ]]
            });
            //查询表单自动化
            searchForm.submit(function(){
                var data = {};
                var searchCondition = $(this).serializeArray();
                for(var i in searchCondition){
                    data[searchCondition[i]['name']] = searchCondition[i]['value'];
                }
                easyuiDatagrid.datagrid('load',data);
                return false;
            });
            searchForm.find('input[name=add_time_start]').datebox({
                editable: false,
                onChange: function(){
                    searchForm.submit();
                }
            });
            searchForm.find('input[name=add_time_end]').datebox({
                editable: false,
                onChange: function(){
                    searchForm.submit();
                }
            });
			/*
            searchForm.find('input[name=car_status]').combobox({
                valueField:'value',
                textField:'text',
                data: <?= json_encode($searchFormOptions['car_status']); ?>,
                editable: false,
                panelHeight:'auto',
                onSelect: function(){
                    searchForm.submit();
                }
            });*/
            //查询表单自动化处理结束
        },
        resetForm: function(){
            var searchForm = $('#search_from_car_overview_status_change_log');
            searchForm.form('reset');
            searchForm.submit();
        }
    };
    CarOverviewStatusChangeLog.init();
</script>