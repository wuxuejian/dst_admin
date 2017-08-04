<table id="easyui_datagrid_card_charge_card_recharge_record"></table>
<div id="easyui_datagrid_card_charge_card_recharge_record_toolbar">
    <div class="data-search-form">
        <form id="search_from_card_charge_card_recharge_record">
            <ul class="search-main">
                <li>
                    <div class="item-name">充值单号</div>
                    <div class="item-input">
                        <input name="ccrr_code" style="width:100%;"  />
                    </div>
                </li>
                <li>
                    <div class="item-name">充值日期</div>
                    <div class="item-input">
                        <input name="ccrr_create_time_start" style="width:91px;"  />
                        -
                        <input name="ccrr_create_time_end" style="width:91px;"  />
                    </div>
                </li>
                <li>
                    <div class="item-name">写卡状态</div>
                    <div class="item-input">
                        <input name="write_status" style="width:100%;"  />
                    </div>
                </li>
                <li class="search-button">
                    <button class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                    <button onclick="CardChargeCardRechargeRecord.resetForm();return false;" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
                </li>
            </ul>
        </form>
    </div>
</div>
<script type="text/javascript">
    var CardChargeCardRechargeRecord = {
        init: function(){
            var easyuiDatagrid = $('#easyui_datagrid_card_charge_card_recharge_record');
            var searchForm = $('#search_from_card_charge_card_recharge_record');
            easyuiDatagrid.datagrid({
                method: 'get',
                url: "<?= yii::$app->urlManager->createUrl(['card/charge-card/get-recharge-records','cc_id'=>$cc_id]); ?>",
                toolbar: '#easyui_datagrid_card_charge_card_recharge_record_toolbar',
                fit:true,
                border: false,
                pagination: true,
                loadMsg: '数据加载中...',
                striped: true,
                checkOnSelect: true,
                rownumbers: true,
                singleSelect: false,
                pageSize: 20,
                frozenColumns: [[
                    {field: 'ck', checkbox: true},
                    {field: 'ccrr_id', title: '充值记录ID', width: 40, align: 'center', hidden: true},
                    {field: 'ccrr_code', title: '充值单号', width: 120, align: 'center', sortable: true},
                    {field: 'ccrr_card_id', title: '电卡ID', width: 40, align: 'center', hidden: true}
                ]],
                columns:[[
                    {field:'ccrr_recharge_money',title:'充值金额(元)',width:90,halign:'center',align:'right',sortable:true},
                    {field:'ccrr_incentive_money',title:'奖励金额(元)',width:90,halign:'center',align:'right',sortable:true},
                    {field:'ccrr_before_money',title:'充值前余额(元)',width:100,halign:'center',align:'right',sortable:true},
                    {field:'ccrr_after_money',title:'充值后余额(元)',width:100,halign:'center',align:'right',sortable:true},
                    {field:'ccrr_create_time',title:'充值时间',width:130,align:'center',sortable:true},
                    {field: 'write_status',title: '写卡状态',width: 80,
                        align: 'center',sortable: true,
                        formatter: function(value){
                            if(value == 'success'){
                                return '<span style="background-color:#05CD69;color:#fff;padding:2px 5px;">成功</span>';
                            }
                            return '<span style="background-color:#FF4040;color:#fff;padding:2px 5px;">失败</span>'
                    }},
                    {field:'ccrr_creator_id',title:'操作人员id',width:100,align:'center',sortable:true,hidden:true},
                    {field:'ccrr_creator',title:'操作人员',width:100,align:'center',sortable:true},
                    {field:'ccrr_mark',title:'备注',width:160,halign: 'center',sortable:true}
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
            searchForm.find('input[name=ccrr_code]').textbox({
                onChange: function(){
                    searchForm.submit();
                }
            });
            searchForm.find('input[name=ccrr_create_time_start]').datebox({
                editable: false,
                onChange: function(){
                    searchForm.submit();
                }
            });
            searchForm.find('input[name=ccrr_create_time_end]').datebox({
                editable: false,
                onChange: function(){
                    searchForm.submit();
                }
            });
            searchForm.find('input[name=write_status]').combobox({
                valueField:'value',
                textField:'text',
                data: [{text: '不限',value: ''},{text: '成功',value: 'success'},{text: '失败',value: 'fail'}],
                editable: false,
                panelHeight:'auto',
                onSelect: function(){
                    searchForm.submit();
                }
            });
            //查询表单自动化处理结束
        },
        resetForm: function(){
            var searchForm = $('#search_from_card_charge_card_recharge_record');
            searchForm.form('reset');
            searchForm.submit();
        }
    };
    CardChargeCardRechargeRecord.init();
</script>