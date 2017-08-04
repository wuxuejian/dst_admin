<table id="easyui-datagrid-process-extract-index"></table> 
<div id="easyui-datagrid-process-extract-index-toolbar">
      <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-process-extract-index">
                <ul class="search-main">
                    <li>
                        <div class="item-name">场站名称</div>
                        <div class="item-input">
                            <input name="name" style="width:100%;" />
                        </div>
                    </li>
                   <!--  <li>
                        <div class="item-name">所属省份</div>
                        <div class="item-input">
                            <input name="name1" style="width:100%;" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">所属城市</div>
                        <div class="item-input">
                            <input name="name1" style="width:100%;" />
                        </div>
                    </li> -->
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
                            <!-- <select class="easyui-combobox" style="width:80px;" id="county"   name="county_id"  editable=false   >
                            </select>
                            县/区 -->
                        </div>
                    </li>
                     <li>
                        <div class="item-name">车辆运营公司</div>
                        <div class="item-input">
                            <input style="width:200px;" name="operating_company_id" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">是否配备充电站</div>
                        <div class="item-input">
                            <input name="is_sta" style="width:100%;" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">场站负责人</div>
                        <div class="item-input">
                            <input name="use_name" style="width:100%;" />
                        </div>
                    </li>

                    <li class="search-button">
                        <button class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button onclick="ProcessExtractIndex.resetForm();" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
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
<div id="easyui-dialog-process-extract-index-add"></div>
<div id="easyui-dialog-process-extract-index-edit"></div>
<div id="easyui-dialog-process-extract-index-site"></div>
<!-- 窗口 -->
<script>
    var ProcessExtractIndex = new Object();
    ProcessExtractIndex.init = function(){
        //获取列表数据process-extract
        $('#easyui-datagrid-process-extract-index').datagrid({  
            method: 'POST', 
            url:"<?php echo yii::$app->urlManager->createUrl(['process/extract-car-site/index']); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-process-extract-index-toolbar",
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
                 /*{field: 'site_name',title: '提车地点',width: 120,align: 'center',sortable: true},
                 {field: 'name_and_tel',title: '负责人联系方式',width: 620,align: 'center',sortable: true},
                
                 {field: 'company_name',title: '运营公司',width: 500,align: 'center',sortable: true},*/
                 {field: 'name',title: '场站名称',width: 250,align: 'center',sortable: true},
                 {field: 'name_and_tel',title: '场站负责人',width: 150,align: 'center',sortable: true},
                 {field: 'name2_and_tel',title: '其他联系人',width: 150,align: 'center',sortable: true},
                 {field: 'company_name',title: '运营公司',width: 150,align: 'center',sortable: true},
                 {field: 'address',title: '详细地址',width: 150,align: 'center',sortable: true},
                 {field: 'sta_photo',title: '场站图片',width: 80,align: 'center',sortable: true,
                    formatter:function(value,row,index){
                        //console.log(value)


                       // return '<img src='+value+' style="width:40px;height:40px"; />';
                        return '<a href="'+value+'" target="_blank" ><img src="'+value+'" width="10px" height="10px"></a>';
                    }   
                
                },

                 {field: 'sta_rel',title: '关联电站',width: 150,align: 'center',sortable: true},
                 {field: 'sta_name',title: '电站名称',width: 150,align: 'center',sortable: true},
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
	$('#easyui-dialog-process-extract-index-add').dialog({
    	title: '添加场站',   
        width: '850px',   
        height: '425px',   
        closed: true,   
        cache: true,   
        modal: true,
        resizable:true,
        maximizable: true,
        buttons: [{
			text:'确定',
			iconCls:'icon-ok',
			handler:function(){
                var form = $('#easyui-form-process-extract-add');
                if(!form.form('validate')) return false;
				var data = form.serialize();
				$.ajax({
					type: 'post',
					url: "<?php echo yii::$app->urlManager->createUrl(['process/extract-car-site/add']); ?>",
					data: data,
					dataType: 'json',
					success: function(data){
						if(data.status){
							$.messager.alert('添加成功',data.info,'info');
							$('#easyui-dialog-process-extract-index-add').dialog('close');
							$('#easyui-datagrid-process-extract-index').datagrid('reload');
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
				$('#easyui-dialog-process-extract-index-add').dialog('close');
			}
		}],
        onClose: function(){
            $(this).dialog('clear');
        }
    });

	//初始化编辑窗口
	$('#easyui-dialog-process-extract-index-edit').dialog({
    	title: '编辑场站',   
        width: '850px',   
        height: '480px',   
        closed: true,   
        cache: true,   
        modal: true,
        resizable:true,
        maximizable: true,
        buttons: [{
			text:'确定',
			iconCls:'icon-ok',
			handler:function(){
                var form = $('#easyui-form-process-extract-edit');
                if(!form.form('validate')) return false;
				var data = form.serialize();
				$.ajax({
					type: 'post',
					url: "<?php echo yii::$app->urlManager->createUrl(['process/extract-car-site/edit']); ?>",
					data: data,
					dataType: 'json',
					success: function(data){
						if(data.status){
							$.messager.alert('编辑成功',data.info,'info');
							$('#easyui-dialog-process-extract-index-edit').dialog('close');
							$('#easyui-datagrid-process-extract-index').datagrid('reload');
						}else{
							$.messager.alert('编辑失败',data.info,'error');
						}
					}
				});
			}
		},{
			text:'取消',
			iconCls:'icon-cancel',
			handler:function(){
				$('#easyui-dialog-process-extract-index-edit').dialog('close');
			}
		}],
        onClose: function(){
            $(this).dialog('clear');
        }
    });

  	//初始流提车地点窗口
    $('#easyui-dialog-process-extract-index-site').window({
        title: '提车地点管理',
    	width: 600,   
        height: 500,   
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
    ProcessExtractIndex.init();
    //查询表单构建
    var searchForm = $('#search-form-process-extract-index');
    searchForm.submit(function(){
        var data = {};
        var searchCondition = $(this).serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        $('#easyui-datagrid-process-extract-index').datagrid('load',data);
        return false;
    });
    searchForm.find('input[name=name]').textbox({
        onChange: function(){
            searchForm.submit();
        }
    });

    searchForm.find('input[name=site_name]').textbox({
       /* onChange: function(){
            searchForm.submit();
        }*/
    });
    searchForm.find('input[name=use_name]').textbox({
       /* onChange: function(){
            searchForm.submit();
        }*/
    });
    searchForm.find('input[name=is_sta]').combobox({
       /* onChange: function(){
            searchForm.submit();
        }*/
        valueField:'value',
        textField:'text',
        editable: false,
        panelHeight:'auto',
        data: [{"value": 0,"text": '无'},
               {"value": 1,"text": '有'},
               
               ],
        onSelect: function(){
            searchForm.submit();
        }
    });

     searchForm.find('input[name=operating_company_id]').combobox({
            valueField:'value',
            textField:'text',
            data: <?= json_encode($searchFormOptions['operating_company_id']); ?>,
            editable: false,
            panelHeight:'auto',
            onSelect: function(){
                searchForm.submit();
            }
        });
    //查询表单构建结束

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


    //获取选择的记录
    ProcessExtractIndex.getSelected = function(){
        var datagrid = $('#easyui-datagrid-process-extract-index');
        var selectRow = datagrid.datagrid('getSelected');
        if(!selectRow){
            $.messager.alert('错误','请选择要操作的记录','error');   
            return false;
        }
        return selectRow;
    }
  	//增加
    ProcessExtractIndex.add = function(){
        $('#easyui-dialog-process-extract-index-add').dialog('open');
        $('#easyui-dialog-process-extract-index-add').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/extract-car-site/add']); ?>");
    }
  	//编辑
    ProcessExtractIndex.edit = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var id = selectRow.id;
        $('#easyui-dialog-process-extract-index-edit').dialog('open');
        $('#easyui-dialog-process-extract-index-edit').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/extract-car-site/edit']); ?>&id="+id);
    }
    //删除提车地点负责人
	ProcessExtractIndex.del = function(){
		var selectRow = this.getSelected();
        if(!selectRow) return false;
        var id = selectRow.id;
		$.messager.confirm('确定删除','您确定要删除该负责人？',function(r){
			if(r){
				$.ajax({
					type: 'post',
					url: '<?php echo yii::$app->urlManager->createUrl(['process/extract-car-site/del']); ?>&id='+id,
					data: {id: id},
					dataType: 'json',
					success: function(data){
						if(data.status){
							$.messager.alert('删除成功',data.info,'info');   
							$('#easyui-datagrid-process-extract-index').datagrid('reload');
						}else{
							$.messager.alert('删除失败',data.info,'error');   
						}
					}
				});
			}
		});
	}
  	//提车地点
    ProcessExtractIndex.site = function(){
        $('#easyui-dialog-process-extract-index-site').dialog('open');
        $('#easyui-dialog-process-extract-index-site').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['process/extract-car-site/site']); ?>");
    }
    //重置查询表单
    ProcessExtractIndex.resetForm = function(){
        var easyuiForm = $('#search-form-process-extract-index');
        easyuiForm.form('reset');
    }

    
</script>