<table id="easyui-datagrid-system-code-update-log-index"></table> 
<div id="easyui-datagrid-system-code-update-log-index-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-system-code-update-log-index">
                <ul class="search-main">
                	<li>
                        <div class="item-name">产品</div>
                        <div class="item-input">
                            <input style="width:200px;" name="product" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">升级类型</div>
                        <div class="item-input">
                            <input style="width:200px;" name="update_type" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">更新时间</div>
                        <div class="item-input">
                            <input class="easyui-datebox" type="text" name="start_update_date" style="width:93px;"
                                   data-options=""
                                />
                            -
                            <input class="easyui-datebox" type="text" name="end_update_date" style="width:93px;"
                                   data-options=""
                                />
                        </div>
                    </li>
                    <li class="search-button">
                        <button type="submit" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button type="submit" onclick="CodeUpdateLogIndex.resetForm()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
                    </li>
                </ul>
            </form>
        </div>
    </div>
    <?php if($buttons){ ?>
        <div class="easyui-panel" title="数据列表" style="padding:3px 2px;width:100%;" data-options="
        iconCls: 'icon-table-list',
        border: false
    ">
            <?php foreach($buttons as $val){ ?>
                <button onclick="<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon']; ?>'"><?= $val['text']; ?></button>
            <?php } ?>
        </div>
    <?php } ?>
