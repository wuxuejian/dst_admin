<form id="cardChargeCardIndex_add_form" method="post" style="padding:5px;">
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
            <div class="ulforform-resizeable-title">电卡编号</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    name="cc_code"
                    style="width:180px;"
                    required="true"
                    validType="match[/^\d{16}$/]"
                    missingMessage="请输入电卡编号！"
                    invalidMessage="请输入16位数字！"
                />
            </div>
        </li>
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">电卡类型</div>
            <div class="ulforform-resizeable-input">
                <select class="easyui-combobox" name="cc_type" style="width:180px;" data-options="panelHeight:'auto',required:true"  editable=false >
                    <?php foreach($config['cc_type'] as $val){ ?>
                        <option value="<?php echo $val['value'] ?>"><?php echo $val['text']; ?></option>
                    <?php } ?>
                </select>
            </div>
        </li>
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">电卡状态</div>
            <div class="ulforform-resizeable-input">
                <select class="easyui-combobox" name="cc_status" style="width:180px;" data-options="panelHeight:'auto',required:true"  editable=false >
                    <?php foreach($config['cc_status'] as $val){ ?>
                        <option value="<?php echo $val['value'] ?>"><?php echo $val['text']; ?></option>
                    <?php } ?>
                </select>
            </div>
        </li>
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">初始额度</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-numberbox"
                    style="width:180px;"
                    name="cc_initial_money"
                    required="true"
                    precision="2"
                    validType="money"
                />元
            </div>
        </li>
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">制卡日期</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-datebox"
                    style="width:180px;"
                    name="cc_start_date"
                    required="true"
                    validType="date"
                    value="<?php echo date('Y-m-d'); ?>"
                />
            </div>
        </li>
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">有效日期</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-datebox"
                    style="width:180px;"
                    name="cc_end_date"
                    validType="date"
                />
            </div>
        </li>
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">所属会员</div>
            <div class="ulforform-resizeable-input">
                <input
                    id="cardChargeCardIndex_add_chooseVip"
                    name="cc_holder_id"
                    style="width:180px"
                />
            </div>
        </li>
        <li class="ulforform-resizeable-group-single">
            <div class="ulforform-resizeable-title">备注</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    name="cc_mark"
                    style="width:482px;height:86px;"
                    data-options="multiline:true"
                    validType="length[200]"
                />
            </div>
        </li>
    </ul>
</form>
<div style="color:red;text-align:center;"><b>注意：请先将卡插入读卡器，并选择正确的串口号！</b></div>
<div id="card_charge_card_add_tipwin"></div>
<script>
    // 请求的URL
    var cardChargeCardIndex_add_URL_ChooseVip = "<?= yii::$app->urlManager->createUrl(['card/charge-card/get-vip']); ?>";
    var cardChargeCardIndex_add = {
        init: function(){
            // 初始化电卡开卡会员combobox
            $('#cardChargeCardIndex_add_chooseVip').combogrid({
                panelWidth: 500,
                panelHeight: 210,
                required: true,
                missingMessage: '请从下拉列表里选择会员！',
                onHidePanel:function(){
                    var _combogrid = $(this);
                    var value = _combogrid.combogrid('getValue');
                    var textbox = _combogrid.combogrid('textbox');
                    var text = textbox.val();
                    var rows = _combogrid.combogrid('grid').datagrid('getSelections');
                    if(text && rows.length < 1 && value == text){
                        $.messager.show(
                            {
                                title: '无效值',
                                msg:'【' + text + '】不是有效值！请重新检索并选择一个会员！'
                            }
                        );
                        _combogrid.combogrid('clear');
                    }
                },
                delay: 800,
                mode:'remote',
                idField: 'vip_id',
                textField: 'vip_code',
                url: cardChargeCardIndex_add_URL_ChooseVip,
                method: 'get',
                scrollbarSize:0,
                pagination: true,
                pageSize: 10,
                pageList: [10,20,30],
                fitColumns: true,
                columns: [[
                    {field:'vip_id',title:'ID',width:20,align:'center'},
                    {field:'vip_code',title:'会员编号',width:140,align:'center'},
                    {field:'vip_mobile',title:'会员手机',width:90,align:'center'},
                    {field:'vip_name',title:'会员名称',width:140,halign:'center'}
                ]]
            });
            //初始化读写卡提示窗口
            $('#card_charge_card_add_tipwin').window({
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
        writeCard: function(cardNo,money,ccrr_id){
            //写卡
            var easyuiForm = $('#cardChargeCardIndex_add_form');
            var port = easyuiForm.find('select[comboname=port]').textbox('getValue');
            var tipWindow = $('#card_charge_card_add_tipwin');
            //开卡
            tipWindow.window('open');
            var writeResult = KLChargeCard.fk(port,cardNo);
            tipWindow.window('close');
            //充值初始金额
            if(writeResult.status){
                if(money > 0 ){
                    //如果初始额度大于0则添加金额
                    tipWindow.window('open');
                    var rechargeWriteResult = KLChargeCard.cz(port,cardNo,money,1);
                    tipWindow.window('close');
                    if(rechargeWriteResult.status){
                        //向后台提交充值成功
                        $.ajax({
                            type: 'get',
                            url: "<?= yii::$app->urlManager->createUrl(['card/recharge/write-success']); ?>&rechargeId="+ccrr_id
                        });
                        var tips = '开卡成功，初始金额写入成功['+writeResult.info+'！';
                        $.messager.alert('开卡成功',tips,'info');
                    }else{
                        var tips = '开卡成功，初始金额写入失败，请通过充值操作添加金额['+writeResult.info+'，'+rechargeWriteResult.info+']'+'！';
                        $.messager.alert('错误',tips,'error');
                    }
                }else{
                    $.messager.alert('开卡成功','开卡成功！','info');
                }
                $('#cardChargeCardIndex_addWin').dialog('close');
                $('#cardChargeCardIndex_datagrid').datagrid('reload');
            }else{
                $.messager.alert('开卡失败，请进行重写卡操作！',writeResult.info,'error');
            }
            
        }
    }

    // 执行初始化函数
    cardChargeCardIndex_add.init();
</script>