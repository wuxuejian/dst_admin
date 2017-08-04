<?php
/**
 * 车辆测试数据登记控制器
 * time    2014/10/17 09:05
 * @author wangmin
 */
namespace backend\modules\car\controllers;
use backend\controllers\BaseController;
use backend\models\Car;
use backend\models\CarTest;
use common\models\Excel;
use yii;
use yii\data\Pagination;
class TestController extends BaseController
{
    public function actionIndex()
    {
        $buttons = $this->getCurrentActionBtn();
        return $this->render('index',[
            'buttons'=>$buttons
        ]);
    }
    
//     public function actionTest(){
//     	set_time_limit(0);
//     	$query = Car::find()
// 	    	->select([
// 	    			'{{%car}}.`id`',
// 	    			'{{%car}}.`plate_number`',
// 	    			'{{%car}}.`add_time`',
// 	    			'{{%car_driving_license}}.`register_date`',
// 	    			])
// 	    	->leftJoin('{{%car_driving_license}}','{{%car}}.id = {{%car_driving_license}}.car_id')
// 	    	->andWhere(['{{%car}}.`is_del`'=>0,'{{%car}}.`add_time`'=>0]);
//     	$carList = $query->asArray()->all();
//     	echo count($carList);
//     	foreach ($carList as $row){
//     		Car::updateAll(['add_time'=>$row['register_date']],['id'=>$row['id']]);
//     	}
    	
// //     	echo $query->createCommand()->getRawSql();exit;
//     }

