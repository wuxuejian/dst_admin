<table id="VipChargeRecordChargeRecord_handleExceptionWin_datagrid"></table>
<div id="VipChargeRecordChargeRecord_handleExceptionWin_datagridToolbar">
    <div class="easyui-panel" title="" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">
        <form id="VipChargeRecordChargeRecord_handleExceptionWin_form" style="padding:5px;">
            <input type="hidden" name="ID" />
            <ul class="ulforform-resizeable">
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">交易号</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-textbox" name="DEAL_NO" style="width:100%;" disabled="true" />
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">电卡编号</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-textbox" name="START_CARD_NO" style="width:100%;" disabled="true" />
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">充电状态</div>
                    <div class="ulforform-resizeable-input">
                        <select class="easyui-combobox" name="DEAL_TYPE" style="width:100%;" data-options="panelHeight:'auto',editable:false" disabled="true" required="true">
                            <option value="2" selected="selected">结束异常</option>
                        </select>
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">记录时间</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-datetimebox" name="TIME_TAG" style="width:100%;" required="true" value="<?php echo date('Y-m-d H:i:s'); ?>"  disabled="true" />
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">开始电量</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-numberbox" name="START_DEAL_DL" style="width:100%;" precision="2" disabled="true" />
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">结束电量</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-numberbox" name="END_DEAL_DL" style="width:100%;" precision="2" required="true" min="<?php echo $recInfo['START_DEAL_DL']; ?>" />
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">交易前余额</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-numberbox" name="REMAIN_BEFORE_DEAL" style="width:100%;" precision="2" disabled="true" />
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">交易后余额</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-numberbox" name="REMAIN_AFTER_DEAL" style="width:100%;" precision="2" required="true" min="0" max="<?php echo $recInfo['REMAIN_BEFORE_DEAL']; ?>" />
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">开始时间</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-datetimebox" name="DEAL_START_DATE" style="width:100%;" disabled="true" />
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">结束时间</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-datetimebox" name="DEAL_END_DATE" style="width:100%;" required="true" />
                    </div>
                </li>
            </ul>
        </form>
    </div>
    <div class="easyui-panel" title="计量计费监控列表" style="padding:0px;width:100%;" data-options="
        iconCls: 'icon-table-list',
        border: false
    "></div>
</div>

<script>
    var VipChargeRecordChargeRecord_handleExceptionWin = {
        params:{
            dataSrc: <?php echo json_encode($recInfo); ?>,
            urlPoleMonitor: '<?php echo yii::$app->urlManager->createUrl(['vip/charge-record/pole-monitor']); ?>'
        },
        init: function(){
            //--表单赋值------------
            var form = $('#VipChargeRecordChargeRecord_handleExceptionWin_form');
            form.form('load',this.params.dataSrc);
            //结束电量、交易后金额、结束时间默认为空
            $('input[name="END_DEAL_DL"]',form).val('');
            $('input[name="REMAIN_AFTER_DEAL"]',form).val('');
            $('input[name="DEAL_END_DATE"]',form).val('');

            //--计量计费监控列表------
            var grid = $('#VipChargeRecordChargeRecord_handleExceptionWin_datagrid');
            grid.datagrid({
                method: 'get',
                url: this.params.urlPoleMonitor,
                queryParams:{
                    'DEV_ID': this.params.dataSrc.DEV_ID,
                    'INNER_ID': this.params.dataSrc.INNER_ID,
                    'DEAL_START_DATE': this.params.dataSrc.DEAL_START_DATE
                },
                toolbar:'#VipChargeRecordChargeRecord_handleExceptionWin_datagridToolbar',
                fit: true,
                border: false,
                pagination: true,
                loadMsg: '数据加载中...',
                striped: true,
                checkOnSelect: true,
                rownumbers: true,
                singleSelect: true,
                frozenColumns: [[
                    {field: 'ck',checkbox: true},
                    {field: 'DEV_ID',title: '设备ID',align: 'center',hidden: true},
                    {field: 'TIME_TAG',title: '数据时间',width: 140,align: 'center',sortable: true}
                ]],
                columns:[[
                    {field: 'CHARGE_AMOUNT',title: '充电电量',width: 100,halign:'center',align: 'right',sortable: true},
                    {field: 'COSUM_AMOUNT',title: '消费金额',width: 100,halign:'center',align: 'right',sortable: true},
                    {field: 'SOC',title: '电池SOC',width: 100,halign:'center',align: 'right',sortable: true},
                    {field: 'CAR_NO',title: '车号',width: 70,align: 'center',sortable: true},
                    {field: 'INNER_ID',title: '测量点',width: 60,align: 'center',sortable: true},
                    {field: 'WRITE_TIME',title: '写库时间',width: 140,align: 'center',sortable: true}
                ]],
                onDblClickRow: function(rowIndex,rowData){
                    var data = {
                        END_DEAL_DL: parseFloat($('input[name="START_DEAL_DL"]',form).val()) + parseFloat(rowData.CHARGE_AMOUNT),
                        REMAIN_AFTER_DEAL: parseFloat($('input[name="REMAIN_BEFORE_DEAL"]',form).val()) - parseFloat(rowData.COSUM_AMOUNT),
                        DEAL_END_DATE: rowData.TIME_TAG
                    };
                    form.form('load',data);
                }
                /*,onLoadSuccess: function(data){
                    //以最新一条有效的计量计费记录为表单赋值
                    var rows = data.rows;
                    var i;
                    for(i=0;i<rows.length;i++){
                        if(parseFloat(rows[i].CHARGE_AMOUNT) > 0){
                            var data = {
                                END_DEAL_DL: parseFloat($('input[name="START_DEAL_DL"]',form).val()) + parseFloat(rows[i].CHARGE_AMOUNT),
                                REMAIN_AFTER_DEAL: parseFloat($('input[name="REMAIN_BEFORE_DEAL"]',form).val()) - parseFloat(rows[i].COSUM_AMOUNT),
                                DEAL_END_DATE: rows[i].TIME_TAG
                            };
                            form.form('load',data);
                            grid.datagrid('unselectAll').datagrid('selectRow',i);
                            break;
                        }
                    }
                }*/
            });


        }
    }
    //执行初始化
    VipChargeRecordChargeRecord_handleExceptionWin.init();
</script>
