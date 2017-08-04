<div style="padding:5px 3px;">
	<!-- 基本信息 begin -->
    <div class="easyui-panel" title="" style="padding:5px;" data-options="collapsible:true,collapsed:false,border:false">
        <form id="chargeAppointmentInfoWin_baseInfo" method="post">
        	<table cellpadding="5" cellspacing="0" style="width:100%;height:100%" border=0>
        	    <tr>
        			<td align="right">预约单编号：</td>
        			<td>
        			    <input class="easyui-textbox" name="code" style="width:153px;" disabled="true" value="系统将会自动生成"  />    
        			</td>
				</tr>
				<tr>
        			<td align="right">预约手机号：</td>
        			<td>
        			    <input class="easyui-textbox" name="mobile" style="width:153px;" data-options="
							validType:'match[/^1[34578][0-9]{9}$/]',
							invalidMessage:'手机号格式错误！',
							required:true,
							missingMessage:'手机号不能为空！'
						"  />  
        			</td>	
				</tr>
				<tr>
					<td align="right">预约时间段：</td>
        			<td colspan=3>
        			    <input name="appointed_date" id="appointed_date" style="width:153px;"  /> &nbsp;
						<input name="time_start" id="time_start" style="width:90px;"  /> -    
        			    <input name="time_end" id="time_end" style="width:90px;"  />    
        			</td>
				</tr>
				<tr style="height:40px;">
        			<td  align="right" valign="bottom">			
						<div  onclick="javascript:chargeAppointmentInfoWin.toggleSearchDiv()" style="cursor:pointer;">
							<img src="jquery-easyui-1.4.3/themes/icons/search.png" style="vertical-align:middle;" border=0 />
							<span style="color:#BF0000;font-weight:bold;text-decoration:underline;margin-left:-3px;">查找电桩</span>&nbsp;
						</div>
					</td>
					<td>
						<div id="searchDiv" style="display:none;">
							<input class="easyui-searchbox" id="searchbox" prompt="安装地点"  style="width:450px;height:30px;shadow:true;"
								searcher="chargeAppointmentInfoWin.searchChargers" menu="#searchMm"> 
							<div id="searchMm">
								<div name="ALL" iconCls="icon-link" style="font-weight:bold;">连接方式</div>
								<?php foreach($config['connection_type'] as $val){ ?>
								<div name="<?php echo $val['value'] ?>" ><?php echo $val['text']; ?></div>
								<?php } ?>
							</div>
							<div id="searchDropdownPanel" style="position:relative;left:0px;top:0px;display:none;">
								<div style="position:absolute;left:0px;top:0px;z-index:9999999;">
									<table id="chargersDatagrid"></table>
								</div>
							</div>					
						</div>		
					</td>
				</tr>
				<tr>
					<td align="right">电桩公司编号：</td>
					<td>
						<input class="easyui-textbox" name="code_from_compony" style="width:153px;" disabled="true"  /> 
					</td>
				</tr>
				<tr>
					<td align="right">电桩连接方式：</td>
        			<td>
        			    <select class="easyui-combobox" name="connection_type" style="width:153px;" data-options="panelHeight:'auto',hasDownArrow:false"  disabled="true">
							<option value=""></option>
							<?php foreach($config['connection_type'] as $val){ ?>
        			        <option value="<?php echo $val['value'] ?>"><?php echo $val['text']; ?></option>
        			        <?php } ?>
        			    </select>        			
					</td>
				</tr>
				<tr>
					<td align="right">电桩安装地点：</td>
					<td>
						<input class="easyui-textbox" name="install_site" style="width:450px;" disabled="true"  /> 
					</td>
				</tr>
				<tr hidden>
        			<td align="right">预约电桩ID：</td>
        			<td>
        			    <input class="easyui-textbox" name="chargerid" id="chargerid" style="width:153px;" editable="false"  /> 
					</td>
				</tr>
				<tr hidden>
					<td align="right">预约单ID：</td>
					<td >
						<input class="easyui-textbox" name="id" style="width:153px;"  editable="false"  /> 
					</td>
				</tr>				
				<tr>
					<td align="right">预约备注：</td>
					<td colspan="5">
						<input class="easyui-textbox" name="mark" style="width:450px;height:60px;" 
						data-options="multiline:true"
                        validType="length[150]"  /> 
					</td>
				</tr>
				<tr style="height:40px;">
					<td colspan=2></td>
				</tr>
			</table>
        </form>
    </div> 
    <!-- 基本信息 end -->
