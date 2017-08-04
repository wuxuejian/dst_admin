<table id="promotionApplyAuditIndex_datagrid"></table>
<div id="promotionApplyAuditIndex_datagridToolbar">
    <div class="easyui-panel" title="检索区域" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="promotionApplyAuditIndex_searchFrom">
                <ul class="search-main">
                    <li>
                        <div class="item-name">申请人</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="applicant" style="width:100%;"
                                data-options="
                                    onChange:function(){
                                        promotionApplyAuditIndex.search();
                                    }
                                "
                            />
                        </div>
                    </li>                    
					<li>
                        <div class="item-name">手机号</div>
                        <div class="item-input">
                           <input class="easyui-textbox" type="text" name="applicant_mobile" style="width:100%;"
                                  data-options="
                                        onChange:function(){
                                            promotionApplyAuditIndex.search();
                                        }
                                  "
                           />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">申请日期</div>
                        <div class="item-input">
                            <input class="easyui-datebox" type="text" name="apply_date_start" style="width:90px;"
                                   data-options="
                                        onChange:function(){
                                            promotionApplyAuditIndex.search();
                                        }
                                   "
                                />
                            -
                            <input class="easyui-datebox" type="text" name="apply_date_end" style="width:90px;"
                                   data-options="
                                        onChange:function(){
                                            promotionApplyAuditIndex.search();
                                        }
                                   "
                                />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">结算状态</div>
                        <div class="item-input">
                            <select class="easyui-combobox" name="settle_status" style="width:100%;" data-options="
                                panelHeight:'auto',
                                editable:false,
                                onChange:function(){
                                     promotionApplyAuditIndex.search();
                                }
                            ">
                                <option value="" selected="selected">不限</option>
                                <option value="UNSETTLED">未结算</option>
                                <option value="SETTLED">已结算</option>
                            </select>

                        </div>
                    </li>
                    <li class="search-button">
                        <a href="javascript:void(0)" onclick="promotionApplyAuditIndex.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                        <a href="javascript:void(0)" onclick="promotionApplyAuditIndex.reset()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</a>
                    </li>
                </ul>
            </form>
        </div>
    </div>

    <?php if(isset($buttons) && !empty($buttons)){ ?>
        <div class="easyui-panel" title="数据列表" style="padding:3px 2px;width:100%;" data-options="
            iconCls: 'icon-table-list',
            border: false
        ">
            <?php foreach($buttons as $val){ ?>
                <a href="javascript:void(0)" onclick="<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon'] ;?>'"><?= $val['text'] ;?></a>
            <?php } ?>
        </div>
    <?php } ?>

</div>

<!-- 窗口 begin-->
<div id="promotionApplyAuditIndex_scanFriendLetDetailsWin"></div>
<div id="promotionApplyAuditIndex_settleWin"></div>
<!-- 窗口 end-->

