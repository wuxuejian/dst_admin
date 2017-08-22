    <?php 
    	$types = array(1=>'交强险',2=>'商业险',3=>'其它险');
    ?>
    <div title="车辆基本信息" style="padding:15px">
        
        <table cellpadding="6" cellspacing="0" align="center"  width="100%" border="0">
            <tr>
                <h3>车辆保单详情</h3>
            </tr>
            <tr>
                <td align="right" width="13%">车牌号：</td>
                <td width="20%"><?php echo $data['plate_number']; ?></td>
                <td align="right" width="13%">车辆品牌：</td>
                <td width="20%"><?php echo $data['brand_name']; ?></td>
                <td align="right" width="13%">车辆型号：</td>
                <td width="20%"><?php echo $data['car_model_name2']; ?></td>
            </tr>
            <!-- <tr>
                <td align="right" width="13%">类型：</td>
                <td width="20%"><?=$types[$data['type']]; ?></td>
                <td align="right" width="13%">保险公司：</td>
                <td width="20%"><?php echo $data['insurer_company_name']; ?></td>
                <td align="right" width="13%">保费合计：</td>
                <td width="20%"><?php echo $data['money_amount']; ?></td>
            </tr> -->
            <tr>
                <td align="right" width="20%">车辆运营公司：</td>
                <td width="20%"><?=$data['oper_name']; ?></td>
                <td align="right" width="20%">机动车所有人：</td>
                <td width="20%"><?php echo $data['owner_name']; ?></td>
                <td align="right" width="13%">一级状态：</td>
                <td width="20%"><?php echo $data['car_status_name']; ?></td>
               
                        
            </tr>
            <tr>
                <td align="right" width="13%">归属客户：</td>
                <td width="20%"><?php echo $data['customer_name']; ?></td>
            </tr>
            
            
        </table>
        <table cellpadding="6" cellspacing="0" align="center"  width="100%" border="0">
            <?php
                if($data['type']==1){
            ?>
            <tr>
                <h3>交强险信息</h3>
            </tr>
            <?php   
                } 
            ?>
            <?php
                if($data['type']==2){
            ?>
            <tr>
                <h3>商业险信息</h3>
            </tr>
            <?php   
                } 
            ?>
            <tr>
                <td align="right" width="13%">保单号：</td>
                <td width="20%"><?=$data['number']; ?></td>
                <td align="right" width="13%">保险公司：</td>
                <?php if($data['type']==1){?>
                <td width="20%"> <?php echo @$data['insurance_compulsory']['insurer_company_name']; ?></td>
                <?php }?>
                <?php if($data['type']==2){?>
                <td width="20%"> <?php echo @$data['insurance_business']['insurer_company_name']; ?></td>
                <?php }?>
                <?php if($data['type']==3){?>
                <td width="20%"> <?php echo @$data['insurance_other']['insurer_company_name']; ?></td>
                <?php }?>
            </tr>
            <tr>
                <?php if($data['type'] !=3){?>
                <td align="right" width="13%">使用性质：</td>
                <td width="20%">
                <?php
                    if($data['use_nature'] == 1){
                            echo  '企业营运货车';
                        }else if($data['use_nature'] == 2) {
                            echo '企业非营运货车';   
                        }else if($data['use_nature'] == 3) {
                            echo '企业非营运客车';   
                        }else if($data['use_nature'] == 4) {
                            echo '企业营运客车';   
                        } else if($data['use_nature'] == 5) {
                            echo '个人家庭自用车';   
                        }else if($data['use_nature'] == 6) {
                            echo '特种车';   
                        } 
                ?>
                <?php } ?>
                </td>
                <td align="right" width="13%">开始时间：</td>
                <td width="20%"><?=date('Y-m-d',$data['start_date'])?></td>
                <td align="right" width="13%">结束时间：</td>
                <td width="20%"><?=date('Y-m-d',$data['end_date'])?></td>
                <!-- <td align="right" width="13%">保期倒计时：</td>
                <td width="20%"><?php echo $data['_end_date']; ?></td> -->
            </tr>
            <tr>
                <td align="right" width="13%">保险费金额</td>
                <td width="20%"><?php echo $data['money_amount']; ?></td>
            </tr>
            <?php
                if($data['type']==2 || $data['type']==3){
            ?>


            <table border="1px solid" cellspacing="0" cellpadding="7" style="width:80%;" type="checkbox"  >
            <tr>
                <td>承保险种：</td><td>保险费合计(元)：</td>
                
            </tr>
            
               
            <?php 
               /* if($data['insurance_text']){*/
                    $insurance_objs = json_decode($data['insurance_text']);
                    /**/
                    foreach ($insurance_objs as $row){
                       // if($row[0]){
                            //echo "<td>{$row[0]}</td><td>({$row[1]}元)</td>".'<br>';
                        //}
                    
              /*  }*/
            ?>
          
            <tr>
                <td><?=$row[0]?></td><td><?=$row[1]?></td>
            </tr>

           <?php } ?>



            <?php   
                } 
            ?>
             </table>



            <tr>
                <td align="right" width="13%">备注：</td>
                <td colspan="5"><?=$data['note']?></td>
                <td></td>
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
                <td align="right" width="13%"><input type="button" value="下载" onclick="CarInsuranceLogScan.download()"/></td>
            </tr>
        </table>
    </div>

   <!--  批单展示 -->
   <?php if($data2) {?>
   <HR style="FILTER: alpha(opacity=100,finishopacity=0,style=3)" width="100%" color=#95B8E7 SIZE=3>
   
        <?php $i=0;foreach($data2 as $key => $dat):?>
       
            <?php if($dat['id'] != null && $dat['type'] == 1){?> <!--  批单展示(交强险批单) -->
          
           <!--  <input type="hidden" name="ii" value="<?php echo $i?>" /> -->
        <div title="车辆基本信息" style="padding:15px">
        <table cellpadding="6" cellspacing="0" align="center"  width="100%" border="0">
            <tr>
                <td align="right" width="13%">批单号：</td>
                <td width="20%"><?php echo $dat['number']; ?></td>
                <td align="right" width="13%">使用性质：</td>
                <td width="20%">
                    <?php 
                        if($dat['use_nature'] == 1){
                            echo  '企业营运货车';
                        }else if($dat['use_nature'] == 2) {
                            echo '企业非营运货车';   
                        }else if($dat['use_nature'] == 3) {
                            echo '企业非营运客车';   
                        }else if($dat['use_nature'] == 4) {
                            echo '企业营运客车';   
                        } else if($dat['use_nature'] == 5) {
                            echo '个人家庭自用车';   
                        }else if($dat['use_nature'] == 6) {
                            echo '特种车';   
                        } 
                    ?>
                </td>
            </tr>
            <tr>
                <td align="right" width="13%">批增金额：</td>
                <td width="20%"><?=$dat['money_amount_add']; ?></td>
                <td align="right" width="13%">批减金额：</td>
                <td width="20%"><?=$dat['money_amount_minus']; ?></td>
                <td align="right" width="20%">批改后保费金额：</td>
                <td width="20%"><?php echo $dat['money_amount']; ?></td>
            </tr>
            <tr>
                <td align="right" width="13%">开始时间：</td>
                <td width="20%"><?=date('Y-m-d',$dat['start_date'])?></td>
                <td align="right" width="13%">结束时间：</td>
                <td width="20%"><?=date('Y-m-d',$dat['end_date'])?></td>
<!--                 <td align="right" width="13%">保期倒计时：</td>
                <td width="20%"><?php echo $dat['_end_date']; ?></td> -->
            </tr>
            <!-- <tr>
                <td align="right" width="13%">批改原因：</td>
                <td colspan="5"><?=$data['note']?></td>
            </tr> -->
            <?php
                if($dat['type']==2 || $dat['type']==3){
            ?>
            <tr>
                <td align="right" width="13%">险种：</td>
                <td colspan="5">
                    <?php 
                        if($dat['insurance_text']){
                            $insurance_objs = json_decode($dat['insurance_text']);
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
                <td align="right" width="13%">批改原因：</td>
                <td colspan="5"><?=$dat['note']?></td>
            </tr>
            <?php if($appends){?>
            <?php
                $appends = json_decode($dat['append_urls']);
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
            <?php } ?>
            <tr>
                <td align="right" width="13%"><input type="button" value="下载" onclick="CarInsuranceLogScan.download2(<?php echo $i;?>)"/></td>
            </tr>
            
        </table>
    </div>
            <?php } ?>



    <?php if($dat['id'] != null && $dat['type'] == 2){?> <!--  批单展示(商业险批单) -->
        <div title="车辆基本信息" style="padding:15px">
        <table cellpadding="6" cellspacing="0" align="center"  width="100%" border="0">
            <tr>
                <td align="right" width="13%">批单号：</td>
                <td width="20%"><?php echo $dat['number']; ?></td>
                <td align="right" width="13%">使用性质：</td>
                <td width="20%">
                    <?php
                        if($dat['use_nature'] == 1){
                            echo  '企业营运货车';
                        }else if($dat['use_nature'] == 2) {
                            echo '企业非营运货车';   
                        }else if($dat['use_nature'] == 3) {
                            echo '企业非营运客车';   
                        }else if($dat['use_nature'] == 4) {
                            echo '企业营运客车';   
                        } else if($dat['use_nature'] == 5) {
                            echo '个人家庭自用车';   
                        }else if($dat['use_nature'] == 6) {
                            echo '特种车';   
                        } 
                    ?>
                </td>
            </tr>
            
            <tr>
                <td align="right" width="13%">开始时间：</td>
                <td width="20%"><?=date('Y-m-d',$dat['start_date'])?></td>
                <td align="right" width="13%">结束时间：</td>
                <td width="20%"><?=date('Y-m-d',$dat['end_date'])?></td>
<!--                 <td align="right" width="13%">保期倒计时：</td>
                <td width="20%"><?php echo $dat['_end_date']; ?></td> -->
            </tr>
            
            <?php
                if($dat['type']==2 || $dat['type']==3){
            ?>

           <table border="1px solid" cellspacing="0" cellpadding="7" style="width:80%;" type="checkbox"  >
            <tr>
                <td>承保险种：</td><td>增/改：</td><td>批改金额：</td><td>批改后保险费小计(元)：</td>   
            </tr>  
            <?php 
                $insurance_objs = json_decode($dat['insurance_text']);
                foreach ($insurance_objs as $row){
            ?>
            <tr>
                <td><?=$row[0]?></td>
                <td>
                    <?php 
                        if($row[1]==1){
                            echo '批增';
                         }elseif($row[1]==2){
                            echo '批减';
                         } else {
                            echo '不变';
                         }
                    ?>
                </td>
                <td><?=$row[2]?></td>
                <td><?=$row[3]?></td>
            </tr>
            <?php } ?>
           
             </table>


            <?php   
                } 
            ?>
            <table>
                <tr>
                    <td></td>
                    <td align="right" width="13%">批改后保费金额：</td>
                    <td width="20%"><?php echo $dat['money_amount']; ?></td>
                </tr>
                <tr>
                    <td align="left" width="13%">批改原因：</td>
                    <td colspan="5"><?=$dat['note']?></td>
                </tr>
                <!-- <tr>
                    <td align="right" width="13%">备注：</td>
                    <td colspan="5"><?=$dat['note']?></td>
                </tr> -->
           </table>
           <!--  <?php if($appends){?> -->
            <?php
                $appends = json_decode($dat['append_urls']);
                //echo '1q1q';

                
              /*  if($appends){*/
                foreach (@$appends as $key => $value) {
            ?>
                <tr>
                    <td>保单附件
                    </td>
                    <td colspan='5'><a href='<?php echo yii::$app->urlManager->createUrl(['car/insurance-log/test']);?>&url=<?=$value?>' target='_b'><img src="<?php echo yii::$app->urlManager->createUrl(['car/insurance-log/test']);?>&url=<?=$value?>" width='100' height='50'/></a></td>
                </tr>
                <?php
                    }
                ?>
            
         <!--    <?php } ?> -->
            <tr>
                <td align="right" width="13%"><input type="button" value="下载" onclick="CarInsuranceLogScan.download2(<?php echo $i;?>)"/></td>
            </tr>
            
        </table>
    </div>
    <?php } ?>



        <?php $i++;endforeach;?>
    <?php }?>
<script type="text/javascript">
  //下载附件
  	var CarInsuranceLogScan = new Object();

    CarInsuranceLogScan.download  = function(){
		window.open("<?php echo yii::$app->urlManager->createUrl(['car/insurance-log/download']);?>&id=<?=$data['id']?>&type=<?=$data['type']?>");
    }
   /* <?php if($data2) {?>
        <?php foreach($data2 as $key => $data):?>
        CarInsuranceLogScan.download  = function(){
            <?php $data2['type']=1; ?>
        window.open("<?php echo yii::$app->urlManager->createUrl(['car/insurance-log/download']);?>&id=<?=$data['id']?>&type=<?=$data2['type']?>");
    }
        <?php endforeach;?>
    <?php }?>*/

    //<?php if($data2) {?>
        

    CarInsuranceLogScan.download2  = function(ii){
        
                
                 window.open("<?php echo yii::$app->urlManager->createUrl(['car/insurance-log/download2']);?>&id=<?=$dat['insurance_id']?>&type=<?=$data['type']?>&ii="+ii);
            
      
    }
        
    //<?php }?>
</script>
    