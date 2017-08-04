<div class="easyui-panel" title="配件种类" style="padding:8px 4px;" data-options="
        iconCls: 'icon-tip',
        border: false
    ">
    <form id="search-form-parts-kind-edit" method="post">
        <table cellpadding="8" cellspacing="0">
            <tr>
                <td><div style="width:85px;text-align:right;">配件类别：</div></td>
                <td>
                    <?php if($all['data'][0]['parents_id'] == '0'){?>
                        <input
                                class="easyui-textbox"
                                style="width:160px;"
                                name="parts_name"
                                value="<?php echo $all['data'][0]['parts_name'];?>"
                                required="true"
                                validType="length[100]"
                        >
                    <?php }else{?>
                        <select name="parts_name" style="width: 160px;">
                            <option
                                    value="<?php echo $all['data'][0]['parents_id'];?>"
                            >
                                <?php echo $all['data'][0]['new_name'];?>
                            </option>
                            <?php foreach ($all['parts_name'] as $k=>$v){?>
                                <option value="<?php echo $v['id'];?>"><?php echo $v['parts_name'];?></option>
                            <?php }?>
                        </select>
                    <?php }?>
                </td>
                <td><div style="width:85px;text-align:right;">配件种类：</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="kind_name"
                        <?php if($all['data'][0]['parents_id'] == '0'){ ?>
                            value=" "
                        <?php }else{?>
                            value="<?php echo $all['data'][0]['parts_name'];?>"
                        <?php }?>
                        required="true"
                        validType="length[100]"
                        <?php if($all['data'][0]['parents_id'] == '0'){ ?>
                            <?php echo 'disabled="disabled"'; ?>
                        <?php }?>
                    >
                </td>
                <td><div style="width:85px;text-align:right;">备注：</div></td>
                <td>
                    <input
                            class="easyui-textbox"
                            style="width:160px;"
                            name="note"
                            value="<?php echo $all['data'][0]['note'];?>"
                            validType="length[150]"
                    >
                </td>
            </tr>
        </table>
        <div>
            <input type="hidden" name="edit_id" value="<?php echo $all['data'][0]['id'];?>">
        </div>
    </form>
</div>