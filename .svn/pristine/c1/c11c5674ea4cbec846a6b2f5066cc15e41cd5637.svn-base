<div style="padding:10px 40px 20px 40px">
    <form id="easyui-form-process-config-edit" class="easyui-form" method="post">
        <input type="hidden" name="id" />
        <table cellpadding="5">
            <tr>
                <td>名称：</td>
                <td>
                    <input  class="easyui-textbox" name="name" required="true" missingMessage="请输入部门名称"/>
                </td>
            </tr>
            <tr>
                <td>对应业务：</td>
                <td>
                    <select class="easyui-combobox"  name="by_business" required="true"   missingMessage="请选择对应业务">
	                    <?php if($business):?>
	                    	<?php foreach ($business as $key=>$busines):?>
	                    		<option value="<?php echo $key?>"><?php echo $busines?></option>
	                    	<?php endforeach;?>
	                    <?php endif;?>
                    </select>
                </td>
            </tr>
        </table>
    </form>
</div>
<script>
    $('#easyui-form-process-config-edit').form('load',<?= json_encode($result); ?>);
</script>