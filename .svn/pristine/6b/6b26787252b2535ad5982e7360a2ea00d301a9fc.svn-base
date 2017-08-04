<table id="easyui-datagrid-car-baseinfo-traffic-compulsory-insurance"></table> 
<div id="easyui-datagrid-car-baseinfo-traffic-compulsory-insurance-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-car-baseinfo-traffic-compulsory-insurance">
                <ul class="search-main">
                    <li>
                        <div class="item-name">保险公司</div>
                        <div class="item-input">
                            <input name="insurer_company" style="width:200px">
                        </div>
                    </li>
                    <li class="search-button">
                        <button class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button onclick="CarBaseinfoTrafficCompulsoryInsurance.resetForm();return false;" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
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
<div id="easyui-dialog-car-baseinfo-tci-add"></div>
<div id="easyui-dialog-car-baseinfo-tci-edit"></div>
<!-- 窗口 -->
<script>
    var CarBaseinfoTrafficCompulsoryInsurance = new Object();
    CarBaseinfoTrafficCompulsoryInsurance.init = function(){
        var easyuiDatagrid = $('#easyui-datagrid-car-baseinfo-traffic-compulsory-insurance');
        //获取列表数据
        easyuiDatagrid.datagrid({  
            method: 'get', 
            url:"<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/tci-get-list','carId'=>$carId]); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-car-baseinfo-traffic-compulsory-insurance-toolbar",
            pagination: true,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
            frozenColumns: [[
                {field: 'ck',checkbox: true}, 
                {field: 'id',title: 'id',hidden: true},   
                {
                    field: 'insurer_company',title: '保险公司',width: 200,
                    sortable: true,
                    formatter: function(value){
                        var insurer_company = <?php echo json_encode($config['INSURANCE_COMPANY']); ?>;
                        if(insurer_company[value]){
                            return insurer_company[value].text;
                        }
                    }
                }
            ]],
            columns:[[
                {field: 'money_amount',title: '保险金额',width: 80,align: 'right',sortable: true},
                {
                    field: 'start_date',title: '开始时间',width: 80,
                    align: 'center',sortable: true,
                    formatter: function(value){
                        if(!isNaN(value) && value > 0){
                            return formatDateToString(value);
                        }
                    }
                },
                {
                    field: 'end_date',title: '结束时间',width: 80,align: 'center',
                    sortable: true,
                    formatter: function(value){
                        if(!isNaN(value) && value > 0){
                            return formatDateToString(value);
                        }
                    }
                },
                {
                    field: 'add_datetime',title: '上次修改时间',width: 130,
                    align: 'center',sortable: true,
                    formatter: function(value){
                        if(!isNaN(value) && value > 0){
                            return formatDateToString(value,true);
                        }
                    }
                },
                {field: 'username',title: '操作人员',width: 100,align: 'left',sortable: true}
            ]],
            onDblClickRow: function(rowIndex,rowData){
                CarBaseinfoTrafficCompulsoryInsurance.edit(rowData.id);
            },
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
        //构建查询表单
        var searchForm = $('#search-form-car-baseinfo-traffic-compulsory-insurance');
        searchForm.submit(function(){
            var data = {};
            var searchCondition = $(this).serializeArray();
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = searchCondition[i]['value'];
            }
            easyuiDatagrid.datagrid('load',data);
            return false;
        });
        searchForm.find('input[name=insurer_company]').combobox({
            valueField:'value',
            textField:'text',
            data: <?= json_encode($insurerCompany); ?>,
            editable: false,
            onChange: function(){
                searchForm.submit();
            }
        });
        //构建查询表单结束
        //初始化添加窗口
        $('#easyui-dialog-car-baseinfo-tci-add').dialog({
            title: '添加交通强制保险记录',   
            width: '615px',   
            height: '200px',   
            closed: true,   
            cache: true,   
            modal: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var form = $('#easyui-form-car-baseinfo-tci-add');
                    if(!form.form('validate')) return false;
                    var data = form.serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/tci-add']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('添加成功',data.info,'info');
                                $('#easyui-dialog-car-baseinfo-tci-add').dialog('close');
                                $('#easyui-datagrid-car-baseinfo-traffic-compulsory-insurance').datagrid('reload');
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
                    $('#easyui-dialog-car-baseinfo-tci-add').dialog('close');
                }
            }],
            onClose: function(){
                $(this).dialog('clear');
            } 
        });
        //初始化修改窗口
        $('#easyui-dialog-car-baseinfo-tci-edit').dialog({
            title: '修改交通强制保险记录',   
            width: '615px',   
            height: '200px',   
            closed: true,   
            cache: true,   
            modal: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var form = $('#easyui-form-car-baseinfo-tci-edit');
                    if(!form.form('validate')) return false;
                    var data = form.serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/tci-edit']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('修改成功',data.info,'info');
                                $('#easyui-dialog-car-baseinfo-tci-edit').dialog('close');
                                $('#easyui-datagrid-car-baseinfo-traffic-compulsory-insurance').datagrid('reload');
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
                    $('#easyui-dialog-car-baseinfo-tci-edit').dialog('close');
                }
            }],
            onClose: function(){
                $(this).dialog('clear');
            } 
        });
    }
    CarBaseinfoTrafficCompulsoryInsurance.init();
    //获取选择的记录
    CarBaseinfoTrafficCompulsoryInsurance.getSelected = function(){
        var datagrid = $('#easyui-datagrid-car-baseinfo-traffic-compulsory-insurance');
        var selectRow = datagrid.datagrid('getSelected');
        if(!selectRow){
            $.messager.alert('错误','请选择要操作的记录','error');   
            return false;
        }
        return selectRow;
    }
    //添加
    CarBaseinfoTrafficCompulsoryInsurance.add = function(){
        $('#easyui-dialog-car-baseinfo-tci-add').dialog('open');
        $('#easyui-dialog-car-baseinfo-tci-add').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/tci-add','carId'=>$carId]); ?>");
    }
    //修改
    CarBaseinfoTrafficCompulsoryInsurance.edit = function(id){
        if(!id){
            var selectRow = this.getSelected();
            if(!selectRow){
                return false;
            }
            id = selectRow.id;
        }
        $('#easyui-dialog-car-baseinfo-tci-edit').dialog('open');
        $('#easyui-dialog-car-baseinfo-tci-edit').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/tci-edit']); ?>&id="+id);
    }
    //删除
    CarBaseinfoTrafficCompulsoryInsurance.remove = function(){
        var selectRow = this.getSelected();
        if(!selectRow) return false;
        var id = selectRow.id;
        $.messager.confirm('确定删除','您确定要删除该条交通强制保险记录？',function(r){
            if(r){
                $.ajax({
                    type: 'get',
                    url: "<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/tci-remove']); ?>",
                    data: {id: id},
                    dataType: 'json',
                    success: function(data){
                        if(data){
                            $.messager.alert('删除成功',data.info,'info');   
                            $('#easyui-datagrid-car-baseinfo-traffic-compulsory-insurance').datagrid('reload');
                        }else{
                            $.messager.alert('删除失败',data.info,'error');   
                        }
                    }
                });
            }
        });
    }
    //重置查询表单
    CarBaseinfoTrafficCompulsoryInsurance.resetForm = function(){
        var easyuiForm = $('#search-form-car-baseinfo-traffic-compulsory-insurance');
        easyuiForm.form('reset');
        easyuiForm.submit();
    }
</script>