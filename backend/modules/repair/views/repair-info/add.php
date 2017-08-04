<form id="easyui-form-repair-info-add" class="easyui-form">
    <div
        class="easyui-panel"
        title="基本信息"
        style="width:100%;margin-bottom:5px;"
        closable="false"
        collapsible="false"
        minimizable="false"
        maximizable="false"
        border="false"
    >
        <table cellpadding="5" cellspacing="0">
            
            <tr>
                <td align="right"><div style="width:70px;">车牌号</div></td>
                <td>
                    <select 
                        class="easyui-combobox" 
                        style="width:160px;"
                        id="car_no"   
                        name="car_no" 
                        required="true"   
                        missingMessage="请选择车牌号">
                            <option value=""></option>
                            <?php foreach ($cars as $car):?>
                            <option value="<?php echo $car['plate_number']?>"><?php echo $car['plate_number']?></option>
                            <?php endforeach;?>
                    </select>
                </td>
                <td align="right"><div style="width:70px;">工单号</div></td>
                <td>
                    <select
                        class="easyui-combobox"
                        required="true"
                        style="width:160px;"
                        id="order_no"
                        name="order_no"
                        editable="false"
                        data-options="panelHeight:'auto'"
                    >
                    </select>
                </td>
                <td align="right"><div style="width:70px;">维修厂类型</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        required="true"
                        style="width:160px;"
                        name="sale_factory"
                        validType="length[100]"
                        editable="false"
                        disabled="disabled"
                        data-options="{onChange: function(){
                            CarBaseinfoAdd.getCarModelName($(this).val());
                        }}"
                    />
                </td>
            </tr>
            <tr>
                <td align="right"><div style="width:70px;">车型</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        required="true"
                        style="width:160px;"
                        name="car_model"
                        validType="length[100]"
                        editable="false"
                        disabled="disabled"
                        data-options="{onChange: function(){
                            CarBaseinfoAdd.getCarModelName($(this).val());
                        }}"
                    />
                </td>
               <td align="right"><div style="width:70px;">车架号</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        required="true"
                        style="width:160px;"
                        name="car_jia_no"
                        validType="length[100]"
                        editable="false"
                        disabled="disabled"
                        data-options="{onChange: function(){
                            CarBaseinfoAdd.getCarModelName($(this).val());
                        }}"
                    />
                </td>
                <td align="right"><div style="width:70px;">机动车所有人</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        required="true"
                        style="width:160px;"
                        name="car_user"
                        validType="length[100]"
                        editable="false"
                        disabled="disabled"
                        data-options="{onChange: function(){
                            CarBaseinfoAdd.getCarModelName($(this).val());
                        }}"
                    />
                </td>
            </tr>
            <tr>
                <td align="right"><div style="width:70px;">上次保养时间</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        required="true"
                        style="width:160px;"
                        name="before_repair_time"
                        validType="length[100]"
                        editable="false"
                        disabled="disabled"
                        data-options="{onChange: function(){
                            CarBaseinfoAdd.getCarModelName($(this).val());
                        }}"
                    />
                </td>
               <td align="right"><div style="width:70px;">上次保养里程</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        required="true"
                        style="width:160px;"
                        name="before_repair_li"
                        validType="length[100]"
                        editable="false"
                        disabled="disabled"
                        data-options="{onChange: function(){
                            CarBaseinfoAdd.getCarModelName($(this).val());
                        }}"
                    />
                </td>
                <td align="right"><div style="width:70px;">送修人</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        required="true"
                        style="width:160px;"
                        name="repair_person"
                        validType="length[100]"
                        data-options="{onChange: function(){
                            CarBaseinfoAdd.getCarModelName($(this).val());
                        }}"
                    />
                </td>
            </tr>
            <tr>
                <td align="right"><div style="width:70px;">送修人电话</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        required="true"
                        style="width:160px;"
                        name="repair_person_tel"
                        validType="length[100]"
                        data-options="{onChange: function(){
                            CarBaseinfoAdd.getCarModelName($(this).val());
                        }}"
                    />
                </td>
               <td align="right"><div style="width:70px;">服务顾问</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        required="true"
                        style="width:160px;"
                        name="fuwu_person"
                        validType="length[100]"
                        data-options="{onChange: function(){
                            CarBaseinfoAdd.getCarModelName($(this).val());
                        }}"
                    />
                </td>
                <td align="right"><div style="width:70px;">服务顾问电话</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        required="true"
                        style="width:160px;"
                        name="fuwu_person_tel"
                        validType="length[100]"
                        data-options="{onChange: function(){
                            CarBaseinfoAdd.getCarModelName($(this).val());
                        }}"
                    />
                </td>
            </tr>
            <tr>
                <td align="right"><div style="width:70px;">是否拖车进厂</div></td>
                <td>
                    <select 
                        class="easyui-combobox" 
                        style="width:160px;"
                        id="tuoche"   
                        name="into_factory" 
                        required="true"      
                        >
                            <option value="-1">请选择</option>
                            <option value="1">是</option>
                            <option value="0">否</option>
                    </select>
                </td>
               <td align="right"><div style="width:70px;">进厂时间</div></td>
                <td>
                    <input class="easyui-datebox" type="text" name="into_time" style="width:93px;" required="true" />
                </td>
                <td align="right"><div style="width:70px;">预计出厂时间</div></td>
                <td>
                   <input class="easyui-datebox" type="text" name="expect_time" style="width:93px;" required="true"/>
                </td>
            </tr>
            <tr>
               <td align="right"><div style="width:70px;">进厂里程</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        required="true"
                        style="width:160px;"
                        name="into_mile"
                        validType="length[100]"
                        data-options="{onChange: function(){
                            CarBaseinfoAdd.getCarModelName($(this).val());
                        }}"
                    />
                </td>
                <td align="right"><div style="width:70px;">SOC</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        required="true"
                        style="width:160px;"
                        name="soc"
                        validType="length[100]"
                        data-options="{onChange: function(){
                            CarBaseinfoAdd.getCarModelName($(this).val());
                        }}"
                    />
                </td> 
            </tr>
            <tr>
               <td align="right"><div style="width:70px;">故障描述</div></td>
                <td>
                    <textarea
                         class="textarea easyui-validatebox"
                        required="true"
                        style="width:320px;"
                        name="error_note"
                        validType="length[100]"
                        data-options="{onChange: function(){
                            CarBaseinfoAdd.getCarModelName($(this).val());
                        }}"
                    ></textarea>
                </td>  
            </tr>
            <tr>
               <td align="right"><div style="width:70px;">备注</div></td>
                <td>
                     <textarea
                         class="textarea easyui-validatebox"
                        style="width:320px;"
                        name="note"
                        validType="length[100]"
                        data-options="{onChange: function(){
                            CarBaseinfoAdd.getCarModelName($(this).val());
                        }}"
                    ></textarea>
                </td>  
            </tr>
            
            
            
        </table>
    </div>
   
    <div
        class="easyui-panel"
        title="工时信息"
        style="width:100%;margin-bottom:5px;"
        closable="false"
        collapsible="false"
        minimizable="false"
        maximizable="false"
        border="false"
    >
        <div id="tb" style="height:auto">
                <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add'" onclick="append_task()">添加行</a>
            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove'" onclick="removeit_task()">
            删除行</a>
            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove'" onclick="accept()">保存</a>
                &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                <span style="font-size:15px">合计:<span style="font-size:15px" id="task_money_all">0.00</span></span>
            </div>
    </div>
     <table id="dg" class="easyui-datagrid"  style="width:700px;height:auto"
                data-options="
                    iconCls: 'icon-edit',
                    singleSelect: true,
                    toolbar: '#tb',
                    
                    method: 'get',
                    onClickRow: onClickRow
                ">
            <thead>
                <tr>
                    <th data-options="field:'task_type',width:100,align:'center',editor:'textbox'">维修类型</th>
                    <th data-options="field:'task_name',width:100,align:'center',editor:'textbox'">维修项目名称</th>
                    <th data-options="field:'task_fee',width:100,align:'center',editor:{type:'numberbox',options:{precision:1}}"> 工时费金额</th>
                    <th data-options="field:'task_note',width:400,align:'center',editor:'textbox'">备注</th>
                </tr>
            </thead>
        </table>
