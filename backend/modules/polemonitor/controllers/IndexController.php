<?php
/**
 * 电桩监控入口控制器
 * time    2015/12/28 16：32
 * @author wangmin
 */
namespace backend\modules\polemonitor\controllers;
use backend\controllers\BaseController;
use yii;
use yii\data\Pagination;
use backend\models\ChargeSpots;
use backend\models\ChargeFrontmachine;

class IndexController extends BaseController
{
    /**
     * 获取前置机数据库中的一些配置项(`dicttype`和`dictitem`表)
     */
    private function getConfigs(){
        $fmId = ChargeFrontmachine::getDefaultFrontMachineId();
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
     * 访问‘按前置机查看电桩’视图
     */
    public function actionScanByFrontMachine(){

        return $this->render('scan-by-front-machine',[
            'buttons'=>$this->getCurrentActionBtn(),
            'defaultFrontMachineId'=>ChargeFrontmachine::getDefaultFrontMachineId(),
            'configs'=>$this->getConfigs()
        ]);
    }


    /**
     * 获取查某个前置机下的电桩列表
     */
    public function actionGetList(){
        //---1.先查本系统中属于要查询的前置机下的电桩---------------
        $fmId = intval(yii::$app->request->get('front_machine_id'));
        if (!$fmId) {
            $fmId = ChargeFrontmachine::getDefaultFrontMachineId();
        }
        $query = ChargeSpots::find()
            ->select([
                'id','code_from_compony','fm_id','logic_addr'
            ])
            ->where(['is_del'=>0]);
        // 查询条件
        $query->andFilterWhere(['fm_id'=>$fmId]);
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
            return json_encode(['rows'=>[],'total'=>0,'errInfo'=>'没有找到任何电桩！']);
        }
        //---2.连接对应前置机数据库,由所查电桩逻辑地址去查询对应电桩信息------------
        $logicAddr_arr = array_column($data, 'logic_addr');
        // 连接前置机数据库并查询出相关数据
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