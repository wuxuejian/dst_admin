<script src="js/jquery.ajaxSubmit.js"></script>
<div style="padding:15px">
    <form action="<?php echo yii::$app->urlManager->createUrl(['parts/parts-info/much-import']); ?>" id="easyui-form-parts-info-import" class="easyui-form" method="post" enctype="multipart/form-data">
        <table cellpadding="8" cellspacing="0" style="height:10px;">
            <tr>
            	<td><br><br><div style="width:85px;margin-left: 20%;">导入CSV文件</div></td>
                <td colspan="3"><br><br>
                	<input type="file" name="append" id="append1">
                </td>
            </tr>
			<tr>
				<td>
	<!-- 				<div style="width:85px;text-align:right;">-->
						<a href='uploads/parts/parts.csv' id='openWindow' style="margin-left: 20%;">下载示例文件</a>
<!--					</div>  -->
				</td>
			</tr>
        </table>
    </form>
</div>

<script type="text/javascript">
//	var PartsInfoImport = new Object();
//提交表单
    PartsInfoIndex.import2 = function(){
    var form = $('#easyui-form-parts-info-import');
    if(!form.form('validate')){
        return false;
    }
    form.ajaxSubmit({
			dataType : "json",
			success : function(data){
                if(data.status){
                    $.messager.alert('新建成功',data.info,'info');
                    $('#easyui-dialog-parts-parts-info-index-import').dialog('close');
                    $('#easyui-datagrid-parts-parts-info-index').datagrid('reload');
                }else{
                    $.messager.alert('新建失败',data.info,'error');
                }
    },
			error: function(xhr){
        $('#loadTips').hide();
    }

		});
	}
</script>