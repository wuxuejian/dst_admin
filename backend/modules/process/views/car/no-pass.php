<div style="padding:10px 40px 20px 40px">
    <form id="easyui-form-process-car-no-pass" class="easyui-form" method="post">
    <input type="hidden" name="id" />
    <input type="hidden" name="step_id"/>
     <input type="hidden" name="template_id"/>
        <table cellpadding="5">
        	<tr>
                <td colspan="2" style="color:red"><?php if(!empty($notice)) echo $notice;  ?></td>
            </tr>
            <tr>
                <td> 补充说明：</td>
                <td>
                	 <input class="easyui-textbox" style="width:450px;height:80px;"   name="remark"   data-options="multiline:true" prompt="200字符以内。如果没有补充，请留空。"
                        validType="length[200]"/>
                </td>
            </tr>
        </table>
    </form>
</div>
<script>
$('#easyui-form-process-car-no-pass').form('load',<?= json_encode($result); ?>);
</script>