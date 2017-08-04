<form id="SystemTaskIndex_addWin_form" method="post" style="padding:5px;">
    <ul class="ulforform-resizeable">
        <li class="ulforform-resizeable-group-single">
            <div class="ulforform-resizeable-title">任务名称</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    name="name"
                    style="width:510px;"
                    required="true"
                    validType="length[255]"
                />
            </div>
        </li>
        <li class="ulforform-resizeable-group-single">
            <div class="ulforform-resizeable-title">执行命令</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    name="exec_command"
                    style="width:510px;"
                    required="true"
                    validType="length[255]"
                />
            </div>
        </li>
        <li class="ulforform-resizeable-group-single">
            <div class="ulforform-resizeable-title">执行频率</div>
            <div class="ulforform-resizeable-input">
                <select
                    class="easyui-combobox"
                    name="exec_frequency"
                    style="width:510px;"
                    required="true"
                    data-options="panelHeight:'auto',required:true" >
                    <?php foreach($config['exec_frequency'] as $item){ ?>
                    <option value="<?php echo $item['value']; ?>"><?php echo $item['text']; ?></option>
                    <?php } ?>
                </select>
            </div>
        </li>
        <li class="ulforform-resizeable-group-single">
            <div class="ulforform-resizeable-title">是否启用</div>
            <div class="ulforform-resizeable-input">
                <select
                    class="easyui-combobox"
                    name="in_use"
                    style="width:510px;"
                    data-options="panelHeight:'auto',required:true,editable: false" >
                    <option value="0">否</option>
                    <option value="1">是</option>
                </select>
            </div>
        </li>
    </ul>
</form>