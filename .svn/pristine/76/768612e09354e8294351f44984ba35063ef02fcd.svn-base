<form id="easyui_form_polemonitor_alert_set_param" style="padding:14px;">
    <table style="text-align:center;width:100%">  
        <thead>
            <tr>
                <th data-options="field:'bjxm',width:100,align:'center'">报警项目</th>
                <th data-options="field:'bjdj',width:100,align:'center'">报警等级</th>
                <th data-options="field:'bjcl',width:180,align:'center'">报警处理</th>
                <th data-options="field:'bjnr',width:220,align:'center'">报警内容</th>
                <th data-options="field:'qyzt',width:100,align:'center'">启用状态</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach($alertProject as $val){
            ?>
            <tr>
                <td style="padding:10px 0;">
                    <?php echo $val['name']; ?>
                    <input type="hidden" name="id[]" value="<?php echo $val['id']; ?>" />
                </td>
                <td>
                    <select class="easyui-combobox" name="alert_level[]" data-options="panelHeight:'auto',editable:false,width:60,value:<?php echo $val['alert_level']; ?>">
                        <option value="5">5</option>
                        <option value="4">4</option>
                        <option value="3">3</option>
                        <option value="2">2</option>
                        <option value="1">1</option>
                    </select>
                </td>
                <td>
                    <select class="easyui-combobox" name="alert_dispose[]" data-options="panelHeight:'auto',editable:false,value:<?php echo $val['alert_dispose']; ?>">
                        <option value="0">不报警</option>
                        <option value="1">后台报警</option>
                        <option value="2">后台报警，短信报警</option>
                    </select>
                </td>
                <td>
                    <input
                        class="easyui-textbox"
                        name="alert_content[]"
                        style="width:340px;"
                        validType="length[255]"
                        value="<?php echo $val['alert_content']; ?>"
                    />
                </td>
                <td>
                    <select class="easyui-combobox" name="in_use[]" data-options="panelHeight:'auto',editable:false,value:<?php echo $val['in_use']; ?>">
                        <option value="0">禁用</option>
                        <option value="1">启用</option>
                    </select>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>