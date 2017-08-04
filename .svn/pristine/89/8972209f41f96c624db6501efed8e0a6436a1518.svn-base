<table id="easyui-datagrid-parts-parts-kind-index"></table>
<div id="easyui-datagrid-parts-parts-kind-index-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">
        <div class="data-search-form">
            <form id="search-form-parts-kind-index" method="post">
                <ul class="search-main">
                    <li>
                        <div class="item-name">配件类别</div>
                        <div class="item-input">
                            <input class="easyui-textbox" name="parts_type" style="width:150px;">
                        </div>
                    </li>
                    <li>
                        <div class="item-name">配件种类</div>
                        <div class="item-input">
                            <input class="easyui-textbox" name="parts_kind" style="width:150px;">
                        </div>
                    </li>
                    <li class="search-button">
                        <button type="submit" onclick="PartsKindIndex.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button type="submit" onclick="PartsKindIndex.resetForm()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
                    </li>
                </ul>
            </form>
        </div>
    </div>
        <div class="easyui-panel" title="数据列表" style="padding:3px 2px;width:100%;" data-options="
        iconCls: 'icon-table-list',
        border: false
    ">
            <button onclick="PartsKindIndex.add()" class="easyui-linkbutton" data-options="iconCls:'icon-add'">添加配件类别</button>
            <button onclick="PartsKindIndex.add_kind()" class="easyui-linkbutton" data-options="iconCls:'icon-add'">添加配件种类</button>
            <button onclick="PartsKindIndex.edit()" class="easyui-linkbutton" data-options="iconCls:'icon-edit'">修改配件</button>
            <button onclick="PartsKindIndex.del()" class="easyui-linkbutton" data-options="iconCls:'icon-cancel'">删除配件</button>
        </div>
