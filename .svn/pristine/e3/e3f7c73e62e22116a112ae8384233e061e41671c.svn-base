<!--  <div style="padding:10px 40px 20px 40px">  -->
        <div class="easyui-panel" title="合同信息" style="padding:5px 0px;"
         data-options="collapsible:true,collapsed:false,border:false,fit:false">
            <ul class="ulforform-resizeable">
                <li class="ulforform-resizeable-group" >
                    <div class="ulforform-resizeable-title">合同类型</div>
                    <div class="ulforform-resizeable-input">
                      <input  class="easyui-textbox" style="width:160px;" readonly  disabled value="<?php  echo !empty($result['contract_type']) ? $result['contract_type']:'';?>" disable  />
                    </div>
                </li>                 
                <li class="ulforform-resizeable-group" >
                    <div class="ulforform-resizeable-title">客户名称</div>
                    <div class="ulforform-resizeable-input">
                      <input  class="easyui-textbox" style="width:160px;" readonly disabled   value="<?php  echo !empty($result['name']) ? $result['name']:'';?>"   />
                    </div>
                </li>
                <li class="ulforform-resizeable-group" >
                    <div class="ulforform-resizeable-title">单月租金</div>
                    <div class="ulforform-resizeable-input">
                      <input class="easyui-textbox" style="width:160px;" readonly disabled   value="<?php  echo !empty($result['monthly_rent']) ? $result['monthly_rent']:'';?>"    />
                    </div>
                </li>                 
                <li class="ulforform-resizeable-group" >
                    <div class="ulforform-resizeable-title">首期应收租金</div>
                    <div class="ulforform-resizeable-input">
                      <input  class="easyui-textbox" style="width:160px;" readonly disabled   value="<?php  echo !empty($result['rent']) ? $result['rent']:'';?>"      />
                    </div>
                </li>
                <li class="ulforform-resizeable-group" >
                    <div class="ulforform-resizeable-title">应收押金</div>
                    <div class="ulforform-resizeable-input">
                      <input  class="easyui-textbox" style="width:160px;" readonly disabled   value="<?php  echo !empty($result['margin']) ? $result['margin']:'';?>"     />
                    </div>
                </li>                 
                <li class="ulforform-resizeable-group" >
                    <div class="ulforform-resizeable-title">发票类型</div>
                    <div class="ulforform-resizeable-input">
                      <input  class="easyui-textbox" style="width:160px;" readonly disabled   value="<?php  echo !empty($result['invoice_type']) ? $result['invoice_type']:'';?>"      />
                    </div>
                </li>   

            </ul>
        </div>
        <form id="easyui-form-process-car-finance-confirm-from" class="easyui-form" method="post">
        <div class="easyui-panel" title="财务确认" style="padding:5px 0px;"
         data-options="collapsible:true,collapsed:false,border:false,fit:false">
         	<ul class="ulforform-resizeable">
         		<input type="hidden" name="id" value="<?php echo $result['id']?>" />
         		<input type="hidden" name="step_id" value="<?php echo $result['step_id']?>" />
         		<input type="hidden" name="template_id" value="<?php echo $result['template_id']?>" />
                 <li class="ulforform-resizeable-group">
                	<div class="ulforform-resizeable-title">首期实收租金</div>
					<div class="ulforform-resizeable-input">
						<input class="easyui-textbox" style="width:160px;"  name="real_rent"  prompt="非必填"  />
					</div>
                 </li>

                 <li class="ulforform-resizeable-group">
                	<div class="ulforform-resizeable-title">实收押金</div>
					<div class="ulforform-resizeable-input">
						<input class="easyui-textbox" style="width:160px;"  name="real_margin"   prompt="非必填"   />
					</div>
                 </li>
                 
                 <li class="ulforform-resizeable-group-single">
                    <div class="ulforform-resizeable-title">补充说明</div>
                    <div class="ulforform-resizeable-input">
                        <input class="easyui-textbox" style="width:470px;height:50px;"   name="confirm_remark"   data-options="multiline:true" prompt="200字符以内。如果没有补充，请留空。"
                        validType="length[200]"/>
                    </div>
                </li>
         	</ul>
        </div>
    </form>
