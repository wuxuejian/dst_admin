<form id="easyui-form-customer-personal-add" class="easyui-form">
    <div
        class="easyui-panel"
        title="身份证信息"
        style="width:100%;margin-bottom:5px;"
        iconCls="icon-save"
        closable="false"
        collapsible="false"
        minimizable="false"
        maximizable="false"
        border="false"
    >
        <table cellpadding="5" cellspacing="0">
            
            <tr>
                <td align="right"><div style="width:70px;">姓名</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="id_name"
                        required="true"
                        missingMessage="请输入姓名！"
                        validType="length[50]"
                    />
                </td>
                <td align="right"><div style="width:70px;">身份证号</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="id_number"
                        required="true"
                        missingMessage="请输入身份证号！"
                        validType="idcard"
                    />
                </td>
                <td align="right"><div style="width:70px;">性别</div></td>
                <td>
                    <select
                        class="easyui-combobox"
                        style="width:160px;"
                        name="id_sex"
                    >
                        <option value="1">男</option>   
                        <option value="0">女</option>   
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right"><div style="width:70px;">地址</div></td>
                <td colspan="5">
                    <input
                        class="easyui-textbox"
                        style="width:660px;"
                        name="id_address"
                        required="true"
                        missingMessage="请输入地址！"
                        validType="length[255]"
                    />
					<a href="javascript:search_baiDu_map();"><img src="jquery-easyui-1.4.3/themes/icons/map_magnify.png" title="查找地图" /></a>
                </td>
            </tr>
			<tr style="display:none;">
				<td align="right"><div style="width:70px;">经度</div></td>
				<td>
					<input class="easyui-numberbox" precision="6" name="personal_lng" style="width:160px;" />
				</td>
				<td align="right"><div style="width:70px;">纬度</div></td>
				<td>
					<input class="easyui-numberbox" precision="6" name="personal_lat" style="width:160px;" />
				</td>
				<td></td>
				<td></td>
			</tr>
        </table>
    </div>

    <div
        class="easyui-panel"
        title="驾驶证信息"
        style="width:100%;margin-bottom:5px;"
        iconCls="icon-save"
        closable="false"
        collapsible="false"
        minimizable="false"
        maximizable="false"
        border="false"
    >
        <table cellpadding="5" cellspacing="0">
            <tr>
                <td align="right"><div style="width:70px;">驾驶证号</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="driving_number"
                        required="true"
                        missingMessage="请输入驾驶证号！"
                        validType="length[50]"
                    />
                </td>
                <td align="right"><div style="width:70px;">领证日期</div></td>
                <td>
                    <input
                        class="easyui-datebox"
                        style="width:160px;"
                        name="driving_issue_date"
                        validType="date"
                    />
                </td>
                <td align="right"><div style="width:70px;"></div></td>
                <td></td>
            </tr>
            <tr>
                <td align="right"><div style="width:70px;">登记地址</div></td>
                <td colspan="5">
                    <input
                        class="easyui-textbox"
                        style="width:685px;"
                        name="driving_addr"
                        validType="length[255]"
                    />
                </td>
            </tr>
            <tr>
                <td align="right"><div style="width:70px;">准驾车型</div></td>
                <td>
                    <select
                        class="easyui-combobox"
                        style="width:160px;"
                        name="driving_class"
                    >
                        <?php foreach($config['driv_class'] as $val){ ?>
                        <option value="<?php echo $val['value']; ?>"><?php echo $val['text']; ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td align="right"><div style="width:70px;">起始日期</div></td>
                <td>
                    <input
                        class="easyui-datebox"
                        style="width:160px;"
                        name="driving_valid_from"
                        validType="date"
                    />
                </td>
                <td align="right"><div style="width:70px;">有效期限</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="driving_valid_for"
                        validType="int"
                        invalidMessage="有效期限错误！"
                    /> 年
                </td>
            </tr>
            <tr>
                <td align="right"><div style="width:70px;">发证机关</div></td>
                <td colspan="5">
                    <input
                        class="easyui-textbox"
                        style="width:685px;"
                        name="driving_issue_authority"
                        validType="length[255]"
                    />
                </td>
            </tr>
        </table>
    </div>
    <div
        class="easyui-panel"
        title="其它信息"
        style="width:100%;margin-bottom:5px;"
        iconCls="icon-save"
        closable="false"
        collapsible="false"
        minimizable="false"
        maximizable="false"
        border="false"
    >
        <table cellpadding="5" cellspacing="0">
            <tr>
                <td align="right"><div style="width:70px;">固定电话</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="telephone"
                        validType="match[/^(\d{1,6}-)?\d{2,20}$/]"
                        invalidMessage="固定电话格式错误！"
                    />
                </td>
                <td align="right"><div style="width:70px;">移动电话</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="mobile"
                        validType="match[/^1[3|4|5|7|8|9]\d{9}$/]"
                        invalidMessage="移动电话格式错误！"
                        required="true"
                        missingMessage="请输入移动电话！"
                        />
                </td>
                <td align="right"><div style="width:70px;">QQ</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="qq"
                        validType="match[/^\d{0,30}$/]"
                        invalidMessage="QQ号错误！"
                    />
                </td>
            </tr>
            <tr>
                <td align="right"><div style="width:70px;">邮箱</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        style="width:160px;"
                        name="email"
                        validType="email"
                        invalidMessage="邮箱格式错误！"
                    />
                </td>
                <td align="right"><div style="width:70px;"></div></td>
                <td></td>
                <td align="right"><div style="width:70px;"></div></td>
                <td></td>
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
        </table>
    </div>
</form>
<div id="baiDuMapWin" class="easyui-dialog" title="查找百度地图" style="width:800px;height:580px;" modal="true" closed="true"></div>
<script>
	function search_baiDu_map() {
		$('#baiDuMapWin').dialog('open');
		var _url = '<?php echo yii::$app->urlManager->createUrl(['interfaces/interfaces/search-baidu-map']); ?>' + '&pageIn=personal_add'; 
		$('#baiDuMapWin').dialog('refresh',_url);	
	}
</script>