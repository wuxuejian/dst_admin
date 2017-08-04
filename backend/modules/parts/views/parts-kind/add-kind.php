<div class="easyui-panel" title="配件种类" style="padding:8px 4px;" data-options="
        iconCls: 'icon-tip',
        border: false
    ">
    <form id="search-form-parts-kind-add-kind" method="post">
        <table cellpadding="8" cellspacing="0">
            <tr>
                <td><div style="width:85px;text-align:right;">配件类别：</div></td>
                <td>
                    <select name="parts_name" style="width: 160px;">
                        <option value="0">请选择</option>
                        <?php foreach ($data as $k=>$v){?>
                        <option value="<?php echo $v['id'];?>"><?php echo $v['parts_name'];?></option>
                        <?php }?>
                    </select>
                </td>
                <td><div style="width:85px;text-align:right;">配件种类：</div></td>
                <td>
                    <input
                            class="easyui-textbox"
                            style="width:160px;"
                            name="kind_name"
                            required="true"
                            validType="length[100]"
                    >
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">备注：</div></td>
                <td>
                    <input
                            class="easyui-textbox"
                            style="width:160px;"
                            name="note"
                            validType="length[150]"
                    >
                </td>
            </tr>
        </table>
    </form>
</div>