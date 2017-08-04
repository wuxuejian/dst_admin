<table id="easyui-datagrid-car-inventory-refit"></table> 
<div id="easyui-datagrid-car-inventory-refit-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-car-inventory-refit">
                <ul class="search-main">
                    <li>
                        <div class="item-name">运营公司</div>
                        <div class="item-input">
                            <input name="operating_company_id" style="width:100%;" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">品牌</div>
                        <div class="item-input">
                            <input name="car_brand" style="width:100%;" />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">车型</div>
                        <div class="item-input">
                            <select  id="car_model_name" class="easyui-combobox" name="car_model_name" data-options="editable:false"  style="width:100%;"  >
                            <option value=''>不限</option>
                            </select>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">改装类型</div>
                        <div class="item-input">
                            <input id="modified_car_type"  name="modified_car_type" style="width:100%;" />
                        </div>
                    </li>
                    <li class="search-button">
                        <button class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button onclick="ProcessConfigIndex.resetForm();" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
                    </li>
                </ul>
            </form>
        </div>
    </div>
</div>
<script>
    var ProcessConfigIndex = new Object();
    ProcessConfigIndex.init = function(){
        //获取列表数据car-inventory
        $('#easyui-datagrid-car-inventory-refit').datagrid({  
            method: 'POST', 
            url:"<?php echo yii::$app->urlManager->createUrl(['car/inventory/refit']); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-car-inventory-refit-toolbar",
            pagination: true,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
            showFooter: true,
			pageSize: 20,
            frozenColumns: [[
                {field: 'ck',checkbox: true}, 
            ]],
            columns: [[
				 {field: 'plate_number',title: '车牌号',width: 120,align: 'center',sortable: true},
				 {field: 'car_brand_name',title: '品牌',width: 120,align: 'center',sortable: true},
				 {field: 'car_model_name',title: '车型',width: 120,align: 'center',sortable: true},
				 {field: 'modified_type',title: '改装类型',width: 240,align: 'left',sortable: true},
				 {field: 'oc_name',title: '运营公司',width: 240,align: 'center',sortable: true},
                    
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
    ProcessConfigIndex.init();
    //查询表单构建
    var searchForm = $('#search-form-car-inventory-refit');
    searchForm.submit(function(){
        var data = {};
        var searchCondition = $(this).serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        //改装车类型多选值
        data['modified_car_type'] =  $('#modified_car_type').combobox('getValues');
        $('#easyui-datagrid-car-inventory-refit').datagrid('load',data);
        return false;
    });
    searchForm.find('input[name=operating_company_id]').combobox({
    	valueField:'id',
        textField:'name',
        data: <?php echo json_encode($searchFormOptions['operating_company_id']); ?>,
        editable: false,
        panelHeight:'auto',
        onSelect: function(){
            searchForm.submit();
        }
    });
    searchForm.find('input[name=car_brand]').combobox({
    	valueField:'value',
        textField:'text',
        data: <?php  echo json_encode($searchFormOptions['car_brand']); ?>,
        editable: false,
        panelHeight:'auto',
        onSelect: function(rec){
        	$('#car_model_name').combobox('clear');
        	<?php foreach ($searchFormOptions['car_model_name'] as $key=>$car_model_name):?>
       	  	if(rec.value == '<?php echo $key;?>'){
           	  var data =[
           	  	 	<?php foreach ($car_model_name as $val):?>
           	  	 	 {text:'<?php echo $val['text']?>',value:'<?php echo $val['value']?>'},
           	  	 	<?php endforeach;?>
           	  	];
       	  	}
       	  	 <?php endforeach;?>
    		if(rec.value == ''){
      		 var data = [{text:'不限',value:''},];
    		}
            
        	 $('#car_model_name').combobox('loadData',data);
            searchForm.submit();
        }
    });
    searchForm.find('select[name=car_model_name]').combobox({
    	valueField:'value',
        textField:'text',
        editable: false,
        panelHeight:'auto',
        onSelect: function(){
            searchForm.submit();
        }
    });
    searchForm.find('input[name=modified_car_type]').combobox({
    	valueField:'value',
        textField:'text',
        data: <?= json_encode($searchFormOptions['modified_car_type']); ?>,
        editable: false,
        multiple: true,
        panelHeight:'auto',
        /*onSelect: function(){
            searchForm.submit();
        }*/
    });

    //重置查询表单
    ProcessConfigIndex.resetForm = function(){
        var easyuiForm = $('#search-form-car-inventory-refit');
        easyuiForm.form('reset');
    }

    
</script>    	  	 