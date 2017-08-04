<script src="js/jquery.ajaxSubmit.js"></script>
<div style="padding:15px"> 
    <form action="<?php echo yii::$app->urlManager->createUrl(['car/maintain-record/maintain-add']); ?>" id="easyui-form-car-maintain-record-type-add" class="easyui-form" method="post" enctype="multipart/form-data">
        
        <table cellpadding="8" cellspacing="0">
            
			<tr>
			<td align="right">车型名称</td>
			<td>
				<select class="easyui-combobox" name="car_model_name" style="width:160px;"
						data-options="panelHeight:'auto',editable:false" required="true"
					>
					<option value="" selected="selected">--请选择--</option>
					<?php foreach($config['car_model_name'] as $val){ ?>
						<option value="<?= $val['value'] ?>"><?= $val['text'] ?></option>
					<?php } ?>
				</select>
			</td>
			</tr>
            <tr>
                <td><div style="width:85px;text-align:right;">保养类型</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="maintain_type"
                        validType="length[100]"
                    />
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">类型描述</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:360px;"
                        name="maintain_des"
                        validType="length[300]"
                    />
                </td>
            </tr>            
        </table>
    </form>
</div>