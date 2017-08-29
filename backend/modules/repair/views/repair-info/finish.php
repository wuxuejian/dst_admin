<form id="easyui-form-repair-info-finish" class="easyui-form">
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
                     <input name="car_no" id="car_no" style="width:150px;" disabled="disabled" value="<?php echo $data['car_id'];?>">
                </td>
                <td align="right"><div style="width:70px;">工单号</div></td>
                <td>
                    <input name="order_no"  style="width:150px;" disabled="disabled" value="<?php echo $data['order_number'];?>">
                </td>
                <td align="right"><div style="width:70px;">维修厂类型</div></td>
                <td>
                    <input 
                        type="text"
                        style="width:160px;" 
                        name="sale_factory" 
                        id="sale_factory" 
                        disabled="disabled"
                        value="<?php echo $data['sale_factory'];?>"
                    >
                </td>
            </tr>
            <tr>
                <td align="right"><div style="width:70px;">车型</div></td>
                <td>
                    <input
                        required="true"
                        id="car_model_name"
                        style="width:160px;"
                        name="car_model_name"
                        disabled="disabled"
                        value="<?php echo $data['car_model_name'];?>"
                    />
                </td>
               <td align="right"><div style="width:70px;">车架号</div></td>
                <td>
                    <input
                        id="car_jia_no"
                        style="width:160px;"
                        name="car_jia_no"
                        disabled="disabled"
                        value="<?php echo $data['vehicle_dentification_number'];?>"
                    />
                </td>
                <td align="right"><div style="width:70px;">机动车所有人</div></td>
                <td>
                    <input
                        required="true"
                        style="width:160px;"
                        id="car_user"
                        name="car_user"
                        disabled="disabled"
                        value="<?php echo $data['name'];?>"
                    />
                </td>
            </tr>
            <tr>
                <td align="right"><div style="width:70px;">上次保养时间</div></td>
                <td>
                    <input
                        style="width:160px;"
                        id="before_repair_time"
                        name="before_repair_time"
                        disabled="disabled"
                        value="<?php echo $data['a'];?>"
                </td>
               <td align="right"><div style="width:70px;">上次保养里程</div></td>
                <td>
                    <input
                        id="before_repair_li"
                        style="width:160px;"
                        name="before_repair_li"
                        validType="length[100]"
                        disabled="disabled"
                        value="<?php echo $data['b'];?>"
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
                        disabled="disabled"
                        value="<?php echo $data['send_human'];?>"
                       
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
                        disabled="disabled"
                         value="<?php echo $data['send_phone'];?>"
                        
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
                        disabled="disabled"
                        value="<?php echo $data['service_human'];?>"
                       
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
                        disabled="disabled"
                        value="<?php echo $data['service_phone'];?>"
                      
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
                        disabled="disabled" 
                        required="true"      
                        >
                            <option value="-1">请选择</option>
                            <option value="1" <?php if($data['into_factory']==1){echo "selected";}?>>是</option>
                            <option value="0" <?php if($data['into_factory']==0){echo "selected";}?>>否</option>
                    </select>
                </td>
               <td align="right"><div style="width:70px;">进厂时间</div></td>
                <td>
                    <input class="easyui-datetimebox" style="width:160px;"  name="in_time"  id="in_time"
                    disabled="disabled"  required value="<?php echo $data['into_time'];?>"  />
                </td>
                <td align="right"><div style="width:70px;">预计出厂时间</div></td>
                <td>
                   <input class="easyui-datetimebox" disabled="disabled" type="text" name="expect_time" style="width:160px;" required="true" value="<?php echo $data['expect_time'];?>" />
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
                        disabled="disabled"
                        value="<?php echo $data['into_mile'];?>"
                        
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
                        disabled="disabled"
                        value="<?php echo $data['soc'];?>"
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
                        disabled="disabled"
                        validType="length[100]"   
                    ><?php echo $data['error_note'];?></textarea>
                </td>  
            </tr>
            <tr>
               <td align="right"><div style="width:70px;">备注</div></td>
                <td>
                     <textarea
                         class="textarea easyui-validatebox"
                        style="width:320px;"
                        name="note"
                        disabled="disabled"
                        validType="length[100]"
                    ><?php echo $data['info_note'];?></textarea>
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
            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove'" onclick="accept()">保存</a>
                &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                <span style="font-size:15px">合计:<span style="font-size:15px" id="task_money_all"><?php echo $data['task_fee']?></span></span>
            </div>
    </div>
     <table id="dg" class="easyui-datagrid"  style="width:700px;height:auto"
                data-options="
                    iconCls: 'icon-edit',
                    singleSelect: true,
                    toolbar: '#tb',
                    url:'<?php echo yii::$app->urlManager->createUrl(['repair/repair-info/get-task']); ?>&id=<?php echo $repair_id;?>',
                    method: 'get',
                    onClickRow: onClickRow
                ">
            <thead>
                <tr>
                    <th data-options="field:'task_type',width:100,align:'center'">维修类型</th>
                    <th data-options="field:'task_name',width:100,align:'center'">维修项目名称</th>
                    <th data-options="field:'task_fee',width:100,align:'center',editor:{type:'numberbox',options:{precision:2}}"> 工时费金额</th>
                    <th data-options="field:'task_note',width:400,align:'center'">备注</th>
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
            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove'" onclick="accept_1()">保存</a>
                &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                <span style="font-size:15px">合计:<span style="font-size:15px" id="part_money_all"><?php echo $data['part_fee'] ?></span></span>
        </div>

    </div>
     <table id="bg" class="easyui-datagrid"  style="width:900px;height:auto"
                data-options="
                    iconCls: 'icon-edit',
                    singleSelect: true,
                    toolbar: '#ab',
                    url:'<?php echo yii::$app->urlManager->createUrl(['repair/repair-info/get-part']); ?>&id=<?php echo $repair_id;?>',
                    method: 'get',
                    onClickRow: onClickRow_1
                ">
            <thead>
                <tr>
                    <th data-options="field:'part_no',width:100,align:'center'">配件编号</th>
                    <th data-options="field:'part_name',width:100,align:'center'">配件名称</th>
                    <th data-options="field:'part_fee',width:100,align:'center',editor:{type:'numberbox',options:{precision:2}}"> 单价</th>
                    <th data-options="field:'part_number',width:100,align:'center',editor:'numberbox'"> 数量</th>
                    <th data-options="field:'part_unit',width:100,align:'center'">单位</th>
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
        <span style="font-size:20px">总金额:<span style="font-size:15px" id="money_all"><?php if($data['account_price']>0){echo $data['account_price'];}else{echo $data['repair_price'];}?></span></span>
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
                        <ul style="padding:0;margin:0;list-style:none;overflow:hidden;" id="repair-add-uploadfile">
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
                        <ul style="padding:0;margin:0;list-style:none;overflow:hidden;" id="repair-add-uploadfile">
                            <?php
                                $thumbs = [
                                    ['repair_img_o','车辆仪表盘'],
                                    ['repair_img_t','故障位置']
                                ];
                                foreach($thumbs as $key=>$item){
                            ?>
                                <li id="img<?php echo $key;?>" style="float:left;margin-right:16px;position:relative;cursor:pointer;margin-bottom:20px;" >
                                    <div style="width:100px;height:100px;">
                                        <img  id="<?php echo $item[0]; ?>"  class="repairImg" src="<?php echo $data[$item[0]];?>" width="100" height="100" />
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
<input type="hidden" id="repair_id" name="id" value="<?php echo $data['id']?>">
<input type="hidden" id="car_no_same" name="car_no" value="">
<input type="hidden" id="part_info" name="part_info" value="">
<input type="hidden" id="task_info" name="task_info" value="">
<input type="hidden" id="repair_price" name="repair_price" value="">
<input type="hidden" id="is_save_task" name="is_save_task" value="0">
<input type="hidden" id="is_save_part" name="is_save_part" value="0">
</form>

<!-- <div id="easyui-dialog-repair-add-uploadimage"></div> -->

<iframe id="iframe-repair-add-uploadimage" name="iframe-repair-add-uploadimage" style="display:none;"></iframe>
<div id="easyui-dialog-repair-add-uploadimage"></div>
        <script type="text/javascript">
            var editIndex = undefined;
            var is_save_task=0;
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
                is_save_task=1;
                $("#is_save_task").val(is_save_task);
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
                is_save_task=1;
                $("#is_save_task").val(is_save_task);
                if (endEditing()){
                    $('#dg').datagrid('appendRow',{status:'P'});
                    editIndex = $('#dg').datagrid('getRows').length-1;
                    $('#dg').datagrid('selectRow', editIndex)
                            .datagrid('beginEdit', editIndex);
                }
            }
            function accept(){
                is_save_task=3;
                $("#is_save_task").val(is_save_task);
                $("#task_money_all").text(0);
                //alert($("task_money_all").text())
                    if (endEditing()){
                        var rows = $("#dg").datagrid("getRows");
                        var task_info=new Array(); 
                        for(var i=0;i<rows.length;i++)
                        {
                            task_info[i]=new Array();
                            task_info[i][0]=rows[i].task_type;
                            task_info[i][1]=rows[i].task_name;
                            if( !rows[i].task_fee){
                                $.messager.alert('数据错误','工时费为必填项','error');
                                                return false;
                            }
                            if(rows[i].task_fee<0){
                                $.messager.alert('数据错误','价格只能为正数','error');
                                return false;
                            }
                            task_info[i][2]=rows[i].task_fee;
                            task_info[i][3]=rows[i].task_note;
                            task_money_all=parseFloat($("#task_money_all").text())+parseFloat(rows[i].task_fee);
                            task_money_all = task_money_all.toFixed(2);
                            $("#task_money_all").text(task_money_all);
                            part_money_all=$("#part_money_all").text();
                            money_all=parseFloat(task_money_all)+parseFloat(part_money_all);
                            money_all = money_all.toFixed(2);
                            $("#money_all").text(money_all) ;
                            $("#repair_price").val(money_all);  


                        }
                        $('#dg').datagrid('acceptChanges');
                        $("#task_info").val(arrayToJson(task_info));
                    }

            }
            function removeit_task(){
                is_save_task=1;
                $("#is_save_task").val(is_save_task);
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
           var is_save_part=0;
            function endEditing_1(){
                if (editIndex_1 == undefined){return true}
                if ($('#bg').datagrid('validateRow', editIndex_1)){
                    $('#bg').datagrid('endEdit', editIndex_1);
                    editIndex_1 = undefined;
                    return true;
                } else {
                    return false;
                }
            }
            function onClickRow_1(index){
                is_save_part=1;
                $("#is_save_part").val(is_save_part);
                if (editIndex_1 != index){
                    if (endEditing_1()){
                        $('#bg').datagrid('selectRow', index)
                                .datagrid('beginEdit', index);
                        editIndex_1 = index;
                    } else {
                        $('#bg').datagrid('selectRow', editIndex_1);
                    }
                }
            }
            function append_part(){
                is_save_part=1;
                $("#is_save_part").val(is_save_part);
                if (endEditing_1()){
                    $('#bg').datagrid('appendRow',{status:'P',part_no:'',part_number:'',part_fee:'',part_unit:'',part_name:'',part_total:'',before_repair_time:'',before_repair_li:''});
                    editIndex_1 = $('#bg').datagrid('getRows').length-1;
                    $('#bg').datagrid('selectRow', editIndex_1)
                            .datagrid('beginEdit', editIndex_1);
                }
            }
            function removeit_part(){
                is_save_part=1;
                $("#is_save_part").val(is_save_part);
                if (editIndex_1 == undefined){return}
                $('#bg').datagrid('cancelEdit', editIndex_1)
                        .datagrid('deleteRow', editIndex_1);
                editIndex_1 = undefined;
            }
            function accept_1(){
                
                
                $("#part_money_all").text(0);
                var car_no=$("#car_no").val();
                //alert(car_no)
                if (endEditing_1()){
                    var rows = $("#bg").datagrid("getRows");
                    var part_number=new Array();
                    var part_fee=new Array();
                    var part_info=new Array();
                    for(var i=0;i<rows.length;i++)
                        {
                            //获取每一行的数据
                            //console.log(i)
                            var part_no=rows[i].part_no;
                             if(part_number[i]<0){
                                                $.messager.alert('数据错误','数量只能为正数','error');
                                                return false;
                                              }
                                              if(part_fee[i]<0){
                                                $.messager.alert('数据错误','价格只能为正数','error');
                                                return false;
                                              }
                            part_number[i]=rows[i].part_number;
                            part_fee[i]=rows[i].part_fee;
                            part_info[i]=new Array();
                            
                            //alert(rows[i].id);//假设有id这个字段
                            $.ajax( {  
                                         "url":"<?php echo yii::$app->urlManager->createUrl(['repair/repair-info/part-info']);?>",// 跳转到 action  
                                         data:{  
                                              part_no : part_no,    
                                              car_no:car_no

                                         },  
                                             type:'post',  
                                             cache:false,
                                             async: false,  
                                             dataType:'json',  
                                             success:function(data) {
                                                //alert(111);
                                                if(data.error==1){
                                                    alert(data.msg);
                                                    return false;
                                                }
                                                //console.log(data)
                                             row = $("#bg").datagrid("getRows")[i];
                                             row.part_total=part_number[i]*part_fee[i];
                                             row.before_repair_time=data.into_time;
                                             row.before_repair_li=data.into_mile;
                                             part_info[i][0]=rows[i].part_no;
					                            part_info[i][1]=part_number[i];
					                            part_info[i][2]=part_fee[i];
					                            part_info[i][3]=rows[i].part_unit;
					                            part_info[i][4]=rows[i].part_name;
					                            part_info[i][5]=rows[i].before_repair_time?rows[i].before_repair_time:0;
					                            part_info[i][6]=rows[i].before_repair_li?rows[i].before_repair_li:0;
                                             part_money_all=parseFloat($("#part_money_all").text())+parseFloat(part_number[i]*part_fee[i]);
                                             part_money_all = part_money_all.toFixed(2);
                                             $("#part_money_all").text(part_money_all);
                                             onClickRow_1(i);
                                             task_money_all=$("#task_money_all").text();
                                             money_all=parseFloat(task_money_all)+parseFloat(part_money_all);
                                             money_all = money_all.toFixed(2);
                                              $("#money_all").text(money_all) ;
                                              $("#repair_price").val(money_all);  
                                             }
                                         });
                                //setTimeout("onClickRow_1("+i+")",100);
                                $('#bg').datagrid('acceptChanges');
                        } 
                       console.log(arrayToJson(part_info))
                         // part_info=part_info.join("-");
                        $("#part_info").val(arrayToJson(part_info));
                        is_save_part=3; 
                        $("#is_save_part").val(is_save_part);               
                                         
                     
                    
                }
            }
            $("#repair_price").val($("#money_all").text());
                //数组转json
                function arrayToJson(o) {

                    　　var r = [];

                    　　if (typeof o == "string") return "\"" + o.replace(/([\'\"\\])/g, "\\$1").replace(/(\n)/g, "\\n").replace(/(\r)/g, "\\r").replace(/(\t)/g, "\\t") + "\"";

                    　　if (typeof o == "object") {

                    　　if (!o.sort) {

                    　　for (var i in o)



                    　　r.push(i + ":" + arrayToJson(o[i]));

                    　　if (!!document.all && !/^\n?function\s*toString\(\)\s*\{\n?\s*\[native code\]\n?\s*\}\n?\s*$/.test(o.toString)) {

                    　　r.push("toString:" + o.toString.toString());

                    　　}

                    　　r = "{" + r.join() + "}";

                    　　} else {

                    　　for (var i = 0; i < o.length; i++) {

                    　　r.push(arrayToJson(o[i]));

                    　　}

                    　　r = "[" + r.join() + "]";

                    　　}

                    　　return r;

                    　　}

                    　　return o.toString();

                }

               // accept();
                //accept_1();
      
        </script>


