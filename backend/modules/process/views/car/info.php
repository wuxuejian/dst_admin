<div class="easyui-tabs" data-options="fit:true,border:false"> 
	<div title="（1）提车申请" style="padding:15px">
        <div class="easyui-panel" title="流程追踪"   style="padding:5px 0px;"data-options="collapsible:true,collapsed:false,border:false,fit:false">
            <table cellpadding="6" cellspacing="0" align="center"  width="100%" border="0">
	            <tr>
	                <td align="right" width="15%">流程节点：</td>
	                <td>发起申请</td>
	                <td align="right" width="15%">执行进度：</td>
	                <td>完成</td>
	            </tr>
	            <tr>
	                <td align="right" width="15%">执行角色：</td>
	                <td><?php echo !empty($result['role_name']) ? $result['role_name']:''?></td>
	                <td align="right" width="15%">执行人：</td>
	                <td><?php echo !empty($result['username']) ? $result['username']:''?></td>
	            </tr>
	            <tr>
	                <td align="right" width="15%">执行时间：</td>
	                <td colspan="3"><?php echo  !empty($result['shenqing_time']) ? date('Y-m-d H:i',$result['shenqing_time']) :'';?></td>
	            </tr>
            </table>
        </div>
        
        <div class="easyui-panel" title="提交信息" style="padding:5px 0px;"
         data-options="collapsible:true,collapsed:false,border:false,fit:false">
         	<table cellpadding="6" cellspacing="0" align="center"  width="100%" border="0">
	            <tr>
	                <td align="right" width="15%">客户名称：</td>
	                <td><?php echo !empty($result['name']) ? $result['name']:'';?></td>
	                <td align="right" width="15%">合同编号：</td>
	                <td><?php echo !empty($result['contract_number']) ? $result['contract_number']:'';?></td>
	            </tr>

	            <tr>
	                <td align="right" width="15%">提车时间：</td>
	                <td><?php echo  !empty($result['extract_time']) ? $result['extract_time']:''?></td>
	                <td align="right" width="15%">提车方式：</td>
	                <td>
	                <?php 
	                if($result['extract_way']==1)
	                {
	                	echo '客户自提';
	                }elseif ($result['extract_way']==2)
	                {
	                	echo '送车上门';
	                }else{
	                	echo '';
	                }	
	                ?>
	                </td>
	            </tr>
	            <tr>
	                <td align="right" width="15%">客户方申请人姓名：</td>
	                <td><?php echo !empty($result['shenqingren']) ? $result['shenqingren']:'';?></td>
	                <td align="right" width="15%">电话：</td>
	                <td><?php echo !empty($result['tel']) ? $result['tel']:'';?></td>
	            </tr>
	            <tr>
	                <td align="right" width="15%">提车需求：</td>
	                <td colsapn="3"><?php echo !empty($result['car_type']) ? $result['car_type']:''?></td>
	            </tr>
	            <!--  <tr>
	                <td align="right" width="15%">补充说明：</td>
	                <td colspan="3"><?php //echo !empty($result['extract_remark']) ? $result['extract_remark']:''?></td>
	            </tr>-->
            </table>
        </div>
    </div>
    
    <div title="（2）部门审批" style="padding:15px">
		<div class="easyui-panel" title="流程追踪"    style="padding:5px 0px;"data-options="collapsible:true,collapsed:false,border:false,fit:false">
            <table cellpadding="6" cellspacing="0" align="center"  width="100%" border="0">
	            <tr>
	                <td align="right" width="15%">流程节点：</td>
	                <td>部门审批</td>
	                <td align="right" width="15%">执行进度：</td>
	                <td><?php echo !empty($process_data[0]['plan']) ? $process_data[0]['plan']:''?></td>
	            </tr>
	            <tr>
	                <td align="right" width="15%">执行角色：</td>
	                <td><?php echo !empty($process_data[0]['role_name']) ? $process_data[0]['role_name']:''?></td>
	                <td align="right" width="15%">执行人：</td>
	                <td><?php echo !empty($process_data[0]['name']) ? $process_data[0]['name']:''?></td>
	            </tr>
	            <tr>
	                <td align="right" width="15%">执行时间：</td>
	                <td><?php echo  !empty($process_data[0]['time']) ? date('Y-m-d H:i',$process_data[0]['time']) :'';?></td>
	                <td align="right" width="15%">执行时限：</td>
	                <td><?php echo !empty($process_data[0]['is_timely']) ? $process_data[0]['is_timely']:''?></td>
	            </tr>
            </table>
        </div>
        
        <div class="easyui-panel" title="提交信息" style="padding:5px 0px;"
         data-options="collapsible:true,collapsed:false,border:false,fit:false">
         	<table cellpadding="6" cellspacing="0" align="center"  width="100%" border="0">
	            <tr>
	                <td align="right" width="15%">审批结果：</td>
	                <td><?php echo !empty($process_data[0]['res']) ? $process_data[0]['res'] :'';?></td>
	                <td align="right" width="15%">补充说明：</td>
	                <td><?php echo !empty($process_data[0]['remark']) ? $process_data[0]['remark'] :'';?></td>
	            </tr>

            </table>
        </div>
    </div>
    
     <div title="（3）车管确认" style="padding:15px">
		<div class="easyui-panel" title="流程追踪"    style="padding:5px 0px;"data-options="collapsible:true,collapsed:false,border:false,fit:false">
            <table cellpadding="6" cellspacing="0" align="center"  width="100%" border="0">
	            <tr>
	                <td align="right" width="15%">流程节点：</td>
	                <td>确认车辆库存</td>
	                <td align="right" width="15%">执行进度：</td>
	                <td><?php echo !empty($process_data[1]['plan']) ? $process_data[1]['plan']:''?></td>
	            </tr>
	            <tr>
	                <td align="right" width="15%">执行角色：</td>
	                <td><?php echo !empty($process_data[1]['role_name']) ? $process_data[1]['role_name']:''?></td>
	                <td align="right" width="15%">执行人：</td>
	                <td><?php echo !empty($process_data[1]['name']) ? $process_data[1]['name']:''?></td>
	            </tr>
	            <tr>
	                <td align="right" width="15%">执行时间：</td>
	                <td><?php echo  !empty($process_data[1]['time']) ? date('Y-m-d H:i',$process_data[1]['time']) :'';?></td>
	                <td align="right" width="15%">执行时限：</td>
	                <td><?php echo !empty($process_data[1]['is_timely']) ? $process_data[1]['is_timely']:''?></td>
	            </tr>
            </table>
        </div>
        
        <div class="easyui-panel" title="提交信息" style="padding:5px 0px;"
         data-options="collapsible:true,collapsed:false,border:false,fit:false">
         	<table cellpadding="6" cellspacing="0" align="center"  width="100%" border="0">
	            <tr>
	                <td align="right" width="15%">审批结果：</td>
	                <td><?php echo !empty($process_data[1]['res']) ? $process_data[1]['res'] :'';?></td>
	                <td align="right" width="15%">补充说明：</td>
	                <td><?php echo !empty($process_data[1]['remark']) ? $process_data[1]['remark'] :'';?></td>
	            </tr>
	            
            </table>
        </div>
    </div>
    <div title="（4）售后确认" style="padding:15px">
		<div class="easyui-panel" title="流程追踪"    style="padding:5px 0px;"data-options="collapsible:true,collapsed:false,border:false,fit:false">
            <table cellpadding="6" cellspacing="0" align="center"  width="100%" border="0">
	            <tr>
	                <td align="right" width="15%">流程节点：</td>
	                <td>确认提车地点负责人</td>
	                <td align="right" width="15%">执行进度：</td>
	                <td><?php echo !empty($process_data[2]['plan']) ? $process_data[2]['plan']:''?></td>
	            </tr>
	            <tr>
	                <td align="right" width="15%">执行角色：</td>
	                <td><?php echo !empty($process_data[2]['role_name']) ? $process_data[2]['role_name']:''?></td>
	                <td align="right" width="15%">执行人：</td>
	                <td><?php echo !empty($process_data[2]['name']) ? $process_data[2]['name']:''?></td>
	            </tr>
	            <tr>
	                <td align="right" width="15%">执行时间：</td>
	                <td><?php echo  !empty($process_data[2]['time']) ? date('Y-m-d H:i',$process_data[2]['time']) :'';?></td>
	                <td align="right" width="15%">执行时限：</td>
	                <td><?php echo !empty($process_data[2]['is_timely']) ? $process_data[2]['is_timely']:''?></td>
	            </tr>
            </table>
        </div>
        
        <div class="easyui-panel" title="提交信息" style="padding:5px 0px;"
         data-options="collapsible:true,collapsed:false,border:false,fit:false">
         	<table cellpadding="6" cellspacing="0" align="center"  width="100%" border="0">
	            <tr>
	                <td align="right" width="15%">审批结果：</td>
	                <td><?php echo !empty($process_data[2]['res']) ? $process_data[2]['res'] :'';?></td>
	                <td align="right" width="15%">补充说明：</td>
	                <td><?php echo !empty($result['tiche_remark']) ? $result['tiche_remark'] :'';?></td>
	            </tr>
	            <tr>
	            	<td align="right" width="15%">提车点：</td>
	                <td colspan="3"><?php $tiche_site = !empty($result['tiche_site']) ? $result['tiche_site'] :''; ?>
	               	<?php  if(is_array($tiche_site)): ?> 
	                <?php foreach ($tiche_site as $v): ?>  	
	                  	<span><?php echo $v['site']?>；负责人：<?php echo $v['user_id']?>；整备车型：<?php echo  !empty($v['brand_type']) ? $v['brand_type']:'';?> : <?php echo !empty($v['car_number']) ? $v['car_number'].'辆':'';?></span><br/>
	                <?php endforeach;?>
	                <?php endif;?>
	                 </td>
	            </tr>
            </table>
        </div>
    </div>
    
      <div title="（5）备车信息" style="padding:15px">
		<div class="easyui-panel" title="流程追踪"    style="padding:5px 0px;"data-options="collapsible:true,collapsed:false,border:false,fit:false">
            <table cellpadding="6" cellspacing="0" align="center"  width="100%" border="0">
	            <tr>
	                <td align="right" width="15%">流程节点：</td>
	                <td>登记整备车辆信息</td>
	                <td align="right" width="15%">执行进度：</td>
	                <td><?php echo !empty($process_data[3]['plan']) ? $process_data[3]['plan']:''?></td>
	            </tr>
	            <tr>
	                <td align="right" width="15%">执行角色：</td>
	                <td><?php echo !empty($process_data[3]['role_name']) ? $process_data[3]['role_name']:''?></td>
	                <td align="right" width="15%">执行人：</td>
	                <td><?php echo !empty($process_data[3]['name']) ? $process_data[3]['name']:''?></td>
	            </tr>
	            <tr>
	                <td align="right" width="15%">执行时间：</td>
	                <td><?php echo  !empty($process_data[3]['time']) ? date('Y-m-d H:i',$process_data[3]['time']) :'';?></td>
	                <td align="right" width="15%">执行时限：</td>
	                <td><?php echo !empty($process_data[3]['is_timely']) ? $process_data[3]['is_timely']:''?></td>
	            </tr>
            </table>
        </div>
        <div style="overflow-x: auto; overflow-y: auto; height: 400px; width:821px;">
        	<table id="archive-tiche" ></table>
	     	<div class="easyui-panel" title="提交信息" style="padding:5px 0px;"
	         data-options="collapsible:true,collapsed:false,border:false,fit:false" id="tiche-toolbar">
	   
	        </div>
        </div>
    </div>
    
    <div title="（6）登记收款方式" style="padding:15px">
		<div class="easyui-panel" title="流程追踪"    style="padding:5px 0px;"data-options="collapsible:true,collapsed:false,border:false,fit:false">
            <table cellpadding="6" cellspacing="0" align="center"  width="100%" border="0">
	            <tr>
	                <td align="right" width="15%">流程节点：</td>
	                <td>登记收款方式</td>
	                <td align="right" width="15%">执行进度：</td>
	                <td><?php echo !empty($process_data[4]['plan']) ? $process_data[4]['plan']:''?></td>
	            </tr>
	            <tr>
	                <td align="right" width="15%">执行角色：</td>
	                <td><?php echo !empty($process_data[4]['role_name']) ? $process_data[4]['role_name']:''?></td>
	                <td align="right" width="15%">执行人：</td>
	                <td><?php echo !empty($process_data[4]['name']) ? $process_data[4]['name']:''?></td>
	            </tr>
	            <tr>
	                <td align="right" width="15%">执行时间：</td>
	                <td><?php echo  !empty($process_data[4]['time']) ? date('Y-m-d H:i',$process_data[4]['time']) :'';?></td>
	                <td align="right" width="15%">执行时限：</td>
	                <td><?php echo !empty($process_data[4]['is_timely']) ? $process_data[4]['is_timely']:''?></td>
	            </tr>
            </table>
        </div>
        
        <div class="easyui-panel" title="提交信息" style="padding:5px 0px;"
         data-options="collapsible:true,collapsed:false,border:false,fit:false">
         	<table cellpadding="6" cellspacing="0" align="center"  width="100%" border="0">
	            <tr>
	                <td align="right" width="15%">审批结果：</td>
	                <td><?php echo !empty($process_data[4]['res']) ? $process_data[4]['res'] :'';?></td>
	                <td align="right" width="15%">收款方式：</td>
	                <td><?php echo !empty($result['proceeds']) ? $result['proceeds'] :'';?></td>
	            </tr>
	            <tr>
	                <td align="right" width="15%">月租金：</td>
	                <td><?php 
	                
	                $rent = !empty($result['rent']) ? $result['rent'] :''; 
	                $arr = json_decode($rent,true);
	                if(is_array($arr)){
	                	foreach ($arr as $k=>$v){
	                		echo "<span>{$k}:{$v}元</span><br/>";
	                	}
	                }
	                ?></td>
	                <td align="right" width="15%">保证金总额：</td>
	                <td><?php echo !empty($result['margin']) ? $result['margin'].'元' :'';?></td>
	            </tr>
				<tr>
	                <td align="right" width="20%">客户转账银行水单：</td>
	                <td colspan="3">
						<?php
							if($result['transfer_accounts_img']){
								echo "<a href='{$result['transfer_accounts_img']}' target='_b'><img src='{$result['transfer_accounts_img']}' height='100px' width='100px'/></a>";
							}else {
								echo "无";
							}
						?>
					</td>
	            </tr>

            </table>
        </div>
    </div>
    
    <div title="（7）确认收款方式" style="padding:15px">
		<div class="easyui-panel" title="流程追踪"    style="padding:5px 0px;"data-options="collapsible:true,collapsed:false,border:false,fit:false">
            <table cellpadding="6" cellspacing="0" align="center"  width="100%" border="0">
	            <tr>
	                <td align="right" width="15%">流程节点：</td>
	                <td>确认收款方式</td>
	                <td align="right" width="15%">执行进度：</td>
	                <td><?php echo !empty($process_data[5]['plan']) ? $process_data[5]['plan']:''?></td>
	            </tr>
	            <tr>
	                <td align="right" width="15%">执行角色：</td>
	                <td><?php echo !empty($process_data[5]['role_name']) ? $process_data[5]['role_name']:''?></td>
	                <td align="right" width="15%">执行人：</td>
	                <td><?php echo !empty($process_data[5]['name']) ? $process_data[5]['name']:''?></td>
	            </tr>
	            <tr>
	                <td align="right" width="15%">执行时间：</td>
	                <td><?php echo  !empty($process_data[5]['time']) ? date('Y-m-d H:i',$process_data[5]['time']) :'';?></td>
	                <td align="right" width="15%">执行时限：</td>
	                <td><?php echo !empty($process_data[5]['is_timely']) ? $process_data[5]['is_timely']:''?></td>
	            </tr>
            </table>
        </div>
        
        <div class="easyui-panel" title="提交信息" style="padding:5px 0px;"
         data-options="collapsible:true,collapsed:false,border:false,fit:false">
         	<table cellpadding="6" cellspacing="0" align="center"  width="100%" border="0">
	            <tr>
	                <td align="right" width="15%">审批结果：</td>
	                <td><?php echo !empty($process_data[5]['res']) ? $process_data[5]['res'] :'';?></td>
	                <td align="right" width="15%">补充说明：</td>
	                <td><?php echo !empty($process_data[5]['remark']) ? $process_data[5]['remark'] :'';?></td>
	            </tr>
            </table>
        </div>
    </div>
    
    
    <div title="（8）交车信息" style="padding:15px">
		<div class="easyui-panel" title="流程追踪"    style="padding:5px 0px;"data-options="collapsible:true,collapsed:false,border:false,fit:false">
            <table cellpadding="6" cellspacing="0" align="center"  width="100%" border="0">
	            <tr>
	                <td align="right" width="15%">流程节点：</td>
	                <td>登记交车信息</td>
	                <td align="right" width="15%">执行进度：</td>
	                <td><?php echo !empty($process_data[6]['plan']) ? $process_data[6]['plan']:''?></td>
	            </tr>
	            <tr>
	                <td align="right" width="15%">执行角色：</td>
	                <td><?php echo !empty($process_data[6]['role_name']) ? $process_data[6]['role_name']:''?></td>
	                <td align="right" width="15%">负责人：</td>
	                <td><?php echo !empty($process_data[6]['name']) ? $process_data[6]['name']:''?></td>
	            </tr>
	            <tr>
	                <td align="right" width="15%">执行时间：</td>
	                <td><?php echo  !empty($process_data[6]['time']) ? date('Y-m-d H:i',$process_data[6]['time']) :'';?></td>
	                <td align="right" width="15%">执行时限：</td>
	                <td><?php echo !empty($process_data[6]['is_timely']) ? $process_data[6]['is_timely']:''?></td>
	            </tr>
	            <tr>
	                <td align="right" width="15%">已交付：</td>
	                <td><?php echo !empty($result['delivery']) ? $result['delivery'] :0;?></td>
	                <td align="right" width="15%">未交付：</td>
	                <td><?php echo !empty($result['no_delivery']) ? $result['no_delivery'] :0;?></td>
	            </tr>
	            <tr>
	                <td align="right" width="15%">需求数量：</td>
	                <td colspan="3"><?php echo !empty($result['number']) ? $result['number'] :0;?></td>
	            </tr>
            </table>
        </div>
        
        <div style="overflow-x: auto; overflow-y: auto; height: 400px; width:821px;">
	        <table id="archive-jiaoche" ></table>
	        <div class="easyui-panel" title="提交信息" style="padding:5px 0px;"
	         data-options="collapsible:true,collapsed:false,border:false,fit:false"  id="jiaoche-toolbar">
	         	<a>客户名称:<?php echo !empty($result['customer_name']) ? $result['customer_name'] :'';?></a>
	         	<a>客户电话:<?php echo !empty($result['customer_tel']) ? $result['customer_tel'] :'';?></a>
	         	<a target="_blank" href="<?php echo !empty($result['extract_auth_image']) ? $result['extract_auth_image'] : "javascript:$.messager.alert('查看提车授权书','没有上传！','info');";?>" class="easyui-linkbutton" data-options="iconCls:'icon-search'"><?php echo '查看提车授权书'; ?></a>
			 	<a target="_blank" href="<?php echo !empty($result['extract_user_image']) ? $result['extract_user_image'] : "javascript:$.messager.alert('查看提车人证件附件','没有上传！','info');";?>" class="easyui-linkbutton" data-options="iconCls:'icon-search'"><?php echo '查看提车人证件附件'; ?></a>
	        </div>
        </div>
    </div>
    
    <div title="（9）填写租金信息" style="padding:15px">
		<div class="easyui-panel" title="流程追踪"    style="padding:5px 0px;"data-options="collapsible:true,collapsed:false,border:false,fit:false">
            <table cellpadding="6" cellspacing="0" align="center"  width="100%" border="0">
	            <tr>
	                <td align="right" width="15%">流程节点：</td>
	                <td>登记应收租金信息</td>
	                <td align="right" width="15%">执行进度：</td>
	                <td><?php echo !empty($process_data[7]['plan']) ? $process_data[7]['plan']:''?></td>
	            </tr>
	            <tr>
	                <td align="right" width="15%">执行角色：</td>
	                <td><?php echo !empty($process_data[7]['role_name']) ? $process_data[7]['role_name']:''?></td>
	                <td align="right" width="15%">执行人：</td>
	                <td><?php echo !empty($process_data[7]['name']) ? $process_data[7]['name']:''?></td>
	            </tr>
	            <tr>
	                <td align="right" width="15%">执行时间：</td>
	                <td><?php echo  !empty($process_data[7]['time']) ? date('Y-m-d H:i',$process_data[7]['time']) :'';?></td>
	                <td align="right" width="15%">执行时限：</td>
	                <td><?php echo !empty($process_data[7]['is_timely']) ? $process_data[7]['is_timely']:''?></td>
	            </tr>
            </table>
        </div>
        <div style="overflow-x: auto; overflow-y: auto; height: 400px; width:821px;">
        	<table id="archive-rent" ></table>
	     	<div class="easyui-panel" title="提交信息" style="padding:5px 0px;"
	         data-options="collapsible:true,collapsed:false,border:false,fit:false" id="rent-toolbar">
	   
	        </div>
        </div>
    </div>
    
    
    <div title="（10）确认租金信息" style="padding:15px">
		<div class="easyui-panel" title="流程追踪"    style="padding:5px 0px;"data-options="collapsible:true,collapsed:false,border:false,fit:false">
            <table cellpadding="6" cellspacing="0" align="center"  width="100%" border="0">
	            <tr>
	                <td align="right" width="15%">流程节点：</td>
	                <td>确认租金信息</td>
	                <td align="right" width="15%">执行进度：</td>
	                <td><?php echo !empty($process_data[8]['plan']) ? $process_data[8]['plan']:''?></td>
	            </tr>
	            <tr>
	                <td align="right" width="15%">执行角色：</td>
	                <td><?php echo !empty($process_data[8]['role_name']) ? $process_data[8]['role_name']:''?></td>
	                <td align="right" width="15%">执行人：</td>
	                <td><?php echo !empty($process_data[8]['name']) ? $process_data[8]['name']:''?></td>
	            </tr>
	            <tr>
	                <td align="right" width="15%">执行时间：</td>
	                <td><?php echo  !empty($process_data[8]['time']) ? date('Y-m-d H:i',$process_data[8]['time']) :'';?></td>
	                <td align="right" width="15%">执行时限：</td>
	                <td><?php echo !empty($process_data[8]['is_timely']) ? $process_data[8]['is_timely']:''?></td>
	            </tr>
            </table>
        </div>
        <div class="easyui-panel" title="提交信息" style="padding:5px 0px;"
         data-options="collapsible:true,collapsed:false,border:false,fit:false">
         	<table cellpadding="6" cellspacing="0" align="center"  width="100%" border="0">
	            <tr>
	                <td align="right" width="15%">审批结果：</td>
	                <td><?php echo !empty($process_data[8]['res']) ? $process_data[8]['res'] :'';?></td>
	                <td align="right" width="15%">补充说明：</td>
	                <td><?php echo !empty($process_data[8]['remark']) ? $process_data[8]['remark'] :'';?></td>
	            </tr>
            </table>
        </div>
    </div>
    
    
    
    
   
    <div title="（11）归档" style="padding:15px">
		<div class="easyui-panel" title="流程追踪"    style="padding:5px 0px;"data-options="collapsible:true,collapsed:false,border:false,fit:false">
            <table cellpadding="6" cellspacing="0" align="center"  width="100%" border="0">
	            <tr>
	                <td align="right" width="15%">流程节点：</td>
	                <td>提车信息归档</td>
	                <td align="right" width="15%">执行进度：</td>
	                <td><?php echo !empty($process_data[9]['plan']) ? $process_data[9]['plan']:''?></td>
	            </tr>
	            <tr>
	                <td align="right" width="15%">执行角色：</td>
	                <td><?php echo !empty($process_data[9]['role_name']) ? $process_data[9]['role_name']:''?></td>
	                <td align="right" width="15%">执行人：</td>
	                <td><?php echo !empty($process_data[9]['name']) ? $process_data[9]['name']:''?></td>
	            </tr>
	            <tr>
	                <td align="right" width="15%">执行时间：</td>
	                <td><?php echo  !empty($process_data[9]['time']) ? date('Y-m-d H:i',$process_data[9]['time']) :'';?></td>
	                <td align="right" width="15%">执行时限：</td>
	                <td><?php echo !empty($process_data[9]['is_timely']) ? $process_data[9]['is_timely']:''?></td>
	            </tr>
            </table>
        </div>
        
        <div class="easyui-panel" title="提交信息" style="padding:5px 0px;"
         data-options="collapsible:true,collapsed:false,border:false,fit:false">
         	<table cellpadding="6" cellspacing="0" align="center"  width="100%" border="0">
	            <tr>
	                <td align="right" width="15%">审批结果：</td>
	                <td><?php echo !empty($process_data[9]['res']) ? $process_data[9]['res'] :'';?></td>
	                <td align="right" width="15%">补充说明：</td>
	                <td><?php echo !empty($result['archive_remark']) ? $result['archive_remark'] :'';?></td>
	            </tr>

            </table>
        </div>
    </div>
