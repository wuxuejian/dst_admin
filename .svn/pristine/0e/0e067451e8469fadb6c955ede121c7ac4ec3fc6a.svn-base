<?php
/**
 * 直流侧瞬时量 控制器
 * time 2015-12-31 16:30
 * @author chengwk
 */
namespace backend\modules\polemonitor\controllers;
use backend\controllers\BaseController;
use yii;
use yii\data\Pagination;
use backend\models\ChargeSpots;
use backend\models\ChargeFrontmachine;

class DcRtvController extends BaseController
{
    /**
     * 访问‘查看直流侧瞬时量’视图
     */
    public function actionIndex(){
        return $this->render('index',[
            'buttons'=>[],
            'defaultChargerId'=>ChargeSpots::getDefaultChargerId('DC')  // 仅查直流桩
        ]);
    }

    /**
     * 获取某个直流电桩侧瞬时量列表
     */
    public function actionGetList(){
		// 以要查询的电桩ID去查出对应前置机id和逻辑地址
        $chargerId = intval(yii::$app->request->get('chargerId'));
        if (!$chargerId) {
            $chargerId = ChargeSpots::getDefaultChargerId('DC');  // 仅查直流桩
        }
		$charger = ChargeSpots::find()
			->select(['id','fm_id','logic_addr'])
			->where(['id'=>$chargerId,'is_del'=>0])
			->asArray()->one();
        if (empty($charger)) {
            return json_encode(['rows'=>[],'total'=>0,'errInfo'=>'找不到对应的电桩！']);
        }
        // 连接对应前置机数据库,并查询直流侧瞬时量记录
        $logic_addr = $charger['logic_addr'];
        $fm_id = $charger['fm_id'];
        $connectArr = ChargeFrontmachine::connect($fm_id);
        if (!$connectArr[0]) {
            return json_encode(['rows'=>[],'total'=>0,'errInfo'=>$connectArr[1]]);
        }
        $fmConnection = $connectArr[1];
        //由逻辑地址关联电桩的设备ID，查询直流侧瞬时量记录
        $query = (new \yii\db\Query())
            ->select([
                'dr.*',
                'cp.DEV_ADDR'
            ])
            ->from('dc_rtv dr')
            ->join('LEFT JOIN', 'charge_pole cp', 'cp.DEV_ID=dr.DEV_ID')
            ->where(['cp.DEV_ADDR' => $logic_addr]);
        $total = $query->count('dr.DEV_ID', $fmConnection);
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
        $acRtvRecords = $query->orderBy($orderStr)->offset($pages->offset)->limit($pages->limit)->all($fmConnection);
        $returnArr['rows'] = $acRtvRecords;
        $returnArr['total'] = $total;
        return json_encode($returnArr);
    }



}