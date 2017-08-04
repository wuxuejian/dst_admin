<table id="carFaultDisposeWin_datagrid"></table>
<div id="carFaultDisposeWin_datagridToolbar">
    <div class="easyui-panel" style="padding:10px 20px" data-options="border:false">
        <form id="carFaultDisposeWin_form">
            <table cellpadding="5" cellspacing="0" border="0" width="80%" >
                <tr hidden>
                    <td align="right">故障ID</td>
                    <td colspan="5"><?php echo $fault['id']; ?></td>
                </tr>
                <tr>
                    <td align="right">故障车辆：</td>
                    <td ><?php echo $fault['plate_number'] != '' ? $fault['plate_number'] : $fault['vehicle_dentification_number']; ?></td>
                    <td align="right">故障编号：</td>
                    <td><?php echo $fault['number']; ?></td>
                    <td align="right">当前状态：</td>
                    <td><?php echo $config['fault_status'][$fault['fault_status']]['text']; ?></td>
                </tr>
            </table>
        </form>
    </div>
    <div class="easyui-panel" title="维修进度" style="padding:3px 2px;width:100%;"
         data-options="
            iconCls: 'icon-table-list',
            border: false
         "
    >
        <a href="javascript:carFaultDisposeWin.add()" class="easyui-linkbutton" data-options="iconCls:'icon-add'">新增进度</a>
        <a href="javascript:carFaultDisposeWin.edit()" class="easyui-linkbutton" data-options="iconCls:'icon-edit'">修改进度</a>
        <a href="javascript:carFaultDisposeWin.remove()" class="easyui-linkbutton" data-options="iconCls:'icon-remove'">删除进度</a>
    </div>

</div>

<div id="carFaultDisposeWin_addEditWin"></div>

