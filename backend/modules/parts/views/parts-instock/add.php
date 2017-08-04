<div class="data-search-form">
    <form id="search-form-drbac-mca-index">
        <ul class="search-main">
            <li>
                <div class="item-name">车辆品牌</div>
                <div class="item-input">
                    <input class="easyui-combotree" name="car_brand" style="width:150px;"></input>
                </div>
            </li>
            <li>
                <div class="item-name">配件类别</div>
                <div class="item-input">
                    <select
                            class="easyui-combobox"
                            style="width:150px;"
                            id="parts_type_add"
                            name="parts_type"
                            editable="true"
                            listHeight="200px"
                    >
                        <option value=" ">请选择</option>
                        <?php foreach($searchFormOptions['parts_type'] as $val){?>
                            <option value="<?php echo $val['id']; ?>"><?php echo $val['parts_name']; ?></option>
                        <?php }?>
                    </select>
                </div>
            </li>
            <li>
                <div class="item-name">配件种类</div>
                <div class="item-input">
                    <select
                            class="easyui-combobox"
                            style="width:150px;"
                            id="parts_kind_add"
                            name="parts_kind"
                            editable="true"
                            data-options="panelHeight:'auto'"
                    >
                    </select>
                </div>
            </li>
            <li>
                <div class="item-name">配件名称</div>
                <div class="item-input">
                    <input class="easyui-textbox" name="parts_name" style="width:150px;"></input>
                </div>
            </li>
            <li>
                <div class="item-name">配件品牌</div>
                <div class="item-input">
                    <input class="easyui-textbox" name="parts_brand" style="width:150px;"></input>
                </div>
            </li>
            <li>
                <div class="item-name">厂家配件编码</div>
                <div class="item-input">
                    <input class="easyui-textbox" name="vender_code" style="width:150px;"></input>
                </div>
            </li>
            <li>
                <div class="item-name">我方配件编码</div>
                <div class="item-input">
                    <input class="easyui-textbox" name="dst_code" style="width:150px;"></input>
                </div>
            </li>
            <!-- <li class="search-button"> -->
                <!-- <a onclick="DrbacMcaIndex.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a> -->
           <!--      <button type="submit" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                <button type="submit" onclick="PartsInstockAdd.resetForm()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
            </li> -->
             <li class="search-button">
                        <a onclick="PartsInstockAdd.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                        <!-- <button type="submit" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button> -->
                        <button type="submit" onclick="PartsInstockAdd.resetForm()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
             </li>
        </ul>
        <div class="easyui-panel" title="配件信息" style="padding:8px 4px;" data-options="
        iconCls: 'icon-tip',
        border: false
    ">
            <a onclick="PartsInstockAdd.addParts()" class="easyui-linkbutton" data-options="iconCls:'icon-add'">添加</a>
        </div>
    </form>
</div>

<table id="easyui-datagrid-parts-parts-instock-add"></table>
<!-- 后面的数据通过添加到此表单提交 -->
<form id="feng_all_data">
    <div style="display:none" id="feng_parts_add"></div>
