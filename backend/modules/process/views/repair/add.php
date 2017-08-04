<div style="padding:10px 40px 20px 40px">  
    <form id="easyui-form-process-repair-add" class="easyui-form" method="post">
        <div >
            <ul class="ulforform-resizeable">
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">工单类型</div>
                    <div class="ulforform-resizeable-input">
                      <select class="easyui-combobox"  style="width:160px;"  name="type" required="true"   missingMessage="请选择工单类型">
	                    	<option value="1">车辆报修</option>
	                    	<!-- <option value="2">车辆出险</option> -->
                   	 </select>
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                	<div class="ulforform-resizeable-title">工单来源</div>
					<div class="ulforform-resizeable-input">
						<select class="easyui-combobox" style="width:160px;"   name="source" required="true"   missingMessage="请选择工单来源">
	                    	<option value="1">400电话</option>
                   		 </select>
					</div>
                 </li>
                  <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">报修人姓名</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-textbox" style="width:160px;"  name="repair_name" required="true" missingMessage="请输入报修人姓名"/>
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">手机号码、电话号码</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-textbox" style="width:160px;"     name="tel"    required="true"   validType="match[/((0\d{2,3})-)(\d{7,8})(-(\d{3,}))?$|1[3|4|5|7|8][0-9]\d{8}$/]" invalidMessage="电话、手机格式错误！" prompt="电话号码格式 区号-号码" />
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">来电时间</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-datetimebox" style="width:160px;"   name="tel_time" required="true" missingMessage="请输入来电时间"/>
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">紧急程度</div>
                    <div class="ulforform-resizeable-input">
                    	<select class="easyui-combobox" style="width:160px;"   name="urgency" required="true"   missingMessage="请选择紧急程度">
	                    	<option value="1">一般紧急</option>
	                    	<option value="2">比较紧急</option>
	                    	<option value="3">非常紧急</option>
                   		 </select>
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">车牌号</div>
                    <div class="ulforform-resizeable-input">
                        <select class="easyui-combobox" style="width:160px;"   name="car_no" required="true"   missingMessage="请选择车牌号">
                        	<option value=""></option>
                        	<?php foreach ($cars as $car):?>
	                    	<option value="<?php echo $car['plate_number']?>"><?php echo $car['plate_number']?></option>
	                    	<?php endforeach;?>
                    </select>
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">故障发生时间</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-datetimebox" style="width:160px;"   name="fault_start_time" required="true" missingMessage="请输入故障发生时间"/>
                    </div>
                </li>
                <li class="ulforform-resizeable-group-single">
                    <div class="ulforform-resizeable-title">故障地点</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-textbox" style="width:470px;"   name="address"  missingMessage="请输入故障地点"/>
                    </div>
                </li>
                <li class="ulforform-resizeable-group-single">
                    <div class="ulforform-resizeable-title">所处方位</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-textbox" style="width:470px;"   name="bearing"  missingMessage="具体方位描述" prompt="请输入车辆在该地点的具体位置"/>
                    </div>
                </li>
                <li class="ulforform-resizeable-group-single">
                    <div class="ulforform-resizeable-title">来电内容简述</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-textbox" style="width:470px;"   name="desc" required="true" missingMessage="必填项"  prompt="概括客户来电内容，15字以内。"
                        validType="length[50]"/>
                    </div>
                </li>
                <li class="ulforform-resizeable-group-single">
                    <div class="ulforform-resizeable-title">来电内容记录</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-textbox" style="height:100px;width:470px;"   name="tel_content" required="true" missingMessage="必填项" data-options="multiline:true" prompt="请记录客户反馈的原话，200字以内。"
                        validType="length[200]"/>
                    </div>
                </li>
                <li class="ulforform-resizeable-group-single">
                    <div class="ulforform-resizeable-title">所需服务</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-textbox" style="width:470px;"   name="need_serve" required="true" missingMessage="必填项" prompt="请填写客户要求提供的服务。"/>
                    </div>
                </li>
            </ul>
        </div>
    </form>
</div>