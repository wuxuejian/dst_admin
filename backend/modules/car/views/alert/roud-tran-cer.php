<table id="easyui-datagrid-car-alert-roud-tran-cer"></table> 
<div id="easyui-datagrid-car-alert-roud-tran-cer-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-car-alert-roud-tran-cer">
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
                    <li class="search-button">
                        <button class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button onclick="CarAlertRoudTranCer.resetForm();return false;" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
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
    var CarAlertRoudTranCer = new Object();
    //配置项
    CarAlertRoudTranCer.CONFIG = <?= json_encode($config); ?>;
    CarAlertRoudTranCer.init = function(){
        //获取列表数据
        $('#easyui-datagrid-car-alert-roud-tran-cer').datagrid({  
            method: 'get', 
            url:"<?php echo yii::$app->urlManager->createUrl(['car/alert/rtc-get-list']); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-car-alert-roud-tran-cer-toolbar",
            pagination: true,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
			pageSize: 20,
            sortName: 'next_annual_verification_date',
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
                {field: 'ton_or_seat',title: '吨（座）位',width: 80,align: 'center',sortable: true},
                {
                    field: 'issuing_organ',title: '核发机关',width: 140,
                    align: 'left',sortable: true,
                    formatter: function(value){
                        var issuing_organ = <?php echo json_encode($config['TC_ISSUED_BY']); ?>;
                        if(issuing_organ[value]){
                            return issuing_organ[value].text;
                        }
                    }
                },
                {
                    field: 'image',title: '附件',width: 44,
                    align: 'center',sortable: true,
                    formatter: function(value){
                        if(value)
                        {
                        	return '<a href="'+value+'" target="_blank" ><img  width="16px" height="16px"  src="./jquery-easyui-1.4.3/themes/icons/large_picture.png" /></a>';
                        } 
                    }
                },
                {field: 'rtc_province',title: '省',width: 40,align: 'center',sortable: true},
                {field: 'rtc_city',title: '市',width: 40,align: 'center',sortable: true},
                {field: 'rtc_number',title: '运输证号',width: 90,align: 'left',sortable: true},
                {
                    field: 'issuing_date',title: '发证日期',width: 80,align: 'center',sortable: true,
                    formatter: function(value){
                        if(!isNaN(value)  && value >0){
                            return formatDateToString(value);
                        }
                    }
                },
                {
                    field: 'last_annual_verification_date',title: '上次审核时间',width: 90,align: 'center',sortable: true,
                    formatter: function(value){
                        if(!isNaN(value)  && value >0){
                            return formatDateToString(value);
                        }
                    }
                },
				{
                    field: 'next_annual_verification_date2',title: '下次审核时间',width: 90,align: 'center',
                    formatter: function(value, row){
                        if(!isNaN(row.next_annual_verification_date)  && row.next_annual_verification_date >0){
                            return formatDateToString(row.next_annual_verification_date);
                        }
                    }
                },
                {
                    field: 'next_annual_verification_date',title: '倒计时',width: 60,align: 'center',sortable: true,
                    formatter: function(value){
                        if(isNaN(value)){
                            return '';
                        }
                        var timeStamp = Date.parse(new Date()) / 1000;
                        if(timeStamp >=  value){
                            return '<span style="color:red">已过期</span>';
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
        var searchForm = $('#search-form-car-alert-roud-tran-cer');
        searchForm.submit(function(){
            var data = {};
            var searchCondition = $(this).serializeArray();
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = $.trim(searchCondition[i]['value']);
            }
            $('#easyui-datagrid-car-alert-roud-tran-cer').datagrid('load',data);
            return false;
        });
        searchForm.find('input[name=plate_number]').textbox({
            onChange: function(){
                $('#search-form-car-alert-roud-tran-cer').submit();
            }
        });
        searchForm.find('input[name=scan_type]').combobox({
            valueField:'value',
            textField:'text',
            data: [{"value": '',"text": '不限'},{"value": 'thirty',"text": '30天内'}],
            editable: false,
            onChange: function(){
                $('#search-form-car-alert-roud-tran-cer').submit();
            }
        });
        //构建查询表单结束
    }
    //重置查询表单
    CarAlertRoudTranCer.resetForm = function(){
        var easyuiForm = $('#search-form-car-alert-roud-tran-cer');
        easyuiForm.form('reset');
        easyuiForm.submit();
    }

    // 导出Excel
    CarAlertRoudTranCer.exportRtcList = function(){
        var searchConditionStr = $('#search-form-car-alert-roud-tran-cer').serialize();
        window.open("<?php echo yii::$app->urlManager->createUrl(['car/alert/export-rtc-list']); ?>" + "&" + searchConditionStr);
    }

    CarAlertRoudTranCer.init();
</script>