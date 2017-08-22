<script src="js/jquery.ajaxSubmit.js"></script>
<table id="easyui-datagrid-process-car-transfer1-edit"></table>
<!-- toolbar start -->
<div id="process-car-transfer1-add-toolbar">
    <form id="easyui-form-process-car-transfer1-index-edit" class="easyui-form" method="post" enctype="multipart/form-data">
		<input type="hidden" name="id" />
		<table cellpadding="8" cellspacing="0">
		<tr>
			<td><div style="width:85px;text-align:right;">钉钉审批号</div></td>
			<td>
				<input
					class="easyui-textbox"
					style="width:160px;"
					name="dd_number"
					required="true"
					missingMessage="请输入钉钉审批号！"
				/>
			</td>
			<td><div style="width:85px;text-align:right;">发起日期</div></td>
			<td>
				<input
					class="easyui-datebox"
					style="width:160px;"
					name="add_time"
					value="<?=date('Y-m-d')?>"
					required="true"
					disabled="true"
					validType="date"
				>
			</td>
		</tr>
		<tr>
			<td><div style="width:85px;text-align:right;">流程附件</div></td>
			<td colspan="5">
				<input type="file" name="attachment"/>（PDF格式的钉钉流程文件）
			</td>
		</tr>
		<tr>
			<td><div style="width:85px;text-align:right;">需求提报人</div></td>
			<td>
				<input
					class="easyui-textbox"
					style="width:160px;"
					name="originator"
					required="true"
					missingMessage="请输入需求提报人！"
				/>
			</td>
		</tr>
		<tr>
			<td><div style="width:85px;text-align:right;">提报人所属运营公司</div></td>
			<td colspan="5">
				<input class="easyui-combotree" name="originator_operating_company_id"
				   data-options="
						width:454,
						url: '<?php echo yii::$app->urlManager->createUrl(['operating/combotree/get-operating-company']); ?>',
						editable: false,
						panelHeight:'auto',
						lines:false,
						required:true,
						missingMessage:'请选择运营公司'
				   "
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
    var ProcessCarTransfer1Edit = new Object();
    //提交表单
    ProcessCarTransfer1Edit.submitForm = function(){
        var form = $('#easyui-form-process-car-transfer1-index-edit');
        if(!form.form('validate')){
            return false;
        }
		form.ajaxSubmit({
			url: "<?php echo yii::$app->urlManager->createUrl(['process/car-transfer/edit1']); ?>",
			dataType : "json",
			success : function(data){
				if(data.status){
                    $.messager.alert('新建成功',data.info,'info');
                    $('#easyui-dialog-process-car-transfer1-edit').dialog('close');
                    $('#easyui-datagrid-process-car-transfer1').datagrid('reload');
                }else{
                    $.messager.alert('新建失败',data.info,'error');
                }
			},
			error: function(xhr){
				$.messager.alert('新建失败','500错','error');
			}
			
		});
    }
	//表单赋值
	var oldData = <?php echo json_encode($carTransferInfo); ?>;
	oldData.add_time = oldData.add_time > 0 ? formatDateToString(oldData.add_time) : ''
	$('#easyui-form-process-car-transfer1-index-edit').form('load',oldData);
</script>