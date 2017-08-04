<form id="vip_vip_reset_pwd" style="padding-top:10px;">
    <input type="hidden" name="id" value="<?= $id; ?>" />
    <ul class="ulforform-resizeable">
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">新密码</div>
            <div class="ulforform-resizeable-input">
                <input
                    id="vip_vip_reset_pwd_password"
                    type="password"
                    class="easyui-textbox"
                    name="password"
                    style="width:100%"
                    required="true"
                    validType="match[/^\w{6,16}$/]"
                    invalidMessage="请输入数字、字母或_，长度6-16位！"
                />
            </div>
        </li>
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">密码重复</div>
            <div class="ulforform-resizeable-input">
                <input
                    type="password"
                    class="easyui-textbox"
                    name="repas"
                    style="width:100%"
                    required="true"
                    validType="equals['#vip_vip_reset_pwd_password']"
                    invalidMessage="两次密码不一致！"
                />
            </div>
        </li>
    </ul>
</form>