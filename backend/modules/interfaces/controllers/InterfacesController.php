<?php
/**
 * @Desc:	百度地图接口控制器 
 * @author: chengwk
 * @date:	2015-10-27
 */
namespace backend\modules\interfaces\controllers;
use yii;
use yii\web\Controller;

class InterfacesController extends Controller
{

    /**
     * 在百度地图上查找经纬度/地址（电桩、个人/企业客户详情弹窗里）
     */
    public function actionSearchBaiduMap(){	
		// 参数pageIn用以区分在哪个页面打开的百度地图
		$datas['pageIn'] = isset($_REQUEST['pageIn']) ? $_REQUEST['pageIn'] : ''; 
		return $this->render('searchBaiduMapWin',$datas);
	}

	
	
}