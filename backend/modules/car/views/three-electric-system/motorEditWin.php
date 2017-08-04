<div style="padding:5px;">
    <form id="ThreeElectricSystemMotor_editWin_form">
        <input type="hidden" name="id" />
        <table cellpadding="8" cellspacing="0" align="center"  width="100%" border="0">
            <tr>
                <td align="right">电机型号</td>
                <td>
                    <input class="easyui-textbox" name="motor_model" style="width:160px;" required="true" />
                </td>
                <td align="right">编码器</td>
                <td>
                    <select class="easyui-combobox" name="encoder" style="width:160px;"
                            data-options="panelHeight:'auto',editable:false" required="true"
                        >
                        <option value="" selected="selected">--不限--</option>
                        <?php foreach($config['encoder'] as $val){ ?>
                            <option value="<?= $val['value'] ?>"><?= $val['text'] ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right">额定功率</td>
                <td>
                    <input class="easyui-numberbox" name="rated_power" style="width:160px;" precision="2"  /> kW
                </td>
                <td align="right">额定转速</td>
                <td>
                    <input class="easyui-numberbox" name="rated_speed" style="width:160px;" precision="2"  /> rpm
                </td>
            </tr>
            <tr>
                <td align="right">额定频率</td>
                <td>
                    <input class="easyui-numberbox" name="rated_frequency" style="width:160px;" precision="2"  /> Hz
                </td>
                <td align="right">额定电流</td>
                <td>
                    <input class="easyui-numberbox" name="rated_current" style="width:160px;" precision="2"  /> A
                </td>
            </tr>
            <tr>
                <td align="right">额定转矩</td>
                <td>
                    <input class="easyui-numberbox" name="rated_torque" style="width:160px;" precision="2"  /> Nm
                </td>
                <td align="right">额定电压</td>
                <td>
                    <input class="easyui-numberbox" name="rated_voltage" style="width:160px;" precision="2"  /> V
                </td>
            </tr>
            <tr>
                <td align="right">峰值功率</td>
                <td>
                    <input class="easyui-numberbox" name="peak_power" style="width:160px;" precision="2"  /> kW
                </td>
                <td align="right">峰值转速</td>
                <td>
                    <input class="easyui-numberbox" name="peak_speed" style="width:160px;" precision="2"  /> rpm
                </td>
            </tr>
            <tr>
                <td align="right">峰值频率</td>
                <td>
                    <input class="easyui-numberbox" name="peak_frequency" style="width:160px;" precision="2"  /> Hz
                </td>
                <td align="right">峰值电流</td>
                <td>
                    <input class="easyui-numberbox" name="peak_current" style="width:160px;" precision="2"  /> A
                </td>
            </tr>
            <tr>
                <td align="right">峰值转矩</td>
                <td>
                    <input class="easyui-numberbox" name="peak_torque" style="width:160px;" precision="2"  /> Nm
                </td>
                <td align="right">极对数</td>
                <td>
                    <input class="easyui-numberbox" name="polar_logarithm" style="width:160px;" precision="2"  />
                </td>
            </tr>
            <tr>
                <td align="right">冷却方式</td>
                <td>
                    <select class="easyui-combobox" name="cooling_type" style="width:160px;"
                            data-options="panelHeight:'auto',editable:false" required="true"
                        >
                        <option value="" selected="selected">--不限--</option>
                        <?php foreach($config['cooling_type'] as $val){ ?>
                            <option value="<?= $val['value'] ?>"><?= $val['text'] ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td align="right">生产厂家</td>
                <td>
                    <input class="easyui-textbox" name="motor_maker" style="width:160px;" />
                </td>
            </tr>
        </table>
    </form>
</div>

<script>
    var oldData = <?php echo json_encode($recInfo); ?>;
    $('#ThreeElectricSystemMotor_editWin_form').form('load',oldData);
</script>