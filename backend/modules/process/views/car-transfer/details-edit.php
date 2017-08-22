<script src="js/jquery.ajaxSubmit.js"></script>
<table id="easyui-datagrid-process-car-transfer2-details-edit"></table>
<!-- toolbar start -->
<div id="process-car-transfer2-details-edit-toolbar">
    <form id="easyui-form-process-car-transfer2-details-edit" class="easyui-form" method="post">
		<input type="hidden" name="id" value="<?=$detailsInfo['id']?>"/>
		<table cellpadding="8" cellspacing="0">
		<tr>
			<td><div style="width:85px;text-align:right;">车辆品牌</div></td>
			<td>
				<?=$detailsInfo['car_brand_name']?>
			</td>
			<td><div style="width:85px;text-align:right;">车型</div></td>
			<td>
				<?=$detailsInfo['car_model_name']?>
			</td>
		</tr>
		
		<tr>
			<td><div style="width:85px;text-align:right;">车辆</div></td>
			<td>
				<input
					id="easyui-form-process-car-transfer2-details-edit-combogrid"
					name="car_id"
					style="width:180px;"
					/>
			</td>
			<td><div style="width:85px;text-align:right;">发车日期</div></td>
			<td>
				<input
					class="easyui-datebox"
					style="width:160px;"
					name="start_time"
					required="true"
					missingMessage="请选择开始时间！"
					validType="date"
				>
			</td>
		</tr>
		<tr>
			<td><div style="width:85px;text-align:right;">承运商</div></td>
			<td>
				<input
					class="easyui-textbox"
					style="width:160px;"
					name="transport_company"
					required="true"
				/>
			</td>
			<td><div style="width:85px;text-align:right;">承运人电话</div></td>
			<td>
				<input
					class="easyui-textbox"
					style="width:160px;"
					name="transport_tel"
					required="true",
					invalidMessage='手机号格式错误！',
					validType='match[/^1[34578][0-9]{9}$/]'
				/>
			</td>
		</tr>
		<tr>
			<td><div style="width:85px;text-align:right;">运费</div></td>
			<td colspan="5">
				<input
					class="easyui-textbox"
					style="width:160px;"
					name="transport_money"
					validType="money"
					required="true"
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
    var ProcessCarTransfer2DetailsEdit = new Object();
    //提交表单
    ProcessCarTransfer2DetailsEdit.submitForm = function(){
        var form = $('#easyui-form-process-car-transfer2-details-edit');
        if(!form.form('validate')){
            return false;
        }
		form.ajaxSubmit({
			url: "<?php echo yii::$app->urlManager->createUrl(['process/car-transfer/details-edit']); ?>",
			dataType : "json",
			success : function(data){
				if(data.status){
                    $.messager.alert('新建成功',data.info,'info');
                    $('#easyui-dialog-process-car-transfer2-details-edit').dialog('close');
                    $('#easyui-datagrid-process-car-transfer2-details').datagrid('reload');
                }else{
                    $.messager.alert('新建失败',data.info,'error');
                }
			},
			error: function(xhr){
				$.messager.alert('新建失败','500错','error');
			}
			
		});
    }
	//初始化-车辆combogrid
	$('#easyui-form-process-car-transfer2-details-edit-combogrid').combogrid({
		panelWidth: 450,
		panelHeight: 200,
		required: true,
		missingMessage: '请输入车牌号/车架号检索后从下拉列表里选择一项！',
		onLoadSuccess:function(){
			$('#easyui-form-process-car-transfer2-details-edit-combogrid').combogrid('setValues',"<?=$detailsInfo['plate_number']?>");
		},
		onHidePanel:function(){
			var _combogrid = $(this);
			var value = _combogrid.combogrid('getValue');
			var text = _combogrid.combogrid('textbox').val();
			var row = _combogrid.combogrid('grid').datagrid('getSelected');
			if(!row){ //没有选择表格行但输入有检索字符串时，提示并清除检索字符串
				/*if(text && value == text){
					$.messager.show(
						{
							title: '无效值',
							msg:'【' + text + '】不是有效值！请重新输入车牌号/车架号检索后，从下拉列表里选择一项！'
						}
					);
					_combogrid.combogrid('clear');
				}*/
			}else{ //注意：若选择了表格行但是原本应显示为text的车牌号不存在，则改成显示车架号为text！
				if(!row.plate_number){
					_combogrid.combogrid('setText', row.vehicle_dentification_number);
					//_combogrid.combogrid('textbox').val(row.vehicle_dentification_number); //这种不好，因为当输入框再次获得焦点时会自动显示value而非text.
				}
			}
		},
		delay: 800,
		mode:'remote',
		idField: 'id',
		textField: 'plate_number',
		url: '<?= yii::$app->urlManager->createUrl(['car/stock/get-cars-by-add']); ?>',
		method: 'get',
		scrollbarSize:0,
		pagination: true,
		pageSize: 10,
		pageList: [10,20,30],
		fitColumns: true,
		rownumbers: true,
		columns: [[
			{field:'id',title:'车辆ID',width:40,align:'center',hidden:true},
			{field:'plate_number',title:'车牌号',width:100,align:'center'},
			{field:'vehicle_dentification_number',title:'车架号',width:150,align:'center'}
		]]
	});
	<?php
		unset($detailsInfo['car_id']);
	?>
	var oldData = <?php echo json_encode($detailsInfo); ?>;
	$('#easyui-form-process-car-transfer2-details-edit').form('load',oldData);
</script>