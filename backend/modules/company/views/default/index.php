<!DOCTYPE html>
<html>
    <meta charset="utf-8">
    <title>订单查询</title>
    <link rel="stylesheet" type="text/css" href="css/company/es_mod.css" />
     <script type="text/javascript" src="js/company/es_mod.js"></script>

</head>
    <?
    function getPay($p_status){
        if($p_status==0){
            echo '未支付';
        }elseif($p_status==1){
            echo '付款中';
        }elseif($p_status==2){
            echo '已支付';
        }
    }
    ?>
<body class="easyui-layout">
<div data-options="region:'center',title:'商户管理中心'" style="width:100%;height:100%" >
    <div class="es_mains" >
        <div class="es_main1">
            <div class="es_moddiv1">
                <div class="es_dbegin es_bder">
                    <img src="images/company/wjx.png" />
                    <span class="s_font">最近订单</span>
                    <span class="disstyle" id="disstyle1"></span>
                </div>
                   <div style="height:286px;" id="default_order_list1">
                        <div class="tabs_order">
                            <ul id="tabs">
                                <li class="tab-nav-action">印前</li>
                                <li class="tab-nav">印中</li>
                                <li class="tab-nav">印后</li>
                            </ul>
                        </div>
                        <div id="tabs-body" class="tabs-body">
                            <div style="display:block">
                                <table class="es_modtables"  border="0" cellpadding="3" cellspacing="1" width="100%">
                                    <thead>
                                    <tr>
                                        <th width="6%">No</th>
                                        <th width="26%">订单编号</th>
                                        <th width="18%">订单名称</th>
                                        <th width="18%">支付状态</th>
                                        <th width="32%">订单金额</th>
                                    </tr>
                                    </thead>
                                    <?$a=0;
                                    foreach($datay[1] as $orders){

                                        $a++;
                                        ?>
                                        <tr><td><?=$a?></td>
                                            <td><?=$orders['order_sn']?></td>
                                            <td><?=$orders['order_title']?></td>
                                            <td><?getPay($orders['pay_status'])?></td>
                                            <td><?=$orders['order_amount']?></td>
                                        </tr>
                                   <? }?>


                                </table>
                            </div>
                            <div style="display:none">
                                <table class="es_modtables"  border="0" cellpadding="3" cellspacing="1" width="100%">
                                    <thead>
                                    <tr>
                                        <th width="6%">No</th>
                                        <th width="26%">订单编号</th>
                                        <th width="18%">订单名称</th>
                                        <th width="18%">支付状态</th>
                                        <th width="32%">订单金额</th>
                                    </tr>
                                    </thead>
                                    <?$s=0;
                                    foreach($datay[2] as $orders){

                                        $s++;
                                        ?>
                                        <tr><td><?=$s?></td>
                                            <td><?=$orders['order_sn']?></td>
                                            <td><?=$orders['order_title']?></td>
                                            <td><?getPay($orders['pay_status'])?></td>
                                            <td><?=$orders['order_amount']?></td>
                                        </tr>
                                    <? }?>


                                </table>
                            </div>
                            <div style="display:none">
                                <table class="es_modtables" border="0" cellpadding="3" cellspacing="1" width="100%">
                                    <thead>
                                    <tr>
                                        <th width="6%">No</th>
                                        <th width="26%">订单编号</th>
                                        <th width="18%">订单名称</th>
                                        <th width="18%">支付状态</th>
                                        <th width="32%">订单金额</th>
                                    </tr>
                                    </thead>
                                    <?$d=0;
                                    foreach($datay[3] as $orders){

                                        $d++;
                                        ?>
                                        <tr><td><?=$d?></td>
                                            <td><?=$orders['order_sn']?></td>
                                            <td><?=$orders['order_title']?></td>
                                            <td><?getPay($orders['pay_status'])?></td>
                                            <td><?=$orders['order_amount']?></td>
                                        </tr>
                                    <? }?>


                                </table>
                            </div>

                        </div>
                   </div>
            </div>

            <div class="es_moddiv1">
                <div class="es_dbegin es_bder">
                    <img src="images/company/wjx.png" />
                    <span class="s_font">配送中订单</span>
                    <span class="disstyle" id="disstyle2"></span>
                </div>
                <div style="height:286px;" id="default_order_list2">
                    <div class="tabs_order">
                        <ul id="tabs2">
                            <li class="tab-nav-action">待配送</li>
                            <li class="tab-nav">配送中</li>
                            <li class="tab-nav">配送完成</li>
                        </ul>
                    </div>
                    <div id="tabs-bodys" class="tabs-body">
                        <div style="display:block">
                            <table class="es_modtables"  border="0" cellpadding="3" cellspacing="1" width="100%">
                                <thead>
                                <tr>
                                    <th width="6%">No</th>
                                    <th width="26%">订单编号</th>
                                    <th width="18%">订单名称</th>
                                    <th width="18%">支付状态</th>
                                    <th width="32%">订单金额</th>
                                </tr>
                                </thead>
                                <?$f=0;
                                foreach($infos[1] as $orders){

                                    $f++;
                                    ?>
                                    <tr><td><?=$f?></td>
                                        <td><?=$orders['order_sn']?></td>
                                        <td><?=$orders['order_title']?></td>
                                        <td><?getPay($orders['pay_status'])?></td>
                                        <td><?=$orders['order_amount']?></td>
                                    </tr>
                                <? }?>


                            </table>
                        </div>
                        <div style="display:none">
                            <table class="es_modtables"  border="0" cellpadding="3" cellspacing="1" width="100%">
                                <thead>
                                <tr>
                                    <th width="6%">No</th>
                                    <th width="26%">订单编号</th>
                                    <th width="18%">订单名称</th>
                                    <th width="18%">支付状态</th>
                                    <th width="32%">订单金额</th>
                                </tr>
                                </thead>
                                <?$g=0;
                                foreach($infos[2] as $orders){

                                    $g++;
                                    ?>
                                    <tr><td><?=$g?></td>
                                        <td><?=$orders['order_sn']?></td>
                                        <td><?=$orders['order_title']?></td>
                                        <td><?getPay($orders['pay_status'])?></td>
                                        <td><?=$orders['order_amount']?></td>
                                    </tr>
                                <? }?>


                            </table>
                        </div>
                        <div style="display:none">
                            <table class="es_modtables" border="0" cellpadding="3" cellspacing="1" width="100%">
                                <thead>
                                <tr>
                                    <th width="6%">No</th>
                                    <th width="26%">订单编号</th>
                                    <th width="18%">订单名称</th>
                                    <th width="18%">支付状态</th>
                                    <th width="32%">订单金额</th>
                                </tr>
                                </thead>
                                <?$h=0;
                                foreach($infos[3] as $orders){

                                    $h++;
                                    ?>
                                    <tr><td><?=$h?></td>
                                        <td><?=$orders['order_sn']?></td>
                                        <td><?=$orders['order_title']?></td>
                                        <td><?getPay($orders['pay_status'])?></td>
                                        <td><?=$orders['order_amount']?></td>
                                    </tr>
                                <? }?>


                            </table>
                        </div>

                    </div>
                </div>
            </div>
            </div>
        </div>
