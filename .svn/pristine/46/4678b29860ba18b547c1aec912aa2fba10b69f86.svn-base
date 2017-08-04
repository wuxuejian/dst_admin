<div style="border-bottom:1px solid #95B8E7"></div>
<div class="easyui-panel" title="车辆基本信息" border="false" collapsible="true">  
    <div style="overflow:hidden;">
        <?php foreach($car as $key=>$val){ ?>
        <ul style="list-style:none;float:left;margin:0;padding:4px 10px;overflow:hidden;width: 240px;height:40px;">
            <li style="font-weight:bold;color:#555;line-height:18px;"><?= $carAttributeLabels[$key]; ?></li>
            <li style="color:#555;line-height:18px;"><?= $val; ?></li>
        </ul>
        <?php } ?>
    </div>
</div>
<div style="border-bottom:1px solid #95B8E7"></div>
<div class="easyui-panel" title="车辆行驶证信息" border="false" collapsible="true">  
    <div style="overflow:hidden;">
        <ul style="list-style:none;float:left;margin:0;padding:4px 10px;overflow:hidden;width: 240px;height:50px;">
            <li style="font-weight:bold;color:#555;line-height:18px;">号牌号码</li>
            <li style="color:#555;line-height:18px;"><?= $car['plate_number']; ?></li>
            
        </ul>
        <ul style="list-style:none;float:left;margin:0;padding:4px 10px;overflow:hidden;width: 240px;height:50px;">
            <li style="font-weight:bold;color:#555;line-height:18px;">车辆类型</li>
            <li style="color:#555;line-height:18px;"><?= $car['car_type']; ?></li>
        </ul>
        <ul style="list-style:none;float:left;margin:0;padding:4px 10px;overflow:hidden;width: 240px;height:50px;">
            <li style="font-weight:bold;color:#555;line-height:18px;">所有人</li>
            <li style="color:#555;line-height:18px;"><?= $car['owner_id']; ?></li>
        </ul>
        <ul style="list-style:none;float:left;margin:0;padding:4px 10px;overflow:hidden;width: 240px;height:50px;">
            <li style="font-weight:bold;color:#555;line-height:18px;">运营公司</li>
            <li style="color:#555;line-height:18px;"><?= $car['operating_company_id']; ?></li>
        </ul>
        <ul style="list-style:none;float:left;margin:0;padding:4px 10px;overflow:hidden;width: 240px;height:50px;">
            <li style="font-weight:bold;color:#555;line-height:18px;">地址</li>
            <li style="color:#555;line-height:18px;"><?= $drivingLicense['addr']; ?></li>
        </ul>
        <ul style="list-style:none;float:left;margin:0;padding:4px 10px;overflow:hidden;width: 240px;height:50px;">
            <li style="font-weight:bold;color:#555;line-height:18px;">使用性质<li>
            <li style="color:#555;line-height:18px;"><?= $car['use_nature']; ?></li>
        </ul>
        <ul style="list-style:none;float:left;margin:0;padding:4px 10px;overflow:hidden;width: 240px;height:50px;">
            <li style="font-weight:bold;color:#555;line-height:18px;">品牌型号</li>
            <li style="color:#555;line-height:18px;"><?php echo $car['car_brand_name']; ?></li>
        </ul>
        <ul style="list-style:none;float:left;margin:0;padding:4px 10px;overflow:hidden;width: 240px;height:50px;">
            <li style="font-weight:bold;color:#555;line-height:18px;">车辆识别代码</li>
            <li style="color:#555;line-height:18px;"><?= $car['vehicle_dentification_number']; ?></li>
        </ul>
        <ul style="list-style:none;float:left;margin:0;padding:4px 10px;overflow:hidden;width: 240px;height:50px;">
            <li style="font-weight:bold;color:#555;line-height:18px;">发动机号码</li>
            <li style="color:#555;line-height:18px;"><?= $car['engine_number']; ?></li>
        </ul>
        <ul style="list-style:none;float:left;margin:0;padding:4px 10px;overflow:hidden;width: 240px;height:50px;">
            <li style="font-weight:bold;color:#555;line-height:18px;">注册日期</li>
            <li style="color:#555;line-height:18px;"><?= $drivingLicense['register_date']; ?></li>
        </ul>
        <ul style="list-style:none;float:left;margin:0;padding:4px 10px;overflow:hidden;width: 240px;height:50px;">
            <li style="font-weight:bold;color:#555;line-height:18px;">发证日期</li>
            <li style="color:#555;line-height:18px;"><?= $drivingLicense['issue_date']; ?></li>
        </ul>
        <ul style="list-style:none;float:left;margin:0;padding:4px 10px;overflow:hidden;width: 240px;height:50px;">
            <li style="font-weight:bold;color:#555;line-height:18px;">电池型号</li>
            <li style="color:#555;line-height:18px;"><?= $car['battery_model']; ?></li>
        </ul>
    </div>
