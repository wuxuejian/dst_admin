<table id="easyui-datagrid-process-contract-approval-index"></table> 
<div id="easyui-datagrid-process-contract-approval-index-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-process-contract-approval-index">
                <ul class="search-main">
                	<li>
                        <div class="item-name">合同编号</div>
                        <div class="item-input">
                            <input style="width:200px;" name="contract_no" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">合同名称</div>
                        <div class="item-input">
                            <input style="width:200px;" name="contract_name" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">审批状态</div>
                        <div class="item-input">
                            <input style="width:200px;" name="contract_type" />
                        </div>
                    </li>
                    <li class="search-button">
                        <a onclick="javascript:ProcessContractApprovalIndex.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                        <a onclick="javascript:ProcessContractApprovalIndex.reset()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</a>
                    </li>
                </ul>
            </form>
        </div>
    </div>
    <?php if(!empty($buttons)){ ?>
        <div class="easyui-panel" title="数据列表" style="padding:3px 2px;width:100%;" data-options="
        iconCls: 'icon-table-list',
        border: false
    ">
        <?php foreach($buttons as $val){ ?>
            <a onclick="<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon']; ?>'"><?= $val['text']; ?></a>
        <?php } ?>
        
        <span  id="operation"   title="数据列表" style="padding:3px 2px;width:100%;" data-options="iconCls: 'icon-table-list',border: false"></span>
        </div>
    <?php } ?>
