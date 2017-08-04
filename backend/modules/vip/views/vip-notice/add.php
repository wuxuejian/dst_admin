<!-- 基本信息 begin -->
<form id="easyui_form_vip_notice_add">
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
                    value="<?= date('Y-m-d H:i:s'); ?>"
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
                    onclick="VipVipNoticeAdd.uploadThumb()">
                    <input type="hidden" name="vn_icon_path" />
                    <img class="faultImg" src="./images/add.jpg" width="80" height="80" />
                </div>
            </div>
        </li>
    </ul>
    <div style="padding-left:6px;">
        <script
            id="vipVipNoticeIndex_AddWindow_ueditor"
            type="text/plain"
            style="width:98%;margin: 0 auto;height:220px;"
        >编辑通知内容...</script>
    </div>
</form>
<iframe id="iframe_vip_vipnotice_add_upload" name="iframe_vip_vipnotice_add_upload" style="display:none;"></iframe>
<div id="easyuiwindow_vip_vipnotice_add_upload"></div>
<script>
    //编辑器
    try{
        if(typeof(vipVipNoticeIndex_AddWindow_ueditor) != 'undefined'){
            vipVipNoticeIndex_AddWindow_ueditor.destroy();
        }
    }catch(e){}
    var vipVipNoticeIndex_AddWindow_ueditor = UE.getEditor('vipVipNoticeIndex_AddWindow_ueditor',{
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
    var VipVipNoticeAdd = {
        params: {
            url: {
                uploadThumb: "<?= yii::$app->urlManager->createUrl(['vip/vip-notice/upload-thumb']); ?>&callback=VipVipNoticeAdd.uploadSuccess&target=iframe_vip_vipnotice_add_upload"
            }
        },
        init: function(){
            //初始化上传窗口
            $('#easyuiwindow_vip_vipnotice_add_upload').dialog({
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
                        $('#easyuiwindow_vip_vipnotice_add_upload').dialog('close');
                    }
                }],
                onClose: function(){
                    $(this).window('clear');
                }
            });
        },
        uploadThumb: function(){
            $('#easyuiwindow_vip_vipnotice_add_upload')
                .dialog('open')
                .dialog('refresh',this.params.url.uploadThumb);
        },
        uploadSuccess: function(rData){
            if(rData.status){
                var form = $('#easyui_form_vip_notice_add');
                var input = form.find('input[name=vn_icon_path]')
                input.val(rData.storePath);
                input.siblings('img').attr('src',rData.storePath);
                $('#easyuiwindow_vip_vipnotice_add_upload').dialog('close');
            }else{
                $.messager.alert('上传错误',rData.info,'error');
            }
        }
    };
    VipVipNoticeAdd.init();
</script>