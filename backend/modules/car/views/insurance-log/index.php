<table id="easyui-datagrid-car-insurance-log"></table> 
<div id="easyui-datagrid-car-insurance-log-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-car-insurance-log">
                <ul class="search-main">
                	<li>
                        <div class="item-name">保单号</div>
                        <div class="item-input">
                            <input name="number" style="width:200px;" />
                        </div>
                    </li>
                	<li>
                        <div class="item-name">车牌/车架/发动机</div>
                        <div class="item-input">
                            <input name="plate_number" style="width:200px;" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">车型名称</div>
                        <div class="item-input">
                            <input style="width:200px;" name="car_model" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">保期</div>
                        <div class="item-input">
                            <input class="easyui-datebox" type="text" name="start_date" style="width:93px;"
                                   data-options="
                                        onChange:function(){
                                            $('#search-form-car-insurance-log').submit();
                                        }
                                   "
                                />
                            -
                            <input class="easyui-datebox" type="text" name="end_date" style="width:93px;"
                                   data-options="
                                        onChange:function(){
                                            $('#search-form-car-insurance-log').submit();
                                        }
                                   "
                                />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">保险公司</div>
                        <div class="item-input">
                            <input name="insurer_company" style="width:200px;" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">险种</div>
                        <div class="item-input">
                            <input name="insurance_type" style="width:200px;" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">保费</div>
                        <div class="item-input">
                            <input name="start_money_amount" style="width:80px;" />
                            -
                            <input name="end_money_amount" style="width:80px;" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">操作人员</div>
                        <div class="item-input">
                            <input name="oper_user" style="width:200px;" />
                        </div>
                    </li>
                    
                    <li class="search-button">
                        <button class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button onclick="CarInsuranceLog.resetForm();return false;" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
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
<!-- 窗口 -->
<div id="easyui-window-car-insurance-log-scan"></div>
<!-- 窗口 -->
<script>
    var CarInsuranceLog = new Object();
    CarInsuranceLog.init = function(){
        var easyuiDatagrid = $('#easyui-datagrid-car-insurance-log');
        //获取列表数据
        easyuiDatagrid.datagrid({  
            method: 'get', 
            url:"<?php echo yii::$app->urlManager->createUrl(['car/insurance-log/get-list']); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-car-insurance-log-toolbar",
            pagination: true,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
            pageSize: 20,
            frozenColumns: [[
                {field: 'ck',checkbox: true}, 
                {field: 'id',title: 'id',hidden: true},
                {field: '_type',title: 'type',hidden: true}
            ]],
            columns:[[
                {
                    field: 'plate_number',title: '车牌号',align: 'center',
                    sortable: true
                },
                {
                    field: 'car_status',title: '一级状态',align: 'center',
                    sortable: true,
                    formatter: function(value){
                        var status = <?php echo json_encode($config['car_status']); ?>;
                        //console.log(status)
                        try{
                            return status[value].text;
                        }catch(e){
                            return '';
                        }
                    }
                },

                {
                    field: 'use_nature_a',title: '使用性质',align: 'center',

                    sortable: true,
                    formatter: function(value, row){
                        //console.log(row.use_nature_a);
                        //console.log(row.use_nature_d);
                        if(row.use_nature_d){
                            if(row.use_nature_d == 1){
                            return '企业营运货车';
                            }else if(row.use_nature_d == 2) {
                                return '企业非营运货车';   
                            }else if(row.use_nature_d == 3) {
                                return '企业非营运客车';   
                            }else if(row.use_nature_d == 4) {
                                return '企业营运客车';   
                            } else if(row.use_nature_d == 5) {
                                return '个人家庭自用车';   
                            }else if(row.use_nature_d == 6) {
                                return '特种车';   
                            }  
                        } else {
                           if(row.use_nature_a == 1){
                            return '企业营运货车';
                            }else if(row.use_nature_a == 2) {
                                return '企业非营运货车';   
                            }else if(row.use_nature_a == 3) {
                                return '企业非营运客车';   
                            }else if(row.use_nature_a == 4) {
                                return '企业营运客车';   
                            } else if(row.use_nature_a == 5) {
                                return '个人家庭自用车';   
                            }else if(row.use_nature_a == 6) {
                                return '特种车';   
                            } 
                        } 
                        
                    }
                },
				{
                    field: 'car_model_name',title: '车型名称',align: 'center',
                    sortable: true,
                   /* formatter: function(value){
                        var car_type = <?php echo json_encode($config['car_model_name']); ?>;
                        try{
                            return car_type[value].text;
                        }catch(e){
                            return '';
                        }
                    }*/
                },
                 /*{
                    field: 'customer_name',title: '归属客户',align: 'center',width: 80,
                    sortable: true
                },*/
                
                // {
                    // field: '_end_date',title: '倒计时',align: 'center',
                    // sortable: true,
                    // formatter: function(value){
                        // if(isNaN(value) || value <= 0){
                            // return '';
                        // }
                        // value = parseInt(value) + 86400;
                        // var timeStamp = Date.parse(new Date()) / 1000;
                        // if(value <= timeStamp){
                            // return '<span style="color:red">已过期</span>';
                        // }
                        // var leftDay = Math.ceil((value - timeStamp) / 86400);
                        // if(leftDay <= 7){
                            // return '<span style="color:red">'+leftDay+'天</span>';
                        // }
                        // return leftDay+'天';
                    // }
                // },
                {
                    field: 'type',title: '险种',width: 160,align: 'center',
                    formatter: function(value){
                        if(value==1){
                            return '交强险';
                        }else{
                            /*if(value==3){
                                var data = eval(value);
                                //alert(data);
                                return '其他险';
                            }*/
							if(value=='' || value==null){
								return '';
							}
							var data = eval(value);
                            
							var insurance_str='';
							for(var i=0;i<data.length;i++){
                                if(data[i][0]=='车损险'){
                                //alert(123);
                                data[i][0] = '机动车损失保险';
                            } else if(data[i][0]=='三者险') {
                                data[i][0] = '机动车第三者责任保险';
                            }
                            else if(data[i][0]=='司乘险(司机)') {
                                data[i][0] = '机动车车上人员责任保险(司机)';
                            }
                            else if(data[i][0]=='司乘险(乘客)') {
                                data[i][0] = '机动车车上人员责任保险(乘客)';
                            }
                            else if(data[i][0]=='盗抢险') {
                                data[i][0] = '全车盗抢保险';
                            }
                            else if(data[i][0]=='玻璃险') {
                                data[i][0] = '玻璃单独破碎险';
                            }
                            else if(data[i][0]=='涉水险') {
                                data[i][0] = '发动机涉水损失险';
                            }
                            else if(data[i][0]=='不计免赔险') {
                                data[i][0] = '不计免赔率险';
                            }
                            else if(data[i][0]=='无法找到第三方特约险') {
                                data[i][0] = '机动车损失保险无法找到第三方特约险';
                            }
								insurance_str+=data[i][0]+'('+data[i][1]+'),';
							}
							return insurance_str;
                        }
                        return '';
                    }
                },
                {
                    field: 'start_date',title: '开始时间',
                    align: 'center',sortable: true,
                    formatter: function(value){
                        if(!isNaN(value) && value > 0){
                            return formatDateToString(value);
                        }
                    }
                },
                {
                    field: 'end_date',title: '结束时间',align: 'center',
                    sortable: true,
                    formatter: function(value){
                        if(!isNaN(value) && value > 0){
                            return formatDateToString(value);
                        }
                    }
                },
                {
                    field: 'number',title: '保单号',align: 'center'
                },
                {
                    field: 'insurer_company',title: '保险公司',width: 160,
                    sortable: true,
                    formatter: function(value){
                        var insurer_company = <?php echo json_encode($config['INSURANCE_COMPANY']); ?>;
                        if(insurer_company[value]){
                            return insurer_company[value].text;
                        }
                    }
                },
                {field: 'money_amount',title: '保费金额',sortable: true},
                {
                    field: 'numberp',title: '批单号',align: 'center'
                },
                /*{field: 'note',title: '备注',width: 200,align: 'left',sortable: true},*/
                /*{
                    field: 'add_datetime',title: '上次修改时间',width: 160,
                    align: 'center',sortable: true,
                    formatter: function(value){
                        if(!isNaN(value) && value > 0){
                            return formatDateToString(value,true);
                        }
                    }
                },*/
                /*{field: 'username',title: '操作人员',align: 'center',sortable: true}*/
                {field: 'money_amount_p',title: '批改后保费金额',sortable: true,align: 'center'},
                {
                    field: 'operating_company_id',title: '车辆运营公司',align: 'center'
                },
                {
                    field: 'owner_name',title: '机动车辆所有人',align: 'center'
                },

            ]],
           /* onDblClickRow: function(rowIndex,rowData){
                console.log(rowData);
                //alert(rowData);
                CarInsuranceLog.scan(rowData.id,rowData.type);
            },*/
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
        var searchForm = $('#search-form-car-insurance-log');
        searchForm.submit(function(){
            var data = {};
            var searchCondition = $(this).serializeArray();
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = searchCondition[i]['value'];
            }
            easyuiDatagrid.datagrid('load',data);
            return false;
        });
        searchForm.find('input[name=number]').textbox({
            onChange: function(){
                searchForm.submit();
            }
        });
        searchForm.find('input[name=plate_number]').textbox({
            onChange: function(){
                searchForm.submit();
            }
        });
        searchForm.find('input[name=oper_user]').textbox({
            onChange: function(){
                searchForm.submit();
            }
        });
        searchForm.find('input[name=start_money_amount]').textbox({
            onChange: function(){
                searchForm.submit();
            }
        });
        searchForm.find('input[name=end_money_amount]').textbox({
            onChange: function(){
                searchForm.submit();
            }
        });
        searchForm.find('input[name=car_model]').combobox({
            valueField:'value',
            textField:'text',
            data: <?= json_encode($searchFormOptions['car_model_name']); ?>,
            editable: false,
            panelHeight:'auto',
            onSelect: function(){
                searchForm.submit();
            }
        });
        searchForm.find('input[name=insurer_company]').combobox({
            valueField:'value',
            textField:'text',
            data: <?= json_encode($insurerCompany); ?>,
            editable: false,
            onChange: function(){
                searchForm.submit();
            }
        });
        <?php 
        	$insurance_types = array(
        			array('value'=>'','text'=>'不限'),
        			array('value'=>1,'text'=>'交强险'),
        			array('value'=>2,'text'=>'商业险'),
        			array('value'=>3,'text'=>'其它险')
        		);
        ?>
        searchForm.find('input[name=insurance_type]').combobox({
            valueField:'value',
            textField:'text',
            data: <?= json_encode($insurance_types); ?>,
            editable: false,
            onChange: function(){
                searchForm.submit();
            }
        });
        //构建查询表单结束
      	//初始化查看窗口
		$('#easyui-window-car-insurance-log-scan').window({
			title: '购买保险记录',
            width: '750',   
            height: '600',   
            closed: true,   
            cache: true,   
            modal: true,
            collapsible: false,
            minimizable: false, 
            maximizable: true,
            onClose: function(){
                $(this).window('clear');
            }       
		});
    }
  	//查看
	CarInsuranceLog.scan = function(id,type){
		if(!id){
			var selectRow = this.getSelected();
			if(!selectRow){
				return false;
			}
	        id = selectRow.id;
	        type = selectRow._type;
		}
		$('#easyui-window-car-insurance-log-scan').window('open');
		$('#easyui-window-car-insurance-log-scan').window('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/insurance-log/scan']); ?>&id="+id+"&type="+type);
	}
    CarInsuranceLog.init();
    //获取选择的记录
    CarInsuranceLog.getSelected = function(){
        var datagrid = $('#easyui-datagrid-car-insurance-log');
        var selectRow = datagrid.datagrid('getSelected');
        if(!selectRow){
            $.messager.alert('错误','请选择要操作的记录','error');   
            return false;
        }
        return selectRow;
    }
  //按条件导出车辆列表
    CarInsuranceLog.exportWidthCondition = function(){
        var url = "<?php echo yii::$app->urlManager->createUrl(['car/insurance-log/export-width-condition']);?>";
        var form = $('#search-form-car-insurance-log');
        var data = {};
        var searchCondition = form.serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        for(var i in data){
            url += '&'+i+'='+data[i];
        }
        window.open(url);
    }
    //重置查询表单
    CarInsuranceLog.resetForm = function(){
        var easyuiForm = $('#search-form-car-insurance-log');
        easyuiForm.form('reset');
        easyuiForm.submit();
    }
</script>