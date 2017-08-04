<table id="easyui-datagrid-parts-parts-stock-showdetail"></table>
<script>
    var PartsOutstockOutstock = new Object();

 PartsOutstockOutstock.init = function(){
        //获取列表数据
        $('#easyui-datagrid-parts-parts-stock-showdetail').datagrid({
            method: 'get',
            url:'<?php echo yii::$app->urlManager->createUrl(['parts/parts-stock/get-stock-list']); ?>',
            fit: true,
            border: false,
       
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
         
     
            columns:[[
                {field: 'region',title: '大区',width: 93,halign: 'center'
            },
                {field: 'company_name',title: '运营公司',width: 100,halign: 'center'},
                {field: 'warehouse_address',title: '仓储地点',width: 100,halign: 'center'
                    
                },
    
                {field: 'brand_name',title: '车辆品牌',width: 100,halign: 'center'
                    
                
                },
                {field: 'parts_type',title: '配件类别',width: 150,halign: 'center'},
                {field: 'parts_kind',title: '配件种类',width: 150,halign: 'center'},
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
                {field: 'under_in_warehouse_time',title: '线下入库时间',width: 150,halign: 'center'},
                {field: 'on_registrant',title: '线上登记人',width: 150,halign: 'center'},
                {field: 'on_registrant_date',title: '线上登记时间',width: 150,halign: 'center'}, 
            ]],
            onDblClickRow: function(rowIndex,rowData){
                PartsOutstockIndex.edit(rowData.id);
            }
        });
        
       console.log("22");
    }
    //添加方法
PartsOutstockOutstock.init();
    //添加方法
        
//初始化添加窗口
        $('#easyui-dialog-parts-outstock-outstock-add').dialog({
            title: '出库',
            width: 1200,
            height: 600,
            cache: true,
            modal: true,
            closed: true,
           buttons: [{
                text:'提交',
                iconCls:'icon-ok',
                handler:function(){
                    var form = $('#easyui-form-start');
                    if(!form.form('validate')) return false;
                    var data = form.serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['purchase/purchase-order/start']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('添加成功',data.info,'info');
                                $('#easyui-datagrid-parts-parts-outstock-outstock').dialog('close');
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
                    $('#easyui-datagrid-parts-parts-outstock-outstock').dialog('close');
                }
            }],
            onClose: function(){
                $(this).dialog('clear');
            }
        });

        
        PartsOutstockOutstock.add = function(){
            console.log("hi");
        $('#easyui-dialog-parts-outstock-outstock-add').dialog('open');
       
          }   
</script>