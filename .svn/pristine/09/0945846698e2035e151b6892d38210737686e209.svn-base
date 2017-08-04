<table id="easyui-datagrid-communication-author-index"></table> 
<div id="easyui-datagrid-communication-author-index-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-communication-author-index">
                <ul class="search-main">
                    <li>
                        <div class="item-name">账号</div>
                        <div class="item-input">
                            <input class="easyui-textbox" name="count" style="width:100%;"  />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">公司名称</div>
                        <div class="item-input">
                            <input class="easyui-textbox" name="company_name" style="width:100%;"  />
                        </div>
                    </li>
                    <li class="search-button">
                        <a href="javascript:CommunicationAuthorIndex.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                        <a href="javascript:CommunicationAuthorIndex.reset()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</a>
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
<!-- 窗口 -->
<div id="easyui-dialog-communication-author-index-add"></div>
<div id="easyui-dialog-communication-author-index-edit"></div>
<div id="easyui-dialog-communication-author-index-password-edit"></div>
<!-- 窗口 -->
<script>
    var CommunicationAuthorIndex = new Object();
    CommunicationAuthorIndex.init = function(){
        //获取列表数据
        $('#easyui-datagrid-communication-author-index').datagrid({
            method: 'get', 
            url:"<?php echo yii::$app->urlManager->createUrl(['communication/author/get-list']); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-communication-author-index-toolbar",
            pagination: true,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
            frozenColumns: [[
                {field: 'ck',checkbox: true}, 
                {field: 'id',title: 'id',hidden: true},   
                {field: 'count',title: '账号',width: '220px',align: 'center',sortable: true}
            ]],
            columns:[[
                {
                    field: 'company_name',title: '公司名称',width: '200px',halign: 'center',sortable: true},
                {field: 'client_id',title: '连接号',width: '70px',align: 'center',sortable: true},
                {field: 'client_ip',title: '连接IP',width: '100px',halign: 'center',sortable: true},
                {
                    field: 'connect_datetime',title: '连接时间',width: '120px',
                    align: 'center',sortable: true,
                    formatter: function(value,row,index){
                        if(parseInt(value) > 0){
                            return formatDateToString(value,true);
                        }
                    }
                },
                {field: 'connect_times',title: '连接次数',width: '70px',align: 'center',sortable: true},
                {
                    field: 'is_online',title: '是否连接',width: '70px',align: 'center',
                    sortable: true,
                    formatter: function(value){
                        if(parseInt(value) == 0){
                            return '<span style="color:red">否</span>';
                        }else{
                            return '<span style="color:green">是</span>';
                        }
                    }
                },
                {field: 'note',title: '备注',width: '300px',halign: 'center'}
            ]]   
        });
        //初始化添加通讯账号窗口
        $('#easyui-dialog-communication-author-index-add').dialog({
            title: '添加通讯账号',   
            width: 750,   
            height: 250,   
            closed: false,   
            cache: true,   
            modal: true,
            closed: true,
            maximizable: false,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var form = $('#easyui-form-communication-author-add');
                    if(!form.form('validate')){
                        return false;
                    }
                    var requestData = form.serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['communication/author/add']); ?>",
                        data: requestData,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('添加成功',data.info,'info');
                                $('#easyui-datagrid-communication-author-index').datagrid('reload');
                                $('#easyui-dialog-communication-author-index-add').dialog('close');
                            }else{
                                $.messager.alert('添加失败',data.info,'error');
                            }
                        }
                    });
                }
            },{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui-dialog-communication-author-index-add').dialog('close');
                }
            }],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
        //初始化修改通讯账号窗口
        $('#easyui-dialog-communication-author-index-edit').dialog({
            title: '修改通讯账号',   
            width: 750,   
            height: 250,   
            closed: false,   
            cache: true,   
            modal: true,
            closed: true,
            maximizable: false,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var form = $('#easyui-form-communication-author-edit');
                    if(!form.form('validate')){
                        return false;
                    }
                    var requestData = form.serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['communication/author/edit']); ?>",
                        data: requestData,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('修改成功',data.info,'info');
                                $('#easyui-datagrid-communication-author-index').datagrid('reload');
                                $('#easyui-dialog-communication-author-index-edit').dialog('close');
                            }else{
                                $.messager.alert('修改失败',data.info,'error');
                            }
                        }
                    });
                }
            },{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui-dialog-communication-author-index-edit').dialog('close');
                }
            }],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
    }
    CommunicationAuthorIndex.init();
    //获取选择的记录
    CommunicationAuthorIndex.getSelected = function(){
        var datagrid = $('#easyui-datagrid-communication-author-index');
        var selectRow = datagrid.datagrid('getSelected');
        if(!selectRow){
            $.messager.alert('错误','请选择要操作的记录','error');   
            return false;
        }
        return selectRow;
    }
    //添加账号
    CommunicationAuthorIndex.add = function(){
        $('#easyui-dialog-communication-author-index-add').dialog('open');
        $('#easyui-dialog-communication-author-index-add').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['communication/author/add']); ?>");
    }
    //修改账号
    CommunicationAuthorIndex.edit = function(){
        var selectRow = this.getSelected();
        if(!selectRow) return false;
        $('#easyui-dialog-communication-author-index-edit').dialog('open');
        $('#easyui-dialog-communication-author-index-edit').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['communication/author/edit']); ?>&id="+selectRow.id);
    }
    //删除账号
    CommunicationAuthorIndex.remove = function(){
        var selectRow = this.getSelected();
        if(!selectRow) return false;
        $.messager.confirm('删除确认','您确定要删除选中的账号？',function(r){
            if(r){
                $.ajax({
                    type: 'get',
                    url: "<?php echo yii::$app->urlManager->createUrl(['communication/author/remove']); ?>",
                    data: {'id':selectRow.id},
                    dataType: 'json',
                    success: function(data){
                        if(data.status){
                                $.messager.alert('删除成功',data.info,'info');
                                $('#easyui-datagrid-communication-author-index').datagrid('reload');
                            }else{
                                $.messager.alert('删除失败',data.info,'error');
                            }
                    }
                });
            }
        });
    }
    //查询
    CommunicationAuthorIndex.search = function(){
        var data = $('#search-form-communication-author-index').serializeArray();
        var searchData = {};
        for(var i in data){
            searchData[data[i].name] = data[i].value;
        }
        $('#easyui-datagrid-communication-author-index').datagrid('load',searchData);
    }
    //重置
    CommunicationAuthorIndex.reset = function(){
        $('#search-form-communication-author-index').form('reset');
    }

</script>