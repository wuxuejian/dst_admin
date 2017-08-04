<table id="easyui-datagrid-car-type-lease-index"></table> 
<div id="easyui-datagrid-car-type-lease-index-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-car-type-lease-index">
                <ul class="search-main">
                    <li>
                        <div class="item-name">车辆品牌</div>
                        <div class="item-input">
                            <input style="width:200px;" name="brand_id" id="brand_id"/>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">车型名称</div>
                        <div class="item-input">
                            <input style="width:200px;" name="car_type_id" id="car_type_id"/>
                        </div>
                    </li>
					  <li>
                        <div class="item-name">运营城市</div>
                        <div class="item-input">
                            <input style="width:200px;" name="city" />
                        </div>
                    </li>
					<li>
                        <div class="item-name">车辆运营公司</div>
                        <div class="item-input">
                            <input style="width:200px;" name="operating_company_id" />
                        </div>
                    </li>
					 <li>
                        <div class="item-name">菜鸟启用状态</div>
                        <div class="item-input">
                            <input style="width:200px;" name="cainiao_status" />
                        </div>
                    </li>
					 <li>
                        <div class="item-name">分时启用状态</div>
                        <div class="item-input">
                            <input style="width:200px;" name="time_status" />
                        </div>
                    </li>
					 <li>
                        <div class="item-name">长租启用状态</div>
                        <div class="item-input">
                            <input style="width:200px;" name="long_lease_status" />
                        </div>
                    </li>
                    <li class="search-button">
                        <button class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button onclick="CarTypeLease.resetForm();return false;" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
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
                <button onclick="<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon'] ;?>'"><?= $val['text'] ;?></button>
            <?php } ?>
        </div>
    <?php } ?>

</div>

<!-- 窗口 -->
<div id="easyui-datagrid-car-type-lease-index-add"></div>
<div id="easyui-datagrid-car-type-lease-index-scan"></div>
<div id="easyui-datagrid-car-type-lease-index-edit"></div>
<div id="easyui-datagrid-car-type-lease-index-remove"></div>

