
		<table cellpadding="8" cellspacing="2" style="width:100%;" border="0">
                    <tr>
                        <th align="right"  width="10%">车牌号：</th>
                        <td>
                            <?php echo $obj['plate_number']; ?>
                        </td>
                        <th align="right"  width="10%">车辆品牌：</th>
                        <td>
                            <?php echo $obj['brand_name']; ?>
                        </td>
                        <th align="right"  width="10%">车辆型号：</th>
                        <td>
                            <?php echo $obj['car_model_name']; ?>
                        </td>
                        <th align="right"  width="10%">归属客户：</th>
                        <td>
                            <?php echo $obj['customer_name']; ?>
                        </td>
                    </tr>
        </table>
            <div class="easyui-panel" title="车辆保险信息" style="padding:8px 0px;"
                 data-options="collapsible:true,collapsed:false,border:false,fit:false">
                 <?php 
                 	if($obj['insurance_compulsory']){
                 ?>
                 <table cellpadding="6" cellspacing="2" style="width:100%;" border="0">
                    <tr>
                    	<th align="right" width="10%">交强险</th>
                        <td width="23%">
                        </td>
                        <th align="right" width="10%">保险公司：</th>
                        <td width="23%">
                            <?php echo $obj['insurance_compulsory']['insurer_company_name']; ?>
                        </td>
                        <th align="right"  width="10%">保费：</th>
                        <td>
                            <?php echo $obj['insurance_compulsory']['money_amount']; ?>
                        </td>
                    </tr>
                    <tr>
                    	<th align="right" width="10%">起保时间：</th>
                        <td width="23%">
                        	<?php echo date("Y-m-d",$obj['insurance_compulsory']['start_date']); ?>
                        </td>
                        <th align="right" width="10%">终保时间：</th>
                        <td width="23%">
                            <?php echo date("Y-m-d",$obj['insurance_compulsory']['end_date']); ?>
                        </td>
                        <th align="right"  width="10%">保期倒计时：</th>
                        <td>
                        	<?php 
                        		$_compulsory_end_date = $obj['insurance_compulsory']['end_date'];
	                        	if($_compulsory_end_date){
	                        		if($_compulsory_end_date+86400 < time()){
	                        			$_compulsory_end_date = '已过期';
	                        		}else{
	                        			$diff = $_compulsory_end_date - strtotime(date('Y-m-d',time())); //年月日
	                        			$days = floor($diff/(3600*24)) + 1; //+1包含今日在内
	                        			$_compulsory_end_date = $days.'天';
	                        		}
	                        	}else{
	                        		$_compulsory_end_date = '';
	                        	}
	                        	echo $_compulsory_end_date;
                        	?>
                        </td>
                    </tr>
                    <tr>
                    	<th align="right" width="10%">备注</th>
                        <td colspan="3">
                        	<?php echo $obj['insurance_compulsory']['note']; ?>
                        </td>
                        <td>
                        	<input type="button" value="保单附件" onclick="compulsoryDownload(<?=$obj['insurance_compulsory']['id']?>)">
                        </td>
                        <td>
                        	<input type="button" value="历史保单" onclick="toCompulsory()">
                        </td>
                    </tr>
                </table>
                 <?php 	
                 	}
                 ?>
                 
                 
                 <?php
                 	//商业险 
                 	if($obj['insurance_business']){
                 ?>
                 <table cellpadding="6" cellspacing="2" style="width:100%;" border="0">
                    <tr>
                    	<th align="right" width="10%">商业险</th>
                        <td width="23%">
                        </td>
                        <th align="right" width="10%">保险公司：</th>
                        <td width="23%">
                            <?php echo $obj['insurance_business']['insurer_company_name']; ?>
                        </td>
                        <th align="right"  width="10%">保费：</th>
                        <td>
                            <?php echo $obj['insurance_business']['money_amount']; ?>
                        </td>
                    </tr>
                    <tr>
                    	<th align="right" width="10%">起保时间：</th>
                        <td width="23%">
                        	<?php echo date("Y-m-d",$obj['insurance_business']['start_date']); ?>
                        </td>
                        <th align="right" width="10%">终保时间：</th>
                        <td width="23%">
                            <?php echo date("Y-m-d",$obj['insurance_business']['end_date']); ?>
                        </td>
                        <th align="right"  width="10%">保期倒计时：</th>
                        <td>
                        	<?php 
                        		$_compulsory_end_date = $obj['insurance_business']['end_date'];
	                        	if($_compulsory_end_date){
	                        		if($_compulsory_end_date+86400 < time()){
	                        			$_compulsory_end_date = '已过期';
	                        		}else{
	                        			$diff = $_compulsory_end_date - strtotime(date('Y-m-d',time())); //年月日
	                        			$days = floor($diff/(3600*24)) + 1; //+1包含今日在内
	                        			$_compulsory_end_date = $days.'天';
	                        		}
	                        	}else{
	                        		$_compulsory_end_date = '';
	                        	}
	                        	echo $_compulsory_end_date;
                        	?>
                        </td>
                    </tr>
                    <tr>
                    	<th align="right" width="10%">险种</th>
                        <td colspan="4">
                        	<?php
                        		$business_insurance = json_decode($obj['insurance_business']['insurance_text']);
                        		foreach ($business_insurance as $row){
                        			echo "{$row[0]}({$row[1]}),";
                        		}
                        	?>
                        </td>
                    </tr>
                    <tr>
                    	<th align="right" width="10%">备注</th>
                        <td colspan="3">
                        	<?php echo $obj['insurance_business']['note']; ?>
                        </td>
                        <td>
                        	<input type="button" value="保单附件" onclick="businessDownload(<?=$obj['insurance_business']['id']?>)">
                        </td>
                        <td>
                        	<input type="button" value="历史保单" onclick="toBusiness()">
                        </td>
                    </tr>
                </table>
                 <?php 	
                 	}
                 ?>
            </div>
