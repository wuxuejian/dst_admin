<div style="padding:10px 40px 20px 40px">
    <form id="easyui-form-app-long-edit" class="easyui-form">
        <input type="hidden" name="id" value="<?php echo $id;?>"/>
        <table cellpadding="8" cellspacing="0">
           <tr>
                <td><div style="width:85px;text-align:right;">销售专员</div></td>
                <td>
                    <select
                        class="easyui-combobox"
                        style="width:160px;"
                        name="sale_id"
						editable="true"
						
                    > 
					<option value="">请选择</option>
                        <?php foreach($sales as $val){ ?>
						
                        <option value="<?php echo $val['id']; ?>"><?php echo $val['name']; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr> 
          
            <tr>
                <td  align="right"><div style="width:70px;">联系电话</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="sales_mobile"
                        
                    >
                </td>
            </tr>
        </table>
    </form>
</div>
<script>
    // $('#easyui-form-app-long-edit').form('load',<?php echo json_encode($customerInfo); ?>)
</script>