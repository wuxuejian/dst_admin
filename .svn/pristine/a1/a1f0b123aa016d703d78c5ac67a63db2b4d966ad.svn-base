<?php
/**
 * @Desc:	会员收藏管理控制器 
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
use backend\models\VipFavorite;
use common\models\Excel;

class VipFavoriteController extends BaseController
{
    public function actionIndex()
    {	
        $data['config'] = (new ConfigCategory())->getCategoryConfig(['connection_type'],'value'); 
		$data['buttons'] = $this->getCurrentActionBtn();
        return $this->render('index',$data); 
    }
    
    /**
     * 获取收藏列表
     */
    public function actionGetList()
    {
        $query = VipFavorite::find()
			->select([
				'{{%vip_favorite}}.*',
				'{{%charge_station}}.`cs_id`',
                '{{%charge_station}}.`cs_code`',
                '{{%charge_station}}.`cs_name`',
                '{{%charge_station}}.`cs_address`',
				'vip_code'=>'{{%vip}}.`code`',
				'vip_name'=>'{{%vip}}.`client`',
                'vip_mobile'=>'{{%vip}}.`mobile`'
			])
			->joinWith('chargeStation',false)
			->joinWith('vip',false)
			->where(['{{%vip_favorite}}.is_del'=>0]);
        //查询条件
        $query->andFilterWhere(['like','{{%vip}}.code',yii::$app->request->get('vip_code')]);
        $query->andFilterWhere(['like','{{%vip}}.mobile',yii::$app->request->get('vip_mobile')]);
        $total = $query->count();
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
		//排序
		if(yii::$app->request->get('sort')){
			$field = yii::$app->request->get('sort');		//field
			$direction = yii::$app->request->get('order');  //asc or desc
			$orderStr = $field .' '. $direction;
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
     * 新增收藏信息  
     */
    public function actionAdd()
    {
        if(yii::$app->request->isPost){
            $model = new VipFavorite(); 
			$formData = yii::$app->request->post('formData');
			parse_str($formData,$arr); //parse_str()把查询字符串解析成数组并存入变量
			//print_r($arr);exit;
            $model->load($arr,'');
            $returnArr = [];
            if($model->validate()){
                $model->systime = time();
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '新增收藏成功！';
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '新增收藏失败！';
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
			return $this->render('vipFavoriteInfoWin',[
				'config'=>$config,
				'myData'=>['action'=>'add']
			]);
		}
    }
    
    
    //修改收藏信息
    public function actionEdit()
    {
        if(yii::$app->request->isPost){
            $id = intval(yii::$app->request->post('id')) or die("Not pass 'id'.");
            $model = VipFavorite::findOne(['id'=>$id]) or die('Not find corresponding data.');
			$formData = yii::$app->request->post('formData');
			parse_str($formData,$arr); //parse_str()把查询字符串解析成数组并存入变量			
            $model->load($arr,'');
            $returnArr = []; 
            if($model->validate()){
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '修改收藏成功！';
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '修改收藏失败！';
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
			$vipFavoriteInfo = VipFavorite::find()
				->select(['{{%vip_favorite}}.*','code_from_compony','connection_type','install_site'])
				->joinWith('charger')
				->where(['{{%vip_favorite}}.id'=>$id])
				->asArray()->one() or die('读取数据失败！');
			return $this->render('vipFavoriteInfoWin',[
				'config'=>$config,
				'myData'=>[
					'action'=>'edit',
					'vipFavoriteInfo'=>$vipFavoriteInfo
				]
			]);
		}
    }
       

    /**
     * 删除收藏单
     */
    public function actionRemove()
    {
        $id = intval(yii::$app->request->get('id')) or die("Not pass 'id'.");
        $returnArr = [];
        if(VipFavorite::updateAll(['is_del'=>1],['id'=>$id])){
            $returnArr['status'] = true;
            $returnArr['info'] = '收藏单删除成功！';
        }else{
            $returnArr['status'] = false;
            $returnArr['info'] = '收藏单删除失败！';
        }
        echo json_encode($returnArr);
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
				['content'=>'收藏的电桩','font-weight'=>true,'colspan'=>3,'align'=>'center'], 					// 跨4列
				['content'=>'登记日期','font-weight'=>true,'width'=>'15','rowspan'=>2,'valign'=>'center'],
				['content'=>'备注','font-weight'=>true,'width'=>'30','rowspan'=>2,'valign'=>'center']
			],
			[ 
				[],[],[],
				// ['content'=>'电桩ID','font-weight'=>true,'width'=>'10'],
				['content'=>'电桩公司编号','font-weight'=>true,'width'=>'15'],
				['content'=>'连接方式','font-weight'=>true,'width'=>'15'],
				['content'=>'安装地点','font-weight'=>true,'width'=>'30'],
				[],[]
			]
		];			
		
		// 要查的字段，与导出的excel表头对应
		$selectArr = [		
			'{{%vip}}.code',
			'{{%vip}}.client',
			'{{%vip}}.mobile',
			// '{{%vip_favorite}}.chargerid',
			'{{%charge_station}}.cs_code',
			'{{%charge_station}}.cs_name',
			'{{%charge_station}}.cs_address',		
			'{{%vip_favorite}}.systime',
			'{{%vip_favorite}}.mark'
		];

        $query = VipFavorite::find()
			->select($selectArr)
            ->joinWith('chargeStation',false)
            ->joinWith('vip',false)
            ->where(['{{%vip_favorite}}.is_del'=>0]);
        //查询条件
        $query->andFilterWhere(['like','{{%vip}}.code',yii::$app->request->get('vip_code')]);
        $query->andFilterWhere(['like','{{%vip}}.mobile',yii::$app->request->get('vip_mobile')]);
		$data = $query->asArray()->all(); 
		// print_r($data);exit;
		
		$excel = new Excel();
        $excel->setHeader([
            'creator'=>'皓峰通讯',
            'lastModifiedBy'=>'hao feng tong xun',
            'title'=>'vip favorite',
            'subject'=>'VipFavorite',
            'description'=>'vip favorite list',
            'keywords'=>'vip favorite list',
            'category'=>'vip favorite list'
        ]);
		
		//---向excel添加表头-------------------------------------
		foreach($excHeaders as $lineData){
			$excel->addLineToExcel($lineData);
		}
        
		//---向excel添加具体数据----------------------------------
		/*$configItems = ['connection_type'];
        $configs = (new ConfigCategory())->getCategoryConfig($configItems,'value'); */
        foreach($data as $item){			
            $lineData = [];		
			// 将combox配置值改以文本显示
			/*foreach($configItems as $conf) { 
				if($conf == 'connection_type') {
					$v = $item['connection_type'];
					$item['connection_type'] = $configs[$conf][$v]['text'];
				}
			}*/		
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
        header("Content-Disposition: attachment; filename=".iconv('utf-8','gbk','收藏列表_'.date('YmdHis').'.xls')); 
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');	
    }
    
}