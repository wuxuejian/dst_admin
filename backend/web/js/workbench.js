/*--------------左边栏变动sidebar----------------*/
/*左边栏左右缩进*/
$('.sidebar-fold').click(function() {
	var status_code_sflod;
	if($('.sidebar').hasClass('sidebar-full')){
		status_code_sflod = 1;
	}else{
		status_code_sflod = 0;
	}

	if(status_code_sflod){
		$(this).removeClass('icon-ellipsish');
		$(this).addClass('icon-ellipsisv')
		$('.sidebar').removeClass('sidebar-full');
		$('.sidebar').addClass('sidebar-mini');
		$('.product').removeClass('product-full');
		$('.product').addClass('product-mini');
	}else{
		$(this).removeClass('icon-ellipsisv');
		$(this).addClass('icon-ellipsish')
		$('.sidebar').removeClass('sidebar-mini');
		$('.sidebar').addClass('sidebar-full');
		$('.product').removeClass('product-mini');
		$('.product').addClass('product-full');
	}
})

/*左边栏上下缩进*/

$(document).ready(function(){
	var sidebar_ul_length = $('.sidebar .sidebar-nav').length;
	var li_height = $('.sidebar .sidebar-nav ul li').height();
	for (var i = 0; i < sidebar_ul_length; i++){
		var li_length = $('.sidebar .sidebar-nav').eq(i).children('ul').children('li').length;
		var content_height = li_length * li_height;
		$('.sidebar .sidebar-nav').eq(i).children('ul').css('height',content_height);
	}
})


$('.sidebar-title-inner').click(function(){
	$(this).parent('.sidebar-nav').toggleClass('sidebar-nav-flod');
	if($(this).parent('.sidebar-nav').hasClass('sidebar-nav-flod')){
		$(this).children('.sidebar-title-icon').removeClass('icon-down');
		$(this).children('.sidebar-title-icon').addClass('icon-left');
	}else{
		$(this).children('.sidebar-title-icon').removeClass('icon-left');
		$(this).children('.sidebar-title-icon').addClass('icon-down');
	}
})

/*--------------二级菜单变动product-nav-----------------*/
$('.v-product-navbar-collapse').click(function(){
	$('.product').toggleClass('product-col-1');
})


$('.have-ul').click(function(){
	var ul_state = $(this).siblings('ul');
	if(ul_state.hasClass('hide')){
		ul_state.removeClass('hide');
	}else{
		ul_state.addClass('hide');
	}
})

/*切换显示内容multi-block*/
$('.multi-block .title').click(function(){
	var _this = $(this)
	_this.siblings('.title').removeClass('active');
	_this.addClass('active');
	var active_state = _this.index();
	_this.parent().siblings('.block-content').children('.box').hide();
	_this.parent().siblings('.block-content').children('.box').eq(active_state).fadeIn(500);
})

/*显示目前栏目*/
$(document).ready(function(){
	var title_content = $('.product-nav-list').find('.active').text();
	if(title_content == ''){title_content = '地上铁后台管理系统'}
	$('.console-title div h5').text(title_content);
	$('title').text(title_content);
})

/*table设置*/
$('.table_checkbox').click(function(){
	if(this.checked){
		$('.d_checkbox').attr('checked',true);
	}else{
		$('.d_checkbox').attr('checked',false);
	}
})

$('.table_nth').click(function(){
	var text_var = $(this).text();
	if($(this).children('span').hasClass('icon-pull-down')){
		$(this).html( text_var + '<span class=icon-pull-up></span>');
	}else if($(this).children('span').hasClass('icon-pull-up')){
		$(this).html( text_var + '<span class=icon-pull-down></span>');
	}else{
		$(this).html( text_var + '<span class=icon-pull-down>i</span>');
	}
})

/*btn 按压效果*/
$('.btn').mousedown(function(){
	$(this).addClass('btn-shadow');
	$(this).mouseup(function(){
		$(this).removeClass('btn-shadow');
	})
	$(this).mouseleave(function(){
		$(this).removeClass('btn-shadow');
	})
})

/*提示*/
function remind_f(level,content,time){
	var re = $('.remind div');
	if(!time){time = 3500 ;}
	re.removeClass();
	re.addClass(level);
	re.text(content);
	re.slideDown(300);
	setTimeout("$('.remind div').fadeOut(1000)",time);
}