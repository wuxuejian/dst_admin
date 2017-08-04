<?php
/**
 * 本控制器为各种【combogrid】获取下拉列表数据
 */
namespace backend\modules\promotion\controllers;
use yii;
use yii\data\Pagination;
use backend\controllers\BaseController;
use backend\models\VipPromotionSign;

class CombogridController extends BaseController{

    /**
     * 获取【租车客户】列表
     */
    public function actionGetRentersList(){
        $queryStr = isset($_REQUEST['q']) ? trim($_REQUEST['q']) : ''; // 检索过滤字符串
        $renterId = isset($_REQUEST['renterId']) ? intval($_REQUEST['renterId']) : 0; //修改时赋值用
        $query = VipPromotionSign::find()
            ->select([
                'id','client','mobile','invite_code_mine','invite_code_used'
            ])
            ->where("code != '' AND invite_code_mine != '' AND is_del = 0");
        if($renterId){ // 修改时查询赋值
            $total = $query->andWhere(['id'=>$renterId])->count();
        }elseif($queryStr){ // 检索过滤时
            $total = $query
                ->andWhere([
                    'or',
                    ['like', 'client', $queryStr],
                    ['like', 'invite_code_mine', $queryStr],
                    ['like', 'mobile', $queryStr]
                ])
                ->count();
        }else{ // 默认查询
            $total = $query->count();
        }
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount' => $total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)->limit($pages->limit)->orderBy('id DESC')->asArray()->all();
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        return json_encode($returnArr);
    }



}