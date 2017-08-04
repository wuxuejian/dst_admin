<?php
/**
 * 车辆出租管理控制器
 * @time 2015/10/10 10:16
 * @author wangmin
 */
namespace backend\modules\car\controllers;
use backend\models\CustomerCompany;
use backend\models\CustomerPersonal;
use yii;
use yii\data\Pagination;
use backend\controllers\BaseController;
use backend\models\Car;
use backend\models\CarLetRecord;
use backend\models\CarLetContract;
use backend\models\ConfigCategory;
use common\models\Excel;
class LetController extends BaseController
{
    public function actionIndex()
    {
        $buttons = $this->getCurrentActionBtn();
        //获取车辆出租状态
        $config = (new ConfigCategory)->getCategoryConfig(['car_let_status','customer_type','car_model_name'],'value');
        //查询表单select选项
        $searchFormOptions = [];
        if($config['customer_type']){
            $searchFormOptions['customer_type'] = [];
            $searchFormOptions['customer_type'][] = ['value'=>'','text'=>'不限'];
            foreach($config['customer_type'] as $val){
                $searchFormOptions['customer_type'][] = ['value'=>$val['value'],'text'=>$val['text']];
            }
        }
        return $this->render('index',[
            'buttons'=>$buttons,
            'config'=>$config,
            'searchFormOptions'=>$searchFormOptions
        ]);
    }

