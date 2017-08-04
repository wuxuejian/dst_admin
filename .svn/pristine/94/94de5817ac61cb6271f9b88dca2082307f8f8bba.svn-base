<table id="easyui-datagrid-finance-rent-rel-car"></table> 
<div id="easyui-datagrid-finance-rent-rel-car-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-finance-rent-rel-car">
                <ul class="search-main">
                    <li>
                        <div class="item-name">车牌号</div>
                        <div class="item-input">
                            <input name="plate_number" style="width:200px;"></input>
                        </div>
                    </li>
                     <li>
                        <div class="item-name">车辆品牌</div>
                        <div class="item-input">
                            <input name="car_brand" style="width:200px;"></input>
                        </div>
                    </li>
                    <li class="search-button">
                        <button class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button onclick="CarFinanceRentRelCar.resetForm()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
                    </li>
                </ul>
            </form>
        </div>
    </div>
    <?php if($buttons){ ?>
    <div class="easyui-panel" title="数据列表" style="padding:8px 4px;" data-options="
        iconCls: 'icon-tip',
        border: false
    ">
        <?php foreach($buttons as $val){ ?>
        <a href="javascript:<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon']; ?>'"><?= $val['text']; ?></a>
        <?php } ?>
    </div>
    <?php } ?>
</div>
<!-- 窗口 -->
<div id="easyui-dialog-finance-rent-rel-car-add"></div>
<!-- <div id="easyui-dialog-car-baseinfo-driving-license-edit"></div> -->
<!-- 窗口 -->
<script>
    var CarFinanceRentRelCar = new Object();
    CarFinanceRentRelCar.init = function(){
        //获取列表数据
        $('#easyui-datagrid-finance-rent-rel-car').datagrid({  
            method: 'get', 
            url:"<?php echo yii::$app->urlManager->createUrl(['car/finance-rent/get-rel-list','id'=>$id]); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-finance-rent-rel-car-toolbar",
            pagination: true,
            loadMsg: '数据加载中...',
            width: '955px',   
            height: '550px', 
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: false,
            frozenColumns: [[
                {field: 'ck',checkbox: true}, 
                {field: 'id',title: 'id',hidden: true},   
                
            ]],
            columns:[[
                {field: 'plate_number',title: '车牌号',width: 100,align: 'center',sortable: true},
                {field: 'car_brand',title: '车辆品牌',width: 100,align: 'center',sortable: true},
                {field: 'add_time',title: '添加时间',width: 100,align: 'center',sortable: true,
                    formatter: function(value){
                        if(!isNaN(value) && value > 0){
                            return formatDateToString(value);
                        } else {
                            return "-";
                        }
                    }
                },
                {field: 'add_name',title: '操作人',width: 100,align: 'center',sortable: true},

            ]],
          
            onDblClickRow: function(rowIndex,rowData){
                CarFinanceRentRelCar.edit(rowData.id);
            }
        });
    }
        //构建查询表单
        var searchFrom = $('#search-form-finance-rent-rel-car');
        searchFrom.submit(function(){
            var data = {};
            var searchCondition = $(this).serializeArray();
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = searchCondition[i]['value'];
            }
            $('#easyui-datagrid-finance-rent-rel-car').datagrid('load',data);
            return false;
        });
        searchFrom.find('input[name=plate_number]').textbox({
            onChange: function(){
               
                $('#search-form-finance-rent-rel-car').submit();
            }
        });
        searchFrom.find('input[name=car_brand]').textbox({
            onChange: function(){
               
                $('#search-form-finance-rent-rel-car').submit();
            }
        });
   
        //初始化添加窗口
        $('#easyui-dialog-finance-rent-rel-car-add').dialog({
            title: '批量添加车辆',   
            width: '400px',   
            height: '450px',   
            closed: true,   
            cache: true,   
            modal: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var form = $('#easyui-form-finance-rent-add-rel-car');
                    if(!form.form('validate')) return false;
                    var data = form.serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['car/finance-rent/add-rel-car']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('添加成功',data.info,'info');
                                $('#easyui-dialog-finance-rent-rel-car-add').dialog('close');
                                $('#easyui-datagrid-finance-rent-rel-car').datagrid('reload');
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
                    $('#easyui-dialog-finance-rent-rel-car-add').dialog('close');
                }
            }]
        });
        //初始化修改窗口
        /*$('#easyui-dialog-car-baseinfo-driving-license-edit').dialog({
            title: '修改行驶证管理',   
            width: '715px',   
            height: '350px',   
            closed: true,   
            cache: true,   
            modal: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var form = $('#easyui-form-car-baseinfo-driving-license-edit');
                    if(!form.form('validate')) return false;
                    var data = form.serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/edit-driving-license']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('修改成功',data.info,'info');
                                $('#easyui-dialog-car-baseinfo-driving-license-edit').dialog('close');
                                $('#easyui-datagrid-finance-rent-rel-car').datagrid('reload');
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
                    $('#easyui-dialog-car-baseinfo-driving-license-edit').dialog('close');
                }
            }]  
        });
    }*/
    CarFinanceRentRelCar.init();
    //获取选择的记录
    CarFinanceRentRelCar.getSelected = function(){
        var datagrid = $('#easyui-datagrid-finance-rent-rel-car');
        var selectRow = datagrid.datagrid('getSelected');
        //console.log(selectRow)
        if(!selectRow){
            $.messager.alert('错误','请选择要操作的记录','error');   
            return false;
        }
        return selectRow;
    }
    //添加
    CarFinanceRentRelCar.add = function(){
        
        //console.log(id)
        //alert(123);
        $('#easyui-dialog-finance-rent-rel-car-add').dialog('open');
        $('#easyui-dialog-finance-rent-rel-car-add').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/finance-rent/add-rel-car','id'=>$id]); ?>");
        
    
    }
    /*//修改 
    CarFinanceRentRelCar.edit = function(id){
        if(!id){
            var selectRow = this.getSelected();
            if(!selectRow){
                return false;
            }
            id = selectRow.id;
        }
        $('#easyui-dialog-car-baseinfo-driving-license-edit').dialog('open');
        $('#easyui-dialog-car-baseinfo-driving-license-edit').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/edit-driving-license']); ?>&carId="+id);
    }*/
    //删除
    CarFinanceRentRelCar.remove = function(){
        var selectRow = this.getSelected();
        if(!selectRow) return false;
        var id = selectRow.id;
        $.messager.confirm('确定删除','您确定要删除该数据？',function(r){
            if(r){
                $.ajax({
                    type: 'get',
                    url: "<?php echo yii::$app->urlManager->createUrl(['car/finance-rent/remove']); ?>",
                    data: {id: id},
                    dataType: 'json',
                    success: function(data){
                        if(data){
                            $.messager.alert('删除成功',data.info,'info');   
                            $('#easyui-datagrid-finance-rent-rel-car').datagrid('reload');
                        }else{
                            $.messager.alert('删除失败',data.info,'error');   
                        }
                    }
                });
            }
        });
    }
    //查询
    CarFinanceRentRelCar.search = function(){
        var form = $('#search-form-finance-rent-rel-car');
        var data = {};
        var searchCondition = form.serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        $('#easyui-datagrid-finance-rent-rel-car').datagrid('load',data);
    }
    //查询
    CarFinanceRentRelCar.resetForm = function(){
        $('#search-form-finance-rent-rel-car').form('reset');
    }
</script>