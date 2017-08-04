<?php
/**
 * 本控制器为各种【combogrid】提供下拉数据
 */
namespace backend\modules\charge\controllers;
use yii;
use yii\data\Pagination;
use backend\controllers\BaseController;
use backend\models\ChargeStation;

class CombogridController extends BaseController{

    /**
     * 获取充电站combogrid
     */
    public function actionGetStation()
    {
        $queryStr = isset($_REQUEST['q']) ? trim($_REQUEST['q']) : ''; // 检索过滤字符串
        $stationId = isset($_REQUEST['stationId']) ? intval($_REQUEST['stationId']) : 0; //修改时赋值用
        $query = ChargeStation::find()
            ->select(['cs_id','cs_code','cs_name','cs_address'])
            ->where(['cs_is_del'=>0]);
        if($stationId){
            // 修改时查询赋值
            $total = $query->andWhere(['cs_id'=>$stationId])->count();
        }elseif($queryStr){
            // 检索过滤时
            $total = $query->andWhere([
                'or',
                ['like', 'cs_code', $queryStr],
                ['like', 'cs_name', $queryStr],
                ['like', 'cs_address', $queryStr]
            ])->count();
        }else{
            // 默认查询
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