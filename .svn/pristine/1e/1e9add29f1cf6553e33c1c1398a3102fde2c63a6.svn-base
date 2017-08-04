<form id="easyui-form-process-repair-maintain-ajax-indicator-light-from" class="easyui-form" method="post">
<table cellpadding="6" cellspacing="0" align="center"  width="100%" border="0">
<?php if($result):?>
<?php foreach ($result as $k=>$val):?>
	<div style="width:25%; height:100px;float:left">
		<img  class="repairImg" src="<?php echo $val['image_url'];?>"  style="width:60px;height:60px;  border:1px solid #ccc;" />
		<div><input type="checkbox"   name="indicator_light[]" value="<?php echo $val['id']?>" /><?php echo $val['name']?></div>
	</div>
<?php endforeach;?>
<?php else:?>
<tr><td colspan="4">没有数据</td></tr>
<?php endif;?>
</table>
</form>