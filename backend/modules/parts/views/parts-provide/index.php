<table id="easyui-datagrid-parts-parts-provide-index"></table>
<div id="easyui-datagrid-parts-parts-provide-index-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">
        <div class="data-search-form">
            <form id="search-form-parts-parts-provide-index" method="post">
                <ul class="search-main">
                    <li>
                        <div class="item-name">供应商编号：</div>
                        <div class="item-input">
                            <input class="easyui-textbox" name="provide_code" style="width:150px;" data-options="prompt:'请输入',">
                        </div>
                    </li>
                    <li>
                        <div class="item-name">供应商名称：</div>
                        <div class="item-input">
                            <input class="easyui-textbox" name="provide_name" style="width:150px;" data-options="prompt:'请输入',">
                        </div>
                    </li>
                    <li>
                        <div class="item-name">合作方式：</div>
                        <div class="item-input">
                            <select class="easyui-combobox" name="work_type" style="width:150px;">
                                <option value=" ">请选择</option>
                                <option value="1">有合作协议</option>
                                <option value="2">无合作协议</option>
                            </select>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">所在城市：</div>
                        <div class="item-input">
                            <input class="easyui-textbox" name="home_area" style="width:150px;" data-options="prompt:'请输入',">
                        </div>
                    </li>
                    <li>
                        <div class="item-name">负责人：</div>
                        <div class="item-input">
                            <input class="easyui-textbox" name="duty_man" style="width:150px;" data-options="prompt:'请输入',">
                        </div>
                    </li>
                    <li>
                        <div class="item-name">状态：</div>
                        <div class="item-input">
                            <select class="easyui-combobox" name="status" style="width:150px;">
                                <option value="0">正常</option>
                                <option value="1">作废</option>
                            </select>
                        </div>
                    </li>
                    <li class="search-button">
                        <button type="submit" onclick="PartsProvide.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button type="submit" onclick="PartsProvide.resetForm()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
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
<div id="easyui-dialog-parts-parts-provide-index-add"></div>
<div id="easyui-dialog-parts-parts-provide-index-edit"></div>
<div id="easyui-dialog-parts-parts-provide-index-see"></div>
<script>
    var PartsProvide = new Object();
    PartsProvide.init = function(){
        //获取列表数据
        $('#easyui-datagrid-parts-parts-provide-index').datagrid({
            method: 'get',
            url:'<?php echo yii::$app->urlManager->createUrl(['parts/parts-provide/get-list']); ?>',
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-parts-parts-provide-index-toolbar",
            pagination: true,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
            columns:[[
                {field: 'ck',checkbox: true},
                {field: 'provide_code',title: '供应商编号'},
                {field: 'provide_name',title: '供应商名称',width: 100},
                {field: 'work_type',title: '合作方式',width: 100,
                    formatter: function(value,row,index){
                        if(row.work_type == '1'){
                            return '有合作协议';
                        }else{
                            return '无合作协议';
                        }
                    }
                },
                {field: 'main_range',title: '主营范围',width: 100},
                {field: 'duty_man',title: '负责人',width: 100},
                {field: 'tel',title: '联系方式',width: 100},
                {field: 'region_name',title: '所在城市',width: 100},
                {field: 'status',title: '状态',width: 50,
                    formatter: function(value,row,index){
                        if(row.status == '0'){
                            return '正常';
                        }else{
                            return '作废';
                        }
                    }
                },
                {field: 'create_man',title: '创建人',width: 100},
                {field: 'create_time',title: '创建时间',width: 100},
                {field: 'note',title: '备注',width: 100},
            ]]
        });
        //初始化添加窗口`
        $('#easyui-dialog-parts-parts-provide-index-add').dialog({
            title: '新增',
            width: 900,
            height: 300,
            cache: true,
            modal: true,
            resizable:true,
            closed: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var form = $('#search-form-parts-provide-add');
                    if(!form.form('validate')){
                        return false;
                    }
                    var data = form.serialize();
                    var button = $(this);
                    button.linkbutton('disable');
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['parts/parts-provide/add']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status == 1){
                                $.messager.alert('新建成功',data.info,'info');
                                $('#easyui-dialog-parts-parts-provide-index-add').dialog('close');
                                $('#easyui-datagrid-parts-parts-provide-index').datagrid('reload');
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
                    $('#easyui-datagrid-parts-parts-provide-index').datagrid('reload');
                    $('#easyui-dialog-parts-parts-provide-index-add').dialog('close');
                }
            },{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui-dialog-parts-parts-provide-index-add').dialog('close');
                }
            }]
        });
        //初始化修改窗口
        $('#easyui-dialog-parts-parts-provide-index-edit').dialog({
            title: '修改',
            width: 1000,
            height: 250,
            closed: true,
            cache: true,
            modal: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var data = $('#search-form-parts-provide-edit').serialize();
                    var button = $(this);
                    button.linkbutton('disable');
                    $.ajax({
                        type: 'post',
                        url: '<?php echo yii::$app->urlManager->createUrl(['parts/parts-provide/edit']); ?>',
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status == 1){
                                $.messager.alert('修改成功',data.info,'info');
                                $('#easyui-dialog-parts-parts-provide-index-edit').dialog('close');
                                $('#easyui-datagrid-parts-parts-provide-index').datagrid('reload');
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
                    $('#easyui-dialog-parts-parts-provide-index-edit').dialog('close');
                }
            }]
        });
        //初始化查看窗口
        $('#easyui-dialog-parts-parts-provide-index-see').dialog({
            title: '查看',
            width: 1000,
            height: 250,
            closed: true,
            cache: true,
            modal: true,
            buttons: [{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui-dialog-parts-parts-provide-index-see').dialog('close');
                }
            }]
        });
        //绑定记录双击事件
        $('#easyui-datagrid-parts-parts-provide-index').datagrid({
            onDblClickRow: function(rowIndex,rowData){
                PartsProvide.edit(rowData.id);
            }
        });
    }
    PartsProvide.init();
    //添加方法
    PartsProvide.add = function(){
        $('#easyui-dialog-parts-parts-provide-index-add').dialog('open');
        $('#easyui-dialog-parts-parts-provide-index-add').dialog('refresh','<?php echo yii::$app->urlManager->createUrl(['parts/parts-provide/add']); ?>');
    }
    //删除配件信息
    PartsProvide.del = function(){
        var datagrid = $('#easyui-datagrid-parts-parts-provide-index');
        var partsData = datagrid.datagrid('getSelected');
        if(partsData == null){
            $.messager.alert('作废失败','请选择作废项','error');
            return false;
        }
        var id = partsData.id;
        $.messager.confirm('确认对话框', '确定作废？', function(r){
            if (r){
                $.ajax({
                    type: 'post',
                    url: "<?php echo yii::$app->urlManager->createUrl(['parts/parts-provide/del']); ?>",
                    data: {'id':id},
                    dataType: 'json',
                    success: function(data){
                        if(data.status == 1){
                            $.messager.alert('作废成功',data.info,'info');
                            $('#easyui-datagrid-parts-parts-provide-index').datagrid('reload');
                        }else if(data.status == 2){
                            $.messager.alert('作废失败',data.info,'error');
                        }else{
                            $.messager.alert('作废失败',data.info,'error');
                        }
                    }
                });
            }
        });
    }
    PartsProvide.edit = function(){
        var datagrid = $('#easyui-datagrid-parts-parts-provide-index');
        var partsData = datagrid.datagrid('getSelected');
        if(partsData == null){
            $.messager.alert('修改失败','请选择修改项','error');
            return false;
        }
        var id = partsData.id;
        $('#easyui-dialog-parts-parts-provide-index-edit').dialog('open');
        $('#easyui-dialog-parts-parts-provide-index-edit').dialog('refresh','<?php echo yii::$app->urlManager->createUrl(['parts/parts-provide/edit']); ?>&id='+id);
    }
    //查看详情
    PartsProvide.see = function(){
        var datagrid = $('#easyui-datagrid-parts-parts-provide-index');
        var partsData = datagrid.datagrid('getSelected');
        if(partsData == null){
            $.messager.alert('查看失败','请选择查看项','error');
            return false;
        }
        var id = partsData.id;
        $('#easyui-dialog-parts-parts-provide-index-see').dialog('open');
        $('#easyui-dialog-parts-parts-provide-index-see').dialog('refresh','<?php echo yii::$app->urlManager->createUrl(['parts/parts-provide/see']); ?>&id='+id);
    }
    //构建查询表单
    var searchForm = $('#search-form-parts-parts-provide-index');
    /**查询表单提交事件**/
    searchForm.submit(function(){
        var data = {};
        var searchCondition = $(this).serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        $('#easyui-datagrid-parts-parts-provide-index').datagrid('load',data);
        return false;
    });

    //汽车品牌下拉
//    searchForm.find('input[name=car_brand]').combotree({
//        url: "<?php //echo yii::$app->urlManager->createUrl(['car/combotree/get-car-brands']); ?>//",
//        editable: false,
//        panelHeight:'auto',
//        lines:false,
//    });
    //重置查询表单
    PartsProvide.resetForm = function(){
        var easyuiForm = $('#search-form-parts-parts-provide-index');
        easyuiForm.form('reset');
    }

    //条件搜索查询
    PartsProvide.search = function(){
        var form = $('#search-form-parts-parts-provide-index');
        var data = {};
        var searchCondition = form.serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        $('#easyui-datagrid-parts-parts-provide-index').datagrid('load',data);
    }
</script>