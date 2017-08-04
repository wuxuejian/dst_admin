<table id="easyui-datagrid-system-backup-import-backup-index"></table>
<div id="easyui-datagrid-system-backup-import-backup-index-toolbar">
    <div
        class="easyui-panel"
        title="备份检索"
        border="false"
        iconCls='icon-search'
    >
        <div class="data-search-form">
            <form id="search-form-system-backup-import-backup-index">
                <ul class="search-main">
                    <li>
                        <div class="item-name">备份类型</div>
                        <div class="item-input">
                            <select class="easyui-combobox" name="backup_type" style="width:150px;">
                                <option value="">不限</option>
                                <option value="file">程序文件</option>
                                <option value="database">数据库文件</option>
                            </select>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">备份日期</div>
                        <div class="item-input">
                            <input class="easyui-datebox" type="text" name="backup_date_start" style="width:150px;" validType='date'></input>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">备份日期</div>
                        <div class="item-input" validType='date'>
                            <input class="easyui-datebox" type="text" name="backup_date_end" style="width:150px;"></input>
                        </div>
                    </li>
                    <li class="search-button">
                        <a onclick="BackupImportBackupIndex.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                    </li>
                </ul>
            </form>
        </div>
    </div>
    <div class="easyui-panel" title="备份列表" border="false" iconCls='icon-tip' style="width:100%;"></div>
    <div style="padding:8px 4px">
        <?php foreach($buttons as $val){ ?>
        <a onclick="<?php echo $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?php echo $val['icon']; ?>'"><?php echo $val['text']; ?></a>
        <?php } ?>
    </div>
</div>
<!-- 窗口 -->
<div
    id="easyui-window-system-backup-import-backup-index"
    class="easyui-window"
    title="备份"
    width='600px'   
    height='400px'
    collapsible="false"
    minimizable="false"
    maximizable="false"
    closed="true"
    shadow="true"
    modal="true"
>
    <iframe
        id="iframe-system-backup-import-backup-index"
        frameborder="none"
        style="width:100%;height:100%;"
    ></iframe>
</div>
<!-- 窗口 -->
<script>
    var BackupImportBackupIndex = new Object();
    BackupImportBackupIndex.init = function(){
        //获取列表数据
        $('#easyui-datagrid-system-backup-import-backup-index').datagrid({  
            method: 'get', 
            url:"<?php echo yii::$app->urlManager->createUrl(['system/backup-import/get-list']); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-system-backup-import-backup-index-toolbar",
            pagination: true,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
            frozenColumns: [[
                {field: 'ck',checkbox: true}, 
                {field: 'id',title: 'id',hidden: true},   
                {
                    field: 'backup_type',title: '类型',width: 100,sortable: true,
                    formatter: function(value){
                        if(value == 'DB'){
                            return '数据库文件';
                        }else{
                            return '程序文件';
                        }
                    }
                },
                {field: 'file_name',title: '文件名称',width: 200,align: 'left',sortable: true}
            ]],
            columns:[[
                {
                    field: 'file_size',title: '文件大小',width: 100,align: 'left',
                    formatter: function(value){
                        return BackupImportBackupIndex.fileSize(value);
                    }
                },
                {
                    field: 'backup_datetime',title: '备份日期',width: 140,align: 'center',
                    sortable: true,
                    formatter: function(value){
                        if(value > 0){
                            return formatDateToString(value,true);
                        }
                    }
                }
            ]]
        });
    }
    BackupImportBackupIndex.init();
    //计算文件大小
    BackupImportBackupIndex.fileSize = function(size){
        var unit = [' byte',' kb',' mb',' gb'];
        var times = 0;
        while(size > 1024){
            size /= 1024;
            times ++;
        }
        return parseInt(size*100) / 100 + unit[times];
    }
    //获取当前选中记录
    BackupImportBackupIndex.getSelected = function(){
        var datagrid = $('#easyui-datagrid-system-backup-import-backup-index');
        var selectRow = datagrid.datagrid('getSelected');
        if(!selectRow){
            $.messager.alert('错误','请选择要操作的记录','error');   
            return false;
        }
        return selectRow;
    }
    //一键备份
    BackupImportBackupIndex.backupAll = function(){
        var easyuiWindow = $('#easyui-window-system-backup-import-backup-index');
        easyuiWindow.window('open');
        var iframe = document.getElementById('iframe-system-backup-import-backup-index');
        $(iframe.contentWindow.document.body).html('');
        $(iframe).attr('src',"<?php echo yii::$app->urlManager->createUrl(['system/backup-import/backup-all']); ?>");
    }
    //程序文件备份
    BackupImportBackupIndex.backupFile = function(){
        var easyuiWindow = $('#easyui-window-system-backup-import-backup-index');
        easyuiWindow.window('open');
        var iframe = document.getElementById('iframe-system-backup-import-backup-index');
        $(iframe.contentWindow.document.body).html('');
        $(iframe).attr('src',"<?php echo yii::$app->urlManager->createUrl(['system/backup-import/backup-file']); ?>");
    }
    //数据库备份
    BackupImportBackupIndex.backupDatabase = function(){
        var easyuiWindow = $('#easyui-window-system-backup-import-backup-index');
        easyuiWindow.window('open');
        var iframe = document.getElementById('iframe-system-backup-import-backup-index');
        $(iframe.contentWindow.document.body).html('');
        $(iframe).attr('src',"<?php echo yii::$app->urlManager->createUrl(['system/backup-import/backup-database']); ?>");
    }
    //删除备份
    BackupImportBackupIndex.fileRemove = function(){
        var selectRow = this.getSelected();
        if(!selectRow) return false;
        var id = selectRow.id;
        $.messager.confirm('删除确定','您确认要删除所选择的备份？',function(r){
            if(r){
                $.ajax({
                    type: 'post',
                    url: "<?php echo yii::$app->urlManager->createUrl(['system/backup-import/backup-del']); ?>",
                    data: {"id": id},
                    dataType: 'json',
                    success: function(data){
                        if(data.status){
                            $.messager.alert('删除成功',data.info,'info');
                            $('#easyui-datagrid-system-backup-import-backup-index').datagrid('reload');
                        }else{
                            $.messager.alert('删除失败',data.info,'error');
                        }
                    }
                });
            }
        });
    }
    //备份下载
    BackupImportBackupIndex.fileDownload = function(){
        var selectRow = this.getSelected();
        if(!selectRow) return false;
        window.open("<?php echo yii::$app->urlManager->createUrl(['system/backup-import/backup-download']); ?>&id="+selectRow.id);
    }
    //查询
    BackupImportBackupIndex.search = function(){
        var form = $('#search-form-system-backup-import-backup-index');
        if(!form.form('validate')) return false;
        var data = {};
        var searchCondition = form.serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        $('#easyui-datagrid-system-backup-import-backup-index').datagrid('load',data);
    }
</script>