<table id="SystemDaemonIndex_datagrid"></table> 
<div id="SystemDaemonIndex_datagridToolbar">
    <div class="easyui-panel" title="检索区域" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="SystemDaemonIndex_searchForm">
                <ul class="search-main">
                    <li>
                        <div class="item-name">进程名称</div>
                        <div class="item-input">
                            <input
                                class="easyui-textbox"
                                name="name"
                                style="width:100%;"
                                data-options="{
                                    onChange: function(){
                                        SystemDaemonIndex.search();
                                    }
                                }"
                            />
                        </div>
                    </li>
                    <li class="search-button">
                        <button class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button onclick="SystemDaemonIndex.reset();return false;" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
                    </li>
                </ul>
            </form>
        </div>
    </div>

    <?php if(!empty($buttons)){ ?>
        <div class="easyui-panel" title="进程列表" style="padding:3px 2px;width:100%;" data-options="
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
<div id="SystemDaemonIndex_addWin"></div>
<div id="SystemDaemonIndex_editWin"></div>
<!-- 窗口 -->

<script>
    var SystemDaemonIndex = {
        params: {
            url: {
                getList: "<?= yii::$app->urlManager->createUrl(['system/daemon/get-list']); ?>",
                add: "<?= yii::$app->urlManager->createUrl(['system/daemon/add']); ?>",
                edit: "<?= yii::$app->urlManager->createUrl(['system/daemon/edit']); ?>",
                remove: "<?= yii::$app->urlManager->createUrl(['system/daemon/remove']); ?>"
            }
        },
        // 初始化
        init: function () {
            //--初始化表格
            $('#SystemDaemonIndex_datagrid').datagrid({
                method: 'get',
                url: SystemDaemonIndex.params.url.getList,
                toolbar: "#SystemDaemonIndex_datagridToolbar",
                fit: true,
                border: false,
                pagination: true,
                loadMsg: '数据加载中...',
                striped: true,
                checkOnSelect: true,
                rownumbers: true,
                singleSelect: true,
                pageSize: 20,
                frozenColumns: [[
                    {field: 'ck', checkbox: true},
                    {field: 'id',title: 'ID', hidden: true},
                    {field: 'name', title: '进程名称', width: 100, halign: 'center'}
                ]],
                columns: [[
                    {field: 'script_path', title: '脚本位置', width: 300, halign: 'center', sortable: true},
                    {field: 'description', title: '任务描述', width: 180, halign: 'center', sortable: true},
                    {field: 'status', title: '状态', width: 60, align: 'center',
                        formatter:function(v){
                            switch(v){
                                case 'NORMAL':
                                    return '<span style="background-color:#05CD69;color:#fff;padding:2px 5px;">正常</span>';
                                case 'ABNORMAL':
                                    return '<span style="background-color:#FF4040;color:#fff;padding:2px 5px;">异常</span>';
                            }
                        }
                    },
                    {field: 'startTime', title: '开始时间', width: 130, align: 'center'},
                    {field: 'runTime', title: '运行时间', width: 100, halign: 'center',align: 'right'},
                    {field: 'pid', title: '进程id', width: 60, align: 'center'},
                    {field: 'memory', title: '内存消耗', width: 80, halign: 'center',align: 'right'}
                ]]
            });
            var searchForm = $('#SystemDaemonIndex_searchForm');
            searchForm.submit(function(){
                var data = {};
                var searchCondition = $(this).serializeArray();
                for(var i in searchCondition){
                    data[searchCondition[i]['name']] = $.trim(searchCondition[i]['value']);
                }
                $('#SystemDaemonIndex_datagrid').datagrid('load',data);
                return false;
            });
            //--初始化【新增】窗口
            $('#SystemDaemonIndex_addWin').dialog({
                title: '新增守护进程',
                width: 680,
                height: 250,
                closed: true,
                cache: true,
                modal: true,
                maximizable: false,
                resizable: true,
                onClose: function () {
                    $(this).dialog('clear');
                },
                buttons: [{
                    text: '确定',
                    iconCls: 'icon-ok',
                    handler: function () {
                        var easyuiForm = $('#SystemDaemonIndex_addWin_form');
                        if(!easyuiForm.form('validate')){
                            $.messager.show({
                                title: '表单验证不通过',
                                msg: '请检查表单是否填写完整或填写错误！'
                            });
                            return false;
                        }
                        $.ajax({
                            type: 'post',
                            url: SystemDaemonIndex.params.url.add,
                            data: easyuiForm.serialize(),
                            dataType: 'json',
                            success: function(rData){
                                if(rData.status){
                                    $.messager.show({
                                        title: '操作成功',
                                        msg:rData.info
                                    });
                                    $('#SystemDaemonIndex_addWin').dialog('close');
                                    $('#SystemDaemonIndex_datagrid').datagrid('reload');
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
                        $('#SystemDaemonIndex_addWin').dialog('close');
                    }
                }]
            });
            //--初始化【修改】窗口
            $('#SystemDaemonIndex_editWin').dialog({
                title: '修改守护进程',
                width: 680,
                height: 250,
                closed: true,
                cache: true,
                modal: true,
                maximizable: false,
                resizable: true,
                onClose: function () {
                    $(this).dialog('clear');
                },
                buttons: [{
                    text: '确定',
                    iconCls: 'icon-ok',
                    handler: function () {
                        var easyuiForm = $('#SystemDaemonIndex_editWin_form');
                        if(!easyuiForm.form('validate')){
                            $.messager.show({
                                title: '表单验证不通过',
                                msg: '请检查表单是否填写完整或填写错误！'
                            });
                            return false;
                        }
                        $.ajax({
                            type: 'post',
                            url: SystemDaemonIndex.params.url.edit,
                            data: easyuiForm.serialize(),
                            dataType: 'json',
                            success: function(rData){
                                if(rData.status){
                                    $.messager.show({
                                        title: '操作成功',
                                        msg:rData.info
                                    });
                                    $('#SystemDaemonIndex_editWin').dialog('close');
                                    $('#SystemDaemonIndex_datagrid').datagrid('reload');
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
                        $('#SystemDaemonIndex_editWin').dialog('close');
                    }
                }]
            });
        },
        // 获取当前所选择的记录
        getCurrentSelected: function(multiline){
            var datagrid = $('#SystemDaemonIndex_datagrid');
            var selectRows = datagrid.datagrid('getSelections');
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
            $('#SystemDaemonIndex_addWin')
                .dialog('open')
                .dialog('refresh',SystemDaemonIndex.params.url.add);
        },
        // 修改
        edit: function(id){
            id = id || (this.getCurrentSelected()).id;
            if(!id) return false;
            $('#SystemDaemonIndex_editWin')
                .dialog('open')
                .dialog('refresh', SystemDaemonIndex.params.url.edit + '&id=' + id);
        },
        // 删除
        remove: function(){
            var id = (this.getCurrentSelected()).id;
            if(!id) return false;
            $.messager.confirm('确定删除','您确定要删除所选行吗？',function(r){
                if(r){
                    $.ajax({
                        type: 'get',
                        url: SystemDaemonIndex.params.url.remove,
                        data: {id: id},
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.show({
                                    title: '操作成功',
                                    msg: data.info
                                });
                                $('#SystemDaemonIndex_datagrid').datagrid('reload');
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
            $('#SystemDaemonIndex_searchForm').submit();
        },
        // 重置
        reset: function(){
            var searchForm = $('#SystemDaemonIndex_searchForm');
            searchForm.form('reset');
            searchForm.submit();
        }
    }

    // 执行初始化函数
    SystemDaemonIndex.init();

</script>