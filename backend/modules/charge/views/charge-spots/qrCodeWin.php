<div class="easyui-panel" title="" 
	data-options="
		iconCls: 'icon-qrcode'
		border: false
	"
	style="padding:10px;border:0px solid red;"
>
	<table cellspacing="0" cellpadding="8" align="center" border="0" width="100%">
		<tr hidden>
			<td align="right">电桩ID:</td>
			<td><?php echo $chargerInfo['id']; ?></td>
		</tr>
		<tr>
			<td align="right" width="100px">电桩编号:</td>
			<td><?php echo $chargerInfo['code_from_compony']; ?></td>
		</tr>
		<tr>
			<td align="right">电桩类型:</td>
			<td><?php echo $chargerInfo['charge_type_txt']; ?></td>
		</tr>
		<tr>
			<td align="right">电枪总数:</td>
			<td><?php echo $chargerInfo['charge_gun_nums']; ?></td>
		</tr>
	</table>
	<ul style="list-style:none;width:625px;margin:0 auto;;padding:0;overflow:hidden;">
		<?php
			foreach($measuringPoint as $key=>$val){
				$qrdata = [];
				$qrdata['pole_Id'] = $chargerInfo['id'];
				$qrdata['measuring_point'] = $val;
				$qrdata = json_encode($qrdata);
		?>
		<li style="width:310px;text-align:center;float:left;"><img style="width:330px;height:330px;" src="<?= yii::$app->urlManager->createUrl(['charge/charge-spots/create-qr','qrdata'=>$qrdata]); ?>" /><br /><?= $key; ?></li>
		<?php } ?>
	</ul>
</div>