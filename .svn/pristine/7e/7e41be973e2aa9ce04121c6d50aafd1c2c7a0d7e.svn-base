<?php
/**
 * Created by PhpStorm.
 * User: lingcheng
 * Date: 2015/9/24
 * Time: 13:57
 */
?>
<div id="layout_fronted_company_user" class="easyui-layout" style="width: 100%;height:100%" >

    <div id="search_fronted_company_user" class="easyui-panel" data-options="region:'north',border:false,collapsible:false" title="查询区域" style="width: 100%;height:100px">
        <from id ="from_user_search"  class="easyui-form" name="from_user_search" method="post" data-options="novalidate:true" action="<?=\yii\helpers\Url::to(['/company/user/list']) ?>">

            <div class="search_class">账户：<input class="easyui-textbox" name="worker_name" style="width:120px"></div>

            <div class="search_class">姓名：<input class="easyui-textbox" name="user_name"  style="width:120px"></div>

            <div class="search_class">部门：<input id="cc" class="easyui-combobox" name="part_id"
                                  data-options="valueField:'id',textField:'department',url:'<?= \yii\helpers\Url::to(['/company/user/depart']) ?>'" panelHeight="auto"/></div>

            <div class="search_class">
            <div style="text-align:right;margin-right:5px;">
                <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-search'" onclick="User.doSearch()">搜索</a>
                <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-cancel'" onclick="$('#from_user_search').form('clear')">重置</a>
            </div></div>
        </from>
    </div>
    <div  id="" class="easyui-panel"  data-options="region:'center',border:false,collapsible:false"   style="width: 100%;height:auto;border-top:  1px solid #95B8E7;">
        <table id = "grid_fronted_company_user" class="easyui-datagrid" style="%;height:100%"  title="用户列表"
               data-options="
               fit:true,
               border:false,
               rownumbers:true,
               singleSelect:true,
               onDblClickRow:User.dedit,
               striped:true,
               pagination:true,
               pageSize: 20,
               pageList: [20,50,100],
               rowStyler:function(index,row){
               if (row.status_lock!=0){
               return 'color:red;';
        }
        },
        onLoadSuccess:TW_ENUM.parseGridAlias,
        url:'<?=\yii\helpers\Url::to(['/company/user/list']) ?>',
        method:'get'"
        idField="itemid"
        iconCls="icon-user" striped="true" toolbar="#tb_fronted_company_user">
        <thead >
        <tr>
            <th data-options="field:'itemid',checkbox:true"></th>
            <th data-options="field:'worker_name',sortable:true" width="8%"  >账户</th>
            <th data-options="field:'user_name',sortable:true" width="8%">姓名</th>
            <th data-options="field:'part_id',sortable:true"  width="5%">部门</th>
            <th data-options="field:'sex', codeAlias:'sex',sortable:true" editor:{type:'combobox'}" width="3%" align="center" >性别</th>
            <th data-options="field:'email',sortable:true"  width="12%">邮箱</th>
            <th data-options="field:'mobile',sortable:true"  width="7%" align="center">手机</th>
            <th data-options="field:'telephone',sortable:true"  width="8%" align="center">电话</th>
            <th data-options="field:'qq',sortable:true" width="7%" >QQ</th>
            <th data-options="field:'principal',sortable:true, codeAlias:'yn', editor:{type:'combobox'}" width="4%" align="center">负责人</th>
            <th data-options="field:'online',sortable:true, codeAlias:'user_online', editor:{type:'combobox'},
                    styler: function(value,row,index){
                                if (value == 1){
                                  return 'color:#00FF66';
                                 }else{
                                 return 'color:#ccc';
                                 }
			               }
			" width="5%" align="center">状态</th>
            <th data-options="field:'status_lock',sortable:true,codeAlias:'status_lock', editor:{type:'combobox'}" align="center" width="5%">锁定</th>
            <th data-options="field:'login_time',sortable:true"   width="12%" align="center">最后登录时间</th>
            <th data-options="field:'login_ip',sortable:true"   width="10%" align="center">最后登陆IP</th>
            <th data-options="field:'admin',hidden:'true'"  width="10%"></th>
        </tr>
        </thead>
        </table>

    </div>

    <div id="tb_fronted_company_user" style="height:auto">
        <? foreach ($datas['buttons'] as $key=> $b){ ?>
            <a href="#" class="easyui-linkbutton" id="<?=$key?>" iconCls="<?=$b['class']?>" onClick="<?=$b['click']?>"><?=$b['text']?> </a>
        <? }?>
    </div>
<!--编辑窗口-->
    <div id="fronted_company_user_edit"></div>

    <!-- 以下为设置角色窗口  -->
    <div id="win_user_setrole"
         class="easyui-dialog" title="角色设置" style="width:400px;height:300px;"
         data-options="resizable:true,modal:true,closed:true,
     buttons:[{
				text:'保存',
				iconCls:'icon-save',
				handler:User.savePri
			},{
				text:'取消',
				iconCls:'icon-cancel',
				handler:User.calcePri
			}]">
        <form action="" name="from_user_role" id="from_user_role" method="post">
            <table id="role_table"></table>
            <input name="uid" id="uid" type="hidden" value="<?=$uid?>">
        </form>
    </div>
<!--修改密码窗口-->
    <div id="win_user_setpw"
         class="easyui-dialog" title="修改密码" style="width:400px;height:200px;"
         data-options="resizable:true,modal:true,closed:true,
         buttons:[{
				text:'保存',
				iconCls:'icon-save',
				handler:User.savePw
			},{
				text:'取消',
				iconCls:'icon-cancel',
				handler:User.calcePw
			}]">
        <form action="" name="from_user_pw" id="from_user_pw" method="post">
            <table width="100%" border="0" cellpadding="1" cellspacing="1">
                <tr>
                    <td colspan="2" style="line-height: 20px;">
                        &nbsp;<input type="hidden" name="itemid" id="itemid" value="">
                    </td>
                </tr>
                <tr>
                    <td>新密码：</td>
                    <td><input type="password" name="p1" id="p1" class="easyui-textbox" data-options="required:true"></td>
                </tr>
                <tr>
                    <td>重复密码：</td>
                    <td><input type="password" name="p2" id="p2" class="easyui-textbox" data-options="required:true" validType="equals['#p1']"></td>
                </tr>
            </table>

        </form>
    </div>




</div>
<script type="text/javascript" src="js/company/user.js"></script>
<script type="text/javascript">
    User.operateUrls = <?= json_encode($datas['urls'])?>;
</script>