<div class="easyui-tabs" data-options="fit:true,border:false">
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
                <?php foreach($conditionItemDeal['total_vol_max'] as $key=>$val){ ?>
                <tr>
                    <td style="padding:10px 0;">
                        总电压过高[<?= $key+1; ?>]
                    </td>
                    <td>
                        ≥
                        <?= $val['set_value']; ?>
                        x N (V)
                    </td>
                    <td><?= $val['alert_level']; ?></td>
                    <td>
                        <?php
                            switch ($val['alert_dispose']) {
                                case 0:
                                    echo '不报警';
                                    break;
                                case 1:
                                    echo '后台报警';
                                    break;
                                default:
                                    echo '后台报警，短信报警';
                                    break;
                            }
                        ?>
                    </td>
                    <td><?= $val['alert_content']; ?></td>
                    <td>
                        <?php
                            switch ($val['in_use']) {
                                case 0:
                                    echo '禁用';
                                    break;
                                default:
                                    echo '启用';
                                    break;
                            }
                        ?>
                    </td>
                </tr>
                <?php } ?>
                <?php foreach($conditionItemDeal['total_vol_min'] as $key=>$val){ ?>
                <tr>
                    <td style="padding:10px 0;">
                        总电压过低[<?= $key+1 ?>]
                    </td>
                    <td>
                        ≤
                        <?= $val['set_value']; ?>
                        x N (V)
                    </td>
                    <td><?= $val['alert_level']; ?></td>
                    <td>
                        <?php
                            switch ($val['alert_dispose']) {
                                case 0:
                                    echo '不报警';
                                    break;
                                case 1:
                                    echo '后台报警';
                                    break;
                                default:
                                    echo '后台报警，短信报警';
                                    break;
                            }
                        ?>
                    </td>
                    <td><?= $val['alert_content']; ?></td>
                    <td>
                        <?php
                            switch ($val['in_use']) {
                                case 0:
                                    echo '禁用';
                                    break;
                                default:
                                    echo '启用';
                                    break;
                            }
                        ?>
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
                <?php foreach($conditionItemDeal['single_vol_max'] as $key=>$val){ ?>
                <tr>
                    <td style="padding:10px 0;">
                        单体电压过高[<?= $key+1; ?>]
                    </td>
                    <td>
                        ≥
                        <?= $val['set_value']; ?>
                        (V)
                    </td>
                    <td><?= $val['alert_level']; ?></td>
                    <td>
                        <?php
                            switch ($val['alert_dispose']) {
                                case 0:
                                    echo '不报警';
                                    break;
                                case 1:
                                    echo '后台报警';
                                    break;
                                default:
                                    echo '后台报警，短信报警';
                                    break;
                            }
                        ?>
                    </td>
                    <td><?= $val['alert_content']; ?></td>
                    <td>
                        <?php
                            switch ($val['in_use']) {
                                case 0:
                                    echo '禁用';
                                    break;
                                default:
                                    echo '启用';
                                    break;
                            }
                        ?>
                    </td>
                </tr>
                <?php } ?>
                <?php foreach($conditionItemDeal['single_vol_min'] as $key=>$val){ ?>
                <tr>
                    <td style="padding:10px 0;">
                        单体电压过低[<?= $key+1; ?>]
                    </td>
                    <td>
                        ≤
                        <?= $val['set_value']; ?>
                        (V)
                    </td>
                    <td><?= $val['alert_level']; ?></td>
                    <td>
                        <?php
                            switch ($val['alert_dispose']) {
                                case 0:
                                    echo '不报警';
                                    break;
                                case 1:
                                    echo '后台报警';
                                    break;
                                default:
                                    echo '后台报警，短信报警';
                                    break;
                            }
                        ?>
                    </td>
                    <td><?= $val['alert_content']; ?></td>
                    <td>
                        <?php
                            switch ($val['in_use']) {
                                case 0:
                                    echo '禁用';
                                    break;
                                default:
                                    echo '启用';
                                    break;
                            }
                        ?>
                    </td>
                </tr>
                <?php } ?>
                <?php foreach($conditionItemDeal['single_vol_diff_max'] as $key=>$val){ ?>
                <tr>
                    <td style="padding:10px 0;">
                        单体压差不平[<?= $key+1; ?>]
                    </td>
                    <td>
                        ≥
                        <?= $val['set_value']; ?>
                        (V)
                    </td>
                    <td><?= $val['alert_level']; ?></td>
                    <td>
                        <?php
                            switch ($val['alert_dispose']) {
                                case 0:
                                    echo '不报警';
                                    break;
                                case 1:
                                    echo '后台报警';
                                    break;
                                default:
                                    echo '后台报警，短信报警';
                                    break;
                            }
                        ?>
                    </td>
                    <td><?= $val['alert_content']; ?></td>
                    <td>
                        <?php
                            switch ($val['in_use']) {
                                case 0:
                                    echo '禁用';
                                    break;
                                default:
                                    echo '启用';
                                    break;
                            }
                        ?>
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
                <?php foreach($conditionItemDeal['discharge_current_max'] as $key=>$val){ ?>
                <tr>
                    <td style="padding:10px 0;">
                        放电电流过大[<?= $key+1; ?>]
                    </td>
                    <td>
                        ≥
                        <?= $val['set_value']; ?>
                        (A)
                    </td>
                    <td><?= $val['alert_level']; ?></td>
                    <td>
                        <?php
                            switch ($val['alert_dispose']) {
                                case 0:
                                    echo '不报警';
                                    break;
                                case 1:
                                    echo '后台报警';
                                    break;
                                default:
                                    echo '后台报警，短信报警';
                                    break;
                            }
                        ?>
                    </td>
                    <td><?= $val['alert_content']; ?></td>
                    <td>
                        <?php
                            switch ($val['in_use']) {
                                case 0:
                                    echo '禁用';
                                    break;
                                default:
                                    echo '启用';
                                    break;
                            }
                        ?>
                    </td>
                </tr>
                <?php } ?>
                <?php foreach($conditionItemDeal['charge_current_max'] as $key=>$val){ ?>
                <tr>
                    <td style="padding:10px 0;">
                        充电电流过大[<?= $key+1; ?>]
                    </td>
                    <td>
                        ≥
                        <?= $val['set_value']; ?>
                        (A)
                    </td>
                    <td><?= $val['alert_level']; ?></td>
                    <td>
                        <?php
                            switch ($val['alert_dispose']) {
                                case 0:
                                    echo '不报警';
                                    break;
                                case 1:
                                    echo '后台报警';
                                    break;
                                default:
                                    echo '后台报警，短信报警';
                                    break;
                            }
                        ?>
                    </td>
                    <td><?= $val['alert_content']; ?></td>
                    <td>
                        <?php
                            switch ($val['in_use']) {
                                case 0:
                                    echo '禁用';
                                    break;
                                default:
                                    echo '启用';
                                    break;
                            }
                        ?>
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
                <?php foreach($conditionItemDeal['insulation_min'] as $key=>$val){ ?>
                <tr>
                    <td style="padding:10px 0;">
                        绝缘故障[<?= $key+1; ?>]
                    </td>
                    <td>
                        ≤
                        <?= $val['set_value']; ?>
                        (Ω/V)
                    </td>
                    <td><?= $val['alert_level']; ?></td>
                    <td>
                        <?php
                            switch ($val['alert_dispose']) {
                                case 0:
                                    echo '不报警';
                                    break;
                                case 1:
                                    echo '后台报警';
                                    break;
                                default:
                                    echo '后台报警，短信报警';
                                    break;
                            }
                        ?>
                    </td>
                    <td><?= $val['alert_content']; ?></td>
                    <td>
                        <?php
                            switch ($val['in_use']) {
                                case 0:
                                    echo '禁用';
                                    break;
                                default:
                                    echo '启用';
                                    break;
                            }
                        ?>
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
                <?php foreach($conditionItemDeal['package_tem_max'] as $key=>$val){ ?>
                <tr>
                    <td style="padding:10px 0;">
                        电池包温度过高[<?= $key+1; ?>]
                    </td>
                    <td>
                        ≥
                        <?= $val['set_value']; ?>
                        (℃)
                    </td>
                    <td><?= $val['alert_level']; ?></td>
                    <td>
                        <?php
                            switch ($val['alert_dispose']) {
                                case 0:
                                    echo '不报警';
                                    break;
                                case 1:
                                    echo '后台报警';
                                    break;
                                default:
                                    echo '后台报警，短信报警';
                                    break;
                            }
                        ?>
                    </td>
                    <td><?= $val['alert_content']; ?></td>
                    <td>
                        <?php
                            switch ($val['in_use']) {
                                case 0:
                                    echo '禁用';
                                    break;
                                default:
                                    echo '启用';
                                    break;
                            }
                        ?>
                    </td>
                </tr>
                <?php } ?>
                <?php foreach($conditionItemDeal['package_tem_min'] as $key=>$val){ ?>
                <tr>
                    <td style="padding:10px 0;">
                        电池包温度过低[<?= $key+1; ?>]
                    </td>
                    <td>
                        ≤
                        <?= $val['set_value']; ?>
                        (℃)
                    </td>
                    <td><?= $val['alert_level']; ?></td>
                    <td>
                        <?php
                            switch ($val['alert_dispose']) {
                                case 0:
                                    echo '不报警';
                                    break;
                                case 1:
                                    echo '后台报警';
                                    break;
                                default:
                                    echo '后台报警，短信报警';
                                    break;
                            }
                        ?>
                    </td>
                    <td><?= $val['alert_content']; ?></td>
                    <td>
                        <?php
                            switch ($val['in_use']) {
                                case 0:
                                    echo '禁用';
                                    break;
                                default:
                                    echo '启用';
                                    break;
                            }
                        ?>
                    </td>
                </tr>
                <?php } ?>
                <?php foreach($conditionItemDeal['package_tem_change_max'] as $key=>$val){ ?>
                <tr>
                    <td style="padding:10px 0;">
                        温升过快[<?= $key+1; ?>]
                    </td>
                    <td>
                        ≥
                        <?= $val['set_value']; ?>
                        (℃)
                    </td>
                    <td><?= $val['alert_level']; ?></td>
                    <td>
                        <?php
                            switch ($val['alert_dispose']) {
                                case 0:
                                    echo '不报警';
                                    break;
                                case 1:
                                    echo '后台报警';
                                    break;
                                default:
                                    echo '后台报警，短信报警';
                                    break;
                            }
                        ?>
                    </td>
                    <td><?= $val['alert_content']; ?></td>
                    <td>
                        <?php
                            switch ($val['in_use']) {
                                case 0:
                                    echo '禁用';
                                    break;
                                default:
                                    echo '启用';
                                    break;
                            }
                        ?>
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
                    </td>
                    <td>
                        ≥
                        <?= $conditionItemDeal['bms_auto_exam_max'][0]['interval_time']; ?>
                        (秒)
                    </td>
                    <td><?= $conditionItemDeal['bms_auto_exam_max'][0]['alert_level']; ?></td>
                    <td>
                        <?php
                            switch ($conditionItemDeal['bms_auto_exam_max'][0]['alert_dispose']) {
                                case 0:
                                    echo '不报警';
                                    break;
                                case 1:
                                    echo '后台报警';
                                    break;
                                default:
                                    echo '后台报警，短信报警';
                                    break;
                            }
                        ?>
                    </td>
                    <td><?= $conditionItemDeal['bms_auto_exam_max'][0]['alert_content']; ?></td>
                    <td>
                        <?php
                            switch ($conditionItemDeal['bms_auto_exam_max'][0]['in_use']) {
                                case 0:
                                    echo '禁用';
                                    break;
                                default:
                                    echo '启用';
                                    break;
                            }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td style="padding:10px 0;">
                        与充电桩通讯故障
                    </td>
                    <td>
                        ≥
                        <?= $conditionItemDeal['pole_communication_max'][0]['interval_time']; ?>
                        (秒)
                    </td>
                    <td><?= $conditionItemDeal['pole_communication_max'][0]['alert_level']; ?></td>
                    <td>
                        <?php
                            switch ($conditionItemDeal['pole_communication_max'][0]['alert_dispose']) {
                                case 0:
                                    echo '不报警';
                                    break;
                                case 1:
                                    echo '后台报警';
                                    break;
                                default:
                                    echo '后台报警，短信报警';
                                    break;
                            }
                        ?>
                    </td>
                    <td><?= $conditionItemDeal['pole_communication_max'][0]['alert_content']; ?></td>
                    <td>
                        <?php
                            switch ($conditionItemDeal['pole_communication_max'][0]['in_use']) {
                                case 0:
                                    echo '禁用';
                                    break;
                                default:
                                    echo '启用';
                                    break;
                            }
                        ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>