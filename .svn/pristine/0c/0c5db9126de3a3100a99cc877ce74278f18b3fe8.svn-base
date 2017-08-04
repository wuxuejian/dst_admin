<script type="text/javascript" src="<?= yii::getAlias('@web'); ?>/jquery-easyui-1.4.3/plugins/jquery.datagrid_detailview.js"></script>

<table id="vip_index_datagrid"></table> 
<div id="vip_index_datagrid_toolbar">
    <div class="easyui-panel" title="检索区域" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="vip_index_searchFrom">
                <ul class="search-main">
                    <li>
                        <div class="item-name">会员编号</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="code" style="width:100%;"
                                data-options="
                                    onChange:function(){
                                        vipIndex.search();
                                    }
                                "
                                />
                        </div>
                    </li>                    
					<li>
                        <div class="item-name">会员名称</div>
                        <div class="item-input">
                           <input class="easyui-textbox" type="text" name="client" style="width:100%;"
                                  data-options="
                                    onChange:function(){
                                        vipIndex.search();
                                    }
                                "
                               />
                        </div>
                    </li>                    
					<li>
                        <div class="item-name">手机号</div>
                        <div class="item-input">
                           <input class="easyui-textbox" type="text" name="mobile" style="width:100%;"
                                  data-options="
                                    onChange:function(){
                                        vipIndex.search();
                                    }
                                "
                               />
                        </div>
                    </li>                    
					<li>
                        <div class="item-name">车牌号</div>
                        <div class="item-input">
                           <input class="easyui-textbox" type="text" name="vehicle" style="width:100%;"
                                  data-options="
                                    onChange:function(){
                                        vipIndex.search();
                                    }
                                "
                               />
                        </div>
                    </li>  
					<li>
                        <div class="item-name">电卡编号</div>
                        <div class="item-input">
                           <input class="easyui-textbox" type="text" name="card_no" style="width:100%;"
                                  data-options="
                                    onChange:function(){
                                        vipIndex.search();
                                    }
                                "
                               />
                        </div>
                    </li>
                    <li>
                        <div class="item-name">登记日期</div>
                        <div class="item-input">
							<input class="easyui-datebox" type="text" name="systime_start" style="width:90px;"
                                   data-options="
                                    onChange:function(){
                                        vipIndex.search();
                                    }
                                "
                            />
                            -
							<input class="easyui-datebox" type="text" name="systime_end" style="width:90px;"
                                   data-options="
                                    onChange:function(){
                                        vipIndex.search();
                                    }
                                "
                            />
                        </div>               
                    </li>
                    <li class="search-button">
                        <a href="javascript:void(0)" onclick="vipIndex.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                        <a href="javascript:void(0)" onclick="vipIndex.reset()" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</a>
                    </li>
                </ul>
            </form>
        </div>
    </div>

    <?php if($buttons){ ?>
        <div class="easyui-panel" title="会员列表" style="padding:3px 2px;width:100%;" data-options="
            iconCls: 'icon-table-list',border: false">
            <?php foreach($buttons as $val){ ?>
                <a href="javascript:void(0)" onclick="<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon']; ?>'"><?= $val['text']; ?></a>
            <?php } ?>
        </div>
    <?php } ?>
</div>
<!-- 窗口 -->
<div id="vip_index_datagrid_add_edit_win"></div>
<div id="easyui_dialog_vip_vip_index_reset_pwd"></div>
<div id="easyui_dialog_vip_vip_index_change_count_money"></div>
<!-- 窗口 -->