    /**
     * 获取所有车辆测试数据列表
     */
    public function actionGetList()
    {
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $query = CarTest::find()
                    ->select([
                        CarTest::tableName().'.*',
                        Car::tableName().'.`plate_number`'
                    ])->joinWith('car',false,'LEFT JOIN')
                    ->where([
                        CarTest::tableName().'.`is_del`'=>0
                    ]);
        //查询条件
        $query->andFilterWhere([
            'like',
            Car::tableName().'.`plate_number`',
            yii::$app->request->get('plate_number')
        ]);
        $regTimeStart = yii::$app->request->get('reg_time_start');
        if($regTimeStart){
            $query->andFilterWhere([
                '>=',
                CarTest::tableName().'.`reg_time`',
                strtotime($regTimeStart)
            ]);
        }
        $regTimeEnd = yii::$app->request->get('reg_time_end');
        if($regTimeEnd){
            $query->andFilterWhere([
                '<=',
                CarTest::tableName().'.`reg_time`',
                strtotime($regTimeEnd)
            ]);
        }
        //查询条件结束
        //排序开始
        $sortColumn = yii::$app->request->get('sort');
        $sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
        $orderBy = '';
        if($sortColumn){
            switch ($sortColumn) {
                case 'plate_number':
                    $orderBy = Car::tableName().'.`plate_number` ';
                    break;
                default:
                    $orderBy = CarTest::tableName().'.`'.$sortColumn.'` ';
                    break;
            }
        }else{
           $orderBy = CarTest::tableName().'.`id` ';
        }
        $orderBy .= $sortType;
        //排序结束
        $total = $query->count();
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)->limit($pages->limit)->orderBy($orderBy)->asArray()->all();
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        echo json_encode($returnArr);
    }

    /**
     * 添加测试数据
     */
    public function actionAdd()
    {
        //data submit start
        if(yii::$app->request->isPost){
            $model = new CarTest();
            $model->load(yii::$app->request->post(),'');
            $returnArr = [];
            $returnArr['status'] = true;
            $returnArr['info'] = '';
            if($model->validate()){
                $model->reg_time = time();
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '车辆测试数据登记成功！';
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '数据保存失败！';
                }
            }else{
                $returnArr['status'] = false;
                $error = $model->getErrors();
                if($error){
                    foreach($error as $val){
                        $returnArr['info'] .= $val[0];
                    }
                }else{
                    $returnArr['info'] = '未知错误！';
                }
            }
            echo json_encode($returnArr);
            return;
        }
        //data submit end
        return $this->render('add');
    }

    /**
     * 修改测试数据
     */
    public function actionEdit()
    {
        //data submit start
        if(yii::$app->request->isPost){
            $id = yii::$app->request->post('id') or die('param id is required');
            $model = CarTest::findOne(['id'=>$id]);
            $model or die('record not found');
            $model->load(yii::$app->request->post(),'');
            $returnArr = [];
            $returnArr['status'] = true;
            $returnArr['info'] = '';
            if($model->validate()){
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '车辆测试数据修改成功！';
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '数据保存失败！';
                }
            }else{
                $returnArr['status'] = false;
                $error = $model->getErrors();
                if($error){
                    foreach($error as $val){
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
        //查询所有状态为库存车型的车牌号
        $id = yii::$app->request->get('id') or die('param id is required');
        $model = CarTest::findOne(['id'=>$id]);
        if(!$model){
            echo 'record not found!';
            return;
        }
        return $this->render('edit',[
            'testInfo'=>$model->getOldAttributes()
        ]);
    }

    /**
     * 删除测试数据
     */
    public function actionRemove()
    {
        $id = intval(yii::$app->request->get('id')) or die('param id is required');
        $returnArr = [];
        if(CarTest::updateAll(['is_del'=>1],['id'=>$id])){
            //删除车辆故障信息
            $returnArr['status'] = true;
            $returnArr['info'] = '车辆测试数据删除成功！';
        }else{
            $returnArr['status'] = false;
            $returnArr['info'] = '车辆测试数据删除失败！';
        }
        echo json_encode($returnArr);
    }

    /**
     * 按条件导出
     */
    public function actionExportWidthCondition()
    {
        $query = CarTest::find()
                    ->select([
                        Car::tableName().'.`plate_number`',
                        CarTest::tableName().'.`reg_time`',
                        CarTest::tableName().'.`mileage`',
                        CarTest::tableName().'.`use_hour`',
                        CarTest::tableName().'.`use_minute`',
                        CarTest::tableName().'.`slow_recharge_status`',
                        CarTest::tableName().'.`fast_recharge_status`',
                    ])->joinWith('car',false,'LEFT JOIN')
                    ->where([
                        CarTest::tableName().'.`is_del`'=>0
                    ]);
        //查询条件
        $query->andFilterWhere([
            'like',
            Car::tableName().'.`plate_number`',
            yii::$app->request->get('plate_number')
        ]);
        $regTimeStart = yii::$app->request->get('reg_time_start');
        if($regTimeStart){
            $query->andFilterWhere([
                '>=',
                CarTest::tableName().'.`reg_time`',
                strtotime($regTimeStart)
            ]);
        }
        $regTimeEnd = yii::$app->request->get('reg_time_end');
        if($regTimeEnd){
            $query->andFilterWhere([
                '<=',
                CarTest::tableName().'.`reg_time`',
                strtotime($regTimeEnd)
            ]);
        }
        //查询条件结束
        $data = $query->asArray()->all();
        $excel = new Excel();
        $excel->setHeader([
            'creator'=>'皓峰通讯',
            'lastModifiedBy'=>'hao feng tong xun',
            'title'=>'car test',
            'subject'=>'car test',
            'description'=>'car test list',
            'keywords'=>'car test list',
            'category'=>'car test list'
        ]);
        $lineData = [
            ['content'=>'车牌号','font-weight'=>true,'width'=>'20'],
            ['content'=>'登记时间','font-weight'=>true,'width'=>'20'],
            ['content'=>'测试里程数','font-weight'=>true,'width'=>'20'],
            ['content'=>'测试使用小时数','font-weight'=>true,'width'=>'20'],
            ['content'=>'测试使用分钟数','font-weight'=>true,'width'=>'20'],
            ['content'=>'慢充充电状况','font-weight'=>true,'width'=>'80'],
            ['content'=>'快充充电状况','font-weight'=>true,'width'=>'80'],

        ];
        $excel->addLineToExcel($lineData);
        foreach($data as $val){
            $val['reg_time'] = date('Y-m-d H:i:s',$val['reg_time']);
            $lineData = [];
            foreach($val as $v){
                $lineData[] = ['content'=>$v,'align'=>'left'];
            }
            $excel->addLineToExcel($lineData);
        }
        $objPHPExcel = $excel->getPHPExcel();
        header("Content-type: application/octet-stream"); 
        header("Accept-Ranges: bytes"); 
        //header("Accept-Length:".$fileSize); 
        header("Content-Disposition: attachment; filename=".iconv('utf-8','gbk','车辆测试数据列表.xls')); 
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }

}