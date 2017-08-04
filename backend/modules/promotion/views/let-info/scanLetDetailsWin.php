<form id="promotionLetInfoIndex_addEditWin_form" method="post" style="padding:10px 0px;">
    <table cellpadding="8" cellspacing="3" align="center" style="width:100%;" border="0">
        <tr>
            <th align="right" width="15%">租车客户：</th>
            <td width="25%">
                <?php echo $letInfo['renter']; ?>
            </td>
            <th align="right" width="15%">手机号：</th>
            <td>
                <?php echo $letInfo['renter_mobile']; ?>
            </td>
        </tr>
        <tr>
            <th align="right">租车数量：</th>
            <td>
                <?php echo $letInfo['amount']; ?>
            </td>
            <th align="right">受理人员：</th>
            <td>
                <?php echo $letInfo['operator']; ?>
            </td>
        </tr>
        <tr>
            <th align="right">合同编号：</th>
            <td>
                <?php echo $letInfo['contract_no']; ?>
            </td>
            <th align="right">签订日期：</th>
            <td>
                <?php echo $letInfo['sign_date']; ?>
            </td>
        </tr>
        <tr>
            <th align="right">邀请码：</th>
            <td>
                <?php echo $letInfo['renter_invite_code']; ?>
            </td>
            <th align="right">注册日期：</th>
            <td>
                <?php echo $letInfo['renter_sign_date']; ?>
            </td>
        </tr>
        <tr>
            <th align="right" valign="top">备注：</th>
            <td colspan="5">
                <?php echo $letInfo['mark']; ?>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <div style="margin:0;padding:0; width:100%;height:1px;background-color:#eee;overflow:hidden;"></div>
            </td>
        </tr>
        <tr>
            <th align="right">邀请人：</th>
            <td>
                <?php echo $letInfo['inviter']; ?>
            </td>
            <th align="right">手机号：</th>
            <td>
                <?php echo $letInfo['inviter_mobile']; ?>
            </td>
        </tr>
        <tr>
            <th align="right">邀请码：</th>
            <td>
                <?php echo $letInfo['inviter_invite_code']; ?>
            </td>
            <th align="right"></th>
            <td></td>
        </tr>
    </table>
</form>
