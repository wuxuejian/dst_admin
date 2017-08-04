<div style="padding:10px;">
    <form id="easyui-form-customer-contract-record-index-stop">
    	<input type="hidden" name="id" value="<?=$contractInfo['id']?>">
        <ul class="ulforform-resizeable">
            <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">终止合作类型</div>
                   <div class="ulforform-resizeable-input">
                    <input class="easyui-combotree" name="stop_type" style="width:180px;" id="easyui-form-customer-contract-record-index-stop-type" 
                           data-options="
                                url: '<?php echo yii::$app->urlManager->createUrl(['customer/contract-record/get-stop-type']); ?>',
                                editable: false,
                                panelHeight:'auto',
                                panelWidth:'auto',
                                lines:false,
                                required:true,
                                missingMessage:'请选择终止合作类型',
								onLoadSuccess:stopTypeLoadSuccess,
								onSelect: loadStopCause
                           "
                        />
                     </div>
            </li>
            <li class="ulforform-resizeable-group">
                <div class="ulforform-resizeable-title">终止合作原因</div>
                <div class="ulforform-resizeable-input">
                    <select
                        id="easyui-form-customer-contract-record-index-stop-cause"
                        class="easyui-combobox"
                        name="stop_cause"
                        style="width:180px;"
                        required="true"
                        editable="false"
                        data-options="panelHeight:'auto'"
                        missingMessage="请选择终止合作原因"
                    >
                       
                    </select>
                    <!-- <input  id="add_user_pid"  class="easyui-textbox" name="department_id"   data-options="editable:false"  required="true" missingMessage="请先选择用车部门" /> -->
                </div>
            </li>
        </ul>
    </form>
</div>
<script>
	var stop_type = <?=$contractInfo['stop_type']?>;
	var stop_cause = <?=$contractInfo['stop_cause']?>;
	function stopTypeLoadSuccess(){
		$("#easyui-form-customer-contract-record-index-stop-type").combotree('setValue',stop_type);
		initStopCause(stop_type);
	}
	function initStopCause(stop_type){
		$('#easyui-form-customer-contract-record-index-stop-cause').combotree({
			url: "<?php echo yii::$app->urlManager->createUrl(['customer/contract-record/get-stop-cause']); ?>&stop_type="+stop_type,
			editable: false,
			panelHeight:'auto',
			panelWidth:300,
			lines:false,
			onLoadSuccess: function(data){ //展开到当前菜单位置
				$("#easyui-form-customer-contract-record-index-stop-cause").combotree('setValue',stop_cause);
			}
		});
	}
	function loadStopCause(rec){
		stop_type  = rec.id;
		$('#easyui-form-customer-contract-record-index-stop-cause').combotree({
			url: "<?php echo yii::$app->urlManager->createUrl(['customer/contract-record/get-stop-cause']); ?>&stop_type="+stop_type,
			editable: false,
			panelHeight:'auto',
			panelWidth:300,
			lines:false,
			onLoadSuccess: function(data){ //展开到当前菜单位置
				$("#easyui-form-customer-contract-record-index-stop-cause").combotree('setValue',0);
			}
		});
	}
	//表单赋值
	
</script>