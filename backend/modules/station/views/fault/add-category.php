<form id="StationFalutCategory_addWin_form" method="post" style="padding:5px;">
    <ul class="ulforform-resizeable">
        <li class="ulforform-resizeable-group-single">
            <div class="ulforform-resizeable-title">父分类</div>
            <div class="ulforform-resizeable-input">
                <input name="pid" id="StationFalutCategory_addWin_form_pid" style="width:100%;" />
            </div>
        </li>
        <li class="ulforform-resizeable-group-single">
            <div class="ulforform-resizeable-title">分类名称</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    name="category"
                    style="width:100%;"
                    required="true"
                    validType="length[64]"
                    missingMessage="请填写分类名称！"
                />
            </div>
        </li>
        <li class="ulforform-resizeable-group-single">
            <div class="ulforform-resizeable-title">故障编码</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    name="code"
                    style="width:100%;"
                    validType="length[16]"
                />
            </div>
        </li>
    </ul>
</form>
<script>
    var StationFalutCategory_addWin  = {
        init: function(){
            var curMenuId = <?= $curMenuId; ?>;
            $('#StationFalutCategory_addWin_form_pid').combotree({
                url: "<?php echo yii::$app->urlManager->createUrl(['station/fault/get-categorys']); ?>&isShowRoot=1",
                editable: false,
                panelHeight:'auto',
                panelWidth:300,
                lines:false,
                onLoadSuccess: function(data){ //展开到当前菜单位置
                    if(parseInt(curMenuId)){
                        var combTree = $('#StationFalutCategory_addWin_form_pid');
                        combTree.combotree('setValue',curMenuId);
                        var t = combTree.combotree('tree');
                        var curNode = t.tree('getSelected');
                        t.tree('collapseAll').tree('expandTo',curNode.target);
                    }
                }
            });
        }
    };
    StationFalutCategory_addWin.init();
</script>