<?php
/**
 * 本控制器为各种【combogrid】获取下拉列表数据
 */
namespace backend\modules\customer\controllers;
use yii;
use yii\data\Pagination;
use backend\controllers\BaseController;
use backend\models\CustomerCompany;
use backend\models\CustomerPersonal;
use backend\models\CarLetContract;

class CombogridController extends BaseController{

    /**
     * 获取【企业客户】列表
     */
    public function actionGetCompanyCustomerList(){
        $queryStr = isset($_REQUEST['q']) ? trim($_REQUEST['q']) : ''; // 检索过滤字符串
        $customerId = isset($_REQUEST['customerId']) ? intval($_REQUEST['customerId']) : 0; //修改时赋值用
        $query = CustomerCompany::find()
            ->select([
                'id AS customer_id',
                'company_name AS customer_name',
                'company_addr AS customer_address'
            ])
            ->where(['is_del'=>0]);

        //检测是否要根据当前登录人员所属运营公司来显示列表数据-20160328
        $isLimitedArr = CustomerCompany::isLimitedToShowByAdminOperatingCompany(true);
        if(is_array($isLimitedArr) && isset($isLimitedArr['status']) && $isLimitedArr['status']){
            $query->andWhere("{{%customer_company}}.`operating_company_id` in ({$isLimitedArr['adminInfo_operatingCompanyId']})");
        }

        if($customerId){ // 修改时查询赋值
            $total = $query->andWhere(['id'=>$customerId])->count();
        }elseif($queryStr){ // 检索过滤时
            $total = $query
                ->andWhere([
                    'or',
                    ['like', 'company_name', $queryStr],
                    ['like', 'company_addr', $queryStr]
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
    }


    /**
     * 获取【个人客户】列表
     */
    public function actionGetPersonalCustomerList(){
        $queryStr = isset($_REQUEST['q']) ? trim($_REQUEST['q']) : ''; // 检索过滤字符串
        $customerId = isset($_REQUEST['customerId']) ? intval($_REQUEST['customerId']) : 0; //修改时赋值用
        $query = CustomerPersonal::find()
            ->select([
                'id AS customer_id',
                'id_name AS customer_name',
                'mobile AS customer_mobile',
                'id_address AS customer_address'
            ])
            ->where(['is_del'=>0]);

        //检测是否要根据当前登录人员所属运营公司来显示列表数据-20160328
        $isLimitedArr = CustomerPersonal::isLimitedToShowByAdminOperatingCompany(true);
        if(is_array($isLimitedArr) && isset($isLimitedArr['status']) && $isLimitedArr['status']){
            $query->andWhere("{{%customer_personal}}.`operating_company_id` in ({$isLimitedArr['adminInfo_operatingCompanyId']})");
        }

        if($customerId){ // 修改时查询赋值
            $total = $query->andWhere(['id'=>$customerId])->count();
        }elseif($queryStr){ // 检索过滤时
            $total = $query
                ->andWhere([
                    'or',
                    ['like', 'id_name', $queryStr],
                    ['like', 'mobile', $queryStr],
                    ['like', 'id_address', $queryStr]
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
    }


    /**
     * 获取【选择续费合同】列表
     * 注意：同时包含企业/个人客户合同
     */
    public function actionGetContractList(){
        $queryStr = isset($_REQUEST['q']) ? trim($_REQUEST['q']) : ''; // 检索过滤字符串
        $contractId = isset($_REQUEST['contractId']) ? intval($_REQUEST['contractId']) : 0; //修改时赋值用

        $query = CarLetContract::find()
            ->select([
                'contract_id'=>'{{%car_let_contract}}.id',
                'contract_number'=>'{{%car_let_contract}}.number',
                '{{%car_let_contract}}.customer_type',
                'cCustomer_name'=>'{{%customer_company}}.`company_name`',
                'pCustomer_name'=>'{{%customer_personal}}.`id_name`',
            ])
            ->joinWith('customerCompany',false,'LEFT JOIN')  //查企业客户
            ->joinWith('customerPersonal',false,'LEFT JOIN') //查个人客户
            ->where(['{{%car_let_contract}}.is_del'=>0]);

        //检测是否要根据当前登录人员所属运营公司来显示列表数据-20160326
        $isLimitedArr = CarLetContract::isLimitedToShowByAdminOperatingCompany(true);
        if(is_array($isLimitedArr) && isset($isLimitedArr['status']) && $isLimitedArr['status']){
            $query->andWhere("{{%car_let_contract}}.`operating_company_id` in ({$isLimitedArr['adminInfo_operatingCompanyId']})");
        }

        if($contractId){ // 修改时查询赋值
            $total = $query->andWhere(['{{%car_let_contract}}.id'=>$contractId])->count();
        }elseif($queryStr){ // 检索过滤时
            $total = $query
                ->andWhere([
                    'or',
                    ['like', '{{%car_let_contract}}.number', $queryStr],
                    ['like','{{%customer_company}}.`company_name`',$queryStr],
                    ['like','{{%customer_personal}}.`id_name`',$queryStr]
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
    }




}