</div>
<div style="border-bottom:1px solid #95B8E7"></div>
<div class="easyui-panel" title="车辆道路运输证信息" border="false" collapsible="true">  
    <div style="overflow:hidden;">
        <ul style="list-style:none;float:left;margin:0;padding:4px 10px;overflow:hidden;width: 240px;height:40px;">
            <li style="font-weight:bold;color:#555;line-height:18px;">业户名称</li>
            <li style="color:#555;line-height:18px;"><?php echo $car['owner_id']; ?></li>
        </ul>
        <ul style="list-style:none;float:left;margin:0;padding:4px 10px;overflow:hidden;width: 240px;height:40px;">
            <li style="font-weight:bold;color:#555;line-height:18px;">车辆号牌</li>
            <li style="color:#555;line-height:18px;"><?php echo $car['plate_number']; ?></li>
        </ul>
        <ul style="list-style:none;float:left;margin:0;padding:4px 10px;overflow:hidden;width: 240px;height:40px;">
            <li style="font-weight:bold;color:#555;line-height:18px;">车辆类型</li>
            <li style="color:#555;line-height:18px;">
                <?= $car['car_type']; ?>
                <?php echo $car['car_brand_name']; ?>
                <?= $car['car_model']; ?>
            </li>
        </ul>
        <ul style="list-style:none;float:left;margin:0;padding:4px 10px;overflow:hidden;width: 240px;height:40px;">
            <li style="font-weight:bold;color:#555;line-height:18px;">吨（座）位</li>
            <li style="color:#555;line-height:18px;"><?php echo $roadTransportCertificate['ton_or_seat']; ?></li>
        </ul>
        <ul style="list-style:none;float:left;margin:0;padding:4px 10px;overflow:hidden;width: 240px;height:40px;">
            <li style="font-weight:bold;color:#555;line-height:18px;">车辆尺寸</li>
            <li style="color:#555;line-height:18px;">
                <?php 
                    echo $car['outside_long'].' X '.$car['outside_width'].' X '.$car['outside_height'];
                ?>
                毫米
            </li>
        </ul>
        <ul style="list-style:none;float:left;margin:0;padding:4px 10px;overflow:hidden;width: 240px;height:40px;">
            <li style="font-weight:bold;color:#555;line-height:18px;">道路运输证号</li>
            <li style="color:#555;line-height:18px;">
                <?php echo $roadTransportCertificate['rtc_province']; ?>
                交运管
                <?php echo $roadTransportCertificate['rtc_city']; ?>
                字
                <?php echo $roadTransportCertificate['rtc_number']; ?>
                号
            </li>
        </ul>
        <ul style="list-style:none;float:left;margin:0;padding:4px 10px;overflow:hidden;width: 240px;height:40px;">
            <li style="font-weight:bold;color:#555;line-height:18px;">核发机关</li>
            <li style="color:#555;line-height:18px;"><?= $roadTransportCertificate['issuing_organ']; ?></li>
        </ul>
        <ul style="list-style:none;float:left;margin:0;padding:4px 10px;overflow:hidden;width: 240px;height:40px;">
            <li style="font-weight:bold;color:#555;line-height:18px;">发证日期</li>
            <li style="color:#555;line-height:18px;"><?= $roadTransportCertificate['issuing_date']; ?></li>
        </ul>
        <ul style="list-style:none;float:left;margin:0;padding:4px 10px;overflow:hidden;width: 240px;height:40px;">
            <li style="font-weight:bold;color:#555;line-height:18px;">上次年审时间</li>
            <li style="color:#555;line-height:18px;"><?= $roadTransportCertificate['last_annual_verification_date']; ?></li>
        </ul>
    </div>
</div>