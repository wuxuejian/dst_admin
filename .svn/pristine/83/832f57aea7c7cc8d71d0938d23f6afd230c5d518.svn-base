<script src="js/jquery.ajaxSubmit.js"></script>
<div style="padding:15px"> 
    <form action="<?php echo yii::$app->urlManager->createUrl(['car/insurance/bi-add']); ?>" id="easyui-form-car-insurance-bi-add" class="easyui-form" method="post" enctype="multipart/form-data">
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
                        validType="length[100]"
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
            <?php 
            	$types = array('车损险','三者险','司乘险(司机)','司乘险(乘客)','不计免赔险','玻璃险','涉水险','盗抢险','无法找到第三方特约险');
            ?>
            <tr>
            	<td><div style="width:85px;text-align:right;">险种</div></td>
            </tr>
            <?php 
            	$insurance_texts = json_decode($biInfo['insurance_text']);
                if(!$insurance_texts){
                    $insurance_texts = array();
                }
            	foreach ($insurance_texts as $index=>$row){
            ?>
            	<tr>
            		<td></td>
	                <td colspan="3">
	                	<select id="type<?=$index?>"   name="type[]" required="true">
	                    	<?php foreach ($types as $value):?>
	                  		    <option value="<?=$value?>"<?=$value==$row[0]?' selected':''?>><?=$value?></option>
	                  		<?php endforeach;?>
	                    </select>
	                    <input class="easyui-textbox" name="money[]" value="<?=$row[1]?>"/>
	                	<input type="button" value="删除" onclick="CarInsuranceBiAdd.delText(<?=$index?>)">
	                </td>
	            </tr>
            <?php 
            	}
            ?>
            <tr>
            	<td></td>
               <td> <input id="add_text" type="button" value="增加其它险种" onclick="CarInsuranceBiAdd.addText()" data-value="2" /></td>
               <td colspan="2">保费合计：<span id='money_amount'></span></td>
            </tr>
            <tr>
            	<td><div style="width:85px;text-align:right;">保单附件</div></td>
                <td colspan="3">
                	<input type="file" name="append[]" id="append1">
                </td>
            </tr>
            <tr>
            	<td></td>
               <td> <input id="add_append" type="button" value="增加保单附件" onclick="CarInsuranceBiAdd.addAppend()" data-value="2" /></td>
            </tr>
        </table>
    </form>
</div>
<script type="text/javascript">
    var CarInsuranceBiAdd = {
        init: function(){
            $('#easyui-form-car-insurance-bi-add').find('input[name=insurer_company]').combobox({
                required: true,
                panelHeight: 'auto',
                valueField: 'value',
                textField: 'text',
                data: <?php echo json_encode($config['INSURANCE_COMPANY']); ?>
            });
        },
        addText:function(selValue){
    		var data = $("#add_text").attr('data-value');
    		var html ='<tr><td></td><td colspan="3"><select id="type'+data+'" name="type[]">';
    			<?php
    				foreach($types as $value){
    			?>
    				html += '<option value="<?=$value?>"'+(selValue=='<?=$value?>'?' selected':'')+'><?=$value?></option>';
    			<?php 
    				}
    			?>
    			html +='</select> <input class="easyui-textbox" name="money[]"/> <input type="button" value="移除" onclick="CarInsuranceBiAdd.delText('+data+')" /></td></tr>';
    		$("#add_text").parent().parent().before(html);
    		$("#add_text").attr('data-value',parseInt(data)+1);

    		$('#easyui-form-car-insurance-bi-add').find('input[name="money[]"]').textbox({
                onChange: function(){
                	CarInsuranceBiAdd.calAmount();
                }
            });
    	},
    	delText:function(data){
    		$("#type"+data).parent().parent().remove();
    		CarInsuranceBiAdd.calAmount();
    	},
    	addAppend:function(){
    		var data = $("#add_append").attr('data-value');
    		var html ='<tr><td></td><td colspan="3"><input type="file" name="append[]" id="append'+data+'">'+
    					'<input type="button" value="移除" onclick="CarInsuranceBiAdd.delAppend('+data+')" />'+
    				'</td></tr>';
    		$("#add_append").parent().parent().before(html);
    		$("#add_append").attr('data-value',parseInt(data)+1);
    	},
    	delAppend:function(data){
    		$("#append"+data).parent().parent().remove();
    	},
    	submitForm:function(){
    	    var form = $('#easyui-form-car-insurance-bi-add');
    	    if(!form.form('validate')){
    	        return false;
    	    }
    	    form.ajaxSubmit({
    			dataType : "json",
    			success : function(data){
    				if(data.status){
    	                $.messager.alert('新建成功',data.info,'info');
    	                $('#easyui-dialog-car-insurance-bi-add').dialog('close');
    	                $('#easyui-datagrid-car-insurance-business-insurance').datagrid('reload');
    	            }else{
    	                $.messager.alert('新建失败',data.info,'error');
    	            }
    			},
    			error: function(xhr){
    				$('#loadTips').hide();
    			}
    			
    		});
    	}
    };
    /**CarInsuranceBiAdd.addText('三者险');
    CarInsuranceBiAdd.addText('司乘险(司机)');
    CarInsuranceBiAdd.addText('司乘险(乘客)');
    CarInsuranceBiAdd.addText('玻璃险');
    CarInsuranceBiAdd.addText('涉水险');
    CarInsuranceBiAdd.addText('盗抢险');
    CarInsuranceBiAdd.addText('不计免赔险');
    CarInsuranceBiAdd.addText('无法找到第三方特约险');**/

    CarInsuranceBiAdd.init();
  //保费合计
	CarInsuranceBiAdd.calAmount = function(){
		var moneys = 0;
		$('#easyui-form-car-insurance-bi-add')
			.find('input[name="money[]"]')
			.each(function(){   
				if(!isNaN($(this).val()) && $(this).val()!=''){
					moneys += parseFloat($(this).val());
				}
		}); 
		$('#money_amount').text(moneys.toFixed(2));

		$('#easyui-form-car-insurance-bi-add').find('input[name="money[]"]').textbox({
            onChange: function(){
            	CarInsuranceBiAdd.calAmount();
            }
        });
	}
	CarInsuranceBiAdd.calAmount();

	<?php 
		$biInfo['car_id'] = $carId;
	?>	
	var oldData = <?php echo json_encode($biInfo); ?>;
	oldData.start_date = parseInt(oldData.start_date) > 0 ? formatDateToString(oldData.start_date) : '';
	oldData.end_date = parseInt(oldData.end_date) > 0 ? formatDateToString(oldData.end_date) : '';
	$('#easyui-form-car-insurance-bi-add').form('load',oldData);
</script>