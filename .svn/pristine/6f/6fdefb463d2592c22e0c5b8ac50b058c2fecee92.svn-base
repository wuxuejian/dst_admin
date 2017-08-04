<table id="cardChargeCardIndex_datagrid"></table> 
<div id="cardChargeCardIndex_datagridToolbar">
    <div class="easyui-panel" title="检索区域" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="cardChargeCardIndex_searchFrom">
                <ul class="search-main">
                    <li>
                        <div class="item-name">电卡编号</div>
                        <div class="item-input">
                            <input
                                class="easyui-textbox"
                                name="cc_code"
                                style="width:100%;"
                                data-options="{
                                    onChange: function(){
                                        cardChargeCardIndex.search();
                                    }
                                }"
                            />
                        </div>
                    </li>                                     
					<li>
                        <div class="item-name">电卡类型</div>
                        <div class="item-input">
                            <select
                                class="easyui-combobox"
                                name="cc_type"
                                style="width:100%;"
                                data-options="{
                                    panelHeight:'auto',editable:false,
                                    onChange: function(){
                                        cardChargeCardIndex.search();
                                    }
                            }">
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
                            <select
                                class="easyui-combobox"
                                name="cc_status"
                                style="width:100%;"
                                data-options="{
                                    panelHeight:'auto',
                                    editable:false,
                                    onChange: function(){
                                        cardChargeCardIndex.search();
                                    }
                            }">
                                <option value="" selected="selected">--不限--</option>
                                <?php foreach($config['cc_status'] as $val){ ?>
                                <option value="<?= $val['value'] ?>"><?= $val['text'] ?></option>
                                <?php } ?>
                            </select>
						</div>
                    </li>
                    <li>
                        <div class="item-name">会员名称</div>
                        <div class="item-input">
                            <input
                                class="easyui-textbox"
                                name="cc_holder_client"
                                style="width:100%;"
                                data-options="{
                                    onChange: function(){
                                        cardChargeCardIndex.search();
                                    }
                                }"
                            />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">会员手机</div>
                        <div class="item-input">
                            <input
                                class="easyui-textbox"
                                name="cc_holder_mobile"
                                style="width:100%;"
                                data-options="{
                                    onChange: function(){
                                        cardChargeCardIndex.search();
                                    }
                                }"
                            />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">制卡日期</div>
                        <div class="item-input-datebox">
                            <input
                                class="easyui-datebox"
                                name="cc_start_date_start"
                                style="width:91px;"
                                data-options="{
                                    onChange: function(){
                                        cardChargeCardIndex.search();
                                    }
                                }"
                            /> -
                            <input
                                class="easyui-datebox"
                                name="cc_start_date_end"
                                style="width:90px;"
                                data-options="{
                                    onChange: function(){
                                        cardChargeCardIndex.search();
                                    }
                                }"
                            />
                        </div>
                    </li>
                    <li class="search-button">
                        <button class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button onclick="cardChargeCardIndex.reset();return false;" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
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
                <button onclick="<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon'] ;?>'"><?= $val['text'] ;?></button>
            <?php } ?>
        </div>
    <?php } ?>

</div>

<!-- 窗口 -->
<div id="cardChargeCardIndex_addWin"></div>
<div id="cardChargeCardIndex_editWin"></div>
<div id="cardChargeCardIndex_rechargeWin"></div>
<div id="cardChargeCardIndex_scanCardDetailsWin"></div>
<div id="cardChargeCardIndex_recharge"></div>
<div id="cardChargeCardIndex_swap"></div>
<div id="cardChargeCardIndex_read"></div>
<!-- 窗口 -->

