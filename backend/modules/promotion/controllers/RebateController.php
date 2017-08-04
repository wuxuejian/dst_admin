<?php
/**
 * @Desc: 微信推广活动->微信公众号->推广返利 控制器
 * @date:	2016-03-08
 */
namespace backend\modules\promotion\controllers;
use yii;
use yii\data\Pagination;
use backend\models\VipPromotionSign;


class RebateController extends BaseController{

    /*
     * 访问“推广返利”视图
     */
	public function actionIndex(){
        return $this->render('index');
	}



}