<form id="easyui_form_carmonitor_exception_control_shot_message_rule" style="padding:10px;">
    <ul class="ulforform-resizeable">
        <li class="ulforform-resizeable-group-single">
            <div class="ulforform-resizeable-title">工作日推送时间</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-timespinner"
                    name="wd_start_time"
                    style="width:100px;"
                    value="00:00"
                    required="true"
                    validType="match[/^\d{2}:\d{2}$/]"
                />
                至
                <input
                    class="easyui-timespinner"
                    name="wd_end_time"
                    style="width:100px;"
                    value="23:59"
                    required="true"
                    validType="match[/^\d{2}:\d{2}$/]"
                />
            </div>
        </li>
        <li class="ulforform-resizeable-group-single">
            <div class="ulforform-resizeable-title">报警信息接收人</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    name="wd_mobile"
                    style="width:225px;height:100px;"
                    data-options="multiline:true"
                    prompt="多个手机号以'|'分隔"
                />
            </div>
        </li>
        <li class="ulforform-resizeable-group-single">
            <div class="ulforform-resizeable-title">节假日推送时间</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-timespinner"
                    name="hd_start_time"
                    style="width:100px;"
                    value="00:00"
                    required="true"
                    validType="match[/^\d{2}:\d{2}$/]"
                />
                至
                <input
                    class="easyui-timespinner"
                    name="hd_end_time"
                    style="width:100px;"
                    value="23:59"
                    required="true"
                    validType="match[/^\d{2}:\d{2}$/]"
                />
            </div>
        </li>
        <li class="ulforform-resizeable-group-single">
            <div class="ulforform-resizeable-title">报警信息接收人</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    name="hd_mobile"
                    style="width:225px;height:100px;"
                    data-options="multiline:true"
                    prompt="多个手机号以'|'分隔"
                />
            </div>
        </li>
    </ul>
</form>
<script type="text/javascript">
    var CarmonitorExceptionControlAlertShotMessageRule = {
        params: {
            oldData: <?php echo json_encode($data); ?>
        },
        init: function(){
            $('#easyui_form_carmonitor_exception_control_shot_message_rule').form('load',this.params.oldData);
        }
    };
    CarmonitorExceptionControlAlertShotMessageRule.init();
</script>