<form id="carmonitorDetectionIndex_detectWin_form" style="padding:5px;">
    <ul class="ulforform-resizeable">
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">车辆品牌</div>
            <div class="ulforform-resizeable-input">
                <select class="easyui-combobox" name="car_brand" style="width:100%;" data-options="panelHeight:'auto',required:true,editable:false">
                    <option value="" selected="selected">--不限--</option>
                    <?php foreach($config['car_brand'] as $val){ ?>
                        <option value="<?php echo $val['value'] ?>"><?php echo $val['text']; ?></option>
                    <?php } ?>
                </select>
            </div>
        </li>
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">车辆类型</div>
            <div class="ulforform-resizeable-input">
                <select class="easyui-combobox" name="car_type" style="width:100%;" data-options="panelHeight:'auto',required:true,editable:false">
                    <option value="" selected="selected">--不限--</option>
                    <?php foreach($config['car_type'] as $val){ ?>
                        <option value="<?php echo $val['value'] ?>"><?php echo $val['text']; ?></option>
                    <?php } ?>
                </select>
            </div>
        </li>
        <li class="ulforform-resizeable-group-single">
            <div class="ulforform-resizeable-title">车架号</div>
            <div class="ulforform-resizeable-input" style="width:80%;">
                <input class="easyui-textbox" name="car_vin" style="width:100%;height:280px;"
                       value="<?php echo $car_vin; ?>"
                       data-options="
                            multiline:true,
                            prompt:'请输入需要检测的车辆车架号，每个车架号占一行或者以空格分隔'
                      "
                    />
            </div>
        </li>
        <li class="ulforform-resizeable-group-single">
            <div class="ulforform-resizeable-title"></div>
            <div class="ulforform-resizeable-input">
                <div id="detectFailTip" style="color:red;"></div>
            </div>
        </li>
    </ul>
</form>
