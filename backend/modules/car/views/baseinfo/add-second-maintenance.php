<div style="padding:15px"> 
    <form id="easyui-form-car-baseinfo-add-second-maintenance">
        <input type="hidden" name="car_id" value="<?php echo $carId; ?>" />
        <table cellpadding="8" cellspacing="0">
            <tr>
                <td><div style="width:85px;text-align:right;">维护卡编号</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="number"
                        required="true"
                        missingMessage="请输入二级维护记录卡编号！"
                        validType="number[50]"
                    />
                </td>
                <td><div style="width:85px;text-align:right;">本次维护日期</div></td>
                <td>
                    <input
                        class="easyui-datebox"
                        style="width:160px;"
                        name="current_date"
                        required="true"
                        missingMessage="请选择本次维护日期！"
                        validType="date"
                    />
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">下次维护日期</div></td>
                <td>
                    <input
                        class="easyui-datebox"
                        style="width:160px;"
                        name="next_date"
                        required="true"
                        missingMessage="请选择下次维护日期！"
                        validType="date"
                    />
                </td>
                <td><div style="width:85px;text-align:right;"></div></td>
                <td></td>
            </tr>
        </table>
    </form>
</div>