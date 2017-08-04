<?php
/* @var $this yii\web\View */
?>
<div id="layout_department" class="easyui-layout" style="width:100%;height:100%;">
    <div id="department_panel_list" data-options="region:'center', border:false">
        <table id="grid-department-list" title="商户部门列表" class="easyui-datagrid" style="width:100%"
               data-options="
               rownumbers:true,
                fit:true,
                 border:false,
               singleSelect:true,
               pagination:true,
               striped:true,
               onDblClickRow:Department.dedit,
               pageSize: 20,
               pageList: [20,50,100],
               url:'<?=\Yii::$app->urlManager->createUrl('company/department/list')?>',
               toolbar:'#tb_department'">
            <thead>
                <tr>
                    <th field="id" checkbox="true"></th>
                    <!--<th field="boss_id" width="15%">商户</th>-->
                    <th data-options="field:'department',sortable:true"  width="5%">部门名称</th>
                    <th data-options="field:'shortening',sortable:true" field="shortening"  width="8%"  >简写</th>
                    <th data-options="field:'sort',sortable:true" width="8%">排序</th>
                    <th data-options="field:'memo'" width="75%">备注</th>

                </tr>
            </thead>
        </table>

        <div id="tb_department" style="padding:1px;height:auto">
            <div style="margin-bottom:5px">
                <? foreach ($buttons as $key=> $b){ ?>
                    <a href="#" class="easyui-linkbutton" id="<?=$key?>" iconCls="<?=$b['class']?>" onClick="<?=$b['click']?>"><?=$b['text']?> </a>
                <? }?>
            </div>
        </div>

  </div>

    <!-- 以下为编辑窗口  -->
    <div id="win_department_edit" class="easyui-dialog" title="编辑部门信息" style="width:500px;height:250px"
         data-options="iconCls:'icon-edit',modal:true,closed:true,closable:true,inline:true,
            buttons: [{
					text:'保存',
					iconCls:'icon-save',
					handler: Department.submitDepartmentForm
				},{
					text:'取消',
					iconCls:'icon-cancel',
					handler: Department.closeDepartmentEdit
				}]
		">
        <form id="form_depart_edit" name="form1" method="post" action="">
            <input name='id' type='hidden' />
            <table width="100%" border="0" cellpadding="1" cellspacing="1">
                <tr>
                    <td colspan="4" align="left" class="group_label">&nbsp;</td></td>
                </tr>

                <tr>
                    <td width="201" align="right">部&nbsp;&nbsp;门：</td>
                    <td width="201">
                    <input  name="department" size='15' type='text'  size='15' class="easyui-textbox" data-options="required:true"/>
                    </td>
                    <td width="201" align="right">部门简写：</td>
                    <td width="254"><input name='shortening' type='text'  size='15' class="easyui-textbox" data-options="required:true" validType="strCode"/></td>
                </tr>
                <tr>
                    <td width="122" align="right">排&nbsp;&nbsp;序：</td>
                    <td width="254"><input name='sort' type='text'  size='15' class="easyui-textbox" /></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td width="122" align="right">备&nbsp;&nbsp;注：</td>
                    <td colspan="3"><input name='memo' class="easyui-textbox" data-options="multiline:true" style="width: 300px;height: 60px;"/></td>

                </tr>
            </table>
        </form>
    </div>

</div>
<script type="text/javascript" src="easyui1.4.1/jquery.easyui.extend.validate.js"> </script>
<script type="text/javascript" src="js/company/depart.js"> </script>
<script language="javascript">
    Department.operateUrls = <?=json_encode($urls)?>;
    Department.init();
</script>
