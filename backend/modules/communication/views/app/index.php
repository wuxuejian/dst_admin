<table id="easyui-datagrid-communication-app-index"></table> 
<div id="easyui-datagrid-communication-app-index-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-communication-app-index">
                <ul class="search-main">
                    <li>
                        <div class="item-name">应用名称</div>
                        <div class="item-input">
                            <input class="easyui-textbox" name="app_name" style="width:100%;" />
                        </div>
                    </li>
                    <li class="search-button">
                        <a href="javascript:CommunicationAppIndex.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                        <a href="javascript:CommunicationAppIndex.reset()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</a>
                    </li>
                </ul>
            </form>
        </div>
    </div>

    <?php if(!empty($buttons)){ ?>
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
<form id="communication-app-index-submit-data" style="display:none"></form>
<!-- 窗口 -->
<div
    id="easyui-window-communication-app-index-iframe"
    class="easyui-window"
    title="应用操作状态"
    style="width:800px;height:400px"
    iconCls='icon-save'
    collapsible="false"
    minimizable="false"
    maximizable="false"
    closed="true"
>
    <iframe id="communication-app-index-iframe" style="width:780px;height:360px;" frameborder="none"></iframe>
</div>
<!-- 窗口 -->
<script>
    var CommunicationAppIndex = new Object();
    CommunicationAppIndex.init = function(){
        //获取列表数据
        $('#easyui-datagrid-communication-app-index').datagrid({
            method: 'get', 
            url:"<?php echo yii::$app->urlManager->createUrl(['communication/app/get-list']); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-communication-app-index-toolbar",
            pagination: true,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: false,
            frozenColumns: [[
                {field: 'ck',checkbox: true}, 
                {field: 'id',title: 'id',hidden: true},   
                {
                    field: 'app_name',title: '应用名称',width: '150px',halign: 'center',
                    sortable: true,
                    editor:{
                        type:'textbox',
                        options:{
                            required: true,
                            validType: 'length[100]'
                        }
                    }
                } 
            ]],
            columns:[[
                {
                    field: 'app_path',title: '应用目录',width: '350px',halign: 'center',
                    sortable: true,
                    editor:{
                        type:'textbox',
                        options:{
                            required: true,
                            validType: 'length[255]'
                        }
                    }
                },
                {
                    field: 'app_addr',title: '应用地址',width: '100px',halign: 'center',
                    sortable: true,
                    editor:{
                        type:'textbox',
                        options:{
                            required: true,
                            validType: 'length[100]'
                        }
                    }
                },
                {
                    field: 'app_port',title: '端口号',width: '90px',align: 'center',
                    sortable: true,
                    editor:{
                        type:'textbox',
                        options:{
                            required: true,
                            validType: 'int'
                        }
                    }
                },
                {
                    field: 'status',title: '状态',width: '100px',align: 'center',
                    formatter: function(value){
                        if(value == 1){
                            return '<b style="color:green">正常</b>';
                        }else{
                            return '<b style="color:red">异常</b>';
                        }
                    }
                },
                {field: 'response',title: '响应时间(s)',width: '150px',halign: 'center',align: 'right'}
            ]]   
        });
    }
    CommunicationAppIndex.init();
    //获取选择的记录
    //获取选择记录
    CommunicationAppIndex.getSelected = function(multiple){
        var datagrid = $('#easyui-datagrid-communication-app-index');
        if(multiple){
            selectRows = datagrid.datagrid('getSelections');
            if(selectRows.length <= 0){
                $.messager.alert('错误','请选择要操作的记录！','error');   
                return false;
            }
            return selectRows;
        }else{
            var selectRow = datagrid.datagrid('getSelected');
            if(!selectRow){
                $.messager.alert('错误','请选择要操作的记录！','error');   
                return false;
            }
            return selectRow;
        }  
    }
    //添加应用
    CommunicationAppIndex.add = function(){
        var datagrid = $('#easyui-datagrid-communication-app-index');
        datagrid.datagrid('appendRow',{       
            id: '0',
            app_name: '',
            app_path: '',
            app_addr: '',
            app_port: ''
        });
        var rows = datagrid.datagrid('getRows');
        var lastRowNum = rows.length - 1;
        var lastRow = rows[lastRowNum];
        var rowIndex = datagrid.datagrid('getRowIndex',lastRow);
        datagrid.datagrid('beginEdit',rowIndex);
        datagrid.datagrid('selectRow',rowIndex);
    }
    //修改应用
    CommunicationAppIndex.edit = function(){
        var selectRows = this.getSelected(true);
        if(!selectRows) return false;
        var datagrid = $('#easyui-datagrid-communication-app-index');
        for(var i in selectRows){
            datagrid.datagrid('beginEdit',datagrid.datagrid('getRowIndex',selectRows[i]));
        }
    }
    //保存修改
    CommunicationAppIndex.saveAddEdit = function()
    {
        var selectRows = this.getSelected(true);
        if(!selectRows) return false;
        var datagrid = $('#easyui-datagrid-communication-app-index');
        for(var i in selectRows){
            datagrid.datagrid('endEdit',datagrid.datagrid('getRowIndex',selectRows[i]));
        }
        var selectRows = this.getSelected(true);
        var html = '';
        for(var i in selectRows){
            if(selectRows[i].app_name){
                html += '<input type="text" name="id[]" value="'+selectRows[i].id+'" />';
                html += '<input type="text" name="app_name[]" value="'+selectRows[i].app_name+'" />';
                html += '<input type="text" name="app_path[]" value="'+selectRows[i].app_path+'" />';
                html += '<input type="text" name="app_addr[]" value="'+selectRows[i].app_addr+'" />';
                html += '<input type="text" name="app_port[]" value="'+selectRows[i].app_port+'" />';
            }
        }
        var form = $('#communication-app-index-submit-data');
        form.html(html);
        var data = form.serialize();
        $.ajax({
            type: 'post',
            url: "<?php echo yii::$app->urlManager->createUrl(['communication/app/add-edit']); ?>",
            data: data,
            dataType: 'json',
            success: function(data){
                $('#easyui-datagrid-communication-app-index').datagrid('reload');
                if(data.status){
                    $.messager.alert('操作成功',data.info,'info');
                    $('#easyui-datagrid-car-contract-record-edit').datagrid('reload');
                }else{
                    $.messager.alert('操作失败',data.info,'error');
                }
            }
        });
        
    }
    //删除应用
    CommunicationAppIndex.remove = function(){
        var selectRow = this.getSelected(true);
        if(!selectRow) return false;
        var ids = '';
        for(var i in selectRow){
            ids += selectRow[i].id+',';
        }
        $.messager.confirm('删除确认','您确定要删除选中的应用？',function(r){
            if(r){
                $.ajax({
                    type: 'get',
                    url: "<?php echo yii::$app->urlManager->createUrl(['communication/app/remove']); ?>",
                    data: {'ids': ids},
                    dataType: 'json',
                    success: function(data){
                        if(data.status){
                            $.messager.alert('删除成功',data.info,'info');
                            $('#easyui-datagrid-communication-app-index').datagrid('reload');
                        }else{
                            $.messager.alert('删除失败',data.info,'error');
                        }
                    }
                });
            }
        });
    }
    //启动或重启应用
    CommunicationAppIndex.startRestart = function(){
        var selectRow = this.getSelected();
        if(!selectRow) return false;
        $.messager.confirm('操作确认','您确定要启动或重启应用？',function(r){
            if(r){
                $('#easyui-window-communication-app-index-iframe').window('open');
                var iframe = document.getElementById('communication-app-index-iframe');
                $(iframe.contentWindow.document.body).html('');
                $(iframe).attr('src',"<?php echo yii::$app->urlManager->createUrl(['communication/app/start']); ?>&id="+selectRow.id);
            }
        });
    }
    //停止应用
    CommunicationAppIndex.stop = function(){
        var selectRow = this.getSelected();
        if(!selectRow) return false;
        $.messager.confirm('操作确认','您确定要停止应用？',function(r){
            if(r){
                $('#easyui-window-communication-app-index-iframe').window('open');
                var iframe = document.getElementById('communication-app-index-iframe');
                $(iframe.contentWindow.document.body).html('');
                $(iframe).attr('src',"<?php echo yii::$app->urlManager->createUrl(['communication/app/stop']); ?>&id="+selectRow.id);
            }
        });
    }
    //查看应用状态
    CommunicationAppIndex.status = function(){
        var selectRow = this.getSelected();
        if(!selectRow) return false;
        $('#easyui-window-communication-app-index-iframe').window('open');
        var iframe = document.getElementById('communication-app-index-iframe');
        $(iframe.contentWindow.document.body).html('');
        $(iframe).attr('src',"<?php echo yii::$app->urlManager->createUrl(['communication/app/status']); ?>&id="+selectRow.id);
    }
    //查看应用状态
    CommunicationAppIndex.ping = function(){
        var selectRow = this.getSelected();
        if(!selectRow) return false;
        $('#easyui-window-communication-app-index-iframe').window('open');
        var iframe = document.getElementById('communication-app-index-iframe');
        $(iframe.contentWindow.document.body).html('');
        $(iframe).attr('src',"<?php echo yii::$app->urlManager->createUrl(['communication/app/ping']); ?>&id="+selectRow.id);
    }
    //查询
    CommunicationAppIndex.search = function(){
        var data = $('#search-form-communication-app-index').serializeArray();
        var searchData = {};
        for(var i in data){
            searchData[data[i].name] = data[i].value;
        }
        $('#easyui-datagrid-communication-app-index').datagrid('load',searchData);
    }
    //重置
    CommunicationAppIndex.reset = function(){
        $('#search-form-communication-app-index').form('reset');
    }
</script>