</div>
<!-- 窗口 -->
<div id="easyui-dialog-parts-parts-kind-index-add"></div>
<div id="easyui-dialog-parts-parts-kind-index-add-kind"></div>
<div id="easyui-dialog-parts-parts-kind-index-edit"></div>
<div id="easyui-dialog-parts-parts-kind-index-del"></div>
<script>
    var PartsKindIndex = new Object();
    PartsKindIndex.init = function(){
        //获取列表数据
        $('#easyui-datagrid-parts-parts-kind-index').datagrid({
            method: 'post',
            url:'<?php echo yii::$app->urlManager->createUrl(['parts/parts-kind/get-list']); ?>',
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-parts-parts-kind-index-toolbar",
            pagination: true,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
            columns:[[
                {field: 'ck',checkbox: true},
                {field: 'parents_name',title: '配件类别',width: 100,
                    formatter: function(value,row,index){
                        if(row.parents_id==0){
                            return row.parts_name;
                        }else{
                            return row.parents_name;
                        }
                    }
                },
                {field: 'parts_name',title: '配件种类',width:150,
                    formatter: function(value,row,index){
                        if(row.parents_id == 0){
                            return ' ';
                        }else{
                            return row.parts_name;
                        }
                    }
                },
                {field: 'note',title: '备注信息',width: 150},
                {field: 'last_time',title: '最后操作时间',width: 180},
            ]]
        });
        //初始化添加窗口`
        $('#easyui-dialog-parts-parts-kind-index-add').dialog({
            title: '添加配件类别信息',
            width: 900,
            height: 200,
            cache: true,
            modal: true,
            closed: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var data = $('#search-form-parts-kind-add').serialize();
//                    alert(console.log(data));return false;
                    var button = $(this);
                    button.linkbutton('disable');
                    $.ajax({
                        type: 'post',
                        url: '<?php echo yii::$app->urlManager->createUrl(['parts/parts-kind/add']); ?>',
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status == 1){
                                $.messager.alert('添加成功',data.info,'info');
                                $('#easyui-dialog-parts-parts-kind-index-add').dialog('close');
                                $('#easyui-datagrid-parts-parts-kind-index').datagrid('reload');
                                button.linkbutton('enable');
                            }else if(data.status == 2){
                                $.messager.alert('添加失败',data.info,'error');
                                button.linkbutton('enable');
                            }else{
                                $.messager.alert('添加失败',data.info,'error');
                                button.linkbutton('enable');
                            }
                        }
                    });
                    $('#easyui-datagrid-parts-parts-kind-index').datagrid('reload');
                }
            },{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui-dialog-parts-parts-kind-index-add').dialog('close');
                }
            }]
        });
        //初始化修改窗口
        $('#easyui-dialog-parts-parts-kind-index-add-kind').dialog({
            title: '添加配件种类',
            width: 900,
            height: 200,
            closed: true,
            cache: true,
            modal: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var data = $('#search-form-parts-kind-add-kind').serialize();
                    var button = $(this);
                    button.linkbutton('disable');
                    $.ajax({
                        type: 'post',
                        url: '<?php echo yii::$app->urlManager->createUrl(['parts/parts-kind/add-kind']); ?>',
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status == 1){
                                $.messager.alert('添加成功',data.info,'info');
                                $('#easyui-dialog-parts-parts-kind-index-add-kind').dialog('close');
                                $('#easyui-datagrid-parts-parts-kind-index').datagrid('reload');
                                button.linkbutton('enable');
                            }else if(data.status == 2){
                                $.messager.alert('添加失败',data.info,'error');
                                button.linkbutton('enable');
                            }else{
                                $.messager.alert('添加失败',data.info,'error');
                                button.linkbutton('enable');
                            }
                        }
                    });
                }
            },{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui-dialog-parts-parts-kind-index-add-kind').dialog('close');
                }
            }]
        });
        $('#easyui-dialog-parts-parts-kind-index-edit').dialog({
            title: '编辑配件',
            width: 900,
            height: 200,
            closed: true,
            resizable:true,
            cache: true,
            modal: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var data = $('#search-form-parts-kind-edit').serialize();
                    var button = $(this);
                    button.linkbutton('disable');
                    $.ajax({
                        type: 'post',
                        url: '<?php echo yii::$app->urlManager->createUrl(['parts/parts-kind/edit']); ?>',
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status == 1){
                                $.messager.alert('修改成功',data.info,'info');
                                $('#easyui-dialog-parts-parts-kind-index-edit').dialog('close');
                                $('#easyui-datagrid-parts-parts-kind-index').datagrid('reload');
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
                    $('#easyui-dialog-parts-parts-kind-index-edit').dialog('close');
                }
            }]
        });
    }
    PartsKindIndex.init();
    //添加配件类型
    PartsKindIndex.add = function(){
        $('#easyui-dialog-parts-parts-kind-index-add').dialog('open');
        $('#easyui-dialog-parts-parts-kind-index-add').dialog('refresh','<?php echo yii::$app->urlManager->createUrl(['parts/parts-kind/add']); ?>');
    }
    //添加配件种类
    PartsKindIndex.add_kind = function(){
        $('#easyui-dialog-parts-parts-kind-index-add-kind').dialog('open');
        $('#easyui-dialog-parts-parts-kind-index-add-kind').dialog('refresh','<?php echo yii::$app->urlManager->createUrl(['parts/parts-kind/add-kind']); ?>');
    }
    //编辑配件信息
    PartsKindIndex.edit = function(){
        var datagrid = $('#easyui-datagrid-parts-parts-kind-index');
        var partsData = datagrid.datagrid('getSelected');
        if(partsData == null){
            $.messager.alert('修改失败','请选择修改项','error');
            return false;
        }
        var id = partsData.id;
        var parents_id = partsData.parents_id;
        $('#easyui-dialog-parts-parts-kind-index-edit').dialog('open');
        $('#easyui-dialog-parts-parts-kind-index-edit').dialog('refresh','<?php echo yii::$app->urlManager->createUrl(['parts/parts-kind/edit']); ?>&id='+id+'&parents_id='+parents_id);
    }
    //删除配件信息
    PartsKindIndex.del = function(){
        var datagrid = $('#easyui-datagrid-parts-parts-kind-index');
        var partsData = datagrid.datagrid('getSelected');
        if(partsData == null){
            $.messager.alert('删除失败','请选择删除项','error');
            return false;
        }
        $.messager.confirm('删除','是否删除当前选项',function (r) {
            var id = partsData.id;
            if(r){
                $.ajax({
                    type: 'post',
                    url: '<?php echo yii::$app->urlManager->createUrl(['parts/parts-kind/del']); ?>',
                    data: {'id':id},
                    dataType: 'json',
                    success: function(data){
                        if(data.status == 1){
                            $.messager.alert('删除成功',data.info,'info');
                            $('#easyui-datagrid-parts-parts-kind-index').datagrid('reload');
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
    //构建查询表单
    var searchForm = $('#search-form-parts-kind-index');
    /**查询表单提交事件**/
    searchForm.submit(function(){
        var data = {};
        var searchCondition = $(this).serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        $('#easyui-datagrid-parts-parts-kind-index').datagrid('load',data);
        return false;
    });

    //重置查询表单
    PartsKindIndex.resetForm = function(){
        var easyuiForm = $('#search-form-parts-kind-index');
        easyuiForm.form('reset');
    }

    //条件搜索查询
    PartsKindIndex.search = function(){
        var form = $('#search-form-parts-kind-index');
        var data = {};
        var searchCondition = form.serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        $('#easyui-datagrid-parts-parts-kind-index').datagrid('load',data);
    }
</script>