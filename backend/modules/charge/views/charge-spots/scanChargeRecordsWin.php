<table id="ChargeSpotsIndex_scanChargeRecords_datagrid"></table> 
<div id="ChargeSpotsIndex_scanChargeRecords_datagridToolbar">
    <div class="easyui-panel" title="检索区域" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">
        <div class="data-search-form">
            <form id="ChargeSpotsIndex_scanChargeRecords_searchFrom">
                <ul class="search-main">
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
                                        ChargeSpotsIndex_scanChargeRecords.search();
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
                                        ChargeSpotsIndex_scanChargeRecords.search();
                                    }
                                "
                            />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">状态</div>
                        <div class="item-input">
                            <select
                                class="easyui-combobox"
                                name="DEAL_TYPE"
                                style="width:100%;"
                                data-options="
                                    panelHeight:'auto',
                                    editable:false,
                                    onChange:function(){
                                        ChargeSpotsIndex_scanChargeRecords.search();
                                    }
                                "
                            >
                                <option value="0" selected="true">正在充电</option>
                                <option value="1">结束充电</option>
                            </select>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">充电时间</div>
                        <div class="item-input">
                            <input
                                class="easyui-datebox"
                                type="text"
                                name="DEAL_START_DATE_start"
                                style="width:93px;"
                                data-options="
                                    onChange:function(){
                                        ChargeSpotsIndex_scanChargeRecords.search();
                                    }
                                "
                                />
                            -
                            <input
                                class="easyui-datebox"
                                type="text"
                                name="DEAL_START_DATE_end"
                                style="width:93px;"
                                data-options="
                                    onChange:function(){
                                        ChargeSpotsIndex_scanChargeRecords.search();
                                    }
                                "
                                />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">记录时间</div>
                        <div class="item-input">
                            <input
                                class="easyui-datebox"
                                type="text"
                                name="TIME_TAG_start"
                                style="width:93px;"
                                data-options="
                                    onChange:function(){
                                        ChargeSpotsIndex_scanChargeRecords.search();
                                    }
                                "
                                />
                            -
                            <input
                                class="easyui-datebox"
                                type="text"
                                name="TIME_TAG_end"
                                style="width:93px;"
                                data-options="
                                    onChange:function(){
                                        ChargeSpotsIndex_scanChargeRecords.search();
                                    }
                                "
                                />
                        </div>
                    </li>
                    <li class="search-button">
                        <button class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button onclick="ChargeSpotsIndex_scanChargeRecords.reset();return false;" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
                    </li>
                </ul>
            </form>
        </div>
    </div>
    <?php if(!empty($buttons)){ ?>
        <div class="easyui-panel" title="记录列表" style="padding:3px 2px;width:100%;" data-options="
        iconCls: 'icon-table-list',
        border: false
    ">
            <?php foreach($buttons as $val){ ?>
                <button onclick="javascript:<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon'] ;?>'"><?= $val['text'] ;?></button>
            <?php } ?>
        </div>
    <?php } ?>
</div>
<script>
    var ChargeSpotsIndex_scanChargeRecords = {
        // 请求的URl
        param: {
            "urlDatagrid": "<?php echo yii::$app->urlManager->createUrl(['charge/charge-spots/get-charge-records','id'=>$id]); ?>",
            "easyuiDatagrid": $('#ChargeSpotsIndex_scanChargeRecords_datagrid'),
            "searchForm": $('#ChargeSpotsIndex_scanChargeRecords_searchFrom')
        },
        // 初始化函数
        init: function(){
            this.param.easyuiDatagrid.datagrid({
                method: 'get',
                url: ChargeSpotsIndex_scanChargeRecords.param.urlDatagrid,
                queryParams: {end_status: 1},
                toolbar: "#ChargeSpotsIndex_scanChargeRecords_datagridToolbar",
                fit:true,
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
                    {field: 'DEAL_NO', title: '交易流水号', width: 80,align: 'center',sortable:true}
                ]],
                columns: [[
                    {field: 'START_CARD_NO', title: '电卡编号', width: 120, align: 'center',sortable:true},
                    {field: 'DEAL_TYPE', title: '状态', width: 80, align: 'center',sortable:true,
                        formatter:function(v){
                            switch(parseInt(v)){
                                case 0:
                                    return '<span style="background-color:#FFCC01;color:#fff;padding:2px 5px;">正在充电</span>';
                                case 1:
                                    return '<span style="background-color:#05CD69;color:#fff;padding:2px 5px;">结束正常</span>';
                                case 2:
                                    return '<span style="background-color:#FF4040;color:#fff;padding:2px 5px;">结束异常</span>';
                            }
                        }
                    },
                    {field: 'START_DEAL_DL', title: '开始电量(度)', width: 90, halign: 'center',align:'right',sortable:true},
                    {field: 'END_DEAL_DL', title: '结束电量(度)', width: 90, halign: 'center',align:'right',sortable:true},
                    {field: 'c_dl', title: '<span style="color:#FF8000;">消费电量(度)</span>', width: 90, halign: 'center',align:'right'},
                    {field: 'REMAIN_BEFORE_DEAL', title: '交易前余额(元)', width: 100, halign: 'center',align:'right',sortable:true},
                    {field: 'REMAIN_AFTER_DEAL', title: '交易后余额(元)', width: 100, halign: 'center',align:'right',sortable:true},
                    {field: 'c_amount', title: '<span style="color:#FF8000;">消费金额(元)</span>', width: 100, halign: 'center',align:'right'},
                    {field: 'DEAL_START_DATE', title: '开始时间', width: 130, align: 'center',sortable:true},
                    {field: 'DEAL_END_DATE', title: '结束时间', width: 130, align: 'center',sortable:true},
                    {field: 'CAR_NO', title: '车号', width: 50, align: 'center',sortable:true},
                    {field: 'INNER_ID', title: '测量点', width: 50, align: 'center',sortable:true},
                    // {field: 'gun_name', title: '电枪', width: 50, align: 'center'},
                    {field: 'TIME_TAG', title: '记录时间', width: 130, align: 'center',sortable:true}
                ]]
            });
            this.param.searchForm.submit(function(){
                var data = {};
                var searchCondition = $(this).serializeArray();
                for(var i in searchCondition){
                    data[searchCondition[i]['name']] = $.trim(searchCondition[i]['value']);
                }
                ChargeSpotsIndex_scanChargeRecords.param.easyuiDatagrid.datagrid('load',data);
                return false;
            });
        },
        // 查询
        search: function(){
            this.param.searchForm.submit();
        },
        // 重置
        reset: function(){
            this.param.searchForm.form('reset');
            this.param.searchForm.submit();
        }
    }

    // 执行初始化函数
    ChargeSpotsIndex_scanChargeRecords.init();

</script>