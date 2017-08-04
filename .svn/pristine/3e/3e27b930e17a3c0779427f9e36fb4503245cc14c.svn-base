<?php
/**
 * 通讯授权管理控制器
 * time    2014/10/17 11:48
 * @author wangmin
 */
namespace backend\modules\communication\controllers;
use backend\controllers\BaseController;
use backend\models\TcpAuthor;
use yii;
use yii\data\Pagination;
class AuthorController extends BaseController
{
    public function actionIndex()
    {
        $buttons = $this->getCurrentActionBtn();
        return $this->render('index',[
            'buttons'=>$buttons
        ]);
    }

    public function actionGetList()
    {
        $pageSize = isset($_GET['rows']) && $_GET['rows']<=50 ? intval($_GET['rows']) : 10;
        //查询条件
        $activeRecord = TcpAuthor::find()->andWhere(['=','is_del',0]);
        $activeRecord->andFilterWhere(['like','count',yii::$app->request->get('count')]);
        $activeRecord->andFilterWhere(['like','company_name',yii::$app->request->get('company_name')]);
        //查询条件结束
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
        $total = $activeRecord->count();
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $activeRecord->offset($pages->offset)->limit($pages->limit)->orderBy($orderBy)->asArray()->all();
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        echo json_encode($returnArr);
    }

    /**
     * 添加账号
     */
    public function actionAdd()
    {
        //data submit start
        if(yii::$app->request->isPost){
            $model = new TcpAuthor;
            $model->load(yii::$app->request->post(),'');
            $returnArr = [];
            $returnArr['status'] = true;
            $returnArr['info'] = '';
            if($model->validate()){
                $model->count = md5(date('Ymd').str_pad(mt_rand(0,10000),5,0));
                $model->password = md5(substr(md5(yii::$app->request->post('password')),0,30));
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '账号添加成功！';
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '数据保存失败！';
                }
            }else{
                $returnArr['status'] = false;
                $errors = $model->getErrors();
                if($errors){
                    foreach($errors as $val){
                        $returnArr['info'] .= $val[0];
                    }
                }else{
                    $returnArr['info'] = '未知错误！';
                }
            }
            echo json_encode($returnArr);
            return null;
        }
        //data submit end
        return $this->render('add');
    }

    /**
     * 修改账号
     */
    public function actionEdit()
    {
        //data submit start
        if(yii::$app->request->isPost){
            $id = yii::$app->request->post('id') or die('param id is required!');
            $model = TcpAuthor::findOne(['id'=>$id]);
            $model->load(yii::$app->request->post(),'');
            $returnArr = [];
            $returnArr['status'] = true;
            $returnArr['info'] = '';
            if($model->validate()){
                if(!empty(yii::$app->request->post('password'))){
                    $model->password = md5(substr(md5(yii::$app->request->post('password')),0,30));
                }
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '账号修改成功！';
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '数据修改失败！';
                }
            }else{
                $returnArr['status'] = false;
                $errors = $model->getErrors();
                if($errors){
                    foreach($errors as $val){
                        $returnArr['info'] .= $val[0];
                    }
                }else{
                    $returnArr['info'] = '未知错误！';
                }
            }
            echo json_encode($returnArr);
            return null;
        }
        //data submit end
        $id = yii::$app->request->get('id') or die('param id is required!');
        $oldData = TcpAuthor::find()
            ->select(['id','company_name','note'])
            ->where(['id'=>$id])->asArray()->one();
        $oldData or die('record not found!');
        return $this->render('edit',[
            'oldData'=>json_encode($oldData)
        ]);
    }

    /**
     * 修改账号密码
     */
    public function actionPasswordEdit()
    {
        //data submit start
        if(yii::$app->request->isPost){
            $id = yii::$app->request->post('id') or die('param id is required!');
            return null;
        }
        //data submit end
        $id = yii::$app->request->get('id') or die('param id is required!');
        return $this->render('password-edit',[
            'id'=>$id
        ]);
    }

    /**
     * 删除账号
     */
    public function actionRemove()
    {
        $id = yii::$app->request->get('id') or die('param id is required');
        $returnArr = [];
        $returnArr['status'] = true;
        $returnArr['info'] = '';
        if(TcpAuthor::updateAll(['is_del'=>1],['id'=>$id])){
            $returnArr['status'] = true;
            $returnArr['info'] = '账号删除成功！';
        }else{
            $returnArr['status'] = true;
            $returnArr['info'] = '账号删除失败！';
        }
        echo json_encode($returnArr);
    }
}