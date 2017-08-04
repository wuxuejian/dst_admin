<div class="easyui-tabs" data-options="fit:true,border:false"> 
    <!-- 车辆基本信息 -->
    <div title="车辆基本信息" style="padding:15px">
        <table cellpadding="6" cellspacing="0" align="center"  width="100%" border="0">
            <tr>
                <th align="right" width="13%">车牌号：</th>
                <td width="20%"><?php echo $car['plate_number']; ?></td>
                <th align="right" width="13%">购买批次：</th>
                <td><?php echo $car['buy_batch_number']; ?></td>
                <th align="right" width="13%">一级状态：</th>
                <td width="20%"><?php echo isset($config['car_status'][$car['car_status']]) ? $config['car_status'][$car['car_status']]['text'] : '' ;?></td>
            </tr>
            <tr>
                <th align="right">机动车所有人：</th>
                <td ><?php echo $car['owner_id']; ?></td>
                <th align="right" width="13%">二级状态：</th>
                <td width="20%" colspan="3"><?php echo isset($config['car_status2'][$car['car_status2']]) ? $config['car_status2'][$car['car_status2']]['text'] : '' ;?></td>
            </tr>
            <tr>
                <th align="right">车辆运营公司：</th>
                <td colspan="5"><?php echo $car['operating_company_id']; ?></td>
            </tr>
            <tr>
                <th align="right">身份证明名称：</th>
                <td><?php echo $car['identity_name']; ?></td>
                <th align="right">身份证明号码：</th>
                <td><?php echo $car['identity_number']; ?></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <th align="right">登记机关：</th>
                <td colspan="5"><?php echo $car['reg_organ']; ?></td>
            </tr>
            <tr>
                <th align="right">登记日期：</th>
                <td><?php echo date('Y-m-d',$car['reg_date']); ?></td>
                <th align="right">登记编号：</th>
                <td><?php echo $car['reg_number']; ?></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <th align="right">车辆类型：</th>
                <td><?php echo $config['car_type'][$car['car_type']]['text']; ?></td>
                <th align="right">车辆品牌：</th>
                <td><?php echo $car['brand_name']; ?></td>
                <th align="right">车辆型号：</th>
                <td><?php echo $car['car_model']; ?></td>
            </tr>
            <tr>
                <th align="right">车型名称：</th>
                <td colspan="5"><?php echo $car['car_model_name']; ?></td>
            </tr>
            <tr>
                <th align="right">车身颜色：</th>
                <td><?php echo @$config['car_color'][$car['car_color']]['text']; ?></td>
                <th align="right">车架号：</th>
                <td><?php echo $car['vehicle_dentification_number']; ?></td>
                <th align="right">进口/国产：</th>
                <td><?php echo $config['import_domestic'][$car['import_domestic']]['text']; ?></td>
            </tr>
            <tr>
                <th align="right">发动机号：</th>
                <td><?php echo $car['engine_number']; ?></td>
                <th align="right">发动机型号：</th>
                <td><?php echo $car['engine_model']; ?></td>
                <th align="right">燃料种类：</th>
                <td><?php echo $config['fuel_type'][$car['fuel_type']]['text']; ?></td>
            </tr>
            <tr>
                <th align="right">排量：</th>
                <td><?php echo $car['displacement']; ?> ml</td>
                <th align="right">功率：</th>
                <td><?php echo $car['power']; ?> kw</td>
                <th align="right">续航里程：</th>
                <td><?php  echo $car['endurance_mileage']; ?> km</td>
            </tr>
            <tr>
                <th align="right">制造厂名称：</th>
                <td colspan="5"><?php echo $car['manufacturer_name']; ?></td>
            </tr>
            <tr>
                <th align="right">转向形式：</th>
                <td><?php if($car['turn_type']){ echo $config['turn_type'][$car['turn_type']]['text']; } ?></td>
                <th align="right">轮距前：</th>
                <td><?php echo $car['wheel_distance_f']; ?> mm</td>
                <th align="right">轮距后：</th>
                <td><?php echo $car['wheel_distance_b']; ?> mm</td>
            </tr>
            <tr>
                <th align="right">轮胎数：</th>
                <td><?php echo $car['wheel_amount']; ?></td>
                <th align="right">轮胎规格：</th>
                <td><?php echo $car['wheel_specifications']; ?></td>
                <th align="right">钢板弹簧片数：</th>
                <td><?php echo $car['plate_amount']; ?></td>
            </tr>
            <tr>
                <th align="right">轴距：</th>
                <td><?php echo $car['shaft_distance']; ?> mm</td>
                <th align="right">轴数：</th>
                <td><?php echo $car['shaft_amount']; ?></td>
                <th align="right">外廓尺寸长：</th>
                <td><?php echo $car['outside_long']; ?> mm</td>
            </tr>
            <tr>
                <th align="right">外廓尺寸宽：</th>
                <td><?php echo $car['outside_width']; ?> mm</td>
                <th align="right">外廓尺寸高：</th>
                <td><?php echo $car['outside_height']; ?> mm</td>
                <th align="right">货厢内部尺寸长：</th>
                <td><?php echo $car['inside_long']; ?> mm</td>
            </tr>
            <tr>
                <th align="right">货厢内部尺寸宽：</th>
                <td><?php echo $car['inside_width']; ?> mm</td>
                <th align="right">货厢内部尺寸高：</th>
                <td><?php echo $car['inside_height']; ?> mm</td>
                <th align="right">总质量：</th>
                <td><?php echo $car['total_mass']; ?> kg</td>
            </tr>
            <tr>
                <th align="right">核定载质量：</th>
                <td><?php echo $car['check_mass']; ?> kg</td>
                <th align="right">核定载客：</th>
                <td><?php echo $car['check_passenger']; ?> kg</td>
                <th align="right">准牵引总质量：</th>
                <td><?php echo $car['check_tow_mass']; ?> kg</td>
            </tr>
            <tr>
                <th align="right">驾驶室载客：</th>
                <td><?php echo $car['cab_passenger']; ?></td>
                <th align="right">使用性质：</th>
                <td><?php echo $config['use_nature'][$car['use_nature']]['text']; ?></td>
                <th align="right">车辆获得方式：</th>
                <td><?php echo $config['gain_way'][$car['gain_way']]['text']; ?></td>
            </tr>
            <tr>
                <th align="right">车辆出厂日期：</th>
                <td><?php echo date('Y-m-d',$car['leave_factory_date']); ?></td>
                <th align="right">发证机关：</th>
                <td><?php echo $car['issuing_organ']; ?></td>
                <th align="right">发证日期：</th>
                <td><?php echo date('Y-m-d',$car['issuing_date']); ?></td>
            </tr>
            <tr>
                <th align="right">电池型号：</th>
                <td><?php echo $car['battery_model']; ?></td>
                <th align="right">电机型号：</th>
                <td><?php echo $car['motor_model']; ?></td>
                <th align="right">电机控制器型号：</th>
                <td><?php echo $car['motor_monitor_model']; ?></td>
            </tr>
        </table>
    </div>

    <!-- 车辆行驶证信息 -->
    <div title="车辆行驶证信息" style="padding:15px">
        <table cellpadding="6" cellspacing="0" align="center"  width="100%" border="0">
            <tr>
                <th align="right" width="15%">号牌号码：</th>
                <td width="35%"><?php echo $car['plate_number']; ?></td>
                <th align="right" width="15%">车辆类型：</th>
                <td><?php echo $config['car_type'][$car['car_type']]['text']; ?></td>
            </tr>
            <tr>
                <th align="right">所有人：</th>
                <td colspan="3"><?php echo $car['owner_id']; ?></td>
            </tr>
            <tr>
                <th align="right">地址：</th>
                <td colspan="3"><?php echo isset($config['DL_REG_ADDR'][$drivingLicense['addr']]) ? $config['DL_REG_ADDR'][$drivingLicense['addr']]['text'] : '' ; ?></td>
            </tr>
            <tr>
                <th align="right">使用性质：</th>
                <td><?php echo $config['use_nature'][$car['use_nature']]['text']; ?></td>
                <th align="right">品牌型号：</th>
                <td><?php echo $car['brand_name'],' ',$car['car_model']; ?></td>
            </tr>
            <tr>
                <th align="right">车辆识别代码：</th>
                <td colspan="3"><?php echo $car['vehicle_dentification_number']; ?></td>
            </tr>
            <tr>
                <th align="right">发动机号码：</th>
                <td colspan="3"><?php echo $car['engine_number']; ?></td>
            </tr>
            <tr>
                <th align="right">注册日期：</th>
                <td><?php echo $drivingLicense['register_date'] ? date('Y-m-d',$drivingLicense['register_date']) : ''; ?></td>
                <th align="right">发证日期：</th>
                <td><?php echo $drivingLicense['issue_date'] ? date('Y-m-d',$drivingLicense['issue_date']) : ''; ?></td>
            </tr>
        </table>
        <br /><br /><br />
        <table cellpadding="6" cellspacing="0" align="center"  width="100%" border="0">
            <tr>
                <th align="right" width="15%">号牌号码：</th>
                <td width="35%"><?php echo $car['plate_number']; ?></td>
                <th align="right" width="15%">档案编号：</th>
                <td><?php echo $drivingLicense['archives_number']; ?></td>
            </tr>
            <tr>
                <th align="right">核定载人数：</th>
                <td><?php echo $car['check_passenger']; ?></td>
                <th align="right">总质量：</th>
                <td><?php echo $car['total_mass']; ?> kg</td>
            </tr>
            <tr>
                <th align="right">整备质量：</th>
                <td><?php echo $drivingLicense['total_mass']; ?> kg</td>
                <th align="right">核定载质量：</th>
                <td><?php echo $car['check_mass']; ?> kg</td>
            </tr>
            <tr>
                <th align="right">外廓尺寸：</th>
                <td>
                    <?php 
                        echo $car['outside_long'].' X '.$car['outside_width'].' X '.$car['outside_height'];
                    ?>
                    mm
                </td>
                <th align="right">牵引力总质量：</th>
                <td><?php echo $car['check_tow_mass']; ?> kg</td>
            </tr>
            <tr>
                <th align="right">强制报废日期：</th>
                <td><?php echo $drivingLicense['force_scrap_date'] ? date('Y-m-d',$drivingLicense['force_scrap_date']) : ''; ?></td>
                <th align="right">检验有效期：</th>
                <td><?php echo $drivingLicense['valid_to_date'] ? date('Y-m-d',$drivingLicense['valid_to_date']) : ''; ?></td>
            </tr>
        </table>
    </div>

    <!-- 运营证信息 -->
    <div title="运营证信息" style="padding:15px">
        <table cellpadding="6" cellspacing="0" align="center"  width="100%" border="0">
            <tr>
                <th align="right" width="15%">业户名称：</th>
                <td><?php echo $car['owner_id']; ?></td>
            </tr>
            <tr>
                <th align="right">车辆号牌：</th>
                <td><?php echo $car['plate_number']; ?></td>
            </tr>
            <tr>
                <th align="right">车辆类型：</th>
                <td>
                    <?php echo $config['car_type'][$car['car_type']]['text']; ?>
                    <?php echo $car['brand_name']; ?>
                    <?php echo $car['car_model']; ?>
                </td>
            </tr>
            <tr>
                <th align="right">吨（座）位：</th>
                <td><?php echo $roadTransportCertificate['ton_or_seat']; ?></td>
            </tr>
            <tr>
                <th align="right">车辆尺寸：</th>
                <td>
                    <?php 
                        echo $car['outside_long'].' X '.$car['outside_width'].' X '.$car['outside_height'];
                    ?>
                    毫米
                </td>
            </tr>
            <tr>
                <th align="right">道路运输证号：</th>
                <td>
                    <?php echo $roadTransportCertificate['rtc_province']; ?>
                    交运管
                    <?php echo $roadTransportCertificate['rtc_city']; ?>
                    字
                    <?php echo $roadTransportCertificate['rtc_number']; ?>
                    号
                </td>
            </tr>
            <tr>
                <th align="right">核发机关：</th>
                <td><?php echo isset($config['TC_ISSUED_BY'][$roadTransportCertificate['issuing_organ']]) ? $config['TC_ISSUED_BY'][$roadTransportCertificate['issuing_organ']]['text'] : ''; ?></td>
            </tr>
            <tr>
                <th align="right">发证日期：</th>
                <td><?php echo date('Y-m-d',$roadTransportCertificate['issuing_date']); ?></td>
            </tr>
            <tr>
                <th align="right">上次年审时间：</th>
                <td><?php echo date('Y-m-d',$roadTransportCertificate['last_annual_verification_date']); ?></td>
            </tr>
        </table>
    </div>

    <!-- 三电系统管理信息 -->
    <div title="三电系统管理信息" style="padding:15px">
        <table cellpadding="6" cellspacing="0" align="center"  width="100%" border="0">
            <tr>
                <th align="right" width="13%">电池型号：</th>
                <td width="20%"><?php echo isset($threeElectricSystem['battery']['battery_model']) ? $threeElectricSystem['battery']['battery_model'] : ''; ?></td>
                <th align="right" width="13%">电池类型：</th>
                <td width="20%">
                    <?php
                        if(isset($threeElectricSystem['battery']['battery_type'])){
                            $op = $threeElectricSystem['battery']['battery_type'];
                            echo isset($config['battery_type'][$op]) ? $config['battery_type'][$op]['text'] : '' ;
                        }else{
                            echo '';
                        }
                    ?>
                </td>
                <th align="right" width="20%">电池系统额定电压：</th>
                <td><?php echo isset($threeElectricSystem['battery']['system_voltage']) ? $threeElectricSystem['battery']['system_voltage'] : ''; ?> V</td>
            </tr>
            <tr>
                <th align="right">电池系统额定容量：</th>
                <td><?php echo isset($threeElectricSystem['battery']['system_capacity']) ? $threeElectricSystem['battery']['system_capacity'] : ''; ?> Ah</td>
                <th align="right">电池系统额定电能：</th>
                <td><?php echo isset($threeElectricSystem['battery']['system_power']) ? $threeElectricSystem['battery']['system_power'] : ''; ?> kWh</td>
                <th align="right">电池系统电池串联数量：</th>
                <td><?php echo isset($threeElectricSystem['battery']['system_nums']) ? $threeElectricSystem['battery']['system_nums'] : ''; ?></td>
            </tr>
            <tr>
                <th align="right">充电接口类型：</th>
                <td>
                    <?php
                        if(isset($threeElectricSystem['battery']['connection_type'])){
                            $op = $threeElectricSystem['battery']['connection_type'];
                            echo isset($config['connection_type'][$op]) ? $config['connection_type'][$op]['text'] : '' ;
                        }else{
                            echo '';
                        }
                    ?>
                </td>
                <th align="right">电池规格：</th>
                <td>
                    <?php
                        if(isset($threeElectricSystem['battery']['battery_spec'])){
                            $op = $threeElectricSystem['battery']['battery_spec'];
                            echo isset($config['battery_spec'][$op]) ? $config['battery_spec'][$op]['text'] : '' ;
                        }else{
                            echo '';
                        }
                    ?>
                </td>
                <th align="right">单体电池额定电压：</th>
                <td><?php echo isset($threeElectricSystem['battery']['single_voltage']) ? $threeElectricSystem['battery']['single_voltage'] : ''; ?> V</td>
            </tr>
            <tr>
                <th align="right">单体电池额定容量：</th>
                <td><?php echo isset($threeElectricSystem['battery']['single_capacity']) ? $threeElectricSystem['battery']['single_capacity'] : ''; ?> Ah</td>
                <th align="right">电池模块容量：</th>
                <td><?php echo isset($threeElectricSystem['battery']['module_capacity']) ? $threeElectricSystem['battery']['module_capacity'] : ''; ?> kWh</td>
                <th align="right">电池模块数量：</th>
                <td><?php echo isset($threeElectricSystem['battery']['module_nums']) ? $threeElectricSystem['battery']['module_nums'] : ''; ?></td>
            </tr>
            <tr>
                <th align="right">电池生产厂家：</th>
                <td><?php echo isset($threeElectricSystem['battery']['battery_maker']) ? $threeElectricSystem['battery']['battery_maker'] : ''; ?></td>
                <th align="right"></th>
                <td></td>
                <th align="right"></th>
                <td></td>
            </tr>
            <tr>
                <td colspan="6">
                    <div style="width:100%;border-bottom:1px dashed #ddd;"></div>
                </td>
            </tr>
            <tr>
                <th align="right">电机型号：</th>
                <td><?php echo isset($threeElectricSystem['motor']['motor_model']) ? $threeElectricSystem['motor']['motor_model'] : ''; ?></td>
                <th align="right">编码器：</th>
                <td>
                    <?php
                        if(isset($threeElectricSystem['motor']['encoder'])){
                            $op = $threeElectricSystem['motor']['encoder'];
                            echo isset($config['encoder'][$op]) ? $config['encoder'][$op]['text'] : '' ;
                        }else{
                            echo '';
                        }
                    ?>
                </td>
                <th align="right">额定功率：</th>
                <td><?php echo isset($threeElectricSystem['motor']['rated_power']) ? $threeElectricSystem['motor']['rated_power'] : ''; ?> kW</td>
            </tr>
            <tr>
                <th align="right">额定转速：</th>
                <td><?php echo isset($threeElectricSystem['motor']['rated_speed']) ? $threeElectricSystem['motor']['rated_speed'] : ''; ?> rpm</td>
                <th align="right">额定频率：</th>
                <td><?php echo isset($threeElectricSystem['motor']['rated_frequency']) ? $threeElectricSystem['motor']['rated_frequency'] : ''; ?> Hz</td>
                <th align="right">额定电流：</th>
                <td><?php echo isset($threeElectricSystem['motor']['rated_current']) ? $threeElectricSystem['motor']['rated_current'] : ''; ?> A</td>
            </tr>
            <tr>
                <th align="right">额定转矩：</th>
                <td><?php echo isset($threeElectricSystem['motor']['rated_torque']) ? $threeElectricSystem['motor']['rated_torque'] : ''; ?> Nm</td>
                <th align="right">额定电压：</th>
                <td><?php echo isset($threeElectricSystem['motor']['rated_voltage']) ? $threeElectricSystem['motor']['rated_voltage'] : ''; ?> V</td>
                <th align="right">峰值功率：</th>
                <td><?php echo isset($threeElectricSystem['motor']['peak_power']) ? $threeElectricSystem['motor']['peak_power'] : ''; ?> kW</td>
            </tr>
            <tr>
                <th align="right">峰值转速：</th>
                <td><?php echo isset($threeElectricSystem['motor']['peak_speed']) ? $threeElectricSystem['motor']['peak_speed'] : ''; ?> rpm</td>
                <th align="right">峰值频率：</th>
                <td><?php echo isset($threeElectricSystem['motor']['peak_frequency']) ? $threeElectricSystem['motor']['peak_frequency'] : ''; ?> Hz</td>
                <th align="right">峰值电流：</th>
                <td><?php echo isset($threeElectricSystem['motor']['peak_current']) ? $threeElectricSystem['motor']['peak_current'] : ''; ?> A</td>
            </tr>
            <tr>
                <th align="right">峰值转矩：</th>
                <td><?php echo isset($threeElectricSystem['motor']['peak_torque']) ? $threeElectricSystem['motor']['peak_torque'] : ''; ?> Nm</td>
                <th align="right">极对数：</th>
                <td><?php echo isset($threeElectricSystem['motor']['polar_logarithm']) ? $threeElectricSystem['motor']['polar_logarithm'] : ''; ?></td>
                <th align="right">冷却方式：</th>
                <td>
                    <?php
                    if(isset($threeElectricSystem['motor_monitor']['cooling_type'])){
                        $op = $threeElectricSystem['motor_monitor']['cooling_type'];
                        echo isset($config['cooling_type'][$op]) ? $config['cooling_type'][$op]['text'] : '' ;
                    }else{
                        echo '';
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <th align="right">电机生产厂家：</th>
                <td><?php echo isset($threeElectricSystem['motor']['motor_maker']) ? $threeElectricSystem['motor']['motor_maker'] : ''; ?></td>
                <th align="right"></th>
                <td></td>
                <th align="right"></th>
                <td></td>
            </tr>
            <tr>
                <td colspan="6">
                    <div style="width:100%;border-bottom:1px dashed #ddd;"></div>
                </td>
            </tr>
            <tr>
                <th align="right">电机控制器型号：</th>
                <td><?php echo isset($threeElectricSystem['motor_monitor']['motor_monitor_model']) ? $threeElectricSystem['motor_monitor']['motor_monitor_model'] : ''; ?></td>
                <th align="right">适用电机：</th>
                <td>
                    <?php
                    if(isset($threeElectricSystem['motor_monitor']['apply_motor_type'])){
                        $op = $threeElectricSystem['motor_monitor']['apply_motor_type'];
                        echo isset($config['apply_motor_type'][$op]) ? $config['apply_motor_type'][$op]['text'] : '' ;
                    }else{
                        echo '';
                    }
                    ?>
                </td>
                <th align="right">输入电压范围：</th>
                <td>
                    <?php
                        echo isset($threeElectricSystem['motor_monitor']['input_voltage_range_s']) ? $threeElectricSystem['motor_monitor']['input_voltage_range_s'] : '';
                        echo ' - ';
                        echo isset($threeElectricSystem['motor_monitor']['input_voltage_range_e']) ? $threeElectricSystem['motor_monitor']['input_voltage_range_e'] : '';
                    ?> kW
                </td>
            </tr>
            <tr>
                <th align="right">额定输入电压：</th>
                <td><?php echo isset($threeElectricSystem['motor_monitor']['rated_input_voltage']) ? $threeElectricSystem['motor_monitor']['rated_input_voltage'] : ''; ?> rpm</td>
                <th align="right">额定容量：</th>
                <td><?php echo isset($threeElectricSystem['motor_monitor']['rated_capacity']) ? $threeElectricSystem['motor_monitor']['rated_capacity'] : ''; ?> kVA</td>
                <th align="right">峰值容量：</th>
                <td><?php echo isset($threeElectricSystem['motor_monitor']['peak_capacity']) ? $threeElectricSystem['motor_monitor']['peak_capacity'] : ''; ?> kVA</td>
            </tr>
            <tr>
                <th align="right">额定输入电流：</th>
                <td><?php echo isset($threeElectricSystem['motor_monitor']['rated_input_current']) ? $threeElectricSystem['motor_monitor']['rated_input_current'] : ''; ?> A</td>
                <th align="right">额定输出电流：</th>
                <td><?php echo isset($threeElectricSystem['motor_monitor']['rated_output_current']) ? $threeElectricSystem['motor_monitor']['rated_output_current'] : ''; ?> A</td>
                <th align="right">峰值输出电流：</th>
                <td><?php echo isset($threeElectricSystem['motor_monitor']['peak_output_current']) ? $threeElectricSystem['motor_monitor']['peak_output_current'] : ''; ?> A</td>
            </tr>
            <tr>
                <th align="right">峰值电流持续时间：</th>
                <td><?php echo isset($threeElectricSystem['motor_monitor']['peak_current_duration']) ? $threeElectricSystem['motor_monitor']['peak_current_duration'] : ''; ?> min</td>
                <th align="right">输出频率范围：</th>
                <td>
                    <?php
                        echo isset($threeElectricSystem['motor_monitor']['output_frequency_range_s']) ? $threeElectricSystem['motor_monitor']['output_frequency_range_s'] : '';
                        echo ' - ';
                        echo isset($threeElectricSystem['motor_monitor']['output_frequency_range_e']) ? $threeElectricSystem['motor_monitor']['output_frequency_range_e'] : '';
                    ?> Hz
                </td>
                <th align="right">控制器最大效率：</th>
                <td><?php echo isset($threeElectricSystem['motor_monitor']['max_effciency']) ? $threeElectricSystem['motor_monitor']['max_effciency'] : ''; ?> %</td>
            </tr>
            <tr>
                <th align="right">防护等级：</th>
                <td><?php echo isset($threeElectricSystem['motor_monitor']['protection_level']) ? $threeElectricSystem['motor_monitor']['protection_level'] : ''; ?></td>
                <th align="right">工作环境温度：</th>
                <td><?php echo isset($threeElectricSystem['motor_monitor']['working_temp']) ? $threeElectricSystem['motor_monitor']['working_temp'] : ''; ?> ℃</td>
                <th align="right">冷却方式：</th>
                <td>
                    <?php
                    if(isset($threeElectricSystem['motor_monitor']['cooling_type'])){
                        $op = $threeElectricSystem['motor_monitor']['cooling_type'];
                        echo isset($config['cooling_type'][$op]) ? $config['cooling_type'][$op]['text'] : '' ;
                    }else{
                        echo '';
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <th align="right">电控生产厂家：</th>
                <td><?php echo isset($threeElectricSystem['motor_monitor']['motor_monitor_maker']) ? $threeElectricSystem['motor_monitor']['motor_monitor_maker'] : ''; ?></td>
                <th align="right"></th>
                <td></td>
                <th align="right"></th>
                <td></td>
            </tr>
        </table>
    </div>

</div>