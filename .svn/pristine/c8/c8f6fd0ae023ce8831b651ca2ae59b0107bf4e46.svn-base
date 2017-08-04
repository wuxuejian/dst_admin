<table id="chargeChargeCardIndex_datagrid"></table> 
<div id="chargeChargeCardIndex_datagridToolbar">
    <div class="easyui-panel" title="检索区域" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="chargeChargeCardIndex_searchFrom">
                <ul class="search-main">
                    <li>
                        <div class="item-name">电卡编号</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="cc_code" style="width:100%;"  />
                        </div>
                    </li>                                     
					<li>
                        <div class="item-name">电卡类型</div>
                        <div class="item-input">
                            <select class="easyui-combobox"  name="cc_type" style="width:100%;" data-options="panelHeight:'auto',editable:false">
                                <option value="" selected="selected">--不限--</option>
                                <?php foreach($config['cc_type'] as $val){ ?>
                                <option value="<?= $val['value'] ?>"><?= $val['text'] ?></option>
                                <?php } ?>
                            </select>
						</div>
                    </li>
					<li>
                        <div class="item-name">电卡状态</div>
                        <div class="item-input">
                            <select class="easyui-combobox"  name="cc_status" style="width:100%;" data-options="panelHeight:'auto',editable:false">
                                <option value="" selected="selected">--不限--</option>
                                <?php foreach($config['cc_status'] as $val){ ?>
                                <option value="<?= $val['value'] ?>"><?= $val['text'] ?></option>
                                <?php } ?>
                            </select>
						</div>
                    </li>
                    <li>
                        <div class="item-name">会员编号</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="cc_holder_code" style="width:100%;"  />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">会员手机</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="cc_holder_mobile" style="width:100%;"  />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">制卡日期</div>
                        <div class="item-input-datebox">
                            <input class="easyui-datebox" type="text" name="cc_start_date_start" style="width:90px;"  /> -
                            <input class="easyui-datebox" type="text" name="cc_start_date_end" style="width:90px;"  />
                        </div>
                    </li>
                    <li class="search-button">
                        <a href="javascript:chargeChargeCardIndex.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                        <a href="javascript:chargeChargeCardIndex.reset()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</a>
                    </li>
                </ul>
            </form>
        </div>
    </div>

    <?php if(!empty($buttons)){ ?>
        <div class="easyui-panel" title="电卡列表" style="padding:3px 2px;width:100%;" data-options="
        iconCls: 'icon-table-list',
        border: false
    ">
            <?php foreach($buttons as $val){ ?>
                <a href="javascript:<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon'] ;?>'"><?= $val['text'] ;?></a>
            <?php } ?>
        </div>
    <?php } ?>

</div>

<!-- 窗口 -->
<div id="chargeChargeCardIndex_addEditWin"></div>
<div id="chargeChargeCardIndex_rechargeWin"></div>
<div id="chargeChargeCardIndex_scanCardDetailsWin"></div>
<!-- 窗口 -->