<script>
	// 初始数据
	var cardChargeCardIndex_CONFIG = <?php echo json_encode($config); ?>;
    // 请求的URL
    var cardChargeCardIndex_URL_getList = "<?php echo yii::$app->urlManager->createUrl(['card/charge-card/get-list']); ?>";
    var cardChargeCardIndex_URL_add = "<?php echo yii::$app->urlManager->createUrl(['card/charge-card/add']); ?>";
    var cardChargeCardIndex_URL_edit = "<?php echo yii::$app->urlManager->createUrl(['card/charge-card/edit']); ?>";
    var cardChargeCardIndex_URL_remove = "<?php echo yii::$app->urlManager->createUrl(['card/charge-card/remove']); ?>";
    var cardChargeCardIndex_URL_recharge = "<?php echo yii::$app->urlManager->createUrl(['card/recharge/add']); ?>";
    var cardChargeCardIndex_URL_scanCardDetails = "<?php echo yii::$app->urlManager->createUrl(['card/charge-card/scan-card-details']); ?>";
    var cardChargeCardIndex_URL_exportGridData = "<?php echo yii::$app->urlManager->createUrl(['card/charge-card/export-grid-data']); ?>";

    var cardChargeCardIndex = {
        params: {
            url: {
                read: "<?= yii::$app->urlManager->createUrl(['card/charge-card/read']); ?>"
            }
        },
        // 初始化
        init: function () {
            var easyuiDatagrid = $('#cardChargeCardIndex_datagrid');
            //--初始化表格
            easyuiDatagrid.datagrid({
                method: 'get',
                url: cardChargeCardIndex_URL_getList,
                toolbar: "#cardChargeCardIndex_datagridToolbar",
                fit: true,
                border: false,
                pagination: true,
                loadMsg: '数据加载中...',
                striped: true,
                checkOnSelect: true,
                rownumbers: true,
                singleSelect: true,
                pageSize: 20,
                showFooter: true,
                frozenColumns: [[
                    {field: 'ck', checkbox: true},
                    {field: 'cc_id', title: '电卡ID', width: 40, align: 'center', hidden: true},
                    {field: 'cc_code', title: '电卡编号', width: 110, align: 'center', sortable: true}
                ]],
                columns: [[
                    {field: 'cc_type', title: '电卡类型', align: 'center', width: 80, sortable: true,
                        formatter: function (value, row, index) {
                            try {
                                var str = 'cardChargeCardIndex_CONFIG.cc_type.' + value + '.text';
                                return eval(str);
                            } catch (e) {
                                return value;
                            }
                        }
                    },
                    {field: 'cc_status', title: '电卡状态', align: 'center', width: 80, sortable: true,
                        formatter: function (value, row, index) {
                            try {
                                var str = 'cardChargeCardIndex_CONFIG.cc_status.' + value + '.text';
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
                    cardChargeCardIndex.edit(rowData.cc_id);
                }
            });
            var searchForm = $('#cardChargeCardIndex_searchFrom');
            searchForm.submit(function(){
                var data = {};
                var searchCondition = $(this).serializeArray();
                for(var i in searchCondition){
                    data[searchCondition[i]['name']] = searchCondition[i]['value'];
                }
                easyuiDatagrid.datagrid('load',data);
                return false;
            });
            //--初始化【新增】窗口
            $('#cardChargeCardIndex_addWin').dialog({
                title: '新增充电卡',
                width: 660,
                height: 360,
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
                        var form = $('#cardChargeCardIndex_add_form');
                        form.form('submit', {
                            url: "<?= yii::$app->urlManager->createUrl(['card/charge-card/add']); ?>",
                            onSubmit: function(){
                                if(!$(this).form('validate')){
                                    $.messager.show({
                                        title: '表单验证不通过',
                                        msg: '请检查表单是否填写完整或填写错误！'
                                    });
                                    return false;
                                }
                            },
                            success: function(rData){
                                // change JSON string to js object
                                var rData = eval('(' + rData + ')');
                                if(rData.status){
                                    var cardNo = rData.cardInfo.cc_code;
                                    var money = 0;
                                    var ccrr_id = 0;
                                    if(rData.ccrrInfo.ccrr_recharge_money){
                                        money = rData.ccrrInfo.ccrr_recharge_money;
                                    }
                                    if(rData.ccrrInfo.ccrr_id){
                                        ccrr_id = rData.ccrrInfo.ccrr_id;
                                    }
                                    cardChargeCardIndex_add.writeCard(cardNo,money,ccrr_id);
                                }else{
                                    $.messager.alert('错误',rData.info,'error');
                                }
                            }
                        });
                    }
                }, {
                    text: '取消',
                    iconCls: 'icon-cancel',
                    handler: function () {
                        $('#cardChargeCardIndex_addWin').dialog('close');
                    }
                }]
            });
            //--初始化【修改】窗口
            $('#cardChargeCardIndex_editWin').dialog({
                title: '修改充电卡',
                width: 680,
                height: 380,
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
                        var form = $('#cardChargeCardIndex_editWin_form');
                        form.form('submit', {
                            url: "<?= yii::$app->urlManager->createUrl(['card/charge-card/edit']); ?>",
                            onSubmit: function(){
                                if(!$(this).form('validate')){
                                    $.messager.show({
                                        title: '表单验证不通过',
                                        msg: '请检查表单是否填写完整或填写错误！'
                                    });
                                    return false;
                                }
                            },
                            success: function(rData){
                                // change JSON string to js object
                                var rData = eval('(' + rData + ')');
                                if(rData.status){
                                    $.messager.alert('操作成功',rData.info,'info');
                                    $('#cardChargeCardIndex_editWin').dialog('close');
                                    $('#cardChargeCardIndex_datagrid').datagrid('reload');
                                }else{
                                    $.messager.alert('操作失败',rData.info,'error');
                                }
                            }
                        });
                    }
                }, {
                    text: '取消',
                    iconCls: 'icon-cancel',
                    handler: function () {
                        $('#cardChargeCardIndex_editWin').dialog('close');
                    }
                }]
            });
            //--初始化【充值】窗口
            $('#cardChargeCardIndex_rechargeWin').dialog({
                title: '电卡充值',
                width: 1000,
                height: 500,
                closed: true,
                cache: true,
                modal: true,
                maximizable: true,
                resizable: false,
                onClose: function () {
                    $(this).dialog('clear');
                },
                buttons: [{
                    text: '确定',
                    iconCls: 'icon-ok',
                    handler: function () {
                        var easyuiForm = $('#easyui_form_card_recharge_add');
                        if(!easyuiForm.find('input[name=cc_code]').val()){
                            $.messager.alert('错误','未知的卡号，请先读卡！','error');
                            return;
                        }
                        if(!easyuiForm.form('validate')){
                            return;
                        }
                        //请求提交数据
                        $.ajax({
                            type: "post",
                            url: "<?= yii::$app->urlManager->createUrl(['card/recharge/add']); ?>",
                            data: easyuiForm.serialize(),
                            dataType: "json",
                            success: function(rData){
                                if(rData.status){
                                    //数据保存成功写卡
                                    CardRechargeAdd.writeCard(rData.rechargeId,rData.rechargeMoney,rData.rechargeTimes);
                                }else{
                                    $.messager.alert('错误',rData.info,'error');
                                }
                            }
                        });
                    }
                }, {
                    text: '取消',
                    iconCls: 'icon-cancel',
                    handler: function () {
                        $('#cardChargeCardIndex_rechargeWin').dialog('close');
                    }
                }]
            });
            //--初始化【金额调剂】窗口
            $('#cardChargeCardIndex_swap').dialog({
                title: '电卡金额调剂',
                width: '80%',
                height: '80%',
                closed: true,
                cache: true,
                modal: true,
                maximizable: true,
                resizable: false,
                onClose: function () {
                    $(this).dialog('clear');
                },
                buttons: [{
                    text: '确定',
                    iconCls: 'icon-ok',
                    handler: function () {
                        var easyuiForm = $('#easyui_form_card_swap_do');
                        if(!easyuiForm.find('input[name=cc_code]').val()){
                            $.messager.alert('错误','未知的卡号，请先读卡！','error');
                            return;
                        }
                        if(!easyuiForm.form('validate')){
                            return;
                        }
                        var type = easyuiForm.find('select[comboname=type]').combobox('getValue');
                        var money = easyuiForm.find('input[textboxname=money]').textbox('getValue');
                        if(type == 'reduce'){
                            var str = '本次操作将对该卡余额减少<b>'+money+'元</b>，确定执行？';
                        }else{
                            var str = '本次操作将对该卡余额增加<b>'+money+'元</b>，确定执行？';
                        }
                        $.messager.confirm('调剂确认',str,function(r){
                            if(r){
                                //请求提交数据
                                $.ajax({
                                    type: "post",
                                    url: "<?= yii::$app->urlManager->createUrl(['card/swap/do']); ?>",
                                    data: easyuiForm.serialize(),
                                    dataType: "json",
                                    success: function(rData){
                                        if(rData.status){
                                            //写卡
                                            CardSwapDo.writeCard(rData.data);
                                        }else{
                                            $.messager.alert('操作失败',rData.info,'error');
                                        }
                                    }
                                });
                            }
                        });
                    }
                }, {
                    text: '取消',
                    iconCls: 'icon-cancel',
                    handler: function () {
                        $('#cardChargeCardIndex_swap').dialog('close');
                    }
                }]
            });
            //--初始化【查看电卡详情】窗口
            $('#cardChargeCardIndex_scanCardDetailsWin').window({
                title: '查看电卡详情',
                width: 1000,
                height: 500,
                closed: true,
                cache: true,
                modal: true,
                maximizable: true,
                collapsible: false,
                minimizable: false,
                onClose: function () {
                    $(this).window('clear');
                }
            });
            //--初始化【读卡】窗口
            $('#cardChargeCardIndex_read').dialog({
                title: '读卡',
                width: 660,
                height: 240,
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
                        CardChargeCardRead.readCard();
                    }
                }, {
                    text: '取消',
                    iconCls: 'icon-cancel',
                    handler: function () {
                        $('#cardChargeCardIndex_read').dialog('close');
                    }
                }]
            });
        },
        // 获取当前所选择的记录
        getCurrentSelected: function(multiline){
            var datagrid = $('#cardChargeCardIndex_datagrid');
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
            $('#cardChargeCardIndex_addWin')
                .dialog('open')
                .dialog('refresh',"<?= yii::$app->urlManager->createUrl(['card/charge-card/add']); ?>");
        },
        // 修改
        edit: function(id){
            var cc_id = id || (this.getCurrentSelected()).cc_id;
            if(!cc_id) return false;
            $('#cardChargeCardIndex_editWin')
                .dialog('open')
                .dialog('refresh', cardChargeCardIndex_URL_edit + '&cc_id=' + cc_id)
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
                        url: cardChargeCardIndex_URL_remove,
                        data: {cc_id: cc_id},
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.show({
                                    title: '删除成功',
                                    msg: data.info
                                });
                                $('#cardChargeCardIndex_datagrid').datagrid('reload');
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
            $('#cardChargeCardIndex_rechargeWin')
                .dialog('open')
                .dialog('refresh',cardChargeCardIndex_URL_recharge);
        },
        // 查看电卡详情
        scanCardDetails: function(){
            var cc_id = (this.getCurrentSelected()).cc_id;
            if(!cc_id) return false;
            $('#cardChargeCardIndex_scanCardDetailsWin')
                .dialog('open')
                .dialog('refresh',cardChargeCardIndex_URL_scanCardDetails + '&cc_id=' + cc_id);
        },
        // 在地图上显示
        showOnMap: function(){
            var grid = $('#cardChargeCardIndex_datagrid');
            if(grid.datagrid('getData').total < 1){
                $.messager.alert('警告','还没有任何数据！','warning');
                return false;
            }
            var _title = '地图标注-充电卡';
            var form = $('#cardChargeCardIndex_searchFrom');
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
            var searchConditionStr = $('#cardChargeCardIndex_searchFrom').serialize();
            window.open(cardChargeCardIndex_URL_exportGridData + "&" + searchConditionStr);
        },
        // 查询
        search: function(){
            $('#cardChargeCardIndex_searchFrom').submit();
        },
        // 重置
        reset: function(){
            var searchForm = $('#cardChargeCardIndex_searchFrom');
            searchForm.form('reset');
            searchForm.submit();
        },
        //金额调剂
        swap: function(){
            $('#cardChargeCardIndex_swap')
                .dialog('open')
                .dialog('refresh',"<?= yii::$app->urlManager->createUrl(['card/swap/do']); ?>")
        },
        //读卡
        read: function(){
            $('#cardChargeCardIndex_read')
                .dialog('open')
                .dialog('refresh',this.params.url.read);
        }
    }

    // 执行初始化函数
	cardChargeCardIndex.init();

</script>