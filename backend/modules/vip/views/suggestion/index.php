<table id="easyui-datagrid-vip-suggestion-index"></table> 
<div id="easyui-datagrid-vip-suggestion-index-toolbar">
    <div class="easyui-panel" title="检索区域" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-from-vip-suggestion-index">
                <ul class="search-main">
                    <li>
                        <div class="item-name">意见建议编号</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="vs_code" style="width:100%;"  />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">会员编号</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="vip_code" style="width:100%;"  />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">会员手机号</div>
                        <div class="item-input">
                           <input class="easyui-textbox" type="text" name="vip_mobile" style="width:100%;"  />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">提议时间</div>
                        <div class="item-input">
                            <input class="easyui-datebox" type="text" name="vs_time_start" style="width:90px;"  /> -
                            <input class="easyui-datebox" type="text" name="vs_time_end" style="width:90px;"  />
                        </div>               
                    </li>
                    <li>
                        <div class="item-name">回复管理员</div>
                        <div class="item-input">
                           <input class="easyui-textbox" type="text" name="admin_username" style="width:100%;"  />
                        </div>
                    </li>
                    <li class="search-button">
                        <a id="search-button" href="javascript:VipSuggestionIndex.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                        <a id="reset-button" href="javascript:VipSuggestionIndex.reset()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</a>
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
                <a href="javascript:<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon'] ;?>'"><?= $val['text'] ;?></a>
            <?php } ?>
        </div>
    <?php } ?>

