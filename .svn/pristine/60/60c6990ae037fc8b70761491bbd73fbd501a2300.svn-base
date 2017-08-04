<?php
/**
 * @Description: App操作指南 控制器
 * @author: chengwk
 * @create:	2016-04-05 10:04:15
 */
namespace backend\modules\vip\controllers;
use backend\controllers\BaseController;
use yii;
use backend\models\VipGuide;

class VipGuideController extends BaseController
{
    public function actionIndex()
    {
		$buttons = $this->getCurrentActionBtn();
        $vipGuide = VipGuide::find()->where(['id'=>1])->asArray()->one();
        if(!$vipGuide){
            return '未找到记录！';
        }
        return $this->render('index',[
            'buttons'=>$buttons,
            'vipGuideInfo'=>$vipGuide
        ]);
    }

    /**
     * 保存
     */
    public function actionSave(){
        if(yii::$app->request->isPost){
            $data = yii::$app->request->post();
            $model = VipGuide::findOne(1);
            if(!$model){
                return json_encode(['status'=>false,'info'=>'未找到记录！']);
            }
            $model->content = $data['content'];
            $model->last_modify_datetime = date('Y-m-d H:i:s');
            $model->last_modify_aid = $_SESSION['backend']['adminInfo']['id'];
            if($model->save(true)){
                $returnArr['status'] = true;
                $returnArr['info'] = '修改成功！';
            }else{
                $returnArr['status'] = false;
                $error = $model->getErrors();
                if($error){
                    $returnArr['info'] = join('',array_column($error,0));
                }else{
                    $returnArr['info'] = '未知错误';
                }
            }
            return json_encode($returnArr);
        }
    }

}