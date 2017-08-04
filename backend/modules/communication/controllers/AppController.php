<?php
/**
 * 通讯服务程序状态
 * time    2014/10/17 11:48
 * @author wangmin
 */
namespace backend\modules\communication\controllers;
use backend\controllers\BaseController;
use backend\models\TcpApplications;
use backend\models\ConfigCategory;
use yii;
use yii\data\Pagination;
class AppController extends BaseController
{
    /**
     * 索引
     */
    public function actionIndex()
    {
        $buttons = $this->getCurrentActionBtn();
        return $this->render('index',[
            'buttons'=>$buttons
        ]);
    }

    /**
     * 获取列表
     */
    public function actionGetList()
    {
        $pageSize = isset($_GET['rows']) && $_GET['rows']<=50 ? intval($_GET['rows']) : 10;
        //查询条件
        $activeRecord = TcpApplications::find()->andWhere(['=','is_del',0]);
        $activeRecord->andFilterWhere(['like','app_name',yii::$app->request->get('app_name')]);
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
        if($data){
            foreach($data as &$dataItem){
                $ping = $this->socketPing($dataItem['app_addr'],$dataItem['app_port']);
                if($ping['status']){
                    $dataItem['status'] = 1;
                    $dataItem['response'] = $ping['response'];
                }else{
                    $dataItem['status'] = 0;
                }
            }
        }
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        echo json_encode($returnArr);
    }

