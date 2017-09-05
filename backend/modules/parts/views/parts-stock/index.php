<table id="easyui-datagrid-parts-stock-index"></table> 
<div id="easyui-datagrid-parts-stock-index-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
             <form id="search-form-parts-stock-index">
                <ul class="search-main">
                    <li>
                        <div class="item-name">配件编码</div>
                        <input class="easyui-textbox" type="text" style="width:150px;" name="parts_code" data-options="prompt:'请输入',"/>
                    </li>
                    <li>
                        <div class="item-name">配件名称</div>
                        <input class="easyui-textbox" type="text" style="width:150px;" name="parts_name" data-options="prompt:'请输入',"/>
                    </li>
                    <li>
                        <div class="item-name">运营公司</div>
                        <select
                                class="easyui-combobox"
                                style="width:150px;"
                                name="company"
                                id="company_feng_stock"
                                editable="true"
                                listHeight="200px"
                        >
                            <option value=" ">请选择</option>
                            <?php foreach($searchFormOptions['company'] as $val){?>
                                <option value="<?php echo $val['id']; ?>"><?php echo $val['name']; ?></option>
                            <?php }?>
                        </select>
                    </li>
                    <li>
                        <div class="item-name">仓库名称</div>
                        <input class="easyui-combobox" name="warehouse_address" id="house_name_stock" data-options="prompt:'请输入'," style="width:150px;">
                    </li>
                    <li>
                        <div class="item-name">原厂编码</div>
                            <input class="easyui-textbox" type="text" name="factory_code" style="width:150px;" data-options="prompt:'请输入'," />
                    </li>
                    <li class="search-button">
                        <button type="submit" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button type="submit" onclick="PartsStockIndex.resetForm()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
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
<div id="easyui-dialog-parts-stock-index-showdetail"></div>
<script>
	var PartsStockIndex = new Object();
	PartsStockIndex.init = function(){
		$('#easyui-datagrid-parts-stock-index').datagrid({  
			method: 'get', 
		    url:"<?php echo yii::$app->urlManager->createUrl(['parts/parts-stock/get-list']); ?>",  
            idField: 'id',
            treeField: 'contract_number', 
			fit: true,
			border: false,
			toolbar: "#easyui-datagrid-parts-stock-index-toolbar",
			pagination: true,
			loadMsg: '数据加载中...',
			striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
			pageSize: 20,
		    columns:[[
                {field: 'company',title: '运营公司',width: 150,halign: 'center'},
                {field: 'house_name',title: '仓储地点',width: 100,halign: 'center'},
                {field: 'parts_code',title: '配件编码',width: 100,halign: 'center'},
                {field: 'parts_name',title: '配件名称',width: 150,halign: 'center'},
                {field: 'size',title: '规格',width: 150,halign: 'center'},
                {field: 'unit',title: '单位',width: 150,halign: 'center'},
                {field: 'storage_num',title: '库存数量',width: 150,halign: 'center'},
                {field: 'factory_code',title: '原厂编码',width: 150,halign: 'center'},
            ]],
            onDblClickRow: function(rowIndex,rowData){
                PartsStockIndex.edit(rowData.id);
            }
		});

		//构建查询表单
        var searchForm = $('#search-form-parts-stock-index');
        /**查询表单提交事件**/
        searchForm.submit(function(){
            var data = {};
            var searchCondition = $(this).serializeArray();
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = searchCondition[i]['value'];
            }
            $('#easyui-datagrid-parts-stock-index').datagrid('load',data);
            return false;
        });
		 searchForm.find('input[name=brand_id]').combotree({
            url: "<?php echo yii::$app->urlManager->createUrl(['car/combotree/get-car-brands']); ?>",
            editable: true,
            panelHeight:'auto',
            lines:false,
            onChange: function(o){
                searchForm.submit();
            }
        });

        searchForm.find('select[name=warehouse_address]').combobox({
            valueField:'value',
            textField:'text',
            data: '',
            editable: true,
            panelHeight:'auto',
            onSelect: function(){
                searchForm.submit();
            }
        });
        //构建查询表单结束
	
	}
	PartsStockIndex.init();


	//获取选择的记录
    //参数all = true标示是否要返回所有被选择的记录
	PartsStockIndex.getSelected = function(all){
		var datagrid = $('#easyui-datagrid-parts-stock-index');
        if(all){
            var selectRows = datagrid.datagrid('getSelections');
            if(selectRows.length <= 0){
                $.messager.alert('错误','请选择要操作的记录','error');   
                return false;
            }
            return selectRows;
        }else{
            var selectRow = datagrid.datagrid('getSelected');
            if(!selectRow){
                $.messager.alert('错误','请选择要操作的记录','error');   
                return false;
            }
            return selectRow;
        }
		
	}
     //初始化添加窗口
        $('#easyui-dialog-parts-stock-index-showdetail').dialog({
            title: '库存详情',   
            width: '1100px',   
            height: '400px',   
            closed: true,   
            cache: true,   
            modal: true,
            resizable:true,
            maximizable: true,
            buttons: [],
            onClose: function(){
                $(this).dialog('clear');
            }
        });

    //添加方法
    PartsStockIndex.showdetail = function(){
        $('#easyui-dialog-parts-stock-index-showdetail').dialog('open');
        $('#easyui-dialog-parts-stock-index-showdetail').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['parts/parts-stock/showdetail']); ?>");
    }

    PartsStockIndex.del = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var id = selectRow.id;
        $.messager.confirm('确定删除','您确定要删除该记录？',function(r){
            if(r){
                $.ajax({
                    type: 'get',
                    url: "<?php echo yii::$app->urlManager->createUrl(['parts/parts-stock/del']); ?>",
                    data: {id: id},
                    dataType: 'json',
                    success: function(data){
                        if(data.status){
                            $.messager.alert('删除成功',data.info,'info');   
                            $('#easyui-datagrid-customer-personal-index').datagrid('reload');
                        }else{
                            $.messager.alert('删除失败',data.info,'error');   
                        }
                    }
                });
            }
        });
    }

	//查询
    PartsStockIndex.search = function(){
        var form = $('#search-form-parts-stock-index');
        var data = {};
        var searchCondition = form.serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        $('#easyui-datagrid-parts-stock-index').datagrid('load',data);
    }
    

 	
	//重置查询表单
    PartsStockIndex.resetForm = function(){
        var easyuiForm = $('#search-form-parts-stock-index');
        easyuiForm.form('reset');
         PartsStockIndex.search();
    }

     PartsStockIndex.search = function(){
        var form = $('#search-form-parts-stock-index');
        var data = {};
        var searchCondition = form.serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        $('#easyui-datagrid-parts-stock-index').datagrid('load',data);
    }
