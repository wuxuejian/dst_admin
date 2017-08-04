<?php
/**
 * @Desc:	会员管理控制器 
 * @author: chengwk
 * @date:	2015-10-19
 */
namespace backend\modules\vip\controllers;
use backend\controllers\BaseController;
use yii;
use yii\base\Object;
use yii\data\Pagination;
use backend\models\ConfigCategory;
use backend\models\Vip;
use backend\models\Vehicle;
use backend\models\VipMoneyChange;
use backend\classes\UserLog;//日志类
use common\models\Excel;

class VipController extends BaseController
{
    public function actionIndex()
    {	
        $data['config'] = (new ConfigCategory())->getCategoryConfig(['connection_type'],'value');
		$data['buttons'] = $this->getCurrentActionBtn();
        return $this->render('index',$data); 
    }
    
    /**
     * 获取列表
     */
    public function actionGetList()
    {
        $query = Vip::find()
				->joinWith('vehicle',true)
				->where('{{%vip}}.`is_del` = 0');
        //查询条件
        $query->andFilterWhere(['like','code',yii::$app->request->get('code')]);
        $query->andFilterWhere(['like','client',yii::$app->request->get('client')]);
        $query->andFilterWhere(['like','mobile',yii::$app->request->get('mobile')]);
        $query->andFilterWhere(['like','vehicle',yii::$app->request->get('vehicle')]);
        $query->andFilterWhere(['like','CONCAT("999",LPAD({{%vip}}.`id`,13,0))',yii::$app->request->get('card_no')]);
        $timeStart = yii::$app->request->get('systime_start');
		if($timeStart){
            $query->andFilterWhere(['>=','`systime`',strtotime($timeStart)]);
        }
        $timeEnd = yii::$app->request->get('systime_end');
        if($timeEnd){
            $query->andFilterWhere(['<=','`systime`',strtotime($timeEnd.' 23:59:59')]);
        }
        $query2 = clone $query;
        $total = $query->groupBy('{{%vip}}.`id`')->count(); // 这里因会员存在多车辆情况，要按id分组计算总数
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
		//排序
        $field = yii::$app->request->get('sort');
        $direction = yii::$app->request->get('order','desc');
        if($field){
            switch($field){
                case 'card_no':
                    $orderStr = '{{%vip}}.`id` ';
                    break;
                default:
                    $orderStr = $field.' ';
            }
            $orderStr .= $direction;
		}else{
			$orderStr = '{{%vip}}.id DESC';
		}
        $data = $query2->offset($pages->offset)->limit($pages->limit)->orderBy($orderStr)->asArray()->all();
        if($data){
            foreach($data as &$item){
                $item['card_no'] = '999'.str_pad($item['id'],13,0,STR_PAD_LEFT);
            }
        }
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        // 表格底部增加合计行（这里因会员存在多车辆情况导致金额重复计算，只能按id分组查所有唯一记录再合计）
        $results = $query->select(['`cs_vip`.`id`','`money_acount`'])->offset(0)->limit(-1)->asArray()->all();
        $money_acount = array_sum( array_column($results, 'money_acount') );
        $returnArr['footer'] = [[
            'code'=>'合计：',
            'money_acount'=>$money_acount
        ]];
        return json_encode($returnArr);
    }
    
