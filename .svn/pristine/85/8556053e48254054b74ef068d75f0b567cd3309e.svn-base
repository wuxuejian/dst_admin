<div style="padding:10px 40px 20px 40px">  
    <form id="easyui-form-station-service-edit" class="easyui-form" method="post">
    <input type="hidden" name="id" value="<?php echo $result['id']?>"/>
        <div >
            <ul class="ulforform-resizeable">
            	<li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">修理厂名称</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-textbox" style="width:160px;"  name="site_name" required="true" prompt="请输入服务站点的公司全称"/>
                    </div>
                </li>
                <!-- <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">站点简称</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-textbox" style="width:160px;"  name="site_short_name" required="true" />
                    </div>
                </li> -->
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">修理厂类别</div>
                    <div class="ulforform-resizeable-input">
                      <select class="easyui-combobox"  style="width:160px;"  id="type" name="type2" required="true"   editable=false>
	                    	<option value=""></option>
                            <option value="1">4S店</option>
                            <option value="2">二类</option>
                            <option value="3">三类</option>
                            <option value="4">其他</option>
                   	 </select>
                    </div>
                </li>
                <!-- <li class="ulforform-resizeable-group level">
                	<div class="ulforform-resizeable-title">站点级别</div>
					<div class="ulforform-resizeable-input">
						<select class="easyui-combobox" style="width:160px;"   name="level"  editable=false   >
	                    	<option value="1">一级</option>
	                    	<option value="2">二级</option>
	                    	<option value="3">三级</option>
                   		 </select>
					</div>
                 </li> -->
                 <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">座机号码</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-textbox" style="width:50px;"  name="landline1" value=""/>
                        <input class="easyui-textbox" style="width:85px;"  name="landline2" value=""/>
                        <input class="easyui-textbox" style="width:50px;"  name="landline3" value=""/>
                    </div>
                </li>
                 <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">合作方式</div>
                    <div class="ulforform-resizeable-input">
                        
                        <select class="easyui-combobox"  style="width:160px;"  name="team_type" required="true" editable=false>
                            <option value=""></option>
                            <option value="1">合作协议</option>
                            <option value="2">无合作协议</option>
                           
                     </select>
                    </div>
                </li>


                 <!-- <li class="ulforform-resizeable-group hide" style="display:none">
                	<div class="ulforform-resizeable-title"></div>
					<div class="ulforform-resizeable-input"></div>
                 </li> -->
                 <li class="ulforform-resizeable-group-single">
                    <div class="ulforform-resizeable-title">授权品牌</div>
                    <div class="ulforform-resizeable-input">
                        <input type="checkbox" name="brand_id[]" value="5" <?php if(in_array('北汽新能源', $result['arr_brand_name'])):  echo 'checked';next($result['arr_brand_name']); endif;?> />北汽新能源
                        <input type="checkbox" name="brand_id[]" value="7" <?php if(in_array('东风特种车', $result['arr_brand_name'])):  echo 'checked';next($result['arr_brand_name']); endif;?> />东风特种车
                        <input type="checkbox" name="brand_id[]" value="3" <?php if(in_array('比亚迪', $result['arr_brand_name'])):  echo 'checked';next($result['arr_brand_name']); endif;?> />比亚迪
                        <input type="checkbox" name="brand_id[]" value="4" <?php if(in_array('瑞驰', $result['arr_brand_name'])):  echo 'checked';next($result['arr_brand_name']); endif;?> />瑞驰
                        <input type="checkbox" name="brand_id[]" value="9" <?php if(in_array('上汽大通', $result['arr_brand_name'])):  echo 'checked';next($result['arr_brand_name']); endif;?> />上汽大通

                        <input type="checkbox" name="brand_id[]" value="15" <?php if(in_array('依维柯', $result['arr_brand_name'])):  echo 'checked';next($result['arr_brand_name']); endif;?> />依维柯
                        <input type="checkbox" name="brand_id[]" value="16" <?php if(in_array('陕汽牌', $result['arr_brand_name'])):  echo 'checked';next($result['arr_brand_name']); endif;?> />陕汽牌
                        <input type="checkbox" name="brand_id[]" value="17" <?php if(in_array('特斯拉', $result['arr_brand_name'])):  echo 'checked';next($result['arr_brand_name']); endif;?> />上汽大通
                        <input type="checkbox" name="brand_id[]" value="18" <?php if(in_array('鸿雁牌', $result['arr_brand_name'])):  echo 'checked';next($result['arr_brand_name']); endif;?> />鸿雁牌
                    </div>
                 </li>
                
                <li class="ulforform-resizeable-group-single">
                    <div class="ulforform-resizeable-title">地址</div>
                    <div class="ulforform-resizeable-input">
                       <select class="easyui-combobox" style="width:100px;" id="province_id"   name="province_id"  editable=false   >
                   		<option value=""></option>
                   		<?php foreach($provinces as $row): ?>
                            <option value="<?php echo  $row['region_id']?>"><? echo $row['region_name']?></option>
                        <?php endforeach;?>
                   	  </select>省
                    </div>
                    <div class="ulforform-resizeable-input">
                       <select class="easyui-combobox" style="width:100px;"  id="city_id"    name="city_id" required="true" editable=false   >
                   		<option value=""></option>
                   		<?php foreach($citys as $row): ?>
                            <option value="<?php echo  $row['region_id']?>"><? echo $row['region_name']?></option>
                        <?php endforeach;?>
                   		 </select>市
                    </div>
                    <div class="ulforform-resizeable-input"> 
                       <select class="easyui-combobox" style="width:100px;"  id="county_id"  name="county_id" required="true"  editable=false   >
                   		<option value=""></option>
                   		<?php foreach($countys as $row): ?>
                            <option value="<?php echo  $row['region_id']?>"><? echo $row['region_name']?></option>
                        <?php endforeach;?>
                   		 </select>区/县
                    </div>
                   
                </li>
                <li class="ulforform-resizeable-group-single">
                    <div class="ulforform-resizeable-title"></div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-textbox" style="width:470px;"  name="address" required="true" />
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">主要负责人</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-textbox" style="width:160px;"  name="main_duty_name" required="true" />
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">手机号码</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-textbox" style="width:160px;"  name="main_duty_tel" required="true" validType="match[/^1[3|4|5|7|8][0-9]\d{8}$/]" invalidMessage="手机号码格式错误！" />
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">其他联系人</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-textbox" style="width:160px;"  name="other_duty_name"  />
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">手机号码</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-textbox" style="width:160px;"  name="other_duty_tel"  validType="match[/^1[3|4|5|7|8][0-9]\d{8}$/]" invalidMessage="手机号码格式错误！"/>
                    </div>
                </li>
                
                <li class="ulforform-resizeable-group-single">
                    <div class="ulforform-resizeable-title">提供服务</div>
                    <div class="ulforform-resizeable-input">
                        <input type="checkbox" name="provide_services[]" value="维修" <?php if($result['provide_services']){if(in_array('维修', $result['provide_services'])):  echo 'checked';next($result['provide_services']); endif;}?> />维修
                    </div>
                    <div class="ulforform-resizeable-input">
                        <input type="checkbox" name="provide_services[]" value="保养" <?php if($result['provide_services']){if(in_array('保养', $result['provide_services'])):  echo 'checked';next($result['provide_services']); endif;}?> />保养
                    </div>
                    <div class="ulforform-resizeable-input">
                        <input type="checkbox" name="provide_services[]" value="定损" <?php if($result['provide_services']){if(in_array('定损', $result['provide_services'])):  echo 'checked';next($result['provide_services']); endif;}?> />定损
                    </div>
                   
                </li>
                <li class="ulforform-resizeable-group-single">
                    <div class="ulforform-resizeable-title">备注</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-textbox" style="height:100px;width:470px;"   name="remark"  data-options="multiline:true" prompt="200字以内。"
                        validType="length[200]"/>
                    </div>
                </li>
            </ul>
        </div>
    </form>
</div>

<script>
$('#easyui-form-station-service-edit').form('load',<?= json_encode($result); ?>);
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


	$('select[name=type]').combobox({

		onChange: function (n,o) {
			if(n == 1)
			{
				$(".level").css('display','block');
				$(".hide").css('display','none');
			}else{
				$(".level").css('display','none');
				$(".hide").css('display','block');
			}
		}
	});

	if($('#type').val() == 1)
	{
		$(".level").css('display','block');
		$(".hide").css('display','none');
	}else{
		$(".level").css('display','none');
		$(".hide").css('display','block');
	}
})



</script>