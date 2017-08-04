<table id="easyui-datagrid-parts-parts-info-index"></table>
<div id="easyui-datagrid-parts-parts-info-index-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">
        <div class="data-search-form">
            <form id="search-form-parts-info-index" method="post">
                <ul class="search-main">
                    <li>
                        <div class="item-name">车辆品牌</div>
                        <div class="item-input">
                            <input class="easyui-textbox" name="car_brand" style="width:150px;">
                        </div>
                    </li>
                    <li>
                        <div class="item-name">配件类别</div>
                        <div class="item-input">
                            <select
                                    class="easyui-combobox"
                                    style="width:150px;"
                                    id="parts_type"
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
                                    id="parts_kind"
                                    name="parts_kind"
                                    editable="true"
                                    data-options="panelHeight:'auto'"
                            >
                            </select>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">配件名称</div>
                        <div class="item-input">
                            <input class="easyui-textbox" name="parts_name" style="width:150px;">
                        </div>
                    </li>
                    <li>
                        <div class="item-name">配件品牌</div>
                        <div class="item-input">
                            <input class="easyui-textbox" name="parts_brand" style="width:150px;">
                        </div>
                    </li>
                    <li>
                        <div class="item-name">厂家配件编码</div>
                        <div class="item-input">
                            <input class="easyui-textbox" name="vender_code" style="width:150px;">
                        </div>
                    </li>
                    <li>
                        <div class="item-name">我方配件编码</div>
                        <div class="item-input">
                            <input class="easyui-textbox" name="dst_code" style="width:150px;">
                        </div>
                    </li>
                    <li class="search-button">
                        <button type="submit" onclick="PartsInfoIndex.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button type="submit" onclick="PartsInfoIndex.resetForm()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
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
<div id="easyui-dialog-parts-parts-info-index-add"></div>
<div id="easyui-dialog-parts-parts-info-index-edit"></div>
<div id="easyui-dialog-parts-parts-info-index-import">
<!--    <form id="much_import" enctype="multipart/form-data" method="post">
        <div style="margin-left: 20%;margin-top: 50px;">请上传文件：<input type="file" id="upload"></div>
        <br><div style="margin-left: 20%;">（xlsx格式，可参考案例填写）</div>
        <br><div style="margin-left: 20%;">案例：<a>hehe</a></div>
    </form>-->
