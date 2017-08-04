<?php
/*
 * 守护进程监控 控制器
 */
namespace backend\modules\system\controllers;
use backend\controllers\BaseController;
use yii;
use yii\data\Pagination;
use backend\models\ConfigCategory;
use backend\models\SystemDaemon;

class DaemonController extends BaseController
{
    public function actionIndex()
    {
        $buttons = $this->getCurrentActionBtn();
        return $this->render('index',[
            'buttons'=>$buttons
        ]);
    }
    
    /**
     * 获取进程列表
     */
    public function actionGetList()
    {
        $query = SystemDaemon::find()
            ->select(['*']);
        //查询条件
        $query->andFilterWhere(['like','`name`',yii::$app->request->get('name')]);
        //排序
        $sortColumn = yii::$app->request->get('sort');
        $sortType = yii::$app->request->get('order') == 'desc' ? 'desc' : 'asc';
        $orderBy = '';
        if($sortColumn){
            $orderBy = '`'.$sortColumn.'` ';
        }else{
            $orderBy = '`id` ';
        }
        $orderBy .= $sortType;
        $total = $query->count();
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $query->offset($pages->offset)->limit($pages->limit)->orderBy($orderBy);
        $rows = $query->asArray()->all();
        if($rows){
            //从进程日志中获取进程信息
            foreach($rows as &$row){
                $logPath = dirname($row['script_path']) . '/ProcessInfo/' . basename($row['script_path']) . '.log';
                if(file_exists($logPath)){
                    $logInfo = file_get_contents($logPath);
                    $processInfo = json_decode($logInfo,true);
                    $row['status'] = (time() - $processInfo['activeTime']) < 120 ? 'NORMAL' : 'ABNORMAL';
                    $row['startTime'] = date('Y-m-d H:i:s',$processInfo['startTime']);
                    $sec = $processInfo['activeTime'] - $processInfo['startTime'];
                    if($sec){
                        $d = floor( $sec / (3600*24) );
                        $diff = $sec - $d * (3600*24);
                        $h = floor( $diff / 3600 );
                        $diff2 = $diff - $h * 3600;
                        $m = floor( $diff2 / 60);
                        $diff3 = $diff2 - $m * 60;
                        $str = '';
                        if($d){
                            $str .= $d . '天';
                        }
                        if($h){
                            $str .= $h . '时';
                        }
                        if($m){
                            $str .= $m . '分';
                        }
                        if($diff3){
                            $str .= $diff3 . '秒';
                        }
                        $row['runTime'] = $str;
                    }else{
                        $row['runTime'] = '';
                    }
                    $row['pid'] = $processInfo['pid'];
                    $row['memory'] = round($processInfo['memory'] / 1024 / 1024, 2) . ' M';
                }
            }
        }
        $returnArr = [];
        $returnArr['rows'] = $rows;
        $returnArr['total'] = $total;
        return json_encode($returnArr);
    }
    
    /**
     * 添加进程
     */
    public function actionAdd()
    {
        if(yii::$app->request->isPost){
            $formData = yii::$app->request->post();
            $model = new SystemDaemon();
            $model->load($formData,'');
            if($model->save(true)){
                $returnArr['status'] = true;
                $returnArr['info'] = '守护进程添加成功！';
            }else{
                $returnArr['status'] = false;
                $error = $model->getErrors();
                if($error){
                    $errorStr = join('',array_column($error,0));
                }else{
                    $errorStr = '未知错误！';
                }
                $returnArr['info'] = $errorStr;
            }
            return json_encode($returnArr);
        }else{
            return $this->render('add');
        }
    }
    
    /**
     * 修改进程
     */
    public function actionEdit()
    {
        //data submit
        if(yii::$app->request->isPost){
            $formData = yii::$app->request->post();
            $model = SystemDaemon::findOne(['id'=>$formData['id']]);
            if(!$model){
                die('未找到对应守护进程！');
            }
            $model->load($formData,'');
            if($model->validate()){
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '守护进程修改成功';
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '守护进程修改失败';
                }
            }else{
                $returnArr['status'] = false;
                $error = $model->getErrors();
                if($error){
                    $errorStr = join('',array_column($error,0));
                }else{
                    $errorStr = '未知错误';
                }
                $returnArr['info'] = $errorStr;
            }
            return json_encode($returnArr);
        }else{
            $id = intval(yii::$app->request->get('id'));
            $recInfo = SystemDaemon::find()->where(['id'=>$id])->asArray()->one();
            if(!$recInfo){
                die('未找到对应守护进程！');
            }
            return $this->render('edit',['recInfo'=>$recInfo]);
        }
    }
    
    /**
     * 删除进程
     */
    public function actionRemove()
    {
        $id = intval(yii::$app->request->get('id')) or die('param(id) is required');
        $returnArr = [];
        if(SystemDaemon::deleteAll(['id'=>$id])){
            $returnArr['status'] = true;
            $returnArr['info'] = '守护进程删除成功！';
        }else{
            $returnArr['status'] = false;
            $returnArr['info'] = '守护进程删除失败！';
        }
        echo json_encode($returnArr);
    }



}