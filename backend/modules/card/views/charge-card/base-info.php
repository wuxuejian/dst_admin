<div style="padding:10px;color:#333;">
    <ul style="overflow:hidden;list-style:none;padding:0;margin:0;">
        <li style="float:left;overflow:hidden;padding:4px;">
            <div style="padding:4px;width:200px;font-weight:bold;">电卡编号</div>
            <div style="padding:4px;width:200px;"><?php echo $cardInfo['cc_code']; ?></div>
        </li>
        <li style="float:left;overflow:hidden;padding:4px;">
            <div style="padding:4px;width:200px;font-weight:bold;">电卡类型</div>
            <div style="padding:4px;width:200px;"><?php echo $cardInfo['cc_type']; ?></div>
        </li>
        <li style="float:left;overflow:hidden;padding:4px;">
            <div style="padding:4px;width:200px;font-weight:bold;">电卡状态</div>
            <div style="padding:4px;width:200px;"><?php echo $cardInfo['cc_status']; ?></div>
        </li>
        <li style="float:left;overflow:hidden;padding:4px;">
            <div style="padding:4px;width:200px;font-weight:bold;">会员编号</div>
            <div style="padding:4px;width:200px;"><?php echo $cardInfo['cc_holder_code']; ?></div>
        </li>
        <li style="float:left;overflow:hidden;padding:4px;">
            <div style="padding:4px;width:200px;font-weight:bold;">制卡日期</div>
            <div style="padding:4px;width:200px;"><?php echo $cardInfo['cc_start_date']; ?></div>
        </li>
        <li style="float:left;overflow:hidden;padding:4px;">
            <div style="padding:4px;width:200px;font-weight:bold;">有效日期</div>
            <div style="padding:4px;width:200px;"><?php echo $cardInfo['cc_end_date']; ?></div>
        </li>
        <li style="float:left;overflow:hidden;padding:4px;">
            <div style="padding:4px;width:200px;font-weight:bold;">当前余额</div>
            <div style="padding:4px;width:200px;"><?php echo $cardInfo['cc_current_money']; ?> 元</div>
        </li>
        <li style="float:left;overflow:hidden;padding:4px;">
            <div style="padding:4px;width:200px;font-weight:bold;">充值次数</div>
            <div style="padding:4px;width:200px;"><?php echo $cardInfo['recharge_times']; ?></div>
        </li>
        <li style="float:left;overflow:hidden;padding:4px;">
            <div style="padding:4px;width:200px;font-weight:bold;">消费次数</div>
            <div style="padding:4px;width:200px;"><?php echo $cardInfo['consume_num']; ?></div>
        </li>
        <li style="float:left;overflow:hidden;padding:4px;">
            <div style="padding:4px;width:200px;font-weight:bold;">最后充电时间</div>
            <div style="padding:4px;width:200px;"><?php echo $cardInfo['lastChargeDateTime']; ?></div>
        </li>
        <li style="clear:both;overflow:hidden;padding:4px;">
            <div style="padding:4px;font-weight:bold;">备注</div>
            <div style="padding:4px;"><?php echo $cardInfo['cc_mark']; ?></div>
        </li>
    </ul>
</div>