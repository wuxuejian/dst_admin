<table id="easyui-datagrid-car-baseinfo-index"></table> 
<div id="easyui-datagrid-car-baseinfo-index-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-car-baseinfo-index">
                <ul class="search-main">
                    <li>
                        <div class="item-name">车牌号</div>
                        <div class="item-input">
                            <input name="plate_number" style="width:200px;" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">车架号（vin）</div>
                        <div class="item-input">
                            <input name="vehicle_dentification_number" style="width:200px;" />
                        </div>
                    </li>

                    <li>
                        <div class="item-name">车辆类型</div>
                        <div class="item-input">
                            <input style="width:200px;" name="car_type" />
                        </div>
                    </li>

                    <li>
                        <div class="item-name">一级状态</div>
                        <div class="item-input">
                            <input style="width:200px;" name="car_status" />
                        </div>
                    </li>

                    <li>
                        <div class="item-name">二级状态</div>
                        <div class="item-input">
                            <input style="width:200px;" name="car_status2" />
                        </div>
                    </li>
                    
                    <li>
                        <div class="item-name">车辆品牌</div>
                        <div class="item-input">
                            <input style="width:200px;" name="brand_id" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">行驶证</div>
                        <div class="item-input">
                            <input style="width:200px;" name="transact_dl" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">交强险</div>
                        <div class="item-input">
                            <input style="width:200px;" name="transact_ic" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">道路运输证</div>
                        <div class="item-input">
                            <input style="width:200px;" name="transact_rtc" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">二级维护卡</div>
                        <div class="item-input">
                            <input style="width:200px;" name="transact_sm" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">商业险</div>
                        <div class="item-input">
                            <input style="width:200px;" name="transact_ib" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">车辆运营公司</div>
                        <div class="item-input">
                            <input style="width:200px;" name="operating_company_id" />
                        </div>
                    </li>
					<li>
                        <div class="item-name">机动车所有人</div>
                        <div class="item-input">
                            <input style="width:200px;" name="owner_id" />
                        </div>
                    </li>
					<li>
                        <div class="item-name">车辆购置方式</div>
                        <div class="item-input">
                            <input style="width:200px;" name="gain_way" />
                        </div>
                    </li>
					<li>
                        <div class="item-name">车辆购买年份</div>
                        <div class="item-input">
                            <input style="width:200px;" name="gain_year" />
                        </div>
                    </li>
                    <!-- <li>
                        <div class="item-name">车辆类型</div>
                        <div class="item-input">
                            <input style="width:200px;" name="car_type" />
                        </div>
                    </li> -->
                    <li class="search-button">
                        <button type="submit" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button type="submit" onclick="CarBaseinfoIndex.resetForm()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
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
<div id="easyui-dialog-car-baseinfo-index-add"></div>
<div id="easyui-window-car-baseinfo-index-scan"></div>
<div id="easyui-dialog-car-baseinfo-index-edit"></div>
<div id="easyui-window-car-baseinfo-index-attachment"></div>
<div id="easyui-window-car-baseinfo-index-fault"></div>
<div id="easyui-dialog-car-baseinfo-index-driving-license"></div>
<div id="easyui-dialog-car-baseinfo-index-road-transport-certificate"></div>
<div id="easyui-window-car-baseinfo-second-maintenance-record"></div>
<div id="easyui-window-car-baseinfo-traffic-compulsory-insurance"></div>
<div id="easyui-window-car-baseinfo-business-insurance"></div>
<div id="easyui-dialog-car-baseinfo-index-import"></div>
<div id="easyui-dialog-car-baseinfo-index-driving-license-import"></div>

