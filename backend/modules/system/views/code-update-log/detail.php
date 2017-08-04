<table cellpadding="6" cellspacing="0" align="center"  width="100%" border="0">
	<tr>
		<td align="right" width="13%">升级产品：</td>
		<td width="20%">
			<?php
				$product_arr = array(1=>'地上铁APP',2=>'地上铁系统');
				echo $product_arr[$log['product']];
			?>
		</td>
		<td align="right" width="13%">升级类型：</td>
		<td width="20%">
			<?php
				$update_type_arr = array(1=>'优化',2=>'修复',3=>'新增',4=>'删除');
				echo $update_type_arr[$log['update_type']];
			?>
		</td>
	</tr>
	<tr>
		<td align="right" width="13%">功能模块：</td>
		<td width="20%">
			<?=$log['module']?>
		</td>
		<td align="right" width="13%">版本号：</td>
		<td width="20%">
			<?=$log['version_number']?>
		</td>
	</tr>
	<tr>
		<td align="right" width="13%">升级日期：</td>
		<td width="20%">
			<?=$log['update_date']?>
		</td>
	</tr>
	<tr>
		<td align="right" width="13%">升级内容简述：</td>
		<td width="20%">
			<?=$log['update_title']?>
		</td>
	</tr>
	<tr>
		<td align="right" width="13%">升级详细内容：</td>
		<td width="20%">
			<?=$log['note']?>
		</td>
	</tr>
</table>
