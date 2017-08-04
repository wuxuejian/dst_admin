<div style="padding:10px 40px 20px 40px">
    <form id="easyui-form-process-car-archive-form" class="easyui-form" method="post">
    <input type="hidden" name="id" />
    <input type="hidden" name="step_id"/>
     <input type="hidden" name="template_id"/>
        <table cellpadding="5">
        	<tr>
                <td> 提示：</td>
                <td> 请确认本次提车申请的所有车辆已经交付完成，并已成功同步到对应的合同中。
                </td>
            </tr>
            <tr>
                <td> 补充说明：</td>
                <td>
                	 <input class="easyui-textbox" style="width:450px;height:80px;"   name="archive_remark"   data-options="multiline:true" prompt="200字符以内。"
                        validType="length[200]"/>
                </td>
            </tr>
        </table>
    </form>
</div>
<script>
$('#easyui-form-process-car-archive-form').form('load',<?= json_encode($result); ?>);
</script>