</div>

<!-- 窗口 -->
<script>
	 $(function(){
	        $('#archive-tiche').datagrid({  
	            method: 'POST', 
	            url:"<?php echo yii::$app->urlManager->createUrl(['process/car/get-list']); ?>&id=<?php echo $result['id'] ?>",   
	            fit: true,
	            border: false,
	            toolbar: "#tiche-toolbar",
	            pagination: true,
	            loadMsg: '数据加载中...',
	            striped: true,
	            checkOnSelect: true,
	            rownumbers: true,
	            singleSelect: true,
	            showFooter: true,
				pageSize: 10,
	            frozenColumns: [[
	                {field: 'id',title: 'id',hidden: true}
	            ]],
	            columns: [[
	                 {field: 'car_no',title: '车牌号',width: 120,align: 'center',sortable: true},
	                 {field: 'car_type',title: '品牌型号',width: 120,align: 'center',sortable: true},
	                 {field: 'vehicle_license',title: '行驶证年审日期',width: 120,align: 'center',sortable: true},
	                 {field: 'road_transport',title: '道路运输证年审日期',width: 120,align: 'center',sortable: true},
	                 {field: 'insurance',title: '交强险有效期',width: 120,align: 'center',sortable: true},
	                 {field: 'business_risks',title: '商业险有效期',width: 120,align: 'center',sortable: true},
	                /* {field: 'monitoring',title: '监控数据更新日期',width: 120,align: 'center',sortable: true},*/
	                 {field: 'certificate',title: '随车工具',width: 120,align: 'center',sortable: true,
	                	 formatter: function(value){
	                         if(value == 1){
	                             return '已备齐';
	                         }else{
	                        	 return '未备齐';
	                         }
	                     }
					
	                  },
	                 {field: 'electricity',title: '电量充足',width: 120,align: 'center',sortable: true,
	                	  formatter: function(value){
	                          if(value == 1){
	                              return '充足';
	                          }else{
	                         	  return '不足';
	                          }
	                      }
	                  },
	                  {field: 'follow_car_card',title: '随车证件',width: 120,align: 'center',sortable: true},
	                  {field: 'follow_car_data',title: '随车资料',width: 120,align: 'center',sortable: true},     
	                 {field: 'username',title: '操作人',width: 120,align: 'center', sortable: true},   
	            ]],
	            onLoadSuccess: function (data){
	                $(this).datagrid('doCellTip',{
	                    position : 'bottom',
	                    maxWidth : '300px',
	                    onlyShowInterrupt : true,
	                    specialShowFields : [     
	                        {field : 'action',showField : 'action'}
	                    ],
	                    tipStyler : {            
	                        'backgroundColor' : '#E4F0FC',
	                        borderColor : '#87A9D0',
	                        boxShadow : '1px 1px 3px #292929'
	                    }
	                });
	            },
	        });


	        $('#archive-rent').datagrid({  
	            method: 'POST', 
	            url:"<?php echo yii::$app->urlManager->createUrl(['process/car/get-list']); ?>&id=<?php echo $result['id'] ?>&is_delivery=1&is_jiaoche=1",   
	            fit: true,
	            border: false,
	            toolbar: "#rent-toolbar",
	            pagination: true,
	            loadMsg: '数据加载中...',
	            striped: true,
	            checkOnSelect: true,
	            rownumbers: true,
	            singleSelect: true,
	            showFooter: true,
				pageSize: 10,
	            frozenColumns: [[
	                {field: 'id',title: 'id',hidden: true}
	            ]],
	            columns: [[
	                       {field: 'car_no',title: '车牌号',width: 120,align: 'center',sortable: true},
	                       {field: 'car_type',title: '品牌型号',width: 120,align: 'center',sortable: true},
	                       {field: 'first_phase',title: '首期（天）',width: 120,align: 'center',sortable: true},
	                       {field: 'first_phase_fee',title: '首期服务费（元）',width: 120,align: 'center',sortable: true},
	                       {field: 'money_fee',title: '服务费（元/月）',width: 120,align: 'center',sortable: true},
	                       {field: 'time_limit',title: '期限(月)',width: 120,align: 'center',sortable: true},
	                       //{field: 'margin',title: '保证金',width: 120,align: 'center',sortable: true},
	                       {field: 'start_time',title: '开始用车日期',width: 120,align: 'center',sortable: true},
	                       {field: 'end_time',title: '车辆归还日期',width: 120,align: 'center',sortable: true},        
	                  ]],
	            onLoadSuccess: function (data){
	                $(this).datagrid('doCellTip',{
	                    position : 'bottom',
	                    maxWidth : '300px',
	                    onlyShowInterrupt : true,
	                    specialShowFields : [     
	                        {field : 'action',showField : 'action'}
	                    ],
	                    tipStyler : {            
	                        'backgroundColor' : '#E4F0FC',
	                        borderColor : '#87A9D0',
	                        boxShadow : '1px 1px 3px #292929'
	                    }
	                });
	            },
	        });

	        
	        $('#archive-jiaoche').datagrid({  
	            method: 'POST', 
	            url:"<?php echo yii::$app->urlManager->createUrl(['process/car/get-list']); ?>&id=<?php echo $result['id'] ?>&is_jiaoche=1",   
	            fit: true,
	            border: false,
	            toolbar: "#jiaoche-toolbar",
	            pagination: true,
	            loadMsg: '数据加载中...',
	            striped: true,
	            checkOnSelect: true,
	            rownumbers: true,
	            singleSelect: true,
	            showFooter: true,
				pageSize: 10,
	            frozenColumns: [[
	                {field: 'ck',checkbox: true}, 
	                {field: 'id',title: 'id',hidden: true}
	            ]],
	            columns: [[
	                 {field: 'car_no',title: '车牌号',width: 120,align: 'center',sortable: true},
	                 {field: 'car_type',title: '品牌型号',width: 120,align: 'center',sortable: true},
	                 {field: 'is_delivery',title: '已交付',width: 120,align: 'center',sortable: true,
	                	 formatter: function(value){
	                         if(value == 1){
	                             return '已交付';
	                         }else{
	                        	 return '未交付';
	                         }
	                     }
	                },
	                {field: 'verify_car_photo',title: '交车单',width: 120,align: 'center', sortable: true,
	              	  formatter: function(value){
	                        if(value){
	                            return '<a href="'+value+'" target="_blank">查看</a>';
	                        }else{
	                       	  return '没有上传';
	                        }
	                    }
	               },
	                 {field: 'remark',title: '备注',width: 360,align: 'center',sortable: true},
	                 
	            ]],
	            onLoadSuccess: function (data){
	                $(this).datagrid('doCellTip',{
	                    position : 'bottom',
	                    maxWidth : '300px',
	                    onlyShowInterrupt : true,
	                    specialShowFields : [     
	                        {field : 'action',showField : 'action'}
	                    ],
	                    tipStyler : {            
	                        'backgroundColor' : '#E4F0FC',
	                        borderColor : '#87A9D0',
	                        boxShadow : '1px 1px 3px #292929'
	                    }
	                });
	            }
	        });
	});
        
 </script>