</div>
<div id="easyui-window-vip-suggestion-index-scan"></div>
<div id="easyui-dialog-vip-suggestion-index-edit"></div>
<div id="easyui-dialog-vip-suggestion-index-reply"></div>
<script>
    var VipSuggestionIndex = new Object();
    VipSuggestionIndex.init = function(){
        //获取列表数据
        $('#easyui-datagrid-vip-suggestion-index').datagrid({
            method: 'get', 
            url:"<?= yii::$app->urlManager->createUrl(['vip/suggestion/get-list']); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-vip-suggestion-index-toolbar",
            pagination: true,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
            pageSize: 20,
            frozenColumns: [[
                {field: 'ck',checkbox: true}, 
                {field: 'vs_id',title: 'vs_id',width:40,align:'center',hidden:true},   
                {field: 'vs_code',title: '意见建议编号',width: 150,align:'center',sortable:true},   
            ]],
            columns:[[
                {field: 'vs_title',title: '主题',width: 200,align:'left',sortable:true},
                {field: 'vs_time',title: '提议时间',width: 130,align:'center',sortable:true},
                {field: 'vs_content',title: '内容',width: 400,align:'left',sortable:true},
                {field: 'vip_code',title: '会员编号',width: 160,align:'center',sortable:true},
                {field: 'vip_mobile',title: '会员手机号',width: 100,align:'center',sortable:true},
                {field: 'admin_username',title: '回复管理员',width: 100,align:'center',sortable:true},
                {field: 'vs_respond_time',title: '回复时间',width: 130,align:'center',sortable:true},
                {field: 'vs_respond_txt',title: '回复内容',width: 400,align:'left',sortable:true},
                {field: 'vs_mark',title: '备注',width: 400,align:'left',sortable:true}
            ]]
        });
        //初始化查看详细窗口
        $('#easyui-window-vip-suggestion-index-scan').window({
            title: '意见建议详细查看',
            width: 800,   
            height: 400,   
            modal: true,
            closed: true,
            collapsible: false,
            minimizable: false,
            maximizable: false,
            onClose: function(){
                $(this).window('clear');
            }
        });
        //初始化修改窗口
        $('#easyui-dialog-vip-suggestion-index-edit').dialog({
            title: '意见建议修改',
            width: 1000,
            height: 500,
            closed: false,
            cache: true,
            modal: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var easyuiForm = $('#easyui-form-vip-suggestion-edit');
                    if(!easyuiForm.form('validate')){
                        return false;
                    }
                    $.ajax({
                        "type": 'post',
                        "url": "<?= yii::$app->urlManager->createUrl(['vip/suggestion/edit']); ?>",
                        "data": easyuiForm.serialize(),
                        "dataType": 'json',
                        "success": function(rData){
                            if(rData.status){
                                $.messager.alert('修改成功',rData.info,'info');
                                $('#easyui-datagrid-vip-suggestion-index').datagrid('reload');
                                $('#easyui-dialog-vip-suggestion-index-edit').dialog('close');
                            }else{
                                $.messager.alert('修改失败',rData.info,'error');
                            }
                        }
                    });
                }
            },{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui-dialog-vip-suggestion-index-edit').dialog('close');
                }
            }],
            closed: true,
            onClose: function(){
                $(this).dialog('clear');
            } 
        });
        //初始化回复窗口
        $('#easyui-dialog-vip-suggestion-index-reply').dialog({
            title: '意见建议回复',   
            width: 800,   
            height: 400,   
            closed: false,   
            cache: true,   
            modal: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var easyuiForm = $('#easyui-form-vip-suggestion-reply');
                    if(!easyuiForm.form('validate')){
                        return false;
                    }
                    $.ajax({
                        "type": 'post',
                        "url": "<?= yii::$app->urlManager->createUrl(['vip/suggestion/reply']); ?>",
                        "data": easyuiForm.serialize(),
                        "dataType": 'json',
                        "success": function(rData){
                            if(rData.status){
                                $.messager.alert('回复成功',rData.info,'info');
                                $('#easyui-datagrid-vip-suggestion-index').datagrid('reload');
                                $('#easyui-dialog-vip-suggestion-index-reply').dialog('close');
                            }else{
                                $.messager.alert('回复失败',rData.info,'error');
                            }
                        }
                    });
                }
            },{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui-dialog-vip-suggestion-index-reply').dialog('close');
                }
            }],
            closed: true,
            onClose: function(){
                $(this).dialog('clear');
            }
        });
    }
    
    VipSuggestionIndex.init();

    //获取选择的记录
    VipSuggestionIndex.getSelected = function(){
        var datagrid = $('#easyui-datagrid-vip-suggestion-index');
        var selectRow = datagrid.datagrid('getSelected');
        if(!selectRow){
            $.messager.alert('警告','请先选择要操作的记录！','warning');
            return false;
        }
        return selectRow.vs_id;
    }
    
    //查看详细
    VipSuggestionIndex.scan = function(){
        var vs_id = this.getSelected();
        if(!vs_id){
            return;
        }
        var easyuiWindow = $('#easyui-window-vip-suggestion-index-scan');
        easyuiWindow.window('open');
        easyuiWindow.window('refresh',"<?= yii::$app->urlManager->createUrl(['vip/suggestion/scan']); ?>&vs_id="+vs_id);
    }

    //修改
    VipSuggestionIndex.edit = function(){
        var vs_id = this.getSelected();
        if(!vs_id){
            return;
        }
        var easyuiDialog = $('#easyui-dialog-vip-suggestion-index-edit');
        easyuiDialog.dialog('open');
        easyuiDialog.window('refresh',"<?= yii::$app->urlManager->createUrl(['vip/suggestion/edit']); ?>&vs_id="+vs_id);
    }

    //回复
    VipSuggestionIndex.reply = function(){
        var vs_id = this.getSelected();
        if(!vs_id){
            return;
        }
        var easyuiDialog = $('#easyui-dialog-vip-suggestion-index-reply');
        easyuiDialog.dialog('open');
        easyuiDialog.window('refresh',"<?= yii::$app->urlManager->createUrl(['vip/suggestion/reply']); ?>&vs_id="+vs_id);
    }
 
    //删除
    VipSuggestionIndex.remove = function(){
        var vs_id = this.getSelected();
        if(!vs_id){
            return;
        }
        $.messager.confirm('确定删除','您确定要删除该意见建议数据？',function(r){
            if(r){
                $.ajax({
                    type: 'get',
                    url: "<?php echo yii::$app->urlManager->createUrl(['vip/suggestion/remove']); ?>",
                    data: {"vs_id": vs_id},
                    dataType: 'json',
                    success: function(rData){
                        if(rData){
                            $.messager.alert('提示',rData.info,'info');   
                            $('#easyui-datagrid-vip-suggestion-index').datagrid('reload');
                        }else{
                            $.messager.alert('错误',rData.info,'error');   
                        }
                    }
                });
            }
        });
    }

    //查询
    VipSuggestionIndex.search = function(){
        var form = $('#search-from-vip-suggestion-index');
        var data = {};
        var searchCondition = form.serializeArray(); 
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        $('#easyui-datagrid-vip-suggestion-index').datagrid('load',data);
    }
    //重置
    VipSuggestionIndex.reset = function(){
        $('#search-from-vip-suggestion-index').form('reset');
    }

    //导出
    VipSuggestionIndex.exportGridData = function(){
        var form = $('#search-from-vip-suggestion-index');
        var str = form.serialize();
        window.open("<?php echo yii::$app->urlManager->createUrl(['vip/vip/export-grid-data']); ?>&" + str);
    }

    
</script>