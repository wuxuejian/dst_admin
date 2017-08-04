<div style="padding:10px 40px 20px 40px">
    <form id="easyui-form-car-contract-record-car-edit" class="easyui-form" method="post">
        <input type="hidden" name="id" />
        <table cellpadding="8" cellspacing="0">
            <tr>
                <td><div style="width:70px;">月租金</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="month_rent"
                        validType="money"
                    />
                </td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td><div style="width:70px;">备注</div></td>
                <td colspan="3">
                    <input 
                        class="easyui-textbox"
                        name="note"
                        data-options="multiline:true"
                        style="height:38px;width:425px;"
                    />
                </td>
            </tr>
        </table>
    </form>
</div>
<script>
    var CarContractRecordCarEdit = new Object();
    CarContractRecordCarEdit.init = function(){
        $('#easyui-form-car-contract-record-car-edit').form('load',<?php echo json_encode($letInfo); ?>);
    }
    CarContractRecordCarEdit.init();
</script>