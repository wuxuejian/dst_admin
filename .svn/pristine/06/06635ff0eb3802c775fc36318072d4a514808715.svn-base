<?php
/**
 * 试用车辆基本信息管理 控制器
 * @author chengwk
 * @date   2015-12-10
 */
namespace backend\modules\car\controllers;
use backend\controllers\BaseController;
use backend\models\Car;
use backend\models\ConfigCategory;
use backend\models\CarFault;
use backend\models\CarDrivingLicense;
use backend\models\CarRoadTransportCertificate;
use backend\models\CarSecondMaintenance;
use backend\models\CarInsuranceCompulsory;
use backend\models\CarInsuranceBusiness;
use backend\classes\UserLog;
use common\models\Excel;
use common\models\File;
use yii;
use yii\base\Object;
use yii\data\Pagination;

class TrialCarController extends BaseController
{
    public function actionIndex()
    {
        //获取本页按钮
        $buttons = $this->getCurrentActionBtn(); 
        //获取配置数据
        $configItems = ['trial_car_status','car_type','use_nature','car_brand','car_color'];
        $config = (new ConfigCategory)->getCategoryConfig($configItems,'value');
        return $this->render('index',[
            'buttons'=>$buttons,
            'config'=>$config
        ]);
    }
	

}