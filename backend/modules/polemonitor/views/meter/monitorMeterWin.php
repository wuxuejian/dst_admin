<table id="PolemonitorIndexScanByFrontMachine_monitorMeterWin_datagrid"></table> 
<div id="PolemonitorIndexScanByFrontMachine_monitorMeterWin_datagridToolbar"></div>

<div id="PolemonitorIndexScanByFrontMachine_monitorMeterWin_tabs" class="easyui-tabs" data-options="fit:true" style="width:100%;height:auto;">
    <div id="monitorMeterWin_tabs_meterElectricityTab" title="电表电量" iconCls="icon-chart-pie">
        <table id="monitorMeterWin_tabs_meterElectricityTab_datagrid"></table>
    </div>
    <div id="monitorMeterWin_tabs_meterInstantaneousFlowTab" title="电表瞬时量" iconCls="icon-chart-pie">
        <table id="monitorMeterWin_tabs_meterInstantaneousFlowTab_datagrid"></table>
    </div>
    <div id="monitorMeterWin_tabs_meterHarmonicVoltageTab" title="电表1-31次谐波电压数据" iconCls="icon-chart-pie">
        <table id="monitorMeterWin_tabs_meterHarmonicVoltageTab_datagrid"></table>
    </div>
    <div id="monitorMeterWin_tabs_meterHarmonicCurrentTab" title="电表1-31次谐波电流数据" iconCls="icon-chart-pie">
        <table id="monitorMeterWin_tabs_meterHarmonicCurrentTab_datagrid"></table>
    </div>
</div>

