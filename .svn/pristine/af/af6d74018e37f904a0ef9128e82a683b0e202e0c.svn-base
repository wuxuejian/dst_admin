<script src="js/jquery.ajaxSubmit.js"></script>
<div style="padding:10px 40px 20px 40px">
    <form action="<?php echo yii::$app->urlManager->createUrl(['process/contract-approval/add']); ?>" id="easyui-form-process-contract-approval-add" class="easyui-form" method="post" enctype="multipart/form-data">
    	<table cellpadding="5" cellspacing="0" width="100%" border="0">
    		<tr>
    			<td>合同类型：</td>
    			<td>
    			    <select class="easyui-combobox" id="contract_type" required="true" name="contract_type" style="width:173px;" editable="false" panelHeight="auto">
                        <option value=""></option>
                        <option value="1">采购类</option>
                        <option value="2">营销类</option>
                        <option value="3">行政类</option>
                        <option value="4">基建类</option>
                        <option value="5">专项服务类</option>
                        <option value="6">知识产权类</option>
                        <option value="10">其它</option>
                    </select>
    			</td>
    			<td>合同格式：</td>
    			<td>
    			    <select class="easyui-combobox" required="true" id="contract_format" name="contract_format" style="width:173px;" editable="false" panelHeight="auto">
                        <option value=""></option>
                        <option value="1">公司标准合同</option>
                        <option value="2">公司非标准合同</option>
                        <option value="3">政府范本合同</option>
                        <option value="4">对方起草合同</option>
                    </select>
    			</td>
    		</tr>
    		<tr>
    		    <td>合同名称：</td>
    		    <td>
    		        <input class="easyui-textbox" required="true" name="contract_name" id="contract_name" />
    		    </td>
    			<td>合同编号：</td>
    			<td>
    			    <input class="easyui-textbox" required="true" name="contract_no" id="contract_no" />
    			</td>
    		</tr>
    		<tr>
    		    <td>对方公司名称：</td>
    		    <td colspan="3">
    		        <input class="easyui-textbox" style="width:465px;" required="true" name="customer_company_name" id="customer_company_name" />
    		    </td>
    		</tr>
    		<tr>
    		    <td>对方联系人：</td>
    		    <td>
    		        <input class="easyui-textbox" required="true" name="customer_contact_name" id="customer_contact_name" />
    		    </td>
    			<td>联系电话：</td>
    			<td>
    			    <input class="easyui-textbox" required="true" name="customer_contact_tel" id="customer_contact_tel" />
    			</td>
    		</tr>
    		<tr>
    		    <td>合同要点：</td>
    		    <td colspan="3">
    		    	<input
                        class="easyui-textbox"
                        name='contract_cruces'
                        id='contract_cruces'
                        data-options="multiline:true"
                        style="height:60px;width:465px;"
                        required="true"
                        prompt=""
                        validType="length[500]"
                        />
    		    </td>
    		</tr>
    		<tr>
    			<td>合同标的额：</td>
    			<td>
    			    <select class="easyui-combobox" id="money_type" required="true" name="money_type" style="width:173px;" editable="false" panelHeight="auto">
                        <option value=""></option>
                        <option value="1">有具体金额</option>
                        <option value="2">无具体金额</option>
                    </select>
    			</td>
    			<td>金额：</td>
    			<td>
    			    <input class="easyui-textbox" required="true" name="money" id="money" />
    			</td>
    		</tr>
    		<tr>
    			<td>收款/付款：</td>
    			<td>
    			    <select class="easyui-combobox" id="business_type" required="true" name="business_type" style="width:173px;" editable="false" panelHeight="auto">
                        <option value=""></option>
                        <option value="1">收款</option>
                        <option value="2">付款</option>
                    </select>
    			</td>
    			<td>收款/付款时间：</td>
    			<td>
    			    <input
	                        class="easyui-datebox"
	                        style="width:100px;"
	                        id="business_time"
	                        name="business_time"
	                        validType="date"
	                        required="true"
	                    />
    			</td>
    		</tr>
    		<tr>
    		    <td>收款/付款方式：</td>
    		    <td colspan="3">
    		    	<input type="radio" name="business_way" value="现金">现金</input>
                	<input type="radio" name="business_way" value="支票">支票</input>
    		    	<input type="radio" name="business_way" value="汇款">汇款</input>
    		    	<input type="radio" name="business_way" value="other">其它方式</input>
    		    	<input class="easyui-textbox" name="other_business_way" id="other_business_way" />
    		    </td>
    		</tr>
    		<tr>
    			<td>经办人：</td>
    			<td>
    			    <input class="easyui-textbox" name="oper_name" id="oper_name" required="true" />
    			</td>
    			<td>所属部门：</td>
                <td>
                    <select class="easyui-combobox" name="oper_department_id" required="true" style="width:173px;" editable="false" panelHeight="auto">
                    		<option value=""></option>
                        <?php foreach($department as $val) { ?>
                            <option value="<?php echo $val['id']; ?>"><?php echo $val['name']; ?></option>
                        <?php } ?>
                    </select>
                </td>
    		</tr>
    		<tr>
    			<td>经办人电话：</td>
    			<td>
    			    <input class="easyui-textbox" required="true" name="oper_tel" id="oper_tel" />
    			</td>
    			<td>合同份数：</td>
    			<td>
    			    <input class="easyui-textbox" required="true" name="contract_num" id="contract_num" />
    			</td>
    		</tr>
    		<tr>
    			<td>审批报送时间：</td>
    			<td>
    			    <input
	                        class="easyui-datebox"
	                        style="width:100px;"
	                        id="approval_start_time"
	                        name="approval_start_time"
	                        validType="date"
	                        required="true"
	                    />
    			</td>
    			<td>要求完成审批时间：</td>
    			<td>
    			    <input
	                        class="easyui-datebox"
	                        style="width:100px;"
	                        id="approval_end_time"
	                        name="approval_end_time"
	                        validType="date"
	                        required="true"
	                    />
    			</td>
    		</tr>
    		<tr>
    			<td>上传合同文本：</td>
    			<td colspan="3">
    			    <input type="file" name="contract" value="上传"> （请上传PDF格式的合同附件）
    			</td>
    		</tr>
    	</table>
    </form>
</div>
<script>
    var ProcessContractApprovalAdd = new Object();
  //提交表单
    ProcessContractApprovalAdd.submitForm = function(){
        var form = $('#easyui-form-process-contract-approval-add');
        if(!form.form('validate')){
            return false;
        }
        form.ajaxSubmit({
			dataType : "json",
			success : function(data){
				if(data.status){
                    $.messager.alert('新建成功',data.info,'info');
                    $('#easyui-datagrid-process-contract-approval-index-add').dialog('close');
                    $('#easyui-datagrid-process-contract-approval-index').datagrid('reload');
                }else{
                    $.messager.alert('新建失败',data.info,'error');
                }
			},
			error: function(xhr){
				$('#loadTips').hide();
			}
			
		});
    }
    $(document).ready(function () {
    	$("#money_type").combobox({
    		onChange: function (n,o) {
    			if(n==1){
    				$('#money').textbox({ 
        				required:true
        			})
    			}else if(n==2){
    				$('#money').textbox({ 
        				required:false
        			})
    			}
    		}
    	});

	});
</script>