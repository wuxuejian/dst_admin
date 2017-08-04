<!--  <div style="padding:10px 40px 20px 40px">  -->
    <form id="easyui-form-process-repair-maintain-edit-from" class="easyui-form" method="post">
			<input type="hidden" name="id" value="<?=$row['id'] ?>"/>
            <ul class="">
             	<li class="ulforform-resizeable-group" >
                    <div class="ulforform-resizeable-title">车辆号牌</div>
                    <div class="ulforform-resizeable-input">
                      <input id="easyui-form-car-fault-register-carCombogrid2" required class="easyui-textbox" style="width:160px;"  name="car_id"  id="car_id"  value="<?= $row['car_id']?>" />
                    </div>
                </li>       
                <li class="ulforform-resizeable-group">
                	<div class="ulforform-resizeable-title">进入修理厂日期、时间</div>
					<div class="ulforform-resizeable-input">
						<input class="easyui-datetimebox" name="add_time" data-options="required:true,showSeconds:false" value="<?= $row['add_time']?>" style="width:250px" missingMessage="请选择进入修理厂日期、时间！" id="add_time">
					</div>
                 </li>
				  <li class="ulforform-resizeable-group">
                	<div class="ulforform-resizeable-title">出修理厂日期、时间</div>
					<div class="ulforform-resizeable-input">
						<input class="easyui-datetimebox" name="out_time" data-options="required:true,showSeconds:false" value="<?= $row['out_time']?>" style="width:250px" missingMessage="请选择出修理厂日期、时间！" id="out_time">
					</div>
                 </li>
				 <li class="ulforform-resizeable-group" >
                    <div class="ulforform-resizeable-title">保养类别</div>
                    <div class="ulforform-resizeable-input">
                      <input id="easyui-form-car-fault-register-carCombogrid3" required class="easyui-textbox" style="width:160px;"  name="type"    id="type"  />
                    </div>
                </li>     
              
                <li class="ulforform-resizeable-group">
                	<div class="ulforform-resizeable-title">保养公里数</div>
					<div class="ulforform-resizeable-input">
						<input class="easyui-textbox" style="width:160px;"  name="driving_mileage" required  value="<?=$row['driving_mileage']?>" id="feedback_name"  />
                        <span id='tip2'></span>
					</div>
                 </li>
				 
				<li class="ulforform-resizeable-group" >
                    <div class="ulforform-resizeable-title">保养维修厂</div>
                    <div class="ulforform-resizeable-input">
                      <input id="easyui-form-car-fault-register-carCombogrid" required class="easyui-textbox" style="width:160px;"  name="maintenance_shop"      />
                    </div>
                </li>    
								
                <li class="ulforform-resizeable-group">
                	<div class="ulforform-resizeable-title">保养费用</div>
					<div class="ulforform-resizeable-input">
						<input
                         class="easyui-textbox" 
                         style="width:160px;"  
                         name="amount"   
                         value="<?=$row['amount']?>" 
                         id="amount"  
                         required
                         validType="match[/^\d+(\.\d+)?$/i]" invalidMessage="请输入数字！" 
                         />
					</div>
                 </li>
				 <li class="ulforform-resizeable-group-single">
	                <div class="ulforform-resizeable-title">上传故障照片</div>
	                <div class="ulforform-resizeable-input">
	                    <ul style="padding:0;margin:0;list-style:none;overflow:hidden;" id="process-repair-uploadfile">
	                        <?php
	                            $thumbs = [
	                                 ['in_car_img','进维修厂收车单'],
	                                ['out_car_img','出场保养结果单据'],
									['maintain_img','保修手册凭证'],
	                            ];
	                            foreach($thumbs as $key=>$item){
	                        ?>
	                            <li id="img<?php echo $key;?>"  style="float:left;margin-right:16px;position:relative;cursor:pointer; margin-bottom:20px;" >
	                                <div style="width:100px;height:100px;">
	                                    <img  id="<?php echo $item[0]; ?>"  class="repairImg" src="<?php echo $row[$item[0]]?>" width="100" height="100" />
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
       
    </form>
<!-- </div> -->
<iframe id="iframe-process-repair-uploadimage" name="iframe-process-repair-uploadimage" style="display:none;"></iframe>
<div id="easyui-dialog-process-repair-uploadimage"></div>
<div id="easyui-dialog-process-repair-maintain-indicator-light"></div>

<script>  
var faultInfo = <?php echo json_encode($row); ?>;
console.log(faultInfo);
$('#easyui-form-process-repair-maintain-edit-from').form('load',faultInfo);

$(function(){
	 temp_car_id = "<?php echo $row['car_id'];?>";
	
	 $('#easyui-form-car-fault-register-carCombogrid3').combogrid({
         panelWidth: 450,
         panelHeight: 200,
      //   required: true,
         missingMessage: '请输入保养类别名称检索后从下拉列表里选择一项！',
         onHidePanel:function(){
             var _combogrid3 = $(this);
             var value = _combogrid3.combogrid('getValue');
             var text = _combogrid3.combogrid('textbox').val();
             var row = _combogrid3.combogrid('grid').datagrid('getSelected');
             //console.log(row);
             if(!row){ //没有选择表格行但输入有检索字符串时，提示并清除检索字符串
                 if(text && value == text){
                     $.messager.show(
                         {
                             title: '无效值',
                             msg:'【' + text + '】不是有效值！请重新输入保养类别名称检索后，从下拉列表里选择一项！'
                         }
                     );
                     _combogrid3.combogrid('clear');
                 }
             }else{ //注意：若选择了表格行但是原本应显示为text的车牌号不存在，则改成显示车架号为text！
                 if(!row.id){
                     _combogrid3.combogrid('setText', row.type);
                     //_combogrid.combogrid('textbox').val(row.vehicle_dentification_number); //这种不好，因为当输入框再次获得焦点时会自动显示value而非text.
                 }
             }
         },
		 queryParams: {
			id:temp_car_id
		 },
         delay: 800,
         mode:'remote',
         idField: 'id',
         textField: 'maintain_type',
         url: '<?= yii::$app->urlManager->createUrl(['car/maintain-record/get-type-by-car-id']); ?>',
		 onLoadSuccess: function(obj) { 
			
         },
		onSelect: function(){
			temp_car_id = $('#easyui-form-car-fault-register-carCombogrid2').combogrid('grid').datagrid('getSelected').id;
			$('#easyui-form-car-fault-register-carCombogrid3').combogrid({
				  queryParams: {
					id:temp_car_id
				 }
			});
			 console.log(temp_car_id);
          },
         method: 'get',
         scrollbarSize:0,
         pagination: true,
         pageSize: 10,
         pageList: [10,20,30],
         fitColumns: true,
         rownumbers: true,
         columns: [[
             {field:'id',title:'保养类别ID',width:40,align:'center',hidden:true},
             {field:'maintain_type',title:'保养类别',width:100,align:'center'}
            
         ]]
     });
	 
	 $('#easyui-form-car-fault-register-carCombogrid2').combogrid({
         panelWidth: 450,
         panelHeight: 200,
      //   required: true,
         missingMessage: '请输入车牌号/车架号检索后从下拉列表里选择一项！',
         onHidePanel:function(){
			 console.log("nini");
             var _combogrid2 = $(this);
             var value = _combogrid2.combogrid('getValue');
             var text = _combogrid2.combogrid('textbox').val();
             var row = _combogrid2.combogrid('grid').datagrid('getSelected');
             if(!row){ //没有选择表格行但输入有检索字符串时，提示并清除检索字符串
                 if(text && value == text){
                     $.messager.show(
                         {
                             title: '无效值',
                             msg:'【' + text + '】不是有效值！请重新输入车牌号/车架号检索后，从下拉列表里选择一项！'
                         }
                     );
                     _combogrid2.combogrid('clear');
                 }
             }else{ //注意：若选择了表格行但是原本应显示为text的车牌号不存在，则改成显示车架号为text！
                 console.log("hi");
				 if(!row.plate_number){
                     _combogrid2.combogrid('setText', row.plate_number);
                     //_combogrid.combogrid('textbox').val(row.vehicle_dentification_number); //这种不好，因为当输入框再次获得焦点时会自动显示value而非text.
                 }
             }
         },
         delay: 800,
         mode:'remote',
         idField: 'id',
         textField: 'plate_number',
         url: '<?= yii::$app->urlManager->createUrl(['car/fault/get-cars']); ?>',
		 onLoadSuccess: function(obj) { 
			$('#easyui-form-car-fault-register-carCombogrid3').combogrid({
				  queryParams: {
					id:temp_car_id
				 }
			 });
         },
		onSelect: function(){
			temp_car_id = $('#easyui-form-car-fault-register-carCombogrid2').combogrid('grid').datagrid('getSelected').id;
			$('#easyui-form-car-fault-register-carCombogrid3').combogrid({
				  queryParams: {
					id:temp_car_id
				 }
			});
			 console.log(temp_car_id);
          },
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
	 
      $('#feedback_name').numberbox({
            
            onChange: function(rec){
                $("#tip2").html('');
                //alert('123');
                //console.log(rec);
                $.ajax({
                type: 'post',
                url: '<?php echo yii::$app->urlManager->createUrl(['car/maintain-record/check2']); ?>',
                data: {id:temp_car_id},
                //console.log(id);
                dataType: 'json',
                success: function(data){
                    
                    $.each(data,function(i, value){
                       //console.log(rec);
                       //console.log(parseInt(value.driving_mileage));

                       if(rec == parseInt(value.driving_mileage)){
                        //alert('777');
                        $("#tip2").html("<font color='red'>*该保养里程已存在，请不要重复录入</font>");
                        } 
                    });    
                }
                })  
            }
          
        });

      
	 //注意：加载成功后，若原本应显示为text的车牌号不存在，则改成显示车架号为text！
     var _combogrid = $('#easyui-form-car-fault-register-carCombogrid2');
     _combogrid.combogrid('grid').datagrid({
         queryParams: {'car_id':faultInfo.car_id},
         onLoadSuccess: function(data){
			 // console.log(faultInfo); consolo.log("233");
             if(data.rows.length == 1){
                 var row = data.rows[0];
                 if(row && !row.plate_number){
                     _combogrid.combogrid('setText', row.vehicle_dentification_number);
                 }else{
                     _combogrid.combogrid('setText', row.plate_number);
                 }
             }
         }
     });
	 
	

	 

	 $('#easyui-form-car-fault-register-carCombogrid').combogrid({
         panelWidth: 450,
         panelHeight: 200,
      //   required: true,
         missingMessage: '请输入维修厂名称检索后从下拉列表里选择一项！',
         onHidePanel:function(){
            //alert('m1');
             var _combogrid = $(this);
             var value = _combogrid.combogrid('getValue');
             var text = _combogrid.combogrid('textbox').val();
             var row = _combogrid.combogrid('grid').datagrid('getSelected');
             //console.log(row);
             if(!row){ //没有选择表格行但输入有检索字符串时，提示并清除检索字符串
                 if(text && value == text){
                     $.messager.show(
                         {
                             title: '无效值',
                             msg:'【' + text + '】不是有效值！请重新输入维修厂名称检索后，从下拉列表里选择一项！'
                         }
                     );
                     _combogrid.combogrid('clear');
                 }
             }else{ //注意：若选择了表格行但是原本应显示为text的车牌号不存在，则改成显示车架号为text！
                 if(!row.site_name){
                     _combogrid.combogrid('setText', row.site_name);
                     //_combogrid.combogrid('textbox').val(row.vehicle_dentification_number); //这种不好，因为当输入框再次获得焦点时会自动显示value而非text.
                 }
             }
         },
         delay: 800,
         mode:'remote',
         idField: 'id',
         textField: 'site_name',
         url: '<?= yii::$app->urlManager->createUrl(['car/maintain-record/get-maintain-services']); ?>',
		 onLoadSuccess: function(obj) { 
			
         },
		onSelect: function(){
			 //temp_car_id = $('#easyui-form-car-fault-register-carCombogrid').combogrid('grid').datagrid('getSelected').id;
			// $('#easyui-form-car-fault-register-claimId').combogrid({
			//	  queryParams: {
			//		id:temp_car_id
			//	 }
			// });
			 //console.log(temp_car_id);
          },
         method: 'get',
         scrollbarSize:0,
         pagination: true,
         pageSize: 10,
         pageList: [10,20,30],
         fitColumns: true,
         rownumbers: true,
         columns: [[
             {field:'id',title:'服务站ID',width:40,align:'center',hidden:true},
             {field:'site_name',title:'站点名称',width:100,align:'center'}
            
         ]]
     });
    //注意：加载成功后，若原本应显示为text的车牌号不存在，则改成显示车架号为text！
     var _combogrid2 = $('#easyui-form-car-fault-register-carCombogrid');
     _combogrid2.combogrid('grid').datagrid({
         queryParams: {'id':faultInfo.maintenance_shop},
         onLoadSuccess: function(data){
             // console.log(faultInfo); consolo.log("233");
            // console.log(data);
             //console.log(data.rows[0]);
             //alert('12')

             if(data.rows.length == 1){
                 var row = data.rows[0];
                 if(row && !row.site_name){
                     _combogrid2.combogrid('setText', row.site_name);
                 }else{
                     _combogrid2.combogrid('setText', row.site_name);
                 }
             }
         }
     });
	 

});

                 
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


    ProcessRepairUpload.init();

  
</script>