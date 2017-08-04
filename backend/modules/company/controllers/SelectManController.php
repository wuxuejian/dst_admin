<?php

namespace frontend\modules\company\controllers;

use yii;
use yii\data\Pagination;
use backend\classes\SelectMade;
use common\models\BusCompany;
use common\models\Department;
use common\models\EcsOrderInfo;
use frontend\modules\company\models\SelectMan;

/**
 * @name 商户人员选择控制器
 * @author tanbenjiagn
 * @date 2015-8-21
 *
 */
class SelectManController extends \frontend\controllers\BaseController
{
    public function actionIndex()
    {
    	// 获取查询条件
    	$searchData = Yii::$app->request->get('searchData');
    	
    	// 获取商户ID
    	$bossId = Yii::$app->request->get('bossId');
    	$bossId = isset($bossId) ? $bossId : '';
    	
    	// 获取面板ID（搜索需要）
		$winId = Yii::$app->request->get('winId');
    	
    	// 获取对应商户信息
    	$bossInfo = (new SelectMan())->getBossInfo($bossId,$searchData);
    	
		// 获取商户对应部门
		$department = (new SelectMan())->getDepartment($bossId);
		
		// 获取商户下对应用户
		$user = (new SelectMan())->getUser($bossId);
		
		// 获取商户下所有角色
		$role = (new SelectMan())->getRole($bossId);
		
        return $this->renderPartial('index', [
        		'bossId' => $bossId,
        		'winId' => $winId,
        		'bossInfo' => $bossInfo,
        		'department' => $department,
        		'user' => $user,
        		'role' => $role       		
        ]);
    }
   
    
    /**
     * @name 测试
     */
    public function actionTest()
    {
    	return $this->renderPartial('test');
    }
}
