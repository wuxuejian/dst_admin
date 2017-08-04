<table cellpadding="6" cellspacing="0" align="center"  width="100%" border="0">
	<tr>
		<td align="right" width="13%">车辆品牌：</td>
		<td width="20%">
			<?=$detail['car_brand']?>
		</td>
		<td align="right" width="13%">产品型号：</td>
		<td width="20%">
			<?=$detail['car_model']?>
		</td>
	</tr>
	<tr>
		<td align="right" width="13%">计划提车数量：</td>
		<td width="20%">
			<?=$detail['put_car_num']?>
		</td>
		<td align="right" width="13%">抽检数量：</td>
		<td width="20%">
			<?=$detail['inspection_num']?>
		</td>
	</tr>
	<tr>
		<td align="right" width="13%">抽检负责人：</td>
		<td width="20%">
			<?=$detail['inspection_director_name']?>
		</td>
		<td align="right" width="13%">验车时间：</td>
		<td width="20%">
			<?=$detail['validate_car_time']?>
		</td>
	</tr>
	<tr>
		<td align="right" width="13%">抽检结果判定：</td>
		<td width="20%" colspan="3">
			<?=$detail['inspection_result']?>
		</td>
	</tr>
	<tr>
		<td align="right" width="13%">验车单附件：</td>
		<td width="20%" colspan="3">
			<a href='./uploads/image/inspection/<?=$detail['car_no_img']?>' target="_b">
				<img src='./uploads/image/inspection/<?=$detail['car_no_img']?>' width="100" height="100"/>
			</a>
		</td>
	</tr>
	<?php 
		foreach ($cars as $index=>$row){
	?>
		<tr>
			<td align="right" width="13%">样车<?=$index+1?>车架号：</td>
			<td width="20%" colspan="3">
				<?=$row['vehicle_dentification_number']?>
			</td>
		</tr>
		<tr>
			<td align="right" width="13%">检验情况描述：</td>
			<td width="20%" colspan="3">
				<?=$row['note']?>
			</td>
		</tr>
	<?php 
		}
	?>
</table>
