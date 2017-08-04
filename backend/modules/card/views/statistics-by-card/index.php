<table id="statisticsByCardIndex_datagrid"></table> 
<div id="statisticsByCardIndex_datagridToolbar">
    <div class="easyui-panel" title="检索区域" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="statisticsByCardIndex_searchFrom">
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
                                        statisticsByCardIndex.search();
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
                                    panelHeight:'auto',
                                    editable:false,
                                    onChange: function(){
                                        statisticsByCardIndex.search();
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
                                        statisticsByCardIndex.search();
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
                        <div class="item-name">会员编号</div>
                        <div class="item-input">
                            <input
                                class="easyui-textbox"
                                name="holder_code"
                                style="width:100%;"
                                data-options="{
                                    onChange: function(){
                                        statisticsByCardIndex.search();
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
                                name="holder_mobile"
                                style="width:100%;"
                                data-options="{
                                    onChange: function(){
                                        statisticsByCardIndex.search();
                                    }
                                }"
                            />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">会员名称</div>
                        <div class="item-input">
                            <input
                                class="easyui-textbox"
                                name="holder_name"
                                style="width:100%;"
                                data-options="{
                                    onChange: function(){
                                        statisticsByCardIndex.search();
                                    }
                                }"
                            />
                        </div>
                    </li>
                    <li class="search-button">
                        <button class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button onclick="statisticsByCardIndex.reset();return false;" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
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

<script>
    var statisticsByCardIndex = {
        // 初始数据
        CONFIG: <?php echo json_encode($config); ?>,
        // 请求的URL
        URL:{
            getList: "<?php echo yii::$app->urlManager->createUrl(['card/statistics-by-card/get-list']); ?>",
            exportGridData: "<?php echo yii::$app->urlManager->createUrl(['card/statistics-by-card/export-grid-data']); ?>"
        },
        // 初始化
        init: function () {
            var easyuiDatagrid = $('#statisticsByCardIndex_datagrid');
            //--初始化表格
            easyuiDatagrid.datagrid({
                method: 'get',
                url: statisticsByCardIndex.URL.getList,
                toolbar: "#statisticsByCardIndex_datagridToolbar",
                fit: true,
                border: false,
                pagination: true,
                loadMsg: '数据加载中...',
                striped: true,
                checkOnSelect: true,
                rownumbers: true,
                singleSelect: true,
                showFooter: true,
                pageSize: 20,
                frozenColumns: [[
                    {field: 'ck', checkbox: true},
                    {field: 'cc_id', title: '电卡ID', width: 40, align: 'center', hidden: true},
                    {field: 'cc_code', title: '电卡编号', width: 110, align: 'center', sortable: true}
                ]],
                columns: [[
                    {field: 'cc_type', title: '电卡类型', align: 'center', width: 80, sortable: true,
                        formatter: function (value, row, index) {
                            try {
                                var str = 'statisticsByCardIndex.CONFIG.cc_type.' + value + '.text';
                                return eval(str);
                            } catch (e) {
                                return value;
                            }
                        }
                    },
                    {field: 'cc_status', title: '电卡状态', align: 'center', width: 80, sortable: true,
                        formatter: function (value, row, index) {
                            try {
                                var str = 'statisticsByCardIndex.CONFIG.cc_status.' + value + '.text';
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
                    {field: 'recharge_num', title: '充值次数(次)', width: 90, align: 'center', sortable: true},
                    {field: 'recharge_money', title: '充值金额(元)', width: 100, halign: 'center',align: 'right', sortable: true,
                        formatter: function (value, row, index) {
                            if(parseFloat(value)){
                                return value;
                            }else{
                                return '0.00';
                            }
                        }
                    },
                    {field: 'incentive_money', title: '奖励金额(元)', width: 100, halign: 'center',align: 'right', sortable: true,
                        formatter: function (value, row, index) {
                            if(parseFloat(value)){
                                return value;
                            }else{
                                return '0.00';
                            }
                        }
                    },
                    {field: 'cc_current_money', title: '当前余额(元)', width: 90, halign: 'center',align: 'right', sortable: true,
                        formatter: function (value, row, index) {
                            if(parseFloat(value)){
                                return value;
                            }else{
                                return '0.00';
                            }
                        }
                    },
                    {field: 'holder_code', title: '会员编号', width: 140, align: 'center', sortable: true},
                    {field: 'holder_mobile', title: '会员手机', width: 90, align: 'center', sortable: true},
                    {field: 'holder_name', title: '会员名称', width: 100, align: 'center', sortable: true}
                ]]
            });
            var searchForm = $('#statisticsByCardIndex_searchFrom');
            searchForm.submit(function(){
                var data = {};
                var searchCondition = $(this).serializeArray();
                for(var i in searchCondition){
                    data[searchCondition[i]['name']] = $.trim(searchCondition[i]['value']);
                }
                easyuiDatagrid.datagrid('load',data);
                return false;
            });
        },
        // 获取当前所选择的记录
        getCurrentSelected: function(multiline){
            var datagrid = $('#statisticsByCardIndex_datagrid');
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
        // 导出Excel
        exportGridData: function(){
            var searchConditionStr = $('#statisticsByCardIndex_searchFrom').serialize();
            window.open(statisticsByCardIndex.URL.exportGridData + "&" + searchConditionStr);
        },
        // 查询
        search: function(){
            $('#statisticsByCardIndex_searchFrom').submit();
        },
        // 重置
        reset: function(){
            var searchForm = $('#statisticsByCardIndex_searchFrom');
            searchForm.form('reset');
            searchForm.submit();
        }
    }

    // 执行初始化函数
	statisticsByCardIndex.init();

</script>