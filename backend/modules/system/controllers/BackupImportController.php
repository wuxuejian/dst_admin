<?php
/**
 * 文件备份、数据库备份、数据库语句执行控制器
 * time    2015/10/24 09:54
 * @author wangmin
 */
namespace backend\modules\system\controllers;
use backend\controllers\BaseController;
use backend\models\SystemBackup;
use common\models\File;
use yii;
use yii\data\Pagination;
class BackupImportController extends BaseController
{

    /**
     * 备份索引
     */
    public function actionBackupIndex()
    {
        $buttons = $this->getCurrentActionBtn();
        return $this->render('backup-index',[
            'buttons'=>$buttons
        ]);
    }

    /**
     * 获取备份列表
     */
    public function actionGetList()
    {
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        //查出每辆车的最新交强险记录
        $query = SystemBackup::find();
        //查询条件
        $query->andFilterWhere([
            '=',
            'backup_type',
            yii::$app->request->get('backup_type')
        ]);
        $backupDateStart = yii::$app->request->get('backup_date_start');
        if($backupDateStart){
            $query->andFilterWhere([
                '>=',
                '`backup_datetime`',
                strtotime($backupDateStart)
            ]);
        }
        $backupDateEnd = yii::$app->request->get('backup_date_end');
        if($backupDateEnd){
            $query->andFilterWhere([
                '<=',
                '`backup_datetime`',
                strtotime($backupDateEnd)
            ]);
        }
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
        $total = $query->count();
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)->limit($pages->limit)
                ->orderBy($orderBy)->asArray()->all();
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        echo json_encode($returnArr);
    }

    /**
     * 备份文件与数据库
     */
    public function actionBackupAll()
    {
        $this->actionBackupFile();
        $this->actionBackupDatabase();
    }

    /**
     * 程序文件备份
     */
    public function actionBackupFile()
    {
        ignore_user_abort(true);
        set_time_limit(0);
        @ob_end_flush();
        echo str_pad(" ", 256);
        echo '<div style="font-size:13px;line-height:22px;">';
        echo '开始备份程序文件...<br />';
        echo '正在读取备份目录文件...<br />';
        flush();
        $root = dirname(dirname(getcwd()));
        $except = '/(backup)|(runtime)|(\.svn)|(export_dir)/';
        $files = File::dirScanAll($root,true,$except);
        echo '正在压缩文件...<br />';
        flush();
        $zipFileName = $root.'/backup/'.time().'.zip';
        $zip = new \ZipArchive();
        $zip->open($zipFileName, \ZIPARCHIVE::CREATE);
        $zip->addEmptyDir(basename($root));
        foreach($files as $val){
            $zipPath = substr($val, strlen($root) + 1);
            if(is_dir($val)){
                $zip->addEmptyDir($zipPath);
            }else{
                $zip->addFile($val, $zipPath);
            }
        }
        $zip->close();
        $num = 8;
        echo '压缩完成等待处理...<br />';
        do{
            if(file_exists($zipFileName)){
                //保存备份结果
                $model = new SystemBackup;
                $model->backup_type = 'FILE';
                $model->file_name = basename($zipFileName);
                $model->file_size = filesize($zipFileName);
                $model->backup_datetime = time();
                $model->save(false);
                echo '程序文件备份操作完成！<br />';
                echo '</div>';
                return;//成功生成文件结束本方法执行
            }else{
                //文件还没生成继续等待...
            }
            $num --;
            sleep(2);
        }while($num > 0);
        echo '<span style="color:red">程序文件备份失败！</span><br />';
        echo '</div>';
    }

    /**
     * 备份数据库
     */
    public function actionBackupDatabase()
    {
        ignore_user_abort(true);
        set_time_limit(0);
        @ob_end_flush();
        echo str_pad(" ", 256);
        echo '<div style="font-size:13px;line-height:22px;">';
        echo '开始备份数据库...<br />';
        flush();
        $dbDsn = yii::$app->db->dsn;
        $dbName = substr($dbDsn,strpos($dbDsn,'dbname=')+7);
        $dbUsername = yii::$app->db->username;
        $dbPassword = yii::$app->db->password;
        $mysqlBin = yii::$app->params['mysql_bin'];
        $fileName = time().'.sql';
        $exportPosition = dirname(dirname(getcwd())).'/backup/'.$fileName;
        $commond = "{$mysqlBin}/mysqldump -u{$dbUsername} -p{$dbPassword} {$dbName} > {$exportPosition}";
        echo '执行命令：'.$commond.'<br />';
        flush();
        passthru($commond);
        if(!file_exists($exportPosition)){
             echo '<span style="color:red">数据库备份失败！</span><br />';
        }else{
            //保存备份结果
            $model = new SystemBackup;
            $model->backup_type = 'DB';
            $model->file_name = $fileName;
            $model->file_size = filesize($exportPosition);
            $model->backup_datetime = time();
            $model->save(false);
            echo '数据库备份操作完成！<br />';
        }
        echo '</div>';
        flush();
    }

    /**
     * 删除备份
     */
    public function actionBackupDel()
    {
        $id = yii::$app->request->post('id') or die('param id is required');
        $model = SystemBackup::findOne(['id'=>$id]);
        $model or die('record not found');
        $filename = dirname(dirname(getcwd())).'/backup/'.$model->getOldAttribute('file_name');
        $returnArr = [];
        $returnArr['info'] = '';
        $returnArr['status'] = false;
        $res = false;
        if(file_exists($filename)){
            if(unlink($filename)){
                $res = $model->delete();
            }
        }else{
            $res = $model->delete();
        }
        if($res){
            $returnArr['info'] = '备份删除成功！';
            $returnArr['status'] = true;
        }else{
            $returnArr['info'] = '备份删除失败！';
            $returnArr['status'] = false;
        }
        echo json_encode($returnArr);
    }

    /**
     * 下载备份
     */
    /*public function actionBackupDownload()
    {
        die;
        $id = yii::$app->request->get('id') or die('param id is required');
        $model = SystemBackup::findOne(['id'=>$id]);
        $model or die('record not found');
        $filename = dirname(dirname(getcwd())).'/backup/'.$model->getOldAttribute('file_name');
        if(!file_exists($filename)){
            echo 'the file you want to downlad is missing!';
        }else{
            File::fileDownload($filename);
        }
    }*/

}