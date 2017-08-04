<table id="personalContractIndex_carManageWin_datagrid"></table> 
<div id="personalContractIndex_carManageWin_datagridToolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="personalContractIndex_carManageWin_searchForm">
                <ul class="search-main">
                    <li>
                        <div class="item-name">车牌号</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="plate_number" style="width:150px;" />
                        </div>
                    </li>
                    <li class="search-button">
                        <a href="javascript:personalContractIndex_carManageWin.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                    </li>
                </ul>
            </form>
        </div>
    </div>
    <?php if($buttons){ ?>
    <div class="easyui-panel" title="数据列表" style="padding:3px 2px;width:100%;" data-options="
        iconCls: 'icon-tip',
        border: false
    ">
        <?php foreach($buttons as $val){ ?>
        <a href="javascript:<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon']; ?>'"><?= $val['text']; ?></a>
        <?php } ?>
    </div>
    <?php } ?>
</div>
<form id="personalContractIndex_carManageWin_submitDataForm" style="display:none"></form>
<script>
    var personalContractIndex_carManageWin = new Object();
    personalContractIndex_carManageWin.init = function(){
        //获取列表数据
        $('#personalContractIndex_carManageWin_datagrid').datagrid({  
            method: 'get', 
            url:"<?php echo yii::$app->urlManager->createUrl(['customer/personal-contract/get-car-list','contractId'=>$contractInfo['id']]); ?>",   
            fit: true,
            border: false,
            toolbar: "#personalContractIndex_carManageWin_datagridToolbar",
            pagination: true,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: false,
            showFooter: true,
            frozenColumns: [[
                {field: 'ck',checkbox: true}, 
                {field: 'id',title: 'id',hidden: true}
            ]],
            columns:[[
                {
                    field: 'plate_number',title: '车牌号',width: 100,sortable: true,
                    editor:{
                        type:'combobox',
                        options:{
                            valueField:'plate_number',
                            textField:'plate_number',
                            data: <?php echo json_encode($stockCars); ?>,
                            required: true
                        }
                    }
                },
                {
                    field: 'month_rent',title: '月租金',width: 100, align: 'right',
                    sortable: true,
                    editor:{
                        type:'textbox',
                        options:{
                            validType: 'money'
                        }
                    }
                },
                {
                    field: 'let_time',title: '起租时间',width: 125, align: 'center',
                    sortable: true,formatter: function(value){
                        if(!isNaN(value) && value != 0){
                            return formatDateToString(value);
                        }
                    },
                    editor:{
                        type:'datebox',
                        options:{
                            required: true,
                            validType: 'date'
                        }
                    }
                },
                {
                    field: 'back_time',title: '还车时间',width: 125, align: 'center',
                    sortable: true,formatter: function(value){
                        if(!isNaN(value) && value != 0){
                            return formatDateToString(value);
                        }
                    }
                },
                {
                    field: 'note',title: '备注',width: 300,align: 'left',
                    editor:{
                        type:'textbox',
                        options:{
                            validType: 'length[255]'
                        }
                    }
                }
            ]]
        });
    }
    //获取选择的记录
    personalContractIndex_carManageWin.getSelected = function(multiple){
        var datagrid = $('#personalContractIndex_carManageWin_datagrid');
        if(multiple){
            selectRows = datagrid.datagrid('getSelections');
            if(selectRows.length <= 0){
                $.messager.alert('错误','请选择要操作的记录！','error');   
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
    //添加签约车辆
    personalContractIndex_carManageWin.carAdd = function(){
        var datagrid = $('#personalContractIndex_carManageWin_datagrid');
        datagrid.datagrid('appendRow',{       
            id: '0',
            plate_number: '',
            let_time: '',
            back_time: '',
            note: ''
        });
        var rows = datagrid.datagrid('getRows');
        var lastRowNum = rows.length - 1;
        var lastRow = rows[lastRowNum];
        var rowIndex = datagrid.datagrid('getRowIndex',lastRow);
        datagrid.datagrid('beginEdit',rowIndex);
        datagrid.datagrid('selectRow',rowIndex);
    }
    //修改签约车辆
    personalContractIndex_carManageWin.carEdit = function(){
        var selectRows = this.getSelected(true);
        if(!selectRows) return false;
        var datagrid = $('#personalContractIndex_carManageWin_datagrid');
        for(var i in selectRows){
            var rowIndex = datagrid.datagrid('getRowIndex',selectRows[i]);
            datagrid.datagrid('beginEdit',rowIndex);//开启行编辑
            if(selectRows[i].id){
                //原记录的车辆号不允许修改
                var editor = datagrid.datagrid('getEditor',{"index": rowIndex,"field": "plate_number"});
                editor.target.combobox('disable');
            }
        }
    }
    //保存修改
    personalContractIndex_carManageWin.saveEdit = function()
    {
        var selectRows = this.getSelected(true);
        if(!selectRows) return false;
        var datagrid = $('#personalContractIndex_carManageWin_datagrid');
        
        for(var i in selectRows){
            datagrid.datagrid('endEdit',datagrid.datagrid('getRowIndex',selectRows[i]));
        }
        var selectRows = this.getSelected(true);
        var html = '<input type="text" name="contract_id" value="<?php echo $contractInfo["id"]; ?>" />';
        html += '<input type="text" name="pCustomer_id" value="<?php echo $contractInfo["pCustomer_id"]; ?>" />';
        for(var i in selectRows){
            if(selectRows[i].plate_number){
                html += '<input type="text" name="id[]" value="'+selectRows[i].id+'" />';
                html += '<input type="text" name="plate_number[]" value="'+selectRows[i].plate_number+'" />';
                html += '<input type="text" name="month_rent[]" value="'+selectRows[i].month_rent+'" />';
                if(!isNaN(selectRows[i].let_time)){
                    html += '<input type="text" name="let_time[]" value="'+formatDateToString(selectRows[i].let_time)+'" />';
                }else{
                    html += '<input type="text" name="let_time[]" value="'+selectRows[i].let_time+'" />';
                }
                
                html += '<input type="text" name="note[]" value="'+selectRows[i].note+'" />';
            }
        }
        var form = $('#personalContractIndex_carManageWin_submitDataForm');
        form.html(html);
        var data = form.serialize();
        $.ajax({
            type: 'post',
            url: "<?php echo yii::$app->urlManager->createUrl(['customer/personal-contract/add-edit-car']); ?>",
            data: data,
            dataType: 'json',
            success: function(data){
                if(data.status){
                    $.messager.alert('操作成功',data.info,'info');
                    datagrid.datagrid('reload');
                }else{
                    $.messager.alert('操作失败',data.info,'error');
                }
            }
        });
        
    }
    //归还车辆
    personalContractIndex_carManageWin.backCar = function(){
        var selectRows = this.getSelected(true);
        if(!selectRows) return false;
        var id = '';
        for(var i in selectRows){
            id += selectRows[i].id + ',';
        }
        $.messager.confirm('操作确认','您确定要归还所选车辆？',function(r){
            if(r){
                $.ajax({
                    type: 'post',
                    url: "<?php echo yii::$app->urlManager->createUrl(['customer/personal-contract/car-back']); ?>",
                    data: {"id": id},
                    dataType: 'json',
                    success: function(data){
                        if(data.status){
                            $.messager.alert('归还成功',data.info,'info');
                            $('#personalContractIndex_carManageWin_datagrid').datagrid('reload');
                        }else{
                            $.messager.alert('归还失败',data.info,'error');
                        }
                    }
                });
            }
        });
    }
    //查询
    personalContractIndex_carManageWin.search = function(){
        var form = $('#personalContractIndex_carManageWin_searchForm');
        var data = {};
        var searchCondition = form.serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        $('#personalContractIndex_carManageWin_datagrid').datagrid('load',data);
    }
    //执行初始化
    personalContractIndex_carManageWin.init();
</script>