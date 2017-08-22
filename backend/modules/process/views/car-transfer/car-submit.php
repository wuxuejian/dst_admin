<script src="js/jquery.ajaxSubmit.js"></script>
<table id="easyui-datagrid-process-car-transfer3-car-submit"></table>
<!-- toolbar start -->
<div id="process-car-transfer3-car-submit-toolbar">
    <form id="easyui-form-process-car-transfer3-car-submit" class="easyui-form" method="post">
		<input type="hidden" name="id" value="<?=$transferDetailsInfo['id']?>"/>
		<table cellpadding="8" cellspacing="0">
		<tr>
			<td><div style="width:150px;text-align:right;">车辆品牌：</div></td>
			<td>
				<?=$transferDetailsInfo['car_brand_name']?>
			</td>
			<td><div style="width:150px;text-align:right;">车型：</div></td>
			<td>
				<?=$transferDetailsInfo['car_model_name']?>
			</td>
		</tr>
		<tr>
			<td><div style="width:150px;text-align:right;">车牌：</div></td>
			<td>
				<?=$transferDetailsInfo['plate_number']?>
			</td>
			<td><div style="width:150px;text-align:right;">车架号：</div></td>
			<td>
				<?=$transferDetailsInfo['vehicle_dentification_number']?>
			</td>
		</tr>
		<tr>
			<td><div style="width:150px;text-align:right;">调入前所属运营公司：</div></td>
			<td >
				<?=$transferDetailsInfo['pre_operating_company_name']?>
			</td>
		
			<td><div style="width:150px;text-align:right;">调入后所属运营公司：</div></td>
			<td>
				<?=$transferDetailsInfo['after_operating_company_name']?>
			</td>
		</tr>
		<tr>
			<td><div style="width:150px;text-align:right;">实际到车日期</div></td>
			<td>
				<input
					class="easyui-datebox"
					style="width:160px;"
					name="end_time"
					required="true"
					missingMessage="请选择实际到车日期！"
					validType="date"
				>
			</td>
			<td><div style="width:150px;text-align:right;">证件是否齐全</div></td>
			<td>
				<input name="credentials_status" style="width:200px" required="true">
			</td>
		</tr>
		<tr>
			<td><div style="width:150px;text-align:right;">车辆异常情况</div></td>
			<td colspan="3">
				<input
					class="easyui-textbox"
					style="width:550px;"
					name="abnormal_note"
				/>
			</td>
		</tr>
		
		</table>
    </form>
</div>
<!-- toolbar end -->
<!-- 窗口 -->
<!-- 窗口 -->
<script>
    var ProcessCarTransfer3CarSubmit = new Object();
	//构建查询表单
        var searchForm = $('#easyui-form-process-car-transfer3-car-submit');
        searchForm.submit(function(){
            var data = {};
            var searchCondition = $(this).serializeArray();
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = searchCondition[i]['value'];
            }
            easyuiDatagrid.datagrid('load',data);
            return false;
        });
        searchForm.find('input[name=credentials_status]').combobox({
            valueField:'value',
            textField:'text',
            data: [{"value":"1","text":"是"},{"value":"2","text":"否"}],
            editable: false
        });
    //构建查询表单结束
    //提交表单
    ProcessCarTransfer3CarSubmit.submitForm = function(){
        var form = $('#easyui-form-process-car-transfer3-car-submit');
        if(!form.form('validate')){
            return false;
        }
		$.messager.confirm('确定提交','提交后会变更车辆所属运营公司，您确定要提交吗？',function(r){
            if(r){
				form.ajaxSubmit({
					url: "<?php echo yii::$app->urlManager->createUrl(['process/car-transfer/car-submit']); ?>",
					dataType : "json",
					success : function(data){
						if(data.status){
							$.messager.alert('操作成功',data.info+'重新加载更新状态！','info');   
							$('#easyui-dialog-process-car-transfer3-car-submit').dialog('close');
							$('#easyui-datagrid-process-car-transfer3-details').datagrid('reload');
						}else{
							$.messager.alert('操作失败',data.info,'error');   
						}
					},
					error: function(xhr){
						$.messager.alert('操作失败','500错','error');
					}
					
				});
            }
        });
    }
</script>