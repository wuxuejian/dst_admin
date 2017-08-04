<table id="ownerBasicIndex_datagrid"></table> 
<div id="ownerBasicIndex_datagridToolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="ownerBasicIndex_searchForm">
                <ul class="search-main">
                    <li>
                        <div class="item-name">所有人名称</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="name" style="width:100%;"  />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">所有人编号</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="code" style="width:100%;" />
                        </div>
                    </li>
                    <li class="search-button">
                        <button type="submit" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button type="submit" onclick="ownerBasicIndex.resetForm()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
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
                <button onclick="javascript:<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon']; ?>'"><?= $val['text']; ?></button>
            <?php } ?>
        </div>
    <?php } ?>
</div>
<!-- 窗口 -->
<div id="ownerBasicIndex_addWin"></div>
<div id="ownerBasicIndex_editWin"></div>
<!-- 窗口 -->
<script>
	var ownerBasicIndex = new Object();
    // 请求的URL
    ownerBasicIndex.URL = {
        getList: "<?php echo yii::$app->urlManager->createUrl(['owner/basic/get-list']); ?>",
        add: "<?php echo yii::$app->urlManager->createUrl(['owner/basic/add']); ?>",
        edit: "<?php echo yii::$app->urlManager->createUrl(['owner/basic/edit']); ?>",
        remove: "<?php echo yii::$app->urlManager->createUrl(['owner/basic/remove']); ?>",
        exportGridData: "<?php echo yii::$app->urlManager->createUrl(['owner/basic/export-grid-data']); ?>"
    }
    ownerBasicIndex.init = function(){
		$('#ownerBasicIndex_datagrid').treegrid({
			method: 'get', 
		    url: ownerBasicIndex.URL.getList,
            idField: 'id',
            treeField: 'name', 
			fit: true,
			border: false,
			toolbar: "#ownerBasicIndex_datagridToolbar",
			pagination: true,
			loadMsg: '数据加载中...',
			striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
			pageSize: 20,
            frozenColumns: [[
				{field: 'ck',checkbox: true}, 
				{field: 'id',title: 'id',hidden: true},
				{field: 'name',title: '所有人名称',width: 200,sortable: true,halign: 'center'}
			]],
		    columns: [[
                {field: 'code',title: '所有人编码',width: 100,sortable: true,halign: 'center'},
                {field: 'addr',title: '所有人地址',width: 250,sortable: true,halign: 'center'},
                {field: 'note',title: '备注',width: 300,halign: 'center'}
            ]],
            onDblClickRow: function(rowIndex,rowData){
                ownerBasicIndex.edit(rowData.id);
            }
		});

		//构建查询表单
        var searchForm = $('#ownerBasicIndex_searchForm');
        /**查询表单提交事件**/
        searchForm.submit(function(){
            var data = {};
            var searchCondition = $(this).serializeArray();
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = $.trim(searchCondition[i]['value']);
            }
            $('#ownerBasicIndex_datagrid').treegrid('load',data);
            return false;
        });
        searchForm.find('input[name=name]').textbox({
            onChange: function(){
                searchForm.submit();
            }
        });
        searchForm.find('input[name=code]').textbox({
            onChange: function(){
                searchForm.submit();
            }
        });
        //构建查询表单结束

		//初始化添加窗口
		$('#ownerBasicIndex_addWin').dialog({
        	title: '添加机动车所有人',
            width: 680,   
            height: 300,
            closed: true,   
            cache: true,   
            modal: true,
            buttons: [{
				text:'确定',
				iconCls:'icon-ok',
				handler:function(){
                    var form = $('#ownerBasicIndex_addWin_form');
                    if(!form.form('validate')) return false;
					$.ajax({
						type: 'post',
						url: ownerBasicIndex.URL.add,
						data: form.serialize(),
						dataType: 'json',
						success: function(data){
							if(data.status){
								$.messager.alert('添加成功',data.info,'info');
								$('#ownerBasicIndex_addWin').dialog('close');
								$('#ownerBasicIndex_datagrid').treegrid('reload');
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
					$('#ownerBasicIndex_addWin').dialog('close');
				}
			}],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
        //初始化修改窗口
		$('#ownerBasicIndex_editWin').dialog({
        	title: '修改机动车所有人',
            width: 680,   
            height: 300,
            closed: true,   
            cache: true,   
            modal: true,
            buttons: [{
				text:'确定',
				iconCls:'icon-ok',
				handler:function(){
                    var form = $('#ownerBasicIndex_editWin_form');
                    if(!form.form('validate')) return false;
					$.ajax({
						type: 'post',
						url: ownerBasicIndex.URL.edit,
						data: form.serialize(),
						dataType: 'json',
						success: function(data){
							if(data.status){
								$.messager.alert('修改成功',data.info,'info');
								$('#ownerBasicIndex_editWin').dialog('close');
								$('#ownerBasicIndex_datagrid').treegrid('reload');
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
					$('#ownerBasicIndex_editWin').dialog('close');
				}
            }],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
	}
	ownerBasicIndex.init();

	//获取选择的记录
    //参数all = true标示是否要返回所有被选择的记录
	ownerBasicIndex.getSelected = function(all){
		var treegrid = $('#ownerBasicIndex_datagrid');
        if(all){
            var selectRows = treegrid.treegrid('getSelections');
            if(selectRows.length <= 0){
                $.messager.alert('错误','请选择要操作的记录','error');   
                return false;
            }
            return selectRows;
        }else{
            var selectRow = treegrid.treegrid('getSelected');
            if(!selectRow){
                $.messager.alert('错误','请选择要操作的记录','error');   
                return false;
            }
            return selectRow;
        }
		
	}
	//添加
	ownerBasicIndex.add = function(){
		$('#ownerBasicIndex_addWin')
            .dialog('open')
		    .dialog('refresh',ownerBasicIndex.URL.add);
	}
	//修改
	ownerBasicIndex.edit = function(id){
		if(!id){
			var selectRow = this.getSelected();
            if(!selectRow){
                return false;
            }
            id = selectRow.id;
		}
		$('#ownerBasicIndex_editWin')
            .dialog('open')
		    .dialog('refresh',ownerBasicIndex.URL.edit + "&id=" + id);
	}
	//删除
	ownerBasicIndex.remove = function(){
		var selectRow = this.getSelected();
        if(!selectRow) return false;
        var id = selectRow.id;
		$.messager.confirm('确定删除','你确定要删除所选数据吗？',function(r){
			if(r){
				$.ajax({
					type: 'get',
					url: ownerBasicIndex.URL.remove,
					data: {id: id},
					dataType: 'json',
					success: function(data){
						if(data){
							$.messager.alert('删除成功',data.info,'info');   
							$('#ownerBasicIndex_datagrid').treegrid('reload');
						}else{
							$.messager.alert('删除失败',data.info,'error');   
						}
					}
				});
			}
		});
	}
    //按条件导出
    ownerBasicIndex.exportGridData = function(){
        var url = ownerBasicIndex.URL.exportGridData;
        var form = $('#ownerBasicIndex_searchForm');
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
    //重置查询表单
    ownerBasicIndex.resetForm = function(){
        var easyuiForm = $('#ownerBasicIndex_searchForm');
        easyuiForm.form('reset');
    }
</script>