    /**
     * 获取车辆出租信息列表
     */
    public function actionGetList()
    {
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $query = CarLetRecord::find()
                ->select([
                    '{{%car_let_record}}.*',
                    '{{%car}}.`plate_number`',
                	'{{%car}}.`brand_id`',
                	'{{%car}}.`car_model`',
                    'contract_number'=>'{{%car_let_contract}}.`number`',
                    '{{%car_let_contract}}.`customer_type`',
                	'{{%car_let_contract}}.`contract_type`',
                    'cCustomer_name'=>'{{%customer_company}}.`company_name`',
                	'cKeeper_mobile'=>'{{%customer_company}}.`keeper_mobile`',
                    'pCustomer_name'=>'{{%customer_personal}}.`id_name`',
                	'pKeeper_mobile'=>'{{%customer_personal}}.`mobile`',
                    'salesperson'=>'{{%car_let_contract}}.`salesperson`',
                ])
                ->joinWith('car',false,'LEFT JOIN')
                ->joinWith('letContract',false,'LEFT JOIN')
                ->joinWith('customerCompany',false,'LEFT JOIN')  //查企业客户
                ->joinWith('customerPersonal',false,'LEFT JOIN') //查个人客户
                ->where(['{{%car_let_record}}.is_del'=>0]);
        //////查询条件开始
        $contract_type = yii::$app->request->get('contract_type');
        if($contract_type){
        	$query->andFilterWhere(['=','{{%car_let_contract}}.`contract_type`',$contract_type]);
        }
        $query->andFilterWhere(['like','{{%car}}.`plate_number`',yii::$app->request->get('plate_number')]);
        $car_model_name = yii::$app->request->get('car_model_name');
        if($car_model_name){
        	$connection = yii::$app->db;
        	$car_models = $connection->createCommand(
        			"select value from cs_config_item
        			where belongs_id=62 and is_del=0 and text = :car_model_name"
        	)->bindValues([':car_model_name'=>$car_model_name])
        	->queryAll();
        	$car_models1 = array();
        	foreach ($car_models as $row){
        		array_push($car_models1, $row['value']);
        	}
        	$query->andFilterWhere(['in','{{%car}}.`car_model`',$car_models1]);
        }
        $query->andFilterWhere(['like','{{%car_let_contract}}.`number`',yii::$app->request->get('contract_number')]);
        $query->andFilterWhere([
            'or',
            ['like','{{%customer_company}}.`company_name`',yii::$app->request->get('customer_name')],
            ['like','{{%customer_personal}}.`id_name`',yii::$app->request->get('customer_name')]
        ]);
        $query->andFilterWhere(['like','{{%car_let_contract}}.`customer_type`',yii::$app->request->get('customer_type')]);
        $letStatus = yii::$app->request->get('let_status');
        if($letStatus){
            switch($letStatus){
                case 'LETING':
                    $query->andFilterWhere(['{{%car_let_record}}.`back_time`'=>0]); break;
                case 'BACKED':
                    $query->andFilterWhere(['>','{{%car_let_record}}.`back_time`',0]); break;
            }
        }
        $letTimeStart = yii::$app->request->get('let_time_start');
        if($letTimeStart){
            $query->andFilterWhere([
                '>=',
                '{{%car_let_record}}.`let_time`',
                strtotime($letTimeStart)
            ]);
        }
        $letTimeEnd = yii::$app->request->get('let_time_end');
        if($letTimeEnd){
            $query->andFilterWhere([
                '<=',
                '{{%car_let_record}}.`let_time`',
                strtotime($letTimeEnd)
            ]);
        }
        $backTimeStart = yii::$app->request->get('back_time_start');
        if($backTimeStart){
            $query->andFilterWhere([
                '>=',
                '{{%car_let_record}}.`back_time`',
                strtotime($backTimeStart)
            ]);
        }
        $backTimeEnd = yii::$app->request->get('back_time_end');
        if($backTimeEnd){
            $query->andFilterWhere([
                '<=',
                '{{%car_let_record}}.`back_time`',
                strtotime($backTimeEnd)
            ]);
        }
        
        //车辆品牌  by  2016/9/20
        $brand_id = yii::$app->request->get('brand_id');
        if($brand_id)
        {
        	$brandQuery = (new \yii\db\Query())->select('id')->from('cs_car_brand')->where('id=:id OR pid=:pid',[':id'=>$brand_id,':pid'=>$brand_id]);
        	$query->andWhere(['{{%car}}.`brand_id`' => $brandQuery]);
        }
        
        //检测是否要根据当前登录人员所属运营公司来显示列表数据-20160326
        $isLimitedArr = CarLetContract::isLimitedToShowByAdminOperatingCompany();
        if(is_array($isLimitedArr) && isset($isLimitedArr['status']) && $isLimitedArr['status']){
            $query->andWhere("{{%car}}.`operating_company_id` in ({$isLimitedArr['adminInfo_operatingCompanyId']})");
        }
        //////查询条件结束
//         echo $query->createCommand()->getRawSql();exit;
        //排序开始
        $sortColumn = yii::$app->request->get('sort');
        $sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
        $orderBy = '';
        if($sortColumn){
            switch ($sortColumn) {
                case 'plate_number':
                    $orderBy = '{{%car}}.`plate_number` ';
                    break;
                case 'contract_number':
                    $orderBy = '{{%car_let_contract}}.`number` ';
                    break;
                case 'customer_type':
                    $orderBy = '{{%car_let_contract}}.`customer_type` ';
                    break;
                case 'customer_name':
                    $orderBy = '{{%customer_company}}.`company_name` ';
                    break;
                default:
                    $orderBy = "{{%car_let_record}}.`{$sortColumn}` ";
                    break;
            }
        }else{
           $orderBy = '{{%car_let_record}}.`id` ';
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
     * 车辆出租登记
     */
    /*public function actionRegister()
    {
        //data submit start
        if(yii::$app->request->isPost){
            $model = new CarLet;
            $model->load(yii::$app->request->post(),'');
            $returnArr = [];
            $returnArr['status'] = true;
            $returnArr['info'] = '';
            if($model->validate()){
                $model->reg_time = time();
                $model->status = 'LETING';
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '登记成功！';
                    //修改车辆状态为已出租
                    Car::updateAll(['status'=>'LETING'],['id'=>$model->car_id]);
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '登记失败！';
                }
            }else{
                $returnArr['status'] = false;
                $errors = $model->getErrors();
                if($errors){
                    foreach($errors as $val){
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
        //查询所有可用车辆车牌号
        $car = Car::find()->select(['id','plate_number'])->where(['status'=>'USABLE'])->asArray()->all();
        return $this->render('register',[
            'car'=>$car,
        ]);
    }*/

    /**
     * 出租登记时获取客户列表
     */
    /*public function actionGetCustomerList()
    {
        $customer = Customer::find()
                    ->select(['id','number'])
                    ->where(['is_del'=>0])
                    ->asArray()->all();
        echo json_encode($customer);
    }*/

    /**
     * 车辆出租信息修改
     */
    /*public function actionEdit()
    {
        //data submit start
        if(yii::$app->request->isPost){
            $id = yii::$app->request->post('id') or die('param id is required');
            $model = CarLet::findOne(['id'=>$id]);
            $model or die('record is not found');
            $model->setScenario('edit');
            $model->load(yii::$app->request->post(),'');
            if($model->getOldAttribute('car_id') != $model->car_id){
                $markChangeCarId = true;
            }else{
                $markChangeCarId = false;
            }
            if($model->validate()){
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '登记信息修改成功！';
                    //如果修改了出租车辆
                    if($markChangeCarId){
                        Car::updateAll(['status'=>'USABLE'],['id'=>$model->getOldAttribute('car_id')]);
                        Car::updateAll(['status'=>'LETING'],['id'=>$model->car_id]);
                    }
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '登记信息修改失败！';
                }
            }else{
                $returnArr['status'] = false;
                $errors = $model->getErrors();
                if($errors){
                    foreach($errors as $val){
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
        $id = yii::$app->request->get('id') or die('param id is required');
        $let = CarLet::find()->where(['id'=>$id])->asArray()->one();
        $let or die('record is not found');
        //查询所有可租车辆信息和当前所租车辆信息
        $car = Car::find()->select(['id','plate_number'])
               ->where(['status'=>'USABLE'])
               ->orWhere(['id'=>$let['car_id']])
               ->asArray()->all();
        //查询所有可用客户
        $customer = Customer::find()
                    ->select(['id','number'])
                    ->where(['is_del'=>0])
                    ->asArray()->all();
        return $this->render('edit',[
            'let'=>$let,
            'car'=>$car,
            'customer'=>$customer
        ]);
    }*/
    
    /**
     * 车辆归还
     */
    /*public function actionBack()
    {
        //data submit start
        if(yii::$app->request->isPost){
            $id = yii::$app->request->post('id') or die('param id is required');
            $model = CarLet::findOne(['id'=>$id]);
            $model or die('record not found');
            if($model->getOldAttribute('status') != 'LETING'){
                die('只处理状态为leting的记录！');
            }
            $model->setScenario('back');
            $model->true_rent = yii::$app->request->post('true_rent');
            $model->fine = yii::$app->request->post('fine');
            $model->note = yii::$app->request->post('note');
            $returnArr = [];
            $returnArr['status'] = true;
            $returnArr['info'] = [];
            if($model->validate()){
                $model->status = 'HASBACK';
                $model->true_back_time = time();
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '车辆归还成功！';
                    //成功归还，还原车辆状态
                    Car::updateAll(['status'=>'USABLE'],['id'=>$model->getOldAttribute('car_id')]);
                }else{
                    $returnArr['info'] = '车辆归还失败！';
                }
            }else{
                $returnArr['status'] = false;
                $errors = $model->getErrors();
                if($errors){
                    foreach($errors as $val){
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
        $id = yii::$app->request->get('id') or die('param id is required');
        $letInfo = CarLet::find()
                ->select([
                    CarLet::tableName().'.*',
                    Car::tableName().'.`plate_number`',
                    'customer_number'=>Customer::tableName().'.`number`'
                ])
                ->joinWith('car',false,'LEFT JOIN')
                ->joinWith('customer',false,'LEFT JOIN')
                ->where([CarLet::tableName().'.`id`'=>$id])->asArray()->one();
        $letInfo or die('record not found');
        if($letInfo['status'] == 'HASBACK'){
            //车辆已经作了归还处理
            die('已经归还，无法处理！');
        }
        return $this->render('back',[
            'letInfo'=>$letInfo
        ]);
    }*/

    /**
     * 租车记录导出excel
     */
    public function actionExportWidthCondition()
    {
        set_time_limit(0);
        $query = CarLetRecord::find()
                ->select([
                    '{{%car}}.`plate_number`',
                	'{{%car}}.`car_model`',
                    '{{%car_let_contract}}.`number`',
                    'customer_name'=>'{{%car_let_contract}}.`cCustomer_id`', //作为'客户名称'
                	'customer_mobile'=>'{{%car_let_contract}}.`cCustomer_id`', //作为'客户负责人电话'
                    '{{%car_let_contract}}.`customer_type`',
                    '{{%car_let_record}}.`month_rent`',
                    '{{%car_let_record}}.`let_time`',
                    'let_status'=>'{{%car_let_record}}.`back_time`', //作为'出租状态'判断是否已退租
                    '{{%car_let_record}}.`back_time`',
                    '{{%car_let_record}}.`note`',
                    'cCustomer_name'=>'{{%customer_company}}.`company_name`',
                    'pCustomer_name'=>'{{%customer_personal}}.`id_name`',
                	'cKeeper_mobile'=>'{{%customer_company}}.`keeper_mobile`',
                	'pKeeper_mobile'=>'{{%customer_personal}}.`mobile`',
                    'salesperson'=>'{{%car_let_contract}}.`salesperson`',
                ])
                ->joinWith('car',false,'LEFT JOIN')
                ->joinWith('letContract',false,'LEFT JOIN')
                ->joinWith('customerCompany',false,'LEFT JOIN')  //查企业客户
                ->joinWith('customerPersonal',false,'LEFT JOIN') //查个人客户
                ->where(['{{%car_let_record}}.is_del'=>0]);
        //查询条件
        $query->andFilterWhere(['like','{{%car}}.`plate_number`',yii::$app->request->get('plate_number')]);
        $car_model_name = yii::$app->request->get('car_model_name');
        if($car_model_name){
        	$connection = yii::$app->db;
        	$car_models = $connection->createCommand(
        			"select value from cs_config_item
        			where belongs_id=62 and is_del=0 and text = :car_model_name"
        	)->bindValues([':car_model_name'=>$car_model_name])
        	->queryAll();
        	$car_models1 = array();
        	foreach ($car_models as $row){
        		array_push($car_models1, $row['value']);
        	}
        	$query->andFilterWhere(['in','{{%car}}.`car_model`',$car_models1]);
        	//         	echo $query->createCommand()->getRawSql();exit;
        }
        $query->andFilterWhere(['like','{{%car_let_contract}}.`number`',yii::$app->request->get('contract_number')]);
        $query->andFilterWhere([
            'or',
            ['like','{{%customer_company}}.`company_name`',yii::$app->request->get('customer_name')],
            ['like','{{%customer_personal}}.`id_name`',yii::$app->request->get('customer_name')]
        ]);
        $query->andFilterWhere(['like','{{%car_let_contract}}.`customer_type`',yii::$app->request->get('customer_type')]);
        $letStatus = yii::$app->request->get('let_status');
        if($letStatus){
            switch($letStatus){
                case 'LETING':
                    $query->andFilterWhere(['{{%car_let_record}}.`back_time`'=>0]); break;
                case 'BACKED':
                    $query->andFilterWhere(['>','{{%car_let_record}}.`back_time`',0]); break;
            }
        }
        $letTimeStart = yii::$app->request->get('let_time_start');
        if($letTimeStart){
            $query->andFilterWhere([
                '>=',
                '{{%car_let_record}}.`let_time`',
                strtotime($letTimeStart)
            ]);
        }
        $letTimeEnd = yii::$app->request->get('let_time_end');
        if($letTimeEnd){
            $query->andFilterWhere([
                '<=',
                '{{%car_let_record}}.`let_time`',
                strtotime($letTimeEnd)
            ]);
        }
        $backTimeStart = yii::$app->request->get('back_time_start');
        if($backTimeStart){
            $query->andFilterWhere([
                '>=',
                '{{%car_let_record}}.`back_time`',
                strtotime($backTimeStart)
            ]);
        }
        $backTimeEnd = yii::$app->request->get('back_time_end');
        if($backTimeEnd){
            $query->andFilterWhere([
                '<=',
                '{{%car_let_record}}.`back_time`',
                strtotime($backTimeEnd)
            ]);
        }
        //车辆品牌  by  2016/9/20
        $brand_id = yii::$app->request->get('brand_id');
        if($brand_id)
        {
            $brandQuery = (new \yii\db\Query())->select('id')->from('cs_car_brand')->where('id=:id OR pid=:pid',[':id'=>$brand_id,':pid'=>$brand_id]);
            $query->andWhere(['{{%car}}.`brand_id`' => $brandQuery]);
        }
        //查询条件
        $data = $query->asArray()->all();
        $excel = new Excel();
        $excel->setHeader([
            'creator'=>'皓峰通讯',
            'lastModifiedBy'=>'hao feng tong xun',
            'title'=>'car_let_record',
            'subject'=>'car_let_record',
            'description'=>'car_let_record list',
            'keywords'=>'car_let_record list',
            'category'=>'car_let_record list'
        ]);
        //获取车辆出租状态
        $lineData = [
            ['content'=>'车牌号','font-weight'=>true,'width'=>'20'],
            ['content'=>'车型名称','font-weight'=>true,'width'=>'20'],
            ['content'=>'合同编号','font-weight'=>true,'width'=>'20'],
            ['content'=>'承租客户','font-weight'=>true,'width'=>'20'],
            ['content'=>'车管负责人电话','font-weight'=>true,'width'=>'20'],
            ['content'=>'客户类型','font-weight'=>true,'width'=>'20'],
            ['content'=>'月租金','font-weight'=>true,'width'=>'10'],
            ['content'=>'出租时间','font-weight'=>true,'width'=>'24'],
            ['content'=>'出租状态','font-weight'=>true,'width'=>'24'],
            ['content'=>'还车时间','font-weight'=>true,'width'=>'24'],
            ['content'=>'备注','font-weight'=>true,'width'=>'60'],
            ['content'=>'归属销售员','font-weight'=>true,'width'=>'24'],
        ];
        $excel->addLineToExcel($lineData);

        $configItems = ['customer_type','car_model_name'];
        $configs = (new ConfigCategory())->getCategoryConfig($configItems,'value');

        foreach($data as $val){
        	$val['car_model'] = @$configs['car_model_name'][$val['car_model']]['text'];
            $val['let_time'] = $val['let_time'] ? date('Y-m-d H:i:s',$val['let_time']) : '';
            $val['back_time'] = $val['back_time'] ? date('Y-m-d H:i:s',$val['back_time']) : '';
            // 各combox配置项以txt代替val
            foreach($configItems as $conf) {
                if(isset($val[$conf]) && $val[$conf]) {
                    $val[$conf] = $configs[$conf][$val[$conf]]['text'];
                }
            }
            $val['let_status'] = $val['let_status']>0 ? '已退租' : '出租中';
            $val['customer_name'] = $val['cCustomer_name']!='' ? $val['cCustomer_name'] : $val['pCustomer_name'];
            $val['customer_mobile'] = $val['cKeeper_mobile']!='' ? $val['cKeeper_mobile'] : $val['pKeeper_mobile'];
            unset($val['cCustomer_name']);
            unset($val['pCustomer_name']);
            unset($val['cKeeper_mobile']);
            unset($val['pKeeper_mobile']);
            $lineData = [];
            foreach($val as $key=>$v){
                if($key == 'month_rent'){
                    $lineData[] = ['content'=>$v,'align'=>'right'];
                }else{
                    $lineData[] = ['content'=>$v,'align'=>'left'];
                }
            }
            $excel->addLineToExcel($lineData);
        }
        unset($data);

        $objPHPExcel = $excel->getPHPExcel();
        header("Content-type: application/octet-stream"); 
        header("Accept-Ranges: bytes"); 
        //header("Accept-Length:".$fileSize); 
        header("Content-Disposition: attachment; filename=".iconv('utf-8','gbk','车辆出租列表.xls')); 
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }
}