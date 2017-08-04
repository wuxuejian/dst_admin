<div id="car-attachment-add-fileupload-container" style="height:98%;margin:5px; auto;"></div>
<script>
	//alert(JqueryAjaxFileUpload);
    JqueryAjaxFileUpload.init({
        //上传控件的容器
        container: $('#car-attachment-add-fileupload-container'),
        //后台数据处理地址
        url: "<?php echo yii::$app->urlManager->createUrl(['car/attachment/add',['carId'=>$carId]]); ?>",
        //需要添加的额外列，这些列会随图片文件一同被提交
        addColumn: [
            {'title': '附件名称','content': '<input type="text" name="name" /><input type="hidden" name="carId" value="<?php echo $carId; ?>" />'}
        ],
        //文件输入框的name属性的值
        fileInputName: 'attachment',
        //每上传完一个文件都会回调该方法
        success: function(data){
            console.log(data);
        },
        //所有图片上传完成后回调方法
        complete: function(){
            //alert('所有文件上传完成');
        }
    }); 
</script>