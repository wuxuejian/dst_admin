<div style="padding:10px 40px 20px 40px">
    <form id="easyui-form-customer-company-add" class="easyui-form" method="post">
        <table cellpadding="8" cellspacing="0">
            <tr>
                <td align="right"><div style="width:70px;">客户号</div></td>
                <td>
                    <input class="easyui-textbox" disabled="true" style="width:160px;" value="系统生成" />
                </td>
                <td align="right"><div style="width:70px;">密码</div></td>
                <td>
                    <input
                        id="customer-company-add-password"
                        class="easyui-textbox"
                        name="password"
                        validType="match[/^\w{6,16}$/]"
                        invalidMessage="6到16位(数字/字母/_)！"
                        required="true"
                        type="password"
                    />
                </td>
                <td align="right"><div style="width:70px;">密码重复</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        type="password"
                        style="width:160px;"
                        required="true"
                        validType="equals['#customer-company-add-password']"
                        invalidMessage="两次密码不一致！"
                    />
                </td>
            </tr>
            <tr>
                <td align="right"><div style="width:70px;">公司名称</div></td>
                <td colspan="3">
                    <input
                        class="easyui-textbox"
                        style="width:440px;"
                        name="company_name"
                        required="true"
						validType="match[/^.{5,30}$/]"
                        invalidMessage="公司名称错误，长度不能少于5个字符！"
                    />
                </td>
                <td align="right"><div style="width:90px;">营业执照注册号</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="reg_number"
                        validType="length[50]"
                    />
                </td>
            </tr>
            <tr>
                <td align="right"><div style="width:70px;">公司地址</div></td>
                <td colspan="5">
                    <input
                        class="easyui-textbox"
                        style="width:660px;"
                        name="company_addr"
                        required="true"
                        missingMessage="请输入公司地址！"
                        validType="length[255]"
                    />
					<a href="javascript:search_baiDu_map();"><img src="jquery-easyui-1.4.3/themes/icons/map_magnify.png" title="查找地图" /></a>
                </td>
            </tr>
			<tr style="display:none;">
				<td align="right"><div style="width:70px;">经度</div></td>
				<td>
					<input class="easyui-numberbox" precision="6" name="company_lng" style="width:160px;" />
				</td>
				<td align="right"><div style="width:70px;">纬度</div></td>
				<td>
					<input class="easyui-numberbox" precision="6" name="company_lat" style="width:160px;" />
				</td>
				<td></td>
				<td></td>
			</tr>
            <tr>
                <td align="right"><div style="width:70px;">法人姓名</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="corporate_name"
                        required="true"
                        missingMessage="请填写法人姓名！"
                        validType="length[50]"
                    >
                </td>
                <td align="right"><div style="width:70px;">法人职务</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="corporate_post"
                        validType="length[30]"
                    >
                </td>
                <td  align="right"><div style="width:70px;">法人座机</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="corporate_telephone"
                        validType="match[/^(\d{1,6}-)?\d{2,20}$/]"
                        invalidMessage="坐机号码错误！"
                    >
                </td>
            </tr>
            <tr>
                <td  align="right"><div style="width:70px;">法人手机</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="corporate_mobile"
                    >
                </td>
                <td  align="right"><div style="width:70px;">法人邮箱</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="corporate_email"
                        validType="email"
                        invalidMessage="邮箱格式错误！"
                    >
                </td>
                <td  align="right"><div style="width:70px;">法人QQ</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="corporate_qq"
                        validType="match[/^\d{0,30}$/]"
                        invalidMessage="QQ号错误！"
                    >
                </td>
            </tr>
            <tr>
                <td  align="right"><div style="width:70px;">负责人姓名</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="director_name"
                        required="true"
                        missingMessage="请填写负责人姓名！"
                        validType="length[50]"
                    >
                </td>
                <td  align="right"><div style="width:70px;">负责人职务</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="director_post"
                        validType="length[30]"
                    >
                </td>
                <td  align="right"><div style="width:70px;">负责人座机</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="director_telephone"
                        validType="match[/^(\d{1,6}-)?\d{2,20}$/]"
                        invalidMessage="坐机号码错误！"
                    >
                </td>
            </tr>
            <tr>
                <td  align="right"><div style="width:70px;">负责人手机</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="director_mobile"
                    >
                </td>
                <td  align="right"><div style="width:70px;">负责人邮箱</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="director_email"
                        validType="email"
                        invalidMessage="邮箱格式错误！"
                    >
                </td>
                <td  align="right"><div style="width:70px;">负责人QQ</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="director_qq"
                        validType="match[/^\d{0,30}$/]"
                        invalidMessage="QQ号错误！"
                    >
                </td>
            </tr>
            <tr>
                <td  align="right"><div style="width:70px;">联系人姓名</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="contact_name"
                        required="true"
                        missingMessage="请填写联系人姓名！"
                        validType="length[50]"
                    >
                </td>
                <td  align="right"><div style="width:70px;">联系人职务</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="contact_post"
                        validType="length[30]"
                    >
                </td>
                <td  align="right"><div style="width:70px;">联系人座机</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="contact_telephone"
                        validType="match[/^(\d{1,6}-)?\d{2,20}$/]"
                        invalidMessage="坐机号码错误！"
                    >
                </td>
            </tr>
            <tr>
                <td  align="right"><div style="width:70px;">联系人手机</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="contact_mobile"
                    >
                </td>
                <td  align="right"><div style="width:70px;">联系人邮箱</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="contact_email"
                        validType="email"
                        invalidMessage="邮箱格式错误！"
                    >
                </td>
                <td  align="right"><div style="width:70px;">联系人QQ</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="contact_qq"
                        validType="match[/^\d{0,30}$/]"
                        invalidMessage="QQ号错误！"
                    >
                </td>
            </tr>
            <tr>
                <td  align="right"><div style="width:70px;">车管负责人姓名</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="keeper_name"
                        required="true"
                        missingMessage="请填写车管人姓名！"
                        validType="length[50]"
                    >
                </td>
                <td  align="right"><div style="width:70px;">车管负责人职务</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="keeper_post"
                        validType="length[30]"
                    >
                </td>
                <td  align="right"><div style="width:70px;">车管负责人座机</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="keeper_telephone"
                        validType="match[/^(\d{1,6}-)?\d{2,20}$/]"
                        invalidMessage="坐机号码错误！"
                    >
                </td>
            </tr>
            <tr>
                <td  align="right"><div style="width:70px;">车管负责人手机</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="keeper_mobile"
                        required="true"
                    >
                </td>
                <td  align="right"><div style="width:70px;">车管负责人邮箱</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="keeper_email"
                        validType="email"
                        invalidMessage="邮箱格式错误！"
                    >
                </td>
                <td  align="right"><div style="width:70px;">车管负责人QQ</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="keeper_qq"
                        validType="match[/^\d{0,30}$/]"
                        invalidMessage="QQ号错误！"
                    >
                </td>
            </tr>
            <tr>
                <td  align="right"><div style="width:70px;">开户名</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="account_name"
                    >
                </td>
                <td  align="right"><div style="width:70px;">开户银行</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="bank_account"
                    >
                </td>
                <td  align="right"><div style="width:70px;">开户账号</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="account_opening"
                    >
                </td>
            </tr>
			<tr>
                <td  align="right"><div style="width:70px;">客户类型一级分类</div></td>
                <td>
					<input id="easyui-form-customer-company-add-classify1_id" name="classify1_id" value="">
                </td>
				<td  align="right"><div style="width:70px;">客户类型二级分类</div></td>
                <td>
					<input id="easyui-form-customer-company-add-classify2_id" name="classify2_id" value="">
                </td>
				<td  align="right"><div style="width:70px;">客户等级</div></td>
                <td>
					<select
                        class="easyui-combobox"
                        style="width:160px;"
                        name="level"
						editable="false"
						data-options="panelHeight:'auto',required:true"
                    >
                    	<option value="0"></option>
						<option value="1">战略大客户</option>
						<option value="2">潜力大客户</option>
						<option value="3">普通客户</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td  align="right"><div style="width:70px;">公司简介</div></td>
                <td colspan="5">
                    <input 
                        class="easyui-textbox"
                        name="company_brief"
                        data-options="multiline:true"
                        style="height:60px;width:685px;"
                        validType="length[255]"
                    />
                </td>
            </tr>
        </table>
    </form>
</div>
<div id="baiDuMapWin" class="easyui-dialog" title="查找百度地图" style="width:800px;height:580px;" modal="true" closed="true"></div>
<script>	
	$("#easyui-form-customer-company-add-classify1_id").combobox(
		{
			url:'<?php echo yii::$app->urlManager->createUrl(['customer/classify/get-list-all']); ?>',
			valueField:'id',
			textField:'name',
			editable: false,
			required:true,
			onSelect: function(rec){
				$("#easyui-form-customer-company-add-classify2_id").combobox(
					{
						url:'<?php echo yii::$app->urlManager->createUrl(['customer/classify/get-list-all']); ?>'+'&pid='+rec.id,
						valueField:'id',
						textField:'name',
						editable: false,
						required:true,
						onSelect: function(rec){
						}
					}
				);
			}
		}
	);
	function search_baiDu_map() {
		$('#baiDuMapWin').dialog('open');
		var _url = '<?php echo yii::$app->urlManager->createUrl(['interfaces/interfaces/search-baidu-map']); ?>' + '&pageIn=company_add'; 
		$('#baiDuMapWin').dialog('refresh',_url);	
	}
</script>