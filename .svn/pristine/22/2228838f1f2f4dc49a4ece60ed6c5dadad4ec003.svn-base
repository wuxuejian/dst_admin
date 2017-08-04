<div style="padding:10px 40px 20px 40px">
    <form id="easyui-form-process-car-contract" class="easyui-form" method="post">
     <input type="hidden" name="id" />
     <input type="hidden" name="step_id" />
     <input type="hidden" name="template_id" />
        <table cellpadding="5">
            <tr>
                <td> 合同编号：</td>
                <td>
                    <select class="easyui-combobox" id="contract_number"   name="contract_number" required="true"  >
                    		<?php foreach ($contracts as $contract):?>
	                  	<option value="<?php echo $contract['number']?>"><?php echo $contract['number']?></option>
	                  		<?php endforeach;?>
                    </select>
                </td>
            </tr>
            <tr>
                <td> 客户名称：</td>
                <td>
                    <input  type="text" id="name" name="name" required="true" missingMessage="请输入客户名称"/>
                </td>
            </tr>
            <tr>
                <td> 应收保证金(元)：</td>
                <td>
                    <input  type="text" id="margin" name="margin" required="true" missingMessage="请输入已收保证金"/>
                </td>
            </tr>
            <tr>
                <td> 应收租金(元)：</td>
                <td>
                    <input class="easyui-textbox" name="rent" required="true" missingMessage="请输入已收租金"/>
                </td>
            </tr>
            
         <?php $i=1; foreach ($result['car_types'] as $k=>$car_type):?>
        	<tr>
                <td><?php if($k==0):?>车辆品牌：<?php endif;?></td>
                <td>
                    <select id="car_brand<?php echo $i;?>"   name="car_brand[]" required="true"   onchange ="select_type(<?php echo $i;?>)">
	                    	<?php foreach ($cars as $key=>$car):?>
	                  		    <option value="<?php echo $key;?>" <?php if($car_type['car_brand']==$key) echo 'selected';?>><?php echo $key;?></option>
	                  		<?php endforeach;?>
                    </select>
                    <select id="car_type<?php echo $i;?>"   name="car_type[]" required="true"  >
                    	<?php foreach ($cars[$car_type['car_brand']] as $val): ?>
                    	<option value="<?php echo $val?>" <?php if($car_type['car_type']==$val) echo 'selected';?>><?php echo $val?></option>
                    	<?php endforeach;?>
                    </select>
                    <input class="easyui-textbox" name="car_number[]" value="<?php echo $car_type['car_number'];?>" required="true" missingMessage="请输入需求数量"/>
                    <?php if($k!=0):?><input type="button" value="移除" onclick="del(<?php echo $i;?>)" /><?php endif;?>
                </td>
            </tr>
        <?php $i++; endforeach;?>
            <tr>
            	<td></td>
               <td> <input id="add" type="button" value="增加其他车型品牌" onclick="add_car()" data-value="<?php echo $i;?>" /></td>
            </tr>
            
            
            <tr>
                <td> 提车方式：</td>
                <td>
                    <select class="easyui-combobox" id="tc_way"   name="extract_way" required="true"  >

                    	<option value="1">自提</option>
						<option value="2" >送车上门</option>
                    </select>
                </td>
            </tr>
	            <tr class="zt_div"  style="display:none">
	                <td> 自提客户姓名：</td>
	                <td>
	                    <input type="text"  name="extract_name" required="true" missingMessage="请输入自提客户姓名"/>
	                </td>
	            </tr>
	            <tr class="zt_div" style="display:none">
	                <td> 自提客户联系电话：</td>
	                <td>
	                    <input type="text"  name="extract_mobile" required="true" missingMessage="请输入自提客户联系电话" />
	                </td>
	            </tr>
	            <tr class="zt_div" style="display:none">
	                <td> 自提客户证件号码：</td>
	                <td>
	                	 <input type="text" name="extract_zhengjian" required="true" missingMessage="请输入自提客户证件号码" />
	                </td>
	            </tr>
            <tr>
                <td> 补充说明：</td>
                <td>
                	 <textarea rows="5" cols="21" name="contract_remark"></textarea>
                </td>
            </tr>
        </table>
    </form>
</div>
<div id="easyui-dialog-process-car-contract-upload"></div>
<script>
$('#easyui-form-process-car-contract').form('load',<?= json_encode($result); ?>);
$(function(){
	 $('#contract_number').combobox({
		    onChange:function(newValue,oldValue){
		    	select_contract(newValue);
		    }
	});
	 $('#tc_way').combobox({
		    onChange:function(newValue,oldValue){
		    	tc_way(newValue);
		    }
	});
		
	 select_contract($("#contract_number").val());
	 tc_way($("#tc_way").val());

	 select_type(1);
})


function select_contract(value)
{
	if(value)
	{
		$.ajax({
			type: 'post',
			url: '<?php echo yii::$app->urlManager->createUrl(['process/car/get-name']); ?>',
			data: {number: value},
			dataType: 'json',
			success: function(data){
				if(data.status){
					$("#name").val(data.name);
					$("#margin").val(data.bail);
				}
			}
		});
	}else{
		$("#name").val('');
		$("#margin").val('');
	}
}

function tc_way(value)
{
	if(value)
	{
		if(value == 1)
		{
			$(".zt_div").css('display','table-row');
		}else{
			$(".zt_div").css('display','none');
		}
	}else{
		$(".zt_div").css('display','none');
	}
}


function select_type(id)
{
	var car_brand = $("#car_brand"+id).val();
	if(car_brand)
	{
		var str ='';
		<?php foreach ($cars as $k=>$val):?>
		<?php foreach ($val as $v):?>
		if(car_brand == "<?php echo $k?>")
		{
			 str +='<option value="<?php echo $v?>"><?php echo $v?></option>';
		}
		<?php endforeach;?>
		<?php endforeach;?>
		$("#car_type"+id).empty();
		$("#car_type"+id).append(str);
	}
}

function add_car()
{
 	var data = $("#add").attr('data-value');
	var html ='<tr><td></td><td><select id="car_brand'+data+'"   name="car_brand[]" required="true"   onchange ="select_type('+data+')">';

	<?php foreach ($cars as $key=>$car):?>
	    html += '<option value="<?php echo $key;?>"><?php echo $key;?></option>';
	<?php endforeach;?>

	html +='</select> <select id="car_type'+data+'"  name="car_type[]" required="true"  ></select> <input class="easyui-textbox" name="car_number[]" required="true" missingMessage="请输入需求数量"/> <input type="button" value="移除" onclick="del('+data+')" /></td></tr>';

	$("#add").parent().parent().before(html);
	$("#add").attr('data-value',parseInt(data)+1);
	select_type(data);
	//$(this).prev(".selected")
	//alert('123');
}
function del(id)
{
	//alert($("car_brand"+id).parent().parent('tr'));
$("#car_brand"+id).parent().parent().remove();
}
</script>
	