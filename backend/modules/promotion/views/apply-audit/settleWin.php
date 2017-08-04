<div style="padding:10px 20px;">
    <div style="padding:10px 0px 20px 0px;">以下是该用户的活动奖励信息，请务必核实并完成转账后再操作完成结算！</div>
    <table cellspacing="1" cellpadding="8" width="100%" align="center"  border="0">
        <tr>
            <td align="center" style="background-color:#EBF2FE;">姓名</td>
            <td align="center" style="background-color:#EBF2FE;">手机</td>
            <td align="center" style="background-color:#EBF2FE;">邀请注册总数</td>
            <td align="center" style="background-color:#EBF2FE;">朋友租车总数</td>
            <td align="center" style="background-color:#EBF2FE;">奖金总额(元)</td>
            <td align="center" style="background-color:#EBF2FE;">已结算(元)</td>
            <td align="center" style="background-color:#EBF2FE;">待结算(元)</td>
            <td align="center" style="background-color:#EBF2FE;">本次申请金额(元)</td>
        </tr>
        <tr>
            <td align="center"><?php echo $statistics['inviter']; ?></td>
            <td align="center"><?php echo $statistics['inviter_mobile']; ?></td>
            <td align="center"><?php echo $statistics['total_invite_num']; ?></td>
            <td align="center"><?php echo $statistics['total_rent_num']; ?></td>
            <td align="right"><?php echo $statistics['total_reward']; ?></td>
            <td align="right"><?php echo $statistics['total_reward_settled']; ?></td>
            <td align="right"><?php echo $statistics['total_reward_unsettled']; ?></td>
            <td align="right"><?php echo $statistics['unsettled_reward']; ?></td>
        </tr>
    </table>
    <form id="promotionApplyAuditIndex_settleWin_form">
        <input type="hidden" name="applyCash_id" value="<?php echo $statistics['applyCash_id']; ?>" />
        <input type="hidden" name="inviter_id" value="<?php echo $statistics['apply_id']; ?>" />
        <!-- 请注意下面使用三元操作符的地方：
            有时候第一次申请尚未被结算用户又提交了第二次申请，而后台作结算操作时若先结算了第一次而后又来结算第二次，
            此时就会出现本次申请结算金额大于实际待结算金额。则本次实际要结算金额以实际待结算金额为准，且实际要结算租车记录id也以实际待结算租车记录id为准
        -->
        <input type="hidden" name="unsettled_letIds" value="<?php echo $statistics['unsettled_reward'] > $statistics['total_reward_unsettled'] ? $statistics['unsettled_letIds'] : $statistics['apply_letIds']; ?>" />
        <div style="padding:40px 0px 20px 0px;text-align:center;">
            <span style="font-size:15px;">本次结算金额:</span>
            <input class="easyui-numberbox" name="settled_money" data-options="editable:false,precision:2" style="height:30px;" value="<?php echo $statistics['unsettled_reward'] > $statistics['total_reward_unsettled'] ? $statistics['total_reward_unsettled'] : $statistics['unsettled_reward']; ?>" /> 元
        </div>
        <div style="padding:10px 0px;text-align:center;">
            <input type="checkbox" name="isVerified" id="promotionApplyAuditIndex_settleWin_isVerified" /><label for="promotionApplyAuditIndex_settleWin_isVerified">我已核实并已完成转账</label>
        </div>
    </form>
</div>
