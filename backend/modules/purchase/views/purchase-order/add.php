<form
    id="easyui-form-add"
    class="easyui-form"
    style="padding:10px;" method="post"
>
    <div class="easyui-tabs" id="tt"> 
    <div title="订单基本信息" style="padding:15px" >
        <!-- <div class="easyui-panel" title="流程追踪"   style="padding:5px 0px;"data-options="collapsible:true,collapsed:false,border:false,fit:false"> -->
            <table cellpadding="6" cellspacing="0" align="center"  width="100%" border="0" >
                <tr>
                <td align="right"><div style="width:70px;">合同编号</div></td>
                <td>
                    <input class="easyui-textbox" name="contract_number" required="true"/>
                </td>
                <td align="right"><div style="width:70px;">经销商名称</div></td>
                <td>
                    <input  class="easyui-textbox" name="distributor_name" 
                           
                    />
                </td>
                </tr>
                <tr>
                <td align="right"><div style="width:70px;">订单编号</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        value="系统自动生成"
                        style="width:160px;"
                        name="order_number"
                        disabled="disabled"
                        
                       
                    />
                </td>

                <td align="right"><div style="width:70px;">接收方</div></td>
                <td>
                  <!--   <input
                        id="operating_company_id"
                       
                        class="easyui-textbox"
                        style="width:160px;"
                        
                        
                    /> -->
                   <!--  <div class="item-name">大区</div> -->
                    <div class="item-input" >
                        <select   class="easyui-combobox" name="regional" data-options="editable:false"  style="width:60%;"  >
                        <option value=''>选择大区</option>
                        <option value='1'>华南大区</option>
                        <option value='2'>华北大区</option>
                        <option value='3'>华东大区</option>
                        <option value='4'>华中大区</option>
                        <option value='5'>西南大区</option>
                        </select>
                    </div>
                </td>
            
                <input type="hidden" id="ocs1" name="ocs" /><!-- 当前大区下的所有运营公司ID -->
                <td>
                  <!--  <div class="item-name">运营公司</div> -->
                        <!-- <div class="ulforform-resizeable-title">所属公司</div> -->
                   <div class="ulforform-resizeable-input">
                    <!-- <input class="easyui-combotree" name="operating_company_id" style="width:180px;" id="add_user_oc" 
                           data-options="
                                url: '<?php echo yii::$app->urlManager->createUrl(['operating/combotree/get-operating-company']); ?>',
                                editable: false,
                                panelHeight:'auto',
                                panelWidth:'auto',
                                lines:false,
                                required:true,
                                missingMessage:'请选择运营公司'
                           "
                        /> -->
                        <div class="item-input">
                            <input id="oc1"  name="operating_company_id"  class="easyui-combobox" style="width:100%;" data-options="editable:false" />
                        </div>


                     </div>
                </td>
                </tr>

                <tr>
                <td align="right"><div>合同签署时间</div></td>
                <td>
                    <input
                        class="easyui-datetimebox"
                        required="true"
                        style="width:160px;"
                        name="sign_time"
                        
                    />
                </td>
                <td align="right"><div style="width:70px;">所有人</div></td>
                <td>
                    <input
                        name="receiver_id"
                       
                        class="easyui-textbox"
                        style="width:160px;"    
                    />
                </td>
                </tr>
                <tr>
                <td align="right"><div>预计发货时间</div></td>
                <td>
                    <input
                        class="easyui-datetimebox"
                        required="true"
                        style="width:160px;"
                        name="estimated_delivery_time"
                       
                    />
                </td>  
                </tr>
            </table>
       <!--  </div> -->
        
        
    </div>
    
    <div title="车辆详情" style="padding:15px">
       <!--  <div class="easyui-panel" title="流程追踪"    style="padding:5px 0px;"data-options="collapsible:true,collapsed:false,border:false,fit:false"> -->
            <table cellpadding="0" cellspacing="0" align="center"  height="100%" width="100%" border="0px" >
                <tr>
                    <td>类别</td><td>品牌</td><td>车型</td><td>数量</td><td>其他</td>
                </tr>
                <tr>
                    <td>
                        <select id="car_gary1"   name="car_gary[]" required="true"  onchange ="search(1)" >
                            <option value="zhengche">整车</option>
                        </select>
                    </td>
                    <!-- <td colspan="3"> -->
                    <td>
                        <select id="car_brand1"   name="car_brand[]" required="true" onchange="loadCarType(1)">
                            <?php foreach ($data1 as $key=>$dat):?>
                                <option value="<?php echo $dat['id'];?>"><?php echo $dat['text'];?></option>
                            <?php endforeach;?>
                        </select>
                     <!--   <input id="car_brand1" class="easyui-combotree" name="car_brand[]" required="true" onchange ="select_type(1)"
                           data-options="
                                width:160,
                                url: '<?php echo yii::$app->urlManager->createUrl(['car/combotree/get-car-brands']); ?>',
                                editable: false,
                                panelHeight:'auto',
                                lines:false
                           "
                        />  -->
                    </td>
                    <td>
                        <select id="car_type1"   name="car_type[]" required="true"  onchange ="search(1)" >
                        </select>
                    </td>
                    <td>
                        <input id="search1"  class="easyui-textbox" name="car_number[]" required="true" missingMessage="请输入需求数量"/>
                    </td>
                    <!-- <td>
                        <input id="search1"  class="easyui-textbox" name="car_number[]" required="true" missingMessage="请输入需求数量"/>
                    </td> -->
                       
                    
                     <td>
                        <input id="sea"  class="easyui-textbox" name="c_number[]"  />   
                    </td>
                
                </tr>

           
                <tr> 
                   <td colspan="5" align="center"> <input id="add" type="button" value="增加" onclick="add_car()" data-value="2"  /></td>
                </tr>



            </table>
       <!--  </div>  -->
       
    </div>
    
     <div title="其他信息" style="padding:15px">
        <!-- <div class="easyui-panel" title="流程追踪"    style="padding:5px 0px;"data-options="collapsible:true,collapsed:false,border:false,fit:false"> -->
            <table cellpadding="6" cellspacing="0" align="center"  width="100%" border="0">
               <tr>
                <td align="right"><div style="width:70px;">其他消息</div></td>
                <td>
                    <!-- <input class="easyui-textbox" name="note" required="true"          
                    /> -->

                    <textarea name="note" rows="7" cols="130">
                   
                    </textarea>
                </td>
                </tr>
            </table>
        <!-- </div> -->  
    </div>
