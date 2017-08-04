<div style="padding:10px;">
    <form id="easyui-form-car-fault-edit">
        <input type="hidden" name="id" />
        <ul class="ulforform-resizeable">
            <li class="ulforform-resizeable-group">
                <div class="ulforform-resizeable-title">故障车辆</div>
                <div class="ulforform-resizeable-input">
                    <input
                        id="easyui-form-car-fault-edit-carCombogrid"
                        name="car_id"
                        style="width:180px;"
                        disabled="true"
                        />
                </div>
            </li>
            <li class="ulforform-resizeable-group">
                <div class="ulforform-resizeable-title">故障状态</div>
                <div class="ulforform-resizeable-input">
                    <select
                        class="easyui-combobox"
                        name="fault_status"
                        style="width:180px;"
                        required="true"
                        editable="false"
                        data-options="panelHeight:'auto'"
                        >
                        <?php foreach($config['fault_status'] as $val){ ?>
                            <option value="<?= $val['value'] ?>"><?= $val['text'] ?></option>
                        <?php } ?>
                    </select>
                </div>
            </li>
            <li class="ulforform-resizeable-group">
                <div class="ulforform-resizeable-title">故障发生时间</div>
                <div class="ulforform-resizeable-input">
                    <input
                        class="easyui-datebox"
                        name="f_datetime"
                        style="width:180px;"
                        required="true"
                        />
                </div>
            </li>
            <li class="ulforform-resizeable-group-single">
                <div class="ulforform-resizeable-title">故障发生地点</div>
                <div class="ulforform-resizeable-input">
                    <input
                        class="easyui-textbox"
                        name="f_place"
                        style="width:800px;"
                        required="true"
                        validType="length[255]"
                        />
                </div>
            </li>
            <li class="ulforform-resizeable-group">
                <div class="ulforform-resizeable-title">故障反馈人</div>
                <div class="ulforform-resizeable-input">
                    <input
                        class="easyui-textbox"
                        name="fb_name"
                        style="width:180px;"
                        required="true"
                        validType="length[50]"
                        />
                </div>
            </li>
            <li class="ulforform-resizeable-group">
                <div class="ulforform-resizeable-title">联系电话</div>
                <div class="ulforform-resizeable-input">
                    <input
                        class="easyui-textbox"
                        name="fb_mobile"
                        style="width:180px;"
                        />
                </div>
            </li>
            <li class="ulforform-resizeable-group">
                <div class="ulforform-resizeable-title">故障反馈时间</div>
                <div class="ulforform-resizeable-input">
                    <input
                        class="easyui-datebox"
                        name="fb_date"
                        style="width:180px;"
                        value="<?php echo date('Y-m-d'); ?>"
                        />
                </div>
            </li>
            <li class="ulforform-resizeable-group-single">
                <div class="ulforform-resizeable-title">故障现象描述</div>
                <div class="ulforform-resizeable-input">
                    <input
                        class="easyui-textbox"
                        name='f_desc'
                        data-options="multiline:true"
                        style="height:60px;width:800px;"
                        required="true"
                        prompt="请填写车辆故障的详细表现，500字符以内。"
                        validType="length[500]"
                        />
                </div>
            </li>
            <li class="ulforform-resizeable-group-single">
                <div class="ulforform-resizeable-title">故障引发原因</div>
                <div class="ulforform-resizeable-input">
                    <input
                        class="easyui-textbox"
                        name='f_reason'
                        data-options="multiline:true"
                        style="height:60px;width:800px;"
                        prompt="请填写引发该故障的原因，500字符以内。"
                        validType="length[500]"
                        />
                </div>
            </li>
            <li class="ulforform-resizeable-group">
                <div class="ulforform-resizeable-title">本方初次受理人</div>
                <div class="ulforform-resizeable-input">
                    <input
                        class="easyui-textbox"
                        name="ap_name"
                        style="width:180px;"
                        required="true"
                        missingMessage="初次收到故障反馈的本方人员。"
                        validType="length[50]"
                        />
                </div>
            </li>
            <li class="ulforform-resizeable-group">
                <div class="ulforform-resizeable-title">故障上报时间</div>
                <div class="ulforform-resizeable-input">
                    <input
                        class="easyui-datebox"
                        name="report_date"
                        style="width:180px;"
                        missingMessage="将故障上报给4S店或厂家的时间。"
                        />
                </div>
            </li>
            <li class="ulforform-resizeable-group">
                <div class="ulforform-resizeable-title">预计完结日期</div>
                <div class="ulforform-resizeable-input">
                    <input
                        class="easyui-datebox"
                        name="expect_end_date"
                        style="width:180px;"
                        missingMessage="预计故障可修复的日期。"
                        />
                </div>
            </li>
            <li class="ulforform-resizeable-group">
                <div class="ulforform-resizeable-title">维修方负责人</div>
                <div class="ulforform-resizeable-input">
                    <input
                        class="easyui-textbox"
                        name="fzr_name"
                        style="width:180px;"
                        validType="length[50]"
                        missingMessage="如果车辆送修，请填写维修方的对接人；如果没有送修，则留空。"
                        />
                </div>
            </li>
            <li class="ulforform-resizeable-group">
                <div class="ulforform-resizeable-title">联系电话</div>
                <div class="ulforform-resizeable-input">
                    <input
                        class="easyui-textbox"
                        name="fzr_mobile"
                        style="width:180px;"
                        />
                </div>
            </li>
            <li class="ulforform-resizeable-group">
                <div class="ulforform-resizeable-title">进厂维修单号</div>
                <div class="ulforform-resizeable-input">
                    <input
                        class="easyui-textbox"
                        name="repair_order_no"
                        style="width:180px;"
                        />
                </div>
            </li>
            <li class="ulforform-resizeable-group-single">
                <div class="ulforform-resizeable-title">故障处理方法</div>
                <div class="ulforform-resizeable-input">
                    <input
                        class="easyui-textbox"
                        name='f_dispose'
                        data-options="multiline:true"
                        style="height:60px;width:800px;"
                        validType="length[500]"
                        prompt="请填写该故障计划采用或已实施的修复方法，500字符以内。"
                        />
                </div>
            </li>
            <li class="ulforform-resizeable-group-single">
                <div class="ulforform-resizeable-title">上传故障照片</div>
                <div class="ulforform-resizeable-input">
                    <ul style="padding:0;margin:0;list-style:none;overflow:hidden;" id="car-fault-edit-uploadfile">
                        <?php
                            $thumbs = [
                                ['thumb_plate_number','车牌照片'],
                                ['thumb_meter','车辆仪表'],
                                ['thumb_scene','故障现场照片'],
                                ['thumb_place','故障位置照片'],
                                ['thumb_fb','反馈人签名'],
                                ['thumb_repair_order','进厂维修单']
                            ];
                            foreach($thumbs as $item){
                        ?>
                            <li style="float:left;margin-right:16px;position:relative;cursor:pointer;">
                                <div style="width:100px;height:100px;">
                                    <img class="faultImg" src="<?php echo $faultInfo[$item[0]]!='' ? './uploads/image/fault/'.$faultInfo[$item[0]] : './images/add.jpg'; ?>" width="100" height="100" />
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
    </form>
</div>
<iframe id="iframe-car-fault-edit-uploadimage" name="iframe-car-fault-edit-uploadimage" style="display:none;"></iframe>
<div id="easyui-dialog-car-fault-edit-uploadimage"></div>
<script type="text/javascript">
    var CarFaultEdit = {
        init: function(){
            //初始化照片上传窗口
            $('#easyui-dialog-car-fault-edit-uploadimage').dialog({
                title: '车辆故障照片上传',
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
                        var form = $('#easyui-form-car-fault-edit-upload-window');
                        if(!form.form('validate')){
                            return false;
                        }
                        form.submit();
                    }
                },{
                    text:'取消',
                    iconCls:'icon-cancel',
                    handler:function(){
                        $('#easyui-dialog-car-fault-edit-uploadimage').dialog('close');
                    }
                }],
                onClose: function(){
                    $(this).window('clear');
                }
            });

            //给上传故障图片绑定各类事件
            $('#car-fault-edit-uploadfile').children('li')
                .click(function(){ //单击打开上传窗口
                    var columnName = $(this).find('input').attr('name');
                    $('#easyui-dialog-car-fault-edit-uploadimage')
                        .dialog('open')
                        .dialog('refresh',"<?= yii::$app->urlManager->createUrl(['car/fault/upload-window']); ?>&isEdit=1&columnName="+columnName);
                })
                .mouseover(function(){
                    var imgSrc = $(this).find('img.faultImg').attr('src');
                    if(imgSrc != './images/add.jpg'){
                        //显示删除图标并绑定删除事件
                        $(this).find('div.removeIcon').show().click(function(e){
                            e.stopPropagation();
                            $(this).parent().find('img.faultImg').attr('src','./images/add.jpg');
                            $(this).parent().find('input').val('');
                        });
                    }
                })
                .mouseleave(function(){
                    $(this).find('div.removeIcon').hide();
                });

            // 放大显示上传图片
            $('#car-fault-edit-uploadfile').children('li').each(function(){
                var imgSrc = $(this).find('img.faultImg').attr('src');
                if(imgSrc != './images/add.jpg') {
                    $(this).tooltip({
                        position: 'top',
                        content: '<img src="' + imgSrc + '" width="350px" height="350px" border="0" />'
                    });
                }
            });

            //初始化-车辆combogrid
            $('#easyui-form-car-fault-edit-carCombogrid').combogrid({
                panelWidth: 450,
                panelHeight: 200,
                required: true,
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

            //表单赋值
            var faultInfo = <?php echo json_encode($faultInfo); ?>;
            $('#easyui-form-car-fault-edit').form('load',faultInfo);
            //注意：加载成功后，若原本应显示为text的车牌号不存在，则改成显示车架号为text！
            var _combogrid = $('#easyui-form-car-fault-edit-carCombogrid');
            _combogrid.combogrid('grid').datagrid({
                queryParams: {'car_id':faultInfo.car_id},
                onLoadSuccess: function(data){
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
        },
        uploadComplete: function(rData){
            if(rData.status){
                $('#easyui-dialog-car-fault-edit-uploadimage').dialog('close');
                var inputControl = $('#car-fault-edit-uploadfile').find('input[name='+rData.columnName+']');
                inputControl.val(rData.storePath);
                inputControl.siblings('img').attr('src','uploads/image/fault/'+rData.storePath);
                // 放大显示上传图片
                inputControl.parent().parent().tooltip({
                    position: 'top',
                    content: '<img src="' + 'uploads/image/fault/'+rData.storePath + '" width="350px" height="350px" border="0" />'
                });
            }else{
                $.messager.alert('上传错误',rData.info,'error');
            }
        }
    };
    CarFaultEdit.init();
</script>