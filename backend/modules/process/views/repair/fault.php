<table id="easyui-datagrid-process-repair-fault-index"></table>
<div id="easyui-datagrid-process-repair-fault-index-toolbar">
    <div class="easyui-panel" style="padding:10px 20px" data-options="border:false">
            <table cellpadding="5" cellspacing="0" border="0" width="80%" >
                <tr>
                    <td width="100px">故障发生时间：</td>
                    <td><?php echo  !empty($maintain['fault_start_time']) ? date('Y-m-d H:i',$maintain['fault_start_time']):''; ?></td>
                    <td width="100px">故障发生地点：</td>
                    <td><?php echo $maintain['fault_address']; ?></td>
                </tr>
                <tr>
                    <td width="100px">路面情况：</td>
                    <td >
                    <?php  
                    $road_situation = json_decode($maintain['road_situation'],true); 
                     echo is_array($road_situation) ? implode(' ', $road_situation):'';
                    ?></td>
                    <td width="100px">天气情况：</td>
                    <td align ="left"><?php echo $maintain['weather_situation'];  ?></td>
                </tr>
                <tr>
                    <td width="100px">气温情况：</td>
                    <td align ="left"><?php echo $maintain['temperature_situation'];  ?></td>
                    <td width="100px">车辆时速：</td>
                    <td align ="left"><?php echo $maintain['vehicle_speed'];  ?></td>
                </tr>
                <tr>
                    <td width="100px">故障代码：</td>
                    <td ><?php echo $maintain['fault_code']; ?></td>
                    <td width="100px">当前行驶里程：</td>
                    <td align ="left"><?php echo $maintain['current_mileage'];  ?></td>
                </tr>
                 <tr>
                    <td width="100px">重启后故障消失：</td>
                    <td ><?php echo !empty($maintain['vehicle_launch']) ?'是':'否' ; ?></td>
                    <td width="100px">故障指示灯：</td>
                    <td align ="left" width="150px">
                    <?php if($maintain['indicator_light']):
                    	$indicator_lights = json_decode($maintain['indicator_light'],true);
                    ?>
                     <?php foreach ($indicator_lights as $indicator_light):?>
                      <img src="<?php echo $indicator_light['image_url']?>" width="40" height="40" style=" border:2px solid #ccc;" />
                      <?php endforeach;?>
                    <?php endif;?>
                    </td>
                </tr>
                
                <tr>
                    <td width="100px">故障描述：</td>
                    <td><?php echo $maintain['scene_desc']; ?></td>
                    <td width="100px">现场处理结果：</td>
                    <td><?php echo $maintain['scene_result'];  ?></td>
                </tr>
                <tr>
                    <td width="100px">故障引发原因：</td>
                    <td ><?php echo $maintain['fault_why']; ?></td>
                    <td width="100px">故障维修方法：</td>
                    <td><?php echo $maintain['maintain_method']; ?></td>
                </tr>
                <tr>
                    <td width="100px">更换配件：</td>
                    <td ><?php echo $maintain['accessories']; ?></td>
                    <td width="100px">本方受理人：</td>
                    <td><?php echo $maintain['accept_name']; ?></td>
                </tr>
            </table>
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

<div id="easyui-dialog-process-repair-fault-add"></div>

<script type="text/javascript">
	var ProcessRepairFault = new Object();
	ProcessRepairFault.init = function(){
	    //获取列表数据process-repair
	    $('#easyui-datagrid-process-repair-fault-index').datagrid({  
	        method: 'POST', 
	        url:"<?php echo yii::$app->urlManager->createUrl(['process/repair/fault']); ?>&maintain_id=<?php echo $maintain_id; ?>",   
	        fit: true,
	        border: false,
	        toolbar: "#easyui-datagrid-process-repair-fault-index-toolbar",
	        pagination: true,
	        loadMsg: '数据加载中...',
	        striped: true,
	        checkOnSelect: true,
	        rownumbers: true,
	        singleSelect: true,
	        showFooter: true,
			pageSize: 20,
	        frozenColumns: [[
	            {field: 'ck',checkbox: true}, 
	            {field: 'id',title: 'id',hidden: true}
	        ]],
	        columns: [[
	                   {title: '故障分类',colspan: 6}, // 跨几列
	                   {field: 'category',title: '故障名称', rowspan:2,width: 200,align: 'left',sortable: true},
	                   {field: 'total_code',title: '总故障编码', rowspan:2,width: 80,align: 'center', sortable: true,}, 
	              ],
	              	[
	  	               {field: 'category1',title: '故障大类',width: 120,align: 'center'},
	  	               {field: 'code1',title: '编码',width: 50,align: 'center'},
	  	               {field: 'category2',title: '故障级别',width: 80,align: 'center'},
	  	               {field: 'code2',title: '编码',width: 50,align: 'center'},
	  	               {field: 'category3',title: '故障原因大类',width: 120,align: 'center'},
	  	               {field: 'code3',title: '编码',width: 50,align: 'center'},
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
	}

	 //初始化添加窗口
	$('#easyui-dialog-process-repair-fault-add').dialog({
    	title: '添加车辆故障信息',   
        width: '600px',   
        height: '200px',   
        closed: true,   
        cache: true,   
        modal: true,
        resizable:true,
        maximizable: true,
        buttons: [{
			text:'确定',
			iconCls:'icon-ok',
			handler:function(){
                var form = $('#easyui-form-process-repair-fault-add-from');
                if(!form.form('validate')) return false;
				var data = form.serialize();
				$.ajax({
					type: 'post',
					url: "<?php echo yii::$app->urlManager->createUrl(['process/repair/fault-add']); ?>",
					data: data,
					dataType: 'json',
					success: function(data){
						if(data.status){
							$.messager.alert('添加成功',data.info,'info');
							$('#easyui-dialog-process-repair-fault-add').dialog('close');
							$('#easyui-datagrid-process-repair-fault-index').datagrid('reload');
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
				$('#easyui-dialog-process-repair-fault-add').dialog('close');
			}
		}],
        onClose: function(){
            $(this).dialog('clear');
        }
    });

	
	// 执行初始化函数
	ProcessRepairFault.init();

	ProcessRepairFault .getSelected = function(){
        var datagrid = $('#easyui-datagrid-process-repair-fault-index');
        var selectRow = datagrid.datagrid('getSelected');
        if(!selectRow){
            $.messager.alert('错误','请选择要操作的记录','error');   
            return false;
        }
        return selectRow;
    }
	
	//增加
    ProcessRepairFault .add = function(){
        $('#easyui-dialog-process-repair-fault-add').dialog('open');
        $('#easyui-dialog-process-repair-fault-add').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/repair/fault-add']); ?>&maintain_id=<?php echo $maintain_id;?>");
    }

    //删除
	ProcessRepairFault .del = function(){
		var selectRow = this.getSelected();
        if(!selectRow) return false;
        var id = selectRow.id;
		$.messager.confirm('确定删除','您确定要删除该车辆故障信息？',function(r){
			if(r){
				$.ajax({
					type: 'post',
					url: '<?php echo yii::$app->urlManager->createUrl(['process/repair/fault-del']); ?>',
					data: {id: id},
					dataType: 'json',
					success: function(data){
						if(data.status){
							$.messager.alert('删除成功',data.info,'info');   
							$('#easyui-datagrid-process-repair-fault-index').datagrid('reload');
						}else{
							$.messager.alert('删除失败',data.info,'error');   
						}
					}
				});
			}
		});
	}
</script>