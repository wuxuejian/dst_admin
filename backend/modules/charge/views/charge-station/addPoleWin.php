<form id="chargeChargeStationIndex_addPoleWin_form" method="post" style="padding:5px 0px;">
    <table cellpadding="5" cellspacing="0" style="width:100%;" border="0">
        <tr>
            <td align="right">所属电站</td>
            <td>
                <input class="easyui-textbox" name="station_name" style="width:160px;" value="<?php echo $station['cs_name']; ?>" editable="false" disabled="true" />
            </td>
            <td align="right" hidden>所属电站ID</td>
            <td colspan="3" hidden>
                <input class="easyui-textbox" name="station_id" style="width:160px;" value="<?php echo $station['cs_id']; ?>" editable="false" />
            </td>
        </tr>
        <tr>
            <td align="right">逻辑地址</td>
            <td colspan="5">
                <input class="easyui-numberbox" name="logic_addr" style="width:160px;" />
                <span style="padding-left:10px;color:#FF7070">* 逻辑地址确保电桩与前置机数据库中某设备地址相对应</span>
            </td>
        </tr>
        <tr>
            <th align="right" style="background-color:#EBDFA1;">基本信息</th>
            <td colspan="5" style="background-color:#EBDFA1;"></td>
        </tr>
        <tr hidden>
            <td align="right">电桩ID</td>
            <td colspan="5">
                <input class="easyui-textbox" name="id" style="width:160px;" value="0" editable="false" />
            </td>
        </tr>
        <tr>
            <td align="right" width="10%">电桩编号</td>
            <td width="23%">
                <input class="easyui-textbox" name="code_from_compony" style="width:160px;" required="true" />
            </td>
            <td align="right" width="10%">出厂编号</td>
            <td>
                <input class="easyui-textbox" name="code_from_factory" style="width:160px;" required="true" />
            </td>
            <td align="right"  width="10%">电桩型号</td>
            <td width="23%">
                <select class="easyui-combobox" name="model" style="width:160px;" data-options="panelHeight:'auto',required:true"  editable=false >
                    <?php foreach($config['model'] as $val){ ?>
                        <option value="<?php echo $val['value'] ?>"><?php echo $val['text']; ?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>
        <tr>
            <td align="right">电桩类型</td>
            <td>
                <select class="easyui-combobox" name="charge_type" style="width:160px;"  editable=false
                        data-options="
                        panelHeight:'auto',
                        required:true,
                        onChange: function(newVal,oldVal){
                            if(newVal == 'DC' || newVal == 'AC'){
                                $('#addPoleWin_chargeGunNums').numberbox('setValue',1);
                            }else{
                                $('#addPoleWin_chargeGunNums').numberbox('setValue',2);
                            }
                        }
                    ">
                    <?php foreach($config['charge_type'] as $val){ ?>
                        <option value="<?php echo $val['value'] ?>"><?php echo $val['text']; ?></option>
                    <?php } ?>
                </select>
            </td>
            <td align="right">协议类型</td>
            <td>
                <select class="easyui-combobox" name="connection_type" style="width:160px;" data-options="panelHeight:'auto',required:true"  editable=false >
                    <?php foreach($config['connection_type'] as $val){ ?>
                        <option value="<?php echo $val['value'] ?>"><?php echo $val['text']; ?></option>
                    <?php } ?>
                </select>
            </td>
            <td align="right">生产厂家</td>
            <td>
                <select class="easyui-combobox" name="manufacturer" style="width:160px;" data-options="panelHeight:'auto',required:true"  editable=false >
                    <?php foreach($config['manufacturer'] as $val){ ?>
                        <option value="<?php echo $val['value'] ?>"><?php echo $val['text']; ?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>
        <tr>
            <td align="right">充电模式</td>
            <td>
                <select class="easyui-combobox" name="charge_pattern" style="width:160px;" data-options="panelHeight:'auto',required:true"  editable=false >
                    <?php foreach($config['charge_pattern'] as $val){ ?>
                        <option value="<?php echo $val['value'] ?>"><?php echo $val['text']; ?></option>
                    <?php } ?>
                </select>
            </td>
            <td align="right">购置日期</td>
            <td colspan="3">
                <input class="easyui-datebox" name="purchase_date" style="width:160px;" validType="date"  />
            </td>
        </tr>
        <tr hidden>
            <td align="right" valign="top" rowspan="2">规格参数</td>
            <td colspan="5">
                &nbsp;&nbsp;电枪数量
                <input class="easyui-numberbox" name="charge_gun_nums" id="addPoleWin_chargeGunNums" style="width:80px;" value="1" editable="false"  />个&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;线长
                <input class="easyui-numberbox" name="wire_length" style="width:80px;" precision="2" min="0"  />米
            </td>
        </tr>
        <tr hidden>
            <td colspan="5">
                额定输出电压
                <input class="easyui-numberbox" name="rated_output_voltage" style="width:80px;" precision="2" min="0" /> V&nbsp;&nbsp;
                额定输出电流
                <input class="easyui-numberbox" name="rated_output_current" style="width:80px;" precision="2" min="0" /> A&nbsp;&nbsp;
                额定输出功率
                <input class="easyui-numberbox" name="rated_output_power" style="width:80px;" precision="2" min="0"  /> KW&nbsp;&nbsp;
            </td>
        </tr>
        <tr>
            <td align="right">安装方式</td>
            <td>
                <select class="easyui-combobox" name="install_type" style="width:160px;" data-options="panelHeight:'auto',required:true"  editable=false >
                    <?php foreach($config['install_type'] as $val){ ?>
                        <option value="<?php echo $val['value'] ?>"><?php echo $val['text']; ?></option>
                    <?php } ?>
                </select>
            </td>
            <td align="right">安装日期</td>
            <td colspan="3">
                <input class="easyui-datebox" name="install_date" style="width:160px;" validType="date" value="<?php echo date('Y-m-d'); ?>" />
            </td>
        </tr>
        <tr>
            <td align="right" valign="top">备注</td>
            <td colspan="5">
                <input class="easyui-textbox" name="mark" style="width:600px;height:60px;"
                       data-options="multiline:true"
                       validType="length[150]"  />
            </td>
        </tr>
    </table>
</form>

