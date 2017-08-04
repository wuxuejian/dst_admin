<form id="SystemMenuIndex_addWin_form" method="post" style="padding:5px;">
    <ul class="ulforform-resizeable">
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">父菜单</div>
            <div class="ulforform-resizeable-input">
                <input name="pid" id="SystemMenuIndex_addWin_form_pid" style="width:100%;" />
            </div>
        </li>
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">菜单名称</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    name="name"
                    style="width:100%;"
                    required="true"
                    validType="length[50]"
                    missingMessage="请填写菜单名称！"
                />
            </div>
        </li>
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">菜单图标</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    name="icon_class"
                    style="width:100%;"
                    validType="length[100]"
                />
            </div>
        </li>
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">排序号</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-numberbox"
                    name="list_order"
                    style="width:100%;"
                    validType="int"
                    min="0"
                />
            </div>
        </li>
        <li class="ulforform-resizeable-group-single">
            <div class="ulforform-resizeable-title">菜单MCA</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    name="mca"
                    style="width:510px;"
                    validType="length[255]"
                    prompt="填写格式：模块/控制器/方法"
                />
            </div>
        </li>
        <li class="ulforform-resizeable-group-single">
            <div class="ulforform-resizeable-title">外链URL</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    name="target_url"
                    style="width:510px;"
                    validType="length[255]"
                />
            </div>
        </li>
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">默认展开</div>
            <div class="ulforform-resizeable-input">
                <select
                    class="easyui-combobox"
                    name="opend"
                    style="width:100%;"
                    data-options="{panelHeight:'auto',required:true,editable: false}" >
                    <option value="0">否</option>
                    <option value="1">是</option>
                </select>
            </div>
        </li>
        <li class="ulforform-resizeable-group-single">
            <div class="ulforform-resizeable-title">备注</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    name="note"
                    style="width:510px;height:86px;"
                    data-options="multiline:true"
                    validType="length[255]"
                />
            </div>
        </li>
    </ul>
</form>
<script>
    var SystemMenuIndex_addWin  = {
        init: function(){
            var curMenuId = <?= $curMenuId; ?>;
            $('#SystemMenuIndex_addWin_form_pid').combotree({
                url: "<?php echo yii::$app->urlManager->createUrl(['system/combotree/get-menus']); ?>&isShowRoot=1",
                editable: false,
                panelHeight:'auto',
                panelWidth:300,
                lines:false,
                onLoadSuccess: function(data){ //展开到当前菜单位置
                    if(parseInt(curMenuId)){
                        var combTree = $('#SystemMenuIndex_addWin_form_pid');
                        combTree.combotree('setValue',curMenuId);
                        var t = combTree.combotree('tree');
                        var curNode = t.tree('getSelected');
                        t.tree('collapseAll').tree('expandTo',curNode.target);
                    }
                }
            });
        }
    };
    SystemMenuIndex_addWin.init();
</script>