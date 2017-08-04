<div style="padding:10px 40px 20px 40px">
    <form id="easyui-form-process-car-add" class="easyui-form" method="post">
    <input type="hidden" name="id" />
        <table cellpadding="5">
        <?php $i=1; foreach ($result['car_types'] as $k=>$car_type):?>
        	<tr>
                <td><?php if($k==0):?>车辆品牌：<?php endif;?></td>
                <td colspan="3">
                    <select id="car_brand<?php echo $i;?>"   name="car_brand[]" required="true"   onchange ="select_type(<?php echo $i;?>)">
	                    	<?php foreach ($cars as $key=>$car):?>
	                  		    <option value="<?php echo $key;?>" <?php if($car_type['car_brand']==$key) echo 'selected';?>><?php echo $key;?></option>
	                  		<?php endforeach;?>
                    </select>
                    <select id="car_type<?php echo $i;?>"   name="car_type[]" required="true"   onchange ="search(<?php echo $i;?>)">
                    	<?php foreach ($cars[$car_type['car_brand']] as $val): ?>
                    	<option value="<?php echo $val?>" <?php if($car_type['car_type']==$val) echo 'selected';?>><?php echo $val?></option>
                    	<?php endforeach;?>
                    </select>
                    <input id="search<?php echo $i;?>"  class="easyui-textbox" name="car_number[]" value="<?php echo $car_type['car_number'];?>" required="true" missingMessage="请输入需求数量"/>
                    <!--  <input type="button" value="查询库存" onclick="search(<?php echo $i;?>)" />-->
                    <?php if($k!=0):?><input type="button" value="移除" onclick="del(<?php echo $i;?>)" /><?php endif;?>
                    <span id="inventory<?php echo $i;?>" style="color:red"></span>
                </td>
            </tr>
        <?php $i++; endforeach;?>
            <tr>
                <td> 提车时间：</td>
                <td>
                    <input class="easyui-datetimebox" name="extract_time" style="width: 173px" required="true" missingMessage="请输入提车时间" />
                </td>
                <td> 提车方式：</td>
                <td>
                    <select class="easyui-combobox"  name="extract_way" style="width: 173px;" data-options="editable:false"  required="true"   missingMessage="请选择提车方式">
	                    	<option value=""></option>
	                    	<option value="1">客户自提</option>
	                    	<option value="2">送车上门</option>
                   	</select>
                </td>
            </tr>
            
             <tr>
                <td> 合同编号：</td>
                <td>
                    <select class="easyui-combobox" id="contract_number" name="contract_number" style="width: 173px" required="true"  >
                   		 <option value='<?php echo $result['contract_number']?>' selected="selected"><?php echo $result['contract_number']?></option> 
                   	</select>
                </td>
                <td>订单编号：</td>
                	<td><input class="easyui-textbox" name="batch_no"  prompt="请填写订单号，如:01"   /></td>
            </tr>
            
            <tr>
                <td> 合同类型：</td>
                <td>
                    <select class="easyui-combobox" id="contract_type"  name="contract_type" style="width: 173px" data-options="editable:false"  required="true"   missingMessage="请选择合同类型">
	                    	<option value=""></option>
	                    	<option value="1">租赁合同</option>
	                    	<option value="2">试用协议</option>
	                    	<option value="3">虚拟合同</option>
                   	</select>
                </td>
                <td> 客户类型：</td>
                <td>
                     <select class="easyui-combobox"  id="customer_type"  name="customer_type" style="width: 173px" data-options="editable:false"  required="true"   missingMessage="请选择客户类型">
	                    	<option value=""></option>
	                    	<option value="1">企业客户</option>
	                    	<option value="2">个人客户</option>
                   	</select>
                </td>
            </tr>
            
            <tr>
                <td> 客户名称：</td>
                <td>
                    <select class="easyui-combobox"  id="name"  name="name" style="width: 173px" required="true"   >
                           <option value='<?php echo $result['name']?>' selected="selected"><?php echo $result['name']?></option> 
                   	</select>
                </td>
                
                <td> 申请人姓名：</td>
                <td>
                   	<input class="easyui-textbox" name="shenqingren"    />
                </td>
                
            </tr>
            
             <tr>
                <td> 电话：</td>
                <td>
                    <input class="easyui-textbox" name="tel"   validType="match[/((0\d{2,3})-)(\d{7,8})(-(\d{3,}))?$|1[3|4|5|7|8][0-9]\d{8}$/]" invalidMessage="电话、手机错误！" prompt="电话号码格式 区号-号码"   />
                </td>
            </tr>

           
        </table>
        <div style="margin-top:10px;border:1px solid #DDDDDD;padding:5px 5px;color:#666666 ">
        	【提示】请根据需求数量选择提车日期：10台以下，提前2个工作日；10-50台，3个工作日；50-100台，5个工作日；100台以上，10个工作日（工作日不含周六日及假期）。如需加急提车，请与车管及售后部门沟通确认。
        </div>
    </form>
