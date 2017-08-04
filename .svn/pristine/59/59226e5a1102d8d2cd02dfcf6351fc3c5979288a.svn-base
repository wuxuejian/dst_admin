<table id="polemonitorChargeRecordIndex_datagrid"></table> 
<div id="polemonitorChargeRecordIndex_datagridToolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="polemonitorChargeRecordIndex_searchForm">
                <ul class="search-main">
                    <li>
                        <div class="item-name">选择电桩</div>
                        <div class="item-input">
                            <select
                                class="easyui-combogrid"
                                name="chargerId"
                                style="width:100%;"
                                data-options="
                                    panelWidth: 420,
                                    panelHeight: 200,
                                    delay: 800,
                                    mode:'remote',
                                    idField: 'id',
                                    textField: 'code_from_compony',
                                    value:<?= $defaultChargerId; ?>,
                                    url: '<?= yii::$app->urlManager->createUrl(['polemonitor/combogrid/get-charger-list']); ?>',
                                    method: 'get',
                                    scrollbarSize:0,
                                    pagination: true,
                                    pageSize: 10,
                                    pageList: [10,20,30],
                                    fitColumns: true,
                                    rownumbers: true,
                                    columns: [[
                                        {field:'id',title:'ID',width:40,hidden:true},
                                        {field:'code_from_compony',title:'电桩编号',align:'center',width:90},
                                        {field:'logic_addr',title:'逻辑地址',align:'center',width:90},
                                        {field:'cs_name',title:'电站名称',halign:'center',width:250}
                                    ]],
                                    onHidePanel:function(){
                                        var _combogrid = $(this);
                                        var value = _combogrid.combogrid('getValue');
                                        var textbox = _combogrid.combogrid('textbox');
                                        var text = textbox.val();
                                        var rows = _combogrid.combogrid('grid').datagrid('getSelections');
                                        if(text && rows.length < 1 && value == text){
                                            $.messager.show(
                                                {
                                                    title: '无效值',
                                                    msg:'【' + text + '】不是有效值！请重新检索并选择一个电桩！'
                                                }
                                            );
                                            _combogrid.combogrid('clear');
                                        }else{
                                            polemonitorChargeRecordIndex.search();
                                        }
                                    }
                                "
                            ></select>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">交易流水号</div>
                        <div class="item-input">
                            <input
                                class="easyui-textbox"
                                type="text"
                                name="DEAL_NO"
                                style="width:100%;"
                                data-options="
                                    onChange:function(){
                                        polemonitorChargeRecordIndex.search();
                                    }
                                "
                                />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">电卡编号</div>
                        <div class="item-input">
                            <input
                                class="easyui-textbox"
                                type="text"
                                name="START_CARD_NO"
                                style="width:100%;"
                                data-options="
                                    onChange:function(){
                                        polemonitorChargeRecordIndex.search();
                                    }
                                "
                                />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">充电时间</div>
                        <div class="item-input">
                            <input
                                class="easyui-datebox"
                                type="text"
                                name="DEAL_START_DATE_start"
                                style="width:91px;"
                                data-options="
                                    onChange:function(){
                                        polemonitorChargeRecordIndex.search();
                                    }
                                "
                                />
                            -
                            <input
                                class="easyui-datebox"
                                type="text"
                                name="DEAL_START_DATE_end"
                                style="width:91px;"
                                data-options="
                                    onChange:function(){
                                        polemonitorChargeRecordIndex.search();
                                    }
                                "
                                />
                        </div>
                    </li>
                    <li class="search-button">
                        <a href="javascript:void(0)" onclick="polemonitorChargeRecordIndex.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                        <a href="javascript:void(0)" onclick="polemonitorChargeRecordIndex.reset()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</a>
                    </li>
                </ul>
            </form>
        </div>
    </div>
    <?php if(!empty($buttons)){ ?>
        <div class="easyui-panel" title="充电记录" style="padding:3px 2px;" data-options="
        iconCls: 'icon-table-list',
        border: false
    ">
            <?php foreach($buttons as $val){ ?>
                <a href="javascript:void(0)"  onclick="<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon'] ;?>'"><?= $val['text'] ;?></a>
            <?php } ?>
        </div>
    <?php } ?>
