<script src="js/jquery.ajaxSubmit.js"></script>
<table id="easyui-datagrid-process-car-transfer1-list-add"></table>
<!-- toolbar start -->
<div id="process-car-transfer1-list-add-toolbar">
    <form id="easyui-form-process-car-transfer1-list-add" class="easyui-form" method="post">
		<input type="hidden" name="transfer_id" value="<?=$transfer_id?>"/>
		<table cellpadding="8" cellspacing="0">
		<tr>
			<td><div style="width:85px;text-align:right;">车辆品牌</div></td>
			<td>
				<input class="easyui-combotree" name="car_brand_id"
				   data-options="
						width:160,
						url: '<?php echo yii::$app->urlManager->createUrl(['car/combotree/get-car-brands']); ?>',
						editable: false,
						panelHeight:'auto',
						lines:false,
						required: true,
						onSelect:ProcessCarTransfer1ListAdd.CarBrandSelect
				   "
				/>
			</td>
			<td><div style="width:85px;text-align:right;">车型</div></td>
			<td>
				<select
                        id="process-car-transfer1-list-add-car-type"
                        class="easyui-combobox"
						valueField='id'
						textField='text'
                        name="car_type_id"
                        style="width:180px;"
                        required="true"
                        editable="false"
                        data-options="panelHeight:'auto'"
                    >
                       
                </select>
			</td>
		</tr>
		<tr>
			<td><div style="width:85px;text-align:right;">需求台数</div></td>
			<td>
				<input
					class="easyui-textbox"
					style="width:160px;"
					name="number"
					required="true"
					validType="int"
				/>
			</td>
		</tr>
		<tr>
			<td><div style="width:85px;text-align:right;">调入前所属运营公司</div></td>
			<td colspan="5">
				<input class="easyui-combotree" name="pre_operating_company_id"
				   data-options="
						width:454,
						url: '<?php echo yii::$app->urlManager->createUrl(['operating/combotree/get-operating-company']); ?>',
						editable: false,
						panelHeight:'auto',
						lines:false,
						required:true
				   "
				/>
			</td>
		</tr>
		<tr>
			<td><div style="width:85px;text-align:right;">调入后所属运营公司</div></td>
			<td colspan="5">
				<input class="easyui-combotree" name="after_operating_company_id"
				   data-options="
						width:454,
						url: '<?php echo yii::$app->urlManager->createUrl(['operating/combotree/get-operating-company']); ?>',
						editable: false,
						panelHeight:'auto',
						lines:false,
						required:true
				   "
				/>
			</td>
		</tr>
		<tr>
			<td><div style="width:85px;text-align:right;">是否变更车辆所有人</div></td>
			<td colspan="5">
				<input class="easyui-combobox" name="is_owner_change"
				   data-options="
						valueField: 'value',
						textField: 'label',
						width:454,
						data: 
							[{
								label: '是',
								value: '1'
							},{
								label: '否',
								value: '2'
							}],
						editable: false,
						panelHeight:'auto',
						lines:false,
						required:true,
						onSelect:ProcessCarTransfer1ListAdd.loadAfterOwnerId
				   "
				/>
			</td>
		</tr>
		<tr>
			<td><div style="width:85px;text-align:right;">调入后车辆所有人</div></td>
			<td colspan="5">
				<input class="easyui-combotree" name="after_owner_id" id="process-car-transfer1-list-add-after-owner-id" />
			</td>
		</tr>
		<tr>
			<td><div style="width:85px;text-align:right;">其他要求备注</div></td>
			<td colspan="5">
				<input
					class="easyui-textbox"
					name="note"
					style="width:454px;height:40px;padding:0;" 
					data-options="multiline:true"
					validType="length[150]"
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
    var ProcessCarTransfer1ListAdd = new Object();
	ProcessCarTransfer1ListAdd.loadAfterOwnerId = function(rec){
		if(rec.value==1){
			$('#process-car-transfer1-list-add-after-owner-id').combotree({
				width:454,
				url: '<?php echo yii::$app->urlManager->createUrl(['owner/combotree/get-owners']); ?>',
				editable: false,
				panelHeight:'auto',
				lines:false,
				required:true,
				disabled:false
			});
		}else {
			$('#process-car-transfer1-list-add-after-owner-id').combotree(
				{ 
					required: false,
					disabled: true
				}
			);
		}
	}
	ProcessCarTransfer1ListAdd.CarBrandSelect = function(rec){
		var oc  = rec.id;
		//加载车型
		$('#process-car-transfer1-list-add-car-type').combobox({
			url: "<?php echo yii::$app->urlManager->createUrl(['operating/combobox/get-car-type']); ?>&brand_id="+oc,
			editable: false,
			panelHeight:'auto',
			panelWidth:300,
			lines:false
		});
	}
    //提交表单
    ProcessCarTransfer1ListAdd.submitForm = function(){
        var form = $('#easyui-form-process-car-transfer1-list-add');
        if(!form.form('validate')){
            return false;
        }
		form.ajaxSubmit({
			url: "<?php echo yii::$app->urlManager->createUrl(['process/car-transfer/list1-add']); ?>",
			dataType : "json",
			success : function(data){
				if(data.status){
                    $.messager.alert('新建成功',data.info,'info');
                    $('#easyui-dialog-process-car-transfer1-list-add').dialog('close');
                    $('#easyui-datagrid-process-car-transfer1-list').datagrid('reload');
                }else{
                    $.messager.alert('新建失败',data.info,'error');
                }
			},
			error: function(xhr){
				$.messager.alert('新建失败','500错','error');
			}
			
		});
    }
</script>