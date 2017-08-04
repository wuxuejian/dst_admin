<script src="js/jquery.ajaxSubmit.js"></script>
<div style="padding:15px"> 
    <form action="<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/driving-license-import']); ?>" id="easyui-form-car-baseinfo-driving-license-import" class="easyui-form" method="post" enctype="multipart/form-data">
        <table cellpadding="8" cellspacing="0" style="height:10px;">
            <tr>
            	<td><div style="width:85px;text-align:right;">导入csv文件</div></td>
                <td colspan="3">
                	<input type="file" name="append" id="append1">
                </td>			
            </tr>  
			<tr>
				<td>
	<!-- 				<div style="width:85px;text-align:right;">-->
						 <a href='uploads/driving_license/ssz_eg.csv' id='openWindow'>下载示例文件</a>
<!--					</div>  -->
				</td>
			</tr>
        </table>
    </form>
</div>

<script type="text/javascript">
	//var CarBaseinfoIndex = new Object();
//提交表单
	CarBaseinfoIndex.drivingLicenseImport2 = function(){
	    var form = $('#easyui-form-car-baseinfo-driving-license-import');
	    if(!form.form('validate')){
	        return false;
	    }
	    form.ajaxSubmit({
			dataType : "json",
			success : function(data){
				if(data.status){
	                $.messager.alert('新建成功',data.info,'info');
	                $('#easyui-dialog-car-baseinfo-index-driving-license-import').dialog('close');
	                $('#easyui-datagrid-car-baseinfo-index').datagrid('reload');
	            }else{
	                $.messager.alert('新建失败',data.info,'error');
	            }
			},
			error: function(xhr){
				$('#loadTips').hide();
			}
			
		});
	}
	
	
    //CarBaseinfoIndex.init();
</script>