<form id="chargeChargeCardIndex_addEditWin_form" method="post" style="padding:5px;">
    <table cellpadding="6" cellspacing="0" style="width:90%;" border="0" align="center">
        <tr hidden>
            <td align="right">电卡ID</td>
            <td>
                <input class="easyui-textbox" name="cc_id" style="width:230px;" value="0" editable="false"  />
            </td>
        </tr>
        <tr>
            <td align="right">电卡编号</td>
            <td>
                <input class="easyui-textbox" name="cc_code" style="width:230px;" required="true" missingMessage="请输入电卡编号！"  />
            </td>
        </tr>
        <tr>
            <td align="right">电卡类型</td>
            <td>
                <select class="easyui-combobox" name="cc_type" style="width:230px;" data-options="panelHeight:'auto',required:true"  editable=false >
                    <?php foreach($config['cc_type'] as $val){ ?>
                        <option value="<?php echo $val['value'] ?>"><?php echo $val['text']; ?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>
        <tr>
            <td align="right">电卡状态</td>
            <td>
                <select class="easyui-combobox" name="cc_status" style="width:230px;" data-options="panelHeight:'auto',required:true"  editable=false >
                    <?php foreach($config['cc_status'] as $val){ ?>
                        <option value="<?php echo $val['value'] ?>"><?php echo $val['text']; ?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>
        <tr>
            <td align="right">初始额度</td>
            <td>
                <input class="easyui-numberbox" style="width:230px;" name="cc_initial_money" required="true" precision="2" min="0.00"  />元
            </td>
        </tr>
        <tr>
            <td align="right">制卡日期</td>
            <td>
                <input class="easyui-datebox" style="width:230px;" name="cc_start_date" required="true" validType="date" value="<?php echo date('Y-m-d'); ?>" />
            </td>
        </tr>
        <tr>
            <td align="right">有效日期</td>
            <td>
                <input class="easyui-datebox" style="width:230px;" name="cc_end_date" required="true" validType="date" />
            </td>
        </tr>
        <tr>
            <td align="right">选择会员</td>
            <td>
                <input id="chargeChargeCardIndex_addEditWin_chooseVip" name="cc_holder_id"  style="width:230px" />
            </td>
        </tr>
        <tr>
            <td align="right" valign="top">备注</td>
            <td>
                <input class="easyui-textbox" name="cc_mark" style="width:230px;height:100px;"
                       data-options="multiline:true"
                       validType="length[200]"  />
            </td>
        </tr>
    </table>
</form>

<script>
    // 初始数据
    var chargeChargeCardIndex_addEditWin_initData = <?php echo json_encode($initData); ?>;
    // 请求的URL
    var chargeChargeCardIndex_addEditWin_URL_ChooseVip = "<?php echo yii::$app->urlManager->createUrl(['charge/charge-card/get-vip']); ?>";

    var chargeChargeCardIndex_addEditWin = {
        init: function(){
            // 初始化电卡开卡会员combobox
            $('#chargeChargeCardIndex_addEditWin_chooseVip').combogrid({
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
                url: chargeChargeCardIndex_addEditWin_URL_ChooseVip,
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
        }
    }

    // 执行初始化函数
    chargeChargeCardIndex_addEditWin.init();

    // 修改时加载出旧数据
	if(chargeChargeCardIndex_addEditWin_initData.action == 'edit'){
        $('#chargeChargeCardIndex_addEditWin_form').form('load',chargeChargeCardIndex_addEditWin_initData.ChargeCardInfo);
        // 查旧客户以赋值显示text,因为combogrid远程查询第一页不一定存在该客户而显示为id
        var vip = {vipId: chargeChargeCardIndex_addEditWin_initData.ChargeCardInfo.cc_holder_id};
        $('#chargeChargeCardIndex_addEditWin_chooseVip').combogrid('grid').datagrid('load',vip);
    }

</script>