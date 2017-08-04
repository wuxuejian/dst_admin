<table id="easyui-datagrid-customer-personal-index"></table> 
<div id="easyui-datagrid-customer-personal-index-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-customer-personal-index">
                <ul class="search-main">
                    <li>
                        <div class="item-name">姓名</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="id_name" style="width:100%;"
                                   data-options="
                                        onChange:function(){
                                            CustomerPersonalIndex.search();
                                        }
                                   "
                                />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">手机</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="mobile" style="width:100%;"
                                   data-options="
                                        onChange:function(){
                                            CustomerPersonalIndex.search();
                                        }
                                   "
                                />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">身份证号</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="id_number" style="width:100%;"
                                   data-options="
                                        onChange:function(){
                                            CustomerPersonalIndex.search();
                                        }
                                   "
                                />
                        </div>
                    </li>
                    <li class="search-button">
                        <a href="javascript:void(0)" onclick="CustomerPersonalIndex.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                        <a href="javascript:void(0)" onclick="CustomerPersonalIndex.reset()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</a>
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
        <a  href="javascript:void(0)"
            onclick="<?= $val['on_click']; ?>"
            class="easyui-linkbutton"
            data-options="iconCls:'<?= $val['icon'] ;?>'"
        ><?= $val['text'] ;?></a>
        <?php } ?>
    </div>
    <?php } ?>
