<table id="easyui-datagrid-carmonitor-alert-index"></table> 
<div id="easyui-datagrid-carmonitor-alert-index-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-carmonitor-alert-index">
                <ul class="search-main">
                    <li>
                        <div class="item-name">车牌号</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="plate_number" style="width:100%;"  />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">车架号（vin）</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="car_vin" style="width:100%;"  />
                        </div>
                    </li>
                    <li class="search-button">
                        <a href="javascript:CarmonitorAlertIndex.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                        <a href="javascript:CarmonitorAlertIndex.reset()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</a>
                    </li>
                </ul>
            </form>
        </div>
    </div>

    <?php if(!empty($buttons)){ ?>
        <div class="easyui-panel" title="数据列表" style="padding:3px 2px;width:100%;" data-options="
        iconCls: 'icon-table-list',
        border: false
    ">
            <?php foreach($buttons as $val){ ?>
                <a href="javascript:<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon']; ?>'"><?= $val['text']; ?></a>
            <?php } ?>
        </div>
    <?php } ?>

</div>
<script>
    var CarmonitorAlertIndex = new Object();
    CarmonitorAlertIndex.timer = 0;
    CarmonitorAlertIndex.init = function(){
        //获取列表数据
        $('#easyui-datagrid-carmonitor-alert-index').datagrid({  
            method: 'get', 
            url:"<?php echo yii::$app->urlManager->createUrl(['carmonitor/alert/get-list']); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-carmonitor-alert-index-toolbar",
            pagination: true,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: false,
            frozenColumns: [[
                {field: 'ck',checkbox: true}, 
                {field: 'id',title: 'id',hidden: true},   
                {field: 'plate_number',title: '车牌号',width: 70,align: 'center',sortable: true},
            ]],
            columns:[[
                {field: 'car_vin',title: '车架号',width: 120,align: 'center',sortable: true},
                {field: 'data_source',title: '数据来源',width: 80,align: 'center',sortable: true},
                {
                    field: 'collection_datetime',title: '数据采集时间',width: 130,align: 'center',
                    sortable: true,
                    formatter: function(value){
                        return formatDateToString(value,true);
                    }
                },
                {
                    field: 'update_datetime',title: '记录更新时间',width: 130,align: 'center',
                    sortable: true,
                    formatter: function(value){
                        return formatDateToString(value,true);
                    }
                },
                {field: 'alert_type',title: '报警类型',width: 80,align: 'center',sortable: true},
                {field: 'ecu_module',title: '报警模块编码',width: 100,align: 'center',sortable: true},
                {field: 'content',title: '报警内容',width: 300,align: 'center',sortable: true},
                {field: 'level',title: '报警级别',width: 80,align: 'center',sortable: true}
            ]]
        });
    }
    CarmonitorAlertIndex.init();
    //获取选择的记录
    //参数all = true标示是否要返回所有被选择的记录
    CarmonitorAlertIndex.getSelected = function(all){
        var datagrid = $('#easyui-datagrid-carmonitor-alert-index');
        if(all){
            var selectRows = datagrid.datagrid('getSelections');
            if(selectRows.length <= 0){
                $.messager.alert('错误','请选择要操作的记录','error');   
                return false;
            }
            return selectRows;
        }else{
            var selectRow = datagrid.datagrid('getSelected');
            if(!selectRow){
                $.messager.alert('错误','请选择要操作的记录','error');   
                return false;
            }
            return selectRow;
        }
        
    }
    //查询
    CarmonitorAlertIndex.search = function(){
        var form = $('#search-form-carmonitor-alert-index');
        var data = {};
        var searchCondition = form.serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        $('#easyui-datagrid-carmonitor-alert-index').datagrid('load',data);
    }
    //重置
    CarmonitorAlertIndex.reset = function(){
        $('#search-form-carmonitor-alert-index').form('reset');
    }
</script>