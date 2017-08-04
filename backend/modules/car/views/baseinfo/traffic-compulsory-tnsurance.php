<table id="easyui-datagrid-car-baseinfo-traffic-compulsory-insurance"></table> 
<div id="easyui-datagrid-car-baseinfo-traffic-compulsory-insurance-toolbar">
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
                            <input class="easyui-textbox" type="text" name="number" style="width:150px;"></input>
                        </div>
                    </li>
                    <li class="search-button">
                        <a id="btn" href="javascript:CarBaseinfoTrafficCompulsoryInsurance.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
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
<div id="easyui-dialog-car-baseinfo-tci-add"></div>
<div id="easyui-dialog-car-baseinfo-tci-edit"></div>
<!-- 窗口 -->
<script>
    var CarBaseinfoTrafficCompulsoryInsurance = new Object();
    CarBaseinfoTrafficCompulsoryInsurance.init = function(){
        //获取列表数据
        $('#easyui-datagrid-car-baseinfo-traffic-compulsory-insurance').datagrid({  
            method: 'get', 
            url:"<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/tci-get-list','carId'=>$carId]); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-car-baseinfo-traffic-compulsory-insurance-toolbar",
            pagination: true,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
            frozenColumns: [[
                {field: 'ck',checkbox: true}, 
                {field: 'id',title: 'id',hidden: true},   
                {field: 'number',title: '维护卡编号',width: 200},   
            ]],
            columns:[[
                {
                    field: 'current_date',title: '本次维护日期',width: 200,align: 'center',
                    formatter: function(value){
                        if(!isNaN(value) && value > 0){
                            return formatDateToString(value);
                        }
                    }
                },
                {
                    field: 'next_date',title: '下次维护日期',width: 200,align: 'center',
                    formatter: function(value){
                        if(!isNaN(value) && value > 0){
                            return formatDateToString(value);
                        }
                    }
                }
            ]],
            onDblClickRow: function(rowIndex,rowData){
                CarBaseinfoTrafficCompulsoryInsurance.edit(rowData.id);
            }
        });
        //初始化添加窗口
        $('#easyui-dialog-car-baseinfo-tci-add').dialog({
            title: '添加交通强制保险记录',   
            width: '615px',   
            height: '200px',   
            closed: true,   
            cache: true,   
            modal: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var form = $('#easyui-form-car-baseinfo-tci-add');
                    if(!form.form('validate')) return false;
                    var data = form.serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/tci-add']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('添加成功',data.info,'info');
                                $('#easyui-dialog-car-baseinfo-tci-add').dialog('close');
                                $('#easyui-datagrid-car-baseinfo-traffic-compulsory-insurance').datagrid('reload');
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
                    $('#easyui-dialog-car-baseinfo-tci-add').dialog('close');
                }
            }]
        });
        //初始化修改窗口
        $('#easyui-dialog-car-baseinfo-tci-edit').dialog({
            title: '修改交通强制保险记录',   
            width: '615px',   
            height: '200px',   
            closed: true,   
            cache: true,   
            modal: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var form = $('#easyui-form-car-baseinfo-tci-edit');
                    if(!form.form('validate')) return false;
                    var data = form.serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/tci-edit']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('修改成功',data.info,'info');
                                $('#easyui-dialog-car-baseinfo-tci-edit').dialog('close');
                                $('#easyui-datagrid-car-baseinfo-traffic-compulsory-insurance').datagrid('reload');
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
                    $('#easyui-dialog-car-baseinfo-tci-edit').dialog('close');
                }
            }]  
        });
    }
    CarBaseinfoTrafficCompulsoryInsurance.init();
    //获取选择的记录
    CarBaseinfoTrafficCompulsoryInsurance.getSelected = function(){
        var datagrid = $('#easyui-datagrid-car-baseinfo-traffic-compulsory-insurance');
        var selectRow = datagrid.datagrid('getSelected');
        if(!selectRow){
            $.messager.alert('错误','请选择要操作的记录','error');   
            return false;
        }
        return selectRow;
    }
    //添加
    CarBaseinfoTrafficCompulsoryInsurance.add = function(){
        $('#easyui-dialog-car-baseinfo-tci-add').dialog('open');
        $('#easyui-dialog-car-baseinfo-tci-add').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/tci-add','carId'=>$carId]); ?>");
    }
    //修改
    CarBaseinfoTrafficCompulsoryInsurance.edit = function(id){
        if(!id){
            var selectRow = this.getSelected();
            if(!selectRow){
                return false;
            }
            id = selectRow.id;
        }
        $('#easyui-dialog-car-baseinfo-tci-edit').dialog('open');
        $('#easyui-dialog-car-baseinfo-tci-edit').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/tci-edit']); ?>&id="+id);
    }
    //删除
    CarBaseinfoTrafficCompulsoryInsurance.remove = function(){
        var selectRow = this.getSelected();
        if(!selectRow) return false;
        var id = selectRow.id;
        $.messager.confirm('确定删除','您确定要删除该条二级维护记录数据？',function(r){
            if(r){
                $.ajax({
                    type: 'get',
                    url: "<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/tci-remove']); ?>",
                    data: {id: id},
                    dataType: 'json',
                    success: function(data){
                        if(data){
                            $.messager.alert('删除成功',data.info,'info');   
                            $('#easyui-datagrid-car-baseinfo-traffic-compulsory-insurance').datagrid('reload');
                        }else{
                            $.messager.alert('删除失败',data.info,'error');   
                        }
                    }
                });
            }
        });
    }
    //查询
    CarBaseinfoTrafficCompulsoryInsurance.search = function(){
        var form = $('#search-form-car-baseinfo-second-maintenance-record');
        var data = {};
        var searchCondition = form.serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        $('#easyui-datagrid-car-baseinfo-traffic-compulsory-insurance').datagrid('load',data);
    }
</script>