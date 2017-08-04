<form id="customerContractRenewIndex_renewAddWin_topForm" class="easyui-form">
    <ul class="ulforform-resizeable"  style="margin:5px auto;">
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">续费合同</div>
            <div class="ulforform-resizeable-input">
                <input
                    id="customerContractRenewIndex_renewAddWin_chooseContract"
                    name="contract_id"
                    style="width:160px;"
                />
            </div>
        </li>
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">应收金额</div>
            <div class="ulforform-resizeable-input">
                <input
                    id="customerContractRenewIndex_renewAddWin_shouldMoney"
                    class="easyui-textbox"
                    style="width:160px;"
                    disabled="true"
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
                    style="height:40px;width:462px;"
                />
            </div>
        </li>
    </ul>
</form>
<div style="border-bottom:1px solid #95B8E7;"></div>
<div class="easyui-panel" title="当前出租车辆" data-options="
        iconCls: 'icon-car',
        border: false
    "></div>
<table id="customerContractRenewIndex_renewAddWin_add"></table>

<script>
    // 配置数据
    var customerContractRenewIndex_renewAddWin_CONFIG = <?php echo json_encode($config); ?>;

    // 初始数据
    var contractId = <?php echo $contractId; ?>;

    // 请求的URL
    customerContractRenewIndex_renewAddWin_URL_getContractList = "<?php echo yii::$app->urlManager->createUrl(['customer/combogrid/get-contract-list']); ?>";
    customerContractRenewIndex_renewAddWin_URL_getContractCars = "<?php echo yii::$app->urlManager->createUrl(['customer/contract-renew/get-contract-cars']); ?>";

    var customerContractRenewIndex_renewAddWin = {
        init: function () {
            // 初始化选择续费合同combogrid
            $('#customerContractRenewIndex_renewAddWin_chooseContract').combogrid({
                panelWidth: 500,
                panelHeight: 200,
                delay: 800,
                mode: 'remote',
                idField: 'contract_id',
                textField: 'contract_number',
                url: customerContractRenewIndex_renewAddWin_URL_getContractList,
                method: 'get',
                scrollbarSize: 0,
                pagination: true,
                pageSize: 10,
                pageList: [10, 20, 30],
                fitColumns: true,
                rownumbers: true,
                columns: [[
                    {field: 'contract_id', title: '合同ID', width: 50, align: 'center',hidden:true},
                    {field: 'contract_number', title: '合同编号', width: 120, halign: 'center'},
                    {field: 'customer_name', title: '承租客户', width: 230, halign: 'center',
                        formatter: function(value,row,index){
                            if(row.cCustomer_name){
                                return row.cCustomer_name;
                            }else if(row.pCustomer_name){
                                return row.pCustomer_name;
                            }else{
                                return '';
                            }
                        }
                    },
                    {field: 'customer_type', title: '客户类型', width: 80, align: 'center',
                        formatter: function(value){
                            try {
                                var str = 'customerContractRenewIndex_renewAddWin_CONFIG.customer_type.' + value + '.text';
                                return eval(str);
                            } catch (e) {
                                return value;
                            }
                        }
                    }
                ]],
                required: true,
                missingMessage: '请从下拉列表里选择续费合同！',
                onHidePanel: function () {
                    var _combogrid = $(this);
                    var value = _combogrid.combogrid('getValue');
                    var textbox = _combogrid.combogrid('textbox');
                    var text = textbox.val();
                    var rows = _combogrid.combogrid('grid').datagrid('getSelections');
                    if (text && rows.length < 1 && value == text) {
                        $.messager.show({
                            title: '无效合同',
                            msg: '【' + text + '】不是有效合同！<br/>必须从检索的下拉列表里选择一行！'
                        });
                        _combogrid.combogrid('clear');
                    }else{ // 获取该合同相关数据赋值
                        var data = {contractId:value};
                        $('#customerContractRenewIndex_renewAddWin_add').datagrid('load',data);
                    }
                }
            });

            // 初始化车辆datagrid
            $('#customerContractRenewIndex_renewAddWin_add').datagrid({
                border: false,
                pagination: false,
                loadMsg: '数据加载中...',
                striped: true,
                checkOnSelect: true,
                rownumbers: true,
                singleSelect: true,
                showFooter: true,
                method: 'get',
                url: customerContractRenewIndex_renewAddWin_URL_getContractCars + '&contractId=' + contractId,
                columns: [[
                    {field: 'plate_number', title: '车牌号', width: 125, align: 'center'},
                    {field: 'month_rent', title: '月租金', width: 125, align: 'center'},
                    {
                        field: 'let_time', title: '出租时间', width: 125, align: 'center',
                        formatter: function (value) {
                            if (value > 0) {
                                return formatDateToString(value);
                            }
                        }
                    },
                    {field: 'note', title: '备注', width: 220, align: 'center'}
                ]],
                onLoadSuccess:function(data){ // 表格加载完成后为表单‘应收金额’字段赋值
                    var rows = data.rows;
                    var shouldMoney = 0.00;
                    for(var i=0;i<rows.length;i++){
                        shouldMoney += parseFloat(rows[i].month_rent);
                    }
                    $('#customerContractRenewIndex_renewAddWin_shouldMoney').textbox('setValue',shouldMoney);
                }
            });
        }
    }

    // 执行初始化函数
    customerContractRenewIndex_renewAddWin.init();

    // 加载旧表单数据,查出合同以赋值显示text,因为combogrid远程查询第一页不一定存在该合同而显示为id
    if(parseInt(contractId) > 0){
        $('#customerContractRenewIndex_renewAddWin_topForm').form('load',{contract_id: contractId});
        var data = {contractId: contractId};
        $('#customerContractRenewIndex_renewAddWin_chooseContract').combogrid('grid').datagrid('load',data);
    }

</script>