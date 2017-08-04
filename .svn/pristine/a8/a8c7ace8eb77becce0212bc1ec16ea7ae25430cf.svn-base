<table id="easyui-datagrid-car-office-car-register-index"></table> 
<div id="easyui-datagrid-car-office-car-register-index-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-car-office-car-register-index">
                <ul class="search-main">
                    <li>
                        <div class="item-name">车牌号</div>
                        <div class="item-input">
                            <input name="plate_number" style="width:150px;" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">车辆状态</div>
                        <div class="item-input">
                        
                         <input style="width:200px;" name="status" />
                        </div>

                    </li>
                    <li>
                        <div class="item-name">用车人</div>
                        <div class="item-input">
                            <input name="username" style="width:150px;" />
                        </div>
                    </li>
                    <li class="search-button">
                        <button class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button onclick="CarOfficeCarRegisterIndex.resetForm();return false;" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
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
                <button onclick="<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon'] ;?>'"><?= $val['text'] ;?></button>
            <?php } ?>
        </div>
    <?php } ?>

</div>

<!-- 窗口 -->
<div id="easyui-dialog-car-office-car-register-index-add"></div>
<div id="easyui-dialog-car-office-car-register-index-add2"></div>
<div id="easyui-dialog-car-office-car-register-index-return"></div>

