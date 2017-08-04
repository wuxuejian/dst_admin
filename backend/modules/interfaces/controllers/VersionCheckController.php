<?php
/**
 * APP版本检测 控制器
 */
namespace backend\modules\interfaces\controllers;
use yii;
use yii\web\Controller;
class VersionCheckController extends Controller
{
    public $layout = false;
    public $enableCsrfValidation = false;
    /**
     * 版本检测
     * version-check_check
     */
    public function actionCheck(){
        $returnArr = [
            'error'=>1,
            'msg'=>'',
            'data'=>[],
        ];
        $version = isset($_REQUEST['version']) ? trim($_REQUEST['version']) : '';
        if(!$version){
            $returnArr['msg'] = '参数错误！';
            return json_encode($returnArr);
        }
		//从配置文件中获取app版本信息
		$filename = 'appVersionCheck.txt';
		if(!file_exists($filename)){
            $returnArr['msg'] = 'APP版本配置文件未找到！';
            return json_encode($returnArr);
		}
		$fileContent = trim(file_get_contents($filename)); 
		if(!$fileContent){
            $returnArr['msg'] = 'APP版本配置文件内容不能为空！';
            return json_encode($returnArr);
		}
		$configInfo = explode('|',$fileContent);

		if(preg_match('/android/i',$version))
		{
			$configVersion = $configInfo[0];
			unset($configInfo[0]);
			unset($configInfo[2]);
			unset($configInfo[3]);
			$configContent = join('\n',$configInfo);
			$versionInfo = [
			'version'=>$configVersion,
			'path'=>'http://120.25.209.72/dst.apk',
			'update_content'=>$configContent,
			];
		}else{
			$configVersion = $configInfo[2];
			unset($configInfo[0]);
			unset($configInfo[1]);
			unset($configInfo[2]);
			$configContent = join('\n',$configInfo);
			$versionInfo = [
			'version'=>$configVersion,
			'path'=>'http://120.25.209.72/dst.ipa',
			'update_content'=>$configContent,
			];
		}
		
		
        if($version != $versionInfo['version']){
            $returnArr['error'] = 0;
            $returnArr['data']['has_new_ver'] = true;
            $returnArr['data'] = array_merge($returnArr['data'],$versionInfo);
        }else{
            $returnArr['error'] = 0;
            $returnArr['data']['has_new_ver'] = false;
            $returnArr['msg'] = '当前已是最新版本！';
        }
        return json_encode($returnArr);
    }
} 