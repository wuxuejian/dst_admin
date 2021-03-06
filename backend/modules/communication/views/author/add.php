<form id="easyui-form-communication-author-add" class="easyui-form" method="post">
    <br />
    <ul class="ulforform-resizeable">
        <li class="ulforform-resizeable-group-single">
            <div class="ulforform-resizeable-title">公司名称</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    style="width:600px;"
                    name="company_name"
                    validType="length[255]"
                />
            </div>
        </li>
        <li class="ulforform-resizeable-group-single">
            <div class="ulforform-resizeable-title">登陆密码</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    style="width:600px;"
                    name="password"
                    required="true"
                    validType="length[20]"
                />
            </div>
        </li>
        <li class="ulforform-resizeable-group-single">
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