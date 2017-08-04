<table id="easyui-datagrid-customer-company-sms-notify-log-index"></table> 
<div id="easyui-datagrid-customer-company-sms-notify-log-index-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-customer-company-sms-notify-log-index">
                <ul class="search-main">
                    <li>
                        <div class="item-name">客户名称</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="company_name" style="width:100%;" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">客户号</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="company_number" style="width:100%;" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">发送日期</div>
                        <div class="item-input">
							<input class="easyui-datebox" type="text" name="start_send_time" style="width:90px;"
                                   data-options="
                                    onChange:function(){
                                        CustomerCompanySmsNotifyLogIndex.search();
                                    }
                                "
                            />
                            -
							<input class="easyui-datebox" type="text" name="end_send_time" style="width:90px;"
                                   data-options="
                                    onChange:function(){
                                        CustomerCompanySmsNotifyLogIndex.search();
                                    }
                                "
                            />
                        </div>               
                    </li>
                    <li class="search-button">
                        <a onclick="javascript:CustomerCompanySmsNotifyLogIndex.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                        <a onclick="javascript:CustomerCompanySmsNotifyLogIndex.reset()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</a>
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
<div id="easyui-dialog-customer-company-sms-notify-log-index-edit"></div>
<!-- 窗口 -->
<script>
    var CustomerCompanySmsNotifyLogIndex = new Object();
    CustomerCompanySmsNotifyLogIndex.init = function(){
        //获取列表数据
        $('#easyui-datagrid-customer-company-sms-notify-log-index').datagrid({  
            method: 'get', 
            url:"<?php echo yii::$app->urlManager->createUrl(['customer/company-sms-notify-log/get-list']); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-customer-company-sms-notify-log-index-toolbar",
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
                {field: 'company_number',title: '客户号',align:'center',width: 120,sortable: true},
                {field: 'company_name',title: '客户名称',width: 200,align:'center',sortable: true}
            ]],
            columns:[[
				{field: 'car_num',title: '租车数量',width: 60,align:'center',sortable: true},
				{field: 'amount',title: '下月应缴租金',width: 100,align:'center',sortable: true},
				{field: 'delivery_time',title: '交租时间',width: 100,align:'center',sortable: true},
				{field: 'keeper_name',title: '管理人姓名',width: 120,align:'center',sortable: true},
				{field: 'keeper_mobile',title: '管理人手机',width: 100,align:'center',sortable: true},
				{field: 'send_time',title: '发送时间',width: 120,align:'center',sortable: true},
				{field: 'oper_user',title: '操作人',width: 100,align:'center',sortable: true}
            ]],
            //双击
            onDblClickRow: function(rowIndex,rowData){
                //CustomerCompanySmsNotifyLogIndex.edit(rowData.id);
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

      	//构建查询表单
        var searchForm = $('#search-form-customer-company-sms-notify-log-index');
        searchForm.find('input[name=company_name]').textbox({
            onChange: function(){
            	CustomerCompanySmsNotifyLogIndex.search();
            }
        });
        searchForm.find('input[name=company_number]').textbox({
            onChange: function(){
            	CustomerCompanySmsNotifyLogIndex.search();
            }
        });
    }
    //获取选择的记录
    CustomerCompanySmsNotifyLogIndex.getSelected = function(){
        var datagrid = $('#easyui-datagrid-customer-company-sms-notify-log-index');
        var selectRow = datagrid.datagrid('getSelected');
        if(!selectRow){
            $.messager.alert('错误','请选择要操作的记录','error');   
            return false;
        }
        return selectRow;
    }
    //查询
    CustomerCompanySmsNotifyLogIndex.search = function(){
        var form = $('#search-form-customer-company-sms-notify-log-index');
        var data = {};
        var searchCondition = form.serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        $('#easyui-datagrid-customer-company-sms-notify-log-index').datagrid('load',data);
    }
 	 //条件导出
    CustomerCompanySmsNotifyLogIndex.exportWidthCondition = function(){
        var form = $('#search-form-customer-company-sms-notify-log-index');
        window.open("<?php echo yii::$app->urlManager->createUrl(['customer/company-sms-notify-log/export-width-condition']); ?>&"+form.serialize());
    }
    //重置
    CustomerCompanySmsNotifyLogIndex.reset = function(){
        $('#search-form-customer-company-sms-notify-log-index').form('reset');
    }
    //执行
    CustomerCompanySmsNotifyLogIndex.init();
</script>