<table id="easyui-datagrid-car-insurance-index"></table> 
<div id="easyui-datagrid-car-insurance-index-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-car-insurance-index">
                <ul class="search-main">
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
                    <li class="item-name">
		                <div class="item-name">归属客户</div>
		                <div class="item-input">
		                    <input
		                        id="easyui-form-car-insurance-customerCombogrid"
		                        name="customer"
		                        style="width:180px;"
		                        />
		                </div>
		            </li>
                    <li>
                        <div class="item-name">交强险</div>
                        <div class="item-input">
                            <input style="width:200px;" name="transact_ic" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">商业险</div>
                        <div class="item-input">
                            <input style="width:200px;" name="transact_ib" />
                        </div>
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
                    <li class="search-button">
                        <button type="submit" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button type="submit" onclick="CarInsuranceIndex.resetForm()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
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
                <button onclick="<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon']; ?>'"><?= $val['text']; ?></button>
            <?php } ?>
        </div>
    <?php } ?>
</div>
<!-- 窗口 -->
<div id="easyui-dialog-car-insurance-index-add"></div>
<div id="easyui-window-car-insurance-index-scan"></div>
<div id="easyui-dialog-car-insurance-index-edit"></div>
<div id="easyui-window-car-insurance-index-attachment"></div>
<div id="easyui-window-car-insurance-index-fault"></div>
<div id="easyui-dialog-car-insurance-index-driving-license"></div>
<div id="easyui-dialog-car-insurance-index-road-transport-certificate"></div>
<div id="easyui-window-car-insurance-second-maintenance-record"></div>
<div id="easyui-window-car-insurance-traffic-compulsory-insurance"></div>
<div id="easyui-window-car-insurance-business-insurance"></div>
<div id="easyui-window-car-insurance-other-insurance"></div>
<div id="easyui-window-car-insurance-claim"></div>
<!-- 窗口 -->
<script>
	var CarInsuranceIndex = new Object();
	CarInsuranceIndex.init = function(){
		$('#easyui-datagrid-car-insurance-index').datagrid({  
			method: 'get', 
		    url:"<?php echo yii::$app->urlManager->createUrl(['car/insurance/get-list']); ?>",   
			fit: true,
			border: false,
			toolbar: "#easyui-datagrid-car-insurance-index-toolbar",
			pagination: true,
			loadMsg: '数据加载中...',
			striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: false,
			pageSize: 20,
            frozenColumns: [[
				{field: 'ck',checkbox: true}, 
				{field: 'id',title: 'id',hidden: true},   
				{field: 'plate_number',title: '车牌号',width: 70,sortable: true,align: 'center'}
			]],
		    columns: [[
                {field: 'car_status',title: '一级状态',width: 70,sortable: true,align: 'center',
                    sortable: true,
                    formatter: function(value){
                        var status = <?php echo json_encode($config['car_status']); ?>;
                        //alert(status)
                        //console.log(status)
                        try{
                            return status[value].text;
                        }catch(e){
                            return '';
                        }
                    }
                },
                {field: 'use_nature',title: '使用性质',width: 85,sortable: true,align: 'center',
                    sortable: true,
                    formatter: function(value,row){
                        //console.log(row);
                        if(row.use_nature_p){
                            if(row.use_nature_p == 1){
                            return '企业营运货车';
                            }else if(row.use_nature_p == 2) {
                                return '企业非营运货车';   
                            }else if(row.use_nature_p == 3) {
                                return '企业非营运客车';   
                            }else if(row.use_nature_p == 4) {
                                return '企业营运客车';   
                            } else if(row.use_nature_p == 5) {
                                return '个人家庭自用车';   
                            }else if(row.use_nature_p == 6) {
                                return '特种车';   
                            }  
                        } else {
                           if(row.use_nature == 1){
                            return '企业营运货车';
                            }else if(row.use_nature == 2) {
                                return '企业非营运货车';   
                            }else if(row.use_nature == 3) {
                                return '企业非营运客车';   
                            }else if(row.use_nature == 4) {
                                return '企业营运客车';   
                            } else if(row.use_nature == 5) {
                                return '个人家庭自用车';   
                            }else if(row.use_nature == 6) {
                                return '特种车';   
                            } 
                        } 
                        
                    }
                },
                {
                    field: 'car_model_name2',title: '车型名称',width: 90,align: 'center',
                    sortable: true,
                    /*formatter: function(value){
                        var car_type = <?php echo json_encode($config['car_model_name']); ?>;
                        try{
                            return car_type[value].text;
                        }catch(e){
                            return '';
                        }
                    }*/
                },
                {
                    field: 'transact_ic',title: '交强险',width: 70,align: 'center',
                    sortable: true,
                    formatter: function(value){
                        //alert(value);
                        if(value >= 1){
                            return '<img src="./jquery-easyui-1.4.3/themes/icons/ok.png" />';
                        }else{
                            return '<img src="./jquery-easyui-1.4.3/themes/icons/no.png" />';
                        }
                    }
                },
                {
                    field: 'transact_ic_f',title: '交强险附件',width: 70,align: 'center',
                    sortable: true,
                    formatter: function(value){
                        if(value == null || value == '[]'){
                            return '<img src="./jquery-easyui-1.4.3/themes/icons/no.png" />';
                        }else{
                            
                            return '<img src="./jquery-easyui-1.4.3/themes/icons/ok.png" />';
                        }
                    }
                },
                {
                    field: 'start_date_tic',title: '开始时间',width: 70,align: 'center',
                    sortable: false,
                    formatter: function(value){
                    	if(!isNaN(value) && value >0){
                            return formatDateToString(value);
                        }
                    }
                },
                {
                    field: 'end_date_tic',title: '结束时间',width: 70,align: 'center',
                    sortable: false,
                    formatter: function(value){
                    	if(!isNaN(value) && value >0){
                            return formatDateToString(value);
                        }
                    }
                },
                /*{
                    field: '_compulsory_end_date',title: '交强险倒计时',width: 80,align: 'center',
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
                },*/
                {
                    field: 'transact_ic_pd',title: '交强险批单',width: 70,align: 'center',
                    sortable: true,
                    formatter: function(value){
                        //alert(value);
                        if(value >= 1){
                            return '<img src="./jquery-easyui-1.4.3/themes/icons/ok.png" />';
                        }else{
                            return '<img src="./jquery-easyui-1.4.3/themes/icons/no.png" />';
                        }
                    }
                },
                {
                    field: 'transact_ib',title: '商业险',width: 70,align: 'center',
                    sortable: true,
                    formatter: function(value){
                        if(value >= 1){
                            return '<img src="./jquery-easyui-1.4.3/themes/icons/ok.png" />';
                        }else{
                            return '<img src="./jquery-easyui-1.4.3/themes/icons/no.png" />';
                        }
                    }
                },
                 {
                    field: 'transact_ib_f',title: '商业险附件',width: 70,align: 'center',
                    sortable: true,
                    formatter: function(value){
                        if(value == null || value=='[]'){
                            return '<img src="./jquery-easyui-1.4.3/themes/icons/no.png" />';
                        }else{
                            return '<img src="./jquery-easyui-1.4.3/themes/icons/ok.png" />';
                        }
                    }
                },
                {
                    field: 'start_date_bi',title: '开始时间',width: 70,align: 'center',
                    sortable: false,
                    formatter: function(value){
                    	if(!isNaN(value) && value >0){
                            return formatDateToString(value);
                        }
                    }
                },
                {
                    field: 'end_date_bi',title: '结束时间',width: 70,align: 'center',
                    sortable: false,
                    formatter: function(value){
                    	if(!isNaN(value) && value >0){
                            return formatDateToString(value);
                        }
                    }
                },
                /*{
                    field: '_business_end_date',title: '商业险倒计时',width: 80,align: 'center',
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
                },*/
                 {
                    field: 'transact_ib_pd',title: '商业险批单',width: 70,align: 'center',
                    sortable: true,
                    formatter: function(value){
                        if(value >= 1){
                            return '<img src="./jquery-easyui-1.4.3/themes/icons/ok.png" />';
                        }else{
                            return '<img src="./jquery-easyui-1.4.3/themes/icons/no.png" />';
                        }
                    }
                },


				{field: 'operating_company_id', title: '车辆运营公司', width: 170, halign: 'center', sortable: true},
				{field: 'owner_name', title: '机动车辆所有人', width: 170, halign: 'center', sortable: true},
                
                /*{
                    field: 'insurance_last_update_time',title: '上次修改时间',width: 90,align: 'center',
                    sortable: false,
                    formatter: function(value){
                        if(!isNaN(value) && value >0){
                            return formatDateToString(value);
                        }
                    }
                },*/
                {
                    field: 'customer_name',title: '归属客户',width: 70,align: 'center'
                },
                {field: 'username',title: '操作人员',width: 100,halign: 'center',sortable: false}
            ]],
            onDblClickRow: function(rowIndex,rowData){
                CarInsuranceIndex.scan(rowData.id);
            },
            onLoadSuccess: function (data) {
                //单元格内容悬浮提示，doCellTip()是在入口文件index.php中拓展的。
                $(this).datagrid('doCellTip', {
                    position: 'bottom',
                    maxWidth: '300px',
                    onlyShowInterrupt: true, //false时所有单元格都显示提示；true时配合specialShowFields自定义要提示的列
                    specialShowFields: [     //需要提示的列
                        //{field: 'sketch', showField: 'sketch'}
                    ],
                    tipStyler: {
                        backgroundColor: '#E4F0FC',
                        borderColor: '#87A9D0',
                        boxShadow: '1px 1px 3px #292929'
                    }
                });
            }
		});	
		//构建查询表单
        var searchForm = $('#search-form-car-insurance-index');
        /**查询表单提交事件**/
        searchForm.submit(function(){
            var data = {};
            var searchCondition = $(this).serializeArray();
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = searchCondition[i]['value'];
            }
            $('#easyui-datagrid-car-insurance-index').datagrid('load',data);
            return false;
        });
        searchForm.find('input[name=plate_number]').textbox({
            onChange: function(){
                searchForm.submit();
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
        searchForm.find('input[name=transact_dl]').combobox({
            valueField:'value',
            textField:'text',
            editable: false,
            panelHeight:'auto',
            data: [{"value": '',"text": '不限'},{"value": 1,"text": '已办理'},{"value": 2,"text": '未办理'}],
            onSelect: function(){
                searchForm.submit();
            }
        });
        searchForm.find('input[name=transact_ic]').combobox({
            valueField:'value',
            textField:'text',
            editable: false,
            panelHeight:'auto',
            data: [{"value": '',"text": '不限'},{"value": 1,"text": '已办理'},{"value": 2,"text": '未办理'}],
            onSelect: function(){
                searchForm.submit();
            }
        });
        searchForm.find('input[name=transact_ib]').combobox({
            valueField:'value',
            textField:'text',
            editable: false,
            panelHeight:'auto',
            data: [{"value": '',"text": '不限'},{"value": 1,"text": '已办理'},{"value": 2,"text": '未办理'}],
            onSelect: function(){
                searchForm.submit();
            }
        });
        //构建查询表单结束
		//初始化添加窗口
		$('#easyui-dialog-car-insurance-index-add').dialog({
        	title: '添加车辆信息',   
            width: '980px',   
            height: '500px',   
            closed: true,   
            cache: true,   
            modal: true,
            resizable:true,
            maximizable: true,
            buttons: [{
				text:'确定',
				iconCls:'icon-ok',
				handler:function(){
                    var form = $('#easyui-form-car-insurance-add');
                    if(!form.form('validate')) return false;
					var data = form.serialize();
					$.ajax({
						type: 'post',
						url: "<?php echo yii::$app->urlManager->createUrl(['car/insurance/add']); ?>",
						data: data,
						dataType: 'json',
						success: function(data){
							if(data.status){
								$.messager.alert('添加成功',data.info,'info');
								$('#easyui-dialog-car-insurance-index-add').dialog('close');
								$('#easyui-datagrid-car-insurance-index').datagrid('reload');
							}else{
								$.messager.alert('添加失败',data.info,'error');
							}
						}
					});
				}
			},{
				text:'取消',
				iconCls:'icon-cancel',
				handler:function(){
					$('#easyui-dialog-car-insurance-index-add').dialog('close');
				}
			}],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
        //初始化查看窗口
		$('#easyui-window-car-insurance-index-scan').window({
			title: '车辆保险信息',
            width: '80%',   
            height: '80%',   
            closed: true,   
            cache: true,   
            modal: true,
            collapsible: false,
            minimizable: false, 
            maximizable: false,
            onClose: function(){
                $(this).window('clear');
            }       
		});
        //初始化修改窗口
		$('#easyui-dialog-car-insurance-index-edit').dialog({
        	title: '修改车辆信息',   
            width: '980px',   
            height: '500px',   
            closed: true,   
            cache: true,   
            modal: true,
            resizable:true,
            maximizable: true,
            buttons: [{
				text:'确定',
				iconCls:'icon-ok',
				handler:function(){
                    var form = $('#easyui-form-car-insurance-edit');
                    if(!form.form('validate')) return false;
                    var data = form.serialize();
					$.ajax({
						type: 'post',
						url: "<?php echo yii::$app->urlManager->createUrl(['car/insurance/edit']); ?>",
						data: data,
						dataType: 'json',
						success: function(data){
							if(data.status){
								$.messager.alert('修改成功',data.info,'info');
								$('#easyui-dialog-car-insurance-index-edit').dialog('close');
								$('#easyui-datagrid-car-insurance-index').datagrid('reload');
							}else{
								$.messager.alert('修改失败',data.info,'error');
							}
						}
					});
				}
			},{
				text:'取消',
				iconCls:'icon-cancel',
				handler:function(){
					$('#easyui-dialog-car-insurance-index-edit').dialog('close');
				}
            }],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
        //初始化附件管理窗口
        $('#easyui-window-car-insurance-index-attachment').window({
            title: '车辆附件管理',
        	width: 800,   
            height: 500,   
            modal: true,
            closed: true,
            collapsible: false,
            minimizable: false,
            maximizable: false,
            onClose: function(){
                $(this).window('clear');
            }                    
        });
        //初始化故障管理窗口
        $('#easyui-window-car-insurance-index-fault').window({
            title: '车辆故障管理',
        	width: 1000,   
            height: 600,   
            modal: true,
            closed: true,
            collapsible: false,
            minimizable: false,
            maximizable: false,
            onClose: function(){
                $(this).window('clear');
            }                      
        });
        //初始化行驶证管理窗口
        $('#easyui-dialog-car-insurance-index-driving-license').dialog({
            title: '车辆行驶证',   
            width: '640px',   
            height: '270px',   
            closed: true,   
            cache: true,   
            modal: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var form = $('#easyui-form-car-insurance-driving-license');
                    if(!form.form('validate')) return false;
                    var data = form.serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['car/insurance/driving-license']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('操作成功',data.info,'info');
                                $('#easyui-dialog-car-insurance-index-driving-license').dialog('close');
                            }else{
                                $.messager.alert('操作失败',data.info,'error');
                            }
                        }
                    });
                }
            },{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui-dialog-car-insurance-index-driving-license').dialog('close');
                }
            }],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
        //初始化道路运输证窗口
        $('#easyui-dialog-car-insurance-index-road-transport-certificate').dialog({
            title: '车辆道路运输证',   
            width: '630px',   
            height: '270px',   
            closed: true,   
            cache: true,   
            modal: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var form = $('#easyui-form-car-insurance-road-transport-certificate');
                    if(!form.form('validate')) return false;
                    var data = form.serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['car/insurance/road-transport-certificate']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('操作成功',data.info,'info');
                                $('#easyui-dialog-car-insurance-index-road-transport-certificate').dialog('close');
                            }else{
                                $.messager.alert('操作失败',data.info,'error');
                            }
                        }
                    });
                }
            },{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui-dialog-car-insurance-index-road-transport-certificate').dialog('close');
                }
            }],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
        //初始化车辆二级维护记录管理窗口
        $('#easyui-window-car-insurance-second-maintenance-record').window({
            title: '车辆二级维护记录管理',
            width: 800,   
            height: 460,   
            modal: true,
            closed: true,
            collapsible: false,
            minimizable: false,
            maximizable: false,
            onClose: function(){
                $(this).window('clear');
            }                       
        });
        //初始化车辆交强险管理窗口
        $('#easyui-window-car-insurance-traffic-compulsory-insurance').window({
            title: '车辆交通强制险管理',
            width: 800,   
            height: 460,   
            modal: true,
            closed: true,
            collapsible: false,
            minimizable: false,
            maximizable: false,
            onClose: function(){
                $(this).window('clear');
            }                       
        });
        //初始化车辆商业险管理窗口
        $('#easyui-window-car-insurance-business-insurance').window({
            title: '车辆商业保险管理',
            width: 800,   
            height: 460,   
            modal: true,
            closed: true,
            collapsible: false,
            minimizable: false,
            maximizable: false,
            onClose: function(){
                $(this).window('clear');
            }                       
        });
      	//初始化车辆其它险管理窗口
        $('#easyui-window-car-insurance-other-insurance').window({
            title: '车辆其它险管理',
            width: 800,   
            height: 460,   
            modal: true,
            closed: true,
            collapsible: false,
            minimizable: false,
            maximizable: false,
            onClose: function(){
                $(this).window('clear');
            }                       
        });
      	//初始化车辆出险理赔管理窗口
        $('#easyui-window-car-insurance-claim').window({
            title: '车辆出险理赔管理',
            width: 800,   
            height: 460,   
            modal: true,
            closed: true,
            collapsible: false,
            minimizable: false,
            maximizable: false,
            onClose: function(){
                $(this).window('clear');
            }                       
        });
      	//初始化归属客户
        $('#easyui-form-car-insurance-customerCombogrid').combogrid({
            panelWidth: 450,
            panelHeight: 200,
            required: true,
            missingMessage: '请输入检索后从下拉列表里选择一项！',
            onHidePanel:function(){
                var _combogrid = $(this);
                var value = _combogrid.combogrid('getValue');
                var text = _combogrid.combogrid('textbox').val();
                var row = _combogrid.combogrid('grid').datagrid('getSelected');
                if(!row){ //没有选择表格行但输入有检索字符串时，提示并清除检索字符串
                    if(text && value == text){
                        $.messager.show(
                            {
                                title: '无效值',
                                msg:'【' + text + '】不是有效值！请重新输入检索后，从下拉列表里选择一项！'
                            }
                        );
                        _combogrid.combogrid('clear');
                    }
                }
            },
            delay: 800,
            mode:'remote',
            idField: 'value',
            textField: 'text',
            url: '<?= yii::$app->urlManager->createUrl(['car/insurance/get-customers']); ?>',
            method: 'get',
            scrollbarSize:0,
            pagination: false,
            pageSize: 10,
            pageList: [10,20,30],
            fitColumns: true,
            rownumbers: true,
            onSelect: function(){
                searchForm.submit();
            },
            columns: [[
				{field:'value',title:'归属客户key',width:40,align:'center',hidden:true},
                {field:'text',title:'归属客户',width:150,align:'center'}
            ]]
        });
	}
	CarInsuranceIndex.init();

	
	//获取选择的记录
    //参数all = true标示是否要返回所有被选择的记录
	CarInsuranceIndex.getSelected = function(all){
		var datagrid = $('#easyui-datagrid-car-insurance-index');
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
	//添加方法
	CarInsuranceIndex.add = function(){
		$('#easyui-dialog-car-insurance-index-add').dialog('open');
		$('#easyui-dialog-car-insurance-index-add').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/insurance/add']); ?>");
	}
	//查看
	CarInsuranceIndex.scan = function(id){
		if(!id){
			var selectRow = this.getSelected();
			if(!selectRow){
				return false;
			}
	        id = selectRow.id;
		}
		$('#easyui-window-car-insurance-index-scan').window('open');
		$('#easyui-window-car-insurance-index-scan').window('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/insurance/scan']); ?>&id="+id);
	}
	//修改
	CarInsuranceIndex.edit = function(id){
		if(!id){
			var selectRow = this.getSelected();
            if(!selectRow){
                return false;
            }
            id = selectRow.id;
		}
		$('#easyui-dialog-car-insurance-index-edit').dialog('open');
		$('#easyui-dialog-car-insurance-index-edit').dialog('refresh','<?php echo yii::$app->urlManager->createUrl(['car/insurance/edit']); ?>&id='+id);
	}
	//删除
	CarInsuranceIndex.remove = function(){
		var selectRow = this.getSelected();
        if(!selectRow) return false;
        var id = selectRow.id;
		$.messager.confirm('确定删除','您确定要删除该汽车数据？',function(r){
			if(r){
				$.ajax({
					type: 'get',
					url: '<?php echo yii::$app->urlManager->createUrl(['car/insurance/remove']); ?>',
					data: {id: id},
					dataType: 'json',
					success: function(data){
						if(data.stauts){
							$.messager.alert('删除成功',data.info,'info');   
							$('#easyui-datagrid-car-insurance-index').datagrid('reload');
						}else{
							$.messager.alert('删除失败',data.info,'error');   
						}
					}
				});
			}
		});
	}
    //交强险管理
    CarInsuranceIndex.trafficCompulsoryInsurance = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var id = selectRow.id;
        $('#easyui-window-car-insurance-traffic-compulsory-insurance').window('open');
        $('#easyui-window-car-insurance-traffic-compulsory-insurance').window('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/insurance/traffic-compulsory-insurance']); ?>&carId="+id);
    }
    //商业险管理
    CarInsuranceIndex.businessInsurance = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var id = selectRow.id;
        $('#easyui-window-car-insurance-business-insurance').window('open');
        $('#easyui-window-car-insurance-business-insurance').window('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/insurance/business-insurance']); ?>&carId="+id);
    }
  	//其它险管理
    CarInsuranceIndex.otherInsurance = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var id = selectRow.id;
        $('#easyui-window-car-insurance-other-insurance').window('open');
        $('#easyui-window-car-insurance-other-insurance').window('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/insurance/other-insurance']); ?>&carId="+id);
    }
  	//出险理赔管理
    CarInsuranceIndex.insuranceClaim = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var id = selectRow.id;
        $('#easyui-window-car-insurance-claim').window('open');
        $('#easyui-window-car-insurance-claim').window('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/insurance-claim/index']); ?>&carId="+id);
    }
    //按条件导出车辆列表
    CarInsuranceIndex.exportWidthCondition = function(){
        var url = "<?php echo yii::$app->urlManager->createUrl(['car/insurance/export-width-condition']);?>";
        var form = $('#search-form-car-insurance-index');
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
    CarInsuranceIndex.resetForm = function(){
        var easyuiForm = $('#search-form-car-insurance-index');
        easyuiForm.form('reset');
    }
</script>