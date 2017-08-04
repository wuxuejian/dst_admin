 <table cellpadding="6" cellspacing="2" style="width:100%;" border="0">
    <tr>
        <th align="right" width="10%">状态：</th>
        <td width="23%">
            <?php 
                $states = array(
                    0=>'',1=>'1.客户退车，等待销售沟通',2=>'2.确定退车，等待领导审批',3=>'3.同意退车，等待售后验车',4=>'4.已验车，等待商务核算',5=>'5.已核算，等待审批',
                		6=>'6.核算审批通过，等待财务确认',7=>'7.财务确认，终止合同书',8=>'8.合同终止，完成退车',
                		20=>'2.客户取消退车',21=>'3.退车申请被驳回',22=>'6.核算驳回');
                echo @$states[$obj['state']];
            ?>
        </td>
    	<th align="right" width="10%">客户名称：</th>
        <td width="23%">
            <?php 
                if($obj['company_name']){
                    echo $obj['company_name'];
                }else if($obj['id_name']){
                    echo $obj['id_name'];
                }else {
                    echo $obj['other_customer_name'];
                }
            ?>
        </td>
        <th align="right" width="10%">客户电话：</th>
        <td width="23%">
             <?=$obj['customer_tel'];?>
        </td>
    </tr>
    <tr>
        <th align="right"  width="10%">客户地址：</th>
        <td>
            <?php echo $obj['customer_addr']; ?>
        </td>
        <th align="right"  width="10%">退车原因：</th>
        <td>
            <?php echo $obj['back_cause']; ?>
        </td>
        <th align="right"  width="10%">预计还车时间：</th>
        <td>
            <?php echo $obj['back_time']; ?>
        </td>
    </tr>
    <tr>
        <th align="right"  width="10%"><font color="#ADADAD">退车意愿登记人：</font></th>
        <td>
            <font color="#ADADAD"><?php echo $obj['oper_user1']; ?></font>
        </td>
        <th align="right"  width="10%"><font color="#ADADAD">退车意愿登记时间：</font></th>
        <td>
            <font color="#ADADAD"><?php echo $obj['oper_time1']; ?></font>
        </td>
    </tr>
    <tr>
        <th align="right"  width="10%">客户退车申请附件：</th>
        <td>
        	<?php 
        		if($obj['append_url1']){
        	?>
            <a href="<?=$obj['append_url1']?>" target="_b"><img src="<?=$obj['append_url1']?>" width='50' height='50'/></a>
            <?php }?>
        </td>
    </tr>
    
    <?php 
    	$break_contract_types = array('','合同未到期','合同已到期');
    	$contracts = json_decode($obj['contract_text']);
    	if(!$contracts){
    		$contracts = array();
    	}
    	foreach ($contracts as $row){
    ?>
    	<tr>
	    	<th align="right"  width="10%">合同编号：</th>
	        <td>
	            <?php 
	                echo @$row->contract_number; 
	            ?>
	        </td>
		</tr>
    	<tr>
	    	<th align="right"  width="10%">合同违约情况：</th>
	        <td>
	            <?php 
	                echo @$break_contract_types[$row->break_contract_type]; 
	            ?>
	        </td>
	        <th align="right"  width="10%">合同时间：</th>
	        <td>
	            <?php echo $row->contract_time; ?>
	        </td>
	        <th align="right"  width="10%">违约金金额：</th>
	        <td>
	            <?php echo $row->break_contract_money; ?>
	        </td>
	    </tr>
    <?php 
    	}
    ?>
    <tr>
        <th align="right"  width="10%">退车车辆列表：</th>
        <td colspan="5">
            <?php 
                if(@!$obj['cars']){
                    $obj['cars'] = array();
                }
                $cars_num = 0;
                foreach ($obj['cars'] as $row) {
                	$cars_num++;
                    echo $row['plate_number'].'('.@$config['car_model_name'][$row['car_model']]['text'].')， ';
                }
            ?>
        </td>
    </tr>
    <tr>
        <th align="right"  width="10%">退车数量：</th>
        <td>
            <?=$cars_num?>
        </td>
    </tr>
    <?php 
    	if($obj['state'] == 20){
    ?>
    <tr>
        <th align="right"  width="10%">取消退车原因：</th>
        <td>
            <?php echo $obj['cancel_back_cause']; ?>
        </td>
    </tr>
    <?php }?>
    
    <tr>
        <th align="right"  width="10%"><font color="#ADADAD">销售沟通确认人：</font></th>
        <td>
            <font color="#ADADAD"><?php echo $obj['oper_user2']; ?></font>
        </td>
        <th align="right"  width="10%"><font color="#ADADAD">销售沟通确认时间：</font></th>
        <td>
            <font color="#ADADAD"><?php echo $obj['oper_time2']; ?></font>
        </td>
    </tr>
    
    <tr>
        <th align="right"  width="10%">同意/驳回：</th>
        <td>
            <?php
                $is_rejects = array('','同意','驳回');
                echo @$is_rejects[$obj['is_reject']]; 
            ?>
        </td>
        <?php 
        	if($obj['is_reject']==2){
        ?>
        <th align="right"  width="10%">驳回意见：</th>
        <td>
            <?php echo $obj['reject_cause']; ?>
        </td>
        <?php }?>
    </tr>
    <tr>
        <th align="right" width="10%">车辆违章信息：</th>
        <td colspan="5">
            <?php
                $wz_list = array();
                if($obj['wz_text']){
                    $wz_list = json_decode($obj['wz_text']);
                }
                foreach ($wz_list as $row) {
                	if(!$row->lists){
                		continue;
                	}
                    echo $row->hphm.'：';
                    if($row->lists){
                        foreach ($row->lists as $index=>$row1) {
                        	if($index>0){
                        		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                        	}
                            echo $row1->date.','.$row1->area.','.$row1->act.','.$row1->fen.'分,'.$row1->money.'元,'.($row1->handled==1?'处理':'未处理').'<br/> ';
                        }
                    }else {
                        echo '无';
                    }
                    echo '<br/>';
                }
                echo '<font color="#ADADAD">*无违章车辆不显示在此列表</font><br/>';
            ?>
        </td>
    </tr>
    <?php 
    	$repair_types = array('','客户自修','公司修理','无需维修');
    	$damages = json_decode($obj['damage_text']);
    	if(!$damages){
    		$damages = array();
    	}
    	foreach ($damages as $row){
    ?>
    	<tr>
	    	<th align="right"  width="10%">定损车辆：</th>
	        <td>
	            <?php 
	            	foreach ($obj['damage_cars'] as $damage_car){
	            		if(@$row->car_id == $damage_car['id']){
	            			echo $damage_car['plate_number'];
	            			break;
	            		}
	            	}
	                 
	            ?>
	        </td>
		</tr>
    	<tr>
	    	<th align="right"  width="10%">定损金额：</th>
	        <td>
	            <?= $row->damage_money; ?>
	        </td>
	        <th align="right"  width="10%">维修类型：</th>
	        <td>
	            <?php echo @$repair_types[$row->repair_type];?>
	        </td>
	        <th align="right"  width="10%">损失部位：</th>
	        <td>
	            <?php echo $row->position; ?>
	        </td>
	    </tr>
		<tr>
			<th width="10%"> </th>
	        <td colspan="5">
	            <?php
					if($row->img_url){
						$img_urls = explode(",",$row->img_url);
						foreach($img_urls as $row1){
							if($row1){
								echo "<a href='{$row1}' target='_b'><img src='{$row1}' width='50px' height='50px'/></a>&nbsp;&nbsp;";
							}
						}
					}				
				?>
	        </td>
	    </tr>
    <?php 
    	}
    ?>
    <tr>
        <th align="right"  width="10%"><font color="#ADADAD">售后验车人：</font></th>
        <td>
            <font color="#ADADAD"><?php echo $obj['oper_user4']; ?></font>
        </td>
        <th align="right"  width="10%"><font color="#ADADAD">售后验车时间：</font></th>
        <td>
            <font color="#ADADAD"><?php echo $obj['oper_time4']; ?></font>
        </td>
    </tr>
    <tr>
        <th align="right"  width="10%">违约金：</th>
        <td>
            <?php echo $obj['penalty_money']; ?>
        </td>
        <th align="right"  width="10%">押金：</th>
        <td>
            <?php echo $obj['foregift_money']; ?>
        </td>
        <th align="right"  width="10%">结算退还金额：</th>
        <td>
            <?php echo $obj['back_money']; ?>
        </td>
    </tr>
    <tr>
        <th align="right"  width="10%">退还时间：</th>
        <td>
            <?php echo $obj['back_time3']; ?>
        </td>
    </tr>
    <tr>
        <th align="right"  width="10%"><font color="#ADADAD">押金结算人：</font></th>
        <td>
            <font color="#ADADAD"><?php echo $obj['oper_user5']; ?></font>
        </td>
        <th align="right"  width="10%"><font color="#ADADAD">押金结算时间：</font></th>
        <td>
            <font color="#ADADAD"><?php echo $obj['oper_time5']; ?></font>
        </td>
    </tr>
    <tr>
        <th align="right"  width="10%">合同终止书附件：</th>
        <td>
            <a href="<?=$obj['append_url3']?>" target="_b"><img src="<?=$obj['append_url3']?>" width='50' height='50'/></a>
        </td>
        <th align="right"  width="10%">转账凭证：</th>
        <td>
            <a href="<?=$obj['append_url4']?>" target="_b"><img src="<?=$obj['append_url4']?>" width='50' height='50'/></a>
        </td>
        <th align="right"  width="10%">备注：</th>
        <td>
            <?php echo $obj['note8']; ?>
        </td>
    </tr>
    <tr>
        <th align="right"  width="10%"><font color="#ADADAD">签订合同终止书人：</font></th>
        <td>
            <font color="#ADADAD"><?php echo $obj['oper_user6']; ?></font>
        </td>
        <th align="right"  width="10%"><font color="#ADADAD">签订合同终止书时间：</font></th>
        <td>
            <font color="#ADADAD"><?php echo $obj['oper_time6']; ?></font>
        </td>
    </tr>
