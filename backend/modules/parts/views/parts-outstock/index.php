<table id="easyui-datagrid-parts-outstock-index"></table> 
<div id="easyui-datagrid-parts-outstock-index-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-parts-outstock-index">
                <ul class="search-main">
                    <li>
                        <div class="item-name">大区</div>
                        <div class="item-input">
                           <select
                        class="easyui-combobox"
                        style="width:160px;"
                        id="s_province_1"
                        name="region"
                        editable="true"
                    >
                       <?php foreach($searchFormOptions['region'] as $val){?>
                        <option value="<?php echo $val['id']; ?>"><?php echo $val['name']; ?></option>
                       <?php }?>
                    </select>
                        </div>
                    </li>   
                    <li>
                        <div class="item-name">运营公司</div>
                        <!-- <div class="item-input">
                            <input style="width:200px;" name="operating_company_id" />
                        </div> -->
                        <select class="easyui-combobox" id="s_city_1" name="operating_company_id" style="width:200px;"></select>
                    </li> 
                    <li>
                        <div class="item-name">仓储地点</div>
                        <!-- <div class="item-input">
                            <input class="easyui-textbox" type="text" name="warehouse_address" style="width:100%;"  />
                        </div> -->
                        <select class="easyui-combobox" id="s_county_1" name="warehouse_address" style="width: 150px;"></select>

                    </li>
                 <!--    <li>
                        <div class="item-name">仓储编码</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="order_number" style="width:100%;"  />
                        </div>
                    </li> -->
                    <li>
                        <div class="item-name">车辆品牌</div>
                        <div class="item-input">
                           <input style="width:200px;" name="brand_id" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">配件类别</div>
                        <div class="item-input">
                            <select
                                    class="easyui-combobox"
                                    style="width:150px;"
                                    id="parts_type_instock_1"
                                    name="parts_type"
                                    editable="true"
                                    listHeight="200px"
                            >
                                <?php foreach($searchFormOptions['parts_type'] as $val){?>
                                    <option value="<?php echo $val['id']; ?>"><?php echo $val['name']; ?></option>
                                <?php }?>
                            </select>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">配件种类</div>
                        <div class="item-input">
                            <select
                                    class="easyui-combobox"
                                    style="width:150px;"
                                    id="parts_kind_instock_1"
                                    name="parts_kind"
                                    editable="true"
                                    data-options="panelHeight:'auto'"
                            >
                            </select>
                        </div>
                    </li> 
                    <li>
                        <div class="item-name">配件名称</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="parts_name" style="width:100%;"  />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">配件品牌</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="parts_brand" style="width:100%;"  />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">厂家配件编码</div>
                        <div class="item-input">
                           <input class="easyui-textbox" type="text" name="vender_code" style="width:100%;"  />
                        </div>
                    </li> 
                    <li>
                        <div class="item-name">我方配件编码</div>
                        <div class="item-input">
                           <input class="easyui-textbox" type="text" name="dst_code" style="width:100%;"  />
                        </div>
                    </li> 
                     <li>
                        <div class="item-name">线下出库人</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="use_person" style="width:100%;"  />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">线下出库时间</div>
                        <div class="item-input">
                            <input class="easyui-datebox" type="text" name="start_out_time" style="width:93px;"
                                   data-options="
                                        onChange:function(){
                                            $('#search-form-parts-outstock-index').search();
                                        }
                                   "
                                />
                                -
                                <input class="easyui-datebox" type="text" name="end_out_time" style="width:93px;"
                                   data-options="
                                        onChange:function(){
                                            $('#search-form-parts-outstock-index').search();
                                        }
                                   "
                                />
                        </div>
                    </li>
                                          
                    <li class="search-button">
                        <button type="submit" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button type="submit" onclick="PartsOutstockIndex.resetForm()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
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
<div id="easyui-dialog-parts-outstock-index-outstock"></div>
<div id="easyui-dialog-parts-outstock-outstock-add"></div>
<script>
    var PartsOutstockIndex = new Object();
    PartsOutstockIndex.init = function(){
        $('#easyui-datagrid-parts-outstock-index').datagrid({  
            method: 'get', 
            url:"<?php echo yii::$app->urlManager->createUrl(['parts/parts-outstock/get-list']); ?>",  
            idField: 'id',
            treeField: 'contract_number', 
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-parts-outstock-index-toolbar",
            pagination: true,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
            pageSize: 20,
            frozenColumns: [[
                {field: 'ck',checkbox: true}, 
                {field: 'out_id',title: 'id',hidden: true},
                {field: 'plate_number',title: '车牌号',width: 150,sortable: true,halign: 'center'},   
            ]], 
            columns: [[ 
                     
                {field: 'out_reason',title: '出库原因',width: 150,sortable: true,halign: 'center'},             
                {field: 'use_person',title: '领用人',width: 150,halign: 'center'},
                {field: 'out_time',title: '线下出库时间',width: 150,halign: 'center'},
                {field: 'region_name',title: '大区',width: 93,halign: 'center'
            },
                {field: 'company_name',title: '运营公司',width: 100,halign: 'center'},
                {field: 'warehouse_address',title: '仓储地点',width: 100,halign: 'center'
                    
                },
    
                {field: 'brand_name',title: '车辆品牌',width: 100,halign: 'center'
                    
                
                },
                {field: 'parents_name',title: '配件类别',width: 150,halign: 'center'},
                {field: 'son_name',title: '配件种类',width: 150,halign: 'center'},
                {field: 'parts_name',title: '配件名称',width: 150,halign: 'center'},
                {field: 'parts_brand',title: '配件品牌',width: 150,halign: 'center'},
                {field: 'vender_code',title: '厂家配件编码',width: 150,halign: 'center'},
                {field: 'dst_code',title: '我方配件编码',width: 150,halign: 'center'},
                {field: 'unit',title: '单位',width: 150,halign: 'center'},
                {field: 'main_engine_price',title: '主机厂参考价（元）',width: 150,halign: 'center'},
                {field: 'shop_price',title: '采购单价（元）',width: 150,halign: 'center'},
                {field: 'out_price',title: '出库单价（元）',width: 150,halign: 'center'},
                {field: 'out_number',title: '数量',width: 150,halign: 'center'},
                {field: 'standard',title: '规格',width: 150,halign: 'center'},
                {field: 'parts_model',title: '型号',width: 150,halign: 'center'},
                {field: 'param',title: '参数',width: 150,halign: 'center'},
                {field: 'expiration_date',title: '保质期（月）',width: 150,halign: 'center'},
                {field: 'warranty_date',title: '保修期（月）',width: 150,halign: 'center'},
                {field: 'match_car',title: '适用车型',width: 150,halign: 'center'},
                {field: 'original_from',title: '配件来源',width: 150,halign: 'center'},
                {field: 'original_from_company',title: '配件供应商名称',width: 150,halign: 'center'},
                {field: 'original_from_code',title: '配件供应商编码',width: 150,halign: 'center'},
                {field: 'factory',title: '正副厂',width: 150,halign: 'center'},
                {field: 'product_company',title: '配件生产商名称',width: 150,halign: 'center'},
                {field: 'product_company_code',title: '配件生产商编号',width: 150,halign: 'center'},
                 
            ]],
            onDblClickRow: function(rowIndex,rowData){
                PartsOutstockIndex.edit(rowData.id);
            }
        });

        //构建查询表单
        var searchForm = $('#search-form-parts-outstock-index');
        /**查询表单提交事件**/
        searchForm.submit(function(){
            var data = {};
            var searchCondition = $(this).serializeArray();
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = searchCondition[i]['value'];
            }
            $('#easyui-datagrid-parts-outstock-index').datagrid('load',data);
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

         searchForm.find('input[name=part_type]').combobox({
            valueField:'value',
            textField:'text',
            data: <?= json_encode($searchFormOptions['part_type']); ?>,
            editable: true,
            panelHeight:'auto',
            onSelect: function(){
                searchForm.submit();
            }
        });
         searchForm.find('input[name=part_kind]').combobox({
            valueField:'value',
            textField:'text',
            data: <?= json_encode($searchFormOptions['part_kind']); ?>,
            editable: true,
            panelHeight:'500px',
            onSelect: function(){
                searchForm.submit();
            }
        });
        searchForm.find('input[name=parts_name]').combobox({
            valueField:'value',
            textField:'text',
            data: <?= json_encode($searchFormOptions['parts_name']); ?>,
            editable: true,
            panelHeight:'auto',
            onSelect: function(){
                searchForm.submit();
            }
        });
    
        searchForm.find('input[name=region]').combobox({
            valueField:'id',
            textField:'name',
            data: <?= json_encode($searchFormOptions['region']); ?>,
            editable: true,
            panelHeight:'auto',
            onSelect: function(){
                searchForm.submit();
            }
        });

        searchForm.find('select[name=operating_company_id]').combobox({
            valueField:'value',
            textField:'text',
            data: '',
            editable: true,
            panelHeight:'auto',
            onSelect: function(){
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
    PartsOutstockIndex.init();



    //获取选择的记录
    //参数all = true标示是否要返回所有被选择的记录
    PartsOutstockIndex.getSelected = function(all){
        var datagrid = $('#easyui-datagrid-parts-outstock-index');
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
        $('#easyui-dialog-parts-outstock-index-outstock').dialog({
            title: '出库登记',   
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
    PartsOutstockIndex.outstock = function(){
        $('#easyui-dialog-parts-outstock-index-outstock').dialog('open');
        $('#easyui-dialog-parts-outstock-index-outstock').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['parts/parts-outstock/outstock']); ?>");
    }



    PartsOutstockIndex.del = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var id = selectRow.out_id;
        $.messager.confirm('确定删除','您确定要删除该记录？',function(r){
            if(r){
                $.ajax({
                    type: 'get',
                    url: "<?php echo yii::$app->urlManager->createUrl(['parts/parts-outstock/del']); ?>",
                    data: {id: id},
                    dataType: 'json',
                    success: function(data){
                        if(data.status){
                            $.messager.alert('删除成功',data.info,'info');   
                            $('#easyui-datagrid-parts-outstock-index').datagrid('reload');
                        }else{
                            $.messager.alert('删除失败',data.info,'error');   
                        }
                    }
                });
            }
        });
    }

    


    //重置查询表单
    PartsOutstockIndex.resetForm = function(){
        var easyuiForm = $('#search-form-parts-outstock-index');
        easyuiForm.form('reset');
    PartsOutstockIndex.search();

    }
     //条件搜索查询
    PartsOutstockIndex.search = function(){
        var form = $('#search-form-parts-outstock-index');
        var data = {};
        var searchCondition = form.serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        $('#easyui-datagrid-parts-outstock-index').datagrid('load',data);
    }

</script>
<script type="text/javascript">
//二级联动
    $('#parts_type_instock_1').combobox({
        onChange: function (n,o) {
            var id = $('#parts_type_instock_1').combobox('getValue');
            $.ajax({
                async: false,
                url:'<?php echo yii::$app->urlManager->createUrl(['parts/parts-instock/get-kind']); ?>',
                type:'post',
                data:{'id':id},
                dataType:'json',
                success:function(data){
//                    $('#parts_kind').combobox('clear');
                    $('#parts_kind_instock_1').combobox({
                        valueField:'value',
                        textField:'text',
                        editable: false,
                        panelHeight:'auto',
                        data: data
                    });
                    $('#parts_kind_instock_1').combobox('setValues','');
                }
            });
        }
    });
    //三级联动
    $('#s_province_1').combobox({
        onChange: function (n,o) {
            var id = $('#s_province_1').combobox('getValue');
            $.ajax({
                async: false,
                url:'<?php echo yii::$app->urlManager->createUrl(['parts/parts-instock/get-company']); ?>',
                type:'post',
                data:{'id':id},
                dataType:'json',
                success:function(data){
                    $('#s_city_1').combobox({
                        valueField:'value',
                        textField:'text',
                        editable: false,
                        panelHeight:'auto',
                        data: data,
                        onChange:function (n,o) {
                            var id = $('#s_city_1').combobox('getValue');
                            $.ajax({
                                async: false,
                                url:'<?php echo yii::$app->urlManager->createUrl(['parts/parts-instock/get-site']); ?>',
                                type:'post',
                                data:{'id':id},
                                dataType:'json',
                                success:function(data){
                                    $('#s_county_1').combobox({
                                        valueField:'value',
                                        textField:'text',
                                        editable: false,
                                        panelHeight:'auto',
                                        data: data
                                    });
                                    $('#s_county_1').combobox('setValues','');
                                }
                            });
                        }
                    });
                    $('#s_city_1').combobox('setValues','');
                }
            });
        }
    });
</script>
