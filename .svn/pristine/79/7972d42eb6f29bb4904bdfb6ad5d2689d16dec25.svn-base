<div style="padding:10px 40px 20px 40px">  
    <form id="easyui-form-process-car-jiaoche-udelivery-form" class="easyui-form" method="post">
    	<input type="hidden" name="id" value="<?php echo $result['id']?>"/>
        <div>
            <ul class="ulforform-resizeable">
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">车牌号</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-textbox" style="width:160px;"  name="car_no"  value="<?php  echo !empty($result['car_no']) ? $result['car_no']:'';?>" readonly />
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">已交付</div>
                    <div class="ulforform-resizeable-input">
                        <select class="easyui-combobox"  name="is_delivery" style="width:160px;" data-options="editable:false"  required="true"   >
                        	<option value=""></option>
	                    	<option value="1" <?php if(!empty($result['is_delivery']) && $result['is_delivery']==1)  echo 'selected'?>>是</option>
	                    	<option value="0" <?php if(!empty($result['is_delivery']) && $result['is_delivery']==0)  echo 'selected'?>>否</option>
                   		</select>
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">交车单附件</div>
                    <div class="ulforform-resizeable-input">
                        <input type="file" name="verify_car_photo" required="true" missingMessage="请上传交车单附件"/>
                    </div>
                </li>
                <li class="ulforform-resizeable-group">
                    <div class="ulforform-resizeable-title">交车时间</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-datetimebox" name="jiaoche_time"  style="width: 158px" required="true" missingMessage="请填写交车时间"/>
                    </div>
                </li>
                <li class="ulforform-resizeable-group-single">
                    <div class="ulforform-resizeable-title">备注</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-textbox" style="height:100px;width:470px;"   name="remark"  data-options="multiline:true" prompt="200字以内。"
                        validType="length[200]"   value="<?php  echo !empty($result['remark']) ? $result['remark']:'';?>" />
                    </div>
                </li>
               
            </ul>
        </div>
    </form>
</div>
