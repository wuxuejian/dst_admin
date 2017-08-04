<?php
/**
 * Created by PhpStorm.
 * User: lingcheng
 * Date: 2015/6/5
 * Time: 17:11
 */
$roleId = Yii::$app->request->get('role_id');

?>
<div id="win_privilege_member">
    <table id="grid-member-list" title="" class="easyui-datagrid" style="width:100%"
           data-options="
               rownumbers:true,
               singleSelect:true,
               striped:true,
               pagination:true,
               pageSize: 20,
               pageList: [20,50,100],
               url:'<?=\yii\helpers\Url::to(['/company/privilege/role-member', 'role_id' => $roleId]);?>',
               toolbar:'#tb_member'">
        <thead>
        <tr>
            <th data-options="field:'itemid',sortable:true,checkbox:true" width="20%">用户名</th>
            <th field="worker_name" width="20%">用户名</th>
            <th field="user_name"  width="50%">用户名称</th>
        </tr>
        </thead>
    </table>
    <div id="tb_member">
        <a href="#" class="easyui-linkbutton" iconCls="icon-add" onclick="Pri.addMemeber(<?=$roleId?>)">添加</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-delete" onclick="Pri.delMemeber(<?=$roleId?>)">删除</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-refresh" onclick="$('#grid-member-list').datagrid('reload');">刷新</a>
    </div>

</div>





