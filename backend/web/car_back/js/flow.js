//获取页面尾部?代码
function getQueryString(name) { 
	var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i"); 
	var r = window.location.search.substr(1).match(reg); 
	if (r != null) return unescape(r[2]); return null; 
}
//处理步骤显示退车流程
function step_show_hide(id,state,total_page,role_ids){
	$('input[name="id"]').val(id);
	if(state == null){
		state = 0;
	}else if(state == 21){
		state = 1;
	}else if(state == 22){
		state = 5;
	}
	for(var i = state; i < total_page; i++){
		var ii = parseInt(i)+1;
		$('.steps a').eq(ii).addClass('disabled');	
	}
	for(var j = 0 ; j <= total_page; j++){
		jj = parseInt(j)+1;
		$('.steps a').eq(j).attr('href','index'+jj+'.html?id='+id+'&state='+state+'&role_ids='+role_ids);
	}
}

//输入下拉有其它
$('.print_input').keyup(function(){
	if($(this).siblings('.print_down').text() == '其它'){
		$('.print_ture').show();
	}else{
		$('.print_ture').hide();
		$('.print_ture').val('');
		//alert($('.print_div').length);
		//alert($('.print_div').index());
		//alert($('.print_ture').index());
	}	
})
$('.print_div').click(function(){
	if($(this).siblings('.print_down').text() == '其它'){
		$('.print_ture').show();
	}else{
		$('.print_ture').hide();
		$('.print_ture').val('');
		//alert($('.print_div').length);
		//alert($('.print_div').index());
		//alert($('.print_ture').index());
	}	
})

//添加多一个栏目的方法
function add_copy(classname){	
	var page_copy = $('.'+ classname ).eq(0).clone();
	page_copy.find('input').val('');
	page_copy.find('select').val('');
	$('.'+ classname).last().after(page_copy);
	//预处理下拉框
  	$('.selection.dropdown').dropdown();
  	$('.delete_copy').click(function(){
		$(this).parents('.copy_parents').remove();
		num_check();
	})
	$('select[name="car_no[]"]').change(function(){
		for(i=0; i<car_list.length; i++){
			if($(this).val() == car_list[i].plate_number){
				$(this).parents('.copy_parents').find('input[name="car_model_name[]"]').val(car_list[i].car_model_name);
			}
		}
	})
	num_check();
}
//删除拷贝的内容
$('.delete_copy').click(function(){
	$(this).parents('.copy_parents').remove();
	num_check();
})

//获取上一次用户和操作时间
function user_time(id,user,time){
	if(user != '' && user != undefined ){
		$('.oper_user').text('操作人：'+user);
	}else{
		$('.oper_user').hide();
	}
	if(time != '' && time != undefined){
		$('.oper_time').text(time);
	}else{
		$('.oper_time').hide();
	}
}
//显示隐藏特定对象
function show_f(id){
	$('#'+ id ).show();
}
function hide_f(id){
	$('#'+ id ).hide();
}

//关闭窗口
function cancel(){
	window.opener=null;
	window.open('','_self');
	window.close();
}

//上传按钮显示文件名
$(".a-upload").on("change","input[type='file']",function(){
    var filePath=$(this).val();
    if(filePath.indexOf("jpg")!=-1 || filePath.indexOf("png")!=-1){
        $(".fileerrorTip").html("").hide();
        var arr=filePath.split('\\');
        var fileName=arr[arr.length-1];
        $(".showFileName").html(fileName);
    }else{
        $(".showFileName").html("");
        $(".fileerrorTip").html("您未上传文件，或者您上传文件类型有误！").show();
        return false 
    }
})

