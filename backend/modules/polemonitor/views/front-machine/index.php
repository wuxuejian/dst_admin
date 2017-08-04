<table id="easyui-datagrid-polemonitor-front-machine-index"></table> 
<div id="easyui-datagrid-polemonitor-front-machine-index-toolbar">
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
                            <input class="easyui-textbox" type="text" name="plate_number" style="width:150px;"></input>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">车架号（vin）</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="car_vin" style="width:150px;"></input>
                        </div>
                    </li>
                    <li class="search-button">
                        <a id="btn" href="javascript:PolemonitorFrontMachineIndex.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                    </li>
                </ul>
            </form>
        </div>
    </div>
    <?php if($buttons){ ?>
    <div class="easyui-panel" title="数据列表" style="padding:8px 4px;" data-options="
        iconCls: 'icon-tip',
        border: false
    ">
        <?php foreach($buttons as $val){ ?>
        <a href="javascript:<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon']; ?>'"><?= $val['text']; ?></a>
        <?php } ?>
    </div>
    <?php } ?>
</div>
<script>
    var PolemonitorFrontMachineIndex = new Object();
    PolemonitorFrontMachineIndex.timer = 0;
    PolemonitorFrontMachineIndex.init = function(){
        //获取列表数据
        $('#easyui-datagrid-polemonitor-front-machine-index').datagrid({  
            method: 'get', 
            url:"<?php echo yii::$app->urlManager->createUrl(['polemonitor/front-machine/get-list']); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-polemonitor-front-machine-index-toolbar",
            pagination: true,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: false,
            frozenColumns: [[
                {field: 'ck',checkbox: true}, 
                {field: 'id',title: 'id',hidden: true},   
                {field: 'plate_number',title: '车牌号',width: 100,sortable: true},   
            ]],
            columns:[[
                {field: 'car_vin',title: '车架号',width: 200,align: 'left',sortable: true},
                {field: 'data_source',title: '数据来源',width: 100,align: 'left',sortable: true},
                {
                    field: 'collection_datetime',title: '数据采集时间',width: 120,align: 'center',
                    sortable: true,
                    formatter: function(value){
                        return formatDateToString(value,true);
                    }
                },
                {
                    field: 'update_datetime',title: '记录更新时间',width: 120,align: 'left',
                    sortable: true,
                    formatter: function(value){
                        return formatDateToString(value,true);
                    }
                },
                {field: 'alert_type',title: '报警类型',width: 120,align: 'left',sortable: true},
                {field: 'ecu_module',title: '报警模块编码',width: 100,align: 'left',sortable: true,},
                {field: 'content',title: '报警内容',width: 300,align: 'left',sortable: true},
                {field: 'level',title: '报警级别',width: 80,align: 'left',sortable: true}
            ]]
        });
    }
    PolemonitorFrontMachineIndex.init();
    //获取选择的记录
    //参数all = true标示是否要返回所有被选择的记录
    PolemonitorFrontMachineIndex.getSelected = function(all){
        var datagrid = $('#easyui-datagrid-polemonitor-front-machine-index');
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
    PolemonitorFrontMachineIndex.search = function(){
        var form = $('#search-form-carmonitor-alert-index');
        var data = {};
        var searchCondition = form.serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        $('#easyui-datagrid-polemonitor-front-machine-index').datagrid('load',data);
    }
</script>