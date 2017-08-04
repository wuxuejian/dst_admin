<?php
/**
 * @Desc:	管理会员的车辆控制器 
 * @author: chengwk
 * @date:	2015-10-23
 */
namespace backend\modules\vip\controllers;
use backend\controllers\BaseController;
use yii;
use yii\base\Object;
use yii\data\Pagination;
use backend\models\Vehicle;
class VehicleController extends BaseController
{
    
    /**
     * 获取某会员的车辆列表
     */
    public function actionGetVehicleByVipId()
    {
        $vipId = isset($_REQUEST['vipId']) && $_REQUEST['vipId'] ? $_REQUEST['vipId'] : 0; 
		if(!$vipId){
			echo json_encode(['rows'=>[],'total'=>0]);
			return;
		}	
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $query = Vehicle::find()->where(['vip_id'=>$vipId,'is_del'=>0]);
        $total = $query->count();
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
		//排序
		if(yii::$app->request->get('sort')){
			$field = yii::$app->request->get('sort');		//field
			$direction = yii::$app->request->get('order');  //asc or desc
			$orderStr = $field.' '.$direction;
		}else{
			$orderStr = 'id ASC';
		}		
        $data = $query->offset($pages->offset)->limit($pages->limit)->orderBy($orderStr)->asArray()->all();
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        echo json_encode($returnArr);
    }
    
}