<table id="easyui-datagrid-car-test-index"></table> 
<div id="easyui-datagrid-car-test-index-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-car-test-index">
                <ul class="search-main">
                    <li>
                        <div class="item-name">车牌号</div>
                        <div class="item-input">
                            <input name="plate_number" style="width:100%;" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">登记时间</div>
                        <div class="item-input">
                            <input class="easyui-datebox" name="reg_time_start" style="width:90px;" />
                            -
                            <input class="easyui-datebox" name="reg_time_end" style="width:90px;" />
                        </div>
                    </li>
                    <li class="search-button">
                        <button class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button onclick="CarTestIndex.resetForm();" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
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
                <a href="javascript:<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon']; ?>'"><?= $val['text']; ?></a>
            <?php } ?>
        </div>
    <?php } ?>

</div>
<!-- 窗口 -->
<div id="easyui-dialog-car-test-index-add"></div>
<div id="easyui-dialog-car-test-index-edit"></div>
<!-- 窗口 -->
<script>
    var CarTestIndex = new Object();
    CarTestIndex.init = function(){
        //获取列表数据
        $('#easyui-datagrid-car-test-index').datagrid({  
            method: 'get', 
            url:"<?php echo yii::$app->urlManager->createUrl(['car/test/get-list']); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-car-test-index-toolbar",
            pagination: true,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
            frozenColumns: [[
                {field: 'ck',checkbox: true}, 
                {field: 'id',title: 'id',hidden: true},   
                {field: 'plate_number',title: '车牌号',width: 70,sortable: true,align: 'center'}
            ]],
            columns:[[
                {
                    field: 'reg_time',title: '登记时间',width: 140, align: 'center',
                    sortable: true,
                    formatter: function(value){
                        if(!isNaN(value) && value > 0){
                            return formatDateToString(value,true);
                        }
                    }
                },
                {field: 'mileage',title: '测试里程(km)',width: 100,halign: 'center',align: 'right',sortable: true},
                {field: 'use_hour',title: '测试小时',width: 80,halign: 'center',align: 'right',sortable: true},
                {field: 'use_minute',title: '测试分钟',width: 80,halign: 'center',align: 'right',sortable: true},
                {field: 'slow_recharge_status',title: '慢充充电状况',width: 300,halign: 'center',align: 'left'},
                {field: 'fast_recharge_status',title: '慢充充电状况',width: 300,halign: 'center',align: 'left'}
            ]],
            onDblClickRow: function(rowIndex,rowData){
                CarTestIndex.edit(rowData.id);
            },
            onLoadSuccess: function (data){
                $(this).datagrid('doCellTip',{
                    position : 'bottom',
                    maxWidth : '300px',
                    onlyShowInterrupt : true,
                    specialShowFields : [     
                        {field : 'action',showField : 'action'}
                    ],
                    tipStyler : {            
                        'backgroundColor' : '#E4F0FC',
                        borderColor : '#87A9D0',
                        boxShadow : '1px 1px 3px #292929'
                    }
                });
            }
        });
        //构建查询表单
        var searchFrom = $('#search-form-car-test-index');
        searchFrom.submit(function(){
            var data = {};
            var searchCondition = $(this).serializeArray();
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = searchCondition[i]['value'];
            }
            $('#easyui-datagrid-car-test-index').datagrid('load',data);
            return false;
        });
        searchFrom.find('input[name=plate_number]').textbox({
            onChange: function(){
                $('#search-form-car-test-index').submit();
            }
        });
        searchFrom.find('input[name=reg_time_start]').datebox({
            editable: false,
            onChange: function(){
                $('#search-form-car-test-index').submit();
            }
        });
        searchFrom.find('input[name=reg_time_end]').datebox({
            editable: false,
            onChange: function(){
                $('#search-form-car-test-index').submit();
            }
        });
        //构建查询表单结束
        //初始化添加窗口
        $('#easyui-dialog-car-test-index-add').dialog({
            title: '添加测试记录',   
            width: '670px',   
            height: '340px',   
            closed: true,   
            cache: true,   
            modal: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var form = $('#easyui-form-car-test-add');
                    if(!form.form('validate')) return false;
                    var data = form.serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['car/test/add']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('添加成功',data.info,'info');
                                $('#easyui-dialog-car-test-index-add').dialog('close');
                                $('#easyui-datagrid-car-test-index').datagrid('reload');
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
                    $('#easyui-dialog-car-test-index-add').dialog('close');
                }
            }]
        });
        //初始化修改窗口
        $('#easyui-dialog-car-test-index-edit').dialog({
            title: '修改车辆信息',   
            width: '670px',   
            height: '340px',   
            closed: true,   
            cache: true,   
            modal: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var form = $('#easyui-form-car-test-edit');
                    if(!form.form('validate')) return false;
                    var data = form.serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['car/test/edit']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('修改成功',data.info,'info');
                                $('#easyui-dialog-car-test-index-edit').dialog('close');
                                $('#easyui-datagrid-car-test-index').datagrid('reload');
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
                    $('#easyui-dialog-car-test-index-edit').dialog('close');
                }
            }]  
        });
    }
    CarTestIndex.init();
    //获取选择的记录
    CarTestIndex.getSelected = function(){
        var datagrid = $('#easyui-datagrid-car-test-index');
        var selectRow = datagrid.datagrid('getSelected');
        if(!selectRow){
            $.messager.alert('错误','请选择要操作的记录','error');   
            return false;
        }
        return selectRow;
    }
    //添加方法
    CarTestIndex.add = function(){
        $('#easyui-dialog-car-test-index-add').dialog('open');
        $('#easyui-dialog-car-test-index-add').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/test/add']); ?>");
    }
    //修改
    CarTestIndex.edit = function(id){
        if(!id){
            var selectRow = this.getSelected();
            if(!selectRow){
                return false;
            }
            id = selectRow.id;
        }
        $('#easyui-dialog-car-test-index-edit').dialog('open');
        $('#easyui-dialog-car-test-index-edit').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/test/edit']); ?>&id="+id);
    }
    //删除
    CarTestIndex.remove = function(){
        var selectRow = this.getSelected();
        if(!selectRow) return false;
        var id = selectRow.id;
        $.messager.confirm('确定删除','您确定要删除该条测试数据？',function(r){
            if(r){
                $.ajax({
                    type: 'get',
                    url: "<?php echo yii::$app->urlManager->createUrl(['car/test/remove']); ?>",
                    data: {id: id},
                    dataType: 'json',
                    success: function(data){
                        if(data){
                            $.messager.alert('删除成功',data.info,'info');   
                            $('#easyui-datagrid-car-test-index').datagrid('reload');
                        }else{
                            $.messager.alert('删除失败',data.info,'error');   
                        }
                    }
                });
            }
        });
    }
    //按条件导出
    CarTestIndex.exportWidthCondition = function(){
        var form = $('#search-form-car-test-index');
        window.open("<?= yii::$app->urlManager->createUrl(['car/test/export-width-condition']); ?>&"+form.serialize());
    }
    //重置查询表单
    CarTestIndex.resetForm = function(){
        $('#search-form-car-test-index').form('reset');
    }
</script>