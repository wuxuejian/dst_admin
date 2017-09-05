<form id="search-form-parts-provide-edit" method="post">
    <table cellpadding="8" cellspacing="0">
        <tr>
            <td><div style="width:85px;text-align:right;">供应商编号：</div></td>
            <td>
                <input
                    class="easyui-textbox"
                    style="width:150px;"
                    name="provide_code"
                    validType="length[50]"
                    value="<?php echo $data['provide_code'];?>"
                    data-options="prompt:'请输入',"
                >
                <input type="hidden" name="provide_id" value="<?php echo $data['id'];?>">
            </td>
            <td><div style="width:85px;text-align:right;">供应商名称：</div></td>
            <td>
                <input
                    class="easyui-textbox"
                    style="width:150px;"
                    name="provide_name"
                    required="true"
                    validType="length[50]"
                    value="<?php echo $data['provide_name'];?>"
                    data-options="prompt:'请输入',"
                >
            </td>
            <td><div style="width:85px;text-align:right;">负责人：</div></td>
            <td>
                <input
                    class="easyui-textbox"
                    style="width:150px;"
                    name="duty_man"
                    required="true"
                    validType="length[50]"
                    value="<?php echo $data['duty_man'];?>"
                    data-options="prompt:'请输入',"
                >
            </td>
        </tr>
        <tr>
            <td><div style="width:85px;text-align:right;">联系方式：</div></td>
            <td>
                <input
                    class="easyui-textbox"
                    style="width:150px;"
                    name="tel"
                    required="true"
                    validType="tel"
                    value="<?php echo $data['tel'];?>"
                    data-options="prompt:'请输入',"
                >
            </td>
            <td><div style="width:85px;text-align:right;">主营范围：</div></td>
            <td>
                <input
                    class="easyui-textbox"
                    style="width:150px;"
                    name="main_range"
                    required="true"
                    validType="length[50]"
                    value="<?php echo $data['main_range'];?>"
                    data-options="prompt:'请输入',"
                >
            </td>
            <td><div style="width:85px;text-align:right;">合作方式：</div></td>
            <td>
                <select name="work_type" style="width:150px;">
                    <option value=" ">请选择</option>
                    <option value="1" <?php if($data['work_type']==1){echo "selected";}?>>有合作协议</option>
                    <option value="2" <?php if($data['work_type']==2){echo "selected";}?>>无合作协议</option>
                </select>
            </td>
        </tr>
        <tr>
            <td><div style="width:85px;text-align:right;">所属区域：</div></td>
            <td>
                <select class="easyui-combobox" id="s_province" name="home_sheng" style="width: 65px;">
                    <option value="<?php echo $data['sheng_id'];?>"><?php echo $data['sheng_name'];?></option>
                    <?php foreach($sheng as $val){?>
                        <option value="<?php echo $val['region_id']; ?>"><?php echo $val['region_name']; ?></option>
                    <?php }?>
                </select>
                <select class="easyui-combobox" id="s_city" name="home_shi" style="width:60px;">
                    <option value="<?php echo $data['shi_id'];?>"><?php echo $data['shi_name'];?></option>
                </select>
                <select class="easyui-combobox" id="s_county" name="home_qu" style="width: 70px;">
                    <option value="<?php echo $data['qu_id'];?>"><?php echo $data['qu_name'];?></option>
                </select>
            </td>
            <td><div style="width:85px;text-align:right;">具体地址：</div></td>
            <td>
                <input
                    class="easyui-textbox"
                    style="width:150px;"
                    name="addr"
                    required="true"
                    validType="length[50]"
                    value="<?php echo $data['addr'];?>"
                    data-options="prompt:'请输入',"
                >
            </td>
        </tr>
        <tr>
            <td><div style="width:85px;text-align:right;">备注：</div></td>
            <td colspan="5">
                <input
                    class="easyui-textbox"
                    style="width:690px;"
                    name="note"
                    validType="length[100]"
                    value="<?php echo $data['note'];?>"
                    data-options="prompt:'请输入',"
                >
            </td>
        </tr>
    </table>
</form>
<script>
    //validType="onlyNum"
    //新增验证规则
    $.extend($.fn.validatebox.defaults.rules, {
        onlyNum:{
            validator:function(value,param){
                var reg = /^[0-9a-zA-Z]*$/g;
                return reg.test(value);
            },
            message:  '只能输入数字或字母！'
        },
        tel:{
            validator:function(value,param){
                var reg = /^0{0,1}(1[0-9][0-9]|15[7-9]|153|156|18[7-9])[0-9]{8}$/g;
                return reg.test(value);
            },
            message:  '手机号不正确！'
        }
    });
</script>
<script>
    //构建查询表单
    var searchForm = $('#search-form-parts-provide-edit');
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
    //三级联动
    $('#s_province').combobox({
        onChange: function (n,o) {
            var id = $('#s_province').combobox('getValue');
            $.ajax({
                async: false,
                url:'<?php echo yii::$app->urlManager->createUrl(['parts/parts-provide/get-shi']); ?>',
                type:'post',
                data:{'id':id},
                dataType:'json',
                success:function(data){
                    $('#s_city').combobox({
                        valueField:'value',
                        textField:'text',
                        editable: false,
                        panelHeight:'auto',
                        data: data,
                        onChange:function (n,o) {
                            var id = $('#s_city').combobox('getValue');
                            $.ajax({
                                async: false,
                                url:'<?php echo yii::$app->urlManager->createUrl(['parts/parts-provide/get-qu']); ?>',
                                type:'post',
                                data:{'id':id},
                                dataType:'json',
                                success:function(data){
                                    $('#s_county').combobox({
                                        valueField:'value',
                                        textField:'text',
                                        editable: false,
                                        panelHeight:'auto',
                                        data: data
                                    });
                                    $('#s_county').combobox('setValues','');
                                }
                            });
                        }
                    });
                    $('#s_city').combobox('setValues','');
                }
            });
        }
    });
</script>