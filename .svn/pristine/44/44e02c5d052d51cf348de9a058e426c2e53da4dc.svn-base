<!--  <div style="padding:10px 40px 20px 40px">  -->
    <form id="easyui-form-process-repair-maintain-add-from" class="easyui-form" method="post">
        <div class="easyui-panel" title="(1)基本情况" style="padding:5px 0px;"
         data-options="collapsible:true,collapsed:false,border:false,fit:false">
            <ul class="ulforform-resizeable">
             	<li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">维修原因</div>
                    <div class="ulforform-resizeable-input">
                      <select id="is_type"   class="easyui-combobox" style="width:160px;" data-options="editable:false"    name="type" required="true"   missingMessage="请选择故障原因">
                   		 	<option value="3">我方报修</option>
                   		 	<option value="1">客户报修</option>
                   		 	<option value="2">保险事故</option>
                   		 </select>
                    </div>
                </li>
                <li class="ulforform-resizeable-group is_type12" style="display:none">
                    <div class="ulforform-resizeable-title">服务工单号</div>
                    <div class="ulforform-resizeable-input">
                      <input id="order_no" class="easyui-textbox" style="width:160px;"  name="order_no"       />
                    </div>
                </li>
                <li class="ulforform-resizeable-group" >
                    <div class="ulforform-resizeable-title">故障车辆号牌</div>
                    <div class="ulforform-resizeable-input">
                      <input id="easyui-form-car-fault-register-carCombogrid" class="easyui-textbox" style="width:160px;"  name="car_id"      />
                    </div>
                </li>                 
                <li class="ulforform-resizeable-group">
                	<div class="ulforform-resizeable-title">故障发生时间</div>
					<div class="ulforform-resizeable-input">
						<input class="easyui-datetimebox" style="width:160px;"  name="fault_start_time"  id="fault_start_time"  required  />
					</div>
                 </li>
               <!--  <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">故障反馈时间</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-datebox" style="width:160px;"  name="feedback_time"  required  id="feedback_time" />
                    </div>
                </li>-->  
                <li class="ulforform-resizeable-group">
                	<div class="ulforform-resizeable-title">故障反馈人</div>
					<div class="ulforform-resizeable-input">
						<input class="easyui-textbox" style="width:160px;"  name="feedback_name" required  id="feedback_name"  />
					</div>
                 </li>
                 <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">反馈人电话</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-textbox" style="width:160px;"  name="tel"   id="tel"  required   validType="match[/((0\d{2,3})-)(\d{7,8})(-(\d{3,}))?$|1[3|4|5|7|8][0-9]\d{8}$/]" invalidMessage="电话、手机格式错误！" prompt="电话号码格式 区号-号码"  />
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                	<div class="ulforform-resizeable-title">送修人姓名</div>
					<div class="ulforform-resizeable-input">
						<input class="easyui-textbox" style="width:160px;"  name="accept_name"   id="accept_name"  required/>
					</div>
                 </li>
                 <!--  <li class="ulforform-resizeable-group">
                	<div class="ulforform-resizeable-title">故障上报时间</div>
					<div class="ulforform-resizeable-input">
						<input class="easyui-datetimebox" style="width:160px;"  name="fault_report_time"  required id="fault_report_time"  />
					</div>
                 </li>-->

            </ul>
        </div>
        
        <div class="easyui-panel" title="(2)现场情况" style="padding:5px 0px;"
         data-options="collapsible:true,collapsed:false,border:false,fit:false">
         	<ul class="ulforform-resizeable">
         	 	<li class="ulforform-resizeable-group-single">
                    <div class="ulforform-resizeable-title">故障地点</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-textbox" style="width:470px;"  name="fault_address"  id="fault_address" required  />
                    </div>
                </li>
                <li class="ulforform-resizeable-group-single">
                    <div class="ulforform-resizeable-title">路面情况</div>
                    <div class="ulforform-resizeable-input">
                        <input type="checkbox" name="road_situation[]" value="有较深积水" />有较深积水（＞80mm）
                    </div>
                    <div class="ulforform-resizeable-input">
                        <input type="checkbox" name="road_situation[]" value="较为颠簸" />较为颠簸
                    </div>
                    <div class="ulforform-resizeable-input">
                        <input type="checkbox" name="road_situation[]" value="交通拥堵" />交通拥堵
                    </div>
                    <div class="ulforform-resizeable-input">
                        <input type="checkbox" name="road_situation[]" value="有坡度" />有坡度
                    </div>
                    <div class="ulforform-resizeable-input">
                        <input type="checkbox" name="road_situation[]" value="路况正常" />路况正常
                    </div>
                </li>
                <li class="ulforform-resizeable-group-single">
                    <div class="ulforform-resizeable-title">天气情况</div>
                    <div class="ulforform-resizeable-input">
                        <select   class="easyui-combobox" style="width:85px;" data-options="editable:false"    name="weather_situation" required="true">
                   		 	<option value=""></option>
                   		 	<option value="晴天">晴天</option>
                   		 	<option value="小雨">小雨</option>
                   		 	<option value="中雨">中雨</option>
                   		 	<option value="大雨及以上">大雨及以上</option>
                   		 	<option value="潮湿">潮湿</option>
                   		 </select>
                    </div>
                    
                    <div class="ulforform-resizeable-title">气温情况</div>
                    <div class="ulforform-resizeable-input">
                    	<select   class="easyui-combobox" style="width:85px;" data-options="editable:false"    name="temperature_situation" required="true">
                   		 	<option value=""></option>
                   		 	<option value="&lt;0℃">&lt;0℃</option>
                   		 	<option value="0-20℃">0-20℃</option>
                   		 	<option value="20-30℃">20-30℃</option>
                   		 	<option value="30-35℃">30-35℃</option>
                   		 	<option value="&gt;35℃">&gt;35℃</option>
                   		 </select>
                    </div>
                    <div class="ulforform-resizeable-title">车辆时速</div>
                    <div class="ulforform-resizeable-input">
                        <select   class="easyui-combobox" style="width:85px;" data-options="editable:false"    name="vehicle_speed" required="true">
                   		 	<option value=""></option>
                   		 	<option value="熄火状态">熄火状态</option>
                   		 	<option value="点火停止状态">点火停止状态</option>
                   		 	<option value="0-20km/h">0-20km/h</option>
                   		 	<option value="21-40km/h">21-40km/h</option>
                   		 	<option value="41-60km/h">41-60km/h</option>
                   		 	<option value="61-80km/h">61-80km/h</option>
                   		 	<option value="&gt;80km/h">&gt;80km/h</option>
                   		 </select>
                    </div>
                </li>
         		<li class="ulforform-resizeable-group">
                	<div class="ulforform-resizeable-title">故障代码</div>
					<div class="ulforform-resizeable-input">
						<input class="easyui-textbox" style="width:160px;"  name="fault_code"   id="fault_code"  />
					</div>
                 </li>
                 <li class="ulforform-resizeable-group">
                	<div class="ulforform-resizeable-title">当时行驶里程</div>
					<div class="ulforform-resizeable-input">
						<input class="easyui-textbox" style="width:160px;"  name="current_mileage"   id="current_mileage"  required  />
					</div>
                 </li>
                 <li class="ulforform-resizeable-group-single" style="height:60px;">
                	<div class="ulforform-resizeable-title">仪表故障指示灯</div>
					<div class="ulforform-resizeable-input">
						<img  class="repairImg" src="./images/add.jpg" width="40" height="40" style=" border:2px solid #ccc;" onclick="ProcessRepairUpload.indicator_light()" />
						<input type="hidden" name="indicator_light"  />
						<span  id="indicator_light"></span> 
					</div>
                 </li>
                 
                 
                 <li class="ulforform-resizeable-group-single">
                    <div class="ulforform-resizeable-title">故障描述</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-textbox" style="width:470px;height:50px;"   id="scene_desc"   required name="scene_desc"   data-options="multiline:true" prompt="200字符以内。"
                        validType="length[200]"/>
                    </div>
                </li>
         	</ul>
        </div>
        
        <div class="easyui-panel" title="(3)处理情况" style="padding:5px 0px;"
         data-options="collapsible:true,collapsed:false,border:false,fit:false">
         	<ul class="ulforform-resizeable">
	         		<li class="ulforform-resizeable-group-single">
	                    <div class="ulforform-resizeable-title">处理结果</div>
	                    <div class="ulforform-resizeable-input">
	                        <input class="easyui-textbox" style="width:470px;height:50px;"   id="scene_result"  required  name="scene_result"   data-options="multiline:true" prompt="200字符以内。"
	                        validType="length[200]"/>
	                    </div>
	                </li>
	                <li class="ulforform-resizeable-group">
	                	<div class="ulforform-resizeable-title">重启后故障消失</div>
						<div class="ulforform-resizeable-input">
							<select    class="easyui-combobox" style="width:160px;" data-options="editable:false"    name="vehicle_launch" required="true"  >
	                   		 	<option value=""></option>
	                   		 	<option value="0">否</option>
	                   		 	<option value="1">是</option>
	                   		 </select>
						</div>
	                 </li>
	                 <li class="ulforform-resizeable-group">
	                	<div class="ulforform-resizeable-title">需要拖车</div>
						<div class="ulforform-resizeable-input">
							<select   class="easyui-combobox" style="width:160px;" data-options="editable:false"    name="is_trailer" required="true"  >
	                   		 	<option value=""></option>
	                   		 	<option value="0">否</option>
	                   		 	<option value="1">是</option>
	                   		 </select>
						</div>
	                 </li>
	                 <li class="ulforform-resizeable-group">
	                	<div class="ulforform-resizeable-title">维修方式</div>
						<div class="ulforform-resizeable-input">
							<select id="is_go_scene"   class="easyui-combobox" style="width:160px;" data-options="editable:false"    name="maintain_way" required="true"   missingMessage="请选择维修方式">
	                   		 	<option value="1">进厂维修</option>
	                   		 	<option value="2">现场维修</option>
	                   		 	<option value="3">自修</option>
	                   		 </select>
						</div>
	                 </li>
	                 <li class="ulforform-resizeable-group is_go_scene">
	                	<div class="ulforform-resizeable-title">维修场站</div>
						<div class="ulforform-resizeable-input">
	                   		 <input id="maintain_scene" style="width:160px;"  class="easyui-textbox" style="width:160px;"  name="maintain_scene"       />
						</div>
	                 </li>
	                <li class="ulforform-resizeable-group is_go_scene">
	                    <div class="ulforform-resizeable-title">维修方联系人</div>
	                    <div class="ulforform-resizeable-input">
	                        <input class="easyui-textbox" style="width:160px;"  id="maintain_name" name="maintain_name"    />
	                    </div>
	                </li>
	                <li class="ulforform-resizeable-group is_go_scene">
	                    <div class="ulforform-resizeable-title">联系电话</div>
	                    <div class="ulforform-resizeable-input">
	                        <input class="easyui-textbox" style="width:160px;" id="maintain_tel"   name="maintain_tel"  validType="match[/((0\d{2,3})-)(\d{7,8})(-(\d{3,}))?$|1[3|4|5|7|8][0-9]\d{8}$/]" invalidMessage="电话、手机格式错误！" prompt="电话号码格式 区号-号码"  />
	                    </div>
	                </li>
	                <li class="ulforform-resizeable-group is_go_scene">
	                    <div class="ulforform-resizeable-title">服务接待人</div>
	                    <div class="ulforform-resizeable-input">
	                        <input class="easyui-textbox" style="width:160px;"  id="maintain_worker" name="maintain_worker"   />
	                    </div>
	                </li>
	                <li class="ulforform-resizeable-group is_go_scene">
	                    <div class="ulforform-resizeable-title">联系电话</div>
	                    <div class="ulforform-resizeable-input">
	                        <input class="easyui-textbox" style="width:160px;" id="maintain_worker_tel"   name="maintain_worker_tel" validType="match[/((0\d{2,3})-)(\d{7,8})(-(\d{3,}))?$|1[3|4|5|7|8][0-9]\d{8}$/]" invalidMessage="电话、手机格式错误！" prompt="电话号码格式 区号-号码"   />
	                    </div>
	                </li>
	                <li class="ulforform-resizeable-group is_go_scene">
	                    <div class="ulforform-resizeable-title">进场维修单号</div>
	                    <div class="ulforform-resizeable-input">
	                        <input class="easyui-textbox" style="width:160px;"  id="maintain_no"  name="maintain_no"   />
	                    </div>
	                </li>
	                <li class="ulforform-resizeable-group is_go_scene1">
	                    <div class="ulforform-resizeable-title">进厂时间</div>
	                    <div class="ulforform-resizeable-input">
	                        <input class="easyui-datetimebox" style="width:160px;"  id="into_factory_time"  name="into_factory_time"  />
	                    </div>
	                </li>
	                <li class="ulforform-resizeable-group">
	                    <div class="ulforform-resizeable-title">预计完成时间</div>
	                    <div class="ulforform-resizeable-input">
	                        <input class="easyui-datebox" style="width:160px;"  id="expect_time"  name="expect_time" required   />
	                    </div>
	                </li>
	               <li class="ulforform-resizeable-group-single">
	                <div class="ulforform-resizeable-title">上传故障照片</div>
	                <div class="ulforform-resizeable-input">
	                    <ul style="padding:0;margin:0;list-style:none;overflow:hidden;" id="process-repair-uploadfile">
	                        <?php
	                            $thumbs = [
	                                ['car_no_img','车头正面照片'],
	                                ['car_img1','车尾正面'],
	                                ['car_img2','车身左侧'],
	                                ['car_img3','车身右侧'],
	                                ['dashboard_img','车辆仪表照片'],
	                                ['fault_scene_img','故障现场照片'],
	                                ['fault_location_img','故障部位照片'],
	                                ['maintain_jieche_img','维修接车单照片'],
	                            ];
	                            foreach($thumbs as $key=>$item){
	                        ?>
	                            <li id="img<?php echo $key;?>" style="float:left;margin-right:16px;position:relative;cursor:pointer;margin-bottom:20px;" >
	                                <div style="width:100px;height:100px;">
	                                    <img  id="<?php echo $item[0]; ?>"  class="repairImg" src="./images/add.jpg" width="100" height="100" />
	                                    <input type="hidden" name="<?php echo $item[0]; ?>"  />
	                                </div>
	                                <div class="imgTitle" style="position:absolute;bottom:0;left:0;background:rgba(224,236,255,0.5);width:100px;text-align:center;line-height:24px;"><?php echo $item[1]; ?></div>
	                                <div class="removeIcon" style="position:absolute;top:0;right:0;background:rgba(224,236,255,0.5);display:none;"><img src="./jquery-easyui-1.4.3/themes/icons/clear.png" width="14px" height="14px" /></div>
	                            </li>
	                        <?php } ?>
	                    </ul>
	                </div>
	            </li>
         	</ul>
        </div>
        
    </form>
