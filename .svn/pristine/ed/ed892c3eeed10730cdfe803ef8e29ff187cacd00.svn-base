<table id="easyui-datagrid-process-car-index"></table> 
<div id="easyui-datagrid-process-car-index-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-process-car-index">
                <ul class="search-main">
                    <li>
                        <div class="item-name">客户名称</div>
                        <div class="item-input">
                            <input name="name" style="width:100%;" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">申请时间</div>
                        <div class="item-input">
                            <input name="shenqing_time_start" style="width:90px;"/>
                            -
                            <input name="shenqing_time_end" style="width:90px;" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">车牌号</div>
                        <div class="item-input">
                            <input class="easyui-textbox" name="car_no" style="width:100%;" />
                        </div>
                    </li>
                     <li>
                        <div class="item-name">交车负责人</div>
                        <div class="item-input">
                            <input class="easyui-textbox" name="tiche_manage_user" style="width:100%;" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name"></div>
                        <div class="item-input">
                            <input name="my_approvel" style="width:100%;"  data-options="editable:false" />

                        </div>
                    </li>
                    <!--  <li>
                        <div class="item-name">状态</div>
                        <div class="item-input">
                            <input class="easyui-textbox" name="current_status" style="width:100%;" />
                        </div>
                    </li>-->
                    <li class="search-button">
                        <button class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button onclick="ProcessCarIndex.resetForm();" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
                    </li>
                </ul>
            </form>
        </div>
    </div>
   
    <div  class="easyui-panel" title="数据列表" style="padding:3px 2px;width:100%;" data-options="iconCls: 'icon-table-list',border: false">
     <?php if($buttons): ?>
        <?php foreach($buttons as $val):?>
        <a href="javascript:<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon']; ?>'"><?= $val['text']; ?></a>
        <?php endforeach; ?>
      <?php endif; ?>
   <!--     <span id="operation" style="padding:3px 2px;width:100%;" data-options="iconCls: 'icon-table-list',border: false"></span>-->
   <span  id="operation"   title="数据列表" style="padding:3px 2px;width:100%;" data-options="iconCls: 'icon-table-list',border: false"></span>
    </div>
  
</div>
<!-- 窗口 -->
<div id="easyui-dialog-process-car-index-add"></div>
<div id="easyui-dialog-process-car-index-edit"></div>
<div id="easyui-dialog-process-car-index-steps"></div>
<div id="easyui-dialog-process-car-index-no-pass"></div> <!-- 驳回窗口 -->

<div id="easyui-dialog-process-car-index-tiche"></div> <!-- 登记提车窗口 -->

<div id="easyui-dialog-process-car-index-trace"></div> <!-- 流程追踪 -->
<div id="easyui-dialog-process-car-index-record"></div> <!-- 查看备车车辆 -->
<div id="easyui-dialog-process-car-index-info"></div> <!-- 查看详情 -->
<div id="easyui-dialog-process-car-index-pass"></div> <!-- 通过窗口 -->

<div id="easyui-dialog-process-car-index-tiche-site"></div> <!-- 提车点 -->
<div id="easyui-dialog-process-car-index-jiaoche"></div> <!-- 车辆交付 -->
<div id="easyui-dialog-process-car-index-archive"></div> <!-- 车辆归档 -->
<div id="easyui-dialog-process-car-index-rent"></div> <!-- 登记租金信息-->
<div id="easyui-dialog-process-car-index-proceeds"></div> <!-- 收款方式-->
<div id="easyui-dialog-process-car-index-assign"></div> <!-- 指派提车点负责人 -->



<!-- 窗口 -->
<script>

