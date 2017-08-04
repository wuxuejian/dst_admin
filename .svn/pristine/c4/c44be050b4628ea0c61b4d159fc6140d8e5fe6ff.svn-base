<table id="easyui-treegrid-car-brand-index"></table> 
<div id="easyui-treegrid-car-brand-index-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-car-brand-index">
                <ul class="search-main">
                    <li>
                        <div class="item-name">品牌名称</div>
                        <div class="item-input">
                            <input name="name" style="width:100%;" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">品牌编码</div>
                        <div class="item-input">
                            <input name="code" style="width:100%;" />
                        </div>
                    </li>
                    <li class="search-button">
                        <button type="submit" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button type="submit" onclick="CarBrandIndex.resetForm()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
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
<div id="easyui-dialog-car-brand-index-add"></div>
<div id="easyui-dialog-car-brand-index-edit"></div>
<!-- 窗口 -->
<script>
	var CarBrandIndex = new Object();
	CarBrandIndex.init = function(){
		$('#easyui-treegrid-car-brand-index').treegrid({  
			method: 'get', 
		    url:"<?php echo yii::$app->urlManager->createUrl(['car/brand/get-list']); ?>",  
            idField: 'id',
            treeField: 'name', 
			fit: true,
			border: false,
			toolbar: "#easyui-treegrid-car-brand-index-toolbar",
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
				{field: 'name',title: '品牌名称',width: 150,sortable: true,halign: 'center'}
			]],
		    columns: [[
                {field: 'code',title: '品牌编码',width: 150,sortable: true,halign: 'center'},
                {field: 'note',title: '备注',width: 400,halign: 'center'}
            ]],
            onDblClickRow: function(rowIndex,rowData){
                CarBrandIndex.edit(rowData.id);
            }
		});

		//构建查询表单
        var searchForm = $('#search-form-car-brand-index');
        /**查询表单提交事件**/
        searchForm.submit(function(){
            var data = {};
            var searchCondition = $(this).serializeArray();
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = searchCondition[i]['value'];
            }
            $('#easyui-treegrid-car-brand-index').treegrid('load',data);
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
		$('#easyui-dialog-car-brand-index-add').dialog({
        	title: '添加车辆品牌',   
            width: 680,   
            height: 250,   
            closed: true,   
            cache: true,   
            modal: true,
            buttons: [{
				text:'确定',
				iconCls:'icon-ok',
				handler:function(){
                    var form = $('#easyui-form-car-brand-add');
                    if(!form.form('validate')) return false;
					$.ajax({
						type: 'post',
						url: "<?php echo yii::$app->urlManager->createUrl(['car/brand/add']); ?>",
						data: form.serialize(),
						dataType: 'json',
						success: function(data){
							if(data.status){
								$.messager.alert('添加成功',data.info,'info');
								$('#easyui-dialog-car-brand-index-add').dialog('close');
								$('#easyui-treegrid-car-brand-index').treegrid('reload');
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
					$('#easyui-dialog-car-brand-index-add').dialog('close');
				}
			}],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
        //初始化修改窗口
		$('#easyui-dialog-car-brand-index-edit').dialog({
        	title: '修改车辆品牌',   
            width: 680,   
            height: 250,   
            closed: true,   
            cache: true,   
            modal: true,
            buttons: [{
				text:'确定',
				iconCls:'icon-ok',
				handler:function(){
                    var form = $('#easyui-form-car-brand-edit');
                    if(!form.form('validate')) return false;
					$.ajax({
						type: 'post',
						url: "<?php echo yii::$app->urlManager->createUrl(['car/brand/edit']); ?>",
						data: form.serialize(),
						dataType: 'json',
						success: function(data){
							if(data.status){
								$.messager.alert('修改成功',data.info,'info');
								$('#easyui-dialog-car-brand-index-edit').dialog('close');
								$('#easyui-treegrid-car-brand-index').treegrid('reload');
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
					$('#easyui-dialog-car-brand-index-edit').dialog('close');
				}
            }],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
	}
	CarBrandIndex.init();

	//获取选择的记录
    //参数all = true标示是否要返回所有被选择的记录
	CarBrandIndex.getSelected = function(all){
		var treegrid = $('#easyui-treegrid-car-brand-index');
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
	CarBrandIndex.add = function(){
		$('#easyui-dialog-car-brand-index-add')
            .dialog('open')
		    .dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/brand/add']); ?>");
	}
	//修改
	CarBrandIndex.edit = function(id){
		if(!id){
			var selectRow = this.getSelected();
            if(!selectRow){
                return false;
            }
            id = selectRow.id;
		}
		$('#easyui-dialog-car-brand-index-edit')
            .dialog('open')
		    .dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/brand/edit']); ?>&id="+id);
	}
	//删除
	CarBrandIndex.remove = function(){
		var selectRow = this.getSelected();
        if(!selectRow) return false;
        var id = selectRow.id;
		$.messager.confirm('确定删除','你确定要删除所选数据吗？',function(r){
			if(r){
				$.ajax({
					type: 'get',
					url: '<?php echo yii::$app->urlManager->createUrl(['car/brand/remove']); ?>',
					data: {id: id},
					dataType: 'json',
					success: function(data){
						if(data){
							$.messager.alert('删除成功',data.info,'info');   
							$('#easyui-treegrid-car-brand-index').treegrid('reload');
						}else{
							$.messager.alert('删除失败',data.info,'error');   
						}
					}
				});
			}
		});
	}
    //按条件导出车辆列表
    CarBrandIndex.exportWidthCondition = function(){
        var url = "<?php echo yii::$app->urlManager->createUrl(['car/brand/export-width-condition']);?>";
        var form = $('#search-form-car-brand-index');
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
    CarBrandIndex.resetForm = function(){
        var easyuiForm = $('#search-form-car-brand-index');
        easyuiForm.form('reset');
    }
</script>