<table id="easyui-datagrid-customer-contract-record-add"></table>
<!-- toolbar start -->
<div id="customer-contract-record-add-toolbar">
    <form id="easyui-form-customer-contract-record-index-add" class="easyui-form" method="post">
        <div
            class="easyui-panel"
            title="合同基本信息"    
            iconCls='icon-save'
            border="false"
            style="width:100%;"
        >
			<input type="hidden" name="customer_type" value="COMPANY" />
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
                        id="contract_type_id"
                        class="easyui-combobox"
                        name="contract_type"
                        style="width:160px;"
                        required="true"
                        editable="false"
                        data-options="panelHeight:'auto'"
                        align:"center"
                        
                    >         
                           <option value=''></option>
                              
                           <?php foreach($contract_type as $val){ ?>
                           <option value="<?= $val['text'] ?>"><?= $val['text'] ?></option>
                           <?php } ?>               
                     
                    </select>
                    </div>
                </li>


                <span id='tip'></span>


                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">承租客户</div>
                    <div class="ulforform-resizeable-input">
                        <input id="easyui-combobox-customer-contract-record-add-customer-number" name="cCustomer_id"  style="width:160px" />
                        <a href="javascript:CustomerContractRecordAdd.addCustomer()" class="easyui-linkbutton" data-options="iconCls:'icon-add'" title="新增企业客户"></a>
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
               <!--  <li class="ulforform-resizeable-group">
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
                </li> -->
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
                            required="true"
                        />
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">客户来源</div>
                    <div class="ulforform-resizeable-input">
                    <select
                        class="easyui-combobox"
                        name="source"
                        style="width:160px;"
                        required="true"
                        editable="false"
                        data-options="panelHeight:'auto'"
                        align:"center"   
                    >         
                        <option value=''></option>
                        <option value='1'>400呼叫中心</option>
                        <option value='2'>地推</option>
                        <option value='3'>大客户导入</option>
                        <option value='4'>自主开发</option>
                        <option value='5'>转介绍</option>
                        <option value='6'>活动促销</option>
                        <option value='7'>其他</option>
                    </select>
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">每月租金缴纳日</div>
                    <div class="ulforform-resizeable-input">
                        <!-- <input
                            class="easyui-datebox"
                            style="width:160px;"
                            name="rent_day"
                            required="true"
                            missingMessage="请选择租金缴纳日！"
                            validType="date"
                        /> -->
                       <select
                        class="easyui-combobox"
                        name="rent_day"
                        style="width:160px;"
                        required="true"
                        editable="false"
                        align:"center"   
                        >   
                            <option value=''></option>
                            <option value='1'>1</option>
                            <option value='2'>2</option>
                            <option value='3'>3</option>
                            <option value='4'>4</option>
                            <option value='5'>5</option>
                            <option value='6'>6</option>
                            <option value='7'>7</option>
                            <option value='8'>8</option>
                            <option value='9'>9</option>
                            <option value='10'>10</option>
                            <option value='11'>11</option>
                            <option value='12'>12</option>
                            <option value='13'>13</option>
                            <option value='14'>14</option>
                            <option value='15'>15</option>
                            <option value='16'>16</option>
                            <option value='17'>17</option>
                            <option value='18'>18</option>
                            <option value='19'>19</option>
                            <option value='20'>20</option>
                            <option value='21'>21</option>
                            <option value='22'>22</option>
                            <option value='23'>23</option>
                            <option value='24'>24</option>
                            <option value='25'>25</option>
                            <option value='26'>26</option>
                            <option value='27'>27</option>
                            <option value='28'>28</option>
                            <option value='29'>29</option>
                            <option value='30'>30</option>
                            <option value='31'>31</option>

                        </select>
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">账期</div>
                    <div class="ulforform-resizeable-input">
                        <input
                            class="easyui-textbox"
                            style="width:160px;"
                            name="rent_deadline"
                            prompt="请输入天数"
                            missingMessage="请填写账期！"
                            validType="int"
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
<!--                 <a href="javascript:CustomerContractRecordAdd.addCar()" class="easyui-linkbutton" data-options="iconCls:'icon-add'">添加</a> -->
<!--                 <a href="javascript:CustomerContractRecordAdd.editCar()" class="easyui-linkbutton" data-options="iconCls:'icon-add'">修改</a> -->
<!--                 <a href="javascript:CustomerContractRecordAdd.removeCar()" class="easyui-linkbutton" data-options="iconCls:'icon-remove'">删除</a> -->
            </div>
        </div>
        <div style="display:none" id="customer-contract-record-add-car-item"></div>
    </form>
