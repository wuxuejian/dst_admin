<form id="easyui-form-vip-suggestion-reply" class="easyui-form" style="padding:10px 0;">
    <input type="hidden" name="vs_id" value="<?= $suggestionInfo['vs_id']; ?>" />
    <ul class="ulforform-resizeable" style="line-height:24px;">
        <li class="ulforform-resizeable-group-single">
            <div class="ulforform-resizeable-title">主题</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    value="<?= $suggestionInfo['vs_title']; ?>"
                    style="width: 650px;"
                    validType="length[100]"
                    disabled="true"
                />
            </div>
        </li>
        <li class="ulforform-resizeable-group-single">
            <div class="ulforform-resizeable-title">内容</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    style="width:650px;height:50px;"
                    data-options="multiline:true"
                    validType="length[150]"
                    value="<?= $suggestionInfo['vs_content']; ?>"
                    validType="length[300]"
                    disabled="true"
                />
            </div>
        </li>
        <li class="ulforform-resizeable-group-single">
            <div class="ulforform-resizeable-title">回复内容</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    name="vs_respond_txt"
                    style="width:650px;height:80px;"
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
                    style="width:650px;height:80px;"
                    data-options="multiline:true"
                    validType="length[150]"
                    value="<?= $suggestionInfo['vs_mark']; ?>"
                    validType="length[300]"
                />
            </div>
        </li>
    </ul>
</form>