<div class="easyui-tabs" data-options="fit:true,border:false"> 
<div title="订单详情" style="padding:15px">
<div
    class="easyui-panel"
    title="合同基本信息"
    style="width:100%;margin-bottom:5px;"
    closable="false"
    collapsible="false"
    minimizable="false"
    maximizable="false"
    border="false"
>
<table cellpadding="6" cellspacing="0" align="center"  width="100%" border="0">
	<tr>
		<td align="right"><div style="width:70px;">合同编号:</div></td>
		<td><?php echo $result_main['contract_number'];?></td>
		<td align="right"><div style="width:70px;">经销商名称:</div></td>
		<td width="20%"><?php echo $result_main['distributor_name'];?></td>
	</tr>
	<tr>
		
		<td align="right"><div style="width:70px;">订单编号:</div></td>
		<td width="20%"><?php echo $result_main['order_number'];?></td>
		<td align="right"><div style="width:70px;">接收方:</div></td>
		<td width="20%"><?php echo $result_main['company_name'];?></td>
		<td align="right"><div style="width:70px;">大区:</div></td>
		<td width="20%">
			<?php if($result_main['area']==1){
						echo '华南大区';
					}else if ($result_main['area']==2){
						echo '华北大区';
					} else if($result_main['area']==3) {
						echo '华东大区';
					} else if($result_main['area']==4) {
						echo '华中大区';
					} else{
						echo '西南大区';
					}
			;?>
		</td>
	</tr>
	<tr>
		<td align="right"><div>合同签署时间:</div></td>
		<td width="20%"><?php echo date('Y-m-d',$result_main['sign_time']);?></td>
		<td align="right"><div style="width:70px;">所有人:</div></td>
		<td width="20%"><?php echo $result_main['receiver_name'];?></td>

	</tr>
	<tr>
		<td align="right"><div>预计发货时间:</div></td>
		<td width="20%"><?php echo date('Y-m-d',$result_main['estimated_delivery_time']);?></td>	
	</tr>
	
	</table>
</div>
<div
    class="easyui-panel"
    title="车辆详情"
    style="width:100%;margin-bottom:5px;"
    closable="false"
    collapsible="false"
    minimizable="false"
    maximizable="false"
    border="false"
>
<table cellpadding="6" cellspacing="0" align="center"  width="100%" border="1">
	<tr border="1px" type="checkbox">
		<th>类别</th><th>品牌</th><th>车型</th><th>数量</th><th>其他</th>
	</tr>

		<?php foreach ($result_details as $key => $value): ?>
		<tr border="1px">
			<td><?php echo '整车'?></td>
			<td><?php echo $value['brand_name'];?></td>
			<td><?php echo $value['car_model_name'];?></td>
			<td><?php echo $value['quantity'];?></td>
			<td><?php echo $value['parts'];?></td>
		</tr>
		<?php endforeach; ?>
	
		
	</table>
</div>
<div
    class="easyui-panel"
    title="其他信息"
    style="width:100%;margin-bottom:5px;"
    closable="false"
    collapsible="false"
    minimizable="false"
    maximizable="false"
    border="false"
>
<table cellpadding="6" cellspacing="0" align="center"  width="100%" border="0">
	<tr>	
		<td><?php echo $result_main['note'];?></td>
	</tr>		
</table>
</div>
</div>
<div title="发车详情" style="padding:15px">
<?php $i=1;foreach($result_express as $ket => $value) {?>
<div
    class="easyui-panel"
    title="订单信息(<?php echo $i;?>)"
    style="width:100%;margin-bottom:5px;"
    closable="false"
    collapsible="false"
    minimizable="false"
    maximizable="false"
    border="false"
>
<table cellpadding="6" cellspacing="0" align="center"  width="100%" border="0">
	<tr>
		<td align="right" width="13%">订单编号：</td>
		<td width="20%"><?php echo $value['order_number'];?></td>
		<td align="right" width="13%">接受方：</td>
		<td width="20%"><?php echo $value['company_name'];?></td>
	</tr>
	<tr>
		<td align="right" width="13%">预计发货时间:</td>
		<td width="20%"><?php echo date('Y-m-d',$value['estimated_delivery_time']);?></td>
		<td align="right" width="13%">实际发货时间:</td>
		<td width="20%"><?php echo date('Y-m-d',$value['true_delivery_time']);?></td>
	</tr>	
</table>
</div>

<div
    class="easyui-panel"
    title="物流信息(<?php echo $i;?>)"
    style="width:100%;margin-bottom:5px;"
    closable="false"
    collapsible="false"
    minimizable="false"
    maximizable="false"
    border="false"
>
<table cellpadding="6" cellspacing="0" align="center"  width="100%" border="0">
	<tr>
		<td align="right" width="13%">承运公司:</td>
		<td width="20%"><?php echo $value['express_company'];?></td>
		<td align="right" width="13%">运单编号:</td>
		<td width="20%"><?php echo $value['express_number'];?></td>
	</tr>
	<tr>
		<td align="right" width="13%">联系电话:</td>
		<td width="20%"><?php echo $value['express_phone'];?></td>
		<td align="right" width="13%">预计到达时间:</td>
		<td width="20%"><?php echo date('Y-m-d',$value['estimated_arrive_time']);?></td>
	</tr>	
</table>
</div>
<div
    class="easyui-panel"
    title="车辆信息(<?php echo $i;?>)"
    style="width:100%;margin-bottom:5px;"
    closable="false"
    collapsible="false"
    minimizable="false"
    maximizable="false"
    border="false"
>
<table cellpadding="6" cellspacing="0" align="center"  width="100%" border="1">
	<!-- <tr>
		<td align="right" width="13%">类别:</td>
		<td width="20%"><?php echo $value['plate_number'];?></td>
		<td align="right" width="13%">品牌:</td>
		<td width="20%"><?php echo $value['add_time'];?></td>
		<td align="right" width="13%">类型:</td>
		<td width="20%"><?php echo $value['add_time'];?></td>
		<td align="right" width="13%">数量:</td>
		<td width="20%"><?php echo $value['add_time'];?></td>
		<td align="right" width="13%">其他:</td>
		<td width="20%"><?php echo $value['add_time'];?></td>
	</tr> -->
	<th>类别</th><th>品牌</th><th>车型</th><th>数量</th><th>其他</th>
	<?php foreach ($result_express2 as $key => $val): ?>
	
	<?php foreach ($val['order'] as $key1 =>$value2): ?>
	
		<tr border="1px">
			<td><?php echo '整车'?></td>
			<td><?php echo $value2['brand_name'];?></td>
			<td><?php echo $value2['car_model_name'];?></td>
			<td><?php echo $value['order_num'][$key1];?></td>
			<td><?php echo $value2['parts'];?></td>
		</tr>
	<?php endforeach; ?>
	<?php endforeach; ?>	
</table>

</div>
<?php $i++;} ?>
</div>
<!-- <div title="到车状态" style="padding:15px">
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
	</table>
</div>
<div title="上牌状态" style="padding:15px">
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
	</table>
</div> -->
<!-- <div title="入库状态" style="padding:15px">
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
	</table>
</div> -->

</div>