</div>
<script>
    var PartsInfoIndex = new Object();
    PartsInfoIndex.init = function(){
        //获取列表数据
        $('#easyui-datagrid-parts-parts-info-index').datagrid({
            method: 'get',
            url:'<?php echo yii::$app->urlManager->createUrl(['parts/parts-info/get-list']); ?>',
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-parts-parts-info-index-toolbar",
            pagination: true,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
            columns:[[
                {field: 'ck',checkbox: true},
                {field: 'name',title: '车辆品牌'},
                {field: 'parents_name',title: '配件类别',width: 100},
                {field: 'son_name',title: '配件种类',width: 100},
                {field: 'parts_name',title: '配件名称',width: 100},
                {field: 'parts_brand',title: '配件品牌',width: 100},
                {field: 'vender_code',title: '厂家配件编码',width: 100},
                {field: 'dst_code',title: '我方配件编码',width: 100},
                {field: 'unit',title: '单位',width: 50},
                {field: 'main_engine_price',title: '主机厂参考价',width: 100},
            ]]
        });
        //初始化批量导入窗口
        $('#easyui-dialog-parts-parts-info-index-import').dialog({
            title: '批量导入',
            width: 400,
            height: 250,
            cache: true,
            modal: true,
            closed: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    PartsInfoIndex.import2();
                }
            },{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui-dialog-parts-parts-info-index-import').dialog('close');
                }
            }],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
        //初始化添加窗口`
        $('#easyui-dialog-parts-parts-info-index-add').dialog({
            title: '添加配件信息',
            width: 900,
            height: 300,
            cache: true,
            modal: true,
            closed: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var form = $('#search-form-parts-info-add');
                    if(!form.form('validate')){
                        return false;
                    }
                    var data = form.serialize();
                    var button = $(this);
                    button.linkbutton('disable');
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['parts/parts-info/add']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status == 1){
                                $.messager.alert('新建成功',data.info,'info');
                                $('#easyui-dialog-parts-parts-info-index-add').dialog('close');
                                $('#easyui-datagrid-parts-parts-info-index').datagrid('reload');
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
                    $('#easyui-datagrid-parts-parts-info-index').datagrid('reload');
                    $('#easyui-dialog-parts-parts-info-index-add').dialog('close');
                }
            },{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui-dialog-parts-parts-info-index-add').dialog('close');
                }
            }]
        });
        //初始化修改窗口
        $('#easyui-dialog-parts-parts-info-index-edit').dialog({
            title: '修改配置信息',
            width: 1000,
            height: 250,
            closed: true,
            cache: true,
            modal: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var data = $('#info-edit-feng').serialize();
                    var button = $(this);
                    button.linkbutton('disable');
                    $.ajax({
                        type: 'post',
                        url: '<?php echo yii::$app->urlManager->createUrl(['parts/parts-info/edit']); ?>',
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status == 1){
                                $.messager.alert('修改成功',data.info,'info');
                                $('#easyui-dialog-parts-parts-info-index-edit').dialog('close');
                                $('#easyui-datagrid-parts-parts-info-index').datagrid('reload');
                                button.linkbutton('enable');
                            }else if(data.status == 2){
                                $.messager.alert('修改失败',data.info,'error');
                                button.linkbutton('enable');
                            }else{
                                $.messager.alert('修改失败',data.info,'error');
                                button.linkbutton('enable');
                            }
                        }
                    });
                }
            },{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui-dialog-parts-parts-info-index-edit').dialog('close');
                }
            }]
        });
        //绑定记录双击事件
        $('#easyui-datagrid-parts-parts-info-index').datagrid({
            onDblClickRow: function(rowIndex,rowData){
                PartsInfoIndex.edit(rowData.id);
            }
        });
    }
    PartsInfoIndex.init();
    //添加方法
    PartsInfoIndex.add = function(){
        $('#easyui-dialog-parts-parts-info-index-add').dialog('open');
        $('#easyui-dialog-parts-parts-info-index-add').dialog('refresh','<?php echo yii::$app->urlManager->createUrl(['parts/parts-info/add']); ?>');
    }
    //删除配件信息
    PartsInfoIndex.del = function(){
        var datagrid = $('#easyui-datagrid-parts-parts-info-index');
        var partsData = datagrid.datagrid('getSelected');
        if(partsData == null){
            $.messager.alert('删除失败','请选择删除项','error');
            return false;
        }
        var id = partsData.parts_id;
        $.messager.confirm('确认对话框', '确定删除配件信息？', function(r){
            if (r){
                $.ajax({
                    type: 'post',
                    url: "<?php echo yii::$app->urlManager->createUrl(['parts/parts-info/del']); ?>",
                    data: {'id':id},
                    dataType: 'json',
                    success: function(data){
                        if(data.status == 1){
                            $.messager.alert('删除成功',data.info,'info');
                            $('#easyui-datagrid-parts-parts-info-index').datagrid('reload');
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
    PartsInfoIndex.edit = function(){
        var datagrid = $('#easyui-datagrid-parts-parts-info-index');
        var partsData = datagrid.datagrid('getSelected');
        if(partsData == null){
            $.messager.alert('修改失败','请选择修改项','error');
            return false;
        }
        var id = partsData.parts_id;
        $('#easyui-dialog-parts-parts-info-index-edit').dialog('open');
        $('#easyui-dialog-parts-parts-info-index-edit').dialog('refresh','<?php echo yii::$app->urlManager->createUrl(['parts/parts-info/edit']); ?>&parts='+id);
    }
    //批量导入
    PartsInfoIndex.import = function(){
        $('#easyui-dialog-parts-parts-info-index-import').dialog('open');
        $('#easyui-dialog-parts-parts-info-index-import').dialog('refresh','<?php echo yii::$app->urlManager->createUrl(['parts/parts-info/much-import']); ?>');
    }
    //构建查询表单
    var searchForm = $('#search-form-parts-info-index');
    /**查询表单提交事件**/
    searchForm.submit(function(){
        var data = {};
        var searchCondition = $(this).serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        $('#easyui-datagrid-parts-parts-info-index').datagrid('load',data);
        return false;
    });

    //汽车品牌下拉
    searchForm.find('input[name=car_brand]').combotree({
        url: "<?php echo yii::$app->urlManager->createUrl(['car/combotree/get-car-brands']); ?>",
        editable: false,
        panelHeight:'auto',
        lines:false,
    });
    //重置查询表单
    PartsInfoIndex.resetForm = function(){
        var easyuiForm = $('#search-form-parts-info-index');
        easyuiForm.form('reset');
    }

    //条件搜索查询
    PartsInfoIndex.search = function(){
        var form = $('#search-form-parts-info-index');
        var data = {};
        var searchCondition = form.serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        $('#easyui-datagrid-parts-parts-info-index').datagrid('load',data);
    }
</script>
<script>
    //二级联动
    $('#parts_type').combobox({
        onChange: function (n,o) {
            var id = $('#parts_type').combobox('getValue');
            $.ajax({
                url:'<?php echo yii::$app->urlManager->createUrl(['parts/parts-info/get-kind']); ?>',
                type:'post',
                data:{'id':id},
                dataType:'json',
                success:function(data){
//                    $('#parts_kind').combobox('clear');
                    $('#parts_kind').combobox({
                        valueField:'value',
                        textField:'text',
                        editable: false,
                        panelHeight:'auto',
                        data: data
                    });
                    $('#parts_kind').combobox('setValues','');
                }
            });
        }
    });
</script>