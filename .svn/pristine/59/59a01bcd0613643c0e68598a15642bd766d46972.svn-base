<table id="easyui-datagrid-car-alert-sec-main"></table> 
<div id="easyui-datagrid-car-alert-sec-main-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-car-alert-sec-main">
                <ul class="search-main">
                    <li>
                        <div class="item-name">车牌号</div>
                        <div class="item-input">
                            <input name="plate_number" style="width:200px;" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">维护编号</div>
                        <div class="item-input">
                            <input name="number" style="width:200px;" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">查看类型</div>
                        <div class="item-input">
                            <input name="scan_type" style="width:200px;" />
                        </div>
                    </li>
                    <li class="search-button">
                        <button class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button onclick="CarAlertSecMain.resetForm();return false;" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
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
                <button onclick="<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon'] ;?>'"><?= $val['text'] ;?></button>
            <?php } ?>
        </div>
    <?php } ?>

</div>
<script>
    var CarAlertSecMain = new Object();
    //配置项
    CarAlertSecMain.CONFIG = <?= json_encode($config); ?>;
    CarAlertSecMain.init = function(){
        //获取列表数据
        $('#easyui-datagrid-car-alert-sec-main').datagrid({  
            method: 'get', 
            url:"<?php echo yii::$app->urlManager->createUrl(['car/alert/sm-get-list']); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-car-alert-sec-main-toolbar",
            pagination: true,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
			pageSize: 20,
            sortName: '_next_date',
            sortOrder: 'asc',
            frozenColumns: [[
                {field: 'ck',checkbox: true}, 
                {field: 'id',title: 'id',hidden: true},   
                {field: 'plate_number',title: '车牌号',width: 80,sortable: true,align: 'center'}   
            ]],
            columns:[[
                {field: 'car_status',title: '车辆状态',width: 70,align: 'center',sortable: true,
                    formatter: function(value){
                        try{
                            var str = 'CarAlertSecMain.CONFIG.car_status.' + value + '.text';
                            return eval(str);
                        }catch(e){
                            return value;
                        }
                    }
                },
                {field: 'number',title: '编号',width: 80,align: 'center',sortable: true},
                {
                    field: 'current_date',title: '本次维护时间',width: 110,align: 'center',
                    sortable: true,
                    formatter: function(value){
                        if(!isNaN(value)  && value >0){
                            return formatDateToString(value);
                        }
                    }
                },
                {
                    field: 'next_date',title: '下次维护时间',width: 110,align: 'center',
                    sortable: true,
                    formatter: function(value){
                        if(!isNaN(value)  && value >0){
                            return formatDateToString(value);
                        }
                    }
                },
                {
                    field: '_next_date',title: '倒计时',width: 80,align: 'center',
                    sortable: true,
                    formatter: function(value){
                        if(isNaN(value)){
                            return '';
                        }
                        var timeStamp = Date.parse(new Date()) / 1000;
                        if(timeStamp >=  value){
                            return '过期';
                        }
                        var leftDay = Math.ceil((value - timeStamp) / 86400);
                        if(leftDay <= 30){
                            return '<span style="color:red">'+leftDay+'天</span>';
                        }
                        return leftDay + '天';
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
        var searchForm = $('#search-form-car-alert-sec-main');
        searchForm.submit(function(){
            var data = {};
            var searchCondition = $(this).serializeArray();
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = $.trim(searchCondition[i]['value']);
            }
            $('#easyui-datagrid-car-alert-sec-main').datagrid('load',data);
            return false;
        });
        searchForm.find('input[name=plate_number]').textbox({
            onChange: function(){
                $('#search-form-car-alert-sec-main').submit();
            }
        });
        searchForm.find('input[name=number]').textbox({
            onChange: function(){
                $('#search-form-car-alert-sec-main').submit();
            }
        });
        searchForm.find('input[name=scan_type]').combobox({
            valueField:'value',
            textField:'text',
            data: [{"value": '',"text": '不限'},{"value": 'thirty',"text": '30天内'}],
            editable: false,
            onChange: function(){
                $('#search-form-car-alert-sec-main').submit();
            }
        });
        //构建查询表单结束
    }
    //查询
    CarAlertSecMain.resetForm = function(){
        var easyuiForm = $('#search-form-car-alert-sec-main');
        easyuiForm.form('reset');
        easyuiForm.submit();
    }

    // 导出Excel
    CarAlertSecMain.exportSmList = function(){
        var searchConditionStr = $('#search-form-car-alert-sec-main').serialize();
        window.open("<?php echo yii::$app->urlManager->createUrl(['car/alert/export-sm-list']); ?>" + "&" + searchConditionStr);
    }

    CarAlertSecMain.init();
    
</script>