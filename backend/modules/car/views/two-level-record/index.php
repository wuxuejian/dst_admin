<table id="easyui-datagrid-car-two-level-record-index"></table> 
<div id="easyui-datagrid-car-two-level-record-index-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-car-two-level-record-index">
                <ul class="search-main">
                    <li>
                        <div class="item-name">车牌号</div>
                        <div class="item-input">
                            <input name="plate_number" style="width:200px;" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">维护卡号</div>
                        <div class="item-input">
                            <input name="number" style="width:200px;" />
                        </div>
                    </li>
                    <li class="search-button">
                        <button class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button onclick="CarTwoLevelRecordIndex.resetForm();return false;" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
                    </li>
                </ul>
            </form>
        </div>
    </div>
    <?php if($buttons){ ?>
    <div class="easyui-panel" title="数据列表" data-options="iconCls: 'icon-tip',border: false">
        <div style="padding:8px 4px">
        <?php foreach($buttons as $val){ ?>
            <a href="javascript:<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon']; ?>'"><?= $val['text']; ?></a>
        <?php } ?>
        </div>
    </div>
    <?php } ?>
</div>
<script>
    var CarTwoLevelRecordIndex = new Object();
    CarTwoLevelRecordIndex.init = function(){
        var easyuiDatagrid = $('#easyui-datagrid-car-two-level-record-index');
        //获取列表数据
        easyuiDatagrid.datagrid({  
            method: 'get', 
            url:"<?php echo yii::$app->urlManager->createUrl(['car/two-level-record/get-list']); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-car-two-level-record-index-toolbar",
            pagination: true,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
            frozenColumns: [[
                {field: 'ck',checkbox: true}, 
                {field: 'id',title: 'id',hidden: true},   
                {field: 'plate_number',title: '车牌号',width: 80,sortable: true,align: 'center'},   
            ]],
            columns:[[
                {field: 'number',title: '维护卡号',width: 80,align: 'left',sortable: true},
                {
                    field: 'current_date',title: '本次维护时间',width: 90,align: 'center',
                    sortable: true,
                    formatter: function(value){
                        if(value > 0){
                            return formatDateToString(value);
                        }
                    }
                },
                {
                    field: 'next_date',title: '下次维护时间',width: 90,align: 'center',
                    sortable: true,
                    formatter: function(value){
                        if(value > 0){
                            return formatDateToString(value);
                        }
                    }
                },
                {
                    field: 'add_datetime',title: '上次修改时间',width: 130,align: 'center',
                    sortable: true,
                    formatter: function(value){
                        if(!isNaN(value)  && value >0){
                            return formatDateToString(value,true);
                        }
                    }
                },
                {field: 'username',title: '操作账号',width: 100,sortable: true}
            ]]
        });
        //构建查询表单
        var searchForm = $('#search-form-car-two-level-record-index');
        searchForm.submit(function(){
            var data = {};
            var searchCondition = $(this).serializeArray();
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = searchCondition[i]['value'];
            }
            easyuiDatagrid.datagrid('load',data);
            return false;
        });
        searchForm.find('input[name=plate_number]').textbox({
            onChange: function(){
                searchForm.submit();
            }
        });
        searchForm.find('input[name=number]').textbox({
            onChange: function(){
                searchForm.submit();
            }
        });
        //构建查询表单结束
    }
    CarTwoLevelRecordIndex.init();
    //按条件导出
    CarTwoLevelRecordIndex.exportWidthCondition = function(){
        var form = $('#search-form-car-two-level-record-index');
        window.open("<?= yii::$app->urlManager->createUrl(['car/two-level-record/export-width-condition']); ?>&"+form.serialize());
    }
    //查询
    CarTwoLevelRecordIndex.resetForm = function(){
        var easyuiForm = $('#search-form-car-two-level-record-index');
        easyuiForm.form('reset');
        easyuiForm.submit();
    }
</script>