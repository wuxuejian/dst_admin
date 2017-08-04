<table id="easyui-datagrid-process-inspection-edit"></table>
<!-- toolbar start -->
<div id="process-inspection-edit-toolbar"> 
    <form id="easyui-form-process-inspection-index-edit" class="easyui-form" method="post">
        <input type="hidden" name="id" />
        <div
            class="easyui-panel"
            title="抽检结果登记"    
            iconCls='icon-save'
            border="false"
            style="width:100%;"
        >
            <ul class="ulforform-resizeable">
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">车辆品牌</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-combotree" name="car_brand_id"
                           data-options="
                                width:160,
                                url: '<?php echo yii::$app->urlManager->createUrl(['car/combotree/get-car-brands']); ?>',
                                editable: false,
                                panelHeight:'auto',
                                lines:false,
                                required:true
                           "
                        />
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                	<div class="ulforform-resizeable-title">产品型号</div>
					<div class="ulforform-resizeable-input">
						<select
	                        class="easyui-combobox"
	                        style="width:160px;"
	                        name="car_model"
							editable="false"
							data-options="panelHeight:'auto',required:true"
	                    >
	                    	<option value=""></option>
	                        <?php foreach($config['car_model_name'] as $val){ ?>
	                        <option value="<?php echo $val['value']; ?>"><?php echo $val['value']; ?></option>
	                        <?php } ?>
	                    </select>
					</div>
                 </li>
                  <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">计划提车数量</div>
                    <div class="ulforform-resizeable-input">
                        <input
                            class="easyui-textbox"
                            style="width:160px;"
                            name="put_car_num"
                            required="true"
                            missingMessage="请输入计划提车数量！"
                        />
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">抽检数量</div>
                    <div class="ulforform-resizeable-input">
                        <input
                            class="easyui-textbox"
                            style="width:160px;"
                            name="inspection_num"
                            required="true"
                            missingMessage="请输入抽检数量！"
                        />
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">抽检负责人</div>
                    <div class="ulforform-resizeable-input">
                        <input
                            class="easyui-textbox"
                            style="width:160px;"
                            name="inspection_director_name"
                            required="true"
                            missingMessage="请输入抽检负责人！"
                        />
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">验车时间</div>
                    <div class="ulforform-resizeable-input">
                    	<input
	                        class="easyui-datebox"
	                        style="width:100px;"
	                        name="validate_car_time"
	                        validType="date"
	                        required="true"
	                    />
                    </div>
                </li>
                <li class="ulforform-resizeable-group-single">
                    <div class="ulforform-resizeable-title">抽检结果判定</div>
                    <div class="ulforform-resizeable-input">
                        <input
                        class="easyui-textbox"
                        name='inspection_result'
                        data-options="multiline:true"
                        style="height:60px;width:800px;"
                        required="true"
                        prompt="请填写抽检结果判定，500字符以内。"
                        validType="length[500]"
                        />
                    </div>
                </li>
                <li class="ulforform-resizeable-group-single">
	                <div class="ulforform-resizeable-title">上传故障照片</div>
	                <div class="ulforform-resizeable-input">
	                    <ul style="padding:0;margin:0;list-style:none;overflow:hidden;" id="process-inspection-edit-uploadfile">
	                        <?php
	                            $thumbs = [
	                                ['car_no_img','附件照片']
	                            ];
	                            foreach($thumbs as $item){
	                        ?>
	                            <li style="float:left;margin-right:16px;position:relative;cursor:pointer;">
	                                <div style="width:100px;height:100px;">
	                                    <img class="inspectionImg" src="<?php echo $inspection[$item[0]]!='' ? './uploads/image/inspection/'.$inspection[$item[0]] : './images/add.jpg'; ?>" width="100" height="100" />
	                                    <input type="hidden" name="<?php echo $item[0]; ?>" />
	                                </div>
	                                <div class="imgTitle" style="position:absolute;bottom:0;left:0;background:rgba(224,236,255,0.5);width:100px;text-align:center;line-height:24px;"><?php echo $item[1]; ?></div>
	                                <div class="removeIcon" style="position:absolute;top:0;right:0;background:rgba(224,236,255,0.5);display:none;"><img src="./jquery-easyui-1.4.3/themes/icons/clear.png" width="14px" height="14px" /></div>
	                            </li>
	                        <?php } ?>
	                    </ul>
	                </div>
	            </li>
            </ul>
        </div>
        <div style="border-top:1px solid #95B8E7;"></div>
        <div class="easyui-panel" title="登记车辆" style="padding:3px 2px;width:100%;" iconCls='icon-save' border="false">
                <a href="javascript:ProcessInspectionEdit.addCar()" class="easyui-linkbutton" data-options="iconCls:'icon-add'">添加</a>
                <a href="javascript:ProcessInspectionEdit.editCar()" class="easyui-linkbutton" data-options="iconCls:'icon-add'">批量修改</a>
                <a href="javascript:ProcessInspectionEdit.saveEdit()" class="easyui-linkbutton" data-options="iconCls:'icon-remove'">保存添加/修改</a>
        </div>
    </form>
