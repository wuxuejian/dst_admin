<!-- <div style="padding:10px 40px 20px 40px"> -->
    <form id="easyui-form-process-extract-add" class="easyui-form" method="post">
        <table cellpadding="5">
        	<tr>
                <td><div style="width:85px;text-align:right;">场站名称</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        name="name"
                        style="width:210px;"
                        required="true"
                        
                    />
                </td> 
                <td><div style="width:85px;text-align:right;">运营公司</div></td>
                <td>
                    <input class="easyui-combotree" name="operating_company_id" style="width:180px;" id="add_user_oc" 
                           data-options="
                                url: '<?php echo yii::$app->urlManager->createUrl(['operating/combotree/get-operating-company']); ?>',
                                editable: false,
                                panelHeight:'auto',
                                panelWidth:'auto',
                                lines:false,
                                required:true,
                                missingMessage:'请选择运营公司'
                           "
                        />
                </td>  
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">省份</div></td>
                <td width="23%">
                   <select class="easyui-combobox" style="width:100px;" id="province_id"   name="province_id"  editable=false   >
                   		<option value=""></option>
                   		<?php foreach($provinces as $row): ?>
                            <option value="<?php echo  $row['region_id']?>"><? echo $row['region_name']?></option>
                        <?php endforeach;?>
                   	 </select>
                </td> 
                <td align="right"width="10%">城市</td>
                <td width="23%">
                   <select class="easyui-combobox" style="width:100px;"  id="city_id"    name="city_id" required="true" editable=false   >
                   		<option value=""></option>
                   </select>
                </td>
                <td align="right"width="10%">区/县</td>
                <td width="23%">
                   <select class="easyui-combobox" style="width:100px;"  id="county_id"  name="county_id" required="true"  editable=false   >
                   		<option value=""></option>
                   	</select>
                </td>  
                <!-- <td>
                	 <input class="easyui-textbox" style="width:150px;"  name="address" required="true" prompt="详细地址" />
                </td> -->
                
            </tr>
            <tr>
            <td align="right"width="10%"></td>
            <td width="23%">
                	 <input class="easyui-textbox" style="width:210px;"  name="address" required="true" prompt="详细地址，具体到街道门牌号" />
                </td>
            </tr>

           
            <tr>
                <!-- <td>负责人：</td> -->
                <td><div style="width:85px;text-align:right;">场站负责人</div></td>
                <td>
                    <select class="easyui-combobox"  name="user_id[]" id="site_name" required="true"  style="width:210px"  missingMessage="请选择负责人" >
                    	<option value=""></option>
                    	<?php foreach ($users as $v):?>
	                  	  <option value="<?php echo $v['id']?>"><?php echo $v['name']?></option>
	                    <?php endforeach;?>
                    </select>
                </td>
				<!-- <td>电话号码：</td> -->
				<td><div style="width:85px;text-align:right;">手机号码</div></td>
                <td>
                    <input class="easyui-textbox"   name="tel[]" id="site_tel" style="width:210px" required="true" validType="match[/((0\d{2,3})-)(\d{7,8})(-(\d{3,}))?$|1[3|4|5|7|8][0-9]\d{8}$/]" invalidMessage="电话、手机格式错误！" prompt="" />
                </td>
				 <tr >
            		<td ></td>
               		<td > <input id="add-person" type="button" value="增加其他联系人" onclick="add_person()" data-value="2" /></td>
                </tr>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">关联充电站</div></td>
                <td>
                    <select class="easyui-combobox" style="width:210px;" id="sta_id"   name="sta_name" required="true" >
                        <option value="0">默认无</option>
                        <?php foreach($sta_s as $val){ ?>
                            <option value="<?php echo $val['cs_id'] ?>"><?php echo $val['cs_name']; ?></option>
                        <?php } ?>
                    </select>
                </td> 
            </tr>

        </table>
        <div
       
        class="easyui-panel"
        title="上传图片"
        style="width:100%;margin-bottom:5px;"
        
        closable="false"
        collapsible="false"
        minimizable="false"
        maximizable="false"
        border="false"
       
    >
       <table cellpadding="5" cellspacing="0">
            <!-- <td><div class="ulforform-resizeable-title">上传照片</div></td> -->
            <tr>
            <td>
                <ul style="padding:0;margin:0;list-style:none;overflow:hidden;" id="process-repair-uploadfile">
                    <?php
                        $thumbs = [
                          ['sta_img','场站照片'],
                            
                        ];
                        foreach($thumbs as $key=>$item){
                    ?>
                        <li style="float:left;margin-right:16px;position:relative;cursor:pointer" >
                            <div style="width:100px;height:100px;">
                                <img  id="<?php echo $item[0]; ?>"  class="repairImg" src="<?php echo !empty($config['image']) ? $licenseInfo['image']:'./images/add.jpg'; ?>" width="100" height="100" />
                                <input type="hidden" name="<?php echo $item[0]; ?>"  />
                            </div>
                            <div class="imgTitle" style="position:absolute;bottom:0;left:0;background:rgba(224,236,255,0.5);width:100px;text-align:center;line-height:24px;"><?php echo $item[1]; ?></div>
                            <div class="removeIcon" style="position:absolute;top:0;right:0;background:rgba(224,236,255,0.5);display:none;"><img src="./jquery-easyui-1.4.3/themes/icons/clear.png" width="14px" height="14px" /></div>
                        </li>
                        
                    <?php } ?>
                </ul>
            </td>
            </tr>  
        </table>
    </div>
    </form>
