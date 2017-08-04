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
                        <div class="item-name">大区</div>
                        <div class="item-input">
                            <select class="easyui-combobox" id="s_province" name="s_province" style="width: 150px;">
                                <option value="请选择">请选择</option>
                                <?php foreach($daqu as $val){?>
                                    <option value="<?php echo $val['id']; ?>"><?php echo $val['name']; ?></option>
                                <?php }?>
                            </select>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">运营公司</div>
                        <div class="item-input">
                            <select class="easyui-combobox" id="s_city" name="s_city" style="width:200px;"></select>  
                        </div>
                    </li>
                    <li>
                        <div class="item-name">仓储地点</div>
                        <div class="item-input">
                            <select class="easyui-combobox" id="s_county" name="s_county" style="width: 150px;"></select>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">车辆品牌</div>
                        <div class="item-input">
                            <input class="easyui-combotree" name="brand_id" style="width:150px;"></input>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">配件名称</div>
                        <div class="item-input">
                            <input class="easyui-textbox" name="parts_name" style="width:150px;"></input>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">配件类别</div>
                        <div class="item-input">
                            <select
                                    class="easyui-combobox"
                                    style="width:150px;"
                                    id="parts_type_instock"
                                    name="parts_type"
                                    editable="true"
                                    listHeight="200px"
                            >
                                <option value=" ">请选择</option>
                                <?php foreach($searchFormOptions['parts_type'] as $val){?>
                                    <option value="<?php echo $val['id']; ?>"><?php echo $val['parts_name']; ?></option>
                                <?php }?>
                            </select>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">配件种类</div>
                        <div class="item-input">
                            <select
                                    class="easyui-combobox"
                                    style="width:150px;"
                                    id="parts_kind_instock"
                                    name="parts_kind"
                                    editable="true"
                                    data-options="panelHeight:'auto'"
                            >
                            </select>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">配件品牌</div>
                        <div class="item-input">
                            <input class="easyui-textbox" name="parts_brand" style="width:150px;"></input>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">厂家配件编码</div>
                        <div class="item-input">
                            <input class="easyui-textbox" name="vender_code" style="width:150px;"></input>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">我方配件编码</div>
                        <div class="item-input">
                            <input class="easyui-textbox" name="dst_code" style="width:150px;"></input>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">线下入库时间</div>
                        <div class="item-input">
                            <!-- <input class="easyui-datebox" name="under_in_warehouse_time" style="width:150px;"></input> -->

                            <input class="easyui-datebox" type="text" name="start_add_time" style="width:93px;"
                                   data-options=""
                                />
                            -
                            <input class="easyui-datebox" type="text" name="end_add_time" style="width:93px;"
                                   data-options=""
                                />
                        </div>
                    </li>
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
<script>

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
                {field: 'region_name',title: '大区',width:50},
                {field: 'operating_company_id',title: '运营公司',width:100},
                {field: 'warehouse_address',title: '仓储地点',width:100},
                {field: 'car_brand',title: '车辆品牌',width:100},
                {field: 'parents_name',title: '配件类别',width: 100},
                {field: 'son_name',title: '配件种类',width: 100},
                {field: 'parts_name',title: '配件名称',width: 100},
                {field: 'parts_brand',title: '配件品牌',width: 100},
                {field: 'vender_code',title: '厂家配件编码',width: 100},
                {field: 'dst_code',title: '我方配件编码',width: 100},
                {field: 'unit',title: '单位',width: 50},
                {field: 'main_engine_price',title: '主机厂参考价',width: 100},
                {field: 'shop_price',title: '采购单价（元）',width: 100},
                {field: 'out_price',title: '出库单价（元）',width: 100},
                {field: 'in_number',title: '数量',width: 100},
                {field: 'standard',title: '规格',width: 100},
                {field: 'parts_model',title: '型号',width: 100},
                {field: 'param',title: '参数',width: 100},
                {field: 'expiration_date',title: '保质期（月）',width: 100},
                {field: 'warranty_date',title: '保修期（月）',width: 100},
                {field: 'match_car',title: '适用车型',width: 100},
                {field: 'original_from',title: '配件来源',width: 100},
                {field: 'original_from_company',title: '配件供应商名称',width: 100},
                {field: 'original_from_code',title: '配件供应商编码',width: 100},
                {field: 'factory',title: '正副厂',width: 100},
                {field: 'product_company',title: '配件生产商名称',width: 100},
                {field: 'product_company_code',title: '配件生产商编号',width: 100},
                {field: 'under_in_warehouse_time',title: '线下入库时间',width: 100},
                {field: 'on_registrant',title: '线上登记人',width: 100},
                {field: 'on_registrant_date',title: '线上登记时间',width: 150}
            ]]
        });
        //初始化添加窗口
        $('#easyui-dialog-parts-parts-instack-index-add').dialog({
            title: '选择配件',
            width: 1200,
            height: 'auto',
            cache: true,
            modal: true,
            closed: true,
            maximizable:true,
            buttons: [{
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
        //添加配件
        $('#easyui-dialog-parts-parts-instack-index-add-part').dialog({
            title: '配件入库',
            width: 1000,
            height: 'auto',
            cache: true,
            modal: true,
            closed: true,
            maximizable:true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var form = $('#search-form-parts-add-part');
                    if(!form.form('validate')){
                        return false;
                    }
                    var data = $('#search-form-parts-add-part').serialize();
                    var button = $(this);
                    button.linkbutton('disable');
                    $.ajax({
                        type: 'post',
                        url: '<?php echo yii::$app->urlManager->createUrl(['parts/parts-instock/add']); ?>',
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status == 1){
                                $.messager.alert('入库成功',data.info,'info');
                                $('#easyui-dialog-parts-parts-instack-index-add-part').dialog('close');
                                $('#easyui-datagrid-parts-parts-instock-index').datagrid('reload');
                                button.linkbutton('enable');
                            }else if(data.status == 2){
                                $.messager.alert('入库失败',data.info,'error');
                                button.linkbutton('enable');
                            }else{
                                $.messager.alert('入库失败',data.info,'error');
                                button.linkbutton('enable');
                            }
                        }
                    });
                }
            },{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui-dialog-parts-parts-instack-index-add-part').dialog('close');
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
    }

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
    //车辆品牌下拉
   /* searchForm.find('input[name=car_brand]').combotree({
        editable: false,
        panelHeight:'auto',
        lines:false,
        onChange: function(o){
            searchForm.submit();
        }
    });*/
//    //配件类别下拉
//    searchForm.find('input[name=parts_type]').combobox({
//        valueField:'value',
//        textField:'text',
//        data: <?//= json_encode($searchFormOptions['part_type']); ?>//,
//        editable: true,
//        panelHeight:'auto',
//        onSelect: function(){
//            searchForm.submit();
//        }
//    });
    searchForm.find('input[name=brand_id]').combotree({
            url: "<?php echo yii::$app->urlManager->createUrl(['car/combotree/get-car-brands']); ?>",
            editable: true,
            panelHeight:'auto',
            lines:false,
            onChange: function(o){
                searchForm.submit();
            }
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
    $('#parts_type_instock').combobox({
        onChange: function (n,o) {
            var id = $('#parts_type_instock').combobox('getValue');
            $.ajax({
                async: false,
                url:'<?php echo yii::$app->urlManager->createUrl(['parts/parts-instock/get-kind']); ?>',
                type:'post',
                data:{'id':id},
                dataType:'json',
                success:function(data){
//                    $('#parts_kind').combobox('clear');
                    $('#parts_kind_instock').combobox({
                        valueField:'value',
                        textField:'text',
                        editable: false,
                        panelHeight:'auto',
                        data: data
                    });
                    $('#parts_kind_instock').combobox('setValues','');
                }
            });
        }
    });
    //三级联动
    $('#s_province').combobox({
        onChange: function (n,o) {
            var id = $('#s_province').combobox('getValue');
            $.ajax({
                async: false,
                url:'<?php echo yii::$app->urlManager->createUrl(['parts/parts-instock/get-company']); ?>',
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
                                url:'<?php echo yii::$app->urlManager->createUrl(['parts/parts-instock/get-site']); ?>',
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