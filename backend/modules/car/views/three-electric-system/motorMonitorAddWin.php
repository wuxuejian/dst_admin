<div style="padding:5px;">
    <form id="ThreeElectricSystemMotorMonitor_addWin_form">
        <table cellpadding="8" cellspacing="0" align="center"  width="100%" border="0">
            <tr>
                <td align="right">电机控制器型号</td>
                <td>
                    <input class="easyui-textbox" name="motor_monitor_model" style="width:160px;" required="true" />
                </td>
                <td align="right">适用电机</td>
                <td>
                    <select class="easyui-combobox" name="apply_motor_type" style="width:160px;"
                            data-options="panelHeight:'auto',editable:false" required="true"
                        >
                        <option value="" selected="selected">--不限--</option>
                        <?php foreach($config['apply_motor_type'] as $val){ ?>
                            <option value="<?= $val['value'] ?>"><?= $val['text'] ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right">输入电压范围</td>
                <td>
                    <input class="easyui-numberbox" name="input_voltage_range_s" style="width:70px;" precision="2"  /> -
                    <input class="easyui-numberbox" name="input_voltage_range_e" style="width:70px;" precision="2"  /> kW
                </td>
                <td align="right">额定输入电压</td>
                <td>
                    <input class="easyui-numberbox" name="rated_input_voltage" style="width:160px;" precision="2"  /> rpm
                </td>
            </tr>
            <tr>
                <td align="right">额定容量</td>
                <td>
                    <input class="easyui-numberbox" name="rated_capacity" style="width:160px;" precision="2"  /> kVA
                </td>
                <td align="right">峰值容量</td>
                <td>
                    <input class="easyui-numberbox" name="peak_capacity" style="width:160px;" precision="2"  /> kVA
                </td>
            </tr>
            <tr>
                <td align="right">额定输入电流</td>
                <td>
                    <input class="easyui-numberbox" name="rated_input_current" style="width:160px;" precision="2"  /> A
                </td>
                <td align="right">额定输出电流</td>
                <td>
                    <input class="easyui-numberbox" name="rated_output_current" style="width:160px;" precision="2"  /> A
                </td>
            </tr>
            <tr>
                <td align="right">峰值输出电流</td>
                <td>
                    <input class="easyui-numberbox" name="peak_output_current" style="width:160px;" precision="2"  /> A
                </td>
                <td align="right">峰值电流持续时间</td>
                <td>
                    <input class="easyui-numberbox" name="peak_current_duration" style="width:160px;" /> min
                </td>
            </tr>
            <tr>
                <td align="right">输出频率范围</td>
                <td>
                    <input class="easyui-numberbox" name="output_frequency_range_s" style="width:70px;" precision="2"  /> -
                    <input class="easyui-numberbox" name="output_frequency_range_e" style="width:70px;" precision="2"  /> Hz
                </td>
                <td align="right">控制器最大效率</td>
                <td>
                    <input class="easyui-numberbox" name="max_effciency" style="width:160px;" precision="2"  /> %
                </td>
            </tr>
            <tr>
                <td align="right">防护等级</td>
                <td>
                    <input class="easyui-textbox" name="protection_level" style="width:160px;"  />
                </td>
                <td align="right">工作环境温度</td>
                <td>
                    <input class="easyui-numberbox" name="working_temp" style="width:160px;" precision="2"  /> ℃
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
                    <input class="easyui-textbox" name="motor_monitor_maker" style="width:160px;" />
                </td>
            </tr>
        </table>
    </form>
</div>