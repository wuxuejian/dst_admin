<?php
namespace backend\modules\car\controllers;
use backend\controllers\BaseController;
use yii;
use yii\base\Object;
use yii\web\UploadedFile;
use backend\models\CarAttachment;
/**
 * 汽车附件控制器
 * @author wamgin
 * 
 */
class AttachmentController extends BaseController
{
    /**
     * 指定汽车附件首页
     */
    public function actionIndexSingle()
    {
        $carId = yii::$app->request->get('carId');
        return $this->render('index',[
            'carId'=>$carId
        ]);
    }
    
    /**
     * 获取指定汽车附件列表
     */
    public function actionGetListSingle()
    {
        $carId = yii::$app->request->get('carId');
        $returnArr = [];
        $returnArr['rows'] = [];
        $returnArr['total'] = 0;
        echo json_encode($returnArr);
    }
    
    /**
     * 上传附件
     */
    public function actionAdd()
    {
        //data submit start
        if(yii::$app->request->isPost){
            $model = new CarAttachment();
            $model->setScenario('add');
            $model->attachment = UploadedFile::getInstance($model,'attachment');
            $model->name = yii::$app->request->post('name');
            $model->car_id = yii::$app->request->post('name');
            $model->upload_time = time();
            $returnArr = [];
            $returnArr['name'] = $model->attachment->baseName;
            if($model->validate()){
                $baseName = './car_attachment/'.date('Ymd');
                isdir($baseName) or mkdir($baseName);
                $model->file->saveAs($baseName . '/' . $model->file->baseName . '.' . $model->file->extension);
                if($model->save(false,$saveAttributes)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '添加成功';
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '数据保存失败';
                }
            }else{
                $returnArr['status'] = false;
                $error = $model->getErrors();
                if($error){
                    $errorStr = '';
                    foreach($error as $val){
                        $errorStr .= $val[0].'&';
                    }
                    $returnArr['info'] = rtrim($errorStr,'&');
                }else{
                    $returnArr['info'] = '未知错误';
                }
            }
            echo '<script>window.parent.JqueryAjaxFileUpload.uploadProgress('.json_encode($returnArr).');</script>';;
            return null;
        }
        //data submite end
        $carId = yii::$app->request->get('carId') or die('param carId is required');
        return $this->render('add',[
            'carId'=>$carId
        ]);
    }
}