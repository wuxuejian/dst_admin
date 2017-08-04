<div style="padding:10px;">
	<form id="editProfileWin_form">
		<input type="hidden" name="id"  />
		<table cellspacing="0" cellpadding="5" align="center" border="0" width="100%">
			<tr>
				<td align="right">
					<label>姓名：</label>
				</td>
				<td>
					<input class="easyui-textbox" name="name" value="" placeholder="" editable="false" disabled="true"  />
				</td>
				<td align="right"></td>
				<td></td>
			</tr>
			<tr>
				<td align="right" width="15%">
					<label>账号：</label>
				</td>
				<td width="35%">
					<input class="easyui-textbox" name="username" value="" placeholder="" editable="false" disabled="true" />
				</td>
				<td align="right" width="15%"></td>
				<td></td>
			</tr>
			<tr>
				<td colspan="4">
					<span style="color:red;font-size:12px;">&nbsp;&nbsp;*密码必须同时包含英文大写、小写字母与数字，长度不少于8位。</span>
				</td>
			</tr>
			<tr>
				<td align="right">
					<label>原密码：</label>
				</td>
				<td>
					<input class="easyui-textbox" type="password" name="oldPassword" value="" placeholder="" />
				</td>
				<td align="right"></td>
				<td></td>
			</tr>
			<tr>
				<td align="right" width="15%">
					<label>新密码：</label>
				</td>
				<td>
					<input class="easyui-textbox" type="password" name="newPassword" value="" placeholder="" id="editProfileWin_form_newPassword" />
				</td>
				<td align="right" width="15%">
					<label>确认密码：</label>
				</td>
				<td>
					<input class="easyui-textbox" type="password" name="newPasswordRepeat" value="" placeholder="" validType="equals['#editProfileWin_form_newPassword']" invalidMessage="两次密码不一致！" />
				</td>
			</tr>
			<tr>
				<td align="right">
					<label>性别：</label>
				</td>
				<td>
					<input type="radio" name="sex" value="1" placeholder="" checked="checked"  />男&nbsp;
					<input type="radio" name="sex" value="0" placeholder=""  />女&nbsp;
				</td>
				<td align="right"></td>
				<td></td>
			</tr>
			<tr>
				<td align="right">
					<label>手机：</label>
				</td>
				<td>
					<input class="easyui-numberbox" name="telephone" value="" placeholder=""  
						data-options="
							validType:'match[/^1[34578][0-9]{9}$/]',
							invalidMessage:'手机号格式错误！'
					" />
				</td>
				<td align="right">
					<label>邮箱：</label>
				</td>
				<td>
					<input class="easyui-textbox" name="email" value="" placeholder="" 
						data-options="
							validType:'email',
							invalidMessage:'邮箱格式错误！'
						" />
				</td>
			</tr>
			<tr>
				<td align="right">
					<label>QQ：</label>
				</td>
				<td>
					<input class="easyui-numberbox" name="qq" value="" placeholder=""  />
				</td>
				<td align="right"></td>
				<td></td>
			</tr>
		</table>
	</form>	
</div>
<script>
	$(function(){
		var adminInfo = <?php echo isset($adminInfo) ? json_encode($adminInfo) : 0; ?>;
		if(adminInfo){
			$('#editProfileWin_form').form('load',adminInfo);
			$('input[name="password"]',$('#editProfileWin_form')).val('');
		}
	});
</script>