<!-- </div> -->
<iframe id="iframe-process-repair-uploadimage" name="iframe-process-repair-uploadimage" style="display:none;"></iframe>
<div id="easyui-dialog-process-repair-uploadimage"></div>
<div id="easyui-dialog-process-repair-maintain-indicator-light"></div>

<script>  

$(function(){
	$('#maintain_scene').combobox({ 
	    //  url:"", 
	     // editable:false, //不可编辑状态
	      cache: false,
	    //  panelHeight: 'auto',//自动高度适合
	      valueField:'text',   
	      textField:'text',
	      data: <?= json_encode($maintain_scenes); ?>,
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
	
	 $('#is_go_scene').combobox({
		    onChange:function(newValue,oldValue){
		    	is_go_scene(newValue);	
		    }
	}); 

	 $('#is_type').combobox({
		    onChange:function(newValue,oldValue){
		    	is_type(newValue);	
		    }
	}); 
	 //初始化
	 is_go_scene($("#is_go_scene").val());
	 is_type(3);   //我方维修



	 $('#order_no').combobox({ 
	    //  url:"", 
	     // editable:false, //不可编辑状态
	      cache: false,
	    //  panelHeight: 'auto',//自动高度适合
	      valueField:'order_no',   
	      textField:'order_no',
	      onChange:function(newValue,oldValue){
		    	is_order(newValue);	
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
	 

	 $('#easyui-form-car-fault-register-carCombogrid').combogrid({
         panelWidth: 450,
         panelHeight: 200,
      //   required: true,
         missingMessage: '请输入车牌号/车架号检索后从下拉列表里选择一项！',
         onHidePanel:function(){
             var _combogrid = $(this);
             var value = _combogrid.combogrid('getValue');
             var text = _combogrid.combogrid('textbox').val();
             var row = _combogrid.combogrid('grid').datagrid('getSelected');
             if(!row){ //没有选择表格行但输入有检索字符串时，提示并清除检索字符串
                 if(text && value == text){
                     $.messager.show(
                         {
                             title: '无效值',
                             msg:'【' + text + '】不是有效值！请重新输入车牌号/车架号检索后，从下拉列表里选择一项！'
                         }
                     );
                     _combogrid.combogrid('clear');
                 }
             }else{ //注意：若选择了表格行但是原本应显示为text的车牌号不存在，则改成显示车架号为text！
                 if(!row.plate_number){
                     _combogrid.combogrid('setText', row.vehicle_dentification_number);
                     //_combogrid.combogrid('textbox').val(row.vehicle_dentification_number); //这种不好，因为当输入框再次获得焦点时会自动显示value而非text.
                 }
             }
         },
         delay: 800,
         mode:'remote',
         idField: 'id',
         textField: 'plate_number',
         url: '<?= yii::$app->urlManager->createUrl(['car/fault/get-cars']); ?>',
         method: 'get',
         scrollbarSize:0,
         pagination: true,
         pageSize: 10,
         pageList: [10,20,30],
         fitColumns: true,
         rownumbers: true,
         columns: [[
             {field:'id',title:'车辆ID',width:40,align:'center',hidden:true},
             {field:'plate_number',title:'车牌号',width:100,align:'center'},
             {field:'vehicle_dentification_number',title:'车架号',width:150,align:'center'}
         ]]
     });

	 

});

//是否进场维修
function is_go_scene(is_go_scene)
{
	//alert($("#is_go_scene").val());
	//自修
	if(is_go_scene == 3 || is_type == 2)
	{ 
		$(".is_go_scene").css('display','none');
		$('#maintain_name').textbox({required: false});


		$('#into_factory_time').datetimebox({required: false});
		$(".is_go_scene1").css('display','none');
		
		$("#img7").css('display','none');
	}else{  

		if(is_go_scene == 1)
		{
			$('#into_factory_time').datetimebox({required: true});
			$(".is_go_scene1").css('display','block');
		}else{
			$('#into_factory_time').datetimebox({required: false});
			$(".is_go_scene1").css('display','none');
		}
		
		
		$('#maintain_name').textbox({required: true});
		$(".is_go_scene").css('display','block');
		$("#img7").css('display','block');
	}
}

//故障原因判断
function is_type(is_type)
{
	//我方报修
	if(is_type == 3 || is_type == 2)
	{ 
		$(".is_type12").css('display','none');
		$(".is_type3").css('display','block');
		$('#easyui-form-car-fault-register-carCombogrid').combogrid({required: true});
		$('#order_no').combobox({required: false});
		$('#easyui-form-car-fault-register-carCombogrid').combogrid({readonly:false});
	}else{
		$(".is_type12").css('display','block');
		$(".is_type3").css('display','none');
		$('#order_no').combobox({required: true});
		$('#easyui-form-car-fault-register-carCombogrid').combogrid({readonly:true});

		$.ajax({
	        type: "POST",
	        url: "<?php echo yii::$app->urlManager->createUrl(['process/repair/get-bx-order']); ?>",
	        cache: false,
	        dataType : "json",
	        data:{type:is_type},
	        success: function(data){
	        	$("#order_no").combobox("loadData",data);
	          }
	     });
	}
}

//服务流程订单数据导入到表单
function is_order(order_no)
{
	$.ajax({
        type: "POST",
        url: "<?php echo yii::$app->urlManager->createUrl(['process/repair/get-bx-data']); ?>",
        cache: false,
        dataType : "json",
        data:{order_no:order_no},
        success: function(data){
            $("#easyui-form-car-fault-register-carCombogrid").textbox("setValue", data.car_id);
            $("#easyui-form-car-fault-register-carCombogrid").textbox("setText", data.car_no);
            

			$("#fault_start_time").textbox("setValue", data.fault_start_time)
            
            $("#feedback_time").textbox("setValue", data.tel_time);
            $("#feedback_name").textbox("setValue", data.repair_name);
            $("#tel").textbox("setValue", data.tel);
            $("#accept_name").textbox("setValue", data.assign_name);
            $("#fault_report_time").textbox("setValue", data.time);
            $("#fault_address").textbox("setValue", data.address+' '+data.bearing);
            $("#scene_desc").textbox("setValue", data.scene_desc);
            $("#scene_result").textbox("setValue", data.scene_result);

            if(data.is_go_scene == '1')
            {
            	// $("#is_go_scene").textbox("setValue", '2'); 
            	//$("input[name='maintain_way']").val(1);
            	 $("#is_go_scene").textbox("setValue", '1');
           	 	$("#is_go_scene").textbox("setText", '进厂维修');
            }else{
            	//$("input[name='maintain_way']").val(2);
            	 $("#is_go_scene").textbox("setValue", '2');
           		 $("#is_go_scene").textbox("setText", '现场维修');
            	 //$("#is_go_scene").textbox("setValue", data.is_go_scene);

				$('#into_factory_time').datetimebox({required: false});
				$(".is_go_scene1").css('display','none');
            }
           

            $("#maintain_scene").textbox("setValue", data.maintain_scene);
            $("#maintain_name").textbox("setValue", data.maintain_name);
            $("#maintain_tel").textbox("setValue", data.maintain_tel);
            $("#maintain_no").textbox("setValue", data.maintain_no);
            $("#expect_time").textbox("setValue", data.expect_time);


            
            if(data.car_no_img)
            {
            	$("#car_no_img").attr('src',data.car_no_img);
                $('input[name="car_no_img"]').val(data.car_no_img);
            }
            
            
            if(data.dashboard_img)
            {	
            	$("#dashboard_img").attr('src',data.dashboard_img);
                $('input[name="dashboard_img"]').val(data.dashboard_img);
            }
            
            if(data.fault_scene_img)
            {	
            	$("#fault_scene_img").attr('src',data.fault_scene_img);
                $('input[name="fault_scene_img"]').val(data.fault_scene_img);
            }
            
            if(data.fault_location_img)
            {	
            	$("#fault_location_img").attr('src',data.fault_location_img);
                $('input[name="fault_location_img"]').val(data.fault_location_img);
            }
            
            if(data.maintain_jieche_img)
            {	
                $("#maintain_jieche_img").attr('src',data.maintain_jieche_img);
            	$('input[name="maintain_jieche_img"]').val(data.maintain_jieche_img);
            }
            
            
          }
     });
}

                        
    var ProcessRepairUpload = new Object();
    ProcessRepairUpload.init = function(){
    	//初始化照片上传窗口
        $('#easyui-dialog-process-repair-uploadimage').dialog({
            title: '照片上传',   
            width: 500,   
            height: 160,   
            closed: false,   
            cache: true,   
            modal: true,
            closed: true,
            maximizable: false,
            minimizable: false,
            collapsible: false,
            draggable: false,
            buttons: [{
                text:'上传',
                iconCls:'icon-ok',
                handler:function(){
                    var form = $('#easyui-form-process-repair-upload-window');
                    if(!form.form('validate')){
                        return false;
                    }
                    form.submit();
                }
            },{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui-dialog-process-repair-uploadimage').dialog('close');
                }
            }],
            onClose: function(){
                $(this).window('clear');
            }
        });

        //给上传故障图片绑定各类事件
        $('#process-repair-uploadfile').children('li')
            .click(function(){ //单击打开上传窗口
                var columnName = $(this).find('input').attr('name');
                $('#easyui-dialog-process-repair-uploadimage')
                    .dialog('open')
                    .dialog('refresh',"<?= yii::$app->urlManager->createUrl(['process/repair/upload-window']); ?>&columnName="+columnName);
            })
            .mouseover(function(){
                var imgSrc = $(this).find('img.repairImg').attr('src');
                if(imgSrc != './images/add.jpg'){
                    //显示删除图标并绑定删除事件
                    $(this).find('div.removeIcon').show().click(function(e){
                        e.stopPropagation();
                        $(this).parent().find('img.repairImg').attr('src','./images/add.jpg');
                        $(this).parent().find('input').val('');
                    });
                }
            })
            .mouseleave(function(){
                $(this).find('div.removeIcon').hide();
            });
       
    }

    ProcessRepairUpload.uploadComplete = function(rData){
        if(rData.status){
            $('#easyui-dialog-process-repair-uploadimage').dialog('close');
            var inputControl = $('#process-repair-uploadfile').find('input[name='+rData.columnName+']');
            inputControl.val(rData.storePath);
            inputControl.siblings('img').attr('src',rData.storePath);
            // 放大显示上传图片
            inputControl.parent().parent().tooltip({
                position: 'top',
                content: '<img src="' + rData.storePath + '" width="350px" height="350px" border="0" />'
            });
        }else{
            $.messager.alert('上传错误',rData.info,'error');
        }
    }


    $('#easyui-dialog-process-repair-maintain-indicator-light').dialog({
    	title: '仪表故障指示灯',   
        width: '450px',   
        height: '400px',   
        closed: true,   
        cache: true,   
        modal: true,
        resizable:true,
        maximizable: true,
        buttons: [{
			text:'确定',
			iconCls:'icon-ok',
			handler:function(){
                var form = $('#easyui-form-process-repair-maintain-ajax-indicator-light-from');
                if(!form.form('validate')) return false;
				var data = form.serialize();
				$.ajax({
					type: 'post',
					url: "<?php echo yii::$app->urlManager->createUrl(['process/repair/ajax-indicator-light']); ?>",
					data: data,
					dataType: 'json',
					success: function(data){
						$('#easyui-dialog-process-repair-maintain-indicator-light').dialog('close');
						if(data.status){
							var data = data.data;
							var indicator_ids = '';
							var html = '';
							$.each(data, function(i, value) {
                              //alert(data[i]['name']);
								if(i==0)
								{
									indicator_ids += data[i]['id'];
								}else{
									indicator_ids += ','+data[i]['id'];
								}

								html +='<img  class="repairImg" src="'+data[i]['image_url']+'" width="40" height="40" title="'+data[i]['name']+'"  />&nbsp;'; 
							});
							
							$('input[name="indicator_light"]').val('');
							$('input[name="indicator_light"]').val(indicator_ids);
							$("#indicator_light").empty();
							$("#indicator_light").append(html);
						}else{
							$('input[name="indicator_light"]').val('');
							$("#indicator_light").empty();
						}
					}
				});
			}
		},{
			text:'取消',
			iconCls:'icon-cancel',
			handler:function(){
				$('#easyui-dialog-process-repair-maintain-indicator-light').dialog('close');
			}
		}],
        onClose: function(){
            $(this).dialog('clear');
        }
    });
    
    ProcessRepairUpload.init();

  	//添加车辆故障指示灯
    ProcessRepairUpload.indicator_light = function(){
        $('#easyui-dialog-process-repair-maintain-indicator-light').dialog('open');
        $('#easyui-dialog-process-repair-maintain-indicator-light').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/repair/ajax-indicator-light']); ?>");
    }
</script>