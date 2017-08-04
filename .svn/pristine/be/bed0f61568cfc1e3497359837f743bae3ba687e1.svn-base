<table id="easyui-datagrid-car-fault-all-index"></table> 
<div id="easyui-datagrid-car-fault-index-all-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-car-fault-all-index">
                <ul class="search-main">
                    <li>
                        <div class="item-name">车牌号/车架号</div>
                        <div class="item-input">
                            <input class="easyui-textbox" name="plate_number" style="width:100%;"
                                   data-options="
                                        onChange:function(){
                                            CarFaultAllIndex.search();
                                        }
                                   "
                                />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">客户名称</div>
                        <div class="item-input">
                            <input class="easyui-textbox" name="customer_name" style="width:100%;"
                                   data-options="
                                        onChange:function(){
                                            CarFaultAllIndex.search();
                                        }
                                   "
                                />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">客户类型</div>
                        <div class="item-input">
                            <select class="easyui-combobox"  name="customer_type" style="width:100%;"
                                    data-options="
                                        panelHeight:'auto',
                                        editable:false,
                                        onChange:function(){
                                            CarFaultAllIndex.search();
                                        }
                                    "
                                >
                                <option value="">--不限--</option>
                                <?php foreach($config['customer_type'] as $val){ ?>
                                    <option value="<?= $val['value'] ?>"><?= $val['text'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">故障编号</div>
                        <div class="item-input">
                            <input class="easyui-textbox" name="number" style="width:100%;"
                                   data-options="
                                         onChange:function(){
                                             CarFaultAllIndex.search();
                                         }
                                    "
                                />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">故障现象描述</div>
                        <div class="item-input">
                            <input class="easyui-textbox" name="f_desc" style="width:100%;"
                                   data-options="
                                        onChange:function(){
                                            CarFaultAllIndex.search();
                                        }
                                   "
                                />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">当前状态</div>
                        <div class="item-input">
                            <select class="easyui-combobox"  name="fault_status" style="width:100%;"
                                    data-options="
                                        panelHeight:'auto',
                                        editable:false,
                                        onChange:function(){
                                            CarFaultAllIndex.search();
                                        }
                                    "
                            >
                                <option value="">--不限--</option>
                                <?php foreach($config['fault_status'] as $val){ ?>
                                    <option value="<?= $val['value'] ?>"><?= $val['text'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">本方初次受理人</div>
                        <div class="item-input">
                            <input class="easyui-textbox" name="ap_name" style="width:100%;"
                                   data-options="
                                        onChange:function(){
                                            CarFaultAllIndex.search();
                                        }
                                   "
                                />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">车辆品牌</div>
                        <div class="item-input">
                            <input id="CarFaultAllIndex_searchForm_chooseBrand" name="brand_id"  style="width:100%;"  />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">车辆型号</div>
                        <div class="item-input">
                            <input class="easyui-textbox" name="car_model" style="width:100%;"
                                   data-options="
                                        onChange:function(){
                                            CarFaultAllIndex.search();
                                        }
                                   "
                                />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">车辆运营公司</div>
                        <div class="item-input">
                            <input id="CarFaultAllIndex_searchForm_chooseOperatingCompany" name="operating_company_id"  style="width:100%;"  />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">登记时间</div>
                        <div class="item-input">
                            <input class="easyui-datebox" type="text" name="regDatetime_start" style="width:93px;"
                                   data-options="
                                        onChange:function(){
                                            CarFaultAllIndex.search();
                                        }
                                   "
                                />
                            -
                            <input class="easyui-datebox" type="text" name="regDatetime_end" style="width:93px;"
                                   data-options="
                                        onChange:function(){
                                            CarFaultAllIndex.search();
                                        }
                                   "
                                />
                        </div>
                    </li>
                    <li class="search-button">
                        <a href="javascript:void(0)" onclick="CarFaultAllIndex.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                        <a href="javascript:void(0)" onclick="CarFaultAllIndex.reset()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</a>
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
                <a href="javascript:void(0)" onclick="<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon']; ?>'"><?= $val['text']; ?></a>
            <?php } ?>
        </div>
    <?php } ?>

</div>

<div id="easyui-dialog-car-fault-all-index-register"></div>
<div id="easyui-dialog-car-fault-all-index-edit"></div>
<div id="easyui-dialog-car-fault-all-index-dispose"></div>
<div id="easyui-window-car-fault-all-index-detail"></div>

<script>
    var CarFaultAllIndex = {
        param: {
            CONFIG: <?= json_encode($config); ?>,
            URL:{
                'getAllList': "<?php echo yii::$app->urlManager->createUrl(['car/fault/get-all-list']); ?>",
                'register': "<?php echo yii::$app->urlManager->createUrl(['car/fault/register']); ?>",
                'edit': "<?php echo yii::$app->urlManager->createUrl(['car/fault/edit']); ?>",
                'remove': "<?php echo yii::$app->urlManager->createUrl(['car/fault/remove']); ?>",
                'dispose': "<?php echo yii::$app->urlManager->createUrl(['car/fault/dispose']); ?>",
                'detail': "<?php echo yii::$app->urlManager->createUrl(['car/fault/detail']); ?>",
                'exportWithCondition': "<?php echo yii::$app->urlManager->createUrl(['car/fault/export-with-condition']); ?>"
            }
        },
        init: function () {
            // 初始化列表
            $('#easyui-datagrid-car-fault-all-index').datagrid({
                method: 'get',
                url: CarFaultAllIndex.param.URL.getAllList,
                fit: true,
                border: false,
                toolbar: "#easyui-datagrid-car-fault-index-all-toolbar",
                pagination: true,
                loadMsg: '数据加载中...',
                striped: true,
                checkOnSelect: true,
                rownumbers: true,
                singleSelect: false,
                pageSize: 20,
                frozenColumns: [[
                    {field: 'ck', checkbox: true},
                    {field: 'id', title: 'id', hidden: true},
                    {field: 'plate_number', title: '故障车辆', width: 70, align: 'center', sortable: true}
                ]],
                columns: [[
                    {field: 'vehicle_dentification_number', title: '车架号', width: 115, align: 'center', sortable: true},
                    {field: 'customer_name',title: '客户名称',width: 180,halign: 'center',sortable: true,
                        formatter: function(value,row,index){ //企业/个人客户名称
                            if(row.cCustomer){
                                return row.cCustomer;
                            }else if(row.pCustomer){
                                return row.pCustomer;
                            }else{
                                return '';
                            }
                        }
                    },
                    {field: 'customer_type',title: '客户类型',width: 65,align: 'center',
                        formatter: function(value,row,index){ //企业/个人客户
                            if(row.cCustomer){
                                return '企业';
                            }else if(row.pCustomer){
                                return '个人';
                            }else{
                                return '';
                            }
                        }
                    },
                    {field: 'number', title: '故障编号', width: 95, align: 'center', sortable: true},
                    {field: 'f_desc', title: '故障现象描述', width: 280, halign: 'center', sortable: true},
                    {
                        field: 'fault_status', title: '当前状态', width: 60, align: 'center', sortable: true,
                        formatter: function (value, row, index) {
                            try {
                                var str = 'CarFaultAllIndex.param.CONFIG.fault_status.' + value + '.text';
                                switch (value) {
                                    case 'RECEIVED':
                                        return '<span style="background-color:#D3D3D3;color:#fff;padding:2px 5px;">' + eval(str) + '</span>';
                                    case 'SENT':
                                        return '<span style="background-color:#FFA0A0;color:#fff;padding:2px 5px;">' + eval(str) + '</span>';
                                    case 'REPAIRING':
                                        return '<span style="background-color:#FFCC01;color:#fff;padding:2px 5px;">' + eval(str) + '</span>';
                                    case 'PROCESSED':
                                        return '<span style="background-color:#05CD69;color:#fff;padding:2px 5px;">' + eval(str) + '</span>';
                                    default:
                                        return value;
                                }
                            } catch (e) {
                                return value;
                            }
                        }
                    },
                    {field: 'ap_name', title: '本方初次受理人', width: 90, align: 'center', sortable: true},
                    {field: 'fb_date', title: '反馈时间', width: 80, align: 'center', sortable: true},
                    {field: 'expect_end_date', title: '预计完结时间', width: 80, align: 'center', sortable: true},
                    {field: 'countdown', title: '倒计时', width: 70, align: 'center', sortable: true},
                    {field: 'brand_id', title: '车辆品牌', width: 75, align: 'center', sortable: true},
                    {field: 'car_model', title: '车辆型号', width: 115, halign: 'center', sortable: true,
                    	formatter: function(value,row,index){
    						var status = <?php echo json_encode($config['car_model_name']); ?>;
                            try{
                                return status[value]['text'];
                            }catch(e){}
            		    }
                    },
                    {field: 'operating_company_id', title: '车辆运营公司', width: 170, halign: 'center', sortable: true},
                    {field: 'reg_datetime', title: '登记时间', width: 130, align: 'center', sortable: true},
                    {field: 'username', title: '登记人员', width: 100, halign: 'center', sortable: true}
                ]],
                onLoadSuccess: function (data) {
                    //单元格内容悬浮提示，doCellTip()是在入口文件index.php中拓展的。
                    $(this).datagrid('doCellTip', {
                        position: 'bottom',
                        maxWidth: '300px',
                        onlyShowInterrupt: true, //false时所有单元格都显示提示；true时配合specialShowFields自定义要提示的列
                        specialShowFields: [     //需要提示的列
                            //{field: 'sketch', showField: 'sketch'}
                        ],
                        tipStyler: {
                            backgroundColor: '#E4F0FC',
                            borderColor: '#87A9D0',
                            boxShadow: '1px 1px 3px #292929'
                        }
                    });
                }
            });
            // 初始化【故障登记】窗口
            $('#easyui-dialog-car-fault-all-index-register').dialog({
                title: '车辆故障信息登记',
                width: 1100,
                height: 600,
                cache: true,
                modal: true,
                closed: true,
                maximizable: true,
                draggable: true,
                onClose: function () {
                    $(this).dialog('clear');
                },
                buttons: [{
                    text: '确定',
                    iconCls: 'icon-ok',
                    handler: function () {
                        var form = $('#easyui-form-car-fault-register');
                        if (!form.form('validate')) {
                            return false;
                        }
                        $.ajax({
                            type: 'post',
                            url: CarFaultAllIndex.param.URL.register,
                            data: form.serialize(),
                            dataType: 'json',
                            success: function (data) {
                                if (data.status) {
                                    $.messager.show({
                                         title: '登记成功',
                                         msg: data.info
                                    });
                                    $('#easyui-datagrid-car-fault-all-index').datagrid('reload');
                                    $('#easyui-dialog-car-fault-all-index-register').dialog('close');
                                } else {
                                    $.messager.alert('登记失败', data.info, 'error');
                                }
                            }
                        });
                    }
                }, {
                    text: '取消',
                    iconCls: 'icon-cancel',
                    handler: function () {
                        $('#easyui-dialog-car-fault-all-index-register').dialog('close');
                    }
                }]
            });
            // 初始化【故障修改】窗口
            $('#easyui-dialog-car-fault-all-index-edit').dialog({
                title: '车辆故障信息修改',
                width: 1100,
                height: 600,
                cache: true,
                modal: true,
                closed: true,
                maximizable: true,
                draggable: true,
                onClose: function () {
                    $(this).dialog('clear');
                },
                buttons: [{
                    text: '确定',
                    iconCls: 'icon-ok',
                    handler: function () {
                        var form = $('#easyui-form-car-fault-edit');
                        if (!form.form('validate')) {
                            return false;
                        }
                        $.ajax({
                            type: 'post',
                            url: CarFaultAllIndex.param.URL.edit,
                            data: form.serialize(),
                            dataType: 'json',
                            success: function (data) {
                                if (data.status) {
                                    $.messager.show({
                                        title: '修改成功',
                                        msg: data.info
                                    });
                                    $('#easyui-datagrid-car-fault-all-index').datagrid('reload');
                                    $('#easyui-dialog-car-fault-all-index-edit').dialog('close');
                                } else {
                                    $.messager.alert('修改失败', data.info, 'error');
                                }
                            }
                        });
                    }
                }, {
                    text: '取消',
                    iconCls: 'icon-cancel',
                    handler: function () {
                        $('#easyui-dialog-car-fault-all-index-edit').dialog('close');
                    }
                }]
            });
            // 初始化【故障处理】窗口
            $('#easyui-dialog-car-fault-all-index-dispose').dialog({
                title: '车辆故障处理',
                width: 1000,
                height: 550,
                cache: true,
                modal: true,
                closed: true,
                maximizable: true,
                draggable: true,
                onClose: function () {
                    $(this).dialog('clear');
                }
            });
            // 初始化【查看详细】窗口
            $('#easyui-window-car-fault-all-index-detail').window({
                title: '查看车辆故障详情',
                width: 1000,
                height: 500,
                cache: true,
                modal: true,
                closed: true,
                maximizable: true,
                minimizable: false,
                collapsible: false,
                onClose: function () {
                    $(this).window('clear');
                }
            });
            // 初始化【车辆品牌】combotree
            $('#CarFaultAllIndex_searchForm_chooseBrand').combotree({
                url: "<?php echo yii::$app->urlManager->createUrl(['car/combotree/get-car-brands']); ?>&isShowNotLimitOption=1",
                panelHeight: 'auto',
                valueField: 'id',
                textField: 'text',
                editable: false,
                onChange: function () {
                    CarFaultAllIndex.search();
                }
            });
            // 初始化【车辆运营公司】combotree
            $('#CarFaultAllIndex_searchForm_chooseOperatingCompany').combotree({
                url: "<?php echo yii::$app->urlManager->createUrl(['operating/combotree/get-operating-company']); ?>&isShowNotLimitOption=1",
                panelHeight: 'auto',
                panelWidth: 'auto',
                valueField: 'id',
                textField: 'text',
                editable: false,
                onChange: function () {
                    CarFaultAllIndex.search();
                }
            });
        },
        //获取当前所选择的记录
        getCurrentSelected: function(multiline){
            var datagrid = $('#easyui-datagrid-car-fault-all-index');
            var selectRows = datagrid.datagrid('getSelections');
            if(selectRows.length <= 0){
                $.messager.show({
                    title: '请选择',
                    msg: '请先选择要操作的记录！'
                });
                return false;
            }
            if(multiline){
                return selectRows;
            }else{
                if(selectRows.length > 1){
                    $.messager.show({
                        title: '提醒',
                        msg: '该功能不能批量操作！<br/>如果你选择了多条记录，则默认操作的是第一条记录！'
                    });
                }
                return selectRows[0];
            }
        },
        //故障登记
        register: function(){
            $('#easyui-dialog-car-fault-all-index-register')
                .dialog('open')
                .dialog('refresh',CarFaultAllIndex.param.URL.register);
        },
        //故障修改
        edit: function(){
            var selectRow = this.getCurrentSelected();
            if(!selectRow){
                return false;
            }
            var id = selectRow.id;
            $('#easyui-dialog-car-fault-all-index-edit')
                .dialog('open')
                .dialog('refresh',CarFaultAllIndex.param.URL.edit + "&id=" + id);
        },
        //故障删除
        remove: function(){
            var selectRow = this.getCurrentSelected();
            if(!selectRow){
                return false;
            }
            var id = selectRow.id;
            $.messager.confirm('确定删除','你确定要删除所选车辆故障信息吗？',function(r){
                if(r){
                    $.ajax({
                        type: 'get',
                        url: CarFaultAllIndex.param.URL.remove + "&id=" + id,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.show({
                                    title: '删除成功',
                                    msg: data.info
                                });
                                $('#easyui-datagrid-car-fault-all-index').datagrid('reload');
                            }else{
                                $.messager.alert('删除失败',data.info,'error');
                            }
                        }
                    });
                }
            });
        },
        //故障处理
        dispose: function(){
            var selectRow = this.getCurrentSelected();
            if(!selectRow){
                return false;
            }
            var id = selectRow.id;
            $('#easyui-dialog-car-fault-all-index-dispose')
                .dialog('open')
                .dialog('refresh',CarFaultAllIndex.param.URL.dispose + "&id=" + id);
        },
        //查看详细
        detail: function(){
            var selectRow = this.getCurrentSelected();
            if(!selectRow){
                return false;
            }
            var id = selectRow.id;
            $('#easyui-window-car-fault-all-index-detail')
                .window('open')
                .window('refresh',CarFaultAllIndex.param.URL.detail + '&id=' + id);
        },
        //按条件导出
        exportWithCondition: function(){
            var form = $('#search-form-car-fault-all-index');
            var searchConditionStr = form.serialize();
            window.open(CarFaultAllIndex.param.URL.exportWithCondition + '&' + searchConditionStr);
        },
        //导出指定行
        exportSpecifiedLines: function(){
            var selectRows = this.getCurrentSelected(true);
            if(!selectRows){
                return false;
            }
            var ids = [];
            $.each(selectRows,function(i,row){
                ids.push(row.id);
            });
            var idStr = ids.join(',');
            window.open(CarFaultAllIndex.param.URL.exportWithCondition + '&idStr=' + idStr);
        },
        //查询
        search: function(){
            var data = $('#search-form-car-fault-all-index').serializeArray();
            var searchData = {};
            for(var i in data){
                searchData[data[i].name] = $.trim(data[i].value);
            }
            $('#easyui-datagrid-car-fault-all-index').datagrid('load',searchData);
        },
        //重置
        reset: function(){
            $('#search-form-car-fault-all-index').form('reset');
            CarFaultAllIndex.search()
        }
    }
    // 执行初始化函数
    CarFaultAllIndex.init();
</script>