<?php
/**
 * 客户分类管理控制器
 * time: 2017/07/26 15:15
 * @author pengyl
 */
namespace backend\modules\customer\controllers;
use backend\controllers\BaseController;
use backend\models\CustomerClassify;
use yii;
use yii\data\Pagination;

class ClassifyController extends BaseController
{
    public function actionIndex()
    {
        $buttons = $this->getCurrentActionBtn();
        return $this->render('index',[
            'buttons'=>$buttons
        ]);
    }

    /**
     * 获取客户分类列表
     */
    public function actionGetListAll()
    {
        $pid = yii::$app->request->get('pid');
        if(!$pid){
        	$pid = 0;
        }
        $query = CustomerClassify::find()
            ->select([
                '{{%customer_classify}}.id',
            	'{{%customer_classify}}.name'
            ])
            ->where(['{{%customer_classify}}.pid'=>$pid]);
//         exit($query->createCommand()->sql);
		$data = $query->asArray()->all();
		array_unshift($data,['id'=>0,'name'=>'']);
        echo json_encode($data);
    }
}