$(function(){
	$('#easyui-datagrid-process-car-index').datagrid({
	onClickRow: function(rowIndex) {
		 $('#easyui-datagrid-process-car-index').datagrid('selectRow',rowIndex);
		  var currentRow =$("#easyui-datagrid-process-car-index").datagrid("getSelected");

		  var id = currentRow.id;
		  var is_cancel = currentRow.is_cancel;
          //var action = 'process/car/index';

		  $.ajax({
				type: 'post',
				url: "<?php echo yii::$app->urlManager->createUrl(['process/car/operation']); ?>",
				data: {id:id,is_cancel:is_cancel},
				dataType: 'json',
				success: function(data){
					$("#operation").empty();
					$("#operation").append(data.current_operation);
				}
			});
		}
	});
});

    var ProcessCarIndex = new Object();
    ProcessCarIndex.init = function(){
        //获取列表数据process-config
        $('#easyui-datagrid-process-car-index').datagrid({  
            method: 'POST', 
            url:"<?php echo yii::$app->urlManager->createUrl(['process/car/index']); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-process-car-index-toolbar",
            pagination: true,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
            showFooter: true,
			pageSize: 20,
            frozenColumns: [[
				/*{field: 'ck',
					formatter:function(value,row,index){
						if(row.checked){
							return '<input type="checkbox" name="DataGridCheckbox" checked="checked">';
						}else{
							return '<input type="checkbox" name="DataGridCheckbox">';
						}
					}
                }, */
                {field: 'id',title: 'id',hidden: true},
                {field: 'is_cancel',title: 'is_cancel',hidden: true}
            ]],
            columns: [[
				 {field: 'id',title: '流程ID',width: 50,align: 'center',sortable: true},
				 {field: 'username',title: '申请人',width: 80,align: 'center',sortable: true},
				 {field: 'department_id',title: '申请部门',width: 110,align: 'center',sortable: true},
                 {field: 'shenqing_time',title: '申请时间',width: 120,align: 'center', sortable: true,},
                 {field: 'car_type',title: '车辆品牌',width: 180,align: 'left',sortable: true},
                 {field: 'number',title: '需求数量',width: 70,align: 'center',sortable: true},
                 {field: 'jc_number',title: '交车数量',width: 70,align: 'center',sortable: true},
                 {field: 'current_status',title: '状态',width: 200,align: 'left', sortable: true,},
               //  {field: 'extract_remark',title: '备注',width: 200,align: 'center', sortable: true,},
                 {field: 'count_down',title: '审批倒计时',width: 100,align: 'center', sortable: true,}, 
                 {field: 'extract_time',title: '提车时间',width: 120,align: 'center',sortable: true},
                 {field: 'extract_way',title: '提车方式',width: 120,align: 'center', sortable: true,},
                 {field: 'name',title: '客户名称',width: 200,align: 'left',sortable: true},  
                 {field: 'contract_number',title: '合同编号',width: 150,align: 'left',sortable: true},  
                 
                // {field: 'margin',title: '应收保证金',width: 120,align: 'center',sortable: true},
               //  {field: 'rent',title: '应收租金',width: 120,align: 'center',sortable: true},
                 
                // {field: 'current_operation',title: '操作',width: 200,align: 'center', sortable: true,},                     
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
                $("#operation").empty();
            }
        });
    }
	//初始化添加窗口（◆）
	$('#easyui-dialog-process-car-index-add').dialog({
    	title: '发起申请',   
        width: '900px',   
        height: '500px',   
        closed: true,   
        cache: true,   
        modal: true,
        resizable:true,
        maximizable: true,
        buttons: [{
			text:'保存',
			iconCls:'icon-ok',
			handler:function(){
                var form = $('#easyui-form-process-car-add');
                if(!form.form('validate')) return false;
                var button = this;
                $(button).linkbutton('disable');
				var data = form.serialize();
				$.ajax({
					type: 'post',
					url: "<?php echo yii::$app->urlManager->createUrl(['process/car/add']); ?>",
					data: data,
					dataType: 'json',
					success: function(data){
						$(button).linkbutton('enable');
						if(data.status){
							$.messager.alert('添加成功',data.info,'info');
							$('#easyui-dialog-process-car-index-add').dialog('close');
							$('#easyui-datagrid-process-car-index').datagrid('reload');
						}else{
							$.messager.alert('添加失败',data.info,'error');
						}
					}
				});
			}
		},{
			
			/*text:'取消',
			iconCls:'icon-cancel',
			handler:function(){
				$('#easyui-dialog-process-car-index-add').dialog('close');
			}*/
			text:'提交',
			iconCls:'icon-ok',
			handler:function(){
            var form = $('#easyui-form-process-car-add');
            if(!form.form('validate')) return false;
            var button = this;
            $(button).linkbutton('disable'); 
			var data = form.serialize();
			$.ajax({
				type: 'post',
				url: "<?php echo yii::$app->urlManager->createUrl(['process/car/confirm']); ?>&editer=add",
				data: data,
				dataType: 'json',
				success: function(data){
					$(button).linkbutton('enable');
					if(data.status){
						$.messager.alert('提交成功',data.info,'info');
						$('#easyui-dialog-process-car-index-add').dialog('close');
						$('#easyui-datagrid-process-car-index').datagrid('reload');
					}else{
						$.messager.alert('提交失败',data.info,'error');
					}
				}
			});
			}
		}],
        onClose: function(){
            $(this).dialog('clear');
        }
    });

	//初始化编辑窗口（◆）
	$('#easyui-dialog-process-car-index-edit').dialog({
    	title: '编辑申请',   
        width: '900px',   
        height: '500px',   
        closed: true,   
        cache: true,   
        modal: true,
        resizable:true,
        maximizable: true,
        buttons: [{
			text:'保存',
			iconCls:'icon-ok',
			handler:function(){
                var form = $('#easyui-form-process-car-edit');
                if(!form.form('validate')) return false;
                var button = this;
                $(button).linkbutton('disable');  
				var data = form.serialize();
				$.ajax({
					type: 'post',
					url: "<?php echo yii::$app->urlManager->createUrl(['process/car/edit']); ?>",
					data: data,
					dataType: 'json',
					success: function(data){
						$(button).linkbutton('enable');
						if(data.status){
							$.messager.alert('编辑成功',data.info,'info');
							$('#easyui-dialog-process-car-index-edit').dialog('close');
							$('#easyui-datagrid-process-car-index').datagrid('reload');
						}else{
							$.messager.alert('编辑失败',data.info,'error');
						}
					}
				});
			}
		},{
		 /*	text:'取消',
			iconCls:'icon-cancel',
			handler:function(){
				$('#easyui-dialog-process-car-index-edit').dialog('close');
			}*/
			text:'提交',
			iconCls:'icon-ok',
			handler:function(){
	        var form = $('#easyui-form-process-car-edit');
	        if(!form.form('validate')) return false;
	        var button = this;
            $(button).linkbutton('disable'); 
			var data = form.serialize();
			$.ajax({
				type: 'post',
				url: "<?php echo yii::$app->urlManager->createUrl(['process/car/confirm']); ?>&editer=edit",
				data: data,
				dataType: 'json',
				success: function(data){
					$(button).linkbutton('enable');
					if(data.status){
						$.messager.alert('提交成功',data.info,'info');
						$('#easyui-dialog-process-car-index-edit').dialog('close');
						$('#easyui-datagrid-process-car-index').datagrid('reload');
					}else{
						$.messager.alert('提交失败',data.info,'error');
					}
				}
			});
		  }
		}],
        onClose: function(){
            $(this).dialog('clear');
        }
    });

  	//初始驳回填写原因管理窗口（◆）
	$('#easyui-dialog-process-car-index-no-pass').dialog({
    	title: '驳回',   
        width: '650px',   
        height: '230px',   
        closed: true,   
        cache: true,   
        modal: true,
        resizable:true,
        maximizable: true,
        buttons: [{
			text:'驳回',
			iconCls:'icon-ok',
			handler:function(){
                var form = $('#easyui-form-process-car-no-pass');
                if(!form.form('validate')) return false;
                var button = this;
                $(button).linkbutton('disable'); 
				var data = form.serialize();
				$.ajax({
					type: 'post',
					url: "<?php echo yii::$app->urlManager->createUrl(['process/car/no-pass']); ?>",
					data: data,
					dataType: 'json',
					success: function(data){
						$(button).linkbutton('enable');
						if(data.status){
							$.messager.alert('操作成功',data.info,'info');
							$('#easyui-dialog-process-car-index-no-pass').dialog('close');
							$('#easyui-datagrid-process-car-index').datagrid('reload');
						}else{
							$.messager.alert('操作失败',data.info,'error');
						}
					}
				});
			}
		},{
			text:'取消',
			iconCls:'icon-cancel',
			handler:function(){
				$('#easyui-dialog-process-car-index-no-pass').dialog('close');
			}
		}],
        onClose: function(){
            $(this).dialog('clear');
        }
    });




	//通过窗口（◆）
	$('#easyui-dialog-process-car-index-pass').dialog({
    	title: '审批',   
    	width: '650px',   
        height: '230px',   
        closed: true,   
        cache: true,   
        modal: true,
        resizable:true,
        maximizable: true,
        buttons: [{
			text:'通过',
			iconCls:'icon-ok',
			handler:function(){
				var form = $('#easyui-form-process-car-pass');
                if(!form.form('validate')) return false;
                var button = this;
                $(button).linkbutton('disable'); 
				var data = form.serialize();
				$.ajax({
					type: 'post',
					url: "<?php echo yii::$app->urlManager->createUrl(['process/car/pass']); ?>",
					data: data,
					dataType: 'json',
					success: function(data){
						$(button).linkbutton('enable'); 
						if(data.status){
							if(data.status == "event")
							{
								$('#easyui-dialog-process-car-index-pass').dialog('close');
								//执行下一步流程步骤
								eval(data.info);
							}else{
								$.messager.alert('操作成功',data.info,'info');
								$('#easyui-dialog-process-car-index-pass').dialog('close');
								$('#easyui-datagrid-process-car-index').datagrid('reload');
							}
						}else{
							$.messager.alert('操作失败',data.info,'error');
						}
					}
				});
			}
		},{
			text:'取消',
			iconCls:'icon-cancel',
			handler:function(){
				$('#easyui-dialog-process-car-index-pass').dialog('close');
			}
		}],
        onClose: function(){
            $(this).dialog('clear');
        }
    });
  	

	//初始化登记提车信息窗口（◆）
	$('#easyui-dialog-process-car-index-tiche').dialog({
    	title: '登记备车信息',   
        width: '800px',   
        height: '600px',   
        closed: true,   
        cache: true,   
        modal: true,
        resizable:true,
        maximizable: true,
        buttons: [{
			text:'完成提交',
			iconCls:'icon-ok',
			handler:function(){
					var form = $('#easyui-form-process-car-tiche-from');
	                if(!form.form('validate')) return false;
	                var button = this;
	                $(button).linkbutton('disable'); 
					var data = form.serialize();
					$.ajax({
						type: 'post',
						url: "<?php echo yii::$app->urlManager->createUrl(['process/car/tiche']); ?>",
						data: data,
						dataType: 'json',
						success: function(data){
							$(button).linkbutton('enable');
							if(data.status){
								$.messager.alert('提交成功',data.info,'info');
								$('#easyui-dialog-process-car-index-tiche').dialog('close');
								$('#easyui-datagrid-process-car-index').datagrid('reload');
							}else{
								$.messager.alert('提交失败',data.info,'error');
							}
					}
				});
			
			}
		},{
			text:'稍后继续',
			iconCls:'icon-cancel',
			handler:function(){
				$('#easyui-dialog-process-car-index-tiche').dialog('close');
			}
		}],
        onClose: function(){
            $(this).dialog('clear');
        }
    });


	//初始化填写租金窗口（◆）
	$('#easyui-dialog-process-car-index-rent').dialog({
    	title: '登记租金信息',   
        width: '800px',   
        height: '600px',   
        closed: true,   
        cache: true,   
        modal: true,
        resizable:true,
        maximizable: true,
        buttons: [{
			text:'完成提交',
			iconCls:'icon-ok',
			handler:function(){
					var form = $('#easyui-form-process-car-tiche-from');
	                if(!form.form('validate')) return false;
	                var button = this;
	                $(button).linkbutton('disable');
					var data = form.serialize();
					$.ajax({
						type: 'post',
						url: "<?php echo yii::$app->urlManager->createUrl(['process/car/rent']); ?>",
						data: data,
						dataType: 'json',
						success: function(data){	
							$(button).linkbutton('enable');						
							if(data.status){
								
								/*$('#easyui-dialog-process-car-index-rent').dialog('close');
								//执行下一步流程步骤
								eval(data.info);*/

								
								$('#easyui-dialog-process-car-index-rent').dialog('close');
								$('#easyui-datagrid-process-car-index').datagrid('reload');
							}else{
								$.messager.alert('提交失败',data.info,'error');
							}
					}
				});
			
			}
		},{
			text:'稍后继续',
			iconCls:'icon-cancel',
			handler:function(){
				$('#easyui-dialog-process-car-index-rent').dialog('close');
			}
		}],
        onClose: function(){
            $(this).dialog('clear');
        }
    });

	//初始化填写收款方式窗口（◆）
	$('#easyui-dialog-process-car-index-proceeds').dialog({
    	title: '收款方式',   
        width: '650px',   
        height: '400px',   
        closed: true,   
        cache: true,   
        modal: true,
        resizable:true,
        maximizable: true,
        buttons: [{
			text:'提交',
			iconCls:'icon-ok',
			handler:function(){
					var form = $('#easyui-form-process-car-proceeds');
	                if(!form.form('validate'))  return false;

					if($("input[type='radio']:checked").val() == 'other'){
						if($.trim($("input[name='other']").val()) == ''){
							$.messager.alert('提交成功','请填写详细的其他方式','error');
							return false;
						}
					}
					var button = this;
	                $(button).linkbutton('disable'); 
					form.ajaxSubmit({
						url: "<?php echo yii::$app->urlManager->createUrl(['process/car/proceeds']); ?>",
						dataType: 'json',
						success: function(data){
							$(button).linkbutton('enable'); 
							if(data.status){
								$.messager.alert('提交成功',data.info,'info');
								$('#easyui-dialog-process-car-index-proceeds').dialog('close');
								$('#easyui-datagrid-process-car-index').datagrid('reload');
							}else{
								$.messager.alert('提交失败',data.info,'error');
							}
					}
				});
			
			}
		},{
			text:'关闭',
			iconCls:'icon-cancel',
			handler:function(){
				$('#easyui-dialog-process-car-index-proceeds').dialog('close');
			}
		}],
        onClose: function(){
            $(this).dialog('clear');
        }
    });
	
	






	//提车点（◆）
	$('#easyui-dialog-process-car-index-tiche-site').dialog({
    	title: '确认提车地点',   
        width: '710px',   
        height: '400px',   
        closed: true,   
        cache: true,   
        modal: true,
        resizable:true,
        maximizable: true,
        buttons: [{
			text:'确定',
			iconCls:'icon-ok',
			handler:function(){
                var form = $('#easyui-form-process-car-tiche-site-form');
                if(!form.form('validate')) return false;
                var button = this;
                $(button).linkbutton('disable'); 
				var data = form.serialize();
				$.ajax({
					type: 'post',
					url: "<?php echo yii::$app->urlManager->createUrl(['process/car/tiche-site']); ?>",
					data: data,
					dataType: 'json',
					success: function(data){
						$(button).linkbutton('enable'); 
						if(data.status){
							$.messager.alert('操作成功',data.info,'info');
							$('#easyui-dialog-process-car-index-tiche-site').dialog('close');
							$('#easyui-datagrid-process-car-index').datagrid('reload');
						}else{
							$.messager.alert('操作失败',data.info,'error');							
						}
					}
				});
			}
		},{
			text:'取消',
			iconCls:'icon-cancel',
			handler:function(){
				$('#easyui-dialog-process-car-index-tiche-site').dialog('close');
			}
		}],
        onClose: function(){
            $(this).dialog('clear');
        }
    });

	//指派提车地点负责人（◆）
	$('#easyui-dialog-process-car-index-assign').dialog({
    	title: '指派站点负责人',   
        width: '680px',   
        height: '275px',   
        closed: true,   
        cache: true,   
        modal: true,
        resizable:true,
        maximizable: true,
        buttons: [{
			text:'确定',
			iconCls:'icon-ok',
			handler:function(){
                var form = $('#easyui-form-process-car-tiche-assign-form');
                if(!form.form('validate')) return false;
                var button = this;
                $(button).linkbutton('disable'); 
				var data = form.serialize();
				$.ajax({
					type: 'post',
					url: "<?php echo yii::$app->urlManager->createUrl(['process/car/assign']); ?>",
					data: data,
					dataType: 'json',
					success: function(data){
						$(button).linkbutton('enable'); 
						if(data.status){
							$.messager.alert('操作成功',data.info,'info');
							$('#easyui-dialog-process-car-index-assign').dialog('close');
							$('#easyui-datagrid-process-car-index').datagrid('reload');
						}else{
							$.messager.alert('操作失败',data.info,'error');
						}
					}
				});
			}
		},{
			text:'取消',
			iconCls:'icon-cancel',
			handler:function(){
				$('#easyui-dialog-process-car-index-assign').dialog('close');
			}
		}],
        onClose: function(){
            $(this).dialog('clear');
        }
    });
	
	
	//车辆交车列表（◆）
	$('#easyui-dialog-process-car-index-jiaoche').dialog({
    	title: '登记交车信息',   
        width: '915px',   
        height: '600px',   
        closed: true,   
        cache: true,   
        modal: true,
        resizable:true,
        maximizable: true,
        buttons: [{
			text:'完成提交',
			iconCls:'icon-ok',
			handler:function(){
				var button = this;
				$.messager.confirm('提示','你确认提交申请中所有的车辆？',function(r){
					if(r){

						var form = $('#easyui-form-process-car-jiaoche-from');
		                if(!form.form('validate')) return false;
		                $(button).linkbutton('disable'); 
						var data = form.serialize();
						$.ajax({
							type: 'post',
							url: "<?php echo yii::$app->urlManager->createUrl(['process/car/jiaoche']); ?>",
							data: data,
							dataType: 'json',
							success: function(data){
								$(button).linkbutton('enable'); 
								if(data.status){
									$.messager.alert('提交成功',data.info,'info');
									$('#easyui-dialog-process-car-index-jiaoche').dialog('close');
									$('#easyui-datagrid-process-car-index').datagrid('reload');
								}else{
									$.messager.alert('提交失败',data.info,'error');
								}
						}
					});

					}
				});
					
			
			}
		},{
			text:'稍后继续',
			iconCls:'icon-cancel',
			handler:function(){
				$('#easyui-dialog-process-car-index-jiaoche').dialog('close');
			}
		}],
        onClose: function(){
            $(this).dialog('clear');
        }
    });

	//车辆归档（◆）
	$('#easyui-dialog-process-car-index-archive').dialog({
    	title: '车辆归档确认',   
        width: '800px',   
        height: '250px',   
        closed: true,   
        cache: true,   
        modal: true,
        resizable:true,
        maximizable: true,
        buttons: [{
			text:'提交',
			iconCls:'icon-ok',
			handler:function(){
					var form = $('#easyui-form-process-car-archive-form');
	                if(!form.form('validate')) return false;
	                var button = this;
	                $(button).linkbutton('disable'); 
					var data = form.serialize();
					$.ajax({
						type: 'post',
						url: "<?php echo yii::$app->urlManager->createUrl(['process/car/archive']); ?>",
						data: data,
						dataType: 'json',
						success: function(data){
							$(button).linkbutton('enable'); 
							if(data.status){
								$.messager.alert('归档成功',data.info,'info');
								$('#easyui-dialog-process-car-index-archive').dialog('close');
								$('#easyui-datagrid-process-car-index').datagrid('reload');
							}else{
								$.messager.alert('归档失败',data.info,'error');
							}
					}
				});

			}
		},{
			text:'取消',
			iconCls:'icon-cancel',
			handler:function(){
				$('#easyui-dialog-process-car-index-archive').dialog('close');
			}
		}],
        onClose: function(){
            $(this).dialog('clear');
        }
    });
    
	 //初始化流程追踪查看窗口
	$('#easyui-dialog-process-car-index-trace').window({
		title: '查看流程追踪',
        width: '60%',   
        height: '80%',   
        closed: true,   
        cache: true,   
        modal: true,
        collapsible: false,
        minimizable: false, 
        maximizable: true,
        onClose: function(){
            $(this).window('clear');
        }       
	});
	

	//初始化备车车辆查看窗口
	$('#easyui-dialog-process-car-index-record').window({
		title: '查看备车车辆',
        width: '80%',   
        height: '80%',   
        closed: true,   
        cache: true,   
        modal: true,
        collapsible: false,
        minimizable: false, 
        maximizable: true,
        onClose: function(){
            $(this).window('clear');
        }       
	});

	
	//初始化申请记录详情
	$('#easyui-dialog-process-car-index-info').window({
		title: '查看申请',
        width: '860px',   
        height: '615px',   
        closed: true,   
        cache: true,   
        modal: true,
        collapsible: false,
        minimizable: false, 
        maximizable: true,
        onClose: function(){
            $(this).window('clear');
        }       
	});


    
  	//执行
    ProcessCarIndex.init();

  	

    //获取选择的记录
    ProcessCarIndex.getSelected = function(){
        var datagrid = $('#easyui-datagrid-process-car-index');
        var selectRow = datagrid.datagrid('getSelected');
        if(!selectRow){
            $.messager.alert('错误','请选择要操作的记录','error');   
            return false;
        }
        return selectRow;
    }
   
  	//发起申请(增加)
    ProcessCarIndex.add = function(){
        $('#easyui-dialog-process-car-index-add').dialog('open');
        $('#easyui-dialog-process-car-index-add').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/car/add']); ?>");
    }

    /*****************************************流程流转通用js****************************************************/
    
     //编辑
    ProcessCarIndex.edit = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var id = selectRow.id;
        $('#easyui-dialog-process-car-index-edit').dialog('open');
        $('#easyui-dialog-process-car-index-edit').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/car/edit']); ?>&id="+id);
    }
	//重新申请
    ProcessCarIndex.again = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var id = selectRow.id;
        $('#easyui-dialog-process-car-index-add').dialog('open');
        $('#easyui-dialog-process-car-index-add').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/car/again']); ?>&id="+id);
    }
    
    //删除
	ProcessCarIndex.remove = function(){
		var selectRow = this.getSelected();
        if(!selectRow) return false;
        var id = selectRow.id;
		$.messager.confirm('确定删除','您确定要删除该申请？',function(r){
			if(r){
				$.ajax({
					type: 'post',
					url: '<?php echo yii::$app->urlManager->createUrl(['process/car/delete']); ?>&id='+id,
					data: {id: id},
					dataType: 'json',
					success: function(data){
						if(data.status){
							$.messager.alert('删除成功',data.info,'info');   
							$('#easyui-datagrid-process-car-index').datagrid('reload');
						}else{
							$.messager.alert('删除失败',data.info,'error');   
						}
					}
				});
			}
		});
	}
    
    
	//提交申请
    ProcessCarIndex.confirm = function(){
    	var selectRow = this.getSelected();
        if(!selectRow) return false;
        var id = selectRow.id;
		$.messager.confirm('提交申请','您确定要提交该申请？',function(r){
			if(r){
				$.ajax({
					type: 'post',
					url: '<?php echo yii::$app->urlManager->createUrl(['process/car/confirm']); ?>&id='+id,
					data: {id: id},
					dataType: 'json',
					success: function(data){
						if(data.status){
							$.messager.alert('提交成功',data.info,'info');   
							$('#easyui-datagrid-process-car-index').datagrid('reload');
						}else{
							$.messager.alert('提交失败',data.info,'error');   
						}
					}
				});
			}
		});
	}
	
  	//取消申请
    ProcessCarIndex.cancel = function(template_id){
    	var selectRow = this.getSelected();
        if(!selectRow) return false;
        var id = selectRow.id;
		$.messager.confirm('取消申请','您确定要取消该申请？',function(r){
			if(r){
				$.ajax({
					type: 'post',
					url: '<?php echo yii::$app->urlManager->createUrl(['process/car/cancel']); ?>&id='+id+'&template_id='+template_id,
					data: {id: id},
					dataType: 'json',
					success: function(data){
						if(data.status){
							$.messager.alert('取消成功',data.info,'info');   
							$('#easyui-datagrid-process-car-index').datagrid('reload');
						}else{
							$.messager.alert('取消失败',data.info,'error');   
						}
					}
				});
			}
		});
    }

    
    //审批通过 avg  流程步骤id
    ProcessCarIndex.pass = function(step_id,template_id){
        
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var id = selectRow.id;
        $('#easyui-dialog-process-car-index-pass').dialog('open');
        $('#easyui-dialog-process-car-index-pass').dialog('refresh','<?php echo yii::$app->urlManager->createUrl(['process/car/pass']); ?>&id='+id+'&step_id='+step_id+'&template_id='+template_id);
    }

    
	//审批驳回   流程步骤id
    ProcessCarIndex.no_pass = function(step_id,template_id){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var id = selectRow.id;
        $('#easyui-dialog-process-car-index-no-pass').dialog('open');
        $('#easyui-dialog-process-car-index-no-pass').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/car/no-pass']); ?>&id="+id+"&step_id="+step_id+"&template_id="+template_id);
    }

    /*****************************************流程流转通用js****************************************************/

	//登记提车信息
	ProcessCarIndex.tiche = function(step_id,template_id){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var id = selectRow.id;
        $('#easyui-dialog-process-car-index-tiche').dialog('open');
        $('#easyui-dialog-process-car-index-tiche').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/car/tiche']); ?>&id="+id+"&step_id="+step_id+"&template_id="+template_id);
    }
	//登记租金信息
	ProcessCarIndex.rent = function(step_id,template_id){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var id = selectRow.id;
        $('#easyui-dialog-process-car-index-rent').dialog('open');
        $('#easyui-dialog-process-car-index-rent').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/car/rent']); ?>&id="+id+"&step_id="+step_id+"&template_id="+template_id);
    }  
	//收款方式确认  
	ProcessCarIndex.proceeds = function(step_id,template_id){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var id = selectRow.id;
        $('#easyui-dialog-process-car-index-proceeds').dialog('open');
        $('#easyui-dialog-process-car-index-proceeds').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/car/proceeds']); ?>&id="+id+"&step_id="+step_id+"&template_id="+template_id);
    }  
	
	
	//流程追踪
	ProcessCarIndex.trace = function(template_id){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var id = selectRow.id;
        $('#easyui-dialog-process-car-index-trace').dialog('open');
        $('#easyui-dialog-process-car-index-trace').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/car/trace']); ?>&id="+id+"&template_id="+template_id);
    } 


	//查看备车车辆
	ProcessCarIndex.record = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var id = selectRow.id;
        $('#easyui-dialog-process-car-index-record').dialog('open');
        $('#easyui-dialog-process-car-index-record').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/car/record']); ?>&id="+id);
    }
	//查看详情
	ProcessCarIndex.info = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var id = selectRow.id;
        $('#easyui-dialog-process-car-index-info').dialog('open');
        $('#easyui-dialog-process-car-index-info').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/car/info']); ?>&id="+id);
    }
    



	//提车点
	ProcessCarIndex.tiche_site = function(step_id,template_id){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var id = selectRow.id;
        $('#easyui-dialog-process-car-index-tiche-site').dialog('open');
        $('#easyui-dialog-process-car-index-tiche-site').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/car/tiche-site']); ?>&id="+id+"&step_id="+step_id+"&template_id="+template_id);
    }

	//指派提车点负责人
	ProcessCarIndex.assign = function(step_id,template_id){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var id = selectRow.id;
        $('#easyui-dialog-process-car-index-assign').dialog('open');
        $('#easyui-dialog-process-car-index-assign').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/car/assign']); ?>&id="+id+"&step_id="+step_id+"&template_id="+template_id);
    }

	
	//车辆交付
	ProcessCarIndex.jiaoche = function(step_id,template_id){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var id = selectRow.id;
        $('#easyui-dialog-process-car-index-jiaoche').dialog('open');
        $('#easyui-dialog-process-car-index-jiaoche').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/car/jiaoche']); ?>&id="+id+"&step_id="+step_id+"&template_id="+template_id);
    }

	//车辆归档
	ProcessCarIndex.archive = function(step_id,template_id){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var id = selectRow.id;
        $('#easyui-dialog-process-car-index-archive').dialog('open');
        $('#easyui-dialog-process-car-index-archive').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/car/archive']); ?>&id="+id+"&step_id="+step_id+"&template_id="+template_id);
    }
	 //查询表单构建
    var searchForm = $('#search-form-process-car-index');
    searchForm.submit(function(){
        var data = {};
        var searchCondition = $(this).serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        $('#easyui-datagrid-process-car-index').datagrid('load',data);
        return false;
    });
    searchForm.find('input[name=name]').textbox({
        onChange: function(){
            searchForm.submit();
        }
    });
    //tiche_manage_user
     searchForm.find('input[name=tiche_manage_user]').textbox({
        onChange: function(){
            searchForm.submit();
        }
    });
    searchForm.find('input[name=shenqing_time_start]').datebox({
        editable: false,
        onChange: function(){
            searchForm.submit();
        }
    });
    searchForm.find('input[name=shenqing_time_end]').datebox({
        editable: false,
        onChange: function(){
            searchForm.submit();
        }
    });
    searchForm.find('input[name=my_approvel]').combobox({
    	valueField:'value',
        textField:'text',
        editable: false,
        panelHeight:'auto',
        value:1, 
        data: [
               {"value": 1,"text": '我的待办'},
               {"value": 0,"text": '全部'}
               ],
        onSelect: function(){
            searchForm.submit();
        }
    });
    searchForm.find('input[name=current_status]').combobox({
    	valueField:'value',
        textField:'text',
        editable: false,
        panelHeight:'auto',
        value:'不限', 
        data: [
        	   {"value": "不限","text": '不限'},
               {"value": "申请未提交","text": '申请未提交'},
               {"value": "等待销售部门负责人审批","text": '等待销售部门负责人审批'},
               {"value": "等待车管部门负责人审批","text": '等待车管部门负责人审批'},
               {"value": "等待售后部门负责人确认提车地点","text": '等待售后部门负责人确认提车地点'},
               {"value": "等待提车站点负责人登记整备车辆","text": '等待提车站点负责人登记整备车辆'},
               {"value": "等待提车申请人确认收款方式","text": '等待提车申请人确认收款方式'},
               {"value": "等待财务部门负责人确认收款方式","text": '等待财务部门负责人确认收款方式'},
               {"value": "等待提车站点负责人登记交车信息","text": '等待提车站点负责人登记交车信息'},
               {"value": "等待提车申请人填写租金信息","text": '等待提车申请人填写租金信息'},
               {"value": "等待财务部门负责人确认租金信息","text": '等待财务部门负责人确认租金信息'},
               {"value": "等待提车申请人归档","text": '等待提车申请人归档'},
               {"value": "流程结束","text": '流程结束'},
               {"value": "申请已撤销","text": '申请已撤销'}
              
               ],
        onSelect: function(){
            searchForm.submit();
        }
    });
    
    //查询表单构建结束
    //重置查询表单
    ProcessCarIndex.resetForm = function(){
        var easyuiForm = $('#search-form-process-car-index');
        easyuiForm.form('reset');
    }
    
    //按条件导出车辆列表
    ProcessCarIndex.export = function(){
        var url = "<?php echo yii::$app->urlManager->createUrl(['process/car/export']);?>";
        var form = $('#search-form-process-car-index');
        var data = {};
        var searchCondition = form.serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        for(var i in data){
            url += '&'+i+'='+data[i];
        }
        window.open(url);
    }

     ProcessCarIndex.init();
</script>