</form>
<!--窗口中的窗口-->
<div id="easyui-dialog-parts-parts-instack-index-add-detail"></div>
<script>
    var PartsInstockAdd = new Object();
    PartsInstockAdd.init = function(){
        //初始化datagrid
        $('#easyui-datagrid-parts-parts-instock-add').datagrid({
            method: 'get',
            url:'<?php echo yii::$app->urlManager->createUrl(['parts/parts-info/get-list']); ?>',
//            fit: true,
            height:'auto',
            width:'auto',
            border: false,
            toolbar: "#easyui-datagrid-parts-parts-instock-add-index-toolbar",
            pagination: true,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
            columns:[[
                {field: 'ck',checkbox: true},
                {field: 'car_brand',title: '车辆品牌',width: '100'},
                {field: 'parents_name',title: '配件类别',width: '100'},
                {field: 'son_name',title: '配件种类',width: '100'},
                {field: 'parts_name',title: '配件名称',width: '100'},
                {field: 'parts_brand',title: '配件品牌',width: '100'},
                {field: 'vender_code',title: '厂家配件编码',width: '100'},
                {field: 'dst_code',title: '我方配件编码',width: '100'},
                {field: 'unit',title: '单位',width: 50},
                {field: 'main_engine_price',title: '主机厂参考价',width: '100'}
            ]]
        });
    }
    PartsInstockAdd.init();
    //获取选中记录
    PartsInstockAdd.getSelectedRow = function(){
        var datagrid = $('#easyui-datagrid-parts-parts-instock-add');
        var selectedRow = datagrid.datagrid('getSelected');
        if(!selectedRow){
            $.messager.alert('错误','请选择要操作的记录！','error');
            return false;
        }
        return selectedRow;
    }

    //添加配件
    PartsInstockAdd.addParts = function(){
        var datagrid = $('#easyui-datagrid-parts-parts-instock-add');
        var partsData = datagrid.datagrid('getSelected');
        if(partsData == null){
            $.messager.alert('错误','请选择配件项！','error');
            return false;
        }
        var id = partsData.parts_id;
        $('#easyui-dialog-parts-parts-instack-index-add-part').dialog('open');
        $('#easyui-dialog-parts-parts-instack-index-add-part').dialog('refresh','<?php echo yii::$app->urlManager->createUrl(['parts/parts-instock/add-part']); ?>&parts_id='+id);
    }

    var searchForm = $('#search-form-drbac-mca-index');
    /**查询表单提交事件**/
    searchForm.submit(function(){
        var data = {};
        var searchCondition = $(this).serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        $('#easyui-datagrid-parts-parts-instock-two').datagrid('load',data);
        return false;
    });
    //车辆品牌下拉
   /* searchForm.find('input[name=car_brand]').combotree({
        editable: false,
        panelHeight:'auto',
        lines:false,
        onChange: function(o){
            searchForm.submit();
        }
    });*/
    //配件类别下拉
//    searchForm.find('input[name=parts_type]').combobox({
//        valueField:'value',
//        textField:'text',
//        data: <?//= json_encode($searchFormOptions['part_type']); ?>//,
//        editable: true,
//        panelHeight:'auto',
//        onSelect: function(){
//            searchForm.submit();
//        }
//    });
    searchForm.find('input[name=car_brand]').combotree({
            url: "<?php echo yii::$app->urlManager->createUrl(['car/combotree/get-car-brands']); ?>",
            editable: true,
            panelHeight:'auto',
            lines:false,
            onChange: function(o){
                searchForm.submit();
            }
        });
   

//    searchForm.find('input[name=parts_kind]').combobox({
//        valueField:'value',
//        textField:'text',
//        data: <?//= json_encode($searchFormOptions['part_kind']); ?>//,
//        editable: true,
//        panelHeight:'500',
//        onSelect: function(){
//            searchForm.submit();
//        }
//    });
//    searchForm.find('input[name=parts_name]').combobox({
//        valueField:'value',
//        textField:'text',
//        data: <?//= json_encode($searchFormOptions['parts_name']); ?>//,
//        editable: true,
//        panelHeight:'auto',
//        onSelect: function(){
//            searchForm.submit();
//        }
//    });
    //重置查询表单
    PartsInstockAdd.resetForm = function(){
        var easyuiForm = $('#search-form-drbac-mca-index');
        easyuiForm.form('reset');
        var data = {};
        $('#easyui-datagrid-parts-parts-instock-add').datagrid('load',data);
    }

    //查询表单提交事件
    //条件搜索查询
    PartsInstockAdd.search = function(){
        var form = $('#search-form-drbac-mca-index');
        var data = {};
        var searchCondition = form.serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        $('#easyui-datagrid-parts-parts-instock-add').datagrid('load',data);
    }
</script>
<script>
    //二级联动
    $('#parts_type_add').combobox({
        onChange: function (n,o) {
            var id = $('#parts_type_add').combobox('getValue');
            $.ajax({
                async: false,
                url:'<?php echo yii::$app->urlManager->createUrl(['parts/parts-instock/get-kind']); ?>',
                type:'post',
                data:{'id':id},
                dataType:'json',
                success:function(data){
//                    $('#parts_kind').combobox('clear');
                    $('#parts_kind_add').combobox({
                        valueField:'value',
                        textField:'text',
                        editable: false,
                        panelHeight:'auto',
                        data: data
                    });
                    $('#parts_kind_add').combobox('setValues','');
                }
            });
        }
    });
</script>
