<?php
/**
 * 本控制器为各种【combogrid】获取下拉列表数据
 */
namespace backend\modules\polemonitor\controllers;
use yii;
use yii\data\Pagination;
use backend\controllers\BaseController;
use backend\models\ChargeFrontmachine;
use backend\models\ChargeSpots;
use backend\models\ChargeStation;

class CombogridController extends BaseController{

    /**
     * 获取前置机列表（查询区域的前置机combogrid）
     */
    public function actionGetFrontMachineList(){
        $queryStr = isset($_REQUEST['q']) ? trim($_REQUEST['q']) : ''; // 检索过滤字符串
        $query = ChargeFrontmachine::find()
            ->select(['id','addr','port'])
            ->where(['is_del'=>0]);
        if($queryStr){ // 检索过滤时
            $total = $query->andWhere([
                'or',
                ['like', 'addr', $queryStr],
                ['like', 'port', $queryStr]
            ])
            ->count();
        }else{ // 默认查询
            $total = $query->count();
        }
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount' => $total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        return json_encode($returnArr);
    }


    /**
     * 获取电桩列表（查询区域中的combogrid）
     */
    public function actionGetChargerList(){
        $queryStr = isset($_REQUEST['q']) ? trim($_REQUEST['q']) : ''; // 检索过滤字符串
        $query = ChargeSpots::find()
            ->select(['id','code_from_compony','logic_addr','cs_name'])
            ->joinWith('chargeStation',false)
            ->where(['is_del'=>0,'cs_is_del'=>0]);
        if($queryStr){ // 检索过滤时
            $total = $query->andWhere([
                'or',
                ['like', 'code_from_compony', $queryStr],
                ['like', 'logic_addr', $queryStr],
                ['like', 'cs_name', $queryStr]
            ])
            ->count();
        }else{ // 默认查询
            $total = $query->count();
        }
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount' => $total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        return json_encode($returnArr);
    }




}