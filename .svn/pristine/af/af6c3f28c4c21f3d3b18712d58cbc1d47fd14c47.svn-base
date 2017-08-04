<table id="easyui-datagrid-finance-rent-index"></table> 
<div id="easyui-datagrid-finance-rent-index-toolbar">
      <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-finance-rent-index">
                <ul class="search-main">
                    <li>
                        <div class="item-name">公司名称</div>
                        <div class="item-input">
                            <input name="company_name" style="width:100%;" />
                        </div>
                    </li>
                    <li class="search-button">
                        <button type="submit" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button type="submit" onclick="CarFinanceRentIndex.resetForm()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
                    </li>
                </ul>

            </form>
        </div>
    </div>
    <?php if($buttons){ ?>
    <div class="easyui-panel" title="数据列表" style="padding:3px 2px;width:100%;" data-options="
        iconCls: 'icon-table-list',
        border: false
    ">
        <?php foreach($buttons as $val){ ?>
        <a href="javascript:<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon']; ?>'"><?= $val['text']; ?></a>
        <?php } ?>
    </div>
    <?php } ?>
</div>
<!-- 窗口 -->
<div id="easyui-dialog-finance-rent-index-add"></div>
<div id="easyui-dialog-finance-rent-index-edit"></div>
<div id="easyui-dialog-finance-rent-index-rel-car"></div><!-- 关联车辆管理 -->
<!-- 窗口 -->
<script>
    var CarFinanceRentIndex = new Object();

    CarFinanceRentIndex.init = function(){
        //获取列表数据process-extract
        $('#easyui-datagrid-finance-rent-index').datagrid({  
            method: 'POST', 
            url:"<?php echo yii::$app->urlManager->createUrl(['car/finance-rent/get-list']); ?>",   
            fit: true,
            border: false,
            toolbar: "#easyui-datagrid-finance-rent-index-toolbar",
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
                {field: 'id',title: 'id',hidden: true}
            ]],
            columns: [[
                 {field: 'number',title: '客户号',width: 180,align: 'center',sortable: true},
                 {field: 'company_name',title: '公司名称',width: 250,align: 'center',sortable: true},
                 {field: 'director_name',title: '负责人',width: 150,align: 'center',sortable: true},
                 {field: 'director_mobile',title: '手机号',width: 150,align: 'center',sortable: true},
                 {field: 'num',title: '车辆数量',width: 150,align: 'center',sortable: true},
                 {field: 'add_time',title: '创建时间',width: 150,align: 'center',sortable: true,
                 	formatter: function(value){
						if(!isNaN(value) && value > 0){
							return formatDateToString(value);
						} else {
							return "-";
						}
					}
             	},
                 {field: 'add_person',title: '创建人',width: 80,align: 'center',sortable: true,},

                 /*{field: 'sta_rel',title: '关联电站',width: 150,align: 'center',sortable: true},
                 {field: 'sta_name',title: '电站名称',width: 150,align: 'center',sortable: true},*/
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
	//初始化添加窗口
	$('#easyui-dialog-finance-rent-index-add').dialog({
    	title: '新增融资租赁公司',   
        width: '950px',   
        height: '225px',   
        closed: true,   
        cache: true,   
        modal: true,
        resizable:true,
        maximizable: true,
        buttons: [{
			text:'确定',
			iconCls:'icon-ok',
			handler:function(){
                var form = $('#easyui-form-finance-rent-add');
                if(!form.form('validate')) return false;
				var data = form.serialize();
				$.ajax({
					type: 'post',
					url: "<?php echo yii::$app->urlManager->createUrl(['car/finance-rent/add']); ?>",
					data: data,
					dataType: 'json',
					success: function(data){
						if(data.status){
							$.messager.alert('添加成功',data.info,'info');
							$('#easyui-dialog-finance-rent-index-add').dialog('close');
							$('#easyui-datagrid-finance-rent-index').datagrid('reload');
						}else{
							$.messager.alert('添加失败',data.info,'error');
						}
					}
				});
			}
		},{
			text:'取消',
			iconCls:'icon-cancel',
			handler:function(){
				$('#easyui-dialog-finance-rent-index-add').dialog('close');
			}
		}],
        onClose: function(){
            $(this).dialog('clear');
        }
    });

	//初始化编辑窗口
	$('#easyui-dialog-finance-rent-index-edit').dialog({
    	title: '编辑融资租赁公司',   
        width: '950px',   
        height: '225px',   
        closed: true,   
        cache: true,   
        modal: true,
        resizable:true,
        maximizable: true,
        buttons: [{
			text:'确定',
			iconCls:'icon-ok',
			handler:function(){
                var form = $('#easyui-form-finance-rent-edit');
                if(!form.form('validate')) return false;
				var data = form.serialize();
				$.ajax({
					type: 'post',
					url: "<?php echo yii::$app->urlManager->createUrl(['car/finance-rent/edit']); ?>",
					data: data,
					dataType: 'json',
					success: function(data){
						if(data.status){
							$.messager.alert('编辑成功',data.info,'info');
							$('#easyui-dialog-finance-rent-index-edit').dialog('close');
							$('#easyui-datagrid-finance-rent-index').datagrid('reload');
						}else{
							$.messager.alert('编辑失败',data.info,'error');
						}
					}
				});
			}
		},{
			text:'取消',
			iconCls:'icon-cancel',
			handler:function(){
				$('#easyui-dialog-finance-rent-index-edit').dialog('close');
			}
		}],
        onClose: function(){
            $(this).dialog('clear');
        }
    });

  	//关联车辆管理
   /* $('#easyui-dialog-finance-rent-index-reladd').window({
        title: '关联车辆管理',
    	width: 600,   
        height: 500,   
        modal: true,
        closed: true,
        collapsible: false,
        minimizable: false,
        maximizable: false,
        onClose: function(){
            $(this).window('clear');
        }                    
    });*/
	$('#easyui-dialog-finance-rent-index-rel-car').dialog({
            title: '融资租赁车辆关联',   
            width: '945px',   
            height: '400px',   
            closed: true,   
            cache: true,   
            modal: true,
            buttons: [],
            onClose: function(){
                $(this).dialog('clear');
            }
        });


    
  	//执行
    CarFinanceRentIndex.init();
    //查询表单构建
    var searchForm = $('#search-form-finance-rent-index');
    searchForm.submit(function(){
        var data = {};
        var searchCondition = $(this).serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
        $('#easyui-datagrid-finance-rent-index').datagrid('load',data);
        return false;
    });
    searchForm.find('input[name=company_name]').textbox({
        onChange: function(){
            searchForm.submit();
        }
    });

   
    /* searchForm.find('input[name=operating_company_id]').combobox({
            valueField:'value',
            textField:'text',
            data: <?= json_encode($searchFormOptions['operating_company_id']); ?>,
            editable: false,
            panelHeight:'auto',
            onSelect: function(){
                searchForm.submit();
            }
        });*/
    //查询表单构建结束

   /* $('#province').combobox({
        onChange: function (n,o) {
            var province_id = $('#province').combobox('getValue');
            $.ajax({
                   url:'<?php echo yii::$app->urlManager->createUrl(['charge/charge-station/get-region-list']); ?>',
                   type:'get',
                   data:{parent_id:province_id},
                   dataType:'json',
                   success:function(data){
                        $('#city').combobox({
                           valueField:'region_id',
                           textField:'region_name',
                           editable: false,
                           panelHeight:'auto',
                           data: data
                       });
                        $('#county').combobox({
                               valueField:'region_id',
                               textField:'region_name',
                               editable: false,
                               panelHeight:'auto',
                               data: []
                        });
                        $('#city').combobox('setValues','');
                        $('#county').combobox('setValues','');
                    }
            });
        }
    });
    $('#city').combobox({
        onChange: function (n,o) {
            var city_id = $('#city').combobox('getValue');
            $.ajax({
                   url:'<?php echo yii::$app->urlManager->createUrl(['charge/charge-station/get-region-list']); ?>',
                   type:'get',
                   data:{parent_id:city_id},
                   dataType:'json',
                   success:function(data){
                        $('#county').combobox({
                           valueField:'region_id',
                           textField:'region_name',
                           editable: false,
                           panelHeight:'auto',
                           data: data
                       });
                        $('#county').combobox('setValues','');
                    }
            });
        }
    });
*/

    //获取选择的记录
    CarFinanceRentIndex.getSelected = function(){
        var datagrid = $('#easyui-datagrid-finance-rent-index');
        var selectRow = datagrid.datagrid('getSelected');
        if(!selectRow){
            $.messager.alert('错误','请选择要操作的记录','error');   
            return false;
        }
        return selectRow;
    }
  	//增加
    CarFinanceRentIndex.add = function(){
        $('#easyui-dialog-finance-rent-index-add').dialog('open');
        $('#easyui-dialog-finance-rent-index-add').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/finance-rent/add']); ?>");
    }
  	//编辑
    CarFinanceRentIndex.edit = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var id = selectRow.id;
        $('#easyui-dialog-finance-rent-index-edit').dialog('open');
        $('#easyui-dialog-finance-rent-index-edit').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/finance-rent/edit']); ?>&id="+id);
    }
    //删除融资租赁公司
	CarFinanceRentIndex.del = function(){
		var selectRow = this.getSelected();
        if(!selectRow) return false;
        var id = selectRow.id;
		$.messager.confirm('确定删除','您确定要删除该负责人？',function(r){
			if(r){
				$.ajax({
					type: 'post',
					url: '<?php echo yii::$app->urlManager->createUrl(['car/finance-rent/del']); ?>&id='+id,
					data: {id: id},
					dataType: 'json',
					success: function(data){
						if(data.status){
							$.messager.alert('删除成功',data.info,'info');   
							$('#easyui-datagrid-finance-rent-index').datagrid('reload');
						}else{
							$.messager.alert('删除失败',data.info,'error');   
						}
					}
				});
			}
		});
	}
	//关联车俩管理
    CarFinanceRentIndex.relCar = function(){
        var selectRow = this.getSelected();
        if(!selectRow){
            return false;
        }
        var id = selectRow.id;
        $('#easyui-dialog-finance-rent-index-rel-car').dialog('open');
        $('#easyui-dialog-finance-rent-index-rel-car').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/finance-rent/rel-car']); ?>&id="+id);
    }

  	//
    /*CarFinanceRentIndex.reladd = function(){
        $('#easyui-dialog-finance-rent-index-reladd').dialog('open');
        $('#easyui-dialog-finance-rent-index-reladd').dialog('refresh',"<?php echo yii::$app->urlManager->createUrl(['car/finance-rent/rel-add']); ?>");
    }*/
    //重置查询表单
    CarFinanceRentIndex.resetForm = function(){
        var easyuiForm = $('#search-form-finance-rent-index');
        easyuiForm.form('reset');
    }

    
</script>