</script>
<script>
    //二级联动
    $('#company_feng_stock').combobox({
        onChange: function (n,o) {
            var id = $('#company_feng_stock').combobox('getValue');
            $.ajax({
                async: false,
                url:'<?php echo yii::$app->urlManager->createUrl(['parts/parts-instock/get-site']); ?>',
                type:'post',
                data:{'id':id},
                dataType:'json',
                success:function(data){
//                    $('#parts_kind').combobox('clear');
                    $('#house_name_stock').combobox({
                        valueField:'value',
                        textField:'text',
                        editable: false,
                        panelHeight:'auto',
                        data: data
                    });
                    $('#house_name_stock').combobox('setValues','');
                }
            });
        }
    });
</script>
<script type="text/javascript">
////二级联动
//    $('#parts_type_instock_3').combobox({
//        onChange: function (n,o) {
//            var id = $('#parts_type_instock_3').combobox('getValue');
//            $.ajax({
//                async: false,
//                url:'<?php //echo yii::$app->urlManager->createUrl(['parts/parts-instock/get-kind']); ?>//',
//                type:'post',
//                data:{'id':id},
//                dataType:'json',
//                success:function(data){
////                    $('#parts_kind').combobox('clear');
//                    $('#parts_kind_instock_3').combobox({
//                        valueField:'value',
//                        textField:'text',
//                        editable: false,
//                        panelHeight:'auto',
//                        data: data
//                    });
//                    $('#parts_kind_instock_3').combobox('setValues','');
//                }
//            });
//        }
//    });
    //三级联动
//    $('#s_province_2').combobox({
//        onChange: function (n,o) {
//            var id = $('#s_province_2').combobox('getValue');
//            $.ajax({
//                async: false,
//                url:'<?php //echo yii::$app->urlManager->createUrl(['parts/parts-instock/get-company']); ?>//',
//                type:'post',
//                data:{'id':id},
//                dataType:'json',
//                success:function(data){
//                    $('#s_city_2').combobox({
//                        valueField:'value',
//                        textField:'text',
//                        editable: false,
//                        panelHeight:'auto',
//                        data: data,
//                        onChange:function (n,o) {
//                            var id = $('#s_city_2').combobox('getValue');
//                            $.ajax({
//                                async: false,
//                                url:'<?php //echo yii::$app->urlManager->createUrl(['parts/parts-instock/get-site']); ?>//',
//                                type:'post',
//                                data:{'id':id},
//                                dataType:'json',
//                                success:function(data){
//                                    $('#s_county_2').combobox({
//                                        valueField:'value',
//                                        textField:'text',
//                                        editable: false,
//                                        panelHeight:'auto',
//                                        data: data
//                                    });
//                                    $('#s_county_2').combobox('setValues','');
//                                }
//                            });
//                        }
//                    });
//                    $('#s_city_1').combobox('setValues','');
//                }
//            });
//        }
//    });
</script>