<div style="padding:10px 40px 20px 40px">  
    <form id="easyui-form-process-repair-field-from" class="easyui-form" method="post">
    <input type="hidden" name="id" />
    <input type="hidden" name="repair_id" value="<?php echo $repair_id?>" />
        <div >
            <ul class="ulforform-resizeable">
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">车牌号</div>
                    <div class="ulforform-resizeable-input">
                      <input class="easyui-textbox" style="width:160px;" data-options="editable:false"  name="car_no"     value="<?php echo $car_no;?>"   />
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                	<div class="ulforform-resizeable-title">抵达现场时间</div>
					<div class="ulforform-resizeable-input">
						<input class="easyui-datetimebox" style="width:160px;"  name="arrive_time" required   />
					</div>
                 </li>
                 <li class="ulforform-resizeable-group-single">
                    <div class="ulforform-resizeable-title">现场勘查描述</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-textbox" style="width:470px;height:50px;"   name="scene_desc" required   data-options="multiline:true" prompt="200字符以内。"
                        validType="length[200]"/>
                    </div>
                </li>
                <li class="ulforform-resizeable-group-single">
                    <div class="ulforform-resizeable-title">现场处理结果</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-textbox" style="width:470px;height:50px;"   name="scene_result" required   data-options="multiline:true" prompt="200字符以内。"
                        validType="length[200]"/>
                    </div>
                </li>
                 <li class="ulforform-resizeable-group">
                	<div class="ulforform-resizeable-title">是否进场维修</div>
					<div class="ulforform-resizeable-input">
						<select id="is_go_scene"   class="easyui-combobox" style="width:160px;" data-options="editable:false"    name="is_go_scene" required="true"   missingMessage="请选择指派对象">
                   		 	<option value="1">是</option>
                   		 	<option value="0">否</option>
                   		 </select>
					</div>
                 </li>
                 <li class="ulforform-resizeable-group is_go_scene">
                	<div class="ulforform-resizeable-title">维修场站</div>
					<div class="ulforform-resizeable-input">
						<!--  <select   class="easyui-combobox" style="width:160px;"  id="maintain_scene"  name="maintain_scene"   missingMessage="请选择指派对象">
                   		 	<?php //foreach ($maintain_scenes as $maintain_scene):?>
                   		 	<option value="<?php //echo $maintain_scene['text']?>"><?php //echo $maintain_scene['text']?></option>
                   		 	<?php //endforeach;?>
                   		 </select>-->
                   		 <input id="maintain_scene" style="width:160px;"  class="easyui-textbox" style="width:160px;"  name="maintain_scene"       />
					</div>
                 </li>
                <li class="ulforform-resizeable-group is_go_scene">
                    <div class="ulforform-resizeable-title">维修方联系人</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-textbox" style="width:160px;"  name="maintain_name"   />
                    </div>
                </li>
                <li class="ulforform-resizeable-group is_go_scene">
                    <div class="ulforform-resizeable-title">联系电话</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-textbox" style="width:160px;"   name="maintain_tel"   validType="match[/((0\d{2,3})-)(\d{7,8})(-(\d{3,}))?$|1[3|4|5|7|8][0-9]\d{8}$/]" invalidMessage="电话、手机格式错误！" prompt="电话号码格式 区号-号码"  />
                    </div>
                </li>
                <li class="ulforform-resizeable-group is_go_scene">
                    <div class="ulforform-resizeable-title">进场维修单号</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-textbox" style="width:160px;"   name="maintain_no"   />
                    </div>
                </li>
                <li class="ulforform-resizeable-group is_go_scene">
                    <div class="ulforform-resizeable-title">预计完成时间</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-datebox" style="width:160px;"  name="expect_time"   />
                    </div>
                </li>
                <li class="ulforform-resizeable-group-single is_go_scene">
                    <div class="ulforform-resizeable-title">是否替换车辆</div>
                    <div class="ulforform-resizeable-input">
                        <select  id="is_replace_car"  class="easyui-combobox" style="width:160px;" data-options="editable:false"    name="is_replace_car"    missingMessage="请选择指派对象">
                   		 	<option value="0">否</option>
                   		 	<option value="1">是</option>
                   		 	
                   		 </select>
                    </div>
                </li>
               <li class="ulforform-resizeable-group is_go_scene is_replace_car">
                	<div class="ulforform-resizeable-title">替换车</div>
					<div class="ulforform-resizeable-input">
						<!--  <select    class="easyui-combobox" style="width:160px;"    name="replace_car"   missingMessage="请选择指派对象">
                   		 	<?php //foreach ($cars as $car):?>
                   		 	<option value="<?php //echo $car['plate_number']?>"><?php //echo $car['plate_number']?></option>
                   		 	<?php //endforeach;?>
                   		 </select>-->
                   		 <input id="replace_car" style="width:160px;"  class="easyui-textbox" style="width:160px;"  name="replace_car"       />
					</div>
                 </li>
                 <li class="ulforform-resizeable-group is_go_scene is_replace_car">
                	<div class="ulforform-resizeable-title">交车方式</div>
					<div class="ulforform-resizeable-input">
						<select   class="easyui-combobox" style="width:160px;" data-options="editable:false"    name="replace_way"    missingMessage="请选择指派对象">
                   		 	<option value="1">自提</option>
                   		 	<option value="2">送车上门</option>
                   		 </select>
					</div>
                 </li>
                 <li class="ulforform-resizeable-group is_go_scene is_replace_car">
                    <div class="ulforform-resizeable-title">替换开始时间</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-datebox" style="width:160px;"   name="replace_start_time"   />
                    </div>
                </li>
                <li class="ulforform-resizeable-group is_go_scene is_replace_car">
                    <div class="ulforform-resizeable-title">预计归还时间</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-datebox" style="width:160px;"   name="replace_end_time"   />
                    </div>
                </li>
                <li class="ulforform-resizeable-group is_go_scene">
                    <div class="ulforform-resizeable-title">外勤过路费</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-textbox" style="width:160px;"   name="field_tolls"   />
                    </div>
                </li>
                <li class="ulforform-resizeable-group is_go_scene">
                    <div class="ulforform-resizeable-title">外勤停车费</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-textbox" style="width:160px;"   name="parking"   />
                    </div>
                </li>
				 
               <li class="ulforform-resizeable-group-single">
                <div class="ulforform-resizeable-title">上传故障照片</div>
                <div class="ulforform-resizeable-input">
                    <ul style="padding:0;margin:0;list-style:none;overflow:hidden;" id="process-repair-uploadfile">
                        <?php
                             $thumbs = [
                                ['car_no_img','车牌照片'],
                                ['dashboard_img','车辆仪表照片'],
                                ['fault_scene_img','故障现场照片'],
                                ['fault_location_img','故障部位照片'],
                                ['field_record_img','外勤服务记录照片'],
                                ['maintain_jieche_img','维修站点接车单照片'],
                            ]; 
                            foreach($thumbs as $key=>$item){
                        ?>
                            <li style="float:left;margin-right:16px;position:relative;cursor:pointer"   id="thumbs<?php echo $key;?>">
                                <div style="width:100px;height:100px;">
                                    <img class="repairImg" src="<?php echo !empty($field_record[$item[0]])? $field_record[$item[0]]:'./images/add.jpg'?>" width="100" height="100" />
                                    <input type="hidden" name="<?php echo $item[0]; ?>" />
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
</div>
<iframe id="iframe-process-repair-uploadimage" name="iframe-process-repair-uploadimage" style="display:none;"></iframe>
<div id="easyui-dialog-process-repair-uploadimage"></div>
<script>
$('#easyui-form-process-repair-field-from').form('load',<?= json_encode($field_record); ?>);


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
	$('#replace_car').combobox({ 
	    //  url:"", 
	     // editable:false, //不可编辑状态
	      cache: false,
	    //  panelHeight: 'auto',//自动高度适合
	      valueField:'plate_number',   
	      textField:'plate_number',
	      data: <?= json_encode($cars); ?>,
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

	$("#is_replace_car").combobox({
	    onChange:function(newValue,oldValue){
	    	is_replace_car(newValue);	
	    }
}); 	
	 //初始化
	 is_go_scene($("#is_go_scene").val());
	 is_replace_car($("#is_replace_car").val());
});


//是否进场维修
function is_go_scene(is_go_scene)
{
	//alert($("#is_go_scene").val());
	if(is_go_scene == 0)
	{ 
		$("#thumbs5").css('display','none');
		$(".is_go_scene").css('display','none');
	}else{
		$("#thumbs5").css('display','block');
		$(".is_go_scene").css('display','block');
	}
}

//是否替换车辆
function is_replace_car(is_replace_car)
{
	if(is_replace_car == 0)
	{ 
		$(".is_replace_car").css('display','none');
	}else{
		$(".is_replace_car").css('display','block');
	}
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
    ProcessRepairUpload.init();
</script>