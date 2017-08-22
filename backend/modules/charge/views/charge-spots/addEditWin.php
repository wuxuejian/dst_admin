<form id="ChargeSpotsIndex_addEditWin_form" method="post" style="padding:5px 0px;">
    <table cellpadding="5" cellspacing="0" style="width:100%;" border=0>
        <tr>
            <td align="right">所属电站</td>
            <td colspan="5">
                <input id="ChargeSpotsIndex_addEditWin_chooseStation" name="station_id" style="width:160px;"  />
            </td>
        </tr>
        <tr>
            <td align="right">逻辑地址</td>
            <td colspan="5">
                <input class="easyui-numberbox" name="logic_addr" style="width:160px;"  />
                <span style="padding-left:10px;color:#FF7070">* 逻辑地址确保电桩与前置机数据库中某设备地址相对应</span>
            </td>
        </tr>
        <tr>
            <td align="right">simID</td>
            <td colspan="5">
                <input class="easyui-textbox" name="sim" style="width:160px;"  />
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
                                    $('#addEditWin_chargeGunNums').numberbox('setValue',1);
                                }else{
                                    $('#addEditWin_chargeGunNums').numberbox('setValue',2);
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
            <td>
                <input class="easyui-datebox" name="purchase_date" style="width:160px;" validType="date"  />
            </td>
			<td align="right">通讯方式</td>
            <td>
				<select class="easyui-combobox" name="communication_way" style="width:160px;" data-options="panelHeight:'auto',required:false"  editable=false >
                     <option value="0"></option>
					 <option value="1">局域网</option>
					 <option value="2">GPRS</option>
                </select>
            </td>
        </tr>
        <tr hidden>
            <td align="right" valign="top" rowspan="2">规格参数</td>
            <td colspan="5">
                &nbsp;&nbsp;电枪数量
                <input class="easyui-numberbox" name="charge_gun_nums" id="addEditWin_chargeGunNums" style="width:80px;" value="1" editable="false"  />个&nbsp;&nbsp;&nbsp;&nbsp;
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

<script>
    // 初始化【选择所属电站】combogrid
    $('#ChargeSpotsIndex_addEditWin_chooseStation').combogrid({
        panelWidth: 500,
        panelHeight: 200,
        required: true,
        missingMessage: '请从下拉列表里选择电站！',
        onHidePanel:function(){
            var _combogrid = $(this);
            var value = _combogrid.combogrid('getValue');
            var textbox = _combogrid.combogrid('textbox');
            var text = textbox.val();
            var rows = _combogrid.combogrid('grid').datagrid('getSelections');
            if(text && rows.length < 1 && value == text){
                $.messager.show({
                    title: '无效电站',
                    msg:'【' + text + '】不是有效电站！请重新检索并选择一个电站！'
                });
                _combogrid.combogrid('clear');
            }
        },
        delay: 800,
        mode:'remote',
        idField: 'cs_id',
        textField: 'cs_name',
        url: "<?php echo yii::$app->urlManager->createUrl(['charge/combogrid/get-station']); ?>",
        method: 'get',
        scrollbarSize:0,
        rownumbers: true,
        pagination: true,
        pageSize: 10,
        pageList: [10,20,30],
        fitColumns: true,
        columns: [[
            {field:'cs_id',title:'电站ID',width:35,align:'center',hidden:true},
            {field:'cs_code',title:'电站编号',width:80,align:'center'},
            {field:'cs_name',title:'电站名称',width:130,halign:'center'},
            {field:'cs_address',title:'电站位置',width:200,halign:'center'}
        ]]
    });

    // 加载旧表单数据
	var myData = <?php echo json_encode($myData); ?>;
	switch(myData.action) {
		case 'edit':
			var oldData = myData.chargeSpotsInfo;
			$('#ChargeSpotsIndex_addEditWin_form').form('load',oldData);
            // 查旧电站以赋值显示text,因为combogrid远程查询第一页不一定存在该电站而显示为id
            var stationId = {stationId: oldData.station_id};
            $('#ChargeSpotsIndex_addEditWin_chooseStation').combogrid('grid').datagrid('load',stationId);
            break;
	}
</script>