<form id="easyui-form-car-type-add" class="easyui-form">
    <div
        class="easyui-panel"
        title="基本参数"
        style="width:100%;margin-bottom:5px;"
        closable="false"
        collapsible="false"
        minimizable="false"
        maximizable="false"
        border="false"
    >
        <table cellpadding="5" cellspacing="0">
            
            <tr>
                <td align="right"><div style="width:70px;">车辆品牌</div></td>
                <td>
                    <input class="easyui-combotree" name="brand_id" required="true"
                           data-options="
                                width:160,
                                url: '<?php echo yii::$app->urlManager->createUrl(['car/combotree/get-car-brands']); ?>',
                                editable: false,
                                panelHeight:'auto',
                                lines:false
                           "
                    />
                </td>
                <td align="right"><div style="width:70px;">车辆类型</div></td>
                <td>
                    <select
                        class="easyui-combobox"
                        required="true"
                        style="width:160px;"
                        name="car_type"
                        editable="false"
                        data-options="panelHeight:'auto'"
                    >
                        <option value=""></option>
                        <?php foreach($config['car_type'] as $val){ ?>
                        <option value="<?php echo $val['value']; ?>"><?php echo $val['text']; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right"><div style="width:70px;">车辆型号</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        required="true"
                        style="width:160px;"
                        name="car_model"
                        validType="length[100]"
                        data-options="{onChange: function(){
                            CarBaseinfoAdd.getCarModelName($(this).val());
                        }}"
                    />
                </td>
               <!--  <td align="right"><div style="width:70px;">车型名称</div></td>
                <td>
                    <input
                        id="car_baseinfo_add_input_car_brand_name"
                        required="true"
                        class="easyui-textbox"
                        style="width:160px;"
                        readonly="true"
                        
                    />
                </td> -->
                <td align="right"><div style="width:70px;">车型名称</div></td>
                <td>
                    <input
                        id="car_baseinfo_add_input_car_brand_name2"
                        required="true"
                        class="easyui-textbox"
                        style="width:160px;"
                        name="car_model_name_"
                        prompt="手动输入车型名称"
                        
                        
                    />
                </td>
            </tr>
            <tr>
                <td align="right"><div style="width:70px;">车辆制造厂</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:454px;"
                        name="manufacturer_name"
                        validType="length[100]"
                    />
                </td>
                
            </tr>
            
            
        </table>
    </div>

    <div
        class="easyui-panel"
        title="车身参数"
        style="width:100%;margin-bottom:5px;"
        closable="false"
        collapsible="false"
        minimizable="false"
        maximizable="false"
        border="false"
    >
        <table cellpadding="5" cellspacing="0">
            <tr>
                <td align="right"><div style="width:70px;">外廓长度(mm)</div></td>
                <td>
                    <input
                        required="true"
                        class="easyui-textbox"
                        style="width:160px;"
                        name="outside_long"
                        validType="int"
                        invalidMessage="请输入整形值！"
                    />
                </td>
                <td align="right"><div style="width:70px;">外廓宽度(mm)</div></td>
                <td>
                    <input
                        required="true"
                        class="easyui-textbox"
                        style="width:160px;"
                        name="outside_width"
                        validType="int"
                        invalidMessage="请输入整形值！"
                    />
                </td>
                <td align="right"><div style="width:70px;">外廓高度(mm)</div></td>
                <td>
                    <input
                        required="true"
                        class="easyui-textbox"
                        style="width:160px;"
                        name="outside_height"
                        validType="int"
                        invalidMessage="请输入整形值！"
                    />
                </td>
            </tr>
            <tr>
                <td align="right"><div style="width:70px;">车厢长度(mm)</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="inside_long"
                        validType="int"
                        invalidMessage="请输入整形值！"
                    />
                </td>
                <td align="right"><div style="width:70px;">车厢宽度(mm)</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="inside_width"
                        validType="int"
                        invalidMessage="请输入整形值！"
                    />
                </td>
                <td align="right"><div style="width:70px;">车厢高度(mm)</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="inside_height"
                        validType="int"
                        invalidMessage="请输入整形值！"
                    />
                </td>
            </tr>
            <tr>
                <td align="right"><div style="width:70px;">轴距(mm)</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="shaft_distance"
                        validType="int"
                        invalidMessage="请输入整形值！"
                    />
                </td>
                <td align="right"><div>前轮距(mm)</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="wheel_distance_f"
                        validType="int"
                        invalidMessage="请输入整型值！"
                    />
                </td>
                <td align="right"><div>后轮距(mm)</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="wheel_distance_b"
                        validType="int"
                        invalidMessage="请输入整型值！"
                    />
                </td>
            </tr>
            <tr>
                <td align="right"><div style="width:70px;">容积(L)</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="cubage"
                        validType="float"
                        
                    />
                </td>
                <td align="right"><div style="width:70px;">接近角(°)</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="approach_angle"
                        validType="float"
                        
                    />
                </td>
                <td align="right"><div style="width:70px;">离去角(°)</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="departure_angle"
                        validType="float"
                    />
                </td>
            </tr>
            <tr>
                <td align="right"><div style="width:70px;">总质量(kg)</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="total_mass"
                        validType="int"
                        invalidMessage="请输入整形值！"
                    />
                </td>
                <td align="right"><div>整备质量(kg)</div></td>
                <td>
                    <input
                        required="true"
                        class="easyui-textbox"
                        style="width:160px;"
                        name="empty_mass"
                        validType="int"
                        invalidMessage="请输入整形值！"
                    />
                </td>
                <td align="right"><div>额定载重质量(kg)</div></td>
                <td>
                    <input
                        required="true"
                        class="easyui-textbox"
                        style="width:160px;"
                        name="check_mass"
                        validType="int"
                        invalidMessage="请输入整形值！"
                    />
                </td>
            </tr>
            <tr>
                <td align="right"><div>驾驶室乘客数量</div></td>
                <td>
                     <select
                        required="true"
                        class="easyui-combobox"
                        style="width:160px;"
                        name="cab_passenger"
                    >
                        <option value="0">请选择</option>   
                        <option value="1">2人</option>   
                        <option value="2">3人</option>
                        <option value="3">4人</option>   
                        <option value="4">5人</option>   
                            
                    </select>
                </td>
                <td align="right"><div style="width:70px;">轮胎型号</div></td>
                <td>
                    <input
                        required="true"
                        class="easyui-textbox"
                        style="width:160px;"
                        name="wheel_specifications"
                        validType="length[50]"
                    />
                </td>
                <!-- <td align="right"><div style="width:70px;">轮胎数量</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="wheel_amount"
                        validType="int"
                        invalidMessage="请输入整形值！"
                    />
                </td> -->
            </tr>
            
        </table>
    </div>
    <div
        class="easyui-panel"
        title="电动机"
        style="width:100%;margin-bottom:5px;"
        
        closable="false"
        collapsible="false"
        minimizable="false"
        maximizable="false"
        border="false"
    >
       <table cellpadding="5" cellspacing="0">
            <tr>
                <td align="right"><div style="width:70px;">发动机型号</div></td>
                <td>
                    <input
                        required="true"
                        class="easyui-textbox"
                        style="width:160px;"
                        name="engine_model"
                        validType="length[50]"
                    />
                </td>
                <td align="right"><div style="width:70px;">燃料形式</div></td>
                <td>
                    <select
                        required="true"
                        class="easyui-combobox"
                        style="width:160px;"
                        name="fuel_type"
                        editable="false"
                        data-options="panelHeight:'auto'"
                        required="true"
                    >
                        <option value=""></option>
                        <?php foreach($config['fuel_type'] as $val){ ?>
                        <option value="<?php echo $val['value']; ?>"><?php echo $val['text']; ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td align="right"><div style="width:70px;">排量(mL)</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="displacement"
                        validType="int"
                    />
                </td>
            </tr>
            <tr>
                <td align="right"><div style="width:92px;">工部续航里程(km)</div></td>
                <td>
                    <input
                        required="true"
                        class="easyui-textbox"
                        style="width:160px;"
                        name="endurance_mileage"
                        validateType="int"
                        data-options="panelHeight:'auto'"
                        required="true" 
                    />
                </td>
                <td align="right"><div style="width:92px;">驱动电机额定功率(kW)</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="rated_power"
                        validType="int"
                    />
                </td>
                <td align="right"><div style="width:92px;">驱动电机峰值功率(kW)</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="peak_power"
                        validType="int"
                    />
                </td>
            </tr>
            <tr>
                <td align="right"><div style="width:80px;">动力电池容量(kW·h)</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="power_battery_capacity"
                        validType="int"
                    />
                </td>
                <td align="right"><div style="width:70px;">动力电池生产厂家</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="power_battery_manufacturer"
                        
                    />
                </td>
                <td align="right"><div style="width:70px;">驱动电机生产厂家</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="drive_motor_manufacturer"
                        
                    />
                </td>
            </tr>
            <tr>
                <td align="right"><div style="width:70px;">最高车速(km/h)</div></td>
                <td>
                    <input
                        required="true"
                        class="easyui-textbox"
                        style="width:160px;"
                        name="max_speed"
                        validType="int"
                    />
                </td>
                <td align="right"><div style="width:70px;">充电时间(h)</div></td>
                <td>
                    
                    <input
                        class="easyui-textbox"
                        style="width:60px;"
                        name="fast_charging_time"
                        validType="match[/^[0-9]+([.]{1}[0-9]{1,1})?$/]"
                        prompt="快充"
                    />
                    <input
                        class="easyui-textbox"
                        style="width:60px;"
                        name="slow_charging_time"
                        validType="match[/^[0-9]+([.]{1}[0-9]{1,1})?$/]"
                        prompt="慢充"
                    />
                </td>
                <td align="right"><div style="width:70px;">充电方式</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="charging_type"
                        
                    />
                </td>
            </tr>
            
        </table>
    </div>
    <div
        class="easyui-panel"
        title="车辆图片"
        style="width:100%;margin-bottom:5px;"
        
        closable="false"
        collapsible="false"
        minimizable="false"
        maximizable="false"
        border="false"
    >
       <table cellpadding="5" cellspacing="0">
           <!--  <div class="ulforform-resizeable-title">上传照片</div> -->
                    <!-- <div class="ulforform-resizeable-input">
                        <ul style="padding:0;margin:0;list-style:none;overflow:hidden;" id="process-repair-uploadfile">
                            <?php
                                $thumbs = [
                                  ['car_front_img','车头'],
                                    ['car_left_img','车辆全身'],
                                    ['car_right_img','充电口'],
                                    ['car_tail_img','车厢'],
                                    ['car_control_img','中控'],
                                    ['car_full_img','全车']
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
                    </div> -->
                    <td>
                        <ul style="padding:0;margin:0;list-style:none;overflow:hidden;" id="process-repair-uploadfile">
                            <?php
                                $thumbs = [
                                  ['car_front_img','车头'],
                                    ['car_left_img','车辆全身'],
                                    ['car_right_img','充电口'],
                                    ['car_tail_img','车厢'],
                                    ['car_control_img','中控'],
                                    ['car_full_img','全车']
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
            
        </table>
    </div>
</form>

<!-- <div id="easyui-dialog-process-repair-uploadimage"></div> -->

<iframe id="iframe-process-repair-uploadimage" name="iframe-process-repair-uploadimage" style="display:none;"></iframe>
<div id="easyui-dialog-process-repair-uploadimage"></div>
<script>

    var CarBaseinfoAdd = {
        //获取填写车型的车辆名称
        getCarModelName: function(carBrand){
            var carBrandNameInput = $('#car_baseinfo_add_input_car_brand_name');
            if(!carBrand){
                carBrandNameInput.textbox('setValue','未知');
                return false;
            }
            var carBrandName = <?php echo json_encode($config['car_model_name']); ?>;
            if(carBrandName[carBrand]){
                carBrandNameInput.textbox('setValue',carBrandName[carBrand].text);
            }else{
                carBrandNameInput.textbox('setValue','未知');
            }
        },
        auto_complete: function(){
            var form = $('#car-baseinfo-add-auto-complete');
            if(!form.form('validate')){
                return false;
            }
            $.ajax({
                "type": 'get',
                "url":"<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/auto-complete']);?>",
                "data": form.serialize(),
                "dataType": "json",
                "success": function(data){
                    if(data.status){
                        $("#easyui-form-car-baseinfo-add").form('load',data.info);
                    }else{
                        $.messager.alert('错误',data.info,'error');
                    }
                }
            });
        }
    };


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