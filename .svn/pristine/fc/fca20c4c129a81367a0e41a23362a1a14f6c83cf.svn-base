<form id="easyui-form-finance-rent-edit" class="easyui-form" method="post">
        <input type="hidden" name="id" value="<?php echo $id; ?>" />
        <table cellpadding="5">
        	<tr>
                <td><div style="width:85px;text-align:right;">公司编号</div></td>
                <td>
                    <!-- <input
                        class="easyui-textbox"
                        name="name"
                        style="width:150px;"  
                    /> -->
                    <input class="easyui-textbox" disabled="true" style="width:150px;" value="系统生成" />
                </td>
                <td><div style="width:85px;text-align:right;">设置密码</div></td>
                <td>
                    <input
                        id="finance-rent-add-password"
                        class="easyui-textbox"
                        name="password"
                        style="width:150px;"
                        type="password" 
                        required="true"
                        validType="match[/^\w{6,16}$/]"
                        invalidMessage="6到16位(数字/字母/_)！" 
                    />
                </td>  
                <td><div style="width:85px;text-align:right;">确认密码</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        
                        style="width:150px;"
                        type="password"
                        validType="equals['#finance-rent-add-password']"
                        invalidMessage="两次密码不一致！" 
                        required="true" 
                    />
                </td>  
            </tr>
            <tr>
            <!-- <td align="right"width="10%"></td>
            <td width="23%">
                <input class="easyui-textbox" style="width:210px;"  name="address" required="true" prompt="详细地址，具体到街道门牌号" />
            </td> -->
	            <td><div style="width:85px;text-align:right;">公司名称</div></td>
	                <td>
	                    <input
	                        class="easyui-textbox"
	                        name="company_name"
	                        style="width:250px;"
                            required="true"  
	                    />
	                </td>  
            </tr>
            <tr>
                <td><div style="width:85px;text-align:right;">负责人姓名</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        name="director_name"
                        style="width:150px;"
                        required="true"  
                    />
                </td>
                <td><div style="width:85px;text-align:right;">手机号</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        name="director_mobile"
                        style="width:150px;"
                        required="true"  
                    />
                </td>  
                <td><div style="width:85px;text-align:right;">职务</div></td>
                <td>
                    <input
                        class="easyui-textbox"
                        name="director_post"
                        style="width:150px;"
                        required="true"  
                    />
                </td>  
            </tr>
        </table> 
    </form>
<script>
$('#easyui-form-finance-rent-edit').form('load',<?= json_encode($finance_c); ?>);
</script>