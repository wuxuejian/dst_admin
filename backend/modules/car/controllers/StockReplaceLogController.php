<?php
/**
 * 自用备用车辆替换记录
 * @author pengyl
 */
namespace backend\modules\car\controllers;
use yii;
use yii\data\Pagination;
use backend\controllers\BaseController;

class StockReplaceLogController extends BaseController
{
    public function actionIndex()
    {		
        //获取本页按钮
        $buttons = $this->getCurrentActionBtn();
        return $this->render('index',[
            'buttons'=>$buttons
        ]);
    }
    
    /**
     * 获取车辆替换记录列表
     */
    public function actionGetList()
    {
    	$pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
    	$plate_number = yii::$app->request->get('plate_number');
    	$company_name = yii::$app->request->get('company_name');
    	$replace_start_time = yii::$app->request->get('replace_start_time');
    	$replace_end_time = yii::$app->request->get('replace_end_time');
    
    	$query = (new \yii\db\Query())->select([
    			'a.*',
    			'b.company_name',
    			'c.plate_number',
    			'd.plate_number replace_plate_number',
    			'e.username',
    			'f.car_type'
    			])->from('cs_car_stock_replace_log a')
    			->leftJoin('cs_customer_company b', 'a.c_customer_id = b.id')
    			->leftJoin('cs_car c', 'a.car_id = c.id')
    			->leftJoin('cs_car d', 'a.replace_car_id = d.id')
    			->leftJoin('cs_admin e', 'a.add_aid = e.id')
    			->leftJoin('cs_car_stock f', 'a.car_stock_id = f.id');
    	//查询条件
    	if($plate_number){
    		$query->andFilterWhere([
    				'like',
    				'c.plate_number',
    				$plate_number
    				]);
    	}
    	if($company_name){
    		$query->andFilterWhere([
    				'like',
    				'b.company_name',
    				$company_name
    				]);
    	}
    	if($replace_start_time){
    		$query->andFilterWhere([
    				'>=',
    				'a.replace_start_time',
    				$replace_start_time
    				]);
    	}
    	if($replace_end_time){
    		$query->andFilterWhere([
    				'<=',
    				'a.replace_start_time',
    				$replace_end_time
    				]);
    	}
    	$sortColumn = yii::$app->request->get('sort');
    	$sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
    	$orderBy = '';
    	if($sortColumn){
    		switch ($sortColumn) {
    			case 'plate_number':
    				$orderBy = 'e.`'.$sortColumn.'` ';
    				break;
    			case 'car_type':
    				$orderBy = 'a.`car_type` ';
    				break;
    			case 'car_status':
    				$orderBy = 'a.`car_status` ';
    				break;
    			case 'department_name':
    				$orderBy = 'c.`name` ';
    				break;
    			case 'company_name':
    				$orderBy = 'b.`company_name` ';
    				break;
    			case 'username':
    				$orderBy = 'd.`username` ';
    				break;
    			default:
    				$orderBy = 'a.`'.$sortColumn.'` ';
    			break;
    		}
    	}else{
    		$orderBy = 'a.`id` ';
    	}
    	$orderBy .= $sortType;
    	$total = $query->count();
    	$pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
    	$data = $query->offset($pages->offset)->limit($pages->limit)->orderBy($orderBy)->all();
    	$returnArr = [];
    	$returnArr['rows'] = $data;
    	$returnArr['total'] = $total;
    
    	
    	//     	exit($query->createCommand()->sql);
    	return json_encode($returnArr);
    }
    
    /**
     * 按条件导出车辆列表
     */
    public function actionExportWidthCondition()
    {
    	$plate_number = yii::$app->request->get('plate_number');
    	$company_name = yii::$app->request->get('company_name');
    	$replace_start_time = yii::$app->request->get('replace_start_time');
    	$replace_end_time = yii::$app->request->get('replace_end_time');
    
    	$query = (new \yii\db\Query())->select([
    			'a.*',
    			'b.company_name',
    			'c.plate_number',
    			'd.plate_number replace_plate_number',
    			'e.username',
    			'f.car_type'
    			])->from('cs_car_stock_replace_log a')
    			->leftJoin('cs_customer_company b', 'a.c_customer_id = b.id')
    			->leftJoin('cs_car c', 'a.car_id = c.id')
    			->leftJoin('cs_car d', 'a.replace_car_id = d.id')
    			->leftJoin('cs_admin e', 'a.add_aid = e.id')
    			->leftJoin('cs_car_stock f', 'a.car_stock_id = f.id');
    	//查询条件
    	if($plate_number){
    		$query->andFilterWhere([
    				'like',
    				'c.plate_number',
    				$plate_number
    				]);
    	}
    	if($company_name){
    		$query->andFilterWhere([
    				'like',
    				'b.company_name',
    				$company_name
    				]);
    	}
    	if($replace_start_time){
    		$query->andFilterWhere([
    				'>=',
    				'a.replace_start_time',
    				$replace_start_time
    				]);
    	}
    	if($replace_end_time){
    		$query->andFilterWhere([
    				'<=',
    				'a.replace_start_time',
    				$replace_end_time
    				]);
    	}
    	
    	$data = $query->all();
    	
    	$filename = '自用备用车替换记录.csv'; //设置文件名
    	$str = "车牌号,车辆类型,客户,被替换车辆,替换原因,替换时间,预计还车时间,实际还车时间,操作人,操作时间\n";
    	$car_type_arr = array(1=>'自用车',2=>'备用车');
    	foreach ($data as $row){
    		$str .= "{$row['plate_number']},{$car_type_arr[$row['car_type']]},{$row['company_name']},{$row['replace_plate_number']},{$row['replace_desc']},{$row['replace_start_time']},{$row['replace_end_time']},{$row['real_end_time']},{$row['username']},{$row['add_time']}"."\n";
    	}
    	$str = mb_convert_encoding($str, "GBK", "UTF-8");
    	$this->export_csv($filename,$str); //导出
    }
    
    function export_csv($filename,$data)
    {
    	//		header("Content-type: text/html; charset=utf-8");
    	header("Content-type:text/csv;charset=GBK");
    	header("Content-Disposition:attachment;filename=".$filename);
    	header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
    	header('Expires:0');
    	header('Pragma:public');
    	echo $data;
    }
}