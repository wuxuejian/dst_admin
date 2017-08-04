$(function(){
	$('.disableCss').removeAttr('href');//去掉a标签中的href属性
	$('.disableCss').removeAttr('onclick');//去掉a标签中的onclick事件
});
//输入下拉
$('.drop-down-i').keyup(function(){
	var i_width = $(this).width()-8;
	$(this).siblings('.drop-down-u').width(i_width);
	$(this).siblings('.drop-down-u').show();
});
$('.drop-down-u').mouseleave(function(){
	$(this).hide()
})

$('.must').keyup(function(){
	if($(this).val() != ''){
		$(this).siblings('.warn').detach();
	}
})
$('.must').change(function(){
	if($(this).val() != ''){
		$(this).siblings('.warn').detach();
	}
})
$('.must').mouseover(function(){
	if($(this).val() != ''){
		$(this).siblings('.warn').detach();
	}
})


function cancel(){
	window.opener=null;
	window.open('','_self');
	window.close();
}

function ahref_add_id(p_id,p_step,p_have){
	var a_length = $('#page_nav_ul a').length;
	$('#page_nav_ul a').eq(0).attr('href','index.html?id='+p_id+'&step='+page_step+'&have=1');
	for(i = 1; i < a_length; i++){
		if(i<p_step){
			//alert('index'+i+'.html?id='+p_id+'&step='+page_step+'&have=1');
			$('#page_nav_ul a').eq(i).attr('href','index'+i+'.html?id='+p_id+'&step='+page_step+'&have=1');
			if($('#page_nav_ul a').eq(i).hasClass('disableCss')){
				$('#page_nav_ul a').eq(i).removeClass('disableCss');
				$('#page_nav_ul a').eq(i).addClass('have-done');
			}
		}else{
			$('#page_nav_ul a').eq(i).attr('href','index'+i+'.html?id='+p_id+'&step='+page_step+'&have=1');
		}
		
	}
}


