<table id="easyui-datagrid-process-car-trace"></table> 

<div id="easyui-datagrid-process-car-trace-toolbar">

    <div class="easyui-panel" title="数据列表" style="padding:3px 2px;width:100%;" data-options="
        iconCls: 'icon-table-list',
        border: false
    ">
		
    </div>
</div>

<script>
    var ProcessCarTrace = new Object();
    ProcessCarTrace.init = function(){
        //获取列表数据process-config
        $('#easyui-datagrid-process-car-trace').datagrid({  
            method: 'POST', 
            url:"<?php echo yii::$app->urlManager->createUrl(['process/car/trace']); ?>&id=<?php echo $result['id'] ?>&template_id=<?php echo $result['template_id'] ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-process-car-trace-toolbar",
            pagination: true,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
            showFooter: true,
			pageSize: 20,
            frozenColumns: [[
                {field: 'id',title: 'id',hidden: true}
            ]],
            columns: [[
                 //{field: 'role_name',title: '角色名称',width: 120,align: 'center'},
                 {field: 'current_operator',title: '当前操作',width: 120,align: 'center'},
                 {field: 'operator_name',title: '操作人',width: 120,align: 'center'},
                 {field: 'time',title: '操作时间',width: 120,align: 'center'},
                 {field: 'result',title: '结果',width: 120,align: 'center'},
                 {field: 'remark',title: '备注',width: 120,align: 'center'},
            ]],
            onLoadSuccess: function (data){
                $(this).datagrid('doCellTip',{
                    position : 'bottom',
                    maxWidth : '300px',
                    onlyShowInterrupt : true,
                    specialShowFields : [     
                        {field : 'action',showField : 'action'}
                    ],
                    tipStyler : {            
                        'backgroundColor' : '#E4F0FC',
                        borderColor : '#87A9D0',
                        boxShadow : '1px 1px 3px #292929'
                    }
                });
            }
        });
    }
    
  	//执行
    ProcessCarTrace.init();
    //获取选择的记录
    ProcessCarTrace.getSelected = function(){
        var datagrid = $('#easyui-datagrid-process-car-trace');
        var selectRow = datagrid.datagrid('getSelected');
        if(!selectRow){
            $.messager.alert('错误','请选择要操作的记录','error');   
            return false;
        }
        return selectRow;
    }


    
</script>