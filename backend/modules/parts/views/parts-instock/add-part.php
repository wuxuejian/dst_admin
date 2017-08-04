<style>
    #table_add_part div{
        width: 120px !important;
    }
</style>
<div class="data-search-form">
    <form id="search-form-parts-add-part">
        <input type="hidden" name="parts_id" value="<?php echo $dat['parts_id'];?>">
        <table cellpadding="8" cellspacing="0" id="table_add_part">
            <tr>
                <td><div style="width:85px;text-align:right;">大区：</div></td>
                <td>
                    <select
                            class="easyui-combobox"
                            style="width:150px;"
                            id="s_province_add"
                            name="s_province_add"
                            editable="true"
                            listHeight="200px"
                    >
                        <option value=" ">请选择</option>
                        <?php foreach($daqu as $val){?>
                            <option value="<?php echo $val['id']; ?>"><?php echo $val['name']; ?></option>
                        <?php }?>
                    </select>
                </td>
                <td><div style="width:85px;text-align:right;">运营公司：</div></td>
                <td>
                    <select
                            class="easyui-combobox"
                            style="width:150px;"
                            id="s_city_add"
                            name="s_city_add"
                            editable="true"
                            data-options="panelHeight:'auto'"
                    >
                    </select>
                </td>
                <td><div style="width:85px;text-align:right;">仓储地点：</div></td>
                <td>
                    <select
                            class="easyui-combobox"
                            style="width:150px;"
                            id="s_county_add"
                            name="s_county_add"
                            editable="true"
                            data-options="panelHeight:'auto'"
                    >
                    </select>
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">车辆品牌：</div></td>
                <td>
                    <input class="easyui-textbox" name="brand_id" style="width:150px;" value="<?php echo $dat['car_name'];?>" disabled="disabled">
                </td>
                <td><div style="width:85px;text-align:right;">配件类别：</div></td>
                <td>
                    <input class="easyui-textbox" name="parts_type" style="width:150px;" value="<?php echo $dat['parent_name'];?>" disabled="disabled">
                </td>
                <td><div style="width:85px;text-align:right;">配件种类：</div></td>
                <td>
                    <input class="easyui-textbox" name="parts_kind" style="width:150px;" value="<?php echo $dat['son_name'];?>" disabled="disabled">
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">配件名称：</div></td>
                <td>
                    <input class="easyui-textbox" name="parts_name" style="width:150px;" value="<?php echo $dat['parts_name'];?>" disabled="disabled">
                </td>
                <td><div style="width:85px;text-align:right;">配件品牌：</div></td>
                <td>
                    <input class="easyui-textbox" name="parts_brand" style="width:150px;" value="<?php echo $dat['parts_brand'];?>" disabled="disabled">
                </td>
                <td><div style="width:85px;text-align:right;">厂家配件编码：</div></td>
                <td>
                    <input class="easyui-textbox" name="vender_code" style="width:150px;" value="<?php echo $dat['vender_code'];?>" disabled="disabled">
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">我方配件编码：</div></td>
                <td>
                    <input class="easyui-textbox" name="dst_code" style="width:150px;" value="<?php echo $dat['dst_code'];?>" disabled="disabled">
                </td>
                <td><div style="width:85px;text-align:right;">单位：</div></td>
                <td>
                    <input class="easyui-textbox" name="unit" style="width:150px;" value="<?php echo $dat['unit'];?>" disabled="disabled">
                </td>
                <td><div style="width:85px;text-align:right;">主机厂参考价(元)：</div></td>
                <td>
                    <input class="easyui-textbox" name="main_engine_price" style="width:150px;" value="<?php echo $dat['main_engine_price'];?>" disabled="disabled">
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">采购单价（元）：</div></td>
                <td>
                    <input class="easyui-textbox" id="shop_price" required="required" placeholder="必填" name="shop_price" validType="float" style="width:150px;">
                </td>
                <td><div style="width:85px;text-align:right;">出库单价（元）：</div></td>
                <td>
                    <input class="out_price" disabled="disabled" name="out_price" style="width:145px;">
                </td>
                <td><div style="width:85px;text-align:right;">入库数量：</div></td>
                <td>
                    <input class="easyui-textbox" required name="in_number" style="width:150px;" validType="number">
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">规格：</div></td>
                <td>
                    <input class="easyui-textbox" name="standard" style="width:150px;">
                </td>
                <td><div style="width:85px;text-align:right;">型号：</div></td>
                <td>
                    <input class="easyui-textbox" name="parts_model" style="width:150px;">
                </td>
                <td><div style="width:85px;text-align:right;">参数：</div></td>
                <td>
                    <input class="easyui-textbox" name="param" style="width:150px;">
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">保质期（月）：</div></td>
                <td>
                    <input class="easyui-textbox" required name="expiration_date" style="width:150px;" validType="number">
                </td>
                <td><div style="width:85px;text-align:right;">保修期（月）：</div></td>
                <td>
                    <input class="easyui-textbox" required name="warranty_date" style="width:150px;" validType="number">
                </td>
                <td><div style="width:85px;text-align:right;">适用车型：</div></td>
                <td>
                    <input class="easyui-textbox" required name="match_car" style="width:150px;">
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
                        <?php foreach ($original_from as $v){?>
                            <option value="<?php echo $v['name'];?>"><?php echo $v['name'];?></option>
                        <?php }?>
                    </select>
                </td>
                <td><div style="width:85px;text-align:right;">配件供应商名称：</div></td>
                <td>
                    <input class="easyui-textbox" name="original_from_company" style="width:150px;">
                </td>
                <td><div style="width:85px;text-align:right;">配件供应商编码：</div></td>
                <td>
                    <input class="easyui-textbox" name="original_from_code" style="width:150px;">
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
                        <option value="正厂">正厂</option>
                        <option value="副厂">副厂</option>
                    </select>
                </td>
                <td><div style="width:85px;text-align:right;">配件生产商名称：</div></td>
                <td>
                    <input class="easyui-textbox" name="product_company" style="width:150px;">
                </td>
                <td><div style="width:85px;text-align:right;">配件生产商编号：</div></td>
                <td>
                    <input class="easyui-textbox" name="product_company_code" style="width:150px;">
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">线下入库时间：</div></td>
                <td>
                    <input class="easyui-datebox" required type="text" name="under_in_warehouse_time" style="width:150px;" data-options=""/>
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
            message:  '只能输入整数数字！'
        },
        float:{
            validator:function(value,param){
                var reg = /^\d+(\.\d+)?$/;
                return reg.test(value);
            },
            message:  '只能输入数字！'
        }
    });
