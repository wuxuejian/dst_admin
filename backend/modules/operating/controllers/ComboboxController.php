<?php
/**
 * 本控制器为各种【Combobox】提供下拉数据
 */
namespace backend\modules\operating\controllers;
use backend\models\CarBrand;

use backend\models\CarType;

use yii;
use backend\controllers\BaseController;
use backend\models\OperatingCompany;
use common\classes\Category;

class ComboboxController extends BaseController{

    /**
     * 获取【车辆运营公司】Combobox
     */
    public function actionGetOperatingCompany(){
        $query = OperatingCompany::find()
            ->select(['id','pid','text'=>'name'])
            ->where(['`is_del`'=>0]);
        $rows = $query->asArray()->all();
//         $nodes = [];
//         if(!empty($rows)){
//             $nodes = Category::unlimitedForLayer($rows,'pid');
//         }
//         //print_r($nodes);exit;
//         //判断是否需要显示顶级根节点
//         $isShowRoot = intval(yii::$app->request->get('isShowRoot'));
//         if($isShowRoot){
//             $data = [['id'=>0,'text'=>'顶级','iconCls'=>'icon-filter','children'=>$nodes]];
//         }else{
//             //判断是否需要显示“不限”子选项（一般在搜索域需要显示，而在填写增改信息时不要）
//             $isShowNotLimitOption = intval(yii::$app->request->get('isShowNotLimitOption'));
//             if($isShowNotLimitOption){
//                 array_unshift($nodes,['id'=>'','pid'=>'','text'=>'--不限--']);
//             }
//             $data = $nodes;
//         }
        return json_encode($rows);
    }
    
    /**
     * 获取【车型型号】Combobox
     */
    public function actionGetCarType(){
    	$query = CarType::find()
	    	->select(['id','text'=>'car_model_name'])
	    	->where(['`is_del`'=>0]);
    	$brand_id = yii::$app->request->get('brand_id'); 
    	if($brand_id){
    		$brand_query = CarBrand::find()
		    			->select(['id'])
		    			->where("is_del=0 and (id={$brand_id} or pid={$brand_id})");
    		
    		$brands = $brand_query->asArray()->all();
    		$brand_ids = array();
    		foreach ($brands as $row){
    			array_push($brand_ids, $row['id']);
    		}
    		$query->andFilterWhere(['in','brand_id',$brand_ids]);
//     		exit($query->createCommand()->getRawSql());
    	}
    	
    	$rows = $query->asArray()->all();
    	
    	return json_encode($rows);
    }


}