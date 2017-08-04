<?php
/**
 * Created by PhpStorm.
 * User: lingcheng
 * Date: 2015/6/1
 * Time: 14:00
 */

?>
<div id="layout_userpri" class="easyui-layout" style="width:100%;height:100%;">
    <div class="easyui-panel" title="查询区域" data-options="region:'north',collapsible:true,collapsed:false,border:false"
         style="height:100px;width:100%">
        <from id ="from_puser_search"  class="easyui-form" name="from_user_search" method="post" data-options="novalidate:true" action="<?=\yii\helpers\Url::to(['/company/user/list']) ?>">

            <div class="search_class">账户：<input class="easyui-textbox" name="worker_name" style="width:120px"></div>
            <div class="search_class">姓名：<input class="easyui-textbox" name="user_name"  style="width:120px"></div>
            <div class="search_class">部门：<input id="cc" class="easyui-combobox" name="part_id"
                                  data-options="valueField:'id',textField:'department',url:'<?= \yii\helpers\Url::to(['/company/user/depart']) ?>'" /></div>
            <div class="search_class">
            <div style="text-align:right;margin-right:5px;">
                <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-search'" onclick="Userpri.doSearch()">搜索</a>
                <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-cancel'" onclick="$('#from_puser_search').form('clear')">重置</a>
            </div>
            </div>
        </from>

    </div>
        <div id="userpri_panel_list" data-options="region:'center', href:'',border:false" style="border-top:1px solid #D3D3D3">
        <table id="grid-user-list" title="人员列表" class="easyui-datagrid" style="width:100%;height:98%"
               data-options="
                rownumbers:true,
               singleSelect:true,
               pagination:true,
               striped:true,
               border:false,
               fit:true,
               onDblClickRow:Userpri.dedit,
               pageSize: 20,
               pageList: [20,50,100],
               onLoadSuccess:TW_ENUM.parseGridAlias,
               url:'<?=\Yii::$app->urlManager->createUrl('company/userprivilege/list')?>'"
               idField="itemid"
              iconCls="icon-user" striped="true" toolbar="#tb_userpri">
            <thead data-options="frozen:true">
            <tr>
                <th data-options="field:'itemid',checkbox:true"></th>
               <!-- <th field="boss_id" width="15%">公司</th>-->
                <th data-options="field:'part_id',sortable:true" align="center" width="5%">部门</th>
                <th data-options="field:'worker_name',sortable:true"  align="center" width="8%"  >账户</th>
                <th data-options="field:'user_name',sortable:true"  align="center" width="8%">姓名</th>
                <th data-options="field:'email',sortable:true"   width="15%">邮箱</th>
                <th data-options="field:'mobile',sortable:true"  align="center"  width="10%" >电话</th>
            </tr>
            </thead>
        </table>
        <div id="tb_userpri" style="padding:1px;height:auto">
            <div style="margin-bottom:5px">
                <? foreach ($datas['buttons'] as $key => $b){ ?>
                    <a href="#" class="easyui-linkbutton" id="<?=$key?>" iconCls="<?=$b['class']?>" onClick="<?=$b['click']?>"><?=$b['text']?> </a>
                <? }?>
            </div>
        </div>

    </div>
    <!--以下权限设置页面-->
    <!-- 以下为设置权限窗口  -->
    <div id="win_userprivilege_setpri" > </div>

</div>
<script type="text/javascript" src="js/company/userprivilege.js"> </script>
<script language="javascript">
    Userpri.operateUrls = <?=json_encode($datas['urls'])?>;
   /* Userpri.init();*/
</script>