<div style="padding:10px 40px 20px 40px">
    <form id="easyui-form-process-car-jiaoche-replace-form" class="easyui-form" method="post" enctype ="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo $result['id'];?>"/>
        <table cellpadding="5">
        	<tr>
                <td> 原车牌号：</td>
                <td>
                    <input class="easyui-textbox"   required="true"  value="<?php echo $result['car_no'];?>"  readonly/>
                </td>
            </tr>
            <tr>
                <td> 品牌车型：</td>
                <td>
                    <input class="easyui-textbox"   required="true"  value="<?php echo $result['car_type'];?>"  readonly/>
                </td>
            </tr>
            <tr>
                <td>替换为：</td>
                <td>
                    <select  class="easyui-combobox"  id="car_no"   name="car_no" required="true"  style="width:150px;" missingMessage="要确保提车时，交强险与商业险在有效期内，并且行驶证有效期一致">
	                        <?php //foreach ($result as $val):?>
	                        <!--  <option value="<?php //echo $val['plate_number']?>"><?php //echo $val['plate_number']?></option>-->
	                        <?php //endforeach;?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>行驶证年审日期：</td>
                <td>
                     <input id="vehicle_license"name="vehicle_license" required="true"  readonly/>
                </td>
            </tr>
            <tr>
                <td>道路运输证年审日期：</td>
                <td>
                     <input id="road_transport"name="road_transport" required="true" readonly/>
                </td>
            </tr>
            <tr>
                <td>交强险有效期：</td>
                <td>
                     <input id="insurance"name="insurance" required="true"  readonly/>
                </td>
            </tr>
            <tr>
                <td>商业险有效期：</td>
                <td>
                     <input id="business_risks"name="business_risks" required="true"  readonly  />
                </td>
            </tr>
            <tr>
                <td>监控数据更新日期：</td>
                <td>
                     <input id="monitoring"name="monitoring" required="true"  readonly/>
                </td>
            </tr>
            <tr>
                <td>随车工具：</td>
                <td>
                    <select  class="easyui-combobox"    name="certificate" required="true" data-options="editable:false"   style="width:174px">
	                  <option value=''></option>
	                  <option value="1">已备齐</option>
	                  <option value="0">未备齐</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>电量情况：</td>
                <td>
                    <select class="easyui-combobox"   name="electricity" required="true"  data-options="editable:false"  style="width:174px"  missingMessage="SOC电量必须大于80%">
	                  <option value=''></option>
	                  <option value="1">充足</option>
	                  <option value="0">不足</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>随车证件：</td>
                <td>
                    <input type="checkbox"    name="follow_car_card[]"  value="行驶证" />行驶证
                    <input type="checkbox"    name="follow_car_card[]"  value="道路运输证" />道路运输证
                </td>
            </tr>
            <tr>
                <td>随车资料：</td>
                <td>
                    <input type="checkbox"    name="follow_car_data[]"  value="车型介绍资料" />车型介绍资料
                    <input type="checkbox"    name="follow_car_data[]"  value="充电操作规范" />充电操作规范
                    <input type="checkbox"    name="follow_car_data[]"  value="车辆管理规范" />车辆管理规范
                    <input type="checkbox"    name="follow_car_data[]"  value="驾驶员管理制度" />驾驶员管理制度
                    <input type="checkbox"    name="follow_car_data[]"  value="DST Care 服务介绍" />DST Care 服务介绍
                    <p><input type="checkbox"    name="follow_car_data[]"  value="所在城市限行政策资料" />所在城市限行政策资料</p>
                    <p><input type="checkbox"    name="follow_car_data[]"  value="地上铁APP使用说明书" />地上铁APP使用说明书</p>
                </td>
            </tr>
            <!-- <tr>
                <td>验车单：</td>
                <td>
                    <input type="file" name="verify_car_photo" required="true" missingMessage="请输入流程名称"/>
                </td>
            </tr> -->
        </table>
    </form>
</div>
<script>
$(function(){
	 $('#car_no').combobox({
		    onChange:function(newValue,oldValue){
		        select_car(newValue);
		    }
	});
	 select_type1();
		$('#car_no').combobox({ 
		      //url:'itemManage!categorytbl', 
		     // editable:false, //不可编辑状态
		      cache: false,
		    //  panelHeight: 'auto',//自动高度适合
		      valueField:'plate_number',   
		      textField:'plate_number'
		 });
})
function select_car(car_no){
	<?php foreach ($result1 as $row):?>
	if(car_no == "<?php echo $row['plate_number']?>")
	{
		
		if(<?php echo !empty($row['valid_to_date']) ? 1:0;?>)
		{
			$("#vehicle_license").val("<?php echo date('Y-m-d H:i',$row['valid_to_date']);?>");
		}else{
			$("#vehicle_license").val("未办理");
		}
		
		if(<?php echo !empty($row['next_annual_verification_date']) ? 1:0;?>){
			$("#road_transport").val("<?php echo date('Y-m-d H:i',$row['next_annual_verification_date']);?>");
		}else{
			$("#road_transport").val("未办理");
		}
		
		if(<?php echo !empty($row['traffic_end_date']) ? 1:0;?>)
		{
			$("#insurance").val("<?php echo date('Y-m-d H:i',$row['traffic_end_date']);?>");
		}else{
			$("#insurance").val("未购买");
		}
		
		if(<?php echo !empty($row['business_end_date']) ? 1:0;?>)
		{
			$("#business_risks").val("<?php echo date('Y-m-d H:i',$row['business_end_date']) ;?>");
		}else{
			$("#business_risks").val("未购买");
		}

		if(<?php echo !empty($row['collection_datetime']) ? 1:0 ;?>)
		{
			$("#monitoring").val("<?php echo date('Y-m-d H:i',$row['collection_datetime']);?>");
		}else{
			$("#monitoring").val("没有监控数据");
		}
		
		
	}
	<?php endforeach;?>
	
}
function select_type1(){

	var car_model ="<?php echo $result['car_model']?>";
	var brand_id = "<?php echo $result['brand_id']?>";
	 $.ajax({
	        type: "POST",
	        url: "<?php echo yii::$app->urlManager->createUrl(['process/car/get-car-no']); ?>&tc_receipts=<?php echo $result['tc_receipts'] ?>",
	        cache: false,
	        dataType : "json",
	        data:{brand_id:brand_id,car_model:car_model},
	        success: function(data){
	        	$("#car_no").combobox("loadData",data);
	          }
	     });
     
//	alert($("#car_brand").val());
//	alert($("#car_type").val());
}

</script>