</div>
     <div
        class="easyui-panel"
        title="配件信息"
        style="width:100%;margin-bottom:5px;"
        closable="false"
        collapsible="false"
        minimizable="false"
        maximizable="false"
        border="false"
    >
        <div id="ab" style="height:auto">
                <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add'" onclick="append_part()">添加行</a>
            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove'" onclick="removeit_part()">删除行</a>
            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove'" onclick="accept_1()">保存</a>
                &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                <span style="font-size:15px">合计:<span style="font-size:15px" id="part_money_all">0.00</span></span>
        </div>

    </div>
     <table id="bg" class="easyui-datagrid"  style="width:900px;height:auto"
                data-options="
                    iconCls: 'icon-edit',
                    singleSelect: true,
                    toolbar: '#ab',
                    
                    method: 'get',
                    onClickRow: onClickRow_1
                ">
            <thead>
                <tr>
                    <th data-options="field:'part_no',width:100,align:'center',editor:'textbox'">配件编号</th>
                    <th data-options="field:'part_name',width:100,align:'center',editor:'textbox'">配件名称</th>
                    <th data-options="field:'part_fee',width:100,align:'center',editor:{type:'numberbox',options:{precision:2}}"> 单价</th>
                    <th data-options="field:'part_number',width:100,align:'center',editor:'numberbox'"> 数量</th>
                    <th data-options="field:'part_unit',width:100,align:'center',editor:'textbox'">单位</th>
                    <th data-options="field:'part_total',width:100,align:'center'">配件金额</th>
                    <th data-options="field:'before_repair_time',width:100,align:'center'">上次维修时间</th>
                    <th data-options="field:'before_repair_li',width:100,align:'center'">上次维修里程</th>
                    <th data-options="field:'part_save_time',width:100,align:'center'">配件质保期</th>
                </tr>
            </thead>
        </table>
     <div
            class="easyui-panel"
            title="总金额"
            style="width:100%;margin-bottom:5px;"
            closable="false"
            collapsible="false"
            minimizable="false"
            maximizable="false"
            border="false"
        >
        <span style="font-size:20px">总金额:<span style="font-size:15px" id="money_all">0.00</span></span>
    </div>

    <div
        class="easyui-panel"
        title="上传照片"
        style="width:100%;margin-bottom:5px;"
        
        closable="false"
        collapsible="false"
        minimizable="false"
        maximizable="false"
        border="false"
    >
       <table cellpadding="5" cellspacing="0">
           <!--  <div class="ulforform-resizeable-title">上传照片</div> -->
                    <!-- <div class="ulforform-resizeable-input">
                        <ul style="padding:0;margin:0;list-style:none;overflow:hidden;" id="process-repair-uploadfile">
                            <?php
                                $thumbs = [
                                  ['car_front_img','车头'],
                                    ['car_left_img','车辆全身'],
                                ];
                                foreach($thumbs as $key=>$item){
                            ?>
                                <li id="img<?php echo $key;?>" style="float:left;margin-right:16px;position:relative;cursor:pointer;margin-bottom:20px;" >
                                    <div style="width:100px;height:100px;">
                                        <img  id="<?php echo $item[0]; ?>"  class="repairImg" src="./images/add.jpg" width="100" height="100" />
                                        <input type="hidden" name="<?php echo $item[0]; ?>"  />
                                    </div>
                                    <div class="imgTitle" style="position:absolute;bottom:0;left:0;background:rgba(224,236,255,0.5);width:100px;text-align:center;line-height:24px;"><?php echo $item[1]; ?></div>
                                    <div class="removeIcon" style="position:absolute;top:0;right:0;background:rgba(224,236,255,0.5);display:none;"><img src="./jquery-easyui-1.4.3/themes/icons/clear.png" width="14px" height="14px" /></div>
                                </li>
                            <?php } ?>
                        </ul>
                    </div> -->
                    <td>
                        <ul style="padding:0;margin:0;list-style:none;overflow:hidden;" id="process-repair-uploadfile">
                            <?php
                                $thumbs = [
                                  ['car_front_img','车辆仪表盘'],
                                    ['car_left_img','故障位置'],
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
            
        </table>
    </div>
</form>
<input type="hidden" id="car_no_same" name="car_no" value="">
<!-- <div id="easyui-dialog-process-repair-uploadimage"></div> -->

<iframe id="iframe-process-repair-uploadimage" name="iframe-process-repair-uploadimage" style="display:none;"></iframe>
<div id="easyui-dialog-process-repair-uploadimage"></div>
<script>

    var CarBaseinfoAdd = {
        //获取填写车型的车辆名称
        getCarModelName: function(carBrand){
            var carBrandNameInput = $('#car_baseinfo_add_input_car_brand_name');
            if(!carBrand){
                carBrandNameInput.textbox('setValue','未知');
                return false;
            }
            var carBrandName = <?php echo json_encode($config['car_model_name']); ?>;
            if(carBrandName[carBrand]){
                carBrandNameInput.textbox('setValue',carBrandName[carBrand].text);
            }else{
                carBrandNameInput.textbox('setValue','未知');
            }
        },
        auto_complete: function(){
            var form = $('#car-baseinfo-add-auto-complete');
            if(!form.form('validate')){
                return false;
            }
            $.ajax({
                "type": 'get',
                "url":"<?php echo yii::$app->urlManager->createUrl(['car/baseinfo/auto-complete']);?>",
                "data": form.serialize(),
                "dataType": "json",
                "success": function(data){
                    if(data.status){
                        $("#easyui-form-car-baseinfo-add").form('load',data.info);
                    }else{
                        $.messager.alert('错误',data.info,'error');
                    }
                }
            });
        }
    };


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
</script>
<script type="text/javascript">
    //二级联动
    $('#car_no').combobox({
        onChange: function (n,o) {
            var car_no = $('#car_no').combobox('getValue');
            $('#car_no_same').val(car_no);
            $.ajax({
                async: false,
                url:'<?php echo yii::$app->urlManager->createUrl(['repair/repair-info/get-order']); ?>',
                type:'post',
                data:{'car_no':car_no},
                dataType:'json',
                success:function(data){
//                    $('#parts_kind').combobox('clear');
                    $('#order_no').combobox({
                        valueField:'value',
                        textField:'text',
                        editable: false,
                        panelHeight:'auto',
                        data: data
                    });
                    $('#order_no').combobox('setValues','');
                }
            });
        }
    });
</script>
        <script type="text/javascript">
            var editIndex = undefined;
            function endEditing(){
                if (editIndex == undefined){return true}
                if ($('#dg').datagrid('validateRow', editIndex)){
                    //var ed = $('#dg').datagrid('getEditor', {index:editIndex,field:'productid'});
                   // var productname = $(ed.target).combobox('getText');
                    //$('#dg').datagrid('getRows')[editIndex]['productname'] = productname;
                    $('#dg').datagrid('endEdit', editIndex);
                    editIndex = undefined;
                    return true;
                } else {
                    return false;
                }
            }
            function onClickRow(index){
                if (editIndex != index){
                    if (endEditing()){
                        $('#dg').datagrid('selectRow', index)
                                .datagrid('beginEdit', index);
                        editIndex = index;
                    } else {
                        $('#dg').datagrid('selectRow', editIndex);
                    }
                }
            }
            function append_task(){
                if (endEditing()){
                    $('#dg').datagrid('appendRow',{status:'P'});
                    editIndex = $('#dg').datagrid('getRows').length-1;
                    $('#dg').datagrid('selectRow', editIndex)
                            .datagrid('beginEdit', editIndex);
                }
            }
            function accept(){
                    if (endEditing()){
                        $('#dg').datagrid('acceptChanges');
                    }
            }
            function removeit_task(){
                if (editIndex == undefined){return}
                $('#dg').datagrid('cancelEdit', editIndex)
                        .datagrid('deleteRow', editIndex);
                editIndex = undefined;
            }
            function getChanges(){
                var rows = $('#dg').datagrid('getChanges');
                alert(rows.length+' rows are changed!');
            }
        </script>
        <script type="text/javascript">
            var editIndex_1 = undefined;
            function endEditing_1(){
                if (editIndex_1 == undefined){return true}
                if ($('#bg').datagrid('validateRow', editIndex_1)){
                   // var ed = $('#bg').datagrid('getEditor', {index:editIndex,field:'productid'});
                   // var productname = $(ed.target).combobox('getText');
                    //$('#dg').datagrid('getRows')[editIndex]['productname'] = productname;
                    $('#bg').datagrid('endEdit', editIndex_1);
                    editIndex_1 = undefined;
                    return true;
                } else {
                    return false;
                }
            }
            function onClickRow_1(index){
                //alert(11111)
                //alert(index);
                //var obj=$('#bg').datagrid('getSelected')
                /*if (editIndex_1 != index){
                    if (endEditing_1()){
                        $('#bg').datagrid('selectRow', index)
                                .datagrid('beginEdit', index);
                                //console.log(obj);
                        editIndex_1 = index;
                    } else {
                       $('#bg').datagrid('selectRow', index)
                            .datagrid('endEdit', editIndex_1);
                            //console.log(obj);
                    }
                }else{
                        $('#bg').datagrid('selectRow', index)
                                .datagrid('beginEdit', index);
                                //console.log(obj);
                }*/
                if(editIndex_1!=index){
                    $('#bg').datagrid('selectRow', index)
                                .datagrid('beginEdit', index)
                                .datagrid('endEdit', editIndex_1);
                                //得到当前选择的对象的值
                       var rows = $('#bg').datagrid('getRows');
                       alert(index)
                       alert(editIndex_1);
                       if(editIndex_1==undefined){
                        editIndex_1=index;
                       }
                       
                        var row  = rows[editIndex_1];
                    
                       alert(editIndex_1);
                       editIndex_1=index;
                       var car_no=$('#car_no_same').val();
                       var part_no=row.part_no;
                       var part_number=row.part_number;
                       var part_fee=row.part_fee;
                        $.ajax( {  
                                 "url":"<?php echo yii::$app->urlManager->createUrl(['repair/repair-info/part-info']);?>",// 跳转到 action  
                                 data:{  
                                      part_no : part_no,    
                                      car_no:car_no   
                                 },  
                                     type:'post',  
                                     cache:false,  
                                     dataType:'json',  
                                     success:function(data) {
                                        //alert(111);
                                        console.log(data)
                                     row.before_repair_time=data.into_time;
                                     row.before_repair_li=data.into_mile;
                                     row.part_total=part_number*part_fee;
                                         
                                     }
                                 });
                                
                }else{

                }
            }
            function accept_1(){
                alert(editIndex_1)
                    if (endEditing_1()){
                        $('#bg').datagrid('acceptChanges');
                    }
            }
            function append_part(){
                if (endEditing_1()){
                    $('#bg').datagrid('appendRow',{status:'P',part_no:'',part_number:'',part_fee:'',part_unit:'',part_name:''});
                    //editIndex_1 = $('#bg').datagrid('getRows').length-1;
                    //$('#bg').datagrid('selectRow', editIndex_1);
                            //.datagrid('beginEdit', editIndex_1);
                }

            }
            function removeit_part(){
                if (editIndex_1 == undefined){return}
                $('#bg').datagrid('cancelEdit', editIndex_1)
                        .datagrid('deleteRow', editIndex_1);
                editIndex_1 = undefined;
            }
            function getChanges_1(){
                var rows = $('#bg').datagrid('getChanges');
                alert(rows.length+' rows are changed!');
            }
        </script>
