<table id="personalContractIndex_editWin_datagrid"></table>
<!-- toolbar start -->
<div id="personalContractIndex_editWin_datagridTollbar">
    <form id="personalContractIndex_editWin_form" class="easyui-form" method="post">
        <input type="hidden" name="id" />
        <input type="hidden" name="customer_type" value="PERSONAL" />
        <div
            class="easyui-panel"
            title="合同基本信息"
            iconCls='icon-save'
            border="false"
            style="width:100%;"
            >
            <ul class="ulforform-resizeable">
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">合同编号</div>
                    <div class="ulforform-resizeable-input">
                        <input
                            class="easyui-textbox"
                            style="width:160px;"
                            name="number"
                            required="true"
                            missingMessage="请输入合同编号！"
                            />
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">合同类型</div>
                    <div class="ulforform-resizeable-input">
                       <!--  <input
                            class="easyui-textbox"
                            style="width:160px;"
                            name="contract_type"
                            required="true"
                            missingMessage="请输入合同编号！"
                            /> -->
                         <select
                        
                        class="easyui-combobox"
                        name="contract_type"
                        style="width:160px;"
                        required="true"
                        editable="false"
                        data-options="panelHeight:'auto'"
                        align:"center"
                        
                    >         
                           <option value=''>请选择</option>
                              
                           <?php foreach($contract_type as $val){ ?>
                           <option value="<?= $val['text'] ?>"><?= $val['text'] ?></option>
                           <?php } ?>               
                     
                    </select>
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">承租客户</div>
                    <div class="ulforform-resizeable-input">
                        <input id="personalContractIndex_editWin_combogrid_choosePersonalCustomer" name="pCustomer_id"  style="width:160px" />
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">签订日期</div>
                    <div class="ulforform-resizeable-input">
                        <input
                            class="easyui-datebox"
                            style="width:160px;"
                            name="sign_date"
                            required="true"
                            missingMessage="请选择合同签订日期！"
                            value="<?php echo date('Y-m-d'); ?>"
                            validType="date"
                            >
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">开始时间</div>
                    <div class="ulforform-resizeable-input">
                        <input
                            class="easyui-datebox"
                            style="width:160px;"
                            name="start_time"
                            required="true"
                            missingMessage="请选择开始时间！"
                            validType="date"
                            >
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">结束时间</div>
                    <div class="ulforform-resizeable-input">
                        <input
                            class="easyui-datebox"
                            style="width:160px;"
                            name="end_time"
                            required="true"
                            missingMessage="请选择结束时间！"
                            validType="date"
                            >
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">合同期限</div>
                    <div class="ulforform-resizeable-input">
                        <input
                            class="easyui-datebox"
                            style="width:160px;"
                            name="due_time"
                            required="true"
                            missingMessage="请选择合同期限！"
                            validType="date"
                            />
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">总保证金</div>
                    <div class="ulforform-resizeable-input">
                        <input
                            class="easyui-textbox"
                            style="width:160px;"
                            name="bail"
                            validType="money"
                            />
                    </div>
                </li>
				<li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">归属销售员</div>
                    <div class="ulforform-resizeable-input">
                        <input
                            class="easyui-textbox"
                            style="width:160px;"
                            name="salesperson"
                        />
                    </div>
                </li>
                <li class="ulforform-resizeable-group-single">
                    <div class="ulforform-resizeable-title">备注</div>
                    <div class="ulforform-resizeable-input">
                        <input
                            class="easyui-textbox"
                            name="note"
                            data-options="multiline:true"
                            style="height:40px;width:765px;"
                            />
                    </div>
                </li>
            </ul>
        </div>
        <div style="border-top:1px solid #95B8E7;"></div>
        <div class="easyui-panel" title="签约车辆" style="padding:3px 2px;width:100%;" iconCls='icon-save' border="false">
                <?php foreach($buttons as $val){ ?>
                    <a href="javascript:<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon']; ?>'"><?= $val['text']; ?></a>
                <?php } ?>
        </div>
    </form>
