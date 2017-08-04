<div style="padding:5px;">
    <form id="ThreeElectricSystemBattery_editWin_form">
        <input type="hidden" name="id" />
        <table cellpadding="8" cellspacing="0" align="center"  width="100%" border="0">
            <tr>
                <td align="right">电池型号</td>
                <td>
                    <input class="easyui-textbox" name="battery_model" style="width:160px;" required="true" />
                </td>
                <td align="right">电池类型</td>
                <td>
                    <select class="easyui-combobox" name="battery_type" style="width:160px;"
                            data-options="panelHeight:'auto',editable:false" required="true"
                        >
                        <option value="" selected="selected">--不限--</option>
                        <?php foreach($config['battery_type'] as $val){ ?>
                            <option value="<?= $val['value'] ?>"><?= $val['text'] ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right">电池系统额定电压</td>
                <td>
                    <input class="easyui-numberbox" name="system_voltage" style="width:160px;" precision="2"  /> V
                </td>
                <td align="right">电池系统额定容量</td>
                <td>
                    <input class="easyui-numberbox" name="system_capacity" style="width:160px;" precision="2"  /> Ah
                </td>
            </tr>
            <tr>
                <td align="right">电池系统额定电能</td>
                <td>
                    <input class="easyui-numberbox" name="system_power" style="width:160px;" precision="2"  /> kWh
                </td>
                <td align="right">电池系统电池串联数量</td>
                <td>
                    <input class="easyui-numberbox" name="system_nums" style="width:160px;" /> 个
                </td>
            </tr>
            <tr>
                <td align="right">单体电池额定电压</td>
                <td>
                    <input class="easyui-numberbox" name="single_voltage" style="width:160px;" precision="2"  /> V
                </td>
                <td align="right">单体电池额定容量</td>
                <td>
                    <input class="easyui-numberbox" name="single_capacity" style="width:160px;" precision="2"  /> Ah
                </td>
            </tr>
            <tr>
                <td align="right">电池模块容量</td>
                <td>
                    <input class="easyui-numberbox" name="module_capacity" style="width:160px;" precision="2" /> kWh
                </td>
                <td align="right">电池模块数量</td>
                <td>
                    <input class="easyui-numberbox" name="module_nums" style="width:160px;" /> 个
                </td>
            </tr>
            <tr>
                <td align="right">充电接口类型</td>
                <td>
                    <select class="easyui-combobox" name="connection_type" style="width:160px;"
                            data-options="panelHeight:'auto',editable:false"
                        >
                        <option value="" selected="selected">--不限--</option>
                        <?php foreach($config['connection_type'] as $val){ ?>
                            <option value="<?= $val['value'] ?>"><?= $val['text'] ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td align="right">电池规格</td>
                <td>
                    <select class="easyui-combobox" name="battery_spec" style="width:160px;"
                            data-options="panelHeight:'auto',editable:false"
                        >
                        <option value="" selected="selected">--不限--</option>
                        <?php foreach($config['battery_spec'] as $val){ ?>
                            <option value="<?= $val['value'] ?>"><?= $val['text'] ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right">生产厂家</td>
                <td>
                    <input class="easyui-textbox" name="battery_maker" style="width:160px;"  />
                </td>
                <td></td>
                <td></td>
            </tr>
        </table>
    </form>
</div>

<script>
    var oldData = <?php echo json_encode($recInfo); ?>;
    $('#ThreeElectricSystemBattery_editWin_form').form('load',oldData);
</script>