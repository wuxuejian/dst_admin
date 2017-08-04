<?php
/**
 * Created by PhpStorm.
 * User: lingcheng
 * Date: 2015/5/18
 * Time: 16:08
 */
?>
<div id="layout_pri" class="easyui-layout" style="width:100%;height:100%;">


    <div class="easyui-panel" title="查询区域" data-options="region:'north',collapsible:true,collapsed:false,border:false"
         style="height:100px;width:100%">
        <form id="form_role_search" class="easyui-form" method="post" data-options="novalidate:true"
              action="<?= \yii\helpers\Url::to(['company/privilege/list']) ?>">
            <div class="search_class">角色名称:<input type="text" name="name" class="easyui-textbox"></div>
                <div class="search_class"><div style="text-align:right;margin-right:5px;">
                <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-search'" onclick="Pri.doSearch()">搜索</a>
                <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-cancel'" onclick="$('#form_role_search').form('clear')">重置</a>
            </div>
            </div>
        </form>
        <input type="hidden" name="moduleid" id="moduleid" value="">
    </div>


    <div id="pri_panel_list" data-options="region:'center', href:'',border:false"  style="border-top:1px solid #D3D3D3">
        <table id="grid-role-list" title="角色权限设置" class="easyui-datagrid" style="border-top:1px solid #D3D3D3"
               data-options="
               rownumbers:true,
               singleSelect:true,
               striped:true,
               border:false,
               fit:true,
               onDblClickRow:Pri.dedit,
               pagination:true,
               pageSize: 20,
               pageList: [20,50,100],
               url:'<?=\Yii::$app->urlManager->createUrl('company/privilege/list')?>',
               toolbar:'#pri'">
            <thead>
                <tr>
                    <th field="id" checkbox="true"></th>
                    <th data-options="field:'code',sortable:true" align="center"  width="5%">编码</th>
                    <th data-options="field:'name',sortable:true" align="center"   width="10%">角色</th>
                    <th field="memo"  width="15%">备注</th>
                    <th field="users"  width="70%">成员</th>
                    <th field="edit"  hidden="true" ></th>

                </tr>
            </thead>
        </table>
        <div id="pri" style="padding:1px;height:auto">
            <div style="margin-bottom:5px">
                <? foreach ($datas['buttons'] as $key=> $b){ ?>
                    <a href="#" class="easyui-linkbutton" id="<?=$key?>" iconCls="<?=$b['class']?>" onClick="<?=$b['click']?>"><?=$b['text']?> </a>
                <? }?>
            </div>
        </div>

    </div>
    <!--编辑窗口-->
    <div id="win_busrole_edit" class="easyui-dialog" title="编辑代码信息" style="width:600px;height:200px"
         data-options="iconCls:'icon-edit',modal:true,closed:true,closable:true,inline:true,
        buttons: [{
					text:'确定',
					iconCls:'icon-ok',
					handler: Pri.submitBusForm
				},{
					text:'取消',
					iconCls:'icon-cancel',
					handler: Pri.closeBusEdit
				}]
		">
        <form id="form_busrole_edit" name="form1" method="post" action="">
            <input name='id' type='hidden' />
            <table width="100%" border="0" cellpadding="1" cellspacing="1">
                <tr>
                    <td colspan="4" align="left" class="group_label">&nbsp;&nbsp;</td></td>
                </tr>
                <tr>
                    <td width="201" align="right">编码</td>
                    <td width="201"><input name='code' type='text' size='15' class="easyui-textbox" data-options="required:true" validType="strCode"/></td>
                    <td width="122" align="right">名称</td>
                    <td width="254"><input name='name' type='text'  size='15' class="easyui-textbox" data-options="required:true"/></td>
                </tr>
                <tr>
                    <td align="right">备注</td>
                    <td colspan="3"><input name='memo' type='text'size='50' class="easyui-textbox" data-options="multiline:true" style="width:400px;height:50px"/></td>
                </tr>
            </table>
        </form>
    </div>
    <!--编辑窗口结束-->

    <!-- 以下为设置权限窗口  -->
    <div id="win_privilege_setpri"> </div>

    <!-- 以下为查看成员窗口  -->
    <div id="win_privilege_member" > </div>
    <!-- 以下为设置角色成员窗口  -->
    <div id="win_role_member" class="easyui-dialog" title="所有用户" style="width:600px;height:500px"
         data-options="iconCls:'icon-user',modal:true,closed:true,closable:true,inline:true,
         buttons:[{
				text:'保存',
				iconCls:'icon-ok',
				handler:Pri.saveMember
			},{
				text:'关闭',
				iconCls:'icon-cancel',
				handler:Pri.cancelMember
			}]
		">
        <form action="" id="members" name="members" method="post">
            <input type="hidden" name="rid" id="rid" value="<?=$id?>">
            <table id="grid-members-list" title="" class="easyui-datagrid" style="width:100%"
                   data-options="
                   rownumbers:true,
                   striped:true,
                   pagination:true,
                   pageSize: 20,
                   pageList: [20,50,100],
                   url:'<?=\Yii::$app->urlManager->createUrl(['company/userprivilege/list']);?>'">
                <thead>
                <tr>
                    <th data-options="field:'itemid',sortable:true,checkbox:true" width="20%">用户名</th>
                    <th field="worker_name" width="20%">用户名</th>
                    <th field="user_name"  width="50%">用户名称</th>
                </tr>
                </thead>
            </table>
        </form>
    </div>
</div>

<script type="text/javascript" src="easyui1.4.1/jquery.easyui.extend.validate.js"> </script>
<script type="text/javascript" src="js/company/privilege.js"> </script>
<script language="javascript">
    Pri.operateUrls = <?=json_encode($datas['urls'])?>;
    Pri.init();
</script>