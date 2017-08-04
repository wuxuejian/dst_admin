<table id="easyui-datagrid-car-insurance-other-insurance"></table> 
<div id="easyui-datagrid-car-insurance-other-insurance-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-car-insurance-other-insurance">
                <ul class="search-main">
                    <li>
                        <div class="item-name">保险公司</div>
                        <div class="item-input">
                            <input name="insurer_company" style="width:200px;" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">保单号</div>
                        <div class="item-input">
                            <input name="number" style="width:200px">
                        </div>
                    </li>
                     <li>
                        <div class="item-name">保期</div>
                        <div class="item-input">
                            <input class="easyui-datebox" type="text" name="start_date" style="width:93px;"
                                   data-options="
                                        onChange:function(){
                                            $('#search-form-car-insurance-other-insurance').submit();
                                        }
                                   "
                                />
                            -
                            <input class="easyui-datebox" type="text" name="end_date" style="width:93px;"
                                   data-options="
                                        onChange:function(){
                                            $('#search-form-car-insurance-other-insurance').submit();
                                        }
                                   "
                                />
                        </div>
                    </li>
                    <li class="search-button">
                        <button class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button onclick="CarInsuranceOtherInsurance.resetForm();return false;" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
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
<div id="easyui-dialog-car-insurance-other-add"></div>
<div id="easyui-dialog-car-insurance-other-edit"></div>
<!-- 窗口 -->
<script>
    var CarInsuranceOtherInsurance = new Object();
    CarInsuranceOtherInsurance.init = function(){
        var easyuiDatagrid = $('#easyui-datagrid-car-insurance-other-insurance');
        //获取列表数据
        easyuiDatagrid.datagrid({  
            method: 'get', 
            url:"<?php echo yii::$app->urlManager->createUrl(['car/insurance/other-get-list','carId'=>$carId]); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-car-insurance-other-insurance-toolbar",
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
            columns:[[
				{
				    field: 'number',title: '保单号',
				    sortable: true
				},   
                {
                    field: 'insurer_company',title: '保险公司',width: 200,
                    sortable: true,
                    formatter: function(value){
                        var insurer_company = <?php echo json_encode($config['INSURANCE_COMPANY']); ?>;
                        if(insurer_company[value]){
                            return insurer_company[value].text;
                        }
                    }
                },
				{
                    field: 'insurance_text',title: '险种',width: 200,
                    sortable: true,
                    formatter: function(value){
						if(value=='' || value==null){
							return '';
						}
						var data = eval(value);
						var insurance_str='';
						for(var i=0;i<data.length;i++){
							insurance_str+=data[i][0]+'('+data[i][1]+'),';
						}
						return insurance_str;
                    }
                },
                {field: 'money_amount',title: '保险金额',sortable: true},
                {
                    field: 'start_date',title: '开始时间',width: 80,
                    align: 'center',sortable: true,
                    formatter: function(value){
                        if(!isNaN(value) && value > 0){
                            return formatDateToString(value);
                        }
                    }
                },
                {
                    field: 'end_date',title: '结束时间',width: 80,align: 'center',
                    sortable: true,
                    formatter: function(value){
                        if(!isNaN(value) && value > 0){
                            return formatDateToString(value);
                        }
                    }
                },
                {field: 'note',title: '备注',width: 200,align: 'left',sortable: true},
                {
                    field: 'add_datetime',title: '上次修改时间',width: 160,
                    align: 'center',sortable: true,
                    formatter: function(value){
                        if(!isNaN(value) && value > 0){
                            return formatDateToString(value,true);
                        }
                    }
                },
                {field: 'username',title: '操作人员',align: 'center',sortable: true}
            ]],
            onDblClickRow: function(rowIndex,rowData){
                CarInsuranceOtherInsurance.edit(rowData.id);
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
        var searchForm = $('#search-form-car-insurance-other-insurance');
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
        searchForm.find('input[name=number]').textbox({
            onChange: function(){
                searchForm.submit();
            }
        });
        //构建查询表单结束
        //初始化添加窗口
        $('#easyui-dialog-car-insurance-other-add').dialog({
            title: '添加其它险记录',   
            width: '615px',   
            height: '500px',   
            closed: true,   
            cache: true,   
            modal: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                	CarInsuranceOtherAdd.submitForm();
                }
            },{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui-dialog-car-insurance-other-add').dialog('close');
                }
            }],
            onClose: function(){
                $(this).dialog('clear');
            } 
        });
        //初始化修改窗口
        $('#easyui-dialog-car-insurance-other-edit').dialog({
            title: '修改其它险记录',   
            width: '615px',   
            height: '500px',   
            closed: true,   
            cache: true,   
            modal: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                	CarInsuranceOtherEdit.submitForm();
                }
            },{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui-dialog-car-insurance-other-edit').dialog('close');
                }
            }],
            onClose: function(){
                $(this).dialog('clear');
            } 
        });
    }
    CarInsuranceOtherInsurance.init();
    //获取选择的记录
    CarInsuranceOtherInsurance.getSelected = function(){
        var datagrid = $('#easyui-datagrid-car-insurance-other-insurance');
        var selectRow = datagrid.datagrid('getSelected');
        if(!selectRow){
            $.messager.alert('错误','请选择要操作的记录','error');   
            return false;
        }
        return selectRow;
    }
    //添加
    CarInsuranceOtherInsurance.add = function(){
        $('#easyui-dialog-car-insurance-other-add').dialog('open');
        $('#easyui-dialog-car-insurance-other-add').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/insurance/other-add','carId'=>$carId]); ?>");
    }
    //修改
    CarInsuranceOtherInsurance.edit = function(id){
        if(!id){
            var selectRow = this.getSelected();
            if(!selectRow){
                return false;
            }
            id = selectRow.id;
        }
        $('#easyui-dialog-car-insurance-other-edit').dialog('open');
        $('#easyui-dialog-car-insurance-other-edit').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/insurance/other-edit']); ?>&id="+id);
    }
  //按条件导出车辆列表
    CarInsuranceOtherInsurance.exportWidthCondition = function(){
        var url = "<?php echo yii::$app->urlManager->createUrl(['car/insurance/other-export']).'&carId='.$carId;?>";
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
    //删除
    CarInsuranceOtherInsurance.remove = function(){
        var selectRow = this.getSelected();
        if(!selectRow) return false;
        var id = selectRow.id;
        $.messager.confirm('确定删除','您确定要删除该条其它险记录？',function(r){
            if(r){
                $.ajax({
                    type: 'get',
                    url: "<?php echo yii::$app->urlManager->createUrl(['car/insurance/other-remove']); ?>",
                    data: {id: id},
                    dataType: 'json',
                    success: function(data){
                        if(data){
                            $.messager.alert('删除成功',data.info,'info');   
                            $('#easyui-datagrid-car-insurance-other-insurance').datagrid('reload');
                        }else{
                            $.messager.alert('删除失败',data.info,'error');   
                        }
                    }
                });
            }
        });
    }
    //重置查询表单
    CarInsuranceOtherInsurance.resetForm = function(){
        var easyuiForm = $('#search-form-car-insurance-other-insurance');
        easyuiForm.form('reset');
        easyuiForm.submit();
    }
  //下载附件
    CarInsuranceOtherInsurance.download  = function(){
		var selectRow = this.getSelected();
		if(!selectRow)  return false;
		window.open("<?php echo yii::$app->urlManager->createUrl(['car/insurance/other-download']);?>&id="+selectRow.id);
    }
</script>