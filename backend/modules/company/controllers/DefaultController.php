<?php

namespace frontend\modules\company\controllers;
use Yii;
use yii\base\Module;
use yii\web\Controller;
use common\models\SysMenu;
use common\models\EcsOrderInfo;

class DefaultController extends \frontend\controllers\BaseController
{
    public function actionIndex()
    {
        //最近订单
        $orderInfo = new EcsOrderInfo();
        $data1 =$orderInfo->getOrders(1);
        $data2 =$orderInfo->getOrders(2);
        $data3 =$orderInfo->getOrders(3);
        $datay[1]=$data1;
        $datay[2]=$data2;
        $datay[3]=$data3;

        //配送订单
        $info1 = $orderInfo-> getSendOrder(1);
        $info2 = $orderInfo-> getSendOrder(2);
        $info3 = $orderInfo-> getSendOrder(3);
        $infos[1] = $info1;
        $infos[2] = $info2;
        $infos[3] = $info3;
        //15日内订单统计
        $chartData = EcsOrderInfo::findBySql("SELECT COUNT(ordernum) as num,order_time FROM (SELECT COUNT(order_id) AS ordernum,FROM_UNIXTIME(add_time,'%Y-%m-%d') AS order_time
 FROM  ecs_order_info WHERE order_status < 8 AND DATE_SUB(CURDATE(), INTERVAL 15 DAY) < FROM_UNIXTIME(add_time,'%Y-%m-%d') GROUP BY add_time) orderinfo GROUP BY  order_time
 ")->asArray()->all();
$datas =array();
        $i=0;
$color = array('#FF3366','#CC99FF','#99CCFF','#FFCC00','#FF9966','#FFFF33','#33FF33','#99FFFF','#CCFF66','#CCFFFF','#00CCCC','#339933','#0000FF','#66CCFF','#FF66CC');
foreach($chartData as $key =>$arr){
    $data['label'] = substr($arr['order_time'],5);
    $data['value'] = $arr['num'];
    $data['color'] = $color[$i];
    $datas[]=$data;
    $i++;
}

        $DataJSON = [
            'chart' => [
                'yAxisName'=>'订单数量',
                'caption'=>'最近订单',
                'numberPrefix'=>'',
                'useRoundEdges'=>'1',
                 'showBorder'=>'0'
            ]
        ];
        $DataJSON['data']=$datas;
        return $this->render('index',['DataJSON'=>json_encode($DataJSON),'datay'=>$datay,'infos'=>$infos]);
    }
     public function actionGetUserMenuTree(){
        $mark = Yii::$app->request->get('mark', '');
        $nodes = $this->getMenuItems($mark);
        echo json_encode($nodes);
        
        exit;
    }
    public function getMenuItems($mark){
        $session = Yii::$app->session;
        $session->open();
        $nodes = []; 
        $items = SysMenu::find()
            ->select('id, mark, text, has_children, href, reload, outer')
            ->asArray()
            ->indexBy('mark')
            ->where('parent=:id AND installed=1 AND deleted=0', [':id'=>$mark])
            ->orderBy('sort')
            ->all();

        $privMenus = $session['bPrivMenus'];
        $total = count($items);
        foreach ($items as $item ){
            $flag = 1;
            if (!is_array($privMenus) || !in_array($item['mark'], $privMenus)){
                $flag = 0;  // 去除这项菜单 0去掉菜单 tanbenjiang edit 暂时取消权限
                if($_SESSION['bAdmin'] == 1){ //如果为商户管理员则不用验证权限
                    $flag = 1;
                }
            }
            if ($flag) {
                if ($item['has_children']) {
                    $item['children'] = $this->getMenuItems($item['mark']);
                    $item['state'] = 'closed';
                }

                $nodes[] = $item;
            }
        }
        
        return $nodes;
    }

}
