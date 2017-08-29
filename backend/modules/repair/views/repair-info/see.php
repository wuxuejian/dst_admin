<style>
    img:hover{
        transform: scale(3.0);
    }
</style>
<div class="easyui-panel" title="维修方案详情" style="padding:8px 4px;" data-options="
        iconCls: 'icon-tip',
        border: false
    ">
        <ul style="display:inline-block;float: left;">
            <li>审核状态：<li>
            <li>维修方案审核人：</li>
            <li>完工结算审核人：</li>
            <li>付款审核人：</li>
        </ul>
        <ul style="display:inline-block;">
            <li>
                <?php if($detail_data['check_status'] == 1){?>
                    <div style="background:yellow;">维修方案待审核</div>
                <?php }elseif($detail_data['check_status'] == 2){?>
                    <div style="background:red;">维修方案未通过</div>
                <?php }elseif($detail_data['check_status'] == 3){?>
                    <div style="background:lawngreen;">维修方案已通过</div>
                <?php }elseif($detail_data['check_status'] == 4){?>
                    <div style="background:red;">完工结算未通过</div>
                <?php }elseif($detail_data['check_status'] == 5){?>
                    <div style="background:lawngreen;">完工结算已通过</div>
                <?php }elseif($detail_data['check_status'] == 6){?>
                    <div style="background:red;">付款驳回</div>
                <?php }elseif($detail_data['check_status'] == 7){?>
                    <div style="background:lawngreen;">付款完结</div>
                <?php }elseif($detail_data['check_status'] == 8){?>
                    <div style="background:yellow;">完工结算待审核</div>
                <?php }else{?>
                    <div style="background:red;">审核状态异常</div>
                <?php }?>
            </li>
            <li>
                <?php echo empty($detail_data['project_human']) ? '未审核' : $detail_data['project_human'];?>
            </li>
            <li>
                <?php echo empty($detail_data['account_human']) ? '未审核' : $detail_data['account_human'];?>
            </li>
            <li>
                <?php echo empty($detail_data['money_human']) ? '未审核' : $detail_data['money_human'];?>
            </li>
        </ul>
    <ul style="display:inline-block;">
        <li>单据状态：<li>
        <li>审核意见：</li>
        <li>审核意见：</li>
        <li>审核意见：</li>
    </ul>
    <ul style="display:inline-block;">
        <li>
            <?php echo $detail_data['bill_status'] == 1 ? '正常' : '作废';?>
        <li>
        <li>
            <?php echo empty($detail_data['repair_note']) ? '-' : $detail_data['repair_note'];?>
        </li>
        <li>
            <?php echo empty($detail_data['finish_note']) ? '-' : $detail_data['finish_note'];?>
        </li>
        <li>
            <?php echo empty($detail_data['money_note']) ? '-' : $detail_data['money_note'];?>
        </li>
    </ul>
    <div style="clear:both;"></div>
