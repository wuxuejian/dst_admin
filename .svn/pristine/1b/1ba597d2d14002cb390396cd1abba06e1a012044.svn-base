<div class="easyui-tabs" data-options="fit:true,border:false"> 
<div title="车辆保养详情" style="padding:15px">
<table cellpadding="6" cellspacing="0" align="center"  width="100%" border="0">
	<tr>
		<td align="right" width="13%">车牌号：</td>
		<td width="20%"><?php echo $result['plate_number'];?></td>
		<td align="right" width="13%">进修理厂日期、时间：</td>
		<td width="20%"><?php echo $result['add_time'];?></td>
	</tr>
	<tr>
		
		<td align="right" width="13%">保养类别：</td>
		<td width="20%"><?php echo $result['maintain_type'];?></td>
		<td align="right" width="13%">出修理厂日期、时间：</td>
		<td width="20%"><?php echo $result['out_time'];?></td>
	</tr>
	<tr>
		<td align="right" width="13%">保养公里数：</td>
		<td width="20%"><?php echo $result['driving_mileage']?></td>
		<td align="right" width="13%">保养维修厂：</td>
		<td width="20%"><?php echo $result['site_name']?></td>
	</tr>	
	
	<tr>
		<td align="right" width="">故障照片：</td>
		<td colspan="6">
		<table border="0"><tr><td>
		<a href="<?php echo !empty($result['in_car_img']) ? $result['in_car_img'] : 'javascript:void(0)';?>" target="_blank"><img  src="<?php echo $result['in_car_img']?>" width="100" height="100" title="进维修厂收车单"  alt="没有上传" /></a>
		<span>进厂收车单</span>
		&nbsp;<a href="<?php echo !empty($result['out_car_img']) ? $result['out_car_img']: 'javascript:void(0)';?>" target="_blank"><img  src="<?php echo $result['out_car_img']?>" width="100" height="100" title="出场保养结果单据"  alt="没有上传" /></a>
		<span>出场保养结果单据</span>
		&nbsp;<a href="<?php echo !empty($result['maintain_img']) ? $result['maintain_img']: 'javascript:void(0)';?>" target="_blank"><img src="<?php echo $result['maintain_img']?>" width="100" height="100" title="保修手册凭证" alt="没有上传"   /></a>
		<span>保修手册凭证</span>
		</td></tr></table></td>
	</tr>	
	</table>
</div>


	</div>