</div>
     
<div class="fixed_div" style="float:right; margin-top:20px;">
    <input style="float:right; display:inline-block; margin:0 0 0 10px;" id="submi_id" type="button" name='submit' value="提交" onclick="tijiao()"/>
    <input style="float:right; display:inline-block; margin:0 10px;" id="next_id" type="button" value="下一步" onclick="next()"/>
    <input style="float:right; display:inline-block; margin:0 10px;" id="last_id" type="button" value="上一步" onclick="last()"/>  
    <input style="float:right; display:inline-block; margin:0 10px;" id="canc_id" type="button" value="取消" onclick="canc()"/>
</div>
<!-- <div class="fixed_div" style="float:right; margin-top:20px;">
    <input style="float:right; display:inline-block; margin:0 0 0 10px;" id="submi_id" type="button" name='submit' value="提交" onclick="tijiao()"/>
    <input style="float:right; display:inline-block; margin:0 10px;" id="next_id" type="button" value="下一步" onclick="next()"/>
    <input style="float:right; display:inline-block; margin:0 10px;" id="last_id" type="button" value="上一步" onclick="last()"/>  
    <input style="float:right; display:inline-block; margin:0 10px;" id="canc_id" type="button" value="取消" onclick="canc()"/>
</div> -->
</form>

<!-- <div id="easyui-dialog-process-repair-uploadimage"></div> -->

