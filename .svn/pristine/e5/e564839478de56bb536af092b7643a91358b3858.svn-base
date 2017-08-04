<table id="easyui-datagrid-car-car-back"></table> 
<div id="easyui-datagrid-car-car-back-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-car-car-back">
                <input id="is_db" type="hidden" name="is_db" value="">
                <ul class="search-main">
                	<li>
                        <div class="item-name">退车编号</div>
                        <div class="item-input">
                            <input name="number" style="width:200px">
                        </div>
                    </li>
					<li>
                        <div class="item-name">车牌号</div>
                        <div class="item-input">
                            <input name="plate_number" style="width:200px">
                        </div>
                    </li>
                    <li>
                        <div class="item-name">联系方式</div>
                        <div class="item-input">
                            <input name="customer_tel" style="width:200px">
                        </div>
                    </li>
                    <li>
                        <div class="item-name">退车状态</div>
                        <div class="item-input">
                            <input name="state" style="width:200px">
                        </div>
                    </li>
                    <li>
                        <div class="item-name">客户名称</div>
                        <div class="item-input">
                            <input name="customer_name" style="width:200px">
                        </div>
                    </li> 
                    <li>
                        <div class="item-name">客户类型</div>
                        <div class="item-input">
                            <input name="customer_type" style="width:200px">
                        </div>
                    </li> 
                    <li>
                        <div class="item-name">流程发起时间</div>
                        <div class="item-input">
                            <input class="easyui-datebox" type="text" name="start_add_time" style="width:93px;"
                                   data-options="
                                        onChange:function(){
                                            $('#search-form-car-car-back').submit();
                                        }
                                   "
                                />
                            -
                            <input class="easyui-datebox" type="text" name="end_add_time" style="width:93px;"
                                   data-options="
                                        onChange:function(){
                                            $('#search-form-car-car-back').submit();
                                        }
                                   "
                                />
                        </div>
                    </li>
                    <li class="search-button">
                        <button class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button onclick="CarBack.resetForm();return false;" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
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

        <a href="javascript:CarBack.db()" class="easyui-linkbutton" data-options="iconCls:'icon-tip'">待办（<font color='red'><?=$db_num?></font>）</a>
    </div>
    <?php } ?>
