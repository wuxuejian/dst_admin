<table id="CarTrialProtocolDetailsIndex_datagrid"></table> 
<div id="CarTrialProtocolDetailsIndex_datagridToolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="CarTrialProtocolDetailsIndex_searchForm">
                <ul class="search-main">
                    <li>
                        <div class="item-name">车牌号</div>
                        <div class="item-input">
                            <input name="plate_number" style="width:200px;" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">协议编号</div>
                        <div class="item-input">
                            <input name="ctp_number" style="width:200px;" />
                        </div>
                    </li>
					<li>
						<div class="item-name">试用客户</div>
						<div class="item-input">
							<input name="customer_name" style="width:200px;" />
						</div>
					</li>
                    <li>
                        <div class="item-name">客户类型</div>
                        <div class="item-input">
                            <input name="customer_type" style="width:100%;" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">试用状态</div>
                        <div class="item-input">
                            <input name="trial_status" style="width:100%;" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">试用日期</div>
                        <div class="item-input-datebox">
                            <input class="easyui-datebox" type="text" name="ctp_start_date" style="width:90px;"  /> -
                            <input class="easyui-datebox" type="text" name="ctp_end_date" style="width:90px;"  />
                        </div>
                    </li>
                    <li class="search-button">
                        <button  class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button onclick="CarTrialProtocolDetailsIndex.resetForm();" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
                    </li>
                </ul>
            </form>
        </div>
    </div>

    <?php if($buttons){ ?>
        <div class="easyui-panel" title="数据列表" style="padding:3px 2px;width:100%;" data-options="
        iconCls: 'icon-table-list',
        border: false
    ">
            <?php foreach($buttons as $val){ ?>
                <a href="javascript:<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon']; ?>'"><?= $val['text']; ?></a>
            <?php } ?>
        </div>
    <?php } ?>

</div>

