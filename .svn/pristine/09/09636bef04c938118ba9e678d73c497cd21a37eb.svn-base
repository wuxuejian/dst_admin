<table id="easyui-datagrid-station-fault-index"></table> 
<div id="easyui-datagrid-station-fault-index-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-station-fault-index">
                <ul class="search-main">
                    <li>
                        <div class="item-name">故障名称</div>
                        <div class="item-input">
                            <input class="easyui-textbox" name="category" style="width:100%;" />
                        </div>
                    </li>

                    <li>
                        <div class="item-name">故障分类</div>
                        <div class="item-input">
                            <input class="easyui-combotree" name="pid" style="width:100%;" 
                           data-options="
                                width:160,
                                url: '<?php echo yii::$app->urlManager->createUrl(['station/fault/get-categorys']); ?>&isShowRoot=1&mark=1',
                                editable: false,
                                panelHeight:'auto',
                                lines:false
                           "
                         />
                        </div>
                    </li>

                    <li class="search-button">
                        <button class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button onclick="StationFalutIndex .resetForm();" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
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
        <a href="javascript:<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon']; ?>'"><?= $val['text']; ?></a>
        <?php } ?>
    </div>
    <?php } ?>
</div>
<!-- 窗口 -->
<div id="easyui-dialog-station-fault-index-add"></div>
<div id="easyui-dialog-station-fault-index-edit"></div>
<div id="easyui-dialog-station-fault-index-category"></div>
<div id="easyui-dialog-station-fault-index-events"></div>
<div id="easyui-dialog-process-repair-maintain-maintain-indicator-light"></div>
<!-- 窗口 -->
<script>
    var StationFalutIndex = new Object();
    StationFalutIndex .init = function(){
        //获取列表数据process-config
        $('#easyui-datagrid-station-fault-index').datagrid({  
            method: 'POST', 
            url:"<?php echo yii::$app->urlManager->createUrl(['station/fault/index']); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-station-fault-index-toolbar",
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
                 {field: 'code',title: '编码', rowspan:2,width: 50,align: 'center', sortable: true,}, 
                 {field: 'total_code',title: '总故障编码', rowspan:2,width: 80,align: 'center', sortable: true,},
                 {field: 'dfm_code',title: '东风原始故障码', rowspan:2,width: 100,align: 'center', sortable: true,}, 
                 {field: 'time',title: '登记时间', rowspan:2,width: 120,align: 'center', sortable: true,},  
                 {field: 'operator',title: '登记人员',rowspan:2,width: 120,align: 'center', sortable: true,},  
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
	$('#easyui-dialog-station-fault-index-add').dialog({
    	title: '新增故障',   
        width: '750px',   
        height: '250px',   
        closed: true,   
        cache: true,   
        modal: true,
        resizable:true,
        maximizable: true,
        buttons: [{
			text:'确定',
			iconCls:'icon-ok',
			handler:function(){
                var form = $('#easyui-form-station-fault-add');
                if(!form.form('validate')) return false;
				var data = form.serialize();
				$.ajax({
					type: 'post',
					url: "<?php echo yii::$app->urlManager->createUrl(['station/fault/add']); ?>",
					data: data,
					dataType: 'json',
					success: function(data){
						if(data.status){
							$.messager.alert('添加成功',data.info,'info');
							$('#easyui-dialog-station-fault-index-add').dialog('close');
							$('#easyui-datagrid-station-fault-index').datagrid('reload');
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
				$('#easyui-dialog-station-fault-index-add').dialog('close');
			}
		}],
        onClose: function(){
            $(this).dialog('clear');
        }
    });

	//初始化编辑窗口
	$('#easyui-dialog-station-fault-index-edit').dialog({
    	title: '修改故障',   
        width: '750px',   
        height: '250px',   
        closed: true,   
        cache: true,   
        modal: true,
        resizable:true,
        maximizable: true,
        buttons: [{
			text:'确定',
			iconCls:'icon-ok',
			handler:function(){
                var form = $('#easyui-form-station-fault-edit');
                if(!form.form('validate')) return false;
				var data = form.serialize();
				$.ajax({
					type: 'post',
					url: "<?php echo yii::$app->urlManager->createUrl(['station/fault/edit']); ?>",
					data: data,
					dataType: 'json',
					success: function(data){
						if(data.status){
							$.messager.alert('修改成功',data.info,'info');
							$('#easyui-dialog-station-fault-index-edit').dialog('close');
							$('#easyui-datagrid-station-fault-index').datagrid('reload');
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
				$('#easyui-dialog-station-fault-index-edit').dialog('close');
			}
		}],
        onClose: function(){
            $(this).dialog('clear');
        }
    });

  	//查看故障分类
    $('#easyui-dialog-station-fault-index-category').window({
        title: '车辆故障分类',
    	width: 800,   
        height: 600,   
        modal: true,
        closed: true,
        collapsible: false,
        minimizable: false,
        maximizable: false,
        onClose: function(){
            $(this).window('clear');
        }                    
    });


    //初始故障指示灯页面
    $('#easyui-dialog-process-repair-maintain-maintain-indicator-light').window({
        title: '故障指示灯',
    	width: 850,   
        height: 550,   
        modal: true,
        closed: true,
        collapsible: false,
        minimizable: false,
        maximizable: false,
        onClose: function(){
            $(this).window('clear');
        }                    
    });

  	
    
  	//执行
    StationFalutIndex .init();
    //查询表单构建
    var searchForm = $('#search-form-station-fault-index');
    searchForm.submit(function(){
        var data = {};
        var searchCondition = $(this).serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        $('#easyui-datagrid-station-fault-index').datagrid('load',data);
        return false;
    });

    searchForm.find('input[name=type]').combobox({
        valueField:'value',
        textField:'text',
        editable: false,
        panelHeight:'auto',
        data: [{"value": '',"text": '不限'},
               {"value": 1,"text": '厂家特约服务站'},
               {"value": 2,"text": '我方合作服务站'},
               {"value": 3,"text": '厂家4S店/修理厂'},
               {"value": 4,"text": '其他类型'}
               ],
        onSelect: function(){
            searchForm.submit();
        }
    });

    $('#province').combobox({
		onChange: function (n,o) {
			var province_id = $('#province').combobox('getValue');
			$.ajax({
		           url:'<?php echo yii::$app->urlManager->createUrl(['charge/charge-station/get-region-list']); ?>',
		           type:'get',
		           data:{parent_id:province_id},
		           dataType:'json',
		           success:function(data){
						$('#city').combobox({
		                   valueField:'region_id',
		                   textField:'region_name',
		                   editable: false,
		                   panelHeight:'auto',
		                   data: data
		               });
						$('#county').combobox({
	    	                   valueField:'region_id',
	    	                   textField:'region_name',
	    	                   editable: false,
	    	                   panelHeight:'auto',
	    	                   data: []
	    	            });
						$('#city').combobox('setValues','');
						$('#county').combobox('setValues','');
		            }
		   	});
		}
	});
    $('#city').combobox({
		onChange: function (n,o) {
			var city_id = $('#city').combobox('getValue');
			$.ajax({
		           url:'<?php echo yii::$app->urlManager->createUrl(['charge/charge-station/get-region-list']); ?>',
		           type:'get',
		           data:{parent_id:city_id},
		           dataType:'json',
		           success:function(data){
						$('#county').combobox({
		                   valueField:'region_id',
		                   textField:'region_name',
		                   editable: false,
		                   panelHeight:'auto',
		                   data: data
		               });
						$('#county').combobox('setValues','');
		            }
		   	});
		}
	});

    
	

    
    //查询表单构建结束
    //获取选择的记录
    StationFalutIndex .getSelected = function(){
        var datagrid = $('#easyui-datagrid-station-fault-index');
        var selectRow = datagrid.datagrid('getSelected');
        if(!selectRow){
            $.messager.alert('错误','请选择要操作的记录','error');   
            return false;
        }
        return selectRow;
    }
  	//增加
    StationFalutIndex .add = function(){
        $('#easyui-dialog-station-fault-index-add').dialog('open');
        $('#easyui-dialog-station-fault-index-add').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['station/fault/add']); ?>");
    }
  	//编辑
    StationFalutIndex .edit = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var id = selectRow.id;
        $('#easyui-dialog-station-fault-index-edit').dialog('open');
        $('#easyui-dialog-station-fault-index-edit').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['station/fault/edit']); ?>&id="+id);
    }
    //删除
	StationFalutIndex .del = function(){
		var selectRow = this.getSelected();
        if(!selectRow) return false;
        var id = selectRow.id;
		$.messager.confirm('确定删除','您确定要删除该故障？',function(r){
			if(r){
				$.ajax({
					type: 'post',
					url: '<?php echo yii::$app->urlManager->createUrl(['station/fault/del']); ?>',
					data: {id: id},
					dataType: 'json',
					success: function(data){
						if(data.status){
							$.messager.alert('删除成功',data.info,'info');   
							$('#easyui-datagrid-station-fault-index').datagrid('reload');
						}else{
							$.messager.alert('删除失败',data.info,'error');   
						}
					}
				});
			}
		});
	}
	
  	//查看故障分类
    StationFalutIndex .category = function(){
        $('#easyui-dialog-station-fault-index-category').dialog('open');
        $('#easyui-dialog-station-fault-index-category').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['station/fault/category']); ?>");
    }

    //查看故障指示灯
    StationFalutIndex.indicator_light = function(){
        $('#easyui-dialog-process-repair-maintain-maintain-indicator-light').dialog('open');
        $('#easyui-dialog-process-repair-maintain-maintain-indicator-light').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/repair/indicator-light']); ?>");
    }
    
  //导出
    StationFalutIndex .export_excel = function(){
        var form = $('#search-form-station-fault-index');
        window.open("<?= yii::$app->urlManager->createUrl(['station/fault/export']); ?>&"+form.serialize());
    }
    //重置查询表单
    StationFalutIndex .resetForm = function(){
        var easyuiForm = $('#search-form-station-fault-index');
        easyuiForm.form('reset');
    }
    
</script>