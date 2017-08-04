<table id="chargeChargeStationIndex_datagrid"></table> 
<div id="chargeChargeStationIndex_datagridToolbar">
    <div class="easyui-panel" title="检索区域" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="chargeChargeStationIndex_searchFrom">
                <ul class="search-main">
                    <li>
                        <div class="item-name">电站编号</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="cs_code" style="width:100%;"
                                   data-options="
                                        onChange:function(){
                                            chargeChargeStationIndex.search();
                                        }
                                   "
                            />
                        </div>
                    </li>                                     
                    <li>
                        <div class="item-name">电站名称</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="cs_name" style="width:100%;"
                                   data-options="
                                        onChange:function(){
                                            chargeChargeStationIndex.search();
                                        }
                                   "
                            />
                        </div>
                    </li>
					<li>
                        <div class="item-name">电站类型</div>
                        <div class="item-input">
                            <select class="easyui-combobox"  name="cs_type" style="width:100%;"
                                data-options="
                                    panelHeight:'auto',
                                    editable:false,
                                    onChange:function(){
                                        chargeChargeStationIndex.search();
                                    }
                                "
                            >
                                <option value="" selected="selected">--不限--</option>
                                <?php foreach($config['cs_type'] as $val){ ?>
                                <option value="<?= $val['value'] ?>"><?= $val['text'] ?></option>
                                <?php } ?>
                            </select>
						</div>
                    </li>
					<li>
                        <div class="item-name">电站状态</div>
                        <div class="item-input">
                            <select class="easyui-combobox"  name="cs_status" style="width:100%;"
                                data-options="
                                    panelHeight:'auto',
                                    editable:false,
                                    onChange:function(){
                                        chargeChargeStationIndex.search();
                                    }
                                "
                            >
                                <option value="" selected="selected">--不限--</option>
                                <?php foreach($config['cs_status'] as $val){ ?>
                                <option value="<?= $val['value'] ?>"><?= $val['text'] ?></option>
                                <?php } ?>
                            </select>
						</div>
                    </li>
                    <li>
                        <div class="item-name">电站位置</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="cs_address" style="width:100%;"
                               data-options="
                                    onChange:function(){
                                        chargeChargeStationIndex.search();
                                    }
                               "
                            />
                        </div>
                    </li>
                    <li class="search-button">
                        <a href="javascript:void(0)" onclick="chargeChargeStationIndex.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                        <a href="javascript:void(0)" onclick="chargeChargeStationIndex.reset()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</a>
                    </li>
                </ul>
            </form>
        </div>
    </div>

    <?php if(!empty($buttons)){ ?>
        <div class="easyui-panel" title="电站列表" style="padding:3px 2px;width:100%;" data-options="
        iconCls: 'icon-table-list',
        border: false
    ">
            <?php foreach($buttons as $val){ ?>
                <a href="javascript:void(0)" onclick="<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon'] ;?>'"><?= $val['text'] ;?></a>
            <?php } ?>
        </div>
    <?php } ?>

</div>

<!-- 窗口 -->
<div id="chargeChargeStationIndex_addEditWin"></div>
<div id="chargeChargeStationIndex_addPoleWin"></div>
<div id="chargeChargeStationIndex_scanStationDetailsWin"></div>
<!-- 窗口 -->