<script>
	// 请求的URL
	//var CarTrialProtocolDetailsIndex.URL.getList = "<?php echo yii::$app->urlManager->createUrl(['car/trial-protocol-details/get-list']); ?>";
	//var CarTrialProtocolDetailsIndex.URL.exportWidthCondition = "<?php echo yii::$app->urlManager->createUrl(['car/trial-protocol-details/export-width-condition']);?>";

	var CarTrialProtocolDetailsIndex = {
        'CONFIG': <?php echo json_encode($config); ?>, //配置数据
        'URL':{
            getList: "<?php echo yii::$app->urlManager->createUrl(['car/trial-protocol-details/get-list']); ?>",
            exportWidthCondition: "<?php echo yii::$app->urlManager->createUrl(['car/trial-protocol-details/export-width-condition']);?>"
        },
        init: function(){
			$('#CarTrialProtocolDetailsIndex_datagrid').datagrid({  
				method: 'get', 
				url: CarTrialProtocolDetailsIndex.URL.getList,   
				toolbar: "#CarTrialProtocolDetailsIndex_datagridToolbar",
				fit: true,
				border: false,
				pagination: true,
				loadMsg: '数据加载中...',
				striped: true,
				checkOnSelect: true,
				rownumbers: true,
				singleSelect: false,
				pageSize: 20,
				frozenColumns: [[
					{field: 'ck',checkbox: true}, 
					{field: 'ctpd_id',title: '明细ID',width: 50,hidden:true}, 
					{field: 'plate_number',title: '车牌号',width: 70,align: 'center',sortable: true}
				]],
				columns: [
					[
						{title: '试用协议详情',colspan:6}, // 跨几列
						{field: 'ctpd_deliver_date',title: '交车时间',width: 80,align: 'center',rowspan:2,sortable: true}, // 跨几行
                        {field: 'trial_status',title: '试用状态',rowspan:2,width: 70,align: 'center',
                            formatter: function(value,row,index){
                                var ctpd_back_date = row.ctpd_back_date;
                                if(ctpd_back_date){
                                    return '<span style="background-color:#05CD69;color:#fff;padding:2px 5px;">已还车</span>';
                                }else{
                                    return '<span style="background-color:#FFCC01;color:#fff;padding:2px 5px;">试用中</span>';
                                }

                            }
                        },
                        {field: 'ctpd_back_date',title: '还车时间',width: 80,align: 'center',rowspan:2,sortable: true},
						{field: 'ctpd_note',title: '备注',width: 200,halign: 'center',rowspan:2}
					],
					[
						{field: 'ctp_number',title: '协议编号',width: 120,halign: 'center',sortable: true},
						{field: 'customer_name',title: '试用客户',width: 200,halign: 'center',sortable: true,
                            formatter: function(value,row,index){ //企业/个人客户名称
                                if(row.cCustomer_name){
                                    return row.cCustomer_name;
                                }else if(row.pCustomer_name){
                                    return row.pCustomer_name;
                                }else{
                                    return '';
                                }
                            }
                        },
                        {field: 'customer_type',title: '客户类型',width: 70, align: 'center',sortable: true,
                            formatter: function(value){
                                try{
                                    var str = 'CarTrialProtocolDetailsIndex.CONFIG.customer_type.' + value + '.text';
                                    return eval(str);
                                }catch(e){
                                    return value;
                                }
                            }
                        },
                        {field: 'ctp_sign_date',title: '签订日期',width: 80,align: 'center',sortable: true},
						{field: 'ctp_start_date',title: '开始时间',width: 80,align: 'center',sortable: true},
						{field: 'ctp_end_date',title: '结束时间',width: 80,align: 'center',sortable: true},
					]
				],
                onLoadSuccess: function (data){
                    $(this).datagrid('doCellTip',{
                        position : 'bottom',
                        maxWidth : '300px',
                        onlyShowInterrupt : true,
                        specialShowFields : [     
                            {field : 'action',showField : 'action'}
                        ],
                        tipStyler : {            
                            'backgroundColor' : '#E4F0FC',
                            borderColor : '#87A9D0',
                            boxShadow : '1px 1px 3px #292929'
                        }
                    });
                }
			});	
            //构建查询表单
            var searchForm = $('#CarTrialProtocolDetailsIndex_searchForm');
            searchForm.submit(function(){
                var data = {};
                var searchCondition = $(this).serializeArray();
                for(var i in searchCondition){
                    data[searchCondition[i]['name']] = searchCondition[i]['value'];
                }
                $('#CarTrialProtocolDetailsIndex_datagrid').datagrid('load',data);
                return false;
            });
            searchForm.find('input[name=plate_number]').textbox({
                onChange: function(){
                    $('#CarTrialProtocolDetailsIndex_searchForm').submit();
                }
            });
            searchForm.find('input[name=ctp_number]').textbox({
                onChange: function(){
                    $('#CarTrialProtocolDetailsIndex_searchForm').submit();
                }
            });
            searchForm.find('input[name=customer_name]').textbox({
                onChange: function(){
                    $('#CarTrialProtocolDetailsIndex_searchForm').submit();
                }
            });
            searchForm.find('input[name=customer_type]').combobox({
                valueField:'value',
                textField:'text',
                data: <?= json_encode($searchFormOptions['customer_type']); ?>,
                editable: false,
                panelHeight:'auto',
                onSelect: function(){
                    searchForm.submit();
                }
            });
            searchForm.find('input[name=trial_status]').combobox({
                valueField:'value',
                textField:'text',
                editable: false,
                panelHeight:'auto',
                data: [{"value": '',"text": '不限'},{"value": 'INTRIAL',"text": '试用中'},{"value": 'BACKED',"text": '已还车'}],
                onSelect: function(){
                    searchForm.submit();
                }
            });
            //构建查询表单结束
		},
		//获取当前选择的行记录
		getCurrentSelected: function(multiline){
			var datagrid = $('#CarTrialProtocolDetailsIndex_datagrid');
			if(multiline){
				var selectRows = datagrid.datagrid('getSelections');
				if(selectRows.length <= 0){
					$.messager.show({
						title: '请选择',
						msg: '请选择要操作的记录'
					});   
					return false;
				}
				return selectRows;
			}else{
				var selectRow = datagrid.datagrid('getSelected');
				if(!selectRow){
					$.messager.show({
						title: '请选择',
						msg: '请选择要操作的记录'
					});   
					return false;
				}
				return selectRow;
			}	
		},
		//按条件导出
		exportWidthCondition: function(){		
			var searchConditionStr = $('#CarTrialProtocolDetailsIndex_searchForm').serialize();
			var _url = CarTrialProtocolDetailsIndex.URL.exportWidthCondition + '&' + searchConditionStr;
			window.open(_url);
		},	
		//重置
		resetForm: function(){
			$("#CarTrialProtocolDetailsIndex_searchForm").form('reset');
		}
	}
	
	// 执行初始化函数
	CarTrialProtocolDetailsIndex.init();

</script>