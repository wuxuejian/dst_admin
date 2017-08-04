<script src="js/jquery.ajaxSubmit.js"></script>
<form action="<?php echo yii::$app->urlManager->createUrl(['car/insurance/other-add']); ?>" id="easyui-form-car-insurance-claim-add" style="width:100%;height:100%;" method="post" enctype="multipart/form-data">
    <div class="easyui-tabs" data-options="fit:true,border:false,onSelect:function(title,index){
        alert(index);
    }">
        <div title="出险基本信息" style="padding:20px;">
        	<table cellpadding="8" cellspacing="0">
        		<tr>
	                <td><div style="width:85px;text-align:right;">出险状态</div></td>
	                <td>
	                    <?php 
			            	$status_arr = array(1=>'已报案',2=>'定损维修',3=>'单证收集',4=>'已赔款',5=>'已结案');
			            ?>
                        <select
                            class="easyui-combobox"
                            name="battery_type"
                            data-options="editable:false,panelHeight:'auto'"
                            required="true"
                        >
                        	<option value=""></option>
                            <?php foreach($status_arr as $key=>$val){ ?>
                            <option value="<?=$key?>"><?=$val?></option>
                            <?php } ?>
                        </select>
	                </td>
	                <td><div style="width:85px;text-align:right;">出险日期</div></td>
	                <td>
	                    <input
	                        class="easyui-datebox"
	                        style="width:160px;"
	                        name="date"
	                        required="true"
	                        missingMessage="请选择日期！"
	                        validType="date"
	                    />
	                </td>
	            </tr>
	            <tr>
	                <td align="right"width="10%">省份</td>
	                <td width="23%">
	                	<select class="easyui-combobox" id="province_id" name="province_id" required="true" style="width:160px;" data-options="panelHeight:'auto',required:true"  editable=false >
	                		<option value=""></option>
	                        <?php foreach($provinces as $row){ ?>
	                            <option value="<?=$row['region_id']?>"><?=$row['region_name']?></option>
	                        <?php } ?>
	                    </select>
	                </td>
	                <td align="right"width="10%">城市</td>
	                <td width="23%">
	                	<select class="easyui-combobox" id="city_id" name="city_id" required="true" style="width:160px;" data-options="panelHeight:'auto',required:true"  editable=false >
	                		<option value=""></option>
	                    </select>
	                </td>
	                <td align="right"width="10%">地区</td>
	                <td width="23%">
	                	<select class="easyui-combobox" name="area_id" id="area_id" required="true" style="width:160px;" data-options="panelHeight:'auto',required:true"  editable=false >
	                		<option value=""></option>
	                    </select>
	                </td>
	            </tr>
	            <tr>
	                <td><div style="width:85px;text-align:right;">详细地址</div></td>
	                <td colspan="5">
	                    <input
	                        class="easyui-textbox"
	                        style="width:260px;"
	                        name="addr"
	                        required="true"
	                        validType="length[100]"
	                    />
	                </td>
	            </tr>
	            <tr>
	                <td><div style="width:85px;text-align:right;">报案人</div></td>
	                <td>
	                    <input
	                        class="easyui-textbox"
	                        style="width:160px;"
	                        name="informant_name"
	                        required="true"
	                        validType="length[50]"
	                    />
	                </td>
	                <td><div style="width:85px;text-align:right;">报案人电话</div></td>
	                <td>
	                    <input
	                        class="easyui-textbox"
	                        style="width:160px;"
	                        name="informant_tel"
	                        required="true"
	                        validType="length[20]"
	                    />
	                </td>
	            </tr>
	            <tr>
	                <td><div style="width:85px;text-align:right;">出险经过</div></td>
	                <td colspan="5">
	                    <input
	                        class="easyui-textbox"
	                        name="result"
	                        style="width:454px;height:40px;padding:0;" 
	                        data-options="multiline:true"
	                        validType="length[150]"
	                    />
	                </td>
	            </tr>
	            <tr>
	            	<td><div style="width:85px;text-align:right;">出险资料</div></td>
	                <td colspan="5">
	                	<input type="file" name="append1[]" id="append1_1">
	                </td>
	            </tr>
	            <tr>
	            	<td></td>
	               <td> <input id="add_append1" type="button" value="增加出险资料" onclick="CarInsuranceClaimAdd.addAppend1()" data-value="2" /></td>
	            </tr>
        	</table>
        </div>
        <div title="定损理赔信息" style="padding:20px;">
            <table cellpadding="8" cellspacing="0">
            	<tr>
	                <td><div style="width:85px;text-align:right;">保险公司</div></td>
	                <td colspan="3">
	                    <input style="width:200px;" name="insurer_company1" />
	                </td>
	            </tr>
	            <tr>
	            	<td></td><td id="insurance_text1"></td>
	            </tr>
	            <tr>
	            	<td colspan="4" id="insurance_info1"></td>
	            </tr>
	            <tr>
	                <td><div style="width:85px;text-align:right;">保险公司</div></td>
	                <td colspan="3">
	                    <input style="width:200px;" name="insurer_company2" />
	                </td>
	            </tr>
	            <tr>
	            	<td></td><td id="insurance_text2"></td>
	            </tr>
	            <tr>
	            	<td colspan="4" id="insurance_info2"></td>
	            </tr>
	            <tr>
	                <td><div style="width:85px;text-align:right;">保险公司</div></td>
	                <td colspan="3">
	                    <input style="width:200px;" name="insurer_company3" />
	                </td>
	            </tr>
	            <tr>
	            	<td></td><td id="insurance_text3"></td>
	            </tr>
	            <tr>
	            	<td colspan="4" id="insurance_info3"></td>
	            </tr>
	            <tr>
	                <td><div style="width:85px;text-align:right;">车辆维修备注</div></td>
	                <td colspan="5">
	                    <input
	                        class="easyui-textbox"
	                        name="servicing_note"
	                        style="width:454px;height:40px;padding:0;" 
	                        data-options="multiline:true"
	                        validType="length[200]"
	                    />
	                </td>
	            </tr>
	            <tr>
	               <td colspan="4">定损总额：<span id='damage_amount'></span></td>
	            </tr>
	            <tr>
	               <td colspan="4">理赔总额：<span id='claim_amount'></span></td>
	            </tr>
            </table>
        </div>  
        <div title="公司财务信息" style="padding:20px;">
            <table cellpadding="8" cellspacing="0">
            	<tr>
            		<td></td>
	                <td>转账时间：<input type="text" name="transfer_time[]"/></td>
	                <td>转账金额：<input type="text" name="transfer_money[]"/></td>
	            </tr>
	            <tr>
	            	<td></td>
	            	<td>账户名：<input type="text" name="transfer_account_name[]"/></td>
	            	<td>转账账户：<input type="text" name="transfer_account[]"/></td>
	            </tr>
	            <tr>
	            	<td></td>
	               <td> <input id="add_transfer" type="button" value="增加转账记录" onclick="CarInsuranceClaimAdd.addTransfer()" data-value="2" /></td>
	            </tr>
	            <tr>
	            	<td><div style="width:85px;text-align:right;">财务附件</div></td>
	                <td colspan="3">
	                	<input type="file" name="append2[]" id="append2_1">
	                </td>
	            </tr>
	            <tr>
	            	<td></td>
	               <td> <input id="add_append2" type="button" value="增加出险资料" onclick="CarInsuranceClaimAdd.addAppend2()" data-value="2" /></td>
	            </tr>
			</table>
        </div>
    </div>
