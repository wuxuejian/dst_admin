<div style="padding:5px 3px;margin:0 auto;">
	<!-- 基本信息 begin -->
    <div class="easyui-panel" title="(1)基本信息" style="padding:10px;margin:0 auto;" data-options="collapsible:true,collapsed:false,border:false">
        <form id="vipShareIndex_approveWindow_baseInfoForm" method="post">
        	<table cellpadding="5" cellspacing="0" style="width:100%;" border=0>
        	    <tr>
        			<td width="10%">电桩编号：</td>
        			<td width="23%">
        			    <input class="easyui-textbox" name="code_from_compony" style="width:153px;" required="true" missingMessage="请输入公司编号！" />
        			</td>
        			<td width="10%">出厂编号：</td>
        			<td>
        			    <input class="easyui-textbox" name="code_from_factory" style="width:153px;" required="true" missingMessage="请输入厂家编号！" />
        			</td>
        			<td width="10%">电桩型号：</td>
        			<td width="23%">
        			    <select class="easyui-combobox" name="model" style="width:153px;" data-options="panelHeight:'auto', editable:false">
							<?php foreach($config['model'] as $val){ ?>
        			        <option value="<?php echo $val['value'] ?>"><?php echo $val['text']; ?></option>
        			        <?php } ?>
        			    </select>
        			</td>					
        		</tr>
        	    <tr>
        			<td>电桩类型：</td>
        			<td>
        			    <select class="easyui-combobox" name="charge_type" style="width:153px;" data-options="panelHeight:'auto'"  editable=false >
							<?php foreach($config['charge_type'] as $val){ ?>
        			        <option value="<?php echo $val['value'] ?>"><?php echo $val['text']; ?></option>
        			        <?php } ?>
        			    </select>
        			</td>
        			<td>连接方式：</td>
        			<td>
        			    <select class="easyui-combobox" name="connection_type" style="width:153px;" data-options="panelHeight:'auto'" editable=false required="true">
							<?php foreach($config['connection_type'] as $val){ ?>
        			        <option value="<?php echo $val['value'] ?>"><?php echo $val['text']; ?></option>
        			        <?php } ?>
        			    </select>        			
					</td>
        			<td>规格参数：</td>
        			<td>
        			    <input class="easyui-textbox" name="specification" style="width:153px;" />
					</td>
				</tr>
				<tr>
        			<td>线长：</td>
        			<td>
        			    <input class="easyui-numberbox" name="wire_length" style="width:153px;" precision="2" min="0" /> 米
        			</td>
        			<td>电枪数量：</td>
        			<td>
        			    <input class="easyui-numberbox" name="charge_gun_nums" style="width:153px;"  required="true" value="1" min="1" /> 个
        			</td>
        			<td>生产厂家：</td>
        			<td>
        			    <select class="easyui-combobox" name="manufacturer" style="width:153px;" data-options="panelHeight:'auto',editable:false">
							<?php foreach($config['manufacturer'] as $val){ ?>
        			        <option value="<?php echo $val['value'] ?>"><?php echo $val['text']; ?></option>
        			        <?php } ?>
        			    </select>
        			</td>
        		</tr> 
				<tr>
        			<td>购置日期：</td>
        			<td>
        			    <input class="easyui-datebox" name="purchase_date" style="width:153px;" validType="date" value="parseDateObj(new Date())" />
        			</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			</table>
        </form>
    </div>  
    <!-- 基本信息 end -->
	
	<div style="height:5px;clear:both;"></div>
	
	<!-- 详细信息 begin -->
    <div class="easyui-panel" title="(2)更多信息" style="padding:10px;margin:0 auto;" data-options="collapsible:true,collapsed:false,border:false">
        <form id="vipShareIndex_approveWindow_moreInfoForm" method="post">
        	<table cellpadding="5" cellspacing="0" style="width:100%;" border=0>
        	    <tr>
        			<td width="10%">使用单位：</td>
        			<td width="23%">
        			    <input class="easyui-textbox" name="user" style="width:153px;" />
        			</td>
					<td width="10%">单位联系人：</td>
        			<td>
        			    <input class="easyui-textbox" name="user_linkman" style="width:153px;" />
        			</td>
        			<td width="10%">联系人手机：</td>
        			<td width="23%">
        			    <input class="easyui-textbox" name="user_linkman_mobile" style="width:153px;" />
        			</td>
        		</tr>
				<tr>
        			<td>联系人固话：</td>
        			<td>
        			    <input class="easyui-textbox" name="user_linkman_tel" style="width:153px;" />
        			</td>
					<td>电桩状态：</td>
        			<td>
        			    <select class="easyui-combobox" name="status" style="width:153px;" data-options="panelHeight:'auto', editable:false">
							<?php foreach($config['status'] as $val){ ?>
        			        <option value="<?php echo $val['value'] ?>"><?php echo $val['text']; ?></option>
        			        <?php } ?>
        			    </select>
        			</td>
        			<td>安装日期：</td>
        			<td>
        			    <input class="easyui-datebox" name="install_date" style="width:153px;" validType="date" value="parseDateObj(new Date())" />
        			</td>
        		</tr>
				<tr>
					<td>经度：</td>
					<td>
						<input class="easyui-numberbox" precision="6" name="lng" style="width:153px;" required="true" />
						<a href="javascript:search_baiDu_map();"><img src="jquery-easyui-1.4.3/themes/icons/map_magnify.png" title="查找地图" /></a>
					</td>
					<td>纬度：</td>
					<td>
						<input class="easyui-numberbox" precision="6" name="lat" style="width:153px;" required="true" />
					</td>
        			<td>安装方式：</td>
        			<td>
        			    <select class="easyui-combobox" name="install_type" style="width:153px;" data-options="panelHeight:'auto',editable:false">
							<?php foreach($config['install_type'] as $val){ ?>
        			        <option value="<?php echo $val['value'] ?>"><?php echo $val['text']; ?></option>
        			        <?php } ?>
        			    </select>
        			</td>
        		</tr>
				<tr>
        			<td>安装地点：</td>
        			<td colspan="5">
        			    <input class="easyui-textbox" name="install_site" style="width:465px;" required="true" />
        			</td>
				</tr>
				<tr>
					<td>备注：</td>
					<td colspan="5">
						<input class="easyui-textbox" name="mark" style="width:465px;height:40px;" 
						data-options="multiline:true"
                        validType="length[150]" />
					</td>
				</tr>
				<tr hidden>
					<td>电桩ID：</td>
					<td colspan=5>
						<input class="easyui-textbox" name="id" style="width:153px;"  editable="false" />
					</td>
				</tr>
          	</table>
        </form>
    </div> 
	<!-- 详细信息 end -->	
