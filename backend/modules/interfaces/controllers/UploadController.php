<?php
/**
 * 上传控制器(头像，驾照等)
 */
namespace backend\modules\interfaces\controllers;
use backend\models\Vip;
use backend\models\VipUpload;
use backend\classes\MyUploadFile;
use yii;

class UploadController extends BaseController{
	
	/**
	 *  app会员上传
     */
    public function actionUpload(){
        $datas = [];
        $_mobile  = isset($_REQUEST['mobile'])  ? trim($_REQUEST['mobile']) : '';	 // 手机号
        $_fileFor = isset($_REQUEST['filefor'])  ? trim($_REQUEST['filefor']) : '';  // 上传文件干什么的，如头像，驾照等，方便建立子目录。

        $vip_id = Vip::getVipIdByPhoneNumber($_mobile);

        // 判断超全局数组$_FILES['yourCustomName']能否接收到上传的文件。
        if (!isset($_FILES['myFile'])){
            $datas['error'] = 1;
            $datas['msg'] = '程序有错误：上传表单中file类型input框的name值与$_FILES接收时使用的不一致！';
            return json_encode($datas);
        }
		
		// 具体处理上传文件
		$filePrefix = 'vip' . $vip_id . '_';
        $res = (new MyUploadFile())->handleUploadFile($_FILES['myFile'],$_fileFor,$filePrefix);
		if($res['error']){
            $datas['error'] = 1;
            $datas['msg'] = $res['msg'];
		}else{
            $datas['error'] = 0;
            $datas['msg'] = '上传文件成功！';
            $baseDir = yii::$app->urlManager->createAbsoluteUrl(['interfaces/upload/upload']);
            $baseDir = dirname(explode('.php',$baseDir)[0]);
            $datas['filePath'] = $baseDir.$res['filePath']; // 返回图片完整路径
			
			// 再新增或更新上传文件存储路径等信息到数据库
            $model = VipUpload::find()
                ->where([
                    'vip_id'=>$vip_id,
                    'sub_type'=>$res['subType'],
                    'main_type'=>$res['mainType']
                ])
                ->one();
            if (!$model){
                $model = new VipUpload();
            }
            $model->main_type = $res['mainType'];
            $model->sub_type = $res['subType'];
            $model->file_path = $res['filePath'];
            $model->vip_id = $vip_id;
            $model->upload_time = time();
            if (!$model->save()){
                $datas['error'] = 1;
                $datas['msg'] = '上传文件保存时出错！';
            }
		}
        return json_encode($datas);
    }
	
	
}