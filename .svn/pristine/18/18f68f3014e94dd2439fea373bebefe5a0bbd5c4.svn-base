<table id="easyui-datagrid-car-traffic-enforcement-record-index"></table> 
<div id="easyui-datagrid-car-traffic-enforcement-record-index-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-car-traffic-enforcement-record-index">
                <ul class="search-main">
                    <li>
                        <div class="item-name">车牌号</div>
                        <div class="item-input">
                            <input name="plate_number" style="width:200px;" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">保险公司</div>
                        <div class="item-input">
                            <input name="insurer_company" style="width:200px;" />
                        </div>
                    </li>
                    <li class="search-button">
                        <button class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button onclick="CarTrafficEnforcementRecordIndex.resetForm();" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
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
    var CarTrafficEnforcementRecordIndex = new Object();
    CarTrafficEnforcementRecordIndex.init = function(){
        var easyuiDatagrid = $('#easyui-datagrid-car-traffic-enforcement-record-index');
        //获取列表数据
        easyuiDatagrid.datagrid({  
            method: 'get', 
            url:"<?php echo yii::$app->urlManager->createUrl(['car/traffic-enforcement-record/get-list']); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-car-traffic-enforcement-record-index-toolbar",
            pagination: true,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
            frozenColumns: [[
                {field: 'ck',checkbox: true}, 
                {field: 'id',title: 'id',hidden: true},   
                {field: 'plate_number',title: '车牌号',width: 80,sortable: true,align: 'center'}
            ]],
            columns:[[
                {
                    field: 'insurer_company',title: '保险公司',width: 200,
                    align: 'left',sortable: true,
                    formatter: function(value){
                        var insurer_company = <?php echo json_encode($config['INSURANCE_COMPANY']); ?>;
                        if(insurer_company[value]){
                            return insurer_company[value].text;
                        }
                    }
                },
                {field: 'money_amount',title: '保险金额',width: 80,align: 'right',sortable: true},
                {
                    field: 'start_date',title: '开始日期',width: 80,align: 'center',
                    sortable: true,
                    formatter: function(value){
                        if(value > 0){
                            return formatDateToString(value);
                        }
                    }
                },
                {
                    field: 'end_date',title: '结束日期',width: 80,align: 'center',
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
            ]],
            onLoadSuccess: function (data){
                $(this).datagrid('doCellTip',{
                    position: 'bottom',
                    maxWidth: '300px',
                    onlyShowInterrupt: true,
                    specialShowFields: [
                        {field: 'action',showField: 'action'}
                    ],
                    tipStyler: {
                        'backgroundColor' : '#E4F0FC',
                        borderColor : '#87A9D0',
                        boxShadow : '1px 1px 3px #292929'
                    }
                });
            }
        });
        //构建查询表单
        var searchForm = $('#search-form-car-traffic-enforcement-record-index');
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
        searchForm.find('input[name=insurer_company]').combobox({
            valueField:'value',
            textField:'text',
            data: <?= json_encode($insurerCompany); ?>,
            editable: false,
            onSelect: function(){
                searchForm.submit();
            }
        });
        //构建查询表单结束
    }
    CarTrafficEnforcementRecordIndex.init();
    //按条件导出
    CarTrafficEnforcementRecordIndex.exportWidthCondition = function(){
        var form = $('#search-form-car-traffic-enforcement-record-index');
        window.open("<?= yii::$app->urlManager->createUrl(['car/traffic-enforcement-record/export-width-condition']); ?>&"+form.serialize());
    }
    //重置查询表单
    CarTrafficEnforcementRecordIndex.resetForm = function(){
        var easyuiForm = $('#search-form-car-traffic-enforcement-record-index');
        easyuiForm.form('reset');
        easyuiForm.submit();
        var data = {};
    }
</script>