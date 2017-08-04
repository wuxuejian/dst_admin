<table id="easyui-datagrid-parts-parts-outstock-outstock"></table>
<div id="easyui-datagrid-parts-parts-outstock-outstock-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">
        <div class="data-search-form">
            <form id="search-form-parts-outstock-outstock">
                <ul class="search-main">
                    <li>
                        <div class="item-name">车辆品牌</div>
                        <div class="item-input">
                            <input class="easyui-textbox" name="brand_id" style="width:150px;"></input>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">配件类别</div>
                        <div class="item-input">
                            <select
                                    class="easyui-combobox"
                                    style="width:150px;"
                                    id="parts_type_instock_2"
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
                                    id="parts_kind_instock_2"
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
                            <input class="easyui-textbox" name="parts_name" style="width:150px;"></input>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">配件品牌</div>
                        <div class="item-input">
                            <input class="easyui-textbox" name="parts_brand" style="width:150px;"></input>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">厂家配件编码</div>
                        <div class="item-input">
                            <input class="easyui-textbox" name="vender_code" style="width:150px;"></input>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">我方配件编码</div>
                        <div class="item-input">
                            <input class="easyui-textbox" name="dst_code" style="width:150px;"></input>
                        </div>
                    </li>
                    <li class="search-button">
                        <button type="submit" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button type="submit" onclick="PartsOutstockOutstock.resetForm()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
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
    var PartsOutstockOutstock = new Object();

 PartsOutstockOutstock.init = function(){
        //获取列表数据
        $('#easyui-datagrid-parts-parts-outstock-outstock').datagrid({
            method: 'get',
            url:'<?php echo yii::$app->urlManager->createUrl(['parts/parts-outstock/get-outstock-list']); ?>',
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-parts-parts-outstock-outstock-toolbar",
            pagination: true,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
            pageSize: 20,
            frozenColumns: [[
                {field: 'ck',checkbox: true}, 
                {field: 'insert_id',title: 'id',hidden: true}
                   
            ]], 
            columns:[[
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
                {field: 'storage_quantity',title: '数量',width: 150,halign: 'center'},
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
                {field: 'under_in_warehouse_time',title: '线下入库时间',width: 150,halign: 'center'}, 
            ]],
            onDblClickRow: function(rowIndex,rowData){
                PartsOutstockIndex.edit(rowData.insert_id);
            }
        });

         var searchForm = $('#search-form-parts-outstock-outstock');
        /**查询表单提交事件**/
        searchForm.submit(function(){
            var data = {};
            var searchCondition = $(this).serializeArray();
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = searchCondition[i]['value'];
            }
            $('#easyui-datagrid-parts-parts-outstock-outstock').datagrid('load',data);
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

        
    }
    //添加方法
PartsOutstockOutstock.init();
    //添加方法
     //参数all = true标示是否要返回所有被选择的记录
    PartsOutstockOutstock.getSelected = function(all){
        var treegrid = $('#easyui-datagrid-parts-parts-outstock-outstock');
        if(all){
            var selectRows = treegrid.treegrid('getSelections');
            if(selectRows.length <= 0){
                $.messager.alert('错误','请选择要操作的记录','error');   
                return false;
            }
            return selectRows;
        }else{
            var selectRow = treegrid.treegrid('getSelected');
            if(!selectRow){
                $.messager.alert('错误','请选择要操作的记录','error');   
                return false;
            }
            return selectRow;
        }
        
    }
        
//初始化添加窗口
        $('#easyui-dialog-parts-outstock-outstock-add').dialog({
            title: '出库保存',   
            width: '1100px',   
            height: '400px',   
            closed: true,   
            cache: true,   
            modal: true,
            resizable:true,
            maximizable: true,
           buttons: [{
                text:'提交',
                iconCls:'icon-ok',
                handler:function(){
                    var form = $('#easyui-form-addstock');
                    if(!form.form('validate')) return false;
                    var data = form.serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['parts/parts-outstock/save-stock']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('添加成功',data.info,'info');
                                $('#easyui-dialog-parts-outstock-outstock-add').dialog('close');
                                $('#easyui-datagrid-parts-outstock-index').datagrid('reload');
                                $('#easyui-datagrid-parts-parts-outstock-outstock').datagrid('reload');
                            }else{
                                $.messager.alert('添加失败',data.info,'error');
                            }
                        }
                    });
                }
            },{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui-dialog-parts-outstock-outstock-add').dialog('close');
                }
            }],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
        

        
        PartsOutstockOutstock.add = function(){
            var selectRow = this.getSelected();
            if(!selectRow){
                return false;
            }
            var id = selectRow.insert_id;
        $('#easyui-dialog-parts-outstock-outstock-add').dialog('open');
        $('#easyui-dialog-parts-outstock-outstock-add').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['parts/parts-outstock/add-stock']); ?>&id="+id);
       
          } 

          //重置查询表单
    PartsOutstockOutstock.resetForm = function(){
        var easyuiForm = $('#search-form-parts-outstock-outstock');
        easyuiForm.form('reset');
    }  
</script>
<script type="text/javascript">
    //二级联动
    $('#parts_type_instock_2').combobox({
        onChange: function (n,o) {
            var id = $('#parts_type_instock_2').combobox('getValue');
            $.ajax({
                async: false,
                url:'<?php echo yii::$app->urlManager->createUrl(['parts/parts-instock/get-kind']); ?>',
                type:'post',
                data:{'id':id},
                dataType:'json',
                success:function(data){
//                    $('#parts_kind').combobox('clear');
                    $('#parts_kind_instock_2').combobox({
                        valueField:'value',
                        textField:'text',
                        editable: false,
                        panelHeight:'auto',
                        data: data
                    });
                    $('#parts_kind_instock_2').combobox('setValues','');
                }
            });
        }
    });
</script>