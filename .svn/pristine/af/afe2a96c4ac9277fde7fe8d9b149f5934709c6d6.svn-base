<table id="vipVipMoneyChangeIndex_datagrid"></table> 
<div id="vipVipMoneyChangeIndex_datagridToolbar">
    <div class="easyui-panel" title="检索区域" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="vipVipMoneyChangeIndex_searchFrom">
                <ul class="search-main">
					<li>
                        <div class="item-name">电卡编号</div>
                        <div class="item-input">
                           <input class="easyui-textbox" type="text" name="card_no" style="width:100%;"  />
                        </div>
                    </li>
					<li>
                        <div class="item-name">会员名称</div>
                        <div class="item-input">
                           <input class="easyui-textbox" type="text" name="vip_name" style="width:100%;"  />
                        </div>
                    </li>
					<li>
                        <div class="item-name">会员手机号</div>
                        <div class="item-input">
                           <input class="easyui-textbox" type="text" name="vip_mobile" style="width:100%;"  />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">变动原因</div>
                        <div class="item-input">
                           <input class="easyui-textbox" type="text" name="reason" style="width:100%;"  />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">变动时间</div>
                        <div class="item-input" style="width:320px">
                            <input class="easyui-datetimebox" type="text" name="start_systime" style="width:150px;"
                                   data-options=""
                                />
                            -
                            <input class="easyui-datetimebox" type="text" name="end_systime" style="width:150px;"
                                   data-options=""
                                />
                        </div>
                    </li>
                    <li class="search-button">
                        <a href="javascript:vipVipMoneyChangeIndex.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                        <a href="javascript:vipVipMoneyChangeIndex.reset()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</a>
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
                <a href="javascript:<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon'] ;?>'"><?= $val['text'] ;?></a>
            <?php } ?>
        </div>
    <?php } ?>

</div>
<!-- 窗口 -->

<!-- 窗口 -->

<script>
	// 请求的URL
	var vipVipMoneyChangeIndex_URL_getList = "<?php echo yii::$app->urlManager->createUrl(['vip/vip-money-change/get-list']); ?>";
	var vipVipMoneyChangeIndex_URL_exportGridData = "<?php echo yii::$app->urlManager->createUrl(['vip/vip-money-change/export-grid-data']); ?>";
	
	var vipVipMoneyChangeIndex = {
		// 初始化
		init: function(){
			// 列表
			$('#vipVipMoneyChangeIndex_datagrid').datagrid({  
				method: 'get', 
				url: vipVipMoneyChangeIndex_URL_getList,   
				fit: true,
				border: false,
				toolbar: "#vipVipMoneyChangeIndex_datagridToolbar",
				pagination: true,
				loadMsg: '数据加载中...',
				striped: true,
				checkOnSelect: true,
				rownumbers: true,
				singleSelect: true,
				pageSize: 20,
				frozenColumns: [[
					{field: 'ck',checkbox: true}, 
					{field: 'id',title: 'ID',width:40,align:'center',hidden:true},
                    {field: 'vip_code',title: '会员编号',width: 100,align:'center',sortable:true},
                    {field: 'card_no',title: '电卡编号',width: 120,align:'center',sortable:true},
                    {field: 'vip_name',title: '会员名称',width: 100,align:'center',sortable:true},
                    {field: 'vip_mobile',title: '会员手机号',width: 130,align:'center',sortable:true}
				]],
				columns:[[
                    {field: 'change_money',title: '变动金额',width: 90,halign:'center',align:'right',sortable:true},
                    {field: 'reason',title: '变动原由',width: 200,halign:'center',sortable:true},
                    {field: 'systime',title: '变动时间',align:'center',width: 140,sortable:true,
                        formatter: function(value,row,index){
                            return formatDateToString(value,true);
                        }
                    },
                    {field: 'note',title: '备注',width: 250,halign:'center'}
                ]]
			});	
		},		
		//获取当前选择的记录
		getCurrentSelected: function(){
			var datagrid = $('#vipVipMoneyChangeIndex_datagrid');
			var selectRow = datagrid.datagrid('getSelected');
			if(!selectRow){
				$.messager.alert('警告','请先选择要操作的记录！','warning');   
				return false;
			}
			return selectRow.id;
		},
		//查询
		search: function(){
			var form = $('#vipVipMoneyChangeIndex_searchFrom');
			var data = {};
			var searchCondition = form.serializeArray(); 
			for(var i in searchCondition){
				data[searchCondition[i]['name']] = searchCondition[i]['value'];
			}
			$('#vipVipMoneyChangeIndex_datagrid').datagrid('load',data);
		},
		//重置
		reset: function(){
			$('#vipVipMoneyChangeIndex_searchFrom').form('reset');
		},
		//导出
		exportGridData: function(){
			var form = $('#vipVipMoneyChangeIndex_searchFrom');
			var searchConditionStr = form.serialize();
			window.open(vipVipMoneyChangeIndex_URL_exportGridData + "&" + searchConditionStr);
		}
	}
	
	// 执行初始化函数
	vipVipMoneyChangeIndex.init();

	
</script>