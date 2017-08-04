<?php
/**
 * @Desc:	充电预约管理控制器 
 * @author: chengwk
 * @date:	2015-11-10
 */
namespace backend\modules\vip\controllers;
use backend\controllers\BaseController;
use yii;
use yii\base\Object;
use yii\data\Pagination;
use backend\models\ConfigCategory;
use backend\models\Vip;
use backend\models\ChargeSpots;
use backend\models\ChargeAppointment;
use common\models\Excel;
use backend\classes\UserLog;

class ChargeAppointmentController extends BaseController
{
    public function actionIndex()
    {	
        $data['config'] = (new ConfigCategory())->getCategoryConfig(['connection_type'],'value'); 
		$data['buttons'] = $this->getCurrentActionBtn();
        return $this->render('index',$data); 
    }
    
    /**
     * 获取预约列表
     */
    public function actionGetList()
    {
        $query = ChargeAppointment::find()
			->select([
                '{{%charge_appointment}}.*',
                '{{%vip}}.mobile',
                'code_from_compony',
                'connection_type',
                'install_site'
            ])
			->joinWith('vip',false)
			->joinWith('charger',false)
			->where(['{{%charge_appointment}}.is_del'=>0]);
        //查询条件
        $query->andFilterWhere(['like','code',yii::$app->request->get('code')]);
        $query->andFilterWhere(['like','{{%vip}}.mobile',yii::$app->request->get('mobile')]);
        $timeStart = yii::$app->request->get('appointed_date_start');
		if($timeStart){
            $query->andFilterWhere(['>=','appointed_date',$timeStart]);
        }
        $timeEnd = yii::$app->request->get('appointed_date_end');
        if($timeEnd){
            $query->andFilterWhere(['<=','appointed_date',$timeEnd]);
        }
        $total = $query->count();
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
		//排序
		if(yii::$app->request->get('sort')){
			$field = yii::$app->request->get('sort');		//field
			$direction = yii::$app->request->get('order');  //asc or desc
			if($field == 'appointed_date_time'){ // 按预约日期+起始时间排
				$orderStr = 'appointed_date '. $direction . ',time_start ' . $direction;
			}else{
				$orderStr = $field .' '. $direction;
			}
		}else{
			$orderStr = 'id desc';
		}		
        $data = $query->offset($pages->offset)->limit($pages->limit)->orderBy($orderStr)->asArray()->all();
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        echo json_encode($returnArr);
    }
    
