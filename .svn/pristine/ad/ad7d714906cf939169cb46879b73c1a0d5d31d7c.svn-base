<table id="promotionLetInfoIndex_datagrid"></table>
<div id="promotionLetInfoIndex_datagridToolbar">
    <div class="easyui-panel" title="检索区域" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="promotionLetInfoIndex_searchFrom">
                <ul class="search-main">
                    <li>
                        <div class="item-name">租车人</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="renter" style="width:100%;"
                                data-options="
                                    onChange:function(){
                                        promotionLetInfoIndex.search();
                                    }
                                "
                            />
                        </div>
                    </li>                    
					<li>
                        <div class="item-name">租车人手机号</div>
                        <div class="item-input">
                           <input class="easyui-textbox" type="text" name="renter_mobile" style="width:100%;"
                                  data-options="
                                        onChange:function(){
                                            promotionLetInfoIndex.search();
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
                                            promotionLetInfoIndex.search();
                                        }
                                  "
                           />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">签订日期</div>
                        <div class="item-input">
                            <input class="easyui-datebox" type="text" name="sign_date_start" style="width:90px;"
                                   data-options="
                                        onChange:function(){
                                            promotionLetInfoIndex.search();
                                        }
                                   "
                                />
                            -
                            <input class="easyui-datebox" type="text" name="sign_date_end" style="width:90px;"
                                   data-options="
                                        onChange:function(){
                                            promotionLetInfoIndex.search();
                                        }
                                   "
                                />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">邀请人</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="inviter" style="width:100%;"
                                   data-options="
                                    onChange:function(){
                                        promotionLetInfoIndex.search();
                                    }
                                "
                                />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">邀请人手机号</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="inviter_mobile" style="width:100%;"
                                   data-options="
                                        onChange:function(){
                                            promotionLetInfoIndex.search();
                                        }
                                  "
                                />
                        </div>
                    </li>
                    <li class="search-button">
                        <a href="javascript:void(0)" onclick="promotionLetInfoIndex.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                        <a href="javascript:void(0)" onclick="promotionLetInfoIndex.reset()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</a>
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
<div id="promotionLetInfoIndex_addWin"></div>
<div id="promotionLetInfoIndex_editWin"></div>
<div id="promotionLetInfoIndex_scanLetDetailsWin"></div>
<!-- 窗口 end-->

