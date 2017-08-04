jQuery.ajaxFileUpload = new Object();
jQuery.ajaxFileUpload.progressTimer = 0;
jQuery.ajaxFileUpload.userParam = {};
//create iframe and form
jQuery.ajaxFileUpload.createUploadIframeAndForm = function(){
	if(!document.getElementById('jquery-ajaxfileupload-iframe')){
		var str = '<iframe id="jquery-ajaxfileupload-iframe" name="jquery-ajaxfileupload-iframe" style="display:none"></iframe>';
		$('body').append(str);
	}
	if(!document.getElementById('jquery-ajaxfileupload-form')){
		var str = '<form id="jquery-ajaxfileupload-form" method="post" target="jquery-ajaxfileupload-iframe" enctype="multipart/form-data" action="'+jQuery.ajaxFileUpload.userParam.url+'" style="display:none"></form>';
		$('body').append(str);
	}
	
};
//progress monitor
jQuery.ajaxFileUpload.uploadPorogress = function(){
	if(jQuery.ajaxFileUpload.progressTimer){
		clearInterval(jQuery.ajaxFileUpload.progressTimer);
	}
	jQuery.ajaxFileUpload.progressTimer = setInterval(function(){
		var iframeContent = $(document.getElementById('jquery-ajaxfileupload-iframe').contentWindow.document.body).html();
		if(iframeContent){
			clearInterval(jQuery.ajaxFileUpload.progressTimer);
			//回调用户自定义方法
			jQuery.ajaxFileUpload.userParam.success(jQuery.ajaxFileUpload.dataTransformation(iframeContent));
		}
	},1000);
};
//data Transformation
jQuery.ajaxFileUpload.dataTransformation = function(data){
	if(jQuery.ajaxFileUpload.userParam.dataType){
		switch(jQuery.ajaxFileUpload.userParam.dataType){
			case 'json':
				return JSON.parse(data);
			default:
				return data;
		}
	}else{
		return data;
	}
	
}
jQuery.ajaxFileUpload.upload = function(userParam){
	if(typeof $ == 'undefined'){
		alert('jquery must be required!');
		return;
	}
	jQuery.ajaxFileUpload.userParam = userParam;
	jQuery.ajaxFileUpload.createUploadIframeAndForm();
	$('#jquery-ajaxfileupload-form').html(userParam.fileElementObject);
	$('#jquery-ajaxfileupload-form').submit();
	jQuery.ajaxFileUpload.uploadPorogress();
};