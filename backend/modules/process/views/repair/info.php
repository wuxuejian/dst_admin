<table cellpadding="6" cellspacing="0" align="center"  width="100%" border="0">
	<tr>
		<td align="right" width="13%">工单类型：</td>
		<td width="20%"><?php echo $result['type'];?></td>
		<td align="right" width="13%">工单来源：</td>
		<td width="20%"><?php echo $result['source']?></td>
	</tr>
	<tr>
		<td align="right" width="13%">报修人姓名：</td>
		<td width="20%"><?php echo $result['repair_name']?></td>
		<td align="right" width="13%">来电号码：</td>
		<td width="20%"><?php echo $result['tel']?></td>
	</tr>
	<tr>
		<td align="right" width="13%">来电时间：</td>
		<td width="20%"><?php echo $result['tel_time'];?></td>
		<td align="right" width="13%">紧急程度：</td>
		<td width="20%"><?php echo $result['urgency']?></td>
	</tr>
	<tr>
		<td align="right" width="13%">车牌号：</td>
		<td width="20%" ><?php echo $result['car_no']?></td>
		<td align="right" width="13%">故障发生时间：</td>
		<td width="20%"><?php echo !empty($result['fault_start_time']) ? date('Y-m-d H:i',$result['fault_start_time']):'';?></td>
	</tr>
	<tr>
		<td align="right" width="13%">故障地点：</td>
		<td width="20%" colspan="3"><?php echo $result['address']?>,<?php echo $result['bearing']?></td>
	</tr>
	<tr>
			<td align="right" width="13%">工单内容简述：</td>
			<td width="20%" colspan="3"><?php echo $result['desc']?></td>
	</tr>
	<tr>
		<td align="right" width="13%">来电内容记录：</td>
		<td width="20%" colspan="3"><?php echo $result['tel_content']?></td>
	</tr>
	<tr>
		<td align="right" width="13%">所需服务：</td>
		<td width="20%" colspan="3"><?php echo $result['need_serve']?></td>
	</tr>
	<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>
	<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>
	<?php if($result['status'] >=3):?>
		<tr>
			<td align="right" width="13%">派单对象：</td>
			<td width="20%"><?php echo $result['assign_name'];?></td>
			<td align="right" width="13%">确认时间：</td>
			<td width="20%"><?php echo !empty($result['confirm_time']) ? date('Y-m-d H:i',$result['confirm_time']):'';?></td>
		</tr>
		<tr>
			<td align="right" width="13%">已听取录音：</td>
			<td width="20%"><?php echo !empty($result['is_voice']) ? '是':'否'?></td>
			<td align="right" width="13%">已电话回访：</td>
			<td width="20%"><?php echo !empty($result['is_visit']) ? '是':'否'?></td>
		</tr>
		<tr>
			<td align="right" width="13%">需要出外勤：</td>
			<td width="20%"><?php echo !empty($result['is_attendance']) ? '是':'否' ?></td>
			<td align="right" width="13%">携带设备：</td>
			<td width="20%"><?php echo $result['carry']?></td>
		</tr>
		<tr>
			<td align="right" width="13%">需申请用车：</td>
			<td width="20%"><?php echo !empty($result['is_use_car']) ? '是':'否' ?></td>
			<td align="right" width="13%">外勤用车车牌号：</td>
			<td width="20%"><?php echo $result['use_car_no']?></td>
		</tr>
	
	<?php endif;?>
	</table>