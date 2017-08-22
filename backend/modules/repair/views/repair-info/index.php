<table id="easyui-datagrid-repair-repair-info-index"></table>
<div id="easyui-datagrid-repair-repair-info-index-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">
        <div class="data-search-form">
            <form id="search-form-repair-info-index" method="post">
                <ul class="search-main">
                    <li>
                        <div class="item-name">车牌号</div>
                        <div class="item-input">
                            <select
                                    class="easyui-combobox"
                                    style="width:180px;"
                                    name="car_id"
                                    editable="true"
                                    listHeight="200px"
                            >
                                <option value=" ">请选择</option>
                                <?php foreach($formoption['car_id'] as $val){?>
                                    <option value="<?php echo $val['plate_number']; ?>"><?php echo $val['plate_number']; ?></option>
                                <?php }?>
                            </select>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">工单号</div>
                        <div class="item-input">
                            <input class="easyui-textbox" name="order_number" style="width:150px;" data-options="prompt:'请输入',">
                        </div>
                    </li>
                    <li>
                        <div class="item-name">工单类型</div>
                        <div class="item-input">
                            <select class="easyui-combobox" name="order_type" style="width:150px;">
                                <option value=" ">请选择</option>
                                <option value="1">我方报修</option>
                                <option value="2">客户报修</option>
                            </select>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">售后修理厂</div>
                        <div class="item-input">
                            <select
                                    class="easyui-combobox"
                                    style="width:180px;"
                                    name="sale_factory"
                                    editable="true"
                                    listHeight="200px"
                            >
                                <option value=" ">请选择</option>
                                <?php foreach($formoption['repair_company'] as $val){?>
                                    <option value="<?php echo $val['site_name']; ?>"><?php echo $val['site_name']; ?></option>
                                <?php }?>
                            </select>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">审批状态</div>
                        <div class="item-input">
                            <select class="easyui-combobox" name="check_status" style="width:150px;">
                                <option value=" ">请选择</option>
                                <option value="1">维修方案待审核</option>
                                <option value="2">维修方案未通过</option>
                                <option value="3">维修方案已通过</option>
                                <option value="4">完工结算未通过</option>
                                <option value="5">完工结算已通过</option>
                                <option value="6">付款驳回</option>
                                <option value="7">付款完结</option>
                            </select>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">单据状态</div>
                        <div class="item-input">
                            <select class="easyui-combobox" name="bill_status" style="width:150px;">
                                <option value="1">正常</option>
                                <option value="0">作废</option>
                            </select>
                        </div>
                    </li>
                    <li class="search-button">
                        <button type="submit" onclick="RepairInfoIndex.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button type="submit" onclick="RepairInfoIndex.resetForm()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
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
<div id="easyui-dialog-repair-info-index-add"></div>
<div id="easyui-dialog-repair-info-index-check"></div>
<div id="easyui-dialog-repair-info-index-bohui"></div>
<div id="easyui-dialog-repair-info-index-checkmoney"></div>
<div id="easyui-dialog-repair-info-index-finish"></div>
<div id="easyui-dialog-repair-info-index-edit"></div>
<div id="easyui-dialog-repair-repair-info-index-money"></div>
<div id="easyui-dialog-repair-repair-info-index-see"></div>
<script>
    var RepairInfoIndex = new Object();
    RepairInfoIndex.init = function(){
        //获取列表数据
        $('#easyui-datagrid-repair-repair-info-index').datagrid({
            method: 'get',
            url:'<?php echo yii::$app->urlManager->createUrl(['repair/repair-info/get-list']); ?>',
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-repair-repair-info-index-toolbar",
            pagination: true,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: false,
            columns:[[
                {field: 'ck',checkbox: true},
                {field: 'id',hidden:true},
                {field: 'car_id',title: '车牌号',width: 100},
                {field: 'order_number',title: '工单号',width: 100},
                {field: 'order_type',title: '工单类型',width: 100,
                    formatter: function(value,row,index){
                        var new_string =row.order_number.substr(0,2);
                        if(new_string == 'WX'){
                            return '我方报修';
                        }else{
                            return '客户报修';
                        }
                    }
                },
                {field: 'maintain_scene',title: '售后修理厂',width: 100},
                {field: 'check_status',title: '审核状态',width: 100,
                    formatter: function(value,row,index){
                        if(row.check_status == 7){
                            return '<span style="background:lawngreen;">付款完结</span>';
                        }else if(row.check_status == 2){
                            return '<span style="background:red;">维修方案未通过</span>';
                        }else if(row.check_status == 3){
                            return '<span style="background:lawngreen;">维修方案已通过</span>';
                        }else if(row.check_status == 4){
                            return '<span style="background:red;">完工结算未通过</span>';
                        }else if(row.check_status == 5){
                            return '<span style="background:lawngreen;">完工结算已通过</span>';
                        }else if(row.check_status == 6){
                            return '<span style="background:red;">付款驳回</span>';
                        }else {
                            return '<span style="background:yellow;">维修方案待审核</span>';
                        }
                    }
                },
                {field: 'repair_price',title: '维修报价',width: 100},
                {field: 'account_price',title: '结算金额',width: 100},
                {field: 'project_human',title: '维修方案审核人',width: 100},
                {field: 'account_human',title: '完工结算审核人',width: 100},
                {field: 'money_human',title: '付款审核人',width: 100},
                {field: 'service_human',title: '服务顾问',width: 100},
                {field: 'create_time',title: '创建时间',width: 100},
                {field: 'finish_time',title: '完结时间',width: 100},
                {field: 'bill_status',title: '单据状态',width: 100,
                    formatter: function(value,row,index){
                        if(row.bill_status == 1){
                            return '正常';
                        }else if(row.bill_status == 2){
                            return 'null';
                        }else{
                            return '作废';
                        }
                    }
                }
            ]]
        });
        //初始化修改窗口`
        $('#easyui-dialog-repair-info-index-bohui').dialog({
            title: '驳回',
            width: 600,
            height: 300,
            cache: true,
            modal: true,
            closed: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    //回调添加页面submitForm方法
                    var addForm = $('#easyui-form-repair-info-bohui');
                    var checkForm = $('#easyui-form-repair-info-check');
                    var checkForm_1=$('#easyui-form-repair-info-check-money');
                    var newForm = addForm.serialize();
                    console.log(newForm)
                    var checkForm=checkForm.serialize();
                    var checkForm_1=checkForm_1.serialize();
                    var data={
                        a:newForm,
                        b:checkForm,
                        c:checkForm_1
                    }
//                    console.log(newForm);return false;
                    $.ajax({
                        type: 'post',
                        url: '<?php echo yii::$app->urlManager->createUrl(['repair/repair-info/bohui']); ?>',
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status ){
                                $.messager.alert('驳回成功',data.info,'info');
                                $('#easyui-dialog-repair-info-index-bohui').dialog('close');
                                $('#easyui-dialog-repair-info-index-check').dialog('close');
                                $('#easyui-dialog-repair-info-index-checkmoney').dialog('close');
                                $('#easyui-datagrid-repair-repair-info-index').datagrid('reload');
                            }else{
                                $.messager.alert('驳回失败',data.info,'error');
                            }
                        }
                    });
                    $('#easyui-datagrid-repair-repair-info-index').datagrid('reload');
                }
            },{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui-dialog-repair-info-index-bohui').dialog('close');
                }
            }],  
            onClose: function(){
                $(this).dialog('clear');
            }
        });

        $('#easyui-dialog-repair-info-index-edit').dialog({
            title: '修改维修单',
            width: 1000,
            height: 400,
            cache: true,
            modal: true,
            closed: true,
            resizable:true,
            maximizable: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    //回调添加页面submitForm方法
                    var addForm = $('#easyui-form-repair-info-edit');
                    var newForm = addForm.serialize();
//                    console.log(newForm);return false;
                    $.ajax({
                        type: 'post',
                        url: '<?php echo yii::$app->urlManager->createUrl(['repair/repair-info/edit']); ?>',
                        data: newForm,
                        dataType: 'json',
                        success: function(data){
                            if(data.status ){
                                $.messager.alert('修改成功',data.info,'info');
                                $('#easyui-dialog-repair-info-index-edit').dialog('close');
                                $('#easyui-datagrid-repair-repair-info-index').datagrid('reload');
                            }else{
                                $.messager.alert('修改失败',data.info,'error');
                            }
                        }
                    });
                    $('#easyui-datagrid-repair-repair-info-index').datagrid('reload');
                }
            },{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui-dialog-repair-info-index-edit').dialog('close');
                }
            }],  
            onClose: function(){
                $(this).dialog('clear');
            }
        });
        //初始化完工结算窗口`
        $('#easyui-dialog-repair-info-index-finish').dialog({
            title: '完工结算',
            width: 1000,
            height: 400,
            cache: true,
            modal: true,
            closed: true,
            resizable:true,
            maximizable: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    //回调添加页面submitForm方法
                    var addForm = $('#easyui-form-repair-info-finish');
                    var newForm = addForm.serialize();
//                    console.log(newForm);return false;
                    $.ajax({
                        type: 'post',
                        url: '<?php echo yii::$app->urlManager->createUrl(['repair/repair-info/finish']); ?>',
                        data: newForm,
                        dataType: 'json',
                        success: function(data){
                            if(data.status ){
                                $.messager.alert('修改成功',data.info,'info');
                                $('#easyui-dialog-repair-info-index-finish').dialog('close');
                                $('#easyui-datagrid-repair-repair-info-index').datagrid('reload');
                            }else{
                                $.messager.alert('修改失败',data.info,'error');
                            }
                        }
                    });
                    $('#easyui-datagrid-repair-repair-info-index').datagrid('reload');
                }
            },{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui-dialog-repair-info-index-finish').dialog('close');
                }
            }],  
            onClose: function(){
                $(this).dialog('clear');
            }
        });
         //初始化审核维修单窗口`
        $('#easyui-dialog-repair-info-index-check').dialog({
            title: '审核维修单',
            width: 1000,
            height: 400,
            cache: true,
            modal: true,
            closed: true,
            resizable:true,
            maximizable: true,
            buttons: [{
                text:'同意',
                iconCls:'icon-ok',
                handler:function(){
                    var addForm = $('#easyui-form-repair-info-check');
                    var newForm = addForm.serialize();
                    newForm.status=1;
                    $.ajax({
                        type: 'post',
                        url: '<?php echo yii::$app->urlManager->createUrl(['repair/repair-info/check']); ?>&status=1',
                        data: newForm,
                        dataType: 'json',
                        success: function(data){
                            if(data.status ){
                                $.messager.alert('已通过',data.info,'info');
                                $('#easyui-dialog-repair-info-index-check').dialog('close');
                                $('#easyui-datagrid-repair-repair-info-index').datagrid('reload');
                            }else{
                                $.messager.alert('异常',data.info,'error');
                            }
                        }
                    });
                    $('#easyui-datagrid-repair-repair-info-index').datagrid('reload');
                }
            },{
                text:'驳回',
                iconCls:'icon-cancel',
                handler:function(){
                     $('#easyui-dialog-repair-info-index-bohui').dialog('open');
                    $('#easyui-dialog-repair-info-index-bohui').dialog('refresh','<?php echo yii::$app->urlManager->createUrl(['repair/repair-info/bohui']); ?>');
                }
            }],  
            onClose: function(){
                $(this).dialog('clear');
            }
        });
         //初始化审核完工结算窗口`
        $('#easyui-dialog-repair-info-index-checkmoney').dialog({
            title: '审核完工结算',
            width: 1000,
            height: 400,
            cache: true,
            modal: true,
            closed: true,
            resizable:true,
            maximizable: true,
            buttons: [{
                text:'同意',
                iconCls:'icon-ok',
                handler:function(){
                    var addForm = $('#easyui-form-repair-info-check-money');
                    var newForm = addForm.serialize();
                    newForm.status=1;
                    $.ajax({
                        type: 'post',
                        url: '<?php echo yii::$app->urlManager->createUrl(['repair/repair-info/check-money']); ?>&status=1',
                        data: newForm,
                        dataType: 'json',
                        success: function(data){
                            if(data.status ){
                                $.messager.alert('已通过',data.info,'info');
                                $('#easyui-dialog-repair-info-index-checkmoney').dialog('close');
                                $('#easyui-datagrid-repair-repair-info-index').datagrid('reload');
                            }else{
                                $.messager.alert('异常',data.info,'error');
                            }
                        }
                    });
                    $('#easyui-datagrid-repair-repair-info-index').datagrid('reload');
                }
            },{
                text:'驳回',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui-dialog-repair-info-index-bohui').dialog('open');
                    $('#easyui-dialog-repair-info-index-bohui').dialog('refresh','<?php echo yii::$app->urlManager->createUrl(['repair/repair-info/bohui']); ?>');
                }
            }],  
            onClose: function(){
                $(this).dialog('clear');
            }
        });
        //初始化添加窗口`
        $('#easyui-dialog-repair-info-index-add').dialog({
            title: '添加维修单',
            width: 1000,
            height: 400,
            cache: true,
            modal: true,
            closed: true,
            resizable:true,
            maximizable: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    //回调添加页面submitForm方法
                    var addForm = $('#easyui-form-repair-info-add');
                    var newForm = addForm.serialize();
//                    console.log(newForm);return false;
                    $.ajax({
                        type: 'post',
                        url: '<?php echo yii::$app->urlManager->createUrl(['repair/repair-info/add']); ?>',
                        data: newForm,
                        dataType: 'json',
                        success: function(data){
                            if(data.status ){
                                $.messager.alert('新建成功',data.info,'info');
                                $('#easyui-dialog-repair-info-index-add').dialog('close');
                                $('#easyui-datagrid-repair-repair-info-index').datagrid('reload');
                            }else{
                                $.messager.alert('新建失败',data.info,'error');
                            }
                        }
                    });
                    $('#easyui-datagrid-repair-repair-info-index').datagrid('reload');
                }
            },{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui-dialog-repair-info-index-add').dialog('close');
                }
            }],  
            onClose: function(){
                $(this).dialog('clear');
            }
        });
        //初始化付款窗口`
        $('#easyui-dialog-repair-repair-info-index-money').dialog({
            title: '付款操作',
             width: '800px',
            height: '400px',
            cache: true,
            modal: true,
            resizable:true,
            maximizable: true,
            closed: true,
            scroll:true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var datagrid = $('#easyui-datagrid-repair-repair-info-index');
                    var repairData = datagrid.datagrid('getChecked');
                    var order = ' ';
                    for(var i = 0;i< repairData.length;i++){
                        order += ','+repairData[i].id;
                    }
                    var order_no = ' ';
                    for(var i = 0;i< repairData.length;i++){
                        order_no += ','+repairData[i].order_number;
                    }
                    var form = $('#repair-money-feng');
                    if(!form.form('validate')){
                        return false;
                    }
                    var data = form.serialize();
                    data = data+'&order_id='+order+'&order_no='+order_no;
                    var button = $(this);
                    button.linkbutton('disable');
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['repair/repair-info/pay-money']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status == 1){
                                $.messager.alert('付款成功',data.info,'info');
                                $('#easyui-dialog-repair-info-index-add').dialog('close');
                                $('#easyui-datagrid-repair-repair-info-index').datagrid('reload');
                                button.linkbutton('enable');
                            }else if(data.status == 2){
                                $.messager.alert('付款失败',data.info,'error');
                                button.linkbutton('enable');
                            }else{
                                $.messager.alert('付款失败',data.info,'error');
                                button.linkbutton('enable');
                            }
                        }
                    });
                    $('#easyui-datagrid-repair-repair-info-index').datagrid('reload');
                    $('#easyui-dialog-repair-repair-info-index-money').dialog('close');
                }
            },{
                text:'驳回',
                iconCls:'icon-cancel',
                handler:function(){
                    var datagrid = $('#easyui-datagrid-repair-repair-info-index');
                    var repairData = datagrid.datagrid('getChecked');
                    var order = ' ';
                    for(var i = 0;i< repairData.length;i++){
                        order += ','+repairData[i].id;
                    }
                    var form = $('#repair-money-feng');
                    var data = form.serialize();
                    data = data+'&go_back='+order;
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['repair/repair-info/pay-money']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status == 1){
                                $.messager.alert('驳回成功',data.info,'info');
                                $('#easyui-dialog-repair-info-index-add').dialog('close');
                                $('#easyui-datagrid-repair-repair-info-index').datagrid('reload');
                                button.linkbutton('enable');
                            }else if(data.status == 2){
                                $.messager.alert('驳回失败',data.info,'error');
                                button.linkbutton('enable');
                            }else{
                                $.messager.alert('驳回失败',data.info,'error');
                                button.linkbutton('enable');
                            }
                        }
                    });
                    $('#easyui-dialog-repair-repair-info-index-money').dialog('close');
                }
            }]
        });
        //初始化查看详情窗口
        $('#easyui-dialog-repair-repair-info-index-see').dialog({
            title: '维修方案',
            width: '900px',
            height: '400px',
            cache: true,
            modal: true,
            resizable:true,
            maximizable: true,
            closed: true,
            scroll:true,
            buttons: [{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui-dialog-repair-repair-info-index-see').dialog('close');
                }
            }]
        });
    }
    RepairInfoIndex.init();
    //添加方法
    //添加方法
    RepairInfoIndex.add = function(){
         var datagrid = $('#easyui-datagrid-repair-repair-info-index');
        var repairData = datagrid.datagrid('getChecked');
        if(repairData == 0){
            $('#easyui-dialog-repair-info-index-add').dialog('open');
            $('#easyui-dialog-repair-info-index-add').dialog('refresh','<?php echo yii::$app->urlManager->createUrl(['repair/repair-info/add']); ?>');
        }else{
            if(repairData.length>1){
               $.messager.alert('编辑失败','无法修改多行维修方案！请重新选择一行','error');
                    return false; 
            }
            var id = repairData[0].id;
            var check_status=repairData[0].check_status;
            if(check_status!=1 && check_status!=2){
                 $.messager.alert('编辑失败','该维修方案已通过，不可再编辑','error');
                    return false;
            }
            $('#easyui-dialog-repair-info-index-edit').dialog('open');
            $('#easyui-dialog-repair-info-index-edit').dialog('refresh','<?php echo yii::$app->urlManager->createUrl(['repair/repair-info/edit']); ?>&id='+id);
        }
    }
    //完工结算
    RepairInfoIndex.finish = function(){
         var datagrid = $('#easyui-datagrid-repair-repair-info-index');
        var repairData = datagrid.datagrid('getChecked');
        if(repairData == 0){
            $.messager.alert('编辑失败','请选择编辑项','error');
                    return false;
        }else{
            if(repairData.length>1){
               $.messager.alert('编辑失败','无法修改多行完工结算！请重新选择一行','error');
                    return false; 
            }
            var id = repairData[0].id;
            var check_status=repairData[0].check_status;
            if(check_status!=3 && check_status!=4 && check_status!=6){
                 $.messager.alert('编辑失败','请遵守操作流程','error');
                    return false;
            }
            $('#easyui-dialog-repair-info-index-finish').dialog('open');
            $('#easyui-dialog-repair-info-index-finish').dialog('refresh','<?php echo yii::$app->urlManager->createUrl(['repair/repair-info/finish']); ?>&id='+id);
        }
    }

    //驳回方法
    RepairInfoIndex.check = function(){
         var datagrid = $('#easyui-datagrid-repair-repair-info-index');
        var repairData = datagrid.datagrid('getChecked');
        if(repairData == 0){
           $.messager.alert('审核失败','请选择删除项','error');
            return false;
        }else{
            if(repairData.length>1){
               $.messager.alert('编辑失败','一次只能选择一条','error');
                    return false; 
            }
            var id = repairData[0].id;
            var check_status=repairData[0].check_status;
            if(check_status==1 ){
                 
                $('#easyui-dialog-repair-info-index-check').dialog('open');
                $('#easyui-dialog-repair-info-index-check').dialog('refresh','<?php echo yii::$app->urlManager->createUrl(['repair/repair-info/check']); ?>&id='+id);
            }else if(check_status==3 ){
                $('#easyui-dialog-repair-info-index-checkmoney').dialog('open');
                $('#easyui-dialog-repair-info-index-checkmoney').dialog('refresh','<?php echo yii::$app->urlManager->createUrl(['repair/repair-info/check-money']); ?>&id='+id);
            }else{
                $.messager.alert('审核失败','该选项卡不可审核','error');
                    return false;
            }
            
        }
    }


    //删除维修方案
    RepairInfoIndex.del = function(){
        var datagrid = $('#easyui-datagrid-repair-repair-info-index');
        var repairData = datagrid.datagrid('getChecked');
        if(repairData.length == 0){
            $.messager.alert('删除失败','请选择删除项','error');
            return false;
        }
        if(repairData.length > 1){
            $.messager.alert('删除失败','只能选择一项删除','error');
            return false;
        }
        if(repairData[0].check_status > 2 ){
            $.messager.alert('删除失败','只能删除维修方案未审核过的单和维修方案待审核的单','error');
            return false;
        }
        var id = repairData[0].id;
        $.messager.confirm('确认对话框', '确定删除维修方案？', function(r){
            if (r){
                $.ajax({
                    type: 'post',
                    url: "<?php echo yii::$app->urlManager->createUrl(['repair/repair-info/del']); ?>",
                    data: {'id':id},
                    dataType: 'json',
                    success: function(data){
                        if(data.status == 1){
                            $.messager.alert('删除成功',data.info,'info');
                            $('#easyui-datagrid-repair-repair-info-index').datagrid('reload');
                        }else if(data.status == 2){
                            $.messager.alert('删除失败',data.info,'error');
                        }else{
                            $.messager.alert('删除失败',data.info,'error');
                        }
                    }
                });
            }
        });
    }
    RepairInfoIndex.print = function(){
        var datagrid = $('#easyui-datagrid-repair-repair-info-index');
        var repairData = datagrid.datagrid('getChecked');
        if(repairData.length == 0){
            $.messager.alert('打印失败','请选择打印项','error');
            return false;
        }
        if(repairData.length > 1){
            $.messager.alert('打印失败','只能选择一项打印','error');
            return false;
        }
        repairData.check_status = parseInt(repairData[0].check_status);
        if(repairData.check_status != 5){
            $.messager.alert('打印失败','只有完工结算已通过才能打印结算单','error');
            return false;
        }
        var id = repairData[0].id;
        window.open('<?php echo yii::$app->urlManager->createUrl(['repair/repair-info/print']); ?>&print='+id,'完工结算单');
    }
    //作废维修方案
    RepairInfoIndex.abandon = function(){
        var datagrid = $('#easyui-datagrid-repair-repair-info-index');
        var repairData = datagrid.datagrid('getChecked');
        if(repairData.length == 0){
            $.messager.alert('作废失败','请选择作废项','error');
            return false;
        }
        if(repairData.length > 1){
            $.messager.alert('作废失败','只能选择一项作废','error');
            return false;
        }
        if(repairData[0].check_status < 4){
            $.messager.alert('作废失败','结算单审核完后才可作废','error');
            return false;
        }
        if(repairData[0].bill_status = 0){
            $.messager.alert('作废失败','选项已作废，请不要重复操作','error');
            return false;
        }
        var id = repairData[0].id;
        var msg = "维修记录：车牌号["+repairData[0].car_id+"]，工单号["+repairData[0].order_number+"]将作废！";
        $.messager.confirm('确认对话框', msg, function(r){
            if (r){
                $.ajax({
                    type: 'post',
                    url: "<?php echo yii::$app->urlManager->createUrl(['repair/repair-info/abandon']); ?>",
                    data: {id:id},
                    dataType: 'json',
                    success: function(data){
                        if(data.status == 1){
                            $.messager.alert('作废成功',data.info,'info');
                            $('#easyui-datagrid-repair-repair-info-index').datagrid('reload');
                            button.linkbutton('enable');
                        }else if(data.status == 2){
                            $.messager.alert('作废失败',data.info,'error');
                            button.linkbutton('enable');
                        }else{
                            $.messager.alert('作废失败',data.info,'error');
                            button.linkbutton('enable');
                        }
                    }
                });
            }
        });
    }
    //查看详情
    RepairInfoIndex.see = function(){
        var datagrid = $('#easyui-datagrid-repair-repair-info-index');
        var repairData = datagrid.datagrid('getChecked');
        if(repairData.length == 0){
            $.messager.alert('查看失败','请选择查看项','error');
            return false;
        }
        if(repairData.length > 1){
            $.messager.alert('查看失败','只能选择一项查看','error');
            return false;
        }
        var id = repairData[0].id;
        $('#easyui-dialog-repair-repair-info-index-see').dialog('open');
        $('#easyui-dialog-repair-repair-info-index-see').dialog('refresh','<?php echo yii::$app->urlManager->createUrl(['repair/repair-info/see']); ?>&id='+id);
    }
    //付款
    RepairInfoIndex.money = function(){
        var datagrid = $('#easyui-datagrid-repair-repair-info-index');
        var repairData = datagrid.datagrid('getChecked');
        if(repairData.length == 0){
            $.messager.alert('付款失败','请选择付款项','error');
            return false;
        }
        for(var i = 0;i<repairData.length;i++){
            if(repairData[i].check_status < 5){
                $.messager.alert('付款失败','完工结算已通过后才可以付款操作','error');
                return false;
            }
            repairData.check_status = parseInt(repairData[i].check_status);
            if(repairData.check_status != 5){
                $.messager.alert('付款失败','完工结算已通过才能付款','error');
                return false;
            }
//            if(repairData.check_status == 7){
//                $.messager.alert('付款失败','已经付款完结的方案不能重复付款操作,有问题请联系管理员','error');
//                return false;
//            }
//            if(repairData.check_status == 6){
//                $.messager.alert('付款失败','已经驳回的方案不能重复付款操作,有问题请联系管理员','error');
//                return false;
//            }
        }
        $('#easyui-dialog-repair-repair-info-index-money').dialog('open');
        $('#easyui-dialog-repair-repair-info-index-money').dialog('refresh','<?php echo yii::$app->urlManager->createUrl(['repair/repair-info/pay-money']); ?>');
    }
    //构建查询表单
    var searchForm = $('#search-form-repair-info-index');
    /**查询表单提交事件**/
    searchForm.submit(function(){
        var data = {};
        var searchCondition = $(this).serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        $('#easyui-datagrid-repair-repair-info-index').datagrid('load',data);
        return false;
    });

    //重置查询表单
    RepairInfoIndex.resetForm = function(){
        var easyuiForm = $('#search-form-repair-info-index');
        easyuiForm.form('reset');
    }

    //条件搜索查询
    RepairInfoIndex.search = function(){
        var form = $('#search-form-repair-info-index');
        var data = {};
        var searchCondition = form.serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        $('#easyui-datagrid-repair-repair-info-index').datagrid('load',data);
    }
</script>