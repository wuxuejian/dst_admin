<table id="personalContractIndex_datagrid"></table> 
<div id="personalContractIndex_datagridToolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="personalContractIndex_searchForm">
                <ul class="search-main">
                    <li>
                        <div class="item-name">合同编号</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="number" style="width:100%;" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">合同类型</div>
                        <div class="item-input">
							<input style="width:200px;" name="contract_type" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">承租客户</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="customer_name" style="width:100%;" />
                        </div>
                    </li>
                    <li class="search-button">
                        <a onclick="personalContractIndex.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                        <a onclick="personalContractIndex.reset()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</a>
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
            <a onclick="<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon']; ?>'"><?= $val['text']; ?></a>
        <?php } ?>
        </div>
    <?php } ?>
</div>
<!-- 窗口 -->
<div id="personalContractIndex_addWin"></div>
<div id="personalContractIndex_editWin"></div>
<div id="personalContractIndex_carManageWin"></div>
<div id="personalContractIndex_renewAddWin"></div>
<div id="personalContractIndex_renewManageWin"></div>
<div id="personalContractIndexStop"></div>
<!-- 窗口 -->
<script>
    // 配置数据
    var personalContractIndex_CONFIG = <?php echo json_encode($config); ?>;

    var personalContractIndex = new Object();
    personalContractIndex.init = function(){
        //初始化-合同列表
        $('#personalContractIndex_datagrid').datagrid({  
            method: 'get', 
            url:"<?php echo yii::$app->urlManager->createUrl(['customer/personal-contract/get-list']); ?>",   
            fit: true,
            border: false,
            toolbar: "#personalContractIndex_datagridToolbar",
            pagination: true,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
            showFooter: true,
            frozenColumns: [[
                {field: 'ck',checkbox: true}, 
                {field: 'id',title: 'id',hidden: true},   
                {field: 'number',title: '合同编号',halign:'center',width: 120,sortable: true},
                 {field: 'contract_type',title: '合同类型',halign:'center',width: 120,sortable: true},
                {field: 'customer_name',title: '承租客户',width: 180,halign:'center',sortable: true}
            ]],
            columns:[[
                {field: 'customer_type',title: '客户类型',width: 60,align:'center',sortable: true,
                    formatter: function (value, row, index) {
                        try {
                            var str = 'personalContractIndex_CONFIG.customer_type.' + value + '.text';
                            return eval(str);
                        } catch (e) {
                            return value;
                        }
                    }
                },
                {
                    field: 'sign_date',title: '签订时间',width: 80, align: 'center',
                    sortable: true,
                    formatter: function(value){
                        if(value){
                            return formatDateToString(value)
                        }
                    }
                },
                {
                    field: 'start_time',title: '开始时间',width: 80, align: 'center',
                    sortable: true,
                    formatter: function(value){
                        if(value){
                            return formatDateToString(value)
                        }
                    }
                },
                {
                    field: 'end_time',title: '结束时间',width: 80, align: 'center',
                    sortable: true,
                    formatter: function(value){
                        if(value){
                            return formatDateToString(value)
                        }
                    }
                },
                {
                    field: 'due_time',title: '合同期限',width: 80, align: 'center',
                    sortable: true,
                    formatter: function(value){
                        if(value){
                            return formatDateToString(value)
                        }
                    }
                },
                {
                    field: 'cost_expire_time',title: '费用到期时间',
                    width: 120, align: 'center',sortable: true,
                    formatter: function(value){
                        if(isNaN(value)){
                            return '';
                        }
                        if(value == 0){
                            return '未启动';
                        }
                        var format = formatDateToString(value);
                        var timestamp = parseInt(Date.parse(new Date()) / 1000);//当前时间戳
                        var status = '';
                        if(value <= timestamp){
                            //已经过期
                            status = '（已过期）';
                        }else if(value < (timestamp + 1296000)){
                            //十五天内到期
                            var day = Math.ceil((value - timestamp) / 86400);
                            status = '<b style="color:red">（'+day+'天后过期）</b>';
                        }else{
                            status =  '';
                        }
                        return format+status;
                    }
                },
				{
					field: 'is_stop',title: '合同状态',width: 80,align: 'center',
                    formatter: function(value){
                        if(value==1){
							return '终止合作';
						}else {
							return '';
						}
                    }
				},
				{
					field: 'stop_type',title: '合同终止类型',width: 100,align: 'center',
                    formatter: function(value){
						var stop_type_arr = {"0":"", "1":"合同到期自动终止", "2":"我方原因提前终止", "3":"客户原因提前终止"};
						return stop_type_arr[value];
                    }
				},
				{
					field: 'stop_cause',title: '终止合作原因',width: 100,align: 'center',
                    formatter: function(value){
                        var stop_type_arr = {"0":"", "1":"客户欠费", "2":"未按时处理违章", "3":"未按时处理年检", "4":"运维服务保障差", "5":"车型不符", "6":"车辆质量", "7":"路权政策"};
						return stop_type_arr[value];
                    }
				},
                {field: 'note',title: '备注',width: 200,halign: 'center'},
                {field: 'operating_company',title: '所属运营公司',width: 170,halign: 'center'},
                {
                    field: 'reg_time',title: '登记时间',width: 130,
                    align: 'center',sortable: true,
                    formatter: function(value){
                        if(value){
                            return formatDateToString(value,true);
                        }
                    }
                },
                {
                    field: 'last_modify_datetime',title: '上次操作时间',width: 130,
                    align: 'center',sortable: true,
                    formatter: function(value){
                        if(!isNaN(value) && value > 0){
                            return formatDateToString(value,true);
                        }
                    }
                },
                {field: 'username',title: '操作账号',width: 100,halign: 'center',sortable: true},
            ]],
            //双击
            onDblClickRow: function(rowIndex,rowData){
                personalContractIndex.edit(rowData.id);
            },
            onLoadSuccess: function (data) {
                //单元格内容悬浮提示，doCellTip()是在入口文件index.php中拓展的。
                $(this).datagrid('doCellTip', {
                    position: 'bottom',
                    maxWidth: '200px',
                    onlyShowInterrupt: true, //false时所有单元格都显示提示；true时配合specialShowFields自定义要提示的列
                    specialShowFields: [     //需要提示的列
                        //{field: 'company_name', showField: 'company_name'}
                    ],
                    tipStyler: {
                        backgroundColor: '#E4F0FC',
                        borderColor: '#87A9D0',
                        boxShadow: '1px 1px 3px #292929'
                    }
                });
            }
        });
        //初始化-新增合同窗口
        $('#personalContractIndex_addWin').dialog({
            title: '&nbsp;新建个人客户合同',
            iconCls:'icon-add', 
            width: '80%',   
            height: '90%',
            closed: true,   
            cache: true,   
            modal: true,
            maximizable: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    //回调添加页面submitForm方法
                    personalContractIndex_addWin.submitForm();
                    $('#personalContractIndex_datagrid').datagrid('reload');
                }
            },{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#personalContractIndex_addWin').dialog('close');
                }
            }],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
        //初始化-修改合同窗口
        $('#personalContractIndex_editWin').dialog({
            title: '&nbsp;修改客户合同', 
            iconCls:'icon-edit',
            width: '80%',   
            height: '90%',
            closed: true,   
            cache: true,   
            modal: true,
            maximizable: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var form = $('#personalContractIndex_editWin_form');
                    if(!form.form('validate')){
                        return false;
                    }
                    var data = form.serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['customer/personal-contract/edit']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('修改成功',data.info,'info');
                                $('#personalContractIndex_editWin').dialog('close');
                                $('#personalContractIndex_datagrid').datagrid('reload');
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
                    $('#personalContractIndex_editWin').dialog('close');
                }
            }],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
        //初始化-添加续费记录窗口
        $('#personalContractIndex_renewAddWin').dialog({
            title: '添加续费记录',   
            width: '640px',   
            height: '400px',   
            closed: true,   
            cache: true,   
            modal: true,
            maximizable: false,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var form = $('#personalContractIndex_renewManageWin_renewAddWin_from');
                    if(!form.form('validate')){
                        return false;
                    }
                    var data = form.serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['customer/personal-contract/renew-add']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('添加成功',data.info,'info');
                                $('#personalContractIndex_renewAddWin').dialog('close');
                                $('#personalContractIndex_datagrid').datagrid('reload');
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
                    $('#personalContractIndex_renewAddWin').dialog('close');
                }
            }],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
        //初始化签约车辆管理窗口
        $('#personalContractIndex_carManageWin').window({
            title: '签约车辆管理',   
            width: 900,   
            height: 500,   
            closed: true,   
            cache: true,   
            modal: true,
            minimizable: false,
            collapsible: false,
            onClose: function(){
                $(this).window('clear');
            }
        });
        //初始化续费管理窗口
        $('#personalContractIndex_renewManageWin').window({
            title: '合同续费管理',   
            width: 900,   
            height: 500,   
            closed: true,   
            cache: true,   
            modal: true,
            minimizable: false,
            collapsible: false,
            onClose: function(){
                $(this).window('clear');
            }
        });
		//初始化终止合同窗口
		$('#personalContractIndexStop').dialog({
        	title: '合同终止',   
            width: '700px',   
            height: '200px',   
            closed: true,   
            cache: true,   
            modal: true,
            resizable:true,
            maximizable: true,
            buttons: [{
				text:'确定',
				iconCls:'icon-ok',
				handler:function(){
                    var form = $('#easyui-form-customer-personal-contract-index-stop');
                    if(!form.form('validate')) return false;
					var data = form.serialize();
					$.ajax({
						type: 'post',
						url: "<?php echo yii::$app->urlManager->createUrl(['customer/personal-contract/stop']); ?>",
						data: data,
						dataType: 'json',
						success: function(data){
							if(data.status){
								$.messager.alert('操作成功',data.info,'info');
								$('#personalContractIndexStop').dialog('close');
								$('#personalContractIndex_datagrid').datagrid('reload');
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
					$('#personalContractIndexStop').dialog('close');
				}
			}],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
    }
	//终止合同
	personalContractIndex.stop = function(id){
		if(!id){
            var selectRow = this.getSelected();
            if(!selectRow)  return false;
            id = selectRow.id;
        }
		$('#personalContractIndexStop').dialog('open');
		$('#personalContractIndexStop').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['customer/personal-contract/stop']); ?>&id="+id);
	}

    //查询列表
     var searchForm = $('#personalContractIndex_searchForm');
        /**查询表单提交事件**/
        searchForm.submit(function(){
            var data = {};
            var searchCondition = $(this).serializeArray();
            
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = searchCondition[i]['value'];
            }
            $('#personalContractIndex_datagrid').datagrid('load',data);
            return false;
        });

    searchForm.find('input[name=contract_type]').combobox({
            valueField:'value',
            textField:'text',
            editable: false,
            panelHeight:'auto',
            data: [{"value": '',"text": '不限'},{"value": '自运营',"text": '自运营'},{"value": '租赁',"text": '租赁'}],
            onSelect: function(){
                searchForm.submit();
            }
        });

    //获取选择的记录
    personalContractIndex.getSelected = function(){
        var datagrid = $('#personalContractIndex_datagrid');
        var selectRow = datagrid.datagrid('getSelected');
        if(!selectRow){
            $.messager.alert('错误','请选择要操作的记录','error');   
            return false;
        }
        return selectRow;
    }
    //新建合同
    personalContractIndex.add = function(){
        $('#personalContractIndex_addWin')
            .dialog('open')
            .dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['customer/personal-contract/add']); ?>");
    }
    //修改合同
    personalContractIndex.edit = function(id){
        if(!id){
            var selectRow = this.getSelected();
            if(!selectRow)  return false;
            id = selectRow.id;
        }
        $('#personalContractIndex_editWin')
            .dialog('open')
            .dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['customer/personal-contract/edit']); ?>&id="+id);
    }
    //签约车辆管理
    personalContractIndex.carManage = function(){
        var selectRow = this.getSelected();
        if(!selectRow)  return false;
        var id = selectRow.id;
        $('#personalContractIndex_carManageWin')
            .window('open')
            .window('refresh',"<?php echo yii::$app->urlManager->createUrl(['customer/personal-contract/car-manage']); ?>&id="+id);
    }
    //合同续费管理
    personalContractIndex.renewManage = function(){
        var selectRow = this.getSelected();
        if(!selectRow)  return false;
        var id = selectRow.id;
        $('#personalContractIndex_renewManageWin')
            .window('open')
            .window('refresh',"<?php echo yii::$app->urlManager->createUrl(['customer/personal-contract/renew-manage']); ?>&id="+id);
    }
    //添加续费记录
    personalContractIndex.renewAdd = function(){
        var selectRow = this.getSelected();
        if(!selectRow)  return false;
        id = selectRow.id;
        $('#personalContractIndex_renewAddWin')
            .dialog('open')
            .dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['customer/personal-contract/renew-add']); ?>&contractId="+id);
    }
    //导出
    personalContractIndex.exportWidthCondition = function(){
        var form = $('#personalContractIndex_searchForm');
        window.open("<?= yii::$app->urlManager->createUrl(['customer/personal-contract/export-width-condition']); ?>&"+form.serialize());
    }
    //删除合同
    personalContractIndex.remove = function(){
        var selectRow = this.getSelected();
        if(!selectRow)  return false;
        id = selectRow.id;
        $.messager.confirm('删除确认','您确定要删除所选的合同？',function(r){
            if(r){
                $.ajax({
                    type:'get',
                    url: "<?= yii::$app->urlManager->createUrl(['customer/personal-contract/remove']); ?>",
                    data: {'id': id},
                    dataType: 'json',
                    success: function(data){
                        if(data.status){
                            $.messager.alert('删除成功',data.info,'info');
                            $('#personalContractIndex_datagrid').datagrid('reload');
                        }else{
                            $.messager.alert('删除失败',data.info,'error');
                        }
                    }
                });
            }
        });
    }
    //查询
    personalContractIndex.search = function(){
        var form = $('#personalContractIndex_searchForm');
        var data = {};
        var searchCondition = form.serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        $('#personalContractIndex_datagrid').datagrid('load',data);
    }
    //重置
    personalContractIndex.reset = function(){
        //$('#personalContractIndex_searchForm').form('reset');
        var easyuiForm = $('#personalContractIndex_searchForm');
        easyuiForm.form('reset');
        easyuiForm.submit();
    }
    //执行
    personalContractIndex.init();
</script>