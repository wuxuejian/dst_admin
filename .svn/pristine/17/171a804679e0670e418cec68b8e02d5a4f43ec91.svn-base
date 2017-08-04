<table id="carmonitorBatteryMaintainIndex_noticeCorrectWin_datagrid"></table>
<div id="carmonitorBatteryMaintainIndex_noticeCorrectWin_datagridToolbar">
    <div class="easyui-panel" title="车辆信息" style="width:100%;padding-bottom:5px;" data-options="iconCls: 'icon-search',border: false">
        <table cellpadding="5" cellspacing="2" width="100%" border="0">
            <tr>
                <td align="right" width="13%">车牌号：</td>
                <td width="20%"><?php echo $carInfo['plate_number']; ?></td>
                <td align="right" width="13%">车架号：</td>
                <td width="20%"><?php echo $carInfo['car_vin']; ?></td>
                <td align="right" width="10%">车辆状态：</td>
                <td><?php echo $carInfo['car_status']; ?></td>
            </tr>
            <tr>
                <td align="right">客户名称：</td>
                <td><?php echo $carInfo['customer']; ?></td>
                <td align="right">联系人：</td>
                <td><?php echo $carInfo['contact_name']; ?></td>
                <td align="right">联系电话：</td>
                <td><?php echo $carInfo['contact_mobile']; ?></td>
            </tr>
        </table>
    </div>
    <?php if(isset($buttons) && !empty($buttons)){ ?>
        <div class="easyui-panel" title="通知记录" style="padding:3px 2px;width:100%;" data-options="
            iconCls: 'icon-table-list',
            border: false
        ">
            <?php foreach($buttons as $val){ ?>
                <a href="javascript:void(0)" onclick="<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon'] ;?>'"><?= $val['text'] ;?></a>
            <?php } ?>
        </div>
    <?php } ?>
</div>

<!-- 窗口 begin -->
<div id="carmonitorBatteryMaintainIndex_noticeCorrectWin_addNoticeWin"></div>
<div id="carmonitorBatteryMaintainIndex_noticeCorrectWin_editNoticeWin"></div>
<!-- 窗口 end -->

