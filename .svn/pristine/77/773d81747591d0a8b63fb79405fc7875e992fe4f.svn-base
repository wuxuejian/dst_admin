<?php
if($buttonAutoComplete){
?>
<div style="width:100%;height:100px;">
<div
    class="easyui-panel" title="信息自动补填操作"
    style="padding:10px;"
    data-options="iconCls:'icon-save',closable:false,collapsible:false,minimizable:false,maximizable:false,border:false,fit:true">
    <form id="car-baseinfo-add-auto-complete">
        <table cellpadding="8" cellspacing="0">
            <tr>
                <td><div style="width:85px;text-align:right;">继承车辆车架号</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="vehicle_dentification_number"
                        required="true"
                        value="<?php echo $last_car_vin; ?>"
                    />
                </td>
                <td><a href="javascript:<?php echo $buttonAutoComplete['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?php echo $buttonAutoComplete['icon']; ?>'"><?php echo $buttonAutoComplete['text']; ?></a></td>
                <td><span style="color:red">*请选择具有相同车型参数的车辆作为模板</span></td>
            </tr>
        </table>
    </form>
</div>
</div>
<?php } ?>
<div style="border-top:1px solid #ccc"></div> 
<!-- 车辆基本信息 -->
<div style="width:100%;">
<div
    class="easyui-panel" title="车辆基本信息"    
    style="padding:10px;"
    data-options="iconCls:'icon-car',closable:false,collapsible:false,minimizable:false,maximizable:false,border:false,fit:true">
    <form id="easyui-form-car-baseinfo-add" method="post">
    	<table cellpadding="8" cellspacing="0">
            <tr>
                <td><div style="width:85px;text-align:right;">车牌号</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="plate_number"
                        validType="length[20]"
                    />
                </td>
                <!-- <td><div style="width:85px;text-align:right;">购买批次</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="buy_batch_number"
                        validType="length[50]"
                    />
                </td > -->
				<td></td>
				<td></td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">车架号</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="vehicle_dentification_number"
                        required="true"
                        validType="length[100]"
                    >
                </td>
                <td><div style="width:85px;text-align:right;">发动机号</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="engine_number"
                        required="true"
                        validType="length[100]"
                    />
                </td>
                <td><div style="width:85px;text-align:right;">登记编号</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="reg_number"
                        validType="length[50]"
                    >
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">备注</div></td>
                <td colspan="5">
                    <input
                        class="easyui-textbox"
                        name="note"
                        style="width:454px;height:40px;padding:0;" 
                        data-options="multiline:true"
                        validType="length[150]"
                    />
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">机动车所有人</div></td>
                <td colspan="5">
                    <input class="easyui-combotree" name="owner_id"
                           data-options="
                                width:454,
                                url: '<?php echo yii::$app->urlManager->createUrl(['owner/combotree/get-owners']); ?>',
                                editable: false,
                                panelHeight:'auto',
                                lines:false
                           "
                        />
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">车辆运营公司</div></td>
                <td colspan="5">
                    <input class="easyui-combotree" name="operating_company_id"
                           data-options="
                                width:454,
                                url: '<?php echo yii::$app->urlManager->createUrl(['operating/combotree/get-operating-company']); ?>',
                                editable: false,
                                panelHeight:'auto',
                                lines:false,
                                required:true,
                                missingMessage:'请选择运营公司'
                           "
                        />
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">身份证明名称</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="identity_name"
                        validType="length[100]"
                    />
                </td>
                <td><div style="width:85px;text-align:right;">身份证明号码</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="identity_number"
                        validType="length[100]"
                    />
                </td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">登记机关</div></td>
                <td colspan="5">
                    <input
                        class="easyui-textbox"
                        style="width:454px;"
                        name="reg_organ"
                        validType="length[100]"
                    />
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">登记日期</div></td>
                <td>
                    <input
                        class="easyui-datebox"
                        style="width:160px;"
                        name="reg_date"
                        validType="date"
                    />
                </td>
                <td><div style="width:85px;text-align:right;">车辆品牌</div></td>
                <td>
                    <input class="easyui-combotree" name="brand_id"
                           data-options="
                                width:160,
                                url: '<?php echo yii::$app->urlManager->createUrl(['car/combotree/get-car-brands']); ?>',
                                editable: false,
                                panelHeight:'auto',
                                lines:false
                           "
                        />
                </td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">车辆类型</div></td>
                <td>
                    <select
                        class="easyui-combobox"
                        style="width:160px;"
                        name="car_type"
						editable="false"
						data-options="panelHeight:'auto'"
						required="true"
                    >
                    	<option value="">请选择</option>
                        <?php foreach($config['car_type'] as $val){ ?>
                        <option value="<?php echo $val['value']; ?>"><?php echo $val['text']; ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td><div style="width:85px;text-align:right;">车辆型号</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="car_model"
                        validType="length[100]"
                        data-options="{onChange: function(){
                            CarBaseinfoAdd.getCarModelName($(this).val());
                        }}"
                    />
                </td>
                <td><div style="width:85px;text-align:right;">车型名称</div></td>
                <td>
                    <input
                        id="car_baseinfo_add_input_car_brand_name"
                        class="easyui-textbox"
                        style="width:160px;"
                        readonly="true"
                    />
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">车身颜色</div></td>
                <td>
                    <select
                        class="easyui-combobox"
                        style="width:160px;"
                        name="car_color"
						editable="false"
						data-options="panelHeight:'auto'"
						required="true"
                    >
                    	<option value="">请选择</option>
                        <?php foreach($config['car_color'] as $val){ ?>
                        <option value="<?php echo $val['value']; ?>"><?php echo $val['text']; ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td><div style="width:85px;text-align:right;">进口/国产</div></td>
                <td>
                    <select
                        class="easyui-combobox"
                        style="width:160px;"
                        name="import_domestic"
						editable="false"
						data-options="panelHeight:'auto'"
                    >
                        <?php foreach($config['import_domestic'] as $val){ ?>
                        <option value="<?php echo $val['value']; ?>"><?php echo $val['text']; ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td><div style="width:85px;text-align:right;">发动机型号</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="engine_model"
                        validType="length[50]"
                    />
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">燃料种类</div></td>
                <td>
                    <select
                        class="easyui-combobox"
                        style="width:160px;"
                        name="fuel_type"
						editable="false"
						data-options="panelHeight:'auto'"
						required="true"
                    >
                    	<option value="">请选择</option>
                        <?php foreach($config['fuel_type'] as $val){ ?>
                        <option value="<?php echo $val['value']; ?>"><?php echo $val['text']; ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td><div style="width:85px;text-align:right;">排量</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="displacement"
                        validType="int"
                    /> mm
                </td>
                <td><div style="width:85px;text-align:right;">功率</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="power"
                        validType="int"
                    /> kw
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">续航里程</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="endurance_mileage"
                        validateType="int"
                        data-options="panelHeight:'auto'"
                        required="true"
                    /> km
                </td>
                <td><div style="width:85px;text-align:right;">制造厂名称</div></td>
                <td colspan="3">
                    <input
                        class="easyui-textbox"
                        style="width:454px;"
                        name="manufacturer_name"
                        validType="length[100]"
                    />
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">转向形式</div></td>
                <td>
                    <select
                        class="easyui-combobox"
                        style="width:160px;"
                        name="turn_type"
						editable="false"
						data-options="panelHeight:'auto'"
						required="true"
                    >
                    	<option value="">请选择</option>
                        <?php foreach($config['turn_type'] as $val){ ?>
                        <option value="<?php echo $val['value']; ?>"><?php echo $val['text']; ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td><div style="width:85px;text-align:right;">轮距前</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="wheel_distance_f"
                        validType="int"
                        invalidMessage="请输入整型值！"
                    /> mm
                </td>
                <td><div style="width:85px;text-align:right;">轮距后</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="wheel_distance_b"
                        validType="int"
                        invalidMessage="请输入整型值！"
                    /> mm
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">轮胎数</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="wheel_amount"
                        validType="int"
                        invalidMessage="请输入整形值！"
                    />
                </td>
                <td><div style="width:85px;text-align:right;">轮胎规格</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="wheel_specifications"
                        validType="length[50]"
                    />
                </td>
                <td><div style="width:85px;text-align:right;">钢板弹簧片数</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="plate_amount"
                        validType="int"
                        invalidMessage="请输入整形值！"
                    />
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">轴距</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="shaft_distance"
                        validType="int"
                        invalidMessage="请输入整形值！"
                    /> mm
                </td>
                <td><div style="width:85px;text-align:right;">轴数</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="shaft_amount"
                        validType="int"
                        invalidMessage="请输入整形值！"
                    >
                </td>
                <td><div style="width:85px;text-align:right;">外廓尺寸长</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="outside_long"
                        validType="int"
                        invalidMessage="请输入整形值！"
                    /> mm
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">外廓尺寸宽</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="outside_width"
                        validType="int"
                        invalidMessage="请输入整形值！"
                    /> mm
                </td>
                <td><div style="width:85px;text-align:right;">外廓尺寸高</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="outside_height"
                        validType="int"
                        invalidMessage="请输入整形值！"
                    /> mm
                </td>
                <td><div style="width:85px;text-align:right;">货厢内部尺寸长</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="inside_long"
                        validType="int"
                        invalidMessage="请输入整形值！"
                    /> mm
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">货厢内部尺寸宽</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="inside_width"
                        validType="int"
                        invalidMessage="请输入整形值！"
                    /> mm
                </td>
                <td><div style="width:85px;text-align:right;">货厢内部尺寸高</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="inside_height"
                        validType="int"
                        invalidMessage="请输入整形值！"
                    /> mm
                </td>
                <td><div style="width:85px;text-align:right;">总质量</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="total_mass"
                        validType="int"
                        invalidMessage="请输入整形值！"
                    /> kg
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">核定载质量</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="check_mass"
                        validType="int"
                        invalidMessage="请输入整形值！"
                    /> kg
                </td>
                <td><div style="width:85px;text-align:right;">核定载客</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="check_passenger"
                        validType="int"
                        invalidMessage="请输入整形值！"
                    />
                </td>
                <td><div style="width:85px;text-align:right;">准牵引总质量</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="check_tow_mass"
                        validType="int"
                        invalidMessage="请输入整形值！"
                    /> kg
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">驾驶室载客</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="cab_passenger"
                        validType="int"
                        invalidMessage="请输入整形值！"
                    />
                </td>
                <td><div style="width:85px;text-align:right;">使用性质</div></td>
                <td>
                    <select
                        class="easyui-combobox"
                        style="width:160px;"
                        name="use_nature"
						editable="false"
						data-options="panelHeight:'auto'"
                    >
                        <?php foreach($config['use_nature'] as $val){ ?>
                        <option value="<?php echo $val['value']; ?>"><?php echo $val['text']; ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td><div style="width:85px;text-align:right;">车辆获得方式</div></td>
                <td>
                    <select
                        class="easyui-combobox"
                        style="width:160px;"
                        name="gain_way"
						editable="false"
						data-options="panelHeight:'auto'"
                    >
                        <?php foreach($config['gain_way'] as $val){ ?>
                        <option value="<?php echo $val['value']; ?>"><?php echo $val['text']; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
			<tr>
				<td><div style="width:85px;text-align:right;">购置年份</div></td>
                <td>
                    <select
                        class="easyui-combobox"
                        style="width:160px;"
                        name="gain_year"
						editable="false"
						data-options="panelHeight:'auto'"
                    >
                        <?php foreach($config['gain_year'] as $val){ ?>
						<option value=""></option>
                        <option value="<?php echo $val['value']; ?>"><?php echo $val['text']; ?></option>
                        <?php } ?>
                    </select>
                </td>
			</tr>
            <tr>
                <td><div style="width:85px;text-align:right;">车辆出厂日期</div></td>
                <td>
                    <input
                        class="easyui-datebox"
                        style="width:160px;"
                        name="leave_factory_date"
                        validType="date"
                    />
                </td>
                <td><div style="width:85px;text-align:right;">发证机关</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="issuing_organ"
                        validType="length[100]"
                    />
                </td>
                <td><div style="width:85px;text-align:right;">发证日期</div></td>
                <td>
                    <input
                        class="easyui-datebox"
                        style="width:160px;"
                        name="issuing_date"
                        validType="date"
                    />
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">电池型号</div></td>
                <td>
                    <input class="easyui-combobox" name="battery_model"
                           data-options="
                                width:160,
                                url: '<?php echo yii::$app->urlManager->createUrl(['car/combobox/get-battery-model']); ?>',
                                panelHeight: 'auto',
                                valueField: 'battery_model',
                                textField: 'battery_model',
                                mode: 'remote',
                                onHidePanel:function(){
                                       var text = $.trim( $(this).combobox('textbox').val() );
                                       if(text){
                                           var data = $(this).combobox('getData');
                                           var i;
                                           var isExist = false;
                                           for(i=0;i<data.length;i++){
                                                if(data[i].battery_model == text){
                                                    isExist = true;
                                                    break;
                                                }
                                           }
                                           if(!isExist){
                                                $.messager.show({
                                                    title: '无效值',
                                                    msg:'【' + text + '】不是有效值！请重新检索并选择一个电池型号！'
                                                });
                                                $(this).combobox('clear');
                                           }
                                       }
                                }
                           "
                        />
                </td>
                <td><div style="width:85px;text-align:right;">电机型号</div></td>
                <td>
                    <input class="easyui-combobox" name="motor_model"
                           data-options="
                                width:160,
                                url: '<?php echo yii::$app->urlManager->createUrl(['car/combobox/get-motor-model']); ?>',
                                panelHeight: 'auto',
                                valueField: 'motor_model',
                                textField: 'motor_model',
                                mode: 'remote',
                                onHidePanel:function(){
                                       var text = $.trim( $(this).combobox('textbox').val() );
                                       if(text){
                                           var data = $(this).combobox('getData');
                                           var i;
                                           var isExist = false;
                                           for(i=0;i<data.length;i++){
                                                if(data[i].motor_model == text){
                                                    isExist = true;
                                                    break;
                                                }
                                           }
                                           if(!isExist){
                                                $.messager.show({
                                                    title: '无效值',
                                                    msg:'【' + text + '】不是有效值！请重新检索并选择一个电机型号！'
                                                });
                                                $(this).combobox('clear');
                                           }
                                       }
                                }
                           "
                        />
                </td>
                <td><div style="width:85px;text-align:right;">电机控制器型号</div></td>
                <td>
                    <input class="easyui-combobox" name="motor_monitor_model"
                           data-options="
                                width:160,
                                url: '<?php echo yii::$app->urlManager->createUrl(['car/combobox/get-motor-monitor-model']); ?>',
                                panelHeight: 'auto',
                                valueField: 'motor_monitor_model',
                                textField: 'motor_monitor_model',
                                mode: 'remote',
                                onHidePanel:function(){
                                       var text = $.trim( $(this).combobox('textbox').val() );
                                       if(text){
                                           var data = $(this).combobox('getData');
                                           var i;
                                           var isExist = false;
                                           for(i=0;i<data.length;i++){
                                                if(data[i].motor_monitor_model == text){
                                                    isExist = true;
                                                    break;
                                                }
                                           }
                                           if(!isExist){
                                                $.messager.show({
                                                    title: '无效值',
                                                    msg:'【' + text + '】不是有效值！请重新检索并选择一个电机控制器型号！'
                                                });
                                                $(this).combobox('clear');
                                           }
                                       }
                                }
                           "
                        />
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">改装类型</div></td>
                <td colspan="5">
                <?php if(!empty($config['modified_car_type'])):?>
                <?php foreach ($config['modified_car_type'] as $v):?>
                	<span><input type="checkbox" name="modified_type[]" value="<?php echo $v['text']?>" /><?php echo $v['text']?></span>
                <?php endforeach;?>
                <?php endif;?>
                </td>
            </tr>
        </table>
    </form>
</div>
</div>
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
</script>