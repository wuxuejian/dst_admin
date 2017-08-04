<table id="easyui-datagrid-process-inspection-all-detail"></table>
<div id="process-inspection-all-detail-toolbar"> 
<table cellpadding="6" cellspacing="0" align="center"  width="100%" border="0" style="background:#FFF">
	<tr>
		<td align="right" width="13%">抽检批次编号：</td>
		<td width="20%">
			<?=$detail['id']?>
		</td>
		<td align="right" width="13%">车辆品牌：</td>
		<td width="20%">
			<?=$detail['car_brand']?>
		</td>
		<td align="right" width="13%">产品型号：</td>
		<td width="20%">
			<?=$detail['car_model']?>
		</td>
	</tr>
	<tr>
		<td align="right" width="13%">计划提车数量：</td>
		<td width="20%">
			<?=$detail['car_num']?>
		</td>
		<td align="right" width="13%">实际提车数量：</td>
		<td width="20%">
			<?=$detail['real_car_num']?>
		</td>
		<td align="right" width="13%"></td>
		<td width="20%">
		</td>
	</tr>
	<tr>
		<td align="right" width="13%">验车负责人：</td>
		<td width="20%">
			<?=$detail['inspection_director_name']?>
		</td>
		<td align="right" width="13%">验车日期：</td>
		<td width="20%">
			<?=$detail['validate_car_time']?>
		</td>
		<td align="right" width="13%"></td>
		<td width="20%">
		</td>
	</tr>
	<tr>
		<td align="right" width="13%">备注：</td>
		<td width="20%" colspan="5">
			<?=$detail['note']?>
		</td>
	</tr>
</table>
<div style="border-top:1px solid #95B8E7;"></div>
	<div class="easyui-panel" title="车辆检验明细" style="width:100%" data-options="
	        iconCls: 'icon-search',
	        border: false
	    ">  
		<div class="data-search-form">
				<form id="search-form-process-inspection-all-detail">
	                <ul class="search-main">
	                    <li>
	                        <div class="item-name">车架号</div>
	                        <div class="item-input" style="width:100px;" >
	                            <input class="easyui-textbox" type="text" name="vehicle_dentification_number" style="width:100px;" />
	                        </div>
	                    </li>
	                    <li>
	                        <div class="item-name">检查结果</div>
	                        <div class="item-input" style="width:100px;">
	                            <input style="width:100px;" name="inspection_result" />
	                        </div>
	                    </li>
	                    <li>
	                        <div class="item-name">是否提车</div>
	                        <div class="item-input" style="width:100px;">
	                            <input style="width:100px;" name="is_put" />
	                        </div>
	                    </li>
	                    <li class="search-button">
	                        <a id="btn" href="javascript:ProcessInspectionAllDetail.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
	                    </li>
	                </ul>
	            </form>
		</div>
	</div>
<div class="easyui-panel" title="数据列表" style="padding:3px 2px;width:100%;" data-options="
        iconCls: 'icon-tip',
        border: false
    ">
        <a href="javascript:ProcessInspectionAllDetail.exportCarsWidthCondition()" class="easyui-linkbutton" data-options="iconCls:'icon-excel'">导出列表</a>
</div>
</div>

<!-- toolbar end -->
<form style="display:none;" id="process-inspection-all-detail-submit-data"></form>
<script>
    var ProcessInspectionAllDetail = new Object();
    ProcessInspectionAllDetail.init = function(){
        var inspection_result_data = <?=json_encode(array(array('id'=>'1','inspection_result'=>'合格'),array('id'=>'2','inspection_result'=>'不合格')))?>;
        var is_put_data = <?=json_encode(array(array('id'=>'1','is_put'=>'已提车'),array('id'=>'2','is_put'=>'未提车')))?>;
        //初始化datagrid
        $('#easyui-datagrid-process-inspection-all-detail').datagrid({
            method: 'get', 
            url:"<?php echo yii::$app->urlManager->createUrl(['process/inspection-all/get-car-list','inspection_id'=>$inspection_id]); ?>",  
            toolbar: "#process-inspection-all-detail-toolbar",
            border: false,
            fit: true,
            pagination: true,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: false,
            showFooter: true,
            frozenColumns: [[
                {field: 'ck',checkbox: true}, 
                {field: 'id',title: 'id',hidden: true},   
                {
                    field: 'vehicle_dentification_number',title: '车架号',width: '15%',sortable: true
                }
            ]],
            columns:[[
				{
				    
				    field: 'inspection_result',title:'检验结果',width: '10%',
					formatter:function(value,row,index){
						for (var i = 0; i < inspection_result_data.length; i++) {
							if (inspection_result_data[i].id == value) {
								return inspection_result_data[i].inspection_result;
							}
						}
					}
				}, 
				{
				    
				    field: 'is_put',title:'提车',width: '10%',
					formatter:function(value,row,index){
						for (var i = 0; i < is_put_data.length; i++) {
							if (is_put_data[i].id == value) {
								return is_put_data[i].is_put;
							}
						}
					}
				}, 
				{
				    field:'car_note',title:'备注',width: '60%',align:'left',
				    editor:{
				        type:'textbox',
				        options:{
				            validType: 'length[255]',
				            prompt: '如车辆检验不合格，请注明原因及处理方法'
				        }
				    }
				}  
            ]],
            onLoadSuccess: function (data) {
                //单元格内容悬浮提示，doCellTip()是在入口文件index.php中拓展的。
                $(this).datagrid('doCellTip', {
                    position: 'bottom',
                    maxWidth: '200px',
                    onlyShowInterrupt: true, //false时所有单元格都显示提示；true时配合specialShowFields自定义要提示的列
                    specialShowFields: [     //需要提示的列
                        //{field: 'company_name', showField: 'company_name'}
                    ],
                    tipStyler: {
                        backgroundColor: '#E4F0FC',
                        borderColor: '#87A9D0',
                        boxShadow: '1px 1px 3px #292929'
                    }
                });
            }
        });
      //构建查询表单
        var searchForm = $('#search-form-process-inspection-all-detail');
        /**查询表单提交事件**/
        searchForm.submit(function(){
            var data = {};
            var searchCondition = $(this).serializeArray();
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = searchCondition[i]['value'];
            }
            $('#easyui-datagrid-process-inspection-all-detail').datagrid('load',data);
            return false;
        });
        searchForm.find('input[name=inspection_result]').combobox({
            valueField:'value',
            textField:'text',
            editable: false,
            panelHeight:'auto',
            data: [{"value": '',"text": '不限'},{"value": 1,"text": '合格'},{"value": 2,"text": '不合格'}],
            onSelect: function(){
                searchForm.submit();
            }
        });
        searchForm.find('input[name=is_put]').combobox({
            valueField:'value',
            textField:'text',
            editable: false,
            panelHeight:'auto',
            data: [{"value": '',"text": '不限'},{"value": 1,"text": '已提车'},{"value": 2,"text": '未提车'}],
            onSelect: function(){
                searchForm.submit();
            }
        });
    }
    ProcessInspectionAllDetail.init();
    //导出
    ProcessInspectionAllDetail.exportCarsWidthCondition = function(){
        window.open("<?= yii::$app->urlManager->createUrl(['process/inspection-all/export-cars-width-condition']); ?>&inspection_id=<?=$inspection_id?>");
    }
  //查询
    ProcessInspectionAllDetail.search = function(){
        var form = $('#search-form-process-inspection-all-detail');
        var data = {};
        var searchCondition = form.serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        $('#easyui-datagrid-process-inspection-all-detail').datagrid('load',data);
    }
</script>
