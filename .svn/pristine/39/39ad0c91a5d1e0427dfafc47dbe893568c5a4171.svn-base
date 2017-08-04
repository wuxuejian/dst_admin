<table id="easyui-datagrid-car-maintain-record-main"></table> 
<div id="easyui-datagrid-car-maintain-record-main-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-car-maintain-record-main">
                <ul class="search-main">
                    <li>
                        <div class="item-name">车牌号</div>
                        <div class="item-input">
                            <input name="plate_number" style="width:200px">
                        </div>
                    </li>
                    <li>
                        <div class="item-name">车型名称</div>
                        <div class="item-input">
                           <!-- <input class="easyui-textbox" type="text" align:"center" name="contract_type" style="width:100%;" /> -->
                            <input class="easyui-combobox" style="width:200px;" name="car_model" id="car_model_id" />
                        </div>
                    </li>
                   <!--  <li class="item-name">
                        <div class="item-name">归属客户</div>
                        <div class="item-input">
                            <input
                                id="easyui-form-car-maintain-record-mainCombogrid"
                                name="customer"
                                style="width:200px;"
                                />
                        </div>
                    </li> -->
                    <li>
                        <div class="item-name">保养类型</div>
                        <div class="item-input">
                            <input class="easyui-combobox" name="maintain_type" style="width:200px" id="type_id" >
                        </div>
                    </li>
                    <li>
                        <div class="item-name">保养时间</div>
                        <div class="item-input">
                            <input class="easyui-datebox" type="text" name="start_add_time" style="width:93px;"
                                   data-options=""
                                />
                            -
                            <input class="easyui-datebox" type="text" name="end_add_time" style="width:93px;"
                                   data-options=""
                                />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">保养厂</div>
                        <div class="item-input">
                            <input  name="maintenance_shop" style="width:200px;" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">登记人</div>
                        <div class="item-input">
                            <input name="reg_name" style="width:200px;" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">车辆运营公司</div>
                        <div class="item-input">
                            <input style="width:200px;" name="operating_company_id" />
                        </div>
                    </li>
                    
                    <li class="search-button" >
                        <button class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button onclick="CarMaintainRecordMain.resetForm();return false;" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
                    </li>
                </ul>
            </form>
        </div>
    </div>
    <?php if($buttons){ ?>
    <div class="easyui-panel" title="数据列表" style="padding:8px 4px;" data-options="
        iconCls: 'icon-tip',
        border: false
    ">
        <?php foreach($buttons as $val){ ?>
        <a href="javascript:<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon']; ?>'"><?= $val['text']; ?></a>
        <?php } ?>
    </div>
    <?php } ?>
