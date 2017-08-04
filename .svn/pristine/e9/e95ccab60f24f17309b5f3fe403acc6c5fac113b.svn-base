<table id="personalContractIndex_addWin_datagrid"></table>

<!-- toolbar start -->
<div id="personalContractIndex_addWin_datagridToolbar">
    <form id="personalContractIndex_addWin_form" class="easyui-form" method="post">
        <div
            class="easyui-panel"
            title="合同基本信息"    
            iconCls='icon-save'
            border="false"
            style="width:100%;"
        >
            <input type="hidden" name="customer_type" value="PERSONAL" />
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
                                     
                     </div>
                    </select>
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">承租客户</div>
                    <div class="ulforform-resizeable-input">
                        <input id="personalContractIndex_addWin_combogrid_choosePersonalCustomer" name="pCustomer_id"  style="width:160px" />
                        <a href="javascript:personalContractIndex_addWin.addPersonalCustomer()" class="easyui-linkbutton" data-options="iconCls:'icon-add'" title="新增个人客户"></a>
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
        <div
            class="easyui-panel"
            title="签约车辆"    
            iconCls='icon-save'
            border="false"
            style="width:100%;"
        >
            <div style="padding:4px;">
                <a href="javascript:personalContractIndex_addWin.addCar()" class="easyui-linkbutton" data-options="iconCls:'icon-add'">添加</a>
                <a href="javascript:personalContractIndex_addWin.editCar()" class="easyui-linkbutton" data-options="iconCls:'icon-add'">修改</a>
                <a href="javascript:personalContractIndex_addWin.removeCar()" class="easyui-linkbutton" data-options="iconCls:'icon-remove'">删除</a>
            </div>
        </div>
        <div style="display:none" id="customer-contract-record-add-car-item"></div>
    </form>
</div>
<!-- toolbar end -->

<!-- 窗口 -->
<div id="personalContractIndex_addWin_addPersonalCustomerWin"></div>
<!-- 窗口 -->

<script>
    // 请求的URL
    var personalContractIndex_addWin_URL_ChoosePersonalCustomer = "<?php echo yii::$app->urlManager->createUrl(['customer/combogrid/get-personal-customer-list']); ?>";
    var personalContractIndex_addWin_URL_addPersonalCustomer = "<?php echo yii::$app->urlManager->createUrl(['customer/personal/add']); ?>";

    var personalContractIndex_addWin = new Object();
    personalContractIndex_addWin.init = function(){
        //初始化-datagrid
        $('#personalContractIndex_addWin_datagrid').datagrid({
            fit: true,
            border: false,
            singleSelect: true,
            rownumbers: true,
            toolbar: '#personalContractIndex_addWin_datagridToolbar',
            columns:[[
                {field: 'ck',checkbox: true},
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
                    field:'let_time',title:'起租时间',width: '120px',
                    editor:{
                        type:'datebox',
                        options:{
                            validType: 'date',
                            required: true
                        }
                    }
                },
                {
                    field:'note',title:'备注',width: '630px',align:'left',
                    editor:{
                        type:'textbox',
                        options:{
                            validType: 'length[255]'
                        }
                    }
                }
            ]]
        });

        // 初始化-combogrid-选择个人客户
        $('#personalContractIndex_addWin_combogrid_choosePersonalCustomer').combogrid({
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
            url: personalContractIndex_addWin_URL_ChoosePersonalCustomer,
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

        //初始化-添加个人客户窗口
        $('#personalContractIndex_addWin_addPersonalCustomerWin').dialog({
            title: '添加个人客户',
            width: '900px',   
            height: '500px',   
            closed: true,   
            cache: true,   
            modal: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var form = $('#easyui-form-customer-personal-add');
                    if(!form.form('validate')){
                        return false;
                    }
                    var data = form.serialize();
                    $.ajax({
                        type: 'post',
                        url: personalContractIndex_addWin_URL_addPersonalCustomer,
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('添加成功',data.info,'info');
                                $('#personalContractIndex_addWin_addPersonalCustomerWin').dialog('close');
                                $('#personalContractIndex_addWin_combogrid_choosePersonalCustomer').combobox('reload');
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
                    $('#personalContractIndex_addWin_addPersonalCustomerWin').dialog('close');
                }
            }],
            onClose:function(){
                $(this).dialog('clear');
            }
        });
    }
    personalContractIndex_addWin.init();

    //添加客户
    personalContractIndex_addWin.addPersonalCustomer = function(){
        $('#personalContractIndex_addWin_addPersonalCustomerWin')
            .dialog('open')
            .dialog('refresh',personalContractIndex_addWin_URL_addPersonalCustomer);
    }
    //获取选中记录
    personalContractIndex_addWin.getSelectedRow = function(){
        var datagrid = $('#personalContractIndex_addWin_datagrid');
        var selectedRow = datagrid.datagrid('getSelected');
        if(!selectedRow){
            $.messager.alert('错误','请选择要操作的记录！','error');
            return false;
        }
        return selectedRow;
    }
    //添加车辆
    personalContractIndex_addWin.addCar = function(){
        var datagrid = $('#personalContractIndex_addWin_datagrid');
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
    //修改车辆
    personalContractIndex_addWin.editCar = function(){
        var selectedRow = this.getSelectedRow();
        if(!selectedRow) return false;
        var datagrid = $('#personalContractIndex_addWin_datagrid');
        var rowIndex = datagrid.datagrid('getRowIndex',selectedRow);
        datagrid.datagrid('beginEdit',rowIndex);
    }
    //删除车辆
    personalContractIndex_addWin.removeCar = function(){
        var selectedRow = this.getSelectedRow();
        if(!selectedRow) return false;
        var datagrid = $('#personalContractIndex_addWin_datagrid');
        var rowIndex = datagrid.datagrid('getRowIndex',selectedRow);
        datagrid.datagrid('deleteRow',rowIndex);
    }
    //提交表单
    personalContractIndex_addWin.submitForm = function(){
        var form = $('#personalContractIndex_addWin_form');
        if(!form.form('validate')){
            return false;
        }
        var datagrid = $('#personalContractIndex_addWin_datagrid');
        var carData = datagrid.datagrid('getData');
        var carRowNum = carData.total;
        var carHtml = '';
        for(var i=0; i<carRowNum;i++){
            datagrid.datagrid('endEdit',i);
            if(carData.rows[i].plate_number != ''){
                carHtml += '<input type="text" name="plate_number[]" value="'+carData.rows[i].plate_number+'" />';
                carHtml += '<input type="text" name="month_rent[]" value="'+carData.rows[i].month_rent+'" />';
                carHtml += '<input type="text" name="let_time[]" value="'+carData.rows[i].let_time+'" />';
                carHtml += '<input type="text" name="car_note[]" value="'+carData.rows[i].note+'" />';
            }
        }
        $('#customer-contract-record-add-car-item').html(carHtml);
        var data = form.serialize();
        $.ajax({
            type: 'post',
            url: "<?php echo yii::$app->urlManager->createUrl(['customer/personal-contract/add']); ?>",
            data: data,
            dataType: 'json',
            success: function(data){
                if(data.status){
                    $.messager.alert('新建成功',data.info,'info');
                    $('#easyui-datagrid-customer-contract-record-index-add').dialog('close');
                    $('#easyui-datagrid-customer-contract-record-index').datagrid('reload');
                }else{
                    $.messager.alert('新建失败',data.info,'error');
                }
            }
        });
    }
</script>