</div>
<!-- toolbar end -->
<!-- 窗口 -->
<div id="customer-contract-record-add-customer"></div>
<!-- 窗口 -->
<script>
    var CustomerContractRecordAdd = new Object();
    CustomerContractRecordAdd.init = function(){
        //初始化datagrid
        $('#easyui-datagrid-customer-contract-record-add').datagrid({
            fit: true,
            border: false,
            singleSelect: true,
            rownumbers: true,
            toolbar: '#customer-contract-record-add-toolbar',
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
        $('#easyui-combobox-customer-contract-record-add-customer-number').combogrid({
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
            url: "<?php echo yii::$app->urlManager->createUrl(['customer/combogrid/get-company-customer-list']); ?>",
            idField: 'customer_id',
            textField: 'customer_name',
            columns: [[
                {field:'customer_id',title:'ID',width:20,align:'center',hidden:true},
                {field:'customer_name',title:'企业客户名称',width:150,halign:'center'},
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

		
        $('#contract_type_id').combobox({

            onChange:function(newValue,oldValue){
                
                    var data = 0;
                    if(newValue == '租赁'){
                        var html = "<li class='ulforform-resizeable-group'>\
                        <div class='ulforform-resizeable-title' style='width:85px;text-align:right;'>业务类型</div>\
                        <div class='ulforform-resizeable-input'><select id='second_contract_type_id' name='second_contract_type' style='width:160px;' class='easyui-combobox' align:'center' ><option>长租</option><option>以租代售</option><option>分时租赁</option><option>短租</option></select>\
                        </div></li>";
                    }
                    if(newValue == '自运营'){
                        var html = "<li class='ulforform-resizeable-group'>\
                        <div class='ulforform-resizeable-title' style='width:85px;text-align:right;'>业务类型</div>\
                        <div class='ulforform-resizeable-input'><select id='second_contract_type_id' name='second_contract_type' style='width:160px;' class='easyui-combobox' align:'center' ><option>店配</option><option>宅配</option><option>调拨转运</option><option>接驳运输</option><option>收派</option></select>\
                        </div></li>";
                    }
                    
                    $("#tip").html(html);
                     //$("#contract_type_id").parent().parent().after(html);
               /* var datax,json;
                datax = [];
                datax.push({ "text": "测试", "id": 100 });*/
                //$("#user_id"+data).combobox("loadData", datax);

                $("#second_contract_type_id").combobox({
                    //data:<?=json_encode($users)?>,
                    valueField:'id',
                    textField:'name',
                    /*onSelect: function () {
                        changeValue(data);
                    } */
                });
                
                //$("#site_tel"+data).textbox();

         }
    })




        //初始化添加窗口
        $('#customer-contract-record-add-customer').dialog({
            title: '添加企业客户',   
            width: '900px',   
            height: '500px',   
            closed: true,   
            cache: true,   
            modal: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var form = $('#easyui-form-customer-company-add');
                    if(!form.form('validate')){
                        return false;
                    }
                    var data = form.serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['customer/company/add']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('添加成功',data.info,'info');
                                $('#customer-contract-record-add-customer').dialog('close');
                                $('#easyui-combobox-customer-contract-record-add-customer-number').combobox('reload');
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
                    $('#customer-contract-record-add-customer').dialog('close');
                }
            }],
            onClose:function(){
                $(this).dialog('clear');
            }
        });
    }
    CustomerContractRecordAdd.init();
    //添加客户
    CustomerContractRecordAdd.addCustomer = function(){
        $('#customer-contract-record-add-customer').dialog('open');
        $('#customer-contract-record-add-customer').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['customer/company/add']); ?>");
    }
    //获取选中记录
    CustomerContractRecordAdd.getSelectedRow = function(){
        var datagrid = $('#easyui-datagrid-customer-contract-record-add');
        var selectedRow = datagrid.datagrid('getSelected');
        if(!selectedRow){
            $.messager.alert('错误','请选择要操作的记录！','error');
            return false;
        }
        return selectedRow;
    }
    //添加车辆
    CustomerContractRecordAdd.addCar = function(){
        var datagrid = $('#easyui-datagrid-customer-contract-record-add');
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
    CustomerContractRecordAdd.editCar = function(){
        var selectedRow = this.getSelectedRow();
        if(!selectedRow) return false;
        var datagrid = $('#easyui-datagrid-customer-contract-record-add');
        var rowIndex = datagrid.datagrid('getRowIndex',selectedRow);
        datagrid.datagrid('beginEdit',rowIndex);
    }
    //删除车辆
    CustomerContractRecordAdd.removeCar = function(){
        var selectedRow = this.getSelectedRow();
        if(!selectedRow) return false;
        var datagrid = $('#easyui-datagrid-customer-contract-record-add');
        var rowIndex = datagrid.datagrid('getRowIndex',selectedRow);
        datagrid.datagrid('deleteRow',rowIndex);
    }
    //提交表单
    CustomerContractRecordAdd.submitForm = function(){
        var form = $('#easyui-form-customer-contract-record-index-add');
        if(!form.form('validate')){
            return false;
        }
        var datagrid = $('#easyui-datagrid-customer-contract-record-add');
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
            url: "<?php echo yii::$app->urlManager->createUrl(['customer/contract-record/add']); ?>",
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