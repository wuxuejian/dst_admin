<form id="promotionLetInfoIndex_editWin_form" method="post" style="padding:10px 0px;">
    <table cellpadding="5" cellspacing="0" style="width:100%;" border="0">
        <input type="hidden" name="id" />
        <input type="hidden" name="inviter_invite_code" />
        <tr>
            <td align="right" width="15%">租车客户</td>
            <td width="25%">
                <input name="renter_id" id="promotionLetInfoIndex_editWin_chooseRenter" style="width:160px;"  />
            </td>
            <td align="right" width="15%">手机号</td>
            <td>
                <input class="easyui-numberbox" name="renter_mobile" id="promotionLetInfoIndex_editWin_renterMobile" style="width:160px;" disabled="true" />
            </td>
        </tr>
        <tr>
            <td align="right">租车数量</td>
            <td>
                <input class="easyui-numberbox" name="amount" style="width:160px;" required="true" min="1" />
            </td>
            <td align="right">受理人员</td>
            <td>
                <input class="easyui-textbox" name="operator" style="width:160px;" required="true" />
            </td>
        </tr>
        <tr>
            <td align="right">合同编号</td>
            <td>
                <input class="easyui-textbox" name="contract_no" style="width:160px;" required="true"   />
            </td>
            <td align="right">签订日期</td>
            <td>
                <input class="easyui-datebox" name="sign_date" style="width:160px;" validType="date"  />
            </td>
        </tr>
        <tr>
            <td align="right" valign="top">备注</td>
            <td colspan="5">
                <input class="easyui-textbox" name="mark" style="width:470px;height:60px;"
                       data-options="multiline:true"
                       validType="length[150]"  />
            </td>
        </tr>
    </table>
</form>

<script>
    var promotionLetInfoIndex_editWin = {
        init:function(){
            // 初始化【选择租车客户】combogrid
            $('#promotionLetInfoIndex_editWin_chooseRenter').combogrid({
                panelWidth: 500,
                panelHeight: 200,
                scrollbarSize:0,
                pagination: true,
                pageSize: 10,
                pageList: [10,20,30],
                fitColumns: true,
                rownumbers: true,
                delay: 800,
                mode:'remote',
                idField: 'id',
                textField: 'client',
                url: "<?php echo yii::$app->urlManager->createUrl(['promotion/combogrid/get-renters-list']); ?>",
                method: 'get',
                columns: [[
                    {field:'id',title:'ID',width:35,align:'center',hidden:true},
                    {field:'client',title:'姓名',width:80,align:'center'},
                    {field:'mobile',title:'手机',width:100,align:'center'},
                    {field:'invite_code_mine',title:'专属邀请码',width:100,align:'center'},
                    {field:'invite_code_used',title:'使用邀请码',width:100,align:'center'}
                ]],
                required: true,
                missingMessage: '请从下拉列表里选择租车客户！',
                onHidePanel:function(){
                    var _combogrid = $(this);
                    var value = _combogrid.combogrid('getValue');
                    var textbox = _combogrid.combogrid('textbox');
                    var text = textbox.val();
                    var rows = _combogrid.combogrid('grid').datagrid('getSelections');
                    if(text && rows.length < 1 && value == text){
                        $.messager.show({
                            title: '无效',
                            msg:'【' + text + '】不是有效租车客户！请重新检索并选择！'
                        });
                        _combogrid.combogrid('clear');
                    }else{
                        var record =  _combogrid.combogrid('grid').datagrid('getSelected');
                        var data = {
                            'renter_mobile': record.mobile,
                            'inviter_invite_code': record.invite_code_used
                        };
                        $('#promotionLetInfoIndex_editWin_form').form('load',data);
                    }
                }
            });

            // 修改时会加载旧表单数据
            var letInfo = <?php echo isset($letInfo) ? json_encode($letInfo) : 0; ?>;
            if(letInfo){
                $('#promotionLetInfoIndex_editWin_form').form('load',letInfo);
                // combogrid远程查询并赋值
                $('#promotionLetInfoIndex_editWin_chooseRenter').combogrid('grid').datagrid('load',{renterId: letInfo.renter_id});
            }
        }
    }

    //执行初始化函数
    promotionLetInfoIndex_editWin.init();

</script>