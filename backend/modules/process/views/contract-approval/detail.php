<table cellpadding="5" cellspacing="0" width="100%" border="0">
	<tr>
		<td align="right">合同类型：</td>
		<td>
			<?php 
				$contract_type_arr = array(1=>'采购类',2=>'营销类',3=>'行政类',4=>'基建类',5=>'专项服务类',6=>'知识产权类',10=>'其它');
				echo $contract_type_arr[$detail['contract_type']];
			?>
		</td>
		<td align="right">合同格式：</td>
		<td>
			<?php 
				$contract_format_arr = array(1=>'公司标准合同',2=>'公司非标准合同',3=>'政府范本合同',4=>'对方起草合同');
				echo $contract_format_arr[$detail['contract_format']];
			?>
		</td>
	</tr>
	<tr>
		<td align="right">合同名称：</td>
		<td>
			<?=$detail['contract_name']?>
		</td>
		<td align="right">合同编号：</td>
		<td>
			<?=$detail['contract_no']?>
		</td>
	</tr>
	<tr>
		<td align="right">对方公司名称：</td>
		<td colspan="3">
			<?=$detail['customer_company_name']?>
		</td>
	</tr>
	<tr>
		<td align="right">对方联系人：</td>
		<td>
			<?=$detail['customer_contact_name']?>
		</td>
		<td align="right">联系电话：</td>
		<td>
			<?=$detail['customer_contact_tel']?>
		</td>
	</tr>
	<tr>
		<td align="right">合同要点：</td>
		<td colspan="3">
			<?=$detail['contract_cruces']?>
		</td>
	</tr>
	<tr>
		<td align="right">合同标的额：</td>
		<td>
			<?php 
				$money_type_arr = array(1=>'有具体金额',2=>'无具体金额');
				echo $money_type_arr[$detail['money_type']];
			?>
		</td>
		<td align="right">金额：</td>
		<td>
			<?=$detail['money']?>
		</td>
	</tr>
	<tr>
		<td align="right">收款/付款：</td>
		<td>
			<?php 
				$business_type_arr = array(1=>'收款',2=>'付款');
				echo $business_type_arr[$detail['business_type']];
			?>
		</td>
		<td align="right">收款/付款时间：</td>
		<td>
			<?=$detail['business_time']?>
		</td>
	</tr>
	<tr>
		<td align="right">收款/付款方式：</td>
		<td colspan="3">
			<?=$detail['business_way']?>
		</td>
	</tr>
	<tr>
		<td align="right">经办人：</td>
		<td>
			<?=$detail['oper_name']?>
		</td>
		<td align="right">所属部门：</td>
		<td>
			<?=$detail['department_name']?>
		</td>
	</tr>
	<tr>
		<td align="right">经办人电话：</td>
		<td>
			<?=$detail['oper_tel']?>
		</td>
		<td align="right">合同份数：</td>
		<td>
			<?=$detail['contract_num']?>
		</td>
	</tr>
	<tr>
		<td align="right">审批报送时间：</td>
		<td>
			<?=$detail['approval_start_time']?>
		</td>
		<td align="right">要求完成审批时间：</td>
		<td>
			<?=$detail['approval_end_time']?>
		</td>
	</tr>
</table>