<script>
	var promotionApplyAuditIndex = {
        //请求的URL
        'URL': {
            'getList': '<?php echo yii::$app->urlManager->createUrl(['promotion/apply-audit/get-list']); ?>',
            'scanFriendLetDetails': '<?php echo yii::$app->urlManager->createUrl(['promotion/apply-audit/scan-friend-let-details']); ?>',
            'settle': '<?php echo yii::$app->urlManager->createUrl(['promotion/apply-audit/settle']); ?>',
            'exportGridData': '<?php echo yii::$app->urlManager->createUrl(['promotion/apply-audit/export-grid-data']); ?>'
        },
        //初始化
        init: function() {
            //列表数据
            $('#promotionApplyAuditIndex_datagrid').datagrid({
                method: 'get',
                url: promotionApplyAuditIndex.URL.getList,
                fit: true,
                border: false,
                toolbar: "#promotionApplyAuditIndex_datagridToolbar",
                pagination: true,
                loadMsg: '数据加载中...',
                striped: true,
                checkOnSelect: true,
                rownumbers: true,
                singleSelect: true,
                pageSize: 20,
                frozenColumns: [[
                    {field: 'ck', checkbox: true},
                    {field: 'id', title: 'ID', width: 40, align: 'center', hidden: true},
                    {field: 'applicant', title: '申请人', width: 70, align: 'center', sortable: true}
                ]],
                columns: [[
                    {field: 'applicant_mobile', title: '手机号', width: 80, align: 'center', sortable: true},
                    {field: 'apply_date', title: '申请日期', width: 80, align: 'center', sortable: true},
                    {field: 'pay_type', title: '转账方式', width: 70, align: 'center', sortable: true,
                        formatter: function(value){
                            switch(value){
                                case 'bank':
                                    return '银行';
                                case 'alipay':
                                    return '支付宝';
                                case 'cash':
                                    return '现金';
                            }
                        }
                    },
                    {field: 'bank_name', title: '银行名称', width: 100, align: 'center', sortable: true},
                    {field: 'bank_card', title: '银行卡号', width: 130, halign: 'center', sortable: true},
                    {field: 'alipay_account', title: '支付宝账号', width: 130, halign: 'center', sortable: true},
                    {field: 'settle_status', title: '结算状态', width: 70, align: 'center', sortable: true,
                        formatter: function(value){
                            if(value == 'SETTLED'){
                                return '<span style="background-color:#05CD69;color:#fff;padding:2px 5px;">已结算</span>';
                            }else if(value == 'UNSETTLED'){
                                return '<span style="background-color:#FFCC01;color:#fff;padding:2px 5px;">未结算</span>';
                            }
                        }
                    },
                    {field: 'unsettled_reward', title: '申请结算金额(元)', width: 120, halign: 'center', align: 'right'},
                    {field: 'apply_letIds', title: '申请结算合同', width: 300, halign: 'center'},
                    {field: 'real_settle_money', title: '实际结算金额(元)', width: 120, halign: 'center', align: 'right'},
                    {field: 'real_settle_letIds', title: '实际结算合同', width: 300, halign: 'center'}
                ]],
                onLoadSuccess: function (data) {
                    //单元格内容悬浮提示，doCellTip()是在入口文件index.php中拓展的。
                    $(this).datagrid('doCellTip', {
                        position: 'bottom',
                        maxWidth: '400px',
                        onlyShowInterrupt: true, //false时所有单元格都显示提示；true时配合specialShowFields自定义要提示的列
                        specialShowFields: [     //需要提示的列
                            {field: 'apply_letIds', showField: 'real_settle_letIds'}
                        ],
                        tipStyler: {
                            backgroundColor: '#E4F0FC',
                            borderColor: '#87A9D0',
                            boxShadow: '1px 1px 3px #292929'
                        }
                    });
                }
            });
            //--初始化【查看朋友租车详情】窗口
            $('#promotionApplyAuditIndex_scanFriendLetDetailsWin').window({
                title: '查看朋友租车详情',
                width: 800,
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
            //--初始化【结算】窗口
            $('#promotionApplyAuditIndex_settleWin').dialog({
                title: '结算奖金',
                width: 800,
                height: 350,
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
                        var form = $('#promotionApplyAuditIndex_settleWin_form');
                        if($('[name="settled_money"]',form).val() <= 0){
                            $.messager.show({
                                title: '已结清',
                                msg: '待结算金额为零，无法再结算！'
                            });
                           return false;
                        }
                        if(!$('[name="isVerified"]',form).is(':checked')){
                            $.messager.show({
                                title: '请先核实',
                                msg: '请先确认是否已核实并已完成转账！'
                            });
                           return false;
                        }
                        $.ajax({
                            type: 'post',
                            url: promotionApplyAuditIndex.URL.settle,
                            data: form.serialize(),
                            dataType: 'json',
                            success: function (data) {
                                if (data.status) {
                                    $.messager.show({
                                        title: '操作成功',
                                        msg: data.info
                                    });
                                    $('#promotionApplyAuditIndex_settleWin').dialog('close');
                                    $('#promotionApplyAuditIndex_datagrid').datagrid('reload');
                                } else {
                                    $.messager.show({
                                        title: '操作失败',
                                        msg: data.info
                                    });
                                }
                            }
                        });
                    }
                }, {
                    text: '取消',
                    iconCls: 'icon-cancel',
                    handler: function () {
                        $('#promotionApplyAuditIndex_settleWin').dialog('close');
                    }
                }]
            });
        },
        // 获取当前所选择的记录
        getCurrentSelected: function(multiline){
            var datagrid = $('#promotionApplyAuditIndex_datagrid');
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
        // 查看朋友租车详情
        scanFriendLetDetails: function(){
            var apply_id = (this.getCurrentSelected()).apply_id; //申请人id
            if(!apply_id) return false;
            $('#promotionApplyAuditIndex_scanFriendLetDetailsWin')
                .dialog('open')
                .dialog('refresh',promotionApplyAuditIndex.URL.scanFriendLetDetails + '&apply_id=' + apply_id);
        },
        //结算
        settle: function(){
            var selectedRow = this.getCurrentSelected();
            if(selectedRow.settle_status == 'SETTLED'){
                $.messager.show({
                    title: '已结算',
                    msg: '该记录已经结算过了！'
                });
                return false;
            }
            var id = selectedRow.id;
            if(!id) return false;
            $('#promotionApplyAuditIndex_settleWin')
                .dialog('open')
                .dialog('refresh',promotionApplyAuditIndex.URL.settle + '&id=' + id);
        },
        //查询
        search: function(){
            var form = $('#promotionApplyAuditIndex_searchFrom');
            var data = {};
            var searchCondition = form.serializeArray();
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = searchCondition[i]['value'];
            }
            $('#promotionApplyAuditIndex_datagrid').datagrid('load',data);
        },
        //重置
        reset: function(){
            $('#promotionApplyAuditIndex_searchFrom').form('reset');
            promotionApplyAuditIndex.search();
        },
        //导出Excel
        exportGridData: function(){
            var form = $('#promotionApplyAuditIndex_searchFrom');
            var searchConditionStr = form.serialize();
            window.open(promotionApplyAuditIndex.URL.exportGridData + '&' + searchConditionStr);
        }
    }

    // 执行初始化函数
    promotionApplyAuditIndex.init();

</script>