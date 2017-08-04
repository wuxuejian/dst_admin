<?php
/**
 * 本控制器为各种【combogrid】提供下拉数据
 */
namespace backend\modules\car\controllers;
use yii;
use yii\data\Pagination;
use backend\controllers\BaseController;
use backend\models\Battery;
use backend\models\Motor;
use backend\models\MotorMonitor;

class ComboboxController extends BaseController{
    /**
     * 获取【电池型号】combobox
     */
    public function actionGetBatteryModel(){
        $queryStr = isset($_REQUEST['q']) ? trim($_REQUEST['q']) : ''; // 检索过滤字符串
        $query = Battery::find()
            ->select(['id','battery_model'])
            ->where(['is_del'=>0]);
        if($queryStr){
            $query->andWhere(['like','battery_model',$queryStr]);
        }
        $data = $query->asArray()->all();
        return json_encode($data);
    }

    /**
     * 获取【电机型号】combobox
     */
    public function actionGetMotorModel(){
        $queryStr = isset($_REQUEST['q']) ? trim($_REQUEST['q']) : ''; // 检索过滤字符串
        $query = Motor::find()
            ->select(['id','motor_model'])
            ->where(['is_del'=>0]);
        if($queryStr){
            $query->andWhere(['like','motor_model',$queryStr]);
        }
        $data = $query->asArray()->all();
        return json_encode($data);
    }

    /**
     * 获取【电机控制器型号】combobox
     */
    public function actionGetMotorMonitorModel(){
        $queryStr = isset($_REQUEST['q']) ? trim($_REQUEST['q']) : ''; // 检索过滤字符串
        $query = MotorMonitor::find()
            ->select(['id','motor_monitor_model'])
            ->where(['is_del'=>0]);
        if($queryStr){
            $query->andWhere(['like','motor_monitor_model',$queryStr]);
        }
        $data = $query->asArray()->all();
        return json_encode($data);
    }


}