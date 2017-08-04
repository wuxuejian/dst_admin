<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="content-type">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>分享邀请码</title>
    <style type="text/css">
        *{
            padding:0;
            margin:0;
        }
		img{
			border:0;
		}
        body{
            background:#fff;
            color:#555;
            font-size:14px;
            font-family: 'microsoft yahei', Arial, Helvetica, sans-serif;
            width: 100%;
            height:100%;
        }
        .container{
            margin:0 auto;
            width: 95%;
            padding:5px;
            position: relative;
            z-index: 1;
        }
        .topTitle{
            font-size:16px;
            margin:10px 0px 20px 0px;
        }
        .detail{
            margin:10px 0px 30px 0px;
        }
        .detail_inviteCode{
			text-align:center;
			color:#FF9A02;
			font-weight:bold;
			font-size:20px;
			padding:10px;
        }
		.detail_sendFriendBtn,.detail_signNowBtn{
			text-align:center;
			color:#fff;
			padding:15px;
		}
		.detail_sendFriendBtn span,.detail_signNowBtn span{
			padding:12px 30px;
			background-color:#009900;
			border-radius:5px;
			cursor:pointer;
		}
		.bottomTip{
			margin:10px auto;
		}
		.bottomTip_item{
			margin-bottom:8px;
		}
		.bottomTip .qrcode{
			text-align:center;
		}

		/****************遮罩层********************/
		#mask{
            background-image: url(./images/bg5641.png);
            background-repeat: repeat;
			display: none;
			position: fixed;
			z-index: 999;
            left: 0;
            top: 0;
		}
		#mask_indicator{
            width: 100%;
			height:120px;
			z-index:1000;
            font-size: 16px;
        }
        .hint{
            width: 260px;
            height: 96px;
            color: #fff;
            background-image: url(./images/icon5545.png);
            background-repeat: no-repeat;
            background-position: right center;
            margin:0 auto;
            line-height: 150px;
            text-align: left;
        }
        #mask_indicator p{
            color: #fff;
            text-align: center;
        }
		.alreadyKnowBtn{
			width: 120px;
			height:50px;
			border: 1px solid #fff;
			color: #fff;
			text-align: center;
			line-height: 50px;
			border-radius: 5px;
			margin: 50px auto 0;
		}
    </style>
</head>
<body>
    <div class="container">

        <?php if(isset($myInviteCode) && $myInviteCode){ ?>
        <div class="topTitle">
                <?php if(isset($isVisitSignMenu) && $isVisitSignMenu){ ?>
                <div >您已经注册了，赶快邀请朋友注册吧！这是您的专属邀请码：</div>
                <?php }else{ ?>
                <div >恭喜！这是您的专属邀请码：</div>
                <?php } ?>
        </div>
        <div class="detail">
			<div class="detail_inviteCode"><?php echo $myInviteCode; ?></div>
			<div class="detail_sendFriendBtn">
				<span id="sendFriendBtn">发给朋友</span>
			</div>
        </div>
        <?php }else{ ?>
        <div class="topTitle">
            <div>对不起，您还没有参与活动，请您先注册！</div>
        </div>
        <div class="detail">
            <div class="detail_inviteCode"></div>
            <div class="detail_signNowBtn">
                <span id="signNowBtn">马上注册</span>
            </div>
        </div>
        <?php } ?>


        <div class="bottomTip">
            <div class="bottomTip_item">如何知道我的排名和奖金？</div>
            <div class="bottomTip_item">方法一：</div>
            <div class="bottomTip_item">第一步：关注地上铁官方微信公众号（dstzc8）;</div>
            <div class="bottomTip_item">第二步：依次点击微信菜单：我要->查询排名/查询奖金，就可以查看您当前成功邀请的朋友数量、排名和奖金了。</div>
            <div class="bottomTip_item">您也可以通过长按以下二维码来完成关注：</div>
            <div class="bottomTip_item qrcode">
				<img src="http://yqzc.dstzc.com/car_weixin/images/subscribe.jpg" width="150px" height="150px" />
			</div>
			<div class="bottomTip_item">方法二：</div>
            <div class="bottomTip_item">咨询地上铁租车公司免费客服电话：400-860-4558</div>
        </div>
    </div>
    <div id='mask'>
        <div id="mask_indicator">
            <div class="hint">
                <span>点击右上角分享给好友</span>
            </div>
            <br />
            <p>
                您也可以关注地上铁租车官网微信公众号(dstzc8),随时查看您的排名与奖金!
            </p>
            <div class="alreadyKnowBtn">知道了<div>
        </div>
    </div>
