<div class="easyui-panel" title=""
     style="width:98%;height:100%;"
     footer="#vipVipGuideIndex_footer"
     data-options="border:false"
>
    <form id="vipVipGuideIndex_form">
        <div style="padding-left:15px;padding-top:5px;">
            <script
                id="vipVipGuideIndex_ueditor"
                type="text/plain"
                style="width:99%;height:370px;"
            >编辑内容...
            </script>
        </div>
    </form>
    <!--
    <div id="vipVipGuideIndex_footer" style="padding:5px;text-align: center;">
        <a href="javascript:void(0)" onclick="vipVipGuideIndex.save()" class="easyui-linkbutton" data-options="iconCls:'icon-ok'">确定保存</a>
    </div>
    -->
    <?php if(isset($buttons) && !empty($buttons)){ ?>
        <div id="vipVipGuideIndex_footer" style="padding:5px;text-align: center;">
            <?php foreach($buttons as $val){ ?>
                <a href="javascript:void(0)" onclick="<?= $val['on_click']; ?>" class="easyui-linkbutton" data-options="iconCls:'<?= $val['icon']; ?>'"><?= $val['text']; ?></a>
            <?php } ?>
        </div>
    <?php } ?>

</div>
<script>

    try{
        if(typeof(vipVipGuideIndex_ueditor) != 'undefined'){
            vipVipGuideIndex_ueditor.destroy();
        }
    }catch(e){}
    var vipVipGuideIndex_ueditor = UE.getEditor('vipVipGuideIndex_ueditor',{
        'textarea': 'content',
        toolbars: [[ // 自定义工具栏
            'fullscreen', 'source', '|',
            'undo', 'redo', '|',
            'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc', '|',
            'rowspacingtop', 'rowspacingbottom', 'lineheight', '|',
            'simpleupload','imagecenter','link','preview','|',
            'customstyle', 'paragraph', 'fontfamily', 'fontsize','justifyleft','justifyright','justifycenter','justifyjustify'
        ]],
        autoClearinitialContent:true // focus时自动清空初始化内容
    });

    var vipVipGuideIndex = {
        params:{
            url:{
                save:"<?php echo yii::$app->urlManager->createUrl(['vip/vip-guide/save']); ?>"
            }
        },
        init:function(){
            // ueditor准备好之后才能赋值！
            vipVipGuideIndex_ueditor.addListener("ready", function () {
                this.setContent(<?= json_encode($vipGuideInfo['content']); ?>);
            });
        },
        save:function(){
            var _form = $('#vipVipGuideIndex_form');
            if(!_form.form('validate')){
                $.messager.show({
                    title: '验证不通过',
                    msg: '请检查表单是否填写完整或填写错误！'
                });
                return false;
            }
            var data = _form.serialize();
            $.ajax({
                type: 'post',
                url: vipVipGuideIndex.params.url.save,
                data: data,
                dataType: 'json',
                success: function(data){
                    if(data.status){
                        $.messager.show({
                            title: '修改成功',
                            msg: data.info
                        });
                    }else{
                        $.messager.alert('错误',data.info,'error');
                    }
                }
            });

/*
            $.messager.confirm('确认保存','您确定要保存当前修改吗？',function(r){
                if(r){
                    $.ajax({
                        type: 'post',
                        url: vipVipGuideIndex.params.url.save,
                        data: data,
                        dataType: 'json',
                        success: function(data){
                            if(data.status){
                                $.messager.show({
                                    title: '修改成功',
                                    msg: data.info
                                });
                            }else{
                                $.messager.alert('错误',data.info,'error');
                            }
                        }
                    });
                }
            });
*/
        }
    }
    vipVipGuideIndex.init();
</script>