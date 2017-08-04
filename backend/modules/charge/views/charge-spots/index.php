<table id="ChargeSpotsIndex_datagrid"></table> 
<div id="ChargeSpotsIndex_datagridToolbar">
    <div class="easyui-panel" title="检索区域" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="ChargeSpotsIndex_searchFrom">
                <ul class="search-main">
                    <li>
                        <div class="item-name">电桩编号</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="code_from_compony" style="width:100%;"
                               data-options="
                                    onChange:function(){
                                        ChargeSpotsIndex.search();
                                    }
                               "
                            />
                        </div>
                    </li>                                     
					<li>
                        <div class="item-name">充电模式</div>
                        <div class="item-input">
                            <select class="easyui-combobox"  name="charge_pattern" style="width:100%;"
                                data-options="
                                    panelHeight:'auto',
                                    editable:false,
                                    onChange:function(){
                                        ChargeSpotsIndex.search();
                                    }
                                "
                            >
                                <option value="" selected="selected">--不限--</option>
                                <?php foreach($config['charge_pattern'] as $val){ ?>
                                <option value="<?= $val['value'] ?>"><?= $val['text'] ?></option>
                                <?php } ?>
                            </select>
						</div>
                    </li>
					<li>
                        <div class="item-name">电桩类型</div>
                        <div class="item-input">
                            <select class="easyui-combobox"  name="charge_type" style="width:100%;"
                                data-options="
                                    panelHeight:'auto',
                                    editable:false,
                                    onChange:function(){
                                        ChargeSpotsIndex.search();
                                    }
                                "
                            >
                                <option value="" selected="selected">--不限--</option>
                                <?php foreach($config['charge_type'] as $val){ ?>
                                <option value="<?= $val['value'] ?>"><?= $val['text'] ?></option>
                                <?php } ?>
                            </select>
						</div>
                    </li>
                    <li>
                        <div class="item-name">所属电站</div>
                        <div class="item-input">
                            <input id="ChargeSpotsIndex_searchForm_chooseChargeStation" name="station_id"  style="width:100%;"  />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">安装地点</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="install_site" style="width:100%;"
                                   data-options="
                                    onChange:function(){
                                        ChargeSpotsIndex.search();
                                    }
                               "
                                />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">安装方式</div>
                        <div class="item-input">
                            <select class="easyui-combobox"  name="install_type" style="width:100%;"
                                    data-options="
                                    panelHeight:'auto',
                                    editable:false,
                                    onChange:function(){
                                        ChargeSpotsIndex.search();
                                    }
                                "
                                >
                                <option value="" selected="selected">--不限--</option>
                                <?php foreach($config['install_type'] as $val){ ?>
                                    <option value="<?= $val['value'] ?>"><?= $val['text'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">安装日期</div>
                        <div class="item-input-datebox">
                            <input class="easyui-datebox" type="text" name="install_date_start" style="width:93px;"
                                   data-options="
                                    onChange:function(){
                                        ChargeSpotsIndex.search();
                                    }
                               "
                                />
                            -
                            <input class="easyui-datebox" type="text" name="install_date_end" style="width:93px;"
                                   data-options="
                                    onChange:function(){
                                        ChargeSpotsIndex.search();
                                    }
                               "
                                />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">连接方式</div>
                        <div class="item-input">
                            <select class="easyui-combobox"  name="connection_type" style="width:100%;"
                                data-options="
                                    panelHeight:'auto',
                                    editable:false,
                                    onChange:function(){
                                        ChargeSpotsIndex.search();
                                    }
                                "
                            >
                                <option value="" selected="selected">--不限--</option>
                                <?php foreach($config['connection_type'] as $val){ ?>
                                    <option value="<?= $val['value'] ?>"><?= $val['text'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">生产厂家</div>
                        <div class="item-input">
                            <select class="easyui-combobox"  name="manufacturer" style="width:100%;"
                                    data-options="
                                    panelHeight:'auto',
                                    editable:false,
                                    onChange:function(){
                                        ChargeSpotsIndex.search();
                                    }
                                "
                                >
                                <option value="" selected="selected">--不限--</option>
                                <?php foreach($config['manufacturer'] as $val){ ?>
                                    <option value="<?= $val['value'] ?>"><?= $val['text'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">逻辑地址</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="logic_addr" style="width:100%;"
                               data-options="
                                    onChange:function(){
                                        ChargeSpotsIndex.search();
                                    }
                               "
                            />
                        </div>
                    </li>

                    <li class="search-button">
                        <a href="javascript:void(0)"  onclick="ChargeSpotsIndex.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                        <a href="javascript:void(0)"  onclick="ChargeSpotsIndex.reset()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</a>
                    </li>
                </ul>
            </form>
        </div>
    </div>

    <?php if(!empty($buttons)){ ?>
        <div class="easyui-panel" title="电桩列表" style="padding:3px 2px;" data-options="
        iconCls: 'icon-table-list',
        border: false
    ">
            <?php foreach($buttons as $val){ ?>
                <a href="javascript:void(0)"  onclick="<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon'] ;?>'"><?= $val['text'] ;?></a>
            <?php } ?>
        </div>
    <?php } ?>

</div>
<!-- 窗口 -->
<div id="ChargeSpotsIndex_addEditWin"></div>
<div id="ChargeSpotsIndex_scanDetailsWin"></div>
<div id="ChargeSpotsIndex_scanChargeRecordsWin"></div>
<div id="ChargeSpotsIndex_qrCodeWin"></div>
<div id="ChargeSpotsIndex_monitorChargeWin"></div>
<!-- 窗口 -->

<script>
    var ChargeSpotsIndex = {
        // 相关配置项
        'CONFIG': <?= json_encode($config); ?>,
        // 请求的URL
        'URL': {
            getList: "<?php echo yii::$app->urlManager->createUrl(['charge/charge-spots/get-list']); ?>",
            add: "<?php echo yii::$app->urlManager->createUrl(['charge/charge-spots/add']); ?>",
            edit: "<?php echo yii::$app->urlManager->createUrl(['charge/charge-spots/edit']); ?>",
            remove: "<?php echo yii::$app->urlManager->createUrl(['charge/charge-spots/remove']); ?>",
            scanDetails: "<?php echo yii::$app->urlManager->createUrl(['charge/charge-spots/scan-details']); ?>",
            scanChargeRecords: "<?php echo yii::$app->urlManager->createUrl(['charge/charge-spots/scan-charge-records']); ?>",
            qrCode: "<?php echo yii::$app->urlManager->createUrl(['charge/charge-spots/qr-code']); ?>",
            exportGridData: "<?php echo yii::$app->urlManager->createUrl(['charge/charge-spots/export-grid-data']); ?>",
            showOnMap: "<?php echo yii::$app->urlManager->createUrl(['charge/charge-spots/show-on-map']); ?>",
            monitorCharge: "<?php echo yii::$app->urlManager->createUrl(['charge/charge-spots/monitor-charge']); ?>"
        },
        // 初始化
        init: function () {
            // 初始化列表
            $('#ChargeSpotsIndex_datagrid').datagrid({
                method: 'get',
                url: ChargeSpotsIndex.URL.getList,
                fit: true,
                border: false,
                toolbar: "#ChargeSpotsIndex_datagridToolbar",
                pagination: true,
                loadMsg: '数据加载中...',
                striped: true,
                checkOnSelect: true,
                rownumbers: true,
                singleSelect: true,
                pageSize: 20,
                frozenColumns: [[
                    {field: 'ck', checkbox: true},
                    {field: 'id', title: '电桩ID', width: 40, align: 'center', hidden: true},
                    {field: 'code_from_compony', title: '电桩编号', width: 70, align: 'center', sortable: true},
                    {field: 'charge_pattern', title: '充电模式', width: 60, align: 'center', sortable: true,
                        formatter: function (value, row, index) {
                            try {
                                var str = 'ChargeSpotsIndex.CONFIG.charge_pattern.' + value + '.text';
                                if (value == 'FAST_CHARGE') {
                                    return '<span style="background-color:#05CD69;color:#fff;padding:2px;">' + eval(str) + '</span>';
                                } else if (value == 'SLOW_CHARGE') {
                                    return '<span style="background-color:#FFD040;color:#fff;padding:2px;">' + eval(str) + '</span>';
                                } else {
                                    return value;
                                }
                            } catch (e) {
                                return '';
                            }
                        }
                    }
                ]],
                columns: [[
                    {field: 'charge_type', title: '电桩类型', width: 100, align: 'center', sortable: true,
                        formatter: function (value, row, index) {
                            try {
                                var str = 'ChargeSpotsIndex.CONFIG.charge_type.' + value + '.text';
                                return eval(str);
                            } catch (e) {
                                return '';
                            }
                        }
                    },
                    {field: 'charge_gun_nums', title: '电枪个数', width: 65, align: 'center', sortable: true},
                    {field: 'status', title: '电桩状态', width: 130, align: 'center', sortable: true,
                        formatter: function (value, row, index) {
                            try {
                                // 千万注意：拥有单枪的电桩就只有一个状态；但是拥有双枪的电桩有两个状态（中间以英文逗号分隔）
                                var valArr = value.split(',');
                                var statusHtml = [];
                                var num = valArr.length;
                                for(var i=0; i<num; i++){
                                    var val = valArr[i];
                                    var str = 'ChargeSpotsIndex.CONFIG.status[' + val + '].text';
                                    var str2 = '';
                                    var gunName = num > 1 ? (i==0 ? 'A枪' : 'B枪') : '';
                                    switch (val) {
                                        case '1':
                                            str2 = '<span style="background-color:#05CD69;color:#fff;padding:2px 5px;">' + gunName + eval(str) + '</span>'; break;
                                        case '0':
                                            str2 = '<span style="background-color:#FFCC01;color:#fff;padding:2px 5px;">' + gunName + eval(str) + '</span>'; break;
                                        case '2':
                                            str2 = '<span style="background-color:#F31F28;color:#fff;padding:2px 5px;">' + gunName + eval(str) + '</span>'; break;
                                        case '3':
                                            str2 = '<span style="background-color:#C0C0E0;color:#fff;padding:2px 5px;">' + gunName + eval(str) + '</span>'; break;
                                        case '4':
                                            str2 = '<span style="background-color:#E7E7E7;color:#fff;padding:2px 5px;">' + gunName + eval(str) + '</span>'; break;
                                        default:
                                            str2 = value;
                                    }
                                    statusHtml.push(str2);
                                }
                                return statusHtml.join(' ');
                            } catch (e) {
                                return '';
                            }
                        }
                    },
                    {field: 'station_id', title: '所属充电站id', width: 60, align: 'center',hidden:true},
                    {field: 'station_name', title: '所属充电站', width: 160, halign: 'center', sortable: true},
                    {field: 'install_site', title: '安装地点', width: 200, halign: 'center', sortable: true},
                    {field: 'install_type', title: '安装方式', align: 'center', width: 70, sortable: true,
                        formatter: function (value, row, index) {
                            try {
                                var str = 'ChargeSpotsIndex.CONFIG.install_type.' + value + '.text';
                                return eval(str);
                            } catch (e) {
                                return '';
                            }
                        }
                    },
                    {field: 'install_date', title: '安装日期', align: 'center', width: 80, sortable: true},
                    {field: 'connection_type', title: '连接方式', align: 'center', width: 70, sortable: true,
                        formatter: function (value, row, index) {
                            try {
                                var str = 'ChargeSpotsIndex.CONFIG.connection_type.' + value + '.text';
                                return eval(str);
                            } catch (e) {
                                return '';
                            }
                        }
                    },
                    /*{field: 'manufacturer', title: '生产厂家', align: 'center', width: 70, sortable: true,
                        formatter: function (value, row, index) {
                            try {
                                var str = 'ChargeSpotsIndex.CONFIG.manufacturer.' + value + '.text';
                                return eval(str);
                            } catch (e) {
                                return '';
                            }
                        }
                    },
                    {field: 'code_from_factory', title: '出厂编号', width: 110, halign: 'center', sortable: true},
                    {field: 'model', title: '电桩型号', width: 70, align: 'center', sortable: true,
                        formatter: function (value, row, index) {
                            try {
                                var str = 'ChargeSpotsIndex.CONFIG.model.' + value + '.text';
                                return eval(str);
                            } catch (e) {
                                return '';
                            }
                        }
                    },*/
                    {field: 'logic_addr', title: '逻辑地址', width: 70, align: 'center', sortable: true},
                    {field: 'sim', title: 'simID', width: 70, align: 'center', sortable: true},
                    {field: 'mark', title: '备注', width: 150, halign: 'center', sortable: true},
                    {field: 'sysuser', title: '登记人员', align: 'center', width: 80, sortable: true}
                ]],
                onDblClickRow: function (rowIndex, rowData) {
                    ChargeSpotsIndex.edit(rowData.id);
                },
                onLoadSuccess: function (data) {
                    //单元格内容悬浮提示，doCellTip()是在入口文件index.php中拓展的。
                    $(this).datagrid('doCellTip', {
                        position: 'bottom',
                        maxWidth: '200px',
                        onlyShowInterrupt: true, //false时所有单元格都显示提示；true时配合specialShowFields自定义要提示的列
                        specialShowFields: [     //需要提示的列
                           //{field: 'install_site', showField: 'install_site'}
                        ],
                        tipStyler: {
                            backgroundColor: '#E4F0FC',
                            borderColor: '#87A9D0',
                            boxShadow: '1px 1px 3px #292929'
                        }
                    });
                }
            });

            // 初始化【所属充电站】combobox
            $('#ChargeSpotsIndex_searchForm_chooseChargeStation').combobox({
                //panelWidth: 'auto',
                valueField: 'cs_id',
                textField: 'cs_name',
                editable: false,
                onChange:function(){
                    ChargeSpotsIndex.search();
                },
                data: <?php echo json_encode($chargeStation); ?>
            });

            //初始化【新增/修改】窗口
            $('#ChargeSpotsIndex_addEditWin').dialog({
                title: '新增/修改电桩',
                width: 900,
                height: 400,
                closed: true,
                cache: true,
                modal: true,
                maximizable: false,
                draggable: true,
                resizable: true,
                onClose: function () {
                    $(this).dialog('clear');
                },
                buttons: [{
                    text: '确定',
                    iconCls: 'icon-ok',
                    handler: function () {
                        var form = $('#ChargeSpotsIndex_addEditWin_form');
                        var id = $('input[name="id"]',form)[0].value; // 按电桩id判断是新增还是修改。
                        var _url = ChargeSpotsIndex.URL.add;
                        if(parseInt(id) > 0) _url = ChargeSpotsIndex.URL.edit;
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
                                    $('#ChargeSpotsIndex_addEditWin').dialog('close');
                                    $('#ChargeSpotsIndex_datagrid').datagrid('reload');
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
                        $('#ChargeSpotsIndex_addEditWin').dialog('close');
                    }
                }]
            });

            //初始化【查看电桩详情】窗口
            $('#ChargeSpotsIndex_scanDetailsWin').window({
                title: '查看电桩详情',
                width: 900,
                height: 530,
                closed: true,
                cache: true,
                modal: true,
                resizable: true,
                collapsible: false,
                minimizable: false,
                maximizable: false,
                onClose: function () {
                    $(this).window('clear');
                }
            });

            //初始化【查看充电记录】窗口
            $('#ChargeSpotsIndex_scanChargeRecordsWin').window({
                title: '查看充电记录',
                width: 1100,
                height: 500,
                closed: true,
                cache: true,
                modal: true,
                resizable: false,
                collapsible: false,
                minimizable: false,
                maximizable: true,
                onClose: function () {
                    $(this).window('clear');
                }
            });

            //初始化【二维码】窗口
            $('#ChargeSpotsIndex_qrCodeWin').window({
                title: '二维码',
                iconCls: 'icon-qrcode',
                width: 800,
                height: 500,
                closed: true,
                cache: true,
                modal: true,
                resizable: false,
                collapsible: false,
                minimizable: false,
                maximizable: false,
                onClose: function () {
                    $(this).window('clear');
                }
            });

            // 初始化【充电计量计费监控】窗口
            $('#ChargeSpotsIndex_monitorChargeWin').window({
                title: '充电计量计费监控数据',
                iconCls: 'icon-chart-curve',
                width: 1200,
                height: 580,
                closed: true,
                cache: true,
                modal: true,
                collapsible: false,
                minimizable: false,
                maximizable: true,
                onClose: function () {
                    $(this).window('clear');
                }
            });

        },
        //获取当前所选择的记录
        getCurrentSelected: function(multiline){
            var datagrid = $('#ChargeSpotsIndex_datagrid');
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
        //添加
        add: function () {
            $('#ChargeSpotsIndex_addEditWin')
                .dialog('open')
                .dialog('refresh', ChargeSpotsIndex.URL.add)
                .dialog('setTitle', '新增电桩');
        },
        //修改
        edit: function (id) {
            if (!id) id = (this.getCurrentSelected()).id;
            if (!id) return;
            $('#ChargeSpotsIndex_addEditWin')
                .dialog('open')
                .dialog('refresh', ChargeSpotsIndex.URL.edit + '&id=' + id)
                .dialog('setTitle', '修改电桩');
        },
        //删除
        remove: function () {
            var id = (this.getCurrentSelected()).id;
            if (!id) return;
            $.messager.confirm('确定删除', '你确定要删除该条记录吗？', function (r) {
                if (r) {
                    $.ajax({
                        type: 'get',
                        url: ChargeSpotsIndex.URL.remove,
                        data: {id: id},
                        dataType: 'json',
                        success: function (data) {
                            if (data) {
                                $.messager.alert('提示', data.info, 'info');
                                $('#ChargeSpotsIndex_datagrid').datagrid('reload');
                            } else {
                                $.messager.alert('错误', data.info, 'error');
                            }
                        }
                    });
                }
            });
        },
        //查看电桩详情
        scanDetails: function () {
            var id = (this.getCurrentSelected()).id;
            if (!id) return;
            $('#ChargeSpotsIndex_scanDetailsWin')
                .window('open')
                .window('refresh', ChargeSpotsIndex.URL.scanDetails + '&id=' + id);
        },
        //查看充电记录
        scanChargeRecords: function () {
            var id = (this.getCurrentSelected()).id;
            if (!id) return;
            $('#ChargeSpotsIndex_scanChargeRecordsWin')
                .window('open')
                .window('refresh', ChargeSpotsIndex.URL.scanChargeRecords + '&id=' + id);
        },
        //生成二维码
        qrCode: function () {
            var id = (this.getCurrentSelected()).id;
            if (!id) return;
            $('#ChargeSpotsIndex_qrCodeWin')
                .dialog('open')
                .dialog('refresh', ChargeSpotsIndex.URL.qrCode + '&id=' + id);
        },
        // 充电计量计费监控
        monitorCharge: function(){
            var id = (this.getCurrentSelected()).id;
            if (!id) return;
            $('#ChargeSpotsIndex_monitorChargeWin')
                .window('open')
                .window('refresh',ChargeSpotsIndex.URL.monitorCharge + '&id=' + id);
        },
        //查询
        search: function () {
            var form = $('#ChargeSpotsIndex_searchFrom');
            var data = {};
            var searchCondition = form.serializeArray();
            for (var i in searchCondition) {
                data[searchCondition[i]['name']] = $.trim(searchCondition[i]['value']);
            }
            $('#ChargeSpotsIndex_datagrid').datagrid('load', data);
        },
        //重置
        reset: function () {
            $('#ChargeSpotsIndex_searchFrom').form('reset');
            ChargeSpotsIndex.search(); //防止下拉框和日期重置时无法执行onChange事件
        },
        //导出Excel
        exportGridData: function () {
            var form = $('#ChargeSpotsIndex_searchFrom');
            var str = form.serialize();
            window.open(ChargeSpotsIndex.URL.exportGridData + "&" + str);
        },
        //在地图上显示
        showOnMap: function () {
            var grid = $('#ChargeSpotsIndex_datagrid');
            if (grid.datagrid('getData').total < 1) {
                $.messager.alert('警告', '还没有任何数据！', 'warning');
                return false;
            }
            var _title = '地图标注-电桩';
            //在新tab里显示
            if ($('#easyui_tabs_index_index_main').tabs('exists', _title)) {
                $('#easyui_tabs_index_index_main').tabs('select', _title);
                return;
            }
            var form = $('#ChargeSpotsIndex_searchFrom');
            var _href = ChargeSpotsIndex.URL.showOnMap +'&'+form.serialize();
            $('#easyui_tabs_index_index_main').tabs('add',{
                title: _title,
                content: '<iframe scrolling="no" frameborder="0" src="' + _href + '" style="width:100%;height:100%;"></iframe>',
                closable: true,
                fit: true
            });
        }
    }

    // 执行初始化函数
    ChargeSpotsIndex.init();

</script>