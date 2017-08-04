<div style="padding:10px 40px 20px 40px">
    <form id="easyui-form-car-let-back" class="easyui-form">
        <input type="hidden" name="id" />
        <table cellpadding="8" cellspacing="0">
            <tr>
                <td><div style="width:70px;">出租车辆</div></td>
                <td>
                    <input 
                        class="easyui-textbox"
                        style="width:160px;"
                        name="plate_number"
                        disabled="true" 
                    />
                </td>
                <td><div style="width:70px;">承租客户号</div></td>
                <td>
                    <input 
                        class="easyui-textbox"
                        style="width:160px;"
                        name="customer_number"
                        disabled="true" 
                    />
                </td>
            </tr>
            <tr>
                <td><div style="width:70px;">提车时间</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="take_time"
                        disabled="true"
                    >
                </td>
                <td><div style="width:70px;">还车时间</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="back_time"
                        disabled="true"
                    >
                </td>
            </tr>
            <tr>
                <td><div style="width:70px;">实收租金</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="true_rent"
                        required="true"
                        missingMessage="请填写实收租金"
                        validType="money"
                    >
                </td>
                <td><div style="width:70px;">罚金</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="fine"
                        validType="money"
                    >
                </td>
            </tr>
            <tr>
                <td><div style="width:70px;">备注</div></td>
                <td colspan="3">
                    <input 
                        class="easyui-textbox"
                        name="note"
                        data-options="multiline:true"
                        style="height:60px;width:425px;"
                    />
                </td>
            </tr>
        </table>
    </form>
</div>
<script>
    var CarLetBack = new Object();
    CarLetBack.init = function(){
        var data = <?= json_encode($letInfo); ?>;
        data.take_time = formatDateToString(data.take_time);
        data.back_time = formatDateToString(data.back_time);
        $('#easyui-form-car-let-back').form('load',data);
    }
    CarLetBack.init();
</script>