<!-- <iframe id="iframe-process-repair-uploadimage" name="iframe-process-repair-uploadimage" style="display:none;"></iframe> -->
<!-- <div id="easyui-dialog-process-repair-uploadimage"></div> -->
<!-- <script type="text/javascript" src="jquery.steps.min.js"></script> -->
<script>

    var step = 0;
    function next(){
        if(step !=2){
            step++;
        }   
        render(step);
        //判断 隐藏取消按钮
        if(step != 0){
       //alert('34') 
        var odi = document.getElementById("canc_id");
        odi.style.display = 'none';
        //alert('13')
        var odi1 = document.getElementById("last_id");
            odi1.style.display = 'block';
        }
    }
    function render(id){
        var t = $('#tt');
        var tabs = t.tabs('tabs');
        t.tabs('select', tabs[id].panel('options').title);
    }
    /*if(step == 0){
        //alert('12')
        console.log(step)
        var odi = document.getElementById("canc_id");
            odi.style.display = 'visible';
        var odi1 = document.getElementById("last_id");
            odi1.style.display = 'none';
    }*/ 
    
    //var step_l = 2;
    function last(){
        if(step !=0){
            step--;
        }
        render(step);
    }
   /* function render_l(id){
        var t = $('#tt');
        var tabs = t.tabs('tabs');
            t.tabs('select', tabs[id].panel('options').title);
    }*/


    //表单提交
    function tijiao() {
        //alert('123');
         var form = $('#easyui-form-add');
                    if(!form.form('validate')) return false;
                    var data = form.serialize();
                    $.ajax({
                        type: 'post',
                        url: "<?php echo yii::$app->urlManager->createUrl(['purchase/purchase-order/add']); ?>",
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.alert('添加成功',data.info,'info');
                                $('#easyui-dialog-purchase-order-index-add').dialog('close');
                                $('#easyui-datagrid-purchase-order-index').datagrid('reload');
                            }else{
                                $.messager.alert('添加失败',data.info,'error');
                            }
                        }
                    });       
    }

    
    //增加
    function add_car()
    {
        var data = $("#add").attr('data-value');
        
        var html ='<tr><td><select id="car_gary'+data+'"   name="car_gary[]"><option value="zhengche">整车</option></select></td><td><select id="car_brand'+data+'"   name="car_brand[]" required="true" onchange="loadCarType('+data+')">';
        //console.log(html);

        <?php foreach ($data1 as $key=>$dat):?>
            
            html += '<option value="<?php echo $dat["id"];?>"><?php echo $dat["text"];?></option>';
            
        <?php endforeach;?>

        html +='</select></td><td> <select id="car_type'+data+'"  name="car_type[]" required="true"  onchange ="search('+data+')"  >';
      
        html +='</select> </td><td><input id="search'+data+'" class="easyui-textbox" name="car_number[]" required="true" missingMessage="请输入数量"/> <!--<input  type="button" value="查询库存" onclick="search('+data+')" />--></td><td><input id="sea"  class="easyui-textbox" name="c_number[]"  />   </td><td> <input type="button" value="移除" onclick="del('+data+')" /><span id="inventory'+data+'" style="color:red"></span></td></tr>';
        
        $("#add").parent().parent().before(html);

        $("#add").attr('data-value',parseInt(data)+1);

		loadCarType(data);
        //console.log($("#add").attr('data-value',parseInt(data)+1));
        //select_type(data);
        //console.log(select_type(data));
        //$(this).prev(".selected")
        //alert('123');
    }
    //移除
    function del(id)
    {
        //alert($("car_brand"+id).parent().parent('tr'));
    $("#car_brand"+id).parent().parent().remove();
    }

	//加载车型
	function loadCarType(index){

		$.ajax({
            type: 'get',
            url: "<?php echo yii::$app->urlManager->createUrl(['car/car-type/get-list']); ?>",
            data: {brand_id:$('#car_brand'+index).val()},
            dataType: 'json',
            success: function(data){
				var str = '';
				$.each(data.rows, function(i,val){
				   str += '<option value="'+val.id+'">'+val.car_model_name+'</option>';
				});
				$('#car_type'+index).html(str);
                /*$("#inventory"+id).html(car_brand+car_type+' 当前可提'+data.count+'辆，库存锁定'+data.lock_count+'辆，仅供参考！');*/
                //$.messager.alert('车辆库存',car_brand+car_type+' 库存'+data.count+'辆，仅供参考！','info');
            }
        });
	}
	loadCarType(1);

    function search(id)
    {
        var car_brand = $("#car_brand"+id).val();
        var car_type = $("#car_type"+id).val();

        
        $.ajax({
            type: 'post',
            url: "<?php echo yii::$app->urlManager->createUrl(['process/car/search-number']); ?>&id=0",
            data: {car_brand:car_brand,car_model:car_type},
            dataType: 'json',
            success: function(data){
                /*$("#inventory"+id).html(car_brand+car_type+' 当前可提'+data.count+'辆，库存锁定'+data.lock_count+'辆，仅供参考！');*/
                //$.messager.alert('车辆库存',car_brand+car_type+' 库存'+data.count+'辆，仅供参考！','info');
            }
        });
    }

    var PurchaseOrderIndex2  = {
        init: function(){
            var easyuiForm = $('#easyui-form-add');
            easyuiForm.find('input[name=receiver_id]').combotree({
                url: "<?php echo yii::$app->urlManager->createUrl(['owner/combotree/get-owners']); ?>&isShowRoot=1",
                editable: false,
                panelHeight:'auto',
                panelWidth:300,
                lines:false
            });
        }
    };
    PurchaseOrderIndex2.init();



    //查询表单构建
    var searchForm = $('#easyui-form-add');
    searchForm.submit(function(){
        var data = {};
        var searchCondition = $(this).serializeArray();
        for(var i in searchCondition){
            data[searchCondition[i]['name']] = searchCondition[i]['value'];
        }
       /* $('#easyui-datagrid-car-inventory-index').datagrid('load',data);
        return false;*/
    });

    searchForm.find('select[name=regional]').combobox({
        valueField:'value',
        textField:'text',
        editable: false,
        panelHeight:'auto',
        onSelect: function(rec){
            $('#oc1').combobox('clear');
            var data = [{text:'不限',value:''},];
            //当前大区下所有的运营公司id
            var ocs = '';
            <?php foreach ($searchFormOptions['operating_company_id']  as $val):?>

                if(rec.value == '<?php echo $val['area'];?>'){
                    var a = {text:'<?php echo $val['name']?>',value:'<?php echo $val['id']?>'};
                    data.push(a);
                    //console.log(data);
                    if(ocs == '') {
                        ocs += '<?php echo $val['id'];?>';
                    }else{
                        ocs += ','+'<?php echo $val['id'];?>';
                    } 
                        
                    console.log(ocs);
                }
             <?php endforeach;?>
            if(rec.value == ''){
             var data = [{text:'不限',value:''},];
            }
            $('#oc1').combobox('loadData',data);
            $("#ocs1").val(ocs);
            searchForm.submit();
        }
    });
</script>
