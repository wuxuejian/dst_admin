<div class="easyui-panel" data-options="fit:true,border:false" style="padding:10px;">
    <form id="CustomerCompanyIndex_setPasswordWin_form">
        <table cellspacing="0" cellpadding="5"  align="center" border="0">
            <tr>
                <td>密码：</td>
                <td>
                    <input
                        id="CustomerCompanyIndex_setPasswordWin_form_password"
                        class="easyui-textbox"
                        type="password"
                        name="password"
                        validType="match[/^\w{6,16}$/]"
                        invalidMessage="6到16位(数字/字母/_)！"
						required="true"
						style="width:190px;height:24px;"
                    />
                </td>
            </tr>
            <tr>
                <td>确认密码：</td>
                <td>
                    <input
                        class="easyui-textbox"
                        type="password"
                        validType="equals['#CustomerCompanyIndex_setPasswordWin_form_password']"
                        invalidMessage="两次密码不一致！"
						required="true"
						style="width:190px;height:24px;"
                    />
                </td>
            </tr>
            <tr hidden>
                <td>客户ID：</td>
                <td>
                    <input
                        class="easyui-textbox"
                        name="id"
                        value="<?php echo $customerId; ?>"
                        editable="false"
                    />
                </td>
            </tr>
        </table>
    </form>

</div>