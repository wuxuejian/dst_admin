<div class="easyui-tabs" data-options="fit:true,border:false,tabWidth:130" >
    <!--tab页签1-->
    <div title="基本信息">
        <form id="chargeChargeCardIndex_scanCardDetailsWin_form" method="post">
            <div class="easyui-panel" title="" style="padding:10px;"
                data-options="collapsible:true,collapsed:false,border:false,fit:false">
                <table cellpadding="6" cellspacing="0"  border="0" width="90%" align="center">
                    <tr hidden>
                        <th align="right">电卡ID：</th>
                        <td colspan="5">
                            <?php echo $cardInfo['cc_id']; ?>
                        </td>
                    </tr>
                    <tr>
                        <th align="right" width="10%">电卡编号：</th>
                        <td width="23%">
                            <?php echo $cardInfo['cc_code']; ?>
                        </td>
                        <th align="right"  width="10%">电卡类型：</th>
                        <td>
                            <?php echo $config['cc_type'][$cardInfo['cc_type']]['text']; ?>
                        </td>
                        <th align="right"  width="10%">电卡状态：</th>
                        <td width="23%">
                            <?php echo $config['cc_status'][$cardInfo['cc_status']]['text']; ?>
                        </td>
                    </tr>
                    <tr>
                        <th align="right">会员编号：</th>
                        <td>
                            <?php echo $cardInfo['cc_holder_code']; ?>
                        </td>
                        <th align="right" >制卡日期：</th>
                        <td>
                            <?php echo $cardInfo['cc_start_date']; ?>
                        </td>
                        <th align="right" >有效日期：</th>
                        <td>
                            <?php echo $cardInfo['cc_end_date']; ?>
                        </td>
                    </tr>
                    <tr>
                        <th align="right">初始额度：</th>
                        <td>
                            <?php echo $cardInfo['cc_initial_money']; ?> 元
                        </td>
                        <th align="right">当前余额：</th>
                        <td colspan="3">
                            <span style="font-size:16px;color:#FF0000;"><?php echo $cardInfo['cc_current_money']; ?></span> 元
                        </td>
                    </tr>
                    <tr>
                        <th align="right">充值次数：</th>
                        <td>
                            <?php echo $cardInfo['recharge_num']; ?> 次
                        </td>
                        <th align="right">消费次数：</th>
                        <td colspan="3">
                            <?php echo $cardInfo['consume_num']; ?> 次
                        </td>
                    </tr>
                    <tr>
                        <th align="right" valign="top">备注：</th>
                        <td colspan="5">
                            <div style="height:30px;">
                                <?php echo $cardInfo['cc_mark']; ?>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </form>
    </div>

    <!--tab页签2-->
    <div title="充值记录">
        <div class="easyui-panel" title="数据检索" style="padding:0px 5px;"
             data-options="iconCls:'icon-search',collapsible:false,collapsed:false,border:false,fit:false">
            <div class="data-search-form">
                <form id="scanCardDetailsWin_rechargeTab_searchFrom">
                    <ul class="search-main">
                        <li>
                            <div class="item-name">充值单号</div>
                            <div class="item-input">
                                <input class="easyui-textbox" type="text" name="ccrr_code" style="width:150px;"  />
                            </div>
                        </li>
                        <li>
                            <div class="item-name">充值日期</div>
                            <div class="item-input">
                                <input class="easyui-datebox" type="text" name="ccrr_create_time_start" style="width:90px;"  /> -
                                <input class="easyui-datebox" type="text" name="ccrr_create_time_end" style="width:90px;"  />
                            </div>
                        </li>
                        <li class="search-button">
                            <a href="javascript:chargeChargeCardIndex_scanCardDetailsWin.search('rechargeTab')" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                            <a href="javascript:chargeChargeCardIndex_scanCardDetailsWin.reset('rechargeTab')" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</a>
                        </li>
                    </ul>
                </form>
            </div>
        </div>
        <div class="easyui-panel" title="充值记录" data-options="iconCls:'icon-table-list',border:false,height:370">
            <table id="scanCardDetailsWin_rechargeTab_datagrid"></table>
        </div>
    </div>

    <!--tab页签3-->
    <div title="消费记录">
        <div class="easyui-panel" title="数据检索" style="padding:0px 5px;"
             data-options="iconCls:'icon-search',collapsible:false,collapsed:false,border:false,fit:false">
            <div class="data-search-form">
                <form id="scanCardDetailsWin_consumeTab_searchFrom">
                    <ul class="search-main">
                        <li>
                            <div class="item-name">交易流水号</div>
                            <div class="item-input">
                                <input class="easyui-textbox" type="text" name="DEAL_NO" style="width:150px;"  />
                            </div>
                        </li>
                        <li>
                            <div class="item-name">消费日期</div>
                            <div class="item-input">
                                <input class="easyui-datebox" type="text" name="TIME_TAG_start" style="width:90px;"  /> -
                                <input class="easyui-datebox" type="text" name="TIME_TAG_end" style="width:90px;"  />
                            </div>
                        </li>
                        <li class="search-button">
                            <a href="javascript:chargeChargeCardIndex_scanCardDetailsWin.search('consumeTab')" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                            <a href="javascript:chargeChargeCardIndex_scanCardDetailsWin.reset('consumeTab')" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</a>
                        </li>
                    </ul>
                </form>
            </div>
        </div>
        <div class="easyui-panel" title="消费记录" data-options="iconCls:'icon-table-list',border:false,height:370">
            <table id="scanCardDetailsWin_consumeTab_datagrid"></table>
        </div>
    </div>

