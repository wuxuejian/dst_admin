    <div class="easyui-panel" title="" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">
        <form id="vipVipRechargeRecordIndex_exceptionHandleWin_form" style="padding:5px;">
            <input type="hidden" name="id" />
            <ul class="ulforform-resizeable">
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">充值单号</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-textbox" name="trade_no" style="width:100%;" disabled="true" />
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">充值金额</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-numberbox" name="total_fee" style="width:100%;" precision="2" required="true" min="0" disabled="true"  />
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">支付平台交易号</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-textbox" name="platform_trade_no" style="width:100%;" required="true" />
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">支付时间</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-datetimebox" name="gmt_payment_datetime" style="width:100%;" required="true" value="<?php echo date('Y-m-d H:i:s'); ?>"  />
                    </div>
                </li>
            </ul>
        </form>
    </div>

<script>
    //--表单赋值------------
    $('#vipVipRechargeRecordIndex_exceptionHandleWin_form').form('load',<?php echo json_encode($recInfo); ?>);
</script>
