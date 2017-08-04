<div style="padding:10px 40px 20px 40px">  
    <form id="easyui-form-station-fault-add" class="easyui-form" method="post">
        <div >
            <ul class="ulforform-resizeable">
            	<li class="ulforform-resizeable-group-single">
           		 <div class="ulforform-resizeable-title">父分类</div>
            		<div class="ulforform-resizeable-input">
                		<input name="pid" id="StationFalutCategory_addWin_form_pid" style="width:100%;" />
            		</div>
        		</li>
        		
                <li class="ulforform-resizeable-group-single">
                    <div class="ulforform-resizeable-title">故障名称</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-textbox" style="width:470px;"  name="category" required="true" />
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">编码</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-textbox" style="width:160px;"  name="code" required="true" />
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">东风原始故障码</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-textbox" style="width:160px;"  name="dfm_code"  />
                    </div>
                </li>
            </ul>
        </div>
    </form>
</div>
<script>
    var StationFalutCategory_addWin  = {
        init: function(){
            var curMenuId = 0;
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
