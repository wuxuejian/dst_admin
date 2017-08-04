<form id="easyui-form-vip-suggestion-edit" class="easyui-form" style="padding:10px 0;">
    <input type="hidden" name="vs_id" value="<?= $suggestionInfo['vs_id']; ?>" />
    <ul class="ulforform-resizeable" style="line-height:24px;">
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">编号</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    disabled="true"
                    value="<?= $suggestionInfo['vs_code']; ?>"
                    style="width: 150px;"
                />
            </div>
        </li>
        <li class="ulforform-resizeable-group-single">
            <div class="ulforform-resizeable-title">主题</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    name="vs_title"
                    value="<?= $suggestionInfo['vs_title']; ?>"
                    style="width: 840px;"
                    validType="length[100]"
                />
            </div>
        </li>
        <li class="ulforform-resizeable-group-single">
            <div class="ulforform-resizeable-title">内容</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    name="vs_content"
                    style="width:840px;height:50px;"
                    data-options="multiline:true"
                    validType="length[150]"
                    value="<?= $suggestionInfo['vs_content']; ?>"
                    validType="length[300]"
                />
            </div>
        </li>
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">建议时间</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    disabled="true"
                    value="<?= $suggestionInfo['vs_time']; ?>"
                    style="width: 150px;"
                />
            </div>
        </li>
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">会员编号</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    disabled="true"
                    value="<?= $suggestionInfo['vip_code']; ?>"
                    style="width: 150px;"
                />
            </div>
        </li>
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">会员电话</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    disabled="true"
                    value="<?= $suggestionInfo['vip_mobile']; ?>"
                    style="width: 150px;"
                />
            </div>
        </li>
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">回复管理员</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    disabled="true"
                    value="<?= $suggestionInfo['admin_username']; ?>"
                    style="width: 150px;"
                />
            </div>
        </li>
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">回复时间</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    disabled="true"
                    value="<?= $suggestionInfo['vs_respond_time']; ?>"
                    style="width: 150px;"
                />
            </div>
        </li>
        <li class="ulforform-resizeable-group-single">
            <div class="ulforform-resizeable-title">回复内容</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    name="vs_respond_txt"
                    style="width:840px;height:50px;"
                    data-options="multiline:true"
                    validType="length[150]"
                    value="<?= $suggestionInfo['vs_respond_txt']; ?>"
                    required="true"
                    validType="length[300]"
                />
            </div>
        </li>
        <li class="ulforform-resizeable-group-single">
            <div class="ulforform-resizeable-title">备注</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    name="vs_mark"
                    style="width:840px;height:50px;"
                    data-options="multiline:true"
                    validType="length[150]"
                    value="<?= $suggestionInfo['vs_mark']; ?>"
                    validType="length[200]"
                />
            </div>
        </li>
    </ul>
</form>