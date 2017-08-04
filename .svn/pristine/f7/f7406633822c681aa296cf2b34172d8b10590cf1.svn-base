<table id="promotionInviteInfoIndex_datagrid"></table>
<div id="promotionInviteInfoIndex_datagridToolbar">
    <div class="easyui-panel" title="检索区域" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="promotionInviteInfoIndex_searchFrom">
                <ul class="search-main">
                    <li>
                        <div class="item-name">邀请发起人</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="sponsor" style="width:100%;"
                                data-options="
                                    onChange:function(){
                                        promotionInviteInfoIndex.search();
                                    }
                                "
                            />
                        </div>
                    </li>                    
					<li>
                        <div class="item-name">受邀人</div>
                        <div class="item-input">
                           <input class="easyui-textbox" type="text" name="receiver" style="width:100%;"
                                  data-options="
                                        onChange:function(){
                                            promotionInviteInfoIndex.search();
                                        }
                                  "
                           />
                        </div>
                    </li>                    
					<li>
                        <div class="item-name">手机号</div>
                        <div class="item-input">
                           <input class="easyui-textbox" type="text" name="mobile" style="width:100%;"
                                  data-options="
                                        onChange:function(){
                                            promotionInviteInfoIndex.search();
                                        }
                                  "
                           />
                        </div>
                    </li>                    
                    <li>
                        <div class="item-name">注册日期</div>
                        <div class="item-input">
							<input class="easyui-datebox" type="text" name="systime_start" style="width:90px;"
                                   data-options="
                                        onChange:function(){
                                            promotionInviteInfoIndex.search();
                                        }
                                   "
                            />
                            -
							<input class="easyui-datebox" type="text" name="systime_end" style="width:90px;"
                                   data-options="
                                        onChange:function(){
                                            promotionInviteInfoIndex.search();
                                        }
                                   "
                            />
                        </div>               
                    </li>
                    <li class="search-button">
                        <a href="javascript:void(0)" onclick="promotionInviteInfoIndex.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                        <a href="javascript:void(0)" onclick="promotionInviteInfoIndex.reset()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</a>
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
	var promotionInviteInfoIndex = {
        //请求的URL
        'URL': {
            'getList': '<?php echo yii::$app->urlManager->createUrl(['promotion/invite-info/get-list']); ?>',
            'exportGridData': '<?php echo yii::$app->urlManager->createUrl(['promotion/invite-info/export-grid-data']); ?>'
        },
        //初始化
        init: function() {
            //列表数据
            $('#promotionInviteInfoIndex_datagrid').datagrid({
                method: 'get',
                url: promotionInviteInfoIndex.URL.getList,
                fit: true,
                border: false,
                toolbar: "#promotionInviteInfoIndex_datagridToolbar",
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
                    {field: 'sponsor', title: '邀请发起人', width: 100, align: 'center', sortable: true},
                    {field: 'sponsor_mobile', title: '发起人手机号', width: 100, align: 'center', sortable: true},
                ]],
                columns: [[
                    {field: 'receiver', title: '受邀人', width: 100, align: 'center', sortable: true},
                    {field: 'receiver_mobile', title: '受邀人手机号', width: 100, align: 'center', sortable: true},
                    {field: 'systime', title: '受邀人注册日期', align: 'center', width: 130, sortable: true,
                        formatter: function (value, row, index) {
                            return formatDateToString(value);
                        }
                    }
                ]]
            });
        },
        //查询
        search: function(){
            var form = $('#promotionInviteInfoIndex_searchFrom');
            var data = {};
            var searchCondition = form.serializeArray();
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = searchCondition[i]['value'];
            }
            $('#promotionInviteInfoIndex_datagrid').datagrid('load',data);
        },
        //重置
        reset: function(){
            $('#promotionInviteInfoIndex_searchFrom').form('reset');
            promotionInviteInfoIndex.search();
        },
        //导出Excel
        exportGridData: function(){
            var form = $('#promotionInviteInfoIndex_searchFrom');
            var searchConditionStr = form.serialize();
            window.open(promotionInviteInfoIndex.URL.exportGridData + '&' + searchConditionStr);
        }
    }

    // 执行初始化函数
    promotionInviteInfoIndex.init();

</script>