<script>
    var CarTypeLease = new Object();
    //配置项
    
    CarTypeLease.init = function(){
        //获取列表数据
        
		 $('#easyui-datagrid-car-type-lease-index').datagrid({  
            method: 'get', 
            url:"<?php echo yii::$app->urlManager->createUrl(['car/car-type-lease/get-list']); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-car-type-lease-index-toolbar",
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
            columns:[
				[
					{field: 'brand_id',title: '车辆品牌',width: 150,rowspan:2},
					{field: 'car_model',title: '车型名称',width: 100,rowspan:2},										
					{field: 'region_name',title: '运营城市',width: 120,align: 'left',rowspan:2},
					{field: 'operating_company_id',title: '车辆运营公司',width: 180,align: 'left',rowspan:2},
					
					{title: '分时租赁',colspan:3}, // 跨几列
					{title: '菜鸟租赁',colspan:4},
					{title: '长租',colspan:3}
				],
               [
				{
				    field: 'time_price',title: '时租金',width: 70,
				    sortable: true
				},   
                {
                    field: 'day_price',title: '日租金',width: 50,
                    sortable: true
                }, 
				{
                    field: 'is_enable_time',title: '是否启用',width: 80,
					formatter: function(value){						
						try{							
							if(value == 1){
								return "<span style='color:green'>是</span>";
							} else {
								return "<span style='color:red'>否</span>";
							}
						}catch(e){
							return '';
						}
					}
                },
				
				{
				    field: 'month_price',title: '月租金',width: 70,
				    sortable: true
				},   
                {
                    field: 'deposit',title: '租车押金',width: 70,
                    sortable: true
                },  
				{
                    field: 'wz_deposit',title: '违章押金',width: 70,
                    sortable: true
                }, 
				{
                    field: 'is_enable_cainiao',title: '是否启用',width: 80,
                   formatter: function(value){						
						try{							
							if(value == 1){
								return "<span style='color:green'>是</span>";
							} else {
								return "<span style='color:red'>否</span>";
							}
						}catch(e){
							return '';
						}
					}
                },
				
				{
                    field: 'month_price_long',title: '月租金',width: 50,
                    sortable: true
                },  
				{
                    field: 'year_price_long',title: '年租金',width: 50,
                    sortable: true
                }, 
				{
                    field: 'is_enable_long',title: '是否启用',width: 80,
                    formatter: function(value){						
						try{							
							if(value == 1){
								return "<span style='color:green'>是</span>";
							} else {
								return "<span style='color:red'>否</span>";
							}
						}catch(e){
							return '';
						}
					}
                }
				
               
            ]],
            onDblClickRow: function(rowIndex,rowData){
            	//CarInsuranceClaimLog.scan(rowData.id);
            },
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
       
        //初始化添加窗口
        $('#easyui-datagrid-car-type-lease-index-add').dialog({
            title: '车辆租赁信息添加',   
            width: '970px',   
            height: '600px',   
            closed: true,   
            cache: true,   
            modal: true,
            resizable:true,
            maximizable: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var form = $('#easyui-form-car-type-lease-add');
                    if(!form.form('validate')) return false;
                    var data = form.serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['car/car-type-lease/add']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('添加成功',data.info,'info');
                                $('#easyui-datagrid-car-type-lease-index-add').dialog('close');
                                $('#easyui-datagrid-car-type-lease-index').datagrid('reload');
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
                    $('#easyui-datagrid-car-type-lease-index-add').dialog('close');
                }
            }],
            onClose: function(){
                $(this).dialog('clear');
            }
        });

    //添加方法
    CarTypeLease.add = function(){
        $('#easyui-datagrid-car-type-lease-index-add').dialog('open');
        $('#easyui-datagrid-car-type-lease-index-add').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/car-type-lease/add']); ?>");
    }


    //初始化查询窗口
        $('#easyui-datagrid-car-type-lease-index-scan').window({
            title: '车型模板详情',
            width: '45%',   
            height: '83%',   
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

     CarTypeLease.getSelected = function(all){
        var datagrid = $('#easyui-datagrid-car-type-lease-index');
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

    //查看
    CarTypeLease.scan = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var id = selectRow.id;
        $('#easyui-datagrid-car-type-lease-index-scan').window('open');
        $('#easyui-datagrid-car-type-lease-index-scan').window('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/car-type-lease/scan']); ?>&id="+id);
    }


    //初始化修改窗口
        $('#easyui-datagrid-car-type-lease-index-edit').dialog({
            title: '修改车辆模板',   
            width: '880px',   
            height: '600px',   
            closed: true,   
            cache: true,   
            modal: true,
            resizable:true,
            maximizable: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var form = $('#easyui-form-car-type-lease-edit');
                    if(!form.form('validate')) return false;
                    var data = form.serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['car/car-type-lease/edit']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('修改成功',data.info,'info');
                                $('#easyui-datagrid-car-type-lease-index-edit').dialog('close');
                                $('#easyui-datagrid-car-type-lease-index').datagrid('reload');
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
                    $('#easyui-datagrid-car-type-lease-index-edit').dialog('close');
                }
            }],
            onClose: function(){
                $(this).dialog('clear');
            }
        });

    //修改
    CarTypeLease.edit = function(id){
        console.log(id);
        if(!id){
            var selectRow = this.getSelected();
            if(!selectRow){
                return false;
            }
            id = selectRow.id;
        }
        $('#easyui-datagrid-car-type-lease-index-edit').dialog('open');
        $('#easyui-datagrid-car-type-lease-index-edit').dialog('refresh','<?php echo yii::$app->urlManager->createUrl(['car/car-type-lease/edit']); ?>&id='+id);
    }


    //删除车辆
    CarTypeLease.remove = function(){
        var selectRow = this.getSelected();
        if(!selectRow) return false;
        var id = selectRow.id;
       // alert(id)
        $.messager.confirm('确定删除','您确定要删除该数据吗？',function(r){
            //alert(r)
            //console.log(r)
            if(r){
                $.ajax({
                    type: 'post',
                    url: '<?php echo yii::$app->urlManager->createUrl(['car/car-type-lease/remove']); ?>&id='+id,
                    data: {id: id},
                    dataType: 'json',
                    success: function(data){
                        if(data.status){
                            $.messager.alert('删除成功',data.info,'info');
                            $('#easyui-datagrid-car-type-lease-index').datagrid('reload');
                        }else{
                            $.messager.alert('删除失败',data.info,'error');   
                        }
                    }
                });
            }
        });
    }

 
    //构建查询表单
        var searchForm = $('#search-form-car-type-lease-index');
        /**查询表单提交事件**/
        searchForm.submit(function(){
            var data = {};
            var searchCondition = $(this).serializeArray();
            //console.log(searchCondition)
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = searchCondition[i]['value'];
            }
            $('#easyui-datagrid-car-type-lease-index').datagrid('load',data);
            return false;
        });
        searchForm.find('input[name=brand_id]').combotree({
            url: "<?php echo yii::$app->urlManager->createUrl(['car/combotree/get-car-brands']); ?>",
            editable: false,
            panelHeight:'auto',
            lines:false,
            onChange: function(o){
                searchForm.submit();
            }
        });
        searchForm.find('input[name=car_type_id]').combobox({
            valueField:'value',
            textField:'text',
            data: '',
            editable: false,
            panelHeight:'auto',
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
		
		searchForm.find('input[name=long_lease_status]').combobox({
        	valueField:'value',
            textField:'text',
            data: <?= json_encode($searchFormOptions['long_lease_status']); ?>,
            editable: false,
            panelHeight:'auto',
            onSelect: function(){
                searchForm.submit();
            }
        });
		searchForm.find('input[name=cainiao_status]').combobox({
        	valueField:'value',
            textField:'text',
            data: <?= json_encode($searchFormOptions['cainiao_status']); ?>,
            editable: false,
            panelHeight:'auto',
            onSelect: function(){
                searchForm.submit();
            }
        });
		searchForm.find('input[name=time_status]').combobox({
        	valueField:'value',
            textField:'text',
            data: <?= json_encode($searchFormOptions['time_status']); ?>,
            editable: false,
            panelHeight:'auto',
            onSelect: function(){
                searchForm.submit();
            }
        });
       
        searchForm.find('input[name=city]').combobox({
            valueField:'value',
            textField:'text',
            editable: true,
           // panelHeight:'auto',
            data: <?= json_encode($searchFormOptions['citys_list']); ?>,
            onSelect: function(){
                searchForm.submit();
            }
        });
		
		$('#brand_id').combobox({
			onChange: function (n,o) {
				$('#car_type_id').combobox('clear');
				var brand_id = $('#brand_id').combobox('getValue');
				//console.log(n)
				//console.log(o)
			   // console.log(car_model_name)
				$.ajax({
					   url:"<?php echo yii::$app->urlManager->createUrl(['car/car-type-lease/check3']); ?>",
					   type:'post',
					   data:{brand_id:brand_id},
					   dataType:'json',
					   success:function(data){
						//console.log(data)
						   /* $('#type_id').combobox({
							   valueField:'',
							   textField:'',
							   editable: false,
							   panelHeight:'auto',
							   data: data
						   });*/
							//$('#type_id').combobox('setValues','');
							var current_type = [];
							
							$.each(data,function(i, value){
								var a =[];
								   //console.log(value);
									//var a =[];
									//console.log(a);
									//current_type = value.maintain_type
									a['value'] = value.value;
									a['text'] = value.text;
								   // a['text'] = value['text'];
								   //console.log(a);
									current_type.push(a);
								   //console.log(current_type);
									
							});
							
							$("#car_type_id").combobox("setValue",'');
							$("#car_type_id").combobox("loadData",current_type);
						}
				});
			}
        }); 
		
		
    }
   
   //重置查询表单
   CarTypeLease.resetForm = function(){
        var easyuiForm = $('#search-form-car-type-lease-index');
        easyuiForm.form('reset');
        easyuiForm.submit();
    }
    CarTypeLease.init();
	
	
	
	
	
	
</script>