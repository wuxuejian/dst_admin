<form
    id="easyui-form-process-repair-edit-upload-window"
    target="iframe-process-repair-edit-uploadimage"
    action="<?= yii::$app->urlManager->createUrl(['process/repair/upload-thumb']); ?>&isEdit=1"
    method="post"
    enctype="multipart/form-data"
>
    <div style="padding-top:30px;width:406px;margin:0 auto;">
        <input type="hidden" name="columnName" value="<?= $columnName; ?>">
        <input
            class="easyui-filebox"
            name="<?= $columnName; ?>"
            style="width:400px;"
            data-options="buttonIcon:'icon-folder-image',buttonText:'选择图片'"
            required="true"
        />
    </div>
</form>