<script>
    var carmonitorBatteryMaintainIndex_noticeCorrectWin = {
        params:{
            'URL': {
                'getNoticeList': '<?php echo yii::$app->urlManager->createUrl(['carmonitor/battery-maintain/get-notice-list']); ?>',
                'saveNotice': '<?php echo yii::$app->urlManager->createUrl(['carmonitor/battery-maintain/save-notice']); ?>'
            }
        },
        //初始化
        init: function() {
            //列表
            $('#carmonitorBatteryMaintainIndex_noticeCorrectWin_datagrid').datagrid({
                method: 'get',
                url: carmonitorBatteryMaintainIndex_noticeCorrectWin.params.URL.getNoticeList,
                queryParams:{'car_vin': '<?php echo $carInfo['car_vin']; ?>'},
                fit: true,
                border: false,
                toolbar: "#carmonitorBatteryMaintainIndex_noticeCorrectWin_datagridToolbar",
                pagination: true,
                loadMsg: '数据加载中...',
                striped: true,
                checkOnSelect: true,
                rownumbers: true,
                singleSelect: true,
                pageSize: 20,
                frozenColumns: [[
                    {field: 'ck', checkbox: true},
                    {field: 'id', title: 'ID', width: 40, align: 'center', hidden: true},
                    {field: 'notice_time', title: '通知时间', width: 100, align: 'center', sortable: true,
                        editor: {
                            type:'datebox',options:{required:true,validType:'date'}
                        }
                    }
                ]],
                columns: [[
                    {field: 'notice_sender', title: '受理人', align: 'center', width: 100, sortable: true,
                        editor: {
                            type:'textbox',options:{required:true}
                        }
                    },
                    {field: 'contact_name', title: '联系人', align: 'center', width: 100, sortable: true,
                        editor: {
                            type:'textbox',options:{required:true}
                        }
                    },
                    {field: 'mark', title: '备注', halign: 'center', width: 300, sortable: true,
                        editor: {
                            type:'textbox'
                        }
                    },
                    {field: 'is_corrected', title: '执行慢充修正', align: 'center', width: 90, sortable: true,
                        formatter: function(value){
                            return parseInt(value)>0 ? '是' : '否';
                        }
                        /*,editor: {
                             type:'checkbox',
                             options:{
                                 on: 1,
                                 off: 0
                             }
                         }*/
                    },
                    {field: 'correct_res', title: '修正结果', align: 'center', width: 120, sortable: true,
                        formatter: function(value){
                            if(value){
                                return value;
                            }else{
                                return '—';
                            }
                        }
                    }
                ]]
            });
        },
        //新增
        addNotice: function(){
            var datagrid = $('#carmonitorBatteryMaintainIndex_noticeCorrectWin_datagrid');
            var curRow = datagrid.datagrid('getSelected');
            if(curRow){
                var curRowIndex = datagrid.datagrid('getRowIndex',curRow);
                if(datagrid.datagrid('validateRow',curRowIndex)){
                    datagrid.datagrid('endEdit',curRowIndex);
                }else{
                    return false;
                }
            }
            datagrid.datagrid('insertRow',{
                    index:0,
                    row: {
                        'notice_time': '<?php echo date('Y-m-d'); ?>',
                        'notice_sender': '',
                        'contact_name': '',
                        'mark': '',
                        'is_corrected': 0,
                        'correct_res': ''
                    }
                })
                .datagrid('selectRow',0)  // 选中第一行
                .datagrid('beginEdit',0); // 打开行编辑器
        },
        //修改
        editNotice: function(){
            var datagrid = $('#carmonitorBatteryMaintainIndex_noticeCorrectWin_datagrid');
            var curRow = datagrid.datagrid('getSelected');
            var curRowIndex = datagrid.datagrid('getRowIndex',curRow);
            datagrid.datagrid('beginEdit',curRowIndex); // 打开行编辑器
        },
        //删除
        removeNotice: function(){
            var datagrid = $('#carmonitorBatteryMaintainIndex_noticeCorrectWin_datagrid');
            var curRow = datagrid.datagrid('getSelected');
            var curRowIndex = datagrid.datagrid('getRowIndex',curRow);
            datagrid.datagrid('deleteRow',curRowIndex);
        },
        //保存
        saveNotice: function(){
            var datagrid = $('#carmonitorBatteryMaintainIndex_noticeCorrectWin_datagrid');
            var rows = datagrid.datagrid('getRows');
            if(!rows.length){
                return false;
            }
            var isAllowed = true;
            $.each(rows, function(i,row){
                var index = datagrid.datagrid('getRowIndex',row);
                if(!datagrid.datagrid('validateRow',index)){ //行验证
                    $.messager.show({
                        title: '验证不合法',
                        msg: '列表【第'+(index+1)+'行】校验不合法！'
                    });
                    isAllowed = false;
                    return false;  //注意：这里仅退出each循环，但代码还会往下执行！
                }else{
                    datagrid.datagrid('endEdit',index); //关闭行编辑器
                }
            });
            if(isAllowed){
                //获取发生改变的行
                var insertRows = datagrid.datagrid('getChanges','inserted');
                var updateRows = datagrid.datagrid('getChanges','updated');
                var deleteRows = datagrid.datagrid('getChanges','deleted');
                if(!insertRows.length && !updateRows.length && !deleteRows.length){
                    $.messager.show({
                        title: '无需保存',
                        msg: '列表未发生任何改变，无需保存！'
                    });
                    return false;
                }
                $.messager.confirm('请确认','您确定要保存列表数据吗？',function(r){
                    if(r){
                        $.ajax({
                            type: 'post',
                            url: carmonitorBatteryMaintainIndex_noticeCorrectWin.params.URL.saveNotice,
                            data: {
                                'car_vin': '<?php echo $carInfo['car_vin']; ?>',
                                'insertRows': insertRows,
                                'updateRows' : updateRows,
                                'deleteRows' : deleteRows
                            },
                            dataType: 'json',
                            success: function(data){
                                if(data.status){
                                    $.messager.show({
                                        title: '操作成功',
                                        msg: data.info
                                    });
                                    $('#carmonitorBatteryMaintainIndex_noticeCorrectWin_datagrid').datagrid('reload');
                                    $('#carmonitorBatteryMaintainIndex_datagrid').datagrid('reload');
                                }else{
                                    $.messager.show({
                                        title: '操作失败',
                                        msg: data.info
                                    });
                                }
                            }
                        });
                    }
                });
            }
        }
    }

    // 执行初始化函数
    carmonitorBatteryMaintainIndex_noticeCorrectWin.init();

</script>