</div>
<!-- 窗口 -->
<div id="easyui-dialog-car-maintain-record-main-add"></div>
<div id="easyui-dialog-car-maintain-record-main-edit"></div>
<div id="easyui-dialog-car-maintain-record-main-scan"></div>
<!-- 窗口 -->
<script>
    var CarMaintainRecordMain = new Object();
    CarMaintainRecordMain.init = function(){
        var easyuiDatagrid = $('#easyui-datagrid-car-maintain-record-main');
        //获取列表数据
        easyuiDatagrid.datagrid({  
            method: 'get', 
            url:"<?php echo yii::$app->urlManager->createUrl(['car/maintain-record/get-main-list']); ?>&carId=<?=@$_GET['carId']?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-car-maintain-record-main-toolbar",
            pagination: true,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
            frozenColumns: [[
                {field: 'ck',checkbox: true}, 
                {field: 'id',title: 'id',hidden: true}
            ]],
            columns:[
               [
                {
                    field: 'plate_number',title: '车牌号',width: 100
                },
				 {
                    field: 'add_time',title: '进厂时间',width: 200
                },
                {
                    field: 'out_time',title: '出厂时间',width: 200
                },
                {
                    field: 'driving_mileage',title: '保养公里数',width: 100
                },
                {
                    field: 'type',title: '保养类别',width: 100
                },
                {
                    field: 'site_name',title: '保养维修厂',width: 360
                },
				
                // {
                    // field: 'type',title: '保养类型',width: 100,
                    // formatter: function(value,row,index){ //企业/个人客户名称
                        // if(value == 1){
                            // return 'A保';
                        // }else if(value == 2){
                            // return 'B保';
                        // }else{
                            // return '';
                        // }
                    // }
                // },
                {
                    field: 'amount',title: '保养费用',width: 100
                },
                {
                    field: 'reg_name',title: '登记人',width: 100
                }
            ]],
            onDblClickRow: function(rowIndex,rowData){
                MaintainRecord.edit(rowData.id);
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
        //构建查询表单
        var searchForm = $('#search-form-car-maintain-record-main');
        searchForm.submit(function(){
            var data = {};
            var searchCondition = $(this).serializeArray();
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = searchCondition[i]['value'];
            }
            easyuiDatagrid.datagrid('load',data);
            return false;
        });
        searchForm.find('input[name=maintenance_type]').combobox({
           /* valueField:'value',
            textField:'text',
            data: [{"value":"","text":"不限"},{"value":"1","text":"A保"},{"value":"2","text":"B保"}],
            editable: false,
            onChange: function(){
                searchForm.submit();
            }*/
        });
        searchForm.find('input[name=maintenance_shop]').textbox({
            onChange: function(){
                searchForm.submit();
            }
        });
        searchForm.find('input[name=plate_number]').textbox({
            onChange: function(){
                searchForm.submit();
            }
        });
        searchForm.find('input[name=reg_name]').textbox({
            onChange: function(){
                searchForm.submit();
            }
        });
         /*searchForm.find('input[name=car_type]').textbox({
            onChange: function(){
                searchForm.submit();
            }
        });*/
        searchForm.find('input[name=car_model]').combobox({
            valueField:'value',
            textField:'text',
            data: <?= json_encode($searchFormOptions['car_model_name']); ?>,
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
        //构建查询表单结束
        
      //初始化查看窗口
        $('#easyui-dialog-car-maintain-record-main-scan').window({
            title: '查看详情',
            width: '780px',   
            height: '530px',   
            closed: true,   
            cache: true,   
            modal: true,
            collapsible: false,
            minimizable: false, 
            maximizable: false,
            onClose: function(){
                $(this).window('clear');
            }       
        });
        //初始化归属客户
        $('#easyui-form-car-maintain-record-mainCombogrid').combogrid({
            panelWidth: 450,
            panelHeight: 200,
            missingMessage: '请输入检索后从下拉列表里选择一项！',
            onHidePanel:function(){
                var _combogrid = $(this);
                var value = _combogrid.combogrid('getValue');
                var text = _combogrid.combogrid('textbox').val();
                var row = _combogrid.combogrid('grid').datagrid('getSelected');
                if(!row){ //没有选择表格行但输入有检索字符串时，提示并清除检索字符串
                    if(text && value == text){
                        $.messager.show(
                            {
                                title: '无效值',
                                msg:'【' + text + '】不是有效值！请重新输入检索后，从下拉列表里选择一项！'
                            }
                        );
                        _combogrid.combogrid('clear');
                    }
                }
            },
            delay: 800,
            mode:'remote',
            idField: 'value',
            textField: 'text',
            url: '<?= yii::$app->urlManager->createUrl(['car/insurance/get-customers']); ?>',
            method: 'get',
            scrollbarSize:0,
            pagination: false,
            pageSize: 10,
            pageList: [10,20,30],
            fitColumns: true,
            rownumbers: true,
            onSelect: function(){
                searchForm.submit();
            },
            columns: [[
                {field:'value',title:'归属客户key',width:40,align:'center',hidden:true},
                {field:'text',title:'归属客户',width:150,align:'center'}
            ]]
        });
        //初始化添加窗口
        $('#easyui-dialog-car-maintain-record-main-add').dialog({
            title: '添加保养',   
            width: '580px',   
            height: '530px',   
            closed: true,   
            cache: true,   
            modal: true,
            resizable:true,
            maximizable: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var form = $('#easyui-form-process-repair-maintain-add-from');
                    if(!form.form('validate')) return false;
                    var data = form.serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['car/maintain-record/add']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('添加成功',data.info,'info');
                                $('#easyui-dialog-car-maintain-record-main-add').dialog('close');
                                $('#easyui-datagrid-car-maintain-record-main').datagrid('reload');
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
                    $('#easyui-dialog-car-maintain-record-main-add').dialog('close');
                }
            }],
            onClose: function(){
                $(this).dialog('clear');
            }
        });

        //初始化修改窗口
        $('#easyui-dialog-car-maintain-record-main-edit').dialog({
            title: '修改保养',   
            width: '580px',   
            height: '460px',   
            closed: true,   
            cache: true,   
            modal: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var form = $('#easyui-form-process-repair-maintain-edit-from');
                    if(!form.form('validate')) return false;
                    var data = form.serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['car/maintain-record/edit']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('修改成功',data.info,'info');
                                $('#easyui-dialog-car-maintain-record-main-edit').dialog('close');
                                $('#easyui-datagrid-car-maintain-record-main').datagrid('reload');
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
                    $('#easyui-dialog-car-maintain-record-main-edit').dialog('close');
                }
            }],
            onClose: function(){
                $(this).dialog('clear');
            } 
        });
        //***********************车辆类型与保养类型联动**********************
        //$(function(){
        $('#car_model_id').combobox({
        onChange: function (n,o) {
            $('#type_id').combobox('clear');
            var car_model_name = $('#car_model_id').combobox('getValue');
            //console.log(n)
            //console.log(o)
           // console.log(car_model_name)
            $.ajax({
                   url:"<?php echo yii::$app->urlManager->createUrl(['car/maintain-record/check3']); ?>",
                   type:'post',
                   data:{car_model_name:car_model_name},
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
                                a['value'] = value.id;
                                a['text'] = value.maintain_type;
                               // a['text'] = value['text'];
                               //console.log(a);
                                current_type.push(a);
                               //console.log(current_type);
                                
                        });
                        $("#type_id").combobox("setValue",'');
                        $("#type_id").combobox("loadData",current_type);
                    }
            });
        }
        }); 
       // }
        

    
    }
    CarMaintainRecordMain.init();
    //获取选择的记录
    CarMaintainRecordMain.getSelected = function(){
        var datagrid = $('#easyui-datagrid-car-maintain-record-main');
        var selectRow = datagrid.datagrid('getSelected');
        if(!selectRow){
            $.messager.alert('错误','请选择要操作的记录','error');   
            return false;
        }
		console.log(selectRow);
        return selectRow;
    }
    //添加
    CarMaintainRecordMain.add = function(){
        $('#easyui-dialog-car-maintain-record-main-add').dialog('open');
        $('#easyui-dialog-car-maintain-record-main-add').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/maintain-record/add']); ?>");
    }
  //删除
	CarMaintainRecordMain.remove = function(){
		var selectRow = this.getSelected();
        if(!selectRow) return false;
        var id = selectRow.id;
		$.messager.confirm('确定删除','您确定要删除该数据？',function(r){
			if(r){
				$.ajax({
					type: 'get',
					url: '<?php echo yii::$app->urlManager->createUrl(['car/maintain-record/remove']); ?>',
					data: {id: id},
					dataType: 'json',
					success: function(data){
						if(data.status){
							$.messager.alert('删除成功',data.info,'info');   
							$('#easyui-datagrid-car-maintain-record-main').datagrid('reload');
						}else{
							$.messager.alert('删除失败',data.info,'error');   
						}
					}
				});
			}
		});
	}
    //查看
    CarMaintainRecordMain.scan = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var id = selectRow.id;
        $('#easyui-dialog-car-maintain-record-main-scan').window('open');
        $('#easyui-dialog-car-maintain-record-main-scan').window('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/maintain-record/scan']); ?>&id="+id);
    }
    //修改
    CarMaintainRecordMain.edit = function(id){
        if(!id){
            var selectRow = this.getSelected();
            if(!selectRow){
                return false;
            }
            id = selectRow.id;
        }
        $('#easyui-dialog-car-maintain-record-main-edit').dialog('open');
        $('#easyui-dialog-car-maintain-record-main-edit').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/maintain-record/edit']); ?>&id="+id);
    }
    //按条件导出车辆列表
    CarMaintainRecordMain.export = function(){
        var url = "<?php echo yii::$app->urlManager->createUrl(['car/maintain-record/export']);?>";
        var form = $('#search-form-car-maintain-record-main');
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
    CarMaintainRecordMain.resetForm = function(){
        var easyuiForm = $('#search-form-car-maintain-record-main');
        easyuiForm.form('reset');
        easyuiForm.submit();
    }
</script>