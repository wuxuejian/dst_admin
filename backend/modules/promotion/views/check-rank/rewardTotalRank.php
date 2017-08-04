<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="content-type">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>活动总排名</title>
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
            margin:10px 0px;
        }
        .topTitle .title{
            text-align: center;
            font-size: 16px;
            font-weight: bold;
        }
        .topTitle div{
            margin-bottom: 8px;
        }
        .detail{
            margin:10px 0px 30px 0px;
        }
        .detail table{
            border-collapse: collapse;
            width: 100%;
            margin:5px auto;
        }
        .detail table td{
            text-align: center;
            line-height: 23px;
            border:1px solid #FDFDFD;
            background-color: #FCFCFC;
        }
        .detail table tr:nth-child(odd) td{
            background-color: #F8F8F8;
        }
        .detail table tr.tbHead td{
            background-color: #D7F3E4;
        }
    </style>
</head>
<body>
<div class="container">

    <?php if(isset($rewardTotalRank) && $rewardTotalRank){ ?>
        <div class="topTitle">
            <div class="title">地上铁春季返利活动排名榜</div>
            <div>截至目前，共有 <?php echo isset($totalPerson) ? $totalPerson : 0; ?> 人参与了活动，以下是获得奖金排名前 <?php echo isset($topNum) ? $topNum : 0; ?> 的参与者：</div>
        </div>
        <div class="detail">
            <table cellspacing="0" cellpadding="5" align="center" width="100%" border="1">
                <tr class="tbHead"><td>排名</td><td>参与者</td><td>手机号</th><td>奖金</td><td>朋友租车量</td></tr>
                <?php
                    $str = '';
                    $i = 1;
                    foreach($rewardTotalRank as $item){
                        $n = count($item);
                        if($n == 1){
                            $str .= "<tr><td rowspan=$n>{$i}</td><td>{$item[0]['inviter']}</td><td>{$item[0]['inviter_mobile']}</td><td>{$item[0]['reward']}</td><td>{$item[0]['rent_num']}</td></tr>";
                        }else{
                            $str .= "<tr><td rowspan=$n>{$i}</td><td>{$item[0]['inviter']}</td><td>{$item[0]['inviter_mobile']}</td><td>{$item[0]['reward']}</td><td>{$item[0]['rent_num']}</td></tr>";
                            foreach($item as $k=>$row){
                                if($k > 0){
                                    $str .= "<tr><td>{$row['inviter']}</td><td>{$row['inviter_mobile']}</td><td>{$row['reward']}</td><td>{$row['rent_num']}</td></tr>";
                                }
                            }
                        }
                        $i++;
                    }
                    echo $str;
                ?>
            </table>
        </div>
    <?php }else{ ?>
        <div class="topTitle">
            <div class="title">地上铁春季返利活动排名榜</div>
            <div>>截至目前，共有 <?php echo isset($totalPerson) ? $totalPerson : 0; ?> 人参与了活动，但还没有任何受邀人成功的从地上铁租车，所以暂时还没有产生奖金排名哦！</div>
        </div>
    <?php } ?>
</div>
</body>
<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
</html>
