<form id="carmonitorBatteryAlertIndex_processWin_form" style="padding:5px;">
    <input type="hidden" name="id" />
    <ul class="ulforform-resizeable">
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">处理时间</div>
            <div class="ulforform-resizeable-input">
                <input class="easyui-datetimebox" name="process_time" style="width:100%;" validType="datetime"  />
            </div>
        </li>
        <li class="ulforform-resizeable-group-single">
            <div class="ulforform-resizeable-title">处理方法</div>
            <div class="ulforform-resizeable-input" style="width:80%;">
                <input class="easyui-textbox" name="process_way" style="width:100%;height:80px;"
                       data-options="multiline:true"
                    />
            </div>
        </li>
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">处理状态</div>
            <div class="ulforform-resizeable-input">
                <select class="easyui-combobox" name="process_status" style="width:100%;" data-options="panelHeight:'auto',required:true,editable:false">
                    <option value="PROCESSED">已处理</option>
                    <option value="UNPROCESSED">未处理</option>
                    <option value="WAITFOLLOW">待跟进</option>
                </select>
            </div>
        </li>
    </ul>
</form>
<script>
    var recInfo = <?php echo json_encode($recInfo); ?>;
    $('#carmonitorBatteryAlertIndex_processWin_form').form('load',recInfo);
</script>
