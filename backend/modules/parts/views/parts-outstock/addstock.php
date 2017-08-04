<!--  <div style="padding:10px 40px 20px 40px">  -->
    <form id="easyui-form-addstock" class="easyui-form" method="post">
        <table cellpadding="8" cellspacing="0">
            <tr>
                <td><div style="width:85px;text-align:right;">车辆号牌：</div></td>
                <td>
                    <input id="easyui-form-car-fault-register-carCombogrid2" required class="easyui-textbox" style="width:160px;"  name="car_id"      />
                    <span id='tip'></span>
                </td>
                <td><div style="width:85px;text-align:right;">出库原因：</div></td>
                <td>
                    <textarea class="easyui-validatebox" style="width:160px;"  name="out_reason" required  ></textarea>
                </td>
                <td><div style="width:85px;text-align:right;">领用人：</div></td>
                <td>
                    <input class="easyui-textbox" style="width:160px;"  name="use_person" required  />
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">线下出库时间：</div></td>
                <td>
                    <input class="easyui-datetimebox" name="out_time" data-options="required:true,showSeconds:false" value="" style="width:160px;" missingMessage="请选择线下出库时间！">
                </td>
                <td><div style="width:85px;text-align:right;">大区：</div></td>
                <td>
                    <input class="easyui-textbox" style="width:160px;"  name="region" value="<?php echo $data['region']?>" disabled   />
                </td>
                <td><div style="width:85px;text-align:right;">运营公司：</div></td>
                <td>
                    <input class="easyui-textbox" style="width:160px;"  name="company_name" value="<?php echo $data['company_name']?>" disabled   />
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">仓储地点：</div></td>
                <td>
                    <input class="easyui-textbox" style="width:160px;"  name="warehouse_address"  value="<?php echo $data['warehouse_address']?>" disabled  />
                </td>
                <td><div style="width:85px;text-align:right;">车辆品牌：</div></td>
                <td>
                    <input class="easyui-textbox" style="width:160px;"  name="brand_name" value="<?php echo $data['brand_name']?>" disabled   />
                </td>
                <td><div style="width:85px;text-align:right;">配件类别：</div></td>
                <td>
                    <input class="easyui-textbox" style="width:160px;"  name="parts_type" value="<?php echo $data['parts_type']?>" disabled   />
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">配件种类：</div></td>
                <td>
                    <input class="easyui-textbox" style="width:160px;"  name="parts_kind"  value="<?php echo $data['parts_kind']?>" disabled  />
                </td>
                <td><div style="width:85px;text-align:right;">配件名称：</div></td>
                <td>
                    <input class="easyui-textbox" style="width:160px;"  name="parts_name" value="<?php echo $data['parts_name']?>" disabled   />
                </td>
                <td><div style="width:85px;text-align:right;">配件品牌：</div></td>
                <td>
                    <input class="easyui-textbox" style="width:160px;"  name="parts_brand" value="<?php echo $data['parts_brand']?>" disabled   />
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">厂家配件编码：</div></td>
                <td>
                    <input class="easyui-textbox" style="width:160px;"  name="vender_code"  value="<?php echo $data['vender_code']?>" disabled  />
                </td>
                <td><div style="width:85px;text-align:right;">我方配件编码：</div></td>
                <td>
                    <input class="easyui-textbox" style="width:160px;"  name="dst_code" value="<?php echo $data['dst_code']?>" disabled   />
                </td>
                <td><div style="width:85px;text-align:right;">单位：</div></td>
                <td>
                    <input class="easyui-textbox" style="width:160px;"  name="unit" value="<?php echo $data['unit']?>" disabled   />
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">主机厂参考价（元）：</div></td>
                <td>
                    <input class="easyui-textbox" style="width:160px;"  name="main_engine_price"  value="<?php echo $data['main_engine_price']?>" disabled  />
                </td>
                <td><div style="width:85px;text-align:right;">采购单价（元）：</div></td>
                <td>
                    <input class="easyui-textbox" style="width:160px;"  name="shop_price" value="<?php echo $data['shop_price']?>" disabled   />
                </td>
                <td><div style="width:85px;text-align:right;">出库单价（元）：</div></td>
                <td>
                    <input class="easyui-textbox" style="width:160px;"  name="out_price" value="<?php echo $data['out_price']?>" disabled   />
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">数量：</div></td>
                <td>
                    <input class="easyui-textbox" style="width:160px;"  name="out_number" required  /></td>
                <td><div style="width:85px;text-align:right;">规格：</div></td>
                <td>
                    <input class="easyui-textbox" style="width:160px;"  name="standard" value="<?php echo $data['standard']?>" disabled   />
                </td>
                <td><div style="width:85px;text-align:right;">型号：</div></td>
                <td>
                    <input class="easyui-textbox" style="width:160px;"  name="parts_model" value="<?php echo $data['parts_model']?>" disabled   />
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">参数：</div></td>
                <td>
                    <input class="easyui-textbox" style="width:160px;"  name="param"  value="<?php echo $data['param']?>" disabled  />
                <td><div style="width:85px;text-align:right;">保质期（月）：</div></td>
                <td>
                    <input class="easyui-textbox" style="width:160px;"  name="expiration_date" value="<?php echo $data['expiration_date']?>" disabled   />
                </td>
                <td><div style="width:85px;text-align:right;">保修期（月）：</div></td>
                <td>
                    <input class="easyui-textbox" style="width:160px;"  name="warranty_date" value="<?php echo $data['warranty_date']?>" disabled   />
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">适用车型：</div></td>
                <td>
                    <input class="easyui-textbox" style="width:160px;"  name="match_car"  value="<?php echo $data['match_car']?>" disabled  />
                <td><div style="width:85px;text-align:right;">配件来源：</div></td>
                <td>
                    <input class="easyui-textbox" style="width:160px;"  name="original_from" value="<?php echo $data['original_from']?>" disabled   />
                </td>
                <td><div style="width:85px;text-align:right;">配件供应商名称：</div></td>
                <td>
                    <input class="easyui-textbox" style="width:160px;"  name="original_from" value="<?php echo $data['original_from']?>" disabled   />
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">配件供应商编码：</div></td>
                <td>
                    <input class="easyui-textbox" style="width:160px;"  name="original_from_code"  value="<?php echo $data['original_from_code']?>" disabled  />
                <td><div style="width:85px;text-align:right;">正副厂：</div></td>
                <td>
                    <input class="easyui-textbox" style="width:160px;"  name="factory" value="<?php echo $data['factory']?>" disabled   />
                </td>
                <td><div style="width:85px;text-align:right;">配件生产商名称：</div></td>
                <td>
                    <input class="easyui-textbox" style="width:160px;"  name="product_company" value="<?php echo $data['product_company']?>" disabled   />
                </td>
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">配件生产商编号：</div></td>
                <td>
                    <input class="easyui-textbox" style="width:160px;"  name="product_company_code"  value="<?php echo $data['product_company_code']?>" disabled  />
                <td><div style="width:85px;text-align:right;">线下入库时间：</div></td>
                <td>
                    <input class="easyui-textbox" style="width:160px;"  name="under_in_warehouse_time" value="<?php echo $data['under_in_warehouse_time']?>" disabled   />
                </td>
            </tr>
        </table>
                    <input type="hidden" name="insert_id" value="<?php echo $data['insert_id']?>">
                    <input type="hidden" name="info_parts_id" value="<?php echo $data['info_parts_id']?>">
                    <input type="hidden" name="warehouse_address" value="<?php echo $data['warehouse_address']?>">
                    <input type="hidden" name="storage_quantity" value="<?php echo $data['storage_quantity']?>">
    </form>
