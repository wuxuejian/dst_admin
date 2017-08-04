<?php
/**
 * @Desc:	会员分享管理控制器 （此分享指的是第三方分享新电桩供地上铁审核再收录进系统以供用户搜索使用）
 * @author: chengwk
 * @date:	2015-11-27
 */
namespace backend\modules\vip\controllers;
use backend\controllers\BaseController;
use yii;
use yii\base\Object;
use yii\data\Pagination;
use backend\models\ConfigCategory;
use backend\models\Vip;
use backend\models\ChargeSpots;
use backend\models\VipShare;
use common\models\Excel;

class VipShareController extends BaseController
{
    public function actionIndex()
    {	
		$configItems = ['charge_type','connection_type','install_type','manufacturer','status','model'];
        $data['config'] = (new ConfigCategory())->getCategoryConfig($configItems,'value'); 
		$data['buttons'] = $this->getCurrentActionBtn();
        return $this->render('index',$data); 
    }
    
    /**
     * 获取分享列表
     */
    public function actionGetList()
    {
        $query = VipShare::find()
			->select([
                '{{%vip_share}}.*',
                'vip_code'=>'{{%vip}}.code',
                'vip_name'=>'{{%vip}}.client',
                'vip_mobile'=>'{{%vip}}.mobile',
                '{{%charge_spots}}.code_from_compony'
            ])
			->joinWith('vip',false)
			->joinWith('charger',false)
			->where(['{{%vip_share}}.is_del'=>0]);
        //查询条件
        $query->andFilterWhere(['like','{{%vip}}.code',yii::$app->request->get('vip_code')]);
        $query->andFilterWhere(['like','{{%vip}}.client',yii::$app->request->get('vip_name')]);
        $query->andFilterWhere(['like','{{%vip}}.mobile',yii::$app->request->get('vip_mobile')]);
        $query->andFilterWhere(['=','approve_status',yii::$app->request->get('approve_status')]);
        $total = $query->count();
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
		//排序
		if(yii::$app->request->get('sort')){
			$field = yii::$app->request->get('sort');		//field
			$direction = yii::$app->request->get('order');  //asc or desc
			$orderStr = $field .' '. $direction;
		}else{
			$orderStr = '{{%vip_share}}.approve_status ASC,{{%vip_share}}.share_time DESC';  // 按审核状态升序，分享时间降序排。
		}		
        $data = $query->offset($pages->offset)->limit($pages->limit)->orderBy($orderStr)->asArray()->all();
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        echo json_encode($returnArr);
    }
    
    /**
     * 审核电桩。
	 * (1)若【审核通过】，则添加新电桩并同步更新分享记录状态等。
	 * (2)若【审核不通过】，则不收录电桩而只要更新分享记录状态并填写原因即可。
     */
    public function actionApprove()
    {
        if(yii::$app->request->isPost){
			$postData = yii::$app->request->post();
			$approveResult = $postData['approveResult']; 		 // 审核是否通过？
			$currentVipShareId = $postData['currentVipShareId']; // 当前操作的分享记录ID
			$returnArr = [];
			if($approveResult == 'approve_passed'){ 
				// 【审核通过】 
				$model = new ChargeSpots(); 
				parse_str($postData['formData'],$arr); //parse_str()把查询字符串解析成数组并存入变量
				$model->load($arr,'');
				if($model->validate()){
					$model->systime = time();
					$model->sysuser = $_SESSION['backend']['adminInfo']['username'];
					if($model->save(false)){
						// 新增电桩后再同步更新分享记录
						$chargerid = $model->id;
						$vipShare = VipShare::findOne($currentVipShareId);
						$vipShare->approve_status = 2;
						$vipShare->approve_userid = $_SESSION['backend']['adminInfo']['id'];
						$vipShare->approve_time = date('Y-m-d H:i:s');
						$vipShare->approve_mark = ''; 	// 若之前曾审核不通过，则现在清空原因
						$vipShare->approve_chargerid = $chargerid; 
						if($vipShare->save(false)){
							$returnArr['status'] = true;
							$returnArr['info'] = '审核已通过：新增了电桩并同步更新了当前分享记录！';
						}else{
							$returnArr['status'] = false;
							$returnArr['info'] = '新增了电桩但同步更新当前分享记录失败！';
						}
					}else{
						$returnArr['status'] = false;
						$returnArr['info'] = '保存新电桩时发生错误！';
					}
				}else{
					$returnArr['status'] = false;
					$error = $model->getErrors();
					if($error){
						$errorStr = '';
						foreach($error as $val){
							$errorStr .= $val[0].'&';
						}
						$returnArr['info'] = rtrim($errorStr,'&');
					}else{
						$returnArr['info'] = '未知错误';
					}
				}
			}elseif($approveResult == 'approve_refused'){ 
				// 【审核不通过】
				$vipShare = VipShare::findOne($currentVipShareId);
				$vipShare->approve_status = 1;
				$vipShare->approve_userid = $_SESSION['backend']['adminInfo']['id'];
				$vipShare->approve_time = date('Y-m-d H:i:s');
				$vipShare->approve_mark = $postData['approveMark'];
				if($vipShare->save(false)){
					$returnArr['status'] = true;
					$returnArr['info'] = '审核已不予通过！';
				}else{
					$returnArr['status'] = false;
					$returnArr['info'] = '更新当前分享记录失败！';
				}						
			}else{
				$returnArr['status'] = false;
				$returnArr['info'] = '无法判断审核是否通过，没有做任何处理！';
			}
			return json_encode($returnArr); 
        }else{
			$id = intval(yii::$app->request->get('id')) or die("Not pass 'id'.");
			//获取combo配置数据
			$configItems = ['charge_type','connection_type','install_type','manufacturer','status','model'];
			$configCategoryModel = new ConfigCategory();
			$config = $configCategoryModel->getCategoryConfig($configItems);
			$vipShareInfo = VipShare::find()
				// ->select(['code_from_factory','model','charge_type','connection_type','specification','wire_length','charge_gun_nums',
				// 'manufacturer','install_type','install_site','lng','lat','user','user_linkman','user_linkman_mobile','user_linkman_tel'
				// ])
				->where(['id'=>$id])
				->asArray()->one();
			return $this->render('approveWindow',[
				'config'=>$config,
				'vipShareInfo'=>$vipShareInfo
			]);
		}
    }
	
	
	
