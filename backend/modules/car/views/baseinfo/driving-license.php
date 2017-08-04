<table id="easyui-datagrid-car-baseinfo-driving-license-record"></table> 
<div id="easyui-datagrid-car-baseinfo-driving-license-record-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-car-baseinfo-driving-license-record">
                <ul class="search-main">
                    <li>
                        <div class="item-name">档案编号</div>
                        <div class="item-input">
                            <input name="number" style="width:200px;"></input>
                        </div>
                    </li>
                    <li class="search-button">
                        <button class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button onclick="CarBaseinfoDrivingLicenseRecord.resetForm()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
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
<div id="easyui-dialog-car-baseinfo-driving-license-add"></div>
<div id="easyui-dialog-car-baseinfo-driving-license-edit"></div>
<!-- 窗口 -->
<script>
    var CarBaseinfoDrivingLicenseRecord = new Object();
    CarBaseinfoDrivingLicenseRecord.init = function(){
        //获取列表数据
        $('#easyui-datagrid-car-baseinfo-driving-license-record').datagrid({  
            method: 'get', 
            url:"<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/get-driving-license-list','carId'=>$carId]); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-car-baseinfo-driving-license-record-toolbar",
            pagination: true,
            loadMsg: '数据加载中...',
            width: '955px',   
            height: '550px', 
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
            frozenColumns: [[
                {field: 'ck',checkbox: true}, 
                {field: 'id',title: 'id',hidden: true},   
                {field: 'addr_',title: '登记地址',width: 195},   
            ]],
            columns:[[
                {
                    field: 'register_date',title: '注册日期',width: 80,align: 'center',
                    sortable: true,
                    formatter: function(value){
                        if(!isNaN(value) && value > 0){
                            return formatDateToString(value);
                        }
                    }
                },
                {
                    field: 'issue_date',title: '发证日期',width: 80,align: 'center',
                    sortable: true,
                    formatter: function(value){
                        if(!isNaN(value) && value > 0){
                            return formatDateToString(value);
                        }
                    }
                },
                {
                    field: 'archives_number',title: '档案编号',width: 80,align: 'center',
                  /*  sortable: true,
                    formatter: function(value){
                        if(!isNaN(value) && value > 0){
                            return formatDateToString(value);
                        }
                    }*/
                },
                {
                    field: 'total_mass',title: '整备质量',width: 80,align: 'center',
                  /*  sortable: true,
                    formatter: function(value){
                        if(!isNaN(value) && value > 0){
                            return formatDateToString(value);
                        }
                    }*/
                },
                {
                    field: 'force_scrap_date',title: '强制报废日期',width: 80,align: 'center',
                    sortable: true,
                    formatter: function(value){
                        if(!isNaN(value) && value > 0){
                            return formatDateToString(value);
                        }
                    }
                },
                 {
                    field: 'valid_to_date',title: '检验有效期至',width: 80,align: 'center',
                    sortable: true,
                    formatter: function(value){
                        if(!isNaN(value) && value > 0){
                            return formatDateToString(value);
                        }
                    }
                },
                {
                    field: 'image',title: '上传行驶证照片',width: 80,align: 'center',
                    sortable: true,
                    formatter: function(value){
                        //if(!isNaN(value) && value > 0){
                       if(value != 0 && value != "" && value != null){
                            return '<a href="'+value+'" target="_blank" ><img src="'+value+'" width="80px" height="80px"></a>';
                       }else {
						   return '附件未上传';
					   }
                    }
                },
               

            ]],
            onDblClickRow: function(rowIndex,rowData){
                CarBaseinfoDrivingLicenseRecord.edit(rowData.id);
            }
        });
        //构建查询表单
        var searchFrom = $('#search-form-car-baseinfo-driving-license-record');
        searchFrom.submit(function(){
            var data = {};
            var searchCondition = $(this).serializeArray();
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = searchCondition[i]['value'];
            }
            $('#easyui-datagrid-car-baseinfo-driving-license-record').datagrid('load',data);
            return false;
        });
        searchFrom.find('input[name=number]').textbox({
            onChange: function(){
               
                $('#search-form-car-baseinfo-driving-license-record').submit();
            }
        });
        //构建查询表单结束
        //初始化添加窗口
        $('#easyui-dialog-car-baseinfo-driving-license-add').dialog({
            title: '添加行驶证',   
            width: '715px',   
            height: '350px',   
            closed: true,   
            cache: true,   
            modal: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var form = $('#easyui-form-car-baseinfo-driving-license-add');
                    if(!form.form('validate')) return false;
                    var data = form.serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/add-driving-license']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('添加成功',data.info,'info');
                                $('#easyui-dialog-car-baseinfo-driving-license-add').dialog('close');
                                $('#easyui-datagrid-car-baseinfo-driving-license-record').datagrid('reload');
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
                    $('#easyui-dialog-car-baseinfo-driving-license-add').dialog('close');
                }
            }]
        });
        //初始化修改窗口
        $('#easyui-dialog-car-baseinfo-driving-license-edit').dialog({
            title: '修改行驶证管理',   
            width: '715px',   
            height: '350px',   
            closed: true,   
            cache: true,   
            modal: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var form = $('#easyui-form-car-baseinfo-driving-license-edit');
                    if(!form.form('validate')) return false;
                    var data = form.serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/edit-driving-license']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('修改成功',data.info,'info');
                                $('#easyui-dialog-car-baseinfo-driving-license-edit').dialog('close');
                                $('#easyui-datagrid-car-baseinfo-driving-license-record').datagrid('reload');
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
                    $('#easyui-dialog-car-baseinfo-driving-license-edit').dialog('close');
                }
            }]  
        });
    }
    CarBaseinfoDrivingLicenseRecord.init();
    //获取选择的记录
    CarBaseinfoDrivingLicenseRecord.getSelected = function(){
        var datagrid = $('#easyui-datagrid-car-baseinfo-driving-license-record');
        var selectRow = datagrid.datagrid('getSelected');
        if(!selectRow){
            $.messager.alert('错误','请选择要操作的记录','error');   
            return false;
        }
        return selectRow;
    }
    //添加
    CarBaseinfoDrivingLicenseRecord.add = function(){
        /*alert('j')*/
        $('#easyui-dialog-car-baseinfo-driving-license-add').dialog('open');
        $('#easyui-dialog-car-baseinfo-driving-license-add').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/add-driving-license','carId'=>$carId]); ?>");
         //alert('j')
    
    }
    //修改 
    CarBaseinfoDrivingLicenseRecord.edit = function(id){
        if(!id){
            var selectRow = this.getSelected();
            if(!selectRow){
                return false;
            }
            id = selectRow.id;
        }
        $('#easyui-dialog-car-baseinfo-driving-license-edit').dialog('open');
        $('#easyui-dialog-car-baseinfo-driving-license-edit').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/edit-driving-license']); ?>&carId="+id);
    }
    //删除
    CarBaseinfoDrivingLicenseRecord.remove = function(){
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
                            $('#easyui-datagrid-car-baseinfo-driving-license-record').datagrid('reload');
                        }else{
                            $.messager.alert('删除失败',data.info,'error');   
                        }
                    }
                });
            }
        });
    }
    //查询
    CarBaseinfoDrivingLicenseRecord.search = function(){
        var form = $('#search-form-car-baseinfo-driving-license-record');
        var data = {};
        var searchCondition = form.serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        $('#easyui-datagrid-car-baseinfo-driving-license-record').datagrid('load',data);
    }
    //查询
    CarBaseinfoDrivingLicenseRecord.resetForm = function(){
        $('#search-form-car-baseinfo-driving-license-record').form('reset');
    }
</script>