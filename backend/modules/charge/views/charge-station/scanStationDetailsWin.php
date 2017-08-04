<div class="easyui-tabs" data-options="border:false,tabWidth:130,fit:true" >
    <!--tab页签1-->
    <div title="充电站">
        <form id="chargeChargeStationIndex_scanStationDetailsWin_form" method="post">
            <div class="easyui-panel" title="(1)基本信息" style="padding:8px 0px;"
                 data-options="collapsible:true,collapsed:false,border:false,fit:false">
                <table cellpadding="6" cellspacing="2" style="width:100%;" border="0">
                    <tr hidden>
                        <th align="right">电站ID：</th>
                        <td colspan="5">
                            <?php echo $stationInfo['cs_id']; ?>
                        </td>
                    </tr>
                    <tr>
                        <th align="right" width="10%">电站编号：</th>
                        <td width="23%">
                            <?php echo $stationInfo['cs_code']; ?>
                        </td>
                        <th align="right"  width="10%">电站类型：</th>
                        <td>
                            <?php echo $config['cs_type'][$stationInfo['cs_type']]['text']; ?>
                        </td>
                        <th align="right"  width="10%">电站状态：</th>
                        <td width="23%">
                            <?php echo $config['cs_status'][$stationInfo['cs_status']]['text']; ?>
                        </td>
                    </tr>
                    <tr>
                        <th align="right">电站名称：</th>
                        <td>
                            <?php echo $stationInfo['cs_name']; ?>
                        </td>
                        <th align="right">经纬度：</th>
                        <td colspan="3">
                            <?php echo $stationInfo['cs_lng']; ?>,<?php echo $stationInfo['cs_lat']; ?>
                        </td>
                    </tr>
                    <tr>
                        <th align="right">投运日期：</th>
                        <td>
                            <?php echo $stationInfo['cs_commissioning_date']; ?>
                        </td>
                        <th align="right" >电站位置：</th>
                        <td colspan="3">
                            <?php echo $stationInfo['cs_address']; ?>
                        </td>
                    </tr>
                    <tr>
                        <th align="right">所属前置机：</th>
                        <td colspan="5">
                            <?php echo $stationInfo['cs_fm']; ?>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="easyui-panel" title="(2)运营信息" style="padding:8px 0px;"
                 data-options="collapsible:true,collapsed:false,border:false,fit:false">
                <table cellpadding="6" cellspacing="2" style="width:100%;" border="0">
                    <tr>
                        <th align="right">是否开放：</th>
                        <td colspan="5">
                            <?php echo $stationInfo['cs_is_open'] == 1 ? '是' : '否'; ?>
                        </td>
                    </tr>
                    <tr>
                        <th align="right">使用单位：</th>
                        <td colspan="5">
                            <?php echo $stationInfo['cs_building_user']; ?>
                        </td>
                    </tr>
                    <tr>
                        <th align="right" width="10%">负责人姓名：</th>
                        <td width="23%">
                            <?php echo $stationInfo['cs_manager_name']; ?>
                        </td>
                        <th align="right" width="10%">负责人手机：</th>
                        <td>
                            <?php echo $stationInfo['cs_manager_mobile']; ?>
                        </td>
                        <th align="right" width="10%">服务电话：</th>
                        <td width="23%">
                            <?php echo $stationInfo['cs_service_telephone']; ?>
                        </td>
                    </tr>
                    <tr>
                        <th align="right" valign="top" rowspan="2">开放时间：</th>
                        <td colspan="5">
                            工作日:
                            <span style="width:80px;padding:0px 5px;"><?php echo $stationInfo['cs_opentime']['workday_s']; ?></span> 至
                            <span style="width:80px;padding:0px 5px;"><?php echo $stationInfo['cs_opentime']['workday_e']; ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5">
                            节假日:
                            <span style="width:80px;padding:0px 5px;"><?php echo $stationInfo['cs_opentime']['holiday_s']; ?></span> 至
                            <span style="width:80px;padding:0px 5px;"><?php echo $stationInfo['cs_opentime']['holiday_e']; ?></span>
                        </td>
                    </tr>
                    <tr>
                        <th align="right" valign="top" rowspan="3">充电费率：</th>
                        <td colspan="5">
                            平&nbsp;时:
                            <span style="width:80px;padding:0px 5px;"><?php echo $stationInfo['cs_powerrate']['peacetime_s']; ?></span> 至
                            <span style="width:80px;padding:0px 5px;"><?php echo $stationInfo['cs_powerrate']['peacetime_e']; ?></span>&nbsp;
                            <span style="width:80px;padding:0px 5px;"><?php echo $stationInfo['cs_powerrate']['peacetime_price']; ?></span>元/度
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5">
                            峰&nbsp;时:
                            <span style="width:80px;padding:0px 5px;"><?php echo $stationInfo['cs_powerrate']['peaktime_s']; ?></span> 至
                            <span style="width:80px;padding:0px 5px;"><?php echo $stationInfo['cs_powerrate']['peaktime_e']; ?></span>&nbsp;
                            <span style="width:80px;padding:0px 5px;"><?php echo $stationInfo['cs_powerrate']['peaktime_price']; ?></span>元/度
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5">
                            谷&nbsp;时:
                            <span style="width:80px;padding:0px 5px;"><?php echo $stationInfo['cs_powerrate']['valleytime_s']; ?></span> 至
                            <span style="width:80px;padding:0px 5px;"><?php echo $stationInfo['cs_powerrate']['valleytime_e']; ?></span>&nbsp;
                            <span style="width:80px;padding:0px 5px;"><?php echo $stationInfo['cs_powerrate']['valleytime_price']; ?></span>元/度
                        </td>
                    </tr>
                    <tr>
                        <th align="right">服务费：</th>
                        <td colspan="5">
                            <span style="width:80px;padding:0px 5px;"><?php echo $stationInfo['cs_servicefee']; ?></span>元/度（要包含损耗费用在内）
                        </td>
                    </tr>

                    <tr>
                        <th align="right" valign="top" rowspan="6">停车费：</th>
                        <td colspan="5" style="background-color:#EBDFA1;">慢充桩车位</td>
                    </tr>
                    <tr>
                        <td colspan="5">
                            首<span style="width:80px;padding:0px 5px;"><?php echo $stationInfo['cs_parkingfee']['slowpole_freetime']; ?></span>小时免费
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5">
                            时段1:
                            <span style="width:80px;padding:0px 5px;"><?php echo $stationInfo['cs_parkingfee']['slowpole_period1_s']; ?></span> 至
                            <span style="width:80px;padding:0px 5px;"><?php echo $stationInfo['cs_parkingfee']['slowpole_period1_e']; ?></span>&nbsp;
                            <span style="width:80px;padding:0px 5px;"><?php echo $stationInfo['cs_parkingfee']['slowpole_period1_price']; ?></span>元/小时
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5" style="background-color:#EBDFA1;">快充桩车位</td>
                    </tr>
                    <tr>
                        <td colspan="5">
                            首<span style="width:80px;padding:0px 5px;"><?php echo $stationInfo['cs_parkingfee']['fastpole_freetime']; ?></span>小时免费
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5">
                            时段1:
                            <span style="width:80px;padding:0px 5px;"><?php echo $stationInfo['cs_parkingfee']['fastpole_period1_s']; ?></span> 至
                            <span style="width:80px;padding:0px 5px;"><?php echo $stationInfo['cs_parkingfee']['fastpole_period1_e']; ?></span>&nbsp;
                            <span style="width:80px;padding:0px 5px;"><?php echo $stationInfo['cs_parkingfee']['fastpole_period1_price']; ?></span>元/小时
                        </td>
                    </tr>
                    <tr>
                        <th align="right" valign="top">App温馨提示：</th>
                        <td colspan="5">
                            <?php echo $stationInfo['app_tips']; ?>
                        </td>
                    </tr>
                    <tr>
                        <th align="right" valign="top">备注：</th>
                        <td colspan="5">
                            <?php echo $stationInfo['cs_mark']; ?>
                        </td>
                    </tr>
                    <tr>
                        <th align="right" valign="top">照片：</th>
                        <td colspan="5">
                            <div id="scanStationDetailsWin_stationImagesDiv">
                                <?php
                                if(isset($stationInfo['picPaths']) && !empty($stationInfo['picPaths'])){
                                    foreach($stationInfo['picPaths'] as $k=>$v){
                                        if($v){
                                            ?>
                                            <img src="<?php echo $v; ?>" id="stationImage_<?php echo $k; ?>" width="100" height="100" style="margin-right:20px;border:0px solid #DBDBDB;"/>
                                        <?php
                                        }
                                    }
                                }
                                ?>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </form>
    </div>

    <!--tab页签2-->
    <div title="充电桩">
        <table id="chargeChargeStationIndex_scanStationDetailsWin_datagrid"></table>
        <div id="chargeChargeStationIndex_scanStationDetailsWin_datagridToolbar">
            <div class="easyui-panel" id="chargeChargeStationIndex_scanStationDetailsWin_statistics"
                 title="(1)电桩概览" style="width:100%;padding:2px 10px;height:110px;"
                 data-options="border:false">
            </div>
            <div class="easyui-panel" title="(2)电桩列表" style="width:100%;" data-options="border:false"></div>
        </div>
    </div>