    /**
     * 新增会员信息
     */
    public function actionAdd()
    {
        if(yii::$app->request->isPost){
            $model = new Vip(); 
			$formData = yii::$app->request->post('formData');
			parse_str($formData,$arr); //parse_str()把查询字符串解析成数组并存入变量
            $model->load($arr,'');
            $returnArr = [];
            if($model->validate()){
                $model->code = 'VIP'.date('YmdHis').mt_rand(100,999);
				$model->systime = time();
                $model->sysuser = $_SESSION['backend']['adminInfo']['username'];
                if($model->save(false)){ 
                    $returnArr['status'] = true;
                    $returnArr['info'] = '保存成功！';
                    $returnArr['vipData'] = $model->getAttributes();
					//继续保存该会员的车辆信息
					$gridData = yii::$app->request->post('gridData'); 
					if($gridData){
						$vipId = $model->id;
						$this->saveVehiclesForVip($vipId,$gridData);
					}
                    //记录日志
                    UserLog::log('会员管理-新增会员【' . $model->code . '】','sys');
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '保存失败！';
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
			$config = $configCategoryModel->getCategoryConfig($configItems); 
			return $this->render('vipInfoWin',[
				'config'=>$config,
				'vipId'=>0,
				'myData'=>['action'=>'add']
			]);
		}
    }
    
    
    //修改或仅查看会员信息
    public function actionEdit()
    {
        if(yii::$app->request->isPost){
            $formData = yii::$app->request->post('formData');
            parse_str($formData,$arr); //parse_str()把查询字符串解析成数组并存入变量
            $model = Vip::findOne(['id'=>$arr['id']]) or die('Not find record.');
            $model->load($arr,'');
            $returnArr = []; 
            if($model->validate()){
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '保存成功！';					
					$returnArr['vipData'] = $model->getAttributes();
					//继续保存该会员的车辆信息
					$gridData = yii::$app->request->post('gridData'); 
					if($gridData){
						$vipId = $model->id;
						$this->saveVehiclesForVip($vipId,$gridData,true);
					}
                    //记录日志
                    UserLog::log('会员管理-修改会员【' . $model->code . '】','sys');
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '保存失败！';
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
			$config = $configCategoryModel->getCategoryConfig($configItems);
			$vipInfo = Vip::find()->where(['id'=>$id])->asArray()->one() or die('读取数据失败！');
			return $this->render('vipInfoWin',[
				'config'=>$config,
				'vipId'=>$id,
				'myData'=>[
					'action'=>'edit',
					'vipInfo'=>$vipInfo
				]
			]);
		}
    }
    
	
    /**
     * 为某会员保存车辆信息
     */
    protected function saveVehiclesForVip($vipId,$gridData,$isOldVip=false)
    {
        if($vipId && !empty($gridData)){
			//若老会员的某辆旧车不存在了，则设置is_del=1表示已删除
			if($isOldVip){
				$_vhcIds = [];
				foreach($gridData as $row){
					if(isset($row['id']) && $row['id']){
						$_vhcIds[] = $row['id'];
					}
				}
				$vhcs = Vehicle::find()->select(['id','vehicle'])->where(['vip_id'=>$vipId])->asArray()->all();
                $diffIds = array_diff(array_column($vhcs,'id'),$_vhcIds);
                Vehicle::updateAll(['is_del'=>1],['id'=>$diffIds]);
			}
			//保存新车辆或更新旧车辆
			foreach($gridData as $row){ 
				if(isset($row['vehicle']) && $row['vehicle']){
					if(isset($row['isNewRecord']) && $row['isNewRecord']){
						$vehicleModel = new Vehicle();
						$vehicleModel->vip_id = $vipId;
						$vehicleModel->vehicle = $row['vehicle'];
						$vehicleModel->vhc_model = $row['vhc_model'];
						$vehicleModel->vhc_con_type = $row['vhc_con_type'];
						$vehicleModel->mark = $row['mark'];
						$vehicleModel->save();
					}else{
						$vehicleModel = Vehicle::find()->where(['id'=>$row['id'],'vip_id'=>$vipId])->one();
						$vehicleModel->vehicle = $row['vehicle'];
						$vehicleModel->vhc_model = $row['vhc_model'];
						$vehicleModel->vhc_con_type = $row['vhc_con_type'];
						$vehicleModel->mark = $row['mark'];
						$vehicleModel->update();
					}
					
				}
			}
		}
    }
    
	
    /**
     * 删除会员
     */
    public function actionRemove()
    {
        $id = intval(yii::$app->request->get('id')) or die("Not pass 'id'.");
        $returnArr = [];
        if(Vip::updateAll(['is_del'=>1],['id'=>$id])){
            $returnArr['status'] = true;
            $returnArr['info'] = '删除成功！';
            //记录日志
            UserLog::log("会员管理-删除会员（id：{$id}）",'sys');
        }else{
            $returnArr['status'] = false;
            $returnArr['info'] = '删除失败！';
        }
        echo json_encode($returnArr);
    }

	
	
	/**
     * 导出Excel
     */
    public function actionExportGridData(){
    	set_time_limit(0);
        $excel = new Excel();
        $excel->setHeader([
            'creator'=>'皓峰通讯',
            'lastModifiedBy'=>'hao feng tong xun',
            'title'=>'vip',
            'subject'=>'vip',
            'description'=>'vip list',
            'keywords'=>'vip list',
            'category'=>'vip list'
        ]);
        // 构建导出的excel表头
        $excHeaders = [
            [
                ['content'=>'会员编号','font-weight'=>true,'width'=>'25'],
                ['content'=>'用户来源','font-weight'=>true,'width'=>'25'],
                ['content'=>'会员类型','font-weight'=>true,'width'=>'25'],
                ['content'=>'会员名称','font-weight'=>true,'width'=>'20'],
                ['content'=>'会员手机号','font-weight'=>true,'width'=>'15'],
                ['content'=>'性别','font-weight'=>true,'width'=>'10'],
                ['content'=>'邮箱','font-weight'=>true,'width'=>'15'],
                
                ['content'=>'身份认证','font-weight'=>true,'width'=>'15'],
                ['content'=>'驾照认证','font-weight'=>true,'width'=>'15'],
                ['content'=>'营业执照认证','font-weight'=>true,'width'=>'15'],
                
                ['content'=>'电卡编号','font-weight'=>true,'width'=>'20'],
                ['content'=>'电卡余额','font-weight'=>true,'width'=>'15'],
                ['content'=>'押金余额','font-weight'=>true,'width'=>'15'],
                ['content'=>'备注','font-weight'=>true,'width'=>'15'],
                ['content'=>'登记人员','font-weight'=>true,'width'=>'10'],
                ['content'=>'登记时间','font-weight'=>true,'width'=>'10'],
                ['content'=>'所有车辆','font-weight'=>true,'width'=>'40'] //末尾插入'所有车辆'列
            ]
        ];
        //---向excel添加表头----------
        foreach($excHeaders as $lineData){
            $excel->addLineToExcel($lineData);
        }

        //查询数据，查询字段与构建的excel表头对应
        $query = Vip::find()
                ->select([
                    '{{%vip}}.`id`', //为什么这里不查此id就报错？
                    '{{%vip}}.`code`',
                	'{{%vip}}.`type`',
                	'{{%vip}}.`category`',
                    '{{%vip}}.`client`',
                    '{{%vip}}.`mobile`',
                    '{{%vip}}.`sex`',
                    '{{%vip}}.`email`',
                	'{{%vip}}.`id_card_auth`',
                	'{{%vip}}.`driving_card_auth`',
                	'{{%vip}}.`business_license_auth`',        
                    'card_no'=>'{{%vip}}.`id`', //“电卡编号”占位
                    '{{%vip}}.`money_acount`',
                	'{{%vip}}.`foregift_acount`',
                    '{{%vip}}.`mark`',
                    '{{%vip}}.`sysuser`',
                    '{{%vip}}.`systime`'
                ])
				->joinWith('vehicle',true)
                ->where('{{%vip}}.`is_del` = 0');
        //查询条件开始
        $query->andFilterWhere(['like','code',yii::$app->request->get('code')]);
        $query->andFilterWhere(['like','client',yii::$app->request->get('client')]);
        $query->andFilterWhere(['like','mobile',yii::$app->request->get('mobile')]);
        $query->andFilterWhere(['like','vehicle',yii::$app->request->get('vehicle')]);
        $query->andFilterWhere(['like','CONCAT("999",LPAD({{%vip}}.`id`,13,0))',yii::$app->request->get('card_no')]);
        $timeStart = yii::$app->request->get('systime_start');
        if($timeStart){
            $query->andFilterWhere(['>=','`systime`',strtotime($timeStart)]);
        }
        $timeEnd = yii::$app->request->get('systime_end');
        if($timeEnd){
            $query->andFilterWhere(['<=','`systime`',strtotime($timeEnd.' 23:59:59')]);
        }
		//查询条件结束
		$data = $query->groupBy('{{%vip}}.`id`')->orderBy('{{%vip}}.`id` DESC')->asArray()->all();
		//print_r($data);exit;
		$categorys = array('','个人','企业');
		$types = array('APP租车','蚂蚁智绿','海吉星','客户司机');
		
		$id_card_auths = array('待认证','已认证','不通过' ,'未认证');
		$driving_card_auths = array('待认证','已认证','不通过','未认证');
		$business_license_auths = array('待认证','已认证','不通过','未认证');
		
        if($data){
            foreach($data as $item){
                unset($item['id']);
                $item['category'] = @$categorys[@$item['category']];
                $item['type'] = @$types[@$item['type']];
                
                $item['id_card_auth'] = @$id_card_auths[@$item['id_card_auth']];
                $item['driving_card_auth'] = @$driving_card_auths[@$item['driving_card_auth']];
                $item['business_license_auth'] = @$business_license_auths[@$item['business_license_auth']];
                
                
                $item['sex'] = $item['sex'] == 1 ? '男' : '女';
                //电卡编号
                if($item['card_no']){
                    $item['card_no'] = ' 999'.str_pad($item['card_no'],13,0,STR_PAD_LEFT);
                }
                $item['systime'] = $item['systime'] ? date('Y-m-d H:i:s',$item['systime']) : '';
                $lineData = [];
                foreach($item as $k=>$v) {
                    if($k == 'vehicle' && is_array($v)){ // 将车辆信息拼接起来
                        $arr = [];
                        foreach($v as $vhc){
                            $arr[] = $vhc['vehicle'] . '（' . $vhc['vhc_model'] . '）';
                        }
                        $lineData[] = ['content'=>implode('; ',$arr)];
                    }else{
                        $lineData[] = ['content'=>$v];
                    }
                }
                $excel->addLineToExcel($lineData);
            }
        }

        $objPHPExcel = $excel->getPHPExcel();
        header("Content-type: application/octet-stream"); 
        header("Accept-Ranges: bytes"); 
        //header("Accept-Length:".$fileSize); 
        header("Content-Disposition: attachment; filename=".iconv('utf-8','gbk','会员列表导出.xls'));
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');	
    }

    /**
     * 修改会员密码
     * vip/vip/reset-pwd
     */
    public function actionResetPwd(){
        if(yii::$app->request->isPost){
            $returnArr = [
                'status'=>false,
                'info'=>'',
            ];
            $id = yii::$app->request->post('id');
            if(!$id){
                $returnArr['info'] = '参数错误！';
                return json_encode($returnArr);
            }
            $password = yii::$app->request->post('password');
            $password = md5(substr(md5($password),0,30));
            if(Vip::updateAll(['password'=>$password],['id'=>$id])){
                $returnArr['status'] = true;
                $returnArr['info'] = '密码修改成功！';
            }else{
                $returnArr['info'] = '密码没有被修改或修改失败！';
            }
            return json_encode($returnArr);
        }else{
            $id = yii::$app->request->get('id');
            if(!$id){
                return '参数错误！';
            }
            return $this->render('reset-pwd',[
                'id'=>$id
            ]);
        }
    }

    /**
     * 会员金额调剂
     * vip/vip/change-count-money
     */
    public function actionChangeCountMoney(){
        if(yii::$app->request->isPost){
            $returnArr = [
                'status'=>false,
                'info'=>''
            ];
            $vcmModel = new VipMoneyChange;
            $vcmModel->load(yii::$app->request->post(),'');
            $vcmModel->reason = '管理员操作';
            $vcmModel->systime = time();
            if(yii::$app->request->post('type') != 'add'){
                $vcmModel->change_money = 0 - $vcmModel->change_money;
            }
            //开启事务
            $transaction = yii::$app->db->beginTransaction();
            $res1 = $vcmModel->save(true);
            $res2 = Vip::updateAllCounters([
                'money_acount'=>$vcmModel->change_money,
            ],[
                'id'=>$vcmModel->vip_id,
            ]);
            if($res1 && $res2){
                $returnArr['status'] = true;
                $returnArr['info'] = '操作成功！';
                $transaction->commit();//提交事务
            }else{
                $returnArr['info'] = '操作失败！';
                $transaction->rollback();//回滚事务
            }
            return json_encode($returnArr);
        }else{
            $id = yii::$app->request->get('id');
            if(!$id){
                return '参数错误！';
            }
            return $this->render('change-count-money',[
                'id'=>$id
            ]);
        }
    }
    
}