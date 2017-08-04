<?php
/**
 * @Desc: 微信推广活动->【后台管理系统】->【用户信息管理】 控制器
 * @date:	2016-03-08
 */
namespace backend\modules\promotion\controllers;
use backend\controllers\BaseController;
use yii;
use yii\data\Pagination;
use backend\models\VipPromotionSign;
use backend\classes\UserLog;
use common\models\Excel;

class SignInfoController extends BaseController{

    /*
     * 访问“用户信息管理”视图
     */
    public function actionIndex(){
        $buttons = $this->getCurrentActionBtn();
        return $this->render('index',[
            'buttons'=>$buttons,
        ]);
	}

    /**
     * 获取活动注册信息列表
     */
    public function actionGetList(){
        $query = VipPromotionSign::find()
            ->select([
                'id','client','sex','mobile','company','profession','district','systime',
                'invite_code_mine','invite_code_used','is_lock'
            ])
            ->where("open_id != '' AND invite_code_mine !=''  AND code !=''  AND is_del = 0"); //仅查成功注册的信息
        //查询条件开始
        $query->andFilterWhere(['like','`client`',yii::$app->request->get('client')]);
        $query->andFilterWhere(['like','`mobile`',yii::$app->request->get('mobile')]);
        $query->andFilterWhere(['like','`invite_code_mine`',yii::$app->request->get('invite_code_mine')]);
        $query->andFilterWhere(['like','`invite_code_used`',yii::$app->request->get('invite_code_used')]);
        $query->andFilterWhere(['like','`district`',yii::$app->request->get('district')]);
        $query->andFilterWhere(['=','`is_lock`',yii::$app->request->get('is_lock')]);
        $systimeStart = yii::$app->request->get('systime_start');
        if($systimeStart){
            $query->andFilterWhere(['>=','`systime`',strtotime($systimeStart)]);
        }
        $systimeEnd = yii::$app->request->get('systime_end');
        if($systimeEnd){
            $query->andFilterWhere(['<=','`systime`',strtotime($systimeEnd) + 3600*24]);
        }
        //排序
        $sortColumn = yii::$app->request->get('sort');
        $sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
        $orderBy = '';
        if($sortColumn){
            $orderBy = '`'.$sortColumn.'` ';
        }else{
            $orderBy = '`id` ';
        }
        $orderBy .= $sortType;
        $total = $query->count();
        //分页
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)->limit($pages->limit)->orderBy($orderBy)->asArray()->all();
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        return json_encode($returnArr);
    }

    /**
     * 锁定
     */
    public function actionLockOn(){
        $id = intval(yii::$app->request->get('id'));
        if(!$id){
            return json_encode(['status'=>false,'info'=>'未传递参数id']);
        }
        $model = VipPromotionSign::findOne($id);
        $model->is_lock = 1;
        if($model->save()){
            return json_encode(['status'=>true,'info'=>'锁定成功！']);
        }else{
            return json_encode(['status'=>false,'info'=>'锁定失败！']);
        }
    }

   /**
     * 解锁
     */
    public function actionLockOff(){
        $id = intval(yii::$app->request->get('id'));
        if(!$id){
            return json_encode(['status'=>false,'info'=>'未传递参数id']);
        }
        $model = VipPromotionSign::findOne($id);
        $model->is_lock = 0;
        if($model->save()){
            return json_encode(['status'=>true,'info'=>'解锁成功！']);
        }else{
            return json_encode(['status'=>false,'info'=>'解锁失败！']);
        }
    }


    /**
     * 导出Excel
     */
    public function actionExportGridData(){
        // 构建导出的excel表头
        $excHeaders = [
            [
                ['content'=>'姓名','font-weight'=>true,'width'=>'15'],
                ['content'=>'性别','font-weight'=>true,'width'=>'10'],
                ['content'=>'手机','font-weight'=>true,'width'=>'15'],
                ['content'=>'锁定状态','font-weight'=>true,'width'=>'15'],
                ['content'=>'公司','font-weight'=>true,'width'=>'30'],
                ['content'=>'职业','font-weight'=>true,'width'=>'15'],
                ['content'=>'区域','font-weight'=>true,'width'=>'15'],
                ['content'=>'注册日期','font-weight'=>true,'width'=>'20'],
                ['content'=>'邀请码','font-weight'=>true,'width'=>'15']
            ]
        ];

        // 要查的字段，与导出的excel表头对应
        $selectArr = [
            'client',
            'sex',
            'mobile',
            'is_lock',
            'company',
            'profession',
            'district',
            'systime',
            'invite_code_mine'
        ];

        $query = VipPromotionSign::find()
            ->select($selectArr)
            ->where("open_id != '' AND invite_code_mine !=''  AND code !=''  AND is_del = 0"); //仅查成功注册的信息
        //查询条件开始
        $query->andFilterWhere(['like','`client`',yii::$app->request->get('client')]);
        $query->andFilterWhere(['like','`mobile`',yii::$app->request->get('mobile')]);
        $query->andFilterWhere(['like','`invite_code_mine`',yii::$app->request->get('invite_code_mine')]);
        $query->andFilterWhere(['like','`invite_code_used`',yii::$app->request->get('invite_code_used')]);
        $query->andFilterWhere(['like','`district`',yii::$app->request->get('district')]);
        $query->andFilterWhere(['=','`is_lock`',yii::$app->request->get('is_lock')]);
        $systimeStart = yii::$app->request->get('systime_start');
        if($systimeStart){
            $query->andFilterWhere(['>=','`systime`',strtotime($systimeStart)]);
        }
        $systimeEnd = yii::$app->request->get('systime_end');
        if($systimeEnd){
            $query->andFilterWhere(['<=','`systime`',strtotime($systimeEnd) + 3600*24]);
        }
        //查询条件结束
        $data = $query->asArray()->all();
        //print_r($data);exit;

        $excel = new Excel();
        $excel->setHeader([
            'creator'=>'皓峰通讯',
            'lastModifiedBy'=>'hao feng tong xun',
            'title'=>'vip_promotion_sign',
            'subject'=>'vip_promotion_sign',
            'description'=>'vip_promotion_sign',
            'keywords'=>'vip_promotion_sign',
            'category'=>'vip_promotion_sign'
        ]);

        //---向excel添加表头-------------------------------------
        foreach($excHeaders as $lineData){
            $excel->addLineToExcel($lineData);
        }

        //---向excel添加具体数据----------------------------------
        foreach($data as $item){
            $item['sex'] = $item['sex'] == 1 ? '男' : '女';
            $item['is_lock'] = $item['is_lock'] == 1 ? '锁定' : '正常';
            $item['systime'] = date('Y-m-d H:i:s',$item['systime']);
            $lineData = [];
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
        header("Content-Disposition: attachment; filename=".iconv('utf-8','gbk','微信推广活动注册信息导出.xls'));
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }




}