</script>
<script>
    //三级联动
    $('#s_province_add').combobox({
        onChange: function (n,o) {
            var id = $('#s_province_add').combobox('getValue');
            $.ajax({
                async: false,
                url:'<?php echo yii::$app->urlManager->createUrl(['parts/parts-instock/get-company']); ?>',
                type:'post',
                data:{'id':id},
                dataType:'json',
                success:function(data){
                    $('#s_city_add').combobox({
                        valueField:'value',
                        textField:'text',
                        editable: false,
                        panelHeight:'auto',
                        data: data,
                        onChange:function (n,o) {
                            var id = $('#s_city_add').combobox('getValue');
                            $.ajax({
                                async: false,
                                url:'<?php echo yii::$app->urlManager->createUrl(['parts/parts-instock/get-site']); ?>',
                                type:'post',
                                data:{'id':id},
                                dataType:'json',
                                success:function(data){
                                    $('#s_county_add').combobox({
                                        valueField:'value',
                                        textField:'text',
                                        editable: false,
                                        panelHeight:'auto',
                                        data: data
                                    });
                                    $('#s_county_add').combobox('setValues','');
                                }
                            });
                        }
                    });
                    $('#s_city_add').combobox('setValues','');
                }
            });
        }
    });
</script>
<script>

    function abc(){
        $("input[name='shop_price']").siblings(".textbox-text").blur(function(){
            var now_price$ = $("input[name='shop_price']").val();
            now_price$ = parseFloat(now_price$);
            var new_price = numMulti(now_price$,1.3);
            $('.out_price').val(new_price);
        })
    }

    setTimeout("abc()",1000);

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