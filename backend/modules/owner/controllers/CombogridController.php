<?php
/**
 * 本控制器为各种【combogrid】提供下拉数据
 */
namespace backend\modules\owner\controllers;
use yii;
use yii\data\Pagination;
use backend\controllers\BaseController;
use backend\models\Owner;

class CombogridController extends BaseController{
    /**
     * 获取【机动车所有人】combogrid
     */
    public function actionGetOwners(){
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $query = Owner::find()
            ->andWhere([
                '`is_del`'=>0
            ]);
        if(yii::$app->request->get('pid') && !yii::$app->request->get('q')){
            $query->andWhere(['id'=>yii::$app->request->get('pid')]);
        }
        if(yii::$app->request->get('q')){
            //查询条件开始
            $query->andWhere([
                'or',
                ['like','`name`',yii::$app->request->get('q')],
                ['like','`code`',yii::$app->request->get('q')]
            ]);
        }
        //排序开始
        $sortColumn = yii::$app->request->get('sort');
        $sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
        $orderBy = '';
        if($sortColumn){
            $orderBy = '`'.$sortColumn.'` ';
        }else{
           $orderBy = '`id` ';
        }
        $orderBy .= $sortType;
        //排序结束
        $total = $query->count();
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)->limit($pages->limit)->orderBy($orderBy)->asArray()->all();
        if($data){
            $tmpArr = [];
            foreach($data as $item){
                if($item['pid'] == 0){
                    foreach($data as $row){
                        if(isset($item['pid']) && $row['pid'] == $item['id']){
                            $item['children'][] = $row;
                        }
                    }
                    $tmpArr[] = $item;
                }
            }
            $data = $tmpArr;
            array_unshift($data,['id'=>0,'name'=>'顶级','code'=>'','addr'=>'']);
        }else{
            $data = [['id'=>0,'name'=>'顶级','code'=>'','addr'=>'']];
        }
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total + 1;
        echo json_encode($returnArr);
    }
}