<table id="easyui-datagrid-process-car-tiche"></table> 
<form  id="easyui-form-process-car-tiche-from" class="easyui-form" method="post">
<input type="text" name="id" value="<?php echo $result['id'];?>" /><!-- id -->
<input type="text" name="step_id" value="<?php echo $result['step_id'];?>" /><!-- step_id -->
<input type="text" name="template_id" value="<?php echo $result['template_id'];?>" /><!-- template_id -->
</form>

<div id="easyui-datagrid-process-car-tiche-toolbar">

    <div class="easyui-panel" title="数据列表" style="padding:3px 2px;width:100%;" data-options="
        iconCls: 'icon-table-list',
        border: false
    ">
    <div class="data-search-form" style="margin-bottom:5px;">
            <form id="search-form-process-car-index">
                <ul class="search-main">
                <li>提车需求车辆：</li>
                <?php if($tiche_sites):?>
                <?php foreach ($tiche_sites as $v):?>
                    <li style="width:350px">
                        <span>提车点：<?php echo $v['site']?>; 负责人：<?php echo $v['user_id']?>; 整备车型：<?php echo $v['brand_type']?> <?php echo $v['car_number'].'辆';?></span>
                    </li>
                 <?php endforeach;?>  
                <?php else:?>
                	<?php foreach ($car_type as $k=>$v):?>
                	<li style="width:350px">
                        <span><?php echo $k?> : <?php echo $v.'辆';?></span>
                    </li>
                    <?php endforeach;?>
                <?php endif;?>
                </ul>
            </form>
        </div>
        <a href="javascript:ProcessCarTiche.add();" class="easyui-linkbutton" data-options="iconCls:'icon-add'"><?php echo '添加车辆'; ?></a>
		<a href="javascript:ProcessCarTiche.remove();" class="easyui-linkbutton" data-options="iconCls:'icon-remove'"><?php echo '移除车辆'; ?></a>
		<!--  <a href="javascript:ProcessCarTiche.upload();" class="easyui-linkbutton" data-options="iconCls:'icon-add'"><?php //echo '上传提车附件'; ?></a>
		<a target="_blank" href="<?php //echo !empty($result['extract_auth_image']) ? $result['extract_auth_image'] : "javascript:$.messager.alert('查看提车授权书','没有上传！','info');";?>" class="easyui-linkbutton" data-options="iconCls:'icon-search'"><?php //echo '查看提车授权书'; ?></a>
		<a target="_blank" href="<?php //echo !empty($result['extract_user_image']) ? $result['extract_user_image'] : "javascript:$.messager.alert('查看提车人证件附件','没有上传！','info');";?>" class="easyui-linkbutton" data-options="iconCls:'icon-search'"><?php //echo '查看提车人证件附件'; ?></a>
		-->
    </div>
