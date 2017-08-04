<table id="easyui_datagrid_card_swap_do"></table> 
<div id="easyui_datagrid_card_swap_do_toolbar">
    <form id="easyui_form_card_swap_do">
        <div
            class="easyui-panel"
            title="充电卡信息"
            style="width:100%;padding:10px;"
            data-options="iconCls: 'icon-search',border: false"
        >
            <ul class="ulforform-resizeable">
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">串口号</div>
                    <div class="ulforform-resizeable-input">
                        <select
                            class="easyui-combobox"
                            name="port"
                            style="width:180px;"
                        >
                            <option value="1">COM1</option>
                            <option value="2">COM2</option>
                            <option value="3">COM3</option>
                            <option value="4">COM4</option>
                            <option value="5">COM5</option>
                            <option value="6">COM6</option>
                            <option value="7">COM7</option>
                        </select>
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">卡号</div>
                    <div class="ulforform-resizeable-input">
                        <input
                            class="easyui-textbox"
                            name="cc_code"
                            style="width:180px;"
                            readonly="true"
                        />
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">卡类型</div>
                    <div class="ulforform-resizeable-input">
                        <input
                            class="easyui-textbox"
                            name="cc_type"
                            style="width:180px;"
                            readonly="true"
                        />
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">余额</div>
                    <div class="ulforform-resizeable-input">
                        <input
                            class="easyui-textbox"
                            name="before_money"
                            style="width:180px;"
                            readonly="true"
                        />
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">客户号</div>
                    <div class="ulforform-resizeable-input">
                        <input
                            class="easyui-textbox"
                            name="customer_code"
                            style="width:180px;"
                            readonly="true"
                        />
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">客户名称</div>
                    <div class="ulforform-resizeable-input">
                        <input
                            class="easyui-textbox"
                            name="customer_client"
                            style="width:180px;"
                            readonly="true"
                        />
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">客户手机</div>
                    <div class="ulforform-resizeable-input">
                        <input
                            class="easyui-textbox"
                            name="customer_mobile"
                            style="width:180px;"
                            readonly="true"
                        />
                    </div>
                </li>
            </ul>
            <div style="text-align: right;"><a onclick="CardSwapDo.readCard()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">读卡</a></div>
        </div>
        <div style="border-bottom:1px solid #95B8E7"></div>
        <div
            class="easyui-panel"
            title="充电卡调剂"
            style="width:100%;padding:10px;"
            data-options="iconCls:'icon-search',border: false"
        >
            <ul class="ulforform-resizeable">
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">调剂类型</div>
                    <div class="ulforform-resizeable-input">
                        <select
                            class="easyui-combobox"
                            editable="false"
                            style="width: 180px"
                            name="type"
                        >
                            <option value="reduce">减少</option>
<!--                             <option value="add">增加</option> -->
                        </select>
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">调剂金额</div>
                    <div class="ulforform-resizeable-input">
                        <input
                            class="easyui-textbox"
                            name="money"
                            style="width:180px;"
                            required="true"
                            validType="money"
                        />
                    </div>
                </li>
                <li class="ulforform-resizeable-group-single">
                    <div class="ulforform-resizeable-title">备注</div>
                    <div class="ulforform-resizeable-input">
                        <input
                            class="easyui-textbox"
                            name='note'
                            data-options="multiline:true"
                            style="height:60px;width:482px;"
                            validType="length[255]"
                        />
                    </div>
                </li>
            </ul>
        </div>
    </form>
    <div style="border-bottom:1px solid #95B8E7"></div>
    <div class="easyui-panel" title="异常充电记录" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    "></div>
