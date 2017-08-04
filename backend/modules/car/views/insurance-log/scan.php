    <?php 
    	$types = array(1=>'交强险',2=>'商业险',3=>'其它险');
    ?>
    <div title="车辆基本信息" style="padding:15px">
        <table cellpadding="6" cellspacing="0" align="center"  width="100%" border="0">
            <tr>
                <td align="right" width="13%">车牌号：</td>
                <td width="20%"><?php echo $data['plate_number']; ?></td>
                <td align="right" width="13%">车辆品牌：</td>
                <td width="20%"><?php echo $data['brand_name']; ?></td>
                <td align="right" width="13%">车辆型号：</td>
                <td width="20%"><?php echo $data['car_model_name']; ?></td>
            </tr>
            <tr>
                <td align="right" width="13%">类型：</td>
                <td width="20%"><?=$types[$data['type']]; ?></td>
                <td align="right" width="13%">保险公司：</td>
                <td width="20%"><?php echo $data['insurer_company_name']; ?></td>
                <td align="right" width="13%">保费合计：</td>
                <td width="20%"><?php echo $data['money_amount']; ?></td>
            </tr>
            <tr>
                <td align="right" width="13%">起保时间：</td>
                <td width="20%"><?=date('Y-m-d',$data['start_date'])?></td>
                <td align="right" width="13%">终保时间：</td>
                <td width="20%"><?=date('Y-m-d',$data['end_date'])?></td>
                <td align="right" width="13%">保期倒计时：</td>
                <td width="20%"><?php echo $data['_end_date']; ?></td>
            </tr>
            <?php
            	if($data['type']==2 || $data['type']==3){
            ?>
            <tr>
                <td align="right" width="13%">险种：</td>
                <td colspan="5">
                	<?php 
                		if($data['insurance_text']){
                			$insurance_objs = json_decode($data['insurance_text']);
                			foreach ($insurance_objs as $row){
                				if($row[0]){
                					echo "{$row[0]}({$row[1]}元),";
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
                <td align="right" width="13%">备注：</td>
                <td colspan="5"><?=$data['note']?></td>
            </tr>
            <?php
                $appends = json_decode($data['append_urls']);
                foreach ($appends as $key => $value) {
            ?>
                <tr>
                    <td>保单附件
                    </td>
                    <td colspan='5'><a href='<?php echo yii::$app->urlManager->createUrl(['car/insurance-log/test']);?>&url=<?=$value?>' target='_b'><img src="<?php echo yii::$app->urlManager->createUrl(['car/insurance-log/test']);?>&url=<?=$value?>" width='100' height='50'/></a></td>
                </tr>
            <?php
                }
            ?>
            <tr>
                <td align="right" width="13%"><input type="button" value="保单附件" onclick="CarInsuranceLogScan.download()"/></td>
            </tr>
            
        </table>
    </div>
<script type="text/javascript">
  //下载附件
  	var CarInsuranceLogScan = new Object();
    CarInsuranceLogScan.download  = function(){
		window.open("<?php echo yii::$app->urlManager->createUrl(['car/insurance-log/download']);?>&id=<?=$data['id']?>&type=<?=$data['type']?>");
    }
</script>
    