<!-- </div> -->
<iframe id="iframe-process-repair-uploadimage" name="iframe-process-repair-uploadimage" style="display:none;"></iframe>
<div id="easyui-dialog-process-repair-uploadimage"></div>
<script>
//增加
function add_person()
{
 	var data = $("#add-person").attr('data-value');
	var html ='<tr> \
				<td><div style="width:85px;text-align:right;">其他联系人</div></td> \
				<td> \
					<select id="user_id'+data+'" class="easyui-combobox"  name="user_id[]" required="true"  style="width:210px"  onchange ="change_site('+data+')" missingMessage="请选择负责人" > \
						<option value=""></option>';
						<?php foreach ($users as $v):?>
			html +=			  '<option value="<?php echo $v['id']?>"><?php echo $v['name']?></option>';
						<?php endforeach;?>
		 
			html +=	'		</select>\
				</td>\
				<td><div style="width:85px;text-align:right;">手机号码</div></td>\
				 <td>\
                    <input class="easyui-textbox"   name="tel[]" id="site_tel'+data+'" style="width:210px"  validType="match[/((0\d{2,3})-)(\d{7,8})(-(\d{3,}))?$|1[3|4|5|7|8][0-9]\d{8}$/]" invalidMessage="电话、手机格式错误！" prompt="" />\
                </td>\
				<td><input type="button" value="移除" onclick="del_person('+data+')" /><span id="inventory'+data+'" style="color:red"></span></td>\
				</tr>';



	$("#add-person").parent().parent().before(html);
	$("#add-person").attr('data-value',parseInt(data)+1);
	select_type(data);
	//$(this).prev(".selected")
	//alert('123');
}
//移除
function del_person(id)
{

	//alert($("user_id"+id).parent().parent('tr'));
	$("#user_id"+id).parent().parent().remove();
}



