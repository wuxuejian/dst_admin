<div
    class="easyui-layout"
    data-options="fit:true,border:false"
>  
    <div data-options="region:'north',border:false">
        <div class="data-search-form">
        <form id="search-form-carmonitorgb-realtime-car-distribution">
            <ul class="search-main">
                <li>
                    <div class="item-name">车辆状态</div>
                    <div class="item-input">
                        <select
                            class="easyui-combobox"
                            name="car_current_status"
                            style="width:100%"
                            data-options="{editable: false,panelHeight: 'auto',onChange: function(){
                                CarmonitorgbRealtimeCarDistribution.search();
                            }}"
                        >
                            <option value="">不限</option>
                            <option value="stop">停止</option>
                            <option value="driving" selected="true">行驶</option>
                            <option value="charging">充电</option>
                            <option value="offline">离线</option>
                        </select>
                    </div>
                </li>
                <li>
                    <div class="item-name">车架号(逗号分隔)</div>
                    <div class="item-input">
						<input
							class="easyui-textbox"
							name="car_vins"
							style="width:100%;"/>
                    </div>
                </li>
                <li class="search-button">
                    <button class="easyui-linkbutton" data-options="iconCls:'icon-search'">查看</button>
                    <button onclick="CarmonitorgbRealtimeCarDistribution.reset();return false;" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
                </li>
            </ul>
        </form>
    </div>
    </div>  
    <div data-options="region:'center',title:'地图显示轨迹',border:false">
        <iframe id="iframe-carmonitorgb-realtime-car-distribution" style="width:100%;height:100%;" frameborder="none"></iframe>
    </div>  
</div>
<script>
    var CarmonitorgbRealtimeCarDistribution = {
        init: function(){
            $('#easyui-combogrid-carmonitorgb-realtime-car-distribution-customer').combogrid({   
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
                    CarmonitorgbRealtimeCarDistribution.search();
                },
                columns: [[
                    {field:'number',title:'客户号',width:150,sortable:true},
                    {field:'company_name',title:'客户公司名称',width:400,sortable:true}
                ]]
            });
            var searchForm = $('#search-form-carmonitorgb-realtime-car-distribution');
            searchForm.submit(function(){
                CarmonitorgbRealtimeCarDistribution.search();
                return false;
            });
        },
        search: function(){
            var iframe = document.getElementById('iframe-carmonitorgb-realtime-car-distribution');
            $(iframe.contentWindow.document.body).html('');
            var form = $('#search-form-carmonitorgb-realtime-car-distribution');
            $(iframe).attr('src',"<?php echo yii::$app->urlManager->createUrl(['carmonitorgb/realtime/car-distribution-map']); ?>"+'&'+form.serialize());
        },
        reset: function(){
            var form = $('#search-form-carmonitorgb-realtime-car-distribution');
            form.form('reset');
            form.submit();
        }
    };
    CarmonitorgbRealtimeCarDistribution.init();
    CarmonitorgbRealtimeCarDistribution.search();
</script>