<form id="easyui-form-role-access-access-manage"> 
    <input type="hidden" name="roleId" value="<?= $roleId; ?>">
    <div class="easyui-tabs" border="false">   
        <?php foreach($modules as $val){ ?>
        <div title="<?php echo $val['name']; ?>">   
            <ul style="overflow:hidden;width:90%;margin:0 auto;padding:0;list-style:none;">
                <?php foreach($val['children'] as $v){ ?>
                <li style="width:33%;float:left;padding:5px 0;">
                    <label style="cursor:pointer;">
                        <input style="vertical-align:middle;" type="checkbox" name="actionIds[]" value="<?php echo $v['id']; ?>" <?php echo in_array($v['id'],$accessActionIds) ? 'checked="true"': '' ; ?> /><?php echo $v['name']?>
                    </label>
                </li>
                <?php } ?>
            </ul>   
        </div>
        <?php } ?>
    </div>
</form>