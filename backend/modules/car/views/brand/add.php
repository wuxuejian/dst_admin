<div style="padding:10px 20px">
    <form id="easyui-form-car-brand-add">
        <ul class="ulforform-resizeable">
            <li class="ulforform-resizeable-group-single">
                <div class="ulforform-resizeable-title">所属品牌</div>
                <div class="ulforform-resizeable-input">
                    <input
                        class="easyui-textbox"
                        name="pid"
                        style="width:180px;"
                    />
                </div>
            </li>
            <li class="ulforform-resizeable-group">
                <div class="ulforform-resizeable-title">品牌名称</div>
                <div class="ulforform-resizeable-input">
                    <input
                        class="easyui-textbox"
                        name="name"
                        style="width:180px;"
                        required="true"
                        validType="length[255]"
                    /> 
                </div>
            </li>
            <li class="ulforform-resizeable-group">
                <div class="ulforform-resizeable-title">品牌编号</div>
                <div class="ulforform-resizeable-input">
                    <input
                        class="easyui-textbox"
                        name="code"
                        style="width:180px;"
                        validType="length[255]"
                    /> 
                </div>
            </li>
            <li class="ulforform-resizeable-group-single">
                <div class="ulforform-resizeable-title">备注</div>
                <div class="ulforform-resizeable-input">
                    <input
                        class="easyui-textbox"
                        name='note'
                        data-options="multiline:true"
                        style="height:60px;width:490px;"
                        validType="length[255]"
                    />
                </div>
            </li>
        </ul>
    </form>
</div>
<script>
	var CarBrandAdd  = {
		init: function(){
			var easyuiForm = $('#easyui-form-car-brand-add');
			easyuiForm.find('input[name=pid]').combogrid({
				panelWidth: 450,
                panelHeight: 200,
                required: true,
                missingMessage: '请选择所属品牌',
                onHidePanel:function(){
                    var _combogrid = $(this);
                    var value = _combogrid.combogrid('getValue');
                    var textbox = _combogrid.combogrid('textbox');
                    var text = textbox.val();
                    var rows = _combogrid.combogrid('grid').datagrid('getSelections');
                    if(text && rows.length < 1){
                        $.messager.show(
                            {
                                title: '无效值',
                                msg:'【' + text + '】不是有效值！请重新检索并选择！'
                            }
                        );
                    }
                },
                delay: 800,
                mode:'remote',
                idField: 'id',
                textField: 'name',
                url: "<?= yii::$app->urlManager->createUrl(['car/combogrid/car-brand']); ?>",
                method: 'get',
                scrollbarSize:0,
                pagination: true,
                pageSize: 10,
                pageList: [10,20,30],
                fitColumns: true,
                rownumbers: true,
                columns: [[
                    {field:'id',hidden:true},
                    {field:'name',title:'品牌名称',width:100,align:'left'},
                    {field:'code',title:'品牌编码',width:100,align:'left'}
                ]]
			});
		}
	};
	CarBrandAdd.init();
</script>