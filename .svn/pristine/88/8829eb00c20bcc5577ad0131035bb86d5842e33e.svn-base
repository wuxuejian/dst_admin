<table cellpadding="6" cellspacing="0" align="center"  width="100%" border="0">
	<tr>
		<td align="right" width="13%">车牌号：</td>
		<td width="20%"><?php echo $result['plate_number'];?></td>
		<td align="right" width="13%">车架号：</td>
		<td width="20%"><?php echo $result['vehicle_dentification_number'];?></td>
	</tr>
	<tr>
		<td align="right" width="13%">故障发生时间：</td>
		<td width="20%"><?php echo !empty($result['fault_start_time']) ? date('Y-m-d H:i',$result['fault_start_time']):'';?></td>
		<td align="right" width="13%">故障反馈时间：</td>
		<td width="20%"><?php echo !empty($result['feedback_time']) ? date('Y-m-d H:i',$result['feedback_time']):'';?></td>
	</tr>
	<tr>
		<td align="right" width="13%">故障反馈人：</td>
		<td width="20%"><?php echo $result['feedback_name']?></td>
		<td align="right" width="13%">联系电话：</td>
		<td width="20%"><?php echo $result['tel']?></td>
	</tr>
	<tr>
		<td align="right" width="13%">本方受理人：</td>
		<td width="20%"><?php echo $result['accept_name']?></td>
		<td align="right" width="13%">故障上报时间：</td>
		<td width="20%"><?php echo !empty($result['fault_report_time']) ? date('Y-m-d H:i',$result['fault_report_time']):'';?></td>
	</tr>
	<tr>
		<td align="right" width="13%">故障地点：</td>
		<td width="20%" colspan="3"><?php echo $result['fault_address']?></td>
	</tr>
	<tr>
		<td align="right" width="13%">故障描述：</td>
		<td width="20%" colspan="3"><?php echo $result['scene_desc']?></td>
	</tr>
	<tr>
		<td align="right" width="13%">处理结果：</td>
		<td width="20%" colspan="3"><?php echo $result['scene_result']?></td>
	</tr>
		<tr>
		<td align="right" width="13%">维修方式：</td>
		<td width="20%"><?php

		
		//echo $result['maintain_way'] == 1 ? '进厂维修':'现场维修';
		switch ($result['maintain_way']){
			case 1:
				echo '进厂维修';
				break;
			case 2:
				echo '现场维修';
				break;
			case 3:
				echo '自修';
				break;
		}
		
		
		?></td>
		<?php if($result['maintain_way'] != 3):?>
		<td align="right" width="13%">维修场站：</td>
		<td width="20%"><?php echo $result['maintain_scene']?></td>
		<?php endif;?>
	</tr>
	<?php if($result['maintain_way'] != 3):?>
	<tr>
		<td align="right" width="13%">维修方联系人：</td>
		<td width="20%"><?php echo $result['maintain_name']?></td>
		<td align="right" width="13%">联系电话：</td>
		<td width="20%"><?php echo $result['maintain_tel']?></td>
	</tr>
	<tr>
		<td align="right" width="13%">维修技工：</td>
		<td width="20%"><?php echo $result['maintain_worker']?></td>
		<td align="right" width="13%">联系方式：</td>
		<td width="20%"><?php echo $result['maintain_worker_tel']?></td>
	</tr>
	<tr>
		<td align="right" width="13%">进厂维修单号：</td>
		<td width="20%"><?php echo $result['maintain_no']?></td>
		<td align="right" width="13%">预计完结时间：</td>
		<td width="20%"><?php echo !empty($result['expect_time']) ? date('Y-m-d H:i',$result['expect_time']):''?></td>
	</tr>
	<?php endif;?>
	<tr>
		<td align="right" width="13%">故障照片：</td>
		<td><a href="<?php echo !empty($result['car_no_img']) ? $result['car_no_img'] : 'javascript:void(0)';?>" target="_blank"><img  src="<?php echo $result['car_no_img']?>" width="100" height="100" title="车牌照片"  alt="没有上传" /></a></td>
		<td><a href="<?php echo !empty($result['dashboard_img']) ? $result['dashboard_img']: 'javascript:void(0)';?>" target="_blank"><img  src="<?php echo $result['dashboard_img']?>" width="100" height="100" title="车辆仪表"  alt="没有上传" /></a></td>
		<td><a href="<?php echo !empty($result['fault_scene_img']) ? $result['fault_scene_img']: 'javascript:void(0)';?>" target="_blank"><img src="<?php echo $result['fault_scene_img']?>" width="100" height="100" title="故障现场" alt="没有上传"   /></a></td>
	</tr>
	<tr>
		<td></td>
		<td><a href="<?php echo !empty($result['fault_location_img']) ? $result['fault_location_img'] : 'javascript:void(0)';?>" target="_blank"><img class="repairImg" src="<?php echo $result['fault_location_img']?>" width="100" height="100" title="故障位置" alt="没有上传" /></a></td>
		<td><a href="<?php echo !empty($result['maintain_jieche_img']) ? $result['maintain_jieche_img'] :'javascript:void(0)';?>" target="_blank"><img class="repairImg" src="<?php echo $result['maintain_jieche_img']?>" width="100" height="100" title="维修站点接车单" alt="没有上传"  /></a></td>
	</tr>
	</table>