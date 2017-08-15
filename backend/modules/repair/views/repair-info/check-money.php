<form id="easyui-form-repair-info-check-money" class="easyui-form">
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
                            <option value="1" selected="<?php if($data['into_factory']==1){echo 'selected'; } ?>">是</option>
                            <option value="0" selected="<?php if($data['into_factory']==0){echo 'selected'; } ?>">否</option>
                    </select>
                </td>
               <td align="right"><div style="width:70px;">进厂时间</div></td>
                <td>
                    <input class="easyui-datetimebox" style="width:160px;"  name="in_time"  id="in_time"  required value="<?php echo $data['into_time'];?>" disabled="disabled" />
                </td>
                <td align="right"><div style="width:70px;">预计出厂时间</div></td>
                <td>
                   <input class="easyui-datetimebox" type="text" name="expect_time" style="width:160px;" required="true" value="<?php echo $data['expect_time'];?>" disabled="disabled" />
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
        <span style="font-size:15px">合计:<span style="font-size:15px" id="task_money_all"><?php echo $data['task_fee']?></span></span>
        </div>
        
    </div>
     <table id="dg" class="easyui-datagrid"  style="width:700px;height:auto"
                data-options="
                    iconCls: 'icon-edit',
                    singleSelect: true,
                    toolbar: '#tb',
                    url:'<?php echo yii::$app->urlManager->createUrl(['repair/repair-info/get-task']); ?>&id=<?php echo $data['id'];?>',
                    method: 'get'
                ">
            <thead>
                <tr>
                    <th data-options="field:'task_type',width:100,align:'center'">维修类型</th>
                    <th data-options="field:'task_name',width:100,align:'center'">维修项目名称</th>
                    <th data-options="field:'task_fee',width:100,align:'center'"> 工时费金额</th>
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
            <span style="font-size:15px">合计:<span style="font-size:15px" id="part_money_all"><?php echo $data['part_fee'] ?></span></span>
        </div>

    </div>
     <table id="bg" class="easyui-datagrid"  style="width:900px;height:auto"
                data-options="
                    iconCls: 'icon-edit',
                    singleSelect: true,
                    toolbar: '#ab',
                    url:'<?php echo yii::$app->urlManager->createUrl(['repair/repair-info/get-part']); ?>&id=<?php echo $data['id'];?>',
                    method: 'get'
                ">
            <thead>
                <tr>
                    <th data-options="field:'part_no',width:100,align:'center'">配件编号</th>
                    <th data-options="field:'part_name',width:100,align:'center'">配件名称</th>
                    <th data-options="field:'part_fee',width:100,align:'center'"> 单价</th>
                    <th data-options="field:'part_number',width:100,align:'center'"> 数量</th>
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
        <span style="font-size:20px">总金额:<span style="font-size:15px" id="money_all"><?php echo $data['repair_price']?></span></span>
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
<input type="hidden"  name="check_1" value="0">
</form>


     
     
       

