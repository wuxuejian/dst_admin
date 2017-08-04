<?php
/**
 * 车辆二级维护记录控制器
 * time    2014/10/17 09:05
 * @author wangmin
 */
namespace backend\modules\car\controllers;
use backend\controllers\BaseController;
use backend\models\CarSecondMaintenance;
use backend\models\Car;
use common\models\Excel;
use yii;
use yii\data\Pagination;
class TwoLevelRecordController extends BaseController
{
    public function actionIndex()
    {
        $buttons = $this->getCurrentActionBtn();
        return $this->render('index',[
            'buttons'=>$buttons
        ]);
    }

    //获取车辆二级维护记录列表
    public function actionGetList()
    {
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $query = CarSecondMaintenance::find()
                ->select([
                    '{{%car_second_maintenance}}.*',
                    '{{%car}}.`plate_number`',
                    '{{%admin}}.`username`'
                ])
                ->joinWith('car',false,'LEFT JOIN')
                ->joinWith('admin',false,'LEFT JOIN')
                ->where(['=','{{%car_second_maintenance}}.`is_del`',0]);
        //查询条件
        $query->andFilterWhere([
            'like',
            '{{%car}}.`plate_number`',
            yii::$app->request->get('plate_number')
        ]);
        $query->andFilterWhere([
            'like',
            '{{%car_second_maintenance}}.`number`',
            yii::$app->request->get('number')
        ]);
        //检测是否要根据当前登录人员所属运营公司来显示列表数据-20160325
        $isLimitedArr = Car::isLimitedToShowByAdminOperatingCompany();
        if(is_array($isLimitedArr) && isset($isLimitedArr['status']) && $isLimitedArr['status']){
            $query->andWhere("{{%car}}.`operating_company_id` in ({$isLimitedArr['adminInfo_operatingCompanyId']})");
        }
        //查询条件
        //排序开始
        $sortColumn = yii::$app->request->get('sort');
        $sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
        $orderBy = '';
        if($sortColumn){
            switch ($sortColumn) {
                case 'plate_number':
                    $orderBy = '{{%car}}.`plate_number` ';
                    break;
                case 'username':
                    $orderBy = '{{%admin}}.`username` ';
                    break;
                default:
                    $orderBy = '{{%car_second_maintenance}}.`'.$sortColumn.'` ';
                    break;
            }
        }else{
           $orderBy = '{{%car_second_maintenance}}.`id` ';
        }
        $orderBy .= $sortType;
        //排序结束
        $total = $query->count();
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)->limit($pages->limit)
                ->orderBy($orderBy)
                ->asArray()->all();
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        echo json_encode($returnArr);
    }

    /**
     * 按条件导出二级维护记录
     */
    public function actionExportWidthCondition()
    {
        $query = CarSecondMaintenance::find()
                ->select([
                    '{{%car}}.`plate_number`',
                    '{{%car_second_maintenance}}.`number`',
                    '{{%car_second_maintenance}}.`current_date`',
                    '{{%car_second_maintenance}}.`next_date`',
                    '{{%car_second_maintenance}}.`add_datetime`',
                    '{{%admin}}.`username`',
                ])
                ->joinWith('car',false,'LEFT JOIN')
                ->joinWith('admin',false,'LEFT JOIN')
                ->where(['=','{{%car_second_maintenance}}.`is_del`',0]);
        //查询条件
        $query->andFilterWhere([
            'like',
            '{{%car}}.`plate_number`',
            yii::$app->request->get('plate_number')
        ]);
        $query->andFilterWhere([
            'like',
            '{{%car_second_maintenance}}.`number`',
            yii::$app->request->get('number')
        ]);
        //查询条件
        $data = $query->asArray()->all();
        $excel = new Excel();
        $excel->setHeader([
            'creator'=>'皓峰通讯',
            'lastModifiedBy'=>'hao feng tong xun',
            'title'=>'car two level record',
            'subject'=>'car two level record',
            'description'=>'car two level record list',
            'keywords'=>'car two level record list',
            'category'=>'car two level record list'
        ]);
        $lineData = [
            ['content'=>'车牌号','font-weight'=>true,'width'=>'20'],
            ['content'=>'维护卡号','font-weight'=>true,'width'=>'20'],
            ['content'=>'本次维护时间','font-weight'=>true,'width'=>'20'],
            ['content'=>'下次维护时间','font-weight'=>true,'width'=>'20'],
            ['content'=>'上次修改时间','font-weight'=>true,'width'=>'20'],
            ['content'=>'操作账号','font-weight'=>true,'width'=>'20'],
        ];
        $excel->addLineToExcel($lineData);
        foreach($data as $val){
            $val['current_date'] = date('Y-m-d',$val['current_date']);
            $val['next_date'] = date('Y-m-d',$val['next_date']);
            $val['add_datetime'] = $val['add_datetime'] ? date('Y-m-d H:i:s',$val['add_datetime']) : '';
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
        header("Content-Disposition: attachment; filename=".iconv('utf-8','gbk','车辆二级维护记录列表.xls')); 
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }

}