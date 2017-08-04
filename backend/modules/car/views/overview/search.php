<div style="overflow-x:hidden;">
    <form id="easyui-from-car-overview-index" class="easyui-from" style="padding:10px;text-align:center;">
        <input
            class="easyui-textbox"
            name="vin_or_platenumber"
            required="true"
            style="width:600px;height:30px;"
            prompt="请输入车牌号或车架号..."
            value="<?= yii::$app->request->get('vin_or_platenumber'); ?>"
        />
        <a href="javascript:void(0)" onclick="CarOverviewIndex.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">搜&nbsp;索</a>
    </form>
    <?php
        if($carInfo){
    ?>
    <div style="border-bottom:1px solid #95B8E7;"></div>
    <div class="easyui-panel" title="车辆基本信息" border="false" style="width:100%;">
        <div style="padding: 10px;">
            <ul class="ulforform-resizeable" style="line-height:24px;">
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">车牌号:</div>
                    <div class="ulforform-resizeable-input">
                        <?= $carInfo['plate_number']; ?>
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">车架号:</div>
                    <div class="ulforform-resizeable-input">
                        <?= $carInfo['vehicle_dentification_number']; ?>
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">车辆品牌:</div>
                    <div class="ulforform-resizeable-input">
                        
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">车辆类型:</div>
                    <div class="ulforform-resizeable-input">
                        <?= $carInfo['car_type']; ?>
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">车身颜色:</div>
                    <div class="ulforform-resizeable-input">
                        <?= $carInfo['car_color']; ?>
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">入库时间:</div>
                    <div class="ulforform-resizeable-input">
                        <?= $carInfo['add_time']; ?>
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">行驶证:</div>
                    <div class="ulforform-resizeable-input">
                        <?= $carInfo['drivingLicense']; ?>
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">道路运输证:</div>
                    <div class="ulforform-resizeable-input">
                        <?= $carInfo['roadTransportCertificate']; ?>
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">二级维护卡:</div>
                    <div class="ulforform-resizeable-input">
                        <?= $carInfo['secondMaintenance']; ?>
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">交强险:</div>
                    <div class="ulforform-resizeable-input">
                        <?= $carInfo['insuranceCompulsory']; ?>
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">商业险:</div>
                    <div class="ulforform-resizeable-input">
                        <?= $carInfo['insuranceBusiness']; ?>
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">车辆状态:</div>
                    <div class="ulforform-resizeable-input">
                        <?= $carInfo['car_status']; ?>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div style="border-bottom:1px solid #95B8E7;"></div>
    <div class="easyui-tabs" border="false">
        <?php
        foreach($buttons as $val){
        ?>
        <div title="<?= $val['text']; ?>" iconCls="<?= $val['icon']; ?>" style="padding:20px;" href="<?= $val['href']; ?>"></div>
        <?php } ?>
    </div>
    <?php
        }else{
    ?>
    <div style="padding:10px;color:red;">未检索到"<?= yii::$app->request->get('vin_or_platenumber'); ?>"的相关信息</div>
    <?php
        }
    ?>
</div>