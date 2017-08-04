<form id="carFaultDisposeWin_addEditWin_form" method="post">
    <table cellpadding="6" cellspacing="0"  border="0" width="100%" style="margin-top:5px;">
        <tr hidden>
            <td align="right">进度ID</td>
            <td>
                <input class="easyui-textbox" name="id" style="width:160px;" value="0" editable="false"  />
            </td>
            <td align="right">所属故障ID</td>
            <td colspan="3">
                <input class="easyui-textbox" name="fault_id" style="width:160px;" value="<?php echo $faultId; ?>" editable="false"  />
            </td>
        </tr>
        <tr>
            <td align="right" width="10%">受理人</td>
            <td width="23%">
                <input class="easyui-textbox" name="disposer" style="width:160px;" required="true"  />
            </td>
            <td align="right">受理日期</td>
            <td>
                <input class="easyui-datebox" style="width:160px;" name="dispose_date" required="true" validType="date" />
            </td>
            <td align="right"  width="10%">处理结果</td>
            <td width="23%">
                <select class="easyui-combobox" name="fault_status" style="width:160px;" data-options="panelHeight:'auto',required:true"  editable=false >
                    <?php foreach($config['fault_status'] as $val){ ?>
                        <option value="<?php echo $val['value'] ?>"><?php echo $val['text']; ?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>
        <tr>
            <td align="right" valign="top">进度描述</td>
            <td colspan="5">
                <input class="easyui-textbox" name="progress_desc" style="width:680px;height:60px;"
                       data-options="multiline:true"
                       prompt="如果故障已经完结，请填写引发故障的真实原因，以及最终的处理方法。"
                       validType="length[500]"
                    />
            </td>
        </tr>
    </table>
</form>

<script>
    // 初始数据
    var carFaultDisposeWin_addEditWin_initData = <?php echo json_encode($initData); ?>;
    // 修改时加载出旧数据
	if(carFaultDisposeWin_addEditWin_initData.action == 'edit'){
        $('#carFaultDisposeWin_addEditWin_form').form('load',carFaultDisposeWin_addEditWin_initData.progressInfo);
    }

</script>