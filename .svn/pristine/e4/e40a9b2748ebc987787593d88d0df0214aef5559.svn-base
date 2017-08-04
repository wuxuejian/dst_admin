<table cellpadding="6" cellspacing="0" align="center"  width="100%" border="0">
	<tr>
		<td align="right" width="13%">确认人：</td>
		<td width="20%"><?php echo $result['confirm_name'];?></td>
		<td align="right" width="13%">确认时间：</td>
		<td width="20%"><?php echo !empty($result['confirm_time']) ? date('Y-m-d H:i',$result['confirm_time']) :'';?></td>
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
	<tr>
		<td align="right" width="13%">补充说明：</td>
		<td width="20%" colspan="3"><?php echo $result['confirm_remark']?>,<?php //echo $result['bearing']?></td>
	</tr>
	</table>