</body>
<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script>
	$(function(){

        /*打开遮罩层*/
        function maskLayer(){
            $("#mask").height($(window).height());
            $("#mask").width($(window).width());
            $("#mask").show();
        };

        /*关闭遮罩层*/
        function closeLayer(){
            $("#mask").hide();
        };

        /*遮罩层上“知道了”按钮*/
        $(".alreadyKnowBtn").on("click", function(){
            closeLayer();
        });

        /*“发送朋友”按钮*/
        $("#sendFriendBtn").on("click",function(){
            maskLayer();
        });

        /*“马上注册”按钮*/
        $("#signNowBtn").on("click",function(){
            location.href = '<?php echo yii::$app->urlManager->createUrl(['promotion/sign/index']); ?>';
        });

    });
</script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
    wx.config({
        debug: false,
        appId: '<?php echo $signPackage["appId"];?>',
        timestamp: <?php echo $signPackage["timestamp"];?>,
        nonceStr: '<?php echo $signPackage["nonceStr"];?>',
        signature: '<?php echo $signPackage["signature"];?>',
        jsApiList: [
            // 下面所有要调用的 API 都要加到这个列表中
            'onMenuShareAppMessage',
            'onMenuShareTimeline',
            'onMenuShareQQ'
        ]
    });
    wx.ready(function () {
        // 在这里调用 API
        var _title  = '地上铁租车春季返利，邀请朋友租车获丰厚现金奖励！';
        var _desc   = '这是我的邀请码：<?php echo $myInviteCode; ?>，一起来赚奖金吧！';
        var _link   = 'http://yqzc.dstzc.com/index.php?r=promotion/sign/index&invite_code=<?php echo $myInviteCode; ?>';
        var _imgUrl = 'http://yqzc.dstzc.com/images/logo100.png';
        // 2. 分享接口
        // 2.1 监听“分享给朋友”，按钮点击、自定义分享内容及分享结果接口
        wx.onMenuShareAppMessage({
            title: _title,
            desc: _desc,
            link:  _link,
            imgUrl: _imgUrl,
            trigger: function (res) {
                //alert('用户点击发送给朋友');
            },
            success: function (res) {
                //alert('已分享');
            },
            cancel: function (res) {
                //alert('已取消');
            },
            fail: function (res) {
                alert(JSON.stringify(res));
            }
        });

        // 2.2 监听“分享到朋友圈”按钮点击、自定义分享内容及分享结果接口
        wx.onMenuShareTimeline({
            title: _title,
            desc: _desc,
            link:  _link,
            imgUrl: _imgUrl,
            trigger: function (res) {
                //alert('用户点击分享到朋友圈');
            },
            success: function (res) {
                //alert('已分享');
            },
            cancel: function (res) {
                //alert('已取消');
            },
            fail: function (res) {
                alert(JSON.stringify(res));
            }
        });

        // 2.3 监听“分享到QQ”按钮点击、自定义分享内容及分享结果接口
        wx.onMenuShareQQ({
            title: _title,
            desc: _desc,
            link:  _link,
            imgUrl: _imgUrl,
            trigger: function (res) {
                ///alert('用户点击分享到QQ');
            },
            complete: function (res) {
                //alert(JSON.stringify(res));
            },
            success: function (res) {
                //alert('已分享');
            },
            cancel: function (res) {
                //alert('已取消');
            },
            fail: function (res) {
                alert(JSON.stringify(res));
            }
        });
    });

    wx.error(function (res) {
        alert(res.errMsg);
    });
</script>

</html>
