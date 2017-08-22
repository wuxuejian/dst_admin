<form id="easyui-form-drbac-user-edit-user" class="easyui-form" method="post">
<div style="padding:10px 40px 20px 40px">
        <input type="hidden" name="id" />
        <table cellpadding="5" cellspacing="0" width="100%" border="0">
            <tr>
                <td>用户名：</td>
                <td>
                    <input class="easyui-textbox" name="username" disabled="true" />
                </td>
                <td>账号类型：</td>
                <td>
                    <select class="easyui-combobox" name="repair_company" style="width:173px;" editable="false" panelHeight="auto">
                        <option value="1">外部账号</option>  
                        <option value="0">内部账号</option>  
                    </select> 
                </td>
            </tr>
            <tr>
                <td>姓名：</td>
                <td>
                    <input class="easyui-textbox" name="name" />
                </td>
                <td>性别：</td>
                <td>
                    <select class="easyui-combobox" name="sex" style="width:173px;" editable="false" panelHeight="auto">
                        <option value="1">男</option>  
                        <option value="0">女</option>  
                    </select> 
                </td>
            </tr>
            <tr>
                <td>所属运营公司：</td>
                <td>
                    <input class="easyui-combotree" name="operating_company_id" id="edit_user_oc"
                           data-options="
                                url: '<?php echo yii::$app->urlManager->createUrl(['operating/combotree/get-operating-company']); ?>',
                                editable: false,
                                panelHeight:'auto',
                                panelWidth:'auto',
                                lines:false,
                                required:true,
                                missingMessage:'请选择运营公司'
                           "
                        />
                </td>
                <td>所属部门：</td>
                <td>
                    <!--  <select class="easyui-combobox" name="department_id" style="width:173px;" editable="false" panelHeight="auto">
                        <?php //foreach($department as $val) { ?>
                            <option value="<?php //echo $val['id']; ?>"><?php //echo $val['name']; ?></option>
                        <?php //} ?>
                    </select>-->
                    
                    <input  id="edit_user_pid"  class="easyui-textbox" name="department_id"   data-options="editable:false"  required="true" missingMessage="请先选择所属运营公司" />
                </td>
            </tr>
            <tr>
                <td>手机：</td>
                <td>
                    <input
                        class="easyui-textbox"
                        name="telephone"
                        validType="mobile"
                        />
                </td>
                <td>邮箱地址：</td>
                <td>
                    <input
                        class="easyui-textbox"
                        name="email"
                        validType="email"
                    />
                </td>
            </tr>
            <tr>
                <td>QQ：</td>
                <td>
                    <input
                        class="easyui-textbox"
                        name="qq"
                        validType="match[/^\d+$/]"
                    />
                </td>
                <td>免MAC登录：</td>
                <td>
				  <select class="easyui-combobox" name="mac_pass" style="width:173px;" editable="false" panelHeight="auto">
                        <option value="1">是</option>  
                        <option value="0">否</option>  
                    </select> 			
					</td>
            </tr>
        </table>

</div>

<div
    class="easyui-panel" title="数据权限配置"    
    style="padding:10px;"
    data-options="closable:false,collapsible:false,minimizable:false,maximizable:false,border:false,fit:true">
    	<table cellpadding="8" cellspacing="0">
            <tr>
                <td colspan="4"><div>注：对新用户指定其所属大区及分公司，决定了该用户可以查看的系统数据的范围</div></td>
            </tr>
			<tr>
                <td colspan="4"><div>所属大区（分公司）：</div></td>
            </tr>
			<?php
				$operating_company_ids = explode(',',$adminInfo['operating_company_ids']);
				foreach ($operatingCompany as $row){
			?>
				<tr>
	                <td colspan="4"><input type="checkbox" name="operating_company_ids[]" value="<?=$row['id']?>" <?=in_array($row['id'],$operating_company_ids)?"checked":""?>><?=$row['name']?></td>
	            </tr>
			<?php 
				}
			?>
        </table>
</div>
</form>
  
<script>
    $('#easyui-form-drbac-user-edit-user').form('load',<?php echo json_encode($adminInfo); ?>)
</script>

<script>
$(function(){
	pid_tree(<?php echo  !empty($adminInfo['operating_company_id']) ? $adminInfo['operating_company_id'] :0; ?>,<?php echo  !empty($adminInfo['department_id']) ? $adminInfo['department_id'] :0; ?>);
})

$("#edit_user_oc").combotree({
	onSelect: function(rec){
		pid_tree(rec.id,0);
	}
});

function pid_tree(oc,curMenuId){
    $('#edit_user_pid').combotree({
        url: "<?php echo yii::$app->urlManager->createUrl(['drbac/department/get-categorys']); ?>&isShowRoot=1&mark=1&oc="+oc,
        editable: false,
        panelHeight:'auto',
        panelWidth:300,
        lines:false,
        onLoadSuccess: function(data){ //展开到当前菜单位置
            if(parseInt(curMenuId)){
                var combTree = $('#edit_user_pid');
                combTree.combotree('setValue',curMenuId);
                var t = combTree.combotree('tree');
                var curNode = t.tree('getSelected');
                t.tree('collapseAll').tree('expandTo',curNode.target);
            }
        }
    });
}

</script>