</div>
<!-- 窗口 -->
<div id="easyui-datagrid-process-contract-approval-index-add"></div>
<div id="easyui-datagrid-process-contract-approval-index-edit"></div>
<div id="easyui-datagrid-process-contract-approval-index-detail"></div>
<div id="easyui-dialog-process-contract-approval-index-no-pass"></div> <!-- 驳回窗口 -->
<div id="easyui-dialog-process-contract-approval-index-trace"></div> <!-- 流程追踪 -->
<!-- 窗口 -->
<script>
$(function(){
	$('#easyui-datagrid-process-contract-approval-index').datagrid({
		onClickRow: function(rowIndex) {
			 $('#easyui-datagrid-process-contract-approval-index').datagrid('selectRow',rowIndex);
			  var currentRow =$("#easyui-datagrid-process-contract-approval-index").datagrid("getSelected");
	
			  var id = currentRow.id;
			  var is_cancel = currentRow.is_cancel;
	          //var action = 'process/car/index';
	
			  $.ajax({
					type: 'post',
					url: "<?php echo yii::$app->urlManager->createUrl(['process/contract-approval/operation']); ?>",
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

    var ProcessContractApprovalIndex = new Object();
    ProcessContractApprovalIndex.init = function(){
        //获取列表数据
        $('#easyui-datagrid-process-contract-approval-index').datagrid({  
            method: 'get', 
            url:"<?php echo yii::$app->urlManager->createUrl(['process/contract-approval/get-list']); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-process-contract-approval-index-toolbar",
            pagination: true,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
            showFooter: true,
            frozenColumns: [[
                {field: 'ck',checkbox: true}, 
                {field: 'id',title: 'id',hidden: true},
                {field: 'contract_no',title: '合同编号',halign:'center',width: 120,sortable: true}
            ]],
            columns:[[
				{field: 'contract_name',title: '合同名称',width: 120,align:'center',sortable: true},
			    {field: 'contract_type',title: '合同类型',width: 80,align:'center',sortable: true,
                    formatter: function (value, row, index) {
                    	switch (value) {
	                        case "1":
	                            return "采购类";
	                        case "2":
	                            return '营销类';
	                        case "3":
		                        return "行政类";
	                        case "4":
		                        return "基建类";
	                        case "5":
		                        return "专项服务类";
	                        case "6":
		                        return "知识产权类";
	                        case "10":
		                        return "其它";
	                        default:
	                            return "-";
	                    }
                    }
                },
                {field: 'current_status',title: '审批状态',width: 120,align:'center',sortable: true},
                {field: 'customer_company_name',title: '对方公司名称',width: 120,align:'center',sortable: true},
                {field: 'oper_name',title: '经办人',width: 120,align:'center',sortable: true},
                {field: 'department_name',title: '所属部门',width: 120,align:'center',sortable: true},
                {field: 'approval_start_time',title: '审批报送时间',width: 100,align:'center',sortable: true},
                {field: 'approval_end_time',title: '要求完成审批时间',width: 100,align:'center',sortable: true},
                {field: 'approval_time',title: '倒计时',width: 60,align:'center',sortable: true}
                
            ]],
            //双击
            onDblClickRow: function(rowIndex,rowData){
                //ProcessContractApprovalIndex.edit(rowData.id);
            },
            onLoadSuccess: function (data) {
                //单元格内容悬浮提示，doCellTip()是在入口文件index.php中拓展的。
                $(this).datagrid('doCellTip', {
                    position: 'bottom',
                    maxWidth: '200px',
                    onlyShowInterrupt: true, //false时所有单元格都显示提示；true时配合specialShowFields自定义要提示的列
                    specialShowFields: [     //需要提示的列
                        //{field: 'company_name', showField: 'company_name'}
                    ],
                    tipStyler: {
                        backgroundColor: '#E4F0FC',
                        borderColor: '#87A9D0',
                        boxShadow: '1px 1px 3px #292929'
                    }
                });
            }
        });

      	//构建查询表单
        var searchForm = $('#search-form-process-contract-approval-index');
        /**查询表单提交事件**/
        searchForm.submit(function(){
            var data = {};
            var searchCondition = $(this).serializeArray();
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = searchCondition[i]['value'];
            }
            $('#easyui-datagrid-process-contract-approval-index').datagrid('load',data);
            return false;
        });
        searchForm.find('input[name=contract_type]').combobox({
            valueField:'value',
            textField:'text',
            editable: false,
            panelHeight:'auto',
            data: [{"value": '',"text": '不限'},{"value": 1,"text": '采购类'},{"value": 2,"text": '营销类'},{"value": 3,"text": '行政类'},{"value": 4,"text": '基建类'},{"value": 5,"text": '专项服务类'},{"value": 6,"text": '知识产权类'},{"value": 10,"text": '其它'}],
            onSelect: function(){
                searchForm.submit();
            }
        });
        
        //初始化新增合同窗口
        $('#easyui-datagrid-process-contract-approval-index-add').dialog({
            title: '&nbsp;新建合同',
            iconCls:'icon-add', 
            width: '700',   
            height: '450',   
            closed: true,   
            cache: true,   
            modal: true,
            maximizable: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    //回调添加页面submitForm方法
                    ProcessContractApprovalAdd.submitForm();
                }
            },{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui-datagrid-process-contract-approval-index-add').dialog('close');
                }
            }],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
        //初始化修改合同窗口
        $('#easyui-datagrid-process-contract-approval-index-edit').dialog({
            title: '&nbsp;修改合同', 
            iconCls:'icon-edit',
            width: '700',   
            height: '450',   
            closed: true,   
            cache: true,   
            modal: true,
            maximizable: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                	//回调添加页面submitForm方法
                    ProcessContractApprovalEdit.submitForm();
                }
            },{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui-datagrid-process-contract-approval-index-edit').dialog('close');
                }
            }],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
      //初始化查看窗口
        $('#easyui-datagrid-process-contract-approval-index-detail').dialog({
            title: '&nbsp;合同详情', 
            iconCls:'icon-search',
            width: '750px',   
            height: '500px',   
            closed: true,   
            cache: true,   
            modal: true,
            maximizable: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                	$('#easyui-datagrid-process-contract-approval-index-detail').dialog('close');
                }
            }],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
      //初始化流程追踪查看窗口
    	$('#easyui-dialog-process-contract-approval-index-trace').window({
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
    	//初始驳回填写原因管理窗口
    	$('#easyui-dialog-process-contract-approval-index-no-pass').dialog({
        	title: '驳回',   
            width: '450px',   
            height: '280px',   
            closed: true,   
            cache: true,   
            modal: true,
            resizable:true,
            maximizable: true,
            buttons: [{
    			text:'确定',
    			iconCls:'icon-ok',
    			handler:function(){
                    var form = $('#easyui-form-process-car-no-pass');
                    if(!form.form('validate')) return false;
    				var data = form.serialize();
    				$.ajax({
    					type: 'post',
    					url: "<?php echo yii::$app->urlManager->createUrl(['process/contract-approval/no-pass']); ?>",
    					data: data,
    					dataType: 'json',
    					success: function(data){
    						if(data.status){
    							$.messager.alert('操作成功',data.info,'info');
    							$('#easyui-dialog-process-contract-approval-index-no-pass').dialog('close');
    							$('#easyui-datagrid-process-contract-approval-index').datagrid('reload');
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
    				$('#easyui-dialog-process-contract-approval-index-no-pass').dialog('close');
    			}
    		}],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
    }
    //获取选择的记录
    ProcessContractApprovalIndex.getSelected = function(){
        var datagrid = $('#easyui-datagrid-process-contract-approval-index');
        var selectRow = datagrid.datagrid('getSelected');
        if(!selectRow){
            $.messager.alert('错误','请选择要操作的记录','error');   
            return false;
        }
        return selectRow;
    }
	//查看
    ProcessContractApprovalIndex.detail = function(id){
    	if(!id){
            var selectRow = this.getSelected();
            if(!selectRow)  return false;
            id = selectRow.id;
        }
    	$('#easyui-datagrid-process-contract-approval-index-detail').dialog('open');
        $('#easyui-datagrid-process-contract-approval-index-detail').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/contract-approval/detail']); ?>&id="+id);
    }
  //查看合同正文
    ProcessContractApprovalIndex.url = function(){
		var selectRow = this.getSelected();
		if(!selectRow)  return false;
    	window.open(selectRow.contract_url);
    }
    //新建合同
    ProcessContractApprovalIndex.add = function(){
        $('#easyui-datagrid-process-contract-approval-index-add').dialog('open');
        $('#easyui-datagrid-process-contract-approval-index-add').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/contract-approval/add']); ?>");
    }
    
    //查询
    ProcessContractApprovalIndex.search = function(){
        var form = $('#search-form-process-contract-approval-index');
        var data = {};
        var searchCondition = form.serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        $('#easyui-datagrid-process-contract-approval-index').datagrid('load',data);
    }
    //重置
    ProcessContractApprovalIndex.reset = function(){
        $('#search-form-process-contract-approval-index').form('reset');
    }
  //流程追踪
	ProcessContractApprovalIndex.trace = function(template_id){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var id = selectRow.id;
        $('#easyui-dialog-process-contract-approval-index-trace').dialog('open');
        $('#easyui-dialog-process-contract-approval-index-trace').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/car/trace']); ?>&id="+id+"&template_id="+template_id);
    } 
	/*****************************************流程流转通用js****************************************************/
    
    //编辑
    ProcessContractApprovalIndex.edit = function(id){
            var selectRow = this.getSelected();
            if(!selectRow)  return false;
            id = selectRow.id;
        
        $('#easyui-datagrid-process-contract-approval-index-edit').dialog('open');
        $('#easyui-datagrid-process-contract-approval-index-edit').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/contract-approval/edit']); ?>&id="+id);
    }
   
   //删除
	ProcessContractApprovalIndex.remove = function(){
		var selectRow = this.getSelected();
       if(!selectRow) return false;
       var id = selectRow.id;
		$.messager.confirm('确定删除','您确定要删除该合同？',function(r){
			if(r){
				$.ajax({
					type: 'post',
					url: '<?php echo yii::$app->urlManager->createUrl(['process/contract-approval/delete']); ?>&id='+id,
					data: {id: id},
					dataType: 'json',
					success: function(data){
						if(data.status){
							$.messager.alert('删除成功',data.info,'info');   
							$('#easyui-datagrid-process-contract-approval-index').datagrid('reload');
						}else{
							$.messager.alert('删除失败',data.info,'error');   
						}
					}
				});
			}
		});
	}
   
	//提交申请
   ProcessContractApprovalIndex.confirm = function(){
   	var selectRow = this.getSelected();
       if(!selectRow) return false;
       var id = selectRow.id;
		$.messager.confirm('确定','您确定要提交该申请？',function(r){
			if(r){
				$.ajax({
					type: 'post',
					url: '<?php echo yii::$app->urlManager->createUrl(['process/contract-approval/confirm']); ?>&id='+id,
					data: {id: id},
					dataType: 'json',
					success: function(data){
						if(data.status){
							$.messager.alert('提交成功',data.info,'info');   
							$('#easyui-datagrid-process-contract-approval-index').datagrid('reload');
						}else{
							$.messager.alert('提交失败',data.info,'error');   
						}
					}
				});
			}
		});
	}
	
 	//取消申请
   ProcessContractApprovalIndex.cancel = function(template_id){
   	var selectRow = this.getSelected();
       if(!selectRow) return false;
       var id = selectRow.id;
		$.messager.confirm('确定','您确定要取消该申请？',function(r){
			if(r){
				$.ajax({
					type: 'post',
					url: '<?php echo yii::$app->urlManager->createUrl(['process/contract-approval/cancel']); ?>&id='+id+'&template_id='+template_id,
					data: {id: id},
					dataType: 'json',
					success: function(data){
						if(data.status){
							$.messager.alert('取消成功',data.info,'info');   
							$('#easyui-datagrid-process-contract-approval-index').datagrid('reload');
						}else{
							$.messager.alert('取消失败',data.info,'error');   
						}
					}
				});
			}
		});
   }
   //审批通过 avg  流程步骤id
   ProcessContractApprovalIndex.pass = function(step_id,template_id){
       var selectRow = this.getSelected();
       if(!selectRow){
           return false;
       }
       var id = selectRow.id;
       $.messager.confirm('确定','您确定要通过该申请？',function(r){
			if(r){
				$.ajax({
					type: 'post',
					url: '<?php echo yii::$app->urlManager->createUrl(['process/car/pass']); ?>&id='+id+'&step_id='+step_id+'&template_id='+template_id,
					data: {id: id},
					dataType: 'json',
					success: function(data){
						if(data.status){
							$.messager.alert('操作成功',data.info,'info');   
							$('#easyui-datagrid-process-contract-approval-index').datagrid('reload');
						}else{
							$.messager.alert('操作失败',data.info,'error');   
						}
					}
				});
			}
		});
   }
	//审批驳回   流程步骤id
   ProcessContractApprovalIndex.no_pass = function(step_id,template_id){
       var selectRow = this.getSelected();
       if(!selectRow){
           return false;
       }
       var id = selectRow.id;
       $('#easyui-dialog-process-contract-approval-index-no-pass').dialog('open');
       $('#easyui-dialog-process-contract-approval-index-no-pass').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/car/no-pass']); ?>&id="+id+"&step_id="+step_id+"&template_id="+template_id);
   }

   /*****************************************流程流转通用js****************************************************/
    //执行
    ProcessContractApprovalIndex.init();
</script>