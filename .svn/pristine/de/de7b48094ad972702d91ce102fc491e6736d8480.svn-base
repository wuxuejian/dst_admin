<div style="padding:10px 40px 20px 40px">
    <form id="easyui-form-customer-company-sms-notify-edit" class="easyui-form" method="post">
        <input type="hidden" name="id" value="<?=$info['id']?>"/>
       <table cellpadding="5" cellspacing="0" width="100%" border="0"><tbody>
            <tr>
                <td>客户：</td>
                <td>
                    <input class="easyui-textbox" style="width:160px;" disabled="disabled" value="<?=$info['company_name']?>"/>
                </td>
            </tr>
            <tr>
                <td>给该客户发送短信通知：</td>
                <td>
                    <input type="radio" <?=$info['is_del']?'':'checked="checked"'?> name="is_del" value="0" />是
                    <input type="radio" <?=$info['is_del']?'checked="checked"':''?> name="is_del" value="1" />否
                </td>
            </tr>
            <tr>
                <td>客户需缴纳租金：</td>
                <td>
                    <input class="easyui-textbox" name="amount" style="width:100px;" value="<?=$info['amount']?>"/>元
                </td>
            </tr>
            <tr>
                <td>管理手机号：</td>
                <td>
                    <input class="easyui-textbox" name="keeper_mobile" style="width:100px;" value="<?=$info['keeper_mobile']?>"/>
                </td>
            </tr>
            <tr>
                <td>租车数量：</td>
                <td>
                    <input class="easyui-textbox" name="car_num" style="width:70px;" value="<?=$info['car_num']?>"/>
                </td>
            </tr>
            <tr>
                <td>交租截止时间：</td>
                <td>
                    	<input
                            class="easyui-datebox"
                            style="width:160px;"
                            name="delivery_time"
                            value="<?=$info['delivery_time']?>"
                            required="true"
                            missingMessage="请选择时间！"
                            validType="date"
                            >
                </td>
            </tr>
        </tbody></table>
    </form>
</div>