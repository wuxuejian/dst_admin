<table id="easyui-datagrid-customer-contract-record-car-manage"></table> 
<div id="easyui-datagrid-customer-contract-record-car-manage-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-customer-contract-record-car-manage">
                <ul class="search-main">
                    <li>
                        <div class="item-name">车牌号</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="plate_number" style="width:150px;" />
                        </div>
                    </li>
                    <li class="search-button">
                        <a id="btn" href="javascript:CustomerContractRecordCarManage.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                    </li>
                </ul>
            </form>
        </div>
    </div>
    <?php if($buttons){ ?>
    <div class="easyui-panel" title="数据列表" style="padding:3px 2px;width:100%;" data-options="
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
<div id="easyui-window-customer-contract-record-car-manage-back-car"></div>
<!-- 窗口 -->
<form id="customer-contract-record-car-manage-submit-data" style="display:none"></form>
<script>
    var CustomerContractRecordCarManage = new Object();
    CustomerContractRecordCarManage.init = function(){
        //获取列表数据
        $('#easyui-datagrid-customer-contract-record-car-manage').datagrid({  
            method: 'get', 
            url:"<?php echo yii::$app->urlManager->createUrl(['customer/contract-record/get-car-list','contractId'=>$contractInfo['id']]); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-customer-contract-record-car-manage-toolbar",
            pagination: true,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: false,
            showFooter: true,
            frozenColumns: [[
                {field: 'ck',checkbox: true}, 
                {field: 'id',title: 'id',hidden: true}
            ]],
            columns:[[
                {
                    field: 'plate_number',title: '车牌号',width: 100,sortable: true,
                    editor:{
                        type:'combobox',
                        options:{
                            valueField:'plate_number',
                            textField:'plate_number',
                            data: <?php echo json_encode($stockCars); ?>,
                            required: true
                        }
                    }
                },
                {
                    field: 'month_rent',title: '月租金',width: 100, align: 'right',
                    sortable: true,
                    editor:{
                        type:'textbox',
                        options:{
                            validType: 'money'
                        }
                    }
                },
                {
                    field: 'let_time',title: '起租时间',width: 125, align: 'center',
                    sortable: true,formatter: function(value){
                        if(!isNaN(value) && value != 0){
                            return formatDateToString(value);
                        }
                    },
                    editor:{
                        type:'datebox',
                        options:{
                            required: true,
                            validType: 'date'
                        }
                    }
                },
                {
                    field: 'back_time',title: '还车时间',width: 125, align: 'center',
                    sortable: true,formatter: function(value){
                        if(!isNaN(value) && value != 0){
                            return formatDateToString(value);
                        }
                    }
                },
                {
                    field: 'note',title: '备注',width: 300,align: 'left',
                    editor:{
                        type:'textbox',
                        options:{
                            validType: 'length[255]'
                        }
                    }
                }
            ]]
        });
        //初始化归还窗口
        $('#easyui-window-customer-contract-record-car-manage-back-car').dialog({
            title: '&nbsp;归还车辆',
            iconCls:'icon-add', 
            width: '300',   
            height: '100',   
            closed: true,   
            cache: true,   
            modal: true,
            maximizable: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                	var form = $('#easyui-form-customer-contract-record-car-manage-back-car');
                	CustomerContractRecordCarManage.backCar2(form.find('input[name=back_time]').val());
                	$('#easyui-window-customer-contract-record-car-manage-back-car').dialog('close');
                }
            },{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui-window-customer-contract-record-car-manage-back-car').dialog('close');
                }
            }],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
    }
    //获取选择的记录
    CustomerContractRecordCarManage.getSelected = function(multiple){
        var datagrid = $('#easyui-datagrid-customer-contract-record-car-manage');
        if(multiple){
            selectRows = datagrid.datagrid('getSelections');
            if(selectRows.length <= 0){
                $.messager.alert('错误','请选择要操作的记录！','error');   
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
    //添加签约车辆
    CustomerContractRecordCarManage.carAdd = function(){
        var datagrid = $('#easyui-datagrid-customer-contract-record-car-manage');
        datagrid.datagrid('appendRow',{       
            id: '0',
            plate_number: '',
            let_time: '',
            back_time: '',
            note: ''
        });
        var rows = datagrid.datagrid('getRows');
        var lastRowNum = rows.length - 1;
        var lastRow = rows[lastRowNum];
        var rowIndex = datagrid.datagrid('getRowIndex',lastRow);
        datagrid.datagrid('beginEdit',rowIndex);
        datagrid.datagrid('selectRow',rowIndex);
    }
    //修改签约车辆
    CustomerContractRecordCarManage.carEdit = function(){
        var selectRows = this.getSelected(true);
        if(!selectRows) return false;
        var datagrid = $('#easyui-datagrid-customer-contract-record-car-manage');
        for(var i in selectRows){
            var rowIndex = datagrid.datagrid('getRowIndex',selectRows[i]);
            datagrid.datagrid('beginEdit',rowIndex);//开启行编辑
            if(selectRows[i].id){
                //原记录的车辆号不允许修改
                var editor = datagrid.datagrid('getEditor',{"index": rowIndex,"field": "plate_number"});
                editor.target.combobox('disable');
            }
        }
    }
    //保存修改
    CustomerContractRecordCarManage.saveEdit = function()
    {
        var selectRows = this.getSelected(true);
        if(!selectRows) return false;
        var datagrid = $('#easyui-datagrid-customer-contract-record-car-manage');
        
        for(var i in selectRows){
            datagrid.datagrid('endEdit',datagrid.datagrid('getRowIndex',selectRows[i]));
        }
        var selectRows = this.getSelected(true);
        var html = '<input type="text" name="contract_id" value="<?php echo $contractInfo["id"]; ?>" />';
        html += '<input type="text" name="cCustomer_id" value="<?php echo $contractInfo["cCustomer_id"]; ?>" />';
        for(var i in selectRows){
            if(selectRows[i].plate_number){
                html += '<input type="text" name="id[]" value="'+selectRows[i].id+'" />';
                html += '<input type="text" name="plate_number[]" value="'+selectRows[i].plate_number+'" />';
                html += '<input type="text" name="month_rent[]" value="'+selectRows[i].month_rent+'" />';
                if(!isNaN(selectRows[i].let_time)){
                    html += '<input type="text" name="let_time[]" value="'+formatDateToString(selectRows[i].let_time)+'" />';
                }else{
                    html += '<input type="text" name="let_time[]" value="'+selectRows[i].let_time+'" />';
                }
                
                html += '<input type="text" name="note[]" value="'+selectRows[i].note+'" />';
            }
        }
        var form = $('#customer-contract-record-car-manage-submit-data');
        form.html(html);
        var data = form.serialize();
        $.ajax({
            type: 'post',
            url: "<?php echo yii::$app->urlManager->createUrl(['customer/contract-record/add-edit-car']); ?>",
            data: data,
            dataType: 'json',
            success: function(data){
                if(data.status){
                    $.messager.alert('操作成功',data.info,'info');
                    datagrid.datagrid('reload');
                }else{
                    $.messager.alert('操作失败',data.info,'error');
                }
            }
        });
        
    }
  	//归还车辆
    CustomerContractRecordCarManage.backCar = function(){
        $('#easyui-window-customer-contract-record-car-manage-back-car').dialog('open');
        $('#easyui-window-customer-contract-record-car-manage-back-car').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['customer/contract-record/back-car-window']); ?>");
    }
    //归还车辆
    CustomerContractRecordCarManage.backCar2 = function(back_time){
        var selectRows = this.getSelected(true);
        if(!selectRows) return false;
        var id = '';
        for(var i in selectRows){
            id += selectRows[i].id + ',';
        }
        $.messager.confirm('操作确认','您确定要归还所选车辆？',function(r){
            if(r){
                $.ajax({
                    type: 'post',
                    url: "<?php echo yii::$app->urlManager->createUrl(['customer/contract-record/car-back']); ?>",
                    data: {"id": id,"back_time":back_time},
                    dataType: 'json',
                    success: function(data){
                        if(data.status){
                            $.messager.alert('归还成功',data.info,'info');
                            $('#easyui-datagrid-customer-contract-record-car-manage').datagrid('reload');
                        }else{
                            $.messager.alert('归还失败',data.info,'error');
                        }
                    }
                });
            }
        });
    }
    //查询
    CustomerContractRecordCarManage.search = function(){
        var form = $('#search-form-customer-contract-record-car-manage');
        var data = {};
        var searchCondition = form.serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        $('#easyui-datagrid-customer-contract-record-car-manage').datagrid('load',data);
    }
    //执行初始化
    CustomerContractRecordCarManage.init();
</script>