<?php
/**
 * 合同续费管理 控制器
 * time: 2015/01/11 11:03
 * @author wangmin
 */
namespace backend\modules\customer\controllers;
use backend\controllers\BaseController;
use backend\models\CustomerCompany;
use backend\models\Car;
use backend\models\CarLetContract;
use backend\models\CarLetRecord;
use backend\models\CarLetContractRenewRecord;
use backend\models\Admin;
use backend\classes\UserLog;//日志类
use common\models\Excel;
use yii;
use yii\data\Pagination;
use backend\models\ConfigCategory;

class ContractRenewController extends BaseController
{
    public function actionIndex()
    {
        $buttons = $this->getCurrentActionBtn();
        return $this->render('index',[
            'buttons'=>$buttons
        ]);
    }

    /**
     * 获取合同续费列表
     */
    public function actionGetList()
    {
        $query = CarLetContractRenewRecord::find()
                ->select([
                    CarLetContractRenewRecord::tableName().'.*',
                    'contract_number'=>CarLetContract::tableName().'.`number`',
                    'customer_name'=>CustomerCompany::tableName().'.`company_name`',
                    'admin_name'=>Admin::tableName().'.`name`'
                ])
                ->joinWith('carLetContract',false,'LEFT JOIN')
                ->joinWith('customerCompany',false,'LEFT JOIN')
                ->joinWith('admin',false,'LEFT JOIN');
        //查询条件
        $query->andFilterWhere(['LIKE',CarLetContract::tableName().'.`number`',yii::$app->request->get('contract_number')]);
        $query->andFilterWhere(['LIKE',CustomerCompany::tableName().'.`company_name`',yii::$app->request->get('customer_name')]);
        $total = $query->count();
        $query2 = clone $query; // 底部合计用
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)->limit($pages->limit)
                ->orderBy(CarLetContractRenewRecord::tableName().'.`id` desc')
                ->asArray()->all();
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        // 表格底部增加合计行
        $mySum = $query2->select(['should_money'=>'SUM(should_money)','true_money'=>'SUM(true_money)' ])->asArray()->one();
        $returnArr['footer'] = [[
            'contract_number'=>'合计：',
            'should_money'=>$mySum['should_money'],
            'true_money'=>$mySum['true_money']
        ]];
        echo json_encode($returnArr);
    }

    /**
     * 获取出租合同列表（续费合同combogrid）
     */
    /*public function actionGetContractList(){
        $queryStr = isset($_REQUEST['q']) ? trim($_REQUEST['q']) : ''; // combogrid检索过滤字符串
        $contractId = isset($_REQUEST['contractId']) ? intval($_REQUEST['contractId']) : 0; // 赋值时用
        $query = CarLetContract::find()
            ->select([
                'contract_id'=>'{{%car_let_contract}}.id',
                'contract_number'=>'{{%car_let_contract}}.number',
                'customer_name'=>'{{%customer_company}}.company_name'
            ])
            ->joinWith(['customerCompany'],false,'LEFT JOIN')
            ->where(['{{%car_let_contract}}.is_del'=>0]);
        if($contractId){ // 查询某合同赋值
            $total = $query->andWhere(['{{%car_let_contract}}.id'=>$contractId])->count();
        }elseif($queryStr){ // 检索过滤时
            $total = $query->andWhere([
                'or',
                ['like', '{{%car_let_contract}}.number', $queryStr],
                ['like', '{{%customer_company}}.company_name', $queryStr]
            ])
                ->count();
        }else{ // 默认查询
            $total = $query->count();
        }
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount' => $total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        return json_encode($returnArr);
    }*/

    /**
     * 新增续费记录
     */
    public function actionRenewAdd()
    {
        //data submit
        if(yii::$app->request->isPost){
            $contractId = yii::$app->request->post('contract_id') or die('param contractId is required');
            $contractInfo = CarLetContract::find()
                ->select(['number','customer_id'])
                ->where(['id'=>$contractId])
                ->asArray()->one();
            if(!$contractInfo){
                echo json_encode(['status'=>false,'info'=>'未找到对应的合同记录！']);
                die;
            }
            $letCar = CarLetRecord::find()
                    ->select(['month_rent'])
                    ->where(['back_time'=>0,'contract_id'=>$contractId])
                    ->asArray()->all();
            $shouldMoney = 0.00;
            if($letCar){
                $shouldMoney = array_sum(array_column($letCar,'month_rent'));
            }
            $model = new CarLetContractRenewRecord();
            $model->load(yii::$app->request->post(),'');
            $returnArr = [];
            $returnArr['status'] = true;
            $returnArr['info'] = '';
            if($model->validate()){
                //更新合同续费时间
                //这里可能会使用到事务处理(待定...)
                if(!CarLetContract::updateAll(['cost_expire_time'=>$model->cost_expire_time],['id'=>$model->contract_id])){
                    $returnArr['status'] = false;
                    $returnArr['info'] = '更新合同续费时间失败！';
                }else{
                    $model->admin_id = $_SESSION['backend']['adminInfo']['id'];
                    $model->should_money = $shouldMoney;
                    $model->action_time = time();
                    $model->customer_id = $contractInfo['customer_id'];
                    if($model->save(false)){
                        $returnArr['status'] = true;
                        $returnArr['info'] = '合同续费记录添加成功！';
                        //添加操作日志
                        $logInfo = '新增续费记录(合同编号：'
                            . $contractInfo['number'] .
                            ')';
                        UserLog::log($logInfo,'sys');
                    }else{
                        $returnArr['status'] = false;
                        $returnArr['info'] = '合同续费记录添加失败！';
                    }
                }
            }else{
                $returnArr['status'] = false;
                $errors = $model->getErrors();
                if($errors){
                    foreach($errors as $v){
                        $returnArr['info'] .= $v[0];
                    }
                }else{
                    $returnArr['info'] = '未知错误！';
                }
            }
            return json_encode($returnArr);
        } else { // 访问‘添加合同续费窗口’视图
            $contractId = intval(yii::$app->request->get('contractId'));
            $configItems = ['customer_type'];
            $config = (new ConfigCategory())->getCategoryConfig($configItems,'value');
            return $this->render('renew-add', [
                'contractId' => $contractId,
                'config'=>$config
            ]);
        }
    }

    /**
     * 获取某合同的出租车辆明细
     */
    public function actionGetContractCars()
    {
        $contractId = intval(yii::$app->request->get('contractId'));
        $data = CarLetRecord::find()
            ->select([
                CarLetRecord::tableName() . '.`month_rent`',
                CarLetRecord::tableName() . '.`let_time`',
                CarLetRecord::tableName() . '.`note`',
                Car::tableName() . '.`plate_number`'
            ])->joinWith('car', false, 'LEFT JOIN')
            ->andwhere([
                '=',
                CarLetRecord::tableName() . '.`back_time`',
                0
            ])
            ->andwhere([
                '=',
                CarLetRecord::tableName() . '.`contract_id`',
                $contractId
            ])->asArray()->all();
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = count($data);
        echo json_encode($returnArr);
    }

    /**
     * 按条件导出合同续费记录
     */
    public function actionExportWidthCondition()
    {
        $query = CarLetContract::find()
                ->select([
                    '{{%customer_company}}.`company_name`',
                    '{{%car_let_contract}}.`number`',
                    '{{%car_let_contract}}.`sign_date`',
                    '{{%car_let_contract}}.`start_time`',
                    '{{%car_let_contract}}.`end_time`',
                    '{{%car_let_contract}}.`due_time`',
                    '{{%car_let_contract}}.`bail`',
                    '{{%car_let_contract}}.`cost_expire_time`',
                    '{{%car_let_contract}}.`reg_time`',
                    '{{%car_let_contract}}.`note`',
                    '{{%car_let_contract}}.`last_modify_datetime`',
                    '{{%admin}}.`username`',
                ])
                ->joinWith('customerCompany',false,'LEFT JOIN')
                ->joinWith('admin',false,'LEFT JOIN');
        //查询条件
        $query->andFilterWhere([
            '=',
            '{{%car_let_contract}}.`number`',
            yii::$app->request->get('number')
        ]);
        $query->andFilterWhere([
            'like',
            '{{%customer_company}}.`company_name`',
            yii::$app->request->get('company_name')
        ]);
        //查询条件
        $data = $query->asArray()->all();
        $excel = new Excel();
        $excel->setHeader([
            'creator'=>'皓峰通讯',
            'lastModifiedBy'=>'hao feng tong xun',
            'title'=>'contract record',
            'subject'=>'contract record',
            'description'=>'contract record list',
            'keywords'=>'contract record list',
            'category'=>'contract record list'
        ]);
        $lineData = [
            ['content'=>'客户名称','font-weight'=>true,'width'=>'20'],
            ['content'=>'合同编号','font-weight'=>true,'width'=>'20'],
            ['content'=>'签订时间','font-weight'=>true,'width'=>'20'],
            ['content'=>'开始时间','font-weight'=>true,'width'=>'20'],
            ['content'=>'结束时间','font-weight'=>true,'width'=>'20'],
            ['content'=>'合同期限','font-weight'=>true,'width'=>'20'],
            ['content'=>'保证金','font-weight'=>true,'width'=>'20'],
            ['content'=>'费用到期时间','font-weight'=>true,'width'=>'20'],
            ['content'=>'登记时间','font-weight'=>true,'width'=>'20'],
            ['content'=>'备注','font-weight'=>true,'width'=>'50'],
            ['content'=>'上次操作时间','font-weight'=>true,'width'=>'50'],
            ['content'=>'操作账号','font-weight'=>true,'width'=>'50'],
        ];
        $excel->addLineToExcel($lineData);
        foreach($data as $val){
            $val['due_time'] = $val['due_time'] ? date('Y-m-d',$val['due_time']) : '';
            $val['start_time'] = $val['start_time'] ? date('Y-m-d',$val['start_time']) : '';
            $val['end_time'] = $val['end_time'] ? date('Y-m-d',$val['end_time']) : '';
            $val['cost_expire_time'] = $val['cost_expire_time'] ? date('Y-m-d',$val['cost_expire_time']) : '未启动';
            $val['reg_time'] = $val['reg_time'] ? date('Y-m-d',$val['reg_time']) : '';
            $val['sign_date'] = $val['sign_date'] ? date('Y-m-d',$val['sign_date']) : '';
            $val['last_modify_datetime'] = $val['last_modify_datetime'] ? date('Y-m-d H:i:s',$val['last_modify_datetime']) : '';
            $lineData = [];
            foreach($val as $k=>$v){
                if($k == 'bail'){
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
        header("Content-Disposition: attachment; filename=".iconv('utf-8','gbk','客户订单合同列表.xls')); 
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');

        //添加导出日志
        UserLog::log('按条件导出合同','sys');
    }


}