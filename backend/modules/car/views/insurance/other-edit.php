<script src="js/jquery.ajaxSubmit.js"></script>
<div style="padding:15px"> 
    <form action="<?php echo yii::$app->urlManager->createUrl(['car/insurance/other-edit']); ?>" id="easyui-form-car-insurance-other-edit" class="easyui-form" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" />
        <table cellpadding="8" cellspacing="0">
        	<tr>
                <td><div style="width:85px;text-align:right;">保单号</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:170px;"
                        name="number"
                        required="true"
                        validType="length[100]"
                    />
                </td>
                <td><div style="width:85px;text-align:right;">保险公司</div></td>
                <td>
                    <select
                        class="easyui-combobox"
                        style="width:160px;"
                        name="insurer_company"
                        required="true"
                    >
                        <?php foreach($config['INSURANCE_COMPANY'] as $val){ ?>
                        <option value="<?php echo $val['value']; ?>"><?php echo $val['text']; ?></option>
                        <?php } ?>
                    </select>
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
            	<td><div style="width:85px;text-align:right;">险种</div></td>
            </tr>
            <?php 
            	$insurance_texts = json_decode($otherInfo['insurance_text']);
            	foreach ($insurance_texts as $index=>$row){
            ?>
            	<tr>
            		<td></td>
	                <td colspan="3">
	                    <input class="easyui-textbox" name="type[]" id="type<?=$index?>" value="<?=$row[0]?>"/>
	                    金额
	                    <input class="easyui-textbox" name="money[]" value="<?=$row[1]?>"/>
	                	<input type="button" value="删除" onclick="CarInsuranceOtherEdit.delText(<?=$index?>)">
	                </td>
	            </tr>
            <?php 
            	}
            ?>
            <tr>
            	<td></td>
               <td> <input id="add_text" type="button" value="增加其它险种" onclick="CarInsuranceOtherEdit.addText()" data-value="<?=count($insurance_texts)+1?>" /></td>
               <td colspan="2">保费合计：<span id='money_amount'></span></td>
            </tr>
            <tr>
            	<td><div style="width:85px;text-align:right;">保单附件</div></td>
            </tr>
            <?php 
            	$append_urls = json_decode($otherInfo['append_urls']);
            	foreach ($append_urls as $index=>$row){
            ?>
            	<tr>
	                <td colspan="4">
	                	附件地址：<?=$row?>
	                	<input type="hidden" name="append_url[]" id="append_url<?=$index?>" value="<?=$row?>">
	                	<input type="button" value="删除" onclick="CarInsuranceOtherEdit.del1(<?=$index?>)">
	                </td>
	            </tr>
            <?php 
            	}
            ?>
            
            <tr>
            	<td></td>
               <td> <input id="add_append" type="button" value="增加保单附件" onclick="CarInsuranceOtherEdit.addAppend()" data-value="<?=count($append_urls)+1?>" /></td>
            </tr>
        </table>
    </form>
</div>
<script>
    var oldData = <?php echo json_encode($otherInfo); ?>;
    oldData.start_date = parseInt(oldData.start_date) > 0 ? formatDateToString(oldData.start_date) : '';
    oldData.end_date = parseInt(oldData.end_date) > 0 ? formatDateToString(oldData.end_date) : '';
    $('#easyui-form-car-insurance-other-edit').form('load',oldData);

    var CarInsuranceOtherEdit = new Object();
    CarInsuranceOtherEdit.del1 = function(index){
    	$("#append_url"+index).parent().parent().remove();
    }
    CarInsuranceOtherEdit.addAppend = function()
	{
		var data = $("#add_append").attr('data-value');
		var html ='<tr><td></td><td colspan="3"><input type="file" name="append[]" id="append'+data+'">'+
					'<input type="button" value="移除" onclick="CarInsuranceOtherEdit.delAppend('+data+')" />'+
				'</td></tr>';
		$("#add_append").parent().parent().before(html);
		$("#add_append").attr('data-value',parseInt(data)+1);
	}
    CarInsuranceOtherEdit.delAppend = function(data){
		$("#append"+data).parent().parent().remove();
	}
    CarInsuranceOtherEdit.submitForm = function(){
	    var form = $('#easyui-form-car-insurance-other-edit');
	    if(!form.form('validate')){
	        return false;
	    }
	    form.ajaxSubmit({
			dataType : "json",
			success : function(data){
				if(data.status){
	                $.messager.alert('修改成功',data.info,'info');
	                $('#easyui-dialog-car-insurance-other-edit').dialog('close');
	                $('#easyui-datagrid-car-insurance-other-insurance').datagrid('reload');
	            }else{
	                $.messager.alert('修改失败',data.info,'error');
	            }
			},
			error: function(xhr){
				$('#loadTips').hide();
			}
			
		});
	}

    CarInsuranceOtherEdit.addText = function(){
		var data = $("#add_text").attr('data-value');
		var html ='<tr><td></td><td colspan="3"><input id="type'+data+'" class="easyui-textbox" name="type[]"/>';
			html +=' 金额<input class="easyui-textbox" name="money[]"/> <input type="button" value="移除" onclick="CarInsuranceOtherAdd.delText('+data+')" /></td></tr>';
		$("#add_text").parent().parent().before(html);
		$("#add_text").attr('data-value',parseInt(data)+1);

		$('#easyui-form-car-insurance-other-edit').find('input[name="money[]"]').textbox({
            onChange: function(){
            	CarInsuranceOtherEdit.calAmount();
            }
        });
	},
	CarInsuranceOtherEdit.delText = function(data){
		$("#type"+data).parent().parent().remove();
		CarInsuranceOtherEdit.calAmount();
	}

	//保费合计
	CarInsuranceOtherEdit.calAmount = function(){
		var moneys = 0;
		$('#easyui-form-car-insurance-other-edit')
			.find('input[name="money[]"]')
			.each(function(){   
				moneys += parseFloat($(this).val());
		}); 
		$('#money_amount').text(moneys);

		$('#easyui-form-car-insurance-other-edit').find('input[name="money[]"]').textbox({
            onChange: function(){
            	CarInsuranceOtherEdit.calAmount();
            }
        });
	}
	CarInsuranceOtherEdit.calAmount();
</script>