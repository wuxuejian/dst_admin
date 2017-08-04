<?php
/**
 * 车辆异常报警控制器
 * time    2015/11/16 11:11
 * @author wangmin
 */
namespace backend\modules\carmonitor\controllers;
use backend\controllers\BaseController;
use backend\models\Car;
use backend\models\TcpCarException;
use yii;
use yii\data\Pagination;
class AlertController extends BaseController
{
    public function actionIndex()
    {
        $buttons = $this->getCurrentActionBtn();
        return $this->render('index',[
            'buttons'=>$buttons
        ]);
    }

    /**
     * 获取车辆异常列表
     */
    public function actionGetList()
    {
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $query = TcpCarException::find();
        //查询条件
        $query->andFilterWhere([
            'like',
            '`car_vin`',
            yii::$app->request->get('car_vin')
        ]);
        //查询条件结束
        //排序开始
        $sortColumn = yii::$app->request->get('sort');
        $sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
        $orderBy = '';
        if($sortColumn){
            switch ($sortColumn) {
                default:
                    $orderBy = '`'.$sortColumn.'` ';
                    break;
            }
        }else{
           $orderBy = '`collection_datetime` ';
        }
        $orderBy .= $sortType;
        //排序结束
        $total = $query->count();
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)->limit($pages->limit)
                ->orderBy($orderBy)->asArray()->all();
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        return json_encode($returnArr);
    }
}