<!-- 基本信息 begin -->
<form id="easyui_form_vip_notice_edit">
    <input type="hidden" name="vn_id" />
    <ul class="ulforform-resizeable">
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">标题</div>
            <div class="ulforform-resizeable-input">
                <input class="easyui-textbox" name="vn_title" style="width:100%;"  />
            </div>
        </li>
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">类型</div>
            <div class="ulforform-resizeable-input">
                <select class="easyui-combobox" name="vn_type" style="width:100%;" data-options="panelHeight:'auto',editable:false">
                    <?php foreach($config['vn_type'] as $val){ ?>
                        <option value="<?= $val['value'] ?>"><?= $val['text'] ?></option>
                    <?php } ?>
                </select>
            </div>
        </li>
        <li class="ulforform-resizeable-group">
            <div class="ulforform-resizeable-title">发布时间</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-datetimebox"
                    name="vn_public_time"
                    style="width:100%;"
                    required="true"
                />
            </div>
        </li>
        <li class="ulforform-resizeable-group-single">
            <div class="ulforform-resizeable-title">备注</div>
            <div class="ulforform-resizeable-input">
                <input
                    class="easyui-textbox"
                    name="vn_mark"
                    style="width:512px;height:40px;"
                    data-options="multiline:true"
                    validType="length[150]"
                />
            </div>
        </li>
        <li class="ulforform-resizeable-group-single">
            <div class="ulforform-resizeable-title">缩略图</div>
            <div class="ulforform-resizeable-input">
                <div
                    style="width:80px;height:80px;cursor:pointer;"
                    onclick="VipVipNoticeEdit.uploadThumb()">
                    <input type="hidden" name="vn_icon_path" />
                    <img class="faultImg" src="./images/add.jpg" width="80" height="80" />
                </div>
            </div>
        </li>
    </ul>
    <div style="padding-left:6px;">
        <script
            id="vipVipNoticeIndex_EditWindow_ueditor"
            type="text/plain"
            style="width:98%;margin: 0 auto;height:220px;"
        >编辑通知内容...</script>
    </div>
</form>
<iframe id="iframe_vip_vipnotice_edit_upload" name="iframe_vip_vipnotice_edit_upload" style="display:none;"></iframe>
<div id="easyuiwindow_vip_vipnotice_edit_upload"></div>
<script>
    //编辑器
    try{
        if(typeof(vipVipNoticeIndex_EditWindow_ueditor) != 'undefined'){
            vipVipNoticeIndex_EditWindow_ueditor.destroy();
        }
    }catch(e){}
    var vipVipNoticeIndex_EditWindow_ueditor = UE.getEditor('vipVipNoticeIndex_EditWindow_ueditor',{
        'textarea': 'vn_content',
        toolbars: [[ // 自定义工具栏
            'fullscreen', 'source', '|',
            'undo', 'redo', '|',
            'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc', '|',
            //'rowspacingtop', 'rowspacingbottom', 'lineheight', '|',
            //'simpleupload','insertimage','imagecenter','link','preview','|',
            //'customstyle', 'paragraph', 'fontfamily', 'fontsize','justifyleft','justifyright','justifycenter','justifyjustify'
        ]],
        autoClearinitialContent:true, // focus时自动清空初始化内容
    });
    var VipVipNoticeEdit = {
        params: {
            url: {
                uploadThumb: "<?= yii::$app->urlManager->createUrl(['vip/vip-notice/upload-thumb']); ?>&callback=VipVipNoticeEdit.uploadSuccess&target=iframe_vip_vipnotice_edit_upload"
            },
            oldData: <?= json_encode($vipNoticeInfo); ?>
        },
        init: function(){
            //初始化上传窗口
            $('#easyuiwindow_vip_vipnotice_edit_upload').dialog({
                title: '通知缩略图上传',   
                width: 500,   
                height: 160,   
                closed: false,   
                cache: true,   
                modal: true,
                closed: true,
                maximizable: false,
                minimizable: false,
                collapsible: false,
                draggable: false,
                buttons: [{
                    text:'上传',
                    iconCls:'icon-ok',
                    handler:function(){
                        var form = $('#easyui_form_vip_vipnotice_uploadwindow');
                        if(!form.form('validate')){
                            return false;
                        }
                        form.submit();
                    }
                },{
                    text:'取消',
                    iconCls:'icon-cancel',
                    handler:function(){
                        $('#easyuiwindow_vip_vipnotice_edit_upload').dialog('close');
                    }
                }],
                onClose: function(){
                    $(this).window('clear');
                }
            });
            //装载表单数据
            var dataForm = $('#easyui_form_vip_notice_edit');
            dataForm.form('load',this.params.oldData);
            if(this.params.oldData.vn_icon_path){
                var input = dataForm.find('input[name=vn_icon_path]')
                input.val(this.params.oldData.vn_icon_path);
                input.siblings('img').attr('src',this.params.oldData.vn_icon_path);
            }
            // ueditor准备好之后才能赋值！
            vipVipNoticeIndex_EditWindow_ueditor.addListener("ready", function () {
                this.setContent(VipVipNoticeEdit.params.oldData.vn_content);

            });
        },
        uploadThumb: function(){
            $('#easyuiwindow_vip_vipnotice_edit_upload')
                .dialog('open')
                .dialog('refresh',this.params.url.uploadThumb);
        },
        uploadSuccess: function(rData){
            if(rData.status){
                var form = $('#easyui_form_vip_notice_edit');
                var input = form.find('input[name=vn_icon_path]')
                input.val(rData.storePath);
                input.siblings('img').attr('src',rData.storePath);
                $('#easyuiwindow_vip_vipnotice_edit_upload').dialog('close');
            }else{
                $.messager.alert('上传错误',rData.info,'error');
            }
        }
    };
    VipVipNoticeEdit.init();
</script>