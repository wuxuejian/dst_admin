<?php
/**
 * @Desc:   会员意见建议
 * @author: wangmin
 * @date:   2016-01-25 11:42
 */
namespace backend\modules\vip\controllers;
use yii;
use yii\data\Pagination;
use backend\controllers\BaseController;
use backend\models\VipSuggestion;
class SuggestionController extends BaseController{

    /**
     * 意见建议管理入口
     */
    public function actionIndex(){
        $buttons = $this->getCurrentActionBtn();
        return $this->render('index',[
            'buttons'=>$buttons,
        ]);
    }

    /**
     * 获取数据列表
     * 该方法未设置权限
     */
    public function actionGetList(){
        $query = VipSuggestion::find()
            ->select([
                '{{%vip_suggestion}}.`vs_id`',
                '{{%vip_suggestion}}.`vs_code`',
                '{{%vip_suggestion}}.`vs_title`',
                '{{%vip_suggestion}}.`vs_content`',
                '{{%vip_suggestion}}.`vs_time`',
                '{{%vip_suggestion}}.`vs_respond_time`',
                '{{%vip_suggestion}}.`vs_respond_txt`',
                '{{%vip_suggestion}}.`vs_mark`',
                'vip_code'=>'{{%vip}}.`code`',
                'vip_mobile'=>'{{%vip}}.`mobile`',
                'admin_username'=>'{{%admin}}.`username`',
            ])->joinWith('vip',false)->joinWith('admin',false)
            ->where(['{{%vip_suggestion}}.`vs_is_del`'=>0]);
        //查询条件
        $query->andFilterWhere(['like','{{%vip_suggestion}}.`vs_code`',yii::$app->request->get('vs_code')]);
        $query->andFilterWhere(['like','{{%vip}}.`code`',yii::$app->request->get('vip_code')]);
        $query->andFilterWhere(['like','{{%vip}}.`mobile`',yii::$app->request->get('vip_mobile')]);
        $query->andFilterWhere(['>=','{{%vip_suggestion}}.`vs_time`',yii::$app->request->get('vs_time_start')]);
        $query->andFilterWhere(['<=','{{%vip_suggestion}}.`vs_time`',yii::$app->request->get('vs_time_end')]);
        $query->andFilterWhere(['=','{{%admin}}.`username`',yii::$app->request->get('admin_username')]);
        //排序开始
        $sortColumn = yii::$app->request->get('sort');
        $sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
        $orderBy = '';
        if($sortColumn){
            switch ($sortColumn) {
                case 'vip_code':
                    $orderBy = '{{%vip}}.`code` ';
                    break;
                case 'vip_mobile':
                    $orderBy = '{{%vip}}.`mobile` ';
                    break;
                case 'admin_username':
                    $orderBy = '{{%admin}}.`username` ';
                    break;
                default:
                    $orderBy = '{{%vip_suggestion}}.`'.$sortColumn.'` ';
                    break;
            }
        }else{
           $orderBy = '{{%vip_suggestion}}.`vs_id` ';
        }
        $orderBy .= $sortType;
        //排序结束
        $total = $query->count();
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)->limit($pages->limit)
                ->orderBy($orderBy)->asArray()->all();
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        echo json_encode($returnArr);
    }

    /**
     * 查看详细
     */
    public function actionScan(){
        $vsId = yii::$app->request->get('vs_id');
        if(!$vsId){
            return;
        }
        $suggestionInfo = VipSuggestion::find()
            ->select([
                '{{%vip_suggestion}}.`vs_id`',
                '{{%vip_suggestion}}.`vs_code`',
                '{{%vip_suggestion}}.`vs_title`',
                '{{%vip_suggestion}}.`vs_content`',
                '{{%vip_suggestion}}.`vs_time`',
                '{{%vip_suggestion}}.`vs_respond_time`',
                '{{%vip_suggestion}}.`vs_respond_txt`',
                '{{%vip_suggestion}}.`vs_mark`',
                'vip_code'=>'{{%vip}}.`code`',
                'vip_mobile'=>'{{%vip}}.`mobile`',
                'admin_username'=>'{{%admin}}.`username`',
            ])->where(['{{%vip_suggestion}}.`vs_id`'=>$vsId])
            ->joinWith('vip',false)->joinWith('admin',false)
            ->asArray()->one();
        if(!$suggestionInfo){
            return false;
        }
        return $this->render('scan',[
            'suggestionInfo'=>$suggestionInfo,
        ]);
    }

    /**
     * 修改意见建议
     */
    public function actionEdit(){
        if(yii::$app->request->isPost){
            $model = VipSuggestion::findOne(['vs_id'=>yii::$app->request->post('vs_id')]);
            if(!$model){
                return;
            }
            $returnArr = [];
            $returnArr['status'] = true;
            $returnArr['info'] = '';
            $model->load(yii::$app->request->post(),'');
            if(yii::$app->request->post('vs_respond_txt')){
                $model->vs_responder_id = $_SESSION['backend']['adminInfo']['id'];
                $model->vs_respond_time = date('Y-m-d H:i:s');
            }
            if($model->save()){
                $returnArr['status'] = true;
                $returnArr['info'] = '意见建议修改成功！';
            }else{
                $errors = $model->getErrors();
                if($errors){
                    $errorInfo = join('',array_colums($errors,0));
                }else{
                    $errorInfo = '数据保存失败！';
                }
                $returnArr['info'] = $errorInfo;
            }
            echo json_encode($returnArr);
            return;
        }else{
            $vsId = yii::$app->request->get('vs_id');
            if(!$vsId){
                return;
            }
            $suggestionInfo = VipSuggestion::find()
                ->select([
                    '{{%vip_suggestion}}.`vs_id`',
                    '{{%vip_suggestion}}.`vs_code`',
                    '{{%vip_suggestion}}.`vs_title`',
                    '{{%vip_suggestion}}.`vs_content`',
                    '{{%vip_suggestion}}.`vs_time`',
                    '{{%vip_suggestion}}.`vs_respond_time`',
                    '{{%vip_suggestion}}.`vs_respond_txt`',
                    '{{%vip_suggestion}}.`vs_mark`',
                    'vip_code'=>'{{%vip}}.`code`',
                    'vip_mobile'=>'{{%vip}}.`mobile`',
                    'admin_username'=>'{{%admin}}.`username`',
                ])->where(['{{%vip_suggestion}}.`vs_id`'=>$vsId])
                ->joinWith('vip',false)->joinWith('admin',false)
                ->asArray()->one();
            if(!$suggestionInfo){
                return false;
            }
            return $this->render('edit',[
                'suggestionInfo'=>$suggestionInfo,
            ]);
        }
    }

    /**
     * 回复
     */
    public function actionReply(){
        if(yii::$app->request->isPost){
            $model = VipSuggestion::findOne(['vs_id'=>yii::$app->request->post('vs_id')]);
            if(!$model){
                return;
            }
            $returnArr = [];
            $returnArr['status'] = true;
            $returnArr['info'] = '';
            $model->load(yii::$app->request->post(),'');
            $model->vs_responder_id = $_SESSION['backend']['adminInfo']['id'];
            $model->vs_respond_time = date('Y-m-d H:i:s');
            if($model->save()){
                $returnArr['status'] = true;
                $returnArr['info'] = '意见建议回复成功！';
            }else{
                $errors = $model->getErrors();
                if($errors){
                    $errorInfo = join('',array_colums($errors,0));
                }else{
                    $errorInfo = '数据保存失败！';
                }
                $returnArr['info'] = $errorInfo;
            }
            echo json_encode($returnArr);
            return;
        }else{
            $vsId = yii::$app->request->get('vs_id');
            if(!$vsId){
                return;
            }
            $suggestionInfo = VipSuggestion::find()
                ->select([
                    '`vs_id`',
                    '`vs_title`',
                    '`vs_content`',
                    '`vs_respond_txt`',
                    '`vs_mark`',
                ])->where(['`vs_id`'=>$vsId])
                ->asArray()->one();
            if(!$suggestionInfo){
                return false;
            }
            return $this->render('reply',[
                'suggestionInfo'=>$suggestionInfo,
            ]);
        }
    }

    /**
     * 删除意见建议
     */
    public function actionRemove(){
        $vsId = yii::$app->request->get('vs_id');
        $returnArr = [];
        $returnArr['status'] = false;
        $returnArr['info'] = '';
        if(VipSuggestion::updateAll(['vs_is_del'=>1],['vs_id'=>$vsId])){
            $returnArr['status'] = true;
            $returnArr['info'] = '意见建议删除成功！';
        }else{
            $returnArr['info'] = '意见建议删除失败！';
        }
        echo json_encode($returnArr);
    }
}