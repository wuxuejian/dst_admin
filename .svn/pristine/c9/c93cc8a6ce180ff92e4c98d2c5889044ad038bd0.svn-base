<div style="padding:10px 40px 20px 40px">
    <form id="easyui-form-drbac-role-access-memeber-manage" class="easyui-form" method="post">
        <input type="hidden" name="roleId" value="<?php echo $roleId; ?>" />
        <table cellpadding="3" cellspacing="0" style="width:100%;line-height:22px;">
            <tr style="font-weight: bold;background: #E0ECFF;">
                <th style="border: 1px solid #95B8E7;"></th>
                <th style="border: 1px solid #95B8E7;border-left:none;">账号</th>
                <th style="border: 1px solid #95B8E7;border-left:none;">姓名</th>
                <th style="border: 1px solid #95B8E7;border-left:none;">性别</th>
            </tr>
            <?php foreach($admin as $key=>$val){ ?>
            <tr>
                <td style="border-left:1px solid #ccc;border-right:1px solid #ccc;border-bottom:1px solid #ccc;"><input type="checkbox" name="admin_id[]" value="<?php echo $val['id']; ?>" /></td>
                <td style="border-right:1px solid #ccc;border-bottom:1px solid #ccc;"><?php echo $val['username']; ?></td>
                <td style="border-right:1px solid #ccc;border-bottom:1px solid #ccc;"><?php echo $val['name']; ?></td>
                <td style="border-right:1px solid #ccc;border-bottom:1px solid #ccc;"><?php echo $val['sex'] == 0 ? '女' : '男' ; ?></td>
            </tr>
            <?php } ?>
        </table>
    </form>
</div>
<script>
    $('#easyui-form-drbac-role-access-memeber-manage').find('input').each(function(){
        var roleAdmin = <?php echo json_encode($roleAdmin); ?>;
        if(roleAdmin){
            var str = 'roleAdmin['+$(this).val()+']';
            try{
                if(eval(str)){
                    $(this).attr('checked',true);
                }
            }catch(e){}
        }
    });
</script>