</div>
<!-- 窗口 -->
<div id="easyui-dialog-customer-personal-index-add"></div>
<div id="easyui-dialog-customer-personal-index-edit"></div>
<!-- 窗口 -->
<script>
    var CustomerPersonalIndex = new Object();
    CustomerPersonalIndex.init = function(){
        //获取列表数据
        $('#easyui-datagrid-customer-personal-index').datagrid({  
            method: 'get', 
            url: "<?= yii::$app->urlManager->createUrl(['customer/personal/get-list']); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-customer-personal-index-toolbar",
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
                {field: 'id_name',title: '姓名',width: 80,align: 'center',sortable: true}
            ]],
            columns:[[
                {field: 'mobile',title: '手机',width: 100,align: 'center',sortable: true},
                {field: 'id_number',title: '身份证号',width: 130,align: 'center',sortable: true},
                {field: 'id_sex',title: '性别',width: 40,align: 'left',align: 'center',
                    sortable: true,
                    formatter: function(value){
                        if(value) return '男';
                        return '女';
                    }
                },
                {field: 'id_address',title: '住址',width: 180,halign: 'center',sortable: true},
                {field: 'telephone',title: '固定电话',width: 100,align: 'center',sortable: true},
                {field: 'qq',title: 'QQ',width: 100,align: 'center',sortable: true},
                {field: 'email',title: '邮件',width: 100,align: 'center',sortable: true},
                {field: 'driving_number',title: '驾驶证号',width: 120,halign: 'center',sortable: true},
                {field: 'driving_addr',title: '驾驶证登记地址',width: 180,halign: 'center',sortable: true},
                {field: 'driving_issue_date',title: '初次领证日期',width: 90,align: 'center',
                    sortable: true,
                    formatter: function(value){
                        if(!isNaN(value) && value > 0) return formatDateToString(value);
                    }
                },
                {field: 'driving_class',title: '准驾车型',width: 90,align: 'center',sortable: true},
                {field: 'driving_valid_from',title: '有效起始日期',width: 90,align: 'center',
                    sortable: true,
                    formatter: function(value){
                        if(!isNaN(value) && value > 0) return formatDateToString(value);
                    }
                },
                {field: 'driving_valid_for',title: '有效期限',width: 90,align: 'center',
                    sortable: true,
                    formatter: function(value){
                        return value + '年';
                    }
                },
                {field: 'driving_issue_authority',title: '发证机关',width: 90,align: 'center',sortable: true},
                {field: 'operating_company',title: '所属运营公司',width: 170,halign: 'center',sortable: true}
            ]],
            onDblClickRow: function(rowIndex,rowData){
                CustomerPersonalIndex.edit(rowData.id);
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
        $('#easyui-dialog-customer-personal-index-add').dialog({
            title: '添加个人客户',   
            width: '820px',   
            height: '500px',   
            closed: true,   
            cache: true,   
            modal: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var form = $('#easyui-form-customer-personal-add');
                    if(!form.form('validate')){
                        return false;
                    }
                    var data = form.serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['customer/personal/add']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('添加成功',data.info,'info');
                                $('#easyui-dialog-customer-personal-index-add').dialog('close');
                                $('#easyui-datagrid-customer-personal-index').datagrid('reload');
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
                    $('#easyui-dialog-customer-personal-index-add').dialog('close');
                }
            }],
            onClose:function(){
                $(this).dialog('clear');
            }  
        });
        //初始化修改窗口
        $('#easyui-dialog-customer-personal-index-edit').dialog({
            title: '修改客户信息',   
            width: '820px',   
            height: '500px',   
            closed: true,   
            cache: true,   
            modal: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler:function(){
                    var form = $('#easyui-form-customer-personal-edit');
                    if(!form.form('validate')){
                        return false;
                    }
                    var data = form.serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['customer/personal/edit']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('修改成功',data.info,'info');
                                $('#easyui-dialog-customer-personal-index-edit').dialog('close');
                                $('#easyui-datagrid-customer-personal-index').datagrid('reload');
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
                    $('#easyui-dialog-customer-personal-index-edit').dialog('close');
                }
            }] 
        });
    }
    CustomerPersonalIndex.init();
    //获取选择的记录
    CustomerPersonalIndex.getSelected = function(){
        var datagrid = $('#easyui-datagrid-customer-personal-index');
        var selectRow = datagrid.datagrid('getSelected');
        if(!selectRow){
            $.messager.alert('错误','请选择要操作的记录','error');   
            return false;
        }
        return selectRow;
    }
    //添加方法
    CustomerPersonalIndex.add = function(){
        $('#easyui-dialog-customer-personal-index-add').dialog('open');
        $('#easyui-dialog-customer-personal-index-add').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['customer/personal/add']); ?>");
    }
    //修改
    CustomerPersonalIndex.edit = function(id){
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
        $('#easyui-dialog-customer-personal-index-edit').dialog('open');
        $('#easyui-dialog-customer-personal-index-edit').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['customer/personal/edit']); ?>&id="+id);
    }
    //删除
    CustomerPersonalIndex.remove = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var id = selectRow.id;
        $.messager.confirm('确定删除','您确定要删除该客户？',function(r){
            if(r){
                $.ajax({
                    type: 'get',
                    url: "<?php echo yii::$app->urlManager->createUrl(['customer/personal/remove']); ?>",
                    data: {id: id},
                    dataType: 'json',
                    success: function(data){
                        if(data.status){
                            $.messager.alert('删除成功',data.info,'info');   
                            $('#easyui-datagrid-customer-personal-index').datagrid('reload');
                        }else{
                            $.messager.alert('删除失败',data.info,'error');   
                        }
                    }
                });
            }
        });
    }
    //条件导出
    CustomerPersonalIndex.exportWidthCondition = function(){
        var form = $('#search-form-customer-personal-index');
        window.open("<?php echo yii::$app->urlManager->createUrl(['customer/personal/export-width-condition']); ?>&"+form.serialize());
    }
	
	//在地图上显示
	CustomerPersonalIndex.showOnMap = function(){
		var grid = $('#easyui-datagrid-customer-personal-index');
		if(grid.datagrid('getData').total < 1){
			$.messager.alert('警告','还没有任何数据！','warning');  
			return false; 
		}
		var _title = '地图标注-个人客户';
		//在新tab里显示
		if($('#easyui_tabs_index_index_main').tabs('exists',_title)){
			$('#easyui_tabs_index_index_main').tabs('select',_title);
			return;
		}
        var form = $('#search-form-customer-personal-index');
        var _href = "<?php echo yii::$app->urlManager->createUrl(['customer/personal/show-on-map']); ?>" +'&'+form.serialize();
        $('#easyui_tabs_index_index_main').tabs('add',{
            title: _title,
            content: '<iframe scrolling="no" frameborder="0" src="' + _href + '" style="width:100%;height:100%;"></iframe>',
            closable: true,
            fit: true
        });
    }
	
    //查询
    CustomerPersonalIndex.search = function(){
        var form = $('#search-form-customer-personal-index');
        var data = {};
        var searchCondition = form.serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        $('#easyui-datagrid-customer-personal-index').datagrid('load',data);
    }
    //重置
    CustomerPersonalIndex.reset = function(){
        $('#search-form-customer-personal-index').form('reset');
    }

</script>