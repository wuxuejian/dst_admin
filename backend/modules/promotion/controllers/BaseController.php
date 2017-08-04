<?php
/**
 * 基类控制器
 */
namespace backend\modules\promotion\controllers;
use yii;
use yii\web\Controller;

class BaseController extends Controller{
	
	public $layout = false;
	public $enableCsrfValidation = false;
	
	/*
	 * 初始化方法
	 */
    public function init(){
        parent::init();
		return true;
    }


}