</div>

<script>
    // 配置数据
    var scanStationDetailsWin_CONFIG = <?= json_encode($config); ?>;
    // 请求的URl
    var scanStationDetailsWin_URL_getPolesOfStation = "<?php echo yii::$app->urlManager->createUrl(['charge/charge-station/get-poles-of-station','stationId'=>$stationInfo['cs_id']]); ?>";

    var chargeChargeStationIndex_scanStationDetailsWin = {
        // 初始化函数
        init: function(){
            //初始化表格
            $('#chargeChargeStationIndex_scanStationDetailsWin_datagrid').datagrid({
                method: 'get',
                url: scanStationDetailsWin_URL_getPolesOfStation,
                toolbar: '#chargeChargeStationIndex_scanStationDetailsWin_datagridToolbar',
                fit:true,
                border: false,
                pagination: true,
                loadMsg: '数据加载中...',
                striped: true,
                checkOnSelect: true,
                rownumbers: true,
                singleSelect: false,
                pageSize: 30,
                frozenColumns: [[
                    {field: 'ck', checkbox: true},
                    {field: 'id', title: '电桩ID', width: 40, align: 'center', hidden: true},
                    {field: 'station_id', title: '电站ID', width: 40, align: 'center', hidden: true},
                    {field: 'code_from_compony', title: '电桩编号', width: 70, align: 'center', sortable: true},
                    {field: 'charge_pattern', title: '充电模式', width: 60, align: 'center', sortable: true,
                        formatter: function (value, row, index) {
                            try {
                                var str = 'scanStationDetailsWin_CONFIG.charge_pattern.' + value + '.text';
                                var txt = eval(str);
                                if (value == 'FAST_CHARGE') {
                                    return '<span style="background-color:#05CD69;color:#fff;padding:2px;">' + txt + '</span>';
                                } else if (value == 'SLOW_CHARGE') {
                                    return '<span style="background-color:#FFD040;color:#fff;padding:2px;">' + txt + '</span>';
                                } else {
                                    return value;
                                }
                            } catch (e) {
                                return '';
                            }
                        }
                    }
                ]],
                columns:[[
                    {field: 'charge_type', title: '电桩类型', width: 100, align: 'center', sortable: true,
                        formatter: function (value, row, index) {
                            try {
                                var str = 'scanStationDetailsWin_CONFIG.charge_type.' + value + '.text';
                                return eval(str);
                            } catch (e) {
                                return '';
                            }
                        }
                    },
                    {field:'charge_gun_nums',title:'电枪个数',width:60,align:'center',sortable:true},
                    {field: 'status', title: '电桩状态', width: 130, align: 'center', sortable: true,
                        formatter: function (value, row, index) {
                            try {
                                // 千万注意：拥有单枪的电桩就只有一个状态；但是拥有双枪的电桩有两个状态（中间以英文逗号分隔）
                                var valArr = value.split(',');
                                var statusHtml = [];
                                var num = valArr.length;
                                for(var i=0; i<num; i++){
                                    var val = valArr[i];
                                    var str = 'scanStationDetailsWin_CONFIG.status[' + val + '].text';
                                    var str2 = '';
                                    var gunName = num > 1 ? (i==0 ? 'A枪' : 'B枪') : '';
                                    switch (val) {
                                        case '1':
                                            str2 = '<span style="background-color:#05CD69;color:#fff;padding:2px 5px;">' + gunName + eval(str) + '</span>'; break;
                                        case '0':
                                            str2 = '<span style="background-color:#FFCC01;color:#fff;padding:2px 5px;">' + gunName + eval(str) + '</span>'; break;
                                        case '2':
                                            str2 = '<span style="background-color:#F31F28;color:#fff;padding:2px 5px;">' + gunName + eval(str) + '</span>'; break;
                                        case '3':
                                            str2 = '<span style="background-color:#C0C0E0;color:#fff;padding:2px 5px;">' + gunName + eval(str) + '</span>'; break;
                                        case '4':
                                            str2 = '<span style="background-color:#E7E7E7;color:#fff;padding:2px 5px;">' + gunName + eval(str) + '</span>'; break;
                                        default:
                                            str2 = value;
                                    }
                                    statusHtml.push(str2);
                                }
                                return statusHtml.join(' ');
                            } catch (e) {
                                return '';
                            }
                        }
                    },
                    {field: 'station_id', title: '所属充电站id', width: 60, align: 'center',hidden:true},
                    {field: 'station_name', title: '所属充电站', width: 160, halign: 'center', sortable: true},
                    {field: 'install_site', title: '安装地点', width: 200, halign: 'center', sortable: true},
                    {field: 'install_type', title: '安装方式', align: 'center', width: 70, sortable: true,
                        formatter: function (value, row, index) {
                            try {
                                var str = 'scanStationDetailsWin_CONFIG.install_type.' + value + '.text';
                                return eval(str);
                            } catch (e) {
                                return '';
                            }
                        }
                    },
                    {field: 'install_date', title: '安装日期', align: 'center', width: 80, sortable: true},
                    {field: 'connection_type', title: '连接方式', align: 'center', width: 80, sortable: true,
                        formatter: function (value, row, index) {
                            try {
                                var str = 'scanStationDetailsWin_CONFIG.connection_type.' + value + '.text';
                                return eval(str);
                            } catch (e) {
                                return '';
                            }
                        }
                    },
                    {field: 'manufacturer', title: '生产厂家', align: 'center', width: 80, sortable: true,
                        formatter: function (value, row, index) {
                            try {
                                var str = 'scanStationDetailsWin_CONFIG.manufacturer.' + value + '.text';
                                return eval(str);
                            } catch (e) {
                                return '';
                            }
                        }
                    },
                    {field: 'code_from_factory', title: '出厂编号', width: 110, halign: 'center', sortable: true},
                    {field: 'model', title: '电桩型号', width: 70, align: 'center', sortable: true,
                        formatter: function (value, row, index) {
                            try {
                                var str = 'scanStationDetailsWin_CONFIG.model.' + value + '.text';
                                return eval(str);
                            } catch (e) {
                                return '';
                            }
                        }
                    },
                    {field: 'logic_addr', title: '逻辑地址', width: 70, align: 'center', sortable: true},
                    {field: 'mark', title: '备注', width: 150, halign: 'center', sortable: true},
                    {field: 'sysuser', title: '登记人员', align: 'center', width: 80, sortable: true}
                ]],
                onLoadSuccess: function(data){
                    // 注意：只在第一次加载数据时向电桩概览区域写入内容，保证后期无论怎么重新加载时都不会改变此区域内容。
                    var isFirstLoad = data.isFirstLoad;
                    if(parseInt(isFirstLoad)){
                        var htmlStr = '';
                        var statistics = data.statistics;
                        if(typeof(statistics) == 'object' && !(statistics instanceof Array)){
                            for(var i in statistics){
                                var item = statistics[i];
                                var detailStr = '';
                                var sumNum = 0;
                                for(var k in item){
                                    sumNum += item[k];
                                    // 注意：拥有单枪的电桩就只有一个状态；但是拥有双枪的电桩有两个状态（中间以英文逗号分隔）
                                    var valArr = k.split(',');
                                    if(valArr.length == 1){
                                        detailStr += '[' + eval('scanStationDetailsWin_CONFIG.status[' + valArr[0] + '].text') + ']<span style="color:red;font-weight:bold;">' + item[k] + '</span>个；';
                                    }else{
                                        detailStr += '[' + eval('scanStationDetailsWin_CONFIG.status[' + valArr[0] + '].text') + '+' + eval('scanStationDetailsWin_CONFIG.status[' + valArr[1] + '].text') + ']<span style="color:red;font-weight:bold;">' + item[k] + '</span>个；';
                                    }
                                }
                                if(i == 'FAST_CHARGE'){
                                    htmlStr +=
                                        '<p>' +
                                        '<span style="background-color:#05CD69;color:#fff;padding:2px;margin:0px 3px 0px 10px;">快</span> ' +
                                        '快充桩共<span style="color:red;font-weight:bold;cursor:pointer;padding:0px 5px;text-decoration:underline;font-size:16px;"' +
                                        'onclick="chargeChargeStationIndex_scanStationDetailsWin.checkPoleDetails(\'' + i + '\')">' + sumNum + '</span>个：' +
                                        detailStr +
                                        '</p>';
                                }else if(i == 'SLOW_CHARGE'){
                                    htmlStr +=
                                        '<p>' +
                                        '<span style="background-color:#FFD040;color:#fff;padding:2px;margin:0px 3px 0px 10px;">慢</span> ' +
                                        '慢充桩共<span style="color:red;font-weight:bold;cursor:pointer;padding:0px 5px;text-decoration:underline;font-size:16px;"' +
                                        'onclick="chargeChargeStationIndex_scanStationDetailsWin.checkPoleDetails(\'' + i + '\')">' + sumNum + '</span>个：' +
                                        detailStr +
                                        '</p>';
                                }
                            }
                        }else{
                            htmlStr = '<p>电站还没有添加任何电桩！</p>';
                        }
                        $('#chargeChargeStationIndex_scanStationDetailsWin_statistics').html(htmlStr);
                    }
                }
            });
        },
        // 查看该类电桩详情，重载电桩列表
        checkPoleDetails: function(p){
            var param = {'charge_pattern': p};
            $('#chargeChargeStationIndex_scanStationDetailsWin_datagrid').datagrid('reload',param);
        }
    }

    // 执行初始化函数
    chargeChargeStationIndex_scanStationDetailsWin.init();

</script>