<div style="padding:10px 40px 20px 40px">  
    <form id="easyui-form-process-repair-assigned" class="easyui-form" method="post">
    <input type="hidden" name="id" value="<?php echo $id?>" />
        <div >
            <ul class="ulforform-resizeable">
            <!--  
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">指派部门</div>
                    <div class="ulforform-resizeable-input">
                      <select id="department" class="easyui-combobox"  style="width:160px;"  required="true"   missingMessage="请选择指派部门">
	                    	<?php //foreach ($departments as $department):?>
	                    	<option value="<?php //echo $department['id']?>" <?php //if($department['id'] ==5) echo 'selected';?>><?php //echo $department['name']?></option>
	                    	<?php //endforeach;?>
                   	 </select>
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                	<div class="ulforform-resizeable-title">指派对象</div>
					<div class="ulforform-resizeable-input">
						<select id="user" class="easyui-combobox" style="width:160px;"   name="assign_name" required="true"   missingMessage="请选择指派对象">
                   		 </select>
					</div>
                 </li>
             -->    
                 <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">地点</div>
                    <div class="ulforform-resizeable-input">
                      <select id="car_site" class="easyui-combobox" style="width:160px;"  required="true"   missingMessage="请选择提车地点">
                      		<option value=''></option>
                      		<?php if($car_site):?>
	                    	<?php foreach ($car_site as $v):?>
	                    	<?php if($v['parent_id']==0 && $v['name']):?>
	                    	<option value="<?php echo $v['id']?>" ><?php echo $v['name']?></option>
	                    	<?php endif;?>
	                    	<?php endforeach;?>
	                    	<?php endif;?>
                   	 </select>
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                	<div class="ulforform-resizeable-title">指派对象</div>
					<div class="ulforform-resizeable-input">
						<select id="user" class="easyui-combobox"  data-options="editable: false"style="width:160px;"   name="assign_name" required="true"   missingMessage="请选择指派对象">
                   		 </select>
					</div>
                 </li>
                 
                 
                <li class="ulforform-resizeable-group-single">
                    <div class="ulforform-resizeable-title">补充说明</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-textbox" style="height:100px;width:470px;"   name="assign_remark"   data-options="multiline:true" prompt="200字符以内。"
                        validType="length[200]"/>
                    </div>
                </li>
            </ul>
        </div>
    </form>
</div>
<script>
$(function(){
	 $('#car_site').combobox({
        panelHeight:150,
        //editable: false,
        onSelect: function(rec){
        	$('#user').combobox('clear');
        	var data =[];
        	<?php foreach ($car_site as $key=>$val):?>
       	  	if(rec.value == '<?php echo $val['parent_id'];?>'){
           	 // var data =[
           	 // 	 	 {text:'<?php //echo $val['user_name']?>',value:'<?php //echo $val['user_name']?>'},
           	 // 	];
        	 var a = {text:'<?php echo $val['user_name']?>',value:'<?php echo $val['user_name']?>'};
           	 data.push(a);
       	  	}
       	  	 <?php endforeach;?>
    		if(rec.value == ''){
      		 var data = [{text:'',value:''},];
    		}
    		$('#user').combobox('loadData',data);
        },
	 	onHidePanel: function() {
         var valueField = $(this).combobox("options").valueField;
         var val = $(this).combobox("getValue");  //当前combobox的值
         var allData = $(this).combobox("getData");   //获取combobox所有数据
         var result = true;      //为true说明输入的值在下拉框数据中不存在
         for (var i = 0; i < allData.length; i++) {
             if (val == allData[i][valueField]) {
                 result = false;
             }
         }
         if (result) {
             $(this).combobox("clear");
         }
     } 
	});
});
</script>
<!--  
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
	        url: "<?php //echo yii::$app->urlManager->createUrl(['process/repair/get-user']); ?>",
	        cache: false,
	        dataType : "json",
	        data:{department_id:department_id},
	        success: function(data){
	        	$("#user").combobox("loadData",data);
	          }
	     });
     
}
</script>-->