</div>

<script>
    // 请求的URl
    var scanCardDetailsWin_URL_getRechargeRecords = "<?php echo yii::$app->urlManager->createUrl(['charge/charge-card/get-recharge-records','cc_id'=>$cardInfo['cc_id']]); ?>";
    var scanCardDetailsWin_URL_getConsumeRecords = "<?php echo yii::$app->urlManager->createUrl(['charge/charge-card/get-consume-records','cc_id'=>$cardInfo['cc_id']]); ?>";

    var chargeChargeCardIndex_scanCardDetailsWin = {
        // 初始化函数
        init: function(){
            // 充值记录列表
            $('#scanCardDetailsWin_rechargeTab_datagrid').datagrid({
                method: 'get',
                url: scanCardDetailsWin_URL_getRechargeRecords,
                fit:true,
                border: false,
                pagination: true,
                loadMsg: '数据加载中...',
                striped: true,
                checkOnSelect: true,
                rownumbers: true,
                singleSelect: false,
                pageSize: 20,
                frozenColumns: [[
                    {field: 'ck', checkbox: true},
                    {field: 'ccrr_id', title: '充值记录ID', width: 40, align: 'center', hidden: true},
                    {field: 'ccrr_code', title: '充值单号', width: 120, align: 'center', sortable: true},
                    {field: 'ccrr_card_id', title: '电卡ID', width: 40, align: 'center', hidden: true}
                ]],
                columns:[[
                    {field:'ccrr_recharge_money',title:'充值金额(元)',width:90,halign:'center',align:'right',sortable:true},
                    {field:'ccrr_incentive_money',title:'奖励金额(元)',width:90,halign:'center',align:'right',sortable:true},
                    {field:'ccrr_before_money',title:'充值前余额(元)',width:100,halign:'center',align:'right',sortable:true},
                    {field:'ccrr_after_money',title:'充值后余额(元)',width:100,halign:'center',align:'right',sortable:true},
                    {field:'ccrr_create_time',title:'充值时间',width:130,align:'center',sortable:true},
                    {field:'ccrr_creator_id',title:'操作人员id',width:100,align:'center',sortable:true,hidden:true},
                    {field:'ccrr_creator',title:'操作人员',width:100,align:'center',sortable:true},
                    {field:'ccrr_mark',title:'备注',width:180,align:'center',sortable:true}
                ]]
            });
            // 消费记录列表(待修改)
            $('#scanCardDetailsWin_consumeTab_datagrid').datagrid({
                method: 'get',
                url: scanCardDetailsWin_URL_getConsumeRecords,
                fit:true,
                border: false,
                pagination: true,
                loadMsg: '数据加载中...',
                striped: true,
                checkOnSelect: true,
                rownumbers: true,
                singleSelect: false,
                pageSize: 20,
/*
                frozenColumns: [[
                    {field: 'ck', checkbox: true},
                    {field: 'cccr_id', title: '消费记录ID', width: 40, align: 'center', hidden: true},
                    {field: 'cccr_code', title: '消费单号', width: 120, align: 'center', sortable: true},
                    {field: 'cccr_card_id', title: '电卡ID', width: 40, align: 'center', hidden: true}
                ]],
                columns:[[
                    {field:'cccr_consume_money',title:'消费金额(元)',width:90,halign:'center',align:'right',sortable:true},
                    {field:'cccr_before_money',title:'消费前余额(元)',width:100,halign:'center',align:'right',sortable:true},
                    {field:'cccr_after_money',title:'消费后余额(元)',width:100,halign:'center',align:'right',sortable:true},
                    {field:'cccr_create_time',title:'消费时间',width:130,align:'center',sortable:true},
                    {field:'cccr_creator_id',title:'操作人员id',width:100,align:'center',sortable:true,hidden:true},
                    {field:'cccr_creator',title:'操作人员',width:100,align:'center',sortable:true},
                    {field:'cccr_mark',title:'备注',width:180,align:'center',sortable:true}
                ]]
*/
                frozenColumns: [[
                    {field: 'ck', checkbox: true},
//                    {field: 'DEV_ID', title: '设备ID', width: 50,align: 'center', hidden: false},
                    {field: 'DEAL_NO', title: '交易流水号', width: 80,align: 'center',sortable: true},
                ]],
                columns: [[
                    {field: 'TIME_TAG', title: '数据时间', width: 130, align: 'center', sortable: true},
                    {field: 'DEAL_TYPE', title: '交易类型', width: 60, align: 'center', sortable: true},
//                    {field: 'AREA_CODE', title: '地区代码', width: 60, align: 'center', sortable: true},
                    {field: 'START_CARD_NO', title: '卡号', width: 120, align: 'center', sortable: true},
//                    {field: 'START_CARD_NO', title: '开始卡号', width: 120, align: 'center', sortable: true},
//                    {field: 'END_CARD_NO', title: '结束卡号', width: 120, align: 'center', sortable: true},
//                    {field: 'START_CARD_TYPE', title: '开始卡型', width: 60, align: 'center', sortable: true},
//                    {field: 'END_CARD_TYPE', title: '结束卡型', width: 60, align: 'center', sortable: true},

                    {field: 'START_DEAL_DL', title: '开始电量(度)', width: 90, halign: 'center',align:'right', sortable: true},
//                    {field: 'START_DEAL_R1_DL', title: '开始交易费率1电量行度(度)', width: 150, halign: 'center',align:'right', sortable: true},
//                    {field: 'START_DEAL_R2_DL', title: '开始交易费率2电量行度(度)', width: 150, halign: 'center',align:'right', sortable: true},
//                    {field: 'START_DEAL_R3_DL', title: '开始交易费率3电量行度(度)', width: 150, halign: 'center',align:'right', sortable: true},
//                    {field: 'START_DEAL_R4_DL', title: '开始交易费率4电量行度(度)', width: 150, halign: 'center',align:'right', sortable: true},

                    {field: 'END_DEAL_DL', title: '结束电量(度)', width: 90, halign: 'center',align:'right', sortable: true},
//                    {field: 'END_DEAL_R1_DL', title: '结束交易费率1电量行度(度)', width: 150, halign: 'center',align:'right', sortable: true},
//                    {field: 'END_DEAL_R2_DL', title: '结束交易费率2电量行度(度)', width: 150, halign: 'center',align:'right', sortable: true},
//                    {field: 'END_DEAL_R3_DL', title: '结束交易费率3电量行度(度)', width: 150, halign: 'center',align:'right', sortable: true},
//                    {field: 'END_DEAL_R4_DL', title: '结束交易费率4电量行度(度)', width: 150, halign: 'center',align:'right', sortable: true},

//                    {field: 'DEAL_R1_PRICE', title: '交易费率1电价(元)', width: 130, halign: 'center',align:'right', sortable: true},
//                    {field: 'DEAL_R2_PRICE', title: '交易费率2电价(元)', width: 130, halign: 'center',align:'right', sortable: true},
//                    {field: 'DEAL_R3_PRICE', title: '交易费率3电价(元)', width: 130, halign: 'center',align:'right', sortable: true},
//                    {field: 'DEAL_R4_PRICE', title: '交易费率4电价(元)', width: 130, halign: 'center',align:'right', sortable: true},

//                    {field: 'STOP_FEE_PRICE', title: '停车费单价(元/小时)', width: 130, halign: 'center',align:'right', sortable: true},

                    {field: 'DEAL_START_DATE', title: '交易开始时间', width: 130, align: 'center', sortable: true},
                    {field: 'DEAL_END_DATE', title: '交易结束时间', width: 130, align: 'center', sortable: true},

                    {field: 'STOP_FEE', title: '停车费(元)', width: 70, halign: 'center',align:'right', sortable: true},
                    {field: 'REMAIN_BEFORE_DEAL', title: '交易前余额(元)', width: 100, halign: 'center',align:'right', sortable: true},
                    {field: 'REMAIN_AFTER_DEAL', title: '交易后余额(元)', width: 100, halign: 'center',align:'right', sortable: true},

//                    {field: 'CAR_DEAL_COUNTER', title: '卡交易计数器', width: 90, align: 'center', sortable: true},
                    {field: 'TRM_NO', title: '终端号', width: 60, align: 'center', sortable: true},
//                    {field: 'CARD_VER_NO', title: '卡版本号', width: 60, align: 'center', sortable: true},
//                    {field: 'POS_NO', title: 'POS机号', width: 60, align: 'center', sortable: true},
                    {field: 'CARD_STATUS', title: '卡状态码', width: 60, align: 'center', sortable: true},
                    {field: 'WRITE_TIME', title: '写库时间', width: 130, align: 'center', sortable: true},
                    {field: 'CAR_NO', title: '车号', width: 50, align: 'center', sortable: true},
                    {field: 'INNER_ID', title: '测量点', width: 50, align: 'center', sortable: true}
                ]]
            });
        },
        // 查询
        search: function(whichTab){
            if(whichTab == 'rechargeTab') {
                var form = $('#scanCardDetailsWin_rechargeTab_searchFrom');
                var grid = $('#scanCardDetailsWin_rechargeTab_datagrid');
            }else if(whichTab == 'consumeTab'){
                var form = $('#scanCardDetailsWin_consumeTab_searchFrom');
                var grid = $('#scanCardDetailsWin_consumeTab_datagrid');
            }
            var data = {};
            var searchCondition = form.serializeArray();
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = searchCondition[i]['value'];
            }
            grid.datagrid('load',data);
        },
        // 重置
        reset: function(whichTab){
            if(whichTab == 'rechargeTab') {
                var form = $('#scanCardDetailsWin_rechargeTab_searchFrom');
            }else if(whichTab == 'consumeTab'){
                var form = $('#scanCardDetailsWin_consumeTab_searchFrom');
            }
            form.form('reset');
        }
    }

    // 执行初始化函数
    chargeChargeCardIndex_scanCardDetailsWin.init();

</script>