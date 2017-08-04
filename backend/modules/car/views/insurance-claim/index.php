<table id="easyui-datagrid-car-insurance-claim"></table> 
<div id="easyui-datagrid-car-insurance-claim-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-car-insurance-claim">
                <ul class="search-main">
                	<li>
                        <div class="item-name">出险日期</div>
                        <div class="item-input">
                            <input class="easyui-datebox" type="text" name="start_danger_date" style="width:93px;"
                                   data-options="
                                        onChange:function(){
                                            $('#search-form-car-insurance-claim').submit();
                                        }
                                   "
                                />
                            -
                            <input class="easyui-datebox" type="text" name="end_danger_date" style="width:93px;"
                                   data-options="
                                        onChange:function(){
                                            $('#search-form-car-insurance-claim').submit();
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
                    <li class="search-button">
                        <button class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button onclick="CarInsuranceClaim.resetForm();return false;" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
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
<div id="easyui-dialog-car-insurance-claim-add"></div>
<div id="easyui-dialog-car-insurance-claim-edit"></div>
<div id="easyui-dialog-car-insurance-claim-scan"></div>
<!-- 窗口 -->
<script>
    var CarInsuranceClaim = new Object();
    CarInsuranceClaim.init = function(){
        var easyuiDatagrid = $('#easyui-datagrid-car-insurance-claim');
        //获取列表数据
        easyuiDatagrid.datagrid({  
            method: 'get', 
            url:"<?php echo yii::$app->urlManager->createUrl(['car/insurance-claim/get-list','carId'=>$carId]); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-car-insurance-claim-toolbar",
            pagination: true,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
            frozenColumns: [[
                {field: 'ck',checkbox: true}, 
                {field: 'id',title: 'id',hidden: true},
				{field: 'step',title: '出险步骤',hidden: true}
            ]],
            columns:[
				[
					{title: '出险信息',colspan:5}, // 跨几列
					{title: '理赔信息',colspan:3},
					{field: 'status',title: '出险状态',width: 80,align: 'center',rowspan:2,sortable: true}, // 跨几行
					{field: 'last_update_time',title: '上次修改时间',width: 80,align: 'center',rowspan:2,sortable: true},
					{field: 'last_update_user',title: '操作帐号',width: 80,align: 'center',rowspan:2,sortable: true}
				],
               [
				{
				    field: 'danger_date',title: '出险日期',width: 100,
				    sortable: true
				},   
                {
                    field: 'people',title: '报案人',width: 200,
                    sortable: true
                },
                {field: 'tel',title: '报案电话',width: 100},
                {field: 'insurance_text',title: '保险公司',width: 100,
                    formatter: function(value){
                    	var data = eval(value);
                    	var insurance_company_str='';
                    	for(var i=0;i<data.length;i++){
                    		insurance_company_str+=data[i].insurance_company+',';
                    	}
                    	return insurance_company_str;
                    }
                },
                {field: '_insurance_text',title: '赔付险种',width: 100,
                    formatter: function(value){
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
                {field: 'claim_text',title: '赔付到帐时间',width: 100,
                    formatter: function(value){
                    	if(value=='' || value==null){
                            return '';
                        }
                    	var data = eval(value);
                    	for(var i=0;i<data.length;i++){
                        	for(var j=0;j<data[i].length;j++){
                        		return data[i][j].claim_time;
                        	}
                    	}
                    }
                },
                {field: '_claim_text',title: '赔款金额',width: 100,
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
                {field: 'transfer_text',title: '财务转账时间',width: 100,
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
                CarInsuranceClaim.edit(rowData.id);
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
        var searchForm = $('#search-form-car-insurance-claim');
        searchForm.submit(function(){
            var data = {};
            var searchCondition = $(this).serializeArray();
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = searchCondition[i]['value'];
            }
            easyuiDatagrid.datagrid('load',data);
            return false;
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
            data: [{"value":"","text":"不限"},{"value":"1","text":"报案出险"}
            ,{"value":"2","text":"勘查结论"},{"value":"3","text":"保险定损"},{"value":"4","text":"车辆维修"},
            {"value":"5","text":"保险理赔"},{"value":"6","text":"保险请款"},{"value":"7","text":"转账结案"}],
            editable: false,
            onChange: function(){
                searchForm.submit();
            }
        });
        searchForm.find('input[name=people]').textbox({
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
        //构建查询表单结束
        
      //初始化查看窗口
		$('#easyui-dialog-car-insurance-claim-scan').window({
			title: '查看详情',
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
    CarInsuranceClaim.init();
    //获取选择的记录
    CarInsuranceClaim.getSelected = function(){
        var datagrid = $('#easyui-datagrid-car-insurance-claim');
        var selectRow = datagrid.datagrid('getSelected');
        if(!selectRow){
            $.messager.alert('错误','请选择要操作的记录','error');   
            return false;
        }
        return selectRow;
    }
    //添加
    CarInsuranceClaim.add = function(){
    	window.open('<?= yii::getAlias('@web'); ?>/claim/');
    }
  	//查看
	CarInsuranceClaim.scan = function(){
		var selectRow = this.getSelected();
		if(!selectRow){
			return false;
		}
        var id = selectRow.id;
        $('#easyui-dialog-car-insurance-claim-scan').window('open');
		$('#easyui-dialog-car-insurance-claim-scan').window('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/insurance-claim/scan']); ?>&id="+id);
	}
    //修改
    CarInsuranceClaim.edit = function(id){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
	    var id = selectRow.id;
	    var step = selectRow.step;
	    
	    window.open('<?= yii::getAlias('@web'); ?>/claim?id='+id+'&step='+step);
    }
    //删除
    CarInsuranceClaim.remove = function(){
        var selectRow = this.getSelected();
        if(!selectRow) return false;
        var id = selectRow.id;
        $.messager.confirm('确定删除','您确定要删除该条其它险记录？',function(r){
            if(r){
                $.ajax({
                    type: 'get',
                    url: "<?php echo yii::$app->urlManager->createUrl(['car/insurance-claim/remove']); ?>",
                    data: {id: id},
                    dataType: 'json',
                    success: function(data){
                        if(data){
                            $.messager.alert('删除成功',data.info,'info');   
                            $('#easyui-datagrid-car-insurance-claim').datagrid('reload');
                        }else{
                            $.messager.alert('删除失败',data.info,'error');   
                        }
                    }
                });
            }
        });
    }
  	//按条件导出车辆列表
    CarInsuranceClaim.exportWidthCondition = function(){
        var url = "<?php echo yii::$app->urlManager->createUrl(['car/insurance-claim/export-width-condition']).'&carId='.$carId;?>";
        var form = $('#search-form-car-insurance-claim');
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
    CarInsuranceClaim.resetForm = function(){
        var easyuiForm = $('#search-form-car-insurance-claim');
        easyuiForm.form('reset');
        easyuiForm.submit();
    }
</script>