<div style="padding:5px 3px;">
    <div class="easyui-panel" title="" style="padding:5px;" data-options="collapsible:true,collapsed:false,border:false">
        <form id="trialRegisterWin_form" method="post">
        	<table cellpadding="5" cellspacing="0" style="width:100%;" border=0>
				<tr>
					<td align="right">试用车辆：</td>
					<td>
						<input class="easyui-textbox" style="width:140px;" disabled="true" value="<?= $vehicle; ?>"></input>
					</td>
				</tr>
				<tr hidden>
        			<td align="right">试用车辆ID：</td>
        			<td>
        			    <input class="easyui-textbox" name="trial_car_id" style="width:140px;" editable="false" value="<?= $vhcid; ?>"></input> 
					</td>
				</tr>
				<tr>
					<td align="right">试用客户：</td>
					<td>
						<select class="easyui-combogrid" style="width:250px" name="trial_customer_id"
							data-options="
									panelWidth: 500,
									panelHeight: 'auto',
									required: true,
									missingMessage: '请从下拉列表里选择客户！',
									onHidePanel:function(){
										var _combogrid = $(this);
										var value = _combogrid.combogrid('getValue');
										var textbox = _combogrid.combogrid('textbox');
										var text = textbox.val();
										var rows = _combogrid.combogrid('grid').datagrid('getSelections');
										if(text && rows.length < 1 && value == text){										
											$.messager.show(
												{
													title: '无效客户',
													msg:'【' + text + '】不是有效客户！请重新选择！',
												}
											);
											_combogrid.combogrid('setValue','');
										}
									},
									delay: 500,
									mode: 'remote',
									idField: 'trial_customer_id',
									textField: 'trial_customer_name',
									url: '<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/choose-trial-customer']); ?>',
									method: 'get',
									scrollbarSize:0, 
									pagination: true,
									pageSize: 10,
									pageList: [10,15,20,25],
									fitColumns: true,
									columns: [[
										{field:'trial_customer_id',title:'客户ID',width:40,hidden:true},
										{field:'trial_customer_name',title:'客户名称',width:100},
										{field:'trial_customer_address',title:'客户地址',width:150}
									]]
								"
						></select>
					</td>
				</tr>
				<tr>				
        			<td align="right">试用日期：</td>
        			<td>
        			    <input class="easyui-datebox" name="trial_date_start"  style="width:140px;"  required="true"  validType="date"></input> -   
						<input class="easyui-datebox" name="trial_date_end"  style="width:140px;"  required="true" validType="date"></input>  					
					</td>
				</tr>				
				<tr>
					<td align="right" valign="top">试用备注：</td>
					<td>
						<input class="easyui-textbox" name="trial_note" style="width:300px;height:60px;" 
						data-options="multiline:true"
                        validType="length[150]"></input> 
					</td>
				</tr>
			</table>
        </form>
    </div> 
</div>