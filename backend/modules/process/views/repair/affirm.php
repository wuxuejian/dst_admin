<div style="padding:10px 40px 20px 40px">  
    <form id="easyui-form-process-repair-confirmed" class="easyui-form" method="post">
    <input type="hidden" name="id" value="<?php echo $id?>" />
        <div >
            <ul class="ulforform-resizeable">
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">已听取录音</div>
                    <div class="ulforform-resizeable-input">
                     <!-- <select  class="easyui-combobox"  data-options="editable:false;"  name="is_voice"   style="width:160px;"  required="true"   missingMessage="请选择指派部门">
	                    	 <option value="1">是</option>
	                    	<option value="0">否</option> 
                   	 </select>-->
                   	 <input id="is_voice"  name="is_voice" style="width:160px;" required="true" />
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                	<div class="ulforform-resizeable-title">已电话回访</div>
					<div class="ulforform-resizeable-input">
					<!--	<select  class="easyui-combobox" style="width:160px;" data-options="editable:false"    name="is_visit" required="true"   missingMessage="请选择指派对象">
                   			<option value="1">是</option>
	                    	<option value="0">否</option>
                   		 </select> -->
                   		  <input id="is_visit"  name="is_visit" style="width:160px;" required="true" />
					</div>
                 </li>
                 <li class="ulforform-resizeable-group">
                	<div class="ulforform-resizeable-title">需要出外勤</div>
					<div class="ulforform-resizeable-input">
						<!--  <select id="is_waiqing"   class="easyui-combobox" style="width:160px;" data-options="editable:false"    name="is_attendance" required="true"   missingMessage="请选择指派对象">
                   		 	<option value="1">是</option>
                   		 	<option value="0">否</option>
                   		 </select>-->
                   		 <input id="is_waiqing"  name="is_attendance" style="width:160px;" required="true" />
					</div>
                 </li>
                 
                 <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">电话回访时间</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-datetimebox" style="width:160px;"   name="visit_time" missingMessage="请输入电话回访时间"/>
                    </div>
                </li>
                <li class="ulforform-resizeable-group-single is_waiqing">
                    <div class="ulforform-resizeable-title">携带设备</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-textbox" style="width:470px;height:50px;" data-options="multiline:true"   name="carry"   />
                    </div>
                </li>
               
				 
               <li class="ulforform-resizeable-group is_waiqing">
                	<div class="ulforform-resizeable-title">需要申请用车</div>
					<div class="ulforform-resizeable-input">
						<!--  <select id="is_use"   class="easyui-combobox" style="width:160px;" data-options="editable:false"    name="is_use_car" required="true"   missingMessage="请选择指派对象">
                   		 	<option value="0">否</option>
                   		 	<option value="1">是</option>
                   		 </select>-->
                   		 <input id="is_use"  name="is_use_car" style="width:160px;"  />
					</div>
                 </li>
                 <li class="ulforform-resizeable-group is_waiqing is_use">
                    <div class="ulforform-resizeable-title">外勤用车车牌号</div>
                    <div class="ulforform-resizeable-input">
                        <select  class="easyui-combobox" style="width:160px;"   name="use_car_no"   missingMessage="请选择指派对象">
                   		<?php foreach ($cars as $car):?>
                   			<option value="<?php echo $car['plate_number']?>"><?php echo $car['plate_number']?></option>
                   		<?php endforeach;?>
                   		</select>
                    </div>
                </li>
                <li class="ulforform-resizeable-group-single">
                    <div class="ulforform-resizeable-title">补充说明</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-textbox" style="width:470px;height:100px;"   name="confirm_remark"   data-options="multiline:true" prompt="200字符以内。"
                        validType="length[200]"/>
                    </div>
                </li>
 
                
            </ul>
        </div>
    </form>
</div>
<script>
$(function(){

	 $('#is_voice').combobox({
		 valueField:'value',
	     textField:'text',
	     editable: false,
	     panelHeight:'auto',
	     data: [{"value": '1',"text": '是'},{"value": '0',"text": '否'}]
	});
	 $('#is_visit').combobox({
		 valueField:'value',
	     textField:'text',
	     editable: false,
	     panelHeight:'auto',
	     data: [{"value": '1',"text": '是'},{"value": '0',"text": '否'}]
	});	 
	
	 $('#is_waiqing').combobox({
		 valueField:'value',
	     textField:'text',
	     editable: false,
	     panelHeight:'auto',
	     data: [{"value": '1',"text": '是'},{"value": '0',"text": '否'}],
		    onChange:function(newValue,oldValue){
		    	is_waiqing(newValue);	
		    }
	});
	 $('#is_use').combobox({
		 valueField:'value',
	     textField:'text',
	     editable: false,
	     panelHeight:'auto',
	     data: [{"value": '1',"text": '是'},{"value": '0',"text": '否'}],
		    onChange:function(newValue,oldValue){
		    	is_use(newValue);
		    }
	});
	 
	 //初始化
	 is_waiqing($("#is_waiqing").val());
});

function is_waiqing(is_waiqing)
{
	if(is_waiqing == 0)
	{ 
		//alert('123');
		$(".is_waiqing").css('display','none');
	}else{
		$(".is_waiqing").css('display','block');
	}
	is_use($("#is_use").val());
}


function is_use(is_use)
{
	if(is_use == 0)
	{ 
		//alert('123');
		$(".is_use").css('display','none');
	}else{
		$(".is_use").css('display','block');
	}
}

</script>