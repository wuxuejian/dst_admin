<div style="padding:10px 40px 20px 40px">
    <form id="easyui-form-drbac-user-reset-password" class="easyui-form" method="post">
        <input type="hidden" name="id" value="<?php echo $id; ?>" />
        <table cellpadding="5" width="100%" border="0">
            <tr>
                <td>密码：</td>
                <td>
                    <input 
                        id="easyui-validatebox-drbac-user-reset-password-password"
                        class="easyui-textbox"
                        name="password"
                        type="password"
                        required="true"
                        validType="match[/^\w{6,20}$/]"
                        missingMessage="请输入密码"
                        invalidMessage="密码只能是6到20位的数字、字母或下划线"
                    />    
                </td>
                <td>密码重复：</td>
                <td>
                    <input
                        class="easyui-textbox"
                        type="password"
                        name="repassword"
                        required="true"
                        missingMessage='请再次输入登陆密码'
                        validType="equals['#easyui-validatebox-drbac-user-reset-password-password']"
                        invalidMessage="两次密码不一致"
                    /> 
                </td>
            </tr>
        </table>
    </form>
</div>