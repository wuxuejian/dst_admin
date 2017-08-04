<div style="padding:10px 40px 20px 40px">
    <form id="easyui-form-drbac-mca-edit-controller" method="post">
        <input type="hidden" name="id" />
    	<table cellpadding="5">
    		<tr>
    			<td>中文名称：</td>
    			<td>
    			    <input class="easyui-textbox" name="name" />    
    			</td>
    			<td>控制器代码：</td>
    			<td>
    			    <input class="easyui-textbox" name="controller_code" />
    			</td>
    		</tr>
    		<tr>
    			<td>作为菜单：</td>
    			<td>
    			    <select class="easyui-combobox" name="is_menu" style="width:153px;">  
                        <option value="0">否</option>  
                        <option value="1">是</option>  
                    </select> 
    			</td>
    			<td>排序号：</td>
    			<td>
    			    <input class="easyui-textbox"  name="list_order" />
    			</td>
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
	$('#easyui-form-drbac-mca-edit-controller').form('load',<?php echo json_encode($controllerInfo); ?>);
</script>