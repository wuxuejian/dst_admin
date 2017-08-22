<table id="easyui-datagrid-customer-contract-record-index"></table> 
<div id="easyui-datagrid-customer-contract-record-index-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-customer-contract-record-index">
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
                           <!-- <input class="easyui-textbox" type="text" align:"center" name="contract_type" style="width:100%;" /> -->
							<input id="contract_type_id2" style="width:200px;" name="contract_type" />
                        </div>
                    </li>

                    <span id='tip2'></span>
                     <li>
                        <div class="item-name">客户来源</div>
                        <div class="item-input">
                            <input style="width:200px;" name="source" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">承租客户</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="customer_name" style="width:100%;" />
                        </div>
                    </li>
                    <li class="search-button">
                        <a onclick="javascript:CustomerContractRecordIndex.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                        <a onclick="javascript:CustomerContractRecordIndex.reset()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</a>
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
<div id="easyui-datagrid-customer-contract-record-index-add"></div>
<div id="easyui-datagrid-customer-contract-record-index-edit"></div>
<div id="easyui-datagrid-customer-contract-record-index-car-add"></div>
<div id="easyui-window-customer-contract-record-index-car-manage"></div>
<div id="easyui-window-customer-contract-record-index-renew-add"></div>
<div id="easyui-window-customer-contract-record-index-renew-manage"></div>
<div id="easyui-dialog-customer-contract-record-index-stop"></div>
<!-- 窗口 -->
<script>
    // 配置数据
    var CustomerContractRecordIndex_CONFIG = <?php echo json_encode($config); ?>;

    var CustomerContractRecordIndex = new Object();
    CustomerContractRecordIndex.init = function(){
        //获取列表数据
        $('#easyui-datagrid-customer-contract-record-index').datagrid({  
            method: 'get', 
            url:"<?php echo yii::$app->urlManager->createUrl(['customer/contract-record/get-list']); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-customer-contract-record-index-toolbar",
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
                {field: 'number',title: '合同编号',align:'center',width: 120,sortable: true},
                {field: 'contract_type',title: '合同类型',align:'center',width: 120,sortable: true},
                {field: 'customer_name',title: '承租客户',width: 180,align:'center',sortable: true}
            ]],
            columns:[[
			    {field: 'customer_type',title: '客户类型',width: 60,align:'center',sortable: true,
                    formatter: function (value, row, index) {
                        try {
                            var str = 'CustomerContractRecordIndex_CONFIG.customer_type.' + value + '.text';
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
                    field: '_end_time',title: '倒计时',width: 80, align: 'center',
                    formatter: function(value){
                        if(isNaN(value) || value <= 0){
                            return '';
                        }
                        value = parseInt(value) + 86400;
                        var timeStamp = Date.parse(new Date()) / 1000;
                        if(value <= timeStamp){
                            return '<span style="color:red">已过期</span>';
                        }
                        var leftDay = Math.ceil((value - timeStamp) / 86400);
                        if(leftDay <= 7){
                            return '<span style="color:red">'+leftDay+'天</span>';
                        }
                        return leftDay+'天';
                    }
                },
                {
                    field: '_due_time',title: '合同期限',width: 80, align: 'center',
                    sortable: true,
                    /*formatter: function(value){
                        if(value){
                            return formatDateToString(value)
                        }
                    }*/
                },
                {
                    field: 'car_let_record_num',title: '车辆数量',width: 80, align: 'center'
                },
                {
                    field: 'cost_expire_time',title: '费用到期时间',
                    width: 100, align: 'center',sortable: true,
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
                {field: 'source',title: '客户来源',width: 90,align: 'center',
                    formatter: function(value){
                        if(value == 1){
                            return '400呼叫中心';
                        } else if(value == 1){
                            return '400呼叫中心';
                        }else if(value == 2){
                            return '地推';
                        } else if(value == 3){
                            return '大客户导入';
                        } else if(value == 4){
                            return '自主开发';
                        } else if(value == 5){
                            return '转介绍';
                        } else if(value == 6){
                            return '活动促销';
                        } else if(value == 7){
                            return '其他';
                        } else {
                            return '';
                        }  
                    }
                },
                {field: 'second_contract_type',title: '业务类型',width: 90,align: 'center'},
                {field: 'rent_day',title: '每月租金缴纳日',width: 100,align: 'center',
                    /* formatter: function(value){
                        if(value){
                            return formatDateToString(value)
                        }
                    }*/
                },

                {field: 'note',title: '备注',width: 200,align: 'center'},
                {field: 'operating_company',title: '所属运营公司',width: 170,align: 'center'},
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
                {field: 'username',title: '操作账号',width: 100,align: 'center',sortable: true}
            ]],
            //双击
            onDblClickRow: function(rowIndex,rowData){
                CustomerContractRecordIndex.edit(rowData.id);
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
        //初始化新增合同窗口
        $('#easyui-datagrid-customer-contract-record-index-add').dialog({
            title: '&nbsp;新建客户合同',
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
                    CustomerContractRecordAdd.submitForm();
                }
            },{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui-datagrid-customer-contract-record-index-add').dialog('close');
                }
            }],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
        //初始化修改合同窗口
        $('#easyui-datagrid-customer-contract-record-index-edit').dialog({
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
                    var form = $('#easyui-form-customer-contract-record-index-edit');
                    if(!form.form('validate')){
                        return false;
                    }
                    var data = form.serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['customer/contract-record/edit']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('修改成功',data.info,'info');
                                $('#easyui-datagrid-customer-contract-record-index-edit').dialog('close');
                                $('#easyui-datagrid-customer-contract-record-index').datagrid('reload');
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
                    $('#easyui-datagrid-customer-contract-record-index-edit').dialog('close');
                }
            }],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
        //初始化添加续费记录窗口
        $('#easyui-window-customer-contract-record-index-renew-add').dialog({
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
                    var form = $('#easyui-form-customer-contract-record-renew-add');
                    if(!form.form('validate')){
                        return false;
                    }
                    var data = form.serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['customer/contract-record/renew-add']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('添加成功',data.info,'info');
                                $('#easyui-window-customer-contract-record-index-renew-add').dialog('close');
                                $('#easyui-datagrid-customer-contract-record-index').datagrid('reload');
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
                    $('#easyui-window-customer-contract-record-index-renew-add').dialog('close');
                }
            }],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
        //初始化签约车辆管理窗口
        $('#easyui-window-customer-contract-record-index-car-manage').window({
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
        $('#easyui-window-customer-contract-record-index-renew-manage').window({
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
		$('#easyui-dialog-customer-contract-record-index-stop').dialog({
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
                    var form = $('#easyui-form-customer-contract-record-index-stop');
                    if(!form.form('validate')) return false;
					var data = form.serialize();
					$.ajax({
						type: 'post',
						url: "<?php echo yii::$app->urlManager->createUrl(['customer/contract-record/stop']); ?>",
						data: data,
						dataType: 'json',
						success: function(data){
							if(data.status){
								$.messager.alert('操作成功',data.info,'info');
								$('#easyui-dialog-customer-contract-record-index-stop').dialog('close');
								$('#easyui-datagrid-customer-contract-record-index').datagrid('reload');
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
					$('#easyui-dialog-customer-contract-record-index-stop').dialog('close');
				}
			}],
            onClose: function(){
                $(this).dialog('clear');
            }
        });
    }

	//终止合同
	CustomerContractRecordIndex.stop = function(id){
		if(!id){
            var selectRow = this.getSelected();
            if(!selectRow)  return false;
            id = selectRow.id;
        }
		$('#easyui-dialog-customer-contract-record-index-stop').dialog('open');
		$('#easyui-dialog-customer-contract-record-index-stop').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['customer/contract-record/stop']); ?>&id="+id);
	}
    //获取选择的记录
    CustomerContractRecordIndex.getSelected = function(){
        var datagrid = $('#easyui-datagrid-customer-contract-record-index');
        var selectRow = datagrid.datagrid('getSelected');
        if(!selectRow){
            $.messager.alert('错误','请选择要操作的记录','error');   
            return false;
        }
        return selectRow;
    }
    //新建合同
    CustomerContractRecordIndex.add = function(){
        $('#easyui-datagrid-customer-contract-record-index-add').dialog('open');
        $('#easyui-datagrid-customer-contract-record-index-add').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['customer/contract-record/add']); ?>");
    }
    //修改合同
    CustomerContractRecordIndex.edit = function(id){
        if(!id){
            var selectRow = this.getSelected();
            if(!selectRow)  return false;
            id = selectRow.id;
        }
        $('#easyui-datagrid-customer-contract-record-index-edit').dialog('open');
        $('#easyui-datagrid-customer-contract-record-index-edit').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['customer/contract-record/edit']); ?>&id="+id);
    }
    //添加签约车辆
    CustomerContractRecordIndex.addCar = function(){
        var selectRow = this.getSelected();
        if(!selectRow)  return false;
        id = selectRow.id;
        $('#easyui-datagrid-customer-contract-record-index-car-add').dialog('open');
        $('#easyui-datagrid-customer-contract-record-index-car-add').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['customer/contract-record/car-add']); ?>&contractId="+id);
    }
    //签约车辆管理
    CustomerContractRecordIndex.carManage = function(){
        var selectRow = this.getSelected();
        if(!selectRow)  return false;
        var id = selectRow.id;
        $('#easyui-window-customer-contract-record-index-car-manage').window('open');
        $('#easyui-window-customer-contract-record-index-car-manage').window('refresh',"<?php echo yii::$app->urlManager->createUrl(['customer/contract-record/car-manage']); ?>&id="+id);
    }
    //合同续费管理
    CustomerContractRecordIndex.renewManage = function(){
        var selectRow = this.getSelected();
        if(!selectRow)  return false;
        var id = selectRow.id;
        $('#easyui-window-customer-contract-record-index-renew-manage').window('open');
        $('#easyui-window-customer-contract-record-index-renew-manage').window('refresh',"<?php echo yii::$app->urlManager->createUrl(['customer/contract-record/renew-manage']); ?>&id="+id);
    }
    //添加续费记录
    CustomerContractRecordIndex.renewAdd = function(){
        var selectRow = this.getSelected();
        if(!selectRow)  return false;
        id = selectRow.id;
        $('#easyui-window-customer-contract-record-index-renew-add').dialog('open');
        $('#easyui-window-customer-contract-record-index-renew-add').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['customer/contract-record/renew-add']); ?>&contractId="+id);
    }
    //导出
    CustomerContractRecordIndex.exportWidthCondition = function(){
        var form = $('#search-form-customer-contract-record-index');
        window.open("<?= yii::$app->urlManager->createUrl(['customer/contract-record/export-width-condition']); ?>&"+form.serialize());
    }
    //删除合同
    CustomerContractRecordIndex.remove = function(){
        var selectRow = this.getSelected();
        if(!selectRow)  return false;
        id = selectRow.id;
        $.messager.confirm('删除确认','您确定要删除所选的合同？',function(r){
            if(r){
                $.ajax({
                    type:'get',
                    url: "<?= yii::$app->urlManager->createUrl(['customer/contract-record/remove']); ?>",
                    data: {'id': id},
                    dataType: 'json',
                    success: function(data){
                        if(data.status){
                            $.messager.alert('删除成功',data.info,'info');
                            $('#easyui-datagrid-customer-contract-record-index').datagrid('reload');
                        }else{
                            $.messager.alert('删除失败',data.info,'error');
                        }
                    }
                });
            }
        });
    }

    

    //查询列表
    var searchForm = $('#search-form-customer-contract-record-index');
        /**查询表单提交事件**/
        searchForm.submit(function(){
            var data = {};
            var searchCondition = $(this).serializeArray();
            
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = searchCondition[i]['value'];
            }
            $('#easyui-datagrid-customer-contract-record-index').datagrid('load',data);
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
    searchForm.find('input[name=source]').combobox({
            valueField:'value',
            textField:'text',
            editable: false,
            panelHeight:'auto',
            data: [{"value": '',"text": '不限'},{"value": '1',"text": '400呼叫中心'},{"value": '2',"text": '地推'},{"value": '3',"text": '大客户导入'},{"value": '4',"text": '自主开发'},{"value": '5',"text": '转介绍'},{"value": '6',"text": '活动促销'},{"value": '7',"text": '其他'}],
            onSelect: function(){
                searchForm.submit();
            }
        });
    searchForm.find('input[name=second_contract_type]').combobox({
            valueField:'value',
            textField:'text',
            editable: false,
            panelHeight:'auto',
            data: [{"value": '',"text": '不限'},{"value": '长租',"text": '长租'},{"value": '以租代售',"text": '以租代售'},{"value": '分时租赁',"text": '分时租赁'},{"value": '短租',"text": '短租'},{"value": '店配',"text": '店配'},{"value": '宅配',"text": '宅配'},{"value": '调拨转运',"text": '调拨转运'},{"value": '接驳运输',"text": '接驳运输'},{"value": '收派',"text": '收派'}],
            onSelect: function(){
                searchForm.submit();
            }
        });
/*
                    <li>
                        <div class="item-name">合同类型</div>
                        <div class="item-input">
                           <!-- <input class="easyui-textbox" type="text" align:"center" name="contract_type" style="width:100%;" /> -->
                            <input id="contract_type_id" style="width:200px;" name="contract_type" />
                        </div>
                    </li>*/

    $('#contract_type_id2').combobox({

            onChange:function(newValue,oldValue){
               //alert(0)
                    var data = 0;
                    if(newValue == '租赁'){
                        var html = "<li>\
                        <div class='item-input'><select id='second_contract_type_id2' name='second_contract_type' style='width:160px;' class='easyui-combobox' align:'center' ><option value=''>不限</option><option>长租</option><option>以租代售</option><option>分时租赁</option><option>短租</option></select>\
                        </div></li>";
                    }
                    if(newValue == '自运营'){
                        var html = "<li class='ulforform-resizeable-group'>\
                        <div class='ulforform-resizeable-input'><select id='second_contract_type_id2' name='second_contract_type' style='width:160px;' class='easyui-combobox' align:'center' ><option value=''>不限</option><option>店配</option><option>宅配</option><option>调拨转运</option><option>接驳运输</option><option>收派</option></select>\
                        </div></li>";
                    }
                    
                    $("#tip2").html(html);
                     //$("#contract_type_id").parent().parent().after(html);
               /* var datax,json;
                datax = [];
                datax.push({ "text": "测试", "id": 100 });*/
                //$("#user_id"+data).combobox("loadData", datax);

                $("#second_contract_type_id2").combobox({
                    data:<?=json_encode($users)?>,
                    valueField:'id',
                    textField:'name',
                    /*onSelect: function () {
                        changeValue(data);
                    } */
                });
                
                //$("#site_tel"+data).textbox();

         }
    })
    
    

    //查询
    CustomerContractRecordIndex.search = function(){
        var form = $('#search-form-customer-contract-record-index');
        var data = {};
        var searchCondition = form.serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        $('#easyui-datagrid-customer-contract-record-index').datagrid('load',data);
    }
    //重置
    CustomerContractRecordIndex.reset = function(){
        $('#search-form-customer-contract-record-index').form('reset');
    }
    //执行
    CustomerContractRecordIndex.init();
</script>