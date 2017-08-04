<?php
/**
 * 配置控制器
 */
namespace backend\modules\interfaces\controllers;
use yii;
use yii\web\Controller;
use backend\models\ConfigCategory;

class ConfigsController extends Controller{
    public $layout = false;
    public $enableCsrfValidation = false;
	/**
	 *	获取某些页面所需的配置
	 * 	@$_configsfor: string，英文逗号分隔的所需配置项
	 */
	public function actionGetConfigs($_configsfor=''){
		$datas = [];
		if(!isset($_configsfor) || !$_configsfor){
			$_configsfor = isset($_REQUEST['configsfor']) ? trim($_REQUEST['configsfor']) : '';  // 所需配置，string
		}	
        if (!$_configsfor){
            $datas['error'] = 1;
            $datas['msg'] = "没有指定所需的配置！";
            $datas['errline'] = __LINE__;
            echo json_encode($datas); exit;
        }
        $configItems = explode(',',$_configsfor);
		$configs = (new ConfigCategory())->getCategoryConfig($configItems,'value');
		if (empty($configs)){
			$datas['error'] = 1;
			$datas['msg'] = "查找不到指定的配置！";
			$datas['errline'] = __LINE__;
		}else{
			foreach($configs as $key=>$config){
				$arr = [];
				foreach($config as $item){
					$tmp = [];
					$tmp['value'] =  $item['value'];
					$tmp['text']  =  $item['text'];
					$arr[] = $tmp;
				}
				$configs[$key] = $arr;
			}
			$datas['error'] = 0;
			$datas['msg'] = "获取配置成功！";
			$datas['data'] = $configs;
		}
		echo json_encode($datas); exit;
	}

	
}