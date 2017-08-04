<table id="easyui-datagrid-car-baseinfo-second-maintenance-record"></table> 
<div id="easyui-datagrid-car-baseinfo-second-maintenance-record-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-car-baseinfo-second-maintenance-record">
                <ul class="search-main">
                    <li>
                        <div class="item-name">卡编号</div>
                        <div class="item-input">
                            <input name="number" style="width:200px;"></input>
                        </div>
                    </li>
                    <li class="search-button">
                        <button class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button onclick="CarBaseinfoSecondMaintenanceRecord.resetForm()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
                    </li>
                </ul>
            </form>
        </div>
    </div>
    <?php if($buttons){ ?>
    <div class="easyui-panel" title="数据列表" style="padding:8px 4px;" data-options="
        iconCls: 'icon-tip',
        border: false
    ">
        <?php foreach($buttons as $val){ ?>
        <a href="javascript:<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon']; ?>'"><?= $val['text']; ?></a>
        <?php } ?>
    </div>
    <?php } ?>
</div>
<!-- 窗口 -->
<div id="easyui-dialog-car-baseinfo-second-maintenance-add"></div>
<div id="easyui-dialog-car-baseinfo-second-maintenance-edit"></div>
<!-- 窗口 -->
<script>
    var CarBaseinfoSecondMaintenanceRecord = new Object();
    CarBaseinfoSecondMaintenanceRecord.init = function(){
        //获取列表数据
        $('#easyui-datagrid-car-baseinfo-second-maintenance-record').datagrid({  
            method: 'get', 
            url:"<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/get-second-maintenance-list','carId'=>$carId]); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-car-baseinfo-second-maintenance-record-toolbar",
            pagination: true,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
            frozenColumns: [[
                {field: 'ck',checkbox: true}, 
                {field: 'id',title: 'id',hidden: true},   
                {field: 'number',title: '维护卡编号',width: 200,sortable: true},   
            ]],
            columns:[[
                {
                    field: 'current_date',title: '本次维护日期',width: 100,align: 'center',
                    sortable: true,
                    formatter: function(value){
                        if(!isNaN(value) && value > 0){
                            return formatDateToString(value);
                        }
                    }
                },
                {
                    field: 'next_date',title: '下次维护日期',width: 100,align: 'center',
                    sortable: true,
                    formatter: function(value){
                        if(!isNaN(value) && value > 0){
                            return formatDateToString(value);
                        }
                    }
                }
            ]],
            onDblClickRow: function(rowIndex,rowData){
                CarBaseinfoSecondMaintenanceRecord.edit(rowData.id);
            }
        });
        //构建查询表单
        var searchFrom = $('#search-form-car-baseinfo-second-maintenance-record');
        searchFrom.submit(function(){
            var data = {};
            var searchCondition = $(this).serializeArray();
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = searchCondition[i]['value'];
            }
            $('#easyui-datagrid-car-baseinfo-second-maintenance-record').datagrid('load',data);
            return false;
        });
        searchFrom.find('input[name=number]').textbox({
            onChange: function(){
                $('#search-form-car-baseinfo-second-maintenance-record').submit();
            }
        });
        //构建查询表单结束
        //初始化添加窗口
        $('#easyui-dialog-car-baseinfo-second-maintenance-add').dialog({
            title: '添加二级维护记录',   
            width: '615px',   
            height: '200px',   
            closed: true,   
            cache: true,   
            modal: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var form = $('#easyui-form-car-baseinfo-add-second-maintenance');
                    if(!form.form('validate')) return false;
                    var data = form.serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/add-second-maintenance']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('添加成功',data.info,'info');
                                $('#easyui-dialog-car-baseinfo-second-maintenance-add').dialog('close');
                                $('#easyui-datagrid-car-baseinfo-second-maintenance-record').datagrid('reload');
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
                    $('#easyui-dialog-car-baseinfo-second-maintenance-add').dialog('close');
                }
            }]
        });
        //初始化修改窗口
        $('#easyui-dialog-car-baseinfo-second-maintenance-edit').dialog({
            title: '修改二级维护记录',   
            width: '615px',   
            height: '200px',   
            closed: true,   
            cache: true,   
            modal: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var form = $('#easyui-form-car-baseinfo-edit-second-maintenance');
                    if(!form.form('validate')) return false;
                    var data = form.serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/edit-second-maintenance']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('修改成功',data.info,'info');
                                $('#easyui-dialog-car-baseinfo-second-maintenance-edit').dialog('close');
                                $('#easyui-datagrid-car-baseinfo-second-maintenance-record').datagrid('reload');
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
                    $('#easyui-dialog-car-baseinfo-second-maintenance-edit').dialog('close');
                }
            }]  
        });
    }
    CarBaseinfoSecondMaintenanceRecord.init();
    //获取选择的记录
    CarBaseinfoSecondMaintenanceRecord.getSelected = function(){
        var datagrid = $('#easyui-datagrid-car-baseinfo-second-maintenance-record');
        var selectRow = datagrid.datagrid('getSelected');
        if(!selectRow){
            $.messager.alert('错误','请选择要操作的记录','error');   
            return false;
        }
        return selectRow;
    }
    //添加
    CarBaseinfoSecondMaintenanceRecord.add = function(){
        $('#easyui-dialog-car-baseinfo-second-maintenance-add').dialog('open');
        $('#easyui-dialog-car-baseinfo-second-maintenance-add').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/add-second-maintenance','carId'=>$carId]); ?>");
    }
    //修改
    CarBaseinfoSecondMaintenanceRecord.edit = function(id){
        if(!id){
            var selectRow = this.getSelected();
            if(!selectRow){
                return false;
            }
            id = selectRow.id;
        }
        $('#easyui-dialog-car-baseinfo-second-maintenance-edit').dialog('open');
        $('#easyui-dialog-car-baseinfo-second-maintenance-edit').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/edit-second-maintenance']); ?>&id="+id);
    }
    //删除
    CarBaseinfoSecondMaintenanceRecord.remove = function(){
        var selectRow = this.getSelected();
        if(!selectRow) return false;
        var id = selectRow.id;
        $.messager.confirm('确定删除','您确定要删除该条二级维护记录数据？',function(r){
            if(r){
                $.ajax({
                    type: 'get',
                    url: "<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/remove-second-maintenance']); ?>",
                    data: {id: id},
                    dataType: 'json',
                    success: function(data){
                        if(data){
                            $.messager.alert('删除成功',data.info,'info');   
                            $('#easyui-datagrid-car-baseinfo-second-maintenance-record').datagrid('reload');
                        }else{
                            $.messager.alert('删除失败',data.info,'error');   
                        }
                    }
                });
            }
        });
    }
    //查询
    CarBaseinfoSecondMaintenanceRecord.search = function(){
        var form = $('#search-form-car-baseinfo-second-maintenance-record');
        var data = {};
        var searchCondition = form.serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        $('#easyui-datagrid-car-baseinfo-second-maintenance-record').datagrid('load',data);
    }
    //查询
    CarBaseinfoSecondMaintenanceRecord.resetForm = function(){
        $('#search-form-car-baseinfo-second-maintenance-record').form('reset');
    }
</script>