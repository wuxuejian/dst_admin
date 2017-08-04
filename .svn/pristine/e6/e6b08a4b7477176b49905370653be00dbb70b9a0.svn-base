<table id="easyui-datagrid-station-service-index"></table> 
<div id="easyui-datagrid-station-service-index-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-station-service-index">
                <ul class="search-main">
                    <li>
                        <div class="item-name">修理厂名称</div>
                        <div class="item-input">
                            <input name="site_name" style="width:100%;" />
                        </div>
                    </li>
                     <li>
                        <div class="item-name">修理厂类别</div>
                        <div class="item-input">
                            <input name="type2" style="width:100%;" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">授权品牌</div>
                        <div class="item-input">
                            <input class="easyui-combotree" name="arr_brand_name"
                           data-options="
                                width:160,
                                url: '<?php echo yii::$app->urlManager->createUrl(['car/combotree/get-car-brands']); ?>',
                                editable: false,
                                panelHeight:'auto',
                                lines:false
                           "
                         />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">所属区域</div>
                        <div class="item-input" style="width:350px;">
                            <select class="easyui-combobox" style="width:80px;" id="province"   name="province_id"  editable=false   >
                   			<option value=""></option>
	                   			<?php foreach($provinces as $row): ?>
	                            <option value="<?php echo  $row['region_id']?>"><? echo $row['region_name']?></option>
	                        	<?php endforeach;?>
                   	  		</select>
                            省
                   	  		<select class="easyui-combobox" style="width:80px;" id="city"   name="city_id"  editable=false   >
                   	  		</select>
                             市
                   	  		<select class="easyui-combobox" style="width:80px;" id="county"   name="county_id"  editable=false   >
                   	  		</select>
                            县/区
                        </div>
                    </li>
                     <li>
                        <div class="item-name">合作方式</div>
                        <div class="item-input">
                            <input name="team_type" style="width:100%;" />
                        </div>
                    </li>
                    <li class="search-button">
                        <button class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button onclick="StationServiceIndex .resetForm();" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
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
<div id="easyui-dialog-station-service-index-add"></div>
<div id="easyui-dialog-station-service-index-edit"></div>
<div id="easyui-dialog-station-service-index-info"></div>
<div id="easyui-dialog-station-service-index-events"></div>
<!-- 窗口 -->
<script>
    var StationServiceIndex = new Object();
    StationServiceIndex .init = function(){
        //获取列表数据process-config
        $('#easyui-datagrid-station-service-index').datagrid({  
            method: 'POST', 
            url:"<?php echo yii::$app->urlManager->createUrl(['station/service/index']); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-station-service-index-toolbar",
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
                 {field: 'site_name',title: '修理厂名称',width: 120,align: 'center',sortable: true},
                 //{field: 'type',title: '类型',width: 120,align: 'center',sortable: true},
                 {field: 'type2',title: '修理厂分类',width: 120,align: 'center',sortable: true},
                 {field: 'arr_brand_name',title: '授权品牌',width: 120,align: 'center',sortable: true},
                 {field: 'team_type',title: '合作方式',width: 120,align: 'center',sortable: true},
                 //{field: 'level',title: '级别',width: 120,align: 'center',sortable: true},
                 //{field: 'brand_name',title: '所属厂商',width: 120,align: 'center', sortable: true,},
                 //{field: 'addr',title: '地址',width: 120,align: 'center',sortable: true},
                 {field: 'main_duty_name',title: '负责人',width: 120,align: 'center',sortable: true},
                 {field: 'main_duty_tel',title: '手机',width: 120,align: 'center',sortable: true},
                 {field: 'provide_services',title: '提供服务类型',width: 120,align: 'center', sortable: true,}, 
                 {field: 'remark',title: '备注',width: 120,align: 'center', sortable: true,},    
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
	//初始化添加窗口
	$('#easyui-dialog-station-service-index-add').dialog({
    	title: '新增售后服务站点',   
        width: '750px',   
        height: '500px',   
        closed: true,   
        cache: true,   
        modal: true,
        resizable:true,
        maximizable: true,
        buttons: [{
			text:'确定',
			iconCls:'icon-ok',
			handler:function(){
                var form = $('#easyui-form-station-service-add');
                if(!form.form('validate')) return false;
				var data = form.serialize();
				$.ajax({
					type: 'post',
					url: "<?php echo yii::$app->urlManager->createUrl(['station/service/add']); ?>",
					data: data,
					dataType: 'json',
					success: function(data){
						if(data.status){
							$.messager.alert('添加成功',data.info,'info');
							$('#easyui-dialog-station-service-index-add').dialog('close');
							$('#easyui-datagrid-station-service-index').datagrid('reload');
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
				$('#easyui-dialog-station-service-index-add').dialog('close');
			}
		}],
        onClose: function(){
            $(this).dialog('clear');
        }
    });

	//初始化编辑窗口
	$('#easyui-dialog-station-service-index-edit').dialog({
    	title: '修改售后服务站点',   
        width: '750px',   
        height: '500px',   
        closed: true,   
        cache: true,   
        modal: true,
        resizable:true,
        maximizable: true,
        buttons: [{
			text:'确定',
			iconCls:'icon-ok',
			handler:function(){
                var form = $('#easyui-form-station-service-edit');
                if(!form.form('validate')) return false;
				var data = form.serialize();
				$.ajax({
					type: 'post',
					url: "<?php echo yii::$app->urlManager->createUrl(['station/service/edit']); ?>",
					data: data,
					dataType: 'json',
					success: function(data){
						if(data.status){
							$.messager.alert('修改成功',data.info,'info');
							$('#easyui-dialog-station-service-index-edit').dialog('close');
							$('#easyui-datagrid-station-service-index').datagrid('reload');
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
				$('#easyui-dialog-station-service-index-edit').dialog('close');
			}
		}],
        onClose: function(){
            $(this).dialog('clear');
        }
    });

  	//查看详情
    $('#easyui-dialog-station-service-index-info').window({
        title: '服务站点详情',
    	width: 800,   
        height: 350,   
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
    StationServiceIndex .init();
    //查询表单构建
    var searchForm = $('#search-form-station-service-index');
    searchForm.submit(function(){
        var data = {};
        var searchCondition = $(this).serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        $('#easyui-datagrid-station-service-index').datagrid('load',data);
        return false;
    });
    searchForm.find('input[name=site_name]').textbox({
       /* onChange: function(){
            searchForm.submit();
        }*/
    });
     searchForm.find('input[name=arr_brand_name]').combotree({
       /* onChange: function(){
            searchForm.submit();
        }*/
        /*onSelect: function(){
            searchForm.submit();
        }*/
    });
    searchForm.find('input[name=team_type]').combobox({
       /* onChange: function(){
            searchForm.submit();
        }*/
        valueField:'value',
        textField:'text',
        editable: false,
        panelHeight:'auto',
        data: [{"value": '',"text": '不限'},
               {"value": 1,"text": '有合作协议'},
               {"value": 2,"text": '无合作协议'},
               ],
        onSelect: function(){
            searchForm.submit();
        }
    });
    searchForm.find('input[name=type2]').combobox({
        valueField:'value',
        textField:'text',
        editable: false,
        panelHeight:'auto',
        data: [{"value": '',"text": '不限'},
               {"value": 1,"text": '4S店'},
               {"value": 2,"text": '二类'},
               {"value": 3,"text": '三类'},
               {"value": 4,"text": '其他'}
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
    StationServiceIndex .getSelected = function(){
        var datagrid = $('#easyui-datagrid-station-service-index');
        var selectRow = datagrid.datagrid('getSelected');
        if(!selectRow){
            $.messager.alert('错误','请选择要操作的记录','error');   
            return false;
        }
        return selectRow;
    }
  	//增加
    StationServiceIndex .add = function(){
        $('#easyui-dialog-station-service-index-add').dialog('open');
        $('#easyui-dialog-station-service-index-add').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['station/service/add']); ?>");
    }
  	//编辑
    StationServiceIndex .edit = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var id = selectRow.id;
        $('#easyui-dialog-station-service-index-edit').dialog('open');
        $('#easyui-dialog-station-service-index-edit').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['station/service/edit']); ?>&id="+id);
    }
    //删除
	StationServiceIndex .del = function(){
		var selectRow = this.getSelected();
        if(!selectRow) return false;
        var id = selectRow.id;
		$.messager.confirm('确定删除','您确定要删除该服务站点？',function(r){
			if(r){
				$.ajax({
					type: 'post',
					url: '<?php echo yii::$app->urlManager->createUrl(['station/service/del']); ?>',
					data: {id: id},
					dataType: 'json',
					success: function(data){
						if(data.status){
							$.messager.alert('删除成功',data.info,'info');   
							$('#easyui-datagrid-station-service-index').datagrid('reload');
						}else{
							$.messager.alert('删除失败',data.info,'error');   
						}
					}
				});
			}
		});
	}
  	//详情
    StationServiceIndex .info = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var id = selectRow.id;
        $('#easyui-dialog-station-service-index-info').dialog('open');
        $('#easyui-dialog-station-service-index-info').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['station/service/info']); ?>&id="+id);
    }
  //导出
    StationServiceIndex .export_excel = function(){
        var form = $('#search-form-station-service-index');
        window.open("<?= yii::$app->urlManager->createUrl(['station/service/export']); ?>&"+form.serialize());
    }
    //重置查询表单
    StationServiceIndex .resetForm = function(){
        var easyuiForm = $('#search-form-station-service-index');
        easyuiForm.form('reset');
    }
    
</script>