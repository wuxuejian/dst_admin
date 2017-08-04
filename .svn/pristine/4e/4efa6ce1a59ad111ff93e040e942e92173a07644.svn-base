<form
    id="easyui-form-charge-frontmachine-edit"
    class="easyui-form"
    style="padding:10px;"
>
    <input type="hidden" name="id" />
    <ul class="ulforform-resizeable">
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">地址</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    style="width:160px;"
                    name="addr"
                    required="true"
                    validType="length[20]"
                />
            </div>
        </li>
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">端口</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    style="width:160px;"
                    name="port"
                    required="true"
                    validType="int"
                >
            </div>
        </li>
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">权限等级</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    style="width:160px;"
                    name="access_level"
                    required="true"
                    validType="length[100]"
                >
            </div>
        </li>
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">密码</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    style="width:160px;"
                    name="password"
                    required="true"
                    validType="length[50]"
                >
            </div>
        </li>
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">寄存器编号</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    style="width:160px;"
                    name="register_number"
                    required="true"
                    validType="int"
                />
            </div>
        </li>

        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title"></div>
            <div class="ulforform-resizeable-input"></div>
        </li>
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">数据库用户名</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    style="width:160px;"
                    name="db_username"
                    required="true"
                    />
            </div>
        </li>
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">数据库密码</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    style="width:160px;"
                    name="db_password"
                    required="true"
                    />
            </div>
        </li>
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">数据库端口</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    style="width:160px;"
                    name="db_port"
                    required="true"
                    />
            </div>
        </li>
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">数据库名称</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    style="width:160px;"
                    name="db_name"
                    required="true"
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
                    validType="length[255]"
                    style="height:60px;width:460px;"
                />
            </div>
        </li>
    </ul>
</form>
<script>
    $('#easyui-form-charge-frontmachine-edit').form('load',<?= json_encode($oldData); ?>)
</script>