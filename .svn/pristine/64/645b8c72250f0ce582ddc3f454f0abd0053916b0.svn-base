<div style="padding:10px 20px">
    <form id="operatingBasicIndex_addWin_form">
        <ul class="ulforform-resizeable">
            <li class="ulforform-resizeable-group-single">
                <div class="ulforform-resizeable-title">父级运营公司</div>
                <div class="ulforform-resizeable-input">
                    <input
                        class="easyui-textbox"
                        name="pid"
                        style="width:180px;"
                    />
                </div>
            </li>
            <li class="ulforform-resizeable-group-single">
                <div class="ulforform-resizeable-title">所属大区</div>
                <div class="ulforform-resizeable-input">
                    <input
                        class="easyui-textbox"
                        name="area"
                        style="width:180px;"
                        required="true"
                        validType="length[255]"
                    /> 
                </div>
            </li>
            <li class="ulforform-resizeable-group-single">
                <div class="ulforform-resizeable-title">运营公司名称</div>
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
            <li class="ulforform-resizeable-group-single">
                <div class="ulforform-resizeable-title">运营公司地址</div>
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
	var operatingBasicIndex_addWin  = {
		init: function(){
			var easyuiForm = $('#operatingBasicIndex_addWin_form');
            easyuiForm.find('input[name=pid]').combotree({
                url: "<?php echo yii::$app->urlManager->createUrl(['operating/combotree/get-operating-company']); ?>&isShowRoot=1",
                editable: false,
                panelHeight:'auto',
                panelWidth:300,
                lines:false
            });
            easyuiForm.find('input[name=area]').combobox({
                valueField:'value',
                textField:'text',
                editable: false,
                panelHeight:'auto',
                data: [{"value": '',"text": ''},{"value": 1,"text": '华南大区'},{"value": 2,"text": '华北大区'},{"value": 3,"text": '华东大区'},{"value": 4,"text": '华中大区'},{"value": 5,"text": '西南大区'}],
                onSelect: function(){
                    
                }
            });
		}
	};
	operatingBasicIndex_addWin.init();
</script>