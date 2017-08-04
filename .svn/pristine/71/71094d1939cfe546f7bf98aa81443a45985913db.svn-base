<?php
/**
 * 个人客户管理控制器
 * time: 2014/10/14 15:38
 * @author wangmin
 */
namespace backend\modules\customer\controllers;
use backend\controllers\BaseController;
use backend\models\CustomerPersonal;
use backend\models\ConfigCategory;
use common\models\Excel;
use yii;
use yii\data\Pagination;
class PersonalController extends BaseController
{
    public function actionIndex()
    {
        $buttons = $this->getCurrentActionBtn();
        return $this->render('index',[
            'buttons'=>$buttons
        ]);
    }

    /**
     * 获取个人客户列表
     */
    public function actionGetList()
    {
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $query = CustomerPersonal::find()
            ->select([
                '{{%customer_personal}}.*',
                'operating_company'=>'{{%operating_company}}.name'
            ])
            ->joinWith('operatingCompany',false)
            ->where(['{{%customer_personal}}.is_del'=>0]);
        //查询条件开始
        $query->andFilterWhere(['like','id_name',yii::$app->request->get('id_name')]);
        $query->andFilterWhere(['like','mobile',yii::$app->request->get('mobile')]);
        $query->andFilterWhere(['like','id_number',yii::$app->request->get('id_number')]);
        //检测是否要根据当前登录人员所属运营公司来显示列表数据-20160328
        $isLimitedArr = CustomerPersonal::isLimitedToShowByAdminOperatingCompany();
        if(is_array($isLimitedArr) && isset($isLimitedArr['status']) && $isLimitedArr['status']){
            $query->andWhere("{{%customer_personal}}.`operating_company_id` in ({$isLimitedArr['adminInfo_operatingCompanyId']})");
        }
        //查询条件结束
        //排序开始
        $sortColumn = yii::$app->request->get('sort');
        $sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
        $orderBy = '';
        if($sortColumn){
            switch($sortColumn){
                case 'operating_company':
                    $orderBy = "{{%operating_company}}.name ";
                    break;
                default:
                    $orderBy = "{{%customer_personal}}.$sortColumn ";
            }
        }else{
            $orderBy = '{{%customer_personal}}.`id` ';
        }
        $orderBy .= $sortType;
        //排序结束
        $total = $query->count();
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query
                ->offset($pages->offset)->limit($pages->limit)
                ->orderBy($orderBy)->asArray()->all();
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        echo json_encode($returnArr);
    }

