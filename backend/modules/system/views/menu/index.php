<table id="SystemMenuIndex_treegrid"></table> 
<div id="SystemMenuIndex_treegridToolbar">
    <div class="easyui-panel" title="检索区域" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="SystemMenuIndex_searchForm">
                <ul class="search-main">
                    <li>
                        <div class="item-name">菜单名称</div>
                        <div class="item-input">
                            <input
                                class="easyui-textbox"
                                name="name"
                                style="width:100%;"
                                data-options="{
                                    onChange: function(){
                                        SystemMenuIndex.search();
                                    }
                                }"
                            />
                        </div>
                    </li>
                    <li class="search-button">
                        <button class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button onclick="SystemMenuIndex.reset();return false;" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
                    </li>
                </ul>
            </form>
        </div>
    </div>

    <?php if(!empty($buttons)){ ?>
        <div class="easyui-panel" title="菜单列表" style="padding:3px 2px;width:100%;" data-options="
        iconCls: 'icon-table-list',
        border: false
    ">
            <?php foreach($buttons as $val){ ?>
                <button onclick="<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon'] ;?>'"><?= $val['text'] ;?></button>
            <?php } ?>
        </div>
    <?php } ?>

</div>

<!-- 窗口 -->
<div id="SystemMenuIndex_addWin"></div>
<div id="SystemMenuIndex_editWin"></div>
<!-- 窗口 -->