<!-------------------------------------------------------- 左边完毕 ------------------------------------------------------------------>
        <div class="es_main2">
            <div class="es_moddiv2">
                <div class="es_dbegin es_bder">
                    <img src="images/company/es_tj.png" />
                    <span class="s_font">最近订单</span>
                    <span class="disstyle" id="disstyle4"></span>
                </div>
                <div class="es_displays" id="es_mod4" style="height:286px;">
                    <div  id="es_mod4s" align="center">
                   <!--柱形图-->
                    </div>
                </div>
            </div>
            <script language="JavaScript" src="FusionCharts3.2.1/Charts/FusionCharts.js"></script>
            <script type="text/javascript">
                var DataJSON = <?php echo $DataJSON?>;
                var yuanzhu = new FusionCharts("FusionCharts3.2.1/Charts/Column2D.swf", "yuanzhuid", "560", "260", "0", "0");
                 yuanzhu.setJSONData(DataJSON);
                 yuanzhu.render("es_mod4s");
            </script>
            <div class="es_moddiv2">
                <div class="es_dbegin es_bder">
                    <img src="images/company/es_xx.png" />
                    <span class="s_font">帮助信息</span>
                    <span class="disstyle" id="disstyle3"></span>
                </div>
                <div class="es_displays" id="es_mod3" style="height:286px;">
                    <table border="0" cellpadding="3" cellspacing="1" width="100%"class="es_modnexts">
                        <tr>
                            <td width="15%" align="center"><img src="images/company/es_tdxx.png" /></td>
                            <td width="45%">
                                <ul>
                                    <li><a href="javascript:void(0);" class="es_al">平台最新政策<span class="lefts">2015-05-23</span></a></li>
                                    <li><a href="javascript:void(0);" class="es_al">平台最新政策<span class="lefts">2015-05-23</span></a></li>
                                    <li><a href="javascript:void(0);" class="es_al">平台最新政策<span class="lefts">2015-05-23</span></a></li>
                                </ul>
                            </td>
                            <td width="40%">
                                <ul>
                                    <li><a href="javascript:void(0);" class="es_al">平台最新政策<span class="lefts">2015-05-23</span></a></li>
                                    <li><a href="javascript:void(0);" class="es_al">平台最新政策<span class="lefts">2015-05-23</span></a></li>
                                    <li><a href="javascript:void(0);" class="es_al">平台最新政策<span class="lefts">2015-05-23</span></a></li>
                                </ul>
                            </td>
                        </tr>
                    </table>
                    <table border="0" cellpadding="3" cellspacing="1" width="100%" class="es_modnexts">
                        <tr>
                            <td align="center"><img src="images/company/es_td1.png" /><span class="nexts lefts">个人修改信息</span></td>
                            <td align="center"><img src="images/company/es_td2.png" /><span class="nexts lefts">我的生产工作统计</span></td>
                            <td align="center"><img src="images/company/es_td3.png" /><span class="nexts lefts">登陆<i>45</i>次</span></td>
                        </tr>
                    </table>
                    <table border="0" cellpadding="3" cellspacing="1" width="100%" class="es_modends">
                        <tr>
                            <td align="center" colspan="3">
                                <ul class="es_ultable">
                                    <li class="greens">
                                        <img src="images/company/es_li1.png" />
                                        <p class="pl1">任务 </p>
                                        <p class="pl2">完成 </p>
                                    <li class="blues">
                                        <img src="images/company/es_li2.png" />
                                        <p class="pl1">获得 </p>
                                        <p class="pl2">$32,000 </p>
                                    </li>
                                    <li class="glays">
                                        <img src="images/company/es_li3.png" />
                                        <p class="pl1">下载次数 </p>
                                        <p class="pl2">1,205 </p>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

</body>
</html>
