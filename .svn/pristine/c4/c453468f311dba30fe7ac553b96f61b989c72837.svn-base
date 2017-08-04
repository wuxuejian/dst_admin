<div style="padding:10px 40px 20px 40px">
    <form id="easyui-form-process-config-add-step" class="easyui-form" method="post">
        <table cellpadding="5">
        <input type="hidden" name="template_id" value="<?php echo $template_id;?>" />
            <tr>
                <td> 指定审批角色：</td>
                <td>
                    <select class="easyui-combobox"  name="assign_role_id"  required="true"  >
	                    <?php if($roles):?>
	                    	<?php foreach ($roles as $role):?>
	                    		<option value="<?php echo $role->id?>"><?php echo $role->name;?></option>
	                    	<?php endforeach;?>
	                    <?php endif;?>
                    </select>
                </td>
            </tr>
            <tr>
                <td> 动作：</td>
                <td>
                    <select class="easyui-combobox"  name="is_approval_action" id="is_approval_action"  required="true" >
	                    <option value="1">审批</option>
	                    <option value="2">指定事件</option>
                    </select>
                </td>
            </tr>
            <tr id="event"  style="display:none;">
                <td> 事件：</td>
                <td>
                    <select class="easyui-combobox"  name="is_event_action"   missingMessage="请选择执行事件">
	                    <?php if($events):?>
	                    	<?php foreach ($events as $key=>$event):?>
	                    		<option value="<?php echo $key?>"><?php echo $event?></option>
	                    	<?php endforeach;?>
	                    <?php endif;?>
                    </select>
                    
                </td>
            </tr>
            <tr>
                <td> 终止审批：</td>
                <td>
                    <select class="easyui-combobox"  name="is_cancel" required="true" >
	                    <option value="0">不可以</option>
	                    <option value="1">可以</option>
                    </select>
                 </td>
            </tr>
            <tr>
                <td> 倒计时(天)：</td>
                <td>
                    <input  class="easyui-textbox" name="count_down" required="true" missingMessage="请输入步骤截止倒计时"/>
                </td>
            </tr>
            <tr>
                <td> 顺序(升序)：</td>
                <td>
                    <input  class="easyui-textbox" name="sort" required="true" missingMessage="请输入步骤顺序"/>
                </td>
            </tr>
            
        </table>
    </form>
</div>
<script>
$(function(){
	 $('#is_approval_action').combobox({
		    onChange:function(newValue,oldValue){
		        select_type(newValue);
		    }
	});
		select_type($("#is_approval_action").val());
})



function select_type(newValue){
	if(newValue == 2)
	{
		$("#event").css('display','table-row');
	}else{
		$("#event").css('display','none');
	}
}

</script>