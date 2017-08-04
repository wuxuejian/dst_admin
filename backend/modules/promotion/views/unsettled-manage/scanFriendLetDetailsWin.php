<table id="promotionUnsettledManageIndex_scanFriendLetDetailsWin_datagrid"></table>
<div id="promotionUnsettledManageIndex_scanFriendLetDetailsWin_datagridToolbar">
    <div class="easyui-panel" title="检索区域" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">
        <div class="data-search-form">
            <form id="promotionUnsettledManageIndex_scanFriendLetDetailsWin_searchFrom">
                <ul class="search-main">
                    <li>
                        <div class="item-name">租车人</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="renter" style="width:100%;"
                                   data-options="
                                    onChange:function(){
                                        promotionUnsettledManageIndex_scanFriendLetDetailsWin.search();
                                    }
                                "
                                />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">手机号</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="renter_mobile" style="width:100%;"
                                   data-options="
                                        onChange:function(){
                                            promotionUnsettledManageIndex_scanFriendLetDetailsWin.search();
                                        }
                                  "
                                />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">合同编号</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="contract_no" style="width:100%;"
                                   data-options="
                                        onChange:function(){
                                            promotionUnsettledManageIndex_scanFriendLetDetailsWin.search();
                                        }
                                  "
                                />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">租车日期</div>
                        <div class="item-input">
                            <input class="easyui-datebox" type="text" name="create_time_start" style="width:90px;"
                                   data-options="
                                        onChange:function(){
                                            promotionUnsettledManageIndex_scanFriendLetDetailsWin.search();
                                        }
                                   "
                                />
                            -
                            <input class="easyui-datebox" type="text" name="create_time_end" style="width:90px;"
                                   data-options="
                                        onChange:function(){
                                            promotionUnsettledManageIndex_scanFriendLetDetailsWin.search();
                                        }
                                   "
                                />
                        </div>
                    </li>
                    <li class="search-button">
                        <a href="javascript:void(0)" onclick="promotionUnsettledManageIndex_scanFriendLetDetailsWin.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                        <a href="javascript:void(0)" onclick="promotionUnsettledManageIndex_scanFriendLetDetailsWin.reset()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</a>
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

<script>
    var promotionUnsettledManageIndex_scanFriendLetDetailsWin = {
        //请求的URL
        'URL': {
            'getFriendLetList': '<?php echo yii::$app->urlManager->createUrl(['promotion/unsettled-manage/get-friend-let-list','inviter_id'=>$inviter_id]); ?>'
        },
        //初始化
        init: function() {
            //列表数据
            $('#promotionUnsettledManageIndex_scanFriendLetDetailsWin_datagrid').datagrid({
                method: 'get',
                url: promotionUnsettledManageIndex_scanFriendLetDetailsWin.URL.getFriendLetList,
                fit: true,
                border: false,
                toolbar: "#promotionUnsettledManageIndex_scanFriendLetDetailsWin_datagridToolbar",
                pagination: true,
                loadMsg: '数据加载中...',
                striped: true,
                checkOnSelect: true,
                rownumbers: true,
                singleSelect: true,
                pageSize: 20,
                frozenColumns: [[
                    {field: 'ck', checkbox: true},
                    {field: 'id', title: '租车记录ID', width: 40, align: 'center', hidden: true},
                    {field: 'renter', title: '租车人', width: 90, align: 'center', sortable: true}
                ]],
                columns: [[
                    {field: 'renter_mobile', title: '手机号', width: 100, align: 'center', sortable: true},
                    {field: 'amount', title: '租车数量', width: 90, align: 'center', sortable: true},
                    {field: 'contract_no', title: '合同编号', width: 140, halign: 'center', sortable: true},
                    {field: 'create_time', title: '租车时间', width: 130, align: 'center', sortable: true},
                    {field: 'is_settle', title: '奖金结算', width: 90, align: 'center', sortable: true,
                        formatter: function(value){
                            if(value == 'YES'){
                                return '已结算';
                            }else if(value == 'NO'){
                                return '未结算';
                            }
                        }
                    }
                ]]
            });
        },
        // 获取当前所选择的记录
        getCurrentSelected: function(multiline){
            var datagrid = $('#promotionUnsettledManageIndex_scanFriendLetDetailsWin_datagrid');
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
        //查询
        search: function(){
            var searchForm = $('#promotionUnsettledManageIndex_scanFriendLetDetailsWin_searchFrom');
            var data = {};
            var searchCondition = searchForm.serializeArray();
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = searchCondition[i]['value'];
            }
            $('#promotionUnsettledManageIndex_scanFriendLetDetailsWin_datagrid').datagrid('load',data);
        },
        //重置
        reset: function(){
            $('#promotionUnsettledManageIndex_scanFriendLetDetailsWin_searchFrom').form('reset');
            promotionUnsettledManageIndex_scanFriendLetDetailsWin.search();
        }
    }

    // 执行初始化函数
    promotionUnsettledManageIndex_scanFriendLetDetailsWin.init();

</script>