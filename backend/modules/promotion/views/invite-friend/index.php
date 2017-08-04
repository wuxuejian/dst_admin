<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="content-type">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>邀请朋友</title>
    <style type="text/css">
        .*{
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
            font-family: microsoft yahei, Arial, Helvetica, sans-serif;
        }
        .container{
            margin:0 auto;
            width: 95%;
            padding:5px;
        }
        .topTitle{
            font-size:16px;
        }
        .detail{
            margin:15px 0px;
        }
        .detail_inviteRules{
            margin:5px 0px;
        }
        .detail_inviteWords{
            margin:10px 0px;
			border:1px solid #ccc;
			border-radius:5px;
			padding:5px 10px;	
        }
		.detail_howInvite{
			text-align:center;
			color:#079C6A;
			font-weight:bold;
			padding:8px 0px;
			margin-bottom:20px;
		}
		.bottomTip{
			margin:10px auto;
		}
		.bottomTip_item{
			margin-bottom:10px;
		}
		.bottomTip .qrcode{
			text-align:center;
		}
    </style>
</head>
<body>
    <div class="container">
        <div class="topTitle">
            <div >将您的邀请码告诉朋友，朋友租车之后您就可以获得现金奖励！</div>
        </div>
		
        <div class="detail">
			<?php if(isset($myInviteCode)){ ?>
            <div class="detail_inviteRules">
                您可以点击分享按钮，将您的邀请码分享给朋友，一旦朋友注册时填写了您的邀请码，然后从地上铁完成了租车，您就可以获得相应的奖励金额：
            </div>
            <div class="detail_inviteWords">
                朋友，我在用地上铁租车了。很好用，想分享给你！我的邀请码是：<span style="font-size:16px;color:#FF9A02;"><?php echo $myInviteCode; ?></span>
			</div>
			<div class="detail_howInvite">
				点击右上角“发送给朋友”即可
			</div>
			<?php }else{ ?>
			对不起，您还没有注册，请你依次点击微信菜单：参与活动->我要注册。
			<?php } ?>
        </div>
		
        <div class="bottomTip">
            <div class="bottomTip_item">如何知道我邀请了多少人？</div>
            <div class="bottomTip_item">第一步：关注地上铁官方微信公众号（dstzc8）;</div>
            <div class="bottomTip_item">第二步：依次点击微信菜单：我要->查询排名，就可以查看您当前所成功邀请的朋友数量了。</div>
            <div class="bottomTip_item">您也可以通过长按以下二维码来完成关注：</div>
            <div class="bottomTip_item qrcode">
				<img src="http://yqzc.dstzc.com/car_weixin/images/subscribe.jpg" width="150px" height="150px" />
			</div>
        </div>
    </div>
</body>
<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
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
        var _title  = '地上铁租车微信邀请码';
        var _desc   = '邀请码：<?php echo $myInviteCode; ?>';
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