<table id="easyui-datagrid-car-alert-driv-license"></table> 
<div id="easyui-datagrid-car-alert-driv-license-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-car-alert-driv-license">
                <ul class="search-main">
                    <li>
                        <div class="item-name">车牌号</div>
                        <div class="item-input">
                            <input name="plate_number" style="width:200px;" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">查看类型</div>
                        <div class="item-input">
                            <input name="scan_type" style="width:200px;" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">车辆品牌</div>
                        <div class="item-input">
                            <input style="width:200px;" name="brand_id" />
                        </div>
                    </li>
                    <li class="search-button">
                        <button class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button onclick="CarAlertDrivLicense.resetForm();return false;" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
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
    var CarAlertDrivLicense = new Object();
    //配置项
    CarAlertDrivLicense.CONFIG = <?= json_encode($config); ?>;
    CarAlertDrivLicense.init = function(){
        //获取列表数据
        $('#easyui-datagrid-car-alert-driv-license').datagrid({  
            method: 'get', 
            url:"<?php echo yii::$app->urlManager->createUrl(['car/alert/dl-get-list']); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-car-alert-driv-license-toolbar",
            pagination: true,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
			pageSize: 20,
            sortName: 'next_valid_date',
            sortOrder: 'asc',
            frozenColumns: [[
                {field: 'ck',checkbox: true}, 
                {field: 'id',title: 'id',hidden: true},   
                {field: 'plate_number',title: '车牌号',width: 80,sortable: true,align: 'center'}
            ]],
            columns:[[
				{field: 'car_brand_name',title: '车辆品牌',width: 70,align: 'center'},
				{field: 'car_model_name',title: '车型名称',width: 70,align: 'center'},
                {field: 'car_status',title: '车辆状态',width: 70,align: 'center',sortable: true,
                    formatter: function(value){
                        try{
                            var str = 'CarAlertDrivLicense.CONFIG.car_status.' + value + '.text';
                            return eval(str);
                        }catch(e){
                            return value;
                        }
                    }
                },
                {
                    field: 'addr',title: '登记地址',width: 230,
                    halign: 'center',sortable: true,
                    formatter: function(value){
                        var addr = <?php echo json_encode($config['DL_REG_ADDR']); ?>;
                        if(addr[value]){
                            return addr[value].text;
                        }
                    }
                },
                {
                    field: 'image',title: '附件',width: 44,
                    align: 'center',sortable: true,
                    formatter: function(value){
                        if(value)
                        {
                        	return '<a href="'+value+'" target="_blank"  ><img width="16px" height="16px" src="./jquery-easyui-1.4.3/themes/icons/large_picture.png" /></a>';
                        } 
                    }
                },
                {
                    field: 'register_date',title: '注册日期',width: 80,align: 'center',
                    sortable: true,
                    formatter: function(value){
                        if(!isNaN(value)  && value >0){
                            return formatDateToString(value);
                        }
                    }
                },
                {
                    field: 'issue_date',title: '发证日期',width: 80,align: 'center',
                    sortable: true,
                    formatter: function(value){
                        if(!isNaN(value)  && value >0){
                            return formatDateToString(value);
                        }
                    }
                },
                {field: 'archives_number',title: '档案编号',width: 100,halign: 'center',sortable: true},
                {field: 'total_mass',title: '整备质量(kg)',width: 90,halign: 'center',align: 'right',sortable: true},
                {
                    field: 'force_scrap_date',title: '强制报废日期',width: 100,align: 'center',
                    sortable: true,
                    formatter: function(value){
                        if(!isNaN(value)  && value > 0){
                            return formatDateToString(value);
                        }
                    }
                },
				{
                    field: 'valid_to_date',title: '检验有效期',width: 80,align: 'center',
                    sortable: true,
                    formatter: function(value){
                        if(!isNaN(value)  && value > 0){
                            return formatDateToString(value);
                        }
                    }
                },
                {
                    field: 'next_valid_date',title: '倒计时',width: 60,align: 'center',
                    sortable: true
                },
                {
                    field: 'add_datetime',title: '上次修改时间',width: 140,align: 'center',
                    sortable: true,
                    formatter: function(value){
                        if(!isNaN(value)  && value >0){
                            return formatDateToString(value,true);
                        }
                    }
                },
                {field: 'username',title: '操作账号',width: 100,align: 'center',sortable: true}
            ]],
            onLoadSuccess: function (data){
                $(this).datagrid('doCellTip',{
                    position : 'bottom',
                    maxWidth : '300px',
                    onlyShowInterrupt : true,
                    specialShowFields : [     
                        {field : 'action',showField : 'action'}
                    ],
                    tipStyler : {            
                        'backgroundColor' : '#E4F0FC',
                        borderColor : '#87A9D0',
                        boxShadow : '1px 1px 3px #292929'
                    }
                });
            }
        });
        
        //构建查询表单
        var searchForm = $('#search-form-car-alert-driv-license');
        searchForm.find('input[name=brand_id]').combotree({
            url: "<?php echo yii::$app->urlManager->createUrl(['car/combotree/get-car-brands']); ?>",
            editable: false,
            panelHeight:'auto',
            lines:false,
            onChange: function(o){
                searchForm.submit();
            }
        });
        searchForm.submit(function(){
            var data = {};
            var searchCondition = $(this).serializeArray();
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = $.trim(searchCondition[i]['value']);
            }
            $('#easyui-datagrid-car-alert-driv-license').datagrid('load',data);
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
        searchForm.find('input[name=scan_type]').combobox({
            valueField:'value',
            textField:'text',
            data: [{"value": '',"text": '不限'},{"value": 'thirty',"text": '30天内'}],
            editable: false,
            onChange: function(){
                searchForm.submit();
            }
        });
        //构建查询表单结束
    }
    //重置查询表单
    CarAlertDrivLicense.resetForm = function(){
        var easyuiForm = $('#search-form-car-alert-driv-license');
        easyuiForm.form('reset');
        easyuiForm.submit();
    }

    // 导出Excel
    CarAlertDrivLicense.exportDlList = function(){
        var searchConditionStr = $('#search-form-car-alert-driv-license').serialize();
        window.open("<?php echo yii::$app->urlManager->createUrl(['car/alert/export-dl-list']); ?>" + "&" + searchConditionStr);
    }

    CarAlertDrivLicense.init();
</script>