<div style="padding:10px 40px 20px 40px">
    <form id="easyui-form-drbac-department-add" class="easyui-form" method="post">
        <table cellpadding="5">
        	<tr>
                <td>运营公司：</td>
                <td>
                    <select id="add_oc"  class="easyui-combobox" name="operating_company_id"  required="true" data-options="editable:false"  style="width:100%" missingMessage="请选择运营公司名称" >
                          <option value=''></option>
                          <?php foreach ($oc as $v):?>
                          	<option value='<?php echo $v['id']?>'><?php echo $v['name']?></option>
                          <?php endforeach;?>
                     </select>
                </td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>上级部门：</td>
                <td>
                    <input 
                    	class="easyui-textbox"
                        name="pid"
                        required="true"
                        id="add_pid" 
                        data-options="editable:false" 
                    />
                </td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>部门名称：</td>
                <td>
                    <input 
                        class="easyui-textbox"
                        name="name"
                        required="true"
                        missingMessage="请输入部门名称" 
                    />
                </td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>备注：</td>
                <td colspan="3">
                    <input 
                        class="easyui-textbox"
                        name="note"
                        data-options="multiline:true"
                        style="height:60px;width:376px;"
                    />
                </td>
            </tr>
        </table>
    </form>
</div>
<script>
$("#add_oc").combobox({
	onSelect: function(rec){
    	//var oc  = $("#oc").combobox('getValue');
    	var oc  = rec.value;
        var curMenuId = 0;
        $('#add_pid').combotree({
            url: "<?php echo yii::$app->urlManager->createUrl(['drbac/department/get-categorys']); ?>&isShowRoot=1&oc="+oc,
            editable: false,
            panelHeight:'auto',
            panelWidth:300,
            lines:false,
            onLoadSuccess: function(data){ //展开到当前菜单位置
                if(parseInt(curMenuId)){
                    var combTree = $('#add_pid');
                    combTree.combotree('setValue',curMenuId);
                    var t = combTree.combotree('tree');
                    var curNode = t.tree('getSelected');
                    t.tree('collapseAll').tree('expandTo',curNode.target);
                }
            }
        });
    }
});

</script>