</div>

<!-- 填写审核未通过原因弹窗 begin-->
<div id="vipShareIndex_approveWindow_approveMarkWindow" style="padding:10px;text-align:center"></div>
<!-- 填写审核未通过原因弹窗 end-->

<div id="baiDuMapWin" class="easyui-dialog" title="查找百度地图" style="width:800px;height:580px;" modal="true" closed="true"></div>
<script>
	// 加载数据
	var vipShareInfo = <?php echo json_encode($vipShareInfo); ?>; 
	$('#vipShareIndex_approveWindow_baseInfoForm').form('load',vipShareInfo);
	$('#vipShareIndex_approveWindow_moreInfoForm').form('load',vipShareInfo);
	
	// 若上次审核是不通过的，提示上次不通过的原因以引起审核人重视检查。
	var approveMark = $.trim(vipShareInfo.approve_mark);	
	if(approveMark){
		$.messager.show({
			title: '上次审核不通过',
			msg: '<span style="color:red;">' + approveMark + '</span><br />',
			timeout:5000,
			showType:'fade',
			style:{
				right:'',
				top: document.body.scrollTop+document.documentElement.scrollTop + 5,
				bottom:''
			}
		});
	}

	function search_baiDu_map() {
		$('#baiDuMapWin').dialog('open');
		var _url = '<?php echo yii::$app->urlManager->createUrl(['interfaces/interfaces/search-baidu-map']); ?>' + '&pageIn=vipShareApproveWindow'; 
		$('#baiDuMapWin').dialog('refresh',_url);	
	}
</script>