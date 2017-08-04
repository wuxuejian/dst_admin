<table id="easyui-datagrid-process-car-tiche"></table> 
<div id="easyui-datagrid-process-car-tiche-toolbar">

<div id="easyui-datagrid-process-car-tiche-toolbar">

    <div class="easyui-panel" title="数据列表" style="padding:3px 2px;width:100%;" data-options="
        iconCls: 'icon-table-list',
        border: false
    ">
		<a target="_blank" href="<?php echo !empty($result['extract_auth_image']) ? $result['extract_auth_image'] : "javascript:$.messager.alert('查看提车授权书','没有上传！','info');";?>" class="easyui-linkbutton" data-options="iconCls:'icon-search'"><?php echo '查看提车授权书'; ?></a>
		<a target="_blank" href="<?php echo !empty($result['extract_user_image']) ? $result['extract_user_image'] : "javascript:$.messager.alert('查看提车人证件附件','没有上传！','info');";?>" class="easyui-linkbutton" data-options="iconCls:'icon-search'"><?php echo '查看提车人证件附件'; ?></a>
    </div>
</div>
</div>
<!-- 窗口 -->
<div id="easyui-dialog-process-car-tiche-add"></div>
<!-- 窗口 -->
<script>
    var ProcessCarTiche = new Object();
    ProcessCarTiche.init = function(){
        //获取列表数据process-config
        $('#easyui-datagrid-process-car-tiche').datagrid({  
            method: 'POST', 
            url:"<?php echo yii::$app->urlManager->createUrl(['process/car/get-list']); ?>&id=<?php echo $result['id'] ?>",   
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
			pageSize: 10,
            frozenColumns: [[
                {field: 'id',title: 'id',hidden: true}
            ]],
            columns: [[
                 {field: 'car_no',title: '车牌号',width: 120,align: 'center',sortable: true},
                 {field: 'vehicle_license',title: '行驶证',width: 120,align: 'center',sortable: true},
                 {field: 'road_transport',title: '道路运输证',width: 120,align: 'center',sortable: true},
                 {field: 'insurance',title: '交强险',width: 120,align: 'center',sortable: true},
                 {field: 'business_risks',title: '商业险',width: 120,align: 'center',sortable: true},
                 /*{field: 'monitoring',title: '监控数据',width: 120,align: 'center',sortable: true},*/
                 {field: 'certificate',title: '随车证件/工具',width: 120,align: 'center',sortable: true,
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
                         	  return '不充足';
                          }
                      }
                  },
                 {field: 'verify_car_photo',title: '验车单',width: 120,align: 'center', sortable: true,
                	  formatter: function(value){
                          if(value){
                              //<img width="80px;" src="'+value+'"></img>
                              return '<a href="'+value+'" target="_blank">查看</a>';
                          }else{
                         	  return '没有上传';
                          }
                      }
                 },    
                 {field: 'username',title: '操作人',width: 120,align: 'center', sortable: true,},   
            ]],
            onLoadSuccess: function (data){
               // alert(data.total);
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
	$('#easyui-dialog-process-car-tiche-add').dialog({
    	title: '添加车辆',   
        width: '450px',   
        height: '450px',   
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
					url: "<?php echo yii::$app->urlManager->createUrl(['process/car/add-tiche']); ?>&id=<?php echo $result['id'] ?>",
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

</script>