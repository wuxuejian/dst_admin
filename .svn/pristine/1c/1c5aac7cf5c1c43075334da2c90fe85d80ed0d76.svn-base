<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="content-type">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>奖金提现申请</title>
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
            font-size: 16px;
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
        .detail table td.tdRight{
            text-align: right;
            padding-right:5px;
        }
        /*****选择收款方式****************************/
        .payment input{
            outline:none;
        }
        .payment table .payType{
            height: 25px;
            line-height: 25px;
            text-align: center;
            width: 80%;
            min-width: 80px;
            border: 1px solid #D5D5D5;
            border-radius: 3px;
            cursor: pointer;
        }
        .payment table .current{
            background-color: #70d49f;
            color: #fff;
        }
        .payment table input[type=radio]{
            display:none;
        }
        .fillInfoWrap{
            padding: 5px;
            height: 130px;
            margin-bottom: 20px;
        }
        .fillInfoWrap .tabPanel{
            background-color: #F5F5F5;
            border: 1px solid #F1F1F1;
            border-radius: 3px;
            padding:10px 0px 5px 10px;
        }
        .fillInfoWrap .tabPanel div{
            margin-bottom: 5px;
        }
        .fillInfoWrap .alipay, .fillInfoWrap .cash{
            display: none;
        }
        .fillInfoWrap input:focus{
            box-shadow:0 0 8px #ddd;
        }
        .fillInfoWrap input[type=text]{
            width:90%;
            padding-left:5px;
            outline:none;
            border:1px solid #ccc;
            border-radius:5px;
            height:30px;
        }
        .submitBtnWrap{
            margin: 5px 0px 10px 0px;
            text-align: center;
        }
        .submitBtn{
            display: block;
            background-color:#70d49f;
            width: 60%;
            min-width: 90px;
            margin: 0 auto;
            height:35px;
            line-height:35px;
            font-size:16px;
            color:#fff;
            letter-spacing:1px;
            text-decoration: none;
            cursor:pointer;
            border-radius:5px;
            -webkit-border-radius:5px;
            box-shadow: 0px 4px 4px #ddd;
        }
        .hasSubmit{} /*用以标记是否已提交申请过*/
        #applyFormErrTip{
            background: #000;
            filter: alpha(opacity=50);
            opacity: 0.5;
            display: none;
            color:#fff;
            position: absolute;
            top: 50%;
            left: 50%;
            width: 250px;
            height: 30px;
            margin-left:-125px;
            margin-top:-15px;
            line-height: 30px;
            text-align:center;
            border-radius:5px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="topTitle">
        <div class="title">您当前可以申请提现的奖金是：<?php echo $rewardDetails['unsettled_reward']; ?> 元</div>
        <div>以下是从参与活动以来，您所邀请注册并成功租车的朋友的所有历史租车记录：</div>
    </div>
    <div class="detail">
        <table cellspacing="0" cellpadding="5" align="center" width="100%" border="1">
            <tr class="tbHead"><td>租车人</td><td>租车日期</td><td>租车数量(部)</td></tr>
            <?php
                $str = '';
                foreach($friendsLetInfo as $item){
                    $str .= "<tr><td>{$item['renter']}</td><td>".substr($item['create_time'],0,10)."</td><td class='tdRight'>{$item['amount']}</td></tr>";
                }
                echo $str;
            ?>
        </table>
    </div>

    <div class="payment">
        <form class="apply_form">
            <input type="hidden" name="open_id" value="<?php echo isset($open_id) ? $open_id : ''; ?>" >
            <input type="hidden" name="total_rent_num" value="<?php echo $rewardDetails['totalAmount']; ?>" >
            <input type="hidden" name="total_reward" value="<?php echo $rewardDetails['total_reward']; ?>" >
            <input type="hidden" name="settled_reward" value="<?php echo $rewardDetails['settled_reward']; ?>" >
            <input type="hidden" name="unsettled_reward" value="<?php echo $rewardDetails['unsettled_reward']; ?>" >
            <input type="hidden" name="apply_letIds" value="<?php echo $rewardDetails['noSettle_letIds']; ?>" >
            <div>请选择收款方式</div>
            <table cellspacing="5" cellpadding="5" align="center" width="100%" border="0">
                <tr>
                    <td align="left">
                        <div class="payType current">银行转账<input type="radio" name="pay_type" value="bank" checked="checked" /></div>
                    </td>
                    <td align="center">
                        <div class="payType">支付宝转账<input type="radio" name="pay_type" value="alipay" /></div>
                    </td>
                    <td align="right">
                        <div class="payType">领取现金<input type="radio" name="pay_type" value="cash" /></div>
                    </td>
                </tr>
            </table>
            <div class="fillInfoWrap">
                <div class="tabPanel bank">
                    <div><label>银行名称：</label><input type="text" name="bank_name" placeholder="必填" /></div>
                    <div><label>银行卡号：</label><input type="text" name="bank_card" placeholder="必填" /></div>
                </div>
                <div class="tabPanel alipay">
                    <div><label>支付宝账号：</label><input type="text" name="alipay_account" placeholder="必填" /></div>
                </div>
                <div class="tabPanel cash">
                    <div>待规划...</div>
                </div>
            </div>
            <div class="submitBtnWrap">
                <a href="javascript:void(0)" class="submitBtn">提交申请</a>
            </div>
        </form>
    </div>
    <div id="applyFormErrTip"></div>
