<script>
    var CarOverviewUseStatus = new Object();
</script>
<?php
    switch($carStatus){
        case 'NAKED':
?>
<div>车辆状态：裸车</div>
<?php
            break;
        case 'STOCK':
?>
<div>车辆状态：库存</div>
<?php
            break;
        case 'REPAIRING':
        case 'FAULT':
            //维修中或故障
?>
<table id="car-overview-use-status-fault"></table>
<script type="text/javascript">
    CarOverviewUseStatus.fault = function(){
        var easyuiDatagrid = $('#car-overview-use-status-fault');
        easyuiDatagrid.datagrid({
            columns:[[
                {field:'car_status',title:'车辆状态',width:100,align: 'center'},
                {field:'f_desc',title:'故障描述',width:300},
                {field:'fault_status',title:'当前状态',width:100,align:'center'},
                {field:'username',title:'登记人员',width:100,align:'center'},
                {field:'reg_datetime',title:'登记时间',width:140,align:'center'}
            ]]
        });
        easyuiDatagrid.datagrid('appendRow',<?= $data; ?>);
    };
    CarOverviewUseStatus.fault();
</script>
<?php
            break;
        case 'LETING':
            //出租中
?>
<table id="car-overview-use-status-leting"></table>
<script type="text/javascript">
    CarOverviewUseStatus.leting = function(){
        var easyuiDatagrid = $('#car-overview-use-status-leting');
        easyuiDatagrid.datagrid({
            columns:[[
                {field:'car_status',title:'车辆状态',width:100,rowspan: 2,align:'center'},
                {title: '合同详情',colspan: 3},
                {field: 'let_time',title:'出租时间',width: 140,align: 'center',rowspan: 2},
                {field: 'back_time',title:'还车时间',width: 140,align: 'center',rowspan: 2},
                {field: 'note',title:'备注',rowspan: 2,width: 200}
            ],[
                {field: 'contract_number',title: '合同编号',width: 130,halign: 'center'},
                {field: 'customer_name',title: '客户名称',width: 220,halign: 'center',
                    formatter: function(value,row,index){ //企业/个人客户名称
                        if(row.cCustomer_name){
                            return row.cCustomer_name;
                        }else if(row.pCustomer_name){
                            return row.pCustomer_name;
                        }else{
                            return '';
                        }
                    }
                },
                {field: 'customer_type',title: '客户类型',width: 70,align: 'center'}
            ]]
        });
        easyuiDatagrid.datagrid('appendRow',<?= $data; ?>);
    };
    CarOverviewUseStatus.leting();
</script>
<?php
            break;
        case 'INTRIAL':
            //试用中
?>
<table id="car-overview-use-status-intrial"></table>
<script type="text/javascript">
    CarOverviewUseStatus.intrial = function(){
        var easyuiDatagrid = $('#car-overview-use-status-intrial');
        easyuiDatagrid.datagrid({
            columns:[[
                {field:'car_status',title:'车辆状态',width:100,rowspan: 2,align:'center'},
                {title: '试用协议详情',colspan: 5},
                {field: 'ctpd_deliver_date',title:'交车时间',width: 90,align: 'center',rowspan: 2},
                {field: 'ctpd_back_date',title:'还车时间',width: 90,align: 'center',rowspan: 2},
                {field: 'ctpd_note',title:'备注',rowspan: 2,width: 200}
            ],[
                {field: 'ctp_number',title: '协议编号',width: 130,halign: 'center'},
                {field: 'cc_name',title: '客户名称',width: 220,halign: 'center'},
                {field: 'ctp_sign_date',title: '签订日期',width: 90,align: 'center'},
                {field: 'ctp_start_date',title: '开始时间',width: 90,align: 'center'},
                {field: 'ctp_end_date',title: '结束时间',width: 90,align: 'center'}
            ]]
        });
        easyuiDatagrid.datagrid('appendRow',<?= $data; ?>);
    };
    CarOverviewUseStatus.intrial();
</script>
<?php
            break;
    }
?>