<script>
    var SystemMenuIndex = {
        params: {
            url: {
                getList: "<?= yii::$app->urlManager->createUrl(['system/menu/get-list-data']); ?>",
                add: "<?= yii::$app->urlManager->createUrl(['system/menu/add']); ?>",
                edit: "<?= yii::$app->urlManager->createUrl(['system/menu/edit']); ?>",
                remove: "<?= yii::$app->urlManager->createUrl(['system/menu/remove']); ?>"
            }
        },
        // 初始化
        init: function () {
            //--初始化表格
            $('#SystemMenuIndex_treegrid').treegrid({
                idField: 'id',
                treeField: 'name',
                method: 'get',
                url: SystemMenuIndex.params.url.getList,
                toolbar: "#SystemMenuIndex_treegridToolbar",
                fit: true,
                border: false,
                pagination: false,
                loadMsg: '数据加载中...',
                striped: true,
                checkOnSelect: true,
                rownumbers: true,
                singleSelect: true,
                pageSize: 50,
                frozenColumns: [[
                    {field: 'ck', checkbox: true},
                    {field: 'id',title: 'ID', hidden: true},
                    {field: 'name', title: '菜单名称', width: 200, halign: 'center'}
                ]],
                columns: [[
                    {field: 'mca', title: 'MCA地址', width: 230, halign: 'center', sortable: true},
                    {field: 'target_url', title: '目标地址', width: 150, halign: 'center', sortable: true},
                    {field: 'icon_class', title: '图标样式', width: 90, halign: 'center', sortable: true},
                    {field: 'list_order', title: '排序号', align: 'center', width: 60, sortable: true},
                    {field: 'opend', title: '默认展开', width: 60, align: 'center',sortable: true,
                        formatter: function (value, row, index) {
                            if(value == 1){
                                return '<b>是</b>';
                            }else{
                                return '否'; 
                            }
                        }
                    },
                    {field: 'is_lock', title: '系统锁定', width: 60, align: 'center',sortable: true,
                        formatter: function (value, row, index) {
                            if(value == 1){
                                return '<b>是</b>';
                            }else{
                                return '否'; 
                            }
                        }
                    },
                    {field: 'note', title: '备注', width: 150, halign: 'center', sortable: true}
                ]]
            });
            $('#SystemMenuIndex_searchForm').submit(function(){
                var data = {};
                var searchCondition = $(this).serializeArray();
                for(var i in searchCondition){
                    data[searchCondition[i]['name']] = $.trim(searchCondition[i]['value']);
                }
                $('#SystemMenuIndex_treegrid').treegrid('load',data);
                return false;
            });
            //--初始化【新增】窗口
            $('#SystemMenuIndex_addWin').dialog({
                title: '新增菜单',
                width: 680,
                height: 360,
                closed: true,
                cache: true,
                modal: true,
                maximizable: false,
                resizable: false,
                onClose: function () {
                    $(this).dialog('clear');
                },
                buttons: [{
                    text: '确定',
                    iconCls: 'icon-ok',
                    handler: function () {
                        var easyuiForm = $('#SystemMenuIndex_addWin_form');
                        if(!easyuiForm.form('validate')){
                            $.messager.show({
                                title: '表单验证不通过',
                                msg: '请检查表单是否填写完整或填写错误！'
                            });
                            return false;
                        }
                        $.ajax({
                            type: 'post',
                            url: SystemMenuIndex.params.url.add,
                            data: easyuiForm.serialize(),
                            dataType: 'json',
                            success: function(rData){
                                if(rData.status){
                                    $.messager.show({
                                        title: '操作成功',
                                        msg:rData.info
                                    });
                                    $('#SystemMenuIndex_addWin').dialog('close');
                                    $('#SystemMenuIndex_treegrid').treegrid('reload');
                                }else{
                                    $.messager.alert('错误',rData.info,'error');
                                }
                            }
                        });
                    }
                }, {
                    text: '取消',
                    iconCls: 'icon-cancel',
                    handler: function () {
                        $('#SystemMenuIndex_addWin').dialog('close');
                    }
                }]
            });
            //--初始化【修改】窗口
            $('#SystemMenuIndex_editWin').dialog({
                title: '修改菜单',
                width: 680,
                height: 380,
                closed: true,
                cache: true,
                modal: true,
                maximizable: false,
                resizable: false,
                onClose: function () {
                    $(this).dialog('clear');
                },
                buttons: [{
                    text: '确定',
                    iconCls: 'icon-ok',
                    handler: function () {
                        var easyuiForm = $('#SystemMenuIndex_editWin_form');
                        if(!easyuiForm.form('validate')){
                            $.messager.show({
                                title: '表单验证不通过',
                                msg: '请检查表单是否填写完整或填写错误！'
                            });
                            return false;
                        }
                        $.ajax({
                            type: 'post',
                            url: SystemMenuIndex.params.url.edit,
                            data: easyuiForm.serialize(),
                            dataType: 'json',
                            success: function(rData){
                                if(rData.status){
                                    $.messager.show({
                                        title: '操作成功',
                                        msg:rData.info
                                    });
                                    $('#SystemMenuIndex_editWin').dialog('close');
                                    $('#SystemMenuIndex_treegrid').treegrid('reload');
                                }else{
                                    $.messager.alert('错误',rData.info,'error');
                                }
                            }
                        });
                    }
                }, {
                    text: '取消',
                    iconCls: 'icon-cancel',
                    handler: function () {
                        $('#SystemMenuIndex_editWin').dialog('close');
                    }
                }]
            });
        },
        // 获取当前所选择的记录
        getCurrentSelected: function(multiline){
            var treegrid = $('#SystemMenuIndex_treegrid');
            var selectRows = treegrid.treegrid('getSelections');
            if(selectRows.length <= 0){
                $.messager.show({
                    title: '请选择',
                    msg: '请先选择要操作的记录！'
                });
                return false;
            }
            if(multiline){
                return selectRows;
            }else{
                if(selectRows.length > 1){
                    $.messager.show({
                        title: '提醒',
                        msg: '该功能不能批量操作！<br/>如果你选择了多条记录，则默认操作的是第一条记录！'
                    });
                }
                return selectRows[0];
            }
        },
        // 添加
        add: function(){
            var selectRow = $('#SystemMenuIndex_treegrid').treegrid('getSelected');
            var id = 0;
            if(selectRow) id = selectRow.id;
            $('#SystemMenuIndex_addWin')
                .dialog('open')
                .dialog('refresh',SystemMenuIndex.params.url.add + '&id=' + id);
        },
        // 修改
        edit: function(id){
            id = id || (this.getCurrentSelected()).id;
            if(!id) return false;
            $('#SystemMenuIndex_editWin')
                .dialog('open')
                .dialog('refresh', SystemMenuIndex.params.url.edit + '&id=' + id);
        },
        // 删除
        remove: function(){
            var id = (this.getCurrentSelected()).id;
            if(!id) return false;
            $.messager.confirm('确定删除','您确定要删除所选行吗？',function(r){
                if(r){
                    $.ajax({
                        type: 'get',
                        url: SystemMenuIndex.params.url.remove,
                        data: {id: id},
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.show({
                                    title: '操作成功',
                                    msg: data.info
                                });
                                $('#SystemMenuIndex_treegrid').treegrid('reload');
                            }else{
                                $.messager.alert('操作失败',data.info,'error');
                            }
                        }
                    });
                }
            });
        },
        // 查询
        search: function(){
            $('#SystemMenuIndex_searchForm').submit();
        },
        // 重置
        reset: function(){
            var searchForm = $('#SystemMenuIndex_searchForm');
            searchForm.form('reset');
            searchForm.submit();
        }
    }

    // 执行初始化函数
    SystemMenuIndex.init();

</script>