</div>
<script>
    var polemonitorChargeRecordIndex = {
        init: function () {
            //获取列表数据
            $('#polemonitorChargeRecordIndex_datagrid').datagrid({
                method: 'get',
                url: "<?php echo yii::$app->urlManager->createUrl(['polemonitor/charge-record/get-list']); ?>",
                fit: true,
                border: false,
                toolbar: "#polemonitorChargeRecordIndex_datagridToolbar",
                pagination: true,
                loadMsg: '数据加载中...',
                striped: true,
                checkOnSelect: true,
                rownumbers: true,
                singleSelect: false,
                pageSize:20,
                frozenColumns: [[
                    {field: 'ck', checkbox: true},
                    {field: 'DEV_ID', title: '设备ID', width: 50,align: 'center', hidden: false},
                    {field: 'DEAL_NO', title: '交易流水号', width: 80,align: 'center',sortable: true},
                ]],
                columns: [[
                    {field: 'TIME_TAG', title: '数据时间', width: 140, align: 'center', sortable: true},
                    {field: 'DEV_ADDR', title: '逻辑地址', width: 70, align: 'center', sortable: true},
                    {field: 'DEAL_TYPE', title: '交易类型', width: 60, align: 'center', sortable: true},
                    {field: 'AREA_CODE', title: '地区代码', width: 60, align: 'center', sortable: true},
                    {field: 'START_CARD_NO', title: '开始卡号', width: 120, align: 'center', sortable: true},
                    {field: 'END_CARD_NO', title: '结束卡号', width: 120, align: 'center', sortable: true},
//                    {field: 'START_CARD_TYPE', title: '开始卡型', width: 60, align: 'center', sortable: true},
//                    {field: 'END_CARD_TYPE', title: '结束卡型', width: 60, align: 'center', sortable: true},

                    {field: 'START_DEAL_DL', title: '开始交易电量(度)', width: 130, halign: 'center',align:'right', sortable: true},
//                    {field: 'START_DEAL_R1_DL', title: '开始交易费率1电量(度)', width: 130, halign: 'center',align:'right', sortable: true},
//                    {field: 'START_DEAL_R2_DL', title: '开始交易费率2电量(度)', width: 130, halign: 'center',align:'right', sortable: true},
//                    {field: 'START_DEAL_R3_DL', title: '开始交易费率3电量(度)', width: 130, halign: 'center',align:'right', sortable: true},
//                    {field: 'START_DEAL_R4_DL', title: '开始交易费率4电量(度)', width: 130, halign: 'center',align:'right', sortable: true},

                    {field: 'END_DEAL_DL', title: '结束交易电量(度)', width: 130, halign: 'center',align:'right', sortable: true},
//                    {field: 'END_DEAL_R1_DL', title: '结束交易费率1电量(度)', width: 130, halign: 'center',align:'right', sortable: true},
//                    {field: 'END_DEAL_R2_DL', title: '结束交易费率2电量(度)', width: 130, halign: 'center',align:'right', sortable: true},
//                    {field: 'END_DEAL_R3_DL', title: '结束交易费率3电量(度)', width: 130, halign: 'center',align:'right', sortable: true},
//                    {field: 'END_DEAL_R4_DL', title: '结束交易费率4电量(度)', width: 130, halign: 'center',align:'right', sortable: true},

                    {field: 'DEAL_R1_PRICE', title: '交易费率1电价(元)', width: 130, halign: 'center',align:'right', sortable: true},
//                    {field: 'DEAL_R2_PRICE', title: '交易费率2电价(元)', width: 130, halign: 'center',align:'right', sortable: true},
//                    {field: 'DEAL_R3_PRICE', title: '交易费率3电价(元)', width: 130, halign: 'center',align:'right', sortable: true},
//                    {field: 'DEAL_R4_PRICE', title: '交易费率4电价(元)', width: 130, halign: 'center',align:'right', sortable: true},

                    {field: 'STOP_FEE_PRICE', title: '停车费单价(元/小时)', width: 130, halign: 'center',align:'right', sortable: true},

                    {field: 'DEAL_START_DATE', title: '交易开始时间', width: 140, align: 'center', sortable: true},
                    {field: 'DEAL_END_DATE', title: '交易结束时间', width: 140, align: 'center', sortable: true},

                    {field: 'STOP_FEE', title: '停车费(元)', width: 100, halign: 'center',align:'right', sortable: true},
                    {field: 'REMAIN_BEFORE_DEAL', title: '交易前余额(元)', width: 100, halign: 'center',align:'right', sortable: true},
                    {field: 'REMAIN_AFTER_DEAL', title: '交易后余额(元)', width: 100, halign: 'center',align:'right', sortable: true},

//                    {field: 'CAR_DEAL_COUNTER', title: '卡交易计数器', width: 100, align: 'center', sortable: true},
                    {field: 'TRM_NO', title: '终端号', width: 60, align: 'center', sortable: true},
                    {field: 'CARD_VER_NO', title: '卡版本号', width: 60, align: 'center', sortable: true},
                    {field: 'POS_NO', title: 'POS机号', width: 60, align: 'center', sortable: true},
                    {field: 'CARD_STATUS', title: '卡状态码', width: 60, align: 'center', sortable: true},
                    {field: 'WRITE_TIME', title: '写库时间', width: 140, align: 'center', sortable: true},
                    {field: 'CAR_NO', title: '车号', width: 60, align: 'center', sortable: true},
                    {field: 'INNER_ID', title: '测量点', width: 60, align: 'center', sortable: true}
                ]],
                onLoadSuccess: function(data){
                    if(data.errInfo){
                        $.messager.show({
                            title:'获取数据失败',
                            msg: '<span style="color:red;">' + data.errInfo + '</span>'
                        });
                    }
                }
            });
        },
        //获取当前选择的记录。参数all = true标示是否要返回所有被选择的记录
        getCurrentSelected: function (all){
            var datagrid = $('#polemonitorChargeRecordIndex_datagrid');
            var selectRows = datagrid.datagrid('getSelections');
            if (selectRows.length <= 0) {
                $.messager.show({
                    title: '请选择',
                    msg: '请选择要操作的记录！'
                });
                return false;
            }
            if (all){
                return selectRows;
            } else {
                return selectRow[0];
            }
        },
        //查询
        search: function () {
            var form = $('#polemonitorChargeRecordIndex_searchForm');
            var data = {};
            var searchCondition = form.serializeArray();
            for (var i in searchCondition) {
                data[searchCondition[i]['name']] = $.trim(searchCondition[i]['value']);
            }
            $('#polemonitorChargeRecordIndex_datagrid').datagrid('load', data);
        },
        //重置
        reset: function(){
            $('#polemonitorChargeRecordIndex_searchForm').form('reset');
            polemonitorChargeRecordIndex.search();
        },
        //导出Excel
        exportGridData: function () {
            var form = $('#polemonitorChargeRecordIndex_searchForm');
            var str = form.serialize();
            window.open("<?php echo yii::$app->urlManager->createUrl(['polemonitor/charge-record/export-grid-data']); ?>" + "&" + str);
        }
    }

    // 执行初始化函数
    polemonitorChargeRecordIndex.init();

</script>