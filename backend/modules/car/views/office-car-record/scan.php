<form
    id="easyui-form-car-office-car-register-scan"
    class="easyui-form"
    style="padding:10px;" method="post"
>
    	<table cellpadding="8" cellspacing="0">
		<tr>
			<h2>借车登记信息</h2>
		</tr>
            <tr>
                <td><div style="width:100px;text-align:right;">车牌号：</div></td>
                <td ><?php echo $carofficeregister['plate_number']; ?></td>
			</tr>
                <td><div style="width:100px;text-align:right;">申请部门：</div></td>
                <td><?php echo $carofficeregister['department_name']; ?></td>
                <td><div style="width:100px;text-align:right;">用车人：</div></td>
                <td><?php echo $carofficeregister['username']; ?></td>
            </tr>
            <tr>
                <td><div style="width:100px;text-align:right;">开始用车时间：</div></td>
                <td><?php echo $carofficeregister['start_time']; ?></td>
                <td><div style="width:100px;text-align:right;">预计还车时间：</div></td>
                <td><?php echo $carofficeregister['end_time']; ?></td>
				</tr>
			<tr>
                <td><div style="width:100px;text-align:right;">用车事由：</div></td>
                <td><?php echo $carofficeregister['reason']; ?></td>	
                <td><div style="width:100px;text-align:right;">出车地点：</div></td>
                <td><?php echo $carofficeregister['address']; ?></td>	
            </tr>
            <tr>

                <td><div style="width:100px;text-align:right;">当前总里程：</div></td>
                <td><?php echo $carofficeregister['total_distance']; ?></td>	
                <td><div style="width:100px;text-align:right;">剩余续航里程：</div></td>
                <td colspan="5"><?php echo $carofficeregister['remain_distance']; ?></td>					
            </tr>
             <tr>
                <td><div style="width:100px;text-align:right;">备注：</div></td>
                <td><?php echo $carofficeregister['note']; ?></td>              
            </tr>
             <tr>

                <td><div style="width:100px;text-align:right;">登记人：</div></td>
                <td><?php echo $carofficeregister['reg_name']; ?></td> 
                <td><div style="width:100px;text-align:right;">登记时间：</div></td>
                <td colspan="5"><?php echo $carofficeregister['reg_time']; ?></td>                  
            </tr>
				
					
        </table>
<HR style="FILTER: alpha(opacity=100,finishopacity=0,style=3)" width="100%" color=#95B8E7 SIZE=3>
    	<table cellpadding="8" cellspacing="0">
		<tr>
			<h2>还车登记信息</h2>
		</tr>
		<tr>
                <td><div style="width:100px;text-align:right;">还车时间：</div></td>
                <td><?php echo $carofficeregister['return_time']; ?></td>	
		</tr>
		<tr>
                <td><div style="width:100px;text-align:right;">还车时总里程：</div></td>
                <td><?php echo $carofficeregister['return_distance']; ?></td>	
                <td><div style="width:100px;text-align:right;">剩余续航里程：</div></td>
                <td><?php echo $carofficeregister['remain_distance_return']; ?></td>	
		</tr>
		<tr>
                <td><div style="width:100px;text-align:right;">备注：</div></td>
                <td><?php echo $carofficeregister['note_return']; ?></td>	
		</tr>				
	</table>

</form>