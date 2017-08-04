<form id="easyui-form-car-type-lease-add" class="easyui-form">
  <div
        class="easyui-panel"
        title="基本参数"
        style="width:100%;margin-bottom:5px;"
        closable="false"
        collapsible="false"
        minimizable="false"
        maximizable="false"
        border="false"
    >
   
        <table border="0" cellpadding="5" cellspacing="0">
            
            <tr>
                <td align="right"><div style="width:70px;">车辆品牌</div></td>
                <td>
                    <input class="easyui-combotree" name="brand_id" id="brand_id_add" required="true"
                           data-options="
                                width:160,
                                url: '<?php echo yii::$app->urlManager->createUrl(['car/combotree/get-car-brands']); ?>',
                                editable: false,
                                panelHeight:'auto',
                                lines:false
                           "
                    />
                </td>
                <td align="right"><div style="width:70px;">车辆型号</div></td>
                <td>
                    <select
                        class="easyui-combobox"
                        required="true"
                        style="width:160px;"
                        name="car_type_id"
                        id="car_type_id_add"
                        editable="false"
                        data-options="panelHeight:'auto'"
                    >
                        <option value="">请选择</option>
                        <?php //foreach($config['car_type_id'] as $val){ ?>
                      <!--  <option value="<?php// echo $val['value']; ?>"><?php //echo $val['text']; ?></option> -->
                        <?php// } ?>
                    </select>
                </td>
            </tr>
			<tr>
                <td><div style="text-align:right;">省份</div></td>
                <td width="23%">
                   <select class="easyui-combobox" style="width:100px;" id="province_id"   name="province_id"  editable=false   >
                   		<option value=""></option>
                   		<?php foreach($provinces as $row): ?>
                            <option value="<?php echo  $row['region_id']?>"><? echo $row['region_name']?></option>
                        <?php endforeach;?>
                   	 </select>
                </td> 
                <td align="right">城市</td>
                <td width="23%">
                   <select class="easyui-combobox" style="width:100px;"  id="city_id"    name="city_id" required="true" editable=false   >
                   		<option value=""></option>
                   </select>
                </td>                
                
            </tr>			
             <tr>
                <td><div style="text-align:right;">车辆运营公司</div></td>
                <td colspan="4">
                    <input class="easyui-combotree" name="operating_company_id"
                           data-options="
                                width:454,
                                url: '<?php echo yii::$app->urlManager->createUrl(['operating/combotree/get-operating-company']); ?>',
                                editable: false,
                                panelHeight:'auto',
                                lines:false,
                                required:true,
                                missingMessage:'请选择运营公司'
                           "
                        />
                </td>
            </tr>
            
            
        </table>
	<div class="easyui-tabs" id="tt"> 
	<div title="长租" style="padding:20px;">
		<table cellpadding="5" cellspacing="0">            
            <tr>               
                <td align="right"><div style="width:70px;">启用状态</div></td>
                <td>
                    <select
                        class="easyui-combobox"                       
                        style="width:160px;"
                        name="is_enable_long"
                        editable="false"
                        data-options="panelHeight:'auto'"
                    >
                        
                        <?php foreach($config['long_lease_status'] as $val){ ?>
                        <option value="<?php echo $val['value']; ?>"><?php echo $val['text']; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>             
				<td align="right"><div style="width:70px;">月租金</div></td>
                <td>
                    <input
                        class="easyui-textbox"                      
                        style="width:160px;"
                        name="month_price_long"
                        validType="length[100]"
                        data-options="panelHeight:'auto'"
                    />（￥）
                </td>
				<td align="right"><div style="width:70px;">年租金</div></td>
                <td>
                    <input
                        class="easyui-textbox"                       
                        style="width:160px;"
                        name="year_price_long"
                        validType="length[100]"
                        data-options="panelHeight:'auto'"
                    />（￥）
                </td>
            </tr>
              <tr>
                <td align="right"><div style="width:70px;">租车押金</div></td>
                <td>
                    <input
                        class="easyui-textbox"                       
                        style="width:160px;"
                        name="deposit_long"
                        validType="length[100]"
                        data-options="panelHeight:'auto'"
                    />（￥）
                </td>
				<td align="right"><div style="width:70px;">违章押金</div></td>
                <td>
                    <input
                        class="easyui-textbox"                      
                        style="width:160px;"
                        name="wz_deposit_long"
                        validType="length[100]"
                        data-options="panelHeight:'auto'"
                    />（￥）
                </td>
            </tr>
			</tr>
            <tr>
                <td align="right"><div style="width:70px;">提车点1</div></td>
                 <td>
                    <select
                        class="easyui-combobox"
                        required="true"
                        style="width:160px;"
                        name="service_site_long[]"
                        editable="false"
                        data-options="panelHeight:'auto'"
						id="a"
						
                    >
                        <option value="">请选择</option>
                        <?php foreach($config['service_site'] as $val){ ?>
                        <option value="<?php echo $val['value']; ?>"><?php echo $val['text']; ?></option>
                        <?php } ?>
                    </select>
                </td>			
            </tr>			
			<tr><td colspan="3"><!-- 描述 --></td></tr>
			<tr>
                <td align="right"><div style="width:70px;">提车点2</div></td>
                 <td>
                    <select
                        class="easyui-combobox"
                        required="true"
                        style="width:160px;"
                        name="service_site_long[]"
                        editable="false"
                        data-options="panelHeight:'auto'"						
                    >
                        <option value="">请选择</option>
                        <?php foreach($config['service_site'] as $val){ ?>
                        <option value="<?php echo $val['value']; ?>"><?php echo $val['text']; ?></option>
                        <?php } ?>
                    </select>
                </td>			
            </tr>
			 <tr >
            		<td ></td>
               		<td > <input id="add_site_long" type="button" value="增加" onclick="addSiteLong()" data-value="2" /></td>
             </tr>
			<tr><td colspan="3"><!-- 描述 --></td></tr>
        </table>
    </div>
	<div title="分时" style="padding:20px;">
		<table cellpadding="5" cellspacing="0">            
            <tr>               
                <td align="right"><div style="width:70px;">启用状态</div></td>
                <td>
                    <select
                        class="easyui-combobox"                       
                        style="width:160px;"
                        name="is_enable_time"
                        editable="false"
                        data-options="panelHeight:'auto'"
                    >
                       
                        <?php foreach($config['cainiao_status'] as $val){ ?>
                        <option value="<?php echo $val['value']; ?>"><?php echo $val['text']; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right"><div style="width:70px;">起步里程</div></td>
                <td>
                    <input
                        class="easyui-textbox"                       
                        style="width:160px;"
                        name="starting_mileage"
                        validType="length[100]"
                        data-options="panelHeight:'auto'"
                    />（km）
                </td>
				<td align="right"><div style="width:70px;">起步价格</div></td>
                <td>
                    <input
                        class="easyui-textbox"                       
                        style="width:160px;"
                        name="starting_price"
                        validType="length[100]"
                        data-options="panelHeight:'auto'"
                    />（￥）
                </td>
            </tr>
              <tr>
                <td align="right"><div style="width:70px;">租车押金</div></td>
                <td>
                    <input
                        class="easyui-textbox"                       
                        style="width:160px;"
                        name="deposit_time"
                        validType="length[100]"
                        data-options="panelHeight:'auto'"
                    />（￥）
                </td>
				<td align="right"><div style="width:70px;">违章押金</div></td>
                <td>
                    <input
                        class="easyui-textbox"                       
                        style="width:160px;"
                        name="wz_deposit_time"
                        validType="length[100]"
                        data-options="panelHeight:'auto'"
                    />（￥）
                </td>
            </tr>	
			<tr>
                <td align="right"><div style="width:70px;">保险费用</div></td>
                <td>
                    <input
                        class="easyui-textbox"                       
                        style="width:160px;"
                        name="insurance_expense"
                        validType="length[100]"
                        data-options="panelHeight:'auto'"
                    />（￥）
                </td>
				<td align="right"><div style="width:70px;">不计免赔</div></td>
                <td>
                    <input
                        class="easyui-textbox"                       
                        style="width:160px;"
                        name="insurance_bjmp"
                        validType="length[100]"
                        data-options="panelHeight:'auto'"
                    />（￥）
                </td>
            </tr>			
            <tr>
                <td align="right"><div style="width:70px;">提车点1</div></td>
                 <td>
                    <select
                        class="easyui-combobox"
                        required="true"
                        style="width:160px;"
                        name="service_site_time[]"
                        editable="false"
                        data-options="panelHeight:'auto'"
						id="a"
						
                    >
                        <option value="">请选择</option>
                        <?php foreach($config['service_site'] as $val){ ?>
                        <option value="<?php echo $val['value']; ?>"><?php echo $val['text']; ?></option>
                        <?php } ?>
                    </select>
                </td>			
            </tr>	
			<tr><td colspan="2"><span style="font-size:18px;font-weight:bold;">时租设置</span></td></tr>
			<tr>
                <td align="right"><div style="width:70px;">时租金</div></td>
                <td>
                    <input
                        class="easyui-textbox"                       
                        style="width:160px;"
                        name="time_price"
                        validType="length[100]"
                        data-options="panelHeight:'auto'"
                    />（￥）
                </td>
			</tr>
			<tr>
				<td align="right"><div style="width:70px;">超时费用</div></td>
                <td>
                    <input
                        class="easyui-textbox"                        
                        style="width:160px;"
                        name="time_out_price_"
                        validType="length[100]"
                        data-options="panelHeight:'auto'"
                    />（￥）
                </td>
            </tr>			
<tr><td colspan="2"><span style="font-size:18px;font-weight:bold;">日租设置</span></td></tr>
			<tr>
                <td align="right"><div style="width:70px;">日租金</div></td>
                <td>
                    <input
                        class="easyui-textbox"                        
                        style="width:160px;"
                        name="day_price"
                        validType="length[100]"
                        data-options="panelHeight:'auto'"
                    />（￥）
                </td>
			</tr>
			<tr>
				<td align="right"><div style="width:70px;">超时费用</div></td>
                <td>            
       
         <input
                        class="easyui-textbox"                       
                        style="width:160px;"
                        name="day_out_price_h"
                        validType="length[100]"
                        data-options="panelHeight:'auto'"
                    />元/小时
		  <input
                        class="easyui-textbox"                       
                        style="width:160px;"
                        name="day_out_price_d"
                        validType="length[100]"
                        data-options="panelHeight:'auto'"
                    />元/天
                </td>
            </tr>			


			
			<tr><td colspan="3"><!-- 描述 --></td></tr>
			<tr>
                <td align="right"><div style="width:70px;">提车点2</div></td>
                 <td>
                    <select
                        class="easyui-combobox"
                        required="true"
                        style="width:160px;"
                        name="service_site_time[]"
                        editable="false"
                        data-options="panelHeight:'auto'"
						onSelect:function(record){
                                      alert("hi");
                                }
                    >
                        <option value="">请选择</option>
                        <?php foreach($config['service_site'] as $val){ ?>
                        <option value="<?php echo $val['value']; ?>"><?php echo $val['text']; ?></option>
                        <?php } ?>
                    </select>
                </td>			
            </tr>
			 <tr >
            		<td ></td>
               		<td > <input id="add_site_time" type="button" value="增加" onclick="addSiteTime()" data-value="2" /></td>
             </tr>
			<tr><td colspan="3"><!-- 描述 --></td></tr>
        </table>
    </div>
	<div title="菜鸟" style="padding:20px;">
		<table cellpadding="5" cellspacing="0">            
            <tr>               
                <td align="right"><div style="width:70px;">启用状态</div></td>
                <td>
                    <select
                        class="easyui-combobox"                       
                        style="width:160px;"
                        name="is_enable_cainiao"
                        editable="false"
                        data-options="panelHeight:'auto'"
                    >
                       
                        <?php foreach($config['cainiao_status'] as $val){ ?>
                        <option value="<?php echo $val['value']; ?>"><?php echo $val['text']; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right"><div style="width:70px;">运营库存</div></td>
                <td>
                    <input
                        class="easyui-textbox"                       
                        style="width:160px;"
                        name="stock_number"
                        validType="length[100]"
                        data-options="panelHeight:'auto'"
                    />（台）
                </td>
				<td align="right"><div style="width:70px;">月租金</div></td>
                <td>
                    <input
                        class="easyui-textbox"                       
                        style="width:160px;"
                        name="month_price"
                        validType="length[100]"
                        data-options="panelHeight:'auto'"
                    />（￥）
                </td>
            </tr>
              <tr>
                <td align="right"><div style="width:70px;">租车押金</div></td>
                <td>
                    <input
                        class="easyui-textbox"                        
                        style="width:160px;"
                        name="deposit"
                        validType="length[100]"
                        data-options="panelHeight:'auto'"
                    />（￥）
                </td>
				<td align="right"><div style="width:70px;">违章押金</div></td>
                <td>
                    <input
                        class="easyui-textbox"                       
                        style="width:160px;"
                        name="wz_deposit"
                        validType="length[100]"
                        data-options="panelHeight:'auto'"
                    />（￥）
                </td>
            </tr>
			</tr>
            <tr>
                <td align="right"><div style="width:70px;">提车点1</div></td>
                 <td>
                    <select
                        class="easyui-combobox"
                        required="true"
                        style="width:160px;"
                        name="service_site[]"
                        editable="false"
                        data-options="panelHeight:'auto'"
						id="a"
						
                    >
                        <option value="">请选择</option>
                        <?php foreach($config['service_site'] as $val){ ?>
                        <option value="<?php echo $val['value']; ?>"><?php echo $val['text']; ?></option>
                        <?php } ?>
                    </select>
                </td>			
            </tr>			
			<tr><td colspan="3"><!-- 描述 --></td></tr>
			<tr>
                <td align="right"><div style="width:70px;">提车点2</div></td>
                 <td>
                    <select
                        class="easyui-combobox"
                        required="true"
                        style="width:160px;"
                        name="service_site[]"
                        editable="false"
                        data-options="panelHeight:'auto'"
						onSelect:function(record){
                                      alert("hi");
                                }
                    >
                        <option value="">请选择</option>
                        <?php foreach($config['service_site'] as $val){ ?>
                        <option value="<?php echo $val['value']; ?>"><?php echo $val['text']; ?></option>
                        <?php } ?>
                    </select>
                </td>			
            </tr>
			 <tr >
            		<td ></td>
               		<td > <input id="add_site" type="button" value="增加" onclick="addSite()" data-value="2" /></td>
             </tr>
			<tr><td colspan="3"><!-- 描述 --></td></tr>
        </table>
    </div>
</div>


   
</form>
<script>

var i = 2;
var j = 2;
var k = 2;
//增加菜鸟场站
function addSite()
{
 	i++;
	var data = $("#add_site").attr('data-value');
	var html ='<tr> \
				<td><div style="width:85px;text-align:right;">提车点'+i+'</div></td> \
				<td> \
					<select id="service_site'+data+'" class="easyui-combobox"  name="service_site[]" required="true"  style="width:210px"  missingMessage="请选择" > \
						<option value=""></option>';
						<?php foreach ($config['service_site'] as $v):?>
			html +=			  '<option value="<?php echo $v['value']?>"><?php echo $v['text']?></option>';
						<?php endforeach;?>
		 
			html +=	'		</select>\
				</td>\
				<td><input type="button" value="移除" onclick="del_site('+data+')" /><span id="inventory'+data+'" style="color:red"></span></td>\
				</tr>';



	$("#add_site").parent().parent().before(html);
	$("#add_site").attr('data-value',parseInt(data)+1);
	//select_type(data);
	//$(this).prev(".selected")
	//alert('123');
}
//移除菜鸟场站
function del_site(id)
{

	//alert($("user_id"+id).parent().parent('tr'));
	$("#service_site"+id).parent().parent().remove();
}

//添加分时场站
function addSiteTime()
{
	j++;
	var data = $("#add_site_time").attr('data-value');
	var html ='<tr> \
				<td><div style="width:85px;text-align:right;">提车点'+j+'</div></td> \
				<td> \
					<select id="service_site_time'+data+'" class="easyui-combobox"  name="service_site_time[]" required="true"  style="width:210px"  missingMessage="请选择" > \
						<option value=""></option>';
						<?php foreach ($config['service_site'] as $v):?>
			html +=			  '<option value="<?php echo $v['value']?>"><?php echo $v['text']?></option>';
						<?php endforeach;?>
		 
			html +=	'		</select>\
				</td>\
				<td><input type="button" value="移除" onclick="del_site_time('+data+')" /><span id="inventory'+data+'" style="color:red"></span></td>\
				</tr>';



	$("#add_site_time").parent().parent().before(html);
	$("#add_site_time").attr('data-value',parseInt(data)+1);
	//select_type(data);
	//$(this).prev(".selected")
	//alert('123');
}
//移除分时场站
function del_site_time(id)
{

	//alert($("user_id"+id).parent().parent('tr'));
	$("#service_site_time"+id).parent().parent().remove();
}

//添加长租场站
function addSiteLong()
{
	k++;
	var data = $("#add_site_long").attr('data-value');
	var html ='<tr> \
				<td><div style="width:85px;text-align:right;">提车点'+k+'</div></td> \
				<td> \
					<select id="service_site_long'+data+'" class="easyui-combobox"  name="service_site_long[]" required="true"  style="width:210px"  missingMessage="请选择" > \
						<option value=""></option>';
						<?php foreach ($config['service_site'] as $v):?>
			html +=			  '<option value="<?php echo $v['value']?>"><?php echo $v['text']?></option>';
						<?php endforeach;?>
		 
			html +=	'		</select>\
				</td>\
				<td><input type="button" value="移除" onclick="del_site_long('+data+')" /><span id="inventory'+data+'" style="color:red"></span></td>\
				</tr>';



	$("#add_site_long").parent().parent().before(html);
	$("#add_site_long").attr('data-value',parseInt(data)+1);
	
}
//移除长租场站
function del_site_long(id)
{

	$("#service_site_long"+id).parent().parent().remove();
}

$('#brand_id_add').combotree({
	 onChange: function (n,o){
		//alert(n);
		$('#car_type_id_add').combobox('clear');
		var brand_id = $('#brand_id_add').combobox('getValue');
		//console.log(brand_id) 
		//return 0;
		//console.log(o)
	   // console.log(car_model_name)
	   
		$.ajax({
			   url:"<?php echo yii::$app->urlManager->createUrl(['car/car-type-lease/check3']); ?>",
			   type:'post',
			   data:{brand_id:brand_id},
			   dataType:'json',
			   success:function(data){
				   console.log(data)
				  // return 1;
				//console.log(data)
				   /* $('#type_id').combobox({
					   valueField:'',
					   textField:'',
					   editable: false,
					   panelHeight:'auto',
					   data: data
				   });*/
					//$('#type_id').combobox('setValues','');
					var current_type = [];
					
					$.each(data,function(i, value){
						var a =[];
						   //console.log(value);
							//var a =[];
							//console.log(a);
							//current_type = value.maintain_type
							a['value'] = value.value;
							a['text'] = value.text;
						   // a['text'] = value['text'];
						   //console.log(a);
							current_type.push(a);
						   //console.log(current_type);
							
					});
					
					$("#car_type_id_add").combobox("setValue",'');
					$("#car_type_id_add").combobox("loadData",current_type);
				}
		});
	}
 }); 

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
  
  
	</script>

