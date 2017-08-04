<table id="easyui-datagrid-process-car-jiaoche"></table> 
<form  id="easyui-form-process-car-jiaoche-from" class="easyui-form" method="post">
<input type="text" name="id" value="<?php echo $result['id'];?>" /><!-- id -->
<input type="text" name="step_id" value="<?php echo $result['step_id'];?>" /><!-- step_id -->
<input type="text" name="template_id" value="<?php echo $result['template_id'];?>" /><!-- template_id -->
</form>

<div id="easyui-datagrid-process-car-jiaoche-toolbar">

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
                        <span>提车点:<?php echo $v['site']?>; 负责人：<?php echo $v['user_id']?>; 整备车型：<?php echo $v['brand_type']?> <?php echo $v['car_number'].'辆';?></span>
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
		<a href="javascript:ProcessCarJiaoche.replace();" class="easyui-linkbutton" data-options="iconCls:'icon-edit'"><?php echo '更换车辆'; ?></a>
		<a href="javascript:ProcessCarJiaoche.udelivery();" class="easyui-linkbutton" data-options="iconCls:'icon-edit'"><?php echo '交付车辆'; ?></a>
		<a href="javascript:ProcessCarJiaoche.upload();" class="easyui-linkbutton" data-options="iconCls:'icon-add'"><?php echo '登记客户提车人信息'; ?></a>
		<a target="_blank" href="<?php echo !empty($result['extract_auth_image']) ? $result['extract_auth_image'] : "javascript:$.messager.alert('查看提车授权书','没有上传！','info');";?>" class="easyui-linkbutton" data-options="iconCls:'icon-search'"><?php echo '查看提车授权书'; ?></a>
		<a target="_blank" href="<?php echo !empty($result['extract_user_image']) ? $result['extract_user_image'] : "javascript:$.messager.alert('查看提车人证件附件','没有上传！','info');";?>" class="easyui-linkbutton" data-options="iconCls:'icon-search'"><?php echo '查看提车人证件附件'; ?></a>
    </div>
</div>
<!-- 窗口 -->
<div id="easyui-dialog-process-car-jiaoche-upload"></div>
<div id="easyui-dialog-process-car-jiaoche-udelivery"></div><!-- 交付车辆 -->
<div id="easyui-dialog-process-car-jiaoche-replace"></div><!-- 更换车辆 -->


