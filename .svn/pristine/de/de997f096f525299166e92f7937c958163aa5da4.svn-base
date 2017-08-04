<?php
/**
 * 按电站查看电桩 控制器
 * time    2016-02-01 14:06
 * @author chengwk
 */
namespace backend\modules\polemonitor\controllers;
use backend\controllers\BaseController;
use yii;
use yii\data\Pagination;
use backend\models\ChargeStation;
use backend\models\ChargeSpots;
use backend\models\ChargeFrontmachine;

class ScanByStationController extends BaseController
{

    /**
     * 获取电站（查询区域的电站combogrid）
     * @$isPageInitStation: 此值为真时表示是初始打开视图页面，此时默认只查询第一个电站；为假时则是正常的获取combogrid下拉列表数据。
     */
    public function actionGetChargeStations($isPageInitStation=false){
        $queryStr = isset($_REQUEST['q']) ? trim($_REQUEST['q']) : ''; // combogrid检索过滤字符串
        $query = ChargeStation::find()
            ->select(['cs_id','cs_code','cs_name','cs_fm_id'])
            ->where(['cs_is_del'=>0])
            ->orderBy('cs_id DESC');
        if(!$isPageInitStation){
            if ($queryStr) { // 检索过滤时
                $total = $query->andWhere([
                    'or',
                    ['like', 'cs_code', $queryStr],
                    ['like', 'cs_name', $queryStr]
                ])
                    ->count();
            } else { // 默认查询
                $total = $query->count();
            }
            $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
            $pages = new Pagination(['totalCount' => $total, 'pageSize' => $pageSize]);
            $data = $query->offset($pages->offset)->limit($pages->limit)->asArray()->all();
            $returnArr = [];
            $returnArr['rows'] = $data;
            $returnArr['total'] = $total;
            return json_encode($returnArr);
        }else{
            // 若是初始打开视图页面，此时默认只查询第一个电站。（为了电桩列表同步默认加载此电站下所有电桩等）
            $pageInitStation = $query->asArray()->one();
            return $pageInitStation;
        }
    }


    /**
     * 查所需的前置机数据库中的配置项(`dicttype`和`dictitem`表)
     */
    private function getConfigs(){
        // 连接前置机数据库并查询出相关数据
        $pageInitStation = $this->actionGetChargeStations(true);
        $fmId = $pageInitStation['cs_fm_id'];
        $connectArr = ChargeFrontmachine::connect($fmId);
        if (!$connectArr[0]) {
            return $connectArr[1];
        }
        $fmConnection = $connectArr[1];
        $dictTypes = [
            '103' => '设备类型',
            '109' => '协议类型',
            '110' => '通道ID',
            '130' => '充电桩枪口类型',
            '131' => '充电桩枪口',
            '116' => '充电桩状态',
            '102' => '充电桩充电类别',
            '115' => '充电桩充电功率',
            '117' => '充电桩厂家',
            '125' => '充电桩充电枪个数',
            '126' => '充电枪标识',
        ];
        $dictTypesIds = array_keys($dictTypes);
        $dictItems = (new \yii\db\Query())
            ->select(['TYPE_ID', 'ITEM_NUM', 'ITEM_NAME'])
            ->from('dictitem')
            ->where(['TYPE_ID' => $dictTypesIds])
            ->orderBy('ORDERS ASC')
            ->all($fmConnection);
        $configs = [];
        foreach ($dictItems as $item) {
            $typeId = $item['TYPE_ID'];
            $k = $item['ITEM_NUM'];
            $v = $item['ITEM_NAME'];
            $configs[$typeId]['dictType'] = $dictTypes[$typeId];
            $configs[$typeId]['dictItem'][$k] = $v;
        }
        return $configs;
    }


    /**
     * 访问视图-按电站查看电桩
     */
    public function actionScanByStation(){
        $pageInitStation = $this->actionGetChargeStations(true);
        $pageInitStationId = $pageInitStation['cs_id'];
        return $this->render('scan-by-station',[
            'buttons'=>$this->getCurrentActionBtn(),
            'pageInitStationId'=>$pageInitStationId,
            'configs'=>$this->getConfigs()
        ]);
    }


    /**
     * 获取某电站内的电桩列表
     */
    public function actionGetList()
    {
        //---1.先查本系统的属于该电站下的电桩---------------
        $stationId = intval(yii::$app->request->get('stationId'));
        $query = ChargeSpots::find()
            ->select(['id','code_from_compony','logic_addr'])
            ->where(['is_del'=>0,'station_id'=>$stationId]);
        // 查询条件
        $query->andFilterWhere(['LIKE','code_from_compony',yii::$app->request->get('code_from_compony')]);
        $query->andFilterWhere(['LIKE','logic_addr',yii::$app->request->get('logic_addr')]);
        $total = $query->count();
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        //排序
        if(yii::$app->request->get('sort')){
            $field = yii::$app->request->get('sort');		//field
            $direction = yii::$app->request->get('order');  //asc or desc
            $orderStr = $field.' '.$direction;
        }else{
            $orderStr = 'id desc';
        }
        $data = $query->offset($pages->offset)->limit($pages->limit)->orderBy($orderStr)->asArray()->all();
        if(!$data){
            echo json_encode(['rows'=>[],'total'=>0,'errInfo'=>'该电站内没有找到任何电桩！']);
            return;
        }
        //---2.连接对应前置机数据库,由所查电桩逻辑地址去查询对应电桩信息------------
        // 根据电站ID查所属前置机。
        $model = ChargeStation::findOne($stationId);
        $fmId = $model->cs_fm_id;
        $logicAddr_arr = array_unique(array_column($data, 'logic_addr'));
        $connectArr = ChargeFrontmachine::connect($fmId);
        if (!$connectArr[0]) {
            return json_encode(['rows'=>[],'total'=>0,'errInfo'=>$connectArr[1]]);
        }
        $fmConnection = $connectArr[1];
        $rows = (new \yii\db\Query())
            ->select([
                'DEV_ID','DEV_NAME', 'DEV_ADDR', 'DEV_TYPE', 'PRTL_TYPE', 'CHN_ID', 'HEART_PERIOD', 'CHARGE_TYPE',
                'SPEAR_COUNT', 'RATED_VOLTAGE', 'RATED_CURRENT', 'MAX_POWER', 'CHARGING_POWER', 'FACTORY', 'SN'
            ])
            ->from('charge_pole')
            ->where(['DEV_ADDR' => $logicAddr_arr])
            ->orderBy('DEV_ID DESC')
            ->indexBy('DEV_ADDR')
            ->all($fmConnection);
        // 合并数据
        foreach ($data as $k => &$ControllerGLitem) {
            if(!$rows){
                break;
            }
            if(isset($rows[$ControllerGLitem['logic_addr']])){
                $ControllerGLitem = array_merge($ControllerGLitem, $rows[$ControllerGLitem['logic_addr']]);
            }
        }
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        return json_encode($returnArr);
    }



}