<!-- 窗口 -->
<script>
	var CarBaseinfoIndex = new Object();
	CarBaseinfoIndex.init = function(){
		$('#easyui-datagrid-car-baseinfo-index').datagrid({  
			method: 'get', 
		    url:"<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/get-list']); ?>",   
			fit: true,
			border: false,
			toolbar: "#easyui-datagrid-car-baseinfo-index-toolbar",
			pagination: true,
			loadMsg: '数据加载中...',
			striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: false,
			pageSize: 20,
            frozenColumns: [[
				{field: 'ck',checkbox: true}, 
				{field: 'id',title: 'id',hidden: true},   
				{field: 'plate_number',title: '车牌号',width: 70,sortable: true,align: 'center'}
			]],
		    columns: [[
                {field: 'vehicle_dentification_number',title: '车架号',width: 120,align: 'center',sortable: true},
                {field: 'engine_number',title: '发动机号',width: 100,align: 'center',sortable: true},
				 {
                    field: 'car_type',title: '车身类型',width: 100,align: 'center',
                    sortable: true,
                    formatter: function(value){
                        var car_type = <?php echo json_encode($config['car_type']); ?>;
                        try{
                            return car_type[value].text;
                        }catch(e){
                            return '';
                        }
                    }
                },

                {
                    field: 'car_status',title: '一级状态',width: 70,align: 'center',
                    sortable: true,
                    formatter: function(value){
                        var status = <?php echo json_encode($config['car_status']); ?>;
                        try{
                            return status[value].text;
                        }catch(e){
                            return '';
                        }
                    }
                },
                {
                    field: 'car_status2',title: '二级状态',width: 70,align: 'center',
                    sortable: true,
                    formatter: function(value){
                        var status = <?php echo json_encode($config['car_status2']); ?>;
                        try{
                            return status[value].text;
                        }catch(e){
                            return '';
                        }
                    }
                },



                {field: 'car_brand',title: '车辆品牌',width: 70,align: 'center',sortable: true},
                {
                    field: 'car_model_name',title: '车型名称',width: 90,align: 'center',
                    sortable: true,
                    /*formatter: function(value){
                        var car_type = <?php echo json_encode($config['car_model_name']); ?>;
                        try{
                            return car_type[value].text;    
                        }catch(e){
                            return '';
                        }
                    }*/
                },
               /* {
                    field: 'modified_type',title: '改装类型',width: 190,align: 'left',sortable: true
                },*/

                {
                    field: 'car_color',title: '车身颜色',width: 70,align: 'center',
                    sortable: true,
                    formatter: function(value){
                        var car_color = <?php echo json_encode($config['car_color']); ?>;
                        try{
                            return car_color[value].text;
                        }catch(e){
                            return '';
                        }
                    }
                },
                {
                    field: 'transact_dl',title: '行驶证',width: 70,align: 'center',
                    sortable: true,
                    formatter: function(value){
                        if(value >= 1){
                            return '<img src="./jquery-easyui-1.4.3/themes/icons/ok.png" />';
                        }else{
                            return '<img src="./jquery-easyui-1.4.3/themes/icons/no.png" />';
                        }
                    }
                },
                {
                    field: 'transact_ic',title: '交强险',width: 70,align: 'center',
                    sortable: true,
                    formatter: function(value){
                        if(value >= 1){
                            return '<img src="./jquery-easyui-1.4.3/themes/icons/ok.png" />';
                        }else{
                            return '<img src="./jquery-easyui-1.4.3/themes/icons/no.png" />';
                        }
                    }
                },
                {
                    field: 'transact_rtc',title: '道路运输证',width: 70,align: 'center',
                    sortable: true,
                    formatter: function(value){
                        if(value >= 1){
                            return '<img src="./jquery-easyui-1.4.3/themes/icons/ok.png" />';
                        }else{
                            return '<img src="./jquery-easyui-1.4.3/themes/icons/no.png" />';
                        }
                    }
                },
                {
                    field: 'transact_sm',title: '二级维护卡',width: 70,align: 'center',
                    sortable: true,
                    formatter: function(value){
                        if(value >= 1){
                            return '<img src="./jquery-easyui-1.4.3/themes/icons/ok.png" />';
                        }else{
                            return '<img src="./jquery-easyui-1.4.3/themes/icons/no.png" />';
                        }
                    }
                },
                {
                    field: 'transact_ib',title: '商业险',width: 70,align: 'center',
                    sortable: true,
                    formatter: function(value){
                        if(value >= 1){
                            return '<img src="./jquery-easyui-1.4.3/themes/icons/ok.png" />';
                        }else{
                            return '<img src="./jquery-easyui-1.4.3/themes/icons/no.png" />';
                        }
                    }
                },
                {
                    field: 'add_time',title: '入库时间',width: 90,align: 'center',
                    sortable: true,
                    formatter: function(value){
                        if(!isNaN(value) && value >0){
                            return formatDateToString(value);
                        }
                    }
                },
                {field: 'username',title: '操作人员',width: 100,halign: 'center',sortable: true},
                {field: 'note',title: '备注',width: 200,align: 'left'}
            ]],
            onDblClickRow: function(rowIndex,rowData){
                CarBaseinfoIndex.edit(rowData.id);
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
        var searchForm = $('#search-form-car-baseinfo-index');
        /**查询表单提交事件**/
        searchForm.submit(function(){
            var data = {};
            var searchCondition = $(this).serializeArray();
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = searchCondition[i]['value'];
            }
            $('#easyui-datagrid-car-baseinfo-index').datagrid('load',data);
            return false;
        });
        searchForm.find('input[name=plate_number]').textbox({
            onChange: function(){
                searchForm.submit();
            }
        });
        searchForm.find('input[name=vehicle_dentification_number]').textbox({
            onChange: function(){
                searchForm.submit();
            }
        });
        searchForm.find('input[name=car_status]').combobox({
            valueField:'value',
            textField:'text',
            data: <?= json_encode($searchFormOptions['car_status']); ?>,
            editable: false,
            panelHeight:'auto',
            onSelect: function(){
                searchForm.submit();
            }
        });
        searchForm.find('input[name=car_status2]').combobox({
            valueField:'value',
            textField:'text',
            data: <?= json_encode($searchFormOptions['car_status2']); ?>,
            editable: false,
            panelHeight:'auto',
            onSelect: function(){
                searchForm.submit();
            }
        });
        searchForm.find('input[name=transact_dl]').combobox({
            valueField:'value',
            textField:'text',
            editable: false,
            panelHeight:'auto',
            data: [{"value": '',"text": '不限'},{"value": 1,"text": '已办理'},{"value": 2,"text": '未办理'}],
            onSelect: function(){
                searchForm.submit();
            }
        });
        searchForm.find('input[name=transact_ic]').combobox({
            valueField:'value',
            textField:'text',
            editable: false,
            panelHeight:'auto',
            data: [{"value": '',"text": '不限'},{"value": 1,"text": '已办理'},{"value": 2,"text": '未办理'}],
            onSelect: function(){
                searchForm.submit();
            }
        });
        searchForm.find('input[name=transact_rtc]').combobox({
            valueField:'value',
            textField:'text',
            editable: false,
            panelHeight:'auto',
            data: [{"value": '',"text": '不限'},{"value": 1,"text": '已办理'},{"value": 2,"text": '未办理'}],
            onSelect: function(){
                searchForm.submit();
            }
        });
        searchForm.find('input[name=transact_sm]').combobox({
            valueField:'value',
            textField:'text',
            editable: false,
            panelHeight:'auto',
            data: [{"value": '',"text": '不限'},{"value": 1,"text": '已办理'},{"value": 2,"text": '未办理'}],
            onSelect: function(){
                searchForm.submit();
            }
        });
        searchForm.find('input[name=transact_ib]').combobox({
            valueField:'value',
            textField:'text',
            editable: false,
            panelHeight:'auto',
            data: [{"value": '',"text": '不限'},{"value": 1,"text": '已办理'},{"value": 2,"text": '未办理'}],
            onSelect: function(){
                searchForm.submit();
            }
        });
		searchForm.find('input[name=gain_way]').combobox({
            valueField:'value',
            textField:'text',
            data: <?= json_encode($searchFormOptions['gain_way']); ?>,
            editable: false,
            panelHeight:'auto',
            onSelect: function(){
                searchForm.submit();
            }
        });
		searchForm.find('input[name=gain_year]').combobox({
            valueField:'value',
            textField:'text',
            data: <?= json_encode($searchFormOptions['gain_year']); ?>,
            editable: false,
            panelHeight:'auto',
            onSelect: function(){
                searchForm.submit();
            }
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
        searchForm.find('input[name=car_type]').combobox({
        	valueField:'value',
            textField:'text',
            data: <?= json_encode($searchFormOptions['car_type']); ?>,
            editable: false,
            panelHeight:'auto',
            onSelect: function(){
                searchForm.submit();
            }
        });
		searchForm.find('input[name=owner_id]').combotree({
            url: "<?php echo yii::$app->urlManager->createUrl(['owner/combotree/get-owners']); ?>",
            editable: false,
            panelHeight:'auto',
            lines:false,
            onChange: function(o){
                searchForm.submit();
            }
        });

        
        //构建查询表单结束
		//初始化添加窗口
		$('#easyui-dialog-car-baseinfo-index-add').dialog({
        	title: '添加车辆信息',   
            width: '980px',   
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
                    var form = $('#easyui-form-car-baseinfo-add');
                    if(!form.form('validate')) return false;
					var data = form.serialize();
					$.ajax({
						type: 'post',
						url: "<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/add']); ?>",
						data: data,
						dataType: 'json',
						success: function(data){
							if(data.status){
								$.messager.alert('添加成功',data.info,'info');
								$('#easyui-dialog-car-baseinfo-index-add').dialog('close');
								$('#easyui-datagrid-car-baseinfo-index').datagrid('reload');
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
					$('#easyui-dialog-car-baseinfo-index-add').dialog('close');
				}
			}],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
        //初始化查看窗口
		$('#easyui-window-car-baseinfo-index-scan').window({
			title: '查看车辆信息',
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
        //初始化修改窗口
		$('#easyui-dialog-car-baseinfo-index-edit').dialog({
        	title: '修改车辆信息',   
            width: '980px',   
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
                    var form = $('#easyui-form-car-baseinfo-edit');
                    if(!form.form('validate')) return false;
                    var data = form.serialize();
					$.ajax({
						type: 'post',
						url: "<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/edit']); ?>",
						data: data,
						dataType: 'json',
						success: function(data){
							if(data.status){
								$.messager.alert('修改成功',data.info,'info');
								$('#easyui-dialog-car-baseinfo-index-edit').dialog('close');
								$('#easyui-datagrid-car-baseinfo-index').datagrid('reload');
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
					$('#easyui-dialog-car-baseinfo-index-edit').dialog('close');
				}
            }],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
        //初始化附件管理窗口
        $('#easyui-window-car-baseinfo-index-attachment').window({
            title: '车辆附件管理',
        	width: 800,   
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
        //初始化故障管理窗口
        $('#easyui-window-car-baseinfo-index-fault').window({
            title: '车辆故障管理',
        	width: 1000,   
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
        //初始化行驶证管理窗口
        $('#easyui-dialog-car-baseinfo-index-driving-license').dialog({
            title: '车辆行驶证',   
            width: '845px',   
            height: '400px',   
            closed: true,   
            cache: true,   
            modal: true,
            buttons: [],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
        //初始化道路运输证窗口
        $('#easyui-dialog-car-baseinfo-index-road-transport-certificate').dialog({
            title: '车辆道路运输证',   
            width: '630px',   
            height: '360px',   
            closed: true,   
            cache: true,   
            modal: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var form = $('#easyui-form-car-baseinfo-road-transport-certificate');
                    if(!form.form('validate')) return false;
                    if ($("input[name='image']").val() == ""){
    					$.messager.alert('添加失败','请上传道路运输证照片','error');
    					return false;
    				}

                    
                    var data = form.serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/road-transport-certificate']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('操作成功',data.info,'info');
                                $('#easyui-dialog-car-baseinfo-index-road-transport-certificate').dialog('close');
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
                    $('#easyui-dialog-car-baseinfo-index-road-transport-certificate').dialog('close');
                }
            }],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
        //初始化车辆二级维护记录管理窗口
        $('#easyui-window-car-baseinfo-second-maintenance-record').window({
            title: '车辆二级维护记录管理',
            width: 800,   
            height: 460,   
            modal: true,
            closed: true,
            collapsible: false,
            minimizable: false,
            maximizable: false,
            onClose: function(){
                $(this).window('clear');
            }                       
        });
        //初始化车辆交强险管理窗口
        $('#easyui-window-car-baseinfo-traffic-compulsory-insurance').window({
            title: '车辆交通强制险管理',
            width: 800,   
            height: 460,   
            modal: true,
            closed: true,
            collapsible: false,
            minimizable: false,
            maximizable: false,
            onClose: function(){
                $(this).window('clear');
            }                       
        });
        //初始化车辆商业险管理窗口
        $('#easyui-window-car-baseinfo-business-insurance').window({
            title: '车辆商业保险管理',
            width: 800,   
            height: 460,   
            modal: true,
            closed: true,
            collapsible: false,
            minimizable: false,
            maximizable: false,
            onClose: function(){
                $(this).window('clear');
            }                       
        });
	}
	CarBaseinfoIndex.init();

	
	//获取选择的记录
    //参数all = true标示是否要返回所有被选择的记录
	CarBaseinfoIndex.getSelected = function(all){
		var datagrid = $('#easyui-datagrid-car-baseinfo-index');
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
	//添加方法
	CarBaseinfoIndex.add = function(){
		$('#easyui-dialog-car-baseinfo-index-add').dialog('open');
		$('#easyui-dialog-car-baseinfo-index-add').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/add']); ?>");
	}
	//查看
	CarBaseinfoIndex.scan = function(){
		var selectRow = this.getSelected();
		if(!selectRow){
			return false;
		}
        var id = selectRow.id;
		$('#easyui-window-car-baseinfo-index-scan').window('open');
		$('#easyui-window-car-baseinfo-index-scan').window('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/scan']); ?>&id="+id);
	}
	//修改
	CarBaseinfoIndex.edit = function(id){
		if(!id){
			var selectRow = this.getSelected();
            if(!selectRow){
                return false;
            }
            id = selectRow.id;
		}
		$('#easyui-dialog-car-baseinfo-index-edit').dialog('open');
		$('#easyui-dialog-car-baseinfo-index-edit').dialog('refresh','<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/edit']); ?>&id='+id);
	}
	//删除
	CarBaseinfoIndex.remove = function(){
		var selectRow = this.getSelected();
        if(!selectRow) return false;
        var id = selectRow.id;
		$.messager.confirm('确定删除','您确定要删除该汽车数据？',function(r){
			if(r){
				$.ajax({
					type: 'get',
					url: '<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/remove']); ?>',
					data: {id: id},
					dataType: 'json',
					success: function(data){
						if(data.stauts){
							$.messager.alert('删除成功',data.info,'info');   
							$('#easyui-datagrid-car-baseinfo-index').datagrid('reload');
						}else{
							$.messager.alert('删除失败',data.info,'error');   
						}
					}
				});
			}
		});
	}
	//附件管理
	CarBaseinfoIndex.attachment = function(){
		var selectRow = this.getSelected();
        if(!selectRow) return false;
        var id = selectRow.id;
		$('#easyui-window-car-baseinfo-index-attachment').window('open');
		$('#easyui-window-car-baseinfo-index-attachment').window('refresh','<?php echo yii::$app->urlManager->createUrl(['car/attachment/index-single']); ?>&carId='+id);
	}
	//故障管理
	CarBaseinfoIndex.faultMange = function(){
		var selectRow = this.getSelected();
        if(!selectRow) return false;
        var id = selectRow.id;
		$('#easyui-window-car-baseinfo-index-fault').window('open');
		$('#easyui-window-car-baseinfo-index-fault').window('refresh','<?php echo yii::$app->urlManager->createUrl(['car/fault/index']); ?>&carId='+id);
	}
    //行驶证管理
    CarBaseinfoIndex.drivingLicense = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var id = selectRow.id;
        $('#easyui-dialog-car-baseinfo-index-driving-license').dialog('open');
        $('#easyui-dialog-car-baseinfo-index-driving-license').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/driving-license']); ?>&carId="+id);
    }
    //道路运输证管理
    CarBaseinfoIndex.roadTransportCertificate = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var id = selectRow.id;
        $('#easyui-dialog-car-baseinfo-index-road-transport-certificate').dialog('open');
        $('#easyui-dialog-car-baseinfo-index-road-transport-certificate').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/road-transport-certificate']); ?>&carId="+id);
    }
    //二级维护记录管理
    CarBaseinfoIndex.secondMaintenanceRecord = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var id = selectRow.id;
        $('#easyui-window-car-baseinfo-second-maintenance-record').window('open');
        $('#easyui-window-car-baseinfo-second-maintenance-record').window('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/second-maintenance-record']); ?>&carId="+id);
    }
    //交强险管理
    CarBaseinfoIndex.trafficCompulsoryInsurance = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var id = selectRow.id;
        $('#easyui-window-car-baseinfo-traffic-compulsory-insurance').window('open');
        $('#easyui-window-car-baseinfo-traffic-compulsory-insurance').window('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/traffic-compulsory-insurance']); ?>&carId="+id);
    }
    //商业险管理
    CarBaseinfoIndex.businessInsurance = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var id = selectRow.id;
        $('#easyui-window-car-baseinfo-business-insurance').window('open');
        $('#easyui-window-car-baseinfo-business-insurance').window('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/business-insurance']); ?>&carId="+id);
    }
    //导出所选择车辆的信息
    CarBaseinfoIndex.exportChooseCar = function(){
        var selectRows = this.getSelected(true);
        if(!selectRows){
            return false;
        }
        var id = '';
        for(var i in selectRows){
            id += selectRows[i].id+',';
        }
        window.open("<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/export-choose']);?>&id="+id);
    }
    //按条件导出车辆列表
    CarBaseinfoIndex.exportWidthCondition = function(){
        var url = "<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/export-width-condition']);?>";
        var form = $('#search-form-car-baseinfo-index');
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
    CarBaseinfoIndex.resetForm = function(){
        var easyuiForm = $('#search-form-car-baseinfo-index');
        easyuiForm.form('reset');
    }
	

    //导入功能
    /*CarBaseinfoIndex.import = function(){
        var url = "<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/import']);?>";
        var form = $('#search-form-car-baseinfo-index');
        var data = {};
        var searchCondition = form.serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        for(var i in data){
            url += '&'+i+'='+data[i];
        }
        window.open(url);
    }*/

    //初始化导入司机窗口
        $('#easyui-dialog-car-baseinfo-index-import').dialog({
            title: '导入车辆信息文件',   
            width: '415px',   
            height: '200px',   
            closed: true,   
            cache: true,   
            modal: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
					console.log("1");
                    CarBaseinfoIndex.import2();
                }
            },{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui-dialog-car-baseinfo-index-import').dialog('close');
                }
            }],
            onClose: function(){
                $(this).dialog('clear');
            } 
        });
	   //初始化导入行驶证窗口
        $('#easyui-dialog-car-baseinfo-index-driving-license-import').dialog({
            title: '导入行驶证信息文件',   
            width: '415px',   
            height: '200px',   
            closed: true,   
            cache: true,   
            modal: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    CarBaseinfoIndex.drivingLicenseImport2();
                }
            },{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui-dialog-car-baseinfo-index-driving-license-import').dialog('close');
                }
            }],
            onClose: function(){
                $(this).dialog('clear');
            } 
        });	

    //导入 import
    CarBaseinfoIndex.import = function(){
        $('#easyui-dialog-car-baseinfo-index-import').dialog('open');
        $('#easyui-dialog-car-baseinfo-index-import').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/import']); ?>");
    }
	
	
	 //导入行驶证
    CarBaseinfoIndex.drivingLicenseImport = function(){
		//console.log("hi");
		$('#easyui-dialog-car-baseinfo-index-driving-license-import').dialog('open');
        $('#easyui-dialog-car-baseinfo-index-driving-license-import').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/driving-license-import']); ?>");
    }
	

</script>