<?php
/**
 * 【电桩】控制器
 * time    2016/01/08 11:10
 * @author chengwk
 */
namespace backend\modules\polemonitor\controllers;
use backend\controllers\BaseController;
use yii;
use yii\data\Pagination;
use backend\models\ChargeFrontmachine;

class PoleController extends BaseController{

    /**
     * 访问‘电桩监控’窗口视图
     */
    public function actionMonitorChargePole(){
        $fmId = isset($_GET['fmId']) ? $_GET['fmId'] : 0;    // 当前前置机id
        $devId = isset($_GET['devId']) ? $_GET['devId'] : 0; // 当前充电设备id
        return $this->render('monitorChargePoleWin', [
            'initDatas' => [
                'devId' => $devId,
                'fmId' => $fmId
            ]
        ]);
    }

    /**
     * 在‘电桩监控’窗口里获取数据列表
     */
    public function actionMonitorChargePoleGetList()
    {
        $fmId = isset($_GET['fmId']) ? $_GET['fmId'] : 0;    // 当前前置机id
        $devId = isset($_GET['devId']) ? $_GET['devId'] : 0; // 当前充电设备id
        if (!$devId || !$fmId) {
            return json_encode(['rows'=>[],'total'=>0,'errInfo'=>'没有传递前置机id或设备id！']);
        }
        // 连接前置机数据库并查询出相关数据
        $connectArr = ChargeFrontmachine::connect($fmId);
        if (!$connectArr[0]) {
            return json_encode(['rows'=>[],'total'=>0,'errInfo'=>$connectArr[1]]);
        }
        $fmConnection = $connectArr[1];
        // 只查与该设备相匹配的最新监控数据
        $query = (new \yii\db\Query())
            ->select(['cmd.*'])
            ->from('cp_moni_data cmd')
            ->where(['DEV_ID'=>$devId]);
        $total = $query->count('DEV_ID', $fmConnection);
        // 分页
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount' => $total, 'pageSize' => $pageSize]);
        //排序
        if(yii::$app->request->get('sort')){
            $field = yii::$app->request->get('sort');		//field
            $direction = yii::$app->request->get('order');  //asc or desc
            $orderStr = $field.' '.$direction;
        }else{
            $orderStr = 'STU_TIME_TAG desc';
        }
        $rows = $query->orderBy($orderStr)->offset($pages->offset)->limit($pages->limit)->all($fmConnection);
        $returnArr['rows'] = $rows;
        $returnArr['total'] = $total;
        return json_encode($returnArr);
    }


}