    /**
     * 添加或修改应用
     */
    public function actionAddEdit()
    {
        $returnArr = [];
        $returnArr['status'] = true;
        $returnArr['info'] = '';
        if(!empty(yii::$app->request->post())){
            foreach(yii::$app->request->post('id') as $key=>$val){
                if(!$val){
                    $model = new TcpApplications;
                }else{
                    $model = TcpApplications::findOne(['id'=>intval($val)]);
                    $model or $model = new TcpApplications;
                }
                $model->app_name = $_POST['app_name'][$key];
                $model->app_path = rtrim(str_replace('/\\','/',$_POST['app_path'][$key]),'/');
                $model->app_addr = $_POST['app_addr'][$key];
                $model->app_port = $_POST['app_port'][$key];
                if($model->validate()){
                    if(!$model->save(false)){
                        $returnArr['status'] = false;
                        $returnArr['info'] .= $_POST['app_name'][$key].'保存失败！';
                    }
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] .= $_POST['app_name'][$key].'操作失败，';
                    $errors = $model->getErrors();
                    if($errors && is_array($errors)){
                        foreach($errors as $error){
                            $returnArr['info'] .= $error[0];
                        }
                    }else{
                        $returnArr['info'] .= '未知错误！';
                    }
                }
            }
        }else{
            $returnArr['status'] = false;
            $returnArr['info'] = '没有应用被添加或修改！';
        }
        if(empty($returnArr['info'])){
            $returnArr['info'] = '添加或修改操作成功！';
        }
        echo json_encode($returnArr);
    }

    /**
     * 删除应用
     */
    public function actionRemove()
    {
        $ids = rtrim(yii::$app->request->get('ids'),',') or die('param ids is required!');
        $ids = explode(',',$ids);
        $returnArr = [];
        $returnArr['status'] = true;
        $returnArr['info'] = '';
        if($ids){
            if(TcpApplications::updateAll(['is_del'=>1],['in','id',$ids])){
                $returnArr['status'] = true;
                $returnArr['info'] = '删除成功！';
            }else{
                $returnArr['status'] = false;
                $returnArr['info'] = '删除失败！';
            }
        }else{
            $returnArr['status'] = false;
            $returnArr['info'] = '没有应用被删除！';
        }
        echo json_encode($returnArr);
    }

    /**
     * 启动应用
     */
    public function actionStart()
    {
        $id = yii::$app->request->get('id') or die('param id is required');
        $app = TcpApplications::find()
                    ->select(['app_path'])
                    ->where(['id'=>intval($id)])
                    ->asArray()->one() or die('record not found!');
        $file = $app['app_path'].'/start.php';
        echo '<style>*{font-size:12px;line-height:22px;}</style>';
        if(file_exists($file)){
            echo '<pre>';
            echo $this->execCommand($file.' restart -d');
            echo '</pre>';
        }else{
            echo '无法找到文件：',$file,"<br />";
        }
    }

    /**
     * 停止应用
     */
    public function actionStop()
    {
        $id = yii::$app->request->get('id') or die('param id is required');
        $app = TcpApplications::find()
                    ->select(['app_path'])
                    ->where(['id'=>intval($id)])
                    ->asArray()->one() or die('record not found!');
        $file = $app['app_path'].'/start.php';
        echo '<style>*{font-size:12px;line-height:22px;}</style>';
        if(file_exists($file)){
            echo '<pre>';
            echo $this->execCommand($file.' stop');
            echo '</pre>';
        }else{
            echo '无法找到文件：',$file,"<br />";
        }
    }


    /**
     * 查看应用状态
     */
    public function actionStatus()
    {
        $id = yii::$app->request->get('id') or die('param id is required');
        $app = TcpApplications::find()
                    ->select(['app_path'])
                    ->where(['id'=>intval($id)])
                    ->asArray()->one() or die('record not found!');
        $file = $app['app_path'].'/start.php';
        echo '<style>*{font-size:12px;line-height:24px;}</style>';
        if(file_exists($file)){
            echo '<pre>';
            echo $this->execCommand($file.' status');
            echo '</pre>';
        }else{
            echo '无法找到文件：',$file,"<br />";
        }
    }

    /**
     * 执行系统命令
     */
    protected function execCommand($command)
    {
        //获取配置php环境变量
        $config = (new ConfigCategory)->getCategoryConfig(['php_path']);
        if(!$config){
            return '<b style="color:red">缺少PHP环境变量配置！</b>';
        }
        $phpPath = array_values($config['php_path'])[0]['value'];
        $phpPath = str_replace('\\','/', $phpPath);
        $phpPath = rtrim($phpPath,'/').'/';
        $command = $phpPath.'php '.$command;
        echo '执行命令：'.$command.'<br />';
        $str = system($command);
        if(!$str){
            $str .= '<b style="color:red">无数据返回，请检查系统配置中的php环境变量！</b><br />';
        }
        $str .= '<br />';
        return $str;
    }

    /**
     * ping应用
     */
    public function actionPing($id = 0)
    {
        $id = yii::$app->request->get('id') or die('param id is required');
        $model = TcpApplications::find()
                    ->select(['app_addr','app_port'])
                    ->where(['id'=>intval($id)])
                    ->asArray()->one() or die('record not found!');
        echo '<style>*{font-size:12px;line-height:22px;}</style>';
        echo 'ping ',$model['app_addr'],' on ',$model['app_port'],':<br />';
        for($i = 0;$i < 5;$i++){
            echo '[',($i+1),'] ';
            $result = $this->socketPing($model['app_addr'],$model['app_port']);
            if(isset($result['status']) && $result['status']){
                echo '<b style="color:green;">成功</b>，响应时间：',$result['response'],'s <br />';
            }else{
                echo '<b style="color:red;">失败！</b>',iconv('gbk','utf-8',$result['errstr']),'<br />';
            }
            ob_flush();
            flush();
        }
        echo '结束！';
    }

    /**
     * socket ping
     */
    protected function socketPing($addr,$port)
    {
        $startTime = microtime(true);
        $socket = @fsockopen($addr,$port,$errno,$errstr,1);
        if(!$socket){
            return [
                'status'=>false,
                'errstr'=>$errstr,
            ];
        }else{
            $endTime = microtime(true);
            return [
                'status'=>true,
                'response'=>$endTime-$startTime,
            ];
        }
    }
}