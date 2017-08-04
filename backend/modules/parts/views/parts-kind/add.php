<div class="easyui-panel" title="配件类别" style="padding:8px 4px;" data-options="
        iconCls: 'icon-tip',
        border: false
    ">
        <form id="search-form-parts-kind-add" method="post">
            <table cellpadding="8" cellspacing="0">
                <tr>
                    <td><div style="width:85px;text-align:right;">配件类别：</div></td>
                    <td>
                        <input
                                class="easyui-textbox"
                                style="width:160px;"
                                name="type_name"
                                required="true"
                                validType="length[100]"
                        >
                    </td>
                    <td><div style="width:85px;text-align:right;">备注：</div></td>
                    <td>
                        <input
                                class="easyui-textbox"
                                style="width:160px;"
                                name="note"
                                validType="length[150]"
                        >
                    </td>
                </tr>
            </table>
        </form>
</div>