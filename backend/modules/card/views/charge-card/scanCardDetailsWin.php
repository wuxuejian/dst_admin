<div class="easyui-tabs" data-options="fit:true,border:false,tabWidth:130" >
    <?php foreach($buttons as $val){ ?>
    <div
        title="<?= $val['text']; ?>"
        href="<?= yii::$app->urlManager->createUrl([$val['target_mca_code'],'cc_id'=>$cc_id]);?>"
        fit="true"
        closable="false"
        iconCls="<?= $val['icon']; ?>"
    ></div>
    <?php } ?>
</div>