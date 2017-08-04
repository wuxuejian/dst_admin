<form id="easyui-form-car-contract-record-car-add" class="easyui-form" style="height:100%;">
    <input type="hidden" name="contractId" value="<?php echo $contractId; ?>" />
    <table id="easyui-datagrid-car-contract-record-car-add"></table>
    <div id="easyui-datagrid-car-contract-record-car-add-toolbar">
        <a href="javascript:CarContractRecordCarAdd.add()" class="easyui-linkbutton" data-options="iconCls:'icon-add'">添加</a>
        <a href="javascript:CarContractRecordCarAdd.remove()" class="easyui-linkbutton" data-options="iconCls:'icon-remove'">删除</a>
    </div>
    <div style="display:none" id="car-contract-record-car-add-car-item"></div>
</form>
<script>
    var CarContractRecordCarAdd = new Object();
    CarContractRecordCarAdd.init = function(){
        //初始化datagrid
        $('#easyui-datagrid-car-contract-record-car-add').datagrid({   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-car-contract-record-car-add-toolbar",
            pagination: false,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
            showFooter: true,
            frozenColumns: [[
                {field: 'ck',checkbox: true}
            ]],
            columns:[[
                {
                    
                    field: 'plate_number',title:'车牌号',width: '100px',
                    editor:{
                        type:'combobox',
                        options:{
                            valueField:'plate_number',
                            textField:'plate_number',
                            data: <?php echo json_encode($car); ?>,
                            required: true
                        }
                    }
                },   
                {
                    field:'month_rent',title:'月租金',width: '80px',
                    editor:{
                        type:'textbox',
                        options:{
                            validType: 'money',
                            required: true
                        }
                    }
                },
                {
                    field:'let_time',title:'出租时间',width: '180px',
                    editor:{
                        type:'datebox',
                        options:{
                            validType: 'date',
                            required: true
                        }
                    }
                }, 
                {
                    field:'note',title:'备注',width: '330px',align:'left',
                    editor:{
                        type:'textbox',
                        options:{
                            name: "note[]",
                            validType: 'length[255]'
                        }
                    }
                }   
            ]],
            //双击
            onDblClickRow: function(rowIndex,rowData){
                //CarContractRecordIndex.edit(rowData.id);
            }
        });
    }
    CarContractRecordCarAdd.init();
    //获取选中记录
    CarContractRecordCarAdd.getSelectedRow = function(){
        var datagrid = $('#easyui-datagrid-car-contract-record-car-add');
        var selectedRow = datagrid.datagrid('getSelected');
        if(!selectedRow){
            $.messager.alert('错误','请选择要操作的记录！','error');
            return false;
        }
        return selectedRow;
    }
    //添加签约车辆
    CarContractRecordCarAdd.add = function(){
        var datagrid = $('#easyui-datagrid-car-contract-record-car-add');
        var data = datagrid.datagrid('getData');
        var rowsNum = data.total;
        datagrid.datagrid('appendRow',{
            'plate_number': '',
            'month_rent': '0.00',
            'let_time': formatDateToString(Date.parse(new Date()) / 1000),
            'note': ''
        });
        datagrid.datagrid('beginEdit',rowsNum);
    }
    //删除签约车辆
    CarContractRecordCarAdd.remove = function(){
        var selectedRow = this.getSelectedRow();
        if(!selectedRow) return false;
        var datagrid = $('#easyui-datagrid-car-contract-record-car-add');
        var rowIndex = datagrid.datagrid('getRowIndex',selectedRow);
        datagrid.datagrid('deleteRow',rowIndex);
    }
    CarContractRecordCarAdd.submitForm = function(){
        var form = $('#easyui-form-car-contract-record-car-add');
        if(!form.form('validate')) return false;
        var datagrid = $('#easyui-datagrid-car-contract-record-car-add');
        var carData = datagrid.datagrid('getData');
        var carRowNum = carData.total;
        var carHtml = '';
        for(var i=0; i<carRowNum;i++){
            datagrid.datagrid('endEdit',i);
            if(carData.rows[i].plate_number != ''){
                carHtml += '<input type="text" name="plate_number[]" value="'+carData.rows[i].plate_number+'" />';
                carHtml += '<input type="text" name="month_rent[]" value="'+carData.rows[i].month_rent+'" />';
                carHtml += '<input type="text" name="let_time[]" value="'+carData.rows[i].let_time+'" />';
                carHtml += '<input type="text" name="note[]" value="'+carData.rows[i].note+'" />';
            }
        }
        $('#car-contract-record-car-add-car-item').html(carHtml);         
        var data = form.serialize();
        $.ajax({
            type: 'post',
            url: "<?php echo yii::$app->urlManager->createUrl(['car/contract-record/car-add']); ?>",
            data: data,
            dataType: 'json',
            success: function(data){
                if(data.status){
                    $.messager.alert('操作成功',data.info,'info');
                    try{
                        $('#easyui-datagrid-car-contract-record-car-manage-car-add').dialog('close');
                        $('#easyui-datagrid-car-contract-record-car-manage').datagrid('reload');
                    }catch(e){}
                    
                }else{
                    $.messager.alert('操作失败',data.info,'error');
                }
            }
        });
    }
</script>