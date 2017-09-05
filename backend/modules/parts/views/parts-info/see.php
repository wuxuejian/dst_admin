<form id="search-form-parts-info-edit" method="post">
    <table cellpadding="8" cellspacing="0">
        <tr>
            <td colspan="4">
                <h2>
                    配件基础信息
                </h2>
            </td>
        </tr>
        <tr>
            <td><div style="width:85px;text-align:right;">原厂编码：</div></td>
            <td>
                <input
                    class="easyui-textbox"
                    style="width:150px;"
                    name="factory_code"
                    required="true"
                    validType="onlyNum"
                    value="<?php echo $data['factory_code'];?>"
                    disabled="disabled"

                >
                <input type="hidden" name="id" value="<?php echo $data['id'];?>">
            </td>
            <td><div style="width:85px;text-align:right;">配件名称：</div></td>
            <td>
                <input
                    class="easyui-textbox"
                    style="width:150px;"
                    name="parts_name"
                    required="true"
                    validType="length[100]"
                    value="<?php echo $data['parts_name'];?>"
                    disabled="disabled"

                >
            </td>
        </tr>
        <tr>
            <td><div style="width:85px;text-align:right;">单位：</div></td>
            <td>
                <input style="width:150px;" class="easyui-textbox" type="text" name="unit" value="<?php echo $data['unit'];?>" disabled="disabled">
            </td>
            <td><div style="width:85px;text-align:right;">规格：</div></td>
            <td>
                <input
                    class="easyui-textbox"
                    style="width:150px;"
                    name="size"
                    validType="length[100]"
                    value="<?php echo $data['size'];?>"
                    disabled="disabled"
                >
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <h2>
                    配件详细信息
                </h2>
            </td>
        </tr>
        <tr>
            <td><div style="width:85px;text-align:right;">三包期(月)：</div></td>
            <td>
                <input
                    class="easyui-textbox"
                    style="width:150px;"
                    name="three_date"
                    validType="Num"
                    value="<?php echo $data['three_date'];?>"
                    disabled="disabled"
                >
            </td>
            <td><div style="width:85px;text-align:right;">保质期(月)：</div></td>
            <td>
                <input
                    class="easyui-textbox"
                    style="width:150px;"
                    name="work_date"
                    validType="Num"
                    value="<?php echo $data['work_date'];?>"
                    disabled="disabled"
                >
            </td>
        </tr>
        <tr>
            <td><div style="width:85px;text-align:right;">采购指导价：</div></td>
            <td>
                <input
                    class="easyui-textbox"
                    style="width:150px;"
                    name="shop_price"
                    validType="onlyNum"
                    value="<?php echo $data['shop_price'];?>"
                    disabled="disabled"
                >
            </td>
            <td><div style="width:85px;text-align:right;">出库指导价：</div></td>
            <td>
                <input
                    class="easyui-textbox"
                    style="width:150px;"
                    name="out_price"
                    validType="onlyNum"
                    value="<?php echo $data['out_price'];?>"
                    disabled="disabled"
                >
            </td>
        </tr>
    </table>
    <h2>
        适用车型
    </h2>
    <div style="background: #000">
        <div style="display: inline-block; float: left; position: relative; padding: 10px; background: #ddd; border-radius: 4px; margin: 0 0 10px 10px;">
            <span><?php echo $data['car_type'];?></span>
            <input name="che_type[]" style="display: none;" value="<?php echo $data['car_type'];?>">
        </div>
    </div>
</form>
<script>
    $(function(){
        $('#edit').click(function(){
            $('#edit_select').css("display","block");
        })
        $("#edit_select").change(function(){
            var $options=$('#edit_select option:selected');
            var edithtml = "<div style=\"display: inline-block; float: left; position: relative; padding: 10px; background: #ddd; border-radius: 4px; margin: 0 0 10px 10px;\">\n" +
                "            <span>"+$options.text()+"</span>\n" +
                "            <img onclick=\"javascript:del(this);\" class=\"delet\" style=\"position: absolute; width: 16px; height: 16px; right: -8px; top:-8px;\" src=\"./jquery-easyui-1.4.3/themes/icons/cancel.png\">\n" +
                "            <input name=\"che_type[]\" style=\"display: none;\" value="+$options.val()+">\n" +
                "        </div>";
            $('#append_edit').before(edithtml);
            $('#edit_select').css("display","none");
            $('#edit_select').val('');
        });
    })
    function del(obj)
    {
        $(obj).parent('div').remove()
    }
</script>