<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>通知列表</title>
    <script type="text/javascript" src="<?= yii::getAlias('@web'); ?>/jquery-easyui-1.4.3/jquery.min.js"></script>
</head>
<body>
    <style>
        body{
            padding: 0;
            margin: 0;
            background: #ebebeb;
            font-family: "微软雅黑";
        }
        ul{
            margin: 0 auto;
            padding: 0 10px;
            list-style: none;
            background: #fff;
            border-radius: 4px;
        }
        .notice-list-a{
            display: block;
            width: 100%;
            height: 80px;
            padding: 15px 0;
            overflow: hidden;
            border-top: 1px solid #ebebeb;
            color: #030303;
            text-decoration: none;
        }
        .notice-list-a:visited{
            color: #030303;
            text-decoration: none;
        }
        .notice-list-a:active{
            background: none;
        }
        .notice-list-a .notice-image{
            float: left;
            width: 80px;
            height: 80px;
            border-radius:4px;
            background-position: center;
        }
        .notice-list-a .notice-title-pubtime{
            float: right;
        }
        .notice-list-a .notice-title-pubtime .title{
            color: #555;
            font-weight: bold;
            line-height: 40px;
        }
        .notice-list-a .notice-title-pubtime .pub-time{
            color: #999;
        }
        .get-more{
            display: block;
            width: 90%;
            height: 50px;
            background: #70d49f;
            margin: 16px auto;
            border-radius: 4px;
            color: white;
            font-size: 20px;
            line-height: 50px;
            text-align: center;
            cursor: pointer;
        }
        .get-more:hover{
            background: #60c48f;
        }
        #alert-window{
            width: 80%;
            background: white;
            border:1px solid #7cde9f;
            border-radius: 4px;
            position: fixed;
            top: 30%;
            display: none;
        }
        #alert-window-message{
            padding: 20px 10px;
            font-size:14px;
            color:#555;
            text-align:center;
            line-height: 22px;
        }
    </style>
    <script>
        function alert(msg){
            closeAlertWindow();
            var alertWindow = $('#alert-window');
            alertWindow.show();
            $('#alert-window-message').html(msg);
            var w = parseInt(($(window).width()-alertWindow.width())/2);
            alertWindow.css({'left': w});
        }
        function closeAlertWindow(){
            $('#alert-window').hide();
        }
        function setNoticeTileWidth(){
            var cotentWidth = $('.notice-list').children('li').eq(0).width();
            $('.notice-title-pubtime').width(cotentWidth - 100);
        }
        var page = 1;
        function getMore(obj){
            var cHtml = $(obj).html();
            if(cHtml == '数据加载中...'){
                return false;
            }
            $(obj).html('数据加载中...');
            $.ajax({
                type: "post",
                url: "<?= yii::$app->urlManager->createUrl(['interfaces/notice/list']); ?>",
                data: {'page': page},
                dataType: 'json',
                success: function(rData){
                    $(obj).html('获取更多');
                    if(rData[0]){
                        page ++;
                        var noticList = $('.notice-list');
                        for(var i in rData){
                            var str = '';
                            str += '<li>'
                            str += '<a href="?r=interfaces/notice/content&id='+rData[i].vn_id+'" class="notice-list-a">';
                            //str += '    <div class="notice-image" style="background-image:url('+rData[i].vn_icon_path+');"></div>';
                            str += '    <div class="notice-image" style="background-image:url(<?= yii::getAlias('@web'); ?>/images/news_frame.png);">';
                            if(rData[i].vn_icon_path){
                                str += '    <img style="width:80px;height:80px;" src="'+rData[i].vn_icon_path+'">';
                            }else{
                                str += '    <img style="width:80px;height:80px;" src="<?= yii::getAlias('@web'); ?>/images/logo100.png">';
                            }
                            
                            str += '   </div>';
                            str += '   <div class="notice-title-pubtime">';
                            str += '       <div class="title">'+rData[i].vn_title+'</div>';
                            str += '       <div class="pubtime">'+rData[i].vn_public_time+'</div>';
                            str += '   </div>';
                            str += '</a>';
                            str += '</li>';
                            noticList.append(str);
                        }
                        setNoticeTileWidth();
                    }else{
                        alert('数据已经全部加载，无更多数据！');
                    }
                }
            });
        }
        $(function(){
            $(window).resize(function(){
                setNoticeTileWidth();
            });
            setNoticeTileWidth();
            getMore();//获取数据
        });
    </script>
    <ul class="notice-list"></ul>
    <div class="get-more" onclick="getMore(this)">获取更多</div>
    <div id="alert-window" onclick="closeAlertWindow()">
        <div style="padding:20px;">
            <div id="alert-window-message"></div>
            <div style="text-align:center;width:100px;background:#70d49f;color:white;border-radius:4px;padding:10px;margin:0 auto;cursor:pointer;">关闭</div>
        </div>
    </div>
</body>
</html>