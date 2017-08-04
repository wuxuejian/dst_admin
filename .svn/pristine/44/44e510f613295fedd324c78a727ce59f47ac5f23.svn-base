<div style="padding:10px 40px 20px 40px">
    <form id="easyui-form-drbac-user-role-distribution" class="easyui-form" method="post">
        <input type="hidden" name="adminId" value="<?php echo $adminId; ?>" />
        <table cellpadding="3" cellspacing="0" style="width:100%;line-height:22px;">
            <tr style="font-weight: bold;background: #E0ECFF;">
                <th style="border: 1px solid #95B8E7;"></th>
                <th style="border: 1px solid #95B8E7;border-left:none;">角色名称</th>
                <th style="border: 1px solid #95B8E7;border-left:none;">备注</th>
            </tr>
            <?php foreach($roles as $key=>$val){ ?>
            <tr>
                <td style="border-left:1px solid #ccc;border-right:1px solid #ccc;border-bottom:1px solid #ccc;"><input type="checkbox" name="role_id[]" value="<?php echo $val['id']; ?>" /></td>
                <td style="border-right:1px solid #ccc;border-bottom:1px solid #ccc;"><?php echo $val['name']; ?></td>
                <td style="border-right:1px solid #ccc;border-bottom:1px solid #ccc;"><?php echo $val['note']; ?></td>
            </tr>
            <?php } ?>
        </table>
    </form>
</div>
<script>
    $('#easyui-form-drbac-user-role-distribution').find('input').each(function(){
        var userRole = <?php echo json_encode($userRoles); ?>;
        if(userRole){
            var str = 'userRole['+$(this).val()+']';
            try{
                if(eval(str)){
                    $(this).attr('checked',true);
                }
            }catch(e){}
        }
    });
</script>