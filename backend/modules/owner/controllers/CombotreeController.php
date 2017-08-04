<?php
/**
 * 本控制器为各种【combotree】提供下拉数据
 */
namespace backend\modules\owner\controllers;
use yii;
use backend\controllers\BaseController;
use backend\models\Owner;
use common\classes\Category;

class CombotreeController extends BaseController{

    /**
     * 获取【机动车所有人】combotree
     */
    public function actionGetOwners(){
        $query = Owner::find()
            ->select(['id','pid','text'=>'name'])
            ->where(['`is_del`'=>0]);
        $rows = $query->asArray()->all();
        $nodes = [];
        if($rows){
            $nodes = Category::unlimitedForLayer($rows,'pid');
        }
        //print_r($nodes);exit;
        //判断是否需要显示顶级根节点
        $isShowRoot = intval(yii::$app->request->get('isShowRoot'));
        if($isShowRoot){
            $data = [['id'=>0,'text'=>'顶级','iconCls'=>'icon-filter','children'=>$nodes]];
        }else{
            //判断是否需要显示“不限”子选项（一般在搜索域需要显示，而在填写增改信息时不要）
            $isShowNotLimitOption = intval(yii::$app->request->get('isShowNotLimitOption'));
            if($isShowNotLimitOption){
                array_unshift($nodes,['id'=>'','pid'=>'','text'=>'--不限--']);
            }
            $data = $nodes;
        }
        return json_encode($data);
    }


}