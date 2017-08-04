<form id="SystemDaemonIndex_addWin_form" method="post" style="padding:5px;">
    <ul class="ulforform-resizeable">
        <li class="ulforform-resizeable-group-single">
            <div class="ulforform-resizeable-title">进程名称</div>
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
            <div class="ulforform-resizeable-title">脚本位置</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    name="script_path"
                    style="width:510px;"
                    required="true"
                    validType="length[255]"
                />
            </div>
        </li>
        <li class="ulforform-resizeable-group-single">
            <div class="ulforform-resizeable-title">任务描述</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    name="description"
                    style="width:510px;height:60px;"
                    multiline="true"
                    validType="length[255]"
                />
            </div>
        </li>
    </ul>
</form>