<form id="cardChargeCardIndex_rechargeWin_form" method="post" style="padding:5px;">
    <table cellpadding="6" cellspacing="0" style="width:90%;" border="0" align="center">
        <tr hidden>
            <td align="right">电卡ID：</td>
            <td>
                <input class="easyui-textbox" style="width:120px;" name="ccrr_card_id" value="<?php echo $ChargeCardInfo['cc_id']; ?>" />
            </td>
        </tr>
        <tr>
            <td align="right">电卡编号：</td>
            <td>
                <?php echo $ChargeCardInfo['cc_code']; ?>
            </td>
        </tr>
        <tr>
            <td align="right">会员编号：</td>
            <td>
                <?php echo $ChargeCardInfo['cc_holder_code']; ?>
            </td>
        </tr>
        <tr>
            <td align="right">初始额度：</td>
            <td>
                <?php echo $ChargeCardInfo['cc_initial_money']; ?> 元
            </td>
        </tr>
        <tr>
            <td align="right">当前余额：</td>
            <td>
                <span id="currentMoney"><?php echo $ChargeCardInfo['cc_current_money']; ?></span> 元
            </td>
        </tr>
        <tr>
            <td align="right">充值金额：</td>
            <td>
                <input class="easyui-numberbox" style="width:150px;" name="ccrr_recharge_money" id="rechargeMoney"
                       data-options="
                            required: true,
                            precision: 2,
                            min: 0.00,
                            onChange: function(newValue,oldValue){
                                $('#incentiveMoney').numberbox('reset');
                                var currentMoney = $('#currentMoney').text();
                                if(!newValue) {
                                    newValue = 0.00;
                                }
                                var newTotalMoney = (parseFloat(currentMoney) + parseFloat(newValue)).toFixed(2);
                                $('#afterMoney').html(newTotalMoney);
                            }
                       "
                /> 元
            </td>
        </tr>
        <tr>
            <td align="right">奖励金额：</td>
            <td>
                <input class="easyui-numberbox" style="width:150px;" name="ccrr_incentive_money" id="incentiveMoney"
                       data-options="
                            required: false,
                            precision: 2,
                            min: 0.00,
                            onChange: function(newValue,oldValue){
                                var currentMoney = $('#currentMoney').text();
                                var rechargeMoney = $('#rechargeMoney').numberbox('getValue');
                                if(!rechargeMoney) rechargeMoney = 0.00;
                                if(!newValue) newValue = 0.00;
                                var newTotalMoney = (parseFloat(currentMoney) + parseFloat(rechargeMoney) + parseFloat(newValue)).toFixed(2);
                                $('#afterMoney').html(newTotalMoney);
                            }
                       "
                /> 元
            </td>
        </tr>
        <tr>
            <td align="right">充值后余额：</td>
            <td>
                <span id="afterMoney" style="font-size:16px;color:#FF0000;"><?php echo $ChargeCardInfo['cc_current_money']; ?></span> 元
            </td>
        </tr>
        <tr>
            <td align="right" valign="top">备注：</td>
            <td>
                <input class="easyui-textbox" name="ccrr_mark" style="width:230px;height:100px;"
                       data-options="multiline:true"
                       validType="length[200]"  />
            </td>
        </tr>
    </table>
</form>

<script>
    // 加载出旧数据
    $('#cardChargeCardIndex_rechargeWin_form').form('load',<?php echo json_encode($ChargeCardInfo); ?>);

</script>