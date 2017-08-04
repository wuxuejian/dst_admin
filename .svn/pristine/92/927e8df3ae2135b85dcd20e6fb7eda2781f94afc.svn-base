<form id="easyui-form-system-code-update-log-edit" method="post" style="padding:5px;">
	<input type="hidden" name="id" value="<?=$log['id']?>" />
        <ul class="ulforform-resizeable">
            <li class="ulforform-resizeable-group">
                <div class="ulforform-resizeable-title">升级产品</div>
                <div class="ulforform-resizeable-input">
                    <select class="easyui-combobox" name="product" style="width:153px;" required="true">  
                        <option value=""></option>
                        <option value="1"<?=$log['product']==1?' selected':''?>>地上铁APP</option>
                        <option value="2"<?=$log['product']==2?' selected':''?>>地上铁系统</option>
                    </select>
                </div>
            </li>
            <li class="ulforform-resizeable-group">
                <div class="ulforform-resizeable-title">升级类型</div>
                <div class="ulforform-resizeable-input">
                    <select class="easyui-combobox" name="update_type" style="width:153px;" required="true">  
                        <option value=""></option>
                        <option value="1"<?=$log['update_type']==1?' selected':''?>>优化</option>
                        <option value="2"<?=$log['update_type']==2?' selected':''?>>修复</option>
                        <option value="3"<?=$log['update_type']==3?' selected':''?>>新增</option>
                        <option value="4"<?=$log['update_type']==4?' selected':''?>>删除</option>
                    </select>
                </div>
            </li>
            <li class="ulforform-resizeable-group">
                <div class="ulforform-resizeable-title">功能模块</div>
                <div class="ulforform-resizeable-input">
                    <input class="easyui-textbox" name="module" value="<?=$log['module']?>"></input>
                </div>
            </li>
            <li class="ulforform-resizeable-group">
                <div class="ulforform-resizeable-title">版本号</div>
                <div class="ulforform-resizeable-input">
                    <input class="easyui-textbox" name="version_number" value="<?=$log['version_number']?>"></input>
                </div>
            </li>
            <li class="ulforform-resizeable-group">
                <div class="ulforform-resizeable-title">升级日期</div>
                <div class="ulforform-resizeable-input">
                    <input class="easyui-datebox" type="text" name="update_date" value="<?=$log['update_date']?>" style="width:93px;" required="true"/>
                </div>
            </li>
            <li class="ulforform-resizeable-group-single">
                <div class="ulforform-resizeable-title">升级内容简述</div>
                <div class="ulforform-resizeable-input">
                	<input
	                    class="easyui-textbox"
	                    name="update_title"
	                    style="width:400px;"
                    	required="true"
	                    validType="length[100]"
	                    value="<?=$log['update_title']?>"
	                />
                </div>
            </li>
            <li class="ulforform-resizeable-group-single">
                <div class="ulforform-resizeable-title">升级详细内容</div>
                <div class="ulforform-resizeable-input">
                	<input
                        class="easyui-textbox"
                        name='note'
                        data-options="multiline:true"
                        style="height:60px;width:400px;"
                        validType="length[500]"
                        prompt=""
                        value="<?=$log['note']?>"
                    />
                </div>
            </li>
        </ul>
</form>