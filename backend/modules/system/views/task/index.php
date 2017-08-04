<table id="SystemTaskIndex_datagrid"></table> 
<div id="SystemTaskIndex_datagridToolbar">
    <div class="easyui-panel" title="检索区域" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="SystemTaskIndex_searchForm">
                <ul class="search-main">
                    <li>
                        <div class="item-name">任务名称</div>
                        <div class="item-input">
                            <input
                                class="easyui-textbox"
                                name="name"
                                style="width:100%;"
                                data-options="{
                                    onChange: function(){
                                        SystemTaskIndex.search();
                                    }
                                }"
                            />
                        </div>
                    </li>
                    <li class="search-button">
                        <button class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button onclick="SystemTaskIndex.reset();return false;" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
                    </li>
                </ul>
            </form>
        </div>
    </div>

    <?php if(!empty($buttons)){ ?>
        <div class="easyui-panel" title="任务列表" style="padding:3px 2px;width:100%;" data-options="
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
<div id="SystemTaskIndex_addWin"></div>
<div id="SystemTaskIndex_editWin"></div>
<!-- 窗口 -->

<script>
    var SystemTaskIndex = {
        params: {
            CONFIG: <?php echo json_encode($config); ?>,
            url: {
                getList: "<?= yii::$app->urlManager->createUrl(['system/task/get-list-data']); ?>",
                add: "<?= yii::$app->urlManager->createUrl(['system/task/add']); ?>",
                edit: "<?= yii::$app->urlManager->createUrl(['system/task/edit']); ?>",
                remove: "<?= yii::$app->urlManager->createUrl(['system/task/remove']); ?>"
            }
        },
        // 初始化
        init: function () {
            //--初始化表格
            $('#SystemTaskIndex_datagrid').datagrid({
                method: 'get',
                url: SystemTaskIndex.params.url.getList,
                toolbar: "#SystemTaskIndex_datagridToolbar",
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
                    {field: 'name', title: '任务名称', width: 180, halign: 'center'}
                ]],
                columns: [[
                    {field: 'exec_command', title: '执行命令', width: 400, halign: 'center', sortable: true},
                    {field: 'exec_frequency', title: '执行频率', width: 150, halign: 'center', sortable: true,
                        formatter: function(value){
                            var exec_frequency = SystemTaskIndex.params.CONFIG.exec_frequency;
                            if(exec_frequency.hasOwnProperty(value)){
                                return exec_frequency[value].text;
                            }else{
                                return value;
                            }
                        }
                    },
                    {field: 'in_use', title: '是否启用', width: 70, align: 'center', sortable: true,
                        formatter: function(value){
                            return parseInt(value)==1 ? '是' : '<span style="color:#ccc">否</span>';
                        }
                    },
                    {field: 'pid', title: '任务进程id', width: 80, align: 'center',sortable: true},
                    {field: 'last_exec_datetime', title: '任务上次执行时间', width: 130, align: 'center',sortable: true}
                ]]
            });
            var searchForm = $('#SystemTaskIndex_searchForm');
            searchForm.submit(function(){
                var data = {};
                var searchCondition = $(this).serializeArray();
                for(var i in searchCondition){
                    data[searchCondition[i]['name']] = $.trim(searchCondition[i]['value']);
                }
                $('#SystemTaskIndex_datagrid').datagrid('load',data);
                return false;
            });
            //--初始化【新增】窗口
            $('#SystemTaskIndex_addWin').dialog({
                title: '新增计划任务',
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
                        var easyuiForm = $('#SystemTaskIndex_addWin_form');
                        if(!easyuiForm.form('validate')){
                            $.messager.show({
                                title: '表单验证不通过',
                                msg: '请检查表单是否填写完整或填写错误！'
                            });
                            return false;
                        }
                        $.ajax({
                            type: 'post',
                            url: SystemTaskIndex.params.url.add,
                            data: easyuiForm.serialize(),
                            dataType: 'json',
                            success: function(rData){
                                if(rData.status){
                                    $.messager.show({
                                        title: '操作成功',
                                        msg:rData.info
                                    });
                                    $('#SystemTaskIndex_addWin').dialog('close');
                                    $('#SystemTaskIndex_datagrid').datagrid('reload');
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
                        $('#SystemTaskIndex_addWin').dialog('close');
                    }
                }]
            });
            //--初始化【修改】窗口
            $('#SystemTaskIndex_editWin').dialog({
                title: '修改计划任务',
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
                        var easyuiForm = $('#SystemTaskIndex_editWin_form');
                        if(!easyuiForm.form('validate')){
                            $.messager.show({
                                title: '表单验证不通过',
                                msg: '请检查表单是否填写完整或填写错误！'
                            });
                            return false;
                        }
                        $.ajax({
                            type: 'post',
                            url: SystemTaskIndex.params.url.edit,
                            data: easyuiForm.serialize(),
                            dataType: 'json',
                            success: function(rData){
                                if(rData.status){
                                    $.messager.show({
                                        title: '操作成功',
                                        msg:rData.info
                                    });
                                    $('#SystemTaskIndex_editWin').dialog('close');
                                    $('#SystemTaskIndex_datagrid').datagrid('reload');
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
                        $('#SystemTaskIndex_editWin').dialog('close');
                    }
                }]
            });
        },
        // 获取当前所选择的记录
        getCurrentSelected: function(multiline){
            var datagrid = $('#SystemTaskIndex_datagrid');
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
            $('#SystemTaskIndex_addWin')
                .dialog('open')
                .dialog('refresh',SystemTaskIndex.params.url.add);
        },
        // 修改
        edit: function(id){
            id = id || (this.getCurrentSelected()).id;
            if(!id) return false;
            $('#SystemTaskIndex_editWin')
                .dialog('open')
                .dialog('refresh', SystemTaskIndex.params.url.edit + '&id=' + id);
        },
        // 删除
        remove: function(){
            var id = (this.getCurrentSelected()).id;
            if(!id) return false;
            $.messager.confirm('确定删除','您确定要删除所选行吗？',function(r){
                if(r){
                    $.ajax({
                        type: 'get',
                        url: SystemTaskIndex.params.url.remove,
                        data: {id: id},
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.show({
                                    title: '操作成功',
                                    msg: data.info
                                });
                                $('#SystemTaskIndex_datagrid').datagrid('reload');
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
            $('#SystemTaskIndex_searchForm').submit();
        },
        // 重置
        reset: function(){
            var searchForm = $('#SystemTaskIndex_searchForm');
            searchForm.form('reset');
            searchForm.submit();
        }
    }

    // 执行初始化函数
    SystemTaskIndex.init();

</script>