</div>
<!-- 窗口 -->
<div id="easyui-dialog-car-car-back-add"></div>
<div id="easyui-dialog-car-car-back-edit"></div>
<div id="easyui-dialog-car-car-back-scan"></div>
<!-- 窗口 -->
<script>
    var CarBack = new Object();
    CarBack.init = function(){
        var easyuiDatagrid = $('#easyui-datagrid-car-car-back');
        //获取列表数据
        easyuiDatagrid.datagrid({  
            method: 'get', 
            url:"<?php echo yii::$app->urlManager->createUrl(['car/car-back/get-list']); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-car-car-back-toolbar",
            pagination: true,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
            frozenColumns: [[
                {field: 'ck',checkbox: true}, 
                {field: 'id',title: 'id',hidden: true}
            ]],
            columns:[
               [
				{
				    field: 'number',title: '退车编号',width: 100
				},
                {
                    field: 'company_name',title: '客户名称',width: 100,
                    formatter: function(value,row,index){ //企业/个人客户名称
                        if(row.company_name){
                            return row.company_name;
                        }else if(row.id_name){
                            return row.id_name;
                        }else if(row.other_customer_name){
                            return row.other_customer_name;
                        }else{
                            return '';
                        }
                    }
                },
                {
                    field: 'company_type',title: '客户类型',width: 100,
                    formatter: function(value,row,index){ //企业/个人客户名称
                        if(row.id_name){
                            return '个人客户';
                        }else if(row.company_type == 1){
                            return "渠道";
                        }else if(row.company_type == 2){
                            return "大客户";
                        }else if(row.company_type == 3){
                            return "B端网点";
                        }else{
                            return '';
                        }
                    }
                },
                {
                    field: 'customer_tel',title: '联系方式',width: 100
                },
                {
                    field: 'car_ids',title: '退车数量',width: 100,
                    formatter: function(value,row,index){
						if(row.contract_text=="" || row.contract_text==null){
							return "";
						}
						var contracts = JSON.parse(row.contract_text);
						var car_num=0;
						
						for(var i=0;i<contracts.length;i++){
							if(contracts[i].car_ids==""){
								continue;
							}
							car_num += contracts[i].car_ids.split(",").length;
						}
						return car_num;
                    }
                },
                {
                    field: 'contract_number',title: '合同编号',width: 100,
					formatter: function(value,row,index){
						if(row.contract_text=="" || row.contract_text==null){
							return "";
						}
						var contracts = JSON.parse(row.contract_text);
						var contract_numbers = '';
						for(var i=0;i<contracts.length;i++){
							contract_numbers += contracts[i].contract_number+',';
						}
						return contract_numbers;
                    }
                },
                {
                    field: 'state',title: '退车状态',width: 180,
                    formatter: function(value,row,index){
                        var states = {"":"","1":"1.客户退车，等待销售沟通","2":"2.确定退车，等待领导审批","3":"3.同意退车，等待售后验车","4":"4.已验车，等待入库","5":"5.已入库，等待商务核算","6":"6.已核算，等待审批","7":"7.核算审批通过，等待财务确认","8":"8.财务确认，终止合同书","9":"9.已归档","20":"2.客户取消退车","21":"3.退车申请被驳回","22":"7.核算驳回"};
                        if(value == 20){
                            return "<font color='red'>"+states[value]+"</font>"+"："+row.cancel_back_cause;
                        }else if(value == 21){
                            return "<font color='red'>"+states[value]+"</font>"+"："+row.reject_cause;
                        }else if(value == 22){
                            return "<font color='red'>"+states[value]+"</font>"+"："+row.reject_cause2;
                        }
                        return states[value];
                    }
                },
           /*     {
                    field: 'oper_time1',title: '倒计时',width: 100
                },*/

                {field: 'time_status',title: '倒计时',width: 100,align: 'center',sortable: true,
                     formatter: function (value, row, index) {
                           return value;
                      }
                 },

                {
                    field: 'add_time',title: '流程发起时间',width: 120,
                    formatter: function(value){
                        if(!isNaN(value) && value >0){
                            return formatDateToString(value,true);
                        }
                    }
                },
                {
                    field: 'back_cause',title: '退车原因',width: 100
                },
                {
                    field: 'extract_car_site_name',title: '退车站场',width: 100
                },
                {
                    field: 'extract_car_site_id',title: '场地负责人',width: 100,
					formatter: function(value){
						var extract_car_sites = <?=json_encode($extract_car_site_map)?>;
                        try{
							var names = '';
							for(var i in extract_car_sites[value]){
								names += extract_car_sites[value][i].name+',';
							}
                            return names;
                        }catch(e){
                            return '';
                        }
                    }
                },
                {
                    field: 'last_update_user',title: '上一次操作人',width: 100
                },
                {
                    field: 'last_update_time',title: '上一次操作时间',width: 100,
                    formatter: function(value){
                        if(!isNaN(value) && value >0){
                            return formatDateToString(value,true);
                        }
                    }
                }
            ]],
            onDblClickRow: function(rowIndex,rowData){
                CarBack.edit(rowData.id);
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
        var searchForm = $('#search-form-car-car-back');
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
        searchForm.find('input[name=customer_tel]').textbox({
            onChange: function(){
                searchForm.submit();
            }
        });
		searchForm.find('input[name=plate_number]').textbox({
            onChange: function(){
                searchForm.submit();
            }
        });
        searchForm.find('input[name=state]').combobox({
            valueField:'value',
            textField:'text',
            data: [{"value":"","text":"不限"},{"value":"1","text":"1.客户退车，等待销售沟通"},{"value":"2","text":"2.确定退车，等待领导审批"},{"value":"20","text":"(2).客户取消退车"},
				{"value":"3","text":"3.同意退车，等待售后验车"},{"value":"21","text":"(3).退车申请被驳回"},{"value":"4","text":"4.已验车，等待入库"},{"value":"5","text":"5.已入库，等待商务核算"},
				{"value":"6","text":"6.已核算，等待审批"},{"value":"7","text":"7.核算审批通过，等待财务确认"},{"value":"22","text":"(7).核算驳回"},{"value":"8","text":"8.财务确认，终止合同书"},
				{"value":"9","text":"9.已归档"}],
            editable: false,
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
            data: [{"value":"","text":"不限"},{"value":"1","text":"渠道"}
            ,{"value":"2","text":"大客户"},{"value":"3","text":"B端网点"},{"value":"4","text":"个人"}],
            editable: false,
            onChange: function(){
                searchForm.submit();
            }
        });
        //构建查询表单结束
        
      //初始化查看窗口
		$('#easyui-dialog-car-car-back-scan').window({
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
    CarBack.init();
    //获取选择的记录
    CarBack.getSelected = function(){
        var datagrid = $('#easyui-datagrid-car-car-back');
        var selectRow = datagrid.datagrid('getSelected');
        if(!selectRow){
            $.messager.alert('错误','请选择要操作的记录','error');   
            return false;
        }
        return selectRow;
    }
    //添加
    CarBack.add = function(){
        window.open('<?= yii::getAlias('@web'); ?>/car_back/index1.html');
    }
    //代办
    CarBack.db = function(){
        $("#is_db").val(1);
        var searchForm = $('#search-form-car-car-back');
        searchForm.submit();
    }
  	//查看
	CarBack.scan = function(){
		var selectRow = this.getSelected();
		if(!selectRow){
			return false;
		}
        var id = selectRow.id;
        $('#easyui-dialog-car-car-back-scan').window('open');
		$('#easyui-dialog-car-car-back-scan').window('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/car-back/scan']); ?>&id="+id);
	}
    //修改
    CarBack.edit = function(id){
        if(!id){
            var selectRow = this.getSelected();
            if(!selectRow){
                return false;
            }
            id = selectRow.id;
        }
        var state = selectRow.state;
        var page = state==9?9:(parseInt(state)+1);
        
        if(page==21 || page==22){
            page = 2;
        }else if(page==23){
            page = 6;
        }
        window.open('<?= yii::getAlias('@web'); ?>/car_back/index'+page+'.html?id='+id+'&state='+state);
    }
    //删除
    CarBack.remove = function(){
        var selectRow = this.getSelected();
        if(!selectRow) return false;
        var id = selectRow.id;
        $.messager.confirm('确定删除','您确定要删除该条其它险记录？',function(r){
            if(r){
                $.ajax({
                    type: 'get',
                    url: "<?php echo yii::$app->urlManager->createUrl(['car/car-back/remove']); ?>",
                    data: {id: id},
                    dataType: 'json',
                    success: function(data){
                        if(data){
                            $.messager.alert('删除成功',data.info,'info');   
                            $('#easyui-datagrid-car-car-back').datagrid('reload');
                        }else{
                            $.messager.alert('删除失败',data.info,'error');   
                        }
                    }
                });
            }
        });
    }
  	//按条件导出车辆列表
    CarBack.export = function(){
        var url = "<?php echo yii::$app->urlManager->createUrl(['car/car-back/export']);?>";
        var form = $('#search-form-car-car-back');
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
    CarBack.resetForm = function(){
        var easyuiForm = $('#search-form-car-car-back');
        easyuiForm.form('reset');
        easyuiForm.submit();
    }
</script>