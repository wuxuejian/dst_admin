<?php
/**
 * @Desc:	会员账户变动记录管理控制器 
 * @author: chengwk
 * @date:	2015-12-25
 */
namespace backend\modules\vip\controllers;
use backend\controllers\BaseController;
use yii;
use yii\base\Object;
use yii\data\Pagination;
use backend\models\ConfigCategory;
use backend\models\Vip;
use backend\models\VipMoneyChange;
use common\models\Excel;

class VipMoneyChangeController extends BaseController
{
    public function actionIndex()
    {	
		$data['buttons'] = $this->getCurrentActionBtn();
        return $this->render('index',$data); 
    }
    
    /**
     * 获取列表
     */
    public function actionGetList()
    {
        $query = VipMoneyChange::find()
			->select([
				'{{%vip_money_change}}.*',
				'vip_code'=>'{{%vip}}.code',
				'vip_name'=>'{{%vip}}.client',
				'vip_mobile'=>'{{%vip}}.mobile',
				'vip_id'=>'{{%vip}}.id'	
			])
			->joinWith('vip',false);
        //查询条件
        $query->andFilterWhere(['like','CONCAT("999",LPAD({{%vip_money_change}}.`vip_id`,13,0))',yii::$app->request->get('card_no')]);
        $query->andFilterWhere(['like','{{%vip}}.client',yii::$app->request->get('vip_name')]);
        $query->andFilterWhere(['like','{{%vip}}.mobile',yii::$app->request->get('vip_mobile')]);
        $query->andFilterWhere(['like','{{%vip_money_change}}.reason',yii::$app->request->get('reason')]);
        $start_systime = yii::$app->request->get('start_systime');
        if($start_systime){
        	$query->andFilterWhere(['>=','{{%vip_money_change}}.systime', strtotime($start_systime)]);
        }
        $end_systime = yii::$app->request->get('end_systime');
        if($end_systime){
        	$query->andFilterWhere(['<=','{{%vip_money_change}}.systime', strtotime($end_systime)]);
        }
        
//         exit($query->createCommand()->sql);
        
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
        if($data){
        	foreach($data as &$item){
        		$item['card_no'] = '999'.str_pad($item['vip_id'],13,0,STR_PAD_LEFT);
        	}
        }
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        echo json_encode($returnArr);
    }

	
	
	/**
     * 导出Excel
     */
    public function actionExportGridData()
    {
    	set_time_limit(0);
		// 构建导出的excel表头（这里有2行表头）
		$excHeaders = [
			[
                ['content'=>'会员编号','font-weight'=>true,'width'=>'15'],
                ['content'=>'电卡编号','font-weight'=>true,'width'=>'20'],
                ['content'=>'会员名称','font-weight'=>true,'width'=>'15'],
                ['content'=>'会员手机号','font-weight'=>true,'width'=>'15'],
                ['content'=>'变动金额','font-weight'=>true,'width'=>'15'],
                ['content'=>'变动原由','font-weight'=>true,'width'=>'30'],
                ['content'=>'变动时间','font-weight'=>true,'width'=>'15'],
                ['content'=>'备注','font-weight'=>true,'width'=>'30']
			]
		];			
		
		// 要查的字段，与导出的excel表头对应
		$selectArr = [		
			'{{%vip}}.code',
			'vip_id'=>'{{%vip}}.id',		
			'{{%vip}}.client',
			'{{%vip}}.mobile',
			'{{%vip_money_change}}.change_money',
			'{{%vip_money_change}}.reason',
			'{{%vip_money_change}}.systime',
			'{{%vip_money_change}}.note'
		];

        $query = VipMoneyChange::find()
            ->select($selectArr)
            ->joinWith('vip',false);
        //查询条件
        $query->andFilterWhere(['like','{{%vip}}.code',yii::$app->request->get('vip_code')]);
        $query->andFilterWhere(['like','{{%vip}}.client',yii::$app->request->get('vip_name')]);
        $query->andFilterWhere(['like','{{%vip}}.mobile',yii::$app->request->get('vip_mobile')]);
        $query->andFilterWhere(['like','{{%vip_money_change}}.reason',yii::$app->request->get('reason')]);
        $start_systime = yii::$app->request->get('start_systime');
        if($start_systime){
        	$query->andFilterWhere(['>=','{{%vip_money_change}}.systime', strtotime($start_systime)]);
        }
        $end_systime = yii::$app->request->get('end_systime');
        if($end_systime){
        	$query->andFilterWhere(['<=','{{%vip_money_change}}.systime', strtotime($end_systime)]);
        }
		$data = $query->asArray()->all();
		 /* echo '<pre>';
		var_dump($data);exit();   */
		
		
		if($data){
			foreach($data as $key=>$item){
				//加空格 处理 PHPExcel导出的长数字被科学计数法转换丢失数据
				$data[$key]['vip_id'] = '999'.str_pad($item['vip_id'],13,0,STR_PAD_LEFT).' '; 
			}
		} 
		/*   echo '<pre>';
		var_dump($data);exit();  */  
		
		$excel = new Excel();
        $excel->setHeader([
            'creator'=>'皓峰通讯',
            'lastModifiedBy'=>'hao feng tong xun',
            'title'=>'vip_money_change',
            'subject'=>'vip_money_change',
            'description'=>'vip_money_change',
            'keywords'=>'vip_money_change',
            'category'=>'vip_money_change'
        ]);
		
		//---向excel添加表头-------------------------------------
		foreach($excHeaders as $lineData){
			$excel->addLineToExcel($lineData);
		}
		//---向excel添加具体数据----------------------------------
        foreach($data as $item){
            $lineData = [];		
            $item['systime'] = $item['systime'] ? date('Y-m-d',(int)$item['systime']) : '';
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
        header("Content-Disposition: attachment; filename=".iconv('utf-8','gbk','会员账户变动记录_'.date('YmdHis').'.xls'));
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');	
    }
    
}