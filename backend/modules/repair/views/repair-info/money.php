<div id="easyui-datagrid-repair-repair-info-money-toolbar">
    <div class="data-search-form">
        <form action="#" method="post" id="repair-money-feng">
            <table cellpadding="8" cellspacing="0">
                <tr>
                    <td>备注:</td>
                    <td><input class="easyui-textbox" data-options="multiline:true" name="money_note" style="width:500px;height:100px" required="true"></td>
                </tr>
                <tr>
                    <td>上传凭证:</td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <ul style="padding:0;margin:0;list-style:none;overflow:hidden;" id="pay-money-uploadfile">
                            <?php
                            $thumbs = [
                                ['money_img','付款凭证'],
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
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
<iframe id="iframe-process-repair-uploadimage" name="iframe-process-repair-uploadimage" style="display:none;"></iframe>
<div id="easyui-dialog-repair-repair-info-uploadimage"></div>
<script>
    var PayMoney = new Object();
    PayMoney.init = function(){
        //初始化照片上传窗口
        $('#easyui-dialog-repair-repair-info-uploadimage').dialog({
            title: '照片上传',
            width: 500,
            height: 160,
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
                    var form = $('#easyui-form-repair-repai-infor-upload-window-feng');
                    if(!form.form('validate')){
                        return false;
                    }
                    form.submit();
                }
            },{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui-dialog-repair-repair-info-uploadimage').dialog('close');
                }
            }],
            onClose: function(){
                $(this).window('clear');
            }
        });

        //给上传故障图片绑定各类事件
        $('#pay-money-uploadfile').children('li')
            .click(function(){ //单击打开上传窗口
                var columnName = $(this).find('input').attr('name');
                $('#easyui-dialog-repair-repair-info-uploadimage')
                    .dialog('open')
                    .dialog('refresh',"<?= yii::$app->urlManager->createUrl(['repair/repair-info/upload-window-feng']); ?>&columnName="+columnName);
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

    PayMoney.uploadComplete = function(rData){
        if(rData.status){
            $('#easyui-dialog-repair-repair-info-uploadimage').dialog('close');
            var inputControl = $('#pay-money-uploadfile').find('input[name='+rData.columnName+']');
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

    PayMoney.init();
</script>