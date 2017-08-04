<table id="CardChargeCardSwapList_datagrid"></table> 
<div id="CardChargeCardSwapList_datagridToolbar">
    <div class="easyui-panel" title="检索区域" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="CardChargeCardSwapList_searchFrom">
                <ul class="search-main">
                    <li>
                        <div class="item-name">电卡编号</div>
                        <div class="item-input">
                            <input name="cc_code" style="width:100%;" />
                        </div>
                    </li>                                     
                    <li>
                        <div class="item-name">调剂类型</div>
                        <div class="item-input">
                            <input name="type" style="width:100%;" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">操作时间</div>
                        <div class="item-input" style="width:320px">
                            <input class="easyui-datetimebox" type="text" name="start_atime" style="width:150px;"
                                   data-options=""
                                />
                            -
                            <input class="easyui-datetimebox" type="text" name="end_atime" style="width:150px;"
                                   data-options=""
                                />
                        </div>
                    </li>
                    <li class="search-button">
                        <button
                            class="easyui-linkbutton"
                            iconCls="icon-search"
                        >查询</button>
                        <button
                            onclick="CardChargeCardSwapList.resetSearchForm();return false;"
                            class="easyui-linkbutton"
                            iconCls="icon-reload"
                        >重置</button>
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
                <a onclick="<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon'] ;?>'"><?= $val['text'] ;?></a>
            <?php } ?>
        </div>
    <?php } ?>

</div>
<script>
    var CardChargeCardSwapList = {
        // 初始化
        init: function () {
            var searchForm = $('#CardChargeCardSwapList_searchFrom');
            var easyuiDatagrid = $('#CardChargeCardSwapList_datagrid');
            //--初始化表格
            easyuiDatagrid.datagrid({
                method: 'get',
                url: "<?= yii::$app->urlManager->createUrl(['card/swap/get-list-data']); ?>",
                toolbar: "#CardChargeCardSwapList_datagridToolbar",
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
                    {field: 'ck',checkbox: true},
                    {field: 'id',hidden: true},
                    {field: 'cc_code', title: '电卡编号', width: 110, align: 'center', sortable: true}
                ]],
                columns: [[
                    {field: 'type', title: '调剂类型', align: 'center', width: 80, sortable: true,
                        formatter: function (value, row, index) {
                            if(value ==  'add'){
                                return '增加';
                            }
                            return '减少';
                        }
                    },
                    {field: 'before_money', title: '调剂前金额',halign: 'center', align: 'right', width: 80, sortable: true},
                    {field: 'money', title: '调剂金额',halign: 'center',align: 'right', width: 80, sortable: true},
                    {field: 'after_money', title: '调剂后金额',halign: 'center',align: 'right', width: 80, sortable: true},
                    {field: 'write_status', title: '写卡状态', align: 'center', width: 80, sortable: true,
                        formatter: function (value, row, index) {
                            if(value ==  'success'){
                                return '<span style="background-color:#05CD69;color:#fff;padding:2px 5px;">成功</span>';
                            }
                            return '<span style="background-color:#FF4040;color:#fff;padding:2px 5px;">失败</span>';
                        }
                    },
                    {field: 'atime', title: '操作时间', align: 'center', width: 130, sortable: true},
                    {field: 'username',halign: 'center',title: '操作账号',width: 120, sortable: true},
                    {field: 'note',halign: 'centet',title: '备注',width: 200}
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
            //表单自动化处理开始
            searchForm.submit(function(){
                var data = {};
                var searchCondition = $(this).serializeArray();
                for(var i in searchCondition){
                    data[searchCondition[i]['name']] = searchCondition[i]['value'];
                }
                easyuiDatagrid.datagrid('load',data);
            return false;
            });
            searchForm.find('input[name=cc_code]').textbox({
                onChange: function(){
                    searchForm.submit();
                }
            });
            searchForm.find('input[name=type]').combobox({
                valueField:'value',
                textField:'text',
                data: [{"value": '',"text": '不限'},{"value": 'reduce',"text": '减少'},{"value": 'add',"text": '增加'}],
                editable: false,
                panelHeight:'auto',
                onSelect: function(){
                    searchForm.submit();
                }
            });
            //表单自动化处理结束
        },
        resetSearchForm: function(){
            var searchForm = $('#CardChargeCardSwapList_searchFrom');
            searchForm.form('reset');
            searchForm.submit();
        },
    }
    // 执行初始化函数
    CardChargeCardSwapList.init();
  //导出
	CardChargeCardSwapList.export_excel = function(){
		var form = $('#CardChargeCardSwapList_searchFrom');
		var searchConditionStr = form.serialize();
		window.open("<?= yii::$app->urlManager->createUrl(['card/swap/export-excel']); ?>&rows=<?php echo yii::$app->request->get('rows')?>&"+form.serialize());
	}
</script>