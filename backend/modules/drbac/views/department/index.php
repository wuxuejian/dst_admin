<table id="easyui-datagrid-drbac-department-index"></table> 
<div id="easyui-datagrid-drbac-department-index-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-drbac-department-index">
                <ul class="search-main">
                	<li>
                        <div class="item-name">运营公司</div>
                        <div class="item-input">
                            <input class="easyui-textbox" name="operating_company_id" style="width:200px;"></input>
                        </div>
                    </li>
                    <!--  <li>
                        <div class="item-name">部门名称</div>
                        <div class="item-input">
                            <input class="easyui-textbox" name="name" style="width:150px;"></input>
                        </div>
                    </li>-->
                    <li class="search-button">
                        <button class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button onclick="DrbacDepartmentIndex.resetForm();" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
                    </li>
                </ul>
            </form>
        </div>
    </div>
    <div class="easyui-panel" title="部门列表" style="width:100%" data-options="
        iconCls: 'icon-tip',
        border: false
    ">
        <?php if($buttons){ ?>
        <div style="padding:8px 4px;">
            <?php foreach($buttons as $val){ ?>
            <a
                onclick="<?= $val['on_click']; ?>"
                class="easyui-linkbutton"
                data-options="iconCls:'<?= $val['icon'] ;?>'"
            ><?= $val['text'] ;?></a>
            <?php } ?>
        </div>
        <?php } ?>
    </div>