</div>
<!-- toolbar end -->
<form style="display:none;" id="personalContractIndex_editWin_submitDataForm"></form>
<script>
    var personalContractIndex_editWin = new Object();
    personalContractIndex_editWin.init = function(){
        //初始化datagrid
        $('#personalContractIndex_editWin_datagrid').datagrid({
            method: 'get',
            url:"<?php echo yii::$app->urlManager->createUrl(['customer/personal-contract/get-car-list','contractId'=>$contractId]); ?>",
            toolbar: "#personalContractIndex_editWin_datagridTollbar",
            border: false,
            fit: true,
            pagination: true,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: false,
            showFooter: true,
            frozenColumns: [[
                {field: 'ck',checkbox: true},
                {field: 'id',title: 'id',hidden: true},
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
                }
            ]],
            columns:[[
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
                    sortable: true,
                    formatter: function(value){
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
                    sortable: true,
                    formatter: function(value){
                        if(!isNaN(value) && value != 0){
                            return formatDateToString(value);
                        }
                        return '';
                    }/*,
                 editor:{
                 type:'datebox',
                 value: '2015-12-23',
                 options:{
                 value: "2015-11-23",
                 validType: 'date',
                 }
                 }*/
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

        //初始化-combogrid-选择个人客户
        $('#personalContractIndex_editWin_combogrid_choosePersonalCustomer').combogrid({
            panelWidth: 500,
            panelHeight: 200,
            pageSize: 10,
            pageList: [10,20,30],
            scrollbarSize:0,
            pagination: true,
            fitColumns: true,
            rownumbers: true,
            delay: 800,
            mode:'remote',
            method: 'get',
            url: "<?php echo yii::$app->urlManager->createUrl(['customer/combogrid/get-personal-customer-list']); ?>",
            idField: 'customer_id',
            textField: 'customer_name',
            columns: [[
                {field:'customer_id',title:'ID',width:20,align:'center',hidden:true},
                {field:'customer_name',title:'个人客户名称',width:100,align:'center'},
                {field:'customer_mobile',title:'手机号',width:100,align:'center'},
                {field:'customer_address',title:'地址',width:200,halign:'center'}
            ]],
            required: true,
            missingMessage: '请从下拉列表里选择客户！',
            onHidePanel:function(){
                var _combogrid = $(this);
                var value = _combogrid.combogrid('getValue');
                var textbox = _combogrid.combogrid('textbox');
                var text = textbox.val();
                var rows = _combogrid.combogrid('grid').datagrid('getSelections');
                if(text && rows.length < 1 && value == text){
                    $.messager.show(
                        {
                            title: '无效客户',
                            msg:'【' + text + '】不是有效客户！请重新检索并选择一个客户！'
                        }
                    );
                    _combogrid.combogrid('clear');
                }
            }
        });

        //表单赋值
        var oldData = <?php echo json_encode($contractInfo); ?>;
        oldData.due_time = oldData.due_time > 0 ? formatDateToString(oldData.due_time) : '';
        oldData.start_time = oldData.start_time > 0 ? formatDateToString(oldData.start_time) : '';
        oldData.end_time = oldData.end_time > 0 ? formatDateToString(oldData.end_time) : '';
        oldData.sign_date = oldData.sign_date > 0 ? formatDateToString(oldData.sign_date) : '';
        $('#personalContractIndex_editWin_form').form('load',oldData);
        //为combogrid查询赋值
        $('#personalContractIndex_editWin_combogrid_choosePersonalCustomer').combogrid('grid').datagrid('load',{'customerId':oldData.pCustomer_id});

    }
    personalContractIndex_editWin.init();

    //获取选择记录
    personalContractIndex_editWin.getSelected = function(multiple){
        var datagrid = $('#personalContractIndex_editWin_datagrid');
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
                $.messager.alert('错误','请选择要操作的记录！','error');
                return false;
            }
            return selectRow;
        }
    }
    //签约车辆
    personalContractIndex_editWin.addCar = function(){
        var datagrid = $('#personalContractIndex_editWin_datagrid');
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
    personalContractIndex_editWin.editCar = function(){
        var selectRows = this.getSelected(true);
        if(!selectRows) return false;
        var datagrid = $('#personalContractIndex_editWin_datagrid');
        for(var i in selectRows){
            var rowIndex = datagrid.datagrid('getRowIndex',selectRows[i]);
            datagrid.datagrid('beginEdit',rowIndex);
            if(selectRows[i].id){
                //原记录的车辆号不允许修改
                var editor = datagrid.datagrid('getEditor',{"index": rowIndex,"field": "plate_number"});
                editor.target.combobox('disable');
            }
        }
    }
    //保存修改
    personalContractIndex_editWin.saveEdit = function(){
        var selectRows = this.getSelected(true);
        if(!selectRows) return false;
        var datagrid = $('#personalContractIndex_editWin_datagrid');

        for(var i in selectRows){
            datagrid.datagrid('endEdit',datagrid.datagrid('getRowIndex',selectRows[i]));
        }
        var selectRows = this.getSelected(true);
        var html = '<input type="text" name="contract_id" value="<?php echo $contractInfo["id"]; ?>" />';
        html += '<input type="text" name="pCustomer_id" value="<?php echo $contractInfo["pCustomer_id"]; ?>" />';
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
        var form = $('#personalContractIndex_editWin_submitDataForm');
        form.html(html);
        var data = form.serialize();
        $.ajax({
            type: 'post',
            url: "<?php echo yii::$app->urlManager->createUrl(['customer/personal-contract/add-edit-car']); ?>",
            data: data,
            dataType: 'json',
            success: function(data){
                if(data.status){
                    $.messager.alert('操作成功',data.info,'info');
                    $('#personalContractIndex_editWin_datagrid').datagrid('reload');
                }else{
                    $.messager.alert('操作失败',data.info,'error');
                }
            }
        });
    }
    //归还车辆
    personalContractIndex_editWin.backCar = function(){
        var selectRows = this.getSelected(true);
        if(!selectRows) return false;
        var id = '';
        for(var i in selectRows){
            id += selectRows[i].id + ',';
        }
        $.messager.confirm('确认还车','你确定要归还所选车辆吗？',function(r){
            if(r){
                $.ajax({
                    type: 'post',
                    url: "<?php echo yii::$app->urlManager->createUrl(['customer/personal-contract/car-back']); ?>",
                    data: {"id": id},
                    dataType: 'json',
                    success: function(data){
                        if(data.status){
                            $.messager.alert('归还成功',data.info,'info');
                            $('#personalContractIndex_editWin_datagrid').datagrid('reload');
                        }else{
                            $.messager.alert('归还失败',data.info,'error');
                        }
                    }
                });
            }
        });
    }
</script>