	/**
     * 导出Excel
     */
    public function actionExportGridData()
    {
		// 构建导出的excel表头（这里有2行表头）
		$excHeaders = [
			[ 
				['content'=>'会员编号','font-weight'=>true,'width'=>'15','rowspan'=>2,'valign'=>'center'],
				['content'=>'会员名称','font-weight'=>true,'width'=>'15','rowspan'=>2,'valign'=>'center'],
				['content'=>'会员手机号','font-weight'=>true,'width'=>'15','rowspan'=>2,'valign'=>'center'],
				['content'=>'分享时间','font-weight'=>true,'width'=>'15','rowspan'=>2,'valign'=>'center'],
				['content'=>'分享备注','font-weight'=>true,'width'=>'20','rowspan'=>2,'valign'=>'center'],
				['content'=>'审核情况','font-weight'=>true,'colspan'=>3,'align'=>'center'], 					// 跨4列
				['content'=>'分享的电桩','font-weight'=>true,'colspan'=>3,'align'=>'center'], 				// 跨4列
				['content'=>'登记时间','font-weight'=>true,'width'=>'15','rowspan'=>2,'valign'=>'center']
			],
			[ 
				[],[],[],[],[],
				['content'=>'审核状态','font-weight'=>true,'width'=>'15'],
				['content'=>'审核时间','font-weight'=>true,'width'=>'15'],
				['content'=>'审核备注','font-weight'=>true,'width'=>'30'],
				['content'=>'电桩编号','font-weight'=>true,'width'=>'15'],
				['content'=>'连接方式','font-weight'=>true,'width'=>'15'],
				['content'=>'安装地点','font-weight'=>true,'width'=>'30'],
				[]
			]
		];			
		
		// 要查的字段，与导出的excel表头对应
		$selectArr = [		
			'{{%vip}}.code',
			'{{%vip}}.client',
			'{{%vip}}.mobile',
			'{{%vip_share}}.share_time',
			'{{%vip_share}}.mark',
			'{{%vip_share}}.approve_status',
			'{{%vip_share}}.approve_time',
			'{{%vip_share}}.approve_mark',
			'{{%charge_spots}}.code_from_compony',
			'{{%charge_spots}}.connection_type',
			'{{%charge_spots}}.install_site',		
			'{{%vip_share}}.systime'
		];

        $query = VipShare::find()
			->select($selectArr)
			->joinWith('charger',false)
			->joinWith('vip',false)
			->where(['{{%vip_share}}.is_del'=>0]);
        // 查询条件
        $query->andFilterWhere(['like','{{%vip}}.code',yii::$app->request->get('vip_code')]);
        $query->andFilterWhere(['like','{{%vip}}.client',yii::$app->request->get('vip_name')]);
        $query->andFilterWhere(['like','{{%vip}}.mobile',yii::$app->request->get('vip_mobile')]);
        $data = $query
            ->orderBy('{{%vip_share}}.approve_status ASC,{{%vip_share}}.share_time DESC')
            ->asArray()->all();
		//print_r($data);exit;
		
		$excel = new Excel();
        $excel->setHeader([
            'creator'=>'皓峰通讯',
            'lastModifiedBy'=>'hao feng tong xun',
            'title'=>'VipShare',
            'subject'=>'VipShare',
            'description'=>'VipShare list',
            'keywords'=>'VipShare list',
            'category'=>'VipShare list'
        ]);
		
		//---向excel添加表头-------------------------------------
		foreach($excHeaders as $lineData){
			$excel->addLineToExcel($lineData);
		}
        
		//---向excel添加具体数据----------------------------------
		$configItems = ['connection_type'];
        $configs = (new ConfigCategory())->getCategoryConfig($configItems,'value'); 
        foreach($data as $item){			
            $lineData = [];		
			// 将combox配置值改以文本显示
			foreach($configItems as $conf) { 
				if(isset($item[$conf]) && $item[$conf]) {
					$item[$conf] = $configs[$conf][$item[$conf]]['text'];
				}
			}
            $item['approve_status'] = $item['approve_status']==2 ? '审核通过' : ($item['approve_status']==1 ? '审核未通过' : '未审核') ; //0未审核，1审核未通过，2审核通过
            $item['systime'] = $item['systime'] ? date('Y-m-d',$item['systime']) : '';
			foreach($item as $k=>$v) {
				if(!is_array($v)){
					$lineData[] = ['content'=>$v];
				}
            } 
            $excel->addLineToExcel($lineData);
        }
		unset($data);
		
        $objPHPExcel = $excel->getPHPExcel();
        header("Content-type: application/octet-stream"); 
        header("Accept-Ranges: bytes"); 
        //header("Accept-Length:".$fileSize); 
        header("Content-Disposition: attachment; filename=".iconv('utf-8','gbk','分享列表_'.date('YmdHis').'.xls')); 
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');	
    }
    
}