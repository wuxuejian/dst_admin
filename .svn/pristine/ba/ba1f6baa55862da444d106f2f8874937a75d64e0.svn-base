<table id="easyui-datagrid-car-insurance-claim-log"></table> 
<div id="easyui-datagrid-car-insurance-claim-log-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-car-insurance-claim-log">
                <ul class="search-main">
                	<li>
                        <div class="item-name">车牌/车架/发动机</div>
                        <div class="item-input">
                            <input name="plate_number" style="width:200px;" />
                        </div>
                    </li>
                	<li>
                        <div class="item-name">出险日期</div>
                        <div class="item-input">
                            <input class="easyui-datebox" type="text" name="start_danger_date" style="width:93px;"
                                   data-options="
                                        onChange:function(){
                                            $('#search-form-car-insurance-claim-log').submit();
                                        }
                                   "
                                />
                            -
                            <input class="easyui-datebox" type="text" name="end_danger_date" style="width:93px;"
                                   data-options="
                                        onChange:function(){
                                            $('#search-form-car-insurance-claim-log').submit();
                                        }
                                   "
                                />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">报案人</div>
                        <div class="item-input">
                            <input name="people" style="width:200px">
                        </div>
                    </li>
                    <li>
                        <div class="item-name">报案电话</div>
                        <div class="item-input">
                            <input name="tel" style="width:200px">
                        </div>
                    </li>
                    <li>
                        <div class="item-name">保险公司</div>
                        <div class="item-input">
                            <input name="insurer_company" style="width:200px;" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">赔付险种</div>
                        <div class="item-input">
                            <input name="insurer_type" style="width:200px;" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">出险状态</div>
                        <div class="item-input">
                            <input name="status" style="width:200px;" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">赔付时间</div>
                        <div class="item-input">
                        	<input name="claim_time" style="width:200px">
                        </div>
                    </li>
                    <li>
                        <div class="item-name">财务转账时间</div>
                        <div class="item-input">
                        	<input name="transfer_time" style="width:200px">
                        </div>
                    </li>
					<li>
                        <div class="item-name">出险单号</div>
                        <div class="item-input">
                        	<input name="number" style="width:200px">
                        </div>
                    </li>
                    <li>
                        <div class="item-name">三者信息</div>
                        <div class="item-input">
                            <input name="wreckers" style="width:200px;" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">理赔金额</div>
                        <div class="item-input">
                            <input name="claim_amount_start" style="width:90px">
                            -
                            <input name="claim_amount_end" style="width:90px">
                        </div>
                    </li>
					<li class="item-name">
		                <div class="item-name">归属客户</div>
		                <div class="item-input">
		                    <input
		                        id="easyui-form-car-insurance-customerCombogrid2"
		                        name="customer"
		                        style="width:180px;"
		                        />
		                </div>
		            </li>
					 <li>
                        <div class="item-name">车辆运营公司</div>
                        <div class="item-input">
                            <input style="width:200px;" name="operating_company_id" />
                        </div>
                    </li>
					
                    <li class="search-button">
                        <button class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button onclick="CarInsuranceClaimLog.resetForm();return false;" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
                    </li>
                </ul>
            </form>
        </div>
    </div>
    <div class="easyui-panel" title="数据列表" style="padding:8px 4px;" data-options="
        iconCls: 'icon-tip',
        border: false
    ">
<!--        <a href="javascript:CarInsuranceClaimLog.scan()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查看详情</a> -->
<!--        <a href="javascript:CarInsuranceClaimLog.add()" class="easyui-linkbutton" data-options="iconCls:'icon-add'">添加记录</a> -->
<!--        <a href="javascript:CarInsuranceClaimLog.edit()" class="easyui-linkbutton" data-options="iconCls:'icon-edit'">修改记录</a> -->
<!--        <a href="javascript:CarInsuranceClaimLog.remove()" class="easyui-linkbutton" data-options="iconCls:'icon-remove'">删除记录</a> -->
<!--        <a href="javascript:CarInsuranceClaimLog.logon()" class="easyui-linkbutton" data-options="iconCls:'icon-remove'">注销记录</a> -->
<!--        <a href="javascript:CarInsuranceClaimLog.cancelLogon()" class="easyui-linkbutton" data-options="iconCls:'icon-remove'">取消注销记录</a> -->
<!--        <a href="javascript:CarInsuranceClaimLog.exportWidthCondition()" class="easyui-linkbutton" data-options="iconCls:'icon-excel'">导出Excel</a> -->
       
       <?php foreach($buttons as $val){ ?>
        <a href="javascript:<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon']; ?>'"><?= $val['text']; ?></a>
        <?php } ?>
    </div>
