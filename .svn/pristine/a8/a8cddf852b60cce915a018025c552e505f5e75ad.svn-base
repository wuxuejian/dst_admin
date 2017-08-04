<div style="padding:10px 40px 20px 40px">
    <form id="easyui-form-system-config-category-add" method="post">
    	<table cellpadding="5">
    		<tr>
    			<td>父级配置:</td>
    			<td>
    			    <select class="easyui-combobox" name="parent_id" style="width:153px;">  
                        <option value="0">作为父级配置</option>  
                        <?php foreach($topCategory as $val){ ?>
                        <option value="<?php echo $val['id'] ?>"><?php echo $val['title']; ?></option> 
                        <?php } ?> 
                    </select>     
    			</td>
    			<td>分类名称:</td>
    			<td><input class="easyui-textbox" name="title"></input></td>
    		</tr>
    		<tr>
    			<td>分类键名:</td>
    			<td>
    			    <input class="easyui-textbox" name="key"></input>
    			</td>
    			<td>排序号:</td>
    			<td><input class="easyui-textbox" name="list_order"></input></td>
    		</tr>
    	</table>
    </form>
</div>