<?php
/**
 * @Desc:	会员通知管理控制器 
 * @author: chengwk
 * @date:	2015-12-25
 */
namespace backend\modules\vip\controllers;
use backend\controllers\BaseController;
use yii;
use yii\base\Object;
use yii\data\Pagination;
use yii\web\UploadedFile;
use backend\models\ConfigCategory;
use backend\models\Vip;
use backend\models\VipNotice;
use common\models\Excel;

class VipNoticeController extends BaseController
{
    public function actionIndex()
    {
        //获取combo配置数据
        $configItems = ['vn_type'];
        $data['config'] = (new ConfigCategory())->getCategoryConfig($configItems,'value');
		$data['buttons'] = $this->getCurrentActionBtn();
        return $this->render('index',$data); 
    }
    
    /**
     * 获取列表
     */
    public function actionGetList()
    {
        $query = VipNotice::find()
            ->select([
                'vn_id','vn_code','vn_title','vn_type',
                'vn_icon_path','vn_public_time','vn_mark','vn_systime','vn_sysuser'
            ])
			->where(['vn_is_del'=>0]);
        //查询条件
        $query->andFilterWhere(['like','vn_code',yii::$app->request->get('vn_code')]);
        $query->andFilterWhere(['like','vn_title',yii::$app->request->get('vn_title')]);
        $query->andFilterWhere(['=','vn_type',yii::$app->request->get('vn_type')]);
        $total = $query->count();
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
		//排序
		if(yii::$app->request->get('sort')){
			$field = yii::$app->request->get('sort');		//field
			$direction = yii::$app->request->get('order');  //asc or desc
			$orderStr = $field .' '. $direction;
		}else{
			$orderStr = 'vn_id desc';
		}
        $data = $query->offset($pages->offset)->limit($pages->limit)->orderBy($orderStr)->asArray()->all();
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        echo json_encode($returnArr);
    }
    
    /**
     * 新增/修改
     */
    public function actionAdd()
    {
        if(yii::$app->request->isPost){
            $returnArr = ['status'=>false,'info'=>'出错了'];
            $model = new VipNotice;
            $model->load(yii::$app->request->post(),'');
            $model->vn_code = 'VN'.date('YmdHis').mt_rand(100,999);
            $model->vn_systime = time();
            $model->vn_sysuser_id = $_SESSION['backend']['adminInfo']['id'];
            $model->vn_sysuser = $_SESSION['backend']['adminInfo']['username'];
            if($model->save(true)){
                $returnArr['status'] = true;
                $returnArr['info'] = '新增通知成功！';
            }else{
                $returnArr['status'] = false;
                $error = $model->getErrors();
                if($error){
                    $returnArr['info'] = join('',array_column($error,0));
                }else{
                    $returnArr['info'] = '未知错误';
                }
            }
            return json_encode($returnArr); 
        }else{
            //获取combo配置数据
            $configItems = ['vn_type'];
            $config = (new ConfigCategory())->getCategoryConfig($configItems,'value');
            return $this->render('add',[
                'config'=>$config
            ]);
		}
    }

    /**
     * 修改
     */
    public function actionEdit(){
        if(yii::$app->request->isPost){
            $returnArr = ['status'=>false,'info'=>'出错了'];
            $vnId = intval(yii::$app->request->post('vn_id')); // 通知id
            $model = VipNotice::findOne($vnId);
            if(!$model){
                return;
            }
            $model->load(yii::$app->request->post(),'');
            if($model->save(true)){
                $returnArr['status'] = true;
                $returnArr['info'] = '修改通知成功！';
            }else{
                $returnArr['status'] = false;
                $error = $model->getErrors();
                if($error){
                    $returnArr['info'] = join('',array_column($error,0));
                }else{
                    $returnArr['info'] = '未知错误';
                }
            }
            return json_encode($returnArr);
        }else{
            $vnId = intval(yii::$app->request->get('vn_id')); // 修改时才传递会员通知id
            if(!$vnId){
                return '参数错误！';
            }
            $vipNotice = VipNotice::find()
                ->where(['vn_id'=>$vnId])
                ->asArray()->one();
            if(!$vipNotice){
                return '记录不存在！';
            }
            //获取combo配置数据
            $configItems = ['vn_type'];
            $config = (new ConfigCategory())->getCategoryConfig($configItems,'value');
            return $this->render('edit',[
                'config'=>$config,
                'vipNoticeInfo'=>$vipNotice
            ]);
        }
    }


