<form id="personalContractIndex_renewManageWin_renewAddWin_from" class="easyui-form">
    <input type="hidden" name="contract_id" value="<?php echo $contractId; ?>" />
    <ul class="ulforform-resizeable">
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">应收金额</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    style="width:160px;"
                    disabled="true"
                    value="<?php echo $shouldMoney; ?>"
                />
            </div>
        </li>
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">实收金额</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    style="width:160px;"
                    name="true_money"
                    required="true"
                    missingMessage="请输入实收金额！"
                    validType="money"
                >
            </div>
        </li>
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">到期时间</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-datebox"
                    style="width:160px;"
                    name="cost_expire_time"
                    required="true"
                    missingMessage="请选到期时间！"
                    validType="date"
                >
            </div>
        </li>
        <li class="ulforform-resizeable-group-single">
            <div class="ulforform-resizeable-title">备注</div>
            <div class="ulforform-resizeable-input">
                <input 
                    class="easyui-textbox"
                    name="note"
                    data-options="multiline:true"
                    style="height:30px;width:462px;"
                />
            </div>
        </li>
    </ul>
</form>
<div style="border-bottom:1px solid #95B8E7;"></div>
<div class="easyui-panel" title="当前出租车辆" style="width:100%;" data-options="
        iconCls: 'icon-tip',
        border: false
    "></div>
<table id="personalContractIndex_renewManageWin_renewAddWin_datagrid"></table>
<script>
    var personalContractIndex_renewManageWin_renewAddWin = new Object();
    personalContractIndex_renewManageWin_renewAddWin.init = function(){
        $('#personalContractIndex_renewManageWin_renewAddWin_datagrid').datagrid({  
            border: false,
            pagination: false,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
            showFooter: true,
            columns:[[
                {field: 'plate_number',title: '车牌号',width: 125, align: 'center'},
                {field: 'month_rent',title: '月租金',width: 125, align: 'center'},
                {
                    field: 'let_time',title: '出租时间',width: 125, align: 'center',
                    formatter: function(value){
                        if(value > 0){
                            return formatDateToString(value);
                        }
                    }
                },
                {field: 'note',title: '备注',width: 125, align: 'center'}
            ]]
        });
        var datagrid = $('#personalContractIndex_renewManageWin_renewAddWin_datagrid');
        <?php foreach($letCar as $val){ ?>
        datagrid.datagrid('appendRow',<?php echo json_encode($val); ?>);
        <?php } ?>
    }
    personalContractIndex_renewManageWin_renewAddWin.init();
</script>