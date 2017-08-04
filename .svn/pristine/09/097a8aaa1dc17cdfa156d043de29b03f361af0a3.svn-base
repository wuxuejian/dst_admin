<table id="polemonitorDcRsIndex_datagrid"></table> 
<div id="polemonitorDcRsIndex_datagridToolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="polemonitorDcRsIndex_searchForm">
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
                                            polemonitorDcRsIndex.search();
                                        }
                                    }
                                "
                            ></select>
                        </div>
                    </li>
                    <li class="search-button">
                        <a href="javascript:void(0)" onclick="polemonitorDcRsIndex.search()"class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
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
        <a href="javascript:void(0)" onclick="<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon']; ?>'"><?= $val['text']; ?></a>
        <?php } ?>
    </div>
    <?php } ?>
</div>
<script>
    var polemonitorDcRsIndex = {
        init: function () {
            //获取列表数据
            $('#polemonitorDcRsIndex_datagrid').datagrid({
                method: 'get',
                url: "<?php echo yii::$app->urlManager->createUrl(['polemonitor/dc-rs/get-list']); ?>",
                fit: true,
                border: false,
                toolbar: "#polemonitorDcRsIndex_datagridToolbar",
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
                    {field: 'INNER_ID', title: '充电机在充电设备内部编号', width: 90, align: 'center', sortable: true},
                    {field: 'BATTERY_STATUS', title: '蓄电池组状态', width: 90, align: 'center', sortable: true
                        ,formatter: function(value){
                            return value == 0 ? '没充满' : '充满了';
                        }
                    },
                    {field: 'BATTERY_FAILURE_CODE', title: '蓄电池组故障代码', width: 100, align: 'center', sortable: true
                        ,formatter: function(value){
                            switch(value){
                                case 0: return '正常'; break;
                                case 1: return '电压过高'; break;
                                case 2: return '电压过低'; break;
                                case 3: return '荷电状态SOC过高'; break;
                                case 4: return '荷电状态SOC过低'; break;
                                case 5: return '充电过流'; break;
                                case 6: return '温度过高'; break;
                                case 7: return '均衡故障'; break;
                                case 8: return '匹配故障'; break;
                                case 9: return '绝缘状态异常'; break;
                                case 10: return '高压连接状态异常'; break;
                                default: return value;
                            }
                        }
                    },
                    {field: 'DCM_STATUS', title: '直流充电机状态', width: 100, align: 'center', sortable: true
                        ,formatter: function(value){
                            return value == 0 ? '待机' : '充电';
                        }
                    },
                    {field: 'DCM_FAILURE_CODE', title: '直流充电机故障代码', width: 100, align: 'center', sortable: true
                        ,formatter: function(value){
                            return value == 0 ? '通讯正常' : '通讯异常';
                        }
                    },
                    {field: 'DCM_AC_STATUS', title: '直流充电机交流侧开关状态', width: 100, align: 'center', sortable: true
                        ,formatter: function(value){
                            return value == 0 ? '关' : '开';
                        }
                    },
                    {field: 'DCM_DC_STATUS', title: '充电机直流侧开关状态', width: 100, align: 'center', sortable: true
                        ,formatter: function(value){
                            return value == 0 ? '关' : '开';
                        }
                    },
                    {field: 'DCM_DC_FUSE', title: '充电机直流侧开关跳闸/熔断器熔断', width: 100, align: 'center', sortable: true
                        ,formatter: function(value){
                            return value == 0 ? '正常' : '跳闸/熔断';
                        }
                    },
                    
					{field: 'MU_FAILURE', title: '监控单元故障', width: 100, align: 'center', sortable: true
                        ,formatter: function(value){
                            return value == 0 ? '正常' : '故障';
                        }
                    },
                    {field: 'MU_COMMINTERRUPT', title: '监控单元与站内监控系统通讯中断', width: 100, align: 'center', sortable: true
                        ,formatter: function(value){
                            return value == 0 ? '正常' : '通讯中断';
                        }
                    },
                    {field: 'CM_STATUS', title: '充电机开/关机', width: 100, align: 'center', sortable: true
                        ,formatter: function(value){
                            return value == 0 ? '关机' : '开机';
                        }
                    },
                    {field: 'CM_EMERGE_STOP', title: '充电机紧急停机', width: 100, align: 'center', sortable: true
                        ,formatter: function(value){
                            return value == 0 ? '无紧急停机' : '紧急停机';
                        }
                    },
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
            var datagrid = $('#polemonitorDcRsIndex_datagrid');
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
            var form = $('#polemonitorDcRsIndex_searchForm');
            var data = {};
            var searchCondition = form.serializeArray();
            for (var i in searchCondition) {
                data[searchCondition[i]['name']] = $.trim(searchCondition[i]['value']);
            }
            $('#polemonitorDcRsIndex_datagrid').datagrid('load', data);
        }
    }

    // 执行初始化函数
    polemonitorDcRsIndex.init();

</script>