<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        /*'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],*/
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ],
    'params' => $params,
    //默认路由
    'defaultRoute' => '/index/index',
    //模块配置
    'modules' => [
        'system' => [
            'class' => 'backend\modules\system\Module',
        ],
        'car' => [
            'class' => 'backend\modules\car\Module',
        ],
        'drbac' => [
            'class' => 'backend\modules\drbac\Module',
        ],
        'customer' => [
            'class' => 'backend\modules\customer\Module',
        ],
		'charge' => [
            'class' => 'backend\modules\charge\Module',
		],
		'card' => [
            'class' => 'backend\modules\card\Module',
		],
		'vip' => [
            'class' => 'backend\modules\vip\Module',
		],
		'interfaces' => [
            'class' => 'backend\modules\interfaces\Module',
		],
		//通讯模块
		'communication'=>[
			'class' => 'backend\modules\communication\Module',
		],
        //车辆实时数据监控模块
        'carmonitor'=>[
            'class' => 'backend\modules\carmonitor\Module',
        ],
        //车辆实时数据监控模块（新）
        'carmonitorgb'=>[
        'class' => 'backend\modules\carmonitorgb\Module',
        ],
        //电桩监控模块
        'polemonitor'=>[
            'class' => 'backend\modules\polemonitor\Module',
        ],
        //机动车所有人模块
        'owner'=>[
            'class' => 'backend\modules\owner\Module',
        ],
        //车辆运营公司管理模块
        'operating'=>[
            'class' => 'backend\modules\operating\Module',
        ],
        //微信推广活动
        'promotion'=>[
            'class' => 'backend\modules\promotion\Module',
        ],
        //流程管理
        'process'=>[
        	'class' => 'backend\modules\process\Module',
        ],
         //售后服务站点管理
        'station'=>[
       		 'class' => 'backend\modules\station\Module',
        ],
        //采购订单
        'purchase'=>[
             'class' => 'backend\modules\purchase\Module',
        ], 
        //配件
        'parts'=>[
             'class' => 'backend\modules\parts\Module',
        ],
		 //维修模块
        'repair'=>[
            'class' => 'backend\modules\repair\Module',
        ] 
    ]
];
