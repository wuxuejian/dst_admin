<table id="easyui-datagrid-customer-company-index"></table> 
<div id="easyui-datagrid-customer-company-index-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-customer-company-index">
                <ul class="search-main">
                    <li>
                        <div class="item-name">公司名称</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="company_name" style="width:100%;"
                                   data-options="
                                        onChange:function(){
                                            CustomerCompanyIndex.search();
                                        }
                                   "
                                />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">营业执照注册号</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="regNumber" style="width:100%;"
                                   data-options="
                                        onChange:function(){
                                            CustomerCompanyIndex.search();
                                        }
                                   "
                                />
                        </div>
                    </li>
                    <li class="search-button">
                        <a href="javascript:void(0)" onclick="CustomerCompanyIndex.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                        <a href="javascript:void(0)" onclick="CustomerCompanyIndex.reset()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</a>
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
        <a   href="javascript:void(0)"
             onclick="<?= $val['on_click']; ?>"
             class="easyui-linkbutton"
             data-options="iconCls:'<?= $val['icon'] ;?>'"
        ><?= $val['text'] ;?></a>
        <?php } ?>
    </div>
    <?php } ?>
</div>
<!-- 窗口 -->
<div id="easyui-dialog-customer-company-index-add"></div>
<div id="easyui-dialog-customer-company-index-edit"></div>
<div id="easyui-dialog-customer-company-index-setPassword"></div>
<div id="easyui-dialog-customer-company-index-relation-vip"></div>
<!-- 窗口 -->
<script>
    var CustomerCompanyIndex = new Object();
    CustomerCompanyIndex.init = function(){
        //获取列表数据
        $('#easyui-datagrid-customer-company-index').datagrid({  
            method: 'get', 
            url: "<?= yii::$app->urlManager->createUrl(['customer/company/get-list']); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-customer-company-index-toolbar",
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
                {field: 'number',title: '客户号',width: 120,align: 'center',sortable: true},
                {field: 'company_name',title: '公司名称',width: 180,halign: 'center',sortable: true}
            ]],
            columns:[[
                {field: 'reg_number',title: '营业执照注册号',width: 120,halign: 'center',sortable: true},
                {field: 'company_addr',title: '公司地址',width: 230,halign: 'center',sortable: true},
                {field: 'contact_name',title: '联系人姓名',width: 80,align: 'center',sortable: true},
                {field: 'contact_mobile',title: '联系人手机',width: 80,align: 'center'},
                {field: 'director_name',title: '负责人姓名',width: 80,align: 'center',sortable: true},
                {field: 'director_mobile',title: '负责人手机',width: 80,align: 'center'},
                {field: 'keeper_name',title: '车管负责人姓名',width: 80,align: 'center',sortable: true},
                {field: 'keeper_mobile',title: '车管负责人手机',width: 80,align: 'center'},
                {field: 'corporate_name',title: '法人姓名',width: 80,align: 'center',sortable: true},
                {field: 'corporate_mobile',title: '法人手机',width: 80,align: 'center'},
                {field: 'company_brief',title: '公司简介',width: 200,halign: 'center'},
                {field: 'operating_company',title: '所属运营公司',width: 170,halign: 'center',sortable: true},
                {field: 'type',title: '客户类型',width: 170,halign: 'center',sortable: true,
                	formatter: function(value){
                		var types = ["", '渠道', "大客户", 'B端网点']; 
                    	return types[value];
                    }
                }
                
            ]],
            onDblClickRow: function(rowIndex,rowData){
                CustomerCompanyIndex.edit(rowData.id);
            },
            onLoadSuccess: function (data) {
                //单元格内容悬浮提示，doCellTip()是在入口文件index.php中拓展的。
                $(this).datagrid('doCellTip', {
                    position: 'bottom',
                    maxWidth: '200px',
                    onlyShowInterrupt: true, //false时所有单元格都显示提示；true时配合specialShowFields自定义要提示的列
                    specialShowFields: [     //需要提示的列
                        //{field: 'company_addr', showField: 'company_addr'}
                    ],
                    tipStyler: {
                        backgroundColor: '#E4F0FC',
                        borderColor: '#87A9D0',
                        boxShadow: '1px 1px 3px #292929'
                    }
                });
            }
        });
        //初始化添加窗口
        $('#easyui-dialog-customer-company-index-add').dialog({
            title: '添加企业客户',   
            width: '900px',   
            height: '500px',   
            closed: true,   
            cache: true,   
            modal: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var form = $('#easyui-form-customer-company-add');
                    if(!form.form('validate')){
                        return false;
                    }
                    var data = form.serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['customer/company/add']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('添加成功',data.info,'info');
                                $('#easyui-dialog-customer-company-index-add').dialog('close');
                                $('#easyui-datagrid-customer-company-index').datagrid('reload');
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
                    $('#easyui-dialog-customer-company-index-add').dialog('close');
                }
            }],
            onClose:function(){
                $(this).dialog('clear');
            }  
        });
        //初始化修改窗口
        $('#easyui-dialog-customer-company-index-edit').dialog({
            title: '修改客户信息',   
            width: '900px',   
            height: '500px',   
            closed: true,   
            cache: true,   
            modal: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var form = $('#easyui-form-customer-company-edit');
                    if(!form.form('validate')){
                        return false;
                    }
                    var data = form.serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['customer/company/edit']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('修改成功',data.info,'info');
                                $('#easyui-dialog-customer-company-index-edit').dialog('close');
                                $('#easyui-datagrid-customer-company-index').datagrid('reload');
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
                    $('#easyui-dialog-customer-company-index-edit').dialog('close');
                }
            }] 
        });
        // 初始化设置密码窗口
        $('#easyui-dialog-customer-company-index-setPassword').dialog({
            title:'设置密码',iconCls:'icon-group-key',
            height:200,width:400,
            modal:true,
            closed:true,
            onClose: function(){
                $(this).dialog('clear');
            },
            buttons:[{
                text:'确定',
                iconCls:'icon-ok',
                handler: function(){
                    var form = $('#CustomerCompanyIndex_setPasswordWin_form');
                    if(!form.form('validate')){
                        return false;
                    }
                    var data = form.serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['customer/company/set-password']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('设置密码成功',data.info,'info');
                                $('#easyui-dialog-customer-company-index-setPassword').dialog('close');
                                $('#easyui-datagrid-customer-company-index').datagrid('reload');
                            }else{
                                $.messager.alert('设置密码失败',data.info,'error');
                            }
                        }
                    });
                }
            },{
                text:'取消',
                iconCls:'icon-cancel',
                handler: function(){
                    $('#easyui-dialog-customer-company-index-setPassword').dialog('close');
                }
            }]
        });
        //初始化关联vip窗口
        $('#easyui-dialog-customer-company-index-relation-vip').dialog({
            title: '关联会员',
            iconCls: 'icon-search',
            width: 400,   
            height: 160,   
            closed: true,   
            cache: true,   
            modal: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var form = $('#easyui-form-customer-company-relation-vip');
                    if(!form.form('validate')){
                        return false;
                    }
                    var data = form.serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['customer/company/relation-vip']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('关联成功',data.info,'info');
                                $('#easyui-dialog-customer-company-index-relation-vip').dialog('close');
                                $('#easyui-datagrid-customer-company-index').datagrid('reload');
                            }else{
                                $.messager.alert('关联失败',data.info,'error');
                            }
                        }
                    });
                }
            },{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui-dialog-customer-company-index-relation-vip').dialog('close');
                }
            }] 
        });
    }
    CustomerCompanyIndex.init();
    //获取选择的记录
    CustomerCompanyIndex.getSelected = function(){
        var datagrid = $('#easyui-datagrid-customer-company-index');
        var selectRow = datagrid.datagrid('getSelected');
        if(!selectRow){
            $.messager.alert('错误','请选择要操作的记录','error');   
            return false;
        }
        return selectRow;
    }
    //添加方法
    CustomerCompanyIndex.add = function(){
        $('#easyui-dialog-customer-company-index-add').dialog('open');
        $('#easyui-dialog-customer-company-index-add').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['customer/company/add']); ?>");
    }
    //修改
    CustomerCompanyIndex.edit = function(id){
        if(!id){
            var selectRow = this.getSelected();
            if(!selectRow){
                return false;
            }
            id = selectRow.id;
        }
        if(!id){
            return false;
        }
        $('#easyui-dialog-customer-company-index-edit').dialog('open');
        $('#easyui-dialog-customer-company-index-edit').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['customer/company/edit']); ?>&id="+id);
    }
    //删除
    CustomerCompanyIndex.remove = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var id = selectRow.id;
        $.messager.confirm('确定删除','您确定要删除该客户？',function(r){
            if(r){
                $.ajax({
                    type: 'get',
                    url: "<?php echo yii::$app->urlManager->createUrl(['customer/company/remove']); ?>",
                    data: {id: id},
                    dataType: 'json',
                    success: function(data){
                        if(data.status){
                            $.messager.alert('删除成功',data.info,'info');   
                            $('#easyui-datagrid-customer-company-index').datagrid('reload');
                        }else{
                            $.messager.alert('删除失败',data.info,'error');   
                        }
                    }
                });
            }
        });
    }
	
    //条件导出
    CustomerCompanyIndex.exportWidthCondition = function(){
        var form = $('#search-form-customer-company-index');
        window.open("<?php echo yii::$app->urlManager->createUrl(['customer/company/export-width-condition']); ?>&"+form.serialize());
    }	
	
	//在地图上显示
	CustomerCompanyIndex.showOnMap = function(){
		var grid = $('#easyui-datagrid-customer-company-index');
		if(grid.datagrid('getData').total < 1){
			$.messager.alert('警告','还没有任何数据！','warning');  
			return false; 
		}
		var _title = '地图标注-企业客户';
		//在新tab里显示
		if($('#easyui_tabs_index_index_main').tabs('exists',_title)){
			$('#easyui_tabs_index_index_main').tabs('select',_title);
			return;
		}
        var form = $('#search-form-customer-company-index');
        var _href = "<?php echo yii::$app->urlManager->createUrl(['customer/company/show-on-map']); ?>" +'&'+form.serialize();
        $('#easyui_tabs_index_index_main').tabs('add',{
            title: _title,
            content: '<iframe scrolling="no" frameborder="0" src="' + _href + '" style="width:100%;height:100%;"></iframe>',
            closable: true,
            fit: true
        });
    }

    //设置客户密码
    CustomerCompanyIndex.setPassword = function() {
        var selectRow = this.getSelected();
        if (!selectRow) {
            return false;
        }
        var id = selectRow.id;
        var _url = "<?php echo yii::$app->urlManager->createUrl(['customer/company/set-password']); ?>&customerId=" + id;
        $('#easyui-dialog-customer-company-index-setPassword')
            .dialog('open')
            .dialog('refresh',_url);
    }

    //关联vip
    CustomerCompanyIndex.relationVip = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        id = selectRow.id;
        var easyuiDialog = $('#easyui-dialog-customer-company-index-relation-vip');
        easyuiDialog.dialog('open');
        easyuiDialog.dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['customer/company/relation-vip']); ?>&id="+id);
    }
    //解除vip关联
    CustomerCompanyIndex.relieveVip = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        id = selectRow.id;
        $.messager.confirm('操作确认','您确定要执行解除操作？',function(r){
            if(r){
                $.ajax({
                    type: 'get',
                    url: "<?php echo yii::$app->urlManager->createUrl(['customer/company/relieve-vip']); ?>",
                    "data": {'id': id},
                    "dataType": "json",
                    "success": function(rData){
                        if(rData.status){
                            $.messager.alert('操作成功',rData.info,'info');   
                            $('#easyui-datagrid-customer-company-index').datagrid('reload');
                        }else{
                            $.messager.alert('操作失败',rData.info,'error');   
                        }
                    }
                });
            }
        }); 
    }
	
    //查询
    CustomerCompanyIndex.search = function(){
        var form = $('#search-form-customer-company-index');
        var data = {};
        var searchCondition = form.serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = $.trim(searchCondition[i]['value']);
        }
        $('#easyui-datagrid-customer-company-index').datagrid('load',data);
    }
    //重置
    CustomerCompanyIndex.reset = function(){
        $('#search-form-customer-company-index').form('reset');
    }
</script>