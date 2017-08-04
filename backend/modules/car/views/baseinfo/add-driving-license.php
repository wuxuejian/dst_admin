<div style="padding:15px"> 
    <form id="easyui-form-car-baseinfo-driving-license-add" method="post">
        <input type="hidden" name="car_id" value="<?php echo $carId; ?>" />
        <table cellpadding="8" cellspacing="0">
            <tr>
                <td><div style="width:85px;text-align:right;">登记地址</div></td>
                <td colspan="3">
                    <select
                        class="easyui-combobox"
                        style="width:440px;"
                        name="addr"
                        required="true"
                        editable="false"
                    >
                       <?php foreach($config['DL_REG_ADDR'] as $val){ ?>
                        <option value="<?php echo $val['value']; ?>"><?php echo $val['text']; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">注册时间</div></td>
                <td>
                    <input
                        class="easyui-datebox"
                        style="width:160px;"
                        name="register_date"
                        required="true"
                        missingMessage="请选择行驶证注册时间！"
                        validType="date"
                    />
                </td>
                <td><div style="width:85px;text-align:right;">发证日期</div></td>
                <td>
                    <input
                        class="easyui-datebox"
                        style="width:160px;"
                        name="issue_date"
                        required="true"
                        missingMessage="请选择行驶证发证日期！"
                        validType="date"
                    />
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">档案编号</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="archives_number"
                        validType="length[50]"
                    />
                </td>
                <td><div style="width:85px;text-align:right;">整备质量</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="total_mass"
                        required="true"
                        missingMessage="请输入整备质量！"
                        validType="int"
                    /> kg
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">强制报废日期</div></td>
                <td>
                    <input
                        class="easyui-datebox"
                        style="width:160px;"
                        name="force_scrap_date"
                        required="true"
                        missingMessage="请选择行驶证强制报废日期！"
                        validType="date"
                    />
                </td>
                <td><div style="width:85px;text-align:right;">检验有效期至</div></td>
                <td>
                    <input
                        class="easyui-datebox"
                        style="width:160px;"
                        name="valid_to_date"
                        required="true"
                        missingMessage="请选择行驶证检验有效期至！"
                        validType="date"
                    />
                </td>
            </tr>
             <tr>
	                <td><div style="width:85px;text-align:right;">上传行驶证照片</div></td>
	                <td>
	                    <ul style="padding:0;margin:0;list-style:none;overflow:hidden;" id="process-repair-uploadfile">
	                        <?php
	                            $thumbs = [
	                                ['image','行驶证照片'],
	                            ];
	                            foreach($thumbs as $key=>$item){
	                        ?>
	                            <li style="float:left;margin-right:16px;position:relative;cursor:pointer" >
	                                <div style="width:100px;height:100px;">
	                                    <img  id="<?php echo $item[0]; ?>"  class="repairImg" src="<?php echo !empty($config['image']) ? $licenseInfo['image']:'./images/add.jpg'; ?>" width="100" height="100" />
	                                    <input type="hidden" name="<?php echo $item[0]; ?>"  />
	                                </div>
	                                <div class="imgTitle" style="position:absolute;bottom:0;left:0;background:rgba(224,236,255,0.5);width:100px;text-align:center;line-height:24px;"><?php echo $item[1]; ?></div>
	                                <div class="removeIcon" style="position:absolute;top:0;right:0;background:rgba(224,236,255,0.5);display:none;"><img src="./jquery-easyui-1.4.3/themes/icons/clear.png" width="14px" height="14px" /></div>
	                            </li>
	                            
	                        <?php } ?>
	                    </ul>
	                </td>
	               <td><?php if(!empty($config['image'])):?><span><a href="<?php echo $config['image']?>" target="_blank" style="text-decoration:none">查看大图</a></span><?php endif;?></td>
	            </tr>
        </table>
    </form>
</div>
<iframe id="iframe-process-repair-uploadimage" name="iframe-process-repair-uploadimage" style="display:none;"></iframe>
<div id="easyui-dialog-process-repair-uploadimage"></div>
<script>
    var oldData = <?php echo json_encode($config); ?>;
    oldData.register_date = parseInt(oldData.register_date) > 0 ? formatDateToString(oldData.register_date) : '';
    oldData.issue_date = parseInt(oldData.issue_date) > 0 ? formatDateToString(oldData.issue_date) : '';
    oldData.force_scrap_date = parseInt(oldData.force_scrap_date) > 0 ? formatDateToString(oldData.force_scrap_date) : '';
    oldData.valid_to_date = parseInt(oldData.valid_to_date) > 0 ? formatDateToString(oldData.valid_to_date) : '';
    $('#easyui-form-car-baseinfo-driving-license').form('load',oldData);



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