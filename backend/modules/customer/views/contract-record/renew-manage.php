<table id="easyui-datagrid-customer-contract-record-renew-manage"></table> 
<div id="easyui-datagrid-customer-contract-record-renew-manage-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-customer-contract-record-renew-manage">
                <ul class="search-main">
                    <li>
                        <div class="item-name">操作时间</div>
                        <div class="item-input">
                            <input class="easyui-datetimebox" type="text" name="action_time_start" style="width:150px;"></input>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">操作时间</div>
                        <div class="item-input">
                            <input class="easyui-datetimebox" type="text" name="action_time_end" style="width:150px;"></input>
                        </div>
                    </li>
                    <li class="search-button">
                        <a id="btn" href="javascript:CustomerContractRecordRenewManage.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
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
<!-- 窗口 -->
<div id="easyui-datagrid-customer-contract-record-renew-manage-renew-add"></div>
<!-- 窗口 -->
<script>
    var CustomerContractRecordRenewManage = new Object();
    CustomerContractRecordRenewManage.init = function(){
        //获取列表数据
        $('#easyui-datagrid-customer-contract-record-renew-manage').datagrid({  
            method: 'get', 
            url:"<?php echo yii::$app->urlManager->createUrl(['customer/contract-record/get-renew-list','contractId'=>$contractId]); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-customer-contract-record-renew-manage-toolbar",
            pagination: true,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
            showFooter: true,
            frozenColumns: [[
                {field: 'ck',checkbox: true}, 
                {field: 'id',title: 'id',hidden: true},   
                {field: 'admin_name',title: '操作人员',width: 100}
            ]],
            columns:[[
                {
                    field: 'cost_expire_time',title: '续费到期时间',width: 125, align: 'center',
                    formatter: function(value){
                        if(!isNaN(value) && value != 0){
                            return formatDateToString(value);
                        }
                    }
                },
                {field: 'should_money',title: '应收金额',width: 125, align: 'right'},
                {field: 'true_money',title: '实收金额',width: 125, align: 'right'},
                {
                    field: 'action_time',title: '操作时间',width: 130, align: 'center',
                    formatter: function(value){
                        if(!isNaN(value) && value != 0){
                            return formatDateToString(value,true);
                        }
                    }
                },
                {field: 'note',title: '备注',width: 300,align: 'left'}
            ]],
            //双击
            onDblClickRow: function(rowIndex,rowData){
                CustomerContractRecordRenewManage.renewEdit(rowData.id);
            }
        });
        //初始化添加续费记录窗口
        $('#easyui-datagrid-customer-contract-record-renew-manage-renew-add').dialog({
            title: '添加续费记录',   
            width: '640px',   
            height: '400px',   
            closed: true,   
            cache: true,   
            modal: true,
            maximizable: false,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var form = $('#easyui-form-customer-contract-record-renew-add');
                    if(!form.form('validate')){
                        return false;
                    }
                    var data = form.serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['customer/contract-record/renew-add']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('添加成功',data.info,'info');
                                $('#easyui-datagrid-customer-contract-record-renew-manage-renew-add').dialog('close');
                                $('#easyui-datagrid-customer-contract-record-renew-manage').datagrid('reload');
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
                    $('#easyui-datagrid-customer-contract-record-renew-manage-renew-add').dialog('close');
                }
            }]
        });
    }
    //添加续费记录
    CustomerContractRecordRenewManage.renewAdd = function(){
        $('#easyui-datagrid-customer-contract-record-renew-manage-renew-add').dialog('open');
        $('#easyui-datagrid-customer-contract-record-renew-manage-renew-add').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['customer/contract-record/renew-add','contractId'=>$contractId]); ?>");
    }
    //查询
    CustomerContractRecordRenewManage.search = function(){
        var form = $('#search-form-customer-contract-record-renew-manage');
        var data = {};
        var searchCondition = form.serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        $('#easyui-datagrid-customer-contract-record-renew-manage').datagrid('load',data);
    }
    //执行初始化
    CustomerContractRecordRenewManage.init();
</script>