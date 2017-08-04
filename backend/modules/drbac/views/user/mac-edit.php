<form id="easyui-form-drbac-user-index-mac-edit" class="easyui-form" method="post">
<div style="padding:10px 40px 20px 40px">
        <input type="hidden" name="id" />
        <table cellpadding="5" cellspacing="0" width="100%" border="0">
            <tr>
                <td>MAC地址：</td>
                <td>
                    <input class="easyui-textbox" name="mac"  />
                </td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>备注：</td>
                <td>
                    <input class="easyui-textbox" name="note" />
                </td>                
            </tr>
           
        </table>

</div>

</form>
  
<script>
    $('#easyui-form-drbac-user-index-mac-edit').form('load',<?php echo json_encode($macInfo); ?>)
</script>