<div class="easyui-panel" title="车辆出险信息" style="padding:8px 0px;"
                 data-options="collapsible:true,collapsed:false,border:false,fit:false">
	<table cellpadding="8" cellspacing="2" style="width:100%;" border="0">
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
                    		1报案出险
                    	</th>
                    	<td align="right" colspan="7" style="background-color:#EBDFA1;">
                    		上一次操作人员：<?=$obj['insurance_claim']['oper_user1']?>
                    	</td>
                    </tr>
                    <tr>
                    	<th align="right" width="10%">出险车牌号：</th>
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
                    		2查勘结论
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
                    		3保险定损
                    	</th>
                    	<td align="right" colspan="7" style="background-color:#EBDFA1;">
                    		上一次操作人员：<?=$obj['insurance_claim']['oper_user3']?>
                    	</td>
                    </tr>
                    <?php 
                    	$damageds = json_decode($obj['insurance_claim']['damaged_text']);
                    	$index = 0;
                    	foreach ($responsibilitys as $row){
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
                    		4车辆维修
                    	</th>
                    	<td align="right" colspan="7" style="background-color:#EBDFA1;">
                    		上一次操作人员：<?=$obj['insurance_claim']['oper_user4']?>
                    	</td>
                    </tr>
                    <?php 
                    	$maintenances = json_decode($obj['insurance_claim']['maintenance_text']);
                    	$index=0;
                    	foreach ($responsibilitys as $row){
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
                    		5保险理赔
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
                    		6保险请款
                    	</th>
                    	<td align="right" colspan="7" style="background-color:#EBDFA1;">
                    		上一次操作人员：<?=$obj['insurance_claim']['oper_user6']?>
                    	</td>
                    </tr>
                    <?php 
                    	$pays = json_decode($obj['insurance_claim']['pay_text']);
                    	$transfer_amount = 0;
                    	foreach ($pays as $row){
                    		echo '<tr><th align="right">客户名称：</th><td>'.$row->customer_name.'</td></tr>';
                    		echo '<tr><th align="right">开户银行：</th><td>'.$row->bank_account.'</td>';
                    		echo '<th align="right">账户名：</th><td>'.$row->account_name.'</td>';
                    		echo '<th align="right">开户帐号：</th><td>'.$row->account_opening.'</td>';
                    		echo '</tr>';
                    		echo '<tr><th align="right">转账金额：</th><td>'.$row->transfer_amount.'</td>';
                    		echo '<th align="right">抵押金额：</th><td>'.$row->rent_amount.'</td>';
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
                    		7转账结案
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
                    		echo '<th align="right">转账凭证：</th><td><img src="'.$transfers[$index]->append_url.'"/></td>';
                    		echo '</tr>';
                    	}
                    ?>
	</table>
</div>
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