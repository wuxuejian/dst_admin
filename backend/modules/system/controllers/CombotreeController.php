<?php
/**
 * 本控制器为各种【combotree】提供下拉数据
 */
namespace backend\modules\system\controllers;
use yii;
use backend\controllers\BaseController;
use backend\models\SystemMenu;
use common\classes\Category;

class CombotreeController extends BaseController{

    /**
     * 获取所有菜单 combotree
     */
    public function actionGetMenus(){
        $query = SystemMenu::find()
            ->select([
                'id','pid',
                'text'=>'name'
            ])
            ->where(['`is_del`'=>0]);
        $rows = $query->orderBy('`list_order` DESC,`id` ASC')->asArray()->all();

        if(!empty($rows)){
            $nodes = Category::unlimitedForLayer($rows,'pid');
        }

        //判断是否需要显示顶级根节点
        $isShowRoot = intval(yii::$app->request->get('isShowRoot'));
        if($isShowRoot){
            if(!empty($nodes)){
                $data = [['id'=>0,'text'=>'作为一级菜单','iconCls'=>'icon-filter','children'=>$nodes]];
            }else{
                $data = [['id'=>0,'text'=>'作为一级菜单','iconCls'=>'icon-filter','children'=>[]]];
            }
        }
        return json_encode($data);
    }


}