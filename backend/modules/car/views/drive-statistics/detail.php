<div
    class="easyui-tabs"
    data-options="{fit:true,border:false}"
>
<?php
foreach($buttons as $val){
?>
    <div
        title="<?php echo $val['text']; ?>"
        href="<?php echo yii::$app->urlManager->createUrl([
            $val['target_mca_code'],
            'car_vin'=>$carVin,
            'date'=>$date
        ]); ?>"
    ></div>
<?php
}
?>
</div>