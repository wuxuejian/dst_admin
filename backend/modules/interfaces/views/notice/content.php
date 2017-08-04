<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN"
"http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <meta http-equiv="content-type" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
    <title><?php echo $notice ? $notice['vn_title'] : '未找到消息'; ?></title>
    <script>

    </script>
    <style>
        body{
            margin: 0;
            padding: 10px;
            color: #555;
            font-family: '微软雅黑';
        }
        h1,h2,h3{
            margin: 0;
            padding: 10px 0;
        }
    </style>
</head>
<body>
    <h2><?php echo $notice['vn_title']; ?></h2>
    <div>
        <span><?php echo $notice['vn_public_time']; ?></span>
        &nbsp;&nbsp;
        <span>消息类型：<?php echo isset($vn_type[$notice['vn_type']]) ? $vn_type[$notice['vn_type']]['text'] : ''; ?><span>
    </div>
    <br />
    <div><?php echo $notice['vn_content']; ?></div>
</body>
</html>