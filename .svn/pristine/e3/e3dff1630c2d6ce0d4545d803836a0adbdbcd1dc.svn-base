<div style="padding:15px"> 
    <form id="easyui-form-car-baseinfo-tci-edit">
        <input type="hidden" name="id" />
        <table cellpadding="8" cellspacing="0">
            <tr>
                <td><div style="width:85px;text-align:right;">保险公司</div></td>
                <td>
                    <select
                        class="easyui-combobox"
                        style="width:160px;"
                        name="insurer_company"
                        required="true"
                    >
                        <?php foreach($config['INSURANCE_COMPANY'] as $val){ ?>
                        <option value="<?php echo $val['value']; ?>"><?php echo $val['text']; ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td><div style="width:85px;text-align:right;">保险金额</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="money_amount"
                        required="true"
                        missingMessage="请输入保险金额！"
                        validType="money"
                    />
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">开始时间</div></td>
                <td>
                    <input
                        class="easyui-datebox"
                        style="width:160px;"
                        name="start_date"
                        required="true"
                        missingMessage="请选择开始日期！"
                        validType="date"
                    />
                </td>
                <td><div style="width:85px;text-align:right;">结束时间</div></td>
                <td>
                    <input
                        class="easyui-datebox"
                        style="width:160px;"
                        name="end_date"
                        required="true"
                        missingMessage="请选择结束日期！"
                        validType="date"
                    />
                </td>
            </tr>
        </table>
    </form>
</div>
<script>
    var oldData = <?php echo json_encode($tciInfo); ?>;
    oldData.start_date = parseInt(oldData.start_date) > 0 ? formatDateToString(oldData.start_date) : '';
    oldData.end_date = parseInt(oldData.end_date) > 0 ? formatDateToString(oldData.end_date) : '';
    $('#easyui-form-car-baseinfo-tci-edit').form('load',oldData);
</script>