<!-- </div> -->
<iframe id="iframe-process-repair-uploadimage" name="iframe-process-repair-uploadimage" style="display:none;"></iframe>
<div id="easyui-dialog-process-repair-uploadimage"></div>
<div id="easyui-dialog-process-repair-maintain-indicator-light"></div>

<script>  

$(function(){
    temp_car_id = "";
    
     $('#easyui-form-car-fault-register-carCombogrid3').combogrid({
         panelWidth: 450,
         panelHeight: 200,
      //   required: true,
         missingMessage: '请输入保养类别名称检索后从下拉列表里选择一项！',
         onHidePanel:function(){
             var _combogrid3 = $(this);
             var value = _combogrid3.combogrid('getValue');
             var text = _combogrid3.combogrid('textbox').val();
             var row = _combogrid3.combogrid('grid').datagrid('getSelected');
             if(!row){ //没有选择表格行但输入有检索字符串时，提示并清除检索字符串
                 if(text && value == text){
                     $.messager.show(
                         {
                             title: '无效值',
                             msg:'【' + text + '】不是有效值！请重新输入保养类别名称检索后，从下拉列表里选择一项！'
                         }
                     );
                     _combogrid3.combogrid('clear');
                 }
             }else{ //注意：若选择了表格行但是原本应显示为text的车牌号不存在，则改成显示车架号为text！
                 if(!row.id){
                     _combogrid3.combogrid('setText', row.maintain_type);
                     //_combogrid.combogrid('textbox').val(row.vehicle_dentification_number); //这种不好，因为当输入框再次获得焦点时会自动显示value而非text.
                 }
             }
         },
         queryParams: {
            id:temp_car_id
         },
         delay: 800,
         mode:'remote',
         idField: 'id',
         textField: 'maintain_type',
         url: '<?= yii::$app->urlManager->createUrl(['car/maintain-record/get-type-by-car-id']); ?>',
         onLoadSuccess: function(obj) { 
            
         },
        onSelect: function(){
            temp_car_id = $('#easyui-form-car-fault-register-carCombogrid2').combogrid('grid').datagrid('getSelected').id;
            $('#easyui-form-car-fault-register-carCombogrid3').combogrid({
                  queryParams: {
                    id:temp_car_id
                 }
            });
             console.log(temp_car_id);
          },
         method: 'get',
         scrollbarSize:0,
         pagination: true,
         pageSize: 10,
         pageList: [10,20,30],
         fitColumns: true,
         rownumbers: true,
         columns: [[
             {field:'id',title:'保养类别ID',width:40,align:'center',hidden:true},
             {field:'maintain_type',title:'保养类别',width:100,align:'center'}
            
         ]]
     });
     
     $('#easyui-form-car-fault-register-carCombogrid2').combogrid({
         panelWidth: 450,
         panelHeight: 200,
      //   required: true,
         missingMessage: '请输入车牌号/车架号检索后从下拉列表里选择一项！',
         onHidePanel:function(){
             var _combogrid2 = $(this);
             var value = _combogrid2.combogrid('getValue');
             var text = _combogrid2.combogrid('textbox').val();
             var row = _combogrid2.combogrid('grid').datagrid('getSelected');
             if(!row){ //没有选择表格行但输入有检索字符串时，提示并清除检索字符串
                 if(text && value == text){
                     $.messager.show(
                         {
                             title: '无效值',
                             msg:'【' + text + '】不是有效值！请重新输入车牌号/车架号检索后，从下拉列表里选择一项！'
                         }
                     );
                     _combogrid2.combogrid('clear');
                 }
             }else{ //注意：若选择了表格行但是原本应显示为text的车牌号不存在，则改成显示车架号为text！
                 if(!row.plate_number){
                     _combogrid2.combogrid('setText', row.vehicle_dentification_number);
                     //_combogrid.combogrid('textbox').val(row.vehicle_dentification_number); //这种不好，因为当输入框再次获得焦点时会自动显示value而非text.
                 }
             }
         },
         delay: 800,
         mode:'remote',
         idField: 'id',
         textField: 'plate_number',
         url: '<?= yii::$app->urlManager->createUrl(['car/fault/get-cars']); ?>',
         onLoadSuccess: function(obj) { 
            
         },
        onSelect: function(rec){
            temp_car_id = $('#easyui-form-car-fault-register-carCombogrid2').combogrid('grid').datagrid('getSelected').id;
            $('#easyui-form-car-fault-register-carCombogrid3').combogrid({
                  queryParams: {
                    id:temp_car_id
                 }
            });
             //console.log(temp_car_id);
             /*if(temp_car_id){
                
             }*/
            

          




          },
         method: 'get',
         scrollbarSize:0,
         pagination: true,
         pageSize: 10,
         pageList: [10,20,30],
         fitColumns: true,
         rownumbers: true,
         columns: [[
             {field:'id',title:'车辆ID',width:40,align:'center',hidden:true},
             {field:'plate_number',title:'车牌号',width:100,align:'center'},
             {field:'vehicle_dentification_number',title:'车架号',width:150,align:'center'}
         ]]
     });

     
      
            



   

     

});

                 
 
  
</script>