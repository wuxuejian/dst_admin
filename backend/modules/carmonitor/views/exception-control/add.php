<form id="easyui_form_carmonitor_exception_control_add" style="width:100%;height:100%;">
    <div class="easyui-tabs" data-options="fit:true,border:false">
        <div title="电池信息" style="padding:20px;">
            <ul class="ulforform-resizeable">
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">电池类型</div>
                    <div class="ulforform-resizeable-input">
                        <select
                            class="easyui-combobox"
                            name="battery_type"
                            data-options="editable:false,panelHeight:'auto'"
                        >
                            <?php foreach($config['battery_type'] as $val){ ?>
                            <option value="<?= $val['value']; ?>"><?= $val['text']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </li>
            </ul>
        </div>
        <div title="总电压" style="padding:20px;">
            <table style="text-align:center;width:100%">  
                <thead>
                    <tr>
                        <th data-options="field:'bjxm',width:100,align:'center'">报警项目</th>
                        <th data-options="field:'cxsz',width:140,align:'center'">参数设置</th>
                        <th data-options="field:'bjdj',width:100,align:'center'">报警等级</th>
                        <th data-options="field:'bjcl',width:180,align:'center'">报警处理</th>
                        <th data-options="field:'bjnr',width:220,align:'center'">报警内容</th>
                        <th data-options="field:'qyzt',width:100,align:'center'">启用状态</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for($i=1;$i<=4;$i++){ ?>
                    <tr>
                        <td style="padding:10px 0;">
                            总电压过高[<?= $i; ?>]
                            <input type="hidden" name="alert_type[]" value="total_vol" />
                            <input type="hidden" name="max_min[]" value="max" />
                        </td>
                        <td>
                            ≥
                            <input
                                class="easyui-textbox"
                                name="set_value[]"
                                validType="match[/^\d{1,6}(\.\d{0,3})?$/]"
                                invalidMessage="数值类型，最多三位小数，最大值999999.999！"
                            />
                            x N (V)
                            <input  type="hidden" name="interval_time[]" />
                        </td>
                        <td>
                            <select class="easyui-combobox" name="alert_level[]" data-options="panelHeight:'auto',editable:false,width:60">
                                <option value="5">5</option>
                                <option value="4">4</option>
                                <option value="3">3</option>
                                <option value="2">2</option>
                                <option value="1">1</option>
                            </select>
                        </td>
                        <td>
                            <select class="easyui-combobox" name="alert_dispose[]" data-options="panelHeight:'auto',editable:false">
                                <option value="0">不报警</option>
                                <option value="1">后台报警</option>
                                <option value="2">后台报警，短信报警</option>
                            </select>
                        </td>
                        <td>
                            <input
                                class="easyui-textbox"
                                name="alert_content[]"
                                style="width:300px;"
                                validType="length[255]"
                            />
                        </td>
                        <td>
                            <select class="easyui-combobox" name="in_use[]" data-options="panelHeight:'auto',editable:false">
                                <option value="0">禁用</option>
                                <option value="1">启用</option>
                            </select>
                        </td>
                    </tr>
                    <?php } ?>
                    <?php for($i=1;$i<=4;$i++){ ?>
                    <tr>
                        <td style="padding:10px 0;">
                            总电压过低[<?= $i ?>]
                            <input type="hidden" name="alert_type[]" value="total_vol" />
                            <input type="hidden" name="max_min[]" value="min" />
                        </td>
                        <td>
                            ≤
                            <input
                                class="easyui-textbox"
                                name="set_value[]"
                                validType="match[/^\d{1,6}(\.\d{0,3})?$/]"
                                invalidMessage="数值类型，最多三位小数，最大值999999.999！"
                            />
                            x N (V)
                            <input  type="hidden" name="interval_time[]" />
                        </td>
                        <td>
                            <select class="easyui-combobox" name="alert_level[]" data-options="panelHeight:'auto',editable:false,width:60">
                                <option value="5">5</option>
                                <option value="4">4</option>
                                <option value="3">3</option>
                                <option value="2">2</option>
                                <option value="1">1</option>
                            </select>
                        </td>
                        <td>
                            <select class="easyui-combobox" name="alert_dispose[]" data-options="panelHeight:'auto',editable:false">
                                <option value="0">不报警</option>
                                <option value="1">后台报警</option>
                                <option value="2">后台报警，短信报警</option>
                            </select>
                        </td>
                        <td>
                            <input
                                class="easyui-textbox"
                                name="alert_content[]"
                                style="width:300px;"
                                validType="length[255]"
                            />
                        </td>
                        <td>
                            <select class="easyui-combobox" name="in_use[]" data-options="panelHeight:'auto',editable:false">
                                <option value="0">禁用</option>
                                <option value="1">启用</option>
                            </select>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>  
        <div title="单体电压" style="padding:20px;">
            <table style="text-align:center;width:100%">  
                <thead>
                    <tr>
                        <th data-options="field:'bjxm',width:100,align:'center'">报警项目</th>
                        <th data-options="field:'cxsz',width:140,align:'center'">参数设置</th>
                        <th data-options="field:'bjdj',width:100,align:'center'">报警等级</th>
                        <th data-options="field:'bjcl',width:180,align:'center'">报警处理</th>
                        <th data-options="field:'bjnr',width:220,align:'center'">报警内容</th>
                        <th data-options="field:'qyzt',width:100,align:'center'">启用状态</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for($i=1;$i<=4;$i++){ ?>
                    <tr>
                        <td style="padding:10px 0;">
                            单体电压过高[<?= $i; ?>]
                            <input type="hidden" name="alert_type[]" value="single_vol" />
                            <input type="hidden" name="max_min[]" value="max" />
                        </td>
                        <td>
                            ≥
                            <input
                                class="easyui-textbox"
                                name="set_value[]"
                                validType="match[/^\d{1,6}(\.\d{0,3})?$/]"
                                invalidMessage="数值类型，最多三位小数，最大值999999.999！"
                            />
                            (V)
                            <input  type="hidden" name="interval_time[]" />
                        </td>
                        <td>
                            <select class="easyui-combobox" name="alert_level[]" data-options="panelHeight:'auto',editable:false,width:60">
                                <option value="5">5</option>
                                <option value="4">4</option>
                                <option value="3">3</option>
                                <option value="2">2</option>
                                <option value="1">1</option>
                            </select>
                        </td>
                        <td>
                            <select class="easyui-combobox" name="alert_dispose[]" data-options="panelHeight:'auto',editable:false">
                                <option value="0">不报警</option>
                                <option value="1">后台报警</option>
                                <option value="2">后台报警，短信报警</option>
                            </select>
                        </td>
                        <td>
                            <input
                                class="easyui-textbox"
                                name="alert_content[]"
                                style="width:300px;"
                                validType="length[255]"
                            />
                        </td>
                        <td>
                            <select class="easyui-combobox" name="in_use[]" data-options="panelHeight:'auto',editable:false">
                                <option value="0">禁用</option>
                                <option value="1">启用</option>
                            </select>
                        </td>
                    </tr>
                    <?php } ?>
                    <?php for($i=1;$i<=4;$i++){ ?>
                    <tr>
                        <td style="padding:10px 0;">
                            单体电压过低[<?= $i; ?>]
                            <input type="hidden" name="alert_type[]" value="single_vol" />
                            <input type="hidden" name="max_min[]" value="min" />
                        </td>
                        <td>
                            ≤
                            <input
                                class="easyui-textbox"
                                name="set_value[]"
                                validType="match[/^\d{1,6}(\.\d{0,3})?$/]"
                                invalidMessage="数值类型，最多三位小数，最大值999999.999！"
                            />
                            (V)
                            <input  type="hidden" name="interval_time[]" />
                        </td>
                        <td>
                            <select class="easyui-combobox" name="alert_level[]" data-options="panelHeight:'auto',editable:false,width:60">
                                <option value="5">5</option>
                                <option value="4">4</option>
                                <option value="3">3</option>
                                <option value="2">2</option>
                                <option value="1">1</option>
                            </select>
                        </td>
                        <td>
                            <select class="easyui-combobox" name="alert_dispose[]" data-options="panelHeight:'auto',editable:false">
                                <option value="0">不报警</option>
                                <option value="1">后台报警</option>
                                <option value="2">后台报警，短信报警</option>
                            </select>
                        </td>
                        <td>
                            <input
                                class="easyui-textbox"
                                name="alert_content[]"
                                style="width:300px;"
                                validType="length[255]"
                            />
                        </td>
                        <td>
                            <select class="easyui-combobox" name="in_use[]" data-options="panelHeight:'auto',editable:false">
                                <option value="0">禁用</option>
                                <option value="1">启用</option>
                            </select>
                        </td>
                    </tr>
                    <?php } ?>
                    <?php for($i=1;$i<=3;$i++){ ?>
                    <tr>
                        <td style="padding:10px 0;">
                            单体压差不平[<?= $i; ?>]
                            <input type="hidden" name="alert_type[]" value="single_vol_diff" />
                            <input type="hidden" name="max_min[]" value="max" />
                        </td>
                        <td>
                            ≥
                            <input
                                class="easyui-textbox"
                                name="set_value[]"
                                validType="match[/^\d{1,6}(\.\d{0,3})?$/]"
                                invalidMessage="数值类型，最多三位小数，最大值999999.999！"
                            />
                            (V)
                            <input  type="hidden" name="interval_time[]" />
                        </td>
                        <td>
                            <select class="easyui-combobox" name="alert_level[]" data-options="panelHeight:'auto',editable:false,width:60">
                                <option value="5">5</option>
                                <option value="4">4</option>
                                <option value="3">3</option>
                                <option value="2">2</option>
                                <option value="1">1</option>
                            </select>
                        </td>
                        <td>
                            <select class="easyui-combobox" name="alert_dispose[]" data-options="panelHeight:'auto',editable:false">
                                <option value="0">不报警</option>
                                <option value="1">后台报警</option>
                                <option value="2">后台报警，短信报警</option>
                            </select>
                        </td>
                        <td>
                            <input
                                class="easyui-textbox"
                                name="alert_content[]"
                                style="width:300px;"
                                validType="length[255]"
                            />
                        </td>
                        <td>
                            <select class="easyui-combobox" name="in_use[]" data-options="panelHeight:'auto',editable:false">
                                <option value="0">禁用</option>
                                <option value="1">启用</option>
                            </select>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>  
        <div title="充放电电流" style="padding:20px;">
            <table style="text-align:center;width:100%">  
                <thead>
                    <tr>
                        <th data-options="field:'bjxm',width:100,align:'center'">报警项目</th>
                        <th data-options="field:'cxsz',width:140,align:'center'">参数设置</th>
                        <th data-options="field:'bjdj',width:100,align:'center'">报警等级</th>
                        <th data-options="field:'bjcl',width:180,align:'center'">报警处理</th>
                        <th data-options="field:'bjnr',width:220,align:'center'">报警内容</th>
                        <th data-options="field:'qyzt',width:100,align:'center'">启用状态</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for($i=1;$i<=2;$i++){ ?>
                    <tr>
                        <td style="padding:10px 0;">
                            放电电流过大[<?= $i; ?>]
                            <input type="hidden" name="alert_type[]" value="discharge_current" />
                            <input type="hidden" name="max_min[]" value="max" />
                        </td>
                        <td>
                            ≥
                            <input
                                class="easyui-textbox"
                                name="set_value[]"
                                validType="match[/^\d{1,6}(\.\d{0,3})?$/]"
                                invalidMessage="数值类型，最多三位小数，最大值999999.999！"
                            />
                            (A)
                            <input  type="hidden" name="interval_time[]" />
                        </td>
                        <td>
                            <select class="easyui-combobox" name="alert_level[]" data-options="panelHeight:'auto',editable:false,width:60">
                                <option value="5">5</option>
                                <option value="4">4</option>
                                <option value="3">3</option>
                                <option value="2">2</option>
                                <option value="1">1</option>
                            </select>
                        </td>
                        <td>
                            <select class="easyui-combobox" name="alert_dispose[]" data-options="panelHeight:'auto',editable:false">
                                <option value="0">不报警</option>
                                <option value="1">后台报警</option>
                                <option value="2">后台报警，短信报警</option>
                            </select>
                        </td>
                        <td>
                            <input
                                class="easyui-textbox"
                                name="alert_content[]"
                                style="width:300px;"
                                validType="length[255]"
                            />
                        </td>
                        <td>
                            <select class="easyui-combobox" name="in_use[]" data-options="panelHeight:'auto',editable:false">
                                <option value="0">禁用</option>
                                <option value="1">启用</option>
                            </select>
                        </td>
                    </tr>
                    <?php } ?>
                    <?php for($i=1;$i<=3;$i++){ ?>
                    <tr>
                        <td style="padding:10px 0;">
                            充电电流过大[<?= $i; ?>]
                            <input type="hidden" name="alert_type[]" value="charge_current" />
                            <input type="hidden" name="max_min[]" value="max" />
                        </td>
                        <td>
                            ≥
                            <input
                                class="easyui-textbox"
                                name="set_value[]"
                                validType="match[/^\d{1,6}(\.\d{0,3})?$/]"
                                invalidMessage="数值类型，最多三位小数，最大值999999.999！"
                            />
                            (A)
                            <input  type="hidden" name="interval_time[]" />
                        </td>
                        <td>
                            <select class="easyui-combobox" name="alert_level[]" data-options="panelHeight:'auto',editable:false,width:60">
                                <option value="5">5</option>
                                <option value="4">4</option>
                                <option value="3">3</option>
                                <option value="2">2</option>
                                <option value="1">1</option>
                            </select>
                        </td>
                        <td>
                            <select class="easyui-combobox" name="alert_dispose[]" data-options="panelHeight:'auto',editable:false">
                                <option value="0">不报警</option>
                                <option value="1">后台报警</option>
                                <option value="2">后台报警，短信报警</option>
                            </select>
                        </td>
                        <td>
                            <input
                                class="easyui-textbox"
                                name="alert_content[]"
                                style="width:300px;"
                                validType="length[255]"
                            />
                        </td>
                        <td>
                            <select class="easyui-combobox" name="in_use[]" data-options="panelHeight:'auto',editable:false">
                                <option value="0">禁用</option>
                                <option value="1">启用</option>
                            </select>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <div title="绝缘故障" style="padding:20px;">
            <table style="text-align:center;width:100%">  
                <thead>
                    <tr>
                        <th data-options="field:'bjxm',width:100,align:'center'">报警项目</th>
                        <th data-options="field:'cxsz',width:140,align:'center'">参数设置</th>
                        <th data-options="field:'bjdj',width:100,align:'center'">报警等级</th>
                        <th data-options="field:'bjcl',width:180,align:'center'">报警处理</th>
                        <th data-options="field:'bjnr',width:220,align:'center'">报警内容</th>
                        <th data-options="field:'qyzt',width:100,align:'center'">启用状态</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for($i=1;$i<=3;$i++){ ?>
                    <tr>
                        <td style="padding:10px 0;">
                            绝缘故障[<?= $i; ?>]
                            <input type="hidden" name="alert_type[]" value="insulation" />
                            <input type="hidden" name="max_min[]" value="min" />
                        </td>
                        <td>
                            ≤
                            <input
                                class="easyui-textbox"
                                name="set_value[]"
                                validType="match[/^\d{1,6}(\.\d{0,3})?$/]"
                                invalidMessage="数值类型，最多三位小数，最大值999999.999！"
                            />
                            (Ω/V)
                            <input  type="hidden" name="interval_time[]" />
                        </td>
                        <td>
                            <select class="easyui-combobox" name="alert_level[]" data-options="panelHeight:'auto',editable:false,width:60">
                                <option value="5">5</option>
                                <option value="4">4</option>
                                <option value="3">3</option>
                                <option value="2">2</option>
                                <option value="1">1</option>
                            </select>
                        </td>
                        <td>
                            <select class="easyui-combobox" name="alert_dispose[]" data-options="panelHeight:'auto',editable:false">
                                <option value="0">不报警</option>
                                <option value="1">后台报警</option>
                                <option value="2">后台报警，短信报警</option>
                            </select>
                        </td>
                        <td>
                            <input
                                class="easyui-textbox"
                                name="alert_content[]"
                                style="width:300px;"
                                validType="length[255]"
                            />
                        </td>
                        <td>
                            <select class="easyui-combobox" name="in_use[]" data-options="panelHeight:'auto',editable:false">
                                <option value="0">禁用</option>
                                <option value="1">启用</option>
                            </select>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <div title="电池温度" style="padding:20px;">
            <table style="text-align:center;width:100%">  
                <thead>
                    <tr>
                        <th data-options="field:'bjxm',width:100,align:'center'">报警项目</th>
                        <th data-options="field:'cxsz',width:140,align:'center'">参数设置</th>
                        <th data-options="field:'bjdj',width:100,align:'center'">报警等级</th>
                        <th data-options="field:'bjcl',width:180,align:'center'">报警处理</th>
                        <th data-options="field:'bjnr',width:220,align:'center'">报警内容</th>
                        <th data-options="field:'qyzt',width:100,align:'center'">启用状态</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for($i=1;$i<=4;$i++){ ?>
                    <tr>
                        <td style="padding:10px 0;">
                            电池包温度过高[<?= $i; ?>]
                            <input type="hidden" name="alert_type[]" value="package_tem" />
                            <input type="hidden" name="max_min[]" value="max" />
                        </td>
                        <td>
                            ≥
                            <input
                                class="easyui-textbox"
                                name="set_value[]"
                                validType="match[/^\d{1,6}(\.\d{0,3})?$/]"
                                invalidMessage="数值类型，最多三位小数，最大值999999.999！"
                            />
                            (℃)
                            <input  type="hidden" name="interval_time[]" />
                        </td>
                        <td>
                            <select class="easyui-combobox" name="alert_level[]" data-options="panelHeight:'auto',editable:false,width:60">
                                <option value="5">5</option>
                                <option value="4">4</option>
                                <option value="3">3</option>
                                <option value="2">2</option>
                                <option value="1">1</option>
                            </select>
                        </td>
                        <td>
                            <select class="easyui-combobox" name="alert_dispose[]" data-options="panelHeight:'auto',editable:false">
                                <option value="0">不报警</option>
                                <option value="1">后台报警</option>
                                <option value="2">后台报警，短信报警</option>
                            </select>
                        </td>
                        <td>
                            <input
                                class="easyui-textbox"
                                name="alert_content[]"
                                style="width:300px;"
                                validType="length[255]"
                            />
                        </td>
                        <td>
                            <select class="easyui-combobox" name="in_use[]" data-options="panelHeight:'auto',editable:false">
                                <option value="0">禁用</option>
                                <option value="1">启用</option>
                            </select>
                        </td>
                    </tr>
                    <?php } ?>
                    <?php for($i=1;$i<=1;$i++){ ?>
                    <tr>
                        <td style="padding:10px 0;">
                            电池包温度过低[<?= $i; ?>]
                            <input type="hidden" name="alert_type[]" value="package_tem" />
                            <input type="hidden" name="max_min[]" value="min" />
                        </td>
                        <td>
                            ≤
                            <input
                                class="easyui-textbox"
                                name="set_value[]"
                                validType="match[/^(-)?\d{1,6}(\.\d{0,3})?$/]"
                                invalidMessage="数值类型，最多三位小数，值范围-999999.999-999999.999！"
                            />
                            (℃)
                            <input  type="hidden" name="interval_time[]" />
                        </td>
                        <td>
                            <select class="easyui-combobox" name="alert_level[]" data-options="panelHeight:'auto',editable:false,width:60">
                                <option value="5">5</option>
                                <option value="4">4</option>
                                <option value="3">3</option>
                                <option value="2">2</option>
                                <option value="1">1</option>
                            </select>
                        </td>
                        <td>
                            <select class="easyui-combobox" name="alert_dispose[]" data-options="panelHeight:'auto',editable:false">
                                <option value="0">不报警</option>
                                <option value="1">后台报警</option>
                                <option value="2">后台报警，短信报警</option>
                            </select>
                        </td>
                        <td>
                            <input
                                class="easyui-textbox"
                                name="alert_content[]"
                                style="width:300px;"
                                validType="length[255]"
                            />
                        </td>
                        <td>
                            <select class="easyui-combobox" name="in_use[]" data-options="panelHeight:'auto',editable:false">
                                <option value="0">禁用</option>
                                <option value="1">启用</option>
                            </select>
                        </td>
                    </tr>
                    <?php } ?>
                    <?php for($i=1;$i<=4;$i++){ ?>
                    <tr>
                        <td style="padding:10px 0;">
                            温升过快[<?= $i; ?>]
                            <input type="hidden" name="alert_type[]" value="package_tem_change" />
                            <input type="hidden" name="max_min[]" value="max" />
                        </td>
                        <td>
                            ≥
                            <input
                                class="easyui-textbox"
                                name="set_value[]"
                                validType="match[/^\d{1,6}(\.\d{0,3})?$/]"
                                invalidMessage="数值类型，最多三位小数，最大值999999.999！"
                            />
                            (℃)
                            <input  type="hidden" name="interval_time[]" />
                        </td>
                        <td>
                            <select class="easyui-combobox" name="alert_level[]" data-options="panelHeight:'auto',editable:false,width:60">
                                <option value="5">5</option>
                                <option value="4">4</option>
                                <option value="3">3</option>
                                <option value="2">2</option>
                                <option value="1">1</option>
                            </select>
                        </td>
                        <td>
                            <select class="easyui-combobox" name="alert_dispose[]" data-options="panelHeight:'auto',editable:false">
                                <option value="0">不报警</option>
                                <option value="1">后台报警</option>
                                <option value="2">后台报警，短信报警</option>
                            </select>
                        </td>
                        <td>
                            <input
                                class="easyui-textbox"
                                name="alert_content[]"
                                style="width:300px;"
                                validType="length[255]"
                            />
                        </td>
                        <td>
                            <select class="easyui-combobox" name="in_use[]" data-options="panelHeight:'auto',editable:false">
                                <option value="0">禁用</option>
                                <option value="1">启用</option>
                            </select>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <div title="其他告警" style="padding:20px;">
            <table style="text-align:center;width:100%">  
                <thead>
                    <tr>
                        <th data-options="field:'bjxm',width:100,align:'center'">报警项目</th>
                        <th data-options="field:'cxsz',width:140,align:'center'">参数设置</th>
                        <th data-options="field:'bjdj',width:100,align:'center'">报警等级</th>
                        <th data-options="field:'bjcl',width:180,align:'center'">报警处理</th>
                        <th data-options="field:'bjnr',width:220,align:'center'">报警内容</th>
                        <th data-options="field:'qyzt',width:100,align:'center'">启用状态</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding:10px 0;">
                            BMS自检故障
                            <input type="hidden" name="alert_type[]" value="bms_auto_exam" />
                            <input type="hidden" name="max_min[]" value="max" />
                        </td>
                        <td>
                            <input type="hidden" name="set_value[]" value="0" />
                            ≥
                            <input
                                class="easyui-textbox"
                                name="interval_time[]"
                                validType="int"
                            />
                            (秒)
                        </td>
                        <td>
                            <select class="easyui-combobox" name="alert_level[]" data-options="panelHeight:'auto',editable:false,width:60">
                                <option value="5">5</option>
                                <option value="4">4</option>
                                <option value="3">3</option>
                                <option value="2">2</option>
                                <option value="1">1</option>
                            </select>
                        </td>
                        <td>
                            <select class="easyui-combobox" name="alert_dispose[]" data-options="panelHeight:'auto',editable:false">
                                <option value="0">不报警</option>
                                <option value="1">后台报警</option>
                                <option value="2">后台报警，短信报警</option>
                            </select>
                        </td>
                        <td>
                            <input
                                class="easyui-textbox"
                                name="alert_content[]"
                                style="width:300px;"
                                validType="length[255]"
                            />
                        </td>
                        <td>
                            <select class="easyui-combobox" name="in_use[]" data-options="panelHeight:'auto',editable:false">
                                <option value="0">禁用</option>
                                <option value="1">启用</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:10px 0;">
                            与充电桩通讯故障
                            <input type="hidden" name="alert_type[]" value="pole_communication" />
                            <input type="hidden" name="max_min[]" value="max" />
                        </td>
                        <td>
                            <input type="hidden" name="set_value[]" value="0" />
                            ≥
                            <input
                                class="easyui-textbox"
                                name="interval_time[]"
                                validType="int"
                            />
                            (秒)
                        </td>
                        <td>
                            <select class="easyui-combobox" name="alert_level[]" data-options="panelHeight:'auto',editable:false,width:60">
                                <option value="5">5</option>
                                <option value="4">4</option>
                                <option value="3">3</option>
                                <option value="2">2</option>
                                <option value="1">1</option>
                            </select>
                        </td>
                        <td>
                            <select class="easyui-combobox" name="alert_dispose[]" data-options="panelHeight:'auto',editable:false">
                                <option value="0">不报警</option>
                                <option value="1">后台报警</option>
                                <option value="2">后台报警，短信报警</option>
                            </select>
                        </td>
                        <td>
                            <input
                                class="easyui-textbox"
                                name="alert_content[]"
                                style="width:300px;"
                                validType="length[255]"
                            />
                        </td>
                        <td>
                            <select class="easyui-combobox" name="in_use[]" data-options="panelHeight:'auto',editable:false">
                                <option value="0">禁用</option>
                                <option value="1">启用</option>
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</form>