</form>

<script type="text/javascript">
	var insurance_text_arr = <?=json_encode($insurance_text_arr)?>;	//保险公司对应险种
	<?php 
		array_push($config['INSURANCE_COMPANY'], array('value'=>'','text'=>'无'));
	?>
    var CarInsuranceClaimAdd = {
        init: function(){
        	var _form = $("#easyui-form-car-insurance-claim-add"); 
        	$(_form).find('input[name=insurer_company1]').combobox({
                required: false,
                panelHeight: 'auto',
                valueField: 'value',
                textField: 'text',
                data: <?php echo json_encode($config['INSURANCE_COMPANY']); ?>,
                onChange: function (n,o) {
                	CarInsuranceClaimAdd.changeInsurerCompany(n,1);
                }
            });
        	$(_form).find('input[name=insurer_company2]').combobox({
                required: false,
                panelHeight: 'auto',
                valueField: 'value',
                textField: 'text',
                data: <?php echo json_encode($config['INSURANCE_COMPANY']); ?>,
                onChange: function (n,o) {
                	CarInsuranceClaimAdd.changeInsurerCompany(n,2);
                }
            });
        	$(_form).find('input[name=insurer_company3]').combobox({
                required: false,
                panelHeight: 'auto',
                valueField: 'value',
                textField: 'text',
                data: <?php echo json_encode($config['INSURANCE_COMPANY']); ?>,
                onChange: function (n,o) {
                	CarInsuranceClaimAdd.changeInsurerCompany(n,3);
                }
            });
            //设置onchange事件
            $('select[name=province_id]',_form).combobox({
            	onChange: function (n,o) {
            		var province_id = $('#province_id').combobox('getValue');
            		$.ajax({
            	           url:'<?php echo yii::$app->urlManager->createUrl(['charge/charge-station/get-region-list']); ?>',
            	           type:'get',
            	           data:{parent_id:province_id},
            	           dataType:'json',
            	           success:function(data){
								$('#city_id').combobox({
            	                   valueField:'region_id',
            	                   textField:'region_name',
            	                   editable: false,
            	                   panelHeight:'auto',
            	                   data: data
            	               });
								$('#area_id').combobox({
	            	                   valueField:'region_id',
	            	                   textField:'region_name',
	            	                   editable: false,
	            	                   panelHeight:'auto',
	            	                   data: []
	            	            });
								$('#city_id').combobox('setValues','');
								$('#area_id').combobox('setValues','');
            	            }
            	   	});
            	}
            });
            $('select[name=city_id]',_form).combobox({
            	onChange: function (n,o) {
            		var city_id = $('#city_id').combobox('getValue');
            		$.ajax({
            	           url:'<?php echo yii::$app->urlManager->createUrl(['charge/charge-station/get-region-list']); ?>',
            	           type:'get',
            	           data:{parent_id:city_id},
            	           dataType:'json',
            	           success:function(data){
								$('#area_id').combobox({
            	                   valueField:'region_id',
            	                   textField:'region_name',
            	                   editable: false,
            	                   panelHeight:'auto',
            	                   data: data
            	               });
								$('#area_id').combobox('setValues','');
            	            }
            	   	});
            	}
            });
        },
        changeInsurerCompany:function(n,id){	//选择保险公司加载信息
            var single_compulsory = true;
        	if(n==''){
            	$("#insurance_text"+id).html('');
            	$("#insurance_info"+id).html('');
            	return false;
            }
            var text = '';
            if(!isNaN(insurance_text_arr[n])){//交强险
            	text += '<input type="checkbox" name="insurance'+id+'[]" value="交强险">交强险';
            }else {
            	single_compulsory = false;
                for(var i=0;i<insurance_text_arr[n].length;i++){
                    if(!isNaN(insurance_text_arr[n][i])){	//交强险
                    	text += '<input type="checkbox" name="insurance'+id+'[]" value="交强险">交强险';
                    }else {
	                    for(var j=0;j<insurance_text_arr[n][i].length;j++){
	                    	text += '<input type="checkbox" name="insurance'+id+'[]" value="'+insurance_text_arr[n][i][j][0]+'">'+insurance_text_arr[n][i][j][0];
	                    }
                    }
                }
            }
            $("#insurance_text"+id).html(text);
			var info='';
			info += '三者车定损：<input type="text" damage="damage[]" name="par1_'+id+'"/> ';
			info += '三者物损：<input type="text" damage="damage[]" name="par2_'+id+'"/> ';
			info += '三者人伤：<input type="text" damage="damage[]" name="par3_'+id+'"/><br/><br/>';
			if(!single_compulsory){
				info += '标的车定损：<input type="text" damage="damage[]" name="par4_'+id+'"/><br/><br/>';
			}
			
			info += '赔付时间：<input class="easyui-datebox" style="width:160px;" name="par5_'+id+'" validType="date"/> ';
			info += '赔付金额：<input type="text" claim="claim[]" name="par6_'+id+'"/>';
            $("#insurance_info"+id).html(info);

            $('#easyui-form-car-insurance-claim-add').find('input[damage="damage[]"]').textbox({
                onChange: function(){
                	CarInsuranceClaimAdd.calDamageAmount();
                }
            });
            $('#easyui-form-car-insurance-claim-add').find('input[claim="claim[]"]').textbox({
                onChange: function(){
                	CarInsuranceClaimAdd.calClaimAmount();
                }
            });
        },
        calDamageAmount:function(){	//计算定损总额
        	var moneys = 0;
    		$('#easyui-form-car-insurance-claim-add')
    			.find('input[damage="damage[]"]')
    			.each(function(){   
    				if(!isNaN($(this).val()) && $(this).val()!=''){
    					moneys += parseFloat($(this).val());
    				}
    		}); 
    		$('#damage_amount').text(moneys);
        },
        calClaimAmount:function(){	//计算理赔总额
        	var moneys = 0;
    		$('#easyui-form-car-insurance-claim-add')
    			.find('input[claim="claim[]"]')
    			.each(function(){   
    				if(!isNaN($(this).val()) && $(this).val()!=''){
    					moneys += parseFloat($(this).val());
    				}
    		}); 
    		$('#claim_amount').text(moneys);
        }
        ,
        addText:function(){
    		
    	},
    	delText:function(data){
    		$("#type"+data).parent().parent().remove();
    		CarInsuranceClaimAdd.calAmount();
    	},
    	addTransfer:function(){	//增加转账记录
    		var data = $("#add_append1").attr('data-value');
    		var html= '<tr>'+
                '<td></td><td>转账时间：<input type="text" name="transfer_time[]"/></td>'+
                '<td>转账金额：<input type="text" name="transfer_money[]"/></td>'+
	            '</tr>'+
	            '<tr>'+
	            	'<td></td><td>账户名：<input type="text" name="transfer_account_name[]"/></td>'+
	            	'<td>转账账户：<input type="text" name="transfer_account[]"/></td>'+
	            '</tr>';
    		$("#add_transfer").parent().parent().before(html);
    		$("#add_transfer").attr('data-value',parseInt(data)+1);
    	}
    	,
    	addAppend1:function(){
    		var data = $("#add_append1").attr('data-value');
    		var html ='<tr><td></td><td colspan="5"><input type="file" name="append1[]" id="append1_'+data+'">'+
    					'<input type="button" value="移除" onclick="CarInsuranceClaimAdd.delAppend1('+data+')" />'+
    				'</td></tr>';
    		$("#add_append1").parent().parent().before(html);
    		$("#add_append1").attr('data-value',parseInt(data)+1);
    	},
    	delAppend1:function(data){
    		$("#append1_"+data).parent().parent().remove();
    	},
    	addAppend2:function(){
    		var data = $("#add_append2").attr('data-value');
    		var html ='<tr><td></td><td colspan="5"><input type="file" name="append2[]" id="append2_'+data+'">'+
    					'<input type="button" value="移除" onclick="CarInsuranceClaimAdd.delAppend2('+data+')" />'+
    				'</td></tr>';
    		$("#add_append2").parent().parent().before(html);
    		$("#add_append2").attr('data-value',parseInt(data)+1);
    	},
    	delAppend2:function(data){
    		$("#append2_"+data).parent().parent().remove();
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
    CarInsuranceClaimAdd.init();
</script>