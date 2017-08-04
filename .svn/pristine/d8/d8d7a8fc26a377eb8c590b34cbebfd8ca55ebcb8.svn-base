<script src="js/jquery.ajaxSubmit.js"></script>
<div style="padding:10px 40px 20px 40px">
    <form id="easyui-form-process-car-proceeds" class="easyui-form" method="post" enctype ="multipart/form-data">
    <input type="hidden" name="id" />
    <input type="hidden" name="step_id"/>
     <input type="hidden" name="template_id"/>
        <table cellpadding="5">
       		 <tr>
                <td colspan="3">提示:请根据销售人员与客户的协商情况，确认该批次车辆租金的收款方式.</td>
            </tr>
            <?php $i=0; foreach ($car_type as $k=>$v):?>
            <tr>
            	<td><?php if($i==0):?> 月租金：<?php endif;?></td>
            	<td>
                	<input  class="easyui-textbox"  value="<?php echo $k;?>"  disabled />
                </td>
             	<td>
             		<input  class="easyui-textbox"  name="rent[<?php echo $k?>]" required="true"  missingMessage="请填写月租金" />
             	</td>
            </tr>
            <?php $i++; endforeach;?>
            <tr>
            	<td> 保证金总额：</td>
            	<td colspan="2">
                	<input  class="easyui-textbox"  name="margin"  required="true"  style="width:356px" missingMessage="请填写保证金总额" />
                </td>
            </tr>	
            <tr>
                <td> 收款方式：</td>
                <td colspan="2">
                	 <p><input type="radio" name="proceeds" value="收到租金后再交车" />收到租金后再交车</p>
                	 <p><input type="radio" name="proceeds" value="先交车再收取租金" />先交车再收取租金</p>
                	 <p><input type="radio" name="proceeds" value="随车去客户公司收款" />随车去客户公司收款</p>
                	 <p><input type="radio" name="proceeds" value="other" />其他方式：<input class="easyui-textbox"  name="other" style="width:270px" /></p>
                </td>
            </tr>
			<tr>
            	<td> 客户转账银行水单：</td>
            	<td colspan="2">
                	<input name="transfer_accounts_img" type="file"/>
                </td>
            </tr>	
        </table>
    </form>
</div>
<script>
$('#easyui-form-process-car-proceeds').form('load',<?php echo json_encode($result); ?>);
</script>