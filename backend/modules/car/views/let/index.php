<table id="easyui-datagrid-car-let-index"></table> 
<div id="easyui-datagrid-car-let-index-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-car-let-index">
                <ul class="search-main">
                    <li>
                        <div class="item-name">车牌号</div>
                        <div class="item-input">
                            <input name="plate_number" style="width:100%;" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">合同编号</div>
                        <div class="item-input">
                            <input name="contract_number" style="width:100%;" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">客户名称</div>
                        <div class="item-input">
                            <input name="customer_name" style="width:100%;" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">客户类型</div>
                        <div class="item-input">
                            <input name="customer_type" style="width:100%;" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">出租状态</div>
                        <div class="item-input">
                            <input name="let_status" style="width:100%;" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">出租时间</div>
                        <div class="item-input">
                            <input name="let_time_start" style="width:90px;"/>
                            -
                            <input name="let_time_end" style="width:90px;" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">还车时间</div>
                        <div class="item-input">
                            <input name="back_time_start" style="width:90px;" />
                            -
                            <input name="back_time_end" style="width:90px;" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">车辆品牌</div>
                        <div class="item-input">
                            <input style="width:200px;" name="brand_id" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">车型名称</div>
                        <div class="item-input">
                        	<select class="easyui-combobox"  name="car_model_name" style="width:150px;"
                        		data-options="
                                        panelHeight:'auto',
                                        editable:false,
                                        onChange:function(){
                                            searchForm.submit();
                                        }
                                    "
                        	>
                                <option value="">不限</option>
                                <?php 
                                	$tmp = array();
                                	foreach($config['car_model_name'] as $val){
                                		if(in_array($val['text'], $tmp)){
                                			continue;
                                		}
                                		array_push($tmp, $val['text']); 
                                ?>
                                <option value="<?= $val['text'] ?>"><?= $val['text'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </li>	
					<li>
                        <div class="item-name">合同类型</div>
                        <div class="item-input">
                        	<select class="easyui-combobox"  name="contract_type" style="width:150px;"
                        		data-options="
                                        panelHeight:'auto',
                                        editable:false,
                                        onChange:function(){
                                            searchForm.submit();
                                        }
                                    "
                        	>
                                <option value="">不限</option>
                                <option value="租赁">租赁</option>
								<option value="自运营">自运营</option>
                            </select>
                        </div>
                    </li>
                    <li class="search-button">
                        <button class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button onclick="CarLetIndex.resetForm();" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
                    </li>
                </ul>
            </form>
        </div>
    </div>
    <?php if($buttons){ ?>
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
    var CarLetIndex = new Object();
    //配置数据
    CarLetIndex.CONFIG = <?php echo json_encode($config); ?>;
    CarLetIndex.init = function(){
        //获取列表数据
        $('#easyui-datagrid-car-let-index').datagrid({  
            method: 'get', 
            url:"<?php echo yii::$app->urlManager->createUrl(['car/let/get-list']); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-car-let-index-toolbar",
            pagination: true,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
            showFooter: true,
			pageSize: 20,
            frozenColumns: [[
                {field: 'ck',checkbox: true}, 
                {field: 'id',title: 'id',hidden: true},   
                {field: 'plate_number',title: '车牌号',width: 65,align: 'center',sortable: true},
                {
                    field: 'car_model',title: '车型名称',width: 90,align: 'left',
                    formatter: function(value){
                        var car_type = <?php echo json_encode($config['car_model_name']); ?>;
                        try{
                            return car_type[value].text;
                        }catch(e){
                            return '';
                        }
                    }
                }
            ]],
            columns:[[
                {title: '合同详情',colspan: 6}, // 跨几列
                {
                    field: 'let_time',title: '出租时间',rowspan:2,width: 130,align: 'center',sortable: true,
                    formatter: function(value){
                        if(!isNaN(value) && value > 0){
                            return formatDateToString(value,true);
                        }
                    }
                },
                {field: 'let_status',title: '出租状态',rowspan:2,width: 70,align: 'center',
                    formatter: function(value,row,index){
                        var flag = parseInt(row.back_time)>0 ? 1 : 0;
                        switch (flag) {
                            case 0:
                                return '<span style="background-color:#FFCC01;color:#fff;padding:2px 5px;">出租中</span>';
                            case 1:
                                return '<span style="background-color:#05CD69;color:#fff;padding:2px 5px;">已退租</span>';
                            default:
                                return value;
                        }

                    }
                },
                {
                    field: 'back_time',title: '还车时间',rowspan:2,width: 130,align: 'center',sortable: true,
                    formatter: function(value){
                        if(!isNaN(value) && value > 0){
                            return formatDateToString(value,true);
                        }
                    }
                },
                {field: 'note',title: '备注',width: 200,rowspan:2,align: 'left'}
            ],[
                {field: 'contract_number',title: '合同编号',width: 130,halign: 'center',sortable: true},
				{field: 'contract_type',title: '合同类型',align: 'center'},
                {field: 'customer_name',title: '承租客户',width: 180, halign: 'center',sortable: true,
                    formatter: function(value,row,index){ //企业/个人客户名称
                        if(row.cCustomer_name){
                            return row.cCustomer_name;
                        }else if(row.pCustomer_name){
                            return row.pCustomer_name;
                        }else{
                            return '';
                        }
                    }
                },
                {field: 'customer_type',title: '客户类型',width: 70, align: 'center',sortable: true,
                    formatter: function(value){
                        try{
                            var str = 'CarLetIndex.CONFIG.customer_type.' + value + '.text';
                            return eval(str);
                        }catch(e){
                            return value;
                        }
                    }
                },
                {field: 'keeper_mobile',title: '车管负责人电话',width: 100, align: 'center',sortable: true,
                    formatter: function(value,row,index){
                    	if(row.cKeeper_mobile){
                            return row.cKeeper_mobile;
                        }else if(row.pKeeper_mobile){
                            return row.pKeeper_mobile;
                        }else{
                            return '';
                        }
                    }
                },
                {field: 'salesperson',title: '归属销售员',width: 100, align: 'center',sortable: true,
                    
                },

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
    }
    //查询表单构建
    var searchForm = $('#search-form-car-let-index');
    searchForm.submit(function(){
        var data = {};
        var searchCondition = $(this).serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        $('#easyui-datagrid-car-let-index').datagrid('load',data);
        return false;
    });
    searchForm.find('input[name=plate_number]').textbox({
        onChange: function(){
            searchForm.submit();
        }
    });
    searchForm.find('input[name=contract_number]').textbox({
        onChange: function(){
            searchForm.submit();
        }
    });
    searchForm.find('input[name=customer_name]').textbox({
        onChange: function(){
            searchForm.submit();
        }
    });
    searchForm.find('input[name=customer_type]').combobox({
        valueField:'value',
        textField:'text',
        data: <?= json_encode($searchFormOptions['customer_type']); ?>,
        editable: false,
        panelHeight:'auto',
        onSelect: function(){
            searchForm.submit();
        }
    });
    searchForm.find('input[name=let_status]').combobox({
        valueField:'value',
        textField:'text',
        editable: false,
        panelHeight:'auto',
        data: [{"value": '',"text": '不限'},{"value": 'LETING',"text": '出租中'},{"value": 'BACKED',"text": '已退租'}],
        onSelect: function(){
            searchForm.submit();
        }
    });
    searchForm.find('input[name=let_time_start]').datebox({
        editable: false,
        onChange: function(){
            searchForm.submit();
        }
    });
    searchForm.find('input[name=let_time_end]').datebox({
        editable: false,
        onChange: function(){
            searchForm.submit();
        }
    });
    searchForm.find('input[name=back_time_start]').datebox({
        editable: false,
        onChange: function(){
            searchForm.submit();
        }
    });
    searchForm.find('input[name=back_time_end]').datebox({
        editable: false,
        onChange: function(){
            searchForm.submit();
        }
    });
    searchForm.find('input[name=brand_id]').combotree({
        url: "<?php echo yii::$app->urlManager->createUrl(['car/combotree/get-car-brands']); ?>",
        editable: false,
        panelHeight:'auto',
        lines:false,
        onChange: function(o){
            searchForm.submit();
        }
    });
    //查询表单构建结束
    //获取选择的记录
    CarLetIndex.getSelected = function(){
        var datagrid = $('#easyui-datagrid-car-let-index');
        var selectRow = datagrid.datagrid('getSelected');
        if(!selectRow){
            $.messager.alert('错误','请选择要操作的记录','error');   
            return false;
        }
        return selectRow;
    }
    //导出
    CarLetIndex.exportWidthCondition = function(){
        var form = $('#search-form-car-let-index');
        window.open("<?= yii::$app->urlManager->createUrl(['car/let/export-width-condition']); ?>&"+form.serialize());
    }
    //重置查询表单
    CarLetIndex.resetForm = function(){
        var easyuiForm = $('#search-form-car-let-index');
        easyuiForm.form('reset');
    }
    //执行
    CarLetIndex.init();
</script>