<script>
	var promotionLetInfoIndex = {
        //请求的URL
        'URL': {
            'getList': '<?php echo yii::$app->urlManager->createUrl(['promotion/let-info/get-list']); ?>',
            'add': '<?php echo yii::$app->urlManager->createUrl(['promotion/let-info/add']); ?>',
            'edit': '<?php echo yii::$app->urlManager->createUrl(['promotion/let-info/edit']); ?>',
            'scanLetDetails': '<?php echo yii::$app->urlManager->createUrl(['promotion/let-info/scan-let-details']); ?>',
            'exportGridData': '<?php echo yii::$app->urlManager->createUrl(['promotion/let-info/export-grid-data']); ?>',
            'sendLetNoticeToInviter': '<?php echo yii::$app->urlManager->createUrl(['promotion/ali-shotmessage/send-let-notice-to-inviter']); ?>'
        },
        //初始化
        init: function() {
            //列表数据
            $('#promotionLetInfoIndex_datagrid').datagrid({
                method: 'get',
                url: promotionLetInfoIndex.URL.getList,
                fit: true,
                border: false,
                toolbar: "#promotionLetInfoIndex_datagridToolbar",
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
                    {field: 'renter', title: '租车人', width: 70, align: 'center', sortable: true}
                ]],
                columns: [[
                    {field: 'renter_mobile', title: '手机号', width: 80, align: 'center', sortable: true},
                    {field: 'amount', title: '租车数量', width: 70, align: 'center', sortable: true},
                    {field: 'contract_no', title: '合同编号', width: 120, halign: 'center', sortable: true},
                    {field: 'sign_date', title: '合同签订日期', width: 90, align: 'center', sortable: true},
                    {field: 'operator', title: '合同受理人', width: 80, align: 'center', sortable: true},
//                    {field: 'renter_sign_date', title: '注册日期', align: 'center', width: 90, sortable: true,
//                        formatter: function (value, row, index) {
//                            return formatDateToString(value);
//                        }
//                    },
                    {field: 'create_time', title: '租车时间', align: 'center', width: 130, sortable: true},
                    {field: 'inviter', title: '邀请人', width: 80, align: 'center', sortable: true},
                    {field: 'inviter_mobile', title: '邀请人手机号', width: 90, align: 'center', sortable: true},
                    {field: 'mark', title: '备注', width: 200, halign: 'center'}
                ]]
            });
            //--初始化【新增】窗口
            $('#promotionLetInfoIndex_addWin').dialog({
                title: '新增租车信息',
                width: 700,
                height: 300,
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
                        var form = $('#promotionLetInfoIndex_addWin_form');
                        form.form('submit', {
                            url: promotionLetInfoIndex.URL.add,
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
                                    $('#promotionLetInfoIndex_addWin').dialog('close');
                                    $('#promotionLetInfoIndex_datagrid').datagrid('reload');
                                    //新增后，若有邀请人则需要短信通知邀请人
                                    if(data.sendMsgData){
                                        ajaxSendShortMessage(promotionLetInfoIndex.URL.sendLetNoticeToInviter, data.sendMsgData); //定义在入口文件index.php中
                                    }
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
                        $('#promotionLetInfoIndex_addWin').dialog('close');
                    }
                }]
            });
            //--初始化【修改】窗口
            $('#promotionLetInfoIndex_editWin').dialog({
                title: '修改租车信息',
                width: 700,
                height: 300,
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
                        var form = $('#promotionLetInfoIndex_editWin_form');
                        form.form('submit', {
                            url: promotionLetInfoIndex.URL.edit,
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
                                    $('#promotionLetInfoIndex_editWin').dialog('close');
                                    $('#promotionLetInfoIndex_datagrid').datagrid('reload');
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
                        $('#promotionLetInfoIndex_editWin').dialog('close');
                    }
                }]
            });
            //--初始化【查看租车详情】窗口
            $('#promotionLetInfoIndex_scanLetDetailsWin').window({
                title: '查看租车详情',
                width: 800,
                height: 400,
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
        },
        // 获取当前所选择的记录
        getCurrentSelected: function(multiline){
            var datagrid = $('#promotionLetInfoIndex_datagrid');
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
        // 添加
        add: function(){
            $('#promotionLetInfoIndex_addWin')
                .dialog('open')
                .dialog('refresh',promotionLetInfoIndex.URL.add);
        },
        // 修改
        edit: function(id){
            var id = id || (this.getCurrentSelected()).id;
            if(!id) return false;
            $('#promotionLetInfoIndex_editWin')
                .dialog('open')
                .dialog('refresh', promotionLetInfoIndex.URL.edit + '&id=' + id);
        },
        // 查看租车详情
        scanLetDetails: function(){
            var id = (this.getCurrentSelected()).id;
            if(!id) return false;
            $('#promotionLetInfoIndex_scanLetDetailsWin')
                .dialog('open')
                .dialog('refresh',promotionLetInfoIndex.URL.scanLetDetails + '&id=' + id);
        },
        //查询
        search: function(){
            var form = $('#promotionLetInfoIndex_searchFrom');
            var data = {};
            var searchCondition = form.serializeArray();
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = searchCondition[i]['value'];
            }
            $('#promotionLetInfoIndex_datagrid').datagrid('load',data);
        },
        //重置
        reset: function(){
            $('#promotionLetInfoIndex_searchFrom').form('reset');
            promotionLetInfoIndex.search();
        },
        //导出Excel
        exportGridData: function(){
            var form = $('#promotionLetInfoIndex_searchFrom');
            var searchConditionStr = form.serialize();
            window.open(promotionLetInfoIndex.URL.exportGridData + '&' + searchConditionStr);
        }
    }

    // 执行初始化函数
    promotionLetInfoIndex.init();

</script>