var ProcessRepairUpload = new Object();
    ProcessRepairUpload.init = function(){
        //初始化照片上传窗口
        $('#easyui-dialog-process-repair-uploadimage').dialog({
            title: '照片上传',   
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
                    var form = $('#easyui-form-process-repair-upload-window');
                    if(!form.form('validate')){
                        return false;
                    }
                    form.submit();
                }
            },{
                text:'取消',
                iconCls:'icon-cancel',
                handler:function(){
                    $('#easyui-dialog-process-repair-uploadimage').dialog('close');
                }
            }],
            onClose: function(){
                $(this).window('clear');
            }
        });

        //给上传故障图片绑定各类事件
        $('#process-repair-uploadfile').children('li')
            .click(function(){ //单击打开上传窗口
                var columnName = $(this).find('input').attr('name');
                $('#easyui-dialog-process-repair-uploadimage')
                    .dialog('open')
                    .dialog('refresh',"<?= yii::$app->urlManager->createUrl(['process/repair/upload-window']); ?>&columnName="+columnName);
            })
            .mouseover(function(){
                var imgSrc = $(this).find('img.repairImg').attr('src');
                if(imgSrc != './images/add.jpg'){
                    //显示删除图标并绑定删除事件
                    $(this).find('div.removeIcon').show().click(function(e){
                        e.stopPropagation();
                        $(this).parent().find('img.repairImg').attr('src','./images/add.jpg');
                        $(this).parent().find('input').val('');
                    });
                }
            })
            .mouseleave(function(){
                $(this).find('div.removeIcon').hide();
            });
       
    }
    ProcessRepairUpload.uploadComplete = function(rData){
        if(rData.status){
            $('#easyui-dialog-process-repair-uploadimage').dialog('close');
            var inputControl = $('#process-repair-uploadfile').find('input[name='+rData.columnName+']');
            inputControl.val(rData.storePath);
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


    ProcessRepairUpload.init();


    $(function(){
    //设置onchange事件
    $('select[name=province_id]').combobox({
        onChange: function (n,o) {
            var province_id = $('#province_id').combobox('getValue');
            $.ajax({
                   url:'<?php echo yii::$app->urlManager->createUrl(['charge/charge-station/get-region-list']); ?>',
                   type:'get',
                   data:{parent_id:province_id},
                   dataType:'json',
                   success:function(data){
                        $('#city_id').combobox({
                           valueField:'region_id',
                           textField:'region_name',
                           editable: false,
                           panelHeight:'auto',
                           data: data
                       });
                        $('#county_id').combobox({
                               valueField:'region_id',
                               textField:'region_name',
                               editable: false,
                               panelHeight:'auto',
                               data: []
                        });
                        $('#city_id').combobox('setValues','');
                        $('#county_id').combobox('setValues','');
                    }
            });
        }
    });
    $('select[name=city_id]').combobox({
        onChange: function (n,o) {
            var city_id = $('#city_id').combobox('getValue');
            $.ajax({
                   url:'<?php echo yii::$app->urlManager->createUrl(['charge/charge-station/get-region-list']); ?>',
                   type:'get',
                   data:{parent_id:city_id},
                   dataType:'json',
                   success:function(data){
                        $('#county_id').combobox({
                           valueField:'region_id',
                           textField:'region_name',
                           editable: false,
                           panelHeight:'auto',
                           data: data
                       });
                        $('#county_id').combobox('setValues','');
                    }
            });
        }
    });
})
/*input[name=is_sta]*/


$(function(){
     $('#site_name').combobox({
        //panelHeight:'auto',
        editable: true,
        onSelect: function(rec){
            console.log(rec.value)
			$("#site_tel").textbox('setText', "");
            //$('#site_tel').combobox('clear');
            $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['process/extract-car-site/check']); ?>",
                        
                        data: {id:rec.value},
                         dataType: 'json',
                        success: function(data){
                         console.log(data.telephone)   
                            $('#site_tel').textbox('setValue',data.telephone);
                        }
                    });
        }
    });

   
});

 function change_site(i){
        var a =  $("#user_id"+i).val();


       // console.log(a);
       /* $("#site_tel"+i).textbox('setText', "");*/
        $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['process/extract-car-site/check']); ?>",
                        
                        data: {id:a},
                         dataType: 'json',
                        success: function(data){
                         //console.log(data.telephone)   
                            $('#site_tel'+i).val(data.telephone);
                        }
                    });
        
    }

</script>