    /**
     * 删除(可批量)
     */
    public function actionRemove()
    {
        $idStr = trim(yii::$app->request->post('idStr')) or die("Param 'idStr' is required.");
        $idStr = rtrim($idStr,','); // 去掉id字符串最后的逗号
        $ids = explode(',',$idStr);
        $affected = VipNotice::updateAll(['vn_is_del'=>1],['vn_id'=>$ids]);
        $returnArr = [];
        if($affected){
            $returnArr['status'] = true;
            $returnArr['info'] = '通知删除成功！';
        }else{
            $returnArr['status'] = false;
            $returnArr['info'] = '通知删除失败！';
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
				['content'=>'通知编号','font-weight'=>true,'width'=>'25'],
				['content'=>'通知标题','font-weight'=>true,'width'=>'25'],
				['content'=>'通知类型','font-weight'=>true,'width'=>'15'],
				['content'=>'发布时间','font-weight'=>true,'width'=>'15'],
				['content'=>'备注','font-weight'=>true,'width'=>'30'],
				['content'=>'创建时间','font-weight'=>true,'width'=>'20'],
				['content'=>'创建人员','font-weight'=>true,'width'=>'15']
			]
		];			
		
		// 要查的字段，与导出的excel表头对应
		$selectArr = [		
			'{{%vip_notice}}.vn_code',
			'{{%vip_notice}}.vn_title',
			'{{%vip_notice}}.vn_type',
			'{{%vip_notice}}.vn_public_time',
			'{{%vip_notice}}.vn_mark',
			'{{%vip_notice}}.vn_systime',
			'{{%vip_notice}}.vn_sysuser'
		];

        $query = VipNotice::find()
			->select($selectArr)
            ->where(['vn_is_del'=>0]);;
        //查询条件
        $query->andFilterWhere(['like','vn_code',yii::$app->request->get('vn_code')]);
        $query->andFilterWhere(['like','vn_title',yii::$app->request->get('vn_title')]);
        $query->andFilterWhere(['=','vn_type',yii::$app->request->get('vn_type')]);
		$data = $query->asArray()->all(); 
		// print_r($data);exit;
		
		$excel = new Excel();
        $excel->setHeader([
            'creator'=>'皓峰通讯',
            'lastModifiedBy'=>'hao feng tong xun',
            'title'=>'VipNotice',
            'subject'=>'VipNotice',
            'description'=>'VipNotice list',
            'keywords'=>'VipNotice list',
            'category'=>'VipNotice list'
        ]);
		
		//---向excel添加表头-------------------------------------
		foreach($excHeaders as $lineData){
			$excel->addLineToExcel($lineData);
		}
        
		//---向excel添加具体数据----------------------------------
		$configItems = ['vn_type'];
        $configs = (new ConfigCategory())->getCategoryConfig($configItems,'value'); 
        foreach($data as $item){			
            $lineData = [];		
			// 将combox配置值改以文本显示
			foreach($configItems as $conf) { 
				if(isset($item[$conf]) && $item[$conf]) {
					$item[$conf] = $configs[$conf][$item[$conf]]['text'];
				}
			}		
            $item['vn_systime'] = $item['vn_systime'] ? date('Y-m-d H:i:s',$item['vn_systime']) : '';
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
        header("Content-Disposition: attachment; filename=".iconv('utf-8','gbk','通知列表_'.date('YmdHis').'.xls')); 
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');	
    }

    /**
     * 上传新闻缩略图
     */
    public function actionUploadThumb(){
        if(yii::$app->request->isPost){
            $returnArr = [];
            $returnArr['status'] = false;
            $returnArr['info'] = '';
            $callback = yii::$app->request->post('callback');
            $upload = UploadedFile::getInstanceByName('vn_icon_path');
            $fileExt = $upload->getExtension();
            $allowExt = ['jpg','png','jpeg','gif'];
            if(!in_array($fileExt,$allowExt)){
                $returnArr['info'] = '文件格式错误！';
                return '<script>window.parent.'.$callback.'('.json_encode($returnArr).');</script>';
            }
            $fileName = uniqid().'.'.$fileExt;
            // 处理上传图片的储存路径，这里指定在与入口文件同级的uploads目录之下。
            $storePath = './uploads/image/notice/';
            if(!is_dir($storePath)){
                mkdir($storePath,0777,true);
            }
            $storePath .= $fileName;
            if($upload->saveAs($storePath)){
                $returnArr['status'] = true;
                $returnArr['info'] = $fileName;
                $returnArr['storePath'] = $storePath;
            }else{
                $returnArr['info'] = $upload->error;
            }
            return '<script>window.parent.'.$callback.'('.json_encode($returnArr).');</script>';
        }else{
            return $this->render('upload-thumb',[
                'callback'=>yii::$app->request->get('callback'),
                'target'=>yii::$app->request->get('target'),
            ]);
        }
    }
    
}