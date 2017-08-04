<table id="operatingBasicIndex_datagrid"></table> 
<div id="operatingBasicIndex_datagridToolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="operatingBasicIndex_searchForm">
                <ul class="search-main">
                    <li>
                        <div class="item-name">运营公司名称</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="name" style="width:100%;"  />
                        </div>
                    </li>
                    <li class="search-button">
                        <button type="submit" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button type="submit" onclick="operatingBasicIndex.resetForm()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
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
<div id="operatingBasicIndex_addWin"></div>
<div id="operatingBasicIndex_editWin"></div>
<!-- 窗口 -->
<script>
	var operatingBasicIndex = new Object();
    // 请求的URL
    operatingBasicIndex.URL = {
        getList: "<?php echo yii::$app->urlManager->createUrl(['operating/basic/get-list']); ?>",
        add: "<?php echo yii::$app->urlManager->createUrl(['operating/basic/add']); ?>",
        edit: "<?php echo yii::$app->urlManager->createUrl(['operating/basic/edit']); ?>",
        remove: "<?php echo yii::$app->urlManager->createUrl(['operating/basic/remove']); ?>",
        exportGridData: "<?php echo yii::$app->urlManager->createUrl(['operating/basic/export-grid-data']); ?>"
    }
    operatingBasicIndex.init = function(){
		$('#operatingBasicIndex_datagrid').treegrid({
			method: 'get', 
		    url: operatingBasicIndex.URL.getList,
            idField: 'id',
            treeField: 'name', 
			fit: true,
			border: false,
			toolbar: "#operatingBasicIndex_datagridToolbar",
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
				{field: 'name',title: '运营公司名称',width: 230,sortable: true,halign: 'center'},
				{field: 'area',title: '所属大区',sortable: true,halign: 'center',
                    formatter: function(value){
                        if(value == 1){
                            return "华南大区";
                        }else if(value == 2){
                        	return "华北大区";
                        }else if(value == 3){
                        	return "华东大区";
                        }else if(value == 4){
                        	return "华中大区";
                        }else if(value == 5){
                        	return "西南大区";
                        }else {
                            return "";
                        }
                    }
                }
			]],
		    columns: [[
                {field: 'addr',title: '运营公司地址',width: 280,sortable: true,halign: 'center'},
                {field: 'note',title: '备注',width: 350,halign: 'center'}
            ]],
            onDblClickRow: function(rowIndex,rowData){
                operatingBasicIndex.edit(rowData.id);
            }
		});

		//构建查询表单
        var searchForm = $('#operatingBasicIndex_searchForm');
        /**查询表单提交事件**/
        searchForm.submit(function(){
            var data = {};
            var searchCondition = $(this).serializeArray();
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = $.trim(searchCondition[i]['value']);
            }
            $('#operatingBasicIndex_datagrid').treegrid('load',data);
            return false;
        });
        searchForm.find('input[name=name]').textbox({
            onChange: function(){
                searchForm.submit();
            }
        });
        //构建查询表单结束

		//初始化添加窗口
		$('#operatingBasicIndex_addWin').dialog({
        	title: '添加车辆运营公司',
            width: 680,   
            height: 300,
            closed: true,   
            cache: true,   
            modal: true,
            buttons: [{
				text:'确定',
				iconCls:'icon-ok',
				handler:function(){
                    var form = $('#operatingBasicIndex_addWin_form');
                    if(!form.form('validate')) return false;
					$.ajax({
						type: 'post',
						url: operatingBasicIndex.URL.add,
						data: form.serialize(),
						dataType: 'json',
						success: function(data){
							if(data.status){
								$.messager.alert('添加成功',data.info,'info');
								$('#operatingBasicIndex_addWin').dialog('close');
								$('#operatingBasicIndex_datagrid').treegrid('reload');
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
					$('#operatingBasicIndex_addWin').dialog('close');
				}
			}],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
        //初始化修改窗口
		$('#operatingBasicIndex_editWin').dialog({
        	title: '修改车辆运营公司',
            width: 680,   
            height: 300,
            closed: true,   
            cache: true,   
            modal: true,
            buttons: [{
				text:'确定',
				iconCls:'icon-ok',
				handler:function(){
                    var form = $('#operatingBasicIndex_editWin_form');
                    if(!form.form('validate')) return false;
					$.ajax({
						type: 'post',
						url: operatingBasicIndex.URL.edit,
						data: form.serialize(),
						dataType: 'json',
						success: function(data){
							if(data.status){
								$.messager.alert('修改成功',data.info,'info');
								$('#operatingBasicIndex_editWin').dialog('close');
								$('#operatingBasicIndex_datagrid').treegrid('reload');
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
					$('#operatingBasicIndex_editWin').dialog('close');
				}
            }],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
	}
	operatingBasicIndex.init();

	//获取选择的记录
    //参数all = true标示是否要返回所有被选择的记录
	operatingBasicIndex.getSelected = function(all){
		var treegrid = $('#operatingBasicIndex_datagrid');
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
	operatingBasicIndex.add = function(){
		$('#operatingBasicIndex_addWin')
            .dialog('open')
		    .dialog('refresh',operatingBasicIndex.URL.add);
	}
	//修改
	operatingBasicIndex.edit = function(id){
		if(!id){
			var selectRow = this.getSelected();
            if(!selectRow){
                return false;
            }
            id = selectRow.id;
		}
		$('#operatingBasicIndex_editWin')
            .dialog('open')
		    .dialog('refresh',operatingBasicIndex.URL.edit + "&id=" + id);
	}
	//删除
	operatingBasicIndex.remove = function(){
		var selectRow = this.getSelected();
        if(!selectRow) return false;
        var id = selectRow.id;
		$.messager.confirm('确定删除','你确定要删除所选数据吗？',function(r){
			if(r){
				$.ajax({
					type: 'get',
					url: operatingBasicIndex.URL.remove,
					data: {id: id},
					dataType: 'json',
					success: function(data){
						if(data){
							$.messager.alert('删除成功',data.info,'info');   
							$('#operatingBasicIndex_datagrid').treegrid('reload');
						}else{
							$.messager.alert('删除失败',data.info,'error');   
						}
					}
				});
			}
		});
	}
    //按条件导出
    operatingBasicIndex.exportGridData = function(){
        var url = operatingBasicIndex.URL.exportGridData;
        var form = $('#operatingBasicIndex_searchForm');
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
    operatingBasicIndex.resetForm = function(){
        var easyuiForm = $('#operatingBasicIndex_searchForm');
        easyuiForm.form('reset');
    }
</script>