</div>
<iframe id="iframe-process-inspection-edit-uploadimage" name="iframe-process-inspection-edit-uploadimage" style="display:none;"></iframe>
<div id="easyui-dialog-process-inspection-edit-uploadimage"></div>
<!-- toolbar end -->
<form style="display:none;" id="process-inspection-edit-submit-data"></form>
<script>
    var ProcessInspectionEdit = new Object();
    ProcessInspectionEdit.init = function(){
    	//初始化照片上传窗口
        $('#easyui-dialog-process-inspection-edit-uploadimage').dialog({
            title: '车辆故障照片上传',
            width: 500,
            height: 160,
            closed: false,
            cache: true,
            modal: true,
            closed: true,
            maximizable: false,
            minimizable: false,
            collapsible: false,
            draggable: false,
            buttons: [{
                text:'上传',
                iconCls:'icon-ok',
                handler:function(){
                    var form = $('#easyui-form-process-inspection-edit-upload-window');
                    if(!form.form('validate')){
                        return false;
                    }
                    form.submit();
                }
            },{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui-dialog-process-inspection-edit-uploadimage').dialog('close');
                }
            }],
            onClose: function(){
                $(this).window('clear');
            }
        });

        //给上传故障图片绑定各类事件
        $('#process-inspection-edit-uploadfile').children('li')
            .click(function(){ //单击打开上传窗口
                var columnName = $(this).find('input').attr('name');
                $('#easyui-dialog-process-inspection-edit-uploadimage')
                    .dialog('open')
                    .dialog('refresh',"<?= yii::$app->urlManager->createUrl(['process/inspection/upload-window']); ?>&isEdit=1&columnName="+columnName);
            })
            .mouseover(function(){
                var imgSrc = $(this).find('img.inspectionImg').attr('src');
                if(imgSrc != './images/add.jpg'){
                    //显示删除图标并绑定删除事件
                    $(this).find('div.removeIcon').show().click(function(e){
                        e.stopPropagation();
                        $(this).parent().find('img.inspectionImg').attr('src','./images/add.jpg');
                        $(this).parent().find('input').val('');
                    });
                }
            })
            .mouseleave(function(){
                $(this).find('div.removeIcon').hide();
            });

        // 放大显示上传图片
        $('#process-inspection-edit-uploadfile').children('li').each(function(){
            var imgSrc = $(this).find('img.inspectionImg').attr('src');
            if(imgSrc != './images/add.jpg') {
                $(this).tooltip({
                    position: 'top',
                    content: '<img src="' + imgSrc + '" width="350px" height="350px" border="0" />'
                });
            }
        });
        //初始化datagrid
        $('#easyui-datagrid-process-inspection-edit').datagrid({
            method: 'get', 
            url:"<?php echo yii::$app->urlManager->createUrl(['process/inspection/get-car-list','inspectionId'=>$inspectionId]); ?>",  
            toolbar: "#process-inspection-edit-toolbar",
            border: false,
            fit: true,
            pagination: true,
            loadMsg: '数据加载中...',
            striped: true,
            checkOnSelect: true,
            rownumbers: true,
            singleSelect: false,
            showFooter: true,
            frozenColumns: [[
                {field: 'ck',checkbox: true}, 
                {field: 'id',title: 'id',hidden: true},   
                {
                    field: 'vehicle_dentification_number',title: '车架号',width: '15%',sortable: true,
                    editor:{
                    	type:'textbox',
                        options:{
                        	validType: 'length[50]'
                        }
                    }
                }
            ]],
            columns:[[
				{
				    field:'note',title:'检验情况描述',width: '81%',halign: 'center',
				    editor:{
				        type:'textbox',
				        options:{
				            validType: 'length[255]'
				        }
				    }
				} 
            ]],
            onLoadSuccess: function (data) {
                //单元格内容悬浮提示，doCellTip()是在入口文件index.php中拓展的。
                $(this).datagrid('doCellTip', {
                    position: 'bottom',
                    maxWidth: '200px',
                    onlyShowInterrupt: true, //false时所有单元格都显示提示；true时配合specialShowFields自定义要提示的列
                    specialShowFields: [     //需要提示的列
                        //{field: 'company_name', showField: 'company_name'}
                    ],
                    tipStyler: {
                        backgroundColor: '#E4F0FC',
                        borderColor: '#87A9D0',
                        boxShadow: '1px 1px 3px #292929'
                    }
                });
            }
        });

        //表单赋值
        var oldData = <?php echo json_encode($inspection); ?>;
        setTimeout(function(){
        	$('#easyui-form-process-inspection-index-edit').form('load',oldData);
        },1000);

    }
    ProcessInspectionEdit.init();

    ProcessInspectionEdit.uploadComplete = function(rData){
        if(rData.status){
            $('#easyui-dialog-process-inspection-edit-uploadimage').dialog('close');
            var inputControl = $('#process-inspection-edit-uploadfile').find('input[name='+rData.columnName+']');
            inputControl.val(rData.info);
            inputControl.siblings('img').attr('src',rData.storePath);
            // 放大显示上传图片
            inputControl.parent().parent().tooltip({
                position: 'top',
                content: '<img src="' + rData.storePath + '" width="350px" height="350px" border="0" />'
            });
        }else{
            $.messager.alert('上传错误',rData.info,'error');
        }
    }
    //获取选择记录
    ProcessInspectionEdit.getSelected = function(multiple){
        var datagrid = $('#easyui-datagrid-process-inspection-edit');
        if(multiple){
            selectRows = datagrid.datagrid('getSelections');
            if(selectRows.length <= 0){
                $.messager.alert('错误','请选择要操作的记录！','error');   
                return false;
            }
            return selectRows;
        }else{
            var selectRow = datagrid.datagrid('getSelected');
            if(!selectRow){
                $.messager.alert('错误','请选择要操作的记录！','error');   
                return false;
            }
            return selectRow;
        }  
    }
    //登记车辆
    ProcessInspectionEdit.addCar = function(){
        var datagrid = $('#easyui-datagrid-process-inspection-edit');
        datagrid.datagrid('appendRow',{       
            id: '0',
            vehicle_dentification_number: '',
            note: ''
        });
        var rows = datagrid.datagrid('getRows');
        var lastRowNum = rows.length - 1;
        var lastRow = rows[lastRowNum];
        var rowIndex = datagrid.datagrid('getRowIndex',lastRow);
        datagrid.datagrid('beginEdit',rowIndex);
        datagrid.datagrid('selectRow',rowIndex);
    }
    //修改登记车辆
    ProcessInspectionEdit.editCar = function(){
        var selectRows = this.getSelected(true);
        if(!selectRows) return false;
        var datagrid = $('#easyui-datagrid-process-inspection-edit');
        for(var i in selectRows){
            var rowIndex = datagrid.datagrid('getRowIndex',selectRows[i]);
            datagrid.datagrid('beginEdit',rowIndex);
        }
    }
    //保存修改
    ProcessInspectionEdit.saveEdit = function()
    {
        var selectRows = this.getSelected(true);
        if(!selectRows) return false;
        var datagrid = $('#easyui-datagrid-process-inspection-edit');
        
        for(var i in selectRows){
            datagrid.datagrid('endEdit',datagrid.datagrid('getRowIndex',selectRows[i]));
        }
        var selectRows = this.getSelected(true);
        var html = '<input type="text" name="inspection_id" value="<?php echo $inspection["id"]; ?>" />';
        for(var i in selectRows){
            if(selectRows[i].vehicle_dentification_number){
                html += '<input type="text" name="id[]" value="'+selectRows[i].id+'" />';
                html += '<input type="text" name="vehicle_dentification_number[]" value="'+selectRows[i].vehicle_dentification_number+'" />';
                html += '<input type="text" name="note[]" value="'+selectRows[i].note+'" />';
            }
        }
        var form = $('#process-inspection-edit-submit-data');
        form.html(html);
        var data = form.serialize();
        $.ajax({
            type: 'post',
            url: "<?php echo yii::$app->urlManager->createUrl(['process/inspection/add-edit-car']); ?>",
            data: data,
            dataType: 'json',
            success: function(data){
                if(data.status){
                    $.messager.alert('操作成功',data.info,'info');
                    $('#easyui-datagrid-process-inspection-edit').datagrid('reload');
                }else{
                    $.messager.alert('操作失败',data.info,'error');
                }
            }
        });
    }
</script>