<table id="easyui-dialog-car-insurance-tci-edit-pd-index"></table>
<script src="js/jquery.ajaxSubmit.js"></script>
<div style="padding:15px"> 
    <form action="<?php echo yii::$app->urlManager->createUrl(['car/insurance/tci-edit']); ?>" id="easyui-form-car-insurance-tci-edit" class="easyui-form" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $tciInfo['id'] ?>"/>
       
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
            </tr>
            <?php 
            	$append_urls = json_decode($tciInfo['append_urls']);
                if(!$append_urls){
                    $append_urls = array();
                }

            	foreach ($append_urls as $index=>$row){
                /*var_dump($index);*/
            ?>
            	<tr>
	                <td colspan="4">
	                	附件地址：<?=$row?>"
	                	<input type="hidden" name="append_url[]" id="append_url<?=$index?>" value="<?=$row?>">
	                	<input type="button" value="删除" onclick="CarInsuranceTciEdit.del1(<?=$index?>)">
	                </td>
	            </tr>
            <?php 
            	}
            ?>
            
            <tr>
            	<td></td>
               <td> <input id="add_append" type="button" value="增加保单附件" onclick="CarInsuranceTciEdit.addAppend()" data-value="<?=count($append_urls)+1?>" /></td>
            </tr>
            <?php 
                /*$append_pd = json_decode($pdinfo['append_urls']);
                if(!$append_pd){
                    $append_pd = array();
                }*/
                //var_dump($append_pd);exit;
                foreach ($pdinfo as $index=>$row){

            ?>
                <tr>
                   
                    <td colspan="4">
                        <input style="width:200px;text-align:right;" type="button" value="已添加批单，批单号：<?=$row['number']?>" onclick="CarInsuranceTciEdit.pdEdit(<?=$row['id'];?>)">
                        <input type="hidden" name="del_ids[]" id="append_url_pd<?=$index?>" value="<?=$row['id']?>">
                        <input type="button" value="删除" onclick="CarInsuranceTciEdit.del2(<?=$index?>,<?=$row['id']?>)">
                    </td>
                </tr>
            <?php 
                }
            ?>  
            <tr>
                <td></td>
               <td> <input id="add_append" type="button" onclick="CarInsuranceTciEdit.addPd()" value="添加批单"  data-value="" /></td>
            </tr>
            

        </table>
    </form>
</div>
<div id="easyui-dialog-insurance-company-index-add123"></div>
<div id="easyui-dialog-insurance-company-index-pdedit"></div>
<script>
    var oldData = <?php echo json_encode($tciInfo); ?>;
    oldData.start_date = parseInt(oldData.start_date) > 0 ? formatDateToString(oldData.start_date) : '';
    oldData.end_date = parseInt(oldData.end_date) > 0 ? formatDateToString(oldData.end_date) : '';
    $('#easyui-form-car-insurance-tci-edit').form('load',oldData);

    var CarInsuranceTciEdit = new Object();
    CarInsuranceTciEdit.del1 = function(index){
    	$("#append_url"+index).parent().parent().remove();
    }
    CarInsuranceTciEdit.addAppend = function()
	{
		var data = $("#add_append").attr('data-value');
		var html ='<tr><td></td><td colspan="3"><input type="file" name="append[]" id="append'+data+'">'+
					'<input type="button" value="移除" onclick="CarInsuranceTciEdit.delAppend('+data+')" />'+
				'</td></tr>';
		$("#add_append").parent().parent().before(html);
		$("#add_append").attr('data-value',parseInt(data)+1);
	}
    CarInsuranceTciEdit.delAppend = function(data){
		$("#append"+data).parent().parent().remove();
	}

    //批单删除
    //var ids = new Array();
    CarInsuranceTciEdit.del2 = function(index,id){
        $("#append_url_pd"+index).parent().parent().remove();
        //alert(id);
        
        /*ids.push(id)
        //alert(ids);
        del_ids = ids.join(",");
        $("#append_url_pd").val(del_ids);*/
    }
    //交强险批单
        $('#easyui-dialog-insurance-company-index-add123').dialog({
            title: '&nbsp;添加交强险批单',
            iconCls:'icon-add', 
            width: '800',   
            height: '400',   
            closed: true,   
            cache: true,   
            modal: true,
            maximizable: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    /*var form = $('#easyui-form-customer-contract-record-car-manage-back-car');
                    //CustomerContractRecordEdit.backCar2(form.find('input[name=back_time]').val());
                    //$('#easyui-dialog-insurance-company-index-add123').dialog('close');
                    if(!form.form('validate')) return false;
                    var data = form.serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['car/insurance/pd-add']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('操作成功',data.info,'info');
                                $('#easyui-dialog-insurance-company-index-add123').dialog('close');
                            }else{
                                $.messager.alert('操作失败',data.info,'error');
                            }
                        }
                    });*/
                    CarInsuranceTciAddPd.submitForm(); 
                }
            },{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui-dialog-insurance-company-index-add123').dialog('close');
                   // $('#easyui-dialog-car-insurance-tci-edit').dialog('close');
                   
                }
            }],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
//交强险批单
    CarInsuranceTciEdit.addPd = function(){
        $('#easyui-dialog-insurance-company-index-add123').dialog('open');
        $('#easyui-dialog-insurance-company-index-add123').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/insurance/pd-add','id'=>$tciInfo['id']]); ?>");
    }

    //修改加强险批单
    $('#easyui-dialog-insurance-company-index-pdedit').dialog({
            title: '&nbsp;修改交强险批单',
            iconCls:'icon-add', 
            width: '800',   
            height: '400',   
            closed: true,   
            cache: true,   
            modal: true,
            maximizable: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    
                    CarInsuranceTciAddEdit.submitForm(); 
                }
            },{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui-dialog-insurance-company-index-pdedit').dialog('close');
                }
            }],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
    //修改交强险批单
    CarInsuranceTciEdit.pdEdit = function(id){
        //alert(id);
        $('#easyui-dialog-insurance-company-index-pdedit').dialog('open');
        $('#easyui-dialog-insurance-company-index-pdedit').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/insurance/pd-edit']); ?>&id="+id);
    }




    CarInsuranceTciEdit.submitForm = function(){
	    var form = $('#easyui-form-car-insurance-tci-edit');
	    if(!form.form('validate')){
	        return false;
	    }
	    form.ajaxSubmit({
			dataType : "json",
			success : function(data){
				if(data.status){
	                $.messager.alert('修改成功',data.info,'info');
	                $('#easyui-dialog-car-insurance-tci-edit').dialog('close');
	                $('#easyui-datagrid-car-insurance-traffic-compulsory-insurance').datagrid('reload');
	            }else{
	                $.messager.alert('修改失败',data.info,'error');
	            }
			},
			error: function(xhr){
				$('#loadTips').hide();
			}
			
		});
	}
</script>