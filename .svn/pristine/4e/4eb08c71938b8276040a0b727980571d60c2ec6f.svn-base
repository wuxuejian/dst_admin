<table id="promotionUnsettledManageIndex_datagrid"></table>
<div id="promotionUnsettledManageIndex_datagridToolbar">
    <div class="easyui-panel" title="检索区域" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="promotionUnsettledManageIndex_searchFrom">
                <ul class="search-main">
                    <li>
                        <div class="item-name">邀请人</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="inviter" style="width:100%;"
                                data-options="
                                    onChange:function(){
                                        promotionUnsettledManageIndex.search();
                                    }
                                "
                            />
                        </div>
                    </li>                    
					<li>
                        <div class="item-name">手机号</div>
                        <div class="item-input">
                           <input class="easyui-textbox" type="text" name="inviter_mobile" style="width:100%;"
                                  data-options="
                                        onChange:function(){
                                            promotionUnsettledManageIndex.search();
                                        }
                                  "
                           />
                        </div>
                    </li>
					<li>
                        <div class="item-name">邀请码</div>
                        <div class="item-input">
                           <input class="easyui-textbox" type="text" name="inviter_invite_code" style="width:100%;"
                                  data-options="
                                        onChange:function(){
                                            promotionUnsettledManageIndex.search();
                                        }
                                  "
                           />
                        </div>
                    </li>
                    <li class="search-button">
                        <a href="javascript:void(0)" onclick="promotionUnsettledManageIndex.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                        <a href="javascript:void(0)" onclick="promotionUnsettledManageIndex.reset()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</a>
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
<div id="promotionUnsettledManageIndex_scanFriendLetDetailsWin"></div>
<div id="promotionUnsettledManageIndex_settleWin"></div>
<!-- 窗口 end-->

<script>
	var promotionUnsettledManageIndex = {
        //请求的URL
        'URL': {
            'getList': '<?php echo yii::$app->urlManager->createUrl(['promotion/unsettled-manage/get-list']); ?>',
            'scanFriendLetDetails': '<?php echo yii::$app->urlManager->createUrl(['promotion/unsettled-manage/scan-friend-let-details']); ?>',
            'settle': '<?php echo yii::$app->urlManager->createUrl(['promotion/unsettled-manage/settle']); ?>',
            'exportGridData': '<?php echo yii::$app->urlManager->createUrl(['promotion/unsettled-manage/export-grid-data']); ?>'
        },
        //初始化
        init: function() {
            //列表数据
            $('#promotionUnsettledManageIndex_datagrid').datagrid({
                method: 'get',
                url: promotionUnsettledManageIndex.URL.getList,
                fit: true,
                border: false,
                toolbar: "#promotionUnsettledManageIndex_datagridToolbar",
                pagination: true,
                loadMsg: '数据加载中...',
                striped: true,
                checkOnSelect: true,
                rownumbers: true,
                singleSelect: true,
                pageSize: 20,
                frozenColumns: [[
                    {field: 'ck', checkbox: true},
                    {field: 'inviter_id', title: 'ID', width: 40, align: 'center', hidden: true},
                    {field: 'inviter', title: '邀请人', width: 90, align: 'center', sortable: true}
                ]],
                columns: [[
                    {field: 'inviter_mobile', title: '手机号', width: 90, align: 'center', sortable: true},
                    {field: 'inviter_invite_code', title: '邀请码', width: 80, align: 'center', sortable: true},
                    {field: 'total_invite_num', title: '邀请注册总数(人)', width: 120, align: 'center'},
                    {field: 'total_rent_num', title: '朋友租车总数(部)', width: 120, align: 'center', sortable: true},
                    {field: 'total_reward', title: '奖金总额(元)', width: 120, halign: 'center', align: 'right'},
                    {field: 'total_reward_settled', title: '已结算(元)', width: 120, halign: 'center', align: 'right'},
                    {field: 'total_reward_unsettled', title: '待结算(元)', width: 120, halign: 'center', align: 'right'}
                ]]
            });
            //--初始化【查看朋友租车详情】窗口
            $('#promotionUnsettledManageIndex_scanFriendLetDetailsWin').window({
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
            $('#promotionUnsettledManageIndex_settleWin').dialog({
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
                        var form = $('#promotionUnsettledManageIndex_settleWin_form');
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
                            url: promotionUnsettledManageIndex.URL.settle,
                            data: form.serialize(),
                            dataType: 'json',
                            success: function (data) {
                                if (data.status) {
                                    $.messager.show({
                                        title: '操作成功',
                                        msg: data.info
                                    });
                                    $('#promotionUnsettledManageIndex_settleWin').dialog('close');
                                    $('#promotionUnsettledManageIndex_datagrid').datagrid('reload');
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
                        $('#promotionUnsettledManageIndex_settleWin').dialog('close');
                    }
                }]
            });
        },
        // 获取当前所选择的记录
        getCurrentSelected: function(multiline){
            var datagrid = $('#promotionUnsettledManageIndex_datagrid');
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
            var inviter_id = (this.getCurrentSelected()).inviter_id;
            if(!inviter_id) return false;
            $('#promotionUnsettledManageIndex_scanFriendLetDetailsWin')
                .dialog('open')
                .dialog('refresh',promotionUnsettledManageIndex.URL.scanFriendLetDetails + '&inviter_id=' + inviter_id);
        },
        //结算
        settle: function(){
            var inviter_id = (this.getCurrentSelected()).inviter_id;
            if(!inviter_id) return false;
            $('#promotionUnsettledManageIndex_settleWin')
                .dialog('open')
                .dialog('refresh',promotionUnsettledManageIndex.URL.settle + '&inviter_id=' + inviter_id);
        },
        //查询
        search: function(){
            var form = $('#promotionUnsettledManageIndex_searchFrom');
            var data = {};
            var searchCondition = form.serializeArray();
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = searchCondition[i]['value'];
            }
            $('#promotionUnsettledManageIndex_datagrid').datagrid('load',data);
        },
        //重置
        reset: function(){
            $('#promotionUnsettledManageIndex_searchFrom').form('reset');
            promotionUnsettledManageIndex.search();
        },
        //导出Excel
        exportGridData: function(){
            var form = $('#promotionUnsettledManageIndex_searchFrom');
            var searchConditionStr = form.serialize();
            window.open(promotionUnsettledManageIndex.URL.exportGridData + '&' + searchConditionStr);
        }
    }

    // 执行初始化函数
    promotionUnsettledManageIndex.init();

</script>