<script>
    // 初始数据
    var monitorMeterWin_initDatas = <?php echo json_encode($initDatas); ?>;

    // 拼接好列表查询数据时所需的查询参数：当前前置机id 和 当前充电设备id
    var monitorMeterWin_queryStr = '&fmId=' + monitorMeterWin_initDatas.fmId +
                                    '&devId=' + monitorMeterWin_initDatas.devId;

    var PolemonitorIndexScanByFrontMachine_monitorMeterWin = {
		init: function(){
			// 获取电表列表数据
			$('#PolemonitorIndexScanByFrontMachine_monitorMeterWin_datagrid').datagrid({
				title:'电表',collapsible:true,iconCls:'icon-dashboard',
                method: 'get',
				url:"<?php echo yii::$app->urlManager->createUrl(['polemonitor/meter/monitor-meter-get-list']); ?>" + monitorMeterWin_queryStr,
				fit: false,height:'200',width:'100%',
				border: false,
				pagination: true,
				loadMsg: '数据加载中...',
				striped: true,
				checkOnSelect: true,
				rownumbers: true,
				singleSelect: true,
				frozenColumns: [[
					{field: 'ck',checkbox: true}, 
					{field: 'METER_ID',title: '电表ID',align: 'center',hidden: false,sortable: true},
					{field: 'METER_NAME',title: '电表名称',width: 150,align: 'center',sortable: true},
				]],
				columns:[[
					{field: 'METER_ADDR',title: '电表地址',width: 80,align: 'center',sortable: true},
					{field: 'METER_MODEL',title: '电表型号',width: 80,align: 'center',sortable: true},
					{field: 'PT1',title: 'PT1',width: 80,halign:'center',align: 'right',sortable: true},
					{field: 'PT2',title: 'PT2',width: 80,halign:'center',align: 'right',sortable: true},
					{field: 'CT1',title: 'CT1',width: 80,halign:'center',align: 'right',sortable: true},
					{field: 'CT2',title: 'CT2',width: 80,halign:'center',align: 'right',sortable: true},
					{field: 'PRTL_TYPE',title: '协议类型',width: 80,align: 'center',sortable: true},
					{field: 'CHN_ID',title: '通道ID',width: 80,align: 'center',sortable: true},
					{field: 'METER_TYPE',title: '电表类型',width: 80,align: 'center',sortable: true},
					{field: 'Rated_voltage',title: '最低功率',width: 80,halign:'center',align: 'right',sortable: true},
					{field: 'Rated_current',title: '当前功率',width: 80,halign:'center',align: 'right',sortable: true},
					{field: 'Rated_power',title: '额定功率',width: 80,halign:'center',align: 'right',sortable: true},
					{field: 'Max_power',title: '最大功率',width: 80,halign:'center',align: 'right',sortable: true},
					{field: 'BLG_TYPE',title: '是否总表',width: 80,align: 'center',sortable: true},
					{field: 'BLG_DEV_ID',title: '所属设备ID',width: 80,align: 'center',sortable: true},
                    {field: 'INNER_ID',title: '表在充电设备内部编号',width: 100,align: 'center',sortable: true}
				]],
                onLoadSuccess: function(data){
                    if (data.errInfo) {
                        $.messager.show({
                            title:'获取电表监控数据失败',
                            msg: '<span style="color:red;">' + data.errInfo + '</span>'
                        });
                    }
                },
                onClickRow: function(rowIndex, rowData){ // 单击某行的电表时底部各tab都重新查询关联数据
                    var meterId = rowData.METER_ID;
                    var data = {'meterId':meterId,'fmId':monitorMeterWin_initDatas.fmId};
                    $('#monitorMeterWin_tabs_meterElectricityTab_datagrid').datagrid('load',data);
                    $('#monitorMeterWin_tabs_meterInstantaneousFlowTab_datagrid').datagrid('load',data);
                    $('#monitorMeterWin_tabs_meterHarmonicVoltageTab_datagrid').datagrid('load',data);
                    $('#monitorMeterWin_tabs_meterHarmonicCurrentTab_datagrid').datagrid('load',data);
                }
			});
			// 【电表电量tab】获取列表数据
			$('#monitorMeterWin_tabs_meterElectricityTab_datagrid').datagrid({
				method: 'get',
				url:"<?php echo yii::$app->urlManager->createUrl(['polemonitor/meter/get-meter-electricity-list']); ?>",
                //fit: true,
                fit: false,height:300,width:'100%',
				border: false,
				pagination: true,
				loadMsg: '数据加载中...',
				striped: true,
				checkOnSelect: true,
				rownumbers: true,
				singleSelect: true,
				frozenColumns: [[
					{field: 'ck',checkbox: true},
					{field: 'METER_ID',title: '电表ID',align: 'center',hidden: false},
					{field: 'TIME_TAG',title: '数据时间',width: 150,align: 'center',sortable: true},
				]],
				columns:[[
					{field: 'TYPE_FLG',title: '类型',width: 80,align: 'center',sortable: true},

                    {field: 'ZXYGZ',title: '正向有功总示度',width: 100,halign:'center',align: 'right',sortable: true},
					{field: 'FXYGZ',title: '反向有功总示度',width: 100,halign:'center',align: 'right',sortable: true},
					{field: 'ZXWGZ',title: '正向无功总示度',width: 100,halign:'center',align: 'right',sortable: true},
					{field: 'FXWGZ',title: '反向无功总示度',width: 100,halign:'center',align: 'right',sortable: true},

                    {field: 'ZXYG1',title: '正向有功费率1示度(尖)',width: 150,halign:'center',align: 'right',sortable: true},
                    {field: 'FXYG1',title: '反向有功费率1示度(尖)',width: 150,halign:'center',align: 'right',sortable: true},
                    {field: 'ZXWG1',title: '正向无功费率1示度(尖)',width: 150,halign:'center',align: 'right',sortable: true},
                    {field: 'FXWG1',title: '反向无功费率1示度(尖)',width: 150,halign:'center',align: 'right',sortable: true},

                    {field: 'ZXYG2',title: '正向有功费率2示度(峰)',width: 150,halign:'center',align: 'right',sortable: true},
                    {field: 'FXYG2',title: '反向有功费率2示度(峰)',width: 150,halign:'center',align: 'right',sortable: true},
                    {field: 'ZXWG2',title: '正向无功费率2示度(峰)',width: 150,halign:'center',align: 'right',sortable: true},
                    {field: 'FXWG2',title: '反向无功费率2示度(峰)',width: 150,halign:'center',align: 'right',sortable: true},

                    {field: 'ZXYG3',title: '正向有功费率3示度(平)',width: 150,halign:'center',align: 'right',sortable: true},
                    {field: 'FXYG3',title: '反向有功费率3示度(平)',width: 150,halign:'center',align: 'right',sortable: true},
                    {field: 'ZXWG3',title: '正向无功费率3示度(平)',width: 150,halign:'center',align: 'right',sortable: true},
                    {field: 'FXWG3',title: '反向无功费率3示度(平)',width: 150,halign:'center',align: 'right',sortable: true},

                    {field: 'ZXYG4',title: '正向有功费率4示度(谷)',width: 150,halign:'center',align: 'right',sortable: true},
                    {field: 'FXYG4',title: '反向有功费率4示度(谷)',width: 150,halign:'center',align: 'right',sortable: true},
                    {field: 'ZXWG4',title: '正向无功费率4示度(谷)',width: 150,halign:'center',align: 'right',sortable: true},
                    {field: 'FXWG4',title: '反向无功费率4示度(谷)',width: 150,halign:'center',align: 'right',sortable: true},

					{field: 'WRITE_TIME',title: '写库时间',width: 150,align: 'center',sortable: true}
				]]
			});
			// 【电表瞬时量tab】获取列表数据
			$('#monitorMeterWin_tabs_meterInstantaneousFlowTab_datagrid').datagrid({
				method: 'get',
				url:"<?php echo yii::$app->urlManager->createUrl(['polemonitor/meter/get-meter-instantaneous-flow-list']); ?>",
                //fit: true,
                fit: false,height:300,width:'100%',
				border: false,
				pagination: true,
				loadMsg: '数据加载中...',
				striped: true,
				checkOnSelect: true,
				rownumbers: true,
				singleSelect: true,
				frozenColumns: [[
					{field: 'ck',checkbox: true},
					{field: 'METER_ID',title: '电表ID',align: 'center',hidden: false},
					{field: 'TIME_TAG',title: '数据时间',width: 150,align: 'center',sortable: true},
				]],
				columns:[[
                    {field: 'Ua',title: 'A相电压',width: 80,halign:'center',align: 'right',sortable: true},
					{field: 'Ub',title: 'B相电压',width: 80,halign:'center',align: 'right',sortable: true},
					{field: 'Uc',title: 'C相电压',width: 80,halign:'center',align: 'right',sortable: true},
                    {field: 'Ia',title: 'A相电流',width: 80,halign:'center',align: 'right',sortable: true},
					{field: 'Ib',title: 'B相电流',width: 80,halign:'center',align: 'right',sortable: true},
					{field: 'Ic',title: 'C相电流',width: 80,halign:'center',align: 'right',sortable: true},

                    {field: 'Pz',title: '总有功功率',width: 80,halign:'center',align: 'right',sortable: true},
					{field: 'Pa',title: 'A相有功功率',width: 80,halign:'center',align: 'right',sortable: true},
					{field: 'Pb',title: 'B相有功功率',width: 80,halign:'center',align: 'right',sortable: true},
					{field: 'Pc',title: 'C相有功功率',width: 80,halign:'center',align: 'right',sortable: true},
                    {field: 'Qz',title: '总无功功率',width: 80,halign:'center',align: 'right',sortable: true},
					{field: 'Qa',title: 'A相无功功率',width: 80,halign:'center',align: 'right',sortable: true},
					{field: 'Qb',title: 'B相无功功率',width: 80,halign:'center',align: 'right',sortable: true},
					{field: 'Qc',title: 'C相无功功率',width: 80,halign:'center',align: 'right',sortable: true},
                    {field: 'COS',title: '总功率因数',width: 80,halign:'center',align: 'right',sortable: true},
					{field: 'COSA',title: 'A相功率因数',width: 80,halign:'center',align: 'right',sortable: true},
					{field: 'COSB',title: 'B相功率因数',width: 80,halign:'center',align: 'right',sortable: true},
					{field: 'COSC',title: 'C相功率因数',width: 80,halign:'center',align: 'right',sortable: true},

					{field: 'F',title: '频率',width: 60,align: 'center',sortable: true},
					{field: 'WRITE_TIME',title: '写库时间',width: 150,align: 'center',sortable: true}
				]]
			});
			// 【电表1-31次谐波电压数据】获取列表数据
			$('#monitorMeterWin_tabs_meterHarmonicVoltageTab_datagrid').datagrid({
				method: 'get',
				url:"<?php echo yii::$app->urlManager->createUrl(['polemonitor/meter/get-meter-harmonic-voltage-list']); ?>",
                //fit: true,
                fit: false,height:300,width:'100%',
				border: false,
				pagination: true,
				loadMsg: '数据加载中...',
				striped: true,
				checkOnSelect: true,
				rownumbers: true,
				singleSelect: true,
				frozenColumns: [[
					{field: 'ck',checkbox: true},
					{field: 'METER_ID',title: '电表ID',align: 'center',hidden: false},
					{field: 'TIME_TAG',title: '数据时间',width: 150,align: 'center',sortable: true},
				]],
				columns:[[
                        {title: '1次谐波畸变率',colspan:3},
                        {title: '2次谐波畸变率',colspan:3},
                        {title: '3次谐波畸变率',colspan:3},
                        {title: '4次谐波畸变率',colspan:3},
                        {title: '5次谐波畸变率',colspan:3},
                        {title: '29次谐波畸变率',colspan:3},
                        {title: '30次谐波畸变率',colspan:3},
                        {title: '31次谐波畸变率',colspan:3},
                        {field: 'WRITE_TIME',title: '写库时间',width: 150,align: 'center',sortable: true,rowspan:2}
                    ],
                    [
                        {field: 'Ua_1',title: 'A相电压',width: 80,halign:'center',align: 'right',sortable: true},
                        {field: 'Ub_1',title: 'B相电压',width: 80,halign:'center',align: 'right',sortable: true},
                        {field: 'Uc_1',title: 'C相电压',width: 80,halign:'center',align: 'right',sortable: true},
                        {field: 'Ua_2',title: 'A相电压',width: 80,halign:'center',align: 'right',sortable: true},
                        {field: 'Ub_2',title: 'B相电压',width: 80,halign:'center',align: 'right',sortable: true},
                        {field: 'Uc_2',title: 'C相电压',width: 80,halign:'center',align: 'right',sortable: true},
                        {field: 'Ua_3',title: 'A相电压',width: 80,halign:'center',align: 'right',sortable: true},
                        {field: 'Ub_3',title: 'B相电压',width: 80,halign:'center',align: 'right',sortable: true},
                        {field: 'Uc_3',title: 'C相电压',width: 80,halign:'center',align: 'right',sortable: true},
                        {field: 'Ua_4',title: 'A相电压',width: 80,halign:'center',align: 'right',sortable: true},
                        {field: 'Ub_4',title: 'B相电压',width: 80,halign:'center',align: 'right',sortable: true},
                        {field: 'Uc_4',title: 'C相电压',width: 80,halign:'center',align: 'right',sortable: true},
                        {field: 'Ua_5',title: 'A相电压',width: 80,halign:'center',align: 'right',sortable: true},
                        {field: 'Ub_5',title: 'B相电压',width: 80,halign:'center',align: 'right',sortable: true},
                        {field: 'Uc_5',title: 'C相电压',width: 80,halign:'center',align: 'right',sortable: true},
                        {field: 'Ua_29',title: 'A相电压',width: 80,halign:'center',align: 'right',sortable: true},
                        {field: 'Ub_29',title: 'B相电压',width: 80,halign:'center',align: 'right',sortable: true},
                        {field: 'Uc_29',title: 'C相电压',width: 80,halign:'center',align: 'right',sortable: true},
                        {field: 'Ua_30',title: 'A相电压',width: 80,halign:'center',align: 'right',sortable: true},
                        {field: 'Ub_30',title: 'B相电压',width: 80,halign:'center',align: 'right',sortable: true},
                        {field: 'Uc_30',title: 'C相电压',width: 80,halign:'center',align: 'right',sortable: true},
                        {field: 'Ua_31',title: 'A相电压',width: 80,halign:'center',align: 'right',sortable: true},
                        {field: 'Ub_31',title: 'B相电压',width: 80,halign:'center',align: 'right',sortable: true},
                        {field: 'Uc_31',title: 'C相电压',width: 80,halign:'center',align: 'right',sortable: true}
				]]
			});
			// 【电表1-31次谐波电流数据】获取列表数据
			$('#monitorMeterWin_tabs_meterHarmonicCurrentTab_datagrid').datagrid({
				method: 'get',
				url:"<?php echo yii::$app->urlManager->createUrl(['polemonitor/meter/get-meter-harmonic-current-list']); ?>",
                //fit: true,
                fit: false,height:300,width:'100%',
				border: false,
				pagination: true,
				loadMsg: '数据加载中...',
				striped: true,
				checkOnSelect: true,
				rownumbers: true,
				singleSelect: true,
				frozenColumns: [[
					{field: 'ck',checkbox: true},
					{field: 'METER_ID',title: '电表ID',align: 'center',hidden: false},
					{field: 'TIME_TAG',title: '数据时间',width: 150,align: 'center',sortable: true},
				]],
				columns:[[
                        {title: '1次谐波畸变率',colspan:3},
                        {title: '2次谐波畸变率',colspan:3},
                        {title: '3次谐波畸变率',colspan:3},
                        {title: '4次谐波畸变率',colspan:3},
                        {title: '5次谐波畸变率',colspan:3},
                        {title: '29次谐波畸变率',colspan:3},
                        {title: '30次谐波畸变率',colspan:3},
                        {title: '31次谐波畸变率',colspan:3},
                        {field: 'WRITE_TIME',title: '写库时间',width: 150,align: 'center',sortable: true,rowspan:2}
                    ],
                    [
                        {field: 'Ia_1',title: 'A相电流',width: 80,halign:'center',align: 'right',sortable: true},
                        {field: 'Ib_1',title: 'B相电流',width: 80,halign:'center',align: 'right',sortable: true},
                        {field: 'Ic_1',title: 'C相电流',width: 80,halign:'center',align: 'right',sortable: true},
                        {field: 'Ia_2',title: 'A相电流',width: 80,halign:'center',align: 'right',sortable: true},
                        {field: 'Ib_2',title: 'B相电流',width: 80,halign:'center',align: 'right',sortable: true},
                        {field: 'Ic_2',title: 'C相电流',width: 80,halign:'center',align: 'right',sortable: true},
                        {field: 'Ia_3',title: 'A相电流',width: 80,halign:'center',align: 'right',sortable: true},
                        {field: 'Ib_3',title: 'B相电流',width: 80,halign:'center',align: 'right',sortable: true},
                        {field: 'Ic_3',title: 'C相电流',width: 80,halign:'center',align: 'right',sortable: true},
                        {field: 'Ia_4',title: 'A相电流',width: 80,halign:'center',align: 'right',sortable: true},
                        {field: 'Ib_4',title: 'B相电流',width: 80,halign:'center',align: 'right',sortable: true},
                        {field: 'Ic_4',title: 'C相电流',width: 80,halign:'center',align: 'right',sortable: true},
                        {field: 'Ia_5',title: 'A相电流',width: 80,halign:'center',align: 'right',sortable: true},
                        {field: 'Ib_5',title: 'B相电流',width: 80,halign:'center',align: 'right',sortable: true},
                        {field: 'Ic_5',title: 'C相电流',width: 80,halign:'center',align: 'right',sortable: true},
                        {field: 'Ia_29',title: 'A相电流',width: 80,halign:'center',align: 'right',sortable: true},
                        {field: 'Ib_29',title: 'B相电流',width: 80,halign:'center',align: 'right',sortable: true},
                        {field: 'Ic_29',title: 'C相电流',width: 80,halign:'center',align: 'right',sortable: true},
                        {field: 'Ia_30',title: 'A相电流',width: 80,halign:'center',align: 'right',sortable: true},
                        {field: 'Ib_30',title: 'B相电流',width: 80,halign:'center',align: 'right',sortable: true},
                        {field: 'Ic_30',title: 'C相电流',width: 80,halign:'center',align: 'right',sortable: true},
                        {field: 'Ia_31',title: 'A相电流',width: 80,halign:'center',align: 'right',sortable: true},
                        {field: 'Ib_31',title: 'B相电流',width: 80,halign:'center',align: 'right',sortable: true},
                        {field: 'Ic_31',title: 'C相电流',width: 80,halign:'center',align: 'right',sortable: true}
				]]
			});
		},
        //获取选择的记录。参数all = true标示是否要返回所有被选择的记录
        getCurrentSelected: function (all) {
            var datagrid = $('#PolemonitorIndexScanByFrontMachine_monitorMeterWin_datagrid');
            var selectRows = datagrid.datagrid('getSelections');
            if (selectRows.length <= 0) {
                $.messager.show({
                    title: '请选择',
                    msg: '请选择要操作的记录！'
                });
                return false;
            }
            if (all) {
                return selectRows;
            } else {
                if (selectRows.length > 1) {
                    $.messager.show({
                        title: '提醒',
                        msg: '该功能不能批量操作！<br/>如果你选择了多条记录，则默认操作第一条记录！'
                    });
                }
                return selectRows[0];
            }
        },
		//查询
		search: function(){
			var form = $('#PolemonitorIndexScanByFrontMachine_monitorMeterWin_searchForm');
			var data = {};
			var searchCondition = form.serializeArray();
			for(var i in searchCondition){
				data[searchCondition[i]['name']] = searchCondition[i]['value'];
			}
			$('#PolemonitorIndexScanByFrontMachine_monitorMeterWin_datagrid').datagrid('load',data);
		}
	}
	// 执行初始化函数
	PolemonitorIndexScanByFrontMachine_monitorMeterWin.init();
</script>