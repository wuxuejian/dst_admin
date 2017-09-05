<div class="data-search-form">
    <form id="search-form-parts-in-add">
        <ul class="search-main">
            <li>
                <div class="item-name">配件编码</div>
                <div class="item-input">
                    <input class="easyui-textbox" name="parts_code" style="width:150px;">
                </div>
            </li>
            <li>
                <div class="item-name">配件名称</div>
                <div class="item-input">
                    <input class="easyui-textbox" name="parts_name" style="width:150px;">
                </div>
            </li>
            <li>
                <div class="item-name">适用车型</div>
                <div class="item-input">
                    <input class="easyui-textbox" name="car_type" style="width:150px;">
                </div>
            </li>
             <li class="search-button">
                        <a onclick="PartsInstockAdd.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                        <!-- <button type="submit" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button> -->
                        <button type="submit" onclick="PartsInstockAdd.resetForm()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
             </li>
        </ul>
        <div class="easyui-panel" title="配件信息" style="padding:0px 4px;" data-options="
        iconCls: 'icon-tip',
        border: false
    ">
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
            singleSelect: false,
            columns:[[
                {field: 'ck',checkbox: true},
                {field: 'id',hidden:true},
                {field: 'parts_code',title: '配件编码',width: '100'},
                {field: 'parts_name',title: '配件名称',width: '100'},
                {field: 'size',title: '规格',width: '100'},
                {field: 'shop_price',title: '采购指导价',width: '100'},
                {field: 'car_type',title: '适用车型',width: '100'},
                {field: 'unit',title: '单位',width: 50},
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

    var searchForm = $('#search-form-parts-in-add');
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

    //重置查询表单
    PartsInstockAdd.resetForm = function(){
        var easyuiForm = $('#search-form-parts-in-add');
        easyuiForm.form('reset');
        var data = {};
        $('#easyui-datagrid-parts-parts-instock-add').datagrid('load',data);
    }

    //查询表单提交事件
    //条件搜索查询
    PartsInstockAdd.search = function(){
        var form = $('#search-form-parts-in-add');
        var data = {};
        var searchCondition = form.serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        $('#easyui-datagrid-parts-parts-instock-add').datagrid('load',data);
    }
</script>
