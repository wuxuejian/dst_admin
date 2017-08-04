<table id="easyui-datagrid-car-office-car-record-index"></table> 
<div id="easyui-datagrid-car-office-car-record-index-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-car-office-car-form-index">
                <ul class="search-main">
                    <li>
                        <div class="item-name">车牌号</div>
                        <div class="item-input">
                            <input name="plate_number" style="width:150px;" />
                        </div>
                    </li>
        
                    <li>
                        <div class="item-name">用车人</div>
                        <div class="item-input">
                            <input name="username" style="width:150px;" />
                        </div>
                    </li>
                    <li class="search-button">
                        <button class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button onclick="CarOfficeCarRecordIndex.resetForm();return false;" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
                    </li>
                </ul>
            </form>
        </div>
    </div>

    <?php if(!empty($buttons)){ ?>
        <div class="easyui-panel" title="数据列表" style="padding:3px 2px;width:100%;" data-options="
        iconCls: 'icon-table-list',
        border: false
    ">
            <?php foreach($buttons as $val){ ?>
                <button onclick="<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon'] ;?>'"><?= $val['text'] ;?></button>
            <?php } ?>
        </div>
    <?php } ?>

</div>

<!-- 窗口 -->

<div id="easyui-dialog-car-office-car-record-index-scan"></div>


<!-- 窗口 -->

<script>
    var CarOfficeCarRecordIndex = new Object();
    //配置项
    
    CarOfficeCarRecordIndex.init = function(){
        //获取列表数据
        $('#easyui-datagrid-car-office-car-record-index').datagrid({  
            method: 'get', 
            url:"<?php echo yii::$app->urlManager->createUrl(['car/office-car-record/get-list']); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-car-office-car-record-index-toolbar",
            pagination: true,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
            pageSize: 20,
            sortName: 'next_valid_date',
            sortOrder: 'asc',
            frozenColumns: [[
                {field: 'ck',checkbox: true}, 
                {field: 'id',title: 'id',hidden: true},   
                {field: 'plate_number',title: '车牌号',width: 100,sortable: true,align: 'center'}
            ]],
            columns:[[
               

                {
                    field: 'car_brand',title: '车辆品牌',width: 120,align: 'center',
                    
                },

                {
                    field: 'car_model',title: '车型名称',width: 120,align: 'center',
                   
                },
                /*{field: 'archives_number',title: '档案编号',width: 120,halign: 'center',sortable: true},*/
                {field: 'department_name',title: '申请部门',width: 120,align: 'center',sortable: true},
                {field: 'username',title: '用车人',width: 120,align: 'center',sortable: true},
                {field: 'start_time',title: '开始用车时间',width: 120,halign: 'center',sortable: true},
                {field: 'return_time',title: '还车时间',width: 120,halign: 'center',sortable: true},
                {field: 'use_time',title: '用车时长(小时)',width: 120,align: 'center',sortable: true},
                {field: 'use_distance',title: '用车里程（KM）',width: 120,align: 'center',sortable: true},
                {field: 'reg_name',title: '登记人',width: 120,align: 'center',sortable: true},
                {field: 'reg_time',title: '登记时间',width: 120,halign: 'center',sortable: true},
                
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

       
        

    //初始化查看窗口
        $('#easyui-dialog-car-office-car-record-index-scan').window({
            title: '公务车出车登记',
            width: '50%',   
            height: '90%',   
            closed: true,   
            cache: true,   
            modal: true,
            collapsible: false,
            minimizable: false, 
            maximizable: true,
            onClose: function(){
                $(this).window('clear');
            }       
        });

     CarOfficeCarRecordIndex.getSelected = function(all){
        var datagrid = $('#easyui-datagrid-car-office-car-record-index');
        if(all){
            var selectRows = datagrid.datagrid('getSelections');
            if(selectRows.length <= 0){
                $.messager.alert('错误','请选择要操作的记录','error');   
                return false;
            }
            return selectRows;
        }else{
            var selectRow = datagrid.datagrid('getSelected');
            if(!selectRow){
                $.messager.alert('错误','请选择要操作的记录','error');   
                return false;
            }
            return selectRow;
        }
        
    }

        //查看
    CarOfficeCarRecordIndex.scan = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var id = selectRow.id;
        $('#easyui-dialog-car-office-car-record-index-scan').window('open');
        $('#easyui-dialog-car-office-car-record-index-scan').window('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/office-car-record/scan']); ?>&id="+id);
    }
     

    var searchForm = $('#search-form-car-office-car-form-index');
        /**查询表单提交事件**/
        searchForm.submit(function(){
            var data = {};
            var searchCondition = $(this).serializeArray();
            for(var i in searchCondition){
                data[searchCondition[i]['name']] = searchCondition[i]['value'];
            }
            $('#easyui-datagrid-car-office-car-record-index').datagrid('load',data);
            return false;
        });
        searchForm.find('input[name=plate_number]').textbox({
            onChange: function(){
                searchForm.submit();
            }
        });
         searchForm.find('input[name=username]').textbox({
            onChange: function(){
                searchForm.submit();
            }
        });
         

    //按条件导出车辆列表
    CarOfficeCarRecordIndex.export = function(){
        var url = "<?php echo yii::$app->urlManager->createUrl(['car/office-car-record/export-width-condition']);?>";
        var form = $('#search-form-car-office-car-form-index');
        var data = {};
        var searchCondition = form.serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        for(var i in data){
            url += '&'+i+'='+data[i];
        }
        window.open(url);
    }

    }
    //重置查询表单
    CarOfficeCarRecordIndex.resetForm = function(){
        var easyuiForm = $('#search-form-car-office-car-form-index');
        easyuiForm.form('reset');
        easyuiForm.submit();
    }

    

    CarOfficeCarRecordIndex.init();
</script>