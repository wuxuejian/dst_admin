<div style="padding:10px 40px 20px 40px">
    <form id="easyui-form-car-let-register" class="easyui-form" method="post">
        <table cellpadding="8" cellspacing="0">
            <tr>
                <td><div style="width:70px;">出租车辆</div></td>
                <td>
                    <select 
                        class="easyui-combobox"
                        style="width:160px;"
                        name="car_id"
                        required="true"
                        missingMessage="请选择出租车辆"
                    >
                        <?php foreach($car as $val){ ?>
                        <option value="<?= $val['id']; ?>"><?= $val['plate_number']; ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td><div style="width:70px;">承租客户号</div></td>
                <td>
                    <select 
                        id="easyui-combobox-car-let-register-customer-number"
                        class="easyui-combobox"
                        style="width:160px;"
                        name="customer_id"
                        required="true"
                        missingMessage="请输入承租客户号"
                        url="<?= yii::$app->urlManager->createUrl(['car/let/get-customer-list']); ?>"
                        valueField='id',   
                        textField='number'  
                    ></select>
                </td>
                <td><a href="javascript:CarLetRegister.addCustomer()" class="easyui-linkbutton" data-options="iconCls:'icon-add'"></a>   </td>
            </tr>
            <tr>
                <td><div style="width:70px;">提车时间</div></td>
                <td>
                    <input
                        class="easyui-datetimebox"
                        style="width:160px;"
                        name="take_time"
                        required="true"
                        missingMessage="请选择提车时间"
                    >
                </td>
                <td><div style="width:70px;">还车时间</div></td>
                <td colspan="2">
                    <input
                        class="easyui-datetimebox"
                        style="width:160px;"
                        name="back_time"
                    >
                </td>
            </tr>
            <tr>
                <td><div style="width:70px;">备注</div></td>
                <td colspan="4">
                    <input 
                        class="easyui-textbox"
                        name="note"
                        data-options="multiline:true"
                        style="height:60px;width:425px;"
                    />
                </td>
            </tr>
        </table>
    </form>
</div>
<!-- 窗口 -->
<div id="car-let-register-add-customer"></div>
<!-- 窗口 -->
<script>
    var CarLetRegister = new Object();
    CarLetRegister.init = function(){
        //初始化添加窗口
        $('#car-let-register-add-customer').dialog({
            title: '添加客户',   
            width: '1000px',   
            height: '500px',   
            closed: true,   
            cache: true,   
            modal: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var baseform = $('#easyui-form-customer-base-info-add-baseinfo');
                    var personalform = $('#easyui-form-customer-base-info-add-personal');
                    var companyform = $('#easyui-form-customer-base-info-add-company');
                    var data = '';
                    if(baseform.find('input[name=base_type]').val() == 'PERSONAL'){
                        if(!baseform.form('validate') || !personalform.form('validate')){
                            return false;
                        }
                        data = baseform.serialize()+'&'+personalform.serialize();
                    }else{
                        if(!baseform.form('validate') || !companyform.form('validate')){
                            return false;
                        }
                        data = baseform.serialize()+'&'+companyform.serialize();
                    }
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['customer/base-info/add']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('添加成功',data.info,'info');
                                $('#car-let-register-add-customer').dialog('close');
                                $('#easyui-combobox-car-let-register-customer-number').combobox('reload');
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
                    $('#car-let-register-add-customer').dialog('close');
                }
            }]
        });
        $('#car-let-register-add-customer').dialog({
            onClose:function(){
                $('#car-let-register-add-customer').dialog('clear');
            }
        });
    }
    CarLetRegister.init();
    CarLetRegister.addCustomer = function(){
        $('#car-let-register-add-customer').dialog('open');
        $('#car-let-register-add-customer').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['customer/base-info/add']); ?>");
    }
</script>