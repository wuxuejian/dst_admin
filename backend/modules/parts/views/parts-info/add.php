<div class="easyui-panel" title="配件信息" style="padding:8px 4px;" data-options="
        iconCls: 'icon-tip',
        border: false
    ">
    <form id="search-form-parts-info-add" method="post">
        <table cellpadding="8" cellspacing="0">
            <tr>
                <td><div style="width:85px;text-align:right;">车辆品牌：</div></td>
                <td>
                    <input
                            class="easyui-textbox"
                            style="width:150px;"
                            name="car_brand"
                            required="true"
                            validType="length[100]"
                    >
                </td>
                <td><div style="width:85px;text-align:right;">配件类别：</div></td>
                <td>
                    <select
                            class="easyui-combobox"
                            style="width:150px;"
                            id="parts_type_info"
                            name="parts_type"
                            editable="true"
                            listHeight="200px"
                    >
                        <option value=" ">请选择</option>
                        <?php foreach($type_name as $val){?>
                            <option value="<?php echo $val['id']; ?>"><?php echo $val['parts_name']; ?></option>
                        <?php }?>
                    </select>
                </td>
                <td><div style="width:85px;text-align:right;">配件种类：</div></td>
                <td>
                    <select
                            class="easyui-combobox"s
                            style="width:150px;"
                            id="parts_kind_info"
                            name="parts_kind"
                            editable="true"
                            data-options="panelHeight:'auto'"
                    >
                    </select>
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">配件名称：</div></td>
                <td>
                    <input
                            class="easyui-textbox"
                            style="width:150px;"
                            name="parts_name"
                            required="true"
                            validType="length[100]"
                    >
                </td>
                <td><div style="width:85px;text-align:right;">配件品牌：</div></td>
                <td>
                    <input
                            class="easyui-textbox"
                            style="width:150px;"
                            name="parts_brand"
                            required="true"
                            validType="length[150]"
                    >
                </td>
                <td><div style="width:85px;text-align:right;">厂家配件编码：</div></td>
                <td>
                    <input
                            class="easyui-textbox"
                            style="width:150px;"
                            name="vender_code"
                            required="true"
                            validType="onlyNum"
                    >
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">我方配件编码：</div></td>
                <td>
                    <input
                            class="easyui-textbox"
                            style="width:150px;"
                            name="dst_code"
                            required="true"
                            validType="onlyNum"
                    >
                </td>
                <td><div style="width:85px;text-align:right;">单位：</div></td>
                <td>
                    <input
                            class="easyui-textbox"
                            style="width:150px;"
                            name="unit"
                            required="true"
                            validType="length[150]"
                    >
                </td>
                <td><div style="width:85px;text-align:right;">主机厂参考价：</div></td>
                <td>
                    <input
                            class="easyui-textbox"
                            style="width:150px;"
                            name="main_engine_price"
                            required="true"
                            validType="length[150]"
                    >
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
        }
    });
</script>
<script>
    //构建查询表单
    var searchForm = $('#search-form-parts-info-add');
    //汽车品牌下拉
    searchForm.find('input[name=car_brand]').combotree({
        url: "<?php echo yii::$app->urlManager->createUrl(['car/combotree/get-car-brands']); ?>",
        editable: false,
        panelHeight:'auto',
        lines:false,
    });
    //单位下拉
    searchForm.find('input[name=unit]').combobox({
        valueField:'value',
        textField:'text',
        data: <?= json_encode($searchFormOptions['unit']); ?>,
        editable: true,
        panelHeight:'auto',
    });
</script>
<script>
    //二级联动
    $('#parts_type_info').combobox({
        onChange: function (n,o) {
            var id = $('#parts_type_info').combobox('getValue');
            $.ajax({
                url:'<?php echo yii::$app->urlManager->createUrl(['parts/parts-info/get-kind']); ?>',
                type:'post',
                data:{'id':id},
                dataType:'json',
                success:function(data){
//                    $('#parts_kind').combobox('clear');
                    $('#parts_kind_info').combobox({
                        valueField:'value',
                        textField:'text',
                        editable: false,
                        panelHeight:'auto',
                        data: data
                    });
                    $('#parts_kind_info').combobox('setValues','');
                }
            });
        }
    });
</script>