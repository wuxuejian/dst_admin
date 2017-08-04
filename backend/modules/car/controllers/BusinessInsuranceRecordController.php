<?php
/**
 * 车辆商业保险记录控制器
 * time    2014/10/21 11:47
 * @author wangmin
 */
namespace backend\modules\car\controllers;
use backend\controllers\BaseController;
use backend\models\Car;
use backend\models\CarInsuranceBusiness;
use backend\models\ConfigCategory;
use common\models\Excel;
use yii;
use yii\data\Pagination;
class BusinessInsuranceRecordController extends BaseController
{
    public function actionIndex()
    {
        $buttons = $this->getCurrentActionBtn();
        $config = (new ConfigCategory)->getCategoryConfig(['INSURANCE_COMPANY'],'value');
        $insurerCompany = [
            ['value'=>'','text'=>'不限']
        ];
        if($config['INSURANCE_COMPANY']){
            foreach($config['INSURANCE_COMPANY'] as $v){
                $insurerCompany[] = ['value'=>$v['value'],'text'=>$v['text']];
            }
        }
        return $this->render('index',[
            'buttons'=>$buttons,
            'config'=>$config,
            'insurerCompany'=>$insurerCompany,
        ]);
    }

    public function actionGetList()
    {
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $query = CarInsuranceBusiness::find()
                ->select([
                    '{{%car_insurance_business}}.*',
                    '{{%car}}.`plate_number`',
                    '{{%admin}}.`username`'
                ])
                ->joinWith('car',false,'LEFT JOIN')
                ->joinWith('admin',false,'LEFT JOIN')
                ->where(['=','{{%car_insurance_business}}.`is_del`',0]);
        $query->andFilterWhere([
        		'=',
        		Car::tableName().'.`is_del`',
        		0
        		]);
        //查询条件
        $query->andFilterWhere([
            'like',
            '{{%car}}.`plate_number`',
            yii::$app->request->get('plate_number')
        ]);
        $query->andFilterWhere([
            '=',
            '{{%car_insurance_business}}.`insurer_company`',
            yii::$app->request->get('insurer_company')
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
                    $orderBy = '{{%car_insurance_business}}.`'.$sortColumn.'` ';
                    break;
            }
        }else{
           $orderBy = '{{%car_insurance_business}}.`id` ';
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
     * 按条件导出交强险记录
     */
    public function actionExportWidthCondition()
    {
        $query = CarInsuranceBusiness::find()
                ->select([
                    '{{%car}}.`plate_number`',
                    '{{%car_insurance_business}}.`insurer_company`',
                    '{{%car_insurance_business}}.`money_amount`',
                    '{{%car_insurance_business}}.`start_date`',
                    '{{%car_insurance_business}}.`end_date`',
                    '{{%car_insurance_business}}.`add_datetime`',
                    '{{%admin}}.`username`',
                ])
                ->joinWith('car',false,'LEFT JOIN')
                ->joinWith('admin',false,'LEFT JOIN')
                ->where(['=','{{%car_insurance_business}}.`is_del`',0]);
        //查询条件
        $query->andFilterWhere([
            'like',
            '{{%car}}.`plate_number`',
            yii::$app->request->get('plate_number')
        ]);
        $query->andFilterWhere([
            'like',
            '{{%car_insurance_business}}..`insurer_company`',
            yii::$app->request->get('insurer_company')
        ]);
        //查询条件
        $data = $query->asArray()->all();
        $config = (new ConfigCategory)->getCategoryConfig(['INSURANCE_COMPANY'],'value');
        $excel = new Excel();
        $excel->setHeader([
            'creator'=>'皓峰通讯',
            'lastModifiedBy'=>'hao feng tong xun',
            'title'=>'business insurance record',
            'subject'=>'business insurance record',
            'description'=>'business insurance record list',
            'keywords'=>'business insurance record list',
            'category'=>'business insurance record list'
        ]);
        $lineData = [
            ['content'=>'车牌号','font-weight'=>true,'width'=>'20'],
            ['content'=>'保险公司','font-weight'=>true,'width'=>'40'],
            ['content'=>'保险金额','font-weight'=>true,'width'=>'20'],
            ['content'=>'开始日期','font-weight'=>true,'width'=>'20'],
            ['content'=>'结束日期','font-weight'=>true,'width'=>'20'],
            ['content'=>'上次修改时间','font-weight'=>true,'width'=>'20'],
            ['content'=>'操作账号','font-weight'=>true,'width'=>'20'],
        ];
        $excel->addLineToExcel($lineData);
        foreach($data as $val){
            $val['insurer_company'] = $config['INSURANCE_COMPANY'][$val['insurer_company']]['text'];
            $val['start_date'] = date('Y-m-d',$val['start_date']);
            $val['end_date'] = date('Y-m-d',$val['end_date']);
            $val['add_datetime'] = $val['add_datetime'] ? date('Y-m-d H:i:s',$val['add_datetime']) : '';
            $lineData = [];
            foreach($val as $k=>$v){
                if($k == 'money_amount'){
                    $lineData[] = ['content'=>$v,'align'=>'right'];
                }else{
                    $lineData[] = ['content'=>$v,'align'=>'left'];
                }
                
            }
            $excel->addLineToExcel($lineData);
        }
        $objPHPExcel = $excel->getPHPExcel();
        header("Content-type: application/octet-stream"); 
        header("Accept-Ranges: bytes"); 
        //header("Accept-Length:".$fileSize); 
        header("Content-Disposition: attachment; filename=".iconv('utf-8','gbk','车辆商业险记录列表.xls')); 
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }
}