<table id="easyui-datagrid-parts-parts-instock-index"></table>
<div id="easyui-datagrid-parts-parts-instock-index-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">
        <div class="data-search-form">
            <form id="search-form-parts-instock-index">
                <ul class="search-main">
                    <li>
                        <div class="item-name">入库单号</div>
                        <div class="item-input">
                            <input class="easyui-textbox" name="order_in" data-options="prompt:'请输入'," style="width:150px;">
                        </div>
                    </li>
                    <li>
                        <div class="item-name">入库类型</div>
                        <div class="item-input">
                            <select class="easyui-combobox" name="type_in" style="width:150px;">
                                <option value=" ">请输入</option>
                                <option value="采购入库">采购入库</option>
                                <option value="销售退货入库">销售退货入库</option>
                                <option value="调拨入库">调拨入库</option>
                                <option value="索赔入库">索赔入库</option>
                                <option value="盘点入库">盘点入库</option>
                                <option value="其它">其它</option>
                            </select>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">入库时间</div>
                        <div class="item-input">
                            <input class="easyui-datebox" type="text" data-options="prompt:'请输入'," name="start_add_time" style="width:93px;"
                                   data-options=""
                            />
                            -
                            <input class="easyui-datebox" type="text" data-options="prompt:'请输入'," name="end_add_time" style="width:93px;"
                                   data-options=""
                            />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">运营公司</div>
                        <div class="item-input">
                            <select
                                    class="easyui-combobox"
                                    style="width:150px;"
                                    name="company"
                                    id="company_feng"
                                    editable="true"
                                    listHeight="200px"
                            >
                                <option value=" ">请选择</option>
                                <?php foreach($searchFormOptions['company'] as $val){?>
                                    <option value="<?php echo $val['id']; ?>"><?php echo $val['name']; ?></option>
                                <?php }?>
                            </select>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">仓库名称</div>
                        <div class="item-input">
                            <input class="easyui-combobox" name="warehouse_address" id="house_name" data-options="prompt:'请输入'," style="width:150px;">
                        </div>
                    </li>
                    <li>
                        <div class="item-name">验收人</div>
                        <div class="item-input">
                            <select
                                    class="easyui-combobox"
                                    style="width:150px;"
                                    name="check_man"
                                    editable="true"
                                    listHeight="200px"
                            >
                                <option value=" ">请选择</option>
                                <?php foreach($searchFormOptions['check_man'] as $val){?>
                                    <option value="<?php echo $val['user_id']; ?>"><?php echo $val['human_name']; ?></option>
                                <?php }?>
                            </select>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">状态</div>
                        <div class="item-input">
                            <select class="easyui-combobox" name="status" style="width:150px;">
                                <option value="0">正常</option>
                                <option value="1">已冲销</option>
                            </select>
                        </div>
                    </li>
<!--                    <li>-->
<!--                        <div class="item-name">状态</div>-->
<!--                        <div class="item-input">-->
<!--                            <input class="easyui-textbox" name="dst_code" style="width:150px;">-->
<!--                        </div>-->
<!--                    </li>-->
                    <li class="search-button">
                        <!-- <a onclick="DrbacMcaIndex.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a> -->
                        <button type="submit" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button type="submit" onclick="PartsInstockIndex.resetForm()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
                    </li>
                </ul>
            </form>
        </div>
    </div>
    <?php if($buttons){ ?>
        <div class="easyui-panel" title="数据列表" style="padding:3px 2px;width:100%;" data-options="
        iconCls: 'icon-table-list',
        border: false
    ">
            <?php foreach($buttons as $val){ ?>
                <button onclick="<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon']; ?>'"><?= $val['text']; ?></button>
            <?php } ?>
        </div>
    <?php } ?>
</div>
<!-- 窗口 -->
<div id="easyui-dialog-parts-parts-instack-index-add"></div>
<div id="easyui-dialog-parts-parts-instock-index-edit" ></div>
<div id="easyui-dialog-parts-parts-instack-index-add-part"></div>
<div id="easyui-dialog-parts-parts-instack-index-add-new-part"></div>
<div id="easyui-dialog-parts-parts-instack-index-see"></div>

