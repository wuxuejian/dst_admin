<div id="easyui-datagrid-auto-task-index-toolbar">
        <div class="data-search-form">
            <form action="#" method="post" id="info-edit-feng">
                <table cellpadding="8" cellspacing="0">
                    <tr>
                        <td>车辆品牌:</td>
                        <td>
                            <input  class="easyui-combobox" type="text" name="car_brand" value="<?php echo $data['car_brand'];?>" required style="width: 150px;"/>
                        </td>
                        <td>配件类别:</td>
                        <td>
                            <select
                                    class="easyui-combobox"
                                    style="width:150px;"
                                    id="parts_type_info_edit"
                                    name="parts_type"
                                    editable="true"
                            >
                                <?php foreach($type_name as $val){?>
                                    <?php if($data['parent_id'] == $val['id']){?>
                                        <option value="<?php echo $val['id'];?>" selected="selected"><?php echo $val['parts_name']; ?></option>
                                    <?php }else{?>
                                        <option value="<?php echo $val['id'];?>"><?php echo $val['parts_name']; ?></option>
                                    <?php }?>
                                <?php }?>
                            </select>
                        </td>
                        <td>配件种类:</td>
                        <td>
                            <select
                                    class="easyui-combobox"
                                    style="width:150px;"
                                    id="parts_kind_info_edit"
                                    name="parts_kind"
                                    editable="true"
                                    data-options="panelHeight:'auto'"
                            >
                                <option value="<?php echo $data['son_id'];?>"><?php echo $data['son_name'];?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>配件名称:</td>
                        <td><input type="text" class="easyui-textbox" name="parts_name" value="<?php echo $data['parts_name'];?>" required style="width: 150px;" /></td>
                        <td>配件品牌:</td>
                        <td><input type="text" class="easyui-textbox" name="parts_brand" value="<?php echo $data['parts_brand'];?>" required style="width: 150px;" /></td>
                        <td>厂家配件编码:</td>
                        <td><input type="text" class="easyui-textbox" name="vender_code" value="<?php echo $data['vender_code'];?>" required style="width: 150px;" /></td>
                    </tr>
                    <tr>
                        <td>我方配件编码:</td>
                        <td><input type="text" class="easyui-textbox" name="dst_code" value="<?php echo $data['dst_code'];?>" required style="width: 150px;" /></td>
                        <td>单位:</td>
                        <td><input type="text" name="unit" value="<?php echo $data['unit'];?>" required style="width: 150px;" /></td>
                        <td>主机厂参考价:</td>
                        <td><input type="text"  class="easyui-textbox" name="main_engine_price" value="<?php echo $data['main_engine_price'];?>" required style="width: 150px;" /></td>
                    </tr>
                </table>
                <br>
                <input type="hidden" value="<?php echo $data['parts_id'];?>" name="parts_id">
        </form>
    </div>
</div>
<script>
    //修改功能提交,构建查询表单
    var searchFormEdit = $('#info-edit-feng');
    //汽车品牌下拉
    searchFormEdit.find('input[name=car_brand]').combobox({
        valueField:'value',
        textField:'text',
        data: <?= json_encode($searchFormOptions['car_brand']); ?>,
        editable: false,
        panelHeight:'auto',
        lines:false,
    });
    //配件类别下拉
    searchFormEdit.find('input[name=parts_type]').combobox({
        valueField:'value',
        textField:'text',
        data: <?= json_encode($searchFormOptions['type_name']); ?>,
        editable: true,
        panelHeight:'auto',
    });
    //配件种类
//    searchFormEdit.find('input[name=parts_kind]').combobox({
//        valueField:'value',
//        textField:'text',
//        data: <?//= json_encode($searchFormOptions['part_kind']); ?>//,
//        editable: true,
//        panelHeight:'500',
//    });
    //单位
    searchFormEdit.find('input[name=unit]').combobox({
        valueField:'value',
        textField:'text',
        data: <?= json_encode($searchFormOptions['unit']); ?>,
        editable: true,
        panelHeight:'200',
    });
</script>
<script>
    //二级联动
    $('#parts_type_info_edit').combobox({
        onChange: function (n,o) {
            var id = $('#parts_type_info_edit').combobox('getValue');
            $.ajax({
                url:'<?php echo yii::$app->urlManager->createUrl(['parts/parts-info/get-kind']); ?>',
                type:'post',
                data:{'id':id},
                dataType:'json',
                success:function(data){
//                    $('#parts_kind').combobox('clear');
                    $('#parts_kind_info_edit').combobox({
                        valueField:'value',
                        textField:'text',
                        editable: false,
                        panelHeight:'auto',
                        data: data
                    });
                    $('#parts_kind_info_edit').combobox('setValues','');
                }
            });
        }
    });
</script>