<div style="padding:10px 40px 20px 40px">
    <form id="easyui-form-process-car-money-from" class="easyui-form" method="post">
    <input type="hidden" name="id" />
     <input type="hidden" name="step_id" />
     <input type="hidden" name="template_id" />
        <table cellpadding="5">  
            <tr>
            	<td> 实收保证金：</td>
               <td> <input class="easyui-textbox" name="real_margin" required="true" missingMessage="请输入实收保证金"/></td>
            </tr>
            <tr>
                <td> 实收租金：</td>
                <td>
                    <input class="easyui-textbox" name="real_rent" required="true" missingMessage="请输入实收租金"/>
                </td>
            </tr>
            
        </table>
    </form>
</div>
<script>
$('#easyui-form-process-car-money-from').form('load',<?= json_encode($result); ?>);
</script>