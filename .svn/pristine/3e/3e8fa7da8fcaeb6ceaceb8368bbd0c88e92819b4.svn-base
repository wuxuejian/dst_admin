<table id="easyui_datagrid_vip_charge_record_exception_do"></table> 
<div id="easyui_datagrid_vip_charge_record_exception_do_toolbar">
    <form id="easyui_form_vip_charge_record_exception_do" style="background: none;">
        <input type="hidden" name="id" value="<?= $vcrInfo['id'] ?>" />
        <ul class="ulforform-resizeable">
            <li class="ulforform-resizeable-group">
                <div class="ulforform-resizeable-title">消费金额</div>
                <div class="ulforform-resizeable-input">
                    <input
                        class="easyui-textbox"
                        name="c_amount"
                        style="width: 100%;"
                        validType="money"
                    />
                </div>
            </li>
        </ul>
    </form>
    <div style="border-bottom:1px solid #95B8E7"></div>
    <div class="easyui-panel" title="电枪计费计量检测（显示充电记录开充电时间到现在的记录）" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    "></div>
</div>
<script>
    var VipChargeRecordExceptionDo = {
        // 初始化
        init: function() {
            //--初始化表格
            $('#easyui_datagrid_vip_charge_record_exception_do').datagrid({
                method: 'get',
                url: "<?=
                    yii::$app->urlManager->createUrl([
                        'vip/charge-record/pole-monitor',
                        'pold_id'=>$vcrInfo['pole_id'],
                        'mpn'=>$vcrInfo['measuring_point'],
                        'time_tag'=>$vcrInfo['write_datetime'],
                    ]); ?>",
                toolbar: "#easyui_datagrid_vip_charge_record_exception_do_toolbar",
                fit: true,
                border: false,
                pagination: true,
                loadMsg: '数据加载中...',
                striped: true,
                checkOnSelect: true,
                rownumbers: true,
                singleSelect: true,
                pageSize: 20,
                sortName: 'TIME_TAG',
                frozenColumns: [[
                    {field: 'TIME_TAG',title: '数据时间',width: 130,align: 'center',sortable: true}
                ]],
                columns: [[
                    {field: 'COSUM_AMOUNT', title: '消费金额', width: 100, align: 'center', sortable: true},
                    {field: 'CHARGE_AMOUNT', title: '充电电量', width: 100, align: 'center', sortable: true},
                    {field: 'SOC', title: 'SOC', width: 100, align: 'center', sortable: true},
                    {field: 'CAR_NO', title: '车号', width: 100, align: 'center', sortable: true},
                    {field: 'WRITE_TIME', title: '写库时间', width: 130, align: 'center', sortable: true}
                ]]
            });
        }
    };
    // 执行初始化函数
    VipChargeRecordExceptionDo.init();
</script>