<div style="padding:10px;color:#333;">
    <ul style="overflow:hidden;list-style:none;padding:0;margin:0;">
        <li style="float:left;overflow:hidden;padding:3px;">
            <div style="padding:3px;width:200px;height:18px;font-weight:bold;">订单编号</div>
            <div style="padding:3px;width:200px;height:18px;"><?php echo $vcrInfo['number']; ?></div>
        </li>
        <li style="float:left;overflow:hidden;padding:3px;">
            <div style="padding:3px;width:200px;height:18px;font-weight:bold;">电卡编号</div>
            <div style="padding:3px;width:200px;height:18px;"><?php echo $vcrInfo['card_no']; ?></div>
        </li>
        <li style="float:left;overflow:hidden;padding:3px;">
            <div style="padding:3px;width:200px;height:18px;font-weight:bold;">会员手机</div>
            <div style="padding:3px;width:200px;height:18px;"><?php echo $vcrInfo['vip_mobile']; ?></div>
        </li>
        <li style="float:left;overflow:hidden;padding:3px;">
            <div style="padding:3px;width:200px;height:18px;font-weight:bold;">电桩逻辑地址</div>
            <div style="padding:3px;width:200px;height:18px;"><?php echo $vcrInfo['logic_addr']; ?></div>
        </li>
        <li style="float:left;overflow:hidden;padding:3px;">
            <div style="padding:3px;width:200px;height:18px;font-weight:bold;">请求时间</div>
            <div style="padding:3px;width:200px;height:18px;"><?php echo $vcrInfo['write_datetime']; ?></div>
        </li>
        <li style="float:left;overflow:hidden;padding:3px;">
            <div style="padding:3px;width:200px;height:18px;font-weight:bold;">启动状态</div>
            <div style="padding:3px;width:200px;height:18px;">
                <?php
                    switch($vcrInfo['start_status']){
                        case "success":
                            echo '成功';break;
                        case "fail":
                            echo '失败';break;
                        case "timeout":
                            echo '超时';break;
                        default:
                            echo $vcrInfo['start_status'];
                    }
                ?>
            </div>
        </li>
        <li style="float:left;overflow:hidden;padding:3px;">
            <div style="padding:3px;width:200px;height:18px;font-weight:bold;">失败原因</div>
            <div style="padding:3px;width:200px;height:18px;"><?php echo $vcrInfo['start_fail_reason']; ?></div>
        </li>
        <li style="float:left;overflow:hidden;padding:3px;">
            <div style="padding:3px;width:200px;height:18px;font-weight:bold;">停止时间</div>
            <div style="padding:3px;width:200px;height:18px;"><?php echo $vcrInfo['end_datetime']; ?></div>
        </li>
        <li style="float:left;overflow:hidden;padding:3px;">
            <div style="padding:3px;width:200px;height:18px;font-weight:bold;">停止状态</div>
            <div style="padding:3px;width:200px;height:18px;">
                <?php
                    switch($vcrInfo['end_status']){
                        case "success":
                            echo '成功';break;
                        case "fail":
                            echo '失败';break;
                        case "timeout":
                            echo '超时';break;
                        case "noaction":
                            echo '未操作';break;
                        default:
                            echo $vcrInfo['end_status'];
                    }
                ?>
            </div>
        </li>
        <li style="float:left;overflow:hidden;padding:3px;">
            <div style="padding:3px;width:200px;height:18px;font-weight:bold;"><span style="color:#FF8000;">消费金额（元）</span></div>
            <div style="padding:3px;width:200px;height:18px;"><?php echo $vcrInfo['money']; ?></div>
        </li>
        <li style="float:left;overflow:hidden;padding:3px;">
            <div style="padding:3px;width:200px;height:18px;font-weight:bold;">结算时间</div>
            <div style="padding:3px;width:200px;height:18px;"><?php echo $vcrInfo['count_datetime']; ?></div>
        </li>
        <!--
                <li style="float:left;overflow:hidden;padding:3px;">
                    <div style="padding:3px;width:200px;height:18px;font-weight:bold;"><span style="color:#FF8000;">消费金额（元）</span></div>
                    <div style="padding:3px;width:200px;height:18px;"><?php echo $vcrInfo['c_amount']; ?></div>
                </li>
                <li style="float:left;overflow:hidden;padding:3px;">
                    <div style="padding:3px;width:200px;height:18px;font-weight:bold;">支付状态</div>
                    <div style="padding:3px;width:200px;height:18px;">
                        <?php
                            /*switch($vcrInfo['pay_status']){
                                case "wait_pay":
                                    echo '等待支付';break;
                                case "success":
                                    echo '支付成功';break;
                                default:
                                    echo $vcrInfo['pay_status'];
                            }*/
                        ?>
                    </div>
                </li>
                <li style="float:left;overflow:hidden;padding:3px;">
                    <div style="padding:3px;width:200px;height:18px;font-weight:bold;">交易流水号</div>
                    <div style="padding:3px;width:200px;height:18px;"><?php /*echo $vcrInfo['fm_charge_no']; */?></div>
                </li>
        -->
        <li style="clear:both;padding:5px 0px;">
            <!-- <div style="margin:0;padding:0; width:100%;height:1px;background-color:#eee;overflow:hidden;"></div>-->
            <div style="margin:0;padding:5px; width:100%;background-color:#EBDFA1;"><b>对应前置机充电记录</b></div>
        </li>

        <!--前置机充电记录信息-->
        <li style="float:left;overflow:hidden;padding:3px;">
            <div style="padding:3px;width:200px;height:18px;font-weight:bold;">交易流水号</div>
            <div style="padding:3px;width:200px;height:18px;"><?php echo isset($fmRecord['DEAL_NO']) ? $fmRecord['DEAL_NO'] : ''; ?></div>
        </li>
        <li style="float:left;overflow:hidden;padding:3px;">
            <div style="padding:3px;width:200px;height:18px;font-weight:bold;">电卡编号</div>
            <div style="padding:3px;width:200px;height:18px;"><?php echo isset($fmRecord['START_CARD_NO']) ? $fmRecord['START_CARD_NO'] : ''; ?></div>
        </li>
        <li style="float:left;overflow:hidden;padding:3px;">
            <div style="padding:3px;width:200px;height:18px;font-weight:bold;">电站名称</div>
            <div style="padding:3px;width:200px;height:18px;"><?php echo isset($fmRecord['cs_name']) ? $fmRecord['cs_name'] : ''; ?></div>
        </li>
        <li style="float:left;overflow:hidden;padding:3px;">
            <div style="padding:3px;width:200px;height:18px;font-weight:bold;">设备地址</div>
            <div style="padding:3px;width:200px;height:18px;"><?php echo isset($fmRecord['DEV_ADDR']) ? $fmRecord['DEV_ADDR'] : ''; ?></div>
        </li>
        <li style="float:left;overflow:hidden;padding:3px;">
            <div style="padding:3px;width:200px;height:18px;font-weight:bold;">充电状态</div>
            <div style="padding:3px;width:200px;height:18px;">
                <?php
                    $dealType = isset($fmRecord['DEAL_TYPE']) ? $fmRecord['DEAL_TYPE'] : '';
                    if($dealType != ''){
                        switch($dealType){
                            case 0:
                                echo '正在充电';break;
                            case 1:
                                echo '结束正常';break;
                            case 2:
                                echo '结束异常';break;
                            default:
                                echo $fmRecord['DEAL_TYPE'];
                        }
                    }
                ?>
            </div>
        </li>
        <li style="float:left;overflow:hidden;padding:3px;">
            <div style="padding:3px;width:200px;height:18px;font-weight:bold;">开始电量（度）</div>
            <div style="padding:3px;width:200px;height:18px;"><?php echo isset($fmRecord['START_DEAL_DL']) ? $fmRecord['START_DEAL_DL'] : ''; ?></div>
        </li>
        <li style="float:left;overflow:hidden;padding:3px;">
            <div style="padding:3px;width:200px;height:18px;font-weight:bold;">结束电量（度）</div>
            <div style="padding:3px;width:200px;height:18px;"><?php echo isset($fmRecord['END_DEAL_DL']) ? $fmRecord['END_DEAL_DL'] : ''; ?></div>
        </li>
        <li style="float:left;overflow:hidden;padding:3px;">
            <div style="padding:3px;width:200px;height:18px;font-weight:bold;"><span style="color:#FF8000;">消费电量（度）</span></div>
            <div style="padding:3px;width:200px;height:18px;"><?php echo isset($fmRecord['consume_DL']) ? $fmRecord['consume_DL'] : ''; ?></div>
        </li>
        <li style="float:left;overflow:hidden;padding:3px;">
            <div style="padding:3px;width:200px;height:18px;font-weight:bold;">交易前余额（元）</div>
            <div style="padding:3px;width:200px;height:18px;"><?php echo isset($fmRecord['REMAIN_BEFORE_DEAL']) ? $fmRecord['REMAIN_BEFORE_DEAL'] : ''; ?></div>
        </li>
        <li style="float:left;overflow:hidden;padding:3px;">
            <div style="padding:3px;width:200px;height:18px;font-weight:bold;">交易后余额（元）</div>
            <div style="padding:3px;width:200px;height:18px;"><?php echo isset($fmRecord['REMAIN_AFTER_DEAL']) ? $fmRecord['REMAIN_AFTER_DEAL'] : ''; ?></div>
        </li>
        <li style="float:left;overflow:hidden;padding:3px;">
            <div style="padding:3px;width:200px;height:18px;font-weight:bold;"><span style="color:#FF8000;">消费金额（元）</span></div>
            <div style="padding:3px;width:200px;height:18px;"><?php echo isset($fmRecord['consume_money']) ? $fmRecord['consume_money'] : ''; ?></div>
        </li>
        <li style="float:left;overflow:hidden;padding:3px;">
            <div style="padding:3px;width:200px;height:18px;font-weight:bold;">交易开始时间</div>
            <div style="padding:3px;width:200px;height:18px;"><?php echo isset($fmRecord['DEAL_START_DATE']) ? $fmRecord['DEAL_START_DATE'] : ''; ?></div>
        </li>
        <li style="float:left;overflow:hidden;padding:3px;">
            <div style="padding:3px;width:200px;height:18px;font-weight:bold;">交易结束时间</div>
            <div style="padding:3px;width:200px;height:18px;"><?php echo isset($fmRecord['DEAL_END_DATE']) ? $fmRecord['DEAL_END_DATE'] : ''; ?></div>
        </li>
    </ul>
</div>