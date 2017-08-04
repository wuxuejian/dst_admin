<table id="promotionSignInfoIndex_datagrid"></table>
<div id="promotionSignInfoIndex_datagridToolbar">
    <div class="easyui-panel" title="检索区域" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="promotionSignInfoIndex_searchFrom">
                <ul class="search-main">
                    <li>
                        <div class="item-name">姓名</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="client" style="width:100%;"
                                data-options="
                                    onChange:function(){
                                        promotionSignInfoIndex.search();
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
                                            promotionSignInfoIndex.search();
                                        }
                                  "
                           />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">锁定状态</div>
                        <div class="item-input">
                            <select class="easyui-combobox" name="is_lock"  style="width:100%;"
                                    data-options="
                                        panelHeight:'auto',
                                        editable:false,
                                        onChange:function(){
                                            promotionSignInfoIndex.search();
                                        }
                                    "
                                >
                                <option value="" selected="selected">不限</option>
                                <option value="1">锁定</option>
                                <option value="0">正常</option>
                            </select>
                        </div>
                    </li>
					<li>
                        <div class="item-name">区域</div>
                        <div class="item-input">
                           <input class="easyui-textbox" type="text" name="district" style="width:100%;"
                                  data-options="
                                        onChange:function(){
                                            promotionSignInfoIndex.search();
                                        }
                                  "
                           />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">专属邀请码</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="invite_code_mine" style="width:100%;"
                                   data-options="
                                        onChange:function(){
                                            promotionSignInfoIndex.search();
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
                                            promotionSignInfoIndex.search();
                                        }
                                   "
                                />
                            -
                            <input class="easyui-datebox" type="text" name="systime_end" style="width:90px;"
                                   data-options="
                                        onChange:function(){
                                            promotionSignInfoIndex.search();
                                        }
                                   "
                                />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">使用邀请码</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="invite_code_used" style="width:100%;"
                                   data-options="
                                        onChange:function(){
                                            promotionSignInfoIndex.search();
                                        }
                                  "
                                />
                        </div>
                    </li>
                    <li class="search-button">
                        <a href="javascript:void(0)" onclick="promotionSignInfoIndex.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                        <a href="javascript:void(0)" onclick="promotionSignInfoIndex.reset()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</a>
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
	var promotionSignInfoIndex = {
        //请求的URL
        'URL': {
            'getList': '<?php echo yii::$app->urlManager->createUrl(['promotion/sign-info/get-list']); ?>',
            'lockOn': '<?php echo yii::$app->urlManager->createUrl(['promotion/sign-info/lock-on']); ?>',
            'lockOff': '<?php echo yii::$app->urlManager->createUrl(['promotion/sign-info/lock-off']); ?>',
            'exportGridData': '<?php echo yii::$app->urlManager->createUrl(['promotion/sign-info/export-grid-data']); ?>'
        },
        //初始化
        init: function() {
            //列表数据
            $('#promotionSignInfoIndex_datagrid').datagrid({
                method: 'get',
                url: promotionSignInfoIndex.URL.getList,
                fit: true,
                border: false,
                toolbar: "#promotionSignInfoIndex_datagridToolbar",
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
                    {field: 'client', title: '姓名', width: 90, align: 'center', sortable: true}
                ]],
                columns: [[
                    {field: 'sex', title: '性别', width: 50, align: 'center', sortable: true,
                        formatter: function (value, row, index) {
                            return parseInt(value) == 1 ? '男' : '女';
                        }
                    },
                    {field: 'mobile', title: '手机号', width: 90, align: 'center', sortable: true},
                    {field: 'is_lock', title: '锁定状态', width: 70, align: 'center', sortable: true,
                        formatter: function (value, row, index) {
                            if(parseInt(value)){
                                return '<span style="background-color:#F31F28;color:#fff;padding:2px 5px;">锁定</span>';
                            }else{
                                return '<span style="background-color:#05CD69;color:#fff;padding:2px 5px;">正常</span>';
                            }
                        }
                    },
                    {field: 'company', title: '公司', width: 210, halign: 'center', sortable: true},
                    {field: 'profession', title: '职业', width: 100, align: 'center', sortable: true},
                    {field: 'district', title: '区域', width: 90, align: 'center', sortable: true},
                    {field: 'invite_code_mine', title: '专属邀请码', width: 90, align: 'center', sortable: true},
                    {field: 'systime', title: '注册日期', align: 'center', width: 100, sortable: true,
                        formatter: function (value, row, index) {
                            return formatDateToString(value);
                        }
                    },
                    {field: 'invite_code_used', title: '使用邀请码', width: 90, align: 'center', sortable: true}
                ]]
            });
        },
        // 获取当前所选择的记录
        getCurrentSelected: function(multiline){
            var datagrid = $('#promotionSignInfoIndex_datagrid');
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
        //锁定
        lockOn:function(){
            var selectRow = this.getCurrentSelected();
            if(!selectRow) return false;
            if(parseInt(selectRow.is_lock)){
                $.messager.show({
                    title: '无需锁定',
                    msg: '只有处于“非锁定”状态的用户才能被锁定！'
                });
                return false;
            }
            var id = selectRow.id;
            $.messager.confirm('确认锁定','您确定要锁定所选行吗？',function(r){
                if(r){
                    $.ajax({
                        type: 'get',
                        url: promotionSignInfoIndex.URL.lockOn,
                        data: {id: id},
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.show({
                                    title: '操作成功',
                                    msg: data.info
                                });
                                $('#promotionSignInfoIndex_datagrid').datagrid('reload');
                            }else{
                                $.messager.alert('操作失败',data.info,'error');
                            }
                        }
                    });
                }
            });
        },
        //解锁
        lockOff:function(){
            var selectRow = this.getCurrentSelected();
            if(!selectRow) return false;
            if(!parseInt(selectRow.is_lock)){
                $.messager.show({
                    title: '无需解锁',
                    msg: '只有处于【锁定】状态的用户才能被解锁！'
                });
                return false;
            }
            var id = selectRow.id;
            $.messager.confirm('确认解锁','您确定要解锁所选行吗？',function(r){
                if(r){
                    $.ajax({
                        type: 'get',
                        url: promotionSignInfoIndex.URL.lockOff,
                        data: {id: id},
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.show({
                                    title: '操作成功',
                                    msg: data.info
                                });
                                $('#promotionSignInfoIndex_datagrid').datagrid('reload');
                            }else{
                                $.messager.alert('操作失败',data.info,'error');
                            }
                        }
                    });
                }
            });
        },
        //查询
        search: function(){
            var form = $('#promotionSignInfoIndex_searchFrom');
            var data = {};
            var searchCondition = form.serializeArray();
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = searchCondition[i]['value'];
            }
            $('#promotionSignInfoIndex_datagrid').datagrid('load',data);
        },
        //重置
        reset: function(){
            $('#promotionSignInfoIndex_searchFrom').form('reset');
            promotionSignInfoIndex.search();
        },
        //导出Excel
        exportGridData: function(){
            var form = $('#promotionSignInfoIndex_searchFrom');
            var searchConditionStr = form.serialize();
            window.open(promotionSignInfoIndex.URL.exportGridData + '&' + searchConditionStr);
        }
    }

    // 执行初始化函数
    promotionSignInfoIndex.init();

</script>