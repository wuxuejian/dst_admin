<div style="padding:5px;">
    <form id="carmonitorDetectionIndex_setParamsWin_editCriteriaWin_form">
        <input type="hidden" name="id" />
        <table cellpadding="8" cellspacing="0" align="center"  width="100%" border="0">
            <tr>
                <td align="right">电池类型</td>
                <td colspan="5">
                    <select class="easyui-combobox" name="battery_type" style="width:175px;"
                            data-options="panelHeight:'auto',editable:false" required="true"
                        >
                        <?php foreach($config['battery_type'] as $val){ ?>
                            <option value="<?= $val['value'] ?>"><?= $val['text'] ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="6">
                    <div style="width:100%;border-bottom:1px dashed #ddd;"></div>
                </td>
            </tr>
            <tr>
                <td align="right">充电电流阀值I1</td>
                <td>
                    <input class="easyui-numberbox" name="I1" style="width:175px;" required="true" value="10" precision="2" /> A
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td align="right">单体电池电压平均值范围V1</td>
                <td>
                    <input class="easyui-numberbox" name="V1_S" style="width:70px;" required="true" precision="2" /> mV -
                    <input class="easyui-numberbox" name="V1_E" style="width:70px;" required="true" precision="2" /> mV
                </td>
                <td align="right">单体电池电压平均值范围V2</td>
                <td>
                    <input class="easyui-numberbox" name="V2_S" style="width:70px;" required="true" precision="2" /> mV -
                    <input class="easyui-numberbox" name="V2_E" style="width:70px;" required="true" precision="2" /> mV
                </td>
                <td align="right">单体电池电压平均值范围V3</td>
                <td>
                    <input class="easyui-numberbox" name="V3_S" style="width:70px;" required="true" precision="2" /> mV -
                    <input class="easyui-numberbox" name="V3_E" style="width:70px;" required="true" precision="2" /> mV
                </td>
            </tr>
            <tr>
                <td align="right">SOC区间范围Y1</td>
                <td>
                    <input class="easyui-numberbox" name="Y1_S" style="width:70px;" required="true" value="10" precision="2" /> %&nbsp;-
                    <input class="easyui-numberbox" name="Y1_E" style="width:70px;" required="true" value="20" precision="2" /> %
                </td>
                <td align="right">SOC区间范围Y2</td>
                <td>
                    <input class="easyui-numberbox" name="Y2_S" style="width:70px;" required="true" value="30" precision="2" /> %&nbsp;-
                    <input class="easyui-numberbox" name="Y2_E" style="width:70px;" required="true" value="50" precision="2" /> %
                </td>
                <td align="right">SOC区间范围Y3</td>
                <td>
                    <input class="easyui-numberbox" name="Y3_S" style="width:70px;" required="true" value="50" precision="2" /> %&nbsp;-
                    <input class="easyui-numberbox" name="Y3_E" style="width:70px;" required="true" value="90" precision="2" /> %
                </td>
            </tr>
            <tr>
                <td colspan="6">
                    <div style="width:100%;border-bottom:1px dashed #ddd;"></div>
                </td>
            </tr>
            <tr>
                <td align="right">单体电池电压平均值范围V4</td>
                <td>
                    <input class="easyui-numberbox" name="V4_S" style="width:70px;" required="true" precision="2" /> mV -
                    <input class="easyui-numberbox" name="V4_E" style="width:70px;" required="true" precision="2" /> mV
                </td>
                <td align="right">单体电池电压平均值范围V5</td>
                <td>
                    <input class="easyui-numberbox" name="V5_S" style="width:70px;" required="true" precision="2" /> mV -
                    <input class="easyui-numberbox" name="V5_E" style="width:70px;" required="true" precision="2" /> mV
                </td>
                <td align="right">单体电池电压平均值范围V6</td>
                <td>
                    <input class="easyui-numberbox" name="V6_S" style="width:70px;" required="true" precision="2" /> mV -
                    <input class="easyui-numberbox" name="V6_E" style="width:70px;" required="true" precision="2" /> mV
                </td>
            </tr>
            <tr>
                <td align="right">单体最高最低压差值A1</td>
                <td>
                    <input class="easyui-numberbox" name="A1" style="width:175px;" required="true" precision="2" /> mV
                </td>
                <td align="right">单体最高最低压差值A2</td>
                <td>
                    <input class="easyui-numberbox" name="A2" style="width:175px;" required="true" precision="2" /> mV
                </td>
                <td align="right">单体最高最低压差值A3</td>
                <td>
                    <input class="easyui-numberbox" name="A3" style="width:175px;" required="true" precision="2" /> mV
                </td>
            </tr>
            <tr>
                <td colspan="6">
                    <div style="width:100%;border-bottom:1px dashed #ddd;"></div>
                </td>
            </tr>
            <tr>
                <td align="right">判定开始充电时间值T1</td>
                <td>
                    <input class="easyui-numberbox" name="T1" style="width:175px;" required="true"/> 分钟
                </td>
                <td align="right">充电Ah累计时间值T2</td>
                <td>
                    <input class="easyui-numberbox" name="T2" style="width:175px;" required="true"/> 分钟
                </td>
                <td align="right">充电电流判定时间T3</td>
                <td>
                    <input class="easyui-numberbox" name="T3" style="width:175px;" required="true"/> 分钟
                </td>
            </tr>
            <tr>
                <td align="right">SOC容量偏差百分比X</td>
                <td>
                    <input class="easyui-numberbox" name="X" style="width:175px;" required="true" value="10" precision="2"  /> %
                </td>
                <td align="right">充电电流阀值I2</td>
                <td>
                    <input class="easyui-numberbox" name="I2" style="width:175px;" required="true" value="25" precision="2"  /> A
                </td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td align="right">单体电池电压平均值V7</td>
                <td>
                    <input class="easyui-numberbox" name="V7" style="width:175px;" required="true" precision="2" /> mV
                </td>
                <td align="right">单体电池电压最大值V8</td>
                <td>
                    <input class="easyui-numberbox" name="V8" style="width:175px;" required="true" precision="2" /> mV
                </td>
                <td></td>
                <td></td>
            </tr>
        </table>
    </form>
</div>

<script>
    var oldData = <?php echo json_encode($criteriaInfo); ?>;
    $('#carmonitorDetectionIndex_setParamsWin_editCriteriaWin').form('load',oldData);
</script>