</div>
<!-- 窗口 -->
<div id="easyui-dialog-process-car-tiche-add"></div>
<div id="easyui-dialog-process-car-tiche-upload"></div>
<!-- 窗口 -->
<script>
    var ProcessCarTiche = new Object();
    ProcessCarTiche.init = function(){
        //获取列表数据process-config
        $('#easyui-datagrid-process-car-tiche').datagrid({  
            method: 'POST', 
            url:"<?php echo yii::$app->urlManager->createUrl(['process/car/get-list']); ?>&id=<?php echo $result['id'] ?>&flag=1",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-process-car-tiche-toolbar",
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
                 {field: 'car_no',title: '车牌号',width: 120,align: 'center',sortable: true},
                 {field: 'car_type',title: '品牌型号',width: 120,align: 'center',sortable: true},
                 {field: 'vehicle_license',title: '行驶证年审日期',width: 120,align: 'center',sortable: true},
                 {field: 'road_transport',title: '道路运输证年审日期',width: 120,align: 'center',sortable: true},
                 {field: 'insurance',title: '交强险有效期',width: 120,align: 'center',sortable: true},
                 {field: 'business_risks',title: '商业险有效期',width: 120,align: 'center',sortable: true},
                 {field: 'monitoring',title: '监控数据更新日期',width: 120,align: 'center',sortable: true},
                 {field: 'certificate',title: '随车工具',width: 120,align: 'center',sortable: true,
                	 formatter: function(value){
                         if(value == 1){
                             return '已备齐';
                         }else{
                        	 return '未备齐';
                         }
                     }
				
                  },
                 {field: 'electricity',title: '电量充足',width: 120,align: 'center',sortable: true,
                	  formatter: function(value){
                          if(value == 1){
                              return '充足';
                          }else{
                         	  return '不足';
                          }
                      }
                  },
                 {field: 'follow_car_card',title: '随车证件',width: 120,align: 'center',sortable: true},
                 {field: 'follow_car_data',title: '随车资料',width: 120,align: 'center',sortable: true},        
                 {field: 'username',title: '操作人',width: 120,align: 'center', sortable: true},   
            ]],
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
            },
        });
    }

    
	//初始化添加窗口
	$('#easyui-dialog-process-car-tiche-add').dialog({
    	title: '添加车辆',   
        width: '500px',   
        height: '650px',   
        closed: true,   
        cache: true,   
        modal: true,
        resizable:true,
        maximizable: true,
        buttons: [{
			text:'确定',
			iconCls:'icon-ok',
			handler:function(){
                var form = $('#easyui-form-process-car-tiche-add-from');
                if(!form.form('validate')) return false;
				//var data = form.serialize();
				var data = new FormData($('#easyui-form-process-car-tiche-add-from')[0]);  
				$.ajax({
					type: 'post',
					url: "<?php echo yii::$app->urlManager->createUrl(['process/car/add-tiche']); ?>",
					data: data,
					dataType: 'json',
					cache: false,  
			        processData: false,  
			        contentType: false, 
					success: function(data){
						if(data.status){
							$.messager.alert('添加成功',data.info,'info');
							$('#easyui-dialog-process-car-tiche-add').dialog('close');
							$('#easyui-datagrid-process-car-tiche').datagrid('reload');
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
				$('#easyui-dialog-process-car-tiche-add').dialog('close');
			}
		}],
        onClose: function(){
            $(this).dialog('clear');
        }
    });


  	//执行
    ProcessCarTiche.init();
    //获取选择的记录
    ProcessCarTiche.getSelected = function(){
        var datagrid = $('#easyui-datagrid-process-car-tiche');
        var selectRow = datagrid.datagrid('getSelected');
        if(!selectRow){
            $.messager.alert('错误','请选择要操作的记录','error');   
            return false;
        }
        return selectRow;
    }

  //添加车辆    备车数与需求数对比 ，查询是否已备完
    ProcessCarTiche.add = function(){
    	$.ajax({
			type: 'post',
			url: '<?php echo yii::$app->urlManager->createUrl(['process/car/count-contrast']); ?>',
			data: {id: <?php echo $result['id'];?>},
			dataType: 'json',
			success: function(data){
				if(data.status){
					$.messager.alert('提示',data.info,'info');   
					return false;
				}else{
					 $('#easyui-dialog-process-car-tiche-add').dialog('open');
				     $('#easyui-dialog-process-car-tiche-add').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/car/add-tiche']); ?>&id=<?php echo $result['id'] ?>");
				}
			}
		});
       
    }


  //删除
	ProcessCarTiche.remove = function(){
		var selectRow = this.getSelected();
        if(!selectRow) return false;
        var id = selectRow.id;
		$.messager.confirm('确定移除','您确定要移除该车辆？',function(r){
			if(r){
				$.ajax({
					type: 'post',
					url: '<?php echo yii::$app->urlManager->createUrl(['process/car/delete-tiche']); ?>&id='+id,
					data: {id: id},
					dataType: 'json',
					success: function(data){
						if(data.status){
							$.messager.alert('移除成功',data.info,'info');   
							$('#easyui-datagrid-process-car-tiche').datagrid('reload');
						}else{
							$.messager.alert('移除失败',data.info,'error');   
						}
					}
				});
			}
		});
	} 


    //重置查询表单
    ProcessCarTiche.resetForm = function(){
        var easyuiForm = $('#search-form-process-car-tiche');
        easyuiForm.form('reset');
    }

    
</script>