<!-- 窗口 -->
<script>
    var ProcessCarJiaoche = new Object();
    ProcessCarJiaoche.init = function(){
        //获取列表数据process-config
        $('#easyui-datagrid-process-car-jiaoche').datagrid({  
            method: 'POST', 
            url:"<?php echo yii::$app->urlManager->createUrl(['process/car/get-list']); ?>&id=<?php echo $result['id'] ?>&is_jiaoche=1&flag=1",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-process-car-jiaoche-toolbar",
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
                 {field: 'is_delivery',title: '交付状态',width: 120,align: 'center',sortable: true,
                	 formatter: function(value){
                         if(value == 1){
                             return '已交付';
                         }else if(value== -1){
                        	 return '';
                         }else{
                        	 return '未交付';
                         }
                     }
                },
                {field: 'verify_car_photo',title: '交车单',width: 120,align: 'center', sortable: true,
              	  formatter: function(value){
                        if(value){
                            return '<a href="'+value+'" target="_blank">查看</a>';
                        }else{
                       	  return '没有上传';
                        }
                    }
               },
               {field: 'jiaoche_time',title: '交车时间',width: 120,align: 'center',sortable: true},
                 {field: 'remark',title: '备注',width: 360,align: 'center',sortable: true},
                 
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
            }
        });
    }



	//上传提车人附件窗口
	$('#easyui-dialog-process-car-jiaoche-upload').dialog({
		title: '上传',   
	    width: '650px',   
	    height: '350px',   
	    closed: true,   
	    cache: true,   
	    modal: true,
	    resizable:true,
	    maximizable: true,
	    buttons: [{
			text:'确定',
			iconCls:'icon-ok',
			handler:function(){

				if($("input[name='extract_auth_image']").val() == '' &&$("input[name='extract_user_image']").val() == '' )
				{
					$.messager.alert('提交失败','“提车人授权书”、“提车人身份证”中，二者必须上传其中一个','error');
					return false;
				}
				
	            var form = $('#easyui-form-process-car-contract-upload-from');
	            if(!form.form('validate')) return false;
				var data = new FormData($('#easyui-form-process-car-contract-upload-from')[0]);  
				$.ajax({
					type: 'post',
					url: "<?php echo yii::$app->urlManager->createUrl(['process/car/upload']); ?>",
					data: data,
					dataType: 'json',  
			        cache: false,  
			        processData: false,  
			        contentType: false, 
					success: function(data){
						if(data.status){
							$.messager.alert('上传成功',data.info,'info');
							$('#easyui-dialog-process-car-jiaoche-upload').dialog('close');
							$('#easyui-dialog-process-car-index-jiaoche').dialog('close');
							//$('#easyui-datagrid-process-car-jiaoche').datagrid('reload');
						}else{
							$.messager.alert('上传失败',data.info,'error');
						}
					}
				});
			}
		},{
			text:'取消',
			iconCls:'icon-cancel',
			handler:function(){
				$('#easyui-dialog-process-car-jiaoche-upload').dialog('close');
			}
		}],
	    onClose: function(){
	        $(this).dialog('clear');
	    }
	});

	//初始化更换车辆窗口 
	$('#easyui-dialog-process-car-jiaoche-replace').dialog({
    	title: '交付车辆',   
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
                var form = $('#easyui-form-process-car-jiaoche-replace-form');
                if(!form.form('validate')) return false;
				//var data = form.serialize();
				var data = new FormData($('#easyui-form-process-car-jiaoche-replace-form')[0]);  
				$.ajax({
					type: 'post',
					url: "<?php echo yii::$app->urlManager->createUrl(['process/car/replace']); ?>",
					data: data,
					dataType: 'json',
					cache: false,  
			        processData: false,  
			        contentType: false, 
					success: function(data){
						if(data.status){
							$.messager.alert('更换成功',data.info,'info');
							$('#easyui-dialog-process-car-jiaoche-replace').dialog('close');
							$('#easyui-datagrid-process-car-jiaoche').datagrid('reload');
						}else{
							$.messager.alert('更换失败',data.info,'error');
						}
					}
				});
			}
		},{
			text:'取消',
			iconCls:'icon-cancel',
			handler:function(){
				$('#easyui-dialog-process-car-jiaoche-replace').dialog('close');
			}
		}],
        onClose: function(){
            $(this).dialog('clear');
        }
    });
	
	
	
	//初始化交付车辆窗口
	$('#easyui-dialog-process-car-jiaoche-udelivery').dialog({
    	title: '交付车辆',   
        width: '750px',   
        height: '350px',   
        closed: true,   
        cache: true,   
        modal: true,
        resizable:true,
        maximizable: true,
        buttons: [{
			text:'确定',
			iconCls:'icon-ok',
			handler:function(){
                var form = $('#easyui-form-process-car-jiaoche-udelivery-form');
                if(!form.form('validate')) return false;
				//var data = form.serialize();
				var data = new FormData($('#easyui-form-process-car-jiaoche-udelivery-form')[0]);  
				$.ajax({
					type: 'post',
					url: "<?php echo yii::$app->urlManager->createUrl(['process/car/udelivery']); ?>",
					data: data,
					dataType: 'json',
					cache: false,  
			        processData: false,  
			        contentType: false, 
					success: function(data){
						if(data.status){
							$.messager.alert('交付成功',data.info,'info');
							$('#easyui-dialog-process-car-jiaoche-udelivery').dialog('close');
							$('#easyui-datagrid-process-car-jiaoche').datagrid('reload');
						}else{
							$.messager.alert('交付失败',data.info,'error');
						}
					}
				});
			}
		},{
			text:'取消',
			iconCls:'icon-cancel',
			handler:function(){
				$('#easyui-dialog-process-car-jiaoche-udelivery').dialog('close');
			}
		}],
        onClose: function(){
            $(this).dialog('clear');
        }
    });

    
  	//执行
    ProcessCarJiaoche.init();
    //获取选择的记录
    ProcessCarJiaoche.getSelected = function(){
        var datagrid = $('#easyui-datagrid-process-car-jiaoche');
        var selectRow = datagrid.datagrid('getSelected');
        if(!selectRow){
            $.messager.alert('错误','请选择要操作的记录','error');   
            return false;
        }
        return selectRow;
    }


	//更换车辆  
	ProcessCarJiaoche.replace = function(){
		var selectRow = this.getSelected();
        if(!selectRow) return false;
        var id = selectRow.id;
        $('#easyui-dialog-process-car-jiaoche-replace').dialog('open');
        $('#easyui-dialog-process-car-jiaoche-replace').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/car/replace']); ?>&id="+id);
	}
    
	//交付车辆
	ProcessCarJiaoche.udelivery = function(){
		var selectRow = this.getSelected();
        if(!selectRow) return false;
        var id = selectRow.id;
        $('#easyui-dialog-process-car-jiaoche-udelivery').dialog('open');
        $('#easyui-dialog-process-car-jiaoche-udelivery').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/car/udelivery']); ?>&id="+id);
	}
	
	  
	//上传提车人附件	
	ProcessCarJiaoche.upload = function(){
        $('#easyui-dialog-process-car-jiaoche-upload').dialog('open');
        $('#easyui-dialog-process-car-jiaoche-upload').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/car/upload']); ?>&id=<?php echo $result['id'] ?>");
    }
    //重置查询表单
    ProcessCarJiaoche.resetForm = function(){
        var easyuiForm = $('#search-form-process-car-jiaoche');
        easyuiForm.form('reset');
    }

    
</script>