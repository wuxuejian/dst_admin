<table id="easyui-datagrid-car-attachment-index"></table> 
<div id="easyui-datagrid-car-attachment-index-toolbar">
    <div class="easyui-panel" title="数据检索" style="width:100%" data-options="
        iconCls: 'icon-search',
        border: false
    ">   
        <div class="data-search-form">
            <form id="search-form-car-baseinfo-index">
                <ul class="search-main">
                    <li>
                        <div class="item-name">附件名称</div>
                        <div class="item-input">
                            <input class="easyui-textbox" type="text" name="licence_plate" style="width:150px;"></input>
                        </div>
                    </li>
                    <li>
                        <div class="item-name">上传时间</div>
                        <div class="item-input">
                            <input class="easyui-datebox" type="text" name="update_time_start" style="width:150px;"></input> 
                        </div>
                    </li>
                    <li>
                        <div class="item-name">上传时间</div>
                        <div class="item-input">
                            <input class="easyui-datebox" type="text" name="update_time_end" style="width:150px;"></input>
                        </div>
                    </li>
                    <li class="search-button">
                        <a id="btn" href="javascript:CarBaseinfoIndex.search()" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
                    </li>
                </ul>
            </form>
        </div>
    </div>
    <div class="easyui-panel" title="数据列表" style="padding:4px 0px;width:100%" data-options="
        iconCls: 'icon-tip',
        border: false
    ">
        <a href="javascript:CarAttachmentIndex.add()" class="easyui-linkbutton" data-options="iconCls:'icon-add'">添加</a>
        <a href="javascript:CarAttachmentIndex.remove()" class="easyui-linkbutton" data-options="iconCls:'icon-remove'">删除</a>
        <a href="javascript:CarAttachmentIndex.download()" class="easyui-linkbutton" data-options="iconCls:'icon-remove'">下载</a>
    </div>
</div>
<div id="easyui-dialog-car-attachment-index-add"></div>
<script>
	var CarAttachmentIndex = new Object();
	CarAttachmentIndex.init = function(){
		//获取列表数据
		$('#easyui-datagrid-car-attachment-index').datagrid({   
		    url:'<?php echo yii::$app->urlManager->createUrl(['car/attachment/get-list-single','carId'=>$carId]); ?>',   
			fit: true,
			border: false,
			toolbar: "#easyui-datagrid-car-attachment-index-toolbar",
			pagination: true,
			loadMsg: '数据加载中...',
			striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: true,
            frozenColumns: [[
                {field: 'ck',checkbox: true}, 
                {field: 'id',title: 'id',width: 100},   
                {field: 'name',title: '附件名称',width: 200},   
            ]],
		    columns:[[
		        {field: 'brand',title: '文件路径',width: 300,align: 'left'},
		        {
			        field: 'upload_time',title: '上传时间',width: 160,align: 'left',
			        formatter: function(value,row,index){
						return formatDate(value);
    		        }
    	        }
		    ]]   
		});
		//初始化添加窗口
		$('#easyui-dialog-car-attachment-index-add').dialog({
        	title: '上传附件',   
            width: 650,   
            height: 450,   
            closed: false,   
            cache: true,   
            modal: true,
            buttons: [{
				text:'确定',
				iconCls:'icon-ok',
				handler:function(){
					CarAttachmentAdd.uploadFile();
				}
			},{
				text:'取消',
				iconCls:'icon-cancel',
				handler:function(){
					$('#easyui-dialog-car-attachment-index-add').dialog('close');
				}
			}],
			closed: true  
        });
	}
	CarAttachmentIndex.init();
	//获取选择的记录
	CarAttachmentIndex.getSelected = function(){
		var datagrid = $('#easyui-datagrid-car-attachment-index');
		var selectRow = datagrid.datagrid('getSelected');
		if(!selectRow){
			$.messager.alert('错误','请选择要操作的记录','error');   
			return false;
		}
		return selectRow.id;
	}
	//添加方法
	CarAttachmentIndex.add = function(){
		$('#easyui-dialog-car-attachment-index-add').dialog('open');
		$('#easyui-dialog-car-attachment-index-add').dialog('refresh','<?php echo yii::$app->urlManager->createUrl(['car/attachment/add','carId'=>$carId]); ?>');
	}
	//删除
	CarAttachmentIndex.remove = function(){
		
	}
	//查询
	CarAttachmentIndex.search = function(){
		var form = $('#search-form-car-attachment-index');
		var searchCondition = {};
		var licence_plate = form.find('input[name=licence_plate]').val();
		if(licence_plate){
			searchCondition.licence_plate = licence_plate;
		}
		$('#easyui-datagrid-car-baseinfo-index').datagrid('load',searchCondition);
	}
</script>