</div>
<!-- 窗口 -->
<div id="easyui-dialog-car-insurance-claim-log-add"></div>
<div id="easyui-dialog-car-insurance-claim-log-edit"></div>
<div id="easyui-dialog-car-insurance-claim-log-scan"></div>
<!-- 窗口 -->
<script>
    var insurer_company = <?php echo json_encode($config['INSURANCE_COMPANY']); ?>;
    var CarInsuranceClaimLog = new Object();
    CarInsuranceClaimLog.init = function(){
        var easyuiDatagrid = $('#easyui-datagrid-car-insurance-claim-log');
        //获取列表数据
        easyuiDatagrid.datagrid({  
            method: 'get', 
            url:"<?php echo yii::$app->urlManager->createUrl(['car/insurance-claim-log/get-list']); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-car-insurance-claim-log-toolbar",
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
				{field: 'step',title: '出险步骤',hidden: true}
            ]],
            columns:[
				[
					{field: 'number',title: '出险单号',width: 90,rowspan:2},
					{field: 'plate_number',title: '车牌号',width: 60,rowspan:2},
					{
						field: 'car_model',title: '车型名称',width: 60,align: 'left',rowspan:2,
						formatter: function(value){
							var car_type = <?php echo json_encode($config['car_model_name']); ?>;
							try{
								return car_type[value].text;
							}catch(e){
								return '';
							}
						}
					},
					{field: 'claim_customer_name',title: '所属客户',width: 120,align: 'left',rowspan:2},
					{title: '出险信息',colspan:5}, // 跨几列
					{title: '理赔信息',colspan:3},
					{field: 'status',title: '出险状态',width: 120,align: 'center',rowspan:2}, // 跨几行
					{field: 'last_update_time',title: '上次修改时间',width: 80,align: 'center',rowspan:2,sortable: true},
					{field: 'last_update_user',title: '操作帐号',width: 80,align: 'center',rowspan:2,sortable: true}
				],
               [
				{
				    field: 'danger_date',title: '出险日期',width: 70,
				    sortable: true
				},   
                {
                    field: 'people',title: '报案人',width: 50,
                    sortable: true
                },
				
                {field: 'tel',title: '报案电话',width: 80},
                {field: 'insurance_text',title: '保险公司',width: 150,
                    formatter: function(value){
                        if(value=='' || value==null){
                            return '';
                        }
                    	var data = eval(value);
                    	var insurance_company_str='';
                    	for(var i=0;i<data.length;i++){
							//alert(insurer_company[data[i].insurance_company].text);
                            if(insurer_company[data[i].insurance_company]){
                                insurance_company_str+=insurer_company[data[i].insurance_company].text+',';
                            }
                    		//insurance_company_str+=data[i].insurance_company+',';
                    	}
                    	return insurance_company_str;
                    }
                },
							//	 {field: 'operating_company_id', title: '车辆运营公司', width: 170, halign: 'center', sortable: true},
                {field: '_insurance_text',title: '赔付险种',width: 150,
                    formatter: function(value){
                    	if(value=='' || value==null){
                            return '';
                        }
                    	var data = eval(value);
                    	var insurance_company_str='';
                    	for(var i=0;i<data.length;i++){
                        	if(data[i].insurance==null){
                        		continue;
                        	}
                        	for(var j=0;j<data[i].insurance.length;j++){
                        		insurance_company_str+=data[i].insurance[j]+',';
                        	}
                    	}
                    	return insurance_company_str;
                    }
                },
                {field: 'claim_text',title: '赔付到帐时间',width: 80,
                    formatter: function(value){
                    	if(value=='' || value==null){
                            return '';
                        }
                    	var data = eval(value);
						var claim_times = "";
                    	for(var i=0;i<data.length;i++){
                        	for(var j=0;j<data[i].length;j++){
								//return claim_times;
                        		claim_times += data[i][j].claim_time+",";
                        	}
                    	}
						return claim_times;
                    }
                },
                {field: '_claim_text',title: '赔款金额',width: 60,
                    formatter: function(value){
                    	if(value=='' || value==null){
                            return '';
                        }
                    	var data = eval(value);
                    	var claim_amount=0;
                    	for(var i=0;i<data.length;i++){
                        	for(var j=0;j<data[i].length;j++){
                        		var a=/^[0-9]*(\.[0-9]{1,2})?$/;
                        		if(a.test(data[i][j].claim_amount) && data[i][j].claim_amount!=''){
                        			claim_amount += parseFloat(data[i][j].claim_amount);
                        		}
                        	}
                    	}
                    	return claim_amount;
                    }
                },
                {field: 'transfer_text',title: '财务转账时间',width: 80,
                    formatter: function(value){
                    	if(value=='' || value==null){
                            return '';
                        }
                    	var data = eval(value);
                    	var claim_amount=0;
                    	for(var i=0;i<data.length;i++){
                        	return data[i].transfer_time;
                    	}
                    }
                }
            ]],
            onDblClickRow: function(rowIndex,rowData){
            	CarInsuranceClaimLog.scan(rowData.id);
            },
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
        var searchForm = $('#search-form-car-insurance-claim-log');
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
            onChange: function(){
                searchForm.submit();
            }
        });
        searchForm.find('input[name=insurer_type]').combobox({
            valueField:'value',
            textField:'text',
            data: [{"value":"","text":"不限"},{"value":"车损险","text":"车损险"}
            ,{"value":"三者险","text":"三者险"},{"value":"司乘险","text":"司乘险"},{"value":"不计免赔险","text":"不计免赔险"},
            {"value":"玻璃险","text":"玻璃险"},{"value":"涉水险","text":"涉水险"},{"value":"盗抢险","text":"盗抢险"}],
            editable: false,
            onChange: function(){
                searchForm.submit();
            }
        });
        searchForm.find('input[name=status]').combobox({
            valueField:'value',
            textField:'text',
            data: [{"value":"","text":"不限"},{"value":"1","text":"1.已报案，等待查勘"}
            ,{"value":"2","text":"2.已查勘，等待定损"},{"value":"3","text":"3.已定损，维修中"},{"value":"4","text":"4.维修中，等待理赔"},
            {"value":"5","text":"5.已理赔，保险请款"},{"value":"6","text":"6.已请款，等待结案"},{"value":"7","text":"7.已结案"}],
            editable: false,
            onChange: function(){
                searchForm.submit();
            }
        });
        searchForm.find('input[name=wreckers]').combobox({
            valueField:'value',
            textField:'text',
            data: [{"value":"","text":"不限"},{"value":"2","text":"三者车"}
                ,{"value":"3","text":"三者物"},{"value":"4","text":"三者人"}],
            editable: false,
            onChange: function(){
                searchForm.submit();
            }
        });
        searchForm.find('input[name=claim_amount_start]').textbox({
            onChange: function(){
                searchForm.submit();
            }
        });
        searchForm.find('input[name=claim_amount_end]').textbox({
            onChange: function(){
                searchForm.submit();
            }
        });
        searchForm.find('input[name=people]').textbox({
            onChange: function(){
                searchForm.submit();
            }
        });
		searchForm.find('input[name=number]').textbox({
            onChange: function(){
                searchForm.submit();
            }
        });
        searchForm.find('input[name=claim_time]').textbox({
            onChange: function(){
                searchForm.submit();
            }
        });searchForm.find('input[name=transfer_time]').textbox({
            onChange: function(){
                searchForm.submit();
            }
        });
        searchForm.find('input[name=tel]').textbox({
            onChange: function(){
                searchForm.submit();
            }
        });
        searchForm.find('input[name=number]').textbox({
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
        //构建查询表单结束
        //初始化归属客户
        $('#easyui-form-car-insurance-customerCombogrid2').combogrid({
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
      //初始化查看窗口
		$('#easyui-dialog-car-insurance-claim-log-scan').window({
			title: '出险记录详情',
            width: '83%',   
            height: '83%',   
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
    }
    CarInsuranceClaimLog.init();
    //获取选择的记录
    CarInsuranceClaimLog.getSelected = function(all){
		var datagrid = $('#easyui-datagrid-car-insurance-claim-log');
        if(all){
            var selectRows = datagrid.datagrid('getSelections');
            if(selectRows.length <= 0){
               // $.messager.alert('错误','请选择要操作的记录','error');   
                return false;
            }
            return selectRows;
        }else{
            var selectRow = datagrid.datagrid('getSelected');
            if(!selectRow){
               // $.messager.alert('错误','请选择要操作的记录','error');   
                return false;
            }
            return selectRow;
        }
    }
    //添加
    CarInsuranceClaimLog.add = function(){
    	window.open('<?= yii::getAlias('@web'); ?>/claim/');
    }
  	//查看
	CarInsuranceClaimLog.scan = function(id){
		if(!id){
			var selectRow = this.getSelected();
			if(!selectRow){
				return false;
			}
	        id = selectRow.id;
		}
        $('#easyui-dialog-car-insurance-claim-log-scan').window('open');
		$('#easyui-dialog-car-insurance-claim-log-scan').window('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/insurance-claim-log/scan']); ?>&id="+id);
	}
    //修改
    CarInsuranceClaimLog.edit = function(){
            var selectRow = this.getSelected();
            if(!selectRow){
                return false;
            }
        var id = selectRow.id;
        var step = selectRow.step;
        
        window.open('<?= yii::getAlias('@web'); ?>/claim?id='+id+'&step='+step);
    }
    //删除
    CarInsuranceClaimLog.remove = function(){
        var selectRow = this.getSelected();
        if(!selectRow) return false;
        var id = selectRow.id;
        $.messager.confirm('确定删除','您确定要删除该条其它险记录？',function(r){
            if(r){
                $.ajax({
                    type: 'get',
                    url: "<?php echo yii::$app->urlManager->createUrl(['car/insurance-claim-log/remove']); ?>",
                    data: {id: id},
                    dataType: 'json',
                    success: function(data){
                        if(data){
                            $.messager.alert('删除成功',data.info,'info');   
                            $('#easyui-datagrid-car-insurance-claim-log').datagrid('reload');
                        }else{
                            $.messager.alert('删除失败',data.info,'error');   
                        }
                    }
                });
            }
        });
    }
  	//注销
    CarInsuranceClaimLog.logon = function(){
        var selectRow = this.getSelected();
        if(!selectRow) return false;
        var id = selectRow.id;
        $.messager.confirm('确定注销','您确定要注销该条记录？',function(r){
            if(r){
                $.ajax({
                    type: 'get',
                    url: "<?php echo yii::$app->urlManager->createUrl(['car/insurance-claim-log/logon']); ?>",
                    data: {id: id},
                    dataType: 'json',
                    success: function(data){
                        if(data){
                            $.messager.alert('注销成功',data.info,'info');   
                            $('#easyui-datagrid-car-insurance-claim-log').datagrid('reload');
                        }else{
                            $.messager.alert('注销失败',data.info,'error');   
                        }
                    }
                });
            }
        });
    }
    //取消注销
    CarInsuranceClaimLog.cancelLogon = function(){
        var selectRow = this.getSelected();
        if(!selectRow) return false;
        var id = selectRow.id;
        $.messager.confirm('确定取消注销','您确定要取消注销该条记录？',function(r){
            if(r){
                $.ajax({
                    type: 'get',
                    url: "<?php echo yii::$app->urlManager->createUrl(['car/insurance-claim-log/cancel-logon']); ?>",
                    data: {id: id},
                    dataType: 'json',
                    success: function(data){
                        if(data){
                            $.messager.alert('取消注销成功',data.info,'info');   
                            $('#easyui-datagrid-car-insurance-claim-log').datagrid('reload');
                        }else{
                            $.messager.alert('取消注销失败',data.info,'error');   
                        }
                    }
                });
            }
        });
    }
  	//按条件导出车辆列表
    CarInsuranceClaimLog.exportWidthCondition = function(){
		//检查是否导出指定车辆
		var selectRows = this.getSelected(true);
        if(!selectRows){
            var url = "<?php echo yii::$app->urlManager->createUrl(['car/insurance-claim-log/export-width-condition']);?>";
			var form = $('#search-form-car-insurance-claim-log');
			var data = {};
			var searchCondition = form.serializeArray();
			for(var i in searchCondition){
				data[searchCondition[i]['name']] = searchCondition[i]['value'];
			}
			for(var i in data){
				url += '&'+i+'='+data[i];
			}
			window.open(url);
        }else {
			var id = '';
			for(var i in selectRows){
				id += selectRows[i].id+',';
			}
			window.open("<?php echo yii::$app->urlManager->createUrl(['car/insurance-claim-log/export-choose']);?>&id="+id);
		}
    }
	
    //重置查询表单
    CarInsuranceClaimLog.resetForm = function(){
        var easyuiForm = $('#search-form-car-insurance-claim-log');
        easyuiForm.form('reset');
        easyuiForm.submit();
    }
</script>