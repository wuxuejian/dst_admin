$(function(){
    function tabCurts(){
        $(".cont").eq(0).show();
        $(".header li").on("tap", function(){
            var i = $(this).index();
            $(this).addClass("currend").siblings('li').removeClass("currend");
            $(".cont").eq(i).show().siblings(".cont").hide();
        });
    };
    tabCurts();
});
