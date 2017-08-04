<div style="padding:10px 40px 20px 40px">
    <form id="easyui-form-process-config-edit-event" class="easyui-form" method="post">
        <table cellpadding="5">
        <input type="hidden" name="id" />
            <tr>
                <td> 名称：</td>
                <td>
                    <input  class="easyui-textbox" name="name" required="true" missingMessage="请输入名称"/>
                </td>
            </tr>
            <tr>
                <td> URL路由：</td>
                <td>
                    <input  class="easyui-textbox" name="action" required="true" missingMessage="请输入URL路由"/>
                </td>
            </tr>
            <tr>
                <td> JS对象：</td>
                <td>
                    <input  class="easyui-textbox" name="js_object" required="true" missingMessage="请输入JS对象"/>
                </td>
            </tr>
            <tr>
                <td> JS方法：</td>
                <td>
                    <input  class="easyui-textbox" name="js_function" required="true" missingMessage="请输入JS路由"/>
                </td>
            </tr>
            <tr>
                <td> 类型：</td>
                <td>
                    <select class="easyui-combobox"  name="type" required="true" >
	                    <option value="1">指定事件</option>
	                    <option value="2">对应业务</option>
                    </select>
                </td>
            </tr>
            
        </table>
    </form>
</div>
<script>
$('#easyui-form-process-config-edit-event').form('load',<?= json_encode($result); ?>);
</script>