<div style="padding:10px 40px 20px 40px">
    <form id="easyui-form-process-car-tiche-assign-form" class="easyui-form" method="post">
    <input type="hidden" name="id" value="<?php echo $result['id'];?>" />
    <input type="hidden" name="step_id"  value="<?php echo $result['step_id'];?>"/>
     <input type="hidden" name="template_id" value="<?php echo $result['template_id'];?>" />
        <table cellpadding="5">
        
        <?php 
         $tiche_site = json_decode($tiche_site,true);
         //兼容旧版本
         if(json_last_error() != JSON_ERROR_NONE):
         ?>
            <tr>
                <td> 提车地点：</td>
                <td>
                	 <input class="easyui-textbox"    style="width: 450px"  required="true"   missingMessage="请选择提车点" data-options="editable:false" value="<?php echo $tiche_site?>" />
                </td>

            </tr>
            <tr>
           	 <td> 站点负责人：</td>
            	<td>
                      <select id="department" class="easyui-combobox"  style="width:200px;"  required="true"   missingMessage="请选择指派部门">
	                    	<?php foreach ($departments as $department):?>
	                    	<option value="<?php echo $department['id']?>" <?php if($department['id'] ==5) echo 'selected';?>><?php echo $department['name']?></option>
	                    	<?php endforeach;?>
                   	 </select>
                   	 <select id="user" class="easyui-combobox" name="tiche_manage_user" style="width:242px;"    >
                	 </select>

            	</td>
            </tr>
        <?php else:?> 
       	  <?php foreach ($tiche_site as $k=>$v):?>
	           <tr>
	           	 <td><?php if($k==0):?>指定站点负责人：<?php endif;?></td>
	             <td>
	        	  	 <select class="easyui-combobox"    name="site[]" required="true" data-options="editable:false"   style="width:100px" readonly missingMessage="请选择提车地点">
	        	  	 	<?php foreach ($sites as $site):?>
        	  	 		<option value="<?php echo $site['id']; ?>" <?php if($v['site'] ==$site['id'] ) echo 'selected';?>><?php echo $site['name']?></option>
        	  	 	<?php endforeach;?>
	        	  	 </select>
		        	  <select class="easyui-combobox"    name="brand_type[]" data-options="editable:false" style="width:180px" readonly   required="true" missingMessage="请选择要提取的车型品牌" >
			             <option value="<?php echo $v['brand_type']?>"><?php echo $v['brand_type']?></option>
		              </select>
		             <input   class="easyui-textbox" name="car_number[]"  value="<?php echo $v['car_number']?>" style="width:50px" readonly  required="true" missingMessage="请输入提车数量"/>
		             <select class="easyui-combobox"     name="user_id[]" data-options="editable:false" style="width:100px"  required="true" missingMessage="请选择提车地点负责人" >
		             		<option value=""></option>
		             	<?php if(@$site_users[$v['site']] ):?>
		             	<?php foreach (@$site_users[$v['site']] as $val):?>
		             		<option value="<?php echo $val['user_id']?>"><?php echo $val['name'];?></option>
		             	<?php endforeach;?>
		             	<?php endif;?>
			             
		              </select>
	             </td>
	           </tr>
           <?php endforeach;?>
        <?php endif;?>    
            <tr>
            	<td> 补充说明：</td>
                <td>
                	 <input class="easyui-textbox" style="width:450px;height:80px;"   name="assign_remark"   data-options="multiline:true" prompt="200字符以内。如果没有补充，请留空。"
                        validType="length[200]"/>
                </td>
            </tr>
        </table>
    </form>
</div>
<script>
$(function(){
	 $('#department').combobox({
		    onChange:function(newValue,oldValue){
			    $("#user").combobox('clear');
		    	select_user(newValue);
		    }
	});
	 $('#user').combobox({ 
	      //url:'itemManage!categorytbl', 
	     // editable:false, //不可编辑状态
	      cache: false,
	    //  panelHeight: 'auto',//自动高度适合
	      valueField:'name',   
	      textField:'name'
	 });
	 //初始化
	 select_user($("#department").val());
});

function select_user(department_id)
{
	 $.ajax({
	        type: "POST",
	        url: "<?php echo yii::$app->urlManager->createUrl(['process/repair/get-user']); ?>",
	        cache: false,
	        dataType : "json",
	        data:{department_id:department_id},
	        success: function(data){
	        	$("#user").combobox("loadData",data);
	          }
	     });
     
}
</script>