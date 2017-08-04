<div class="data-search-form">
<form action="#" method="post" id="edit-feng">
        <table cellpadding="8" cellspacing="0">
            <tr>
                <td><div style="width:85px;text-align:right;">大区：</div></td>
                <td>
                    <input class="easyui-textbox" type="text" class="region" name="region" disabled="disabled"
                           value="<?php if($parts_in['region'] == 1){?><?php echo '华南';?><?php }else if ($parts_in['region'] == 2){?><?php echo '华北';?><?php }else if ($parts_in['region'] == 3){?><?php echo '华东';?>
<?php }else if ($parts_in['region'] == 4){?><?php echo '西南';?><?php }else if ($parts_in['region'] == 5){?><?php echo '华中';?><?php }else {?><?php echo '失踪了';?><?php }?>"/>
                </td>
                <td><div style="width:85px;text-align:right;">运营公司：</div></td>
                <td>
                    <input class="easyui-textbox" type="text" class="operating_company_id" name="operating_company_id" disabled="disabled" value="<?php echo $parts_in['company_name'];?>"/>
                </td>
                <td><div style="width:85px;text-align:right;">仓储地点：</div></td>
                <td>
                    <input class="easyui-textbox" type="text" class="warehouse_address_feng" disabled="disabled" name="warehouse_address_feng" value="<?php echo $parts_in['site_name'];?>">
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">车辆品牌：</div></td>
                <td>
                    <input class="easyui-textbox" type="text" class="car_brand" name="car_brand" disabled="disabled" value="<?php echo $parts_info['parts_car_brand'];?>"/>
                    <input type="hidden" class="parts_id" name="parts_id" value="<?php echo $parts_info['parts_id'];?>">
                    <input type="hidden" class="parts_in_id" name="parts_in_id" value="<?php echo $parts_in['insert_id'];?>">
                </td>
                <td><div style="width:85px;text-align:right;">配件类别：</div></td>
                <td>
                    <input class="easyui-textbox" type="text" class="parts_type" name="parts_type" disabled="disabled" value="<?php echo $parts_info['parent_name'];?>"/>
                </td>
                <td><div style="width:85px;text-align:right;">配件种类：</div></td>
                <td>
                    <input class="easyui-textbox" type="text" class="parts_kind" name="parts_kind" disabled="disabled" value="<?php echo $parts_info['son_name'];?>"/>
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">配件名称：</div></td>
                <td>
                    <input class="easyui-textbox" type="text" class="parts_name" name="parts_name" disabled="disabled" value="<?php echo $parts_info['parts_name'];?>"/>
                </td>
                <td><div style="width:85px;text-align:right;">配件品牌：</div></td>
                <td>
                    <input class="easyui-textbox" type="text" class="parts_brand" name="parts_brand" disabled="disabled" value="<?php echo $parts_info['parts_brand'];?>"/>
                </td>
                <td><div style="width:85px;text-align:right;">厂家配件编码：</div></td>
                <td>
                    <input class="easyui-textbox" type="text" class="vender_code" name="vender_code" disabled="disabled" value="<?php echo $parts_info['vender_code'];?>"/>
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">我方配件编码：</div></td>
                <td>
                    <input class="easyui-textbox" type="text" class="dst_code" name="dst_code" disabled="disabled" value="<?php echo $parts_info['dst_code'];?>" />
                </td>
                <td><div style="width:85px;text-align:right;">单位：</div></td>
                <td>
                    <input class="easyui-textbox" type="text" class="unit" name="unit" disabled="disabled" value="<?php echo $parts_info['unit'];?>" />
                </td>
                <td><div style="width:85px;text-align:right;">主机厂参考价：</div></td>
                <td>
                    <input class="easyui-textbox" type="text"  class="main_engine_price" name="main_engine_price" disabled="disabled" value="<?php echo $parts_info['main_engine_price'];?>" />
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">采购单价：</div></td>
                <td>
                    <input type="text" id="now_price_edit" placeholder="必填" class="shop_price" name="shop_price" value="<?php echo $parts_in['shop_price'];?>" validType="number"/>
                </td>
                <td><div style="width:85px;text-align:right;">出库单价：</div></td>
                <td>
                    <input type="text" id="new_price_edit" class="out_price" name="out_price" disabled="disabled" value="<?php echo $parts_in['out_price'];?>"/>
                </td>
                <td><div style="width:85px;text-align:right;">数量：</div></td>
                <td>
                    <input class="easyui-textbox" type="text"  class="in_number" name="in_number" value="<?php echo $parts_in['in_number'];?>" validType="number"/><font color="red">*请谨慎操作库存</font>
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">规格：</div></td>
                <td>
                    <input class="easyui-textbox" type="text"  class="standard" name="standard" value="<?php echo $parts_in['standard'];?>"/>
                </td>
                <td><div style="width:85px;text-align:right;">型号：</div></td>
                <td>
                    <input class="easyui-textbox" type="text"  class="parts_model" name="parts_model" value="<?php echo $parts_in['parts_model'];?>"/>
                </td>
                <td><div style="width:85px;text-align:right;">参数：</div></td>
                <td>
                    <input class="easyui-textbox" type="text"  class="param" name="param" value="<?php echo $parts_in['param'];?>"/>
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">保质期（月）：</div></td>
                <td>
                    <input class="easyui-textbox" type="text"  class="expiration_date" name="expiration_date" value="<?php echo $parts_in['expiration_date'];?>" validType="number"/>
                </td>
                <td><div style="width:85px;text-align:right;">保修期（月）：</div></td>
                <td>
                    <input class="easyui-textbox" type="text"  class="warranty_date" name="warranty_date" value="<?php echo $parts_in['warranty_date'];?>" validType="number"/>
                </td>
                <td><div style="width:85px;text-align:right;">适用车型：</div></td>
                <td>
                    <input class="easyui-textbox" type="text"  class="match_car" name="match_car" value="<?php echo $parts_in['match_car'];?>"/>
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">配件来源：</div></td>
                <td>
                    <select
                            class="easyui-combobox"
                            required
                            style="width:150px;"
                            name="original_from"
                            editable="true"
                            data-options="panelHeight:'auto'"
                    >
                        <option value="<?php echo $parts_in['original_from'];?>"><?php echo $parts_in['original_from'];?></option>
                        <option value="厂家索赔">厂家索赔</option>
                        <option value="客户自采">客户自采</option>
                        <option value="维修入库">维修入库</option>
                        <option value="采购入库">采购入库</option>
                    </select>
                </td>
                <td><div style="width:85px;text-align:right;">配件供应商名称：</div></td>
                <td>
                    <input class="easyui-textbox" type="text"  class="original_from_company" name="original_from_company" value="<?php echo $parts_in['original_from_company'];?>"/>
                </td>
                <td><div style="width:85px;text-align:right;">配件供应商编码：</div></td>
                <td>
                    <input class="easyui-textbox" type="text"  class="original_from_code" name="original_from_code" value="<?php echo $parts_in['original_from_company'];?>"/>
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">正副厂：</div></td>
                <td>
                    <select
                            class="easyui-combobox"
                            required
                            style="width:150px;"
                            name="factory"
                            editable="true"
                            data-options="panelHeight:'auto'"
                    >
                        <option value="<?php echo $parts_in['factory'];?>"><?php echo $parts_in['factory'];?></option>
                        <option value="正厂">正厂</option>
                        <option value="副厂">副厂</option>
                    </select>
                </td>
                <td><div style="width:85px;text-align:right;">配件生产商名称：</div></td>
                <td>
                    <input class="easyui-textbox" type="text"  class="product_company" name="product_company" value="<?php echo $parts_in['product_company'];?>"/>
                </td>
                <td><div style="width:85px;text-align:right;">配件生产商编号：</div></td>
                <td>
                    <input class="easyui-textbox" type="text"  class="product_company_code" name="product_company_code" value="<?php echo $parts_in['product_company_code'];?>"/>
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">线下入库时间：</div></td>
                <td>
                    <input style="width: 150px;" type="text"  class="under_in_warehouse_time easyui-datebox" name="under_in_warehouse_time" value="<?php echo $parts_in['under_in_warehouse_time'];?>"/>
                </td>
            </tr>
        </table>
</form>
</div>
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
        number:{
            validator:function(value,param){
                var reg = /^[0-9]*$/g;
                return reg.test(value);
            },
            message:  '只能输入数字！'
        }
    });
</script>
<script>
    $(function () {
        $('#now_price_edit').blur(function () {
            var now_price$ = $('#now_price_edit').val();
            now_price$ = parseFloat(now_price$);
            var new_price = numMulti(now_price$,1.3);
            $('#new_price_edit').val(new_price);
        })
    })
    function numMulti(num1, num2) {
        var baseNum = 0;
        try {
            baseNum += num1.toString().split(".")[1].length;
        } catch (e) {
        }
        try {
            baseNum += num2.toString().split(".")[1].length;
        } catch (e) {
        }
        return Number(num1.toString().replace(".", "")) * Number(num2.toString().replace(".", "")) / Math.pow(10, baseNum);
    }
</script>