<form id="chargeChargeStationIndex_addEditWin_form" method="post">
    <div class="easyui-panel" title="(1)基本信息" style="padding:5px 0px;"
         data-options="collapsible:true,collapsed:false,border:false,fit:false">
        <table cellpadding="5" cellspacing="0" style="width:100%;" border="0">
            <tr hidden>
                <td align="right">电站ID</td>
                <td colspan="5">
                    <input class="easyui-textbox" name="cs_id" style="width:160px;" value="0" editable="false"  />
                </td>
            </tr>
            <tr>
                <td align="right"width="10%">省份</td>
                <td width="23%">
                	<select class="easyui-combobox" id="province_id" name="province_id" required="true" style="width:160px;" data-options="panelHeight:'auto',required:true"  editable=false >
                		<option value=""></option>
                        <?php foreach($provinces as $row){ ?>
                            <option value="<?=$row['region_id']?>"><?=$row['region_name']?></option>
                        <?php } ?>
                    </select>
                </td>
                <td align="right"width="10%">城市</td>
                <td width="23%">
                	<select class="easyui-combobox" id="city_id" name="city_id" required="true" style="width:160px;" data-options="panelHeight:'auto',required:true"  editable=false >
                		<option value=""></option>
                		<?php foreach($citys as $row){ ?>
                            <option value="<?=$row['region_id']?>"><?=$row['region_name']?></option>
                        <?php } ?>
                    </select>
                </td>
                <td align="right"width="10%">地区</td>
                <td width="23%">
                	<select class="easyui-combobox" name="area_id" id="area_id" required="true" style="width:160px;" data-options="panelHeight:'auto',required:true"  editable=false >
                		<option value=""></option>
                		<?php foreach($areas as $row){ ?>
                            <option value="<?=$row['region_id']?>"><?=$row['region_name']?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right" width="10%">电站编号</td>
                <td width="23%">
                    <input class="easyui-textbox" name="cs_code" style="width:160px;" required="true" missingMessage="请输入公司编号！"  />
                </td>
                <td align="right"  width="10%">电站类型</td>
                <td>
                    <select class="easyui-combobox" name="cs_type" style="width:160px;" data-options="panelHeight:'auto',required:true"  editable=false >
                        <?php foreach($config['cs_type'] as $val){ ?>
                            <option value="<?php echo $val['value'] ?>"><?php echo $val['text']; ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td align="right"  width="10%">电站状态</td>
                <td width="23%">
                    <select class="easyui-combobox" name="cs_status" style="width:160px;" data-options="panelHeight:'auto',required:true"  editable=false >
                        <?php foreach($config['cs_status'] as $val){ ?>
                            <option value="<?php echo $val['value'] ?>"><?php echo $val['text']; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right">电站名称</td>
                <td>
                    <input class="easyui-textbox" name="cs_name" style="width:160px;" required="true"  />
                </td>
                <td align="right">经度</td>
                <td>
                    <input class="easyui-numberbox" precision="6" name="cs_lng" style="width:160px;" required="true"  />
                    <a href="javascript:chargeChargeStationIndex_addEditWin.search_baiDu_map();"><img src="jquery-easyui-1.4.3/themes/icons/map_magnify.png" title="查找地图" /></a>
                </td>
                <td align="right">纬度</td>
                <td>
                    <input class="easyui-numberbox" precision="6" name="cs_lat" style="width:160px;" required="true"  />
                </td>
            </tr>
            <tr>
                <td align="right">投运日期</td>
                <td>
                    <input class="easyui-datebox" style="width:160px;" name="cs_commissioning_date" required="true" validType="date" />
                </td>
                <td align="right" >电站位置</td>
                <td colspan="3">
                    <input id="cs_address" class="easyui-textbox" name="cs_address" style="width:530px;" required="true" />
                </td>
            </tr>
            <tr>
                <td align="right" width="10%">所属前置机</td>
                <td width="23%">
                    <input id="chargeChargeStationIndex_addEditWin_chooseFrontMachine" name="cs_fm_id" style="width:160px;color:red;"  required="true"  />
                </td>
                <td align="right" width="20%" colspan="2">
                	快充桩数量
                    <input id="spots_fast_num" class="easyui-textbox" name="spots_fast_num" style="width:50px;" value="0"/>
					慢充桩数量
                    <input id="spots_slow_num" class="easyui-textbox" name="spots_slow_num" style="width:50px;" value="0"/>
                </td>
                <td align="right" width="10%">协议类型</td>
                <td width="23%">
                	<select class="easyui-combobox" name="spots_connection_type" style="width:160px;" data-options="panelHeight:'auto'"  editable=false >
                        <?php foreach($config['connection_type'] as $val){ ?>
                            <option value="<?php echo $val['value'] ?>"><?php echo $val['text']; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
        </table>
    </div>
    <div class="easyui-panel" title="(2)运营信息" style="padding:5px 0px;"
         data-options="collapsible:true,collapsed:false,border:false,fit:false">
        <table cellpadding="5" cellspacing="0" style="width:100%;" border="0">
            <tr>
                <td align="right" width="10%">是否开放</td>
                <td width="23%">
                    <select class="easyui-combobox" name="cs_is_open" style="width:160px;" data-options="panelHeight:'auto'"  editable=false >
                        <option value="0">否</option>
                        <option value="1">是</option>
                    </select>
                </td>
                <td align="right" width="10%"></td>
                <td></td>
                <td align="right" width="10%"></td>
                <td></td>
            </tr>
            <tr>
                <td align="right">使用单位</td>
                <td colspan="5">
                    <input class="easyui-textbox" name="cs_building_user" style="width:510px;"  />
                </td>
            </tr>
            <tr>
                <td align="right">负责人姓名</td>
                <td>
                    <input class="easyui-textbox" name="cs_manager_name" style="width:160px;"  />
                </td>
                <td align="right">负责人手机</td>
                <td>
                    <input class="easyui-textbox" name="cs_manager_mobile" style="width:160px;"  />
                </td>
                <td align="right">服务电话</td>
                <td>
                    <input class="easyui-textbox" name="cs_service_telephone" style="width:160px;"  />
                </td>
            </tr>
            <tr>
                <td align="right" valign="top" rowspan="2">开放时间</td>
                <td colspan="5">
                    工作日:<input class="easyui-timespinner" name="cs_opentime[workday_s]" style="width:100px;" value="00:00"  /> 至
                          <input class="easyui-timespinner" name="cs_opentime[workday_e]" style="width:100px;" value="23:59"  />
                </td>
            </tr>
            <tr>
                <td colspan="5">
                    节假日:<input class="easyui-timespinner" name="cs_opentime[holiday_s]" style="width:100px;" value="00:00"  /> 至
                          <input class="easyui-timespinner" name="cs_opentime[holiday_e]" style="width:100px;" value="23:59"  />
                </td>
            </tr>
            <tr>
                <td align="right" valign="top" rowspan="3">充电费率</td>
                <td colspan="5">
                    平&nbsp;时:<input class="easyui-timespinner" name="cs_powerrate[peacetime_s]" style="width:100px;" value="00:00" /> 至
                    <input class="easyui-timespinner" name="cs_powerrate[peacetime_e]" style="width:100px;" value="23:59" />&nbsp;
                    <input class="easyui-numberbox" precision="2" name="cs_powerrate[peacetime_price]" style="width:80px;" />元/度
                </td>
            </tr>
            <tr>
                <td colspan="5">
                    峰&nbsp;时:<input class="easyui-timespinner" name="cs_powerrate[peaktime_s]" style="width:100px;" value="00:00" /> 至
                    <input class="easyui-timespinner" name="cs_powerrate[peaktime_e]" style="width:100px;" value="23:59" />&nbsp;
                    <input class="easyui-numberbox" precision="2" name="cs_powerrate[peaktime_price]" style="width:80px;" />元/度
                </td>
            </tr>
            <tr>
                <td colspan="5">
                    谷&nbsp;时:<input class="easyui-timespinner" name="cs_powerrate[valleytime_s]" style="width:100px;" value="00:00" /> 至
                    <input class="easyui-timespinner" name="cs_powerrate[valleytime_e]" style="width:100px;" value="23:59" />&nbsp;
                    <input class="easyui-numberbox" precision="2" name="cs_powerrate[valleytime_price]" style="width:80px;" />元/度
                </td>
            </tr>
            <tr>
                <td align="right">服务费</td>
                <td colspan="5">
                    <input class="easyui-numberbox" precision="2" name="cs_servicefee" style="width:80px;" />元/度（要包含损耗费用在内）
                </td>
            </tr>

            <tr>
                <td align="right" valign="top" rowspan="6">停车费</td>
                <td colspan="5" style="background-color:#EBDFA1;">慢充桩车位</td>
            </tr>
            <tr>
                <td colspan="5">
                    首<input class="easyui-numberbox" precision="2" name="cs_parkingfee[slowpole_freetime]" style="width:80px;" />小时免费
                </td>
            </tr>
            <tr>
                <td colspan="5">
                    时段1:<input class="easyui-timespinner" name="cs_parkingfee[slowpole_period1_s]" style="width:100px;" value="00:00" /> 至
                    <input class="easyui-timespinner" name="cs_parkingfee[slowpole_period1_e]" style="width:100px;" value="23:59" />&nbsp;
                    <input class="easyui-numberbox" precision="2" name="cs_parkingfee[slowpole_period1_price]" style="width:80px;" />元/小时
                </td>
            </tr>
            <tr>
                <td colspan="5" style="background-color:#EBDFA1;">快充桩车位</td>
            </tr>
            <tr>
                <td colspan="5">
                    首<input class="easyui-numberbox" precision="2" name="cs_parkingfee[fastpole_freetime]" style="width:80px;" />小时免费
                </td>
            </tr>
            <tr>
                <td colspan="5">
                    时段1:<input class="easyui-timespinner" name="cs_parkingfee[fastpole_period1_s]" style="width:100px;" value="00:00"  /> 至
                    <input class="easyui-timespinner" name="cs_parkingfee[fastpole_period1_e]" style="width:100px;" value="23:59" />&nbsp;
                    <input class="easyui-numberbox" precision="2" name="cs_parkingfee[fastpole_period1_price]" style="width:80px;" />元/小时
                </td>
            </tr>
            <tr>
                <td align="right" valign="top">App温馨提示</td>
                <td colspan="5">
                    <input class="easyui-textbox" name="app_tips" style="width:800px;height:40px;"
                           data-options="multiline:true"
                           validType="length[255]"  />
                </td>
            </tr>
            <tr>
                <td align="right" valign="top">备注</td>
                <td colspan="5">
                    <input class="easyui-textbox" name="cs_mark" style="width:800px;height:40px;"
                           data-options="multiline:true"
                           validType="length[255]"  />
                </td>
            </tr>
            <tr>
                <td align="right" valign="top">添加照片</td>
                <td colspan="5">
                    <a href="javascript:chargeChargeStationIndex_addEditWin.uploadStationImage();" class="easyui-linkbutton" data-options="iconCls:'icon-image-add'" title="上传照片"></a>
                    <span id="addEditWin_stationImagesTip" style="color:red;"></span>
                    <div id="addEditWin_stationImagesDiv">
                        <?php
                            if(isset($stationImages) && !empty($stationImages)){
                                foreach($stationImages as $k=>$v){
                                    if($v){
                        ?>
                                        <img src="<?php echo $v; ?>" id="stationImage_<?php echo $k; ?>" width="100" height="100" style="margin-right:20px;border:0px solid #DBDBDB;"/>
                        <?php
                                    }
                                }
                            }
                        ?>
                    </div>
                    <input type="hidden" name="cs_pic_path_0" style="width:800px;" />
                    <input type="hidden" name="cs_pic_path_1" style="width:800px;" />
                    <input type="hidden" name="cs_pic_path_2" style="width:800px;" />
                </td>
            </tr>
        </table>
    </div>
</form>

<!-- 窗口 -->
<div id="baiDuMapWin" class="easyui-dialog" title="查找百度地图" style="width:800px;height:580px;" modal="true" closed="true"></div>
<div id="chargeChargeStationIndex_addEditWin_uploadStationImageWin"></div>

<script>
    var chargeChargeStationIndex_addEditWin = {
        params:{
            initData: <?php echo json_encode($initData); ?>
        },
        init: function(){
            // 初始化所属前置机combobox
            $('#chargeChargeStationIndex_addEditWin_chooseFrontMachine').combobox({
                panelHeight: 'auto',
                valueField: 'id',
                textField: 'addr',
                editable:false,
                data: chargeChargeStationIndex_addEditWin.params.initData.frontmachine,
                formatter:function(row){
                    return '<span>' + row.addr + ':' + row.port + '</span>';
                }
            });

            // 初始化上传电站照片窗口
            $('#chargeChargeStationIndex_addEditWin_uploadStationImageWin').dialog({
                title: '上传电站照片',
                width: 500,
                height: 300,
                closed: true,
                cache: true,
                modal: true,
                maximizable: false,
                resizable: false,
                onClose: function () {
                    $(this).dialog('clear');
                },
                buttons: [{
                    text: '开始上传',
                    iconCls: 'icon-ok',
                    handler: function () {
                        var form = $('#chargeChargeStationIndex_addEditWin_uploadStationImageWin_form');
                        form.form('submit', {
                            url: "<?php echo yii::$app->urlManager->createUrl(['charge/charge-station/upload-station-image']); ?>",
                            onSubmit: function(){
                                if(!$(this).form('validate')){
                                    $.messager.show({
                                        title: '表单验证不通过',
                                        msg: '请检查表单是否填写完整或填写错误！'
                                    });
                                    return false;
                                }
                            },
                            success: function(data){
                                // change JSON string to js object
                                var data = eval('(' + data + ')');
                                if(data.status){
                                    if(data.status == 2){
                                        $.messager.show({
                                            title: '部分照片上传失败',
                                            msg: data.info
                                        });
                                    }
                                    $('#chargeChargeStationIndex_addEditWin_uploadStationImageWin').dialog('close');
                                    for(var i=0;i<data.filePath.length;i++){
                                        if(data.filePath[i]){
                                            if($('#stationImage_'+i).length > 0){
                                                $('#stationImage_'+i).attr('src',data.filePath[i]);
                                            }else{
                                                var htmlStr = $('#addEditWin_stationImagesDiv').html();
                                                htmlStr += '<img src="'+(data.filePath[i])+'" width="100" height="100" style="margin-right:20px;border:5px double #DBDBDB;"/>';
                                                $('#addEditWin_stationImagesDiv').html(htmlStr);
                                            }
                                            $('input[name="cs_pic_path_'+i+'"]').val(data.filePath[i]);
                                        }
                                    }
                                    $('#addEditWin_stationImagesTip').html('*上传照片后请记得点击右下角“确定”按钮真正保存照片！');
                                }else{
                                    $.messager.alert('上传失败',data.info,'error');
                                }
                            }
                        });
                    }
                }, {
                    text: '取消',
                    iconCls: 'icon-cancel',
                    handler: function () {
                        $('#chargeChargeStationIndex_addEditWin_uploadStationImageWin').dialog('close');
                    }
                }]
            });

            // 修改时加载出旧数据
            if(chargeChargeStationIndex_addEditWin.params.initData.action == 'edit'){
                var oldData = chargeChargeStationIndex_addEditWin.params.initData.ChargeStationInfo;
                var form = $('#chargeChargeStationIndex_addEditWin_form');
                form.form('load',oldData);
                //为开放时间、电费费率、停车费字段赋值
                var arr = ['cs_opentime','cs_powerrate','cs_parkingfee'];
                var i;
                for(i=0;i<arr.length;i++){
                    var obj = eval('oldData.' + arr[i]);
                    for(var k in obj){
                        $('input[name="'+arr[i]+'['+k+']"]',form).val(obj[k]);
                    }
                }
            }

            var _form = $("#chargeChargeStationIndex_addEditWin_form"); 
            //设置onchange事件
            $('select[name=province_id]',_form).combobox({
            	onChange: function (n,o) {
            		var province_id = $('#province_id').combobox('getValue');
            		$.ajax({
            	           url:'<?php echo yii::$app->urlManager->createUrl(['charge/charge-station/get-region-list']); ?>',
            	           type:'get',
            	           data:{parent_id:province_id},
            	           dataType:'json',
            	           success:function(data){
								$('#city_id').combobox({
            	                   valueField:'region_id',
            	                   textField:'region_name',
            	                   editable: false,
            	                   panelHeight:'auto',
            	                   data: data
            	               });
								$('#area_id').combobox({
	            	                   valueField:'region_id',
	            	                   textField:'region_name',
	            	                   editable: false,
	            	                   panelHeight:'auto',
	            	                   data: []
	            	            });
								$('#city_id').combobox('setValues','');
								$('#area_id').combobox('setValues','');
            	            }
            	   	});
            	}
            });
            $('select[name=city_id]',_form).combobox({
            	onChange: function (n,o) {
            		var city_id = $('#city_id').combobox('getValue');
            		$.ajax({
            	           url:'<?php echo yii::$app->urlManager->createUrl(['charge/charge-station/get-region-list']); ?>',
            	           type:'get',
            	           data:{parent_id:city_id},
            	           dataType:'json',
            	           success:function(data){
								$('#area_id').combobox({
            	                   valueField:'region_id',
            	                   textField:'region_name',
            	                   editable: false,
            	                   panelHeight:'auto',
            	                   data: data
            	               });
								$('#area_id').combobox('setValues','');
            	            }
            	   	});
            	}
            });
        },
        // 查找经纬度
        search_baiDu_map: function(){
            var _url = '<?php echo yii::$app->urlManager->createUrl(['interfaces/interfaces/search-baidu-map']); ?>' + '&pageIn=chargeStation';
            $('#baiDuMapWin').dialog('open').dialog('refresh',_url);
        },
        // 上传电站照片
        uploadStationImage: function(){
            var _url = "<?php echo yii::$app->urlManager->createUrl(['charge/charge-station/upload-station-image']); ?>";
            $('#chargeChargeStationIndex_addEditWin_uploadStationImageWin')
                .dialog('open')
                .dialog('refresh',_url);

        }
    }
    // 执行初始化函数
    chargeChargeStationIndex_addEditWin.init();
</script>