<?php
/**
 * @Desc: 微信推广活动->【后台管理系统】->【邀请信息管理】 控制器
 * @date:	2016-03-09
 */
namespace backend\modules\promotion\controllers;
use backend\controllers\BaseController;
use yii;
use yii\data\Pagination;
use backend\models\VipPromotionSign;
use common\models\Excel;

class InviteInfoController extends BaseController{

    /*
     * 访问“邀请信息管理”视图
     */
    public function actionIndex(){
        $buttons = $this->getCurrentActionBtn();
        return $this->render('index',[
            'buttons'=>$buttons,
        ]);
	}

    /**
     * 获取邀请信息列表
     */
    public function actionGetList(){
        //自连接查询
        $sql = "
            SELECT
                a.id,a.client AS sponsor,a.mobile AS sponsor_mobile,
                b.client AS receiver,b.mobile AS receiver_mobile,b.systime
            FROM `cs_vip_promotion_sign` a
            LEFT JOIN `cs_vip_promotion_sign` b ON a.invite_code_mine = b.invite_code_used
            WHERE b.code != '' AND b.invite_code_mine != '' AND b.invite_code_used != ''
        ";
        //查询条件开始
        if(yii::$app->request->get('sponsor')){
            $sql .= " AND a.client LIKE '%".(yii::$app->request->get('sponsor'))."%' ";
        }
        if(yii::$app->request->get('receiver')){
            $sql .= " AND b.client LIKE '%".(yii::$app->request->get('receiver'))."%' ";
        }
        if(yii::$app->request->get('mobile')){
            $sql .= " AND (a.mobile LIKE '%".(yii::$app->request->get('mobile'))."%' OR b.mobile LIKE '%".(yii::$app->request->get('mobile'))."%') ";
        }
        if(yii::$app->request->get('systime_start')){
            $sql .= " AND b.systime >= ".strtotime(yii::$app->request->get('systime_start'));
        }
        if(yii::$app->request->get('systime_end')){
            $sql .= " AND b.systime <= ".(strtotime(yii::$app->request->get('systime_end')) + 3600*24);
        }
        //查询条件结束
        //排序
        $sortColumn = yii::$app->request->get('sort');
        $sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
        $sql .= " ORDER BY ";
        switch($sortColumn){
            case 'sponsor':
                $sql .= "a.client ".$sortType; break;
            case 'sponsor_mobile':
                $sql .= "a.mobile ".$sortType; break;
            case 'receiver':
                $sql .= "b.client ".$sortType; break;
            case 'receiver_mobile':
                $sql .= "b.mobile ".$sortType; break;
            case 'systime':
                $sql .= "b.systime ".$sortType; break;
            default:
                $sql .= "b.systime DESC"; break;
        }
        $total = VipPromotionSign::findBySql($sql)->count();
        //分页
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $sql .= " LIMIT ".$pages->offset.",".$pages->limit;
        $data =  VipPromotionSign::findBySql($sql)->asArray()->all();
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        return json_encode($returnArr);
    }


    



    /**
     * 导出Excel
     */
    public function actionExportGridData(){
        // 构建导出的excel表头
        $excHeaders = [
            [
                ['content'=>'邀请发起人','font-weight'=>true,'width'=>'15'],
                ['content'=>'发起人手机号','font-weight'=>true,'width'=>'15'],
                ['content'=>'受邀人','font-weight'=>true,'width'=>'15'],
                ['content'=>'受邀人手机号','font-weight'=>true,'width'=>'15'],
                ['content'=>'受邀人注册日期','font-weight'=>true,'width'=>'25']
            ]
        ];

        // 要查的字段，与导出的excel表头对应
        //自连接查询
        $sql = "
            SELECT
                a.client AS sponsor,a.mobile AS sponsor_mobile,
                b.client AS receiver,b.mobile AS receiver_mobile,b.systime
            FROM `cs_vip_promotion_sign` a
            LEFT JOIN `cs_vip_promotion_sign` b ON a.invite_code_mine = b.invite_code_used
            WHERE b.code != '' AND b.invite_code_mine != '' AND b.invite_code_used != ''
        ";
        //查询条件开始
        if(yii::$app->request->get('sponsor')){
            $sql .= " AND a.client LIKE '%".(yii::$app->request->get('sponsor'))."%' ";
        }
        if(yii::$app->request->get('receiver')){
            $sql .= " AND b.client LIKE '%".(yii::$app->request->get('receiver'))."%' ";
        }
        if(yii::$app->request->get('mobile')){
            $sql .= " AND (a.mobile LIKE '%".(yii::$app->request->get('mobile'))."%' OR b.mobile LIKE '%".(yii::$app->request->get('mobile'))."%') ";
        }
        if(yii::$app->request->get('systime_start')){
            $sql .= " AND b.systime >= ".strtotime(yii::$app->request->get('systime_start'));
        }
        if(yii::$app->request->get('systime_end')){
            $sql .= " AND b.systime <= ".(strtotime(yii::$app->request->get('systime_end')) + 3600*24);
        }
        //查询条件结束
        //默认按受邀人注册时间升序排列
        $sql .= " ORDER BY b.systime ASC ";
        $data =  VipPromotionSign::findBySql($sql)->asArray()->all();
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
        header("Content-Disposition: attachment; filename=".iconv('utf-8','gbk','微信推广活动邀请信息导出.xls'));
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }




}