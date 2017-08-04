<table id="icRechargeRecords_datagrid"></table> 
<div id="icRechargeRecords_datagridToolbar">
    <div class="easyui-panel" title="检索区域" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
		<form id="icRechargeRecords_searchFrom">
			<ul class="search-main">
				<li>
					<div class="item-name">充值单号</div>
					<div class="item-input">
						<input class="easyui-textbox" type="text" name="ccrr_code" style="width:100%;"
                            data-options="
                                onChange:function(){
                                    icRechargeRecords.search();
                                }
                            "
                        />
					</div>
				</li>
				<li>
					<div class="item-name">电卡编号</div>
					<div class="item-input">
						<input class="easyui-textbox" type="text" name="cc_code" style="width:100%;"
                            data-options="
                                onChange:function(){
                                    icRechargeRecords.search();
                                }
                            "
                        />
					</div>
				</li>
				<li>
					<div class="item-name">电卡类型</div>
					<div class="item-input">
						<select class="easyui-combobox" name="cc_type" style="width:100%;"
                            data-options="
                                panelHeight:'auto',
                                editable:false,
                                onChange:function(){
                                    icRechargeRecords.search();
                                }
                            "
                        >
                            <option value="" selected="selected">不限</option>
                            <?php foreach($config['cc_type'] as $val){ ?>
                                <option value="<?= $val['value'] ?>"><?= $val['text'] ?></option>
                            <?php } ?>
                        </select>
					</div>
				</li>
                <li>
                    <div class="item-name">用户名称</div>
                    <div class="item-input">
                        <input class="easyui-textbox" type="text" name="cc_holder" style="width:100%;"
                            data-options="
                                onChange:function(){
                                    icRechargeRecords.search();
                                }
                            "
                        />
                </li>
                <li>
                    <div class="item-name">充值时间</div>
                    <div class="item-input">
                        <input class="easyui-datebox" type="text" name="ccrr_create_time_start" style="width:90px;"
                            data-options="
                                onChange:function(){
                                    icRechargeRecords.search();
                                }
                            "
                        />
                        -
                        <input class="easyui-datebox" type="text" name="ccrr_create_time_end" style="width:90px;"
                            data-options="
                                onChange:function(){
                                    icRechargeRecords.search();
                                }
                            "
                        />
                    </div>
                </li>
                <li>
                    <div class="item-name">写卡状态</div>
                    <div class="item-input">
                        <select
                            class="easyui-combobox"
                            name="write_status"
                            style="width:100%;"
                            data-options="
                                panelHeight:'auto',
                                editable:false,
                                onChange:function(){
                                    icRechargeRecords.search();
                                }
                            "
                        >
                            <option value="">不限</option>
                            <option value="success">成功</option>
                            <option value="fail">失败</option>
                        </select>
                    </div>
                </li>
				<li class="search-button">
					<button class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
					<button onclick="icRechargeRecords.reset();return false;" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
				</li>
			</ul>
		</form>
        </div>
    </div>

    <?php if(!empty($buttons)){ ?>
        <div class="easyui-panel" title="充值记录" style="padding:3px 2px;width:100%;" data-options="
        iconCls: 'icon-table-list',
        border: false
    ">
            <?php foreach($buttons as $val){ ?>
                <button onclick="<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon'] ;?>'"><?= $val['text'] ;?></button>
            <?php } ?>
        </div>
    <?php } ?>

</div>
	
<script>
    var icRechargeRecords = {
        // 配置项
        'CONFIG': <?php echo json_encode($config); ?>,
        // 请求的URl
        'URL':{
            getIcRechargeRecords: "<?php echo yii::$app->urlManager->createUrl(['card/recharge/get-ic-recharge-records']); ?>",
            exportGridData: "<?php echo yii::$app->urlManager->createUrl(['card/recharge/export-grid-data']); ?>"
        },
        // 初始化函数
        init: function(){
            $('#icRechargeRecords_datagrid').datagrid({
                method: 'get',
                url: icRechargeRecords.URL.getIcRechargeRecords,
				toolbar: "#icRechargeRecords_datagridToolbar",
                fit:true,
                border: false,
                pagination: true,
                loadMsg: '数据加载中...',
                striped: true,
                checkOnSelect: true,
                rownumbers: true,
                singleSelect: false,
                pageSize: 20,
                showFooter: true,
                frozenColumns: [[
                    {field: 'ck', checkbox: true},
                    {field: 'ccrr_code', title: '充值单号', width: 110,align: 'center',sortable: true},
                ]],
                columns: [[
                    {field: 'cc_code', title: '电卡编号', width: 120, align: 'center', sortable: true},
                    {field: 'cc_type', title: '电卡类型', width: 80, align: 'center', sortable: true,
                        formatter:function(value){
                            try {
                                var str = 'icRechargeRecords.CONFIG.cc_type.' + value + '.text';
                                return eval(str);
                            } catch (e) {
                                return value;
                            }
                        }
                    },
                    {field: 'ccrr_recharge_money', title: '充值金额(元)', width: 90, halign: 'center',align:'right', sortable: true},
                    {field: 'ccrr_incentive_money', title: '奖励金额(元)', width: 90, halign: 'center',align:'right', sortable: true},
                    {field: 'ccrr_before_money', title: '充值前余额(元)', width: 90, halign: 'center',align:'right', sortable: true},
                    {field: 'ccrr_after_money', title: '充值后余额(元)', width: 100, halign: 'center',align:'right', sortable: true},
                    {field: 'ccrr_create_time', title: '充值时间', width: 130, align: 'center',sortable: true},
                    {field: 'write_status',title: '写卡状态',width: 80, align: 'center',sortable: true,
                        formatter: function(value){
                            if(value == 'success'){
                                return '<span style="background-color:#05CD69;color:#fff;padding:2px 5px;">成功</span>';
                            }else if(value == 'fail'){
                                return '<span style="background-color:#FF4040;color:#fff;padding:2px 5px;">失败</span>'
                            }
                    }},
                    {field: 'ccrr_creator', title: '操作人员', width: 90, align: 'center', sortable: true},
                    {field: 'ccrr_mark', title: '备注', width: 130, halign: 'center', sortable: true}
                ]]
            });
            //查询表单提交
            $('#icRechargeRecords_searchFrom').submit(function(){
                var grid = $('#icRechargeRecords_datagrid');
                var data = {};
                var searchCondition = $(this).serializeArray();
                for(var i in searchCondition){
                    data[searchCondition[i]['name']] = searchCondition[i]['value'];
                }
                grid.datagrid('load',data);
                return false;
            });
        },
        // 查询
        search: function(){
			$('#icRechargeRecords_searchFrom').submit();
        },
        // 重置
        reset: function(){
			var form = $('#icRechargeRecords_searchFrom');
            form.form('reset');
            form.submit();
        },
        // 导出Excel
        exportGridData: function(){
            var searchConditionStr = $('#icRechargeRecords_searchFrom').serialize();
            window.open(icRechargeRecords.URL.exportGridData + "&" + searchConditionStr);
        }
    }

    // 执行初始化函数
    icRechargeRecords.init();

</script>