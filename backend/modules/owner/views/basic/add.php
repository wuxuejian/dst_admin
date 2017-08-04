<div style="padding:10px 20px">
    <form id="ownerBasicIndex_addWin_form">
        <ul class="ulforform-resizeable">
            <li class="ulforform-resizeable-group-single">
                <div class="ulforform-resizeable-title">父级所有人</div>
                <div class="ulforform-resizeable-input">
                    <input
                        class="easyui-textbox"
                        name="pid"
                        style="width:180px;"
                    />
                </div>
            </li>
            <li class="ulforform-resizeable-group">
                <div class="ulforform-resizeable-title">所有人名称</div>
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
                <div class="ulforform-resizeable-title">所有人编号</div>
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
                <div class="ulforform-resizeable-title">所有人地址</div>
                <div class="ulforform-resizeable-input">
                    <input
                        class="easyui-textbox"
                        name='addr'
                        style="width:490px;"
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
	var ownerBasicIndex_addWin  = {
		init: function(){
			var easyuiForm = $('#ownerBasicIndex_addWin_form');
            easyuiForm.find('input[name=pid]').combotree({
                url: "<?php echo yii::$app->urlManager->createUrl(['owner/combotree/get-owners']); ?>&isShowRoot=1",
                editable: false,
                panelHeight:'auto',
                panelWidth:300,
                lines:false
            });
		}
	};
	ownerBasicIndex_addWin.init();
</script>