<script>
	// 初始数据
	var chargeChargeCardIndex_CONFIG = <?php echo json_encode($config); ?>;

    // 请求的URL
    var chargeChargeCardIndex_URL_getList = "<?php echo yii::$app->urlManager->createUrl(['charge/charge-card/get-list']); ?>";
    var chargeChargeCardIndex_URL_add = "<?php echo yii::$app->urlManager->createUrl(['charge/charge-card/add']); ?>";
    var chargeChargeCardIndex_URL_edit = "<?php echo yii::$app->urlManager->createUrl(['charge/charge-card/edit']); ?>";
    var chargeChargeCardIndex_URL_remove = "<?php echo yii::$app->urlManager->createUrl(['charge/charge-card/remove']); ?>";
    var chargeChargeCardIndex_URL_recharge = "<?php echo yii::$app->urlManager->createUrl(['charge/charge-card/recharge']); ?>";
    var chargeChargeCardIndex_URL_scanCardDetails = "<?php echo yii::$app->urlManager->createUrl(['charge/charge-card/scan-card-details']); ?>";
    var chargeChargeCardIndex_URL_exportGridData = "<?php echo yii::$app->urlManager->createUrl(['charge/charge-card/export-grid-data']); ?>";

    var chargeChargeCardIndex = {
        // 初始化
        init: function () {
            //--初始化表格
            $('#chargeChargeCardIndex_datagrid').datagrid({
                method: 'get',
                url: chargeChargeCardIndex_URL_getList,
                toolbar: "#chargeChargeCardIndex_datagridToolbar",
                fit: true,
                border: false,
                pagination: true,
                loadMsg: '数据加载中...',
                striped: true,
                checkOnSelect: true,
                rownumbers: true,
                singleSelect: true,
                pageSize: 20,
                frozenColumns: [[
                    {field: 'ck', checkbox: true},
                    {field: 'cc_id', title: '电卡ID', width: 40, align: 'center', hidden: true},
                    {field: 'cc_code', title: '电卡编号', width: 100, align: 'center', sortable: true}
                ]],
                columns: [[
                    {field: 'cc_type', title: '电卡类型', align: 'center', width: 80, sortable: true,
                        formatter: function (value, row, index) {
                            try {
                                var str = 'chargeChargeCardIndex_CONFIG.cc_type.' + value + '.text';
                                return eval(str);
                            } catch (e) {
                                return value;
                            }
                        }
                    },
                    {field: 'cc_status', title: '电卡状态', align: 'center', width: 80, sortable: true,
                        formatter: function (value, row, index) {
                            try {
                                var str = 'chargeChargeCardIndex_CONFIG.cc_status.' + value + '.text';
                                switch (value) {
                                    case 'UNACTIVATED':
                                        return '<span style="background-color:#C0C0E0;color:#fff;padding:2px 5px;">' + eval(str) + '</span>';
                                    case 'ACTIVATED':
                                        return '<span style="background-color:#05CD69;color:#fff;padding:2px 5px;">' + eval(str) + '</span>';
                                    case 'LOCKED':
                                        return '<span style="background-color:#FFCC01;color:#fff;padding:2px 5px;">' + eval(str) + '</span>';
                                    case 'STOPPED':
                                        return '<span style="background-color:#E7E7E7;color:#fff;padding:2px 5px;text-decoration:line-through;">' + eval(str) + '</span>';
                                    default:
                                        return value;
                                }
                            } catch (e) {
                                return value;
                            }
                        }
                    },
                    {field: 'cc_initial_money', title: '初始额度(元)', width: 90, halign: 'center',align: 'right', sortable: true},
                    {field: 'cc_current_money', title: '当前余额(元)', width: 90, halign: 'center',align: 'right', sortable: true},
                    {field: 'cc_holder_id', title: '会员ID', width: 60, halign: 'center', sortable: true,hidden:true},
                    {field: 'cc_holder_code', title: '会员编号', width: 140, align: 'center', sortable: true},
                    {field: 'cc_holder_mobile', title: '会员手机', width: 90, align: 'center', sortable: true},
                    {field: 'cc_holder_name', title: '会员名称', width: 100, halign: 'center', sortable: true},
                    {field: 'cc_start_date', title: '制卡日期', width: 90, align: 'center', sortable: true},
                    {field: 'cc_end_date', title: '有效日期', width: 90, align: 'center', sortable: true},
                    {field: 'cc_mark', title: '备注', width: 200, halign: 'center', sortable: true},
                    {field: 'cc_create_time', title: '创建时间', align: 'center', width: 150, sortable: true},
                    {field: 'cc_creator_id', title: '创建人ID', align: 'center', width: 80, sortable: true,hidden:true},
                    {field: 'cc_creator', title: '创建人', align: 'center', width: 80, sortable: true}
                ]],
                onDblClickRow: function (rowIndex, rowData) {
                    chargeChargeCardIndex.edit(rowData.cc_id);
                }
            });
            //--初始化【新增/修改】窗口
            $('#chargeChargeCardIndex_addEditWin').dialog({
                title: '新增/修改充电卡',
                width: 400,
                height: 450,
                closed: true,
                cache: true,
                modal: true,
                maximizable: false,
                resizable: false,
                onClose: function () {
                    $(this).dialog('clear');
                },
                buttons: [{
                    text: '确定',
                    iconCls: 'icon-ok',
                    handler: function () {
                        var form = $('#chargeChargeCardIndex_addEditWin_form');
                        var cc_id = $('input[name="cc_id"]',form)[0].value; // 按电卡id判断是新增还是修改。
                        var _url = chargeChargeCardIndex_URL_add;
                        if(parseInt(cc_id) > 0) _url = chargeChargeCardIndex_URL_edit;
                        form.form('submit', {
                            url: _url,
                            onSubmit: function(){
                                if(!$(this).form('validate')){
                                    $.messager.show({
                                        title: '表单验证不通过',
                                        msg: '请检查表单是否填写完整或填写错误！'
                                    });
                                    return false;
                                }
                            },
                            success: function(data){
                                // change JSON string to js object
                                var data = eval('(' + data + ')');
                                if(data.status){
                                    $.messager.show({
                                        title: '保存成功',
                                        msg: data.info
                                    });
                                    $('#chargeChargeCardIndex_addEditWin').dialog('close');
                                    $('#chargeChargeCardIndex_datagrid').datagrid('reload');
                                }else{
                                    $.messager.alert('错误',data.info,'error');
                                }
                            }
                        });
                    }
                }, {
                    text: '取消',
                    iconCls: 'icon-cancel',
                    handler: function () {
                        $('#chargeChargeCardIndex_addEditWin').dialog('close');
                    }
                }]
            });
            //--初始化【充值】窗口
            $('#chargeChargeCardIndex_rechargeWin').dialog({
                title: '电卡充值',
                width: 400,
                height: 400,
                closed: true,
                cache: true,
                modal: true,
                maximizable: false,
                resizable: false,
                onClose: function () {
                    $(this).dialog('clear');
                },
                buttons: [{
                    text: '确定',
                    iconCls: 'icon-ok',
                    handler: function () {
                        $('#chargeChargeCardIndex_rechargeWin_form').form('submit', {
                            url: chargeChargeCardIndex_URL_recharge,
                            onSubmit: function(){
                                if(!$(this).form('validate')){
                                    $.messager.show({
                                        title: '表单验证不通过',
                                        msg: '请检查表单是否填写完整或填写错误！'
                                    });
                                    return false;
                                }
                            },
                            success: function(data){
                                // change JSON string to js object
                                var data = eval('(' + data + ')');
                                if(data.status){
                                    $.messager.show({
                                        title: '保存成功',
                                        msg: data.info
                                    });
                                    $('#chargeChargeCardIndex_rechargeWin').dialog('close');
                                    $('#chargeChargeCardIndex_datagrid').datagrid('reload');
                                }else{
                                    $.messager.alert('错误',data.info,'error');
                                }
                            }
                        });
                    }
                }, {
                    text: '取消',
                    iconCls: 'icon-cancel',
                    handler: function () {
                        $('#chargeChargeCardIndex_rechargeWin').dialog('close');
                    }
                }]
            });
            //--初始化【查看电卡详情】窗口
            $('#chargeChargeCardIndex_scanCardDetailsWin').window({
                title: '查看电卡详情',
                width: 1000,
                height: 500,
                closed: true,
                cache: true,
                modal: true,
                maximizable: false,
                collapsible: false,
                minimizable: false,
                onClose: function () {
                    $(this).window('clear');
                }
            });
        },
        // 获取当前所选择的记录
        getCurrentSelected: function(multiline){
            var datagrid = $('#chargeChargeCardIndex_datagrid');
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
        // 添加
        add: function(){
            $('#chargeChargeCardIndex_addEditWin')
                .dialog('open')
                .dialog('refresh',chargeChargeCardIndex_URL_add)
                .dialog('setTitle','新增充电卡');
        },
        // 修改
        edit: function(id){
            var cc_id = id || (this.getCurrentSelected()).cc_id;
            if(!cc_id) return false;
            $('#chargeChargeCardIndex_addEditWin')
                .dialog('open')
                .dialog('refresh', chargeChargeCardIndex_URL_edit + '&cc_id=' + cc_id)
                .dialog('setTitle', '修改充电卡');
        },
        // 删除
        remove: function(){
            var selectRow = this.getCurrentSelected();
            if(!selectRow) return false;
            var cc_status = selectRow.cc_status;
            if(cc_status != 'STOPPED'){
                $.messager.show({
                    title: '不能删除',
                    msg: '只有处于【已停用】状态的电卡才允许删除！'
                });
                return false;
            }
            var cc_id = selectRow.cc_id;
            $.messager.confirm('确定删除','您确定要删除所选行吗？',function(r){
                if(r){
                    $.ajax({
                        type: 'get',
                        url: chargeChargeCardIndex_URL_remove,
                        data: {cc_id: cc_id},
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.show({
                                    title: '删除成功',
                                    msg: data.info
                                });
                                $('#chargeChargeCardIndex_datagrid').datagrid('reload');
                            }else{
                                $.messager.alert('删除失败',data.info,'error');
                            }
                        }
                    });
                }
            });
        },
        // 充值
        recharge: function(){
            var selectRow = this.getCurrentSelected();
            if(!selectRow) return false;
            var cc_status = selectRow.cc_status;
            if(cc_status != 'ACTIVATED'){
                $.messager.show({
                    title: '不能充值',
                    msg: '只有处于【已激活】状态的电卡才允许充值！'
                });
                return false;
            }
            var cc_id = selectRow.cc_id;
            $('#chargeChargeCardIndex_rechargeWin')
                .dialog('open')
                .dialog('refresh',chargeChargeCardIndex_URL_recharge + '&cc_id=' + cc_id);
        },
        // 查看电卡详情
        scanCardDetails: function(){
            var cc_id = (this.getCurrentSelected()).cc_id;
            if(!cc_id) return false;
            $('#chargeChargeCardIndex_scanCardDetailsWin')
                .dialog('open')
                .dialog('refresh',chargeChargeCardIndex_URL_scanCardDetails + '&cc_id=' + cc_id);
        },
        // 在地图上显示
        showOnMap: function(){
            var grid = $('#chargeChargeCardIndex_datagrid');
            if(grid.datagrid('getData').total < 1){
                $.messager.alert('警告','还没有任何数据！','warning');
                return false;
            }
            var _title = '地图标注-充电卡';
            var form = $('#chargeChargeCardIndex_searchFrom');
            //在新tab里显示
            if($('#easyui-tabs-index-index-main').tabs('exists',_title)){
                $('#easyui-tabs-index-index-main').tabs('select',_title);
                return;
            }
            $('#easyui-tabs-index-index-main').tabs('add',{
                title: _title,
                content: '',
                href: '?r=charge/charge-card/show-on-map&'+form.serialize(),
                closable: true,
                fit: true
            });
        },
        // 导出Excel
        exportGridData: function(){
            var searchConditionStr = $('#chargeChargeCardIndex_searchFrom').serialize();
            window.open(chargeChargeCardIndex_URL_exportGridData + "&" + searchConditionStr);
        },
        // 查询
        search: function(){
            var form = $('#chargeChargeCardIndex_searchFrom');
            var data = {};
            var searchCondition = form.serializeArray();
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = searchCondition[i]['value'];
            }
            $('#chargeChargeCardIndex_datagrid').datagrid('load',data);
        },
        // 重置
        reset: function(){
            $('#chargeChargeCardIndex_searchFrom').form('reset');
        }
    }

    // 执行初始化函数
	chargeChargeCardIndex.init();

</script>