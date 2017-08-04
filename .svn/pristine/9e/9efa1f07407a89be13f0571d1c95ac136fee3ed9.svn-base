<div style="padding:10px 40px 20px 40px">
    <form id="easyui-form-drbac-mca-add-action-btn" method="post">
        <input type="hidden" name="action_id" value="<?= $actionId; ?>" />
    	<table cellpadding="5">
    		<tr>
    			<td>文本内容：</td>
    			<td>
    			    <input
                        class="easyui-textbox"
                        name="text"
                        required="true"
                        missingMessage="请输入文本内容"
                    />    
    			</td>
    			<td>图标样式：</td>
    			<td>
    			    <input class="easyui-textbox" name="icon" />
    			</td>
    		</tr>
            <tr>
                <td>目标mca：</td>
                <td colspan="3">
                    <input
                        class="easyui-textbox"
                        name="target_mca_code"
                        validType="match[/^([a-z]|-)+\/([a-z]|-)+\/([a-z]|-)+$/]"
                        invalidMessage="请按照格式：模块/控制器/方法"
                    />
                </td>
            </tr>
    		<tr>
    			<td>点击执行脚本：</td>
    			<td>
    			    <input
                        class="easyui-textbox"
                        name="on_click"
                        required="true"
                        missingMessage="请输入点击执行脚本"
                    />
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