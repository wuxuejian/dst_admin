<div style="padding:15px">
    <form id="easyui-form-car-test-add" method="post">
        <table cellpadding="8" cellspacing="0">
            <tr>
                <td><div style="width:85px;text-align:right;">登记车辆</div></td>
                <td><input name="car_id" style="width: 160px;" /></td>
                <td><div style="width:85px;text-align:right;">测试里程</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="mileage"
                        required="true"
                        missingMessage="请输入测试里程数！"
                        validType="match[/^\d+(\.\d{1})?$/]"
                        invalidMessage="测试里程数格式（88.8）错误！"
                    /> km
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">测试小时数</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="use_hour"
                        required="true"
                        missingMessage="请输入测试小时数！"
                        validType="int"
                        invalidMessage="测试小时数只能是整型值可以为0！"
                    /> 小时
                </td>
                <td><div style="width:85px;text-align:right;">测试分钟数</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="use_minute"
                        required="true"
                        missingMessage="请输入测试分钟数！"
                        validType="match[/^[012345]\d?$/]"
                        invalidMessage="测试分钟数只能是小于60的整型值可以为0！"
                    /> 分钟
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">慢充充电状况</div></td>
                <td colspan="3">
                    <input
                        class="easyui-textbox"
                        multiline="true"
                        style="width:465px;height:60px;"
                        name="slow_recharge_status"
                        validType="length[255]"
                    />
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">快充充电状况</div></td>
                <td colspan="3">
                    <input
                        class="easyui-textbox"
                        multiline="true"
                        style="width:465px;height:60px;"
                        name="fast_recharge_status"
                        validType="length[255]"
                    />
                </td>
            </tr>
        </table>
    </form>
</div>
<script>
    var CarTestAdd = {
        init: function(){
            var easyuiForm = $('#easyui-form-car-test-add');
            easyuiForm.find('input[name=car_id]').combogrid({
                panelWidth: 240,
                idField: 'id',
                textField: 'vehicle_dentification_number',
                url: "<?= yii::$app->urlManager->createUrl(['car/combogrid/car-list']); ?>",
                method: 'get',
                mode: 'remote',
                rownumbers: true,
                columns: [[
                    {field:'id',title:'id',hidden: true},
                    {field:'plate_number',title:'车牌号',width:70,align: 'center',sortable: true},
                    {field:'vehicle_dentification_number',title:'车架号',width:120,align: 'center',sortable: true}
                ]]
            });
        }
    }
    CarTestAdd.init();
</script>