<!--     <tr> -->
<!--         <th align="right"  width="10%">已入库车辆列表：</th> -->
<!--         <td> -->
            <?php 
//                 if(@!$obj['storage_cars']){
//                     $obj['storage_cars'] = array();
//                 }
//                 foreach ($obj['storage_cars'] as $row) {
//                     echo $row['plate_number'].',';
//                 }
//             ?>
<!--         </td> -->
<!--     </tr> -->
    <tr>
        <th align="right"  width="10%"><font color="#ADADAD">确认车辆入库人：</font></th>
        <td>
            <font color="#ADADAD"><?php echo $obj['oper_user7']; ?></font>
        </td>
        <th align="right"  width="10%"><font color="#ADADAD">确认车辆入库时间：</font></th>
        <td>
            <font color="#ADADAD"><?php echo $obj['oper_time7']; ?></font>
        </td>
    </tr>
    <tr>
        <th align="right"  width="10%"><font color="#ADADAD">领导审批人：</font></th>
        <td>
            <font color="#ADADAD"><?php echo $obj['oper_user3']; ?></font>
        </td>
        <th align="right"  width="10%"><font color="#ADADAD">领导审批时间：</font></th>
        <td>
            <font color="#ADADAD"><?php echo $obj['oper_time3']; ?></font>
        </td>
    </tr>
    <tr>
        <th align="right"  width="10%"><font color="#ADADAD">黄总审批帐号：</font></th>
        <td>
            <font color="#ADADAD"><?php echo $obj['oper_user6']; ?></font>
        </td>
        <th align="right"  width="10%"><font color="#ADADAD">黄总审批时间：</font></th>
        <td>
            <font color="#ADADAD"><?php echo $obj['oper_time6']; ?></font>
        </td>
    </tr>
    
</table>