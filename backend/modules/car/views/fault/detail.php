<div class="easyui-tabs" data-options="fit:true,border:false,tabWidth:150">
    <!--tab页签1-->
    <div title="故障基本信息">
        <form id="carFaultScanDetailsWin_form" method="post">
            <div class="easyui-panel" title="" style="padding:8px 0px;"
                 data-options="collapsible:true,collapsed:false,border:false,fit:false">
                <table cellpadding="6" cellspacing="6" style="width:95%;" border="0" align="center">
                    <tr hidden>
                        <th align="right">故障ID：</th>
                        <td colspan="5">
                            <?php echo $fault['id']; ?>
                        </td>
                    </tr>
                    <tr>
                        <th align="right" width="15%">故障车辆：</th>
                        <td width="20%">
                            <?php echo $fault['plate_number'] != '' ? $fault['plate_number'] : $fault['vehicle_dentification_number']; ?>
                        </td>
                        <th align="right"  width="15%">故障编号：</th>
                        <td>
                            <?php echo $fault['number']; ?>
                        </td>
                        <th align="right"  width="15%">当前状态：</th>
                        <td width="20%">
                            <?php echo $fault['fault_status']; ?>
                        </td>
                    </tr>
                    <tr>
                        <th align="right">故障发生时间：</th>
                        <td>
                            <?php echo $fault['f_datetime']; ?>
                        </td>
                        <th align="right">故障发生地点：</th>
                        <td colspan="3">
                            <?php echo $fault['f_place']; ?>
                        </td>
                    </tr>
                    <tr>
                        <th align="right">故障反馈人：</th>
                        <td>
                            <?php echo $fault['fb_name']; ?>
                        </td>
                        <th align="right" >联系电话：</th>
                        <td>
                            <?php echo $fault['fb_mobile']; ?>
                        </td>
                        <th align="right" >故障反馈时间：</th>
                        <td>
                            <?php echo $fault['fb_date']; ?>
                        </td>
                    </tr>
                    <tr>
                        <th align="right" valign="top">故障现象描述：</th>
                        <td colspan="5">
                            <div class="carFaultScanDetailsWin_showMultiLineTextArea">
                                <?php echo $fault['f_desc']; ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th align="right" valign="top">故障引发原因：</th>
                        <td colspan="5">
                            <div class="carFaultScanDetailsWin_showMultiLineTextArea">
                                <?php echo $fault['f_reason']; ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th align="right">本方初次受理人：</th>
                        <td>
                            <?php echo $fault['ap_name']; ?>
                        </td>
                        <th align="right" >故障上报时间：</th>
                        <td>
                            <?php echo $fault['report_date']; ?>
                        </td>
                        <th align="right" >预计完结时间：</th>
                        <td>
                            <?php echo $fault['expect_end_date']; ?>
                        </td>
                    </tr>
                    <tr>
                        <th align="right">故障负责人：</th>
                        <td>
                            <?php echo $fault['fzr_name']; ?>
                        </td>
                        <th align="right" >联系电话：</th>
                        <td>
                            <?php echo $fault['fzr_mobile']; ?>
                        </td>
                        <th align="right" >进厂维修单号：</th>
                        <td>
                            <?php echo $fault['repair_order_no']; ?>
                        </td>
                    </tr>
                    <tr>
                        <th align="right" valign="top">故障处理方法：</th>
                        <td colspan="5">
                            <div class="carFaultScanDetailsWin_showMultiLineTextArea">
                                <?php echo $fault['f_dispose']; ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th align="right" valign="top">故障照片：</th>
                        <td colspan="5">
                            <ul style="padding:0;margin:0;list-style:none;overflow:hidden;" id="car-fault-details-uploadfile">
                                <?php
                                    $thumbs = [
                                        ['thumb_plate_number','车牌照片'],
                                        ['thumb_meter','车辆仪表'],
                                        ['thumb_scene','故障现场照片'],
                                        ['thumb_place','故障位置照片'],
                                        ['thumb_fb','反馈人签名'],
                                        ['thumb_repair_order','进厂维修单']
                                    ];
                                    foreach($thumbs as $item){
                                ?>
                                <li style="float:left;margin-right:16px;position:relative;cursor:pointer">
                                    <div style="width:100px;height:100px;">
                                        <img class="faultImg" src="<?php echo $fault[$item[0]]!='' ? './uploads/image/fault/'.$fault[$item[0]] : './images/add.jpg'; ?>" width="100" height="100" />
                                    </div>
                                    <div class="imgTitle" style="position:absolute;bottom:0;left:0;background:rgba(224,236,255,0.5);width:100px;text-align:center;line-height:24px;"><?php echo $item[1]; ?></div>
                                </li>
                                <?php } ?>
                            </ul>
                        </td>
                    </tr>
                </table>
            </div>
        </form>
    </div>

    <!--tab页签2-->
    <div title="维修进度记录">    
        <table id="carFaultScanDetailsWin_datagrid"></table>
    </div>
</div>

<script>
    // 配置数据
    var carFaultScanDetailsWin_CONFIG = <?= json_encode($config); ?>;
    // 请求的URl
    var carFaultScanDetailsWin_URL_getList = "<?php echo yii::$app->urlManager->createUrl(['car/fault-dispose-progress/get-progress-list','faultId'=>$fault['id']]); ?>";

    var carFaultScanDetailsWin = {
        // 初始化函数
        init: function(){
            //--初始化维修进度列表
            $('#carFaultScanDetailsWin_datagrid').datagrid({
                method: 'get',
                url: carFaultScanDetailsWin_URL_getList,
                fit: true,
                border: false,
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

            // 放大显示上传图片
            $('#car-fault-details-uploadfile').children('li').each(function(){
                var imgSrc = $(this).find('img.faultImg').attr('src');
                if(imgSrc != './images/add.jpg') {
                    $(this).tooltip({
                        position: 'top',
                        content: '<img src="' + imgSrc + '" width="350px" height="350px" border="0" />'
                    });
                }
            });

            // 换行显示textarea内容
            $('.carFaultScanDetailsWin_showMultiLineTextArea').each(function(){
                var txtStr = $.trim($(this).html()).replace(/\n|\r\n/g,'<br/>');
                $(this).html(txtStr);
            });
        }
    }

    // 执行初始化函数
    carFaultScanDetailsWin.init();
</script>