<!-- <form
    id="easyui-form-car-office-car-register-scan"
    class="easyui-form"
    style="padding:10px;" method="post"
> -->
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
        <table cellpadding="8" cellspacing="0">
        
            <tr>
                <td><div style="width:100px;text-align:right;">车辆品牌：</div></td>
                <td ><?php echo $data['brand_name']; ?></td>
                <td><div style="width:100px;text-align:right;">车辆类型：</div></td>
                <td ><?php echo $config['car_type'][$data['car_type']]['text']; ?></td>
            </tr>
                <td><div style="width:100px;text-align:right;">车辆型号：</div></td>
                <td><?php echo $data['car_model']; ?></td>
                <td><div style="width:100px;text-align:right;">车型名称：</div></td>
                <td><?php echo $data['car_model_name']; ?></td>
            </tr>
            <tr>
                <td><div style="width:100px;text-align:right;">车辆制造厂：</div></td>
                <td><?php echo $data['manufacturer_name']; ?></td>
                
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
        <table cellpadding="8" cellspacing="0">
            <tr>
                <td><div style="width:100px;text-align:right;">外廓长度(mm):</div></td>
                <td ><?php echo $data['outside_long']; ?></td>
                <td><div style="width:100px;text-align:right;">外廓宽度(mm)：</div></td>
                <td ><?php echo $data['outside_width']; ?></td>
                <td><div style="width:100px;text-align:right;">外廓高度(mm)：</div></td>
                <td ><?php echo $data['outside_height']; ?></td>
            </tr>
             <tr>
                <td><div style="width:100px;text-align:right;">车厢长度(mm):</div></td>
                <td ><?php echo $data['inside_long']; ?></td>
                <td><div style="width:100px;text-align:right;">车厢宽度(mm)：</div></td>
                <td ><?php echo $data['inside_width']; ?></td>
                <td><div style="width:100px;text-align:right;">车厢高度(mm)：</div></td>
                <td ><?php echo $data['inside_height']; ?></td>
            </tr>
            <tr>
                <td><div style="width:100px;text-align:right;">轴距(mm)：</div></td>
                <td ><?php echo $data['shaft_distance']; ?></td>
                <td><div style="width:100px;text-align:right;">前轮距(mm)：</div></td>
                <td ><?php echo $data['wheel_distance_f']; ?></td>
                <td><div style="width:100px;text-align:right;">后轮距(mm)：</div></td>
                <td ><?php echo $data['wheel_distance_b']; ?></td>
            </tr>
            <tr>
                <td><div style="width:100px;text-align:right;">容积(L)：</div></td>
                <td ><?php echo $data['cubage']; ?></td>
                <td><div style="width:100px;text-align:right;">接近角(°)：</div></td>
                <td ><?php echo $data['approach_angle']; ?></td>
                <td><div style="width:100px;text-align:right;">离去角(°)：</div></td>
                <td ><?php echo $data['departure_angle']; ?></td>
            </tr>
            <tr>
                <td><div style="width:100px;text-align:right;">总质量(kg)：</div></td>
                <td ><?php echo $data['total_mass']; ?></td>
                <td><div style="width:100px;text-align:right;">整备质量(kg)：</div></td>
                <td ><?php echo $data['empty_mass']; ?></td>
                <td><div style="width:100px;text-align:right;">额定载重质量(kg)：</div></td>
                <td ><?php echo $data['check_mass']; ?></td>
            </tr>
            <tr>
                <td><div style="width:100px;text-align:right;">驾驶室乘客数量：</div></td>
                <td ><?php echo $data['cab_passenger']; ?></td>
                <td><div style="width:100px;text-align:right;">轮胎型号：</div></td>
                <td ><?php echo $data['wheel_specifications']; ?></td>
               <!--  <td><div style="width:100px;text-align:right;">轮胎数量：</div></td> -->
                <!-- <td ><?php echo $data['wheel_amount']; ?></td> -->
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
        <table cellpadding="8" cellspacing="0">
        <tr>
                <td><div style="width:100px;text-align:right;">发动机型号：</div></td>
                <td ><?php echo $data['engine_model']; ?></td>
                <td><div style="width:100px;text-align:right;">燃料形式：</div></td>
                <td ><?php echo $config['fuel_type'][$data['fuel_type']]['text']; ?></td>
                <td><div style="width:100px;text-align:right;">排量(mL)：</div></td>
                <td ><?php echo $data['displacement']; ?></td>
            </tr>   
        <tr>
                <td><div style="width:100px;text-align:right;">工部续航里程(km)：</div></td>
                <td ><?php echo $data['endurance_mileage']; ?></td>
                <td><div style="width:100px;text-align:right;">驱动电机额定功率(kW)：</div></td>
                <td ><?php echo $data['rated_power']; ?></td>
                <td><div style="width:100px;text-align:right;">驱动电机峰值功率(kW)：</div></td>
                <td ><?php echo $data['peak_power']; ?></td>
            </tr>   
        <tr>
                <td><div style="width:100px;text-align:right;">动力电池容量(kW·h)：</div></td>
                <td ><?php echo $data['power_battery_capacity']; ?></td>
                <td><div style="width:100px;text-align:right;">动力电池生产厂家：</div></td>
                <td ><?php echo $data['power_battery_manufacturer']; ?></td>
                <td><div style="width:100px;text-align:right;">驱动电机生产厂家：</div></td>
                <td ><?php echo $data['drive_motor_manufacturer']; ?></td>
        </tr>  
        <tr>
                <td><div style="width:100px;text-align:right;">最高车速(km/h)：</div></td>
                <td ><?php echo $data['max_speed']; ?></td>
                <td><div style="width:100px;text-align:right;">充电时间(h)：</div></td>
                <td ><span>快充 </span><?php echo $data['fast_charging_time']; ?>&nbsp;&nbsp;<span>慢充 </span><?php echo $data['slow_charging_time']; ?></td>
                <td><div style="width:100px;text-align:right;">充电方式：</div></td>
                <td ><?php echo $data['charging_type']; ?></td>
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
        <tr>
            
            <td colspan="6">
            <table border="0"><tr><td>
            <a href="<?php echo !empty($data['car_front_img']) ? $data['car_front_img'] : 'javascript:void(0)';?>" target="_blank"><img  src="<?php echo $data['car_front_img']?>" width="100" height="100" title="进维修厂收车单"  alt="没有上传" /></a>
            <span>车头</span>
            &nbsp;<a href="<?php echo !empty($data['car_left_img']) ? $data['car_left_img']: 'javascript:void(0)';?>" target="_blank"><img  src="<?php echo $data['car_left_img']?>" width="100" height="100" title="出场保养结果单据"  alt="没有上传" /></a>
            <span>车辆全身</span>
            &nbsp;<a href="<?php echo !empty($data['car_right_img']) ? $data['car_right_img']: 'javascript:void(0)';?>" target="_blank"><img src="<?php echo $data['car_right_img']?>" width="100" height="100" title="保修手册凭证" alt="没有上传"   /></a>
            <span>充电口</span>
            <a href="<?php echo !empty($data['car_tail_img']) ? $data['car_tail_img'] : 'javascript:void(0)';?>" target="_blank"><img  src="<?php echo $data['car_tail_img']?>" width="100" height="100" title="进维修厂收车单"  alt="没有上传" /></a>
            <span>车厢</span>
            &nbsp;<a href="<?php echo !empty($data['car_control_img']) ? $data['car_control_img'] : 'javascript:void(0)';?>" target="_blank"><img  src="<?php echo $data['car_control_img']?>" width="100" height="100" title="进维修厂收车单"  alt="没有上传" /></a>
            <span>中控</span>
            &nbsp;<a href="<?php echo !empty($data['car_full_img']) ? $data['car_full_img'] : 'javascript:void(0)';?>" target="_blank"><img  src="<?php echo $data['car_full_img']?>" width="100" height="100" title="进维修厂收车单"  alt="没有上传" /></a>
            <span>全车</span>
            
            </td></tr></table></td>
               
        </tr>
       
</div>     

<!-- </form> -->


<!-- *************************************************************************************************** -->