</div>
<div id="easyui_window_card_recharge_swap_do_tipwin"></div>
<script>
    var CardSwapDo = {
        // 初始化
        init: function() {
            //
            $('#easyui_window_card_recharge_swap_do_tipwin').window({
                width:400,
                height:200,
                modal:true,
                title: '正在读写卡',
                closed: true,
                resizable: false,
                collapsible: false,
                minimizable: false,
                maximizable: false,
                closable: true,
                content: '<div style="text-align:center;padding-top:60px;">正在进行读写卡操作请等待...</div>'
            });
            //--初始化表格
            $('#easyui_datagrid_card_swap_do').datagrid({
                method: 'get',
                url: "<?= yii::$app->urlManager->createUrl(['card/charge-record/exception-charge']); ?>",
                toolbar: "#easyui_datagrid_card_swap_do_toolbar",
                fit: true,
                border: false,
                pagination: true,
                loadMsg: '数据加载中...',
                striped: true,
                checkOnSelect: true,
                rownumbers: true,
                singleSelect: true,
                pageSize: 20,
                frozenColumns: [[
                    {field: 'ck', checkbox: true},
                    {field: 'cc_id', title: '电卡ID', width: 40, align: 'center', hidden: true},
                    {field: 'cc_code', title: '电卡编号', width: 100, align: 'center', sortable: true}
                ]],
                frozenColumns: [[
                    {field: 'ck', checkbox: true},
                    {field: 'DEAL_NO', title: '交易流水号', width: 80,align: 'center',sortable: true},
                ]],
                columns: [[
                    {field: 'START_CARD_NO', title: '电卡编号', width: 120, align: 'center', sortable: true},
                    {field: 'end_status', title: '状态', width: 80, align: 'center', sortable: true,
                        formatter:function(v){
                            switch(parseInt(v)){
                                case 0:
                                    return '<span style="background-color:#FFCC01;color:#fff;padding:2px 5px;">正在充电</span>';
                                case 1:
                                    return '<span style="background-color:#05CD69;color:#fff;padding:2px 5px;">结束正常</span>';
                                case 2:
                                    return '<span style="background-color:#FF4040;color:#fff;padding:2px 5px;">结束异常</span>';
                            }
                        }
                    },

                    {field: 'START_DEAL_DL', title: '开始电量(度)', width: 90, halign: 'center',align:'right', sortable: true},
                    {field: 'END_DEAL_DL', title: '结束电量(度)', width: 90, halign: 'center',align:'right', sortable: true},
                    {field: 'consume_DL', title: '<span style="color:#FF8000;">消费电量(度)</span>', width: 90, halign: 'center',align:'right'},
                    {field: 'REMAIN_BEFORE_DEAL', title: '交易前余额(元)', width: 100, halign: 'center',align:'right', sortable: true},
                    {field: 'REMAIN_AFTER_DEAL', title: '交易后余额(元)', width: 100, halign: 'center',align:'right', sortable: true},
                    {field: 'consume_money', title: '<span style="color:#FF8000;">消费金额(元)</span>', width: 100, halign: 'center',align:'right'},

                    {field: 'DEAL_START_DATE', title: '开始时间', width: 130, align: 'center', sortable: true},
                    {field: 'DEAL_END_DATE', title: '结束时间', width: 130, align: 'center', sortable: true},
                    {field: 'CAR_NO', title: '车号', width: 50, align: 'center', sortable: true},
                    {field: 'INNER_ID', title: '测量点', width: 50, align: 'center', sortable: true},
                    {field: 'TIME_TAG', title: '记录时间', width: 130, align: 'center', sortable: true}
                ]],
                onDblClickRow: function (rowIndex, rowData) {
                    cardChargeCardIndex.edit(rowData.cc_id);
                }
            });
        },
        readCard: function(){
            var easyuiForm = $('#easyui_form_card_swap_do');
            var port = easyuiForm.find('select[comboname=port]').textbox('getValue');
            var tipWindow = $('#easyui_window_card_recharge_swap_do_tipwin');
            //读取卡号
            tipWindow.window('open');
            var readResult = KLChargeCard.readCard(port);
            tipWindow.window('close');
            if(!readResult.status){
                $.messager.alert('错误',readResult.info,'error');
                return false;
            }
            var cardNo = readResult.cardNo;
            var easyuiDatagrid = $('#easyui_datagrid_card_swap_do');
            easyuiDatagrid.datagrid('reload',"<?= yii::$app->urlManager->createUrl(['card/charge-record/exception-charge']); ?>&cardNo="+cardNo);
            easyuiForm.find('input[textboxname=cc_code]').textbox('setValue',cardNo);
            //读取卡内余额
            tipWindow.window('open');
            var moneyReadResult = KLChargeCard.ye(port);
            tipWindow.window('close');
            if(moneyReadResult.status){
                easyuiForm.find('input[textboxname=before_money]').textbox('setValue',moneyReadResult.money);
            }else{
                $.messager.alert('余额获取失败','余额获取失败，请重新读卡['+moneyReadResult.info+']！','error');
                return;
            }
            //读取卡片的客户信息
            $.ajax({
                type: 'get',
                url: "<?= yii::$app->urlManager->createUrl(['card/charge-card/get-info']); ?>",
                data: {"cardNo": cardNo},
                dataType: 'json',
                success: function(rData){
                    if(rData.status){
                        //写入数据到页面
                        easyuiForm.find('input[textboxname=cc_type]').textbox('setValue',rData.datas.cc_type);
                        easyuiForm.find('input[textboxname=customer_code]').textbox('setValue',rData.datas.code);
                        easyuiForm.find('input[textboxname=customer_client]').textbox('setValue',rData.datas.client);
                        easyuiForm.find('input[textboxname=customer_mobile]').textbox('setValue',rData.datas.mobile);
                    }else{
                        $.messager.error('错误',rData.info,'error');
                    }
                }
            });
        },
        writeCard: function(rData_data){
            var easyuiForm = $('#easyui_form_card_swap_do');
            var port = easyuiForm.find('select[comboname=port]').textbox('getValue');
            var type = rData_data.type;
            var money = rData_data.money;
            var cardNo = rData_data.cc_code;
            var rechargeTimes = rData_data.rechargeTimes;
            var tipWindow = $('#easyui_window_card_recharge_swap_do_tipwin');
            if(type == 'add'){
                //添加金额
                tipWindow.window('open');
                var writeResult = KLChargeCard.cz(port,cardNo,money,rechargeTimes);
                tipWindow.window('close');
            }else{
                //减少金额
                tipWindow.window('open');
                var writeResult = KLChargeCard.kk(port,money);
                tipWindow.window('close');
            }
            if(writeResult.status){
                //如果写卡成功将数据提交给后台
                $.ajax({
                    type: 'get',
                    url: "<?= yii::$app->urlManager->createUrl(['card/swap/write-success']); ?>&swapId="+rData_data.swapId
                });
                $.messager.alert('操作成功','操作成功！','info');
                $('#cardChargeCardIndex_swap').dialog('close');
            }else{
                $.messager.alert('操作失败','写卡失败['+writeResult.info+']！','info');
            }
        }
    };
    // 执行初始化函数
    CardSwapDo.init();
</script>