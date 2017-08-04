<table id="easyui-datagrid-car-stock-replace-log-index"></table> 
<div id="easyui-datagrid-car-stock-replace-log-index-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-car-stock-replace-log-index">
                <ul class="search-main">
                    <li>
                        <div class="item-name">车牌号</div>
                        <div class="item-input">
                            <input name="plate_number" style="width:200px;" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">客户名称</div>
                        <div class="item-input">
                            <input name="company_name" style="width:200px;" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">替换时间</div>
                        <div class="item-input">
                            <input class="easyui-datebox" type="text" name="replace_start_time" style="width:93px;"
                                   data-options=""
                                />
                            -
                            <input class="easyui-datebox" type="text" name="replace_end_time" style="width:93px;"
                                   data-options=""
                                />
                        </div>
                    </li>
                    <li class="search-button">
                        <button type="submit" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button type="submit" onclick="CarStockReplaceLogIndex.resetForm()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
                    </li>
                </ul>
            </form>
        </div>
    </div>
    <?php if($buttons){ ?>
        <div class="easyui-panel" title="数据列表" style="padding:3px 2px;width:100%;" data-options="
        iconCls: 'icon-table-list',
        border: false
    ">
            <?php foreach($buttons as $val){ ?>
                <button onclick="<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon']; ?>'"><?= $val['text']; ?></button>
            <?php } ?>
        </div>
    <?php } ?>
</div>

<!-- 窗口 -->
<script>
	var CarStockReplaceLogIndex = new Object();
	CarStockReplaceLogIndex.init = function(){
		$('#easyui-datagrid-car-stock-replace-log-index').datagrid({  
			method: 'get', 
		    url:"<?php echo yii::$app->urlManager->createUrl(['car/stock-replace-log/get-list']); ?>",   
			fit: true,
			border: false,
			toolbar: "#easyui-datagrid-car-stock-replace-log-index-toolbar",
			pagination: true,
			loadMsg: '数据加载中...',
			striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
			pageSize: 20,
            frozenColumns: [[
				{field: 'ck',checkbox: true}, 
				{field: 'id',title: 'id',hidden: true},   
				{field: 'plate_number',title: '车牌号',width: 70,sortable: true,align: 'center'}
			]],
		    columns: [[
				{
				    field: 'car_type',title: '车辆类型',width: 70,align: 'center',
				    sortable: true,
				    formatter: function(value){
				        if(value == 1){
				            return '自用车';
				        }else if(value == 2){
				        	return '备用车';
				        }
				    }
				},
				{field: 'company_name',title: '客户',width: 180,align: 'center',sortable: true},
				{field: 'replace_plate_number',title: '被替换车辆',width: 70,align: 'center',sortable: true},
				{field: 'replace_desc',title: '替换原因',width: 200,align: 'center',sortable: true},
				{field: 'replace_start_time',title: '替换时间',width: 140,align: 'center',sortable: true},
				{field: 'replace_end_time',title: '预计还车时间',width: 140,align: 'center',sortable: true},
				{field: 'real_end_time',title: '实际还车时间',width: 140,align: 'center',sortable: true},
				{field: 'username',title: '操作人',width: 70,align: 'center',sortable: true},
				{field: 'add_time',title: '操作时间',width: 140,align: 'center',sortable: true}
            ]],
            onDblClickRow: function(rowIndex,rowData){
                CarStockReplaceLogIndex.edit(rowData.id);
            },
            onLoadSuccess: function (data) {
                //单元格内容悬浮提示，doCellTip()是在入口文件index.php中拓展的。
                $(this).datagrid('doCellTip', {
                    position: 'bottom',
                    maxWidth: '300px',
                    onlyShowInterrupt: true, //false时所有单元格都显示提示；true时配合specialShowFields自定义要提示的列
                    specialShowFields: [     //需要提示的列
                        //{field: 'sketch', showField: 'sketch'}
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
        var searchForm = $('#search-form-car-stock-replace-log-index');
        /**查询表单提交事件**/
        searchForm.submit(function(){
            var data = {};
            var searchCondition = $(this).serializeArray();
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = searchCondition[i]['value'];
            }
            $('#easyui-datagrid-car-stock-replace-log-index').datagrid('load',data);
            return false;
        });
        searchForm.find('input[name=plate_number]').textbox({
            onChange: function(){
                searchForm.submit();
            }
        });
        searchForm.find('input[name=company_name]').textbox({
            onChange: function(){
                searchForm.submit();
            }
        });
	}
	CarStockReplaceLogIndex.init();

    //按条件导出车辆列表
    CarStockReplaceLogIndex.exportWidthCondition = function(){
        var url = "<?php echo yii::$app->urlManager->createUrl(['car/stock-replace-log/export-width-condition']);?>";
        var form = $('#search-form-car-stock-replace-log-index');
        var data = {};
        var searchCondition = form.serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        for(var i in data){
            url += '&'+i+'='+data[i];
        }
        window.open(url);
    }
    //重置查询表单
    CarStockReplaceLogIndex.resetForm = function(){
        var easyuiForm = $('#search-form-car-stock-replace-log-index');
        easyuiForm.form('reset');
    }
</script>