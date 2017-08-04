<form id="easyui_form_card_charge_card_read" style="padding-top:10px;">
    <ul class="ulforform-resizeable">
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">串口号</div>
            <div class="ulforform-resizeable-input">
                <select
                    class="easyui-combobox"
                    name="port"
                    style="width:180px;"
                >
                    <option value="1">COM1</option>
                    <option value="2">COM2</option>
                    <option value="3">COM3</option>
                    <option value="4">COM4</option>
                    <option value="5">COM5</option>
                    <option value="6">COM6</option>
                    <option value="7">COM7</option>
                </select>
            </div>
        </li>
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">卡号</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    name="cc_code"
                    style="width:180px;"
                    readonly="true"
                />
            </div>
        </li>
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">卡类型</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    name="cc_type"
                    style="width:180px;"
                    readonly="true"
                />
            </div>
        </li>
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">余额</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    name="before_money"
                    style="width:180px;"
                    readonly="true"
                />
            </div>
        </li>
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">客户号</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    name="customer_code"
                    style="width:180px;"
                    readonly="true"
                />
            </div>
        </li>
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">客户名称</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    name="customer_client"
                    style="width:180px;"
                    readonly="true"
                />
            </div>
        </li>
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">客户手机</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    name="customer_mobile"
                    style="width:180px;"
                    readonly="true"
                />
            </div>
        </li>
    </ul>
</form>
<div id="easyui_window_card_charge_card_read_tipwin"></div>
<script>
    var CardChargeCardRead = {
        // 初始化
        init: function() {
            //
            $('#easyui_window_card_charge_card_read_tipwin').window({
                width:400,
                height:200,
                modal:true,
                title: '正在读写卡',
                closed: true,
                resizable: false,
                collapsible: false,
                minimizable: false,
                maximizable: false,
                closable: true,
                content: '<div style="text-align:center;padding-top:60px;">正在进行读写卡操作请等待...</div>'
            });
        },
        readCard: function(){
            var easyuiForm = $('#easyui_form_card_charge_card_read');
            var port = easyuiForm.find('select[comboname=port]').textbox('getValue');
            var tipWindow = $('#easyui_window_card_charge_card_read_tipwin');
            //读取卡号
            tipWindow.window('open');
            var readResult = KLChargeCard.readCard(port);
            tipWindow.window('close');
            if(!readResult.status){
                $.messager.alert('错误',readResult.info,'error');
                return false;
            }
            var cardNo = readResult.cardNo;
            var easyuiDatagrid = $('#easyui_datagrid_card_swap_do');
            easyuiDatagrid.datagrid('reload',"<?= yii::$app->urlManager->createUrl(['card/charge-record/exception-charge']); ?>&cardNo="+cardNo);
            easyuiForm.find('input[textboxname=cc_code]').textbox('setValue',cardNo);
            //读取卡内余额
            tipWindow.window('open');
            var moneyReadResult = KLChargeCard.ye(port);
            tipWindow.window('close');
            if(moneyReadResult.status){
                easyuiForm.find('input[textboxname=before_money]').textbox('setValue',moneyReadResult.money);
            }else{
                $.messager.alert('余额获取失败','余额获取失败，请重新读卡['+moneyReadResult.info+']！','error');
                return;
            }
            //读取卡片的客户信息
            $.ajax({
                type: 'get',
                url: "<?= yii::$app->urlManager->createUrl(['card/charge-card/get-info']); ?>",
                data: {"cardNo": cardNo},
                dataType: 'json',
                success: function(rData){
                    if(rData.status){
                        //写入数据到页面
                        easyuiForm.find('input[textboxname=cc_type]').textbox('setValue',rData.datas.cc_type);
                        easyuiForm.find('input[textboxname=customer_code]').textbox('setValue',rData.datas.code);
                        easyuiForm.find('input[textboxname=customer_client]').textbox('setValue',rData.datas.client);
                        easyuiForm.find('input[textboxname=customer_mobile]').textbox('setValue',rData.datas.mobile);
                    }else{
                        $.messager.error('错误',rData.info,'error');
                    }
                }
            });
        }
    };
    // 执行初始化函数
    CardChargeCardRead.init();
</script>