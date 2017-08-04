<table id="vipVipRechargeRecordIndex_datagrid"></table> 
<div id="vipVipRechargeRecordIndex_datagridToolbar">
    <div class="easyui-panel" title="检索区域" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="vipVipRechargeRecordIndex_searchFrom">
                <ul class="search-main">
					<li>
                        <div class="item-name">充值单号</div>
                        <div class="item-input">
                           <input class="easyui-textbox" type="text" name="trade_no" style="width:100%;"
                                  data-options="
                                        onChange:function(){
                                            vipVipRechargeRecordIndex.search();
                                        }
                                   "
                               />
                        </div>
                    </li>                    
					<li>
                        <div class="item-name">支付方式</div>
                        <div class="item-input">
                            <select style="width:100%;" class="easyui-combobox" name="pay_way"
                                    data-options="
                                        panelHeight:'auto',
                                        editable:false,
                                        onChange:function(){
                                            vipVipRechargeRecordIndex.search();
                                        }
                                    ">
                                <option value="">--不限--</option>
                                <option value="wechat">微信</option>
                                <option value="alipay">支付宝</option>
                            </select>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">支付平台交易号</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="platform_trade_no" style="width:100%;"
                                   data-options="
                                        onChange:function(){
                                            vipVipRechargeRecordIndex.search();
                                        }
                                   "
                                />
                        </div>
                    </li>
					<li>
                        <div class="item-name">支付状态</div>
                        <div class="item-input">
                            <select style="width:100%;" class="easyui-combobox" name="trade_status"
                                    data-options="
                                        panelHeight:'auto',
                                        editable:false,
                                        onChange:function(){
                                            vipVipRechargeRecordIndex.search();
                                        }
                                    ">
                                <option value="">--不限--</option>
                                <option value="wait_pay">等待支付</option>
                                <option value="success">支付完成</option>
                            </select>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">充值时间</div>
                        <div class="item-input">
                            <input class="easyui-datebox" type="text" name="request_datetime_start" style="width:93px;"
                                   data-options="
                                        onChange:function(){
                                            vipVipRechargeRecordIndex.search();
                                        }
                                   "
                                />
                            -
                            <input class="easyui-datebox" type="text" name="request_datetime_end" style="width:93px;"
                                   data-options="
                                        onChange:function(){
                                            vipVipRechargeRecordIndex.search();
                                        }
                                   "
                                />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">会员编号</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="vip_code" style="width:100%;"
                                   data-options="
                                        onChange:function(){
                                            vipVipRechargeRecordIndex.search();
                                        }
                                   "
                                />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">会员名称</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="vip_name" style="width:100%;"
                                   data-options="
                                        onChange:function(){
                                            vipVipRechargeRecordIndex.search();
                                        }
                                   "
                                />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">会员手机号</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="vip_mobile" style="width:100%;"
                                   data-options="
                                        onChange:function(){
                                            vipVipRechargeRecordIndex.search();
                                        }
                                   "
                                />
                        </div>
                    </li>

                    <li class="search-button">
                        <a href="javascript:vipVipRechargeRecordIndex.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                        <a href="javascript:vipVipRechargeRecordIndex.reset()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</a>
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
                <a onclick="<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon'] ;?>'"><?= $val['text'] ;?></a>
            <?php } ?>
        </div>
    <?php } ?>

</div>
<!-- 窗口 -->
<div id="vipVipRechargeRecordIndex_exceptionHandleWin"></div>
<!-- 窗口 -->