</div>
</body>
<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script>
    $(function(){
        /*选择收款方式*/
        $(".payment").find('.payType').on('click',function(event) {
            $('.payType').removeClass('current');
            $(this).addClass('current');
            var type = $(this).find('input').prop("checked",true).val();
            $('.tabPanel').hide().find('input').val('');
            $('.'+type).show();
        });

        /*提交申请*/
        $('.submitBtn').on('click',function(){
            if($('.submitBtn').hasClass('hasSubmit')){
                $('#applyFormErrTip').show().text('您已经提交申请过了！').fadeOut(2500);
                return false;
            }
            var unsettled_reward = $('[name=unsettled_reward]',$('.apply_form')).val();
            if(!parseFloat(unsettled_reward)){
                $('#applyFormErrTip').show().text('您当前没有可申请提现的奖金！').fadeOut(2500);
                return false;
            }
            //获取选中的支付方式radio值
            var _payType = $('[name=pay_type]:checked',$('.apply_form')).val();
            switch(_payType){
                case 'bank':
                    var _bankName = $.trim($('[name=bank_name]',$('.apply_form')).val());
                    var _bankCard = $.trim($('[name=bank_card]',$('.apply_form')).val());
                    //_bankName = _bankName.replace(/(^\s*)|(\s*$)/g,''); //去两端空格
                    if(!_bankName && !_bankCard){
                        $('#applyFormErrTip').show().text('银行名称和卡号都必填！').fadeOut(2000);
                        return false;
                    }
                    if(!_bankName){
                        $('#applyFormErrTip').show().text('银行名称必填！').fadeOut(2000);
                        return false;
                    }
                    if(!_bankCard){
                        $('#applyFormErrTip').show().text('银行卡号必填！').fadeOut(2000);
                        return false;
                    }
                    var pattern = /^(\d{16}|\d{19})$/;
                    if(!pattern.test(_bankCard)){
                        $('#applyFormErrTip').show().text('银行卡号应是16或19位数字！').fadeOut(2500);
                        return false;
                    }
                    break;
                case 'alipay':
                    var _account = $.trim($('[name=alipay_account]',$('.apply_form')).val());
                    if(!_account){
                        $('#applyFormErrTip').show().text('支付宝账号必填！').fadeOut(2000);
                        return false;
                    }
                    var pattern = /^(1\d{10}|\w+@\w+\.\w{2,3})$/;
                    if(!pattern.test(_account)){
                        $('#applyFormErrTip').show().text('支付宝账号应是邮箱或手机号！').fadeOut(2500);
                        return false;
                    }
                    break;
                case 'cash':
                    $('#applyFormErrTip').show().text('此方式还在完善中...').fadeOut(2500);
                    return false;
                    break;
            }
            $.ajax({
                type: 'post',
                url:'<?php echo yii::$app->urlManager->createUrl(['promotion/apply-cash/submit']); ?>',
                data: $('.apply_form').serialize(),
                dataType: 'json',
                success: function(rdata){
                    if(rdata.status){
                        $('#applyFormErrTip').show().text('提交成功！我们将会尽快处理！').fadeOut(3000);
                        $('.submitBtn').addClass('hasSubmit');
                    }else{
                        $('#applyFormErrTip').show().text(rdata.info).fadeOut(2000);
                    }
                }
            });
        });
    });
</script>
</html>