<script>
    var CarOfficeCarRegisterIndex = new Object();
    //配置项
    
    CarOfficeCarRegisterIndex.init = function(){
        //获取列表数据
        $('#easyui-datagrid-car-office-car-register-index').datagrid({  
            method: 'get', 
            url:"<?php echo yii::$app->urlManager->createUrl(['car/office-car-register/get-list']); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-car-office-car-register-index-toolbar",
            pagination: true,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
            pageSize: 20,
            sortName: 'next_valid_date',
            sortOrder: 'asc',
            frozenColumns: [[
                {field: 'ck',checkbox: true}, 
                {field: 'id',title: 'id',hidden: true},   
                {field: 'plate_number',title: '车牌号',width: 100,sortable: true,align: 'center'}
            ]],
            columns:[[
                

                {
                    field: 'car_brand',title: '车辆品牌',width: 120,align: 'center',
                    sortable: true,
                
                },

                {
                    field: 'car_model',title: '车型名称',width: 120,align: 'center',
                    sortable: true,
                    
                },
                
                {field: 'status',title: '使用状态',width: 120,align: 'center',sortable: true,formatter: function(value){
                        switch(value){
                            case 'available':
                                return '<span style="background-color:#32CD32;color:#fff;padding:2px 5px;">可用</span>';
                            case 'out_car':
                                return '<span style="background-color:#FFCC01;color:#fff;padding:2px 5px;">出车</span>';
                            case 'repair':
                                return '<span style="background-color:#FF4040;color:#fff;padding:2px 5px;">维修</span>';
                        }
                    }},
                {field: 'remain_distance',title: '剩余续航里程',width: 120,align: 'center',sortable: true},
                {field: 'department_name',title: '用车部门',width: 120,align: 'center',sortable: true},
                {field: 'username',title: '用车人',width: 120,align: 'center',sortable: true},
                {field: 'reg_name',title: '登记人',width: 120,align: 'center',sortable: true},
                {field: 'reg_time',title: '登记时间',width: 120,align: 'center',sortable: true},
                
            ]],
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

       
        //初始化添加窗口 (派车登记)
        $('#easyui-dialog-car-office-car-register-index-add').dialog({
            title: '公务车出车登记',   
            width: '780px',   
            height: '350px',   
            closed: true,   
            cache: true,   
            modal: true,
            resizable:true,
            maximizable: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var form = $('#easyui-form-car-office-car-register-add');
                    if(!form.form('validate')) return false;
                    var data = form.serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['car/office-car-register/add']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('添加成功',data.info,'info');
                                $('#easyui-dialog-car-office-car-register-index-add').dialog('close');
                                $('#easyui-datagrid-car-office-car-register-index').datagrid('reload');
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
                    $('#easyui-dialog-car-office-car-register-index-add').dialog('close');
                }
            }],
            onClose: function(){
                $(this).dialog('clear');
            }
        });

        //添加方法
    CarOfficeCarRegisterIndex.lend = function(){
        $('#easyui-dialog-car-office-car-register-index-add').dialog('open');
        $('#easyui-dialog-car-office-car-register-index-add').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/office-car-register/add']); ?>");
    }

    //初始化还车登记窗口
        $('#easyui-dialog-car-office-car-register-index-return').dialog({
            title: '公务车还车登记',   
            width: '780px',   
            height: '250px',   
            closed: true,   
            cache: true,   
            modal: true,
            resizable:true,
            maximizable: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var form = $('#easyui-form-car-office-car-register-return');
                    if(!form.form('validate')) return false;
                    var data = form.serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['car/office-car-register/return']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('添加成功',data.info,'info');
                                $('#easyui-dialog-car-office-car-register-index-return').dialog('close');
                                $('#easyui-datagrid-car-office-car-register-index').datagrid('reload');
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
                    $('#easyui-dialog-car-office-car-register-index-return').dialog('close');
                }
            }],
            onClose: function(){
                $(this).dialog('clear');
            }
        });

       
    CarOfficeCarRegisterIndex.getSelected = function(all){
        var datagrid = $('#easyui-datagrid-car-office-car-register-index');
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

    CarOfficeCarRegisterIndex.return = function(id){
        /*console.log(id);*/
        if(!id){
            var selectRow = this.getSelected();
            if(!selectRow){
                return false;
            }
            id = selectRow.id;
        }
        //console.log(id);
        if(selectRow.status == "available"){
            $.messager.alert('错误','此车不需要归还','error'); 
        } else {
             $('#easyui-dialog-car-office-car-register-index-return').dialog('open');
            $('#easyui-dialog-car-office-car-register-index-return').dialog('refresh','<?php echo yii::$app->urlManager->createUrl(['car/office-car-register/return']); ?>&id='+id);
        }
       
    }

    //派车
    CarOfficeCarRegisterIndex.lend = function(id){
        /*console.log(id);*/
        if(!id){
            var selectRow = this.getSelected();
            if(!selectRow){
                return false;
            }
            id = selectRow.id;
        }
        //console.log(selectRow);
        
        if(selectRow.status == "out_car"){
            /*alert('此车已派出');*/
             $.messager.alert('错误','此车已派出,请重新选择','error'); 
        } else {
        $('#easyui-dialog-car-office-car-register-index-add').dialog('open');
        $('#easyui-dialog-car-office-car-register-index-add').dialog('refresh','<?php echo yii::$app->urlManager->createUrl(['car/office-car-register/add']); ?>&id='+id);
        }

        
    }

    //删除车辆
    CarOfficeCarRegisterIndex.remove = function(){
        var selectRow = this.getSelected();
        if(!selectRow) return false;
        var id = selectRow.id;
        $.messager.confirm('确定删除','您确定要从公务车中移除该车辆吗？',function(r){
            if(r){
                $.ajax({
                    type: 'get',
                    url: '<?php echo yii::$app->urlManager->createUrl(['car/office-car-register/remove']); ?>',
                    data: {id: id},
                    dataType: 'json',
                    success: function(data){
                        if(data.status){
                            $.messager.alert('删除成功',data.info,'info');
                            $('#easyui-datagrid-car-office-car-register-index').datagrid('reload');
                        }else{
                            $.messager.alert('删除失败',data.info,'error');   
                        }
                    }
                });
            }
        });
    }

    //初始化添加窗口 (添加公务车)
        $('#easyui-dialog-car-office-car-register-index-add2').dialog({
            title: '添加公务车',   
            width: '500px',   
            height: '250px',   
            closed: true,   
            cache: true,   
            modal: true,
            resizable:true,
            maximizable: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var form = $('#easyui-form-car-office-car-register-add2');
                    if(!form.form('validate')) return false;
                    var data = form.serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['car/office-car-register/add2']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('添加成功',data.info,'info');
                                $('#easyui-dialog-car-office-car-register-index-add2').dialog('close');
                                $('#easyui-datagrid-car-office-car-register-index').datagrid('reload');
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
                    $('#easyui-dialog-car-office-car-register-index-add2').dialog('close');
                }
            }],
            onClose: function(){
                $(this).dialog('clear');
            }
        });

        //添加方法
    CarOfficeCarRegisterIndex.add = function(){
        $('#easyui-dialog-car-office-car-register-index-add2').dialog('open');
        $('#easyui-dialog-car-office-car-register-index-add2').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/office-car-register/add2']); ?>");
    }

       
        //构建查询表单结束
    //mmmmmmm
    //构建查询表单
        var searchForm = $('#search-form-car-office-car-register-index');
        /**查询表单提交事件**/
        searchForm.submit(function(){
            var data = {};
            var searchCondition = $(this).serializeArray();
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = searchCondition[i]['value'];
            }
            $('#easyui-datagrid-car-office-car-register-index').datagrid('load',data);
            return false;
        });
        searchForm.find('input[name=plate_number]').textbox({
            onChange: function(){
                searchForm.submit();
            }
        });
         searchForm.find('input[name=username]').textbox({
            onChange: function(){
                searchForm.submit();
            }
        });
        

        searchForm.find('input[name=status]').combobox({
            valueField:'value',
            textField:'text',
            editable: false,
            panelHeight:'auto',
            data: [{"value": '',"text": '不限'},{"value": 'available',"text": '可用'},{"value": 'out_car',"text": '出车'},{"value": 'repair',"text": '维修'}],
            onSelect: function(){
                searchForm.submit();
            }
        });
        searchForm.find('input[name=car_type]').combobox({
            valueField:'value',
            textField:'text',
            editable: false,
            panelHeight:'auto',
            data: [{"value": '',"text": '不限'},{"value": 1,"text": '自用'},{"value": 2,"text": '备用'}],
            onSelect: function(){
                searchForm.submit();
            }
        });



    }
    

    //按条件导出车辆列表
    CarOfficeCarRegisterIndex.export = function(){
        var url = "<?php echo yii::$app->urlManager->createUrl(['car/office-car-register/export-width-condition']);?>";
        var form = $('#search-form-car-office-car-register-index');
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

   
    //重置查询表单
    CarOfficeCarRegisterIndex.resetForm = function(){
        var easyuiForm = $('#search-form-car-office-car-register-index');
        easyuiForm.form('reset');
        easyuiForm.submit();
    }



    CarOfficeCarRegisterIndex.init();
</script>
