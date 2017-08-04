<div style="padding:5px;">
    <form id="carmonitorDetectionIndex_setParamsWin_scanCriteriaDetailsWin">
        <table cellpadding="8" cellspacing="2" align="center"  width="100%" border="0">
            <tr>
                <th align="right">电池类型：</th>
                <td colspan="5">
                    <?php echo $criteriaInfo['battery_type']; ?>
                </td>
            </tr>
            <tr>
                <td colspan="6">
                    <div style="width:100%;border-bottom:1px dashed #ddd;"></div>
                </td>
            </tr>
            <tr>
                <th align="right">充电电流阀值I1：</th>
                <td>
                    <?php echo $criteriaInfo['I1']; ?> A
                </td>
                <th></th>
                <td></td>
                <th></th>
                <td></td>
            </tr>
            <tr>
                <th align="right">单体电池电压平均值范围V1：</th>
                <td>
                    <?php echo $criteriaInfo['V1_S']; ?> mV -
                    <?php echo $criteriaInfo['V1_E']; ?> mV
                </td>
                <th align="right">单体电池电压平均值范围V2：</th>
                <td>
                    <?php echo $criteriaInfo['V2_S']; ?> mV -
                    <?php echo $criteriaInfo['V2_E']; ?> mV
                </td>
                <th align="right">单体电池电压平均值范围V3：</th>
                <td>
                    <?php echo $criteriaInfo['V3_S']; ?> mV -
                    <?php echo $criteriaInfo['V3_E']; ?> mV
                </td>
            </tr>
            <tr>
                <th align="right">SOC区间范围Y1：</th>
                <td width="18%">
                    <?php echo $criteriaInfo['Y1_S']; ?> %&nbsp;-
                    <?php echo $criteriaInfo['Y1_E']; ?> %
                </td>
                <th align="right">SOC区间范围Y2：</th>
                <td>
                    <?php echo $criteriaInfo['Y2_S']; ?> %&nbsp;-
                    <?php echo $criteriaInfo['Y2_E']; ?> %
                </td>
                <th align="right">SOC区间范围Y3：</th>
                <td>
                    <?php echo $criteriaInfo['Y3_S']; ?> %&nbsp;-
                    <?php echo $criteriaInfo['Y3_E']; ?> %
                </td>
            </tr>
            <tr>
                <td colspan="6">
                    <div style="width:100%;border-bottom:1px dashed #ddd;"></div>
                </td>
            </tr>
            <tr>
                <th align="right">单体电池电压平均值范围V4：</th>
                <td>
                    <?php echo $criteriaInfo['V4_S']; ?> mV -
                    <?php echo $criteriaInfo['V4_S']; ?> mV
                </td>
                <th align="right">单体电池电压平均值范围V5：</th>
                <td>
                    <?php echo $criteriaInfo['V5_S']; ?> mV -
                    <?php echo $criteriaInfo['V5_S']; ?> mV
                </td>
                <th align="right">单体电池电压平均值范围V6：</th>
                <td>
                    <?php echo $criteriaInfo['V6_S']; ?> mV -
                    <?php echo $criteriaInfo['V6_S']; ?> mV
                </td>
            </tr>
            <tr>
                <th align="right">单体最高最低压差值A1：</th>
                <td>
                    <?php echo $criteriaInfo['A1']; ?> mV
                </td>
                <th align="right">单体最高最低压差值A2：</th>
                <td>
                    <?php echo $criteriaInfo['A2']; ?> mV
                </td>
                <th align="right">单体最高最低压差值A3：</th>
                <td>
                    <?php echo $criteriaInfo['A3']; ?> mV
                </td>
            </tr>
            <tr>
                <td colspan="6">
                    <div style="width:100%;border-bottom:1px dashed #ddd;"></div>
                </td>
            </tr>
            <tr>
                <th align="right">判定开始充电时间值T1：</th>
                <td>
                    <?php echo $criteriaInfo['T1']; ?> 分钟
                </td>
                <th align="right">充电Ah累计时间值T2：</th>
                <td>
                    <?php echo $criteriaInfo['T2']; ?> 分钟
                </td>
                <th align="right">充电电流判定时间T3：</th>
                <td>
                    <?php echo $criteriaInfo['T3']; ?> 分钟
                </td>
            </tr>
            <tr>
                <th align="right">SOC容量偏差百分比X：</th>
                <td>
                    <?php echo $criteriaInfo['X']; ?> %
                </td>
                <th align="right">充电电流阀值I2：</th>
                <td>
                    <?php echo $criteriaInfo['I2']; ?> A
                </td>
                <th></th>
                <td></td>
            </tr>
            <tr>
                <th align="right">单体电池电压平均值V7：</th>
                <td>
                    <?php echo $criteriaInfo['V7']; ?> mV
                </td>
                <th align="right">单体电池电压最大值V8：</th>
                <td>
                    <?php echo $criteriaInfo['V8']; ?> mV
                </td>
                <th></th>
                <td></td>
            </tr>


        </table>
    </form>
</div>