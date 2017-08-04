<div style="padding:10px 40px 20px 40px">
    <form id="easyui-form-process-car-tiche-site-form" class="easyui-form" method="post">
    <input type="hidden" name="id" value="<?php echo $result['id'];?>" />
    <input type="hidden" name="step_id"  value="<?php echo $result['step_id'];?>"/>
     <input type="hidden" name="template_id" value="<?php echo $result['template_id'];?>" />
        <table cellpadding="5">
        
        	<tr>
        		<td>提车需求车辆：</td>
        		<td>
        		<?php foreach ($car_types as $k=>$v):?>
                        <?php echo $k?> : <?php echo $v.'辆';?>
                 <?php endforeach;?>  
        		</td>
        	</tr>
        	<tr>
        	  <td>提车地点分配：</td>
        	  <td>
        	  	 <select class="easyui-combobox" data-options="editable:false,onSelect: function(rec){
        	  	 	$('#user_id1').combobox('clear');
		       	  	<?php foreach ($site_users as $key=>$site_user):?>
		       	  	if(rec.value == '<?php echo $key;?>'){
			       	  var data =[
			       	  		 {text:'',value:''},
			       	  	 	<?php foreach ($site_user as $val):?>
			       	  	 	 {text:'<?php echo $val['name']?>',value:'<?php echo $val['user_id']?>'},
			       	  	 	<?php endforeach;?>
			       	  	];
		       	  	}
		       	  	 <?php endforeach;?>	
		   	  	 
		   	  	  $('#user_id1').combobox('loadData',data);
		   	  	 }"
        	  	   id="site1" name="site[]" required="true"    style="width:100px" missingMessage="请选择提车地点"
	        	  	  
        	  	 >
        	  	 	<option value=""></option>
        	  	 	<?php foreach ($tiche_site as $site):?>
        	  	 		<option value="<?php echo $site['id']; ?>"><?php echo $site['name']?></option>
        	  	 	<?php endforeach;?>
        	  	 </select>
        	  	 <select class="easyui-combobox" id="user_id1"    name="user_id[]" data-options="editable:false" style="width:100px"  required="true" missingMessage="请选择提车地点负责人" >
		             		<option value=""></option>
		          </select>
	        	  <select class="easyui-combobox"   id="brand_type1"   name="brand_type[]" data-options="editable:false"  style="width:180px" required="true" missingMessage="请选择要提取的车型品牌" >
		             <option value=""></option>
		             <?php foreach ($brand_type as $val):?>
		                <option value="<?php echo $val?>"><?php echo $val?></option>
					 <?php endforeach;?>
	              </select>
	             <input id="car_number1"  class="easyui-textbox" name="car_number[]" required="true" style="width:50px"  missingMessage="请输入提车数量"/>
             </td>
        	</tr>
            <tr>
            	<td></td>
               <td> <input id="add" type="button" value="增加" onclick="add_car()" data-value="2" /></td>
            </tr>
            
            <tr>
            	<td> 补充说明：</td>
                <td>
                	 <input class="easyui-textbox" style="width:450px;height:80px;"   name="tiche_remark"   data-options="multiline:true" prompt="200字符以内。如果没有补充，请留空。"
                        validType="length[200]"/>
                </td>
            </tr>
        </table>
    </form>
</div>
<script>    	  	 
//增加
function add_car()
{
 	var data = $("#add").attr('data-value');
	//提车地点
	var html ='<tr><td></td><td><select class="easyui-combobox"   id="site'+data+'" name="site[]" required="true"   style="width:100px" missingMessage="请选择提车地点">';
	var option = '<option value=""></option>';
	<?php foreach ($tiche_site as $site):?>
	  option +='<option value="<?php echo $site['id']; ?>"><?php echo $site['name']?></option>';
	<?php endforeach;?>
	//提车负责人
	html = html+option+'</select> <select class="easyui-combobox" id="user_id'+data+'"    name="user_id[]" data-options="editable:false" style="width:100px"  required="true" missingMessage="请选择提车地点负责人" ><option value=""></option></select>';
	
	html = html+' <select class="easyui-combobox"   id="brand_type'+data+'"   name="brand_type[]" data-options="editable:false"  style="width:180px"   required="true" missingMessage="请选择要提取的车型品牌" >';
	//车型品牌
	var option = '<option value=""></option>';
	<?php foreach ($brand_type as $val):?>
	  option += '<option value="<?php echo $val?>"><?php echo $val?></option>';
	<?php endforeach;?>
	html = html+option+'</select> <input id="car_number'+data+'"  class="easyui-textbox" name="car_number[]" required="true" style="width:50px"  missingMessage="请输入提车数量"/>';
	
	//移除按钮
	html += ' <input type="button" value="移除" onclick="del('+data+')" /></td></tr>';
	html +='</select> <select id="car_type'+data+'"  name="car_type[]" required="true"  onchange ="search('+data+')"  ></select> <input id="search'+data+'" class="easyui-textbox" name="car_number[]" required="true" missingMessage="请输入需求数量"/> <!--<input  type="button" value="查询库存" onclick="search('+data+')" />--> <input type="button" value="移除" onclick="del('+data+')" /><span id="inventory'+data+'" style="color:red"></span></td></tr>';

	$("#add").parent().parent().before(html);
	$("#add").attr('data-value',parseInt(data)+1);

	//easyUI 插件样式初始化
	$("#site"+data).combobox({
		editable:false,
		onSelect: function(rec){
	  	 	$('#user_id'+data).combobox('clear');
       	  	<?php foreach ($site_users as $key=>$site_user):?>
       	  	if(rec.value == '<?php echo $key;?>'){
	       	  var lodadata =[
	       	  		 {text:'',value:''},
	       	  	 	<?php foreach ($site_user as $val):?>
	       	  	 	 {text:'<?php echo $val['name']?>',value:'<?php echo $val['user_id']?>'},
	       	  	 	<?php endforeach;?>
	       	  	];
       	  	}
       	  	 <?php endforeach;?>	
   	  	 
   	  	  $('#user_id'+data).combobox('loadData',lodadata);
   	  	 }

	});
	$("#user_id"+data).combobox({});
	$("#brand_type"+data).combobox({});
	$("#car_number"+data).textbox({});
	
	
	//$(this).prev(".selected")
	//alert('123');
}
//移除
function del(id)
{
	//alert($("car_brand"+id).parent().parent('tr'));
$("#site"+id).parent().parent().remove();
}
</script>