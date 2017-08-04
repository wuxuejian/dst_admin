<!-- <form
    id="easyui-form-car-office-car-register-scan"
    class="easyui-form"
    style="padding:10px;" method="post"
> -->
 <div
        class="easyui-panel"
        title="基本信息"
        style="width:100%;margin-bottom:5px;"
        closable="false"
        collapsible="false"
        minimizable="false"
        maximizable="false"
        border="false"
    >
        <table cellpadding="8" cellspacing="0">        
            <tr>
                <td><div style="width:100px;text-align:right;">需求单号：</div></td>
                <td ><?php echo $data['apply_no']; ?></td>
                <td><div style="width:300px;text-align:right;">下单时间：</div></td>
                <td ><?php echo $data['order_time']; ?></td>
            </tr>
                <td><div style="width:100px;text-align:right;">取车城市：</div></td>
                <td><?php echo $data['city']; ?></td>
                <td><div style="width:300px;text-align:right;">预计取车时间：</div></td>
                <td><?php echo $data['es_take_car_time']; ?></td>
            </tr>
             </tr>
                <td><div style="width:100px;text-align:right;">企业名称：</div></td>
                <td><?php echo $data['company_name']; ?></td>
                <td><div style="width:300px;text-align:right;">联系人姓名：</div></td>
                <td><?php echo $data['contact_name']; ?></td>
            </tr>   
			</tr>
                <td><div style="width:100px;text-align:right;">联系人手机：</div></td>
                <td><?php echo $data['contact_mobile']; ?></td>
                <td><div style="width:300px;text-align:right;">电子邮件：</div></td>
                <td><?php echo $data['contact_email']; ?></td>
            </tr>						
        </table>
		<table cellpadding="8" style="margin-left:100px" cellspacing="0" border="1">        
            <tr>
                <td><div style="width:70px;text-align:right;">车型需求</div></td>             
                <td><div style="width:200px;text-align:right;">品牌型号</div></td>
                <td><div style="width:100px;text-align:right;">需求数量</div></td>
                <td><div style="width:100px;text-align:right;">预计租期</div></td>              
            </tr> 
			<?php $i = 1;if ($data['car_models']) {
					foreach ($data['car_models'] as $model){?>
             </tr>
               <td><div style="width:70px;text-align:right;"><?php echo $i;?></div></td>             
                <td><div style="width:200px;text-align:right;"><?php echo $model->car_type_id;?></div></td>
                <td><div style="width:100px;text-align:right;"><?php echo $model->num;?></div></td>
                <td><div style="width:100px;text-align:right;"><?php echo $model->use_time;?></div></td>
            </tr>   
			<?php }$i++;}?>
							
        </table>
  </div>
<div
        class="easyui-panel"
        title="业务进度"
        style="width:100%;margin-bottom:5px;"
        closable="false"
        collapsible="false"
        minimizable="false"
        maximizable="false"
        border="false"
    >
        <table cellpadding="8" cellspacing="0">
            <tr>
                <td><div style="width:100px;text-align:right;">业务主管:</div></td>
                <td ><?php echo $data['manager_name']; ?></td>
                <td><div style="width:300px;text-align:right;">销售专员：</div></td>
                <td ><?php echo $data['sale_name']; ?></td>               			
            </tr>
			<tr>
			 <td><div style="width:100px;text-align:right;">回访状态：</div></td>
                <td ><?php 
					if ($data['call_back_status'] == 1) {
						echo "已回访";
					} else {
						echo "未回访";
					}				

				?></td>	
			</tr>
             <tr>
                <td><div style="width:100px;text-align:right;">业务主管:</div></td>
                <td ><?php echo $data['call_back_man_note']; ?></td>               
            </tr>
            <tr>
                <td><div style="width:100px;text-align:right;">销售专员：</div></td>
                <td ><?php echo $data['call_back_sale_note']; ?></td>               
            </tr>           
        </table>
</div>


<!-- </form> -->


<!-- *************************************************************************************************** -->
