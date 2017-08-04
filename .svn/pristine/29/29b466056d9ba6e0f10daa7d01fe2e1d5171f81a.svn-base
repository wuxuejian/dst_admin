<table id="easyui-datagrid-car-type-index"></table> 
<div id="easyui-datagrid-car-type-index-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-car-type-index">
                <ul class="search-main">
                    <li>
                        <div class="item-name">车辆品牌</div>
                        <div class="item-input">
                            <input style="width:200px;" name="brand_id" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">车辆类型</div>
                        <div class="item-input">
                            <input style="width:200px;" name="car_type" />
                        </div>

                    </li>
                    <li class="search-button">
                        <button class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button onclick="CarTypeIndex.resetForm();return false;" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
                    </li>
                </ul>
            </form>
        </div>
    </div>

    <?php if(!empty($buttons)){ ?>
        <div class="easyui-panel" title="数据列表" style="padding:3px 2px;width:100%;" data-options="
        iconCls: 'icon-table-list',
        border: false
    ">
            <?php foreach($buttons as $val){ ?>
                <button onclick="<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon'] ;?>'"><?= $val['text'] ;?></button>
            <?php } ?>
        </div>
    <?php } ?>

</div>

<!-- 窗口 -->
<div id="easyui-datagrid-car-type-index-add"></div>
<div id="easyui-datagrid-car-type-index-scan"></div>
<div id="easyui-datagrid-car-type-index-edit"></div>
<div id="easyui-datagrid-car-type-index-remove"></div>

