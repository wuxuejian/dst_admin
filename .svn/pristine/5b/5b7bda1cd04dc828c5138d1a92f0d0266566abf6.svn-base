<div style="padding:10px 40px 20px 40px">
    <form id="easyui-form-system-config-item-edit" method="post">
    	<table cellpadding="5">
    		<tr>
    			<td>文本内容:</td>
    			<td>
    			    <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
    			    <input class="easyui-textbox" name="text"></input>
    			</td>
    			<td>对应值:</td>
    			<td><input class="easyui-textbox" name="value"></input></td>
    		</tr>
    		<tr>
    			<td>排序号:</td>
    			<td><input class="easyui-textbox" name="list_order"></input></td>
    			<td</td>
    			<td></td>
    		</tr>
    		<tr>
    			<td>备注:</td>
    			<td colspan='3'>
    			   <input class="easyui-textbox" name="note" data-options="multiline:true" style="width:368px;height:50px;"></input> 
    			</td>
    		</tr>
    	</table>
    </form>
</div>
<script>
	$('#easyui-form-system-config-item-edit').form('load',<?php echo json_encode($item); ?>);
</script>