</div>
<!-- 窗口 -->
<div id="easyui-dialog-system-code-update-log-index-add"></div>
<div id="easyui-dialog-system-code-update-log-index-edit"></div> 
<div id="easyui-dialog-system-code-update-log-index-detail"></div>
<script>
	var CodeUpdateLogIndex = new Object();
	CodeUpdateLogIndex.init = function(){
		$('#easyui-datagrid-system-code-update-log-index').datagrid({  
			method: 'get', 
		    url:"<?php echo yii::$app->urlManager->createUrl(['system/code-update-log/get-list']); ?>",   
			fit: true,
			border: false,
			toolbar: "#easyui-datagrid-system-code-update-log-index-toolbar",
			pagination: true,
			loadMsg: '数据加载中...',
			striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
			pageSize: 20,
            frozenColumns: [[
				{field: 'ck',checkbox: true}, 
				{field: 'id',title: 'id',hidden: true}
			]],
		    columns: [[
				{
				    field: 'product',title: '产品',width: 70,align: 'center',
				    sortable: true,
				    formatter: function(value){
				        if(value == 1){
				            return '地上铁APP';
				        }else if(value == 2){
				        	return '地上铁系统';
				        }
				    }
				},
				{field: 'version_number',title: '版本号',width: 70,align: 'center',sortable: true},
				{field: 'module',title: '功能模块',width: 80,align: 'center',sortable: true},
				{
				    field: 'update_type',title: '升级类型',width: 70,align: 'center',
				    sortable: true,
				    formatter: function(value){
					    var update_type_arr = {1:'优化',2:'修复',3:'新增',4:'删除'};
					    return update_type_arr[value];
				    }
				},
				{field: 'update_title',title: '升级内容简述',width: 180,align: 'center',sortable: true},
				{field: 'update_date',title: '更新日期',width: 70,align: 'center',sortable: true},
				{field: 'oper_user',title: '记录人',width: 140,align: 'center',sortable: true}
            ]],
            onDblClickRow: function(rowIndex,rowData){
                CodeUpdateLogIndex.edit(rowData.id);
            },
            onLoadSuccess: function (data) {
                //单元格内容悬浮提示，doCellTip()是在入口文件index.php中拓展的。
                $(this).datagrid('doCellTip', {
                    position: 'bottom',
                    maxWidth: '300px',
                    onlyShowInterrupt: true, //false时所有单元格都显示提示；true时配合specialShowFields自定义要提示的列
                    specialShowFields: [     //需要提示的列
                        //{field: 'sketch', showField: 'sketch'}
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
        var searchForm = $('#search-form-system-code-update-log-index');
        /**查询表单提交事件**/
        searchForm.submit(function(){
            var data = {};
            var searchCondition = $(this).serializeArray();
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = searchCondition[i]['value'];
            }
            $('#easyui-datagrid-system-code-update-log-index').datagrid('load',data);
            return false;
        });
        searchForm.find('input[name=product]').combobox({
            valueField:'value',
            textField:'text',
            editable: false,
            panelHeight:'auto',
            data: [{"value": '',"text": '不限'},{"value": 1,"text": '地上铁APP'},{"value": 2,"text": '地上铁系统'}],
            onSelect: function(){
                searchForm.submit();
            }
        });
        searchForm.find('input[name=update_type]').combobox({
            valueField:'value',
            textField:'text',
            editable: false,
            panelHeight:'auto',
            data: [{"value": '',"text": '不限'},{"value": 1,"text": '优化'},{"value": 2,"text": '修复'},{"value": 3,"text": '新增'},{"value": 4,"text": '删除'}],
            onSelect: function(){
                searchForm.submit();
            }
        });
        //构建查询表单结束
		//初始化添加窗口
		$('#easyui-dialog-system-code-update-log-index-add').dialog({
        	title: '新增更新日志',   
            width: '700px',   
            height: '300px',   
            closed: true,   
            cache: true,   
            modal: true,
            resizable:true,
            maximizable: true,
            buttons: [{
				text:'确定',
				iconCls:'icon-ok',
				handler:function(){
                    var form = $('#easyui-form-system-code-update-log-add');
                    if(!form.form('validate')){
                        $.messager.show({
                            title: '表单验证不通过',
                            msg: '请检查表单是否填写完整或填写错误！'
                        });
                        return false;
                    }
					var data = form.serialize();
					$.ajax({
						type: 'post',
						url: "<?php echo yii::$app->urlManager->createUrl(['system/code-update-log/add']); ?>",
						data: data,
						dataType: 'json',
						success: function(data){
							if(data.status){
								$.messager.alert('添加成功',data.info,'info');
								$('#easyui-dialog-system-code-update-log-index-add').dialog('close');
								$('#easyui-datagrid-system-code-update-log-index').datagrid('reload');
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
					$('#easyui-dialog-system-code-update-log-index-add').dialog('close');
				}
			}],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
        //初始化修改窗口
		$('#easyui-dialog-system-code-update-log-index-edit').dialog({
        	title: '修改更新日志',   
            width: '680px',   
            height: '300px',   
            closed: true,   
            cache: true,   
            modal: true,
            resizable:true,
            maximizable: true,
            buttons: [{
				text:'确定',
				iconCls:'icon-ok',
				handler:function(){
                    var form = $('#easyui-form-system-code-update-log-edit');
                    if(!form.form('validate')) return false;
                    var data = form.serialize();
					$.ajax({
						type: 'post',
						url: "<?php echo yii::$app->urlManager->createUrl(['system/code-update-log/edit']); ?>",
						data: data,
						dataType: 'json',
						success: function(data){
							if(data.status){
								$.messager.alert('修改成功',data.info,'info');
								$('#easyui-dialog-system-code-update-log-index-edit').dialog('close');
								$('#easyui-datagrid-system-code-update-log-index').datagrid('reload');
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
					$('#easyui-dialog-system-code-update-log-index-edit').dialog('close');
				}
            }],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
        //初始化查看详情窗口
        $('#easyui-dialog-system-code-update-log-index-detail').dialog({
        	title: '查看详情',   
            width: '550px',   
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
					$('#easyui-dialog-system-code-update-log-index-detail').dialog('close');
				}
			}],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
	}
	CodeUpdateLogIndex.init();

	
	//获取选择的记录
    //参数all = true标示是否要返回所有被选择的记录
	CodeUpdateLogIndex.getSelected = function(all){
		var datagrid = $('#easyui-datagrid-system-code-update-log-index');
        if(all){
            var selectRows = datagrid.datagrid('getSelections');
            if(selectRows.length <= 0){
                $.messager.alert('错误','请选择要操作的记录','error');   
                return false;
            }
            return selectRows;
        }else{
            var selectRow = datagrid.datagrid('getSelected');
            if(!selectRow){
                $.messager.alert('错误','请选择要操作的记录','error');   
                return false;
            }
            return selectRow;
        }
		
	}
	//添加
	CodeUpdateLogIndex.add = function(){
		$('#easyui-dialog-system-code-update-log-index-add').dialog('open');
		$('#easyui-dialog-system-code-update-log-index-add').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['system/code-update-log/add']); ?>");
	}
	//修改
	CodeUpdateLogIndex.edit = function(id){
		if(!id){
			var selectRow = this.getSelected();
            if(!selectRow){
                return false;
            }
            id = selectRow.id;
		}
		$('#easyui-dialog-system-code-update-log-index-edit').dialog('open');
		$('#easyui-dialog-system-code-update-log-index-edit').dialog('refresh','<?php echo yii::$app->urlManager->createUrl(['system/code-update-log/edit']); ?>&id='+id);
	}
	//删除
	CodeUpdateLogIndex.del = function(){
		var selectRow = this.getSelected();
        if(!selectRow) return false;
        var id = selectRow.id;
		$.messager.confirm('确定删除','您确定要删除该日志吗？',function(r){
			if(r){
				$.ajax({
					type: 'get',
					url: '<?php echo yii::$app->urlManager->createUrl(['system/code-update-log/del']); ?>',
					data: {id: id},
					dataType: 'json',
					success: function(data){
						if(data.status){
							$.messager.alert('删除成功',data.info,'info');
							$('#easyui-datagrid-system-code-update-log-index').datagrid('reload');
						}else{
							$.messager.alert('删除失败',data.info,'error');   
						}
					}
				});
			}
		});
	}
	//详情
	CodeUpdateLogIndex.detail = function(){
		var selectRow = this.getSelected();
        if(!selectRow) return false;
        var id = selectRow.id;
        $('#easyui-dialog-system-code-update-log-index-detail').dialog('open');
		$('#easyui-dialog-system-code-update-log-index-detail').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['system/code-update-log/detail']); ?>&id="+id);
	}
    //重置查询表单
    CodeUpdateLogIndex.resetForm = function(){
        var easyuiForm = $('#search-form-system-code-update-log-index');
        easyuiForm.form('reset');
    }
</script>