<script type="text/javascript">
    // 配置数据
    var carFaultDisposeWin_CONFIG = <?= json_encode($config); ?>;
    // 请求的url
    var carFaultDisposeWin_URL_getList = "<?php echo yii::$app->urlManager->createUrl(['car/fault-dispose-progress/get-progress-list','faultId'=>$fault['id']]); ?>";
    var carFaultDisposeWin_URL_add = "<?php echo yii::$app->urlManager->createUrl(['car/fault-dispose-progress/add','faultId'=>$fault['id']]); ?>";
    var carFaultDisposeWin_URL_edit = "<?php echo yii::$app->urlManager->createUrl(['car/fault-dispose-progress/edit','faultId'=>$fault['id']]); ?>";
    var carFaultDisposeWin_URL_remove = "<?php echo yii::$app->urlManager->createUrl(['car/fault-dispose-progress/remove']); ?>";

    var carFaultDisposeWin = {
        //初始化
        init: function(){
            //--初始化维修进度列表
            $('#carFaultDisposeWin_datagrid').datagrid({
                method: 'get',
                url: carFaultDisposeWin_URL_getList,
                fit: true,
                border: false,
                toolbar: "#carFaultDisposeWin_datagridToolbar",
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
                    {field: 'fault_id',title: '所属故障ID',width: 80,align: 'center',sortable: true,hidden: true},
                    {field: 'disposer',title: '受理人',width: 80,align: 'center',sortable: true}
                ]],
                columns:[[
                    {field: 'dispose_date',title: '受理日期',width: 80,align: 'center',sortable: true},
                    {field: 'fault_status',title: '故障状态',width: 80,align: 'center',sortable: true,
                        formatter: function (value, row, index) {
                            try {
                                var str = 'carFaultDisposeWin_CONFIG.fault_status.' + value + '.text';
                                switch (value) {
                                    case 'RECEIVED':
                                        return '<span style="background-color:#D3D3D3;color:#fff;padding:2px 5px;">' + eval(str) + '</span>';
                                    case 'SENT':
                                        return '<span style="background-color:#FFA0A0;color:#fff;padding:2px 5px;">' + eval(str) + '</span>';
                                    case 'REPAIRING':
                                        return '<span style="background-color:#FFCC01;color:#fff;padding:2px 5px;">' + eval(str) + '</span>';
                                    case 'PROCESSED':
                                        return '<span style="background-color:#05CD69;color:#fff;padding:2px 5px;">' + eval(str) + '</span>';
                                    default:
                                        return value;
                                }
                            } catch (e) {
                                return value;
                            }
                        }
                    },
                    {field: 'progress_desc',title: '进度描述',width: 450,halign: 'center',sortable: true},
                    {field: 'create_time',title: '记录时间',width: 130,align: 'center',sortable: true},
                    {field: 'username',title: '记录人员',width: 100,align: 'center',sortable: true}
                ]],
                onDblClickRow: function (rowIndex, rowData) {
                    carFaultDisposeWin.edit(rowData.id);
                },
                onLoadSuccess: function (data) {
                    //单元格内容悬浮提示，doCellTip()是在入口文件index.php中拓展的。
                    $(this).datagrid('doCellTip', {
                        position: 'bottom',
                        maxWidth: '300px',
                        onlyShowInterrupt: true, //false时所有单元格都显示提示；true时配合specialShowFields自定义要提示的列
                        specialShowFields: [     //需要提示的列
                            //{field: 'sketch', showField: 'sketch'}
                        ],
                        tipStyler: {
                            backgroundColor: '#E4F0FC',
                            borderColor: '#87A9D0',
                            boxShadow: '1px 1px 3px #292929'
                        }
                    });
                }
            });
            //--初始化【新增/修改】窗口
            $('#carFaultDisposeWin_addEditWin').dialog({
                title: '新增/修改维修进度',
                width: 800,
                height: 200,
                closed: true,
                cache: true,
                modal: true,
                maximizable: false,
                resizable: false,
                onClose: function () {
                    $(this).dialog('clear');
                },
                buttons: [{
                    text: '确定',
                    iconCls: 'icon-ok',
                    handler: function () {
                        var form = $('#carFaultDisposeWin_addEditWin_form');
                        var id = $('input[name="id"]',form)[0].value; // 按进度id判断是新增还是修改。
                        var _url = carFaultDisposeWin_URL_add;
                        if(parseInt(id) > 0) _url = carFaultDisposeWin_URL_edit;
                        form.form('submit', {
                            url: _url,
                            onSubmit: function(){
                                if(!$(this).form('validate')){
                                    $.messager.show({
                                        title: '表单验证不通过',
                                        msg: '请检查表单是否填写完整或填写错误！'
                                    });
                                    return false;
                                }
                            },
                            success: function(data){
                                // change JSON string to js object
                                var data = eval('(' + data + ')');
                                if(data.status){
                                    $.messager.show({
                                        title: '保存成功',
                                        msg: data.info
                                    });
                                    $('#carFaultDisposeWin_addEditWin').dialog('close');
                                    $('#carFaultDisposeWin_datagrid').datagrid('reload');
                                    $('#easyui-datagrid-car-fault-all-index').datagrid('reload');
                                }else{
                                    $.messager.alert('错误',data.info,'error');
                                }
                            }
                        });
                    }
                }, {
                    text: '取消',
                    iconCls: 'icon-cancel',
                    handler: function () {
                        $('#carFaultDisposeWin_addEditWin').dialog('close');
                    }
                }]
            });
        },
        // 获取当前所选择的记录
        getCurrentSelected: function(multiline){
            var datagrid = $('#carFaultDisposeWin_datagrid');
            var selectRows = datagrid.datagrid('getSelections');
            if(selectRows.length <= 0){
                $.messager.show({
                    title: '请选择',
                    msg: '请先选择要操作的记录！'
                });
                return false;
            }
            if(multiline){
                return selectRows;
            }else{
                if(selectRows.length > 1){
                    $.messager.show({
                        title: '提醒',
                        msg: '该功能不能批量操作！<br/>如果你选择了多条记录，则默认操作的是第一条记录！'
                    });
                }
                return selectRows[0];
            }
        },
        // 新增进度
        add: function(){
            $('#carFaultDisposeWin_addEditWin')
                .dialog('open')
                .dialog('refresh',carFaultDisposeWin_URL_add)
                .dialog('setTitle','新增维修进度');
        },
        // 修改进度
        edit: function(id){
            var progress_id = id || (this.getCurrentSelected()).id;
            if(!progress_id) return false;
            $('#carFaultDisposeWin_addEditWin')
                .dialog('open')
                .dialog('refresh', carFaultDisposeWin_URL_edit + '&progress_id=' + progress_id)
                .dialog('setTitle', '修改维修进度');
        },
        // 删除进度
        remove: function(){
            var selectRow = this.getCurrentSelected();
            if(!selectRow) return false;
            var progress_id = selectRow.id;
            $.messager.confirm('确定删除','您确定要删除所选行吗？',function(r){
                if(r){
                    $.ajax({
                        type: 'get',
                        url: carFaultDisposeWin_URL_remove,
                        data: {progress_id: progress_id},
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.show({
                                    title: '删除成功',
                                    msg: data.info
                                });
                                $('#carFaultDisposeWin_datagrid').datagrid('reload');
                            }else{
                                $.messager.alert('删除失败',data.info,'error');
                            }
                        }
                    });
                }
            });
        }
    }
    // 执行初始化函数
    carFaultDisposeWin.init();

</script>