    /**
     * 新增预约信息  
     */
    public function actionAdd()
    {
        if(yii::$app->request->isPost){
            $model = new ChargeAppointment(); 
			$formData = yii::$app->request->post('formData');
			parse_str($formData,$arr); //parse_str()把查询字符串解析成数组并存入变量
			//print_r($arr);exit;
            $model->load($arr,'');
            $returnArr = [];
            if($model->validate()){
				$model->code = 'CA'.date('YmdHis').mt_rand(100,999);
                $model->systime = time();
                $model->sysuser = $_SESSION['backend']['adminInfo']['username'];
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '新增预约成功！';
					// 记录操作日志
					UserLog::log("会员列表-新增预约：" . $model->code . "！",'sys'); 
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '新增预约失败！';
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
            return json_encode($returnArr); 
        }else{
			//获取combo配置数据
			$configItems = ['connection_type'];
			$configCategoryModel = new ConfigCategory();
			$config = $configCategoryModel->getCategoryConfig($configItems,'value'); 
			return $this->render('chargeAppointmentInfoWin',[
				'config'=>$config,
				'myData'=>['action'=>'add']
			]);
		}
    }
    
    
    //修改预约信息
    public function actionEdit()
    {
        if(yii::$app->request->isPost){
            $id = intval(yii::$app->request->post('id')) or die("Not pass 'id'.");
            $model = ChargeAppointment::findOne(['id'=>$id]) or die('Not find corresponding data.');
			$formData = yii::$app->request->post('formData');
			parse_str($formData,$arr); //parse_str()把查询字符串解析成数组并存入变量			
            $model->load($arr,'');
            $returnArr = []; 
            if($model->validate()){
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '修改预约成功！';
					// 记录操作日志
					UserLog::log("会员列表-修改预约：" . $model->code . "！",'sys'); 
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '修改预约失败！';
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
            return json_encode($returnArr);
        }else{
			$id = intval(yii::$app->request->get('id')) or die("Not pass 'id'.");
			//获取combo配置数据
			$configItems = ['connection_type'];
			$configCategoryModel = new ConfigCategory();
			$config = $configCategoryModel->getCategoryConfig($configItems,'value');
			$chargeAppointmentInfo = ChargeAppointment::find()
				->select(['{{%charge_appointment}}.*','{{%vip}}.mobile','code_from_compony','connection_type','install_site'])
				->joinWith('vip')
				->joinWith('charger')
				->where(['{{%charge_appointment}}.id'=>$id])
				->asArray()->one() or die('读取数据失败！');
			return $this->render('chargeAppointmentInfoWin',[
				'config'=>$config,
				'myData'=>[
					'action'=>'edit',
					'chargeAppointmentInfo'=>$chargeAppointmentInfo
				]
			]);
		}
    }
       

    /**
     * 删除预约单
     */
    public function actionRemove()
    {
        $id = intval(yii::$app->request->get('id')) or die("Not pass 'id'.");
        $returnArr = [];
        if(ChargeAppointment::updateAll(['is_del'=>1],['id'=>$id])){
            $returnArr['status'] = true;
            $returnArr['info'] = '预约单删除成功！';
        }else{
            $returnArr['status'] = false;
            $returnArr['info'] = '预约单删除失败！';
        }
        echo json_encode($returnArr);
    }
	   
	
	
	
	/**
	 * 新增预约时查找电桩
	 */
	public function actionSearchAvailableChargers(){
		$appointedDate = yii::$app->request->get('appointedDate'); 	 // 预约日期
		$connectionType = yii::$app->request->get('connectionType');
		$installSite = yii::$app->request->get('installSite');
		if($appointedDate){
			// 1.查出符合的电桩
			$query = ChargeSpots::find()
					->select(['id','code_from_compony','connection_type','install_site'])
					->andWhere(['is_del'=>0]);
			if($connectionType && $connectionType != 'ALL'){
				$query->andFilterWhere(['=','connection_type',$connectionType]);
			}
			if($installSite){
				$query->andFilterWhere(['like','connection_type',$installSite]);
			}
			$total = $query->count();
			$pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
			$pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
			$chargers = $query->offset($pages->offset)->limit($pages->limit)->asArray()->all(); 
			
			// 2.查出各电桩在指定预约日期的预约单
			$appointments = [];
			if(!empty($chargers)){
				$ids = [];
				foreach($chargers as $charger){
					$ids[] = $charger['id'];
				}
				$appointments = ChargeAppointment::find()
							->select(['id','`code`','appointed_date','time_start','time_end','chargerid'])
							->where(['and',['in','chargerid',$ids],"appointed_date='{$appointedDate}'",'is_del=0'])
							->asArray()->all(); 
			}
			
			// 3.将预约单组合到电桩里
			if(!empty($appointments)){
				$tmpArr = []; 
				$idArr = [];
				foreach($appointments as $appointment){
					$chargerid = $appointment['chargerid'];
					$idArr[] = $chargerid;
					$tmpArr[$chargerid][] = $appointment;
				}
				$tmpArr['chargerids'] = array_unique($idArr);
				foreach($chargers as $k=>$charger){
					$id = $charger['id'];
					if(in_array($id,$tmpArr['chargerids'])){
						$chargers[$k]['appointments'] = $tmpArr[$id]; 
					}else{
						$chargers[$k]['appointments'] = [];
					}
				}
			}else{
				foreach($chargers as $k=>$charger){
					$chargers[$k]['appointments'] = [];
				}
			}
			
			$returnArr = [];
			$returnArr['rows'] = $chargers;
			$returnArr['total'] = $total;
			echo json_encode($returnArr);     
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
				['content'=>'预约单编号','font-weight'=>true,'width'=>'20','rowspan'=>2,'valign'=>'center'],		
				['content'=>'预约手机号','font-weight'=>true,'width'=>'15','rowspan'=>2,'valign'=>'center'],
				['content'=>'预约时间段','font-weight'=>true,'width'=>'25','rowspan'=>2,'valign'=>'center'],
				['content'=>'预约的电桩','font-weight'=>true,'colspan'=>3,'align'=>'center'], 					// 跨4列
				['content'=>'是否完成','font-weight'=>true,'width'=>'15','rowspan'=>2,'valign'=>'center'],
				['content'=>'登记日期','font-weight'=>true,'width'=>'15','rowspan'=>2,'valign'=>'center'],
				['content'=>'备注','font-weight'=>true,'width'=>'30','rowspan'=>2,'valign'=>'center']
			],
			[ 
				[],[], [],
				['content'=>'电桩编号','font-weight'=>true,'width'=>'15'],
				['content'=>'连接方式','font-weight'=>true,'width'=>'15'],
				['content'=>'安装地点','font-weight'=>true,'width'=>'30'],
				[],[],[]
			]
		];			
		
		// 要查的字段，与导出的excel表头对应
		$selectArr = [		
			'{{%charge_appointment}}.code',
			'{{%vip}}.mobile',
			"appointed_date_time"=>"CONCAT_WS(' ',appointed_date,(CONCAT_WS('~',time_start,time_end)))", // CONCAT_WS()以某分隔符拼接多列值
			'{{%charge_spots}}.code_from_compony',
			'{{%charge_spots}}.connection_type',
			'{{%charge_spots}}.install_site',		
			'{{%charge_appointment}}.isfinished',
			'{{%charge_appointment}}.systime',
			'{{%charge_appointment}}.mark'
		];
        $query = ChargeAppointment::find()
			->select($selectArr)
            ->joinWith('vip',false)
            ->joinWith('charger',false)
            ->where(['{{%charge_appointment}}.is_del'=>0]);
        //查询条件
        $query->andFilterWhere(['like','code',yii::$app->request->get('code')]);
        $query->andFilterWhere(['like','{{%vip}}.mobile',yii::$app->request->get('mobile')]);
        $timeStart = yii::$app->request->get('appointed_date_start');
        if($timeStart){
            $query->andFilterWhere(['>=','appointed_date',$timeStart]);
        }
        $timeEnd = yii::$app->request->get('appointed_date_end');
        if($timeEnd){
            $query->andFilterWhere(['<=','appointed_date',$timeEnd]);
        }
		$data = $query->asArray()->all(); 
		// print_r($data);exit;
		
		$excel = new Excel();
        $excel->setHeader([
            'creator'=>'皓峰通讯',
            'lastModifiedBy'=>'hao feng tong xun',
            'title'=>'charge appointment',
            'subject'=>'ChargeAppointment',
            'description'=>'charge appointment list',
            'keywords'=>'charge appointment list',
            'category'=>'charge appointment list'
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
				if($conf == 'connection_type') {
					$v = $item['connection_type'];
					$item['connection_type'] = $configs[$conf][$v]['text'];
				}
			}		
            $item['systime'] = $item['systime'] ? date('Y-m-d',$item['systime']) : '';
            $item['isfinished'] = $item['isfinished'] ? '是' : '否';
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
        header("Content-Disposition: attachment; filename=".iconv('utf-8','gbk','预约列表_'.date('YmdHis').'.xls')); 
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');	
    }
    
}