<table id="vipVipNoticeIndex_datagrid"></table> 
<div id="vipVipNoticeIndex_datagridToolbar">
    <div class="easyui-panel" title="检索区域" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="vipVipNoticeIndex_searchFrom">
                <ul class="search-main">                                   
					<li>
                        <div class="item-name">通知编号</div>
                        <div class="item-input">
                            <input
                                class="easyui-textbox"
                                type="text"
                                name="vn_code"
                                style="width:100%;"
                                data-options="{
                                    onChange: function(){
                                        vipVipNoticeIndex.search();
                                    }
                                }"
                            />
                        </div>
                    </li>                    
					<li>
                        <div class="item-name">通知标题</div>
                        <div class="item-input">
                            <input
                                class="easyui-textbox"
                                type="text"
                                name="vn_title"
                                style="width:100%;"
                                data-options="{
                                    onChange: function(){
                                        vipVipNoticeIndex.search();
                                    }
                                }"
                            />
                        </div>
                    </li>                    
					<li>
                        <div class="item-name">通知类型</div>
                        <div class="item-input">
                            <select
                                class="easyui-combobox"
                                name="vn_type"
                                style="width:100%;"
                                data-options="{
                                    panelHeight:'auto',editable:false,
                                    onChange: function(){
                                        vipVipNoticeIndex.search();
                                    }
                                }">
                                <option value="">--不限--</option>
                                <?php foreach($config['vn_type'] as $val){ ?>
                                    <option value="<?= $val['value'] ?>"><?= $val['text'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </li>
                    <li class="search-button">
                        <button class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</button>
                        <button onclick="vipVipNoticeIndex.reset();return false;" class="easyui-linkbutton" data-options="iconCls:'icon-reload'">重置</button>
                    </li>
                </ul>
            </form>
        </div>
    </div>
	
    <div class="easyui-panel" title="数据列表" style="padding:3px 2px;width:100%;" data-options="
        iconCls: 'icon-table-list',
        border: false
    ">
	<?php 
		if($buttons && !empty($buttons)){
			foreach($buttons as $val){ 				
				echo '<button onclick="' . $val['on_click'] . '" class="easyui-linkbutton" data-options="iconCls:\'' . $val['icon'] . '\'">' . $val['text'] . '</button> ';
			} 
		}
	?>
	</div>
</div>
<!-- 窗口 -->
<div id="vipVipNoticeIndex_addWindow"></div>
<div id="vipVipNoticeIndex_editWindow"></div>
<!-- 窗口 -->

<script>
    // 请求的URL
    var vipVipNoticeIndex_URL_getList = "<?php echo yii::$app->urlManager->createUrl(['vip/vip-notice/get-list']); ?>";
    var vipVipNoticeIndex_URL_remove = "<?php echo yii::$app->urlManager->createUrl(['vip/vip-notice/remove']); ?>";
    var vipVipNoticeIndex_URL_exportGridData = "<?php echo yii::$app->urlManager->createUrl(['vip/vip-notice/export-grid-data']); ?>";
    // 配置项目
    var vipVipNoticeIndex_CONFIG = <?php echo json_encode($config); ?>;

    var vipVipNoticeIndex = {
        params: {
            url: {
                add: "<?php echo yii::$app->urlManager->createUrl(['vip/vip-notice/add']); ?>",
                edit: "<?= yii::$app->urlManager->createUrl(['vip/vip-notice/edit']); ?>"
            }
        },
		// 初始化
		init: function(){
            var easyuiDatagrid = $('#vipVipNoticeIndex_datagrid');
			// 初始化表格
			easyuiDatagrid.datagrid({  
				method: 'get', 
				url: vipVipNoticeIndex_URL_getList,
				fit: true,
				border: false,
				toolbar: "#vipVipNoticeIndex_datagridToolbar",
				pagination: true,
				loadMsg: '数据加载中...',
				striped: true,
				checkOnSelect: true,
				rownumbers: true,
				singleSelect: false,
				pageSize: 20,
				frozenColumns: [[
					{field: 'ck',checkbox: true}, 
					{field: 'vn_id',title: 'ID',width:40,align:'center',hidden:true},
                    {field:'vn_code',title: '通知编号',width: 140,align:'center',sortable:true},
                    {field:'vn_title',title: '通知标题',width: 250,halign:'center',sortable:true}
				]],
				columns:[[
                    {field: 'vn_icon_path', title: '照片', width: 50, align: 'center', sortable: true,
                        formatter: function(value,row,index){
                            var str =  '<span class="easyui-tooltip" tipRowIndex='
                                    + index
                                    + ' style="color:blue;cursor:pointer;padding-left:5px;line-height:18px;" >'
                                    + '<img src="./jquery-easyui-1.4.3/themes/icons/large_picture.png"  width="12" height="12" />'
                                    + '</span>';
                            return str;
                        }
                    },
					{field:'vn_type',title: '通知类型',width: 90,align:'center',sortable:true,
                        formatter:function(value,row,index){
                            var str = 'vipVipNoticeIndex_CONFIG.vn_type.' + value + '.text';
                            return eval(str);
                        }
                    },
                    {field:'vn_public_time',title: '发布时间',width: 140,align:'center',sortable:true},
					{field:'vn_mark',title: '备注',width: 200,halign:'center',sortable:true},
					{field:'vn_systime',title: '创建时间',align:'center',width: 140,sortable:true,
						formatter: function(value,row,index){
							return formatDateToString(value,true);
						}
					},
                    {field:'vn_sysuser',title: '创建人员',align:'center',width: 140,sortable:true}
				]],
                onLoadSuccess:function(){ // 表格数据加载成功后，设置悬浮提示框！
                    var rows = $(this).datagrid('getRows');
                    $(this).datagrid('getPanel').find('.easyui-tooltip').each(function(){
                        var index = parseInt($(this).attr('tipRowIndex'));
                        var row = rows[index];
                        var vn_icon_path = row.vn_icon_path;
                        $(this).tooltip({
                            position: 'right',
                            content : $('<div style="padding:3px 1px;font-size:90%;"></div>'),
                            onUpdate: function(cc){
                                var contStr = '<div style="padding:3px;text-align: center;">';
                                if(vn_icon_path){
                                    contStr += '<img src="' + vn_icon_path + '" width="100" height="100" style="margin:3px;" />';
                                }else{
                                    contStr += '<div style="text-align:center;height:50px;line-height:50px;">无缩略图！</div>';
                                }
                                contStr += '</div>';
                                cc.panel({
                                    title: '<div style="text-align:center">缩略图</div>',
                                    width: 230,
                                    minHeight: 50,
                                    content: contStr
                                });
                            }
                        });
                    });
                }
			});
            var searchForm = $('#vipVipNoticeIndex_searchFrom');
            searchForm.submit(function(){
                var data = {};
                var searchCondition = $(this).serializeArray();
                for(var i in searchCondition){
                    data[searchCondition[i]['name']] = searchCondition[i]['value'];
                }
                easyuiDatagrid.datagrid('load',data);
            return false;
                return false;
            });
            //初始化新增/修改窗口
            $('#vipVipNoticeIndex_addWindow').dialog({
                title: '新增通知',
                width: 1000,
                height: 550,
                closed: true,
                cache: true,
                modal: true,
                maximizable: true,
                onClose: function(){
                    // 编辑器
                    try{
                        if(typeof(vipVipNoticeIndex_AddWindow_ueditor) != 'undefined'){
                            vipVipNoticeIndex_AddWindow_ueditor.destroy();
                        }
                    }catch(e){}
                    $(this).dialog('clear');
                },
                buttons: [{
                    text:'确定',
                    iconCls:'icon-ok',
                    handler: function(){
                        var _form = $('#easyui_form_vip_notice_add');
                        if(!_form.form('validate')){
                            $.messager.show({
                                title: '验证不通过',
                                msg: '请检查表单是否填写完整或填写错误！'
                            });
                            return false;
                        }
                        $.ajax({
                            type: 'post',
                            url: vipVipNoticeIndex.params.url.add,
                            data: _form.serialize(),
                            dataType: 'json',
                            success: function(data){
                                if(data.status){
                                    $.messager.show({
                                        title: '新增成功',
                                        msg: data.info
                                    });
                                    $('#vipVipNoticeIndex_addWindow').dialog('close');
                                    $('#vipVipNoticeIndex_datagrid').datagrid('reload');
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
                        $('#vipVipNoticeIndex_addWindow').dialog('close');
                    }
                }]
            });
            //初始化修改窗口
            $('#vipVipNoticeIndex_editWindow').dialog({
                title: '修改通知',
                width: 1000,
                height: 550,
                closed: true,
                cache: true,
                modal: true,
                maximizable: true,
                onClose: function(){
                    // 编辑器
                    try{
                        if(typeof(vipVipNoticeIndex_EditWindow_ueditor) != 'undefined'){
                            vipVipNoticeIndex_EditWindow_ueditor.destroy();
                        }
                    }catch(e){}
                    $(this).dialog('clear');
                },
                buttons: [{
                    text:'确定',
                    iconCls:'icon-ok',
                    handler: function(){
                        var _form = $('#easyui_form_vip_notice_edit');
                        if(!_form.form('validate')){
                            $.messager.show({
                                title: '验证不通过',
                                msg: '请检查表单是否填写完整或填写错误！'
                            });
                            return false;
                        }
                        $.ajax({
                            type: 'post',
                            url: vipVipNoticeIndex.params.url.edit,
                            data: _form.serialize(),
                            dataType: 'json',
                            success: function(data){
                                if(data.status){
                                    $.messager.show({
                                        title: '修改成功',
                                        msg: data.info
                                    });
                                    $('#vipVipNoticeIndex_editWindow').dialog('close');
                                    $('#vipVipNoticeIndex_datagrid').datagrid('reload');
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
                        $('#vipVipNoticeIndex_editWindow').dialog('close');
                    }
                }]
            });
        },
        //获取当前所选择的记录
        getCurrentSelected: function(multiline){
            var datagrid = $('#vipVipNoticeIndex_datagrid');
            var selectRows = datagrid.datagrid('getSelections');
            if(selectRows.length <= 0){
                $.messager.show({
                    title: '请选择',
                    msg: '请先选择要操作的记录！'
                });
                return false;
            }
            if(multiline){
                return selectRows;
            }else{
                if(selectRows.length > 1){
                    $.messager.show({
                        title: '提醒',
                        msg: '该功能不能批量操作！<br/>如果你选择了多条记录，则默认操作的是第一条记录！'
                    });
                }
                return selectRows[0];
            }
        },
        // 新建通知
        add: function(){
            $('#vipVipNoticeIndex_addWindow')
                .dialog('open')
                .dialog('refresh',this.params.url.add);
        },
        // 修改通知
        edit: function(){
            var selectRow = this.getCurrentSelected();
            if(!selectRow) return false;
            var vn_id = selectRow.vn_id;
            $('#vipVipNoticeIndex_editWindow')
                .dialog('open')
                .dialog('refresh', vipVipNoticeIndex.params.url.edit + '&vn_id=' + vn_id);
        },
        // 删除通知
        remove: function(){
            var selectRows = this.getCurrentSelected(true);
            if(!selectRows) return false;
            $.messager.confirm('确认删除','你确定是要删除所选数据吗？',function(r){
                if(r){
                    var idStr = '';
                    for(var i in selectRows){
                        idStr += selectRows[i].vn_id + ',';
                    }
                    $.ajax({
                        type: 'post',
                        url: vipVipNoticeIndex_URL_remove,
                        data: {'idStr': idStr},
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.show({
                                    title: '删除成功',
                                    msg: data.info
                                });
                                $('#vipVipNoticeIndex_datagrid').datagrid('reload');
                            }else{
                                $.messager.alert('错误',data.info,'error');
                            }
                        }
                    });
                }
            });
        },
		// 查询
		search: function(){
			$('#vipVipNoticeIndex_searchFrom').submit();
		},
		// 重置
		reset: function(){
            var searchForm = $('#vipVipNoticeIndex_searchFrom');
			searchForm.form('reset');
            searchForm.submit();
		},
		// 导出
		exportGridData: function(){
			var form = $('#vipVipNoticeIndex_searchFrom');
			var searchConditionStr = form.serialize();
			window.open(vipVipNoticeIndex_URL_exportGridData + "&" + searchConditionStr);
		}
	}
	
	// 执行初始化函数
	vipVipNoticeIndex.init();
	
</script>