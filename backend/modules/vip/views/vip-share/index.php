<table id="vipShareIndex_datagrid"></table>
<div id="vipShareIndex_datagridToolbar">
    <div class="easyui-panel" title="检索区域" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="vipShareIndex_searchFrom">
                <ul class="search-main">                                   
					<li>
                        <div class="item-name">会员编号</div>
                        <div class="item-input">
                           <input class="easyui-textbox" type="text" name="vip_code" style="width:100%;" />
                        </div>
                    </li>                    
					<li>
                        <div class="item-name">会员手机号</div>
                        <div class="item-input">
                           <input class="easyui-textbox" type="text" name="vip_mobile" style="width:100%;" />
                        </div>
                    </li>
					<li>
                        <div class="item-name">审核状态</div>
                        <div class="item-input">
                            <select class="easyui-combobox"  name="approve_status" style="width:100%;" data-options="panelHeight:'auto',editable:false">
                                <option value="" selected="selected">--不限--</option>
                                <option value="0">待审核</option>
                                <option value="1">审核不通过</option>
                                <option value="2">审核已通过</option>
                            </select>
                        </div>
                    </li>                    
                    <li class="search-button">
                        <a onclick="vipShareIndex.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                        <a onclick="vipShareIndex.reset()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</a>
                    </li>
                </ul>
            </form>
        </div>
    </div>

    <?php if(!empty($buttons)){ ?>
        <div class="easyui-panel" title="分享列表" style="padding:3px 2px;width:100%;" data-options="
        iconCls: 'icon-table-list',
        border: false
    ">
            <?php foreach($buttons as $val){ ?>
                <button onclick="<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon'] ;?>'"><?= $val['text'] ;?></button>
            <?php } ?>
        </div>
    <?php } ?>

</div>
<!-- 窗口 -->
<div id="vipShareIndex_approveWindow"></div>
<!-- 窗口 -->

