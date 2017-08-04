<div style="padding:10px 40px 20px 40px">  
    <form id="easyui-form-process-repair-maintain-reg-from" class="easyui-form" method="post">
    <input type="hidden" name="id" value="<?php echo $maintain_result['id']?>" />
    <input type="hidden" name="order_no"  value="<?php echo $order_no; ?>"/>
    <input type="hidden" name="maintain_way" value="<?php echo $maintain_way;?>"/>
        <div > 
            <ul class="ulforform-resizeable">
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">车牌号</div>
                    <div class="ulforform-resizeable-input">
                      <input class="easyui-textbox" style="width:160px;" data-options="editable:false" name="car_no"     value="<?php echo $car_no;?>"  />
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                	<div class="ulforform-resizeable-title">故障处理结果</div>
					<div class="ulforform-resizeable-input">
						<select   class="easyui-combobox" style="width:160px;"data-options="editable: false "    name="fault_result" required="true"   missingMessage="请填写故障处理结果">
                   		 	<option value="已修复">已修复</option>
                   		 </select>
					</div>
                 </li>
                 <li class="ulforform-resizeable-group-single">
                    <div class="ulforform-resizeable-title">故障引发原因</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-textbox" style="width:470px;height:50px;"  required  name="fault_why"   data-options="multiline:true" prompt="200字符以内。"
                        validType="length[200]"/>
                    </div>
                </li>
                <li class="ulforform-resizeable-group-single">
                    <div class="ulforform-resizeable-title">故障处理方法</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-textbox" style="width:470px;height:50px;" required   name="maintain_method"   data-options="multiline:true" prompt="200字符以内。"
                        validType="length[200]"/>
                    </div>
                </li>
                <li class="ulforform-resizeable-group ">
                    <div class="ulforform-resizeable-title">维修结束时间</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-datetimebox" style="width:160px;"  name="leave_factory_time"   />
                    </div>
                </li>
                <li class="ulforform-resizeable-group ">
                    <div class="ulforform-resizeable-title">接车人员</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-textbox" style="width:160px;"  name="jieche_name"   />
                    </div>
                </li>
				<li class="ulforform-resizeable-group ">
                    <div class="ulforform-resizeable-title">替换车：</div>
                    <div class="ulforform-resizeable-title">
                        <?=$replace_car?$replace_car:'无'?>
                    </div>
                </li>
                <li class="ulforform-resizeable-group ">
                    <div class="ulforform-resizeable-title">替换车归还时间</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-datebox" style="width:160px;"  name="return_replace_time"   />
                    </div>
                </li>
                <li class="ulforform-resizeable-group ">
                    <div class="ulforform-resizeable-title">替换车接车人员</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-textbox" style="width:160px;"  name="replace_jieche_name"   />
                    </div>
                </li>
                 <li class="ulforform-resizeable-group">
                	<div class="ulforform-resizeable-title">是否收取维修费</div>
					<div class="ulforform-resizeable-input">
						<select  class="easyui-combobox" style="width:160px;" data-options="editable:false"    name="is_maintain_cost" required="true"   >
                   		 	<option value="1">是</option>
                   		 	<option value="0">否</option>
                   		 </select>
					</div>
                 </li>
                <li class="ulforform-resizeable-group ">
                    <div class="ulforform-resizeable-title">维修费用</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-textbox" style="width:160px;"  name="maintain_cost"   />
                    </div>
                </li>
                 <li class="ulforform-resizeable-group-single ">
                    <div class="ulforform-resizeable-title">更换配件</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-textbox" style="width:470px;"  name="accessories"   />
                    </div>
                </li>
                
                <?php if($maintain_way == '进厂维修'):?>
                <li class="ulforform-resizeable-group-single">
	                <div class="ulforform-resizeable-title">上传照片</div>
	                <div class="ulforform-resizeable-input">
	                    <ul style="padding:0;margin:0;list-style:none;overflow:hidden;" id="process-repair-uploadfile">
	                        <?php
	                            $thumbs = [
	                                ['leave_jieche_img','出厂接车单'],
	                            ];
	                            foreach($thumbs as $key=>$item){
	                        ?>
	                            <li style="float:left;margin-right:16px;position:relative;cursor:pointer" >
	                                <div style="width:100px;height:100px;">
	                                    <img  id="<?php echo $item[0]; ?>"  class="repairImg" src="./images/add.jpg" width="100" height="100"  />
	                                    <input type="hidden" name="<?php echo $item[0]; ?>"  />
	                                </div>
	                                <div class="imgTitle" style="position:absolute;bottom:0;left:0;background:rgba(224,236,255,0.5);width:100px;text-align:center;line-height:24px;"><?php echo $item[1]; ?></div>
	                                <div class="removeIcon" style="position:absolute;top:0;right:0;background:rgba(224,236,255,0.5);display:none;"><img src="./jquery-easyui-1.4.3/themes/icons/clear.png" width="14px" height="14px" /></div>
	                            </li>
	                        <?php } ?>
	                    </ul>
	                </div>
            	</li>
            <?php endif;?>
            
            
            </ul>
        </div>
    </form>
</div>
<iframe id="iframe-process-repair-uploadimage" name="iframe-process-repair-uploadimage" style="display:none;"></iframe>
<div id="easyui-dialog-process-repair-uploadimage"></div>
<script>
$('#easyui-form-process-repair-maintain-reg-from').form('load',<?= json_encode($maintain_result); ?>);
                      
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