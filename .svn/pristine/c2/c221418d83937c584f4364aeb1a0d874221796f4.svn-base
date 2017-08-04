<table id="easyui-datagrid-charge-frontmachine-index"></table> 
<div id="easyui-datagrid-charge-frontmachine-index-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-charge-frontmachine-index">
                <ul class="search-main">
                    <li>
                        <div class="item-name">地址</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="addr" style="width:100%;"
                               data-options="
                                    onChange:function(){
                                        ChargeFrontmachineIndex.search();
                                    }
                               "
                            />
                        </div>
                    </li>
                    <li class="search-button">
                        <a href="javascript:void(0)"  onclick="ChargeFrontmachineIndex.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                        <a href="javascript:void(0)"  onclick="ChargeFrontmachineIndex.reset()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</a>
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
                <a href="javascript:void(0)"  onclick="<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon'] ;?>'"><?= $val['text'] ;?></a>
            <?php } ?>
        </div>
    <?php } ?>

</div>
<!-- 窗口 -->
<div id="easyui-dialog-charge-frontmachine-index-add"></div>
<div id="easyui-dialog-charge-frontmachine-index-edit"></div>
<!-- 窗口 -->
<script>
    var ChargeFrontmachineIndex = new Object();
    ChargeFrontmachineIndex.init = function(){
        $('#easyui-datagrid-charge-frontmachine-index').datagrid({  
            method: 'get', 
            url:"<?php echo yii::$app->urlManager->createUrl(['charge/frontmachine/get-list']); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-charge-frontmachine-index-toolbar",
            pagination: true,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
            pageSize: 20,
            frozenColumns: [[
                {field: 'ck',checkbox: true}, 
                {field: 'id',title: 'id',hidden: true},   
                {field: 'addr',title: '地址',width: 100,halign:'center',sortable: true},
            ]],
            columns: [[
                {field: 'port',title: '端口号',width: 60,align:'center',sortable: true},
                {field: 'access_level',title: '权限等级',width: 60,align:'center',sortable: true},
                {field: 'password',title: '密码',width: 80,align:'center',sortable: true,
                    formatter:function(value){
                        if (value) {
                            return '******';
                        }
                    }
                },
                {field: 'register_number',title: '寄存器编号',width: 90,align:'center',sortable: true},
                {field: 'db_username',title: '数据库用户名',width: 90,align: 'center',sortable: true},
                {field: 'db_password',title: '数据库密码',width: 90,align: 'center',sortable: true,
                    formatter:function(value){
                        if (value) {
                            return '******';
                        }
                    }
                },
                {field: 'db_port',title: '数据库端口',width: 90,align: 'center',sortable: true},
                {field: 'db_name',title: '数据库名称',width: 90,align: 'center',sortable: true},
                {field: 'note',title: '备注',width: 250,halign:'center'}
            ]],
            onDblClickRow: function(rowIndex,rowData){
                ChargeFrontmachineIndex.edit(rowData.id);
            }
        }); 
        
        //初始化添加窗口
        $('#easyui-dialog-charge-frontmachine-index-add').dialog({
            title: '&nbsp;添加前置机',
            iconCls: 'icon-add',
            width: 670,
            height: 350,
            closed: true,   
            cache: true,   
            modal: true,
            maximizable: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var form = $('#easyui-form-charge-frontmachine-add');
                    if(!form.form('validate')) return false;
                    var data = form.serialize(); 
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['charge/frontmachine/add']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('添加成功',data.info,'info');
                                $('#easyui-dialog-charge-frontmachine-index-add').dialog('close');
                                $('#easyui-datagrid-charge-frontmachine-index').datagrid('reload');
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
                    $('#easyui-dialog-charge-frontmachine-index-add').dialog('close');
                }
            }],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
        //初始化修改窗口
        $('#easyui-dialog-charge-frontmachine-index-edit').dialog({
            title: '&nbsp;修改前置机',
            iconCls: 'icon-add',
            width: 670,
            height: 350,
            closed: true,   
            cache: true,   
            modal: true,
            maximizable: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var form = $('#easyui-form-charge-frontmachine-edit');
                    if(!form.form('validate')) return false;
                    var data = form.serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['charge/frontmachine/edit']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('修改成功',data.info,'info');
                                $('#easyui-dialog-charge-frontmachine-index-edit').dialog('close');
                                $('#easyui-datagrid-charge-frontmachine-index').datagrid('reload');
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
                    $('#easyui-dialog-charge-frontmachine-index-edit').dialog('close');
                }
            }],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
    }
    ChargeFrontmachineIndex.init();
    //获取选择的记录
    //参数all = true标示是否要返回所有被选择的记录
    ChargeFrontmachineIndex.getSelected = function(all){
        var datagrid = $('#easyui-datagrid-charge-frontmachine-index');
        if(all){
            var selectRows = datagrid.datagrid('getSelections');
            if(selectRows.length <= 0){
                $.messager.alert('错误','请选择要操作的记录','error');   
                return false;
            }
            return selectRows;
        }else{
            var selectRow = datagrid.datagrid('getSelected');
            if(!selectRow){
                $.messager.alert('错误','请选择要操作的记录','error');   
                return false;
            }
            return selectRow;
        }
        
    }
    //添加方法
    ChargeFrontmachineIndex.add = function(){
        $('#easyui-dialog-charge-frontmachine-index-add').dialog('open');
        $('#easyui-dialog-charge-frontmachine-index-add').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['charge/frontmachine/add']); ?>");
    }
    //修改
    ChargeFrontmachineIndex.edit = function(id){
        if(!id){
            var selectRow = this.getSelected();
            if(!selectRow){
                return false;
            }
            id = selectRow.id;
        }
        $('#easyui-dialog-charge-frontmachine-index-edit').dialog('open');
        $('#easyui-dialog-charge-frontmachine-index-edit').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['charge/frontmachine/edit']); ?>&id="+id);
    }
    //删除
    ChargeFrontmachineIndex.remove = function(){
        var selectRow = this.getSelected();
        if(!selectRow) return false;
        var id = selectRow.id;
        $.messager.confirm('确定删除','您确定要删除选中的前置机？',function(r){
            if(r){
                $.ajax({
                    type: 'get',
                    url: "<?php echo yii::$app->urlManager->createUrl(['charge/frontmachine/remove']); ?>",
                    data: {id: id},
                    dataType: 'json',
                    success: function(data){
                        if(data){
                            $.messager.alert('删除成功',data.info,'info');   
                            $('#easyui-datagrid-charge-frontmachine-index').datagrid('reload');
                        }else{
                            $.messager.alert('删除失败',data.info,'error');   
                        }
                    }
                });
            }
        });
    }
    //按条件导出车辆列表
    ChargeFrontmachineIndex.exportWidthCondition = function(){
        var url = "<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/export-width-condition']);?>";
        var form = $('#search-form-charge-frontmachine-index');
        var data = {};
        var searchCondition = form.serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        for(var i in data){
            url += '&'+i+'='+data[i];
        }
        window.open(url);
    }
    //查询
    ChargeFrontmachineIndex.search = function(){
        var form = $('#search-form-charge-frontmachine-index');
        var data = {};
        var searchCondition = form.serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = $.trim(searchCondition[i]['value']);
        }
        $('#easyui-datagrid-charge-frontmachine-index').datagrid('load',data);
    }
    //重置
    ChargeFrontmachineIndex.reset = function(){
        $('#search-form-charge-frontmachine-index').form('reset');
    }

</script>