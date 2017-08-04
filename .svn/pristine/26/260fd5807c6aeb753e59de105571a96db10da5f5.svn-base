<form id="easyui-form-car-type-lease-edit" class="easyui-form">
  	<input type="hidden" name="id" value="<?php echo $row_result['id']?>" />
  <div
        class="easyui-panel"
        title="基本参数"
        style="width:100%;margin-bottom:5px;"
        closable="false"
        collapsible="false"
        minimizable="false"
        maximizable="false"
        border="false"
    >
   
        <table border="0" cellpadding="5" cellspacing="0">
            
            <tr>
				<td><div style="width:100px;text-align:right;">车辆品牌：</div></td>
				<td ><?php echo $row_result['brand_name']; ?></td>
				
				<td><div style="width:100px;text-align:right;">车辆型号：</div></td>
				<td ><?php echo $row_result['car_model']; ?></td>
               
            </tr>
			<tr>
				<td><div style="width:100px;text-align:right;">省份：</div></td>
				<td ><?php echo $row_result['province_name']; ?></td>
				
				<td><div style="width:100px;text-align:right;">城市：</div></td>
				<td ><?php echo $row_result['region_name']; ?></td>
			              
                
            </tr>			
             <tr>
				<td><div style="width:100px;text-align:right;">车辆运营公司：</div></td>
				<td ><?php echo $row_result['operating_company_name']; ?></td>
            </tr>
            
            
        </table>
	<div class="easyui-tabs" id="tt"> 
	<div title="长租" style="padding:20px;">
		<table cellpadding="5" cellspacing="0">            
            <tr>               
				<td><div style="width:100px;text-align:right;">启用状态：</div></td>
				<td ><?php echo $row_result['is_enable_long']==1?'开启':'未开启'; ?></td>               
            </tr>
			<tr>               
				<td><div style="width:100px;text-align:right;">月租金：</div></td>
				<td ><?php echo $row_result['month_price_long']; ?>（￥）</td>   
				<td><div style="width:100px;text-align:right;">年租金：</div></td>
				<td ><?php echo $row_result['year_price_long']; ?>（￥）</td>  				
            </tr>          
              <tr>
			  <td><div style="width:100px;text-align:right;">租车押金：</div></td>
				<td ><?php echo $row_result['deposit_long']; ?>（￥）</td>  		
				<td><div style="width:100px;text-align:right;">违章押金：</div></td>
				<td ><?php echo $row_result['wz_deposit_long']; ?>（￥）</td> 
            </tr>
			</tr>
            <tr>
                <td align="right"><div style="width:70px;">提车点1</div></td>
                 <td>
                    <select
                        class="easyui-combobox"
                        required="true"
                        style="width:160px;"
                        name="service_site_long[]"
                        editable="false"
						disabled=true 
                        data-options="panelHeight:'auto'"
						id="a"
						
                    >
                        <option value="">请选择</option>
                        <?php foreach($config['service_site'] as $val){ ?>
                        <option <?php if ($sites_long[0] == $val['value']){echo "selected";}?> value="<?php echo $val['value']; ?>"><?php echo $val['text']; ?></option>
                        <?php } ?>
                    </select>
                </td>			
            </tr>			
			<tr><td colspan="3"><!-- 描述 --></td></tr>
			<?php 
			$k = 2;
			$next_long = count($sites_long) + $k - 1;
			unset($sites_long[0]);
			foreach ($sites_long as $the_one_long) { ?>		
			<tr>
                <td align="right"><div style="width:70px;">提车点<?php echo $k;?></div></td>
                 <td>
                    <select
                        class="easyui-combobox"
                        required="true"
                        style="width:160px;"
                        name="service_site_long[]"
                        editable="false"
						disabled=true 
                        data-options="panelHeight:'auto'"
						id="service_site_long<?php echo ($k-1); ?>"						
                    >
                        <option value="">请选择</option>
                        <?php foreach($config['service_site'] as $val){ ?>
                        <option <?php if ($the_one_long == $val['value']){echo "selected";}?> value="<?php echo $val['value']; ?>"><?php echo $val['text']; ?></option>
                        <?php } ?>
                    </select>
                </td>							
            </tr>
			<?php $k++;}?>
			 <tr >
            		<td ></td>
             </tr>
			<tr><td colspan="3"><!-- 描述 --></td></tr>
        </table>
    </div>
	
	<div title="分时" style="padding:20px;">
		<table cellpadding="5" cellspacing="0">            
            <tr>  
				<td><div style="width:100px;text-align:right;">启用状态：</div></td>
				<td ><?php echo $row_result['is_enable_time']==1?'开启':'未开启'; ?></td> 
            </tr>
            <tr>
				<td><div style="width:100px;text-align:right;">起步里程：</div></td>
				<td ><?php echo $row_result['starting_mileage']; ?>（km）</td>   
				
               <td><div style="width:100px;text-align:right;">起步价格：</div></td>
				<td ><?php echo $row_result['starting_price']; ?>（￥）</td> 			
            </tr>
              <tr>
			    
				<td><div style="width:100px;text-align:right;">租车押金：</div></td>
				<td ><?php echo $row_result['deposit_time']; ?>（￥）</td> 	

				<td><div style="width:100px;text-align:right;">违章押金：</div></td>
				<td ><?php echo $row_result['wz_deposit_time']; ?>（￥）</td> 				
            </tr>	
			<tr>
				<td><div style="width:100px;text-align:right;">保险费用：</div></td>
				<td ><?php echo $row_result['insurance_expense']; ?>（￥）</td> 
				
				<td><div style="width:100px;text-align:right;">不计免赔：</div></td>
				<td ><?php echo $row_result['insurance_bjmp']; ?>（￥）</td> 
				
            </tr>			
            <tr>
                <td align="right"><div style="width:70px;">提车点1</div></td>
                 <td>
                    <select
                        class="easyui-combobox"
                        required="true"
                        style="width:160px;"
                        name="service_site_time[]"
                        editable="false"
						disabled=true
                        data-options="panelHeight:'auto'"
						id="a"
						
                    >
                        <option value="">请选择</option>
                        <?php foreach($config['service_site'] as $val){ ?>
						
                        <option <?php if ($sites[0] == $val['value']){echo "selected";}?> value="<?php echo $val['value']; ?>"><?php echo $val['text']; ?></option>
                        <?php } ?>
                    </select>
                </td>			
            </tr>	
			<tr><td colspan="2"><span style="font-size:18px;font-weight:bold;">时租设置</span></td></tr>
			<tr>
				<td><div style="width:100px;text-align:right;">时租金：</div></td>
				<td ><?php echo $row_result['time_price']; ?>（￥）</td> 
			</tr>
			<tr>
				<td><div style="width:100px;text-align:right;">超时费用：</div></td>
				<td ><?php echo $row_result['time_out_price_']; ?>（￥）</td> 				
				
            </tr>			
			<tr><td colspan="2"><span style="font-size:18px;font-weight:bold;">日租设置</span></td></tr>
			<tr>
				<td><div style="width:100px;text-align:right;">日租金：</div></td>
				<td ><?php echo $row_result['day_price']; ?>（￥）</td>                
			</tr>
			<tr>
				<td><div style="width:100px;text-align:right;">超时费用：</div></td>
				<td ><?php echo $row_result['day_out_price_h']; ?>元/小时</td>  
				<td ><?php echo $row_result['day_out_price_d']; ?>元/天</td>  			
            </tr>				
			<tr><td colspan="3"><!-- 描述 --></td></tr>
			<?php 
			$j = 2;
			$next = (count($sites)-1) + $j;
			unset($sites[0]);
			foreach ($sites as $the_one) { ?>				
			<tr>
                <td align="right"><div style="width:70px;">提车点<?php echo $j; ?></div></td>
                 <td>
                    <select
                        class="easyui-combobox"
                        required="true"
                        style="width:160px;"
                        name="service_site_time[]"
                        editable="false"
						disabled=true
                        data-options="panelHeight:'auto'"
						id="service_site_time<?php echo ($j-1); ?>"
						
                    >
                        <option value="">请选择</option>
                        <?php foreach($config['service_site'] as $val){ ?>
                        <option <?php if ($the_one == $val['value']){echo "selected";}?> value="<?php echo $val['value']; ?>"><?php echo $val['text']; ?></option>
                        <?php } ?>
                    </select>
                </td>				
            </tr>
			<?php $j++;}?>
			 
			<tr><td colspan="3"><!-- 描述 --></td></tr>
        </table>
    </div>
	<div title="菜鸟" style="padding:20px;">
		<table cellpadding="5" cellspacing="0">            
            <tr> 
				<td><div style="width:100px;text-align:right;">启用状态：</div></td>
				<td ><?php echo $row_result['is_enable_cainiao']==1?'开启':'未开启'; ?></td> 
            </tr>
            <tr>
				<td><div style="width:100px;text-align:right;">运营库存：</div></td>
				<td ><?php echo $row_result['stock_number']; ?>（台）</td> 
				
				<td><div style="width:100px;text-align:right;">月租金：</div></td>
				<td ><?php echo $row_result['month_price']; ?>（￥）</td> 
				
            </tr>
              <tr>
			  <td><div style="width:100px;text-align:right;">租车押金：</div></td>
				<td ><?php echo $row_result['deposit']; ?>（￥）</td> 
				
				<td><div style="width:100px;text-align:right;">违章押金：</div></td>
				<td ><?php echo $row_result['wz_deposit']; ?>（￥）</td> 
				
            </tr>
			</tr>
            <tr>
                <td align="right"><div style="width:70px;">提车点1</div></td>
                 <td>
                    <select
                        class="easyui-combobox"
                        required="true"
                        style="width:160px;"
                        name="service_site[]"
                        editable="false"
						disabled=true
                        data-options="panelHeight:'auto'"
						id="a"
						
                    >
                        <option value="">请选择</option>
                        <?php foreach($config['service_site'] as $val){ ?>
                        <option <?php if ($sites_cainiao[0] == $val['value']){echo "selected";}?> value="<?php echo $val['value']; ?>"><?php echo $val['text']; ?></option>
                        <?php } ?>
                    </select>
                </td>			
            </tr>			
			<tr><td colspan="3"><!-- 描述 --></td></tr>
			<?php 
			$i = 2;
			$next_cainiao = count($sites_cainiao) + $i - 1;
			unset($sites_cainiao[0]);
			foreach ($sites_cainiao as $the_one_cainiao) { ?>		
			<tr>
                <td align="right"><div style="width:70px;">提车点<?php echo $i;?></div></td>
                 <td>
                    <select
                        class="easyui-combobox"
                        required="true"
                        style="width:160px;"
                        name="service_site[]"
                        editable="false"
						disabled=true
                        data-options="panelHeight:'auto'"
						id="service_site<?php echo ($i-1); ?>"						
                    >
                        <option value="">请选择</option>
                        <?php foreach($config['service_site'] as $val){ ?>
                        <option <?php if ($the_one_cainiao == $val['value']){echo "selected";}?> value="<?php echo $val['value']; ?>"><?php echo $val['text']; ?></option>
                        <?php } ?>
                    </select>
                </td>							
            </tr>
			<?php $i++;}?>			 
			<tr><td colspan="3"><!-- 描述 --></td></tr>
        </table>
    </div>
</div>


   
</form>
<script>
  var faultInfo = <?php echo json_encode($row_result); ?>;
  $('#easyui-form-car-type-lease-edit').form('load',faultInfo);

	</script>

