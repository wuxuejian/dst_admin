<?php
/**
 * 【电表】控制器
 * time    2016/01/08 11:10
 * @author chengwk
 */
namespace backend\modules\polemonitor\controllers;
use backend\controllers\BaseController;
use yii;
use yii\data\Pagination;
use backend\models\ChargeFrontmachine;

class MeterController extends BaseController{

    /**
     * 访问‘电表监控’窗口视图
     */
    public function actionMonitorMeter(){
        $fmId = isset($_GET['fmId']) ? $_GET['fmId'] : 0;    // 当前前置机id
        $devId = isset($_GET['devId']) ? $_GET['devId'] : 0; // 当前充电设备id
        return $this->render('monitorMeterWin', [
            'initDatas' => [
                'devId' => $devId,
                'fmId' => $fmId
            ]
        ]);
    }

    /**
     * 在‘电表监控’窗口里获取数据列表
     */
    public function actionMonitorMeterGetList()
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
            ->select(['meter.*'])
            ->from('meter')
            ->where(['BLG_DEV_ID'=>$devId]);
        $total = $query->count('METER_ID', $fmConnection);
        // 分页
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount' => $total, 'pageSize' => $pageSize]);
        //排序
        if(yii::$app->request->get('sort')){
            $field = yii::$app->request->get('sort');		//field
            $direction = yii::$app->request->get('order');  //asc or desc
            $orderStr = $field.' '.$direction;
        }else{
            $orderStr = 'METER_ID desc';
        }
        $rows = $query->orderBy($orderStr)->offset($pages->offset)->limit($pages->limit)->all($fmConnection);
        $returnArr['rows'] = $rows;
        $returnArr['total'] = $total;
        return json_encode($returnArr);
    }

    /**
     * 获取【电表电量tab】的数据列表
     */
    public function actionGetMeterElectricityList()
    {
        $fmId = isset($_GET['fmId']) ? $_GET['fmId'] : 0;           // 当前前置机id
        $meterId = isset($_GET['meterId']) ? $_GET['meterId'] : 0;  // 当前电表id
        if (!$meterId || !$fmId) {
            return json_encode(['rows'=>[],'total'=>0,'errInfo'=>'没有传递前置机id或电表id！']);
        }
        // 连接前置机数据库并查询出相关数据
        $connectArr = ChargeFrontmachine::connect($fmId);
        if (!$connectArr[0]) {
            return json_encode(['rows'=>[],'total'=>0,'errInfo'=>$connectArr[1]]);
        }
        $fmConnection = $connectArr[1];
        // 只查与该设备相匹配的最新监控数据
        $query = (new \yii\db\Query())
            ->select(['inspect_dl.*'])
            ->from('inspect_dl')
            ->where(['METER_ID'=>$meterId]);
        $total = $query->count('METER_ID', $fmConnection);
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

    /**
     * 获取【电表瞬时量tab】的数据列表
     */
    public function actionGetMeterInstantaneousFlowList()
    {
        $fmId = isset($_GET['fmId']) ? $_GET['fmId'] : 0;           // 当前前置机id
        $meterId = isset($_GET['meterId']) ? $_GET['meterId'] : 0;  // 当前电表id
        if (!$meterId || !$fmId) {
            return json_encode(['rows'=>[],'total'=>0,'errInfo'=>'没有传递前置机id或电表id！']);
        }
        // 连接前置机数据库并查询出相关数据
        $connectArr = ChargeFrontmachine::connect($fmId);
        if (!$connectArr[0]) {
            return json_encode(['rows'=>[],'total'=>0,'errInfo'=>$connectArr[1]]);
        }
        $fmConnection = $connectArr[1];
        // 只查与该设备相匹配的最新监控数据
        $query = (new \yii\db\Query())
            ->select(['inspect_rtv.*'])
            ->from('inspect_rtv')
            ->where(['METER_ID'=>$meterId]);
        $total = $query->count('METER_ID', $fmConnection);
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

    /**
     * 获取【电表1-31次谐波电压数据】的数据列表
     */
    public function actionGetMeterHarmonicVoltageList()
    {
        $fmId = isset($_GET['fmId']) ? $_GET['fmId'] : 0;           // 当前前置机id
        $meterId = isset($_GET['meterId']) ? $_GET['meterId'] : 0;  // 当前电表id
        if (!$meterId || !$fmId) {
            return json_encode(['rows'=>[],'total'=>0,'errInfo'=>'没有传递前置机id或电表id！']);
        }
        // 连接前置机数据库并查询出相关数据
        $connectArr = ChargeFrontmachine::connect($fmId);
        if (!$connectArr[0]) {
            return json_encode(['rows'=>[],'total'=>0,'errInfo'=>$connectArr[1]]);
        }
        $fmConnection = $connectArr[1];
        // 只查与该设备相匹配的最新监控数据
        $query = (new \yii\db\Query())
            ->select(['harmonic_uv.*'])
            ->from('harmonic_uv')
            ->where(['METER_ID'=>$meterId]);
        $total = $query->count('METER_ID', $fmConnection);
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

    /**
     * 获取【电表1-31次谐波电流数据】的数据列表
     */
    public function actionGetMeterHarmonicCurrentList()
    {
        $fmId = isset($_GET['fmId']) ? $_GET['fmId'] : 0;           // 当前前置机id
        $meterId = isset($_GET['meterId']) ? $_GET['meterId'] : 0;  // 当前电表id
        if (!$meterId || !$fmId) {
            return json_encode(['rows'=>[],'total'=>0,'errInfo'=>'没有传递前置机id或电表id！']);
        }
        // 连接前置机数据库并查询出相关数据
        $connectArr = ChargeFrontmachine::connect($fmId);
        if (!$connectArr[0]) {
            return json_encode(['rows'=>[],'total'=>0,'errInfo'=>$connectArr[1]]);
        }
        $fmConnection = $connectArr[1];
        // 只查与该设备相匹配的最新监控数据
        $query = (new \yii\db\Query())
            ->select(['harmonic_ia.*'])
            ->from('harmonic_ia')
            ->where(['METER_ID'=>$meterId]);
        $total = $query->count('METER_ID', $fmConnection);
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