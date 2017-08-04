<table id="easyui_datagrid_card_charge_card_charge_record"></table>
<div id="easyui_datagrid_card_charge_card_charge_record_toolbar">
    <div class="data-search-form">
        <form id="search_from_card_charge_card_charge_record">
            <ul class="search-main">
                <li>
                    <div class="item-name">交易流水号</div>
                    <div class="item-input">
                        <input name="DEAL_NO" style="width:150px;"  />
                    </div>
                </li>
                <li>
                    <div class="item-name">消费日期</div>
                    <div class="item-input">
                        <input name="DEAL_START_DATE_start" style="width:91px;"  />
                        -
                        <input name="DEAL_START_DATE_end" style="width:91px;"  />
                    </div>
                </li>
                <li class="search-button">
                    <button class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                    <button onclick="CardChargeCardChargeRecord.resetForm();return false;" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
                </li>
            </ul>
        </form>
    </div>
</div>
<script type="text/javascript">
    var CardChargeCardChargeRecord = {
        init: function(){
            var easyuiDatagrid = $('#easyui_datagrid_card_charge_card_charge_record');
            var searchForm = $('#search_from_card_charge_card_charge_record');
            easyuiDatagrid.datagrid({
                method: 'get',
                url: "<?= yii::$app->urlManager->createUrl(['card/charge-card/get-consume-records','cc_id'=>$cc_id]); ?>",
                toolbar: '#easyui_datagrid_card_charge_card_charge_record_toolbar',
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
                    {field: 'DEAL_NO', title: '交易流水号', width: 80,align: 'center',sortable: true},
                ]],
                columns: [[
                    {field: 'START_CARD_NO', title: '电卡编号', width: 120, align: 'center', sortable: true},
                    {field: 'end_status', title: '状态', width: 80, align: 'center', sortable: true,
                        formatter:function(v){
                            switch(parseInt(v)){
                                case 0:
                                    return '<span style="background-color:#FFCC01;color:#fff;padding:2px 5px;">正在充电</span>';
                                case 1:
                                    return '<span style="background-color:#05CD69;color:#fff;padding:2px 5px;">结束正常</span>';
                                case 2:
                                    return '<span style="background-color:#FF4040;color:#fff;padding:2px 5px;">结束异常</span>';
                            }
                        }
                    },

                    {field: 'START_DEAL_DL', title: '开始电量(度)', width: 90, halign: 'center',align:'right', sortable: true},
                    {field: 'END_DEAL_DL', title: '结束电量(度)', width: 90, halign: 'center',align:'right', sortable: true},
                    {field: 'consume_DL', title: '<span style="color:#FF8000;">消费电量(度)</span>', width: 90, halign: 'center',align:'right'},
                    {field: 'REMAIN_BEFORE_DEAL', title: '交易前余额(元)', width: 100, halign: 'center',align:'right', sortable: true},
                    {field: 'REMAIN_AFTER_DEAL', title: '交易后余额(元)', width: 100, halign: 'center',align:'right', sortable: true},
                    {field: 'consume_money', title: '<span style="color:#FF8000;">消费金额(元)</span>', width: 100, halign: 'center',align:'right'},

                    {field: 'DEAL_START_DATE', title: '开始时间', width: 130, align: 'center', sortable: true},
                    {field: 'DEAL_END_DATE', title: '结束时间', width: 130, align: 'center', sortable: true},

//                    {field: 'x', title: '用户', width: 60, align: 'center', sortable: true},
//                    {field: 'x', title: '电桩编号', width: 60, align: 'center', sortable: true},
//                    {field: 'x', title: '电站名称', width: 60, align: 'center', sortable: true},
//                    {field: 'x', title: '运营商', width: 60, align: 'center', sortable: true},

//                    {field: 'TRM_NO', title: '终端号', width: 60, align: 'center', sortable: true},
                    {field: 'CAR_NO', title: '车号', width: 50, align: 'center', sortable: true},
                    {field: 'INNER_ID', title: '测量点', width: 50, align: 'center', sortable: true},
                    {field: 'TIME_TAG', title: '记录时间', width: 130, align: 'center', sortable: true}
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
            searchForm.find('input[name=DEAL_NO]').textbox({
                onChange: function(){
                    searchForm.submit();
                }
            });
            searchForm.find('input[name=DEAL_START_DATE_start]').datebox({
                editable: false,
                onChange: function(){
                    searchForm.submit();
                }
            });
            searchForm.find('input[name=DEAL_START_DATE_end]').datebox({
                editable: false,
                onChange: function(){
                    searchForm.submit();
                }
            });
            //查询表单自动化处理结束
        },
        resetForm: function(){
            var searchForm = $('#search_from_card_charge_card_charge_record');
            searchForm.form('reset');
            searchForm.submit();
        }
    };
    CardChargeCardChargeRecord.init();
</script>