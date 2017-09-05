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
                        data-options="prompt:'请输入',"
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
                        data-options="prompt:'请输入',"
                >
            </td>
        </tr>
        <tr>
            <td><div style="width:85px;text-align:right;">单位：</div></td>
            <td>
                <select
                        class="easyui-combobox"s
                        style="width:150px;"
                        id="unit"
                        name="unit"
                        editable="true"
                        data-options="panelHeight:'300'"
                >
                    <option value="<?php echo $data['unit'];?>"><?php echo $data['unit'];?></option>
                    <?php
                    $unit = '只,盒,件,长,桶,米,片,瓶,把,副,块,升,根,公斤,包,个,罐,对,箱,台,斤,车,支,双,套,条,卷,提,颗,次,付,面,辆';
                    $unit = explode(',', $unit);
                    ?>
                    <?php foreach ($unit as $v){?>
                        <option value="<?php echo $v;?>"><?php echo $v;?></option>
                    <?php }?>
                </select>
            </td>
            <td><div style="width:85px;text-align:right;">规格：</div></td>
            <td>
                <input
                        class="easyui-textbox"
                        style="width:150px;"
                        name="size"
                        validType="length[100]"
                        value="<?php echo $data['size'];?>"
                        data-options="prompt:'请输入'"
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
                        data-options="prompt:'请输入',"
                        value="<?php echo $data['three_date'];?>"
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
                        data-options="prompt:'请输入',"
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
                        validType="moneyNum"
                        value="<?php echo $data['shop_price'];?>"
                        data-options="prompt:'请输入',"
                >
            </td>
            <td><div style="width:85px;text-align:right;">出库指导价：</div></td>
            <td>
                <input
                        class="easyui-textbox"
                        style="width:150px;"
                        name="out_price"
                        validType="moneyNum"
                        value="<?php echo $data['out_price'];?>"
                        data-options="prompt:'请输入',"
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
            <img onclick="javascript:del(this);" class="delet" style="position: absolute; width: 16px; height: 16px; right: -8px; top:-8px;" src="./jquery-easyui-1.4.3/themes/icons/cancel.png">
            <input name="che_type[]" style="display: none;" value="<?php echo $data['car_type'];?>">
        </div>
        <div id="append_edit" style="display: inline-block; float: left; position: relative; margin-left: 10px;">
            <span id="edit" style="position: absolute; left: 0; top:0;z-index:10; padding:9px; border:1px solid #ddd; border-radius: 4px;">+</span>
            <select id="edit_select" style="display: none; position: absolute; left: 0; top:0;z-index:11">
                <option></option>
                <option value="666">111</option>
            </select>
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
<script>
    //新增验证规则
    $.extend($.fn.validatebox.defaults.rules, {
        onlyNum:{
            validator:function(value,param){
                var reg = /^[0-9a-zA-Z]*$/g;
                return reg.test(value);
            },
            message:  '只能输入数字或字母！'
        },
        Num:{
            validator:function(value,param){
                var reg = /^[0-9]*$/g;
                return reg.test(value);
            },
            message:  '只能输入数字！'
        },
        moneyNum:{
            validator:function(value,param){
                var reg = /^[0-9.]*$/g;
                return reg.test(value);
            },
            message:  '只能输入数字！'
        },
    });
</script>