</div>
<!-- 窗口 -->
<div id="easyui-dialog-drbac-department-index-add"></div>
<div id="easyui-dialog-drbac-department-index-edit"></div>
<!-- 窗口 -->
<script>
    var DrbacDepartmentIndex = new Object();
    DrbacDepartmentIndex.init = function(){
        //获取列表数据
     //   $('#easyui-datagrid-drbac-department-index').datagrid({  
     //       method: 'get', 
     //       url:"<?php //echo yii::$app->urlManager->createUrl(['drbac/department/get-department-list']); ?>",   
     //       fit: true,
     //       border: false,
     //       toolbar: "#easyui-datagrid-drbac-department-index-toolbar",
     //       pagination: true,
     //       loadMsg: '数据加载中...',
     //       striped: true,
     //       checkOnSelect: true,
    //        rownumbers: true,
     //       singleSelect: true,
    //        frozenColumns: [[
    //            {field: 'ck',checkbox: true}, 
    //            {field: 'id',title: 'id',hidden: true},    
    //            {field: 'name',title: '部门名称',width: 160,sortable: true}
    //        ]],
    //        columns:[[
    //            {field: 'note',title: '备注',width: 400,align: 'left'}
    //        ]]   
    //    });
    
    
    	 $('#easyui-datagrid-drbac-department-index').treegrid({
                idField: 'id',
                treeField: 'name',
                method: 'post',
                url: "<?= yii::$app->urlManager->createUrl(['drbac/department/index']); ?>",
                toolbar: "#easyui-datagrid-drbac-department-index-toolbar",
                fit: true,
                border: false,
                pagination: false,
                loadMsg: '数据加载中...',
                striped: true,
                checkOnSelect: true,
                rownumbers: true,
                singleSelect: true,
                pageSize: 50,
                frozenColumns: [[
                    {field: 'ck', checkbox: true},
                    {field: 'id',title: 'ID', hidden: true},
                ]],
                columns: [[
                    {field: 'name', title: '部门名称', width: 480, halign: 'center', sortable: true},
                    {field: 'note', title: '备注', width: 400, halign: 'left', sortable: true},
                ]]
            });
       
    }
    DrbacDepartmentIndex.init();

    //初始化添加窗口
    $('#easyui-dialog-drbac-department-index-add').dialog({
        title: '添加新部门', 
        width: 600,
        height: 230,
        cache: true,   
        modal: true,
        closed: true,
        resizable:true,
        maximizable: true,
        buttons: [{
            text:'确定',
            iconCls:'icon-ok',
            handler:function(){
                var form = $('#easyui-form-drbac-department-add');
                if(!form.form('validate')){
                    return false;
                }
                var data = form.serialize();
                $.ajax({
                    type: 'post',
                    url: "<?php echo yii::$app->urlManager->createUrl(['drbac/department/add']); ?>",
                    data: data,
                    dataType: 'json',
                    success: function(data){
                        if(data.status){
                            $.messager.alert('添加成功',data.info,'info');
                            $('#easyui-dialog-drbac-department-index-add').dialog('close');
                            $('#easyui-datagrid-drbac-department-index').treegrid('reload');
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
                $('#easyui-dialog-drbac-department-index-add').dialog('close');
            }
        }]
    });
    //初始化修改窗口
    $('#easyui-dialog-drbac-department-index-edit').dialog({
        title: '修改部门信息',   
        width: 600,   
        height: 230,   
        closed: true,   
        cache: true,   
        modal: true,
        resizable:true,
        maximizable: true,
        buttons: [{
            text:'确定',
            iconCls:'icon-ok',
            handler:function(){
                var form = $('#easyui-form-drbac-department-edit');
                if(!form.form('validate')){
                    return false;
                }
                var data = form.serialize();
                $.ajax({
                    type: 'post',
                    url: "<?php echo yii::$app->urlManager->createUrl(['drbac/department/edit']); ?>",
                    data: data,
                    dataType: 'json',
                    success: function(data){
                        if(data.status){
                            $.messager.alert('修改成功',data.info,'info');
                            $('#easyui-dialog-drbac-department-index-edit').dialog('close');
                            $('#easyui-datagrid-drbac-department-index').treegrid('reload');
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
                $('#easyui-dialog-drbac-department-index-edit').dialog('close');
            }
        }]
    });
    
    //获取选择的记录
    DrbacDepartmentIndex.getSelected = function(){
        var datagrid = $('#easyui-datagrid-drbac-department-index');
        var selectRow = datagrid.treegrid('getSelected');
        if(!selectRow){
            $.messager.alert('错误','请选择要操作的记录','error');   
            return false;
        }
        return selectRow.id;
    }
    //添加方法
    DrbacDepartmentIndex.add = function(){
        $('#easyui-dialog-drbac-department-index-add').dialog('open');
        $('#easyui-dialog-drbac-department-index-add').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['drbac/department/add']); ?>");
    }
    //修改
    DrbacDepartmentIndex.edit = function(id){
        if(!id){
            id = this.getSelected();
        }
        if(!id){
            return;
        }
        $('#easyui-dialog-drbac-department-index-edit').dialog('open');
        $('#easyui-dialog-drbac-department-index-edit').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['drbac/department/edit']); ?>&id="+id);
    }
    //删除部门
    DrbacDepartmentIndex.remove = function(){
        var id = this.getSelected();
        if(!id){
            return;
        }
        $.messager.confirm('删除确认','删除该部门，同时会删除该部门下的所有子部门，您确定要删除该部门？',function(r){
            if(r){
                $.ajax({
                    type: 'get',
                    url: "<?= yii::$app->urlManager->createUrl(['drbac/department/remove']); ?>",
                    data: {"id": id},
                    dataType: 'json',
                    success: function(data){
                        if(data.status){
                            $('#easyui-datagrid-drbac-department-index').treegrid('reload');
                            $.messager.alert('删除成功',data.info,'info');
                        }else{
                            $.messager.alert('删除失败',data.info,'error');
                        }
                    }
                });
            }
        });
        
    }

 	 //查询表单构建
    var searchForm = $('#search-form-drbac-department-index');
    searchForm.submit(function(){
        var data = {};
        var searchCondition = $(this).serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        $('#easyui-datagrid-drbac-department-index').treegrid('load',data);
        return false;
    });
    searchForm.find('input[name=operating_company_id]').combobox({
    	valueField:'value',
        textField:'text',
        data: <?php echo json_encode($searchFormOptions['operating_company_id']); ?>,
        editable: false,
        panelHeight:'auto',
        onSelect: function(){
            searchForm.submit();
        }
    });

    //重置查询表单
    DrbacDepartmentIndex.resetForm = function(){
        var easyuiForm = $('#search-form-drbac-department-index');
        easyuiForm.form('reset');
    }
</script>