<?php
namespace backend\modules\system\controllers;

use Yii;
use backend\models\ConfigCategory;
use backend\models\SysUserLog;
use backend\models\SysUserLogSearch;

/**
 * @name 日志操作控制器类
 * @author tanbenjiang
 * @date 2015-6-11
 *
 */

class SysLogController extends \backend\controllers\BaseController
{
    public function actionIndex()
    {	
        $config = (new ConfigCategory())->getCategoryConfig(['log_type'],'value'); 
        return $this->render('index',[
            'config'=>$config
        ]); 
    }
    
    
    /**
     * @name 远程获取日志数据信息
     */
    public function actionList()
    {
        $param = \Yii::$app->request->post();
        
        // 接收分页和排序参数
        $page = isset($param['page']) ? intval($param['page']) : 1;
        $rows = isset($param['rows']) ? intval($param['rows']) : 20;
        $sort = isset($param['sort']) ? strval($param['sort']) : 'log_id';
        $order = isset($param['order']) ? strval($param['order']) : 'desc';
        
        // 执行查询操作
        if(!empty($param['search_flag'])){
        	$query = SysUserLogSearch::search($param);
        }else{
        	$query = SysUserLog::find();
        }
          
        $count = $query->count();
        $results = $query->asArray()
                       ->orderBy("{$sort} {$order}")
                       ->offset(($page-1)*$rows)->limit($rows)
                       ->all();
        
        // 格式化操作时间
        foreach ($results as $key=>$value)
        {
        	$results[$key]['log_time'] = date('Y-m-d H:i:s',$value['log_time']);
        }
        
        $datas = ['total' => $count, 'rows' => $results];
        
        echo json_encode($datas);
    }

}