<script>
    function del_parts(obj){
        $(obj).parent().parent('tr').remove();
        account();
    }
    function jisuan(a,b){
        a = parseFloat(a);
        b = parseFloat(b);
        var new_price = numMulti(a,b);
        return new_price;
    }
    function chan(obj){
        var price = $(obj).val();
//        alert(price);
        var shop_price = $(obj).parent().siblings('.shop_price').find('input').val();
//        alert(shop_price);
        var new_price =  numMulti(price,shop_price);
//        alert(new_price);
        var price_ji = $(obj).parent().siblings('.new_price_feng');
//        alert($(obj).parent().siblings('.new_price_feng').text());
        price_ji.text(new_price);
        account();
    }
    function chan_Price(obj){
        var price = $(obj).val();
//        alert(price);
        var shop_num = $(obj).parent().siblings('.shop_num').find('input').val();
//        alert(shop_price);
        var new_price =  numMulti(price,shop_num);
//        alert(new_price);
        var price_ji = $(obj).parent().siblings('.new_price_feng');
//        alert($(obj).parent().siblings('.new_price_feng').text());
        price_ji.text(new_price);
        account();
    }
    function account(){
        var amount = 0;
        for(var i = 0 ; i < $('.new_price_feng').length ; i++){
            amount += parseFloat($('.new_price_feng').eq(i).text());
        }
        $('#amount_money_feng').text(amount);
    }
    var PartsInstockIndex = new Object();
    PartsInstockIndex.init = function(){
        //获取列表数据
        $('#easyui-datagrid-parts-parts-instock-index').datagrid({
            method: 'get',
            url:'<?php echo yii::$app->urlManager->createUrl(['parts/parts-instock/get-list']); ?>',
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-parts-parts-instock-index-toolbar",
            pagination: true,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
            columns:[[
                {field: 'ck',checkbox: true},
                {field: 'parts_id',hidden:true},
                {field: 'order_in',title: '入库单号',width:100},
                {field: 'type_in',title: '入库类型',width:100},
                {field: 'house_name',title: '仓库名称',width:100},
                {field: 'company',title: '运营公司',width:100},
                {field: 'user_name',title: '验收人',width: 100},
                {field: 'status',title: '冲销状态',width: 100,
                    formatter: function(value,row,index){
                        if(row.status == 1){
                            return '是';
                        }else{
                            return '否';
                        }
                    }
                },
                {field: 'create_man',title: '创建人',width: 100},
                {field: 'create_time',title: '创建时间',width: 100},
                {field: 'note',title: '备注',width: 100},
                {field: 'edit_man',title: '修改人',width: 100},
                {field: 'edit_time',title: '修改时间',width: 100}
            ]]
        });
        //初始化添加窗口
        $('#easyui-dialog-parts-parts-instack-index-add').dialog({
            title: '选择配件',
            width: 900,
            height: 'auto',
            cache: true,
            modal: true,
            closed: true,
            maximizable:true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var form = $('#search-form-parts-in-add-new');
                    if(!form.form('validate')){
                        return false;
                    }
                    var data = $('#search-form-parts-in-add-new').serialize();
//                    console.log(data);return false;
                    var button = $(this);
                    button.linkbutton('disable');
                    $.ajax({
                        type: 'post',
                        url: '<?php echo yii::$app->urlManager->createUrl(['parts/parts-instock/add']); ?>',
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status == 1){
                                $.messager.alert('新建成功',data.info,'info');
                                $('#easyui-dialog-parts-parts-instack-index-add').dialog('close');
                                $('#easyui-datagrid-parts-parts-instock-index').datagrid('reload');
                                button.linkbutton('enable');
                            }else if(data.status == 2){
                                $.messager.alert('新建失败',data.info,'error');
                                button.linkbutton('enable');
                            }else{
                                $.messager.alert('新建失败',data.info,'error');
                                button.linkbutton('enable');
                            }
                        }
                    });
                }
            },{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui-dialog-parts-parts-instack-index-add').dialog('close');
                }
            }]
        });
        //初始化修改窗口
        $('#easyui-dialog-parts-parts-instock-index-edit').dialog({
            title: '修改配置信息',
            width: 1100,
            height: 400,
            closed: true,
            cache: true,
            modal: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var form = $('#edit-feng');
                    if(!form.form('validate')){
                        return false;
                    }
                    var data = $('#edit-feng').serialize();
                    var button = $(this);
                    button.linkbutton('disable');
                    $.ajax({
                        type: 'post',
                        url: '<?php echo yii::$app->urlManager->createUrl(['parts/parts-instock/edit']); ?>',
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status == 1){
                                $.messager.alert('新建成功',data.info,'info');
                                $('#easyui-dialog-parts-parts-instock-index-edit').dialog('close');
                                $('#easyui-datagrid-parts-parts-instock-index').datagrid('reload');
                                button.linkbutton('enable');
                            }else if(data.status == 2){
                                $.messager.alert('新建失败',data.info,'error');
                                button.linkbutton('enable');
                            }else{
                                $.messager.alert('新建失败',data.info,'error');
                                button.linkbutton('enable');
                            }
                        }
                    });
                }
            },{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui-dialog-parts-parts-instock-index-edit').dialog('close');
                }
            }]
        });
        //添加新配件
        $('#easyui-dialog-parts-parts-instack-index-add-new-part').dialog({
            title: '选择配件',
            width: 700,
            height: 'auto',
            cache: true,
            modal: true,
            closed: true,
            maximizable:true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var datagrid = $('#easyui-datagrid-parts-parts-instock-add');
                    var partsData = datagrid.datagrid('getChecked');
                    if(partsData.length == ' '){
                        $.messager.alert('选择失败','请至少选择一项','error');
                        return false;
                    }
                    var addHtml = ' ';
                    for(var i =0;i<partsData.length;i++){
                        addHtml += "<tr>" +
                            "    <td>" +
                            "        <input type=\"hidden\" name='parts_id[]' value='"+partsData[i].id+"'>" +
                                    partsData[i].parts_code +
                            "    </td>" +
                            "    <td>" +
                                    partsData[i].parts_name +
                            "    </td>" +
                            "    <td>" +
                                    partsData[i].size +
                            "    </td>" +
                            "    <td class='shop_price'>" +
                            "        <input onkeyup='javascript:chan_Price(this);' style='width: 50px;' type='text' name='price_in[]' value='"+partsData[i].shop_price+"'>" +
                            "    </td>" +
                            "    <td class='shop_num'>" +
                            "        <input onkeyup=\"javascript:chan(this);\" style='width: 50px;' type='text' name='price_num[]' value='1'>" +
                            "    </td>" +
                            "    <td>" +
                                    partsData[i].unit +
                            "    </td>" +
                            "    <td class='new_price_feng'>" +
                                    jisuan(partsData[i].shop_price,1)  +
                            "    </td>" +
                            "    <td>" +
                                    partsData[i].car_type +
                            "    </td>" +
                            "    <td>" +
                            "        <select name='parrs_nature[]'>" +
                            "            <option value='原厂新件'>原厂新件</option>" +
                            "            <option value='原厂旧件'>原厂旧件</option>" +
                            "            <option value='副厂新件'>副厂新件</option>" +
                            "            <option value='副厂旧件'>副厂旧件</option>" +
                            "        </select>" +
                            "    </td>" +
                            "    <td>" +
                            "        <button onclick=\"javascript:del_parts(this);\">删除</button>" +
                            "    </td>" +
                            "</tr>";
                    }
                    $('#add_parts_new').append(addHtml);
                    //计算总价
                    account();
                    $('#easyui-dialog-parts-parts-instack-index-add-new-part').dialog('close');
                }
            },{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui-dialog-parts-parts-instack-index-add-new-part').dialog('close');
                }
            }]
        });
        //查看
        $('#easyui-dialog-parts-parts-instack-index-see').dialog({
            title: '查看',
            width: 900,
            height: 'auto',
            cache: true,
            modal: true,
            closed: true,
            maximizable:true,
            buttons: [{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui-dialog-parts-parts-instack-index-see').dialog('close');
                }
            }]
        });
        //绑定记录双击事件
        $('#easyui-datagrid-parts-parts-instock-index').datagrid({
            onDblClickRow: function(rowIndex,rowData){
                PartsInstockIndex.edit(rowData.id);
            }
        });
    }
    PartsInstockIndex.init();
    //添加方法
    PartsInstockIndex.add = function(){
        $('#easyui-dialog-parts-parts-instack-index-add').dialog('open');
        $('#easyui-dialog-parts-parts-instack-index-add').dialog('refresh','<?php echo yii::$app->urlManager->createUrl(['parts/parts-instock/add']); ?>');
    }
    //添加配件
    PartsInstockIndex.add_new = function(){
        $('#easyui-dialog-parts-parts-instack-index-add-new-part').dialog('open');
        $('#easyui-dialog-parts-parts-instack-index-add-new-part').dialog('refresh','<?php echo yii::$app->urlManager->createUrl(['parts/parts-instock/add-new']); ?>');
    }
    //添加方法
    PartsInstockIndex.del = function(){
        var datagrid = $('#easyui-datagrid-parts-parts-instock-index');
        var partsData = datagrid.datagrid('getSelected');
        if(partsData == null){
            $.messager.alert('删除失败','请选择删除项','error');
            return false;
        }
        var parts_id = partsData.parts_id;
        var insert_id = partsData.insert_id;
        $.messager.confirm('确认对话框', '确定删除配件信息？', function(r){
            if (r){
                $.ajax({
                    type: 'post',
                    url: "<?php echo yii::$app->urlManager->createUrl(['parts/parts-instock/del']); ?>",
                    data: {'parts_id':parts_id,'insert_id':insert_id},
                    dataType: 'json',
                    success: function(data){
                        if(data.status == 1){
                            $.messager.alert('删除成功',data.info,'info');
                            $('#easyui-datagrid-parts-parts-instock-index').datagrid('reload');
                        }else if(data.status == 2){
                            $.messager.alert('删除失败',data.info,'error');
                        }else{
                            $.messager.alert('删除失败',data.info,'error');
                        }
                    }
                });
            }
        });
    }
    PartsInstockIndex.edit = function(){
        var datagrid = $('#easyui-datagrid-parts-parts-instock-index');
        var partsData = datagrid.datagrid('getSelected');
        if(partsData == null){
            alert('请选择修改项');
            return false;
        }
        var insert_id = partsData.insert_id;
        var parts_id = partsData.parts_id;
        $('#easyui-dialog-parts-parts-instock-index-edit').dialog('open');
        $('#easyui-dialog-parts-parts-instock-index-edit').dialog('refresh','<?php echo yii::$app->urlManager->createUrl(['parts/parts-instock/edit']); ?>&insert_id='+insert_id+'&parts_id='+parts_id);
    };
    //查看
    PartsInstockIndex.see = function(){
        var datagrid = $('#easyui-datagrid-parts-parts-instock-index');
        var partsData = datagrid.datagrid('getSelected');
        if(partsData == null){
            alert('请选择查看项');
            return false;
        }
        var id = partsData.id;
        $('#easyui-dialog-parts-parts-instack-index-see').dialog('open');
        $('#easyui-dialog-parts-parts-instack-index-see').dialog('refresh','<?php echo yii::$app->urlManager->createUrl(['parts/parts-instock/see']); ?>&id='+id);
    };
    var searchForm = $('#search-form-parts-instock-index');
    /**查询表单提交事件**/
    searchForm.submit(function(){
        var data = {};
        var searchCondition = $(this).serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        $('#easyui-datagrid-parts-parts-instock-index').datagrid('load',data);
        return false;
    });

    //重置查询表单
    PartsInstockIndex.resetForm = function(){
        var easyuiForm = $('#search-form-parts-instock-index');
        easyuiForm.form('reset');
    }

    //查询表单提交事件
    //条件搜索查询
    PartsInstockIndex.search = function(){
        var form = $('#search-form-parts-instock-index');
        var data = {};
        var searchCondition = form.serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        $('#easyui-datagrid-parts-parts-instock-index').datagrid('load',data);
    }

</script>
<script>
    //二级联动
    $('#company_feng').combobox({
        onChange: function (n,o) {
            var id = $('#company_feng').combobox('getValue');
            $.ajax({
                async: false,
                url:'<?php echo yii::$app->urlManager->createUrl(['parts/parts-instock/get-site']); ?>',
                type:'post',
                data:{'id':id},
                dataType:'json',
                success:function(data){
//                    $('#parts_kind').combobox('clear');
                    $('#house_name').combobox({
                        valueField:'value',
                        textField:'text',
                        editable: false,
                        panelHeight:'auto',
                        data: data
                    });
                    $('#house_name').combobox('setValues','');
                }
            });
        }
    });
</script>
<script>
    //计算采购单价
    $(function () {
        $('#now_price').blur(function () {
            var now_price$ = $('#now_price').val();
            now_price$ = parseFloat(now_price$);
            var new_price = numMulti(now_price$,1.3);
            $('#new_price').val(new_price);
        })
    });
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