<form id="ChargeSpotsIndex_addEditWindow_baseInfo" method="post" style="padding:5px 0px;">
    <table cellpadding="5" cellspacing="2" style="width:100%;" border="0">
        <tr hidden>
            <td align="right">所属电站ID：</td>
            <td colspan="5">
                <?php echo $chargerInfo['station_id']; ?>
            </td>
        </tr>
        <tr>
            <td align="right">所属电站：</td>
            <td colspan="5">
                <?php echo $chargerInfo['station_name']; ?>
            </td>
        </tr>
        <tr>
            <td align="right">逻辑地址：</td>
            <td colspan="5">
                <?php echo $chargerInfo['logic_addr']; ?>
            </td>
        </tr>
        <tr>
            <th align="left" colspan="6" style="background-color:#EBDFA1;">基本信息</th>
        </tr>
        <tr hidden>
            <td align="right">电桩ID：</td>
            <td colspan="5">
                <?php echo $chargerInfo['id']; ?>
            </td>
        </tr>
        <tr>
            <td align="right" width="12%">电桩编号：</td>
            <td width="23%">
                <?php echo $chargerInfo['code_from_compony']; ?>
            </td>
            <td align="right" width="12%">出厂编号：</td>
            <td>
                <?php echo $chargerInfo['code_from_factory']; ?>
            </td>
            <td align="right"  width="12%">电桩型号：</td>
            <td width="23%">
                <?php echo isset($config['model'][$chargerInfo['model']]) ? $config['model'][$chargerInfo['model']]['text'] : ''; ?>
            </td>
        </tr>
        <tr>
            <td align="right">电桩类型：</td>
            <td>
                <?php echo isset($config['charge_type'][$chargerInfo['charge_type']]) ? $config['charge_type'][$chargerInfo['charge_type']]['text'] : ''; ?>
            </td>
            <td align="right">协议类型：</td>
            <td>
                <?php echo isset($config['connection_type'][$chargerInfo['connection_type']]) ? $config['connection_type'][$chargerInfo['connection_type']]['text'] : ''; ?>
            </td>
            <td align="right">生产厂家：</td>
            <td>
                <?php echo isset($config['manufacturer'][$chargerInfo['manufacturer']]) ? $config['manufacturer'][$chargerInfo['manufacturer']]['text'] : ''; ?>
            </td>
        </tr>
        <tr>
            <td align="right">充电模式：</td>
            <td>
                <?php echo isset($config['charge_pattern'][$chargerInfo['charge_pattern']]) ? $config['charge_pattern'][$chargerInfo['charge_pattern']]['text'] : ''; ?>
            </td>
            <td align="right">购置日期：</td>
            <td colspan="3">
                <?php echo $chargerInfo['purchase_date']; ?>
            </td>
        </tr>
        <tr hidden>
            <td align="right" valign="top" rowspan="2">规格参数：</td>
            <td colspan="5">
                &nbsp;&nbsp;电枪数量：
                <?php echo $chargerInfo['charge_gun_nums']; ?> 个&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;线长：
                <?php echo $chargerInfo['wire_length']; ?> 米
            </td>
        </tr>
        <tr hidden>
            <td colspan="5">
                额定输出电压：
                <?php echo $chargerInfo['rated_output_voltage']; ?> V&nbsp;&nbsp;
                额定输出电流：
                <?php echo $chargerInfo['rated_output_current']; ?> A&nbsp;&nbsp;
                额定输出功率：
                <?php echo $chargerInfo['rated_output_power']; ?> KW&nbsp;&nbsp;
            </td>
        </tr>
        <tr>
            <td align="right">安装方式：</td>
            <td>
                <?php echo isset($config['install_type'][$chargerInfo['install_type']]) ? $config['install_type'][$chargerInfo['install_type']]['text'] : ''; ?>
            </td>
            <td align="right">安装日期：</td>
            <td colspan="3">
                <?php echo $chargerInfo['install_date']; ?>
            </td>
        </tr>
        <tr>
            <td align="right" valign="top">备注：</td>
            <td colspan="5">
                <?php echo $chargerInfo['mark']; ?>
            </td>
        </tr>
		<!--
        <tr>
            <th align="left" colspan="6"  style="background-color:#EBDFA1;">实时数据</th>
        </tr>
        <tr>
            <td align="right">充电车辆VIN：</td>
            <td>
                CESHI123456
            </td>
            <td align="right">充电卡编号：</td>
            <td>
                CESHI123456
            </td>
            <td align="right">用户姓名：</td>
            <td>
                CESHI123456
            </td>
        </tr>
        <tr>
            <td align="right">充电输出电压：</td>
            <td>
                0.00 V
            </td>
            <td align="right">充电输出电流：</td>
            <td>
                0.00 A
            </td>
            <td align="right">充电输出功率：</td>
            <td>
                0.00 KW
            </td>
        </tr>
        <tr>
            <td align="right">最高单体电压：</td>
            <td>
                0.00 V
            </td>
            <td align="right">最高电池温度：</td>
            <td>
                0.00 ℃
            </td>
            <td align="right">最低电池温度：</td>
            <td>
                0.00 ℃
            </td>
        </tr>
        <tr>
            <td align="right">开始SOC(%)：</td>
            <td>
                0.00 %
            </td>
            <td align="right">当前SOC(%)：</td>
            <td>
                0.00 %
            </td>
            <td align="right">当前充电量：</td>
            <td>
                0.00 KWH
            </td>
        </tr>
        <tr>
            <td align="right">充电开始时间：</td>
            <td>
                2016-01-20 12:40:55
            </td>
            <td align="right">充电持续时间：</td>
            <td>
                1小时30分钟
            </td>
            <td align="right">数据采集时间：</td>
            <td>
                2016-01-20 12:40:55
            </td>
        </tr>
		-->
        <tr>
            <th align="left" colspan="6" style="background-color:#EBDFA1;">二维码</th>
        </tr>
        <tr>
            <td align="center" colspan="6">
                <ul style="list-style:none;width:400px;margin:0 auto;padding:0;overflow:hidden;">
                    <?php
                        foreach($measuringPoint as $key=>$val){
                            $qrdata = [];
                            $qrdata['pole_Id'] = $chargerInfo['id'];
                            $qrdata['measuring_point'] = $val;
                            $qrdata = json_encode($qrdata);
                    ?>
                        <li style="width:200px;text-align:center;float:left;">
                            <img style="width:200px;height:200px;" src="<?= yii::$app->urlManager->createUrl(['charge/charge-spots/create-qr','qrdata'=>$qrdata]); ?>" /><br /><?= $key; ?>
                        </li>
                    <?php } ?>
                </ul>
            </td>
        </tr>
    </table>
</form>