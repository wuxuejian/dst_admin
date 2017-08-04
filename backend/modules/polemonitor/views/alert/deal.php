<table id="easyui_datagrid_polemonitor_alert_deal"></table> 
<div id="easyui_datagrid_polemonitor_alert_deal_toolbar">
    <div
        class="easyui-panel"
        title="充电桩信息"
        style="width:100%;line-height:24px;"
        data-options="iconCls: 'icon-search',border: false"
    >
        <ul class="ulforform-resizeable">
            <li class="ulforform-resizeable-group">
                <div class="ulforform-resizeable-title">充电站</div>
                <div class="ulforform-resizeable-input">
                    <input
                        class="easyui-textbox"
                        style="width:100%"
                        name="plate_number"
                        data-options="readonly:true"
                        value="<?php echo $alertRecord['cs_name']; ?>"
                    />
                </div>
            </li>
            <li class="ulforform-resizeable-group">
                <div class="ulforform-resizeable-title">充电桩</div>
                <div class="ulforform-resizeable-input">
                    <input
                        class="easyui-textbox"
                        style="width:100%"
                        name="car_vin"
                        data-options="readonly:true"
                        value="<?php echo $alertRecord['dev_addr']; ?>"
                    />
                </div>
            </li>
            <li class="ulforform-resizeable-group">
                <div class="ulforform-resizeable-title">充电桩状态</div>
                <div class="ulforform-resizeable-input">
                    <input
                        class="easyui-textbox"
                        style="width:100%"
                        name="car_status_text"
                        data-options="readonly:true"
                        value="<?php echo $alertRecord['pole_status']; ?>"
                    />
                </div>
            </li>
        </ul>    
    </div>
    <div style="border-bottom:1px solid #95B8E7"></div>
    <div
        class="easyui-panel"
        title="处理进度"
        style="width:100%;"
        data-options="iconCls: 'icon-table-list',border: false"
    >
        <div style="padding:4px;">
            <button onclick="PolemonitorAlertDeal.add()" class="easyui-linkbutton" data-options="iconCls:'icon-add'">添加处理记录</button>
            <button onclick="PolemonitorAlertDeal.remove()" class="easyui-linkbutton" data-options="iconCls:'icon-remove'">删除处理记录</button>
            <span style="color:red;padding-left:10px;">注：双击记录可修改，点击“确定”按钮才能保存修改！</span>
        </div>
    </div>
