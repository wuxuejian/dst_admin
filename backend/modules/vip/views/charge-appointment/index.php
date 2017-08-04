<table id="charge_appointment_index_datagrid"></table> 
<div id="charge_appointment_index_datagrid_toolbar">
    <div class="easyui-panel" title="检索区域" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="charge_appointment_index_searchFrom">
                <ul class="search-main">
                    <li>
                        <div class="item-name">预约单编号</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="code" style="width:100%;"  />
                        </div>
                    </li>                                     
					<li>
                        <div class="item-name">预约手机号</div>
                        <div class="item-input">
                           <input class="easyui-textbox" type="text" name="mobile" style="width:100%;"  />
                        </div>
                    </li>                    
                    <li>
                        <div class="item-name">预约日期</div>
                        <div class="item-input">
							<input class="easyui-datebox" type="text" name="appointed_date_start" style="width:90px;"  /> -
							<input class="easyui-datebox" type="text" name="appointed_date_end" style="width:90px;"  />
                        </div>               
                    </li> 
                    <li class="search-button">
                        <a href="javascript:chargeAppointmentIndex.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                        <a href="javascript:chargeAppointmentIndex.reset()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</a>
                    </li>
                </ul>
            </form>
        </div>
    </div>

    <?php if(!empty($buttons)){ ?>
        <div class="easyui-panel" title="预约列表" style="padding:3px 2px;width:100%;" data-options="
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
<div id="charge_appointment_index_datagrid_add_edit_win"></div>
<!-- 窗口 -->

<script>
	var chargeAppointmentIndex = new Object();
	var connection_type = <?= json_encode($config['connection_type']); ?>; 
	
	chargeAppointmentIndex.init = function(){
		//获取列表数据
		$('#charge_appointment_index_datagrid').datagrid({  
			method: 'get', 
		    url:'<?php echo yii::$app->urlManager->createUrl(['vip/charge-appointment/get-list']); ?>',   
			fit: true,
			border: false,
			toolbar: "#charge_appointment_index_datagrid_toolbar",
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
                {field: 'code',title: '预约单编号',width: 130,align:'center',sortable:true},   
            ]],
		    columns:[
		        [
					{field: 'mobile',title: '预约手机号',width: 90,align:'center',sortable:true,rowspan:2}, //跨2行
					{field: 'appointed_date_time',title: '预约时间段',width: 150,align:'center',sortable:true,rowspan:2,
						formatter: function(value,row,index){
							return row.appointed_date + ' ' + row.time_start + '~' + row.time_end;
						}
					},
					{title:'预约的电桩',colspan:4}, //跨4列
					{field: 'isfinished',title: '是否完成',width: 70,align:'center',sortable:true,rowspan:2,
						formatter: function(value,row,index){
							return value==1 ? '是' : '否';
						}
					},
					{field: 'mark',title: '备注',width: 150,align:'center',rowspan:2},
					{field: 'systime',title: '登记日期',align:'center',width: 90,sortable:true,rowspan:2,
						formatter: function(value,row,index){
							return formatDateToString(value);
						}
					}
				],[
					{field: 'chargerid',title: '电桩ID',width: 40,align:'center',sortable:true,hidden:true},
					{field: 'code_from_compony',title: '电桩编号',width: 120,align:'center',sortable:true},
					{field: 'connection_type',title: '连接方式',width: 80,align:'center',sortable:true,
						formatter: function(value,row,index){
							try{ 
								var str = 'connection_type.' + value + '.text';
								return eval(str); 
							}catch(e){					
								return '';
							}
						}
					},
					{field: 'install_site',title: '安装地点',width: 220,align:'left',sortable:true}
				]
		    ],
			onDblClickCell: function(rowIndex,fieldName,fieldValue){
				if(fieldName != '_expander'){
					chargeAppointmentIndex.edit();
				}
            }
		});
		
        //初始化新增/修改窗口
		$('#charge_appointment_index_datagrid_add_edit_win').dialog({
        	title: '新增/修改预约',   
            width: 690,
            height: 450,   
            closed: false,   
            cache: true,   
            modal: true,
            buttons: [{
				id:'chargeAppointmentInfoWin_saveBtn',
				text:'确定',
				iconCls:'icon-ok'			
			},{
				text:'取消',
				iconCls:'icon-cancel',
				handler:function(){
					$('#charge_appointment_index_datagrid_add_edit_win').dialog('close');
				}
			}],
			closed: true  
        });
	}
	
	chargeAppointmentIndex.init();
	
	
	
	
	
	//添加
	chargeAppointmentIndex.add = function(){
		var _url = '<?php echo yii::$app->urlManager->createUrl(['vip/charge-appointment/add']); ?>';
		$('#charge_appointment_index_datagrid_add_edit_win').dialog('open');
		$('#charge_appointment_index_datagrid_add_edit_win').dialog('refresh',_url);
		$('#charge_appointment_index_datagrid_add_edit_win').dialog('setTitle','新增预约');
		$('#chargeAppointmentInfoWin_saveBtn').unbind().bind('click',function(){
			var _form = $('#chargeAppointmentInfoWin_baseInfo');
			if(!_form.form('validate')){
				$.messager.alert('警告','表单验证未通过，请仔细检查！','warning');
				return false;
			}
			var data = {};
			data.formData = _form.serialize();
			$.ajax({
				type: 'post',
				url: _url,
				data: data,
				dataType: 'json',
				success: function(data){ 
					if(data.status){
						$('#charge_appointment_index_datagrid').datagrid('reload');
						$('#charge_appointment_index_datagrid_add_edit_win').dialog('close');
						$.messager.alert('提示',data.info,'info');
					}else{
						$.messager.alert('错误',data.info,'error');
					}
				}
			});
		});
	}
	
	//获取选择的记录
	chargeAppointmentIndex.getSelected = function(){
		var datagrid = $('#charge_appointment_index_datagrid');
		var selectRow = datagrid.datagrid('getSelected');
		if(!selectRow){
			$.messager.alert('警告','请先选择要操作的记录！','warning');   
			return false;
		}
		return selectRow.id;
	}
	
	//修改
	chargeAppointmentIndex.edit = function(id){
		if(!id){
			id = this.getSelected();
		}
		if(!id){
			return;
		}
		var _url = '<?php echo yii::$app->urlManager->createUrl(['vip/charge-appointment/edit']); ?>';
		$('#charge_appointment_index_datagrid_add_edit_win').dialog('open');
		$('#charge_appointment_index_datagrid_add_edit_win').dialog('refresh',_url+'&id='+id);
		$('#charge_appointment_index_datagrid_add_edit_win').dialog('setTitle','修改预约');
		$('#chargeAppointmentInfoWin_saveBtn').unbind().bind('click',function(){
			var _form = $('#chargeAppointmentInfoWin_baseInfo');
			if(!_form.form('validate')){
				$.messager.alert('警告','表单验证未通过，请仔细检查！','warning');
				return false;
			}
			var data = {};
			data.id = id;
			data.formData = _form.serialize();
			$.ajax({
				type: 'post',
				url: _url,
				data: data,
				dataType: 'json',
				success: function(data){ 
					if(data.status){
						$('#charge_appointment_index_datagrid').datagrid('reload');
						$('#charge_appointment_index_datagrid_add_edit_win').dialog('close');
						$.messager.alert('提示',data.info,'info');
					}else{
						$.messager.alert('错误',data.info,'error');
					}
				}
			});
		});
	}
	
	
	
	//删除
	chargeAppointmentIndex.remove = function(){
		var id = this.getSelected();
		if(!id){
			return;
		}
		$.messager.confirm('确定删除','您确定要删除该预约单？',function(r){
			if(r){
				$.ajax({
					type: 'get',
					url: '<?php echo yii::$app->urlManager->createUrl(['vip/charge-appointment/remove']); ?>',
					data: {id: id},
					dataType: 'json',
					success: function(data){
						if(data){
							$.messager.alert('提示',data.info,'info');   
							$('#charge_appointment_index_datagrid').datagrid('reload');
						}else{
							$.messager.alert('错误',data.info,'error');   
						}
					}
				});
			}
		});
	}
	//查询
	chargeAppointmentIndex.search = function(){
		var form = $('#charge_appointment_index_searchFrom');
		var data = {};
		var searchCondition = form.serializeArray(); 
		for(var i in searchCondition){
			data[searchCondition[i]['name']] = searchCondition[i]['value'];
		}
		$('#charge_appointment_index_datagrid').datagrid('load',data);
	}
	
	//重置
	chargeAppointmentIndex.reset = function(){
		$('#charge_appointment_index_searchFrom').form('reset');
	}
	
	//导出
    chargeAppointmentIndex.exportGridData = function(){
		var form = $('#charge_appointment_index_searchFrom');
		var str = form.serialize();
        window.open("<?php echo yii::$app->urlManager->createUrl(['vip/charge-appointment/export-grid-data']); ?>&" + str);
    }

	
</script>