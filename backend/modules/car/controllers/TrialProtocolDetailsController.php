<?php
/**
 * 车辆试用记录管理控制器
 * @author chengwk
 * @date   2015-12-10
 */
namespace backend\modules\car\controllers;
use backend\controllers\BaseController;
use yii;
use yii\base\Object;
use yii\data\Pagination;
use backend\models\ConfigCategory;
use backend\models\CarTrialProtocol;
use backend\models\CarTrialProtocolDetails;
use backend\models\CustomerCompany;
use backend\models\CustomerPersonal;
use common\models\Excel;

class TrialProtocolDetailsController extends BaseController
{
    public function actionIndex()
    {
        //获取本页按钮
        $buttons = $this->getCurrentActionBtn();
        $config = (new ConfigCategory)->getCategoryConfig(['customer_type'],'value');
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
     * 获取试用车辆明细列表
     */
    public function actionGetList()
    {
        $query = CarTrialProtocolDetails::find()
                ->select([
                    '{{%car_trial_protocol_details}}.*',
                    '{{%car_trial_protocol}}.ctp_number',
                    '{{%car_trial_protocol}}.ctp_sign_date',
                    '{{%car_trial_protocol}}.ctp_start_date',
                    '{{%car_trial_protocol}}.ctp_end_date',
                    '{{%car}}.plate_number',
					'customer_type'=>'{{%car_trial_protocol}}.`ctp_customer_type`',
                    'cCustomer_name'=>'{{%customer_company}}.`company_name`',
                    'pCustomer_name'=>'{{%customer_personal}}.`id_name`',
                ])
                ->joinWith('car',false)
                ->joinWith('carTrialProtocol',false)
                ->joinWith('customerCompany',false,'LEFT JOIN')  //查企业客户
                ->joinWith('customerPersonal',false,'LEFT JOIN') //查个人客户
                ->andWhere(['ctpd_is_del'=>0]);
        // 查询条件
        $query->andFilterWhere(['like','plate_number',yii::$app->request->get('plate_number')]);
        $query->andFilterWhere(['like','ctp_number',yii::$app->request->get('ctp_number')]);
        $query->andFilterWhere([ //企业/个人客户名称
            'or',
            ['like','{{%customer_company}}.`company_name`',yii::$app->request->get('customer_name')],
            ['like','{{%customer_personal}}.`id_name`',yii::$app->request->get('customer_name')]
        ]);
        $query->andFilterWhere(['like','{{%car_trial_protocol}}.`ctp_customer_type`',yii::$app->request->get('customer_type')]);
        $trialStatus = yii::$app->request->get('trial_status'); // 试用状态
        if($trialStatus){
            switch($trialStatus){
                case 'INTRIAL':
                    $query->andWhere(['ctpd_back_date'=>null]); break;
                case 'BACKED':
                    $query->andWhere('ctpd_back_date IS NOT NULL'); break;
            }
        }
		$query->andFilterWhere(['>=','ctp_start_date',yii::$app->request->get('ctp_start_date')]); // 试用日期
		$query->andFilterWhere(['<=','ctp_end_date',yii::$app->request->get('ctp_end_date')]);
        //检测是否要根据当前登录人员所属运营公司来显示列表数据-20160326
        $isLimitedArr = carTrialProtocol::isLimitedToShowByAdminOperatingCompany();
        if(is_array($isLimitedArr) && isset($isLimitedArr['status']) && $isLimitedArr['status']){
            $query->andWhere("{{%car_trial_protocol}}.`operating_company_id` in ({$isLimitedArr['adminInfo_operatingCompanyId']})");
        }
        //查询条件
		// 排序
        $sortColumn = yii::$app->request->get('sort');
        $sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
        $orderBy = '';
        if($sortColumn){
            switch ($sortColumn) {
                case 'plate_number':
                    $orderBy = '{{%car}}.`plate_number` ';
                    break;
                case 'customer_type':
                    $orderBy = '{{%car_let_contract}}.`customer_type` ';
                    break;
                case 'customer_name':
                    $orderBy = '{{%customer_company}}.`company_name` ';
                    break;
                case 'ctp_number':
                case 'ctp_sign_date':
                case 'ctp_start_date':
                case 'ctp_end_date':
                    $orderBy = '{{%car_trial_protocol}}.`'.$sortColumn.'` ';
                    break;
                default:
                    $orderBy = '{{%car_trial_protocol_details}}.`'.$sortColumn.'` ';
                    break;
            }
        }else{
           $orderBy = 'ctp_id ';
        }
        $orderBy .= $sortType;
        $total = $query->count();
		//分页
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
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
     * 按条件导出Excel
     */
    public function actionExportWidthCondition()
    {
		// 构建excel表头（这里有2行表头）
		$excHeaders = [
			[ 
				['content'=>'车牌号','font-weight'=>true,'width'=>'15','rowspan'=>2,'valign'=>'center'],		// 跨2行
				['content'=>'试用协议详情','font-weight'=>true,'colspan'=>5,'align'=>'center'], 				// 跨5列
				['content'=>'试用状态','font-weight'=>true,'width'=>'15','rowspan'=>2,'valign'=>'center'],
				['content'=>'还车时间','font-weight'=>true,'width'=>'15','rowspan'=>2,'valign'=>'center'],
				['content'=>'备注','font-weight'=>true,'width'=>'30','rowspan'=>2,'valign'=>'center']
			],
			[ 
				[],
				['content'=>'协议编号','font-weight'=>true,'width'=>'15'],
				['content'=>'试用客户','font-weight'=>true,'width'=>'15'],
				['content'=>'签订日期','font-weight'=>true,'width'=>'15'],
				['content'=>'开始时间','font-weight'=>true,'width'=>'15'],
				['content'=>'结束时间','font-weight'=>true,'width'=>'15'],
				[],[]
			]
		];
        // 要查的字段，与导出的excel表头对应
		$selectArr = [
			'plate_number',
			'ctp_number',
			'customer_name'=>'ctp_cCustomer_id', //作为'客户名称'
			'ctp_sign_date',
			'ctp_start_date',
			'ctp_end_date',
			'trial_status'=>'ctpd_back_date', //作为'试用状态'判断是否已退租
			'ctpd_back_date',
			'ctpd_note',
			'ctp_cCustomer_name'=>'{{%customer_company}}.`company_name`',
			'ctp_pCustomer_name'=>'{{%customer_personal}}.`id_name`',
		];	
        $query = CarTrialProtocolDetails::find()
                ->select($selectArr)
                ->joinWith('car',false)
                ->joinWith('carTrialProtocol',false)
                ->joinWith('customerCompany',false,'LEFT JOIN')  //查企业客户
                ->joinWith('customerPersonal',false,'LEFT JOIN') //查个人客户
                ->andWhere(['ctpd_is_del'=>0]);
        // 查询条件
        $query->andFilterWhere(['like','plate_number',yii::$app->request->get('plate_number')]);
        $query->andFilterWhere(['like','ctp_number',yii::$app->request->get('ctp_number')]);
        $query->andFilterWhere([ //企业/个人客户名称
            'or',
            ['like','{{%customer_company}}.`company_name`',yii::$app->request->get('customer_name')],
            ['like','{{%customer_personal}}.`id_name`',yii::$app->request->get('customer_name')]
        ]);
        $query->andFilterWhere(['like','{{%car_trial_protocol}}.`ctp_customer_type`',yii::$app->request->get('customer_type')]);
        $trialStatus = yii::$app->request->get('trial_status'); // 试用状态
        if($trialStatus){
            switch($trialStatus){
                case 'INTRIAL':
                    $query->andWhere(['ctpd_back_date'=>null]); break;
                case 'BACKED':
                    $query->andWhere('ctpd_back_date IS NOT NULL'); break;
            }
        }
        $query->andFilterWhere(['>=','ctp_start_date',yii::$app->request->get('ctp_start_date')]); // 试用日期
        $query->andFilterWhere(['<=','ctp_end_date',yii::$app->request->get('ctp_end_date')]);
        //查询条件
        $data = $query->asArray()->all(); 
		// print_r($data);exit;
		
        $excel = new Excel();
        $excel->setHeader([
            'creator'=>'皓峰通讯',
            'lastModifiedBy'=>'hao feng tong xun',
            'title'=>'car_trial_protocol_details',
            'subject'=>'car_trial_protocol_details',
            'description'=>'car_trial_protocol_details list',
            'keywords'=>'car_trial_protocol_details list',
            'category'=>'car_trial_protocol_details list'
        ]);
		// excel表头
		foreach($excHeaders as $lineData){
			$excel->addLineToExcel($lineData);
		}
		
        $configItems = ['customer_type'];
        $configs = (new ConfigCategory())->getCategoryConfig($configItems,'value');
		
		foreach($data as $val){
            $lineData = [];
            // 各combox配置项以txt代替val
            foreach($configItems as $conf) {
                if(isset($val['ctp'.$conf]) && $val['ctp'.$conf]) {
                    $val['ctp'.$conf] = $configs[$conf][$val['ctp'.$conf]]['text'];
                }
            }
			$val['trial_status'] = $val['trial_status']==null ? '试用中' : '已还车';
            $val['customer_name'] = $val['ctp_cCustomer_name']!='' ? $val['ctp_cCustomer_name'] : $val['ctp_pCustomer_name'];
            unset($val['ctp_cCustomer_name']);
            unset($val['ctp_pCustomer_name']);
            foreach($val as $v){
				if(!is_array($v)){
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
        header("Content-Disposition: attachment; filename=".iconv('utf-8','gbk','车辆试用记录列表.xls'));
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }	

}