<script>
	var vipIndex = new Object();
	//var connection_type = <?= json_encode($config['connection_type']); ?>;
	vipIndex.params =  {
        url: {
            resetPwd: "<?= yii::$app->urlManager->createUrl(['vip/vip/reset-pwd']); ?>",
            changeCountMoney: "<?= yii::$app->urlManager->createUrl(['vip/vip/change-count-money']); ?>"
        }
    };
	vipIndex.init = function(){
        
		//获取列表数据
		$('#vip_index_datagrid').datagrid({  
			method: 'get', 
		    url:'<?php echo yii::$app->urlManager->createUrl(['vip/vip/get-list']); ?>',   
			fit: true,
			border: false,
			toolbar: "#vip_index_datagrid_toolbar",
			pagination: true,
			loadMsg: '数据加载中...',
			striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
			pageSize: 20,
            showFooter: true,
            frozenColumns: [[
                {field: 'ck',checkbox: true}, 
                {field: 'id',title: 'ID',width:40,align:'center',hidden:true},
                {field: 'code',title: '会员编号',width: 140,align:'center',sortable:true}
            ]],
		    columns:[[
		        {field: 'client',title: '会员名称',width: 90,align:'center',sortable:true},
                {field: 'mobile',title: '手机号',width: 80,align:'center',sortable:true},
		        {field: 'sex',title: '性别',width: 40,align:'center',sortable:true,
					formatter:function(value,row,index){
                        if(value == 1){
                            return '男';
                        }else if(value == 0){
                            return '女';
                        }
					}
				},
				{field: 'email',title: '邮箱',width: 120,align:'center',sortable:true},
				{field: 'card_no',title: '电卡编号',width: 120,align:'center',sortable:true},
				{field: 'money_acount',title: '账户余额',width: 90,halign:'center',align:'right',sortable:true},
				{field: 'mark',title: '备注',width: 200,halign:'center'},
		        {field: 'systime',title: '登记日期',align:'center',width: 90,sortable:true,
					formatter: function(value,row,index){
                        if(value){
                            return formatDateToString(value);
                        }
    		        }
				}
		    ]],
			onDblClickCell: function(rowIndex,fieldName,fieldValue){
				if(fieldName != '_expander'){
					vipIndex.edit();
				}
            },
			view: detailview, //行扩展，要对应引入插件
			detailFormatter: function(rowIndex, rowData){
				var str = '<div style="padding:0px;background-color:#E4F0FD;"> ';
					str += '<table cellpadding=5 cellspacing=0 border=0 width="100%">';
					str += 	'<tr style="color:red;background-color:#C4DDFA;">';
					str +=  	'<th align="center" width=100>车牌号</th><th align="center" width=150>车型</th><th align="center">备注</th>';
					str +=  '</tr>';
				if(rowData.vehicle && rowData.vehicle.length > 0){
					for(var i in rowData.vehicle){		
						// var tmpStr = eval('connection_type.' + rowData.vehicle[i].vhc_con_type + '.text'); //充电连接方式
						str +=	'<tr>';
						str +=		'<td align="center" style="padding:5px;border:0;"><span style="padding:3px;background-color:#1F4696;color:white;border:1px groove white;">' + rowData.vehicle[i].vehicle + '</span></td>';
						str +=		'<td align="center" style="padding:5px;border:0;">' + rowData.vehicle[i].vhc_model + '</td>';
						str +=		'<td align="center" style="padding:5px;border:0;">' + rowData.vehicle[i].mark + '</td>';
						str += 	'</tr>';
					}
				}
				str += '</table>';
				str += '</div>';
				return str;
			}
		});
		
        //初始化新增/修改窗口
		$('#vip_index_datagrid_add_edit_win').dialog({
        	title: '新增/修改会员',   
            width: 1000,   
            height: 500,
            closed: false,   
            cache: true,   
            modal: true,
            buttons: [{
				id:'vipInfoWin_saveBtn',
				text:'确定',
				iconCls:'icon-ok',
                handler: function(){
                    var _form = $('#vipInfoWin_baseInfo');
                    var _grid = $('#vipInfoWin_datagrid');
                    if(!_form.form('validate')){
                        $.messager.alert('警告','表单验证未通过，请仔细检查！','warning');
                        return false;
                    }
                    var _gridData = _grid.edatagrid('getData');
                    if(_gridData.total > 0){
                        for(var i=0;i<_gridData.total;i++){
                            if(!_grid.edatagrid('validateRow',i)){ //行验证
                                $.messager.alert('警告','车辆列表：第'+(i+1)+'行校验不通过！','warning');
                                return false;
                            }else{
                                _grid.edatagrid('endEdit',i);//结束行编辑状态并临时保存
                            }
                        }
                    }else{
                        //$.messager.alert('警告','车辆列表：列表不能为空！','warning');
                        //return false;
                    }
                    var data = {};
                    data.formData = _form.serialize();
                    data.gridData = _gridData.rows;
                    var id = parseInt($('input[name=id]',_form).val());
                    if(id){
                        var _url = '<?php echo yii::$app->urlManager->createUrl(['vip/vip/edit']); ?>';
                    }else{
                        var _url = '<?php echo yii::$app->urlManager->createUrl(['vip/vip/add']); ?>';
                    }
                    $.ajax({
                        type: 'post',
                        url: _url,
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $('#vip_index_datagrid').datagrid('reload');
                                $('#vip_index_datagrid_add_edit_win').dialog('close');
                            }else{
                                $.messager.alert('错误',data.info,'error');
                            }
                        }
                    });
                }
			},{
				text:'取消',
				iconCls:'icon-cancel',
				handler:function(){
					$('#vip_index_datagrid_add_edit_win').dialog('close');
				}
			}],
			closed: true  
        });
        //初始化修改密码窗口
        $('#easyui_dialog_vip_vip_index_reset_pwd').dialog({
            title: '修改会员密码',   
            width: 680,   
            height: 130,
            closed: false,   
            cache: true,   
            modal: true,
            closed: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler: function(){
                    var easyuiForm = $('#vip_vip_reset_pwd');
                    if(!easyuiForm.form('validate')){
                        $.messager.show({
                            title:'数据填写有误',
                            msg:'请确认表单数据填写正确！'
                        });
                        return false;
                    }
                    $.ajax({
                        type: 'post',
                        url: vipIndex.params.url.resetPwd,
                        data: easyuiForm.serialize(),
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('操作成功',data.info,'info');
                                $('#easyui_dialog_vip_vip_index_reset_pwd').dialog('close');
                            }else{
                                $.messager.alert('操作失败',data.info,'error');
                            }
                        }
                    });
                }
            },{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui_dialog_vip_vip_index_reset_pwd').dialog('close');
                }
            }]
        });
        //修改会员余额
        $('#easyui_dialog_vip_vip_index_change_count_money').dialog({
            title: '修改会员余额',   
            width: 680,   
            height: 200,
            closed: false,   
            cache: true,   
            modal: true,
            closed: true,
            buttons: [{
                text:'确定',
                iconCls:'icon-ok',
                handler: function(){
                    var easyuiForm = $('#vip_vip_change_count_money');
                    if(!easyuiForm.form('validate')){
                        $.messager.show({
                            title:'数据填写有误',
                            msg:'请确认表单数据填写正确！'
                        });
                        return false;
                    }
                    $.ajax({
                        type: 'post',
                        url: vipIndex.params.url.changeCountMoney,
                        data: easyuiForm.serialize(),
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('操作成功',data.info,'info');
                                $('#easyui_dialog_vip_vip_index_change_count_money').dialog('close');
                                $('#vip_index_datagrid').datagrid('reload');
                            }else{
                                $.messager.alert('操作失败',data.info,'error');
                            }
                        }
                    });
                }
            },{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui_dialog_vip_vip_index_change_count_money').dialog('close');
                }
            }]
        });
	}
	
	vipIndex.init();


    //获取选择的记录
    vipIndex.getSelected = function(){
        var datagrid = $('#vip_index_datagrid');
        var selectRow = datagrid.datagrid('getSelected');
        if(!selectRow){
            $.messager.show({
                title:'操作失败',
                msg:'请选择您要操作的记录！'
            });
            return false;
        }
        return selectRow.id;
    }

    //添加
	vipIndex.add = function(){
		$('#vip_index_datagrid_add_edit_win')
            .dialog('open')
		    .dialog('refresh','<?php echo yii::$app->urlManager->createUrl(['vip/vip/add']); ?>')
		    .dialog('setTitle','新增会员');
	}

	//修改
	vipIndex.edit = function(id){
        id = id || this.getSelected();
		if(!id){
			return;
		}
		$('#vip_index_datagrid_add_edit_win')
            .dialog('open')
            .dialog('refresh','<?php echo yii::$app->urlManager->createUrl(['vip/vip/edit']); ?>'+'&id='+id)
            .dialog('setTitle','修改会员');
	}

	//删除
	vipIndex.remove = function(){
		var id = this.getSelected();
		if(!id){
			return;
		}
		$.messager.confirm('确定删除','您确定要删除该会员数据？',function(r){
			if(r){
				$.ajax({
					type: 'get',
					url: '<?php echo yii::$app->urlManager->createUrl(['vip/vip/remove']); ?>',
					data: {id: id},
					dataType: 'json',
					success: function(data){
						if(data){
							$.messager.alert('提示',data.info,'info');   
							$('#vip_index_datagrid').datagrid('reload');
						}else{
							$.messager.alert('错误',data.info,'error');   
						}
					}
				});
			}
		});
	}
	//查询
	vipIndex.search = function(){
		var form = $('#vip_index_searchFrom');
		var data = {};
		var searchCondition = form.serializeArray(); 
		for(var i in searchCondition){
			data[searchCondition[i]['name']] = $.trim(searchCondition[i]['value']);
		}
		$('#vip_index_datagrid').datagrid('load',data);
	}
	
	//重置
	vipIndex.reset = function(){
		$('#vip_index_searchFrom').form('reset');
        vipIndex.search();
	}
	
	//导出
    vipIndex.exportGridData = function(){
		var form = $('#vip_index_searchFrom');
		var str = form.serialize();
        window.open("<?php echo yii::$app->urlManager->createUrl(['vip/vip/export-grid-data']); ?>&" + str);
    }
    //修改密码
    vipIndex.resetPwd = function(){
        var id = this.getSelected();
        if(!id){
            return false;
        }
        $('#easyui_dialog_vip_vip_index_reset_pwd')
            .dialog('open')
            .dialog('refresh',this.params.url.resetPwd+'&id='+id);
    }
    //改变用户余额
    vipIndex.changeCountMoney = function(){
        var id = this.getSelected();
        if(!id){
            return false;
        }
        $('#easyui_dialog_vip_vip_index_change_count_money')
            .dialog('open')
            .dialog('refresh',this.params.url.changeCountMoney+'&id='+id);
    }

	
</script>