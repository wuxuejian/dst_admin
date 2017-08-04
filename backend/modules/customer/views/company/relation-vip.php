<div style="padding:10px 40px 20px 40px">
    <form id="easyui-form-customer-company-relation-vip" class="easyui-form">
        <input type="hidden" name="id" value="<?php echo $id; ?>" />
        <table cellpadding="8" cellspacing="0">
            <tr>
                <td>
                    <select name="type" class="easyui-combobox">
                        <option value="mobile">手机号码</option>
                        <option value="code">会员编号</option>
                    </select>
                </td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:200px;"
                        name="code_val"
                        required="true"
                    />
                </td>
            </tr>
        </table>
    </form>
</div>