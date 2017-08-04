<table id="icRecords_datagrid"></table> 
<div id="icRecords_datagridToolbar">
    <div class="easyui-panel" title="检索区域" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
		<form id="icRecords_searchFrom">
			<ul class="search-main">
				<li>
					<div class="item-name">交易流水号</div>
					<div class="item-input">
						<input class="easyui-textbox" type="text" name="DEAL_NO" style="width:100%;"
                            data-options="
                                onChange:function(){
                                    icRecords.search();
                                }
                            "
                        />
					</div>
				</li>
				<li>
					<div class="item-name">电卡编号</div>
					<div class="item-input">
						<input class="easyui-textbox" type="text" name="START_CARD_NO" style="width:100%;"
                            data-options="
                                onChange:function(){
                                    icRecords.search();
                                }
                            "
                        />
					</div>
				</li>
				<li>
					<div class="item-name">状态</div>
					<div class="item-input">
						<select class="easyui-combobox" name="end_status" style="width:100%;"
                            data-options="
                                panelHeight:'auto',
                                editable:false,
                                onChange:function(){
                                    icRecords.search();
                                }
                            "
                        >
                            <option value="">不限</option>
                            <option value="1">正在充电</option>
                            <option value="2">结束正常</option>
                            <option value="3">结束异常</option>
                        </select>
					</div>
				</li>
                <li>
                    <div class="item-name">充电时间</div>
                    <div class="item-input">
                        <input class="easyui-datebox" type="text" name="DEAL_START_DATE_start" style="width:90px;"
                            data-options="
                                onChange:function(){
                                    icRecords.search();
                                }
                            "
                        />
                        -
                        <input class="easyui-datebox" type="text" name="DEAL_START_DATE_end" style="width:90px;"
                            data-options="
                                onChange:function(){
                                    icRecords.search();
                                }
                            "
                        />
                    </div>
                </li>
				<li class="search-button">
					<a onclick="javascript:icRecords.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
					<a onclick="javascript:icRecords.reset()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</a>
				</li>
			</ul>
		</form>
        </div>
    </div>

    <?php if(!empty($buttons)){ ?>
        <div class="easyui-panel" title="记录列表" style="padding:3px 2px;width:100%;" data-options="
        iconCls: 'icon-table-list',
        border: false
    ">
            <?php foreach($buttons as $val){ ?>
                <a onclick="javascript:<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon'] ;?>'"><?= $val['text'] ;?></a>
            <?php } ?>
        </div>
    <?php } ?>

</div>
	
<script>
    var icRecords = {
        // 请求的URl
        'URL':{
            getIcRecords: "<?php echo yii::$app->urlManager->createUrl(['card/charge-record/get-ic-records']); ?>",
            exportGridData: "<?php echo yii::$app->urlManager->createUrl(['card/charge-record/export-grid-data']); ?>"
        },
        // 初始化函数
        init: function(){
            $('#icRecords_datagrid').datagrid({
                method: 'get',
                url: icRecords.URL.getIcRecords,
				toolbar: "#icRecords_datagridToolbar",
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
//                    {field: 'TRM_NO', title: '终端号', width: 60, align: 'center', sortable: true},
                    {field: 'CAR_NO', title: '车号', width: 50, align: 'center', sortable: true},
                    {field: 'INNER_ID', title: '测量点', width: 50, align: 'center', sortable: true},
                    {field: 'TIME_TAG', title: '记录时间', width: 130, align: 'center', sortable: true}
                ]]
            });
        },
        // 查询
        search: function(){
			var form = $('#icRecords_searchFrom');
			var grid = $('#icRecords_datagrid');
            var data = {};
            var searchCondition = form.serializeArray();
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = searchCondition[i]['value'];
            }
            grid.datagrid('load',data);
        },
        // 重置
        reset: function(){
			var form = $('#icRecords_searchFrom');
            form.form('reset');
            icRecords.search();
        },
        // 导出Excel
        exportGridData: function(){
            var searchConditionStr = $('#icRecords_searchFrom').serialize();
            window.open(icRecords.URL.exportGridData + "&" + searchConditionStr);
        }

    }

    // 执行初始化函数
    icRecords.init();

</script>