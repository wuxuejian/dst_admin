<div style="padding:10px 40px 20px 40px">
    <form id="easyui-form-car-let-edit" class="easyui-form" method="post">
        <input type="hidden" name="id" />
        <table cellpadding="8" cellspacing="0">
            <tr>
                <td><div style="width:70px;">出租车辆</div></td>
                <td>
                    <select 
                        class="easyui-combobox"
                        style="width:160px;"
                        name="car_id"
                        required="true"
                        missingMessage="请选择出租车辆"
                    >
                        <?php foreach($car as $val){ ?>
                        <option value="<?= $val['id']; ?>"><?= $val['plate_number']; ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td><div style="width:70px;">承租客户号</div></td>
                <td>
                    <select 
                        class="easyui-combobox"
                        style="width:160px;"
                        name="customer_id"
                        required="true"
                        missingMessage="请输入承租客户号"
                    >
                        <?php foreach($customer as $val){ ?>
                        <option value="<?= $val['id']; ?>"><?= $val['number']; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><div style="width:70px;">提车时间</div></td>
                <td>
                    <input
                        class="easyui-datetimebox"
                        style="width:160px;"
                        name="take_time"
                        required="true"
                        missingMessage="请选择提车时间"
                    >
                </td>
                <td><div style="width:70px;">还车时间</div></td>
                <td>
                    <input
                        class="easyui-datetimebox"
                        style="width:160px;"
                        name="back_time"
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
    var CarLetEdit = new Object();
    CarLetEdit.init = function(){
        var data = <?= json_encode($let); ?>;
        data.take_time = formatDateToString(data.take_time);
        data.back_time = formatDateToString(data.back_time);
        $('#easyui-form-car-let-edit').form('load',data);
    }
    CarLetEdit.init();
</script>