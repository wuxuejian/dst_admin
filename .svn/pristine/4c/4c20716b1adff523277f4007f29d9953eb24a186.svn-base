<?php
/**
 * 本控制器为各种【combogrid】提供下拉数据
 */
namespace backend\modules\system\controllers;
use backend\controllers\BaseController;
use backend\models\Car;
use backend\models\CarBrand;
use backend\models\ChargeStation;
use yii;
use yii\data\Pagination;

class CombogridController extends BaseController{
    /**
     * 获取【车辆】combogrid
     * system/combogrid/car-list
     */
    public function actionCarList(){
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $query = Car::find()
            ->select([
                '`id`',
                '`plate_number`',
                '`vehicle_dentification_number`',
            ])->andWhere(['{{%car}}.`is_del`'=>0]);

        //检测是否要根据当前登录人员所属运营公司来显示列表数据-20160325
        $isLimitedArr = Car::isLimitedToShowByAdminOperatingCompany(true);
        if(is_array($isLimitedArr) && isset($isLimitedArr['status']) && $isLimitedArr['status']){
            $query->andWhere("{{%car}}.`operating_company_id` in ({$isLimitedArr['adminInfo_operatingCompanyId']})");
        }

        if(yii::$app->request->get('car_id') && !yii::$app->request->get('q')){
            $query->andWhere(['id'=>yii::$app->request->get('car_id')]);
        }
        if(yii::$app->request->get('q')){
            $query->andWhere('`plate_number` like :q or `vehicle_dentification_number` like :q',['q'=>'%'.yii::$app->request->get('q').'%']);
        }
        //排序开始
        $sortColumn = yii::$app->request->get('sort');
        $sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
        $orderBy = '';
        if($sortColumn){
            $orderBy = '`'.$sortColumn.'` ';
        }else{
           $orderBy = '`id` ';
        }
        $orderBy .= $sortType;
        //排序结束
        $total = $query->count();
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)->limit($pages->limit)->orderBy($orderBy)->asArray()->all();
        $returnArr = [];
        $returnArr['rows'] = $data; 
        $returnArr['total'] = $total;
        echo json_encode($returnArr);
    }

    /**
     * 获取【车辆品牌】combogrid
     */
    public function actionCarBrand(){
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $query = CarBrand::find()
            ->select([
                '`id`',
                '`name`',
                '`code`',
            ])
            ->andWhere([
                '`is_del`'=>0,
                'pid'=>0  //只取一级大类品牌
            ]);
        if(yii::$app->request->get('pid') && !yii::$app->request->get('q')){
            $query->andWhere(['id'=>yii::$app->request->get('pid')]);
        }
        if(yii::$app->request->get('q')){
            $query->andWhere(['like','name',yii::$app->request->get('q')]);
        }
        //排序开始
        $sortColumn = yii::$app->request->get('sort');
        $sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
        $orderBy = '';
        if($sortColumn){
            $orderBy = '`'.$sortColumn.'` ';
        }else{
           $orderBy = '`id` ';
        }
        $orderBy .= $sortType;
        //排序结束
        $total = $query->count();
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)->limit($pages->limit)->orderBy($orderBy)->asArray()->all();
        if($data){
            array_unshift($data,['id'=>0,'name'=>'顶级','code'=>'']);
        }else{
            $data = [['id'=>0,'name'=>'顶级','code'=>'']];
        }
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total + 1;
        echo json_encode($returnArr);
    }

    /**
     * 获取充电站
     */
    public function actionChargeStation(){
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $query = ChargeStation::find()
            ->select([
                '`cs_id`',
                '`cs_code`',
                '`cs_name`',
            ])
            ->andWhere([
                '`cs_is_del`'=>0,
            ]);
        if(yii::$app->request->get('cs_id') && !yii::$app->request->get('q')){
            $query->andWhere(['cs_id'=>yii::$app->request->get('cs_id')]);
        }
        if(yii::$app->request->get('q')){
            $query->andWhere(['like','cs_name',yii::$app->request->get('q')]);
        }
        //排序开始
        $sortColumn = yii::$app->request->get('sort');
        $sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
        $orderBy = '';
        if($sortColumn){
            $orderBy = '`'.$sortColumn.'` ';
        }else{
           $orderBy = '`cs_id` ';
        }
        $orderBy .= $sortType;
        //排序结束
        $total = $query->count();
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)
            ->limit($pages->limit)->orderBy($orderBy)->asArray()->all();
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        return json_encode($returnArr);
    }
}