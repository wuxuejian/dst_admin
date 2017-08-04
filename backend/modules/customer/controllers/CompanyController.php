<?php
/**
 * 企业客户管理控制器
 * time: 2014/10/14 11:35
 * @author wangmin
 */
namespace backend\modules\customer\controllers;
use backend\controllers\BaseController;
use backend\models\CustomerCompany;
use backend\models\Vip;
use common\models\Excel;
use yii;
use yii\data\Pagination;
use backend\models\ConfigCategory;

class CompanyController extends BaseController
{
    public function actionIndex()
    {
        $buttons = $this->getCurrentActionBtn();
        return $this->render('index',[
            'buttons'=>$buttons
        ]);
    }

    /**
     * 获取客户列表
     */
    public function actionGetList()
    {
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $query = CustomerCompany::find()
            ->select([
                '{{%customer_company}}.id','number','company_name','reg_number',
                'company_addr','corporate_name',
                'corporate_mobile','director_name','director_mobile',
                'contact_name','contact_mobile','keeper_name','keeper_mobile',
                'company_brief',
            	'{{%customer_company}}.type',
                'operating_company'=>'{{%operating_company}}.name'
            ])
            ->joinWith('operatingCompany',false)
            ->where(['{{%customer_company}}.is_del'=>0]);
        //查询条件开始
        $query->andFilterWhere([
            'like',
            'company_name',
            yii::$app->request->get('company_name')
        ]);
        $query->andFilterWhere([
            'like',
            'reg_number',
            yii::$app->request->get('regNumber')
        ]);
        //检测是否要根据当前登录人员所属运营公司来显示列表数据-20160328
        $isLimitedArr = CustomerCompany::isLimitedToShowByAdminOperatingCompany();
        if(is_array($isLimitedArr) && isset($isLimitedArr['status']) && $isLimitedArr['status']){
            $query->andWhere("{{%customer_company}}.`operating_company_id` in ({$isLimitedArr['adminInfo_operatingCompanyId']})");
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
                    $orderBy = "{{%customer_company}}.$sortColumn ";
            }
        }else{
           $orderBy = '{{%customer_company}}.`id` ';
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
     * 添加企业客户
     */
    public function actionAdd()
    {
        //data submit start
        if(yii::$app->request->isPost){
            $model = new CustomerCompany;
            $model->load(yii::$app->request->post(),'');
            $model->number = date('YmdHis').str_pad(mt_rand(0,100),3,0);
            $model->password = md5(substr(md5(yii::$app->request->post('password')),0,30));
            $model->operating_company_id = $_SESSION['backend']['adminInfo']['operating_company_id'];
            $model->classify2_id = $model->classify2_id?$model->classify2_id:0;
            $returnArr = [];
            $returnArr['status'] = true;
            $returnArr['info'] = '';
            
            if($model->validate()){
            	$company_name = trim(yii::$app->request->post('company_name'));
            	if(CustomerCompany::find()
		            ->select('count(*)')
		            ->where(['company_name'=>$company_name])
		            ->count()>0){
            		$returnArr['status'] = false;
            		$returnArr['info'] = '企业客户已存在';
            		exit(json_encode($returnArr));
            	}
            	 
            	 
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '企业客户添加成功！';
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
        return $this->render('add');
    }

    /**
     * 修改企业客户信息
     */
    public function actionEdit()
    {
        //data submit start
        if(yii::$app->request->isPost){
            $id = yii::$app->request->post('id') or die('param id is required');
            $model = CustomerCompany::findOne(['id'=>$id]);
            $model or die('record not found');
            //检查当前登录用户和要操作的企业客户的所属运营公司是否匹配-20160328
            $checkArr = CustomerCompany::checkOperatingCompanyIsMatch($id);
            if(is_array($checkArr) && isset($checkArr['status']) && !$checkArr['status']){
                return json_encode(['status'=>false,'info'=>$checkArr['info']]);
            }
            $model->load(yii::$app->request->post(),'');
            $model->classify2_id = $model->classify2_id?$model->classify2_id:0;
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
        $columns = (new CustomerCompany)->attributeLabels();
        unset($columns['password']);
        $customerInfo = CustomerCompany::find()
            ->select(array_keys($columns))
            ->where(['id'=>$id])
            ->asArray()->one();
        $customerInfo or die('record not found');
        return $this->render('edit',[
            'customerInfo'=>$customerInfo
        ]);
    }

    /**
     * 删除企业客户
     */
    public function actionRemove()
    {
        $id = yii::$app->request->get('id') or die('param id is required');
        //检查当前登录用户和要操作的企业客户的所属运营公司是否匹配-20160328
        $checkArr = CustomerCompany::checkOperatingCompanyIsMatch($id);
        if(is_array($checkArr) && isset($checkArr['status']) && !$checkArr['status']){
            return json_encode(['status'=>false,'info'=>$checkArr['info']]);
        }
        $returnArr = [];
        $returnArr['status'] = true;
        $returnArr['info'] = '';
        if(CustomerCompany::updateAll(['is_del'=>1],['id'=>$id])){
            $returnArr['status'] = true;
            $returnArr['info'] = '客户删除成功！';
        }else{
            $returnArr['status'] = false;
            $returnArr['info'] = '客户删除失败！';
        }
        echo json_encode($returnArr);
    }

    /**
     * 条件导出
     */
    public function actionExportWidthCondition()
    {
        $query = CustomerCompany::find()
            ->select([
                'number','company_name','reg_number','company_addr',
                'contact_name','contact_mobile',
                'director_name','director_mobile',
                'keeper_name','keeper_mobile',
                'corporate_name','corporate_mobile',
                'company_brief',
                'operating_company'=>'{{%operating_company}}.name'
            ])
            ->joinWith('operatingCompany',false)
            ->where(['{{%customer_company}}.is_del'=>0]);
        //查询条件开始
        $query->andFilterWhere([
            'like',
            'company_name',
            yii::$app->request->get('company_name')
        ]);
        $query->andFilterWhere([
            'like',
            'reg_number',
            yii::$app->request->get('regNumber')
        ]);
        //检测是否要根据当前登录人员所属运营公司来显示列表数据-20160328
        $isLimitedArr = CustomerCompany::isLimitedToShowByAdminOperatingCompany();
        if(is_array($isLimitedArr) && isset($isLimitedArr['status']) && $isLimitedArr['status']){
            $query->andWhere("{{%customer_company}}.`operating_company_id` in ({$isLimitedArr['adminInfo_operatingCompanyId']})");
        }
        //查询条件结束
        $data = $query->asArray()->all();

        $excel = new Excel();
        $excel->setHeader([
            'creator'=>'皓峰通讯',
            'lastModifiedBy'=>'hao feng tong xun',
            'title'=>'customer company',
            'subject'=>'customer company',
            'description'=>'customer company list',
            'keywords'=>'customer company list',
            'category'=>'customer company list'
        ]);
        $excHeaders = [
            [
                ['content'=>'客户号','font-weight'=>true,'width'=>'20'],
                ['content'=>'公司名称','font-weight'=>true,'width'=>'30'],
                ['content'=>'营业执照注册号','font-weight'=>true,'width'=>'15'],
                ['content'=>'公司地址','font-weight'=>true,'width'=>'30'],
                ['content'=>'联系人姓名','font-weight'=>true,'width'=>'10'],
                ['content'=>'联系人手机','font-weight'=>true,'width'=>'15'],
                ['content'=>'负责人姓名','font-weight'=>true,'width'=>'10'],
                ['content'=>'负责人手机','font-weight'=>true,'width'=>'15'],
                ['content'=>'管理人姓名','font-weight'=>true,'width'=>'10'],
                ['content'=>'管理人手机','font-weight'=>true,'width'=>'15'],
                ['content'=>'法人姓名','font-weight'=>true,'width'=>'10'],
                ['content'=>'法人手机','font-weight'=>true,'width'=>'15'],
                ['content'=>'公司简介','font-weight'=>true,'width'=>'30'],
                ['content'=>'所属运营公司','font-weight'=>true,'width'=>'30']
            ],
        ];
        // excel表头
        $excel->addLineToExcel($excHeaders[0]);

        foreach($data as $val){
            $val['number'] = ' '.$val['number'];
            $lineData = [];
            foreach($val as $v){
                $lineData[] = ['content'=>$v];
            }
            $excel->addLineToExcel($lineData);
        }
        $objPHPExcel = $excel->getPHPExcel();
        header("Content-type: application/octet-stream"); 
        header("Accept-Ranges: bytes"); 
        //header("Accept-Length:".$fileSize); 
        header("Content-Disposition: attachment; filename=".iconv('utf-8','gbk','企业客户列表导出.xls'));
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }
	
	/**
	 * 在百度地图上显示所有企业客户
	 */
	public function actionShowOnMap(){
        $config = (new ConfigCategory)->getCategoryConfig(['bmap_api_addr']);
        $_config = [];
        foreach($config as $key=>$val){
            $_config[$key] = array_values($val)[0]['value'];
        }
		$query = CustomerCompany::find()->where(['is_del'=>0]);
		$query->andFilterWhere(['like','company_name',yii::$app->request->get('company_name')]);
		$query->andFilterWhere(['like','reg_number',yii::$app->request->get('regNumber')]);
		$columns = ['id','company_name','company_addr','company_lng','company_lat','contact_name','contact_mobile','contact_email'];
		$data = $query->select($columns)->asArray()->all();
        return $this->render('showOnMapWin',[
            'listData'=>$data,
            'config'=>$_config
        ]);
	}

    /**
     * 设置客户密码
     */
    public function actionSetPassword(){
        // 提交并保存密码
        if (yii::$app->request->isPost) {
            $postData = yii::$app->request->post();
            //检查当前登录用户和要操作的企业客户的所属运营公司是否匹配-20160328
            $checkArr = CustomerCompany::checkOperatingCompanyIsMatch($postData['id']);
            if(is_array($checkArr) && isset($checkArr['status']) && !$checkArr['status']){
                return json_encode(['status'=>false,'info'=>$checkArr['info']]);
            }
            $model = CustomerCompany::findOne(['id'=>$postData['id']]);
            $model->password = md5(substr(md5($postData['password']),0,30));
            if ($model->save()) {
                $returnArr['status'] = true;
                $returnArr['info'] = '密码设置成功！';
            } else {
                $returnArr['status'] = false;
                $returnArr['info'] = '密码设置失败！';
            }
            echo json_encode($returnArr); exit;
        } else {  // 访问视图
            $customerId = intval(yii::$app->request->get('customerId'));
            return $this->render('setPasswordWin',['customerId'=>$customerId]);
        }
    }

    /**
     * 为企业客户关联vip
     */
    public function actionRelationVip(){
        if(yii::$app->request->isPost){
            $customerId = yii::$app->request->post('id');
            $type = yii::$app->request->post('type');
            $codeVal = yii::$app->request->post('code_val');
            //检查当前登录用户和要操作的企业客户的所属运营公司是否匹配-20160328
            $checkArr = CustomerCompany::checkOperatingCompanyIsMatch($customerId);
            if(is_array($checkArr) && isset($checkArr['status']) && !$checkArr['status']){
                return json_encode(['status'=>false,'info'=>$checkArr['info']]);
            }
            $returnArr = [];
            $returnArr['status'] = false;
            $returnArr['info'] = '';
            switch ($type) {
                case 'mobile':
                    $vipInfo = Vip::find()
                        ->select(['id'])
                        ->where(['mobile'=>$codeVal])
                        ->asArray()->one();
                    break;
                case 'code':
                    $vipInfo = Vip::find()
                        ->select(['id'])
                        ->where(['code'=>$codeVal])
                        ->asArray()->one();
                    break;
            }
            if($vipInfo){
                //检测该会员是否已经被其它客户关联
                $hasRelation = CustomerCompany::find()
                    ->select(['id','number'])
                    ->where(['vip_id'=>$vipInfo['id']])->asArray()->one();
                if($hasRelation){
                    $returnArr['info'] = '该会员已经关联客户：'.$hasRelation['number'].'，请先解除关联！';
                }else{
                    if(CustomerCompany::updateAll(['vip_id'=>$vipInfo['id']],['id'=>$customerId])){
                        $returnArr['status'] = true;
                        $returnArr['info'] = '关联成功！';
                    }else{
                        $returnArr['info'] = '关联失败！';
                    }
                }
            }else{
                $returnArr['info'] = '会员不存在！';
            }
            echo json_encode($returnArr);
            return null;
        }else{
            $id = yii::$app->request->get('id');
            $id or die('param id is required!');
            return $this->render('relation-vip',[
                'id'=>$id
            ]);
        }
    }

    /**
     * 解除企业客户与会员的关联
     */
    public function actionRelieveVip(){
        $id = yii::$app->request->get('id');
        //检查当前登录用户和要操作的企业客户的所属运营公司是否匹配-20160328
        $checkArr = CustomerCompany::checkOperatingCompanyIsMatch($id);
        if(is_array($checkArr) && isset($checkArr['status']) && !$checkArr['status']){
            return json_encode(['status'=>false,'info'=>$checkArr['info']]);
        }
        $returnArr = [];
        $returnArr['status'] = false;
        $returnArr['info'] = '';
        if($id){
            if(CustomerCompany::updateAll(['vip_id'=>0],['id'=>$id])){
                $returnArr['status'] = true;
                $returnArr['info'] = '解除成功！';
            }else{
                $returnArr['info'] = '操作失败！';
            }
        }else{
            $returnArr['info'] = '参数错误！';
        }
        echo json_encode($returnArr);
    }


}