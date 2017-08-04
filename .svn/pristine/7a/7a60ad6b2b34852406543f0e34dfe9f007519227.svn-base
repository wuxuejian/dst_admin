<form
    id="easyui-form-car-office-car-register-add"
    class="easyui-form"
    style="padding:10px;" method="post"
>
        <input type="hidden" name="car_id" value="<?=$id; ?>" />

        <table cellpadding="8" cellspacing="0">
        
            <tr>
                <td><div style="width:85px;text-align:right;">车牌号</div></td>
                <td>
                    <input
                        id="easyui-form-car-office-car-register-add-index"
                        name="car_id"
                        style="width:180px;"
                        value="<?=$car['plate_number']?>"
                        disabled="disabled"
                            
                    />
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">申请部门</div></td>
                <td>



                  

                   
                    <div class="ulforform-resizeable-input">
                        <select class="easyui-combobox" style="width:160px;" id = "car_site" name="department_id" required="true"   missingMessage="请选择车牌号">
                           <option value=''></option>
                            
                            <?php foreach ($dep_uer as $v):?>
                            
                            <option value="<?php echo $v['id']?>" ><?php echo $v['name']?></option>
                           
                            <?php endforeach;?>
                    </select>
                    </div>
              


                </td> 
                <td><div style="width:85px;text-align:right;">用车人</div></td>
                <td>
                    <select
                        id = "username"
                        class="easyui-combobox"
                        name="username_id"
                        style="width:180px;"
                        required="true"
                        editable="false"
                        data-options="panelHeight:'auto'"
                    >   
                       
                    </select>
                </td>  
            </tr>

            <tr>
                <td><div style="width:85px;text-align:right;">开始用车时间</div></td>
                <td>
                    <!-- <input
                        class="easyui-datetimebox"
                        style="width:160px;"
                        name="start_time"
                        required="true"
                        missingMessage="请选择开始日期！"
                        validType="datetime"
                    /> -->
                    
                        <input class="easyui-datetimebox" name="start_time" 
                        data-options="required:true,showSeconds:false" value="" style="width:150px" missingMessage="请选择开始日期！">
                    
                </td> 
                <td><div style="width:85px;text-align:right;">预计还车时间</div></td>
                <td>
                    <!-- <input
                       class="easyui-datetimebox"
                        style="width:160px;"
                        name="end_time"
                        required="true"
                        missingMessage="请选择预计还车日期！"
                        validType="datetime"
                    /> -->
                    <input class="easyui-datetimebox" name="end_time" 
                        data-options="required:true,showSeconds:false" value="" style="width:150px" missingMessage="请选择预计还车日期！">
                </td>  
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">用车事由</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        name="reason"
                        style="width:250px;"
                        validType="length[50]"
                        required="true"

                    />
                </td> 
                <td><div style="width:85px;text-align:right;">用车地点</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        name="address"
                        style="width:160px;"
                        validType="length[50]"
                    />
                </td>  
            </tr>

            <tr>
                <td><div style="width:85px;text-align:right;">当前总里程</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        name="total_distance"
                        style="width:250px;"
                        validType="length[50]"
                    />
                </td> 
                <td><div style="width:85px;text-align:right;">剩余续航里程</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        name="remain_distance"
                        style="width:160px;"
                        validType="length[50]"
                    />
                </td>  
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">备注</div></td>
                <td>
                    <text
                        class="easyui-textbox"
                        name="note"
                        style="width:250px;"
                        validType="length[100]"
                    />
                </td> 
            </tr>


        </table>


    </form>


<div id="easyui-form-car-office-car-register-add-uploadimage"></div>
<script type="text/javascript">
   
   //CarOfficeCarRegisterIndex.init();*/
   $(function(){
     $('#car_site').combobox({
        //panelHeight:'auto',
        editable: false,
        onSelect: function(rec){
            $('#username').combobox('clear');
            $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['car/office-car-register/check']); ?>",
                        data: {id:rec.value},
                      
                        dataType: 'json',
                        success: function(data){
                            
                            $('#username').combobox('loadData',eval(data));
                        }
                    });
        }
    });
});


  

</script>