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
                        <div class="item-name">配件编码</div>
                        <div class="item-input">
                            <input class="easyui-textbox" name="parts_code" data-options="prompt:'请输入'," style="width:150px;">
                        </div>
                    </li>
                    <li>
                        <div class="item-name">配件名称</div>
                        <div class="item-input">
                            <input class="easyui-textbox" name="parts_name" data-options="prompt:'请输入'," style="width:150px;">
                        </div>
                    </li>
                    <li>
                        <div class="item-name">规格</div>
                        <div class="item-input">
                            <input class="easyui-textbox" name="size" data-options="prompt:'请输入'," style="width:150px;">
                        </div>
                    </li>
                    <li>
                        <div class="item-name">原厂编码</div>
                        <div class="item-input">
                            <input class="easyui-textbox" name="factory_code" data-options="prompt:'请输入'," style="width:150px;">
                        </div>
                    </li>
                    <li>
                        <div class="item-name">适用车型</div>
                        <div class="item-input">
                            <input class="easyui-textbox" name="car_type" data-options="prompt:'请输入'," style="width:150px;">
                        </div>
                    </li>
                    <li>
                        <div class="item-name">状态</div>
                        <div class="item-input">
                            <select class="easyui-combobox" name="status" style="width:150px;">
                                <option value="0">正常</option>
                                <option value="1">作废</option>
                            </select>
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
<div id="easyui-dialog-parts-parts-info-index-see"></div>
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
                {field: 'id',hidden:true},
                {field: 'parts_code',title: '配件编码',width: 200},
                {field: 'parts_name',title: '配件名称',width: 100},
                {field: 'size',title: '规格',width: 100},
                {field: 'unit',title: '单位',width: 50},
                {field: 'factory_code',title: '原厂编码',width: 100},
                {field: 'status',title: '状态',width: 100,
                    formatter: function(value,row,index){
                        if(row.status == 1){
                            return '作废';
                        }else{
                            return '正常';
                        }
                    }
                },
                {field: 'create_man',title: '创建人',width: 100},
                {field: 'create_time',title: '创建时间',width: 150},
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
            width: 700,
            height: 500,
            cache: true,
            modal: true,
            resizable:true,
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
//                    var button = $(this);
//                    button.linkbutton('disable');
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
            title: '修改',
            width: 700,
            height: 500,
            closed: true,
            cache: true,
            modal: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var data = $('#search-form-parts-info-edit').serialize();
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
        //查看
        $('#easyui-dialog-parts-parts-info-index-see').dialog({
            title: '查看',
            width: 700,
            height: 500,
            closed: true,
            cache: true,
            modal: true,
            buttons: [{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui-dialog-parts-parts-info-index-see').dialog('close');
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
    //查看方法
    PartsInfoIndex.see = function(){
        var datagrid = $('#easyui-datagrid-parts-parts-info-index');
        var partsData = datagrid.datagrid('getSelected');
        if(partsData == null){
            $.messager.alert('查看失败','请选择查看项','error');
            return false;
        }
        var id = partsData.id;
        $('#easyui-dialog-parts-parts-info-index-see').dialog('open');
        $('#easyui-dialog-parts-parts-info-index-see').dialog('refresh','<?php echo yii::$app->urlManager->createUrl(['parts/parts-info/see']); ?>&parts='+id);
    }
    //删除配件信息
    PartsInfoIndex.del = function(){
        var datagrid = $('#easyui-datagrid-parts-parts-info-index');
        var partsData = datagrid.datagrid('getSelected');
        if(partsData == null){
            $.messager.alert('删除失败','请选择作废项','error');
            return false;
        }
        var id = partsData.id;
        $.messager.confirm('确认对话框', '确定作废配件信息？', function(r){
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
        var id = partsData.id;
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