</div>
<form id="easyui_form_polemonitor_alert_deal_save_data" style="display:none"></form>
<script>
    var PolemonitorAlertDeal = {
        params: {
            url: {
                getListData: "<?= yii::$app->urlManager->createUrl(['polemonitor/alert/get-deal-record','csa_id'=>$csaId]); ?>",
                alertDeal: "<?= yii::$app->urlManager->createUrl(['polemonitor/alert/deal','csa_id'=>$csaId]); ?>"
            },
            datagrid: $('#easyui_datagrid_polemonitor_alert_deal'),
            forms: {
                saveData: $('#easyui_form_polemonitor_alert_deal_save_data')
            }
        },
        init: function(){
            //获取列表数据
            this.params.datagrid.datagrid({  
                method: 'get', 
                url:this.params.url.getListData,   
                fit: true,
                border: false,
                toolbar: "#easyui_datagrid_polemonitor_alert_deal_toolbar",
                pagination: false,
                loadMsg: '数据加载中...',
                striped: true,
                checkOnSelect: true,
                rownumbers: true,
                singleSelect: false,
                frozenColumns: [[
                    {field: 'ck',checkbox: true},
                    {field: 'id',hidden: true}
                ]],
                columns:[[
                    {
                        field: 'status',title: "处理进度",align: 'center',
                        sortable: true,width: 100,
                        editor: {
                            type:'combobox',
                            options:{
                                valueField:'value',
                                textField:'text',
                                panelHeight: 'auto',
                                data: [{value: "已受理",text: "已受理"},{value: "处理中",text: "处理中"},{value: "已完结",text: "已完结"}],
                                required:true,
                                editable: false
                        }}
                    },
                    {field: 'deal_way',title: '处理方法',width: 500,align: 'left',halign: 'center',editor: {
                        type:'textbox',
                        options: {validType: "length[255]"}
                    }},
                    {field: 'deal_date',title: '处理时间',width: 120,align: 'center',sortable: true,editor: {
                        type: 'datebox',
                        options: {required: true,validType: "date"}
                    }},
                    {field: 'username',title: '记录人员',width: 100,align: 'left',haling:'center'}
                ]],
                onDblClickRow: function(rowIndex,rowData){
                    var rows = PolemonitorAlertDeal.params.datagrid.datagrid('getRows').length;
                    for(var i = 0;i<rows;i++){
                        if(PolemonitorAlertDeal.params.datagrid.datagrid('validateRow',i)){
                            PolemonitorAlertDeal.params.datagrid.datagrid('endEdit',i);
                        }else{
                            PolemonitorAlertDeal.params.datagrid.datagrid('deleteRow',i);
                        }
                    }
                    PolemonitorAlertDeal.params.datagrid.datagrid('beginEdit',rowIndex);
                }
            });
        },
        getSelected: function(all){
            var datagrid = this.params.datagrid;
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
        },
        add: function(){
            var currentRowIndex = this.params.datagrid.datagrid('getRows').length;
            for(var i = 0;i < currentRowIndex;i++){
                if(this.params.datagrid.datagrid('validateRow',i)){
                    this.params.datagrid.datagrid('endEdit',i);
                }else{
                    //有项目验证不通过无法添加新项目
                    return;
                }
            }
            this.params.datagrid.datagrid('appendRow',{
                status: '',
                deal_way: '',
                deal_datetime: ''
            });
            this.params.datagrid.datagrid('beginEdit',currentRowIndex);
        },
        remove: function(){
            var selectRows = this.getSelected(true);
            if(!selectRows){
                return false;
            }
            for(var i in selectRows){
                var rowIndex = this.params.datagrid.datagrid('getRowIndex',selectRows[i]);
                this.params.datagrid.datagrid('deleteRow',rowIndex);
            }
        },
        saveData: function(){
            var rows = this.params.datagrid.datagrid('getRows').length;
            for(var i = 0;i < rows;i++){
                if(this.params.datagrid.datagrid('validateRow',i)){
                    this.params.datagrid.datagrid('endEdit',i);
                }else{
                    $.messager.alert('操作失败','数据填写有误','error');
                    return;
                }
            }
            //组装表单数据
            this.params.forms.saveData.html('');
            var rowsData = this.params.datagrid.datagrid('getRows');
            for(var i in rowsData){
                var idInput = $('<input name="id[]" value="0" />');
                if(rowsData[i].id){
                    idInput.val(rowsData[i].id);
                }
                this.params.forms.saveData.append(idInput);
                var statusInput = $('<input name="status[]" value="" />');
                statusInput.val(rowsData[i].status);
                this.params.forms.saveData.append(statusInput);
                var dealWayInput = $('<input name="deal_way[]" value="" />');
                dealWayInput.val(rowsData[i].deal_way);
                this.params.forms.saveData.append(dealWayInput);
                var dealDatetimeInput = $('<input name="deal_date[]" value="" />');
                dealDatetimeInput.val(rowsData[i].deal_date);
                this.params.forms.saveData.append(dealDatetimeInput);
            }
            $.ajax({
                type: 'post',
                url : this.params.url.alertDeal,
                data: this.params.forms.saveData.serialize(),
                dataType: 'json',
                success: function(rData){
                    if(rData.error){
                        $.messager.alert('操作失败',rData.msg,'error');
                    }else{
                        $.messager.alert('操作成功',rData.msg,'info');
                        try{
                            PolemonitorAlertIndex.params.windows.deal.dialog('close');
                        }catch(e){}
                        try{
                            IndexWelcome.params.windows.poleAlertDeal.dialog('close');
                        }catch(e){}
                        
                        
                    }
                }
            });
        }
    };
    PolemonitorAlertDeal.init();
</script>