<script>
	// 请求的URL
	var vipShareIndex_URL_getList = "<?php echo yii::$app->urlManager->createUrl(['vip/vip-share/get-list']);?>";
	var vipShareIndex_URL_approve = "<?php echo yii::$app->urlManager->createUrl(['vip/vip-share/approve']);?>";
	var vipShareIndex_URL_exportGridData = "<?php echo yii::$app->urlManager->createUrl(['vip/vip-share/export-grid-data']);?>";	
	// 相关配置
	var vipShareIndex_CONFIG_charge_type = <?= json_encode($config['charge_type']); ?>;
	var vipShareIndex_CONFIG_connection_type = <?= json_encode($config['connection_type']); ?>;
	var vipShareIndex_CONFIG_install_type = <?= json_encode($config['install_type']); ?>;
	var vipShareIndex_CONFIG_manufacturer = <?= json_encode($config['manufacturer']); ?>;
	var vipShareIndex_CONFIG_model = <?= json_encode($config['model']); ?>;
	
	var vipShareIndex = {
		// 初始化
		init: function(){
			// 初始化表格
            $('#vipShareIndex_datagrid').datagrid({
				method: 'get', 
				url: vipShareIndex_URL_getList,   
				fit: true,
				border: false,
				toolbar: '#vipShareIndex_datagridToolbar',
				pagination: true,
				loadMsg: '数据加载中...',
				striped: true,
				checkOnSelect: true,
				rownumbers: true,
				singleSelect: true,
				pageSize: 20,
				frozenColumns: [[
					{field: 'ck',checkbox: true}, 
					{field: 'id',title: '记录ID',width:40,align:'center',hidden:true}, 
					{field: 'vip_code',title: '会员编号',width: 140,align:'center',sortable:true},
					{field: 'vip_mobile',title: '会员手机号',width: 90,align:'center',sortable:true}
				]],
				columns:[
					[
						{field: 'share_time',title: '分享时间',align:'center',width: 130,sortable:true,rowspan:2},
						{field: 'mark',title: '分享备注',halign:'center',width: 100,sortable:true,rowspan:2},
						{title:'审核情况',colspan:3},
						{title:'分享的电桩',colspan:7},
						{field: 'systime',title: '登记时间',width: 130,align:'center',sortable:true,rowspan:2,
							formatter: function(value,row,index){
								return formatDateToString(value);
							}
						}
					],
					[
						{field: 'approve_status',title: '审核状态',width: 100,align:'center',sortable:true,
							formatter:function(value,row,index){
								if(parseInt(value) == 2){
									return '<img src="jquery-easyui-1.4.3/themes/icons/circle-check-green.png" /> <span style="font-weight:bold;color:#95CF15;">审核已通过</span>';
								}else if(parseInt(value) == 1){
									return '<img src="jquery-easyui-1.4.3/themes/icons/circle-cross-red.png" /> <span style="font-weight:bold;color:#EE4A22;">审核不通过</span>';
								}else{
									return '待审核';
								}
							}
						},	
						{field: 'approve_time',title: '审核时间',align:'center',width: 140,sortable:true},
						{field: 'approve_mark',title: '审核备注',width: 220,halign:'center'},
						//{field: 'code_from_compony',title: '<span title="仅审核通过才有值">电桩编号?</span>',width:130,align:'center',sortable:true},
						{field: 'code_from_factory',title: '出厂编号',width: 130,align:'center',sortable:true},
						{field: 'manufacturer',title: '生产厂家',align:'center',width: 80,sortable:true,
							formatter: function(value,row,index){
								try{ 
									var str = 'vipShareIndex_CONFIG_manufacturer.' + value + '.text';
									return eval(str); 
								}catch(e){					
									return '';
								}
							}
						},
						{field: 'connection_type',title: '连接方式',align:'center',width: 80,sortable:true,
							formatter: function(value,row,index){
								try{ 
									var str = 'vipShareIndex_CONFIG_connection_type.' + value + '.text';
									return eval(str); 
								}catch(e){					
									return '';
								}
							}
						},
						{field: 'model',title: '型号',width: 80,align:'center',sortable:true,
							formatter: function(value,row,index){
								try{ 
									var str = 'vipShareIndex_CONFIG_model.' + value + '.text';
									return eval(str); 
								}catch(e){					
									return '';
								}
							}
						},
						{field: 'charge_type',title: '充电桩类型',width: 70,align:'center',sortable:true,
							formatter: function(value,row,index){
								try{ 
									var str = 'vipShareIndex_CONFIG_charge_type.' + value + '.text';
									return eval(str); 
								}catch(e){					
									return '';
								}
							}
						},
						{field: 'install_type',title: '安装方式',align:'center',width: 80,sortable:true,
							formatter: function(value,row,index){
								try{ 
									var str = 'vipShareIndex_CONFIG_install_type.' + value + '.text';
									return eval(str); 
								}catch(e){					
									return '';
								}
							}
						},
						{field: 'install_site',title: '安装地点',width: 220,halign:'center',sortable:true}
					]
				],
				onDblClickRow: function(rowIndex,rowData){
					vipShareIndex.edit(rowData.id);
				},
				rowStyler: function(rowIndex,row){
					if(row.approve_status == 1){ // 0未审核，1审核未通过，2审核通过
						return "color:#EE4A22;font-weight:bold;";
					}
				},
				onLoadSuccess:function(data){ 
					$(this).datagrid('doCellTip',{
						position : 'bottom',
						maxWidth : '300px',
						onlyShowInterrupt : true,
						specialShowFields : [     
							{field : 'approve_mark',showField : 'approve_mark'},
							{field : 'mark',showField : 'mark'}
						],
						tipStyler : {			 
							'backgroundColor' : '#E4F0FC',
							'borderColor' : '#87A9D0',
							'boxShadow' : '1px 1px 3px #292929'
						}
					});  
				}
			});		
		
			//初始化【审核电桩】窗口（类似新增电桩窗口）
            $('#vipShareIndex_approveWindow').dialog({
				title: '审核--会员分享的电桩',  
				width: 1000,   
				height: 500,   
				closed: true,   
				cache: true,   
				modal: true,
				buttons: [{
					text:'审核通过,收录成新电桩',
					iconCls:'icon-circle-check-green',
					handler:function(){
						var _form1 = $('#vipShareIndex_approveWindow_baseInfoForm');
						var _form2 = $('#vipShareIndex_approveWindow_moreInfoForm');
						if(!_form1.form('validate') || !_form2.form('validate')){
							$.messager.show({
								title: '表单验证不合法',
								msg: '请检查表单是否填写完整或填写错误！'
							});
							return false;
						}						
						$.messager.confirm('确认审核通过','请仔细检查表单。你确定要审核通过吗？',function(t){
							if(t){
								var submitData = {
									approveResult: 'approve_passed', 							// 审核通过
									formData: _form1.serialize() + '&' + _form2.serialize(),	// 表单数据
									currentVipShareId: vipShareIndex.getCurrentSelected().id	// 当前分享记录ID
								}
								$.ajax({
									type: 'post',
									url: vipShareIndex_URL_approve,
									data: submitData,
									dataType: 'json',
									success: function(data){ 
										if(data.status){
											$.messager.show({
												title: '操作成功',
												msg: data.info
											});
                                            $('#vipShareIndex_approveWindow').dialog('close');
                                            $('#vipShareIndex_datagrid').datagrid('reload');
										}else{
											$.messager.alert('操作失败',data.info,'error');
										}
									}
								});
							}
						});					
					}
				},{
					text:'审核不通过',
					iconCls:'icon-circle-cross-red',
					handler:function(){
						// 显示弹窗填写不通过原因
						var approveMarkWindow = $('#vipShareIndex_approveWindow_approveMarkWindow');
						approveMarkWindow.dialog({
							title: '请简述审核不通过的原因',
							width: 300,
							height: 180,
							closed: false,
							cache: false,
							content: '<input class="easyui-textbox" name="approve_mark" style="" data-options="required:true,multiline:true,fit:true" validType="length[150]" />',
							modal: true,
							buttons:[{
								text:'确定',
								iconCls:'icon-ok',
								handler:function(){
									var approveMark = $.trim($("[name='approve_mark']",approveMarkWindow)[0].value);
									if(approveMark == ''){
										$.messager.show({
											title: '填写不能为空',
											msg:'请填写审核不通过的原因！'
										});
										return false;
									}
									var submitData = {
										approveResult: 'approve_refused',							// 审核不通过
										approveMark: approveMark,									// 不通过原因
										currentVipShareId: vipShareIndex.getCurrentSelected().id	// 当前分享记录ID
									};
									$.ajax({
										type: 'post',
										url: vipShareIndex_URL_approve,
										data: submitData,
										dataType: 'json',
										success: function(data){ 
											if(data.status){
												$.messager.show({
													title: '操作成功',
													msg: data.info
												});
												approveMarkWindow.dialog('close');
                                                $('#vipShareIndex_approveWindow').dialog('close');
                                                $('#vipShareIndex_datagrid').datagrid('reload');
											}else{
												$.messager.alert('操作失败',data.info,'error');
											}
										}
									});
								}
							}]
						});
					}
				},{
					text:'取消',
					iconCls:'icon-cancel',
					handler:function(){
                        $('#vipShareIndex_approveWindow').dialog('close');
					}
				}]
			});
		},
		//获取当前所选择的记录
		getCurrentSelected: function(multiline){
			var datagrid = $('#vipShareIndex_datagrid');
			var selectRows = datagrid.datagrid('getSelections');
			if(selectRows.length <= 0){
				$.messager.show({
					title: '请选择',
					msg: '请先选择要操作的记录！'
				});   
				return false;
			}
			if(multiline){
				return selectRows;
			}else{
				if(selectRows.length > 1){
					$.messager.show({
						title: '提醒',
						msg: '该功能不能批量操作！<br/>如果你选择了多条记录，则默认操作的是第一条记录！'
					});   
				}
				return selectRows[0];
			}
		},
		// 审核电桩
		approve: function(){
			var selectedRow = this.getCurrentSelected();
			if(!selectedRow) return false;
			var approve_status = parseInt(selectedRow.approve_status);
			if(approve_status == 0 || approve_status == 1){ // 0未审核；1审核不通过；2审核已通过
				var id = selectedRow.id;
				var _url = vipShareIndex_URL_approve + '&id=' + id;
                $('#vipShareIndex_approveWindow').dialog('open').dialog('refresh',_url);
			}else{
				$.messager.show({
					title: '不能再审核',
					msg: '该记录审核状态为【审核已通过】，不能再审核！'
				});   
				return false;
			}
		},
		//查询
		search: function(){
			var data = {};
			var searchCondition = $('#vipShareIndex_searchFrom').serializeArray();
			for(var i in searchCondition){
				data[searchCondition[i]['name']] = searchCondition[i]['value'];
			}
            $('#vipShareIndex_datagrid').datagrid('load',data);
		},	
		//重置
		reset: function(){
            $('#vipShareIndex_searchFrom').form('reset');
		},
		//导出
		exportGridData: function(){
			var searchConditionStr = $('#vipShareIndex_searchFrom').serialize();
			var _url = vipShareIndex_URL_exportGridData + '&' + searchConditionStr;
			window.open(_url);
		}
	}
	
	// 执行初始化函数
	vipShareIndex.init();
	
</script>