<script src="js/jquery.ajaxSubmit.js"></script>
<div style="padding:15px"> 
    <form action="<?php echo yii::$app->urlManager->createUrl(['car/insurance/tci-add']); ?>" id="easyui-form-car-insurance-tci-add" class="easyui-form" method="post" enctype="multipart/form-data">
        <input type="hidden" name="car_id" value="<?php echo $carId; ?>" />
        <table cellpadding="8" cellspacing="0">
        	<tr>
                <td><div style="width:85px;text-align:right;">保单号</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:170px;"
                        name="number"
                        required="true"
                         validType="match[/^[a-zA-Z0-9_]{0,}$/]"
                    />
                </td>
                <td><div style="width:85px;text-align:right;">保险公司</div></td>
                <td>
                    <input style="width:160px;" name="insurer_company" />
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">开始时间</div></td>
                <td>
                    <input
                        class="easyui-datebox"
                        style="width:160px;"
                        name="start_date"
                        required="true"
                        missingMessage="请选择开始日期！"
                        validType="date"
                    />
                </td>
                <td><div style="width:85px;text-align:right;">结束时间</div></td>
                <td>
                    <input
                        class="easyui-datebox"
                        style="width:160px;"
                        name="end_date"
                        required="true"
                        missingMessage="请选择结束日期！"
                        validType="date"
                    />
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">保险金额</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="money_amount"
                        required="true"
                        missingMessage="请输入保险金额！"
                        validType="money"
                    />
                </td>
                <td><div style="width:85px;text-align:right;">使用性质</div></td>
                <!-- <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="use_nature"
                        required="true"
                        missingMessage="请输入保险金额！"
                        validType="money"
                    />
                </td> -->
                <td>
                <select class="easyui-combobox"  style="width:160px;"  name="use_nature" required="true" editable=true>
                            <option value=""></option>
                            <option value="1">企业营运货车</option>
                            <option value="2">企业非营运货车</option>
                            <option value="3">企业非营运客车</option>
                            <option value="4">企业营运客车</option>
                            <option value="5">个人家庭自用车</option>
                            <option value="6">特种车</option>
                </select>
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">备注</div></td>
                <td colspan="3">
                    <input
                        class="easyui-textbox"
                        name="note"
                        style="width:454px;height:40px;padding:0;" 
                        data-options="multiline:true"
                        validType="length[150]"
                    />
                </td>
            </tr>
            <tr>
            	<td><div style="width:85px;text-align:right;">交强险保单附件</div></td>
                <td colspan="3">
                	<input type="file" name="append[]" id="append1">
                </td>
            </tr>
            <tr>
            	<td></td>
               <td> <input id="add_append" type="button" value="增加保单附件" onclick="addAppend()" data-value="2" /></td>
            </tr>
            
        </table>
    </form>
</div>
<script type="text/javascript">
	var CarInsuranceTciAdd = new Object();
//提交表单
	CarInsuranceTciAdd.submitForm = function(){
	    var form = $('#easyui-form-car-insurance-tci-add');
	    if(!form.form('validate')){
	        return false;
	    }
	    form.ajaxSubmit({
			dataType : "json",
			success : function(data){
				if(data.status){
	                $.messager.alert('新建成功',data.info,'info');
	                $('#easyui-dialog-car-insurance-tci-add').dialog('close');
	                $('#easyui-datagrid-car-insurance-traffic-compulsory-insurance').datagrid('reload');
	            }else{
	                $.messager.alert('新建失败',data.info,'error');
	            }
			},
			error: function(xhr){
				$('#loadTips').hide();
			}
			
		});
	}
	
	function addAppend()
	{
		var data = $("#add_append").attr('data-value');

		var html ='<tr><td></td><td colspan="3"><input type="file" name="append[]" id="append'+data+'">'+
					'<input type="button" value="移除" onclick="delAppend('+data+')" />'+
				'</td></tr>';
		$("#add_append").parent().parent().before(html);
		$("#add_append").attr('data-value',parseInt(data)+1);
        //alert(data);
	}
	function delAppend(data){
		$("#append"+data).parent().parent().remove();
	}
    CarInsuranceTciAdd.init = function(){
    	$('#easyui-form-car-insurance-tci-add').find('input[name=insurer_company]').combobox({
                required: true,
                panelHeight: 'auto',
                valueField: 'value',
                textField: 'text',
                data: <?php echo json_encode($config['INSURANCE_COMPANY']); ?>
        });
    }
    CarInsuranceTciAdd.init();


    <?php 
    		$tciInfo['car_id'] = $carId;
    	?>	
	var oldData = <?php echo json_encode($tciInfo); ?>;
	oldData.start_date = parseInt(oldData.start_date) > 0 ? formatDateToString(oldData.start_date) : '';
	oldData.end_date = parseInt(oldData.end_date) > 0 ? formatDateToString(oldData.end_date) : '';
	$('#easyui-form-car-insurance-tci-add').form('load',oldData);
</script>


