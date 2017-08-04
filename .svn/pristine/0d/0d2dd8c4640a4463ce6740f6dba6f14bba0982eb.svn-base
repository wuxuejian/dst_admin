
	<table cellpadding="8" cellspacing="2" style="width:100%;" border="0">
					<tr>
                        <th align="right"  width="10%">车辆品牌：</th>
                        <td>
                            <?php echo $obj['brand_name']; ?>
                        </td>
                        <th align="right"  width="10%">车辆型号：</th>
                        <td>
                            <?php echo $obj['car_model_name']; ?>
                        </td>
                        <th align="right"  width="10%">车辆状态：</th>
                        <td>
                            <?php echo $obj['customer_name']; ?>
                        </td>
                    </tr>

                    <tr>
                    	<th align="right" width="10%">出险单号：</th>
                        <td>
                        	<?=$obj['insurance_claim']['number']?>
                        </td>
                        <th align="right" width="10%">出险状态：</th>
                        <td>
                            <?=$obj['insurance_claim_state']?>
                        </td>
                        <th align="right"  width="10%">归属客户：</th>
                        <td>
                           <?php echo $obj['claim_customer_name']; ?>
                        </td>
                    </tr>
                    <tr>
                    	<th align="left" colspan="1"  width="10%" style="background-color:#EBDFA1;">
                    		1.报案出险
                    	</th>
                    	<td align="right" colspan="7" style="background-color:#EBDFA1;">
                    		上一次操作人员：<?=$obj['insurance_claim']['oper_user1']?>
                    	</td>
                    </tr>
                    <tr>
                    	<th align="right" width="10%">车牌号：</th>
                        <td>
                        	<?=$obj['insurance_claim']['claim_car']?>
                        </td>
                        <th align="right" width="10%">出险日期：</th>
                        <td>
                            <?=$obj['insurance_claim']['danger_date']?>
                        </td>
                    </tr>
                    <tr>
                    	<th align="right" width="10%">报案人：</th>
                        <td>
                        	<?=$obj['insurance_claim']['people']?>
                        </td>
                        <th align="right" width="10%">报案人电话：</th>
                        <td>
                            <?=$obj['insurance_claim']['tel']?>
                        </td>
                    </tr>
                    <tr>
                    	<th align="right" width="10%">出险地址：</th>
                        <td colspan="3">
                        	<?=$obj['insurance_claim']['area_detail']?>
                        </td>
                    </tr>
                    
                    <tr>
                    	<th align="left" colspan="1"  width="10%" style="background-color:#EBDFA1;">
                    		2.查勘结论
                    	</th>
                    	<td align="right" colspan="7" style="background-color:#EBDFA1;">
                    		上一次操作人员：<?=$obj['insurance_claim']['oper_user2']?>
                    	</td>
                    </tr>
                    <tr>
                    	<th align="right" width="10%">查看类型：</th>
                        <td colspan="7">
                        	<?php 
                        		$type_of_survey_arr = array(1=>'保险公司查勘',2=>'快处快赔',3=>'交警查勘',4=>'公估公司',5=>'互碰自赔');
                        	?>
                        	<?php 
                        		if($obj['insurance_claim']['type_of_survey']){
                        			echo $type_of_survey_arr[$obj['insurance_claim']['type_of_survey']];
                        		}else {
                        			echo $obj['insurance_claim']['type_detail'];
                        		}
                        	?>
                        </td>
                    </tr>
                    <?php 
                    	$responsibilitys = json_decode($obj['insurance_claim']['responsibility_text']);
						if(!$responsibilitys){
							$responsibilitys = array();
						}
                    	foreach ($responsibilitys as $row){
                    		echo '<tr>';
                    		if($row->responsibility_object==1){	//标的车
echo '<th align="right">责任对象：</th><td>标的车</td>';
echo '<th align="right" ></th><td ></td>';
echo '<th align="right">责任比重：</th><td>'.$row->specific_gravity.'</td>';
echo '<th align="right">受损情况：</th><td>'.$row->damage_condition.'</td>';
                    		}else if($row->responsibility_object==2){	//三者车
echo '<th align="right" >责任对象：</th><td >三者车</td>';
echo '<th align="right" >车牌号：</th><td >'.$row->plate_number.'</td>';
echo '<th align="right" >责任比重：</th><td >'.$row->specific_gravity.'</td>';
echo '<th align="right" >受损情况：</th><td >'.$row->damage_condition.'</td>';                    			
                    		}else if($row->responsibility_object==3){	//三者物
echo '<th align="right" width="10%">责任对象：</th><td width="23%">三者物</td>';
echo '<th align="right" >物体名称：</th><td >'.$row->object_name.'</td>';
echo '<th align="right" >责任比重：</th><td >'.$row->specific_gravity.'</td>';
echo '<th align="right" >受损情况：</th><td >'.$row->damage_condition.'</td>';
                    		}else if($row->responsibility_object==4){	//三者人
echo '<th align="right" width="10%">责任对象：</th><td width="23%">三者人</td>';
echo '<th align="right" >姓名：</th><td >'.$row->full_name.'</td>';
echo '<th align="right" >责任比重：</th><td >'.$row->specific_gravity.'</td>';
echo '<th align="right" >受损情况：</th><td >'.$row->damage_condition.'</td>';
                    		}
                    		echo '</tr>';
                    	}
                    ?>
                    
                    <tr>
                    	<th align="left" colspan="1"  width="10%" style="background-color:#EBDFA1;">
                    		3.保险定损
                    	</th>
                    	<td align="right" colspan="7" style="background-color:#EBDFA1;">
                    		上一次操作人员：<?=$obj['insurance_claim']['oper_user3']?>
                    	</td>
                    </tr>
                    <?php 
                    	$damageds = json_decode($obj['insurance_claim']['damaged_text']);
                    	$index = 0;
                    	foreach ($responsibilitys as $row){
                            if(!@$damageds[$index]){
                                continue;
                            }
                    		if($row->responsibility_object==1 || $row->responsibility_object==2 || $row->responsibility_object==3){
                    			echo '<tr>';
                    			if($row->responsibility_object==1){	//标的车
                    				echo '<th align="right">标的车定损：</th><td>'.$damageds[$index]->damaged_money.'</td>';
                    				echo '<th align="right">定损时间：</th><td>'.$damageds[$index]->damaged_date.'</td>';
                    			}else if($row->responsibility_object==2){	//三者车
                    				echo '<th align="right">'.$row->plate_number.'定损：</th><td>'.$damageds[$index]->damaged_money.'</td>';
                    				echo '<th align="right">定损时间：</th><td>'.$damageds[$index]->damaged_date.'</td>';
                    			}else if($row->responsibility_object==3){	//三者物
                    				echo '<th align="right">'.$row->object_name.'定损：</th><td>'.$damageds[$index]->damaged_money.'</td>';
                    				echo '<th align="right">定损时间：</th><td>'.$damageds[$index]->damaged_date.'</td>';
                    			}
                    			echo '</tr>';
                    			$index++;
                    		}
                    	}
                    ?>
                    <tr>
                    	<th align="left" colspan="1"  width="10%" style="background-color:#EBDFA1;">
                    		4.车辆维修
                    	</th>
                    	<td align="right" colspan="7" style="background-color:#EBDFA1;">
                    		上一次操作人员：<?=$obj['insurance_claim']['oper_user4']?>
                    	</td>
                    </tr>
                    <?php 
                    	$maintenances = json_decode($obj['insurance_claim']['maintenance_text']);
                    	$index=0;
                    	foreach ($responsibilitys as $row){
							if(!@$maintenances[$index]){
								continue;
							}
                    		if($row->responsibility_object==1 || $row->responsibility_object==2){
	                    		if($row->responsibility_object==1){	//标的车
	echo '<tr><th align="right">标的维修厂：</th><td>'.$maintenances[$index]->maintenance_shop.'</td></tr>';
	                    		}else if($row->responsibility_object==2){	//三者车
	echo '<tr><th align="right">'.$row->plate_number.'维修厂：</th><td>'.$maintenances[$index]->maintenance_shop.'</td></tr>';
	                    		}
	                    		echo '<tr><th align="right">维修情况：</th><td>'.$maintenances[$index]->maintenance_condition.'</td>';
	                    		echo '<th align="right">维修时间：</th><td>'.$maintenances[$index]->maintenance_time.'</td></tr>';
	                    		$index++;
                    		}
                    	}
                    ?>
                    
                    <tr>
                    	<th align="left" colspan="1"  width="10%" style="background-color:#EBDFA1;">
                    		5.保险理赔
                    	</th>
                    	<td align="right" colspan="7" style="background-color:#EBDFA1;">
                    		上一次操作人员：<?=$obj['insurance_claim']['oper_user5']?>
                    	</td>
                    </tr>
                    <?php 
                    	$claims = json_decode($obj['insurance_claim']['claim_text']);
                    	$index = 0;
                    	$claim_amount = 0;
                    	foreach ($responsibilitys as $row){
                    		if($row->responsibility_object==1 || $row->responsibility_object==2 || $row->responsibility_object==3){
								if(!@$claims[$index]){
									continue;
								}
                    			if($row->responsibility_object==1){	//标的车
                    				foreach ($claims[$index] as $sub){
                    					echo '<tr><th align="right">标的车理赔：</th><td></td><th align="right">理赔类型：</th><td>'.$sub->claim_type.'</td></tr>';
                    					echo '<tr><th align="right">保险公司：</th><td>'.$sub->insurance_company.'</td>';
                    					echo '<th align="right">理赔时间：</th><td>'.$sub->claim_time.'</td>';
                    					echo '<th align="right">理赔金额：</th><td>'.$sub->claim_amount.'</td></tr>';
                    					$claim_amount += $sub->claim_amount;
                    				}
                    			}else if($row->responsibility_object==2){	//三者车
                    				foreach ($claims[$index] as $sub){
                    					echo '<tr><th align="right">'.$row->plate_number.'理赔：</th><td></td><th align="right">理赔类型：</th><td>'.$sub->claim_type.'</td></tr>';
                    					echo '<tr><th align="right">保险公司：</th><td>'.$sub->insurance_company.'</td>';
                    					echo '<th align="right">理赔时间：</th><td>'.$sub->claim_time.'</td>';
                    					echo '<th align="right">理赔金额：</th><td>'.$sub->claim_amount.'</td></tr>';
                    					$claim_amount += $sub->claim_amount;
                    				}
                    			}else if($row->responsibility_object==3){	//三者物
                    				foreach ($claims[$index] as $sub){
                    					echo '<tr><th align="right">'.$row->object_name.'理赔：</th><td></td><th align="right">理赔类型：</th><td>'.$sub->claim_type.'</td></tr>';
                    					echo '<tr><th align="right">保险公司：</th><td>'.$sub->insurance_company.'</td>';
                    					echo '<th align="right">理赔时间：</th><td>'.$sub->claim_time.'</td>';
                    					echo '<th align="right">理赔金额：</th><td>'.$sub->claim_amount.'</td></tr>';
                    					echo '<tr><th align="right">赔付对象：</th><td>'.$sub->claim_customer.'</td><th align="right">赔付账户：</th><td>'.$sub->claim_account.'</td></tr>';
                    					$claim_amount += $sub->claim_amount;
                    				}
                    			}
                    			
                    			$index++;
                    		}
                    	}
                    ?>
                    
                    <tr>
                    	<th align="left" colspan="1"  width="10%" style="background-color:#EBDFA1;">
                    		6.保险请款
                    	</th>
                    	<td align="right" colspan="7" style="background-color:#EBDFA1;">
                    		上一次操作人员：<?=$obj['insurance_claim']['oper_user6']?>
                    	</td>
                    </tr>
                    <?php 
                    	$pays = json_decode($obj['insurance_claim']['pay_text']);
						if(!$pays){$pays=array();}
                    	$transfer_amount = 0;
                    	foreach ($pays as $row){
                    		echo '<tr><th align="right">客户名称：</th><td>'.$row->customer_name.'</td></tr>';
                    		echo '<tr><th align="right">开户银行：</th><td>'.$row->bank_account.'</td>';
                    		echo '<th align="right">账户名：</th><td>'.$row->account_name.'</td>';
                    		echo '<th align="right">开户帐号：</th><td>'.$row->account_opening.'</td>';
                    		echo '</tr>';
                    		echo '<tr><th align="right">转账金额：</th><td>'.$row->transfer_amount.'</td>';
                    		echo '<th align="right">抵押金额：</th><td>'.$obj['insurance_claim']['rent_amount'].'</td>';
                    		echo '<th align="right">请款用途：</th><td>'.$row->please_use.'</td>';
                    		echo '</tr>';
                    		$transfer_amount += $row->transfer_amount;
                    	}
                    	
                    	echo '<tr><th align="right"></th><td></td>';
                    	echo '<th align="right">转账总额：</th><td>'.$transfer_amount.'</td>';
                    	echo '<th align="right">理赔余额：</th><td>'.$claim_amount.'</td>';
                    	echo '</tr>';
                    ?>
                    
                    <tr>
                    	<th align="left" colspan="1"  width="10%" style="background-color:#EBDFA1;">
                    		7.转账结案
                    	</th>
                    	<td align="right" colspan="7" style="background-color:#EBDFA1;">
                    		上一次操作人员：<?=$obj['insurance_claim']['oper_user7']?>
                    	</td>
                    </tr>
                    <?php 
                    	$transfers = json_decode($obj['insurance_claim']['transfer_text']);
                    	foreach ($pays as $index=>$row){
                    		if(!@$transfers[$index]){
                    			continue;
                    		}
                    		echo '<tr><th align="right">客户名称：</th><td>'.$row->customer_name.'</td>';
                    		echo '<tr><th align="right">转账时间：</th><td>'.$transfers[$index]->transfer_time.'</td>';
                    		echo '<th align="right">转账凭证：</th><td><a target="_b" href="'.$transfers[$index]->append_url.'"><img width="50" height="50" src="'.$transfers[$index]->append_url.'"/></a></td>';
                    		echo '</tr>';
                    	}
                    ?>
	</table>

<script>
function compulsoryDownload(id){
	window.open("<?php echo yii::$app->urlManager->createUrl(['car/insurance/download']);?>&id="+id);
}
function toCompulsory(){
	$('#easyui-window-car-insurance-index-scan').dialog('close');
	CarInsuranceIndex.trafficCompulsoryInsurance();
}
function businessDownload(id){
	window.open("<?php echo yii::$app->urlManager->createUrl(['car/insurance/bi-download']);?>&id="+id);
}
function toBusiness(){
	$('#easyui-window-car-insurance-index-scan').dialog('close');
	CarInsuranceIndex.businessInsurance();
}
</script>