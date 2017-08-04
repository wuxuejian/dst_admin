<script>
    var CarOverviewLicense = new Object();
</script>
<div style="border-bottom:1px solid #95B8E7"></div>
<div class="easyui-panel" title="行驶证年审预警" border="false" collapsible="true">
    <div style="padding:5px 0;">
        <?php
        if($drivingLicense){
        ?>
        <table id="easyui-datagrid-car-overview-license-drivinglicense"></table>
        <script>
            CarOverviewLicense.drivinglicense = function(){
                var easyuiDatagrid = $('#easyui-datagrid-car-overview-license-drivinglicense');
                easyuiDatagrid.datagrid({
                    columns:[[
                        {field:'addr',title:'登记地址',width: 200},
                        {field:'register_date',title:'注册日期',width: 140,align: 'center'},
                        {field:'issue_date',title:'发证日期',width: 140,align: 'center'}, 
                        {field:'archives_number',title:'档案编号',width: 140,align: 'center'},
                        {field:'total_mass',title:'整备质量(kg)',width: 140,align: 'center'},
                        {field:'leftDay',title:'年审剩余时间(天)',width: 140,align: 'center'},
                        {field:'add_datetime',title:'修改时间',width: 140,align: 'center'},
                        {field:'username',title:'操作账号',width: 140,align: 'center'}
                    ]]
                });
                easyuiDatagrid.datagrid('appendRow',<?= json_encode($drivingLicense); ?>)
            };
            CarOverviewLicense.drivinglicense();
        </script>
        <?php }else{ ?>
        <div style="color:red">无记录！</div>
        <?php } ?>
    </div>
</div>
<div style="border-bottom:1px solid #95B8E7"></div>
<div class="easyui-panel" title="道路运输证预警" border="false" collapsible="true">
    <div style="padding:5px 0;">
        <?php
        if($transportCertificate){
        ?>
        <table id="easyui-datagrid-car-overview-license-transportcertificate"></table>
        <script>
            CarOverviewLicense.transportcertificate = function(){
                var easyuiDatagrid = $('#easyui-datagrid-car-overview-license-transportcertificate');
                easyuiDatagrid.datagrid({
                    columns:[[
                        {field:'ton_or_seat',title:'吨(位)',width: 80},
                        {field:'issuing_organ',title:'核发机关',width: 200,align: 'center'},
                        {field:'rtc_province',title:'省',width: 40,align: 'center'}, 
                        {field:'rtc_city',title:'市',width: 40,align: 'center'},
                        {field:'rtc_number',title:'道路运输证号',width: 140,align: 'center'},
                        {field:'issuing_date',title:'发证日期',width: 140,align: 'center'},
                        {field:'leftDay',title:'审核剩余时间(天)',width: 140,align: 'center'},
                        {field:'last_annual_verification_date',title:'上次审核时间',width: 140,align: 'center'},
                        {field:'add_datetime',title:'修改时间',width: 140,align: 'center'},
                        {field:'username',title:'操作账号',width: 140,align: 'center'}
                    ]]
                });
                easyuiDatagrid.datagrid('appendRow',<?= json_encode($transportCertificate); ?>)
            };
            CarOverviewLicense.transportcertificate();
        </script>
        <?php }else{ ?>
        <div style="color:red">无记录！</div>
        <?php } ?>
    </div>
</div>
<div style="border-bottom:1px solid #95B8E7"></div>
<div class="easyui-panel" title="交强险预警" border="false" collapsible="true">
    <div style="padding:5px 0;">
        <?php
        if($insuranceCompulsory){
        ?>
        <table id="easyui-datagrid-car-overview-license-insurancecompulsory"></table>
        <script>
            CarOverviewLicense.insurancecompulsory = function(){
                var easyuiDatagrid = $('#easyui-datagrid-car-overview-license-insurancecompulsory');
                easyuiDatagrid.datagrid({
                    columns:[[
                        {field:'insurer_company',title:'保险公司',width: 200},
                        {field:'money_amount',title:'保险金额',width: 120,align: 'right'},
                        {field:'start_date',title:'开始时间',width: 140,align: 'center'}, 
                        {field:'end_date',title:'结束时间',width: 140,align: 'center'},
                        {field:'leftDay',title:'剩余时间(天)',width: 140,align: 'center'},
                        {field:'add_datetime',title:'修改时间',width: 140,align: 'center'},
                        {field:'username',title:'操作账号',width: 140,align: 'center'}
                    ]]
                });
                easyuiDatagrid.datagrid('appendRow',<?= json_encode($insuranceCompulsory); ?>)
            };
            CarOverviewLicense.insurancecompulsory();
        </script>
        <?php }else{ ?>
        <div style="color:red">无记录！</div>
        <?php } ?>
    </div>
</div>
<div style="border-bottom:1px solid #95B8E7"></div>
<div class="easyui-panel" title="商业险预警" border="false" collapsible="true">
    <div style="padding:5px 0;">
        <?php
        if($insuranceBusiness){
        ?>
        <table id="easyui-datagrid-car-overview-license-insurancebusiness"></table>
        <script>
            CarOverviewLicense.insurancebusiness = function(){
                var easyuiDatagrid = $('#easyui-datagrid-car-overview-license-insurancebusiness');
                easyuiDatagrid.datagrid({
                    columns:[[
                        {field:'insurer_company',title:'保险公司',width: 200},
                        {field:'money_amount',title:'保险金额',width: 120,align: 'right'},
                        {field:'start_date',title:'开始时间',width: 140,align: 'center'}, 
                        {field:'end_date',title:'结束时间',width: 140,align: 'center'},
                        {field:'leftDay',title:'剩余时间(天)',width: 140,align: 'center'},
                        {field:'add_datetime',title:'修改时间',width: 140,align: 'center'},
                        {field:'username',title:'操作账号',width: 140,align: 'center'}
                    ]]
                });
                easyuiDatagrid.datagrid('appendRow',<?= json_encode($insuranceBusiness); ?>)
            };
            CarOverviewLicense.insurancebusiness();
        </script>
        <?php }else{ ?>
        <div style="color:red">无记录！</div>
        <?php } ?>
    </div>
</div>
<div style="border-bottom:1px solid #95B8E7"></div>
<div class="easyui-panel" title="二级维护记录预警" border="false" collapsible="true">
    <div style="padding:5px 0;">
        <?php
        if($secondMaintenance){
        ?>
        <table id="easyui-datagrid-car-overview-license-secondmaintenance"></table>
        <script>
            CarOverviewLicense.secondmaintenance = function(){
                var easyuiDatagrid = $('#easyui-datagrid-car-overview-license-secondmaintenance');
                easyuiDatagrid.datagrid({
                    columns:[[
                        {field:'number',title:'编号',width: 140},
                        {field:'current_date',title:'本次维护时间',width: 120,align: 'right'},
                        {field:'next_date',title:'下次维护时间',width: 140,align: 'center'}, 
                        {field:'leftDay',title:'剩余时间(天)',width: 140,align: 'center'},
                        {field:'add_datetime',title:'修改时间',width: 140,align: 'center'},
                        {field:'username',title:'操作账号',width: 140,align: 'center'}
                    ]]
                });
                easyuiDatagrid.datagrid('appendRow',<?= json_encode($secondMaintenance); ?>)
            };
            CarOverviewLicense.secondmaintenance();
        </script>
        <?php }else{ ?>
        <div style="color:red">无记录！</div>
        <?php } ?>
    </div>
</div>