<script>
    var chargeChargeStationIndex = {
        //配置项、请求的URL等
        Params:{
            CONFIG: <?php echo json_encode($config); ?>,
            URL:{
                getList: "<?php echo yii::$app->urlManager->createUrl(['charge/charge-station/get-list']); ?>",
                add: "<?php echo yii::$app->urlManager->createUrl(['charge/charge-station/add']); ?>",
                edit: "<?php echo yii::$app->urlManager->createUrl(['charge/charge-station/edit']); ?>",
                remove: "<?php echo yii::$app->urlManager->createUrl(['charge/charge-station/remove']); ?>",
                addPole: "<?php echo yii::$app->urlManager->createUrl(['charge/charge-station/add-pole']); ?>",
                scanStationDetails: "<?php echo yii::$app->urlManager->createUrl(['charge/charge-station/scan-station-details']); ?>",
                exportGridData: "<?php echo yii::$app->urlManager->createUrl(['charge/charge-station/export-grid-data']); ?>",
                showOnMap: "<?php echo yii::$app->urlManager->createUrl(['charge/charge-station/show-on-map']); ?>"
            }
        },
        // 初始化
        init: function () {
            //--初始化表格
            $('#chargeChargeStationIndex_datagrid').datagrid({
                method: 'get',
                url: chargeChargeStationIndex.Params.URL.getList,
                toolbar: "#chargeChargeStationIndex_datagridToolbar",
                fit: true,
                border: false,
                pagination: true,
                loadMsg: '数据加载中...',
                striped: true,
                checkOnSelect: true,
                rownumbers: true,
                singleSelect: false,
                pageSize: 20,
                frozenColumns: [[
                    {field: 'ck', checkbox: true},
                    {field: 'cs_id', title: '电站ID', width: 40, align: 'center', hidden: true},
                    {field: 'cs_code', title: '电站编号', width: 90, align: 'center', sortable: true},
                    {field: 'cs_name', title: '电站名称', width: 180, halign: 'center', sortable: true}
                ]],
                columns: [[
                    {field: 'cs_type', title: '电站类型', align: 'center', width: 70, sortable: true,
                        formatter: function (value, row, index) {
                            try {
                                var str = 'chargeChargeStationIndex.Params.CONFIG.cs_type.' + value + '.text';
                                return eval(str);
                            } catch (e) {
                                return value;
                            }
                        }
                    },
                    {field: 'cs_status', title: '电站状态', align: 'center', width: 70, sortable: true,
                        formatter: function (value, row, index) {
                            try {
                                var str = 'chargeChargeStationIndex.Params.CONFIG.cs_status.' + value + '.text';
                                switch (value) {
                                    case 'NORMAL':
                                        return '<span style="background-color:#05CD69;color:#fff;padding:2px 5px;">' + eval(str) + '</span>';
                                    case 'ABULIDING':
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
                    {field: 'cs_address', title: '电站位置', width: 230, halign: 'center', sortable: true},
                    {field: 'charger_num', title: '电桩数量', width: 70, align: 'center', sortable: true},
                    {field: 'cs_is_open', title: '是否开放', width: 70, align: 'center', sortable: true,
                        formatter: function (value, row, index) {
                            try {
                                return parseInt(value)==1 ? '是' : '<span style="color:red;font-weight:bold;">否</span>';
                            } catch (e) {
                                return value;
                            }
                        }
                    },
                    //{field: 'cs_building_user', title: '运营商/客户', width: 180, halign: 'center', sortable: true},
                    {field: 'cs_commissioning_date', title: '投运日期', width: 80, align: 'center', sortable: true},
                    {field: 'cs_pic_path', title: '照片', width: 50, align: 'center', sortable: true,
                        formatter: function(value,row,index){
                            var str =  '<span class="easyui-tooltip" tipRowIndex=' + index + ' style="color:blue;cursor:pointer;padding-left:5px;line-height:18px;" >' +
                                        '<img src="jquery-easyui-1.4.3/themes/icons/large_picture.png"  width="12" height="12" />' +
                                    '</span>';
                            return str;
                        }
                    },
                    {field: 'cs_mark', title: '备注', width: 200, halign: 'center', sortable: true},
                    {field: 'cs_fm_id', title: '所属前置机ID', width: 100, align: 'center', sortable: true,hidden:true},
                    {field: 'cs_fm', title: '所属前置机', width: 100, align: 'center', sortable: true},
                    {field: 'cs_create_time', title: '创建时间', align: 'center', width: 130, sortable: true},
                    {field: 'cs_creator_id', title: '创建人ID', align: 'center', width: 80, sortable: true,hidden:true},
                    {field: 'cs_creator', title: '创建人', align: 'center', width: 80, sortable: true}
                ]],
                onDblClickRow: function (rowIndex, rowData) {
                    chargeChargeStationIndex.edit(rowData.cs_id);
                },
                onLoadSuccess:function(){ // 表格数据加载成功后，设置悬浮提示框！
                    var rows = $(this).datagrid('getRows');
                    $(this).datagrid('getPanel').find('.easyui-tooltip').each(function(){
                        var index = parseInt($(this).attr('tipRowIndex'));
                        var row = rows[index];
                        var cs_name = row.cs_name;
                        var cs_pic_path = row.cs_pic_path;
                        $(this).tooltip({
                            position: 'right',
                            content : $('<div style="padding:3px 1px;font-size:90%;"></div>'),
                            onUpdate: function(cc){
                                var contStr = '<div style="padding:3px;">';
                                if(cs_pic_path){
                                    var pics = cs_pic_path.split(';');
                                    for(var i=0;i<pics.length;i++){
                                        if(pics[i]){
                                            contStr += '<img src="' + pics[i] + '" width="100" height="100" style="margin:3px;" />';
                                        }
                                    }
                                }else{
                                    contStr += '<div style="text-align:center;height:50px;line-height:50px;">还没有上传照片！</div>';
                                }
                                contStr += '</div>';
                                cc.panel({
                                    title: '<div style="text-align:center">' + cs_name + '</div>',
                                    width: 230,
                                    minHeight: 50,
                                    content: contStr
                                });
                            }
                        });
                    });
                }
            });
            //--初始化【新增/修改】窗口
            $('#chargeChargeStationIndex_addEditWin').dialog({
                title: '新增/修改充电站',
                width: 1100,
                height: 600,
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
                        var form = $('#chargeChargeStationIndex_addEditWin_form');
                        var cs_id = $('input[name="cs_id"]',form)[0].value; // 按电站id判断是新增还是修改。
                        var _url = chargeChargeStationIndex.Params.URL.add;
                        if(parseInt(cs_id) > 0) _url = chargeChargeStationIndex.Params.URL.edit;
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
                                    $('#chargeChargeStationIndex_addEditWin').dialog('close');
                                    $('#chargeChargeStationIndex_datagrid').datagrid('reload');
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
                        $('#chargeChargeStationIndex_addEditWin').dialog('close');
                    }
                }]
            });
            //--初始化【添加充电桩】窗口
            $('#chargeChargeStationIndex_addPoleWin').dialog({
                title: '添加充电桩',
                width: 900,
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
                        $('#chargeChargeStationIndex_addPoleWin_form').form('submit', {
                            url: chargeChargeStationIndex.Params.URL.addPole,
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
                                    $('#chargeChargeStationIndex_addPoleWin').dialog('close');
                                    $('#chargeChargeStationIndex_datagrid').datagrid('reload');
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
                        $('#chargeChargeStationIndex_addPoleWin').dialog('close');
                    }
                }]
            });
            //--初始化【查看电站详情】窗口
            $('#chargeChargeStationIndex_scanStationDetailsWin').window({
                title: '查看充电站详情',
                width: 1000,
                height: 550,
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
            var datagrid = $('#chargeChargeStationIndex_datagrid');
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
            $('#chargeChargeStationIndex_addEditWin')
                .dialog('open')
                .dialog('refresh',chargeChargeStationIndex.Params.URL.add)
                .dialog('setTitle','新增充电站');
        },
        // 修改
        edit: function(id){
            var cs_id = id || (this.getCurrentSelected()).cs_id;
            if(!cs_id) return false;
            $('#chargeChargeStationIndex_addEditWin')
                .dialog('open')
                .dialog('refresh', chargeChargeStationIndex.Params.URL.edit + '&cs_id=' + cs_id)
                .dialog('setTitle', '修改充电站');
        },
        // 删除
        remove: function(){
            var selectRow = this.getCurrentSelected();
            if(!selectRow) return false;
            var cs_id = selectRow.cs_id;
            $.messager.confirm('确定删除','您确定要删除所选行吗？',function(r){
                if(r){
                    $.ajax({
                        type: 'get',
                        url: chargeChargeStationIndex.Params.URL.remove,
                        data: {cs_id: cs_id},
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.show({
                                    title: '删除成功',
                                    msg: data.info
                                });
                                $('#chargeChargeStationIndex_datagrid').datagrid('reload');
                            }else{
                                $.messager.alert('删除失败',data.info,'error');
                            }
                        }
                    });
                }
            });
        },
        // 添加充电桩
        addPole: function(){
            var selectRow = this.getCurrentSelected();
            if(!selectRow) return false;
            var cs_id = selectRow.cs_id;
            $('#chargeChargeStationIndex_addPoleWin')
                .dialog('open')
                .dialog('refresh',chargeChargeStationIndex.Params.URL.addPole + '&cs_id=' + cs_id);
        },
        // 查看电站详情
        scanStationDetails: function(){
            var cs_id = (this.getCurrentSelected()).cs_id;
            if(!cs_id) return false;
            $('#chargeChargeStationIndex_scanStationDetailsWin')
                .dialog('open')
                .dialog('refresh',chargeChargeStationIndex.Params.URL.scanStationDetails + '&cs_id=' + cs_id);
        },
        // 在地图上显示
        showOnMap: function(){
            var grid = $('#chargeChargeStationIndex_datagrid');
            if(grid.datagrid('getData').total < 1){
                $.messager.alert('警告','还没有任何数据！','warning');
                return false;
            }
            var _title = '地图标注-充电站';
            //在新tab里显示
            if($('#easyui_tabs_index_index_main').tabs('exists',_title)){
                $('#easyui_tabs_index_index_main').tabs('select',_title);
                return;
            }
            var form = $('#chargeChargeStationIndex_searchFrom');
            var _href = chargeChargeStationIndex.Params.URL.showOnMap +'&'+form.serialize();
            $('#easyui_tabs_index_index_main').tabs('add',{
                title: _title,
                content: '<iframe scrolling="no" frameborder="0" src="' + _href + '" style="width:100%;height:100%;"></iframe>',
                closable: true,
                fit: true
            });
        },
        // 导出Excel
        exportGridData: function(){
            var searchConditionStr = $('#chargeChargeStationIndex_searchFrom').serialize();
            window.open(chargeChargeStationIndex.Params.URL.exportGridData + "&" + searchConditionStr);
        },
        // 查询
        search: function(){
            var form = $('#chargeChargeStationIndex_searchFrom');
            var data = {};
            var searchCondition = form.serializeArray();
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = $.trim(searchCondition[i]['value']);
            }
            $('#chargeChargeStationIndex_datagrid').datagrid('load',data);
        },
        // 重置
        reset: function(){
            $('#chargeChargeStationIndex_searchFrom').form('reset');
            chargeChargeStationIndex.search();
        }
    }

    // 执行初始化函数
	chargeChargeStationIndex.init();

</script>