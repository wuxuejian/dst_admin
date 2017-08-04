<form id="easyui_form_card_recharge_add">
    <div class="easyui-panel" title="充电卡信息"    
        style="width:100%;height: 180px;padding:10px;background:#fafafa;"  
        data-options="
            iconCls:'icon-add',
            closable:false,   
            collapsible:false,
            border: false,
            minimizable:false,
            maximizable:false">  
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
                        name="ccrr_before_money"
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
        <div style="text-align:right"><a onclick="CardRechargeAdd.readCard()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">读卡</a></div>
    </div>
    <div style="border-bottom:1px solid #95B8E7"></div>
    <div class="easyui-panel" title="充值信息"    
        style="width:100%;height:200px;padding:10px;background:#fafafa;"  
        data-options="
            iconCls:'icon-add',
            closable:false,   
            collapsible:false,
            border: false,
            minimizable:false,
            maximizable:false">  
        <ul class="ulforform-resizeable">
            <li class="ulforform-resizeable-group">
                <div class="ulforform-resizeable-title">充值金额</div>
                <div class="ulforform-resizeable-input">
                    <input
                        class="easyui-textbox"
                        name="ccrr_recharge_money"
                        style="width:180px;"
                        required="true"
                        validType="money"
                    />
                </div>
            </li>
            <li class="ulforform-resizeable-group">
                <div class="ulforform-resizeable-title">充值奖励</div>
                <div class="ulforform-resizeable-input">
                    <input
                        class="easyui-textbox"
                        name="ccrr_incentive_money"
                        style="width:180px;"
                        value="0.00"
                        validType="money"
                    />
                </div>
            </li>
            <li class="ulforform-resizeable-group-single">
                <div class="ulforform-resizeable-title">备注</div>
                <div class="ulforform-resizeable-input">
                    <input
                        class="easyui-textbox"
                        name='ccrr_mark'
                        data-options="multiline:true"
                        style="height:60px;width:482px;"
                        validType="length[255]"
                    />
                </div>
            </li>
        </ul>
    </div>
</form>
<div id="easyui_window_card_recharge_add_tipwin"></div>
<script>
    var CardRechargeAdd = {
        init: function(){
            $('#easyui_window_card_recharge_add_tipwin').window({
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
            var easyuiForm = $('#easyui_form_card_recharge_add');
            var port = easyuiForm.find('select[comboname=port]').textbox('getValue');
            var tipWindow = $('#easyui_window_card_recharge_add_tipwin');
            //读取卡号
            tipWindow.window('open');
            var readResult = KLChargeCard.readCard(port);
            tipWindow.window('close');
            if(!readResult.status){
                $.messager.alert('错误',readResult.info,'error');
                return false;
            }
            var cardNo = readResult.cardNo;
            easyuiForm.find('input[textboxname=cc_code]').textbox('setValue',cardNo);
            //读取卡内余额
            tipWindow.window('open');
            var moneyReadResult = KLChargeCard.ye(port);
            tipWindow.window('close');
            if(moneyReadResult.status){
                easyuiForm.find('input[textboxname=ccrr_before_money]').textbox('setValue',moneyReadResult.money);
            }else{
                $.messager.alert('余额获取失败',moneyReadResult.info,'error');
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
        },
        writeCard: function(rechargeId,rechargeMoney,chargeTimes){
            var easyuiForm = $('#easyui_form_card_recharge_add');
            var port = easyuiForm.find('select[comboname=port]').textbox('getValue');
            var cardNo = easyuiForm.find('input[textboxname=cc_code]').textbox('getValue');
            var tipWindow = $('#easyui_window_card_recharge_add_tipwin');
            tipWindow.window('open');
            var writeResult = KLChargeCard.cz(port,cardNo,rechargeMoney,chargeTimes);
            tipWindow.window('close');
            if(writeResult.status){
                //如果写卡成功将数据提交给后台
                $.ajax({
                    type: 'get',
                    url: "<?= yii::$app->urlManager->createUrl(['card/recharge/write-success']); ?>&rechargeId="+rechargeId
                });
                $.messager.alert('操作成功','充值成功！','info');
                $('#cardChargeCardIndex_rechargeWin').dialog('close');
            }else{
                $.messager.alert('操作失败','充值失败['+writeResult.info+']！','info');
            }
        }
    };
    CardRechargeAdd.init();
</script>