    /**
     * 添加个人客户
     */
    public function actionAdd()
    {
        //data submit start
        if(yii::$app->request->isPost){
            $model = new CustomerPersonal;
            $model->load(yii::$app->request->post(),'');
            $model->operating_company_id = $_SESSION['backend']['adminInfo']['operating_company_id'];
            $returnArr = [];
            $returnArr['status'] = true;
            $returnArr['info'] = '';
            if($model->validate()){
                $model->number = date('YmdHis').str_pad(mt_rand(0,100),3,0);
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '个人客户添加成功！';
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '数据保存失败！';
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
        //获取配置
        $config = (new ConfigCategory)->getCategoryConfig(['driv_class']);
        return $this->render('add',[
            'config'=>$config
        ]);
    }

    /**
     * 修改个人客户信息
     */
    public function actionEdit()
    {
        //data submit start
        if(yii::$app->request->isPost){
            $id = yii::$app->request->post('id') or die('param id is required');
            $model = CustomerPersonal::findOne(['id'=>$id]);
            $model or die('record not found');
            //检查当前登录用户和要操作的企业客户的所属运营公司是否匹配-20160328
            $checkArr = CustomerPersonal::checkOperatingCompanyIsMatch($id);
            if(is_array($checkArr) && isset($checkArr['status']) && !$checkArr['status']){
                return json_encode(['status'=>false,'info'=>$checkArr['info']]);
            }
            $model->load(yii::$app->request->post(),'');
            $returnArr = [];
            $returnArr['status'] = true;
            $returnArr['info'] = '';
            if($model->validate()){
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '客户信息修改成功！';
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '客户信息修改失败！';
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
        $model = CustomerPersonal::findOne(['id'=>$id]);
        $model or die('record not found');
        $customerInfo = $model->getOldAttributes();
        if($customerInfo['driving_issue_date']){
            $customerInfo['driving_issue_date'] = date('Y-m-d',$customerInfo['driving_issue_date']);
        }else{
            $customerInfo['driving_issue_date'] = '';
        }
        if($customerInfo['driving_valid_from']){
            $customerInfo['driving_valid_from'] = date('Y-m-d',$customerInfo['driving_valid_from']);
        }else{
            $customerInfo['driving_valid_from'] = '';
        }
        //print_r($customerInfo);exit;
        $config = (new ConfigCategory)->getCategoryConfig(['driv_class']);
        return $this->render('edit',[
            'customerInfo'=>$customerInfo,
            'config'=>$config
        ]);
    }

    /**
     * 删除个人客户
     */
    public function actionRemove()
    {
        $id = yii::$app->request->get('id') or die('param id is required');
        //检查当前登录用户和要操作的企业客户的所属运营公司是否匹配-20160328
        $checkArr = CustomerPersonal::checkOperatingCompanyIsMatch($id);
        if(is_array($checkArr) && isset($checkArr['status']) && !$checkArr['status']){
            return json_encode(['status'=>false,'info'=>$checkArr['info']]);
        }
        $returnArr = [];
        $returnArr['status'] = true;
        $returnArr['info'] = '';
        if(CustomerPersonal::updateAll(['is_del'=>1],['id'=>$id])){
            $returnArr['status'] = true;
            $returnArr['info'] = '客户删除成功！';
        }else{
            $returnArr['status'] = false;
            $returnArr['info'] = '客户删除失败！';
        }
        echo json_encode($returnArr);
    }

    /**
     * 按条件导出
     */
    public function actionExportWidthCondition()
    {
        $query = CustomerPersonal::find()
            ->select([
                '{{%customer_personal}}.number',
                '{{%customer_personal}}.id_name',
                '{{%customer_personal}}.mobile',
                '{{%customer_personal}}.id_number',
                '{{%customer_personal}}.id_sex',
                '{{%customer_personal}}.id_address',
                '{{%customer_personal}}.telephone',
                '{{%customer_personal}}.qq',
                '{{%customer_personal}}.email',
                '{{%customer_personal}}.driving_number',
                '{{%customer_personal}}.driving_addr',
                '{{%customer_personal}}.driving_issue_date',
                '{{%customer_personal}}.driving_class',
                '{{%customer_personal}}.driving_valid_from',
                '{{%customer_personal}}.driving_valid_for',
                '{{%customer_personal}}.driving_issue_authority',
                'operating_company'=>'{{%operating_company}}.name'
            ])
            ->joinWith('operatingCompany',false)
            ->where(['{{%customer_personal}}.is_del'=>0]);
        //查询条件开始
        $query->andFilterWhere(['like','id_name',yii::$app->request->get('id_name')]);
        $query->andFilterWhere(['like','mobile',yii::$app->request->get('mobile')]);
        $query->andFilterWhere(['like','id_number',yii::$app->request->get('id_number')]);
        //检测是否要根据当前登录人员所属运营公司来显示列表数据-20160328
        $isLimitedArr = CustomerPersonal::isLimitedToShowByAdminOperatingCompany();
        if(is_array($isLimitedArr) && isset($isLimitedArr['status']) && $isLimitedArr['status']){
            $query->andWhere("{{%customer_personal}}.`operating_company_id` in ({$isLimitedArr['adminInfo_operatingCompanyId']})");
        }
        //查询条件结束
        $data = $query->asArray()->all();

        $excel = new Excel();
        $excel->setHeader([
            'creator'=>'皓峰通讯',
            'lastModifiedBy'=>'hao feng tong xun',
            'title'=>'customer personal',
            'subject'=>'customer personal',
            'description'=>'customer personal list',
            'keywords'=>'customer personal list',
            'category'=>'customer personal list'
        ]);
        $excHeaders = [
            [
                ['content'=>'客户号','font-weight'=>true,'width'=>'20'],
                ['content'=>'姓名','font-weight'=>true,'width'=>'15'],
                ['content'=>'手机','font-weight'=>true,'width'=>'15'],
                ['content'=>'身份证号','font-weight'=>true,'width'=>'30'],
                ['content'=>'性别','font-weight'=>true,'width'=>'10'],
                ['content'=>'住址','font-weight'=>true,'width'=>'25'],
                ['content'=>'固定电话','font-weight'=>true,'width'=>'15'],
                ['content'=>'QQ','font-weight'=>true,'width'=>'15'],
                ['content'=>'邮件','font-weight'=>true,'width'=>'15'],
                ['content'=>'驾驶证号','font-weight'=>true,'width'=>'15'],
                ['content'=>'驾驶证登记地址','font-weight'=>true,'width'=>'30'],
                ['content'=>'初次领证日期','font-weight'=>true,'width'=>'15'],
                ['content'=>'准驾车型','font-weight'=>true,'width'=>'15'],
                ['content'=>'有效起始日期','font-weight'=>true,'width'=>'15'],
                ['content'=>'有效期限','font-weight'=>true,'width'=>'15'],
                ['content'=>'发证机关','font-weight'=>true,'width'=>'30'],
                ['content'=>'所属运营公司','font-weight'=>true,'width'=>'30']
            ],
        ];
        // excel表头
        $excel->addLineToExcel($excHeaders[0]);

        foreach($data as $val){
            $lineData = [];
            $val['number'] .= ' ';
            $val['id_sex'] = $val['id_sex'] == 1 ? '男' : '女';
            $val['driving_issue_date'] = $val['driving_issue_date'] ? date('Y-m-d',$val['driving_issue_date']) : '';
            $val['driving_valid_for'] .= '年';
            $val['driving_valid_from'] = $val['driving_valid_from'] ? date('Y-m-d',$val['driving_valid_from']) : '';
            foreach($val as $v){
                $lineData[] = ['content'=>$v];
            }
            $excel->addLineToExcel($lineData);
        }
        $objPHPExcel = $excel->getPHPExcel();
        header("Content-type: application/octet-stream"); 
        header("Accept-Ranges: bytes"); 
        //header("Accept-Length:".$fileSize); 
        header("Content-Disposition: attachment; filename=".iconv('utf-8','gbk','个人客户列表.xls')); 
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }

	/**
	 * 在百度地图上显示所有个人客户
	 */
	public function actionShowOnMap(){
        $config = (new ConfigCategory)->getCategoryConfig(['bmap_api_addr']);
        $_config = [];
        foreach($config as $key=>$val){
            $_config[$key] = array_values($val)[0]['value'];
        }
		$query = CustomerPersonal::find()->where(['is_del'=>0]);
		//查询条件
		$query->andFilterWhere(['like','id_name',yii::$app->request->get('id_name')]);
        $query->andFilterWhere(['like','mobile',yii::$app->request->get('mobile')]);
        $query->andFilterWhere(['like','id_number',yii::$app->request->get('id_number')]);
		$columns = ['id','number','id_name','id_sex','id_address','personal_lng','personal_lat','mobile','email'];
		$data = $query->select($columns)->asArray()->all();
        return $this->render('showOnMapWin',[
            'listData'=>$data,
            'config'=>$_config
        ]);
	}
	
}