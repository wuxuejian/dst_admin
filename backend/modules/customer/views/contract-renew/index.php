<table id="customerContractRenewIndex_datagrid"></table> 
<div id="customerContractRenewIndex_datagridToobar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="customerContractRenewIndex_searchForm">
                <ul class="search-main">
                    <li>
                        <div class="item-name">合同编号</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="contract_number" style="width:100%;"  />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">承租客户</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="customer_name" style="width:100%;"  />
                        </div>
                    </li>
                    <li class="search-button">
                        <a onclick="customerContractRenewIndex.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                        <a onclick="customerContractRenewIndex.reset()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</a>
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
            <a onclick="<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon']; ?>'"><?= $val['text']; ?></a>
        <?php } ?>
    </div>
    <?php } ?>
</div>
<!-- 窗口 -->
<div id="customerContractRenewIndex_renewAddWin"></div>
<!-- 窗口 -->
<script>
    // 请求的URl
    var customerContractRenewIndex_URL_getList = "<?php echo yii::$app->urlManager->createUrl(['customer/contract-renew/get-list']); ?>";
    var customerContractRenewIndex_URL_renewAdd = "<?php echo yii::$app->urlManager->createUrl(['customer/contract-renew/renew-add']); ?>";

    var customerContractRenewIndex = {
        init: function () {
            //获取列表数据
            $('#customerContractRenewIndex_datagrid').datagrid({
                method: 'get',
                url: customerContractRenewIndex_URL_getList,
                fit: true,
                border: false,
                toolbar: "#customerContractRenewIndex_datagridToobar",
                pagination: true,
                loadMsg: '数据加载中...',
                striped: true,
                checkOnSelect: true,
                rownumbers: true,
                singleSelect: false,
                showFooter: true,
                frozenColumns: [[
                    {field: 'ck', checkbox: true},
                    {field: 'id', title: '续费记录ID', hidden: true},
                    {field: 'contract_id', title: '合同ID', align:'center', width: 60, sortable:true, hidden: true},
                    {field: 'contract_number', title: '合同编号', halign:'center', width: 120, sortable:true},
                    {field: 'customer_name', title: '承租客户', halign:'center', width: 180, sortable:true}
                ]],
                columns: [[
                    {
                        field: 'cost_expire_time', title: '续费到期时间', width: 90, align: 'center',
                        formatter: function (value) {
                            if (!isNaN(value) && value != 0) {
                                return formatDateToString(value);
                            }
                        }
                    },
                    {field: 'should_money', title: '应收金额(元)', width: 100, align: 'right', halign: 'center'},
                    {field: 'true_money', title: '实收金额(元)', width: 100, align: 'right', halign: 'center'},
                    {
                        field: 'action_time', title: '操作时间', width: 140, align: 'center',
                        formatter: function (value) {
                            if (!isNaN(value) && value != 0) {
                                return formatDateToString(value, true);
                            }
                        }
                    },
                    {field: 'admin_name', title: '操作人员', align:'center', width: 100},
                    {field: 'note', title: '备注', width: 250, halign: 'center'}
                ]],
                onLoadSuccess: function (data) {
                    //单元格内容悬浮提示，doCellTip()是在入口文件index.php中拓展的。
                    $(this).datagrid('doCellTip', {
                        position: 'bottom',
                        maxWidth: '200px',
                        onlyShowInterrupt: true, //false时所有单元格都显示提示；true时配合specialShowFields自定义要提示的列
                        specialShowFields: [     //需要提示的列
                            //{field: 'customer_name', showField: 'customer_name'}
                        ],
                        tipStyler: {
                            backgroundColor: '#E4F0FC',
                            borderColor: '#87A9D0',
                            boxShadow: '1px 1px 3px #292929'
                        }
                    });
                }
            });
            //初始化添加续费记录窗口
            $('#customerContractRenewIndex_renewAddWin').dialog({
                title: '添加合同续费',
                width: '650px',
                height: '500px',
                closed: true,
                cache: true,
                modal: true,
                maximizable: false,
                buttons: [{
                    text: '确定',
                    iconCls: 'icon-ok',
                    handler: function () {
                        var form = $('#customerContractRenewIndex_renewAddWin_topForm');
                        if (!form.form('validate')) {
                            return false;
                        }
                        var data = form.serialize();
                        $.ajax({
                            type: 'post',
                            url: customerContractRenewIndex_URL_renewAdd,
                            data: data,
                            dataType: 'json',
                            success: function (data) {
                                if (data.status) {
                                    $.messager.alert('添加成功', data.info, 'info');
                                    $('#customerContractRenewIndex_renewAddWin').dialog('close');
                                    $('#customerContractRenewIndex_datagrid').datagrid('reload');
                                } else {
                                    $.messager.alert('添加失败', data.info, 'error');
                                }
                            }
                        });
                    }
                }, {
                    text: '取消',
                    iconCls: 'icon-cancel',
                    handler: function () {
                        $('#customerContractRenewIndex_renewAddWin').dialog('close');
                    }
                }]
            });
        },
        //添加合同续费
        renewAdd: function () {
            var selectedRow = $('#customerContractRenewIndex_datagrid').datagrid('getSelected');
            var contractId = 0;
            if (selectedRow) {
                contractId = selectedRow.contract_id;
            }
            var _url = customerContractRenewIndex_URL_renewAdd + '&contractId=' + contractId;
            $('#customerContractRenewIndex_renewAddWin')
                .dialog('open')
                .dialog('refresh', _url);
        },
        //查询
        search: function () {
            var form = $('#customerContractRenewIndex_searchForm');
            var data = {};
            var searchCondition = form.serializeArray();
            for (var i in searchCondition) {
                data[searchCondition[i]['name']] = searchCondition[i]['value'];
            }
            $('#customerContractRenewIndex_datagrid').datagrid('load', data);
        },
        //重置
        reset: function(){
            $('#customerContractRenewIndex_searchForm').form('reset');
        }
    }
    //执行初始化
    customerContractRenewIndex.init();
</script>