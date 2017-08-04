<table cellpadding="6" cellspacing="0" align="center"  width="100%" border="0">
<?php if(!empty($row['id'])):?>
	<tr>
		<td align="right" width="13%">车牌号：</td>
		<td width="20%"><?php echo $row['car_no'];?></td>
		<td align="right" width="13%">抵达现场时间：</td>
		<td width="20%"><?php echo !empty($row['arrive_time']) ? date('Y-m-d H:i',$row['arrive_time']):'';?></td>
	</tr>
	<tr>
		<td align="right" width="13%">现场故障描述：</td>
		<td width="20%" colspan="3"><?php echo $row['scene_desc']?></td>
	</tr>
	<tr>
		<td align="right" width="13%">现场处理结果：</td>
		<td width="20%" colspan="3"><?php echo $row['scene_result']?></td>
	</tr>
		<tr>
		<td align="right" width="13%">是否进厂维修：</td>
		<td width="20%"><?php echo !empty($row['is_go_scene']) ? '是':'否';?></td>
		<td align="right" width="13%">维修场站：</td>
		<td width="20%"><?php echo $row['maintain_scene']?></td>
	</tr>
	<tr>
		<td align="right" width="13%">维修方联系人：</td>
		<td width="20%"><?php echo $row['maintain_name']?></td>
		<td align="right" width="13%">联系电话：</td>
		<td width="20%"><?php echo $row['maintain_tel']?></td>
	</tr>
	<tr>
		<td align="right" width="13%">进厂维修单号：</td>
		<td width="20%"><?php echo $row['maintain_no']?></td>
		<td align="right" width="13%">预计完结时间：</td>
		<td width="20%"><?php echo !empty($row['expect_time']) ? date('Y-m-d H:i',$row['expect_time']):'';?></td>
	</tr>

	<tr>
		<td align="right" width="13%">是否替换车辆：</td>
		<td width="20%"><?php echo !empty($row['replace_car']) ? '是':'否';?></td>
		<td align="right" width="13%">替换车：</td>
		<td width="20%"><?php echo $row['replace_car']?></td>
	</tr>
	<tr>
		<td align="right" width="13%">替换开始时间：</td>
		<td width="20%"><?php echo !empty($row['replace_start_time']) ? date('Y-m-d H:i',$row['replace_start_time']):''?></td>
		<td align="right" width="13%">预计归还时间：</td>
		<td width="20%"><?php echo !empty($row['replace_end_time']) ? date('Y-m-d H:i',$row['replace_end_time']):''?></td>
	</tr>
	<tr>
		<td align="right" width="13%">外勤过路费：</td>
		<td width="20%"><?php echo $row['field_tolls']?></td>
		<td align="right" width="13%">外勤停车费：</td>
		<td width="20%"><?php echo $row['parking']?></td>
	</tr>

	
	<tr>
		<td align="right" width="13%">故障照片：</td>
		<td><a href="<?php echo !empty($row['car_no_img']) ? $row['car_no_img'] : 'javascript:void(0)';?>" target="_blank"><img  src="<?php echo $row['car_no_img']?>" width="100" height="100" title="车牌照片"  alt="没有上传" /></a></td>
		<td><a href="<?php echo !empty($row['dashboard_img']) ? $row['dashboard_img']: 'javascript:void(0)';?>" target="_blank"><img  src="<?php echo $row['dashboard_img']?>" width="100" height="100" title="车辆仪表"  alt="没有上传" /></a></td>
		<td><a href="<?php echo !empty($row['fault_scene_img']) ? $row['fault_scene_img']: 'javascript:void(0)';?>" target="_blank"><img src="<?php echo $row['fault_scene_img']?>" width="100" height="100" title="故障现场" alt="没有上传"   /></a></td>
	</tr>
	<tr>
		<td></td>
		<td><a href="<?php echo !empty($row['fault_location_img']) ? $row['fault_location_img'] : 'javascript:void(0)';?>" target="_blank"><img class="repairImg" src="<?php echo $row['fault_location_img']?>" width="100" height="100" title="故障位置" alt="没有上传" /></a></td>
		<td><a href="<?php echo !empty($row['field_record_img']) ? $row['field_record_img'] :'javascript:void(0)';?>" target="_blank"><img class="repairImg" src="<?php echo $row['field_record_img']?>" width="100" height="100" title="外勤服务记录表" alt="没有上传"  /></a></td>
		<td><a href="<?php echo !empty($row['maintain_jieche_img']) ? $row['maintain_jieche_img'] :'javascript:void(0)';?>" target="_blank"><img class="repairImg" src="<?php echo $row['maintain_jieche_img']?>" width="100" height="100" title="维修站点接车单" alt="没有上传"  /></a></td>
	</tr>
	<?php else:?>
	<tr><td>没有登记,暂无详情</td></tr>
	<?php endif;?>
	</table>