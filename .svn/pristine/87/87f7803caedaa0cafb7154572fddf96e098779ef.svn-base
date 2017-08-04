<div style="padding:10px 40px 20px 40px">  
    <form id="easyui-form-station-service-add" class="easyui-form" method="post">
        <div >
            <ul class="ulforform-resizeable">
            	<li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">修理厂名称</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-textbox" style="width:160px;"  name="site_name" required="true" prompt="请输入公司全称"/>
                    </div>
                </li>
                <!-- <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">站点简称</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-textbox" style="width:160px;"  name="site_short_name" required="true" />
                    </div>
                </li> -->
                <!-- <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">站点类型</div>
                    <div class="ulforform-resizeable-input">
                      <select class="easyui-combobox"  style="width:160px;"  name="type" required="true"   editable=false>
	                    	<option value="1">厂家特约服务站</option>
	                    	<option value="2">我方合作服务站</option>
	                    	<option value="3">厂家4S店/修理厂</option>
	                    	<option value="4">其他类型</option>
                   	 </select>
                    </div>
                </li> -->
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">修理厂类别</div>
                    <div class="ulforform-resizeable-input">
                      <select class="easyui-combobox"  style="width:160px;"  name="type2" required="true" editable=false>
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
	                    	<option value="1">二级</option>
	                    	<option value="1">三级</option>
                   		 </select>
					</div>
                 </li> -->
                 <!-- <li class="ulforform-resizeable-group hide" style="display:none">
                	<div class="ulforform-resizeable-title"></div>
					<div class="ulforform-resizeable-input"></div>
                 </li> -->
                 <!-- <li class="ulforform-resizeable-group">
                	<div class="ulforform-resizeable-title">所属厂商</div>
					<div class="ulforform-resizeable-input" editable=false>
						<input class="easyui-combotree" name="brand_id"
                           data-options="
                                width:160,
                                url: '<?php echo yii::$app->urlManager->createUrl(['car/combotree/get-car-brands']); ?>',
                                editable: false,
                                panelHeight:'auto',
                                lines:false
                           "
                         />
					</div>
                 </li> -->
                  <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">座机号码</div>
                    <div class="ulforform-resizeable-input">
                        <!-- <input class="easyui-textbox" style="width:160px;"  name="landline" required="true"  validType="match[/^((0\d{2,3})-)(\d{7,8})(-(\d{3,}))?$/]" invalidMessage="座机号码格式错误！" prompt="座机号码格式 区号-号码" missingMessage="请输入座机号码"/> -->
                        <input class="easyui-textbox" style="width:50px;"  name="landline[]" required="true" prompt="区号" missingMessage="请输入区号"/>
                        <input class="easyui-textbox" style="width:85px;"  name="landline[]" required="true" prompt="电话号" missingMessage="请输入电话号"/>
                        <input class="easyui-textbox" style="width:50px;"  name="landline[]" prompt="分机" missingMessage="请输入分机"/>
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
                <li class="ulforform-resizeable-group-single">
                    <div class="ulforform-resizeable-title">授权品牌</div>
                    <div class="ulforform-resizeable-input">
                        <?php foreach($data1 as $row): ?>
                        <input type="checkbox" name="brand_id[]" value="<?php echo  $row['id']?>" /><?php echo  $row['text']?>
                        <?php endforeach;?>
                    </div>
                    <!-- <div class="ulforform-resizeable-input">
                        <input type="checkbox" name="brand_id[]" value="7" />东风特种车
                    </div>
                    <div class="ulforform-resizeable-input">
                        <input type="checkbox" name="brand_id[]" value="3" />比亚迪
                    </div>
                    <div class="ulforform-resizeable-input">
                        <input type="checkbox" name="brand_id[]" value="4" />瑞驰
                    </div>
                     <div class="ulforform-resizeable-input">
                        <input type="checkbox" name="brand_id[]" value="9" />上汽大通
                    </div> -->
                   
                </li>

                <li class="ulforform-resizeable-group-single">
                    <div class="ulforform-resizeable-title">地址</div>
                    <div class="ulforform-resizeable-input">
                       <select class="easyui-combobox" style="width:100px;" id="province_id"   name="province_id" required="true" editable=false   >
                   		<option value=""></option>
                   		<?php foreach($provinces as $row): ?>
                            <option value="<?php echo  $row['region_id']?>"><? echo $row['region_name']?></option>
                        <?php endforeach;?>
                   	  </select>省
                    </div>
                    <div class="ulforform-resizeable-input">
                       <select class="easyui-combobox" style="width:100px;"  id="city_id"    name="city_id" required="true" editable=false   >
                   		<option value=""></option>
                   		 </select>市
                    </div>
                    <div class="ulforform-resizeable-input"> 
                       <select class="easyui-combobox" style="width:100px;"  id="county_id"  name="county_id" required="true"  editable=false   >
                   		<option value=""></option>
                   		 </select>区/县
                    </div>
                   
                </li>
                <li class="ulforform-resizeable-group-single">
                    <div class="ulforform-resizeable-title"></div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-textbox" style="width:470px;"  name="address" required="true" prompt="详细地址" />
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
                        <input type="checkbox" name="provide_services[]" value="维修" />维修
                    </div>
                    <div class="ulforform-resizeable-input">
                        <input type="checkbox" name="provide_services[]" value="保养" />保养
                    </div>
                    <div class="ulforform-resizeable-input">
                        <input type="checkbox" name="provide_services[]" value="定损" />定损
                    </div>
                    <!-- <div class="ulforform-resizeable-input">&nbsp;</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-textbox" style="width:285px;"  name="provide_services[]"  />
                    </div> -->
                </li>
                <li class="ulforform-resizeable-group-single">
                    <div class="ulforform-resizeable-title">服务备注</div>
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
	})
})



</script>