<!--    <div style="padding-left: 40px;">-->
<!--        凭证备注：<input class="easyui-textbox" disabled="disabled" value="--><?php //echo $detail_data['money_note'];?><!--" data-options="multiline:true" name="money_note" style="width:600px;height:50px">-->
<!--    </div>-->
    <div>
        <h3>基本信息</h3>
        <table cellpadding="8" cellspacing="0">
            <tr>
                <td><div style="width:85px;text-align:right;">车牌号：</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:150px;"
                        name="car_brand"
                        value="<?php echo $detail_data['car_id'];?>"
                        disabled="disabled"
                    >
                </td>
                <td><div style="width:85px;text-align:right;">工单号：</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:150px;"
                        name="car_brand"
                        value="<?php echo $detail_data['order_number'];?>"
                        disabled="disabled"
                    >
                </td>
                <td><div style="width:85px;text-align:right;">维修厂类型：</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:150px;"
                        name="car_brand"
                        <?php if($detail_data['sale_factory'] == 1){?>
                            value="外部维修厂"
                        <?php }else{?>
                            value="内部维修厂"
                        <?php }?>
                        disabled="disabled"
                    >
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">车型：</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:150px;"
                        name="car_brand"
                        value="<?php echo $detail_data['che_type'];?>"
                        disabled="disabled"
                    >
                </td>
                <td><div style="width:85px;text-align:right;">车架号：</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:150px;"
                        name="car_brand"
                        value="<?php echo $detail_data['vehicle_dentification_number'];?>"
                        disabled="disabled"
                    >
                </td>
                <td><div style="width:85px;text-align:right;">机动车所有人：</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:150px;"
                        name="car_brand"
                        value="<?php echo $detail_data['owner_name'];?>"
                        disabled="disabled"
                    >
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">上次保养时间：</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:150px;"
                        name="car_brand"
                        value="<?php echo $detail_data['add_time'];?>"
                        disabled="disabled"
                    >
                </td>
                <td><div style="width:85px;text-align:right;">上次保养里程：</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:150px;"
                        name="car_brand"
                        value="<?php echo $detail_data['driving_mileage'];?>"
                        disabled="disabled"
                    >
                </td>
                <td><div style="width:85px;text-align:right;">送修人：</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:150px;"
                        name="car_brand"
                        value="<?php echo $detail_data['send_human'];?>"
                        disabled="disabled"
                    >
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">送修人电话：</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:150px;"
                        name="car_brand"
                        value="<?php echo $detail_data['send_phone'];?>"
                        disabled="disabled"
                    >
                </td>
                <td><div style="width:85px;text-align:right;">服务顾问：</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:150px;"
                        name="car_brand"
                        value="<?php echo $detail_data['service_human'];?>"
                        disabled="disabled"
                    >
                </td>
                <td><div style="width:85px;text-align:right;">服务顾问电话：</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:150px;"
                        name="car_brand"
                        value="<?php echo $detail_data['service_phone'];?>"
                        disabled="disabled"
                    >
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">是否拖车进厂：</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:150px;"
                        name="car_brand"
                        <?php if($detail_data['into_factory'] == 1){?>
                            value="是"
                        <?php }else{?>
                            value="否"
                        <?php }?>
                        disabled="disabled"
                    >
                </td>
                <td><div style="width:85px;text-align:right;">进厂时间：</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:150px;"
                        name="car_brand"
                        value="<?php echo $detail_data['into_time'];?>"
                        disabled="disabled"
                    >
                </td>
                <td><div style="width:85px;text-align:right;">预计出厂时间：</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:150px;"
                        name="car_brand"
                        value="<?php echo $detail_data['expect_time'];?>"
                        disabled="disabled"
                    >
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">进厂里程：</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:150px;"
                        name="car_brand"
                        value="<?php echo $detail_data['into_mile'];?>"
                        disabled="disabled"
                    >
                </td>
                <td><div style="width:85px;text-align:right;">SOC：</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:150px;"
                        name="car_brand"
                        value="<?php echo $detail_data['soc'];?>"
                        disabled="disabled"
                    >
                </td>
            </tr>
        </table>
        <div style="padding-left: 32px;padding-top: 10px;">
            故障描述：<input class="easyui-textbox" value="<?php echo $detail_data['error_note'];?>" disabled="disabled" data-options="multiline:true" name="money_note" style="width:600px;height:50px">
        </div>
        <br>
        <div style="padding-left: 56px;">
            备注：<input class="easyui-textbox" value="<?php echo $detail_data['info_note'];?>" disabled="disabled" data-options="multiline:true" name="money_note" style="width:600px;height:50px">
        </div>
        <h3>保养标准</h3>
            <table width="700px">
                <tr>
                    <th>
                        车型名称
                    </th>
                    <th>
                        保养类型
                    </th>
                    <th>
                        描述
                    </th>
                </tr>
                <tr>
                    <td>
                        <?php echo $detail_data['car_model_name'];?>
                    </td>
                    <td>
                        <?php echo $detail_data['maintain_type'];?>
                    </td>
                    <td>
                        <?php echo $detail_data['maintain_des'];?>
                    </td>
                </tr>
            </table>
        <h3>工时信息</h3>
            <table width="700px">
                <tr>
                    <th>
                        维修类型
                    </th>
                    <th>
                        维修项目名称
                    </th>
                    <th>
                        工时费金额
                    </th>
                    <th>
                        备注
                    </th>
                </tr>
                <?php if($detail_data['task_info']){?>
                    <?php foreach ($detail_data['task_info'] as $k=>$v){?>
                <tr>
                    <td>
                        <?php echo $v['0'];?>
                    </td>
                    <td>
                        <?php echo $v['1'];?>
                    </td>
                    <td>
                        <?php echo $v['2'];?>
                    </td>
                    <td>
                        <?php echo $v['3'];?>
                    </td>
                </tr>
                    <?php }?>
                <?php }?>
            </table>
        <h3>配件信息</h3>
            <table width="700px">
                <tr>
                    <th>
                        配件编号
                    </th>
                    <th>
                        配件名称
                    </th>
                    <th>
                        单价
                    </th>
                    <th>
                        数量
                    </th>
                    <th>
                        单位
                    </th>
                    <th>
                        配件金额
                    </th>
                    <th>
                        上次维修时间
                    </th>
                    <th>
                        上次维修里程
                    </th>
                    <th>
                        配件质保期
                    </th>
                </tr>
                <?php foreach ($repair_part as $k=>$v){?>
                <tr>
                    <td>
                        <?php echo $v['part_no'];?>
                    </td>
                    <td>
                        <?php echo $v['part_name'];?>
                    </td>
                    <td>
                        <?php echo $v['part_fee'];?>
                    </td>
                    <td>
                        <?php echo $v['part_number'];?>
                    </td>
                    <td>
                        <?php echo $v['part_unit'];?>
                    </td>
                    <td>
                        <?php echo $v['now_price'];?>
                    </td>
                    <td>
                        <?php echo $v['before_repair_time'];?>
                    </td>
                    <td>
                        <?php echo $v['before_repair_li'];?>
                    </td>
                    <td>
                        <?php echo 12;?>
                    </td>
                </tr>
                <?php }?>
            </table>
        <h3>车辆照片</h3>
            <span style="padding-left: 50px;">
                <img width="200" height="200" src="<?php echo $detail_data['repair_img_o'];?>" alt="未上传照片">
            </span>
            <span style="padding-left: 50px;">
                <img width="200" height="200" src="<?php echo $detail_data['repair_img_t'];?>" alt="未上传照片">
            </span>
        <h3>凭证照片</h3>
            <div style="padding-left: 50px;">
                <img width="200" height="200" src="<?php echo $detail_data['money_img'];?>" alt="未上传照片">
            </div>
    </div>
</div>