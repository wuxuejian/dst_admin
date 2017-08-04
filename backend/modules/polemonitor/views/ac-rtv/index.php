<table id="polemonitorAcRtvIndex_datagrid"></table> 
<div id="polemonitorAcRtvIndex_datagridToolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="polemonitorAcRtvIndex_searchForm">
                <ul class="search-main">
                    <li>
                        <div class="item-name">选择电桩</div>
                        <div class="item-input">
                            <select
                                class="easyui-combogrid"
                                name="chargerId"
                                style="width:100%;"
                                data-options="
                                    panelWidth: 420,
                                    panelHeight: 200,
                                    delay: 800,
                                    mode:'remote',
                                    idField: 'id',
                                    textField: 'code_from_compony',
                                    value:<?= $defaultChargerId; ?>,
                                    url: '<?= yii::$app->urlManager->createUrl(['polemonitor/combogrid/get-charger-list']); ?>',
                                    method: 'get',
                                    scrollbarSize:0,
                                    pagination: true,
                                    pageSize: 10,
                                    pageList: [10,20,30],
                                    fitColumns: true,
                                    rownumbers: true,
                                    columns: [[
                                        {field:'id',title:'ID',width:40,hidden:true},
                                        {field:'code_from_compony',title:'电桩编号',align:'center',width:90},
                                        {field:'logic_addr',title:'逻辑地址',align:'center',width:90},
                                        {field:'cs_name',title:'电站名称',halign:'center',width:250}
                                    ]],
                                    onHidePanel:function(){
                                        var _combogrid = $(this);
                                        var value = _combogrid.combogrid('getValue');
                                        var textbox = _combogrid.combogrid('textbox');
                                        var text = textbox.val();
                                        var rows = _combogrid.combogrid('grid').datagrid('getSelections');
                                        if(text && rows.length < 1 && value == text){
                                            $.messager.show(
                                                {
                                                    title: '无效值',
                                                    msg:'【' + text + '】不是有效值！请重新检索并选择一个电桩！'
                                                }
                                            );
                                            _combogrid.combogrid('clear');
                                        }else{
                                            polemonitorAcRtvIndex.search();
                                        }
                                    }
                                "
                            ></select>
                        </div>
                    </li>
                    <li class="search-button">
                        <a href="javascript:void(0)" onclick="polemonitorAcRtvIndex.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
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
        <a href="javascript:void(0)"  onclick="<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon']; ?>'"><?= $val['text']; ?></a>
        <?php } ?>
    </div>
    <?php } ?>
</div>
<script>
    var polemonitorAcRtvIndex = {
        init: function () {
            //获取列表数据
            $('#polemonitorAcRtvIndex_datagrid').datagrid({
                method: 'get',
                url: "<?php echo yii::$app->urlManager->createUrl(['polemonitor/ac-rtv/get-list']); ?>",
                fit: true,
                border: false,
                toolbar: "#polemonitorAcRtvIndex_datagridToolbar",
                pagination: true,
                loadMsg: '数据加载中...',
                striped: true,
                checkOnSelect: true,
                rownumbers: true,
                singleSelect: false,
                pageSize:20,
                frozenColumns: [[
                    {field: 'ck', checkbox: true},
                    {field: 'DEV_ID', title: '设备ID', width: 50,align: 'center', hidden: false},
                    {field: 'TIME_TAG', title: '数据时间', width: 140, align: 'center', sortable: true}
                ]],
                columns: [[
                    {field: 'DEV_ADDR', title: '逻辑地址', width: 80, align: 'center', sortable: true},
                    {field: 'INNER_ID', title: '数据测量点', width: 80, align: 'center', sortable: true},
                    {field: 'Ua', title: 'A相电压', width: 80, halign: 'center', align:'right', sortable: true},
                    {field: 'Ub', title: 'B相电压', width: 80, halign: 'center', align:'right', sortable: true},
                    {field: 'Uc', title: 'C相电压', width: 80, halign: 'center', align:'right', sortable: true},
                    {field: 'Ia', title: 'A相电流', width: 80, halign: 'center', align:'right', sortable: true},
                    {field: 'Ib', title: 'B相电流', width: 80, halign: 'center', align:'right', sortable: true},
                    {field: 'Ic', title: 'C相电流', width: 80, halign: 'center', align:'right', sortable: true},
                    {field: 'WRITE_TIME', title: '写库时间', width: 140, align: 'center', sortable: true}
                ]],
                onLoadSuccess: function(data){
                    if(data.errInfo){
                        $.messager.show({
                            title:'获取数据失败',
                            msg: '<span style="color:red;">' + data.errInfo + '</span>'
                        });
                    }
                }
            });
        },
        //获取当前选择的记录。参数all = true标示是否要返回所有被选择的记录
        getCurrentSelected: function (all){
            var datagrid = $('#polemonitorAcRtvIndex_datagrid');
            var selectRows = datagrid.datagrid('getSelections');
            if (selectRows.length <= 0) {
                $.messager.show({
                    title: '请选择',
                    msg: '请选择要操作的记录！'
                });
                return false;
            }
            if (all) {
                return selectRows;
            } else {
                return selectRow[0];
            }
        },
        //查询
        search: function () {
            var form = $('#polemonitorAcRtvIndex_searchForm');
            var data = {};
            var searchCondition = form.serializeArray();
            for (var i in searchCondition) {
                data[searchCondition[i]['name']] = searchCondition[i]['value'];
            }
            $('#polemonitorAcRtvIndex_datagrid').datagrid('load', data);
        }
    }

    // 执行初始化函数
    polemonitorAcRtvIndex.init();

</script>