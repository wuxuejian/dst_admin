<div style="padding:10px 40px 20px 40px">
    <form id="easyui-form-app-long-callback" class="easyui-form">
        <input type="hidden" name="id" value="<?php echo $id;?>"/>
        <table cellpadding="8" cellspacing="0">
           <tr>
                <td><div style="width:85px;text-align:right;">业务主管</div></td>
                <td>				 
                  <input
                        class="easyui-textbox"
                        name="call_back_man_note"
                        style="width:254px;height:70px;padding:0;" 
                        data-options="multiline:true"
                        validType="length[150]"
                    />
                </td>
            </tr>           
            <tr>
                <td  align="right"><div style="width:70px;">销售专员</div></td>
                <td>
                     <input
                        class="easyui-textbox"
                        name="call_back_sale_note"
                        style="width:254px;height:70px;padding:0;" 
                        data-options="multiline:true"
                        validType="length[150]"
                    />
                </td>
            </tr>
        </table>
    </form>
</div>
<script>
    // $('#easyui-form-app-long-edit').form('load',<?php echo json_encode($customerInfo); ?>)
</script>