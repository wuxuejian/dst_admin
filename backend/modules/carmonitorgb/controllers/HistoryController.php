<?php
/**
 * 车辆监控数据导出控制器
 * time    2016/02/25
 * @author wangmin
 */
namespace backend\modules\carmonitorgb\controllers;
use yii\db\MongoDBNew;

use backend\controllers\BaseController;
use backend\models\Car;

use common\classes\CarRealtimeDataAnalysis;
use common\models\Excel;
use common\classes\File;

use yii;
use yii\data\Pagination;
class HistoryController extends BaseController
{
    public function actionIndex(){
        $buttons = $this->getCurrentActionBtn();
        return $this->render('index',[
            'buttons'=>$buttons
        ]);
    }

    public function actionGetListData(){
    	date_default_timezone_set('PRC');
        $returnArr = [
            'rows'=>[],
            'total'=>0
        ];
        $carVin = yii::$app->request->get('car_vin');
        if(!$carVin){
            return json_encode($returnArr);
        }
        $startDate = yii::$app->request->get('start_date');
        if(!$startDate){
            $startDate = date('Y-m-d');
        }
        $endDate = yii::$app->request->get('end_date');
        $startTimeStamp = strtotime($startDate);
        
        $db = new MongoDBNew('car_history_data_'.date('Ym',$startTimeStamp).'_'.substr($carVin,-1));
        $filter = [];
        $options = [
	        'projection' => [
	        	'_id' => 0
	        ],
	        'sort' => ['collectionDatetime' => -1],
        ];
        //查询条件
        $filter = [
	        'carVin' => $carVin,
	        'collectionDatetime' => ['$gte' => (int)$startTimeStamp]
        ];
//         $db->where(['carVin' => $carVin]);
//         $db->where_gte('collectionDatetime', (int)$startTimeStamp);
        if($endDate){
        	$filter['collectionDatetime'] = ['$gte' => (int)$startTimeStamp, '$lte' => strtotime($endDate)];
        }
        //查询条件处理结束
        $total = $db->getCount($filter);
        if($total > 10000){
        	$returnArr['status'] = false;
        	$returnArr['info'] = '超出最大数据限制！';
        	return json_encode($returnArr);
        }
        //排序开始
        $sortColumn = yii::$app->request->get('sort');
        $sortType = yii::$app->request->get('order') == 'asc' ? '1' : '-1';
        if($sortColumn){
        	$options['sort'] = [$sortColumn => (int)$sortType];	//-1降序
        }
        //排序结束
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $options['skip'] = $pages->offset;
        $options['limit'] = $pageSize;
        $data = $db->query($filter,$options);
        //数据格式化
        foreach ($data as $index=>$row){
        	$row = json_decode(json_encode($row),true);
        	if(in_array($row['companyNo'], array(3))){
        		continue;
        	}
        	$row['moterTorque'] = number_format($row['moterTorque'],1);
        	$row['speed'] = number_format($row['speed'],1);
        	$row['moterVoltage'] = number_format($row['moterVoltage'],1);
        	$row['moterCurrent'] = number_format($row['moterCurrent'],1);
        	$row['batteryPackageTotalVoltage'] = number_format($row['batteryPackageTotalVoltage'],1);
        	$row['batteryPackageCurrent'] = number_format($row['batteryPackageCurrent'],1);
        	$row['batterySingleHvValue'] = number_format($row['batterySingleHvValue'],3);
        	$row['batterySingleLvValue'] = number_format($row['batterySingleLvValue'],3);
        	if($row['battteryVoltageData']){
        		foreach ($row['battteryVoltageData'] as $index2 => $row2){
        			foreach ($row2['battteryVoltageList'] as $index3 => $battteryVoltage){
        				$row2['battteryVoltageList'][$index3] = number_format($battteryVoltage,3);
        			}
        			$row['battteryVoltageData'][$index2] = $row2;
        		}
        	}
        	$data[$index] = $row;
        }
        
        $returnArr['total'] = $total;
        $returnArr['rows'] = $data;
        return json_encode($returnArr);
    }
}