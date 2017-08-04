<?php
/**
 * 【电能】控制器
 * time    2016/01/08 11:10
 * @author chengwk
 */
namespace backend\modules\polemonitor\controllers;
use backend\controllers\BaseController;
use yii;
use yii\data\Pagination;
use backend\models\ChargeFrontmachine;

class PowerController extends BaseController{

    /**
     * 访问‘总电能示值监控’窗口视图
     */
    public function actionMonitorTotalPower(){
        $fmId = isset($_GET['fmId']) ? $_GET['fmId'] : 0;    // 当前前置机id
        $devId = isset($_GET['devId']) ? $_GET['devId'] : 0; // 当前充电设备id
        return $this->render('monitorTotalPowerWin', [
            'initDatas' => [
                'devId' => $devId,
                'fmId' => $fmId
            ]
        ]);
    }

    /**
     * 在‘总电能示值监控’窗口里获取数据列表
     */
    public function actionMonitorTotalPowerGetList()
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
            ->select(['dd.*'])
            ->from('dev_dl dd')
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
            $orderStr = 'TIME_TAG desc';
        }
        $rows = $query->orderBy($orderStr)->offset($pages->offset)->limit($pages->limit)->all($fmConnection);
        $returnArr['rows'] = $rows;
        $returnArr['total'] = $total;
        return json_encode($returnArr);
    }



}