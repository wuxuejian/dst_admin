<table id="easyui-datagrid-car-inventory-index"></table> 
<div id="easyui-datagrid-car-inventory-index-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-car-inventory-index">
                <ul class="search-main">
                <!-- 云林说大区是写死的，方志华说就按写死的来  by 2016/12/7 -->
                	<li>
                        <div class="item-name">大区</div>
                        <div class="item-input">
                            <select   class="easyui-combobox" name="regional" data-options="editable:false"  style="width:100%;"  >
                            <option value=''>不限</option>
                            <option value='1'>华南大区</option>
                            <option value='2'>华北大区</option>
                            <option value='3'>华东大区</option>
                            <option value='4'>华中大区</option>
                            <option value='5'>西南大区</option>
                            </select>
                        </div>
                    </li>
                    <input type="hidden" id="ocs1" name="ocs" /><!-- 当前大区下的所有运营公司ID -->
                    <li>
                        <div class="item-name">运营公司</div>
                        <div class="item-input">
                            <input id="oc1"  name="operating_company_id"  class="easyui-combobox" style="width:100%;" data-options="editable:false"  />
                        </div>
                    </li>
                    <!--  <li>
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
                    </li>-->
                    <li class="search-button">
                        <button class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button onclick="CarInventoryIndex.resetForm();" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
                    </li>
                </ul>
            </form>
        </div>
    </div>
    <div class="easyui-panel" title="数据列表" style="padding:3px 2px;width:100%;" data-options="
        iconCls: 'icon-table-list',
        border: false
    ">
        <a href="javascript:CarInventoryIndex.export_excel()" class="easyui-linkbutton" data-options="iconCls:'icon-excel'">导出列表</a>
    </div>
</div>
<script>
    var CarInventoryIndex = new Object();
    CarInventoryIndex.init = function(){
        //获取列表数据car-inventory
        $('#easyui-datagrid-car-inventory-index').datagrid({  
            method: 'POST', 
            url:"<?php echo yii::$app->urlManager->createUrl(['car/inventory/get-list']); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-car-inventory-index-toolbar",
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
				 {field: 'regional',title: '大区',width: 240,align: 'center',sortable: true},
				 {field: 'operating_company_name',title: '运营公司',width: 240,align: 'center',sortable: true},
				 {field: 'car_brand_name',title: '品牌',width: 120,align: 'center',sortable: true},
				 {field: 'car_model_name',title: '车型',width: 120,align: 'center',sortable: true},
                 {field: 'prepare_count',title: '提车中',width: 120,align: 'center',sortable: true},
				 //{field: 'demand_count',title: '已进入提车流程数量',width: 120,align: 'center',sortable: true},
				// {field: 'extract_car_count',title: '已整备数量',width: 120,align: 'center',sortable: true}, 
				 {field: 'back_count',title: '退车中数量',width: 120,align: 'center',sortable: true},
                 {field: 'lock_count',title: '锁定库存',width: 120,align: 'center',sortable: true},
                 {field: 'inventory_count',title: '可用库存',width: 120,align: 'center',sortable: true},
                 {field: 'dealer_count',title: '经销商库存',width: 120,align: 'center',sortable: true},
                    
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
    CarInventoryIndex.init();
    //查询表单构建
    var searchForm = $('#search-form-car-inventory-index');
    searchForm.submit(function(){
        var data = {};
        var searchCondition = $(this).serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        $('#easyui-datagrid-car-inventory-index').datagrid('load',data);
        return false;
    });

    searchForm.find('select[name=regional]').combobox({
    	valueField:'value',
        textField:'text',
        editable: false,
        panelHeight:'auto',
        onSelect: function(rec){
        	$('#oc1').combobox('clear');
        	var data = [{text:'不限',value:''},];
        	//当前大区下所有的运营公司id
        	var ocs = '';
        	<?php foreach ($searchFormOptions['operating_company_id']  as $val):?>
	       	  	if(rec.value == '<?php echo $val['area'];?>'){
	           	 	var a = {text:'<?php echo $val['name']?>',value:'<?php echo $val['id']?>'};
	           	 	data.push(a);
	           	 	if(ocs == '') {
	           	 		ocs += '<?php echo $val['id'];?>';
		           	}else{
		           		ocs += ','+'<?php echo $val['id'];?>';
			        } 
		           	 	
	           	 	
	       	  	}
       	  	 <?php endforeach;?>
    		if(rec.value == ''){
      		 var data = [{text:'不限',value:''},];
    		}
        	$('#oc1').combobox('loadData',data);
        	$("#ocs1").val(ocs);
            searchForm.submit();
        }
    });
    searchForm.find('input[name=operating_company_id]').combobox({
    	valueField:'value',
        textField:'text',
        editable: false,
        panelHeight:'auto',
        onSelect: function(){
            searchForm.submit();
        }
    });

    
	/* 下面script中代码 */

    //重置查询表单
    CarInventoryIndex.resetForm = function(){
        var easyuiForm = $('#search-form-car-inventory-index');
        easyuiForm.form('reset');
    }
    CarInventoryIndex.export_excel = function(){
        var form = $('#search-form-car-inventory-index');
        window.open("<?php echo yii::$app->urlManager->createUrl(['car/inventory/export-excel']); ?>&"+form.serialize());
    }
    
</script>
       	  	 
 <!--       	  	 
<script>
searchForm.find('input[name=operating_company_id]').combobox({
    	valueField:'value',
        textField:'text',
        data: <?php //echo json_encode($searchFormOptions['operating_company_id']); ?>,
        editable: false,
        panelHeight:'auto',
        onSelect: function(){
            searchForm.submit();
        }
    });
searchForm.find('input[name=car_brand]').combobox({
	valueField:'value',
    textField:'text',
    data: <?php  //echo json_encode($searchFormOptions['car_brand']); ?>,
    editable: false,
    panelHeight:'auto',
    onSelect: function(rec){
    	$('#car_model_name').combobox('clear');
    	<?php //foreach ($searchFormOptions['car_model_name'] as $key=>$car_model_name):?>
   	  	if(rec.value == '<?php //echo $key;?>'){
       	  var data =[
       	  	 	<?php //foreach ($car_model_name as $val):?>
       	  	 	 {text:'<?php //echo $val['text']?>',value:'<?php //echo $val['value']?>'},
       	  	 	<?php //endforeach;?>
       	  	];
   	  	}
   	  	 <?php //endforeach;?>
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
</script>--> 