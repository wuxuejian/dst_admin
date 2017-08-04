<div style="padding:10px;">
    <form id="easyui-form-car-stock-add">
    	<input type="hidden" name="car_type" value="1">
        <ul class="ulforform-resizeable">
            <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">所属公司</div>
                   <div class="ulforform-resizeable-input">
                    <input class="easyui-combotree" name="operating_company_id" style="width:180px;" id="add_user_oc" 
                           data-options="
								url: '<?php echo yii::$app->urlManager->createUrl(['operating/combotree/get-operating-company']); ?>',
                                editable: false,
                                panelHeight:'auto',
                                panelWidth:'auto',
                                lines:false,
                                required:true,
                                missingMessage:'请选择运营公司',
								onSelect:CarFaultRegister.operatingCompanySelect
                           "
                        />
                     </div>
            </li>
			<li class="ulforform-resizeable-group">
                <div class="ulforform-resizeable-title">车牌号</div>
                <div class="ulforform-resizeable-input">
                    <select
                        id="easyui-form-car-stock-add-carCombogrid"
                        class="easyui-combobox"
                        name="car_id"
                        style="width:180px;"
                        required="true"
                        editable="false"
                        data-options="panelHeight:'auto'"
                        missingMessage="请选择车牌"
                    >
					</select>
                </div>
            </li>
            <li class="ulforform-resizeable-group">
                <div class="ulforform-resizeable-title">用车部门</div>
                <div class="ulforform-resizeable-input">
                    <select
                        id="add_user_pid"
                        class="easyui-combobox"
                        name="department_id"
                        style="width:180px;"
                        required="true"
                        editable="false"
                        data-options="panelHeight:'auto'"
                        missingMessage="请选择用车部门"
                    >
                       
                    </select>
                    <!-- <input  id="add_user_pid"  class="easyui-textbox" name="department_id"   data-options="editable:false"  required="true" missingMessage="请先选择用车部门" /> -->
                </div>
            </li>
        </ul>
    </form>
</div>

<div id="easyui-dialog-car-stock-add-uploadimage"></div>
<script type="text/javascript">
    var CarFaultRegister = {
		initAddCarCombogrid:function(operating_company_id){
			//初始化-车辆combogrid
            $('#easyui-form-car-stock-add-carCombogrid').combogrid({
                panelWidth: 450,
                panelHeight: 200,
                required: true,
                missingMessage: '请输入车牌号/车架号检索后从下拉列表里选择一项！',
                onHidePanel:function(){
                    var _combogrid = $(this);
                    var value = _combogrid.combogrid('getValue');
                    var text = _combogrid.combogrid('textbox').val();
                    var row = _combogrid.combogrid('grid').datagrid('getSelected');
                    if(!row){ //没有选择表格行但输入有检索字符串时，提示并清除检索字符串
                        if(text && value == text){
                            $.messager.show(
                                {
                                    title: '无效值',
                                    msg:'【' + text + '】不是有效值！请重新输入车牌号/车架号检索后，从下拉列表里选择一项！'
                                }
                            );
                            _combogrid.combogrid('clear');
                        }
                    }else{ //注意：若选择了表格行但是原本应显示为text的车牌号不存在，则改成显示车架号为text！
                        if(!row.plate_number){
                            _combogrid.combogrid('setText', row.vehicle_dentification_number);
                            //_combogrid.combogrid('textbox').val(row.vehicle_dentification_number); //这种不好，因为当输入框再次获得焦点时会自动显示value而非text.
                        }
                    }
                },
                delay: 800,
                mode:'remote',
                idField: 'id',
                textField: 'plate_number',
                url: '<?= yii::$app->urlManager->createUrl(['car/stock/get-cars-by-add']); ?>'+'&operating_company_id='+operating_company_id,
                method: 'get',
                scrollbarSize:0,
                pagination: true,
                pageSize: 10,
                pageList: [10,20,30],
                fitColumns: true,
                rownumbers: true,
                columns: [[
                    {field:'id',title:'车辆ID',width:40,align:'center',hidden:true},
                    {field:'plate_number',title:'车牌号',width:100,align:'center'},
                    {field:'vehicle_dentification_number',title:'车架号',width:150,align:'center'}
                ]]
            });
		},
        init: function(){
            
        },
		operatingCompanySelect: function(rec){
			var oc  = rec.id;
			var curMenuId = 0;
			//加载部门
			$('#add_user_pid').combotree({
				url: "<?php echo yii::$app->urlManager->createUrl(['drbac/department/get-categorys']); ?>&isShowRoot=1&mark=1&oc="+oc,
				editable: false,
				panelHeight:'auto',
				panelWidth:300,
				lines:false,
				onLoadSuccess: function(data){ //展开到当前菜单位置
					if(parseInt(curMenuId)){
						var combTree = $('#add_user_pid');
						combTree.combotree('setValue',curMenuId);
						var t = combTree.combotree('tree');
						var curNode = t.tree('getSelected');
						t.tree('collapseAll').tree('expandTo',curNode.target);
					}
				}
			});
			//加载车牌
			CarFaultRegister.initAddCarCombogrid(oc);
		}
    };
/*
    $("#add_user_oc").combotree({
		url: '<?php echo yii::$app->urlManager->createUrl(['operating/combotree/get-operating-company']); ?>',
		onSelect: function(rec){
			var oc  = rec.id;
			var curMenuId = 0;
			$('#add_user_pid').combotree({
				url: "<?php echo yii::$app->urlManager->createUrl(['drbac/department/get-categorys']); ?>&isShowRoot=1&mark=1&oc="+oc,
				editable: false,
				panelHeight:'auto',
				panelWidth:300,
				lines:false,
				onLoadSuccess: function(data){ //展开到当前菜单位置
					if(parseInt(curMenuId)){
						var combTree = $('#add_user_pid');
						combTree.combotree('setValue',curMenuId);
						var t = combTree.combotree('tree');
						var curNode = t.tree('getSelected');
						t.tree('collapseAll').tree('expandTo',curNode.target);
					}
				}
			});
		}
	});
*/

    //CarFaultRegister.init();
</script>