<script>
	// 请求的URL
	var vipVipRechargeRecordIndex_URL_getList = "<?php echo yii::$app->urlManager->createUrl(['vip/vip-recharge-record/get-list']); ?>";
	var vipVipRechargeRecordIndex_URL_exportGridData = "<?php echo yii::$app->urlManager->createUrl(['vip/vip-recharge-record/export-grid-data']); ?>";
	var vipVipRechargeRecordIndex_URL_exceptionHandle = "<?php echo yii::$app->urlManager->createUrl(['vip/vip-recharge-record/exception-handle']); ?>";

	var vipVipRechargeRecordIndex = {
		// 初始化
		init: function(){
			// 列表
			$('#vipVipRechargeRecordIndex_datagrid').datagrid({  
				method: 'get', 
				url: vipVipRechargeRecordIndex_URL_getList,   
				fit: true,
				border: false,
				toolbar: "#vipVipRechargeRecordIndex_datagridToolbar",
				pagination: true,
				loadMsg: '数据加载中...',
				striped: true,
				checkOnSelect: true,
				rownumbers: true,
				singleSelect: true,
				pageSize: 20,
                showFooter: true,
				frozenColumns: [[
					{field: 'ck',checkbox: true}, 
					{field: 'id',title: 'ID',width:40,align:'center',hidden:true},
                    {field: 'trade_no',title: '充值单号',width: 110,align:'center',sortable:true},
				]],
				columns:[[
                    {field: 'total_fee',title: '充值金额',width: 70,halign:'center',align:'right',sortable:true},
                    {field: 'pay_way',title: '支付方式',width: 70,align:'center',sortable:true,
                        formatter: function(value,row,index){
                            if(value == 'wechat'){
                                return '微信';
                            }else if(value == 'alipay'){
                                return '支付宝';
                            }else{
                                return value;
                            }
                        }
                    },
                    {field: 'platform_trade_no',title: '支付平台交易号',width: 180,align:'center',sortable:true},
                    {field: 'trade_status',title: '支付状态',width: 70,align:'center',sortable:true,
                        formatter: function(value,row,index){
                            if(value == 'wait_pay'){
                                return '等待支付';
                            }else if(value == 'success'){
                                return '<span style="color:green;font-weight:bold;">支付完成</span>';
                            }else{
                                return value;
                            }
                        }
                    },
                    {field: 'request_datetime',title: '系统记录时间',align:'center',width: 130,sortable:true,
                        formatter: function(value,row,index){
                            if(parseInt(value)) {
                                return formatDateToString(value, true);
                            }else{
                                return '';
                            }
                        }
                    },
                    {field: 'last_notify_datetime',title: '最后通知时间',align:'center',width: 130,sortable:true,
                        formatter: function(value,row,index){
                            if(parseInt(value)) {
                                return formatDateToString(value, true);
                            }else{
                                return '';
                            }
                        }
                    },
                    {field: 'gmt_create_datetime',title: '交易创建时间',align:'center',width: 130,sortable:true,
                        formatter: function(value,row,index){
                            if(parseInt(value)) {
                                return formatDateToString(value, true);
                            }else{
                                return '';
                            }
                        }
                    },
                    {field: 'gmt_payment_datetime',title: '交易支付时间',align:'center',width: 130,sortable:true,
                        formatter: function(value,row,index){
                            if(parseInt(value)) {
                                return formatDateToString(value, true);
                            }else{
                                return '';
                            }
                        }
                    },
                    {field: 'vip_code',title: '会员编号',width: 130,align:'center',sortable:true},
                    {field: 'vip_mobile',title: '会员手机号',width: 80,align:'center',sortable:true},
                    {field: 'vip_name',title: '会员名称',width: 90,align:'center',sortable:true}
                ]]
			});
            //初始化【异常处理】窗口
            $('#vipVipRechargeRecordIndex_exceptionHandleWin').dialog({
                title: '异常处理',
                width: 700,
                height: 180,
                closed: true,
                cache: true,
                modal: true,
                maximizable: false,
                draggable: true,
                resizable: true,
                onClose: function () {
                    $(this).dialog('clear');
                },
                buttons: [{
                    text: '确定',
                    iconCls: 'icon-ok',
                    handler: function () {
                        var form = $('#vipVipRechargeRecordIndex_exceptionHandleWin_form');
                        if(!form.form('validate')) return false;
                        $.ajax({
                            type: 'post',
                            url: vipVipRechargeRecordIndex_URL_exceptionHandle,
                            data: form.serialize(),
                            dataType: 'json',
                            success: function(data){
                                if(data.status){
                                    $.messager.show({
                                        title: '操作成功',
                                        msg: data.info
                                    });
                                    $('#vipVipRechargeRecordIndex_exceptionHandleWin').dialog('close');
                                    $('#vipVipRechargeRecordIndex_datagrid').datagrid('reload');
                                }else{
                                    $.messager.alert('操作失败',data.info,'error');
                                }
                            }
                        });
                    }
                }, {
                    text: '取消',
                    iconCls: 'icon-cancel',
                    handler: function () {
                        $('#vipVipRechargeRecordIndex_exceptionHandleWin').dialog('close');
                    }
                }]
            });
        },
        //异常处理
        exceptionHandle: function(){
            var record = $('#vipVipRechargeRecordIndex_datagrid').datagrid('getSelected');
            if(record.trade_status != 'wait_pay'){
                $.messager.show({
                    title: '已完成',
                    msg: '该充值单已经支付完成，无需再处理！'
                });
                return false;
            }
            var id = record.id;
            $('#vipVipRechargeRecordIndex_exceptionHandleWin')
                .dialog('open')
                .dialog('refresh', vipVipRechargeRecordIndex_URL_exceptionHandle + '&id=' + id);

        },
        //查询
		search: function(){
			var form = $('#vipVipRechargeRecordIndex_searchFrom');
			var data = {};
			var searchCondition = form.serializeArray(); 
			for(var i in searchCondition){
				data[searchCondition[i]['name']] = $.trim(searchCondition[i]['value']);
			}
			$('#vipVipRechargeRecordIndex_datagrid').datagrid('load',data);
		},
		//重置
		reset: function(){
			$('#vipVipRechargeRecordIndex_searchFrom').form('reset');
            vipVipRechargeRecordIndex.search();
		},
		//导出
		exportGridData: function(){
			var form = $('#vipVipRechargeRecordIndex_searchFrom');
			var searchConditionStr = form.serialize();
			window.open(vipVipRechargeRecordIndex_URL_exportGridData + "&" + searchConditionStr);
		}
	}
	
	// 执行初始化函数
	vipVipRechargeRecordIndex.init();

	
</script>