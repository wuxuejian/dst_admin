1<?php
require_once dirname(__FILE__) . '/../common/Common.php';
require_once dirname(__FILE__) . '/../class/menuStub.php';
interface_log(DEBUG, 0, "***start menu**");
$menuData = array(
	'button'=>array(
		array(
			'name' => "春季活动",
			'sub_button' => array(
				array(
					'type' => 'view',
					'name' => '推广返利',
					'url' => 'http://yqzc.dstzc.com/index.php?r=promotion/rebate/index'
				),
					array(
					'type' => 'view',
					'name' => '活动日程',
					'url' => 'http://mp.weixin.qq.com/s?__biz=MzA3OTk5Mjc4MQ==&mid=405889424&idx=2&sn=9f0b9d073c73f869e27aaa37419f761f#rd'
				),
				array(
					'type' => 'view',
					'name' => '租车优惠',
					'url' => 'http://mp.weixin.qq.com/s?__biz=MzA3OTk5Mjc4MQ==&mid=405889424&idx=3&sn=ef4b4af87cee2479cf82dcfb4e59b411#rd'
				)
			)
		),
		array(
			'name' => "参与活动",
			'sub_button' => array(
				array(
					'type' => 'view',
					'name' => '我要注册',
					'url' => 'http://yqzc.dstzc.com/car_weixin/index.php'
				),
				array(
					'type' => 'view',
					'name' => '邀请朋友',
					'url' => 'http://yqzc.dstzc.com/index.php?r=promotion/invite-friend/index'
				),
				array(
					'type' => 'view',
					'name' => '申请提现',
					'url' => 'http://yqzc.dstzc.com/index.php?r=promotion/apply-cash/index'
				)
				
			)
		),
		array(
			'name' => "我要",
			'sub_button' => array(
				array(
					'type' => 'click',
					'name' => '查看排名',
					'key' => 'ranking'
				),
				array(
					'type' => 'click',
					'name' => '查看奖金',
					'key' => 'reward'
				),
				array(
					'type' => 'click',
					'name' => '联系客服',
					'key' => 'service'
				)
			)
		),
	)
);

$ret = menuStub::create($menuData);
if(false === $ret) {
	interface_log(DEBUG, 0, "create menu fail!");
	echo "create menu fail!\n";
} else {
	interface_log(DEBUG, 0, "creat menu success");
	echo "create menu success!\n";
}
interface_log(DEBUG, 0, "***end menu***");
