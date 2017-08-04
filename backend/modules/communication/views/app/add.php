<form id="easyui-form-communication-app-add" class="easyui-form" method="post">
    <br />
    <ul class="ulforform-resizeable">
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">应用名称</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    style="width:180px;"
                    name="app_name"
                    validType="length[255]"
                />
            </div>
        </li>
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">应用目录</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    style="width:180px;"
                    name="app_path"
                    validType="length[255]"
                />
            </div>
        </li>
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">应用地址</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    style="width:180px;"
                    name="app_addr"
                    validType="length[255]"
                />
            </div>
        </li>
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">应用地址</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    style="width:180px;"
                    name="app_addr"
                    validType="length[255]"
                />
            </div>
        </li>
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">登陆密码</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    style="width:180px;"
                    name="password"
                    required="true"
                    validType="length[20]"
                />
            </div>
        </li>
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">备注</div>
            <div class="ulforform-resizeable-input">
                <input 
                    class="easyui-textbox"
                    name="note"
                    data-options="multiline:true"
                    style="height:60px;width:600px;"
                    validType="length[255]"
                />
            </div>
        </li>
    </ul>
</form>