</div>
<script>
$('#easyui-form-process-car-add').form('load',<?= json_encode($result); ?>);
$(function(){
	//var ajax_data =[];
	select_type(1);
	search(1);
	 //合同类型
/*	 $('#contract_type').combobox({
		    onChange:function(newValue,oldValue){
		    	var customer_type = $('#customer_type').combobox('getValue');
		    	$.ajax({
		 	        type: "GET",
		 	        url: "<?php //echo yii::$app->urlManager->createUrl(['process/car/get-customer']); ?>",
		 	        cache: false,
		 	        dataType : "json",
		 	        data:{contract_type:newValue,customer_type:customer_type},
		 	        success: function(data){
		 	        	$('#name').combobox('setValue','');
		 	        	$('#contract_number').combobox('setValue','');

		 	        	$("#name").combobox("loadData",data);
		 	        	$("#contract_number").combobox("loadData",data);
		 	        	ajax_data = data;
		 	          }
		 	     });	
		    }
	});*/
	//客户类型	 
/*	 $('#customer_type').combobox({
		    onChange:function(newValue,oldValue){
				var contract_type = $('#contract_type').combobox('getValue');
		    	 $.ajax({
		 	        type: "GET",
		 	        url: "<?php //echo yii::$app->urlManager->createUrl(['process/car/get-customer']); ?>",
		 	        cache: false,
		 	        dataType : "json",
		 	        data:{contract_type:contract_type,customer_type:newValue},
		 	        success: function(data){
		 	        	$('#name').combobox('setValue','');
		 	        	$('#contract_number').combobox('setValue','');

		 	        	$("#name").combobox("loadData",data);
		 	        	$("#contract_number").combobox("loadData",data);
		 	        	ajax_data = data;
		 	          }
		 	     });	
		    }
	});*/
	//客户名称
/*	 $('#name').combobox({ 
	      //url:'itemManage!categorytbl', 
	     // editable:false, //不可编辑状态
	      cache: false,
	    //  panelHeight: 'auto',//自动高度适合
	      valueField:'name',   
	      textField:'name',
	      data: [{"value": "<?php //echo $result['name']?>","text": '不限'}],
	      onChange:function(newValue,oldValue){
		    	var current_number = [];
		    	$.each(ajax_data,function(i, value){
					if(value['name'] == newValue){
						current_number.push(value);
					}
			    });

				$("#contract_number").combobox("setValue",'');
			    $("#contract_number").combobox("loadData",current_number);
		    }
	 });*/
	 //合同编号
	 $('#contract_number').combobox({
	     /* cache: false,
	      valueField:'number',   
	      textField:'number',
		    onChange:function(newValue,oldValue){
		    	$.each(ajax_data,function(i, value){
					if(value['number'] == newValue){
						$("#name").combobox("setValue",value['name']);	

					}
			    });
			   
		    }*/
		 onChange:function(newValue,oldValue){
			 $.each(<?php echo json_encode($contracts);?>,function(i, value){
					if(value['number'] == newValue){
						//客户类型
						if(value['customer_type'] == 'COMPANY')
						{
							$("#customer_type").combobox("setValue",1);
						}else{
							$("#customer_type").combobox("setValue",2);
						}	

						//合同类型
						if(value['1'])
						{
							$("#contract_type").combobox("setValue",1);
						}else{
							$("#contract_type").combobox("setValue",2);
						}	
						$.ajax({
							type: 'post',
							url: '<?php echo yii::$app->urlManager->createUrl(['process/car/get-name']); ?>',
							data: {number: newValue},
							dataType: 'json',
							success: function(data){
								if(data.status){
									$("#name").combobox("setValue",data.name);
								}
							}
						});
					}
			    });
    		}
		});	
})
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
		search(id);
	}
}

function add_car()
{
	var data = $("#add").attr('data-value');
	var html ='<tr><td></td><td colspan="3"><select id="car_brand'+data+'"   name="car_brand[]" required="true"   onchange ="select_type('+data+')">';

	<?php foreach ($cars as $key=>$car):?>
	    html += '<option value="<?php echo $key;?>"><?php echo $key;?></option>';
	<?php endforeach;?>

	html +='</select> <select id="car_type'+data+'"  name="car_type[]" required="true"  onchange ="search('+data+')"  ></select> <input id="search'+data+'" class="easyui-textbox" name="car_number[]" required="true" missingMessage="请输入需求数量"/> <!--<input  type="button" value="查询库存" onclick="search('+data+')" />--> <input type="button" value="移除" onclick="del('+data+')" /><span id="inventory'+data+'" style="color:red"></span></td></tr>';

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

function search(id)
{
	var car_brand = $("#car_brand"+id).val();
	var car_type = $("#car_type"+id).val();

	
	$.ajax({
		type: 'post',
		url: "<?php echo yii::$app->urlManager->createUrl(['process/car/search-number']); ?>&id=0",
		data: {car_brand:car_brand,car_model:car_type},
		dataType: 'json',
		success: function(data){
			//$.messager.alert('车辆库存',car_brand+car_type+' 库存'+data.count+'辆，仅供参考！','info');
			//$("#inventory"+id).html(car_brand+car_type+' 库存'+data.count+'辆，仅供参考！');
			$("#inventory"+id).html(car_brand+car_type+' 当前可提'+data.count+'辆，库存锁定'+data.lock_count+'辆仅供参考！');
		}
	});
}
</script>
	