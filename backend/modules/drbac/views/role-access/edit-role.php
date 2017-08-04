<div style="padding:10px 40px 20px 40px">
    <form id="easyui-form-drbac-role-access-edit-role" method="post">
        <input type="hidden" name="id">
    	<table cellpadding="5">
    		<tr>
    			<td>角色名称：</td>
    			<td>
    			    <input class="easyui-textbox" name="name" />    
    			</td>
    			<td></td>
    			<td></td>
    		</tr>
    		<tr>
    		    <td>备注：</td>
    			<td colspan="3">
    			    <input class="easyui-textbox" name="note" data-options="multiline:true" style="height:60px;width:376px;" />
    			</td>
    		</tr>
    	</table>
    </form>
</div>
<script>
	$('#easyui-form-drbac-role-access-edit-role').form('load',<?php echo json_encode($roleInfo); ?>);
</script>