</div>
<script>
	var connection_type = <?= json_encode($config['connection_type']); ?>;
	var myData = <?php echo json_encode($myData); ?>;
	
	var chargeAppointmentInfoWin = {};
    
	// 页面初始化设置
	chargeAppointmentInfoWin.init = function(){ 
		var now = new Date();
		var curYmd = parseDateObj(now); 
		var curYmdHis = parseDateObj(now,true); 
		
		// 预约日期
		$('#appointed_date').datebox({		
			required: true,
			missingMessage: '请输入预约日期！',
			validType: 'date'
		});
		
		// 预约日期控件-日历对象，若是新增预约时则禁用当前日期之前的日期。 
		if(myData.action == 'add'){	
			$('#appointed_date').datebox('calendar').calendar({
				'validator': function(date){
					var _Ymd = parseDateObj(date);
					if (curYmd > _Ymd){
						return false;
					}else{
						return true;
					}
				},
				'formatter': function(date){
					var day = date.getDate();
					var _Ymd = parseDateObj(date);
					if (curYmd > _Ymd){
						return '<div style="filter:alpha(opacity=35);-moz-opacity:0.35;-khtml-opacity:0.35;opacity:0.35;">' + day + '</div>';
					}else{
						return day;
					}
				}	
			});
		}
		
		$('#time_start').timespinner({
			required: true,
			missingMessage:'请输入起始时间！',
			showSeconds: false,
			increment: 30
		});
		$('#time_end').timespinner({
			required: true,
			missingMessage:'请输入截止时间！',
			showSeconds: false,
			increment: 30	
		});
				
		// 若是修改预约，则加载旧数据
		switch(myData.action) {
			case 'edit':
				var oldData = myData.chargeAppointmentInfo;
				$('#chargeAppointmentInfoWin_baseInfo').form('load',oldData);
				break;
			default: break;
		}

		// 搜索电桩的下拉列表
		$('#chargersDatagrid').datagrid({
			maxHeight:200,
			width:500,
			method: 'get',
			url: '<?php echo yii::$app->urlManager->createUrl(['vip/charge-appointment/search-available-chargers']); ?>',
			striped: true,
			checkOnSelect: true,
			singleSelect: true,
			fitColumns: true,
			scrollbarSize :0, 		  //设置右侧滚动条宽度为0
			pagination: true,
			pageSize: 20,
			pageList: [10,20,30,50],
			columns: [[
				{field:'id',title:'电桩ID',width:50,hidden:true},
				{field:'code_from_compony',title:'<b>电桩公司编号</b>',width:95,halign:'center',align:'center'},
				{field:'connection_type',title:'<b>连接方式</b>',width:60,halign:'center',align:'center',
					formatter: function(value,row,index){
						try{ 
							var str = 'connection_type.' + value + '.text';
							return eval(str); 
						}catch(e){					
							return '';
						}
					}
				},
				{field:'install_site',title:'<b>安装地点</b>',width:270,halign:'center'},
				{field:'appointments',title:'查看详情',width:63,halign:'center',
					formatter: function(value,row,index){
						if(value && value.length > 0){
							var num = '<span style="color:red;font-weight:bold;">' + value.length + '</span>';
						}else{
							var num = '<span>0</span>';
						}
						var str =  '<span style="color:blue;cursor:pointer;padding-left:5px;" class="easyui-tooltip" tipRowIndex='+index+' >' + 
										'预约(' + num + ')' + 
									'</span>';	
						return str;
					}
				}
			]],
			onClickRow: function(rowIndex,rowData){
				var _data = { 
					chargerid: rowData.id,
					code_from_compony: rowData.code_from_compony,
					connection_type: rowData.connection_type,
					install_site: rowData.install_site
				}
				$('#chargeAppointmentInfoWin_baseInfo').form('load',_data);
				$('#searchDiv').fadeOut('fast');
			},
			onLoadSuccess:function(){ // 表格数据加载成功后，设置悬浮提示框！
				var appointed_date = $('#appointed_date').datebox('getValue');
				var rows = $('#chargersDatagrid').datagrid('getRows');
				$('#chargersDatagrid').datagrid('getPanel').find('.easyui-tooltip').each(function(){
					var index = parseInt($(this).attr('tipRowIndex'));
					var row = rows[index]; 
					var appointments = row.appointments;
					$(this).tooltip({
						position: 'right',
						content : $('<div style="padding:3px 1px;font-size:90%;"></div>'),
						onUpdate: function(cc){
							var contStr = '';
							if(appointments && appointments.length > 0){
								contStr += '<table cellspacing=0 cellpadding=3 border=0 width="100%" align="center">';
								contStr += '<tr align="center"><th>预约时间段</th><th>预约单号</th></tr>';
									for(var i=0;i<appointments.length;i++){
										contStr += '<tr align="center"><td>' + (appointments[i].time_start + '~' + appointments[i].time_end) + '</td><td>' + appointments[i].code + '</td></tr>';
									}
								contStr += '</table>';	
							}else{
								contStr += '<div style="text-align:center;height:50px;line-height:50px;">尚无任何预约！</div>';
							}
							cc.panel({
								title: '<div style="text-align:center">' + appointed_date + '</div>',
								width: 230,
								minHeight: 50,
								content: contStr
							});
						}
					});
				});
			}
		});
		
		
	}
	chargeAppointmentInfoWin.init();
		
	
	// 显示/隐藏查询域
	chargeAppointmentInfoWin.toggleSearchDiv = function(){
		$('#searchDiv').fadeToggle('fast');
	}
	

	
	// 查找电桩
	chargeAppointmentInfoWin.searchChargers = function(inputValue,chooseOption){
		var appointed_date = $('#appointed_date').datebox('getValue'); 
		if(!appointed_date){
			$.messager.show({
				title: '提示',
				msg: '请先填写【预约日期】！'
			}); 
			return false;
		}
		var _queryParams = {
			appointedDate: appointed_date,
			connectionType: chooseOption,
			installSite: inputValue
		};
		$('#chargersDatagrid').datagrid('load',_queryParams);
		var ddPanel = $('#searchDropdownPanel');
		ddPanel.show();
		// 设置点击页面其他地方才隐藏下拉面板（关键是阻止面板自身的click事件冒泡）
		ddPanel.parent().on('click',function(event){
			event.stopPropagation(); 
		});
		$(document).on('click',function(){
			ddPanel.hide();
		});
	}
	
</script>