<script>
    var CarTypeIndex = new Object();
    //配置项
    
    CarTypeIndex.init = function(){
        //获取列表数据
        $('#easyui-datagrid-car-type-index').datagrid({  
            method: 'get', 
            url:"<?php echo yii::$app->urlManager->createUrl(['car/car-type/get-list']); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-car-type-index-toolbar",
            pagination: true,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
            pageSize: 20,
            sortName: 'next_valid_date',
            sortOrder: 'asc',
            frozenColumns: [[
                {field: 'ck',checkbox: true}, 
                {field: 'id',title: 'id',hidden: true},   
                {field: 'c_code',title: '车型编号',width: 100,sortable: true,align: 'center'}
            ]],
            columns:[[
                

                {
                    field: 'brand_name',title: '车辆品牌',width: 120,align: 'center',
                    sortable: true,
                
                },

                /*{
                    field: 'car_type',title: '车辆类型',width: 120,align: 'center',
                    sortable: true,
                    
                },*/
                {
                    field: 'car_type',title: '车辆类型',width: 100,align: 'center',
                    sortable: true,
                    formatter: function(value){
                        var car_type = <?php echo json_encode($config['car_type']); ?>;
                        try{
                            return car_type[value].text;
                        }catch(e){
                            return '';
                        }
                    }
                },
                
               
               /* {
                    field: 'car_model',title: '车辆型号',width: 120,align: 'center',
                    sortable: true,
                
                },*/
                /*{field: '-----查出来',title: '车型名称',width: 120,align: 'center',sortable: true},*/
                {
                    field: 'car_model_name',title: '车型名称',width: 90,align: 'center',
                    sortable: true,
                   /* formatter: function(value){
                        //console.log(value)
                        var car_type = <?php echo json_encode($config['car_model_name']); ?>;
                        //console.log(car_type)
                        try{
                            return car_type[value].text;    
                        }catch(e){
                            return '';
                        }
                    }*/
                },
                {field: 'outside_ckg',title: '长*宽*高(mm)',width: 120,align: 'center',sortable: true},
                {field: 'engine_model',title: '发动机型号',width: 120,align: 'center',sortable: true},
                /*{field: 'fuel_type',title: '燃料形式',width: 120,align: 'center',sortable: true},*/
                {
                    field: 'fuel_type',title: '燃料形式',width: 100,align: 'center',
                    sortable: true,
                    /*formatter: function(value){
                        console.log(value)
                        var car_type = <?php echo json_encode($config['fuel_type']); ?>;
                        //alert('1');
                        //console.log(car_type)
                        try{
                            return car_type[value].text;
                        }catch(e){
                            return '';
                        }
                    }*/
                },
                {field: 'endurance_mileage',title: '工部续航里程(km)',width: 120,align: 'center',sortable: true},
                {field: 'rated_power',title: '驱动电机额定功率(kW)',width: 150,align: 'center',sortable: true},
                {field: 'power_battery_capacity',title: '动力电池容量(kW·h)',width: 130,align: 'center',sortable: true},
                /*{field: 'add_time',title: '添加时间',width: 120,align: 'center',sortable: true},*/
                {
                    field: 'add_time',title: '添加时间',width: 90,align: 'center',
                    sortable: true,
                    formatter: function(value){
                        if(!isNaN(value) && value >0){
                            return formatDateToString(value);
                        }
                    }
                },
                 {field: 'username',title: '操作人',width: 120,align: 'center',sortable: true},
                
            ]],
            onLoadSuccess: function (data){
                $(this).datagrid('doCellTip',{
                    position : 'bottom',
                    maxWidth : '300px',
                    onlyShowInterrupt : true,
                    specialShowFields : [     
                        {field : 'action',showField : 'action'}
                    ],
                    tipStyler : {            
                        'backgroundColor' : '#E4F0FC',
                        borderColor : '#87A9D0',
                        boxShadow : '1px 1px 3px #292929'
                    }
                });
            }
        });

       
        //初始化添加窗口
        $('#easyui-datagrid-car-type-index-add').dialog({
            title: '车型模板添加',   
            width: '880px',   
            height: '600px',   
            closed: true,   
            cache: true,   
            modal: true,
            resizable:true,
            maximizable: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var form = $('#easyui-form-car-type-add');
                    if(!form.form('validate')) return false;
                    var data = form.serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['car/car-type/add']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('添加成功',data.info,'info');
                                $('#easyui-datagrid-car-type-index-add').dialog('close');
                                $('#easyui-datagrid-car-type-index').datagrid('reload');
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
                    $('#easyui-datagrid-car-type-index-add').dialog('close');
                }
            }],
            onClose: function(){
                $(this).dialog('clear');
            }
        });

    //添加方法
    CarTypeIndex.add = function(){
        $('#easyui-datagrid-car-type-index-add').dialog('open');
        $('#easyui-datagrid-car-type-index-add').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/car-type/add']); ?>");
    }


    //初始化查询窗口
        $('#easyui-datagrid-car-type-index-scan').window({
            title: '车型模板详情',
            width: '45%',   
            height: '83%',   
            closed: true,   
            cache: true,   
            modal: true,
            collapsible: false,
            minimizable: false, 
            maximizable: true,
            onClose: function(){
                $(this).window('clear');
            }       
        });

     CarTypeIndex.getSelected = function(all){
        var datagrid = $('#easyui-datagrid-car-type-index');
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

    //查看
    CarTypeIndex.scan = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var id = selectRow.id;
        $('#easyui-datagrid-car-type-index-scan').window('open');
        $('#easyui-datagrid-car-type-index-scan').window('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/car-type/scan']); ?>&id="+id);
    }


    //初始化修改窗口
        $('#easyui-datagrid-car-type-index-edit').dialog({
            title: '修改车辆模板',   
            width: '880px',   
            height: '600px',   
            closed: true,   
            cache: true,   
            modal: true,
            resizable:true,
            maximizable: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var form = $('#easyui-form-car-type-edit');
                    if(!form.form('validate')) return false;
                    var data = form.serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['car/car-type/edit']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('修改成功',data.info,'info');
                                $('#easyui-datagrid-car-type-index-edit').dialog('close');
                                $('#easyui-datagrid-car-type-index').datagrid('reload');
                            }else{
                                $.messager.alert('修改失败',data.info,'error');
                            }
                        }
                    });
                }
            },{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui-datagrid-car-type-index-edit').dialog('close');
                }
            }],
            onClose: function(){
                $(this).dialog('clear');
            }
        });

    //修改
    CarTypeIndex.edit = function(id){
        console.log(id);
        if(!id){
            var selectRow = this.getSelected();
            if(!selectRow){
                return false;
            }
            id = selectRow.id;
        }
        $('#easyui-datagrid-car-type-index-edit').dialog('open');
        $('#easyui-datagrid-car-type-index-edit').dialog('refresh','<?php echo yii::$app->urlManager->createUrl(['car/car-type/edit']); ?>&id='+id);
    }


    //删除车辆
    CarTypeIndex.remove = function(){
        var selectRow = this.getSelected();
        if(!selectRow) return false;
        var id = selectRow.id;
        $.messager.confirm('确定删除','您确定要删除该数据吗？',function(r){
            if(r){
                $.ajax({
                    type: 'get',
                    url: '<?php echo yii::$app->urlManager->createUrl(['car/car-type/remove']); ?>',
                    data: {id: id},
                    dataType: 'json',
                    success: function(data){
                        if(data.status){
                            $.messager.alert('删除成功',data.info,'info');
                            $('#easyui-datagrid-car-type-index').datagrid('reload');
                        }else{
                            $.messager.alert('删除失败',data.info,'error');   
                        }
                    }
                });
            }
        });
    }

 
    //构建查询表单
        var searchForm = $('#search-form-car-type-index');
        /**查询表单提交事件**/
        searchForm.submit(function(){
            var data = {};
            var searchCondition = $(this).serializeArray();
            //console.log(searchCondition)
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = searchCondition[i]['value'];
            }
            $('#easyui-datagrid-car-type-index').datagrid('load',data);
            return false;
        });
        searchForm.find('input[name=brand_id]').combotree({
            url: "<?php echo yii::$app->urlManager->createUrl(['car/combotree/get-car-brands']); ?>",
            editable: false,
            panelHeight:'auto',
            lines:false,
            onChange: function(o){
                searchForm.submit();
            }
        });
        searchForm.find('input[name=car_type]').combobox({
            valueField:'value',
            textField:'text',
            data: <?= json_encode($searchFormOptions['car_type']); ?>,
            editable: false,
            panelHeight:'auto',
            onSelect: function(){
                searchForm.submit();
            }
        });
        /*searchForm.find('input[name=plate_number]').textbox({
            onChange: function(){
                searchForm.submit();
            }
        });
         searchForm.find('input[name=username]').textbox({
            onChange: function(){
                searchForm.submit();
            }
        });
        

        searchForm.find('input[name=status]').combobox({
            valueField:'value',
            textField:'text',
            editable: false,
            panelHeight:'auto',
            data: [{"value": '',"text": '不限'},{"value": 'available',"text": '可用'},{"value": 'out_car',"text": '出车'},{"value": 'repair',"text": '维修'}],
            onSelect: function(){
                searchForm.submit();
            }
        });
        searchForm.find('input[name=car_type]').combobox({
            valueField:'value',
            textField:'text',
            editable: false,
            panelHeight:'auto',
            data: [{"value": '',"text": '不限'},{"value": 1,"text": '自用'},{"value": 2,"text": '备用'}],
            onSelect: function(){
                searchForm.submit();
            }
        });
*/


    }
    

    //按条件导出车辆列表
 /*   CarTypeIndex.export = function(){
        var url = "<?php echo yii::$app->urlManager->createUrl(['car/office-car-register/export-width-condition']);?>";
        var form = $('#search-form-car-type-index');
        var data = {};
        var searchCondition = form.serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        for(var i in data){
            url += '&'+i+'='+data[i];
        }
        window.open(url);
    }*/

   
    //重置查询表单
    CarTypeIndex.resetForm = function(){
        var easyuiForm = $('#search-form-car-type-index');
        easyuiForm.form('reset');
        easyuiForm.submit();
    }



    CarTypeIndex.init();
</script>