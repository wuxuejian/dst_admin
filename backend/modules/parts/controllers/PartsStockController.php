<?php
namespace backend\modules\parts\controllers;
use backend\controllers\BaseController;
use backend\models\PurchaseOrderMain;
use backend\models\PurchaseExpress;

use backend\modules\parts\models\PartsInstockModel;
use backend\modules\parts\models\PartsInfoModel;
use backend\modules\parts\models\PartsStockModel;


use backend\models\Owner;
use backend\models\OperatingCompany;
use backend\models\Battery;
use backend\models\Motor;
use backend\models\MotorMonitor;
use backend\classes\UserLog;
use common\models\Excel;
use common\models\File;
use yii;
use yii\base\Object;
use yii\data\Pagination;
use backend\models\Admin;
use common\classes\Category;
use backend\models\CarType;

class PartsStockController extends BaseController
{
    public function actionIndex()
    {
        $buttons = $this->getCurrentActionBtn();
        $searchFormOptions['company'] = PartsInstockModel::company();
		return $this->render('index',[
            'buttons'=>$buttons,
            'searchFormOptions'=>$searchFormOptions
        ]);
	}
	public function actionGetList()
	{
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $get_data = $_GET;
//        var_dump($get_data);die;
        $data = PartsStockModel::search($get_data,$pageSize);
        return $data;
	}
}