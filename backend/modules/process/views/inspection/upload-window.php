<form
    id="easyui-form-process-inspection-upload-window"
    target="iframe-process-inspection-uploadimage"
    action="<?= yii::$app->urlManager->createUrl(['process/inspection/upload-thumb']); ?>"
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

