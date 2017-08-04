<?php
/**
 * @Description: App操作指南 控制器
 * @author: chengwk
 * @create:	2016-04-05 10:04:15
 */
namespace backend\modules\interfaces\controllers;
use backend\models\VipGuide;
use yii;
use yii\web\Controller;

class GuideController extends Controller
{
    public $layout = false;
    public $enableCsrfValidation = false;
    /**
     * 获取APP操作指南
     * guide_get-guide
     */
    public function actionGetGuide()
    {
        $vipGuide = VipGuide::find()->where(['id'=>1])->asArray()->one();
        if(!$vipGuide){
            return '未找到记录！';
        }
        return $this->render('index',[
            'vipGuideInfo'=>$vipGuide
        ]);
    }

    /**
     * 获取URL
     * interfaces/guide/get-guide-url
     * guide_get-guide-url
     */
    public function actionGetGuideUrl(){
        $datas['error'] = 0;
        $datas['data'] = yii::$app->urlManager->createAbsoluteUrl(['interfaces/guide/get-guide']);
        return json_encode($datas);
    }

}