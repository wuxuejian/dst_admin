<table id="easyui-datagrid-car-fault-index"></table> 
<div id="easyui-datagrid-car-fault-index-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-car-fault-index">
                <ul class="search-main">
                    <li>
                        <div class="item-name">故障简述</div>
                        <div class="item-input">
                            <input class="easyui-textbox" name="sketch" style="width:150px;"  />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">登记起始时间</div>
                        <div class="item-input">
                            <input class="easyui-datebox" name="register_time_start" style="width:150px;"  /> 
                        </div>
                    </li>
                    <li>
                        <div class="item-name">登记结束时间</div>
                        <div class="item-input">
                            <input class="easyui-datebox" name="register_time_end" style="width:150px;"  />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">当前状态</div>
                        <div class="item-input">
                            <select class="easyui-combobox"  name="status" style="width:150px;">
                                <option value="">不限</option>
                                <?php foreach($config['fault_status'] as $val){ ?>
                                <option value="<?= $val['value'] ?>"><?= $val['text'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </li>
                    <li class="search-button">
                        <a onclick="CarFaultIndex.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                    </li>
                </ul>
            </form>
        </div>
    </div>
    <div class="easyui-panel" title="数据列表" style="padding:4px 0px;width:100%" data-options="
        iconCls: 'icon-tip',
        border: false
    ">
        <button onclick="CarFaultIndex.register()" class="easyui-linkbutton" data-options="iconCls:'icon-add'">故障登记</button>
        <button onclick="CarFaultIndex.dispose()" class="easyui-linkbutton" data-options="iconCls:'icon-remove'">故障处理</button>
        <button onclick="CarFaultIndex.detail()" class="easyui-linkbutton" data-options="iconCls:'icon-remove'">查看详细</button>
    </div>
</div>
<div id="easyui-dialog-car-fault-index-register"></div>
<div id="easyui-dialog-car-fault-index-edit"></div>
<div id="easyui-dialog-car-fault-index-dispose"></div>
<div id="easyui-window-car-fault-index-detail"></div>
<script>
	var CarFaultIndex = new Object();
	CarFaultIndex.init = function(){
		//获取列表数据
		$('#easyui-datagrid-car-fault-index').datagrid({
			method: 'get', 
		    url:'<?php echo yii::$app->urlManager->createUrl(['car/fault/get-list','carId'=>$carId]); ?>',   
			fit: true,
			border: false,
			toolbar: "#easyui-datagrid-car-fault-index-toolbar",
			pagination: true,
			loadMsg: '数据加载中...',
			striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
            frozenColumns: [[
                {field: 'ck',checkbox: true}, 
                {field: 'id',title: 'id',width: 100},   
                {field: 'sketch',title: '故障简述',width: 500} 
            ]],
		    columns:[[
		        {
			        field: 'register_time',title: '登记时间',width: 160,align: 'center',
			        formatter: function(value,row,index){
						return formatDateToString(value);
    		        }
    	        },
		        {
    		        field: 'status',title: '当前状态',width: 100,align: 'center',
    		        formatter: function(value,row,index){
						var status = <?php echo json_encode($config['fault_status']); ?>;
                        try{
                            return status[value]['text'];
                        }catch(e){}
        		    }
    		    }
		    ]]   
		});
		//初始化-登记窗口
		$('#easyui-dialog-car-fault-index-register').dialog({
        	title: '车辆故障信息登记',
            width: 800,   
            height: 500,   
            closed: false,   
            cache: true,   
            modal: true,
            closed: true,
            maximizable: true,
            buttons: [{
				text:'确定',
				iconCls:'icon-ok',
				handler:function(){
					$.ajax({
						type: 'post',
						url: '<?= yii::$app->urlManager->createUrl(['car/fault/register','carId'=>$carId]); ?>',
						data: $('#easyui-form-car-fault-register').serialize(),
						dataType: 'json',
						success: function(data){
							if(data.status){
								$.messager.alert('添加成功',data.info,'info');
								$('#easyui-datagrid-car-fault-index').datagrid('reload');
								$('#easyui-dialog-car-fault-index-register').dialog('close');
							}else{
								$.messager.alert('添加失败',data.info,'error');
							}
						}
					});
				}
			},{
				text:'取消',
				iconCls:'icon-cancel',
				handler:function(){
					$('#easyui-dialog-car-fault-index-register').dialog('close');
				}
			}],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
		//初始化-修改窗口
		$('#easyui-dialog-car-fault-index-edit').dialog({
        	title: '车辆故障信息修改',
            width: 800,
            height: 500,
            closed: false,
            cache: true,
            modal: true,
            closed: true,
            maximizable: true,
            buttons: [{
				text:'确定',
				iconCls:'icon-ok',
				handler:function(){
					$.ajax({
						type: 'post',
						url: '<?= yii::$app->urlManager->createUrl(['car/fault/register','carId'=>$carId]); ?>',
						data: $('#easyui-form-car-fault-edit').serialize(),
						dataType: 'json',
						success: function(data){
							if(data.status){
								$.messager.alert('修改成功',data.info,'info');
								$('#easyui-datagrid-car-fault-index').datagrid('reload');
								$('#easyui-dialog-car-fault-index-edit').dialog('close');
							}else{
								$.messager.alert('修改失败',data.info,'error');
							}
						}
					});
				}
			},{
				text:'取消',
				iconCls:'icon-cancel',
				handler:function(){
					$('#easyui-dialog-car-fault-index-register').dialog('close');
				}
			}],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
		//初始化问题处理窗口
		$('#easyui-dialog-car-fault-index-dispose').dialog({
        	title: '车辆故障处理',   
            width: 800,   
            height: 500,   
            closed: false,   
            cache: true,   
            modal: true,
            closed: true,
            maximizable: true,
            buttons: [{
				text:'确定',
				iconCls:'icon-ok',
				handler:function(){
					$.ajax({
						type: 'post',
						url: '<?= yii::$app->urlManager->createUrl(['car/fault/dispose']); ?>',
						data: $('#easyui-form-car-fault-dispose').serialize(),
						dataType: 'json',
						success: function(data){
							if(data.status){
								$.messager.alert('处理成功',data.info,'info');
								$('#easyui-datagrid-car-fault-index').datagrid('reload');
								$('#easyui-dialog-car-fault-index-dispose').dialog('close');
							}else{
								$.messager.alert('处理失败',data.info,'error');
							}
						}
					});
				}
			},{
				text:'取消',
				iconCls:'icon-cancel',
				handler:function(){
					$('#easyui-dialog-car-fault-index-dispose').dialog('close');
				}
			}],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
        //初始化查看详细窗口
		$('#easyui-window-car-fault-index-detail').window({
        	title: '车辆故障详细',   
            width: 800,   
            height: 500,   
            closed: false,   
            cache: true,   
            modal: true,
            closed: true,
            maximizable: true,
            minimizable: false,
            collapsible: false
		});
	}
	CarFaultIndex.init();
	//获取选择的记录
	CarFaultIndex.getSelected = function(){
		var datagrid = $('#easyui-datagrid-car-fault-index');
		var selectRow = datagrid.datagrid('getSelected');
		if(!selectRow){
			$.messager.alert('错误','请选择要操作的记录','error');   
			return false;
		}
		return selectRow.id;
	}
	//登记
	CarFaultIndex.register = function(){
		$('#easyui-dialog-car-fault-index-register').dialog('open');
		$('#easyui-dialog-car-fault-index-register').dialog('refresh','<?php echo yii::$app->urlManager->createUrl(['car/fault/register','carId'=>$carId]); ?>');
	}
	//故障处理
	CarFaultIndex.dispose = function(){
		try{
    		if(typeof ueditor_car_fault_dispose_self != 'undefined'){  
    			ueditor_car_fault_dispose_self.destroy();  
    		}
    		if(typeof ueditor_car_fault_dispose_garage != 'undefined'){  
    			ueditor_car_fault_dispose_garage.destroy();  
    		}
		}catch(e){

		}
		id = this.getSelected();
		if(!id){
			return false;
		}
		$('#easyui-dialog-car-fault-index-dispose').dialog('open');
		$('#easyui-dialog-car-fault-index-dispose').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/fault/dispose']); ?>&id="+id);
	}
	//查看详细
	CarFaultIndex.detail = function(){
		var id = this.getSelected();
		if(!id){
			return false;
		}
		$('#easyui-window-car-fault-index-detail').window('open');
		$('#easyui-window-car-fault-index-detail').window('refresh','<?php echo yii::$app->urlManager->createUrl(['car/fault/detail']); ?>&id='+id);
	}
	//查询
	CarFaultIndex.search = function(){
		var data = $('#search-form-car-fault-index').serializeArray();
		var searchData = {};
		for(var i in data){
			searchData[data[i].name] = data[i].value;
		}
		$('#easyui-datagrid-car-fault-index').datagrid('load',searchData);
	}
</script>