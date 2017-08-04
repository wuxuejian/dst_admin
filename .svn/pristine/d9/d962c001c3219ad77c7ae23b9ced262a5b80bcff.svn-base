<table id="easyui-datagrid-car-alert-business-compulsory"></table> 
<div id="easyui-datagrid-car-alert-business-compulsory-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-car-alert-bussiness-compulsory">
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
                    <li>
                        <div class="item-name">查看类型</div>
                        <div class="item-input">
                            <input name="scan_type" style="width:200px;" />
                        </div>
                    </li>
                    <li class="search-button">
                        <button class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button onclick="CarAlertBusinessCompulsory.resetForm();return false;" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
                    </li>
					<li>
                        <div class="item-name">车辆运营公司</div>
                        <div class="item-input">
                            <input style="width:200px;" name="operating_company_id" />
                        </div>
                    </li>
					<li>
                        <div class="item-name">机动车所有人</div>
                        <div class="item-input">
                            <input style="width:200px;" name="owner_id" />
                        </div>
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
    var CarAlertBusinessCompulsory = new Object();
    //配置项
    CarAlertBusinessCompulsory.CONFIG = <?= json_encode($config); ?>;
    CarAlertBusinessCompulsory.init = function(){
        //获取列表数据
        $('#easyui-datagrid-car-alert-business-compulsory').datagrid({  
            method: 'get', 
            url:"<?php echo yii::$app->urlManager->createUrl(['car/alert/bc-get-list']); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-car-alert-business-compulsory-toolbar",
            pagination: true,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
			pageSize: 20,
            sortName: '_end_date',
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
                            var str = 'CarAlertBusinessCompulsory.CONFIG.car_status.' + value + '.text';
                            return eval(str);
                        }catch(e){
                            return value;
                        }
                    }
                },
                {
                    field: 'insurer_company',title: '保险公司名称',
                    width: 280,align: 'left',sortable: true,
                    formatter: function(value){
                        var insurer_company = <?= json_encode($insurerCompany); ?>;
                        if(insurer_company[value]){
                            return insurer_company[value].text;
                        }
                    }
                },
                {field: 'money_amount',title: '保险金额',width: 80,align: 'right',sortable: true},
                {
                    field: 'start_date',title: '开始时间',width: 80,align: 'center',
                    sortable: true,
                    formatter: function(value){
                        if(!isNaN(value)  && value >0){
                            return formatDateToString(value);
                        }
                    }
                },
                {
                    field: 'end_date',title: '结束时间',width: 80,align: 'center',
                    sortable: true,
                    formatter: function(value){
                        if(!isNaN(value)  && value >0){
                            return formatDateToString(value);
                        }
                    }
                },
                {
                    field: '_end_date',title: '倒计时',width: 80,align: 'center',
                    sortable: true,
                    formatter: function(value){
                        if(isNaN(value) || value <= 0){
                            return '';
                        }
                        value = parseInt(value) + 86400;
                        var timeStamp = Date.parse(new Date()) / 1000;
                        if(value <= timeStamp){
                            return '<span style="color:red">已过期</span>';
                        }
                        var leftDay = Math.ceil((value - timeStamp) / 86400);
                        if(leftDay <= 7){
                            return '<span style="color:red">'+leftDay+'天</span>';
                        }
                        return leftDay+'天';
                    }
                },
				 {field: 'operating_company_id', title: '车辆运营公司', width: 170, halign: 'center', sortable: true},
				 {field: 'owner_name', title: '机动车辆所有人', width: 170, halign: 'center', sortable: true},
                
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
        var searchForm = $('#search-form-car-alert-bussiness-compulsory');
        searchForm.submit(function(){
            var data = {};
            var searchCondition = $(this).serializeArray();
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = $.trim(searchCondition[i]['value']);
            }
            $('#easyui-datagrid-car-alert-business-compulsory').datagrid('load',data);
            return false;
        });
        searchForm.find('input[name=plate_number]').textbox({
            onChange: function(){
                $('#search-form-car-alert-bussiness-compulsory').submit();
            }
        });
        searchForm.find('input[name=insurer_company]').combobox({
            valueField:'value',
            textField:'text',
            data: <?= json_encode($serachConditionIC); ?>,
            editable: false,
            onChange: function(){
                $('#search-form-car-alert-bussiness-compulsory').submit();
            }
        });
        searchForm.find('input[name=scan_type]').combobox({
            valueField:'value',
            textField:'text',
            data: [{"value": '',"text": '不限'},{"value": 'seven',"text": '七天'}],
            editable: false,
            onChange: function(){
                $('#search-form-car-alert-bussiness-compulsory').submit();
            }
        });
		searchForm.find('input[name=operating_company_id]').combobox({
        	valueField:'value',
            textField:'text',
            data: <?= json_encode($searchFormOptions['operating_company_id']); ?>,
            editable: false,
            panelHeight:'auto',
            onSelect: function(){
                searchForm.submit();
            }
        });
		searchForm.find('input[name=owner_id]').combotree({
            url: "<?php echo yii::$app->urlManager->createUrl(['owner/combotree/get-owners']); ?>",
            editable: false,
            panelHeight:'auto',
            lines:false,
            onChange: function(o){
                searchForm.submit();
            }
        });
        //构建查询表单结束
    }
    //重置查询表单
    CarAlertBusinessCompulsory.resetForm = function(){
        var easyuiForm = $('#search-form-car-alert-bussiness-compulsory');
        easyuiForm.form('reset');
        easyuiForm.submit();
    }

    // 导出Excel
    CarAlertBusinessCompulsory.exportBcList = function(){
        var searchConditionStr = $('#search-form-car-alert-bussiness-compulsory').serialize();
        window.open("<?php echo yii::$app->urlManager->createUrl(['car/alert/export-bc-list']); ?>" + "&" + searchConditionStr);
    }

    CarAlertBusinessCompulsory.init();
</script>